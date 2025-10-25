<?php
/**
 * Admin floating blocks class file.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Floating_Blocks;

use XTS\Singleton;
use XTS\Admin\Modules\Dashboard\Status_Button;
use XTS\Modules\Styles_Storage;

/**
 * Admin layouts class.
 */
class Admin extends Singleton {
	/**
	 * Block types.
	 *
	 * @var object
	 */
	private $manager;

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

		add_filter( 'woodmart_admin_localized_string_array', array( $this, 'add_localized_settings' ) );

		foreach ( $this->block_types as $block_key => $block_type ) {
			$post_type = $block_type['post_type'];

			add_filter( 'views_edit-' . $post_type, array( $this, 'print_interface' ) );
			add_filter( 'manage_edit-' . $post_type . '_columns', array( $this, 'admin_columns_titles' ) );
			add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'admin_columns_content' ), 10, 2 );

			new Status_Button( $post_type, 2 );
		}

		add_action( 'new_to_publish', array( $this, 'clear_transients_on_publish' ) );
		add_action( 'save_post', array( $this, 'clear_transients' ), 10, 2 );
		add_action( 'edit_post', array( $this, 'clear_transients' ), 10, 2 );
		add_action( 'deleted_post', array( $this, 'clear_transients' ), 10, 2 );
		add_action( 'woodmart_change_post_status', array( $this, 'clear_transients_on_ajax' ) );
		add_action( 'pre_delete_post', array( $this, 'delete_post' ), 10, 2 );
	}

	/**
	 * Print layout form.
	 *
	 * @param string $block_key Block key.
	 * @return string Form HTML.
	 */
	public function get_form( $block_key = 'floating-block' ) {
		ob_start();

		$block_type = $this->block_types[ $block_key ];

		$this->get_template(
			'create-form',
			array(
				'admin'      => $this,
				'block_key'  => $block_key,
				'block_type' => $block_type,
			)
		);

		return ob_get_clean();
	}

	/**
	 * Interface.
	 *
	 * @param mixed $views Default views.
	 *
	 * @return mixed
	 */
	public function print_interface( $views ) {
		wp_enqueue_script( 'wd-floating-block', WOODMART_THEME_DIR . '/inc/modules/floating-blocks/admin/assets/createForm.js', array( 'jquery' ), WOODMART_VERSION, true );

		$current_screen = get_current_screen();
		$post_type      = $current_screen->post_type;

		$block_key = '';
		foreach ( $this->block_types as $key => $type ) {
			if ( $type['post_type'] === $post_type ) {
				$block_key = $key;
				break;
			}
		}

		$this->get_template(
			'interface',
			array(
				'admin'     => $this,
				'block_key' => $block_key,
			)
		);

		return $views;
	}

	/**
	 * Get template.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Arguments for template.
	 */
	public function get_template( $template_name, $args = array() ) {
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // phpcs:ignore
		}

		include WOODMART_THEMEROOT . '/inc/modules/floating-blocks/admin/templates/' . $template_name . '.php';
	}

	/**
	 * Add localized settings.
	 *
	 * @param array $localize_data List of localized dates.
	 *
	 * @return array
	 */
	public function add_localized_settings( $localize_data ) {
		foreach ( $this->block_types as $block_key => $block_type ) {
			$localize_data[ $block_key . '_creation_error' ] = esc_html__( 'Something went wrong with the creation of the', 'woodmart' ) . ' ' . $block_type['label'] . '!';
		}

		return $localize_data;
	}

	/**
	 * Callback for post delete.
	 *
	 * @param WP_Post|false|null $delete Post ID.
	 * @param WP_Post            $post Post ID.
	 * @return void
	 */
	public function delete_post( $delete, $post ) {
		$id        = $post->ID;
		$post_type = $post->post_type;

		if ( ! $id || ! $this->manager->get_block_key_by_post_type( $post_type ) ) {
			return;
		}

		$block_key   = $this->manager->get_block_key_by_post_type( $post_type );
		$storage_key = 'floating-block-' . $id;

		if ( 'popup' === $block_key ) {
			$storage_key = 'popup-' . $id;
		}

		$storage = new Styles_Storage( $storage_key, 'post_meta', $id );
		$storage->delete_css();
		$storage->reset_data();
	}

	/**
	 * Clear transients when a wd_floating_block post is published.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function clear_transients_on_publish( $post ) {
		$post_type = $post->post_type;

		if ( ! $this->manager->get_block_key_by_post_type( $post_type ) ) {
			return;
		}

		$block_key = $this->manager->get_block_key_by_post_type( $post_type );
		$this->clear_transients_for_type( $block_key, $post->ID );
	}

	/**
	 * Clear transients for wd_floating_block post type.
	 *
	 * @param int     $post_id The post ID.
	 * @param WP_Post $post    The post object.
	 */
	public function clear_transients( $post_id, $post ) {
		$post_type = $post->post_type;

		if ( ! $this->manager->get_block_key_by_post_type( $post_type ) ) {
			return;
		}

		$block_key = $this->manager->get_block_key_by_post_type( $post_type );
		$this->clear_transients_for_type( $block_key, $post_id );
	}

	/**
	 * Clear transients for wd_floating_block post type via AJAX.
	 */
	public function clear_transients_on_ajax() {
		if ( ! wp_doing_ajax() || empty( $_POST['action'] ) || empty( $_POST['id'] ) || 'wd_change_post_status' !== $_POST['action'] ) {
			return;
		}

		$post_id = intval( $_POST['id'] );
		$post    = get_post( $post_id );

		if ( ! $post || ! $this->manager->get_block_key_by_post_type( $post->post_type ) ) {
			return;
		}

		$block_key = $this->manager->get_block_key_by_post_type( $post->post_type );
		$this->clear_transients_for_type( $block_key, $post_id );
	}

	/**
	 * Modify the columns displayed in the floating blocks admin table.
	 *
	 * @param array $columns An array of column names.
	 * @return array Modified array of column names.
	 */
	public function admin_columns_titles( $columns ) {
		unset( $columns['date'] );

		$new_columns = array(
			'wd_categories' => esc_html__( 'Categories', 'woodmart' ),
			'date'          => esc_html__( 'Date', 'woodmart' ),
		);

		$columns = $columns + $new_columns;
		return $columns;
	}

	/**
	 * Manage the custom columns for the Floating Blocks post type.
	 *
	 * @param string $column  The name of the column being displayed.
	 * @param int    $post_id The ID of the current post.
	 *
	 * @return void
	 */
	public function admin_columns_content( $column, $post_id ) {
		if ( 'wd_categories' !== $column ) {
			return;
		}

		$post_type = get_post_type( $post_id );
		$block_key = $this->manager->get_block_key_by_post_type( $post_type );
		$taxonomy  = 'wd_floating_block_cat';

		if ( 'popup' === $block_key ) {
			$taxonomy = 'wd_popup_cat';
		}

		$terms    = wp_get_post_terms( $post_id, $taxonomy );
		$keys     = array_keys( $terms );
		$last_key = end( $keys );

		if ( ! $terms ) {
			echo '—';
		}

		foreach ( $terms as $key => $term ) {
					$name = $term->name;
			if ( $key !== $last_key ) {
				$name .= ',';
			}
			?>
			<a href="<?php echo esc_url( 'edit.php?post_type=' . $post_type . '&' . $taxonomy . '=' . $term->slug ); ?>">
					<?php echo esc_html( $name ); ?>
				</a>
			<?php
		}
	}

	/**
	 * Clear transients for a specific block type.
	 *
	 * @param string $block_key Block key.
	 * @param int    $post_id   Post ID.
	 */
	private function clear_transients_for_type( $block_key, $post_id ) {
		$transient_key = 'wd_all_floating_block_conditions';
		$storage_key   = 'floating-block-' . $post_id;

		if ( 'popup' === $block_key ) {
			$transient_key = 'wd_all_popup_conditions';
			$storage_key   = 'popup-' . $post_id;
		}

		delete_transient( $transient_key );

		$storage = new Styles_Storage( $storage_key, 'post_meta', $post_id );
		$storage->delete_css();
		$storage->reset_data();
	}
}

Admin::get_instance();
