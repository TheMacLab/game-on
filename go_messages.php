<?php
function go_messages_bar() {
	if ( ! is_admin_bar_showing() || ! is_user_logged_in() ) {
		return;
	}
	global $wpdb;
	global $wp_admin_bar;
	$messages = get_user_meta( get_current_user_id(), 'go_admin_messages', true );
	$msg_count = intval( $messages[0] );
	$msg_array = ( ! empty( $messages[1] ) ? $messages[1] : null );
	if ( $msg_count > 0 ) {
		$style = 'background: #ff0000;';
		if ( 1 === $msg_count ) {
			$wp_admin_bar->add_menu( 
				array(
					'id' => 'go_messages_blurb',
					'title' => 'New message from admin',
					'href' => '#',
					'parent' => 'go_messages'
				) 
			);
		} else {
			$wp_admin_bar->add_menu( 
				array(
					'id' => 'go_messages_blurb',
					'title' => 'New messages from admin',
					'href' => '#',
					'parent' => 'go_messages'
				) 
			);
		}
	} else {
		$style = 'background: #222222;';
		$wp_admin_bar->add_menu( 
			array(
				'id' => 'go_messages_blurb',
				'title' => 'No new messages from admin',
				'href' => '#',
				'parent' => 'go_messages'
			) 
		);
	}
	$wp_admin_bar->add_menu( 
		array(
			'id' => 'go_messages',
			'title' => 
				"<div style='padding-top:5px;'>" .
					"<div id='go_messages_bar' style='{$style}'>" .
						"{$msg_count}" .
					"</div>" .
				"</div>",
			'href' => '#',
		)
	);
	if ( ! empty( $msg_array ) ) {
		foreach ( $msg_array as $date => $message_obj ) {
			$msg_body = $message_obj[0];

			// $message_obj[1] will contain 0 (int) when read or 1 (int) when unread
			$msg_already_seen = ( empty( $message_obj[1] ) ? true : false );
			
			// if the message has already been read, apply no styling
			$style = ( $msg_already_seen ? '' : 'color: red;' );
			$formatted_date = date( 'm-d-Y', $date );
			$seen_elem = '';
			$title = '';

			if ( preg_match( "/[<>]+/", $msg_body ) ) {
				$title = preg_replace( "/(<a[^>]+>|<\/a>)+/", '', $msg_body );
			} else {
				$title = $msg_body;
			}

			if ( ! $msg_already_seen ) {
				$seen_elem = "{$formatted_date} " .
					"<a class='go_messages_anchor' " .
							"onClick='go_mark_seen({$date}, \"unseen\" ); " .
								"go_change_seen({$date}, \"unseen\", this);' " .
							"style='display: inline;' " .
							"href='#' >" .
						"Mark Read" .
					"</a> " .
					"<a class='go_messages_anchor' " .
							"onClick='go_mark_seen({$date}, \"remove\" );' " .
							"style='display:inline;' " .
							"href='#' >" .
						"Delete" .
					"</a>";
			} else {
				$seen_elem = "{$formatted_date} " .
					"<a class='go_messages_anchor' " .
							"onClick='go_mark_seen({$date}, \"seen\" ); " .
								"go_change_seen({$date}, \"seen\", this);' " .
							"style='display: inline;' " .
							"href='#' >" .
						"Mark Unread".
					"</a> ".
					"<a class='go_messages_anchor' ".
							"onClick='go_mark_seen({$date}, \"remove\" );' " .
							"style='display:inline;' ".
							"href='#' >".
						"Delete".
					"</a>";
			}

			$wp_admin_bar->add_menu( 
				array(
					'id' => $date,
					'title' => "<div style='{$style}'>{$title}...</div>",
					'href' => '#',
					'parent' => 'go_messages'
				) 
			);

			$wp_admin_bar->add_menu( 
				array(
					'id' => rand(),
					'title' => $seen_elem,
					'parent' => $date,
					'meta' => array( 
						'html' => 
							"<div class='go_message_container' style='width:350px;'>".
								$msg_body .
							"</div>",
						'class' => 'go_message_item'
					)
				) 
			);
		}
	}
}

function go_mark_read() {
	global $wpdb;
	$messages = get_user_meta(get_current_user_id(), 'go_admin_messages', true );
	if ( $_POST['type'] == 'unseen' ) {
		if ( $messages[1][ $_POST['date'] ][1] == 1) {
			$messages[1][ $_POST['date'] ][1] = 0;
			(int) $messages[0] = (int) $messages[0] - 1;
		}
	} elseif ( $_POST['type'] == 'remove' ) {
		if ( $messages[1][ $_POST['date'] ][1] == 1) {
			(int) $messages[0] = (int) $messages[0] - 1;
		}	
		unset( $messages[1][ $_POST['date'] ] );
	} elseif ( $_POST['type'] == 'seen' ) {
		if ( $messages[1][ $_POST['date'] ][1] == 0) {
			$messages[1][ $_POST['date'] ][1] = 1;
			(int) $messages[0] = (int) $messages[0] + 1;
		}
	}
	update_user_meta( get_current_user_id(), 'go_admin_messages', $messages );
	echo JSON_encode( array (0 => $_POST['date'], 1 => $_POST['type'], 2 => $messages[0] ) );
	die();
}

function go_message_user( $user_id, $message ) {
	date_default_timezone_set( 'America/Los_Angeles' );
	$timestamp = time();
	$current_messages = get_user_meta( $user_id, 'go_admin_messages',true );
	$current_messages[1][ $timestamp ] = array( $message, 1 );
	$is_admin = false;
	$admin_user = get_user_by( 'id', get_current_user_id() );
	if ( ! empty( $admin_user ) ) {
		$admin_user_roles = $admin_user->roles;
		if ( is_array( $admin_user_roles ) ) {
			foreach ( $admin_user_roles as $key => $role ) {
				if ( $role === 'administrator' ) {
					$is_admin = true;
					break;
				}
			}
		}
	}
	$message_time = date( "m/d @ h:i", $timestamp );
	$admin_messages = get_user_meta(get_current_user_id(), 'go_admin_message_history', true );
	$admin_messages[ $user_id ][] = array(
		'message' => $message,
		'time' => $message_time
	);
	krsort( $admin_messages[ $user_id ] );
	if ( $is_admin = true ) {
		update_user_meta( $admin_user->ID, 'go_admin_message_history', $admin_messages );
	}
	krsort( $current_messages[1] );
	if (count( $current_messages[1] ) > 9) {
		array_pop( $current_messages[1] );
	}
	if ( ! $current_messages[0] ) {
		$current_messages[0] = 1;
	} else {
		(int) $current_messages[0] = (int) $current_messages[0] + 1;
		if ( (int) $current_messages[0] > 9 ) {
			(int) $current_messages[0] = 9;
		}
	}
	update_user_meta( $user_id, 'go_admin_messages', $current_messages );
}

?>