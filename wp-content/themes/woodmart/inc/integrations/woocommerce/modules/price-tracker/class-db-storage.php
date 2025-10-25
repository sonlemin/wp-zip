<?php
/**
 * Database storage.
 *
 * @package woodmart
 */

namespace XTS\Modules\Price_Tracker;

use XTS\Singleton;
use WC_Product;

/**
 * Database storage class.
 */
class DB_Storage extends Singleton {
	/**
	 * Price tracker table name.
	 *
	 * @const string
	 */
	const PRICE_TRACKING_TABLE = 'woodmart_price_tracker';

	/**
	 * Subscription statuses.
	 */
	const STATUS_SIGNED     = 'signed';
	const STATUS_DISCOUNTED = 'discounted';
	const STATUS_SENT       = 'sent';

	/**
	 * Constructor.
	 */
	public function init() {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
			return;
		}

		self::define_tables();

		if ( ! get_option( 'wd_price_tracker_installed' ) ) {
			add_action( 'admin_init', array( __CLASS__, 'install' ), 100 );
		}
	}

	/**
	 * Create an entry in the price_tracker table.
	 *
	 * @param array $data List of args for saved in db.
	 *
	 * @return int|false ID recording or false on failure.
	 */
	public function create_subscription( $data ) {
		global $wpdb;

		// Validate required fields.
		$required_fields = array( 'user_email', 'product_id' );

		foreach ( $required_fields as $field ) {
			if ( empty( $data[ $field ] ) ) {
				return false;
			}
		}

		// Sanitize data.
		$data = $this->sanitize_subscription_data( $data );

		$result = $wpdb->insert( $wpdb->wd_price_tracker, $data );

		$this->clear_cache();

		return $result ? $wpdb->insert_id : false;
	}

	/**
	 * Sanitize subscription data before database operations.
	 *
	 * @param array $data Raw subscription data.
	 * @return array Sanitized data.
	 */
	private function sanitize_subscription_data( $data ) {
		$sanitized = array();

		if ( isset( $data['user_id'] ) ) {
			$sanitized['user_id'] = absint( $data['user_id'] );
		}

		if ( isset( $data['user_email'] ) ) {
			$sanitized['user_email'] = sanitize_email( $data['user_email'] );
		}

		if ( isset( $data['product_id'] ) ) {
			$sanitized['product_id'] = absint( $data['product_id'] );
		}

		if ( isset( $data['variation_id'] ) ) {
			$sanitized['variation_id'] = absint( $data['variation_id'] );
		}

		if ( isset( $data['product_price'] ) ) {
			$sanitized['product_price'] = wc_format_decimal( $data['product_price'] );
		}

		if ( isset( $data['desired_price'] ) ) {
			$sanitized['desired_price'] = wc_format_decimal( $data['desired_price'] );
		}

		if ( isset( $data['subscribe_status'] ) ) {
			$sanitized['subscribe_status'] = in_array( $data['subscribe_status'], array( self::STATUS_SIGNED, self::STATUS_DISCOUNTED, self::STATUS_SENT ), true )
				? $data['subscribe_status']
				: self::STATUS_SIGNED;
		}

		if ( isset( $data['email_language'] ) ) {
			$sanitized['email_language'] = sanitize_text_field( $data['email_language'] );
		}

		if ( isset( $data['email_currency'] ) ) {
			$sanitized['email_currency'] = sanitize_text_field( $data['email_currency'] );
		}

		if ( isset( $data['unsubscribe_token'] ) ) {
			$sanitized['unsubscribe_token'] = sanitize_text_field( $data['unsubscribe_token'] );
		}

		return $sanitized;
	}

	/**
	 * Retrieves the count of active price tracker subscriptions for the current user.
	 *
	 * @return int Number of active subscriptions for the current user.
	 */
	public function get_subscription_count_for_current_user() {
		global $wpdb;

		return $wpdb->get_var( // phpcs:ignore.
			$wpdb->prepare(
				"SELECT COUNT(list_id) 
				FROM $wpdb->wd_price_tracker
				WHERE user_id = %d",
				array(
					get_current_user_id(),
				)
			)
		);
	}

	/**
	 * Get list of signed variation product ids by user id.
	 *
	 * @param int|string $product_id Product id.
	 * @param int|string $user_id User id.
	 *
	 * @return array
	 */
	public function get_signed_variations_by_user_id( $product_id, $user_id ) {
		global $wpdb;

		$results = $wpdb->get_col( // phpcs:ignore.
			$wpdb->prepare(
				"SELECT variation_id FROM {$wpdb->wd_price_tracker} WHERE product_id = %d AND user_id = %d",
				array( $product_id, $user_id )
			)
		);

		return array_map( 'intval', $results );
	}

	/**
	 * Retrieves a grouped list of price tracker subscriptions that are ready to be sent.
	 *
	 * @param int $emails_limit The maximum number of unique user emails to process.
	 * @param int $product_limit The maximum number of unique products to process.
	 *
	 * @return array Grouped subscriptions by user email and list ID.
	 */
	public function get_subscriptions_to_send( $emails_limit, $product_limit ) {
		global $wpdb;

		$emails_limit  = absint( $emails_limit );
		$product_limit = absint( $product_limit );

		if ( $emails_limit <= 0 || $product_limit <= 0 ) {
			return array();
		}

		$cache_key = 'woodmart_price_tracker_subscriptions_' . md5( $emails_limit . '_' . $product_limit );
		$cached    = wp_cache_get( $cache_key, self::PRICE_TRACKING_TABLE );

		if ( false !== $cached ) {
			return $cached;
		}

		$emails = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT user_email
				FROM {$wpdb->wd_price_tracker}
				WHERE subscribe_status = %s
				AND is_sent = %s
				ORDER BY user_email
				LIMIT %d",
				self::STATUS_DISCOUNTED,
				'no',
				$emails_limit
			)
		);

		if ( empty( $emails ) ) {
			$this->clear_cache();

			return array();
		}

		$safe_emails = array();

		foreach ( $emails as $email ) {
			if ( is_email( $email ) ) {
				$safe_emails[] = $wpdb->prepare( '%s', $email );
			}
		}

		if ( empty( $safe_emails ) ) {
			return array();
		}

		$emails_in     = implode( ',', $safe_emails );
		$subscriptions = array();

		$all_subscriptions = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT *
				FROM {$wpdb->wd_price_tracker}
				WHERE user_email IN ($emails_in)
				AND subscribe_status = %s
				AND is_sent = %s
				ORDER BY user_email, list_id",
				self::STATUS_DISCOUNTED,
				'no'
			)
		);

		foreach ( $all_subscriptions as $subscription ) {
			if ( ! isset( $subscriptions[ $subscription->user_email ] ) ) {
				$subscriptions[ $subscription->user_email ] = array();
			}

			if ( count( $subscriptions[ $subscription->user_email ] ) < $product_limit ) {
				$subscriptions[ $subscription->user_email ][] = $subscription;
			}
		}

		if ( ! empty( $subscriptions ) ) {
			wp_cache_set( $cache_key, $subscriptions, self::PRICE_TRACKING_TABLE, 300 );
		}

		return $subscriptions;
	}

	/**
	 * Get subscriptions by user id.
	 *
	 * @param string|int $user_id User id.
	 * @param int        $page What is the pagination page.
	 *
	 * @return stdClass[]|null
	 */
	public function get_subscriptions_user_id( $user_id, $page ) {
		global $wpdb;

		$user_id = absint( $user_id );

		if ( ! $user_id ) {
			return array();
		}

		$items_per_page = absint( apply_filters( 'woodmart_price_tracker_per_page', 12 ) );
		$page           = max( 1, absint( $page ) );
		$offset         = ( $page - 1 ) * $items_per_page;

		$results = $wpdb->get_results( // phpcs:ignore.
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->wd_price_tracker}
				WHERE user_id = %d
				ORDER BY created_date_gmt DESC
				LIMIT %d OFFSET %d",
				array(
					$user_id,
					$items_per_page,
					$offset,
				)
			)
		);

		return $results;
	}

	/**
	 * Updates the subscription price and status for a given product and variation.
	 * If the new price of the product is higher than the expected price (product_price or desired_price), the status will change to 'signed'.
	 * Otherwise, the status is assigned to 'discounted'.
	 *
	 * @param float $new_price     The new price to update.
	 * @param int   $post_id       The product ID.
	 * @param int   $variation_id  The variation ID (default 0).
	 *
	 * @return int|false Number of rows updated, or false on error.
	 */
	public function update_subscription_price( $new_price, $post_id, $variation_id = 0 ) {
		global $wpdb;

		if ( ! is_numeric( $new_price ) || $new_price < 0 ) {
			return false;
		}

		$post_id      = absint( $post_id );
		$variation_id = absint( $variation_id );

		if ( ! $post_id ) {
			return false;
		}

		$new_price = wc_format_decimal( $new_price );

		$where_clause = 'product_id = %d';
		$where_params = array( $post_id );

		if ( $variation_id > 0 ) {
			$where_clause  .= ' AND variation_id = %d';
			$where_params[] = $variation_id;
		} else {
			$where_clause .= ' AND (variation_id IS NULL OR variation_id = 0)';
		}

		$desired_price_condition = '';
		if ( woodmart_get_opt( 'price_tracker_desired_price' ) ) {
			$desired_price_condition = $wpdb->prepare(
				"AND (desired_price IS NULL OR desired_price = '' OR CAST(desired_price AS DECIMAL(20,6)) >= %s)",
				$new_price
			);
		}

		$query = $wpdb->prepare(
			"UPDATE {$wpdb->wd_price_tracker}
			SET
				product_new_price = CASE
					WHEN CAST(product_price AS DECIMAL(20,6)) > %s {$desired_price_condition}
					THEN %s
					ELSE NULL
				END,
				subscribe_status = CASE
					WHEN CAST(product_price AS DECIMAL(20,6)) > %s {$desired_price_condition}
					THEN %s
					ELSE %s
				END,
				is_sent = %s
			WHERE {$where_clause}
				AND (product_new_price IS NULL OR product_new_price != %s)",
			array_merge(
				array( $new_price, $new_price, $new_price, self::STATUS_DISCOUNTED, self::STATUS_SIGNED, 'no' ),
				$where_params,
				array( $new_price )
			)
		);

		$result = $wpdb->query( $query );

		if ( $result ) {
			$this->clear_cache();
		}

		return $result;
	}

	/**
	 * Update the sent status of multiple subscriptions.
	 *
	 * @param array $list_ids Array of subscription list IDs.
	 *
	 * @return int|false Number of rows updated, or false on error.
	 */
	public function update_subscriptions_sent_status( $list_ids ) {
		global $wpdb;

		if ( empty( $list_ids ) || ! is_array( $list_ids ) ) {
			return false;
		}

		// Validate and sanitize IDs.
		$list_ids = array_filter( array_map( 'absint', $list_ids ) );

		if ( empty( $list_ids ) ) {
			return false;
		}

		$placeholders = implode( ',', array_fill( 0, count( $list_ids ), '%d' ) );

		$result = $wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->wd_price_tracker}
					SET subscribe_status = %s,
						is_sent = %s
					WHERE list_id IN ($placeholders)",
				array_merge( array( self::STATUS_SENT, 'yes' ), $list_ids )
			)
		);

		// Clear cache after updates.
		$this->clear_cache();

		return $result;
	}

	/**
	 * Update the desired price for a product and variation.
	 *
	 * @param int|string $product_id Product id.
	 * @param int|string $variation_id Variation id.
	 * @param float      $desired_price Desired price.
	 *
	 * @return int|false Number of rows updated, or false on error.
	 */
	public function update_price_tracker_desired_price( $product_id, $variation_id, $desired_price ) {
		global $wpdb;

		$product_id   = absint( $product_id );
		$variation_id = absint( $variation_id );

		if ( ! $product_id ) {
			return false;
		}

		if ( ! is_numeric( $desired_price ) || $desired_price < 0 ) {
			return false;
		}

		$desired_price = wc_format_decimal( $desired_price );

		$result = $wpdb->update(
			$wpdb->wd_price_tracker,
			array( 'desired_price' => $desired_price ),
			array(
				'product_id'   => $product_id,
				'variation_id' => $variation_id,
				'user_id'      => get_current_user_id(),
			),
			array( '%s' ),
			array( '%d', '%d', '%d' )
		);

		if ( $result ) {
			$this->clear_cache();
		}

		return $result;
	}

	/**
	 * Update user id by unsubscribe token.
	 *
	 * @param string $unsubscribe_token Unsubscribe token.
	 * @param int    $user_id User id.
	 *
	 * @return int|false Number of rows updated, or false on error.
	 */
	public function update_user_id_by_token( $unsubscribe_token, $user_id ) {
		global $wpdb;

		$result = $wpdb->update( // phpcs:ignore.
			$wpdb->wd_price_tracker,
			array( 'user_id' => $user_id ),
			array( 'unsubscribe_token' => $unsubscribe_token )
		);

		return $result;
	}

	/**
	 * Delete subscribe by unsubscribe token.
	 *
	 * @param string $token Unsubscribe token.
	 */
	public function unsubscribe_by_token( $token ) {
		global $wpdb;

		if ( empty( $token ) || ! is_string( $token ) ) {
			return false;
		}

		$token = sanitize_text_field( $token );

		return $wpdb->delete(
			$wpdb->wd_price_tracker,
			array( 'unsubscribe_token' => $token ),
			array( '%s' )
		);
	}

	/**
	 * Delete subscribe by product id.
	 *
	 * @param string $id Product id.
	 */
	public function unsubscribe_by_product_id( $id ) {
		global $wpdb;

		$product = wc_get_product( $id );

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		return $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->wd_price_tracker}
				WHERE product_id = %d
				OR variation_id = %d",
				$id,
				$id
			)
		);
	}

	/**
	 * Unsubscribes a user from price tracker by their email address.
	 *
	 * @param string $user_email The email address of the user to unsubscribe.
	 * @return int|false Number of rows affected, or false on error.
	 */
	public function unsubscribe_by_email( $user_email ) {
		global $wpdb;

		if ( ! is_email( $user_email ) ) {
			return false;
		}

		return $wpdb->delete(
			$wpdb->wd_price_tracker,
			array( 'user_email' => $user_email ),
			array( '%s' )
		);
	}

	/**
	 * Delete single subscription by user email and product id.
	 *
	 * @param int|string $id Product id.
	 * @param string     $user_email User email.
	 *
	 * @return int ID deleted recording.
	 */
	public function unsubscribe_by_user_email_and_product_id( $id, $user_email ) {
		global $wpdb;

		return $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->wd_price_tracker}
				WHERE
				user_email = %s
				AND	( product_id = %d OR variation_id = %d )",
				$user_email,
				$id,
				$id
			)
		);
	}

	/**
	 * Delete subscribe for current user.
	 *
	 * @param int|string $product_id Product id. It can be either the id of a variation or a simple product.
	 *
	 * @return int ID deleted recording.
	 */
	public function unsubscribe_current_user( $product_id ) {
		global $wpdb;

		if ( ! $product_id || ! get_current_user_id() ) {
			return false;
		}

		$result = $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->wd_price_tracker}
				WHERE user_id = %d
				AND (
					variation_id = %d
					OR (
						product_id = %d
						AND (variation_id IS NULL OR variation_id = 0)
					)
				)",
				get_current_user_id(),
				$product_id,
				$product_id
			)
		);

		if ( $result ) {
			$this->clear_cache();
		}

		return $result;
	}

	/**
	 * Check is signed product.
	 *
	 * @param int|string $product_id Product id.
	 * @param int|string $user_id User id.
	 *
	 * @return bool
	 */
	public function check_is_signed_product_by_user_id( $product_id, $user_id ) {
		global $wpdb;

		$results = $wpdb->get_row( // phpcs:ignore.
			$wpdb->prepare(
				"SELECT list_id FROM {$wpdb->wd_price_tracker} WHERE ( product_id = %d OR variation_id = %d ) AND user_id = %d",
				array( $product_id, $product_id, $user_id )
			)
		);

		return ! empty( $results );
	}

	/**
	 * Check if a subscription exists for this user.
	 *
	 * @param int|string $product_id Product id.
	 * @param string     $user_email User email.
	 *
	 * @return bool
	 */
	public function check_subscription_exists( $product_id, $user_email ) {
		global $wpdb;

		$where_user_id = '';
		$product_id    = absint( $product_id );
		$user_email    = sanitize_email( $user_email );

		if ( ! is_email( $user_email ) ) {
			return false;
		}

		if ( is_user_logged_in() ) {
			$where_user_id = $wpdb->prepare(
				'AND user_id = %d',
				get_current_user_id()
			);
		}

		$results = $wpdb->get_results( // phpcs:ignore.
			$wpdb->prepare(
				"SELECT list_id
				FROM {$wpdb->wd_price_tracker}
				WHERE ( product_id = %d OR variation_id = %d )
				AND user_email = %s
				{$where_user_id}",
				array( $product_id, $product_id, $user_email )
			)
		);

		return ! empty( $results );
	}

	/**
	 * Check if the desired price is the same as the one already set for the product and variation.
	 *
	 * @param int|string $product_id Product id.
	 * @param int|string $variation_id Variation id.
	 * @param float      $desired_price Desired price.
	 *
	 * @return bool
	 */
	public function check_is_same_desired_price( $product_id, $variation_id, $desired_price ) {
		global $wpdb;

		$results = $wpdb->get_results( // phpcs:ignore.
			$wpdb->prepare(
				"SELECT list_id
				FROM {$wpdb->wd_price_tracker}
				WHERE product_id = %d
				AND variation_id = %d
				AND desired_price = %s
				AND user_id = %d",
				array(
					$product_id,
					$variation_id,
					$desired_price,
					get_current_user_id(),
				)
			)
		);

		return ! empty( $results );
	}

	/**
	 * Clear cache for price tracker.
	 *
	 * This method clears the cache for the price tracker table and any related cached data.
	 */
	private function clear_cache() {
		wp_cache_delete( self::PRICE_TRACKING_TABLE );

		$emails_limit  = apply_filters( 'woodmart_send_price_tracker_email_limited', 20 );
		$product_limit = apply_filters( 'woodmart_send_price_tracker_email_product_limited', 10 );

		wp_cache_delete(
			'woodmart_price_tracker_subscriptions_' . md5( $emails_limit . '_' . $product_limit ),
			self::PRICE_TRACKING_TABLE
		);
	}

	/**
	 * Define tables aliases.
	 *
	 * @return void
	 */
	public static function define_tables() {
		global $wpdb;

		$wpdb->wd_price_tracker = $wpdb->prefix . self::PRICE_TRACKING_TABLE;
		$wpdb->tables[]         = self::PRICE_TRACKING_TABLE;
	}

	/**
	 * Create table.
	 */
	public static function install() {
		global $wpdb;

		if ( ! isset( $_GET['settings-updated'] ) ) {
			return;
		}

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . self::PRICE_TRACKING_TABLE;

		$sql = "CREATE TABLE $table_name (
			list_id BIGINT NOT NULL AUTO_INCREMENT,
			user_id BIGINT,
			user_email VARCHAR(100) NOT NULL,
			product_id BIGINT NOT NULL,
			variation_id BIGINT DEFAULT 0,
			product_price DECIMAL(20,6),
			product_new_price DECIMAL(20,6),
			desired_price DECIMAL(20,6),
			subscribe_status ENUM('signed', 'discounted', 'sent') DEFAULT 'signed',
			is_sent ENUM('yes', 'no') DEFAULT 'no',
			email_language VARCHAR(10),
			email_currency VARCHAR(10),
			unsubscribe_token VARCHAR(100),
			created_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			created_date_gmt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (list_id),
			UNIQUE KEY unsubscribe_token (unsubscribe_token),
			KEY idx_user_product (user_id, product_id, variation_id),
			KEY idx_email_status (user_email, subscribe_status, is_sent),
			KEY idx_status_sent (subscribe_status, is_sent),
			KEY idx_product_variation (product_id, variation_id)
		) $charset_collate;";

		dbDelta( $sql );
		update_option( 'wd_price_tracker_installed', true, false );
	}
}

DB_Storage::get_instance();
