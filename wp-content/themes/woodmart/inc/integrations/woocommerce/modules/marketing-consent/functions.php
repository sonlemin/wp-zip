<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

use XTS\Modules\Marketing_Consent\DB_Storage;
use XTS\Modules\Marketing_Consent\Unsubscribed_Emails;

if ( ! function_exists( 'woodmart_should_skip_subscription_email' ) ) {
	/**
	 * Determines whether to skip sending a subscription email to the user.
	 *
	 * Checks if the email marketing consent is enabled and whether a subscription
	 * already exists for the given user email and user ID.
	 *
	 * @param string $user_email The email address of the user.
	 * @param int    $user_id    Optional. The ID of the user. Default 0.
	 *
	 * @return bool True if the subscription email should be skipped, false otherwise.
	 */
	function woodmart_should_skip_subscription_email( $user_email, $user_id = 0 ) {
		$user_email = sanitize_email( $user_email );
		$user_id    = absint( $user_id );

		if ( ! woodmart_get_opt( 'email_marketing_consent_enabled' ) || empty( $user_email ) ) {
			return false;
		}

		$storage = DB_Storage::get_instance();
		$where   = array();

		if ( 0 !== $user_id ) {
			$where = array(
				'user_id'    => $user_id,
				'user_email' => null,
			);
		} else {
			$where = array(
				'user_id'    => 0,
				'user_email' => $user_email,
			);
		}

		$subscription_exists = $storage->check_subscription_exists( $where );

		if ( 0 !== $user_id ) {
			$subscription_exists = $subscription_exists && get_userdata( $user_id )->user_email === $user_email;
		}

		return ! $subscription_exists;
	}
}

if ( ! function_exists( 'woodmart_is_user_unsubscribed_from_mailing' ) ) {
	/**
	 * Check if a user has unsubscribed from a specific mailing list.
	 *
	 * This function checks whether the given email address has unsubscribed from the specified mailing list.
	 * It supports both the new database table method and the legacy options-based method for unsubscribed users.
	 *
	 * @param string $email        The user's email address to check.
	 * @param string $mailing_name This is the name of the class that extends WC_Email.
	 *                             Example: XTS_Email_Wishlist_Back_In_Stock, XTS_Email_Abandoned_Cart.
	 *
	 * @return bool True if the user has unsubscribed from the mailing list, false otherwise.
	 */
	function woodmart_is_user_unsubscribed_from_mailing( $email, $mailing_name ) {
		if ( get_option( 'woodmart_unsubscribed_emails_migrated' ) ) {
			$unsubscribed_emails = Unsubscribed_Emails::get_instance();

			return $unsubscribed_emails->check_is_user_unsubscribed_from_mailing( $email, $mailing_name );
		}

		switch ( $mailing_name ) {
			case 'XTS_Email_Wishlist_Back_In_Stock':
			case 'XTS_Email_Wishlist_On_Sale_Products':
			case 'XTS_Email_Wishlist_Promotional':
				$unsubscribed_users = get_option( 'woodmart_wishlist_unsubscribed_users', array() );
				break;
			case 'XTS_Email_Abandoned_Cart':
				$unsubscribed_users = get_option( 'woodmart_abandoned_cart_unsubscribed_users', array() );
				break;
			default:
				$unsubscribed_users = array();
				break;
		}

		return ! empty( $unsubscribed_users ) && in_array( $email, $unsubscribed_users, true );
	}
}

if ( ! function_exists( 'woodmart_unsubscribe_user_from_mailing' ) ) {
	/**
	 * Unsubscribes a user from a specific mailing list.
	 *
	 * Depending on the migration status, this function either uses the Unsubscribed_Emails class
	 * or updates the appropriate WordPress option to store the unsubscribed email.
	 *
	 * @param string $email        The email address to unsubscribe.
	 * @param string $mailing_name The name of the mailing list to unsubscribe from.
	 *
	 * @return bool True on success, false on failure.
	 */
	function woodmart_unsubscribe_user_from_mailing( $email, $mailing_name ) {
		if ( get_option( 'woodmart_unsubscribed_emails_migrated' ) ) {
			$unsubscribed_emails = Unsubscribed_Emails::get_instance();

			return $unsubscribed_emails->insert_unsubscribed_email( $email, $mailing_name );
		}

		switch ( $mailing_name ) {
			case 'XTS_Email_Wishlist_Back_In_Stock':
			case 'XTS_Email_Wishlist_On_Sale_Products':
			case 'XTS_Email_Wishlist_Promotional':
				$option_name = 'woodmart_wishlist_unsubscribed_users';
				break;
			case 'XTS_Email_Abandoned_Cart':
				$option_name = 'woodmart_abandoned_cart_unsubscribed_users';
				break;
			default:
				$option_name = '';
				break;
		}

		if ( empty( $option_name ) ) {
			return false;
		}

		$unsubscribed_users = get_option( $option_name, array() );

		if ( ! in_array( $email, $unsubscribed_users, true ) ) {
			$unsubscribed_users[] = $email;

			update_option( $option_name, $unsubscribed_users, false );
		}

		return true;
	}
}

if ( ! function_exists( 'woodmart_subscribe_user_from_mailing' ) ) {
	/**
	 * Deletes a user's unsubscription from a specific mailing list.
	 *
	 * Depending on the migration status, this function either uses the Unsubscribed_Emails class
	 * or updates the appropriate WordPress option to remove the unsubscribed email.
	 *
	 * @param string $email        The email address to delete from unsubscription.
	 * @param string $mailing_name The name of the mailing list to delete from.
	 *
	 * @return bool True on success, false on failure.
	 */
	function woodmart_delete_user_unsubscription_from_mailing( $email, $mailing_name ) {
		if ( get_option( 'woodmart_unsubscribed_emails_migrated' ) ) {
			$unsubscribed_emails = Unsubscribed_Emails::get_instance();

			return $unsubscribed_emails->delete_user_unsubscription( $email, $mailing_name );
		}

		switch ( $mailing_name ) {
			case 'XTS_Email_Wishlist_Back_In_Stock':
			case 'XTS_Email_Wishlist_On_Sale_Products':
			case 'XTS_Email_Wishlist_Promotional':
				$option_name = 'woodmart_wishlist_unsubscribed_users';
				break;
			case 'XTS_Email_Abandoned_Cart':
				$option_name = 'woodmart_abandoned_cart_unsubscribed_users';
				break;
			default:
				$option_name = '';
				break;
		}

		if ( empty( $option_name ) ) {
			return false;
		}

		$unsubscribed_users = get_option( $option_name, array() );

		if ( in_array( $email, $unsubscribed_users, true ) ) {
			$unsubscribed_users = array_diff( $unsubscribed_users, array( $email ) );

			update_option( $option_name, $unsubscribed_users, false );
		}

		return true;
	}
}
