function go_update_bonus_loot(){jQuery("#go_bonus_loot").html('<span class="go_loading"></span>');var e=GO_EVERY_PAGE_DATA.nonces.go_update_bonus_loot,o=go_task_data.ID;
//alert (post_id);
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_update_bonus_loot",post_id:o},success:function(e){console.log("Bonus Loot"),console.log(e),jQuery("#go_bonus_loot").remove(),jQuery("#go_wrapper").append(e)}})}
//For the Timer (v4)
function getTimeRemaining(e){var o=Date.parse(e)-Date.parse(new Date),r=Math.floor(o/1e3%60),t=Math.floor(o/1e3/60%60),s=Math.floor(o/36e5%24),_;return{total:o,days:Math.floor(o/864e5),hours:s,minutes:t,seconds:r}}
//Initializes the new timer (v4)
function initializeClock(e,r){function o(){var e=getTimeRemaining(r),o;(e.days=Math.max(0,e.days),s.innerHTML=e.days,e.hours=Math.max(0,e.hours),_.innerHTML=("0"+e.hours).slice(-2),e.minutes=Math.max(0,e.minutes),a.innerHTML=("0"+e.minutes).slice(-2),e.seconds=Math.max(0,e.seconds),n.innerHTML=("0"+e.seconds).slice(-2),e.total=0)&&(clearInterval(u),new Audio(PluginDir.url+"media/sounds/airhorn.mp3").play())}var t=document.getElementById(e),s=t.querySelector(".days"),_=t.querySelector(".hours"),a=t.querySelector(".minutes"),n=t.querySelector(".seconds");o();var i=getTimeRemaining(r),g=i.total;if(console.log(i.total),0<g)var u=setInterval(o,1e3)}function go_timer_abandon(){var e=go_task_data.redirectURL;
//window.location = $homeURL;
window.location=e}function flash_error_msg(e){var o=jQuery(e).css("background-color");void 0===typeof o&&(o="white"),jQuery(e).animate({color:o},200,function(){jQuery(e).animate({color:"red"},200)})}
// disables the target stage button, and adds a loading gif to it
function go_enable_loading(e){
//prevent further events with this button
//jQuery('#go_button').prop('disabled',true);
// prepend the loading gif to the button's content, to show that the request is being processed
e.innerHTML='<span class="go_loading"></span>'+e.innerHTML}
// re-enables the stage button, and removes the loading gif
function go_disable_loading(){console.log("oneclick"),jQuery(".go_loading").remove(),jQuery("#go_button").off().one("click",function(e){task_stage_check_input(this)}),jQuery("#go_back_button").off().one("click",function(e){task_stage_check_input(this)}),jQuery("#go_bonus_button").off().one("click",function(e){go_update_bonus_loot(this)}),jQuery(".go_str_item").off().one("click",function(e){go_lb_opener(this.id)}),jQuery(".go_blog_opener").off().one("click",function(e){go_blog_opener(this)}),
//add active class to checks and buttons
jQuery(".progress").closest(".go_checks_and_buttons").addClass("active")}function task_stage_check_input(e){console.log("button clicked"),
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
var o="";void 0!==jQuery(e).attr("button_type")&&(o=jQuery(e).attr("button_type"));var r="";void 0!==jQuery(e).attr("status")&&(r=jQuery(e).attr("status"));var t="",s;
///v4 START VALIDATE FIELD ENTRIES BEFORE SUBMIT
if(void 0!==jQuery(e).attr("check_type")&&(t=jQuery(e).attr("check_type")),"continue"==o||"complete"==o||"continue_bonus"==o||"complete_bonus"==o)if("password"===t||"unlock"==t){if(!(0<jQuery("#go_result").attr("value").length)){jQuery("#go_stage_error_msg").show();var _="Retrieve the password from "+go_task_data.admin_name+".";return jQuery("#go_stage_error_msg").text()!=_?jQuery("#go_stage_error_msg").text(_):flash_error_msg("#go_stage_error_msg"),void go_disable_loading()}}else if("URL"==t){var a=jQuery("#go_result").attr("value").replace(/\s+/,"");if(!(0<a.length)){jQuery("#go_stage_error_msg").show();var _="Enter a valid URL.";return jQuery("#go_stage_error_msg").text()!=_?jQuery("#go_stage_error_msg").text(_):flash_error_msg("#go_stage_error_msg"),void go_disable_loading()}if(!a.match(/^(http:\/\/|https:\/\/).*\..*$/)||0<a.lastIndexOf("http://")||0<a.lastIndexOf("https://")){jQuery("#go_stage_error_msg").show();var _="Enter a valid URL.";return jQuery("#go_stage_error_msg").text()!=_?jQuery("#go_stage_error_msg").text(_):flash_error_msg("#go_stage_error_msg"),void go_disable_loading()}var n=!0}else if("upload"==t){var i;if(null==jQuery("#go_result").attr("value")){jQuery("#go_stage_error_msg").show();var _="Please attach a file.";return jQuery("#go_stage_error_msg").text()!=_?jQuery("#go_stage_error_msg").text(_):flash_error_msg("#go_stage_error_msg"),void go_disable_loading()}}else if("quiz"==t){var g=jQuery(".go_test_list");if(1<=g.length){for(var u=0,l=0;l<g.length;l++){var c="#"+g[l].id+" input:checked",d;1<=jQuery(c).length&&u++}
//if all questions were answered
return u>=g.length?go_quiz_check_answers(r,e):1<g.length?(jQuery("#go_stage_error_msg").show(),"Please answer all questions!"!=jQuery("#go_stage_error_msg").text()?jQuery("#go_stage_error_msg").text("Please answer all questions!"):flash_error_msg("#go_stage_error_msg")):(jQuery("#go_stage_error_msg").show(),"Please answer the question!"!=jQuery("#go_stage_error_msg").text()?jQuery("#go_stage_error_msg").text("Please answer the question!"):flash_error_msg("#go_stage_error_msg")),void go_disable_loading()}}task_stage_change(e)}function task_stage_change(e){console.log("change");
//v4 Set variables
var o="";void 0!==jQuery(e).attr("button_type")&&(o=jQuery(e).attr("button_type"));
//console.log(button_type);
var r="";void 0!==jQuery(e).attr("status")&&(r=jQuery(e).attr("status"));var t="";void 0!==jQuery(e).attr("check_type")&&(t=jQuery(e).attr("check_type"));var s=jQuery("#go_admin_bar_progress_bar").css("background-color"),_=jQuery("#go_result").attr("value");if("blog"==t&&"undo_last_bonus"!=o){
//result = tinyMCE.activeEditor.getContent();
_=go_get_tinymce_content_check();var a=jQuery("#go_result_title_check").val(),n=jQuery("#go_result_title_check").data("blog_post_id")}else var a=null;jQuery.ajax({type:"POST",data:{_ajax_nonce:go_task_data.go_task_change_stage,action:"go_task_change_stage",post_id:go_task_data.ID,user_id:go_task_data.userID,status:r,button_type:o,check_type:t,result:_,result_title:a,blog_post_id:n},success:function(e){console.log("success");
//console.log(raw);
// parse the raw response to get the desired JSON
var o={};try{var o=JSON.parse(e)}catch(e){o={json_status:"101",timer_start:"",button_type:"",time_left:"",html:"",redirect:"",rewards:{gold:0}}}
//console.log(res.html);
//alert(json_status);
if("101"===Number.parseInt(o.json_status)){console.log(101),jQuery("#go_stage_error_msg").show();var r="Server Error.";jQuery("#go_stage_error_msg").text()!=r?jQuery("#go_stage_error_msg").text(r):flash_error_msg("#go_stage_error_msg")}else if(302===Number.parseInt(o.json_status))console.log(302),window.location=o.location;else if("refresh"==o.json_status)location.reload();else if("bad_password"==o.json_status){jQuery("#go_stage_error_msg").show();var r="Invalid password.";jQuery("#go_stage_error_msg").text()!=r?jQuery("#go_stage_error_msg").text(r):flash_error_msg("#go_stage_error_msg"),go_disable_loading()}else{if("undo"==o.button_type)jQuery("#go_wrapper div").last().hide(),jQuery("#go_wrapper > div").slice(-3).hide("slow",function(){jQuery(this).remove()});else if("undo_last"==o.button_type)jQuery("#go_wrapper div").last().hide(),jQuery("#go_wrapper > div").slice(-2).hide("slow",function(){jQuery(this).remove()});else if("continue"==o.button_type||"complete"==o.button_type)jQuery("#go_wrapper > div").slice(-1).hide("slow",function(){jQuery(this).remove()});else if("show_bonus"==o.button_type)jQuery("#go_buttons").remove(),
//remove active class to checks and buttons
jQuery(".go_checks_and_buttons").removeClass("active");else if("continue_bonus"==o.button_type||"complete_bonus"==o.button_type)jQuery("#go_wrapper > div").slice(-1).hide("slow",function(){jQuery(this).remove()});else if("undo_bonus"==o.button_type)jQuery("#go_wrapper > div").slice(-2).hide("slow",function(){jQuery(this).remove()});else if("undo_last_bonus"==o.button_type)jQuery("#go_wrapper > div").slice(-1).hide("slow",function(){jQuery(this).remove()});else if("abandon_bonus"==o.button_type)jQuery("#go_wrapper > div").slice(-3).remove();else if("abandon"==o.button_type)window.location=o.redirect;else if("timer"==o.button_type){var t;jQuery("#go_wrapper > div").slice(-2).hide("slow",function(){jQuery(this).remove()}),new Audio(PluginDir.url+"media/sounds/airhorn.mp3").play()}go_append(o),
//Pop up currency awards
jQuery("#notification").html(o.notification),jQuery("#go_admin_bar_progress_bar").css({"background-color":s}),jQuery("#go_button").ready(function(){
//check_locks();
})}}})}function go_get_tinymce_content_check(){return console.log("html"),jQuery("#wp-go_blog_post-wrap .wp-editor-area").is(":visible")?jQuery("#wp-go_blog_post-wrap .wp-editor-area").val():(console.log("visual"),tinyMCE.activeEditor.getContent())}
//This isn't currently used, but saving just in case . . .
function go_mce_reset(){
// remove existing editor instance
tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post"),tinymce.execCommand("mceAddEditor",!0,"go_blog_post"),tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post_edit"),tinymce.execCommand("mceAddEditor",!0,"go_blog_post_edit")}function go_append(e){
//jQuery( res.html ).addClass('active');
jQuery(e.html).appendTo("#go_wrapper").stop().hide().show("slow").promise().then(function(){
// Animation complete
go_Vids_Fit_and_Box("body"),go_make_clickable(),go_disable_loading(),
//go_mce();
// remove existing editor instance, and add new one
tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post"),tinymce.execCommand("mceAddEditor",!0,"go_blog_post")})}
// Makes it so you can press return and enter content in a field
function go_make_clickable(){
//Make URL button clickable by clicking enter when field is in focus
jQuery(".clickable").keyup(function(e){
// 13 is ENTER
13===e.which&&
// do something
jQuery("#go_button").click()})}
//This updates the admin view option and refreshes the page.
function go_update_admin_view(e){jQuery.ajax({type:"POST",url:MyAjax.ajaxurl,data:{_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_update_admin_view,action:"go_update_admin_view",
//user_id: go_task_data.userID,
go_admin_view:e},success:function(e){location.reload()},error:function(e){console.log(e),console.log("fail")}})}function go_quiz_check_answers(e,_){
//if ( jQuery( ".go_test_list" ).length != 0) {
var a=jQuery(".go_test_list"),n=a.length;if(jQuery(".go_test_list :checked").length>=n){
//var test_list = jQuery( ".go_test_list" );
//var list_size = test_list.length;
var o=[];if(1<jQuery(".go_test_list").length){for(var r=[],t=0;t<n;t++){
// figure out the type of each test
var s=a[t].children[1].children[0].type;o.push(s);
// get the checked inputs of each test
var i="#"+a[t].id+" :checked",g=jQuery(i);if("radio"==s)
// push indiviudal answers to the choice_array
null!=g[0]&&r.push(g[0].value);else if("checkbox"==s){for(var u=[],l=0;l<g.length;l++)u.push(g[l].value);var c=u.join("### ");r.push(c)}}var d=r.join("#### "),y=o.join("### ")}else{var m=jQuery(".go_test_list li input:checked"),y;if("radio"==(y=jQuery(".go_test_list li input").first().attr("type")))var d=m[0].value;else if("checkbox"==y){for(var d=[],l=0;l<m.length;l++)d.push(m[l].value);d=d.join("### ")}}}jQuery.ajax({type:"POST",data:{_ajax_nonce:go_task_data.go_unlock_stage,action:"go_unlock_stage",task_id:go_task_data.ID,user_id:go_task_data.userID,list_size:n,chosen_answer:d,type:y,status:e},success:function(e){if("refresh"==e)location.reload();else{if(1==e)return jQuery(".go_test_container").hide("slow"),jQuery("#test_failure_msg").hide("slow"),jQuery(".go_test_submit_div").hide("slow"),jQuery(".go_wrong_answer_marker").hide(),jQuery("#go_stage_error_msg").hide(),task_stage_change(_),0;//return a mod of 0
if(0==e)
//go_disable_loading();
return jQuery("#go_stage_error_msg").show(),jQuery("#go_stage_error_msg").text("Wrong answer, try again!"),1;//return a mod of 1
if("string"==typeof e&&1<n){console.log("response"+e);
//var failed_count = failed_questions.length;
//console.log (failed_count);
for(var o=e.split(", "),r=0;r<a.length;r++){var t="#"+a[r].id;-1===jQuery.inArray(t,o)?(jQuery(t+" .go_wrong_answer_marker").is(":visible")&&jQuery(t+" .go_wrong_answer_marker").hide(),jQuery(t+" .go_correct_answer_marker").is(":visible")||jQuery(t+" .go_correct_answer_marker").show()):(jQuery(t+" .go_correct_answer_marker").is(":visible")&&jQuery(t+" .go_correct_answer_marker").hide(),jQuery(t+" .go_wrong_answer_marker").is(":visible")||jQuery(t+" .go_wrong_answer_marker").show())}var s;
//go_disable_loading();
return void("Wrong answer, try again!"!=jQuery("#go_stage_error_msg").text()?(jQuery("#go_stage_error_msg").show(),jQuery("#go_stage_error_msg").text("Wrong answer, try again!")):flash_error_msg("#go_stage_error_msg"))}}}})}jQuery(document).ready(function(){
//add onclick to blog edit buttons
//jQuery( document ).ready( function() {
jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)}),
//});
jQuery.ajaxSetup({url:go_task_data.url+="/wp-admin/admin-ajax.php"}),go_make_clickable(),jQuery(".go_stage_message").show(),
// remove existing editor instance
tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post"),tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post_edit"),jQuery("#go_hidden_mce").remove(),jQuery("#go_hidden_mce_edit").remove();var e=jQuery("#go_select_admin_view").val();console.log(e),"all"!=e&&(
//add onclick to continue buttons
jQuery("#go_button").one("click",function(e){task_stage_check_input(this)}),jQuery("#go_back_button").one("click",function(e){task_stage_check_input(this)})),
//add onclick to bonus loot buttons
jQuery("#go_bonus_button").one("click",function(e){go_update_bonus_loot(this)}),
//add active class to checks and buttons
jQuery(".progress").closest(".go_checks_and_buttons").addClass("active"),jQuery("#go_admin_override").appendTo(".go_locks"),jQuery("#go_admin_override").click(function(){jQuery(".go_password").show()})});