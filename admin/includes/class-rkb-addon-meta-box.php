<?php

if (!defined('WPINC')) {
    die;
}

 /*------------------------------  Custom Meta Box Section ---------------------------------*/

class BKB_RKB_Meta_Box {
    
    function __construct( $custom_fields ) {
        
        $this->custom_fields  = $custom_fields; //Set custom field data as global value.

        add_action( 'add_meta_boxes', array( &$this, 'metaboxes' ) );
        
        add_action( 'save_post', array( &$this, 'save_meta_box_data' ) ); 
        
    }
            
    
    //Custom Meta Box.
    
    function metaboxes() {
        
        $bwl_cmb_custom_fields = $this->custom_fields;

        // First parameter is meta box ID.
        // Second parameter is meta box title.
        // Third parameter is callback function.
        // Last paramenter must be same as post_type_name
        
        add_meta_box(
            $bwl_cmb_custom_fields['meta_box_id'],
            $bwl_cmb_custom_fields['meta_box_heading'],
            array( &$this, 'show_meta_box' ),
            $bwl_cmb_custom_fields['post_type'],            
            $bwl_cmb_custom_fields['context'], 
            $bwl_cmb_custom_fields['priority']
        );

    }

    function show_meta_box( $post ) {
        
        $bwl_cmb_custom_fields = $this->custom_fields;
        
        foreach( $bwl_cmb_custom_fields['fields'] as $custom_field ) :

            $field_value = get_post_meta($post->ID, $custom_field['id'], true);
       
        ?>

            <?php if( $custom_field['type'] == 'text' ) : ?>

            <p>
                <label for="<?php echo $custom_field['id']?>"><?php echo $custom_field['title']?> </label>
                <input type="<?php echo $custom_field['type']?>" id="<?php echo $custom_field['id']?>" name="<?php echo $custom_field['name']?>" class="<?php echo $custom_field['class']?>" value="<?php echo esc_attr($field_value); ?>"/>
            </p>
            
            <?php endif; ?>
            
            <?php if( $custom_field['type'] == 'select' ) : ?>
            
                <?php 
                
                    $values = get_post_custom( $post->ID );
                    
                    $selected = isset( $values[$custom_field['name']] ) ? esc_attr( $values[$custom_field['name']][0] ) : $custom_field['default_value'];
 
                ?>
            
                <p> 
                    <label for="<?php echo $custom_field['id']?>"><?php echo $custom_field['title']?> </label> 
                    <select name="<?php echo $custom_field['name']?>" id="<?php echo $custom_field['id']?>"> 
                        
                        <option value="" selected="selected">- Select -</option>
                        
                        <?php foreach( $custom_field['value'] as $key => $value ) : ?>
                            <option value="<?php echo $key ?>" <?php selected( $selected, $key ); ?> ><?php echo $value; ?></option> 
                        <?php endforeach; ?>
                        
                    </select> 
                </p> 

            <?php endif; ?>
                
             <?php if( $custom_field['type'] == 'multiple_checkbox' ) : 
                
                    $custom_meta = get_post_meta($post->ID, 'bkb_rkb_user_roles', true);
             
                    
                    if( !is_array($custom_meta) ) {
                        $custom_meta = array();
                        $custom_meta[0] = $custom_field['disable_item'];
                    }
                    
                    
//             echo "<pre>";
//             print_r($custom_meta);
//             echo "</pre>";
                    
//                    echo "<pre>";
//                    print_r($custom_field);
//                    echo "</pre>";
                    echo '<p id="container-'.$custom_field['id'].'">';
                    echo '<label for="'.$custom_field['id'].'" style="margin-bottom: 5px; display:block;">'.$custom_field['title'].'</label><br />';
                    if( count( $custom_field['value'] ) > 0 ) :
                        
                       foreach ( $custom_field['value'] as $key=>$values ) :
                        
                            if ( $key == $custom_field['disable_item'] ) {
                                $bkb_rkb_disable_tag = 'readonly="readonly" onclick="javascript: return false;"
';
                                $disable_note = '<small> (' . $custom_field['disable_note'] .')</small>';
                            } else {
                                $bkb_rkb_disable_tag = "";
                                $disable_note = "";
                            }

                        ?>
                       
                            <input <?php echo $bkb_rkb_disable_tag?> type="checkbox" name="<?php echo $custom_field['name'];?>[]" value="<?php echo $key; ?>" <?php if (in_array($key, $custom_meta)) echo 'checked="checked"'; ?>  /> <?php echo $values; ?> <?php echo $disable_note; ?><br />
                                
                        <?php
                        
                       endforeach;
                        
                    endif;
                    
                    echo '</p>';
                
                ?>  
            
             <?php endif; ?>    
            
            <?php if( $custom_field['type'] == 'checkbox' ) : ?>
            
                <p> 
                    <input type="checkbox" id="my_meta_box_check" name="my_meta_box_check" <?php checked($check, 'on'); ?> />  
                    <label for="my_meta_box_check">Do not check this</label>  
                </p>  
            
             <?php endif; ?>

        <?php
        
            endforeach;
            
    }        

