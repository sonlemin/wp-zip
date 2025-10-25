<?php
use XTS\Modules\Mega_Menu_Walker;

$menu_style = ( $params['menu_style'] ) ? $params['menu_style'] : 'default';
$location   = 'main-menu';
$classes    = 'whb-' . $id;
$classes   .= ' text-' . $params['menu_align'];
$aria_label = esc_attr__( 'Secondary navigation', 'woodmart' );

if ( 'bordered' === $params['menu_style'] ) {
	$classes .= ' wd-full-height';
}

$menu_classes = ' wd-style-' . $menu_style;
if ( isset( $params['items_gap'] ) ) {
	$menu_classes .= ' wd-gap-' . $params['items_gap'];
}

if ( ! empty( $params['icon_alignment'] ) && 'inherit' !== $params['icon_alignment'] ) {
	$menu_classes .= ' wd-icon-' . $params['icon_alignment'];
}

if ( isset( $params['inline'] ) && $params['inline'] ) {
	$classes = ' wd-inline';
}

if ( ! empty( $params['bg_overlay'] ) ) {
	woodmart_enqueue_js_script( 'menu-overlay' );

	$classes .= ' wd-with-overlay';
}

$classes .= woodmart_get_old_classes( ' navigation-style-' . $menu_style );

$menu_object = wp_get_nav_menu_object( $params['menu_id'] );

if ( ! empty( $menu_object ) && $menu_object->name ) {
	$aria_label = $menu_object->name;
}

if ( 'bg' === $params['menu_style'] ) {
	woodmart_enqueue_inline_style( 'bg-navigation' );
}
?>

<nav class="wd-header-nav wd-header-secondary-nav <?php echo esc_attr( $classes ); ?>" role="navigation" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<?php
	if ( $menu_object && wp_get_nav_menu_items( $params['menu_id'] ) ) {
		wp_nav_menu(
			array(
				'container'  => '',
				'menu'       => $params['menu_id'],
				'menu_class' => 'menu wd-nav wd-nav-header wd-nav-secondary' . $menu_classes,
				'walker'     => new Mega_Menu_Walker(),
			)
		);
	} elseif ( $params['menu_id'] ) {
		?>
		<span>
			<?php esc_html_e( 'Wrong menu selected', 'woodmart' ); ?>
		</span>
		<?php
	} else {
		?>
		<span>
			<?php esc_html_e( 'Choose menu', 'woodmart' ); ?>
		</span>
		<?php
	}
	?>
</nav>
