<?php

/**
 * Plugin Name:   Restrict KB Access by User Role - Knowledgebase Addon
 * Plugin URI:      https://1.envato.market/bkbm-wp
 * Description:     Restrict KB Addon allows you to limit access of KB content according to user role.
 * Author:            Mahbub Alam Khan
 * Version:           1.1.3
 * Author URI:      https://bluewindlab.net
 * WP Requires at least: 6.0+
 * Text Domain:    bkb_rkb
 * Domain Path:     /languages/
 *
 * @package BKBRKB
 * @author Mahbub Alam Khan
 * @license GPL-2.0+
 * @link https://codecanyon.net/user/xenioushk
 * @copyright 2025 BlueWindLab
 */


namespace BKBRKB;

// security check.
defined( 'ABSPATH' ) || die( 'Unauthorized access' );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Load the plugin constants
if ( file_exists( __DIR__ . '/includes/Helpers/DependencyManager.php' ) ) {
    Helpers\DependencyManager::register();
}

use KAFWPB\Base\Activate;
use KAFWPB\Base\Deactivate;

/**
 * Function to handle the activation of the plugin.
 *
 * @return void
 */
function activate_plugin() { // phpcs:ignore
    $activate = new Activate();
    $activate->activate();
}

/**
 * Function to handle the deactivation of the plugin.
 *
 * @return void
 */
function deactivate_plugin() { // phpcs:ignore
	Deactivate::deactivate();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate_plugin' );

/**
 * Function to handle the initialization of the plugin.
 *
 * @return void
 */
function init_bkbrkb() {

    // Check if the parent plugin installed.
    if ( ! class_exists( 'BwlKbManager\\Init' ) ) {
        add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_main_plugin' ] );
        return;
    }

    // Check parent plugin activation status.
    if ( ! ( Helpers\DependencyManager::get_product_activation_status() ) ) {
        add_action( 'admin_notices', [ Helpers\DependencyManager::class, 'notice_missing_purchase_verification' ] );
        return;
    }

    if ( class_exists( 'BKBRKB\\Init' ) ) {

        // Init::register_services();
    }
}

add_action( 'init', __NAMESPACE__ . '\\init_bkbrkb' );


return;

// If this file is called directly, abort.


define( 'BKBRKB_PARENT_PLUGIN_INSTALLED_VERSION', get_option( 'bwl_kb_plugin_version' ) );
define( 'BKBRKB_ADDON_PARENT_PLUGIN_TITLE', 'BWL Knowledge Base Manager Plugin' );
define( 'BKBRKB_ADDON_TITLE', 'Restrict KB Access by User Role' );
define( 'BKBRKB_PARENT_PLUGIN_REQUIRED_VERSION', '1.4.2' ); // change plugin required version in here.
define( 'BKBRKB_ADDON_CURRENT_VERSION', '1.1.2' ); // change plugin current version in here.
define( 'BKBRKB_ADDON_INSTALLATION_TAG', 'bkbm_rkbur_installation_' . str_replace( '.', '_', BKBRKB_ADDON_CURRENT_VERSION ) );
define( 'BKBRKB_ADDON_UPDATER_SLUG', plugin_basename( __FILE__ ) ); // change plugin current version in here.
define( 'BKBRKB_ADDON_CC_ID', '13722991' ); // Plugin codecanyon Id.
define( 'BKBRKB_DIR', plugin_dir_path( __FILE__ ) );
define( 'BKBRKB_PLUGIN_DIR', plugins_url() . '/restrict-kb-by-user-role/' );

require_once plugin_dir_path( __FILE__ ) . 'public/class-rkb-addon.php';

register_activation_hook( __FILE__, [ 'BKB_Rkb', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'BKB_Rkb', 'deactivate' ] );

add_action( 'plugins_loaded', [ 'BKB_Rkb', 'get_instance' ] );

if ( is_admin() ) {

    include_once plugin_dir_path( __FILE__ ) . 'admin/class-rkb-addon-admin.php';
    add_action( 'plugins_loaded', [ 'BKB_Rkb_Admin', 'get_instance' ] );
}
