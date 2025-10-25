<?php
/**
 * Gutenberg blocks class.
 *
 * @package Woodmart
 */

namespace XTS\Gutenberg;

use WP_Roles;
use XTS\Modules\Layouts\Single_Post;
use XTS\Modules\Layouts\Single_Product;
use XTS\Singleton;

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Gutenberg module.
 *
 * @package Woodmart
 */
class Gutenberg extends Singleton {
	/**
	 * Register new controls.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'files_include' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_styles' ), 30 );
		add_filter( 'pre_render_block', array( $this, 'pre_render_block' ), 10, 2 );
		add_action( 'rest_api_init', array( $this, 'register_rest_fields' ) );
	}

	/**
	 * Include module files.
	 *
	 * @since 1.0.0
	 */
	public function files_include() {
		if ( ! woodmart_get_opt( 'gutenberg_blocks' ) ) {
			return;
		}

		$files = array(
			'integrations/gutenberg/helpers',
			'integrations/gutenberg/inc/class-block-css',
			'integrations/gutenberg/inc/class-block-attributes',
			'integrations/gutenberg/inc/class-template-library',

			'integrations/gutenberg/class-block',
			'integrations/gutenberg/class-post-css',
			'integrations/gutenberg/class-blocks-assets',
			'integrations/gutenberg/class-google-fonts',
			'integrations/gutenberg/class-widget-block',

			// Required layout shortcodes.
			'modules/layouts/wpb/shortcodes/archive-loop/blog-archive',
			'modules/layouts/wpb/shortcodes/archive-loop/portfolio-archive',
			'modules/layouts/wpb/shortcodes/single-post/post-categories',
			'modules/layouts/wpb/shortcodes/single-post/post-comments',
			'modules/layouts/wpb/shortcodes/single-post/post-image',

			// Controls CSS helpers.
			'/integrations/gutenberg/src/components/controls/position/css',
			'/integrations/gutenberg/src/components/controls/padding/css',
			'/integrations/gutenberg/src/components/controls/margin/css',
			'/integrations/gutenberg/src/components/controls/background/css',
			'/integrations/gutenberg/src/components/controls/advanced/css',
			'/integrations/gutenberg/src/components/controls/transition/css',
			'/integrations/gutenberg/src/components/controls/shape-divider/css',
			'/integrations/gutenberg/src/components/controls/border/css',
			'/integrations/gutenberg/src/components/controls/box-shadow/css',
			'/integrations/gutenberg/src/components/controls/typography/css',
			'/integrations/gutenberg/src/components/controls/transform/css',
			'/integrations/gutenberg/src/components/controls/carousel/css',

			// Controls attributes helpers.
			'/integrations/gutenberg/src/components/controls/position/attributes',
			'/integrations/gutenberg/src/components/controls/padding/attributes',
			'/integrations/gutenberg/src/components/controls/margin/attributes',
			'/integrations/gutenberg/src/components/controls/background/attributes',
			'/integrations/gutenberg/src/components/controls/border/attributes',
			'/integrations/gutenberg/src/components/controls/box-shadow/attributes',
			'/integrations/gutenberg/src/components/controls/animation/attributes',
			'/integrations/gutenberg/src/components/controls/parallax-scroll/attributes',
			'/integrations/gutenberg/src/components/controls/responsive-visibility/attributes',
			'/integrations/gutenberg/src/components/controls/transition/attributes',
			'/integrations/gutenberg/src/components/controls/shape-divider/attributes',
			'/integrations/gutenberg/src/components/controls/typography/attributes',
			'/integrations/gutenberg/src/components/controls/advanced-color-picker/attributes',
			'/integrations/gutenberg/src/components/controls/transform/attributes',
			'/integrations/gutenberg/src/components/controls/advanced/attributes',
			'/integrations/gutenberg/src/components/controls/carousel/attributes',

			// Block attributes helpers.
			'/integrations/gutenberg/src/blocks/products/attributes',
			'/integrations/gutenberg/src/blocks/product-categories/attributes',
			'/integrations/gutenberg/src/blocks/blog/attributes',
			'/integrations/gutenberg/src/blocks/breadcrumbs/attributes',
			'/integrations/gutenberg/src/blocks/portfolio/attributes',
			'/integrations/gutenberg/src/blocks/product-filters/product-filters/attributes',
			'/integrations/gutenberg/src/blocks/product-filters/product-filters-attributes/attributes',
			'/integrations/gutenberg/src/blocks/product-filters/product-filters-categories/attributes',
			'/integrations/gutenberg/src/blocks/product-filters/product-filters-stock-status/attributes',
			'/integrations/gutenberg/src/blocks/product-filters/product-filters-prices/attributes',
			'/integrations/gutenberg/src/blocks/product-filters/product-filters-orderby/attributes',
			'/integrations/gutenberg/src/blocks/brands/attributes',
			'/integrations/gutenberg/src/blocks/social-buttons/attributes',
			'/integrations/gutenberg/src/blocks/hotspot-product/attributes',
			'/integrations/gutenberg/src/blocks/menu/attributes',
			'/integrations/gutenberg/src/blocks/ajax-search/attributes',
			'/integrations/gutenberg/src/blocks/instagram/attributes',
			'/integrations/gutenberg/src/blocks/sidebar/attributes',
			'/integrations/gutenberg/src/blocks/contact-form-7/attributes',
			'/integrations/gutenberg/src/blocks/mailchimp/attributes',
			'/integrations/gutenberg/src/blocks/container/attributes',
			'/integrations/gutenberg/src/blocks/video/attributes',
			'/integrations/gutenberg/src/blocks/slider/attributes',
			'/integrations/gutenberg/src/blocks/google-map/attributes',
			'/integrations/gutenberg/src/blocks/open-street-map/attributes',
			'/integrations/gutenberg/src/blocks/size-guide/attributes',
			'/integrations/gutenberg/src/blocks/page-heading/attributes',

			// Single product blocks.
			'/integrations/gutenberg/src/layouts/sp-add-to-cart/attributes',
			'/integrations/gutenberg/src/layouts/sp-short-description/attributes',
			'/integrations/gutenberg/src/layouts/sp-title/attributes',
			'/integrations/gutenberg/src/layouts/sp-gallery/attributes',
			'/integrations/gutenberg/src/layouts/sp-content/attributes',
			'/integrations/gutenberg/src/layouts/sp-meta/attributes',
			'/integrations/gutenberg/src/layouts/sp-price/attributes',
			'/integrations/gutenberg/src/layouts/sp-navigation/attributes',
			'/integrations/gutenberg/src/layouts/sp-rating/attributes',
			'/integrations/gutenberg/src/layouts/sp-reviews/attributes',
			'/integrations/gutenberg/src/layouts/sp-stock-progress-bar/attributes',
			'/integrations/gutenberg/src/layouts/sp-additional-info-table/attributes',
			'/integrations/gutenberg/src/layouts/sp-extra-content/attributes',
			'/integrations/gutenberg/src/layouts/sp-brand-info/attributes',
			'/integrations/gutenberg/src/layouts/sp-brands/attributes',
			'/integrations/gutenberg/src/layouts/sp-compare-btn/attributes',
			'/integrations/gutenberg/src/layouts/sp-wishlist-btn/attributes',
			'/integrations/gutenberg/src/layouts/sp-size-guide-btn/attributes',
			'/integrations/gutenberg/src/layouts/sp-stock-status/attributes',
			'/integrations/gutenberg/src/layouts/sp-visitor-counter/attributes',
			'/integrations/gutenberg/src/layouts/sp-dynamic-discount/attributes',
			'/integrations/gutenberg/src/layouts/sp-fbt-products/attributes',
			'/integrations/gutenberg/src/layouts/sp-linked-variations/attributes',
			'/integrations/gutenberg/src/layouts/sp-meta-value/attributes',
			'/integrations/gutenberg/src/layouts/sp-sold-counter/attributes',
			'/integrations/gutenberg/src/layouts/sp-countdown/attributes',
			'/integrations/gutenberg/src/layouts/sp-tabs/attributes',
			'/integrations/gutenberg/src/layouts/sp-estimate-delivery/attributes',
			'/integrations/gutenberg/src/layouts/sp-price-tracker/attributes',

			// Shop archive blocks.
			'/integrations/gutenberg/src/layouts/sa-active-filters/attributes',
			'/integrations/gutenberg/src/layouts/sa-archive-description/attributes',
			'/integrations/gutenberg/src/layouts/sa-archive-extra-description/attributes',
			'/integrations/gutenberg/src/layouts/sa-archive-products/attributes',
			'/integrations/gutenberg/src/layouts/sa-archive-title/attributes',
			'/integrations/gutenberg/src/layouts/sa-filters-area/attributes',
			'/integrations/gutenberg/src/layouts/sa-filters-area-btn/attributes',
			'/integrations/gutenberg/src/layouts/sa-orderby/attributes',
			'/integrations/gutenberg/src/layouts/sa-per-page/attributes',
			'/integrations/gutenberg/src/layouts/sa-result-count/attributes',
			'/integrations/gutenberg/src/layouts/sa-view/attributes',

			// Post archive blocks.
			'/integrations/gutenberg/src/layouts/pa-blog/attributes',
			'/integrations/gutenberg/src/layouts/pa-portfolio/attributes',
			'/integrations/gutenberg/src/layouts/pa-portfolio-cats/attributes',

			// Single post blocks.
			'/integrations/gutenberg/src/layouts/author-bio/attributes',
			'/integrations/gutenberg/src/layouts/post-author-meta/attributes',
			'/integrations/gutenberg/src/layouts/post-categories/attributes',
			'/integrations/gutenberg/src/layouts/post-comments/attributes',
			'/integrations/gutenberg/src/layouts/post-comments-button/attributes',
			'/integrations/gutenberg/src/layouts/post-comments-form/attributes',
			'/integrations/gutenberg/src/layouts/post-content/attributes',
			'/integrations/gutenberg/src/layouts/post-date-meta/attributes',
			'/integrations/gutenberg/src/layouts/post-excerpt/attributes',
			'/integrations/gutenberg/src/layouts/post-image/attributes',
			'/integrations/gutenberg/src/layouts/post-meta-value/attributes',
			'/integrations/gutenberg/src/layouts/post-navigation/attributes',
			'/integrations/gutenberg/src/layouts/post-tags/attributes',
			'/integrations/gutenberg/src/layouts/post-title/attributes',

			// WooCommerce blocks.
			'/integrations/gutenberg/src/layouts/woo-breadcrumbs/attributes',
			'/integrations/gutenberg/src/layouts/woo-checkout-steps/attributes',
			'/integrations/gutenberg/src/layouts/woo-hook/attributes',
			'/integrations/gutenberg/src/layouts/woo-notices/attributes',
			'/integrations/gutenberg/src/layouts/woo-page-title/attributes',
			'/integrations/gutenberg/src/layouts/woo-shipping-progress-bar/attributes',

			// Checkout blocks.
			'/integrations/gutenberg/src/layouts/ch-billing-details/attributes',
			'/integrations/gutenberg/src/layouts/ch-coupon-form/attributes',
			'/integrations/gutenberg/src/layouts/ch-login-form/attributes',
			'/integrations/gutenberg/src/layouts/ch-order-review/attributes',
			'/integrations/gutenberg/src/layouts/ch-payment-methods/attributes',
			'/integrations/gutenberg/src/layouts/ch-shipping-details/attributes',

			// Thank you page blocks.
			'/integrations/gutenberg/src/layouts/tp-customer-details/attributes',
			'/integrations/gutenberg/src/layouts/tp-order-details/attributes',
			'/integrations/gutenberg/src/layouts/tp-order-overview/attributes',
			'/integrations/gutenberg/src/layouts/tp-order-message/attributes',
			'/integrations/gutenberg/src/layouts/tp-payment-instructions/attributes',
			'/integrations/gutenberg/src/layouts/tp-order-meta/attributes',

			// My account blocks.
			'/integrations/gutenberg/src/layouts/ma-content/attributes',
			'/integrations/gutenberg/src/layouts/ma-navigation/attributes',
			'/integrations/gutenberg/src/layouts/ma-login/attributes',
			'/integrations/gutenberg/src/layouts/ma-register/attributes',
			'/integrations/gutenberg/src/layouts/ma-lost-password/attributes',

			// Cart blocks.
			'/integrations/gutenberg/src/layouts/ct-table/attributes',
			'/integrations/gutenberg/src/layouts/ct-totals/attributes',
			'/integrations/gutenberg/src/layouts/ct-empty-cart/attributes',
			'/integrations/gutenberg/src/layouts/ct-free-gifts/attributes',

			'integrations/gutenberg/class-blocks',
		);

		foreach ( $files as $file ) {
			require_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php' );
		}
	}

