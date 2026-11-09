<?php

if (class_exists("WC_Integration")) {
    class Biteship_Integration extends WC_Integration
    {
        const BACKFILL_ACTION = "biteship_backfill_subscription_info";

        protected $api;

        public function __construct()
        {
            $this->id = "biteship";
            $this->method_title = __("Biteship", "biteship-shipping");
            $this->method_description = __("An integration for utilizing Biteship for shipping.", "biteship-shipping");

            $this->init_form_fields();
            $this->init_settings();
            $this->load_dependencies();

            add_action("woocommerce_update_options_integration_" . $this->id, [$this, "process_admin_options"]);
            add_action("admin_init", [$this, "maybe_schedule_subscription_backfill"]);
            add_action(self::BACKFILL_ACTION, [$this, "backfill_subscription_info"]);
        }

        private function load_dependencies()
        {
            $this->api = Biteship_API::get_instance();
        }

        public function init_form_fields()
        {
            $this->form_fields = [
                "api_key" => [
                    "title" => __("Kunci WooCommerce", "biteship-shipping"),
                    "type" => "password",
                    "description" => __(
                        "Kunci WooCommerce yang digunakan untuk menghubungkan toko Anda dengan layanan Biteship. Dapatkan Kunci WooCommerce di dashboard Biteship pada menu Integrations > WooCommerce.",
                        "biteship-shipping"
                    ),
                    "desc_tip" => false,
                    "default" => $this->get_option("api_key"),
                ],
                "subscription_name" => [
                    "title" => __("Subscription", "biteship-shipping"),
                    "type" => "text",
                    "description" => $this->get_option("subscription_description"),
                    "desc_tip" => false,
                    "default" => $this->get_option("subscription_name"),
                    "custom_attributes" => [
                        "readonly" => "readonly",
                    ],
                ],
                "complete_status_on_delivered" => [
                    "title" => __("Enable auto complete status", "biteship-shipping"),
                    "type" => "checkbox",
                    "description" => __("Automatically update order status to <b>Completed</b> when order is shipped.", "biteship-shipping"),
                    "desc_tip" => false,
                    "default" => "",
                ],
                "use_map_for_address" => [
                    "title" => __("Use map location as address in checkout", "biteship-shipping"),
                    "type" => "checkbox",
                    "description" => __("Automatically fill-out necessary data for billing / shipping address based on map location.", "biteship-shipping"),
                    "desc_tip" => false,
                    "default" => "",
                ],
                "use_area_dropdown" => [
                    "title" => __("Use area dropdowns in checkout", "biteship-shipping"),
                    "type" => "checkbox",
                    "description" => __("Select province, city, and postcode through dropdowns in checkout page.", "biteship-shipping"),
                    "desc_tip" => false,
                    "default" => "",
                ],
            ];
        }

        public function process_admin_options()
        {
            parent::process_admin_options();

            $api_key = $this->get_option("api_key");

            $info = $this->api->get_subscription_info($api_key);

            if (empty($info["success"])) {
                $message = !empty($info["error"])
                    ? $info["error"]
                    : __("Kunci WooCommerce tidak valid.", "biteship-shipping");
                WC_Admin_Settings::add_error($message);
                $this->update_option("api_key", "");
                $this->update_option("api_key_id", "");
                $this->update_option("subscription_name", "");
                $this->update_option("subscription_description", "");
                $this->update_option("subscription_type", "");
                $this->update_option("subscription_tier", "");
                $this->update_option("is_eligible_for_maps", "");
                $this->update_option("subscription_period", "");
                $this->update_option("active", false);
                $this->update_option("due_at", "");
                return;
            }

            $this->store_subscription_info($info);
        }

        private function store_subscription_info($info)
        {
            $subscription_tier = !empty($info["subscription_tier"]) ? strtolower($info["subscription_tier"]) : "";
            $info_details = $this->get_subscription_tier_detail($subscription_tier, $info["subscription_tier_label"]);

            $this->update_option("api_key_id", $info["id"]);
            $this->update_option("subscription_name", $info_details["name"]);
            $this->update_option("subscription_description", $info_details["description"]);
            $this->update_option("subscription_type", $info["subscription_type"]);
            $this->update_option("subscription_tier", $subscription_tier);
            $this->update_option("is_eligible_for_maps", $info["is_eligible_for_maps"] ? "yes" : "");
            $this->update_option("subscription_period", $info["subscription_period"]);
            $this->update_option("active", $info["active"]);
            $this->update_option("due_at", $info["due_at"]);
        }

        /**
         * Keys validated before is_eligible_for_maps existed have no stored flag, so maps would stay
         * off until someone re-saved settings. Re-validate them once in the background.
         */
        private function needs_subscription_backfill()
        {
            $settings = get_option($this->get_option_key());
            return is_array($settings)
                && !empty($settings["api_key"])
                && !empty($settings["api_key_id"])
                && !array_key_exists("is_eligible_for_maps", $settings);
        }

        public function maybe_schedule_subscription_backfill()
        {
            if (wp_doing_ajax() || !function_exists("as_enqueue_async_action")) {
                return;
            }

            // Throttle: at most one attempt per hour, so a failing backend is not hammered.
            if (get_transient(self::BACKFILL_ACTION) || !$this->needs_subscription_backfill()) {
                return;
            }

            set_transient(self::BACKFILL_ACTION, 1, HOUR_IN_SECONDS);

            if (!as_has_scheduled_action(self::BACKFILL_ACTION)) {
                as_enqueue_async_action(self::BACKFILL_ACTION, [], "biteship");
            }
        }

        public function backfill_subscription_info()
        {
            // Re-read: settings may have been saved since this instance loaded them.
            $this->init_settings();

            if (!$this->needs_subscription_backfill()) {
                return;
            }

            $info = $this->api->get_subscription_info($this->get_option("api_key"));

            // Unlike a settings save, never clear the key here: leave the flag unset and retry later.
            if (empty($info["success"])) {
                return;
            }

            $this->store_subscription_info($info);
        }

        public function get_subscription_tier_detail($subscription_tier, $label = null)
        {
            if (!$subscription_tier) {
                return [
                    "name" => "",
                    "description" => __("No active subscription. Activate a plan in the Biteship dashboard.", "biteship-shipping"),
                ];
            }

            return [
                // Prefer the backend's display label (enterprise is shown as "Biteship Plus").
                "name" => $label ? $label : ucfirst($subscription_tier),
                "description" => __("Plan limits are managed in the Biteship dashboard.", "biteship-shipping"),
            ];
        }
    }
}
