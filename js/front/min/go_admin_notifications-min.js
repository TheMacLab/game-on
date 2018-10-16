function go_admin_check_messages(){
//ajax call for new messages php function
//on success, if new messages, print
var e=GO_ADMIN_DATA.nonces.go_admin_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_admin_messages"},success:function(e){
//console.log(res);
0!=e&&jQuery("body").append(e)}})}function go_admin_check_messages_focus(){document.hasFocus()&&go_admin_check_messages()}jQuery(document).ready(function(){setInterval(go_admin_check_messages_focus,1e4),jQuery(window).focus(function(){go_admin_check_messages()})});