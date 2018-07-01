<?php
/**
 * Determines what level the user should be at.
 *
 * Uses the user's current points (XP), the rank thresholds stored in the "go_ranks" option, and the
 * user's rank data (stored in "go_rank" meta data) to iterate through the available ranks and 
 * assign the user an appropriate rank.
 *
 * @since 1.0.0
 * @see go_get_rank(), go_set_rank(), go_return_points(), go_user_at_max_rank(), go_user_at_min_rank()
 *
 * @param  int 	   $user_id 	 Optional. The user's id.
 * @param  int 	   $total_points Optional. The user's new point total.
 * @param  boolean $output 		 Optional. Whether to echo the new rank notification or return it.
 * @return string|null If there is a new rank to assign and the output parameter is true, the rank's
 *					   notification will be echoed. If the output parameter is false, the rank's
 *					   notification will be returned. Otherwise, null will be returned.
 */
function go_update_ranks ( $user_id = null, $total_points = null, $output = false ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	/**
	 * Passed to go_set_rank() to determine whether to add or remove badges. The default assumes
	 * that the user is leveling up. This gets modified below, when the user is down-leveling.
	 */
	$is_level_up = true;
	$ranks = get_option( 'go_ranks' );
	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$badges_array = $ranks['badges'];
	$new_rank = '';
	$current_points = 0;

	if ( empty( $ranks ) ) {
		error_log(
			"Game On Error: the go_ranks option is empty in ".
			"go_update_ranks() in go_ranks.php! ".
			"Ranks have to be provided in the settings page"
		);
		return;
	}

	if ( null !== $total_points && $total_points >= 0 ) {
		$current_points = $total_points;
	} else {
		$current_points = go_return_points( $user_id );
	}
	$user_rank = go_get_rank( $user_id );
	$current_rank = $user_rank['current_rank'];
	$current_rank_points = $user_rank['current_rank_points'];
	$next_rank = $user_rank['next_rank'];
	$next_rank_points = $user_rank['next_rank_points'];

	/*
	 * Here we search for the index of the current rank by point threshold,
	 * which should be unique (it's not guaranteed to be unique).
	 */
	$current_rank_index = array_search( $current_rank_points, $points_array );

	// the current rank's badge id, used when the user is at the minimum rank already
	$current_rank_badge_id = $badges_array[ $current_rank_index ];

	$min_rank_points = (int) $points_array[ 0 ];
	$is_min_rank = go_user_at_min_rank( $user_id, $min_rank_points, $current_rank_points );

	/*
	 * Here we are referring to last element manually,
	 * since we don't want to modify
	 * the arrays with the array_pop function.
	 */
	$max_rank_index = count( $name_array ) - 1;
	$max_rank_points = (int) $points_array[ $max_rank_index ];
	$is_max_rank = go_user_at_max_rank( $user_id, $max_rank_points, $current_rank_points );
	
	/*
	 * If the user's current points are greater than or equal
	 * to the current max rank's points, we'll handle things
	 * slightly differently than normal.
	 */
	if ( $current_points >= $max_rank_points ) {
		if ( ! $is_max_rank ) {
			
			// ...set the user's rank to the max rank
			$new_rank = go_set_rank( $user_id, $max_rank_index, $ranks, $is_level_up );
		}
	} else {

		// we don't want to enter this block when the user is at the max rank, because in that
		// case the user's current points will always be greater than the next rank's points
		if ( $current_points > $next_rank_points && ! $is_max_rank ) {

			/*
			 * We can safely start the loop at the index immediately after the
			 * current rank's index in the $points_array array. This is because
			 * we already know that the current rank is not the max rank, so there
			 * will be at least one iteration of the loop.
			 */
			for ( $i = $current_rank_index + 1; $i < count( $points_array ); $i++ ) {
				
				// this reflects the points required to reach the rank at the current index
				$rank_point_threshold = $points_array[ $i ];

				// this checks if the user's points fall under the rank at the current index
				if ( $current_points < $rank_point_threshold ) {

					// ...set the user's rank to the rank at the current index
					$new_rank = go_set_rank( $user_id, $i - 1, $ranks, $is_level_up );
					break;
				}
			}

		// end-if current points are greater than the next rank's point threshold (up-leveling)
		} else if ( $current_points < $current_rank_points ) {
			$is_level_up = false;

			if ( $current_points > 0 ) {

				// loop through to find rank lower than the current one
				for ( $x = $current_rank_index - 1; $x >= 0; $x-- ) {

					// this reflects the points required to reach the rank at the current index
					$rank_point_threshold = $points_array[ $x ];

					// this checks that the rank threshold at the current index falls under the user's points
					if ( $current_points > $rank_point_threshold ) {

						// ...set the user's rank to the rank at the current index,
						// and remove an badges assigned to the current rank
						$new_rank = go_set_rank( $user_id, $x, $ranks, $is_level_up, $current_rank_index );
						break;
					}
				}
			} else {

				// if the user isn't already at the minimum rank...
				if ( ! $is_min_rank ) {

					// ...set the user's rank to the minimum rank,
					// and remove any badges assigned to the current rank
					$new_rank = go_set_rank( $user_id, 0, $ranks, $is_level_up, $current_rank_index );
				}
			}

		// end-if current points are less than the current rank's point threshold (down-leveling)
		} else if ( $is_min_rank ) {
			go_award_badge(
				array(
					'id' 		=> $current_rank_badge_id,
					'repeat' 	=> false,
					'uid' 		=> $user_id
				)
			);
		// end-if user is already at the minimum rank (generally new users)
		}

	// end-if current points are less than the max rank's point threshold
	}

	if ( ! empty( $new_rank ) ) {
		if ( $output ) {
			echo $new_rank;
		} else {
			return $new_rank;
		}
	} else {
		return null;
	}
}

