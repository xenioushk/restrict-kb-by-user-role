<?php
namespace BKBRKB\Helpers;

/**
 * Class for plugin helpers.
 *
 * @package BKBRKB
 */
class RkbHelpers {


    public static function can_user_access( $post_id ) {

        // Initially, we set allow disable for current user.
        $bkb_rkb_allow_post_access = 0;

        // Get All List of Allowed User set by the admin.
        $bkb_rkb_user_roles = get_post_meta( $post_id, 'bkb_rkb_user_roles', true );

        $defaultRole = 'administrator';

        if ( ! empty( $bkb_rkb_user_roles ) ) {

            $isAdminRoleExists = array_search( $defaultRole, $bkb_rkb_user_roles, true );

            if ( $isAdminRoleExists === false ) {
                $bkb_rkb_user_roles[] = $defaultRole;
            }
		} else {
            $bkb_rkb_user_roles = [ $defaultRole ];
        }

        // Get current user info.
        $current_user = wp_get_current_user();
        // If current user not logged in then we set user role as blank.
        $current_user_role = '';

        // Extracting Current User Role Information.
        if ( isset( $current_user->roles[0] ) ) {
            $current_user_role = $current_user->roles[0];
        }

        // Checking user role can able to access the content or not.
        // If user role can access the content then we change post access value in to 1 and return it.

        if ( $current_user_role != '' && in_array( $current_user_role, $bkb_rkb_user_roles ) ) {

            $bkb_rkb_allow_post_access = 1;
        }

        return $bkb_rkb_allow_post_access;
    }


    public static function bkb_rkb_get_excluded_posts( $bkb_tax_type = 'category' ) {

        global $bkb_data;

        $bkb_rkb_get_excluded_posts = [];

        if ( isset( $bkb_data['bkb_rkb_all_kb_display_status'] ) && $bkb_data['bkb_rkb_all_kb_display_status'] == 'on' ) {

            return $bkb_rkb_get_excluded_posts;
        } else {

            global $wp_query;
            $current_queried_object = $wp_query->get_queried_object();
            $category_slug          = $current_queried_object->slug;

            $args = [
                'post_status'         => 'publish',
                'post_type'           => 'bwl_kb',
                'ignore_sticky_posts' => 1,
                'posts_per_page'      => -1,
            ];

            if ( $bkb_tax_type == 'tags' ) {
                $args['bkb_tags'] = $category_slug;
            } else {
                $args['bkb_category'] = $category_slug;
            }

            $args['meta_query'] = [
                [
                    'key'     => 'bkb_rkb_status',
                    'compare' => '=',
                    'value'   => '1',
                ],
            ];

            $loop = new WP_Query( $args );

            if ( count( $loop->posts ) > 0 ) {

                foreach ( $loop->posts as $posts ) {

                    $bkb_rkb_get_excluded_posts[] = $posts->ID;
                }
            }

            wp_reset_query();
        }

        return $bkb_rkb_get_excluded_posts;
    }
}
