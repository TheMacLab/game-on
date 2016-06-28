<?php

//adds currency and points for reasons that are not post tied.
function go_add_currency( $user_id, $reason, $status, $raw_points, $raw_currency, $update, $bonus_loot = false ) {
	global $wpdb;
	$table_name_go = "{$wpdb->prefix}go";

	$user_bonuses = go_return_bonus_currency( $user_id );
	$user_penalties = go_return_penalty( $user_id );
	$points = $raw_points;
	$currency = $raw_currency;
	if ( $status !== -1 ) {
		$modded_array = go_return_multiplier(
			$user_id,
			$raw_points,
			$raw_currency,
			$user_bonuses,
			$user_penalties
		);
		$points = $modded_array[0];
		$currency = $modded_array[1];
	}

	if ( $update == false ) {
		$wpdb->insert(
			$table_name_go,
			array(
				'uid' => $user_id,
				'reason' => $reason,
				'status' => $status,
				'points' => $points,
				'currency' => $currency
			)
		);
	} elseif ( $update == true ) {
		$wpdb->update(
			$table_name_go,
			array(
				'status' => $status,
				'points' => $points,
				'currency' => $currency
			),
			array(
				'uid' => $user_id,
				'reason' => $reason
			)
		);
	}
	go_update_totals( $user_id, $raw_points, $raw_currency, 0, 0, 0, $status, $bonus_loot );
}