/**
 * Update the user's rank.
 *
 * Update the user's rank to the one at the passed index. Add or remove badges depending on
 * whether the user is leveling up or leveling down. Then return the level notification,
 * so that it can be manipulated elsewhere.
 *
 * @since 2.6.0
 *
 * @global int 	   $go_notify_counter Keeps track of the number of notifications on screen and is used
 *									  to space notifications.
 *
 * @param  int 	   $user_id 		  The user's id.
 * @param  int 	   $new_rank_index 	  The index of the user's new rank within the "points" array of the
 *									  "go_ranks" GO-option.
 * @param  array   $ranks 			  Contains the rank data stored in the "go_ranks" GO-option.
 * @param  boolean $is_rank_up 		  Determines whether the user is leveling up or leveling down.
 * @param  int 	   $old_rank_index 	  Optional. The index of the user's current rank within the "points"
 *									  array of the "go_ranks" GO-option. Only needed when the user
 *									  is leveling down (i.e. $is_rank_up == false).
 * @return string|null Returns the notification of the user's new level on success.
 *					   Returns NULL on failure and may output errors to the PHP error log.
 */
function go_set_rank( $user_id, $new_rank_index, $ranks, $is_rank_up = true, $old_rank_index = -1 ) {
	global $go_notify_counter;

	if ( ! isset( $user_id ) || ! isset( $new_rank_index ) ||
			! isset( $ranks ) || ! is_int( $user_id ) ||
			! is_int( $new_rank_index ) || ! is_array( $ranks ) ||
			! is_bool( $is_rank_up ) || ! is_int( $old_rank_index ) ||
			$new_rank_index < 0 ) {

		error_log(
			"Game On Error: invalid call to go_set_rank() in go_ranks.php, ".
			"args( user_id={$user_id}, new_rank_index={$new_rank_index}, ranks=".
			print_r( $ranks, true ).", is_rank_up=" . ( $is_rank_up ? 'true' : 'false' ) . 
			", old_rank_index={$old_rank_index} )"
		);

		return;
	}

	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$badges_array = $ranks['badges'];

	$new_rank_badge_id = $badges_array[ $new_rank_index ];

	// the number of ranks that the user is decreasing or increasing by
	$rank_index_diff = 0;

	$new_rank_array = array();
	$new_rank = '';
	$new_rank_points = 0;
	$new_next_rank = '';
	$new_next_rank_points = 0;

	$new_rank = $name_array[ $new_rank_index ];
	$new_rank_points = $points_array[ $new_rank_index ];
	if ( isset( $name_array[ $new_rank_index + 1 ] ) &&
			isset( $points_array[ $new_rank_index + 1 ] ) ) {
		$new_next_rank = $name_array[ $new_rank_index + 1 ];
		$new_next_rank_points = $points_array[ $new_rank_index + 1 ];
	}

	if ( $is_rank_up ) {
		$rank_index_diff = $new_rank_index - $old_rank_index;
		go_award_badge_r( $user_id, $new_rank_index, $rank_index_diff, $badges_array );
	} else if ( $old_rank_index >= 0 ) {
		$rank_index_diff = $old_rank_index - $new_rank_index;
		go_remove_badge_r( $user_id, $old_rank_index, $rank_index_diff, $badges_array );
	}
	go_clean_badges( $user_id );

	$new_rank_array = array(
		array( 
			$new_rank, 
			$new_rank_points
		),
		array(
			$new_next_rank,
			$new_next_rank_points
		)
	);

	// update the user's meta-data
	update_user_meta( $user_id, 'go_rank', $new_rank_array );

	$go_notify_counter++;
	$space = $go_notify_counter * 85;
	$notification = '
		<div id="go_notification_level" class="go_notification" style="top: '.
			( $space - 17 ).'px; color: white; background: #ffcc00; text-align: center; '.
			'width: 300px; line-height: 68px; height: 81.6px; font-size: 52px;"> '.
			$new_rank.'!</div>
		<script type="text/javascript" language="javascript">
			jQuery( "document" ).ready( function() {
				go_notification(3000, jQuery( "#go_notification_level" ) );
				jQuery( "#go_admin_bar_rank" ).html( "'.$new_rank.'" );
			});
		</script>
	';

	return $notification;
}

