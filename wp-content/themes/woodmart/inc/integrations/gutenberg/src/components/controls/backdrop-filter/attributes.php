<?php
/**
 * Backdrop filter attributes.
 *
 * @package Woodmart
 */

if ( ! function_exists( 'wd_get_backdrop_filter_control_attrs' ) ) {
	function wd_get_backdrop_filter_control_attrs( $attr, $attrs_prefix = '' ) {
		$attr->add_attr(
			array(
				'blur'       => array(
					'type' => 'number',
				),
				'brightness' => array(
					'type'    => 'number',
					'default' => 1,
				),
				'contrast'   => array(
					'type'    => 'number',
					'default' => 100,
				),
				'grayscale'  => array(
					'type' => 'number',
				),
				'hueRotate'  => array(
					'type' => 'number',
				),
				'invert'     => array(
					'type' => 'number',
				),
				'opacity'    => array(
					'type'    => 'number',
					'default' => 100,
				),
				'saturate'   => array(
					'type'    => 'number',
					'default' => 100,
				),
				'sepia'      => array(
					'type' => 'number',
				),
			),
			$attrs_prefix
		);
	}
}
