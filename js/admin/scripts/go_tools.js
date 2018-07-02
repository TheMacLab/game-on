jQuery(document).ready(function(){
    //add on click
    jQuery('#go_tool_update').click(go_tools_ajax);
});

function go_tools_ajax (){
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_upgade4;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_upgade4'
        },
        success: function( res ) {
alert ("Done.  Hope that helps :)")
        }
    });
}