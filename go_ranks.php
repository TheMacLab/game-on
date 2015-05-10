<?php

/*
	This is the file that displays a page on the admin side of wordpress for the list of rankings.
	Allows administrator to edit points required for each ranking and to delete certain rankings/add others. 
*/

// $output has two possible boolean values: true and false. True will echo any rank notification,
// false will return any rank notifications.
function go_update_ranks ( $user_id, $total_points, $output = true ) {
	global $wpdb;
	global $current_rank;
	go_get_rank( $user_id);
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	global $current_points;
	$ranks = get_option( 'go_ranks' );
	$name_array = $ranks['name'];
	$points_array = $ranks['points'];
	$update = false;
	
	if ( $next_rank != '' ) {
		if ( $total_points >= $next_rank_points ) {
			while ( current( $points_array ) != $next_rank_points ) {
				next( $points_array );
			}
			
			while ( $total_points >= current( $points_array ) ) {
				$current_key = key( $points_array );
				$new_rank = $name_array[ $current_key ];
				$new_rank_points = $points_array[ $current_key ];
				$new_next_rank = $name_array[ $current_key + 1 ];
				$new_next_rank_points = $points_array[ $current_key + 1 ];
				if ( $ranks['badges'][ $current_key ] ) {
					$badge_id = $ranks['badges'][ $current_key ];
					do_shortcode( "[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']" );
				}
				next( $points_array );
			}
			$new_rank = array(
				array( $new_rank, $new_rank_points ),	
				array( $new_next_rank, $new_next_rank_points )
			);
			$update = true;
		}
		
		if ( ! empty( $points_array ) ) {
			reset( $points_array );
		}
		
		if ( $total_points < $current_rank_points ) {
			
			while ( current( $points_array ) != $current_rank_points ) {
				next( $points_array );
			}
		
			while ( $total_points < current( $points_array ) ) {
				$current_key = key( $points_array );
				$new_rank = $name_array[ $current_key - 1 ];
				$new_rank_points = $points_array[ $current_key - 1 ];
				$new_next_rank = $name_array[ $current_key ];
				$new_next_rank_points = $points_array[ $current_key ];
				if( $ranks['badges'][ $current_key ] ) {
					go_remove_badge( $user_id, $ranks['badges'][ $current_key ] );
				}
				prev( $points_array );
			}
			$new_rank = array(
				array( $new_rank, $new_rank_points ),	
				array( $new_next_rank, $new_next_rank_points )
			);
			$update = true;
		}
	}
	
	if ( $update ) {
		update_user_meta( $user_id, 'go_rank', $new_rank );
		go_get_rank( $user_id );
		global $current_rank;
		global $current_rank_points;
		global $next_rank;
		global $next_rank_points;
		global $counter;
		$counter++;
		$space = $counter * 85;
		$notification = '
			<div id="go_notification_level" class="go_notification" style="top: '.( $space - 17).'px; color: white; background: #ffcc00; text-align: center; width: 300px; line-height: 68px; height: 81.6px; font-size: 52px;"> '.$current_rank.'!</div>
			<script type="text/javascript" language="javascript">
				go_notification(3000, jQuery( "#go_notification_level" ) );
				jQuery( "#go_admin_bar_rank" ).html( "'.$current_rank.'" );
			</script>
		';
		if ( $output === true ) {
			echo $notification;	
		} elseif ( $output === false ) {
			return $notification;
		}
	}
}

function go_get_rank ( $user_id ) {
	global $wpdb;
	$rank = get_user_meta( $user_id, 'go_rank' );
	global $current_rank;
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	$current_rank = $rank[0][0][0];
	$current_rank_points = $rank[0][0][1];
	$next_rank = $rank[0][1][0];
	$next_rank_points = $rank[0][1][1];
}

function go_get_all_ranks () {
	$all_ranks = get_option( 'go_ranks' );
	$all_ranks_sorted = array();
	foreach ( $all_ranks as $level => $points ) {
		$all_ranks_sorted[] = array( 'name' => $level , 'value' => $points );
	}
	return $all_ranks_sorted;
}

function go_clean_ranks () {
	$all_ranks = get_option( 'go_ranks' );
	$all_ranks_sorted = array();
	foreach ( $all_ranks as $level => $points ) {
    	echo "<option value='{$points}'>{$level}</option>";
	}
}

function go_get_rank_key ( $points ) {
	global $wpdb;
	$ranks = get_option( 'go_ranks', false );
	$key = array_search( $points, $ranks['points'] );
	return $key;
}

?>