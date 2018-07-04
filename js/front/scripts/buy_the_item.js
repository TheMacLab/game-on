//Add an on click to all store items
jQuery(document).ready(function(){
    jQuery('.go_str_item').one("click", function(e){
        go_lb_opener( this.id );
    });
});

//open the lightbox for the store items
function go_lb_opener( id ) {
    jQuery( '#light' ).css( 'display', 'block' );
    jQuery('.go_str_item').prop('onclick',null).off('click');
    if ( jQuery( '#go_stats_page_black_bg' ).css( 'display' ) == 'none' ) {
        jQuery( '#fade' ).css( 'display', 'block' );
    }

    // this will stop the body from scrolling behind the lightbox
    //jQuery( 'html' ).addClass( 'go_no_scroll' );
    if ( ! jQuery.trim( jQuery( '#lb-content' ).html() ).length ) {
        var get_id = id;
        var nonce = GO_EVERY_PAGE_DATA.nonces.go_lb_ajax_referall;
        var gotoSend = {
            action:"go_lb_ajax",
            //nonce: "<?php echo esc_js( wp_create_nonce( 'go_lb_ajax_referall' ) ); ?>",
            nonce: nonce,
            the_item_id: get_id,
        };
        var url_action = "<?php echo admin_url( '/admin-ajax.php' ); ?>";
        //jQuery.ajaxSetup({ cache: true });
        jQuery.ajax({
            //url: url_action,
            url: MyAjax.ajaxurl,
            type:'POST',
            data: gotoSend,
            beforeSend: function() {
                jQuery( "#lb-content" ).append( '<div class="go-lb-loading"></div>' );
            },
            cache: false,
            success: function( results, textStatus, XMLHttpRequest ) {
                jQuery( "#lb-content" ).innerHTML = "";
                jQuery( "#lb-content" ).html( '' );
                //jQuery( "#lb-content" ).append(results);
                jQuery.featherlight(results);
                jQuery('.go_str_item').one("click", function(e){
                    go_lb_opener( this.id );
                });
                //window.go_req_currency = jQuery( '#golb-fr-price' ).attr( 'req' );
                //window.go_req_points = jQuery( '#golb-fr-points' ).attr( 'req' );
                //window.go_req_bonus_currency = jQuery( '#golb-fr-bonus_currency' ).attr( 'req' );
                //window.go_req_penalty = jQuery( '#golb-fr-penalty' ).attr( 'req' );
                //window.go_req_minutes = jQuery( '#golb-fr-minutes' ).attr( 'req' );
                //window.go_cur_currency = jQuery( '#golb-fr-price' ).attr( 'cur' );
                //window.go_cur_points = jQuery( '#golb-fr-points' ).attr( 'cur' );
                //window.go_cur_bonus_currency = jQuery( '#golb-fr-bonus_currency' ).attr( 'cur' );
                //window.go_cur_minutes = jQuery( '#golb-fr-minutes' ).attr( 'cur' );
                window.go_purchase_limit = jQuery( '#golb-fr-purchase-limit' ).attr( 'val' );

                // `window.go_store_debt_enabled` was implemented as a temporary hotfix for
                // bugs in v2.6.1
                window.go_store_debt_enabled = (
                    'true' === jQuery( '.golb-fr-boxes-debt' ).val() ?
                        true : false
                );

                //if ( go_purchase_limit == 0 ) {go_purchase_limit = 9999;}

                // determines the upper limit of the purchase quantity spinner, which is limited
                // by the amount of currency that the user has and the cost of the Store Item
                var spinner_max_size = go_purchase_limit;

                /*
                if ( ! go_store_debt_enabled ) {

                    var point_cost_ratio = go_purchase_limit;
                    var currency_cost_ratio = go_purchase_limit;
                    if ( go_req_points > 0 ) {
                        point_cost_ratio = Math.floor( go_cur_points / go_req_points );
                    }
                    if ( go_req_currency > 0 ) {
                        currency_cost_ratio = Math.floor( go_cur_currency / go_req_currency );
                    }

                    if ( point_cost_ratio < 1 || currency_cost_ratio < 1 ) {
                        spinner_max_size = 1;
                    } else {
                        spinner_max_size = Math.min( point_cost_ratio, currency_cost_ratio, spinner_max_size );
                    }
                }
                */

                jQuery( '#go_qty' ).spinner({
                    max: spinner_max_size,
                    min: 1,
                    stop: function() {
                        jQuery( this ).change();
                    }
                });
                /*
                jQuery( '#go_qty' ).change( function() {

                    // updates gold value
                    var price_raw = jQuery( '#golb-fr-price' ).html();
                    var price_sub = price_raw.substr( price_raw.indexOf( ":" ) + 2 );
                    if ( price_sub.length > 0 ) {
                        var price = price_raw.replace( price_sub, Math.abs( go_req_currency ) * jQuery( this ).val() );
                        jQuery( '#golb-fr-price' ).html( price );
                    }

                    // updates XP value
                    var points_raw = jQuery( '#golb-fr-points' ).html();
                    var points_sub = points_raw.substr( points_raw.indexOf( ":" ) + 2 );
                    if ( points_sub.length > 0 ) {
                        var points = points_raw.replace( points_sub, Math.abs( go_req_points ) * jQuery( this ).val() );
                        jQuery( '#golb-fr-points' ).html( points );
                    }

                    // updates honor value
                    var bonus_currency_raw = jQuery( '#golb-fr-bonus_currency' ).html();
                    var bonus_currency_sub = bonus_currency_raw.substr( bonus_currency_raw.indexOf( ":" ) + 2 );
                    if ( bonus_currency_sub.length > 0 ) {
                        var bonus_currency = bonus_currency_raw.replace( bonus_currency_sub, Math.abs( go_req_bonus_currency ) * jQuery( this ).val() );
                        jQuery( '#golb-fr-bonus_currency' ).html( bonus_currency );
                    }

                    // updates penalty value
                    var penalty_raw = jQuery( '#golb-fr-penalty' ).html();
                    var penalty_sub = penalty_raw.substr( penalty_raw.indexOf( ":" ) + 2 );
                    if ( penalty_sub.length > 0 ) {
                        var penalty = penalty_raw.replace( penalty_sub, Math.abs( go_req_penalty ) * jQuery( this ).val() );
                        jQuery( '#golb-fr-penalty' ).html( penalty );
                    }

                    // update minutes value
                    var minutes_raw = jQuery( '#golb-fr-minutes' ).html();
                    var minutes_sub = minutes_raw.substr( minutes_raw.indexOf( ":" ) + 2 );
                    if ( minutes_sub.length > 0 ) {
                        var minutes = minutes_raw.replace( minutes_sub, Math.abs( go_req_minutes ) * jQuery( this ).val() );
                        jQuery( '#golb-fr-minutes' ).html( minutes );
                    }
                });
                */

            }
        });
    }
}

