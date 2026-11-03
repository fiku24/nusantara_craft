<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="content-aa">
 *
 * @package TC E-Commerce Shop
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
  <?php if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
  } else {
    do_action( 'wp_body_open' );
  }?>
  <?php if(get_theme_mod('tc_e_commerce_shop_preloader_hide',false)!= '' || get_theme_mod('tc_e_commerce_shop_responsive_preloader_hide',false) != ''){ ?>
    <?php if(get_theme_mod( 'tc_e_commerce_shop_preloader_type','center-square') == 'center-square'){ ?>
      <div class='preloader'>
        <div class='preloader-squares'>
          <div class='square'></div>
          <div class='square'></div>
          <div class='square'></div>
          <div class='square'></div>
        </div>
      </div>
    <?php }else if(get_theme_mod( 'tc_e_commerce_shop_preloader_type') == 'chasing-square') {?>    
      <div class='preloader'>
        <div class='preloader-chasing-squares'>
          <div class='square'></div>
          <div class='square'></div>
          <div class='square'></div>
          <div class='square'></div>
        </div>
      </div>
    <?php }?>
  <?php }?>
  <header role="banner" class="full-header">
    <a class="screen-reader-text skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'tc-e-commerce-shop' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'tc-e-commerce-shop' );?></span></a>
    <?php if( get_theme_mod('tc_e_commerce_shop_topbar_hide', false)!= '' || get_theme_mod('tc_e_commerce_shop_responsive_topbar_hide',false) != ''){ ?>
      <div class="tc-e-commerce-shop-topbar">
        <div class="container">
          <div class="baricon p-2">
            <?php if( get_theme_mod( 'tc_e_commerce_shop_mail','' ) != '') { ?>
              <a target="_blank" href="mailto:<?php echo esc_attr( get_theme_mod('tc_e_commerce_shop_mail','')); ?>"><span class="email  me-xl-2 me-lg-0 me-md-2 py-md-0 py-2"><?php if(get_theme_mod('tc_e_commerce_shop_mail_icon') != 'None'){?><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_mail_icon','fa fa-envelope')); ?>  me-1" aria-hidden="true"></i><?php }?><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_mail','') ); ?></span><span class="screen-reader-text"><?php esc_html_e( 'Email','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_call','' ) != '') { ?>
              <a target="_blank" href="tel:<?php echo esc_attr( get_theme_mod('tc_e_commerce_shop_call','')); ?>"><span class="call pb-md-0 pb-2"><?php if(get_theme_mod('tc_e_commerce_shop_phone_icon') != 'None'){?><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_phone_icon','fa fa-phone')); ?> me-1" aria-hidden="true"></i><?php }?><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_call','')); ?></span><span class="screen-reader-text"><?php esc_html_e( 'Phone Number','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_facebook_url') != '' && get_theme_mod('tc_e_commerce_shop_facebook_icon') != 'None') { ?>
              <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_facebook_url','' ) ); ?>"><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_facebook_icon','fab fa-facebook-f')); ?> ms-xl-3 ms-lg-1 ms-3" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Facebook','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_twitter_url') != '' && get_theme_mod('tc_e_commerce_shop_twitter_icon') != 'None') { ?>
              <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_twitter_url','' ) ); ?>"><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_twitter_icon','fab fa-twitter')); ?> ms-xl-3 ms-lg-1 ms-3" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Twitter','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_instagram_url') != '' && get_theme_mod('tc_e_commerce_shop_instagram_icon') != 'None') { ?>
              <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_instagram_url','' ) ); ?>"><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_instagram_icon','fab fa-instagram')); ?> ms-xl-3 ms-lg-1 ms-3"></i><span class="screen-reader-text"><?php esc_html_e( 'Instagram','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_youtube_url') != '' && get_theme_mod('tc_e_commerce_shop_youtube_icon') != 'None') { ?>
              <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_youtube_url','' ) ); ?>"><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_youtube_icon','fab fa-youtube')); ?> ms-xl-3 ms-lg-1 ms-3" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Youtube','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_linkedin_url') != '' && get_theme_mod('tc_e_commerce_shop_linkedin_icon') != 'None') { ?>
              <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_linkedin_url','' ) ); ?>"><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_linkedin_icon','fab fa-linkedin-in')); ?> ms-xl-3 ms-lg-1 ms-3" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e( 'Youtube','tc-e-commerce-shop' );?></span></a>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php }?>
    <div id="header" class="<?php if( get_theme_mod( 'tc_e_commerce_shop_sticky_header', false) != '' || get_theme_mod('tc_e_commerce_shop_responsive_sticky_header',false) != '') { ?> sticky-header<?php } else { ?>close-sticky <?php } ?>">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-5 col-6 py-1 px-3 align-self-center">
            <div class="tc-e-commerce-shop-logo">
              <?php if ( has_custom_logo() ) : ?>
                <div class="site-logo"><?php the_custom_logo(); ?></div>
              <?php endif; ?>
              <?php if( get_theme_mod( 'tc_e_commerce_shop_site_title',true) != '') { ?>
                <?php $blog_info = get_bloginfo( 'name' ); ?>
                <?php if ( ! empty( $blog_info ) ) : ?>
                  <?php if ( is_front_page() && is_home() ) : ?>
                    <h1 class="site-title p-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                  <?php else : ?>
                    <p class="site-title m-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                  <?php endif; ?>
                <?php endif; ?>
              <?php }?>
              <?php if( get_theme_mod( 'tc_e_commerce_shop_site_tagline',false) != '') { ?>
                <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
                ?>
                  <p class="site-description m-0">
                    <?php echo esc_html($description); ?>
                  </p>
                <?php endif; ?>
              <?php }?>
            </div>
          </div>
          <div class="col-lg-9 col-md-7 col-6 align-self-center">
            <div class="menubox nav align-self-center">
              <?php ?>
                <div class="toggle-menu responsive-menu p-1 mt-2">
                  <button role="tab" onclick="tc_e_commerce_shop_menu_open()"><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_responsive_open_menu_icon','fas fa-bars'));?>"></i><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_open_menu_label', __('Open Menu','tc-e-commerce-shop'))); ?><span class="screen-reader-text"><?php esc_html_e('Open Menu','tc-e-commerce-shop'); ?></span></button>
                </div>
                <div id="menu-sidebar" class="nav side-menu">
                  <nav id="primary-site-navigation" class="primary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'tc-e-commerce-shop' ); ?>">
                    <?php 
                      wp_nav_menu( array( 
                        'theme_location' => 'primary',
                        'container_class' => 'main-menu-navigation clearfix' ,
                        'menu_class' => 'clearfix',
                        'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
                        'fallback_cb' => 'wp_page_menu',
                      ) ); 
                    ?>
                    <a href="javascript:void(0)" class="closebtn responsive-menu" onclick="tc_e_commerce_shop_menu_close()"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_close_menu_label', __('Close Menu','tc-e-commerce-shop'))); ?><i class="<?php echo esc_html(get_theme_mod('tc_e_commerce_shop_responsive_close_menu_icon','fas fa-times'));?>"></i><span class="screen-reader-text"><?php esc_html_e('Close Menu','tc-e-commerce-shop'); ?></span></a>
                  </nav>
                </div>
              <?php ?>
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </div>
  </header>