<?php
/**
 * Biteship API
 *
 * @package Biteship_For_WooCommerce
 */

class Biteship_API
{
    /**
     * Biteship API base URL
     */
    private $api_url;

    public function __construct()
    {
        $this->api_url = getenv("WORDPRESS_BITESHIP_API_URL") ?: "https://api.biteship.com";
    }

    /**
     * The single instance of the class
     */
    private static $instance = null;

    /**
     * Main Biteship_API Instance
     *
     * Ensures only one instance of Biteship_API is loaded or can be loaded.
     *
     * @return Biteship_API - Main instance.
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_gmaps_js_url()
    {
        return $this->api_url . "/v1/woocommerce/plugins/gmaps_js?key_id=" . urlencode(Biteship_Helper::get_setting("api_key_id"));
    }

    public function get_gmaps_js()
    {
        $args = [
            "method" => "GET",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Accept" => "application/javascript",
            ],
        ];

        $response = wp_remote_request($this->get_gmaps_js_url(), $args);

        if (is_wp_error($response)) {
            return $response;
        }

        $gmaps_js = wp_remote_retrieve_body($response);

        return $gmaps_js;
    }

    /**
     * Create an order via Biteship API
     *
     * @param array $order_data Order data to be sent to Biteship
     * @return array|WP_Error The API response or WP_Error on failure
     * @see https://biteship.com/id/docs/api/orders/create For API documentation
     */
    public function create_order($params)
    {
        $body = [
            "origin_contact_name" => $params["origin_contact_name"],
            "origin_contact_phone" => $params["origin_contact_phone"],
            "origin_postal_code" => isset($params["origin_postcode"]) ? $params["origin_postcode"] : null,
            "origin_coordinate" => isset($params["origin_coordinate"]) ? $params["origin_coordinate"] : null,
            "origin_address" => $params["origin_address"],
            "destination_contact_name" => $params["destination_contact_name"],
            "destination_contact_phone" => $params["destination_contact_phone"],
            "destination_postal_code" => isset($params["destination_postcode"]) ? $params["destination_postcode"] : null,
            "destination_coordinate" => isset($params["destination_coordinate"]) ? $params["destination_coordinate"] : null,
            "destination_address" => $params["destination_address"],
            "courier_company" => $params["courier_code"],
            "courier_type" => $params["service_code"],
            "delivery_type" => "now",
            "reference_id" => isset($params["reference_id"]) ? $params["reference_id"] : null,
            "webhooks" => [
                [
                    "name" => "Woocommerce Tracking status",
                    "events" => ["order.status"],
                    "url" => $params["callback_url"],
                ],
            ],
            "items" => $params["items"],
        ];

        if (isset($params["origin_coordinate"]) && isset($params["destination_coordinate"])) {
            $body["origin_coordinate"] = $params["origin_coordinate"];
            $body["destination_coordinate"] = $params["destination_coordinate"];
        } else {
            $body["origin_postal_code"] = $params["origin_postcode"];
            $body["destination_postal_code"] = $params["destination_postcode"];
        }

        if (isset($params["cash_on_delivery"]["amount"])) {
            $body["destination_cash_on_delivery"] = $params["cash_on_delivery"]["amount"];
        }

        if (isset($params["insurance"]["amount"])) {
            $body["courier_insurance"] = $params["insurance"]["amount"];
        }

        $args = [
            "method" => "POST",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Content-Type" => "application/json",
            ],
            "body" => wp_json_encode($body),
        ];

