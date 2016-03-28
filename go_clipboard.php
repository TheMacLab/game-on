<?php

function go_clipboard() {
	add_submenu_page( 'game-on-options.php', 'Clipboard', 'Clipboard', 'manage_options', 'go_clipboard', 'go_clipboard_menu' );
}

function go_clipboard_menu() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} else {
	?>
		<div id="records_tabs">
			<ul>
				<li><a href="#clipboard_wrap">Clipboard</a></li>
				<li><a href="#clipboard_messages_wrap">Messages</a></li>
			</ul>
			<div id="clipboard_wrap">
				<select class="menuitem" id="go_clipboard_class_a_choice" onchange="go_clipboard_class_a_choice();">
					<option>...</option>
					<?php
					$class_a = get_option( 'go_class_a' );
					if ( $class_a ) {
						foreach ( $class_a as $key => $value ) {
							echo "<option class='ui-corner-all'>{$value}</option>";
						}
					}
					?>
				</select>
			
				<div id="go_clipboard_add">
					<?php go_options_help( 'http://maclab.guhsd.net/go/video/clipboard/clipboard.mp4', 'Clipboard Help' ); ?>
					<label for="go_clipboard_points"><?php echo go_return_options( 'go_points_name' ); ?>: </label><input name="go_clipboard_points" id="go_clipboard_points" class='go_clipboard_add'/> 
					<label for="go_clipboard_currency"><?php echo go_return_options( 'go_currency_name' ); ?>: </label><input name="go_clipboard_currency" id="go_clipboard_currency" class='go_clipboard_add'/>
					<label for="go_clipboard_bonus_currency"><?php echo go_return_options( 'go_bonus_currency_name' ); ?>: </label> <input name="go_clipboard_bonus_currency" id="go_clipboard_bonus_currency" class='go_clipboard_add'/>
					<label for="go_clipboard_penalty"><?php echo go_return_options( 'go_penalty_name' ); ?>: </label><input name="go_clipboard_penalty" id="go_clipboard_penalty" class='go_clipboard_add'/>
					<label for="go_clipboard_minutes"><?php echo go_return_options( 'go_minutes_name' ); ?>: </label><input name="go_clipboard_minutes" id="go_clipboard_minutes" class='go_clipboard_add'/>
					<label for="go_clipboard_badge">Badge ID:</label><input name="go_clipboard_badge" id="go_clipboard_badge" class='go_clipboard_add'/><br/>
					<label name="go_clipboard_reason">Message: </label>
					<div>
						<textarea name="go_clipboard_reason" id="go_clipboard_reason" placeholder='See me'></textarea><br/>
						<button class="ui-button-text" id="go_send_message" onclick="go_clipboard_add();">Add</button>
						<button id="go_fix_messages" onclick="fixmessages()">Fix Messages</button>
					</div>
				
					<table id="go_clipboard_table" class="pretty">
						<thead>
							<tr>
								<th><input type="checkbox" onClick="go_toggle(this);" /></th>
								<th class="header"><a href="#" >ID</a></th>
								<th class="header"><a href="#" ><?php echo go_return_options( 'go_class_b_name' ); ?></a></th>
								<th class="header"><a href="#" >Student Name</a></th>
								<th class="header"><a href="#" >Display Name</a></th>
								<th class="header"><a href="#" ><?php echo go_return_options( 'go_level_names' ); ?></a></th>
								<?php if ( go_return_options( 'go_focus_switch' ) == 'On' ) { ?><th class="header"><a href="#" ><?php echo go_return_options( 'go_focus_name' ); ?></a></th><?php } ?>
								<th class="header"><a href="#"><?php echo go_return_options( 'go_points_name' ); ?></a></th>
								<th class="header"><a href="#"><?php echo go_return_options( 'go_currency_name' ); ?></a></th>
								<th class="header"><a href="#"><?php echo go_return_options( 'go_bonus_currency_name' ); ?></a></th>
								<th class="header"><a href="#"><?php echo go_return_options( 'go_penalty_name' ); ?></a></th>
								<th class="header"><a href="#"><?php echo go_return_options( 'go_minutes_name' ); ?></a></th>
								<th class="header"><a href="#"><?php echo go_return_options( 'go_badges_name' ); ?></a></th>
							</tr>
						</thead>
						<tbody id="go_clipboard_table_body"></tbody>
					</table>
				</div>
			</div>
			<div id="clipboard_messages_wrap">
				<select class="menuitem" id="go_clipboard_class_a_choice_messages" onchange="go_clipboard_class_a_choice_messages();">
					<option>...</option>
					<?php
					$class_a_messages = get_option( 'go_class_a' );
					if ( $class_a_messages ) {
						foreach ( $class_a_messages as $key => $value ) {
							echo "<option class='ui-corner-all'>{$value}</option>";
						}
					}
					?>
					<option>All</option>
				</select>
				<table id="go_clipboard_messages" class="pretty">
					<thead>
						<tr>
							<th class="header" width="5%" id="messages_id"><a href="#" >ID</a></th>
							<th class="header" width="5%" id="messages_computer"><a href="#"><?php echo go_return_options( 'go_class_b_name' ); ?></a></th>
							<th class="header" width="6.5%" id="messages_student"><a href="#">Student Name</a></th>
							<th class="header" width="6.5%" id="messages_display"><a href="#">Display Name</a></th>
							<th class="header" width="6%" id="messages_date"><a href="#">Date</a></th>
							<th class="header" id="messages_message"><a href="#">Message</a></th>
						</tr>
					</thead>
					<tbody id="go_clipboard_messages_body"></tbody>
				</table>
			</div>
		</div>
	<?php
	}
}

