jQuery( document ).ready( function() {
	if ( jQuery( '#records_tabs' ).length ) {
		jQuery('#records_tabs').tabs();
        jQuery( '.clipboard_tabs' ).click( function() {
        	//console.log("tabs");
            tab = jQuery(this).attr('tab');
            switch (tab) {
                case 'messages':
                    //console.log("messages");
                    go_clipboard_class_a_choice_messages();
                    break;
                case 'activity':
                    //console.log("activity");
                    go_clipboard_class_a_choice_activity();
                    break;
            }
        });
	}

	if ( jQuery( "#go_clipboard_datatable" ).length ) {
		go_clipboard_class_a_choice();
	}
});

function go_toggle( source ) {
	checkboxes = jQuery( '.go_checkbox' );
	for (var i = 0, n = checkboxes.length; i < n ;i++) {
		checkboxes[ i ].checked = source.checked;
	}
}

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

function go_clipboard_class_a_choice() {
	//var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
    go_filter_datatables();

	if (jQuery("#go_clipboard_datatable").length) {

		//XP////////////////////////////
		//go_sort_leaders("go_clipboard", 4);
		var Clipboard = jQuery('#go_clipboard_datatable').DataTable({
            stateSave: true,
            "bPaginate": false,
            //colReorder: true,
            "order": [[4, "asc"]],
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
                'copy', 'excel', 'pdf',
                {
                    extend: 'colvis',
                    columns: ':not(.noVis)',
                    postfixButtons: [ 'colvisRestore' ]
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
                    console.log("values saved");
                }
            });

		});
	}
}

function go_clipboard_class_a_choice_activity() {
    if ( jQuery( "#go_clipboard_activity_datatable" ).length == 0 ) {
        var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_activity;
        jQuery.ajax({
            type: "post",
            url: MyAjax.ajaxurl,
            data: {
                _ajax_nonce: nonce,
                action: 'go_clipboard_intable_activity',
                go_clipboard_class_a_choice_activity: jQuery( '#go_clipboard_class_a_choice_activity' ).val()
            },
            success: function( res ) {
                if (-1 !== res) {
                    jQuery('#clipboard_activity_wrap').html(res);
                    go_filter_datatables();
                    var Messages = jQuery('#go_clipboard_activity_datatable').DataTable({
                        stateSave: true,
                        "bPaginate": false,
                        //colReorder: true,
                        "order": [[4, "asc"]],
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
                            'copy', 'excel', 'pdf',
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
                            'copy', 'excel', 'pdf',
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

function go_clipboard_clear_fields() {
	jQuery( '#go_clipboard_points' ).val( '' );
	jQuery( '#go_clipboard_currency' ).val( '' );
	jQuery( '#go_clipboard_bonus_currency' ).val( '' );
	jQuery( '#go_clipboard_minutes' ).val( '' );
	jQuery( '#go_clipboard_penalty' ).val( '' );
	jQuery( '#go_clipboard_reason' ).val( '' );
	jQuery( '#go_clipboard_badge' ).val( '' );
}

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