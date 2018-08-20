function go_admin_check_messages(){
    //ajax call for new messages php function
    //on success, if new messages, print

    var nonce = GO_ADMIN_DATA.nonces.go_admin_messages;

    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_admin_messages'
        },
        success: function( res ) {
            if ( 0 !== res ) {
                jQuery('body').append(res);
                //console.log(res);

            }
        }
    });
}

jQuery( document ).ready( function() {
    go_admin_check_messages();
    setInterval(go_admin_check_messages_focus, 10000);
});

function go_admin_check_messages_focus(){
    if ( document.hasFocus() ) {
        go_admin_check_messages();
    }
}