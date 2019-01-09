/*
  SortTable
  version 2
  7th April 2007
  Stuart Langridge, http://www.kryogenix.org/code/browser/sorttable/

  Instructions:
  Download this file
  Add <script src="sorttable.js"></script> to your HTML
  Add class="sortable" to any table you'd like to make sortable
  Click on the headers to sort

  Thanks to many, many people for contributions and suggestions.
  Licenced as X11: http://www.kryogenix.org/code/browser/licence.html
  This basically means: do what you want with it.
*/
// written by Dean Edwards, 2005
// with input from Tino Zijdel, Matthias Miller, Diego Perini
// http://dean.edwards.name/weblog/2005/10/add-event/
function dean_addEvent(e,t,o){if(e.addEventListener)e.addEventListener(t,o,!1);else{
// assign each event handler a unique ID
o.$$guid||(o.$$guid=dean_addEvent.guid++),
// create a hash table of event types for the element
e.events||(e.events={});
// create a hash table of event handlers for each element/event pair
var r=e.events[t];r||(r=e.events[t]={},
// store the existing event handler (if there is one)
e["on"+t]&&(r[0]=e["on"+t])),
// store the event handler in the hash table
r[o.$$guid]=o,
// assign a global event handler to do all the work
e["on"+t]=handleEvent}}function removeEvent(e,t,o){e.removeEventListener?e.removeEventListener(t,o,!1):
// delete the event handler from the hash table
e.events&&e.events[t]&&delete e.events[t][o.$$guid]}function handleEvent(e){var t=!0;
// grab the event object (IE uses a global event object)
e=e||fixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);
// get a reference to the hash table of event handlers
var o=this.events[e.type];
// execute each event handler
for(var r in o)this.$$handleEvent=o[r],!1===this.$$handleEvent(e)&&(t=!1);return t}function fixEvent(e){
// add W3C standard event methods
return e.preventDefault=fixEvent.preventDefault,e.stopPropagation=fixEvent.stopPropagation,e}
/*
 * go_tasks_admin.js
 *
 * Where all the functionality for the task edit page goes.
 *
 * @see go_generate_accordion_array() below, it maps all the functions to their appropriate
 *      settings/accordions.
 */
/*
 * Disable sorting of metaboxes

jQuery(document).ready( function($) {
    $('.meta-box-sortables').sortable({
        disabled: true
    });

    $('.postbox .hndle').css('cursor', 'pointer');
});


 */
//fix https://stackoverflow.com/questions/9588025/change-tinymce-editors-height-dynamically
function set_height_mce(){jQuery(".go_call_to_action .mce-edit-area iframe").height(100)}
/*
on the create new taxonomy term page,
this hides the acf stuff until a parent map is selected
 */