// Adds currency and points for reasons that are post tied.
function go_add_post(
		$user_id, $post_id, $status, $points, $currency, $bonus_currency = null,
		$minutes = null, $page_id, $repeat = null, $count = null, $e_fail_count = null, $a_fail_count = null,
		$c_fail_count = null, $m_fail_count = null, $e_passed = null, $a_passed = null, $c_passed = null, $m_passed = null,
		$url = null, $update_time = false, $reason = null, $bonus_loot = false, $notify = true
	) {
	global $wpdb;
	$table_name_go = "{$wpdb->prefix}go";
	$time = date( 'm/d@H:i', current_time( 'timestamp', 0 ) );
	$user_bonuses = go_return_bonus_currency( $user_id );
	$user_penalties = go_return_penalty( $user_id );
	
	if ( $status === -1 ) {
		$qty = ( false === $bonus_loot ) ? $_POST['qty'] : 1;
		$old_points = $wpdb->get_row( "SELECT * FROM {$table_name_go} WHERE uid = {$user_id} and post_id = {$post_id} LIMIT 1" );
		$points *= $qty;
		$currency *= $qty;
		$bonus_currency *= $qty;
		$minutes *= $qty;
		$gifted = false;
		if ( get_current_user_id() != $user_id ) {
			$reason = 'Gifted';
			$gifted = true;
		}
	   	if ( $repeat != 'on' || empty( $old_points ) ) {
			$wpdb->insert(
				$table_name_go, 
				array(
					'uid' => $user_id, 
					'post_id' => $post_id, 
					'status' => -1, 
					'points' => $points,
					'currency' => $currency,
					'bonus_currency' => $bonus_currency,
					'page_id' => $page_id, 
					'count' => $qty + $count,
					'reason' => $reason,
					'timestamp' => $time,
					'gifted' => $gifted,
					'minutes' => $minutes
				)
			);
		} else {
			$wpdb->update(
				$table_name_go,
				array(
					'points' => $points + ( $old_points->points ),
					'currency' => $currency + ( $old_points->currency ),
					'bonus_currency' => $bonus_currency + ( $old_points->bonus_currency ),
					'minutes' => $minutes + ( $old_points->minutes ),
					'page_id' => $page_id,
					'count' => ( ( $old_points->count ) + $qty ),
					'reason' => $reason
				), 
				array(
					'uid' => $user_id, 
					'post_id' => $post_id
				)
			);
		}
	} else {
		$modded_array = go_return_multiplier( $user_id, $points, $currency, $user_bonuses, $user_penalties );
		$modded_points = $modded_array[0];
		$modded_currency = $modded_array[1];
		$old_points = $wpdb->get_row( "SELECT * FROM {$table_name_go} WHERE uid = {$user_id} AND post_id = {$post_id}" );
		if ( ! empty( $old_points ) ) {
			$old_url_array = unserialize( $old_points->url );
			$url_array = array();
			foreach ( $url_array as $key => $val ) {
				if ( ! empty( $val ) ) {
					$url_array[ $key ] = $val;
				}
			}
			$url_array[ $status ] = $url;
			$url_array = serialize( $url_array);
		} else {
			$url_array = serialize( array( $status => $url ) );
		}
		if ( $repeat === 'on' ) {
			$wpdb->update(
				$table_name_go,
				array(
					'status' => $status,
					'points' => $modded_points + ( $old_points->points ),
					'currency' => $modded_currency + ( $old_points->currency ),
					'bonus_currency' => $bonus_currency + ( $old_points->bonus_currency ),
					'page_id' => $page_id,
					'count' => $count + ( $old_points->count ),
					'url' => $url_array
				), 
				array( 
						'uid' => $user_id, 
						'post_id' => $post_id
				)
			);
		} else {
			if ( $status === 0 ) {
				$wpdb->insert( 
					$table_name_go, 
					array( 
						'uid' => $user_id, 
						'post_id' => $post_id, 
						'status' => 1, 
						'points' => $modded_points, 
						'currency' => $modded_currency, 
						'bonus_currency' => $bonus_currency, 
						'page_id' => $page_id
					)
				);
			} else {
				$columns = array(
					'points' => $modded_points + ( $old_points->points ), 
					'currency' => $modded_currency + ( $old_points->currency ), 
					'bonus_currency' => $bonus_currency + ( $old_points->bonus_currency ), 
					'page_id' => $page_id,
					'url' => $url_array
				);
				if ( ! is_null( $status ) ) {
					$timestamp = $wpdb->get_var( "SELECT `timestamp` FROM {$wpdb->prefix}go WHERE uid='{$user_id}' AND post_id='{$post_id}'" );
					if ( $update_time && empty( $timestamp ) ) {
						$columns['timestamp'] = $time;
					}
					$columns['status'] = $status;
				}
				$wpdb->update( $table_name_go, $columns, array( 'uid' => $user_id, 'post_id' => $post_id) );
			}
		}
		if ( $e_fail_count != null || $a_fail_count != null || $c_fail_count != null || $m_fail_count != null ) {
			$wpdb->update( 
				$table_name_go, 
				array( 
					'status' => $status, 
					'points' => $modded_points + ( $old_points->points ), 
					'currency' => $modded_currency + ( $old_points->currency ), 
					'bonus_currency' => $bonus_currency + ( $old_points->bonus_currency ), 
					'page_id' => $page_id, 
					'e_fail_count' => $e_fail_count, 
					'a_fail_count' => $a_fail_count, 
					'c_fail_count' => $c_fail_count, 
					'm_fail_count' => $m_fail_count, 
					'e_passed' => $e_passed, 
					'a_passed' => $a_passed, 
					'c_passed' => $c_passed, 
					'm_passed' => $m_passed, 
					'url' => $url_array
				), 
				array( 
					'uid' => $user_id, 
					'post_id' => $post_id
				)
			);
		}
	} // end if status isn't equal to -1
	go_update_totals( intval( $user_id ), $points, $currency, $bonus_currency, 0, $minutes, $status, $bonus_loot, null, $notify );
}
	
// Adds bonus currency.
function go_add_bonus_currency( $user_id, $bonus_currency, $reason, $status = 6 ) {
	global $wpdb;
	$table_name_go = $wpdb->prefix . "go";
	if ( ! empty( $_POST['qty'] ) ) {
		$bonus_currency = $bonus_currency * $_POST['qty'];
	}
	$time = date( 'm/d@H:i', current_time( 'timestamp', 0 ) );
	$wpdb->insert(
		$table_name_go, 
		array(
			'uid' => $user_id, 
			'status' => $status, 
			'bonus_currency' => $bonus_currency, 
			'reason' => $reason, 
			'timestamp' => $time
		)
	);
	go_update_totals( $user_id, 0, 0, $bonus_currency, 0, 0 );
}

// Adds penalties
function go_add_penalty( $user_id, $penalty, $reason, $status = 6, $bonus_loot = false ) {
	global $wpdb;
	$table_name_go = $wpdb->prefix."go";
	if ( ! empty( $_POST['qty'] ) ) {
		$penalty = $penalty * $_POST['qty'];
	}
	$time = date( 'm/d@H:i', current_time( 'timestamp', 0 ) );
	$wpdb->insert( 
		$table_name_go, 
		array(
			'uid' => $user_id, 
			'status' => $status, 
			'penalty' => $penalty, 
			'reason' => $reason, 
			'timestamp' => $time
		) 
	);
	go_update_totals( $user_id, 0, 0, 0, $penalty, 0, null, $bonus_loot );
}