function go_clipboard_intable() {
	global $wpdb;
	$class_a_choice = $_POST[ 'go_clipboard_class_a_choice' ];
	$table_name_user_meta = $wpdb->prefix.'usermeta';
	$uid = $wpdb->get_results( "SELECT user_id 
		FROM {$table_name_user_meta} 
		WHERE meta_key = '{$wpdb->prefix}capabilities' 
		AND meta_value NOT LIKE '%administrator%'"
	);
	foreach ( $uid as $id ) {
		$class_a = get_user_meta( $id->user_id, 'go_classifications', true );
		if ( ! empty( $class_a[ $class_a_choice ] ) ) {
			$user_data_key = get_userdata( $id->user_id ); 
			$user_login = $user_data_key->user_login;
			$user_display = $user_data_key->display_name;
			$user_first_name = $user_data_key->user_firstname;
			$user_last_name =  $user_data_key->user_lastname;
			$user_url =  $user_data_key->user_url;
			if ( go_return_options( 'go_focus_switch' ) == 'On' ) {
				$user_focuses = go_display_user_focuses( $id->user_id );
				$focus_name = get_option( 'go_focus_name' );
				$focuses = get_option( 'go_focus' );
				$focuses_list = "<option>{$user_focuses}</option><option ".( ( empty( $user_focuses) || $user_focuses == "No {$focus_name}" ) ? "selected" : '' ).">No {$focus_name}</option>";
				foreach ( $focuses as $focus ) {
					$focuses_list .= "<option value='".esc_attr( $focus )."' >{$focus}</option>";
				}
			}
			$bonus_currency = go_return_bonus_currency( $id->user_id );
			$penalty = go_return_penalty( $id->user_id );
			$minutes = go_return_minutes( $id->user_id );
			$currency = go_return_currency( $id->user_id );
			$points = go_return_points( $id->user_id );
			$badge_count = go_return_badge_count( $id->user_id );
			$rank = go_get_rank( $id->user_id );
			$current_rank = $rank['current_rank'];
			
			echo "<tr id='user_{$id->user_id}'>
					<td><input class='go_checkbox' type='checkbox' name='go_selected' value='{$id->user_id}'/></td>
					<td><span><a href='#' onclick='go_admin_bar_stats_page_button(&quot;{$id->user_id}&quot;);'>{$user_login}</a></td>
					<td>{$class_a[ $class_a_choice]}</td>
					<td><a href='{$user_url}' target='_blank'>{$user_last_name}, {$user_first_name}</a></td>
					<td>{$user_display}</td>
					<td>{$current_rank}</td>
					".( (go_return_options( 'go_focus_switch' ) == 'On' ) ? "<td><select class='go_focus' onchange='go_user_focus_change(&quot;{$id->user_id}&quot;, this);'>{$focuses_list}</select</td>" : '' )."
					<td class='user_points'>{$points}</td>
					<td class='user_currency'>{$currency}</td>
					<td class='user_bonus_currency'>{$bonus_currency}</td>
					<td class='user_penalty'>{$penalty}</td>
					<td class='user_minutes'>{$minutes}</td>
					<td class='user_badge_count'>{$badge_count}</td>
				  </tr>";
		}
	}
	die();
}

