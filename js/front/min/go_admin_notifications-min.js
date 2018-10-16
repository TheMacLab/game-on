function go_admin_check_messages(){
//ajax call for new messages php function
//on success, if new messages, print
var a=GO_ADMIN_DATA.nonces.go_admin_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_admin_messages"},success:function(a){
//console.log(res);
0!=a&&jQuery("body").append(a)}})}
/*
jQuery( document ).ready( function() {
    setInterval(go_admin_check_messages_focus, 10000);
    jQuery(window).focus(function() {
        go_admin_check_messages();
    });
});

function go_admin_check_messages_focus(){
    if ( document.hasFocus() ) {
        go_admin_check_messages();
    }
}
*/