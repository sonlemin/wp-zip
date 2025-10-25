<?php
/**
 * Countdown timer map.
 */

namespace XTS\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Countdown extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_countdown_timer';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Countdown timer', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-countdown';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'wd-elements' );
	}

	/**
	 * Register the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/**
		 * Content tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_content_section',
			array(
				'label' => esc_html__( 'General', 'woodmart' ),
			)
		);

		$this->add_control(
			'date',
			array(
				'label'   => esc_html__( 'Date', 'woodmart' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d', strtotime( ' +2 months' ) ),
			)
		);

		$this->add_control(
			'hide_on_finish',
			array(
				'label'        => esc_html__( 'Hide countdown on finish', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'labels',
			array(
				'label'   => esc_html__( 'Labels', 'woodmart' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => esc_html__( 'Separator', 'woodmart' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'separator_text',
			array(
				'label'     => esc_html__( 'Text', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ':',
				'condition' => array(
					'separator' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_style_section',
			array(
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'block'  => esc_html__( 'Block', 'woodmart' ),
					'inline' => esc_html__( 'Inline', 'woodmart' ),
				),
				'default' => 'block',
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => esc_html__( 'Predefined size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'small'  => esc_html__( 'Small (20px)', 'woodmart' ),
					'medium' => esc_html__( 'Medium (24px)', 'woodmart' ),
					'large'  => esc_html__( 'Large (28px)', 'woodmart' ),
					'xlarge' => esc_html__( 'Extra Large (42px)', 'woodmart' ),
				),
				'default' => 'medium',
			)
		);

		$this->add_control(
			'align',
			array(
				'label'   => esc_html__( 'Alignment', 'woodmart' ),
				'type'    => 'wd_buttons',
				'options' => array(
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
				'default' => 'center',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'items_style_section',
			array(
				'label' => esc_html__( 'Items', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => esc_html__( 'Background', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'simple' => esc_html__( 'Default', 'woodmart' ),
					'active' => esc_html__( 'Primary color', 'woodmart' ),
					'custom' => esc_html__( 'Custom', 'woodmart' ),
				),
				'default' => 'simple',
			)
		);

		$this->add_control(
			'custom_bg',
			array(
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-timer' => '--wd-timer-bg: {{VALUE}}',
				),
				'default'   => '',
				'condition' => array(
					'style' => 'custom',
				),
			)
		);

		$this->add_control(
			'woodmart_color_scheme',
			array(
				'label'   => esc_html__( 'Color scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''      => esc_html__( 'Inherit', 'woodmart' ),
					'light' => esc_html__( 'Light', 'woodmart' ),
					'dark'  => esc_html__( 'Dark', 'woodmart' ),
				),
				'default' => '',
			)
		);

		$this->add_control(
			'enable_border',
			array(
				'label'        => esc_html__( 'Border', 'woodmart' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'woodmart' ),
				'label_off'    => esc_html__( 'Off', 'woodmart' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'items_border',
				'selector'  => '{{WRAPPER}} .wd-item',
				'condition' => array(
					'enable_border' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'woodmart' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wd-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'enable_border' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .wd-item',
			)
		);

		$this->add_control(
			'time_options',
			array(
				'label'     => esc_html__( 'Numbers', 'woodmart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'time_typography',
				'selector' => '{{WRAPPER}} .wd-timer-value',
			)
		);

		$this->add_control(
			'time_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-timer-value' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'label_options',
			array(
				'label'     => esc_html__( 'Labels', 'woodmart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .wd-timer-text',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-timer-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'style_divider',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->add_responsive_control(
			'items_gap',
			array(
				'label'      => esc_html__( 'Gap', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wd-timer' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_min_height',
			array(
				'label'      => esc_html__( 'Min height', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wd-item' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'items_min_width',
			array(
				'label'      => esc_html__( 'Min width', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wd-item' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'separator_style_section',
			array(
				'label'     => esc_html__( 'Separator', 'woodmart' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'separator' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'separator_font_size',
			array(
				'label'      => esc_html__( 'Font size', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'devices'    => array( 'desktop', 'tablet', 'mobile' ),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wd-sep' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-sep' => 'color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$default_settings = array(
			'date'                  => '2020-12-12',
			'woodmart_color_scheme' => 'dark',
			'size'                  => 'medium',
			'align'                 => 'center',
			'style'                 => 'standard',
			'hide_on_finish'        => 'no',
			'labels'                => 'yes',
			'separator'             => 'no',
			'separator_text'        => '',
		);

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$timezone = apply_filters( 'woodmart_wp_timezone_element', false ) ? get_option( 'timezone_string' ) : 'GMT';

		$separator = ! empty( $settings['separator'] ) && 'yes' === $settings['separator'] && ! empty( $settings['separator_text'] );

		$this->add_render_attribute(
			array(
				'wrapper' => array(
					'class' => array(
						'wd-countdown-timer',
						'color-scheme-' . $settings['woodmart_color_scheme'],
						'text-' . $settings['align'],
					),
				),
				'timer'   => array(
					'class'               => array(
						'wd-timer',
						'wd-size-' . $settings['size'],
						'active' === $settings['style'] ? 'wd-bg-active' : '',
						! $settings['labels'] ? 'wd-labels-hide' : '',
						'inline' === $settings['layout'] ? 'wd-layout-inline' : '',
						woodmart_get_old_classes( ' woodmart-timer' ),
					),
					'data-end-date'       => array(
						apply_filters( 'wd_countdown_timer_end_date', $settings['date'] ),
					),
					'data-timezone'       => array(
						$timezone,
					),
					'data-hide-on-finish' => array(
						$settings['hide_on_finish'],
					),
				),
			)
		);

		woodmart_enqueue_js_library( 'countdown-bundle' );
		woodmart_enqueue_js_script( 'countdown-element' );
		woodmart_enqueue_inline_style( 'countdown' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'timer' ); ?>>
				<span class="wd-item wd-timer-days">
					<span class="wd-timer-value">
						0
					</span>
					<span class="wd-timer-text">
						<?php esc_html_e( 'days', 'woodmart' ); ?>
					</span>
				</span>
				<?php if ( $separator ) : ?>
					<div class="wd-sep">
						<?php echo esc_html( $settings['separator_text'] ); ?>
					</div>
				<?php endif; ?>
				<span class="wd-item wd-timer-hours">
					<span class="wd-timer-value">
						00
					</span>
					<span class="wd-timer-text">
						<?php esc_html_e( 'hr', 'woodmart' ); ?>
					</span>
				</span>
				<?php if ( $separator ) : ?>
					<div class="wd-sep">
						<?php echo esc_html( $settings['separator_text'] ); ?>
					</div>
				<?php endif; ?>
				<span class="wd-item wd-timer-min">
					<span class="wd-timer-value">
						00
					</span>
					<span class="wd-timer-text">
						<?php esc_html_e( 'min', 'woodmart' ); ?>
					</span>
				</span>
				<?php if ( $separator ) : ?>
					<div class="wd-sep">
						<?php echo esc_html( $settings['separator_text'] ); ?>
					</div>
				<?php endif; ?>
				<span class="wd-item wd-timer-sec">
					<span class="wd-timer-value">
						00
					</span>
					<span class="wd-timer-text">
						<?php esc_html_e( 'sc', 'woodmart' ); ?>
					</span>
				</span>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register( new Countdown() );