function go_clipboard_intable_messages() {
	global $wpdb;
	$admin_messages = get_user_meta(get_current_user_id(), 'go_admin_message_history', true );
	foreach ( $admin_messages as $student_id => $messages ) {
		$user_data_key = get_userdata( $student_id );
		$class_a_messages = get_user_meta( $student_id, 'go_classifications', true );
		$class_a_choice_messages = $_POST['go_clipboard_class_a_choice_messages'];
		$user_login = $user_data_key->user_login;
		$user_display = $user_data_key->display_name;
		$user_first_name = $user_data_key->user_firstname;
		$user_last_name =  $user_data_key->user_lastname;
		$user_url =  $user_data_key->user_url;
		$array_count = count( $messages );
		if ( ! empty( $class_a_messages[ $class_a_choice_messages ] ) ) {
			for ( $i = $array_count - 1; $i >= 0; $i-- ) {
				echo "<tr id='user_{$student_id}'>
					<td><span><a href='#' onclick='go_admin_bar_stats_page_button(&quot;{$student_id}&quot;);'>{$user_login}</a></td>
					<td>{$class_a_messages[$class_a_choice_messages]}</td>
					<td><a href='{$user_url}' target='_blank'>{$user_last_name}, {$user_first_name}</a></td>
					<td>{$user_display}</td>
					<td>{$messages[$i]['time']}</td>
					<td class='message'>{$messages[$i]['message']}</td>
					</tr>";
			}
		}  elseif ( $class_a_choice_messages == 'All' ) {
			if ( ! empty( $class_a_messages ) ) {
				foreach ( $class_a_messages as $key => $computer ) {
					for ( $i = $array_count - 1; $i >= 0; $i-- ) {
						echo "<tr id='user_{$student_id}'>
							<td><span><a href='#' onclick='go_admin_bar_stats_page_button(&quot;{$student_id}&quot;);'>{$user_login}</a></td>
							<td>{$computer}</td>
							<td><a href='{$user_url}' target='_blank'>{$user_last_name}, {$user_first_name}</a></td>
							<td>{$user_display}</td>
							<td>{$messages[$i]['time']}</td>
							<td class='message'>{$messages[$i]['message']}</td>
							</tr>";
					}
				}
			}
		}
	}
	die();
}
 
function go_clipboard_add() {
	$ids = $_POST['ids'];
	$points = intval( $_POST['points'] );
	$currency = intval( $_POST['currency'] );
	$bonus_currency = intval( $_POST['bonus_currency'] );
	$penalty = intval( $_POST['penalty'] );
	$minutes = intval( $_POST['minutes'] );
	$reason = $_POST['reason'];
	$badge_id = intval( $_POST['badge_ID'] );
	$status = 6;
	$bonus_loot_default = null;
	$undo_default = false;
	$show_notification = false;

	$output_data = array(
		"update_status" => false
	);

	foreach ( $ids as $key => $user_id ) {
		$user_id = intval( $user_id );
		if ( '' != $reason ) {
			if ( null != $badge_id ) {
				go_award_badge(
					array(
						'id' 		=> $badge_id,
						'repeat' 	=> false,
						'uid' 		=> $user_id
					)
				);
			}
			go_update_totals(
				$user_id,
				$points,
				$currency,
				$bonus_currency,
				$penalty,
				$minutes,
				$status,
				$bonus_loot_default,
				$undo_default,
				$show_notification
			);
			go_message_user( $user_id, $reason );

			// returning information to the AJAX call to update the clipboard
			$new_point_total = go_return_points( $user_id );
			$new_currency_total = go_return_currency( $user_id );
			$new_bonus_currency_total = go_return_bonus_currency( $user_id );
			$new_penalty_total = go_return_penalty( $user_id );
			$new_minute_total = go_return_minutes( $user_id );
			$new_badge_count = go_return_badge_count( $user_id );

			$output_data[ 'update_status' ] = true;
			$output_data[ $user_id ] = array(
				"points" => $new_point_total,
				"currency" => $new_currency_total,
				"bonus_currency" => $new_bonus_currency_total,
				"penalty" => $new_penalty_total,
				"minutes" => $new_minute_total,
				"badge_count" => $new_badge_count
			);
		}
	}
	wp_die( json_encode( $output_data ) );
}

function go_update_user_focuses() {
	$new_user_focus = stripslashes( $_POST['new_user_focus'] );
	$user_id = $_POST['user_id'];
	if ( $new_user_focus != 'No '.go_return_options( 'go_focus_name' ) ) {
		update_user_meta( $user_id, 'go_focus', array( $new_user_focus ) );
	} else {
		update_user_meta( $user_id, 'go_focus', array() );
	}
	echo $new_user_focus;
	die();	
}

function fixmessages() {
	global $wpdb;
	$users = get_users(array( 'role' => 'Subscriber' ) );
	foreach ( $users as $user ) {
		$messages = get_user_meta( $user->ID, 'go_admin_messages',true );
		$messages_array = $messages[1];
		$messages_unread = array_values( $messages_array );
		$messages_unread_count = 0;
		foreach ( $messages_unread as $message_unread ) {
			if ( $message_unread[1] == 1) {
				$messages_unread_count++;	
			}
		}
		if ( $messages[0] != $message_unread_count ) {
			$messages[0] = $messages_unread_count;
			update_user_meta( $user->ID, 'go_admin_messages', $messages );
		}
	}
	
	die();
}
?>
