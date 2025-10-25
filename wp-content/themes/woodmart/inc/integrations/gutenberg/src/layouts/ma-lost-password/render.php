<?php
use XTS\Modules\Layouts\Main;

if ( ! function_exists( 'wd_gutenberg_my_account_lost_pass' ) ) {
	function wd_gutenberg_my_account_lost_pass( $block_attributes ) {
		$wrapper_classes = wd_get_gutenberg_element_classes( $block_attributes );
		$el_id           = wd_get_gutenberg_element_id( $block_attributes );
		$post_type       = get_post_type();
		Main::setup_preview();

		ob_start();
		?>
		<div id="<?php echo esc_attr( $el_id ); ?>" class="wd-el-my-account-lost-password<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php
			$endpoint = WC()->query->get_current_endpoint();

			if ( 'lost-password' === $endpoint || 'woodmart_layout' === $post_type ) {
				WC_Shortcode_My_Account::lost_password();
			}
			?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
