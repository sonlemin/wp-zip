<?php
/**
 * Email Marketing consent class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Marketing_Consent;

use XTS\Singleton;
use WP_User;
use WP_Error;

/**
 * Email Marketing consent class.
 */
class Frontend extends Singleton {
	/**
	 * List of marketing emails.
	 *
	 * @var array $marketing_emails - List of marketing emails.
	 */
	public $marketing_emails = array();

	/**
	 * Instance of DB_Storage class.
	 *
	 * @var DB_Storage $db_storage - Instance of DB_Storage class.
	 */
	private $db_storage;

	/**
	 * Instance of Unsubscribed_Emails class.
	 *
	 * @var Unsubscribed_Emails $unsubscribed_emails - Instance of Unsubscribed_Emails class.
	 */
	private $unsubscribed_emails;

	/**
	 * Init.
	 */
	public function init() {
		$this->marketing_emails    = array(
			'XTS_Email_Wishlist_Back_In_Stock',
			'XTS_Email_Wishlist_On_Sale_Products',
			'XTS_Email_Wishlist_Promotional',
			'XTS_Email_Abandoned_Cart',
			'XTS_Email_Review_Reminder',
		);
		$this->db_storage          = DB_Storage::get_instance();
		$this->unsubscribed_emails = Unsubscribed_Emails::get_instance();

		add_action( 'woocommerce_register_form', array( $this, 'add_checkbox' ), 30 );
		add_action( 'woocommerce_edit_account_form_fields', array( $this, 'add_checkbox' ) );
		add_action( 'woocommerce_edit_account_form_fields', array( $this, 'add_individual_subscription_checkboxes' ) );
		add_action( 'woocommerce_review_order_before_submit', array( $this, 'add_checkbox' ) );

		add_action( 'woocommerce_created_customer', array( $this, 'add_subscription_on_registration' ) );
		add_action( 'woocommerce_save_account_details', array( $this, 'add_subscription_on_edit_account' ) );
		add_action( 'woocommerce_save_account_details', array( $this, 'add_individual_subscription_on_edit_account' ) );
		add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'add_subscription_on_placing_order' ) );
		add_action( 'wp_login', array( $this, 'update_subscription_user_id_on_login' ), 10, 2 );

		add_action( 'delete_user', array( $this, 'remove_subscription_on_user_delete' ) );
	}

	/**
	 * Adds a subscription consent checkbox to the WooCommerce form.
	 *
	 * Outputs a checkbox field for users to opt-in to marketing emails and updates.
	 *
	 * @return void
	 */
	public function add_checkbox() {
		$subscription_exists = false;

		if ( is_user_logged_in() ) {
			// Do not display the checkbox on the checkout page if the user is loggined.
			if ( is_checkout() ) {
				return;
			}

			$user_id             = get_current_user_id();
			$user_details        = get_userdata( $user_id );
			$email               = $user_details->user_email;
			$subscription_exists = $this->db_storage->check_subscription_exists( array( 'user_id' => $user_id ) );
		}

		$this->render_checkbox(
			array(
				'id'              => 'wd_email_subscription_consent',
				'label'           => esc_html__( 'I would like to receive marketing emails and updates.', 'woodmart' ),
				'checked_value'   => 1,
				'unchecked_value' => 0,
				'value'           => $subscription_exists ? 1 : 0,
				'class'           => array( 'form-row-wide', 'wd-email-sub-main' ),
				'input_class'     => array( 'wd-email-main-consent' ),
			)
		);
	}

	/**
	 * Adds individual subscription consent checkboxes for marketing emails on the frontend.
	 *
	 * Displays a checkbox for each enabled marketing email template if the individual control option is enabled.
	 * The checkboxes allow users to opt-in or opt-out of specific marketing emails.
	 *
	 * @return void
	 */
	public function add_individual_subscription_checkboxes() {
		if ( ! woodmart_get_opt( 'email_subscription_individual_control' ) ) {
			return;
		}

		$user_id             = get_current_user_id();
		$user_email          = get_userdata( $user_id )->user_email;
		$subscription_exists = $this->db_storage->check_subscription_exists( array( 'user_id' => $user_id ) );
		$mailer              = WC()->mailer();
		$email_templates     = $mailer->get_emails();
		$checkbox_attributes = '';

		// Disabled checkboxes if the user has not subscribed to the marketing newsletter.
		if ( ! $subscription_exists ) {
			$checkbox_attributes = 'disabled="disabled"';
		}

		woodmart_enqueue_js_script( 'email-subscription-checkboxes' );

		foreach ( $email_templates as $mailing_name => $email_instance ) {
			if ( ! in_array( $mailing_name, $this->marketing_emails, true ) || ! $email_instance->is_enabled() ) {
				continue;
			}

			$option_prefix = strtolower( str_replace( 'XTS_Email_', '', $mailing_name ) );
			$option_name   = 'wd_email_individual_subscription_consent_' . $option_prefix;
			$value         = ( ! $subscription_exists || $this->unsubscribed_emails->check_is_user_unsubscribed_from_mailing( $user_email, $mailing_name ) ) ? 0 : 1;

			$this->render_checkbox(
				array(
					'id'               => $option_name,
					'label'            => $email_instance->get_title(),
					'checked_value'    => 1,
					'unchecked_value'  => 0,
					'value'            => $value,
					'class'            => array( 'form-row-wide', 'wd-email-sub' ),
					'input_class'      => array( 'wd-email-individual-consent' ),
					'input_attributes' => $checkbox_attributes,
				)
			);
		}
	}

	/**
	 * Renders a checkbox input field.
	 *
	 * Outputs the HTML for a checkbox input with the specified attributes and label.
	 *
	 * @param array $args List of arguments for rendering the checkbox.
	 *
	 * @return void
	 */
	public function render_checkbox( $args ) {
		$default_args = array(
			'id'               => '',
			'label'            => '',
			'checked_value'    => 1,
			'unchecked_value'  => null,
			'value'            => 0,
			'class'            => array( 'form-row-wide' ),
			'input_class'      => array(),
			'input_attributes' => '',
		);

		$args = wp_parse_args( $args, $default_args );
		?>
		<p class="form-row <?php echo esc_attr( implode( ' ', $args['class'] ) ); ?>" id="<?php echo esc_attr( $args['id'] ) . '_field'; ?>">
			<label class="checkbox">
				<?php if ( ! is_null( $args['unchecked_value'] ) ) : ?>
					<input type="hidden" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $args['unchecked_value'] ); ?>" />
				<?php endif; ?>

				<input type="checkbox" name="<?php echo esc_attr( $args['id'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $args['checked_value'] ); ?>" class="<?php echo esc_attr( 'input-checkbox ' . implode( ' ', $args['input_class'] ) ); ?>" <?php echo esc_attr( $args['input_attributes'] ); ?> <?php checked( $args['value'], $args['checked_value'] ); ?> /> 

				<?php echo wp_kses_post( $args['label'] ); ?>
			</label>
		</p>
		<?php
	}

	/**
	 * Adds or updates an email subscription for a customer during registration.
	 *
	 * Checks if the email marketing consent is enabled and if an email is provided.
	 * Handles consent for subscription, updates or deletes existing subscriptions,
	 * or adds a new subscription as appropriate.
	 * Displays an error notice if the operation fails.
	 *
	 * @param int $customer_id The ID of the newly registered customer.
	 */
	public function add_subscription_on_registration( $customer_id ) {
		if ( ! woodmart_get_opt( 'email_marketing_consent_enabled' ) || empty( $_POST['email'] ) ) {
			return;
		}

		$consent             = ! empty( $_POST['wd_email_subscription_consent'] );
		$user_email          = sanitize_email( $_POST['email'] );
		$guest_data          = array(
			'user_id'    => 0,
			'user_email' => $user_email,
		);
		$subscription_exists = $this->db_storage->check_subscription_exists( $guest_data );
		$result              = false;

		if ( $subscription_exists ) {
			if ( $consent ) {
				$result = $this->db_storage->update_subscription(
					array(
						'user_id'    => $customer_id,
						'user_email' => null,
					),
					$guest_data
				);
			} else {
				$result = $this->db_storage->delete_subscription( $guest_data );
			}
		} elseif ( $consent ) {
			$result = $this->db_storage->add_subscription( $customer_id );
		}

		if ( is_wp_error( $result ) ) {
			wc_add_notice( $result->get_error_message(), 'error' );
		}
	}

	/**
	 * Handles adding, updating, or deleting an email subscription when a user edits their account.
	 *
	 * @param int $user_id The ID of the user editing their account.
	 */
	public function add_subscription_on_edit_account( $user_id ) {
		if ( ! woodmart_get_opt( 'email_marketing_consent_enabled' ) ) {
			return;
		}

		$result     = false;
		$consent    = ! empty( $_POST['wd_email_subscription_consent'] );
		$user_email = get_userdata( $user_id )->user_email;

		$guest_data = array(
			'user_id'    => 0,
			'user_email' => $user_email,
		);

		$user_data = array(
			'user_id'    => $user_id,
			'user_email' => null,
		);

		$guest_subscription_exists = $this->db_storage->check_subscription_exists( $guest_data );
		$user_subscription_exists  = $this->db_storage->check_subscription_exists( $user_data );

		if ( $consent ) {
			if ( $guest_subscription_exists ) {
				$result = $this->db_storage->update_subscription( $user_data, $guest_data );
			} elseif ( ! $user_subscription_exists ) {
				$result = $this->db_storage->add_subscription( $user_id );
			}
		} elseif ( $guest_subscription_exists || $user_subscription_exists ) {
			$result = $this->db_storage->delete_subscription( $guest_subscription_exists ? $guest_data : $user_data );
		}

		if ( is_wp_error( $result ) ) {
			wc_add_notice( $result->get_error_message(), 'error' );
		}
	}

	/**
	 * Handles individual email subscription preferences when editing the account.
	 *
	 * Updates the user's subscription status for each marketing email based on their consent.
	 * Adds or removes the user from the unsubscribed list as needed.
	 *
	 * @param int $user_id The ID of the user being edited.
	 */
	public function add_individual_subscription_on_edit_account( $user_id ) {
		if ( ! woodmart_get_opt( 'email_marketing_consent_enabled' ) || ! woodmart_get_opt( 'email_subscription_individual_control' ) || empty( $_POST['account_email'] ) ) {
			return;
		}

		$mailer = WC()->mailer();
		$email  = sanitize_email( $_POST['account_email'] );

		foreach ( $this->marketing_emails as $mailing_name ) {
			$option_prefix = strtolower( str_replace( 'XTS_Email_', '', $mailing_name ) );
			$option_name   = 'wd_email_individual_subscription_consent_' . $option_prefix;

			if ( ! isset( $_POST[ $option_name ] ) ) {
				continue;
			}

			$consent              = ! empty( $_POST[ $option_name ] );
			$is_user_unsubscribed = $this->unsubscribed_emails->check_is_user_unsubscribed_from_mailing( $email, $mailing_name );

			if ( $consent && $is_user_unsubscribed ) {
				$result = $this->unsubscribed_emails->delete_user_unsubscription( $email, $mailing_name );

				if ( is_wp_error( $result ) ) {
					wc_add_notice( $result->get_error_message(), 'error' );
				}
			} elseif ( ! $consent && ! $is_user_unsubscribed ) {
				$result = $this->unsubscribed_emails->insert_unsubscribed_email( $email, $mailing_name );

				if ( false === $result ) {
					$email_object = isset( $mailer->emails[ $mailing_name ] ) ? $mailer->emails[ $mailing_name ] : null;
					$email_title  = $email_object && isset( $email_object->title ) ? $email_object->title : $mailing_name;

					wc_add_notice(
						sprintf(
							/* translators: %s: email title */
							__( 'Unable to unsubscribe from the mailing list: "%s"', 'woodmart' ),
							$email_title
						),
						'error'
					);
				}
			}
		}
	}

	/**
	 * Adds an email subscription when an order is placed, if the user has given consent.
	 *
	 * @param int $order_id The ID of the placed order.
	 */
	public function add_subscription_on_placing_order( $order_id ) {
		if ( is_user_logged_in() || empty( $_POST['wd_email_subscription_consent'] ) || ! isset( $_POST['billing_email'] ) || '' === $_POST['billing_email'] ) {
			return;
		}

		$email = sanitize_email( $_POST['billing_email'] );

		if ( ! empty( $email ) ) {
			$result = $this->db_storage->add_subscription( $email );

			if ( is_wp_error( $result ) ) {
				wc_add_notice( $result->get_error_message(), 'error' );
			}
		}
	}

	/**
	 * Updates the subscription record to assign it to the logged-in user if a guest subscription exists.
	 *
	 * This method checks if there is a subscription with the user's email and a user_id of 0 (guest).
	 * If such a subscription exists, it updates the subscription to associate it with the logged-in user's ID.
	 *
	 * @param string  $user_login The username used to log in.
	 * @param WP_User $user       The WP_User object of the logged-in user.
	 */
	public function update_subscription_user_id_on_login( $user_login, $user ) {
		if ( ! woodmart_get_opt( 'email_marketing_consent_enabled' ) ) {
			return;
		}

		$result     = false;
		$user_id    = $user->ID;
		$user_email = $user->user_email;

		$guest_data = array(
			'user_id'    => 0,
			'user_email' => $user_email,
		);

		$user_data = array(
			'user_id'    => $user_id,
			'user_email' => null,
		);

		$guest_subscription_exists = $this->db_storage->check_subscription_exists( $guest_data );
		$user_subscription_exists  = $this->db_storage->check_subscription_exists( $user_data );

		if ( $user_subscription_exists && $guest_subscription_exists ) {
			$result = $this->db_storage->delete_subscription( $guest_data );
		} elseif ( $guest_subscription_exists ) {
			$result = $this->db_storage->update_subscription( $user_data, $guest_data );
		}

		if ( is_wp_error( $result ) ) {
			wc_add_notice( $result->get_error_message(), 'error' );
		}
	}

	/**
	 * Removes the email subscription for a user when their account is deleted.
	 *
	 * @param int $user_id The ID of the user being deleted.
	 */
	public function remove_subscription_on_user_delete( $user_id ) {
		if ( ! woodmart_get_opt( 'email_marketing_consent_enabled' ) ) {
			return;
		}

		$this->db_storage->delete_subscription( array( 'user_id' => $user_id ) );
	}
}

Frontend::get_instance();
