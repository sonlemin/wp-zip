<?php
/**
 * Frontend class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Price_Tracker;

use XTS\Singleton;
use XTS\Modules\Layouts\Main as Layouts;
use WC_Product;

/**
 * Frontend class.
 */
class Frontend extends Singleton {
	/**
	 * Instance of DB_Storage class.
	 *
	 * @var DB_Storage $db_storage - Instance of DB_Storage class.
	 */
	private $db_storage;

	/**
	 * List of popup fields.
	 *
	 * @var array
	 */
	public $popup_fields;

	/**
	 * Constructor.
	 */
	public function init() {
		if ( ! woodmart_get_opt( 'price_tracker_enabled' ) ) {
			return;
		}

		$this->popup_fields = array();
		$this->db_storage   = DB_Storage::get_instance();

		add_action( 'wp', array( $this, 'add_output_subscribe_form' ), 100 );

		add_action( 'wp_ajax_woodmart_update_price_tracker_form', array( $this, 'update_price_tracker_form' ) );
		add_action( 'wp_ajax_nopriv_woodmart_update_price_tracker_form', array( $this, 'update_price_tracker_form' ) );

		add_action( 'wp_ajax_woodmart_add_to_price_tracker', array( $this, 'add_to_price_tracker' ) );
		add_action( 'wp_ajax_nopriv_woodmart_add_to_price_tracker', array( $this, 'add_to_price_tracker' ) );

		add_action( 'wp_ajax_woodmart_remove_from_price_tracker', array( $this, 'remove_from_price_tracker' ) );
		add_action( 'wp_ajax_nopriv_woodmart_remove_from_price_tracker', array( $this, 'remove_from_price_tracker' ) );

		add_filter( 'woodmart_localized_string_array', array( $this, 'add_localized_settings' ) );

		add_action( 'wp_login', array( $this, 'set_user_id_in_price_tracker' ), 10, 2 );

		// Price tracker in my account page.
		if ( is_user_logged_in() ) {
			add_filter( 'woocommerce_account_menu_items', array( $this, 'add_menu_item' ), 10, 1 );
			add_action( 'woocommerce_account_price-tracker_endpoint', array( $this, 'account_template' ) );

			add_action( 'wp_ajax_woodmart_remove_from_price_tracker_in_my_account', array( $this, 'remove_from_price_tracker_in_my_account_action' ) );
			add_action( 'wp_ajax_woodmart_update_price_tracker_desired_price', array( $this, 'update_price_tracker_desired_price_action' ) );
		}
	}

