<?php

// Exit if accessed directly.
if (!defined("ABSPATH")) {
    exit();
}

class Biteship_Admin
{
    private Biteship_API $api;
    private Biteship_Admin_Rest_Controller $rest_controller;

    public function __construct()
    {
        $this->api = Biteship_API::get_instance();
        $this->rest_controller = Biteship_Admin_Rest_Controller::get_instance();
    }

    public function init()
    {
        $this->register_hooks();
    }

    public function register_hooks()
    {
        // Register endpoint
        add_action("rest_api_init", [$this->rest_controller, "register_routes"]);
        // // Inject Javascript
        add_action("admin_enqueue_scripts", [$this, "enqueue_scripts"]);
        // // Add store coordinate on General Settings
        add_filter("woocommerce_get_settings_general", [$this, "register_store_fields"]);
        // Inject Map on General Settings
        add_action("woocommerce_settings_general_options_after", [$this, "include_map_html"]);
        // Debug code
        add_action("woocommerce_admin_order_data_after_order_details", [$this, "order_debug"]);
        // Add biteship actions
        add_filter("bulk_actions-edit-shop_order", [$this, "add_bulk_orders_actions"], 10); // Legacy
        add_filter("bulk_actions-woocommerce_page_wc-orders", [$this, "add_bulk_orders_actions"], 10); // HPOS
        add_action("handle_bulk_actions-edit-shop_order", [$this, "handle_bulk_actions"], 20, 3); // Legacy
        add_action("handle_bulk_actions-woocommerce_page_wc-orders", [$this, "handle_bulk_actions"], 21, 3); // HPOS
        // Add biteship actions
        add_filter("woocommerce_order_actions", [$this, "add_order_actions"], 10);
        add_action("woocommerce_order_action_create_shipment", [$this, "handle_create_shipment"]);
        add_action("woocommerce_order_action_cancel_shipment", [$this, "handle_cancel_shipment"]);
        add_action("woocommerce_order_action_refresh_shipment", [$this, "handle_refresh_shipment"]);
        // Add column
        add_filter("manage_edit-shop_order_columns", [$this, "add_order_columns"], 10);
        add_filter("manage_woocommerce_page_wc-orders_columns", [$this, "add_order_columns"], 10);
        // Add column contents
        add_action("manage_woocommerce_page_wc-orders_custom_column", [$this, "add_order_contents"], 10, 2);
        add_action("manage_shop_order_posts_custom_column", [$this, "add_order_contents"], 10, 2);
        // Add tracking information
        add_action("woocommerce_admin_order_data_after_shipping_address", [$this, "add_order_detail_contents"], 10);
        // Register biteship's integration
        add_action("plugins_loaded", [$this, "init_integrations"]);
        add_filter("woocommerce_integrations", [$this, "register_integrations"]);
        add_filter("plugin_action_links_biteship-shipping/biteship-shipping.php", [$this, "plugin_links"]);
        add_action("admin_notices", [$this, "display_admin_notices"]);
        add_filter("woocommerce_order_item_get_formatted_meta_data", [$this, "hide_meta_data"]);
        // // Handle shipping address update
        // add_action("woocommerce_admin_order_data_after_shipping_address", [$this, "handle_shipping_address_update"], 20);
    }

    // public function handle_shipping_address_update($order) {
    //     $rate = Biteship_Admin_Helper::get_rate($order);
    //     if ($shipping_rate) {
    //         $order->set_shipping_total($rate);
    //         $order->calculate_totals();
    //     }
    // }

    public function hide_meta_data($meta_data)
    {
        $filtered_meta = [];
        foreach ($meta_data as $meta) {
            if (!str_starts_with($meta->key, "_wc_bts")) {
                $filtered_meta[] = $meta;
            }
        }
        return $filtered_meta;
    }

    public function plugin_links($links)
    {
        $settings_url = add_query_arg(
            [
                "page" => "wc-settings",
                "tab" => "integration",
                "section" => "biteship",
            ],
            admin_url("admin.php")
        );

        $settings_link = "<a href='$settings_url'>Settings</a>";
        array_unshift($links, $settings_link);
        return $links;
    }

    public function init_integrations()
    {
        require_once BITESHIP_SHIPPING_PATH . "includes/class-biteship-integration.php";
    }

    public function register_integrations($integrations)
    {
        $integrations[] = "Biteship_Integration";
        return $integrations;
    }

