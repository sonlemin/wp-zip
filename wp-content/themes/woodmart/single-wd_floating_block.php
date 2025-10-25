<?php
/**
 * The template for displaying floating blocks.
 *
 * @package xts
 */

use Elementor\Plugin;

if ( ! current_user_can( apply_filters( 'woodmart_wd_floating_block_access', 'edit_posts' ) ) ) {
	wp_die( 'You do not have access.', '', array( 'back_link' => true ) );
}

get_header();

?>
<?php if ( woodmart_is_elementor_installed() && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_page() || woodmart_elementor_is_preview_mode() ) ) : ?>
	<?php
	woodmart_enqueue_inline_style( 'opt-floating-block' );

	$document        = Plugin::$instance->documents->get( get_the_ID() );
	$page_settings   = $document->get_settings();
	$prefix          = 'wd_fb_';
	$wrapper_classes = 'wd-fb-holder wd-scroll';

	$hide_on_desktop = $page_settings[ $prefix . 'hide_floating_block' ];
	$hide_on_tablet  = $page_settings[ $prefix . 'hide_floating_block_tablet' ];
	$hide_on_mobile  = $page_settings[ $prefix . 'hide_floating_block_mobile' ];

	if ( $hide_on_desktop ) {
		$wrapper_classes .= ' wd-hide-lg';
	}

	if ( $hide_on_tablet ) {
		$wrapper_classes .= ' wd-hide-md-sm';
	}

	if ( $hide_on_mobile ) {
		$wrapper_classes .= ' wd-hide-sm';
	}

	$btn_classes = 'wd-fb-close wd-action-btn wd-cross-icon';

	if ( isset( $page_settings[ $prefix . 'positioning_area' ] ) && 'container' === $page_settings[ $prefix . 'positioning_area' ] ) {
		$wrapper_classes .= ' container';
	}

	if ( isset( $page_settings[ $prefix . 'close_btn' ] ) && ! $page_settings[ $prefix . 'close_btn' ] ) {
		$btn_classes .= ' wd-hide';
	}

	if ( ! empty( $page_settings[ $prefix . 'close_btn_display' ] ) ) {
		$btn_classes .= ' wd-style-' . $page_settings[ $prefix . 'close_btn_display' ];
	} else {
		$btn_classes .= ' wd-style-icon';
	}

	$bg_image = $page_settings[ $prefix . 'background_image' ];
	?>
	<div id="<?php echo esc_attr( 'wd-fb-' . get_the_ID() ); ?>" class="<?php echo esc_attr( $wrapper_classes ); ?>">
		<div class="wd-fb-wrap">
			<div class="<?php echo esc_attr( $btn_classes ); ?>">
				<a title="<?php esc_html_e( 'Close', 'woodmart' ); ?>" href="#" rel="nofollow">
					<span><?php esc_html_e( 'Close', 'woodmart' ); ?></span>
				</a>
			</div>
			<div class="wd-fb">
				<?php if ( ! empty( $bg_image['id'] ) ) : ?>
					<div class="wd-fb-bg wd-fill">
						<?php
						$image_size = isset( $bg_image['size'] ) ? $bg_image['size'] : 'full';
						echo woodmart_otf_get_image_html(
							$bg_image['id'],
							$image_size,
							false
						);
						?>
					</div>
				<?php endif; ?>
				<div class="wd-fb-inner wd-scroll-content wd-entry-content">
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<?php the_content(); ?>
					<?php endwhile; ?>
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
