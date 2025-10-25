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
echo esc_html__( "We have added you to the price reduction alert list for the following item:\n", 'woodmart' );
echo esc_html( $email->object->get_name() ) . ' ' . wp_kses( $email->object->get_price(), true ) . ' ' . esc_url( $email->object->get_permalink() ) . "\n";
echo esc_html__( "Best regards,\n", 'woodmart' );
echo esc_html( $email->get_blogname() );

echo "\n----------------------------------------\n\n";

echo esc_html( __( 'If you don\'t want to receive any further notification, please follow this link', 'woodmart' ) . ' ' . $unsubscribe_link );

echo "\n----------------------------------------\n\n";

echo wp_kses_post( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) );
