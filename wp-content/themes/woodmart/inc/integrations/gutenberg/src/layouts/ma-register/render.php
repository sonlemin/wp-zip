<?php
use XTS\Modules\Layouts\Main;

if ( ! function_exists( 'wd_gutenberg_my_account_register' ) ) {
	function wd_gutenberg_my_account_register( $block_attributes ) {
		$wrapper_classes = wd_get_gutenberg_element_classes( $block_attributes );
		$el_id           = wd_get_gutenberg_element_id( $block_attributes );

		Main::setup_preview();

		ob_start();
		?>
		<div id="<?php echo esc_attr( $el_id ); ?>" class="wd-el-my-account-register<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php woodmart_register_form(); ?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
