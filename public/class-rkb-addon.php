<?php

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}

class BKB_Rkb
{

    const VERSION = '1.0.1';

    protected $plugin_slug = 'bkb-rkb';

    protected static $instance = null;

    private function __construct()
    {

        if (class_exists('BwlKbManager\\Init') && BKBRKB_PARENT_PLUGIN_INSTALLED_VERSION > '1.0.5') {

            $this->include_files();
            add_filter('bkb_rkb_post_access', array($this, 'bkb_rkb_post_access'));
            add_filter('the_content', array($this,  'bkb_rkb_modify_taxonomy_exceprt'));
            add_filter('custom_rkb_title', array($this,  'custom_rkb_title'));
            add_filter('bkb_rkb_query_filter', array($this,  'bkb_rkb_query_filter'));
            add_filter('bkb_rkb_blog_query_filter', array($this,  'bkb_rkb_blog_query_filter'));
            add_filter('bkb_rkb_search_query_filter', array($this,  'bkb_rkb_search_query_filter'));
        }
    }


    public function include_files()
    {

        require_once(BKBRKB_DIR . 'public/includes/bkb-rkb-helpers.php');
    }

    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function activate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }


    public static function deactivate($network_wide)
    {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }


    public function activate_new_site($blog_id)
    {

        if (1 !== did_action('wpmu_new_blog')) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }


    private static function get_blog_ids()
    {

        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    private static function single_activate()
    {
        // @TODO: Define activation functionality here
    }

    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }


    public function load_plugin_textdomain()
    {

        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
    }

    /***********************************************************
     * @Loc: restrict-kb-by-user-role\public\class-rkb-addon.php
     * @Description: Modify Meta Query Arguments Based on User Role
     * @Since: 1.0.0
     * @Created: 15-11-2015
     * @Edited: 17-11-2015
     * @By: Mahbub
     ***********************************************************/

    public function bkb_rkb_query_filter($args)
    {

        global $bkb_data;

        // If Restriction plugin is activated but the user want to allow all KB contents for all user then
        // Plugin will not modify Meta query arguments.

        if (isset($bkb_data['bkb_rkb_global_status']) && $bkb_data['bkb_rkb_global_status'] == 1) {
            return $args;
        }

        //This portion only work for restriction add, on plugin.
        // First we need to check if the plugin has been installed & activated in the system or not.
        // If the plugin is activated then we check other options.

        $current_user = wp_get_current_user(); // Get current user info.
        $current_user_role = "";
        // Extract Current User Role Info
        if (isset($current_user->roles[0])) {
            $current_user_role = $current_user->roles[0];
        }

        // Initially we will not display restricted KB items with non-restricted items.
        $bkb_rkb_all_kb_display_status = 0;

        if (isset($bkb_data['bkb_rkb_all_kb_display_status']) && $bkb_data['bkb_rkb_all_kb_display_status'] == "on") {
            //Allow to display all restricted items.
            $bkb_rkb_all_kb_display_status = 1;
        }


        if ($current_user_role == "" && $bkb_rkb_all_kb_display_status == 0) {

            // Hide Restricted Posts.
            // Only non logged in users can see open KB posts.

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'bkb_rkb_status',
                    'compare' => 'NOT EXISTS', // works!
                    'value' => '' // This is ignored, but is necessary...
                ),
                array(
                    'key' => 'bkb_rkb_status',
                    'value' => 0
                )
            );
        } else if ($current_user_role != "" && $current_user_role != "administrator" && $bkb_rkb_all_kb_display_status == 0) {

            // This area for checking logged in users capability.

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'bkb_rkb_user_roles',
                    'compare' => 'LIKE', // works!
                    'value' => $current_user_role, // This is ignored, but is necessary...
                ), array(
                    'key' => 'bkb_rkb_status',
                    'compare' => 'NOT EXISTS', // works!
                    'value' => '' // This is ignored, but is necessary...
                ),
                array(
                    'key' => 'bkb_rkb_status',
                    'value' => 0
                )
            );
        } else {
        }

        return $args;
    }

    /***********************************************************
     * @Loc: restrict-kb-by-user-role\public\class-rkb-addon.php
     * @Description: Modify KB Title With Lock/Unlock Icon Based on User Role
     * @Since: 1.0.0
     * @Created: 15-11-2015
     * @Edited: 17-11-2015
     * @By: Mahbub
     ***********************************************************/

    public function custom_rkb_title($title)
    {

        global $post, $bkb_data;

        $bkb_rkb_status = get_post_meta($post->ID, "bkb_rkb_status", TRUE);

        if ($bkb_rkb_status == 1) {
            $bkb_display_lock_icon = " <i class='fa fa-lock'></i>";
        } else {
            $bkb_display_lock_icon = "";
        }

        if (isset($bkb_data['bkb_rkb_lock_icon']) && $bkb_data['bkb_rkb_lock_icon'] == 1) {
            $bkb_display_lock_icon = "";
        }

        return $title . $bkb_display_lock_icon;
    }

    /***********************************************************
     * @Loc: restrict-kb-by-user-role\public\class-rkb-addon.php
     * @Description: Modify KB Content With Custom Message Based on User Role
     * @Since: 1.0.0
     * @Created: 15-11-2015
     * @Edited: 17-11-2015
     * @By: Mahbub
     ***********************************************************/

    public function bkb_rkb_modify_taxonomy_exceprt($content)
    {

        global $post, $bkb_data;

        if (!is_admin() && is_tax('bkb_category') && isset($bkb_data['bkb_cat_default_tpl_ordering_status']['enabled']) && $bkb_data['bkb_cat_default_tpl_ordering_status']['enabled'] == 'on') {

            $bkb_rkb_post_access_result = apply_filters('bkb_rkb_post_access', $post->ID);

            if ($bkb_rkb_post_access_result != 1) {
                return $bkb_rkb_post_access_result;
            } else {
                return $content;
            }
        } else if (!is_admin() && is_tax('bkb_tags') && isset($bkb_data['bkb_tag_default_tpl_ordering_status']['enabled']) && $bkb_data['bkb_tag_default_tpl_ordering_status']['enabled'] == 'on') {

            $bkb_rkb_post_access_result = apply_filters('bkb_rkb_post_access', $post->ID);

            if ($bkb_rkb_post_access_result != 1) {
                return $bkb_rkb_post_access_result;
            } else {
                return $content;
            }
        } else if (!is_admin() && get_post_type($post->ID) == "bwl_kb") {

            $bkb_rkb_post_access_result = apply_filters('bkb_rkb_post_access', $post->ID);

            if ($bkb_rkb_post_access_result != 1) {
                return $bkb_rkb_post_access_result;
            } else {
                return $content;
            }
        } else {
            return $content;
        }
    }

    /***********************************************************
     * @Loc: restrict-kb-by-user-role\public\class-rkb-addon.php
     * @Description: Modify Single Page KB Content With Custom Message Based on User Role
     * @Since: 1.0.0
     * @Created: 15-11-2015
     * @Edited: 17-11-2015
     * @By: Mahbub
     ***********************************************************/


    public function bkb_rkb_post_access($post_id)
    {

        global $bkb_data;

        // First we check if global KB access disable status. If global status is 1 then we allow all kind of users 
        // to access all the KB content.
        if (isset($bkb_data['bkb_rkb_global_status']) && $bkb_data['bkb_rkb_global_status'] == 1) {
            return 1;
        }

        //Secondly, if global access disable is false then we need to check each post current access status.
        // Admin can individually set restriction.

        $bkb_rkb_allow_post_access_message = 1; // Return 1 if user can access the content. Else Return a message.

        $bkb_rkb_status = get_post_meta($post_id, "bkb_rkb_status", TRUE);  // Get Access Restriction Staus.

        // So, if status is 1 then we need to check user compatibility to access current post.

        if ($bkb_rkb_status == 1) {

            //Checking user compatibility in here.
            $bkb_rkb_allow_post_access_status = bkb_user_can_access_content($post_id);

            // If post access status return 1 then user can access the page other wise we display a notification message.
            // Admin can set custom message for access restriction.

            if ($bkb_rkb_allow_post_access_status != 1) {

                if (isset($bkb_data['bkb_rkb_single_kb_msg']) && $bkb_data['bkb_rkb_single_kb_msg'] != "") {

                    $bkb_rkb_allow_post_access_message = sanitize_textarea_field($bkb_data['bkb_rkb_single_kb_msg']);
                } else {
                    $bkb_wp_login_url = home_url() . '/wp-admin/';
                    $bkb_rkb_allow_post_access_message = __("Sorry, you are not allowed to access the knowledgebase content.", "bkb_rkb") .
                        ' <a href=' . $bkb_wp_login_url . ' target="_blank">' . __('Log In', 'bkb_rkb') . '</a> ' . __('required to access the content', 'bkb_rkb');
                }
            }
        }

        return $bkb_rkb_allow_post_access_message;
    }

    public function bkb_rkb_blog_query_filter($bkb_kbdabp_excluded_posts)
    {

        global $bkb_data;

        // First we check if global KB access disable status. If global status is 1 then we allow all kind of users 
        // to access all the KB content.
        if (isset($bkb_data['bkb_rkb_global_status']) && $bkb_data['bkb_rkb_global_status'] == 1) {
            return $bkb_kbdabp_excluded_posts;
        }


        $args = array(
            'post_status' => 'publish',
            'post_type' => 'bwl_kb',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => -1
        );

        //This portion only work for restriction add, on plugin.
        // First we need to check if the plugin has been installed & activated in the system or not.
        // If the plugin is activated then we check other options.

        $current_user = wp_get_current_user(); // Get current user info.
        $current_user_role = "";
        // Extract Current User Role Info
        if (isset($current_user->roles[0])) {
            $current_user_role = $current_user->roles[0];
        }


        // Initially we will not display restricted KB items with non-restricted items.
        $bkb_rkb_all_kb_display_status = 0;

        if (isset($bkb_data['bkb_rkb_all_kb_display_status']) && $bkb_data['bkb_rkb_all_kb_display_status'] == "on") {
            //Allow to display all restricted items.
            $bkb_rkb_all_kb_display_status = 1;
        }


        if ($current_user_role == "" && $bkb_rkb_all_kb_display_status == 0) {

            // Hide Restricted Posts.
            // Only non logged in users can see open KB posts.

            $args['meta_query'] = array(
                array(
                    'key' => 'bkb_rkb_status',
                    'value' => 1
                )
            );
        } else if ($current_user_role != "" && $current_user_role != "administrator" && $bkb_rkb_all_kb_display_status == 0) {

            // This area for checking logged in users capability.

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'bkb_rkb_user_roles',
                    'compare' => 'NOT LIKE', // works!
                    'value' => $current_user_role, // This is ignored, but is necessary...
                ), array(
                    'key' => 'bkb_rkb_status',
                    'compare' => 'NOT EXISTS', // works!
                    'value' => '' // This is ignored, but is necessary...
                )
            );
        } else {

            // Default User Role Administrator. Just return the query.
            return $bkb_kbdabp_excluded_posts;
        }

        $loop = new WP_Query($args);

        if (count($loop->posts) > 0) {

            foreach ($loop->posts as $posts) {

                $bkb_kbdabp_excluded_posts[] = $posts->ID;
            }
        }

        wp_reset_query();

        return $bkb_kbdabp_excluded_posts;
    }


    public function bkb_rkb_search_query_filter($rkb_query_vars)
    {


        global $wpdb;

        extract($rkb_query_vars);

        $args = array(
            's' => trim($s),
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page'  => $limit,
            'orderby' => $orderby,
        );
        //                   echo "<pre>";
        //                   print_r($args);
        //                   echo "</pre>";
        //                   die();

        $current_user = wp_get_current_user(); // Get current user info.
        $current_user_role = "";
        // Extract Current User Role Info
        if (isset($current_user->roles[0])) {
            $current_user_role = $current_user->roles[0];
        }

        if ($current_user_role != "" && $current_user_role != "administrator") {

            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key' => 'bkb_rkb_user_roles',
                    'compare' => 'LIKE', // works!
                    'value' => $current_user_role, // This is ignored, but is necessary...
                ), array(
                    'key' => 'bkb_rkb_status',
                    'compare' => 'NOT EXISTS', // works!
                    'value' => '' // This is ignored, but is necessary...
                ),
                array(
                    'key' => 'bkb_rkb_status',
                    'value' => 0
                )
            );
        } else if ($current_user_role != "" && $current_user_role == "administrator") {

            // Admin user
            //                        die();
            $args['meta_query'] = array();
        } else {

            // Non Loggedin Users

            $args['meta_query'] = array(

                array(
                    'key' => 'bkb_rkb_status',
                    'value' => 0
                )
            );
        }

        $query = new WP_Query($args);

        //                   echo $wpdb->last_query;
        //                   echo "<pre>";
        //                   print_r($query);
        //                   echo "</pre>";

        $pageposts = $query->posts;

        //                   echo "<pre>";
        //                   print_r($pageposts);
        //                   echo "</pre>";

        $output = array();
        $counter = 0;
        foreach ($pageposts as $k => $v) {

            //                        echo "<pre>";
            //                        print_r($v);
            //                        echo "</pre>";

            $output[$counter]['title'] = $v->post_title;
            $output[$counter]['link'] = get_permalink($v->ID);
            $counter++;
        }

        $results = $output;
        //                    
        //                    echo "<pre>";
        //                    print_r($results);
        //                    echo "</pre>";
        //
        //                    die();

        return $results;
    }
}
