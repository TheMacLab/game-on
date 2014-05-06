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
    $post_id = $_POST["the_id"];
	$qty = $_POST['qty'];
	
	if(isset($_POST['recipient']) && !empty($_POST['recipient'])){
		$recipient = $_POST['recipient'];
	}
	
	if($recipient){
		$recipient_id = $wpdb->get_var('SELECT id FROM '.$wpdb->users.' WHERE display_name="'.$recipient.'"'); 
	}
	$user_id = get_current_user_id(); 
	
	$custom_fields = get_post_custom($post_id);
	$req_currency = check_custom($custom_fields['go_mta_store_currency'][0]);
	$req_points = check_custom($custom_fields['go_mta_store_points'][0]);
	$req_minutes = check_custom($custom_fields['go_mta_store_time'][0]);
	$req_rank = check_custom($custom_fields['go_mta_store_rank'][0]);
	$item_focus_switch = check_custom($custom_fields['go_mta_focus_item_switch'][0]);
	if($item_focus_switch && $item_focus_switch == 'on'){
		$item_focus = check_custom($custom_fields['go_mta_focuses'][0]);	
	}
	$penalty = check_custom($custom_fields['go_mta_penalty_switch']);
	$exchange_switch = check_custom($custom_fields['go_mta_store_exchange_switch'][0]);
	if($exchange_switch && $exchange_switch == 'on'){
		$exchange_currency = check_custom($custom_fields['go_mta_store_exchange_currency'][0]) * $qty;
		$exchange_points = check_custom($custom_fields['go_mta_store_exchange_points'][0]) * $qty;
		$exchange_time = check_custom($custom_fields['go_mta_store_exchange_time'][0]) * $qty;
	}
	$item_url = check_custom($custom_fields['go_mta_store_itemURL'][0]);
	$repeat = 'on';
	
	$cur_currency = go_return_currency($user_id);
	$cur_points = go_return_points($user_id);
	$cur_minutes = go_return_minutes($user_id);
	
	$enough_currency = check_values($req_currency, $cur_currency);
	$enough_points = check_values($req_points, $cur_points);
	$enough_minutes = check_values($req_minutes, $cur_minutes);
	
	if(($enough_currency && $enough_minutes && $enough_points) || $penalty){
		if($item_focus_switch && $item_focus){
			$user_focuses = (array) get_user_meta($user_id, 'go_focus', true);
			$user_focuses[] = $item_focus;
			update_user_meta($user_id, 'go_focus', $user_focuses);
		}
		if($recipient_id){
			go_add_post($recipient_id, $post_id, -1, -$req_points, -$req_currency, null, $repeat);
			go_message_user($recipient_id, get_userdata($user_id)->display_name.' has purchased <a href="javascript:;" onclick="go_lb_opener('.$post_id.')" style="display: inline-block; text-decoration: underline; padding: 0px; margin: 0px;">'.get_the_title($post_id).'</a> for you '.$qty.' time(s).');
			if($exchange_currency || $exchange_points || $exchange_time){
				go_update_totals($recipient_id, $exchange_points, $exchange_currency, $exchange_time);
			}
		}else{
			go_add_post($user_id, $post_id, -1, -$req_points, -$req_currency, null, $repeat);
		}
		if($req_minutes != ''){
			go_add_minutes($user_id, -$req_minutes, 'store');
		}
		if($item_url){
			$item_hyperlink = '<a target="_blank" href="'.$item_url.'">Grab your loot!</a>';
			echo $item_hyperlink;
		} else{
			echo 'Purchased';	
		}
	} else{
		$enough_array = array('currency' => $enough_currency, 'points' => $enough_points, 'time' => $enough_minutes);
		$errors = array();
		foreach($enough_array as $key => $enough){
			if(!$enough){
				$errors[] = $key;
			}
		}
		$errors = implode(', ', $errors);
		echo 'Need more '.substr($errors,0,strlen($errors));
	}
	die();
}
?>