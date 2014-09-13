<?php

//adds currency and points for reasons that are not post tied.
function go_add_currency ($user_id, $reason, $status, $points, $currency, $update) {	
	global $wpdb;
	$table_name_go = $wpdb->prefix . "go";
	if ($update == false) {
		$wpdb->insert($table_name_go, array('uid'=> $user_id, 'reason'=> $reason, 'status'=> $status, 'points'=> $points, 'currency'=>$currency));
	} else if ($update == true) {
		$wpdb->update($table_name_go,array('status'=>$status, 'points'=>$points, 'currency'=> $currency), array('uid'=>$user_id, 'reason'=>$reason));
	}
	go_update_totals($user_id,$points,$currency,0,0,0);
}




// Adds currency and points for reasons that are post tied.
function go_add_post ($user_id, $post_id, $status, $points, $currency, $bonus_currency = null, $page_id, $repeat = null, $count = null, $e_fail_count = null, $a_fail_count = null, $c_fail_count = null, $m_fail_count = null, $e_passed = null, $a_passed = null, $c_passed = null, $m_passed = null) {
	global $wpdb;
	global $current_user_id;
	$table_name_go = $wpdb->prefix . "go";
	$time = date('m/d@H:i',current_time('timestamp',0));
	$penalties = go_return_penalty($user_id);
	if ($status == -1) {
		$qty = $_POST['qty'];
		$old_points = $wpdb->get_row("SELECT * FROM {$table_name_go} WHERE uid = {$user_id} and post_id = {$post_id} LIMIT 1");
		$points *= $qty;
		$currency *= $qty;
		$gifted = false;
		if ($current_user_id != $user_id) {
			$reason = 'Purchased by '.get_userdata($user_id)->display_name.' '.$qty.' times';	
			$gifted = true;
		}
	   	if ($repeat != 'on' || empty($old_points)) {
			$wpdb->insert(
				$table_name_go, 
				array(
					'uid'=> $user_id, 
					'post_id'=> $post_id, 
					'status'=> -1, 
					'points'=> $points,
					'currency'=>$currency,
					'bonus_currency' => $bonus_currency,
					'page_id' => $page_id, 
					'count'=> $qty,
					'reason' => $reason,
					'timestamp' => $time,
					'gifted' => $gifted
				)
			);
		} else {
			$wpdb->update(
				$table_name_go,
				array(
					'points'=>$points+ ($old_points->points), 
					'currency'=> $currency+($old_points->currency), 
					'bonus_currency' => $bonus_currency + ($old_points->bonus_currency),
					'page_id' => $page_id, 
					'count'=> (($old_points->count)+$qty),
					'reason' => $reason
				), 
				array(
					'uid'=> $user_id, 
					'post_id'=> $post_id
				)
			);
		}
	} else {
		if ($repeat == 'on') {
			$old_points = $wpdb->get_row("select * from ".$table_name_go." where uid = $user_id and post_id = $post_id ");
			$modded_array = go_return_multiplier($user_id, $points, $currency, $bonus_currency, $penalties);
			$modded_points = $modded_array[0];
			$modded_currency = $modded_array[1];
			$wpdb->update($table_name_go,array('status' => $status, 'points' => $modded_points + ($old_points->points), 'currency' => $modded_currency + ($old_points->currency), 'bonus_currency' => $bonus_currency + ($old_points->bonus_currency), 'page_id' => $page_id, 'count' => $count + ($old_points->count)), array('uid' => $user_id, 'post_id' => $post_id));
			
		} else {
			if ($status == 0) {
				$modded_array = go_return_multiplier($user_id, $points, $currency, $bonus_currency, $penalties);
				$modded_points = $modded_array[0];
				$modded_currency = $modded_array[1];
				$wpdb->insert($table_name_go, array('uid' => $user_id, 'post_id' => $post_id, 'status' => 1, 'points' => $modded_points, 'currency' => $modded_currency, 'bonus_currency' => $bonus_currency, 'page_id' => $page_id));
				
			} else {
				$old_points = $wpdb->get_row("select * from ".$table_name_go." where uid = $user_id and post_id = $post_id ");
				$modded_array = go_return_multiplier($user_id, $points, $currency, $bonus_currency, $penalties);
				$modded_points = $modded_array[0];
				$modded_currency = $modded_array[1];
				$wpdb->update($table_name_go,array('status' => $status, 'points' => $modded_points + ($old_points->points), 'currency' => $modded_currency + ($old_points->currency), 'bonus_currency' => $bonus_currency + ($old_points->bonus_currency), 'page_id' => $page_id), array('uid' => $user_id, 'post_id' => $post_id));
			}
		}
		if ($e_fail_count != null || $a_fail_count != null || $c_fail_count != null || $m_fail_count != null) {
			$modded_array = go_return_multiplier($user_id, $points, $currency, $bonus_currency, $penalties);
			$modded_points = $modded_array[0];
			$modded_currency = $modded_array[1];
			$wpdb->update($table_name_go, array('status' => $status, 'points' => $modded_points + ($old_points->points), 'currency' => $modded_currency + ($old_points->currency), 'bonus_currency' => $bonus_currency + ($old_points->bonus_currency), 'page_id' => $page_id, 'e_fail_count' => $e_fail_count, 'a_fail_count' => $a_fail_count, 'c_fail_count' => $c_fail_count, 'm_fail_count' => $m_fail_count, 'e_passed' => $e_passed, 'a_passed' => $a_passed, 'c_passed' => $c_passed, 'm_passed' => $m_passed), array('uid' => $user_id, 'post_id' => $post_id));
		}
	}
	go_update_totals($user_id,$points,$currency,$bonus_currency,0,$status);
}
	
