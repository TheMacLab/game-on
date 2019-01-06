<?php

//timer_start_time (time the timer start_button was clicked)
//timer_setting
//end time (time that the timer is expired) = timer_start_time + timer_setting
//time_left = end_time - current time

////timer length (time on timer)
/**
 * TIMER
 * @param $custom_fields
 * @param $is_logged_in
 * @param $user_id
 * @param $post_id
 * @param $task_name
 * @return bool
 */
function go_display_timer ($custom_fields, $is_logged_in, $user_id, $post_id, $task_name){
    $timer_on = $custom_fields['go_timer_toggle'][0];
    if ($timer_on && $is_logged_in) {
        $timer_status = go_timer($custom_fields, $user_id, $post_id, $task_name);
        //if ($timer_status == true) {
        return $timer_status;
        //}
    }
}

/**
 * @param $custom_fields
 * @param $user_id
 * @param $id
 * @param $task_name
 * @return bool
 */
function go_timer( $custom_fields, $user_id, $id, $task_name ) {

		$end_time = go_end_time ($custom_fields, $user_id, $id );//start + timer time
		//get the start time from the go table
		$start_time = go_timer_start_time ( $id, $user_id );
        $mod = (isset($custom_fields['go_timer_settings_timer_mod'][0]) ?  $custom_fields['go_timer_settings_timer_mod'][0] : 0);

    //if start time is empty
		//the timer has never been started
		if ( empty($start_time)){
			$time_string = secondsToTime($end_time);
			//display message "you will have . . ."
			echo "<div class='go_timer_message'> <h3 class='go_error_red'>Timer</h3>This is a timed ".$task_name.".<br>You will have " . $time_string . " to complete this " . $task_name . " before your rewards are reduced by " . $mod . "%.";

			// Display Buttons
            $db_status = go_get_status($id, $user_id);
			echo "<div id='go_buttons' style='overflow: auto;'>";
            echo "<a id='go_abandon_task' onclick='go_timer_abandon();this.disabled = true;' style='float: left;'>Abandon</a>";
            echo "<button id='go_button' status='" . $db_status . "'  timer='true' button_type='timer' style='float: right;'>Start</button> ";

			echo "</div>";
            echo "</div>";

			//returning true stops the printing of the rest of the task because the timer is set but not started
			return true;
		}
		//The timer has been started before
		else {
		//else start time is set, display running timer or time's up message.
			$current_date = strtotime(current_time('mysql')); //current date and time
			$timer_time = $end_time - $current_date;
            echo "<div class='go_timer_message'> <h3 class='go_error_red'>Timer</h3>";

            //if the time is up, display message
			if ($timer_time <= 0) {
				echo "<span>Time's up! Rewards have been reduced by " . $mod . "%.</span>";
				$end_time = 0;
			}
			//else display running timer
			else{
				echo "<span>You have a limited amount of time to complete this " . $task_name . " before rewards are reduced by " . $mod . "%.</span>";
				
			}
			echo "<div>";
            go_print_timer();
			echo "</div>";
			echo "</div>";
            $timer_time = $timer_time * 1000;


			echo "<script>jQuery(document).ready(function() {initializeClock('clockdiv', $timer_time);});</script>";
			echo "<script>jQuery(document).ready(function() {initializeClock('go_timer', $timer_time);});</script>";
			echo "<script>jQuery('#clockdiv').show();</script>";
			return false;
		}
}

/**
 * @param $seconds
 * @return string
 */
function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

/**
 * @param $id
 * @param $user_id
 * @return mixed
 */
function go_timer_start_time ($id, $user_id ) {
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";

    //get the start time from the go table
    $start_time = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT timer_time 
				FROM {$go_task_table_name} 
				WHERE post_id = %d AND uid = %d",
            $id,
            $user_id
        )
    );
    return $start_time;
}

/**
 * @param $custom_fields
 * @param $user_id
 * @param $id
 * @return false|int
 */
function go_end_time ($custom_fields, $user_id, $id ) { //returns the time the timer will expire if running, or the time set if not

    $start_time = go_timer_start_time ($id, $user_id );
    //get amount of time in seconds on the timer
    $days = $custom_fields['go_timer_settings_days'][0];
    $hours = $custom_fields['go_timer_settings_hours'][0];
    $minutes =  $custom_fields['go_timer_settings_minutes'][0];
    $seconds = $custom_fields['go_timer_settings_seconds'][0];
    $future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) ;
    //get the start time from the go table
    if (empty($start_time)){
        //IF THIS IS THE FIRST TIME YOU CLICK START
        $end_time = $future_time;
    }else{
        $end_time = strtotime($start_time) + $future_time;
    }
    return $end_time;

}

/**
 *
 */
function go_print_timer () {
	?>

	<div id='clockdiv' style='display: none;'>  
		<div>    <span class='days'></span>    
			<div class='smalltext'>Days</div>  
		</div>  
		<div>    <span class='hours'></span>    
			<div class='smalltext'>Hours</div>  
		</div>  
		<div>    <span class='minutes'></span>    
			<div class='smalltext'>Minutes</div>  
		</div>  
		<div>    
			<span class='seconds'></span>    
			<div class='smalltext'>Seconds</div>  
		</div>
	</div>

	<?php
}