<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}
/**
* ------------------------------------------------------------------------------------------------
* Countdown timer element map
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_get_vc_map_countdown_timer' ) ) {
	function woodmart_get_vc_map_countdown_timer() {
		$time_typography = woodmart_get_typography_map(
			array(
				'key'              => 'time',
				'title'            => esc_html__( 'Numbers typography', 'woodmart' ),
				'group'            => esc_html__( 'Style', 'woodmart' ),
				'selector'         => '{{WRAPPER}} .wd-timer-value',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			)
		);

		$label_typography = woodmart_get_typography_map(
			array(
				'key'              => 'label',
				'title'            => esc_html__( 'Labels typography', 'woodmart' ),
				'group'            => esc_html__( 'Style', 'woodmart' ),
				'selector'         => '{{WRAPPER}} .wd-timer-text',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			)
		);

		return array(
			'name'        => esc_html__( 'Countdown timer', 'woodmart' ),
			'base'        => 'woodmart_countdown_timer',
			'category'    => woodmart_get_tab_title_category_for_wpb( esc_html__( 'Theme elements', 'woodmart' ) ),
			'description' => esc_html__( 'Shows countdown timer', 'woodmart' ),
			'icon'        => WOODMART_ASSETS . '/images/vc-icon/countdown-timer.svg',
			'params'      => array(
				array(
					'param_name' => 'woodmart_css_id',
					'type'       => 'woodmart_css_id',
				),
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'General', 'woodmart' ),
					'param_name' => 'date_divider',
				),
				array(
					'type'             => 'woodmart_datepicker',
					'heading'          => esc_html__( 'Date', 'woodmart' ),
					'param_name'       => 'date',
					'hint'             => esc_html__( 'Final date in the format Y/m/d. For example 2020/12/12 13:00', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Hide countdown on finish', 'woodmart' ),
					'param_name'       => 'hide_on_finish',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Labels', 'woodmart' ),
					'param_name'       => 'labels',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'yes',
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Separator', 'woodmart' ),
					'param_name'       => 'separator',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Text', 'woodmart' ),
					'param_name'       => 'separator_text',
					'std'              => ':',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency'       => array(
						'element' => 'separator',
						'value'   => array( 'yes' ),
					),
				),

				/**
				 * Style
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'General', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'param_name' => 'style_divider',
				),
				array(
					'type'             => 'woodmart_dropdown',
					'heading'          => esc_html__( 'Layout', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'layout',
					'value'            => array(
						esc_html__( 'Block', 'woodmart' )  => 'block',
						esc_html__( 'Inline', 'woodmart' ) => 'inline',
					),
					'std'              => 'block',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'dropdown',
					'heading'          => esc_html__( 'Size', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'size',
					'value'            => array(
						esc_html__( 'Medium (24px)', 'woodmart' ) => 'medium',
						esc_html__( 'Small (20px)', 'woodmart' ) => 'small',
						esc_html__( 'Large (28px)', 'woodmart' ) => 'large',
						esc_html__( 'Extra Large (42px)', 'woodmart' ) => 'xlarge',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'woodmart_image_select',
					'heading'          => esc_html__( 'Align', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'align',
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
					'std'              => 'center',
					'wood_tooltip'     => true,
					'hint'             => esc_html__( 'Select image alignment.', 'woodmart' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column title-align',
				),
				array(
					'title'      => esc_html__( 'Items', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'type'       => 'woodmart_title_divider',
					'param_name' => 'items_divider',
				),

				array(
					'type'             => 'woodmart_dropdown',
					'heading'          => esc_html__( 'Background', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'style',
					'value'            => array(
						esc_html__( 'Default', 'woodmart' ) => 'simple',
						esc_html__( 'Primary color', 'woodmart' ) => 'active',
						esc_html__( 'Custom', 'woodmart' ) => 'custom',
					),
					'std'              => 'simple',
					'style'            => array(
						'active' => woodmart_get_color_value( 'primary-color', '#7eb934' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'wd_colorpicker',
					'heading'          => esc_html__( 'Background color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'background_color',
					'selectors'        => array(
						'{{WRAPPER}} .wd-timer' => array(
							'--wd-timer-bg:{{VALUE}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency'       => array(
						'element' => 'style',
						'value'   => array( 'custom' ),
					),
				),
				array(
					'type'             => 'woodmart_button_set',
					'heading'          => esc_html__( 'Color scheme', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'woodmart_color_scheme',
					'value'            => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'Light', 'woodmart' ) => 'light',
						esc_html__( 'Dark', 'woodmart' )  => 'dark',
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Border', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'enable_border',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),

				array(
					'heading'          => esc_html__( 'Border type', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_select',
					'param_name'       => 'border_type',
					'style'            => 'select',
					'selectors'        => array(
						'{{WRAPPER}} .wd-item' => array(
							'border-style: {{VALUE}};',
						),
					),
					'devices'          => array(
						'desktop' => array(
							'value' => '',
						),
					),
					'value'            => array(
						esc_html__( 'Inherit', 'woodmart' ) => '',
						esc_html__( 'None', 'woodmart' )    => 'none',
						esc_html__( 'Solid', 'woodmart' )   => 'solid',
						esc_html__( 'Dotted', 'woodmart' )  => 'dotted',
						esc_html__( 'Double', 'woodmart' )  => 'double',
						esc_html__( 'Dashed', 'woodmart' )  => 'dashed',
						esc_html__( 'Groove', 'woodmart' )  => 'groove',
					),
					'dependency'       => array(
						'element' => 'enable_border',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),

				array(
					'heading'          => esc_html__( 'Border color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'border_color',
					'selectors'        => array(
						'{{WRAPPER}} .wd-item' => array(
							'border-color: {{VALUE}};',
						),
					),
					'dependency'       => array(
						'element' => 'enable_border',
						'value'   => array( 'yes' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),

				array(
					'heading'    => esc_html__( 'Border width', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'type'       => 'wd_dimensions',
					'param_name' => 'border_width',
					'selectors'  => array(
						'{{WRAPPER}} .wd-item' => array(
							'border-top-width: {{TOP}}px;',
							'border-right-width: {{RIGHT}}px;',
							'border-bottom-width: {{BOTTOM}}px;',
							'border-left-width: {{LEFT}}px;',
						),
					),
					'devices'    => array(
						'desktop' => array(
							'unit' => 'px',
						),
					),
					'range'      => array(
						'px' => array(),
					),
					'dependency'       => array(
						'element' => 'enable_border',
						'value'   => array( 'yes' ),
					),
				),

				array(
					'heading'    => esc_html__( 'Border radius', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'type'       => 'wd_dimensions',
					'param_name' => 'border_radius',
					'selectors'  => array(
						'{{WRAPPER}} .wd-item' => array(
							'border-top-left-radius: {{TOP}}{{UNIT}};',
							'border-top-right-radius: {{RIGHT}}{{UNIT}};',
							'border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
							'border-bottom-left-radius: {{LEFT}}{{UNIT}};',
						),
					),
					'devices'    => array(
						'desktop' => array(
							'unit' => 'px',
						),
					),
					'range'      => array(
						'px' => array(),
						'%'  => array(),
					),
					'dependency' => array(
						'element' => 'enable_border',
						'value'   => array( 'yes' ),
					),
				),

				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'enable_shadow',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'type'             => 'wd_box_shadow',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'items_shadow',
					'selectors'        => array(
						'{{WRAPPER}} .wd-item' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'dependency'       => array(
						'element' => 'enable_shadow',
						'value'   => array( 'yes' ),
					),
					'default'          => array(
						'horizontal' => '0',
						'vertical'   => '0',
						'blur'       => '9',
						'spread'     => '0',
						'color'      => '',
					),
				),

				$time_typography['font_family'],
				$time_typography['font_size'],
				$time_typography['font_weight'],
				$time_typography['text_transform'],
				$time_typography['font_style'],
				$time_typography['line_height'],

				array(
					'heading'          => esc_html__( 'Numbers color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'time_color',
					'selectors'        => array(
						'{{WRAPPER}} .wd-timer-value' => array(
							'color: {{VALUE}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),

				$label_typography['font_family'],
				$label_typography['font_size'],
				$label_typography['font_weight'],
				$label_typography['text_transform'],
				$label_typography['font_style'],
				$label_typography['line_height'],

				array(
					'heading'          => esc_html__( 'Labels color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'label_color',
					'selectors'        => array(
						'{{WRAPPER}} .wd-timer-text' => array(
							'color: {{VALUE}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),

				array(
					'heading'          => esc_html__( 'Gap', 'woodmart' ),
					'type'             => 'wd_slider',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'items_gap',
					'devices'          => array(
						'desktop' => array(
							'unit' => 'px',
							'value' => '',
						),
						'tablet'  => array(
							'unit' => 'px',
							'value' => '',
						),
						'mobile'  => array(
							'unit' => 'px',
							'value' => '',
						),
					),
					'range'            => array(
						'px' => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
					'selectors'        => array(
						'{{WRAPPER}} .wd-timer' => array(
							'gap: {{VALUE}}{{UNIT}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Min height', 'woodmart' ),
					'type'             => 'wd_slider',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'items_min_height',
					'devices'          => array(
						'desktop' => array(
							'unit' => 'px',
							'value' => '',
						),
						'tablet'  => array(
							'unit' => 'px',
							'value' => '',
						),
						'mobile'  => array(
							'unit' => 'px',
							'value' => '',
						),
					),
					'range'            => array(
						'px' => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
					'selectors'        => array(
						'{{WRAPPER}} .wd-item' => array(
							'min-height: {{VALUE}}{{UNIT}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),
				array(
					'heading'          => esc_html__( 'Min width', 'woodmart' ),
					'type'             => 'wd_slider',
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'param_name'       => 'items_min_width',
					'devices'          => array(
						'desktop' => array(
							'unit' => 'px',
							'value' => '',
						),
						'tablet'  => array(
							'unit' => 'px',
							'value' => '',
						),
						'mobile'  => array(
							'unit' => 'px',
							'value' => '',
						),
					),
					'range'            => array(
						'px' => array(
							'min'  => 1,
							'max'  => 200,
							'step' => 1,
						),
					),
					'selectors'        => array(
						'{{WRAPPER}} .wd-item' => array(
							'min-width: {{VALUE}}{{UNIT}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),

				array(
					'title'      => esc_html__( 'Separator', 'woodmart' ),
					'group'      => esc_html__( 'Style', 'woodmart' ),
					'type'       => 'woodmart_title_divider',
					'param_name' => 'separator_divider',
					'dependency' => array(
						'element' => 'separator',
						'value'   => array( 'yes' ),
					),
				),

				array(
					'heading'          => esc_html__( 'Font size', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_slider',
					'param_name'       => 'separator_font_size',
					'devices'          => array(
						'desktop' => array(
							'unit'  => 'px',
							'value' => '',
						),
						'tablet'  => array(
							'unit'  => 'px',
							'value' => '',
						),
						'mobile'  => array(
							'unit'  => 'px',
							'value' => '',
						),
					),
					'range'            => array(
						'px' => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
					),
					'selectors'        => array(
						'{{WRAPPER}} .wd-timer .wd-sep' => array(
							'font-size: {{VALUE}}{{UNIT}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),

				array(
					'heading'          => esc_html__( 'Color', 'woodmart' ),
					'group'            => esc_html__( 'Style', 'woodmart' ),
					'type'             => 'wd_colorpicker',
					'param_name'       => 'separator_color',
					'selectors'        => array(
						'{{WRAPPER}} .wd-timer .wd-sep' => array(
							'color: {{VALUE}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
				),

				/**
				 * Extra
				 */
				array(
					'type'       => 'woodmart_title_divider',
					'holder'     => 'div',
					'title'      => esc_html__( 'Extra options', 'woodmart' ),
					'param_name' => 'extra_divider',
				),
				( function_exists( 'vc_map_add_css_animation' ) ) ? vc_map_add_css_animation( true ) : '',
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Extra class name', 'woodmart' ),
					'param_name' => 'el_class',
					'hint'       => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'woodmart' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'woodmart' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),

				array(
					'type'             => 'woodmart_switch',
					'heading'          => esc_html__( 'Box shadow', 'woodmart' ),
					'group'            => esc_html__( 'Design Options', 'js_composer' ),
					'param_name'       => 'box_shadow',
					'true_state'       => 'yes',
					'false_state'      => 'no',
					'default'          => 'no',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'wd_box_shadow',
					'group'            => esc_html__( 'Design Options', 'js_composer' ),
					'param_name'       => 'box_shadow_value',
					'selectors'        => array(
						'{{WRAPPER}}' => array(
							'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
						),
					),
					'edit_field_class' => 'vc_col-sm-12 vc_column',
					'dependency'       => array(
						'element' => 'box_shadow',
						'value'   => array( 'yes' ),
					),
					'default'          => array(
						'horizontal' => '0',
						'vertical'   => '0',
						'blur'       => '9',
						'spread'     => '0',
						'color'      => '',
					),
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