        return $this->make_request("/v1/woocommerce/orders", $args);
    }

    /**
     * Get an order via Biteship API
     *
     * @param string $order_id The order id
     * @return array|WP_Error The API response or WP_Error on failure
     * @see https://biteship.com/id/docs/api/orders/retrieve For API documentation
     */
    public function get_order($order_id)
    {
        $args = [
            "method" => "GET",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Accept" => "application/json",
            ],
        ];

        return $this->make_request("/v1/woocommerce/orders/{$order_id}", $args);
    }

    /**
     * Cancel an order via Biteship API
     *
     * @param string $order_id The order id
     * @return array|WP_Error The API response or WP_Error on failure
     * @see https://biteship.com/id/docs/api/orders/cancel For API documentation
     */
    public function cancel_order($order_id)
    {
        $args = [
            "method" => "DELETE",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Content-Type" => "application/json",
            ],
        ];

        return $this->make_request("/v1/woocommerce/orders/{$order_id}", $args);
    }

    /**
     * Get shipping rates from Biteship API
     *
     * @param array $rate_data Data required to get shipping rates.
     * @return array|WP_Error The API response or WP_Error on failure.
     * @see https://biteship.com/id/docs/couriers/retrieve For API documentation
     */
    public function get_rates($params)
    {
        $endpoint = "rates";

        // SHIP-1818: WooCommerce's own rate cache lives in the shopper's session, so it does
        // nothing for traffic that never keeps a cookie — crawlers hitting product pages get a
        // fresh session every time and recalculate from scratch. A transient keyed on the request
        // itself collapses those into one call, and is a plain win for real shoppers too.
        $cache_key = "biteship_rates_" . md5(wp_json_encode($params));
        $cached = get_transient($cache_key);
        if (false !== $cached) {
            return $cached;
        }

        $args = [
            "method" => "POST",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Content-Type" => "application/json",
            ],
            "body" => wp_json_encode($params),
        ];

        $response = $this->make_request("/v1/woocommerce/rates/couriers", $args);

        if (!is_array($response) || !$response["success"]) {
            return;
        }

        // Short TTL: long enough to absorb repeated identical lookups within a checkout session
        // or a crawl, short enough that a courier price change is picked up promptly.
        set_transient($cache_key, $response["pricing"], 10 * MINUTE_IN_SECONDS);

        return $response["pricing"];
    }

    public function get_subscription_info($api_key)
    {
        $args = [
            "method" => "POST",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Content-Type" => "application/json",
            ],
            "body" => wp_json_encode([
                "licence" => $api_key,
            ]),
        ];

        $response = $this->make_request("/v1/woocommerce/plugins/validate_key", $args);

        if (is_wp_error($response)) {
            return [
                "success" => false,
                "error" => __("Tidak dapat terhubung ke server Biteship. Silakan coba lagi.", "biteship-shipping"),
            ];
        }

        if (!is_array($response) || empty($response["success"])) {
            return [
                "success" => false,
                // Surface the backend's verbose message; fall back if absent.
                "error" => (is_array($response) && !empty($response["error"]))
                    ? $response["error"]
                    : __("Kunci WooCommerce tidak valid.", "biteship-shipping"),
            ];
        }

        return [
            "success" => true,
            "id" => $response["data"]["id"],
            "subscription_type" => $response["data"]["type"],
            "subscription_period" => $response["data"]["subscriptionPeriod"],
            "active" => $response["data"]["active"],
            "due_at" => $response["data"]["dueAt"],
            "status" => $response["data"]["status"],
            // Subscription plan (starter, grow, scale, enterprise); null when there is no active subscription.
            "subscription_tier" => isset($response["data"]["subscription_tier"]) ? $response["data"]["subscription_tier"] : null,
            "subscription_tier_label" => isset($response["data"]["subscription_tier_label"]) ? $response["data"]["subscription_tier_label"] : null,
            // Backend decides map access (Grow and above); missing means not eligible.
            "is_eligible_for_maps" => !empty($response["data"]["is_eligible_for_maps"]),
        ];
    }

    public function get_administrative_division_levels($params)
    {
        $args = [
            "method" => "GET",
            "headers" => [
                "Authorization" => "Bearer " . Biteship_Helper::get_setting("api_key"),
                "Accept" => "application/json",
            ],
        ];

        $query_params = [
            'level' => $params["level"]
        ];
        
        if (isset($params["administrative_division_level_id"])) {
            $query_params['administrative_division_level_id'] = $params["administrative_division_level_id"];
        }
        
        $url = "/v2/woocommerce/maps/administrative_division_levels?" . http_build_query($query_params);
        return $this->make_request($url, $args);
    }

    /**
     * Make an API request to Biteship
     *
     * @param string $endpoint The API endpoint
     * @param array $args The request arguments
     * @return array|WP_Error The API response or WP_Error on failure
     */
    private function make_request($endpoint, $args)
    {
        $url = $this->api_url . $endpoint;

        // SHIP-1818: identify the plugin version on every call. Without it there is no way to
        // measure adoption of a fix server-side, which is how we ended up guessing last time.
        if (!isset($args["headers"])) {
            $args["headers"] = [];
        }
        $args["headers"]["X-Biteship-Plugin-Version"] = BITESHIP_SHIPPING_VERSION;

        // SHIP-1818: a 60s timeout blocks a shopper's checkout for a full minute when the API is
        // slow, because calculate_shipping() runs synchronously in the checkout request. 15s is
        // well beyond our p99 and fails fast enough that the shopper is not stranded.
        if (!isset($args['timeout'])) {
            $args['timeout'] = 15;
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return $response;
        }

        $response_json = wp_remote_retrieve_body($response);
        $body = json_decode($response_json, true);

        return $body;
    }
}
