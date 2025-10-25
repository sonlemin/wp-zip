<?php

namespace XTS\Modules\Floating_Blocks;

use XTS\Singleton;

class Main extends Singleton {
	/**
	 * Constructor.
	 */
	public function init() {
		define( 'XTS_FLOATING_BLOCKS_DIR', WOODMART_THEMEROOT . '/inc/modules/floating-blocks/' );
		add_action( 'init', array( $this, 'include_files' ), 10 );
	}

	/**
	 * Include required admin files.
	 */
	public function include_files() {
		if ( woodmart_is_elementor_installed() ) {
			require_once XTS_FLOATING_BLOCKS_DIR . '/integrations/elementor/class-fb-doc.php';
			require_once XTS_FLOATING_BLOCKS_DIR . '/integrations/elementor/class-popup-doc.php';
		}

		// WPBakery.
		if ( defined( 'WPB_VC_VERSION' ) && ! isset( $_GET['vcv-gutenberg-editor'] ) ) { // phpcs:ignore.
			require_once XTS_FLOATING_BLOCKS_DIR . '/integrations/wpb/class-fb.php';
			require_once XTS_FLOATING_BLOCKS_DIR . '/integrations/wpb/class-popup.php';
		}

		if ( woodmart_get_opt( 'gutenberg_blocks' ) ) {
			require_once XTS_FLOATING_BLOCKS_DIR . '/integrations/gutenberg/class-popup.php';
			require_once XTS_FLOATING_BLOCKS_DIR . '/integrations/gutenberg/class-fb.php';
		}

		require_once XTS_FLOATING_BLOCKS_DIR . '/class-import.php';
		require_once XTS_FLOATING_BLOCKS_DIR . '/class-manager.php';
		require_once XTS_FLOATING_BLOCKS_DIR . '/class-admin.php';
		require_once XTS_FLOATING_BLOCKS_DIR . '/class-frontend.php';
	}
}

Main::get_instance();
