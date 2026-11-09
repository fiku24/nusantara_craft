<?php

class Biteship_Admin_Rest_Controller
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register_routes()
    {
        register_rest_route("wc-biteship/v1", "/wc-orders/(?P<id>\d+)/tracking-status", [
            "methods" => "POST",
            "callback" => [$this, "update_tracking_status"],
            "permission_callback" => "__return_true",
        ]);

        register_rest_route("wc-biteship/v1", "/wc-orders/(?P<id>\d+)/shipment", [
            "methods" => "POST",
            "callback" => [$this, "create_shipment"],
            "permission_callback" => [$this, "create_shipment_permissions_check"],
        ]);
    }

    public function create_shipment_permissions_check(WP_REST_Request $request)
    {
        return current_user_can("manage_woocommerce") || wc_rest_check_permission("create", $request);
    }

    public function create_shipment(WP_REST_Request $request)
    {
        $order_id = $request->get_param("id");

        if (empty($order_id)) {
            return new WP_REST_Response(["message" => "Invalid parameters"], 400);
        }

        $order = wc_get_order($order_id);
        if (empty($order)) {
            return new WP_REST_Response(["message" => "Order not found."], 404);
        }

        if ($order->get_status() !== "processing") {
            return new WP_REST_Response(["message" => "Order status must be processing."], 422);
        }

        $tracking_status = $order->get_meta(Biteship_Helper::meta_key("tracking_status"));

        if (!empty($tracking_status) && !in_array($tracking_status, ["cancelled", "rejected", "returned", "courier_not_found"])) {
            return new WP_REST_Response(["message" => "Order already has active shipment."], 422);
        }

        $error_message = Biteship_Admin_Helper::create_shipment($order);
        if (is_string($error_message)) {
            return new WP_REST_Response(["message" => $error_message], 422);
        }

        return new WP_REST_Response($order->get_data(), 200);
    }

    public function update_tracking_status(WP_REST_Request $request)
    {
        $order_id = $request->get_param("id");

        if (empty($order_id)) {
            return new WP_REST_Response(["message" => "Invalid parameters"], 400);
        }

        $params = $request->get_json_params();
        $tracking_status = $params["status"];

        if (empty($tracking_status)) {
            return new WP_REST_Response(["message" => "Invalid parameters"], 400);
        }

        $order = wc_get_order($order_id);
        if (empty($order)) {
            return new WP_REST_Response(["message" => "Order not found."], 404);
        }

        if ($tracking_status == "delivered") {
            if (Biteship_Helper::is_complete_status_on_delivered()) {
                $order->update_status("completed");
            }
        }

        Biteship_Admin_Helper::apply_tracking_status_effects($order, $tracking_status);

        $order->update_meta_data(Biteship_Helper::meta_key("tracking_status"), $tracking_status);
        $order->save();

        return new WP_REST_Response(null, 204);
    }
}
