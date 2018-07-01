<?php

function go_return_currency( $user_id ) {

	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_loot";
	$currency = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT gold 
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
			$user_id
		)
	);
	return $currency;

}
	
function go_return_points( $user_id ) {

	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_loot";
	$points = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT xp 
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
			$user_id
		)
	);
	return $points;
}

function go_return_health( $user_id ) {

	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_loot";
	$health = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT health 
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
			$user_id
		)
	);
	return $health;
}

function go_return_c4( $user_id ) {

	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_loot";
	$c4 = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT c4
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
			$user_id
		)
	);
	return $c4;

}



function go_display_points( $points ) {

	$prefix = get_option( 'go_points_prefix' );
	$suffix = get_option( 'go_points_suffix' );
	return "{$prefix} {$points} {$suffix}";
}
	
function go_display_currency( $currency ) {
	$prefix = get_option( 'go_currency_prefix' );
	$suffix = get_option( 'go_currency_suffix' );
	return "{$prefix} {$currency} {$suffix}";
}

function go_display_bonus_currency( $bonus_currency ) {
	$prefix = get_option( 'go_bonus_currency_prefix' );
	$suffix = get_option( 'go_bonus_currency_suffix' );
	return "{$prefix} {$bonus_currency} {$suffix}";
}

function go_display_penalty( $penalty ) {
	$prefix = get_option( 'go_penalty_prefix' );
	$suffix = get_option( 'go_penalty_suffix' );
	return "{$prefix} {$penalty} {$suffix}";
}

function go_display_minutes( $minutes ) {
	$prefix = get_option( 'go_minutes_prefix' );
	$suffix = get_option( 'go_minutes_suffix' );
	return "{$prefix} {$minutes} {$suffix}";
}

/**
 * Output currency formatted for the admin bar dropdown.
 *
 * Outputs any currency in the format (without quotations): "1234 Experience (XP)".
 *
 * @since 2.5.6
 *
 * @param  STRING $currency_type Contains the base name of the currency to be displayed
 * 			(e.g. "points", "currency", "bonus_currency", or "penalty" ).
 * @param  BOOLEAN $output Optional. TRUE will echo the currency, FALSE will return it (default).
 * @return STRING/NULL Either echos or returns the currency. Returns FALSE on failure.
 */
function go_display_longhand_currency ( $currency_type, $amount, $output = false ) {
	if ( "xp" === $currency_type ||
			"gold" === $currency_type ||
			"health" === $currency_type ||
			"c4" === $currency_type
		) {

		$currency_name = get_option( "options_go_loot_{$currency_type}_name" );
		$suffix = get_option( "options_go_loot_{$currency_type}_abbreviation" );
		$str = "{$amount} {$currency_name} ({$suffix})";

		if ( $output ) {
			echo $str;
		} else {
			return $str;
		}
	} else {
		return false;
	}
}

function go_display_shorthand_currency ( $currency_type, $amount, $output = false ) {
    if ( "xp" === $currency_type ||
        "gold" === $currency_type ||
        "health" === $currency_type ||
        "c4" === $currency_type
    ) {

        $suffix = get_option( "options_go_loot_{$currency_type}_abbreviation" );
        $str = "{$suffix}: {$amount}";

        if ( $output ) {
            echo $str;
        } else {
            return $str;
        }
    } else {
        return false;
    }
}

function go_filter_focuses( $elem ) {
	if ( strpos( $elem, ':' ) === false && strpos( $elem, 'No '.get_option( 'go_focus_name' ) ) === false) {
		return true;
	} else { 
		return false;
	}
}

function go_display_user_focuses( $user_id ) {
	$user_focuses = get_user_meta( $user_id , 'go_focus', true );
	
	if ( ! empty( $user_focuses ) ) {
		if ( ! is_array( $user_focuses) ) {
			$output = $user_focuses;
		} else {
			$filtered_user_focuses = array_filter( $user_focuses );
			if ( count( array_unique( $filtered_user_focuses ) ) === 1 && reset( $filtered_user_focuses ) === ':' ) {
				$output = 'No '.get_option( 'go_focus_name' );
			} else {
				$value = array_filter( $filtered_user_focuses, 'go_filter_focuses' );
				$output = implode( ', ', $value );
			}
		}
	} else {
		$output = 'No '.get_option( 'go_focus_name' );
	}
	
	return $output;
}

function go_return_badge_count( $user_id ) {
	global $wpdb;
	$badge_count = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT badge_count 
			FROM {$wpdb->prefix}go_loot 
			WHERE uid = %d",
			$user_id
		)
	);
	return $badge_count;
}

?>