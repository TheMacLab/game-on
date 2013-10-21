<?php

function testbutton(){
	?>
    <form method="post" action="">
    <input type="submit" name="button" />
    </form>
    <?php
	
	if(isset($_POST['button'])){
update_totals( 15, 0, 11, 0);		}
	
	
	}

//adds currency and points for reasons that are not post tied.


function go_add_currency($user_id, $reason, $status, $points, $currency, $update){
	
	global $wpdb;
	   $table_name_go = $wpdb->prefix . "go";

	if($update == false){
		$wpdb->insert($table_name_go, array('uid'=> $user_id, 'reason'=> $reason, 'status'=> $status, 'points'=> $points, 'currency'=>$currency));
		} else if($update == true) {
			$wpdb->update($table_name_go,array('status'=>$status, 'points'=>$points, 'currency'=> $currency), array('uid'=>$user_id, 'reason'=>$reason));
			}
		go_update_totals($user_id,$points,$currency,0);

	}
	




// Adds currency and points for reasons that are post tied.

function go_add_post($user_id, $post_id, $status, $points, $currency, $page_id, $repeat = null){
	
	global $wpdb;
	   $table_name_go = $wpdb->prefix . "go";
	   if($status == -1){
		   $qty = $_POST['qty'];
		   $old_points = $wpdb->get_row("select * from ".$table_name_go." where uid = $user_id and post_id = $post_id ");
		   $points = $points * $qty;
		   $currency = $currency * $qty;
		   if($repeat != 'on' || empty($old_points)){
			   $wpdb->insert($table_name_go, array('uid'=> $user_id, 'post_id'=> $post_id, 'status'=> -1, 'points'=> $points, 'currency'=>$currency, 'page_id' => $page_id, 'count'=> $qty));
			   } else {
				   $wpdb->update($table_name_go,array('status'=>$status, 'points'=>$points+ ($old_points->points), 'currency'=> $currency+($old_points->currency), 'page_id' => $page_id, 'count'=> (($old_points->count)+$qty)), array('uid'=>$user_id, 'post_id'=>$post_id));
				   }
		   
	   } else {
if($repeat == 'on'){
	$old_points = $wpdb->get_row("select * from ".$table_name_go." where uid = $user_id and post_id = $post_id ");
			$wpdb->update($table_name_go,array('status'=>$status, 'points'=>$points+ ($old_points->points), 'currency'=> $currency+($old_points->currency), 'page_id' => $page_id, 'count'=> ($old_points->count)+1), array('uid'=>$user_id, 'post_id'=>$post_id));
	} else {
	if($status == 0){
		$wpdb->insert($table_name_go, array('uid'=> $user_id, 'post_id'=> $post_id, 'status'=> 1, 'points'=> $points, 'currency'=>$currency, 'page_id' => $page_id));
		} else {
	$old_points = $wpdb->get_row("select * from ".$table_name_go." where uid = $user_id and post_id = $post_id ");
			$wpdb->update($table_name_go,array('status'=>$status, 'points'=>$points+ ($old_points->points), 'currency'=> $currency+($old_points->currency), 'page_id' => $page_id), array('uid'=>$user_id, 'post_id'=>$post_id));
			}}}
	
	
	go_update_totals($user_id,$points,$currency,0);
	
	}
	
// Adds minutes.

function go_add_minutes($user_id, $minutes, $reason){
	global $wpdb;
	$table_name_go = $wpdb->prefix . "go";
	if(!empty($_POST['qty'])){
		$minutes = $minutes * $_POST['qty'];
		}
	$time = date('m/d@H:i',current_time('timestamp',0));
	$minutes_reason = array('reason'=>$reason, 'time'=>$time);
	$minutes_reason_serialized = serialize($minutes_reason);
	$wpdb->insert($table_name_go, array('uid'=> $user_id, 'minutes'=> $minutes, 'reason'=> $minutes_reason_serialized) );
	go_update_totals($user_id,0,0,$minutes);
	}
	
	
function go_notify($type, $points='', $currency='', $time='') {
	if ($points < 0 || $currency < 0) {
		$sym = '';
	} else {
		$sym = '+';
	}
	global $counter;
	$counter++;
	$space = $counter*85;
	if($type == 'points'){$display = go_display_points($points);}elseif ($type == 'currency'){$display = go_display_currency($currency);} else if($type=='Minutes'){
		$display = $time. 'Minutes';
		}
	
	// Refer to go_notification.js for explanation
	echo '<div id="go_notification" class="go_notification" style="top: '.$space.'px">'.$display.'</div><script type="text/javascript" language="javascript">	
		
		jQuery(".go_notification").fadeIn(200);
		
		var highest_index = 0;
		jQuery("*").each(function(){
			var current_index = parseInt(jQuery(this).css("z-index"), 10);
			if(current_index > highest_index){
				highest_index = current_index;
				jQuery(".go_notification").css("z-index", highest_index);
			}
		});
		setTimeout(function(){
			jQuery(".go_notification").fadeOut("slow");
		},1500)
		
		
	</script>';
}
//negatives undo
function go_add_infraction($user_id,$infractionCount,$update){
	global $wpdb;
	$infractions = $infractionCount + go_return_infractions($user_id);
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	if($update == false){
		$wpdb->insert($table_name_go_totals, array('uid'=> $user_id, 'infractions'=>$infractions));
		} else if($update == true) {
			$wpdb->update($table_name_go_totals,array('infractions'=>$infractions), array('uid'=>$user_id));
			}
}

