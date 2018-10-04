//Add an on click to all store items
jQuery(document).ready(function(){
    jQuery('.go_str_item').one("click", function(e){
        go_lb_opener( this.id );
    });
});

// Makes it so you can press return and enter content in a field
function go_make_store_clickable() {
    //Make URL button clickable by clicking enter when field is in focus
    jQuery('.clickable').keyup(function(ev) {
        // 13 is ENTER
        if (ev.which === 13) {
            jQuery("#go_store_pass_button").click();
        }
    });
}

//open the lightbox for the store items
function go_lb_opener( id ) {
    jQuery( '#light' ).css( 'display', 'block' );
    jQuery('.go_str_item').prop('onclick',null).off('click');
    if ( jQuery( '#go_stats_page_black_bg' ).css( 'display' ) == 'none' ) {
        jQuery( '#fade' ).css( 'display', 'block' );
    }
    if ( ! jQuery.trim( jQuery( '#lb-content' ).html() ).length ) {
        var get_id = id;
        var nonce = GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax;
        var gotoSend = {
            action:"go_the_lb_ajax",
            _ajax_nonce: nonce,
            the_item_id: get_id,
        };
        jQuery.ajax({
            url: MyAjax.ajaxurl,
            type:'POST',
            data: gotoSend,
            beforeSend: function() {
                jQuery( "#lb-content" ).append( '<div class="go-lb-loading"></div>' );
            },
            cache: false,
            success: function( raw) {
                //console.log('success');
                //console.log(raw);
                var res = JSON.parse( raw );

                try {
                    var res = JSON.parse( raw );
                } catch (e) {
                    res = {
                        json_status: '101',
                        html: ''
                    };
                }
                //console.log('html');
                //console.log(res.html);
                //console.log(res.json_status);

                jQuery( "#lb-content" ).innerHTML = "";
                jQuery( "#lb-content" ).html( '' );

                //jQuery( "#lb-content" ).append(results);
                //jQuery('.featherlight-content').html(res.html);
                jQuery.featherlight(res.html, {variant: 'store'});



                //console.log('success');
                //console.log(raw);



                if ( '101' === Number.parseInt( res.json_status ) ) {
                    console.log (101);
                    jQuery( '#go_store_error_msg' ).show();
                    var error = "Server Error.";
                    if ( jQuery( '#go_store_error_msg' ).text() != error ) {
                        jQuery( '#go_store_error_msg' ).text( error );
                    } else {
                        flash_error_msg_store( '#go_store_error_msg' );
                    }
                } else if ( 302 === Number.parseInt( res.json_status ) ) {
                    console.log (302);
                    window.location = res.location;

                }
                jQuery('.go_str_item').one("click", function(e){
                    go_lb_opener( this.id );
                });

                jQuery('#go_store_pass_button').one("click", function (e) {
                    go_store_password(id);
                });

                go_max_purchase_limit();

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
			action: 'go_buy_item',
			the_id: id,
			qty: jQuery( '#go_qty' ).val(),
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

function flash_error_msg_store( elem ) {
    var bg_color = jQuery( elem ).css( 'background-color' );
    if ( typeof bg_color === undefined ) {
        bg_color = "white";
    }
    jQuery( elem ).animate({
        color: bg_color
    }, 200, function() {
        jQuery( elem ).animate({
            color: "red"
        }, 200 );
    });
}

function go_store_password( id ){
    //console.log('button clicked');
    //disable button to prevent double clicks
    //go_enable_loading( target );
    var pass_entered = jQuery('#go_store_password_result').attr('value').length > 0 ? true : false;
    if (!pass_entered) {
        jQuery('#go_store_error_msg').show();
        var error = "Please enter a password.";
        if (jQuery('#go_store_error_msg').text() != error) {
            jQuery('#go_store_error_msg').text(error);
        } else {
            flash_error_msg_store('#go_store_error_msg');
        }
        jQuery('#go_store_pass_button').one("click", function (e) {
            go_store_password(id);
        });
        return;
    }
    var result = jQuery( '#go_store_password_result' ).attr( 'value' );

    jQuery( '#light' ).css( 'display', 'block' );
    if ( jQuery( '#go_stats_page_black_bg' ).css( 'display' ) == 'none' ) {
        jQuery( '#fade' ).css( 'display', 'block' );
    }
    if ( ! jQuery.trim( jQuery( '#lb-content' ).html() ).length ) {
        var get_id = id;
        var nonce = GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax;
        var gotoSend = {
            action:"go_the_lb_ajax",
            _ajax_nonce: nonce,
            the_item_id: get_id,
            skip_locks: true,
            result: result
        };

        jQuery.ajax({

            url: MyAjax.ajaxurl,
            type:'POST',
            data: gotoSend,
            cache: false,
            success: function( raw) {
                    //console.log('success');
                    //console.log(raw);
                    var res = JSON.parse( raw );

                    try {
                        var res = JSON.parse( raw );
                    } catch (e) {
                        res = {
                            json_status: '101',
                            html: ''
                        };
                    }

                    //console.log('html');
                    //console.log(res.html);
                    //console.log(res.json_status);
                    //alert(res.json_status);
                    if ( '101' === Number.parseInt( res.json_status ) ) {
                        console.log (101);
                        jQuery( '#go_store_error_msg' ).show();
                        var error = "Server Error.";
                        if ( jQuery( '#go_store_error_msg' ).text() != error ) {
                            jQuery( '#go_store_error_msg' ).text( error );
                        } else {
                            flash_error_msg_store( '#go_store_error_msg' );
                        }
                    } else if ( 302 === Number.parseInt( res.json_status ) ) {
                        console.log (302);
                        window.location = res.location;

                    }else if ( 'bad_password' ==  res.json_status ) {
                        //console.log("bad");
                        jQuery( '#go_store_error_msg' ).show();
                        var error = "Invalid password.";
                        if ( jQuery( '#go_store_error_msg' ).text() != error ) {
                            jQuery( '#go_store_error_msg' ).text( error );
                        } else {
                            flash_error_msg_store( '#go_store_error_msg' );
                        }
                        jQuery('#go_store_pass_button').one("click", function (e) {
                            go_store_password(id);
                        });
                    }else {
                        //console.log("good");
                        jQuery('#go_store_pass_button').one("click", function (e) {
                            go_store_password(id);
                        });
                        jQuery('#go_store_lightbox_container').hide();
                        jQuery('.featherlight-content').html(res.html);
                        go_max_purchase_limit();


                    }
            }
        });
    }
}

function go_max_purchase_limit(){
    window.go_purchase_limit = jQuery( '#golb-fr-purchase-limit' ).attr( 'val' );

    var spinner_max_size = go_purchase_limit;

    jQuery( '#go_qty' ).spinner({
        max: spinner_max_size,
        min: 1,
        stop: function() {
            jQuery( this ).change();
        }
    });
    go_make_store_clickable();
    //jQuery('#go_store_admin_override').click( function () {
    //    jQuery('.go_store_lock').show();
    //});
    jQuery('#go_store_admin_override').one("click", function (e) {
        //console.log("override");
        jQuery('.go_store_lock').show();
        jQuery('#go_store_admin_override').hide();
        go_make_store_clickable();

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



