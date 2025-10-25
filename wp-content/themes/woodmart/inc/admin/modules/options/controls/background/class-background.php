<?php
/**
 * Set element background options and generate css.
 *
 * @package xts
 */

namespace XTS\Admin\Modules\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Admin\Modules\Options\Field;

/**
 * Background properties control.
 */
class Background extends Field {

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		$value        = $this->get_field_value();
		$style        = '';
		$allowed_size = woodmart_get_default_image_sizes();

		$options = array_merge(
			array(
				'image_size' => array_combine(
					array_column( $allowed_size, 'value' ),
					array_column( $allowed_size, 'name' )
				),
				'repeat'     => array(
					''          => '',
					'no-repeat' => esc_html__( 'No Repeat', 'woodmart' ),
					'repeat'    => esc_html__( 'Repeat', 'woodmart' ),
					'repeat-x'  => esc_html__( 'Repeat Horizontally', 'woodmart' ),
					'repeat-y'  => esc_html__( 'Repeat Vertically', 'woodmart' ),
					'inherit'   => esc_html__( 'Inherit', 'woodmart' ),
				),
				'size'       => array(
					''        => '',
					'cover'   => esc_html__( 'Cover', 'woodmart' ),
					'contain' => esc_html__( 'Contain', 'woodmart' ),
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
				),
				'attachment' => array(
					''        => '',
					'fixed'   => esc_html__( 'Fixed', 'woodmart' ),
					'scroll'  => esc_html__( 'Scroll', 'woodmart' ),
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
				),
				'position'   => array(
					''              => '',
					'left top'      => esc_html__( 'Left Top', 'woodmart' ),
					'left center'   => esc_html__( 'Left Center', 'woodmart' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'woodmart' ),
					'center top'    => esc_html__( 'Center Top', 'woodmart' ),
					'center center' => esc_html__( 'Center Center', 'woodmart' ),
					'center bottom' => esc_html__( 'Center Bottom', 'woodmart' ),
					'right top'     => esc_html__( 'Right Top', 'woodmart' ),
					'right center'  => esc_html__( 'Right Center', 'woodmart' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'woodmart' ),
				),
			),
			! empty( $this->args['options'] ) ? $this->args['options'] : array()
		);

		if ( ! empty( $value['color'] ) || ! empty( $value['id'] ) ) {
			if ( ! empty( $value['color'] ) ) {
				$style .= ' background-color:' . $value['color'] . ';';
			}
			if ( ! empty( $value['id'] ) ) {
				$style .= ' background-image: url(' . wp_get_attachment_image_url( $value['id'] ) . '); ';
			}
			if ( ! empty( $value['repeat'] ) ) {
				$style .= ' background-repeat:' . $value['repeat'] . ';';
			}
			if ( ! empty( $value['size'] ) ) {
				$style .= ' background-size:' . $value['size'] . ';';
			}
			if ( ! empty( $value['attachment'] ) ) {
				$style .= ' background-attachment:' . $value['attachment'] . ';';
			}
			if ( ! empty( $value['position'] ) ) {
				$style .= ' background-position:' . $value['position'] . ';';
			}

			if ( $style ) {
				$style .= ' height: 100px';
			}
		}

		?>
			<div class="xts-bg-source">
				<div class="xts-bg-color">
					<input type="text" class="color-picker" data-alpha-enabled="<?php echo isset( $this->args['alpha'] ) ? esc_attr( $this->args['alpha'] ) : 'true'; ?>" name="<?php echo esc_attr( $this->get_input_name( 'color' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'color' ) ); ?>" />
				</div>
				<div class="xts-bg-image">
					<div class="xts-upload-preview<?php echo ( isset( $value['url'] ) && ! empty( $value['url'] ) ) ? ' xts-preview-shown' : ''; ?>">
						<?php if ( isset( $value['url'] ) && ! empty( $value['url'] ) ) : ?>
							<img src="<?php echo esc_url( $value['url'] ); ?>">
						<?php endif ?>
					</div>
				</div>
				<div class="xts-upload-btns">
					<button class="xts-btn xts-upload-btn xts-i-import"><?php esc_html_e( 'Upload', 'woodmart' ); ?></button>
					<button class="xts-btn xts-color-warning xts-remove-upload-btn xts-i-trash<?php echo ( isset( $value['url'] ) && ! empty( $value['url'] ) ) ? ' xts-active' : ''; ?>"><?php esc_html_e( 'Remove', 'woodmart' ); ?></button>
					<input type="hidden" class="xts-upload-input-url" name="<?php echo esc_attr( $this->get_input_name( 'url' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'url' ) ); ?>" />
					<input type="hidden" class="xts-upload-input-id" name="<?php echo esc_attr( $this->get_input_name( 'id' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'id' ) ); ?>" />
				</div>
			</div>

