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
var s=jQuery("#name").val();jQuery("#go_map_shortcode_id .acf-input").html('Place this code in a content area to link directly to this map.<br><br>[go_single_map_link map_id="'+e+'"]'+s+"[/go_single_map_link]"),null==e&&jQuery("#go_map_shortcode_id").hide()}
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
if(1==jQuery(this).hasClass("size-full"))var t=jQuery(this).attr("src");else var s,a=/.*wp-image/,o=jQuery(this).attr("class").replace(a,"wp-image"),r=jQuery(this).attr("src"),_=/-([^-]+).$/,n=/\.[0-9a-z]+$/i,i=r.match(n),t=r.replace(_,i);
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
tab=jQuery(this).attr("tab"),tab){case"about":go_stats_about();break;case"tasks":go_stats_task_list();break;case"store":go_stats_item_list();break;case"history":go_stats_activity_list();break;case"badges":go_stats_badges_list();break;case"groups":go_stats_groups_list();break;case"leaderboard":go_stats_leaderboard();break}}))}})}function go_stats_links(){jQuery(".go_user_link_stats").prop("onclick",null).off("click"),jQuery(".go_user_link_stats").one("click",function(){var e;go_admin_bar_stats_page_button(jQuery(this).attr("name"))})}function go_stats_about(e){console.log("about");
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
jQuery.fn.dataTable.ext.search.push(function(e,t,s){var a=e.sTableId;
//console.log(myTable);
if("go_clipboard_stats_datatable"==a||"go_clipboard_messages_datatable"==a||"go_clipboard_activity_datatable"==a){var o=jQuery("#go_clipboard_user_go_sections_select").val(),r=jQuery("#go_clipboard_user_go_groups_select").val(),_=jQuery("#go_clipboard_go_badges_select").val(),n=t[4],i=t[3],l=t[2];// use data for the filter by column
console.log("data"+t),console.log("badges"+n),console.log("groups"+i),console.log("sections"+l),
//console.log(sections);
i=JSON.parse(i),console.log("groups"+i),
//sections = JSON.parse(sections);
n=JSON.parse(n),console.log("badges"+n),console.log("sections"+l);var u=!0;return(u="none"==r||-1!=jQuery.inArray(r,i))&&(u="none"==o||l==o),"go_clipboard_datatable"==a&&u&&(u="none"==_||-1!=jQuery.inArray(_,n)),u}if("go_leaders_datatable"!=a)return!0;var o=jQuery("#go_user_go_sections_select").val(),r=jQuery("#go_user_go_groups_select").val(),i=t[2],l=t[1];// use data for the filter by column
i=JSON.parse(i),l=JSON.parse(l);
//badges = JSON.parse(badges);
var u=!0;return(u="none"==r||-1!=jQuery.inArray(r,i))&&(u="none"==o||-1!=jQuery.inArray(o,l)),u})}
/*
function go_stats_leaderboard() {
    //jQuery( '#go_stats_lite_wrapper' ).remove();
    jQuery("#go_leaderboard_wrapper").show();
    go_filter_datatables();

    //var nonce_leaderboard_choices = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard_choices;
    //remove from localized data and actions
    var nonce_leaderboard = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard;
    if (jQuery("#go_leaderboard_wrapper").length == 0) {
        jQuery(".go_leaderboard_wrapper").show();
        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce_leaderboard,
                action: 'go_stats_leaderboard',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function( raw ) {
                console.log('success');
                ////console.log(raw);
                // parse the raw response to get the desired JSON
                var res = {};
                try {
                    var res = JSON.parse( raw );
                } catch (e) {
                    console.log("parse_error");
                }
                ////console.log(res.xp_sticky);
                //console.log(res.html);

                jQuery('#stats_leaderboard').html(res.html);


					//jQuery(document).ready(function() {
                        console.log("________XP___________");
                        if (jQuery("#go_xp_leaders_datatable").length) {

                            //XP////////////////////////////
							//go_sort_leaders("go_xp_leaders_datatable", 4);
							var table = jQuery('#go_xp_leaders_datatable').DataTable({

								//"paging": true,
								"orderFixed": [[4, "desc"]],
								//"destroy": true,
								responsive: false,
								"autoWidth": false,
								"paging": true,
                                "searching": false,
								"columnDefs": [

                                    {
                                        "targets": [0],
                                        "orderable": false
                                    },
                                    {
										"targets": [1],
										"visible": false
									},
									{
										"targets": [2],
										"visible": false
									},
                                    {
                                        "targets": [3],
                                        "orderable": false
                                    },
                                    {
                                        "targets": [4],
                                        "orderable": false
                                    }
								]
                        	});

                            table.on( 'order.dt search.dt', function () {
                                table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();
                    	}

                        //GOLD

                        if (jQuery("#go_gold_leaders_datatable").length) {
                            //go_sort_leaders("go_gold_leaders_datatable", 4);
                            //console.log("________GOLD___________");
                            var table2 = jQuery('#go_gold_leaders_datatable').DataTable({
                                "paging": true,
                                "orderFixed": [[4, "desc"]],
                                //"destroy": true,
                                responsive: false,
                                "autoWidth": false,
                                "searching": false,
                                "columnDefs": [

                                    {
                                        "targets": [0],
                                        "orderable": false
                                    },
                                    {
                                        "targets": [1],
                                        "visible": false
                                    },
                                    {
                                        "targets": [2],
                                        "visible": false
                                    },
                                    {
                                        "targets": [3],
                                        "orderable": false
                                    },
                                    {
                                        "targets": [4],
                                        "orderable": false
                                    }
                                ]
                            });

                            table2.on( 'order.dt search.dt', function () {
                                table2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();
                        }

                        //C4//////////////////
                        if (jQuery("#go_health_leaders_datatable").length) {
                            //go_sort_leaders("go_c4_leaders_datatable", 4);
                            //console.log("________C4___________");
                            var table3 = jQuery('#go_health_leaders_datatable').DataTable({
                                "paging": true,
                                "orderFixed": [[4, "desc"]],
                                //"destroy": true,
                                responsive: false,
                                "autoWidth": false,
                                "searching": false,
                                "columnDefs": [

                                    {
                                        "targets": [0],
                                        "orderable": false
                                    },
                                    {
                                        "targets": [1],
                                        "visible": false
                                    },
                                    {
                                        "targets": [2],
                                        "visible": false
                                    },
                                    {
                                        "targets": [3],
                                        "orderable": false
                                    },
                                    {
                                        "targets": [4],
                                        "orderable": false
                                    }
                                ]
                            });


                            table3.on( 'order.dt search.dt', function () {
                                table3.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();
                        }

                        //BADGES

                        if (jQuery("#go_badges_leaders_datatable").length) {
                            //go_sort_leaders("go_badges_leaders_datatable", 4);
                            //console.log("________Badges___________");
                            var table4 = jQuery('#go_badges_leaders_datatable').DataTable({
                                "paging": true,
                                "orderFixed": [[4, "desc"]],
                                //"destroy": true,
                                responsive: false,
                                "autoWidth": false,
                                "searching": false,
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

                            table4.on( 'order.dt search.dt', function () {
                                table4.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                    cell.innerHTML = i+1;
                                } );
                            } ).draw();
                        }


                        // Event listener to the two range filtering inputs to redraw on input
                        jQuery('#go_user_go_sections_select, #go_user_go_groups_select').change( function() {
                            if (jQuery("#go_xp_leaders_datatable").length) {
                                table.draw();
                            }
                            if (jQuery("#go_gold_leaders_datatable").length) {
                                table2.draw();
                            }
                            if (jQuery("#go_health_leaders_datatable").length) {
                                table3.draw();
                            }
                            if (jQuery("#go_badges_leaders_datatable").length) {
                                table4.draw();
                            }
                        } );

                //});

            }
        });
    }
}
*/function go_stats_leaderboard(){jQuery("#go_stats_lite_wrapper").remove(),jQuery("#go_leaderboard_wrapper").show(),go_filter_datatables();
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
var s=jQuery("#go_leaders_datatable").DataTable({
//"orderFixed": [[4, "desc"]],
//"destroy": true,
responsive:!1,autoWidth:!1,paging:!0,order:[[4,"desc"]],columnDefs:[{targets:[1],visible:!1},{targets:[2],visible:!1}]});s.on("order.dt search.dt",function(){s.column(0,{search:"applied",order:"applied"}).nodes().each(function(e,t){e.innerHTML=t+1})}).draw()}
// Event listener to the two range filtering inputs to redraw on input
jQuery("#go_user_go_sections_select, #go_user_go_groups_select").change(function(){jQuery("#go_leaders_datatable").length&&s.draw()})}}))}function go_stats_lite(e){
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
function decimalAdjust(e,t,s){
// If the exp is undefined or zero...
return void 0===s||0==+s?Math[e](t):(
// If the value is not a number or the exp is not an integer...
t=+t,s=+s,isNaN(t)||"number"!=typeof s||s%1!=0?NaN:(
// Shift
t=t.toString().split("e"),+((
// Shift back
t=(t=Math[e](+(t[0]+"e"+(t[1]?+t[1]-s:-s)))).toString().split("e"))[0]+"e"+(t[1]?+t[1]+s:s))))}
// Decimal round
// Makes it so you can press return and enter content in a field
function go_make_store_clickable(){
//Make URL button clickable by clicking enter when field is in focus
jQuery(".clickable").keyup(function(e){
// 13 is ENTER
13===e.which&&jQuery("#go_store_pass_button").click()})}
//open the lightbox for the store items
function go_lb_opener(a){if(jQuery("#light").css("display","block"),jQuery(".go_str_item").prop("onclick",null).off("click"),"none"==jQuery("#go_stats_page_black_bg").css("display")&&jQuery("#fade").css("display","block"),!jQuery.trim(jQuery("#lb-content").html()).length){var e=a,t,s={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:e};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:s,beforeSend:function(){jQuery("#lb-content").append('<div class="go-lb-loading"></div>')},cache:!1,success:function(e){console.log("success"),console.log(e);var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}
//console.log('success');
//console.log(raw);
if(console.log("html"),console.log(t.html),console.log(t.json_status),jQuery("#lb-content").innerHTML="",jQuery("#lb-content").html(""),
//jQuery( "#lb-content" ).append(results);
//jQuery('.featherlight-content').html(res.html);
jQuery.featherlight(t.html,{variant:"store"}),"101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var s="Server Error.";jQuery("#go_store_error_msg").text()!=s?jQuery("#go_store_error_msg").text(s):flash_error_msg_store("#go_store_error_msg")}else 302===Number.parseInt(t.json_status)&&(console.log(302),window.location=t.location);jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)}),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(a)}),go_max_purchase_limit()}})}}
//called when the "buy" button is clicked.
function goBuytheItem(t,e){var a=GO_BUY_ITEM_DATA.nonces.go_buy_item,o=GO_BUY_ITEM_DATA.userID;console.log(o),jQuery(document).ready(function(s){var e={_ajax_nonce:a,action:"go_buy_item",the_id:t,qty:s("#go_qty").val(),user_id:o};s.ajax({url:MyAjax.ajaxurl,type:"POST",data:e,beforeSend:function(){s("#golb-fr-buy").innerHTML="",s("#golb-fr-buy").html(""),s("#golb-fr-buy").append('<div id="go-buy-loading" class="buy_gold"></div>')},success:function(e){
//console.log("SUccess: " + raw);
var t={};try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:"101 Error: Please try again."}}-1!==e.indexOf("Error")?s("#light").html(e):
//go_sounds( 'store' );
s("#light").html(t.html)}})})}function flash_error_msg_store(e){var t=jQuery(e).css("background-color");void 0===typeof t&&(t="white"),jQuery(e).animate({color:t},200,function(){jQuery(e).animate({color:"red"},200)})}function go_store_password(a){
//console.log('button clicked');
//disable button to prevent double clicks
//go_enable_loading( target );
var e;if(!(0<jQuery("#go_store_password_result").attr("value").length)){jQuery("#go_store_error_msg").show();var t="Please enter a password.";return jQuery("#go_store_error_msg").text()!=t?jQuery("#go_store_error_msg").text(t):flash_error_msg_store("#go_store_error_msg"),void jQuery("#go_store_pass_button").one("click",function(e){go_store_password(a)})}var s=jQuery("#go_store_password_result").attr("value");if(jQuery("#light").css("display","block"),"none"==jQuery("#go_stats_page_black_bg").css("display")&&jQuery("#fade").css("display","block"),!jQuery.trim(jQuery("#lb-content").html()).length){var o=a,r,_={action:"go_the_lb_ajax",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax,the_item_id:o,skip_locks:!0,result:s};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:_,cache:!1,success:function(e){
//console.log('success');
//console.log(raw);
var t=JSON.parse(e);try{var t=JSON.parse(e)}catch(e){t={json_status:"101",html:""}}
//console.log('html');
//console.log(res.html);
//console.log(res.json_status);
//alert(res.json_status);
if("101"===Number.parseInt(t.json_status)){console.log(101),jQuery("#go_store_error_msg").show();var s="Server Error.";jQuery("#go_store_error_msg").text()!=s?jQuery("#go_store_error_msg").text(s):flash_error_msg_store("#go_store_error_msg")}else if(302===Number.parseInt(t.json_status))console.log(302),window.location=t.location;else if("bad_password"==t.json_status){
//console.log("bad");
jQuery("#go_store_error_msg").show();var s="Invalid password.";jQuery("#go_store_error_msg").text()!=s?jQuery("#go_store_error_msg").text(s):flash_error_msg_store("#go_store_error_msg"),jQuery("#go_store_pass_button").one("click",function(e){go_store_password(a)})}else
//console.log("good");
jQuery("#go_store_pass_button").one("click",function(e){go_store_password(a)}),jQuery("#go_store_lightbox_container").hide(),jQuery(".featherlight-content").html(t.html),go_max_purchase_limit()}})}}function go_max_purchase_limit(){window.go_purchase_limit=jQuery("#golb-fr-purchase-limit").attr("val");var e=go_purchase_limit;jQuery("#go_qty").spinner({max:e,min:1,stop:function(){jQuery(this).change()}}),go_make_store_clickable(),
//jQuery('#go_store_admin_override').click( function () {
//    jQuery('.go_store_lock').show();
//});
jQuery("#go_store_admin_override").one("click",function(e){
//console.log("override");
jQuery(".go_store_lock").show(),jQuery("#go_store_admin_override").hide(),go_make_store_clickable()})}
//Not sure if this is still used
function go_count_item(e){var t=GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:{_ajax_nonce:t,action:"go_get_purchase_count",item_id:e},success:function(e){if(-1!==e){var t=e.toString();jQuery("#golb-purchased").html("Quantity purchased: "+t)}}})}function go_messages_opener(s,e,a){
//jQuery('#go_messages_icon').prop('onclick',null).off('click'); //blog
//remove the onclick events from any message link and then reattach after ajax call
//types of links 1. clipboard 2. stats link 3. task reset button and 4. blog page
//alert(user_id);
if(e=void 0!==e?e:null,a=void 0!==a?a:null,
//console.log(message_type);
jQuery(".go_messages_icon").prop("onclick",null).off("click"),//clipboard
jQuery(".go_stats_messages_icon").prop("onclick",null).off("click"),//stats
jQuery(".go_reset_task").prop("onclick",null).off("click"),jQuery(".go_tasks_reset").prop("onclick",null).off("click"),//reset task links
//reset the multiple task reset button
jQuery(".go_tasks_reset_multiple").prop("onclick",null).off("click"),s)//this is from the stats panel, so user_id was sent so stuff it in an array
var o=[s];else for(//no user_id sent, this is from the clipboard and get user ids from checkboxes
var t=jQuery(".go_checkbox:visible"),o=[],r=0;r<t.length;r++)!0===t[r].checked&&o.push(jQuery(t[r]).val());if(null==e&&"reset_multiple"==a)for(//this is a reset multiple quests from stats panel
var t=jQuery(".go_checkbox:visible"),_=[],r=0;r<t.length;r++)!0===t[r].checked&&_.push(jQuery(t[r]).val());else//this is from the stats panel, so user_id was sent so stuff it in an array
var _=[e];var n,i={action:"go_create_admin_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_create_admin_message,post_id:_,user_ids:o,message_type:a};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:i,success:function(e){
//console.log(results);
jQuery.featherlight(e,{variant:"message"}),jQuery(".go_tax_select").select2(),jQuery("#go_message_submit").one("click",function(e){go_send_message(o,_,a)}),
//clipboard
jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),
//stats and blog
//console.log("hi:" + user_id);
jQuery(".go_stats_messages_icon").one("click",function(e){var t;go_messages_opener(jQuery(this).attr("name"))});
//reset task links
var t=jQuery("#go_stats_messages_icon_stats").attr("name");jQuery(".go_reset_task").one("click",function(e){go_messages_opener(t,this.id,"reset")}),
//reset multiple tasks link
jQuery(".go_tasks_reset_multiple").one("click",function(){go_messages_opener(s,null,"reset_multiple")})},error:function(e,t,s){
//clipboard
jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()});
//stats and blog
var a=jQuery("#go_stats_messages_icon_stats").attr("name");jQuery("#go_stats_messages_icon").one("click",function(e){go_messages_opener(a)}),
//reset task links
jQuery(".go_reset_task").one("click",function(e){go_messages_opener(a,this.id,"reset")})}})}function go_send_message(e,t,s){
//replace button with loader
//check for negative numbers and give error
//user_ids
var a=jQuery("[name=title]").val(),o=jQuery("[name=message]").val(),r=jQuery("[name=xp_toggle]").siblings().hasClass("-on")?1:-1,_=jQuery("[name=xp]").val()*r,n=jQuery("[name=gold_toggle]").siblings().hasClass("-on")?1:-1,i=jQuery("[name=gold]").val()*n,l=jQuery("[name=health_toggle]").siblings().hasClass("-on")?1:-1,u=jQuery("[name=health]").val()*l,c=jQuery("[name=c4_toggle]").siblings().hasClass("-on")?1:-1,g=jQuery("[name=c4]").val()*c,d=jQuery("#go_messages_go_badges_select").val(),y=jQuery("[name=badges_toggle]").siblings().hasClass("-on"),j=jQuery("#go_messages_user_go_groups_select").val(),p=jQuery("[name=groups_toggle]").siblings().hasClass("-on"),h,m={action:"go_send_message",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_send_message,post_id:t,user_ids:e,message_type:s,title:a,message:o,xp:_,gold:i,health:u,c4:g,badges_toggle:y,badges:d,groups_toggle:p,groups:j};jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:m,success:function(e){
// show success or error message
jQuery("#go_messages_container").html("Message sent successfully."),jQuery("#go_tasks_datatable").remove(),go_stats_task_list(),go_toggle_off()},error:function(e,t,s){jQuery("#go_messages_container").html("Error.")}})}jQuery("input,select").bind("keydown",function(e){var t;13===(e.keyCode||e.which)&&(e.preventDefault(),jQuery("input, select, textarea")[jQuery("input,select,textarea").index(this)+1].focus())}),jQuery(document).ready(function(){go_hide_child_tax_acfs(),jQuery(".taxonomy-task_chains #parent, .taxonomy-go_badges #parent").change(function(){go_hide_child_tax_acfs()})}),String.prototype.getMid=function(e,t){if("string"==typeof e&&"string"==typeof t){var s=e.length,a=this.length-(e.length+t.length),o;return this.substr(s,a)}},Math.round10||(Math.round10=function(e,t){return decimalAdjust("round",e,t)}),
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
return null;"int"!=typeof e&&(e=Number.parseInt(e));for(var s=null,a=0;a<e;a++)if(0===a)s=void 0!==t?jQuery(this).prev(t):jQuery(this).prev();else{if(null===s)break;s=void 0!==t?jQuery(s).prev(t):jQuery(s).prev()}return s},//Add an on click to all store items
jQuery(document).ready(function(){jQuery(".go_str_item").one("click",function(e){go_lb_opener(this.id)})}),function(_){"use strict";_.fn.fitVids=function(e){var s={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){
// appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
var t=document.head||document.getElementsByTagName("head")[0],a=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}",o=document.createElement("div");o.innerHTML='<p>x</p><style id="fit-vids-style">'+a+"</style>",t.appendChild(o.childNodes[1])}return e&&_.extend(s,e),this.each(function(){var e=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];s.customSelector&&e.push(s.customSelector);var r=".fitvidsignore";s.ignore&&(r=r+", "+s.ignore);var t=_(this).find(e.join(","));// Disable FitVids on this video.
(// SwfObj conflict patch
t=(t=t.not("object object")).not(r)).each(function(){var e=_(this);if(!(0<e.parents(r).length||"embed"===this.tagName.toLowerCase()&&e.parent("object").length||e.parent(".fluid-width-video-wrapper").length)){e.css("height")||e.css("width")||!isNaN(e.attr("height"))&&!isNaN(e.attr("width"))||(e.attr("height",9),e.attr("width",16));var t,s,a=("object"===this.tagName.toLowerCase()||e.attr("height")&&!isNaN(parseInt(e.attr("height"),10))?parseInt(e.attr("height"),10):e.height())/(isNaN(parseInt(e.attr("width"),10))?e.width():parseInt(e.attr("width"),10));if(!e.attr("name")){var o="fitvid"+_.fn.fitVids._count;e.attr("name",o),_.fn.fitVids._count++}e.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",100*a+"%"),e.removeAttr("height").removeAttr("width")}})})},
// Internal counter for unique video names.
_.fn.fitVids._count=0}(window.jQuery||window.Zepto);