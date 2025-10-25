<?php
/**
 * The template for displaying all html block.
 *
 * @package xts
 */

if ( ! current_user_can( apply_filters( 'woodmart_html_block_access', 'edit_posts' ) ) ) {
	wp_die( 'You do not have access.', '', array( 'back_link' => true ) );
}

get_header();

woodmart_editor_scheme_switcher();
?>

<div class="container">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</div>

<?php

get_footer();
