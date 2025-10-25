<?php
/**
 * Review reminder emails html template.
 *
 * @package XTS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php echo esc_html__( 'Hi ', 'woodmart' ) . esc_html( $email->user_name ); ?></p>

<p><?php echo wp_kses_post( __( 'We hope you\'re enjoying your recent purchase from our store! Your opinion truly matters to us and helps other shoppers make informed choices.', 'woodmart' ) ); ?> 
</p>

<p><?php echo wp_kses_post( __( 'We\'d be incredibly grateful if you could take a minute to leave a quick review of the items you bought:', 'woodmart' ) ); ?></p>

<p><?php echo esc_html__( 'Your recent purchase:', 'woodmart' ); ?></p>

<table class="td xts-prod-table" cellspacing="0" cellpadding="6" border="1">
		<thead>
			<tr>
				<th class="td" scope="col"></th>
				<th class="td xts-align-start" scope="col"><?php esc_html_e( 'Product', 'woodmart' ); ?></th>
				<th class="td xts-align-end" scope="col"><?php esc_html_e( 'Leave a review', 'woodmart' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $email->object->item_list as $product_id => $data ) : ?>
				<tr>
					<td class="td xts-tbody-td xts-img-col xts-align-start">
						<a href="<?php echo esc_url( $data['permalink'] ); ?>">
							<?php
								$img_styles = 'vertical-align:middle;margin-' . is_rtl() ? 'left' : 'right' . ': 10px';
								$image_size = apply_filters( 'woodmart_review_reminder_email_thumbnail_item_size', array( 32, 32 ) );
							?>
							<div style="margin-bottom: 5px">
								<img src="<?php echo $data['image_id'] ? esc_url( current( wp_get_attachment_image_src( $data['image_id'], 'thumbnail' ) ) ) : esc_url( wc_placeholder_img_src() ); ?>" alt="<?php esc_attr_e( 'Product image', 'woodmart' ); ?>" height="<?php echo esc_attr( $image_size[1] ); ?>" width="<?php echo esc_attr( $image_size[0] ); ?>" style="<?php echo esc_attr( $img_styles ); ?>" />
							</div>
						</a>
					</td>
					<td class="td xts-tbody-td xts-align-start">
						<a href="<?php echo esc_url( $data['permalink'] ); ?>">
							<?php echo esc_html( $data['name'] ); ?>
						</a>
					</td>
					<td class="td xts-tbody-td xts-align-end">
						<a class="xts-add-to-cart xts-leave-review" href="<?php echo esc_url( $data['permalink'] ); ?>">
							<?php echo esc_html__( 'Leave a review', 'woodmart' ); ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<p><?php echo esc_html__( 'Your honest feedback helps us improve and continue offering products you love. Plus, it helps fellow customers get a better idea of what to expect.', 'woodmart' ); ?></p>

<p><?php echo esc_html__( 'Thank you for being a valued part of our community!', 'woodmart' ); ?></p>

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
