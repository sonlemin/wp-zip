<?php
/**
 * WooCommerce Subscriptions.
 *
 * @package woodmart
 */

if ( ! class_exists( 'WC_Subscriptions' ) ) {
	return;
}

if ( ! function_exists( 'woodmart_wc_subscriptions_add_variable_product_types' ) ) {
	/**
	 * Add variable subscription product type.
	 *
	 * @param array $types Product types.
	 *
	 * @return array
	 */
	function woodmart_wc_subscriptions_add_variable_product_types( $types ) {
		$types[] = 'variable-subscription';

		return $types;
	}

	add_filter( 'woodmart_variable_product_types', 'woodmart_wc_subscriptions_add_variable_product_types' );
}

if ( ! function_exists( 'woodmart_wc_subscriptions_add_custom_product_types' ) ) {
	/**
	 * Add custom product types.
	 *
	 * @param array $types Product types.
	 *
	 * @return array
	 */
	function woodmart_wc_subscriptions_add_custom_product_types( $types ) {
		$types[] = 'subscription';
		$types[] = 'variable-subscription';
		$types[] = 'subscription_variation';

		return $types;
	}

	add_filter( 'woodmart_waitlist_allowed_product_types', 'woodmart_wc_subscriptions_add_custom_product_types' );
	add_filter( 'woodmart_price_tracker_allowed_product_types', 'woodmart_wc_subscriptions_add_custom_product_types' );
}
