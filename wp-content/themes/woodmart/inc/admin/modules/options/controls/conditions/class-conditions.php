<?php
/**
 * HTML dropdown select control.
 *
 * @package woodmart
 */

namespace XTS\Admin\Modules\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Admin\Modules\Options\Field;

/**
 * Switcher field control.
 */
class Conditions extends Field {
	/**
	 * Custom inner fields.
	 *
	 * @var array
	 */
	public $custom_inner_fields = array();

	/**
	 * Construct the object.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args Field args array.
	 * @param array  $options Options from the database.
	 * @param string $type Field type.
	 * @param string $object $object Object for post or term.
	 */
	public function __construct( $args, $options, $type = 'options', $object = 'post' ) {
		parent::__construct( $args, $options, $type, $object );

		$this->set_inner_fields();

		// Select2 values for Discount Condition options.
		add_action( 'wp_ajax_wd_conditions_query', array( $this, 'conditions_query' ) );

		add_filter( 'woodmart_admin_localized_string_array', array( $this, 'add_localized_settings' ) );
	}

	/**
	 * Sets the inner fields for the current instance.
	 *
	 * This method ensures that the 'inner_fields' argument is populated with default values
	 * if not already set. If some default inner fields are missing from the current 'inner_fields',
	 * they will be added with their default values.
	 *
	 * @return void
	 */
	public function set_inner_fields() {
		$default_field = $this->get_default_inner_fields();

		if ( empty( $this->args['inner_fields'] ) ) {
			$this->args['inner_fields'] = $default_field;

			return;
		}

		$missing_fields      = array_diff( array_keys( $default_field ), array_keys( $this->args['inner_fields'] ) );
		$custom_inner_fields = array_diff( array_keys( $this->args['inner_fields'] ), array_keys( $default_field ) );

		foreach ( $missing_fields as $field_key ) {
			$this->args['inner_fields'][ $field_key ] = $default_field[ $field_key ];
		}

		foreach ( $custom_inner_fields as $field_key ) {
			$this->custom_inner_fields[ $field_key ] = $this->args['inner_fields'][ $field_key ];
		}
	}

	/**
	 * Get default conditions fields.
	 *
	 * @return array
	 */
	public function get_default_inner_fields() {
		$default_fields = array(
			'comparison'         => array(
				'name'    => esc_html__( 'Comparison condition', 'woodmart' ),
				'options' => array(
					'include' => esc_html__( 'Include', 'woodmart' ),
					'exclude' => esc_html__( 'Exclude', 'woodmart' ),
				),
			),
			'type'               => array(
				'name'    => esc_html__( 'Condition type', 'woodmart' ),
				'options' => array(
					'all'                    => esc_html__( 'All products', 'woodmart' ),
					'product'                => esc_html__( 'Single product id', 'woodmart' ),
					'product_cat'            => esc_html__( 'Product category', 'woodmart' ),
					'product_cat_children'   => esc_html__( 'Child product categories', 'woodmart' ),
					'product_tag'            => esc_html__( 'Product tag', 'woodmart' ),
					'product_attr_term'      => esc_html__( 'Product attribute', 'woodmart' ),
					'product_type'           => esc_html__( 'Product type', 'woodmart' ),
					'product_shipping_class' => esc_html__( 'Product shipping class', 'woodmart' ),
				),
			),
			'query'              => array(
				'name'     => esc_html__( 'Condition query', 'woodmart' ),
				'options'  => array(),
				'requires' => array(
					array(
						'key'     => 'type',
						'compare' => 'equals',
						'value'   => array(
							'product',
							'product_cat',
							'product_cat_children',
							'product_tag',
							'product_attr_term',
							'product_shipping_class',
						),
					),
				),
			),
			'product-type-query' => array(
				'name'     => esc_html__( 'Condition query', 'woodmart' ),
				'options'  => array(
					'simple'   => esc_html__( 'Simple product', 'woodmart' ),
					'variable' => esc_html__( 'Variable product', 'woodmart' ),
				),
				'requires' => array(
					array(
						'key'     => 'type',
						'compare' => 'equals',
						'value'   => 'product_type',
					),
				),
			),
		);

		if ( ! empty( $this->args['exclude_fields'] ) ) {
			foreach ( $this->args['exclude_fields'] as $exclude_field ) {
				if ( isset( $default_fields[ $exclude_field ] ) ) {
					unset( $default_fields[ $exclude_field ] );
				}
			}
		}

		if ( isset( $default_fields['type'] ) && taxonomy_exists( 'product_brand' ) ) {
			$default_fields['type']['options']['product_brand'] = esc_html__( 'Product brand', 'woodmart' );

			if ( isset( $default_fields['query'] ) ) {
				$default_fields['query']['requires'][] = array(
					'key'     => 'type',
					'compare' => 'equals',
					'value'   => 'product_brand',
				);
			}
		}

		return $default_fields;
	}

