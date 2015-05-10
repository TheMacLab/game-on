<?php

function go_return_currency( $user_id ) {
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$currency = (int) $wpdb->get_var( "SELECT currency FROM {$table_name_go_totals} WHERE uid = {$user_id}" );
	return $currency;
}
	
function go_return_points( $user_id ) {
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$points = (int) $wpdb->get_var( "SELECT points FROM {$table_name_go_totals} WHERE uid = {$user_id}" );
	return $points;
}

function go_return_bonus_currency( $user_id ) {
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$bonus_currency = (int) $wpdb->get_var( "SELECT bonus_currency FROM {$table_name_go_totals} WHERE uid = {$user_id}" );
	return $bonus_currency;
}

function go_return_penalty( $user_id ) {
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$penalty = (int) $wpdb->get_var( "SELECT penalty FROM {$table_name_go_totals} WHERE uid = {$user_id}" );
	return $penalty;
}

function go_return_minutes( $user_id ) {
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$minutes = (int) $wpdb->get_var( "SELECT minutes FROM {$table_name_go_totals} WHERE uid = {$user_id}" );
	return $minutes;
}

function go_display_points( $points ) {
	global $wpdb;
	$prefix = go_return_options( 'go_points_prefix' );
	$suffix = go_return_options( 'go_points_suffix' );
	return $prefix.$points.$suffix;
}
	
function go_display_currency( $currency ) {
	global $wpdb;
	$prefix = go_return_options( 'go_currency_prefix' );
	$suffix = go_return_options( 'go_currency_suffix' );
	return $prefix . $currency . $suffix;
}

function go_display_bonus_currency( $bonus_currency ) {
	global $wpdb;
	$prefix = go_return_options( 'go_bonus_currency_prefix' );
	$suffix = go_return_options( 'go_bonus_currency_suffix' );
	return $prefix . $bonus_currency . $suffix;
}

function go_display_penalty( $penalty ) {
	global $wpdb;
	$prefix = go_return_options( 'go_penalty_prefix' );
	$suffix = go_return_options( 'go_penalty_suffix' );
	return $prefix . $penalty . $suffix;
}

function go_display_minutes( $minutes ) {
	global $wpdb;
	$prefix = go_return_options( 'go_minutes_prefix' );
	$suffix = go_return_options( 'go_minutes_suffix' );
	return $prefix . $minutes . $suffix;
}

function go_filter_focuses ( $elem ) {
	if ( strpos( $elem, ':' ) === false && strpos( $elem, 'No '.go_return_options( 'go_focus_name' ) ) === false) {
		return true;
	} else { 
		return false;
	}
}

function go_display_user_focuses ( $user_id ) {
	$user_focuses = get_user_meta( $user_id , 'go_focus', true );
	
	if ( !empty( $user_focuses ) ) {
		if ( !is_array( $user_focuses) ) {
			$output = $user_focuses;
		} else {
			$filtered_user_focuses = array_filter( $user_focuses );
			if ( count( array_unique( $filtered_user_focuses ) ) === 1 && reset( $filtered_user_focuses ) === ':' ) {
				$output = 'No '.go_return_options( 'go_focus_name' );
			} else {
				$value = array_filter( $filtered_user_focuses, 'go_filter_focuses' );
				$output = implode( ', ', $value );
			}
		}
	} else {
		$output = 'No '.go_return_options( 'go_focus_name' );
	}
	
	return $output;
}

function go_return_badge_count( $user_id ) {
	global $wpdb;
	$badge_count = (int) $wpdb->get_var( "SELECT badge_count FROM {$wpdb->prefix}go_totals WHERE uid = {$user_id}" );
	return $badge_count;
}

// Optionally returns user's correct current rank name FROM the go_ranks option, based on user's current points.
// Updates current_rank global regardless.
function go_return_clean_rank( $user_id, $return_rank = true) {
	if ( !empty( $user_id ) ) {
		global $current_rank;
		global $current_points;
		$ranks = get_option( 'go_ranks' );
		$names = $ranks['name'];
		$points = $ranks['points'];
		if ( !empty( $names ) && !empty( $points ) ) {
			for ( $i = 0; $i < count( $points ); $i++) {
				if ( (int) $points[ $i + 1 ] > (int) $current_points ) {
					$name = $names[ $i ];
					$current_rank = $name;
					if ( $return_rank === true) {
						return ( $name );
					}
				}
			}
		}
	}
}

?>