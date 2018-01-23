<?php

function debug_timer ( $data ) {
	$task_is_locked = false;
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);
     if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

function go_record_stage_time($post_id = null, $status = null) {
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	if ( ! empty( $_POST['page_id'] ) ) {
		$post_id = (int) $_POST['page_id']; // Post id posted from ajax function
	}
	if ( is_null( $status ) ) {
		$status = (int) $_POST['status']; // Task's status posted from ajax function
	}
	$time = date( 'm/d@H:i', current_time( 'timestamp', 0 ) );
	$timestamps = get_user_meta( $user_id, 'go_task_timestamps', true );

	if ( is_array( $timestamps ) ) {
		if ( empty( $timestamps[ $post_id ][ $status ] ) ) {
			$timestamps[ $post_id ][ $status ] = array(
				0 => $time,
				1 => $time,
			);
		} elseif ( 5 == $status ) {
			$timestamps[ $post_id ][ $status ][0] = $time;
		} else {
			$timestamps[ $post_id ][ $status ][1] = $time;
		}
	} else {
		$timestamps = array(
			$post_id => array(
				array(
					$time,
					$time,
				)
			),
		);
	}
	update_user_meta( $user_id, 'go_task_timestamps', $timestamps );
}

function go_task_timer( $task_id, $user_id, $future_modifier ) {
	global $wpdb;
	$unix_now = current_time( 'timestamp' );
	$user_timers = get_user_meta( $user_id, 'go_timers' );
	$accept_timestamp = 0;

	if ( ! empty( $user_timers[0][ $task_id ] ) ) {
		$accept_timestamp = $user_timers[0][ $task_id ];
	} else {
		$accept_timestamp_raw = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT timestamp 
				FROM {$wpdb->prefix}go 
				WHERE uid = %d AND post_id = %d",
				$user_id,
				$task_id
			)
		);
		$accept_timestamp = strtotime( str_replace( '@', ' ', $accept_timestamp_raw ) );
	}

	$days = (int) $future_modifier['days'] ;
	$hours = (int) $future_modifier['hours'];
	$minutes = (int) $future_modifier['minutes'];
	$seconds = (int) $future_modifier['seconds'];
	$percentage = $future_modifier['percentage'];
	$future_time = ( ! empty( $accept_timestamp ) ) ? strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) + $accept_timestamp : strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) + $unix_now;
	$countdown = $future_time - $unix_now;
	$sounded_array = ( array) get_user_meta( $user_id, 'go_sounded_tasks', true );
	?>  
	<div id='go_task_timer'></div>
	<script type='text/javascript'>
		jQuery( document ).ready( function() {
			var timer = setInterval( go_task_timer, 1000 );
			timers.push( timer );
			var countdown = <?php echo $countdown; ?>;
			var before = <?php echo $future_time?>;
			var percentage = <?php echo 100 - $percentage; ?> / 100;
			jQuery( window ).focus( function() {
				clearInterval( timer );
				timer = setInterval( go_task_timer, 1000 );
				timers.push( timer );
				var now = new Date();
				countdown = Math.floor( before - ( now.getTime() / 1000 ) + ( now.getTimezoneOffset() * 60 ) );
			});
			for ( i = 0; i < timers.length - 1; i++ ) {
				clearInterval( timers[ i ] );
			}
			function go_task_timer() {
				var sounded = <?php echo ( ( ! empty( $sounded_array['future'][ $task_id ] ) && $sounded_array['future'][ $task_id ] ) ? 'true' : 'false' ); ?>;
				countdown = countdown - 1;
				jQuery( '#go_task_timer' ).empty();
				jQuery( '.go_stage_message' ).last().parent().before( jQuery( '#go_task_timer' ) );
				if (countdown > 0) {
					var days = Math.floor( countdown / 86400) < 10 ? ( "0" + Math.floor( countdown / 86400 ) ) : Math.floor( countdown / 86400 );
					var hours = Math.floor( ( countdown - ( days * 86400 ) ) / 3600 ) < 10 ? ( "0" + Math.floor( ( countdown - ( days * 86400 ) ) / 3600 ) ) : Math.floor( ( countdown - ( days * 86400 ) ) / 3600 );
					var minutes = Math.floor( ( countdown - ( ( days * 86400 ) + ( hours * 3600 ) ) ) / 60 ) < 10 ? ( "0" + Math.floor( ( countdown - ( days * 86400 ) - ( hours * 3600 ) ) / 60) ) : Math.floor( ( countdown - ( days * 86400 ) - ( hours * 3600 ) ) / 60);
					var seconds = ( countdown - ( ( days * 86400 ) + ( hours * 3600 ) + ( minutes * 60 ) ) ) < 10 ? ( "0" + ( countdown - ( ( days * 86400 ) + ( hours * 3600 ) + ( minutes * 60 ) ) ) ) : ( countdown - ( ( days * 86400 ) + ( hours * 3600 ) + ( minutes * 60 ) ) );
					jQuery( '#go_task_timer' ).html( days + ':' +hours + ':' + minutes + ':' + seconds );
				} else {
				
					clearInterval(timer);
					
					if ( sounded === false && ! jQuery( '#go_task_timer' ).hasClass( 'sounded' ) ) {
						go_sounds( 'timer' );
						jQuery( '#go_task_timer' ).addClass( 'sounded' );
						<?php
							$sounded_array['future'][ $task_id ] = true;
							update_user_meta( $user_id, 'go_sounded_tasks', $sounded_array );
						?>
					} 
					jQuery( '#go_task_timer' ).html( "You've run out of time to <?php echo strtolower( go_return_options( 'go_third_stage_button' ) ); ?> this <?php echo strtolower( go_return_options( 'go_tasks_name' ) ); ?> for full rewards." ).css( 'color', 'red' );
					if ( percentage != 0 ) {
						if ( ! jQuery( '#go_stage_3_points' ).hasClass( 'go_updated' ) ) {
							jQuery( '#go_stage_3_points' ).html( Math.floor( parseFloat( jQuery( '#go_stage_3_points' ).html() ) * percentage ) ).addClass( 'go_updated' );
						}
						if ( ! jQuery( '#go_stage_3_currency' ).hasClass( 'go_updated' ) ) {
							jQuery( '#go_stage_3_currency' ).html( Math.floor( parseFloat( jQuery( '#go_stage_3_currency' ).html() ) * percentage ) ).addClass( 'go_updated' );
						}
					} else {
						jQuery( '#go_task_stage_3_rewards' ).html( 'Expired: No Rewards' );
					}
				}
			}
			
			// Safari caching fix
			jQuery( window ).bind( "pageshow", function( event ) {
				if ( event.originalEvent.persisted ) {
					window.location.reload();
				}
			});
			
			if ( ! jQuery( '#go_future_notification' ).is( ':visible' ) ) {
				jQuery( '#go_future_notification' ).show();
			}
			
			go_task_timer( <?php echo $countdown; ?>);
		});
	</script>
	<?php
}




