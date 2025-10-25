<?php
/**
 * Post content map.
 *
 * @package Woodmart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_get_vc_map_my_account_nav' ) ) {
	/**
	 * Post content map.
	 */
	function woodmart_get_vc_map_my_account_nav() {
		$item_typography = woodmart_get_typography_map(
			array(
				'key'      => 'item_typography',
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'selector' => '{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li > a',
			)
		);

		return array(
			'base'        => 'woodmart_my_account_nav',
			'name'        => esc_html__( 'My account navigation', 'woodmart' ),
			'category'    => woodmart_get_tab_title_category_for_wpb( esc_html__( 'My account elements', 'woodmart' ) ),
			'description' => esc_html__( 'My account navigation menu', 'woodmart' ),
			'icon'        => WOODMART_ASSETS . '/images/vc-icon/ma-icons/navigation.svg',
			'params'      => array(
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'General', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'general_divider',
				),
				array(
					'type'       => 'woodmart_css_id',
					'param_name' => 'woodmart_css_id',
					'group'      => esc_html__( 'Style', 'woodmart' ),
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Orientation', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'orientation',
					'value'            => array(
						esc_html__( 'Vertical', 'woodmart' )   => 'vertical',
						esc_html__( 'Horizontal', 'woodmart' ) => 'horizontal',
					),
					'std'              => 'vertical',
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'layout_type',
					'heading'    => esc_html__( 'Layout', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'value'      => array(
						esc_html__( 'Inline', 'woodmart' ) => 'inline',
						esc_html__( 'Grid', 'woodmart' )   => 'grid',
					),
					'dependency' => array(
						'element' => 'orientation',
						'value'   => array( 'horizontal' ),
					),
					'std'        => 'inline',
				),
				array(
					'type'             => 'woodmart_button_set',
					'heading'          => esc_html__( 'Columns', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'columns_tabs',
					'tabs'             => true,
					'value'            => array(
						esc_html__( 'Desktop', 'woodmart' ) => 'desktop',
						esc_html__( 'Tablet', 'woodmart' ) => 'tablet',
						esc_html__( 'Mobile', 'woodmart' ) => 'mobile',
					),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'default'          => 'desktop',
					'edit_field_class' => 'wd-res-control wd-custom-width vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'param_name'       => 'nav_columns',
					'value'            => array(
						'1'  => '1',
						'2'  => '2',
						'3'  => '3',
						'4'  => '4',
						'5'  => '5',
						'6'  => '6',
						'7'  => '7',
						'8'  => '8',
						'9'  => '9',
						'10' => '10',
						'11' => '11',
						'12' => '12',
					),
					'std'              => '3',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'wd_dependency'    => array(
						'element' => 'columns_tabs',
						'value'   => array( 'desktop' ),
					),
					'edit_field_class' => 'wd-res-item vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'param_name'       => 'nav_columns_tablet',
					'value'            => array(
						esc_html__( 'Auto', 'woodmart' ) => 'auto',
						'1'                              => '1',
						'2'                              => '2',
						'3'                              => '3',
						'4'                              => '4',
						'5'                              => '5',
						'6'                              => '6',
						'7'                              => '7',
						'8'                              => '8',
						'9'                              => '9',
						'10'                             => '10',
						'11'                             => '11',
						'12'                             => '12',
					),
					'std'              => 'auto',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'wd_dependency'    => array(
						'element' => 'columns_tabs',
						'value'   => array( 'tablet' ),
					),
					'edit_field_class' => 'wd-res-item vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'param_name'       => 'nav_columns_mobile',
					'value'            => array(
						esc_html__( 'Auto', 'woodmart' ) => 'auto',
						'1'                              => '1',
						'2'                              => '2',
						'3'                              => '3',
						'4'                              => '4',
						'5'                              => '5',
						'6'                              => '6',
						'7'                              => '7',
						'8'                              => '8',
						'9'                              => '9',
						'10'                             => '10',
						'11'                             => '11',
						'12'                             => '12',
					),
					'std'              => 'auto',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'wd_dependency'    => array(
						'element' => 'columns_tabs',
						'value'   => array( 'mobile' ),
					),
					'edit_field_class' => 'wd-res-item vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'woodmart_button_set',
					'heading'          => esc_html__( 'Space between', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'spacing_tabs',
					'tabs'             => true,
					'value'            => array(
						esc_html__( 'Desktop', 'woodmart' ) => 'desktop',
						esc_html__( 'Tablet', 'woodmart' ) => 'tablet',
						esc_html__( 'Mobile', 'woodmart' ) => 'mobile',
					),
					'default'          => 'desktop',
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'edit_field_class' => 'wd-res-control wd-custom-width vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'param_name'       => 'nav_spacing',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						30 => 30,
						20 => 20,
						10 => 10,
						6  => 6,
						2  => 2,
						0  => 0,
					),
					'std'              => '',
					'wd_dependency'    => array(
						'element' => 'spacing_tabs',
						'value'   => array( 'desktop' ),
					),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'edit_field_class' => 'wd-res-item vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'param_name'       => 'nav_spacing_tablet',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						30 => 30,
						20 => 20,
						10 => 10,
						6  => 6,
						2  => 2,
						0  => 0,
					),
					'std'              => '',
					'wd_dependency'    => array(
						'element' => 'spacing_tabs',
						'value'   => array( 'tablet' ),
					),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'edit_field_class' => 'wd-res-item vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'param_name'       => 'nav_spacing_mobile',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => '',
						30 => 30,
						20 => 20,
						10 => 10,
						6  => 6,
						2  => 2,
						0  => 0,
					),
					'std'              => '',
					'wd_dependency'    => array(
						'element' => 'spacing_tabs',
						'value'   => array( 'mobile' ),
					),
					'dependency'       => array(
						'element' => 'layout_type',
						'value'   => array( 'grid' ),
					),
					'edit_field_class' => 'wd-res-item vc_col-sm-12 vc_column',
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
						'element' => 'orientation',
						'value'   => array( 'horizontal' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'heading'          => esc_html__( 'Color scheme', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'dropdown',
					'param_name'       => 'tabs_title_text_color_scheme',
					'value'            => array(
						esc_html__( 'Inherit', 'woodmart' ) => 'inherit',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' )  => 'dark',
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Items', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'items_divider',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Style', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'nav_design',
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => 'simple',
						esc_html__( 'Bordered', 'woodmart' ) => 'default',
						esc_html__( 'Background', 'woodmart' ) => 'with-bg',
					),
					'std'              => 'simple',
					'dependency'       => array(
						'element' => 'orientation',
						'value'   => array( 'vertical' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Style', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'style',
					'value'            => array(
						esc_html__( 'Default', 'woodmart' )    => 'default',
						esc_html__( 'Underline', 'woodmart' )  => 'underline',
					),
					'std'              => 'default',
					'dependency'       => array(
						'element' => 'orientation',
						'value'   => array( 'horizontal' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Gap', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'vertical_items_gap',
					'std'              => 'm',
					'value'            => array(
						esc_html__( 'Small', 'woodmart' )  => 's',
						esc_html__( 'Medium', 'woodmart' ) => 'm',
						esc_html__( 'Large', 'woodmart' )  => 'l',
						esc_html__( 'Custom', 'woodmart' ) => 'custom',
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'dependency'       => array(
						'element' => 'nav_design',
						'value'   => array( 'simple' ),
					),
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Gap', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'items_gap',
					'value'            => array(
						esc_html__( 'Small', 'woodmart' )  => 's',
						esc_html__( 'Medium', 'woodmart' ) => 'm',
						esc_html__( 'Large', 'woodmart' )  => 'l',
						esc_html__( 'Custom', 'woodmart' ) => 'custom',
					),
					'std'              => 'm',
					'dependency'       => array(
						'element' => 'orientation',
						'value'   => array( 'horizontal' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Custom gap', 'woodmart' ),
					'param_name'    => 'custom_vertical_items_gap',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
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
						'element' => 'orientation',
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
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
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
						'element' => 'orientation',
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
				array(
					'type'             => 'woodmart_button_set',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'items_tabs',
					'value'            => array(
						esc_html__( 'Idle', 'woodmart' )   => 'idle',
						esc_html__( 'Hover', 'woodmart' )  => 'hover',
						esc_html__( 'Active', 'woodmart' ) => 'active',
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'nav_color',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-color: {{VALUE}};',
						),
					),
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'nav_color_hover',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-color-hover: {{VALUE}};',
						),
					),
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'nav_color_active',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-color-active: {{VALUE}};',
						),
					),
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				// Background.
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'param_name'       => 'nav_bg_color_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'nav_bg_color',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-bg: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'nav_bg_color_enable',
						'value'   => array( 'yes' ),
					),
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'param_name'       => 'nav_bg_hover_color_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'nav_bg_color_hover',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-bg-hover: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'nav_bg_hover_color_enable',
						'value'   => array( 'yes' ),
					),
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'param_name'       => 'nav_bg_active_color_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'nav_bg_color_active',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-bg-active: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'nav_bg_active_color_enable',
						'value'   => array( 'yes' ),
					),
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
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
					'param_name'       => 'nav_border_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'nav_border_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li > a' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'       => array(
						'element' => 'nav_border_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border width', 'woodmart' ),
					'param_name'    => 'nav_border_width',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li > a' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Border color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'nav_border_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li > a' => array(
							'border-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border radius', 'woodmart' ),
					'param_name'    => 'nav_border_radius',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_enable',
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
					'param_name'       => 'nav_border_hover_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'nav_border_hover_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li:hover > a' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'       => array(
						'element' => 'nav_border_hover_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border width', 'woodmart' ),
					'param_name'    => 'nav_border_hover_width',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li:hover > a' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_hover_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Border color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'nav_border_hover_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li:hover > a' => array(
							'border-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_hover_enable',
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
					'param_name'       => 'nav_border_active_enable',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'nav_border_active_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li.wd-active > a' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'       => array(
						'element' => 'nav_border_active_enable',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'          => 'wd_slider',
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'heading'       => esc_html__( 'Border width', 'woodmart' ),
					'param_name'    => 'nav_border_active_width',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li.wd-active > a' => array(
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
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_active_enable',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'       => esc_html__( 'Border color', 'woodmart' ),
					'group'         => esc_html__( 'Style', 'woodmart' ),
					'type'          => 'wd_colorpicker',
					'param_name'    => 'nav_border_active_color',
					'selectors'     => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li.wd-active > a' => array(
							'border-color: {{VALUE}};',
						),
					),
					'wd_dependency' => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'    => array(
						'element' => 'nav_border_active_enable',
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
					'param_name'    => 'nav_box_shadow_enable',
					'true_state'    => 'yes',
					'false_state'   => 'no',
					'default'       => 'no',
					'wd_dependency' => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
				),
				array(
					'type'             => 'wd_box_shadow',
					'param_name'       => 'nav_box_shadow',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li > a' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'idle' ),
					),
					'dependency'       => array(
						'element' => 'nav_box_shadow_enable',
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
					'param_name'    => 'nav_box_shadow_hover_enable',
					'true_state'    => 'yes',
					'false_state'   => 'no',
					'default'       => 'no',
					'wd_dependency' => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
				),
				array(
					'type'             => 'wd_box_shadow',
					'param_name'       => 'nav_box_shadow_hover',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li:hover > a' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'hover' ),
					),
					'dependency'       => array(
						'element' => 'nav_box_shadow_hover_enable',
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
					'param_name'    => 'nav_box_shadow_active_enable',
					'true_state'    => 'yes',
					'false_state'   => 'no',
					'default'       => 'no',
					'wd_dependency' => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
				),
				array(
					'type'             => 'wd_box_shadow',
					'param_name'       => 'nav_box_shadow_active',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li.wd-active > a' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'wd_dependency'    => array(
						'element' => 'items_tabs',
						'value'   => array( 'active' ),
					),
					'dependency'       => array(
						'element' => 'nav_box_shadow_active_enable',
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
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
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
				// Icons.
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Icons', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'icons_divider',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Show icons', 'woodmart' ),
					'param_name'       => 'show_icons',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'yes',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Alignment', 'woodmart' ),
					'param_name'       => 'icon_alignment',
					'value'            => array(
						esc_html__( 'Left', 'woodmart' )  => 'left',
						esc_html__( 'Right', 'woodmart' ) => 'right',
						esc_html__( 'Top', 'woodmart' )   => 'top',
					),
					'wd_dependency'    => array(
						'element' => 'show_icons',
						'value'   => array( 'yes' ),
					),
					'std'              => 'left',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'wd_slider',
					'param_name'       => 'icon_size',
					'heading'          => esc_html__( 'Size', 'woodmart' ),
					'devices'          => array(
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
					'range'            => array(
						'px' => array(
							'min'  => 1,
							'max'  => 50,
							'step' => 1,
						),
					),
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc' => array(
							'--nav-icon-size: {{VALUE}}{{UNIT}};',
						),
					),
					'dependency'       => array(
						'element' => 'show_icons',
						'value'   => array( 'yes' ),
					),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_button_set',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'icons_color_tabs',
					'value'            => array(
						esc_html__( 'Idle', 'woodmart' )   => 'idle',
						esc_html__( 'Hover', 'woodmart' )  => 'hover',
						esc_html__( 'Active', 'woodmart' ) => 'active',
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'dependency'       => array(
						'element' => 'show_icons',
						'value'   => array( 'yes' ),
					),
				),
				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'icons_color',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li > a > .wd-nav-icon' => array(
							'color: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'show_icons',
						'value'   => array( 'yes' ),
					),
					'wd_dependency'    => array(
						'element' => 'icons_color_tabs',
						'value'   => array( 'idle' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'icons_color_hover',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li:hover > a > .wd-nav-icon' => array(
							'color: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'show_icons',
						'value'   => array( 'yes' ),
					),
					'wd_dependency'    => array(
						'element' => 'icons_color_tabs',
						'value'   => array( 'hover' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'icons_color_active',
					'selectors'        => array(
						'{{WRAPPER}}.wd-el-my-acc-nav .wd-nav-my-acc > li.wd-active > a > .wd-nav-icon' => array(
							'color: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'show_icons',
						'value'   => array( 'yes' ),
					),
					'wd_dependency'    => array(
						'element' => 'icons_color_tabs',
						'value'   => array( 'active' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'woodmart' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'woodmart' ),
				),
				woodmart_get_vc_responsive_spacing_map(),
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
