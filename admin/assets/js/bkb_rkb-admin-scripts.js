(function($) {


    /*------------------------------ Voting Date Range Picker ---------------------------------*/
    
    if ( $("#bkb_rkb_status").length && $("#container-bkb_rkb_user_roles").length ) {
        
         var $bkb_rkb_status = $("#bkb_rkb_status"),
              $bkb_rkb_user_roles = $("#container-bkb_rkb_user_roles");
              
              
        if ( $bkb_rkb_status.val() == 1 ) {
            $bkb_rkb_user_roles.show("slow");
        } else {
            $bkb_rkb_user_roles.hide("slow");
        }
        
        // On change action

        $bkb_rkb_status.on("change",function(){
            
            if ($(this).val() == 1) {
                $bkb_rkb_user_roles.show("slow");
            } else {
                $bkb_rkb_user_roles.hide("slow");
            }
            
        });
        
    }
    
    /*------------------------------ Quick/Bulk Edit Section ---------------------------------*/
    
    if (typeof (inlineEditPost) == 'undefined') {
        return '';
    }
    
            
    // we create a copy of the WP inline edit post function
    var $wp_inline_edit = inlineEditPost.edit;

    // and then we overwrite the function with our own code
    inlineEditPost.edit = function(id) {

        // "call" the original WP edit function
        // we don't want to leave WordPress hanging
        $wp_inline_edit.apply(this, arguments);

        // now we take care of our business

        // get the post ID

        var $post_id = 0;

        if (typeof (id) == 'object')
            $post_id = parseInt(this.getId(id));

        if ($post_id > 0) {

            // define the edit row
            var $edit_row = $('#edit-' + $post_id);
           
            // Display Status

            var bkb_rkb_status = $('#bkb_rkb_status-' + $post_id).data('status_code');
        
            $edit_row.find('select[name="bkb_rkb_status"]').val((bkb_rkb_status == 1) ? 1 : 0);

        }

    };

    /*------------------------------ Bulk Edit Settings ---------------------------------*/
    
    $('#bulk_edit').on('click', function() {

        // define the bulk edit row
        var $bulk_row = $('#bulk-edit');
        // get the selected post ids that are being edited
        var $post_ids = new Array();
        $bulk_row.find('#bulk-titles').children().each(function() {
            $post_ids.push($(this).attr('id').replace(/^(ttle)/i, ''));
        });

        // get the $bkb_display_status

        var $bkb_rkb_status = $bulk_row.find('select[name="bkb_rkb_status"]').val();
//        alert(" "+$bkb_rkb_status);
        // save the data
        $.ajax({
            url: ajaxurl, // this is a variable that WordPress has already defined for us
            type: 'POST',
            async: false,
            cache: false,
            data: {
                action: 'manage_wp_posts_using_bulk_edit_rkb', // this is the name of our WP AJAX function that we'll set up next
                post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
                bkb_rkb_status: $bkb_rkb_status
            }
        });

    });

}(jQuery));