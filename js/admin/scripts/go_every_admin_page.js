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

/*
 * Disable submit with enter key, tab to next field instead
*/


jQuery("input,select").bind("keydown", function (e) {
    var keyCode = e.keyCode || e.which;
    if(keyCode === 13) {
        e.preventDefault();
        jQuery('input, select, textarea')
            [jQuery('input,select,textarea').index(this)+1].focus();
    }
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
    }
    else{
        jQuery('.go_child_term').show();
        //jQuery('#acf-term-fields').show();
        //jQuery('.acf-field').show();
        //jQuery('h2').show();
    }

}

jQuery(document).ready(function(){
    go_hide_child_tax_acfs();

    jQuery('.taxonomy-task_chains #parent, .taxonomy-go_badges #parent').change(function(){
        if(jQuery(this).val() == -1){
            //jQuery('#acf-term-fields').hide();
            //jQuery('.acf-field').hide();
            jQuery('.go_child_term').hide();

        }
        else{
            jQuery('.go_child_term').show();
            //jQuery('#acf-term-fields').show();
            //jQuery('.acf-field').show();
            //jQuery('h2').show();
        }
    });


    //store item edit--add item id to bottom
    var item_id = jQuery('#post_ID').val();
    jQuery('#go_store_item_id .acf-input').html('[go_store id="' + item_id + '"]');

});