// Adds bonus currency.
function go_add_bonus_currency ($user_id, $bonus_currency, $reason){
	global $wpdb;
	$table_name_go = $wpdb->prefix . "go";
	if(!empty($_POST['qty'])){
		$bonus_currency = $bonus_currency * $_POST['qty'];
	}
	$time = date('m/d@H:i',current_time('timestamp',0));
	$wpdb->insert($table_name_go, array('uid'=> $user_id, 'bonus_currency'=> $bonus_currency, 'reason'=> $reason, 'timestamp' => $time));
	go_update_totals($user_id,0,0,$bonus_currency,0, $status);
}

// Adds penalties

function go_add_penalty ($user_id, $penalty, $reason){
	global $wpdb;
	$table_name_go = $wpdb->prefix."go";
	if (!empty($_POST['qty'])) {
		$penalty = $penalty * $_POST['qty'];
	}
	$time = date('m/d@H:i',current_time('timestamp',0));
	$wpdb->insert($table_name_go, array('uid'=> $user_id, 'penalty'=> $penalty, 'reason'=> $reason, 'timestamp' => $time) );
	go_update_totals($user_id,0,0,0,$penalty, $status);
}
	
	
function go_notify ($type, $points = '', $currency = '', $bonus_currency = '', $penalty = '', $user_id = null, $display = null) {
	if($user_id != get_current_user_id()){
		return false;	
	}else{
		if ($points < 0 || $currency < 0) {
			$sym = '-';
			$color = "red";
		} else {
			$sym = '+';
			$color = "green";
		}
		global $counter;
		$counter++;
		$space = $counter*85;
		if ($type == 'points'){
			$display = go_display_points($points);
		} else if ($type == 'currency') {
			$display = go_display_currency($currency);
		} else if($type == 'bonus_currency') {
			$display = go_display_bonus_currency($bonus_currency);
		} else if($type == 'penalty') {
			$display = go_display_penalty($penalty);
		} else if($type == 'custom') {
			$display = $display;
		}
		echo "
		<div id='go_notification' class='go_notification' style='top: {$space}px; color: {$color} '>{$display}</div>
		<script type='text/javascript' language='javascript'> 
		go_notification();
		</script>";
	}
}

function go_update_admin_bar ($type, $title, $value, $status = null) {
	global $next_rank_points;
	global $current_rank_points;
	
	if ($type == 'points') {
		$display = go_display_points($value); 
		$rng = ($current_rank_points -$value) * -1;
		$dom = ($next_rank_points - $current_rank_points);
		if ($status == 0) { 
			echo "<script language='javascript'>
				jQuery(document).ready(function() {
					jQuery('#points_needed_to_level_up').html('{$rng}/{$dom}');
				});
			</script>";
		} else {
			echo "<script language='javascript'>
					jQuery('#points_needed_to_level_up').html('{$rng}/{$dom}');
			</script>";
		}
	} elseif ($type == 'currency') {
		$display = go_display_currency($value);
	} elseif ($type == 'bonus_currency') { 
		$display = go_display_bonus_currency($value);
		$current_bonus_currency = go_return_bonus_currency(get_current_user_id());
		$color = barColor($current_bonus_currency);
	} elseif ($type == 'penalty') {
		$display = go_display_penalty($value);
	}
	$percentage = go_get_level_percentage(get_current_user_id());
	echo "<script language='javascript'>
		jQuery('#go_admin_bar_{$type}').html('{$title}: {$display}');
		jQuery('#go_admin_bar_progress_bar').css({'width': '{$percentage}%'".(($color)?", 'background-color': '{$color}'":"")."});
	</script>";
}


