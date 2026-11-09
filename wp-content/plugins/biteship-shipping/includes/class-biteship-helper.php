<?php

// Exit if accessed directly.
if (!defined("ABSPATH")) {
    exit();
}

class Biteship_Helper
{
    public static function meta_key($str)
    {
        return "_wc_bts_" . $str;
    }

    public static function option_key($str)
    {
        return "wc_bts_" . $str;
    }

    public static function notice_key(...$args)
    {
        return "wc-bts-" . implode("-", $args);
    }

    public static function get_option($key)
    {
        return get_option(self::option_key($key));
    }

    public static function get_setting($key)
    {
        $settings = get_option("woocommerce_biteship_settings");
        return isset($settings[$key]) ? $settings[$key] : null;
    }

    public static function has_map_access()
    {
        // The backend decides map eligibility at key validation; the plugin never infers it from the plan.
        return !empty(self::get_setting("api_key_id")) && self::get_setting("is_eligible_for_maps") === "yes";
    }

    public static function is_complete_status_on_delivered()
    {
        $complete_status_on_delivered = self::get_setting("complete_status_on_delivered");
        return $complete_status_on_delivered === "yes";
    }

    public static function use_area_dropdown()
    {
        $use_area_dropdown = self::get_setting("use_area_dropdown");
        return $use_area_dropdown === "yes";
    }

    public static function get_gmaps_js_url()
    {
        if (!self::has_map_access()) {
            return;
        }

        $api = Biteship_API::get_instance();

        return $api->get_gmaps_js_url();
    }

    public static function get_coordinate_object($coordinate)
    {
        $points = explode(",", $coordinate);
        return [
            "latitude" => $points[0],
            "longitude" => $points[1],
        ];
    }

    public static function get_cod_fee($amount, $cod_percentage)
    {
        return $amount * ($cod_percentage / 100);
    }

    public static function get_insurance_fee($amount, $insurance_percentage)
    {
        return $amount * ($insurance_percentage / 100);
    }

    public static function get_weight_in_grams( WC_Product $product ) {
        $weight = (float) $product->get_weight();
        $unit = get_option( 'woocommerce_weight_unit' );
    
        switch ( $unit ) {
            case 'kg':  return $weight * 1000;
            case 'lbs': return $weight * 453.592;
            case 'oz':  return $weight * 28.3495;
            default:    return $weight;
        }
    }

    public static function is_billing_only()
    {
        return get_option("woocommerce_ship_to_destination") == "billing_only";
    }

    public static function get_items_from_order($order)
    {
        $items = [];
        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            $item_object = [
                "name" => $product->get_name(),
                "width" => $product->get_width(),
                "length" => $product->get_length(),
                "height" => $product->get_height(),
                "weight" => self::get_weight_in_grams($product),
                "description" => esc_html($product->get_short_description()),
                "quantity" => $item->get_quantity(),
                "value" => $product->get_price(),
                "sku" => $product->get_sku(),
            ];

            if (!isset($item_object["description"])) {
                $item_object["description"] = "Goods";
            }

            $item_object["description"] = esc_html($item_object["description"]);

            $items[] = $item_object;
        }

        return $items;
    }

    public static function get_items_from_package($package)
    {
        $items = [];
        foreach ($package->get_items() as $item_id => $item) {
            $product = $item->get_product();
            $item_object = [
                "name" => $product->get_name(),
                "width" => $product->get_width(),
                "length" => $product->get_length(),
                "height" => $product->get_height(),
                "weight" => self::get_weight_in_grams($product),
                "description" => esc_html($product->get_short_description()),
                "quantity" => $item->get_quantity(),
                "value" => $product->get_price(),
                "sku" => $product->get_sku(),
            ];

            if (!isset($item_object["description"])) {
                $item_object["description"] = "Goods";
            }

            $item_object["description"] = esc_html($item_object["description"]);

            $items[] = $item_object;
        }

        return $items;
    }
}
