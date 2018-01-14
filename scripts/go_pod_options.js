( function() {
	jQuery( document ).ready( function() {
		jQuery( '#go_pod_form' ).submit( function( event ) {
			if ( 'undefined' === typeof( event.looped ) ) {
				event.preventDefault();

				// set the hidden pod inputs correctly
				update_previous_pod_inputs();

				// call the submit event again, but let the default action run
				jQuery( '#go_pod_form' ).trigger({ type: 'submit', looped: true });
			}
		});
	});

	var update_previous_pod_inputs = function() {
		var target_pod_slug, current_pod_slug;
		jQuery( '.go_pod_list_item' ).each( function( index, elem ) {
			target_pod_slug = jQuery( elem ).find( '.go_next_pod_select option:selected' ).attr( 'slug' );
			current_pod_slug = jQuery( elem ).find( '.go_pod_current_pod_slug' ).val();
			if ( 'undefined' != typeof( target_pod_slug ) && '...' != target_pod_slug ) {

				// Set the target's previous slug to the current pod's slug.
				// This allows the pods to check if their parent has been completed.
				jQuery( '#go_pod_span_' + target_pod_slug ).find( '.go_pod_previous_pod_slug' ).val( current_pod_slug );
			}
		});
	};
} )();