			<div class="xts-bg-controls xts-row xts-sp-10">
				<div class="xts-col-xl-6 xts-col-12">
					<div class="xts-bg-image-options xts-row xts-sp-10<?php echo empty( $value['url'] ) ? ' xts-hidden' : ''; ?>">
						<?php if ( ! empty( $options['image_size'] ) ) : ?>
							<div class="xts-col-12">
								<div class="xts-row xts-sp-10">
									<div class="xts-col-12">
										<?php $image_size = $this->get_field_value( 'image_size' ) ? $this->get_field_value( 'image_size' ) : 'full'; ?>
										<select class="xts-image-size" data-placeholder="<?php esc_attr_e( 'Image size', 'woodmart' ); ?>" name="<?php echo esc_attr( $this->get_input_name( 'image_size' ) ); ?>">
											<?php foreach ( $options['image_size'] as $key => $label ) : ?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $image_size, $key ); ?>>
													<?php echo esc_html( $label ); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="xts-col-lg-6 xts-col-12<?php echo 'custom' !== $this->get_field_value( 'image_size' ) ? ' xts-hidden' : ''; ?>">
										<input type="number" name="<?php echo esc_attr( $this->get_input_name( 'image_size_custom_width' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'image_size_custom_width' ) ); ?>" placeholder="<?php esc_attr_e( 'Width', 'woodmart' ); ?>" class="xts-image-size-custom" />
									</div>
									<div class="xts-col-lg-6 xts-col-12<?php echo 'custom' !== $this->get_field_value( 'image_size' ) ? ' xts-hidden' : ''; ?>">
										<input type="number" name="<?php echo esc_attr( $this->get_input_name( 'image_size_custom_height' ) ); ?>" value="<?php echo esc_attr( $this->get_field_value( 'image_size_custom_height' ) ); ?>" placeholder="<?php esc_attr_e( 'Height', 'woodmart' ); ?>" class="xts-image-size-custom" />
									</div>
								</div>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $options['repeat'] ) ) : ?>
							<div class="xts-col-lg-6 xts-col-12">
								<select class="xts-bg-repeat" data-placeholder="<?php esc_attr_e( 'Background repeat', 'woodmart' ); ?>" name="<?php echo esc_attr( $this->get_input_name( 'repeat' ) ); ?>">
									<?php foreach ( $options['repeat'] as $key => $label ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->get_field_value( 'repeat' ), $key ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $options['size'] ) ) : ?>
							<div class="xts-col-lg-6 xts-col-12">
								<select class="xts-bg-size" data-placeholder="<?php esc_attr_e( 'Background size', 'woodmart' ); ?>" name="<?php echo esc_attr( $this->get_input_name( 'size' ) ); ?>">
									<?php foreach ( $options['size'] as $key => $label ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->get_field_value( 'size' ), $key ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $options['attachment'] ) ) : ?>
							<div class="xts-col-lg-6 xts-col-12">
								<select class="xts-bg-attachment" data-placeholder="<?php esc_attr_e( 'Background attachment', 'woodmart' ); ?>" name="<?php echo esc_attr( $this->get_input_name( 'attachment' ) ); ?>">
									<?php foreach ( $options['attachment'] as $key => $label ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->get_field_value( 'attachment' ), $key ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $options['position'] ) ) : ?>
							<div class="xts-col-lg-6 xts-col-12">
								<select class="xts-bg-position" data-placeholder="<?php esc_attr_e( 'Background position', 'woodmart' ); ?>" name="<?php echo esc_attr( $this->get_input_name( 'position' ) ); ?>">
									<?php foreach ( $options['position'] as $key => $label ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->get_field_value( 'position' ), $key ); ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="xts-col-xl-6 xts-col-12">
					<div class="xts-bg-preview" style="<?php echo esc_attr( $style ); ?>"></div>
				</div>
			</div>

		<?php
	}

	/**
	 * Enqueue colorpicker lib.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', WOODMART_ASSETS . '/js/libs/wp-color-picker-alpha.js', array( 'wp-color-picker' ), woodmart_get_theme_info( 'Version' ), true );
		wp_enqueue_script( 'select2', WOODMART_ASSETS . '/js/libs/select2.full.min.js', array(), woodmart_get_theme_info( 'Version' ), true );
	}

	/**
	 * Output field's css code based on the settings..
	 *
	 * @since 1.0.0
	 *
	 * @return array $output Generated CSS code.
	 */
	public function css_output() {
		if ( ! isset( $this->args['selector'] ) || empty( $this->args['selector'] ) || empty( $this->get_field_value() ) || ( ! $this->get_field_value( 'color' ) && ! $this->get_field_value( 'url' ) && ! $this->get_field_value( 'repeat' ) && ! $this->get_field_value( 'size' ) && ! $this->get_field_value( 'attachment' ) && ! $this->get_field_value( 'position' ) ) ) {
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

		$device = ! empty( $this->args['css_device'] ) ? $this->args['css_device'] : 'desktop';
		$value  = $this->get_field_value();

		$css_rules = array_merge(
			array(
				'color'      => 'background-color',
				'image'      => 'background-image',
				'repeat'     => 'background-repeat',
				'size'       => 'background-size',
				'attachment' => 'background-attachment',
				'position'   => 'background-position',
			),
			! empty( $this->args['css_rules'] ) ? $this->args['css_rules'] : array()
		);

		$output = array();

		if ( $css_rules['color'] && ! empty( $value['color'] ) ) {
			$output[] = $css_rules['color'] . ': ' . $value['color'] . ';' . "\n";
		}
		if ( $css_rules['image'] ) {
			if ( ! empty( $value['url'] ) ) {
				if ( ! empty( $value['id'] ) && ! empty( $value['image_size'] ) ) {
					if ( 'custom' === $value['image_size'] ) {
						$image_size_width  = ! empty( $value['image_size_custom_width'] ) ? (int) $value['image_size_custom_width'] : 0;
						$image_size_height = ! empty( $value['image_size_custom_height'] ) ? (int) $value['image_size_custom_height'] : 0;

						if ( $image_size_width || $image_size_height ) {
							$image_size = array( $image_size_width, $image_size_height );
						} else {
							$image_size = 'full';
						}
					} else {
						$image_size = $value['image_size'];
					}

					$output[] = $css_rules['image'] . ': url(' . woodmart_otf_get_image_url( $value['id'], $image_size ) . ');' . "\n";
				} else {
					$output[] = $css_rules['image'] . ': url(' . $value['url'] . ');' . "\n";
				}
			} else {
				$output[] = $css_rules['image'] . ': none;' . "\n";
			}
		}
		if ( $css_rules['repeat'] && ! empty( $value['repeat'] ) ) {
			$output[] = $css_rules['repeat'] . ': ' . $value['repeat'] . ';' . "\n";
		}
		if ( $css_rules['size'] && ! empty( $value['size'] ) ) {
			$output[] = $css_rules['size'] . ': ' . $value['size'] . ';' . "\n";
		}
		if ( $css_rules['attachment'] && ! empty( $value['attachment'] ) ) {
			$output[] = $css_rules['attachment'] . ': ' . $value['attachment'] . ';' . "\n";
		}
		if ( $css_rules['position'] && ! empty( $value['position'] ) ) {
			$output[] = $css_rules['position'] . ': ' . $value['position'] . ';' . "\n";
		}

		return array(
			$device => array(
				$this->args['selector'] => $output,
			),
		);
	}

	/**
	 * Check value URl and ID fields.
	 *
	 * @since 1.0.0
	 *
	 * @param  string or array $value Field value.
	 */
	public function validate( $value ) {
		if ( isset( $value['id'] ) ) {
			$attachment = wp_get_attachment_url( $value['id'] );

			if ( $attachment ) {
				$value['url'] = $attachment;
			}
		}

		return $value;
	}
}


