<?php

function go_return_currency($user_id){
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$currency = (int)$wpdb->get_var("select currency from ".$table_name_go_totals." where uid = $user_id");
	return $currency;
}
	
function go_return_points($user_id){
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$points = (int)$wpdb->get_var("select points from ".$table_name_go_totals." where uid = $user_id");
	return $points;
}

function go_return_bonus_currency($user_id){
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$bonus_currency = (int)$wpdb->get_var("select bonus_currency from ".$table_name_go_totals." where uid = $user_id");
	return $bonus_currency;
}

function go_return_penalty($user_id){
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$penalty = (int)$wpdb->get_var("select penalty from ".$table_name_go_totals." where uid = $user_id");
	return $penalty;
}

function go_display_points($points){
	global $wpdb;
	$prefix = go_return_options('go_points_prefix');
	$suffix = go_return_options('go_points_suffix');
	return $prefix.$points.$suffix;
}
	
function go_display_currency($currency){
	global $wpdb;
	$prefix = go_return_options('go_currency_prefix');
	$suffix = go_return_options('go_currency_suffix');
	return $prefix.$currency.$suffix;
}

function go_display_bonus_currency($bonus_currency) {
	global $wpdb;
	$prefix = go_return_options('go_bonus_currency_prefix');
	$suffix = go_return_options('go_bonus_currency_suffix');
	return $prefix.$bonus_currency.$suffix;
}

function go_display_penalty($penalty){
	global $wpdb;
	$prefix = go_return_options('go_penalty_prefix');
	$suffix = go_return_options('go_penalty_suffix');
	return $prefix.$penalty.$suffix;
}

function go_display_user_focuses($user_id){
	
	if(get_user_meta($user_id, 'go_focus',true)){
		if(!is_array(get_user_meta($user_id, 'go_focus',true))){
			$value = get_user_meta($user_id, 'go_focus',true);
		}else{
			$value = implode(', ',get_user_meta($user_id, 'go_focus', true));
		}
	} else{
		$value = 'No '.go_return_options('go_focus_name');
	}
	
	return $value;
}

function go_return_badge_count($user_id){
	global $wpdb;
	$badge_count = $wpdb->get_var("SELECT badge_count FROM {$wpdb->prefix}go_totals WHERE uid = {$user_id}");
	return $badge_count;
}
?>