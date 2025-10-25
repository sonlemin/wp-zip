<?php
/**
 * Elementor Popup_Document class file.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Floating_Blocks\Integrations;

use Elementor\Core\DocumentTypes\PageBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Popup_Document class.
 */
class Popup_Document extends PageBase {

	/**
	 * Get type.
	 *
	 * @return string
	 */
	public static function get_type() {
		return 'wd_popup';
	}

	/**
	 * Get title.
	 *
	 * @return string
	 */
	public static function get_title() {
		return esc_html__( 'Popup', 'woodmart' );
	}

	/**
	 * Get plural title.
	 *
	 * @return string
	 */
	public static function get_plural_title() {
		return esc_html__( 'Popups', 'woodmart' );
	}

	/**
	 * Get properties.
	 *
	 * @return array
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group']   = 'woodmart';
		$properties['show_in_finder']    = false;
		$properties['show_on_admin_bar'] = false;
		$properties['cpt']               = array( 'wd_popup' );
		$properties['support_kit']       = true;

		return $properties;
	}

	/**
	 * Get name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wd_popup';
	}

	/**
	 * Removed Elementor style controls.
	 *
	 * @access public
	 * @static
	 * @param Document $document Elementor document.
	 */
	public static function register_style_controls( $document ) {}

	/**
	 * Removed hide title control.
	 *
	 * @access public
	 * @static
	 * @param Document $document Elementor document.
	 */
	public static function register_hide_title_control( $document ) {}