// Adds minutes
function go_add_minutes( $user_id, $minutes, $reason, $status = 6 ) {
	global $wpdb;
	$table_name_go = $wpdb->prefix."go";
	if ( ! empty( $_POST['qty'] ) ) {
		$minutes = $minutes * $_POST['qty'];
	}
	$time = date( 'm/d@H:i', current_time( 'timestamp',0 ) );
	$wpdb->insert(
		$table_name_go, 
		array(
			'uid' => $user_id, 
			'status' => $status, 
			'minutes' => $minutes, 
			'reason' => $reason,
			'timestamp' => $time
		)
	);
	go_update_totals( $user_id, 0, 0, 0, 0, $minutes );
}

function go_notify( $type, $points = '', $currency = '', $bonus_currency = '', $penalty = '', $minutes = '', $user_id = null, $display = null, $bonus_loot = false, $undo = false ) {
	if ( $user_id !== get_current_user_id() ) {
		return false;	
	} else {
		if ( $points < 0 || $currency < 0 || $bonus_currency < 0 || $minutes < 0 || $undo == true ) {
			$sym = '-';
			$background = "#ff0000";
		} elseif ( $penalty < 0 ) {
			$sym = '+';
			$background = ( false === $bonus_loot ) ? "#39b54a" : "#1E90FF";
		} elseif ( $penalty > 0 ) {
			$sym = '-';
			$background = "#ff0000";
		} elseif ( true === $bonus_loot ) {
			$background = "#1E90FF";
		} else {
			$sym = '+';
			$background = "#39b54a";
		}
		global $go_notify_counter;
		$go_notify_counter++;
		$space = $go_notify_counter * 85;
		if ( $type == 'points' ) {
			$display = go_display_points( $points );
		} elseif ( $type == 'currency' ) {
			$display = go_display_currency( $currency );
		} elseif ( $type == 'bonus_currency' ) {
			$display = go_display_bonus_currency( $bonus_currency );
		} elseif ( $type == 'penalty' ) {
			$display = go_display_penalty( $penalty );
		} elseif ( $type == 'minutes' ) {
			$display = go_display_minutes( $minutes );
		} elseif ( $type == 'custom' ) {
			$display = $display;
		}
		echo "
		<div id='go_notification' class='go_notification' style='top: {$space}px; background: {$background}; '>{$display}</div>
		<script type='text/javascript' language='javascript'> 
		go_notification();
		</script>";
	}
}

function go_update_admin_bar( $type, $title, $value, $status = null ) {
	$user_id = get_current_user_id();
	$rank = go_get_rank( $user_id );
	$current_rank_points = $rank['current_rank_points'];
	$next_rank_points = $rank['next_rank_points'];

	$current_bonus_currency = go_return_bonus_currency( $user_id );
	$current_penalty = go_return_penalty( $user_id );

	$go_option_ranks = get_option( 'go_ranks' );
	$points_array = $go_option_ranks['points'];

	/*
	 * Here we are referring to last element manually,
	 * since we don't want to modifiy
	 * the arrays with the array_pop function.
	 */
	$max_rank_index = count( $points_array ) - 1;
	$max_rank_points = (int) $points_array[ $max_rank_index ];

	$color = barColor( $current_bonus_currency, $current_penalty );

	$display = go_display_longhand_currency( $type, $value );
	
	if ( 'points' == $type ) {
		
		if ( null !== $next_rank_points ) {
			$rank_threshold_diff = ( $next_rank_points - $current_rank_points );
		} else {
			$rank_threshold_diff = 1;
		}
		$pts_to_rank_threshold = ( $value - $current_rank_points );

		if ( $max_rank_points === $current_rank_points ) {
			$prestige_name = go_return_options( 'go_prestige_name' );
			$pts_to_rank_up_str = $prestige_name;
		} else {
			$pts_to_rank_up_str = "{$pts_to_rank_threshold} / {$rank_threshold_diff}";
		}
		echo "<script language='javascript'>
			jQuery(document).ready(function() {
				jQuery( '#points_needed_to_level_up' ).html( '{$pts_to_rank_up_str}' );
			} );
		</script>";
	}
	$percentage = go_get_level_percentage( $user_id );
	echo "<script language='javascript'>
		jQuery(document).ready(function() {
			jQuery( '#go_admin_bar_{$type}' ).html( '{$display}' );
			jQuery( '#go_admin_bar_progress_bar' ).css( {'width': '{$percentage}%'".( ( $color ) ? ", 'background-color' : '{$color}'" : '' )."} );
		} );
	</script>";
}

