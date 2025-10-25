<?php
/**
 * Send Review reminder email.
 *
 * @package XTS
 */

if ( ! class_exists( 'XTS_Email_Review_Reminder' ) ) :

	/**
	 * Send Review reminder email.
	 */
	class XTS_Email_Review_Reminder extends WC_Email {
		/**
		 * Instance of stdClass with email data.
		 *
		 * @var object;
		 */
		public $object;

		/**
		 * User name.
		 *
		 * @var string $user_name
		 */
		public $user_name;

		/**
		 * Constructor.
		 */
		public function __construct() {
			if ( ! woodmart_get_opt( 'review_reminder_enabled' ) ) {
				return;
			}

			$this->id          = 'woodmart_review_reminder_email';
			$this->title       = esc_html__( 'Review reminder', 'woodmart' );
			$this->description = esc_html__( 'This email is sent to customers as a reminder to leave a review for their recent purchase.', 'woodmart' );

			$this->heading = wp_kses_post( __( 'Tell us what you think - your feedback means the world!', 'woodmart' ) );
			$this->subject = wp_kses_post( __( 'Tell us what you think - your feedback means the world!', 'woodmart' ) );

			$this->template_html  = 'emails/review-reminder.php';
			$this->template_plain = 'emails/plain/review-reminder.php';

			// Triggers for this email.
			add_action( 'woodmart_send_review_reminder_notification', array( $this, 'trigger' ) );

			add_filter( 'woodmart_emails_list', array( $this, 'register_woodmart_email' ) );

			// Call parent constructor.
			parent::__construct();
		}

		/**
		 * Register woodmart email.
		 *
		 * @param array $email_class List of Woodmart emails.
		 *
		 * @return array
		 */
		public function register_woodmart_email( $email_class ) {
			$email_class[] = get_class( $this );

			return $email_class;
		}

		/**
		 * Method triggered to send email.
		 *
		 * @param object $email_data data.
		 *
		 * @return void
		 */
		public function trigger( $email_data ) {
			$this->object    = $email_data;
			$this->recipient = $this->object->email;

			if ( ! empty( $this->object->language ) ) {
				do_action( 'wpml_switch_language', $this->object->language );
			}

			$user = get_user_by( 'email', $this->recipient );

			if ( $user instanceof WP_User ) {
				$user_name = $user->display_name;
			} elseif ( ! empty( $this->object->user_first_name ) || ! empty( $this->object->user_last_name ) ) {
				$user_name = $this->object->user_first_name . ' ' . $this->object->user_last_name;
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
			$unsubscribe_token = hash_hmac( 'sha256', $this->object->email, 'woodmart_review_reminder_unsubscribe' );

			return add_query_arg(
				array(
					'token'  => $unsubscribe_token,
					'email'  => $this->object->email,
					'action' => 'woodmart_review_reminder_unsubscribe',
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

return new XTS_Email_Review_Reminder();
