<?php
/**
 * The template part for displaying single-post
 *
 * @package TC E-Commerce Shop
 * @subpackage tc_e_commerce_shop
 * @since TC E-Commerce Shop 1.0
 */
?>
<?php 
  $archive_year  = get_the_time('Y'); 
  $archive_month = get_the_time('m'); 
  $archive_day   = get_the_time('d'); 
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
    <div class="new-text">
        <div class="box-content">
            <h1><?php the_title(); ?></h1>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_single_post_date',true) != '' || get_theme_mod( 'tc_e_commerce_shop_single_post_comment_no',true) != '' || get_theme_mod( 'tc_e_commerce_shop_single_post_author',true) != '' || get_theme_mod( 'tc_e_commerce_shop_single_post_time_hide',true) != '') { ?>
                <div class="metabox py-1 mb-3">
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_single_post_date',true) != '') { ?>
                      <span class="entry-date me-2 px-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_single_postdate_icon', 'far fa-calendar-alt me-1')); ?>"></i><a href="<?php echo esc_url( get_day_link( $archive_year, $archive_month, $archive_day)); ?>"><?php echo esc_html( get_the_date() ); ?><span class="screen-reader-text"><?php echo esc_html( get_the_date() ); ?></span></a></span>
                    <?php }?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_single_post_author',true) != '') { ?>
                      <span class="entry-author me-2 px-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_single_postauthor_icon', 'fas fa-user me-1')); ?>"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span>
                    <?php }?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_single_post_comment_no',true) != '') { ?>
                      <span class="entry-comments me-2 px-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_single_postcomment_icon', 'fa fa-comments me-1')); ?>" aria-hidden="true"></i><?php comments_number( __('0 Comment', 'tc-e-commerce-shop'), __('0 Comments', 'tc-e-commerce-shop'), __('% Comments', 'tc-e-commerce-shop') ); ?> </span>
                    <?php }?>
                    <?php if( get_theme_mod( 'tc_e_commerce_shop_single_post_time_hide',true) != '') { ?>
                        <span class="entry-time me-2 px-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_single_posttime_icon', 'fas fa-clock me-1')); ?>"></i> <?php echo esc_html( get_the_time() ); ?></span>
                    <?php }?>
                </div>
            <?php }?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_feature_image',true) != '') { ?>
                <div class="feature-box single-post-img">
                    <?php the_post_thumbnail(); ?>
                </div>    
            <?php }?>
            <?php if( get_theme_mod('tc_e_commerce_shop_show_hide_single_post_categories',true) != ''){ ?>
                <div class="category mb-2">
                    <?php the_category(); ?>
                </div>
            <?php } ?>
            <div class="entry-content"><?php the_content();?></div>

            <?php if( get_theme_mod( 'tc_e_commerce_shop_tags',true) != '') { ?>
                <div class="tags"><?php the_tags(); ?></div>
            <?php }?>

            <?php
            
            if( get_theme_mod( 'tc_e_commerce_shop_comment',true) != '') {
                // If comments are open or we have at least one comment, load up the comment template
                if ( comments_open() || '0' != get_comments_number() )
                comments_template();
            }
            
            if ( is_singular( 'attachment' ) ) {
                // Parent post navigation.
                the_post_navigation( array(
                    'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'tc-e-commerce-shop' ),
                ) );
            }   elseif ( is_singular( 'post' ) ) {
                if( get_theme_mod( 'tc_e_commerce_shop_nav_links',true) != '') {
                    // Previous/next post navigation.
                    the_post_navigation( array(
                        'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html(get_theme_mod('tc_e_commerce_shop_next_text',__( 'Next Post', 'tc-e-commerce-shop' ))) . '<i class="fas fa-chevron-right ms-1"></i></span> ' .
                            '<span class="screen-reader-text">' . __( 'Next Post', 'tc-e-commerce-shop' ) . '</span> ' .
                            '',
                        'prev_text' => '<span class="meta-nav" aria-hidden="true"><i class="fas fa-chevron-left me-1"></i>' . esc_html(get_theme_mod('tc_e_commerce_shop_prev_text',__( 'Previous Post', 'tc-e-commerce-shop' ))) . '</span> ' .
                            '<span class="screen-reader-text">' . __( 'Previous Post', 'tc-e-commerce-shop' ) . '</span> ' .
                            '',
                    ) );
                }
            }?>
        </div>
    </div>
    <div class="clearfix"></div> 
</article>

<?php if (get_theme_mod('tc_e_commerce_shop_related_posts',true) != '') {
    get_template_part( 'template-parts/related-posts' );
}