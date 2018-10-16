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


function go_noty_close_oldest(){
    Noty.setMaxVisible(6);
    var noty_list_count = jQuery('#noty_layout__topRight > div').length;
    if(noty_list_count == 0) {
        jQuery('#noty_layout__topRight').remove();
    }
    if(noty_list_count >= 5) {
        jQuery('#noty_layout__topRight > div').first().trigger( "click" );
    }
}




function go_lightbox_blog_img(){
    jQuery('[class*= wp-image]').each(function(  ) {
        var fullSize = jQuery( this ).hasClass( "size-full" );
        //console.log("fullsize:" + fullSize);
        if (fullSize == true) {
            var imagesrc = jQuery(this).attr('src');
        }else{

            var class1 = jQuery(this).attr('class');
            //console.log(class1);
            //var patt = /w3schools/i;
            var regEx = /.*wp-image/;
            var imageID = class1.replace(regEx, 'wp-image');
            //console.log(imageID);

            var src1 = jQuery(this).attr('src');
            //console.log(src1);
            //var patt = /w3schools/i;
            var regEx2 = /-([^-]+).$/;


            //var regEx3 = /\.[0-9a-z]+$/i;
            var patt1 = /\.[0-9a-z]+$/i;
            var m1 = (src1).match(patt1);

            //var imagesrc = src1.replace(regEx2, regEx3);
            var imagesrc = src1.replace(regEx2, m1);
            //console.log(imagesrc);
        }
        jQuery(this).featherlight(imagesrc);
    });
}


function go_admin_bar_stats_page_button( id ) {//this is called from the admin bar and is hard coded in the php code
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_admin_bar_stats;

    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_admin_bar_stats',
            uid: id
        },
        success: function( res ) {
            if ( -1 !== res ) {
                /*
                jQuery( '#go_stats_white_overlay' ).html( res );
                jQuery( '#go_stats_page_black_bg' ).show();
                jQuery( '#go_stats_white_overlay' ).show();
                jQuery( '#go_stats_hidden_input' ).val( id );

                // this will stop the body from scrolling behind the stats page
                jQuery( 'html' ).addClass( 'go_no_scroll' );
                */
                jQuery.featherlight(res, {variant: 'stats'});

                go_stats_task_list();

                jQuery('#stats_tabs').tabs();
                jQuery( '.stats_tabs' ).click( function() {
                    //console.log("tabs");
                    tab = jQuery(this).attr('tab');
                    switch (tab) {
                        case 'about':
                            go_stats_about();
                            break;
                        case 'tasks':
                            go_stats_task_list();
                            break;
                        case 'store':
                            go_stats_item_list();
                            break;
                        case 'history':
                            go_stats_activity_list();
                            break;
                        case 'badges':
                            go_stats_badges_list();
                            break;
                        case 'groups':
                            go_stats_groups_list();
                            break;
                        case 'leaderboard':
                            go_stats_leaderboard();
                            break;


                    }
                });


            }
        }
    });
}

function go_stats_links(){
    jQuery('.go_user_link_stats').prop('onclick',null).off('click');
    jQuery('.go_user_link_stats').one('click', function(){  var user_id = jQuery(this).attr('name'); go_admin_bar_stats_page_button(user_id)});
}

function go_stats_about(user_id) {
    console.log("about");
    //jQuery(".go_datatables").hide();
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_about;
    if ( jQuery( "#go_stats_about" ).length == 0 ) {
        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_about',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    console.log(res);
                    console.log("about me");
                    //jQuery( '#go_stats_body' ).html( '' );
                    //var oTable = jQuery('#go_tasks_datatable').dataTable();
                    //oTable.fnDestroy();

                    jQuery('#stats_about').html(res);


                }
            }
        });
    }
}

function go_blog_lightbox_opener(post_id){
    console.log("open");
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_lightbox_opener;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_blog_lightbox_opener',
            blog_post_id: post_id
        },
        success: function (res) {
            if (-1 !== res) {
                jQuery.featherlight(res, {variant: 'blog_post'});

                jQuery(".go_blog_lightbox").off().one("click", function(){
                    go_blog_lightbox_opener(this.id);
                });

            }

        }
    });
}