//called when the "buy" button is clicked.
function goBuytheItem( id, count ) {

	var nonce = GO_BUY_ITEM_DATA.nonces.go_buy_item;
	var user_id = GO_BUY_ITEM_DATA.userID;
	console.log(user_id);
	jQuery( document ).ready( function( jQuery ) {
		var gotoBuy = {
			_ajax_nonce: nonce,
			action: 'go_buy_item2',
			the_id: id,
			qty: jQuery( '#go_qty' ).val(),
			//recipient: jQuery( '#go_recipient' ).val(),
			//purchase_count: count,
            user_id: user_id,
		};


		jQuery.ajax({
			url: MyAjax.ajaxurl,
			type: 'POST',
			data: gotoBuy,
			beforeSend: function() {
				jQuery( '#golb-fr-buy' ).innerHTML = '';
				jQuery( '#golb-fr-buy' ).html( '' );
				jQuery( '#golb-fr-buy' ).append( '<div id="go-buy-loading" class="buy_gold"></div>' );
			},
			success: function( raw ) {
				//console.log("SUccess: " + raw);
                var res = {};
                try {
                    var res = JSON.parse( raw );
                } catch (e) {
                    res = {
                        json_status : '101',
                        html : '101 Error: Please try again.'
                    };
                }
				if ( -1 !== raw.indexOf( 'Error' ) ) {
					jQuery( '#light').html(raw);
				} else {
					//go_sounds( 'store' );
                    jQuery( '#light').html(res.html);
				}
			}
		});
	});
}

//Not sure if this is still used
function go_count_item( item_id ) {
	var nonce = GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		type: 'POST',
		data: {
			_ajax_nonce: nonce,
			action: 'go_get_purchase_count',
			item_id: item_id
		},
		success: function( res ) {
			if ( -1 !== res ) {
				var count = res.toString();
				jQuery( '#golb-purchased' ).html( 'Quantity purchased: ' + count );
			}
		}
	});
}



