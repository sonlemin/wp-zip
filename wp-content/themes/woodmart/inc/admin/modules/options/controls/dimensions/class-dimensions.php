<?php
/**
 * Dimensions control.
 *
 * @package xts
 */

namespace XTS\Admin\Modules\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Admin\Modules\Options\Field;

/**
 * Input type text field control.
 */
class Dimensions extends Field {
	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		$value        = $this->get_field_value();
		$data         = array();
		$control_type = ! empty( $this->args['controls_type'] ) ? $this->args['controls_type'] : 'input';

		if ( ! empty( $value ) && function_exists( 'woodmart_decompress' ) && woodmart_is_compressed_data( $value ) ) {
			$data = json_decode( woodmart_decompress( $value ), true );
		} else {
			$data['devices'] = $this->args['devices'];
		}

		$data['devices'] = array_merge( $this->args['devices'], $data['devices'] );


		?>
			<div class="xts-dimensions xts-field-type-<?php echo esc_attr( $control_type ); ?>">
				<?php if ( $data['devices'] && ! empty( $this->args['dimensions'] ) ) : ?>
					<div class="xts-control-tabs-nav">
						<?php if ( 1 < count( $data['devices'] ) ) : ?>
							<?php foreach ( $data['devices'] as $device => $device_settings ) : ?>
								<span class="xts-control-tab-nav-item xts-device<?php echo esc_attr( ' wd-' . $device ); ?><?php echo array_key_first( $data['devices'] ) === $device ? esc_attr( ' xts-active' ) : ''; ?>" data-value="<?php echo esc_attr( $device ); ?>">
									<span>
										<?php echo esc_attr( $device ); ?>
									</span>
								</span>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<?php foreach ( $data['devices'] as $device => $device_settings ) : ?>
						<?php
						$device_unit = isset( $device_settings['unit'] ) ? $device_settings['unit'] : '-';
						$min_value   = isset( $this->args['range'][ $device_unit ]['min'] ) ? $this->args['range'][ $device_unit ]['min'] : '';
						$max_value   = isset( $this->args['range'][ $device_unit ]['max'] ) ? $this->args['range'][ $device_unit ]['max'] : '';
						$step_value  = isset( $this->args['range'][ $device_unit ]['step'] ) ? $this->args['range'][ $device_unit ]['step'] : '';

						?>
						<div class="xts-control-tab-content<?php echo array_key_first( $data['devices'] ) === $device ? esc_attr( ' xts-active' ) : ''; ?>"  data-device="<?php echo esc_attr( $device ); ?>" data-unit="<?php echo esc_attr( $device_unit ); ?>">
							<?php foreach ( $this->args['dimensions'] as $key => $title ) : ?>
								<div class="xts-dimensions-field">
									<label>
										<?php echo esc_html( $title ); ?>
									</label>
									<?php if ( 'slider' === $control_type ) : ?>
										<div class="xts-dimensions-slider"></div>
									<?php endif; ?>
									<span class="xts-dimensions-field-value-input">
										<input type="number" data-key="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( isset( $device_settings[ $key ] ) ? $device_settings[ $key ] : '' ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( $max_value ); ?>" step="<?php echo esc_attr( $step_value ); ?>"/>
									</span>
								</div>
							<?php endforeach; ?>
							<div class="xts-lock-units xts-add-on<?php echo ! empty( $data['is_lock'] ) ? ' xts-active' : ''; ?>"></div>
							<?php if ( ! empty( $this->args['range'] ) ) : ?>
								<div class="xts-slider-units">
									<?php foreach ( $this->args['range'] as $unit => $value ) : ?>
										<?php if ( '-' === $unit ) : ?>
											<?php continue; ?>
										<?php endif; ?>

