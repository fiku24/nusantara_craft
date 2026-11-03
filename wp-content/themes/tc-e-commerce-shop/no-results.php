<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.WordPress.org/Template_Hierarchy
 *
 * @package TC E-Commerce Shop
 */
?>

<header role="banner">
	<h2 class="entry-title"><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_no_result_title',__('Nothing Found', 'tc-e-commerce-shop' )) ); ?></h2>
</header>

<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( esc_html__( 'Ready to publish your first post? Get started here.', 'tc-e-commerce-shop' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
	<?php elseif ( is_search() ) : ?>
		<p><?php echo esc_html( get_theme_mod('tc_e_commerce_shop_no_result_text',__('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'tc-e-commerce-shop' )) ); ?></p><br />
		<?php if (get_theme_mod('tc_e_commerce_shop_show_search_form',true) != '') {
			get_search_form();
		}?>
	<?php else : ?>
		<p><?php esc_html_e( 'Dont worry&hellip it happens to the best of us.', 'tc-e-commerce-shop' ); ?></p><br />
		<div class="read-moresec">
			<a href="<?php echo esc_url( home_url() ); ?>" class="button hvr-sweep-to-right my-3 py-2 px-4"><?php esc_html_e( 'Back to Home Page', 'tc-e-commerce-shop' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Back to Home Page','tc-e-commerce-shop' );?></span></a>
		</div>
<?php endif; ?>