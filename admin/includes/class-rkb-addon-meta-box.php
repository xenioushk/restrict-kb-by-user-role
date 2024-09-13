<?php

if (!defined('WPINC')) {
    die;
}

use \BwlKbManager\Api\CmbMetaBoxApi;
use \BwlKbManager\Base\BaseController;

class RkbAddonMetaBox
{
    function bkb_rkb_custom_meta_init()
    {
        $baseController = new BaseController();

        
        // Get all the roles
        global $wp_roles;
        $allAvailableRoles = $wp_roles->roles;
        $filteredRoles = [];

        if (sizeof($allAvailableRoles) > 0) :
            foreach ($allAvailableRoles as $role_id => $role_info) :                
                $filteredRoles[$role_id] = $role_info['name'];
            endforeach;
        endif;

        $custom_fields = array(

            'meta_box_id'           => 'cmb_bkb_rkb', // Unique id of meta box.
            'meta_box_heading'  => __('KB Access Restriction', 'bkb_rkb'), // That text will be show in meta box head section.
            'post_type'               => $baseController->plugin_post_type, // define post type. go to register_post_type method to view post_type name.        
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
                    'value'     => $filteredRoles,
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
