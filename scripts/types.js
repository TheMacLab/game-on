

jQuery( document ).ready(function() {

		go_prepare_sortable_term_list();

});

function go_prepare_sortable_term_list () {
			jQuery( '.go_task_chain_order_list' ).sortable({
				axis: "y",
				start: function( event, ui ) {
					jQuery( ui.item ).addClass( 'go_sortable_item' );
				},
				stop: function( event, ui ) {
					
					jQuery( ui.item ).removeClass( 'go_sortable_item' );

					var chain_list = jQuery( ui.item ).parent()[0];
					
					var order = [];
					var task_id_array = go_get_term_ids( chain_list );
					
					var order_str = '';

					for ( var i = 0; i < task_id_array.length; i++ ) {
						if ( 'undefined' !== typeof task_id_array[ i ] && '' !== task_id_array[ i ] ) {
							order.push( task_id_array[ i ] );
							
						}
					}

					order_str = order.join( ',' );
					console.log(order_str);

					jQuery( chain_list ).siblings( '.go_store_order_hidden' ).val( order_str );
				}
			});
}

/**
 * Returns the task IDs in the sortable chain list on the current task edit page.
 *
 * @since 3.0.0
 *
 * @return array A list of task IDs from the sortable chain list on the task edit page.
 */
function go_get_term_ids( chain_list_el ) {
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