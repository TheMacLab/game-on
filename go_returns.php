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
function go_return_infractions($user_id){
	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$infractions = (int)$wpdb->get_var("select infractions from ".$table_name_go_totals." where uid = $user_id");
	return $infractions;
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
function go_display_user_focuses($user_id){
	
	if(get_user_meta($user_id, 'go_focus',true)){
		if(!is_array(get_user_meta($user_id, 'go_focus',true))){
			$valueu = get_user_meta($user_id, 'go_focus',true);
		}else{
			$valueu = implode(', ',get_user_meta($user_id, 'go_focus', true));
		}
	} else{
		$valueu = 'No '.go_return_options('go_focus_name');	
	}
	
	return $valueu;
}

function go_return_task_amount_in_chain($chain){
	global $wpdb;
	$count = $wpdb->query("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key = 'chain' and meta_value = '".$chain."'");
	return $count;
}
?>