jQuery( document ).ready( function() {
    	if ( jQuery( '#records_tabs' ).length ) {
		jQuery('#records_tabs').tabs();
        jQuery( '.clipboard_tabs' ).click( function() {
        	//console.log("tabs");
            tab = jQuery(this).attr('tab');
            switch (tab) {

                case 'clipboard':
                    console.log("stats1");
                    go_clipboard_stats_datatable(false);
                    //force window resize on load to initialize responsive behavior
                    jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust()
                        .responsive.recalc();
                    break;
                case 'store':
                    //console.log("messages");
                    go_clipboard_store_datatable();
                    //force window resize on load to initialize responsive behavior
                    jQuery("#go_clipboard_store_datatable").DataTable().columns.adjust()
                       .responsive.recalc();
                    break;
                case 'messages':
                    //console.log("messages");
                    go_clipboard_messages_datatable();
                    //force window resize on load to initialize responsive behavior
                    jQuery("#go_clipboard_messages_datatable").DataTable().columns.adjust()
                        .responsive.recalc();
                    break;
                case 'activity':
                    //console.log("activity");
                    go_clipboard_activity_datatable();
                    jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust()
                        .responsive.recalc();
                    break;
            }
        });
	}

	if ( jQuery( "#records_tabs" ).length ) {
        go_clipboard_stats_datatable(false);
        jQuery("#records_tabs").css("margin-left", '');

        jQuery( ".datepicker" ).datepicker({ firstDay: 0 });
        jQuery(".datepicker").datepicker('setDate', new Date());
	}

});

function go_toggle( source ) {
	checkboxes = jQuery( '.go_checkbox' );
	for (var i = 0, n = checkboxes.length; i < n ;i++) {
		checkboxes[ i ].checked = source.checked;
	}
}

function go_clipboard_change_filter() {
    var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
    console.log(current_tab);
    if (current_tab == "clipboard_wrap"){
        console.log("1");
        //Clipboard.draw();
        //jQuery("#clipboard_stats_datatable_container").html("");
        jQuery("#clipboard_store_datatable_container").html("");
        jQuery("#clipboard_messages_datatable_container").html("");
        //jQuery("#clipboard_activity_datatable_container").html("");
    }
    else if (current_tab == "clipboard_store_wrap") {
        console.log("2");
        //Store.draw();
        //jQuery("#clipboard_stats_datatable_container").html("");
        //jQuery("#clipboard_store_datatable_container").html("");
        jQuery("#clipboard_messages_datatable_container").html("");
        //jQuery("#clipboard_activity_datatable_container").html("");
    }
    else if (current_tab == "clipboard_messages_wrap") {
        console.log("3");
        //Messages.draw();
        //jQuery("#clipboard_stats_datatable_container").html("");
        jQuery("#clipboard_store_datatable_container").html("");
        //jQuery("#clipboard_messages_datatable_container").html("");
        //jQuery("#clipboard_activity_datatable_container").html("");
    }
    else if (current_tab == "clipboard_activity_wrap") {
        console.log("4");
        //Activity.draw();
        //jQuery("#clipboard_stats_datatable_container").html("");
        jQuery("#clipboard_store_datatable_container").html("");
        jQuery("#clipboard_messages_datatable_container").html("");
        //jQuery("#clipboard_activity_datatable_container").html("");
    }

    //ajax to save the values
    var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_save_filters;
    var section = jQuery( '#go_clipboard_user_go_sections_select' ).val();
    var group = jQuery( '#go_clipboard_user_go_groups_select' ).val();
    var badge = jQuery( '#go_clipboard_go_badges_select' ).val();
    //alert (section);
    //console.log(jQuery( '#go_clipboard_user_go_sections_select' ).val());
    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_clipboard_save_filters',
            section: section,
            badge: badge,
            group: group

        },
        success: function( res ) {
            //console.log("values saved");
        }
    });

}

function go_filter_clipboard_datatables(mytable) { //function that filters all tables on draw
    jQuery.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var mytable = settings.sTableId;
            //console.log("mytable" + mytable);
            //if (mytable == "go_clipboard_stats_datatable" || mytable == "go_clipboard_messages_datatable" || mytable == "go_clipboard_activity_datatable") {
                var section = jQuery('#go_clipboard_user_go_sections_select').val();
                var group = jQuery('#go_clipboard_user_go_groups_select').val();
                var badge = jQuery('#go_clipboard_go_badges_select').val();
                var badges =  data[4] ;
                var groups =  data[3] ; // use data for the filter by column
                var sections = data[2]; // use data for the filter by column
                //console.log("data" + data);
                //console.log("badges" + badges);
                //console.log("groups" + groups);
                //console.log("sections" + sections);
                //console.log(sections);


                groups = JSON.parse(groups);

                //console.log("groups" + groups);
                //sections = JSON.parse(sections);
                badges = JSON.parse(badges);
                //console.log("badges" + badges);

                //console.log("sections" + sections);

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
            //}
            //else{
             //   return true;
           // }
        });
}

