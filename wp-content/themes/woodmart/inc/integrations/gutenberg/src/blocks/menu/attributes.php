<?php

use XTS\Gutenberg\Block_Attributes;

if ( ! function_exists( 'wd_get_block_menu_attrs' ) ) {
	function wd_get_block_menu_attrs() {
		$attr = new Block_Attributes();

		$attr->add_attr(
			array(
				'nav_menu'                   => array(
					'type' => 'string',
				),
				'design'                     => array(
					'type'    => 'string',
					'default' => 'horizontal',
				),
				'dropdown_design'            => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'vertical_items_gap'         => array(
					'type'    => 'string',
					'default' => 's',
				),
				'customVerticalItemsGap'     => array(
					'type'          => 'number',
					'xtsResponsive' => true,
				),
				'style'                      => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'items_gap'                  => array(
					'type'    => 'string',
					'default' => 's',
				),
				'customItemsGap'             => array(
					'type'          => 'number',
					'xtsResponsive' => true,
				),
				'align'                      => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'icon_alignment'             => array(
					'type' => 'string',
				),
				'color_scheme'               => array(
					'type' => 'string',
				),
				'iconWidth'                  => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'iconHeight'                 => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'itemsBorderWidthLock'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'itemsBorderHoverWidthLock'  => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'itemsBorderActiveWidthLock' => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'disable_active_style'       => array(
					'type' => 'boolean',
				),
			)
		);

		$attr->add_attr( wd_get_typography_control_attrs(), 'itemTp' );
		$attr->add_attr( wd_get_color_control_attrs( 'itemsColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'itemsHoverColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'itemsActiveColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'itemsBgColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'itemsBgHoverColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'itemsBgActiveColor' ) );

		wd_get_box_shadow_control_attrs( $attr, 'itemsBoxShadow' );
		wd_get_box_shadow_control_attrs( $attr, 'itemsBoxShadowHover' );
		wd_get_box_shadow_control_attrs( $attr, 'itemsBoxShadowActive' );

		wd_get_border_control_attrs( $attr, 'itemsBorder' );
		wd_get_border_control_attrs( $attr, 'itemsBorderHover' );
		wd_get_border_control_attrs( $attr, 'itemsBorderActive' );

		wd_get_padding_control_attrs( $attr, 'itemsPadding' );

		wd_get_advanced_tab_attrs( $attr );

		return $attr->get_attr();
	}
}
