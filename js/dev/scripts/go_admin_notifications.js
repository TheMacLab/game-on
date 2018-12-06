jQuery( document ).ready( function() {

    if(typeof GO_ADMIN_DATA !== 'undefined') {
        setInterval(go_admin_check_messages, 10000);
        jQuery(window).focus(function () {
            go_admin_check_messages();
        });
    }
});

function go_admin_check_messages(){
    //ajax call for new messages php function
    //on success, if new messages, print
    //console.log("check_messages");
    var nonce = GO_ADMIN_DATA.nonces.go_admin_messages;

    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_admin_messages'
        },
        success: function( res ) {
            console.log(res);
            if ( 0 != res ) {
                jQuery('body').append(res);
                //console.log(res);
            }
        }
    });
}

function go_admin_check_messages_focus(){
    if ( document.hasFocus() ) {
        //go_admin_check_messages();
    }
}
