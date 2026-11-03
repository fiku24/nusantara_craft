<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package TC E-Commerce Shop
 */
get_header(); ?>

<main id="main" role="main" class="middle-align py-4">
    <div class="container">
        <?php
        $tc_e_commerce_shop_left_right = get_theme_mod( 'tc_e_commerce_shop_theme_options','Right Sidebar');
        if($tc_e_commerce_shop_left_right == 'Left Sidebar'){ ?>
            <div class="row">
                <div class="col-lg-4 col-md-4"><?php get_sidebar(); ?></div>
                <div class="col-lg-8 col-md-8">
                    <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <?php if ( have_posts() ) :
                    /* Start the Loop */
                      
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', get_post_format() ); 
                      
                        endwhile;
                        wp_reset_postdata();
                        else :

                            get_template_part( 'no-results' ); 

                        endif; 
                    ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                </div>
                <div class="clearfix"></div>
            </div>
        <?php }else if($tc_e_commerce_shop_left_right == 'Right Sidebar'){ ?>
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <?php if ( have_posts() ) :
                        /* Start the Loop */
                          
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', get_post_format() ); 
                          
                        endwhile;
                        wp_reset_postdata();
                        else :

                            get_template_part( 'no-results' ); 

                        endif; 
                    ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                </div>
                <div class="col-lg-4 col-md-4"><?php get_sidebar(); ?></div>
            </div>
        <?php }else if($tc_e_commerce_shop_left_right == 'One Column'){ ?>
            <div class="hospi-blog">
                <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                        <div class="navigation my-3">
                            <?php tc_e_commerce_shop_post_navigation();?>
                            <div class="clearfix"></div>
                        </div>
                    <?php }?>
                <?php }?>
                <?php if ( have_posts() ) :
                    /* Start the Loop */
                      
                    while ( have_posts() ) : the_post();

                        get_template_part( 'template-parts/content', get_post_format() ); 
                      
                    endwhile;
                    wp_reset_postdata();
                    else :

                        get_template_part( 'no-results' ); 

                    endif; 
                ?>
                <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                        <div class="navigation my-3">
                            <?php tc_e_commerce_shop_post_navigation();?>
                            <div class="clearfix"></div>
                        </div>
                    <?php }?>
                <?php }?>
            </div>
        <?php }else if($tc_e_commerce_shop_left_right == 'Three Columns'){ ?>
            <div class="row">
                <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
                <div class="col-lg-6 col-md-6">
                    <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <?php if ( have_posts() ) :
                        /* Start the Loop */
                          
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', get_post_format() ); 
                          
                        endwhile;
                        wp_reset_postdata();
                        else :

                            get_template_part( 'no-results' ); 

                        endif; 
                    ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                </div>
                <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar( 'sidebar-2' ); ?></div>
            </div>
        <?php }else if($tc_e_commerce_shop_left_right == 'Four Columns'){ ?>
            <div class="row">
                <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
                <div class="col-lg-3 col-md-3">
                    <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <?php if ( have_posts() ) :
                        /* Start the Loop */
                          
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', get_post_format() ); 
                          
                        endwhile;
                        wp_reset_postdata();
                        else :

                            get_template_part( 'no-results' ); 

                        endif; 
                    ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                </div>
                <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar( 'sidebar-2' ); ?></div>
                <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar( 'sidebar-3' ); ?></div>
            </div>
        <?php }else if($tc_e_commerce_shop_left_right == 'Grid Layout'){ ?>
            <div class="row">
                <div class="row col-lg-9 col-md-9">
                    <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <?php if ( have_posts() ) :
                        /* Start the Loop */
                          
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/grid-layout' ); 
                          
                        endwhile;
                        wp_reset_postdata();
                        else :

                            get_template_part( 'no-results' ); 

                        endif; 
                    ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                </div>
                <div id="sidebar" class="col-lg-3 col-md-3"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
            </div>
        <?php } else{?>
            <div class="row">
                <div class="col-lg-8 col-md-8">
                    <h1 class="search-title"><?php /* translators: %s: search term */ printf(esc_html('Search Results for: %s','tc-e-commerce-shop'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?></h1>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'top' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                    <?php if ( have_posts() ) :
                        /* Start the Loop */
                          
                        while ( have_posts() ) : the_post();

                            get_template_part( 'template-parts/content', get_post_format() ); 
                          
                        endwhile;
                        wp_reset_postdata();
                        else :

                            get_template_part( 'no-results' ); 

                        endif; 
                    ?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_navigation_hide',true) != '') { ?>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'bottom' || get_theme_mod( 'tc_e_commerce_shop_post_navigation_position','bottom') == 'both')  { ?>
                            <div class="navigation my-3">
                                <?php tc_e_commerce_shop_post_navigation();?>
                                <div class="clearfix"></div>
                            </div>
                        <?php }?>
                    <?php }?>
                </div>
                <div class="col-lg-4 col-md-4"><?php get_sidebar(); ?></div>
            </div>
        <?php } ?>
    </div>
</main>

<?php get_footer(); ?>