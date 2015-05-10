function apply_presets () {
	var points = jQuery( '#go_presets option:selected' ).attr( 'points' ).split( ',' );
	var currency = jQuery( '#go_presets option:selected' ).attr( 'currency' ).split( ',' );
	jQuery( '.go_reward_points' ).each( function () {
		for ( i = 1; i <= 5; i++ ) {
			jQuery( '.go_reward_points_' + i ).val( points[ i-1 ] );
		}
	});
	jQuery( '.go_reward_currency' ).each( function () {
		for( i = 1; i <= 5; i++ ){
			jQuery( '.go_reward_currency_' + i).val( currency[ i-1 ] );
		}
	});
}