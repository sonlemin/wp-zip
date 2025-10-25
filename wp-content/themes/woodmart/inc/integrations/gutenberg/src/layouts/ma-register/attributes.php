<?php

use XTS\Gutenberg\Block_Attributes;

if ( ! function_exists( 'wd_get_my_account_register_attrs' ) ) {
	function wd_get_my_account_register_attrs() {
		$attr = new Block_Attributes();

		$attr->add_attr(
			array(
				'btnAlign' => array(
					'type'       => 'string',
					'responsive' => true,
				),
			)
		);

		wd_get_advanced_tab_attrs( $attr );
		return $attr->get_attr();
	}
}
