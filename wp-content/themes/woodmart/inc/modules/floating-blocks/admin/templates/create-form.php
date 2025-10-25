<?php
/**
 * Form template.
 *
 * @package Woodmart
 *
 * @var Admin  $admin      Admin instance.
 * @var string $block_key  Block key ("floating-block" or "popup").
 * @var array  $block_type Block type configuration.
 */

$config = $block_type['create_form'];
?>
<form>
	<div class="xts-popup-fields">
		<div class="xts-popup-field">
			<label for="wd_predefined_type">
				<?php echo esc_html( $config['label_text'] ); ?>
			</label>
			<select class="xts-fb-type" id="wd_floating_block" name="wd_predefined_type">
				<option value=""><?php esc_html_e( 'Empty', 'woodmart' ); ?></option>
				<?php foreach ( $block_type['templates'] as $template_key => $template_data ) : ?>
					<option value="<?php echo esc_attr( $template_key ); ?>"><?php echo esc_html( $template_data['title'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="xts-popup-field">
			<label for="wd_fb_name">
				<?php echo esc_html( $config['name_label'] ); ?>
			</label>
			<input class="xts-fb-name" id="wd_fb_name" name="wd_fb_name" type="text" placeholder="<?php esc_attr_e( $config['name_placeholder'], 'woodmart' ); ?>" required value="<?php esc_attr_e( $config['default_name'], 'woodmart' ); ?>">
		</div>
	</div>

	<?php foreach ( $block_type['templates'] as $template_key => $template_data ) : ?>
		<div class="xts-popup-predefined-layouts xts-images-set xts-hidden" data-type="<?php echo esc_attr( $template_key ); ?>">
			<div class="xts-popup-label"><?php echo esc_html( $config['templates_label'] ); ?></div>
			<div class="xts-btns-set">
				<?php foreach ( $template_data['layouts'] as $layout_key => $layout_data ) : ?>
					<div class="xts-popup-predefined-layout xts-set-item xts-set-btn-img" data-name="<?php echo esc_attr( $layout_key ); ?>">
						<img src="<?php echo esc_url( WOODMART_THEME_DIR . '/inc/modules/floating-blocks/admin/predefined/' . $block_key . '/' . $template_key . '/' . $layout_key . '/preview.jpg' ); ?>" alt="<?php echo esc_attr( $config['preview_alt'] ); ?>">
						<span class="xts-images-set-lable"><?php echo esc_html( $layout_data['title'] ); ?></span>
						<?php if ( ! empty( $layout_data['url'] ) ) : ?>
							<div class="xts-import-preview-wrap">
								<a href="<?php echo esc_url( $layout_data['url'] ); ?>" class="xts-btn xts-color-primary xts-import-item-preview xts-i-view" target="_blank">
									<?php esc_html_e( 'Live preview', 'woodmart' ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>

	<div class="xts-popup-actions xts-popup-actions-overlap">
		<button class="xts-add-floating-block-submit xts-btn xts-color-primary xts-i-add" type="submit">
			<?php echo esc_html( $config['submit_text'] ); ?>
		</button>
	</div>
</form>