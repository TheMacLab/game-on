<?php
/////////////////////////////////////////////
//////////// Front-End Lightbox! ////////////
///////// Module by Vincent Astolfi /////////
/////////////////////////////////////////////
///////////// Special Thanks To /////////////
///////// http://www.ajaxload.info/ /////////
////////////// For Loading Icons ////////////
/////////////////////////////////////////////
//Includes
include ( 'buy-ajax.php' ); // Ajax run when buying something
// Main Lightbox Ajax Function
function go_the_lb_ajax() {
    check_ajax_referer( 'go_lb_ajax_referall', 'nonce' );
	global $wpdb;
	$table_name_go = "{$wpdb->prefix}go";
	$the_id = (int) $_POST['the_item_id'];
	$the_post = get_post( $the_id );
	$the_title = $the_post->post_title;
	$item_content = get_post_field( 'post_content', $the_id );
	$the_content = wpautop( $item_content );
	$custom_fields = get_post_custom( $the_id );

	$super_mod_enabled = false;
	if ( ! empty( $custom_fields['go_mta_store_super_modifier'][0] ) &&
			'on' == strtolower( $custom_fields['go_mta_store_super_modifier'][0] ) ) {
		$super_mod_enabled = true;
	}

	$currency_name       = go_return_options( 'go_currency_name' );
	$points_name         = go_return_options( 'go_points_name' );
	$bonus_currency_name = go_return_options( 'go_bonus_currency_name' );
	$penalty_name        = go_return_options( 'go_penalty_name' );
	$minutes_name        = go_return_options( 'go_minutes_name' );

	$user_id = get_current_user_id();
	$is_admin = go_user_is_admin( $user_id );
	$user_points = go_return_points( $user_id );
	$user_currency = go_return_currency( $user_id );
	$user_bonus_currency = go_return_bonus_currency( $user_id );
	$user_penalties = go_return_penalty( $user_id );
	$user_minutes = go_return_minutes( $user_id );
	$penalty = ( ! empty( $custom_fields['go_mta_debt_switch'][0] ) ? true : false );

	$store_cost = ( ! empty( $custom_fields['go_mta_store_cost'][0] ) ? unserialize( $custom_fields['go_mta_store_cost'][0] ) : null );
	if ( ! empty( $store_cost ) ) {

		$temp_cost = array( $store_cost[0], $store_cost[1] );
		if ( $super_mod_enabled ) {

			// the store cost values have to have their signs switched, due to the context of store
			// items.
			$modded_cost = go_return_multiplier( $user_id, -$temp_cost[0], -$temp_cost[1], $user_bonus_currency, $user_penalties, true );

			// the returned modded values have to have their signs switched back
			$temp_cost[0] = -$modded_cost[0];
			$temp_cost[1] = -$modded_cost[1];
		}

		$req_currency       = $temp_cost[0];
		$req_points         = $temp_cost[1];
		$req_bonus_currency = $store_cost[2];
		$req_penalty        = $store_cost[3];
		$req_minutes        = $store_cost[4];
	}

	$store_filter = ( ! empty( $custom_fields['go_mta_store_filter'][0] ) ? unserialize( $custom_fields['go_mta_store_filter'][0] ) : null );
	if ( ! empty( $store_filter ) ) {
		$is_filtered = ( ! empty( $store_filter[0] ) ? true : false );
		if ( $is_filtered ) {
			$bonus_filter   = ( ! empty( $store_filter[1] ) ? (int) $store_filter[1] : 0 );
			$penalty_filter = ( ! empty( $store_filter[2] ) ? (int) $store_filter[2] : 0 );
		}
	}

	$store_limit = ( ! empty( $custom_fields['go_mta_store_limit'][0] ) ? unserialize( $custom_fields['go_mta_store_limit'][0] ) : null );
	if ( ! empty( $store_limit ) ) {
		$is_limited = $store_limit[0];
		if ( $is_limited == 'true' ) {
			$purchase_limit = $store_limit[1];
		}
	}

	$purchase_count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT SUM( count ) 
			FROM {$table_name_go} 
			WHERE post_id = %d AND uid = %d 
			LIMIT 1",
			$the_id,
			$user_id
		)
	);
	$store_gift = ( ! empty( $custom_fields['go_mta_store_gift'][0] ) ? unserialize( $custom_fields['go_mta_store_gift'][0] ) : null );
	$is_giftable = false;
	if ( ! empty( $store_gift ) ) {
		$is_giftable = (bool) $store_gift[0];
	}
	$is_unpurchasable = ( ! empty( $custom_fields['go_mta_store_unpurchasable'][0] ) ? $custom_fields['go_mta_store_unpurchasable'][0] : '' );

	echo "<h2>{$the_title}</h2>";
	echo '<div id="go-lb-the-content">'.do_shortcode( $the_content ).'</div>';

	$output_currency = $req_currency;
	if ( 0 == $req_currency ) {
		$gold_color = 'n';
	} elseif ( $req_currency < 0 ) {
		$gold_color = 'g';
		$output_currency *= -1;
	} else {
		$gold_color = 'r';
	}

	$output_points = $req_points;
	if ( 0 == $req_points ) {
		$points_color = 'n';
	} elseif ( $req_points < 0 ) {
		$points_color = 'g';
		$output_points *= -1;
	} else {
		$points_color = 'r';
	}

	$output_bonus_currency = $req_bonus_currency;
	if ( 0 == $req_bonus_currency ) {
		$bonus_currency_color = 'n';
	} elseif ( $req_bonus_currency < 0 ) {
		$bonus_currency_color = 'g';
		$output_bonus_currency *= -1;
	} else {
		$bonus_currency_color = 'r';
	}

	$output_penalty = $req_penalty;
	if ( 0 == $req_penalty ) {
		$penalty_color = 'n';
	} elseif ( $req_penalty < 0 ) {
		$penalty_color = 'r';
		$output_penalty *= -1;
	} else {
		$penalty_color = 'g';
	}

	$output_minutes = $req_minutes;
	if ( 0 == $req_minutes ) {
		$minutes_color = 'n';
	} elseif ( $req_minutes < 0 ) {
		$minutes_color = 'g';
		$output_minutes *= -1;
	} else {
		$minutes_color = 'r';
	}

	if ( $gold_color == "g" && $points_color == "g" ) {
		$buy_color = "gold";
	} else { 
		$buy_color = "gold"; 
	}

	$passed_bonus_filter = true;
	$passed_penalty_filter = true;
	
	if ( $is_filtered && ! empty( $bonus_filter ) && $user_bonus_currency < $bonus_filter ) {
		$passed_bonus_filter = false;
	}

	if ( $is_filtered && ! empty( $penalty_filter ) && $user_penalties >= $penalty_filter ) {
		$passed_penalty_filter = false;
	}
		
	$user_focuses = array();
	
	// Get focus options associated with item
	$item_focus_array = ( ! empty( $custom_fields['go_mta_store_focus'][0] ) ? unserialize( $custom_fields['go_mta_store_focus'][0] ) : null );
	
	// Check if item actually has focus
	$is_focused = filter_var( $item_focus_array[0], FILTER_VALIDATE_BOOLEAN );
	if ( $is_focused ) {
		$item_focus = $item_focus_array[1];
	}
	
	// Check if user has a focus
	if ( get_user_meta( $user_id, 'go_focus', true ) != null ) {
		$user_focuses = (array) get_user_meta( $user_id, 'go_focus', true );
	}
	
	// Check if item is locked by focus
	$locked_by_focus = ( ! empty( $custom_fields['go_mta_store_focus_lock'][0] ) ? $custom_fields['go_mta_store_focus_lock'][0] : null );
	if ( ! empty( $locked_by_focus ) ) {
		$focus_category_lock = true;
	}
	
	// Grab which focuses are chosen as the locks
	if ( get_the_terms( $the_id, 'store_focus_categories' ) && $focus_category_lock ) {
		$categories = get_the_terms( $the_id, 'store_focus_categories' );
		$category_names = array();
		foreach ( $categories as $category ) {
			array_push( $category_names, $category->name );	
		}
	}
	
	// Check to see if the user has any of the focuses
	if ( ! empty( $category_names ) && $user_focuses ) {
		$go_ahead = array_intersect( $user_focuses, $category_names );
	}
	
	if ( $is_focused && ! empty( $item_focus ) && ! empty( $user_focuses ) && in_array( $item_focus, $user_focuses ) ) {
		die( 'You already have this '.go_return_options( 'go_focus_name' ).'!' );	
	}
	if ( empty( $go_ahead ) && ! empty( $focus_category_lock ) ) {
		die( 'Item only available to those in '.implode( ', ', $category_names ).' '.strtolower( go_return_options( 'go_focus_name' ) ) );
	}
	if ( ! $passed_bonus_filter || ! $passed_penalty_filter ) {
		$filter_error_str = '';

		if ( ! $passed_bonus_filter ) {
			$bonus_diff = $bonus_filter - $user_bonus_currency;
			$filter_error_str .= "You need {$bonus_diff} more ".go_return_options( 'go_bonus_currency_name' ).' to view this item.';
		}

		if ( ! $passed_penalty_filter ) {
			$penalty_diff = $user_penalties - $penalty_filter;
			if ( $penalty_diff > 0 ) {
				$filter_error_str .= "\nYou have {$penalty_diff} too many ".go_return_options( 'go_penalty_name' ).'.';
			} elseif ( 0 === $penalty_diff ) {
				$filter_error_str .= "\nYou need less than {$penalty_filter} ".go_return_options( 'go_penalty_name' ).' to view this item.';
			}
		}

		die( $filter_error_str );
	}
	if ( ! empty( $purchase_limit) && $purchase_count >= $purchase_limit ) {
		die( "You've reached the maximum purchase limit." );
	}

	// gets the user's current badges
	$user_badges = get_user_meta( $user_id, 'go_badges', true );
	if ( ! $user_badges ) {
		$user_badges = array();
	}

	// gets an array of badge IDs to prevent users who don't have the badges from viewing the item
	$badge_filter_meta = get_post_meta( $the_id, 'go_mta_badge_filter', true );

	// an array of badge IDs
	$badge_filter_ids = array();

	// determines if the user has the correct badges
	$badge_filtered = false;
	$badge_diff = array();
	if ( ! empty( $badge_filter_meta ) && isset( $badge_filter_meta[0] ) && $badge_filter_meta[0] ) {
		$badge_filter_ids = array_filter( (array) $badge_filter_meta[1], 'go_badge_exists' );

		// checks to see if the filter array are in the the user's badge array
		$intersection = array_values( array_intersect( $user_badges, $badge_filter_ids ) );

		// stores an array of the badges that were not found in the user's badge array
		$badge_diff = array_values( array_diff( $badge_filter_ids, $intersection ) );
		if ( ! empty( $badge_filter_ids ) && ! empty( $badge_diff ) ) {
			$badge_filtered = true;
		}
	}

	if ( ! $is_admin && $badge_filtered ) {
		$return_badge_list = true;

		// outputs all the badges that the user must obtain before viewing the store item
		printf(
			'You need the following badges to view this item:<br/>%s',
			go_badge_output_list( $badge_diff, $return_badge_list )
		);
		wp_die();
	}

	printf(
		'<div id="golb-fr-price" class="golb-fr-boxes-%s" req="%d" cur="%d">%s: %d</div>
		<div id="golb-fr-points" class="golb-fr-boxes-%s" req="%d" cur="%d">%s: %d</div>
		<div id="golb-fr-bonus_currency" class="golb-fr-boxes-%s" req="%d" cur="%d">%s: %d</div>
		<div id="golb-fr-penalty" class="golb-fr-boxes-%s" req="%d" cur="%d">%s: %d</div>
		<div id="golb-fr-minutes" class="golb-fr-boxes-%s" req="%d" cur="%d">%s: %d</div>',
		$gold_color, $req_currency, $user_currency, $currency_name, $output_currency,
		$points_color, $req_points, $user_points, $points_name, $output_points,
		$bonus_currency_color, $req_bonus_currency, $user_bonus_currency, $bonus_currency_name, $output_bonus_currency,
		$penalty_color, $req_penalty, $user_penalties, $penalty_name, $output_penalty,
		$minutes_color, $req_minutes, $user_minutes, $minutes_name, $output_minutes
	);
	?>
	<?php
	if ( $is_unpurchasable != 'on' ) {
		?>
		<div id="golb-fr-qty" class="golb-fr-boxes-n">Qty: <input id="go_qty" style="width: 40px;font-size: 11px; margin-right:0px; margin-top: 0px; bottom: 3px; position: relative;" value="1" disabled="disabled" /></div>
		<input type='hidden' class='golb-fr-boxes-debt' value='<?php echo ( $penalty ? 'true' : 'false' ); ?>' />
		<div id="golb-fr-buy" class="golb-fr-boxes-<?php echo $buy_color; ?>" onclick="goBuytheItem( '<?php echo $the_id; ?>', '<?php echo $buy_color; ?>', '<?php echo $purchase_count?>' ); this.removeAttribute( 'onclick' );">Buy</div>
		<div id="golb-fr-purchase-limit" val="<?php echo ( ! empty( $purchase_limit ) ? $purchase_limit : 0 ); ?>"><?php echo ( ! empty( $purchase_limit ) ? "Limit {$purchase_limit}" : 'No limit' ); ?></div>
		<div id="golb-purchased">
		<?
		if ( is_null( $purchase_count ) ) { 
			echo 'Quantity purchased: 0';
		} else {
			echo "Quantity purchased: {$purchase_count}";
		} 
	}
	if ( empty( $item_focus ) && ! $penalty && $is_giftable ) {
	?>
		<br />
		Gift this item <input type='checkbox' id='go_toggle_gift_fields'/>
		<div id="go_recipient_wrap" class="golb-fr-boxes-giftable">Gift To: <input id="go_recipient" type="text"/></div>
		<div id="go_search_results"></div>
		<script>
			var go_gift_check_box = jQuery( "#go_toggle_gift_fields" );
			var go_gift_text_box = jQuery( "#go_recipient_wrap" );
			go_gift_text_box.prop( "hidden", true );
			go_gift_check_box.click( function() {
				if ( jQuery( this ).is( ":checked" ) ) {
					go_gift_text_box.prop( "hidden", false );
				} else {
					go_gift_text_box.prop( "hidden", true );
					jQuery( '#go_search_results' ).hide();
					jQuery( "#go_recipient" ).val( '' );
				}
			});
		</script>
    
	<?php 
	}
	
	?>
	</div>
	<?php
	
    die();
}

