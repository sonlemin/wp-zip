<?php
/**
 * My account navigation map.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Layouts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget for My Account Navigation.
 */
class My_Account_Navigation extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_my_account_navigation';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'My account navigation', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-ma-navigation';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'wd-my-account-elements' );
	}

	/**
	 * Show in panel.
	 *
	 * @return bool Whether to show the widget in the panel or not.
	 */
	public function show_in_panel() {
		return Main::is_layout_type( 'my_account_page' );
	}

	/**
	 * Register the widget controls.
	 */
	protected function register_controls() {
		// Tab Style.
		// General section.
		$this->start_controls_section(
			'general_style_section',
			array(
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'css_classes',
			array(
				'type'         => 'wd_css_class',
				'default'      => 'wd-el-my-acc-nav',
				'prefix_class' => '',
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'   => esc_html__( 'Orientation', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'vertical'   => esc_html__( 'Vertical', 'woodmart' ),
					'horizontal' => esc_html__( 'Horizontal', 'woodmart' ),
				),
				'default' => 'vertical',
			)
		);

		$this->add_control(
			'layout_type',
			array(
				'label'     => esc_html__( 'Layout', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inline' => esc_html__( 'Inline', 'woodmart' ),
					'grid'   => esc_html__( 'Grid', 'woodmart' ),
				),
				'default'   => 'inline',
				'condition' => array(
					'orientation' => array( 'horizontal' ),
				),
			)
		);

		$this->add_responsive_control(
			'nav_columns',
			array(
				'label'      => esc_html__( 'Columns', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 3,
				),
				'size_units' => '',
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 12,
						'step' => 1,
					),
				),
				'condition'  => array(
					'orientation' => array( 'horizontal' ),
					'layout_type' => array( 'grid' ),
				),
			)
		);

		$this->add_responsive_control(
			'nav_spacing',
			array(
				'label'     => esc_html__( 'Space between', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					0  => esc_html__( '0 px', 'woodmart' ),
					2  => esc_html__( '2 px', 'woodmart' ),
					6  => esc_html__( '6 px', 'woodmart' ),
					10 => esc_html__( '10 px', 'woodmart' ),
					20 => esc_html__( '20 px', 'woodmart' ),
					30 => esc_html__( '30 px', 'woodmart' ),
				),
				'default'   => 30,
				'devices'   => array( 'desktop', 'tablet', 'mobile' ),
				'classes'   => 'wd-hide-custom-breakpoints',
				'condition' => array(
					'orientation' => array( 'horizontal' ),
					'layout_type' => array( 'grid' ),
				),
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'        => esc_html__( 'Alignment', 'woodmart' ),
				'type'         => 'wd_buttons',
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/left.jpg',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/center.jpg',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/right.jpg',
					),
				),
				'condition'    => array(
					'orientation' => array( 'horizontal' ),
				),
				'default'      => 'left',
				'prefix_class' => 'text-',
			)
		);

		$this->add_control(
			'color_scheme',
			array(
				'label'   => esc_html__( 'Color scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
				),
				'default' => 'inherit',
			)
		);

		$this->end_controls_section();

		// Items section.
		$this->start_controls_section(
			'items_style_section',
			array(
				'label' => esc_html__( 'Items', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'nav_design',
			array(
				'label'     => esc_html__( 'Style', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'simple'  => esc_html__( 'Default', 'woodmart' ),
					'default' => esc_html__( 'Bordered', 'woodmart' ),
					'with-bg' => esc_html__( 'Background', 'woodmart' ),
				),
				'condition' => array(
					'orientation' => array( 'vertical' ),
				),
				'default'   => 'simple',
			)
		);

		$this->add_control(
			'style',
			array(
				'label'     => esc_html__( 'Style', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'default'   => esc_html__( 'Default', 'woodmart' ),
					'underline' => esc_html__( 'Underline', 'woodmart' ),
				),
				'condition' => array(
					'orientation' => array( 'horizontal' ),
				),
				'default'   => 'default',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'label'    => esc_html__( 'Typography', 'woodmart' ),
				'selector' => '{{WRAPPER}} .wd-nav-my-acc > li > a',
			)
		);

		// Nav tabs start.
		$this->start_controls_tabs(
			'items_tabs'
		);

		$this->start_controls_tab(
			'nav_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'woodmart' ),
			)
		);

		$this->add_control(
			'nav_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_bg_color_enable',
			array(
				'label'        => esc_html__( 'Background color', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'nav_bg_color',
			array(
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-bg: {{VALUE}}',
				),
				'condition' => array(
					'nav_bg_color_enable' => '1',
				),
			)
		);

		$this->add_control(
			'nav_border_enable',
			array(
				'label'        => esc_html__( 'Border', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'nav_border',
				'label'     => esc_html__( 'Border', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-nav-my-acc > li > a',
				'condition' => array(
					'nav_border_enable' => '1',
				),
			)
		);

		$this->add_responsive_control(
			'nav_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'woodmart' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wd-nav-my-acc > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'nav_border_enable' => '1',
				),
			)
		);

		$this->add_control(
			'nav_box_shadow_enable',
			array(
				'label'        => esc_html__( 'Box shadow', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'nav_shadow',
				'label'     => esc_html__( 'Box Shadow', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-nav-my-acc > li > a',
				'condition' => array(
					'nav_box_shadow_enable' => '1',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'woodmart' ),
			)
		);

		$this->add_control(
			'nav_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-color-hover: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_bg_hover_color_enable',
			array(
				'label'        => esc_html__( 'Background color', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'nav_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-bg-hover: {{VALUE}}',
				),
				'condition' => array(
					'nav_bg_hover_color_enable' => '1',
				),
			)
		);

		$this->add_control(
			'nav_border_hover_enable',
			array(
				'label'        => esc_html__( 'Border', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'nav_border_hover',
				'label'     => esc_html__( 'Border', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-nav-my-acc > li:hover > a',
				'condition' => array(
					'nav_border_hover_enable' => '1',
				),
			)
		);

		$this->add_control(
			'nav_box_shadow_hover_enable',
			array(
				'label'        => esc_html__( 'Box shadow', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'nav_shadow_hover',
				'label'     => esc_html__( 'Box Shadow', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-nav-my-acc > li:hover > a',
				'condition' => array(
					'nav_box_shadow_hover_enable' => '1',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_active_tab',
			array(
				'label' => esc_html__( 'Active', 'woodmart' ),
			)
		);

		$this->add_control(
			'nav_color_active',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-color-active: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_bg_active_color_enable',
			array(
				'label'        => esc_html__( 'Background color', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'nav_bg_color_active',
			array(
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-bg-active: {{VALUE}}',
				),
				'condition' => array(
					'nav_bg_active_color_enable' => '1',
				),
			)
		);

		$this->add_control(
			'nav_border_active_enable',
			array(
				'label'        => esc_html__( 'Border', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'nav_border_active',
				'label'     => esc_html__( 'Border', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-nav-my-acc > li.wd-active > a',
				'condition' => array(
					'nav_border_active_enable' => '1',
				),
			)
		);

		$this->add_control(
			'nav_box_shadow_active_enable',
			array(
				'label'        => esc_html__( 'Box shadow', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'render_type'  => 'template',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'nav_box_shadow_active',
				'label'     => esc_html__( 'Box Shadow', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-nav-my-acc > li.wd-active > a',
				'condition' => array(
					'nav_box_shadow_active_enable' => '1',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		// Nav tabs end.

		$this->add_control(
			'items_separator',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'items_gap',
			array(
				'label'      => esc_html__( 'Gap', 'woodmart' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					's'      => esc_html__( 'Small', 'woodmart' ),
					'm'      => esc_html__( 'Medium', 'woodmart' ),
					'l'      => esc_html__( 'Large', 'woodmart' ),
					'custom' => esc_html__( 'Custom', 'woodmart' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'orientation',
									'operator' => '===',
									'value'    => 'vertical',
								),
								array(
									'name'     => 'nav_design',
									'operator' => '===',
									'value'    => 'simple',
								),
							),
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'orientation',
									'operator' => '===',
									'value'    => 'horizontal',
								),
								array(
									'name'     => 'layout_type',
									'operator' => '===',
									'value'    => 'inline',
								),
							),
						),
					),
				),
				'default'    => 'm',
			)
		);

		$this->add_responsive_control(
			'custom_items_gap',
			array(
				'label'      => esc_html__( 'Custom gap', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-gap: {{SIZE}}px;',
				),
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'items_gap',
							'operator' => '===',
							'value'    => 'custom',
						),
						array(
							'relation' => 'or',
							'terms'    => array(
								array(
									'relation' => 'and',
									'terms'    => array(
										array(
											'name'     => 'orientation',
											'operator' => '===',
											'value'    => 'vertical',
										),
										array(
											'name'     => 'nav_design',
											'operator' => '===',
											'value'    => 'simple',
										),
									),
								),
								array(
									'relation' => 'and',
									'terms'    => array(
										array(
											'name'     => 'orientation',
											'operator' => '===',
											'value'    => 'horizontal',
										),
										array(
											'name'     => 'layout_type',
											'operator' => '===',
											'value'    => 'inline',
										),
									),
								),
							),
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'tabs_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'woodmart' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-pd: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Icons section.
		$this->start_controls_section(
			'icon_style_section',
			array(
				'label' => esc_html__( 'Icons', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'show_icons',
			array(
				'label'        => esc_html__( 'Show icons', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'icon_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'woodmart' ),
				'type'      => 'wd_buttons',
				'options'   => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/infobox/position/left.png',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/infobox/position/top.png',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/infobox/position/right.png',
					),
				),
				'condition' => array(
					'show_icons' => 'yes',
				),
				'default'   => 'left',
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wd-nav-my-acc' => '--nav-icon-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_icons' => 'yes',
				),
			)
		);

		// Icon tabs start.
		$this->start_controls_tabs(
			'icon_color_tabs',
			array(
				'condition' => array(
					'show_icons' => 'yes',
				),
			)
		);

		$this->start_controls_tab(
			'icon_color_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'woodmart' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav > li > a .wd-nav-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_color_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'woodmart' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav > li:hover > a .wd-nav-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_color_active_tab',
			array(
				'label' => esc_html__( 'Active', 'woodmart' ),
			)
		);

		$this->add_control(
			'icon_color_active',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-nav > li.wd-active > a .wd-nav-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		// Icon tabs end.

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings = wp_parse_args(
			$this->get_settings_for_display(),
			array(
				'nav_columns'                  => array( 'size' => 3 ),
				'nav_columns_tablet'           => array( 'size' => '' ),
				'nav_columns_mobile'           => array( 'size' => '' ),
				'nav_spacing'                  => 30,
				'nav_spacing_tablet'           => '',
				'nav_spacing_mobile'           => '',
				'nav_design'                   => 'simple',
				'show_icons'                   => true,
				'items_gap'                    => 30,
				'style'                        => 'default',
				'tabs_title_text_color_scheme' => 'inherit',
			)
		);

		$attributes     = '';
		$layout_type    = $settings['layout_type'];
		$orientation    = $settings['orientation'];
		$show_icons     = $settings['show_icons'];
		$icon_alignment = $settings['icon_alignment'];
		$menu_classes   = ' wd-nav-' . $settings['orientation'];

		$items_bg_activated      = $settings['nav_bg_color_enable'] || $settings['nav_bg_hover_color_enable'] || $settings['nav_bg_active_color_enable'];
		$items_box_shadow_active = $settings['nav_box_shadow_enable'] || $settings['nav_box_shadow_hover_enable'] || $settings['nav_box_shadow_active_enable'];
		$items_border_active     = $settings['nav_border_enable'] || $settings['nav_border_hover_enable'] || $settings['nav_border_active_enable'];

		if ( $items_bg_activated || $items_box_shadow_active || $items_border_active ) {
			$menu_classes .= ' wd-add-pd';
		}

		if ( 'inherit' !== $settings['color_scheme'] ) {
			$menu_classes .= ' color-scheme-' . $settings['color_scheme'];
		}

		if ( 'grid' === $layout_type ) {
			$grid_atts = array(
				'columns'        => $settings['nav_columns']['size'],
				'columns_tablet' => $settings['nav_columns_tablet']['size'],
				'columns_mobile' => $settings['nav_columns_mobile']['size'],
				'spacing'        => $settings['nav_spacing'],
				'spacing_tablet' => $settings['nav_spacing_tablet'],
				'spacing_mobile' => $settings['nav_spacing_mobile'],
			);

			$menu_classes = ' wd-grid-g wd-style-default';
			$attributes  .= ' style="' . woodmart_get_grid_attrs( $grid_atts ) . '"';
		}

		if ( $show_icons ) {
			$menu_classes .= ' wd-icon-' . ( $icon_alignment ? $icon_alignment : 'left' );
		}

		if ( 'horizontal' === $orientation ) {
			$menu_classes .= ' wd-style-' . $settings['style'];
		}

		if ( 'vertical' === $orientation ) {
			woodmart_enqueue_inline_style( 'mod-nav-vertical' );
			woodmart_enqueue_inline_style( 'mod-nav-vertical-design-' . $settings['nav_design'] );

			$menu_classes .= ' wd-design-' . $settings['nav_design'];
		}

		$gap_condition = ( 'vertical' === $orientation && 'simple' === $settings['nav_design'] ) || ( 'horizontal' === $orientation && 'inline' === $layout_type );

		if ( $gap_condition && ! empty( $settings['items_gap'] ) && 'custom' !== $settings['items_gap'] ) {
			$menu_classes .= ' wd-gap-' . $settings['items_gap'];
		}

		Main::setup_preview();
		woodmart_account_navigation( $menu_classes, $show_icons, $attributes );
		Main::restore_preview();
	}
}

Plugin::instance()->widgets_manager->register( new My_Account_Navigation() );