//Update totals
function go_update_totals( $user_id, $points, $currency, $bonus_currency, $penalty, $minutes, $status = null, $bonus_loot = null, $undo = false, $notify = true ) {
	global $wpdb;
	$table_name_go_totals = "{$wpdb->prefix}go_totals";
	$user_bonuses = go_return_bonus_currency( $user_id );
	$user_penalties = go_return_penalty( $user_id );
	if ( $user_id !== get_current_user_id() ) {
		$notify = false;
	}

	if ( $status !== -1 ) {
		$modded_array = go_return_multiplier( $user_id, $points, $currency, $user_bonuses, $user_penalties );
		$points = $modded_array[0];
		$currency = $modded_array[1];
	}
	if ( $points != 0 ) {
		$total_points = go_return_points( $user_id );
		$new_point_total = ( $points + $total_points > 0 ? $points + $total_points : 0 );

		$wpdb->update( 
			$table_name_go_totals, 
			array( 
				'points' => $new_point_total
			), 
			array( 
				'uid' => $user_id 
			) 
		);

		go_update_ranks( $user_id, $new_point_total, true );
		$total_string = (string) $new_point_total;
		if ( true === $notify ) {
			go_notify( 'points', $points, 0, 0, 0, 0, $user_id, null, $bonus_loot );
			go_update_admin_bar( 'points', go_return_options( 'go_points_name' ), $total_string, $status );
		}
	}
	if ( $currency != 0 ) {
		$total_currency = go_return_currency( $user_id );
		$wpdb->update( 
			$table_name_go_totals, 
			array( 
				'currency' => $currency + $total_currency
			), 
			array( 
				'uid' => $user_id
			) 
		);
		if ( true === $notify ) {
			go_notify( 'currency', 0, $currency, 0, 0, 0, $user_id, null, $bonus_loot );
			go_update_admin_bar( 'currency', go_return_options( 'go_currency_name' ), ( $currency + $total_currency ) );
		}
	}
	if ( $bonus_currency != 0 ) {
		$total_bonus_currency = go_return_bonus_currency( $user_id );
		$wpdb->update( 
			$table_name_go_totals, 
			array( 
				'bonus_currency' => $total_bonus_currency + $bonus_currency
			), 
			array( 
				'uid' => $user_id
			) 
		);
		if ( true === $notify ) {
			go_notify( 'bonus_currency', 0, 0, $bonus_currency, 0, 0, $user_id, null, $bonus_loot );
			go_update_admin_bar( 'bonus_currency', go_return_options( 'go_bonus_currency_name' ), $total_bonus_currency + $bonus_currency );
		}
	}
	if ( $penalty != 0 ) {
		$total_penalty = go_return_penalty( $user_id );
		$wpdb->update( 
			$table_name_go_totals, 
			array( 
				'penalty' => $total_penalty + $penalty
			), 
			array( 
				'uid' => $user_id
			) 
		);
		if ( true === $notify ) {
			go_notify( 'penalty', 0, 0, 0, $penalty, 0, $user_id, null, $bonus_loot );
			go_update_admin_bar( 'penalty', go_return_options( 'go_penalty_name' ), $total_penalty + $penalty );
		}
	}
	if ( $minutes != 0 ) {
		$total_minutes = go_return_minutes( $user_id );
		$wpdb->update( 
			$table_name_go_totals, 
			array( 
				'minutes' => $total_minutes + $minutes), 
			array( 
				'uid' => $user_id
			) 
		);
		if ( true === $notify ) {
			go_notify( 'minutes', 0, 0, 0, 0, $minutes, $user_id, null, $bonus_loot );
			go_update_admin_bar( 'minutes', go_return_options( 'go_minutes_name' ), $total_minutes + $minutes );
		}
	}
	if ( true === $bonus_loot && true === $notify ) {
		go_notify( 'custom', 0, 0, 0, 0, 0, $user_id, 'Bonus Loot', $bonus_loot, $undo );
	}
}

