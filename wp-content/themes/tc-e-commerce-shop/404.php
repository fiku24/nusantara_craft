<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package TC E-Commerce Shop
 */
get_header(); ?>

<main id="main" role="main" class="content-aa">
	<div class="container">
        <div class="page-content text-center py-5">
			<?php if(get_theme_mod('tc_e_commerce_shop_404_title','404 Not Found')){ ?>	
				<h1><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_404_title',__('404 Not Found', 'tc-e-commerce-shop' )) ); ?></h1>
			<?php }?>
			<?php if(get_theme_mod('tc_e_commerce_shop_404_text','Looks like you have taken a wrong turn. Dont worry it happens to the best of us.')){ ?>
				<p class="text-404"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_404_text',__('Looks like you have taken a wrong turn. Dont worry it happens to the best of us.', 'tc-e-commerce-shop' )) ); ?></p>
			<?php }?>
			<?php if(get_theme_mod('tc_e_commerce_shop_404_button_text','Back to Home Page')){ ?>
				<div class="read-moresec">
	           		<a href="<?php echo esc_url( home_url() ); ?>" class="button hvr-sweep-to-right my-3 py-2 px-4"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_404_button_text',__('Back to Home Page', 'tc-e-commerce-shop' )) ); ?><span class="screen-reader-text"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_404_button_text',__('Back to Home Page', 'tc-e-commerce-shop' )) ); ?></span></a>
				</div>
				<div class="clearfix"></div>
			<?php }?>
        </div>
	</div>
</main>

<?php get_footer(); ?>