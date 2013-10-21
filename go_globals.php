<?php

function go_global_defaults(){
	global $role_default;
	$role_default = 'subscriber';
	}
function go_global_info(){
	global $wpdb;
	global $current_user_id;
	global $current_points;
	global $current_currency;
	global $current_user_infractions;
	global $current_max_infractions;
 	$current_user = wp_get_current_user();
 	$current_user_id = $current_user->ID;
	$current_points = go_return_points($current_user_id);
	$current_currency = go_return_currency($current_user_id);
	$current_user_infractions = go_return_infractions($current_user_id);
	$current_max_infractions = go_return_options('go_max_infractions');
	go_get_rank($current_user_id);
	
	//Debug line (can be uncommented if the second argument is 0):
	//Causes the user to recive infractions upon loading a page 
	//(note: this hits the database AFTER the number of infractions is written to the HTML document sent to the user)
	go_add_infraction($current_user_id,0,true);
	}

?>
