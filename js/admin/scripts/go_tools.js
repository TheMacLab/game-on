jQuery(document).ready(function(){
    //add on click
    jQuery('#go_tool_update').click(go_update_go_ajax);
    jQuery('#go_tool_update_no_loot').click(go_update_go_ajax_no_task_loot);
});

function go_update_go_ajax (){
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_upgade4;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_upgade4',
            loot: true
        },
        success: function( res ) {
alert ("Done.  Hope that helps :)")
        }
    });
}


function go_update_go_ajax_no_task_loot (){
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_upgade4;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_upgade4',
            loot: false
        },
        success: function( res ) {
            alert ("Done.  Hope that helps :)")
        }
    });
}