	/**
	 * Get data from db for render select2 options for Discount Condition options in admin page.
	 */
	public function conditions_query() {
		check_ajax_referer( 'wd-new-template-nonce', 'security' );

		$query_type = woodmart_clean( $_POST['query_type'] ); // phpcs:ignore
		$search     = isset( $_POST['search'] ) ? woodmart_clean( $_POST['search'] ) : false; // phpcs:ignore

		$items = array();

		switch ( $query_type ) {
			// Freegift, product tabs.
			case 'product_cat':
			case 'product_cat_children':
			case 'product_tag':
			case 'product_brand':
			case 'product_attr_term':
			case 'product_shipping_class':
				$taxonomy = array();

				if ( 'product_cat' === $query_type || 'product_cat_children' === $query_type ) {
					$taxonomy[] = 'product_cat';
				}
				if ( 'product_tag' === $query_type ) {
					$taxonomy[] = 'product_tag';
				}
				if ( 'product_attr_term' === $query_type ) {
					foreach ( wc_get_attribute_taxonomies() as $attribute ) {
						$taxonomy[] = 'pa_' . $attribute->attribute_name;
					}
				}
				if ( 'product_brand' === $query_type && taxonomy_exists( 'product_brand' ) ) {
					$taxonomy[] = 'product_brand';
				}
				if ( 'product_shipping_class' === $query_type ) {
					$taxonomy[] = 'product_shipping_class';
				}

				$terms = get_terms(
					array(
						'hide_empty' => false,
						'fields'     => 'all',
						'taxonomy'   => $taxonomy,
						'search'     => $search,
					)
				);

				if ( count( $terms ) > 0 ) {
					foreach ( $terms as $term ) {
						$items[] = array(
							'id'   => $term->term_id,
							'text' => $term->name . ' (ID: ' . $term->term_id . ') (Tax: ' . $term->taxonomy . ')',
						);
					}
				}
				break;
			case 'product_type':
				$product_types = wc_get_product_types();

				unset( $product_types['grouped'], $product_types['external'] );

				foreach ( $product_types as $type => $title ) {
					$items[] = array(
						'id'   => $type,
						'text' => $title,
					);
				}
				break;
			case 'product':
				$posts = get_posts(
					array(
						's'                => $search,
						'post_type'        => 'product',
						'posts_per_page'   => 100,
						'suppress_filters' => false,
					)
				);

				if ( count( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						$items[] = array(
							'id'   => $post->ID,
							'text' => $post->post_title . ' (ID: ' . $post->ID . ')',
						);
					}
				}
				break;
			// Popup builder.
			case 'post_type':
			case 'single_post_type':
				$post_types = get_post_types( array( 'public' => true ), 'objects' );
				foreach ( $post_types as $post_type ) {
					if ( $search && stripos( $post_type->label, $search ) === false ) {
						continue;
					}
					$items[] = array(
						'id'   => $post_type->name,
						'text' => $post_type->label,
					);
				}
				break;
			case 'taxonomy':
				$taxonomies = get_taxonomies(
					array(
						'public' => true,
					),
					'objects'
				);

				foreach ( $taxonomies as $taxonomy ) {
					if ( $search && stripos( $taxonomy->label, $search ) === false ) {
						continue;
					}
					$items[] = array(
						'id'   => $taxonomy->name,
						'text' => $taxonomy->label,
					);
				}
				break;
			case 'term_id':
			case 'single_posts_term_id':
				$taxonomies = get_taxonomies();

				foreach ( $taxonomies as $taxonomy ) {
					$terms = get_terms(
						array(
							'taxonomy'   => $taxonomy,
							'hide_empty' => false,
							'search'     => $search,
						)
					);

					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$items[] = array(
								'id'   => $term->term_id,
								'text' => $term->name . ' (ID: ' . $term->term_id . ')',
							);
						}
					}
				}
				break;
			case 'post_id':
				$args = array(
					'post_type'      => get_post_types( array( 'public' => true ) ),
					'posts_per_page' => 100,
					's'              => $search,
				);