function go_is_timer_expired ($custom_fields, $userid, $id, $wpdb){

	//go_mta_time_modifier is time set and modifier 
	//Time Sensitive 100Days 90%
	//a:5:{s:4:"days";d:100;s:5:"hours";d:1;s:7:"minutes";i:0;s:7:"seconds";i:0;s:10:"percentage";d:90;}
	
	//go_mta_time_filters
	//Calendar On
	//a:2:{s:8:"calendar";b:1;s:6:"future";b:0;}
	
	//go_mta_date_picker
	//1/16/18 4:02 50%
	//a:3:{s:4:"date";a:1:{i:0;s:10:"2018-01-17";}s:4:"time";a:1:{i:0;s:5:"04:02";}s:7:"percent";a:1:{i:0;s:2:"50";}}
	
	
	//go_sounded_tasks (User_meta)
	
	
	 // Array of future modifier switches, determines whether the calendar option or time after stage one option is chosen
	$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : null );
	$date_picker = ( ! empty( $custom_fields['go_mta_date_picker'][0] ) && unserialize( $custom_fields['go_mta_date_picker'][0] ) ? array_filter( unserialize( $custom_fields['go_mta_date_picker'][0] ) ) : false );
	
	//$date_update_percent = $update_percent;
	
	$future_modifier = ( ! empty( $custom_fields['go_mta_time_modifier'][0] ) ? unserialize( $custom_fields['go_mta_time_modifier'][0] ) : null );
	
	$future_timer = false;
	if ( ! empty( $future_modifier ) && ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) && ! ( $future_modifier['days'] == 0 && $future_modifier['hours'] == 0 && $future_modifier['minutes'] == 0 && $future_modifier['seconds'] == 0 ) ) {
		//this runs if timer is set 
		//find out if time is expired
		//if time is expired
		//set the $future_timer = true;
		
		//second function to set percent if future_timer = true;

		$user_timers = get_user_meta( $user_id, 'go_timers' );
		$accept_timestamp = 0;
		if ( ! empty( $user_timers[0][ $id ] ) ) {
			$accept_timestamp = $user_timers[0][ $id ];
		} else {
			$accept_timestamp_raw = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT timestamp 
					FROM {$wpdb->prefix}go 
					WHERE uid = %d AND post_id = %d",
					$user_id,
					$id
				)
			);
			$accept_timestamp = strtotime( str_replace( '@', ' ', $accept_timestamp_raw ) );
		}
		$days = (int) $future_modifier['days'];
		$hours = (int) $future_modifier['hours'];
		$minutes =  (int) $future_modifier['minutes'];
		$seconds = (int) $future_modifier['seconds'];
		$future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) + $accept_timestamp;
		if ( $status == 2 || ( ! empty( $accept_timestamp) && $status < 3 ) ) {
			go_task_timer( $id, $user_id, $future_modifier );
		}
		
		if ( $future_time != $accept_timestamp && ( ( $unix_now >= $future_time && $status >= 2 ) || ( $unix_now >= $future_time && ! empty( $accept_timestamp ) ) ) ) {
			$future_update_percent = (float) ( (100 - $future_modifier['percentage'] )/100);
			$future_timer = true;
		} else {
			$future_update_percent = 1;
		}
		if ( $status < 3 ) {
			$time_string = ( ( ! empty( $days) ) ? "{$days} day".( ( $days > 1) ? 's' : '' ).( ( ! empty( $hours ) || ! empty( $minutes ) || ! empty( $seconds ) ) ? ', ' : '' ) : '' ). 
						   ( ( ! empty( $hours) ) ? "{$hours} hour".( ( $hours > 1) ? 's' : '' ).( ( ! empty( $minutes ) || ! empty( $seconds ) ) ? ', ' : '' ) : '' ).
						   ( ( ! empty( $minutes) ) ? "{$minutes} minute".( ( $minutes > 1) ? 's' : '' ).( ( ! empty( $seconds ) ) ? ', ' : '' ) : '' ).
						   ( ( ! empty( $seconds) ) ? "{$seconds} second".( ( $seconds > 1) ? 's' : '' ) : '' );
			echo "<span id='go_future_notification'><span id='go_future_notification_task_name'>Time Sensitive 1".ucfirst( $task_name ).":</span><br/> After accepting you will have {$time_string} to reach ".go_return_options( 'go_third_stage_name' )." of this {$task_name} before the rewards will be irrevocably reduced by {$future_modifier['percentage']}%.</span>";
			$future_timer = true;
		}
	} else {
		$future_update_percent = 1;
	}

	if ( ! empty( $future_switches['calendar'] ) && $future_switches['calendar'] == 'on' ) {
		$update_percent = $date_update_percent;
	} elseif ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) {
		$update_percent = $future_update_percent;
	} else {
		$update_percent = 1;
	}

