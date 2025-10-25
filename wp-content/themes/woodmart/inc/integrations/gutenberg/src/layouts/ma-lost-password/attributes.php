<?php

use XTS\Gutenberg\Block_Attributes;

if ( ! function_exists( 'wd_get_my_account_lost_pass_attrs' ) ) {
	function wd_get_my_account_lost_pass_attrs() {
		$attr = new Block_Attributes();
		wd_get_advanced_tab_attrs( $attr );
		return $attr->get_attr();
	}
}
