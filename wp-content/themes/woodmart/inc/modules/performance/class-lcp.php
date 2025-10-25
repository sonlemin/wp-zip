<?php
/**
 * The LCP (Largest Contentful Paint) module for Woodmart theme.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Performance;

use XTS\Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * The LCP module class.
 */
class LCP extends Singleton {

	/**
	 * Desktop image src.
	 *
	 * @var null
	 */
	private $desktop_image_src = null;

	/**
	 * Mobile image src.
	 *
	 * @var null
	 */
	private $mobile_image_src = null;

	/**
	 * Register hooks.
	 */
	public function init() {
		add_filter( 'wp_head', array( $this, 'get_preload_image' ), 5 );

		add_action( 'wp_ajax_woodmart_update_lcp_image', array( $this, 'ajax_update_lcp_image' ) );
		add_action( 'wp', array( $this, 'remove_lcp_image' ) );

		add_action( 'admin_bar_menu', array( $this, 'add_lcp_button_to_admin_bar' ), 100 );
		add_action( 'woodmart_before_wp_footer', array( $this, 'popup_template' ), 300 );

		add_filter( 'wp_min_priority_img_pixels', array( $this, 'disable_default_fetchpriority' ) );

		add_action( 'woodmart_localized_string_array', array( $this, 'add_localized_settings' ) );

		add_action( 'woodmart_exclude_lazyload_urls', array( $this, 'exclude_preload_image' ) );
		add_filter( 'wp_get_loading_optimization_attributes', array( $this, 'exclude_preload_image_in_lazy_load' ), 10, 3 );
		add_filter( 'woodmart_enable_lazy_loading', array( $this, 'disable_lazy_loading' ) );
	}

	/**
	 * Check if we are in capture mode.
	 *
	 * @return bool
	 */
	protected function is_capture_mode() {
		return woodmart_get_opt( 'preload_lcp_image' ) && is_user_logged_in() && isset( $_GET['wd_capture_lcp'] ) && current_user_can( 'manage_options' ) && wp_verify_nonce( $_GET['security'], 'wd_preload_image' ); // phpcs:ignore
	}

	/**
	 * Disable lazy loading when in capture mode.
	 *
	 * @param boolean $enable Whether to enable lazy loading.
	 * @return false
	 */
	public function disable_lazy_loading( $enable ) {
		return $this->is_capture_mode() ? false : $enable;
	}

