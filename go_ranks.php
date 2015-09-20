<?php

/*
	This is the file that displays a page on the admin side of wordpress for the list of rankings.
	Allows administrator to edit points required for each ranking and to delete certain rankings/add others. 
*/

// $output has two possible boolean values: true and false. True will echo any rank notification,
// false will return any rank notifications.
function go_update_ranks( $user_id, $total_points, $output = true ) {
	global $current_rank;
	go_get_rank( $user_id);
	global $current_rank_points;
	global $current_points;
	global $next_rank;
	global $next_rank_points;
	$ranks = get_option( 'go_ranks' );
	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$badges_array = $ranks['badges'];
	// $update = false;

	/*
	 * Here we are referring to last element manually,
	 * since we don't want to modifiy
	 * the arrays with the array_pop function.
	 */
	$max_rank_index = count( $name_array ) - 1;
	$max_rank = $name_array[ $max_rank_index ];
	$max_rank_points = $points_array[ $max_rank_index ];

	/*
	 * Here we search for the index of the current rank by point threshold,
	 * which should be unique (it's not guaranteed to be unique).
	 */
	$current_rank_index = array_search( $current_rank_points, $points_array );

	// error_log( print_r( $current_rank, true ) );
	// error_log( print_r( $current_rank_points, true ) );
	// error_log( print_r( $current_points, true ) );

	// error_log( print_r( $max_rank, true ) );
	// error_log( print_r( $max_rank_points, true ) );
	
	/*
	 * If the user's current points are greater than or equal
	 * to the current max rank's points, we'll handle things
	 * slightly differently than normal.
	 */
	if ( $current_points >= $max_rank_points ) {
		if ( $current_rank !== $max_rank ) {
			
			// ...set the user's rank to the max rank
		}
	} else {
		if ( $current_points > $current_rank_points ) {

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
					if ( $output ) {
						go_set_rank( $user_id, $i - 1, $ranks, true, $output );
					} else {
						return go_set_rank( $user_id, $i - 1, $ranks, true, $output );
					}
					break;
				}
			}
		} else if ( $current_points < $current_rank_points ) {
			if ( $current_points > 0 ) {
				// loop through to find rank lower than the current one

				for ( $x = 0; $x < $current_rank_index; $x++ ) {

					// this reflects the points required to reach the rank at the current index
					$rank_point_threshold = $points_array[ $x ];

					// this checks if the user's points fall under the rank at the current index
					if ( $current_points < $rank_point_threshold ) {

						// ...set the user's rank to the rank at the current index,
						// and remove an badges assigned to the current rank
					}
				}
			} else {
				// ...set the user's rank to the minimum rank,
				// and remove an badges assigned to the current rank
			}
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
	// 	global $counter;
	// 	$counter++;
	// 	$space = $counter * 85;
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

function go_set_rank( $user_id, $new_rank_index, $ranks, $is_level_up, $echo_notification = true ) {
	global $current_rank;
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	global $counter;

	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$badges_array = $ranks['badges'];
	$badge_id = $badges_array[ $new_rank_index ];

	$new_rank_array = array();
	$new_rank = '';
	$new_rank_points = 0;
	$new_next_rank = '';
	$new_next_rank_points = 0;

	if ( ! isset( $user_id ) || ! isset( $new_rank_index ) ||
			! isset( $ranks ) || ! is_int( $user_id ) ||
			! is_int( $new_rank_index ) || ! is_array( $ranks ) ||
			! is_bool( $is_level_up ) || $new_rank_index < 0 ) {
		error_log( 
			"Game On Error: invalid call to go_set_rank() in go_ranks.php, ".
			"args( user_id={$user_id}, new_rank_index={$new_rank_index}, ranks=".
			print_r( $ranks, true ) . ", is_level_up={$is_level_up} )" 
		);
		return;
	}

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
	// 	global $counter;
	// 	$counter++;
	// 	$space = $counter * 85;
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

	error_log( $new_rank );

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

	// this will pull from the user's meta-data, which we just updated
	$rank_meta_data = go_get_rank( $user_id );
	error_log( print_r( $rank_meta_data, true ) );
	$current_rank = $rank_meta_data[0];
	$current_rank_points = $rank_meta_data[1];
	$next_rank = $rank_meta_data[2];
	$next_rank_points = $rank_meta_data[3];

	$counter++;
	$space = $counter * 85;
	$notification = '
		<div id="go_notification_level" class="go_notification" style="top: '.
			( $space - 17 ).'px; color: white; background: #ffcc00; text-align: center; '.
			'width: 300px; line-height: 68px; height: 81.6px; font-size: 52px;"> '.
			$current_rank.'!</div>
		<script type="text/javascript" language="javascript">
			go_notification(3000, jQuery( "#go_notification_level" ) );
			jQuery( "#go_admin_bar_rank" ).html( "'.$current_rank.'" );
		</script>
	';

	if ( $echo_notification === true ) {
		echo $notification;
	} elseif ( $echo_notification === false ) {
		return $notification;
	}
}

function go_get_rank( $user_id ) {
	$rank = get_user_meta( $user_id, 'go_rank' );
	global $current_rank;
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
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
}

function go_get_all_ranks() {
	$all_ranks = get_option( 'go_ranks' );
	$all_ranks_sorted = array();
	foreach ( $all_ranks as $level => $points ) {
		$all_ranks_sorted[] = array( 'name' => $level , 'value' => $points );
	}
	return $all_ranks_sorted;
}

function go_clean_ranks() {
	$all_ranks = get_option( 'go_ranks' );
	$all_ranks_sorted = array();
	foreach ( $all_ranks as $level => $points ) {
    	echo "<option value='{$points}'>{$level}</option>";
	}
}

function go_get_rank_key( $points ) {
	$ranks = get_option( 'go_ranks', false );
	$key = array_search( $points, $ranks['points'] );
	return $key;
}

?>