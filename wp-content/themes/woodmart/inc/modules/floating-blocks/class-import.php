<?php
/**
 * Floating Blocks Import class for post types with metaboxes.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Floating_Blocks;

use WOODCORE_Import;
use XTS\Admin\Modules\Import\Helpers;
use XTS\Admin\Modules\Import\XML;

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Floating Blocks Import class for CPT with metaboxes.
 */
class Import {
	/**
	 * Helpers.
	 *
	 * @var Helpers
	 */
	private $helpers;

	/**
	 * Manager.
	 *
	 * @var Manager
	 */
	private $manager;

	/**
	 * Block types.
	 *
	 * @var array
	 */
	private $block_types;

	/**
	 * Module path for XML files.
	 *
	 * @var string
	 */
	private $module_path = '/inc/modules/floating-blocks/admin/predefined/';

	/**
	 * Imported post ID.
	 *
	 * @var int|null
	 */
	private $imported_post_id;

	/**
	 * Constructor method.
	 */
	public function __construct() {
		$this->helpers          = Helpers::get_instance();
		$this->manager          = Manager::get_instance();
		$this->block_types      = woodmart_get_config( 'fb-types' );
		$this->imported_post_id = null;

		$this->init_hooks();
	}

	/**
	 * Initialize WordPress hooks.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_filter( 'wp_import_existing_post', array( $this, 'force_new_post' ), 1, 2 );
		add_action( 'wp_import_insert_post', array( $this, 'store_imported_post_id' ), 10, 4 );
	}

	/**
	 * Store the ID of imported post.
	 *
	 * @param int   $post_id     The post ID.
	 * @param int   $original_id The original post ID from XML.
	 * @param array $postdata    The post data.
	 * @param array $post        The post array from XML.
	 */
	public function store_imported_post_id( $post_id, $original_id, $postdata, $post ) {
		if ( isset( $post['post_type'] ) && $this->manager->get_block_key_by_post_type( $post['post_type'] ) ) {
			$this->imported_post_id = $post_id;
		}
	}

	/**
	 * Always create a new post when importing specific post type.
	 *
	 * @param int   $post_exists ID of an existing post, or 0 if none exists.
	 * @param array $post        Post data from the import file.
	 *
	 * @return int New value for post ID — return 0 to force a new post to be created.
	 */
	public function force_new_post( $post_exists, $post ) {
		if ( isset( $post['post_type'] ) && $this->manager->get_block_key_by_post_type( $post['post_type'] ) ) {
			return 0;
		}

		return $post_exists;
	}

	/**
	 * Imports an XML file for a predefined content and processes the imported data.
	 *
	 * @param string $predefined_name The name of the predefined content to import.
	 * @param string $predefined_type The type of the predefined content to import.
	 * @param string $block_type      The block type key (e.g., 'floating-block', 'popup').
	 *
	 * @return int|false The ID of the newly created post on success, or false on failure.
	 */
	public function import_xml( $predefined_name, $predefined_type, $block_type = 'floating-block' ) {
		$external_builder = 'wpb' === woodmart_get_current_page_builder() ? 'wpb' : 'elementor';
		$builder          = 'native' === woodmart_get_opt( 'current_builder' ) ? 'gutenberg' : $external_builder;

		$file_path = WOODMART_THEMEROOT . $this->module_path . $block_type . '/' . $predefined_type . '/' . $predefined_name . '/';

		if ( 'wpb' === $builder ) {
			$file_path .= 'content.xml';
		} elseif ( 'elementor' === $builder ) {
			$file_path .= 'content-elementor.xml';
		} else {
			$file_path .= 'content-gutenberg.xml';
		}

		$this->imported_post_id = null;

		$post_type = '';

		if ( isset( $this->block_types[ $block_type ]['post_type'] ) ) {
			$post_type = $this->block_types[ $block_type ]['post_type'];
		}

		if ( ! $post_type ) {
			return false;
		}

		$posts_before = wp_count_posts( $post_type );
		$importer     = $this->get_importer();

		if ( ! $importer ) {
			return false;
		}

		$importer->fetch_attachments = true;
		$importer->import( $file_path );
		$posts_after = wp_count_posts( $post_type );

		if ( $posts_after->publish > $posts_before->publish && $this->imported_post_id ) {
			$post_id = $this->imported_post_id;
			$this->replace_content_urls( $post_id );
			return $post_id;
		}

		return false;
	}

	/**
	 * Replace dummy URLs with the correct site-specific URLs in the post content.
	 *
	 * @param int $post_id ID of the post to update.
	 */
	private function replace_content_urls( $post_id ) {
		global $wpdb;

		// @codingStandardsIgnoreStart
		$content = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_content FROM {$wpdb->posts} WHERE ID = %d",
				$post_id
			)
		);
		// @codingStandardsIgnoreEnd

		if ( ! $content ) {
			return;
		}

		$links = $this->helpers->links;

		foreach ( $links as $key => $old_urls ) {
			$new_url = ( 'uploads' === $key )
				? trailingslashit( wp_upload_dir()['baseurl'] )
				: trailingslashit( get_home_url() );

			foreach ( $old_urls as $old_url ) {
				$content = str_replace( $old_url, $new_url, $content );
			}
		}

		// @codingStandardsIgnoreStart
		$wpdb->update(
			$wpdb->posts,
			array( 'post_content' => $content ),
			array( 'ID' => $post_id ),
			array( '%s' ),
			array( '%d' )
		);
		// @codingStandardsIgnoreEnd

		clean_post_cache( $post_id );
	}

	/**
	 * Get importer instance using existing XML class functionality.
	 *
	 * @return WOODCORE_Import|false
	 */
	private function get_importer() {
		$xml_import = new XML();
		return $xml_import->get_importer();
	}
}
