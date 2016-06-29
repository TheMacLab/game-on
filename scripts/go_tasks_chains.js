/*
 * go_tasks_chains.js
 *
 * Where all the functionality for the task chains in the task edit page goes.
 */

/**
 * Prepares the task chain list in the task edit page for use by an admin. Enables the sorting 
 * functionality of the list.
 *
 * Listens for user reordering sortable list. Then it handles requesting the 
 * `wp_ajax_go_update_task_order` hook to update the task chain's order in the task's meta data.
 *
 * @see go_chain_get_task_ids()
 * @global array GO_TASK_DATA Contains data pertaining to the current task and the chain it's in.
 * 
 * @since 2.6.1
 */
function go_prepare_sortable_list () {
	if ( 'undefined' !== typeof GO_TASK_DATA ) {
		var task_id = GO_TASK_DATA.task_id;
		var in_chain = GO_TASK_DATA.task_chains.in_chain;
		var chain_name = GO_TASK_DATA.task_chains.chain_name;
		var nonce = GO_TASK_DATA.nonces.go_update_task_order;
	
		if ( null !== task_id && in_chain ) {
			var order = [];
	
			jQuery( '#go_task_order_in_chain' ).sortable({
				axis: "y",
				start: function( event, ui ) {
					jQuery( ui.item ).addClass( 'go_sortable_item' );
				},
				stop: function( event, ui ) {
					jQuery( ui.item ).removeClass( 'go_sortable_item' );
					order = go_chain_get_task_ids();

					jQuery.ajax({
						url: MyAjax.ajaxurl,
						type: 'POST',
						data: {
							_ajax_nonce: nonce,
							action: 'go_update_task_order',
							order: order,
							chain_name: chain_name,
							post_id: task_id
						}
					});
				}
			});
		}
	}
}

/**
 * Updates the task chain list order before the task is published/updated.
 *
 * Updates the task chain list order in the task's meta data just before the task is
 * published/updated. This function doesn't send a request to the `wp_ajax_go_update_task_order` 
 * hook, instead it directly manipulates the value stored in the chain_position meta data field
 * "Custom Fields" accordion in the task edit page DOM.
 *
 * @see go_chain_get_task_ids()
 * @global array GO_TASK_DATA Contains data pertaining to the current task and the chain it's in.
 * 
 * @since 2.6.1
 */
function go_update_task_order_before_publish () {

	// Get the order of the task chain from the "Chain Order" meta box.
	var order = go_chain_get_task_ids();

	if ( order.length > 0 ) {

		// Get the position of this task in the chain and get the current value of the meta value "chain_position".
		// Compare them and update the meta value if they are not equal and the task id does exist in the chain.
		var n_position = order.indexOf( GO_TASK_DATA.task_id );
		var c_position = jQuery( "#the-list .left" ).children( "input[value='chain_position']" ).first().parent( 'td.left' ).siblings( "td" ).children( "textarea" ).text();
		if ( n_position !== c_position && -1 !== n_position ) {
			jQuery( "#the-list .left" ).children( "input[value='chain_position']" ).first().parent( 'td.left' ).siblings( "td" ).children( "textarea" ).text( n_position );
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
function go_chain_get_task_ids () {
	var order = [];
	jQuery( '.go_task_in_chain' ).each( function( i, el ) {
		order[ i + 1 ] = jQuery( this ).attr( 'post_id' );
	});

	return order;
}