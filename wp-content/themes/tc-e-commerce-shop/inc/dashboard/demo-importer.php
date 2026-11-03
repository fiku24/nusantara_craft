<div class="theme-import">
	<?php 
        // Check if the demo import has been completed
        $tc_e_commerce_shop_demo_import_completed = get_option('tc_e_commerce_shop_demo_import_completed', false);

        // If the demo import is completed, display the "View Site" button
        if ($tc_e_commerce_shop_demo_import_completed) {
        echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'tc-e-commerce-shop') . '</p>';
        echo '<span><a href="' . esc_url(home_url()) . '"  class= "run-import view-site" target="_blank">' . esc_html__('VIEW SITE', 'tc-e-commerce-shop') . '</a></span>';
        }

		// POST and update the customizer and other related data
        if (isset($_POST['submit'])) {

            // Check if woocommerce is installed and activated
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                // Install the plugin if it doesn't exist
                $tc_e_commerce_shop_plugin_slug = 'woocommerce';
                $tc_e_commerce_shop_plugin_file = 'woocommerce/woocommerce.php';

                // Check if plugin is installed
                $tc_e_commerce_shop_installed_plugins = get_plugins();
                if (!isset($tc_e_commerce_shop_installed_plugins[$tc_e_commerce_shop_plugin_file])) {
                    include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
                    include_once(ABSPATH . 'wp-admin/includes/file.php');
                    include_once(ABSPATH . 'wp-admin/includes/misc.php');
                    include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

                    // Install the plugin
                    $tc_e_commerce_shop_upgrader = new Plugin_Upgrader();
                    $tc_e_commerce_shop_upgrader->install('https://downloads.WordPress.org/plugin/woocommerce.latest-stable.zip');
                }
                // Activate the plugin
                activate_plugin($tc_e_commerce_shop_plugin_file);
            }

            // ------- Create Nav Menu --------
            $tc_e_commerce_shop_menuname = 'Main Menus';
            $tc_e_commerce_shop_bpmenulocation = 'primary';
            $tc_e_commerce_shop_menu_exists = wp_get_nav_menu_object($tc_e_commerce_shop_menuname);

            if (!$tc_e_commerce_shop_menu_exists) {
                $tc_e_commerce_shop_menu_id = wp_create_nav_menu($tc_e_commerce_shop_menuname);

                // Create Home Page
                $tc_e_commerce_shop_home_title = 'Home';
                $tc_e_commerce_shop_home = array(
                    'post_type' => 'page',
                    'post_title' => $tc_e_commerce_shop_home_title,
                    'post_content' => '',
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'home'
                );
                $tc_e_commerce_shop_home_id = wp_insert_post($tc_e_commerce_shop_home);
                // Assign Home Page Template
                add_post_meta($tc_e_commerce_shop_home_id, '_wp_page_template', 'page-template/custom-frontpage.php');
                // Update options to set Home Page as the front page
                update_option('page_on_front', $tc_e_commerce_shop_home_id);
                update_option('show_on_front', 'page');
                // Add Home Page to Menu
                wp_update_nav_menu_item($tc_e_commerce_shop_menu_id, 0, array(
                    'menu-item-title' => __('Home', 'tc-e-commerce-shop'),
                    'menu-item-classes' => 'home',
                    'menu-item-url' => home_url('/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $tc_e_commerce_shop_home_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create About Us Page with Dummy Content
                $tc_e_commerce_shop_pages_title = 'About Us';
                $tc_e_commerce_shop_pages_content = '
                <p>Explore all the pages we have on our website. Find information about our services, company, and more.</p>

                 Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br> 

                  All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $tc_e_commerce_shop_pages = array(
                    'post_type' => 'page',
                    'post_title' => $tc_e_commerce_shop_pages_title,
                    'post_content' => $tc_e_commerce_shop_pages_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'pages'
                );
                $tc_e_commerce_shop_pages_id = wp_insert_post($tc_e_commerce_shop_pages);
                // Add About Us Page to Menu
                wp_update_nav_menu_item($tc_e_commerce_shop_menu_id, 0, array(
                    'menu-item-title' => __('About Us', 'tc-e-commerce-shop'),
                    'menu-item-classes' => 'pages',
                    'menu-item-url' => home_url('/pages/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $tc_e_commerce_shop_pages_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // Create Shop Page with Dummy Content
                $tc_e_commerce_shop_about_title = 'Shop';
                $tc_e_commerce_shop_about_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...<br>

                         Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br> 

                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text.<br> 

                            All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $tc_e_commerce_shop_about = array(
                    'post_type' => 'page',
                    'post_title' => $tc_e_commerce_shop_about_title,
                    'post_content' => $tc_e_commerce_shop_about_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'about-us'
                );
                $tc_e_commerce_shop_about_id = wp_insert_post($tc_e_commerce_shop_about);
                // Add Shop Page to Menu
                wp_update_nav_menu_item($tc_e_commerce_shop_menu_id, 0, array(
                    'menu-item-title' => __('Shop', 'tc-e-commerce-shop'),
                    'menu-item-classes' => 'about-us',
                    'menu-item-url' => home_url('/about-us/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $tc_e_commerce_shop_about_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));

                // ===== CREATE BLOG PAGE =====
                $tc_e_commerce_shop_blog_page = get_page_by_path('blog');
                if (!$tc_e_commerce_shop_blog_page) {
                    $tc_e_commerce_shop_blog_page_id = wp_insert_post(array(
                        'post_type'   => 'page',
                        'post_title'  => 'Blog',
                        'post_status' => 'publish',
                        'post_name'   => 'blog',
                    ));

                } else {
                    $tc_e_commerce_shop_blog_page_id = $tc_e_commerce_shop_blog_page->ID;
                }
                update_option('page_for_posts', $tc_e_commerce_shop_blog_page_id);

                wp_update_nav_menu_item($tc_e_commerce_shop_menu_id, 0, array(
                    'menu-item-title'     => __('Blog', 'tc-e-commerce-shop'),
                    'menu-item-object-id' => $tc_e_commerce_shop_blog_page_id,
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ));
                
                // Create Contact Us Page with Dummy Content
                $tc_e_commerce_shop_about_title = 'Contact Us';
                $tc_e_commerce_shop_about_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam...<br>

                         Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br> 

                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which dont look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isnt anything embarrassing hidden in the middle of text.<br> 

                            All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
                $tc_e_commerce_shop_about = array(
                    'post_type' => 'page',
                    'post_title' => $tc_e_commerce_shop_about_title,
                    'post_content' => $tc_e_commerce_shop_about_content,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_slug' => 'about-us'
                );
                $tc_e_commerce_shop_about_id = wp_insert_post($tc_e_commerce_shop_about);
                // Add Contact Us Page to Menu
                wp_update_nav_menu_item($tc_e_commerce_shop_menu_id, 0, array(
                    'menu-item-title' => __('Contact Us', 'tc-e-commerce-shop'),
                    'menu-item-classes' => 'about-us',
                    'menu-item-url' => home_url('/about-us/'),
                    'menu-item-status' => 'publish',
                    'menu-item-object-id' => $tc_e_commerce_shop_about_id,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type'
                ));



                // Set the menu location if it's not already set
                if (!has_nav_menu($tc_e_commerce_shop_bpmenulocation)) {
                    $tc_e_commerce_shop_locations = get_theme_mod('nav_menu_locations'); // Use 'nav_menu_locations' to get locations array
                    if (empty($tc_e_commerce_shop_locations)) {
                        $tc_e_commerce_shop_locations = array();
                    }
                    $tc_e_commerce_shop_locations[$tc_e_commerce_shop_bpmenulocation] = $tc_e_commerce_shop_menu_id;
                    set_theme_mod('nav_menu_locations', $tc_e_commerce_shop_locations);
                }
                
        }     

            // Header
            set_theme_mod( 'tc_e_commerce_shop_topbar_hide', true ); 
            set_theme_mod( 'tc_e_commerce_shop_mail', 'xyz123@example.com' ); 
            set_theme_mod( 'tc_e_commerce_shop_call', '+123 456 7890' ); 
            set_theme_mod( 'tc_e_commerce_shop_facebook_url', '#' ); 
            set_theme_mod( 'tc_e_commerce_shop_twitter_url', '#' );
            set_theme_mod( 'tc_e_commerce_shop_instagram_url', '#' );
            set_theme_mod( 'tc_e_commerce_shop_youtube_url', '#' );
            set_theme_mod( 'tc_e_commerce_shop_linkedin_url', '#' );

            //Slider   
            set_theme_mod( 'tc_e_commerce_shop_slider_hide', true ); 
            set_theme_mod( 'tc_e_commerce_shop_slider_button_text', 'LEARN MORE' );
            set_theme_mod( 'tc_e_commerce_shop_slider_button_link', '#' );

            $tc_e_commerce_shop_slider_titles = [
                'DISCOVER THE LATEST TRENDS',   
                'UNBEATABLE DEALS EVERY DAY', 
                'QUALITY YOU CAN TRUST',     
                'SHOP NOW, PAY LATER',         
            ];          
            $tc_e_commerce_shop_slider_contents = [
                'Browse our collection of the latest styles and must-haves for every season.',
                'Grab exclusive discounts on your favorite products while they last.',         
                'Every product is carefully selected to meet the highest standards.',          
                'Enjoy interest-free payment plans and hassle-free shopping.',                 
            ];                        
            for($tc_e_commerce_shop_i=1;$tc_e_commerce_shop_i<=4;$tc_e_commerce_shop_i++){
                $tc_e_commerce_shop_slider_title = $tc_e_commerce_shop_slider_titles[$tc_e_commerce_shop_i - 1];
                $tc_e_commerce_shop_slider_content = $tc_e_commerce_shop_slider_contents[$tc_e_commerce_shop_i - 1];
                // Create post object
                $tc_e_commerce_shop_my_post = array(
                'post_title'    => wp_strip_all_tags( $tc_e_commerce_shop_slider_title ),
                'post_content'  => $tc_e_commerce_shop_slider_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                );
 
                // Insert the post into the database
                $tc_e_commerce_shop_post_id = wp_insert_post( $tc_e_commerce_shop_my_post );
 
                if ($tc_e_commerce_shop_post_id) {
                  // Set the theme mod for the slider page
                  set_theme_mod('tc_e_commerce_shop_slidersettings_page' . $tc_e_commerce_shop_i, $tc_e_commerce_shop_post_id);
 
                   $tc_e_commerce_shop_image_url = get_template_directory_uri().'/images/banner-image'.$tc_e_commerce_shop_i.'.png';
 
                 $tc_e_commerce_shop_image_id = media_sideload_image($tc_e_commerce_shop_image_url, $tc_e_commerce_shop_post_id, null, 'id');
 
                     if (!is_wp_error($tc_e_commerce_shop_image_id)) {
                         // Set the downloaded image as the post's featured image
                         set_post_thumbnail($tc_e_commerce_shop_post_id, $tc_e_commerce_shop_image_id);
                     }
                 }
            } 

            //Product Section
            set_theme_mod( 'tc_e_commerce_shop_sec1_title', 'FEATURED PRODUCTS' );

            $tc_e_commerce_shop_title_array = array(
                array("Product Title 1", "Product Title 2", "Product Title 3", "Product Title 4")
                );
    
            foreach ($tc_e_commerce_shop_title_array as $tc_e_commerce_shop_titles) {
                // Loop to create only 3 products
                for ($tc_e_commerce_shop_i = 0; $tc_e_commerce_shop_i < 4; $tc_e_commerce_shop_i++) {
                    // Create product content
                    $tc_e_commerce_shop_title = $tc_e_commerce_shop_titles[$tc_e_commerce_shop_i];
                    $tc_e_commerce_shop_content = 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
    
                    // Create product post object
                    $tc_e_commerce_shop_my_post = array(
                        'post_title'    => wp_strip_all_tags($tc_e_commerce_shop_title),
                        'post_content'  => $tc_e_commerce_shop_content,
                        'post_status'   => 'publish',
                        'post_type'     => 'product',
                    );
                    set_theme_mod('tc_e_commerce_shop_servicesettings-page-', esc_url($tc_e_commerce_shop_post_id));
                    // Insert the product into the database
                    $tc_e_commerce_shop_post_id = wp_insert_post($tc_e_commerce_shop_my_post);
    
                    if (is_wp_error($tc_e_commerce_shop_post_id)) {
                        error_log('Error creating product: ' . $tc_e_commerce_shop_post_id->get_error_message());
                        continue; // Skip to the next product if creation fails
                    }
    
                    // Add product meta (price, etc.)
                    update_post_meta($tc_e_commerce_shop_post_id, '_regular_price', '25.00'); // Regular price
                    update_post_meta($tc_e_commerce_shop_post_id, '_sale_price', '20.00'); // Sale price
                    update_post_meta($tc_e_commerce_shop_post_id, '_price', '20.00'); // Active price
    
                    // Handle the featured image using media_sideload_image
                    $tc_e_commerce_shop_image_url = get_template_directory_uri() . '/images/product' . ($tc_e_commerce_shop_i + 1) . '.png';
                    $tc_e_commerce_shop_image_id = media_sideload_image($tc_e_commerce_shop_image_url, $tc_e_commerce_shop_post_id, null, 'id');
    
                    if (is_wp_error($tc_e_commerce_shop_image_id)) {
                        error_log('Error downloading image: ' . $tc_e_commerce_shop_image_id->get_error_message());
                        continue; // Skip to the next product if image download fails
                    }
    
                    // Assign featured image to product
                    set_post_thumbnail($tc_e_commerce_shop_post_id, $tc_e_commerce_shop_image_id);
                }
            }
    
            // Create the 'Products' page if it doesn't exist
            $tc_e_commerce_shop_page_query = new WP_Query(array(
                'post_type'      => 'page',
                'title'          => 'Products',
                'post_status'    => 'publish',
                'posts_per_page' => 1
            ));
    
            if (!$tc_e_commerce_shop_page_query->have_posts()) {
                $tc_e_commerce_shop_page_title = 'Products';
                $tc_e_commerce_shop_productpage = '[products limit="4" columns="4"]';
    
                // Append the WooCommerce products shortcode to the content
                $tc_e_commerce_shop_content = '';
                $tc_e_commerce_shop_content .= do_shortcode($tc_e_commerce_shop_productpage);
    
                // Create the new page
                $tc_e_commerce_shop_page = array(
                    'post_type'    => 'page',
                    'post_title'   => $tc_e_commerce_shop_page_title,
                    'post_content' => $tc_e_commerce_shop_content,
                    'post_status'  => 'publish',
                    'post_author'  => 1,
                    'post_slug'    => 'products'
                );
    
                // Insert the page and get its ID
                $tc_e_commerce_shop_page_id = wp_insert_post($tc_e_commerce_shop_page);
    
                // Store the page ID in theme mod
                if (!is_wp_error($tc_e_commerce_shop_page_id)) {
                    set_theme_mod('tc_e_commerce_shop_servicesettings-page-', $tc_e_commerce_shop_page_id);
                }
            }    

            //Copyright Text
            set_theme_mod( 'tc_e_commerce_shop_footer_copy', 'By ThemesCaliber' ); 

            // Set the demo import completion flag
    		update_option('tc_e_commerce_shop_demo_import_completed', true);
    		// Display success message and "View Site" button
    		echo '<p class="notice-text">' . esc_html__('Your demo import has been completed successfully.', 'tc-e-commerce-shop') . '</p>';
    		echo '<span><a href="' . esc_url(home_url()) . '" class="run-import site-btn" target="_blank">' . esc_html__('VIEW SITE', 'tc-e-commerce-shop') . '</a></span>';

        }
    ?>
  
    <p class="note"><?php esc_html_e( 'Please Note: If your website is live and already contains data, we recommend creating a backup first. Running this importer will replace your current settings with the custom values from the demo.', 'tc-e-commerce-shop' ); ?></p>
        <form action="<?php echo esc_url(home_url()); ?>/wp-admin/themes.php?page=tc_e_commerce_shop_guide" method="POST" onsubmit="return validate(this);">
        <?php if (!get_option('tc_e_commerce_shop_demo_import_completed')) : ?>
            <button type="submit" name="submit" class="run-import">
                    <?php esc_html_e('Run Importer','tc-e-commerce-shop'); ?>
                    <span id="spinner" style="display: none;">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/spinner.gif" alt="Loading..." style="width:34px; height:34px; vertical-align: middle;" />
                    </span>
            </button>
        <?php endif; ?>
        </form>
        <script type="text/javascript">
            function validate(valid) {
                if(confirm("Do you really want to import the theme demo content?")){
                    document.getElementById('spinner').style.display = 'inline-block';
                }
                else {
                    return false;
                }
            }
        </script>
    </div>