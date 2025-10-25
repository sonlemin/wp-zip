<?php
/**
 * Database storage.
 *
 * @package woodmart
 */

namespace XTS\Modules\Marketing_Consent;

use XTS\Singleton;
use WC_Product;
use WP_Error;

/**
 * Database storage class.
 */
class DB_Storage extends Singleton {
	/**
	 * Marketing consent table name.
	 *
	 * @const string
	 */
	const MARKETING_CONSENT_TABLE = 'woodmart_marketing_consent';

	/**
	 * List of allowed fields for email subscription consent data storage.
	 *
	 * These fields represent the columns that are permitted to be stored or manipulated
	 * in the email marketing consent's database. Each field corresponds to a specific
	 * attribute related to user consent for email subscriptions.
	 *
	 * @var array
	 */
	protected static $allowed_fields = array(
		'user_id',
		'user_email',
		'consent_type',
		'consent_status',
		'confirmation_token',
		'consent_date',
		'consent_date_gmt',
		'id',
	);

	/**
	 * Constructor.
	 */
	public function init() {
		self::define_tables();

		if ( ! get_option( 'woodmart_marketing_consent_installed' ) ) {
			add_action( 'admin_init', array( __CLASS__, 'install' ), 100 );
		}
	}

	/**
	 * Adds a new marketing email subscription for the given email address and user ID.
	 *
	 * Checks if a subscription already exists for the provided email and user ID.
	 * If not, inserts a new subscription record into the database with consent details.
	 *
	 * @param string|int $value Email або user_id.
	 *
	 * @return int|WP_Error The inserted subscription ID on success, or WP_Error on failure.
	 */
	public function add_subscription( $value ) {
		global $wpdb;

		if ( ! is_numeric( $value ) && ! is_email( $value ) ) {
			return new WP_Error( 'invalid_argument', __( 'Invalid argument for subscription.', 'woodmart' ) );
		}

		$checked_data = array();
		$data         = array(
			'consent_date'     => current_time( 'mysql' ),
			'consent_date_gmt' => current_time( 'mysql', true ),
		);

		if ( is_numeric( $value ) ) {
			$checked_data['user_id'] = $value;
			$data['user_id']         = intval( $value );
			$data['user_email']      = null;
		} else {
			$checked_data['user_email'] = $value;
			$data['user_id']            = 0;
			$data['user_email']         = sanitize_email( $value );
		}

		// Check if subscription already exists for this email.
		if ( $this->check_subscription_exists( $checked_data ) ) {
			return new WP_Error( 'email_exists', __( 'A subscription to marketing emails and updates for this email address is already in place.', 'woodmart' ) );
		}

		$result = $wpdb->insert( $wpdb->wd_marketing_consent, $data );

		if ( false === $result ) {
			return new WP_Error( 'db_insert_error', __( 'Could not save your subscription to marketing emails and updates', 'woodmart' ), $wpdb->last_error );
		}

		return $wpdb->insert_id;
	}

	/**
	 * Retrieves subscription data from the marketing consent table based on the specified selection and conditions.
	 *
	 * @param string $select The column(s) to select in the query.
	 * @param array  $where  The conditions to apply in the WHERE clause.
	 *
	 * @return mixed The value retrieved from the database, or null if not found.
	 */
	public function get_subscription_data( $select, $where ) {
		global $wpdb;

		if ( ! in_array( $select, self::$allowed_fields, true ) ) {
			return new WP_Error( 'invalid_field', __( 'Invalid field requested.', 'woodmart' ) );
		}

		$where  = $this->get_where_str( $where );
		$result = $wpdb->get_var( "SELECT {$select} FROM {$wpdb->wd_marketing_consent} WHERE {$where}" );

		return $result;
	}

	/**
	 * Updates a subscription record in the marketing consent database table.
	 *
	 * @param array $data  Associative array of column => value pairs to update.
	 * @param array $where Associative array of WHERE conditions for the update.
	 *
	 * @return int|WP_Error Number of rows updated, or WP_Error on failure.
	 */
	public function update_subscription( $data, $where ) {
		global $wpdb;

		$result = $wpdb->update( $wpdb->wd_marketing_consent, $data, $where );

		if ( false === $result ) {
			return new WP_Error( 'db_insert_error', __( 'Could not update your subscription to marketing emails and updates', 'woodmart' ), $wpdb->last_error );
		}

		return $result;
	}

	/**
	 * Deletes a marketing email subscription for a given email and user ID.
	 *
	 * @param array $where Associative array of WHERE conditions for the delete.
	 *
	 * @return int|WP_Error   Number of rows deleted, or WP_Error on failure.
	 */
	public function delete_subscription( $where ) {
		global $wpdb;

		$result = $wpdb->delete( $wpdb->wd_marketing_consent, $where );

		if ( false === $result ) {
			return new WP_Error( 'db_insert_error', __( 'Could not delete your subscription to marketing emails and updates', 'woodmart' ), $wpdb->last_error );
		}

		return $result;
	}

	/**
	 * Checks if a subscription exists in the marketing consent table based on the given conditions.
	 *
	 * @param array|string $where Conditions to filter the query.
	 * @return int Number of matching subscriptions found.
	 */
	public function check_subscription_exists( $where ) {
		global $wpdb;

		if ( ! get_option( 'woodmart_marketing_consent_installed' ) ) {
			return 0;
		}

		$where  = $this->get_where_str( $where );
		$result = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->wd_marketing_consent} WHERE {$where}" );

		return $result;
	}

	/**
	 * Builds a SQL WHERE clause string from an associative array of field-value pairs.
	 *
	 * @param array $where Associative array where keys are field names and values are the values to match.
	 *
	 * @return string The generated WHERE clause string with prepared statements.
	 */
	public function get_where_str( $where ) {
		global $wpdb;

		$where_prepape = array();

		foreach ( $where as $field => $value ) {
			if ( ! in_array( $field, self::$allowed_fields, true ) ) {
				continue;
			}

			if ( is_null( $value ) ) {
				$where_prepape[] = "{$field} IS NULL";
			} else {
				$where_prepape[] = $wpdb->prepare(
					"{$field} = %s",
					$value
				);
			}
		}

		$where_str = implode( 'AND ', $where_prepape );

		return $where_str;
	}

	/**
	 * Define tables aliases.
	 *
	 * @return void
	 */
	public static function define_tables() {
		global $wpdb;

		$wpdb->wd_marketing_consent = $wpdb->prefix . self::MARKETING_CONSENT_TABLE;
		$wpdb->tables[]             = self::MARKETING_CONSENT_TABLE;
	}

	/**
	 * Create table.
	 */
	public static function install() {
		global $wpdb;

		if ( ! isset( $_GET['settings-updated'] ) ) {
			return;
		}

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . self::MARKETING_CONSENT_TABLE;
		$sql             = "CREATE TABLE $table_name (
					id bigint(20) NOT NULL AUTO_INCREMENT,
					user_id bigint(20),
					user_email VARCHAR(100),
					consent_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					consent_date_gmt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (id)
					) $charset_collate;";

		dbDelta( $sql );

		update_option( 'woodmart_marketing_consent_installed', true, false );
	}
}

DB_Storage::get_instance();
