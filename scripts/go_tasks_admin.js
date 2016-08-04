/*
 * go_tasks_admin.js
 *
 * Where all the functionality for the task edit page goes.
 */

/**
 * Setting Row On Toggle Handlers
 *
 * "go_toggle" callbacks are called when an accordion has been toggled. See `go_toggle_accordion()`
 * for context.
 *
 * Function name convention: "go_[cmb_id]_on_toggle"
 */

// a handler specifically for when the start filter setting row is opened
function go_start_filter_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );
	var start_filter_enabled = false;
	if ( 1 === jQuery( row_class + ' #go_start_checkbox' ).length &&
			jQuery( row_class + ' #go_start_checkbox' ).is( ':checked' ) ) {
		start_filter_enabled = true;
	}

	var filter_fields = jQuery( row_class + ' #go_start_info' );

	if ( ! visible ) {
		jQuery( row_class ).show();
		if ( ! start_filter_enabled ) {
			jQuery( filter_fields ).hide();
		} else {
			jQuery( filter_fields ).show();
		}
	} else if ( visible ) {
		jQuery( row_class ).hide();
	}
}

// a handler specifically for when the date picker setting row is opened
function go_date_picker_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );
	var date_checked = false;
	if ( 1 === jQuery( '#go_calendar_checkbox' ).length &&
			jQuery( '#go_calendar_checkbox' ).is( ':checked' ) ) {
		date_checked = true;
	}

	if ( date_checked && ! visible ) {
		jQuery( row_class ).show();
	} else if ( visible ) {
		jQuery( row_class ).hide();
	}
}

// a handler specifically for when the time modifier setting row is opened
function go_time_modifier_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );
	var time_checked = false;
	if ( 1 === jQuery( '#go_future_checkbox' ).length &&
			jQuery( '#go_future_checkbox' ).is( ':checked' ) ) {
		time_checked = true;
	}

	if ( time_checked && ! visible ) {
		jQuery( row_class ).show();
	} else if ( visible ) {
		jQuery( row_class ).hide();
	}
}

// a handler specifically for when the chain order setting setting row is opened
function go_chain_order_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );
	var in_chain = GO_TASK_DATA.task_chains.in_chain;

	if ( in_chain && ! visible ) {
		jQuery( row_class ).show();
	} else if ( visible ) {
		jQuery( row_class ).hide();
	}
}

// a handler specifically for when the final chain message setting row is opened
function go_final_chain_message_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );
	var in_chain = GO_TASK_DATA.task_chains.in_chain;
	var is_final_task = GO_TASK_DATA.task_chains.is_last_in_chain;

	if ( in_chain && is_final_task && ! visible ) {
		jQuery( row_class ).show();
	} else if ( visible ) {
		jQuery( row_class ).hide();
	}
}

// a handler specifically for when the admin lock setting row is opened in any of the stage accordions
function go_admin_lock_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );
	var is_checked = false;
	if ( 1 === jQuery( row_class + ' .go_admin_lock_checkbox' ).length &&
			jQuery( row_class + ' .go_admin_lock_checkbox' ).is( ':checked' ) ) {
		is_checked = true;
	}

	var password_input = jQuery( row_class + ' .go_admin_lock_text' );

	if ( ! visible ) {
		jQuery( row_class ).show();
		if ( ! is_checked ) {
			jQuery( password_input ).hide();
		} else {
			jQuery( password_input ).show();
		}
	} else {
		jQuery( row_class ).hide();
	}
}

function go_test_loot_checkbox_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );

	// determines whether or not the primary test checkbox is checked
	var test_checkbox = jQuery( row_class ).prev( 'tr' ).find( 'input[type="checkbox"]' );

	var is_checked = false;
	if ( 1 === test_checkbox.length && test_checkbox.is( ':checked' ) ) {
		is_checked = true;
	}

	if ( ! visible && is_checked ) {
		jQuery( row_class ).show();
	} else if ( visible && ! is_checked ) {
		jQuery( row_class ).hide();
	}
}

function go_test_loot_mod_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );

	var loot_checkbox_row = jQuery( row_class ).prev( 'tr' );
	var loot_checkbox = loot_checkbox_row.find( 'input[type="checkbox"]' );

	var test_checkbox = loot_checkbox_row.prev( 'tr' ).find( 'input[type="checkbox"]' );

	var is_loot_checked = false;
	if ( 1 === loot_checkbox.length && loot_checkbox.is( ':checked' ) ) {
		is_loot_checked = true;
	}

	var is_primary_checked = false;
	if ( 1 === test_checkbox.length && test_checkbox.is( ':checked' ) ) {
		is_primary_checked = true;
	}

	if ( ! visible && is_loot_checked && is_primary_checked ) {
		jQuery( row_class ).show();
	} else if ( visible && ( ! is_loot_checked || is_primary_checked ) ) {
		jQuery( row_class ).hide();
	}
}

function go_test_field_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );

	// determines whether or not the primary test checkbox is checked
	var test_checkbox = jQuery( row_class ).go_prev_n( 3, 'tr' ).find( 'input[type="checkbox"]' );

	var is_checked = false;
	if ( 1 === test_checkbox.length && test_checkbox.is( ':checked' ) ) {
		is_checked = true;
	}

	if ( ! visible && is_checked ) {
		jQuery( row_class ).show();
	} else if ( visible && ! is_checked ) {
		jQuery( row_class ).hide();
	}
}

function go_badge_input_on_toggle( row_class ) {
	var visible = jQuery( row_class ).is( ':visible' );

	var badge_checkbox = jQuery( row_class + ' .go_badge_input_toggle' );
	var badge_list     = jQuery( row_class + ' .go_stage_badge_container' );

	var is_checked = false;
	if ( 1 === badge_checkbox.length && badge_checkbox.is( ':checked' ) ) {
		is_checked = true;
	}

	if ( ! visible ) {
		jQuery( row_class ).show();
		if ( ! is_checked ) {
			jQuery( badge_list ).hide();
		} else {
			jQuery( badge_list ).show();
		}
	} else {
		jQuery( row_class ).hide();
	}
}

/**
 * Accordion On Load Handlers
 *
 * "on_load" callbacks are called when the page is first loaded. See `go_accordion_array_on_load()`
 * for context. These callbacks are intended to add and respond to event handlers for individual
 * accordion rows.
 *
 * Function name convention: "go_[cmb_id]_on_load"
 */

// a handler specifically for when the fifth stage accordion is first loaded
function go_stage_five_on_load( row_class ) {
	if ( 1 !== jQuery( '#go_mta_five_stage_switch' ).length ) {
		return;
	}

	// displays the stage five accordion when either of the stage five options are checked
	if ( jQuery( '#go_mta_five_stage_switch' ).is( ':checked' ) ) {

	}
}

/**
 * Setting Row On Load Handlers
 *
 * "on_load" callbacks are called when the page is first loaded. See `go_accordion_array_on_load()`
 * for context. These callbacks are intended to add and respond to event handlers for individual
 * setting rows.
 *
 * Function name convention: "go_[cmb_id]_on_load"
 */

// a custom event handler just for the start filter setting row
function go_start_filter_on_load( row ) {
	jQuery( row.class ).hide();

	if ( 1 === jQuery( row.class + ' #go_start_checkbox' ).length ) {
		jQuery( row.class + ' #go_start_checkbox' ).change( go_start_filter_checkbox_on_change );
	}
}

function go_time_filters_on_load( row ) {
	jQuery( row.class ).hide();

	var time_filter_checkboxes = jQuery( row.class + ' #go_calendar_checkbox, ' + row.class + ' #go_future_checkbox' );

	if ( 2 === time_filter_checkboxes.length ) {
		time_filter_checkboxes.change( go_time_filter_checkboxes_on_change );
	}
}

