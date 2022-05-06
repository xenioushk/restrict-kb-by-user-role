<?php

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}


if (!function_exists('bkb_user_can_access_content')) {

/***********************************************************
* @Loc: restrict-kb-by-user-role\public\includes\bkb-rkb-helpers.php
* @Description: Check user content access compatibility.
* @Since: 1.0.0
* @Created: 15-11-2015
* @Edited: 17-11-2015
* @By: Mahbub
***********************************************************/
    
    function bkb_user_can_access_content($post_id) {
      
        //Initially, we set allow disable for current user.
        $bkb_rkb_allow_post_access = 0;

        //Get All List of Allowed User set by the admin.
        $bkb_rkb_user_roles = get_post_meta($post_id, "bkb_rkb_user_roles", TRUE);
//        echo "<pre>";
//        print_r($bkb_rkb_user_roles);
//        echo "</pre>";
//        echo "<pre>";
//        print_r(json_decode($bkb_rkb_user_roles));
//        echo "</pre>";
        if( !is_array($bkb_rkb_user_roles) ) {
            $bkb_rkb_user_roles = array('administrator') ;
        }
        
//        echo "IN".$post_id." Array Size".sizeof($bkb_rkb_user_roles);
//        echo "<br>";
       
        // Get current user info.
        $current_user = wp_get_current_user();
        //If current user not logged in then we set user role as blank.
        $current_user_role = "";

        // Extracting Current User Role Information.
        if(isset($current_user->roles[0])) {
            $current_user_role = $current_user->roles[0];
        }
//  echo $current_user_role;      
//        echo "<pre>";
//        print_r($bkb_rkb_user_roles);
//        echo "</pre>";
//        echo "Status". is_string($bkb_rkb_user_roles);
//        
//        if(is_string($bkb_rkb_user_roles)) {
//            echo json_decode($bkb_rkb_user_roles);
//            echo "<pre>";
//            print_r(json_decode($bkb_rkb_user_roles));
//            echo "</pre>";
//        }
//        die();

        //Checking user role can able to access the content or not.
        // If user role can access the content then we change post access value in to 1 and return it.
        
        if( $current_user_role!="" && in_array( $current_user_role, $bkb_rkb_user_roles )) {
            
            $bkb_rkb_allow_post_access = 1;
            
        }

         return $bkb_rkb_allow_post_access;
    
     }

}

if (!function_exists('bkb_rkb_get_excluded_posts')) {

    function bkb_rkb_get_excluded_posts($bkb_tax_type = "category") {

        global $bkb_data;

        $bkb_rkb_get_excluded_posts = array();

        if (isset($bkb_data['bkb_rkb_all_kb_display_status']) && $bkb_data['bkb_rkb_all_kb_display_status'] == "on") {

            return $bkb_rkb_get_excluded_posts;
            
        } else {

            global $wp_query;
            $current_queried_object = $wp_query->get_queried_object();
            $category_slug = $current_queried_object->slug;
//                echo $category_slug; 
//            echo "<pre>";
//            print_r($bkb_data);
//            echo "</pre>";
            $args = array(
                'post_status' => 'publish',
                'post_type' => 'bwl_kb',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => -1
            );

            if ($bkb_tax_type == "tags") {
                $args['bkb_tags'] = $category_slug;
            } else {
                $args['bkb_category'] = $category_slug;
            }

            $args ['meta_query'] = array(
                array(
                    'key' => 'bkb_rkb_status',
                    'compare' => '=',
                    'value' => '1'
                )
            );

//                echo "<pre>";
//                print_r($args);
//                echo "</pre>";

            $loop = new WP_Query($args);

            if (count($loop->posts) > 0) {

                foreach ($loop->posts as $posts) {

                    $bkb_rkb_get_excluded_posts[] = $posts->ID;
                }
            }

//                echo "<pre>";
//                print_r($bkb_kbdabp_excluded_posts);
//                echo "</pre>";

            wp_reset_query();
        }

        return $bkb_rkb_get_excluded_posts;
    }

}