	/**
	 * Add document controls.
	 */
	public function register_controls() {
		$popup_id = $this->get_main_id();
		$prefix   = 'wd_popup_';

		// Settings tab.

		$this->start_controls_section(
			$prefix . 'settings_layout',
			array(
				'label' => esc_html__( 'Layout', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_responsive_control(
			$prefix . 'width',
			array(
				'label'      => esc_html__( 'Width', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					),
					'vw' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-popup-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'height',
			array(
				'label'      => esc_html__( 'Height', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2000,
						'step' => 1,
					),
					'vh' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-popup-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'content_vertical_align',
			array(
				'label'     => esc_html__( 'Content position', 'woodmart' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Top', 'woodmart' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'woodmart' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'end'    => array(
						'title' => esc_html__( 'Bottom', 'woodmart' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup' => '--wd-align-items: {{VALUE}};',
				),
				'condition' => array(
					$prefix . 'height[size]!' => '',
				),
			)
		);

		$this->add_control(
			$prefix . 'heading_position',
			array(
				'label'     => esc_html__( 'Position', 'woodmart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			$prefix . 'horizontal_align',
			array(
				'label'     => esc_html__( 'Horizontal', 'woodmart' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woodmart' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woodmart' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woodmart' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'vertical_align',
			array(
				'label'     => esc_html__( 'Vertical', 'woodmart' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'start'  => array(
						'title' => esc_html__( 'Top', 'woodmart' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center' => array(
						'title' => esc_html__( 'Middle', 'woodmart' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'end'    => array(
						'title' => esc_html__( 'Bottom', 'woodmart' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors' => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-align-items: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'settings_close_behavior',
			array(
				'label' => esc_html__( 'Close behavior', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			$prefix . 'close_btn',
			array(
				'label'             => esc_html__( 'Close button', 'woodmart' ),
				'type'              => Controls_Manager::SWITCHER,
				'description'       => esc_html__( 'Disable the default close button. After that, the popup can be closed via the background overlay or a custom button using the “Close by selector” option.', 'woodmart' ),
				'label_on'          => esc_html__( 'On', 'woodmart' ),
				'label_off'         => esc_html__( 'Off', 'woodmart' ),
				'return_value'      => '1',
				'default'           => '1',
				'wd_reload_preview' => true,
			)
		);

		$this->add_control(
			$prefix . 'close_btn_display',
			array(
				'label'             => esc_html__( 'Display', 'woodmart' ),
				'type'              => Controls_Manager::SELECT,
				'description'       => esc_html__( 'Choose the close button design.', 'woodmart' ),
				'options'           => array(
					'icon' => esc_html__( 'Icon', 'woodmart' ),
					'text' => esc_html__( 'Icon with text', 'woodmart' ),
				),
				'default'           => 'icon',
				'condition'         => array(
					$prefix . 'close_btn' => '1',
				),
				'wd_reload_preview' => true,
			)
		);

		$this->add_control(
			$prefix . 'close_by_overlay',
			array(
				'label'        => esc_html__( 'Close by overlay', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Close the popup when clicking on the background overlay.', 'woodmart' ),
				'return_value' => '1',
				'default'      => '1',
			)
		);

		$this->add_control(
			$prefix . 'close_by_esc',
			array(
				'label'        => esc_html__( 'Close by ESC key', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Close the popup when pressing the Escape key.', 'woodmart' ),
				'return_value' => '1',
				'default'      => '1',
			)
		);

		$this->add_control(
			$prefix . 'close_by_selector',
			array(
				'label'       => esc_html__( 'Close by selector', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Create an alternative popup close button. Enter a CSS selector (e.g., .wd-close-popup) that will close the popup when clicked.', 'woodmart' ),
				'default'     => '',
			)
		);

		$this->add_control(
			$prefix . 'persistent_close',
			array(
				'label'        => esc_html__( 'Persistent close', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '1',
				'return_value' => '1',
				'description'  => esc_html__( 'Once closed, the popup will stay hidden on reload until the version is raised.', 'woodmart' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'settings_advanced',
			array(
				'label' => esc_html__( 'Advanced', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$this->add_control(
			$prefix . 'enable_page_scrolling',
			array(
				'label'        => esc_html__( 'Enable page scrolling', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => '1',
				'default'      => '',
			)
		);

		$this->add_control(
			$prefix . 'responsive_title',
			array(
				'separator' => 'before',
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Responsive', 'woodmart' ),
			)
		);

		$this->add_control(
			$prefix . 'hide_popup',
			array(
				'label'             => esc_html__( 'Hide on desktop', 'woodmart' ),
				'type'              => Controls_Manager::SWITCHER,
				'wd_reload_preview' => true,
				'return_value'      => '1',
				'default'           => '',
			)
		);

		$this->add_control(
			$prefix . 'hide_popup_tablet',
			array(
				'label'             => esc_html__( 'Hide on tablet', 'woodmart' ),
				'type'              => Controls_Manager::SWITCHER,
				'wd_reload_preview' => true,
				'return_value'      => '1',
				'default'           => '',
			)
		);

		$this->add_control(
			$prefix . 'hide_popup_mobile',
			array(
				'label'             => esc_html__( 'Hide on mobile', 'woodmart' ),
				'type'              => Controls_Manager::SWITCHER,
				'wd_reload_preview' => true,
				'return_value'      => '1',
				'default'           => '',
			)
		);

		$this->end_controls_section();

		// Popup tab.

		$this->start_controls_section(
			$prefix . 'settings_popup',
			array(
				'label' => esc_html__( 'Popup', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'background',
				'fields_options' => array(
					'background' => array(
						'label' => esc_html__( 'Background type', 'woodmart' ),
					),
				),
				'description'    => esc_html__( 'Set background color or image for popup.', 'woodmart' ),
				'types'          => array( 'classic', 'gradient' ),
				'selector'       => '.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup',
			)
		);

		$this->add_control(
			$prefix . 'background_divider',
			array(
				'type'      => \Elementor\Controls_Manager::DIVIDER,
				'condition' => array(
					'background_background' => array( 'classic', 'gradient' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => $prefix . 'border',
				'selector'       => '.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup',
				'fields_options' => array(
					'border' => array(
						'label' => esc_html__( 'Border type', 'woodmart' ),
					),
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'woodmart' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => $prefix . 'box_shadow',
				'selector' => '.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup',
			)
		);

		$this->add_responsive_control(
			$prefix . 'padding',
			array(
				'label'       => esc_html__( 'Padding', 'woodmart' ),
				'description' => esc_html__( 'Sets the spacing between the popup’s borders and its content.', 'woodmart' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup .wd-popup-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'   => 'before',
				'default'     => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => 'px',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'margin',
			array(
				'label'       => esc_html__( 'Margin', 'woodmart' ),
				'description' => esc_html__( 'Sets the spacing between the popup and the edge of the screen.', 'woodmart' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'selectors'   => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-popup-mt: {{TOP}}{{UNIT}}; --wd-popup-mr: {{RIGHT}}{{UNIT}}; --wd-popup-mb: {{BOTTOM}}{{UNIT}}; --wd-popup-ml: {{LEFT}}{{UNIT}};',
				),
				'default'     => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => 'px',
				),
			)
		);

		$this->add_control(
			$prefix . 'animation',
			array(
				'label'       => esc_html__( 'Animation', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'default'                => esc_html__( 'From left to right', 'woodmart' ),
					'slide-from-top'         => esc_html__( 'Slide from top', 'woodmart' ),
					'slide-from-bottom'      => esc_html__( 'Slide from bottom', 'woodmart' ),
					'slide-from-left'        => esc_html__( 'Slide from left', 'woodmart' ),
					'slide-from-right'       => esc_html__( 'Slide from right', 'woodmart' ),
					'slide-short-from-left'  => esc_html__( 'Slide short from left', 'woodmart' ),
					'slide-short-from-right' => esc_html__( 'Slide short from right', 'woodmart' ),
					'top-flip-x'             => esc_html__( 'Top flip X', 'woodmart' ),
					'bottom-flip-x'          => esc_html__( 'Bottom flip X', 'woodmart' ),
					'right-flip-y'           => esc_html__( 'Right flip Y', 'woodmart' ),
					'left-flip-y'            => esc_html__( 'Left flip Y', 'woodmart' ),
					'snap-in-top'            => esc_html__( 'Snap in top', 'woodmart' ),
					'snap-in-bottom'         => esc_html__( 'Snap in bottom', 'woodmart' ),
					'snap-in-left'           => esc_html__( 'Snap in left', 'woodmart' ),
					'snap-in-right'          => esc_html__( 'Snap in right', 'woodmart' ),
					'zoom-in'                => esc_html__( 'Zoom in', 'woodmart' ),
				),
				'default'     => 'default',
				'separator'   => 'before',
				'description' => esc_html__( 'Select a popup appearance animation.', 'woodmart' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'settings_overlay',
			array(
				'label' => esc_html__( 'Overlay', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			$prefix . 'overlay_color',
			array(
				'label'       => esc_html__( 'Background color', 'woodmart' ),
				'type'        => Controls_Manager::COLOR,
				'description' => esc_html__( 'Overlay color. Set transparency to make the site partially visible beneath the overlay.', 'woodmart' ),
				'selectors'   => array(
					'.mfp-bg.wd-mfp-popup-bg-' . $popup_id => 'background-color: {{VALUE}};',
				),
				'default'     => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'style_close_button',
			array(
				'label' => esc_html__( 'Close button', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			$prefix . 'close_btn_offset_v',
			array(
				'label'      => esc_html__( 'Offset vertical', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -500,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-close-btn-offset-v: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'size' => '',
					'unit' => 'px',
				),
			)
		);

		$this->add_responsive_control(
			$prefix . 'close_btn_offset_h',
			array(
				'label'      => esc_html__( 'Offset horizontal', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => -500,
						'max'  => 500,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id => '--wd-close-btn-offset-h: {{SIZE}}{{UNIT}};',
				),
				'default'    => array(
					'size' => '',
					'unit' => 'px',
				),
			)
		);

		$this->start_controls_tabs(
			'link_color_tabs'
		);

		$this->start_controls_tab(
			'link_color_tab',
			array(
				'label' => esc_html__( 'Idle', 'woodmart' ),
			)
		);

		$this->add_control(
			$prefix . 'close_btn_text_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup-close' => '--wd-action-color: {{VALUE}};',
				),
				'default'   => '',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_hover_color_tab',
			array(
				'label' => esc_html__( 'Hover', 'woodmart' ),
			)
		);

		$this->add_control(
			$prefix . 'close_btn_text_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.mfp-wrap.wd-mfp-popup-wrap-' . $popup_id . ' .wd-popup-close' => '--wd-action-color-hover: {{VALUE}};',
				),
				'default'   => '',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'triggers',
			array(
				'label' => esc_html__( 'Triggers', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$this->add_control(
			$prefix . 'is_some_time_enabled',
			array(
				'label'        => esc_html__( 'Page loaded', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show popup after some time (in milliseconds).', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '1',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'time_to_show',
			array(
				'label'     => esc_html__( 'Time to show', 'woodmart' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'default'   => '0',
				'condition' => array(
					$prefix . 'is_some_time_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'time_to_show_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_some_time_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_some_time',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_after_scroll_enabled',
			array(
				'label'        => esc_html__( 'User scroll', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show popup after user scrolls a certain percentage of the page.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'scroll_value',
			array(
				'label'      => esc_html__( 'Scroll value', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'range'      => array(
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 50,
					'unit' => '%',
				),
				'condition'  => array(
					$prefix . 'is_after_scroll_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'after_scroll_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_after_scroll_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_after_scroll',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_scroll_to_selector_enabled',
			array(
				'label'        => esc_html__( 'Scroll to selector', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show popup when user scrolls to a specific CSS selector.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'scroll_to_selector',
			array(
				'label'     => esc_html__( 'Selector', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'description' => esc_html__( 'Comma-separated list of selectors. For example: .wrapper .special-button, .newsletter-icon', 'woodmart' ),
				'default'   => '',
				'condition' => array(
					$prefix . 'is_scroll_to_selector_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'scroll_to_selector_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_scroll_to_selector_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_scroll_to_selector',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_inactivity_time_enabled',
			array(
				'label'        => esc_html__( 'Inactivity time', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show popup after inactivity time (in milliseconds).', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'inactivity_time',
			array(
				'label'     => esc_html__( 'Time to show', 'woodmart' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'default'   => 10000,
				'condition' => array(
					$prefix . 'is_inactivity_time_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'inactivity_time_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_inactivity_time_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_inactivity_time',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_exit_intent_enabled',
			array(
				'label'        => esc_html__( 'Exit intent', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Popup appears when the cursor exits the viewport, suggesting tab closure.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'exit_intent_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_exit_intent_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_exit_intent',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_on_click_enabled',
			array(
				'label'        => esc_html__( 'On click', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Number of clicks required to show popup.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'click_times',
			array(
				'label'     => esc_html__( 'Click times', 'woodmart' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'default'   => 3,
				'condition' => array(
					$prefix . 'is_on_click_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'click_times_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_on_click_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_click_times',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_on_selector_click_enabled',
			array(
				'label'        => esc_html__( 'On selector click', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'CSS selector to trigger popup on click.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'selector',
			array(
				'label'     => esc_html__( 'Selector', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'description'  => esc_html__( 'Comma-separated list of selectors. For example: .wrapper .special-button, .newsletter-icon', 'woodmart' ),
				'default'   => '',
				'condition' => array(
					$prefix . 'is_on_selector_click_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'selector_click_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_on_selector_click_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_selector_click',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_url_parameter_enabled',
			array(
				'label'        => esc_html__( 'URL contains specific parameter', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Name of the URL parameter to check.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'parameters',
			array(
				'label'       => esc_html__( 'Parameters', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Comma-separated list of parameters. For example: utm_source=facebook, single_key', 'woodmart' ),
				'default'     => '',
				'condition'   => array(
					$prefix . 'is_url_parameter_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'url_parameter_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_url_parameter_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_url_parameter',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_url_hashtag_enabled',
			array(
				'label'        => esc_html__( 'URL contains specific hashtag', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Name of the URL hashtag to check.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'hashtags',
			array(
				'label'       => esc_html__( 'Hashtags', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Comma-separated list of hashtags. For example: #hashtag1, #hashtag2', 'woodmart' ),
				'default'     => '',
				'condition'   => array(
					$prefix . 'is_url_hashtag_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'url_hashtag_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_url_hashtag_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_url_hashtag',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_after_page_views_enabled',
			array(
				'label'        => esc_html__( 'After page views', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show popup after a specific number of page views.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'after_page_views',
			array(
				'label'     => esc_html__( 'Views', 'woodmart' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'default'   => 1,
				'condition' => array(
					$prefix . 'is_after_page_views_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'after_page_views_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_after_page_views_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'divider_after_page_views',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			$prefix . 'is_after_sessions_enabled',
			array(
				'label'        => esc_html__( 'After sessions', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Show popup after a specific number of user sessions.', 'woodmart' ),
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'default'      => '',
				'return_value' => '1',
			)
		);

		$this->add_control(
			$prefix . 'after_sessions',
			array(
				'label'     => esc_html__( 'Sessions', 'woodmart' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'default'   => 1,
				'condition' => array(
					$prefix . 'is_after_sessions_enabled' => '1',
				),
			)
		);

		$this->add_control(
			$prefix . 'after_sessions_once',
			array(
				'label'        => esc_html__( 'Trigger once', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'return_value' => '1',
				'condition'    => array(
					$prefix . 'is_after_sessions_enabled' => '1',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$prefix . 'conditions_section',
			array(
				'label' => esc_html__( 'Conditions', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$this->add_control(
			$prefix . 'hidden_repeater',
			array(
				'type'    => Controls_Manager::REPEATER,
				'default' => array(),
				'fields'  => array(),
				'classes' => 'elementor-hidden',
			)
		);

		$this->add_control(
			$prefix . 'conditions',
			array(
				'label'   => esc_html__( 'Display conditions', 'woodmart' ),
				'type'    => Controls_Manager::REPEATER,
				'default' => array(
					array(
						'comparison' => 'include',
						'type'       => 'all',
					),
				),
				'fields'  => array(
					array(
						'name'    => 'comparison',
						'label'   => esc_html__( 'Comparison condition', 'woodmart' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'include',
						'options' => array(
							'include' => esc_html__( 'Include', 'woodmart' ),
							'exclude' => esc_html__( 'Exclude', 'woodmart' ),
						),
					),
					array(
						'name'    => 'type',
						'label'   => esc_html__( 'Condition type', 'woodmart' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'all',
						'options' => array(
							'all'                  => esc_html__( 'All', 'woodmart' ),
							'post_type'            => esc_html__( 'Post types', 'woodmart' ),
							'single_post_type'     => esc_html__( 'Post type single page', 'woodmart' ),
							'post_id'              => esc_html__( 'Post ID', 'woodmart' ),
							'taxonomy'             => esc_html__( 'Taxonomy', 'woodmart' ),
							'term_id'              => esc_html__( 'Term ID', 'woodmart' ),
							'single_posts_term_id' => esc_html__( 'Single posts from term', 'woodmart' ),
							'user_role'            => esc_html__( 'User role', 'woodmart' ),
							'custom'               => esc_html__( 'Custom', 'woodmart' ),
						),
					),
					array(
						'name'       => 'query_post_type',
						'label'      => esc_html__( 'Condition query', 'woodmart' ),
						'type'       => 'wd_autocomplete',
						'search'     => 'woodmart_get_posts_by_query',
						'render'     => 'woodmart_get_posts_title_by_id',
						'query_type' => 'post_type',
						'default'    => '',
						'condition'  => array(
							'type' => 'post_type',
						),
					),
					array(
						'name'       => 'query_single_post_type',
						'label'      => esc_html__( 'Condition query', 'woodmart' ),
						'type'       => 'wd_autocomplete',
						'search'     => 'woodmart_get_posts_by_query',
						'render'     => 'woodmart_get_posts_title_by_id',
						'query_type' => 'single_post_type',
						'default'    => '',
						'condition'  => array(
							'type' => 'single_post_type',
						),
					),
					array(
						'name'       => 'query_post_id',
						'label'      => esc_html__( 'Condition query', 'woodmart' ),
						'type'       => 'wd_autocomplete',
						'search'     => 'woodmart_get_posts_by_query',
						'render'     => 'woodmart_get_posts_title_by_id',
						'query_type' => 'post_id',
						'default'    => '',
						'condition'  => array(
							'type' => 'post_id',
						),
					),
					array(
						'name'       => 'query_taxonomy',
						'label'      => esc_html__( 'Condition query', 'woodmart' ),
						'type'       => 'wd_autocomplete',
						'search'     => 'woodmart_get_posts_by_query',
						'render'     => 'woodmart_get_posts_title_by_id',
						'query_type' => 'taxonomy',
						'default'    => '',
						'condition'  => array(
							'type' => 'taxonomy',
						),
					),
					array(
						'name'       => 'query_term_id',
						'label'      => esc_html__( 'Condition query', 'woodmart' ),
						'type'       => 'wd_autocomplete',
						'search'     => 'woodmart_get_posts_by_query',
						'render'     => 'woodmart_get_posts_title_by_id',
						'query_type' => 'term_id',
						'default'    => '',
						'condition'  => array(
							'type' => 'term_id',
						),
					),
					array(
						'name'       => 'query_single_posts_term_id',
						'label'      => esc_html__( 'Condition query', 'woodmart' ),
						'type'       => 'wd_autocomplete',
						'search'     => 'woodmart_get_posts_by_query',
						'render'     => 'woodmart_get_posts_title_by_id',
						'query_type' => 'single_posts_term_id',
						'default'    => '',
						'condition'  => array(
							'type' => 'single_posts_term_id',
						),
					),
					array(
						'name'      => 'query_user_role',
						'label'     => esc_html__( 'Condition query', 'woodmart' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => array_combine(
							array_keys( wp_roles()->roles ),
							array_column( wp_roles()->roles, 'name' )
						),
						'default'   => '',
						'condition' => array(
							'type' => 'user_role',
						),
					),
					array(
						'name'      => 'query_custom',
						'label'     => esc_html__( 'Condition query', 'woodmart' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => woodmart_get_custom_conditions_list(),
						'default'   => 'search',
						'condition' => array(
							'type' => 'custom',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		parent::register_controls();

		$this->remove_control( 'template' );
		$this->remove_control( 'template_default_description' );
		$this->remove_control( 'template_theme_description' );
		$this->remove_control( 'template_canvas_description' );
		$this->remove_control( 'template_header_footer_description' );
		$this->remove_control( 'reload_preview_description' );
	}
}
