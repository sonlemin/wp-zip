<?php
/**
 * Admin floating blocks class file.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Floating_Blocks;

use XTS\Singleton;
use XTS\Modules\Floating_Blocks\Import;
use XTS\Modules\Floating_Blocks\Integrations\Fb_Document;
use XTS\Modules\Floating_Blocks\Integrations\Popup_Document;

/**
 * Manager class.
 */
class Manager extends Singleton {
	/**
	 * Importer instance.
	 *
	 * @var Import
	 */
	private $importer;

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
		$this->importer    = new Import();
		$this->block_types = woodmart_get_config( 'fb-types' );

		add_action( 'elementor/documents/register', array( $this, 'register_elementor_document_type' ) );
		add_filter( 'elementor/settings/controls/checkbox_list_cpt/post_type_objects', array( $this, 'add_custom_post_types' ) );

		foreach ( $this->block_types as $block_type ) {
			add_action( 'wp_ajax_' . $block_type['ajax_action'], array( $this, 'create_post' ) );
		}
	}

	/**
	 * Get active builder for specific post ID.
	 *
	 * @param int $post_id Post ID.
	 * @return string Builder name (wpb|gutenberg|elementor).
	 */
	public function get_active_editor( $post_id ) {
		if ( defined( 'WPB_VC_VERSION' ) && ! wp_is_serving_rest_request() ) {
			$vc_data = get_post_meta( $post_id, '_wpb_vc_js_status', true );
			if ( 'true' === $vc_data || get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true ) ) {
				return 'wpb';
			}
		}

		if ( woodmart_is_elementor_installed() ) {
			$document            = \Elementor\Plugin::$instance->documents->get( $post_id );
			$elementor_edit_mode = get_post_meta( $post_id, '_elementor_edit_mode', true );

			if ( 'builder' === $elementor_edit_mode && ( $document instanceof Popup_Document || $document instanceof Fb_Document ) ) {
				return 'elementor';
			}
		}

		return 'gutenberg';
	}

	/**
	 * Include required admin files.
	 *
	 * @param \Elementor\Core\Documents_Manager $documents_manager Elementor documents manager.
	 */
	public function register_elementor_document_type( $documents_manager ) {
		if ( ! woodmart_is_elementor_installed() ) {
			return;
		}

		$documents_manager->register_document_type( 'wd_floating_block', Fb_Document::class );
		$documents_manager->register_document_type( 'wd_popup', Popup_Document::class );

		add_action( 'elementor/editor/before_enqueue_scripts', 'woodmart_enqueue_admin_scripts' );
	}

	/**
	 * Add custom post types to Elementor post type list.
	 *
	 * @param array $post_types Existing post types.
	 * @return array
	 */
	public function add_custom_post_types( $post_types ) {
		foreach ( $this->block_types as $key => $block_type ) {
			$post_type = $block_type['post_type'];
			$my_cpt    = get_post_type_object( $post_type );

			if ( $my_cpt ) {
				$post_types[ $post_type ] = $my_cpt;
			}
		}

		return $post_types;
	}

	/**
	 * Create new block.
	 */
	public function create_post() {
		check_ajax_referer( 'wd-new-template-nonce', 'security' );

		$current_action = isset( $_POST['action'] ) ? woodmart_clean( $_POST['action'] ) : ''; // phpcs:ignore
		$block          = array();
		$block_key      = '';

		foreach ( $this->block_types as $key => $block_type ) {
			if ( $current_action === $block_type['ajax_action'] ) {
				$block     = $block_type;
				$block_key = $key;
				break;
			}
		}

		if ( empty( $block ) ) {
			wp_send_json_error( esc_html__( 'Invalid block type', 'woodmart' ) );
		}

		$title           = woodmart_clean( isset( $_POST['name'] ) ? $_POST['name'] : 'New ' . $block['label'] ); // phpcs:ignore
		$predefined_type = isset( $_POST['floating_type'] ) ? woodmart_clean( $_POST['floating_type'] ) : ''; // phpcs:ignore
		$predefined_name = isset( $_POST['predefined_name'] ) ? woodmart_clean( $_POST['predefined_name'] ) : ''; // phpcs:ignore

		if ( ! $predefined_type || ! $predefined_name ) {
			$args = array(
				'post_title'  => $title,
				'post_type'   => $block['post_type'],
				'post_status' => 'publish',
			);

			$id = wp_insert_post( $args );

			wp_send_json(
				array(
					'redirect_url' => $this->get_edit_url( $id ),
				)
			);
		}

		ob_start();
		$id = $this->importer->import_xml( $predefined_name, $predefined_type, $block_key );
		ob_end_clean();

		wp_update_post(
			array(
				'ID'         => $id,
				'post_title' => $title,
				'post_name'  => sanitize_title( $title ),
			)
		);

		wp_send_json(
			array(
				'redirect_url' => $this->get_edit_url( $id ),
			)
		);
	}

	/**
	 * Get edit URL.
	 *
	 * @param int $id Post ID.
	 * @return string Edit URL.
	 */
	private function get_edit_url( $id ) {
		$external_builder = 'wpb' === woodmart_get_current_page_builder() ? 'wpb' : 'elementor';
		$current_builder  = 'native' === woodmart_get_opt( 'current_builder' ) ? 'gutenberg' : $external_builder;

		if ( defined( 'WPB_VC_VERSION' ) && 'wpb' === $current_builder ) {
			return html_entity_decode( get_edit_post_link( $id ) . '&wpb-backend-editor' );
		}

		if ( woodmart_is_elementor_installed() && 'elementor' === $current_builder ) {
			return \Elementor\Plugin::$instance->documents->get( $id )->get_edit_url();
		}

		return html_entity_decode( get_edit_post_link( $id, 'block-editor' ) );
	}

	/**
	 * Get condition priority.
	 *
	 * @param string $type Condition type.
	 *
	 * @return int
	 */
	public function get_condition_priority( $type ) {
		$priority = 70;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'post_type':
			case 'taxonomy':
				$priority = 20;
				break;
			case 'single_post_type':
			case 'term_id':
			case 'single_posts_term_id':
				$priority = 30;
				break;
			case 'post_id':
				$priority = 40;
				break;
			case 'user_role':
				$priority = 50;
				break;
			case 'custom':
				$priority = 60;
				break;
		}

		return $priority;
	}

	/**
	 * Retrieves the IDs of floating blocks that exist on current page.
	 *
	 * @param string $post_type Post type.
	 * @return array The IDs of rendered floating blocks.
	 */
	public function get_current_ids( $post_type ) {
		$rendered_block_ids = array();
		$all_conditions     = $this->get_all_conditions();

		if ( woodmart_get_opt( 'promo_popup' ) && 'wd_popup' === $post_type ) {
			$rendered_block_ids[] = 'legacy';
		}

		foreach ( $all_conditions as $block_id => $conditions ) {
			if ( ! $this->check_conditions( $conditions ) || get_post_type( $block_id ) !== $post_type || ( 'legacy' === $block_id && 'wd_popup' === $post_type ) ) {
				continue;
			}

			$rendered_block_ids[] = $block_id;
		}

		return $rendered_block_ids;
	}

	/**
	 * Sort conditions by priority.
	 *
	 * @param array $a First condition.
	 * @param array $b Second condition.
	 *
	 * @return int
	 */
	public function sort_by_priority( $a, $b ) {
		return $b['condition_priority'] <=> $a['condition_priority'];
	}

	/**
	 * Check floating block conditions and determine if a floating block should be active.
	 *
	 * @param array $conditions Floating block condition name.
	 *
	 * @return bool
	 */
	public function check_conditions( $conditions ) {
		if ( empty( $conditions ) || ! is_array( $conditions ) ) {
			return false;
		}

		foreach ( $conditions as $id => $condition ) {
			$conditions[ $id ]['condition_priority'] = $this->get_condition_priority( $condition['type'] );
		}

		uasort( $conditions, array( $this, 'sort_by_priority' ) );

		$includes_matchs = array();
		$excludes_matchs = array();

		foreach ( $conditions as $condition ) {
			$met        = false;
			$query      = isset( $condition['query'] ) ? $condition['query'] : '';
			$comparison = isset( $condition['comparison'] ) ? $condition['comparison'] : '';

			switch ( $condition['type'] ) {
				case 'all':
					$met = true;
					break;

				case 'post_type':
					$met = get_post_type() === $query;
					break;

				case 'single_post_type':
					$met = is_singular( $query );
					break;

				case 'post_id':
					if ( $query && ! is_admin() ) {
						$met = (int) woodmart_get_the_ID() === (int) $query;
					}
					break;

				case 'single_posts_term_id':
					if ( is_singular() ) {
						$terms = wp_get_post_terms( get_the_ID(), get_taxonomies(), array( 'fields' => 'ids' ) );
						if ( $terms ) {
							$met = in_array( $query, $terms, false );
						}
					}
					break;

				case 'term_id':
					$object  = get_queried_object();
					$term_id = false;

					if ( is_object( $object ) && property_exists( $object, 'term_id' ) ) {
						$term_id = $object->term_id;
						$type    = $object->taxonomy;

						$ancestors = get_ancestors( $term_id, $type );

						if ( in_array( $query, $ancestors, false ) ) {
							$term_id = $query;
						}
					}

					if ( $term_id ) {
						$met = (int) $term_id === (int) $query;
					}
					break;

				case 'taxonomy':
					$object   = get_queried_object();
					$taxonomy = is_object( $object ) && property_exists( $object, 'taxonomy' ) ? $object->taxonomy : false;

					if ( $taxonomy ) {
						$met = $taxonomy === $query;
					}
					break;

				case 'user_role':
					$user_roles = is_user_logged_in() ? (array) wp_get_current_user()->roles : array();
					$met        = in_array( $query, $user_roles, true );
					break;

				case 'custom':
					switch ( $query ) {
						case 'search':
							$met = is_search();
							break;
						case 'blog':
							$met = (int) get_the_ID() === (int) get_option( 'page_for_posts' );
							break;
						case 'front':
							$met = (int) get_the_ID() === (int) get_option( 'page_on_front' );
							break;
						case 'archives':
							$met = is_archive();
							break;
						case 'author':
							$met = is_author();
							break;
						case 'error404':
							$met = is_404();
							break;
						case 'logged_in':
							$met = is_user_logged_in();
							break;
						case 'shop':
							if ( woodmart_woocommerce_installed() ) {
								$met = is_shop();
							}
							break;
						case 'single_product':
							if ( woodmart_woocommerce_installed() ) {
								$met = is_product();
							}
							break;
						case 'cart':
							if ( woodmart_woocommerce_installed() ) {
								$met = is_cart();
							}
							break;
						case 'checkout':
							if ( woodmart_woocommerce_installed() ) {
								$met = is_checkout();
							}
							break;
						case 'account':
							if ( woodmart_woocommerce_installed() ) {
								$met = is_account_page();
							}
							break;
						case 'is_mobile':
							$met = wp_is_mobile();
							break;
						case 'is_rtl':
							$met = is_rtl();
							break;
					}
					break;
			}

			if ( 'exclude' === $comparison ) {
				$excludes_matchs[] = $met;
			} else {
				$includes_matchs[] = $met;
			}
		}

		$is_included = ( ! empty( $includes_matchs ) && in_array( true, $includes_matchs, true ) );
		$is_excluded = ( ! empty( $excludes_matchs ) && in_array( true, $excludes_matchs, true ) );

		return $is_included && ! $is_excluded;
	}

	/**
	 * Get all IDs.
	 *
	 * Retrieves all IDs for each floating type.
	 *
	 * @return array List of IDs.
	 */
	public function get_all_ids() {
		$all_ids = array();

		foreach ( $this->block_types as $block_key => $block_type ) {
			$post_type = $block_type['post_type'];

			$ids = get_posts(
				array(
					'fields'         => 'ids',
					'posts_per_page' => apply_filters( 'woodmart_fl_block_posts_per_page', 100 ),
					'post_type'      => $post_type,
					'post_status'    => 'publish',
				)
			);

			if ( woodmart_get_opt( 'promo_popup' ) ) {
				$all_ids[] = 'legacy';
			}

			$all_ids = array_merge( $all_ids, $ids );
		}

		return $all_ids;
	}

	/**
	 * Get IDs for specific block type.
	 *
	 * @param string $block_key Block key (e.g., 'popup', 'floating-block').
	 * @return array List of IDs for the specific block type.
	 */
	public function get_ids_for_type( $block_key ) {
		$block_types = woodmart_get_config( 'fb-types' );

		if ( ! isset( $block_types[ $block_key ] ) ) {
			return array();
		}

		$post_type = $block_types[ $block_key ]['post_type'];

		$ids = get_posts(
			array(
				'fields'         => 'ids',
				'posts_per_page' => apply_filters( 'woodmart_fl_block_posts_per_page', 100 ),
				'post_type'      => $post_type,
				'post_status'    => 'publish',
			)
		);

		if ( 'popup' === $block_key && woodmart_get_opt( 'promo_popup' ) ) {
			$ids[] = 'legacy';
		}

		return $ids;
	}

	/**
	 * Get all block conditions.
	 *
	 * Retrieves conditions for all blocks by their IDs.
	 *
	 * @return array List of conditions grouped by block IDs.
	 */
	public function get_all_conditions() {
		$all_conditions = array();
		$block_types    = woodmart_get_config( 'fb-types' );

		foreach ( $block_types as $block_key => $block_type ) {
			$transient_key = $this->get_transient_key( $block_key );
			$cache         = get_transient( $transient_key );

			if ( $cache ) {
				$all_conditions += $cache;
				continue;
			}

			$ids = $this->get_ids_for_type( $block_key );

			$type_conditions = array();

			foreach ( $ids as $id ) {
				if ( 'legacy' === $id ) {
					$all_conditions['legacy'] = array();
					continue;
				}

				$conditions = $this->get_single_post_conditions( $id );

				if ( ! empty( $conditions ) ) {
					$type_conditions[ $id ] = $conditions;
				}
			}

			set_transient( $transient_key, $type_conditions );
			$all_conditions += $type_conditions;
		}

		return $all_conditions;
	}

	/**
	 * Get Gutenberg option value.
	 *
	 * @param int    $floating_id Floating block ID.
	 * @param string $option_name Option name.
	 * @return mixed
	 */
	public function get_gutenberg_option( $floating_id, $option_name ) {
		if ( strpos( $option_name, '_tablet' ) !== false ) {
			$option_name = str_replace( '_tablet', 'Tablet', $option_name );
		}

		if ( strpos( $option_name, '_mobile' ) !== false ) {
			$option_name = str_replace( '_mobile', 'Mobile', $option_name );
		}

		return get_post_meta( $floating_id, 'wd_' . $option_name, true );
	}

	/**
	 * Get transient key for block type.
	 *
	 * @param string $block_key Block key.
	 * @return string Transient key.
	 */
	private function get_transient_key( $block_key ) {
		$transient_keys = array(
			'popup'          => 'wd_all_popup_conditions',
			'floating-block' => 'wd_all_floating_block_conditions',
		);

		return isset( $transient_keys[ $block_key ] ) ? $transient_keys[ $block_key ] : 'wd_all_block_conditions';
	}

	/**
	 * Get conditions for a single floating block
	 *
	 * Retrieves the conditions for a specific floating block by its ID.
	 *
	 * @param string $id Floating block ID.
	 *
	 * @return array List of conditions for the floating block
	 */
	public function get_single_post_conditions( $id = '' ) {
		if ( 'legacy' === $id ) {
			return array();
		}

		$active_builder = $this->get_active_editor( $id );
		$conditions     = array();

		if ( 'wpb' === $active_builder ) {
			$conditions = get_post_meta( $id, 'conditions', true );
		} elseif ( 'elementor' === $active_builder ) {
			$doc               = \Elementor\Plugin::$instance->documents->get_doc_for_frontend( $id );
			$elementor_options = $doc ? $doc->get_settings_for_display() : array();
			$post              = get_post( $id );
			$conditions_key    = 'wd_fb_conditions';

			if ( $post && 'wd_popup' === $post->post_type ) {
				$conditions_key = 'wd_popup_conditions';
			}

			$conditions = isset( $elementor_options[ $conditions_key ] ) ? $elementor_options[ $conditions_key ] : array();

			if ( $conditions && is_array( $conditions ) ) {
				$filtered_conditions = array();

				foreach ( $conditions as $condition ) {
					if ( empty( $condition['type'] ) && empty( $condition['comparison'] ) ) {
						continue;
					}

					$filtered_conditions[] = array(
						'comparison' => $condition['comparison'],
						'type'       => $condition['type'],
						'query'      => isset( $condition[ 'query_' . $condition['type'] ] ) ? $condition[ 'query_' . $condition['type'] ] : '',
					);
				}

				$conditions = $filtered_conditions;
			}
		} else {
			$gutenberg_conditions = $this->get_gutenberg_option( $id, 'conditions' );

			if ( $gutenberg_conditions ) {
				foreach ( $gutenberg_conditions as $condition ) {
					if ( empty( $condition['type'] ) && empty( $condition['comparison'] ) ) {
						continue;
					}

					$conditions[] = array(
						'comparison' => $condition['comparison'],
						'type'       => $condition['type'],
						'query'      => isset( $condition[ 'query_' . $condition['type'] ] ) ? $condition[ 'query_' . $condition['type'] ] : '',
					);
				}
			}
		}

		return $conditions;
	}

	/**
	 * Get the block key by post type.
	 *
	 * @param string $post_type Post type.
	 * @return string|false Block key if found, false otherwise.
	 */
	public function get_block_key_by_post_type( $post_type ) {
		foreach ( $this->block_types as $block_key => $block_type ) {
			if ( $block_type['post_type'] === $post_type ) {
				return $block_key;
			}
		}
		return false;
	}
}

Manager::get_instance();