/**
 * Updates the user's ranks, if need be, after script/style enqueueing has occurred.
 *
 * Calls go_update_ranks() when the "wp_footer" action is fired, with a priority of 21. This occurs 
 * after default enqueueing has occurred (default priority for enqueued scripts is 20). 
 * Processing the user's rank at this point prevents any unwanted header errors. We can assume
 * that the notifications will succeed, since jQuery should be loaded by the time that they are
 * output.
 *
 * @since 2.6.0
 *
 * @see go_update_totals()
 */
function go_update_totals_out_of_bounds () {
	$user_id = get_current_user_id();
	$go_current_points = go_return_points( $user_id );
	if ( $go_current_points < 0 ) {
		$points = $go_current_points * -1;
		$currency = 0;
		$bonus_currency = 0;
		$penalty = 0;
		$minutes = 0;

		// this is not a store item, but we don't want the super modifier to be applied
		$status = -1;

		go_update_totals( $user_id, $points, $currency, $bonus_currency, $penalty, $minutes, $status );
	}
}

function go_admin_bar_add () {
	$points = $_POST['go_admin_bar_add_points'];
	$points_reason = $_POST['go_admin_bar_add_points_reason'];
	
	$currency = $_POST['go_admin_bar_add_currency'];
	$currency_reason = $_POST['go_admin_bar_add_currency_reason'];
	
	$bonus_currency = $_POST['go_admin_bar_add_bonus_currency'];
	$bonus_currency_reason = $_POST['go_admin_bar_add_bonus_currency_reason'];
	
	$penalty = $_POST['go_admin_bar_add_penalty'];
	$penalty_reason = $_POST['go_admin_bar_add_penalty_reason'];
	
	$minutes = $_POST['go_admin_bar_add_minutes'];
	$minutes_reason = $_POST['go_admin_bar_add_minutes_reason'];
	
	$user_id = get_current_user_id();

	if ( '' != $points && '' != $points_reason ) {
		go_add_currency( $user_id, $points_reason, 6, $points, 0, false );
	}
	if ( '' != $currency && '' != $currency_reason ) {
		go_add_currency( $user_id, $currency_reason, 6, 0, $currency, false );
	}
	if ( '' != $bonus_currency && '' != $bonus_currency_reason ) {
		go_add_bonus_currency( $user_id, $bonus_currency, $bonus_currency_reason );
	}
	if ( '' != $penalty && '' != $penalty_reason ) {
		go_add_penalty( $user_id, $penalty, $penalty_reason);
	}
	if ( '' != $minutes && '' != $minutes_reason ) {
		go_add_minutes( $user_id, $minutes, $minutes_reason);
	}
	
	die();
}

function go_get_level_percentage( $user_id ) {
	global $wpdb;
	$current_points = go_return_points( $user_id );
	$rank = go_get_rank( $user_id );
	$current_rank = $rank['current_rank'];
	$current_rank_points = $rank['current_rank_points'];
	$next_rank_points = $rank['next_rank_points'];

	$dom = ( $next_rank_points - $current_rank_points );
	if ( $dom <= 0 ) { 
		$dom = 1;
	}
	$percentage = ( $current_points - $current_rank_points ) / $dom * 100;
	if ( $percentage <= 0 ) { 
		$percentage = 0;
	} else if ( $percentage >= 100 ) {
		$percentage = 100;
	}
	return $percentage;
}

function go_return_options( $option ) {
	if ( defined ( $option ) ) {
		return constant( $option );
	} else {
		return get_option( $option );
	}
}

function barColor( $current_bonus_currency, $current_penalty ) {
	$bonus_threshold = (int) get_option( 'go_multiplier_threshold', 10 );
	$penalty_threshold = (int) get_option( 'go_penalty_threshold', 5 );
	$current_penalty = (int) $current_penalty;
	$color = "#39b54a";
	if ( $current_bonus_currency >= $bonus_threshold && $current_penalty <= $penalty_threshold ) {
		$color = "#1E90FF";
	}
	if ( $current_penalty >= $penalty_threshold ) {
		$color = "#ffcc00";
	}
	if ( $current_penalty >= $penalty_threshold * 2 ) {
		$color = "#ff6700";
	}
	if ( $current_penalty >= $penalty_threshold * 3 ) {
		$color = "#ff0000";
	}
	if ( $current_penalty >= $penalty_threshold * 4 ) {
		$color = "#464646";
	}

	return $color;
}

