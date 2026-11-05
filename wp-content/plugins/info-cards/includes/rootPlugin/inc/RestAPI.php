<?php

namespace ICB;

class RestAPI {
    function __construct() {
  

        add_action( 'rest_api_init', [ $this, 'registerSettings' ] );
        add_action( 'rest_api_init', [ $this, 'registerRoutes' ] );

        add_action( 'wp_ajax_icbGetBlocks',     [ $this, 'icbGetBlocks_callback' ] );
        add_action( 'wp_ajax_icbSaveUninstallOption', [ $this, 'icbSaveUninstallOption_callback' ] );
        add_action( 'wp_ajax_ncbPosts',         [ $this, 'ncbPosts_callback' ] );
        add_action( 'wp_ajax_nopriv_ncbPosts',  [ $this, 'ncbPosts_callback' ] );
    }

   

    function registerSettings() {
        register_setting( 'icbUtils', 'icbUtils', [
            'type' => 'object',
            'show_in_rest' => [
                'name'   => 'icbUtils',
                'schema' => [
                    'type'       => 'object',
                    'properties' => [
                        'nonce' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
            'default' => [
                'nonce' => wp_create_nonce( 'wp_rest' ),
            ],
        ] );
    }

    /**
     * icbGetBlocks (AJAX)
     */
    public function icbGetBlocks_callback() {
        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ?? '' ) );

        if ( ! wp_verify_nonce( $nonce, 'icb_admin_nonce' ) ) {
            wp_send_json_error( 'Invalid Request' );
        }

        $db_data = get_option( 'icbBlocks', [] );

        if ( ! isset( $_POST['data'] ) ) {
            wp_send_json_success( $db_data );
        }

        $data = json_decode( stripslashes( $_POST['data'] ), true );

        update_option( 'icbBlocks', $data );

        wp_send_json_success( $data );
    }

    /**
     * icbSaveUninstallOption (AJAX) — persist the "delete data on uninstall" toggle.
     * Contract matches bpl-tools/Admin/Settings: reads $_POST['nonce'] and $_POST['enabled'].
     */
    public function icbSaveUninstallOption_callback() {
        $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) );

        if ( ! wp_verify_nonce( $nonce, 'icb_save_uninstall_option' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid security token.', 'info-cards' ) ], 403 );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to perform this action.', 'info-cards' ) ], 403 );
        }

        $raw_enabled = isset( $_POST['enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) : '';
        $enabled     = ( 'true' === $raw_enabled || '1' === $raw_enabled );

        update_option( 'icbDeleteDataOnUninstall', $enabled );

        wp_send_json_success( [
            'enabled' => $enabled,
            'message' => $enabled
                ? __( 'Data deletion enabled.', 'info-cards' )
                : __( 'Data will be preserved on uninstall.', 'info-cards' ),
        ] );
    }

    /**
     * ncbPosts (AJAX)
     */
    public function ncbPosts_callback() {
        $nonce = sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ?? '' ) );

        if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) && ! wp_verify_nonce( $nonce, 'wp_ajax' ) ) {
            wp_send_json_error( 'Invalid Request' );
        }

        $queryAttr  = isset( $_POST['queryAttr'] ) ? wp_unslash( $_POST['queryAttr'] ) : [];
        $pageNumber = isset( $_POST['pageNumber'] ) ? (int) $_POST['pageNumber'] : 1;

        if ( class_exists( '\ICB\Posts' ) ) {
            $posts = \ICB\Posts::getPosts( $queryAttr, $pageNumber );
            wp_send_json_success( $posts );
        }

        wp_send_json_error( 'Posts class not found' );
    }

    /**
     * REST routes
     */
    function registerRoutes() {
       

        register_rest_route( 'icb/v1', '/posts', [
            'methods'             => 'GET',
            'callback'            => [ $this, 'getPosts' ],
            'permission_callback' => '__return_true',
            'args'                => [
                'per_page' => [
                    'default'           => 6,
                    'sanitize_callback' => 'absint',
                ],
                'category' => [
                    'default'           => 0,
                    'sanitize_callback' => 'absint',
                ],
            ],
        ] );
    }

    /**
     * GET Posts
     */
    function getPosts( \WP_REST_Request $request ) {
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => $request->get_param( 'per_page' ),
            'post_status'    => 'publish',
        ];

        $category = $request->get_param( 'category' );
        if ( $category ) {
            $args['cat'] = $category;
        }

        $query = new \WP_Query( $args );
        $posts = [];

        foreach ( $query->posts as $post ) {
            $posts[] = [
                'id'        => $post->ID,
                'title'     => get_the_title( $post ),
                'excerpt'   => get_the_excerpt( $post ),
                'link'      => get_permalink( $post ),
                'thumbnail' => get_the_post_thumbnail_url( $post, 'medium' ),
                'date'      => get_the_date( 'M j, Y', $post ),
                'author'    => get_the_author_meta( 'display_name', $post->post_author ),
            ];
        }

        return rest_ensure_response( $posts );
    }
}
