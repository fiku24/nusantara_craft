=== TC E-Commerce Shop WordPress Theme ===
Contributors: Themescaliber
Tags: left-sidebar, right-sidebar, one-column, two-columns, three-columns, four-columns, grid-layout, block-styles, wide-blocks, custom-background, custom-logo, custom-menu, custom-header, editor-style, featured-images, footer-widgets, flexible-header, sticky-post, full-width-template, theme-options, threaded-comments, translation-ready, post-formats, rtl-language-support, blog, e-commerce, portfolio
Requires at least: 5.0
Tested up to: 7.0
Requires PHP: 7.2
Stable tag: 1.6.3
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

TC E-Commerce Shop is a dynamic, responsive, and multipurpose eCommerce theme designed for online stores including book stores, sports shops, electronics, mobile & tablet outlets, apparel, baby products, departmental stores, organic vegetables, eco-friendly products, biking accessories, men’s beauty products, home exercise equipment, dairy products, fashion and cosmetics, handbags, medical supplies, smart speakers, digital shops, natural feminine care products, hair care, affiliate websites, pet care, dropshipping stores, disinfectants, boutique rugs, kids’ and women’s fashion, accessories, luxury items, jewelry, and more.

== Description ==

TC E-Commerce Shop is a dynamic, responsive, and multipurpose eCommerce theme designed for online stores including book stores, sports shops, electronics, mobile & tablet outlets, apparel, baby products, departmental stores, organic vegetables, eco-friendly products, biking accessories, men’s beauty products, home exercise equipment, dairy products, fashion and cosmetics, handbags, medical supplies, smart speakers, digital shops, natural feminine care products, hair care, affiliate websites, pet care, dropshipping stores, disinfectants, boutique rugs, kids’ and women’s fashion, accessories, luxury items, jewelry, and more. It supports marketplace layouts, multivendor features, product catalogs, shopping cart integration, online payment systems, store management tools, product filters, mega menus, sliders, and grid/list product layouts. Built on Bootstrap for a solid foundation, it integrates seamlessly with WooCommerce for full eCommerce functionality. The theme offers banners, CTAs, sidebars, post formats, testimonials, shortcodes, customization controls, product search, and category management, all within an SEO-friendly, mobile-optimized, and fast-loading design. With flexible personalization options, social media integration, and clean code, TC E-Commerce Shop is perfect for creating professional, high-performing, and interactive online stores across various niches.

== Changelog ==

= 0.1 =
* Initial Version Released

= 0.2 =
* Styling Done

= 0.3 =
* Error resolved.