	/**
	 * Get preload image for LCP.
	 *
	 * @return void
	 */
	public function get_preload_image() {
		if ( ! woodmart_get_opt( 'preload_lcp_image' ) || $this->is_capture_mode() ) {
			return;
		}

		$post_id = get_the_ID();

		$desktop_image = get_post_meta( $post_id, '_woodmart_preload_image', true );
		$mobile_image  = get_post_meta( $post_id, '_woodmart_preload_image_mobile', true );

		if ( empty( $desktop_image['id'] ) && empty( $mobile_image['id'] ) ) {
			return;
		}

		$desktop_image_size = get_post_meta( $post_id, '_woodmart_preload_image_size', true );
		$desktop_image_size = $desktop_image_size ? $desktop_image_size : 'full';

		if ( 'custom' === $desktop_image_size ) {
			$image_width  = (int) get_post_meta( $post_id, '_woodmart_preload_image_custom_width', true );
			$image_height = (int) get_post_meta( $post_id, '_woodmart_preload_image_custom_height', true );

			if ( $image_width || $image_height ) {
				$desktop_image_size = $image_width . 'x' . $image_height;
			} else {
				$desktop_image_size = 'full';
			}
		}

		$desktop_image_type = get_post_meta( $post_id, '_woodmart_preload_image_type', true );

		$mobile_image_size = get_post_meta( $post_id, '_woodmart_preload_image_mobile_size', true );
		$mobile_image_size = $mobile_image_size ? $mobile_image_size : 'full';
		$mobile_image_type = get_post_meta( $post_id, '_woodmart_preload_image_mobile_type', true );

		if ( 'custom' === $mobile_image_size ) {
			$image_width  = (int) get_post_meta( $post_id, '_woodmart_preload_image_mobile_custom_width', true );
			$image_height = (int) get_post_meta( $post_id, '_woodmart_preload_image_mobile_custom_height', true );

			if ( $image_width || $image_height ) {
				$mobile_image_size = $image_width . 'x' . $image_height;
			} else {
				$mobile_image_size = 'full';
			}
		}

		$same_image = (
			! empty( $desktop_image['id'] ) &&
			! empty( $mobile_image['id'] ) &&
			$desktop_image['id'] === $mobile_image['id'] &&
			$desktop_image_size === $mobile_image_size &&
			$desktop_image_type === $mobile_image_type
		);

		if ( ! empty( $desktop_image['id'] ) ) {
			$src = woodmart_otf_get_image_url( $desktop_image['id'], $desktop_image_size );

			if ( $src ) {
				echo '<link rel="preload" as="image" href="' . esc_url( $src ) . '"';

				if ( ! $same_image && ! empty( $mobile_image['id'] ) ) {
					echo ' media="(min-width: 769px)"';
				}

				if ( 'image' === $desktop_image_type ) {
					$srcset = wp_get_attachment_image_srcset( $desktop_image['id'], $desktop_image_size );
					$sizes  = wp_get_attachment_image_sizes( $desktop_image['id'], $desktop_image_size );

					if ( $srcset ) {
						echo ' imagesrcset="' . esc_attr( $srcset ) . '"';
					}
					if ( $sizes ) {
						echo ' imagesizes="' . esc_attr( $sizes ) . '"';
					}
				}

				echo ' fetchpriority="high" />' . "\n";
			}
		}

		if ( $same_image ) {
			return;
		}

		if ( ! empty( $mobile_image['id'] ) ) {
			$src = woodmart_otf_get_image_url( $mobile_image['id'], $mobile_image_size );

			if ( $src ) {
				echo '<link rel="preload" as="image" href="' . esc_url( $src ) . '" media="(max-width: 768px)"';

				if ( 'image' === $mobile_image_type ) {
					$srcset = wp_get_attachment_image_srcset( $mobile_image['id'], $mobile_image_size );
					$sizes  = wp_get_attachment_image_sizes( $mobile_image['id'], $mobile_image_size );

					if ( $srcset ) {
						echo ' imagesrcset="' . esc_attr( $srcset ) . '"';
					}
					if ( $sizes ) {
						echo ' imagesizes="' . esc_attr( $sizes ) . '"';
					}
				}

				echo ' fetchpriority="high" />' . "\n";
			}
		}
	}

	/**
	 * Remove image from LCP.
	 *
	 * @return void
	 */
	public function remove_lcp_image() {
		if ( ! isset( $_GET['wd_remove_lcp'] ) || ! woodmart_get_opt( 'preload_lcp_image' ) || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['security'], 'wd_remove_preload_image' ) ) { // phpcs:ignore
			return;
		}

		$post_id = get_the_ID();
		$device  = isset( $_GET['device'] ) && 'mobile' === $_GET['device'] ? 'mobile' : 'desktop';

		delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile' : '_woodmart_preload_image' );
		delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_size' : '_woodmart_preload_image_size' );
		delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_custom_width' : '_woodmart_preload_image_custom_width' );
		delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_custom_height' : '_woodmart_preload_image_custom_height' );
		delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_type' : '_woodmart_preload_image_type' );
	}

	/**
	 * Update LCP image via AJAX.
	 *
	 * @return void
	 */
	public function ajax_update_lcp_image() {
		check_ajax_referer( 'wd_preload_image', 'security' );

		if ( ! woodmart_get_opt( 'preload_lcp_image' ) || ! isset( $_GET['post_id'] ) || ! isset( $_GET['image_url'] ) ) { // phpcs:ignore
			return;
		}

		$post_id          = intval( $_GET['post_id'] ); // phpcs:ignore
		$image_url        = esc_url_raw( $_GET['image_url'] ); // phpcs:ignore
		$image_type       = isset( $_GET['image_type'] ) ? sanitize_text_field( $_GET['image_type'] ) : 'image'; // phpcs:ignore
		$device           = isset( $_GET['device'] ) ? sanitize_text_field( $_GET['device'] ) : 'desktop'; // phpcs:ignore
		$upload_dir_paths = wp_upload_dir();
		$allowed_sizes    = array_keys( woodmart_get_default_image_sizes() );

		if ( $image_url ) {
			$relative_path = str_replace( $upload_dir_paths['baseurl'] . '/', '', $image_url );

			$size        = 'full';
			$custom_size = '';

			if ( preg_match( '/-(\d+)x(\d+)(?=\.\w{3,4}$)/', $relative_path, $matches ) ) {
				$size          = (int) $matches[1] . 'x' . (int) $matches[2];
				$custom_size   = $size;
				$relative_path = preg_replace( '/-\d+x\d+(?=\.\w{3,4}$)/', '', $relative_path );
			}

			global $wpdb;
			$attachment_id = $wpdb->get_var(
				$wpdb->prepare(
					"
					SELECT ID FROM $wpdb->posts
					WHERE post_type = 'attachment'
					AND guid LIKE %s
					LIMIT 1
				",
					'%' . $relative_path
				)
			);

			update_post_meta(
				$post_id,
				'mobile' === $device ? '_woodmart_preload_image_mobile' : '_woodmart_preload_image',
				array(
					'id'  => $attachment_id,
					'url' => $image_url,
				)
			);

			if ( $custom_size && $attachment_id ) {
				$attachment_meta = wp_get_attachment_metadata( $attachment_id );

				if ( ! empty( $attachment_meta['sizes'] ) ) {
					foreach ( $attachment_meta['sizes'] as $size_name => $size_data ) {
						if ( (int) $size_data['width'] === (int) $matches[1] && (int) $size_data['height'] === (int) $matches[2] && in_array( $size_name, $allowed_sizes, true ) ) {
							$size        = $size_name;
							$custom_size = '';
							break;
						}
					}
				}
			}

			if ( ! in_array( $size, $allowed_sizes, true ) ) {
				update_post_meta(
					$post_id,
					'mobile' === $device ? '_woodmart_preload_image_mobile_size' : '_woodmart_preload_image_size',
					'custom'
				);

				if ( $custom_size ) {
					$dimensions = explode( 'x', $custom_size );

					update_post_meta(
						$post_id,
						'mobile' === $device ? '_woodmart_preload_image_mobile_custom_width' : '_woodmart_preload_image_custom_width',
						(int) $dimensions[0]
					);
					update_post_meta(
						$post_id,
						'mobile' === $device ? '_woodmart_preload_image_mobile_custom_height' : '_woodmart_preload_image_custom_height',
						(int) $dimensions[1]
					);
				}
			} else {
				update_post_meta(
					$post_id,
					'mobile' === $device ? '_woodmart_preload_image_mobile_size' : '_woodmart_preload_image_size',
					$size
				);

				delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_custom_width' : '_woodmart_preload_image_custom_width' );
				delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_custom_height' : '_woodmart_preload_image_custom_height' );
			}

			update_post_meta(
				$post_id,
				'mobile' === $device ? '_woodmart_preload_image_mobile_type' : '_woodmart_preload_image_type',
				$image_type
			);
		} else {
			delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile' : '_woodmart_preload_image' );
			delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_size' : '_woodmart_preload_image_size' );
			delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_custom_width' : '_woodmart_preload_image_custom_width' );
			delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_custom_height' : '_woodmart_preload_image_custom_height' );
			delete_post_meta( $post_id, 'mobile' === $device ? '_woodmart_preload_image_mobile_type' : '_woodmart_preload_image_type' );
		}

		wp_send_json_success(
			array(
				'message' => esc_html__( 'Your selection has been saved successfully.', 'woodmart' ),
			)
		);
	}

	/**
	 * Add LCP button to the admin bar.
	 *
	 * @param object $admin_bar The admin bar object.
	 * @return void
	 */
	public function add_lcp_button_to_admin_bar( $admin_bar ) {
		if ( current_user_can( 'manage_options' ) && woodmart_get_opt( 'preload_lcp_image' ) ) {
			global $post;

			if ( ! is_singular() || ! $post || ! isset( $post->ID ) || get_post_type( $post->ID ) !== 'page' ) {
				return;
			}

			$desktop_image = get_post_meta( $post->ID, '_woodmart_preload_image', true );
			$mobile_image  = get_post_meta( $post->ID, '_woodmart_preload_image_mobile', true );

			$has_image = ( ! empty( $desktop_image['id'] ) || ! empty( $mobile_image['id'] ) );

			$url = add_query_arg( 'wd_capture_lcp', '1', get_permalink( $post->ID ) );
			$url = add_query_arg( 'security', wp_create_nonce( 'wd_preload_image' ), $url );

			$classes = 'wd-lcp-admin-bar';

			if ( $has_image && ! $this->is_capture_mode() ) {
				if ( ! empty( $desktop_image['id'] ) ) {
					$classes .= ' wd-has-desktop-image';
				}
				if ( ! empty( $mobile_image['id'] ) ) {
					$classes .= ' wd-has-mobile-image';
				}
			}

			$admin_bar->add_node(
				array(
					'id'    => 'woodmart_lcp_image',
					'title' => $has_image ? esc_html__( 'LCP Image', 'woodmart' ) : esc_html__( 'Find LCP Image', 'woodmart' ),
					'href'  => $url,
					'meta'  => array(
						'title' => $has_image ? '' : esc_html__( 'Set LCP image for this page', 'woodmart' ),
						'class' => $classes,
					),
				)
			);

			if ( $has_image && ! $this->is_capture_mode() ) {
				woodmart_force_enqueue_style( 'opt-lcp-image' );

				$remove_image_url = add_query_arg( 'wd_remove_lcp', '1', get_permalink( $post->ID ) );
				$remove_image_url = add_query_arg( 'security', wp_create_nonce( 'wd_remove_preload_image' ), $remove_image_url );

				if ( ! empty( $desktop_image['id'] ) ) {
					ob_start();
					?>
					<div class="wd-lcp-content">
						<div class="wd-lcp-thumb">
							<?php echo wp_get_attachment_image( $desktop_image['id'] ); ?>
						</div>
						<div class="wd-lcp-desc"><?php esc_html_e( 'Following LCP image is already set for this page. If you want to change it, please click the relevant button below.', 'woodmart' ); ?></div>
						<a href="https://xtemos.com/docs-topic/explanation-of-preload-lcp-image-option/"><?php esc_html_e( 'Documentation', 'woodmart' ); ?></a>
					</div>
					<?php

					$admin_bar->add_node(
						array(
							'id'     => 'woodmart_lcp_image_content',
							'title'  => ob_get_clean(),
							'parent' => 'woodmart_lcp_image',
							'meta'   => array(
								'title' => '',
								'class' => 'wd-hide-sm',
							),
						)
					);

					$admin_bar->add_node(
						array(
							'id'     => 'woodmart_lcp_image_change',
							'title'  => esc_html__( 'Refresh image', 'woodmart' ),
							'parent' => 'woodmart_lcp_image',
							'href'   => $url,
							'meta'   => array(
								'class' => 'wd-change-lcp wd-hide-sm',
							),
						)
					);

					$admin_bar->add_node(
						array(
							'id'     => 'woodmart_lcp_image_remove',
							'title'  => esc_html__( 'Remove image', 'woodmart' ),
							'parent' => 'woodmart_lcp_image',
							'href'   => add_query_arg( 'device', 'desktop', $remove_image_url ),
							'meta'   => array(
								'class' => 'wd-remove-lcp wd-hide-sm',
							),
						)
					);
				}

				if ( ! empty( $mobile_image['id'] ) ) {
					ob_start();

					?>
					<div class="wd-lcp-content">
						<div class="wd-lcp-thumb">
							<?php echo wp_get_attachment_image( $mobile_image['id'] ); ?>
						</div>
						<div class="wd-lcp-desc"><?php esc_html_e( 'Following LCP image is already set for this page. If you want to change it, please click the relevant button below.', 'woodmart' ); ?></div>
						<a href="https://xtemos.com/docs-topic/explanation-of-preload-lcp-image-option/"><?php esc_html_e( 'Documentation', 'woodmart' ); ?></a>
					</div>
					<?php

					$admin_bar->add_node(
						array(
							'id'     => 'woodmart_lcp_image_content_mobile',
							'title'  => ob_get_clean(),
							'parent' => 'woodmart_lcp_image',
							'meta'   => array(
								'title' => '',
								'class' => 'wd-hide-lg wd-hide-md-sm',
							),
						)
					);

					$admin_bar->add_node(
						array(
							'id'     => 'woodmart_lcp_image_change_mobile',
							'title'  => esc_html__( 'Refresh image', 'woodmart' ),
							'parent' => 'woodmart_lcp_image',
							'href'   => $url,
							'meta'   => array(
								'class' => 'wd-change-lcp wd-hide-lg wd-hide-md-sm',
							),
						)
					);

					$admin_bar->add_node(
						array(
							'id'     => 'woodmart_lcp_image_remove_mobile',
							'title'  => esc_html__( 'Remove image', 'woodmart' ),
							'parent' => 'woodmart_lcp_image',
							'href'   => add_query_arg( 'device', 'mobile', $remove_image_url ),
							'meta'   => array(
								'class' => 'wd-remove-lcp wd-hide-lg wd-hide-md-sm',
							),
						)
					);
				}
			}

			if ( $this->is_capture_mode() ) {
				$content = '<div class="wd-lcp-content"><div class="wd-lcp-desc">' . esc_html__( 'An LCP image wasn\'t detected on this page. If you\'re sure there\'s a relevant image on the page, but it didn\'t set automatically, you can add it manually using the page metabox options or check the documentation for more details.', 'woodmart' ) . '</div><a href="https://xtemos.com/docs-topic/explanation-of-preload-lcp-image-option/">' . esc_html__( 'Documentation', 'woodmart' ) . '</a><div class="wd-loader-overlay wd-fill"></div></div>';

				$admin_bar->add_node(
					array(
						'id'     => 'woodmart_lcp_image_content',
						'title'  => $content,
						'parent' => 'woodmart_lcp_image',
						'meta'   => array(
							'title' => '',
						),
					)
				);

				$admin_bar->add_node(
					array(
						'id'     => 'woodmart_lcp_image_confirm',
						'title'  => esc_html__( 'Confirm', 'woodmart' ),
						'parent' => 'woodmart_lcp_image',
						'href'   => '#',
						'icon'   => 'xts-i-page-title',
						'meta'   => array(
							'class' => 'wd-confirm',
						),
					)
				);

				$admin_bar->add_node(
					array(
						'id'     => 'woodmart_lcp_image_cancel',
						'title'  => esc_html__( 'Cancel', 'woodmart' ),
						'parent' => 'woodmart_lcp_image',
						'href'   => '#',
						'meta'   => array(
							'class' => 'wd-cancel',
						),
					)
				);

				$admin_bar->add_node(
					array(
						'id'     => 'woodmart_lcp_image_done',
						'title'  => esc_html__( 'Done', 'woodmart' ),
						'parent' => 'woodmart_lcp_image',
						'href'   => '#',
						'icon'   => 'xts-i-page-title',
						'meta'   => array(
							'class' => 'wd-done wd-hide',
						),
					)
				);

			}
		}
	}

	/**
	 * Exclude preload image from lazy loading.
	 *
	 * @param array $urls Array of URLs to exclude from lazy loading.
	 * @return array
	 */
	public function exclude_preload_image( $urls ) {
		if ( woodmart_get_opt( 'preload_lcp_image' ) ) {
			$post_id = get_the_ID();

			$desktop_image = get_post_meta( $post_id, '_woodmart_preload_image', true );
			$mobile_image  = get_post_meta( $post_id, '_woodmart_preload_image_mobile', true );

			if ( ! empty( $desktop_image['id'] ) ) {
				$image_size = get_post_meta( $post_id, '_woodmart_preload_image_size', true );
				$image_size = $image_size ? $image_size : 'full';

				if ( 'custom' === $image_size ) {
					$image_width  = (int) get_post_meta( $post_id, '_woodmart_preload_image_custom_width', true );
					$image_height = (int) get_post_meta( $post_id, '_woodmart_preload_image_custom_height', true );

					if ( $image_width || $image_height ) {
						$image_size = $image_width . 'x' . $image_height;
					} else {
						$image_size = 'full';
					}
				}

				$urls[] = woodmart_otf_get_image_url( $desktop_image['id'], $image_size );
			}

			if ( ! empty( $mobile_image['id'] ) ) {
				$image_size = get_post_meta( $post_id, '_woodmart_preload_image_mobile_size', true );
				$image_size = $image_size ? $image_size : 'full';

				if ( 'custom' === $image_size ) {
					$image_width  = (int) get_post_meta( $post_id, '_woodmart_preload_image_mobile_custom_width', true );
					$image_height = (int) get_post_meta( $post_id, '_woodmart_preload_image_mobile_custom_height', true );

					if ( $image_width || $image_height ) {
						$image_size = $image_width . 'x' . $image_height;
					} else {
						$image_size = 'full';
					}
				}

				$urls[] = woodmart_otf_get_image_url( $mobile_image['id'], $image_size );
			}
		}

		return $urls;
	}

	/**
	 * Exclude preload image from lazy loading.
	 *
	 * @param array  $loading_attrs The loading optimization attributes.
	 * @param string $tag_name The tag name.
	 * @param array  $attr Array of the attributes for the tag.
	 *
	 * @return array
	 */
	public function exclude_preload_image_in_lazy_load( $loading_attrs, $tag_name, $attr ) {
		if ( woodmart_get_opt( 'preload_lcp_image' ) ) {
			$post_id = get_the_ID();

			$desktop_image = get_post_meta( $post_id, '_woodmart_preload_image', true );
			$mobile_image  = get_post_meta( $post_id, '_woodmart_preload_image_mobile', true );

			if ( ! empty( $desktop_image['id'] ) && ! $this->desktop_image_src ) {
				$image_size = get_post_meta( $post_id, '_woodmart_preload_image_size', true );
				$image_size = $image_size ? $image_size : 'full';

				if ( 'custom' === $image_size ) {
					$image_width  = (int) get_post_meta( $post_id, '_woodmart_preload_image_custom_width', true );
					$image_height = (int) get_post_meta( $post_id, '_woodmart_preload_image_custom_height', true );

					if ( $image_width || $image_height ) {
						$image_size = $image_width . 'x' . $image_height;
					} else {
						$image_size = 'full';
					}
				}

				$this->desktop_image_src = woodmart_otf_get_image_url( $desktop_image['id'], $image_size );
			}

			if ( ! empty( $mobile_image['id'] ) && ! $this->mobile_image_src ) {
				$image_size = get_post_meta( $post_id, '_woodmart_preload_image_mobile_size', true );
				$image_size = $image_size ? $image_size : 'full';

				if ( 'custom' === $image_size ) {
					$image_width  = (int) get_post_meta( $post_id, '_woodmart_preload_image_mobile_custom_width', true );
					$image_height = (int) get_post_meta( $post_id, '_woodmart_preload_image_mobile_custom_height', true );

					if ( $image_width || $image_height ) {
						$image_size = $image_width . 'x' . $image_height;
					} else {
						$image_size = 'full';
					}
				}

				$this->mobile_image_src = woodmart_otf_get_image_url( $mobile_image['id'], $image_size );
			}

			if ( ! empty( $attr['src'] ) && in_array( $attr['src'], array( $this->desktop_image_src, $this->mobile_image_src ), true ) ) {
				$loading_attrs['loading'] = false;
			}
		}

		return $loading_attrs;
	}

	/**
	 * Add localized settings for LCP.
	 *
	 * @param array $localized_strings Localized strings array.
	 * @return array
	 */
	public function add_localized_settings( $localized_strings ) {
		if ( $this->is_capture_mode() ) {
			$post_id = get_the_ID();

			$desktop_image = get_post_meta( $post_id, '_woodmart_preload_image', true );
			$mobile_image  = get_post_meta( $post_id, '_woodmart_preload_image_mobile', true );
			$has_image     = ( ! empty( $desktop_image['id'] ) || ! empty( $mobile_image['id'] ) );

			$localized_strings['lcp_image_confirmed']         = esc_html__( 'This is the LCP image detected on the page. Would you like to save it for preloading?', 'woodmart' );
			$localized_strings['lcp_without_image_confirmed'] = $has_image ? esc_html__( 'An LCP image wasn\'t detected on this page, and the previous image that was set is no longer available. If you confirm, the LCP image field will be cleared. If you cancel, the field will retain the previous image in case you want to restore it.', 'woodmart' ) : esc_html__( 'An LCP image wasn\'t detected on this page. If you\'re sure there\'s a relevant image on the page, but it didn\'t set automatically, you can add it manually using the page metabox options or check the documentation for more details.', 'woodmart' );
			$localized_strings['lcp_has_image']               = $has_image;
		}

		return $localized_strings;
	}

	/**
	 * Popup template for LCP confirmation.
	 *
	 * @return void
	 */
	public function popup_template() {
		if ( $this->is_capture_mode() ) {
			woodmart_enqueue_inline_style( 'opt-lcp-image' );

			woodmart_enqueue_js_script( 'lcp-tracker' );
			?>
			<div class="wd-loader-overlay wd-fill wd-lcp-loader"></div>
			<?php
		}
	}

	/**
	 * Disable default fetchpriority for images when LCP image is set.
	 *
	 * @param int $pixels Number of pixels to consider for fetchpriority.
	 * @return int
	 */
	public function disable_default_fetchpriority( $pixels ) {
		if ( $this->is_capture_mode() ) {
			return $pixels;
		}

		$post_id = get_the_ID();

		$desktop_image = get_post_meta( $post_id, '_woodmart_preload_image', true );
		$mobile_image  = get_post_meta( $post_id, '_woodmart_preload_image_mobile', true );

		if ( ! empty( $desktop_image['id'] ) || ! empty( $mobile_image['id'] ) ) {
			return PHP_INT_MAX;
		}

		return $pixels;
	}
}

LCP::get_instance();
