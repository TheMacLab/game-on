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

function go_return_minutes($user_id){
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$minutes = (int)$wpdb->get_var("select minutes from ".$table_name_go_totals." where uid = $user_id");
	return $minutes;
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

?>