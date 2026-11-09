<?php

class Biteship
{
    /**
     * The single instance of the class.
     *
     * @var Biteship
     */
    protected static $_instance = null;

    /**
     * @since    1.0.0
     * @access   protected
     * @var      Biteship_Public $public Public class.
     */
    protected $public;

    /**
     * @since    1.0.0
     * @access   protected
     * @var      Biteship_Admin $admin Admin class.
     */
    protected $admin;

    /**
     * @since    1.0.0
     * @access   protected
     * @var      Biteship_Rest_Controller $rest_controller Rest controller class.
     */
    protected $rest_controller;

    /**
     * Main Biteship Instance.
     *
     * Ensures only one instance of Biteship is loaded or can be loaded.
     *
     * @return Biteship - Main instance.
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Biteship Constructor.
     */
    public function __construct()
    {
        $this->init();
        $this->load_dependencies();

        $this->register_hooks();

        $this->admin->init();
        $this->public->init();
    }

    public function load_textdomain()
    {
        load_plugin_textdomain("biteship-shipping", false, BITESHIP_SHIPPING_PATH . "languages");
    }

    /**
     * Activate the plugin.
     */
    public static function activate()
    {
        Biteship_Activator::activate();
    }

    /**
     * Deactivate the plugin.
     */
    public static function deactivate()
    {
        Biteship_Deactivator::deactivate();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function init()
    {
        require_once BITESHIP_SHIPPING_PATH . "includes/class-biteship-activator.php";
        require_once BITESHIP_SHIPPING_PATH . "includes/class-biteship-deactivator.php";
    }

    private function load_dependencies()
    {
        require_once BITESHIP_SHIPPING_PATH . "includes/class-biteship-api.php";
        require_once BITESHIP_SHIPPING_PATH . "includes/class-biteship-helper.php";
        require_once BITESHIP_SHIPPING_PATH . "admin/class-biteship-admin-notice.php";
        require_once BITESHIP_SHIPPING_PATH . "admin/class-biteship-admin-helper.php";
        require_once BITESHIP_SHIPPING_PATH . "admin/class-biteship-admin-rest-controller.php";
        require_once BITESHIP_SHIPPING_PATH . "admin/class-biteship-admin.php";
        require_once BITESHIP_SHIPPING_PATH . "public/class-biteship-public-helper.php";
        require_once BITESHIP_SHIPPING_PATH . "public/class-biteship-public.php";

        $this->admin = new Biteship_Admin();
        $this->public = new Biteship_Public();
        // $this->rest_controller = new Biteship_Rest_Controller();
    }

    /**
     * Hook into actions and filters.
     */
    private function register_hooks()
    {
        register_activation_hook(BITESHIP_SHIPPING_FILE, [$this, "activate"]);
        register_deactivation_hook(BITESHIP_SHIPPING_FILE, [$this, "deactivate"]);

        // Register endpoint
        // add_action("rest_api_init", [$this->rest_controller, "register_routes"]);
    }
}
