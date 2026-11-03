<?php
/**
 * @package TC E-Commerce Shop
 * @subpackage tc-e-commerce-shop
 * @since tc-e-commerce-shop 1.0
 * Setup the WordPress core custom header feature.
 *
 * @uses tc_e_commerce_shop_header_style()
*/

function tc_e_commerce_shop_custom_header_setup() {

	add_theme_support( 'custom-header', apply_filters( 'tc_e_commerce_shop_custom_header_args', array(
		'default-text-color' => 'fff',
		'header-text' 	     =>	false,
		'width'              => 1300,
		'height'             => 90,
		'flex-height'        => true,
	    'flex-width'         => true,
		'wp-head-callback'   => 'tc_e_commerce_shop_header_style',
	) ) );

}

add_action( 'after_setup_theme', 'tc_e_commerce_shop_custom_header_setup' );

if ( ! function_exists( 'tc_e_commerce_shop_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see tc_e_commerce_shop_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'tc_e_commerce_shop_header_style' );
function tc_e_commerce_shop_header_style() {
	//Check if user has defined any header image.
	if ( get_header_image() ) :
	$tc_e_commerce_shop_custom_css = "
        .full-header{
			background-image:url('".esc_url(get_header_image())."');
			background-position: center;
			background-size: 100% 100%;
		}";
	   	wp_add_inline_style( 'tc-e-commerce-shop-basic-style', $tc_e_commerce_shop_custom_css );
	endif;
}
endif; // tc_e_commerce_shop_header_style