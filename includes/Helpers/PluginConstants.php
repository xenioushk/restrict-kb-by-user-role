<?php
namespace BKBRKB\Helpers;

/**
 * Class for plugin constants.
 *
 * @package BKBRKB
 */
class PluginConstants {

		/**
         * Static property to hold plugin options.
         *
         * @var array
         */
	public static $plugin_options = [];

	/**
	 * Initialize the plugin options.
	 */
	public static function init() {

		self::$plugin_options = get_option( 'bkb_options' );
	}

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
		return plugin_dir_url( self::get_plugin_path() . BKBRKB_PLUGIN_ROOT_FILE );
	}

	/**
	 * Register the plugin constants.
	 */
	public static function register() {
		self::init();
		self::set_paths_constants();
		self::set_base_constants();
		self::set_assets_constants();
		self::set_updater_constants();
		self::set_product_info_constants();
	}

	/**
	 * Set the plugin base constants.
     *
	 * @example: $plugin_data = get_plugin_data( BKBRKB_PLUGIN_DIR . '/' . BKBRKB_PLUGIN_ROOT_FILE );
	 * echo '<pre>';
	 * print_r( $plugin_data );
	 * echo '</pre>';
	 * @example_param: Name,PluginURI,Description,Author,Version,AuthorURI,RequiresAtLeast,TestedUpTo,TextDomain,DomainPath
	 */
	private static function set_base_constants() {

		$plugin_data = get_plugin_data( BKBRKB_PLUGIN_DIR . '/' . BKBRKB_PLUGIN_ROOT_FILE );

		define( 'BKBRKB_PLUGIN_VERSION', $plugin_data['Version'] ?? '1.0.0' );
		define( 'BKBRKB_PLUGIN_TITLE', $plugin_data['Name'] ?? 'Restrict KB Access by User Role' );
		define( 'BKBRKB_TRANSLATION_DIR', $plugin_data['DomainPath'] ?? '/languages/' );
		define( 'BKBRKB_TEXT_DOMAIN', $plugin_data['TextDomain'] ?? '' );

		define( 'BKBRKB_PLUGIN_FOLDER', 'restrict-kb-by-user-role' );
		define( 'BKBRKB_PLUGIN_CURRENT_VERSION', BKBRKB_PLUGIN_VERSION );
		define( 'BKBRKB_PLUGIN_POST_TYPE', 'bwl_kb' );
		define( 'BKBRKB_PLUGIN_TAXONOMY_CAT', 'bkb_category' );
		define( 'BKBRKB_PLUGIN_TAXONOMY_TAGS', 'bkb_tags' );
	}

	/**
	 * Set the plugin paths constants.
	 */
	private static function set_paths_constants() {
		define( 'BKBRKB_PLUGIN_ROOT_FILE', 'restrict-kb-by-user-role.php' );
		define( 'BKBRKB_PLUGIN_DIR', self::get_plugin_path() );
		define( 'BKBRKB_PLUGIN_FILE_PATH', BKBRKB_PLUGIN_DIR );
		define( 'BKBRKB_PLUGIN_URL', self::get_plugin_url() );
	}

	/**
	 * Set the plugin assets constants.
	 */
	private static function set_assets_constants() {
		define( 'BKBRKB_PLUGIN_STYLES_ASSETS_DIR', BKBRKB_PLUGIN_URL . 'assets/styles/' );
		define( 'BKBRKB_PLUGIN_SCRIPTS_ASSETS_DIR', BKBRKB_PLUGIN_URL . 'assets/scripts/' );
		define( 'BKBRKB_PLUGIN_LIBS_DIR', BKBRKB_PLUGIN_URL . 'libs/' );
	}

	/**
	 * Set the updater constants.
	 */
	private static function set_updater_constants() {

		// Only change the slug.
		$slug        = 'bkbm/notifier_bkbm_rkbur.php';
		$updater_url = "https://projects.bluewindlab.net/wpplugin/zipped/plugins/{$slug}";

		define( 'BKBRKB_PLUGIN_UPDATER_URL', $updater_url ); // phpcs:ignore
		define( 'BKBRKB_PLUGIN_UPDATER_SLUG', BKBRKB_PLUGIN_FOLDER . '/' . BKBRKB_PLUGIN_ROOT_FILE ); // phpcs:ignore
		define( 'BKBRKB_PLUGIN_PATH', BKBRKB_PLUGIN_DIR );
	}

	/**
	 * Set the product info constants.
	 */
	private static function set_product_info_constants() {
		define( 'BKBRKB_PRODUCT_ID', '13722991' ); // Plugin codecanyon/themeforest Id.
		define( 'BKBRKB_PRODUCT_INSTALLATION_TAG', 'bkbm_rkbur_installation_' . str_replace( '.', '_', BKBRKB_PLUGIN_VERSION ) );
	}
}
