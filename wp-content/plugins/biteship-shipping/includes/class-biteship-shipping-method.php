<?php
/**
 * Biteship Shipping Method for WooCommerce
 *
 * @package Biteship_For_WooCommerce
 */

class Biteship_Shipping_Method extends WC_Shipping_Method
{
    private $api;

    public function __construct($instance_id = 0)
    {
        $this->id = "biteship";
        $this->title = __("Biteship shipping", "biteship-shipping");
        $this->instance_id = absint($instance_id);
        $this->method_title = __("Biteship shipping", "biteship-shipping");
        $this->method_description = __("Ship with ease with Biteship", "biteship-shipping");
        $this->supports = ["shipping-zones", "instance-settings", "instance-settings-modal"];
        $this->load_dependencies();
        $this->init();

        // Save settings in admin if you have any defined
        add_action("woocommerce_update_options_shipping_" . $this->id, [$this, "process_admin_options"]);
    }

    private function load_dependencies()
    {
        $this->api = Biteship_API::get_instance();
    }

    private function is_cod()
    {
        $chosen_payment_method = Biteship_Public_Helper::get_payment_method();

        if ($chosen_payment_method != "cod") {
            return false;
        }

        $cod_settings = Biteship_Public_Helper::get_cod_settings();
        $rate_id = implode(":", [$this->id, $this->instance_id]);

        if (!in_array($rate_id, $cod_settings["enable_for_methods"])) {
            return in_array($this->id, $cod_settings["enable_for_methods"]);
        }

        return true;
    }

    public function init()
    {
        // Load the settings API
        $this->init_form_fields();
        $this->init_settings();
        $this->init_instance_form_fields();
        $this->init_instance_settings();
    }

    public function init_form_fields()
    {
    }

    public function init_instance_form_fields()
    {
        $this->instance_form_fields = [
            "cod_preference" => [
                "title" => __("COD preference", "biteship-shipping"),
                "type" => "select",
                "description" => __("Specify whether cod fee will be based on biteship calculation or flat percentage.", "biteship-shipping"),
                "desc_tip" => true,
                "options" => [
                    "biteship" => __("Biteship", "biteship-shipping"),
                    "flat_percentage" => __("Flat Percentage", "biteship-shipping"),
                ],
            ],
            "cod_percentage" => [
                "title" => __("COD fee percent", "biteship-shipping"),
                "type" => "number",
                "description" => __("The base cost of COD (Cash on Delivery) is generally 4% of the item value.", "biteship-shipping"),
                "desc_tip" => true,
                "default" => "4",
                "custom_attributes" => [
                    "step" => "0.01",
                ],
            ],
            "insurance" => [
                "title" => __("Enable insurance", "biteship-shipping"),
                "type" => "select",
                "description" => __("Choose insurance configuration to use, whether it is disabled, enabled, or use global setting.", "biteship-shipping"),
                "desc_tip" => true,
                "options" => [
                    "enabled" => __("Enabled", "biteship-shipping"),
                    "disabled" => __("Disabled", "biteship-shipping"),
                ],
            ],
            "insurance_percentage" => [
                "title" => __("Insurance fee percentage", "biteship-shipping"),
                "type" => "number",
                "description" => __("The base cost of insurance is 0.5% of the item value.", "biteship-shipping"),
                "desc_tip" => true,
                "default" => "0.5",
                "custom_attributes" => [
                    "step" => "0.01",
                ],
            ],
        ];
    }

