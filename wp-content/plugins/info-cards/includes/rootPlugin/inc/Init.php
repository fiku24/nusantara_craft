<?php

namespace ICB;

class Init {
    function __construct() {
        add_action( 'init', [ $this, 'onInit' ] );
        add_filter( 'block_categories_all', [ $this, 'registerBlockCategory' ], 10, 2 );
    }

    function onInit() {
        $this->icb_register_cdn_assets();
        $this->icb_register_blocks();
        $this->icb_register_filters();

        wp_set_script_translations( 'icb-cards-editor-script', 'info-cards', ICB_DIR_PATH . 'languages' );
    }

    /**
     * Register external CDN scripts/styles.
     * Each block's block.json declares which handles it needs in viewScript/style,
     * so WordPress will auto-enqueue them ONLY on pages where that block is used.
     */
    function icb_register_cdn_assets() {
        // -- Unicorn Studio (used by: info-cards) --
        wp_register_script(
            'unicorn-studio',
            'https://cdn.jsdelivr.net/gh/hiunicornstudio/unicornstudio.js@v1.4.25/dist/unicornStudio.umd.js',
            [],
            '1.4.25',
            true
        );

        // -- GSAP (used by: magnifying-glass-cards) --
        wp_register_script(
            'mgc-gsap',
            'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js',
            [],
            '3.11.3',
            true
        );

        // -- Splitting.js (used by: magnifying-glass-cards) --
        wp_register_script(
            'mgc-splitting',
            'https://unpkg.com/splitting/dist/splitting.min.js',
            [],
            null,
            true
        );

        // -- Owl Carousel CSS (used by: expandable-animated-card-slider) --
        wp_register_style(
            'owl-carousel',
            'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css',
            [],
            '2.3.4'
        );

        wp_register_style(
            'owl-carousel-theme',
            'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css',
            [ 'owl-carousel' ],
            '2.3.4'
        );

        // -- Owl Carousel JS (used by: expandable-animated-card-slider) --
        wp_register_script(
            'owl-carousel-js',
            'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',
            [ 'jquery' ],
            '2.3.4',
            true
        );

        wp_register_script(
            'owl-carousel-mousewheel',
            'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.mousewheel.min.js',
            [ 'owl-carousel-js' ],
            '2.3.4',
            true
        );
    }

    /**
     * Loop through build/blocks/ and register each block.
     * Free blocks are always registered.
     * Pro blocks are only registered when premium is active.
     * Disabled blocks (toggled OFF) are skipped entirely.
     */
    function icb_register_blocks() {
        $blocks_path = ICB_DIR_PATH . 'build/blocks/';

        // Use scandir instead of glob to prevent issues on restricted servers
        if ( ! is_dir( $blocks_path ) ) {
            return;
        }

        $files = scandir( $blocks_path );
        $all_blocks = [];

        foreach ( $files as $file ) {
            if ( $file !== '.' && $file !== '..' && is_dir( $blocks_path . $file ) ) {
                $all_blocks[] = $blocks_path . $file;
            }
        }

        if ( empty( $all_blocks ) ) {
            return;
        }

        // Blocks toggled OFF by the admin dashboard.
        $disabled_blocks = get_option( 'icbBlocks', [] );
        if ( ! is_array( $disabled_blocks ) ) {
            $disabled_blocks = [];
        }

    

        // Register shared assets for pro blocks
      

        foreach ( $all_blocks as $block_path ) {
            $block_name = basename( $block_path );

            // Skip if admin toggled this block OFF.
            if ( in_array( $block_name, $disabled_blocks, true ) ) {
                continue;
            }

            // Free blocks — always register.
            // 'parent' is the selector/chooser UI, needed by all users.
            if ( in_array( $block_name, [ 'info-cards', 'parent' ], true ) ) {
                register_block_type( $block_path );
                continue;
            }

            // All other blocks are Pro — only register when premium is active.
         
        }
    }

    /**
     * Register block category at the top of the list.
     */
    function registerBlockCategory( $categories, $context ) {
        if ( ! is_array( $categories ) ) {
            $categories = [];
        }
        // Prevent duplicate category
        foreach ( $categories as $category ) {
            if ( isset( $category['slug'] ) && 'info-cards' === $category['slug'] ) {
                return $categories;
            }
        }
        // Add category at top
        array_unshift( $categories, [
            'slug'  => 'info-cards',
            'title' => __( 'Info Cards', 'info-cards' ),
            'icon'  => null,
        ] );
        return $categories;
    }

    /**
     * Default title/content filters for new pages.
     */
    function icb_register_filters() {
        add_filter( 'default_title', function ( $title, $post = null ) {
            if ( is_object( $post ) && isset( $post->post_type ) && 'page' === $post->post_type && isset( $_GET['title'] ) ) {
                return sanitize_text_field( wp_unslash( $_GET['title'] ) );
            }
            return $title;
        }, 10, 2 );

        add_filter( 'default_content', function ( $content, $post = null ) {
            if ( is_object( $post ) && isset( $post->post_type ) && 'page' === $post->post_type && isset( $_GET['content'] ) ) {
                return wp_unslash( $_GET['content'] );
            }
            return $content;
        }, 10, 2 );
    }
}
