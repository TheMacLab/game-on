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
	$posts_in_chain = get_posts(array(
		'post_type' => 'tasks',
		'post_status' => 'publish',
		'taxonomy' => 'task_chains',
		'term' => $chain,
		'posts_per_page' => '-1'
	));
	$count = count($posts_in_chain);
	return $count;
}

function go_return_badge_count($user_id){
	global $wpdb;
	$badge_ids = get_user_meta($user_id, 'go_badges', true);
	$badge_count = count($badge_ids);
	$wpdb->update($wpdb->prefix."go_totals", array('badge_count' => $badge_count), array('uid' => $user_id));
	return $badge_count;
}
?>