= 0.3.1 =
* Below are following resolved points:-
	-- On the all_the_cool_cats issue, the problem wasn't with the name of the variables, but with the name of the transient itself(sorry if I wasn't clear enough on it). Variables inside of your functions you can name however you like, but since the transient will be stored in the database, just like post meta, it needs to have a custom prefix. So the name of your transient(what you pass to get_transient() and set_transient()) should be tc_e_commerce_shop_all_the_cool_cats.

    --It seems like you accidentally removed the template-parts folder, so now I can't check the sticky post fix :)

    --The fix for the IE style is to simply remove the dependency(as that's what preventing the style from being enqueued). Here's the updated code: wp_enqueue_style('tc-e-commerce-shop-ie', get_template_directory_uri().'/css/ie.css');

    --On the screenshot and image licensing, you need to declare all images that are part of the screenshot. So not just the background image, but also the camera image, the woman, shoes, watch and sunglasses.

    --Also on the screenshot - I don't feel like it follows the requirement of being a reasonable representation of what the theme can look like. I'm going to highlight the specific issues that I'm seeing in terms of the screenshot vs the theme installed locally:

    --There's no way in the theme to hide the site title
    --There's no way to change any of the content over the slider background. The title and "Learn More" are automatically inserted over the featured image and centered.
    --The arrows are still not exactly like in the screenshot.
    --The styling of the products is not the same as in the screenshot
    --The red border(left, right and bottom), as well as the black bar on the bottom do not exist in the actual theme

= 0.3.2 =
* 	Below are following resolved points:-
	-- In template-tags.php, you're still calling delete_transient( 'all_the_cool_cats' ); instead of delete_transient( 'tc_e_commerce_shop_all_the_cool_cats' );

	-- The instructions on how to setup the home page should be part of the readme, or otherwise implemented for the end-user to see(you can add descriptions to the Customizer sections for example). Adding the instructions to the readme will be enough in general.

= 0.3.3 =
* Tested Upto WordPress Version 4.9.1.
* Done Styling Changes.
* Remove Unwanted css code.
* Added Hooks In Theme.
	
= 0.3.4 =
* Long Site Title and Site Description are handling properly. 
* Removed the archive template part form index.php , search.php and archive.php.
* Removed wp_reset_postdata(); form template-tag.php ex wp_get_attachment_image( $post->ID, $attachment_size ).
* Removed in-line style code from custom-header.php.

= 0.3.5 =
* Woocommerce files updated.
* Fontawesome files updated.
* Font code update in function.php file.
* Done the css customization. 

= 0.3.6 =
* Update bootstrap.
* Added post format files and done the changes in template-part folder.
* Removed the unused css.
* Removed all the font family and add new font in theme.
* update the translation file in language folder.
* Added js code in custom.js and delete the other js files.

= 0.3.7 =
* Added color and font pallete.
* Resolved theme errors.

= 0.3.8 =
* Changed the slider.
* Resolved theme errors.
* Updated woocommerce.
* Changed theme screenshot.
* Changed image urls.
* Updated POT file.

= 0.3.9 =
* Changed Screenshot.
* Updated readme file.

= 0.4 =
* Added theme color option.
* Updated POT file.
* Resolved theme issue.

= 0.4.1 =
* Changed the notice design in dashboard.
* Added skip to content part in the theme.

= 0.4.2 =
* Resolved contrast errors.
* Added new menu style.
* Added new search style.
* Updated pot file.
* Added Show/hide option for topbar in customizer.
* Added sticky header option in customizer.
* Changed some theme code.

= 0.4.3 =
* Added Logo Width option in customizer.
* Updated pot file.

= 0.4.4 =
* Added Width layout option in customizer.
* Updated Woocommerce widgets css.
* Updated POT file.

= 0.4.5 =
* Added enable / disable option for post date, post author & post comments.
* Updated POT file.

= 0.4.6 =
* Added footer widget layout setting in customizer.

= 0.4.7 =
* Added show/hide Back to Top button and its alignment.
* Starter content is added in theme.
* Selective Refresh is added in theme.
* Updated .pot file.

= 0.4.8 =
* Added Slider content spacing option.
* Added Show/hide option for slider title & Button.
* Added slider content alignement option.
* Added slider image opacity option and slider speed option.
* Added Show/hide option for site title & site description.
* Updated .pot file.

= 0.4.9 =
* Added Post excerpt length and excerpt suffix settings.
* Added Post Content type setting.
* Added Post button text, border radius and Button padding settings.
* Added Enable / Disable option for Related post and its title setting.
* Added Related posts order by setting and its count setting.
* Updated .pot file.

= 0.5 =
* Added Show/Hide Shop page and Single Product page sidebar options.
* Added Show/hide Related products option in single product page.
* Added Products per row and Products per page options.
* Added Woocommerce button padding and border radius options.
* Added Show/Hide Product border and Product border radius option.
* Added Products padding and product box shadow options.
* Updated .pot file.

= 0.5.1 =
* Added Enable/ Disable Single post feature image and Tags options.
* Added Enable/ Disable Single post comment and nav links options.
* Added Single post Previous navigation text option.
* Added Single post Next navigation text option.
* Added 404 page setttings.
* Updated .pot file.

= 0.5.2 =
* Added preloader & its show/hide option.
* Added preloader type option.
* Added preloader background color options.
* Added copyright padding & font size options.
* Added copyright alignment option.
* Resolved theme errors.
* Updated .pot file.

= 0.5.3 =
* Resolved within menu focus issue.
* Resolved some theme errors.

= 0.5.4 =
* Added Sale badge position option.
* Added Sale badge top bottom padding option.
* Added Sale badge left right padding option.
* Added Sale badge border radius option.
* Added footer background color option.
* Added footer background image option.
* Added Back to Top Button Text option.
* Added sanitize_callback function input type.
* Prefixing done in custom.js
* Removed "esc_html" from echoing function
* Added triple license of Font Awesome.
* Removed stable tag from readme.txt
* Updated .pot file.

= 0.6 =
* Added Comment Form Width option.
* Added Comment title option.
* Added Comment button text option.
* Added No Result Page Title option.
* Added No Result Page text option.
* Added Show/Hide Search Form option.
* Updated .pot file.

= 0.6.1 =
* Added Post Navigation type option.
* Added Enable / Disable Post Navigation option.
* Added Topbar top bottom padding option.
* Added Sticky Header Padding option.
* Make clickable phone number and email.
* Updated .pot file.

= 0.6.2 =
* Changed menu focus code.
* Changed License & License URI in style.css
* Added has_nav_menu() condition in header.php.
* Changed header images dimensions.

= 0.6.3 =
* Added FontAwesome icon changer option for every icons.
* Added stable tag in readme.txt file.
* Added flexible header tag in theme.
* Added .mo and .po files of Arabic, German, Spanish, French, Italian, Russian, Turkish and Chinese languages.

= 0.6.4 =
* Added Wide Block tag in theme.
* Resolved some errors.

= 0.6.5 =
* Error Resolved.

= 0.6.6 =
* Reduced css by adding padding margin classes of bootstrap.

= 0.6.7 =
* Added escaping to get_template_directory_uri().
* Changed outline type in css.
* Done some prefixing.

= 0.6.8 =
* Added navigation case option.
* Added navigation font size option.
* Removed jquery.min.js file & its enqueued code.
* Removed effects.css file & its enqueued code.
* Done some changes in customizer.
* Updated all .po files.

= 0.6.9 =
* Changed email sanitization.
* Changed some function.php code.
* Added some classes in global color css.
* Added site title and description font size option.
* Updated all .po files.

= 0.7 =
* Added Enable/Disable Featured image option for blog page.
* Added Featured image border radius option.
* Added Featured image box shadow option.
* Added linkedin social media settings.
* Updated all .po files.

= 0.7.1 =
* Added option to Slider Height.
* Updated .pot file.

= 0.7.2 =
* Added Enable/Disable Shop page pagination option.
* Added Post blocks option.
* Updated .pot file.

= 0.7.3 =
* Updated bootstrap version 5.0.1.
* Changed preloader js.

= 0.7.4 =
* Added post navigation position option.
* Changed responsive media css.
* Updated .pot file.

= 0.7.5 =
* Resolved css error.
* Added theme support function for html5.
* Added theme support function for responsive embeds.
* Updated .pot file.

= 0.7.6 =
* Added Open Menu Icon option in Mobile Media.
* Added Close Menu Icon option in Mobile Media.
* Added Show / Hide Topbar option in Mobile Media.
* Added Show / Hide Back to Top Button option in Mobile Media.
* Updated .pot file.

= 0.7.7 =
* Added Show / Hide Preloader option in Mobile Media.
* Updated .pot file.

= 0.7.8 =
* Added show/hide slider overlay settings.
* Added slider overlay color settings.
* Updated .pot file.

= 0.7.9 =
* Added show/hide Slider option in Mobile Media.
* Updated .pot file.

= 0.8 =
* Added width and margin for search widget in style.css.
* Added width for login box in style.css file.
* Resolved search widget css error.
* Changed title of sidebar options for theme layout setting in customizer.php file.
* Added hover effect on anchor tag in style.css file.
* Resolved theme check errors. 

= 0.8.1 =
* Added option to change product sale font size.
* Updated .pot file.

= 0.8.2 =
* Added enable / disable option for post time.
* Updated all .po files.

= 0.8.3 =
* Added navigation font weight option.
* Updated .pot file.

= 0.8.4 =
* Added Social icon font size option.
* Updated .pot file.

= 0.8.5 =
* Added Copyright Background Color.
* Added free theme Documentation link in customizer and get started.
* Added premium theme Documentation link in get started.
* Updated .pot file.

= 0.8.6 =
* Added Enable / Disable Single Post Time.
* Updated .pot file.

= 0.8.7 =
* Added Show/Hide Sticky header for mobile media options.
* Updated .pot file.

= 0.8.8 =
* Added Copyright Background Color.
* Updated .pot file.

= 0.8.9 =
* Added condition for slider image.
* Updated .pot file.

= 0.9 =
* Change css for sidebar widget.

= 0.9.1 =
* Added web font file.
* Added .POT file.

= 0.9.2 =
* Added Block pattern in theme.
* Updated .POT file.

= 0.9.3 =
* Added single post categories option.
* Added css for single post.
* Added site title color option.
* Added site description color option.
* Added body color option. 
* Added body fonts option.
* Added body font size option.
* Update .POT file.

= 0.9.4 =
* Added logo margin option
* Added logo padding option
* Added menu icon color option
* Update POT file.

= 0.9.5 =
* Changed font family functions.
* Added prefixing for variable.
* Added load theme textdomain function in function.php.
* Added esc_attr for icons.
* Added requires at least in style.css.
* Changed gpl version in style and readme file.
* Removed esc_url from function.php. 
* Added shop page sidebar layout option.
* Added Enable / Disable single product page sidebar option.
* Added single product page layout option.
* Update POT file.

= 0.9.6 =
* Added single post sidebar layout option.
* Update POT file.

= 0.9.7 =
* Added menu color option.
* Added menu hover color option.
* Added submenu color option.
* Update POT file.

= 0.9.8 =
* Added single post breadcrumb option.
* Added single page breadcrumb option.
* Update POT file.

= 0.9.9 =
* Added show/hide single post date,author,comment no option.
* Update POT file.

= 1.0 =
* Added show/hide slider button option in responsive media.
* Added single post image border radius option.
* Added single post image shadow option.
* Done reordering of settings in single post section.
* Update POT file.

= 1.0.1 =
* Added submenu hover color.
* Added back to top font size option.
* Added back to top color option.
* Added single page sidebar layout option.
* Added show/hide sidebar option in responsive media. 
* Update POT file.

= 1.0.2 =
* Added about theme section.
* Added demo link,documentation link and support link in about theme section.
* Added breadcrums color option.
* Added breadcrums background color option.
* Added breadcrums hover color option.
* Added breadcrums hover background color option.
* Added single post category color option.
* Added single post category bg color option.
* Added show /hide copyright option.
* Added show /hide Footer option.
* Update POT file.

= 1.0.3 =
* Added copyright color option.
* Added copyright hover color.
* Resolved css error for related post content in tablet media.
* Resolved css error for related post button in tablet media.
* Resolved css error for single product page.
* Update POT file.

= 1.0.4 =
* Added blog post metabox seperator option.
* Added single post metabox seperator option.
* Update POT file.

= 1.0.5 =
* Added footer widget heading alignment option.
* Added footer widget content alignment option.
* Resolved error for metabox saperator.
* Update POT file.

= 1.0.6 =
* Added esc_attr in woocommerce pages.
* Tested upto WP v6.3
* Resolved error for footer widget content alignment.
* Resolved css error for related post button.
* Update POT file.

= 1.0.7 =
* Resolved css error for image markup.
* Resolved css error for for block image.
* Resolved css error for blog buttons.
* Resolved css error for block cover.
* Resolved css error for block gallery image.
* Resolved error for single post comment box.
* Added css for single post comment section.
* Fixed the pagination issue on clearfloat page.
* Resolved menu condition issues.
* Resolved error for submenu colors.

= 1.0.8 =
* Added blog post date icon,author icon,comment icon,time icon option.
* update pot file.

= 1.0.9 =
* Resolved css error for blog buttons.
* Added css for block widgets sidebar.
* Added css for block widgets footer.
* Added classes in global color.
* Resolved layout elements page css. 

= 1.1 =
* Added single post date icon,author icon,comments icon,time icon.
* Changed condition for tagline.
* Added classes in global color.
* Update POT file.

= 1.1.1 =
* Added Enable / disable post category option.
* Resolved errors for block pattern.
* Changed condition for single page breadcrumb.
* Update POT file.

= 1.1.2 =
* Added blog post alignment option.
* Added tgm file.
* Tested upto WP v6.4
* Resolved font family issue.
* Update POT file.

= 1.1.3 =
* Added css for checkout page.
* Added css for cart page.
* Added classes in global color.
* Changed condition for slider in responsive media. 
* Resolved error for topbar social icons.

= 1.1.4 =
* Added slider button link option.
* Update POT file.

= 1.1.5 =
* Changed rtl css.
* Added css for view cart button.

= 1.1.6 =
* Added open menu label option in mobile media section.
* Added close menu label option in mobile media section.
* Update POT file.

= 1.1.7 =
* Added premium features information in header section,product section and post setting.
* Resolved error for toggle menu.
* Update POT file.

= 1.1.8 =
* Added footer widgets heading font size option.
* Added footer widgets heading font weight option.
* Update POT file.

= 1.1.9 =
* Added slider prev and next icon option in slider settings.
* Resolved error for slider height.
* Changed condition for slider button link.
* Update POT file.

= 1.2 =
* Resolved css error for view cart button in single product page.
* Resolved css error for checkout page summary block. 
* Tested upto WP v6.5.

= 1.2.1 =
* Added footer heading text transform option.
* Added search option in preview sidebar.
* Update woocommerce
* Updated fontawsome file.
* Resolved escaping issue in about theme section and premium features.
* Update POT file.

= 1.2.2 =
* Added blog post button font size option.
* Resolved error for post time and single post time.
* Update POT file.

= 1.2.3 =
* Added related post excerpt length option.
* Added post button text transform option.
* Update POT file.

= 1.2.4 =
* Added footer social icons pannel in customizer.
* Added footer social icons link options.
* Added footer social icons changing options.
* Added show/Hide option for Footer social icons.
* Added footer background attatchment option in customizer.
* Added footer image position option in customizer.
* Update POT file.

= 1.2.5 =
* Updated links of buy now ,free url and docs.

= 1.2.6 =
* Added button settings pannel in customizer.
* Added font weight options in button settings.
* Added font size options in footer social icons section.
* Added css for myaccount address button.
* Update POT file.

= 1.2.7 =
* Added icon alignment options in footer social icons section.
* Resolved css error in myaccount navigation.
* Resolved css error for search button.
* Tested upto WP v6.6
* Update POT file.

= 1.2.8 =
* Added show/hide slider arrows in slider settings.
* Added css for proceed to checkout button in cart page.
* Resolved css error image widgets in blog sidebar.
* Update POT file.

= 1.2.9 =
* Added review link in about theme section.
* Added letter spacing options in button settings.
* Added icon color options in footer social icons section.
* Update POT file.

= 1.3 =
* Added font weight option in color/font pallete.
* Added back to top icon hover color.
* Resolved css error in premium features.
* Resolved error in font size option in color/font pallete.
* Update POT file.

= 1.3.1 =
* Resolved excerpt length error in post settings.
* Added preloader background image option in customizer.
* Resolved css error in customizer.
* Tested upto WP v6.7
* Update POT file.

= 1.3.2 =
* Added menu item hover style option in customizer.
* Resolved css error in currency switcher.
* Resolved css error in shop page and single product page.
* Update woocommerce.
* Update POT file.

= 1.3.3 =
* Added back to top icon text transform.
* Added demo import.
* Resolved css error in customizer.
* Update POT file.

= 1.3.4 =
* Added button shape option in customizer.
* Resolved error in footer section.
* Resolved escaping issue.
* Update POT file.

= 1.3.5 =
* Added featured image dimension option in blog post settings.
* Added button hover effect option in customizer.
* Added css for address button my account page.
* Resolved css error in customizer.
* Update POT file.

= 1.3.6 =
* Added grid post settings panel in customizer.
* Added option for grid post into blocks and without blocks.
* Added featured image border radius option in grid post settings.
* Resolved css error in myaccount page.
* Update POT file.

= 1.3.7 =
* Added show/hide date, author, comments and time option in grid post settings.
* Resolved global color error.
* Update POT file.

= 1.3.8 = 
* Added grid post icon changing option in date, author, comments and time option.
* Added related post section panel in customizer.
* Added footer heading letter spacing.
* Tested upto WP v6.8
* Update POT file.

= 1.3.9 = 
* Added grid post content type option.
* Added grid post metabox separator option.
* Added related post show / hide featured image option.
* Updated activation notice.
* Resolved error of footer content alignment setting.
* Update POT file.

= 1.4 = 
* Added sidebar main heading background image.
* Added hover effect to back to scroll button.
* Updated the theme live preview sidebar and blog post with dark border.
* Updated get started page.
* Added hover effect in sidebar content.
* Added css in blog post heading.

= 1.4.1 = 
* Added css for get pro button and about theme section in customizer.
* Fixed responsiveness issues in the related posts section for tablet media.
* Added css for single post image and blog post image.
* Removed ibtana plugin recommendation.
* Added css for active tab in get started.

= 1.4.2 = 
* Columns selection option in grid post.
* Added wow.js effect in blog post.
* Added initial letter capital setting in blog post.
* Updated activation notice.
* Updated get started page.

= 1.4.3 = 
* Fixed bugs.

= 1.4.4 = 
* Added meta info in related post.
* Added sticky sidebar option in theme layout settings.
* Updated font awesome.

= 1.4.5 =
* Fixed bugs.

= 1.4.6 = 
* Added image dimension option in single post settings.
* Added related posts meta box info separator option.
* Added hide/show featured image in grid post settings.

= 1.4.7 =
* Fixed bugs.

= 1.4.8 = 
* Added loader for demo import button.
* Added menu font padding option.
* Added related post excerpt suffix option.

= 1.4.9  =
* Resolve minor bugs.

= 1.5.0 =
* Apply animation on sidebar in post page.
* Added animation on footer.
* Added footer copyright sticky.

= 1.5.1 =
* Resolved minor bugs.
* Added description.

= 1.5.2 =
* Added menus in demo import.
* Resolved activation notice dismiss issue.
* Tested upto WP v6.9.

= 1.5.3 =
* Fixed minor bugs.
* Updated description.

= 1.5.4 =
* Added on/off setting of animation. 
* Updated front page heading structure.
* Resolved get started page css eror in responsive media.
* Added animation on front page.
* Resolved error for woocommerce page redirection in demo import.
* Resolved plugin tgm error.

= 1.5.5 =
* Fetching post on blog menu.
* Added hover effect on sidebar blogs.
* Added css on single post page.
* Resolved css error in categories page.
* Resolved css error of activation notice.
* Resolved prefixing issues.
* Updated pot file. 

= 1.5.6 =
* Added custom cursor setting.
* Added slider button background color option.
* Added slider button lable color option.
* Updated rtl file.
* Updated pot file.

= 1.5.7 =
* Fixed minor bugs.

= 1.5.8 =
* Version Updated.

= 1.5.9 =
* General Maintenance Release.

= 1.6.0 =
* Enhanced Visual Appearance.

= 1.6.1 =
* Resolved the CSS issue affecting the banner notice button.
* Resolved the CSS issue affecting the customizer button.


= 1.6.2 =
* Framework Optimization.

= 1.6.3 =
* Enhanced Visual Appearance.
== Resources ==

TC E-Commerce Shop WordPress Theme, Copyright 2017 Themescaliber
TC E-Commerce Shop is distributed under the terms of the GNU GPL

Theme is Built using the following resource bundles.

- Bootstrap 
	-- Mark Otto
	-- copyright 2011-2021, Mark Otto
	-- https://github.com/twbs/bootstrap/releases/download/v5.0.1/bootstrap-5.0.1-dist.zip
	-- License: Code released under the MIT License. v5.0.1
	-- https://github.com/twbs/bootstrap/blob/main/LICENSE

- Font-Awesome 
	-- Davegandy
    -- Copyright 2025 Fonticons, Inc.
    -- https://github.com/FortAwesome/Font-Awesome.git
    -- License: Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License
    -- https://github.com/FortAwesome/Font-Awesome/blob/6.x/LICENSE.txt

- Customizer Pro 
	-- Justin Tadlock
	-- Copyright 2016, Justin Tadlock
	-- https://github.com/justintadlock/trt-customizer-pro.git
	-- License: GNU General Public License v3.0
	-- http://www.gnu.org/licenses/old-licenses/gpl-3.0.html

- Superfish 
	-- Joeldbirch
	-- Copyright 2013, Justin Tadlock
	-- https://github.com/joeldbirch/superfish.git
	-- License: Free to use and abuse under the MIT license. v1.7.9
	-- https://github.com/joeldbirch/superfish/blob/master/MIT-LICENSE.txt

- Webfonts Loader
  	-- https://github.com/WPTT/webfont-loader
  	-- License: https://github.com/WPTT/webfont-loader/blob/master/LICENSE

- TGMPA
   - GaryJones Copyright (C) 1989, 1991
   - https://github.com/TGMPA/TGM-Plugin-Activation/blob/develop/LICENSE.md
   - License: GNU General Public License v2.0        

- Screenshot Images.
	License: CC0 1.0 Universal (CC0 1.0) 
	Source: https://stocksnap.io/license

	Slider image, Copyright Lisa Fotios
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/ELZU79WHYI

	Slider image, Copyright Lisa Fotios
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/1OLSIPVRE0

	Slider image, Copyright Lisa Fotios
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/KKOQ2ZWHO1

	Slider image, Copyright Lisa Fotios
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/KRMOSAYCZ4

	Product image, Copyright EVG Photos
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/UHAQDIYT6X

	Product image, Copyright Jess Watters
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/KFMR70XLYS

	Product image, Copyright Burst
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/IVZDQN85VY

	Product image, Copyright Jess Watters
	License: CC0 1.0 Universal (CC0 1.0)
	Source: https://stocksnap.io/photo/LSOVUF5JEA

- WOW JS 
    Matthieu Aussaguel
    Copyright 2016, Matthieu Aussaguel
    https://github.com/matthieua/WOW
    License: GPLv3
    https://github.com/matthieua/WOW/blob/master/README.md

- Animate CSS 
    Daniel Eden
    Copyright 2021 Daniel Eden
    https://github.com/animate-css/animate.css
    License: 2.1 https://github.com/animate-css/animate.css/blob/main/LICENSE
