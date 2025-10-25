<?php

namespace XTS\Modules\Linked_Variations;

use XTS\Admin\Modules\Options;

class Main {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		woodmart_include_files(
			__DIR__,
			array(
				'./class-admin',
				'./class-frontend',
			)
		);
	}

	/**
	 * Add option.
	 *
	 * @return void
	 */
	public function add_options() {
		Options::add_field(
			array(
				'id'          => 'linked_variations',
				'name'        => esc_html__( 'Enable linked variations', 'woodmart' ),
				'hint'        => '<video data-src="' . WOODMART_TOOLTIP_URL . 'enable-linked-variations.mp4" autoplay loop muted></video>',
				'description' => wp_kses( __( 'This feature allows you to create a new kind of variable product based on simple products. You can create linked variations bundles via Dashboard -> Products -> Linked variations. Read more information in our <a href="https://xtemos.com/docs-topic/linked-variations/" target="_blank">documentation</a>.', 'woodmart' ), true ),
				'group'       => esc_html__( 'Linked variations', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'variable_products_section',
				'default'     => true,
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 190,
				'class'       => 'xts-preset-field-disabled',
			)
		);
	}
}

new Main();
