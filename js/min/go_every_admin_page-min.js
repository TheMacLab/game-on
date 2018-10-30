/*
 * Disable submit with enter key, tab to next field instead
*/
jQuery(document).ready(function(){jQuery(".acf-input input,select").bind("keydown",function(e){var t;13===(e.keyCode||e.which)&&(e.preventDefault(),jQuery("input, select, textarea")[jQuery("input,select,textarea").index(this)+1].focus())})});