//Update totals
function go_update_totals ($user_id, $points, $currency, $bonus_currency, $penalty, $status = null){
	global $wpdb;
	$bonuses = go_return_bonus_currency($user_id);
	$penalties = go_return_penalty($user_id);
	$modded_array = go_return_multiplier($user_id, $points, $currency, $bonuses, $penalties);
	$modded_points = $modded_array[0];
	$modded_currency = $modded_array[1];
	if($points != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$totalpoints = go_return_points($user_id);
		$wpdb->update($table_name_go_totals, array('points' => $modded_points + $totalpoints), array('uid' => $user_id));
		go_update_ranks($user_id, ($modded_points + $totalpoints));
		go_notify('points', $modded_points, 0, 0, 0, $user_id);
		$p = (string)($modded_points + $totalpoints);
		go_update_admin_bar('points',go_return_options('go_points_name'),$p, $status);
		}
	if($currency != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$totalcurrency = go_return_currency($user_id);
		$wpdb->update($table_name_go_totals, array('currency' => $modded_currency + $totalcurrency), array('uid' => $user_id));
		go_notify('currency', 0, $modded_currency, 0, 0, $user_id);
		go_update_admin_bar('currency', go_return_options('go_currency_name'), ($modded_currency + $totalcurrency));
		}
	if($bonus_currency != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$total_bonus_currency = go_return_bonus_currency($user_id);
		$wpdb->update($table_name_go_totals, array('bonus_currency'=> $total_bonus_currency+$bonus_currency), array('uid'=>$user_id));
		go_notify('bonus_currency', 0, 0, $bonus_currency, 0, $user_id);
		go_update_admin_bar('bonus_currency', go_return_options('go_bonus_currency_name'), $total_bonus_currency+$bonus_currency);
		}
	if($penalty != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$total_penalty = go_return_penalty($user_id);
		$wpdb->update($table_name_go_totals, array('penalty'=> $total_penalty+$penalty), array('uid'=>$user_id));
		go_notify('penalty', 0, 0, 0, $penalty, $user_id);
		go_update_admin_bar('penalty', go_return_options('go_penalty_name'), $total_penalty+$penalty);
		}
	}

function go_admin_bar_add () {
	$points_points = $_POST['go_admin_bar_points_points'];
	$points_reason = $_POST['go_admin_bar_points_reason'];
	
	$currency_points = $_POST['go_admin_bar_currency_points'];
	$currency_reason = $_POST['go_admin_bar_currency_reason'];
	
	$bonus_currency_points = $_POST['go_admin_bar_bonus_currency_points'];
	$bonus_currency_reason = $_POST['go_admin_bar_bonus_currency_reason'];
	
	$penalty_points = $_POST['go_admin_bar_penalty_points'];
	$penalty_reason = $_POST['go_admin_bar_penalty_reason'];
	
	$user_id = get_current_user_id();
	
	if ($points_points != '' && $points_reason != '') {
		go_add_currency($user_id,$points_reason, 6, $points_points, 0, false);
		go_update_ranks($current_user_id, $current_points);
	}
	if ($currency_points != '' && $currency_reason != '') {
		go_add_currency($user_id, $currency_reason, 6, 0, $currency_points, false);
	}
	if ($bonus_currency_points != '' && $bonus_currency_reason != '') {
		go_add_bonus_currency($user_id, $bonus_currency_points, $bonus_currency_reason);
	}
	if ($penalty_points != '' && $penalty_reason != '') {
		go_add_penalty($user_id, $penalty_points, $penalty_reason);
	}
	
	die();
}

function go_get_level_percentage ($user_id) {
	global $wpdb;
	$current_points = go_return_points($user_id);
	go_get_rank($user_id);
	global $current_currency;
	global $current_rank;
	global $next_rank_points;
	global $current_rank_points;
	$dom = ($next_rank_points-$current_rank_points);
	if ($dom <= 0){ 
		$dom = 1;
	}
	$percentage = ($current_points-$current_rank_points)/$dom*100;
	if ($percentage <= 0){ 
		$percentage = 0;
	} else if ($percentage >= 100)
		{$percentage = 100;
	}
	return $percentage;
}

function go_return_options ($option) {
	if (defined ($option)) {
		return constant($option);
	} else {
		return get_option($option);
	}
}

