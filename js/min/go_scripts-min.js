/*
function go_sounds( type ) {

    console.log("sounds" + PluginDir.url);
    if ( 'store' == type ) {
        var audio = new Audio( PluginDir.url + 'media/win.mp3' );
        audio.play();
    } else if ( 'timer' == type ) {
        var audio = new Audio( PluginDir.url + 'media/airhorn.mp3' );
        audio.play();
    }
}
*/
function go_noty_close_oldest(){Noty.setMaxVisible(6);var e=jQuery("#noty_layout__topRight > div").length;0==e&&jQuery("#noty_layout__topRight").remove(),5<=e&&jQuery("#noty_layout__topRight > div").first().trigger("click")}function go_lightbox_blog_img(){jQuery("[class*= wp-image]").each(function(){var e;
//console.log("fullsize:" + fullSize);
if(1==jQuery(this).hasClass("size-full"))var t=jQuery(this).attr("src");else var a,s=/.*wp-image/,o=jQuery(this).attr("class").replace(s,"wp-image"),r=jQuery(this).attr("src"),_=/-([^-]+).$/,i=/\.[0-9a-z]+$/i,n=r.match(i),t=r.replace(_,n);
//console.log(class1);
//var patt = /w3schools/i;
jQuery(this).featherlight(t)})}function go_admin_bar_stats_page_button(e){//this is called from the admin bar and is hard coded in the php code
var t=GO_EVERY_PAGE_DATA.nonces.go_admin_bar_stats;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_admin_bar_stats",uid:e},success:function(e){-1!==e&&(jQuery.featherlight(e,{variant:"stats"}),go_stats_task_list(),jQuery("#stats_tabs").tabs(),jQuery(".stats_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){case"about":go_stats_about();break;case"tasks":go_stats_task_list();break;case"store":go_stats_item_list();break;case"history":go_stats_activity_list();break;case"badges":go_stats_badges_list();break;case"groups":go_stats_groups_list();break;case"leaderboard":go_stats_leaderboard();break}}))}})}function go_stats_links(){jQuery(".go_user_link_stats").prop("onclick",null).off("click"),jQuery(".go_user_link_stats").one("click",function(){var e;go_admin_bar_stats_page_button(jQuery(this).attr("name"))})}function go_stats_about(e){console.log("about");
//jQuery(".go_datatables").hide();
var t=GO_EVERY_PAGE_DATA.nonces.go_stats_about;0==jQuery("#go_stats_about").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_about",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(console.log(e),console.log("about me"),
//jQuery( '#go_stats_body' ).html( '' );
//var oTable = jQuery('#go_tasks_datatable').dataTable();
//oTable.fnDestroy();
jQuery("#stats_about").html(e))}})}function go_blog_lightbox_opener(e){console.log("open");var t=GO_EVERY_PAGE_DATA.nonces.go_blog_lightbox_opener;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_blog_lightbox_opener",blog_post_id:e},success:function(e){-1!==e&&(jQuery.featherlight(e,{variant:"blog_post"}),jQuery(".go_blog_lightbox").off().one("click",function(){go_blog_lightbox_opener(this.id)}))}})}
//The v4 no Server Side Processing (SSP)
function go_stats_task_list(){var e;jQuery("#go_task_list_single").remove(),jQuery("#go_task_list").show(),jQuery("#go_tasks_datatable").DataTable().columns.adjust().draw();var t=GO_EVERY_PAGE_DATA.nonces.go_stats_task_list;0==jQuery("#go_tasks_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_task_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_tasks").html(e),jQuery("#go_tasks_datatable").dataTable({responsive:!0,autoWidth:!1,order:[[jQuery("th.go_tasks_timestamps").index(),"desc"]],columnDefs:[{targets:"go_tasks_reset",sortable:!1}],drawCallback:function(){var e=jQuery("#go_stats_messages_icon_stats").attr("name");jQuery(".go_reset_task").prop("onclick",null).off("click"),jQuery(".go_reset_task").one("click",function(){go_messages_opener(e,this.id,"reset")}),jQuery(".go_tasks_reset_multiple").prop("onclick",null).off("click"),jQuery(".go_tasks_reset_multiple").one("click",function(){go_messages_opener(e,null,"reset_multiple")}),jQuery(".go_blog_lightbox").off().one("click",function(){go_blog_lightbox_opener(this.id)})}}));
//console.log("everypage");
//make task reset buttons into links
//jQuery(".go_reset_task").one("click", function(){
//go_messages_opener( user_id, this.id, 'reset' );
//});
/*
                    jQuery("#go_tasks_datatable_length select").focus(
                        function(){
                            console.log("click");
                            jQuery('.go_reset_task').prop('onclick',null).off('click');
                            jQuery(".go_tasks_reset").one("click", function(){
                                go_messages_opener( user_id, this.id, 'reset' );
                            });
                        }
                    );
                    */}})}
