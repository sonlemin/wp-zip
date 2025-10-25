<?php

use XTS\Gutenberg\Block_CSS;

if ( ! function_exists( 'wd_get_block_padding_css' ) ) {
	function wd_get_block_padding_css( $selector, $attributes, $attr_prefix, $rule = 'padding', $force_shorthand = false ) {
		$block_css = new Block_CSS( $attributes );

		foreach ( array( 'global', 'tablet', 'mobile' ) as $device ) {
			$device_name = 'global' !== $device ? ucfirst( $device ) : '';

			$top    = isset( $attributes[ $attr_prefix . 'Top' . $device_name ] ) ? $attributes[ $attr_prefix . 'Top' . $device_name ] : '';
			$right  = isset( $attributes[ $attr_prefix . 'Right' . $device_name ] ) ? $attributes[ $attr_prefix . 'Right' . $device_name ] : '';
			$bottom = isset( $attributes[ $attr_prefix . 'Bottom' . $device_name ] ) ? $attributes[ $attr_prefix . 'Bottom' . $device_name ] : '';
			$left   = isset( $attributes[ $attr_prefix . 'Left' . $device_name ] ) ? $attributes[ $attr_prefix . 'Left' . $device_name ] : '';

			if ( '' === $top && '' === $right && '' === $bottom && '' === $left ) {
				continue;
			}

			if ( $force_shorthand || ( ! is_array( $rule ) && '' !== $top && '' !== $right && '' !== $bottom && '' !== $left ) ) {
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
