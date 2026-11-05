<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'ICB_InfoCards' ) ) {
    class ICB_InfoCards {
        function __construct() {
            $this->loaded_classes();
        }

        function loaded_classes() {
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/Init.php';
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/Enqueue.php';
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/AdminMenu.php';
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/ShortCode.php';
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/RestAPI.php';
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/Functions.php';
            require_once ICB_DIR_PATH . 'includes/rootPlugin/inc/Posts.php';

            new ICB\Init();
            new ICB\Enqueue();
            new ICB\AdminMenu();
            new ICB\ShortCode();
            new ICB\RestAPI();
        }
    }
    new ICB_InfoCards();
}
