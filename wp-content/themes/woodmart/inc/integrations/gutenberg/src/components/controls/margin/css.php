<?php

use XTS\Gutenberg\Block_CSS;

if ( ! function_exists( 'wd_get_block_margin_css' ) ) {
	function wd_get_block_margin_css( $selector, $attributes, $attr_prefix, $rule = 'margin' ) {
		$block_css = new Block_CSS( $attributes );

		foreach ( array( 'global', 'tablet', 'mobile' ) as $device ) {
			$device_name = 'global' !== $device ? ucfirst( $device ) : '';

			if (
				! is_array( $rule ) &&
				isset( $attributes[ $attr_prefix . 'Top' . $device_name ] ) && '' !== $attributes[ $attr_prefix . 'Top' . $device_name ]
				&& isset( $attributes[ $attr_prefix . 'Right' . $device_name ] ) && '' !== $attributes[ $attr_prefix . 'Right' . $device_name ]
				&& isset( $attributes[ $attr_prefix . 'Bottom' . $device_name ] ) && '' !== $attributes[ $attr_prefix . 'Bottom' . $device_name ]
				&& isset( $attributes[ $attr_prefix . 'Left' . $device_name ] ) && '' !== $attributes[ $attr_prefix . 'Left' . $device_name ]
			) {
				$block_css->add_to_selector(
					$selector,
					$rule . ':' . $block_css->get_value_from_sides( $attr_prefix, $device ) . ';',
					$device
				);
			} else {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'Top' . $device_name,
							'template'  => ( is_array( $rule ) && isset( $rule['top'] ) ? $rule['top'] : $rule . '-top' ) . ': {{value}}' . $block_css->get_units_for_attribute( $attr_prefix, $device ) . ';',
						),
						array(
							'attr_name' => $attr_prefix . 'Right' . $device_name,
							'template'  => ( is_array( $rule ) && isset( $rule['right'] ) ? $rule['right'] : $rule . '-right' ) . ': {{value}}' . $block_css->get_units_for_attribute( $attr_prefix, $device ) . ';',
						),
						array(
							'attr_name' => $attr_prefix . 'Bottom' . $device_name,
							'template'  => ( is_array( $rule ) && isset( $rule['bottom'] ) ? $rule['bottom'] : $rule . '-bottom' ) . ': {{value}}' . $block_css->get_units_for_attribute( $attr_prefix, $device ) . ';',
						),
						array(
							'attr_name' => $attr_prefix . 'Left' . $device_name,
							'template'  => ( is_array( $rule ) && isset( $rule['left'] ) ? $rule['left'] : $rule . '-left' ) . ': {{value}}' . $block_css->get_units_for_attribute( $attr_prefix, $device ) . ';',
						),
					),
					$device
				);
			}
		}

		return $block_css->get_css();
	}
}
