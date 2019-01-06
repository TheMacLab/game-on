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
function task_stage_check_input(e,o,t){console.log("button clicked"),
//disable button to prevent double clicks
go_enable_loading(e);
//BUTTON TYPES
//Abandon
//Start Timer
//Continue
//Undo
//Repeat
//Undo Repeat --is this different than just undo
//Continue or Complete button needs to validate input for:
////quizes
///URLs
///passwords
///uploads
//if it passes validation:
////send information to php with ajax and wait for a response
//if response is success
////update totals
///flash rewards and sounds
////update last check
////update current stage and check
//v4 Set variables
var r="";void 0!==jQuery(e).attr("button_type")&&(r=jQuery(e).attr("button_type"));var n="";void 0!==jQuery(e).attr("status")&&(n=jQuery(e).attr("status"));var l="";void 0!==jQuery(e).attr("check_type")&&(l=jQuery(e).attr("check_type"),console.log(l));var a=!1;jQuery("#go_stage_error_msg").text(""),jQuery("#go_blog_error_msg").text("");var _="<h3>Your post was not saved.</h3><ul> ",i=jQuery(e).attr("url_toggle"),g=jQuery(e).attr("video_toggle"),u=jQuery(e).attr("file_toggle"),s=jQuery(e).attr("text_toggle"),c=jQuery(e).attr("blog_suffix"),d,b="#go_result_url"+c,y="#go_result_media"+c,p;
//console.log ("GRV: " + go_result_video);
///v4 START VALIDATE FIELD ENTRIES BEFORE SUBMIT
//if (button_type == 'continue' || button_type == 'complete' || button_type =='continue_bonus' || button_type =='complete_bonus') {
if("blog"==l||"blog_lightbox"==l){//min words and Video field on blog form validation
if("1"==g){var h=jQuery("#go_result_video"+c).attr("value").replace(/\s+/,"");console.log(h),0<h.length?!h.match(/^(http:\/\/|https:\/\/).*\..*$/)||0<h.lastIndexOf("http://")||0<h.lastIndexOf("https://")?(_+="<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>",a=!0):-1==h.search("youtube")&&-1==h.search("vimeo")&&(_+="<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>",a=!0):(_+="<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>",a=!0)}if("1"==s){
//Word count validation
var j=jQuery(e).attr("min_words"),f;//this variable is used in the other functions as well
//alert("min Words: " + min_words);
tinymce_getContentLength_new()<j&&(_+="<li>Your post is not long enough. There must be "+j+" words minimum.</li>",a=!0)}}"password"!==l&&"unlock"!=l||(0<jQuery("#go_result").attr("value").length||(_+="Retrieve the password from "+go_task_data.admin_name+".",a=!0));if("URL"==l||("blog"==l||"blog_lightbox"==l)&&1==i){if("URL"==l)var h=jQuery("#go_result").attr("value").replace(/\s+/,"");else var h=jQuery(b).attr("value").replace(/\s+/,""),v=jQuery(e).attr("required_string");console.log("URL"+h),0<h.length?!h.match(/^(http:\/\/|https:\/\/).*\..*$/)||0<h.lastIndexOf("http://")||0<h.lastIndexOf("https://")?(_+="<li>Enter a valid URL.</li>",a=!0):"blog"!=l&&"blog_lightbox"!=l||-1==h.indexOf(v)&&(_+='<li>Enter a valid URL. The URL must contain "'+v+'".</li>',a=!0):(_+="<li>Enter a valid URL.</li>",go_disable_loading(),a=!0)}if("upload"==l||("blog"==l||"blog_lightbox"==l)&&1==u){if("upload"==l)var Q=jQuery("#go_result").attr("value");else var Q=jQuery(y).attr("value");null==Q&&(_+="<li>Please attach a file.</li>",a=!0)}if("quiz"==l){var m=jQuery(".go_test_list");if(1<=m.length){for(var k=0,x=0;x<m.length;x++){var w="#"+m[x].id+" input:checked",E;1<=jQuery(w).length&&k++}
//if all questions were answered
a=k>=m.length?(go_quiz_check_answers(n,e),!1):(1<m.length?_+="<li>Please answer all questions!</li>":_+="<li>Please answer the question!</li>",!0)}}
//}
if(_+="</ul>",1==a)return 1==o?(console.log("error_stage"),
//flash_error_msg('#go_stage_error_msg');
jQuery("#go_stage_error_msg").append(_),jQuery("#go_stage_error_msg").show()):(console.log("error_blog"),jQuery("#go_blog_error_msg").append(_),jQuery("#go_blog_error_msg").show()),console.log("error validation"),void go_disable_loading();jQuery("#go_stage_error_msg").hide(),jQuery("#go_blog_error_msg").hide(),1==o?task_stage_change(e)://this was a blog save button (not a continue on a stage) so just save without changing stage.  The function only validated the inputs.
go_blog_submit(e,c,t)}
// disables the target stage button, and adds a loading gif to it
function go_enable_loading(e){
//prevent further events with this button
//jQuery('#go_button').prop('disabled',true);
// prepend the loading gif to the button's content, to show that the request is being processed
e.innerHTML='<span class="go_loading"></span>'+e.innerHTML}
// re-enables the stage button, and removes the loading gif
function go_disable_loading(){console.log("oneclick"),jQuery(".go_loading").remove(),jQuery("#go_button").off().one("click",function(e){task_stage_check_input(this,!0,null)}),jQuery("#go_back_button").off().one("click",function(e){
//task_stage_check_input( this, true, null );
task_stage_change(this)}),jQuery("#go_save_button").off().one("click",function(e){task_stage_check_input(this,!1,!1)}),jQuery("#go_bonus_button").off().one("click",function(e){go_update_bonus_loot(this)}),jQuery(".go_str_item").off().one("click",function(e){go_lb_opener(this.id)}),console.log("opener4"),jQuery(".go_blog_opener").off().one("click",function(e){go_blog_opener(this)}),jQuery("#go_blog_submit").off().one("click",function(e){task_stage_check_input(this,!1,!1)}),
//add active class to checks and buttons
jQuery(".progress").closest(".go_checks_and_buttons").addClass("active")}function tinymce_getContentLength_new(){var e=tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText,o=0;if(e){var t=(e=(e=(e=(e=e.replace(/\.\.\./g," ")).replace(/<.[^<>]*?>/g," ").replace(/&nbsp;|&#160;/gi," ")).replace(/(\w+)(&#?[a-z0-9]+;)+(\w+)/i,"$1$3").replace(/&.+?;/g," ")).replace(/[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g,"")).match(/[\w\u2019\x27\-\u00C0-\u1FFF]+/g);t&&(o=t.length)}return o}function go_blog_opener(e){jQuery("#go_hidden_mce").remove(),jQuery(".go_blog_opener").prop("onclick",null).off("click");
//var result_title = jQuery( this ).attr( 'value' );
var o=jQuery(e).attr("blog_post_id"),t,r={action:"go_blog_opener",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_opener,blog_post_id:o};
//console.log(el);
//console.log(blog_post_id);
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:r,cache:!1,success:function(e){
//console.log(results);
//tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
//tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
jQuery.featherlight(e,{afterContent:function(){
//console.log("after");
tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post_lightbox"),tinymce.execCommand("mceAddEditor",!0,"go_blog_post_lightbox")}}),
//tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
//tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
jQuery(".featherlight").css("background","rgba(0,0,0,.8)"),jQuery(".featherlight .featherlight-content").css("width","80%"),console.log("opener2"),jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)})}})}function go_blog_submit(e,o,t){var r=GO_EVERY_PAGE_DATA.nonces.go_blog_submit,n=go_get_tinymce_content_blog(),l=jQuery("#go_blog_title"+o).val();console.log("title: "+l);var a,_,i,g,u={action:"go_blog_submit",_ajax_nonce:r,result:n,result_title:l,blog_post_id:jQuery(e).attr("blog_post_id"),blog_url:jQuery("#go_result_url"+o).val(),blog_media:jQuery("#go_result_media"+o).attr("value"),blog_video:jQuery("#go_result_video"+o).val()};
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:u,cache:!1,success:function(e){console.log("success"),1==t?location.reload():0!=e&&(jQuery("body").append(e),
//console.log(res);
jQuery(".go_loading").remove(),jQuery("#go_save_button"+o).off().one("click",function(e){task_stage_check_input(this,!1,!1)}))}})}function go_get_tinymce_content_blog(){
//console.log("html");
return jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")?jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").val():tinyMCE.activeEditor.getContent()}function go_blog_user_task(e,o){
//jQuery(".go_datatables").hide();
console.log("blogs!");var t=GO_EVERY_PAGE_DATA.nonces.go_blog_user_task;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_blog_user_task",uid:e,task_id:o},success:function(e){jQuery.featherlight(e,{variant:"blogs",afterOpen:function(e){
//console.log("fitvids"); // this contains all related elements
//alert(this.$content.hasClass('true')); // alert class of content
//jQuery("#go_blog_container").fitVids();
go_fit_and_max_only("#go_blog_container")}})}})}jQuery(document).ready(function(){
//add onclick to blog edit buttons
//console.log("opener3");
jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)}),jQuery("#go_hidden_mce").remove(),jQuery("#go_hidden_mce_edit").remove()});