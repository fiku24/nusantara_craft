<?php

// Exit if accessed directly.
if (!defined("ABSPATH")) {
    exit();
}

class Biteship_Admin_Helper
{
    public static function get_store_address()
    {
        $store_address = get_option("woocommerce_store_address");
        $store_address_2 = get_option("woocommerce_store_address_2");

        if (!empty($store_address_2)) {
            $store_address .= " " . $store_address_2;
        }

        return $store_address;
    }

    public static function get_customer_address($order)
    {
        $customer_address = $order->get_shipping_address_1();
        $customer_address_2 = $order->get_shipping_address_2();

        if (isset($customer_address_2)) {
            $customer_address .= " " . $customer_address_2;
        }

        return $customer_address;
    }

    public static function get_rate($order)
    {
        $rate_params = self::build_rates_params($order);
        $rates = Biteship_API::get_instance()->get_rates($rate_params);

        if (!is_array($rates)) {
            return isset($rate_response["error"]) ? $rate_response["error"] : __("An unknown error occurred while fetching the rate.", "biteship-shipping");
        }

        foreach ($rates as $rate) {
            $match_courier_code = $rate["courier_code"] == $rate_params["courier_code"];
            $match_service_code = $rate["courier_service_code"] == $rate_params["service_code"];
            if ($match_courier && $match_service_code) {
                return $rate["price"];
            }
        }
    }

    public static function is_legacy_order($order)
    {
        return $order->meta_exists("_billing_biteship_address") ||
            $order->meta_exists("_billing_biteship_city") ||
            $order->meta_exists("_billing_biteship_district") ||
            $order->meta_exists("_billing_biteship_location") ||
            $order->meta_exists("_billing_biteship_location_coordinate") ||
            $order->meta_exists("_billing_biteship_province") ||
            $order->meta_exists("_billing_biteship_zipcode") ||
            $order->meta_exists("_shipping_address_index") ||
            $order->meta_exists("_shipping_biteship_address") ||
            $order->meta_exists("_shipping_biteship_city") ||
            $order->meta_exists("_shipping_biteship_district") ||
            $order->meta_exists("_shipping_biteship_location") ||
            $order->meta_exists("_shipping_biteship_location_coordinate") ||
            $order->meta_exists("_shipping_biteship_province") ||
            $order->meta_exists("_shipping_biteship_zipcode");
    }

    public static function is_legacy_order_insured($order)
    {
        foreach ($order->get_fees() as $fee) {
            $fee_name = strtolower($fee->get_name());
            if (strpos($fee_name, "insurance") || strpos($fee_name, "asuransi")) {
                return true;
            }
        }

        return false;
    }

    public static function create_shipment($order)
    {
        if (!self::acquire_shipment_lock($order)) {
            return __("A shipment is already being created for this order.", "biteship-shipping");
        }

        try {
            $order_params = self::build_order_params($order);
            $order_response = Biteship_API::get_instance()->create_order($order_params);

            if (!is_array($order_response) || empty($order_response["success"])) {
                $existing_shipment = self::existing_shipment_from_duplicate($order_response);
                if (is_array($existing_shipment)) {
                    self::save_created_shipment($order, $existing_shipment, $order_params["reference_id"]);
                    return true;
                }

                return isset($order_response["error"]) ? $order_response["error"] : __("An unknown error occurred while creating the shipment.", "biteship-shipping");
            }

            self::save_created_shipment($order, $order_response, $order_params["reference_id"]);

            return true;
        } finally {
            self::release_shipment_lock($order);
        }
    }

    public static function refresh_shipment($order)
    {
        if ($order->get_status() === "completed") {
            return false;
        }

        $order_id = $order->get_meta(Biteship_Helper::meta_key("order_id"));
        if (empty($order_id)) {
            $order_id = $order->get_meta("biteship_order_id");
        }

        if (empty($order_id)) {
            return false;
        }

        $order_response = Biteship_API::get_instance()->get_order($order_id);
        if (!isset($order_response["success"]) || !$order_response["success"]) {
            return false;
        }

        self::apply_tracking_status_effects($order, $order_response["status"]);

        $order->update_meta_data(Biteship_Helper::meta_key("order_id"), $order_response["id"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_id"), $order_response["courier"]["tracking_id"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_number"), $order_response["courier"]["waybill_id"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_status"), $order_response["status"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_url"), $order_response["courier"]["link"]);

        if ($order_response["status"] == "delivered") {
            if (Biteship_Helper::is_complete_status_on_delivered()) {
                $order->update_status("completed");
            }
        }

        $order->save();

        return true;
    }