////////////////////////////////////////////////////
function go_frontend_lightbox_html() {
	?>
	<script type="text/javascript">
	
	function go_lb_closer() {
		document.getElementById( 'light' ).style.display='none';
		document.getElementById( 'fade' ).style.display='none';
		document.getElementById( 'lb-content' ).innerHTML = '';
		jQuery( 'html' ).removeClass( 'go_no_scroll' );
	}
	
	function go_lb_opener( id ) {
		jQuery( '#light' ).css( 'display', 'block' );
		if ( jQuery( '#go_stats_page_black_bg' ).css( 'display' ) == 'none' ) {
			jQuery( '#fade' ).css( 'display', 'block' );
		}

		// this will stop the body from scrolling behind the lightbox
		jQuery( 'html' ).addClass( 'go_no_scroll' );
		if ( ! jQuery.trim( jQuery( '#lb-content' ).html() ).length ) {
			var get_id = id;
			var gotoSend = {
				action:"go_lb_ajax",
				nonce: "<?php echo esc_js( wp_create_nonce( 'go_lb_ajax_referall' ) ); ?>",
				the_item_id: get_id,
			};
			var url_action = "<?php echo admin_url( '/admin-ajax.php' ); ?>";
			jQuery.ajaxSetup({ cache: true });
			jQuery.ajax({
				url: url_action,
				type:'POST',
				data: gotoSend,
				beforeSend: function() {
					jQuery( "#lb-content" ).append( '<div class="go-lb-loading"></div>' );
				},
				cache: false,
				success: function( results, textStatus, XMLHttpRequest ) {  
					jQuery( "#lb-content" ).innerHTML = "";
					jQuery( "#lb-content" ).html( '' );  
					jQuery( "#lb-content" ).append(results);
					window.go_req_currency = jQuery( '#golb-fr-price' ).attr( 'req' );
					window.go_req_points = jQuery( '#golb-fr-points' ).attr( 'req' );
					window.go_req_bonus_currency = jQuery( '#golb-fr-bonus_currency' ).attr( 'req' );
					window.go_req_penalty = jQuery( '#golb-fr-penalty' ).attr( 'req' );
					window.go_req_minutes = jQuery( '#golb-fr-minutes' ).attr( 'req' );
					window.go_cur_currency = jQuery( '#golb-fr-price' ).attr( 'cur' );
					window.go_cur_points = jQuery( '#golb-fr-points' ).attr( 'cur' );
					window.go_cur_bonus_currency = jQuery( '#golb-fr-bonus_currency' ).attr( 'cur' );
					window.go_cur_minutes = jQuery( '#golb-fr-minutes' ).attr( 'cur' );
					window.go_purchase_limit = jQuery( '#golb-fr-purchase-limit' ).attr( 'val' );

					// `window.go_store_debt_enabled` was implemented as a temporary hotfix for
					// bugs in v2.6.1
					window.go_store_debt_enabled = (
						'true' === jQuery( '.golb-fr-boxes-debt' ).val() ?
						true : false
					);

					if ( go_purchase_limit == 0 ) {
						go_purchase_limit = <?php echo ( defined( PHP_INT_MAX ) ? PHP_INT_MAX : 9001 ); ?>;
					}

					// determines the upper limit of the purchase quantity spinner, which is limited
					// by the amount of currency that the user has and the cost of the Store Item
					var spinner_max_size = go_purchase_limit;

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

					jQuery( '#go_qty' ).spinner({
						max: spinner_max_size,
						min: 1,
						stop: function() {
							jQuery( this ).change();
						}
					});
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
					if ( jQuery( '.white_content' ).css( 'display' ) != 'none' ) {
						jQuery(document).keyup( function( e ) { 
							if ( e.keyCode == 27 ) { // If keypressed is escape, run this
								go_lb_closer();
							} 
						});
						jQuery( '.black_overlay' ).click( function() {
							go_lb_closer();
						});
					}
					var done_typing = 0;
					var typing_timer;
					var recipient = jQuery( '#go_recipient' );
					var search_res = jQuery( '#go_search_results' );
					recipient.keyup( function() {
						clearTimeout(typing_timer);
						if ( recipient.val().length != 0 ) {
							typing_timer = setTimeout( function() {
								go_search_for_user( recipient.val() );
							}, done_typing);
						} else {
							jQuery( '#go_search_results' ).hide();
						}
					});
					recipient.focus( function() {
						if ( search_res.is( ':hidden' ) ) {
							search_res.empty();
							search_res.show();	
						}
					});
				} 
			});
		}
	}
	
	function go_fill_recipient( el ) {
		var el = jQuery( el );
		var val = el.text();
		var recipient = jQuery( '#go_recipient' );
		recipient.val( val );
		el.parent().hide();
	}
	
	function go_close_this( el ) {
		jQuery( el ).parent().hide();	
	}
	
	function go_search_for_user( user ) {
		var url_action = "<?php echo admin_url( '/admin-ajax.php' ); ?>";
		jQuery.ajax({
			url: url_action,
			type: "POST",
			data: {
				_ajax_nonce: '<?php echo wp_create_nonce( 'go_search_for_user' ); ?>',
				action: 'go_search_for_user',
				user: user
			},
			success: function( data ) {
				var recipient = jQuery( '#go_recipient' );
				var search_res = jQuery( '#go_search_results' );
				var position = recipient.position();
				search_res.css({ 
					top: position.top + recipient.height() + 1 + "px", 
					left: position.left + "px", 
					width: recipient[0].getBoundingClientRect().width - 2 + "px"
				});
				search_res.html( data );
			}
		});
	}
	
	</script>
		<div id="light" class="white_content">
			<a href="javascript:void(0)" onclick="go_lb_closer();" class="go_lb_closer">Close</a>
			<div id="lb-content"></div>
		</div>
		<div id="fade" class="black_overlay"></div>
	<?php
}