function go_date_picker_on_load( row ) {
	jQuery( row.class ).hide();

	var date_checkbox = jQuery( '#go_calendar_checkbox' );
	var add_field_button = jQuery( row.class + ' #go_mta_add_task_decay' );
	var del_field_button = jQuery( row.class + ' #go_mta_remove_task_decay' );

	if ( 1 === date_checkbox.length ) {

		// go_date_picker_on_toggle is wrapped in a closure, otherwise the jQuery.Event sent to the
		// "change" listener will be confused for the class of the row
		date_checkbox.change( function() {
			go_date_picker_on_toggle( row.class );
		});
	}

	if ( 1 === add_field_button.length ) {
		add_field_button.click( go_date_picker_add_field );
	}
	
	if ( 1 === del_field_button.length ) {
		del_field_button.click( go_date_picker_del_field );
	}
}

function go_time_modifier_on_load( row ) {
	jQuery( row.class ).hide();

	var time_checkbox = jQuery( '#go_future_checkbox' );

	if ( 1 === time_checkbox.length ) {

		// go_date_picker_on_toggle is wrapped in a closure, otherwise the jQuery.Event sent to the
		// "change" listener will be confused for the class of the row
		time_checkbox.change( function() {
			go_time_modifier_on_toggle( row.class );
		});
	}
}

function go_chain_order_on_load( row ) {
	jQuery( row.class ).hide();

	var in_chain = GO_TASK_DATA.task_chains.in_chain;

	if ( in_chain ) {
		go_prepare_sortable_list();
	}
}

function go_stage_reward_on_load( row ) {
	jQuery( row.class ).hide();

	var reward_fields = jQuery( row.class + ' .go_reward_input' );

	if ( reward_fields.length > 0 ) {
		jQuery( reward_fields ).keyup( go_stage_reward_on_keyup );
	}
}

function go_admin_lock_on_load( row ) {
	jQuery( row.class ).hide();

	var lock_checkbox = jQuery( row.class + ' .go_admin_lock_checkbox' );

	if ( 1 === lock_checkbox.length ) {
		lock_checkbox.change( go_admin_lock_checkbox_on_change );
	}
}

function go_test_checkbox_on_load( row ) {
	jQuery( row.class ).hide();

	var test_checkbox = jQuery( row.class + ' input[type="checkbox"]' );

	if ( 1 === test_checkbox.length ) {
		test_checkbox.change( { row_class: row.class }, go_test_checkbox_on_change );
	}
}

function go_test_loot_checkbox_on_load( row ) {
	jQuery( row.class ).hide();

	var loot_checkbox = jQuery( row.class + ' input[type="checkbox"]' );

	if ( 1 === loot_checkbox.length ) {
		loot_checkbox.change( { row_class: row.class }, go_test_loot_checkbox_on_change );
	}
}

function go_badge_input_on_load( row ) {
	jQuery( row.class ).hide();

	var badge_checkbox = jQuery( row.class + ' .go_badge_input_toggle' );
	var add_buttons    = jQuery( row.class + ' .go_badge_input_add' );
	var del_buttons    = jQuery( row.class + ' .go_badge_input_del' );

	if ( 1 === badge_checkbox.length ) {
		badge_checkbox.change( go_badge_input_checkbox_on_change );
	}

	if ( add_buttons.length >= 1 ) {
		add_buttons.click( go_badge_input_add_field );
	}

	if ( del_buttons.length >= 1 ) {
		del_buttons.click( go_badge_input_del_field );
	}
}

/**
 * Miscellaneous On Load Handlers
 *
 * "on_load" handlers are called when the page is first loaded. See `go_accordion_array_on_load()`
 * for context. These handlers generally apply to a large number of elements, accordions, and
 * setting rows. Misfit handlers belong here.
 *
 * Function name convention: "go_[subject]_on_load"
 */

// a custom function specifically for dealing with custom date and time picker inputs
function go_data_and_time_inputs_on_load() {

	// This can be faked by the browser, so it is not reliable. e.g. Internet Explorer can say that
	// its user agent is "chrome"
	var is_chrome = ( -1 !== navigator.userAgent.toLowerCase().indexOf( 'chrome' ) ? true : false );
	
	// the browser is supposedly not Chrome
	if ( ! is_chrome ) {
		if ( jQuery( 'input.go_datepicker' ).length > 0 ) {
			jQuery( 'input.go_datepicker' ).each( function( index, elem ) {
				jQuery( elem ).datepicker( { dateFormat: "yy-mm-dd" } );
			});
		}

		if ( jQuery( 'input.custom_time' ).length > 0 ) {
			jQuery( 'input.custom_time' ).each( function( index, elem ) {
				
				// initializes the custom time field as jQuery time selector field
				jQuery( elem ).ptTimeSelect();

				// retrieves time in <hour>:<minute> format
				var timer = jQuery( elem ).val();
				var hour, minutes = 0;
				var hour_str, minute_str, time_output = '';
				var divider_index = -1;

				divider_index = timer.search( ':' );
				hour          = parseInt( timer.substring( 0, divider_index ) );
				minutes       = parseInt( timer.substring( divider_index + 1, divider_index + 3 ) );
				var period    = ( hour < 12 ? 'AM' : 'PM' );

				if ( 'PM' === period && 12 !== hour ) {
					var hour_diff = hour - 12;
					if ( hour_diff >= 10 ) {
						hour_str = hour_diff;
					} else {
						hour_str = '0' + ( hour - 12 );
					}
				} else if ( 'AM' === period && 0 === hour ) {
					hour_str = '12';
				} else {
					hour_str = hour;
				}

				if ( 0 === minutes || minutes < 10 ) {
					minute_str = '0' + minutes;
				} else {
					minute_str = minutes;
				}
				
				// reformats time into <hour>:<minute> AM/PM
				time_output = hour_str + ':' + minute_str + ' ' + period;
				jQuery( elem ).val( time_output );
			});
		}
	}

	// adds a special keypress event handler for time inputs
	jQuery( 'input.custom_time' ).keypress( function( event ) {
		var regex = new RegExp( "^[0-9:APM]$" );
		var key = String.fromCharCode( ! event.charCode ? event.which : event.charCode );
		var input = jQuery( event.target );
		var value = input.val();
		
		if ( ! regex.test( key ) || value.length > 7 ) {
			event.preventDefault();
		}

		if ( value.length > 7 ) {
			input.val( value.substr( 0, 8 ) );
		}
	});
}

function go_before_publish_on_load() {
	jQuery( 'input#publish' ).on( 'click submit', go_before_task_publish_handler );

	/* 
	 * This is meant to prevent the page from being submitted via 
	 * the enter button, which would bypass the validation functions 
	 * that are run when the publish button is triggered.
	 */
	jQuery( 'form#post' ).keydown( function( e ) {

		// if the enter key is hit, trigger the "submit" event on the publish button
		if ( 13 === e.keyCode ) {
			e.preventDefault();
			jQuery( 'input#publish' ).trigger( 'submit' );
		}
	});
}

/**
 * End Miscellaneous On Load Handlers
 */

function go_start_filter_checkbox_on_change( event ) {
	var is_checked = false;
	if ( 1 === jQuery( event.target ).length && jQuery( event.target ).is( ':checked' ) ) {
		is_checked = true;
	}

	var filter_fields = jQuery( event.target ).siblings( '#go_start_info' );

	if ( is_checked ) {
		filter_fields.show();
	} else {
		filter_fields.hide();
	}
}

/**
 * NOTE: The following functionality exists because the time filter uses checkboxes, when it should
 *       use radio buttons. When the time filter is updated to make proper use of radio buttons,
 *       this function will no longer be needed.
 */
function go_time_filter_checkboxes_on_change( event ) {
	var target_checkbox = event.target;
	var sibling_checkbox = null;
	var is_checked, is_sibling_checked = false;
	
	if ( 1 === jQuery( event.target ).length && jQuery( event.target ).is( ':checked' ) ) {
		is_checked = true;
	}

	if ( 'go_calendar_checkbox' === target_checkbox.id ) {
		sibling_checkbox = jQuery( '#go_future_checkbox' );
	} else {
		sibling_checkbox = jQuery( '#go_calendar_checkbox' );
	}

	if ( sibling_checkbox.is( ':checked' ) ) {
		is_sibling_checked = true;
	}

	// creates the illusion that the date and time checkboxes are connected
	if ( is_checked && is_sibling_checked ) {
		sibling_checkbox.prop( 'checked', '' );
		sibling_checkbox.trigger( 'change' );
	}
}

