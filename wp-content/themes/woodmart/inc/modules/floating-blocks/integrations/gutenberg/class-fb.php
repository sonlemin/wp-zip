<?php
/**
 * Floating block metaboxes.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Floating_Blocks\Integrations;

use XTS\Gutenberg\Block_Attributes;
use XTS\Gutenberg\Block_CSS;
use XTS\Singleton;


/**
 * Floating block metaboxes.
 */
class Gutenberg_FB extends Singleton {

	/**
	 * Meta from REST API.
	 *
	 * @var array
	 */
	private $rest_meta_data = array();

	/**
	 * Init.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_meta_fields' ), 100 );

		add_action( 'rest_pre_insert_wd_floating_block', array( $this, 'handle_rest_post_save' ), 10, 3 );
		add_filter( 'woodmart_post_blocks_css', array( $this, 'render_css' ), 10, 3 );
	}

	/**
	 * Register post meta fields for Gutenberg editor.
	 *
	 * @return void
	 */
	public function register_meta_fields() {
		$attributes = $this->get_block_attributes();

		if ( ! $attributes ) {
			return;
		}

		foreach ( $attributes as $key => $value ) {
			$settings = array(
				'single'        => true,
				'type'          => $value['type'],
				'auth_callback' => '__return_true',
				'show_in_rest'  => in_array( $value['type'], array( 'array', 'object' ), true ) ? $this->prepare_rest_schema_for_attribute( $value['type'] ) : true,
			);

			if ( isset( $value['default'] ) ) {
				$settings['default'] = $value['default'];
			}

			register_post_meta(
				'wd_floating_block',
				$key,
				$settings
			);
		}
	}

	/**
	 * Prepare REST schema configuration for an attribute type.
	 *
	 * @param string $type Attribute type.
	 * @return array
	 */
	public function prepare_rest_schema_for_attribute( $type ) {
		return array(
			'schema' => array(
				'type'                 => $type,
				'additionalProperties' => true,
			),
		);
	}

	/**
	 * Define floating block attributes configuration.
	 *
	 * @return array
	 */
	protected function get_block_attributes() {
		if ( ! class_exists( 'XTS\Gutenberg\Block_Attributes' ) ) {
			return array();
		}

		$attr = new Block_Attributes();

		$attr->add_attr(
			array(
				'wd_width'                         => array(
					'type'       => 'string',
					'responsive' => true,
					'units'      => 'px',
				),
				'wd_height'                        => array(
					'type'       => 'string',
					'responsive' => true,
					'units'      => 'px',
				),
				'wd_content_vertical_align'        => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'wd_positioning_area'              => array(
					'type'    => 'string',
					'default' => 'full-width',
				),
				'wd_horizontal_align'              => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'wd_vertical_align'                => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'wd_z_index'                       => array(
					'type' => 'string',
				),
				'wd_close_btn'                     => array(
					'type' => 'boolean',
				),
				'wd_close_btn_display'             => array(
					'type'    => 'string',
					'default' => 'icon',
				),
				'wd_close_by_selector'             => array(
					'type' => 'string',
				),
				'wd_persistent_close'              => array(
					'type' => 'boolean',
				),
				'wd_hide_floating_block'           => array(
					'type'       => 'boolean',
					'responsive' => true,
				),
				'wd_enable_page_scrolling'         => array(
					'type' => 'boolean',
				),
				'wd_animation'                     => array(
					'type' => 'string',
				),
				'wd_btn_offset_v'                  => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'wd_btn_offset_h'                  => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'wd_display_type'                  => array(
					'type'    => 'string',
					'default' => 'always',
				),
				'wd_is_some_time_enabled'          => array(
					'type' => 'boolean',
				),
				'wd_time_to_show'                  => array(
					'type'    => 'string',
					'default' => '0',
				),
				'wd_time_to_show_once'             => array(
					'type' => 'boolean',
				),
				'wd_is_after_scroll_enabled'       => array(
					'type' => 'boolean',
				),
				'wd_scroll_value'                  => array(
					'type'    => 'string',
					'default' => '50',
					'units'   => '%',
				),
				'wd_after_scroll_once'             => array(
					'type' => 'boolean',
				),
				'wd_is_scroll_to_selector_enabled' => array(
					'type' => 'boolean',
				),
				'wd_scroll_to_selector'            => array(
					'type' => 'string',
				),
				'wd_scroll_to_selector_once'       => array(
					'type' => 'boolean',
				),
				'wd_is_inactivity_time_enabled'    => array(
					'type' => 'boolean',
				),
				'wd_inactivity_time'               => array(
					'type'    => 'string',
					'default' => '10000',
				),
				'wd_inactivity_time_once'          => array(
					'type' => 'boolean',
				),
				'wd_is_exit_intent_enabled'        => array(
					'type' => 'boolean',
				),
				'wd_exit_intent_once'              => array(
					'type' => 'boolean',
				),
				'wd_is_on_click_enabled'           => array(
					'type' => 'boolean',
				),
				'wd_click_times'                   => array(
					'type'    => 'string',
					'default' => '3',
				),
				'wd_click_times_once'              => array(
					'type' => 'boolean',
				),
				'wd_is_on_selector_click_enabled'  => array(
					'type' => 'boolean',
				),
				'wd_selector'                      => array(
					'type' => 'string',
				),
				'wd_selector_click_once'           => array(
					'type' => 'boolean',
				),
				'wd_is_url_parameter_enabled'      => array(
					'type' => 'boolean',
				),
				'wd_parameters'                    => array(
					'type' => 'string',
				),
				'wd_url_parameter_once'            => array(
					'type' => 'boolean',
				),
				'wd_is_url_hashtag_enabled'        => array(
					'type' => 'boolean',
				),
				'wd_hashtags'                      => array(
					'type' => 'string',
				),
				'wd_url_hashtag_once'              => array(
					'type' => 'boolean',
				),
				'wd_is_after_page_views_enabled'   => array(
					'type' => 'boolean',
				),
				'wd_after_page_views'              => array(
					'type'    => 'string',
					'default' => '1',
				),
				'wd_after_page_views_once'         => array(
					'type' => 'boolean',
				),
				'wd_is_after_sessions_enabled'     => array(
					'type' => 'boolean',
				),
				'wd_after_sessions'                => array(
					'type'    => 'string',
					'default' => '1',
				),
				'wd_after_sessions_once'           => array(
					'type' => 'boolean',
				),
				'wd_conditions'                    => array(
					'type'    => 'object',
					'default' => array(
						array(
							'comparison' => 'include',
							'type'       => 'all',
						),
					),
				),
			),
		);

		$attr->add_attr( wd_get_color_control_attrs( 'wd_close_btn_text_color' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'wd_close_btn_text_color_hover' ) );

		wd_get_margin_control_attrs( $attr, 'wd_margin' );
		wd_get_padding_control_attrs( $attr, 'wd_padding' );
		wd_get_box_shadow_control_attrs( $attr, 'wd_box_shadow' );
		wd_get_border_control_attrs( $attr, 'wd_border' );
		wd_get_background_control_attrs( $attr, 'wd_background' );

		return $attr->get_attr();
	}

