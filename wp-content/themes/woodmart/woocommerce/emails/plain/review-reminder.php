<?php
/**
 * Review reminder emails plain template.
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

echo esc_html__( 'We hope you\'re enjoying your recent purchase from our store! Your opinion truly matters to us and helps other shoppers make informed choices.', 'woodmart' ) . "\n";

echo esc_html__( 'We\'d be incredibly grateful if you could take a minute to leave a quick review of the items you bought:', 'woodmart' ) . "\n";

echo esc_html__( 'Your recent purchase:', 'woodmart' ) . "\n";

foreach ( $email->object->item_list as $product_id => $data ) {
	echo $data['name'] . ' - ' . $data['permalink'] . "\n";
}

echo esc_html__( 'Your honest feedback helps us improve and continue offering products you love. Plus, it helps fellow customers get a better idea of what to expect.', 'woodmart' ) . "\n";

echo esc_html__( 'Thank you for being a valued part of our community!', 'woodmart' ) . "\n";

echo esc_html__( 'Best regards,', 'woodmart' ) . esc_html( $email->get_blogname() ) . "\n\n\n";

echo esc_html( __( 'If you don\'t want to receive any further notification, please follow this link', 'woodmart' ) . ' ' . $unsubscribe_link ) . "\n";

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
