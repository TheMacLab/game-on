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
if(1==jQuery(this).hasClass("size-full"))var t=jQuery(this).attr("src");else var a,s=/.*wp-image/,o=jQuery(this).attr("class").replace(s,"wp-image"),r=jQuery(this).attr("src"),i=/-([^-]+).$/,n=/\.[0-9a-z]+$/i,_=r.match(n),t=r.replace(i,_);
//console.log(class1);
//var patt = /w3schools/i;
jQuery(this).featherlight(t)})}function go_admin_bar_stats_page_button(e){//this is called from the admin bar and is hard coded in the php code
var t=GO_EVERY_PAGE_DATA.nonces.go_admin_bar_stats;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_admin_bar_stats",uid:e},success:function(e){-1!==e&&(
/*
                jQuery( '#go_stats_white_overlay' ).html( res );
                jQuery( '#go_stats_page_black_bg' ).show();
                jQuery( '#go_stats_white_overlay' ).show();
                jQuery( '#go_stats_hidden_input' ).val( id );

                // this will stop the body from scrolling behind the stats page
                jQuery( 'html' ).addClass( 'go_no_scroll' );
                */
jQuery.featherlight(e,{variant:"stats"}),go_stats_task_list(),jQuery("#stats_tabs").tabs(),jQuery(".stats_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){case"about":go_stats_about();break;case"tasks":go_stats_task_list();break;case"store":go_stats_item_list();break;case"history":go_stats_activity_list();break;case"badges":go_stats_badges_list();break;case"groups":go_stats_groups_list();break;case"leaderboard":go_stats_leaderboard();break;case"leaderboard2":go_stats_leaderboard2();break}}))}})}function go_stats_links(){jQuery(".go_user_link_stats").prop("onclick",null).off("click"),jQuery(".go_user_link_stats").one("click",function(){var e;go_admin_bar_stats_page_button(jQuery(this).attr("name"))})}function go_stats_about(e){console.log("about");
//jQuery(".go_datatables").hide();
var t=GO_EVERY_PAGE_DATA.nonces.go_stats_about;0==jQuery("#go_stats_about").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_about",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(console.log(e),console.log("about me"),
//jQuery( '#go_stats_body' ).html( '' );
//var oTable = jQuery('#go_tasks_datatable').dataTable();
//oTable.fnDestroy();
jQuery("#stats_about").html(e))}})}
//The v4 no Server Side Processing (SSP)
function go_stats_task_list(){var e;jQuery("#go_task_list_single").remove(),jQuery("#go_task_list").show(),jQuery("#go_tasks_datatable").DataTable().columns.adjust().draw();var t=GO_EVERY_PAGE_DATA.nonces.go_stats_task_list;0==jQuery("#go_tasks_datatable").length&&jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_task_list",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){-1!==e&&(jQuery("#stats_tasks").html(e),jQuery("#go_tasks_datatable").dataTable({responsive:!0,autoWidth:!1,order:[[jQuery("th.go_tasks_timestamps").index(),"desc"]],columnDefs:[{targets:"go_tasks_reset",sortable:!1}],drawCallback:function(){var e=jQuery("#go_stats_messages_icon_stats").attr("name");jQuery(".go_reset_task").prop("onclick",null).off("click"),jQuery(".go_reset_task").one("click",function(){go_messages_opener(e,this.id,"reset")}),jQuery(".go_tasks_reset_multiple").prop("onclick",null).off("click"),jQuery(".go_tasks_reset_multiple").one("click",function(){go_messages_opener(e,null,"reset_multiple")})}}));
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
/*
function go_sort_leaders(tableID, column) {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById(tableID);
    switching = true;
    //Make a loop that will continue until
    //no switching has been done:
    console.log("switching");
    while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.getElementsByTagName("TR");
        //Loop through all table rows (except the first, which contains table headers):
        for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            //Get the two elements you want to compare, one from current row and one from the next:
            x = rows[i].getElementsByTagName("TD")[column];
            xVal = x.innerHTML;
            y = rows[i + 1].getElementsByTagName("TD")[column];
            yVal = y.innerHTML;
            //check if the two rows should switch place:
            if (parseInt(xVal) < parseInt(yVal)) {
                //if so, mark as a switch and break the loop:
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            //If a switch has been marked, make the switch and mark that a switch has been done:
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}
*/
//this is for the leaderboard on the stats page and the clipboard
function go_filter_datatables(){//function that filters all tables on draw
jQuery.fn.dataTable.ext.search.push(function(e,t,a){var s=e.sTableId;
//console.log(myTable);
if("go_clipboard_stats_datatable"==s||"go_clipboard_messages_datatable"==s||"go_clipboard_activity_datatable"==s){var o=jQuery("#go_clipboard_user_go_sections_select").val(),r=jQuery("#go_clipboard_user_go_groups_select").val(),i=jQuery("#go_clipboard_go_badges_select").val(),n=t[4],_=t[3],l=t[2];// use data for the filter by column
console.log("data"+t),console.log("badges"+n),console.log("groups"+_),console.log("sections"+l),
//console.log(sections);
_=JSON.parse(_),console.log("groups"+_),
//sections = JSON.parse(sections);
n=JSON.parse(n),console.log("badges"+n),console.log("sections"+l);var c=!0;return(c="none"==r||-1!=jQuery.inArray(r,_))&&(c="none"==o||l==o),"go_clipboard_datatable"==s&&c&&(c="none"==i||-1!=jQuery.inArray(i,n)),c}if("go_xp_leaders_datatable"!=s&&"go_gold_leaders_datatable"!=s&&"go_c4_leaders_datatable"!=s&&"go_badges_leaders_datatable"!=s)return!0;var o=jQuery("#go_user_go_sections_select").val(),r=jQuery("#go_user_go_groups_select").val(),_=t[2],l=t[1];// use data for the filter by column
_=JSON.parse(_),l=JSON.parse(l);
//badges = JSON.parse(badges);
var c=!0;return(c="none"==r||-1!=jQuery.inArray(r,_))&&(c="none"==o||-1!=jQuery.inArray(o,l)),c})}function go_stats_leaderboard(){
//jQuery( '#go_stats_lite_wrapper' ).remove();
jQuery("#go_leaderboard_wrapper").show(),go_filter_datatables();
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
console.log("________XP___________"),jQuery("#go_xp_leaders_datatable").length){
//XP////////////////////////////
//go_sort_leaders("go_xp_leaders_datatable", 4);
var a=jQuery("#go_xp_leaders_datatable").DataTable({
//"paging": true,
orderFixed:[[4,"desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,paging:!0,searching:!1,columnDefs:[{targets:[0],orderable:!1},{targets:[1],visible:!1},{targets:[2],visible:!1},{targets:[3],orderable:!1},{targets:[4],orderable:!1}]});a.on("order.dt search.dt",function(){a.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
//GOLD
if(jQuery("#go_gold_leaders_datatable").length){
//go_sort_leaders("go_gold_leaders_datatable", 4);
//console.log("________GOLD___________");
var s=jQuery("#go_gold_leaders_datatable").DataTable({paging:!0,orderFixed:[[4,"desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,searching:!1,columnDefs:[{targets:[0],orderable:!1},{targets:[1],visible:!1},{targets:[2],visible:!1},{targets:[3],orderable:!1},{targets:[4],orderable:!1}]});s.on("order.dt search.dt",function(){s.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
//C4//////////////////
if(jQuery("#go_health_leaders_datatable").length){
//go_sort_leaders("go_c4_leaders_datatable", 4);
//console.log("________C4___________");
var o=jQuery("#go_health_leaders_datatable").DataTable({paging:!0,orderFixed:[[4,"desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,searching:!1,columnDefs:[{targets:[0],orderable:!1},{targets:[1],visible:!1},{targets:[2],visible:!1},{targets:[3],orderable:!1},{targets:[4],orderable:!1}]});o.on("order.dt search.dt",function(){o.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
//BADGES
if(jQuery("#go_badges_leaders_datatable").length){
//go_sort_leaders("go_badges_leaders_datatable", 4);
//console.log("________Badges___________");
var r=jQuery("#go_badges_leaders_datatable").DataTable({paging:!0,orderFixed:[[4,"desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,searching:!1,columnDefs:[{targets:[1],visible:!1},{targets:[2],visible:!1}]});r.on("order.dt search.dt",function(){r.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
// Event listener to the two range filtering inputs to redraw on input
jQuery("#go_user_go_sections_select, #go_user_go_groups_select").change(function(){jQuery("#go_xp_leaders_datatable").length&&a.draw(),jQuery("#go_gold_leaders_datatable").length&&s.draw(),jQuery("#go_health_leaders_datatable").length&&o.draw(),jQuery("#go_badges_leaders_datatable").length&&r.draw()})}}))}function go_stats_leaderboard2(){
//jQuery( '#go_stats_lite_wrapper' ).remove();
jQuery("#go_leaderboard2_wrapper").show(),go_filter_datatables();
//var nonce_leaderboard_choices = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard_choices;
//remove from localized data and actions
var e=GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard2;0==jQuery("#go_leaderboard2_wrapper").length&&(jQuery(".go_leaderboard2_wrapper").show(),jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_stats_leaderboard2",user_id:jQuery("#go_stats_hidden_input").val()},success:function(e){console.log("success");
////console.log(raw);
// parse the raw response to get the desired JSON
var t={};try{var t=JSON.parse(e)}catch(e){console.log("parse_error")}
////console.log(res.xp_sticky);
//console.log(res.html);
if(jQuery("#stats_leaderboard2").html(t.html),
//jQuery(document).ready(function() {
console.log("________XP___________"),jQuery("#go_leaders_datatable").length){
//XP////////////////////////////
//go_sort_leaders("go_xp_leaders_datatable", 4);
var a=jQuery("#go_leaders_datatable").DataTable({
//"orderFixed": [[4, "desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,paging:!0,order:[[4,"desc"]],columnDefs:[{targets:[1],visible:!1},{targets:[2],visible:!1}]});a.on("order.dt search.dt",function(){a.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
// Event listener to the two range filtering inputs to redraw on input
jQuery("#go_user_go_sections_select, #go_user_go_groups_select").change(function(){jQuery("#go_leaders_datatable").length&&table.draw()})}}))}function go_stats_lite(e){
//jQuery(".go_datatables").hide();
var t=GO_EVERY_PAGE_DATA.nonces.go_stats_lite;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_stats_lite",uid:e},success:function(e){-1!==e&&(
//jQuery( '#go_stats_body' ).html( '' );
jQuery("#go_stats_lite_wrapper").remove(),jQuery("#stats_leaderboard").append(e),jQuery("#go_leaderboard_wrapper").hide(),jQuery("#go_tasks_datatable_lite").dataTable({destroy:!0,responsive:!0,autoWidth:!1}))}})}
//	Grabs substring in the middle of the string object that getMid() is being called from.
//	Takes two strings, one from the left and one from the right.
/**
 * Decimal adjustment of a number.
 *
 * @param string type  The type of adjustment.
 * @param number value The number to adjust.
 * @param int    exp   The exponent (the 10 logarithm of the adjustment base).
 * @returns number The adjusted value.
 */
function decimalAdjust(e,t,a){
// If the exp is undefined or zero...
return void 0===a||0==+a?Math[e](t):(
// If the value is not a number or the exp is not an integer...
t=+t,a=+a,isNaN(t)||"number"!=typeof a||a%1!=0?NaN:(
// Shift
t=t.toString().split("e"),+((
// Shift back
t=(t=Math[e](+(t[0]+"e"+(t[1]?+t[1]-a:-a)))).toString().split("e"))[0]+"e"+(t[1]?+t[1]+a:a))))}
// Decimal round
//open the lightbox for the store items
function go_lb_opener(e){if(jQuery("#light").css("display","block"),jQuery(".go_str_item").prop("onclick",null).off("click"),"none"==jQuery("#go_stats_page_black_bg").css("display")&&jQuery("#fade").css("display","block"),!jQuery.trim(jQuery("#lb-content").html()).length){var t=e,a,s={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:t},o="<?php echo admin_url( '/admin-ajax.php' ); ?>";
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({
//url: url_action,
url:MyAjax.ajaxurl,type:"POST",data:s,beforeSend:function(){jQuery("#lb-content").append('<div class="go-lb-loading"></div>')},cache:!1,success:function(e){jQuery("#lb-content").innerHTML="",jQuery("#lb-content").html(""),
//jQuery( "#lb-content" ).append(results);
jQuery.featherlight(e,{variant:"store"}),jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)}),
//window.go_req_currency = jQuery( '#golb-fr-price' ).attr( 'req' );
//window.go_req_points = jQuery( '#golb-fr-points' ).attr( 'req' );
//window.go_req_bonus_currency = jQuery( '#golb-fr-bonus_currency' ).attr( 'req' );
//window.go_req_penalty = jQuery( '#golb-fr-penalty' ).attr( 'req' );
//window.go_req_minutes = jQuery( '#golb-fr-minutes' ).attr( 'req' );
//window.go_cur_currency = jQuery( '#golb-fr-price' ).attr( 'cur' );
//window.go_cur_points = jQuery( '#golb-fr-points' ).attr( 'cur' );
//window.go_cur_bonus_currency = jQuery( '#golb-fr-bonus_currency' ).attr( 'cur' );
//window.go_cur_minutes = jQuery( '#golb-fr-minutes' ).attr( 'cur' );
window.go_purchase_limit=jQuery("#golb-fr-purchase-limit").attr("val"),
// `window.go_store_debt_enabled` was implemented as a temporary hotfix for
// bugs in v2.6.1
window.go_store_debt_enabled="true"===jQuery(".golb-fr-boxes-debt").val();
//if ( go_purchase_limit == 0 ) {go_purchase_limit = 9999;}
// determines the upper limit of the purchase quantity spinner, which is limited
// by the amount of currency that the user has and the cost of the Store Item
var t=go_purchase_limit;
/*
                if ( ! go_store_debt_enabled ) {

                    var point_cost_ratio = go_purchase_limit;
                    var currency_cost_ratio = go_purchase_limit;
                    if ( go_req_points > 0 ) {
                        point_cost_ratio = Math.floor( go_cur_points / go_req_points );
                    }
                    if ( go_req_currency > 0 ) {
                        currency_cost_ratio = Math.floor( go_cur_currency / go_req_currency );
                    }

                    if ( point_cost_ratio < 1 || currency_cost_ratio < 1 ) {
                        spinner_max_size = 1;
                    } else {
                        spinner_max_size = Math.min( point_cost_ratio, currency_cost_ratio, spinner_max_size );
                    }
                }
                */jQuery("#go_qty").spinner({max:t,min:1,stop:function(){jQuery(this).change()}})}})}}
//called when the "buy" button is clicked.
function goBuytheItem(t,e){var s=GO_BUY_ITEM_DATA.nonces.go_buy_item,o=GO_BUY_ITEM_DATA.userID;console.log(o),jQuery(document).ready(function(a){var e={_ajax_nonce:s,action:"go_buy_item",the_id:t,qty:a("#go_qty").val(),
//recipient: jQuery( '#go_recipient' ).val(),
//purchase_count: count,
user_id:o};a.ajax({url:MyAjax.ajaxurl,type:"POST",data:e,beforeSend:function(){a("#golb-fr-buy").innerHTML="",a("#golb-fr-buy").html(""),a("#golb-fr-buy").append('<div id="go-buy-loading" class="buy_gold"></div>')},success:function(e){
//console.log("SUccess: " + raw);
var t={};try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:"101 Error: Please try again."}}-1!==e.indexOf("Error")?a("#light").html(e):
//go_sounds( 'store' );
a("#light").html(t.html)}})})}
//Not sure if this is still used
function go_count_item(e){var t=GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:{_ajax_nonce:t,action:"go_get_purchase_count",item_id:e},success:function(e){if(-1!==e){var t=e.toString();jQuery("#golb-purchased").html("Quantity purchased: "+t)}}})}function Vids_Fit_and_Box(){runmefirst(function(){
//after making the video fit, set the max width and add the lightbox code
Max_width_and_LightboxNow();
//go_native_video_resize();
})}function runmefirst(e){fitVidsNow(),e()}function fitVidsNow(){
//make the videos fit on the page
jQuery("body").fitVids();
// var local_customSelector = "mejs-container";
//jQuery("body").fitVids({customSelector: "video"});
}
//resize in the lightbox--featherlight
function go_video_resize(){var e=jQuery(".featherlight-content .fluid-width-video-wrapper").css("padding-top"),t=jQuery(".featherlight-content .fluid-width-video-wrapper").css("width"),a=(e=parseFloat(e))/(t=parseFloat(t));console.log("Vratio:"+a);var s=jQuery(window).width();console.log("vW:"+s);var o=s,r=jQuery(window).height();console.log("vH:"+r);var i=s*a;console.log("cH1:"+i),r<i&&(i=r-50,console.log("cH2:"+i),o=i/a,console.log("cW:"+o)),jQuery(".featherlight-content").css("width",o),jQuery(".featherlight-content").css("height",i)}function Max_width_and_LightboxNow(){
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
}}function go_blog_opener(e){jQuery("#go_hidden_mce").remove(),jQuery(".go_blog_opener").prop("onclick",null).off("click");
//var result_title = jQuery( this ).attr( 'value' );
var t=jQuery(e).attr("blog_post_id"),a,s={action:"go_blog_opener",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_opener,blog_post_id:t};
//console.log(el);
//console.log(blog_post_id);
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:s,cache:!1,success:function(e){
//console.log(results);
jQuery.featherlight(e),tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post"),tinymce.execCommand("mceAddEditor",!1,"go_blog_post"),jQuery(".featherlight").css("background","rgba(0,0,0,.8)"),jQuery(".featherlight .featherlight-content").css("width","80%"),jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)})}})}function go_blog_submit(e){var t,a,s,o,r={action:"go_blog_submit",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_submit,result:tinyMCE.activeEditor.getContent(),result_title:jQuery("#go_result_title").attr("value"),blog_post_id:jQuery(e).attr("blog_post_id")};
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:r,cache:!1,success:function(){console.log("success"),location.reload()}})}function go_messages_opener(a,e,s){
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
var t=jQuery(".go_checkbox:visible"),i=[],r=0;r<t.length;r++)!0===t[r].checked&&i.push(jQuery(t[r]).val());else//this is from the stats panel, so user_id was sent so stuff it in an array
var i=[e];var n,_={action:"go_create_admin_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_create_admin_message,post_id:i,user_ids:o,message_type:s};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:_,success:function(e){
//console.log(results);
jQuery.featherlight(e,{variant:"message"}),jQuery(".go_tax_select").select2(),jQuery("#go_message_submit").one("click",function(e){go_send_message(o,i,s)}),
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
var s=jQuery("[name=title]").val(),o=jQuery("[name=message]").val(),r=jQuery("[name=xp_toggle]").siblings().hasClass("-on")?1:-1,i=jQuery("[name=xp]").val()*r,n=jQuery("[name=gold_toggle]").siblings().hasClass("-on")?1:-1,_=jQuery("[name=gold]").val()*n,l=jQuery("[name=health_toggle]").siblings().hasClass("-on")?1:-1,c=jQuery("[name=health]").val()*l,u=jQuery("[name=c4_toggle]").siblings().hasClass("-on")?1:-1,d=jQuery("[name=c4]").val()*u,g=jQuery("#go_messages_go_badges_select").val(),p=jQuery("[name=badges_toggle]").siblings().hasClass("-on"),h=jQuery("#go_messages_user_go_groups_select").val(),y=jQuery("[name=groups_toggle]").siblings().hasClass("-on"),j,b={action:"go_send_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_send_message,post_id:t,user_ids:e,message_type:a,title:s,message:o,xp:i,gold:_,health:c,c4:d,badges_toggle:p,badges:g,groups_toggle:y,groups:h};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:b,success:function(e){
// show success or error message
jQuery("#go_messages_container").html("Message sent successfully."),jQuery("#go_tasks_datatable").remove(),go_stats_task_list(),go_toggle_off()},error:function(e,t,a){jQuery("#go_messages_container").html("Error.")}})}function go_toggle(e){checkboxes=jQuery(".go_checkbox");for(var t=0,a=checkboxes.length;t<a;t++)checkboxes[t].checked=e.checked}function go_toggle_off(){checkboxes=jQuery(".go_checkbox");for(var e=0,t=checkboxes.length;e<t;e++)checkboxes[e].checked=!1}function go_clipboard_class_a_choice(){if(
//var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
go_filter_datatables(),jQuery("#go_clipboard_stats_datatable").length){
//XP////////////////////////////
//go_sort_leaders("go_clipboard", 4);
var o=jQuery("#go_clipboard_stats_datatable").DataTable({
//stateSave: false,
bPaginate:!1,
//colReorder: true,
order:[[5,"asc"]],responsive:!0,autoWidth:!1,stateSave:!0,
//"destroy": true,
dom:"Bfrtip",drawCallback:function(e){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"1px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[4],visible:!1,className:"noVis"},{targets:[7],className:"noVis"},{targets:[8],className:"noVis"},{targets:[10],className:"noVis",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(e,t,a,s){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
//on change filter listener
//console.log("change5");
jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){
//console.log("change");
o.draw();
//ajax function to save the values
var e=GO_CLIPBOARD_DATA.nonces.go_clipboard_save_filters,t=jQuery("#go_clipboard_user_go_sections_select").val(),a=jQuery("#go_clipboard_user_go_groups_select").val(),s=jQuery("#go_clipboard_go_badges_select").val();
//alert (section);
//console.log(jQuery( '#go_clipboard_user_go_sections_select' ).val());
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_clipboard_save_filters",section:t,badge:s,group:a},success:function(e){
//console.log("values saved");
}})}),jQuery("#records_tabs").css("margin-left","")}
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc()}function go_clipboard_class_a_choice_activity(e){if(0==jQuery("#go_clipboard_activity_datatable").length||1==e){var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_activity,a=jQuery(".datepicker").val();
//console.log(date);
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_intable_activity",go_clipboard_class_a_choice_activity:jQuery("#go_clipboard_class_a_choice_activity").val(),date:jQuery(".datepicker").val()},success:function(e){
//console.log("success");
if(-1!==e){jQuery("#clipboard_activity_datatable_container").html(e);
//go_filter_datatables();
var t=jQuery("#go_clipboard_activity_datatable").DataTable({
//stateSave: false,
bPaginate:!1,
//colReorder: true,
order:[[4,"asc"]],responsive:!0,autoWidth:!1,stateSave:!0,
//"destroy": true,
dom:"Bfrtip",drawCallback:function(e){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[4],visible:!1,className:"noVis"},{targets:[7],className:"noVis"},{targets:[8],className:"noVis"},{targets:[10],className:"noVis",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(e,t,a,s){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
//show date filter
jQuery("#go_timestamp_filters").show(),
//on change filter listener
//console.log("change5");
jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){
//console.log("change");
t.draw()}),
// Add event listener for opening and closing more actions
jQuery("#go_clipboard_activity_datatable .show_more").click(function(){var e;
//console.log(hidden);
0==jQuery(this).hasClass("shown")?(jQuery(this).addClass("shown"),jQuery(this).siblings(".hidden_action").show(),jQuery(this).find(".hide_more_actions").show(),jQuery(this).find(".show_more_actions").hide()):(jQuery(this).removeClass("shown"),jQuery(this).siblings(".hidden_action").hide(),jQuery(this).find(".hide_more_actions").hide(),jQuery(this).find(".show_more_actions").show())
/*
                        var table = jQuery(this).closest('table');
                        console.log(table);
                        var row = table.row( table );
                        console.log(row);
                        if ( row.isShown() ) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                        }
                        else {
                            // Open this row
                            row.child( format(row.data()) ).show();
                            tr.addClass('shown');
                        }
                        */})}}})}}
