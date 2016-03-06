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
        'value' => "[go_award_badge id={$post->ID} repeat=false]",
        'label' => __( 'Shortcode' ),
		'input' => 'text'
    );
    return $form_fields;	
}

function go_award_badge_r ( $user_id, $new_rank_index, $rank_count, $badges_array = null ) {
	if ( ! empty( $user_id ) && $new_rank_index > 0 && $rank_count >= 1 ) {
		if ( empty( $badges_array ) ) {
			$ranks = get_option( 'go_ranks' );
			$badges_array = $ranks['badges'];
		}
		$old_rank_index = $new_rank_index - $rank_count;

		// iterate down the ranks badge array, removing each of the user's badges
		for ( $rank_index = $old_rank_index; $rank_index <= $new_rank_index; $rank_index++ ) {
			$badge_id = $badges_array[ $rank_index ];
			if ( null !== $badge_id ) {
				go_award_badge(
					array(
						'id' 		=> $badge_id,
						'repeat' 	=> false,
						'uid' 		=> $user_id
					)
				);
			}
		}
	}
}

function go_award_badge ( $atts ) {
	$defaults = array(
		'id' => null,
		'repeat' => false,
		'uid' => get_current_user_id()
	);
	$atts = shortcode_atts( $defaults, $atts );

	$badge_id = $atts['id'];
	$repeat = $atts['repeat'];
	$user_id = $atts['uid'];

	if ( ! empty( $badge_id ) ) {
		global $wpdb;
		global $go_notify_counter;
		$go_notify_counter++;
		$space = $go_notify_counter * 85;
		
		$display = wp_get_attachment_image( $badge_id, array( 200, 200 ), false );
		$existing_badges = get_user_meta( $user_id, 'go_badges', true);
		if ( false === $repeat ) {
			if ( empty( $existing_badges ) || ! in_array( $badge_id, $existing_badges ) ) {
				$existing_badges[] = $badge_id;
				update_user_meta( $user_id, 'go_badges', $existing_badges );
				if ( $user_id == get_current_user_id() ) {
					echo '
					<div id="go_notification_badges" class="go_notification go_notification_badges" style="background: none; top: '.$space.'px;">'.$display.'</div>
					<script type="text/javascript" language="javascript">
						go_notification(3000, jQuery( "#go_notification_badges" ) );
					</script>';
				}
			}
		} else if ( true === $repeat ) {
			$existing_badges[] = $badge_id;
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
}

function go_remove_badge_r ( $user_id, $old_rank_index, $rank_count, $badges_array = null ) {
	if ( ! empty( $user_id ) && $old_rank_index > 0 && $rank_count >= 1 ) {
		if ( empty( $badges_array ) ) {
			$ranks = get_option( 'go_ranks' );
			$badges_array = $ranks['badges'];
		}
		$new_rank_index = $old_rank_index - $rank_count;

		// iterate down the ranks badge array, removing each of the user's badges
		for ( $rank_index = $old_rank_index; $rank_index > $new_rank_index; $rank_index-- ) {
			$badge_id = $badges_array[ $rank_index ];
			if ( 0 !== $badge_id ) {
				go_remove_badge( $user_id, $badge_id );
			}
		}
	}
}

function go_remove_badge ( $user_id, $badge_id = -1 ) {
	if ( ! empty( $user_id ) && ! empty( $badge_id ) && ! empty( $badge_id ) && $badge_id > 0 ) {
		global $wpdb;
		$existing_badges = get_user_meta( $user_id, 'go_badges', true );
		$badge_count = count( $existing_badges );
		$matching_badge_id = array_search( $badge_id, $existing_badges );
		if ( false !== $matching_badge_id && $matching_badge_id > -1 ) {
			unset( $existing_badges[ $matching_badge_id ] );
			update_user_meta( $user_id, 'go_badges', $existing_badges );
			$new_badge_count = $badge_count - 1;
			$wpdb->update( "{$wpdb->prefix}go_totals", array( 'badge_count' => $new_badge_count ), array( 'uid' => $user_id ) );
		}
	}
}

?>