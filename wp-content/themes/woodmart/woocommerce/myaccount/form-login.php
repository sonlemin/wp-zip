<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$tabs         = woodmart_get_opt( 'login_tabs' );
$reg_text     = woodmart_get_opt( 'reg_text' );
$login_text   = woodmart_get_opt( 'login_text' );
$account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

$class  = 'wd-registration-page';
$class .= woodmart_get_old_classes( ' woodmart-registration-page' );

if ( $tabs && get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
	woodmart_enqueue_js_script( 'login-tabs' );
	$class .= ' wd-register-tabs';
	$class .= woodmart_get_old_classes( ' woodmart-register-tabs' );
}

if ( get_option( 'woocommerce_enable_myaccount_registration' ) !== 'yes' ) {
	$class .= ' wd-no-registration';
}

if ( $login_text && $reg_text ) {
	$class .= ' with-login-reg-info';
}

if ( isset( $_GET['action'] ) && 'register' === $_GET['action'] && $tabs ) {
	$class .= ' active-register';
}

// WC 3.5.0
if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.5.0', '<' ) ) {
	wc_print_notices();
}

woodmart_enqueue_inline_style( 'woo-mod-login-form' );
woodmart_enqueue_inline_style( 'woo-page-login-register' );

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="<?php echo esc_attr( $class ); ?>">

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="wd-grid-f-col" id="customer_login">

	<div class="wd-col col-login">

<?php endif; ?>

		<h2 class="wd-login-title"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

		<?php woodmart_login_form( true, add_query_arg( 'action', 'login', $account_link ) ); ?>

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	</div>

	<div class="wd-col col-register">

		<h2 class="wd-login-title"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

		<?php woodmart_register_form(); ?>

	</div>

	<?php if ( $tabs ) : ?>
		<div class="wd-col col-register-text">

			<p class="title wd-login-divider <?php echo woodmart_get_old_classes( ' wood-login-divider' ); ?>"><span><?php esc_html_e( 'Or', 'woodmart' ); ?></span></p>

			<?php
				$reg_title   = woodmart_get_opt( 'reg_title' ) ? woodmart_get_opt( 'reg_title' ) : esc_html__( 'Register', 'woocommerce' );
				$login_title = woodmart_get_opt( 'login_title' ) ? woodmart_get_opt( 'login_title' ) : esc_html__( 'Login', 'woocommerce' );

				$title = $reg_title;

			if ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) {
				$title = $login_title;
			}
			?>

			<?php if ( $login_text || $reg_text ) : ?>
				<h2 class="wd-login-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif ?>

			<?php if ( $login_text ) : ?>
				<div class="login-info"><?php echo do_shortcode( $login_text ); ?></div>
			<?php endif ?>

			<?php if ( $reg_text ) : ?>
				<div class="registration-info"><?php echo do_shortcode( $reg_text ); ?></div>
			<?php endif ?>

			<?php
				$button_text = esc_html__( 'Register', 'woocommerce' );

			if ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) {
				$button_text = esc_html__( 'Login', 'woocommerce' );
			}
			?>

			<a href="#" rel="nofollow noopener" class="btn wd-switch-to-register" data-login="<?php esc_html_e( 'Login', 'woocommerce' ); ?>" data-login-title="<?php echo esc_attr( $login_title ); ?>" data-reg-title="<?php echo esc_attr( $reg_title ); ?>" data-register="<?php esc_html_e( 'Register', 'woocommerce' ); ?>"><?php echo esc_html( $button_text ); ?></a>

		</div>
	<?php endif ?>

</div>
<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
