<?php
/**
 * The template for displaying home page.
 *
 * Template Name: Custom Home Page
 *
 * @package TC E-Commerce Shop
 */
get_header(); ?>

<main id="main" role="main">
  <?php do_action( 'tc_e_commerce_shop_above_slider' ); ?>
  
  <?php if( get_theme_mod('tc_e_commerce_shop_slider_hide', false) != '' || get_theme_mod( 'tc_e_commerce_shop_mobile_media_slider', true) != ''){ ?>
    <section id="slider">
      <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="<?php echo esc_attr(get_theme_mod( 'tc_e_commerce_shop_slider_speed',3000)) ?>"> 
        <?php $tc_e_commerce_shop_slider_pages = array();
          for ( $count = 1; $count <= 4; $count++ ) {
            $mod = intval( get_theme_mod( 'tc_e_commerce_shop_slidersettings_page' . $count ));
            if ( 'page-none-selected' != $mod ) {
              $tc_e_commerce_shop_slider_pages[] = $mod;
            }
          }
          if( !empty($tc_e_commerce_shop_slider_pages) ) :
            $args = array(
              'post_type' => 'page',
              'post__in' => $tc_e_commerce_shop_slider_pages,
              'orderby' => 'post__in'
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
              $i = 1;
        ?>
        <div class="carousel-inner" role="listbox">
          <?php  while ( $query->have_posts() ) : $query->the_post(); ?>
            <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
              <a href="<?php echo esc_url( get_permalink() );?>"><?php if(has_post_thumbnail()){
                  the_post_thumbnail();
                } else{?>
                  <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/banner-image.png" alt="" />
                <?php } ?>
              </a>
              <div class="carousel-caption wow zoomIn">
                <div class="inner_carousel">
                  <?php if( get_theme_mod('tc_e_commerce_shop_slider_title',true) != ''){ ?>
                    <h1><a href="<?php echo esc_url( get_permalink() );?>"><?php the_title(); ?><span class="screen-reader-text"><?php the_title(); ?></span></a></h1>
                  <?php }?>
                  <?php if ( get_theme_mod('tc_e_commerce_shop_slider_button_text','LEARN MORE') != '' && get_theme_mod('tc_e_commerce_shop_slider_button',true) != ''|| get_theme_mod('tc_e_commerce_shop_slider_button_responsive',true) != ''|| get_theme_mod('tc_e_commerce_shop_slider_button_link') != '') {?>
                    <div class="more-btn hvr-sweep-to-right">              
                      <a href="<?php echo esc_url(get_theme_mod('tc_e_commerce_shop_slider_button_link')!= '') ? esc_url(get_theme_mod('tc_e_commerce_shop_slider_button_link')) : esc_url(get_permalink()); ?>"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_slider_button_text',__('LEARN MORE', 'tc-e-commerce-shop')) ); ?><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_slider_button_text',__('LEARN MORE', 'tc-e-commerce-shop')) ); ?></span></a>
                    </div>
                  <?php }?>
                </div>
              </div>
            </div>
          <?php $i++; endwhile; 
          wp_reset_postdata();?>
        </div>
        <?php else : ?>
            <div class="no-postfound"></div>
          <?php endif;
        endif;?>
        <?php if( get_theme_mod('tc_e_commerce_shop_slider_arrow',true) != ''){ ?>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon px-2" aria-hidden="true"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_slider_prev_icon','fas fa-chevron-left')); ?>"></i></span><span class="screen-reader-text"><?php esc_html_e( 'Previous','tc-e-commerce-shop' );?></span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon px-2" aria-hidden="true"><i class="<?php echo esc_attr(get_theme_mod('tc_e_commerce_shop_slider_next_icon','fas fa-chevron-right')); ?>"></i></span><span class="screen-reader-text"><?php esc_html_e( 'Next','tc-e-commerce-shop' );?></span>
        </a>
        <?php }?>
      </div>  
      <div class="clearfix"></div>
    </section>
  <?php }?>

  <?php do_action( 'tc_e_commerce_shop_below_slider' ); ?>

  <?php if( get_theme_mod('tc_e_commerce_shop_sec1_title') != ''){ ?>
    <section id="our-products" class="my-5">
    	<div class="container wow bounceInUp">
        <?php if( get_theme_mod('tc_e_commerce_shop_sec1_title') != ''){ ?>     
          <strong><?php echo esc_html(get_theme_mod('tc_e_commerce_shop_sec1_title','')); ?></strong>
          <hr class="product">
        <?php }?>
  			<?php $tc_e_commerce_shop_slider_pages = array();
  				$mod = intval( get_theme_mod( 'tc_e_commerce_shop_servicesettings-page-'));
  				if ( 'page-none-selected' != $mod ) {
  				  $tc_e_commerce_shop_slider_pages[] = $mod;
  				}
  			if( !empty($tc_e_commerce_shop_slider_pages) ) :
  			  $args = array(
  			    'post_type' => 'page',
  			    'post__in' => $tc_e_commerce_shop_slider_pages,
  			    'orderby' => 'post__in'
  			  );
  			  $query = new WP_Query( $args );
  			  if ( $query->have_posts() ) :
  					while ( $query->have_posts() ) : $query->the_post(); ?>
  				    <div class="box-image">
  			        <p><?php the_content(); ?></p>
  			        <div class="clearfix"></div>
  				    </div>
  					<?php endwhile; ?>
  			  <?php else : ?>
			      <div class="no-postfound"></div>
  			  <?php endif;
  			endif;
  		  wp_reset_postdata();?>
  	    <div class="clearfix"></div>		
    	</div>
    </section>
  <?php }?>

  <?php do_action( 'tc_e_commerce_shop_below_product_section' ); ?>

  <div id="content-ma">
    <div class="container">
    	<?php while ( have_posts() ) : the_post(); ?>
      	<?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>