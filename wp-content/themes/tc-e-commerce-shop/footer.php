<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package TC E-Commerce Shop
 */
?>
<footer role="contentinfo">
        <?php //Set widget areas classes based on user choice
            $tc_e_commerce_shop_widget_areas = get_theme_mod('tc_e_commerce_shop_footer_widget_layout', '4');
            if ($tc_e_commerce_shop_widget_areas == '3') {
                $tc_e_commerce_shop_cols = 'col-lg-4 col-md-6';
            } elseif ($tc_e_commerce_shop_widget_areas == '4') {
                $tc_e_commerce_shop_cols = 'col-lg-3 col-md-6';
            } elseif ($tc_e_commerce_shop_widget_areas == '2') {
                $tc_e_commerce_shop_cols = 'col-lg-6 col-md-6';
            } else {
                $tc_e_commerce_shop_cols = 'col-lg-12 col-md-12';
            }
        ?>
        <?php if (get_theme_mod('tc_e_commerce_shop_footer_hide_show', true)){ ?>
            <div class="footertown">
                <div class="container wow bounceInUp">
                    <div class="row">
                        <!-- Footer 1 -->
                        <div class="<?php echo esc_attr($tc_e_commerce_shop_cols); ?> footer-block">
                            <?php if (is_active_sidebar('footer-1')) : ?>
                                <?php dynamic_sidebar('footer-1'); ?>
                            <?php else : ?>
                                <aside id="text-about" class="widget py-3" role="complementary" aria-label="<?php esc_attr_e('About Us', 'tc-e-commerce-shop'); ?>">
                                    <h2 class="widget-title"><?php esc_html_e('About Us', 'tc-e-commerce-shop'); ?></h2>
                                    <p><?php esc_html_e('Add a brief description about your business here.', 'tc-e-commerce-shop'); ?></p>
                                </aside>
                            <?php endif; ?>
                        </div>

                        <!-- Footer 2 -->
                        <div class="<?php echo esc_attr($tc_e_commerce_shop_cols); ?> footer-block">
                            <?php if (is_active_sidebar('footer-2')) : ?>
                                <?php dynamic_sidebar('footer-2'); ?>
                            <?php else : ?>
                                <aside id="archives" class="widget py-3" role="complementary" aria-label="<?php esc_attr_e('Archives', 'tc-e-commerce-shop'); ?>">
                                    <h2 class="widget-title"><?php esc_html_e('Archives', 'tc-e-commerce-shop'); ?></h2>
                                    <ul>
                                        <?php wp_get_archives(array('type' => 'monthly')); ?>
                                    </ul>
                                </aside>
                            <?php endif; ?>
                        </div>

                        <!-- Footer 3 -->
                        <div class="<?php echo esc_attr($tc_e_commerce_shop_cols); ?> footer-block">
                            <?php if (is_active_sidebar('footer-3')) : ?>
                                <?php dynamic_sidebar('footer-3'); ?>
                            <?php else : ?>
                                <aside id="business-info" class="widget py-3" role="complementary" aria-label="<?php esc_attr_e('Business Info', 'tc-e-commerce-shop'); ?>">
                                    <h2 class="widget-title"><?php esc_html_e('Business Info', 'tc-e-commerce-shop'); ?></h2>
                                    <p><?php esc_html_e('Provide your business address, contact details, and other important information here.', 'tc-e-commerce-shop'); ?></p>
                                </aside>
                            <?php endif; ?>
                        </div>

                        <!-- Footer 4 -->
                        <div class="<?php echo esc_attr($tc_e_commerce_shop_cols); ?> footer-block">
                            <?php if (is_active_sidebar('footer-4')) : ?>
                                <?php dynamic_sidebar('footer-4'); ?>
                            <?php else : ?>
                                <aside id="search-widget" class="widget py-3" role="complementary" aria-label="<?php esc_attr_e('Search', 'tc-e-commerce-shop'); ?>">
                                    <h2 class="widget-title"><?php esc_html_e('Search', 'tc-e-commerce-shop'); ?></h2>
                                    <?php the_widget('WP_Widget_Search'); ?>
                                </aside>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>   
<div class="footer <?php if( get_theme_mod( 'tc_e_commerce_shop_copyright_sticky', false) == 1) { ?> copyright-sticky"<?php } else { ?>close-sticky <?php } ?>">   
    <?php if (get_theme_mod('tc_e_commerce_shop_copyright_hide_show', true)) {?>
        <div id="footer" class="copyright-wrapper">
        	<div class="container wow bounceInUp">
                <div class="copyright">
                    <p><?php tc_e_commerce_shop_credit_link(); ?> <?php echo esc_html(get_theme_mod('tc_e_commerce_shop_footer_copy',__('By ThemesCaliber','tc-e-commerce-shop'))); ?></p>
                
                    <?php if (get_theme_mod('tc_e_commerce_shop_show_footer_social_icon', true)){ ?>     
                    <div class="socialicons col-lg-12 col-md-12 col-12 align-self-center">                      
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_footer_facebook_url' ) != '' && get_theme_mod('tc_e_commerce_shop_footer_facebook_icon') != 'None') { ?>
                            <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_footer_facebook_url','' ) ); ?>"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_footer_facebook_icon', 'fab fa-facebook-f')); ?>"></i><span class="screen-reader-text"><?php esc_html_e( 'Facebook','tc-e-commerce-shop' );?></span></a>
                        <?php } ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_footer_twitter_url' ) != '' && get_theme_mod('tc_e_commerce_shop_footer_twitter_icon') != 'None') { ?>
                            <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_footer_twitter_url','' ) ); ?>"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_footer_twitter_icon', 'fab fa-twitter')); ?>"></i><span class="screen-reader-text"><?php esc_html_e( 'Twitter','tc-e-commerce-shop' );?></span></a>
                        <?php } ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_footer_instagram_url' ) != '' && get_theme_mod('tc_e_commerce_shop_footer_instagram_icon') != 'None') { ?>
                            <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_footer_instagram_url','' ) ); ?>"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_footer_instagram_icon','fab fa-instagram')); ?>"></i><span class="screen-reader-text"><?php esc_html_e( 'Instagram','tc-e-commerce-shop' );?></span></a>
                        <?php } ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_footer_youtube_url' ) != '' && get_theme_mod('tc_e_commerce_shop_footer_youtube_icon') != 'None') { ?>
                            <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_footer_youtube_url','' ) ); ?>"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_footer_youtube_icon','fab fa-youtube')); ?>"></i><span class="screen-reader-text"><?php esc_html_e( 'Youtube','tc-e-commerce-shop' );?></span></a>
                        <?php } ?>    
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_footer_linkedin_url' ) != '' && get_theme_mod('tc_e_commerce_shop_footer_linkedin_icon') != 'None') { ?>
                            <a target="_blank" href="<?php echo esc_url( get_theme_mod( 'tc_e_commerce_shop_footer_linkedin_url','' ) ); ?>"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_footer_linkedin_icon','fab fa-linkedin')); ?>"></i><span class="screen-reader-text"><?php esc_html_e( 'Linkedin','tc-e-commerce-shop' );?></span></a>
                        <?php } ?>                       
                    </div>
            <?php } ?> 

                <div class="clear"></div>  
            </div>
        </div>
    <?php } ?>  
