<?php

namespace XTS\Modules;

use WC_Product_Cat_List_Walker;

if ( ! class_exists( 'WC_Product_Cat_List_Walker' ) ) {
	require_once WC()->plugin_path() . '/includes/walkers/class-wc-product-cat-list-walker.php';
}

class Product_Category_Filter_Walker extends WC_Product_Cat_List_Walker {
	/**
	 * List of current categories ids.
	 *
	 * @var array List of current categories ids.
	 */
	public $current_categories = array();

	/**
	 * List of current category ancestors ids.
	 *
	 * @var array List of current category ancestors ids.
	 */
	public $current_category_ancestors = array();

	/**
	 * Constructor.
	 *
	 * @param array $current_categories List of current categories ids.
	 */
	public function __construct( $current_categories = array() ) {
		$this->current_categories = $current_categories;

		foreach ( $this->current_categories as $current_category ) {
			$this->current_category_ancestors = array_merge(
				$this->current_category_ancestors,
				get_ancestors( $current_category, 'product_cat' )
			);
		}

		$this->current_category_ancestors = array_unique( $this->current_category_ancestors );
	}

	/**
	 * Start the element output.
	 *
	 * @param string  $output            Passed by reference. Used to append additional content.
	 * @param object  $category          Category.
	 * @param int     $depth             Depth of category in reference to parents.
	 * @param array   $args              Arguments.
	 * @param integer $current_object_id Current object ID.
	 */
	public function start_el( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
		switch ( $args['view_type'] ) {
			case 'list':
				$this->start_el_list( $output, $category, $depth, $args, $current_object_id );
				break;
			case 'dropdown':
				$this->start_el_dropdown( $output, $category, $depth, $args, $current_object_id );
				break;
		}
	}

	/**
	 * Start the element output.
	 *
	 * @param string  $output            Passed by reference. Used to append additional content.
	 * @param object  $category          Category.
	 * @param int     $depth             Depth of category in reference to parents.
	 * @param array   $args              Arguments.
	 * @param integer $current_object_id Current object ID.
	 */
	public function start_el_list( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$class_names = array();

		$cat_id = intval( $category->term_id );

		$class_names[] = 'cat-item cat-item-' . $cat_id;

		if ( in_array( $cat_id, $this->current_categories, true ) ) {
			$class_names[] = 'wd-active';
		}

		if ( $args['has_children'] && $args['hierarchical'] && ( empty( $args['max_depth'] ) || $args['max_depth'] > $depth + 1 ) ) {
			$class_names[] = ' wd-active-parent';
		}

		if ( $this->current_category_ancestors && $this->current_categories && in_array( $cat_id, $this->current_category_ancestors, true ) ) {
			$class_names[] = 'wd-current-active-parent';
		}

		$output .= '<li class="' . implode( ' ', $class_names ) . '">';
		$output .= '<a href="' . $this->get_filter_url( $category ) . '" class="wd-filter-lable">' . apply_filters( 'list_product_cats', $category->name, $category ) . '</a>';

		if ( $args['show_count'] ) {
			$output .= ' <span class="count">' . $category->count . '</span>';
		}
	}

	/**
	 * Start the element output.
	 *
	 * @param string  $output            Passed by reference. Used to append additional content.
	 * @param object  $category          Category.
	 * @param int     $depth             Depth of category in reference to parents.
	 * @param array   $args              Arguments.
	 * @param integer $current_object_id Current object ID.
	 */
	public function start_el_dropdown( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
		if ( ! empty( $args['hierarchical'] ) ) {
			$pad = str_repeat( '&nbsp;', $depth * 3 );
		} else {
			$pad = '';
		}

		$cat_id   = intval( $category->term_id );
		$cat_name = apply_filters( 'list_product_cats', $category->name, $category );
		$output  .= "\t<option class=\"level-$depth\" value=\"" . esc_attr( $category->slug ) . '"';

		if ( in_array( $cat_id, $this->current_categories, true ) ) {
			$output .= ' selected="selected"';
		}

		$output .= '>';
		$output .= esc_html( $pad . $cat_name );

		if ( ! empty( $args['show_count'] ) ) {
			$output .= '&nbsp;(' . absint( $category->count ) . ')';
		}

		$output .= "</option>\n";
	}

	public function get_filter_url( $category ) {
		$base_link         = woodmart_filters_get_page_base_url();
		$category_slug     = $category->slug;
		$chosen_categories = array_map(
			function ( $chosen_category_id ) {
				$chosen_category = get_term_by( 'term_id', $chosen_category_id, 'product_cat' );

				if ( $chosen_category ) {
					return $chosen_category->slug;
				}
			},
			$this->current_categories
		);

		if ( is_product_category() && ! isset( $_GET['filter_category'] ) ) { // phpcs:ignore.
			global $wp_query;

			if ( isset( $wp_query->queried_object_id ) ) {
				$current_cat_obj = $wp_query->queried_object;
				$key             = array_search( $current_cat_obj->slug, $chosen_categories, true );

				if ( false !== $key ) {
					unset( $chosen_categories[ $key ] );
				}
			}
		} elseif ( ! empty( $category->parent ) && in_array( $category->parent, $this->current_categories, true ) ) {
			$parent_category = get_term_by( 'term_id', $category->parent, 'product_cat' );

			if ( $parent_category ) {
				$key = array_search( $parent_category->slug, $chosen_categories, true );

				if ( false !== $key ) {
					unset( $chosen_categories[ $key ] );
				}
			}
		} elseif ( empty( $category->parent ) ) {
			$child_categories = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
					'parent'     => $category->term_id,
					'fields'     => 'ids',
				)
			);

			if ( ! is_wp_error( $child_categories ) && ! empty( $child_categories ) ) {
				foreach ( $child_categories as $child_category_id ) {
					$child_category = get_term_by( 'term_id', $child_category_id, 'product_cat' );

					if ( $child_category ) {
						$key = array_search( $child_category->slug, $chosen_categories, true );

						if ( false !== $key ) {
							unset( $chosen_categories[ $key ] );
						}
					}
				}
			}
		}

		// Remove all child categories when clicking on parent category.
		$all_child_categories = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'child_of'   => $category->term_id,
				'fields'     => 'ids',
			)
		);

		if ( ! is_wp_error( $all_child_categories ) && ! empty( $all_child_categories ) ) {
			foreach ( $all_child_categories as $child_category_id ) {
				$child_category = get_term_by( 'term_id', $child_category_id, 'product_cat' );

				if ( $child_category ) {
					$key = array_search( $child_category->slug, $chosen_categories, true );

					if ( false !== $key ) {
						unset( $chosen_categories[ $key ] );
					}
				}
			}
		}

		if ( in_array( $category_slug, $chosen_categories, true ) ) {
			$key = array_search( $category_slug, $chosen_categories, true );

			if ( false !== $key ) {
				unset( $chosen_categories[ $key ] );
			}
		} else {
			$chosen_categories[] = $category->slug;
		}

		if ( ! empty( $chosen_categories ) ) {
			$link = add_query_arg( 'filter_category', implode( ',', $chosen_categories ), $base_link );
		} else {
			$link = remove_query_arg( 'filter_category', $base_link );
		}

		$link = str_replace( '%2C', ',', $link );

		return $link;
	}
}
