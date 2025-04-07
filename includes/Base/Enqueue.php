<?php
namespace KAFWPB\Base;

/**
 * Class for registering the plugin scripts and styles.
 *
 * @package KAFWPB
 */
class Enqueue {

	/**
	 * Frontend script slug.
	 *
	 * @var string $frontend_script_slug
	 */
	private $frontend_script_slug;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Frontend script slug.
		// This is required to hook the loclization texts.
		$this->frontend_script_slug = 'bkb_vc-frontend';
	}

	/**
	 * Register the plugin scripts and styles loading actions.
	 */
	public function register() {
		add_action( 'wp_enqueue_scripts', [ $this, 'get_the_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'get_the_scripts' ] );
	}

	/**
	 * Load the plugin styles.
	 */
	public function get_the_styles() {

		wp_enqueue_style(
            $this->frontend_script_slug,
            KAFWPB_PLUGIN_STYLES_ASSETS_DIR . 'frontend.css',
            [],
            KAFWPB_PLUGIN_VERSION
		);
	}

	/**
	 * Load the plugin scripts.
	 */
	public function get_the_scripts() {

		// Register JS
		wp_enqueue_script(
            'counterup',
            KAFWPB_PLUGIN_LIBS_DIR . 'jquery-counterup/jquery.counterup.min.js',
            [ 'jquery' ],
            KAFWPB_PLUGIN_VERSION,
            true
        );

		wp_enqueue_script(
            'waypoints',
            KAFWPB_PLUGIN_LIBS_DIR . 'jquery-waypoint/waypoints.min.js',
            [ 'jquery' ],
            KAFWPB_PLUGIN_VERSION,
            true
        );

		wp_enqueue_script(
            $this->frontend_script_slug,
            KAFWPB_PLUGIN_SCRIPTS_ASSETS_DIR . 'frontend.js',
            [ 'jquery' ],
            KAFWPB_PLUGIN_VERSION,
            true
        );

		// Load frontend variables used by the JS files.
		$this->get_the_localization_texts();
	}

	/**
	 * Load the localization texts.
	 */
	private function get_the_localization_texts() {

		// Localize scripts.
		// Frontend.
		// Access data: KafwpbFrontendData.version
		wp_localize_script(
            $this->frontend_script_slug,
            'KafwpbFrontendData',
            [
				'version' => KAFWPB_PLUGIN_VERSION,
            ]
		);
	}
}
