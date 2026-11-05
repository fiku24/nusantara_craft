<?php

namespace ICB;

class Enqueue {
    function __construct() {
        add_action( 'enqueue_block_assets', [ $this, 'enqueueBlockAssets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
    }

    /**
     * Block assets — pass ICB_BLOCK_DATA to the block editor.
     * JS reads this via window.ICB_BLOCK_DATA in edit.js files.
     */
    function enqueueBlockAssets() {
        $disabled_blocks = get_option( 'icbBlocks', [] );
        if ( ! is_array( $disabled_blocks ) ) {
            $disabled_blocks = [];
        }

      
    }

    /**
     * Admin Script Enqueue — only on the Info Cards dashboard page.
     */
    function adminEnqueueScripts( $hook ) {
        if ( strpos( $hook, 'info-cards-dashboard' ) !== false ) {
            wp_enqueue_style(
                'icb-admin-dashboard-css',
                ICB_DIR_URL . 'build/admin-dashboard.css',
                [],
                ICB_VERSION
            );

            wp_enqueue_script(
                'icb-admin-dashboard-js',
                ICB_DIR_URL . 'build/admin-dashboard.js',
                [ 'react', 'react-dom', 'wp-util' ],
                ICB_VERSION,
                true
            );
        }
    }
}