/**
 * Returns an array containing data for the user's current and next rank.
 *
 * Uses the user's "go_rank" meta data value to return the name and point threshold of the current
 * and next rank.
 *
 * @since 2.4.4
 *
 * @param  int $user_id The user's id.
 * @return array Returns an array of defaults on when the user's "go_rank" meta data is empty. 
 * 				 On success, returns array of rank data.
 */
function go_get_rank ( $user_id ) {
	if ( empty( $user_id ) ) {
		return;
	}
    $go_current_xp = go_get_user_loot( $user_id, 'xp' );
    $rank_count = get_option('options_go_loot_xp_levels_level');
    $i = $rank_count - 1; //account for count starting at 0
    while ( $i >= 0 ) {

        $xp = get_option('options_go_loot_xp_levels_level_' . $i . '_xp');
        if ($go_current_xp >= $xp){
            $current_rank = get_option('options_go_loot_xp_levels_level_' . $i . '_name');
            $current_rank_points = $xp;
            $next_rank_points = get_option('options_go_loot_xp_levels_level_' . ($i +1) . '_xp');
            $next_rank = get_option('options_go_loot_xp_levels_level_' . ($i +1) . '_name');
            break;
        }
        $i--;
    }

		return array(
			'current_rank' 		  => $current_rank,
			'current_rank_points' => $current_rank_points,
			'next_rank' 		  => $next_rank,
			'next_rank_points' 	  => $next_rank_points,
            'rank_num'            => ($i + 1)
		);
}

/**
 * Determines whether or not the user is at the max rank.
 * 
 * When a user is at the max rank, their current rank's point threshold will match
 * that of the max rank.
 * 
 * @since 2.6.0
 * @see go_get_rank()
 * 
 * @param  int $user_id 			The user's id.
 * @param  int $max_rank_points 	The point threshold of the max rank.
 * @param  int $current_rank_points The point threshold of the user's current rank.
 * @return boolean TRUE on success. FALSE on failure.
 */
function go_user_at_max_rank ( $user_id, $max_rank_points = null, $current_rank_points = null ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}
	
	if ( null === $max_rank_points || $max_rank_points < 0 ) {
		$ranks = get_option( 'go_ranks' );
		$max_rank_index = count( $ranks['name'] ) - 1;
		$max_rank_points = $ranks['points'][ $max_rank_index ];
	}

	if ( null === $current_rank_points || $current_rank_points < 0 ) {
		$user_rank = go_get_rank( $user_id );
		$current_rank_points = $user_rank['current_rank_points'];
	}

	// compare the point thresholds
	if ( (int) $current_rank_points === (int) $max_rank_points ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Determines whether or not the user is at the min rank.
 * 
 * When a user is at the min rank, their current rank's point threshold will match
 * that of the min rank. By default that would be a threshold of zero. However, that
 * threshold can be modified, so we must not expect that the min rank will always be zero.
 * 
 * @since 2.6.0
 * @see go_get_rank()
 * 
 * @param  int $user_id 			The user's id.
 * @param  int $min_rank_points 	The point threshold of the min rank.
 * @param  int $current_rank_points The point threshold of the user's current rank.
 * @return boolean TRUE on success. FALSE on failure.
 */
function go_user_at_min_rank ( $user_id, $min_rank_points = null, $current_rank_points = null ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	if ( null === $min_rank_points || $min_rank_points < 0 ) {
		$ranks = get_option( 'go_ranks' );
		$min_rank_points = $ranks['points'][0];
	}

	if ( null === $current_rank_points || $current_rank_points < 0 ) {
		$user_rank = go_get_rank( $user_id );
		$current_rank_points = $user_rank['current_rank_points'];
	}

	// compare the point thresholds
	if ( $current_rank_points === $min_rank_points ) {
		return true;
	} else {
		return false;
	}
}
?>