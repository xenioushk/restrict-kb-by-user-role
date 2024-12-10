<?php

/**
 * Plugin Name:   Restrict KB Access by User Role - Knowledgebase Addon
 * Plugin URI:      https://1.envato.market/bkbm-wp
 * Description:     Restrict KB Addon allows you to limit access of KB content according to user role. 
 * Version:           1.1.1
 * Author:            Mahbub Alam Khan
 * Author URI:      https://bluewindlab.net
 * Text Domain:    bkb_rkb
 *Domain Path:     /languages/
 */

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}

define('BKBRKB_PARENT_PLUGIN_INSTALLED_VERSION', get_option('bwl_kb_plugin_version'));
define('BKBRKB_ADDON_PARENT_PLUGIN_TITLE', 'BWL Knowledge Base Manager Plugin');
define('BKBRKB_ADDON_TITLE', 'Restrict KB Access by User Role');
define('BKBRKB_PARENT_PLUGIN_REQUIRED_VERSION', '1.4.2'); // change plugin required version in here.
define('BKBRKB_ADDON_CURRENT_VERSION', '1.1.1'); // change plugin current version in here.
define('BKBRKB_ADDON_INSTALLATION_TAG', 'bkbm_rkbur_installation_' . str_replace('.', '_', BKBRKB_ADDON_CURRENT_VERSION));
define('BKBRKB_ADDON_UPDATER_SLUG', plugin_basename(__FILE__)); // change plugin current version in here.
define("BKBRKB_ADDON_CC_ID", "13722991"); // Plugin codecanyon Id.
define('BKBRKB_DIR', plugin_dir_path(__FILE__));
define("BKBRKB_PLUGIN_DIR", plugins_url() . '/restrict-kb-by-user-role/');
define("BKBRKB_PARENT_PLUGIN_PURCHASE_STATUS", get_option('bkbm_purchase_verified') == 1 ? 1 : 0);

require_once(plugin_dir_path(__FILE__) . 'public/class-rkb-addon.php');

register_activation_hook(__FILE__, array('BKB_Rkb', 'activate'));
register_deactivation_hook(__FILE__, array('BKB_Rkb', 'deactivate'));

add_action('plugins_loaded', array('BKB_Rkb', 'get_instance'));

if (is_admin()) {

    require_once(plugin_dir_path(__FILE__) . 'admin/class-rkb-addon-admin.php');
    add_action('plugins_loaded', array('BKB_Rkb_Admin', 'get_instance'));
}