function go_search_for_user() {
	global $wpdb;
	if ( empty( $_POST['user'] ) ) {
		die();
	}
	$display_name = sanitize_text_field( $_POST['user'] );
	$display_name_array = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT display_name 
			FROM {$wpdb->users} 
			WHERE display_name LIKE %s 
			LIMIT 0, 4",
			"%{$display_name}%"
		)
	);
	if ( ! empty( $display_name_array ) ) {
		foreach ( $display_name_array as $name ) {
			echo '<a href="javascript:;" class="go_search_res_user" onclick="go_fill_recipient( this )">'.$name->display_name."</a><br/>";
		}
	} else {
		echo '<a href="javascript:;" class="go_search_res_user" onclick="go_close_this( this )">No users found</a>';	
	}
	die();	
}

function go_get_purchase_count() {
	global $wpdb;
	$table_name_go = $wpdb->prefix."go";
	$the_id = ( ! empty( $_POST['item_id'] ) ? (int) $_POST['item_id'] : 0 );

	if ( empty( $the_id ) ) {
		die( '0' );
	}

	$user_id = get_current_user_id();
	check_ajax_referer( 'go_get_purchase_count_' . $user_id );

	$purchase_count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT SUM( count ) 
			FROM {$table_name_go} 
			WHERE post_id = %d AND uid = %d 
			LIMIT 1",
			$the_id,
			$user_id
		)
	);

	if ( empty( $purchase_count ) ) {
		echo '0';
	} else {
		echo $purchase_count;
	}
	die();
}
?>