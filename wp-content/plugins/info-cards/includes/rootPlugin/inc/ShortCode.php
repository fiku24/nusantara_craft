<?php

namespace ICB;

class ShortCode {
    private $post_type = 'icb';

    function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
        add_action( 'init', [ $this, 'onInit' ] );
        add_shortcode( 'icb', [ $this, 'onAddShortcode' ] );

        add_filter( 'manage_icb_posts_columns', [ $this, 'manageICBPostsColumns' ], 10 );
        add_action( 'manage_icb_posts_custom_column', [ $this, 'manageICBPostsCustomColumns' ], 10, 2 );

        add_filter( 'use_block_editor_for_post', [ $this, 'useBlockEditorForPost' ], 999, 2 );
    }

    function onInit() {
        // Mirror pdf-embed-block: premium users see the selector UI,
        // free users get the default card block directly.
     
            $template = [ [ 'icb/cards' ] ];
        

        register_post_type( $this->post_type, [
            'labels' => [
                'name'               => __( 'ShortCodes', 'info-cards' ),
                'singular_name'      => __( 'ShortCode', 'info-cards' ),
                'add_new'            => __( 'Add New', 'info-cards' ),
                'add_new_item'       => __( 'Add New ShortCode', 'info-cards' ),
                'edit_item'          => __( 'Edit ShortCode', 'info-cards' ),
                'new_item'           => __( 'New ShortCode', 'info-cards' ),
                'view_item'          => __( 'View ShortCode', 'info-cards' ),
                'search_items'       => __( 'Search ShortCodes', 'info-cards' ),
                'not_found'          => __( 'Sorry, we couldn\'t find the ShortCode you are looking for.', 'info-cards' ),
            ],
            'public'              => false,
            'show_ui'             => true,
            'show_in_rest'        => true,
            'publicly_queryable'  => false,
            'show_in_menu'        => 'info-cards-dashboard',
            'exclude_from_search' => true,
            'menu_position'       => 14,
            'has_archive'         => false,
            'hierarchical'        => false,
            'capability_type'     => 'page',
            'rewrite'             => [ 'slug' => 'apb' ],
            'supports'            => [ 'title', 'editor' ],
            'template'            => $template,
            'template_lock'       => 'all',
        ] );
    }

    function onAddShortcode( $atts ) {
        $atts = shortcode_atts( [
            'id' => 0,
        ], $atts, 'icb' );

        $post_id = intval( $atts['id'] );
        if ( ! $post_id ) {
            return '';
        }

        $post = get_post( $post_id );
        if ( ! $post ) {
            return '';
        }

        if ( post_password_required( $post ) ) {
            return get_the_password_form( $post );
        }

        switch ( $post->post_status ) {
            case 'publish':
                return $this->displayContent( $post );

            case 'private':
                if ( current_user_can( 'read_private_posts' ) ) {
                    return $this->displayContent( $post );
                }
                return '';

            case 'draft':
            case 'pending':
            case 'future':
                if ( current_user_can( 'edit_post', $post_id ) ) {
                    return $this->displayContent( $post );
                }
                return '';

            default:
                return '';
        }
    }

    function displayContent( $post ) {
        $blocks = parse_blocks( $post->post_content );
        if ( empty( $blocks ) || ! isset( $blocks[0] ) ) {
            return '';
        }
        return render_block( $blocks[0] );
    }

    function manageICBPostsColumns( $defaults ) {
        unset( $defaults['date'] );
        $defaults['shortcode'] = __( 'ShortCode', 'info-cards' );
        $defaults['date']      = __( 'Date', 'info-cards' );
        return $defaults;
    }

    function manageICBPostsCustomColumns( $column_name, $post_ID ) {
        if ( $column_name === 'shortcode' ) {
            echo '<div class="bPlAdminShortcode" id="bPlAdminShortcode-' . esc_attr( $post_ID ) . '">
                <input readonly value="[icb id=&quot;' . esc_attr( $post_ID ) . '&quot;]" onclick="copyBPlAdminShortcode(\'' . esc_attr( $post_ID ) . '\')">
                <span class="tooltip">' . esc_html__( 'Copy To Clipboard', 'info-cards' ) . '</span>
            </div>';
        }
    }

    function useBlockEditorForPost( $use, $post ) {
        if ( is_object( $post ) && isset( $post->post_type ) && $this->post_type === $post->post_type ) {
            return true;
        }
        return $use;
    }

    function adminEnqueueScripts( $hook ) {
        $screen = get_current_screen();
        if ( ! $screen || $screen->post_type !== $this->post_type ) {
            return;
        }

        if ( in_array( $hook, [ 'edit.php', 'post.php' ], true ) ) {
            wp_enqueue_style( 'icb-admin-post', ICB_DIR . 'build/admin-post.css', [], ICB_VERSION );
            wp_enqueue_script( 'icb-admin-post', ICB_DIR . 'build/admin-post.js', [], ICB_VERSION, true );
            wp_set_script_translations( 'icb-admin-post', 'info-cards', ICB_DIR_PATH . 'languages' );
        }
    }
}
