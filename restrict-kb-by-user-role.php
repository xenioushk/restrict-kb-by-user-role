<?php

/**
 * Plugin Name:     Restrict KB Access by User Role - Knowledgebase Addon
 * Plugin URI:        http://bit.ly/kb-lock
 * Description:      Restrict KB Addon allows you to limit access of KB content according to user role. 
 * Version:           1.0.5
 * Author:            Md Mahbub Alam Khan
 * Author URI:       http://codecanyon.net/user/xenioushk?ref=xenioushk
 * Text Domain: bkb_rkb
 *Domain Path: /languages/
 */
// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */

//Version Define For Parent Plugin And Addon.
// @Since: 1.0.0

define('BKBRKB_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_kb_plugin_version'));
define('BKBRKB_ADDON_PARENT_PLUGIN_TITLE', '<b>BWL Knowledge Base Manager Plugin</b> ');
define('BKBRKB_ADDON_TITLE', '<b>Restrict KB Access by User Role</b>');
define('BKBRKB_PARENT_PLUGIN_REQUIRED_VERSION', '1.0.9'); // change plugin required version in here.
define('BKBRKB_ADDON_CURRENT_VERSION', '1.0.5'); // change plugin current version in here.

define('BKBRKB_DIR', plugin_dir_path(__FILE__));
define("BKBRKB_PLUGIN_DIR", plugins_url() . '/restrict-kb-by-user-role/');
require_once(plugin_dir_path(__FILE__) . 'public/class-rkb-addon.php');

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array('BKB_Rkb', 'activate'));
register_deactivation_hook(__FILE__, array('BKB_Rkb', 'deactivate'));

add_action('plugins_loaded', array('BKB_Rkb', 'get_instance'));

/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . 'admin/includes/class-rkb-addon-meta-box.php');
    require_once(plugin_dir_path(__FILE__) . 'admin/class-rkb-addon-admin.php');
    add_action('plugins_loaded', array('BKB_Rkb_Admin', 'get_instance'));
}