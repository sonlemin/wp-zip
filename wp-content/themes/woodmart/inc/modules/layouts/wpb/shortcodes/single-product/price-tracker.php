<?php
/**
 * Price tracker shortcode.
 *
 * @package Woodmart
 */

use XTS\Modules\Price_Tracker\Frontend as Price_Tracker_Frontend;
use XTS\Modules\Layouts\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_shortcode_single_product_price_tracker' ) ) {
	/**
	 * Price tracker shortcode.
	 *
	 * @param array $settings Shortcode attributes.
	 */
	function woodmart_shortcode_single_product_price_tracker( $settings ) {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) || ( woodmart_get_opt( 'price_tracker_for_loggined' ) && ! is_user_logged_in() ) ) {
			return;
		}

		$default_settings = array(
			'alignment' => 'left',
			'css'       => '',
			'style'     => 'text',
		);
		$settings         = wp_parse_args( $settings, $default_settings );

		$btn_classes  = 'wd-action-btn wd-pt-icon';
		$btn_classes .= ' wd-style-' . $settings['style'];

		if ( 'icon' === $settings['style'] ) {
			$btn_classes .= ' wd-tooltip';
		}

		$wrapper_classes = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );

		if ( $settings['css'] ) {
			$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
		}

		$wrapper_classes .= ' text-' . woodmart_vc_get_control_data( $settings['alignment'], 'desktop' );

		$class_instance = Price_Tracker_Frontend::get_instance();

		Main::setup_preview();

		ob_start();
		echo $class_instance->render_button( $btn_classes ); // phpcs:ignore.
		$button_html = ob_get_clean();

		if ( empty( $button_html ) ) {
			Main::restore_preview();

			return '';
		}

		ob_start();

		if ( 'icon' === $settings['style'] ) {
			woodmart_enqueue_js_library( 'tooltips' );
			woodmart_enqueue_js_script( 'btns-tooltips' );
		}

		$class_instance->render_popup();
		?>
		<div class="wd-single-action-btn wd-single-pt-btn wd-wpb<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php echo $button_html // phpcs:ignore. ?>
		</div>
		<?php

		Main::restore_preview();

		return ob_get_clean();
	}
}
