<?php
namespace XTS\Modules\Floating_Blocks;

use XTS\Gutenberg\Blocks_Assets;
use XTS\Gutenberg\Post_CSS;
use XTS\Singleton;
use XTS\Admin\Modules\Options\Metaboxes;
use XTS\Modules\Styles_Storage;
/**
 * Frontend floating blocks class file.
 *
 * @package Woodmart
 */
class Frontend extends Singleton {
	/**
	 * Manager instance.
	 *
	 * @var Manager instanse.
	 */
	public $manager;

	/**
	 * Block types.
	 *
	 * @var array
	 */
	private $block_types;

	/**
	 * Constructor.
	 */
	public function init() {
		$this->manager     = Manager::get_instance();
		$this->block_types = woodmart_get_config( 'fb-types' );

		add_action( 'wp_body_open', array( $this, 'render_all_floating_blocks' ), 50 );
		add_action( 'woodmart_before_wp_footer', array( $this, 'render_all_popups' ) );
	}

	/**
	 * Get floating block option value.
	 *
	 * @param int    $floating_id Floating block ID.
	 * @param string $option_name Option name.
	 * @return mixed
	 */
	private function get_floating_option( $floating_id, $option_name ) {
		$active_builder = $this->manager->get_active_editor( $floating_id );
		$prefix         = 'wd_fb_';
		$value          = '';

		if ( 'wpb' === $active_builder ) {
			$value = get_post_meta( $floating_id, $option_name, true );
		} elseif ( 'elementor' === $active_builder ) {
			$doc               = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $floating_id );
			$elementor_options = $doc ? $doc->get_settings_for_display() : array();

			if ( isset( $elementor_options[ $prefix . $option_name ] ) ) {
				$value = $elementor_options[ $prefix . $option_name ];
			}
		} else {
			$value = $this->manager->get_gutenberg_option( $floating_id, $option_name );
		}

