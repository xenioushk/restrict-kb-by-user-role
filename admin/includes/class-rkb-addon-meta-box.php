<?php

if (!defined('WPINC')) {
    die;
}

use \BwlKbManager\Api\CmbMetaBoxApi;

class RkbAddonMetaBox
{
    function bkb_rkb_custom_meta_init()
    {
        $bkbm_all_user_roles = get_editable_roles();
        $bkbm_filter_user_roles = array();

        if (sizeof($bkbm_all_user_roles) > 0) :
            foreach ($bkbm_all_user_roles as $role_id => $role_info) :
                $bkbm_filter_user_roles[$role_id] = $role_info['name'];
            endforeach;
        endif;

        //    echo "<pre>";
        //    print_r($bkbm_filter_user_roles);
        //    echo "</pre>";
        //    die();
        $custom_fields = array(

            'meta_box_id'           => 'cmb_bkb_rkb', // Unique id of meta box.
            'meta_box_heading'  => __('KB Access Restriction', 'bkb_rkb'), // That text will be show in meta box head section.
            'post_type'               => 'bwl_kb', // define post type. go to register_post_type method to view post_type name.        
            'context'                   => 'side',
            'priority'                    => 'low',
            'fields'                       => array(
                'bkb_rkb_status'  => array(
                    'title'      => __('Restrict KB content access?', 'bkb_rkb'),
                    'id'         => 'bkb_rkb_status',
                    'name'    => 'bkb_rkb_status',
                    'type'      => 'select',
                    'value'     => array(
                        '0' => __('No', 'bkb_rkb'),
                        '1' => __('Yes', 'bkb_rkb')
                    ),
                    'default_value' => 0,
                    'class'      => 'widefat'
                ),
                'bkb_rkb_user_roles'  => array(
                    'title'      => __('Select user roles you want to give access: ', 'bkb_rkb'),
                    'id'         => 'bkb_rkb_user_roles',
                    'name'    => 'bkb_rkb_user_roles',
                    'type'      => 'multiple_checkbox',
                    'value'     => $bkbm_filter_user_roles,
                    'default_value' => 1,
                    'class'      => 'widefat',
                    'disable_item' => 'administrator',
                    'disable_note' => __('Administrator always able to access all the KB items', 'bkb_rkb'),
                )
            )
        );
        // A new meta box will be created in KB add/edit page.
        if (class_exists('BwlKbManager\\Init')) {
            new CmbMetaBoxApi($custom_fields);
        }
    }
}

// META BOX START EXECUTION FROM HERE.

add_action('admin_init', [new RkbAddonMetaBox(), 'bkb_rkb_custom_meta_init']);