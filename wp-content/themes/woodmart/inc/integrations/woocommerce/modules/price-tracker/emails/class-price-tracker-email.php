<?php
/**
 * Send price tracker email.
 *
 * @package XTS
 */

if ( ! class_exists( 'XTS_Email_Price_Tracker' ) ) :

	/**
	 * Send price tracker email.
	 */
	class XTS_Email_Price_Tracker extends WC_Email {
		/**
		 * True when the email notification is sent to customers.
		 *
		 * @var bool
		 */
		protected $customer_email = true;

		/**
		 * WC_Product instance.
		 *
		 * @var WC_Product;
		 */
		public $object;

		/**
		 * User name.
		 *
		 * @var string $user_name
		 */
		public $user_name;

		/**
		 * Dummy product for preview email.
		 *
		 * @var WC_Product $dummy_product - Instance of WC_Product class.
		 */
		public $dummy_product;

		/**
		 * Email language.
		 *
		 * @var string Email language.
		 */
		public $language;

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
				return;
			}

			$this->id          = 'woodmart_price_tracker_email';
			$this->title       = esc_html__( 'Price tracker alert', 'woodmart' );
			$this->description = esc_html__( 'This email is sent to customers when the price drops on products they are watching.', 'woodmart' );

			$this->template_html  = 'emails/price-tracker.php';
			$this->template_plain = 'emails/plain/price-tracker.php';

			// Triggers for this email.
			add_action( 'woodmart_send_price_tracker_notification', array( $this, 'trigger' ), 10, 3 );

			add_filter( 'woodmart_emails_list', array( $this, 'register_woodmart_email' ) );

			// Call parent constructor.
			parent::__construct();
		}

		/**
		 * Register woodmart email.
		 *
		 * @param array $email_class List of theme emails.
		 */
		public function register_woodmart_email( $email_class ) {
			$email_class[] = get_class( $this );

			return $email_class;
		}

		/**
		 * Get email subject line dynamically to trigger wpml translations.
		 */
		public function get_subject() {
			return __( 'Great news! The price has dropped on products you’re watching', 'woodmart' );
		}

		/**
		 * Get email heading line dynamically to trigger wpml translations.
		 */
		public function get_heading() {
			return __( 'Price drop alert for your favorite products!', 'woodmart' );
		}

		/**
		 * Method triggered to send email.
		 *
		 * @param string $email This user email.
		 * @param array  $subscriptions List of this user subscriptions.
		 * @param string $email_language Email language.
		 *
		 * @return void
		 */
		public function trigger( $email, $subscriptions, $email_language = 'default' ) {
			$this->recipient = $email;
			$this->object    = $subscriptions;
			$this->language  = $email_language;

			if ( 'default' !== $this->language ) {
				do_action( 'wpml_switch_language', $this->language );
			}

			$user = get_user_by( 'email', $this->recipient );

			if ( $user instanceof WP_User ) {
				$user_name = $user->display_name;
			} else {
				$user_name = esc_html__( 'Customer', 'woodmart' );
			}

			$this->user_name = $user_name;

			if ( ! $this->is_enabled() || ! $this->get_recipient() || ! $this->object ) {
				return;
			}

			$this->send(
				$this->get_recipient(),
				$this->get_subject(),
				$this->get_content(),
				$this->get_headers(),
				$this->get_attachments()
			);

			do_action( 'wpml_switch_language', apply_filters( 'wpml_default_language', null ) );
		}

		/**
		 * Get content html.
		 *
		 * @return string
		 */
		public function get_content_html() {
			ob_start();

			wc_get_template(
				$this->template_html,
				array(
					'email'            => $this,
					'email_heading'    => $this->get_heading(),
					'unsubscribe_link' => $this->get_unsubscribe_link(),
					'sent_to_admin'    => false,
					'plain_text'       => false,
				)
			);

			return ob_get_clean();
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			ob_start();

			wc_get_template(
				$this->template_plain,
				array(
					'email'            => $this,
					'email_heading'    => $this->get_heading(),
					'unsubscribe_link' => $this->get_unsubscribe_link(),
					'sent_to_admin'    => false,
					'plain_text'       => true,
				)
			);

			return ob_get_clean();
		}

		/**
		 * Get unsubscribe link.
		 * Create unsubscribe token if not exists.
		 *
		 * @return string Unsubscribe url.
		 */
		public function get_unsubscribe_link() {
			$unsubscribe_token = hash_hmac( 'sha256', $this->recipient, 'woodmart_price_tracker_unsubscribe_' . wp_salt() );

			return add_query_arg(
				array(
					'token'  => $unsubscribe_token,
					'email'  => $this->recipient,
					'action' => 'woodmart_price_tracker_unsubscribe',
				),
				wc_get_page_permalink( 'shop' )
			);
		}

		/**
		 * Init fields that will store admin preferences.
		 *
		 * @return void
		 */
		public function init_form_fields() {
			parent::init_form_fields();

			unset( $this->form_fields['additional_content'] );
		}
	}

endif;

return new XTS_Email_Price_Tracker();
