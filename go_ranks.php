<?php

// $output has two possible boolean values: true and false. True will echo any rank notification,
// false will return any rank notifications.
function go_update_ranks( $user_id, $total_points = null, $output = false ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$ranks = get_option( 'go_ranks' );
	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$badges_array = $ranks['badges'];
	$new_rank = '';

	if ( empty( $ranks ) ) {
		error_log( 
			"Game On Error: the go_ranks option is empty in ".
			"go_update_ranks() in go_ranks.php! ".
			"Ranks have to be provided in the settings page"
		);
		return;
	}

	$current_points = go_return_points( $user_id );
	$user_rank = go_get_rank( $user_id );
	if ( ! empty( $user_rank ) ) {
		$current_rank = $user_rank[0];
		$current_rank_points = $user_rank[1];
		$next_rank = $user_rank[2];
		$next_rank_points = $user_rank[3];
	} else {
		$current_rank = $name_array[0];
		$current_rank_points = $points_array[0];
		if ( ! empty ( $name_array[1] ) && ! empty( $points_array[1] ) ) {
			$next_rank = $name_array[1];
			$next_rank_points = $points_array[1];
		}
	}

	$min_rank_points = $points_array[ 0 ];
	$is_min_rank = go_user_at_min_rank( $user_id, $min_rank_points, $current_rank_points );

	/*
	 * Here we are referring to last element manually,
	 * since we don't want to modify
	 * the arrays with the array_pop function.
	 */
	$max_rank_index = count( $name_array ) - 1;
	$max_rank_points = $points_array[ $max_rank_index ];
	$is_max_rank = go_user_at_max_rank( $user_id, $max_rank_points, $current_rank_points );

	/*
	 * Here we search for the index of the current rank by point threshold,
	 * which should be unique (it's not guaranteed to be unique).
	 */
	$current_rank_index = array_search( $current_rank_points, $points_array );

	if ( empty( $total_points ) ) {
		$total_points = $current_points;
	}
	
	/*
	 * If the user's current points are greater than or equal
	 * to the current max rank's points, we'll handle things
	 * slightly differently than normal.
	 */
	if ( $current_points >= $max_rank_points ) {
		if ( ! $is_max_rank ) {
			
			// ...set the user's rank to the max rank
			$new_rank = go_set_rank( $user_id, $max_rank_index, $ranks, true );
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
					$new_rank = go_set_rank( $user_id, $i - 1, $ranks, true );

					break;
				}
			}
		} else if ( $current_points < $current_rank_points ) {
			if ( $current_points > 0 ) {

				// loop through to find rank lower than the current one
				for ( $x = $current_rank_index - 1; $x > 0; $x-- ) {

					// this reflects the points required to reach the rank at the current index
					$rank_point_threshold = $points_array[ $x ];

					// this checks that the rank threshold at the current index falls under the user's points
					if ( $current_points > $rank_point_threshold ) {

						// ...set the user's rank to the rank at the current index,
						// and remove an badges assigned to the current rank

						$new_rank = go_set_rank( $user_id, $x, $ranks, true );

						break;
					}
				}
			} else {

				// if the user isn't already at the minimum rank...
				if ( ! $is_min_rank ) {

					// ...set the user's rank to the minimum rank,
					// and remove any badges assigned to the current rank					
					$new_rank = go_set_rank( $user_id, 0, $ranks, true );
				}
			}
		}
	} // end-if current points are less than the max rank's point threshold

	if ( ! empty( $new_rank ) ) {
		if ( $output ) {
			echo $new_rank;
		} else {
			return $new_rank;
		}
	}

	// if ( $next_rank != '' ) {
	// 	if ( $total_points >= $next_rank_points ) {
	// 		while ( current( $points_array ) != $next_rank_points ) {
	// 			next( $points_array );
	// 		}
			
	// 		while ( $total_points >= current( $points_array ) ) {
	// 			$current_key = key( $points_array );
	// 			$new_rank = $name_array[ $current_key ];
	// 			$new_rank_points = $points_array[ $current_key ];
	// 			$new_next_rank = $name_array[ $current_key + 1 ];
	// 			$new_next_rank_points = $points_array[ $current_key + 1 ];
	// 			if ( $ranks['badges'][ $current_key ] ) {
	// 				$badge_id = $ranks['badges'][ $current_key ];
	// 				do_shortcode( "[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']" );
	// 			}
	// 			next( $points_array );
	// 		}
	// 		$new_rank = array(
	// 			array( $new_rank, $new_rank_points ),	
	// 			array( $new_next_rank, $new_next_rank_points )
	// 		);
	// 		$update = true;
	// 	}
		
	// 	if ( ! empty( $points_array ) ) {
	// 		reset( $points_array );
	// 	}
		
	// 	if ( $total_points < $current_rank_points ) {
			
	// 		while ( current( $points_array ) != $current_rank_points ) {
	// 			next( $points_array );
	// 		}
		
	// 		while ( $total_points < current( $points_array ) ) {
	// 			$current_key = key( $points_array );
	// 			$new_rank = $name_array[ $current_key - 1 ];
	// 			$new_rank_points = $points_array[ $current_key - 1 ];
	// 			$new_next_rank = $name_array[ $current_key ];
	// 			$new_next_rank_points = $points_array[ $current_key ];
	// 			if( $ranks['badges'][ $current_key ] ) {
	// 				go_remove_badge( $user_id, $ranks['badges'][ $current_key ] );
	// 			}
	// 			prev( $points_array );
	// 		}
	// 		$new_rank = array(
	// 			array( $new_rank, $new_rank_points ),	
	// 			array( $new_next_rank, $new_next_rank_points )
	// 		);
	// 		$update = true;
	// 	}
	// }
	
	// if ( $update ) {
	// 	update_user_meta( $user_id, 'go_rank', $new_rank );
	// 	go_get_rank( $user_id );
	// 	global $current_rank;
	// 	global $current_rank_points;
	// 	global $next_rank;
	// 	global $next_rank_points;
	// 	global $go_notify_counter;
	// 	$go_notify_counter++;
	// 	$space = $go_notify_counter * 85;
	// 	$notification = '
	// 		<div id="go_notification_level" class="go_notification" style="top: '.( $space - 17).'px; color: white; background: #ffcc00; text-align: center; width: 300px; line-height: 68px; height: 81.6px; font-size: 52px;"> '.$current_rank.'!</div>
	// 		<script type="text/javascript" language="javascript">
	// 			go_notification(3000, jQuery( "#go_notification_level" ) );
	// 			jQuery( "#go_admin_bar_rank" ).html( "'.$current_rank.'" );
	// 		</script>
	// 	';
	// 	if ( $output === true ) {
	// 		echo $notification;	
	// 	} elseif ( $output === false ) {
	// 		return $notification;
	// 	}
	// }
}

