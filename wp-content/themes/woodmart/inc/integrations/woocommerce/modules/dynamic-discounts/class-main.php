<?php
/**
 * Dynamic discounts class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Dynamic_Discounts;

use XTS\Admin\Modules\Options;
use WC_Cart;

/**
 * Dynamic discounts class.
 */
class Main {
	/**
	 * Make sure that the same discount is not applied twice for the same product.
	 *
	 * @var array A list of product IDs for which a discount has already been applied.
	 */
	public $applied = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_options' ) );

		if ( woodmart_get_opt( 'discounts_enabled' ) ) {
			add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_discounts' ), 10, 1 );
		}

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
	 */
	public function add_options() {
		Options::add_field(
			array(
				'id'          => 'discounts_enabled',
				'name'        => esc_html__( 'Enable "Dynamic discounts"', 'woodmart' ),
				'hint'        => wp_kses( '<img data-src="' . WOODMART_TOOLTIP_URL . 'discounts-enabled.jpg" alt="">', true ),
				'description' => esc_html__( 'You can configure your discounts in Dashboard -> Products -> Dynamic Discounts.', 'woodmart' ),
				'group'       => esc_html__( 'Dynamic discounts', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'shop_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 120,
				'class'       => 'xts-preset-field-disabled',
			)
		);

		Options::add_field(
			array(
				'id'          => 'show_discounts_table',
				'name'        => esc_html__( 'Show discounts table', 'woodmart' ),
				'description' => esc_html__( 'Dynamic pricing table on the single product page.', 'woodmart' ),
				'group'       => esc_html__( 'Dynamic discounts', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'shop_section',
				'default'     => '0',
				'on-text'     => esc_html__( 'Yes', 'woodmart' ),
				'off-text'    => esc_html__( 'No', 'woodmart' ),
				'priority'    => 130,
				'class'       => 'xts-preset-field-disabled',
				'requires'    => array(
					array(
						'key'     => 'discounts_enabled',
						'compare' => 'equals',
						'value'   => '1',
					),
				),
			)
		);
	}

	/**
	 * Calculate price with discounts.
	 *
	 * @param WC_Cart $cart WC_Cart class.
	 *
	 * @return void
	 */
	public function calculate_discounts( $cart ) {
		// @codeCoverageIgnoreStart
		// Woocommerce wpml compatibility. Make sure that the discount is calculated only once.
		if ( class_exists( 'woocommerce_wpml' ) && ! defined( 'PAYPAL_API_URL' ) && doing_action( 'woocommerce_cart_loaded_from_session' ) ) {
			return;
		}
		// @codeCoverageIgnoreEnd

		$variations_quantity = array();

		foreach ( $cart->get_cart() as $cart_item ) {
			if ( 'variation' !== $cart_item['data']->get_type() ) {
				continue;
			}

			if ( ! isset( $variations_quantity[ $cart_item['product_id'] ] ) ) {
				$variations_quantity[ $cart_item['product_id'] ] = 0;
			}

			$variations_quantity[ $cart_item['product_id'] ] += (int) $cart_item['quantity'];
		}

		foreach ( $cart->get_cart() as $cart_item ) {
			$product       = $cart_item['data'];
			$item_quantity = $cart_item['quantity'];
			$product_price = apply_filters( 'woodmart_pricing_before_calculate_discounts', (float) $product->get_price(), $cart_item );
			$discount      = Manager::get_instance()->get_discount_rules( $product );

			if ( empty( $product->get_price() ) || empty( $discount ) || ( ! empty( $this->applied ) && in_array( $product->get_id(), $this->applied, true ) ) || isset( $cart_item['wd_is_free_gift'] ) || isset( $cart_item['wd_fbt_bundle_id'] ) ) {
				continue;
			}

			$product->set_regular_price( $product_price );

			if ( ! empty( $variations_quantity ) && 'individual_product' === $discount['discount_quantities'] && in_array( $product->get_parent_id(), array_keys( $variations_quantity ), true ) ) {
				$item_quantity = $variations_quantity[ $product->get_parent_id() ];
			}

			switch ( $discount['_woodmart_rule_type'] ) {
				case 'bulk':
					foreach ( $discount['discount_rules'] as $key => $discount_rule ) {
						if ( $discount_rule['_woodmart_discount_rules_from'] <= $item_quantity && ( $item_quantity <= $discount_rule['_woodmart_discount_rules_to'] || ( array_key_last( $discount['discount_rules'] ) === $key && empty( $discount_rule['_woodmart_discount_rules_to'] ) ) ) ) {
							$discount_type  = $discount_rule['_woodmart_discount_type'];
							$discount_value = $discount_rule[ '_woodmart_discount_' . $discount_type . '_value' ];

							// @codeCoverageIgnoreStart
							// WPML woocommerce-multilingual compatibility.
							if ( function_exists( 'woodmart_wpml_shipping_progress_bar_amount' ) && 'amount' === $discount_type ) {
								$discount_value = woodmart_wpml_shipping_progress_bar_amount( $discount_value );
							}
							// @codeCoverageIgnoreEnd

							$product_price = Manager::get_instance()->get_product_price(
								$product_price,
								array(
									'type'  => $discount_type,
									'value' => $discount_value,
								)
							);
						}
					}
					break;
			}

			$product_price = apply_filters( 'woodmart_pricing_after_calculate_discounts', $product_price, $cart_item );

			if ( $product_price < 0 ) {
				$product_price = 0;
			}

			$product->set_price( $product_price );
			$product->set_sale_price( $product_price );

			$this->applied[] = $product->get_id();
		}
	}
}

new Main();
