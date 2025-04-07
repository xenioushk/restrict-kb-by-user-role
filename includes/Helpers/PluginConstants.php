<?php
namespace KAFWPB\Helpers;

/**
 * Class for plugin constants.
 *
 * @package KAFWPB
 */
class PluginConstants {

		/**
         * Get the relative path to the plugin root.
         *
         * @return string
         * @example wp-content/plugins/<plugin-name>/
         */
	public static function get_plugin_path(): string {
		return dirname( dirname( __DIR__ ) ) . '/';
	}


    /**
     * Get the plugin URL.
     *
     * @return string
     * @example http://appealwp.local/wp-content/plugins/<plugin-name>/
     */
	public static function get_plugin_url(): string {
		return plugin_dir_url( self::get_plugin_path() . KAFWPB_PLUGIN_ROOT_FILE );
	}

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::set_paths_constants();
		self::set_base_constants();
		self::set_assets_constants();
		self::set_updater_constants();
		self::set_product_info_constants();
	}

	/**
	 * Set the plugin base constants.
     *
	 * @example: $plugin_data = get_plugin_data( KAFWPB_PLUGIN_DIR . '/' . KAFWPB_PLUGIN_ROOT_FILE );
	 * echo '<pre>';
	 * print_r( $plugin_data );
	 * echo '</pre>';
	 * @example_param: Name,PluginURI,Description,Author,Version,AuthorURI,RequiresAtLeast,TestedUpTo,TextDomain,DomainPath
	 */
	private static function set_base_constants() {

		$plugin_data = get_plugin_data( KAFWPB_PLUGIN_DIR . '/' . KAFWPB_PLUGIN_ROOT_FILE );

		define( 'KAFWPB_PLUGIN_VERSION', $plugin_data['Version'] ?? '1.0.0' );
		define( 'KAFWPB_PLUGIN_TITLE', $plugin_data['Name'] ?? 'KB Addon For WPBakery Page Builder' );
		define( 'KAFWPB_TRANSLATION_DIR', $plugin_data['DomainPath'] ?? '/languages/' );
		define( 'KAFWPB_TEXT_DOMAIN', $plugin_data['TextDomain'] ?? '' );

		define( 'KAFWPB_PLUGIN_FOLDER', 'kb-addon-for-visual-composer' );
		define( 'KAFWPB_PLUGIN_CURRENT_VERSION', KAFWPB_PLUGIN_VERSION );
		define( 'KAFWPB_PLUGIN_POST_TYPE', 'bwl_kb' );
		define( 'KAFWPB_PLUGIN_TAXONOMY_CAT', 'bkb_category' );
		define( 'KAFWPB_PLUGIN_TAXONOMY_TAGS', 'bkb_tags' );
	}

	/**
	 * Set the plugin paths constants.
	 */
	private static function set_paths_constants() {
		define( 'KAFWPB_PLUGIN_ROOT_FILE', 'kb-addon-for-visual-composer.php' );
		define( 'KAFWPB_PLUGIN_DIR', self::get_plugin_path() );
		define( 'KAFWPB_PLUGIN_FILE_PATH', KAFWPB_PLUGIN_DIR );
		define( 'KAFWPB_PLUGIN_URL', self::get_plugin_url() );
	}

	/**
	 * Set the plugin assets constants.
	 */
	private static function set_assets_constants() {
		define( 'KAFWPB_PLUGIN_STYLES_ASSETS_DIR', KAFWPB_PLUGIN_URL . 'assets/styles/' );
		define( 'KAFWPB_PLUGIN_SCRIPTS_ASSETS_DIR', KAFWPB_PLUGIN_URL . 'assets/scripts/' );
		define( 'KAFWPB_PLUGIN_LIBS_DIR', KAFWPB_PLUGIN_URL . 'libs/' );
	}

	/**
	 * Set the updater constants.
	 */
	private static function set_updater_constants() {

		// Only change the slug.
		$slug        = 'bkbm/notifier_bkbm_kafvc.php';
		$updater_url = "https://projects.bluewindlab.net/wpplugin/zipped/plugins/{$slug}";

		define( 'KAFWPB_PLUGIN_UPDATER_URL', $updater_url ); // phpcs:ignore
		define( 'KAFWPB_PLUGIN_UPDATER_SLUG', KAFWPB_PLUGIN_FOLDER . '/' . KAFWPB_PLUGIN_ROOT_FILE ); // phpcs:ignore
		define( 'KAFWPB_PLUGIN_PATH', KAFWPB_PLUGIN_DIR );
	}

	/**
	 * Set the product info constants.
	 */
	private static function set_product_info_constants() {
		define( 'KAFWPB_PRODUCT_ID', '14935093' ); // Plugin codecanyon/themeforest Id.
		define( 'KAFWPB_PRODUCT_INSTALLATION_TAG', 'bkbm_kavc_installation_' . str_replace( '.', '_', KAFWPB_PLUGIN_VERSION ) );
	}
}
