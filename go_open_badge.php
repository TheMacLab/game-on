<?php
/**
 * Add a badge shortcode to every media file.
 *
 * Adds a field on every media file's edit page, so that an admin can easily copy and paste
 * the badge if they'd like to use it in a post/page/task.
 *
 * @param  array $form_fields An array containing the fields that are displayed on the media file's
 *							  edit page.
 * @param  obj 	 $post 		  The WP_Post instance for the media file.
 * @return array The modified array of form fields for the media file.
 */
function go_badge_add_attachment ( $form_fields, $post ) {
    $form_fields['location'] = array(
        'value' => "[go_award_badge id={$post->ID} repeat=false]",
        'label' => __( 'Shortcode' ),
		'input' => 'text'
    );
    return $form_fields;	
}

/**
 * Recursively award badges.
 *
 * Loops through all possible badges and awards them based on the starting and ending rank of the user.
 * If the rank doesn't have a badge id attached to it, the loop skips that rank.
 *
 * @since 2.5.9
 *
 * @param  int   $user_id 		 The user's id.
 * @param  int   $new_rank_index The index of the user's new rank (zero-indexed).
 * @param  int   $rank_count 	 The number of ranks that the user has leveled-up by.
 * @param  array $badges_array 	 Optional. Contains the badge ids of all the ranks, ordered in descending
 *								 order by rank.
 */
function go_award_badge_r ( $user_id, $new_rank_index, $rank_count = 0, $badges_array = null ) {
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

/**
 * Awards an individual badge.
 *
 * Awards a single badge and echos the necessary notification for the badge. Unique badges will not
 * be awarded a second time. This is hooked up to the shortcode "[go_award_badge]" by admins. Calling
 * "do_shortcode('[go_award_badge]')" in the back-end must be avoided unless absolutely necessary;
 * instead, call the function directly.
 *
 * @since 1.0.0
 *
 * @global object $wpdb				 Instance of the wpdb class from WP core.
 * @global int 	  $go_notify_counter Keeps track of the number of notifications on screen and is used
 *									 to space notifications.
 *
 * @param array $atts {
 *		@type int 	  $id 	  Contains the id of the badge to add. IS NOT the user's id.
 *		@type boolean $repeat Whether or not the badge should be unique.
 *		@type int 	  $uid 	  The user's id.
 * }
 */
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

	if ( ! empty( $badge_id ) && is_bool( $repeat ) ) {
		global $wpdb;
		global $go_notify_counter;
		$go_notify_counter++;
		$space = $go_notify_counter * 85;
		
		$display = wp_get_attachment_image( $badge_id, array( 200, 200 ), false );
		$existing_badges = get_user_meta( $user_id, 'go_badges', true );
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

/**
 * Recursively removes badges.
 *
 * Loops through all possible badges and removes them based on the starting and ending rank of the user.
 * If the rank doesn't have a badge id attached to it, the loop skips that rank.
 *
 * @since 2.5.9
 *
 * @param  int 	 $user_id 		 The user's id.
 * @param  int 	 $old_rank_index The index of the user's old rank (zero-indexed).
 * @param  int 	 $rank_count 	 The number of ranks that the user has leveled-down by.
 * @param  array $badges_array 	 Optional. Contains the badge ids of all the ranks, ordered in descending
 *								 order by rank.
 */
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

/**
 * Removes an individual badge.
 *
 * Removes the badge id from the user's meta data and decrements the user's badge count in the
 * "%go_totals" table.
 *
 * @since 2.0.2
 *
 * @global object $wpdb	Instance of the wpdb class from WP core.
 *
 * @param  int $user_id  The user's id.
 * @param  int $badge_id The badge's id.
 */
function go_remove_badge ( $user_id, $badge_id = -1 ) {
	if ( ! empty( $user_id ) && ! empty( $badge_id ) && $badge_id > 0 ) {
		global $wpdb;
		$existing_badges = get_user_meta( $user_id, 'go_badges', true );
		$badge_count = count( $existing_badges );
		$matching_keys = array_keys( $existing_badges, $badge_id );
		if ( ! empty( $matching_keys ) && count( $matching_keys ) > 0 ) {
			foreach ( $matching_keys as $key ) {
				unset( $existing_badges[ $key ] );
				$badge_count--;
			}
			update_user_meta( $user_id, 'go_badges', $existing_badges );
			$wpdb->update( "{$wpdb->prefix}go_totals", array( 'badge_count' => $badge_count ), array( 'uid' => $user_id ) );
		}
	}
}

/**
 * Removes non-existent badges from user's "go_badges" meta data.
 *
 * Loops through the badges that the user has and removes any that do not have an existing media
 * file attached to their id. This prevents the badges from taking up space in the stats page.
 *
 * @since 2.5.9
 *
 * @param  int	$user_id The user's id.
 * @return null Return null if the user id is missing or invalid.
 */
function go_clean_badges ( $user_id ) {
	if ( empty( $user_id ) || $user_id <= 0 ) {
		error_log(
			"Game On Error: invalid call to go_clean_badges() in go_open_badge.php, ".
			"args( user_id(" . gettype( $user_id ) . ")={$user_id} )"
		);
		return;
	}

	$user_badges = get_user_meta( $user_id, 'go_badges', true );
	$modified_badges = $user_badges;
	$attachment_link = '';
	
	foreach ( $user_badges as $key => $badge_id ) {

		// this will hold the url for the media attachment, if it exists
		$attachment_link = wp_get_attachment_link( $badge_id );
		if ( 'Missing Attachment' === $attachment_link ) {
			unset( $modified_badges[ $key ] );
		}
	}

	// if we actually made changes, update the user's meta data
	if ( $modified_badges != $user_badges ) {

		// unset() can leave holes in an array, this will reorganize the array to deal with that
		$modified_badges = array_values( $modified_badges );
		update_user_meta( $user_id, 'go_badges', $modified_badges );
	}
}
?>