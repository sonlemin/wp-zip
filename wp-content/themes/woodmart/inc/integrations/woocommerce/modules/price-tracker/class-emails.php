<?php
/**
 * Price tracker emails class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Price_Tracker;

use WC_Coupon;
use WC_Product;

/**
 * Price tracker emails class.
 */
class Emails {
	/**
	 * Instance of DB_Storage class.
	 *
	 * @var DB_Storage $db_storage - Instance of DB_Storage class.
	 */
	private $db_storage;

	/**
	 * Init.
	 */
	public function __construct() {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
			return;
		}

		$this->db_storage = DB_Storage::get_instance();

		add_action( 'init', array( $this, 'unsubscribe_user' ) );

		add_filter( 'woocommerce_email_classes', array( $this, 'register_email' ) );
		add_action( 'woocommerce_init', array( $this, 'load_wc_mailer' ) );

		add_filter( 'woocommerce_prepare_email_for_preview', array( $this, 'prepare_email_for_preview' ) );

		add_action( 'woodmart_price_tracker_cron', array( $this, 'send_price_tracker_email' ) );

		if ( ! wp_next_scheduled( 'woodmart_price_tracker_cron' ) ) {
			wp_schedule_event( time(), apply_filters( 'woodmart_schedule_send_price_tracker_email', 'hourly' ), 'woodmart_price_tracker_cron' );
		}
	}

	/**
	 * Unsubscribe after the user has followed the link from email.
	 */
	public function unsubscribe_user() {
		if ( ! isset( $_GET['token'] ) || ! isset( $_GET['email'] ) || ! isset( $_GET['action'] ) || 'woodmart_price_tracker_unsubscribe' !== $_GET['action'] ) { //phpcs:ignore
			return;
		}

		$redirect   = apply_filters( 'woodmart_price_tracker_after_unsubscribe_redirect', remove_query_arg( array( 'token', 'email', 'action' ) ) );
		$token      = woodmart_clean( $_GET['token'] ); //phpcs:ignore.
		$user_email = isset( $_GET['email'] ) ? sanitize_email( wp_unslash( $_GET['email'] ) ) : '';
		$result     = false;

		if ( ! empty( $user_email ) && $this->validate_unsubscribe_token( $user_email, $token ) ) {
			$result = $this->db_storage->unsubscribe_by_email( $user_email );
		}

		if ( $result ) {
			wc_add_notice( esc_html__( 'You’ve been unsubscribed from price tracker emails for this product', 'woodmart' ), 'success' );
		} else {
			wc_add_notice( esc_html__( 'Failed to unsubscribe from price tracker emails for this product', 'woodmart' ), 'error' );
		}

		wp_safe_redirect( $redirect );
		exit();
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
		$expected_token = hash_hmac( 'sha256', $email, 'woodmart_price_tracker_unsubscribe_' . wp_salt() );

		return hash_equals( $expected_token, $token );
	}

	/**
	 * List of registered emails.
	 *
	 * @param array $emails List of registered emails.
	 *
	 * @return array
	 */
	public function register_email( $emails ) {
		$emails['XTS_Email_Price_Tracker']           = include WOODMART_THEMEROOT . '/inc/integrations/woocommerce/modules/price-tracker/emails/class-price-tracker-email.php';
		$emails['XTS_Email_Price_Tracker_Subscribe'] = include WOODMART_THEMEROOT . '/inc/integrations/woocommerce/modules/price-tracker/emails/class-price-tracker-subscribe-email.php';

		return $emails;
	}

	/**
	 * Load woocommerce mailer.
	 */
	public function load_wc_mailer() {
		add_action( 'woodmart_send_price_tracker', array( 'WC_Emails', 'send_transactional_email' ), 10, 4 );
		add_action( 'woodmart_send_price_tracker_subscribe', array( 'WC_Emails', 'send_transactional_email' ), 10, 4 );
	}

	/**
	 * Prepare email for preview.
	 *
	 * @param object $preview_email Email object.
	 */
	public function prepare_email_for_preview( $preview_email ) {
		$preview_email_class = get_class( $preview_email );
		$dummy_product       = $this->get_dummy_product();

		if ( in_array( $preview_email_class, array( 'XTS_Email_Price_Tracker', 'XTS_Email_Price_Tracker_Subscribe' ), true ) ) {
			$preview_email->recipient = 'user_preview@example.com';
			$preview_email->user_name = esc_html__( 'User Preview', 'woodmart' );
		}

		if ( 'XTS_Email_Price_Tracker' === $preview_email_class ) {
			$preview_email->set_object( $this->get_dummy_data() );
			$preview_email->dummy_product = $dummy_product;
		}

		if ( 'XTS_Email_Price_Tracker_Subscribe' === $preview_email_class ) {
			$preview_email->set_object( $dummy_product );
			$preview_email->product_price_html = wc_price( 25 );
		}

		return $preview_email;
	}

	/**
	 * Send priced tracker cart email.
	 *
	 * @codeCoverageIgnore
	 */
	public function send_price_tracker_email() {
		$emails_limit          = apply_filters( 'woodmart_send_price_tracker_email_limited', 20 );
		$product_limit         = apply_filters( 'woodmart_send_price_tracker_email_product_limited', 10 );
		$subscriptions_to_send = $this->db_storage->get_subscriptions_to_send( $emails_limit, $product_limit );
		$subscriptions_to_send = $this->group_subscriptions_by_language( $subscriptions_to_send );

		if ( ! $subscriptions_to_send ) {
			return;
		}

		foreach ( $subscriptions_to_send as $email => $subscriptions ) {
			foreach ( $subscriptions as $email_language => $email_products ) {
				do_action( 'woodmart_send_price_tracker', $email, $email_products, $email_language );

				$this->db_storage->update_subscriptions_sent_status( array_keys( $email_products ) );
			}
		}
	}

	/**
	 * Retrieves subscription records from the database and groups them by the language they were sent in.
	 * If WPML is enabled, emails will be grouped depending on the data in the email_language column, otherwise this column will be ignored and all emails will be grouped with the default key.
	 * When WPML is enabled, this method converts the product id to the desired language.
	 * When Multicurrency is enabled, this method converts product prices to the required currency.
	 *
	 * @param $subscriptions_groped List of subscriptions grouped by user email.
	 *
	 * @return array.
	 */
	public function group_subscriptions_by_language( $subscriptions_groped ) {
		global $woocommerce_wpml;

		if ( empty( $subscriptions_groped ) ) {
			return array();
		}

		$grouped = array();

		foreach ( $subscriptions_groped as $email => $subscriptions ) {
			foreach ( $subscriptions as $subscription ) {
				$product       = wc_get_product( $subscription->variation_id ? $subscription->variation_id : $subscription->product_id );
				$product_price = $product->get_price();
				$desired_price = floatval( $subscription->desired_price );

				if (
					! $product instanceof WC_Product ||
					! $product->is_in_stock() ||
					$product_price > $subscription->product_price ||
					( ! empty( $desired_price ) && $product_price > $desired_price )
				) {
					continue;
				}

				$lang           = 'default';
				$email_currency = 'default';

				if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
					$lang = ! empty( $subscription->email_language ) ? $subscription->email_language : 'default';
				}

				if ( defined( 'WCML_VERSION' ) ) {
					$email_currency = ! empty( $subscription->email_currency ) ? $subscription->email_currency : 'default';
				}

				if ( defined( 'ICL_SITEPRESS_VERSION' ) && 'default' !== $lang ) {
					$subscription->product_id   = apply_filters( 'wpml_object_id', $subscription->product_id, 'product', true, $lang );
					$subscription->variation_id = apply_filters( 'wpml_object_id', $subscription->variation_id, 'product', true, $lang );
				}

				if ( defined( 'WCML_VERSION' ) && isset( $woocommerce_wpml ) && is_object( $woocommerce_wpml->multi_currency ) ) {
					$default_currency = $woocommerce_wpml->multi_currency->get_default_currency();

					if ( 'default' !== $email_currency && 'default' !== $email_currency && $default_currency !== $email_currency ) {
						$subscription->product_price     = $woocommerce_wpml->multi_currency->prices->convert_price_amount_by_currencies( $subscription->product_price, $default_currency, $email_currency );
						$subscription->product_new_price = $woocommerce_wpml->multi_currency->prices->convert_price_amount_by_currencies( $subscription->product_new_price, $default_currency, $email_currency );
						$subscription->desired_price     = $woocommerce_wpml->multi_currency->prices->convert_price_amount_by_currencies( $subscription->desired_price, $default_currency, $email_currency );
					}
				}

				$grouped[ $email ][ $lang ][ $subscription->list_id ] = $subscription;
			}
		}

		return $grouped;
	}

	/**
	 * Get a dummy data.
	 *
	 * @return array
	 */
	private function get_dummy_data() {
		$dummy_data = array(
			'list_id'           => '1',
			'user_id'           => '1',
			'user_email'        => 'user_preview@example.com',
			'product_id'        => '123',
			'variation_id'      => '123',
			'product_price'     => '123',
			'product_new_price' => '100',
			'subscribe_status'  => 'discounted',
			'is_sent'           => '',
			'email_language'    => '',
			'email_currency'    => '',
			'unsubscribe_token' => '',
			'created_date'      => '',
			'created_date_gmt'  => '',
		);

		return array( (object) $dummy_data );
	}

	/**
	 * Get a dummy product.
	 *
	 * @return WC_Product
	 */
	private function get_dummy_product() {
		$product = new WC_Product();
		$product->set_name( __( 'Dummy Product', 'woodmart' ) );
		$product->set_price( 25 );

		return $product;
	}
}

new Emails();
