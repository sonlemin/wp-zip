<?php
/**
 * My account login map.
 *
 * @package Woodmart
 */

namespace XTS\Modules\Layouts;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget for my account login.
 */
class My_Account_Login extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_my_account_login';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Login', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-ma-login';
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
		return Main::is_layout_type( 'my_account_auth' );
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
				'default'      => 'wd-el-my-account-login',
				'prefix_class' => '',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'        => esc_html__( 'Enable title', 'woodmart' ),
				'description'  => esc_html__( 'If "NO" title will be removed.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'show',
				'return_value' => 'show',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Typography', 'woodmart' ),
				'selector'  => '{{WRAPPER}} .wd-login-title',
				'condition' => array(
					'title' => 'show',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wd-login-title' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'title' => 'show',
				),
			)
		);

		$this->add_control(
			'title_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'woodmart' ),
				'type'      => 'wd_buttons',
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/left.jpg',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/center.jpg',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/right.jpg',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wd-login-title' => 'text-align: {{VALUE}}',
				),
				'condition' => array(
					'title' => 'show',
				),
			)
		);

		$this->add_control(
			'button_alignment',
			array(
				'label'        => esc_html__( 'Button position', 'woodmart' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'left'       => esc_html__( 'Left', 'woodmart' ),
					'center'     => esc_html__( 'Center', 'woodmart' ),
					'right'      => esc_html__( 'Right', 'woodmart' ),
					'full-width' => esc_html__( 'Full width', 'woodmart' ),
				),
				'prefix_class' => 'wd-btn-align-',
				'default'      => 'full-width',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		Main::setup_preview();
		$account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$settings     = $this->get_settings_for_display();
		if ( 'show' === $settings['title'] ) {
			echo '<h2 class="wd-login-title">' . esc_html( 'Login', 'woocommerce' ) . '</h2>';
		}
		?>
		<?php woodmart_login_form( true, add_query_arg( 'action', 'login', $account_link ) ); ?>
		<?php
		Main::restore_preview();
	}
}

Plugin::instance()->widgets_manager->register( new My_Account_Login() );