/*
function go_clipboard_clear_fields() {
	jQuery( '#go_clipboard_points' ).val( '' );
	jQuery( '#go_clipboard_currency' ).val( '' );
	jQuery( '#go_clipboard_bonus_currency' ).val( '' );
	jQuery( '#go_clipboard_minutes' ).val( '' );
	jQuery( '#go_clipboard_penalty' ).val( '' );
	jQuery( '#go_clipboard_reason' ).val( '' );
	jQuery( '#go_clipboard_badge' ).val( '' );
}
*/
/*
function go_clipboard_class_a_choiceOLD_AJAX() {
    //var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;

    jQuery.fn.dataTable.ext.search.push(

        function( settings, data, dataIndex ) {

            var section = jQuery('#go_user_go_sections_select').val();
            var group = jQuery('#go_user_go_groups_select').val();
            ////console.log(section);
            ////console.log(section);
            var groups =  data[2] ; // use data for the filter by column
            var sections = data[1]; // use data for the filter by column

            groups = JSON.parse(groups);
            sections = JSON.parse(sections);
            //console.log(groups);
            //console.log(sections);
            ////console.log("sections: " + sections);

            var groupexists = jQuery.inArray(section, sections);

            ////console.log ("Exists" + groupexists)
            if( group == "none" || jQuery.inArray(group, groups) != -1){
                //alert('value is Array!');
                if( section == "none" || jQuery.inArray(section, sections) != -1){
                    return true;
                }
                else{
                    return false;
                }
            } else {
                //alert('Not an array');
                return false;
            }


        }
    );

    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_clipboard_intable',
            go_clipboard_class_a_choice: jQuery( '#go_clipboard_class_a_choice' ).val()
        },
        success: function( res ) {
            if ( -1 !== res ) {
                //jQuery( '#go_clipboard_table_body' ).html( '' );
                //var oTable = jQuery( '#go_clipboard_table' ).dataTable();
                //oTable.fnDestroy();
                jQuery( '#go_clipboard_table_body' ).append( res );
                jQuery(document).ready(function() {

                    if (jQuery("#go_clipboard").length) {

                        //XP////////////////////////////
                        //go_sort_leaders("go_clipboard", 4);
                        var Clipboard = jQuery('#go_clipboard').DataTable({
                            //"paging": true,
                            "orderFixed": [[4, "desc"]],
                            //"destroy": true,
                            responsive: true,
                            "autoWidth": false,
                            "paging": false,
                            "columnDefs": [
                                {
                                    "targets": [1],
                                    "visible": false
                                },
                                {
                                    "targets": [2],
                                    "visible": false
                                }
                            ]
                        });
                        //on change filter listener
                        //console.log("change1");
                        jQuery('#go_user_go_sections_select, #go_user_go_groups_select').change( function() {
                            //console.log("change");
                            Clipboard.draw();

                        } );
                    }
                });


            }
        }
    });
}
 */
