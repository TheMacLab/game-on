function goCattheItem( the_cat_id ) {
	jQuery( document ).ready( function( jQuery ) {
		var gotoCat = {
			action:'cat_item',
			nonce: '',
		};
		jQuery.ajax({
			url: cat_item.ajaxurl,
			type: 'POST',
			data: gotoCat,
			beforeSend: function () { 
				jQuery( '#golb-fr-cat' ).append( '<div id="go-cat-loading" class="cat_loader"></div>' );
			},
			dataType: 'html',
			success: function ( response ) {
				jQuery( '#golb-fr-cat' ).innerHTML = '';
				jQuery( '#golb-fr-cat' ).html('');  
				jQuery( '#golb-fr-cat' ).append( '<span>' + response + '</span>' );
			}
		});
	});
}