function go_date_picker_add_field() {
	jQuery( '#go_list_of_decay_dates tbody' ).last().append(
		'<tr>' +
			'<td>' +
				'<input class="go_date_picker_input go_date_picker_calendar_input go_datepicker custom_date" ' +
					'name="go_mta_task_decay_calendar[]" ' +
					'type="date" placeholder="Click for Date"/>' +
				' @ (hh:mm AM/PM)' +
				'<input class="go_date_picker_input go_date_picker_time_input custom_time" ' +
					'name="go_mta_task_decay_calendar_time[]" ' +
					'type="time" placeholder="Click for Time" value="00:00" />' +
			'</td>' +
			'<td>' +
				'<input class="go_date_picker_input go_date_picker_modifier_input" ' +
					'name="go_mta_task_decay_percent[]" type="text" placeholder="Modifier"/>%' +
			'</td>' +
		'</tr>'
	);
}

function go_date_picker_del_field() {
	jQuery( '#go_list_of_decay_dates tbody tr' ).last( '.go_datepicker' ).remove();
}

function go_stage_reward_on_keyup( event ) {
	var target_field = event.target;
	var reward_stage = jQuery( target_field ).attr( 'stage' );
	var reward_type = jQuery( target_field ).attr( 'reward' );
	var target_val = target_field.value;

	var similar_fields = 'input[stage=' + reward_stage + '][reward=' + reward_type + ']';

	jQuery( similar_fields ).not( target_field ).val( target_val );
}

function go_admin_lock_checkbox_on_change( event ) {
	var target_checkbox = event.target;
	var is_checked = jQuery( target_checkbox ).is( ':checked' );
	var password_field = jQuery( target_checkbox ).siblings( '.go_admin_lock_text' )[0];

	if ( is_checked ) {
		if ( ! jQuery( password_field ).is( ':visible' ) ) {
			jQuery( password_field ).show();
		}
	} else {
		if ( jQuery( password_field ).is( ':visible' ) ) {
			jQuery( password_field ).hide();
		}
	}
}

function go_test_checkbox_on_change( event ) {
	var target_checkbox = event.target;
	var checkbox_row_class = event.handleObj.data.row_class;
	var row_stage = target_checkbox.id.getMid( 'go_mta_test_', '_lock' );
	var is_checked = jQuery( target_checkbox ).is( ':checked' );

	var test_loot_checkbox_row = jQuery( checkbox_row_class + ' ~ tr.cmb_id_go_mta_test_' + row_stage + '_lock_loot' ).first();
	var test_loot_row = jQuery( checkbox_row_class + ' ~ tr.cmb_id_go_mta_test_' + row_stage + '_lock_loot_mod' ).first();
	var test_field_row = jQuery( checkbox_row_class + ' ~ tr.cmb-type-go_test_field' ).first();

	var test_loot_checkbox = test_loot_checkbox_row.find( 'input[type="checkbox"]' );
	var is_loot_checked = test_loot_checkbox.is( ':checked' );

	if ( is_checked ) {
		if ( ! test_loot_checkbox_row.is( ':visible' ) ) {
			test_loot_checkbox_row.show();
		}

		if ( ! test_loot_row.is( ':visible' ) && is_loot_checked ) {
			test_loot_row.show();
		}

		if ( ! test_field_row.is( ':visible' ) ) {
			test_field_row.show();
		}
	} else {
		if ( test_loot_checkbox_row.is( ':visible' ) ) {
			test_loot_checkbox_row.hide();
		}

		if ( test_loot_row.is( ':visible' ) ) {
			test_loot_row.hide();
		}

		if ( test_field_row.is( ':visible' ) ) {
			test_field_row.hide();
		}
	}
}

function go_test_loot_checkbox_on_change( event ) {
	var target_checkbox = event.target;
	var checkbox_row_class = event.handleObj.data.row_class;
	var row_stage = target_checkbox.id.getMid( 'go_mta_test_', '_lock_loot' );
	var is_checked = jQuery( target_checkbox ).is( ':checked' );

	var test_loot_row = jQuery( checkbox_row_class + ' ~ tr.cmb_id_go_mta_test_' + row_stage + '_lock_loot_mod' ).first();
	var is_visible = test_loot_row.is( ':visible' );

	if ( is_checked && ! is_visible ) {
		test_loot_row.show();
	} else if ( ! is_checked && is_visible ) {
		test_loot_row.hide();
	}
}

function go_badge_input_checkbox_on_change( event ) {
	var target_checkbox = event.target;
	var is_checked = jQuery( target_checkbox ).is( ':checked' );

	var badge_input_list = jQuery( target_checkbox ).siblings( 'ul.go_stage_badge_container' );

	var is_visible = false;
	if ( badge_input_list.is( ':visible' ) ) {
		is_visible = true;
	}

	if ( is_checked && ! is_visible ) {
		badge_input_list.show();
	} else if ( ! is_checked && is_visible ) {
		badge_input_list.hide();
	}
}

function go_badge_input_add_field( event ) {
	var add_button       = event.target;
	var del_button       = jQuery( add_button ).siblings( '.go_badge_input_del' ).eq( 0 );
	var badge_input      = jQuery( add_button ).siblings( '.go_badge_input' )[0];
	var badge_list       = jQuery( add_button ).parents().eq( 1 );
	var badge_item       = jQuery( add_button ).parents().eq( 0 );
	var new_input_attrs  = [ 'type', 'name', 'class', 'stage' ];
	var new_button_attrs = [ 'type', 'class', 'value' ];

	// creates a new list item for the badge list
	var new_item = document.createElement( 'li' );
	jQuery( badge_item ).after( new_item );

	// create a new input element
	var new_input = document.createElement( 'input' );
	jQuery( new_item ).append( new_input );

	// creates add new button for adding list items
	var new_add_button = document.createElement( 'input' );
	jQuery( new_item ).append( new_add_button );

	// creates add new button for deleting list items
	var new_del_button = document.createElement( 'input' );
	jQuery( new_item ).append( new_del_button );

	var old_val = '';

	// gives the new input element all the old element's attributes (excluding value)
	for ( var x = 0; x < new_input_attrs.length; x++ ) {
		old_val = jQuery( badge_input ).attr( new_input_attrs[ x ] );
		jQuery( new_input ).attr( new_input_attrs[ x ], old_val );
	}

	// gives the new add button all the old add button's attributes
	for ( var i = 0; i < new_button_attrs.length; i++ ) {
		old_val = jQuery( add_button ).attr( new_button_attrs[ i ] );
		jQuery( new_add_button ).attr( new_button_attrs[ i ], old_val );
	}

	// attaches a click event listener to the new add button
	jQuery( new_add_button ).click( go_badge_input_add_field );

	// gives the new delete button all the old delete button's attributes
	if ( del_button.length > 0 ) {
		for ( var y = 0; y < new_button_attrs.length; y++ ) {
			old_val = jQuery( del_button ).attr( new_button_attrs[ y ] );
			jQuery( new_del_button ).attr( new_button_attrs[ y ], old_val );
		}

		// attaches a click event listener to the new delete button
		jQuery( new_del_button ).click( go_badge_input_del_field );
	}

	var badge_list_items = badge_list.children( 'li' );

	if ( badge_list_items.length > 0 ) {

		// shows the delete button of the first element in the list, if it was hidden
		var first_item_del_button = jQuery( badge_list_items[ 0 ] ).
			children( '.go_badge_input_del' ).eq( 0 );
		if ( ! first_item_del_button.is( ':visible' ) ) {
			first_item_del_button.show();
		}
	}
}

function go_badge_input_del_field( event ) {
	var del_button = event.target;
	var badge_list = jQuery( del_button ).parents().eq( 1 );
	var badge_item = jQuery( del_button ).parents().eq( 0 );

	// deletes the current list item
	jQuery( badge_item ).remove();

	var badge_list_items = badge_list.children( 'li' );

	// hides the delete button of the first element in the list, if there is only one left
	if ( 1 === badge_list_items.length ) {
		jQuery( badge_list_items[ 0 ] ).children( '.go_badge_input_del' ).hide();
	}
}

