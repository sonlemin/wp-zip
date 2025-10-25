<?php
/**
 * Price tracker class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Price_Tracker;

use XTS\Admin\Modules\Options;
use XTS\Modules\Managers\Module_Endpoints_Manager;

/**
 * Price tracker class.
 */
class Main {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		woodmart_include_files( __DIR__, $this->get_include_files() );

		if ( woodmart_get_opt( 'price_tracker_enabled' ) ) {
			add_action( 'init', array( $this, 'add_endpoint_options' ) );
		}
	}

	/**
	 * Add endpoint for price tracker.
	 */
	public function add_endpoint_options() {
		$endpoints_manager = Module_Endpoints_Manager::get_instance();

		$endpoints_manager->add_endpoint_options(
			array(
				'title'    => esc_html__( 'Price tracker', 'woodmart' ),
				'desc'     => esc_html__( 'Endpoint for the "My account &rarr; Price tracker" page.', 'woodmart' ),
				'id'       => 'woodmart_myaccount_price_tracker_endpoint',
				'type'     => 'text',
				'default'  => 'price-tracker',
				'desc_tip' => true,
				'priority' => 20,
			)
		);
	}

	/**
	 * Add options in theme settings.
	 */
	public function add_options() {
		Options::add_field(
			array(
				'id'          => 'price_tracker_enabled',
				'name'        => esc_html__( 'Enable "Price tracker"', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'price-tracker-enabled.jpg" alt="">', true ),
				'description' => esc_html__( 'Enable this option to allow customers to subscribe for price drop notifications on products.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'price_tracker_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 10,
			)
		);

		Options::add_field(
			array(
				'id'          => 'price_tracker_for_loggined',
				'name'        => esc_html__( 'Login to see price tracker', 'woodmart' ),
				'description' => esc_html__( 'Restrict the "Price tracker" feature to registered users to ensure that only registered customers can sign up for notifications about price drops on products.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'price_tracker_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 20,
			)
		);

		Options::add_field(
			array(
				'id'          => 'price_tracker_use_loggedin_email',
				'name'        => esc_html__( 'Skip email input for logged-in users', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'price-tracker-use-loggedin-email.jpg" alt="">', true ),
				'description' => esc_html__( 'Hides the email field for authenticated users and uses their account email for notifications.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'price_tracker_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 30,
			)
		);

		Options::add_field(
			array(
				'id'          => 'price_tracker_desired_price',
				'name'        => esc_html__( 'Enable desired price', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'price-tracker-desired-price.jpg" alt="">', true ),
				'description' => esc_html__( 'Allows the user to specify the exact price after a drop at which they will receive a notification.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'price_tracker_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 40,
			)
		);

		Options::add_field(
			array(
				'id'          => 'price_tracker_enable_privacy_checkbox',
				'name'        => esc_html__( 'Enable privacy policy checkbox', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'price-tracker-enable-privacy-checkbox.jpg" alt="">', true ),
				'description' => esc_html__( 'Activate this setting to require customers to agree to your privacy policy with a checkbox before they can join the price tracker.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'price_tracker_section',
				'default'     => '1',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 50,
			)
		);

		Options::add_field(
			array(
				'id'          => 'price_tracker_fragments_enable',
				'name'        => esc_html__( 'Enable fragments updating', 'woodmart' ),
				'description' => esc_html__( 'Update the price drop notification form dynamically on product pages, ensuring it displays accurate information even when page caching is active.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'price_tracker_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 60,
			)
		);
	}

	/**
	 * Get list of module include files.
	 *
	 * @return array
	 */
	protected function get_include_files() {
		$files = array();

		if ( ! class_exists( 'WP_List_Table' ) ) {
			$files[] = ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		}

		$files = array_merge(
			$files,
			array(
				'./class-db-storage',
				'./class-admin',
				'./class-frontend',
				'./class-emails',
				'./list-tables/class-products-table',
				'./list-tables/class-users-table',
			)
		);

		return $files;
	}
}

new Main();
