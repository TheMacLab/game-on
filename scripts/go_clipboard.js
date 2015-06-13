jQuery( document ).ready( function() {
	jQuery( '#records_tabs' ).tabs();
  	jQuery( '#go_clipboard_table' ).dataTable({
		"bPaginate": false
	});
});

function go_toggle( source ) {
	checkboxes = jQuery( '.go_checkbox' );
	for (var i = 0, n = checkboxes.length; i < n ;i++) {
		checkboxes[ i ].checked = source.checked;
	}
}

function go_clipboard_class_a_choice() {
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: { 
			action: 'go_clipboard_intable',
			go_clipboard_class_a_choice: jQuery( '#go_clipboard_class_a_choice' ).val()
		},
		success: function( html ) {
			jQuery( '#go_clipboard_table_body' ).html( '' );
			var oTable = jQuery( '#go_clipboard_table' ).dataTable();
			oTable.fnDestroy();
			jQuery( '#go_clipboard_table_body' ).html( html );
			jQuery( '#go_clipboard_table' ).dataTable( {
				"bPaginate": false,
				"aaSorting": [[2, "asc"]]
			});
		}
	});
}

function go_clipboard_class_a_choice_messages() {
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: { 
			action: 'go_clipboard_intable_messages',
			go_clipboard_class_a_choice_messages: jQuery( '#go_clipboard_class_a_choice_messages' ).val()
		},
		success: function( html ) {
			jQuery( '#go_clipboard_messages_body' ).html( '' );
			var oTable = jQuery( '#go_clipboard_messages' ).dataTable();
			oTable.fnDestroy();
			jQuery( '#go_clipboard_messages_body' ).html( html );
			jQuery( '#go_clipboard_messages' ).dataTable( {
				"bPaginate": false,
				"aaSorting": [[1, "asc"]],
				"destroy": true
			});
		}
	});
}

function go_user_focus_change( user_id, element ) {
	jQuery.ajax({
		type: "POST",
		url: MyAjax.ajaxurl,
		data: {
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

function go_clipboard_add( id ) {
	var values = [];
	jQuery( '#go_send_message' ).prop( 'disabled', 'disabled' );
	jQuery( "input:checkbox[name=go_selected]:checked" ).each( function() {
		values.push( jQuery( this ).val() );
	});

	if ( values.length > 0 ) {
		add_points = parseFloat( check_null( jQuery( '#go_clipboard_points' ).val() ) );
		add_currency = parseFloat( check_null( jQuery( '#go_clipboard_currency' ).val() ) );
		add_bonus_currency = parseFloat( check_null( jQuery( '#go_clipboard_bonus_currency' ).val() ) );
		add_penalty = parseFloat( check_null( jQuery( '#go_clipboard_penalty' ).val() ) );
		add_minutes = parseFloat( check_null( jQuery( '#go_clipboard_minutes' ).val() ) );
		
		if ( jQuery( '#go_clipboard_reason' ).val() != '' ) {
			reason = jQuery( '#go_clipboard_reason' ).val();	
		} else {
			reason = jQuery( '#go_clipboard_reason' ).attr( 'placeholder' );	
		}
		// console.log(reason);
		jQuery.ajax({
			type: "post",
			url: MyAjax.ajaxurl,
			data: { 
				action: 'go_clipboard_add',
				ids: values,
				points: add_points,
				currency: add_currency,
				bonus_currency: add_bonus_currency,
				penalty: add_penalty,
				reason: reason,
				minutes: add_minutes,
				badge_ID: jQuery( '#go_clipboard_badge' ).val()
			},
			success: function( html ) {
				if ( jQuery( '#go_clipboard_reason' ).val() != '' ) {
					if ( jQuery( '#go_clipboard_badge' ).val() != '' ) {
						badge_count = 1;
					} else {
						badge_count = 0;	
					}
					for ( id in values ) {
						var user_currency = parseFloat( jQuery( '#user_' + values[ id ] + ' .user_currency' ).html() );
						var user_bonus_currency = parseFloat( jQuery( '#user_' +values[ id ]+ ' .user_bonus_currency' ).html() );
						var user_penalty = parseFloat( jQuery( '#user_' +values[ id ]+ ' .user_penalty' ).html() );
						var user_points = parseFloat( jQuery( '#user_' +values[ id ]+ ' .user_points' ).html() );
						var user_badge_count = parseFloat( jQuery( '#user_' +values[ id ]+ ' .user_badge_count' ).html() );
						var user_minutes = parseFloat( jQuery( '#user_' +values[ id ]+ ' .user_minutes' ).html() );
			
						jQuery( '#user_' +values[ id ]+ ' .user_currency' ).html(user_currency + add_currency);
						jQuery( '#user_' +values[ id ]+ ' .user_bonus_currency' ).html(user_bonus_currency + add_bonus_currency);
						jQuery( '#user_' +values[ id ]+ ' .user_penalty' ).html(user_penalty + add_penalty);
						jQuery( '#user_' +values[ id ]+ ' .user_points' ).html(user_points + add_points);
						jQuery( '#user_' +values[ id ]+ ' .user_badge_count' ).html(user_badge_count + badge_count);
						jQuery( '#user_' +values[ id ]+ ' .user_minutes' ).html(user_minutes + add_minutes);
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

function fixmessages() {
	jQuery.ajax({
		type: "POST",
		url: MyAjax.ajaxurl,
		data: {
			action: 'fixmessages'
		},
		success: function() {
			alert( 'Messages fixed' );
		}
	});
}