//the SSP v4 one
/*
function go_stats_task_list4() {
    jQuery(".go_datatables").hide();
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_task_list;
    if ( jQuery( "#go_task_list" ).length ) {

        jQuery( "#go_task_list" ).show();

    }else {


        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_task_list',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    //jQuery( '#go_stats_body' ).html( '' );
                    //var oTable = jQuery('#go_stats_datatable').dataTable();
                    //oTable.fnDestroy();

                    jQuery('#go_stats_body').append(res);
                    jQuery('#go_tasks_datatable').dataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": MyAjax.ajaxurl+'?action=go_tasks_dataloader_ajax',
                        //"bProcessing": true,
                        //"bServerSide": true,
                        //"sAjaxSource": MyAjax.ajaxurl+'?action=go_tasks_dataloader_ajax',
                        'createdRow': function(row, data, dataIndex) {
                            var dateCell = jQuery(row).find('td:eq(0)').text(); // get first column
                            // Split timestamp into [ Y, M, D, h, m, s ]
                            //var t = dateCell.split(/[- :]/);
							// Apply each element to the Date function
                            //var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
                            //var newDate = d.toString('dd-MM-yy');
                            //jQuery(row).find('td:eq(0)').attr("data-order", dateCell).text("hi");

                            var d = new Date(dateCell * 1000);
                            var month = d.getMonth();
                            var day = d.getDate();
                            var year = d.getFullYear().toString().slice(-2);
                            var hours = d.getHours();
                            var dd = "AM";
                            var h = hours;
                            if (h >= 12) {
                                h = hours - 12;
                                dd = "PM";
                            }
                            if (h == 0) {
                                h = 12;
                            }
							// Minutes part from the timestamp
                            var minutes = "0" + d.getMinutes();
							// Seconds part from the timestamp
                            var seconds = "0" + d.getSeconds();

                            // Will display time in 10:30:23 format
                            //var formattedTime = month + "/" + day + "/" + year + "  " + h + ':' + minutes.substr(-2) + ':' + seconds.substr(-2) + " " + dd;
                            var formattedTime = month + "/" + day + "/" + year + "  " + h + ':' + minutes.substr(-2) + " " + dd;
                            jQuery(row).find('td:eq(0)').attr("data-order", dateCell).text(formattedTime);

                           // var newDate = d.toString('dd-MM-yy');

							// new newDate(dateCell * 1000).format('h:i:s')

                            //var dateOrder = $dateCell.text(); // get the ISO date
							////console.log("data: " + data[0] + "  Row: " + row + "  dataindex: " + dataIndex);
							//console.log(formattedTime);
                        },
                        initComplete: function () {
                            this.api().columns().every( function () {
                                var column = this;
                                var select = jQuery('<select><option value=""></option></select>')
                                    .appendTo( jQuery(column.footer()).empty() )
                                    .on( 'change', function () {
                                        var val = jQuery.fn.dataTable.util.escapeRegex(
                                            jQuery(this).val()
                                        );

                                        column
                                            .search( val ? '^'+val+'$' : '', true, false )
                                            .draw();
                                    } );

                                column.data().unique().sort().each( function ( d, j ) {
                                    select.append( '<option value="'+d+'">'+d+'</option>' )
                                } );
                            } );
                        }

                    });
                }
            }
        });
    }


}
*/function go_stats_single_task_activity_list(e){var t=GO_EVERY_PAGE_DATA.nonces.go_stats_single_task_activity_list;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_single_task_activity_list",user_id:jQuery("#go_stats_hidden_input").val(),postID:e},success:function(e){-1!==e&&(
//jQuery( '#go_stats_body' ).html( '' );
jQuery("#go_task_list_single").remove(),jQuery("#go_task_list").hide(),jQuery("#stats_tasks").append(e),jQuery("#go_single_task_datatable").dataTable({bPaginate:!0,order:[[0,"desc"]],
//"destroy": true,
responsive:!0,autoWidth:!1}))}})}function go_stats_item_list(){
//console.log("store");
//jQuery(".go_datatables").hide();
var e=GO_EVERY_PAGE_DATA.nonces.go_stats_item_list;0==jQuery("#go_store_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_item_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_store").html(e),jQuery("#go_store_datatable").dataTable({bPaginate:!0,order:[[0,"desc"]],
//"destroy": true,
responsive:!0,autoWidth:!1}))}})}
/* v4 no ssp
function go_stats_activity_list() {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_activity_list;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_stats_activity_list',
            user_id: jQuery( '#go_stats_hidden_input' ).val()
        },
        success: function( res ) {
            if ( -1 !== res ) {
                //jQuery( '#go_stats_body' ).html( '' );
                var oTable = jQuery( '#go_stats_datatable' ).dataTable();
                oTable.fnDestroy();

                jQuery( '#go_stats_body' ).html( res );
                jQuery( '#go_stats_datatable' ).dataTable( {

                    "bPaginate": true,
                    "order": [[0, "desc"]],
                    "destroy": true,
                    responsive: true,
                    "autoWidth": false
                });
            }
        }
    });
}
*/
//the SSP v4 one
function go_stats_activity_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_activity_list;0==jQuery("#go_activity_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_activity_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_history").html(e),jQuery("#go_activity_datatable").dataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_activity_dataloader_ajax",data:function(e){e.user_id=jQuery("#go_stats_hidden_input").val()}//this doesn't actually pass something to my PHP like it does normally with AJAX.
},responsive:!0,autoWidth:!1,columnDefs:[{targets:"_all",orderable:!1}],searching:!0}))}})}function go_stats_badges_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_badges_list;0==jQuery("#go_badges_list").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_badges_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){
//console.log(res);
-1!==e&&jQuery("#stats_badges").html(e)}})}function go_stats_groups_list(){var e=GO_EVERY_PAGE_DATA.nonces.go_stats_groups_list;0==jQuery("#go_groups_list").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_groups_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&jQuery("#stats_groups").html(e)}})}
//this is for the leaderboard on the stats page and the clipboard
function go_filter_datatables(){//function that filters all tables on draw
jQuery.fn.dataTable.ext.search.push(function(e,t,a){var s=e.sTableId;
//console.log(myTable);
if("go_clipboard_stats_datatable"==s||"go_clipboard_messages_datatable"==s||"go_clipboard_activity_datatable"==s){var o=jQuery("#go_clipboard_user_go_sections_select").val(),r=jQuery("#go_clipboard_user_go_groups_select").val(),_=jQuery("#go_clipboard_go_badges_select").val(),i=t[4],n=t[3],l=t[2];// use data for the filter by column
//console.log("data" + data);
//console.log("badges" + badges);
//console.log("groups" + groups);
//console.log("sections" + sections);
//console.log(sections);
n=JSON.parse(n),
//console.log("groups" + groups);
//sections = JSON.parse(sections);
i=JSON.parse(i);
//console.log("badges" + badges);
//console.log("sections" + sections);
var u=!0;return(u="none"==r||-1!=jQuery.inArray(r,n))&&(u="none"==o||l==o),"go_clipboard_datatable"==s&&u&&(u="none"==_||-1!=jQuery.inArray(_,i)),u}if("go_leaders_datatable"!=s)return!0;var o=jQuery("#go_user_go_sections_select").val(),r=jQuery("#go_user_go_groups_select").val(),n=t[2],l=t[1];// use data for the filter by column
n=JSON.parse(n),l=JSON.parse(l);
//badges = JSON.parse(badges);
var u=!0;return(u="none"==r||-1!=jQuery.inArray(r,n))&&(u="none"==o||-1!=jQuery.inArray(o,l)),u})}function go_stats_leaderboard(){jQuery("#go_stats_lite_wrapper").remove(),jQuery("#go_leaderboard_wrapper").show(),go_filter_datatables();
//var nonce_leaderboard_choices = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard_choices;
//remove from localized data and actions
var e=GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard;0==jQuery("#go_leaderboard_wrapper").length&&(jQuery(".go_leaderboard_wrapper").show(),jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_leaderboard",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){console.log("success");
////console.log(raw);
// parse the raw response to get the desired JSON
var t={};try{var t=JSON.parse(e)}catch(e){console.log("parse_error")}
////console.log(res.xp_sticky);
//console.log(res.html);
if(jQuery("#stats_leaderboard").html(t.html),
//jQuery(document).ready(function() {
console.log("________here___________"),jQuery("#go_leaders_datatable").length){
//XP////////////////////////////
//go_sort_leaders("go_xp_leaders_datatable", 4);
var a=jQuery("#go_leaders_datatable").DataTable({
//"orderFixed": [[4, "desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,paging:!0,order:[[4,"desc"]],columnDefs:[{targets:[1],visible:!1},{targets:[2],visible:!1}]});a.on("order.dt search.dt",function(){a.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
// Event listener to the two range filtering inputs to redraw on input
jQuery("#go_user_go_sections_select, #go_user_go_groups_select").change(function(){jQuery("#go_leaders_datatable").length&&a.draw()})}}))}function go_stats_lite(e){
//jQuery(".go_datatables").hide();
var t=GO_EVERY_PAGE_DATA.nonces.go_stats_lite;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_lite",uid:e},success:function(e){-1!==e&&(
//jQuery( '#go_stats_body' ).html( '' );
jQuery("#go_stats_lite_wrapper").remove(),jQuery("#stats_leaderboard").append(e),jQuery("#go_leaderboard_wrapper").hide(),jQuery("#go_tasks_datatable_lite").dataTable({destroy:!0,responsive:!0,autoWidth:!1}))}})}
// Makes it so you can press return and enter content in a field
function go_make_store_clickable(){
//Make URL button clickable by clicking enter when field is in focus
jQuery(".clickable").keyup(function(e){
// 13 is ENTER
13===e.which&&jQuery("#go_store_pass_button").click()})}
//open the lightbox for the store items
function go_lb_opener(s){if(jQuery("#light").css("display","block"),jQuery(".go_str_item").prop("onclick",null).off("click"),!jQuery.trim(jQuery("#lb-content").html()).length){var e=s,t,a={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:e};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:a,beforeSend:function(){jQuery("#lb-content").append('<div class="go-lb-loading"></div>')},cache:!1,success:function(e){console.log("success"),console.log(e);var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}
//console.log('html');
//console.log(res.html);
//console.log(res.json_status);
//console.log('success');
//console.log(raw);
if(jQuery("#lb-content").innerHTML="",jQuery("#lb-content").html(""),
//jQuery( "#lb-content" ).append(results);
//jQuery('.featherlight-content').html(res.html);
jQuery.featherlight(t.html,{variant:"store"}),"101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var a="Server Error.";jQuery("#go_store_error_msg").text()!=a?jQuery("#go_store_error_msg").text(a):flash_error_msg_store("#go_store_error_msg")}else 302===Number.parseInt(t.json_status)&&(console.log(302),window.location=t.location);jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)}),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)}),go_max_purchase_limit()}})}}
//called when the "buy" button is clicked.
function goBuytheItem(t,e){var s=GO_BUY_ITEM_DATA.nonces.go_buy_item,o=GO_BUY_ITEM_DATA.userID;console.log(o),jQuery(document).ready(function(a){var e={_ajax_nonce:s,action:"go_buy_item",the_id:t,qty:a("#go_qty").val(),user_id:o};a.ajax({url:MyAjax.ajaxurl,type:"POST",data:e,beforeSend:function(){a("#golb-fr-buy").innerHTML="",a("#golb-fr-buy").html(""),a("#golb-fr-buy").append('<div id="go-buy-loading" class="buy_gold"></div>')},success:function(e){
//console.log("SUccess: " + raw);
var t={};try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:"101 Error: Please try again."}}-1!==e.indexOf("Error")?a("#light").html(e):
//go_sounds( 'store' );
a("#light").html(t.html)}})})}function flash_error_msg_store(e){var t=jQuery(e).css("background-color");void 0===typeof t&&(t="white"),jQuery(e).animate({color:t},200,function(){jQuery(e).animate({color:"red"},200)})}function go_store_password(s){
//console.log('button clicked');
//disable button to prevent double clicks
//go_enable_loading( target );
var e;if(!(0<jQuery("#go_store_password_result").attr("value").length)){jQuery("#go_store_error_msg").show();var t="Please enter a password.";return jQuery("#go_store_error_msg").text()!=t?jQuery("#go_store_error_msg").text(t):flash_error_msg_store("#go_store_error_msg"),void jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)})}var a=jQuery("#go_store_password_result").attr("value");if(jQuery("#light").css("display","block"),!jQuery.trim(jQuery("#lb-content").html()).length){var o=s,r,_={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:o,skip_locks:!0,result:a};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:_,cache:!1,success:function(e){
//console.log('success');
//console.log(raw);
var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}
//console.log('html');
//console.log(res.html);
//console.log(res.json_status);
//alert(res.json_status);
if("101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var a="Server Error.";jQuery("#go_store_error_msg").text()!=a?jQuery("#go_store_error_msg").text(a):flash_error_msg_store("#go_store_error_msg")}else if(302===Number.parseInt(t.json_status))console.log(302),window.location=t.location;else if("bad_password"==t.json_status){
//console.log("bad");
jQuery("#go_store_error_msg").show();var a="Invalid password.";jQuery("#go_store_error_msg").text()!=a?jQuery("#go_store_error_msg").text(a):flash_error_msg_store("#go_store_error_msg"),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)})}else
//console.log("good");
jQuery("#go_store_pass_button").one("click",function(e){go_store_password(s)}),jQuery("#go_store_lightbox_container").hide(),jQuery(".featherlight-content").html(t.html),go_max_purchase_limit()}})}}function go_max_purchase_limit(){window.go_purchase_limit=jQuery("#golb-fr-purchase-limit").attr("val");var e=go_purchase_limit;jQuery("#go_qty").spinner({max:e,min:1,stop:function(){jQuery(this).change()}}),go_make_store_clickable(),
//jQuery('#go_store_admin_override').click( function () {
//    jQuery('.go_store_lock').show();
//});
jQuery("#go_store_admin_override").one("click",function(e){
//console.log("override");
jQuery(".go_store_lock").show(),jQuery("#go_store_admin_override").hide(),go_make_store_clickable()})}function go_count_item(e){var t=GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:{_ajax_nonce:t,action:"go_get_purchase_count",item_id:e},success:function(e){if(-1!==e){var t=e.toString();jQuery("#golb-purchased").html("Quantity purchased: "+t)}}})}function go_messages_opener(a,e,s){
//jQuery('#go_messages_icon').prop('onclick',null).off('click'); //blog
//remove the onclick events from any message link and then reattach after ajax call
//types of links 1. clipboard 2. stats link 3. task reset button and 4. blog page
//alert(user_id);
if(e=void 0!==e?e:null,s=void 0!==s?s:null,
//console.log(message_type);
jQuery(".go_messages_icon").prop("onclick",null).off("click"),//clipboard
jQuery(".go_stats_messages_icon").prop("onclick",null).off("click"),//stats
jQuery(".go_reset_task").prop("onclick",null).off("click"),jQuery(".go_tasks_reset").prop("onclick",null).off("click"),//reset task links
//reset the multiple task reset button
jQuery(".go_tasks_reset_multiple").prop("onclick",null).off("click"),a)//this is from the stats panel, so user_id was sent so stuff it in an array
var o=[a];else for(//no user_id sent, this is from the clipboard and get user ids from checkboxes
var t=jQuery(".go_checkbox:visible"),o=[],r=0;r<t.length;r++)!0===t[r].checked&&o.push(jQuery(t[r]).val());if(null==e&&"reset_multiple"==s)for(//this is a reset multiple quests from stats panel
var t=jQuery(".go_checkbox:visible"),_=[],r=0;r<t.length;r++)!0===t[r].checked&&_.push(jQuery(t[r]).val());else//this is from the stats panel, so user_id was sent so stuff it in an array
var _=[e];var i,n={action:"go_create_admin_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_create_admin_message,post_id:_,user_ids:o,message_type:s};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:n,success:function(e){
//console.log(results);
jQuery.featherlight(e,{variant:"message"}),jQuery(".go_tax_select").select2(),jQuery("#go_message_submit").one("click",function(e){go_send_message(o,_,s)}),
//clipboard
jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),
//stats and blog
//console.log("hi:" + user_id);
jQuery(".go_stats_messages_icon").one("click",function(e){var t;go_messages_opener(jQuery(this).attr("name"))});
//reset task links
var t=jQuery("#go_stats_messages_icon_stats").attr("name");jQuery(".go_reset_task").one("click",function(e){go_messages_opener(t,this.id,"reset")}),
//reset multiple tasks link
jQuery(".go_tasks_reset_multiple").one("click",function(){go_messages_opener(a,null,"reset_multiple")})},error:function(e,t,a){
//clipboard
jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()});
//stats and blog
var s=jQuery("#go_stats_messages_icon_stats").attr("name");jQuery("#go_stats_messages_icon").one("click",function(e){go_messages_opener(s)}),
//reset task links
jQuery(".go_reset_task").one("click",function(e){go_messages_opener(s,this.id,"reset")})}})}function go_send_message(e,t,a){
//replace button with loader
//check for negative numbers and give error
//user_ids
var s=jQuery("[name=title]").val(),o=jQuery("[name=message]").val(),r=jQuery("[name=xp_toggle]").siblings().hasClass("-on")?1:-1,_=jQuery("[name=xp]").val()*r,i=jQuery("[name=gold_toggle]").siblings().hasClass("-on")?1:-1,n=jQuery("[name=gold]").val()*i,l=jQuery("[name=health_toggle]").siblings().hasClass("-on")?1:-1,u=jQuery("[name=health]").val()*l,c=jQuery("#go_messages_go_badges_select").val(),g=jQuery("[name=badges_toggle]").siblings().hasClass("-on"),d=jQuery("#go_messages_user_go_groups_select").val(),y=jQuery("[name=groups_toggle]").siblings().hasClass("-on"),h,p={action:"go_send_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_send_message,post_id:t,user_ids:e,message_type:a,title:s,message:o,xp:_,gold:n,health:u,badges_toggle:g,badges:c,groups_toggle:y,groups:d};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:p,success:function(e){
// show success or error message
jQuery("#go_messages_container").html("Message sent successfully."),jQuery("#go_tasks_datatable").remove(),go_stats_task_list(),go_toggle_off()},error:function(e,t,a){jQuery("#go_messages_container").html("Error.")}})}function Vids_Fit_and_Box(){runmefirst(function(){
//after making the video fit, set the max width and add the lightbox code
Max_width_and_LightboxNow();
//go_native_video_resize();
})}function runmefirst(e){fitVidsNow(),e()}function fitVidsNow(){
//make the videos fit on the page
jQuery("body").fitVids()}
//resize in the lightbox--featherlight
function go_video_resize(){var e=jQuery(".featherlight-content .fluid-width-video-wrapper").css("padding-top"),t=jQuery(".featherlight-content .fluid-width-video-wrapper").css("width"),a=(e=parseFloat(e))/(t=parseFloat(t));console.log("Vratio:"+a);var s=jQuery(window).width();console.log("vW:"+s);var o=s,r=jQuery(window).height();console.log("vH:"+r);var _=s*a;console.log("cH1:"+_),r<_&&(_=r-50,console.log("cH2:"+_),o=_/a,console.log("cW:"+o)),jQuery(".featherlight-content").css("width",o),jQuery(".featherlight-content").css("height",_)}function Max_width_and_LightboxNow(){
//console.log("max_width");
//add a max width video wrapper to the fitVid
var e=jQuery("#go_wrapper").data("maxwidth"),t;
//var fluid_width_video_wrapper = {};
if(jQuery(".fluid-width-video-wrapper:not(.fit)").each(function(){jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>'),jQuery(this).addClass("fit"),jQuery(".max-width-video-wrapper").css("max-width",e)}),
//add max-width wrapper to wp-video (added natively or with shortcode
jQuery(".wp-video:not(.fit)").each(function(){jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>'),jQuery(this).addClass("fit"),jQuery(".max-width-video-wrapper").css("max-width",e)}),1===jQuery("#go_wrapper").data("lightbox")){
//alert (lightbox_switch);
//add a featherlight lightroom wrapper to the fitvids iframes
jQuery(".max-width-video-wrapper:not(.wrapped):has(iframe)").each(function(){jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_iframe" href="#" ><span style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;"></span></a>'),jQuery(this).addClass("wrapped")}),
//adds a html link to the wrapper for featherlight lightbox
jQuery('[class^="featherlight_wrapper_iframe"]').each(function(){var e=jQuery(this).parent().find(".fluid-width-video-wrapper").parent().html();
//console.log("src2:" + _src);
//_src="<div class=\"fluid-width-video-wrapper fit\" style=\"padding-top: 56.1905%;\"><iframe src=\"https://www.youtube.com/embed/zRvOnnoYhKw?feature=oembed?&autoplay=1\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen=\"\" name=\"fitvid0\"></iframe></div>"
jQuery(this).attr("href",'<div id="go_video_container" style=" overflow: hidden;">'+e+"</div>"),jQuery(".featherlight_wrapper_iframe").featherlight({targetAttr:"href",closeOnEsc:!0,variant:"fit_and_box",afterOpen:function(e){jQuery(".featherlight-content").css({width:"100%",overflow:"hidden"}),jQuery(".featherlight-content iframe")[0].src+="&autoplay=1",
//ev.preventDefault();
go_video_resize(),jQuery(window).resize(function(){go_video_resize()})}})});
//adds link to native video
var a=setInterval(function(){jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").length&&(console.log("Exists!"),clearInterval(a),jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").each(function(){
//jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_native_vid" href="#" data-featherlight="iframe" ><span style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;"></span></a>');
var e=jQuery(this).find("video").attr("src");console.log("src:"+e),
//jQuery(this).prepend('<a  class="featherlight_wrapper_vid_native" href="#"><span style=\'position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;\'></span></a>');
jQuery(this).prepend('<a href=\'#\' class=\'featherlight_wrapper_vid_shortcode\' data-featherlight=\'<div id="go_video_container" style="height: 90vh; overflow: hidden; text-align: center;"> <video controls autoplay style="height: 100%; max-width: 100%;"><source src="'+e+"\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>'  data-featherlight-close-on-esc='true' data-featherlight-variant='fit_and_box native2' ><span style=\"position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;\"></span></a> "),
//jQuery(this).children(".featherlight_wrapper_vid_shortcode").prepend("<span style=\"position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;\"></span>");
//jQuery(".mejs-overlay-play").unbind("click");
jQuery(this).addClass("wrapped")}))},100);// check every 100ms
}}function go_admin_check_messages(){
//ajax call for new messages php function
//on success, if new messages, print
var e=GO_ADMIN_DATA.nonces.go_admin_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_admin_messages"},success:function(e){
//console.log(res);
0!=e&&jQuery("body").append(e)}})}function go_admin_check_messages_focus(){document.hasFocus()&&go_admin_check_messages()}//Add an on click to all store items
jQuery(document).ready(function(){jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)})}),function(_){"use strict";_.fn.fitVids=function(e){var a={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){
// appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
var t=document.head||document.getElementsByTagName("head")[0],s=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}",o=document.createElement("div");o.innerHTML='<p>x</p><style id="fit-vids-style">'+s+"</style>",t.appendChild(o.childNodes[1])}return e&&_.extend(a,e),this.each(function(){var e=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];a.customSelector&&e.push(a.customSelector);var r=".fitvidsignore";a.ignore&&(r=r+", "+a.ignore);var t=_(this).find(e.join(","));// Disable FitVids on this video.
(// SwfObj conflict patch
t=(t=t.not("object object")).not(r)).each(function(){var e=_(this);if(!(0<e.parents(r).length||"embed"===this.tagName.toLowerCase()&&e.parent("object").length||e.parent(".fluid-width-video-wrapper").length)){e.css("height")||e.css("width")||!isNaN(e.attr("height"))&&!isNaN(e.attr("width"))||(e.attr("height",9),e.attr("width",16));var t,a,s=("object"===this.tagName.toLowerCase()||e.attr("height")&&!isNaN(parseInt(e.attr("height"),10))?parseInt(e.attr("height"),10):e.height())/(isNaN(parseInt(e.attr("width"),10))?e.width():parseInt(e.attr("width"),10));if(!e.attr("name")){var o="fitvid"+_.fn.fitVids._count;e.attr("name",o),_.fn.fitVids._count++}e.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*s+"%"),e.removeAttr("height").removeAttr("width")}})})},
// Internal counter for unique video names.
_.fn.fitVids._count=0}(window.jQuery||window.Zepto),jQuery(window).ready(function(){
//jQuery(".mejs-container").hide();
Vids_Fit_and_Box()}),jQuery(document).ready(function(){"undefined"!=typeof GO_ADMIN_DATA&&(setInterval(go_admin_check_messages_focus,1e4),jQuery(window).focus(function(){go_admin_check_messages()}))});