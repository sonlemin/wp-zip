<?php
/**
 * My account login shortcode.
 *
 * @package Woodmart
 */

use XTS\Modules\Layouts\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_shortcode_my_account_login' ) ) {
	/**
	 * My account login shortcode.
	 *
	 * @param array $settings Shortcode settings.
	 * @return string
	 */
	function woodmart_shortcode_my_account_login( $settings ) {
		$default_settings = array(
			'css'              => '',
			'title_alignment'  => 'left',
			'button_alignment' => 'full-width',
		);

		$settings        = wp_parse_args( $settings, $default_settings );
		$wrapper_classes = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );
		$account_link    = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

		if ( $settings['css'] ) {
			$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
		}

		$wrapper_classes .= ' wd-btn-align-' . woodmart_vc_get_control_data( $settings['button_alignment'], 'desktop' );

		ob_start();

		Main::setup_preview();
		?>
		<div class="wd-wpb wd-el-my-account-login<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php if ( isset( $settings['show_title'] ) && 'yes' === $settings['show_title'] ) : ?>
				<?php $title_classes = ' text-' . $settings['title_alignment']; ?>
				<h2 class="wd-login-title<?php echo esc_html( $title_classes ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>
			<?php endif; ?>
			<?php woodmart_login_form( true, add_query_arg( 'action', 'login', $account_link ) ); ?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
