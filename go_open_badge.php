<?php 

add_shortcode( 'go_award_badge', 'go_award_badge' );

function go_media_upload_tab_name( $tabs ) {
    $newtab = array( 'tab_create_badge' => 'Create Badge' );
    return array_merge( $tabs, $newtab );
}

function go_media_badge_list( $tabs ) {
    $newtab = array( 'tab_badge_list' => 'Badges List' );
    return array_merge( $tabs, $newtab );
}

function go_badge_add_attachment( $form_fields, $post ) {
    $form_fields['location'] = array(
        'value' => "[go_award_badge id='{$post->ID}' repeat = 'off']",
        'label' => __( 'Shortcode' ),
		'input'       => 'text'
    );
    return $form_fields;	
}

function go_award_badge( $atts ) {
	global $wpdb;
	global $go_notify_counter;
	$go_notify_counter++;
	$space = $go_notify_counter*85;
	
	$id = $atts['id'];
	$repeat = $atts['repeat'] ? $atts['repeat'] : 'off';
	$user_id = $atts['uid'] ? $atts['uid'] : get_current_user_id();
	
	$display = wp_get_attachment_image( $id, array( 200, 200 ), false );
	$existing_badges = get_user_meta( $user_id, 'go_badges', true);
	if ( $repeat == 'off' ) {
		if ( empty( $existing_badges ) || ! in_array( $id, $existing_badges ) ) {
			$existing_badges[] = $id;
			update_user_meta( $user_id, 'go_badges', $existing_badges );
			if ( $user_id == get_current_user_id() ) {
				echo '
				<div id="go_notification_badges" class="go_notification go_notification_badges" style="background: none; top: '.$space.'px;">'.$display.'</div>
				<script type="text/javascript" language="javascript">
					go_notification(3000, jQuery( "#go_notification_badges" ) );
				</script>';
			}
		}
	} else { 
		$existing_badges[] = $id;
		update_user_meta( $user_id, 'go_badges', $existing_badges );
		if ( $user_id == get_current_user_id() ) {
			echo '
			<div id="go_notification_badges" class="go_notification go_notification_badges" style="background: none;">'.$display.'</div>
			<script type="text/javascript" language="javascript">
				go_notification(3000, jQuery( "#go_notification_badges" ) );
			</script>';
		}
	}
	$badge_count = count ( get_user_meta( $user_id, 'go_badges', true ) );
	$wpdb->update( "{$wpdb->prefix}go_totals", array( 'badge_count' => $badge_count ), array( 'uid' => $user_id ) );
}

function go_remove_badge( $user_id, $badge_id ) {
	global $wpdb;
	$existing_badges = get_user_meta( $user_id, 'go_badges', true );
	unset( $existing_badges[ array_search( $badge_id, $existing_badges ) ] );
	$badge_count = go_return_badge_count( $user_id ) - 1;
	$wpdb->update( "{$wpdb->prefix}go_totals", array( 'badge_count' => $badge_count ), array( 'uid' => $user_id ) );
	update_user_meta( $user_id, 'go_badges', $existing_badges );
}

?>