<?php
/**
 * Clothes Boutique functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @subpackage Clothes Boutique
 * @since Clothes Boutique 1.0
 */

if ( ! function_exists( 'clothes_boutique_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function clothes_boutique_setup() {
	load_theme_textdomain( 'clothes-boutique', get_template_directory() . '/languages' );
}
endif;
add_action( 'after_setup_theme', 'clothes_boutique_setup' );

function clothes_boutique_block_assets(){
	wp_enqueue_style( 'clothes-boutique-fontawesome', get_template_directory_uri() . '/assets/font-awesome/css/all.css', array(), '7.1.0' );
	wp_enqueue_style( 'clothes-boutique-animatecss', get_template_directory_uri() . '/assets/css/animate.css');
	wp_enqueue_style( 'clothes-boutique-slick-style', get_template_directory_uri().'/assets/css/slick.css' );
	wp_enqueue_style( 'clothes-boutique-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script('clothes-boutique-wow-script', get_template_directory_uri() . '/assets/js/wow.js', array('jquery'));
	wp_enqueue_script( 'clothes-boutique-slick-js', get_template_directory_uri(). '/assets/js/slick.js', array('jquery') ,'',true);
	wp_enqueue_script('clothes-boutique-script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0.0', true);
	wp_style_add_data( 'clothes-boutique-style', 'rtl', 'replace' );
}
add_action('enqueue_block_assets', 'clothes_boutique_block_assets');

/**
 * Load TGM.
 */
require get_template_directory() . '/inc/tgm/tgm.php';

/**
 * Notice.
 */
require_once get_template_directory() . '/inc/notice/notice.php';

/**
 * Theme Info Page.
 */
require get_template_directory() . '/inc/addon.php';

/**
 * Customizer
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Demo import
 */
require get_template_directory() . '/inc/demo-content.php';

function clothes_boutique_setup_theme() {
	if ( ! defined( 'CLOTHES_BOUTIQUE_PREMIUM_PAGE' ) ) {
		define('CLOTHES_BOUTIQUE_PREMIUM_PAGE',__('https://www.theclassictemplates.com/products/clothing-store-wordpress-theme','clothes-boutique'));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_PRO_NAME' ) ) {
		define( 'CLOTHES_BOUTIQUE_PRO_NAME', __( 'About Clothes Boutique', 'clothes-boutique' ));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_THEME_PAGE' ) ) {
		define('CLOTHES_BOUTIQUE_THEME_PAGE',__('https://www.theclassictemplates.com/collections/best-wordpress-templates','clothes-boutique'));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_SUPPORT' ) ) {
		define('CLOTHES_BOUTIQUE_SUPPORT',__('https://wordpress.org/support/theme/clothes-boutique/','clothes-boutique'));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_REVIEW' ) ) {
		define('CLOTHES_BOUTIQUE_REVIEW',__('https://wordpress.org/support/theme/clothes-boutique/reviews/','clothes-boutique'));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_PRO_DEMO' ) ) {
		define('CLOTHES_BOUTIQUE_PRO_DEMO',__('https://live.theclassictemplates.com/clothes-boutique-pro/','clothes-boutique'));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_THEME_DOCUMENTATION' ) ) {
		define('CLOTHES_BOUTIQUE_THEME_DOCUMENTATION',__('https://live.theclassictemplates.com/demo/docs/clothes-boutique-free/','clothes-boutique'));
	}
	if ( ! defined( 'CLOTHES_BOUTIQUE_BUNDLE_PAGE' ) ) {
		define('CLOTHES_BOUTIQUE_BUNDLE_PAGE',__('https://www.theclassictemplates.com/products/wordpress-theme-bundle','clothes-boutique'));
	}
}
add_action('after_setup_theme', 'clothes_boutique_setup_theme');

function clothes_boutique_enqueue_admin_script($hook) {
    // Enqueue admin JS for notices
    wp_enqueue_script('clothes-boutique-welcome-notice', get_template_directory_uri() . '/inc/notice/notice.js', array('jquery'), '', true);
    
    // Localize script to pass data to JavaScript
wp_localize_script('clothes-boutique-welcome-notice', 'clothes_boutique_localize', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('clothes_boutique_welcome_nonce'),
    'demo_nonce' => wp_create_nonce('clothes_boutique_demo_nonce'), // added
    'dismiss_nonce' => wp_create_nonce('clothes_boutique_welcome_nonce'), 
    'redirect_url' => admin_url('themes.php?page=clothes-boutique')
));
}
add_action('admin_enqueue_scripts', 'clothes_boutique_enqueue_admin_script');

add_action('wp_ajax_check_plugin_activation', function () {
    if (!isset($_POST['plugin']) || empty($_POST['plugin'])) {
        wp_send_json_error(['message' => 'Missing plugin identifier']);
    }

    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    // Map plugin identifiers to their main files
    $clothes_boutique_plugin_map = [
    	'woocommerce'                  => 'woocommerce/woocommerce.php',
        'ibtana'               		   => 'ibtana-visual-editor/plugin.php',
        'ibtana-ecommerce'             => 'ibtana-ecommerce-product-addons/plugin.php'
    ];

    $clothes_boutique_requested_plugin = sanitize_text_field($_POST['plugin']);

    if (!isset($clothes_boutique_plugin_map[$clothes_boutique_requested_plugin])) {
        wp_send_json_error(['message' => 'Invalid plugin']);
    }

    $clothes_boutique_plugin_file = $clothes_boutique_plugin_map[$clothes_boutique_requested_plugin];
    $clothes_boutique_is_active   = is_plugin_active($clothes_boutique_plugin_file);

    wp_send_json_success(['active' => $clothes_boutique_is_active]);
});
add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );

add_action( 'admin_init', function() {
  update_option( 'essential_blocks_quick_setup_shown', true );
  update_option( 'essential_blocks_user_type', 'old' );
  remove_submenu_page( 'admin.php', 'eb-quick-setup' );
});

add_action('init', function() {
    if ( get_option('__gutentor_do_redirect') ) {
        update_option('__gutentor_do_redirect', false);
    }
});