//The v4 no Server Side Processing (SSP)
function go_stats_task_list() {
    jQuery( '#go_task_list_single' ).remove();
    jQuery("#go_task_list").show();

    var table = jQuery('#go_tasks_datatable').DataTable();
    table.columns.adjust().draw();

    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_task_list;
    if ( jQuery( "#go_tasks_datatable" ).length == 0) {
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
                        jQuery('#stats_tasks').html(res);
                        jQuery('#go_tasks_datatable').dataTable({
                            responsive: true,
                            "autoWidth": false,
                            "order": [[jQuery('th.go_tasks_timestamps').index(), "desc"]],
                            "columnDefs": [
                                {
                                    "targets": 'go_tasks_reset',
                                    sortable: false,
                                }
                            ],
                            "drawCallback": function( ) {
                                    var user_id = jQuery("#go_stats_messages_icon_stats").attr("name");
                                    jQuery('.go_reset_task').prop('onclick',null).off('click');
                                    jQuery(".go_reset_task").one("click", function(){
                                        go_messages_opener( user_id, this.id, 'reset' );
                                    });
                                    jQuery('.go_tasks_reset_multiple').prop('onclick',null).off('click');
                                    jQuery(".go_tasks_reset_multiple").one("click", function(){
                                        go_messages_opener( user_id, null, 'reset_multiple' );
                                    });

                                    jQuery(".go_blog_lightbox").off().one("click", function(){
                                        go_blog_lightbox_opener(this.id);
                                     });

                            }
                        });

                    }

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
                    */


                }
            });
    }
}

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
*/

function go_stats_single_task_activity_list (postID) {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_single_task_activity_list;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_stats_single_task_activity_list',
            user_id: jQuery( '#go_stats_hidden_input' ).val(),
            postID: postID
        },

        success: function( res ) {
            if ( -1 !== res ) {
                //jQuery( '#go_stats_body' ).html( '' );
                jQuery( '#go_task_list_single' ).remove();
                jQuery("#go_task_list").hide();
                jQuery( '#stats_tasks' ).append( res );
                jQuery( '#go_single_task_datatable' ).dataTable( {

                    "bPaginate": true,
                    "order": [[0, "desc"]],
                    //"destroy": true,
                    responsive: true,
                    "autoWidth": false
                });
            }
        }
    });
}

function go_stats_item_list() {
    //console.log("store");
    //jQuery(".go_datatables").hide();
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_item_list;
    if (jQuery("#go_store_datatable").length == 0 ) {
        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_item_list',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    jQuery('#stats_store').html(res);
                    jQuery('#go_store_datatable').dataTable({

                        "bPaginate": true,
                        "order": [[0, "desc"]],
                        //"destroy": true,
                        responsive: true,
                        "autoWidth": false
                    });
                }
            }
        });
    }
}

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
function go_stats_activity_list() {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_activity_list;
    if (jQuery("#go_activity_datatable").length == 0) {
        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_activity_list',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    jQuery('#stats_history').html(res);
                    jQuery('#go_activity_datatable').dataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": MyAjax.ajaxurl + '?action=go_activity_dataloader_ajax',
                            "data": function(d){
                                d.user_id = jQuery('#go_stats_hidden_input').val();}//this doesn't actually pass something to my PHP like it does normally with AJAX.
                        },
                        responsive: true,
                        "autoWidth": false,
                        columnDefs: [
                            { targets: '_all', "orderable": false }
                        ],

                        "searching": true,
                        /*'createdRow': function (row, data, dataIndex) {
                            var dateCell = jQuery(row).find('td:eq(0)').text(); // get first column

                            var d = new Date(dateCell * 1000);
                            var month = d.getMonth() + 1;
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
                            //var seconds = "0" + d.getSeconds();

// Will display time in 10:30:23 format
                            var formattedTime = month + "/" + day + "/" + year + "  " + h + ':' + minutes.substr(-2) + " " + dd;
                            jQuery(row).find('td:eq(0)').attr("data-order", dateCell).text(formattedTime);


                        }*/


                    });
                }
            }
        });
    }
}

function go_stats_badges_list() {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_badges_list;
    if (jQuery("#go_badges_list").length == 0) {

        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_badges_list',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                //console.log(res);
                if (-1 !== res) {
                    jQuery('#stats_badges').html(res);
                }
            }
        });
    }
}