    public function register_store_fields($fields = [])
    {
        $new_fields = [];

        if (Biteship_Helper::has_map_access()) {
            $new_fields[] = [
                "title" => __("Coordinate", "biteship-shipping"),
                "desc" => __("Pickup location point.", "biteship-shipping"),
                "id" => "woocommerce_store_coordinate",
                "default" => "",
                "type" => "text",
                "desc_tip" => true,
            ];
        }

        $new_fields[] = [
            "title" => __("Shipper name", "biteship-shipping"),
            "desc" => __("Name of the shipper.", "biteship-shipping"),
            "id" => "woocommerce_store_shipper_name",
            "default" => "",
            "type" => "text",
            "desc_tip" => true,
        ];

        $new_fields[] = [
            "title" => __("Shipper phone", "biteship-shipping"),
            "desc" => __("Phone number of the shipper.", "biteship-shipping"),
            "id" => "woocommerce_store_shipper_phone",
            "default" => "",
            "type" => "text",
            "desc_tip" => true,
        ];

        $position = 4;
        array_splice($fields, $position, 0, $new_fields);

        return $fields;
    }

    public function enqueue_scripts($hook)
    {
        $deps = ["jquery", "wc-backbone-modal"];

        wp_enqueue_style("biteship-admin-style", BITESHIP_SHIPPING_URL . "admin/css/biteship-admin.css", [], WC_VERSION);
        wp_enqueue_script("wc-backbone-modal", WC()->plugin_url() . "/assets/js/admin/backbone-modal.min.js", ["backbone", "jquery"], WC_VERSION, true);
        wp_enqueue_style("wc-backbone-modal", WC()->plugin_url() . "/assets/css/admin/backbone-modal.min.css", [], WC_VERSION);

        // SHIP-1818: $hook was accepted and then ignored, so the Google Maps bundle was pulled in
        // on EVERY wp-admin page for map-eligible stores -- a ~1.36 MB download per page view
        // for a script only the store-coordinate picker on the settings screen ever uses.
        // The settings screen is the only place include_map_html() renders a map.
        // Belt and braces: if the hook suffix ever changes, the failure mode is a silently broken
        // store-coordinate picker, so fall back to the page query arg too.
        $requested_page = isset($_GET["page"]) ? sanitize_text_field(wp_unslash($_GET["page"])) : "";
        $is_settings_screen = "woocommerce_page_wc-settings" === $hook || "wc-settings" === $requested_page;

        if ($is_settings_screen && Biteship_Helper::has_map_access()) {
            wp_enqueue_script("google-maps", Biteship_Helper::get_gmaps_js_url(), [], BITESHIP_SHIPPING_VERSION, true);
            $deps[] = "google-maps";
        }

        wp_enqueue_script("biteship-woocommerce-settings-script", BITESHIP_SHIPPING_URL . "admin/js/biteship-admin-woocommerce-settings.js", $deps, "1.0", true);
    }

    public function include_map_html()
    {
        include BITESHIP_SHIPPING_PATH . "/admin/views/woocommerce-general-settings.php";
    }

    public function order_debug($order)
    {
    }

    public function add_bulk_orders_actions($actions)
    {
        $actions["create_shipment"] = __("Create shipment", "biteship-shipping");
        $actions["cancel_shipment"] = __("Cancel shipment", "biteship-shipping");
        $actions["refresh_shipment"] = __("Refresh shipment", "biteship-shipping");

        return $actions;
    }

    public function add_order_actions($actions)
    {
        $actions["create_shipment"] = __("Create shipment", "biteship-shipping");
        $actions["cancel_shipment"] = __("Cancel shipment", "biteship-shipping");
        $actions["refresh_shipment"] = __("Refresh shipment", "biteship-shipping");
        return $actions;
    }

    public function add_order_columns($columns)
    {
        $order_columns = [];
        foreach ($columns as $key => $column) {
        }

        foreach ($columns as $key => $value) {
            $order_columns[$key] = $value;
            if ($key == "order_status") {
                $order_columns["tracking_number"] = __("Tracking number", "biteship-shipping");
                $order_columns["tracking_status"] = __("Tracking status", "biteship-shipping");
            }
        }

        return $order_columns;
    }

    public function add_order_contents($column, $order)
    {
        if (in_array($column, ["tracking_number", "tracking_status"])) {
            if (is_numeric($order)) {
                $order = wc_get_order($order);
            }
        }

        if ($column == "tracking_number") {
            $tracking_url = $order->get_meta(Biteship_Helper::meta_key("tracking_url"));
            $tracking_number = $order->get_meta(Biteship_Helper::meta_key("tracking_number"));

            if (!empty($tracking_number)) {
                echo "<a href='" . esc_url($tracking_url) . "' target='_blank'>" . esc_html($tracking_number) . "</a>";
            } else {
                echo "<span aria-hidden='true'>—</span>";
            }
        }

        if ($column == "tracking_status") {
            $tracking_status = $order->get_meta(Biteship_Helper::meta_key("tracking_status"));
            $tracking_status_label = $this->get_tracking_status_label($tracking_status, $order->get_status());
            $tracking_status_class = $this->get_tracking_status_class($tracking_status, $order->get_status());
            echo "<mark class='order-status " . esc_attr($tracking_status_class) . "'><span>" . esc_html($tracking_status_label) . "</span></mark>";
        }
    }