    function save_meta_box_data( $id ) {
        
        global $post;
        
         if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){   
            
             return $post_id;  
             
         } else {
        
            $bkbm_custom_fields = $this->custom_fields;
            
//            echo "<pre>";
//            print_r($bkbm_custom_fields['fields']);
//            echo "</pre>";
//            die();

            foreach( $bkbm_custom_fields['fields'] as $custom_field ) {
                
//                echo "<pre>";
//                print_r($custom_field);
//                echo "</pre>";

                if ( isset( $_POST[$custom_field['name']] ) ) {
                    
                    if ( $custom_field['type'] == "multiple_checkbox") {
                        
                        // Only multiple checkbox field update code.
                        
//                        echo $custom_field['name'] . ' || '.$custom_field['type'];
//                    echo "<pre>";
//                    print_r($_POST[$custom_field['name']]);
//                    echo "</pre>";
//                    echo "<br>";
//
//                    die();
                    $mc_name = str_replace('[]', '', $custom_field['name']);
                  
                    if( count($_POST[$custom_field['name']] ) > 0  ) {
                        
                     update_post_meta($id, $mc_name,  $_POST[$custom_field['name']] );
                     
                    } else {
                  
                        update_post_meta($id, $mc_name,  "" );
                    }
                        
//                        echo "<pre>";
//                        print_r($bpvm_cmb_fields);
//                        echo "</pre>";
//                        die();
                        
                    } else {
                        
                        // Other Fields Update Code.
                        update_post_meta($id, $custom_field['name'], strip_tags( $_POST[$custom_field['name']] ));
                        
                    }

//                    update_post_meta($id, $custom_field['name'], strip_tags( $_POST[$custom_field['name']] ));

                }

            }
            
         }
        
    }
     
}

// Register Custom Meta Box For BWL Pro Related Post Manager

function bkb_rkb_custom_meta_init() {
    
    
    $bkbm_all_user_roles = get_editable_roles();
    $bkbm_filter_user_roles = array();
    
    if(sizeof($bkbm_all_user_roles)>0):
        foreach ($bkbm_all_user_roles as $role_id=>$role_info ) :
            $bkbm_filter_user_roles[$role_id] = $role_info['name'];
        endforeach;
    endif;

//    echo "<pre>";
//    print_r($bkbm_filter_user_roles);
//    echo "</pre>";
//    die();
    $custom_fields= array(
        
        'meta_box_id'           => 'cmb_bkb_rkb', // Unique id of meta box.
        'meta_box_heading'  => __( 'BKB Access Restriction Settings', 'bkb_rkb'), // That text will be show in meta box head section.
        'post_type'               => 'bwl_kb', // define post type. go to register_post_type method to view post_type name.        
        'context'                   => 'normal',
        'priority'                    => 'high',
        'fields'                       => array(
                                                    'bkb_rkb_status'  => array(
                                                                                'title'      => __( 'Restrict KB content access?', 'bkb_rkb'),
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
                                                                                    'title'      => __( 'Select user roles you want to give access: ', 'bwl-pro-voting-manager'),
                                                                                    'id'         => 'bkb_rkb_user_roles',
                                                                                    'name'    => 'bkb_rkb_user_roles',
                                                                                    'type'      => 'multiple_checkbox',
                                                                                    'value'     => $bkbm_filter_user_roles,
                                                                                    'default_value' => 1,
                                                                                    'class'      => 'widefat',
                                                                                    'disable_item' => 'administrator',
                                                                                    'disable_note' => __( 'Administrator always able to access all the KB items', 'bwl-pro-voting-manager'),
                                                                                ),                       
            
            
                                                )
    );
    
    
    new BKB_RKB_Meta_Box( $custom_fields );     
    
}


// META BOX START EXECUTION FROM HERE.

add_action('admin_init', 'bkb_rkb_custom_meta_init');