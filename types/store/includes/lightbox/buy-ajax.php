<?php
add_action('wp_enqueue_scripts', 'go_buy_the_item'); //add plugin script; 

function go_buy_the_item(){ 
    if(!is_admin()){ 
        wp_enqueue_script('more-posts', plugins_url( 'js/buy_the_item.js' , __FILE__ ), array('jquery'), 1.0, true); 
        wp_localize_script('more-posts', 'buy_item', array('ajaxurl' => admin_url('admin-ajax.php'))); //create ajaxurl global for front-end AJAX call; 
    } 
} 

add_action('wp_ajax_buy_item', 'go_buy_item'); //fire go_buy_item on AJAX call for the backend; 
add_action('wp_ajax_nopriv_buy_item', 'go_buy_item'); //fire go_buy_item on AJAX call for all users; 

function go_buy_item(){ 
global $wpdb;
    $the_id = $_POST["the_id"];
	$qty = $_POST['qty'];
	if(isset($_POST['recipient']) && !empty($_POST['recipient'])){
		$recipient = $_POST['recipient'];
	}
	$user_ID = get_current_user_id(); // Current User ID
	$custom_fields = get_post_custom($the_id);
	if(isset($custom_fields['go_mta_penalty_switch'])){
		$penalty = true;
	}
	$req_points = $custom_fields['go_mta_store_points'][0];
	$req_currency = $custom_fields['go_mta_store_currency'][0];
	$req_time = $custom_fields['go_mta_store_time'][0];
	$req_rank = $custom_fields['go_mta_store_rank'][0];
	if($custom_fields['go_mta_store_itemURL'][0] && $custom_fields['go_mta_store_itemURL'][0] != ''){
		$item_url = $custom_fields['go_mta_store_itemURL'][0];
	} 
	$repeat = 'on';
	$user_points = go_return_points($user_ID);
	$user_currency = go_return_currency($user_ID);
	$user_time = go_return_minutes($user_ID);
	$page_id = get_the_id();
	if ($req_rank <= $user_points) { $rank_re = true; } else { $rank_re = false; }
	if ($req_points <= $user_points ) { $points_re = true; } else { $points_re = false; }
	if ($req_currency <= $user_currency || $penalty) { $currency_re = true; } else { $currency_re = false; }
	 $time_re = true; 
	$the_buy_arr = array('points','currency','time');
	$stack_arr = array();
	foreach ($the_buy_arr as $type) {
		switch ($type) {
			case 'points':
				if ($points_re == false) {
					array_push($stack_arr, go_return_options('go_points_name'));
				}
				break;
			case 'currency':
				if ($currency_re == false) {
					array_push($stack_arr, go_return_options('go_currency_name'));
				}
				break;
			case 'time':
				if ($time_re == false) {
					array_push($stack_arr, 'Time');
				}
				break;
			case 'rank':
				if ($rank_re == false) {
					array_push($stack_arr, 'Rank');
				}
				break;
		}
	}
	
	if ($points_re == true && $currency_re == true && $time_re == true && $rank_re == true) {
		go_add_post($user_ID, $the_id, -1, -$req_points, -$req_currency, $page_id, $repeat);
		if($item_url){
			$item_hyperlink = '<a target="_blank" href="'.$item_url.'">Grab your loot!</a>';
			echo $item_hyperlink;
		} else{
			echo 'Purchased';	
		}
		if( $req_time != ''){
			go_add_minutes($user_ID, -$req_time, get_the_title($the_id));
		}
	} else {
		$new_stack = implode(',', $stack_arr);
		echo $new_stack;
	}
	die();
}
?>