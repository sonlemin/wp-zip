<?php
/**
 * Price tracker email template.
 *
 * @package XTS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo esc_html( wp_strip_all_tags( $email_heading ) ) . "\n\n";

echo esc_html(
	sprintf(
		// translators: %s User name.
		__(
			'Hi, %s!',
			'woodmart'
		),
		$email->user_name
	)
) . "\n";

echo esc_html__( 'Good news!', 'woodmart' );
echo esc_html__( 'The price of some of the products you were watching has dropped.', 'woodmart' );
echo esc_html__( 'Check them out below:', 'woodmart' );
echo "\n\n";

if ( ! empty( $email->object ) ) {
	echo "------------------------------------------\n\n";

	foreach ( $email->object as $subscription ) {
		if ( ! apply_filters( 'woocommerce_is_email_preview', false ) ) {
			$product = $subscription->variation_id ? wc_get_product( $subscription->variation_id ) : wc_get_product( $subscription->product_id );
		} elseif ( isset( $email->dummy_product ) ) {
			$product = $email->dummy_product;
		}

		echo esc_html(
			wp_strip_all_tags(
				sprintf(
					'%1$s (%2$s -> %3$s) [%4$s]',
					$product->get_name(),
					wc_price( $subscription->product_price ),
					wc_price( $subscription->product_new_price ),
					esc_url( add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() ) )
				)
			)
		) . "\n";
	}

	echo "\n------------------------------------------\n\n";
}


echo esc_html__( "Best regards,\n", 'woodmart' );
echo esc_html( $email->get_blogname() );

echo "\n----------------------------------------\n\n";

echo esc_html( __( 'If you don\'t want to receive any further notification, please follow this link', 'woodmart' ) . ' ' . $unsubscribe_link );

echo "\n----------------------------------------\n\n";

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