function barColor ($current_bonus_currency) {
	$color = '#00c100';
	function inRange ($int, $min, $max) {
		return ($int>$min && $int<=$max);
	} 
	switch ($current_bonus_currency) {
		case inRange($current_bonus_currency, 0, PHP_INT_MAX):
			$color = '#00c100';
			return $color; 
			break;
		case inRange($current_bonus_currency, -301, -1):
			$color = '#ffe400';
			return $color;
			break;
		case inRange($current_bonus_currency, -601, -300):
			$color = '#ff6700';
			return $color;
			break;
		case inRange($current_bonus_currency, -901, -600):
			$color = '#cc0000';
			return $color;
			break;
		case inRange($current_bonus_currency, -PHP_INT_MAX, -900):
			$color = '#464646';
			return $color;
			break;
	} 
	
	return $color;
}

function go_return_multiplier ($user_id, $points, $currency, $bonuses, $penalties, $return_mod = false) {
	$points = (int)$points;
	$currency = (int)$currency;
	$bonus_active = get_option('go_multiplier_switch', false);
	$penalty_active = get_option('go_penalty_switch', false);
	if ($bonus_active === 'On' && $penalty_active === 'On') {
		$bonus_threshold = (int)get_option('go_multiplier_threshold', 10);
		$penalty_threshold = (int)get_option('go_penalty_threshold', 5);
		$multiplier = ((int)get_option('go_multiplier_percentage', 20)) / 100;
		$bonus_frac = intval($bonuses / $bonus_threshold);
		$penalty_frac = intval($penalties / $penalty_threshold);
		$diff = $bonus_frac - $penalty_frac;
		if ($diff == 0) {
			if ($return_mod === false) {
				return (array($points, $currency));
			} else if ($return_mod === true) {
				return (0);
			}
		} else {
			$mod = $multiplier * $diff;
			if ($mod > 0) {
				if ($points < 0) {
					$modded_points = floor($points + ($points * $mod));
				} else {
					$modded_points = ceil($points + ($points * $mod));
				}
				if ($currency < 0) {
					$modded_currency = floor($currency + ($currency * $mod));
				} else {
					$modded_currency = ceil($currency + ($currency * $mod));
				}
			} else if ($mod < 0) {
				if ($points < 0) {
					$modded_points = ceil($points + ($points * $mod));
				} else {
					$modded_points = floor($points + ($points * $mod));
				}
				if ($currency < 0) {
					$modded_currency = ceil($currency + ($currency * $mod));
				} else {
					$modded_currency = floor($currency + ($currency * $mod));
				}
			}
			if ($return_mod === false) {
				return (array($modded_points, $modded_currency));
			} else if ($return_mod === true) {
				return ($mod);
			}
		}
	} else if ($bonus_active === 'On') {
		$bonus_threshold = (int)get_option('go_multiplier_threshold', 10);
		$multiplier = ((int)get_option('go_multiplier_percentage', 20)) / 100;
		$bonus_frac = intval($bonuses / $bonus_threshold);
		if ($bonus_frac == 0) {
			if ($return_mod === false) {
				return (array($points, $currency));
			} else if ($return_mod === true) {
				return (0);
			}
		} else {
			$mod = $multiplier * $bonus_frac;
			if ($points < 0) {
				$modded_points = floor($points + ($points * $mod));
			} else {
				$modded_points = ceil($points + ($points * $mod));
			}
			if ($currency < 0) {
				$modded_currency = floor($currency + ($currency * $mod));
			} else {
				$modded_currency = ceil($currency + ($currency * $mod));
			}
			if ($return_mod === false) {
				return (array($modded_points, $modded_currency));
			} else if ($return_mod === true) {
				return ($mod);
			}
		}
	} else if ($penalty_active === 'On') {
		$penalty_threshold = (int)get_option('go_penalty_threshold', 5);
		$multiplier = ((int)get_option('go_multiplier_percentage', 20)) / 100;
		$penalty_frac = intval($penalties / $penalty_threshold);
		if ($penalty_frac == 0) {
			if ($return_mod === false) {
				return (array($points, $currency));
			} else if ($return_mod === true) {
				return (0);
			}
		} else {
			$mod = $multiplier * (-$penalty_frac);
			if ($points < 0) {
				$modded_points = ceil($points + ($points * $mod));
			} else {
				$modded_points = floor($points + ($points * $mod));
			}
			if ($currency < 0) {
				$modded_currency = ceil($currency + ($currency * $mod));
			} else {
				$modded_currency = floor($currency + ($currency * $mod));
			}
			if ($return_mod === false) {
				return (array($modded_points, $modded_currency));
			} else if ($return_mod === true) {
				return ($mod);
			}
		}
	} else {
		return (array($points, $currency));
	}
}
?>