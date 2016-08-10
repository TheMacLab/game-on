/*
 * go_tasks_chains.js
 *
 * Where all the functionality for the task chains in the task edit page goes.
 */

/**
 * Prepares the task chain list in the task edit page for use by an admin. Enables the sorting 
 * functionality of the list.
 * 
 * @since 2.6.1
 *
 * @see go_chain_get_task_ids()
 * @global array GO_TASK_DATA Contains data pertaining to the current task and the chain it's in.
 */
function go_prepare_sortable_list () {
	if ( 'undefined' !== typeof GO_TASK_DATA ) {
		var task_id = GO_TASK_DATA.task_id;
		var in_chain = GO_TASK_DATA.task_chains.in_chain;
	
		if ( null !== task_id && in_chain ) {
			jQuery( '.go_task_chain_order_list' ).sortable({
				axis: "y",
				start: function( event, ui ) {
					jQuery( ui.item ).addClass( 'go_sortable_item' );
				},
				stop: function( event, ui ) {
					jQuery( ui.item ).removeClass( 'go_sortable_item' );

					var chain_list = jQuery( ui.item ).parent()[0];
					var order = [];
					var task_id_array = go_chain_get_task_ids( chain_list );
					var order_str = '';

					for ( var i = 0; i < task_id_array.length; i++ ) {
						if ( 'undefined' !== typeof task_id_array[ i ] && '' !== task_id_array[ i ] ) {
							order.push( task_id_array[ i ] );
						}
					}

					order_str = order.join( ',' );

					jQuery( chain_list ).siblings( '.go_task_order_hidden' ).val( order_str );
				}
			});
		}
	}
}

/**
 * Returns the task IDs in the sortable chain list on the current task edit page.
 *
 * @since 2.6.1
 *
 * @return array A list of task IDs from the sortable chain list on the task edit page.
 */
function go_chain_get_task_ids( chain_list_el ) {
	var order = [];
	if ( 'undefined' !== typeof chain_list_el ) {
		jQuery( chain_list_el ).children( '.go_task_in_chain' ).each( function( i, el ) {
			var val = jQuery( this ).attr( 'post_id' );
			if ( 'string' === typeof val ) {
				val = parseInt( val );
			}
			order[ i ] = val;
		});
	}

	return order;
}