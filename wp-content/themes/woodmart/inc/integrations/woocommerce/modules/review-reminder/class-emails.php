<?php
/**
 * Review reminder class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Review_Reminder;

use XTS\Singleton;

/**
 * Review reminder class.
 */
class Emails extends Singleton {

	/**
	 * Init.
	 */
	public function init() {
		if ( ! woodmart_get_opt( 'review_reminder_enabled' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'unsubscribe_user' ) );

		add_filter( 'woocommerce_email_classes', array( $this, 'register_email' ) );
		add_action( 'woocommerce_init', array( $this, 'load_wc_mailer' ) );

		add_filter( 'woocommerce_prepare_email_for_preview', array( $this, 'prepare_email_for_preview' ) );

		add_action( 'woocommerce_order_status_completed', array( $this, 'schedule_review_reminder_emails' ), 10, 2 );
		add_action( 'woodmart_review_reminder_cron', array( $this, 'send_review_reminder_email' ) );

		if ( ! wp_next_scheduled( 'woodmart_review_reminder_cron' ) ) {
			wp_schedule_event( time(), apply_filters( 'woodmart_schedule_review_reminder_cron', 'hourly' ), 'woodmart_review_reminder_cron' );
		}
	}

	/**
	 * Unsubscribe after the user has followed the link from email.
	 */
	public function unsubscribe_user() {
		if ( ! isset( $_GET['token'] ) || ! isset( $_GET['email'] ) || ! isset( $_GET['action'] ) || 'woodmart_review_reminder_unsubscribe' !== $_GET['action']  ) { //phpcs:ignore
			return;
		}

		$redirect   = apply_filters( 'woodmart_review_reminder_after_unsubscribe_redirect', remove_query_arg( array( 'token', 'email', 'action' ) ) );
		$token      = woodmart_clean( $_GET['token'] ); //phpcs:ignore.
		$user_email = isset( $_GET['email'] ) ? sanitize_email( wp_unslash( $_GET['email'] ) ) : '';
		$result     = false;

		if ( ! empty( $user_email ) && $this->validate_unsubscribe_token( $user_email, $token ) ) {
			$result = woodmart_unsubscribe_user_from_mailing( $user_email, 'XTS_Email_Review_Reminder' );
		}

		if ( $result ) {
			wc_add_notice( esc_html__( 'You have successfully unsubscribed from product review reminder emails', 'woodmart' ), 'success' );
		} else {
			wc_add_notice( esc_html__( 'Failed to unsubscribe from this product review reminder emails', 'woodmart' ), 'error' );
		}

		wp_safe_redirect( $redirect );
		exit();
	}

	/**
	 * List of registered emails.
	 *
	 * @param array $emails List of registered emails.
	 *
	 * @return array
	 */
	public function register_email( $emails ) {
		$emails['XTS_Email_Review_Reminder'] = include WOODMART_THEMEROOT . '/inc/integrations/woocommerce/modules/review-reminder/emails/class-review-reminder-email.php';

		return $emails;
	}

	/**
	 * Load woocommerce mailer.
	 */
	public function load_wc_mailer() {
		add_action( 'woodmart_send_review_reminder', array( 'WC_Emails', 'send_transactional_email' ), 10, 4 );
	}

	/**
	 * Schedules review reminder emails for a WooCommerce order.
	 *
	 * Checks various conditions (such as previous reminders, user consent, and unsubscribed emails)
	 * before scheduling a reminder. Collects product information from the order and stores the
	 * reminder data in the WordPress options table. Updates order meta with the scheduled date.
	 *
	 * @param int             $order_id The ID of the WooCommerce order.
	 * @param \WC_Order|false $order    The WooCommerce order object.
	 */
	public function schedule_review_reminder_emails( $order_id, $order ) {
		if ( ! $order_id || ! $order ) {
			return;
		}

		$reminder_data = get_option( 'woodmart_review_reminder_data', array() );
		$user_email    = $order->get_billing_email();
		$reminder_sent = $order->get_meta( '_wd_review_reminder_sent' );

		if (
			woodmart_is_user_unsubscribed_from_mailing( $user_email, 'XTS_Email_Review_Reminder' ) ||
			in_array( $order_id, array_keys( $reminder_data ), true ) ||
			! empty( $reminder_sent )
		) {
			return;
		}

		$list = array();

		foreach ( $order->get_items() as $item ) {
			$product_id = $item->get_data()['product_id'];

			if ( $this->is_skip_product( $product_id, $user_email ) ) {
				continue;
			}

			$product = $item->get_product();

			$permalink = add_query_arg(
				array(
					'action' => 'wd_review_reminder',
				),
				$product->get_permalink() . '#tab-reviews'
			);

			$list[ $product_id ]['id']        = $product_id;
			$list[ $product_id ]['name']      = $product->get_name();
			$list[ $product_id ]['permalink'] = $permalink;
			$list[ $product_id ]['image_id']  = $product->get_image_id();
		}

		if ( empty( $list ) ) {
			return;
		}

		$scheduled_date = time() + intval( woodmart_get_opt( 'review_reminder_sending_timeframe', 7 ) ) * intval( woodmart_get_opt( 'review_reminder_sending_timeframe_period', DAY_IN_SECONDS ) );

		$reminder_data[ $order_id ] = array(
			'item_list'       => $list,
			'email'           => $user_email,
			'scheduled_date'  => $scheduled_date,
			'language'        => $this->get_user_language(),
			'user_first_name' => $order->get_billing_first_name(),
			'user_last_name'  => $order->get_billing_last_name(),
			'customer_id'     => $order->get_customer_id(),
		);

		$option_updated = update_option( 'woodmart_review_reminder_data', $reminder_data, false );

		if ( $option_updated ) {
			$order->update_meta_data( '_wd_review_reminder_scheduled_date', $scheduled_date );
			$order->save();
		}
	}