function go_badge_input_clear_empty_fields() {
	var stage_lists = jQuery( '.go_stage_badge_container' );

	if ( stage_lists.length > 0 ) {
		for ( var i = 0; i < stage_lists.length; i++ ) {
			var list_items = jQuery( stage_lists[ i ] ).children( 'li' );

			for ( var x = 0; x < list_items.length; x++ ) {
				var item = list_items[ x ];
				var val = jQuery( list_items[ x ] ).children( '.go_badge_input' )[0].value;

				if ( '' === val || 0 === Number.parseInt( val ) ) {
					jQuery( item ).remove();
				}
			}
		}
	}
}

function go_before_task_publish_handler() {
	go_badge_input_clear_empty_fields();
}


/**
 * Toggles an accordion and its underlying settings.
 *
 * Calls toggling callbacks——stored in the `on_toggle` property——on setting rows, if they exist.
 *
 * @since 2.6.1
 *
 * @see go_accordion_click_handler()
 *
 * @param object accordion_data Event specific accordion data from the `go_accordion_click_handler()`
 *                              function.
 */
function go_toggle_accordion( accordion_data ) {
	if ( 'undefined' === typeof accordion_data ) {
		return;
	}

	jQuery( accordion_data.id ).toggleClass( 'opened' );

	var is_open = ( jQuery( accordion_data.id ).hasClass( 'opened' ) ? true : false );
	var setting_rows = accordion_data.setting_rows;

	for ( var x = 0; x < setting_rows.length; x++ ) {
		var row = setting_rows[ x ];
		var row_class = row.class;
		if ( is_open ) {			
			if ( 'undefined' !== typeof row.on_toggle && null !== row.on_toggle ) {
				row.on_toggle( row_class, is_open );
			} else {
				jQuery( row_class ).show();
			}
		} else {
			jQuery( row_class ).hide();
		}
	}
}

/**
 * Handles clicks on accordion rows.
 *
 * Passes custom event data, containing accordion data, on to the `go_toggle_accordion()` function.
 * 
 * @since 2.6.1
 * 
 * @param object event The click event object.
 */
function go_accordion_click_handler( event ) {
	var args = {};
	if ( 'undefined' === typeof event.handleObj.data ) {
		return;
	}
	args = event.handleObj.data;
		
	go_toggle_accordion( args.accordion_data );
}

/**
 * Applies CSS classes, adds event listeners for accordions, and runs on_load callbacks. Called only
 * once, when the page has loaded.
 *
 * Calls any callbacks attached to the accordions or setting rows compiled by
 * `go_generate_accordion_array()`. If a setting row doesn't have a callback to call, the row is
 * hidden.
 *
 * @since 2.6.1
 *
 * @param object accordion_array Accordion and setting data, see `go_generate_accordion_array()`
 *                               for structure.
 * @param array  accordion_names An array of keys (names) for the accordion array parameter.
 */
function go_accordion_array_on_load( accordion_array, accordion_names ) {
	if ( 'undefined' === typeof accordion_array || 'undefined' === typeof accordion_names||
			0 === accordion_names.length ) {
		return;
	}

	for ( var i = 0; i < accordion_names.length; i++ ) {
		var name = accordion_names[ i ];
		var accordion_data = accordion_array[ name ];
		var accordion_row_class = accordion_data.row_class;
		if ( 'undefined' !== typeof accordion_data.on_load && null !== accordion_data.on_load ) {
			accordion_data.on_load( accordion_row_class );
		}

		// add click event listener for the accordion
		jQuery( accordion_data.id ).click( { accordion_data: accordion_data }, go_accordion_click_handler );

		var rows = accordion_data.setting_rows;
		for ( var x = 0; x < rows.length; x++ ) {
			var row = rows[ x ];
			var row_callback = null;
			var row_el = jQuery( row.class );
			
			// applies the GO "condensed" CSS class, to style the row
			if ( ! row_el.hasClass( 'condensed' ) ) {
				row_el.addClass( 'condensed' );
				row_el.children().addClass( 'condensed' );
			}
			
			if ( 'undefined' !== typeof row.on_load && null !== row.on_load ) {
				row.on_load( row );
			} else if ( row_el.is( ':visible' ) ) {
				row_el.hide();
			}
		}
	}

	/** 
	 * Run miscellaneous on-load functions below.
	 */
	go_data_and_time_inputs_on_load();

	go_before_publish_on_load();
}

/**
 * Generates an object containing nearly all the IDs and class identifiers for setting accordions
 * and their settings.
 *
 * @since 2.6.1
 *
 * @return object Contains the accordion ID, accordion row classes, setting row classes, and setting
 *                row callbacks for each accordion.
 *
 *     e.g.
 *     Object {
 *         advanced_task: {
 *             id           : '#go_advanced_task_settings_accordion',
 *             row_class    : 'tr.cmb-type-go_settings_accordion.cmb_id_advanced_task_settings',
 *             on_load: function() {...},
 *             setting_rows : [
 *                 {
 *                     class          : 'tr.cmb-type-go_rank_list.cmb_id_go_mta_req_rank',
 *                     on_load  : function() {...},
 *                     on_toggle: function() {...}
 *                 },
 *                 {
 *                     class          : 'tr.cmb-type-go_start_filter.cmb_id_go_mta_start_filter',
 *                     on_load  : function() {...},
 *                     on_toggle: function() {...}
 *                 },
 *                 ...
 *             ],
 *         },
 *         stage_one: {
 *             id       : '#go_stage_one_settings_accordion',
 *             row_class: 'tr.cmb-type-go_settings_accordion.cmb_id_stage_one_settings',
 *             ...
 *         },
 *         ...
 *     }
 */
