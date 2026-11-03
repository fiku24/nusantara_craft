<?php

require get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';
/**
 * Recommended plugins.
 */
function tc_e_commerce_shop_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'             => __( 'Woocommerce', 'tc-e-commerce-shop' ),
			'slug'             => 'woocommerce',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		)
	);
	$config = array();
	tc_e_commerce_shop_tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'tc_e_commerce_shop_register_recommended_plugins' );