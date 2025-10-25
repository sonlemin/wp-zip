<?php
/**
 * My account content shortcode.
 *
 * @package Woodmart
 */

use XTS\Modules\Layouts\Main;
use XTS\WC_Wishlist\Ui;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_shortcode_my_account_content' ) ) {
	/**
	 * My account content shortcode.
	 *
	 * @param array $settings Shortcode settings.
	 * @return string
	 */
	function woodmart_shortcode_my_account_content( $settings ) {
		$default_settings = array(
			'css' => '',
		);

		$settings         = wp_parse_args( $settings, $default_settings );
		$wrapper_classes  = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );
		$wrapper_classes .= ' woocommerce-MyAccount-content';
		if ( $settings['css'] ) {
			$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
		}

		ob_start();

		Main::setup_preview();
		?>
		<div class="wd-wpb wd-el-my-acc-content<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php
			/**
			 * Hook: woocommerce_account_content.
			 */
			if ( (int) woodmart_get_opt( 'wishlist_page' ) === get_the_ID() ) {
				echo Ui::get_instance()->wishlist_page_content(); // phpcs:ignore.
			} else {
				remove_action( 'woocommerce_account_dashboard', 'woodmart_my_account_links', 10 );
				do_action( 'woocommerce_account_content' ); // phpcs:ignore.
			}
			?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
