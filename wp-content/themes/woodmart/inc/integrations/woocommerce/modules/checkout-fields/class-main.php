<?php
/**
 * Checkout fields class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Checkout_Fields;

use XTS\Admin\Modules\Options;

/**
 * Checkout fields class.
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
				'id'          => 'checkout_fields_enabled',
				'name'        => esc_html__( 'Checkout fields manager', 'woodmart' ),
				'description' => esc_html__( 'You can configure your checkout forms in Dashboard -> WooCommerce -> Checkout Fields.', 'woodmart' ),
				'hint'        => '<video data-src="' . WOODMART_TOOLTIP_URL . 'checkout-fields-manager.mp4" autoplay loop muted></video>',
				'type'        => 'switcher',
				'section'     => 'checkout_section',
				'default'     => false,
				'priority'    => 50,
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
				'./class-helper',
				'./list-tables/class-fields-table',
				'./class-admin',
				'./class-frontend',
				'./class-ajax-actions',
			)
		);

		return $files;
	}
}

new Main();
