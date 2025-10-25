<?php
/**
 * Review reminder class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Review_Reminder;

use XTS\Singleton;

/**
 * Review reminder class.
 */
class Admin extends Singleton {

	/**
	 * Init.
	 */
	public function init() {
		if ( ! woodmart_get_opt( 'review_reminder_enabled' ) ) {
			return;
		}

		// These hooks add the "Review Reminder" column to the WooCommerce reviews table.
		add_filter( 'woocommerce_product_reviews_table_columns', array( $this, 'add_review_status_column' ) );
		add_action( 'woocommerce_product_reviews_table_column_wd_review_reminder', array( $this, 'render_review_status_column' ) );
	}

	/**
	 * Columns header.
	 *
	 * @param array $columns Columns.
	 *
	 * @return array
	 */
	public function add_review_status_column( $columns ) {
		$columns['wd_review_reminder'] = esc_html__( 'Reminder', 'woodmart' );

		return $columns;
	}

	/**
	 * Columns content.
	 *
	 * @param \WP_Comment|mixed $item Review or reply being rendered.
	 *
	 * @return void
	 */
	public function render_review_status_column( $item ) {
		if ( 'yes' === get_comment_meta( $item->comment_ID, '_wd_review_reminder_generated', true ) ) {
			echo '<span class="dashicons dashicons-yes"></span>';
		} else {
			echo '<span class="dashicons dashicons-no"></span>';
		}
	}
}

Admin::get_instance();