function go_update_admin_bar($type, $title, $points_currency){
	global $next_rank_points;
	global $current_rank_points;
	

	
	if($type == 'points'){
		$display = go_display_points($points_currency); 
		$rng = ($current_rank_points -$points_currency) * -1;
		$dom = ($next_rank_points - $current_rank_points);
		echo '<script language="javascript">
			jQuery("#points_needed_to_level_up").html("'.$rng.'/'.$dom.'");
		</script>';
	} elseif ($type == 'currency'){
		$display = go_display_currency($points_currency);
	} elseif($type == 'minutes'){ 
		$display = $points_currency;
		$color = barColor($points_currency);
	}
	
	$percentage = go_get_level_percentage(get_current_user_id());
	echo '<script language="javascript">
		jQuery("#go_admin_bar_'.$type.'").html("'.$title.': '.$display.'");
		jQuery("#go_admin_bar_progress_bar").css({"width": "'.$percentage.'%", "background-color": "'.$color.'"});
	</script>';
	}


//Update totals
function go_update_totals($user_id,$points, $currency, $minutes){
	global $wpdb;
	if($points != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$totalpoints = go_return_points($user_id);
		$wpdb->update($table_name_go_totals, array('points'=> $totalpoints+$points), array('uid'=>$user_id));
		go_update_ranks($user_id, ($totalpoints+$points));
		go_notify('points', $points);
		$p = (string)($totalpoints+$points);
		go_update_admin_bar('points',go_return_options('go_points_name'),$p);
		}
	if($currency != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$totalcurrency = go_return_currency($user_id);
		$wpdb->update($table_name_go_totals, array('currency'=> $totalcurrency+$currency), array('uid'=>$user_id));
		go_notify('currency',0, $currency);
		go_update_admin_bar('currency', go_return_options('go_currency_name'), ($totalcurrency+$currency));
		}
	if($minutes != 0){
		$table_name_go_totals = $wpdb->prefix . "go_totals";
		$totalminutes = go_return_minutes($user_id);
		$wpdb->update($table_name_go_totals, array('minutes'=> $totalminutes+$minutes), array('uid'=>$user_id));
		go_notify('Minutes', 0,0,$minutes);
		go_update_admin_bar('minutes', 'Minutes', $totalminutes+$minutes);
		}
	}





function go_admin_bar_add(){
	
$points_points = $_POST['go_admin_bar_points_points'];
$points_reason = $_POST['go_admin_bar_points_reason'];

$currency_points = $_POST['go_admin_bar_currency_points'];
$currency_reason = $_POST['go_admin_bar_currency_reason'];

$minutes_points = $_POST['go_admin_bar_minutes_points'];
$minutes_reason = $_POST['go_admin_bar_minutes_reason'];
	$user_id = get_current_user_id();

if($points_points != ''&& $points_reason != ''){
	go_add_currency($user_id,$points_reason, 6, $points_points, 0, false);
	}
if($currency_points!= ''&&$currency_reason!= ''){
	go_add_currency($user_id, $currency_reason, 6, 0, $currency_points, false);

	}
if($minutes_points!= ''&&$minutes_reason != ''){
	go_add_minutes($user_id, $minutes_points, $minutes_reason);
	}
	
	die();
	
	}

function go_get_level_percentage($user_id){
	global $wpdb;
	$current_points = go_return_points($user_id);
	go_get_rank($user_id);
	global $current_currency;
	global $current_rank;
	global $next_rank_points;
	global $current_rank_points;
	$dom = ($next_rank_points-$current_rank_points);
	if($dom <= 0){ $dom = 1;}
	$percentage = ($current_points-$current_rank_points)/$dom*100;
	if($percentage <= 0){ $percentage = 0;} else if($percentage >= 100){$percentage = 100;}
	return $percentage;
	}
	
function go_get_health_percentage(){
	global $current_user_infractions;
	global $current_max_infractions;
	$percent = 100 - (($current_user_infractions / $current_max_infractions) * 100);
	return round($percent,2);
}
function go_get_health_percentage_not_current_user($user_id){
	global $wpdb;
	global $current_max_infractions;
	$infractions = go_return_infractions($user_id);
	$percent = 100 - (($infractions / $current_max_infractions) * 100);
	return round($percent,2);
}
function go_get_health_bar_color($percent){
	function rangeCheck($int, $min, $max){
			return ($int>$min && $int<$max);
		}
	switch($percent){
		case($percent >= 80):
		$color = '#00FF00';//Pure Green
		return $color;
		break;
		
		case rangeCheck($percent, 59.999, 80):
		$color = '#FFFF00';//Yellow
		return $color;
		break;
		
		case rangeCheck($percent, 39.999, 60):
		$color = '#FF6600';//"Vibrant" Orange
		return $color;
		break;
		
		case rangeCheck($percent, 19.999, 40):
		$color = '#CB6D51';//Light Red
		return $color;
		break;
		
		case ($percent < 20):
		$color = '#FF0000';//Pure Red
		return $color;
		break;
	}	
}
function go_return_options($option){
if(defined ($option) ){
return constant($option);
} else {
return get_option($option);
}
}
	function barColor($current_minutes){
		$color = '#00c100';
		function inRange($int, $min, $max){
			return ($int>$min && $int<$max);
		}
		switch ($current_minutes){
			case inRange($current_minutes, 0, PHP_INT_MAX):
				$color = '#00c100';
				return $color; 
				break;
			case inRange($current_minutes, -301, -1):
				$color = '#ffe400';
				return $color;
				break;
			case inRange($current_minutes, -601, -300):
				$color = '#ff6700';
				return $color;
				break;
			case inRange($current_minutes, -901, -600):
				$color = '#cc0000';
				return $color;
				break;
			case inRange($current_minutes, -PHP_INT_MAX, -900):
				$color = '#464646';
				return $color;
				break;
		}
		return $color;
	}
?>
