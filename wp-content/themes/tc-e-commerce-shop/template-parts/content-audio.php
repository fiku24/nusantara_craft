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
  $content = apply_filters( 'the_content', get_the_content() );
  $audio = false;
  // Only get audio from the content if a playlist isn't present.
  if ( false === strpos( $content, 'wp-playlist-script' ) ) {
    $audio = get_media_embedded_in_content( $content, array( 'audio' ) );
  }
?>
<?php 
  $archive_year  = get_the_time('Y'); 
  $archive_month = get_the_time('m'); 
  $archive_day   = get_the_time('d'); 
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('inner-service'); ?>>
  <div class="postbox wow zoomInLeft delay-2000 p-3 mb-4">
    <?php
      if ( ! is_single() ) {
        // If not a single post, highlight the audio file.
        if ( ! empty( $audio ) ) {
          foreach ( $audio as $audio_html ) {
            echo '<div class="entry-audio">';
              echo $audio_html;
            echo '</div><!-- .entry-audio -->';
          }
        };
      };
    ?>
    <div class="new-text">           
      <div class="box-content">
        <div class="tc-category">
          <?php the_category(); ?>
        </div>

        <h2 class="pt-0"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?><span class="screen-reader-text"><?php the_title(); ?></span></a></h2>
        <?php if( get_theme_mod( 'tc_e_commerce_shop_date_hide',true) != '' || get_theme_mod( 'tc_e_commerce_shop_comment_hide',true) != '' || get_theme_mod( 'tc_e_commerce_shop_author_hide',true) != '' || get_theme_mod( 'tc_e_commerce_shop_time_hide',true) != '') { ?>
          <div class="metabox py-1 mb-2">
            <?php if( get_theme_mod( 'tc_e_commerce_shop_date_hide',true) != '') { ?>
              <span class="entry-date me-2"><i class="far fa-calendar-alt me-1"></i><a href="<?php echo esc_url( get_day_link( $archive_year, $archive_month, $archive_day)); ?>"><?php echo esc_html( get_the_date() ); ?><span class="screen-reader-text"><?php echo esc_html( get_the_date() ); ?></span></a></span>
            <?php }?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_author_hide',true) != '') { ?>
              <span class="entry-author me-2"><i class="fas fa-user me-1"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' )) ); ?>"><?php the_author(); ?><span class="screen-reader-text"><?php the_author(); ?></span></a></span>
            <?php }?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_comment_hide',true) != '') { ?>
              <span class="entry-comments me-2"><i class="fa fa-comments me-1" aria-hidden="true"></i><?php comments_number( __('0 Comment', 'tc-e-commerce-shop'), __('0 Comments', 'tc-e-commerce-shop'), __('% Comments', 'tc-e-commerce-shop') ); ?> </span>
            <?php }?>
            <?php if( get_theme_mod( 'tc_e_commerce_shop_time_hide',true) != '') { ?>
              <span class="entry-time me-2"><i class="fas fa-clock me-1"></i> <?php echo esc_html( get_the_time() ); ?></span>
            <?php }?>
          </div>
        <?php }?>
        <?php if(get_theme_mod('tc_e_commerce_shop_post_content') == 'Full Content'){ ?>
          <?php the_content(); ?>
        <?php }
        if(get_theme_mod('tc_e_commerce_shop_post_content', 'Excerpt Content') == 'Excerpt Content'){ ?>
          <?php if(get_the_excerpt()) { ?>
            <div class="entry-content"><p class="m-0"><?php $tc_e_commerce_shop_excerpt = get_the_excerpt(); echo esc_html( tc_e_commerce_shop_string_limit_words( $tc_e_commerce_shop_excerpt, esc_attr(get_theme_mod('tc_e_commerce_shop_post_excerpt_length','20')))); ?><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_button_excerpt_suffix','[...]') ); ?></p></div>
          <?php }?>
        <?php }?>
        <?php if ( get_theme_mod('tc_e_commerce_shop_post_button_text','Read Full') != '' ) {?>
          <a href="<?php the_permalink(); ?>" class="blogbutton-small hvr-sweep-to-right mt-2"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_post_button_text',__( 'Read Full','tc-e-commerce-shop' )) ); ?><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_post_button_text',__( 'Read Full','tc-e-commerce-shop' )) ); ?></span></a>
        <?php }?>
      </div>
    </div>
    <div class="clearfix"></div> 
  </div> 
</article>