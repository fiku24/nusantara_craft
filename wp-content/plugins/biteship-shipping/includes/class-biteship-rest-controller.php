<?php

class Biteship_Rest_Controller
{
    public function register_routes()
    {
        register_rest_route("wc-biteship/v1", "/gmaps.js", [
            "methods" => "GET",
            "callback" => [$this, "get_gmaps_js"],
            "permission_callback" => "__return_true", // Adjust as needed.
        ]);
    }

    public function get_gmaps_js(WP_REST_Request $request)
    {
        $api = Biteship_API::get_instance();
        $gmaps_js = $api->get_gmaps_js();

        if (is_wp_error($gmaps_js)) {
            return new WP_REST_Response(["error" => "Cannot load maps."], 500);
        }

        header("Content-Type: application/javascript");
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $gmaps_js;
        exit();
    }
}
