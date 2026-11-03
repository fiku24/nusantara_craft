<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package TC E-Commerce Shop
 */
?>
<div id="sidebar" class="wow zoomIn">
    <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
        <aside role="complementary" aria-label="sidebar-1" id="archives" class="widget">
            <h3 class="widget-title"><?php esc_html_e( 'Archives', 'tc-e-commerce-shop' ); ?></h3>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>
        <aside role="complementary" aria-label="sidebar-2" id="meta" class="widget">
            <h3 class="widget-title"><?php esc_html_e( 'Meta', 'tc-e-commerce-shop' ); ?></h3>
            <ul>
                <?php wp_register(); ?>
                <li><?php wp_loginout(); ?></li>
                <?php wp_meta(); ?>
            </ul>
        </aside>
        <aside id="search" class="widget" role="complementary" aria-label="firstsidebar">
            <h3 class="widget-title"><?php esc_html_e( 'Search', 'tc-e-commerce-shop' ); ?></h3>
            <?php get_search_form(); ?>
        </aside>
    <?php endif; // end sidebar widget area ?>	
</div>