	/**
	 * Styles and scripts to be loaded on backend.
	 *
	 * @return void
	 */
	public function scripts_styles() {
		if ( ! woodmart_get_opt( 'gutenberg_blocks' ) || ! is_admin() ) {
			return;
		}

		wp_register_script( 'xts-blocks', WOODMART_THEME_DIR . '/inc/integrations/gutenberg/build/index.js', array(), WOODMART_VERSION, true );
		wp_register_style( 'xts-blocks', WOODMART_THEME_DIR . '/inc/integrations/gutenberg/build/index.css', array(), WOODMART_VERSION );

		wp_set_script_translations( 'xts-blocks', 'woodmart', trailingslashit( WP_LANG_DIR ) . 'themes' );

		wp_localize_script(
			'xts-blocks',
			'wdBlocksData',
			array(
				'theme_custom_fonts' => $this->get_theme_custom_fonts(),
			)
		);

		wp_enqueue_script(
			'webfontloader',
			'https://ajax.googleapis.com/ajax/libs/webfont/1.5.0/webfont.js',
			array( 'jquery' ),
			'1.5.0',
			true
		);
	}

	/**
	 * Get custom theme fonts.
	 *
	 * @return array
	 */
	public function get_theme_custom_fonts() {
		$custom_fonts_data = woodmart_get_opt( 'multi_custom_fonts' );
		$custom_fonts      = array();
		if ( isset( $custom_fonts_data['{{index}}'] ) ) {
			unset( $custom_fonts_data['{{index}}'] );
		}

		if ( is_array( $custom_fonts_data ) ) {
			foreach ( $custom_fonts_data as $font ) {
				if ( ! $font['font-name'] ) {
					continue;
				}

				$custom_fonts[] = $font['font-name'];
			}
		}

		$typekit_fonts = woodmart_get_opt( 'typekit_fonts' );

		if ( $typekit_fonts ) {
			$typekit = explode( ',', $typekit_fonts );
			foreach ( $typekit as $font ) {
				$custom_fonts[] = trim( $font );
			}
		}

		return $custom_fonts;
	}

