<?php
/**
 * Free shipping progress bar.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Shipping_Progress_Bar;

use XTS\Admin\Modules\Options;

/**
 * Free shipping progress bar.
 */
class Main {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		woodmart_include_files( __DIR__, array( './class-frontend' ) );
	}

	/**
	 * Add options in theme settings.
	 */
	public function add_options() {
		Options::add_section(
			array(
				'id'       => 'shipping_progress_bar',
				'parent'   => 'general_shop_section',
				'name'     => esc_html__( 'Free shipping bar', 'woodmart' ),
				'priority' => 140,
				'icon'     => 'xts-i-cart',
				'class'    => 'xts-preset-section-disabled',
			)
		);

		Options::add_field(
			array(
				'id'          => 'shipping_progress_bar_enabled',
				'name'        => esc_html__( 'Free shipping bar', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'free-shipping-bar-cart-page.jpg" alt="">', true ),
				'description' => esc_html__( 'Display a free shipping progress bar on the website.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'shipping_progress_bar',
				'default'     => '0',
				'priority'    => 10,
			)
		);

		Options::add_field(
			array(
				'id'       => 'shipping_progress_bar_calculation',
				'name'     => esc_html__( 'Calculation', 'woodmart' ),
				'type'     => 'buttons',
				'section'  => 'shipping_progress_bar',
				'options'  => array(
					'custom' => array(
						'name'  => esc_html__( 'Custom', 'woodmart' ),
						'value' => 'custom',
					),
					'wc'     => array(
						'name'  => esc_html__( 'Based on WooCommerce Free shipping method', 'woodmart' ),
						'value' => 'wc',
					),
				),
				'default'  => 'custom',
				'priority' => 20,
			)
		);

		Options::add_field(
			array(
				'id'          => 'shipping_progress_bar_amount',
				'name'        => esc_html__( 'Goal amount', 'woodmart' ),
				'description' => esc_html__( 'Amount to reach 100% defined in your currency absolute value. For example: 300', 'woodmart' ),
				'type'        => 'text_input',
				'section'     => 'shipping_progress_bar',
				'requires'    => array(
					array(
						'key'     => 'shipping_progress_bar_calculation',
						'compare' => 'equals',
						'value'   => 'custom',
					),
				),
				'default'     => '100',
				'priority'    => 30,
			)
		);

		Options::add_field(
			array(
				'id'       => 'shipping_progress_bar_include_coupon',
				'name'     => esc_html__( 'Coupon discount', 'woodmart' ),
				'type'     => 'buttons',
				'section'  => 'shipping_progress_bar',
				'options'  => array(
					'include' => array(
						'name'  => esc_html__( 'Include', 'woodmart' ),
						'value' => 'include',
					),
					'exclude' => array(
						'name'  => esc_html__( 'Exclude', 'woodmart' ),
						'value' => 'exclude',
					),
				),
				'default'  => 'exclude',
				'priority' => 40,
			)
		);

		Options::add_field(
			array(
				'id'       => 'shipping_progress_bar_location_card_page',
				'name'     => esc_html__( 'Cart page', 'woodmart' ),
				'hint'     => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'free-shipping-bar-cart-page.jpg" alt="">', true ),
				'type'     => 'switcher',
				'section'  => 'shipping_progress_bar',
				'group'    => esc_html__( 'Locations', 'woodmart' ),
				'default'  => '1',
				'priority' => 50,
				'class'    => 'xts-col-6',
			)
		);

		Options::add_field(
			array(
				'id'       => 'shipping_progress_bar_location_mini_cart',
				'name'     => esc_html__( 'Mini cart', 'woodmart' ),
				'hint'     => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'free-shipping-bar-mini-cart.jpg" alt="">', true ),
				'type'     => 'switcher',
				'section'  => 'shipping_progress_bar',
				'group'    => esc_html__( 'Locations', 'woodmart' ),
				'default'  => '1',
				'priority' => 60,
				'class'    => 'xts-col-6',
			)
		);

		Options::add_field(
			array(
				'id'       => 'shipping_progress_bar_location_checkout',
				'name'     => esc_html__( 'Checkout page', 'woodmart' ),
				'hint'     => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'free-shipping-bar-checkout-page.jpg" alt="">', true ),
				'type'     => 'switcher',
				'section'  => 'shipping_progress_bar',
				'group'    => esc_html__( 'Locations', 'woodmart' ),
				'default'  => '0',
				'priority' => 70,
				'class'    => 'xts-col-6',
			)
		);

		Options::add_field(
			array(
				'id'       => 'shipping_progress_bar_location_single_product',
				'name'     => esc_html__( 'Single product', 'woodmart' ),
				'hint'     => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'shop-free-shipping-bar.jpg" alt="">', true ),
				'type'     => 'switcher',
				'section'  => 'shipping_progress_bar',
				'group'    => esc_html__( 'Locations', 'woodmart' ),
				'default'  => '0',
				'priority' => 80,
				'class'    => 'xts-col-6',
			)
		);

		Options::add_field(
			array(
				'id'          => 'shipping_progress_bar_message_initial',
				'name'        => esc_html__( 'Initial message', 'woodmart' ),
				'description' => esc_html__( 'Message to show before reaching the goal. Use shortcode [remainder] to display the amount left to reach the minimum.', 'woodmart' ),
				'type'        => 'textarea',
				'wysiwyg'     => true,
				'section'     => 'shipping_progress_bar',
				'group'       => esc_html__( 'Message', 'woodmart' ),
				'default'     => '<p>Add [remainder] to cart and get free shipping!</p>',
				'priority'    => 90,
			)
		);

		Options::add_field(
			array(
				'id'          => 'shipping_progress_bar_message_success',
				'name'        => esc_html__( 'Success message', 'woodmart' ),
				'description' => esc_html__( 'Message to show after reaching 100%.', 'woodmart' ),
				'type'        => 'textarea',
				'wysiwyg'     => true,
				'section'     => 'shipping_progress_bar',
				'group'       => esc_html__( 'Message', 'woodmart' ),
				'default'     => '<p>Your order qualifies for free shipping!</p>',
				'priority'    => 100,
			)
		);
	}
}

new Main();