function go_hide_child_tax_acfs(){-1==jQuery(".taxonomy-task_chains #parent, .taxonomy-go_badges #parent").val()?(
//jQuery('#acf-term-fields').hide();
//jQuery('.acf-field').hide();
jQuery(".go_child_term").hide(),jQuery("#go_map_shortcode_id").show()):(jQuery(".go_child_term").show(),
//jQuery('#acf-term-fields').show();
//jQuery('.acf-field').show();
//jQuery('h2').show();
jQuery("#go_map_shortcode_id").hide());var e=jQuery('[name="tag_ID"]').val();null==e&&jQuery("#go_map_shortcode_id").hide();
//store item shortcode--add item id to bottom
var t=jQuery("#post_ID").val();jQuery("#go_store_item_id .acf-input").html('[go_store id="'+t+'"]');
//map shortcode message
//var map_id = jQuery('[name="tag_ID"]').val();
//console.log(map_id);
var o=jQuery("#name").val();jQuery("#go_map_shortcode_id .acf-input").html('Place this code in a content area to link directly to this map.<br><br>[go_single_map_link map_id="'+e+'"]'+o+"[/go_single_map_link]"),null==e&&jQuery("#go_map_shortcode_id").hide()}function go_validate_growth(){var e=jQuery("#go_levels_growth").find("input").val();isNaN(e)?jQuery("#go_levels_growth").find("input").val(Go_orgGrowth):Go_orgGrowth=e}function go_level_names(){var e=document.getElementById("go_levels_repeater").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length,t,o,r;t=0,o="",jQuery(".go_levels_repeater_names").find("input").each(function(){t++,r=o,o=jQuery(this).val(),
//console.log (thisName);
//console.log (prevName);
1<t&&t!=e&&(console.log("Row:"+t),null!=o&&""!=o||(console.log("empty:"+t),console.log(o),jQuery(this).val(r),o=r))})}function go_levels_limit_each(){var r=document.getElementById("go_levels_repeater").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length,n=Go_orgGrowth,a;
//var growth = jQuery('#go_levels_growth').find('input').val();
a=0,jQuery(".go_levels_repeater_numbers").find("input").each(function(){
//console.log('-----------row'+ row);
var e;a++,e=jQuery(this).val()||0,e=parseInt(e);var t=jQuery(this).closest(".acf-row").prev().find(".go_levels_repeater_numbers").find("input").val()||0;t=parseInt(t);var o=jQuery(this).closest(".acf-row").next().find(".go_levels_repeater_numbers").find("input").val()||0;o=parseInt(o),
//console.log('prev' + prevVal);
//console.log('this' + thisVal);
//console.log('next' + nextVal);
1===a?(//the first row
jQuery(this).attr({max:0,// substitute your own
min:0}),jQuery(this).val(0)):a===r-1?(//the last row
jQuery(this).attr({min:t}),jQuery(this).removeAttr("max"),e<t&&jQuery(this).val(Math.floor(t*n))):a===r||(//all the rows in teh middle
e<o&&jQuery(this).attr({min:t,max:o}),o<e&&jQuery(this).attr({min:t}),e<t&&jQuery(this).val(t*n)
/*
            else if (thisVal > nextVal && nextVal != 0) {

                jQuery(this).val(nextVal);
                //console.log('Middle Row: Value to high.  Set to ' + nextVal);
            }
            else {
                //console.log('middle Value:' + thisVal);
            }
            */)})}