	/**
	 * Pre render block hook.
	 *
	 * @param string|null $value The pre-rendered content. Default null.
	 * @param array       $block The block being rendered.
	 */
	public function pre_render_block( $value, $block ) {
		if ( woodmart_get_opt( 'gutenberg_blocks' ) && wp_is_serving_rest_request() && woodmart_woocommerce_installed() && ! empty( $block['blockName'] ) && ( 'wd/products' === $block['blockName'] || 'wd/products-tabs-products' === $block['blockName'] ) ) {
			include_once WC_ABSPATH . 'includes/wc-template-hooks.php';

			woodmart_woocommerce_init_hooks();
		}

		return $value;
	}

	/**
	 * Register REST fields.
	 *
	 * @return void
	 */
	public function register_rest_fields() {
		if ( ! woodmart_get_opt( 'gutenberg_blocks' ) ) {
			return;
		}

		register_rest_route(
			'wd/v1',
			'/attribute-terms',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_attribute_terms' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);

		register_rest_route(
			'wd/v1',
			'/all-posts',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_posts' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);

		register_rest_route(
			'wd/v1',
			'/all-terms',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_terms' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);

		register_rest_route(
			'wd/v1',
			'/taxonomies',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_taxonomies' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);

		register_rest_route(
			'wd/v1',
			'/all-taxonomies',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_all_taxonomies' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);

		register_rest_route(
			'wd/v1',
			'/user-roles',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_user_roles' ),
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);
	}

	/**
	 * Get attribute terms.
	 *
	 * @param object $request Request object.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_attribute_terms( $request ) {
		$search_term  = $request->get_param( 'search' );
		$selected_ids = $request->get_param( 'selected' );
		$results      = array();

		if ( empty( $search_term ) && empty( $selected_ids ) || ! woodmart_woocommerce_installed() ) {
			return rest_ensure_response( $results );
		}

		$raw_taxonomies = wc_get_attribute_taxonomies();
		$taxonomies     = array();

		if ( ! $raw_taxonomies ) {
			return rest_ensure_response( $results );
		}

		foreach ( $raw_taxonomies as $taxonomy ) {
			$taxonomies[] = 'pa_' . $taxonomy->attribute_name;
		}

		$args = array(
			'taxonomy'   => $taxonomies,
			'hide_empty' => false,
			'search'     => $search_term,
			'exclude'    => $search_term && ! empty( $selected_ids ) ? $selected_ids : array(),
			'include'    => ! $search_term && ! empty( $selected_ids ) ? $selected_ids : array(),
		);

		$terms = get_terms( $args );

		if ( is_array( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {
					$results[] = array(
						'value' => $term->term_id,
						'label' => $term->name . ' (' . $term->taxonomy . ')',
					);
				}
			}
		}

		return rest_ensure_response( $results );
	}

	/**
	 * Get all posts.
	 *
	 * @param object $request Request object.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_all_posts( $request ) {
		$search  = $request->get_param( 'search' );
		$exclude = $request->get_param( 'exclude' );
		$include = $request->get_param( 'include' );
		$results = array();

		$args = array(
			'post_type'   => 'any',
			'post_status' => 'publish',
			'numberposts' => 50,
			'exclude'     => ! empty( $exclude ) ? explode( ',', $exclude ) : array(),
			'include'     => ! empty( $include ) ? explode( ',', $include ) : array(),
		);

		if ( $search ) {
			$args['s'] = $search;
		}

		$posts = get_posts( $args );

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$results[] = array(
					'id'    => $post->ID,
					'title' => $post->post_title . ' - ' . $post->post_type . ' (ID: ' . $post->ID . ')',
				);
			}
		}

		return rest_ensure_response( $results );
	}

	/**
	 * Get all posts.
	 *
	 * @param object $request Request object.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_all_terms( $request ) {
		$search  = $request->get_param( 'search' );
		$include = $request->get_param( 'include' );
		$exclude = $request->get_param( 'exclude' );
		$results = array();

		$taxonomies = get_taxonomies( array( 'public' => true ) );

		if ( woodmart_woocommerce_installed() ) {
			$product_attribute_taxonomies = wc_get_attribute_taxonomies();

			foreach ( $product_attribute_taxonomies as $attr ) {
				$taxonomies[] = 'pa_' . $attr->attribute_name;
			}
		}

		$taxonomies = array_unique( $taxonomies );

		if ( ! empty( $include ) && ! $search ) {
			$args = array(
				'include'    => $include,
				'hide_empty' => false,
			);

			$terms = get_terms( $args );

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$term      = $terms[0];
				$results[] = array(
					'id'   => $term->term_id,
					'name' => $term->name . ' (' . $term->taxonomy . ')',
				);

				return rest_ensure_response( $results );
			}
		}

		foreach ( $taxonomies as $tax ) {
			$args = array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
			);

			if ( ! empty( $include ) ) {
				$args['include'] = array_map( 'intval', $include );
			}

			if ( ! empty( $exclude ) ) {
				$args['exclude'] = array_map( 'intval', $exclude );
			}

			if ( $search ) {
				$args['name__like'] = $search;
			}

			$terms = get_terms( $args );

			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$results[] = array(
						'id'   => $term->term_id,
						'name' => $term->name . ' (' . $term->taxonomy . ')',
					);
				}
			}
		}

		return rest_ensure_response( $results );
	}

	/**
	 * Get taxonomies for a post.
	 *
	 * @param object $request Request object.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_taxonomies( $request ) {
		$post_id = $request->get_param( 'post_id' );

		if ( ! $post_id ) {
			return rest_ensure_response( array() );
		}

		$post_type = get_post_type( $post_id );

		if ( 'woodmart_layout' === $post_type ) {
			$layout_type = get_post_meta( $post_id, 'wd_layout_type', true );

			if ( in_array( $layout_type, array( 'single_product', 'shop_archive' ), true ) ) {
				$post_type = 'product';
			} elseif ( in_array( $layout_type, array( 'blog_archive', 'single_post' ), true ) ) {
				$post_type = 'post';
			} elseif ( in_array( $layout_type, array( 'single_portfolio', 'portfolio_archive' ), true ) ) {
				$post_type = 'portfolio';
			}
		}

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$results    = array();

		if ( ! $taxonomies ) {
			return rest_ensure_response( $results );
		}

		foreach ( $taxonomies as $taxonomy ) {
			$results[] = array(
				'slug' => $taxonomy->name,
				'name' => $taxonomy->label,
			);
		}

		return rest_ensure_response( $results );
	}

	/**
	 * Get all taxonomies.
	 *
	 * @param object $request Request object.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_all_taxonomies( $request ) {
		$search  = $request->get_param( 'search' );
		$include = $request->get_param( 'include' );
		$exclude = $request->get_param( 'exclude' );

		$taxonomies = get_taxonomies( array(), 'objects' );
		$results    = array();

		foreach ( $taxonomies as $taxonomy ) {
			if ( $search && stripos( $taxonomy->label, $search ) === false && stripos( $taxonomy->name, $search ) === false ) {
				continue;
			}

			if ( ! empty( $include ) ) {
				$include_arr = array_map( 'trim', explode( ',', $include ) );
				if ( ! in_array( $taxonomy->name, $include_arr, true ) ) {
					continue;
				}
			}

			if ( ! empty( $exclude ) ) {
				$exclude_arr = array_map( 'trim', explode( ',', $exclude ) );
				if ( in_array( $taxonomy->name, $exclude_arr, true ) ) {
					continue;
				}
			}

			$results[] = array(
				'slug' => $taxonomy->name,
				'name' => $taxonomy->label . ' (' . $taxonomy->name . ')',
			);
		}

		return rest_ensure_response( $results );
	}

	/**
	 * Get user roles.
	 *
	 * @param object $request Request object.
	 * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
	 */
	public function get_user_roles( $request ) {
		$search  = $request->get_param( 'search' );
		$include = $request->get_param( 'include' );
		$exclude = $request->get_param( 'exclude' );

		global $wp_roles;

		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		$all_roles = $wp_roles->roles;
		$results   = array();

		if ( ! empty( $include ) && ! is_array( $include ) ) {
			$include = array_map( 'trim', explode( ',', $include ) );
		}

		if ( ! empty( $exclude ) && ! is_array( $exclude ) ) {
			$exclude = array_map( 'trim', explode( ',', $exclude ) );
		}

		foreach ( $all_roles as $slug => $role ) {
			$name = translate_user_role( $role['name'] );

			if ( $search && false === stripos( $name, $search ) && false === stripos( $slug, $search ) ) {
				continue;
			}

			if ( ! empty( $include ) && ! in_array( $slug, $include, true ) ) {
				continue;
			}

			if ( ! empty( $exclude ) && in_array( $slug, $exclude, true ) ) {
				continue;
			}

			$results[] = array(
				'value' => $slug,
				'label' => $name,
			);
		}

		return rest_ensure_response( $results );
	}
}

Gutenberg::get_instance();