/**
 * Handles applying and returning the super modifier on a set of experience and gold. 
 * 
 * Description: The modified currencies are also rounded before they are output. Under the influence
 * 			of a positive super modifier, negative currencies are rounded down, and positive 
 * 			currencies are rounded up. Under a negative super modifier, negative currencies are 
 * 			rounded up, and positive currencies are rounded down.
 *
 * @since 2.6.0
 * 
 * @param  INT $user_id, the user's id number.
 * @param  INT $points, the number of experience points that the modifier is being applied against.
 * @param  INT $currency, the number of gold that the modifier is being applied against.
 * @param  INT $user_bonuses, the number of bonuses that the user holds.
 * @param  INT $user_penalties, the number of penalties that the user holds.
 * @param  BOOLEAN $return_mod Optional. False, to return an array of the modified currencies. True,
 * 			to return the modifier ONLY.
 * @return ARRAY/INT Returns an array of modified currencies, or returns the modifier itself. If
 * 			the bonus and penalty features are both off, an array of the unmodified currencies are
 * 			returned.
 */
function go_return_multiplier ( $user_id, $points, $currency, $user_bonuses, $user_penalties, $return_mod = false ) {
	$points = (int) $points;
	$currency = (int) $currency;
	$bonus_active = ( 'On' === get_option( 'go_multiplier_switch', false ) ? true : false );
	$penalty_active = ( 'On' === get_option( 'go_penalty_switch', false ) ? true : false );

	$is_max_rank = go_user_at_max_rank( $user_id );

	// the prestige system should disable XP rewards
	$prestige_xp_nullifier = 0;
	$prestige_buff = 2;
	$prestige_debuff = 0.5;

	if ( $is_max_rank ) {
		if ( $points > 0 ) {
			$points *= $prestige_xp_nullifier;
		}
		$currency *= $prestige_buff;
	}

	if ( ! $bonus_active && ! $penalty_active ) {
		return array( $points, $currency );
	}

	if ( $bonus_active && $penalty_active ) {
		$bonus_threshold = (int) get_option( 'go_multiplier_threshold', 10 );
		$penalty_threshold = (int) get_option( 'go_penalty_threshold', 5 );
		$multiplier = ( (int) get_option( 'go_multiplier_percentage', 10 ) ) / 100;
		$bonus_frac = ( $user_bonuses > 0 ? intval( $user_bonuses / $bonus_threshold ) : 0 );
		$penalty_frac = ( $user_penalties > 0 ? intval( $user_penalties / $penalty_threshold ) : 0 );
		$diff = $bonus_frac - $penalty_frac;
		if ( 0 == $diff ) {
			if ( ! $return_mod ) {
				return array( $points, $currency );
			} else {
				return 0;
			}
		} else {
			if ( $is_max_rank && $penalty_frac > 0 ) {
				$currency = ( $currency / $prestige_buff ) * $prestige_debuff;
			}

			$rounded_points = 0;
			$rounded_currency = 0;
			$mod = $multiplier * $diff;
			$modded_points = $points + ( $points * $mod );
			$modded_currency = $currency + ( $currency * $mod );

			if ( $mod > 0 )  {
				if ( $points < 0 ) {
					$rounded_points = floor( $modded_points );
				} else {
					$rounded_points = ceil( $modded_points );
				}
				if ( $currency < 0 ) {
					$rounded_currency = floor( $modded_currency );
				} else {
					$rounded_currency = ceil( $modded_currency );
				}
			} else if ( $mod < 0 ) {
				if ( $points < 0 ) {
					$rounded_points = ceil( $modded_points );
				} else {
					$rounded_points = floor( $modded_points );
				}
				if ( $currency < 0 ) {
					$rounded_currency = ceil( $modded_currency );
				} else {
					$rounded_currency = floor( $modded_currency );
				}
			}
			if ( ! $return_mod ) {
				return array( $rounded_points, $rounded_currency );
			} else {
				return $mod;
			}
		}
	} else if ( $bonus_active ) {
		$bonus_threshold = (int) get_option( 'go_multiplier_threshold', 10 );
		$multiplier = ( (int) get_option( 'go_multiplier_percentage', 10 ) ) / 100;
		$bonus_frac = intval( $user_bonuses / $bonus_threshold );
		if ( 0 == $bonus_frac ) {
			if ( ! $return_mod ) {
				return array( $points, $currency );
			} else {
				return 0;
			}
		} else {
			$rounded_points = 0;
			$rounded_currency = 0;
			$mod = $multiplier * $bonus_frac;
			$modded_points = $points + ( $points * $mod );
			$modded_currency = $currency + ( $currency * $mod );
			
			if ( $points < 0 ) {
				$rounded_points = floor( $modded_points );
			} else {
				$rounded_points = ceil( $modded_points );
			}
			if ( $currency < 0 ) {
				$rounded_currency = floor( $modded_currency );
			} else {
				$rounded_currency = ceil( $modded_currency );
			}
			if ( ! $return_mod ) {
				return array( $rounded_points, $rounded_currency );
			} else {
				return $mod;
			}
		}
	} else if ( $penalty_active ) {
		$penalty_threshold = (int) get_option( 'go_penalty_threshold', 5 );
		$multiplier = ( (int) get_option( 'go_multiplier_percentage', 10 ) ) / 100;
		$penalty_frac = ( $user_penalties > 0 ? intval( $user_penalties / $penalty_threshold ) : 0 );
		if ( 0 == $penalty_frac ) {
			if ( ! $return_mod ) {
				return array( $points, $currency );
			} else {
				return 0;
			}
		} else {
			if ( $is_max_rank && $penalty_frac > 0 ) {
				$currency = ( $currency / $prestige_buff ) * $prestige_debuff;
			}
			
			$rounded_points = 0;
			$rounded_currency = 0;
			$mod = $multiplier * ( -$penalty_frac );
			$modded_points = $points + ( $points * $mod );
			$modded_currency = $currency + ( $currency * $mod );

			if ( $points < 0 ) {
				$rounded_points = ceil( $modded_points );
			} else {
				$rounded_points = floor( $modded_points );
			}
			if ( $currency < 0 ) {
				$rounded_currency = ceil( $modded_currency );
			} else {
				$rounded_currency = floor( $modded_currency );
			}
			if ( ! $return_mod ) {
				return array( $rounded_points, $rounded_currency );
			} else {
				return $mod;
			}
		}
	}
}