    public static function cancel_shipment($order)
    {
        $order_response = Biteship_API::get_instance()->cancel_order($order->get_meta(Biteship_Helper::meta_key("order_id")));

        if (!is_array($order_response) || !$order_response["success"]) {
            return isset($order_response["error"]) ? $order_response["error"] : __("An unknown error occurred while cancelling the shipment.", "biteship-shipping");
        }

        $order->update_meta_data(Biteship_Helper::meta_key("tracking_status"), "cancelled");
        $order->save();

        return true;
    }

    /**
     * Stable id for the active Biteship shipment of this WooCommerce order.
     * Biteship rejects a second non-cancelled order with the same reference.
     * Returned and rejected shipments keep the reference, so those use a suffix.
     */
    public static function shipment_reference_id($order)
    {
        $order_id = $order->get_id();
        $seq = (int) $order->get_meta(Biteship_Helper::meta_key("shipment_seq"));

        if ($seq > 0) {
            return "WC-{$order_id}-{$seq}";
        }

        return "WC-{$order_id}";
    }

    /**
     * Prepare the next reference when a shipment ends in a status that still
     * holds its reference_id, and put a cancelled WooCommerce order back to processing.
     */
    public static function apply_tracking_status_effects($order, $tracking_status)
    {
        if (in_array($tracking_status, ["returned", "rejected"], true)) {
            $current_status = $order->get_meta(Biteship_Helper::meta_key("tracking_status"));
            if ($current_status !== $tracking_status) {
                $seq = (int) $order->get_meta(Biteship_Helper::meta_key("shipment_seq"));
                $order->update_meta_data(Biteship_Helper::meta_key("shipment_seq"), $seq + 1);
            }
        }

        if ($tracking_status === "cancelled" && $order->get_status() === "cancelled") {
            $order->update_status(
                "processing",
                __("Biteship shipment was cancelled. You can create a new shipment.", "biteship-shipping")
            );
        }
    }

    private static function shipment_lock_key($order)
    {
        return "wc_bts_create_shipment_" . $order->get_id();
    }

    private static function acquire_shipment_lock($order)
    {
        $lock_key = self::shipment_lock_key($order);
        $existing = get_option($lock_key, false);

        if (false !== $existing && (time() - (int) $existing) < 2 * MINUTE_IN_SECONDS) {
            return false;
        }

        if (false !== $existing) {
            delete_option($lock_key);
        }

        return add_option($lock_key, time(), "", false);
    }

    private static function release_shipment_lock($order)
    {
        delete_option(self::shipment_lock_key($order));
    }

    private static function existing_shipment_from_duplicate($order_response)
    {
        if (!is_array($order_response) || !isset($order_response["code"]) || (int) $order_response["code"] !== 40002060) {
            return null;
        }

        if (empty($order_response["details"]["order_id"])) {
            return null;
        }

        $existing = Biteship_API::get_instance()->get_order($order_response["details"]["order_id"]);
        if (!is_array($existing) || empty($existing["success"])) {
            return null;
        }

        return $existing;
    }