/*
function go_fix_messages() {
	var nonce = GO_CLIPBOARD_DATA.nonces.go_fix_messages;
	jQuery.ajax({
		type: "POST",
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce,
			action: 'go_fix_messages'
		},
		success: function( res ) {
			if ( -1 !== res ) {
				alert( 'Messages fixed' );
			}
		}
	});
}
*/
/*
function go_clipboard_class_a_choice_messages() {
    if ( jQuery( "#go_clipboard_messages_datatable" ).length == 0 ) {
        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_messages;
        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_intable_messages',
                go_clipboard_class_a_choice_messages: jQuery('#go_clipboard_class_a_choice_messages').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    jQuery('#clipboard_messages_wrap').html(res);
                    go_filter_datatables();
                    var Messages = jQuery('#go_clipboard_messages_datatable').DataTable({
                        stateSave: true,
                        "bPaginate": false,
                        //colReorder: true,
                        "order": [[4, "asc"]],
                        responsive: true,
                        "autoWidth": false,
                        //"destroy": true,
                        dom: 'Bfrtip',

                        "columnDefs": [
                            {
                                "targets": [0],
                                className: 'noVis'
                            },
                            {
                                "targets": [1],
                                "visible": false,
                                className: 'noVis'
                            },
                            {
                                "targets": [2],
                                "visible": false,
                                className: 'noVis'
                            },
                            {
                                "targets": [3],
                                "visible": false,
                                className: 'noVis'
                            },
                            {
                                "targets": [6],
                                className: 'noVis'
                            },
                            {
                                "targets": [7],
                                className: 'noVis'
                            },
                            {
                                "targets": [9],
                                className: 'noVis'
                            }
                        ],
                        buttons: [
                            {
                                extend: 'collection',
                                text: 'Export ...',
                                buttons: [{
                                    extend: 'pdf',
                                    title: 'Game On Data Export',
                                    exportOptions: {
                                        columns: "thead th:not(.noExport)"
                                    },
                                    orientation: 'landscape'
                                },{
                                    extend: 'excel',
                                    title: 'Game On Data Export',
                                    exportOptions: {
                                        columns: "thead th:not(.noExport)"
                                    }
                                }, {
                                    extend: 'csv',
                                    title: 'Game On Data Export',
                                    exportOptions: {
                                        columns: "thead th:not(.noExport)"
                                    }
                                }],

                            },
                            {
                                extend: 'colvis',
                                columns: ':not(.noVis)',
                                postfixButtons: ['colvisRestore']
                            }

                        ]
                    });
                    //on change filter listener
                    //console.log("change5");
                    jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select').change(function () {
                        //console.log("change");
                        Messages.draw();

                    });
                }
            }
        });
    }
}

function go_user_focus_change( user_id, element ) {
	var nonce = GO_CLIPBOARD_DATA.nonces.go_update_user_focuses;
	jQuery.ajax({
		type: "POST",
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce,
			action: 'go_update_user_focuses',
			new_user_focus: jQuery( element ).val(),
			user_id: user_id
		}
	});
}

function check_null( val ) {
	if ( val != '' ) {
		return val;
	} else{
		return 0;
	}
}

function go_clipboard_add() {
	var id_array = [];
	jQuery( '#go_send_message' ).prop( 'disabled', 'disabled' );
	jQuery( "input:checkbox[name=go_selected]:checked" ).each( function() {
		id_array.push( jQuery( this ).val() );
	});

	if ( id_array.length > 0 ) {
		var add_points = parseFloat( check_null( jQuery( '#go_clipboard_points' ).val() ) );
		var add_currency = parseFloat( check_null( jQuery( '#go_clipboard_currency' ).val() ) );
		var add_bonus_currency = parseFloat( check_null( jQuery( '#go_clipboard_bonus_currency' ).val() ) );
		var add_penalty = parseFloat( check_null( jQuery( '#go_clipboard_penalty' ).val() ) );
		var add_minutes = parseFloat( check_null( jQuery( '#go_clipboard_minutes' ).val() ) );
		var badge_id = parseFloat( check_null( jQuery( '#go_clipboard_badge' ).val() ) );
		var reason = jQuery( '#go_clipboard_reason' ).val();
		if ( '' === reason ) {
			reason = jQuery( '#go_clipboard_reason' ).attr( 'placeholder' );
		}

		var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_add;
		jQuery.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			data: {
				_ajax_nonce: nonce,
				action: 'go_clipboard_add',
				ids: id_array,
				points: add_points,
				currency: add_currency,
				bonus_currency: add_bonus_currency,
				penalty: add_penalty,
				reason: reason,
				minutes: add_minutes,
				badge_ID: badge_id
			},
			success: function( res ) {
				var json_index = res.indexOf( '{"update_status":' );
				var json_data = res.substr( json_index );
				var res_obj = JSON.parse( json_data );
				var succeeded = res_obj.update_status;

				if ( succeeded ) {
					for ( index in id_array ) {
						var current_id = id_array[ index ];
						var user_points = res_obj[ current_id ].points;
						var user_currency = res_obj[ current_id ].currency;
						var user_bonus_currency = res_obj[ current_id ].bonus_currency;
						var user_penalty = res_obj[ current_id ].penalty;
						var user_minutes = res_obj[ current_id ].minutes;
						var user_badge_count = res_obj[ current_id ].badge_count;

						jQuery( '#user_' + current_id + ' .user_points' ).html( user_points );
						jQuery( '#user_' + current_id + ' .user_currency' ).html( user_currency );
						jQuery( '#user_' + current_id + ' .user_bonus_currency' ).html( user_bonus_currency );
						jQuery( '#user_' + current_id + ' .user_penalty' ).html( user_penalty );
						jQuery( '#user_' + current_id + ' .user_minutes' ).html( user_minutes );
						jQuery( '#user_' + current_id + ' .user_badge_count' ).html( user_badge_count );
					}
				}
				go_clipboard_clear_fields();
				jQuery( '#go_send_message' ).prop( 'disabled', false );
				jQuery( '#go_clipboard_table input[type="checkbox"]' ).removeAttr( 'checked' );
			}
		});
	} else {
		go_clipboard_clear_fields();
		jQuery( '#go_send_message' ).prop( 'disabled', false );
	}
}
*/
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
var a=jQuery("#name").val();jQuery("#go_map_shortcode_id .acf-input").html('Place this code in a content area to link directly to this map.<br><br>[go_single_map_link map_id="'+e+'"]'+a+"[/go_single_map_link]"),null==e&&jQuery("#go_map_shortcode_id").hide()}String.prototype.getMid=function(e,t){if("string"==typeof e&&"string"==typeof t){var a=e.length,s=this.length-(e.length+t.length),o;return this.substr(a,s)}},Math.round10||(Math.round10=function(e,t){return decimalAdjust("round",e,t)}),
// Decimal floor
Math.floor10||(Math.floor10=function(e,t){return decimalAdjust("floor",e,t)}),
// Decimal ceil
Math.ceil10||(Math.ceil10=function(e,t){return decimalAdjust("ceil",e,t)}),
//This is used to render the quizes
//it is used in the function go_test_field_on_toggle
/**
 * Retrieves the jQuery object of the nth previous element.
 *
 * @since 3.0.0
 *
 * @see jQuery.prototype.prev()
 *
 * @param int    n        The number of times to call `jQuery.prev()`.
 * @param string selector Optional. The selector to be passed to each query.
 * @return jQuery|null The nth previous sibling, or null if none are found in the nth previous
 *                     position.
 */
