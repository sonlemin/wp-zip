<?php
/**
 * Admin class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Price_Tracker;

use XTS\Modules\Price_Tracker\List_Table\Products_Table;
use XTS\Modules\Price_Tracker\List_Table\Users_Table;
use XTS\Modules\Layouts\Main as Layouts;
use WC_Product;

/**
 * Admin class.
 */
class Admin {
	/**
	 * Instance of DB_Storage class.
	 *
	 * @var DB_Storage $db_storage - Instance of DB_Storage class.
	 */
	private $db_storage;

	/**
	 * Page slug for the price tracker admin page.
	 *
	 * @var string
	 */
	public $price_tracker_page;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
			return;
		}

		$this->db_storage = DB_Storage::get_instance();

		add_action( 'admin_init', array( $this, 'delete_price_tracker' ) );
		add_action( 'before_delete_post', array( $this->db_storage, 'unsubscribe_by_product_id' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'update_subscription_prices' ), 30 );

		add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );

		add_action( 'admin_menu', array( $this, 'register_page' ) );
	}

	/**
	 * Delete subscription from users table.
	 */
	public function delete_price_tracker() {
		// Sanitize and validate all inputs.
		$action       = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$token        = isset( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';
		$product_id   = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : 0;
		$variation_id = isset( $_GET['variation_id'] ) ? intval( $_GET['variation_id'] ) : 0;
		$security     = isset( $_GET['security'] ) ? sanitize_text_field( wp_unslash( $_GET['security'] ) ) : '';

		if (
			'woodmart_delete_price_tracker' !== $action ||
			empty( $token ) ||
			empty( $product_id ) ||
			! wp_verify_nonce( $security, 'woodmart_delete_price_tracker_' . ( $variation_id ? $variation_id : $product_id ) )
		) {
			return;
		}

		// Add proper capability check.
		$capability = apply_filters( 'woodmart_capability_menu_page', 'edit_products', 'xts-price-tracker-page' );

		if ( ! current_user_can( $capability ) ) {
			wp_die( esc_html__( 'Insufficient permissions.', 'woodmart' ) );
		}

		// Additional validation.
		if ( ! $this->db_storage->unsubscribe_by_token( $token ) ) {
			wp_die( esc_html__( 'Failed to unsubscribe.', 'woodmart' ) );
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'         => 'xts-price-tracker-page',
					'tab'          => 'users',
					'product_id'   => $product_id,
					'variation_id' => $variation_id,
				),
				admin_url( 'edit.php?post_type=product' )
			)
		);
		exit;
	}

	/**
	 * Updates the subscription prices for a given WooCommerce product.
	 *
	 * If the product is variable or a variable subscription, updates the price for each variation.
	 * Otherwise, updates the price for the simple product.
	 *
	 * @param int $post_id The ID of the product post.
	 *
	 * @return void
	 */
	public function update_subscription_prices( $post_id ) {
		if ( ! $post_id || ! is_numeric( $post_id ) ) {
			return;
		}

		$product = wc_get_product( $post_id );

		if ( ! $product || ! $product instanceof WC_Product ) {
			return;
		}

		if ( in_array( $product->get_type(), apply_filters( 'woodmart_variable_product_types', array( 'variable' ) ), true ) ) {
			$this->update_variable_product_prices( $product, $post_id );
		} else {
			$this->update_simple_product_price( $product, $post_id );
		}
	}

	/**
	 * Update prices for variable product variations.
	 *
	 * @param WC_Product $product The product object.
	 * @param int        $post_id The post ID of the product.
	 */
	private function update_variable_product_prices( $product, $post_id ) {
		$variations = $product->get_children();

		foreach ( $variations as $variation_id ) {
			$variation = wc_get_product( $variation_id );

			if ( ! $variation instanceof WC_Product ) {
				continue;
			}

			$new_price = $variation->get_price();

			if ( is_numeric( $new_price ) ) {
				$this->db_storage->update_subscription_price( $new_price, $post_id, $variation_id );
			}
		}
	}

	/**
	 * Update prices for simple products.
	 *
	 * @param WC_Product $product The product object.
	 * @param int        $post_id The post ID of the product.
	 */
	private function update_simple_product_price( $product, $post_id ) {
		$new_price = $product->get_price();

		if ( is_numeric( $new_price ) ) {
			$this->db_storage->update_subscription_price( $new_price, $post_id );
		}
	}

	/**
	 * Register page on admin panel.
	 *
	 * @return void
	 */
	public function register_page() {
		$this->price_tracker_page = add_submenu_page( // phpcs:ignore.
			'edit.php?post_type=product',
			esc_html__( 'Price tracker', 'woodmart' ),
			esc_html__( 'Price tracker', 'woodmart' ),
			apply_filters( 'woodmart_capability_menu_page', 'edit_products', 'xts-price-tracker-page' ),
			'xts-price-tracker-page',
			array( $this, 'render_page' )
		);

		add_action( 'load-' . $this->price_tracker_page, array( $this, 'price_tracker_screen_options' ) );
	}

	/**
	 * Render page on admin panel.
	 *
	 * @codeCoverageIgnore
	 */
	public function render_page() {
		$list_table = new Products_Table();

		if ( ! empty( $_GET['tab'] ) && 'users' === $_GET['tab'] ) {
			$list_table = new Users_Table();

			$product_id   = isset( $_GET['product_id'] ) ? intval( $_GET['product_id'] ) : false;
			$variation_id = isset( $_GET['variation_id'] ) ? intval( $_GET['variation_id'] ) : false;

			$product_id = $variation_id ? $variation_id : $product_id;

			if ( $product_id ) {
				$product      = wc_get_product( $product_id );
				$product_name = $product->get_name();
			}
		}

		if ( $list_table instanceof Products_Table ) {
			wp_enqueue_style( 'woocommerce_admin_styles' );
		}

		$list_table->prepare_items();
		?>
			<div class="wrap xts-post-type-table xts-pt-page-wrap">
				<h2 class="wp-heading-inline"><?php echo esc_html__( 'Price tracker', 'woodmart' ); ?></h2>

				<?php if ( ! empty( $product_name ) ) : ?>
					<h3>
						<?php echo esc_html( $product_name ); ?>
					</h3>
				<?php endif; ?>

				<form id="xts-pt-settings-page-form" method="get" action="">
					<input type="hidden" name="page" value="xts-price-tracker-page" />
					<input type="hidden" name="post_type" value="product" />
					<?php
					if ( $list_table instanceof Products_Table ) {
						$list_table->search_box( esc_html__( 'Search', 'woodmart' ), 'xts-search' );
					}

					$list_table->display();
					?>
				</form>
			</div>
		<?php
	}

	/**
	 * Add screen options to admin page.
	 */
	public function price_tracker_screen_options() {
		$screen = get_current_screen();

		if ( ! is_object( $screen ) || $screen->id !== $this->price_tracker_page ) {
			return;
		}

		add_screen_option(
			'per_page',
			array(
				'label'   => esc_html__( 'Number of items per page', 'woodmart' ),
				'default' => 20,
				'option'  => 'price_tracker_per_page',
			)
		);
	}

	/**
	 * Save screen options.
	 *
	 * @param mixed  $screen_option The value to save instead of the option value.
	 *                              Default false (to skip saving the current option).
	 * @param string $option        The option name.
	 * @param int    $value         The option value.
	 */
	public function set_screen_option( $screen_option, $option, $value ) {
		if ( 'price_tracker_per_page' === $option ) {
			return $value;
		}

		return $screen_option;
	}
}

new Admin();