    public function add_order_detail_contents($order)
    {
        $order_status = $order->get_status();
        $tracking_status = $order->get_meta(Biteship_Helper::meta_key("tracking_status"));
        $tracking_number = $order->get_meta(Biteship_Helper::meta_key("tracking_number"));
        $tracking_url = $order->get_meta(Biteship_Helper::meta_key("tracking_url"));

        if ($tracking_number) {
            $tracking_number = esc_html($tracking_number);
            $tracking_url = esc_url($tracking_url);
            $tracking_status = esc_html($this->get_tracking_status_label($tracking_status, $order_status));

            echo '<div class="address">';

            echo "<p>";
            echo "<strong>" . esc_html__("Tracking number:", "biteship-shipping") . "</strong>";
            echo "<a href='" . esc_url($tracking_url) . "'>" . esc_html($tracking_number) . "</a>";
            echo "</p>";

            echo "<p>";
            echo "<strong>" . esc_html__("Tracking status:", "biteship-shipping") . "</strong>";
            echo "<span>" . esc_html($tracking_status) . "</span>";
            echo "</p>";

            echo "</div>";
        }
    }

    public function handle_bulk_actions($redirect_url, $action, $order_ids)
    {
        switch ($action) {
            case "create_shipment":
                return $this->handle_create_shipments($redirect_url, $order_ids);
            case "cancel_shipment":
                return $this->handle_cancel_shipments($redirect_url, $order_ids);
            case "refresh_shipment":
                return $this->handle_refresh_shipments($redirect_url, $order_ids);
            default:
                return $redirect_url;
        }
    }

    public function handle_create_shipments($redirect_url, $order_ids)
    {
        $created = 0;
        $errors = [];

        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);

            if ($order->get_status() !== "processing") {
                $errors[] = [
                    "id" => $order_id,
                    /* translators: %d is the order's number */
                    "message" => sprintf(__("Order #%d cannot be shipped because its status is not <b>Processing</b>.", "biteship-shipping"), $order_id),
                ];
                continue;
            }

            $tracking_status = $order->get_meta(Biteship_Helper::meta_key("tracking_status"));

            if (!empty($tracking_status) && !in_array($tracking_status, ["cancelled", "returned", "rejected", "courier_not_found"])) {
                $errors[] = [
                    "id" => $order_id,
                    /* translators: %d is the order's number */
                    "message" => sprintf(__("Order #%d cannot be shipped because it already has active shipment.", "biteship-shipping"), $order_id),
                ];
                continue;
            }

            $error_message = Biteship_Admin_Helper::create_shipment($order);
            if (is_string($error_message)) {
                $errors[] = [
                    "id" => $order_id,
                    /* translators: %1$d is the order's number, %2$s is the error message */
                    "message" => sprintf(__('Failed to ship Order #%1$d: %2$s', "biteship-shipping"), $order_id, $error_message),
                ];
            }