// Makes it so you can press return and enter content in a field
function go_make_store_clickable(){
//Make URL button clickable by clicking enter when field is in focus
jQuery(".clickable").keyup(function(e){
// 13 is ENTER
13===e.which&&jQuery("#go_store_pass_button").click()})}
//open the lightbox for the store items
function go_lb_opener(r){if(jQuery("#light").css("display","block"),jQuery(".go_str_item").prop("onclick",null).off("click"),!jQuery.trim(jQuery("#lb-content").html()).length){var e=r,t,o={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:e};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:o,beforeSend:function(){jQuery("#lb-content").append('<div class="go-lb-loading"></div>')},cache:!1,success:function(e){console.log("success"),console.log(e);var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}if(jQuery("#lb-content").innerHTML="",jQuery("#lb-content").html(""),jQuery.featherlight(t.html,{variant:"store",afterOpen:function(e){console.log("store-fitvids3"),
//jQuery("#go_store_description").fitVids();
//go_fit_and_max_only("#go_store_description");
go_fit_and_max_only("#go_store_description")}}),"101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var o="Server Error.";jQuery("#go_store_error_msg").text()!=o?jQuery("#go_store_error_msg").text(o):flash_error_msg_store("#go_store_error_msg")}else 302===Number.parseInt(t.json_status)&&(console.log(302),window.location=t.location);jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)}),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(r)}),go_max_purchase_limit()}})}}
//called when the "buy" button is clicked.
function goBuytheItem(t,e){var r=GO_BUY_ITEM_DATA.nonces.go_buy_item,n=GO_BUY_ITEM_DATA.userID;jQuery(document).ready(function(o){var e={_ajax_nonce:r,action:"go_buy_item",the_id:t,qty:o("#go_qty").val(),user_id:n};o.ajax({url:MyAjax.ajaxurl,type:"POST",data:e,beforeSend:function(){o("#golb-fr-buy").innerHTML="",o("#golb-fr-buy").html(""),o("#golb-fr-buy").append('<div id="go-buy-loading" class="buy_gold"></div>')},success:function(e){var t={};try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:"101 Error: Please try again."}}-1!==e.indexOf("Error")?o("#light").html(e):o("#light").html(t.html)}})})}function flash_error_msg_store(e){var t=jQuery(e).css("background-color");void 0===typeof t&&(t="white"),jQuery(e).animate({color:t},200,function(){jQuery(e).animate({color:"red"},200)})}function go_store_password(r){var e;if(!(0<jQuery("#go_store_password_result").attr("value").length)){jQuery("#go_store_error_msg").show();var t="Please enter a password.";return jQuery("#go_store_error_msg").text()!=t?jQuery("#go_store_error_msg").text(t):flash_error_msg_store("#go_store_error_msg"),void jQuery("#go_store_pass_button").one("click",function(e){go_store_password(r)})}var o=jQuery("#go_store_password_result").attr("value");if(jQuery("#light").css("display","block"),!jQuery.trim(jQuery("#lb-content").html()).length){var n=r,a,s={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:n,skip_locks:!0,result:o};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:s,cache:!1,success:function(e){var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}if("101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var o="Server Error.";jQuery("#go_store_error_msg").text()!=o?jQuery("#go_store_error_msg").text(o):flash_error_msg_store("#go_store_error_msg")}else if(302===Number.parseInt(t.json_status))console.log(302),window.location=t.location;else if("bad_password"==t.json_status){jQuery("#go_store_error_msg").show();var o="Invalid password.";jQuery("#go_store_error_msg").text()!=o?jQuery("#go_store_error_msg").text(o):flash_error_msg_store("#go_store_error_msg"),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(r)})}else jQuery("#go_store_pass_button").one("click",function(e){go_store_password(r)}),jQuery("#go_store_lightbox_container").hide(),jQuery(".featherlight-content").html(t.html),go_max_purchase_limit()}})}}function go_max_purchase_limit(){window.go_purchase_limit=jQuery("#golb-fr-purchase-limit").attr("val");var e=go_purchase_limit;jQuery("#go_qty").spinner({max:e,min:1,stop:function(){jQuery(this).change()}}),go_make_store_clickable(),jQuery("#go_store_admin_override").one("click",function(e){jQuery(".go_store_lock").show(),jQuery("#go_store_admin_override").hide(),go_make_store_clickable()})}function go_count_item(e){var t=GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:{_ajax_nonce:t,action:"go_get_purchase_count",item_id:e},success:function(e){if(-1!==e){var t=e.toString();jQuery("#golb-purchased").html("Quantity purchased: "+t)}}})}function task_stage_check_input(e,t,o){console.log("button clicked"),
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
var r="";void 0!==jQuery(e).attr("button_type")&&(r=jQuery(e).attr("button_type"));var n="";void 0!==jQuery(e).attr("status")&&(n=jQuery(e).attr("status"));var a="";void 0!==jQuery(e).attr("check_type")&&(a=jQuery(e).attr("check_type"),console.log(a));var s=!1;jQuery("#go_stage_error_msg").text(""),jQuery("#go_blog_error_msg").text("");var i="<h3>Your post was not saved.</h3><ul> ",l=jQuery(e).attr("url_toggle"),_=jQuery(e).attr("video_toggle"),c=jQuery(e).attr("file_toggle"),u=jQuery(e).attr("text_toggle"),g=jQuery(e).attr("blog_suffix"),d="#go_result_video"+g,h="#go_result_url"+g,m="#go_result_media"+g,f;
///v4 START VALIDATE FIELD ENTRIES BEFORE SUBMIT
//if (button_type == 'continue' || button_type == 'complete' || button_type =='continue_bonus' || button_type =='complete_bonus') {
if(console.log("suffix: "+g),"blog"==a||"blog_lightbox"==a){//min words and Video field on blog form validation
if("1"==_){var p=jQuery(d).attr("value").replace(/\s+/,"");console.log(p),0<p.length?!p.match(/^(http:\/\/|https:\/\/).*\..*$/)||0<p.lastIndexOf("http://")||0<p.lastIndexOf("https://")?(i+="<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>",s=!0):-1==p.search("youtube")&&-1==p.search("vimeo")&&(i+="<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>",s=!0):(i+="<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>",s=!0)}if("1"==u){
//Word count validation
var y=jQuery(e).attr("min_words"),b;//this variable is used in the other functions as well
//alert("min Words: " + min_words);
tinymce_getContentLength_new()<y&&(i+="<li>Your post is not long enough. There must be "+y+" words minimum.</li>",s=!0)}}"password"!==a&&"unlock"!=a||(0<jQuery("#go_result").attr("value").length||(i+="Retrieve the password from "+go_task_data.admin_name+".",s=!0));if("URL"==a||("blog"==a||"blog_lightbox"==a)&&1==l){if("URL"==a)var p=jQuery("#go_result").attr("value").replace(/\s+/,"");else var p=jQuery(h).attr("value").replace(/\s+/,""),v=jQuery(e).attr("required_string");console.log("URL"+p),0<p.length?!p.match(/^(http:\/\/|https:\/\/).*\..*$/)||0<p.lastIndexOf("http://")||0<p.lastIndexOf("https://")?(i+="<li>Enter a valid URL.</li>",s=!0):"blog"!=a&&"blog_lightbox"!=a||-1==p.indexOf(v)&&(i+='<li>Enter a valid URL. The URL must contain "'+v+'".</li>',s=!0):(i+="<li>Enter a valid URL.</li>",go_disable_loading(),s=!0)}if("upload"==a||("blog"==a||"blog_lightbox"==a)&&1==c){if("upload"==a)var j=jQuery("#go_result").attr("value");else var j=jQuery(m).attr("value");null==j&&(i+="<li>Please attach a file.</li>",s=!0)}if("quiz"==a){var Q=jQuery(".go_test_list");if(1<=Q.length){for(var w=0,E=0;E<Q.length;E++){var x="#"+Q[E].id+" input:checked",k;1<=jQuery(x).length&&w++}
//if all questions were answered
s=w>=Q.length?(go_quiz_check_answers(n,e),!1):(1<Q.length?i+="<li>Please answer all questions!</li>":i+="<li>Please answer the question!</li>",!0)}}
//}
if(i+="</ul>",1==s)return 1==t?(console.log("error_stage"),
//flash_error_msg('#go_stage_error_msg');
jQuery("#go_stage_error_msg").append(i),jQuery("#go_stage_error_msg").show()):(console.log("error_blog"),jQuery("#go_blog_error_msg").append(i),jQuery("#go_blog_error_msg").show()),console.log("error validation"),void go_disable_loading();jQuery("#go_stage_error_msg").hide(),jQuery("#go_blog_error_msg").hide(),1==t?task_stage_change(e)://this was a blog save button (not a continue on a stage) so just save without changing stage.  The function only validated the inputs.
go_blog_submit(e,g,o)}
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
jQuery(".progress").closest(".go_checks_and_buttons").addClass("active")}function tinymce_getContentLength_new(){var e=tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText,t=0;if(e){var o=(e=(e=(e=(e=e.replace(/\.\.\./g," ")).replace(/<.[^<>]*?>/g," ").replace(/&nbsp;|&#160;/gi," ")).replace(/(\w+)(&#?[a-z0-9]+;)+(\w+)/i,"$1$3").replace(/&.+?;/g," ")).replace(/[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g,"")).match(/[\w\u2019\x27\-\u00C0-\u1FFF]+/g);o&&(t=o.length)}return t}function go_blog_opener(e){jQuery("#go_hidden_mce").remove(),jQuery(".go_blog_opener").prop("onclick",null).off("click");
//var result_title = jQuery( this ).attr( 'value' );
var t=jQuery(e).attr("blog_post_id"),o,r={action:"go_blog_opener",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_opener,blog_post_id:t};
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
jQuery(".featherlight").css("background","rgba(0,0,0,.8)"),jQuery(".featherlight .featherlight-content").css("width","80%"),console.log("opener2"),jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)})}})}function go_blog_submit(e,o,r){var t=GO_EVERY_PAGE_DATA.nonces.go_blog_submit,n=go_get_tinymce_content_blog(),a=jQuery("#go_blog_title"+o).val();console.log("title: "+a);var s=jQuery(e).attr("blog_post_id"),i=jQuery(e).attr("status"),l=jQuery(e).attr("task_id"),_=jQuery("#go_result_url"+o).val(),c=jQuery("#go_result_media"+o).attr("value"),u=jQuery("#go_result_video"+o).val();console.log("blog_url: "+_),console.log("blog_media: "+c);var g={action:"go_blog_submit",_ajax_nonce:t,result:n,result_title:a,blog_post_id:s,blog_url:_,blog_media:c,blog_video:u,go_blog_task_stage:i,post_id:l};
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:g,cache:!1,success:function(e){console.log("success");
//console.log(raw);
// parse the raw response to get the desired JSON
var t={};try{var t=JSON.parse(e)}catch(e){t={json_status:"101",message:"",blog_post_id:""}}console.log("message"+t.message),console.log("blog_post_id"+t.blog_post_id),1==r?location.reload():(jQuery("body").append(t.message),
//console.log(res);
jQuery(".go_loading").remove(),jQuery("#go_save_button"+o).off().one("click",function(e){task_stage_check_input(this,!1,!1)}),jQuery("#go_save_button"+o).attr("blog_post_id",t.blog_post_id),jQuery("#go_blog_title"+o).attr("data-blog_post_id",t.blog_post_id))}})}function go_get_tinymce_content_blog(){
//console.log("html");
return jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")?jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").val():tinyMCE.activeEditor.getContent()}function go_blog_user_task(e,t){
//jQuery(".go_datatables").hide();
console.log("blogs!");var o=GO_EVERY_PAGE_DATA.nonces.go_blog_user_task;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:o,action:"go_blog_user_task",uid:e,task_id:t},success:function(e){jQuery.featherlight(e,{variant:"blogs",afterOpen:function(e){
//console.log("fitvids"); // this contains all related elements
//alert(this.$content.hasClass('true')); // alert class of content
//jQuery("#go_blog_container").fitVids();
go_fit_and_max_only("#go_blog_container")}})}})}var stIsIE=/*@cc_on!@*/!1;
/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
    document.write("<script id=__ie_onload defer src=javascript:void(0)><\/script>");
    var script = document.getElementById("__ie_onload");
    script.onreadystatechange = function() {
        if (this.readyState == "complete") {
            sorttable.init(); // call the onload handler
        }
    };
