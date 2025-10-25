<?php
/**
 * Price tracker table.
 *
 * @var array $data Data for render table.
 *
 * @package Woodmart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="wd-pt-content">
	<?php
		if ( ! $data_count ) {
			woodmart_enqueue_inline_style( 'woo-page-empty-page' );
		}
	?>

	<?php do_action( 'woodmart_before_price_tracker_table' ); ?>

	<?php if ( $data ) : ?>
		<table class="wd-pt-table shop_table shop_table_responsive shop-table-with-img">
			<thead>
				<tr>			
					<th class="product-remove"></th>
					<th class="product-thumbnail"></th>
					<th class="product-name">
						<?php esc_html_e( 'Product', 'woodmart' ); ?>
					</th>
					<th class="product-current-price">
						<?php esc_html_e( 'Current price', 'woodmart' ); ?>
					</th>

					<?php if ( woodmart_get_opt( 'price_tracker_desired_price' ) ) : ?>
					<th class="product-desired-price">
						<?php esc_html_e( 'Desired price', 'woodmart' ); ?>
					</th>
					<?php endif; ?>
				</tr>
			</thead>

			<tbody>
			<?php
			foreach ( $data as $subscription ) {
				$product_id   = $subscription->product_id;
				$variation_id = $subscription->variation_id ? $subscription->variation_id : 0;

				if ( defined( 'WCML_VERSION' ) && defined( 'ICL_SITEPRESS_VERSION' ) ) {
					$current_lang = apply_filters( 'wpml_current_language', null );

					$translated_product_id = apply_filters( 'wpml_object_id', $product_id, 'product', false, $current_lang );
					if ( false !== $translated_product_id ) {
						$product_id = $translated_product_id;
					}

					$translated_variation_id = apply_filters( 'wpml_object_id', $variation_id, 'product', false, $current_lang );
					if ( false !== $translated_variation_id ) {
						$variation_id = $translated_variation_id;
					}
				}

				$product = wc_get_product( $variation_id ? $variation_id : $product_id );

				if ( empty( $product ) ) {
					continue;
				}

				$desired_price = apply_filters( 'wcml_raw_price_amount', floatval( $subscription->desired_price ) );
				$product_link  = $product->get_permalink();
				$product_image = $product->get_image( 'shop_thumbnail' );
				$product_name  = $product->get_name();
				$attributes    = array();

				if ( 'variation' === $product->get_type() ) {
					foreach ( $product->get_attributes() as $taxonomy => $value ) {
						$attributes[] = array(
							'key'     => ucfirst( wc_attribute_label( $taxonomy ) ),
							'value'   => ucfirst( $value ),
							'display' => ucfirst( $value ),
						);
					}
				}

				if ( count( $attributes ) > 2 ) {
					$product_name = $product->get_title();
				}
				?>
				<tr>
					<td class="product-remove">
						<a href="#" class="wd-pt-remove" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-variation-id="<?php echo esc_attr( $variation_id ); ?>">
							&times;
						</a>
					</td>
					<td class="product-thumbnail">
						<a class="product-image" href="<?php echo esc_url( $product_link ); ?>">
							<?php echo wp_kses_post( $product_image ); ?>
						</a>
					</td>
					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woodmart' ); ?>">
						<a class="product-title" href="<?php echo esc_url( $product_link ); ?>">
							<?php echo wp_kses_post( $product_name ); ?>
						</a>
						<?php if ( isset( $attributes ) && ! empty( $attributes ) && count( $attributes ) > 2 ) : ?>
							<?php wc_get_template( 'cart/cart-item-data.php', array( 'item_data' => $attributes ) ); ?>
						<?php endif; ?>
					</td>
					<td class="product-current-price" data-title="<?php esc_attr_e( 'Price', 'woodmart' ); ?>">
						<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
							<?php echo wp_kses_post( $product->get_price_html() ); ?>
						</p>
					</td>
					<?php if ( woodmart_get_opt( 'price_tracker_desired_price' ) ) : ?>
						<td class="product-desired-price" data-title="<?php esc_attr_e( 'Desired price', 'woodmart' ); ?>">
							<?php if ( ! empty( $desired_price ) ) : ?>
								<?php echo wp_kses_post( wc_price( $desired_price ) ); ?>
							<?php else: ?>
								<span class="wd-cell-empty"></span>
							<?php endif; ?>

							<div class="wd-desired-price-opener wd-action-btn wd-style-icon">
								<a href="#" aria-label="<?php esc_attr_e( 'Edit desired price', 'woodmart' ); ?>" title="<?php esc_attr_e( 'Edit desired price', 'woodmart' ); ?>"></a>
							</div>

							<div class="wd-desired-price-edit wd-hide" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-variation-id="<?php echo esc_attr( $variation_id ); ?>">
								<input type="number" name="wd-desired-price-change" min="0" max="<?php echo esc_attr( $product->get_regular_price() ); ?>" value="<?php echo esc_attr( $desired_price ); ?>" data-title="<?php esc_attr_e( 'Edit desired price', 'woodmart' ); ?>">

								<a href="#" class="btn wd-desired-price-save">
									<?php esc_html_e( 'Save', 'woodmart' ); ?>
								</a>

								<div class="wd-desired-price-cancel wd-action-btn wd-style-icon wd-cross-icon">
									<a href="#" aria-label="<?php esc_attr_e( 'Cancel edit desired price', 'woodmart' ); ?>"></a>
								</div>
							</div>
						</td>
					<?php endif; ?>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>

		<div class="wd-loader-overlay wd-fill"></div>

		<?php wc_get_template( 'loop/pagination.php', $paginate_args ); ?>
	<?php else : ?>
		<p class="wd-empty-pt wd-empty-page">
			<?php esc_html_e( 'Price tracker is empty.', 'woodmart' ); ?>
		</p>

		<div class="wd-empty-page-text">
			<?php echo wp_kses( __( 'Your price tracker is empty. Head over to the shop and start tracking items to stay updated on discounts.', 'woodmart' ), woodmart_get_allowed_html() ); ?>
		</div>

		<p class="return-to-shop">
			<a class="button" href="<?php echo esc_url( apply_filters( 'woodmart_price_tracker_return_to_shop_url', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php esc_html_e( 'Return to shop', 'woodmart' ); ?>
			</a>
		</p>
	<?php endif; ?>

	<?php do_action( 'woodmart_after_price_tracker_table' ); ?>
</div>
