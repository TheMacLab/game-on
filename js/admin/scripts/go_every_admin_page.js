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

jQuery(document).ready(function(){


    if(jQuery('#parent').val() == -1){
        jQuery('#acf-term-fields').hide();
    }
    else{
        jQuery('#acf-term-fields').show();
        jQuery('h2').show();
    }

    jQuery('#parent').change(function(){
        if(jQuery(this).val() == -1){
            jQuery('#acf-term-fields').hide();
        }
        else{
            jQuery('#acf-term-fields').show();
            jQuery('h2').show();
        }
    });

});


