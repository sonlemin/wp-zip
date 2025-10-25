<?php
/**
 * My account navigation shortcode.
 *
 * @package Woodmart
 */

use XTS\Modules\Layouts\Main;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_shortcode_my_account_nav' ) ) {
	/**
	 * My account navigation shortcode.
	 *
	 * @param array $settings Shortcode settings.
	 * @return string
	 */
	function woodmart_shortcode_my_account_nav( $settings ) {
		$default_settings = array(
			'css'                          => '',
			'nav_columns'                  => 3,
			'nav_columns_tablet'           => '',
			'nav_columns_mobile'           => '',
			'nav_spacing'                  => 30,
			'nav_spacing_tablet'           => '',
			'nav_spacing_mobile'           => '',
			'nav_design'                   => 'simple',
			'layout_type'                  => 'inline',
			'orientation'                  => 'vertical',
			'alignment'                    => 'left',
			'vertical_items_gap'           => 'm',
			'items_gap'                    => 'm',
			'style'                        => 'default',
			'show_icons'                   => 'yes',
			'icon_alignment'               => 'left',
			'tabs_title_text_color_scheme' => 'inherit',
			'nav_bg_color_enable'          => 'no',
			'nav_bg_hover_color_enable'    => 'no',
			'nav_bg_active_color_enable'   => 'no',
			'nav_border_enable'            => 'no',
			'nav_border_hover_enable'      => 'no',
			'nav_border_active_enable'     => 'no',
			'nav_box_shadow_enable'        => 'no',
			'nav_box_shadow_hover_enable'  => 'no',
			'nav_box_shadow_active_enable' => 'no',
		);

		$settings = wp_parse_args( $settings, $default_settings );

		$wrapper_classes = apply_filters( 'vc_shortcodes_css_class', '', '', $settings );

		if ( $settings['css'] ) {
			$wrapper_classes .= ' ' . vc_shortcode_custom_css_class( $settings['css'] );
		}

		$attributes   = '';
		$layout_type  = $settings['layout_type'];
		$orientation  = $settings['orientation'];
		$menu_classes = ' wd-nav-' . $settings['orientation'];
		$show_icons   = 'no' !== $settings['show_icons'];

		if ( 'grid' === $layout_type ) {
			$menu_classes = ' wd-grid-g wd-style-default';

			$grid_atts = array(
				'columns'        => $settings['nav_columns'],
				'columns_tablet' => $settings['nav_columns_tablet'],
				'columns_mobile' => $settings['nav_columns_mobile'],
				'spacing'        => $settings['nav_spacing'],
				'spacing_tablet' => $settings['nav_spacing_tablet'],
				'spacing_mobile' => $settings['nav_spacing_mobile'],
			);

			$attributes .= ' style="' . woodmart_get_grid_attrs( $grid_atts ) . '"';
		}

		if ( $show_icons ) {
			$menu_classes .= ' wd-icon-' . $settings['icon_alignment'];
		}

		if ( 'horizontal' === $orientation ) {
			$wrapper_classes .= ' text-' . woodmart_vc_get_control_data( $settings['alignment'], 'desktop' );
		}

		if ( 'inline' === $layout_type && 'horizontal' === $orientation ) {
			$menu_classes .= ' wd-style-' . $settings['style'];
			$menu_classes .= ' wd-gap-' . $settings['items_gap'];
		}

		if ( 'inline' === $layout_type && 'vertical' === $orientation ) {
			woodmart_enqueue_inline_style( 'mod-nav-vertical' );
			woodmart_enqueue_inline_style( 'mod-nav-vertical-design-' . $settings['nav_design'] );

			$menu_classes .= ' wd-design-' . $settings['nav_design'];
			$menu_classes .= ' wd-gap-' . $settings['vertical_items_gap'];
		}

		if ( 'inherit' !== $settings['tabs_title_text_color_scheme'] ) {
			$wrapper_classes .= ' color-scheme-' . $settings['tabs_title_text_color_scheme'];
		}

		$nav_bg_activated      = 'yes' === $settings['nav_bg_color_enable'] || 'yes' === $settings['nav_bg_hover_color_enable'] || 'yes' === $settings['nav_bg_active_color_enable'];
		$nav_border_active     = 'yes' === $settings['nav_border_enable'] || 'yes' === $settings['nav_border_hover_enable'] || 'yes' === $settings['nav_border_active_enable'];
		$nav_box_shadow_active = 'yes' === $settings['nav_box_shadow_enable'] || 'yes' === $settings['nav_box_shadow_hover_enable'] || 'yes' === $settings['nav_box_shadow_active_enable'];

		if ( $nav_bg_activated || $nav_box_shadow_active || $nav_border_active ) {
			$menu_classes .= ' wd-add-pd';
		}

		ob_start();
		Main::setup_preview();
		?>
		<div class="wd-wpb wd-el-my-acc-nav<?php echo esc_attr( $wrapper_classes ); ?>">
			<?php woodmart_account_navigation( $menu_classes, $show_icons, $attributes ); ?>
		</div>
		<?php

		Main::restore_preview();
		return ob_get_clean();
	}
}