jQuery.prototype.go_prev_n=function(e,t){if(void 0===e)
//console.error( 'Game On Error: go_prev_n() requires at least one argument.' );
return null;"int"!=typeof e&&(e=Number.parseInt(e));for(var a=null,s=0;s<e;s++)if(0===s)a=void 0!==t?jQuery(this).prev(t):jQuery(this).prev();else{if(null===a)break;a=void 0!==t?jQuery(a).prev(t):jQuery(a).prev()}return a},//Add an on click to all store items
jQuery(document).ready(function(){jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)})}),jQuery(window).ready(function(){
//jQuery(".mejs-container").hide();
Vids_Fit_and_Box()}),function(i){"use strict";i.fn.fitVids=function(e){var a={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){
// appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
var t=document.head||document.getElementsByTagName("head")[0],s=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}",o=document.createElement("div");o.innerHTML='<p>x</p><style id="fit-vids-style">'+s+"</style>",t.appendChild(o.childNodes[1])}return e&&i.extend(a,e),this.each(function(){var e=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];a.customSelector&&e.push(a.customSelector);var r=".fitvidsignore";a.ignore&&(r=r+", "+a.ignore);var t=i(this).find(e.join(","));// Disable FitVids on this video.
(// SwfObj conflict patch
t=(t=t.not("object object")).not(r)).each(function(){var e=i(this);if(!(0<e.parents(r).length||"embed"===this.tagName.toLowerCase()&&e.parent("object").length||e.parent(".fluid-width-video-wrapper").length)){e.css("height")||e.css("width")||!isNaN(e.attr("height"))&&!isNaN(e.attr("width"))||(e.attr("height",9),e.attr("width",16));var t,a,s=("object"===this.tagName.toLowerCase()||e.attr("height")&&!isNaN(parseInt(e.attr("height"),10))?parseInt(e.attr("height"),10):e.height())/(isNaN(parseInt(e.attr("width"),10))?e.width():parseInt(e.attr("width"),10));if(!e.attr("name")){var o="fitvid"+i.fn.fitVids._count;e.attr("name",o),i.fn.fitVids._count++}e.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*s+"%"),e.removeAttr("height").removeAttr("width")}})})},
// Internal counter for unique video names.
i.fn.fitVids._count=0}(window.jQuery||window.Zepto),jQuery(document).ready(function(){jQuery("#records_tabs").length&&(jQuery("#records_tabs").tabs(),jQuery(".clipboard_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){
/*
                case 'messages':
                    //console.log("messages");
                    go_clipboard_class_a_choice_messages();
                    break;
                    */
case"activity":
//console.log("activity");
go_clipboard_class_a_choice_activity(),jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc();break;case"clipboard":
//console.log("activity");
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc();break}})),jQuery("#go_clipboard_stats_datatable").length&&(go_clipboard_class_a_choice(),jQuery(".datepicker").datepicker({firstDay:0}),jQuery(".datepicker").datepicker("setDate",new Date),jQuery(".datepicker").change(function(){
//console.log("change");
jQuery("#go_clipboard_activity_datatable").html("<div id='loader' style='font-size: 1.5em; text-align: center; height: 200px'>loading . . .</div>"),go_clipboard_class_a_choice_activity(!0)}),jQuery(".go_datepicker_refresh").click(function(){jQuery("#go_clipboard_activity_datatable").html("<div id='loader' style='font-size: 1.5em; text-align: center; height: 200px'>loading . . .</div>"),go_clipboard_class_a_choice_activity(!0)}))}),
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
/*
 * Disable submit with enter key, tab to next field instead
*/
jQuery("input,select").bind("keydown",function(e){var t;13===(e.keyCode||e.which)&&(e.preventDefault(),jQuery("input, select, textarea")[jQuery("input,select,textarea").index(this)+1].focus())}),jQuery(document).ready(function(){go_hide_child_tax_acfs(),jQuery(".taxonomy-task_chains #parent, .taxonomy-go_badges #parent").change(function(){go_hide_child_tax_acfs()})});