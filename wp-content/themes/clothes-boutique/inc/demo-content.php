<?php
add_action( 'wp_ajax_clothes_boutique_run_demo_import', 'clothes_boutique_run_demo_import' );

function clothes_boutique_run_demo_import() {

    // Verify nonce
    check_ajax_referer( 'clothes_boutique_demo_nonce', 'nonce' );

    // Check permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Permission denied' );
    }

    // Prevent duplicate import
    if ( get_option( 'clothes_boutique_demo_import_completed' ) ) {
        wp_send_json_success( 'Already imported' );
    }

    // WooCommerce check
    if ( ! class_exists( 'WooCommerce' ) ) {
        wp_send_json_error( 'WooCommerce not active' );
    }

    /* PRODUCT CATEGORIES */
    $clothes_boutique_categories = array(
        array(
            'name'  => 'WOMEN COLLECTION',
            'image' => get_stylesheet_directory() . '/assets/images/category1.png',
        ),
        array(
            'name'  => 'MEN COLLECTION',
            'image' => get_stylesheet_directory() . '/assets/images/category2.png',
        ),
        array(
            'name'  => 'KIDS COLLECTION',
            'image' => get_stylesheet_directory() . '/assets/images/category3.png',
        ),
    );

    $clothes_boutique_category_ids = array();

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    foreach ( $clothes_boutique_categories as $category ) {

        $term = term_exists( $category['name'], 'product_cat' );

        if ( ! $term ) {
            $term = wp_insert_term( $category['name'], 'product_cat' );
        }

        if ( is_wp_error( $term ) ) {
            continue;
        }

        $term_id = is_array( $term ) ? $term['term_id'] : $term;
        $clothes_boutique_category_ids[] = (int) $term_id;

        /* CATEGORY IMAGE */
        if ( ! empty( $category['image'] ) && file_exists( $category['image'] ) ) {

            $upload_dir = wp_upload_dir();
            $filename   = basename( $category['image'] );
            $new_path   = $upload_dir['path'] . '/' . $filename;

            if ( copy( $category['image'], $new_path ) ) {

                $filetype = wp_check_filetype( $filename, null );

                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => sanitize_file_name( $filename ),
                    'post_status'    => 'inherit'
                );

                $attach_id = wp_insert_attachment( $attachment, $new_path );

                $attach_data = wp_generate_attachment_metadata( $attach_id, $new_path );
                wp_update_attachment_metadata( $attach_id, $attach_data );

                update_term_meta( $term_id, 'thumbnail_id', $attach_id );
            }
        }
    }

    /* PRODUCTS */
    $clothes_boutique_products = array(
        array(
            'title' => 'floral dress',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-1.png'
        ),
        array(
            'title' => 'Polo T-Shirt',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-2.png'
        ),
        array(
            'title' => 'Casual Top',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-3.png'
        ),
        array(
            'title' => 'Denim Jacket',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-4.png'
        ),
        array(
            'title' => 'Breeze Stole',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-5.png'
        ),
        array(
            'title' => 'StyleTop',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-6.png'
        ),
        array(
            'title' => 'DailyWear Top',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-7.png'
        ),
        array(
            'title' => 'DarkVibe Jacket',
            'price' => '49.00',
            'sale_price' => '39.00',
            'image' => get_stylesheet_directory() . '/assets/images/product-8.png'
        ),
    );

    foreach ( $clothes_boutique_products as $clothes_boutique_index => $clothes_boutique_product ) {

        // Insert product
        $clothes_boutique_product_id = wp_insert_post( array(
            'post_title'   => $clothes_boutique_product['title'],
            'post_content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.', 
            'post_excerpt' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 
            'post_status'  => 'publish',
            'post_type'    => 'product',
        ));

        if ( is_wp_error( $clothes_boutique_product_id ) ) {
            continue;
        }

        // Set product price
        update_post_meta( $clothes_boutique_product_id, '_regular_price', $clothes_boutique_product['price'] );

        if ( ! empty( $clothes_boutique_product['sale_price'] ) ) {
            update_post_meta( $clothes_boutique_product_id, '_sale_price', $clothes_boutique_product['sale_price'] );
            update_post_meta( $clothes_boutique_product_id, '_price', $clothes_boutique_product['sale_price'] );
        } else {
            update_post_meta( $clothes_boutique_product_id, '_price', $clothes_boutique_product['price'] );
        }

        // Assign category
        wp_set_object_terms(
            $clothes_boutique_product_id,
            $clothes_boutique_category_ids[ $clothes_boutique_index % count( $clothes_boutique_category_ids ) ],
            'product_cat'
        );

        /* FEATURED IMAGE */
        if ( ! empty( $clothes_boutique_product['image'] ) && file_exists( $clothes_boutique_product['image'] ) ) {

            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';

            $image_path = $clothes_boutique_product['image'];

            $upload_dir = wp_upload_dir();
            $filename   = basename( $image_path );

            // Copy image to uploads directory
            $new_file_path = $upload_dir['path'] . '/' . $filename;

            if ( copy( $image_path, $new_file_path ) ) {

                $filetype = wp_check_filetype( $filename, null );

                $attachment = array(
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => sanitize_file_name( $filename ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id = wp_insert_attachment( $attachment, $new_file_path, $clothes_boutique_product_id );

                $attach_data = wp_generate_attachment_metadata( $attach_id, $new_file_path );
                wp_update_attachment_metadata( $attach_id, $attach_data );

                set_post_thumbnail( $clothes_boutique_product_id, $attach_id );
            }
        }
    }

    // Mark demo import as completed
    update_option( 'clothes_boutique_demo_import_completed', true );

    wp_send_json_success( 'Demo imported successfully' );
}