debug_timer("future Timer" . $future_timer);
	return $future_timer;

}



















function go_timer_percent ($custom_fields, $userid, $id, $wpdb){
	$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : null ); // Array of future modifier switches, determines whether the calendar option or time after stage one option is chosen
	
	$date_picker = ( ! empty( $custom_fields['go_mta_date_picker'][0] ) && unserialize( $custom_fields['go_mta_date_picker'][0] ) ? array_filter( unserialize( $custom_fields['go_mta_date_picker'][0] ) ) : false );
	$sounded_array = (array) get_user_meta( $user_id, 'go_sounded_tasks', true );
	debug_timer ( '%' . $future_switches['calendar'] );
	// if there are dates in the date picker
	if ( ! empty( $date_picker) && ( ! empty( $future_switches['calendar'] ) && $future_switches['calendar'] == 'on' ) ) {
		
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
//here debug
			$update_percent = 3; 
			return $update_percent;   
		}
		
	} else {
		$update_percent = 1;  
		return $update_percent;  
	}
	
	$date_update_percent = $update_percent;
	
	$future_modifier = ( ! empty( $custom_fields['go_mta_time_modifier'][0] ) ? unserialize( $custom_fields['go_mta_time_modifier'][0] ) : null );
	$future_timer = false;
	if ( ! empty( $future_modifier ) && ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) && ! ( $future_modifier['days'] == 0 && $future_modifier['hours'] == 0 && $future_modifier['minutes'] == 0 && $future_modifier['seconds'] == 0 ) ) {
		$user_timers = get_user_meta( $user_id, 'go_timers' );
		$accept_timestamp = 0;
		if ( ! empty( $user_timers[0][ $id ] ) ) {
			$accept_timestamp = $user_timers[0][ $id ];
		} else {
			$accept_timestamp_raw = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT timestamp 
					FROM {$wpdb->prefix}go 
					WHERE uid = %d AND post_id = %d",
					$user_id,
					$id
				)
			);
			$accept_timestamp = strtotime( str_replace( '@', ' ', $accept_timestamp_raw ) );
		}
		$days = (int) $future_modifier['days'];
		$hours = (int) $future_modifier['hours'];
		$minutes =  (int) $future_modifier['minutes'];
		$seconds = (int) $future_modifier['seconds'];
		$future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) + $accept_timestamp;
		if ( $status == 2 || ( ! empty( $accept_timestamp) && $status < 3 ) ) {
			go_task_timer( $id, $user_id, $future_modifier );
		}
		
		if ( $future_time != $accept_timestamp && ( ( $unix_now >= $future_time && $status >= 2 ) || ( $unix_now >= $future_time && ! empty( $accept_timestamp ) ) ) ) {
			$future_update_percent = (float) ( (100 - $future_modifier['percentage'] )/100);
			$future_timer = true;
		} else {
			$future_update_percent = 1;
		}
		if ( $status < 3 ) {
			$time_string = ( ( ! empty( $days) ) ? "{$days} day".( ( $days > 1) ? 's' : '' ).( ( ! empty( $hours ) || ! empty( $minutes ) || ! empty( $seconds ) ) ? ', ' : '' ) : '' ). 
						   ( ( ! empty( $hours) ) ? "{$hours} hour".( ( $hours > 1) ? 's' : '' ).( ( ! empty( $minutes ) || ! empty( $seconds ) ) ? ', ' : '' ) : '' ).
						   ( ( ! empty( $minutes) ) ? "{$minutes} minute".( ( $minutes > 1) ? 's' : '' ).( ( ! empty( $seconds ) ) ? ', ' : '' ) : '' ).
						   ( ( ! empty( $seconds) ) ? "{$seconds} second".( ( $seconds > 1) ? 's' : '' ) : '' );
			echo "<span id='go_future_notification'><span id='go_future_notification_task_name'>Time Sensitive ".ucfirst( $task_name ).":</span><br/> After accepting you will have {$time_string} to reach ".go_return_options( 'go_third_stage_name' )." of this {$task_name} before the rewards will be irrevocably reduced by {$future_modifier['percentage']}%.</span>";
		}
	} else {
		$future_update_percent = 1;
	}

	if ( ! empty( $future_switches['calendar'] ) && $future_switches['calendar'] == 'on' ) {
		$update_percent = $date_update_percent;
		return $update_percent;
	} elseif ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) {
		$update_percent = $future_update_percent;
		return $update_percent;
	} else {
		$update_percent = 1;
		return $update_percent;
	}
}


?>	


	
	