function go_toggle_off() {
    checkboxes = jQuery( '.go_checkbox' );
    for (var i = 0, n = checkboxes.length; i < n ;i++) {
        checkboxes[ i ].checked = false;
    }
}

function go_clipboard_stats_datatable(refresh) {
    //hide date filter
    jQuery('#go_timestamp_filters').hide();
	//var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
	if (jQuery("#go_clipboard_stats_datatable").length == 0  || refresh == true) {
        jQuery("#clipboard_stats_datatable_container").html("<h2>Loading . . .</h2>");
        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_stats;
        //console.log("refresh" + refresh);
        //console.log("stats");
        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_stats',
                date: jQuery( '.datepicker' ).val(),
                refresh: refresh
            },
            success: function( res ) {
                //console.log("success");
                if (-1 !== res) {
                    jQuery('#clipboard_stats_datatable_container').html(res);

                    var Clipboard = jQuery('#go_clipboard_stats_datatable').DataTable({
                        deferRender: true,
                        "bPaginate": true,
                        //colReorder: true,
                        "order": [[5, "asc"]],
                        responsive: true,
                        "autoWidth": false,
                        //stateSave: true,
                        "stateDuration": 31557600,
                        //"destroy": true,
                        dom: 'lBfrtip',
                        "drawCallback": function( settings ) {
                            jQuery('.go_messages_icon').prop('onclick',null).off('click');
                            jQuery(".go_messages_icon").one("click", function(e){
                                go_messages_opener();
                            });
                            go_stats_links();
                        },

                        "columnDefs": [
                            { type: 'natural', targets: '_all'  },
                            {
                                "targets": [0],
                                className: 'noVis',
                                "width": "1px",
                                sortable: false
                            },
                            {
                                "targets": [1],
                                className: 'noVis',
                                "width": "20px",
                                sortable: false
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
                                "targets": [4],
                                "visible": false,
                                className: 'noVis'
                            },
                            {
                                "targets": [7],
                                className: 'noVis'
                            },
                            {
                                "targets": [8],
                                className: 'noVis'
                            },
                            {
                                "targets": [10],
                                className: 'noVis',
                                sortable: false
                            }
                        ],
                        buttons: [
                            {
                                text: '<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',
                                action: function ( e, dt, node, config ) {

                                }

                            },
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
                                postfixButtons: ['colvisRestore'],
                                text: 'Column Visibility'
                            }


                        ]
                    });

                    //search on enter only
                    jQuery("div.dataTables_filter input").unbind();
                    jQuery("div.dataTables_filter input").keyup( function (e) {
                        if (e.keyCode == 13) {
                            Clipboard.search( this.value ).draw();
                        }
                    });

                    jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select').change(function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_wrap"){
                            Clipboard.draw();
                        }
                        go_clipboard_change_filter();
                    });

                    jQuery('.go_update_clipboard').one("click", function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_wrap"){
                            go_clipboard_stats_datatable(true);
                        }
                        go_clipboard_change_filter();
                    });

                    //force window resize on load to initialize responsive behavior
                    jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust()
                        .responsive.recalc();

                    //the filter for client side
                    go_filter_clipboard_datatables("go_clipboard_stats_datatable");
                    Clipboard.draw();
                }
            }
        });
    }
}

