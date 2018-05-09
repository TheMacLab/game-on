<?php


/**
 * @param $custom_fields
 * @param $user_id
 * @param $id
 * @param $go_table_name
 * @param $task_name
 * @return bool
 */
function go_timer( $custom_fields, $user_id, $id, $go_table_name, $task_name ) {
        global $wpdb;


		$time_left = go_time_left ($custom_fields, $user_id, $id, $wpdb, $go_table_name );		
		//get the start time from the go table
		$start_time = go_timer_start_time ( $wpdb, $go_table_name, $id, $user_id );
		$mod = $custom_fields['go_timer_settings_timer_mod'][0];

		//if start time is empty
		//the timer has never been started
		if ( empty($start_time)){
			$time_string = secondsToTime($time_left);
			//display message "you will have . . ."
			echo "<div class='go_timer_message'> <h3 class='go_error_red'>Timer</h3>This is a timed ".$task_name.".<br>You will have " . $time_string . " to complete this " . $task_name . " before your rewards are reduced by " . $mod . "%.";

			// Display Buttons
			echo "<div id='go_buttons' style='overflow: auto;'>";
            echo "<a id='go_abandon_task' onclick='go_timer_abandon();this.disabled = true;' style='float: left;'>Abandon</a>";
            echo "<button id='go_button' status='0' onclick='task_stage_change( this )' timer='true' button_type='timer' style='float: right;'>Start</button> ";

			echo "</div>";
            echo "</div>";
			//returning true stops the printing of the rest of the task because the timer is set but not started
			return true;
		}
		//The timer has been started before
		else {
		//else start time is set, display running timer or time's up message.
			$current_date = time(); //current date and time
			$timer_time = $time_left - $current_date;
            echo "<div class='go_timer_message'> <h3 class='go_error_red'>Timer</h3>";

            //if the time is up, display message
			if ($timer_time <= 0) {
				echo "<span>Time's up! Rewards have been reduced by " . $mod . "%.</span>";
				$time_left_ms = 0;
			}
			//else display running timer
			else{
					
				$time_left_ms = $time_left * 1000;
				echo "<span>You have a limited amount of time to complete this " . $task_name . " before rewards are reduced by " . $mod . "%.</span>";
				
			}
			echo "<div>";
            go_print_timer();
			echo "</div>";
			echo "</div>";

			echo "<script>jQuery(document).ready(function() {initializeClock('clockdiv', new Date( " . $time_left_ms . " ), true);});</script>";
			echo "<script>jQuery(document).ready(function() {initializeClock('go_timer', new Date( " . $time_left_ms . " ), true);});</script>";
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
 * @param $wpdb
 * @param $go_table_name
 * @param $id
 * @param $user_id
 * @return mixed
 */
function go_timer_start_time ( $wpdb, $go_table_name, $id, $user_id ) {
    //get the start time from the go table
    $start_time = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT start_time 
				FROM {$go_table_name} 
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
 * @param $wpdb
 * @param $go_table_name
 * @return false|int
 */
function go_time_left ($custom_fields, $user_id, $id, $wpdb, $go_table_name ) {

    $start_time = go_timer_start_time ( $wpdb, $go_table_name, $id, $user_id );
    //get amount of time in seconds on the timer
    $days = $custom_fields['go_timer_settings_days'][0];
    $hours = $custom_fields['go_timer_settings_hours'][0];
    $minutes =  $custom_fields['go_timer_settings_minutes'][0];
    $seconds = $custom_fields['go_timer_settings_seconds'][0];
    $future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) ;

    //get the start time from the go table
    if (empty($start_time)){
        //IF THIS IS THE FIRST TIME YOU CLICK START
        $time_left = $future_time;
    }else{
        $time_left = strtotime($start_time) + $future_time;
    }
    return $time_left;

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