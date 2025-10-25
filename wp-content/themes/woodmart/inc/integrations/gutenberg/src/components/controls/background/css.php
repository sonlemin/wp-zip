<?php
/**
 * Gutenberg background CSS.
 *
 * @package Woodmart
 */

use XTS\Gutenberg\Block_CSS;

if ( ! function_exists( 'wd_get_block_bg_css' ) ) {
	/**
	 * Get block background CSS.
	 *
	 * @param string $selector CSS selector.
	 * @param array  $attributes Block attributes.
	 * @param string $attr_prefix Attribute prefix.
	 * @param string $image_type Image type: background or image.
	 * @return array
	 */
	function wd_get_block_bg_css( $selector, $attributes, $attr_prefix, $image_type = 'background' ) {
		$type      = isset( $attributes[ $attr_prefix . 'Type' ] ) ? $attributes[ $attr_prefix . 'Type' ] : 'classic';
		$block_css = new Block_CSS( $attributes );

		$bg_image_position = function( $device ) use ( $attributes, $attr_prefix, $block_css ) {
			$device_prefix = 'desktop' !== $device ? ucfirst( $device ) : '';

			if ( 'custom' !== $attributes[ $attr_prefix . 'Position' . $device_prefix ] ) {
				return $attributes[ $attr_prefix . 'Position' . $device_prefix ];
			}

			$rule  = ( isset( $attributes[ $attr_prefix . 'CustomPositionX' . $device_prefix ] ) && '' !== $attributes[ $attr_prefix . 'CustomPositionX' . $device_prefix ] ) ? $attributes[ $attr_prefix . 'CustomPositionX' . $device_prefix ] : '0';
			$rule .= $block_css->get_units_for_attribute( 'CustomPositionX', $device );

			$rule .= ' ';
			$rule .= ( isset( $attributes[ $attr_prefix . 'CustomPositionY' . $device_prefix ] ) && '' !== $attributes[ $attr_prefix . 'CustomPositionY' . $device_prefix ] ) ? $attributes[ $attr_prefix . 'CustomPositionY' . $device_prefix ] : '0';
			$rule .= $block_css->get_units_for_attribute( $attr_prefix . 'CustomPositionY', $device );

			return $rule;
		};

		$block_css->add_css_rules(
			$selector,
			array(
				array(
					'attr_name' => $attr_prefix . 'ColorCode',
					'template'  => 'background-color: {{value}};',
				),
				array(
					'attr_name' => $attr_prefix . 'ColorVariable',
					'template'  => 'background-color: var({{value}});',
				),
			)
		);

		if ( 'classic' === $type ) {
			if ( 'background' === $image_type ) {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'Image,url',
							'template'  => 'background-image: url({{value}});',
						),
					)
				);
			}

			$block_css->add_css_rules(
				$selector,
				array(
					array(
						'attr_name' => $attr_prefix . 'Attachment',
						'template'  => 'background-attachment: {{value}};',
					),
					array(
						'attr_name' => $attr_prefix . 'Repeat',
						'template'  => 'background-repeat: {{value}};',
					),
				)
			);

			$block_css->add_css_rules(
				$selector . ' img',
				array(
					array(
						'attr_name' => $attr_prefix . 'ObjectFit',
						'template'  => 'object-fit: {{value}};',
					),
				)
			);

			if ( ! empty( $attributes[ $attr_prefix . 'Position' ] ) ) {
				if ( 'image' === $image_type ) {
					$block_css->add_to_selector(
						$selector . ' img',
						'object-position:' . $bg_image_position( 'desktop' ) . ';',
					);
				} else {
					$block_css->add_to_selector(
						$selector,
						'background-position:' . $bg_image_position( 'desktop' ) . ';',
					);
				}
			}

			if ( isset( $attributes[ $attr_prefix . 'DisplaySize' ] ) ) {
				if ( 'custom' !== $attributes[ $attr_prefix . 'DisplaySize' ] ) {
					$block_css->add_css_rules(
						$selector,
						array(
							array(
								'attr_name' => $attr_prefix . 'DisplaySize',
								'template'  => 'background-size: {{value}};',
							),
						)
					);
				} else {
					$block_css->add_css_rules(
						$selector,
						array(
							array(
								'attr_name' => $attr_prefix . 'CustomDisplaySize',
								'template'  => 'background-size: {{value}}' . $block_css->get_units_for_attribute( $attr_prefix . 'CustomDisplaySize' ) . ';',
							),
						)
					);
				}
			}

			if ( 'background' === $image_type ) {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'ImageTablet,url',
							'template'  => 'background-image: url({{value}});',
						),
					),
					'tablet'
				);
			}

			if ( ! empty( $attributes[ $attr_prefix . 'PositionTablet' ] ) ) {
				if ( 'image' === $image_type ) {
					$block_css->add_to_selector(
						$selector . ' img',
						'object-position:' . $bg_image_position( 'tablet' ) . ';',
						'tablet'
					);
				} else {
					$block_css->add_to_selector(
						$selector,
						'background-position:' . $bg_image_position( 'tablet' ) . ';',
						'tablet'
					);
				}
			}

			if ( isset( $attributes[ $attr_prefix . 'DisplaySizeTablet' ] ) ) {
				if ( 'custom' !== $attributes[ $attr_prefix . 'DisplaySizeTablet' ] ) {
					$block_css->add_css_rules(
						$selector,
						array(
							array(
								'attr_name' => $attr_prefix . 'DisplaySizeTablet',
								'template'  => 'background-size: {{value}};',
							),
						),
						'tablet'
					);
				} else {
					$block_css->add_css_rules(
						$selector,
						array(
							array(
								'attr_name' => $attr_prefix . 'CustomDisplaySizeTablet',
								'template'  => 'background-size: {{value}}' . $block_css->get_units_for_attribute( $attr_prefix . 'CustomDisplaySize', 'tablet' ) . ';',
							),
						),
						'tablet'
					);
				}
			}

			$block_css->add_css_rules(
				$selector . ' img',
				array(
					array(
						'attr_name' => $attr_prefix . 'ObjectFitTablet',
						'template'  => 'object-fit: {{value}};',
					),
				),
				'tablet'
			);

			if ( 'background' === $image_type ) {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'ImageMobile,url',
							'template'  => 'background-image: url({{value}});',
						),
					),
					'mobile'
				);
			}

			if ( ! empty( $attributes[ $attr_prefix . 'PositionMobile' ] ) ) {
				if ( 'image' === $image_type ) {
					$block_css->add_to_selector(
						$selector . ' img',
						'object-position:' . $bg_image_position( 'mobile' ) . ';',
						'mobile'
					);
				} else {
					$block_css->add_to_selector(
						$selector,
						'background-position:' . $bg_image_position( 'mobile' ) . ';',
						'mobile'
					);
				}
			}

			if ( isset( $attributes[ $attr_prefix . 'DisplaySizeMobile' ] ) ) {
				if ( 'custom' !== $attributes[ $attr_prefix . 'DisplaySizeMobile' ] ) {
					$block_css->add_css_rules(
						$selector,
						array(
							array(
								'attr_name' => $attr_prefix . 'DisplaySizeMobile',
								'template'  => 'background-size: {{value}};',
							),
						),
						'mobile'
					);
				} else {
					$block_css->add_css_rules(
						$selector,
						array(
							array(
								'attr_name' => $attr_prefix . 'CustomDisplaySizeMobile',
								'template'  => 'background-size: {{value}}' . $block_css->get_units_for_attribute( $attr_prefix . 'CustomDisplaySize', 'mobile' ) . ';',
							),
						),
						'mobile'
					);
				}
			}

			$block_css->add_css_rules(
				$selector . ' img',
				array(
					array(
						'attr_name' => $attr_prefix . 'ObjectFitMobile',
						'template'  => 'object-fit: {{value}};',
					),
				),
				'mobile'
			);
		}

		if ( 'gradient' === $type ) {
			$gradient_position = ! empty( $attributes[ $attr_prefix . 'GradientPosition' ] ) ? $attributes[ $attr_prefix . 'GradientPosition' ] : 'center center';

			if ( ! empty( $attributes[ $attr_prefix . 'Gradient' ] ) && false !== strpos( $attributes[ $attr_prefix . 'Gradient' ], 'radial-gradient' ) ) {
				$gradient = str_replace( 'radial-gradient(', 'radial-gradient(at ' . $gradient_position . ',', $attributes[ $attr_prefix . 'Gradient' ] );

				$block_css->add_to_selector(
					$selector,
					'background-image: ' . $gradient . ';',
				);
			} else {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'Gradient',
							'template'  => 'background-image: {{value}};',
						),
					)
				);
			}

			if ( ! empty( $attributes[ $attr_prefix . 'GradientTablet' ] ) && false !== strpos( $attributes[ $attr_prefix . 'GradientTablet' ], 'radial-gradient' ) ) {
				$gradient_position = ! empty( $attributes[ $attr_prefix . 'GradientPositionTablet' ] ) ? $attributes[ $attr_prefix . 'GradientPositionTablet' ] : $gradient_position;

				$gradient = str_replace( 'radial-gradient(', 'radial-gradient(at ' . $gradient_position . ',', $attributes[ $attr_prefix . 'GradientTablet' ] );

				$block_css->add_to_selector(
					$selector,
					'background-image: ' . $gradient . ';',
					'tablet'
				);
			} else {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'GradientTablet',
							'template'  => 'background-image: {{value}};',
						),
					),
					'tablet'
				);
			}

			if ( ! empty( $attributes[ $attr_prefix . 'GradientMobile' ] ) && false !== strpos( $attributes[ $attr_prefix . 'GradientMobile' ], 'radial-gradient' ) ) {
				$gradient_position = ! empty( $attributes[ $attr_prefix . 'GradientPositionMobile' ] ) ? $attributes[ $attr_prefix . 'GradientPositionMobile' ] : $gradient_position;

				$gradient = str_replace( 'radial-gradient(', 'radial-gradient(at ' . $gradient_position . ',', $attributes[ $attr_prefix . 'GradientMobile' ] );

				$block_css->add_to_selector(
					$selector,
					'background-image: ' . $gradient . ';',
					'mobile'
				);
			} else {
				$block_css->add_css_rules(
					$selector,
					array(
						array(
							'attr_name' => $attr_prefix . 'GradientMobile',
							'template'  => 'background-image: {{value}};',
						),
					),
					'mobile'
				);
			}
		}

		if ( 'video' === $type ) {
			$block_css->add_css_rules(
				$selector,
				array(
					array(
						'attr_name' => $attr_prefix . 'VideoFallback,url',
						'template'  => 'background-image: url({{value}});',
					),
				)
			);
		}

		return $block_css->get_css();
	}
}
