<?php
function go_buy_item() { 
	global $wpdb;
	$go_table_name = $wpdb->prefix."go";
	$post_id = ( ! empty( $_POST["the_id"] ) ? (int) $_POST["the_id"] : 0 );
	$qty = ( ! empty( $_POST['qty'] ) ? (int) $_POST['qty'] : 0 );
	$current_purchase_count = ( ! empty( $_POST['purchase_count'] ) ? (int) $_POST['purchase_count'] : 0 );

	if ( ! empty( $_POST['recipient'] ) ) {
		$recipient = sanitize_text_field( $_POST['recipient'] );
		$recipient_id = $wpdb->get_var( "SELECT id FROM {$wpdb->users} WHERE display_name='{$recipient}'" );
		$recipient_purchase_count = $wpdb->get_var( "SELECT count FROM {$go_table_name} WHERE post_id={$post_id} AND uid={$recipient_id} LIMIT 1" );
	}
	
	$user_id = get_current_user_id(); 
	$custom_fields = get_post_custom( $post_id );
	$sending_receipt = ( ! empty( $custom_fields['go_mta_store_receipt'][0] ) ? $custom_fields['go_mta_store_receipt'][0] : false );

	$store_cost = ( ! empty( $custom_fields['go_mta_store_cost'][0] ) ? unserialize( $custom_fields['go_mta_store_cost'][0] ) : null );
	if ( ! empty( $store_cost ) ) {
		$req_currency = $store_cost[0];
		$req_points = $store_cost[1];
		$req_bonus_currency = $store_cost[2];
		$req_penalty = $store_cost[3];
		$req_minutes = $store_cost[4];
	}
	$penalty = ( ! empty( $custom_fields['go_mta_penalty_switch'] ) ? $custom_fields['go_mta_penalty_switch'] : false );

	$store_limit = ( ! empty( $custom_fields['go_mta_store_limit'][0] ) ? unserialize( $custom_fields['go_mta_store_limit'][0] ) : null );
	if ( ! empty( $store_limit ) ) {
		$is_limited = $store_limit[0];
		if ( $is_limited ) {
			$limit = (int) $store_limit[1];
		}
	}

	$store_filter = ( ! empty( $custom_fields['go_mta_store_filter'][0] ) ? unserialize( $custom_fields['go_mta_store_filter'][0] ) : null );
	if ( ! empty( $store_filter ) ) {
		$is_filtered = (bool) $store_filter[0];
		if ( $is_filtered ) {
			$req_rank = $store_filter[1];
			$bonus_filter = $store_filter[2];
		}
	}
	
	$store_exchange = ( ! empty( $custom_fields['go_mta_store_exchange'][0] ) ? unserialize( $custom_fields['go_mta_store_exchange'][0] ) : null );
	if ( ! empty( $store_exchange ) ) {
		$is_exchangeable = (bool) $store_exchange[0];
		if ( $is_exchangeable ) {
			$exchange_currency = $store_exchange[1];
			$exchange_points = $store_exchange[2];
			$exchange_bonus_currency = $store_exchange[3];
			$exchange_minutes = $store_exchange[4];
		}
	}
	$item_url = ( ! empty( $custom_fields['go_mta_store_item_url'][0] ) ? $custom_fields['go_mta_store_item_url'][0] : null );
	$badge_id = ( ! empty( $custom_fields['go_mta_badge_id'][0] ) ? $custom_fields['go_mta_badge_id'][0] : null );

	
	$item_focus_array = ( ! empty( $custom_fields['go_mta_store_focus'][0] ) ? unserialize( $custom_fields['go_mta_store_focus'][0] ) : null );
	if ( ! empty( $item_focus_array ) ) {
		$is_focused = (bool) filter_var( $item_focus_array[0], FILTER_VALIDATE_BOOLEAN );
		if ( $is_focused ) {
			$item_focus = $item_focus_array[1];	
		}
	}

	$repeat = 'off';
	
	$cur_currency = go_return_currency( $user_id );
	$cur_points = go_return_points( $user_id );
	$cur_bonus_currency = go_return_bonus_currency( $user_id );
	$cur_penalty = go_return_penalty( $user_id );
	$cur_minutes = go_return_minutes( $user_id );
	
	$enough_currency = check_values( $req_currency, $cur_currency );
	$enough_points = check_values( $req_points, $cur_points );
	$enough_bonus_currency = check_values( $req_bonus_currency, $cur_bonus_currency );
	$enough_penalty = check_values( $req_penalty, $cur_penalty );
	$enough_minutes = check_values( $req_minutes, $cur_minutes );

	$within_limit = true;
	if ( ! empty( $limit ) && $is_limited === "true" ) {
		$qty_diff = $limit - $current_purchase_count - $qty;
		if ( $qty_diff < 0 ) {
			$within_limit = false;
		}
	}

	if ( ( ( $enough_currency && $enough_bonus_currency && $enough_points && $enough_minutes ) || $penalty) && $within_limit ) {
		if ( $is_focused && ! empty( $item_focus ) ) {
			$user_focuses = get_user_meta( $user_id, 'go_focus', true );
			if ( array_search( 'No '.go_return_options( 'go_focus_name' ), $user_focuses ) ) {
				unset( $user_focuses[ array_search( 'No '.go_return_options( 'go_focus_name' ), $user_focuses ) ] );
			}
			$user_focuses[] = $item_focus;
			update_user_meta( $user_id, 'go_focus', $user_focuses );
		}
		if ( ! empty( $recipient_id ) ) {
			$curr_user_obj = get_userdata( $user_id );
			go_message_user( $recipient_id, $curr_user_obj->display_name." has purchased {$qty} <a href='javascript:;' onclick='go_lb_opener({$post_id})'>".get_the_title( $post_id )."</a> for you." );
			if ( $exchange_currency || $exchange_points || $exchange_bonus_currency || $exchange_minutes ) {
				go_add_post( $recipient_id, $post_id, -1, $exchange_points, $exchange_currency, $exchange_bonus_currency, $exchange_minutes, null, $repeat );
				go_add_bonus_currency( $recipient_id, $exchange_bonus_currency, $curr_user_obj->display_name." purchase of {$qty} ".get_the_title( $post_id )."." );
			} else {
				go_add_post( $recipient_id, $post_id, -1,  0,  0, 0, null, $repeat );
			}
			go_add_post( $user_id, $post_id, -1, -$req_points, -$req_currency, -$req_bonus_currency, -$req_minutes, null, $repeat );
			$wpdb->query( $wpdb->prepare( "UPDATE {$go_table_name} SET reason = 'Gifted' WHERE uid = %d AND status = %d AND gifted = %d AND post_id = %d ORDER BY timestamp DESC, reason DESC, id DESC LIMIT 1", $user_id, -1, 0, $post_id ) );
		} else {
			go_add_post( $user_id, $post_id, -1, -$req_points, -$req_currency, -$req_bonus_currency, -$req_minutes, null, $repeat );
			if ( ! empty( $req_penalty ) ) {
				go_add_penalty( $user_id, -$req_penalty, get_the_title( $post_id ) );	
			}
		}
		if ( ! empty( $badge_id ) ) {
			go_award_badge(
				array(
					'id' 		=> $badge_id,
					'repeat' 	=> false,
					'uid' 		=> ( ! empty( $recipient_id ) ? $recipient_id : $user_id )
				)
			);
		}
		if ( ! empty( $item_url ) && isset( $item_url ) ) {
			$item_hyperlink = "<a target='_blank' href='{$item_url}'>Link</a>";
			echo $item_hyperlink;
		} else {
			echo "Purchased";
		}
		if ( $sending_receipt === 'true' ) {
			$receipt = go_mail_item_reciept( $user_id, $post_id, $req_currency, $req_points, $req_bonus_currency, $req_penalty, $req_minutes, $qty, $recipient_id );
			if ( ! empty( $receipt ) ) {
				echo $receipt;
			}
		}
	} else {
		$currency_name = go_return_options( 'go_currency_name' );
		$points_name = go_return_options( 'go_points_name' );
		$bonus_currency_name = go_return_options( 'go_bonus_currency_name' );
		$minutes_name = go_return_options( 'go_minutes_name' );
		$enough_array = array( 
			$currency_name => $enough_currency, 
			$points_name => $enough_points, 
			$bonus_currency_name => $enough_bonus_currency, 
			$minutes_name => $enough_minutes
		);
		$errors = array();
		foreach ( $enough_array as $key => $enough ) {
			if ( ! $enough ) {
				$errors[] = $key;
			}
		}
		if ( ! empty( $errors ) ) {
			$errors = implode( ', ', $errors );
			echo 'Need more '.substr( $errors, 0, strlen( $errors ) );
		}
		if ( $is_limited === "true" && ! $within_limit ) {
			$qty_diff *= -1;
			echo "You've attempted to purchase ".( $qty_diff == 1 ? '1 item' : "{$qty_diff} items" )." greater than the purchase limit.";
		}
	}
	die();
}

