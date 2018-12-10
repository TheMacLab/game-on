/*
 * go_tasks_admin.js
 *
 * Where all the functionality for the task edit page goes.
 *
 * @see go_generate_accordion_array() below, it maps all the functions to their appropriate
 *      settings/accordions.
 */
/*
 * Disable sorting of metaboxes

jQuery(document).ready( function($) {
    $('.meta-box-sortables').sortable({
        disabled: true
    });

    $('.postbox .hndle').css('cursor', 'pointer');
});


 */

//fix https://stackoverflow.com/questions/9588025/change-tinymce-editors-height-dynamically
function set_height_mce() {
    jQuery('.go_call_to_action .mce-edit-area iframe').height( 100 );

}

jQuery(document).ready(function(){
    go_hide_child_tax_acfs();
    jQuery('.taxonomy-task_chains #parent, .taxonomy-go_badges #parent').change(function(){
        go_hide_child_tax_acfs();
    });

    setTimeout(set_height_mce, 1000);

});


/*
on the create new taxonomy term page,
this hides the acf stuff until a parent map is selected
 */

function go_hide_child_tax_acfs() {
    if(jQuery('.taxonomy-task_chains #parent, .taxonomy-go_badges #parent').val() == -1){
        //jQuery('#acf-term-fields').hide();
        //jQuery('.acf-field').hide();
        jQuery('.go_child_term').hide();
        jQuery('#go_map_shortcode_id').show();
    }
    else{
        jQuery('.go_child_term').show();
        //jQuery('#acf-term-fields').show();
        //jQuery('.acf-field').show();
        //jQuery('h2').show();
        jQuery('#go_map_shortcode_id').hide();
    }

    var map_id = jQuery('[name="tag_ID"]').val();
    if (map_id == null) {
        jQuery('#go_map_shortcode_id').hide();
    }

    //store item shortcode--add item id to bottom
    var item_id = jQuery('#post_ID').val();
    jQuery('#go_store_item_id .acf-input').html('[go_store id="' + item_id + '"]');

    //map shortcode message
    //var map_id = jQuery('[name="tag_ID"]').val();
    //console.log(map_id);
    var map_name = jQuery('#name').val();
    jQuery('#go_map_shortcode_id .acf-input').html('Place this code in a content area to link directly to this map.<br><br>[go_single_map_link map_id="' + map_id + '"]' + map_name + '[/go_single_map_link]');
    if (map_id == null) {
        jQuery('#go_map_shortcode_id').hide();
    }

}





