<?php
/**
 * Interface template.
 *
 * @package Woodmart
 *
 * @var Admin $admin        Admin instance.
 * @var string $block_key   Block key.
 */

$config      = woodmart_get_config( 'fb-types' );
$ajax_action = $config[ $block_key ]['ajax_action'];
$title_text  = esc_html__( 'Create floating block', 'woodmart' );

if ( 'popup' === $block_key ) {
	$title_text = esc_html__( 'Create popup', 'woodmart' );
}
?>
<div class="wd-add-fb" data-ajax-action="<?php echo esc_attr( $ajax_action ); ?>">
	<?php
	$admin->get_template(
		'popup',
		array(
			'btn_text'   => '',
			'title_text' => $title_text,
			'content'    => $admin->get_form( $block_key ),
		)
	);
	?>
</div>