/*@end @*/
/* for Safari */
if(sorttable={init:function(){
// quit if this function has already been called
arguments.callee.done||(
// flag this function so we don't do the same thing twice
arguments.callee.done=!0,
// kill the timer
_timer&&clearInterval(_timer),document.createElement&&document.getElementsByTagName&&(sorttable.DATE_RE=/^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/,forEach(document.getElementsByTagName("table"),function(e){-1!=e.className.search(/\bsortable\b/)&&sorttable.makeSortable(e)})))},makeSortable:function(e){if(0==e.getElementsByTagName("thead").length&&(
// table doesn't have a tHead. Since it should have, create one and
// put the first table row in it.
the=document.createElement("thead"),the.appendChild(e.rows[0]),e.insertBefore(the,e.firstChild)),
// Safari doesn't support table.tHead, sigh
null==e.tHead&&(e.tHead=e.getElementsByTagName("thead")[0]),1==e.tHead.rows.length){// can't cope with two header rows
// Sorttable v1 put rows with a class of "sortbottom" at the bottom (as
// "total" rows, for example). This is B&R, since what you're supposed
// to do is put them in a tfoot. So, if there are sortbottom rows,
// for backwards compatibility, move them to tfoot (creating it if needed).
sortbottomrows=[];for(var t=0;t<e.rows.length;t++)-1!=e.rows[t].className.search(/\bsortbottom\b/)&&(sortbottomrows[sortbottomrows.length]=e.rows[t]);if(sortbottomrows){null==e.tFoot&&(
// table doesn't have a tfoot. Create one.
tfo=document.createElement("tfoot"),e.appendChild(tfo));for(var t=0;t<sortbottomrows.length;t++)tfo.appendChild(sortbottomrows[t]);delete sortbottomrows}
// work through each column and calculate its type
headrow=e.tHead.rows[0].cells;for(var t=0;t<headrow.length;t++)
// manually override the type with a sorttable_type attribute
headrow[t].className.match(/\bsorttable_nosort\b/)||(// skip this col
mtch=headrow[t].className.match(/\bsorttable_([a-z0-9]+)\b/),mtch&&(override=mtch[1]),mtch&&"function"==typeof sorttable["sort_"+override]?headrow[t].sorttable_sortfunction=sorttable["sort_"+override]:headrow[t].sorttable_sortfunction=sorttable.guessType(e,t),
// make it clickable to sort
headrow[t].sorttable_columnindex=t,headrow[t].sorttable_tbody=e.tBodies[0],dean_addEvent(headrow[t],"click",sorttable.innerSortFunction=function(e){if(-1!=this.className.search(/\bsorttable_sorted\b/))
// if we're already sorted by this column, just
// reverse the table, which is quicker
return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted","sorttable_sorted_reverse"),this.removeChild(document.getElementById("sorttable_sortfwdind")),sortrevind=document.createElement("span"),sortrevind.id="sorttable_sortrevind",sortrevind.innerHTML=stIsIE?'&nbsp<font face="webdings">5</font>':"&nbsp;&#x25B4;",void this.appendChild(sortrevind);if(-1!=this.className.search(/\bsorttable_sorted_reverse\b/))
// if we're already sorted by this column in reverse, just
// re-reverse the table, which is quicker
return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted_reverse","sorttable_sorted"),this.removeChild(document.getElementById("sorttable_sortrevind")),sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",void this.appendChild(sortfwdind);
// remove sorttable_sorted classes
theadrow=this.parentNode,forEach(theadrow.childNodes,function(e){1==e.nodeType&&(// an element
e.className=e.className.replace("sorttable_sorted_reverse",""),e.className=e.className.replace("sorttable_sorted",""))}),sortfwdind=document.getElementById("sorttable_sortfwdind"),sortfwdind&&sortfwdind.parentNode.removeChild(sortfwdind),sortrevind=document.getElementById("sorttable_sortrevind"),sortrevind&&sortrevind.parentNode.removeChild(sortrevind),this.className+=" sorttable_sorted",sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",this.appendChild(sortfwdind),
// build an array to sort. This is a Schwartzian transform thing,
// i.e., we "decorate" each row with the actual sort key,
// sort based on the sort keys, and then put the rows back in order
// which is a lot faster because you only do getInnerText once per row
row_array=[],col=this.sorttable_columnindex,rows=this.sorttable_tbody.rows;for(var t=0;t<rows.length;t++)row_array[row_array.length]=[sorttable.getInnerText(rows[t].cells[col]),rows[t]];
/* If you want a stable sort, uncomment the following line */
//sorttable.shaker_sort(row_array, this.sorttable_sortfunction);
/* and comment out this one */row_array.sort(this.sorttable_sortfunction),tb=this.sorttable_tbody;for(var t=0;t<row_array.length;t++)tb.appendChild(row_array[t][1]);delete row_array}))}},guessType:function(e,t){
// guess the type of a column based on its first non-blank row
sortfn=sorttable.sort_alpha;for(var o=0;o<e.tBodies[0].rows.length;o++)if(text=sorttable.getInnerText(e.tBodies[0].rows[o].cells[t]),""!=text){if(text.match(/^-?[�$�]?[\d,.]+%?$/))return sorttable.sort_numeric;
// check for a date: dd/mm/yyyy or dd/mm/yy
// can have / or . or - as separator
// can be mm/dd as well
if(possdate=text.match(sorttable.DATE_RE),possdate){if(
// looks like a date
first=parseInt(possdate[1]),second=parseInt(possdate[2]),12<first)
// definitely dd/mm
return sorttable.sort_ddmm;if(12<second)return sorttable.sort_mmdd;
// looks like a date, but we can't tell which, so assume
// that it's dd/mm (English imperialism!) and keep looking
sortfn=sorttable.sort_ddmm}}return sortfn},getInnerText:function(e){
// gets the text we want to use for sorting for a cell.
// strips leading and trailing whitespace.
// this is *not* a generic getInnerText function; it's special to sorttable.
// for example, you can override the cell text with a customkey attribute.
// it also gets .value for <input> fields.
if(!e)return"";if(hasInputs="function"==typeof e.getElementsByTagName&&e.getElementsByTagName("input").length,null!=e.getAttribute("sorttable_customkey"))return e.getAttribute("sorttable_customkey");if(void 0!==e.textContent&&!hasInputs)return e.textContent.replace(/^\s+|\s+$/g,"");if(void 0!==e.innerText&&!hasInputs)return e.innerText.replace(/^\s+|\s+$/g,"");if(void 0!==e.text&&!hasInputs)return e.text.replace(/^\s+|\s+$/g,"");switch(e.nodeType){case 3:if("input"==e.nodeName.toLowerCase())return e.value.replace(/^\s+|\s+$/g,"");case 4:return e.nodeValue.replace(/^\s+|\s+$/g,"");break;case 1:case 11:for(var t="",o=0;o<e.childNodes.length;o++)t+=sorttable.getInnerText(e.childNodes[o]);return t.replace(/^\s+|\s+$/g,"");break;default:return""}},reverse:function(e){
// reverse the rows in a tbody
newrows=[];for(var t=0;t<e.rows.length;t++)newrows[newrows.length]=e.rows[t];for(var t=newrows.length-1;0<=t;t--)e.appendChild(newrows[t]);delete newrows},
/* sort functions
     each sort function takes two parameters, a and b
     you are comparing a[0] and b[0] */
sort_numeric:function(e,t){return aa=parseFloat(e[0].replace(/[^0-9.-]/g,"")),isNaN(aa)&&(aa=0),bb=parseFloat(t[0].replace(/[^0-9.-]/g,"")),isNaN(bb)&&(bb=0),aa-bb},sort_alpha:function(e,t){return e[0]==t[0]?0:e[0]<t[0]?-1:1},sort_ddmm:function(e,t){return mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},sort_mmdd:function(e,t){return mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},shaker_sort:function(e,t){for(
// A stable sort function to allow multi-level sorting of data
// see: http://en.wikipedia.org/wiki/Cocktail_sort
// thanks to Joseph Nahmias
var o=0,r=e.length-1,n=!0;n;){n=!1;for(var a=o;a<r;++a)if(0<t(e[a],e[a+1])){var s=e[a];e[a]=e[a+1],e[a+1]=s,n=!0}// for
if(r--,!n)break;for(var a=r;o<a;--a)if(t(e[a],e[a-1])<0){var s=e[a];e[a]=e[a-1],e[a-1]=s,n=!0}// for
o++}// while(swap)
}},
/* ******************************************************************
   Supporting functions: bundled here to avoid depending on a library
   ****************************************************************** */
// Dean Edwards/Matthias Miller/John Resig
/* for Mozilla/Opera9 */
document.addEventListener&&document.addEventListener("DOMContentLoaded",sorttable.init,!1),/WebKit/i.test(navigator.userAgent))// sniff
var _timer=setInterval(function(){/loaded|complete/.test(document.readyState)&&sorttable.init()},10);
/* for other browsers */window.onload=sorttable.init,
// a counter used to create unique IDs
dean_addEvent.guid=1,fixEvent.preventDefault=function(){this.returnValue=!1},fixEvent.stopPropagation=function(){this.cancelBubble=!0}
// Dean's forEach: http://dean.edwards.name/base/forEach.js
/*
	forEach, version 1.0
	Copyright 2006, Dean Edwards
	License: http://www.opensource.org/licenses/mit-license.php
*/
// array-like enumeration
,Array.forEach||(// mozilla already supports this
Array.forEach=function(e,t,o){for(var r=0;r<e.length;r++)t.call(o,e[r],r,e)}),
// generic enumeration
Function.prototype.forEach=function(e,t,o){for(var r in e)void 0===this.prototype[r]&&t.call(o,e[r],r,e)},
// character enumeration
String.forEach=function(o,r,n){Array.forEach(o.split(""),function(e,t){r.call(n,e,t,o)})};
// globally resolve forEach enumeration
var forEach=function(e,t,o){if(e){var r=Object;// default
if(e instanceof Function)
// functions have a "length" property
r=Function;else{if(e.forEach instanceof Function)
// the object implements a custom forEach method so use that
return void e.forEach(t,o);"string"==typeof e?
// the object is a string
r=String:"number"==typeof e.length&&(
// the object is array-like
r=Array)}r.forEach(e,t,o)}};jQuery(document).ready(function(){go_hide_child_tax_acfs(),jQuery(".taxonomy-task_chains #parent, .taxonomy-go_badges #parent").change(function(){go_hide_child_tax_acfs()}),setTimeout(set_height_mce,1e3)}),
/**
 * This next section makes sure the levels on the options page proceed in ascending order.
 */
