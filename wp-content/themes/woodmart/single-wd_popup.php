<?php
/**
 * The template for displaying custom popups.
 *
 * @package xts
 */

use Elementor\Plugin;

if ( ! current_user_can( apply_filters( 'woodmart_wd_popup_access', 'edit_posts' ) ) ) {
	wp_die( 'You do not have access.', '', array( 'back_link' => true ) );
}

get_header();

?>
<?php if ( woodmart_is_elementor_installed() && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_page() || woodmart_elementor_is_preview_mode() ) ) : ?>
	<?php
	woodmart_enqueue_inline_style( 'mfp-popup' );
	woodmart_enqueue_inline_style( 'opt-popup-builder' );

	$document        = Plugin::$instance->documents->get( get_the_ID() );
	$page_settings   = $document->get_settings();
	$prefix          = 'wd_popup_';
	$wrapper_classes = 'wd-mfp-popup-wrap-' . get_the_ID();

	$hide_on_desktop = $page_settings[ $prefix . 'hide_popup' ];
	$hide_on_tablet  = $page_settings[ $prefix . 'hide_popup_tablet' ];
	$hide_on_mobile  = $page_settings[ $prefix . 'hide_popup_mobile' ];

	if ( $hide_on_desktop ) {
		$wrapper_classes .= ' wd-hide-lg';
	}

	if ( $hide_on_tablet ) {
		$wrapper_classes .= ' wd-hide-md-sm';
	}

	if ( $hide_on_mobile ) {
		$wrapper_classes .= ' wd-hide-sm';
	}

	$btn_classes = 'wd-popup-close wd-action-btn wd-cross-icon';

	if ( isset( $page_settings[ $prefix . 'close_btn' ] ) && ! $page_settings[ $prefix . 'close_btn' ] ) {
		$btn_classes .= ' wd-hide';
	}

	if ( ! empty( $page_settings[ $prefix . 'close_btn_display' ] ) ) {
		$btn_classes .= ' wd-style-' . $page_settings[ $prefix . 'close_btn_display' ];
	} else {
		$btn_classes .= ' wd-style-icon';
	}

	?>
	<div class="mfp-bg mfp-ready wd-mfp-popup-bg-<?php echo esc_html( get_the_ID() ); ?> wd-fill"></div>
	<div class="mfp-wrap wd-popup-builder-wrap wd-scroll <?php echo esc_html( $wrapper_classes ); ?>">
		<div class="mfp-container mfp-s-ready mfp-inline-holder">
			<div class="mfp-content">
				<div class="wd-popup-wrap">
					<div class="<?php echo esc_attr( $btn_classes ); ?>">
						<a title="<?php esc_html_e( 'Close', 'woodmart' ); ?>" href="#" rel="nofollow">
							<span><?php esc_html_e( 'Close', 'woodmart' ); ?></span>
						</a>
					</div>
					<div class="wd-popup wd-scroll-content">
						<div class="wd-popup-inner wd-entry-content">
							<?php while ( have_posts() ) : ?>
								<?php the_post(); ?>
								<?php the_content(); ?>
							<?php endwhile; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php else : ?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
<?php endif; ?>
<?php

get_footer();