				$posts = get_posts( $args );

				if ( count( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						$items[] = array(
							'id'   => $post->ID,
							'text' => $post->post_title . ' (ID:' . $post->ID . ')',
						);
					}
				}
				break;
			case 'user_role':
				$user_roles = get_editable_roles();
				foreach ( $user_roles as $user_role_id => $user_role ) {
					if ( $search && stripos( $user_role['name'], $search ) === false ) {
						continue;
					}
					$items[] = array(
						'id'   => $user_role_id,
						'text' => $user_role['name'],
					);
				}
				break;
			case 'custom':
				$custom_conditions = woodmart_get_custom_conditions_list();

				foreach ( $custom_conditions as $id => $text ) {
					if ( $search && stripos( $text, $search ) === false ) {
						continue;
					}

					$items[] = array(
						'id'   => $id,
						'text' => html_entity_decode( $text ),
					);
				}
		}

		wp_send_json(
			array(
				'results' => $items,
			)
		);
	}

	/**
	 * Get saved data from db for render selected select2 option for Discount Condition options in admin page.
	 *
	 * @param string|int $id Search for this term value.
	 * @param string     $query_type Query type.
	 *
	 * @return array
	 */
	public function get_saved_conditions_query( $id, $query_type ) {
		$item = array();

		switch ( $query_type ) {
			// Freegift, product tabs.
			case 'product_cat':
			case 'product_cat_children':
			case 'product_tag':
			case 'product_brand':
			case 'product_attr_term':
			case 'product_shipping_class':
				$taxonomy = '';

				if ( 'product_cat' === $query_type || 'product_cat_children' === $query_type ) {
					$taxonomy = 'product_cat';
				}
				if ( 'product_tag' === $query_type ) {
					$taxonomy = 'product_tag';
				}
				if ( 'product_brand' === $query_type ) {
					$taxonomy = 'product_brand';
				}
				if ( 'product_shipping_class' === $query_type ) {
					$taxonomy = 'product_shipping_class';
				}

				if ( 'product_attr_term' === $query_type ) {
					foreach ( wc_get_attribute_taxonomies() as $attribute ) {
						$term = get_term_by(
							'id',
							$id,
							'pa_' . $attribute->attribute_name
						);

						if ( ! $term || $term instanceof WP_Error ) {
							continue;
						} else {
							break;
						}
					}
				} else {
					$term = get_term_by(
						'id',
						$id,
						$taxonomy
					);
				}

				if ( ! isset( $term ) || empty( $term ) ) {
					break;
				}

				$item['id']   = $term->term_id;
				$item['text'] = $term->name . ' (ID: ' . $term->term_id . ') (Tax: ' . $term->taxonomy . ')';
				break;
			case 'product':
				$post = get_post( $id );

				$item['id']   = $post->ID;
				$item['text'] = $post->post_title . ' (ID: ' . $post->ID . ')';
				break;
			// Popup builder.
			case 'post_type':
			case 'single_post_type':
				$post_type_object = get_post_type_object( $id );

				if ( ! $post_type_object ) {
					break;
				}

				$item['id']   = $post_type_object->name;
				$item['text'] = $post_type_object->label;
				break;
			case 'taxonomy':
				$taxonomy = get_taxonomy( $id );

				if ( ! $taxonomy ) {
					break;
				}

				$item['id']   = $taxonomy->name;
				$item['text'] = $taxonomy->label;
				break;
			case 'term_id':
			case 'single_posts_term_id':
				$term = get_term( $id );

				if ( ! $term || is_wp_error( $term ) ) {
					break;
				}

				$item['id']   = $term->term_id;
				$item['text'] = $term->name . ' (ID: ' . $term->term_id . ')';
				break;
			case 'post_id':
				$post = get_post( $id );

				if ( ! $post ) {
					break;
				}

				$item['id']   = $post->ID;
				$item['text'] = $post->post_title . ' (ID: ' . $post->ID . ')';
				break;
			case 'user_role':
				$user_roles = get_editable_roles();

				if ( isset( $user_roles[ $id ] ) ) {
					$item['id']   = $id;
					$item['text'] = $user_roles[ $id ]['name'];
				}
				break;
			case 'custom':
				$custom_conditions = woodmart_get_custom_conditions_list();
				if ( isset( $custom_conditions[ $id ] ) ) {
					$item['id']   = $id;
					$item['text'] = $custom_conditions[ $id ];
				}
				break;
		}

		return $item;
	}

	/**
	 * Generates a string representing data dependencies based on the provided dependency array.
	 *
	 * Each dependency is formatted as "key:compare:value;" and concatenated into a single string.
	 * If the value is an array, it is converted to a comma-separated list.
	 *
	 * @param array $dependency Array of dependency conditions, where each item should have 'key', 'compare', and 'value'.
	 *
	 * @return string The formatted data dependency string.
	 */
	public function get_data_dependency( $dependency ) {
		$data_dependency = '';

		if ( ! empty( $dependency ) && is_array( $dependency ) ) {
			foreach ( $dependency as $dep ) {
				$data_dependency .= $dep['key'] . ':' . $dep['compare'] . ':' . ( is_array( $dep['value'] ) ? implode( ',', $dep['value'] ) : $dep['value'] ) . ';';
			}
		}

		return $data_dependency;
	}

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		$option_id                = $this->args['id'];
		$conditions               = maybe_unserialize( $this->get_field_value() );
		$selected_condition_query = array();

		if ( empty( $conditions ) ) {
			$conditions = array(
				array(
					'comparison' => 'include',
					'type'       => 'all',
				),
			);
		}

		$query_dependency      = isset( $this->args['inner_fields']['query']['requires'] ) ? $this->get_data_dependency( $this->args['inner_fields']['query']['requires'] ) : '';
		$query_attr_dependency = ! empty( $query_dependency ) ? ' data-dependency="' . esc_attr( $query_dependency ) . '"' : '';

		$product_type_query_dependency      = isset( $this->args['inner_fields']['product-type-query']['requires'] ) ? $this->get_data_dependency( $this->args['inner_fields']['product-type-query']['requires'] ) : '';
		$product_type_query_dependency_attr = ! empty( $product_type_query_dependency ) ? ' data-dependency="' . esc_attr( $product_type_query_dependency ) . '"' : '';
		?>
		<div class="xts-item-template xts-hidden">
			<div class="xts-table-controls">
				<?php if ( isset( $this->args['inner_fields']['comparison'] ) ) : ?>
				<div class="xts-comparison-condition">
					<select class="xts-comparison-condition" name="<?php echo esc_attr( $option_id . '[{{index}}][comparison]' ); ?>" aria-label="<?php esc_attr_e( 'Comparison condition', 'woodmart' ); ?>" disabled>
						<?php foreach ( $this->args['inner_fields']['comparison']['options'] as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['type'] ) ) : ?>
				<div class="xts-condition-type">
					<select class="xts-condition-type" name="<?php echo esc_attr( $option_id . '[{{index}}][type]' ); ?>" aria-label="<?php esc_attr_e( 'Condition type', 'woodmart' ); ?>" disabled>
						<?php foreach ( $this->args['inner_fields']['type']['options'] as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['query'] ) ) : ?>
				<div class="xts-condition-query xts-hidden" <?php echo $query_attr_dependency; // phpcs:ignore. ?>>
					<select class="xts-condition-query" name="<?php echo esc_attr( $option_id . '[{{index}}][query]' ); ?>" placeholder="<?php esc_attr_e( 'Start typing...', 'woodmart' ); ?>" aria-label="<?php esc_attr_e( 'Condition query', 'woodmart' ); ?>" disabled></select>
				</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['product-type-query'] ) ) : ?>
				<div class="xts-product-type-condition-query xts-hidden" <?php echo $product_type_query_dependency_attr; // phpcs:ignore. ?>>
					<select class="xts-product-type-condition-query" name="<?php echo esc_attr( $option_id . '[{{index}}][product-type-query]' ); ?>" aria-label="<?php esc_attr_e( 'Product type condition query', 'woodmart' ); ?>" disabled>
						<?php foreach ( $this->args['inner_fields']['product-type-query']['options'] as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $this->custom_inner_fields ) ) : ?>
					<?php foreach ( $this->custom_inner_fields as $field_key => $field_data ) : ?>
						<?php
							$field_class           = 'xts-condition-' . $field_key;
							$field_dependency      = isset( $field_data['requires'] ) ? $this->get_data_dependency( $field_data['requires'] ) : '';
							$field_dependency_attr = ! empty( $field_dependency ) ? ' data-dependency="' . esc_attr( $field_dependency ) . '"' : '';
						?>

						<div class="<?php echo esc_attr( $field_class ); ?> xts-hidden" <?php echo $field_dependency_attr; // phpcs:ignore. ?>>
							<select class="<?php echo esc_attr( $field_class ); ?>" name="<?php echo esc_attr( $option_id . '[{{index}}][' . $field_key . ']' ); ?>" aria-label="<?php echo esc_attr( $field_data['name'] ); ?>" disabled>
								<?php foreach ( $this->custom_inner_fields[ $field_key ]['options'] as $key => $label ) : ?>
									<option value="<?php echo esc_attr( $key ); ?>">
										<?php echo esc_html( $label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<div class="xts-close">
					<a href="#" class="xts-remove-item xts-bordered-btn xts-color-warning xts-style-icon xts-i-close"></a>
				</div>
			</div>
		</div>

		<div class="xts-controls-wrapper">
			<div class="xts-table-controls xts-table-heading">
				<?php if ( isset( $this->args['inner_fields']['comparison'] ) ) : ?>
					<div class="xts-comparison-condition">
						<label><?php esc_html_e( 'Comparison condition', 'woodmart' ); ?></label>
					</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['type'] ) ) : ?>
					<div class="xts-condition-type">
						<label><?php esc_html_e( 'Condition type', 'woodmart' ); ?></label>
					</div>

					<?php
					$show_query_title = false;

					foreach ( $conditions as $id => $condition_args ) {
						if ( isset( $condition_args['type'] ) && 'all' !== $condition_args['type'] ) {
							$show_query_title = true;
							break;
						}
					}
					?>
					<div class="xts-condition-query <?php echo $show_query_title ? '' : 'xts-hidden'; ?>">
						<label><?php esc_html_e( 'Condition query', 'woodmart' ); ?></label>
					</div>
				<?php endif; ?>
				
				<div class="xts-close"></div>
			</div>
			<?php foreach ( $conditions as $id => $condition_args ) : //phpcs:ignore. ?>
				<?php
				$show_query_field = false;

				if ( isset( $condition_args['type'] ) && 'all' !== $condition_args['type'] ) {
					$show_query_field = true;
				}

				if ( ! empty( $condition_args['query'] ) && ! empty( $condition_args['type'] ) ) {
					$selected_condition_query = $this->get_saved_conditions_query( $condition_args['query'], $condition_args['type'] );
				}
				?>
				<div class="xts-table-controls">
				<?php if ( isset( $this->args['inner_fields']['comparison'] ) ) : ?>
					<div class="xts-comparison-condition">
						<select class="xts-comparison-condition" name="<?php echo esc_attr( $option_id . '[' . $id . '][comparison]' ); ?>" aria-label="<?php esc_attr_e( 'Comparison condition', 'woodmart' ); ?>">
							<?php foreach ( $this->args['inner_fields']['comparison']['options'] as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $conditions[ $id ]['comparison'] ) ? selected( $conditions[ $id ]['comparison'], $key, false ) : ''; ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['type'] ) ) : ?>
					<div class="xts-condition-type">
						<select class="xts-condition-type" name="<?php echo esc_attr( $option_id . '[' . $id . '][type]' ); ?>" aria-label="<?php esc_attr_e( 'Condition type', 'woodmart' ); ?>">
							<?php foreach ( $this->args['inner_fields']['type']['options'] as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $conditions[ $id ]['type'] ) ? selected( $conditions[ $id ]['type'], $key, false ) : ''; ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['query'] ) ) : ?>
					<div class="xts-condition-query <?php echo $show_query_field ? '' : 'xts-hidden'; ?>" <?php echo $query_attr_dependency; // phpcs:ignore. ?>>
						<select class="xts-condition-query" name="<?php echo esc_attr( $option_id . '[' . $id . '][query]' ); ?>" placeholder="<?php echo esc_attr__( 'Start typing...', 'woodmart' ); ?>" aria-label="<?php esc_attr_e( 'Condition query', 'woodmart' ); ?>">
							<?php if ( ! empty( $selected_condition_query ) ) : ?>
								<option value="<?php echo esc_attr( $selected_condition_query['id'] ); ?>" selected>
									<?php echo esc_html( $selected_condition_query['text'] ); ?>
								</option>
							<?php endif; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( isset( $this->args['inner_fields']['product-type-query'] ) ) : ?>
					<div class="xts-product-type-condition-query <?php echo isset( $conditions[ $id ] ) && ( 'product_type' !== $conditions[ $id ]['type'] || ! isset( $conditions[ $id ]['product-type-query'] ) ) || ! isset( $conditions[ $id ] ) ? 'xts-hidden' : ''; ?>" <?php echo $product_type_query_dependency_attr; // phpcs:ignore. ?>>
						<select class="xts-product-type-condition-query" name="<?php echo esc_attr( $option_id . '[' . $id . '][product-type-query]' ); ?>" aria-label="<?php esc_attr_e( 'Product type condition query', 'woodmart' ); ?>">
							<?php foreach ( $this->args['inner_fields']['product-type-query']['options'] as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $conditions[ $id ]['product-type-query'] ) ? selected( $conditions[ $id ]['product-type-query'], $key, false ) : ''; ?>>
									<?php echo esc_html( $label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

					<?php if ( ! empty( $this->custom_inner_fields ) ) : ?>
						<?php foreach ( $this->custom_inner_fields as $field_key => $field_data ) : ?>
							<?php
							$field_class           = 'xts-condition-' . $field_key;
							$field_classes         = $field_class;
							$field_dependency      = isset( $field_data['requires'] ) ? $this->get_data_dependency( $field_data['requires'] ) : '';
							$field_dependency_attr = ! empty( $field_dependency ) ? ' data-dependency="' . esc_attr( $field_dependency ) . '"' : '';

							$show_field = false;

							foreach ( $field_data['requires'] as $dep ) {
								if ( 'equals' === $dep['compare'] ) {
									$show_field = $dep['value'] === $conditions[ $id ][ $dep['key'] ];
								} else {
									$show_field = $dep['value'] !== $conditions[ $id ][ $dep['key'] ];
								}
							}

							if ( ! $show_field || ! isset( $conditions[ $id ][ $field_key ] ) ) {
								$field_classes .= ' xts-hidden';
							}
							?>

							<div class="<?php echo esc_attr( $field_classes ); ?>" <?php echo $field_dependency_attr; // phpcs:ignore. ?>>
								<select class="<?php echo esc_attr( $field_class ); ?>" name="<?php echo esc_attr( $option_id . '[' . $id . '][' . $field_key . ']' ); ?>" aria-label="<?php echo esc_attr( $field_data['name'] ); ?>">
									<?php foreach ( $this->custom_inner_fields[ $field_key ]['options'] as $key => $label ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php echo isset( $conditions[ $id ][ $field_key ] ) ? selected( $conditions[ $id ][ $field_key ], $key, false ) : ''; ?>>
											<?php echo esc_html( $label ); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>

					<div class="xts-close">
						<a href="#" class="xts-remove-item xts-bordered-btn xts-color-warning xts-style-icon xts-i-close"></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<a href="#" class="xts-add-row xts-inline-btn xts-color-primary xts-i-add">
			<?php esc_html_e( 'Add new condition', 'woodmart' ); ?>
		</a>
		<?php
	}

	/**
	 * Enqueue lib.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'wd-cont-table-control', WOODMART_ASSETS . '/css/parts/cont-table-control.min.css', array(), WOODMART_VERSION );

		wp_enqueue_script( 'select2', WOODMART_ASSETS . '/js/libs/select2.full.min.js', array(), woodmart_get_theme_info( 'Version' ), true );
		wp_enqueue_script( 'woodmart-admin-options', WOODMART_ASSETS . '/js/options.js', array(), WOODMART_VERSION, true );
		wp_enqueue_script( 'wd-conditions', WOODMART_ASSETS . '/js/conditions.js', array(), woodmart_get_theme_info( 'Version' ), true );
	}

	/**
	 * Add localized settings.
	 *
	 * @param array $localize_data List of localized dates.
	 *
	 * @return array
	 */
	public function add_localized_settings( $localize_data ) {
		$localize_data['no_discount_condition'] = esc_html__( 'At least one condition is required.', 'woodmart' );

		return $localize_data;
	}
}
