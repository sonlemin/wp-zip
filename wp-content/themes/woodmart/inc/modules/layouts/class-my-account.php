<?php

namespace XTS\Modules\Layouts;

class My_Account extends Layout_Type {

	/**
	 * Check.
	 *
	 * @param array  $condition Condition.
	 * @param string $type      Layout type.
	 */
	public function check( $condition, $type = '' ) {
		global $post;

		if ( empty( $post ) ) {
			return false;
		}

		$is_active   = false;
		$is_wishlist = (int) woodmart_get_opt( 'wishlist_page' ) === $post->ID;

		if ( 'my_account_page' === $type && ! is_wc_endpoint_url( 'lost-password' ) ) {
			if ( is_user_logged_in() ) {
				switch ( $condition['condition_type'] ) {
					case 'all':
						$is_active = is_account_page() || $is_wishlist || ( wp_is_serving_rest_request() && ! empty( $post ) && 'page' === $post->post_type );
						break;
					case 'dashboard':
						$is_active = is_account_page() && ! WC()->query->get_current_endpoint();
						break;
					default:
						$is_active = 'wishlist' === $condition['condition_type']
							? $is_wishlist
							: is_wc_endpoint_url( $condition['condition_type'] );
						break;
				}
			}
		} elseif ( 'my_account_auth' === $type && ! is_user_logged_in() && is_account_page() && ! is_wc_endpoint_url( 'lost-password' ) ) {
			return true;
		} elseif ( 'my_account_lost_password' === $type && is_wc_endpoint_url( 'lost-password' ) ) {
			return true;
		}

		return $is_active;
	}

	/**
	 * Override templates.
	 *
	 * @param  string $template  Template.
	 *
	 * @return bool|string
	 */
	public function override_template( $template ) {
		global $post;

		if ( woodmart_woocommerce_installed() && ( is_account_page() || ( ! empty( $post ) && (int) woodmart_get_opt( 'wishlist_page' ) === $post->ID ) ) && ( Main::get_instance()->has_custom_layout( 'my_account_page' ) || Main::get_instance()->has_custom_layout( 'my_account_auth' ) || Main::get_instance()->has_custom_layout( 'my_account_lost_password' ) ) ) {
			$this->display_template();
			return false;
		}

		return $template;
	}

	/**
	 * Display custom template on the single page.
	 */
	protected function display_template() {
		parent::display_template();
		$this->before_template_content();
		?>
		<div class="woocommerce entry-content">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php if ( Main::get_instance()->has_custom_layout( 'my_account_page' ) ) : ?>
					<?php $this->template_content( 'my_account_page' ); ?>
				<?php elseif ( Main::get_instance()->has_custom_layout( 'my_account_lost_password' ) ) : ?>
					<?php $this->template_content( 'my_account_lost_password' ); ?>
				<?php else : ?>
					<?php $this->template_content( 'my_account_auth' ); ?>
				<?php endif; ?>
			<?php endwhile; ?>
		</div>
		<?php
		$this->after_template_content();
	}

	/**
	 * Before template content.
	 */
	public function before_template_content() {
		get_header();
		?>
		<div class="wd-content-area site-content">
		<?php
	}

	/**
	 * Before template content.
	 */
	public function after_template_content() {
		?>
		</div>
		<?php
		get_footer();
	}

	/**
	 * Setup post data.
	 */
	public static function setup_postdata() {
		global $post;
		$post = get_post( get_option( 'woocommerce_myaccount_page_id' ) ); // phpcs:ignore.
		setup_postdata( $post );
	}

	/**
	 * Reset post data.
	 */
	public static function reset_postdata() {
		global $post;
		if ( isset( $post ) ) {
			wp_reset_postdata();
		}
	}
}

My_Account::get_instance();
