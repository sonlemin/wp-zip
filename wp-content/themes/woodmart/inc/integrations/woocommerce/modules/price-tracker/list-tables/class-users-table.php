<?php
/**
 * This file describes class for render view price tracker in WordPress admin panel.
 *
 * @package Woodmart.
 */

namespace XTS\Modules\Price_Tracker\List_Table;

use XTS\Modules\Price_Tracker\DB_Storage;
use WP_List_Table;
use WP_User;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Create a new table class that will extend the WP_List_Table.
 */
class Users_Table extends WP_List_Table {
	/**
	 * DB_Storage instance.
	 *
	 * @var DB_Storage
	 */
	protected $db_storage;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
			return;
		}

		parent::__construct();
	}

	/**
	 * Define what data to show on each column of the table.
	 *
	 * @param array  $item        Data.
	 * @param string $column_name - Current column name.
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		return array_key_exists( $column_name, $item ) ? esc_html( $item[ $column_name ] ) : '';
	}

	/**
	 * Prints checkbox column.
	 *
	 * @param array $item Item to use to print record.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="users_emails[]" value="%1$s" />',
			$item['user_email']
		);
	}

	/**
	 * Print column for user thumbnail.
	 *
	 * @param array $item Item for the current record.
	 * @return string Column content
	 */
	public function column_thumb( $item ) {
		$avatar   = get_avatar( $item['user_id'], 40 );
		$edit_url = get_edit_user_link( $item['user_id'] );

		return sprintf( '<a href="%s">%s</a>', $edit_url, $avatar );
	}

	/**
	 * Print column for username
	 *
	 * @param array $item Item for the current record.
	 * @return string Column content
	 */
	public function column_name( $item ) {
		$user          = get_user( $item['user_id'] );
		$user_name     = '';
		$user_edit_url = '';

		if ( empty( $item['user_id'] ) || ! $user instanceof WP_User ) {
			$user_name = esc_html__( 'guest', 'woodmart' );
		} else {
			$user_name     = esc_html( $user->user_login );
			$user_edit_url = get_edit_user_link( $item['user_id'] );
		}

		$actions    = array();
		$view_url   = '';
		$delete_url = add_query_arg(
			array(
				'action'       => 'woodmart_delete_price_tracker',
				'token'        => $item['unsubscribe_token'],
				'product_id'   => $_GET['product_id'],
				'variation_id' => $_GET['variation_id'],
			),
			wp_nonce_url( admin_url( 'edit.php?post_type=product' ), 'woodmart_delete_price_tracker_' . ( ! empty( $_GET['variation_id'] ) ? $_GET['variation_id'] : $_GET['product_id'] ), 'security' )
		);

		if ( '0' !== strval( $item['user_id'] ) ) {
			$view_url = esc_url(
				add_query_arg(
					array(
						'page'     => 'xts-price-tracker-page',
						'_user_id' => $item['user_id'],
					),
					admin_url( 'edit.php?post_type=product' )
				)
			);
		} elseif ( ! empty( $item['user_email'] ) ) {
			$view_url = esc_url(
				add_query_arg(
					array(
						'page'        => 'xts-price-tracker-page',
						'_user_email' => $item['user_email'],
					),
					admin_url( 'edit.php?post_type=product' )
				)
			);
		}

		$actions['ID'] = sprintf( 'ID: %s', esc_html( $item['user_id'] ) );

		if ( ! empty( $view_url ) ) {
			$actions['view'] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $view_url ),
				esc_html__( 'View subscriptions', 'woodmart' )
			);
		}

		$actions['delete'] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $delete_url ),
			esc_html__( 'Delete subscription', 'woodmart' )
		);

		$row_actions = $this->row_actions( $actions );

		ob_start();
		?>
			<strong>
				<?php if ( ! empty( $user_edit_url ) ) : ?>
					<a class="row-title" href="<?php echo esc_url( $user_edit_url ); ?>">
				<?php endif; ?>

				<?php echo esc_html( $user_name ); ?>

				<?php if ( ! empty( $user_edit_url ) ) : ?>
					</a>
				<?php endif; ?>
			</strong>
			<?php echo wp_kses_post( $row_actions ); ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Prints column for user email.
	 *
	 * @param array $item Item to use to print record.
	 *
	 * @return string
	 */
	public function column_email( $item ) {
		return esc_html( $item['user_email'] );
	}

	/**
	 * Prints column for desired price.
	 *
	 * @param array $item Item to use to print record.
	 * @return string
	 */
	public function column_desired_price( $item ) {
		if ( empty( $item['desired_price'] ) ) {
			return '<span class="dashicons dashicons-minus"></span>';
		}

		return wp_kses_post( wc_price( $item['desired_price'] ) );
	}

	/**
	 * Prints date created column.
	 *
	 * @param array $item Item to use to print record.
	 * @return string
	 */
	public function column_date_created( $item ) {
		$date_created = strtotime( $item['created_date'] );
		$time_diff    = time() - $date_created;

		if ( $time_diff < DAY_IN_SECONDS ) {
			// translators: 1. Date diff since subscription creation (EG: 1 hour, 2 seconds, etc...).
			$row = sprintf( esc_html__( '%s ago', 'woodmart' ), human_time_diff( $date_created ) );
		} else {
			$row = date_i18n( wc_date_format(), $date_created );
		}

		return $row;
	}

	/**
	 * Override the parent columns method.
	 * Defines the columns to use in your listing table.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'    => '<input type="checkbox" />',
			'thumb' => '<span class="wc-image tips" data-tip="' . esc_attr__( 'Image', 'woodmart' ) . '">' . esc_html__( 'Image', 'woodmart' ) . '</span>',
			'name'  => esc_html__( 'Name', 'woodmart' ),
			'email' => esc_html__( 'Email', 'woodmart' ),
		);

		if ( woodmart_get_opt( 'price_tracker_desired_price' ) ) {
			$columns['desired_price'] = esc_html__( 'Desired price', 'woodmart' );
		}

		$columns['date_created'] = esc_html__( 'Date created', 'woodmart' );

		return $columns;
	}

	/**
	 * Define which columns are hidden.
	 *
	 * @return array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Define the sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'date_created' => array( 'created_date', false ),
		);
	}

	/**
	 * Sets bulk actions for table.
	 *
	 * @return array Array of available actions.
	 */
	public function get_bulk_actions() {
		return array(
			'delete' => esc_html__( 'Delete', 'woodmart' ),
		);
	}

	/**
	 * Delete price tracker on bulk action.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) ) { // phpcs:ignore.
			return;
		}

		// Detect when a bulk action is being triggered...
		$users_emails = isset( $_REQUEST['users_emails'] ) ? array_map( 'sanitize_email', (array) $_REQUEST['users_emails'] ) : false;
		$product_id   = isset( $_REQUEST['product_id'] ) ? intval( $_REQUEST['product_id'] ) : false;
		$variation_id = isset( $_REQUEST['variation_id'] ) ? intval( $_REQUEST['variation_id'] ) : false;

		if ( 'delete' === $this->current_action() && ! empty( $users_emails ) && ! empty( $product_id ) ) {
			foreach ( $users_emails as $users_email ) {
				try {
					$product_id = $variation_id ? $variation_id : $product_id;

					$this->db_storage->unsubscribe_by_user_email_and_product_id( $product_id, $users_email );
				} catch ( Exception $e ) {
					continue;
				}
			}

			wp_safe_redirect(
				add_query_arg(
					array(
						'page'         => 'xts-price-tracker-page',
						'tab'          => 'users',
						'product_id'   => $_REQUEST['product_id'],
						'variation_id' => $_REQUEST['variation_id'],
					),
					admin_url( 'edit.php?post_type=product' )
				)
			);
			die();
		}
	}

	/**
	 * Prepare the items for the table to process.
	 *
	 * @return void
	 */
	public function prepare_items() {
		$this->db_storage = DB_Storage::get_instance();
		$columns          = $this->get_columns();
		$hidden           = $this->get_hidden_columns();
		$sortable         = $this->get_sortable_columns();
		$user_id          = get_current_user_id();

		$data = $this->table_data();

		$order_by = 'created_date';
		$order    = 'desc';

		// If orderby is set, use this as the sort column.
		if ( ! empty( $_GET['orderby'] ) ) { // phpcs:ignore.
			$order_by = $_GET['orderby']; // phpcs:ignore.
		}

		// If order is set use this as the order.
		if ( ! empty( $_GET['order'] ) ) { // phpcs:ignore.
			$order = $_GET['order']; // phpcs:ignore.
		}

		woodmart_sort_data( $data, $order_by, $order );

		$per_page     = ! empty( get_user_meta( $user_id, 'price_tracker_per_page', true ) ) ? get_user_meta( $user_id, 'price_tracker_per_page', true ) : 20;
		$current_page = $this->get_pagenum();
		$total_items  = count( $data );

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;

		$this->process_bulk_action();
	}

	/**
	 * Get the table data.
	 *
	 * @return array
	 */
	private function table_data() {
		global $wpdb;

		$where_query  = array();
		$product_id   = isset( $_REQUEST['product_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['product_id'] ) ) : false; // phpcs:ignore.
		$variation_id = isset( $_REQUEST['variation_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['variation_id'] ) ) : false; // phpcs:ignore.

		if ( empty( $product_id ) && empty( $variation_id ) ) {
			return array();
		}

		if ( $product_id ) {
			$where_query[] = $wpdb->prepare( "$wpdb->wd_price_tracker.`product_id` = %d", intval( $product_id ) );
		}

		if ( $variation_id ) {
			$where_query[] = $wpdb->prepare( "$wpdb->wd_price_tracker.`variation_id` = %d", intval( $variation_id ) );
		}

		$where_query_text = ! empty( $where_query ) ? ' WHERE ' . implode( ' AND ', $where_query ) : '';

		if ( ! wp_cache_get( 'wd_price_tracker_users_table_data' ) ) {
			wp_cache_set(
				'wd_price_tracker_users_table_data',
				$wpdb->get_results( //phpcs:ignore;
					"SELECT
						$wpdb->wd_price_tracker.`user_id`,
						$wpdb->wd_price_tracker.`user_email`,
						$wpdb->wd_price_tracker.`desired_price`,
						$wpdb->wd_price_tracker.`unsubscribe_token`,
						$wpdb->wd_price_tracker.`created_date_gmt` as `created_date`
					FROM $wpdb->wd_price_tracker"
					. $where_query_text .
					';',
					ARRAY_A
				)
			);
		}

		return wp_cache_get( 'wd_price_tracker_users_table_data' );
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * @param string $which Position of the extra controls (top or bottom).
	 */
	protected function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
			if ( isset( $_GET['tab'] ) && 'users' === $_GET['tab'] ) {
				echo '<input type="hidden" name="tab" value="users" />';
			}
			if ( isset( $_GET['product_id'] ) ) {
				echo '<input type="hidden" name="product_id" value="' . esc_attr( $_GET['product_id'] ) . '" />';
			}
			if ( isset( $_GET['variation_id'] ) ) {
				echo '<input type="hidden" name="variation_id" value="' . esc_attr( $_GET['variation_id'] ) . '" />';
			}
		}
	}
}
