<?php
/**
 * Custom product tabs class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Custom_Product_Tabs;

use XTS\Admin\Modules\Options;

/**
 * Custom product tabs class.
 */
class Main {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		woodmart_include_files(
			__DIR__,
			array(
				'./class-manager',
				'./class-admin',
				'./class-frontend',
			)
		);
	}

	/**
	 * Add options in theme settings.
	 *
	 * @return void
	 */
	public function add_options() {
		Options::add_field(
			array(
				'id'          => 'custom_product_tabs_enabled',
				'name'        => esc_html__( 'Custom tabs', 'woodmart' ),
				'description' => esc_html__( 'Enables a custom post type for adding tabs to single product pages. You can find it under Dashboard → Products → Custom Tabs.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'product_tabs',
				'default'     => '0',
				'on-text'     => esc_html__( 'On', 'woodmart' ),
				'off-text'    => esc_html__( 'Off', 'woodmart' ),
				'priority'    => 105,
			)
		);
	}
}

new Main();
