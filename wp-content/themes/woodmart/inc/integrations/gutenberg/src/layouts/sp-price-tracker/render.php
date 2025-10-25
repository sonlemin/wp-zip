<?php

use XTS\Modules\Price_Tracker\Frontend as Price_Tracker_Frontend;
use XTS\Modules\Layouts\Main;

if ( ! function_exists( 'wd_gutenberg_single_product_price_tracker_btn' ) ) {
	function wd_gutenberg_single_product_price_tracker_btn( $block_attributes ) {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) || ( woodmart_get_opt( 'price_tracker_for_loggined' ) && ! is_user_logged_in() ) ) {
			return '';
		}

		$btn_classes  = 'wd-action-btn wd-pt-icon';
		$btn_classes .= ' wd-style-' . $block_attributes['style'];

		if ( 'icon' === $block_attributes['style'] ) {
			$btn_classes .= ' wd-tooltip';
		}

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

		if ( 'icon' === $block_attributes['style'] ) {
			woodmart_enqueue_js_library( 'tooltips' );
			woodmart_enqueue_js_script( 'btns-tooltips' );
		}

		$class_instance->render_popup();
		?>
		<div id="<?php echo esc_attr( wd_get_gutenberg_element_id( $block_attributes ) ); ?>" class="wd-single-action-btn wd-single-pt-btn<?php echo esc_attr( wd_get_gutenberg_element_classes( $block_attributes ) ); ?>">
			<?php echo $button_html; // phpcs:ignore. ?>
		</div>
		<?php

		Main::restore_preview();

		return ob_get_clean();
	}
}
