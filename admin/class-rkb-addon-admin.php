<?php
if (!defined('WPINC')) {
    die;
}
/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.

 */
class BKB_Rkb_Admin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        
        //@Description: First we need to check if KB Plugin is activated or not. If not then we display a message and return false.
        //@Since: Version 1.0.5
        
        if( ! class_exists( 'BWL_KB_Manager' ) || BKBRKB_PARENT_PLUGIN_REQUIRED_VERSION < '1.0.5' ) {
            add_action('admin_notices', array($this, 'rkb_version_update_admin_notice'));
            return false;
        }
        
        $plugin = BKB_rkb::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
        $post_types = 'bwl_kb';

        // After manage text we need to add "custom_post_type" value.
        add_filter('manage_' . $post_types . '_posts_columns', array($this, 'bkb_rkb_custom_column_header'));

        // After manage text we need to add "custom_post_type" value.
        add_action('manage_' . $post_types . '_posts_custom_column', array($this, 'bkb_rkb_display_custom_column'), 10, 1);


        // Quick & Bulk Edit Section.

        add_action('bulk_edit_custom_box', array($this, 'bkb_rkb_product_quick_edit_box'), 10, 2);
        add_action('quick_edit_custom_box', array($this, 'bkb_rkb_product_quick_edit_box'), 10, 2);

        add_action('save_post', array($this, 'bkb_rkb_product_save_quick_edit_data'), 10, 2);
        add_action('wp_ajax_manage_wp_posts_using_bulk_edit_rkb', array($this, 'manage_wp_posts_using_bulk_edit_rkb'));

        // Load public-facing style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'bkb_rkb_admin_enqueue_scripts'));
        
        
        /*------------------------------  Custom Filter For User Role---------------------------------*/
        add_action( 'restrict_manage_posts', array($this, 'bkb_rkb_admin_top_user_role_filter'), 11 );
        add_filter( 'parse_query', array($this, 'cb_bkb_rkb_admin_top_user_role_filter'), 11 );
        
    }


    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
     //Version Manager:  Update Checking
    
    public function rkb_version_update_admin_notice(){
        
        echo '<div class="updated"><p>You need to download & install '
            . '<b><a href="https://1.envato.market/bkbm-wp" target="_blank">BWL Knowledge Base Manager Plugin</a></b> '
            . 'to use <b>Restrict KB Access by User Role  - Knowledgebase Addon</b>.</p></div>';
        
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function bkb_rkb_admin_enqueue_scripts( $hook ) {
        
         if ( 'edit.php' == $hook && isset($_GET['post_type']) && $_GET['post_type'] == "bwl_kb" ) {
    
            wp_enqueue_script($this->plugin_slug . '-admin-custom-script', plugins_url('assets/js/bkb_rkb-admin-scripts.js', __FILE__), array('jquery'), BKB_Rkb::VERSION, TRUE);
            
        } else {
            
            return;
            
        }
        
    }

    function bkb_rkb_custom_column_header($columns) {

        return array_merge($columns, array(
            'bkb_rkb_status' => __('Access', 'bkb_rkb')
                )
        );
    }

    function bkb_rkb_display_custom_column($column) {

        // Add A Custom Image Size For Admin Panel.

        global $post;

        switch ($column) {

            case 'bkb_rkb_status':

                $bkb_rkb_status = ( get_post_meta($post->ID, "bkb_rkb_status", true) == 1 ) ? 1 : 0;

                // FAQ Display Status In Text.

                $bkb_rkb_status_in_text = ( $bkb_rkb_status == 1 ) ? '<i class="fa fa-lock"></i>' : '<i class="fa fa-unlock"></i>';
                
                $bkb_rkb_user_roles_access_text = __("All types of user can view this KB content", 'bkb_rkb');
                
                if( $bkb_rkb_status == 1 ) {
                    $bkb_rkb_user_roles_titles = get_post_meta($post->ID, 'bkb_rkb_user_roles', true);
            
                    if( ! is_array($bkb_rkb_user_roles_titles) ) {
                        $bkb_rkb_user_roles_access_text = __('only administrator can access this KB', 'bkb_rkb');
                    } else {
                        $bkb_roles = implode(', ', $bkb_rkb_user_roles_titles);
                        $bkb_rkb_user_roles_access_text = __('only', 'bkb_rkb') ." ". $bkb_roles . " ".__('can access this KB', 'bkb_rkb');
                    }
                    
                }

                echo '<div style="cursor: help;" id="bkb_rkb_status-' . $post->ID . '" data-status_code="' . $bkb_rkb_status . '" title="'. ucwords($bkb_rkb_user_roles_access_text).'">' . $bkb_rkb_status_in_text . '</div>';

                break;
        }
    }

    /* ------------------------------ Bulk & Quick Edit Section --------------------------------- */

    function bkb_rkb_product_quick_edit_box($column_name, $post_type) {

        global $post;

        switch ($post_type) {

            case $post_type:

                switch ($column_name) {

                    case 'bkb_rkb_status':

                        $bkb_rkb_status = ( get_post_meta($post->ID, "bkb_rkb_status", true) == "" ) ? "" : get_post_meta($post->ID, "bkb_rkb_status", true);
                        ?>

                        <fieldset class="inline-edit-col-right">
                            <div class="inline-edit-col">
                                <div class="inline-edit-group">
                                    <label class="inline-edit-status alignleft">
                                        <span class="title"><?php _e('Restrict Access', 'bkb_rkb'); ?></span>
                                        <select name="bkb_rkb_status">
                                            <option value=""><?php _e('- No Change -', 'bkb_rkb'); ?></option>
                                            <option value="1"><?php _e('Yes', 'bkb_rkb'); ?></option>
                                            <option value="0"><?php _e('No', 'bkb_rkb'); ?></option>
                                        </select>
                                    </label>

                                </div>
                            </div>
                        </fieldset>


                        <?php
                        break;
                }

                break;
        }
    }

    function bkb_rkb_product_save_quick_edit_data($post_id, $post) {

        // pointless if $_POST is empty (this happens on bulk edit)
        if (empty($_POST))
            return $post_id;

        // verify quick edit nonce
        if (isset($_POST['_inline_edit']) && !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
            return $post_id;

        // don't save for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        // dont save for revisions
        if (isset($post->post_type) && $post->post_type == 'revision')
            return $post_id;

        switch ($post->post_type) {

            case $post->post_type:

                /**
                 * Because this action is run in several places, checking for the array key
                 * keeps WordPress from editing data that wasn't in the form, i.e. if you had
                 * this post meta on your "Quick Edit" but didn't have it on the "Edit Post" screen.
                 */
                $custom_fields = array('bkb_rkb_status');

                foreach ($custom_fields as $field) {

                    if (array_key_exists($field, $_POST)) {

                        update_post_meta($post_id, $field, $_POST[$field]);
                    }
                }

                break;
        }
    }

    function bkb_rkb_product_bulk_edit_box($column_name, $post_type) {

        global $post;

        switch ($post_type) {

            case $post_type:

                switch ($column_name) {

                    case 'bkb_rkb_status':
                        ?>

                        <fieldset class="inline-edit-col-right">
                            <div class="inline-edit-col">
                                <div class="inline-edit-group">
                                    <label class="inline-edit-status alignleft">
                                        <span class="title"><?php _e('Restrict Access?', 'bkb_rkb'); ?></span>
                                        <select name="bkb_rkb_status">
                                            <option value=""><?php _e('- No Change -', 'bkb_rkb'); ?></option>
                                            <option value="1"><?php _e('Yes', 'bkb_rkb'); ?></option>
                                            <option value="0"><?php _e('No', 'bkb_rkb'); ?></option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        <?php
                        break;
                }

                break;
        }
    }

    function manage_wp_posts_using_bulk_edit_rkb() {

        // we need the post IDs
        $post_ids = ( isset($_POST['post_ids']) && !empty($_POST['post_ids']) ) ? $_POST['post_ids'] : NULL;

        // if we have post IDs
        if (!empty($post_ids) && is_array($post_ids)) {

            // Get the custom fields

            $custom_fields = array('bkb_rkb_status');

            foreach ($custom_fields as $field) {

                // if it has a value, doesn't update if empty on bulk
                if (isset($_POST[$field]) && trim($_POST[$field]) != "") {

                    // update for each post ID
                    foreach ($post_ids as $post_id) {

                        if ($_POST[$field] != "") {
                            update_post_meta($post_id, $field, $_POST[$field]);
                        }
                    }
                }
            }
        }
    }
    
    
        /***********************************************************
        * @Loc: 
        * @Description: Admin Top Filter For Role Manager
        * @Since: 1.0.0
        * @Created: 15-11-2015
        * @Edited: 17-11-2015
        * @By: Mahbub
        ***********************************************************/
    

        function bkb_rkb_admin_top_user_role_filter(){
            
            global $typenow;

            //only add filter to post type you want
            if ($typenow == 'bwl_kb') {
               
                $bkbm_all_user_roles = get_editable_roles();
                    
                $bkbm_filter_user_roles = array();

                if(sizeof($bkbm_all_user_roles)>0):
                    foreach ($bkbm_all_user_roles as $role_id=>$role_info ) :
                        $bkbm_filter_user_roles[$role_id] = $role_info['name'];
                    endforeach;
                endif;
                ?>
                <select name="bkb_rkb_user_roles">
                <option value=""><?php _e('Select Role ', 'bkb_rkb'); ?></option>
                <?php
                    $current_v = isset($_GET['bkb_rkb_user_roles'])? $_GET['bkb_rkb_user_roles']:'';
                    foreach ($bkbm_filter_user_roles as $label => $value) {
                        printf
                            (
                                '<option value="%s"%s>%s</option>',
                                $label,
                                $label == $current_v? ' selected="selected"':'',
                                $value
                            );
                        }
                ?>
                </select>
                <?php
            }
        }


        function cb_bkb_rkb_admin_top_user_role_filter( $query ){
            
            global $pagenow;
            global $typenow;
            
            if ( 'bwl_kb' == $typenow && is_admin() && $pagenow=='edit.php' && isset($_GET['bkb_rkb_user_roles']) && $_GET['bkb_rkb_user_roles'] != '') {
                $query->query_vars['meta_key'] = 'bkb_rkb_user_roles';
                $query->query_vars['meta_value'] = $_GET['bkb_rkb_user_roles'];
                $query->query_vars['meta_compare'] = 'LIKE';
            }
        }

}
