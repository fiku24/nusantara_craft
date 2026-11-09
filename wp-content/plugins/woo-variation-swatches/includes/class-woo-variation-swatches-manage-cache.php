<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Woo_Variation_Swatches_Manage_Cache' ) ) {
	class Woo_Variation_Swatches_Manage_Cache {

		// Group flush already done in this request.
		protected bool $is_already_flushed = false;

		protected array $cache_keys_deleted = array();

		protected function __construct() {
			$this->includes();
			$this->hooks();
			$this->init();
			do_action( 'woo_variation_swatches_manage_cache_loaded', $this );
		}

		public static function instance(): Woo_Variation_Swatches_Manage_Cache {
			static $instance;
			if ( null === $instance ) {
				$instance = new self();
			}

			return $instance;
		}

		protected function includes() {
		}

		protected function hooks() {

			// Attributes
			add_action( 'woocommerce_attribute_added', array( $this, 'clear_cache_on_attribute_added' ), 10, 2 );
			add_action( 'woocommerce_attribute_updated', array( $this, 'clear_cache_on_attribute_updated' ), 10, 3 );
			add_action( 'woocommerce_attribute_deleted', array( $this, 'clear_cache_on_attribute_deleted' ), 10, 3 );

			// Products
			add_action( 'woocommerce_save_product_variation', array( $this, 'clear_cache_on_product_modify' ) );
			add_action( 'woocommerce_update_product_variation', array( $this, 'clear_cache_on_product_modify' ) );
			add_action( 'woocommerce_before_delete_product_variation', array(
				$this,
				'clear_cache_on_product_modify'
			) );
			add_action( 'woocommerce_trash_product_variation', array( $this, 'clear_cache_on_product_modify' ) );

			// WooCommerce -> Status -> Tools -> Clear transients
			add_action( 'woocommerce_delete_product_transients', array(
				$this,
				'clear_cache_on_delete_product_transients'
			) );

			// Settings saved.
			add_action( 'getwooplugins_settings_saved', array( $this, 'clear_cache_on_settings_modify' ) );
			// Settings Reset.
			add_action( 'getwooplugins_after_delete_options', array( $this, 'clear_cache_on_settings_modify' ) );

			// Product label settings

			add_action( 'woo_variation_swatches_product_settings_update', array(
				$this,
				'clear_cache_on_product_settings_modify'
			) );
			add_action( 'woo_variation_swatches_product_settings_delete', array(
				$this,
				'clear_cache_on_product_settings_modify'
			) );

			add_action( 'admin_bar_menu', array( $this, 'add_admin_menu' ), 999 );
		}

		public function add_admin_menu( $wp_admin_bar ) {

			if ( ! is_admin() && current_user_can( 'manage_woocommerce' ) && ( is_singular( 'product' ) || is_shop() ) ) {

				$create_nonce = wp_create_nonce( 'woo_variation_swatches_clear_transient' );

				$url = remove_query_arg( array(
					'variation_id',
					'remove_item',
					'add-to-cart',
					'added-to-cart',
				), add_query_arg( 'woo_variation_swatches_clear_transient', $create_nonce ) );

				$node = array(
					'id'    => 'woo-variation-swatches-clear-transient',
					'title' => esc_html__( 'Clear swatches transient', 'woo-variation-swatches' ),
					'href'  => esc_url( $url ),

				);
				$wp_admin_bar->add_menu( $node );
			}
		}

		protected function init(): void {
			if ( function_exists( 'wp_cache_add_global_groups' ) ) {
				wp_cache_add_global_groups( array( $this->get_cache_group_name() ) );
			}
		}

		private function is_polylang(): bool {
			return function_exists( 'pll_default_language' );
		}

		public function get_cache_group_name(): string {
			return 'woo_variation_swatches';
		}

		public function get_cache_key( $key ): string {

			$suffix = '';

			// Language
			$default_language = apply_filters( 'wpml_default_language', null );
			$current_language = apply_filters( 'wpml_current_language', null );

			if ( $this->is_polylang() ) {
				$default_language = pll_default_language( 'locale' );
				$current_language = pll_current_language( 'locale' );
			}

			if ( $current_language !== $default_language ) {
				$suffix .= sprintf( '_%s', $current_language );
			}

			// Currency
			$default_currency = get_option( 'woocommerce_currency' );
			$current_currency = get_woocommerce_currency();

			if ( $current_currency !== $default_currency ) {
				$suffix .= sprintf( '_%s', $current_currency );
			}

			$generated_cache_key = sprintf( '%s%s', $key, $suffix );

			if ( ! is_admin() && current_user_can( 'manage_options' ) && isset( $_GET['woo_variation_swatches_clear_transient'] ) ) {

				check_admin_referer( 'woo_variation_swatches_clear_transient', 'woo_variation_swatches_clear_transient' );

				if ( ! in_array( $generated_cache_key, $this->cache_keys_deleted ) ) {
					delete_transient( $generated_cache_key );
					wp_cache_delete( $generated_cache_key, $this->get_cache_group_name() );
					woo_variation_swatches()->log( 'Delete cache by admin bar menu', array(
						'key' => $generated_cache_key,
					) );

					$this->cache_keys_deleted[] = $generated_cache_key;

					do_action( 'woo_variation_swatches_clear_transient', $generated_cache_key, $this->get_cache_group_name() );
				}
			}

			return apply_filters( 'woo_variation_swatches_get_cache_key', $generated_cache_key, $key, $suffix );
		}

		// Clear Settings Cache
		public function clear_cache_on_settings_modify(): void {
			do_action( 'litespeed_purge_all', 'Woo Variation Swatches: purge all' );
			wp_cache_delete( 'global_settings', $this->get_cache_group_name() );
			woo_variation_swatches()->log( 'Clear global settings cache' );
		}

		// Clear transients Cache
		public function clear_cache_on_delete_product_transients( $post_id ): void {

			// WooCommerce -> Status -> Tools -> Clear transients
			$this->clear_cache_by_group();
		}

		// Clear Attributes Cache
		public function clear_cache_on_attribute_added( $id, $data ): void {

			$transient_key       = $this->get_cache_key( sprintf( 'wvs_transient_taxonomy_name__%s', wc_attribute_taxonomy_name( $data['attribute_name'] ) ) );
			$transient_key_by_id = $this->get_cache_key( sprintf( 'wvs_transient_taxonomy_id__%s', $id ) );

			delete_transient( $transient_key );
			delete_transient( $transient_key_by_id );
			woo_variation_swatches()->log( 'delete_transient cache after attribute added', array(
				'name'    => wc_attribute_taxonomy_name( $data['attribute_name'] ),
				'id'    => $id,
			) );
		}

		public function clear_cache_on_attribute_updated( $id, $data, $old_slug ): void {

			$transient_key       = $this->get_cache_key( sprintf( 'wvs_transient_axonomy_name__%s', wc_attribute_taxonomy_name( $data['attribute_name'] ) ) );
			$transient_key_old   = $this->get_cache_key( sprintf( 'wvs_transient_taxonomy_name__%s', wc_attribute_taxonomy_name( $old_slug ) ) );
			$transient_key_by_id = $this->get_cache_key( sprintf( 'wvs_transient_taxonomy_id__%s', $id ) );

			delete_transient( $transient_key );
			delete_transient( $transient_key_old );
			delete_transient( $transient_key_by_id );

			woo_variation_swatches()->log( 'delete_transient cache after attribute updated', array(
				'old'    => wc_attribute_taxonomy_name( $old_slug ),
				'name'    => wc_attribute_taxonomy_name( $data['attribute_name'] ),
				'id'    => $id,
			) );
		}

		public function clear_cache_on_attribute_deleted( $id, $name, $taxonomy ): void {

			$transient_key       = $this->get_cache_key( sprintf( 'wvs_transient_taxonomy_name__%s', $taxonomy ) );
			$transient_key_by_id = $this->get_cache_key( sprintf( 'wvs_transient_taxonomy_id__%s', $id ) );

			delete_transient( $transient_key );
			delete_transient( $transient_key_by_id );

			woo_variation_swatches()->log( 'delete_transient cache after attribute updated', array(
				'name'    => $taxonomy,
				'id'    => $id,
			) );
		}

		// Clear Product Cache

		public function clear_cache_on_product_modify( $variation_id ) {
			$this->clear_cache_by_group();
		}

		/**
		 * Cache key with product version. Bumping the version invalidates
		 * every language and currency variant of the key at once.
		 *
		 * @param string $key Key template, Ex: 'variation_images'.
		 * @param int $product_id Product id.
		 *
		 * @return string
		 */
		public function get_product_cache_key( string $key, int $product_id ): string {
			// $key = sprintf( '%s_product_%s__v%s', $key, $product_id, $this->get_product_version( $product_id ) );
			$key = sprintf( '%s__p%s', $key, $product_id );

			return $this->get_cache_key( $key );
		}

		// Expiration for persistent cache, so orphaned versioned keys do not stay forever.
		public function get_cache_expiration(): int {
			return absint( apply_filters( 'woo_variation_swatches_cache_expiration', DAY_IN_SECONDS ) );
		}

		public function clear_cache_on_product_settings_modify( int $product_id ): void {
			$this->clear_cache_by_group();
		}

		public function get_last_changed(): string {
			return wp_cache_get_last_changed( $this->get_cache_group_name() );
		}

		public function delete_last_changed(): void {
			wp_cache_delete( 'last_changed', $this->get_cache_group_name() );
		}

		public function update_last_changed(): void {
			// wp_cache_set_last_changed() added in WP 6.3.
			if ( function_exists( 'wp_cache_set_last_changed' ) ) {
				wp_cache_set_last_changed( $this->get_cache_group_name() );
			} else {
				wp_cache_set( 'last_changed', microtime(), $this->get_cache_group_name() );
			}
		}

		/**
		 * Flush cache group, at most once per request. Use only for global changes.
		 *
		 * @return bool True if group flushed or already flushed in this request.
		 */
		public function clear_cache_by_group(): bool {
			if ( $this->is_already_flushed ) {
				return true;
			}

			woo_variation_swatches()->log( 'Trying to clear cache by Group' );

			if ( function_exists( 'wp_cache_flush_group' ) && method_exists( 'WP_Object_Cache', 'flush_group' ) ) {
				if ( wp_cache_supports( 'flush_group' ) ) {
					wp_cache_flush_group( $this->get_cache_group_name() );
					$this->is_already_flushed = true;

					woo_variation_swatches()->log( 'All Cache Cleared by Group' );
				}
			}

			return $this->is_already_flushed;
		}
	}
}

