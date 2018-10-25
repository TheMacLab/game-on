jQuery( document ).ready( function() {
    	if ( jQuery( '#records_tabs' ).length ) {
		jQuery('#records_tabs').tabs();
        jQuery( '.clipboard_tabs' ).click( function() {
        	//console.log("tabs");
            tab = jQuery(this).attr('tab');
            switch (tab) {

                /*
                case 'store':
                    //console.log("store");
                    go_clipboard_class_a_choice_messages();
                    break;
                */
                case 'activity':
                    //console.log("activity");
                    go_clipboard_class_a_choice_activity();
                    jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust()
                        .responsive.recalc();
                    break;
                case 'clipboard':
                    //console.log("activity");
                    //force window resize on load to initialize responsive behavior
                    jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust()
                        .responsive.recalc();
                    break;
            }
        });

	}

	if ( jQuery( "#go_clipboard_stats_datatable" ).length ) {
		go_clipboard_class_a_choice();

        jQuery( ".datepicker" ).datepicker({ firstDay: 0 });
        jQuery(".datepicker").datepicker('setDate', new Date());
        jQuery('.datepicker').change(function () {
            //console.log("change");
            jQuery('#go_clipboard_activity_datatable').html("<div id='loader' style='font-size: 1.5em; text-align: center; height: 200px'>loading . . .</div>");
            go_clipboard_class_a_choice_activity(true);

        });
        jQuery('.go_datepicker_refresh').click(function () {
            jQuery('#go_clipboard_activity_datatable').html("<div id='loader' style='font-size: 1.5em; text-align: center; height: 200px'>loading . . .</div>");
            go_clipboard_class_a_choice_activity(true);

        });

	}
    //console.log('ready');
   // jQuery('.go_tax_select').select2();
});

function go_toggle( source ) {
	checkboxes = jQuery( '.go_checkbox' );
	for (var i = 0, n = checkboxes.length; i < n ;i++) {
		checkboxes[ i ].checked = source.checked;
	}
}

function go_toggle_off() {
    checkboxes = jQuery( '.go_checkbox' );
    for (var i = 0, n = checkboxes.length; i < n ;i++) {
        checkboxes[ i ].checked = false;
    }
}

function go_clipboard_class_a_choice() {
	//var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
    go_filter_datatables();

	if (jQuery("#go_clipboard_stats_datatable").length) {

		//XP////////////////////////////
		//go_sort_leaders("go_clipboard", 4);
		var Clipboard = jQuery('#go_clipboard_stats_datatable').DataTable({
            //stateSave: false,
            "bPaginate": false,
            //colReorder: true,
            "order": [[5, "asc"]],
            responsive: true,
            "autoWidth": false,
            stateSave: true,
            //"destroy": true,
            dom: 'Bfrtip',
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


		//on change filter listener
		//console.log("change5");
		jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select').change( function() {
			//console.log("change");
			Clipboard.draw();
			//ajax function to save the values
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

		});
		jQuery("#records_tabs").css("margin-left", '');


	}
    //force window resize on load to initialize responsive behavior
        jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust()
        .responsive.recalc();

}

function go_clipboard_class_a_choice_activity(refresh) {
    if ( jQuery( "#go_clipboard_activity_datatable" ).length == 0  || refresh == true) {
        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_activity;
        var date = jQuery( '.datepicker' ).val();
        //console.log(date);
        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_intable_activity',
                go_clipboard_class_a_choice_activity: jQuery( '#go_clipboard_class_a_choice_activity' ).val(),
                date: jQuery( '.datepicker' ).val()
            },
            success: function( res ) {
                //console.log("success");
                if (-1 !== res) {
                    jQuery('#clipboard_activity_datatable_container').html(res);
                    //go_filter_datatables();
                    var Messages = jQuery('#go_clipboard_activity_datatable').DataTable({
                        //stateSave: false,
                        "bPaginate": false,
                        //colReorder: true,
                        "order": [[4, "asc"]],
                        responsive: true,
                        "autoWidth": false,
                        stateSave: true,
                        //"destroy": true,
                        dom: 'Bfrtip',
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
                    //show date filter
                    jQuery('#go_timestamp_filters').show();

                    //on change filter listener
                    //console.log("change5");
                    jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select').change(function () {
                        //console.log("change");
                        Messages.draw();

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
                }



            }
        });
    }
}

