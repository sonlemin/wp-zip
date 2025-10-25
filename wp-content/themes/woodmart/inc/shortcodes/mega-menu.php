<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\Modules\Mega_Menu_Walker;

/**
* ------------------------------------------------------------------------------------------------
* Mega Menu widget
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_shortcode_mega_menu' ) ) {
	function woodmart_shortcode_mega_menu( $atts, $content ) {
		$output = $title_html = '';
		$class  = apply_filters( 'vc_shortcodes_css_class', '', '', $atts );

		$atts = shortcode_atts(
			array(
				'title'                          => '',
				'nav_menu'                       => '',
				'style'                          => 'default',
				'design'                         => 'vertical',
				'dropdown_design'                => 'default',
				'items_gap'                      => 's',
				'vertical_items_gap'             => 's',
				'alignment'                      => '',
				'color'                          => '',
				'icon_alignment'                 => 'inherit',
				'woodmart_color_scheme'          => 'light',
				'el_class'                       => '',
				'menu_classes'                   => '',
				'el_id'                          => '',
				'woodmart_css_id'                => '',
				'css'                            => '',
				'is_wpb'                         => true,
				'items_bg_color_enable'          => 'no',
				'items_bg_hover_color_enable'    => 'no',
				'items_bg_active_color_enable'   => 'no',
				'items_border_enable'            => 'no',
				'items_border_hover_enable'      => 'no',
				'items_border_active_enable'     => 'no',
				'items_box_shadow_enable'        => 'no',
				'items_box_shadow_hover_enable'  => 'no',
				'items_box_shadow_active_enable' => 'no',
				'disable_active_style'           => 'no',
			),
			$atts
		);

		$title_classes = ' color-scheme-' . esc_attr( $atts['woodmart_color_scheme'] );

		if ( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$class .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		}
		if ( ! empty( $atts['el_class'] ) ) {
			$class .= ' ' . trim( $atts['el_class'] );
		}

		if ( ! empty( $atts['el_id'] ) ) {
			$widget_id = $atts['el_id'];
		} else {
			$widget_id = 'wd-' . $atts['woodmart_css_id'];
		}

		$menu_classes  = ' wd-nav-' . $atts['design'];
		$menu_classes .= woodmart_get_old_classes( ' ' . $atts['design'] . '-navigation' );
		$menu_classes .= woodmart_get_old_classes( ' navigation-style-' . $atts['style'] );

		if ( 'horizontal' === $atts['design'] ) {
			if ( $atts['alignment'] ) {
				$class .= ' text-' . $atts['alignment'];
			}

			$menu_classes .= ' wd-style-' . $atts['style'];
			$menu_classes .= ' wd-gap-' . $atts['items_gap'];
		}

		if ( 'vertical' === $atts['design'] ) {
			$menu_classes .= ' wd-design-' . $atts['dropdown_design'];

			if ( 'simple' === $atts['dropdown_design'] ) {
				$menu_classes .= ' wd-gap-' . $atts['vertical_items_gap'];
			}
		}

		if ( $atts['icon_alignment'] && 'inherit' !== $atts['icon_alignment'] ) {
			$menu_classes .= ' wd-icon-' . $atts['icon_alignment'];
		}

		if ( ! empty( $atts['menu_classes'] ) ) {
			$menu_classes .= $atts['menu_classes'];
		}

		$items_bg_activated      = 'yes' === $atts['items_bg_color_enable'] || 'yes' === $atts['items_bg_hover_color_enable'] || 'yes' === $atts['items_bg_active_color_enable'];
		$items_border_active     = 'yes' === $atts['items_border_enable'] || 'yes' === $atts['items_border_hover_enable'] || 'yes' === $atts['items_border_active_enable'];
		$items_box_shadow_active = 'yes' === $atts['items_box_shadow_enable'] || 'yes' === $atts['items_box_shadow_hover_enable'] || 'yes' === $atts['items_box_shadow_active_enable'];

		if ( $items_bg_activated || $items_box_shadow_active || $items_border_active ) {
			$menu_classes .= ' wd-add-pd';
		}

		if ( 'yes' === $atts['disable_active_style'] ) {
			$menu_classes .= ' wd-dis-act';
		}

		ob_start();

		woodmart_enqueue_inline_style( 'el-menu' );

		if ( ! empty( $atts['is_wpb'] ) ) {
			woodmart_enqueue_inline_style( 'el-menu-wpb-elem' );
		}

		if ( 'vertical' === $atts['design'] ) {
			woodmart_enqueue_inline_style( 'mod-nav-vertical' );
			woodmart_enqueue_inline_style( 'mod-nav-vertical-design-' . $atts['dropdown_design'] );
		}

		if ( 'horizontal' === $atts['design'] && 'bg' === $atts['style'] ) {
			woodmart_enqueue_inline_style( 'bg-navigation' );
		}
		?>

			<div id="<?php echo esc_attr( $widget_id ); ?>" class="wd-menu widget_nav_mega_menu<?php echo esc_attr( $class ); ?>">

				<?php if ( 'vertical' === $atts['design'] && $atts['title'] ) : ?>
					<h5 class="widget-title<?php echo esc_attr( $title_classes ); ?>">
						<?php echo wp_kses( $atts['title'], woodmart_get_allowed_html() ); ?>
					</h5>
				<?php endif; ?>

				<?php
					wp_nav_menu(
						array(
							'fallback_cb' => '',
							'container'   => '',
							'menu'        => $atts['nav_menu'],
							'menu_class'  => 'menu wd-nav' . $menu_classes,
							'walker'      => new Mega_Menu_Walker(),
						)
					);
				?>

				<?php
				if ( $atts['color'] && ! woodmart_is_css_encode( $atts['color'] ) ) {
					$atts['css']  = '#' . esc_attr( $widget_id ) . ' .widget-title {';
					$atts['css'] .= 'background-color: ' . esc_attr( $atts['color'] ) . ';';
					$atts['css'] .= '}';

					wp_add_inline_style( 'woodmart-inline-css', $atts['css'] );
				}
				?>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