function go_mail_item_reciept( $user_id, $item_id, $req_currency, $req_points, $req_bonus_currency, $req_penalty, $req_mintues, $qty, $recipient_id = null ) {
	global $go_plugin_dir;
	$currency = ucwords( go_return_options( 'go_currency_name' ) );
	$points = ucwords( go_return_options( 'go_points_name' ) );
	$bonus_currency = ucwords( go_return_options( 'go_bonus_currency_name' ) );
	$penalty = ucwords( go_return_options( 'go_penalty_name' ) );
	$minutes = ucwords( go_return_options( 'go_minutes_name' ) );
	$item_title = get_the_title( $item_id );
	$allow_full_name = get_option( 'go_full_student_name_switch' );

	$user_info = get_userdata( $user_id );
	$user_login = $user_info->user_login;
	$first_name = trim( $user_info->first_name );
	$last_name = trim( $user_info->last_name );
	if ( $allow_full_name == 'On' ) {
		$user_name = "{$first_name} {$last_name}";
	} else {
		$last_initial = substr( $last_name, 0, 1 );
		$user_name = "{$first_name} {$last_initial}.";
	}
	$user_email = $user_info->user_email;
	$user_role = $user_info->roles;

	$req_currency *= $qty;
	$req_points *= $qty;
	$req_bonus_currency *= $qty;
	$req_penalty *= -1;
	$req_mintues *= $qty;

	$req_array = array(
		$currency => $req_currency, 
		$points => $req_points, 
		$bonus_currency => $req_bonus_currency, 
		$penalty => $req_penalty, 
		$minutes => $req_mintues
	);
	$received_str = '';
	$spent_str = '';
	foreach ( $req_array as $req_name => $val ) {
		if ( ! empty( $val ) ) {
			if ( $req_name === $penalty ) {
				$received_str .= "\t{$req_name}: {$val}\n\n";
			} else {
				if ( $val < 0 ) {
					$received_str .= "\t{$req_name}: ".( -$val )."\n\n";
				} elseif ( $val > 0 ) {
					$spent_str .= "\t{$req_name}: {$val}\n\n";
				}
			}
		}
	}

	$to = get_option( 'go_admin_email','' );
	require( "{$go_plugin_dir}/mail/class.phpmailer.php" );
	$mail = new PHPMailer();
	$mail->From = get_option( 'go_email_from', 'no-reply@go.net' );
	$mail->FromName = $user_name;
	$mail->AddAddress( $to );
	$mail->Subject = "Purchase: {$item_title} ({$qty}) | {$user_name} {$user_login}";
	if ( ! empty( $recipient_id ) ) {
		$recipient = get_userdata( $recipient_id );
		$recipient_username = $recipient->user_login;
		$recipient_first_name = trim( $recipient->first_name );
		$recipient_last_name = trim( $recipient->last_name );
		if ( $allow_full_name == 'On' ) {
			$recipient_full_name = "{$recipient_first_name} {$recipient_last_name}";
		} else {
			$recipient_last_initial = substr( $recipient_last_name, 0, 1 );
			$recipient_full_name = "{$recipient_first_name} {$recipient_last_name}.";
		}
		$mail->Subject .= " | {$recipient_full_name} {$recipient_username}";
	}

	$mail->Body = "{$user_email}\n\n".
		( ! empty( $spent_str ) ? "Spent:\n\n{$spent_str}" : "" ).
		( ! empty( $received_str ) ? "Received:\n\n{$received_str}" : "" );
	$mail->WordWrap = 50;

	if ( ! $mail->Send() ) {
		if ( ( is_array( $user_role ) && in_array( 'administrator', $user_role ) ) || $user_role === 'administrator' ) {
			return "<div id='go_mailer_error_msg'>{$mail->ErrorInfo}</div>";
		}
	}
}
?>