            $created += 1;
        }

        if (!empty($errors)) {
            $failed = count($errors);

            return Biteship_Admin_Notice::bulk_action_notice($redirect_url, [
                "name" => "create_shipments_failed",
                "context" => $errors,
                "query" => [
                    "failed" => $failed,
                ],
            ]);
        }

        return Biteship_Admin_Notice::bulk_action_notice($redirect_url, [
            "name" => "shipments_created",
            "query" => [
                "created" => $created,
            ],
        ]);
    }

    private function handle_cancel_shipments($redirect_url, $order_ids)
    {
        $cancelled = 0;
        $errors = [];

        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);

            if (!$order->meta_exists(Biteship_Helper::meta_key("order_id"))) {
                continue;
            }

            if ($order->get_meta(Biteship_Helper::meta_key("tracking_status")) != "confirmed") {
                continue;
            }

            $error_message = Biteship_Admin_Helper::cancel_shipment($order);
            if (is_string($error_message)) {
                $errors[] = [
                    "id" => $order_id,
                    /* translators: %1$d is the order's number, %2$s is the error message */
                    "message" => sprintf(__('Order #%1$d cannot be cancelled due to: %2$s', "biteship-shipping"), $order_id, $error_message),
                ];
                continue;
            }

            $cancelled += 1;
        }

        if (!empty($errors)) {
            $failed = count($errors);

            return Biteship_Admin_Notice::bulk_action_notice($redirect_url, [
                "name" => "cancel_shipments_failed",
                "context" => $errors,
                "query" => [
                    "failed" => $failed,
                ],
            ]);
        }

        return Biteship_Admin_Notice::bulk_action_notice($redirect_url, [
            "name" => "shipments_cancelled",
            "query" => [
                "cancelled" => $cancelled,
            ],
        ]);
    }

    private function handle_refresh_shipments($redirect_url, $order_ids)
    {
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            Biteship_Admin_Helper::refresh_shipment($order);
        }

        return $redirect_url;
    }

    public function handle_create_shipment($order)
    {
        $order_id = $order->get_id();

        if ($order->get_status() !== "processing") {
            return Biteship_Admin_Notice::action_notice("order_action", ["name" => "create_shipment_failed", "context" => ["id" => $order_id, "message" => "Order status is not processing!"]]);
        }

        $tracking_status = $order->get_meta(Biteship_Helper::meta_key("tracking_status"));

        if (!empty($tracking_status) && !in_array($tracking_status, ["cancelled", "returned", "rejected", "courier_not_found"])) {
            return Biteship_Admin_Notice::action_notice("order_action", [
                "name" => "create_shipment_failed",
                "context" => ["id" => $order_id, "message" => "Order already has active shipment."],
            ]);
        }

        $error_message = Biteship_Admin_Helper::create_shipment($order);
        if (is_string($error_message)) {
            return Biteship_Admin_Notice::action_notice("order_action", ["name" => "create_shipment_failed", "context" => ["id" => $order_id, "message" => $error_message]]);
        }

        Biteship_Admin_Notice::action_notice("order_action", ["name" => "shipment_created"]);
    }

    public function handle_cancel_shipment($order)
    {
        $order_id = $order->get_id();

        if (!$order->meta_exists(Biteship_Helper::meta_key("order_id"))) {
            return Biteship_Admin_Notice::action_notice("order_action", ["name" => "cancel_shipment_failed", "context" => ["id" => $order_id, "message" => "Order has no active shipment."]]);
        }

        if ($order->get_meta(Biteship_Helper::meta_key("tracking_status")) != "confirmed") {
            return Biteship_Admin_Notice::action_notice("order_action", ["name" => "cancel_shipment_failed", "context" => ["id" => $order_id, "message" => "Shipment is on the way."]]);
        }

        $error_message = Biteship_Admin_Helper::cancel_shipment($order);
        if (is_string($error_message)) {
            return Biteship_Admin_Notice::action_notice("order_action", ["name" => "cancel_shipment_failed", "context" => ["id" => $order_id, "message" => $error_message]]);
        }

        return Biteship_Admin_Notice::action_notice("order_action", ["name" => "shipment_cancelled"]);
    }

    public function handle_refresh_shipment($order)
    {
        $order_id = $order->get_id();

        if (!$order->meta_exists(Biteship_Helper::meta_key("order_id"))) {
            return Biteship_Admin_Notice::action_notice("order_action", ["name" => "cancel_shipment_failed", "context" => ["id" => $order_id, "message" => "Order has no active shipment."]]);
        }

        Biteship_Admin_Helper::refresh_shipment($order);
    }

    public function display_admin_notices()
    {
        Biteship_Admin_Notice::display_notices();
    }

    private function get_tracking_status_class($tracking_status, $order_status)
    {
        if ($order_status === "on-hold" || (empty($tracking_status) && $order_status === "processing")) {
            return "status-neutral";
        }

        switch ($tracking_status) {
            case "confirmed":
            case "allocated":
            case "picking_up":
            case "picked":
            case "in_transit":
            case "dropping_off":
            case "return_in_transit":
                return "status-processing";
            case "cancelled":
            case "rejected":
            case "courier_not_found":
                return "status-trash";
            case "delivered":
            case "returned":
                return "status-completed";
            default:
                return "status-unknown";
        }
    }

    private function get_tracking_status_label($tracking_status, $order_status)
    {
        if ($order_status === "on-hold") {
            return __("Unshippable", "biteship-shipping");
        }

        if (empty($tracking_status) && $order_status === "processing") {
            return __("Shippable", "biteship-shipping");
        }

        switch ($tracking_status) {
            case "confirmed":
                return __("Confirmed", "biteship-shipping");
            case "cancelled":
                return __("Cancelled", "biteship-shipping");
            case "allocated":
                return __("Allocated", "biteship-shipping");
            case "picking_up":
                return __("Picking Up", "biteship-shipping");
            case "picked":
                return __("Picked", "biteship-shipping");
            case "in_transit":
                return __("In Transit", "biteship-shipping");
            case "dropping_off":
                return __("Dropping Off", "biteship-shipping");
            case "on_hold":
                return __("On Hold", "biteship-shipping");
            case "delivered":
                return __("Delivered", "biteship-shipping");
            case "rejected":
                return __("Rejected", "biteship-shipping");
            case "returned":
                return __("Returned", "biteship-shipping");
            case "return_in_transit":
                return __("Return In Transit", "biteship-shipping");
            case "courier_not_found":
                return __("Courier Not Found", "biteship-shipping");
            default:
                return __("Unknown", "biteship-shipping");
        }
    }
}
