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
<?php 
  $tc_e_commerce_shop_grid_columns = get_theme_mod('tc_e_commerce_shop_grid_columns', '3');
  if ($tc_e_commerce_shop_grid_columns == '3') {
    $tc_e_commerce_shop_column_class = 'col-lg-4 col-md-4';
  } elseif ($tc_e_commerce_shop_grid_columns == '4') {
    $tc_e_commerce_shop_column_class = 'col-lg-3 col-md-6';
  } 
?>
<div class="<?php echo esc_attr($tc_e_commerce_shop_column_class); ?>">
    <article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
        <div class="grid-post-box">
            <div class="postbox p-3 mb-4">      
                <?php if(has_post_thumbnail() && get_theme_mod( 'tc_e_commerce_shop_grid_post_image_hide',true) != '') { ?>
                    <div class="box-image">
                        <?php the_post_thumbnail();  ?>
                    </div>
                <?php }?>
                <div class="new-text">
                    <div class="box-content">
                        <h2 class="pt-0"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
                        <?php if( get_theme_mod( 'tc_e_commerce_shop_grid_post_date_hide', true) != '' || get_theme_mod( 'tc_e_commerce_shop_grid_post_comment_hide', true) != '' || get_theme_mod( 'tc_e_commerce_shop_grid_post_author_hide', true) != '' || get_theme_mod( 'tc_e_commerce_shop_grid_post_time_hide', true) != '') { ?>
                            <div class="metabox py-1 px-2 mb-3">
                            <?php if( get_theme_mod( 'tc_e_commerce_shop_grid_post_date_hide', true) != '') { ?>
                                <span class="entry-date me-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_grid_post_date_icon', 'far fa-calendar-alt me-1')); ?>"></i><?php echo esc_html( get_the_date() ); ?></span>
                            <?php } ?>

                            <?php if( get_theme_mod( 'tc_e_commerce_shop_grid_post_author_hide', true) != '') { ?>
                                <span class="entry-author me-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_grid_post_author_icon', 'fas fa-user me-1')); ?>"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span>
                            <?php } ?>

                            <?php if( get_theme_mod( 'tc_e_commerce_shop_grid_post_comment_hide', true) != '') { ?>
                                <span class="entry-comments me-2"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_grid_post_comment_icon', 'fas fa-comments me-1')); ?>"></i> <?php comments_number( __('0 Comments','tc-e-commerce-shop'), __('1 Comment','tc-e-commerce-shop'), __('% Comments','tc-e-commerce-shop') ); ?></span>
                            <?php } ?>

                            <?php if( get_theme_mod( 'tc_e_commerce_shop_grid_post_time_hide', true) != '') { ?>
                                <span class="entry-time"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_grid_post_time_icon', 'fas fa-clock me-1')); ?>"></i> <?php echo esc_html( get_the_time() ); ?></span>
                            <?php } ?>
                            </div>
                        <?php } ?> 
                        <?php if(get_theme_mod('tc_e_commerce_shop_grid_post_content') == 'Full Content'){ ?>
                            <?php the_content(); ?>
                        <?php }
                        if(get_theme_mod('tc_e_commerce_shop_grid_post_content', 'Excerpt Content') == 'Excerpt Content'){ ?>
                            <?php if(get_the_excerpt()) { ?>
                                <div class="entry-content"><p class="m-0"><?php $tc_e_commerce_shop_excerpt = get_the_excerpt(); echo esc_html( tc_e_commerce_shop_string_limit_words( $tc_e_commerce_shop_excerpt, esc_attr(get_theme_mod('tc_e_commerce_shop_grid_post_excerpt_length','20')))); ?><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_grid_post_button_excerpt_suffix','[...]') ); ?></p></div>
                            <?php }?>
                        <?php } ?> 
                        <?php if ( get_theme_mod('tc_e_commerce_shop_post_button_text','Read Full') != '' ) {?>
                            <a href="<?php the_permalink(); ?>" class="blogbutton-small hvr-sweep-to-right mt-2"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_post_button_text',__( 'Read Full','tc-e-commerce-shop' )) ); ?><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_post_button_text',__( 'Read Full','tc-e-commerce-shop' )) ); ?></span></a>
                        <?php }?>
                    </div>
                </div>
                <div class="clearfix"></div> 
            </div>
        </div> 
    </article>
</div>