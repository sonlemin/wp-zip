<?php
if ( ! function_exists( 'wd_gutenberg_contact_form' ) ) {
	function wd_gutenberg_contact_form( $block_attributes ) {
		if ( ! $block_attributes['form_id'] || ! defined( 'WPCF7_PLUGIN' ) ) {
			return '<div id="' . wd_get_gutenberg_element_id( $block_attributes ) . '" class="wd-notice wd-info' . wd_get_gutenberg_element_classes( $block_attributes ) . '"><span>' . esc_html__( 'You need to create a form using Contact form 7 plugin to be able to display it using this element.', 'woodmart' ) . '</span></div>';
		}

		$el_class = '';
		if ( ! empty( $block_attributes['color_scheme'] ) ) {
			$el_class .= ' color-scheme-' . $block_attributes['color_scheme'];
		}

		if ( function_exists( 'wpcf7_enqueue_scripts' ) && ! wp_script_is( 'contact-form-7', 'registered' ) ) {
			$assets = include wpcf7_plugin_path( 'includes/js/index.asset.php' );

			$assets = wp_parse_args(
				$assets,
				array(
					'dependencies' => array(),
					'version'      => WPCF7_VERSION,
				)
			);

			wp_register_script(
				'contact-form-7',
				wpcf7_plugin_url( 'includes/js/index.js' ),
				array_merge(
					$assets['dependencies'],
					array( 'swv' )
				),
				$assets['version'],
				array( 'in_footer' => true )
			);

			wpcf7_enqueue_scripts();
		}

		return '<div id="' . wd_get_gutenberg_element_id( $block_attributes ) . '" class="' . wd_get_gutenberg_element_classes( $block_attributes, 'wd-cf7' ) . '">' . do_shortcode( '[contact-form-7 html_class="' . esc_attr( $el_class ) . '" id="' . esc_attr( $block_attributes['form_id'] ) . '"]' ) . '</div>';
	}
}