function go_generate_accordion_array() {
	var accordion_map = {
		advanced_task: {
			settings: [
				{
					cmb_type: 'go_rank_list',
					cmb_id: 'req_rank'
				},
				{
					cmb_type: 'text',
					cmb_id: 'bonus_currency_filter'
				},
				{
					cmb_type: 'text',
					cmb_id: 'penalty_filter'
				},
				{
					cmb_type: 'go_start_filter',
					cmb_id: 'start_filter',
					on_load: go_start_filter_on_load,
					on_toggle: go_start_filter_on_toggle
				},
				{
					cmb_type: 'go_future_filters',
					cmb_id: 'time_filters',
					on_load: go_time_filters_on_load
				},
				{
					cmb_type: 'go_decay_table',
					cmb_id: 'date_picker',
					on_load: go_date_picker_on_load,
					on_toggle: go_date_picker_on_toggle
				},
				{
					cmb_type: 'go_time_modifier_inputs',
					cmb_id: 'time_modifier',
					on_load: go_time_modifier_on_load,
					on_toggle: go_time_modifier_on_toggle
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'focus_category_lock'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'three_stage_switch'  
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'five_stage_switch'
				},
				{
					cmb_type: 'go_task_chain_order',
					cmb_id: 'chain_order',
					on_load: go_chain_order_on_load,
					on_toggle: go_chain_order_on_toggle
				},
				{
					cmb_type: 'text',
					cmb_id: 'final_chain_message',
					on_toggle: go_final_chain_message_on_toggle
				},
			],
		},
		stage_one: {
			settings: [
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_one_points',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_one_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_one_bonus_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_admin_lock',
					cmb_id: 'encounter_admin_lock',
					on_load: go_admin_lock_on_load,
					on_toggle: go_admin_lock_on_toggle,
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'encounter_url_key'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'encounter_upload'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_encounter_lock',
					on_load: go_test_checkbox_on_load
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_encounter_lock_loot',
					on_load: go_test_loot_checkbox_on_load,
					on_toggle: go_test_loot_checkbox_on_toggle
				},
				{
					cmb_type: 'go_test_modifier',
					cmb_id: 'test_encounter_lock_loot_mod',
					on_toggle: go_test_loot_mod_on_toggle
				},
				{
					cmb_type: 'go_test_field',
					cmb_id: 'test_encounter_lock_fields',
					on_toggle: go_test_field_on_toggle
				},
				{
					cmb_type: 'go_badge_input',
					cmb_id: 'stage_one_badge',
					on_load: go_badge_input_on_load,
					on_toggle: go_badge_input_on_toggle
				},
			],
		},
		stage_two: {
			settings: [
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_two_points',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_two_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_two_bonus_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_admin_lock',
					cmb_id: 'accept_admin_lock',
					on_load: go_admin_lock_on_load,
					on_toggle: go_admin_lock_on_toggle,
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'accept_url_key'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'accept_upload'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_accept_lock',
					on_load: go_test_checkbox_on_load
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_accept_lock_loot',
					on_load: go_test_loot_checkbox_on_load,
					on_toggle: go_test_loot_checkbox_on_toggle
				},
				{
					cmb_type: 'go_test_modifier',
					cmb_id: 'test_accept_lock_loot_mod',
					on_toggle: go_test_loot_mod_on_toggle
				},
				{
					cmb_type: 'go_test_field',
					cmb_id: 'test_accept_lock_fields',
					on_toggle: go_test_field_on_toggle
				},
				{
					cmb_type: 'go_badge_input',
					cmb_id: 'stage_two_badge',
					on_load: go_badge_input_on_load,
					on_toggle: go_badge_input_on_toggle
				},
			],
		},
		stage_three: {
			settings: [
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_three_points',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_three_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_three_bonus_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_admin_lock',
					cmb_id: 'completion_admin_lock',
					on_load: go_admin_lock_on_load,
					on_toggle: go_admin_lock_on_toggle,
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'completion_url_key'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'completion_upload'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_completion_lock',
					on_load: go_test_checkbox_on_load
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_completion_lock_loot',
					on_load: go_test_loot_checkbox_on_load,
					on_toggle: go_test_loot_checkbox_on_toggle
				},
				{
					cmb_type: 'go_test_modifier',
					cmb_id: 'test_completion_lock_loot_mod',
					on_toggle: go_test_loot_mod_on_toggle
				},
				{
					cmb_type: 'go_test_field',
					cmb_id: 'test_completion_lock_fields',
					on_toggle: go_test_field_on_toggle
				},
				{
					cmb_type: 'go_badge_input',
					cmb_id: 'stage_three_badge',
					on_load: go_badge_input_on_load,
					on_toggle: go_badge_input_on_toggle
				},
			],
		},
		stage_four: {
			settings: [
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_four_points',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_four_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_four_bonus_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_admin_lock',
					cmb_id: 'mastery_admin_lock',
					on_load: go_admin_lock_on_load,
					on_toggle: go_admin_lock_on_toggle,
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'mastery_url_key'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'mastery_upload'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_mastery_lock',
					on_load: go_test_checkbox_on_load
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'test_mastery_lock_loot',
					on_load: go_test_loot_checkbox_on_load,
					on_toggle: go_test_loot_checkbox_on_toggle
				},
				{
					cmb_type: 'go_test_modifier',
					cmb_id: 'test_mastery_lock_loot_mod',
					on_toggle: go_test_loot_mod_on_toggle
				},
				{
					cmb_type: 'go_test_field',
					cmb_id: 'test_mastery_lock_fields',
					on_toggle: go_test_field_on_toggle
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'mastery_privacy'
				},
				{
					cmb_type: 'go_badge_input',
					cmb_id: 'stage_four_badge',
					on_load: go_badge_input_on_load,
					on_toggle: go_badge_input_on_toggle
				},
				{
					cmb_type: 'go_bonus_loot',
					cmb_id: 'mastery_bonus_loot'
				},
			],
		},
		stage_five: {
			on_load: go_stage_five_on_load,
			settings: [
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_five_points',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_five_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_stage_reward',
					cmb_id: 'stage_five_bonus_currency',
					on_load: go_stage_reward_on_load
				},
				{
					cmb_type: 'go_repeat_amount',
					cmb_id: 'repeat_amount'
				},
				{
					cmb_type: 'go_admin_lock',
					cmb_id: 'repeat_admin_lock',
					on_load: go_admin_lock_on_load,
					on_toggle: go_admin_lock_on_toggle,
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'repeat_upload'
				},
				{
					cmb_type: 'checkbox',
					cmb_id: 'repeat_privacy'
				},
				{
					cmb_type: 'go_badge_input',
					cmb_id: 'stage_five_badge',
					on_load: go_badge_input_on_load,
					on_toggle: go_badge_input_on_toggle
				},
			],
		},
	};

	var accordion_data = {};

	if ( Object.keys( accordion_map ).length > 0 ) {
		for ( var accordion_name in accordion_map ) {
			var accordion_callback = null;
			if ( 'undefined' !== typeof accordion_map[ accordion_name ].on_load ) {
				accordion_callback = accordion_map[ accordion_name ].on_load;
			}

			accordion_data[ accordion_name ] = {
				id           : '#go_' + accordion_name + '_settings_accordion',
				row_class    : 'tr.cmb-type-go_settings_accordion.cmb_id_' + accordion_name + '_settings',
				on_load: accordion_callback,
				setting_rows : [],
			};

			// the array of custom meta box types and IDs for each setting under the current accordion
			var settings_array = accordion_map[ accordion_name ].settings;
			
			for ( var i = 0; i < settings_array.length; i++ ) {
				var setting_obj = settings_array[ i ];
				
				var on_load = null;
				if ( 'undefined' !== typeof setting_obj.on_load ) {
					on_load = setting_obj.on_load;
				}

				var on_toggle = null;
				if ( 'undefined' !== typeof setting_obj.on_toggle ) {
					on_toggle = setting_obj.on_toggle;
				}
				
				var setting_row_obj = {
					class          : 'tr.cmb-type-' + setting_obj.cmb_type + '.cmb_id_go_mta_' + setting_obj.cmb_id,
					on_load  : on_load,
					on_toggle: on_toggle
				};
				accordion_data[ accordion_name ].setting_rows.push( setting_row_obj );
			}
		}
	}

	return accordion_data;
}

jQuery( document ).ready( function() {
	var go_accordion_array       = go_generate_accordion_array();
	var go_accordion_array_names = Object.keys( go_accordion_array );

	go_accordion_array_on_load( go_accordion_array, go_accordion_array_names );
});

// jQuery( '.go_task_settings_accordion' ).click( function () {
// 	jQuery( this ).children( '.go_triangle_container' ).children( '.go_task_accordion_triangle' ).toggleClass( 'down' );
// });

// function go_toggle_settings_rows( stage_settings, condensed, number ) {
// 	condensed = ( 'undefined' !== typeof condensed ? condensed : false );
// 	for ( var setting in stage_settings ) {
// 		if ( condensed === true ) {
// 			stage_settings[ setting ].addClass( 'condensed' ).children().addClass( 'condensed' );
// 		}
// 		stage_settings[ setting ].toggle( 'slow' );
// 	}
// 	if ( number ) {
// 		for ( var i = 1; i < 6; i++ ) {
// 			if ( i === number ) {	
// 				continue;
// 			}
// 			stage_accordions[ i ].removeClass( "opened" );
// 			if ( stage_settings !== task_settings ) {
// 				jQuery( "#go_advanced_task_settings_accordion" ).removeClass( "opened" );
// 				for ( var settings in task_settings ) {
// 					task_settings[ settings ].hide( 'slow' );
// 				}
// 			} else {
// 				for ( var setting_rows in stage_settings_rows[ i ] ) {
// 					if ( null !== stage_settings_rows[ i ][ setting_rows ] ) {
// 						stage_settings_rows[ i ][ setting_rows ].hide( 'slow' );
// 					}
// 				}
// 			}
// 			if ( jQuery( '#go_calendar_checkbox' ).prop( 'checked' ) &&
// 					jQuery( "#go_advanced_task_settings_accordion" ).hasClass( 'opened' ) ) {
// 				calendar_row.show( 'slow' );
// 				future_row.hide();
// 			} else {
// 				calendar_row.hide( 'slow' );
// 				future_row.hide();
// 			}
// 			if ( jQuery( '#go_future_checkbox' ).prop( 'checked' ) &&
// 					jQuery( "#go_advanced_task_settings_accordion" ).hasClass( 'opened' ) ) {
// 				future_row.show( 'slow' );
// 				calendar_row.hide();
// 			} else {
// 				future_row.hide( 'slow' );
// 				calendar_row.hide();
// 			}
// 		}
// 	}
// }

