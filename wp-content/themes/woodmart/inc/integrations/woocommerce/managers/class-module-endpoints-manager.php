<?php
/**
 * Module endpoints manager.
 *
 * @package woodmart
 */

namespace XTS\Modules\Managers;

use XTS\Singleton;

/**
 * Manages module endpoints
 */
class Module_Endpoints_Manager extends Singleton {
	/**
	 * Endpoint options.
	 *
	 * @var array
	 */
	private $endpoint_options = array();

	/**
	 * Whether hooks have been initialized.
	 *
	 * @var bool
	 */
	private $hooks_initialized = false;

	/**
	 * Constructor.
	 */
	public function init() {
		add_action( 'init', array( $this, 'maybe_setup_endpoints' ), 99 );
	}

	/**
	 * Add endpoint options from a module.
	 *
	 * @param array $options Endpoint options to add.
	 */
	public function add_endpoint_options( $options ) {
		if ( empty( $options ) || ! is_array( $options ) || ! isset( $options['id'] ) ) {
			return;
		}

		if ( ! $this->is_duplicate_endpoint( $options ) ) {
			$this->endpoint_options[] = $options;
		}
	}

	/**
	 * Sets up endpoints if they are defined.
	 */
	public function maybe_setup_endpoints() {
		if ( $this->hooks_initialized || empty( $this->endpoint_options ) ) {
			return;
		}

		$this->setup_endpoint_hooks();

		$this->hooks_initialized = true;

		// Ensure rewrite rules are flushed once when endpoints configuration changes.
		$this->maybe_flush_rewrite_rules();
	}

	/**
	 * Sets up endpoint hooks and rewrite rules.
	 */
	private function setup_endpoint_hooks() {
		// Register filters first so they take effect when we query WC for vars.
		add_filter( 'woocommerce_settings_pages', array( $this, 'add_endpoint_option' ) );
		add_filter( 'woocommerce_get_query_vars', array( $this, 'register_endpoint' ) );

		// Then set up rewrite rules using the now-augmented query vars.
		$this->setup_rewrite_rules();
	}

	/**
	 * Flush rewrite rules once when endpoints configuration changes.
	 *
	 * We do this only in admin and non-AJAX contexts to avoid performance impact on frontend.
	 * Stores a hash of current endpoints (IDs and slugs) and My Account page ID.
	 *
	 * @return void
	 */
	private function maybe_flush_rewrite_rules() {
		if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			return;
		}

		if ( empty( $this->endpoint_options ) ) {
			return;
		}

		$myaccount_id = (int) get_option( 'woocommerce_myaccount_page_id' );

		$endpoints_snapshot = array();
		foreach ( $this->endpoint_options as $option ) {
			if ( ! isset( $option['id'], $option['default'] ) ) {
				continue;
			}

			$endpoints_snapshot[] = array(
				'id'   => (string) $option['id'],
				'slug' => (string) get_option( $option['id'], $option['default'] ),
			);
		}

		// Sort to ensure stable order for hashing regardless of registration order.
		usort(
			$endpoints_snapshot,
			function ( $a, $b ) {
				return strcmp( $a['id'], $b['id'] );
			}
		);

		$hash_source = array(
			'myaccount_id' => $myaccount_id,
			'endpoints'    => $endpoints_snapshot,
		);

		$current_hash = md5( wp_json_encode( $hash_source ) );
		$option_name  = 'xts_module_endpoints_rules_hash';
		$stored_hash  = get_option( $option_name, '' );

