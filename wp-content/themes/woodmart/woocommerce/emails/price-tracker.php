<?php
/**
 * Price tracker email template.
 *
 * @package XTS
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p>
	<?php
	echo esc_html(
		sprintf(
			// translators: %s User login.
			__(
				'Hi, %s!',
				'woodmart'
			),
			$email->user_name
		)
	);
	?>
</p>

<p>
	<?php esc_html_e( 'Good news!', 'woodmart' ); ?>
	<?php esc_html_e( 'The price of some of the products you were watching has dropped.', 'woodmart' ); ?>
	<?php esc_html_e( 'Check them out below:', 'woodmart' ); ?>
</p>

<table class="td xts-prod-table" cellspacing="0" cellpadding="6" border="1">
	<thead>
		<tr>
			<th class="td" scope="col"></th>
			<th class="td xts-align-start" scope="col"><?php esc_html_e( 'Product', 'woodmart' ); ?></th>
			<th class="td xts-align-start" scope="col"><?php esc_html_e( 'Price', 'woodmart' ); ?></th>
			<th class="td xts-align-end" scope="col"><?php esc_html_e( 'Add to cart', 'woodmart' ); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ( $email->object as $subscription ) : ?>
		<?php
		if ( ! apply_filters( 'woocommerce_is_email_preview', false ) ) {
			$product_id = $subscription->variation_id ? $subscription->variation_id : $subscription->product_id;
			$product    = wc_get_product( $product_id );
		} elseif ( isset( $email->dummy_product ) ) {
			$product = $email->dummy_product;
		}

		if ( ! $product instanceof WC_Product ) {
			continue;
		}

		$product_url     = $product->get_permalink();
		$product_name    = $product->get_name();
		$product_image   = $product->get_image( array( 32, 32 ) );
		$price_args      = defined( 'WCML_VERSION' ) ? array( 'currency' => $subscription->email_currency ) : array();
		$old_price       = wc_price( $subscription->product_price, $price_args );
		$new_price       = wc_price( $subscription->product_new_price, $price_args );
		$add_to_cart_url = esc_url( add_query_arg( 'add-to-cart', $product->get_id(), wc_get_cart_url() ) );
		?>

		<tr>
			<td class="td xts-tbody-td xts-img-col xts-align-start">
				<a href="<?php echo esc_url( $product_url ); ?>">
					<?php echo wp_kses( $product_image, true ); ?>
				</a>
			</td>
			<td class="td xts-tbody-td xts-align-start">
				<a href="<?php echo esc_url( $product_url ); ?>">
					<?php echo esc_html( $product_name ); ?>
				</a>
			</td>
			<td class="td xts-tbody-td xts-align-start">
				<del>
					<?php echo wp_kses( $old_price, true ); ?>
				</del>
				<?php echo wp_kses( $new_price, true ); ?>
			</td>
			<td class="td xts-tbody-td xts-align-end">
				<a class="xts-add-to-cart" href="<?php echo esc_url( $add_to_cart_url ); ?>">
					<?php esc_html_e( 'Add to cart', 'woodmart' ); ?>
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

<p><?php echo wp_kses_post( __( 'Best regards, ', 'woodmart' ) ) . esc_html( $email->get_blogname() ); ?></p>
<p>
	<small>
		<?php
		echo wp_kses(
			sprintf(
				// translators: %s Unsubscribe link html.
				esc_html__( 'If you don\'t want to receive any further notification, please %s', 'woodmart' ),
				'<a href="' . esc_url( $unsubscribe_link ) . '">' . esc_html__( 'unsubscribe', 'woodmart' ) . '</a>'
			),
			true
		);
		?>
	</small>
</p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