	/**
	 * Send review reminder email.
	 */
	public function send_review_reminder_email() {
		$reminder_data = get_option( 'woodmart_review_reminder_data', array() );

		if ( ! $reminder_data ) {
			return;
		}

		$counter        = 0;
		$emails_limited = apply_filters( 'woodmart_review_reminder_send_emails_limited', 20 );

		foreach ( $reminder_data as $order_id => $email_data ) {
			if (
				time() < intval( $email_data['scheduled_date'] ) ||
				woodmart_should_skip_subscription_email( $email_data['email'], $email_data['customer_id'] ) ||
				woodmart_is_user_unsubscribed_from_mailing( $email_data['email'], 'XTS_Email_Review_Reminder' )
			) {
				continue;
			}

			if ( ++$counter > $emails_limited ) {
				break;
			}

			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				return;
			}

			do_action( 'woodmart_send_review_reminder', (object) $email_data );

			unset( $reminder_data[ $order_id ] );

			$order->update_meta_data( '_wd_review_reminder_sent', time() );
			$order->delete_meta_data( '_wd_review_reminder_scheduled_date' );
			$order->save();
		}

		update_option( 'woodmart_review_reminder_data', $reminder_data, false );
	}

	/**
	 * Prepare email for preview.
	 *
	 * @param object $preview_email Email object.
	 *
	 * @return object
	 */
	public function prepare_email_for_preview( $preview_email ) {
		if ( 'XTS_Email_Review_Reminder' === get_class( $preview_email ) ) {
			$object = $this->get_dummy_email_object();

			$preview_email->set_object( $object );
			$preview_email->user_name = esc_html__( 'User Preview', 'woodmart' );
		}

		return $preview_email;
	}

	/**
	 * Get dummy email object.
	 *
	 * @return array
	 */
	public function get_dummy_email_object() {
		$data = array(
			'item_list'       => array(
				'123' => array(
					'name'      => __( 'Dummy product', 'woodmart' ),
					'id'        => '123',
					'permalink' => '#',
					'image_id'  => '0',
				),
			),
			'email'           => 'user_preview@example.com',
			'language'        => $this->get_user_language(),
			'user_first_name' => 'User',
			'user_last_name'  => 'Preview',
		);

		return (object) $data;
	}

	/**
	 * Check if product can be reviewed.
	 *
	 * @param integer $product_id The product ID.
	 * @param string  $user_email The user email.
	 *
	 * @return bool
	 */
	public function is_skip_product( $product_id, $user_email ) {
		$excluded_items = apply_filters( 'woodmart_review_reminder_excluded_items', array(), $product_id );

		return ! comments_open( $product_id ) || $this->is_user_has_commented( $product_id, $user_email ) || in_array( $product_id, $excluded_items, true );
	}

	/**
	 * Check if the user has already commented on the product.
	 *
	 * @param int    $product_id Product id.
	 * @param string $user_email User email.
	 *
	 * @return bool
	 */
	public function is_user_has_commented( $product_id, $user_email ) {
		$args = array(
			'post_id'      => $product_id,
			'author_email' => $user_email,
			'count'        => true,
			'status'       => 'approve',
			'type'         => 'review',
		);

		$count = get_comments( $args );

		return $count > 0;
	}

	/**
	 * Return the language of the current user
	 *
	 * @return string
	 */
	public function get_user_language() {
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			return apply_filters( 'wpml_current_language', null ); //phpcs:ignore.
		} else {
			return substr( get_bloginfo( 'language' ), 0, 2 );
		}
	}

	/**
	 * Validate the unsubscribe token for an email.
	 *
	 * @param string $email The email to validate.
	 * @param string $token The token to validate.
	 *
	 * @return bool True if the token is valid, false otherwise.
	 */
	public function validate_unsubscribe_token( $email, $token ) {
		$expected_token = hash_hmac( 'sha256', $email, 'woodmart_review_reminder_unsubscribe' );

		return hash_equals( $expected_token, $token );
	}
}

Emails::get_instance();
