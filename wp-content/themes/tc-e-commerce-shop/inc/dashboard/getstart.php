<?php
//about theme info
add_action( 'admin_menu', 'tc_e_commerce_shop_gettingstarted' );
function tc_e_commerce_shop_gettingstarted() {    	
	add_theme_page( esc_html__('Theme Demo Content', 'tc-e-commerce-shop'), esc_html__('Theme Demo Content', 'tc-e-commerce-shop'), 'edit_theme_options', 'tc_e_commerce_shop_guide', 'tc_e_commerce_shop_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function tc_e_commerce_shop_admin_theme_style() {
   wp_enqueue_style('tc-e-commerce-shop-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/dashboard/getstart.css');
   wp_enqueue_script('tabs', esc_url(get_template_directory_uri()) . '/inc/dashboard/js/tab.js');

   // Admin notice code START
	wp_register_script('tc-e-commerce-shop-notice', esc_url(get_template_directory_uri()) . '/inc/dashboard/js/notice.js', array('jquery'), time(), true);
	wp_enqueue_script('tc-e-commerce-shop-notice');
	// Admin notice code END

}
add_action('admin_enqueue_scripts', 'tc_e_commerce_shop_admin_theme_style');

//guidline for about theme
function tc_e_commerce_shop_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$theme = wp_get_theme( 'tc-e-commerce-shop' );
?>

<div class="wrapper-info">  
	<div id="tc-header">
			<div class="tc-container main-header">
				<a class="tc-logo">
					<img role="img" src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/dashboard/images/logo.png" alt="" />
				</a>
				<span class="tc-header-action">
				<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customize', 'tc-e-commerce-shop'); ?></a>
				<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'tc-e-commerce-shop' ); ?></a>
				<a href="<?php echo esc_url( 'https://www.themescaliber.com/products/multipurpose-ecommerce-WordPress-theme'); ?>" target="_blank"> <?php esc_html_e( 'Get Premium', 'tc-e-commerce-shop' ); ?></a>
				<a href="<?php echo esc_url( 'https://www.themescaliber.com/products/WordPress-theme-bundle' ); ?>" class="bundle_btn" target="_blank"> <?php esc_html_e( 'Bundle of 220+ Themes at $99', 'tc-e-commerce-shop' ); ?></a>
				</span>
			</div>
		</div>
		<div class="tc-container tab-sec">
			<div class="tc-tabs">
				<ul>
					<li class="tablinks home active" onclick="tc_e_commerce_shop_openCity(event, 'tc_demo')">
						<a href="#">
							<?php esc_html_e( 'Theme Demo Import', 'tc-e-commerce-shop' ); ?>
						</a>
					</li>
					<li class="tablinks" onclick="tc_e_commerce_shop_openCity(event, 'tc_index')">
						<a href="#">
							<?php esc_html_e( 'Free Theme Information', 'tc-e-commerce-shop' ); ?>
						</a>
					</li>
					<li class="tablinks" onclick="tc_e_commerce_shop_openCity(event, 'tc_pro')">
						<a href="#">
							<?php esc_html_e( 'Premium Theme Information', 'tc-e-commerce-shop' ); ?>
						</a>
					</li>
					<li class="tablinks" onclick="tc_e_commerce_shop_openCity(event, 'tc_create')">
						<a href="#">
							<?php esc_html_e( 'Theme Support', 'tc-e-commerce-shop' ); ?>
						</a>
					</li>
				</ul>
			</div><!-- END .tc-tabs -->
		</div>

		<div class="tc-container">
			<div class="tc-section">
				<div  id="tc_demo" class="tabcontent">
					<h2><?php esc_html_e( 'Welcome to TC E-Commerce Shop', 'tc-e-commerce-shop' ); ?> <span class="version">Version: <?php echo esc_html($theme['Version']);?></span></h2>
					<hr>
					<div class="demo">
						<h4><?php esc_html_e( 'Click the "Run Importer" button below to load demo content for TC E-Commerce Shop', 'tc-e-commerce-shop' ); ?></h4>
						<?php /* Demo Import */ require get_parent_theme_file_path( '/inc/dashboard/demo-importer.php' );?>
					</div>
				</div><!-- END .tc-section -->
			</div>
		</div>

		<div class="tc-container">
			<div class="tc-section">
				<div  id="tc_index" class="tabcontent">
					<h2><?php esc_html_e( 'Welcome to TC E-Commerce Shop Theme', 'tc-e-commerce-shop' ); ?> <span class="version">Version: <?php echo esc_html($theme['Version']);?></span></h2>
					<hr>
					<div class="info-link">
						<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'tc-e-commerce-shop' ); ?></a>
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'tc-e-commerce-shop'); ?></a>
						<a class="get-pro" href="<?php echo esc_url( TC_E_COMMERCE_SHOP_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get Pro', 'tc-e-commerce-shop'); ?></a>
					</div>
					<div class="col-tc-6">
						<img role="img" src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/dashboard/images/screen.png" alt="" />
					</div>
					<div class="col-tc-6">
						<P><?php esc_html_e( 'TC E-Commerce Shop WordPress Theme is the ultimate solution to create multipurpose online stores such as online book store, sports store, electronic items store, mobile & tablet store, apparel store, baby store, departmental store, organic vegetables, eco friendly products, biking accessories, Mens Beauty Products, Home Exercise Equipment, dairy products, fashion store, cosmetics shop, handbags store, medical stores, Smart Speakers, Retail Business, Everyday Products, digital shop, Natural Feminine Care Products, hair products, affiliate website, Natural Pet Care, dropshipping, Disinfectant Products, Boutique Rugs, kids or women store, accessories store, luxury, jewelry store. It also covers different businesses including technology, digitals, shoe store, accessory store, organic tea, glass store, kitchen online shop design, product showcase, furniture, affiliate store, home appliances site, grocery, clothing, and decorative stores restaurant, corporate companies, storefront, grocery, e shopper platform, kids fashion, agencies, bloggers, etc. Its built on Bootstrap which makes it a perfect base to sell out eCommerce items. This clean multipurpose WooCommerce WordPress Theme offers extensive functionality to sell different products in a fabulous manner. The mobile friendly nature of this theme gives an amazing viewing experience to the visitors. It is built up of awesome features such as banners, call to action buttons, sidebars, post formats, testimonial section, shortcodes, and a lot more. The theme is developed using optimized codes that help in providing faster page load time each time a visitor browses your site. This customizable responsive WooCommerce WordPress theme offers various personalization options to ease the task of website development. The integrated social media features make this multipurpose theme highly interactive on social sites. Its SEO friendly nature gives enhanced performance and better ranking of your site on search engines. With dozens of customizable options, this user-friendly theme makes a perfect fit for your professional eCommerce website. TC multipurpose eCommerce WordPress theme is all that you need to leave your mark in the world of eCommerce!', 'tc-e-commerce-shop' ); ?></P>
					</div>
				</div>
			</div><!-- END .tc-section -->
		</div>

		<div class="tc-container">
			<div class="tc-section">
				<div id="tc_pro" class="tabcontent">
					<h3><?php esc_html_e( 'TC E-Commerce Shop Theme Information', 'tc-e-commerce-shop' ); ?></h3>
					<hr>
					<div class="info-link-pro">
						<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'Buy Now', 'tc-e-commerce-shop' ); ?></a>
						<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_LIVE_DEMO ); ?>" target="_blank"> <?php esc_html_e( 'Live Demo', 'tc-e-commerce-shop' ); ?></a>
						<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_PRO_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Pro Documentation', 'tc-e-commerce-shop' ); ?></a>
					</div>
					<div class="pro-image">
						<img role="img" src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/dashboard/images/resize.png" alt="" />
					</div>
				<div class="col-pro-5">
				<h4><?php esc_html_e( 'TC Ecommerce Pro Theme', 'tc-e-commerce-shop' ); ?></h4>
				<P><?php esc_html_e( 'Premium eCommerce WordPress Themes are phenomenal in terms of practicality, uniqueness, flexibility, versatility, and usability. You can use these multipurpose WordPress eCommerce Themes to build a diverse range of business stores. The niche specific layout of the themes reduces much of your time and efforts in building an online store. These user-friendly themes provide easy account management, user-friendly payment options, pop-ups, view cart and add to cart functionality, and so on. Furthermore, the versatile design displays every item in a distinct and impressive way. Also, the theme is updated on a regular basis with a multitude of advanced features that keeps you nowhere behind the emerging market trends. The best eCommerce ready WordPress themes have inbuilt WordPress Customizer so that you get full control to manage the different theme areas by utilizing ample of customization options. You can change almost everything from colors, fonts, banners, background, featured images, texts, sidebars, links, etc. You will find different custom shortcodes that are well packed in the WordPress woo-commerce Themes to display multiple elements on posts and pages. The WordPress eCommerce templates are user-friendly due to the in-built features such as advanced filters, pop-ups, social sharing options, responsive elements, well-sorted product pages, and various other exclusive features.', 'tc-e-commerce-shop' ); ?></P>		
				</div>
				<div class="col-pro-6">				
					<h4><?php esc_html_e( 'Product Description', 'tc-e-commerce-shop' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Theme Options using Customizer API', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Responsive design', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Favicon, Logo, title and tagline customization', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Advanced Color options', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( '100+ Font Family Options', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Background Image Option', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Simple and Mega Menu Option', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Enable-Disable options on All sections', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Home Page setting for different sections', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Advance Slider with multiple effects and control options', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Pagination option', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Custom CSS option', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Translations Ready', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Parallax Image-Background Section', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Custom Backgrounds, Colors, Headers, Logo & Menu', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Customizable Home Page', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Full-Width Template', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Footer Widgets & Editor Style', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Gallery, Banner & Post Type Plugin Functionality', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Woo Commerce Compatible', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Multiple Inner Page Templates', 'tc-e-commerce-shop' ); ?></li>
						<li><?php esc_html_e( 'Advance Social Media Feature', 'tc-e-commerce-shop' ); ?></li>
					</ul>				
				</div>	
			</div><!-- END .tc-section -->
		</div>

		<div class="tc-container">
			<div class="tc-section">
				<div id="tc_create" class="tabcontent">
					<div class="tab-cont">
						<h4><?php esc_html_e( 'Need Support?', 'tc-e-commerce-shop' ); ?></h4>				
						<div class="info-link-support">
							<P><?php esc_html_e( 'Our team is obliged to help you in every way possible whenever you face any type of difficulties and doubts.', 'tc-e-commerce-shop' ); ?></P>
							<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_SUPPORT ); ?>" target="_blank"> <?php esc_html_e( 'Support Forum', 'tc-e-commerce-shop' ); ?></a>
						</div>
					</div>
					<div class="tab-cont">	
						<h4><?php esc_html_e('Reviews', 'tc-e-commerce-shop'); ?></h4>				
						<div class="info-link-support">
							<P><?php esc_html_e( 'It is commendable to have such a theme inculcated with amazing features and robust functionalities. I feel grateful to recommend this theme to one and all.', 'tc-e-commerce-shop' ); ?></P>
							<a href="<?php echo esc_url( TC_E_COMMERCE_SHOP_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'tc-e-commerce-shop'); ?></a>
						</div>
					</div>

					<div class="tc-section large-section">
						<h2>Let‘s customize your website</h2>
						<p>There are many changes you can make to customize your website. Explore customization options and make it unique.</p>
						<div class="tc-buttons">
							<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>" class="tc-btn primary large-button"><?php esc_html_e('Start Customizing', 'tc-e-commerce-shop'); ?></a>
						</div><!-- END .tc-buttons -->
					</div>
				</div>
			</div><!-- END .tc-section -->
		</div>
	</div>
<?php } ?>