function go_clipboard_store_datatable(refresh) {
    //show date filter
    jQuery('#go_timestamp_filters').show();

    if ( jQuery( "#go_clipboard_store_datatable" ).length == 0  || refresh == true) {
        jQuery("#clipboard_store_datatable_container").html("<h2>Loading . . .</h2>");
        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_store;
        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_store'
                //go_clipboard_messages_datatable: jQuery( '#go_clipboard_store_datatable' ).val()
            },
            success: function( res ) {
                //console.log("success");
                if (-1 !== res) {
                    jQuery('#clipboard_store_datatable_container').html(res);
                    //go_filter_datatables();
                    var Store = jQuery('#go_clipboard_store_datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": MyAjax.ajaxurl + '?action=go_clipboard_store_dataloader_ajax',
                            "data": function(d){
                                //d.user_id = jQuery('#go_stats_hidden_input').val();
                                d.date = jQuery( '.datepicker' ).val();
                                d.section = jQuery('#go_clipboard_user_go_sections_select').val();
                                d.group = jQuery('#go_clipboard_user_go_groups_select').val();
                                d.badge = jQuery('#go_clipboard_go_badges_select').val();
                            }
                        },
                        "bPaginate": true,
                        //colReorder: true,
                        "order": [[8, "desc"]],
                        responsive: true,
                        "autoWidth": false,
                        //stateSave: true,
                        //"stateDuration": 31557600,
                        searchDelay: 1000,
                        dom: 'lBfrtip',
                        "drawCallback": function( settings ) {
                            jQuery('.go_messages_icon').prop('onclick',null).off('click');
                            jQuery(".go_messages_icon").one("click", function(e){
                                go_messages_opener();
                            });
                            go_stats_links();
                        },
                        "columnDefs": [
                            { type: 'natural', targets: '_all', sortable: false  },
                            {
                                "targets": [0],
                                className: 'noVis',
                                "width": "5px",
                                sortable: false
                            },
                            {
                                "targets": [1],
                                className: 'noVis',
                                "width": "20px",
                                sortable: false
                            }
                        ],
                        buttons: [
                            {
                                text: '<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',
                                action: function ( e, dt, node, config ) {
                                }
                            },
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
                                postfixButtons: ['colvisRestore'],
                                text: 'Column Visibility'
                            }
                        ]
                    });

                    jQuery("div.dataTables_filter input").unbind();
                    jQuery("div.dataTables_filter input").keyup( function (e) {
                        if (e.keyCode == 13) {
                            Store.search( this.value ).draw();
                        }
                    });


                    jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, .datepicker').change(function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_store_wrap"){
                            Store.draw();
                        }
                        go_clipboard_change_filter();
                    });

                    jQuery('.go_update_clipboard').one("click", function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_store_wrap"){
                            go_clipboard_store_datatable(true);
                        }
                        go_clipboard_change_filter();
                    });
                }
            }
        });
    }
}

function go_clipboard_messages_datatable(refresh) {
    //show date filter
    jQuery('#go_timestamp_filters').show();

    if ( jQuery( "#go_clipboard_messages_datatable" ).length == 0  || refresh == true) {
        jQuery("#clipboard_messages_datatable_container").html("<h2>Loading . . .</h2>");

        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_messages;

        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_messages'
                //go_clipboard_messages_datatable: jQuery( '#go_clipboard_messages_datatable' ).val()
            },
            success: function( res ) {
                //console.log("success");
                if (-1 !== res) {
                    jQuery('#clipboard_messages_datatable_container').html(res);
                    //go_filter_datatables();
                    var Messages = jQuery('#go_clipboard_messages_datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": MyAjax.ajaxurl + '?action=go_clipboard_messages_dataloader_ajax',
                            "data": function(d){
                                //d.user_id = jQuery('#go_stats_hidden_input').val();
                                d.date = jQuery( '.datepicker' ).val();
                                d.section = jQuery('#go_clipboard_user_go_sections_select').val();
                                d.group = jQuery('#go_clipboard_user_go_groups_select').val();
                                d.badge = jQuery('#go_clipboard_go_badges_select').val();
                            }
                        },
                        "bPaginate": true,
                        //colReorder: true,
                        "order": [[8, "desc"]],
                        responsive: true,
                        "autoWidth": false,
                        searchDelay: 1000,
                        //stateSave: true,
                        //"stateDuration": 31557600,
                        dom: 'lBfrtip',
                        "drawCallback": function( settings ) {
                            jQuery('.go_messages_icon').prop('onclick',null).off('click');
                            jQuery(".go_messages_icon").one("click", function(e){
                                go_messages_opener();
                            });
                            go_stats_links();
                        },
                        "columnDefs": [
                            { type: 'natural', targets: '_all', sortable: false  },
                            {
                                "targets": [0],
                                className: 'noVis',
                                "width": "5px",
                                sortable: false
                            },
                            {
                                "targets": [1],
                                className: 'noVis',
                                "width": "20px",
                                sortable: false
                            }

                        ],
                        buttons: [
                            {
                                text: '<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',
                                action: function ( e, dt, node, config ) {
                                }
                            },
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
                                postfixButtons: ['colvisRestore'],
                                text: 'Column Visibility'
                            }
                        ]
                    });

                    //search only on enter key
                    jQuery("div.dataTables_filter input").unbind();
                    jQuery("div.dataTables_filter input").keyup( function (e) {
                        if (e.keyCode == 13) {
                            Messages.search( this.value ).draw();
                        }
                    });


                    jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, .datepicker').change(function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_messages_wrap"){
                            Messages.draw();
                        }
                        go_clipboard_change_filter();
                    });

                    jQuery('.go_update_clipboard').one("click", function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_messages_wrap"){
                            console.log("draw");
                            go_clipboard_messages_datatable(true);
                        }
                        go_clipboard_change_filter();
                    });
                }
            }
        });
    }
}