// var stage_accordion_rows = {
// 	1: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_one_settings' ),
// 	2: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_two_settings' ),
// 	3: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_three_settings' ),
// 	4: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_four_settings' ),
// 	5: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_five_settings' )
// };

// var stage_accordions = {
// 	1: jQuery( '#go_stage_one_settings_accordion' ),
// 	2: jQuery( '#go_stage_two_settings_accordion' ),
// 	3: jQuery( '#go_stage_three_settings_accordion' ),
// 	4: jQuery( '#go_stage_four_settings_accordion' ),
// 	5: jQuery( '#go_stage_five_settings_accordion' )
// };

// var stage_settings_rows = {
// 	1: [
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_points' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_currency' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_bonus_currency' ),
// 		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_encounter_admin_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_encounter_url_key' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_encounter_upload' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_encounter_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_encounter_lock_loot' ),
// 		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_encounter_lock_loot_mod' ),
// 		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_encounter' ),
// 		jQuery( 'tr.cmb_id_go_mta_stage_one_badge' )
// 	],
// 	2: [
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_points' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_currency' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_bonus_currency' ),
// 		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_accept_admin_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_accept_url_key' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_accept_upload' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_accept_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_accept_lock_loot' ),
// 		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_accept_lock_loot_mod' ),
// 		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_accept' ),
// 		jQuery( 'tr.cmb_id_go_mta_stage_two_badge' )
// 	],
// 	3: [
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_points' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_currency' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_bonus_currency' ),
// 		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_completion_admin_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_upload' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_completion_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_completion_lock_loot' ),
// 		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_completion_lock_loot_mod' ),
// 		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_completion' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_task_mastery' ),
// 		jQuery( 'tr.cmb_id_go_mta_stage_three_badge' )
// 	],
// 	4: [
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_points' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_currency' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_bonus_currency' ),
// 		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_mastery_admin_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock_loot' ),
// 		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_mastery_lock_loot_mod' ),
// 		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_mastery' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_privacy' ),
// 		jQuery( 'tr.cmb_id_go_mta_stage_four_badge' ),
// 		jQuery( 'tr.cmb-type-go_bonus_loot.cmb_id_go_mta_mastery_bonus_loot' )
// 	],
// 	5: [
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_points' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_currency' ),
// 		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_bonus_currency' ),
// 		jQuery( 'tr.cmb-type-go_repeat_amount.cmb_id_go_mta_repeat_amount' ),
// 		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_repeat_admin_lock' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_repeat_upload' ),
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_repeat_privacy' ),
// 		jQuery( 'tr.cmb_id_go_mta_stage_five_badge' )
// 	]
// };

// /*
//  * Advanced Settings Accordion
//  */

// var task_settings = [
// 	jQuery( 'tr.cmb-type-go_rank_list.cmb_id_go_mta_req_rank' ),
// 	jQuery( 'tr.cmb-type-go_start_filter.cmb_id_go_mta_start_filter' ),
// 	jQuery( 'tr.cmb-type-go_future_filters.cmb_id_go_mta_time_filters' ),
// 	jQuery( 'tr.cmb-type-text.cmb_id_go_mta_bonus_currency_filter' ),
// 	jQuery( 'tr.cmb-type-text.cmb_id_go_mta_penalty_filter' ),
// 	jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_focus_category_lock' ),
// 	jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_three_stage_switch' ),
// 	jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_five_stage_switch' ), 
// 	jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ),
// 	jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' )
// ];

// go_toggle_settings_rows( task_settings );

// // jQuery( '#go_advanced_task_settings_accordion' ).click( function () {
// // 	jQuery( this ).toggleClass( 'opened' );
// // 	go_toggle_settings_rows( task_settings, true, 6 );
// // 	if ( in_chain && jQuery( this ).hasClass( 'opened' ) ) {
// // 		jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ).show( 'slow' );
// // 		if ( is_final_task ) {
// // 			jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).show();
// // 		} else {
// // 			if ( jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).is( ":visible" ) ) {
// // 				jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).hide();
// // 			}
// // 		}
// // 	} else {
// // 		if ( jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ).is( ":visible" ) ) {
// // 			jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ).hide();	
// // 		}
// // 		if ( jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).is( ":visible" ) ) {
// // 			jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).hide();
// // 		}
// // 	}
// // 	if ( jQuery( '#go_start_checkbox' ).prop( 'checked' ) ) {
// // 		jQuery( '#go_start_info' ).show( 'slow' );
// // 	} else {
// // 		jQuery( '#go_start_info' ).hide( 'slow' );
// // 	}
// // });

// /*
//  * Modifier Date Picker
//  */

// jQuery( '#go_start_checkbox' ).click( function () {
// 	if ( jQuery( '#go_start_checkbox' ).prop( 'checked' ) ) {
// 		jQuery( '#go_start_info' ).show( 'slow' );
// 	} else {
// 		jQuery( '#go_start_info' ).hide( 'slow' );
// 	}
// });

// /*
//  * Modifier Date Picker
//  */

// var calendar_row = jQuery( 'tr.cmb-type-go_decay_table.cmb_id_go_mta_date_picker' );
// var future_row = jQuery( 'tr.cmb-type-go_time_modifier_inputs.cmb_id_go_mta_time_modifier' );

// calendar_row.hide();
// future_row.hide();

// calendar_row.addClass( 'condensed' ).children().addClass( 'condensed' );
// future_row.addClass( 'condensed' ).children().addClass( 'condensed' );

// jQuery( '#go_calendar_checkbox' ).click( function () {
// 	jQuery( '#go_future_checkbox' ).prop( 'checked', false );
// 	if ( jQuery( '#go_calendar_checkbox' ).prop( 'checked' ) ) {
// 		calendar_row.show( 'slow' );
// 		future_row.hide();
// 	} else {
// 		calendar_row.hide( 'slow' );
// 	}
// });
// jQuery( '#go_future_checkbox' ).click( function () {
// 	jQuery( '#go_calendar_checkbox' ).prop( 'checked', false );
// 	if ( jQuery( '#go_future_checkbox' ).prop( 'checked' ) ) {
// 		future_row.show( 'slow' );
// 		calendar_row.hide();
// 	} else {
// 		future_row.hide( 'slow' );
// 	}
// });

// var is_chrome = navigator.userAgent.toLowerCase().indexOf( 'chrome' ) > -1;
// jQuery( document ).ready( function () {
// 	jQuery ( 'input.custom_time' ).each( function () {
// 		jQuery( this ).keypress( function( e ) {
// 			var regex = new RegExp( "^[0-9+:+A+P+M]$" );
// 			var key = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
// 			if ( ! regex.test( key ) || jQuery( this ).val().length > 7 ) {
// 				e.preventDefault();
// 			}
// 			if ( jQuery( this ).val().length > 7 ) {
// 				jQuery( this ).val( jQuery( this ).val().substr( 0, 8 ) );
// 			}
// 		});
// 	});
// 	if ( ! is_chrome ) {
// 		if ( jQuery( 'input.go_datepicker' ).length ) {
// 			jQuery( 'input.go_datepicker' ).each( function () {
// 				jQuery( this ).datepicker({ dateFormat: "yy-mm-dd" });
// 			});
// 		}
// 		if ( jQuery( 'input.custom_time' ).length ) {
// 			jQuery( 'input.custom_time' ).each( function () {
// 				jQuery( this ).ptTimeSelect(); // Turn input[type='time'] into a custom jquery ui time picker
// 				var timer = jQuery( this ).val(); // Retrieve time value in 00:00 (hh:mm) format
// 				var ampm = ( ( parseInt(timer.substring( 0, timer.search( ':' ) ) ) < 12 ) ? 'AM' : 'PM' ); // Check to see if the time is AM/PM for 12 traditional clocks
// 				var hour = parseInt( timer.substring( 0, timer.search( ':' ) ) ); // Retrieve the first part (hours) of time as a number
// 				var minutes = timer.substring( timer.search( ':' ) + 1, timer.search( ':' ) + 3); // Retrieve the second part (minutes) of time
// 				var hour_pretty = ( ( 'PM' === ampm && 12 !== hour ) ? ( ( hour - 12 >= 10 ) ? hour - 12 : '0' + hour - 12 ) : ( ( 'AM' === ampm && hour === 0 ) ? 12 : hour ) ); // Format the hour string to be within 1-12 numerically, rather than 24 hour cycle as is saved to database
// 				jQuery( this ).val( hour_pretty + ':' + minutes + ' ' + ampm ); // Reconstruct into hh:mm AM/PM human-readable format
// 			});
// 		}
// 	}

