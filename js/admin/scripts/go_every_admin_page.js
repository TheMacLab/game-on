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
        $('input, select, textarea')
            [$('input,select,textarea').index(this)+1].focus();
    }
});



