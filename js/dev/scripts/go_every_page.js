function go_user_profile_link(uid){
    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            //_ajax_nonce: nonce,
            action: 'go_user_profile_link',
            uid: uid
        },
        success: function (url) {
            window.open(url);
        }
    })
}

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
                        case 'messages':
                            go_stats_messages();
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

    jQuery('.go_stats_messages_icon').prop('onclick',null).off('click');
    jQuery(".go_stats_messages_icon").one("click", function(e){ var user_id = jQuery(this).attr("name"); go_messages_opener(user_id); });

    jQuery('#go_user_go_sections_select').select2({
        ajax: {
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 400, // delay in ms while typing when to perform a AJAX search
            data: function (params) {

                return {
                    q: params.term, // search query
                    action: 'go_make_taxonomy_dropdown_ajax', // AJAX action for admin-ajax.php
                    taxonomy: 'user_go_sections'
                };


            },
            processResults: function( data ) {
                console.log ("here: " + data);

                var options = [];
                if (data) {
                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    jQuery.each(data, function (index, text) { // do not forget that "index" is just auto incremented value
                        options.push({id: text[0], text: text[1]});
                    });

                }
                jQuery("#go_user_go_sections_select").select2("destroy");
                jQuery('#go_user_go_sections_select').children().remove();
                jQuery("#go_user_go_sections_select").select2({
                    data:options,
                    placeholder: "Show All",
                    allowClear: true});
                jQuery("#go_user_go_sections_select").select2("open");
                return {
                    results: options
                };

            },
            cache: true
        },
        minimumInputLength: 0, // the minimum of symbols to input before perform a search
        multiple: false,
        placeholder: "Show All",
        allowClear: true
    });
    jQuery('#go_user_go_groups_select').select2({
        ajax: {
            url: ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 400, // delay in ms while typing when to perform a AJAX search
            data: function (params) {
                return {
                    q: params.term, // search query
                    action: 'go_make_taxonomy_dropdown_ajax', // AJAX action for admin-ajax.php
                    taxonomy: 'user_go_groups'
                };
            },
            processResults: function( data ) {
                //console.log("search results: " + data);
                var options = [];
                if ( data ) {

                    // data is the array of arrays, and each of them contains ID and the Label of the option
                    jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                        options.push( { id: text[0], text: text[1]  } );
                    });

                }
                jQuery("#go_user_go_groups_select").select2("destroy");
                jQuery('#go_user_go_groups_select').children().remove();
                jQuery("#go_user_go_groups_select").select2({
                    data:options,
                    placeholder: "Show All",
                    allowClear: true});
                jQuery("#go_user_go_groups_select").select2("open");
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 0, // the minimum of symbols to input before perform a search
        multiple: false,
        placeholder: "Show All",
        allowClear: true
    });

}

function go_stats_about(user_id) {
    //console.log("about");
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
                    //console.log(res);
                    //console.log("about me");
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
    //console.log("open");
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
                            deferRender: true,
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
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": MyAjax.ajaxurl + '?action=go_stats_store_item_dataloader',
                            "data": function(d){
                                d.user_id = jQuery('#go_stats_hidden_input').val();}
                        },
                        responsive: true,
                        "autoWidth": false,
                        columnDefs: [
                            { targets: '_all', "orderable": false }
                        ],
                        "searching": true,
                        "order": [[0, "desc"]]
                    });
                }
            }
        });
    }
}

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
                                d.user_id = jQuery('#go_stats_hidden_input').val();}
                        },
                        responsive: true,
                        "autoWidth": false,
                        columnDefs: [
                            { targets: '_all', "orderable": false }
                        ],

                        "searching": true,

                        "order": [[0, "desc"]],
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

function go_stats_messages() {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_messages;
    if (jQuery("#go_messages_datatable").length == 0) {
        jQuery.ajax({
            type: 'post',
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_stats_messages',
                user_id: jQuery('#go_stats_hidden_input').val()
            },
            success: function (res) {
                if (-1 !== res) {
                    jQuery('#stats_messages').html(res);
                    jQuery('#go_messages_datatable').dataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": MyAjax.ajaxurl + '?action=go_messages_dataloader_ajax',
                            "data": function(d){
                                d.user_id = jQuery('#go_stats_hidden_input').val();}
                        },
                        responsive: true,
                        "autoWidth": false,
                        columnDefs: [
                            { targets: '_all', "orderable": false }
                        ],
                        "searching": true,
                        "order": [[0, "desc"]]
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

function go_stats_leaderboard() {
    var nonce_leaderboard = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard;
    var is_admin = GO_EVERY_PAGE_DATA.go_is_admin;
    var initial_sort = 3;
    if (is_admin == true){
        initial_sort = 4;
    }
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
                //console.log(raw);
                //console.log('success');

                jQuery('#stats_leaderboard').html(raw);


                    //XP////////////////////////////
                    //go_sort_leaders("go_xp_leaders_datatable", 4);
                    var table = jQuery('#go_leaders_datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": MyAjax.ajaxurl + '?action=go_stats_leaderboard_dataloader_ajax',
                            "data": function(d){
                                //d.user_id = jQuery('#go_stats_hidden_input').val();
                                //d.date = jQuery( '.datepicker' ).val();
                                d.section = jQuery('#go_user_go_sections_select').val();
                                d.group = jQuery('#go_user_go_groups_select').val();
                                //d.badge = jQuery('#go_clipboard_go_badges_select').val();
                            }
                        },
                        //"orderFixed": [[4, "desc"]],
                        //"destroy": true,
                        responsive: false,
                        "autoWidth": false,
                        "paging": true,
                        "order": [[initial_sort, "desc"]],
                        "drawCallback": function( settings ) {
                            go_stats_links();
                        },
                        "searching": false,
                        "columnDefs": [
                            { type: 'natural', targets: '_all'},
                            {
                                "targets": [0],
                                sortable: false
                            },
                            {
                                "targets": [1],
                                sortable: false
                            },
                            {
                                "targets": [2],
                                sortable: false
                            },
                            {
                                "targets": [3],
                                sortable: false,
                            },
                            {
                                "targets": [4],
                                sortable: true,
                                "orderSequence": [ "desc" ]
                            },
                            {
                                "targets": [5],
                                sortable: true,
                                "orderSequence": [ "desc" ]
                            },
                            {
                                "targets": [6],
                                sortable: true,
                                "orderSequence": [ "desc" ]
                            },
                        ],
                    });




                // Event listener to the range filtering inputs to redraw on input
                jQuery('#go_user_go_sections_select, #go_user_go_groups_select').change( function() {
                    var section = jQuery('#go_user_go_sections_select').val();
                    console.log(section);
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

            jQuery.featherlight(res, {variant: 'stats_lite'});

            if ( -1 !== res ) {
                //jQuery( '#go_stats_lite_wrapper' ).remove();
                //jQuery( '#stats_leaderboard' ).append( res );
                //jQuery("#go_leaderboard_wrapper").hide();
                jQuery('#go_tasks_datatable_lite').dataTable({
                    "destroy": true,
                    responsive: true,
                    "autoWidth": false,
                    "drawCallback": function( settings ) {
                        go_stats_links();
                    },
                    "searching": false


                });


            }
        }
    });
}

