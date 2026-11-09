<?php

class Biteship_Admin_Notice
{
    private static $bulk_action_notices = [
        "cancel_shipments_failed" => "cancel_shipments_failed_notice",
        "create_shipments_failed" => "create_shipments_failed_notice",
        "shipments_cancelled" => "shipments_cancelled_notice",
        "shipments_created" => "shipments_created_notice",
    ];

    private static $action_notices = [
        "cancel_shipment_failed" => "cancel_shipment_failed_notice",
        "create_shipment_failed" => "create_shipment_failed_notice",
        "shipment_cancelled" => "shipment_cancelled_notice",
        "shipment_created" => "shipment_created_notice",
    ];

    public static function display_notices()
    {
        // Check if the nonce is present
        if (isset($_GET["wpnonce"])) {
            $nonce = sanitize_text_field(wp_unslash($_GET["wpnonce"]));

            // Verify nonce for bulk actions
            if (wp_verify_nonce($nonce, "bulk_action")) {
                if (isset($_GET["bulk_action"])) {
                    $bulk_action = sanitize_text_field(wp_unslash($_GET["bulk_action"]));

                    $query = [];

                    if (isset($_GET["failed"])) {
                        $failed = sanitize_text_field(wp_unslash($_GET["failed"]));
                        $query["failed"] = $failed;
                    }

                    if (isset($_GET["created"])) {
                        $created = sanitize_text_field(wp_unslash($_GET["created"]));
                        $query["created"] = $created;
                    }

                    if (isset($_GET["cancelled"])) {
                        $cancelled = sanitize_text_field(wp_unslash($_GET["cancelled"]));
                        $query["cancelled"] = $cancelled;
                    }

                    self::display_bulk_action_notices($bulk_action, $query);
                }
            }
        }

        if (is_admin()) {
            self::display_action_notices();
        }
    }

    public static function bulk_action_notice($redirect_url, $args)
    {
        if (isset($args["context"])) {
            set_transient(self::get_transient_name($args["name"]), $args["context"], 60);
        }

        if (isset($args["query"])) {
            foreach ($args["query"] as $key => $value) {
                $redirect_url = add_query_arg($key, urlencode($value), $redirect_url);
            }
        }

        $nonce = wp_create_nonce("bulk_action");
        $redirect_url = add_query_arg("wpnonce", $nonce, $redirect_url);

        return add_query_arg("bulk_action", $args["name"], $redirect_url);
    }

    public static function action_notice($name, $args)
    {
        $transient_name = self::get_transient_name($name);
        set_transient($transient_name, $args, 60);
    }

    private static function get_transient_name($name)
    {
        return "biteship-" . md5($name);
    }

    private static function display_bulk_action_notices($name, $query)
    {
        if (!isset(self::$bulk_action_notices[$name])) {
            return;
        }

        $notice = self::$bulk_action_notices[$name];
        if (!method_exists(__CLASS__, $notice)) {
            return;
        }

        self::$notice($query);
    }

    private static function display_action_notices()
    {
        $transient = self::get_transient_name("order_action");

        $payload = get_transient($transient);
        if (!$payload) {
            return;
        }

        $notice = $payload["name"] . "_notice";
        if (!method_exists(__CLASS__, $notice)) {
            return;
        }

        self::$notice($payload);

        delete_transient($transient);
    }

    private static function shipments_created_notice($query)
    {
        if (!isset($query["created"])) {
            return;
        }

        $created = $query["created"];

        /* translators: %d is the total of shipments created */
        wp_admin_notice(sprintf(__("%d shipments created.", "biteship-shipping"), $created), [
            "id" => "shipments_created",
            "dismissible" => true,
            "type" => "success",
        ]);
    }

    private static function shipments_cancelled_notice($query)
    {
        if (!isset($query["cancelled"])) {
            return;
        }

        $cancelled = $query["cancelled"];

        /* translators: %d is the total of shipments cancelled */
        wp_admin_notice(sprintf(__("%d shipments cancelled.", "biteship-shipping"), $cancelled), [
            "id" => "shipments_cancelled",
            "dismissible" => true,
            "type" => "success",
        ]);
    }

    private static function shipment_created_notice($payload)
    {
        wp_admin_notice(__("Shipment created.", "biteship-shipping"), [
            "id" => "shipment_created",
            "dismissible" => true,
            "type" => "success",
        ]);
    }

    private static function shipment_cancelled_notice($payload)
    {
        wp_admin_notice(__("Shipment cancelled.", "biteship-shipping"), [
            "id" => "shipment_cancelled",
            "dismissible" => true,
            "type" => "success",
        ]);
    }

    private static function create_shipments_failed_notice($query)
    {
        if (!isset($query["failed"])) {
            return;
        }

        $failed = $query["failed"];

        /* translators: %d is the total of failed shipments */
        wp_admin_notice(sprintf(__("Failed to create %d shipments.", "biteship-shipping"), $failed), [
            "id" => "create_shipments_failed",
            "dismissible" => true,
            "type" => "error",
        ]);

        $context = get_transient(self::get_transient_name("create_shipments_failed"));
        if (!$context) {
            return;
        }

        foreach ($context as $error) {
            wp_admin_notice($error["message"], [
                "id" => "create_shipments_failed",
                "dismissible" => true,
                "type" => "error",
            ]);
        }
    }

    private static function cancel_shipments_failed_notice($query)
    {
        if (!isset($query["failed"])) {
            return;
        }

        $failed = $query["failed"];

        /* translators: %d is the total of failed shipment cancellation */
        wp_admin_notice(sprintf(__("Failed to cancel %d shipments.", "biteship-shipping"), $failed), [
            "id" => "cancel_shipments_failed",
            "dismissible" => true,
            "type" => "error",
        ]);

        $context = get_transient(self::get_transient_name("cancel_shipments_failed"));
        if (!$context) {
            return;
        }

        foreach ($context as $error) {
            wp_admin_notice(esc_html($error["message"]), [
                "id" => "cancel_shipments_failed",
                "dismissible" => true,
                "type" => "error",
            ]);
        }
    }

    private static function create_shipment_failed_notice($payload)
    {
        if (!isset($payload["context"]) || !isset($payload["context"]["message"])) {
            return;
        }

        wp_admin_notice(esc_html($payload["context"]["message"]), [
            "id" => "create_shipment_failed",
            "dismissible" => true,
            "type" => "error",
        ]);
    }

    private static function cancel_shipment_failed_notice($payload)
    {
        if (!isset($payload["context"]) || !isset($payload["context"]["message"])) {
            return;
        }

        wp_admin_notice(esc_html($payload["context"]["message"]), [
            "id" => "cancel_shipment_failed",
            "dismissible" => true,
            "type" => "error",
        ]);
    }
}
