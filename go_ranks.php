<?php

/*
	This is the file that displays a page on the admin side of wordpress for the list of rankings.
	Allows administrator to edit points required for each ranking and to delete certain rankings/add others. 
*/

// $output has two possible boolean values: true and false. True will echo any rank notification,
// false will return any rank notifications.
function go_update_ranks( $user_id, $total_points = null, $output = false ) {
	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
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

	if ( empty( $total_points ) ) {
		$total_points = $current_points;
	}

	error_log( 
		"#### go_update_ranks ####\n".
		"\$current_points = {$current_points}\n".
		"\$current_rank = {$current_rank}"
	);

	// error_log( $next_rank );
	// error_log( $next_rank_points );

	// error_log( $next_rank );
	// error_log( $next_rank_points );

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
		error_log( "\ncurrent points are greater than the max rank" );
		if ( $current_rank !== $max_rank ) {
			error_log( "\ncurrent rank isn't equal to the max rank" );
			
			// ...set the user's rank to the max rank
			$new_rank = go_set_rank( $user_id, $max_rank_index, $ranks, true );
		}
	} else {
		error_log( "\ncurrent points are less than the max rank" );
		if ( ! empty( $next_rank_points ) && $current_points > $next_rank_points ) {
			error_log( "\ncurrent points are greater than the next rank point threshold" );

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

					error_log(
						"\ncurrent points ({$current_points}) are less than rank's points ".
						"({$name_array[ $i ]}:{$rank_point_threshold})"
					);

					// ...set the user's rank to the rank at the current index
					$new_rank = go_set_rank( $user_id, $i - 1, $ranks, true );

					error_log(
						"\nold rank = {$current_rank}:{$current_rank_points},".
						" new rank = {$new_rank}:{$new_rank_points}"
					);
					break;
				}
			}
		} else if ( $current_points < $current_rank_points ) {
			error_log( "\ncurrent points are less than the current rank point threshold" );

			if ( $current_points > 0 ) {
				// loop through to find rank lower than the current one

				error_log( "#### looping through ranks... {$current_points} ####" );

				for ( $x = 0; $x < $current_rank_index + 1; $x++ ) {

					// this reflects the points required to reach the rank at the current index
					$rank_point_threshold = $points_array[ $x ];

					error_log(
						"{$x} => {$name_array[ $x ]}:{$rank_point_threshold}\n".
						"\tis {$current_points} < {$rank_point_threshold}?...".
						( $current_points < $rank_point_threshold ?  'true' : 'false' )
					);

					// this checks if the user's points fall under the rank at the current index
					if ( $current_points < $rank_point_threshold ) {

						// ...set the user's rank to the rank at the current index,
						// and remove an badges assigned to the current rank

						$new_rank = go_set_rank( $user_id, $x - 1, $ranks, true );

						error_log(
							"\nold rank = {$current_rank}:{$current_rank_points},".
							" new rank = {$new_rank}:{$new_rank_points}"
						);
						break;
					}
				}
			} else {
				// ...set the user's rank to the minimum rank,
				// and remove an badges assigned to the current rank
			}
		}
	} // end-if current points are less than the max level's point threshold

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

function go_set_rank( $user_id, $new_rank_index, $ranks, $is_level_up = true ) {
	global $go_notify_counter;

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

		error_log(
			"UPDATING RANK: \n".
			"\$new_rank 			= {$new_rank}\n".
			"\$new_rank_points 		= {$new_rank_points}\n".
			"\$new_next_rank 		= {$new_next_rank}\n".
			"\$new_next_rank_points = {$new_next_rank_points}"
		);

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

		error_log(
			"##### go_get_rank #####\n".
			"\$current_rank: $current_rank\n".
			"\$current_rank_points: $current_rank_points\n".
			"\$next_rank_points: $next_rank_points\n".
			"\$next_rank: $next_rank"
		);

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

?>