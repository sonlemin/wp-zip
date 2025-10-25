<?php
/**
 * Shipping progress bar frontend class.
 *
 * @package woodmart
 */

namespace XTS\Modules\Shipping_Progress_Bar;

use XTS\Singleton;
use XTS\Modules\Layouts\Main as Builder;
	
/**
 * Shipping progress bar frontend class.
 */
class Frontend extends Singleton {
	/**
	 * Init.
	 */
	public function init() {
		if ( woodmart_get_opt( 'shipping_progress_bar_enabled' ) ) {
			add_action( 'wp', array( $this, 'output_shipping_progress_bar' ), 100 );
			add_action( 'init', array( $this, 'output_shipping_progress_bar_in_mini_cart' ), 100 );
		}
	}

	/**
	 * Output shipping progress bar.
	 */
	public function output_shipping_progress_bar() {
		if ( ! woodmart_get_opt( 'shipping_progress_bar_enabled' ) ) {
			return;
		}

		if ( woodmart_get_opt( 'shipping_progress_bar_location_card_page' ) && ! Builder::get_instance()->has_custom_layout( 'cart' ) ) {
			add_action( 'woocommerce_before_cart_table', array( $this, 'render_shipping_progress_bar_with_wrapper' ) );
		}

		if ( woodmart_get_opt( 'shipping_progress_bar_location_single_product' ) ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'render_shipping_progress_bar_with_wrapper' ), 29 );
		}

		if ( woodmart_get_opt( 'shipping_progress_bar_location_checkout' ) ) {
			add_action( 'woocommerce_checkout_billing', array( $this, 'render_shipping_progress_bar_with_wrapper' ) );
		}
	}

	/**
	 * Update fragments shipping progress bar.
	 *
	 * @return void
	 */
	public function output_shipping_progress_bar_in_mini_cart() {
		if ( ! woodmart_get_opt( 'shipping_progress_bar_enabled' ) ) {
			return;
		}

		if ( woodmart_get_opt( 'shipping_progress_bar_location_mini_cart' ) ) {
			add_action( 'woocommerce_widget_shopping_cart_before_buttons', array( $this, 'render_shipping_progress_bar' ) );
		}

		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'get_shipping_progress_bar_fragments' ), 40 );
		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'get_shipping_progress_bar_checkout_fragments' ), 10 );
	}

	/**
	 * Get shipping progress bar content.
	 *
	 * @codeCoverageIgnore
	 * @return void
	 */
	public function render_shipping_progress_bar_with_wrapper() {
		?>
		<div class="wd-shipping-progress-bar wd-style-bordered">
			<?php $this->render_shipping_progress_bar(); ?>
		</div>
		<?php
	}

	/**
	 * Add shipping progress bar fragment.
	 *
	 * @param array $array Fragments.
	 *
	 * @return array
	 */
	public function get_shipping_progress_bar_checkout_fragments( $array ) {
		ob_start();

		$this->render_shipping_progress_bar();

		$content = ob_get_clean();

		$array['div.wd-free-progress-bar'] = $content;

		return $array;
	}

	/**
	 * Add shipping progress bar fragment.
	 *
	 * @param array $array Fragments.
	 *
	 * @return array
	 */
	public function get_shipping_progress_bar_fragments( $array ) {
		ob_start();

		$this->render_shipping_progress_bar();

		$content = ob_get_clean();

		if ( apply_filters( 'woodmart_update_fragments_fix', true ) ) {
			$array['div.wd-free-progress-bar_wd'] = $content;
		} else {
			$array['div.wd-free-progress-bar'] = $content;
		}

		return $array;
	}

	/**
	 * Render free shipping progress bar.
	 *
	 * @codeCoverageIgnore
	 */
	public function render_shipping_progress_bar() {
		if ( ! woodmart_get_opt( 'shipping_progress_bar_enabled' ) ) {
			return;
		}

		$calculation     = woodmart_get_opt( 'shipping_progress_bar_calculation', 'custom' );
		$wrapper_classes = '';
		$percent         = 100;
		$limit           = 0;
		$free_shipping   = false;

		if ( ! is_object( WC() ) || ! property_exists( WC(), 'cart' ) || ! is_object( WC()->cart ) || ! method_exists( WC()->cart, 'get_displayed_subtotal' ) ) {
			$total       = 0;
			$calculation = 'custom';
		} else {
			$total = floatval( WC()->cart->get_displayed_subtotal() );
		}

		if ( 'wc' === $calculation ) {
			$packages = WC()->cart->get_shipping_packages();
			$package  = reset( $packages );
			$zone     = wc_get_shipping_zone( $package );

			foreach ( $zone->get_shipping_methods( true ) as $method ) {
				if ( 'free_shipping' === $method->id ) {
					$limit = wc_format_decimal( $method->get_option( 'min_amount' ) );
				}
			}
		} elseif ( 'custom' === $calculation ) {
			$limit = woodmart_get_opt( 'shipping_progress_bar_amount' );
		}

		if ( $total && 'include' === woodmart_get_opt( 'shipping_progress_bar_include_coupon' ) && WC()->cart->get_coupons() ) {
			foreach ( WC()->cart->get_coupons() as $coupon ) {
				$total -= WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );

				if ( $coupon->get_free_shipping() ) {
					$free_shipping = true;
					break;
				}
			}
		}

		$limit = floatval( apply_filters( 'woodmart_shipping_progress_bar_amount', $limit ) );

		if ( ! $limit ) {
			return;
		}

		if ( $total < $limit && ! $free_shipping ) {
			$percent = floor( ( $total / $limit ) * 100 );
			$message = str_replace( '[remainder]', wc_price( $limit - $total ), woodmart_get_opt( 'shipping_progress_bar_message_initial' ) );
		} else {
			$message = woodmart_get_opt( 'shipping_progress_bar_message_success' );
		}

		if ( 0 === (int) $total || $percent < 0 ) {
			$wrapper_classes .= ' wd-progress-hide';
		}

		?>
		<div class="wd-progress-bar wd-free-progress-bar<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="progress-msg">
				<?php echo do_shortcode( $message ); ?>
			</div>
			<div class="progress-area">
				<div class="progress-bar" style="width: <?php echo esc_attr( $percent ); ?>%"></div>
			</div>
		</div>
		<?php
	}
}

Frontend::get_instance();
