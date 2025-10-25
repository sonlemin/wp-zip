<?php
/**
 * Floating block metaboxes.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Floating_Blocks\Integrations;

use XTS\Admin\Modules\Options\Metaboxes;
use XTS\Singleton;


/**
 * Floating block metaboxes.
 */
class Fb_Metaboxes extends Singleton {
	/**
	 * Init.
	 */
	public function init() {
		add_action( 'init', array( $this, 'add_metaboxes' ), 20 );
	}

	/**
	 * Add floating block metaboxes.
	 */
	public function add_metaboxes() {
		$metabox = Metaboxes::add_metabox(
			array(
				'id'           => 'wd_floating_block_metaboxes',
				'title'        => esc_html__( 'Settings', 'woodmart' ),
				'post_types'   => array( 'wd_floating_block' ),
				'css_selector' => '#wd-fb-{{ID}}',
			)
		);

		$metabox->add_section(
			array(
				'id'       => 'floating_block',
				'name'     => esc_html__( 'Block', 'woodmart' ),
				'icon'     => 'xts-i-fb',
				'priority' => 10,
			)
		);

		$metabox->add_section(
			array(
				'id'       => 'close_behavior',
				'name'     => esc_html__( 'Close behavior', 'woodmart' ),
				'icon'     => 'xts-i-close',
				'priority' => 20,
			)
		);

		$metabox->add_section(
			array(
				'id'       => 'display_mode',
				'name'     => esc_html__( 'Display mode', 'woodmart' ),
				'icon'     => 'xts-i-cog',
				'priority' => 30,
			)
		);

		$metabox->add_section(
			array(
				'id'       => 'conditions',
				'name'     => esc_html__( 'Conditions', 'woodmart' ),
				'icon'     => 'xts-i-edit-write',
				'priority' => 40,
			)
		);

		$metabox->add_section(
			array(
				'id'       => 'responsive',
				'name'     => esc_html__( 'Responsive', 'woodmart' ),
				'icon'     => 'xts-i-setting-slider-in-square',
				'priority' => 50,
			)
		);

		// General section.

		// Layout group.

		$metabox->add_field(
			array(
				'id'            => 'width',
				'name'          => esc_html__( 'Width', 'woodmart' ),
				'hint'          => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-width.mp4" autoplay loop muted></video>',
				'type'          => 'responsive_range',
				'group'         => esc_html__( 'Layout', 'woodmart' ),
				'section'       => 'floating_block',
				'selectors'     => array(
					'{{WRAPPER}}' => array(
						'--wd-fb-w: {{VALUE}}{{UNIT}};',
					),
				),
				'generate_zero' => true,
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
				'class'         => 'xts-col-6',
				'priority'      => 10,
			)
		);

		$metabox->add_field(
			array(
				'id'            => 'height',
				'name'          => esc_html__( 'Height', 'woodmart' ),
				'hint'          => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-height.mp4" autoplay loop muted></video>',
				'type'          => 'responsive_range',
				'group'         => esc_html__( 'Layout', 'woodmart' ),
				'section'       => 'floating_block',
				'selectors'     => array(
					'{{WRAPPER}}' => array(
						'--wd-fb-h: {{VALUE}}{{UNIT}};',
					),
				),
				'generate_zero' => true,
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
				'class'         => 'xts-col-6',
				'priority'      => 20,
			)
		);

		$metabox->add_field(
			array(
				'id'        => 'content_vertical_align',
				'name'      => esc_html__( 'Content vertical align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-content-vertical-align.mp4" autoplay loop muted></video>',
				'type'      => 'buttons',
				'default'   => 'start',
				'group'     => esc_html__( 'Layout', 'woodmart' ),
				'section'   => 'floating_block',
				'selectors' => array(
					'{{WRAPPER}} .wd-fb' => array(
						'--wd-align-items: {{VALUE}};',
					),
				),
				'options'   => array(
					'start'  => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'start',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.svg',
					),
					'end'    => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'end',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.svg',
					),
				),
				't_tab'     => array(
					'id'       => 'content_settings_tabs',
					'tab'      => esc_html__( 'Desktop', 'woodmart' ),
					'title'    => esc_html__( 'Content position', 'woodmart' ),
					'icon'     => 'xts-i-desktop',
					'style'    => 'devices',
					'requires' => array(
						array(
							'key'     => 'height_type',
							'compare' => 'not_equals',
							'value'   => 'content',
						),
					),
				),
				'requires'  => array(
					array(
						'key'     => 'height_type',
						'compare' => 'not_equals',
						'value'   => 'content',
					),
				),
				'priority'  => 30,
				'class'     => 'xts-tab-field',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'content_vertical_align_tablet',
				'name'       => esc_html__( 'Content vertical align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-content-vertical-align.mp4" autoplay loop muted></video>',
				'group'      => esc_html__( 'Layout', 'woodmart' ),
				'section'    => 'floating_block',
				'type'       => 'buttons',
				'selectors'  => array(
					'{{WRAPPER}} .wd-fb' => array(
						'--wd-align-items: {{VALUE}};',
					),
				),
				'css_device' => 'tablet',
				'options'    => array(
					'start'  => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'start',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.svg',
					),
					'end'    => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'end',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.svg',
					),
				),
				't_tab'      => array(
					'id'   => 'content_settings_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'requires'   => array(
					array(
						'key'     => 'height_type',
						'compare' => 'not_equals',
						'value'   => 'content',
					),
				),
				'priority'   => 31,
				'class'      => 'xts-tab-field',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'content_vertical_align_mobile',
				'name'       => esc_html__( 'Content vertical align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-content-vertical-align.mp4" autoplay loop muted></video>',
				'group'      => esc_html__( 'Layout', 'woodmart' ),
				'section'    => 'floating_block',
				'type'       => 'buttons',
				'css_device' => 'mobile',
				'selectors'  => array(
					'{{WRAPPER}} .wd-fb' => array(
						'--wd-align-items: {{VALUE}};',
					),
				),
				'options'    => array(
					'start'  => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'start',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.svg',
					),
					'end'    => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'end',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.svg',
					),
				),
				't_tab'      => array(
					'id'   => 'content_settings_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'requires'   => array(
					array(
						'key'     => 'height_type',
						'compare' => 'not_equals',
						'value'   => 'content',
					),
				),
				'priority'   => 32,
				'class'      => 'xts-tab-field',
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'positioning_area',
				'name'        => esc_html__( 'Positioning area', 'woodmart' ),
				'hint'        => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-positioning-area.mp4" autoplay loop muted></video>',
				'description' => esc_html__( 'Area relative to which the block will be positioned along the vertical and horizontal axes.', 'woodmart' ),
				'type'        => 'buttons',
				'group'       => esc_html__( 'Layout', 'woodmart' ),
				'section'     => 'floating_block',
				'options'     => array(
					'full-width' => array(
						'name'  => esc_html__( 'Full width', 'woodmart' ),
						'value' => 'full-width',
					),
					'container'  => array(
						'name'  => esc_html__( 'Container', 'woodmart' ),
						'value' => 'container',
					),
				),
				'default'     => 'full-width',
				'class'       => 'xts-col-6',
				'priority'    => 40,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'z_index',
				'name'        => esc_html__( 'Z-index', 'woodmart' ),
				'hint'        => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-z-index.mp4" autoplay loop muted></video>',
				'description' => esc_html__( 'Allows placing the block below or above another block or other elements on the site.', 'woodmart' ),
				'type'        => 'text_input',
				'section'     => 'floating_block',
				'attributes'  => array(
					'type' => 'number',
					'min'  => 0,
				),
				'group'       => esc_html__( 'Layout', 'woodmart' ),
				'selectors'   => array(
					'{{WRAPPER}}' => array(
						'z-index: {{VALUE}};',
					),
				),
				'priority'    => 50,
				'class'       => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'            => 'padding',
				'name'          => esc_html__( 'Padding', 'woodmart' ),
				'hint'          => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-padding.mp4" autoplay loop muted></video>',
				'description'   => esc_html__( 'Sets the spacing between the block borders and its content.', 'woodmart' ),
				'type'          => 'dimensions',
				'group'         => esc_html__( 'Layout', 'woodmart' ),
				'dimensions'    => array(
					'top'    => esc_html__( 'Top', 'woodmart' ),
					'right'  => esc_html__( 'Right', 'woodmart' ),
					'bottom' => esc_html__( 'Bottom', 'woodmart' ),
					'left'   => esc_html__( 'Left', 'woodmart' ),
				),
				'section'       => 'floating_block',
				'selectors'     => array(
					'{{WRAPPER}} .wd-fb-inner' => array(
						'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				),
				'generate_zero' => true,
				'devices'       => array(
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
				'range'         => array(
					'px' => array(
						'min'  => 0,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'class'         => 'xts-col-6',
				'priority'      => 60,
			)
		);

		$metabox->add_field(
			array(
				'id'            => 'margin',
				'name'          => esc_html__( 'Margin', 'woodmart' ),
				'hint'          => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-margin.mp4" autoplay loop muted></video>',
				'description'   => esc_html__( 'Sets the spacing between the block and the edge of the positioning area.', 'woodmart' ),
				'type'          => 'dimensions',
				'group'         => esc_html__( 'Layout', 'woodmart' ),
				'dimensions'    => array(
					'top'    => esc_html__( 'Top', 'woodmart' ),
					'right'  => esc_html__( 'Right', 'woodmart' ),
					'bottom' => esc_html__( 'Bottom', 'woodmart' ),
					'left'   => esc_html__( 'Left', 'woodmart' ),
				),
				'section'       => 'floating_block',
				'selectors'     => array(
					'{{WRAPPER}}' => array(
						'--wd-fb-mt: {{TOP}}{{UNIT}};',
						'--wd-fb-mr: {{RIGHT}}{{UNIT}};',
						'--wd-fb-mb: {{BOTTOM}}{{UNIT}};',
						'--wd-fb-ml: {{LEFT}}{{UNIT}};',
					),
				),
				'generate_zero' => true,
				'devices'       => array(
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
				'range'         => array(
					'px' => array(
						'step' => 1,
					),
					'%'  => array(
						'min'  => -100,
						'max'  => 100,
						'step' => 1,
					),
				),
				'class'         => 'xts-col-6',
				'priority'      => 70,
			)
		);

		$metabox->add_field(
			array(
				'id'        => 'vertical_align',
				'name'      => esc_html__( 'Vertical align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-vertical-align.mp4" autoplay loop muted></video>',
				'type'      => 'buttons',
				'default'   => 'center',
				'group'     => esc_html__( 'Layout', 'woodmart' ),
				'section'   => 'floating_block',
				'selectors' => array(
					'{{WRAPPER}}' => array(
						'--wd-align-items: {{VALUE}};',
					),
				),
				'options'   => array(
					'start'  => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'start',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.svg',
					),
					'end'    => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'end',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.svg',
					),
				),
				't_tab'     => array(
					'id'    => 'vertical_align_tabs',
					'tab'   => esc_html__( 'Desktop', 'woodmart' ),
					'title' => esc_html__( 'Content position', 'woodmart' ),
					'icon'  => 'xts-i-desktop',
					'style' => 'devices',
					'class' => 'xts-col-6',
				),
				'priority'  => 80,
				'class'     => 'xts-tab-field xts-col-12',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'vertical_align_tablet',
				'name'       => esc_html__( 'Vertical align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-vertical-align.mp4" autoplay loop muted></video>',
				'group'      => esc_html__( 'Layout', 'woodmart' ),
				'section'    => 'floating_block',
				'type'       => 'buttons',
				'selectors'  => array(
					'{{WRAPPER}}' => array(
						'--wd-align-items: {{VALUE}};',
					),
				),
				'css_device' => 'tablet',
				'options'    => array(
					'start'  => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'start',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.svg',
					),
					'end'    => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'end',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.svg',
					),
				),
				't_tab'      => array(
					'id'   => 'vertical_align_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'priority'   => 81,
				'class'      => 'xts-tab-field xts-col-12',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'vertical_align_mobile',
				'name'       => esc_html__( 'Vertical align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-vertical-align.mp4" autoplay loop muted></video>',
				'group'      => esc_html__( 'Layout', 'woodmart' ),
				'section'    => 'floating_block',
				'type'       => 'buttons',
				'css_device' => 'mobile',
				'selectors'  => array(
					'{{WRAPPER}}' => array(
						'--wd-align-items: {{VALUE}};',
					),
				),
				'options'    => array(
					'start'  => array(
						'name'  => esc_html__( 'Top', 'woodmart' ),
						'value' => 'start',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/top.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Middle', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/middle.svg',
					),
					'end'    => array(
						'name'  => esc_html__( 'Bottom', 'woodmart' ),
						'value' => 'end',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/bottom.svg',
					),
				),
				't_tab'      => array(
					'id'   => 'vertical_align_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'priority'   => 82,
				'class'      => 'xts-tab-field xts-col-12',
			)
		);

		$metabox->add_field(
			array(
				'id'        => 'horizontal_align',
				'name'      => esc_html__( 'Horizontal align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-horizontal-align.mp4" autoplay loop muted></video>',
				'group'     => esc_html__( 'Layout', 'woodmart' ),
				'section'   => 'floating_block',
				'default'   => 'center',
				'type'      => 'buttons',
				'selectors' => array(
					'{{WRAPPER}}' => array(
						'--wd-justify-content: {{VALUE}};',
					),
				),
				'options'   => array(
					'left'   => array(
						'name'  => esc_html__( 'Left', 'woodmart' ),
						'value' => 'left',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/left.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Center', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/center.svg',
					),
					'right'  => array(
						'name'  => esc_html__( 'Right', 'woodmart' ),
						'value' => 'right',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/right.svg',
					),
				),
				't_tab'     => array(
					'id'    => 'horizontal_align_tabs',
					'tab'   => esc_html__( 'Desktop', 'woodmart' ),
					'icon'  => 'xts-i-desktop',
					'style' => 'devices',
					'class' => 'xts-col-6',
				),
				'priority'  => 90,
				'class'     => 'xts-tab-field xts-last-tab-field xts-col-12',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'horizontal_align_tablet',
				'name'       => esc_html__( 'Horizontal align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-horizontal-align.mp4" autoplay loop muted></video>',
				'group'      => esc_html__( 'Layout', 'woodmart' ),
				'section'    => 'floating_block',
				'type'       => 'buttons',
				'css_device' => 'tablet',
				'selectors'  => array(
					'{{WRAPPER}}' => array(
						'--wd-justify-content: {{VALUE}};',
					),
				),
				'options'    => array(
					'left'   => array(
						'name'  => esc_html__( 'Left', 'woodmart' ),
						'value' => 'left',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/left.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Center', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/center.svg',
					),
					'right'  => array(
						'name'  => esc_html__( 'Right', 'woodmart' ),
						'value' => 'right',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/right.svg',
					),
				),
				't_tab'      => array(
					'id'   => 'horizontal_align_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'priority'   => 91,
				'class'      => 'xts-tab-field xts-last-tab-field xts-col-12',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'horizontal_align_mobile',
				'name'       => esc_html__( 'Horizontal align', 'woodmart' ),
				'hint'      => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-horizontal-align.mp4" autoplay loop muted></video>',
				'group'      => esc_html__( 'Layout', 'woodmart' ),
				'section'    => 'floating_block',
				'type'       => 'buttons',
				'css_device' => 'mobile',
				'selectors'  => array(
					'{{WRAPPER}}' => array(
						'--wd-justify-content: {{VALUE}};',
					),
				),
				'options'    => array(
					'left'   => array(
						'name'  => esc_html__( 'Left', 'woodmart' ),
						'value' => 'left',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/left.svg',
					),
					'center' => array(
						'name'  => esc_html__( 'Center', 'woodmart' ),
						'value' => 'center',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/center.svg',
					),
					'right'  => array(
						'name'  => esc_html__( 'Right', 'woodmart' ),
						'value' => 'right',
						'image' => WOODMART_ASSETS_IMAGES . '/settings/cmb2-align/right.svg',
					),
				),
				't_tab'      => array(
					'id'   => 'horizontal_align_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'priority'   => 92,
				'class'      => 'xts-tab-field xts-last-tab-field xts-col-12',
			)
		);

		// Style group.

		$metabox->add_field(
			array(
				'id'           => 'animation',
				'name'         => esc_html__( 'Animation', 'woodmart' ),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'type'         => 'select',
				'section'      => 'floating_block',
				'description'  => esc_html__( 'Select a floating block appearance animation.', 'woodmart' ),
				'options'      => array(
					''                       => array(
						'name'  => 'Disabled',
						'value' => '',
					),
					'slide-from-top'         => array(
						'name'  => esc_html__( 'Slide from top', 'woodmart' ),
						'value' => 'slide-from-right',
					),
					'slide-from-bottom'      => array(
						'name'  => esc_html__( 'Slide from bottom', 'woodmart' ),
						'value' => 'slide-from-right',
					),
					'slide-from-left'        => array(
						'name'  => esc_html__( 'Slide from left', 'woodmart' ),
						'value' => 'slide-from-left',
					),
					'slide-from-right'       => array(
						'name'  => esc_html__( 'Slide from right', 'woodmart' ),
						'value' => 'slide-from-right',
					),
					'slide-short-from-left'  => array(
						'name'  => esc_html__( 'Slide short from left', 'woodmart' ),
						'value' => 'slide-short-from-left',
					),
					'slide-short-from-right' => array(
						'name'  => esc_html__( 'Slide short from right', 'woodmart' ),
						'value' => 'slide-short-from-right',
					),
					'top-flip-x'             => array(
						'name'  => esc_html__( 'Top flip X', 'woodmart' ),
						'value' => 'top-flip-x',
					),
					'bottom-flip-x'          => array(
						'name'  => esc_html__( 'Bottom flip X', 'woodmart' ),
						'value' => 'bottom-flip-x',
					),
					'right-flip-y'           => array(
						'name'  => esc_html__( 'Right flip Y', 'woodmart' ),
						'value' => 'right-flip-y',
					),
					'left-flip-y'            => array(
						'name'  => esc_html__( 'Left flip Y', 'woodmart' ),
						'value' => 'left-flip-y',
					),
					'snap-in-top'            => array(
						'name'  => esc_html__( 'Snap in top', 'woodmart' ),
						'value' => 'snap-in-top',
					),
					'snap-in-bottom'         => array(
						'name'  => esc_html__( 'Snap in bottom', 'woodmart' ),
						'value' => 'snap-in-bottom',
					),
					'snap-in-left'           => array(
						'name'  => esc_html__( 'Snap in left', 'woodmart' ),
						'value' => 'snap-in-left',
					),
					'snap-in-right'          => array(
						'name'  => esc_html__( 'Snap in right', 'woodmart' ),
						'value' => 'snap-in-right',
					),
					'zoom-in'                => array(
						'name'  => esc_html__( 'Zoom in', 'woodmart' ),
						'value' => 'zoom-in',
					),
				),
				'value'        => '',
				'is_animation' => true,
				'default'      => 'none',
				'priority'     => 100,
			)
		);

		$metabox->add_field(
			array(
				'id'        => 'background_color',
				'name'      => esc_html__( 'Background color', 'woodmart' ),
				'type'      => 'color',
				'section'   => 'floating_block',
				'default'   => '',
				'priority'  => 110,
				'selectors' => array(
					'{{WRAPPER}} .wd-fb' => array(
						'background-color: {{VALUE}};',
					),
				),
				'group'     => esc_html__( 'Style', 'woodmart' ),
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'background_image',
				'name'     => esc_html__( 'Background image', 'woodmart' ),
				'type'     => 'upload',
				'section'  => 'floating_block',
				'priority' => 120,
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'class'    => 'xts-tab-field xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_size',
				'name'     => esc_html__( 'Image size', 'woodmart' ),
				'type'     => 'select',
				'section'  => 'floating_block',
				'options'  => woodmart_get_default_image_sizes(),
				'default'  => 'full',
				'requires' => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'priority' => 130,
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'class'    => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'image_size_custom_width',
				'name'       => esc_html__( 'Custom width (px)', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'floating_block',
				'attributes' => array(
					'type' => 'number',
				),
				'default'    => '',
				'requires'   => array(
					array(
						'key'     => 'image_size',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'priority'   => 131,
				'group'      => esc_html__( 'Style', 'woodmart' ),
				'class'      => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'image_size_custom_height',
				'name'       => esc_html__( 'Custom height (px)', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'floating_block',
				'attributes' => array(
					'type' => 'number',
				),
				'default'    => '',
				'requires'   => array(
					array(
						'key'     => 'image_size',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'priority'   => 132,
				'group'      => esc_html__( 'Style', 'woodmart' ),
				'class'      => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'image_object_fit',
				'name'         => esc_html__( 'Background size', 'woodmart' ),
				'type'         => 'select',
				'select2'      => true,
				'empty_option' => true,
				'section'      => 'floating_block',
				'options'      => array(
					'cover'   => array(
						'name'  => esc_html__( 'Cover', 'woodmart' ),
						'value' => 'cover',
					),
					'contain' => array(
						'name'  => esc_html__( 'Contain', 'woodmart' ),
						'value' => 'contain',
					),
					'fill'    => array(
						'name'  => esc_html__( 'Fill', 'woodmart' ),
						'value' => 'fill',
					),
					'none'    => array(
						'name'  => esc_html__( 'None', 'woodmart' ),
						'value' => 'none',
					),
				),
				'default'      => '',
				't_tab'        => array(
					'id'       => 'image_object_fit_tabs',
					'tab'      => esc_html__( 'Desktop', 'woodmart' ),
					'icon'     => 'xts-i-desktop',
					'style'    => 'devices',
					'requires' => array(
						array(
							'key'     => 'background_image',
							'compare' => 'not_equals',
							'value'   => array(),
						),
					),
				),
				'requires'     => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'priority'     => 140,
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'image_object_fit_tablet',
				'name'         => esc_html__( 'Background size', 'woodmart' ),
				'type'         => 'select',
				'select2'      => true,
				'empty_option' => true,
				'section'      => 'floating_block',
				'options'      => array(
					'cover'   => array(
						'name'  => esc_html__( 'Cover', 'woodmart' ),
						'value' => 'cover',
					),
					'contain' => array(
						'name'  => esc_html__( 'Contain', 'woodmart' ),
						'value' => 'contain',
					),
					'fill'    => array(
						'name'  => esc_html__( 'Fill', 'woodmart' ),
						'value' => 'fill',
					),
					'none'    => array(
						'name'  => esc_html__( 'None', 'woodmart' ),
						'value' => 'none',
					),
				),
				'default'      => '',
				'css_device'   => 'tablet',
				't_tab'        => array(
					'id'   => 'image_object_fit_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'requires'     => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'priority'     => 141,
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'image_object_fit_mobile',
				'name'         => esc_html__( 'Background size', 'woodmart' ),
				'type'         => 'select',
				'select2'      => true,
				'empty_option' => true,
				'section'      => 'floating_block',
				'options'      => array(
					'cover'   => array(
						'name'  => esc_html__( 'Cover', 'woodmart' ),
						'value' => 'cover',
					),
					'contain' => array(
						'name'  => esc_html__( 'Contain', 'woodmart' ),
						'value' => 'contain',
					),
					'fill'    => array(
						'name'  => esc_html__( 'Fill', 'woodmart' ),
						'value' => 'fill',
					),
					'none'    => array(
						'name'  => esc_html__( 'None', 'woodmart' ),
						'value' => 'none',
					),
				),
				'default'      => '',
				'css_device'   => 'mobile',
				't_tab'        => array(
					'id'   => 'image_object_fit_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'requires'     => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'priority'     => 142,
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'image_object_position',
				'name'         => esc_html__( 'Background position', 'woodmart' ),
				'type'         => 'select',
				'empty_option' => true,
				'select2'      => true,
				'section'      => 'floating_block',
				'options'      => array(
					'left-top'      => array(
						'name'  => esc_html__( 'Left Top', 'woodmart' ),
						'value' => 'left top',
					),
					'left-center'   => array(
						'name'  => esc_html__( 'Left Center', 'woodmart' ),
						'value' => 'left center',
					),
					'left-bottom'   => array(
						'name'  => esc_html__( 'Left Bottom', 'woodmart' ),
						'value' => 'left bottom',
					),
					'center-top'    => array(
						'name'  => esc_html__( 'Center Top', 'woodmart' ),
						'value' => 'center top',
					),
					'center-center' => array(
						'name'  => esc_html__( 'Center Center', 'woodmart' ),
						'value' => 'center center',
					),
					'center-bottom' => array(
						'name'  => esc_html__( 'Center Bottom', 'woodmart' ),
						'value' => 'center bottom',
					),
					'right-top'     => array(
						'name'  => esc_html__( 'Right Top', 'woodmart' ),
						'value' => 'right top',
					),
					'right-center'  => array(
						'name'  => esc_html__( 'Right Center', 'woodmart' ),
						'value' => 'right center',
					),
					'right-bottom'  => array(
						'name'  => esc_html__( 'Right Bottom', 'woodmart' ),
						'value' => 'right bottom',
					),
					'custom'        => array(
						'name'  => esc_html__( 'Custom', 'woodmart' ),
						'value' => 'custom',
					),
				),
				'default'      => '',
				't_tab'        => array(
					'id'       => 'image_object_position_tabs',
					'tab'      => esc_html__( 'Desktop', 'woodmart' ),
					'icon'     => 'xts-i-desktop',
					'style'    => 'devices',
					'requires' => array(
						array(
							'key'     => 'background_image',
							'compare' => 'not_equals',
							'value'   => array(),
						),
					),
				),
				'requires'     => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'priority'     => 150,
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'class'        => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_object_position_x',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Position by X (px)', 'woodmart' ),
				'section'  => 'floating_block',
				'requires' => array(
					array(
						'key'     => 'image_object_position',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				't_tab'    => array(
					'id'       => 'image_object_position_tabs',
					'icon'     => 'xts-i-desktop',
					'tab'      => esc_html__( 'Desktop', 'woodmart' ),
					'requires' => array(
						array(
							'key'     => 'image_object_position',
							'compare' => 'equals',
							'value'   => 'custom',
						),
						array(
							'key'     => 'background_image',
							'compare' => 'not_equals',
							'value'   => array(),
						),
					),
				),
				'priority' => 151,
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'class'    => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_object_position_y',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Position by Y (px)', 'woodmart' ),
				'section'  => 'floating_block',
				'requires' => array(
					array(
						'key'     => 'image_object_position',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				't_tab'    => array(
					'id'       => 'image_object_position_tabs',
					'icon'     => 'xts-i-desktop',
					'tab'      => esc_html__( 'Desktop', 'woodmart' ),
					'requires' => array(
						array(
							'key'     => 'image_object_position',
							'compare' => 'equals',
							'value'   => 'custom',
						),
						array(
							'key'     => 'background_image',
							'compare' => 'not_equals',
							'value'   => array(),
						),
					),
				),
				'priority' => 152,
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'class'    => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'image_object_position_tablet',
				'name'         => esc_html__( 'Background position', 'woodmart' ),
				'type'         => 'select',
				'empty_option' => true,
				'select2'      => true,
				'section'      => 'floating_block',
				'options'      => array(
					'left-top'      => array(
						'name'  => esc_html__( 'Left Top', 'woodmart' ),
						'value' => 'left top',
					),
					'left-center'   => array(
						'name'  => esc_html__( 'Left Center', 'woodmart' ),
						'value' => 'left center',
					),
					'left-bottom'   => array(
						'name'  => esc_html__( 'Left Bottom', 'woodmart' ),
						'value' => 'left bottom',
					),
					'center-top'    => array(
						'name'  => esc_html__( 'Center Top', 'woodmart' ),
						'value' => 'center top',
					),
					'center-center' => array(
						'name'  => esc_html__( 'Center Center', 'woodmart' ),
						'value' => 'center center',
					),
					'center-bottom' => array(
						'name'  => esc_html__( 'Center Bottom', 'woodmart' ),
						'value' => 'center bottom',
					),
					'right-top'     => array(
						'name'  => esc_html__( 'Right Top', 'woodmart' ),
						'value' => 'right top',
					),
					'right-center'  => array(
						'name'  => esc_html__( 'Right Center', 'woodmart' ),
						'value' => 'right center',
					),
					'right-bottom'  => array(
						'name'  => esc_html__( 'Right Bottom', 'woodmart' ),
						'value' => 'right bottom',
					),
					'custom'        => array(
						'name'  => esc_html__( 'Custom', 'woodmart' ),
						'value' => 'custom',
					),
				),
				'default'      => '',
				'css_device'   => 'tablet',
				't_tab'        => array(
					'id'   => 'image_object_position_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'requires'     => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'class'        => 'xts-col-4',
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'priority'     => 160,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_object_position_x_tablet',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Position by X (px)', 'woodmart' ),
				'section'  => 'floating_block',
				'requires' => array(
					array(
						'key'     => 'image_object_position_tablet',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				't_tab'    => array(
					'id'   => 'image_object_position_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'priority' => 161,
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'class'    => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_object_position_y_tablet',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Position by Y (px)', 'woodmart' ),
				'section'  => 'floating_block',
				'requires' => array(
					array(
						'key'     => 'image_object_position_tablet',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				't_tab'    => array(
					'id'   => 'image_object_position_tabs',
					'tab'  => esc_html__( 'Tablet', 'woodmart' ),
					'icon' => 'xts-i-tablet',
				),
				'priority' => 162,
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'class'    => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'image_object_position_mobile',
				'name'         => esc_html__( 'Background position', 'woodmart' ),
				'type'         => 'select',
				'empty_option' => true,
				'select2'      => true,
				'section'      => 'floating_block',
				'options'      => array(
					'left-top'      => array(
						'name'  => esc_html__( 'Left Top', 'woodmart' ),
						'value' => 'left top',
					),
					'left-center'   => array(
						'name'  => esc_html__( 'Left Center', 'woodmart' ),
						'value' => 'left center',
					),
					'left-bottom'   => array(
						'name'  => esc_html__( 'Left Bottom', 'woodmart' ),
						'value' => 'left bottom',
					),
					'center-top'    => array(
						'name'  => esc_html__( 'Center Top', 'woodmart' ),
						'value' => 'center top',
					),
					'center-center' => array(
						'name'  => esc_html__( 'Center Center', 'woodmart' ),
						'value' => 'center center',
					),
					'center-bottom' => array(
						'name'  => esc_html__( 'Center Bottom', 'woodmart' ),
						'value' => 'center bottom',
					),
					'right-top'     => array(
						'name'  => esc_html__( 'Right Top', 'woodmart' ),
						'value' => 'right top',
					),
					'right-center'  => array(
						'name'  => esc_html__( 'Right Center', 'woodmart' ),
						'value' => 'right center',
					),
					'right-bottom'  => array(
						'name'  => esc_html__( 'Right Bottom', 'woodmart' ),
						'value' => 'right bottom',
					),
					'custom'        => array(
						'name'  => esc_html__( 'Custom', 'woodmart' ),
						'value' => 'custom',
					),
				),
				'default'      => '',
				'css_device'   => 'mobile',
				't_tab'        => array(
					'id'   => 'image_object_position_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'requires'     => array(
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'class'        => 'xts-col-4',
				'priority'     => 170,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_object_position_x_mobile',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Position by X (px)', 'woodmart' ),
				'section'  => 'floating_block',
				'requires' => array(
					array(
						'key'     => 'image_object_position_mobile',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				't_tab'    => array(
					'id'   => 'image_object_position_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'priority' => 171,
				'class'    => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'image_object_position_y_mobile',
				'type'     => 'text_input',
				'name'     => esc_html__( 'Position by Y (px)', 'woodmart' ),
				'section'  => 'floating_block',
				'requires' => array(
					array(
						'key'     => 'image_object_position_mobile',
						'compare' => 'equals',
						'value'   => 'custom',
					),
					array(
						'key'     => 'background_image',
						'compare' => 'not_equals',
						'value'   => array(),
					),
				),
				't_tab'    => array(
					'id'   => 'image_object_position_tabs',
					'tab'  => esc_html__( 'Mobile', 'woodmart' ),
					'icon' => 'xts-i-phone',
				),
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'priority' => 172,
				'class'    => 'xts-col-4',
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'floating_block',
				'group'    => esc_html__( 'Style', 'woodmart' ),
				'priority' => 179,
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'box_shadow_group',
				'name'         => esc_html__( 'Box shadow', 'woodmart' ),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'type'         => 'group',
				'style'        => 'dropdown',
				'btn_settings' => array(
					'label'   => esc_html__( 'Edit settings', 'woodmart' ),
					'classes' => 'xts-i-cog',
				),
				'selectors'    => array(
					'{{WRAPPER}} .wd-fb' => array(
						'box-shadow:{{BOX_SHADOW_OFFSET_X}} {{BOX_SHADOW_OFFSET_Y}} {{BOX_SHADOW_BLUR}} {{BOX_SHADOW_SPREAD}} {{BOX_SHADOW_COLOR}};',
					),
				),
				'section'      => 'floating_block',
				'inner_fields' => array(
					array(
						'id'       => 'box_shadow_color',
						'name'     => esc_html__( 'Color', 'woodmart' ),
						'type'     => 'color',
						'default'  => array(),
						'priority' => 10,
					),
					array(
						'id'       => 'box_shadow_offset_x',
						'name'     => esc_html__( 'Horizontal offset', 'woodmart' ),
						'type'     => 'responsive_range',
						'devices'  => array(
							'desktop' => array(
								'value' => '',
								'unit'  => 'px',
							),
						),
						'range'    => array(
							'px' => array(
								'min'  => -100,
								'max'  => 100,
								'step' => 1,
							),
						),
						'priority' => 20,
					),
					array(
						'id'       => 'box_shadow_offset_y',
						'name'     => esc_html__( 'Vertical offset', 'woodmart' ),
						'type'     => 'responsive_range',
						'devices'  => array(
							'desktop' => array(
								'value' => '',
								'unit'  => 'px',
							),
						),
						'range'    => array(
							'px' => array(
								'min'  => -100,
								'max'  => 100,
								'step' => 1,
							),
						),
						'priority' => 30,
					),
					array(
						'id'       => 'box_shadow_blur',
						'name'     => esc_html__( 'Blur', 'woodmart' ),
						'type'     => 'responsive_range',
						'devices'  => array(
							'desktop' => array(
								'value' => '',
								'unit'  => 'px',
							),
						),
						'range'    => array(
							'px' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
						),
						'priority' => 40,
					),
					array(
						'id'       => 'box_shadow_spread',
						'name'     => esc_html__( 'Spread', 'woodmart' ),
						'type'     => 'responsive_range',
						'devices'  => array(
							'desktop' => array(
								'value' => '',
								'unit'  => 'px',
							),
						),
						'range'    => array(
							'px' => array(
								'min'  => -100,
								'max'  => 100,
								'step' => 1,
							),
						),
						'priority' => 50,
					),
				),
				'class'        => 'xts-col-6 xts-dropdown-open-top',
				'priority'     => 180,
			)
		);

		$metabox->add_field(
			array(
				'id'           => 'border_group',
				'name'         => esc_html__( 'Border', 'woodmart' ),
				'group'        => esc_html__( 'Style', 'woodmart' ),
				'type'         => 'group',
				'style'        => 'dropdown',
				'btn_settings' => array(
					'label'   => esc_html__( 'Edit settings', 'woodmart' ),
					'classes' => 'xts-i-cog',
				),
				'section'      => 'floating_block',
				'inner_fields' => array(
					array(
						'id'            => 'border_radius',
						'name'          => esc_html__( 'Border radius', 'woodmart' ),
						'type'          => 'responsive_range',
						'selectors'     => array(
							'{{WRAPPER}} .wd-fb' => array(
								'border-radius: {{VALUE}}{{UNIT}};',
							),
						),
						'generate_zero' => true,
						'devices'       => array(
							'desktop' => array(
								'value' => '',
								'unit'  => 'px',
							),
						),
						'range'         => array(
							'px' => array(
								'min'  => 0,
								'max'  => 300,
								'step' => 1,
							),
							'%'  => array(
								'min'  => 0,
								'max'  => 50,
								'step' => 1,
							),
						),
						'priority'      => 10,
					),
					array(
						'id'        => 'border_style',
						'name'      => esc_html__( 'Border style', 'woodmart' ),
						'type'      => 'select',
						'options'   => array(
							''       => array(
								'name'  => esc_html__( 'None', 'woodmart' ),
								'value' => '',
							),
							'solid'  => array(
								'name'  => esc_html__( 'Solid', 'woodmart' ),
								'value' => 'solid',
							),
							'dotted' => array(
								'name'  => esc_html__( 'Dotted', 'woodmart' ),
								'value' => 'dotted',
							),
							'double' => array(
								'name'  => esc_html__( 'Double', 'woodmart' ),
								'value' => 'double',
							),
							'dashed' => array(
								'name'  => esc_html__( 'Dashed', 'woodmart' ),
								'value' => 'dashed',
							),
							'groove' => array(
								'name'  => esc_html__( 'Groove', 'woodmart' ),
								'value' => 'groove',
							),
						),
						'selectors' => array(
							'{{WRAPPER}} .wd-fb' => array(
								'border-style: {{VALUE}};',
							),
						),
						'default'   => '',
						'priority'  => 20,
					),
					array(
						'id'        => 'border_color',
						'name'      => esc_html__( 'Color', 'woodmart' ),
						'type'      => 'color',
						'selectors' => array(
							'{{WRAPPER}} .wd-fb' => array(
								'border-color: {{VALUE}};',
							),
						),
						'default'   => array(),
						'requires'  => array(
							array(
								'key'     => 'border_style',
								'compare' => 'not_equals',
								'value'   => '',
							),
						),
						'priority'  => 30,
					),
					array(
						'id'        => 'border_width',
						'name'      => esc_html__( 'Border width', 'woodmart' ),
						'type'      => 'responsive_range',
						'devices'   => array(
							'desktop' => array(
								'value' => '',
								'unit'  => 'px',
							),
						),
						'range'     => array(
							'px' => array(
								'min'  => 0,
								'max'  => 20,
								'step' => 1,
							),
						),
						'requires'  => array(
							array(
								'key'     => 'border_style',
								'compare' => 'not_equals',
								'value'   => '',
							),
						),
						'selectors' => array(
							'{{WRAPPER}} .wd-fb' => array(
								'border-width: {{VALUE}}{{UNIT}};',
							),
						),
						'priority'  => 40,
					),
				),
				'class'        => 'xts-col-6 xts-dropdown-open-top',
				'priority'     => 190,
			)
		);

		// Close behavior section.

		$metabox->add_field(
			array(
				'id'          => 'close_btn',
				'name'        => esc_html__( 'Close button', 'woodmart' ),
				'hint'        => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-close-btn.mp4" autoplay loop muted></video>',
				'group'       => esc_html__( 'Close button', 'woodmart' ),
				'description' => esc_html__( 'Disable the default close button. After that, the block can be closed via the custom button using the “Close by selector” option.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'close_behavior',
				'default'     => false,
				'priority'    => 10,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'close_btn_display',
				'name'        => esc_html__( 'Display', 'woodmart' ),
				'type'        => 'buttons',
				'section'     => 'close_behavior',
				'group'       => esc_html__( 'Close button', 'woodmart' ),
				'description' => esc_html__( 'Choose the close button design.', 'woodmart' ),
				'default'     => 'icon',
				'options'     => array(
					'icon' => array(
						'name'  => esc_html__( 'Icon', 'woodmart' ),
						'value' => 'icon',
					),
					'text' => array(
						'name'  => esc_html__( 'Icon with text', 'woodmart' ),
						'value' => 'text',
					),
				),
				'priority'    => 20,
				'class'       => 'xts-col-12',
			)
		);

		$metabox->add_field(
			array(
				'id'        => 'close_btn_text_color',
				'name'      => esc_html__( 'Color', 'woodmart' ),
				'type'      => 'color',
				'section'   => 'close_behavior',
				'group'     => esc_html__( 'Close button', 'woodmart' ),
				'selectors' => array(
					'{{WRAPPER}} .wd-fb-close' => array(
						'--wd-action-color: {{VALUE}};',
					),
				),
				'default'   => array(),
				'priority'  => 30,
				'class'     => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'        => 'close_btn_text_color_hover',
				'name'      => esc_html__( 'Hover color', 'woodmart' ),
				'type'      => 'color',
				'section'   => 'close_behavior',
				'group'     => esc_html__( 'Close button', 'woodmart' ),
				'selectors' => array(
					'{{WRAPPER}} .wd-fb-close' => array(
						'--wd-action-color-hover: {{VALUE}};',
					),
				),
				'default'   => array(),
				'priority'  => 40,
				'class'     => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'            => 'close_btn_offset_v',
				'name'          => esc_html__( 'Offset vertical', 'woodmart' ),
				'hint'          => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-close-btn-offset-v.mp4" autoplay loop muted></video>',
				'group'         => esc_html__( 'Close button', 'woodmart' ),
				'type'          => 'responsive_range',
				'section'       => 'close_behavior',
				'selectors'     => array(
					'{{WRAPPER}}' => array(
						'--wd-close-btn-offset-v: {{VALUE}}{{UNIT}};',
					),
				),
				'generate_zero' => true,
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
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'priority'      => 50,
				'class'         => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'            => 'close_btn_offset_h',
				'name'          => esc_html__( 'Offset horizontal', 'woodmart' ),
				'hint'          => '<video data-src="' . WOODMART_TOOLTIP_URL . 'fb-close-btn-offset-h.mp4" autoplay loop muted></video>',
				'group'         => esc_html__( 'Close button', 'woodmart' ),
				'type'          => 'responsive_range',
				'section'       => 'close_behavior',
				'selectors'     => array(
					'{{WRAPPER}}' => array(
						'--wd-close-btn-offset-h: {{VALUE}}{{UNIT}};',
					),
				),
				'generate_zero' => true,
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
						'min'  => -1000,
						'max'  => 1000,
						'step' => 1,
					),
				),
				'priority'      => 60,
				'class'         => 'xts-col-6',
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'close_by_selector',
				'name'        => esc_html__( 'Close by selector', 'woodmart' ),
				'group'       => esc_html__( 'Settings', 'woodmart' ),
				'description' => esc_html__( 'Create an alternative block close button. Enter a CSS selector (e.g., .wd-close-block) that will close the floating block when clicked.', 'woodmart' ),
				'type'        => 'text_input',
				'section'     => 'close_behavior',
				'default'     => '',
				'priority'    => 70,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'persistent_close',
				'name'        => esc_html__( 'Persistent close', 'woodmart' ),
				'group'       => esc_html__( 'Settings', 'woodmart' ),
				'description' => esc_html__( 'Once closed, the block will stay hidden on reload until the version is raised.', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'close_behavior',
				'default'     => false,
				'priority'    => 80,
			)
		);

		// Conditions section.

		$metabox->add_field(
			array(
				'id'             => 'conditions',
				'group'          => esc_html__( 'Display condition', 'woodmart' ),
				'type'           => 'conditions',
				'section'        => 'conditions',
				'priority'       => 20,
				'inner_fields'   => array(
					'type'  => array(
						'name'    => esc_html__( 'Condition type', 'woodmart' ),
						'options' => apply_filters(
							'woodmart_conditions_types',
							array(
								'all'                  => esc_html__( 'All', 'woodmart' ),
								'post_type'            => esc_html__( 'Post types', 'woodmart' ),
								'single_post_type'     => esc_html__( 'Post type single page', 'woodmart' ),
								'post_id'              => esc_html__( 'Post ID', 'woodmart' ),
								'taxonomy'             => esc_html__( 'Taxonomy', 'woodmart' ),
								'term_id'              => esc_html__( 'Term ID', 'woodmart' ),
								'single_posts_term_id' => esc_html__( 'Single posts from term', 'woodmart' ),
								'user_role'            => esc_html__( 'User role', 'woodmart' ),
								'custom'               => esc_html__( 'Custom', 'woodmart' ),
							)
						),
					),
					'query' => array(
						'name'     => esc_html__( 'Condition query', 'woodmart' ),
						'options'  => array(),
						'requires' => array(
							array(
								'key'     => 'type',
								'compare' => 'not_equals',
								'value'   => 'all',
							),
						),
					),
				),
				'exclude_fields' => array(
					'product-type-query',
				),
			)
		);

		// Display mode section.

		$metabox->add_field(
			array(
				'id'       => 'display_type',
				'name'     => esc_html__( 'Display mode', 'woodmart' ),
				'type'     => 'select',
				'section'  => 'display_mode',
				'default'  => 'always',
				'options'  => array(
					'always'   => array(
						'name'  => esc_html__( 'Always', 'woodmart' ),
						'value' => 'always',
					),
					'triggers' => array(
						'name'  => esc_html__( 'Use triggers', 'woodmart' ),
						'value' => 'triggers',
					),
				),
				'priority' => 5,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_some_time_enabled',
				'name'        => esc_html__( 'Page loaded', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Show floating block after some time (in milliseconds).', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'priority'    => 10,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'time_to_show',
				'name'       => esc_html__( 'Time to show', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'display_mode',
				'attributes' => array(
					'type' => 'number',
					'min'  => 0,
				),
				'requires'   => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_some_time_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'class'      => 'xts-col-5',
				'default'    => 0,
				'priority'   => 11,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'time_to_show_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_some_time_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 12,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 13,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_after_scroll_enabled',
				'name'        => esc_html__( 'User scroll', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Show floating block after user scrolls a certain percentage of the page.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'priority'    => 20,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'            => 'scroll_value',
				'name'          => esc_html__( 'Scroll value', 'woodmart' ),
				'type'          => 'responsive_range',
				'generate_zero' => true,
				'section'       => 'display_mode',
				'class'         => 'xts-col-5',
				'requires'      => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_after_scroll_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'devices'       => array(
					'desktop' => array(
						'value' => '',
						'unit'  => '%',
					),
				),
				'range'         => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10000,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'       => 50,
				'priority'      => 21,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'after_scroll_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_after_scroll_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 22,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 23,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_scroll_to_selector_enabled',
				'name'        => esc_html__( 'Scroll to selector', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Show floating block when user scrolls to a specific CSS selector.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'priority'    => 30,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'scroll_to_selector',
				'name'     => esc_html__( 'Selector', 'woodmart' ),
				'type'     => 'text_input',
				'section'  => 'display_mode',
				'description' => esc_html__( 'Comma-separated list of selectors. For example: .wrapper .special-button, .newsletter-icon', 'woodmart' ),
				'class'    => 'xts-col-5',
				'requires' => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_scroll_to_selector_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'default'  => '',
				'priority' => 31,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'scroll_to_selector_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_scroll_to_selector_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 32,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 33,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_inactivity_time_enabled',
				'name'        => esc_html__( 'Inactivity time', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Show floating block after inactivity time (in milliseconds).', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'priority'    => 40,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'inactivity_time',
				'name'       => esc_html__( 'Time to show', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'display_mode',
				'class'      => 'xts-col-5',
				'attributes' => array(
					'type' => 'number',
					'min'  => 1,
				),
				'requires'   => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_inactivity_time_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'default'    => 10000,
				'priority'   => 41,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'inactivity_time_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_inactivity_time_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'priority' => 42,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 43,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_exit_intent_enabled',
				'name'        => esc_html__( 'Exit intent', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Floating block appears when the cursor exits the viewport, suggesting tab closure.', 'woodmart' ),
				'class'       => 'xts-col-10',
				'default'     => false,
				'priority'    => 50,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'exit_intent_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_exit_intent_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'priority' => 51,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 52,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_on_click_enabled',
				'name'        => esc_html__( 'On click', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Number of clicks required to show floating block.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'priority'    => 60,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'click_times',
				'name'       => esc_html__( 'Click times', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'display_mode',
				'attributes' => array(
					'type' => 'number',
					'min'  => 1,
				),
				'requires'   => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_on_click_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'class'      => 'xts-col-5',
				'default'    => 3,
				'priority'   => 61,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'click_times_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_on_click_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 62,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 63,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_on_selector_click_enabled',
				'name'        => esc_html__( 'On selector click', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'CSS selector to trigger floating block on click.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'priority'    => 70,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'selector',
				'name'     => esc_html__( 'Selector', 'woodmart' ),
				'type'     => 'text_input',
				'section'  => 'display_mode',
				'description' => esc_html__( 'Comma-separated list of selectors. For example: .wrapper .special-button, .newsletter-icon', 'woodmart' ),
				'class'    => 'xts-col-5',
				'requires' => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_on_selector_click_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'default'  => '',
				'priority' => 71,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'selector_click_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_on_selector_click_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 72,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 73,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_url_parameter_enabled',
				'name'        => esc_html__( 'URL contains specific parameter', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Name of the URL parameter to check.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority'    => 80,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'parameters',
				'name'        => esc_html__( 'Parameters', 'woodmart' ),
				'description' => esc_html__( 'Comma-separated list of parameters. For example: utm_source=facebook, single_key', 'woodmart' ),
				'type'        => 'text_input',
				'section'     => 'display_mode',
				'class'       => 'xts-col-5',
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_url_parameter_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'default'     => '',
				'priority'    => 81,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'url_parameter_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_url_parameter_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'priority' => 82,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 83,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_url_hashtag_enabled',
				'name'        => esc_html__( 'URL contains specific hashtag', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Name of the URL hashtag to check.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority'    => 90,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'hashtags',
				'name'        => esc_html__( 'Hashtags', 'woodmart' ),
				'description' => esc_html__( 'Comma-separated list of hashtags. For example: #hashtag1, #hashtag2', 'woodmart' ),
				'type'        => 'text_input',
				'section'     => 'display_mode',
				'class'       => 'xts-col-5',
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
					array(
						'key'     => 'is_url_hashtag_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
				),
				'default'     => '',
				'priority'    => 91,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'url_hashtag_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_url_hashtag_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 92,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 93,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_after_page_views_enabled',
				'name'        => esc_html__( 'After page views', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Show block after a specific number of page views.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority'    => 100,
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'after_page_views',
				'name'       => esc_html__( 'Views', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'display_mode',
				'class'      => 'xts-col-5',
				'attributes' => array(
					'type' => 'number',
					'min'  => 1,
				),
				'requires'   => array(
					array(
						'key'     => 'is_after_page_views_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority'   => 101,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'after_page_views_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_after_page_views_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 102,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'clear',
				'type'     => 'clear',
				'section'  => 'display_mode',
				'priority' => 103,
			)
		);

		$metabox->add_field(
			array(
				'id'          => 'is_after_sessions_enabled',
				'name'        => esc_html__( 'After sessions', 'woodmart' ),
				'type'        => 'switcher',
				'section'     => 'display_mode',
				'description' => esc_html__( 'Show block after a specific number of user sessions.', 'woodmart' ),
				'class'       => 'xts-col-5',
				'default'     => false,
				'requires'    => array(
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority'    => 110,
			)
		);

		$metabox->add_field(
			array(
				'id'         => 'after_sessions',
				'name'       => esc_html__( 'Sessions', 'woodmart' ),
				'type'       => 'text_input',
				'section'    => 'display_mode',
				'class'      => 'xts-col-5',
				'attributes' => array(
					'type' => 'number',
					'min'  => 1,
				),
				'requires'   => array(
					array(
						'key'     => 'is_after_sessions_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority'   => 111,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'after_sessions_once',
				'name'     => esc_html__( 'Trigger once', 'woodmart' ),
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'type'     => 'switcher',
				'section'  => 'display_mode',
				'default'  => false,
				'class'    => 'xts-col-2',
				'requires' => array(
					array(
						'key'     => 'is_after_sessions_enabled',
						'compare' => 'equals',
						'value'   => true,
					),
					array(
						'key'     => 'display_type',
						'compare' => 'equals',
						'value'   => 'triggers',
					),
				),
				'priority' => 112,
			)
		);

		// Responsive section.

		$metabox->add_field(
			array(
				'id'       => 'hide_floating_block',
				'name'     => esc_html__( 'Hide on desktop', 'woodmart' ),
				'section'  => 'responsive',
				'type'     => 'switcher',
				'class'    => 'xts-col-4',
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'default'  => false,
				'priority' => 40,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'hide_floating_block_tablet',
				'name'     => esc_html__( 'Hide on tablet', 'woodmart' ),
				'section'  => 'responsive',
				'type'     => 'switcher',
				'class'    => 'xts-col-4',
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'default'  => false,
				'priority' => 41,
			)
		);

		$metabox->add_field(
			array(
				'id'       => 'hide_floating_block_mobile',
				'name'     => esc_html__( 'Hide on mobile', 'woodmart' ),
				'section'  => 'responsive',
				'type'     => 'switcher',
				'class'    => 'xts-col-4',
				'on-text'  => esc_html__( 'Yes', 'woodmart' ),
				'off-text' => esc_html__( 'No', 'woodmart' ),
				'default'  => false,
				'priority' => 42,
			)
		);
	}
}

Fb_Metaboxes::get_instance();