										<span class="wd-slider-unit-control<?php echo esc_attr( $unit === $device_unit ? ' xts-active' : '' ); ?>" data-unit="<?php echo esc_attr( $unit ); ?>">
											<?php echo esc_html( $unit ); ?>
										</span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<input type="hidden" class="xts-dimensions-value" name="<?php echo esc_attr( $this->get_input_name() ); ?>" value="<?php echo function_exists( 'woodmart_compress' ) ? woodmart_compress( wp_json_encode( $data ) ) : ''; ?>" data-settings="<?php echo esc_attr( wp_json_encode( $this->args ) ); ?>">
		<?php
	}

	/**
	 * Output field's css code based on the settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array $output Generated CSS code.
	 */
	public function css_output() {
		if ( empty( $this->args['selectors'] ) || empty( $this->get_field_value() ) || ! function_exists( 'woodmart_decompress' ) || empty( $this->args['dimensions'] ) ) {
			return array();
		}

		if ( ! empty( $this->args['requires'] ) ) {
			foreach ( $this->args['requires'] as $require ) {
				if ( isset( $this->options[ $require['key'] ] ) ) {
					if ( 'equals' === $require['compare'] && ( ( is_array( $require['value'] ) && ! in_array( $this->options[ $require['key'] ], $require['value'], true ) ) || ( ! is_array( $require['value'] ) && $this->options[ $require['key'] ] !== $require['value'] ) ) ) {
						return array();
					} elseif ( 'not_equals' === $require['compare'] && ( ( is_array( $require['value'] ) && in_array( $this->options[ $require['key'] ], $require['value'], true ) ) || ( ! is_array( $require['value'] ) && $this->options[ $require['key'] ] === $require['value'] ) ) ) {
						return array();
					}
				}
			}
		}

		$value      = json_decode( woodmart_decompress( $this->get_field_value() ), true );
		$output_css = array();

		if ( empty( $value['devices'] ) ) {
			return array();
		}

		foreach ( $value['devices'] as $device => $device_value ) {
			$generate_css = false;

			foreach ( $this->args['dimensions'] as $key => $label ) {
				if ( isset( $device_value[ $key ] ) && ( $device_value[ $key ] || ( '' !== $device_value[ $key ] && ! empty( $this->args['generate_zero'] ) ) ) ) {
					$generate_css = true;

					break;
				}
			}

			if ( ! $generate_css ) {
				continue;
			}

			if ( ! $device ) {
				$device = 'desktop';
			}

			foreach ( $this->args['selectors'] as $selector => $css_data ) {
				foreach ( $css_data as $css ) {
					$dimension_keys = array_keys( $this->args['dimensions'] );

					$all_placeholders_exist = true;
					foreach ( $dimension_keys as $dim_key ) {
						if ( strpos( $css, '{{' . strtoupper( $dim_key ) . '}}' ) === false ) {
							$all_placeholders_exist = false;
							break;
						}
					}

					if ( $all_placeholders_exist ) {
						$has_all_values = true;

						foreach ( $dimension_keys as $dim_key ) {
							if ( ! isset( $device_value[ $dim_key ] ) || '' === $device_value[ $dim_key ] ) {
								$has_all_values = false;
								break;
							}
						}

						if ( $has_all_values ) {
							$result = $css;

							foreach ( $dimension_keys as $dim_key ) {
								$result = str_replace( '{{' . strtoupper( $dim_key ) . '}}', $device_value[ $dim_key ], $result );
							}

							if ( isset( $device_value['unit'] ) ) {
								$result = str_replace( '{{UNIT}}', $device_value['unit'], $result );
							}

							$output_css[ $device ][ $selector ][] = $result . "\n";
						} else {
							preg_match( '/^\s*([a-zA-Z\-]+)\s*:/', $css, $prop_match );
							$property_prefix = isset( $prop_match[1] ) ? trim( $prop_match[1] ) : '';

							if ( empty( $property_prefix ) ) {
								continue;
							}

							foreach ( $dimension_keys as $dim_key ) {
								if ( isset( $device_value[ $dim_key ] ) && '' !== $device_value[ $dim_key ] ) {
									$unit = isset( $device_value['unit'] ) && '-' !== $device_value['unit'] ? $device_value['unit'] : '';

									$output_css[ $device ][ $selector ][] = $property_prefix . '-' . $dim_key . ': ' . $device_value[ $dim_key ] . $unit . ';' . "\n";
								}
							}
						}
					} else {
						preg_match_all( '/{{(.*?)}}/', $css, $matches );
						$placeholders = $matches[1];

						$value_keys = array_filter(
							$placeholders,
							function ( $key ) {
								return strtoupper( $key ) !== 'UNIT';
							}
						);

						if ( count( $value_keys ) === 1 ) {
							$key = strtolower( $value_keys[0] );

							if ( ! isset( $device_value[ $key ] ) ) {
								continue;
							}
						}

						$result = $css;

						foreach ( $value_keys as $key ) {
							$lower_key   = strtolower( $key );
							$replace_val = '';

							if ( isset( $device_value[ $lower_key ] ) ) {
								$replace_val = $device_value[ $lower_key ];
							} elseif ( ! empty( $this->args['generate_zero'] ) ) {
								$replace_val = 0;
							}

							$result = str_replace( '{{' . strtoupper( $key ) . '}}', $replace_val, $result );
						}

						if ( isset( $device_value['unit'] ) ) {
							$result = str_replace( '{{UNIT}}', $device_value['unit'], $result );
						}

						$output_css[ $device ][ $selector ][] = $result . "\n";
					}
				}
			}

//			foreach ( $this->args['selectors'] as $selector => $css_data ) {
//				foreach ( $css_data as $css ) {
//					preg_match_all( '/{{(.*?)}}/', $css, $matches );
//					$placeholders = $matches[1];
//
//					$value_keys = array_filter(
//						$placeholders,
//						function ( $key ) {
//							return strtoupper( $key ) !== 'UNIT';
//						}
//					);
//
//					if ( count( $value_keys ) === 1 ) {
//						$key = strtolower( $value_keys[0] );
//
//						if ( ! isset( $device_value[ $key ] ) ) {
//							continue;
//						}
//					}
//
//					$result = $css;
//
//					foreach ( $value_keys as $key ) {
//						$lower_key   = strtolower( $key );
//						$replace_val = '';
//
//						if ( isset( $device_value[ $lower_key ] ) ) {
//							$replace_val = $device_value[ $lower_key ];
//						} elseif ( ! empty( $this->args['generate_zero'] ) ) {
//							$replace_val = 0;
//						}
//
//						$result = str_replace( '{{' . strtoupper( $key ) . '}}', $replace_val, $result );
//					}
//
//					if ( isset( $device_value['unit'] ) ) {
//						$result = str_replace( '{{UNIT}}', $device_value['unit'], $result );
//					}
//
//					$output_css[ $device ][ $selector ][] = $result . "\n";
//				}
//			}
		}

		return $output_css;
	}

	/**
	 * Enqueue slider jquery ui.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		if ( ! empty( $this->args['controls_type'] ) && 'slider' === $this->args['controls_type'] ) {
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_style( 'xts-jquery-ui', WOODMART_ASSETS . '/css/jquery-ui.css', array(), woodmart_get_theme_info( 'Version' ) );
		}
	}
}
