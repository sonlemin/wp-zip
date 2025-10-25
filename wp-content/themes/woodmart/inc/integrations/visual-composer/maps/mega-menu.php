<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'woodmart_get_vc_map_mega_menu' ) ) {
	function woodmart_get_vc_map_mega_menu() {
		$item_typography = woodmart_get_typography_map(
			array(
				'title'    => esc_html__( 'Typography', 'woodmart' ),
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'key'      => 'item_typography',
				'selector' => '{{WRAPPER}}.wd-menu > .wd-nav > li > a',
			)
		);

		return array(
			'name'        => esc_html__( 'Menu', 'woodmart' ),
			'base'        => 'woodmart_mega_menu',
			'category'    => woodmart_get_tab_title_category_for_wpb( esc_html__( 'Theme elements', 'woodmart' ) ),
			'description' => esc_html__( 'Wordpress menu', 'woodmart' ),
			'icon'        => WOODMART_ASSETS . '/images/vc-icon/mega-menu-widget.svg',
			'params'      => array(
				/**
				 * General tab.
				 * General section.
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'General', 'woodmart' ),
					'param_name' => 'general_divider',
				),
				array(
					'type'       => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id',
				),
				array(
					'type'          => 'textfield',
					'heading'       => esc_html__( 'Title', 'woodmart' ),
					'param_name'    => 'title',
					'wd_dependency' => array(
						'element' => 'design',
						'value'   => array( 'vertical' ),
					),
				),
				array(
					'type'       => 'woodmart_dropdown',
					'heading'    => esc_html__( 'Choose Menu', 'woodmart' ),
					'param_name' => 'nav_menu',
					'callback'   => 'woodmart_get_menus_array',
				),
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider',
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint'       => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' ),
				),
				/**
				 * Style tab.
				 * General section.
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'holder'     => 'div',
					'title'      => esc_html__( 'General', 'woodmart' ),
					'param_name' => 'general_style_section',
				),
				array(
					'type'       => 'dropdown',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'heading'    => esc_html__( 'Orientation', 'woodmart' ),
					'param_name' => 'design',
					'value'      => array(
						esc_html__( 'Vertical', 'woodmart' )   => 'vertical',
						esc_html__( 'Horizontal', 'woodmart' ) => 'horizontal',
					),
					'std'        => 'vertical',
				),
				array(
					'type'             => 'woodmart_image_select',
					'heading'          => esc_html__( 'Alignment', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'alignment',
					'value'            => array(
						esc_html__( 'Left', 'woodmart' )   => 'left',
						esc_html__( 'Center', 'woodmart' ) => 'center',
						esc_html__( 'Right', 'woodmart' )  => 'right',
					),
					'images_value'     => array(
						'center' => WOODMART_ASSETS_IMAGES . '/settings/align/center.jpg',
						'left'   => WOODMART_ASSETS_IMAGES . '/settings/align/left.jpg',
						'right'  => WOODMART_ASSETS_IMAGES . '/settings/align/right.jpg',
					),
					'wood_tooltip'     => true,
					'dependency'       => array(
						'element' => 'design',
						'value'   => array( 'horizontal' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'type'       => 'woodmart_colorpicker',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'heading'    => esc_html__( 'Title background color', 'woodmart' ),
					'param_name' => 'color',
					'css_args'   => array(
						'background-color' => array(
							' .widget-title',
						),
					),
					'dependency' => array(
						'element' => 'design',
						'value'   => array( 'vertical' ),
					),
				),
				array(
					'type'       => 'woodmart_button_set',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'heading'    => esc_html__( 'Title color scheme', 'woodmart' ),
					'param_name' => 'woodmart_color_scheme',
					'value'      => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' )  => 'dark',
					),
					'dependency' => array(
						'element' => 'design',
						'value'   => array( 'vertical' ),
					),
				),
				/**
				 * Style tab.
				 * Items section.
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'holder'     => 'div',
					'title'      => esc_html__( 'Items', 'woodmart' ),
					'param_name' => 'items_style_section',
				),
				array(
					'type'          => 'dropdown',
					'heading'       => esc_html__( 'Style', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'param_name'    => 'dropdown_design',
					'value'         => array(
						esc_html__( 'Bordered', 'woodmart' ) => 'default',
						esc_html__( 'Simple', 'woodmart' ) => 'simple',
						esc_html__( 'Background', 'woodmart' ) => 'with-bg',
					),
					'wd_dependency' => array(
						'element' => 'design',
						'value'   => array( 'vertical' ),
					),
					'std'           => 'default',
				),
				array(
					'type'          => 'dropdown',
					'heading'       => esc_html__( 'Style', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'param_name'    => 'style',
					'value'         => array(
						esc_html__( 'Default', 'woodmart' )    => 'default',
						esc_html__( 'Underline', 'woodmart' )  => 'underline',
						esc_html__( 'Bordered', 'woodmart' )   => 'bordered',
						esc_html__( 'Separated', 'woodmart' )  => 'separated',
						esc_html__( 'Background', 'woodmart' ) => 'bg',
					),
					'wd_dependency' => array(
						'element' => 'design',
						'value'   => array( 'horizontal' ),
					),
					'std'           => 'default',
				),
				array(
					'type'          => 'dropdown',
					'heading'       => esc_html__( 'Gap', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'param_name'    => 'vertical_items_gap',
					'value'         => array(
						esc_html__( 'Small', 'woodmart' )  => 's',
						esc_html__( 'Medium', 'woodmart' ) => 'm',
						esc_html__( 'Large', 'woodmart' )  => 'l',
						esc_html__( 'Custom', 'woodmart' ) => 'custom',
					),
					'dependency'    => array(
						'element' => 'dropdown_design',
						'value'   => array( 'simple' ),
					),
					'wd_dependency' => array(
						'element' => 'design',
						'value'   => array( 'vertical' ),
					),
				),
				array(
					'type'       => 'dropdown',
					'heading'    => esc_html__( 'Gap', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'items_gap',
					'value'      => array(
						esc_html__( 'Small', 'woodmart' )  => 's',
						esc_html__( 'Medium', 'woodmart' ) => 'm',
						esc_html__( 'Large', 'woodmart' )  => 'l',
						esc_html__( 'Custom', 'woodmart' ) => 'custom',
					),
					'dependency' => array(
						'element' => 'design',
						'value'   => array( 'horizontal' ),
					),
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Custom gap', 'woodmart' ),
					'param_name'    => 'custom_vertical_items_gap',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-gap: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'       => array(
						'desktop' => array(
							'value' => '',
							'unit'  => 'px',
						),
						'tablet'  => array(
							'value' => '',
							'unit'  => 'px',
						),
						'mobile'  => array(
							'value' => '',
							'unit'  => 'px',
						),
					),
					'range'         => array(
						'px' => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
					),
					'dependency'    => array(
						'element' => 'design',
						'value'   => array( 'vertical' ),
					),
					'wd_dependency' => array(
						'element' => 'vertical_items_gap',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Custom gap', 'woodmart' ),
					'param_name'    => 'custom_items_gap',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-gap: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'       => array(
						'desktop' => array(
							'value' => '',
							'unit'  => 'px',
						),
						'tablet'  => array(
							'value' => '',
							'unit'  => 'px',
						),
						'mobile'  => array(
							'value' => '',
							'unit'  => 'px',
						),
					),
					'range'         => array(
						'px' => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
					),
					'dependency'    => array(
						'element' => 'design',
						'value'   => array( 'horizontal' ),
					),
					'wd_dependency' => array(
						'element' => 'items_gap',
						'value'   => array( 'custom' ),
					),
				),
				$item_typography['font_family'],
				$item_typography['font_size'],
				$item_typography['font_weight'],
				$item_typography['text_transform'],
				$item_typography['font_style'],
				$item_typography['line_height'],
				/**
				 * Style tab.
				 * Items tabs.
				 */
				array(
					'type'       => 'woodmart_button_set',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'items_color_tabs',
					'tabs'       => true,
					'value'      => array(
						esc_html__( 'Idle', 'woodmart' )   => 'idle',
						esc_html__( 'Hover', 'woodmart' )  => 'hover',
						esc_html__( 'Active', 'woodmart' ) => 'active',
					),
					'default'    => 'idle',
				),
				/**
				 * Disable active style.
				 */
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Disable active style', 'woodmart' ),
					'param_name'       => 'disable_active_style',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),

				/**
				 * Color.
				 */
				array(
					'heading'       => esc_html__( 'Color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'items_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'items_hover_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-color-hover: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'items_active_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-color-active: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
				),
				/**
				 * Background color.
				 */
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'param_name'       => 'items_bg_color_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'items_bg_color',
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-bg: {{VALUE}};',
						),
					),
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'       => array(
						'element' => 'items_bg_color_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'param_name'       => 'items_bg_hover_color_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'items_bg_hover_color',
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-bg-hover: {{VALUE}};',
						),
					),
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'       => array(
						'element' => 'items_bg_hover_color_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'param_name'       => 'items_bg_active_color_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'items_bg_active_color',
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-bg-active: {{VALUE}};',
						),
					),
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'       => array(
						'element' => 'items_bg_active_color_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'type'       => 'woodmart_empty_space',
					'param_name' => 'woodmart_empty_space',
				),
				/**
				 * Border idle.
				 */
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Border', 'woodmart' ),
					'param_name'       => 'items_border_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'items_border_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > li > a' => array(
							'border-style: {{VALUE}};',
						),
					),
					'devices'          => array(
						'desktop' => array(
							'value' => '',
						),
					),
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						esc_html__( 'None', 'woodmart' )   => 'none',
						esc_html__( 'Solid', 'woodmart' )  => 'solid',
						esc_html__( 'Dotted', 'woodmart' ) => 'dotted',
						esc_html__( 'Double', 'woodmart' ) => 'double',
						esc_html__( 'Dashed', 'woodmart' ) => 'dashed',
						esc_html__( 'Groove', 'woodmart' ) => 'groove',
					),
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'       => array(
						'element' => 'items_border_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border width', 'woodmart' ),
					'param_name'    => 'items_border_width',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > li > a' => array(
							'border-width: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'       => array(
						'desktop' => array(
							'unit' => 'px',
						),
					),
					'range'         => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'    => array(
						'element' => 'items_border_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Border color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'items_border_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > li > a' => array(
							'border-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'    => array(
						'element' => 'items_border_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border radius', 'woodmart' ),
					'param_name'    => 'items_border_radius',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-radius: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'       => array(
						'desktop' => array(
							'unit' => 'px',
						),
					),
					'range'         => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'    => array(
						'element' => 'items_border_enable',
						'value'   => array( 'yes' ),
					),
				),
				/**
				 * Border hover.
				 */
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Border', 'woodmart' ),
					'param_name'       => 'items_border_hover_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'items_border_hover_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > :is(li:hover, li.wd-opened) > a' => array(
							'border-style: {{VALUE}};',
						),
					),
					'devices'          => array(
						'desktop' => array(
							'value' => '',
						),
					),
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						esc_html__( 'None', 'woodmart' )   => 'none',
						esc_html__( 'Solid', 'woodmart' )  => 'solid',
						esc_html__( 'Dotted', 'woodmart' ) => 'dotted',
						esc_html__( 'Double', 'woodmart' ) => 'double',
						esc_html__( 'Dashed', 'woodmart' ) => 'dashed',
						esc_html__( 'Groove', 'woodmart' ) => 'groove',
					),
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'       => array(
						'element' => 'items_border_hover_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border width', 'woodmart' ),
					'param_name'    => 'items_border_hover_width',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > :is(li:hover, li.wd-opened) > a' => array(
							'border-width: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'       => array(
						'desktop' => array(
							'unit' => 'px',
						),
					),
					'range'         => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'    => array(
						'element' => 'items_border_hover_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Border color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'items_border_hover_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > :is(li:hover, li.wd-opened) > a' => array(
							'border-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'    => array(
						'element' => 'items_border_hover_enable',
						'value'   => array( 'yes' ),
					),
				),
				/**
				 * Border active.
				 */
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Border', 'woodmart' ),
					'param_name'       => 'items_border_active_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'items_border_active_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav:where(:not(.wd-dis-act)) > li.current-menu-item > a' => array(
							'border-style: {{VALUE}};',
						),
					),
					'devices'          => array(
						'desktop' => array(
							'value' => '',
						),
					),
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						esc_html__( 'None', 'woodmart' )   => 'none',
						esc_html__( 'Solid', 'woodmart' )  => 'solid',
						esc_html__( 'Dotted', 'woodmart' ) => 'dotted',
						esc_html__( 'Double', 'woodmart' ) => 'double',
						esc_html__( 'Dashed', 'woodmart' ) => 'dashed',
						esc_html__( 'Groove', 'woodmart' ) => 'groove',
					),
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'       => array(
						'element' => 'items_border_active_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border width', 'woodmart' ),
					'param_name'    => 'items_border_active_width',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav:where(:not(.wd-dis-act)) > li.current-menu-item > a' => array(
							'border-width: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'       => array(
						'desktop' => array(
							'unit' => 'px',
						),
					),
					'range'         => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'    => array(
						'element' => 'items_border_active_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Border color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'items_border_active_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-menu > .wd-nav:where(:not(.wd-dis-act)) > li.current-menu-item > a' => array(
							'border-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'    => array(
						'element' => 'items_border_active_enable',
						'value'   => array( 'yes' ),
					),
				),
				/**
				 * Box shadow idle.
				 */
				array(
					'type'          => 'woodmart_switch',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Box shadow', 'woodmart' ),
					'param_name'    => 'items_box_shadow_enable',
					'true_state'    => 'yes',
					'false_state'   => 'no',
					'default'       => 'no',
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
				),
				array(
					'type'             => 'wd_box_shadow',
					'param_name'       => 'items_box_shadow',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > li > a' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'       => array(
						'element' => 'items_box_shadow_enable',
						'value'   => array( 'yes' ),
					),
					'default'          => array(
						'horizontal' => '0',
						'vertical'   => '0',
						'blur'       => '9',
						'spread'     => '0',
						'color'      => 'rgba(0, 0, 0, .15)',
					),
				),
				/**
				 * Box shadow hover.
				 */
				array(
					'type'          => 'woodmart_switch',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Box shadow', 'woodmart' ),
					'param_name'    => 'items_box_shadow_hover_enable',
					'true_state'    => 'yes',
					'false_state'   => 'no',
					'default'       => 'no',
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
				),
				array(
					'type'             => 'wd_box_shadow',
					'param_name'       => 'items_box_shadow_hover',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > :is(li:hover, li.wd-opened) > a' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'       => array(
						'element' => 'items_box_shadow_hover_enable',
						'value'   => array( 'yes' ),
					),
					'default'          => array(
						'horizontal' => '0',
						'vertical'   => '0',
						'blur'       => '9',
						'spread'     => '0',
						'color'      => 'rgba(0, 0, 0, .15)',
					),
				),
				/**
				 * Box shadow active.
				 */
				array(
					'type'          => 'woodmart_switch',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Box shadow', 'woodmart' ),
					'param_name'    => 'items_box_shadow_active_enable',
					'true_state'    => 'yes',
					'false_state'   => 'no',
					'default'       => 'no',
					'wd_dependency' => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
				),
				array(
					'type'             => 'wd_box_shadow',
					'param_name'       => 'items_box_shadow_active',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'selectors'        => array(
						'{{WRAPPER}}.wd-menu > .wd-nav:where(:not(.wd-dis-act)) > li.current-menu-item > a' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'wd_dependency'    => array(
						'element' => 'items_color_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'       => array(
						'element' => 'items_box_shadow_active_enable',
						'value'   => array( 'yes' ),
					),
					'default'          => array(
						'horizontal' => '0',
						'vertical'   => '0',
						'blur'       => '9',
						'spread'     => '0',
						'color'      => 'rgba(0, 0, 0, .15)',
					),
				),
				/**
				 * Padding.
				 */
				array(
					'heading'    => esc_html__( 'Padding', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'type'       => 'wd_dimensions',
					'param_name' => 'items_padding',
					'selectors'  => array(
						'{{WRAPPER}}.wd-menu > .wd-nav' => array(
							'--nav-pd: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'devices'    => array(
						'desktop' => array(
							'unit' => 'px',
						),
						'tablet'  => array(
							'unit' => 'px',
						),
						'mobile'  => array(
							'unit' => 'px',
						),
					),
					'range'      => array(
						'px' => array(),
					),
				),
				/**
				 * Style tab.
				 * Items icon section.
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'holder'     => 'div',
					'title'      => esc_html__( 'Items icon', 'woodmart' ),
					'param_name' => 'icon_divider',
				),
				array(
					'type'       => 'dropdown',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'heading'    => esc_html__( 'Alignment', 'woodmart' ),
					'param_name' => 'icon_alignment',
					'value'      => array(
						esc_html__( 'Default', 'woodmart' ) => 'inherit',
						esc_html__( 'Left', 'woodmart' )  => 'left',
						esc_html__( 'Right', 'woodmart' ) => 'right',
					),
				),
				array(
					'type'       => 'wd_slider',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'heading'    => esc_html__( 'Height', 'woodmart' ),
					'param_name' => 'icon_height',
					'selectors'  => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > li > a .wd-nav-img' => array(
							'--nav-img-height: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'    => array(
						'desktop' => array(
							'value' => '',
							'unit'  => 'px',
						),
						'tablet'  => array(
							'value' => '',
							'unit'  => 'px',
						),
						'mobile'  => array(
							'value' => '',
							'unit'  => 'px',
						),
					),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						),
					),
				),
				array(
					'type'       => 'wd_slider',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'heading'    => esc_html__( 'Width', 'woodmart' ),
					'param_name' => 'icon_width',
					'selectors'  => array(
						'{{WRAPPER}}.wd-menu > .wd-nav > li > a .wd-nav-img' => array(
							'--nav-img-width: {{VALUE}}{{UNIT}};',
						),
					),
					'devices'    => array(
						'desktop' => array(
							'value' => '',
							'unit'  => 'px',
						),
						'tablet'  => array(
							'value' => '',
							'unit'  => 'px',
						),
						'mobile'  => array(
							'value' => '',
							'unit'  => 'px',
						),
					),
					'range'      => array(
						'px' => array(
							'min'  => 0,
							'max'  => 50,
							'step' => 1,
						),
					),
				),
				array(
					'type'       => 'css_editor',
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),
				woodmart_get_vc_responsive_spacing_map(),
				/**
				 * Advanced
				 */

				// Width option (with dependency Columns option, responsive).
				woodmart_get_responsive_dependency_width_map( 'responsive_tabs' ),
				woodmart_get_responsive_dependency_width_map( 'width_desktop' ),
				woodmart_get_responsive_dependency_width_map( 'custom_width_desktop' ),
				woodmart_get_responsive_dependency_width_map( 'width_tablet' ),
				woodmart_get_responsive_dependency_width_map( 'custom_width_tablet' ),
				woodmart_get_responsive_dependency_width_map( 'width_mobile' ),
				woodmart_get_responsive_dependency_width_map( 'custom_width_mobile' ),
			),
		);
	}
}