function go_clipboard_activity_datatable(refresh) {
    //show date filter
    jQuery('#go_timestamp_filters').show();

    if ( jQuery( "#go_clipboard_activity_datatable" ).length == 0  || refresh == true) {
        jQuery("#clipboard_activity_datatable_container").html("<h2>Loading . . .</h2>");

        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_activity;

        //console.log(date);
        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_activity',
                date: jQuery( '.datepicker' ).val()
            },
            success: function( res ) {
                //console.log("success");
                if (-1 !== res) {
                    jQuery('#clipboard_activity_datatable_container').html(res);
                    //go_filter_datatables();
                    var Activity = jQuery('#go_clipboard_activity_datatable').DataTable({
                        deferRender: true,
                        "bPaginate": true,
                        //colReorder: true,
                        "order": [[4, "asc"]],
                        responsive: true,
                        "autoWidth": false,
                        stateSave: true,
                        "stateDuration": 31557600,
                        dom: 'lBfrtip',
                        "drawCallback": function( settings ) {
                            jQuery('.go_messages_icon').prop('onclick',null).off('click');
                            jQuery(".go_messages_icon").one("click", function(e){
                                go_messages_opener();
                            });
                            go_stats_links();
                        },
                        "columnDefs": [
                            { type: 'natural', targets: '_all'  },
                            {
                                "targets": [0],
                                className: 'noVis',
                                "width": "5px",
                                sortable: false
                            },
                            {
                                "targets": [1],
                                className: 'noVis',
                                "width": "20px",
                                sortable: false
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
                                "targets": [4],
                                "visible": false,
                                className: 'noVis'
                            },
                            {
                                "targets": [7],
                                className: 'noVis'
                            },
                            {
                                "targets": [8],
                                className: 'noVis'
                            },
                            {
                                "targets": [10],
                                className: 'noVis',
                                sortable: false
                            }
                        ],
                        buttons: [
                            {
                                text: '<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',
                                action: function ( e, dt, node, config ) {

                                }

                            },
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
                                postfixButtons: ['colvisRestore'],
                                text: 'Column Visibility'
                            }

                        ]

                    });

                    // Add event listener for opening and closing more actions
                    jQuery('#go_clipboard_activity_datatable .show_more').click( function () {
                        var hidden = jQuery(this).hasClass('shown');
                        //console.log(hidden);
                        if (hidden == false) {
                            jQuery(this).addClass('shown');
                            jQuery(this).siblings('.hidden_action').show();
                            jQuery(this).find('.hide_more_actions').show();
                            jQuery(this).find('.show_more_actions').hide();
                            //console.log("show");
                        }else{
                            jQuery(this).removeClass('shown');
                            jQuery(this).siblings('.hidden_action').hide();
                            jQuery(this).find('.hide_more_actions').hide();
                            jQuery(this).find('.show_more_actions').show();
                            //console.log("hide");
                        }

                    } );

                    //search on enter only
                    jQuery("div.dataTables_filter input").unbind();
                    jQuery("div.dataTables_filter input").keyup( function (e) {
                        if (e.keyCode == 13) {
                            Activity.search( this.value ).draw();
                        }
                    });


                    jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select').change(function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_activity_wrap"){
                            Activity.draw();
                        }
                        go_clipboard_change_filter();
                    });

                    jQuery('.go_update_clipboard').one("click", function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_activity_wrap"){
                            console.log("11");
                            go_clipboard_activity_datatable(true);
                        }
                        go_clipboard_change_filter();
                    });

                    jQuery('.datepicker').change(function () {
                        var current_tab = jQuery("#records_tabs").find("[aria-selected='true']").attr('aria-controls');
                        if (current_tab == "clipboard_activity_wrap"){
                            console.log("22");
                            go_clipboard_activity_datatable(true);
                        }
                        go_clipboard_change_filter();
                    });

                }
            }
        });
    }
}