function go_stats_groups_list() {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_groups_list;

    if (jQuery("#go_groups_list").length == 0) {
        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_groups_list',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    jQuery('#stats_groups').html(res);
                }
            }
        });
    }
}

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
function go_filter_datatables() { //function that filters all tables on draw
    jQuery.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var mytable = settings.sTableId;
            //console.log(myTable);
            if (mytable == "go_clipboard_stats_datatable" || mytable == "go_clipboard_messages_datatable" || mytable == "go_clipboard_activity_datatable") {
                var section = jQuery('#go_clipboard_user_go_sections_select').val();
                var group = jQuery('#go_clipboard_user_go_groups_select').val();
                var badge = jQuery('#go_clipboard_go_badges_select').val();
                var badges =  data[4] ;
                var groups =  data[3] ; // use data for the filter by column
                var sections = data[2]; // use data for the filter by column
                console.log("data" + data);
                console.log("badges" + badges);
                console.log("groups" + groups);
                console.log("sections" + sections);
                //console.log(sections);


                groups = JSON.parse(groups);

                console.log("groups" + groups);
                //sections = JSON.parse(sections);
                badges = JSON.parse(badges);
                console.log("badges" + badges);

                console.log("sections" + sections);

                var inlist = true;
                if( group == "none" || jQuery.inArray(group, groups) != -1) {
                    inlist = true;
                }else {
                    inlist = false;
                }

                if (inlist){
                    if( section == "none" || sections == section) {
                        inlist = true;
                    }else {
                        inlist = false;
                    }
                }
                if (mytable == "go_clipboard_datatable") {
                    if (inlist) {
                        if (badge == "none" || jQuery.inArray(badge, badges) != -1) {
                            inlist = true;
                        } else {
                            inlist = false;
                        }
                    }
                }
                return inlist;
            }
            else if (mytable == "go_leaders_datatable") {
                var section = jQuery('#go_user_go_sections_select').val();
                var group = jQuery('#go_user_go_groups_select').val();

                //var badges =  data[3] ;
                var groups =  data[2] ; // use data for the filter by column
                var sections = data[1]; // use data for the filter by column


                groups = JSON.parse(groups);
                sections = JSON.parse(sections);
                //badges = JSON.parse(badges);


                var inlist = true;
                if( group == "none" || jQuery.inArray(group, groups) != -1) {
                    inlist = true;
                }else {
                    inlist = false;
                }

                if (inlist){
                    if( section == "none" || jQuery.inArray(section, sections) != -1) {
                        inlist = true;
                    }else {
                        inlist = false;
                    }
                }

                return inlist;
            }else{
                return true;
            }
        });
}

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
*/
function go_stats_leaderboard() {
    jQuery( '#go_stats_lite_wrapper' ).remove();
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
                console.log("________here___________");
                if (jQuery("#go_leaders_datatable").length) {

                    //XP////////////////////////////
                    //go_sort_leaders("go_xp_leaders_datatable", 4);
                    var table = jQuery('#go_leaders_datatable').DataTable({
                        //"orderFixed": [[4, "desc"]],
                        //"destroy": true,
                        responsive: false,
                        "autoWidth": false,
                        "paging": true,
                        "order": [[4, "desc"]],
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

                    table.on( 'order.dt search.dt', function () {
                        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                            cell.innerHTML = i+1;
                        } );
                    } ).draw();
                }


                // Event listener to the two range filtering inputs to redraw on input
                jQuery('#go_user_go_sections_select, #go_user_go_groups_select').change( function() {
                    if (jQuery("#go_leaders_datatable").length) {
                        table.draw();
                    }
                } );

                //});

            }
        });
    }
}

function go_stats_lite (user_id) {
    //jQuery(".go_datatables").hide();
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_lite;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_stats_lite',
            uid: user_id
        },
        success: function( res ) {
            if ( -1 !== res ) {
                //jQuery( '#go_stats_body' ).html( '' );
                jQuery( '#go_stats_lite_wrapper' ).remove();
                jQuery( '#stats_leaderboard' ).append( res );
                jQuery("#go_leaderboard_wrapper").hide();
                jQuery('#go_tasks_datatable_lite').dataTable({
                    "destroy": true,
                    responsive: true,
                    "autoWidth": false,

                });


            }
        }
    });
}

//	Grabs substring in the middle of the string object that getMid() is being called from.
//	Takes two strings, one from the left and one from the right.
String.prototype.getMid = function( str_1, str_2 ) {
    if ( 'string' === typeof( str_1 ) && 'string' === typeof( str_2 ) ) {
        var start = str_1.length;
        var substr_length = this.length - ( str_1.length + str_2.length );
        var substr = this.substr( start, substr_length );
        return substr;
    } else {
        if ( 'string' !== typeof( str_1 ) && 'string' !== typeof( str_2 ) ) {
            //console.error("String.prototype.getMid expects two strings as args.");
        } else if ( 'string' !== typeof( str_1 ) ) {
            //console.error("String.prototype.getMid expects 1st arg to be string.");
        } else if ( 'string' !== typeof( str_2 ) ) {
            //console.error("String.prototype.getMid expects 2nd arg to be string.");
        }
    }
}

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
jQuery.prototype.go_prev_n = function ( n, selector ) {
    if ( 'undefined' === typeof n ) {
        //console.error( 'Game On Error: go_prev_n() requires at least one argument.' );
        return null;
    } else if ( 'int' !== typeof n ) {
        n = Number.parseInt( n );
    }

    var obj = null;
    for ( var x = 0; x < n; x++ ) {
        if ( 0 === x ) {
            if ( 'undefined' !== typeof selector ) {
                obj = jQuery( this ).prev( selector );
            } else {
                obj = jQuery( this ).prev();
            }
        } else if ( null !== obj ) {
            if ( 'undefined' !== typeof selector ) {
                obj = jQuery( obj ).prev( selector );
            } else {
                obj = jQuery( obj ).prev();
            }
        } else {
            break;
        }
    }

    return obj;
};

