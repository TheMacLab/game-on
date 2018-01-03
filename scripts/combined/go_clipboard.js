jQuery( document ).ready( function() {
	jQuery( '#records_tabs' ).tabs();
  	if ( jQuery( "#go_clipboard_table" ).length ) {
    	jQuery( '#go_clipboard_table' ).dataTable({
			"bPaginate": false
		});
 	}

});

function go_toggle( source ) {
	checkboxes = jQuery( '.go_checkbox' );
	for (var i = 0, n = checkboxes.length; i < n ;i++) {
		checkboxes[ i ].checked = source.checked;
	}
}

function go_clipboard_class_a_choice() {
	var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
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
				jQuery( '#go_clipboard_table_body' ).html( '' );
				var oTable = jQuery( '#go_clipboard_table' ).dataTable();
				oTable.fnDestroy();
				jQuery( '#go_clipboard_table_body' ).html( res );
				jQuery( '#go_clipboard_table' ).dataTable( {
					"bPaginate": false,
					"aaSorting": [ 
						[ 2, "asc" ]
					]
				});	
			}
		}
	});
}

function go_clipboard_class_a_choice_messages() {
	var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_messages;
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce,
			action: 'go_clipboard_intable_messages',
			go_clipboard_class_a_choice_messages: jQuery( '#go_clipboard_class_a_choice_messages' ).val()
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_clipboard_messages_body' ).html( '' );
				var oTable = jQuery( '#go_clipboard_messages' ).dataTable();
				oTable.fnDestroy();
				jQuery( '#go_clipboard_messages_body' ).html( res );
				jQuery( '#go_clipboard_messages' ).dataTable( {
					"bPaginate": false,
					"aaSorting": [[1, "asc"]],
					"destroy": true
				});
			}
		}
	});
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