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
			"health" === $currency_type
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
        "health" === $currency_type
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