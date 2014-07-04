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
	$table_name_go = $wpdb->prefix."go";
    $post_id = $_POST["the_id"];
	$qty = $_POST['qty'];
	$current_purchase_count = $_POST['purchase_count'];

	if(isset($_POST['recipient']) && !empty($_POST['recipient'])){
		$recipient = $_POST['recipient'];
	}
	
	if($recipient){
		$recipient_id = $wpdb->get_var('SELECT id FROM '.$wpdb->users.' WHERE display_name="'.$recipient.'"'); 
		$recipient_purchase_count = $wpdb->get_var("SELECT `count` FROM `".$table_name_go."` WHERE `post_id`='".$post_id."' AND `uid`='".$recipient_id."'"); 
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
		$exchange_currency = check_custom($custom_fields['go_mta_store_exchange_currency'][0]);
		$exchange_points = check_custom($custom_fields['go_mta_store_exchange_points'][0]);
		$exchange_time = check_custom($custom_fields['go_mta_store_exchange_time'][0]);
	}
	$badge_reward_switch = check_custom($custom_fields['go_mta_badge_switch'][0]);
	if($badge_reward_switch == 'on'){
		$badge_id = check_custom($custom_fields['go_mta_badge_id'][0]);	
		$badge_after_purchases = check_custom($custom_fields['go_mta_badge_purchase_count'][0]);
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
			go_message_user($recipient_id, get_userdata($user_id)->display_name.' has purchased <a href="javascript:;" onclick="go_lb_opener('.$post_id.')" style="display: inline-block; text-decoration: underline; padding: 0px; margin: 0px;">'.get_the_title($post_id).'</a> for you '.$qty.' time(s).');
			if($exchange_currency || $exchange_points || $exchange_time){
				go_add_post($recipient_id, $post_id, -1, $exchange_points, $exchange_currency, null, $repeat);
				go_add_minutes($recipient_id, $exchange_time, get_userdata($user_id)->display_name.' purchase of '.get_the_title($post_id).' '.$qty.' times');
			}
			go_add_post($user_id, $post_id, -1, -$req_points, -$req_currency, null, $repeat);
		}else{
			go_add_post($user_id, $post_id, -1, -$req_points, -$req_currency, null, $repeat);
		}
		if($req_minutes != ''){
			go_add_minutes($user_id, -$req_minutes, 'Purchase of '.get_the_title($post_id).' '.$qty.' times');
		}
		if($badge_reward_switch){
			if($recipient_id){
				if(($recipient_purchase_count + $qty) >= $badge_after_purchases){
					do_shortcode('[go_award_badge id="'.$badge_id.'" repeat = "off" uid="'.$recipient_id.'"]');
				}
			}elseif(($current_purchase_count + $qty) >= $badge_after_purchases){
				do_shortcode('[go_award_badge id="'.$badge_id.'" repeat = "off" uid="'.$user_id.'"]');
			}
		}
		if($item_url){
			$item_hyperlink = '<a target="_blank" href="'.$item_url.'">Grab your loot!</a>';
			echo $item_hyperlink;
		} else{
			echo "Purchased";
		}
		$receipt = go_mail_item_reciept($user_id, $post_id, $req_currency, $req_points, $req_minutes, $qty, $recipient_id);
		if (!empty($receipt)) {
			echo $receipt;
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

function go_mail_item_reciept ($user_id, $item_id, $req_currency, $req_points, $req_minutes, $qty, $recipient_id = null) {
	global $go_plugin_dir;
	$currency = ucwords(go_return_options('go_currency_name'));
	$points = ucwords(go_return_options('go_points_name'));
	$item_title = get_the_title($item_id);
	// For future use, when we give the option to rename minutes:
	// $token = go_return_options('go_token_name');

	$user_info = get_userdata($user_id);
	$username = $user_info->user_login;
	$user_full_name = "{$user_info->first_name} {$user_info->last_name}";
	$user_email = $user_info->user_email;
	$user_role = $user_info->roles;

	$to = get_option('go_admin_email','');
	require("{$go_plugin_dir}/mail/class.phpmailer.php");
	$mail = new PHPMailer();
	$mail->From = "no-reply@go.net";
	$mail->FromName = $user_full_name;
	$mail->AddAddress($to);
	$mail->Subject = "Purchase: {$item_title} ({$qty}) | {$user_full_name} {$username}";
	if (!empty($recipient_id)) {
		$recipient = get_userdata($recipient_id);
		$recipient_full_name = "{$recipient->first_name} {$recipient->last_name}";
		$recipient_username = $recipient->user_login;
		$mail->Subject .= " | {$recipient_full_name} {$recipient_username}";
	}
	$mail->Body = "{$user_email}\n\n{$currency} Spent: {$req_currency}\n\n{$points} Spent: {$req_points}\n\nTime Spent: {$req_minutes}";
	$mail->WordWrap = 50;

	if (!$mail->Send()) {
		if ((is_array($user_role) && in_array('administrator', $user_role)) || $user_role === 'administrator') {
			return "<div id='go_mailer_error_msg'>{$mail->ErrorInfo}</div>";
		}
	}
}
?>