    private static function save_created_shipment($order, $order_response, $reference_id)
    {
        $order->update_meta_data(Biteship_Helper::meta_key("order_id"), $order_response["id"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_id"), $order_response["courier"]["tracking_id"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_number"), $order_response["courier"]["waybill_id"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_status"), $order_response["status"]);
        $order->update_meta_data(Biteship_Helper::meta_key("tracking_url"), $order_response["courier"]["link"]);
        $order->update_meta_data(Biteship_Helper::meta_key("reference_id"), $reference_id);
        $order->save();
    }

    public static function is_cod($order)
    {
        return $order->get_payment_method() == "cod";
    }

    public static function get_items_summary($items = [])
    {
        $items_summary = [
            "value" => 0,
        ];

        foreach ($items as $item) {
            $items_summary["value"] += $item["value"] * $item["quantity"];
        }

        return $items_summary;
    }

    public static function get_store_coordinate()
    {
        $store_coordinate = get_option("woocommerce_store_coordinate");
        return Biteship_Helper::get_coordinate_object($store_coordinate);
    }

    public static function get_store_postal_code()
    {
        $store_postcode = get_option("woocommerce_store_postcode");
        return $store_postcode;
    }

    public static function get_customer_coordinate($order)
    {
        $destination_coordinate = $order->get_meta("_shipping_coordinate");

        // NOTE: Legacy support
        if (empty($destination_coordinate)) {
            $destination_coordinate = $order->get_meta("_shipping_biteship_location_coordinate");
        }

        return Biteship_Helper::get_coordinate_object($destination_coordinate);
    }

    public static function get_customer_postal_code($order)
    {
        return $order->get_shipping_postcode();
    }

    public static function build_rates_params($order)
    {
        $shipping_methods = $order->get_shipping_methods();
        foreach ($shipping_methods as $shipping_method) {
            if ($shipping_method->get_method_id() !== "biteship") {
                continue;
            }

            $courier_code = $shipping_method->get_meta(Biteship_Helper::meta_key("courier_code"), true);
            $service_code = $shipping_method->get_meta(Biteship_Helper::meta_key("service_code"), true);
        }

        $params = [
            "courier_code" => $courier_code,
            "service_code" => $service_code,
        ];

        if (in_array($courier_code, ["gojek", "grab", "lalamove", "borzo", "deliveree", "paxel"])) {
            $origin_coordinate = self::get_store_coordinate();
            $destination_coordinate = self::get_customer_coordinate($order);

            $params["origin_latitude"] = $origin_coordinate["latitude"];
            $params["origin_longitude"] = $origin_coordinate["longitude"];
            $params["destination_latitude"] = $destination_coordinate["latitude"];
            $params["destination_longitude"] = $destination_coordinate["longitude"];
        } else {
            $params["origin_postal_code"] = self::get_store_postal_code();
            $params["destination_postal_code"] = self::get_customer_postal_code();
        }

        $params["items"] = Biteship_Helper::get_items_from_order($order);

        if (self::is_cod($order)) {
            $params["cod"] = true;
        }

        return $params;
    }

    public static function build_order_params($order)
    {
        $order_id = $order->get_id();
        $store_shipper_name = get_option("woocommerce_store_shipper_name");
        $store_shipper_phone = get_option("woocommerce_store_shipper_phone");
        $shipping_methods = $order->get_shipping_methods();
        $total_price = $order->get_total();

        foreach ($shipping_methods as $shipping_method) {
            if ($shipping_method->get_method_id() !== "biteship") {
                continue;
            }

            $courier_code = $shipping_method->get_meta(Biteship_Helper::meta_key("courier_code"), true);
            $service_code = $shipping_method->get_meta(Biteship_Helper::meta_key("service_code"), true);
            $insured = $shipping_method->get_meta(Biteship_Helper::meta_key("insurance"), true) == "enabled";

            // NOTE: Legacy support
            if (empty($courier_code)) {
                $courier_code = $shipping_method->get_meta("courier_code");
            }

            // NOTE: Legacy support
            if (empty($service_code)) {
                $service_code = $shipping_method->get_meta("courier_service_code");
            }

            // NOTE: Legacy support
            if (empty($insured)) {
                $insured = self::is_legacy_order_insured($order);
            }
        }

        $callback_url = get_rest_url(null, "wc-biteship/v1/wc-orders/$order_id/tracking-status");

        $params = [
            "origin_contact_name" => $store_shipper_name,
            "origin_contact_phone" => $store_shipper_phone,
            "origin_address" => self::get_store_address(),
            "destination_contact_name" => $order->get_shipping_first_name() . " " . $order->get_shipping_last_name(),
            "destination_contact_phone" => $order->get_billing_phone(),
            "destination_address" => self::get_customer_address($order),
            "courier_code" => $courier_code,
            "service_code" => $service_code,
            "callback_url" => $callback_url,
            "reference_id" => self::shipment_reference_id($order),
        ];

        if (in_array($courier_code, ["gojek", "grab", "lalamove", "borzo", "deliveree", "paxel"])) {
            $params["origin_coordinate"] = self::get_store_coordinate();
            $params["destination_coordinate"] = self::get_customer_coordinate($order);
        } elseif (in_array($courier_code, ["lion", "pos"])) {
            $params["origin_coordinate"] = self::get_store_coordinate();
            $params["origin_postcode"] = self::get_store_postal_code();
            $params["destination_postcode"] = self::get_customer_postal_code($order);
        } else {
            $params["origin_postcode"] = self::get_store_postal_code();
            $params["destination_postcode"] = self::get_customer_postal_code($order);
        }

        $params["items"] = Biteship_Helper::get_items_from_order($order);
        $items_summary = self::get_items_summary($params["items"]);

        if (self::is_cod($order)) {
            $params["cash_on_delivery"]["amount"] = $total_price;
        }

        if ($insured) {
            $params["insurance"]["amount"] = $items_summary["value"];
        }

        return $params;
    }
}