    public function calculate_shipping($package = [])
    {
        $store_postcode = get_option("woocommerce_store_postcode");
        $destination_postcode = isset($package["destination"]["postcode"]) ? $package["destination"]["postcode"] : null;
        $destination_coordinate = isset($package["destination"]["coordinate"]) ? $package["destination"]["coordinate"] : null;
        $store_coordinate = get_option("woocommerce_store_coordinate");

        if (!$store_postcode) {
            return;
        }

        // SHIP-1818: guard the DESTINATION, not just the store. WooCommerce recalculates shipping
        // on every package-hash change — including plain page views and every address keystroke —
        // which happens long before a shopper has entered an address. The API cannot price a
        // shipment without a destination, so every one of those calls was rejected: ~92 requests
        // per minute platform-wide, 97% of all WooCommerce rates traffic.
        // Returning here means no request is made at all, rather than one that is refused.
        $has_destination_postcode = is_scalar($destination_postcode) && "" !== trim((string) $destination_postcode);
        $has_destination_coordinate = is_array($destination_coordinate)
            ? [] !== array_filter($destination_coordinate, function ($value) {
                return is_scalar($value) && "" !== trim((string) $value);
            })
            : (is_scalar($destination_coordinate) && "" !== trim((string) $destination_coordinate));

        if (!$has_destination_postcode && !$has_destination_coordinate) {
            return;
        }

        $items = $this->build_rates_items($package);

        $params = [
            "origin_postal_code" => $store_postcode,
            "destination_postal_code" => $destination_postcode,
            "items" => $items,
        ];

        if (isset($store_coordinate) && isset($destination_coordinate)) {
            $origin_points = explode(",", $store_coordinate);
            $params["origin_latitude"] = $origin_points[0];
            $params["origin_longitude"] = $origin_points[1];

            $destination_points = explode(",", $destination_coordinate);
            $params["destination_latitude"] = $destination_points[0];
            $params["destination_longitude"] = $destination_points[1];
        }

        $rates = $this->api->get_rates($params);

        if (!isset($rates)) {
            return;
        }

        $cod_percentage = $this->get_cod_percentage();
        $insurance_percentage = $this->get_insurance_percentage();

        foreach ($rates as $rate) {
            $rate_object = [
                "id" => $this->rate_id($rate),
                "label" => $this->rate_label($rate),
                "cost" => $rate["price"],
                "package" => $package,
                "meta_data" => [
                    Biteship_Helper::meta_key("courier_code") => $rate["courier_code"],
                    Biteship_Helper::meta_key("service_code") => $rate["courier_service_code"],
                    Biteship_Helper::meta_key("cod") => $rate["available_for_cash_on_delivery"],
                    Biteship_Helper::meta_key("cod_fee") => isset($rate["cod_fee"]) ? $rate["cod_fee"] : null,
                    Biteship_Helper::meta_key("cod_preference") => $this->get_option("cod_preference"),
                    Biteship_Helper::meta_key("cod_percentage") => $cod_percentage,
                    Biteship_Helper::meta_key("insurance") => $this->get_option("insurance"),
                    Biteship_Helper::meta_key("insurance_percentage") => $insurance_percentage,
                ],
            ];

            $this->add_rate($rate_object);
        }
    }

    private function build_rates_items($package = [])
    {
        $items = [];

        foreach ($package["contents"] as $content) {
            $product = $content["data"];

            array_push($items, [
                "name" => $product->get_name(),
                "description" => esc_html($product->get_short_description()),
                "weight" => Biteship_Helper::get_weight_in_grams($product) ?: 1000,
                "width" => (int) ($product->get_width() ?: 1),
                "height" => (int) ($product->get_height() ?: 1),
                "length" => (int) ($product->get_length() ?: 1),
                "quantity" => $content["quantity"],
                "value" => (int) $product->get_price(),
                "sku" => $product->get_sku(),
            ]);
        }

        return $items;
    }

    private function rate_id($rate)
    {
        return $rate["courier_code"] . "." . $rate["courier_service_code"];
    }

    private function rate_label($rate)
    {
        return $rate["courier_name"] . " - " . $rate["courier_service_name"] . " (" . $rate["duration"] . ")";
    }

    private function get_insurance_percentage()
    {
        if ($this->get_instance_option("insurance") == "enabled") {
            return $this->get_instance_option("insurance_percentage");
        }
    }

    private function get_cod_percentage()
    {
        return $this->get_instance_option("cod_percentage");
    }
}
