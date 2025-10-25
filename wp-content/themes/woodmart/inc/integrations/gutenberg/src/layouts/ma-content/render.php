<?php
use XTS\Modules\Layouts\Main;
use XTS\WC_Wishlist\Ui;

if ( ! function_exists( 'wd_gutenberg_my_account_content' ) ) {
	function wd_gutenberg_my_account_content( $block_attributes ) {
		$wrapper_classes  = wd_get_gutenberg_element_classes( $block_attributes );
		$wrapper_classes .= ' woocommerce-MyAccount-content';
		$el_id            = wd_get_gutenberg_element_id( $block_attributes );

		Main::setup_preview();

		ob_start();
		?>
		<div id="<?php echo esc_attr( $el_id ); ?>" class="wd-el-my-acc-content<?php echo esc_attr( $wrapper_classes ); ?>">
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
