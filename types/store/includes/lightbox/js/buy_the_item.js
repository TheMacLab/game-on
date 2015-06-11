function goBuytheItem( id, buyColor, count ) {
	jQuery( document ).ready( function( jQuery ) {
		var gotoBuy = {
			action: 'buy_item',
			nonce: '',
			the_id: id,
			qty: jQuery( '#go_qty' ).val(),
			recipient: jQuery( '#go_recipient' ).val(),
			purchase_count: count
		};

		// Whenever you figure out a better way to do this, implement it. 
		var color = jQuery( '#go_admin_bar_progress_bar' ).css( 'background-color' );
		jQuery.ajax({
			url: buy_item.ajaxurl,
			type: 'POST',
			data: gotoBuy,
			beforeSend: function() {
				jQuery( '#golb-fr-buy' ).innerHTML = '';
				jQuery( '#golb-fr-buy' ).html( '' );
				jQuery( '#golb-fr-buy' ).append( '<div id="go-buy-loading" class="buy_' + buyColor + '"></div>' );
			},
			dataType: 'html',
			success: function( response ) {
				var buy = jQuery( '#golb-fr-buy' );
				if ( response.indexOf( 'Need more' ) != -1 || response.indexOf( 'You\'ve attempted to purchase' ) != -1 ) {
					alert( response );
					buy.html( 'Error' );
				} else {
					buy.innerHTML = '';
					go_sounds( 'store' );
					// This checks for the existance of a <script> block in the "response" variable.
					// The index is used to split the "response" message, into a string for notifications,
					// and one for everything else.
					var script_index = response.lastIndexOf( '</script>' );
					if ( script_index != -1 ) {
						var go_notifications = response.slice( 0, script_index + 9 );
						var parsed_res = response.slice( script_index + 9 );
						jQuery( 'body' ).append( go_notifications );
						if ( parsed_res.indexOf( 'Purchased' ) != -1 ) {
							buy.html( parsed_res );
						} else if ( parsed_res.indexOf( 'Link' ) != -1 ) {
							buy.html( '<span>' + parsed_res + '</span>' );
						}
					} else {
						buy.html( response );
					}

					// Whenever you figure out a better way to do this, implement it. 
					jQuery( '#go_admin_bar_progress_bar' ).css( "background-color", color );
				}
				go_count_item( id );
				// console.log(response);
			}
		});
	});
}

function go_count_item( id ) {
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		type: 'POST',
		data: {
			action: 'go_get_purchase_count',
			the_item_id: id
		},
		success: function( data ) {
			var count = data.toString();
			jQuery( '#golb-purchased' ).html( 'Quantity purchased: ' + count );
		}
	});
}
