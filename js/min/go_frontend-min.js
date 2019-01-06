/*
function tinymce_updateCharCounter(el, len) {
    jQuery('.char_count').text(len + '/' + '500');
}

function tinymce_getContentLength() {
    var len = tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
    console.log(len);
    return len;
}
*/
function tinymce_getContentLength_new(){var e=tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText,o=0;if(e){var t=(e=(e=(e=(e=e.replace(/\.\.\./g," ")).replace(/<.[^<>]*?>/g," ").replace(/&nbsp;|&#160;/gi," ")).replace(/(\w+)(&#?[a-z0-9]+;)+(\w+)/i,"$1$3").replace(/&.+?;/g," ")).replace(/[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g,"")).match(/[\w\u2019\x27\-\u00C0-\u1FFF]+/g);t&&(o=t.length)}return o}function go_blog_opener(e){jQuery("#go_hidden_mce").remove(),jQuery(".go_blog_opener").prop("onclick",null).off("click");
//var result_title = jQuery( this ).attr( 'value' );
var o=jQuery(e).attr("blog_post_id"),t,n={action:"go_blog_opener",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_opener,blog_post_id:o};
//console.log(el);
//console.log(blog_post_id);
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:n,cache:!1,success:function(e){
//console.log(results);
//tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
//tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
jQuery.featherlight(e,{afterContent:function(){
//console.log("after");
tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post_lightbox"),tinymce.execCommand("mceAddEditor",!0,"go_blog_post_lightbox")}}),
//tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
//tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
jQuery(".featherlight").css("background","rgba(0,0,0,.8)"),jQuery(".featherlight .featherlight-content").css("width","80%"),console.log("opener2"),jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)})}})}function go_blog_submit(e,o,t){var n=GO_EVERY_PAGE_DATA.nonces.go_blog_submit,_=go_get_tinymce_content_blog(),a=jQuery("#go_blog_title"+o).val();console.log("title: "+a);var r,g,l,c,i={action:"go_blog_submit",_ajax_nonce:n,result:_,result_title:a,blog_post_id:jQuery(e).attr("blog_post_id"),blog_url:jQuery("#go_result_url"+o).val(),blog_media:jQuery("#go_result_media"+o).attr("value"),blog_video:jQuery("#go_result_video"+o).val()};
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:i,cache:!1,success:function(e){console.log("success"),1==t?location.reload():0!=e&&(jQuery("body").append(e),
//console.log(res);
jQuery(".go_loading").remove(),jQuery("#go_save_button"+o).off().one("click",function(e){task_stage_check_input(this,!1,!1)}))}})}function go_get_tinymce_content_blog(){
//console.log("html");
return jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")?jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").val():tinyMCE.activeEditor.getContent()}function go_blog_user_task(e,o){
//jQuery(".go_datatables").hide();
console.log("blogs!");var t=GO_EVERY_PAGE_DATA.nonces.go_blog_user_task;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_blog_user_task",uid:e,task_id:o},success:function(e){jQuery.featherlight(e,{variant:"blogs",afterOpen:function(e){
//console.log("fitvids"); // this contains all related elements
//alert(this.$content.hasClass('true')); // alert class of content
//jQuery("#go_blog_container").fitVids();
go_fit_and_max_only("#go_blog_container")}})}})}