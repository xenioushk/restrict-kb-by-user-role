<?php
namespace KAFWPB\Base;

/**
 * Class for including plucin required files.
 *
 * @since: 1.1.0
 * @package KAFWPB
 */
class IncludePluginFiles {

	/**
	 * Files to include in the frontend.
	 *
	 * @var array $frontend_files
	 */
	private $frontend_files;

	/**
	 * Files to include in the frontend.
	 *
	 * @var array $admin_files
	 */
	private $admin_files;

	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		$this->frontend_files = $this->set_frontend_files();
		$this->admin_files    = $this->set_admin_files();
	}

	/**
	 * Register the plugin files.
	 */
	public function register() { // phpcs:ignore

		if ( ! empty( $this->frontend_files ) ) {
			foreach ( $this->frontend_files as $file ) {
				include_once KAFWPB_PLUGIN_FILE_PATH . "/{$file}.php";
			}
		}

		if ( is_admin() && ! empty( $this->admin_files ) ) {
			foreach ( $this->admin_files as $file ) {
				include_once KAFWPB_PLUGIN_FILE_PATH . "/{$file}.php";
			}
		}
	}

	/**
	 * Set the frontend files.
	 */
	private function set_frontend_files() {

		// example
		// $frontend_files = [
		// 'includes/bptm-email-template',
		// 'includes/bwl-pm-helper',
		// 'includes/bwl-sql-helper',
		// ];

		$frontend_files = [];

		return $frontend_files;
	}

	/**
	 * Set the admin files.
	 */
	private function set_admin_files() {
		$admin_files = [];
		return $admin_files;
	}
}