// // 	// prepare the task chain sortable list
// // 	go_prepare_sortable_list();
// });

// /*
//  * Admin Lock
//  */

// function toggle_admin_lock( accordion, stage ) {
// 	if ( typeof accordion === 'string' ) {
// 		if ( jQuery(accordion).hasClass( "opened" ) ) {
// 			if ( jQuery( '#go_mta_' + stage + '_admin_lock_checkbox' ).prop( 'checked' ) ) {
// 				if ( ! jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
// 					jQuery( '#go_mta_' + stage + '_admin_lock_input' ).show( 'slow' );
// 				}
// 			} else {
// 				if ( jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
// 					jQuery( '#go_mta_' + stage + '_admin_lock_input' ).hide( 'slow' );
// 				}
// 			}
// 		} else {
// 			if ( jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_' + stage + '_admin_lock' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_' + stage + '_admin_lock' ).hide();
// 			}
// 		}
// 	} else {
// 		if ( jQuery( '#go_mta_' + stage + '_admin_lock_checkbox' ).prop( 'checked' ) ) {
// 			if ( ! jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
// 				jQuery( '#go_mta_' + stage + '_admin_lock_input' ).show( 'slow' );
// 			}
// 		} else {
// 			if ( jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
// 				jQuery( '#go_mta_' + stage + '_admin_lock_input' ).hide( 'slow' );
// 			}
// 		}
// 	}
// }

// jQuery( '.go_admin_lock_checkbox' ).click( function () {
// 	var stage = this.id.getMid( "go_mta_", "_admin_lock_checkbox" );
// 	toggle_admin_lock( null, stage );
// });

// /*
//  * Task Tests
//  */

// function toggle_tests ( accordion, stage ) {
// 	if (typeof stage === 'string' ) {
// 		if ( jQuery( accordion ).hasClass( "opened" ) ) {
// 			if ( jQuery( '#go_mta_test_' + stage + '_lock' ).prop( 'checked' ) ) {
// 				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).show( 'slow' );
// 				if ( jQuery( '#go_mta_test_' + stage + '_lock_loot' ).prop( 'checked' ) ) {
// 					if ( ! jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 						jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).show( 'slow' );
// 					}
// 				} else {
// 					if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 						jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
// 					}
// 				}
// 				jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).show( 'slow' );
// 			} else {
// 				if ( jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).is( ':visible' ) ) {
// 					jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).hide();
// 				}
// 				if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 					jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
// 				}
// 				if ( jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is( ':visible' ) ) {
// 					jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide();
// 				}
// 			}
// 		} else {
// 			if ( jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).hide();
// 			}
// 			if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
// 			}
// 			if ( jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide();
// 			}
// 		}
// 	}
// }

// function toggle_test_all ( stage ) {
// 	if (typeof stage === 'string' ) {
// 		if ( jQuery( '#go_mta_test_' + stage + '_lock' ).prop( 'checked' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).show( 'slow' );
// 			if ( jQuery( '#go_mta_test_' + stage + '_lock_loot' ).prop( 'checked' ) ) {
// 				if ( ! jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 					jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).show( 'slow' );
// 				}
// 			} else {
// 				if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 					jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
// 				}
// 			}
// 			jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).show( 'slow' );
// 		} else {
// 			if ( jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).hide( 'hide' );
// 			}
// 			if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide( 'hide' );
// 			}
// 			if ( jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide( 'hide' );
// 			}
// 		}
// 	}
// }

// function toggle_test_loot ( stage ) {
// 	if (typeof stage === 'string' ) {
// 		if ( jQuery( '#go_mta_test_' + stage + '_lock_loot' ).prop( 'checked' ) ) {
// 			if ( ! jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).show( 'slow' );
// 			}
// 		} else {
// 			if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
// 				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide( 'slow' );
// 			}
// 		}
// 	}
// }

// jQuery( '#go_mta_test_encounter_lock, #go_mta_test_accept_lock, #go_mta_test_completion_lock, #go_mta_test_mastery_lock' ).click( function () {
// 	var stage = this.id.getMid( "go_mta_test_", "_lock" );
// 	toggle_test_all( stage );
// });

// jQuery( '#go_mta_test_encounter_lock_loot, #go_mta_test_accept_lock_loot, #go_mta_test_completion_lock_loot, #go_mta_test_mastery_lock_loot' ).click( function () {
// 	var stage = this.id.getMid( "go_mta_test_", "_lock_loot" );
// 	toggle_test_loot( stage );
// });

// /*
//  * Badge Rewarding
//  */

// jQuery( '.go_badge_input_toggle' ).each( function () {
// 	var stage = jQuery( this ).attr( 'stage' );
// 	if ( jQuery( this ).prop( 'checked' ) ) {
// 		jQuery( '.go_badge_input[stage=' + stage + ']' ).show( 'slow' );
// 		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).show( 'slow' );
// 		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).show( 'slow' );
// 	} else {
// 		jQuery( '.go_badge_input[stage=' + stage + ']' ).hide( 'slow' );
// 		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).hide( 'slow' );
// 		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).hide( 'slow' );
// 	}
// });

// jQuery( '.go_badge_input_toggle' ).click( function () {
// 	var stage = jQuery( this ).attr( 'stage' );
// 	if ( jQuery( this ).prop( 'checked' ) ) {
// 		jQuery( '.go_badge_input[stage=' + stage + ']' ).show( 'slow', function () {
// 			jQuery( this ).focus();
// 		});
// 		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).show( 'slow' );
// 		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).show( 'slow' );
// 	} else {
// 		jQuery( '.go_badge_input[stage=' + stage + ']' ).hide( 'slow' );
// 		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).hide( 'slow' );
// 		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).hide( 'slow' );
// 	}
// });

// jQuery( 'button[name="go_badge_input_add"]' ).click( function( e ) {
// 	e.preventDefault();
// 	var stage = jQuery( this ).attr( 'stage' );
// 	jQuery( this ).before( "<input type='text' name='go_badge_input_stage_" + stage + "[]' class='go_badge_input' stage='" + stage + "'/>" );
// 	jQuery( 'input[name="go_badge_input_stage_' + stage + '[]"]' ).last().focus();
// });

// jQuery( 'button[name="go_badge_input_remove"]' ).click( function( e ) {
// 	e.preventDefault();
// 	var stage = jQuery( this ).attr( 'stage' );
// 	jQuery( '.go_badge_input[stage=' + stage + ']' ).last().remove();
// });

// /*
//  * Stage One Settings Accordion
//  */

// go_toggle_settings_rows( stage_settings_rows[1] );

// stage_accordions[1].click( function () {
// 	console.log( 'clicked accordion (1)!' );
// 	jQuery( this ).toggleClass( 'opened' );
// 	go_toggle_settings_rows( stage_settings_rows[1], true, 1 );
// 	toggle_admin_lock( stage_accordions[1], 'encounter' );
// 	toggle_tests( stage_accordions[1], 'encounter' );
// });

// /*
//  * Stage Two Settings Accordion
//  */

// go_toggle_settings_rows(stage_settings_rows[2] );

// stage_accordions[2].click( function () {
// 	console.log( 'clicked accordion (2)!' );
// 	jQuery( this ).toggleClass( 'opened' );
// 	go_toggle_settings_rows( stage_settings_rows[2], true, 2 );
// 	toggle_admin_lock( stage_accordions[2], 'accept' );
// 	toggle_tests( stage_accordions[2], 'accept' );
// });

