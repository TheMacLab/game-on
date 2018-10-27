
/*
 * Disable submit with enter key, tab to next field instead
*/
jQuery(document).ready(function(){
    jQuery("input,select").bind("keydown", function (e) {
        var keyCode = e.keyCode || e.which;
        if(keyCode === 13) {
            e.preventDefault();
            jQuery('input, select, textarea')
                [jQuery('input,select,textarea').index(this)+1].focus();
        }
    });
});
