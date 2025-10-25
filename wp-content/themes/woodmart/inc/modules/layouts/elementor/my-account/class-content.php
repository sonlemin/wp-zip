<?php
/**
 * My account content map.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Layouts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use XTS\WC_Wishlist\Ui;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget for My Account Content.
 */
class My_Account_Content extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_my_account_content';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'My account content', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-ma-content';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'wd-my-account-elements' );
	}

	/**
	 * Show in panel.
	 *
	 * @return bool Whether to show the widget in the panel or not.
	 */
	public function show_in_panel() {
		return Main::is_layout_type( 'my_account_page' );
	}

	/**
	 * Register the widget controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'general_style_section',
			array(
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'css_classes',
			array(
				'type'         => 'wd_css_class',
				'default'      => 'wd-el-my-acc-content woocommerce-MyAccount-content',
				'prefix_class' => '',
			)
		);

		$this->add_control(
			'reviews_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Note: This element have not options', 'woodmart' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		Main::setup_preview();
		/**
		 * Hook: woocommerce_account_content.
		 */
		if ( (int) woodmart_get_opt( 'wishlist_page' ) === get_the_ID() ) {
			echo Ui::get_instance()->wishlist_page_content(); // phpcs:ignore.
		} else {
			remove_action( 'woocommerce_account_dashboard', 'woodmart_my_account_links', 10 );
			do_action( 'woocommerce_account_content' ); // phpcs:ignore.
		}

		Main::restore_preview();
	}
}

Plugin::instance()->widgets_manager->register( new My_Account_Content() );
