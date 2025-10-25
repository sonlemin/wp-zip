<?php
/**
 * Unsubscribed Emails class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Marketing_Consent;

use XTS\Singleton;

/**
 * Database storage class.
 */
class Unsubscribed_Emails extends Singleton {
	/**
	 * Unsubscribed_emails table name.
	 *
	 * @const string
	 */
	const UNSUBSCRIBED_EMAILS_TABLE = 'woodmart_unsubscribed_emails';

	/**
	 * Initialize hooks and table definitions.
	 */
	public function init() {
		self::define_tables();

		add_action( 'woodmart_updated', array( __CLASS__, 'migrate' ) );
	}

	/**
	 * Check if a user has unsubscribed from a specific mailing list.
	 *
	 * @param string $email        The user's email address.
	 * @param string $mailing_name The name of the mailing list.
	 *
	 * @return bool True if the user has unsubscribed from the mailing list, false otherwise.
	 */
	public function check_is_user_unsubscribed_from_mailing( $email, $mailing_name ) {
		global $wpdb;

		if ( ! get_option( 'woodmart_unsubscribed_emails_table_created' ) ) {
			return false;
		}

		$result = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->wd_unsubscribed_emails} WHERE user_email = %s AND unsubscribed_email_name = %s",
				$email,
				$mailing_name
			)
		);

		return $result > 0;
	}

	/**
	 * Inserts an unsubscribed email record into the database.
	 *
	 * @param string $email        The email address to unsubscribe.
	 * @param string $mailing_name The name of the mailing list or email campaign.
	 *
	 * @return int|false Number of rows inserted, or false on error.
	 */
	public function insert_unsubscribed_email( $email, $mailing_name ) {
		global $wpdb;

		$result = $wpdb->insert(
			$wpdb->wd_unsubscribed_emails,
			array(
				'user_email'              => $email,
				'unsubscribed_email_name' => $mailing_name,
				'unsubscription_date'     => current_time( 'mysql' ),
				'unsubscription_date_gmt' => current_time( 'mysql', true ),
			),
			array( '%s', '%s', '%s', '%s' )
		);

		return $result;
	}

	/**
	 * Deletes a user's unsubscription record from the database for a specific mailing list.
	 *
	 * @param string $email        The user's email address.
	 * @param string $mailing_name The name of the mailing list/email.
	 *
	 * @return int|\WP_Error Number of rows deleted on success, or WP_Error on failure.
	 */
	public function delete_user_unsubscription( $email, $mailing_name ) {
		global $wpdb;

		$result = $wpdb->delete(
			$wpdb->wd_unsubscribed_emails,
			array(
				'user_email'              => $email,
				'unsubscribed_email_name' => $mailing_name,
			)
		);

		if ( false === $result ) {
			$mailer       = WC()->mailer();
			$email_object = isset( $mailer->emails[ $mailing_name ] ) ? $mailer->emails[ $mailing_name ] : null;
			$email_title  = $email_object && isset( $email_object->title ) ? $email_object->title : $mailing_name;

			return new \WP_Error(
				'db_insert_error',
				sprintf(
					/* translators: %s: email title */
					__( 'Failed to subscribe to the mailing list: "%s"', 'woodmart' ),
					$email_title
				),
				$wpdb->last_error
			);
		}

		return $result;
	}

	/**
	 * Define custom table aliases for $wpdb.
	 */
	public static function define_tables() {
		global $wpdb;

		$wpdb->wd_unsubscribed_emails = $wpdb->prefix . self::UNSUBSCRIBED_EMAILS_TABLE;
		$wpdb->tables[]               = self::UNSUBSCRIBED_EMAILS_TABLE;
	}

	/**
	 * Create the unsubscribed emails table if it does not exist.
	 */
	public static function install() {
		global $wpdb;

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . self::UNSUBSCRIBED_EMAILS_TABLE;
		$sql             = "CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			user_email VARCHAR(100) NOT NULL,
			unsubscribed_email_name VARCHAR(100) NOT NULL,
			unsubscription_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			unsubscription_date_gmt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
			) $charset_collate;";

		$result = dbDelta( $sql );

		if ( is_array( $result ) && in_array( $wpdb->wd_unsubscribed_emails, array_keys( $result ), true ) ) {
			update_option( 'woodmart_unsubscribed_emails_table_created', true, false );
		}
	}

	/**
	 * Migrate old unsubscribed emails options to the new table.
	 */
	public static function unsubscribed_emails_migrate() {
		global $wpdb;

		if ( ! get_option( 'woodmart_unsubscribed_emails_table_created' ) ) {
			return;
		}

		$wishlist_emails       = array_unique( (array) get_option( 'woodmart_wishlist_unsubscribed_users', array() ) );
		$abandoned_cart_emails = array_unique( (array) get_option( 'woodmart_abandoned_cart_unsubscribed_users', array() ) );

		$insert_rows = array();
		$now         = current_time( 'mysql' );
		$now_gmt     = current_time( 'mysql', true );

		// Prepare wishlist unsubscribes.
		$wishlist_types = array(
			'XTS_Email_Wishlist_Back_In_Stock',
			'XTS_Email_Wishlist_On_Sale_Products',
			'XTS_Email_Wishlist_Promotional',
		);

		foreach ( $wishlist_emails as $email ) {
			foreach ( $wishlist_types as $type ) {
				$insert_rows[] = $wpdb->prepare(
					'(%s, %s, %s, %s)',
					$email,
					$type,
					$now,
					$now_gmt
				);
			}
		}

		// Prepare abandoned cart unsubscribes.
		foreach ( $abandoned_cart_emails as $email ) {
			$insert_rows[] = $wpdb->prepare(
				'(%s, %s, %s, %s)',
				$email,
				'XTS_Email_Abandoned_Cart',
				$now,
				$now_gmt
			);
		}

		if ( ! empty( $insert_rows ) ) {
			$query  = "INSERT IGNORE INTO {$wpdb->wd_unsubscribed_emails} (user_email, unsubscribed_email_name, unsubscription_date, unsubscription_date_gmt) VALUES ";
			$query .= implode( ', ', $insert_rows );

			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$result = $wpdb->query( $query );

			if ( false === $result ) {
				if ( function_exists( 'wc_get_logger' ) ) {
					$logger = wc_get_logger();
					$logger->error( 'Failed to migrate unsubscribed emails: ' . $wpdb->last_error, array( 'source' => 'woodmart-unsubscribed-emails' ) );
				} else {
					error_log( 'Failed to migrate unsubscribed emails: ' . $wpdb->last_error );
				}

				return;
			}
		}

		update_option( 'woodmart_unsubscribed_emails_migrated', true, false );
	}

	/**
	 * Handles the migration process for unsubscribed emails.
	 *
	 * - Ensures the current user is an admin with the required capability.
	 * - Installs the unsubscribed emails table if it hasn't been created.
	 * - Migrates unsubscribed emails if migration hasn't occurred yet.
	 *
	 * @return void
	 */
	public static function migrate() {
		if ( ! get_option( 'woodmart_unsubscribed_emails_table_created' ) ) {
			self::install();
		}

		if ( ! get_option( 'woodmart_unsubscribed_emails_migrated' ) ) {
			self::unsubscribed_emails_migrate();
		}
	}
}

Unsubscribed_Emails::get_instance();
