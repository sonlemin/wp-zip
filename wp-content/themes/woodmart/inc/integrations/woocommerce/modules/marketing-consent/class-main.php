<?php
/**
 * Email Marketing consent class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Marketing_Consent;

use XTS\Modules\Managers\Module_Files_Manager;
use XTS\Admin\Modules\Options;

/**
 * Email Marketing consent class.
 */
class Main {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		woodmart_include_files( __DIR__, $this->get_include_files() );
	}

	/**
	 * Add options in theme settings.
	 */
	public function add_options() {
		Options::add_field(
			array(
				'id'          => 'email_marketing_consent_enabled',
				'name'        => esc_html__( 'Enable "Email marketing consent"', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'email-subscription-system-enabled.jpg" alt="">', true ),
				'group'       => esc_html__( 'Email marketing consent', 'woodmart' ),
				'description' => esc_html__( 'Activate marketing email subscription checkboxes on Checkout, Registration, and My Account pages. Users can give consent to emails for: Wishlist product updates, Abandoned cart, Review reminder.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'shop_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 140,
			)
		);

		Options::add_field(
			array(
				'id'          => 'email_subscription_individual_control',
				'name'        => esc_html__( 'Allow managing individual email subscriptions', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'email-subscription-individual-control.jpg" alt="">', true ),
				'group'       => esc_html__( 'Email marketing consent', 'woodmart' ),
				'description' => esc_html__( 'Allows registered users to individually manage email subscriptions for each feature in My Account → Account details.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'shop_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 150,
				'requires'    => array(
					array(
						'key'     => 'email_marketing_consent_enabled',
						'compare' => 'equals',
						'value'   => '1',
					),
				),
			)
		);
	}

	/**
	 * Get list of module include files.
	 *
	 * @return array
	 */
	protected function get_include_files() {
		$files = array(
			'./class-unsubscribed-emails',
			'./functions',
		);

		if ( woodmart_get_opt( 'email_marketing_consent_enabled' ) ) {
			$files[] = './class-db-storage';
			$files[] = './class-frontend';
		}

		return $files;
	}
}

new Main();
