<?php

use XTS\Gutenberg\Block_Attributes;

if ( ! function_exists( 'wd_get_my_account_navigation_attrs' ) ) {
	function wd_get_my_account_navigation_attrs() {
		$attr = new Block_Attributes();

		$attr->add_attr(
			array(
				'layout_type'              => array(
					'type'    => 'string',
					'default' => 'inline',
				),
				'orientation'              => array(
					'type'    => 'string',
					'default' => 'vertical',
				),
				'navColumns'               => array(
					'type'       => 'number',
					'default'    => 3,
					'responsive' => true,
				),
				'navSpacing'               => array(
					'type'       => 'string',
					'default'    => '30',
					'responsive' => true,
				),
				'nav_design'               => array(
					'type'    => 'string',
					'default' => 'simple',
				),
				'style'                    => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'items_gap'                => array(
					'type'    => 'string',
					'default' => 'm',
				),
				'align'                    => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'show_icons'               => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'icon_alignment'           => array(
					'type' => 'string',
				),
				'color_scheme'             => array(
					'type' => 'string',
				),
				'iconSize'                 => array(
					'type'       => 'string',
					'responsive' => true,
				),
				'customItemsGap'           => array(
					'type'       => 'number',
					'responsive' => true,
				),
				'customVerticalItemsGap'   => array(
					'type'       => 'number',
					'responsive' => true,
				),
				'navBorderWidthLock'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'navBorderHoverWidthLock'  => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'navBorderActiveWidthLock' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			)
		);

		$attr->add_attr( wd_get_typography_control_attrs(), 'itemTp' );
		$attr->add_attr( wd_get_color_control_attrs( 'navColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'navHoverColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'navActiveColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'iconColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'iconColorHover' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'iconColorActive' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'navBgColor' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'navBgColorHover' ) );
		$attr->add_attr( wd_get_color_control_attrs( 'navBgColorActive' ) );
		wd_get_border_control_attrs( $attr, 'navBorder' );
		wd_get_border_control_attrs( $attr, 'navBorderHover' );
		wd_get_border_control_attrs( $attr, 'navBorderActive' );
		wd_get_box_shadow_control_attrs( $attr, 'navBoxShadow' );
		wd_get_box_shadow_control_attrs( $attr, 'navBoxShadowHover' );
		wd_get_box_shadow_control_attrs( $attr, 'navBoxShadowActive' );
		wd_get_padding_control_attrs( $attr, 'itemsPadding' );

		wd_get_advanced_tab_attrs( $attr );
		return $attr->get_attr();
	}
}
