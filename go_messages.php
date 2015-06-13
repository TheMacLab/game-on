<?php

add_action( 'admin_bar_init','go_messages_bar' );
function go_messages_bar() {
	global $wpdb;
	global $wp_admin_bar;
	$messages = get_user_meta( get_current_user_id(), 'go_admin_messages', true );
	$msg_count = intval( $messages[0] );
	if ( $messages[0] > 0) {
		$style = 'background: #ff0000;';
		if ( $messages[0] == 1 ) {
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
	if ( ! is_admin_bar_showing() || ! is_user_logged_in() ) {
		return;
	}
	$wp_admin_bar->add_menu( 
		array(
			'id' => 'go_messages',
			'title' => '<div style="padding-top:5px;"><div id="go_messages_bar" style="'.$style.'">'.(int) $messages[0].'</div></div>',
			'href' => '#',
		) 
	);
	if ( ! empty( $messages[1] ) ) {
		foreach ( $messages[1] as $date => $values ) {
			if ( preg_match( "/[<>]+/", $values[0] ) ) {
				$title_temp = preg_replace( "/(<a\s?href=\".*\">)+/", '', $values[0] );
				$title = preg_replace( "/(<\/a>)+/", '', $title_temp );
			} else {
				$title = $values[0];
			}
			$style = '';
			$is_seen = true;
			if ( (int) $values[1] == 1 ) {
				$style = 'color: red;';
				$is_seen = false;
			}
			if ( $is_seen == false ) {
				$seen_elem = date( 'm-d-Y', $date )." <a class='go_messages_anchor' onClick='go_mark_seen({$date}, \"unseen\" ); go_change_seen({$date}, \"unseen\", this);' style='display: inline;' href='#'>Mark Read</a> <a class='go_messages_anchor' onClick='go_mark_seen({$date}, \"remove\" );' style='display:inline;' href='#'>Delete</a>";
			} else {
				$seen_elem = date( 'm-d-Y', $date )." <a class='go_messages_anchor' onClick='go_mark_seen({$date}, \"seen\" ); go_change_seen({$date}, \"seen\", this);' style='display: inline;' href='#'>Mark Unread</a> <a class='go_messages_anchor' onClick='go_mark_seen({$date}, \"remove\" );' style='display:inline;' href='#'>Delete</a>";
			}
			$wp_admin_bar->add_menu( 
				array(
					'id' => $date,
					'title' => '<div style="'.$style.'">'.$title.'...</div>',
					'href' => '#',
					'parent' => 'go_messages',
				) 
			);
			$wp_admin_bar->add_menu( 
				array(
					'id' => rand(),
					'title' => $seen_elem,
					'parent' => $date,
					'meta' => array( 
						'html' =>  '<div class="go_message_container" style="width:350px;">'.$values[0].'</div>',
						'class' => 'go_message_item'
					),
				) 
			);
		}
	}
}

add_action( 'wp_ajax_go_mark_read','go_mark_read' );
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