		return $value;
	}

	/**
	 * Get legacy popup triggers.
	 *
	 * @return string
	 */
	private function get_legacy_popup_triggers() {
		$triggers = array();

		if ( 'time' === woodmart_get_opt( 'popup_event', true ) ) {
			$triggers['time_to_show'] = array(
				'value'     => ( woodmart_get_opt( 'promo_timeout' ) ) ? (int) woodmart_get_opt( 'promo_timeout' ) : 1000,
				'show_once' => '1',
			);
		}

		if ( 'scroll' === woodmart_get_opt( 'popup_event', true ) ) {
			$triggers['scroll_value'] = array(
				'value'     => ( woodmart_get_opt( 'popup_scroll' ) ) ? (int) woodmart_get_opt( 'popup_scroll' ) : 1000,
				'show_once' => '1',
			);
		}

		$triggers['selector'] = array(
			'value'     => '.woodmart-open-newsletter',
			'show_once' => '0',
		);

		if ( woodmart_get_opt( 'popup_pages', true ) ) {
			$triggers['popup_pages'] = woodmart_get_opt( 'popup_pages' ) ? (string) woodmart_get_opt( 'popup_pages' ) : '1';
		}

		return wp_json_encode( $triggers );
	}

	/**
	 * Get legacy popup options.
	 *
	 * @return string
	 */
	private function get_legacy_popup_options() {
		$options = array(
			'version'           => woodmart_get_opt( 'promo_version' ),
			'hide_popup_mobile' => woodmart_get_opt( 'promo_popup_hide_mobile' ),
			'animation'         => 'default',
			'close_btn_display' => 'icon',
			'close_by_overlay'  => '1',
			'close_by_esc'      => '1',
			'close_btn'         => '1',
			'persistent_close'  => '0',
		);

		return wp_json_encode( $options );
	}

	/**
	 * Check if blocks should be shown.
	 *
	 * @return bool
	 */
	private function blocks_show_conditions() {
		if ( is_admin() || in_array( get_post_type(), array( 'woodmart_slide', 'cms_block', 'wd_product_tabs', 'wd_floating_block', 'wd_popup', 'woodmart_layout' ), true ) ) {
			return false;
		}

		if ( woodmart_is_elementor_installed() && ( woodmart_elementor_is_edit_mode() || woodmart_elementor_is_preview_page() || woodmart_elementor_is_preview_mode() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Renders the popups on the frontend.
	 */
	public function render_all_popups() {
		if ( ! $this->blocks_show_conditions() ) {
			return;
		}

		$ids = $this->manager->get_current_ids( 'wd_popup' );

		if ( empty( $ids ) ) {
			return;
		}

		woodmart_enqueue_inline_style( 'mod-animations-transform' );
		woodmart_enqueue_inline_style( 'mod-transform' );

		foreach ( $ids as $block_id ) {
			$this->render_popup( $block_id );
		}
	}

	/**
	 * Renders the floating blocks on the frontend.
	 */
	public function render_all_floating_blocks() {
		if ( ! $this->blocks_show_conditions() ) {
			return;
		}

		$ids = $this->manager->get_current_ids( 'wd_floating_block' );

		if ( empty( $ids ) ) {
			return;
		}

		woodmart_enqueue_inline_style( 'mod-animations-transform' );
		woodmart_enqueue_inline_style( 'mod-transform' );

		foreach ( $ids as $block_id ) {
			$this->render_floating_block( $block_id );
		}
	}

	/**
	 * Renders the floating block on the frontend.
	 *
	 * @param int $floating_id The ID of the floating block post to render.
	 */
	private function render_floating_block( $floating_id ) {
		$wrapper_classes             = '';
		$content_classes             = '';
		$close_btn_classes           = '';
		$triggers                    = '';
		$positioning_area            = $this->get_floating_option( $floating_id, 'positioning_area' );
		$animation                   = $this->get_floating_option( $floating_id, 'animation' );
		$hide_on_desktop             = $this->get_floating_option( $floating_id, 'hide_floating_block' );
		$hide_on_tablet              = $this->get_floating_option( $floating_id, 'hide_floating_block_tablet' );
		$hide_on_mobile              = $this->get_floating_option( $floating_id, 'hide_floating_block_mobile' );
		$close_btn                   = $this->get_floating_option( $floating_id, 'close_btn' );
		$close_btn_display           = $this->get_floating_option( $floating_id, 'close_btn_display' );
		$bg_image                    = $this->get_floating_option( $floating_id, 'background_image' );
		$bg_image_size               = $this->get_floating_option( $floating_id, 'image_size' );
		$bg_image_size_custom_width  = $this->get_floating_option( $floating_id, 'image_size_custom_width' );
		$bg_image_size_custom_height = $this->get_floating_option( $floating_id, 'image_size_custom_height' );
		$bg_image_guten              = $this->get_floating_option( $floating_id, 'backgroundImage' );
		$persistent_close            = $this->get_floating_option( $floating_id, 'persistent_close' );

		if ( $hide_on_desktop ) {
			$wrapper_classes .= ' wd-hide-lg';
		}

		if ( $hide_on_tablet ) {
			$wrapper_classes .= ' wd-hide-md-sm';
		}

		if ( $hide_on_mobile ) {
			$wrapper_classes .= ' wd-hide-sm';
		}

		if ( 'container' === $positioning_area ) {
			$wrapper_classes .= ' container';
		}

		if ( $animation ) {
			$content_classes .= ' wd-animation wd-transform wd-animation-ready wd-animated';
			$content_classes .= ' wd-animation-' . $animation;

			if ( in_array( $animation, array( 'snap-in-top', 'snap-in-bottom', 'snap-in-left', 'snap-in-right' ), true ) ) {
				woodmart_enqueue_inline_style( 'mod-animations-transform-snap' );
			} else {
				woodmart_enqueue_inline_style( 'mod-animations-transform-base' );
			}
		}

		$close_btn_classes .= ' wd-style-' . ( 'text' === $close_btn_display ? 'text' : 'icon' );

		if ( ! $close_btn ) {
			$close_btn_classes .= ' wd-hide';
		}

		$this->get_css_for_floating_block( $floating_id );
		$options      = $this->get_options_json( $floating_id );
		$display_type = $this->get_floating_option( $floating_id, 'display_type' );
		$data_attrs   = '';

		if ( $persistent_close || 'triggers' === $display_type ) {
			$content_classes .= ' wd-hide';
		}

		if ( 'triggers' === $display_type ) {
			$triggers    = $this->get_triggers_json( $floating_id );
			$data_attrs .= ' data-triggers="' . esc_attr( $triggers ) . '"';
		}

		if ( $options ) {
			$data_attrs .= ' data-options="' . esc_attr( $options ) . '"';
		}

		if ( $animation || $display_type || $options ) {
			woodmart_enqueue_js_script( 'floating-blocks' );
		}

		?>
			<div id="<?php echo esc_attr( 'wd-fb-' . $floating_id ); ?>" class="wd-fb-holder wd-scroll<?php echo esc_attr( $wrapper_classes ); ?>"<?php echo wp_kses( $data_attrs, true ); ?> role="complementary" aria-label="<?php esc_attr_e( 'Floating block', 'woodmart' ); ?>">
				<div class="wd-fb-wrap<?php echo esc_attr( $content_classes ); ?>">
					<div class="wd-fb-close wd-action-btn wd-cross-icon<?php echo esc_attr( $close_btn_classes ); ?>">
						<a title="<?php esc_html_e( 'Close', 'woodmart' ); ?>" href="#" rel="nofollow">
							<span><?php esc_html_e( 'Close', 'woodmart' ); ?></span>
						</a>
					</div>
					<div class="wd-fb">
						<?php if ( ! empty( $bg_image['id'] ) || ! empty( $bg_image_guten['id'] ) ) : ?>
							<div class="wd-fb-bg wd-fill">
								<?php
								if ( ! empty( $bg_image['id'] ) ) {
									if ( $bg_image_size ) {
										$bg_image['size'] = $bg_image_size;
									}

									if ( 'custom' === $bg_image_size && ( $bg_image_size_custom_width || $bg_image_size_custom_height ) ) {
										$bg_image['size'] = array( (int) $bg_image_size_custom_width, (int) $bg_image_size_custom_height );
									}

									$image_size = isset( $bg_image['size'] ) ? $bg_image['size'] : 'full';

									echo woodmart_otf_get_image_html(
										$bg_image['id'],
										$image_size,
										false
									);
								} elseif ( ! empty( $bg_image_guten['id'] ) ) {
									$bg_image_size = $this->get_floating_option( $floating_id, 'backgroundImageSize' );
									$image_size    = $bg_image_size ? $bg_image_size : 'full';

									echo woodmart_otf_get_image_html(
										$bg_image_guten['id'],
										$image_size,
										false
									);
								}
								?>
							</div>
						<?php endif; ?>
						<div class="wd-fb-inner wd-scroll-content wd-entry-content">
							<?php echo $this->get_content( $floating_id, true ); // phpcs:ignore. ?>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * Renders the popup on the frontend.
	 *
	 * @param int $popup_id The ID of the popup post to render.
	 */
	private function render_popup( $popup_id ) {
		$options   = $this->get_options_json( $popup_id );
		$triggers  = $this->get_triggers_json( $popup_id );
		$animation = '';

		if ( 'legacy' === $popup_id ) {
			$options  = $this->get_legacy_popup_options();
			$triggers = $this->get_legacy_popup_triggers();
		}

		$active_builder = $this->manager->get_active_editor( $popup_id );

		if ( 'wpb' === $active_builder ) {
			$animation = get_post_meta( $popup_id, 'animation', true );
		} elseif ( 'elementor' === $active_builder ) {
			$prefix            = 'wd_popup_';
			$doc               = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $popup_id );
			$elementor_options = $doc ? $doc->get_settings_for_display() : array();
			$animation         = isset( $elementor_options[ $prefix . 'animation' ] ) ? $elementor_options[ $prefix . 'animation' ] : 'default';
		} else {
			$gutenberg_animation = $this->manager->get_gutenberg_option( $popup_id, 'animation' );

			if ( $gutenberg_animation ) {
				$animation = $gutenberg_animation;
			}
		}

		$classes = '';

		if ( ! $animation || 'legacy' === $popup_id ) {
			$animation = 'default';
		}

		woodmart_enqueue_js_library( 'magnific' );
		woodmart_enqueue_js_script( 'floating-blocks' );
		woodmart_enqueue_inline_style( 'mfp-popup' );

		if ( 'legacy' === $popup_id ) {
			if ( ! woodmart_get_opt( 'promo_popup' ) ) {
				return;
			}

			woodmart_enqueue_inline_style( 'promo-popup' );

			$classes .= ' wd-promo-popup' . woodmart_get_old_classes( ' woodmart-promo-popup' );

			if ( 'dark' !== woodmart_get_opt( 'popup_color_scheme', 'dark' ) ) {
				$classes .= ' color-scheme-' . woodmart_get_opt( 'popup_color_scheme' );
			}
		} else {
			woodmart_enqueue_inline_style( 'opt-popup-builder' );

			$this->get_css_for_popup( $popup_id );

			$classes .= 'wd-popup-builder';
		}

		$classes .= ' wd-popup wd-scroll-content';

		if ( in_array( $animation, array( 'snap-in-top', 'snap-in-bottom', 'snap-in-left', 'snap-in-right' ), true ) ) {
			woodmart_enqueue_inline_style( 'mod-animations-transform-snap' );
		} elseif ( 'default' !== $animation ) {
			woodmart_enqueue_inline_style( 'mod-animations-transform-base' );
		}
		?>
		<div id="<?php echo esc_attr( 'popup-' . $popup_id ); ?>" class="<?php echo esc_attr( $classes ); ?>" data-options="<?php echo esc_attr( $options ); ?>" data-triggers="<?php echo esc_attr( $triggers ); ?>" role="complementary" aria-label="<?php esc_attr_e( 'Popup', 'woodmart' ); ?>">
			<div class="wd-popup-inner wd-entry-content">
				<?php echo $this->get_content( $popup_id, true ); // phpcs:ignore. ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Outputs the CSS for a given popup.
	 *
	 * @param int $popup_id The ID of the popup post to retrieve CSS for.
	 */
	public function get_css_for_popup( $popup_id ) {
		$active_builder = $this->manager->get_active_editor( $popup_id );

		if ( 'wpb' !== $active_builder ) {
			return;
		}

		$storage = new Styles_Storage( 'popup-' . $popup_id, 'post_meta', $popup_id );

		if ( ! $storage->is_css_exists() ) {
			$css = Metaboxes::get_instance()->get_metabox_css( $popup_id, 'wd_popup_metaboxes' );

			if ( $css ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
				}

				$storage->reset_data();
				$storage->write( $css );
			}
		}

		$storage->inline_css();
	}

	/**
	 * Outputs the CSS for a given floating block.
	 *
	 * @param int $floating_id The ID of the floating block post to retrieve CSS for.
	 */
	public function get_css_for_floating_block( $floating_id ) {
		woodmart_enqueue_inline_style( 'opt-floating-block' );

		$active_builder = $this->manager->get_active_editor( $floating_id );

		if ( 'wpb' !== $active_builder ) {
			return;
		}

		$storage = new Styles_Storage( 'floating-block-' . $floating_id, 'post_meta', $floating_id );

		if ( ! $storage->is_css_exists() ) {
			$css = Metaboxes::get_instance()->get_metabox_css( $floating_id, 'wd_floating_block_metaboxes' );

			if ( $css ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once ABSPATH . '/wp-admin/includes/file.php';
				}

				// Object fit.
				$object_fit   = get_post_meta( $floating_id, 'image_object_fit', true );
				$object_fit_t = get_post_meta( $floating_id, 'image_object_fit_tablet', true );
				$object_fit_m = get_post_meta( $floating_id, 'image_object_fit_mobile', true );

				// Image position.
				$image_position   = get_post_meta( $floating_id, 'image_object_position', true );
				$image_position_t = get_post_meta( $floating_id, 'image_object_position_tablet', true );
				$image_position_m = get_post_meta( $floating_id, 'image_object_position_mobile', true );

				$custom_image_pos_x   = get_post_meta( $floating_id, 'image_object_position_x', true );
				$custom_image_pos_x_t = get_post_meta( $floating_id, 'image_object_position_x_tablet', true );
				$custom_image_pos_x_m = get_post_meta( $floating_id, 'image_object_position_x_mobile', true );

				$custom_image_pos_y   = get_post_meta( $floating_id, 'image_object_position_y', true );
				$custom_image_pos_y_t = get_post_meta( $floating_id, 'image_object_position_y_tablet', true );
				$custom_image_pos_y_m = get_post_meta( $floating_id, 'image_object_position_y_mobile', true );

				ob_start();

				if ( $object_fit || $image_position ) :
					?>
				#wd-fb-<?php echo esc_attr( $floating_id ); ?> .wd-fb-bg img {
					<?php if ( $object_fit ) : ?>
						<?php woodmart_maybe_set_css_rule( 'object-fit', $object_fit ); ?>
					<?php endif; ?>
	
					<?php if ( 'custom' === $image_position ) : ?>
						<?php woodmart_maybe_set_css_rule( 'object-position', ( (int) $custom_image_pos_x ) . 'px ' . ( (int) $custom_image_pos_y ) . 'px' ); ?>
					<?php elseif ( $image_position ) : ?>
						<?php woodmart_maybe_set_css_rule( 'object-position', $image_position ); ?>
					<?php endif; ?>
				}
					<?php
				endif;

				if ( $object_fit_t || $image_position_t ) :
					?>
				@media (min-width: 768px) and (max-width: 1024px) {
					#wd-fb-<?php echo esc_attr( $floating_id ); ?> .wd-fb-bg img {
						<?php if ( $object_fit_t ) : ?>
							<?php woodmart_maybe_set_css_rule( 'object-fit', $object_fit_t ); ?>
						<?php endif; ?>
		
						<?php if ( 'custom' === $image_position_t ) : ?>
							<?php woodmart_maybe_set_css_rule( 'object-position', ( (int) $custom_image_pos_x_t ) . 'px ' . ( (int) $custom_image_pos_y_t ) . 'px' ); ?>
						<?php elseif ( $image_position_t ) : ?>
							<?php woodmart_maybe_set_css_rule( 'object-position', $image_position_t ); ?>
						<?php endif; ?>
					}
				}
					<?php
				endif;

				if ( $object_fit_m || $image_position_m ) :
					?>
				@media (max-width: 767px) {
					#wd-fb-<?php echo esc_attr( $floating_id ); ?> .wd-fb-bg img {
						<?php if ( $object_fit_m ) : ?>
							<?php woodmart_maybe_set_css_rule( 'object-fit', $object_fit_m ); ?>
						<?php endif; ?>
		
						<?php if ( 'custom' === $image_position_m ) : ?>
							<?php woodmart_maybe_set_css_rule( 'object-position', ( (int) $custom_image_pos_x_m ) . 'px ' . ( (int) $custom_image_pos_y_m ) . 'px' ); ?>
						<?php elseif ( $image_position_m ) : ?>
							<?php woodmart_maybe_set_css_rule( 'object-position', $image_position_m ); ?>
						<?php endif; ?>
					}
				}
					<?php
				endif;
				$css .= ob_get_clean();
				$storage->reset_data();
				$storage->write( $css );
			}
		}

		$storage->print_styles_inline();
	}


	/**
	 * Retrieves the options configuration for a given floating block.
	 *
	 * @param int $block_id The ID of the floating block post to retrieve triggers for.
	 *
	 * @return array The configurations.
	 */
	public function get_options_json( $block_id ) {
		$post_type = get_post_type( $block_id );
		$block_key = $this->manager->get_block_key_by_post_type( $post_type );

		if ( ! $block_key || ! isset( $this->block_types[ $block_key ] ) ) {
			return false;
		}

		$block_type = $this->block_types[ $block_key ];
		$options    = $block_type['options'];
		$prefix     = $block_type['prefix'];

		$elementor_options = array();

		$active_builder = $this->manager->get_active_editor( $block_id );

		if ( 'elementor' === $active_builder ) {
			$doc               = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $block_id );
			$elementor_options = $doc ? $doc->get_settings_for_display() : array();
		}

		$data = array();

		foreach ( $options as $option ) {
			$value = '';

			if ( 'wpb' === $active_builder ) {
				$value = get_post_meta( $block_id, $option, true );
			} elseif ( 'elementor' === $active_builder && isset( $elementor_options[ $prefix . $option ] ) ) {
				$value = $elementor_options[ $prefix . $option ];
			} else {
				$gutenberg_value = $this->manager->get_gutenberg_option( $block_id, $option );

				if ( $gutenberg_value ) {
					$value = $gutenberg_value;
				}
			}

			if ( is_bool( $value ) ) {
				$value = $value ? '1' : '0';
			}

			if ( 'popup' === $block_key ) {
				if ( 'animation' === $option && ! $value ) {
					$value = 'default';
				}

				if ( 'close_btn_display' === $option && ! $value ) {
					$value = 'icon';
				}
			}

			if ( '' !== $value ) {
				$data[ $option ] = $value;
			}
		}

		if ( empty( $data ) ) {
			return false;
		}

		return wp_json_encode( $data );
	}

	/**
	 * Retrieves the enabled triggers configuration for a given floating block.
	 *
	 * @param int $block_id The ID of the floating block post to retrieve triggers for.
	 *
	 * @return array The configuration of enabled triggers.
	 */
	public function get_triggers_json( $block_id ) {
		$post_type = get_post_type( $block_id );
		$block_key = $this->manager->get_block_key_by_post_type( $post_type );

		if ( ! $block_key || ! isset( $this->block_types[ $block_key ] ) ) {
			return false;
		}

		$block_type = $this->block_types[ $block_key ];
		$prefix     = $block_type['prefix'];

		$data = array();

		$triggers = array(
			'time_to_show'       => array(
				'enabled'   => 'is_some_time_enabled',
				'show_once' => 'time_to_show_once',
			),
			'scroll_value'       => array(
				'enabled'   => 'is_after_scroll_enabled',
				'show_once' => 'after_scroll_once',
			),
			'scroll_to_selector' => array(
				'enabled'   => 'is_scroll_to_selector_enabled',
				'show_once' => 'scroll_to_selector_once',
			),
			'inactivity_time'    => array(
				'enabled'   => 'is_inactivity_time_enabled',
				'show_once' => 'inactivity_time_once',
			),
			'click_times'        => array(
				'enabled'   => 'is_on_click_enabled',
				'show_once' => 'click_times_once',
			),
			'selector'           => array(
				'enabled'   => 'is_on_selector_click_enabled',
				'show_once' => 'selector_click_once',
			),
			'parameters'         => array(
				'enabled'   => 'is_url_parameter_enabled',
				'show_once' => 'url_parameter_once',
			),
			'hashtags'           => array(
				'enabled'   => 'is_url_hashtag_enabled',
				'show_once' => 'url_hashtag_once',
			),
			'after_page_views'   => array(
				'enabled'   => 'is_after_page_views_enabled',
				'show_once' => 'after_page_views_once',
			),
			'after_sessions'     => array(
				'enabled'   => 'is_after_sessions_enabled',
				'show_once' => 'after_sessions_once',
			),
		);

		$active_builder    = $this->manager->get_active_editor( $block_id );
		$elementor_options = array();

		if ( 'elementor' === $active_builder ) {
			$doc               = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $block_id );
			$elementor_options = $doc ? $doc->get_settings_for_display() : array();
		}

		foreach ( $triggers as $trigger => $config ) {
			$value     = '';
			$show_once = false;
			$enabled   = false;

			if ( 'wpb' === $active_builder ) {
				$enabled = get_post_meta( $block_id, $config['enabled'], true );
				if ( $enabled ) {
					$value     = get_post_meta( $block_id, $trigger, true );
					$show_once = get_post_meta( $block_id, $config['show_once'], true );

					if ( 'scroll_value' === $trigger && $value ) {
							$decoded = json_decode( woodmart_decompress( $value ), true );
						if ( ! empty( $decoded['devices']['desktop']['value'] ) && ! empty( $decoded['devices']['desktop']['unit'] ) ) {
							$value = $decoded['devices']['desktop']['value'] . $decoded['devices']['desktop']['unit'];
						}
					}
				}
			} elseif ( 'elementor' === $active_builder ) {
				$enabled = ! empty( $elementor_options[ $prefix . $config['enabled'] ] );
				if ( $enabled && isset( $elementor_options[ $prefix . $trigger ] ) ) {
					$value     = $elementor_options[ $prefix . $trigger ];
					$show_once = $elementor_options[ $prefix . $config['show_once'] ] ?? false;

					if ( 'scroll_value' === $trigger && is_array( $value ) ) {
						$value = ( isset( $value['size'] ) ? $value['size'] : '' ) . ( isset( $value['unit'] ) ? $value['unit'] : '' );
					}
				}
			} else {
				$enabled = $this->manager->get_gutenberg_option( $block_id, $config['enabled'] );
				if ( $enabled ) {
					$value     = $this->manager->get_gutenberg_option( $block_id, $trigger );
					$show_once = $this->manager->get_gutenberg_option( $block_id, $config['show_once'] );

					if ( 'scroll_value' === $trigger ) {
						$unit = $this->manager->get_gutenberg_option( $block_id, 'scroll_valueUnits' );

						if ( ! $unit ) {
							continue;
						}

						$value = $value . $unit;
					}
				}
			}

			if ( $enabled && '' !== $value ) {
				$data[ $trigger ] = array(
					'value'     => (string) $value,
					'show_once' => $show_once ? '1' : '0',
				);
			}
		}

		$exit_intent_enabled = false;
		$exit_intent_once    = false;

		if ( 'wpb' === $active_builder ) {
			$exit_intent_enabled = get_post_meta( $block_id, 'is_exit_intent_enabled', true );
			$exit_intent_once    = get_post_meta( $block_id, 'exit_intent_once', true );
		} elseif ( 'elementor' === $active_builder ) {
			$exit_intent_enabled = ! empty( $elementor_options[ $prefix . 'is_exit_intent_enabled' ] );
			$exit_intent_once    = $elementor_options[ $prefix . 'exit_intent_once' ] ?? false;
		} else {
			$exit_intent_enabled = $this->manager->get_gutenberg_option( $block_id, 'is_exit_intent_enabled' );
			$exit_intent_once    = $this->manager->get_gutenberg_option( $block_id, 'exit_intent_once' );
		}

		if ( $exit_intent_enabled ) {
			$data['exit_intent'] = array(
				'value'     => true,
				'show_once' => $exit_intent_once ? '1' : '0',
			);
		}

		return wp_json_encode( $data );
	}

	/**
	 * Retrieves the content of a floating block by its ID.
	 *
	 * @param int  $id         The ID of the floating block post to retrieve.
	 * @param bool $inline_css Optional. Whether to include inline CSS in the content. Default false.
	 *
	 * @return string The content of the floating block.
	 */
	public function get_content( $id, $inline_css = false ) {
		$post = get_post( $id );

		if ( 'legacy' === $id ) {
			if ( 'text' === woodmart_get_opt( 'promo_popup_content_type', 'text' ) ) {
				return do_shortcode( woodmart_get_opt( 'popup_text' ) );
			} else {
				return woodmart_get_html_block( woodmart_get_opt( 'popup_html_block' ), true );
			}
		}

		if ( ! $post || ! $id ) {
			return '';
		}

		if ( ! $this->manager->get_block_key_by_post_type( $post->post_type ) ) {
			return '';
		}

		$id = apply_filters( 'wpml_object_id', $id, $post->post_type, true );

		if ( 'gutenberg' === $this->manager->get_active_editor( $id ) && ! $post->post_content && woodmart_get_opt( 'gutenberg_blocks' ) ) {
			$content  = Blocks_Assets::get_instance()->get_inline_scripts( $id );
			$content .= Post_CSS::get_instance()->get_inline_blocks_css( $id, $inline_css );

			return $content;
		}

		$content = woodmart_get_post_content( $id, $inline_css );

		if ( ! $content && 'elementor' === $this->manager->get_active_editor( $id ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $id );
			$css_file->enqueue();
		}

		return $content;
	}
}

Frontend::get_instance();
