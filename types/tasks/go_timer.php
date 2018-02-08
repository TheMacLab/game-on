<?php 
//go_timer function

function secondsToTime($seconds) {
	$dtF = new \DateTime('@0');
	$dtT = new \DateTime("@$seconds");
	return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

function go_timer_type ($custom_fields) {
	$go_mta_time_filters = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : '' );
	//get the type of timer set -- duration (future) or set date
	if (! empty( $go_mta_time_filters['future'] ) && 'on' == $go_mta_time_filters['future']){
		$timer_type = 'future';
		return $timer_type;
	}
	else if (! empty( $go_mta_time_filters['calendar'] ) && 'on' == $go_mta_time_filters['calendar']){
		$timer_type = 'calendar';
		return $timer_type;
	}
	else{
		return null;
	}
}

function go_timer_start_time ( $wpdb, $go_table_name, $id, $user_id ) {
	//get the start time from the go table
		$start_time = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT starttime 
				FROM {$go_table_name} 
				WHERE post_id = %d AND uid = %d",
				$id,
				$user_id
			)
		);
		return $start_time;
}

function go_time_left ($custom_fields, $user_id, $id, $wpdb, $go_table_name ) {

		//$timer_type = go_timer_type ($custom_fields);
		$start_time = go_timer_start_time ( $wpdb, $go_table_name, $id, $user_id );
		//$current_date = time(); //current date and time

	//if this is a set duration timer
	//if ($timer_type == 'future' ){				
		//get ammount of time in seconds on the timer
		$future_modifier = ( ! empty( $custom_fields['go_mta_time_modifier'][0] ) ? unserialize( $custom_fields['go_mta_time_modifier'][0] ) : null );
		$days = (int) $future_modifier['days'];
		$hours = (int) $future_modifier['hours'];
		$minutes =  (int) $future_modifier['minutes'];
		$seconds = (int) $future_modifier['seconds'];
		$future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) ;	

		//get the start time from the go table
		if (empty($start_time)){
			//IF THIS IS THE FIRST TIME YOU CLICK START
			$time_left = $future_time;
		}else{
			$time_left = strtotime($start_time) + $future_time;	
		}
		return $time_left;
	/*
	} 
	//if timer is set to a specific date
	else if ($timer_type == 'calendar' ){
		//get ammount of time in seconds on the timer
		$future_modifier = ( ! empty( $custom_fields['go_mta_time_modifier'][0] ) ? unserialize( $custom_fields['go_mta_time_modifier'][0] ) : null );
		$days = (int) $future_modifier['days'];
		$hours = (int) $future_modifier['hours'];
		$minutes =  (int) $future_modifier['minutes'];
		$seconds = (int) $future_modifier['seconds'];
		$future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) ;	
		$time_left =  $future_time - $current_date;
		return $time_left;
	}
	else{
		return null;
	}
	*/
}

function go_timer( $custom_fields, $user_id, $id, $wpdb, $go_table_name, $task_name ) {	

	$timer_type = go_timer_type ($custom_fields);

	if ($timer_type == null){
		return;
	} 

	//if this is a set duration timer
	if ($timer_type == 'future' ){		
		go_print_timer();
		$time_left = go_time_left ($custom_fields, $user_id, $id, $wpdb, $go_table_name );		
		//get the start time from the go table
		$start_time = go_timer_start_time ( $wpdb, $go_table_name, $id, $user_id );

		//if start time is empty
		//the timer has never been started
		if ( empty($start_time)){
			$time_string = secondsToTime($time_left);
			//display message "you will have . . ."
			echo "<div id='clock_message'> You will have " . $time_string . " to complete this " . $task_name . ".</div>";

			// Display Buttons
			echo "<div id='go_buttons'><button id='go_button' status='0' onclick='task_stage_change( this )' timer='true' button_type='timer'>Start</button> ";
			echo "<button id='go_abandon_task' onclick='go_timer_abandon();this.disabled = true;'>Abandon</button>";					
			echo "</div>";
			//returning true stops the printing of the rest of the task because the timer is set but not started
			return true;
		}
		//The timer has been started before
		else {
		//else start time is set, display running timer or time's up message.
			//$time_left = strtotime($start_time) + $future_time - $current_date;
			$current_date = time(); //current date and time
			$timer_time = $time_left - $current_date;
			//if the time is up, display message
			if ($timer_time <= 0) {
				echo "<br><span class='go_error_red'>Time's up! Rewards have been reduced</span>";
				$time_left_ms = 0;
			}
			//else display running timer
			else{
					
				$time_left_ms = $time_left * 1000;
				echo "<br><span class='go_error_red'>You have a limited amount of time to complete this " . $task_name . " before rewards are reduced!</span>";
				
			}
			echo "<script>jQuery(document).ready(function() {initializeClock('clockdiv', new Date( " . $time_left_ms . " ), true);});</script>";
			echo "<script>jQuery(document).ready(function() {initializeClock('go_timer', new Date( " . $time_left_ms . " ), true);});</script>";
			echo "<script>jQuery('#clockdiv').show();</script>";
		}
	} 
	else if ($timer_type == 'calendar' ){
		$date_picker = ( ! empty( $custom_fields['go_mta_date_picker'][0] ) && unserialize( $custom_fields['go_mta_date_picker'][0] ) ? array_filter( unserialize( $custom_fields['go_mta_date_picker'][0] ) ) : false );
		//$sounded_array = (array) get_user_meta( $user_id, 'go_sounded_tasks', true );
		
		// if there are dates in the date picker
		if ( ! empty( $date_picker) ) {
			
			$dates = $date_picker['date'];
			$times = $date_picker['time'];
			$percentages = $date_picker['percent'];
			
			// setup empty array to house which dates are closest, in unix timestamp
			$past_dates = array();
			echo "<span id='go_future_notification'><span id='go_future_notification_task_name'>Time Sensitive ".ucfirst( $task_name ).":</span><br/>";

			foreach ( $dates as $key => $date ) {
				
				// If current date in loop is in the past, add its key to the array of date modifiers
				$english_date = date( "D, F j, Y", strtotime( $date ) );
				$correct_time = date( "g:i A", strtotime( $times[ $key ] ) );

				// gets the UNIX timestamp for the date at the specified time of day
				$timestamp = strtotime( "{$date} {$times[ $key ]}" );

				echo "After {$correct_time} on {$english_date} the rewards will be irrevocably reduced by {$percentages[ $key ]}%.<br/>";
				if ( $unix_now >= $timestamp ) {
					$past_dates[] = abs( $unix_now - $timestamp );
				}
			}
			echo "</span>";

			/*
			if ( ! empty( $past_dates ) ) {
				
				// sorts dates from most recent to oldest
				asort( $past_dates );
				$update_percent = (float) ( ( 100 - $percentages[ key( $past_dates ) ] ) / 100);
				if ( ( ! empty( $sounded_array['date'] ) && ! $sounded_array['date'][ $id ] ) || empty( $sounded_array['date'] ) ) {
					?>
					<script type='text/javascript'>
						go_sounds( 'timer' );
					</script>
					<?php

					// creates the structure for the sounded tasks array, and clears any unwanted data
					// already in the array
					if ( empty( $sounded_array['date'] ) ) {
						$sounded_array = array( 'date' => array() );
					}

					// updates the status of this specified task, so the alarm doesn't sound again
					$sounded_array['date'][ $id ] = true;
					update_user_meta( $user_id, 'go_sounded_tasks', $sounded_array );
				}
			} else {
			
				$update_percent = 1;    
			}
			
		} else {
			$update_percent = 1;    
		*/
		}

	}

}

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

	


	
	