		if ( $current_hash !== $stored_hash ) {
			// Make sure rules are set up for current configuration before flushing.
			$this->setup_rewrite_rules();
			flush_rewrite_rules( false );
			update_option( $option_name, $current_hash, false );
		}
	}

	/**
	 * Adds an endpoint to the module.
	 *
	 * @param array $options Endpoint options.
	 *
	 * @return void
	 */
	private function add_endpoint( $options ) {
		if ( empty( $options ) || ! is_array( $options ) || ! isset( $options['id'] ) ) {
			return;
		}

		if ( ! $this->is_duplicate_endpoint( $options ) ) {
			$this->endpoint_options[] = $options;
		}
	}

	/**
	 * Checks if an endpoint is a duplicate.
	 *
	 * @param array $options The endpoint options.
	 *
	 * @return bool True if duplicate, false otherwise.
	 */
	private function is_duplicate_endpoint( $options ) {
		foreach ( $this->endpoint_options as $existing ) {
			if ( $existing['id'] === $options['id'] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if there are any endpoints defined.
	 *
	 * @return bool True if there are endpoints, false otherwise.
	 */
	public function has_endpoints() {
		return ! empty( $this->endpoint_options );
	}

	/**
	 * Adds endpoint options to WooCommerce settings.
	 *
	 * @param array $settings Current WooCommerce settings.
	 *
	 * @return array Modified settings with endpoint options added.
	 */
	public function add_endpoint_option( $settings ) {
		if ( empty( $this->endpoint_options ) ) {
			return $settings;
		}

		return $this->insert_endpoint_options( $settings );
	}

	/**
	 * Inserts endpoint options into the WooCommerce settings.
	 *
	 * @param array $settings Current WooCommerce settings.
	 *
	 * @return array Modified settings with endpoint options inserted.
	 */
	private function insert_endpoint_options( $settings ) {
		woodmart_sort_data( $this->endpoint_options, 'priority', 'asc' );

		$offset = $this->find_insertion_point( $settings );

		return array_merge(
			array_slice( $settings, 0, $offset, true ),
			$this->endpoint_options,
			array_slice( $settings, $offset, null, true )
		);
	}

	/**
	 * Finds the insertion point for endpoint options in the settings.
	 *
	 * @param array $settings Current WooCommerce settings.
	 *
	 * @return int The index at which to insert the endpoint options.
	 */
	private function find_insertion_point( $settings ) {
		$search_result = array_search(
			'woocommerce_myaccount_payment_methods_endpoint',
			array_column( $settings, 'id' ),
			true
		);

		return false !== $search_result ? $search_result + 1 : count( $settings );
	}

	/**
	 * Registers the endpoints with WooCommerce query vars.
	 *
	 * @param array $query_vars Current query vars.
	 *
	 * @return array Modified query vars with endpoint options added.
	 */
	public function register_endpoint( $query_vars ) {
		foreach ( $this->endpoint_options as $option ) {
			if ( isset( $option['id'], $option['default'] ) ) {
				$query_vars[ $option['default'] ] = get_option( $option['id'], $option['default'] );
			}
		}

		return $query_vars;
	}

	/**
	 * Sets up rewrite rules for the endpoints.
	 *
	 * @return void
	 */
	public function setup_rewrite_rules() {
		if ( empty( $this->endpoint_options ) || ! function_exists( 'WC' ) ) {
			return;
		}

		$myaccount_id = (int) get_option( 'woocommerce_myaccount_page_id' );
		$slug         = (string) get_post_field( 'post_name', $myaccount_id );
		$wc_query     = WC()->query;

		if ( empty( $slug ) || empty( $wc_query ) ) {
			return;
		}

		$query_vars = $wc_query->get_query_vars();

		foreach ( $this->endpoint_options as $option ) {
			if ( ! isset( $option['default'] ) || ! array_key_exists( $option['default'], $query_vars ) ) {
				continue;
			}

			$endpoint = ! empty( $query_vars[ $option['default'] ] ) ? $query_vars[ $option['default'] ] : $option['default'];

			add_rewrite_endpoint( $endpoint, EP_PAGES );

			add_rewrite_rule(
				'^' . $slug . '/' . $endpoint . '/page/([^/]*)?',
				'index.php?page_id=' . $myaccount_id . '&' . $endpoint . '&paged=$matches[1]',
				'top'
			);
		}
	}
}
