function go_user_profile_link(e){jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{
//_ajax_nonce: nonce,
action:"go_user_profile_link",uid:e},success:function(e){window.open(e)}})}function go_noty_close_oldest(){Noty.setMaxVisible(6);var e=jQuery("#noty_layout__topRight > div").length;0==e&&jQuery("#noty_layout__topRight").remove(),5<=e&&jQuery("#noty_layout__topRight > div").first().trigger("click")}function go_lightbox_blog_img(){jQuery("[class*= wp-image]").each(function(){var e;
//console.log("fullsize:" + fullSize);
if(1==jQuery(this).hasClass("size-full"))var t=jQuery(this).attr("src");else var a,s=/.*wp-image/,o=jQuery(this).attr("class").replace(s,"wp-image"),r=jQuery(this).attr("src"),_=/-([^-]+).$/,i=/\.[0-9a-z]+$/i,n=r.match(i),t=r.replace(_,n);
//console.log(class1);
//var patt = /w3schools/i;
jQuery(this).featherlight(t)})}function go_admin_bar_stats_page_button(e){//this is called from the admin bar and is hard coded in the php code
var t=GO_EVERY_PAGE_DATA.nonces.go_admin_bar_stats;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_admin_bar_stats",uid:e},success:function(e){-1!==e&&(jQuery.featherlight(e,{variant:"stats"}),go_stats_task_list(),jQuery("#stats_tabs").tabs(),jQuery(".stats_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){case"about":go_stats_about();break;case"tasks":go_stats_task_list();break;case"store":go_stats_item_list();break;case"history":go_stats_activity_list();break;case"messages":go_stats_messages();break;case"badges":go_stats_badges_list();break;case"groups":go_stats_groups_list();break;case"leaderboard":go_stats_leaderboard();break}}))}})}function go_stats_links(){jQuery(".go_user_link_stats").prop("onclick",null).off("click"),jQuery(".go_user_link_stats").one("click",function(){var e;go_admin_bar_stats_page_button(jQuery(this).attr("name"))}),jQuery(".go_stats_messages_icon").prop("onclick",null).off("click"),jQuery(".go_stats_messages_icon").one("click",function(e){var t;go_messages_opener(this.getAttribute("data-uid"),null,"single_message")})}function go_leaderboard_menus_select2(){0==jQuery("#select2-go_user_go_sections_select-container").length&&jQuery("#go_user_go_sections_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"user_go_sections",is_hier:!1}},processResults:function(e){return jQuery("#go_user_go_sections_select").select2("destroy"),jQuery("#go_user_go_sections_select").children().remove(),jQuery("#go_user_go_sections_select").select2({data:e,placeholder:"Show All",allowClear:!0}).val(group).trigger("change"),jQuery("#go_user_go_sections_select").select2("open"),{results:e}},cache:!0},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Show All",allowClear:!0}),0==jQuery("#select2-go_user_go_groups_select-container").length&&jQuery("#go_user_go_groups_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"user_go_groups",is_hier:!1}},processResults:function(e){return jQuery("#go_user_go_groups_select").select2("destroy"),jQuery("#go_user_go_groups_select").children().remove(),jQuery("#go_user_go_groups_select").select2({data:e,placeholder:"Show All",allowClear:!0}).val(group).trigger("change"),jQuery("#go_user_go_groups_select").select2("open"),{results:e}},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Show All",allowClear:!0},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Show All",allowClear:!0})}function go_stats_about(e){
//console.log("about");
//jQuery(".go_datatables").hide();
var t=GO_EVERY_PAGE_DATA.nonces.go_stats_about;0==jQuery("#go_stats_about").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_about",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&
//console.log(res);
//console.log("about me");
//jQuery( '#go_stats_body' ).html( '' );
//var oTable = jQuery('#go_tasks_datatable').dataTable();
//oTable.fnDestroy();
jQuery("#stats_about").html(e)}})}function go_blog_lightbox_opener(e){
//console.log("open");
var t=GO_EVERY_PAGE_DATA.nonces.go_blog_lightbox_opener;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_blog_lightbox_opener",blog_post_id:e},success:function(e){-1!==e&&(jQuery.featherlight(e,{variant:"blog_post"}),jQuery(".go_blog_lightbox").off().one("click",function(){go_blog_lightbox_opener(this.id)}))}})}function go_stats_task_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_task_list;0==jQuery("#go_tasks_datatable").length?jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_task_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_tasks").html(e),jQuery("#go_tasks_datatable").dataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_tasks_dataloader_ajax",data:function(e){e.user_id=jQuery("#go_stats_hidden_input").val()}},responsive:!0,autoWidth:!1,columnDefs:[{targets:"_all",orderable:!1}],searching:!0,drawCallback:function(e){go_enable_reset_buttons()},order:[[3,"desc"]]}))}}):(jQuery("#go_task_list").show(),jQuery("#go_task_list_single").hide())}function go_enable_reset_buttons(){
//apply on click to the individual task reset icons
jQuery(".go_reset_task_clipboard").prop("onclick",null).off("click"),jQuery(".go_reset_task_clipboard").one("click",function(){go_messages_opener(this.getAttribute("data-uid"),this.getAttribute("data-task"),"single_reset")}),
//apply on click to the reset button at the top
jQuery(".go_tasks_reset_multiple_clipboard").parent().prop("onclick",null).off("click"),jQuery(".go_tasks_reset_multiple_clipboard").parent().one("click",function(){go_messages_opener(null,null,"multiple_reset")})}function go_close_single_history(){jQuery("#go_task_list").show(),jQuery("#go_task_list_single").hide()}function go_stats_single_task_activity_list(e){var t=GO_EVERY_PAGE_DATA.nonces.go_stats_single_task_activity_list;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_single_task_activity_list",user_id:jQuery("#go_stats_hidden_input").val(),postID:e},success:function(e){-1!==e&&(
//jQuery( '#go_stats_body' ).html( '' );
jQuery("#go_task_list_single").remove(),jQuery("#go_task_list").hide(),jQuery("#stats_tasks").append(e),jQuery("#go_single_task_datatable").dataTable({bPaginate:!0,order:[[0,"desc"]],
//"destroy": true,
responsive:!0,autoWidth:!1}))}})}function go_stats_item_list(){
//console.log("store");
//jQuery(".go_datatables").hide();
var e=GO_EVERY_PAGE_DATA.nonces.go_stats_item_list;0==jQuery("#go_store_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_item_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_store").html(e),jQuery("#go_store_datatable").dataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_stats_store_item_dataloader",data:function(e){e.user_id=jQuery("#go_stats_hidden_input").val()}},responsive:!0,autoWidth:!1,columnDefs:[{targets:"_all",orderable:!1}],searching:!0,order:[[0,"desc"]]}))}})}
//the SSP v4 one
function go_stats_activity_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_activity_list;0==jQuery("#go_activity_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_activity_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_history").html(e),jQuery("#go_activity_datatable").dataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_activity_dataloader_ajax",data:function(e){e.user_id=jQuery("#go_stats_hidden_input").val()}},responsive:!0,autoWidth:!1,columnDefs:[{targets:"_all",orderable:!1}],searching:!0,order:[[0,"desc"]]}))}})}function go_stats_messages(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_messages;0==jQuery("#go_messages_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_messages",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_messages").html(e),jQuery("#go_messages_datatable").dataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_messages_dataloader_ajax",data:function(e){e.user_id=jQuery("#go_stats_hidden_input").val()}},responsive:!0,autoWidth:!1,columnDefs:[{targets:"_all",orderable:!1}],searching:!0,order:[[0,"desc"]]}))}})}function go_stats_badges_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_badges_list;0==jQuery("#go_badges_list").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_badges_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){
//console.log(res);
-1!==e&&jQuery("#stats_badges").html(e)}})}function go_stats_groups_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_groups_list;0==jQuery("#go_groups_list").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_groups_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&jQuery("#stats_groups").html(e)}})}function go_stats_leaderboard(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard,t=GO_EVERY_PAGE_DATA.go_is_admin,a=3;1==t&&(a=4),0==jQuery("#go_leaderboard_wrapper").length&&(jQuery(".go_leaderboard_wrapper").show(),jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_leaderboard",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){
//console.log(raw);
//console.log('success');
jQuery("#stats_leaderboard").html(e);
//XP////////////////////////////
//go_sort_leaders("go_xp_leaders_datatable", 4);
var t=jQuery("#go_leaders_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_stats_leaderboard_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
//d.date = jQuery( '.datepicker' ).val();
e.section=jQuery("#go_user_go_sections_select").val(),e.group=jQuery("#go_user_go_groups_select").val()}},
//"orderFixed": [[4, "desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,paging:!0,order:[[a,"desc"]],drawCallback:function(e){go_stats_links(),go_leaderboard_menus_select2()},searching:!1,columnDefs:[{type:"natural",targets:"_all"},{targets:[0],sortable:!1},{targets:[1],sortable:!1},{targets:[2],sortable:!1},{targets:[3],sortable:!1},{targets:[4],sortable:!0,orderSequence:["desc"]},{targets:[5],sortable:!0,orderSequence:["desc"]},{targets:[6],sortable:!0,orderSequence:["desc"]}]});
// Event listener to the range filtering inputs to redraw on input
jQuery("#go_user_go_sections_select, #go_user_go_groups_select").change(function(){var e=jQuery("#go_user_go_sections_select").val();console.log(e),jQuery("#go_leaders_datatable").length&&t.draw()})}}))}function go_stats_lite(e){
//jQuery(".go_datatables").hide();
var t=GO_EVERY_PAGE_DATA.nonces.go_stats_lite;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_lite",uid:e},success:function(e){jQuery.featherlight(e,{variant:"stats_lite"}),-1!==e&&
//jQuery( '#go_stats_lite_wrapper' ).remove();
//jQuery( '#stats_leaderboard' ).append( res );
//jQuery("#go_leaderboard_wrapper").hide();
jQuery("#go_tasks_datatable_lite").dataTable({destroy:!0,responsive:!0,autoWidth:!1,drawCallback:function(e){go_stats_links()},searching:!1})}})}
// Makes it so you can press return and enter content in a field
function go_make_store_clickable(){
//Make URL button clickable by clicking enter when field is in focus
jQuery(".clickable").keyup(function(e){
// 13 is ENTER
13===e.which&&jQuery("#go_store_pass_button").click()})}
//open the lightbox for the store items
function go_lb_opener(s){if(jQuery("#light").css("display","block"),jQuery(".go_str_item").prop("onclick",null).off("click"),!jQuery.trim(jQuery("#lb-content").html()).length){var e=s,t,a={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:e};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:a,beforeSend:function(){jQuery("#lb-content").append('<div class="go-lb-loading"></div>')},cache:!1,success:function(e){console.log("success"),console.log(e);var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}if(jQuery("#lb-content").innerHTML="",jQuery("#lb-content").html(""),jQuery.featherlight(t.html,{variant:"store",afterOpen:function(e){console.log("store-fitvids3"),
//jQuery("#go_store_description").fitVids();
//go_fit_and_max_only("#go_store_description");
go_fit_and_max_only("#go_store_description")}}),"101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var a="Server Error.";jQuery("#go_store_error_msg").text()!=a?jQuery("#go_store_error_msg").text(a):flash_error_msg_store("#go_store_error_msg")}else 302===Number.parseInt(t.json_status)&&(console.log(302),window.location=t.location);jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)}),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)}),go_max_purchase_limit()}})}}
//called when the "buy" button is clicked.
function goBuytheItem(t,e){var s=GO_BUY_ITEM_DATA.nonces.go_buy_item,o=GO_BUY_ITEM_DATA.userID;jQuery(document).ready(function(a){var e={_ajax_nonce:s,action:"go_buy_item",the_id:t,qty:a("#go_qty").val(),user_id:o};a.ajax({url:MyAjax.ajaxurl,type:"POST",data:e,beforeSend:function(){a("#golb-fr-buy").innerHTML="",a("#golb-fr-buy").html(""),a("#golb-fr-buy").append('<div id="go-buy-loading" class="buy_gold"></div>')},success:function(e){var t={};try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:"101 Error: Please try again."}}-1!==e.indexOf("Error")?a("#light").html(e):a("#light").html(t.html)}})})}function flash_error_msg_store(e){var t=jQuery(e).css("background-color");void 0===typeof t&&(t="white"),jQuery(e).animate({color:t},200,function(){jQuery(e).animate({color:"red"},200)})}function go_store_password(s){var e;if(!(0<jQuery("#go_store_password_result").attr("value").length)){jQuery("#go_store_error_msg").show();var t="Please enter a password.";return jQuery("#go_store_error_msg").text()!=t?jQuery("#go_store_error_msg").text(t):flash_error_msg_store("#go_store_error_msg"),void jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)})}var a=jQuery("#go_store_password_result").attr("value");if(jQuery("#light").css("display","block"),!jQuery.trim(jQuery("#lb-content").html()).length){var o=s,r,_={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:o,skip_locks:!0,result:a};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:_,cache:!1,success:function(e){var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}if("101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var a="Server Error.";jQuery("#go_store_error_msg").text()!=a?jQuery("#go_store_error_msg").text(a):flash_error_msg_store("#go_store_error_msg")}else if(302===Number.parseInt(t.json_status))console.log(302),window.location=t.location;else if("bad_password"==t.json_status){jQuery("#go_store_error_msg").show();var a="Invalid password.";jQuery("#go_store_error_msg").text()!=a?jQuery("#go_store_error_msg").text(a):flash_error_msg_store("#go_store_error_msg"),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)})}else jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)}),jQuery("#go_store_lightbox_container").hide(),jQuery(".featherlight-content").html(t.html),go_max_purchase_limit()}})}}function go_max_purchase_limit(){window.go_purchase_limit=jQuery("#golb-fr-purchase-limit").attr("val");var e=go_purchase_limit;jQuery("#go_qty").spinner({max:e,min:1,stop:function(){jQuery(this).change()}}),go_make_store_clickable(),jQuery("#go_store_admin_override").one("click",function(e){jQuery(".go_store_lock").show(),jQuery("#go_store_admin_override").hide(),go_make_store_clickable()})}function go_count_item(e){var t=GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:{_ajax_nonce:t,action:"go_get_purchase_count",item_id:e},success:function(e){if(-1!==e){var t=e.toString();jQuery("#golb-purchased").html("Quantity purchased: "+t)}}})}function go_reset_opener(e){"multiple_messages"==e&&(
//apply on click to the messages button at the top
jQuery(".go_messages_icon_multiple_clipboard").parent().prop("onclick",null).off("click"),jQuery(".go_messages_icon_multiple_clipboard").parent().one("click",function(e){go_messages_opener(null,null,"multiple_messages")})),"single_reset"==e&&(
//apply on click to the individual task reset icons
jQuery(".go_reset_task_clipboard").prop("onclick",null).off("click"),jQuery(".go_reset_task_clipboard").one("click",function(){go_messages_opener(this.getAttribute("data-uid"),this.getAttribute("data-task"),"single_reset")})),"multiple_reset"==e&&(
//apply on click to the reset button at the top
jQuery(".go_tasks_reset_multiple_clipboard").parent().prop("onclick",null).off("click"),jQuery(".go_tasks_reset_multiple_clipboard").parent().one("click",function(){go_messages_opener(null,null,"multiple_reset")})),"single_message"==e&&(jQuery(".go_stats_messages_icon").prop("onclick",null).off("click"),jQuery(".go_stats_messages_icon").one("click",function(e){var t;go_messages_opener(this.getAttribute("data-uid"),null,"single_message")}))}function go_messages_opener(e,t,s){t=void 0!==t?t:null,s=void 0!==s?s:null,console.log("type"+s),jQuery(".go_tasks_reset_multiple_clipboard").prop("onclick",null).off("click");var a=[],o=[],r=[];if("multiple_messages"==s||"multiple_reset"==s){for(//the reset button or messages button on clipboard was pressed
var _=jQuery(".go_checkbox:visible"),i=0;i<_.length;i++)if(!0===_[i].checked){var n=_[i].getAttribute("data-uid"),l=_[i].getAttribute("data-task");"multiple_messages"==s&&(l=""),a.push({uid:n,task:l})}}else"single_reset"!=s&&"single_message"!=s||//single task reset or message was pressed
a.push({uid:e,task:t});
//if only a uid was passed, this is just a send message to single user box (no reset)
var u,c={action:"go_create_admin_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_create_admin_message,
//post_id: post_ids,
//user_id: user_id,
message_type:s,reset_vars:a};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:c,success:function(e){
//console.log(results);
jQuery.featherlight(e,{variant:"message"}),jQuery("#go_message_submit").one("click",function(e){go_send_message(a,s)}),go_reset_opener(s),jQuery("#go_messages_go_badges_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"go_badges",is_hier:!0}},processResults:function(e){return{results:e}},cache:!1},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!0,placeholder:"Show All",allowClear:!0}),jQuery("#go_messages_user_go_groups_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"user_go_groups",is_hier:!0}},processResults:function(e){return{results:e}},cache:!0},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!0,placeholder:"Show All",allowClear:!0}),tippy(".tooltip",{delay:0,arrow:!0,arrowType:"round",size:"large",duration:300,animation:"scale",zIndex:999999}),jQuery("#go_additional_penalty_toggle").change(function(){var e;
//console.log(penalty);
1==document.getElementById("go_additional_penalty_toggle").checked?jQuery("#go_penalty_table").css("display","block"):jQuery("#go_penalty_table").css("display","none")}),jQuery("#go_custom_message_toggle").change(function(){var e;
//console.log(penalty);
1==document.getElementById("go_custom_message_toggle").checked?jQuery("#go_custom_message_table").css("display","block"):jQuery("#go_custom_message_table").css("display","none")})},error:function(e,t,a){go_reset_opener(s)}})}function go_send_message(e,t){var a=jQuery("[name=title]").val();if("reset"==(t="multiple_reset"==t||"single_reset"==t?"reset":"message"))var s=document.getElementById("go_custom_message_toggle").checked,o=document.getElementById("go_additional_penalty_toggle").checked;else var s=null,o=null;if("message"==t||"reset"==t&&1==s)var r=jQuery("[name=message]").val();else var r="";if("message"==t||"reset"==t&&1==o){if("message"==t)var _=jQuery("[name=xp_toggle]").siblings().hasClass("-on")?1:-1,i=jQuery("[name=gold_toggle]").siblings().hasClass("-on")?1:-1,n=jQuery("[name=health_toggle]").siblings().hasClass("-on")?1:-1,l=jQuery("[name=badges_toggle]").siblings().hasClass("-on"),u=jQuery("[name=groups_toggle]").siblings().hasClass("-on");else var _=-1,i=-1,n=-1,l=!1,u=!1;var c=jQuery("[name=xp]").val()*_,g=jQuery("[name=gold]").val()*i,d=jQuery("[name=health]").val()*n,p=jQuery("#go_messages_go_badges_select").val(),y=jQuery("#go_messages_user_go_groups_select").val()}else if("reset"==t&&0==o)var l=!1,u=!1,c=0,g=0,d=0,p=null,y=null;
// send data
var h,j={action:"go_send_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_send_message,
//post_id: post_id,
reset_vars:e,message_type:t,title:a,message:r,xp:c,gold:g,health:d,badges_toggle:l,badges:p,groups_toggle:u,groups:y};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:j,success:function(e){
// show success or error message
jQuery("#go_messages_container").html("Message sent successfully."),jQuery("#go_tasks_datatable").remove(),go_stats_task_list(),go_toggle_off()},error:function(e,t,a){jQuery("#go_messages_container").html("Error.")}})}function go_Vids_Fit_and_Box(e){runmefirst(e,function(){
//after making the video fit, set the max width and add the lightbox code
go_Max_width_and_LightboxNow();
//go_native_video_resize();
})}function runmefirst(e,t){jQuery(e).fitVids(),t()}
//For Max width only when videos are already in lightboxes (the store for example)
function go_fit_and_max_only(e){go_fit_and_max_only_first(e,function(){go_Max_width()})}function go_fit_and_max_only_first(e,t){jQuery(e).fitVids(),t()}
//resize in the lightbox--featherlight
function go_video_resize(){var e=jQuery(".featherlight-content .fluid-width-video-wrapper").css("padding-top"),t=jQuery(".featherlight-content .fluid-width-video-wrapper").css("width"),a=(e=parseFloat(e))/(t=parseFloat(t));console.log("Vratio:"+a);var s=jQuery(window).width();console.log("vW:"+s);var o=s,r=jQuery(window).height();console.log("vH:"+r);var _=s*a;console.log("cH1:"+_),r<_&&(_=r-50,console.log("cH2:"+_),o=_/a,console.log("cW:"+o)),jQuery(".featherlight-content").css("width",o),jQuery(".featherlight-content").css("height",_)}function go_Max_width(){
//var _maxwidth = jQuery("#go_wrapper").data('maxwidth');
var e=GO_EVERY_PAGE_DATA.go_fitvids_maxwidth;console.log("max"+e),
//var fluid_width_video_wrapper = {};
jQuery(".fluid-width-video-wrapper:not(.fit)").each(function(){jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>'),jQuery(this).addClass("fit"),jQuery(".max-width-video-wrapper").css("max-width",e)}),
//add max-width wrapper to wp-video (added natively or with shortcode
jQuery(".wp-video:not(.fit)").each(function(){jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>'),jQuery(this).addClass("fit"),jQuery(".max-width-video-wrapper").css("max-width",e)})}function go_Max_width_and_LightboxNow(){
//Toggle lightbox on and off based on option
//var lightbox_switch = jQuery("#go_wrapper").data('lightbox');
var e;
//console.log("lbs" + lightbox_switch);
if(
//console.log("max_width");
//add a max width video wrapper to the fitVid
go_Max_width(),"1"===GO_EVERY_PAGE_DATA.go_lightbox_switch){
//alert (lightbox_switch);
//add a featherlight lightroom wrapper to the fitvids iframes
jQuery(".max-width-video-wrapper:not(.wrapped):has(iframe)").each(function(){jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_iframe" href="#" ><div style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;"></div></a>'),jQuery(".max-width-video-wrapper").children().unbind(),jQuery(this).addClass("wrapped")}),
//adds a html link to the wrapper for featherlight lightbox
jQuery('[class^="featherlight_wrapper_iframe"]').each(function(){var e=jQuery(this).parent().find(".fluid-width-video-wrapper").parent().html();
//console.log("src2:" + _src);
//_src="<div class=\"fluid-width-video-wrapper fit\" style=\"padding-top: 56.1905%;\"><iframe src=\"https://www.youtube.com/embed/zRvOnnoYhKw?feature=oembed?&autoplay=1\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen=\"\" name=\"fitvid0\"></iframe></div>"
jQuery(this).attr("href",'<div id="go_video_container" style=" overflow: hidden;">'+e+"</div>"),jQuery(".featherlight_wrapper_iframe").featherlight({targetAttr:"href",closeOnEsc:!0,variant:"fit_and_box",afterOpen:function(e){
//console.log ("this" + this);
jQuery(".featherlight-content").css({
//jQuery(this).css({
width:"100%",overflow:"hidden"}),
//jQuery(".featherlight-content iframe").src().append( "&autoplay=1");
jQuery(".featherlight-content iframe").attr("src",jQuery(".featherlight-content iframe").attr("src")+"&autoplay=1"),
//jQuery(this).find("iframe").attr('src' , jQuery(this).find("iframe").attr('src') + "&autoplay=1");
//ev.preventDefault();
//console.log("src2:" + _src);
go_video_resize(),jQuery(window).resize(function(){go_video_resize()})}})});
//adds link to native video
var t=setInterval(function(){jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").length&&(console.log("Exists!"),clearInterval(t),jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").each(function(){
//jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_native_vid" href="#" data-featherlight="iframe" ><span style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;"></span></a>');
var e=jQuery(this).find("video").attr("src");console.log("src:"+e),
//jQuery(this).prepend('<a  class="featherlight_wrapper_vid_native" href="#"><span style=\'position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;\'></span></a>');
jQuery(this).prepend('<a href=\'#\' class=\'featherlight_wrapper_vid_shortcode\' data-featherlight=\'<div id="go_video_container" style="height: 90vh; overflow: hidden; text-align: center;"> <video controls autoplay style="height: 100%; max-width: 100%;"><source src="'+e+"\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>'  data-featherlight-close-on-esc='true' data-featherlight-variant='fit_and_box native2' ><span style=\"position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;\"></span></a> "),
//jQuery(this).children(".featherlight_wrapper_vid_shortcode").prepend("<span style=\"position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;\"></span>");
//jQuery(".mejs-overlay-play").unbind("click");
jQuery(this).addClass("wrapped")}))},100);// check every 100ms
}}function go_to_this_map(e){var t=GO_EVERY_PAGE_DATA.nonces.go_to_this_map;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_to_this_map",map_id:e},success:function(e){console.log("success"),window.location.href=e}})}function go_show_map(e){
//https://stackoverflow.com/questions/28180584/wordpress-update-user-meta-onclick-with-ajax
//https://wordpress.stackexchange.com/questions/216140/update-user-meta-using-with-ajax
//
document.getElementById("maps").style.display="none",document.getElementById("loader").style.display="block";var t=GO_EVERY_PAGE_DATA.nonces.go_update_last_map,a=jQuery("#go_map_user").data("uid");
//var map_nonce = jQuery( '#_wpnonce' ).val();
console.log(e),
//console.log(map_ajax_admin_url);
jQuery.ajax({type:"POST",url:MyAjax.ajaxurl,data:{action:"go_update_last_map",goLastMap:e,_ajax_nonce:t,uid:a},success:function(e){jQuery("#mapwrapper").html(e),console.log("success!"),go_resizeMap(),document.getElementById("loader").style.display="none",document.getElementById("maps").style.display="block"},error:function(e){console.log(e),console.log("fail")}})}
//I think this was supposed to check the dropdown to see if the maps were done.
//It doesn't work
/*
function go_map_check_if_done() {
    go_resizeMap();
    //declare idArray
    var idArray = [];
    //make array of all the maps ids
    jQuery('.map').each(function () {
        idArray.push(this.id);
    });
    console.log("IDS" + idArray);
    console.log(idArray.length);
    //for each map do something
    var mapNum = 0;
    for (var i = 0; i < idArray.length; i++){
        var mapNum = mapNum++;
        var mapNumID = "#mapLink_" + mapNum;
        var mapNumClass = "#mapLink_" + mapNum + ' .mapLink';
        var mapID = "#map_" + mapNum;
        var countAvail = "#" + idArray[i] + " .available_color";
        var countDone = "#" + idArray[i] + " .checkmark";
        var numAvail = jQuery(countAvail).length;
        var numDone = jQuery(countDone).length;
        
     
        if (numAvail == 0){
            if (numDone == 0){
                
                jQuery(mapNumID).addClass("filtered"); 
            }
            else {
                
                jQuery(mapNumID).addClass("done");
                jQuery(mapNumClass).addClass("checkmark");
            }    
        }
    }

    //go_resizeMap();
  }
  */
//Resize map function, also runs on window load
function go_resizeMap(){console.log("resize");
//get mapid from data
var e,t="#map_"+jQuery("#maps").data("mapid"),a=jQuery(t+" .primaryNav > li").length-1;0==a&&(a=1),a==1/0&&(a=1);var s=100/a,o=jQuery(t).width()/a;
//set the width of the tasks on the map
//jQuery(mapID + " .primaryNav li").css("width", taskWidth + "%");
100==s?(jQuery(t+" .primaryNav > li").css("width","90%"),jQuery(t+" .primaryNav li").css("float","right"),jQuery(t+" .tasks > li").css("width","80%"),jQuery(t+" .primaryNav li").addClass("singleCol")):130<=o?(jQuery(t+" .primaryNav li").css("float","left"),jQuery(t+" .primaryNav li").css("width",s+"%"),jQuery(t+" .tasks > li").css("width","100%"),jQuery(t+" .primaryNav li").css("background","")):(jQuery(t+" .primaryNav > li").css("width","100%"),jQuery(t+" .primaryNav li").css("float","right"),jQuery(t+" .tasks > li").css("width","95%"),
//jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
jQuery(t+" .primaryNav li").addClass("singleCol")),jQuery("#sitemap").css("visibility","visible"),jQuery("#maps").css("visibility","visible")}
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */function go_map_dropDown(){document.getElementById("myDropdown").classList.toggle("show")}function go_user_map(e){console.log("map");var t=GO_EVERY_PAGE_DATA.nonces.go_user_map_ajax;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_user_map_ajax",uid:e},success:function(e){-1!==e&&jQuery.featherlight(e,{variant:"map",afterOpen:function(){console.log("after"),
//go_map_check_if_done();
go_resizeMap(),jQuery(window).resize(function(){
// go_resizeMap();
}),jQuery(window).on("resize",function(){go_resizeMap()})}})}})}//Add an on click to all store items
jQuery(document).ready(function(){jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)})}),function(_){"use strict";_.fn.fitVids=function(e){var a={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){
// appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
var t=document.head||document.getElementsByTagName("head")[0],s=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}",o=document.createElement("div");o.innerHTML='<p>x</p><style id="fit-vids-style">'+s+"</style>",t.appendChild(o.childNodes[1])}return e&&_.extend(a,e),this.each(function(){var e=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];a.customSelector&&e.push(a.customSelector);var r=".fitvidsignore";a.ignore&&(r=r+", "+a.ignore);var t=_(this).find(e.join(","));// Disable FitVids on this video.
(// SwfObj conflict patch
t=(t=t.not("object object")).not(r)).each(function(){var e=_(this);if(!(0<e.parents(r).length||"embed"===this.tagName.toLowerCase()&&e.parent("object").length||e.parent(".fluid-width-video-wrapper").length)){e.css("height")||e.css("width")||!isNaN(e.attr("height"))&&!isNaN(e.attr("width"))||(e.attr("height",9),e.attr("width",16));var t,a,s=("object"===this.tagName.toLowerCase()||e.attr("height")&&!isNaN(parseInt(e.attr("height"),10))?parseInt(e.attr("height"),10):e.height())/(isNaN(parseInt(e.attr("width"),10))?e.width():parseInt(e.attr("width"),10));if(!e.attr("name")){var o="fitvid"+_.fn.fitVids._count;e.attr("name",o),_.fn.fitVids._count++}e.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*s+"%"),e.removeAttr("height").removeAttr("width")}})})},
// Internal counter for unique video names.
_.fn.fitVids._count=0}(window.jQuery||window.Zepto),jQuery(window).ready(function(){
//jQuery(".mejs-container").hide();
go_Vids_Fit_and_Box("body")}),
//Resize listener--move to map shortcode/lightbox
/*
//Hide and show map on click
jQuery( document ).ready(function() {
    go_map_check_if_done();
});


jQuery( window ).resize(function() {
    go_resizeMap();
});
*/
// Close the dropdown menu if the user clicks outside of it
window.onclick=function(e){if(!e.target.matches(".dropbtn")){var t=document.getElementsByClassName("dropdown-content"),a;for(a=0;a<t.length;a++){var s=t[a];s.classList.contains("show")&&s.classList.remove("show")}}};