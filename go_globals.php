<?php
$go_plugin_dir = dirname(__FILE__);

function go_global_defaults(){
	global $role_default;
	$role_default = 'subscriber';
}
function go_global_info(){
	global $wpdb;
	global $current_user_id;
	global $current_points;
	global $current_currency;
	global $current_bonus_currency;
	global $current_penalty;
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	$current_points = go_return_points($current_user_id);
	$current_currency = go_return_currency($current_user_id);
	$current_bonus_currency = go_return_bonus_currency($current_user_id);
	$current_penalty = go_return_penalty($current_user_id);
	go_get_rank($current_user_id);
}
?>