//get it set up on page load
jQuery(document).ready(function(){
//get the growth level from options
//var growth = levelGrowth*1;
if("undefined"!=typeof go_is_options_page)var e=go_is_options_page.is_options_page;e&&(
//console.log(is_options_page);
Go_orgGrowth=jQuery("#go_levels_growth").find("input").val(),
//run the limit function once on load
go_levels_limit_each(),
//attach function each input field
jQuery(".go_levels_repeater_numbers").find("input").change(go_levels_limit_each),jQuery(".go_levels_repeater_names").find("input").change(go_level_names),jQuery(".go_levels_repeater_names").find("input").change(go_level_names),jQuery("#go_levels_growth").find("input").change(go_validate_growth),acf.add_action("append",function(e){//run limit function when new row is added and attach it to the input in the new field
// $el will be equivalent to the new element being appended $('tr.row')
//limit to the levels table
if(jQuery(e).closest("#go_levels_repeater").length){var t=e.find("input").first();// find the first input field
jQuery(t).change(go_levels_limit_each);//bind to input on change
var o=e.find("input").last();// find the first input field
jQuery(o).change(go_level_names),
//console.log('-----------------row added------------------------');
go_levels_limit_each(),//run one time
go_level_names()}}),jQuery(".more_info_accordian").accordion({collapsible:!0,header:"h3",active:!1}))}),jQuery(document).ready(function(){if("undefined"!=typeof GO_EDIT_STORE_DATA)var e=GO_EDIT_STORE_DATA.is_store_edit;if(e){var t,o,r="<a id="+GO_EDIT_STORE_DATA.postid+" class='go_str_item ab-item' >View "+GO_EDIT_STORE_DATA.store_name+" Item</a>";
//console.log(link);
jQuery("#wp-admin-bar-view").html(r)}}),//Add an on click to all store items
jQuery(document).ready(function(){jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)})}),
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
jQuery(document).ready(function(){
//add onclick to blog edit buttons
//console.log("opener3");
jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)}),jQuery("#go_hidden_mce").remove(),jQuery("#go_hidden_mce_edit").remove()});