// /*
//  * Stage Three Settings Accordion
//  */

// go_toggle_settings_rows( stage_settings_rows[3] );

// stage_accordions[3].click( function () {
// 	console.log( 'clicked accordion (3)!' );
// 	jQuery( this ).toggleClass( 'opened' );
// 	go_toggle_settings_rows( stage_settings_rows[3], true, 3 );
// 	toggle_admin_lock( stage_accordions[3], 'completion' );
// 	if ( jQuery( this ).hasClass( 'opened' ) ) {
// 		if ( ! jQuery( '#go_mta_task_mastery' ).prop( 'checked' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
// 		} else {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
// 		}
// 	} else {
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
// 	}
// 	toggle_tests( stage_accordions[3], 'completion' );
// });

// /*
//  * Stage Four Settings Accordion
//  */

// go_toggle_settings_rows( stage_settings_rows[4] );

// stage_accordions[4].click( function () {
// 	console.log( 'clicked accordion (4)!' );
// 	jQuery( this ).toggleClass( 'opened' );
// 	go_toggle_settings_rows( stage_settings_rows[4], true, 4 );
// 	toggle_admin_lock( stage_accordions[4], 'mastery' );
// 	if ( jQuery( this ).hasClass( 'opened' ) ) {
// 		if ( ! jQuery( '#go_mta_task_repeat' ).prop( 'checked' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
// 			if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
// 				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
// 			} else {
// 				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
// 			}
// 		} else {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).show();
// 		}
// 	} else {
// 		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
// 	}
// 	toggle_tests( stage_accordions[4], 'mastery' );
// });

// /*
//  * Three Stage Toggle
//  */

// jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).click( function () {
// 	if ( jQuery( this ).prop( 'checked' ) ) {
// 		jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).prop( 'checked', true );
// 		jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).prop( 'checked', false );
// 		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).hide( 'slow' );
// 		stage_accordion_rows[4].hide( 'slow' );
// 		if ( stage_accordions[4].hasClass( 'opened' ) ) {
// 			jQuery(stage_settings_rows[4] ).hide();
// 		}
// 		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).hide( 'slow' );
// 		stage_accordion_rows[5].hide( 'slow' );
// 		if ( stage_accordions[5].hasClass( 'opened' ) ) {
// 			jQuery( stage_settings_rows[5] ).hide();
// 		}
// 		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
// 		}
// 	}else{
// 		jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).prop( 'checked', false );
// 		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).toggle( 'slow' );
// 		stage_accordion_rows[4].toggle( 'slow' );
// 		if ( stage_accordions[4].hasClass( 'opened' ) ) {
// 			jQuery( stage_settings_rows[4] ).hide();
// 		}
// 		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
// 		}
// 	}
// });

// var stage_three = GO_TASK_DATA.stages.is_stage_three_active;

// if ( stage_three ) {
// 	jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).toggle( 'slow' );
// 	stage_accordion_rows[4].toggle( 'slow' );
// 	if ( stage_accordions[4].hasClass( 'opened' ) ) {
// 		go_toggle_settings_rows( stage_settings_rows[4], true, 4 );
// 	}
// }

// /*
//  * Five Stage Toggle
//  */

// go_toggle_settings_rows( stage_settings_rows[5] );
// stage_accordions[5].click( function () {
// 	console.log( 'clicked accordion (5)!' );
// 	jQuery( this ).toggleClass( 'opened' );
// 	go_toggle_settings_rows( stage_settings_rows[5], true, 5 );
// 	toggle_admin_lock( stage_accordions[5], 'repeat' );
// });

// jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).click( function () {
// 	if ( jQuery( this ).prop( 'checked' ) ) {
// 		jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).prop( 'checked', true );
// 		jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).prop( 'checked', false );
// 		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).toggle( 'slow' );
// 		stage_accordion_rows[5].toggle( 'slow' );
// 		if ( stage_accordions[5].hasClass( 'opened' ) ) {
// 			go_toggle_settings_rows( stage_settings_rows[5], true, 5 );
// 		}
// 		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).show( 'slow' );
// 		stage_accordion_rows[4].show( 'slow' );
// 		if ( stage_accordions[4].hasClass( 'opened' ) ) {
// 			jQuery( stage_settings_rows[4] ).show( 'slow' );
// 		}
// 		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
// 		}
// 		if ( jQuery( stage_accordions[4] ).hasClass( 'opened' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).show();
// 		}
// 	} else {
// 		jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).prop( 'checked', false );
// 		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).toggle( 'slow' );
// 		stage_accordion_rows[5].toggle( 'slow' );
// 		if (stage_accordions[5].hasClass( 'opened' ) ) {
// 			go_toggle_settings_rows( stage_settings_rows[5], true, 5 );
// 		}
// 		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
// 		}
// 		if ( jQuery( stage_accordions[4] ).hasClass( 'opened' ) ) {
// 			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
// 		}
// 	}
// });

// var stage_five = GO_TASK_DATA.stages.is_stage_five_active;

// if ( stage_five ) {
// 	jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).show( 'slow' );
// 	stage_accordion_rows[5].show( 'slow' );
// } else {
// 	jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).hide( 'slow' );
// 	stage_accordion_rows[5].hide( 'slow' );
// }

// /*
//  * Toggling Bonus Loot
//  */

// var go_bonus_loot_check_box = jQuery( "#go_bonus_loot_checkbox" );
// var go_bonus_loot_items = jQuery( "#go_bonus_loot_wrap" );
// go_bonus_loot_items.prop( "hidden", true );
// go_bonus_loot_check_box.click( function () {
// 	if ( jQuery( this ).is( ":checked" ) ) {
// 		go_bonus_loot_items.prop( "hidden", false );
// 	} else {
// 		go_bonus_loot_items.prop( "hidden", true );
// 	}
// });
// if ( go_bonus_loot_check_box.is( ":checked" ) ) {
// 	go_bonus_loot_items.prop( "hidden", false );
// } else {
// 	go_bonus_loot_items.prop( "hidden", true );
// }

// function go_bonus_loot_rarity_validate ( item_rarities ) {
// 	var accordion_id = 'go_stage_four_settings_accordion';

// 	// will contain the ids of any failing elements
// 	var invalid_el_ids = [];
// 	var range_min_str = '';
// 	var range_max_str = '';
	
// 	jQuery.each( item_rarities, function ( index, input_field ) {
// 		var id = input_field.id;
// 		var range_min = input_field.min;
// 		range_min_str = range_min;
// 		var range_max = input_field.max;
// 		range_max_str = range_max;
// 		var rarity_val = input_field.value;

// 		// test the rarity value against the desired float pattern
// 		// (i.e. "XX" or "XX.X+", where "X" is a number 0 through 9 and
// 		// + indicates "X" once or more times)
// 		var float_regex = /^([0-9]{1,2}|[0-9]{0,2}\.[0-9]{1,})$/;
// 		var float_match = float_regex.test( rarity_val );
// 		if ( false === float_match ) {
// 			invalid_el_ids.push( id );
// 			return true;
// 		}

// 		// round the number to the hundredths place
// 		// check for the input being within the field's min and max values
// 		var rarity_float = parseFloat( rarity_val );
// 		var rounded_rarity = Math.round10( rarity_float, -2 );
// 		if ( rounded_rarity < range_min && rounded_rarity > range_max ) {
// 			invalid_el_ids.push( id );
// 			return true;
// 		} else {
// 			jQuery( '#' + id ).val( rounded_rarity );
// 		}
// 	});

// 	// validation failed, throw an error
// 	if ( invalid_el_ids.length > 0 ) {
// 		var error = new Error(
// 			'In the rarity field, only decimals (to the hundredths place) from ' +
// 			range_min_str +
// 			' to ' +
// 			range_max_str +
// 			' are allowed.'
// 		);

// 		// will jump to the first failing element
// 		error.name = 'Game On Error';
// 		error.el_ids = invalid_el_ids;
// 		error.accord_id = '#' + accordion_id;
// 		throw error;
// 	}
// }