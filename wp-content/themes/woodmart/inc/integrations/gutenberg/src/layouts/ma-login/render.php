<?php
use XTS\Modules\Layouts\Main;

if ( ! function_exists( 'wd_gutenberg_my_account_login' ) ) {
	function wd_gutenberg_my_account_login( $block_attributes ) {
		$wrapper_classes = wd_get_gutenberg_element_classes( $block_attributes );
		$el_id           = wd_get_gutenberg_element_id( $block_attributes );
		$account_link    = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

		Main::setup_preview();

		ob_start();
		?>
		<div id="<?php echo esc_attr( $el_id ); ?>" class="wd-el-my-account-login<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php woodmart_login_form( true, add_query_arg( 'action', 'login', $account_link ) ); ?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
