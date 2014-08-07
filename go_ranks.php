<?php
/*
	This is the file that displays a page on the admin side of wordpress for the list of rankings.
	Allows administrator to edit points required for each ranking and to delete certain rankings/add others. 
*/

function go_update_ranks($user_id, $total_points){
	global $wpdb;
	global $current_rank;
	go_get_rank($user_id);
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	global $current_points;
	$ranks = get_option('go_ranks');
	if($next_rank != ''){
		if($total_points >= $next_rank_points){
			$new_next_rank = $ranks['name'][array_search($next_rank, $ranks['name']) + 1];
			$new_next_rank_points = $ranks['points'][array_search($next_rank, $ranks['name']) + 1];
			$new_rank = array(array($next_rank, $next_rank_points),	array($new_next_rank, $new_next_rank_points));
			update_user_meta($user_id, 'go_rank', $new_rank);
			if($ranks['badges'][array_search($next_rank, $ranks['name'])]){
				$badge_id = $ranks['badges'][array_search($next_rank, $ranks['name'])];
				do_shortcode("[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']");
			}
			$update = true;
		}
		if($total_points < $current_rank_points){
			$prev_rank = $ranks['name'][array_search($current_rank, $ranks['name']) - 1];
			$prev_rank_points = $ranks['points'][array_search($current_rank, $ranks['name']) - 1];
			$new_rank = array(array($prev_rank, $prev_rank_points),	array($current_rank, $current_rank_points));
			update_user_meta($user_id, 'go_rank', $new_rank);
			if($ranks['badges'][array_search($current_rank, $ranks['name'])]){
				$badge_id = $ranks['badges'][array_search($current_rank, $ranks['name'])];
				$existing_badges = get_user_meta($user_id, 'go_badges', true);
				unset($existing_badges[array_search($badge_id, $existing_badges)]);
				$badge_count = go_return_badge_count($user_id) - 1;
				$wpdb->update($wpdb->prefix."go_totals", array('badge_count' => $badge_count), array('uid' => $user_id));
				update_user_meta($user_id, 'go_badges', $existing_badges);
			}
			$update = true;
		}
	}
	if($update){
		go_get_rank($user_id);
		global $current_rank;
		global $current_rank_points;
		global $next_rank;
		global $next_rank_points;
		global $counter;
		$counter++;
		$space = $counter*85;
		echo '
		<div id="go_notification_level" class="go_notification" style="top: '.($space - 17).'px; color: #FFD700; width: 300px; height: 81.6px; font-size: 52px;"> '.$current_rank.'!</div>
		<script type="text/javascript" language="javascript">
			go_notification(3000, jQuery("#go_notification_level"));
			jQuery("#go_admin_bar_rank").html("'.$current_rank.'");
		</script>';
		$ranks_triggers = get_option('go_ranks_trigger', false);
		if($ranks_triggers){
			if($ranks_triggers[$current_rank]){
				echo do_shortcode($ranks_triggers[$current_rank]);
			}
		}	
	}
}

function go_get_rank($user_id) {
	global $wpdb;
	$rank = get_user_meta($user_id, 'go_rank');
	global $current_rank;
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	$current_rank = $rank[0][0][0];
	$current_rank_points = $rank[0][0][1];
	$next_rank = $rank[0][1][0];
	$next_rank_points = $rank[0][1][1];
}
function go_get_all_ranks() {
	$all_ranks = get_option('go_ranks');
	$all_ranks_sorted = array();
	 foreach($all_ranks as $level => $points) {
		 $all_ranks_sorted[] = array('name' => $level , 'value' => $points);
		 }
	return $all_ranks_sorted;
}
function go_clean_ranks() {
	$all_ranks = get_option('go_ranks');
	$all_ranks_sorted = array();
	foreach($all_ranks as $level => $points) {
    	echo '<option value="'.$points.'">'.$level.'</option>';
	}
}
function go_get_rank_key($points) {
	global $wpdb;
	$ranks = get_option('go_ranks',false);
	$key = array_search($points, $ranks['points']);
	return $key;
}

?>