<?php


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
    $go_current_xp = intval(go_get_user_loot( $user_id, 'xp' ));
    $rank_count = get_option('options_go_loot_xp_levels_level');
    $i = $rank_count - 1; //account for count starting at 0
    while ( $i >= 0 ) {

        $xp = intval(get_option('options_go_loot_xp_levels_level_' . $i . '_xp'));
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