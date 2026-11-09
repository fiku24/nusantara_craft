<?php

class Biteship_Public_Helper
{
    public static function is_cod($rate)
    {
        if (!$rate) {
            return false;
        }

        $chosen_payment_method = self::get_payment_method();
        if ($chosen_payment_method != "cod") {
            return false;
        }

        $cod_settings = self::get_cod_settings();
        $rate_id = implode(":", [$rate->get_method_id(), $rate->get_instance_id()]);
        $rate_meta = $rate->get_meta_data();

        if (!$rate_meta[Biteship_Helper::meta_key("cod")]) {
            return false;
        }

        if (!in_array($rate_id, $cod_settings["enable_for_methods"])) {
            return in_array($rate->get_method_id(), $cod_settings["enable_for_methods"]);
        }

        return true;
    }

    public static function is_insured($rate)
    {
        if (!$rate) {
            return false;
        }

        $rate_meta = $rate->get_meta_data();

        return $rate_meta[Biteship_Helper::meta_key("insurance")] == "enabled";
    }

    public static function get_cod_settings()
    {
        return get_option("woocommerce_cod_settings");
    }

    public static function get_payment_method()
    {
        return WC()->session->get("chosen_payment_method");
    }

    public static function get_shipping_methods()
    {
        return WC()->session->get("chosen_shipping_methods");
    }

    public static function get_rate()
    {
        $choosen_shipping_methods = self::get_shipping_methods();
        $shipping_packages = WC()->shipping()->get_packages();

        foreach ($shipping_packages as $package) {
            foreach ($package["rates"] as $rate_id => $rate) {
                if (in_array($rate_id, $choosen_shipping_methods)) {
                    return $rate;
                }
            }
        }

        return;
    }

    public static function get_cod_fee($cart, $rate)
    {
        $amount = 0;
        foreach ($cart->get_cart() as $cart_item) {
            $amount += $cart_item["line_total"];
        }

        $rate_meta = $rate->get_meta_data();
        if (isset($rate_meta[Biteship_Helper::meta_key("cod_preference")]) && $rate_meta[Biteship_Helper::meta_key("cod_preference")] == "biteship") {
            return $rate_meta[Biteship_Helper::meta_key("cod_fee")];
        }

        $cod_percentage = $rate_meta["_wc_bts_cod_percentage"];

        return Biteship_Helper::get_cod_fee($amount, $cod_percentage);
    }

    public static function get_insurance_fee($cart, $rate)
    {
        $amount = 0;
        foreach ($cart->get_cart() as $cart_item) {
            $amount += $cart_item["line_total"];
        }

        $rate_meta = $rate->get_meta_data();
        $insurance_percentage = $rate_meta[Biteship_Helper::meta_key("insurance_percentage")];

        return Biteship_Helper::get_insurance_fee($amount, $insurance_percentage);
    }
}
