<?php
/**
 * My account lost password shortcode.
 *
 * @package Woodmart
 */

use XTS\Modules\Layouts\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_shortcode_my_account_lost_pass' ) ) {
	/**
	 * My account lost password shortcode.
	 *
	 * @param array $settings Shortcode settings.
	 * @return string
	 */
	function woodmart_shortcode_my_account_lost_pass( $settings ) {
		$default_settings = array(
			'css' => '',
		);

		$settings        = wp_parse_args( $settings, $default_settings );
		$wrapper_classes = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );
		if ( $settings['css'] ) {
			$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
		}

		ob_start();

		Main::setup_preview();
		?>
		<div class="wd-wpb wd-el-my-account-lost-password<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php
			$endpoint = WC()->query->get_current_endpoint();

			if ( 'lost-password' === $endpoint ) {
				WC_Shortcode_My_Account::lost_password();
			}
			?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
