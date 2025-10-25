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
class Frontend extends Singleton {

	/**
	 * Init.
	 */
	public function init() {
		if ( ! woodmart_get_opt( 'review_reminder_enabled' ) ) {
			return;
		}

		// Add and save custom review meta data to mark this review as having been added via email.
		add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'add_custom_review_field' ) );
		add_action( 'comment_post', array( $this, 'save_custom_review_meta' ) );
	}

	/**
	 * Adds a custom hidden field to the WooCommerce review comment form to indicate
	 * whether the review was generated via the review reminder feature.
	 *
	 * @param array $comment_form The existing comment form fields.
	 *
	 * @return array The modified comment form fields with the custom hidden field added.
	 */
	public function add_custom_review_field( $comment_form ) {
		$value = 'no';

		if ( isset( $_GET['action'] ) && 'wd_review_reminder' === $_GET['action'] ) {
			$value = 'yes';
		}

		$comment_form['comment_field'] .= '<input type="hidden" name="_wd_review_reminder_generated" value="' . $value . '">';

		return $comment_form;
	}

	/**
	 * Saves custom review meta data when a WooCommerce product review is submitted.
	 *
	 * @param int $comment_id The ID of the comment being saved.
	 */
	public function save_custom_review_meta( $comment_id ) {
		$comment = get_comment( $comment_id );

		if ( 'review' === $comment->comment_type && isset( $_POST['_wd_review_reminder_generated'] ) ) {
			add_comment_meta( $comment_id, '_wd_review_reminder_generated', sanitize_text_field( $_POST['_wd_review_reminder_generated'] ) );
		}
	}
}

Frontend::get_instance();