/**
 * Update the user's rank.
 *
 * Update the user's rank to the one at the passed index. Add or remove badges depending on
 * 			whether the user is leveling up or leveling down. Then return the level notification,
 *			so that it can be manipulated elsewhere.
 *
 * @since 2.5.9
 * @see go_update_ranks()
 *
 * @global INT $go_notify_counter Keeps track of the number of notifications on screen and is used
 *			to space notifications.
 *
 * @param  INT $user_id The user's id.
 * @param  INT $new_rank_index The index of the user's new rank within the "points" array of the
 *			"go_ranks" GO-option.
 * @param  ARRAY $ranks Contains the rank data stored in the "go_ranks" GO-option.
 * @param  boolean $is_level_up Determines whether the user is leveling up or leveling down.
 * @return STRING/NULL Returns the notification of the user's new level on success. Returns NULL 
 *			on failure and may output errors to the PHP error log.
 */
function go_set_rank( $user_id, $new_rank_index, $ranks, $is_level_up = true ) {
	global $go_notify_counter;

	if ( ! isset( $user_id ) || ! isset( $new_rank_index ) ||
			! isset( $ranks ) || ! is_int( $user_id ) ||
			! is_int( $new_rank_index ) || ! is_array( $ranks ) ||
			! is_bool( $is_level_up ) || $new_rank_index < 0 ) {
		
		error_log(
			( 
				'! isset( $user_id ) => '.( ! isset( $user_id ) ? 'true' : 'false' )."\n".
				'! isset( $new_rank_index ) => '.( ! isset( $new_rank_index ) ? 'true' : 'false' )."\n".
				'! isset( $ranks )  => '.( ! isset( $ranks ) ? 'true' : 'false' )."\n".
				'! is_int( $user_id ) => '.( ! is_int( $user_id ) ? 'true' : 'false' )."\n".
				'! is_int( $new_rank_index ) => '.( ! is_int( $new_rank_index ) ? 'true' : 'false' )."\n".
				'! is_array( $ranks ) => '.( ! is_array( $ranks ) ? 'true' : 'false' )."\n".
				'! is_bool( $is_level_up ) => '.( ! is_bool( $is_level_up ) ? 'true' : 'false' )."\n".
				'( $new_rank_index < 0 ) => '.( $new_rank_index < 0 ? 'true' : 'false' )
			)
		);

		error_log(
			"Game On Error: invalid call to go_set_rank() in go_ranks.php, ".
			"args( user_id={$user_id}, new_rank_index={$new_rank_index}, ranks=".
			print_r( $ranks, true ).", is_level_up={$is_level_up} )"
		);
		return;
	}

	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$badges_array = $ranks['badges'];
	$badge_id = $badges_array[ $new_rank_index ];

	$new_rank_array = array();
	$new_rank = '';
	$new_rank_points = 0;
	$new_next_rank = '';
	$new_next_rank_points = 0;

	// $current_key = key( $points_array );
	// $new_rank = $name_array[ $current_key - 1 ];
	// $new_rank_points = $points_array[ $current_key - 1 ];
	// $new_next_rank = $name_array[ $current_key ];
	// $new_next_rank_points = $points_array[ $current_key ];
	// if( $ranks['badges'][ $current_key ] ) {
	// 		go_remove_badge( $user_id, $ranks['badges'][ $current_key ] );
	// }
	// prev( $points_array );

	// if( $ranks['badges'][ $current_key ] ) {
	// 		go_remove_badge( $user_id, $ranks['badges'][ $current_key ] );
	// }

	// if ( $ranks['badges'][ $current_key ] ) {
	// 		$badge_id = $ranks['badges'][ $current_key ];
	// 		do_shortcode( "[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']" );
	// }

	// $new_rank = array(
	// 	array( $new_rank, $new_rank_points ),	
	// 	array( $new_next_rank, $new_next_rank_points )
	// );

	// if ( $update ) {
	// 	update_user_meta( $user_id, 'go_rank', $new_rank );
	// 	go_get_rank( $user_id );
	// 	global $current_rank;
	// 	global $current_rank_points;
	// 	global $next_rank;
	// 	global $next_rank_points;
	// 	global $go_notify_counter;
	// 	$go_notify_counter++;
	// 	$space = $go_notify_counter * 85;
	// 	$notification = '
	// 		<div id="go_notification_level" class="go_notification" style="top: '.( $space - 17).'px; color: white; background: #ffcc00; text-align: center; width: 300px; line-height: 68px; height: 81.6px; font-size: 52px;"> '.$current_rank.'!</div>
	// 		<script type="text/javascript" language="javascript">
	// 			go_notification(3000, jQuery( "#go_notification_level" ) );
	// 			jQuery( "#go_admin_bar_rank" ).html( "'.$current_rank.'" );
	// 		</script>
	// 	';
	// 	if ( $output === true ) {
	// 		echo $notification;	
	// 	} elseif ( $output === false ) {
	// 		return $notification;
	// 	}
	// }

	if ( $is_level_up ) {
		$new_rank = $name_array[ $new_rank_index ];
		$new_rank_points = $points_array[ $new_rank_index ];
		if ( isset( $name_array[ $new_rank_index + 1 ] ) &&
				isset( $points_array[ $new_rank_index + 1 ] ) ) {
			$new_next_rank = $name_array[ $new_rank_index + 1 ];
			$new_next_rank_points = $points_array[ $new_rank_index + 1 ];
		}

		do_shortcode( "[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']" );
	} else {
		$new_rank = $name_array[ $new_rank_index - 1 ];
		$new_rank_points = $points_array[ $new_rank_index - 1 ];
		if ( isset( $name_array[ $new_rank_index ] ) &&
				isset( $points_array[ $new_rank_index ] ) ) {
			$new_next_rank = $name_array[ $new_rank_index ];
			$new_next_rank_points = $points_array[ $new_rank_index ];
		}

		go_remove_badge( $user_id, $badge_id );
	}

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

function go_get_rank( $user_id ) {
	$rank = get_user_meta( $user_id, 'go_rank' );
	if ( ! empty( $rank[0] ) ) {
		$current_rank = $rank[0][0][0];
		$current_rank_points = $rank[0][0][1];
		$next_rank = $rank[0][1][0];
		$next_rank_points = $rank[0][1][1];

		return array(
			$current_rank,
			$current_rank_points,
			$next_rank,
			$next_rank_points
		);
	} else {
		return;
	}
}

function go_get_all_ranks() {
	$all_ranks = get_option( 'go_ranks' );
	$all_ranks_sorted = array();
	foreach ( $all_ranks as $level => $points ) {
		$all_ranks_sorted[] = array( 'name' => $level , 'value' => $points );
	}
	return $all_ranks_sorted;
}

/**
 * Determines whether or not the user is at the max rank.
 * 
 * Description: When a user is at the max rank, their current rank's point threshold will match
 * 			that of the max rank.
 * 
 * @since 2.5.9
 * @see go_update_ranks()
 * 
 * @param  INT $user_id The user's id.
 * @param  INT $max_rank_points The point threshold of the max rank.
 * @param  INT $current_rank_points The point threshold of the user's current rank.
 * @return BOOL true/false TRUE on success. FALSE on failure.
 */
function go_user_at_max_rank ( $user_id, $max_rank_points = null, $current_rank_points = null ) {
	if ( empty( $user_id ) && ( empty( $max_rank_points ) || empty( $current_rank_points ) ) ) {
		$user_id = get_current_user_id();
	}

	if ( empty( $max_rank_points ) ) {
		$ranks = get_option( 'go_ranks' );
		$max_rank_index = count( $ranks['name'] ) - 1;
		$max_rank_points = $ranks['points'][ $max_rank_index ];
	}

	if ( empty( $current_rank_points ) ) {
		$user_rank = go_get_rank( $user_id );
		$current_rank_points = $user_rank[1];
	}

	// compare the point thresholds
	if ( $current_rank_points === $max_rank_points ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Determines whether or not the user is at the min rank.
 * 
 * Description: When a user is at the min rank, their current rank's point threshold will match
 * 			that of the min rank. By default that would be a threshold of zero. However, that
 * 			threshold can be modified, so we must not expect that the min rank will always be zero.
 * 
 * @since 2.5.9
 * @see go_update_ranks()
 * 
 * @param  INT $user_id The user's id.
 * @param  INT $min_rank_points The point threshold of the min rank.
 * @param  INT $current_rank_points The point threshold of the user's current rank.
 * @return BOOL true/false TRUE on success. FALSE on failure.
 */
function go_user_at_min_rank ( $user_id, $min_rank_points = null, $current_rank_points = null ) {
	if ( empty( $user_id ) && ( empty( $min_rank_points ) || empty( $current_rank_points ) ) ) {
		$user_id = get_current_user_id();
	}

	if ( empty( $min_rank_points ) ) {
		$ranks = get_option( 'go_ranks' );
		$min_rank_points = $ranks['points'][ 0 ];
	}

	if ( empty( $current_rank_points ) ) {
		$user_rank = go_get_rank( $user_id );
		$current_rank_points = $user_rank[1];
	}

	// compare the point thresholds
	if ( $current_rank_points === $min_rank_points ) {
		return true;
	} else {
		return false;
	}
}

?>