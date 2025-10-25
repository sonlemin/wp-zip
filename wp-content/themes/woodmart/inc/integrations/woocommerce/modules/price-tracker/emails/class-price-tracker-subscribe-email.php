<?php
/**
 * Send price tracker email.
 *
 * @package XTS
 */

if ( ! class_exists( 'XTS_Email_Price_Tracker_Subscribe' ) ) :

	/**
	 * Send a letter stating that the user has successfully subscribed.
	 */
	class XTS_Email_Price_Tracker_Subscribe extends WC_Email {
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
		 * Product price html for render.
		 *
		 * @var string Price html for render.
		 */
		public $product_price_html;

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
				return;
			}

			$this->id          = 'woodmart_price_tracker_subscribe_email';
			$this->title       = esc_html__( 'Price tracker alert confirmed', 'woodmart' );
			$this->description = esc_html__( 'This email confirms that the customer has successfully subscribed to price drop alerts for this product.', 'woodmart' );

			$this->template_html  = 'emails/price-tracker-subscribe.php';
			$this->template_plain = 'emails/plain/price-tracker-subscribe.php';

			// Triggers for this email.
			add_action( 'woodmart_send_price_tracker_subscribe_notification', array( $this, 'trigger' ), 10, 4 );

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
			return __( 'Your subscription to price drop notifications is now active.', 'woodmart' );
		}

		/**
		 * Get email heading line dynamically to trigger wpml translations.
		 */
		public function get_heading() {
			return __( 'You have successfully subscribed to price drop notifications.', 'woodmart' );
		}

		/**
		 * Method triggered to send email.
		 *
		 * @param string     $email This user email.
		 * @param WC_Product $product List of this user subscriptions.
		 * @param string     $email_language Current user language.
		 *
		 * @return void
		 */
		public function trigger( $email, $product, $email_language = '', $product_price_html = '' ) {
			$this->recipient          = $email;
			$this->object             = $product;
			$this->product_price_html = $product_price_html;

			if ( ! empty( $email_language ) ) {
				do_action( 'wpml_switch_language', $email_language );
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

			$this->form_fields['enabled']['default'] = 'no';

			unset( $this->form_fields['additional_content'] );
		}
	}

endif;

return new XTS_Email_Price_Tracker_Subscribe();