	/**
	 * Add render actions if single product build is disable.
	 */
	public function add_output_subscribe_form() {
		if ( Layouts::get_instance()->has_custom_layout( 'single_product' ) || ( woodmart_get_opt( 'price_tracker_for_loggined' ) && ! is_user_logged_in() ) ) {
			return;
		}

		add_action( 'woocommerce_single_product_summary', array( $this, 'render_popup' ), 38 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'render_button' ), 38 );
	}

	/**
	 * Render popup html if popup fields is not empty.
	 */
	public function render_popup() {
		$popup_fields = $this->get_render_popup_fields();

		if ( empty( $popup_fields ) || ! $this->should_be_rendered() ) {
			return;
		}

		$product_id       = $this->get_current_product_id();
		$product          = wc_get_product( $product_id );
		$current_currency = $this->get_current_currency();
		$popup_classes    = '';

		if ( ! is_ajax() ) {
			$popup_classes = ' mfp-hide';

			woodmart_enqueue_inline_style( 'mfp-popup' );
			woodmart_enqueue_inline_style( 'mod-animations-transform' );
			woodmart_enqueue_inline_style( 'mod-transform' );

			woodmart_enqueue_js_library( 'magnific' );
			woodmart_enqueue_js_script( 'popup-element' );
			woodmart_enqueue_js_script( 'css-animations' );
		}

		$is_signed_product = $this->is_signed_product( $product_id );
		?>
		<div id="wd-popup-pt" data-wrap-class="wd-popup-pt-wrap" class="wd-popup wd-popup-element wd-popup-pt wd-scroll-content<?php echo esc_attr( $popup_classes ); ?>">

			<div class="wd-pt-signed wd-set-mb reset-last-child<?php echo $is_signed_product ? '' : esc_attr( ' wd-hide' ); ?>">
				<div class="wd-pt-signed-icon"></div>

				<div class="title">
					<?php esc_html_e( 'Subscription successful', 'woodmart' ); ?>
				</div>

				<p>
					<?php esc_html_e( 'You’re now subscribed to price tracking for this product. We’ll notify you if the price drops.', 'woodmart' ); ?>
				</p>

				<div class="wd-pt-signed-btns">
					<a href="#" class="btn wd-close-popup">
						<?php esc_html_e( 'Continue shopping', 'woodmart' ); ?>
					</a>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'price-tracker' ) ); ?>" class="btn wd-pt-view">
						<?php esc_html_e( 'View subscriptions', 'woodmart' ); ?>
					</a>
				</div>
			</div>

			<div class="wd-pt-not-signed wd-set-mb reset-last-child<?php echo $is_signed_product ? esc_attr( ' wd-hide' ) : ''; ?>">
				<div class="title">
					<?php esc_html_e( 'Price tracker', 'woodmart' ); ?>
				</div>
				<p><?php esc_html_e( 'Track this item and get notified if the price drops.', 'woodmart' ); ?></p>

				<?php if ( in_array( 'user_subscribe_email', $popup_fields, true ) ) : ?>
					<input type="email" name="wd-pt-user-subscribe-email" placeholder="<?php esc_html_e( 'Enter your email address', 'woodmart' ); ?>" <?php echo $this->get_email_attr(); // phpcs:ignore. ?> >
				<?php endif; ?>

				<?php if ( in_array( 'user_desired_price', $popup_fields, true ) ) : ?>
					<label class="wd-pt-desired-price" for="wd-pt-desired-price-check">
						<input type="checkbox" name="wd-pt-desired-price-check" id="wd-pt-desired-price-check" class="wd-pt-desired-price-check" value="0" >
						<span>
							<?php esc_html_e( 'The price will be reduced to', 'woodmart' ); ?>
						</span>
						<input type="number" name="wd-pt-user-desired-price" min="1" max="<?php echo esc_attr( $this->get_max_price( $product ) ); ?>">
						<span>
							<?php echo esc_html( $current_currency ); ?>
						</span>
					</label>
				<?php endif; ?>

				<?php if ( in_array( 'policy_check', $popup_fields, true ) ) : ?>
					<label for="wd-pt-policy-check">
						<input type="checkbox" name="wd-pt-policy-check" id="wd-pt-policy-check" class="wd-pt-policy-check" value="0" >
						<span>
							<?php
							if ( function_exists( 'wc_replace_policy_page_link_placeholders' ) ) {
								echo wp_kses_post( wc_replace_policy_page_link_placeholders( esc_html__( 'I have read and accept the [privacy_policy]', 'woodmart' ) ) );
							}
							?>
						</span>
					</label>
				<?php endif; ?>

				<a href="#" class="btn wd-pt-add">
					<?php esc_attr_e( 'Add to price tracker', 'woodmart' ); ?>
				</a>
			</div>

			<div class="wd-loader-overlay wd-fill"></div>
		</div>
		<?php
	}

	/**
	 * Render subscribe button.
	 */
	public function render_button( $button_classes = '' ) {
		if ( ! $this->should_be_rendered() ) {
			return;
		}

		$product_id     = $this->get_current_product_id();
		$btn_data       = $this->get_button_data( $product_id );
		$btn_attributes = '';

		if ( empty( $button_classes ) && ! Layouts::get_instance()->has_custom_layout( 'single_product' ) ) {
			$button_classes = 'wd-action-btn wd-style-text wd-pt-icon';
		}

		if ( ! empty( $btn_data['button_classes'] ) ) {
			$button_classes .= $btn_data['button_classes'];
		}

		if ( ! empty( $btn_data['signed_variations'] ) ) {
			$btn_attributes = "data-signed-variations='" . esc_attr( wp_json_encode( $btn_data['signed_variations'] ) ) . "'";
		}

		if ( ! is_ajax() ) {
			woodmart_enqueue_inline_style( 'woo-opt-pt' );

			woodmart_enqueue_js_script( 'pt-subscribe-form' );
		}
		?>
		<div class="wd-pt-btn <?php echo esc_attr( $button_classes ); ?>" <?php echo $btn_attributes; // phpcs:ignore. ?>>
			<a
				href="<?php echo esc_url( $btn_data['button_link'] ); ?>"
				rel="nofollow"
				class="<?php echo esc_attr( $btn_data['link_classes'] ); ?>"
			>
				<span class="wd-added-icon"></span>
				<span class="wd-action-text"><?php echo esc_html( $btn_data['button_text'] ); ?></span>
			</a>
		</div>
		<?php
	}

	/**
	 * Update price drop subscription form with ajax.
	 *
	 * @return void
	 */
	public function update_price_tracker_form() {
		if ( empty( $_GET['action'] ) || 'woodmart_update_price_tracker_form' !== $_GET['action'] ) {
			return;
		}

		$product_id = $this->get_current_product_id();
		$product    = wc_get_product( $product_id );

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$signed_variations = array();

		if ( in_array( $product->get_type(), apply_filters( 'woodmart_variable_product_types', array( 'variable' ) ), true ) ) {
			$signed_variations = $this->get_signed_variations( $product_id );
		}

		wp_send_json(
			array(
				'is_signed'         => $this->is_signed_product( $product_id ),
				'signed_variations' => $signed_variations,
			)
		);
	}

	/**
	 * Add to price tracker ajax action.
	 */
	public function add_to_price_tracker() {
		if ( ! wp_verify_nonce( $_POST['security'] ? $_POST['security'] : '', 'woodmart_price_tracker_add' ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'Security check failed.', 'woodmart' ),
				)
			);
		}

		$product_id        = ! empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$variation_id      = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$user_email        = ! empty( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
		$desired_price     = ! empty( $_POST['desired_price'] ) ? floatval( wp_unslash( $_POST['desired_price'] ) ) : '';
		$email_language    = '';
		$current_currency  = $this->get_current_currency();
		$origin_product_id = $variation_id ? $variation_id : $product_id;
		$user_id           = get_current_user_id();

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$product_id     = apply_filters( 'wpml_object_id', $product_id, 'product', true, wpml_get_default_language() );
			$variation_id   = apply_filters( 'wpml_object_id', $variation_id, 'product', true, wpml_get_default_language() );
			$email_language = apply_filters( 'wpml_current_language', null );
		}

		if ( defined( 'WCML_VERSION' ) ) {
			$desired_price = $this->convert_price_to_default( $desired_price );
		}

		$current_product_id = $variation_id ? intval( $variation_id ) : intval( $product_id );
		$product            = wc_get_product( $current_product_id );
		$product_price      = $product->get_price();

		if ( empty( $product_id ) || ! $product instanceof WC_Product ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'There is no product.', 'woodmart' ),
				)
			);
			die();
		}

		if ( is_user_logged_in() && woodmart_get_opt( 'price_tracker_use_loggedin_email' ) ) {
			$user_email = wp_get_current_user()->get( 'user_email' );
		}

		if ( empty( $user_email ) || ! is_email( $user_email ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'A valid email address is required to use the price tracker for this product.', 'woodmart' ),
				)
			);
			die();
		}

		if ( $desired_price && $desired_price < 0 ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'Desired price cannot be less than 0.', 'woodmart' ),
				)
			);
			die();
		}

		if ( ! empty( $desired_price ) && $desired_price > $product->get_regular_price() ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'The desired price should not exceed the current price of the product.', 'woodmart' ),
				)
			);
			die();
		}

		if ( $this->db_storage->check_subscription_exists( $current_product_id, $user_email ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'You’re already subscribed to price drop notifications for this product.', 'woodmart' ),
				)
			);
		}

		$unsubscribe_token = wp_generate_password( 24, false );

		$data = array(
			'user_id'           => $user_id,
			'user_email'        => $user_email,
			'product_id'        => $product_id,
			'variation_id'      => $variation_id,
			'product_price'     => $product_price,
			'desired_price'     => $desired_price,
			'subscribe_status'  => 'signed',
			'email_language'    => $email_language,
			'email_currency'    => $current_currency,
			'unsubscribe_token' => $unsubscribe_token,
			'created_date_gmt'  => current_time( 'mysql', 1 ),
		);

		if ( $this->db_storage->create_subscription( $data ) ) {
			if ( ! is_user_logged_in() ) {
				$cookie_data                = $this->get_cookie();
				$cookie_key                 = $variation_id ? $variation_id : $product_id;
				$cookie_data[ $cookie_key ] = $unsubscribe_token;

				woodmart_set_cookie( 'woodmart_price_tracker_unsubscribe_tokens', wp_json_encode( $cookie_data ) );
			}

			$this->maybe_send_price_tracker_subscribe_email( $user_email, $origin_product_id, $email_language );

			wp_send_json_success(
				array(
					'state' => 'signed',
				)
			);
			die();
		}

		wp_send_json_error(
			array(
				'notice' => esc_html__( 'Unable to add product to price tracker.', 'woodmart' ),
			)
		);
		die();
	}

	/**
	 * Ajax action for removing subscription on single product page.
	 */
	public function remove_from_price_tracker() {
		if ( ! wp_verify_nonce( $_POST['security'] ? $_POST['security'] : '', 'woodmart_price_tracker_remove' ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'Security check failed.', 'woodmart' ),
				)
			);
		}

		$product_id = $this->get_current_product_id();
		$product    = wc_get_product( $product_id );

		if ( empty( $product_id ) || ! $product instanceof WC_Product ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'There is no product.', 'woodmart' ),
				)
			);
			die();
		}

		$user_unsubscribed = false;

		if ( ! is_user_logged_in() ) {
			$cookie_data = $this->get_cookie();

			if ( ! empty( $cookie_data ) && in_array( $product_id, array_keys( $cookie_data ), true ) ) {
				$unsubscribe_token = $cookie_data[ $product_id ];

				if ( $this->db_storage->unsubscribe_by_token( $unsubscribe_token ) ) {
					$user_unsubscribed = true;

					unset( $cookie_data[ $product_id ] );

					woodmart_set_cookie( 'woodmart_price_tracker_unsubscribe_tokens', wp_json_encode( $cookie_data ) );
				}
			}
		} elseif ( $this->db_storage->unsubscribe_current_user( $product_id ) ) {
			$user_unsubscribed = true;
		}

		if ( $user_unsubscribed ) {
			wp_send_json_success(
				array(
					'state' => 'not-signed',
				)
			);
			die();
		}

		wp_send_json_error(
			array(
				'notice' => esc_html__( 'Unable to remove product from price tracker.', 'woodmart' ),
			)
		);
		die();
	}

	/**
	 * Ajax action for removing subscription on my account page.
	 */
	public function remove_from_price_tracker_in_my_account_action() {
		if ( ! wp_verify_nonce( $_POST['security'] ? $_POST['security'] : '', 'woodmart_price_tracker_remove' ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'Security check failed.', 'woodmart' ),
				)
			);
		}

		$product_id   = ! empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$variation_id = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$product_id   = apply_filters( 'wpml_object_id', $product_id, 'product', true, wpml_get_default_language() );
			$variation_id = apply_filters( 'wpml_object_id', $variation_id, 'product', true, wpml_get_default_language() );
		}

		$product_id = $variation_id ? $variation_id : $product_id;
		$product    = wc_get_product( $product_id );

		if ( empty( $product_id ) || ! $product instanceof WC_Product ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'There is no product.', 'woodmart' ),
				)
			);
		}

		if ( $this->db_storage->unsubscribe_current_user( $product_id ) ) {
			$content     = '';
			$cookie_data = $this->get_cookie();

			if ( ! empty( $cookie_data ) && in_array( $product_id, array_keys( $cookie_data ), true ) ) {
				unset( $cookie_data[ $product_id ] );

				woodmart_set_cookie( 'woodmart_price_tracker_unsubscribe_tokens', wp_json_encode( $cookie_data ) );
			}

			$data_count = $this->db_storage->get_subscription_count_for_current_user();

			if ( ! $data_count ) {
				ob_start();

				wc_get_template(
					'myaccount/price-tracker.php',
					array(
						'data'       => false,
						'data_count' => $data_count,
					)
				);

				$content = ob_get_clean();
			}

			wp_send_json_success(
				array(
					'content' => $content,
				)
			);
		}

		wp_send_json_error(
			array(
				'notice' => esc_html__( 'Unable to remove product from price tracker.', 'woodmart' ),
			)
		);
	}

	/**
	 * Update desired price for a product.
	 */
	public function update_price_tracker_desired_price_action() {
		if ( ! wp_verify_nonce( $_POST['security'] ? $_POST['security'] : '', 'woodmart_price_tracker_update_desired_price' ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'Security check failed.', 'woodmart' ),
				)
			);
		}

		$product_id    = ! empty( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$variation_id  = ! empty( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
		$desired_price = ! empty( $_POST['desired_price'] ) ? floatval( wp_unslash( $_POST['desired_price'] ) ) : 0;

		$current_currency = get_woocommerce_currency();

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$product_id   = apply_filters( 'wpml_object_id', $product_id, 'product', true, wpml_get_default_language() );
			$variation_id = apply_filters( 'wpml_object_id', $variation_id, 'product', true, wpml_get_default_language() );
		}

		if ( defined( 'WCML_VERSION' ) ) {
			global $woocommerce_wpml;

			$desired_price    = $this->convert_price_to_default( $desired_price );
			$current_currency = $woocommerce_wpml->multi_currency->get_client_currency();
		}

		$product = wc_get_product( $variation_id ? $variation_id : $product_id );

		if ( empty( $product_id ) || ! $product instanceof WC_Product ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'There is no product.', 'woodmart' ),
				)
			);
		}

		if ( $desired_price < 0 ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'Desired price cannot be less than 0.', 'woodmart' ),
				)
			);
		}

		if ( $product->get_regular_price() < $desired_price ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'The desired price should not exceed the current price of the product.', 'woodmart' ),
				)
			);
		}

		if ( $this->db_storage->check_is_same_desired_price( $product_id, $variation_id, $desired_price ) ) {
			wp_send_json_error(
				array(
					'notice' => esc_html__( 'You already have the same desired price for this product.', 'woodmart' ),
				)
			);
		}

		if ( $this->db_storage->update_price_tracker_desired_price( $product_id, $variation_id, $desired_price ) ) {
			if ( ! empty( $desired_price ) ) {
				$desired_price_html = wp_kses_post(
					wc_price(
						apply_filters( 'wcml_raw_price_amount', $desired_price ),
						array( 'currency' => $current_currency )
					)
				);
			} else {
				$desired_price_html = '<span class="wd-cell-empty"></span>';
			}

			wp_send_json_success(
				array(
					'notice'             => esc_html__( 'Desired price updated successfully.', 'woodmart' ),
					'desired_price_html' => $desired_price_html,
				)
			);
		}

		wp_send_json_error(
			array(
				'notice' => esc_html__( 'Unable to update the desired price.', 'woodmart' ),
			)
		);
	}

	/**
	 * Add menu item for standard wc account navigation.
	 *
	 * @param array $items My Account menu items.
	 *
	 * @return array
	 */
	public function add_menu_item( $items ) {
		$new_items = array();

		if ( ! is_array( $items ) ) {
			return $items;
		}

		$items_keys = array_keys( $items );
		$last_key   = end( $items_keys );

		foreach ( $items as $key => $value ) {
			if ( $key === $last_key ) {
				$new_items['price-tracker'] = esc_html__( 'Price tracker', 'woodmart' );
			}

			$new_items[ $key ] = $value;
		}

		return $new_items;
	}

	/**
	 * Price tracker template.
	 *
	 * @codeCoverageIgnore
	 */
	public function account_template() {
		$big          = 999999999; // Need an unlikely integer.
		$per_page     = apply_filters( 'woodmart_price_tracker_per_page', 12 );
		$current      = max( 1, get_query_var( 'paged' ) );
		$data_count   = $this->db_storage->get_subscription_count_for_current_user();
		$num_of_pages = $data_count > 0 && $per_page > 0 ? ceil( $data_count / $per_page ) : 1;

		$paginate_args = array(
			'base'    => str_replace( $big, '%#%', get_pagenum_link( $big, false ) ),
			'format'  => '?paged=%#%',
			'current' => $current,
			'total'   => $num_of_pages,
		);

		woodmart_set_loop_prop( 'shop_pagination', 'links' );

		woodmart_enqueue_inline_style( 'woo-page-pt' );

		woodmart_enqueue_js_script( 'pt-table' );

		wc_get_template(
			'myaccount/price-tracker.php',
			array(
				'data'          => $this->db_storage->get_subscriptions_user_id( get_current_user_id(), $current ),
				'paginate_args' => $paginate_args,
				'data_count'    => $data_count,
			)
		);
	}

	/**
	 * Set user id in price tracker after the user has successfully logged in.
	 *
	 * @param string  $user_login User login.
	 * @param WP_User $user Instance of WP_User class.
	 *
	 * @return void
	 */
	public function set_user_id_in_price_tracker( $user_login, $user ) {
		global $wpdb;

		$cookie_data = $this->get_cookie();

		if ( empty( $cookie_data ) || ! is_array( $cookie_data ) ) {
			return;
		}

		foreach ( $cookie_data as $product_id => $unsubscribe_token ) {
			if ( ! $unsubscribe_token || $this->db_storage->check_subscription_exists( $product_id, $user->user_email ) ) {
				break;
			}

			$this->db_storage->update_user_id_by_token( $unsubscribe_token, $user->ID );
		}
	}

	/**
	 * Add price tracker data in localized settings.
	 *
	 * @param array $localized Settings.
	 *
	 * @return array
	 */
	public function add_localized_settings( $localized ) {
		if ( woodmart_get_opt( 'price_tracker_enabled' ) ) {
			$localized['pt_button_text_not_tracking']   = __( 'Track price', 'woodmart' );
			$localized['pt_button_text_stop_tracking']  = __( 'Stop tracking', 'woodmart' );
			$localized['pt_button_text_watch_tracking'] = __( 'Tracking enabled', 'woodmart' );
			$localized['pt_endpoint_url']               = wc_get_account_endpoint_url( 'price-tracker' );
			$localized['pt_policy_check_msg']           = esc_html__( 'You must accept our Privacy Policy to join the Price tracker.', 'woodmart' );
			$localized['pt_desired_price_check_msg']    = esc_html__( 'You must specify the desired price for this product.', 'woodmart' );
			$localized['pt_subscribe_popup']            = ! is_user_logged_in() || ! woodmart_get_opt( 'price_tracker_use_loggedin_email' );
			$localized['pt_fragments_enable']           = woodmart_get_opt( 'price_tracker_fragments_enable' ) ? 'yes' : 'no';
			$localized['pt_add_button_nonce']           = wp_create_nonce( 'woodmart_price_tracker_add' );
			$localized['pt_remove_button_nonce']        = wp_create_nonce( 'woodmart_price_tracker_remove' );
			$localized['pt_update_desired_price_nonce'] = wp_create_nonce( 'woodmart_price_tracker_update_desired_price' );
		}

		return $localized;
	}

	/**
	 * Gets a list of product variations id to which the customer subscribes.
	 * If it is not a logged-in client, the data is taken from cookies.
	 *
	 * @param int|string $product_id Product id.
	 *
	 * @return array
	 */
	public function get_signed_variations( $product_id ) {
		$product = wc_get_product( $product_id );

		if ( ! $product instanceof WC_Product ) {
			return array();
		}

		$cookie_data       = $this->get_cookie();
		$children_ids      = $product->get_children();
		$signed_variations = array();

		if ( is_user_logged_in() ) {
			$signed_variations = $this->db_storage->get_signed_variations_by_user_id( $product_id, get_current_user_id() );
		} else {
			$signed_variations = array_values( array_intersect( $children_ids, array_keys( $cookie_data ) ) );
		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$translated_signed_variations = array();

			foreach ( $signed_variations as $origin_id ) {
				$translated_signed_variations[] = apply_filters( 'wpml_object_id', $origin_id, 'product', true );
			}

			return $translated_signed_variations;
		}

		return $signed_variations;
	}

	/**
	 * Get cookie.
	 */
	public function get_cookie() {
		$cookie_data = woodmart_get_cookie( 'woodmart_price_tracker_unsubscribe_tokens' );

		return $cookie_data ? json_decode( $cookie_data, true ) : array();
	}

	/**
	 * Get a list of the popup fields that should be displayed on the front end.
	 * The list is generated based on the options enabled in the theme settings.
	 */
	public function get_render_popup_fields() {
		$popup_fields = array();

		if ( ! is_user_logged_in() || ! woodmart_get_opt( 'price_tracker_use_loggedin_email' ) ) {
			$popup_fields[] = 'user_subscribe_email';
		}

		if ( woodmart_get_opt( 'price_tracker_enable_privacy_checkbox' ) ) {
			$popup_fields[] = 'policy_check';
		}

		if ( woodmart_get_opt( 'price_tracker_desired_price' ) ) {
			$popup_fields[] = 'user_desired_price';
		}

		return $popup_fields;
	}

	/**
	 * Get the current product id.
	 * If this is an ajax, it will be taken from the request parameters, otherwise the id is taken from the global variable.
	 * Product id can be a variation if it is ajax.
	 */
	public function get_current_product_id() {
		if ( is_ajax() ) {
			$product_id = 0;

			if ( ! empty( $_REQUEST['variation_id'] ) ) {
				$product_id = absint( $_REQUEST['variation_id'] );
			} elseif ( ! empty( $_REQUEST['product_id'] ) ) {
				$product_id = absint( $_REQUEST['product_id'] );
			}
		} else {
			global $product;

			$product_id = $product instanceof WC_Product ? $product->get_id() : 0;
		}

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$product_id = apply_filters( 'wpml_object_id', $product_id, 'product', true, wpml_get_default_language() );
		}

		return $product_id;
	}

	/**
	 * Get the current currency of the site.
	 *
	 * @return string
	 */
	public function get_current_currency() {
		if ( defined( 'WCML_VERSION' ) ) {
			global $woocommerce_wpml;

			if ( is_object( $woocommerce_wpml->multi_currency ) ) {
				$current_currency = $woocommerce_wpml->multi_currency->get_client_currency();

				if ( ! empty( $current_currency ) ) {
					return $current_currency;
				}
			}
		}

		return get_option( 'woocommerce_currency' );
	}

	/**
	 * Get attributes for email input in popup html.
	 *
	 * @return string
	 */
	public function get_email_attr() {
		$email_attr = '';

		if ( is_user_logged_in() ) {
			$current_user_email = wp_get_current_user()->get( 'user_email' );

			$email_attr = sprintf(
				'value=%s',
				esc_attr( $current_user_email )
			);
		}

		return $email_attr;
	}

	/**
	 * Get the maximum price of the product for variable and simple products.
	 *
	 * @param WC_Product $product Instance of WC_Product class.
	 *
	 * @return int
	 */
	public function get_max_price( $product ) {
		if ( in_array( $product->get_type(), apply_filters( 'woodmart_variable_product_types', array( 'variable' ) ), true ) ) {
			$max_price = $product->get_variation_regular_price( 'max' );
		} else {
			$max_price = $product->get_regular_price();
		}

		return $max_price;
	}

	/**
	 * Get list data for render buton.
	 *
	 * @param int $product_id Product id.
	 *
	 * @return array
	 */
	public function get_button_data( $product_id ) {
		$product             = wc_get_product( $product_id );
		$is_signed_product   = $this->is_signed_product( $product_id );
		$is_variable_product = in_array( $product->get_type(), apply_filters( 'woodmart_variable_product_types', array( 'variable' ) ), true );
		$parent_product_id   = $product->get_parent_id() ? $product->get_parent_id() : $product_id;

		$btn_data = array(
			'button_text'       => __( 'Track price', 'woodmart' ),
			'button_link'       => '#',
			'button_classes'    => '',
			'link_classes'      => '',
			'signed_variations' => '',
		);

		if ( $is_variable_product && ! empty( $parent_product_id ) ) {
			$btn_data['signed_variations'] = $this->get_signed_variations( $parent_product_id );
		}

		if ( ! is_ajax() ) {
			if ( $is_variable_product ) {
				$btn_data['button_classes'] .= ' wd-hide';
			}

			if ( woodmart_get_opt( 'price_tracker_fragments_enable' ) ) {
				$btn_data['button_classes'] .= ' wd-disabled';
			}
		}

		if ( $is_signed_product && ( ! is_ajax() || ( isset( $_REQUEST['action'] ) && 'woodmart_update_price_tracker_form' === $_REQUEST['action'] ) ) ) {
			$btn_data['button_text']     = __( 'Stop tracking', 'woodmart' );
			$btn_data['button_classes'] .= ' wd-pt-remove';
		} elseif ( $is_signed_product && is_ajax() ) {
			$btn_data['button_text']  = __( 'Tracking enabled', 'woodmart' );
			$btn_data['button_link']  = wc_get_account_endpoint_url( 'price-tracker' );
			$btn_data['link_classes'] = 'added';
		} elseif ( ! empty( $this->get_render_popup_fields() ) ) {
			$btn_data['button_link']  = '#wd-popup-pt';
			$btn_data['link_classes'] = 'wd-open-popup';
		} else {
			$btn_data['button_classes'] .= ' wd-pt-add';
		}

		return $btn_data;
	}

	/**
	 * Get converted product price to default site currency.
	 *
	 * @param int|float|string $price Product price.
	 *
	 * @return int|float|string
	 */
	public function convert_price_to_default( $price ) {
		if ( ! defined( 'WCML_VERSION' ) ) {
			return $price;
		}

		global $woocommerce_wpml;

		if ( is_object( $woocommerce_wpml->multi_currency ) ) {
			$default_currency = $woocommerce_wpml->multi_currency->get_default_currency();
			$current_currency = $woocommerce_wpml->multi_currency->get_client_currency();

			if ( ! empty( $price ) && $current_currency !== $default_currency ) {
				$price = $woocommerce_wpml->multi_currency->prices->convert_price_amount_by_currencies( $price, $current_currency, $default_currency );
			}
		}

		return $price;
	}

	/**
	 * Send woodmart_send_price_tracker_subscribe email if it`s enabled.
	 *
	 * @param string $user_email Recipient email.
	 * @param int    $origin_product_id Product id in the default language of the site.
	 * @param string $email_language The language in which the letter should be sent.
	 */
	public function maybe_send_price_tracker_subscribe_email( $user_email, $origin_product_id, $email_language ) {
		$mailer                     = WC()->mailer();
		$confirm_subscription_email = $mailer->emails['XTS_Email_Price_Tracker_Subscribe'];

		if ( ! $confirm_subscription_email->is_enabled() ) {
			return;
		}

		$product = wc_get_product( $origin_product_id );

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$price_html = wc_price( $product->get_price(), array( 'currency' => $this->get_current_currency() ) );

		do_action( 'woodmart_send_price_tracker_subscribe', $user_email, $product, $email_language, $price_html );
	}

	/**
	 * Check if you need to display elements for the subscription form.
	 *
	 * @return bool
	 */
	public function should_be_rendered() {
		if ( is_ajax() && ! $this->is_allowed_ajax_actions() ) {
			return false;
		}

		$product_id = $this->get_current_product_id();
		$product    = wc_get_product( $product_id );

		return $product instanceof WC_Product
			&& $product->is_in_stock()
			&& ! woodmart_loop_prop( 'is_quick_view' )
			&& $this->is_allowed_product_type( $product->get_type() );
	}

	/**
	 * Check if the client is subscribed to this product.
	 * If it is not a logged-in client, the data is taken from cookies.
	 *
	 * @param int|string $product_id Product id.
	 */
	public function is_signed_product( $product_id ) {
		if ( is_user_logged_in() ) {
			$signed = $this->db_storage->check_is_signed_product_by_user_id( $product_id, get_current_user_id() );
		} else {
			$cookie_data = $this->get_cookie();
			$signed      = in_array( $product_id, array_keys( $cookie_data ), true );
		}

		return $signed;
	}

	/**
	 * Check if this is an allowed ajax action.
	 *
	 * @return bool
	 */
	public function is_allowed_ajax_actions() {
		$allowed_actions = array(
			'woodmart_add_to_price_tracker',
			'woodmart_remove_from_price_tracker',
			'woodmart_update_price_tracker_form',
		);

		if ( in_array( $_REQUEST['action'], $allowed_actions, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if this is an allowed product type.
	 *
	 * @return bool
	 */
	public function is_allowed_product_type( $product_type ) {
		$allowed_product_types = apply_filters(
			'woodmart_price_tracker_allowed_product_types',
			array(
				'simple',
				'variable',
				'variation',
			)
		);

		return in_array( $product_type, $allowed_product_types, true );
	}
}

Frontend::get_instance();
