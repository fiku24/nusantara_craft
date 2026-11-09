<?php

/**
 * Plugin Name: Biteship Shipping
 * Description: Integrate Biteship shipping services with your WooCommerce store.
 * Version: 1.6.0
 * Author: Biteship
 * Author URI: https://biteship.com
 * Text Domain: biteship-shipping
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * WC requires at least: 3.0
 * WC tested up to: 10.8
 * Requires Plugins: woocommerce
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Biteship_For_WooCommerce
 */

// Exit if accessed directly.
if (!defined("ABSPATH")) {
    exit();
}

if (in_array("woocommerce/woocommerce.php", apply_filters("active_plugins", get_option("active_plugins")))) {
    // Define plugin constants.
    define("BITESHIP_SHIPPING_VERSION", "1.6.0");
    define("BITESHIP_SHIPPING_FILE", __FILE__);
    define("BITESHIP_SHIPPING_PATH", plugin_dir_path(__FILE__));
    define("BITESHIP_SHIPPING_URL", plugin_dir_url(__FILE__));

    require BITESHIP_SHIPPING_PATH . "includes/class-biteship.php";

    // Declare that we support HPOS
    add_action("before_woocommerce_init", function () {
        if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility("custom_order_tables", __FILE__, true);
        }
    });

    $GLOBALS["biteship_for_woocommerce"] = Biteship::get_instance();
}