function go_task_abandon( $user_id = null, $post_id = null, $e_points = null, $e_currency = null, $e_bonus_currency = null ) {
	global $wpdb;
	if ( empty( $user_id ) && empty( $post_id ) && empty( $e_points ) && empty( $e_currency ) && empty( $e_bonus_currency ) ) {
		$user_id = get_current_user_id();
		$post_id = $_POST['post_id'];
		$e_points = intval( $_POST['encounter_points'] );
		$e_currency = intval( $_POST['encounter_currency'] );
		$e_bonus_currency = intval( $_POST['encounter_bonus'] );
	}
	$table_name_go = "{$wpdb->prefix}go";
	$accept_timestamp = strtotime( str_replace( '@', ' ', $wpdb->get_var( "SELECT timestamp FROM {$wpdb->prefix}go WHERE uid='{$user_id}' AND post_id='{$post_id}'" ) ) );
	go_update_totals( $user_id, -$e_points, -$e_currency, -$e_bonus_currency, 0, 0);
	$wpdb->query( 
		$wpdb->prepare( "
			DELETE FROM {$table_name_go} 
			WHERE uid = %d 
			AND post_id = %d",
			$user_id,
			$post_id
		)
	);
	$custom_fields = get_post_custom( $post_id );
	$future_modifier = ( ! empty( $custom_fields['go_mta_time_modifier'][0] ) ? unserialize( $custom_fields['go_mta_time_modifier'][0] ) : '' );
	$user_timers = get_user_meta( $user_id, 'go_timers', true );
	if ( ! empty( $future_modifier ) && empty( $user_timers[ $post_id ] ) ) {
		$user_timers[ $post_id ] = $accept_timestamp;
		update_user_meta( $user_id, 'go_timers', $user_timers );
	}
}

?>