	/**
	 * Handle post save from REST API and store meta data.
	 *
	 * @param \stdClass        $post Post object prepared for database.
	 * @param \WP_REST_Request $request REST request object.
	 */
	public function handle_rest_post_save( $post, $request ) {
		$this->rest_meta_data = $request->get_param( 'meta' );

		return $post;
	}

	/**
	 * Render CSS.
	 *
	 * @param array  $css Existing CSS.
	 * @param int    $post_id Post ID.
	 * @param object $post Post object.
	 * @return array
	 */
	public function render_css( $css, $post_id, $post ) {
		if ( ! $post_id || ! $post || 'wd_floating_block' !== $post->post_type ) {
			return $css;
		}

		$attrs = array();

		if ( ! empty( $this->rest_meta_data ) ) {
			$attrs = $this->rest_meta_data;
		} else {
			$raw_attrs = $this->get_block_attributes();

			foreach ( $raw_attrs as $attr => $value ) {
				$attrs[ $attr ] = get_post_meta( $post_id, $attr, true );
			}
		}

		if ( empty( $attrs ) || ! is_array( $attrs ) ) {
			return $css;
		}

		$block_css      = new Block_CSS( $attrs );
		$block_selector = '#wd-fb-' . $post_id;

		$block_css->add_css_rules(
			$block_selector,
			array(
				array(
					'attr_name' => 'wd_width',
					'template'  => '--wd-fb-w: {{value}}' . $block_css->get_units_for_attribute( 'wd_width' ) . ';',
				),
				array(
					'attr_name' => 'wd_height',
					'template'  => '--wd-fb-h: {{value}}' . $block_css->get_units_for_attribute( 'wd_height' ) . ';',
				),

				array(
					'attr_name' => 'wd_horizontal_align',
					'template'  => '--wd-justify-content: {{value}};',
				),
				array(
					'attr_name' => 'wd_vertical_align',
					'template'  => '--wd-align-items: {{value}};',
				),

				array(
					'attr_name' => 'wd_z_index',
					'template'  => 'z-index: {{value}};',
				),

				array(
					'attr_name' => 'wd_btn_offset_v',
					'template'  => '--wd-close-btn-offset-v: {{value}}' . $block_css->get_units_for_attribute( 'wd_btn_offset_v' ) . ';',
				),
				array(
					'attr_name' => 'wd_btn_offset_h',
					'template'  => '--wd-close-btn-offset-h: {{value}}' . $block_css->get_units_for_attribute( 'wd_btn_offset_h' ) . ';',
				),
			)
		);

		$block_css->add_css_rules(
			$block_selector . ' .wd-fb',
			array(
				array(
					'attr_name' => 'wd_content_vertical_align',
					'template'  => '--wd-align-items: {{value}};',
				),
			)
		);

		$block_css->add_css_rules(
			$block_selector . ' .wd-fb-close',
			array(
				array(
					'attr_name' => 'wd_close_btn_text_colorCode',
					'template'  => '--wd-action-color: {{value}};',
				),
				array(
					'attr_name' => 'wd_close_btn_text_colorVariable',
					'template'  => '--wd-action-color: var({{value}});',
				),
			)
		);

		$block_css->add_css_rules(
			$block_selector . ' .wd-fb-close',
			array(
				array(
					'attr_name' => 'wd_close_btn_text_color_hoverCode',
					'template'  => '--wd-action-color-hover: {{value}};',
				),
				array(
					'attr_name' => 'wd_close_btn_text_color_hoverVariable',
					'template'  => '--wd-action-color-hover: var({{value}});',
				),
			)
		);

		$block_css->add_css_rules(
			$block_selector,
			array(
				array(
					'attr_name' => 'wd_widthTablet',
					'template'  => '--wd-fb-w: {{value}}' . $block_css->get_units_for_attribute( 'wd_width', 'tablet' ) . ';',
				),
				array(
					'attr_name' => 'wd_heightTablet',
					'template'  => '--wd-fb-h: {{value}}' . $block_css->get_units_for_attribute( 'wd_height', 'tablet' ) . ';',
				),

				array(
					'attr_name' => 'wd_horizontal_alignTablet',
					'template'  => '--wd-justify-content: {{value}};',
				),
				array(
					'attr_name' => 'wd_vertical_alignTablet',
					'template'  => '--wd-align-items: {{value}};',
				),

				array(
					'attr_name' => 'wd_close_btn_offset_vTablet',
					'template'  => '--wd-close-btn-offset-v: {{value}}' . $block_css->get_units_for_attribute( 'wd_close_btn_offset_v', 'tablet' ) . ';',
				),
				array(
					'attr_name' => 'wd_close_btn_offset_hTablet',
					'template'  => '--wd-close-btn-offset-h: {{value}}' . $block_css->get_units_for_attribute( 'wd_close_btn_offset_h', 'tablet' ) . ';',
				),
			),
			'tablet'
		);

		$block_css->add_css_rules(
			$block_selector . ' .wd-fb',
			array(
				array(
					'attr_name' => 'wd_content_vertical_alignTablet',
					'template'  => '--wd-align-items: {{value}};',
				),
			),
			'tablet'
		);

		$block_css->add_css_rules(
			$block_selector,
			array(
				array(
					'attr_name' => 'wd_widthMobile',
					'template'  => '--wd-fb-w: {{value}}' . $block_css->get_units_for_attribute( 'wd_width', 'mobile' ) . ';',
				),
				array(
					'attr_name' => 'wd_heightMobile',
					'template'  => '--wd-fb-h: {{value}}' . $block_css->get_units_for_attribute( 'wd_height', 'mobile' ) . ';',
				),

				array(
					'attr_name' => 'wd_horizontal_alignMobile',
					'template'  => '--wd-justify-content: {{value}};',
				),
				array(
					'attr_name' => 'wd_vertical_alignMobile',
					'template'  => '--wd-align-items: {{value}};',
				),

				array(
					'attr_name' => 'wd_close_btn_offset_vMobile',
					'template'  => '--wd-close-btn-offset-v: {{value}}' . $block_css->get_units_for_attribute( 'wd_close_btn_offset_v', 'mobile' ) . ';',
				),
				array(
					'attr_name' => 'wd_close_btn_offset_hMobile',
					'template'  => '--wd-close-btn-offset-h: {{value}}' . $block_css->get_units_for_attribute( 'wd_close_btn_offset_h', 'mobile' ) . ';',
				),
			),
			'mobile'
		);

		$block_css->add_css_rules(
			$block_selector . ' .wd-fb',
			array(
				array(
					'attr_name' => 'wd_content_vertical_alignMobile',
					'template'  => '--wd-align-items: {{value}};',
				),
			),
			'mobile'
		);

		$block_css->merge_with( wd_get_block_bg_css( $block_selector . ' .wd-fb', $attrs, 'wd_background', 'image' ) );
		$block_css->merge_with( wd_get_block_border_css( $block_selector . ' .wd-fb', $attrs, 'wd_border' ) );
		$block_css->merge_with( wd_get_block_box_shadow_css( $block_selector . ' .wd-fb', $attrs, 'wd_box_shadow' ) );

		$block_css->merge_with( wd_get_block_padding_css( $block_selector . ' .wd-fb-inner', $attrs, 'wd_padding' ) );
		$block_css->merge_with(
			wd_get_block_margin_css(
				$block_selector,
				$attrs,
				'wd_margin',
				array(
					'top'    => '--wd-fb-mt',
					'right'  => '--wd-fb-mr',
					'bottom' => '--wd-fb-mb',
					'left'   => '--wd-fb-ml',
				)
			)
		);

		$css_for_devices = $block_css->get_css_for_devices();

		foreach ( $css_for_devices as $device => $css_device ) {
			if ( $css_device ) {
				$css[ $device ] .= ' ' . $css_device;
			}
		}

		return $css;
	}
}

Gutenberg_FB::get_instance();
