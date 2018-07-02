/*
 * go_store_admin.js
 *
 * Where all the functionality for the store item edit page goes. This and the task edit page script
 * are separated in order to prevent overlap and repeated behavior for similar setting events.
 */

function go_badge_filter_on_load() {

	// Refers to the class of the meta box field row (excluding the metabox field title).
	// This reference is explicitly for the only one badge filter field, if more are ever
	// added, this class will have to be changed.
	var row_class = '.cmb-type-go_badge_input.cmb_id_go_mta_badge_filter';

	var badge_checkbox = jQuery( row_class + ' .go_badge_input_toggle' );
	var add_buttons    = jQuery( row_class + ' .go_badge_input_add' );
	var del_buttons    = jQuery( row_class + ' .go_badge_input_del' );

	if ( 1 === badge_checkbox.length ) {
		badge_checkbox.change( go_badge_filter_checkbox_on_change );
	}

	if ( add_buttons.length >= 1 ) {
		add_buttons.click( go_badge_filter_add_field );
	}

	if ( del_buttons.length >= 1 ) {
		if ( 1 == del_buttons.length ) {
			del_buttons.hide();
		}
		del_buttons.click( go_badge_filter_del_field );
	}
}

function go_badge_filter_on_toggle() {

	// Refers to the class of the meta box field row (excluding the metabox field title).
	// This reference is explicitly for the only one badge filter field, if more are ever
	// added, this class will have to be changed.
	var row_class = '.cmb-type-go_badge_input.cmb_id_go_mta_badge_filter';

	var badge_checkbox = jQuery( row_class + ' .go_badge_input_toggle' );
	var badge_list     = jQuery( row_class + ' .go_stage_badge_container' );

	badge_checkbox.show();

	var is_checked = false;
	if ( 1 === badge_checkbox.length && badge_checkbox.is( ':checked' ) ) {
		is_checked = true;
	}

	jQuery( row_class ).show();
	if ( ! is_checked ) {
		jQuery( badge_list ).hide();
	} else {
		jQuery( badge_list ).show();
	}
}

function go_badge_filter_checkbox_on_change( event ) {
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

function go_badge_filter_add_field( event ) {
	var add_button       = event.target;
	var del_button       = jQuery( add_button ).siblings( '.go_badge_input_del' ).eq( 0 );
	var badge_input      = jQuery( add_button ).siblings( '.go_badge_input' )[0];
	var badge_list       = jQuery( add_button ).parents().eq( 1 );
	var badge_item       = jQuery( add_button ).parents().eq( 0 );
	var new_input_attrs  = [ 'type', 'name', 'class', 'stage', 'placeholder' ];
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
	jQuery( new_add_button ).click( go_badge_filter_add_field );

	// gives the new delete button all the old delete button's attributes
	if ( del_button.length > 0 ) {
		for ( var y = 0; y < new_button_attrs.length; y++ ) {
			old_val = jQuery( del_button ).attr( new_button_attrs[ y ] );
			jQuery( new_del_button ).attr( new_button_attrs[ y ], old_val );
		}

		// attaches a click event listener to the new delete button
		jQuery( new_del_button ).click( go_badge_filter_del_field );
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

function go_badge_filter_del_field( event ) {
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

jQuery(document).ready( function() {
	if ( jQuery( '#go_store_limit_checkbox' ).prop( 'checked' ) ) {
		jQuery( '#go_store_limit_input' ).show( 'slow' );
	} else {
		jQuery( '#go_store_limit_input' ).hide( 'slow' );
	}
	jQuery( '#go_store_limit_checkbox' ).click( function() {
		if ( jQuery( '#go_store_limit_checkbox' ).prop( 'checked' ) ) {
			jQuery( '#go_store_limit_input' ).show( 'slow' );
		} else {
			jQuery( '#go_store_limit_input' ).hide( 'slow' );
		}
	});

	if ( jQuery( '#go_store_filter_checkbox' ).prop( 'checked' ) ) {
		jQuery( '.go_store_filter_input' ).show( 'slow' );
	} else {
		jQuery( '.go_store_filter_input' ).hide( 'slow' );
	}
	jQuery( '#go_store_filter_checkbox' ).click( function() {
		if ( jQuery( '#go_store_filter_checkbox' ).prop( 'checked' ) ) {
			jQuery( '.go_store_filter_input' ).show( 'slow' );
		} else {
			jQuery( '.go_store_filter_input' ).hide( 'slow' );
		}
	});

	// handles events for the badge filter field
	go_badge_filter_on_load();
	jQuery( '.go_badge_input_toggle' ).toggle( go_badge_filter_on_toggle );

	if ( jQuery( '#go_store_gift_checkbox' ).prop( 'checked' ) ) {
		jQuery( '.go_store_gift_input' ).show( 'slow' );
	} else {
		jQuery( '.go_store_gift_input' ).hide( 'slow' );
	}
	jQuery( '#go_store_gift_checkbox' ).click( function() {
		if ( jQuery( '#go_store_gift_checkbox' ).prop( 'checked' ) ) {
			jQuery( '.go_store_gift_input' ).show( 'slow' );
		} else {
			jQuery( '.go_store_gift_input' ).hide( 'slow' );
		}
	});

	if ( jQuery( '#go_store_focus_checkbox' ).prop( 'checked' ) ) {
		jQuery( '#go_store_focus_select' ).show( 'slow' );
	} else {
		jQuery( '#go_store_focus_select' ).hide( 'slow' );
	}
	jQuery( '#go_store_focus_checkbox' ).click( function() {
		if ( jQuery( '#go_store_focus_checkbox' ).prop( 'checked' ) ) {
			jQuery( '#go_store_focus_select' ).show( 'slow' );
		} else {
			jQuery( '#go_store_focus_select' ).hide( 'slow' );
		}
	});
});
