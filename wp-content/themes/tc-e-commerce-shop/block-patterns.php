<?php
/**
 * TC E-Commerce Shop: Block Patterns
 *
 * @package TC E-Commerce Shop
 * @since   1.0.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'tc_e_commerce_shop',
		array( 'label' => __( 'TC E-Commerce Shop', 'tc-e-commerce-shop' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'tc_e_commerce_shop/banner-section',
		array(
			'title'      => __( 'Banner Section', 'tc-e-commerce-shop' ),
			'categories' => array( 'tc_e_commerce_shop' ),
			'content'    => "<!-- wp:cover {\"url\":\"" . esc_url(get_template_directory_uri()) . "/images/banner-image.png\",\"id\":3549,\"dimRatio\":0,\"isDark\":false,\"align\":\"full\",\"className\":\"tc_e_commerce_shop-banner-section\"} -->\n<div class=\"wp-block-cover alignfull is-light tc_e_commerce_shop-banner-section\"><span aria-hidden=\"true\" class=\"wp-block-cover__background has-background-dim-0 has-background-dim\"></span><img class=\"wp-block-cover__image-background wp-image-3549\" alt=\"\" src=\"" . esc_url(get_template_directory_uri()) . "/images/banner-image.png\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:columns -->\n<div class=\"wp-block-columns\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":1,\"style\":{\"typography\":{\"fontSize\":\"45px\",\"textTransform\":\"uppercase\"},\"color\":{\"text\":\"var(--primary-color)\"}}} -->\n<h1 class=\"has-text-align-center has-text-color\" style=\"color:var(--primary-color);font-size:45px;text-transform:uppercase\">Lorem Ipsum&nbsp;is simply dummy </h1>\n<!-- /wp:heading -->\n\n<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button {\"align\":\"center\",\"style\":{\"typography\":{\"fontSize\":\"14px\"},\"color\":{\"background\":\"var(--primary-color)\"}}} -->\n<div class=\"wp-block-button aligncenter has-custom-font-size\" style=\"font-size:14px\"><a class=\"wp-block-button__link has-background\" style=\"background-color:var(--primary-color)\">LEARN MORE</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:cover -->",
		)
	);

	register_block_pattern(
		'tc_e_commerce_shop/product-section',
		array(
			'title'      => __( 'Product Section', 'tc-e-commerce-shop' ),
			'categories' => array( 'tc_e_commerce_shop' ),
			'content'    => "<!-- wp:group {\"className\":\"tc_e_commerce_shop-featured-products-section pt-4 px-md-4\"} -->\n<div class=\"wp-block-group tc_e_commerce_shop-featured-products-section pt-4 px-md-4\"><!-- wp:paragraph {\"style\":{\"typography\":{\"fontSize\":\"24px\"}}} -->\n<p style=\"font-size:24px\">FEATURED PRODUCT</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:woocommerce/product-category {\"columns\":4,\"rows\":1,\"categories\":[18],\"contentVisibility\":{\"title\":true,\"price\":true,\"rating\":true,\"button\":true},\"className\":\"products-row \"} /--></div>\n<!-- /wp:group -->",
		)
	);
}