</div>  
    <?php if( get_theme_mod( 'tc_e_commerce_shop_show_back_to_top',true) != '' || get_theme_mod('tc_e_commerce_shop_responsive_show_back_to_top',true) != ''){ ?>
        <?php $tc_e_commerce_shop_scroll_lay = get_theme_mod( 'tc_e_commerce_shop_back_to_top_alignment','Right');
        if($tc_e_commerce_shop_scroll_lay == 'Left'){ ?>
            <a href="#" class="scrollup left"><span><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_back_to_top_text',__('Back to Top', 'tc-e-commerce-shop' )) ); ?><?php if(get_theme_mod('tc_e_commerce_shop_back_to_top_icon') != 'None') {?><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_back_to_top_icon','fas fa-arrow-up')); ?> ms-2"></i><?php }?></span><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_back_to_top_text',__('Back to Top', 'tc-e-commerce-shop' )) ); ?></span></a>
        <?php }else if($tc_e_commerce_shop_scroll_lay == 'Center'){ ?>
            <a href="#" class="scrollup center"><span><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_back_to_top_text',__('Back to Top', 'tc-e-commerce-shop' )) ); ?><?php if(get_theme_mod('tc_e_commerce_shop_back_to_top_icon') != 'None') {?><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_back_to_top_icon','fas fa-arrow-up')); ?> ms-2"></i><?php }?></span><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_back_to_top_text',__('Back to Top', 'tc-e-commerce-shop' )) ); ?></span></a>
        <?php }else{ ?>
            <a href="#" class="scrollup right"><span><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_back_to_top_text',__('Back to Top', 'tc-e-commerce-shop' )) ); ?><?php if(get_theme_mod('tc_e_commerce_shop_back_to_top_icon') != 'None') {?><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_back_to_top_icon','fas fa-arrow-up')); ?> ms-2"></i><?php }?></span><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_back_to_top_text',__('Back to Top', 'tc-e-commerce-shop' )) ); ?></span></a>
        <?php }?>
    <?php }?>
    <?php wp_footer(); ?>
</footer>
 <?php if ( get_theme_mod('tc_e_commerce_shop_enable_custom_cursor', 'off') == '1' ) : ?>
    <div class="custom-cursor"></div>
<?php endif; ?>
</body>
</html>