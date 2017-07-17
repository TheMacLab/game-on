<?php
session_start();
/*
	This is the file that displays content in a post/page with a task.
	This file interprets and executes the shortcode in a post's body.
*/
// Task Shortcode
function go_task_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts( array(
		'id' => '', // ID defined in Shortcode
		'cats' => '', // Cats defined in Shortcode     
	), $atts);
	$id = $atts['id'];
	global $wpdb;

	// the current user's id
	$user_id = get_current_user_id();
	$page_id = get_the_ID();

	// If the shortcode has an attribute called id, run this code
	if ( $id && ! empty( $user_id ) ) {
		$task_shortcode_nonces = array(
			'go_task_abandon' => wp_create_nonce( 'go_task_abandon_' . $id . '_' . $user_id ),
			'go_unlock_stage' => wp_create_nonce( 'go_unlock_stage_' . $id . '_' . $user_id ),
			'go_test_point_update' => wp_create_nonce( 'go_test_point_update_' . $id . '_' . $user_id ),
			'go_task_change_stage' => wp_create_nonce( 'go_task_change_stage_' . $id . '_' . $user_id ),
		);
		$task_pods = get_option( 'go_task_pod_globals' );
		$pods_array = wp_get_post_terms( get_the_id(), 'task_pods' );
		if ( ! empty( $pods_array ) ) {
			$pod_slug = $pods_array[0]->slug;
			$pod_link = ( ! empty( $task_pods[ $pod_slug ][ 'go_pod_link' ] ) ? $task_pods[ $pod_slug ][ 'go_pod_link' ] : '' );
		}
		$today = date( 'Y-m-d' );
		$task_name = strtolower( go_return_options( 'go_tasks_name' ) );
		$custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id
		$rewards = ( ! empty( $custom_fields['go_presets'][0] ) ? unserialize( $custom_fields['go_presets'][0] ) : null );
		$mastery_active = ( ! empty( $custom_fields['go_mta_three_stage_switch'][0] ) ? ! $custom_fields['go_mta_three_stage_switch'][0] : true ); // whether or not the mastery stage is active
		$repeat = ( ! empty( $custom_fields['go_mta_five_stage_switch'][0] ) ? $custom_fields['go_mta_five_stage_switch'][0] : '' ); // Whether or not you can repeat the task
		$unix_now = current_time( 'timestamp' ); // Current unix timestamp
		$go_table_name = "{$wpdb->prefix}go";
		$task_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count 
				FROM {$go_table_name} 
				WHERE post_id = %d AND uid = %d",
				$id,
				$user_id
			)
		);
		$status = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT status 
				FROM {$go_table_name} 
				WHERE post_id = %d AND uid = %d",
				$id,
				$user_id
			)
		);
		
		$e_admin_lock = get_post_meta( $id, 'go_mta_encounter_admin_lock', true );
		if ( ! empty( $e_admin_lock ) ) {
			$e_is_locked = ( ! empty( $e_admin_lock[0] ) ? true : false );
			if ( $e_is_locked ) {
				$e_pass_lock = ( ! empty( $e_admin_lock[1] ) ? $e_admin_lock[1] : '' );
			}
		}

		$a_admin_lock = get_post_meta( $id, 'go_mta_accept_admin_lock', true );
		if ( ! empty( $a_admin_lock ) ) {
			$a_is_locked = ( ! empty( $a_admin_lock[0] ) ? true : false );
			if ( $a_is_locked ) {
				$a_pass_lock = ( ! empty( $a_admin_lock[1] ) ? $a_admin_lock[1] : '' );
			}
		}

		$c_admin_lock = get_post_meta( $id, 'go_mta_completion_admin_lock', true );
		if ( ! empty( $c_admin_lock ) ) {
			$c_is_locked = ( ! empty( $c_admin_lock[0] ) ? true : false );
			if ( $c_is_locked ) {
				$c_pass_lock = ( ! empty( $c_admin_lock[1] ) ? $c_admin_lock[1] : '' );
			}
		}

		$m_admin_lock = get_post_meta( $id, 'go_mta_mastery_admin_lock', true );
		if ( ! empty( $m_admin_lock ) ) {
			$m_is_locked = ( ! empty( $m_admin_lock[0] ) ? true : false );
			if ( $m_is_locked ) {
				$m_pass_lock = ( ! empty( $m_admin_lock[1] ) ? $m_admin_lock[1] : '' );
			}
		}
		
		$r_admin_lock = get_post_meta( $id, 'go_mta_repeat_admin_lock', true );
		if ( ! empty( $r_admin_lock ) ) {
			$r_is_locked = ( ! empty( $r_admin_lock[0] ) ? true : false );
			if ( $r_is_locked ) {
				$r_pass_lock = ( ! empty( $r_admin_lock[1] ) ? $r_admin_lock[1] : '' );
			}
		}

		$e_url_is_locked = ( ! empty( $custom_fields['go_mta_encounter_url_key'][0] ) ? true : false );
		$a_url_is_locked = ( ! empty( $custom_fields['go_mta_accept_url_key'][0] ) ? true : false );
		$c_url_is_locked = ( ! empty( $custom_fields['go_mta_completion_url_key'][0] ) ? true : false );
		$m_url_is_locked = ( ! empty( $custom_fields['go_mta_mastery_url_key'][0] ) ? true : false );

		$test_e_active = ( ! empty( $custom_fields['go_mta_test_encounter_lock'][0] ) ? $custom_fields['go_mta_test_encounter_lock'][0] : false );
		$test_a_active = ( ! empty( $custom_fields['go_mta_test_accept_lock'][0] ) ? $custom_fields['go_mta_test_accept_lock'][0] : false );
		$test_c_active = ( ! empty( $custom_fields['go_mta_test_completion_lock'][0] ) ? $custom_fields['go_mta_test_completion_lock'][0] : false );
		
		$number_of_stages = 4;
		
		if ( $test_e_active ) {
			$test_e_array = go_task_get_test_meta( 'encounter', $id );
			$test_e_returns = $test_e_array[0];
			$test_e_num = $test_e_array[1];
			$test_e_all_questions = $test_e_array[2][0];
			$test_e_all_types = $test_e_array[2][1];
			$test_e_all_answers = $test_e_array[2][2];
			$test_e_all_keys = $test_e_array[2][3];
		}
		$encounter_upload = ( ! empty( $custom_fields['go_mta_encounter_upload'][0] ) ? $custom_fields['go_mta_encounter_upload'][0] : false );

		if ( $test_a_active ) {
			$test_a_array = go_task_get_test_meta( 'accept', $id );
			$test_a_returns = $test_a_array[0];
			$test_a_num = $test_a_array[1];
			$test_a_all_questions = $test_a_array[2][0];
			$test_a_all_types = $test_a_array[2][1];
			$test_a_all_answers = $test_a_array[2][2];
			$test_a_all_keys = $test_a_array[2][3];
		}
		$accept_upload = ( ! empty( $custom_fields['go_mta_accept_upload'][0] ) ? $custom_fields['go_mta_accept_upload'][0] : false );

		if ( $test_c_active ) {
			$test_c_array = go_task_get_test_meta( 'completion', $id );
			$test_c_returns = $test_c_array[0];
			$test_c_num = $test_c_array[1];
			$test_c_all_questions = $test_c_array[2][0];
			$test_c_all_types = $test_c_array[2][1];
			$test_c_all_answers = $test_c_array[2][2];
			$test_c_all_keys = $test_c_array[2][3];
		}
		$completion_message = ( ! empty( $custom_fields['go_mta_complete_message'][0] ) ? $custom_fields['go_mta_complete_message'][0] : '' ); // Completion Message
		$completion_upload = ( ! empty( $custom_fields['go_mta_completion_upload'][0] ) ? $custom_fields['go_mta_completion_upload'][0] : false );
		
		if ( $mastery_active ) {
			$test_m_active = ( ! empty( $custom_fields['go_mta_test_mastery_lock'][0] ) ? $custom_fields['go_mta_test_mastery_lock'][0] : false );

			if ( $test_m_active ) {
				$test_m_array = go_task_get_test_meta( 'mastery', $id );
				$test_m_returns = $test_m_array[0];
				$test_m_num = $test_m_array[1];
				$test_m_all_questions = $test_m_array[2][0];
				$test_m_all_types = $test_m_array[2][1];
				$test_m_all_answers = $test_m_array[2][2];
				$test_m_all_keys = $test_m_array[2][3];
			}
			$mastery_message = ( ! empty( $custom_fields['go_mta_mastery_message'][0] ) ? $custom_fields['go_mta_mastery_message'][0] : '' ); // Mastery Message
			$mastery_upload = ( ! empty( $custom_fields['go_mta_mastery_upload'][0] ) ? $custom_fields['go_mta_mastery_upload'][0] : false );

			if ( $repeat == 'on' ) {    // Checks if the task is repeatable and if it has a repeat limit
				$repeat_amount = ( ! empty( $custom_fields['go_mta_repeat_amount'][0] ) ? $custom_fields['go_mta_repeat_amount'][0] : 0 );
				$repeat_message = ( ! empty( $custom_fields['go_mta_repeat_message'][0] ) ? $custom_fields['go_mta_repeat_message'][0] : '' );
				$repeat_upload = ( ! empty( $custom_fields['go_mta_repeat_upload'][0] ) ? $custom_fields['go_mta_repeat_upload'][0] : false );
				$number_of_stages = 5;
			}
		} else {
			$number_of_stages = 3;  
		}
		
		// Checks if the task has a bonus currency filter
		// Sets the filter equal to the meta field value declared in the task creation page, if none exists defaults to 0
		$bonus_currency_required = ( ! empty( $custom_fields['go_mta_bonus_currency_filter'][0] ) ? $custom_fields['go_mta_bonus_currency_filter'][0] : 0 );
		
		// Checks if the task has a penalty filter
		$penalty_filter = ( ! empty( $custom_fields['go_mta_penalty_filter'][0] ) ? $custom_fields['go_mta_penalty_filter'][0] : null );
		
		$locked_by_category = ( ! empty( $custom_fields['go_mta_focus_category_lock'][0] ) ? $custom_fields['go_mta_focus_category_lock'][0] : null );
		$focus_category_lock = ( ! empty( $locked_by_category ) ? true : false );

		$description = ( ! empty( $custom_fields['go_mta_quick_desc'][0] ) ? $custom_fields['go_mta_quick_desc'][0] : '' ); // Description

		// gets the user's current badges
		$user_badges = get_user_meta( $user_id, 'go_badges', true );
		if ( ! $user_badges ) {
			$user_badges = array();
		}

		// gets an array of badge IDs to prevent users who don't have the badges from viewing the task
		$badge_filter_meta = get_post_meta( $id, 'go_mta_badge_filter', true );

		// an array of badge IDs
		$badge_filter_ids = array();

		// determines if the user has the correct badges
		$badge_filtered = false;
		$badge_diff = array();
		if ( ! empty( $badge_filter_meta ) && isset( $badge_filter_meta[0] ) && $badge_filter_meta[0] ) {
			$badge_filter_ids = array_filter( (array) $badge_filter_meta[1], 'go_badge_exists' );

			// checks to see if the filter array are in the the user's badge array
			$intersection = array_values( array_intersect( $user_badges, $badge_filter_ids ) );

			// stores an array of the badges that were not found in the user's badge array
			$badge_diff = array_values( array_diff( $badge_filter_ids, $intersection ) );
			if ( ! empty( $badge_filter_ids ) && ! empty( $badge_diff ) ) {
				$badge_filtered = true;
			}
		}

		$points_array = ( ! empty( $rewards['points'] ) ? $rewards['points'] : array() );
		$points_str = implode( ' ', $points_array );
		$currency_array = ( ! empty( $rewards['currency'] ) ? $rewards['currency'] : array() );
		$currency_str = implode( ' ', $currency_array );
		$bonus_currency_array = ( ! empty( $rewards['bonus_currency'] ) ? $rewards['bonus_currency'] : array() );
		$bonus_currency_str = implode( ' ', $bonus_currency_array );
		
		$current_bonus_currency = go_return_bonus_currency( $user_id ); 
		$current_penalty = go_return_penalty( $user_id );

		$go_admin_email = get_option( 'go_admin_email' );
		if ( $go_admin_email ) {
			$admin = get_user_by( 'email', $go_admin_email );
		}

		// use display name of admin with store email, or use default name
		if ( ! empty( $admin ) ) {
			$admin_name = addslashes( $admin->display_name );
		} else {
			$admin_name = 'an administrator';
		}
		$is_admin = go_user_is_admin( $user_id );
		
		$content_post = get_post( $id ); // Grabs content of a task from the post table in your wordpress database where post_id = id in the shortcode. 
		$task_content = $content_post->post_content; // Grabs what the task actually says in the body of it
		
		if ( $task_content != '' && empty( $custom_fields['go_mta_accept_message'] ) ) { // If content is returned from the post table, and the post doesn't have an accept message meta field, run this code
			add_post_meta( $id, 'go_mta_accept_message', $task_content ); // Add accept message meta field with value of the post's content from post table
		} else { // If the task has content in the post table, and has a meta field, run this code
			$accept_message = ( ! empty( $custom_fields['go_mta_accept_message'][0] ) ? $custom_fields['go_mta_accept_message'][0] : '' ); // Set value of accept message equal to the task's accept message meta field value
		}

		// retrieves the date and time, if specified, after which non-admins can accept this task
		$start_filter = get_post_meta( $id, 'go_mta_start_filter', true );

		// holds the output to be displayed when a non-admin has been stopped by the start filter
		$time_string = '';
		if ( ! empty( $start_filter ) && ! empty( $start_filter['checked'] ) && ! $is_admin ) {
			$start_date = $start_filter['date'];
			$start_time = $start_filter['time'];
			$start_unix = strtotime( $start_date . $start_time );

			// stops execution if the user is a non-admin and the start date and time has not
			// passed yet
			if ( $unix_now < $start_unix ) {
				$time_string = date( 'g:i A', $start_unix ) . ' on ' . date( 'D, F j, Y', $start_unix );
				return "<span id='go_future_notification'>Will be available at {$time_string}.</span>";
			}
		}

		$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : null ); // Array of future modifier switches, determines whether the calendar option or time after stage one option is chosen
		
		$date_picker = ( ! empty( $custom_fields['go_mta_date_picker'][0] ) && unserialize( $custom_fields['go_mta_date_picker'][0] ) ? array_filter( unserialize( $custom_fields['go_mta_date_picker'][0] ) ) : false );
		$sounded_array = (array) get_user_meta( $user_id, 'go_sounded_tasks', true );
		
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
				$update_percent = 1;    
			}
			
		} else {
			$update_percent = 1;    
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
		} elseif ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) {
			$update_percent = $future_update_percent;
		} else {
			$update_percent = 1;
		}

		// displays rewards before task contents
		go_display_rewards( $user_id, $points_array, $currency_array, $bonus_currency_array, $update_percent, $number_of_stages, $future_timer );
		?>
		<script type="text/javascript">
			<?php if ( ! empty( $future_switches['calendar'] ) && $future_switches['calendar'] == 'on' && $update_percent != 1 ) { ?>
				var num_of_stages = parseInt( <?php echo $number_of_stages; ?> );
				for ( i = 1; i <= num_of_stages; i++ ) {
					var stage_points = jQuery( '#go_stage_' + i + '_points' );
					var stage_currency = jQuery( '#go_stage_' + i + '_currency' );
					var stage_bonus_currency = jQuery( '#go_stage_' + i + '_bonus_currency' );
					if ( stage_points.length && ! stage_points.hasClass( 'go_updated' ) ) {
						stage_points.addClass( 'go_updated' );
					}
					if ( stage_currency.length && ! stage_currency.hasClass( 'go_updated' ) ) {
						stage_currency.addClass( 'go_updated' );
					}
					if ( stage_bonus_currency.length && ! stage_bonus_currency.hasClass( 'go_updated' ) ) {
						stage_bonus_currency.addClass( 'go_updated' );
					}
				}
			<?php } ?>
			var timers = [];

			jQuery( '.entry-title' ).after( jQuery( '.go_task_rewards' ) );
			<?php
				if ( $update_percent != 1 && ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) ) {
				?>
					jQuery( '#go_stage_3_points' ).addClass( 'go_updated' );
					jQuery( '#go_stage_3_currency' ).addClass( 'go_updated' );
				<?php
				}
				if ( $status >= 3 && ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) ) {
					?>
						jQuery( '#go_future_notification' ).hide();
					<?php
				}
			?>

			if ( jQuery( '#go_task_timer' ).length ) {
				jQuery( '.go_task_rewards' ).after( jQuery( '#go_task_timer' ) );
			}
		</script>
		<?php

		// checks that non-admin users are allowed to access this task
		if ( ! $is_admin && $badge_filtered ) {
			$return_badge_list = true;

			// outputs all the badges that the user must obtain before beginning this task
			printf(
				'You need the following badge(s) to begin this %s:<br/>%s',
				$task_name,
				go_badge_output_list( $badge_diff, $return_badge_list )
			);
		} else {
			
			switch ( $status ) {
				case ( 0 ):
					$db_task_stage_upload_var = 'e_uploaded';
					break;
				case ( 1 ):
					$db_task_stage_upload_var = 'a_uploaded';
					break;
				case ( 2 ):
					$db_task_stage_upload_var = 'c_uploaded';
					break;
				case ( 3 ):
					$db_task_stage_upload_var = 'm_uploaded';
					break;
				case ( 4 ):
					$db_task_stage_upload_var = 'r_uploaded';
					break;
			}
			if ( ! empty( $db_task_stage_upload_var ) ) {
				$is_uploaded = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT {$db_task_stage_upload_var} 
						FROM {$go_table_name} 
						WHERE uid = %d AND post_id = %d",
						$user_id,
						$id
					)
				);
			} else {
				$is_uploaded = 0;
			}
			
			if ( get_the_terms( $id, 'task_focus_categories' ) && $focus_category_lock ) {
				$categories = get_the_terms( $id, 'task_focus_categories' );
				$category_names = array();
				foreach( $categories as $category ) {
					array_push( $category_names, $category->name ); 
				}
			}
			
			if ( get_user_meta( $user_id, 'go_focus', true ) != '' ) {
				$user_focus = get_user_meta( $user_id, 'go_focus', true );  
			}
			
			if ( ! empty( $category_names ) && $user_focus ) {
				$go_ahead = array_intersect( $user_focus, $category_names );
			}
		?>
			<div id="go_description"><div class="go_stage_message"><?php echo  do_shortcode( wpautop( $description ) ); ?></div></div>
		<?php   
		
		if ( ( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) && $status < 2 ) {
			$update_percent = 1;    
		}       

		$chain_order = get_post_meta( $id, 'go_mta_chain_order', true );

		// determines whether or not the user can proceed, if the task is in a chain
		if ( ! empty( $chain_order ) ) {
			$chain_links = array();

			foreach ( $chain_order as $chain_tt_id => $order ) {
				$pos = array_search( $id, $order );
				$the_chain = get_term_by( 'term_taxonomy_id', $chain_tt_id );
				$chain_title = ucwords( $the_chain->name );

				if ( $pos > 0 && ! $is_admin ) {

					/**
					 * The current task is not first and the user is not an administrator.
					 */

					$prev_id = 0;

					// finds the first ID among the tasks before the current one that is published
					for ( $prev_id_counter = 0; $prev_id_counter < $pos; $prev_id_counter++ ) {
						$temp_id = $order[ $prev_id_counter ];
						$temp_task = get_post( $temp_id );

						$temp_finished           = true;
						$temp_status             = go_task_get_status( $temp_id );
						$temp_five_stage_counter = null;
						$temp_status_required    = 4;
						$temp_three_stage_active = (boolean) get_post_meta(
							$temp_id,
							'go_mta_three_stage_switch',
							true
						);
						$temp_five_stage_active  = (boolean) get_post_meta(
							$temp_id,
							'go_mta_five_stage_switch',
							true
						);

						// determines to what stage the user has to progress to finish the task
						if ( $temp_three_stage_active ) {
							$temp_status_required = 3;
						} elseif ( $temp_five_stage_active ) {
							$temp_five_stage_counter = go_task_get_repeat_count( $temp_id );
						}

						// determines whether or not the task is finished
						if ( $temp_status !== $temp_status_required &&
								( ! $temp_five_stage_active ||
								( $temp_five_stage_active && empty( $temp_five_stage_counter ) ) ) ) {

							$temp_finished = false;
						}

						if ( ! empty( $temp_task ) &&
								'publish' === $temp_task->post_status &&
								! $temp_finished ) {

							/**
							 * The task is published, but is not finished. This task must be finished
							 * before the current task can be accepted.
							 */

							$prev_id = $temp_id;
							break;
						}
					}

					if ( 0 !== $prev_id ) {
						$prev_permalink = get_permalink( $prev_id );
						$prev_title = get_the_title( $prev_id );

						$link_tag = sprintf(
							'<a href="%s">%s (%s)</a>',
							$prev_permalink,
							$prev_title,
							$chain_title
						);
						if ( false === array_search( $link_tag, $chain_links ) ) {

							// appends the anchor tag for previous task
							$chain_links[] = $link_tag;
						}
					}
				}
			}

			if ( ! empty( $chain_links ) ) {
				$link_str = '';
				for ( $link_counter = 0; $link_counter < count( $chain_links ); $link_counter++ ) {
					if ( $link_counter > 0 ) {
						$link_str .= ', ';
						if ( count( $chain_links ) > 2 && count( $chain_links ) === $link_counter + 1 ) {
							$link_str .= 'and ';
						}
					}
					$link_str .= $chain_links[ $link_counter ];
				}

				printf(
					'<div class="go_chain_msg_container">'.
						'<div class="go_chain_msg_content">You must finish %s to continue this %s.</div>'.
					'</div>',
					$link_str,
					ucwords( $task_name )
				);

				return false;
			}
		}

		if ( $is_admin === true || ! empty( $go_ahead ) || ! isset( $focus_category_lock ) || empty( $category_names ) ) {
			if ( ( empty( $bonus_currency_required ) || 
						( ! empty( $bonus_currency_required ) && $current_bonus_currency >= $bonus_currency_required ) ) &&
					( empty( $penalty_filter ) ||
						( ! empty( $penalty_filter ) && $current_penalty < $penalty_filter ) ) ) {
				switch ( $status ) {
					
					// First time a user encounters a task
					case 0:

					// sending go_add_post the $repeat var was the problem, that is why it is now sending a null value.
					go_add_post(
						$user_id,
						$id,
						0,
						floor( ( $update_percent * $points_array[0] ) ),
						floor( ( $update_percent * $currency_array[0] ) ),
						floor( ( $update_percent * $bonus_currency_array[0] ) ),
						null,
						$page_id,
						false,
						0,
						0,
						0,
						0,
						0
					);
					go_record_stage_time( $page_id, 1 );
		?>
					<div id="go_content">
					<?php
						if ( $test_e_active ) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ( $test_e_num > 1 ) {
								for ( $i = 0; $i < $test_e_num; $i++ ) {
									if ( ! empty( $test_e_all_types[ $i ] ) &&
											! empty( $test_e_all_questions[ $i ] ) &&
											! empty( $test_e_all_answers[ $i ] ) &&
											! empty( $test_e_all_keys[ $i ] ) ) {

										echo do_shortcode( "[go_test type='".$test_e_all_types[ $i ]."' question='".$test_e_all_questions[ $i ]."' possible_answers='".$test_e_all_answers[ $i ]."' key='".$test_e_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_e_num."']" );
									}
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} elseif ( ! empty( $test_e_all_types[0] ) &&
									! empty( $test_e_all_questions[0] ) &&
									! empty( $test_e_all_answers[0] ) &&
									! empty( $test_e_all_keys[0] ) ) {

								echo do_shortcode( "[go_test type='".$test_e_all_types[0]."' question='".$test_e_all_questions[0]."' possible_answers='".$test_e_all_answers[0]."' key='".$test_e_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ( $encounter_upload ) {
							echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
						}
					?>
					<p id='go_stage_error_msg' style='display: none; color: red;'></p>
					<?php 
					if ( $e_is_locked && ! empty( $e_pass_lock ) ) {
						echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
					} elseif ( $e_url_is_locked === true ) {
						echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
					}
					?>
					<button id="go_button" status= "2" onclick="task_stage_change( this );" <?php if ( $e_is_locked && empty( $e_pass_lock ) ) {echo "admin_lock='true'"; } ?>><?php echo go_return_options( 'go_second_stage_button' ) ?></button>
					<button id="go_abandon_task" onclick="go_task_abandon();this.disabled = true;"><?php echo get_option( 'go_abandon_stage_button', 'Abandon' ); ?></button>
					<?php

					go_task_render_chain_pagination( $id, $user_id );

					echo '</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
					break;
					
					// Encountered
					case 1: 
		?>
					<div id="go_content">
					<?php
						if ( $test_e_active ) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ( $test_e_num > 1) {
								for ( $i = 0; $i < $test_e_num; $i++ ) {
									if ( ! empty( $test_e_all_types[ $i ] ) &&
											! empty( $test_e_all_questions[ $i ] ) &&
											! empty( $test_e_all_answers[ $i ] ) &&
											! empty( $test_e_all_keys[ $i ] ) ) {

										echo do_shortcode( "[go_test type='".$test_e_all_types[ $i ]."' question='".$test_e_all_questions[ $i ]."' possible_answers='".$test_e_all_answers[ $i ]."' key='".$test_e_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_e_num."']" );
									}
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} elseif ( ! empty( $test_e_all_types[0] ) &&
									! empty( $test_e_all_questions[0] ) &&
									! empty( $test_e_all_answers[0] ) &&
									! empty( $test_e_all_keys[0] ) ) {

								echo do_shortcode( "[go_test type='".$test_e_all_types[0]."' question='".$test_e_all_questions[0]."' possible_answers='".$test_e_all_answers[0]."' key='".$test_e_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ( $encounter_upload ) {
							echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
						}
					?>
					<p id='go_stage_error_msg' style='display: none; color: red;'></p>
					<?php 
					if ( $e_is_locked && ! empty( $e_pass_lock ) ) {
						echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
					} elseif ( $e_url_is_locked === true ) {
						echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
					}
					?>
					<button id="go_button" status= "2" onclick="task_stage_change( this );" <?php if ( $e_is_locked && empty( $e_pass_lock ) ) {echo "admin_lock='true'"; } ?>><?php echo go_return_options( 'go_second_stage_button' ) ?></button>
					<button id="go_abandon_task" onclick="go_task_abandon();this.disabled = true;"><?php echo get_option( 'go_abandon_stage_button', 'Abandon' ); ?></button>
					<?php

					go_task_render_chain_pagination( $id, $user_id );

					echo '</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
					break;
					
					// Accepted
					case 2: 
						echo '<div id="go_content"><div class="go_stage_message">'.do_shortcode(wpautop( $accept_message ) ).'</div>';
						if ( $test_a_active ) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ( $test_a_num > 1 ) {
								for ( $i = 0; $i < $test_a_num; $i++ ) {
									if ( ! empty( $test_a_all_types[ $i ] ) &&
											! empty( $test_a_all_questions[ $i ] ) &&
											! empty( $test_a_all_answers[ $i ] ) &&
											! empty( $test_a_all_keys[ $i ] ) ) {

										echo do_shortcode( "[go_test type='".$test_a_all_types[ $i ]."' question='".$test_a_all_questions[ $i ]."' possible_answers='".$test_a_all_answers[ $i ]."' key='".$test_a_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_a_num."']" );
									}
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} elseif ( ! empty( $test_a_all_types[0] ) &&
									! empty( $test_a_all_questions[0] ) &&
									! empty( $test_a_all_answers[0] ) &&
									! empty( $test_a_all_keys[0] ) ) {

								echo do_shortcode( "[go_test type='".$test_a_all_types[0]."' question='".$test_a_all_questions[0]."' possible_answers='".$test_a_all_answers[0]."' key='".$test_a_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ( $accept_upload ) {
							echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
						}
						echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
						if ( $a_is_locked && ! empty( $a_pass_lock ) ) {
							echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
						} elseif ( $a_url_is_locked === true ) {
							echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
						}
						echo "<button id='go_button' status='3' onclick='task_stage_change( this );'";
						if ( $a_is_locked && empty( $a_pass_lock ) ) {
							echo "admin_lock='true'";
						}
						echo '>'.go_return_options( 'go_third_stage_button' ).'</button> '.
							'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';
						
						go_task_render_chain_pagination( $id, $user_id );
						
						echo '</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
						break;
					
					// Completed
					case 3: 
						echo '<div id="go_content"><div class="go_stage_message">'. do_shortcode( wpautop( $accept_message ) ).'</div><div class="go_stage_message">
						'.do_shortcode( wpautop( $completion_message ) ).'</div>';
						if ( $mastery_active ) {
							if ( $test_c_active ) {
								echo "<p id='go_test_error_msg' style='color: red;'></p>";
								if ( $test_c_num > 1 ) {
									for ( $i = 0; $i < $test_c_num; $i++ ) {
										if ( ! empty( $test_c_all_types[ $i ] ) &&
												! empty( $test_c_all_questions[ $i ] ) &&
												! empty( $test_c_all_answers[ $i ] ) &&
												! empty( $test_c_all_keys[ $i ] ) ) {

											echo do_shortcode( "[go_test type='".$test_c_all_types[ $i ]."' question='".$test_c_all_questions[ $i ]."' possible_answers='".$test_c_all_answers[ $i ]."' key='".$test_c_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_c_num."']" );
										}
									}
									echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								} elseif ( ! empty( $test_c_all_types[0] ) &&
										! empty( $test_c_all_questions[0] ) &&
										! empty( $test_c_all_answers[0] ) &&
										! empty( $test_c_all_keys[0] ) ) {

									echo do_shortcode( "[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								}
							}
							if ( $completion_upload ) {
								echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
							}
							echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
							if ( $c_is_locked && ! empty( $c_pass_lock ) ) {
								echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
							} elseif ( $c_url_is_locked === true ) {
								echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
							}
							echo "<button id='go_button' status='4' onclick='task_stage_change( this );'";
							if ( $c_is_locked && empty( $c_pass_lock ) ) {
								echo "admin_lock='true'";
							}
							echo '>'.go_return_options( 'go_fourth_stage_button' ).'</button> '.
								'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';

							go_task_render_chain_pagination( $id, $user_id );
						} else {
							if ( $test_c_active ) {
								echo "<p id='go_test_error_msg' style='color: red;'></p>";
								if ( $test_c_num > 1 ) {
									for ( $i = 0; $i < $test_c_num; $i++ ) {
										if ( ! empty( $test_c_all_types[ $i ] ) &&
												! empty( $test_c_all_questions[ $i ] ) &&
												! empty( $test_c_all_answers[ $i ] ) &&
												! empty( $test_c_all_keys[ $i ] ) ) {

											echo do_shortcode( "[go_test type='".$test_c_all_types[ $i ]."' question='".$test_c_all_questions[ $i ]."' possible_answers='".$test_c_all_answers[ $i ]."' key='".$test_c_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_c_num."']" );
										}
									}
									echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								} elseif ( ! empty( $test_c_all_types[0] ) &&
										! empty( $test_c_all_questions[0] ) &&
										! empty( $test_c_all_answers[0] ) &&
										! empty( $test_c_all_keys[0] ) ) {

									echo do_shortcode( "[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								}
							}
							if ( $completion_upload ) {
								echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
							}

							echo '<span id="go_button" status="4" style="display:none;"></span>'.
								'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';
							
							go_task_render_chain_pagination( $id, $user_id );
							
							echo "</div>" . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
						}
					break;
					
					// Mastered
					case 4:  
						echo'<div id="go_content"><div class="go_stage_message">'. do_shortcode(wpautop( $accept_message ) ).'</div>'.'<div class="go_stage_message">'.do_shortcode(wpautop( $completion_message ) ).'</div><div class="go_stage_message">'.do_shortcode(wpautop( $mastery_message ) ).'</div>';
						if ( $repeat == 'on' ) {
							if ( $task_count < $repeat_amount || $repeat_amount == 0) { // Checks if the amount of times a user has completed a task is less than the amount of times they are allowed to complete a task. If so, outputs the repeat button to allow the user to repeat the task again. 
								if ( $task_count == 0 ) {
									if ( ! empty( $test_m_active ) ) {
										echo "<p id='go_test_error_msg' style='color: red;'></p>";
										if ( $test_m_num > 1 ) {
											for ( $i = 0; $i < $test_m_num; $i++ ) {
												if ( ! empty( $test_m_all_types[ $i ] ) &&
														! empty( $test_m_all_questions[ $i ] ) &&
														! empty( $test_m_all_answers[ $i ] ) &&
														! empty( $test_m_all_keys[ $i ] ) ) {

													echo do_shortcode( "[go_test type='".$test_m_all_types[ $i ]."' question='".$test_m_all_questions[ $i ]."' possible_answers='".$test_m_all_answers[ $i ]."' key='".$test_m_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_m_num."']" );
												}
											}
											echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
										} elseif ( ! empty( $test_m_all_types[0] ) &&
												! empty( $test_m_all_questions[0] ) &&
												! empty( $test_m_all_answers[0] ) &&
												! empty( $test_m_all_keys[0] ) ) {

											echo do_shortcode( "[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
										}
									}
									if ( $mastery_upload ) {
										echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
									}
									echo '
										<div id="repeat_quest">
											<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
												.do_shortcode(wpautop( $repeat_message ) ).
												"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
									if ( $m_is_locked && ! empty( $m_pass_lock ) ) {
										echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
									} elseif ( $m_url_is_locked === true ) {
										echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
									}
									echo "<button id='go_button' status='4' onclick='go_repeat_hide( this );' repeat='on'";
									if ( $m_is_locked && empty( $m_pass_lock ) ) {
										echo "admin_lock='true'";
									}
									echo '>'.go_return_options( 'go_fourth_stage_button' )." Again". 
												'</button>
												<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
											'</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
											'<div id="go_repeat_unclicked">'.
												'<button id="go_button" status="4" onclick="go_repeat_replace();">'.
													'See ' . get_option( 'go_fifth_stage_name' ).
												'</button> '.
												'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
											'</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
										'</div>';
								} else {
									if ( $repeat_upload ) {
										echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
									}
									echo '
										<div id="repeat_quest">
											<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
												.do_shortcode(wpautop( $repeat_message ) ).
												"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
									if ( $r_is_locked && ! empty( $r_pass_lock ) ) {
										echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
									}
									echo "<button id='go_button' status='4' onclick='go_repeat_hide( this );' repeat='on'";
									if ( $r_is_locked && empty( $r_pass_lock ) ) {
										echo "admin_lock='true'";
									}
									echo '>'.go_return_options( 'go_fourth_stage_button' )." Again". 
												'</button>
												<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
											'</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
											'<div id="go_repeat_unclicked">'.
												'<button id="go_button" status="4" onclick="go_repeat_replace();">'.
													'See ' . get_option( 'go_fifth_stage_name' ).
												'</button> '.
												'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
											'</div>'.
											( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
										'</div>';
								}
							} else {
								echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span>'.
									'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
									( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
							}
						} else {
							if ( ! empty( $test_m_active ) ) {
								echo "<p id='go_test_error_msg' style='color: red;'></p>";
								if ( $test_m_num > 1 ) {
									for ( $i = 0; $i < $test_m_num; $i++ ) {
										if ( ! empty( $test_m_all_types[ $i ] ) &&
												! empty( $test_m_all_questions[ $i ] ) &&
												! empty( $test_m_all_answers[ $i ] ) &&
												! empty( $test_m_all_keys[ $i ] ) ) {

											echo do_shortcode( "[go_test type='".$test_m_all_types[ $i ]."' question='".$test_m_all_questions[ $i ]."' possible_answers='".$test_m_all_answers[ $i ]."' key='".$test_m_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_m_num."']" );
										}
									}
									echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								} elseif ( ! empty( $test_m_all_types[0] ) &&
										! empty( $test_m_all_questions[0] ) &&
										! empty( $test_m_all_answers[0] ) &&
										! empty( $test_m_all_keys[0] ) ) {

									echo do_shortcode( "[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								}
							}
							if ( $mastery_upload ) {
								echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
							}
							echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span>'.
								'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';
						}

						go_task_render_chain_pagination( $id, $user_id );
						
						echo '</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
				}
				if ( get_post_type() == 'tasks' ) {
					comments_template();
					wp_list_comments();
				}
			} else {
				if ( ( ! empty( $bonus_currency_required ) &&
							$current_bonus_currency < $bonus_currency_required ) &&
						( ! empty( $penalty_filter ) && 
							$current_penalty >= $penalty_filter ) ) {
					echo "<span class='go_error_red'>You require more than {$bonus_currency_required} ".go_return_options( 'go_bonus_currency_name' )." and less than {$penalty_filter} ".go_return_options( 'go_penalty_name' )." to view this ".go_return_options( 'go_tasks_name' ).".</span>";
				} else if ( ( ! empty( $bonus_currency_required ) &&
						$current_bonus_currency < $bonus_currency_required ) ) {
					echo "<span class='go_error_red'>You require more than {$bonus_currency_required} ".go_return_options( 'go_bonus_currency_name' )." to view this ".go_return_options( 'go_tasks_name' ).".</span>";
				} else if ( ( ! empty( $penalty_filter ) &&
						$current_penalty >= $penalty_filter ) ) {
					echo "<span class='go_error_red'>You require less than {$penalty_filter} ".go_return_options( 'go_penalty_name' )." to view this ".go_return_options( 'go_tasks_name' ).".</span>";
				}
			}
		} else { // If user can't access quest because they aren't part of the specialty, echo this
			$category_name = implode( ',',$category_names );
			$focus_name = get_option( 'go_focus_name', 'Profession' );
			$task_name = strtolower( get_option( 'go_tasks_name', 'Quest' ) );
			echo "<span class='go_error_red'>This {$task_name} is only available to the \"{$category_name}\" {$focus_name}.</span>";
		}

		if ( $test_e_active && $test_e_returns ) {
			$db_test_encounter_fail_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT e_fail_count 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_encounter_fail_count'] = $db_test_encounter_fail_count;
			
			$db_test_encounter_passed = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT e_passed 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_encounter_passed'] = $db_test_encounter_passed;
		}

		if ( $test_a_active && $test_a_returns ) {
			$db_test_accept_fail_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT a_fail_count 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_accept_fail_count'] = $db_test_accept_fail_count;
			
			$db_test_accept_passed = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT a_passed 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_accept_passed'] = $db_test_accept_passed;
		}

		if ( $test_c_active && $test_c_returns ) {
			$db_test_completion_fail_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT c_fail_count 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_completion_fail_count'] = $db_test_completion_fail_count;
			
			$db_test_completion_passed = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT c_passed 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_completion_passed'] = $db_test_completion_passed;
		}

		if ( ! empty( $test_m_active ) && $test_m_returns ) {
			$db_test_mastery_fail_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT m_fail_count 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_mastery_fail_count'] = $db_test_mastery_fail_count;
			
			$test_mastery_passed = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT m_passed 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			$_SESSION['test_mastery_passed'] = $test_mastery_passed;
		}
?>
	<script language="javascript">
		jQuery( document ).ready( function() {
			jQuery.ajaxSetup({ 
				url: '<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php'
			});
			check_locks();
		}); 
		
		function go_task_abandon() {
			jQuery.ajax({
				type: "POST",
				data: {
					_ajax_nonce: '<?php echo $task_shortcode_nonces['go_task_abandon']; ?>',
					action: "go_task_abandon",
					user_id: <?php echo $user_id; ?>,
					post_id: <?php echo $id; ?>,
					encounter_points: <?php echo floor( $points_array[0] * $update_percent ); ?>,
					encounter_currency: <?php echo floor( $currency_array[0] * $update_percent ); ?>,
					encounter_bonus: <?php echo floor( $bonus_currency_array[0] * $update_percent ); ?>
				}, success: function( res ) {
					if ( -1 !== res ) {
						window.location = "<?php echo home_url(); ?>";
					}
				}
			});
		}

		function check_locks() {
			if ( jQuery( ".go_test_list" ).length != 0 ) {
				jQuery( '.go_test_submit_div' ).show();
			}
			var is_uploaded = jQuery( '#go_upload_form' ).attr( 'uploaded' );
			if ( jQuery( ".go_test_list" ).length != 0 && jQuery( '#go_upload_form' ).length != 0 ) {
				if ( jQuery( '#go_pass_lock' ).length == 0 && jQuery( '#go_button' ).attr( 'admin_lock' ) !== 'true' ) {
					jQuery( '#go_button' ).attr( 'disabled', 'true' );
				}
				jQuery( '.go_test_submit' ).click( function() {
					var test_list = jQuery( '.go_test_list' );
					var current_error_msg = jQuery( '#go_test_error_msg' ).text();
					if ( test_list.length > 1 ) {
						var checked_ans = 0;
						for (var i = 0; i < test_list.length; i++ ) {
							var obj_str = "#" + test_list[ i ].id + " input:checked";
							var chosen_answers = jQuery( obj_str );
							if ( chosen_answers.length >= 1 ) {
								checked_ans++;
							} else {
								if ( current_error_msg != "Please answer all questions!" ) {
									jQuery( '#go_test_error_msg' ).text( "Please answer all questions!" );
								} else {
									flash_error_msg( '#go_test_error_msg' );
								}
							}
						}
						if ( checked_ans >= test_list.length && is_uploaded == 1 ) {
							task_unlock();
						} else {
							if ( checked_ans < test_list.length && is_uploaded != 1 ) {
								var error = "Please answer all questions and upload a file!";
							} else if ( checked_ans < test_list.length ) {
								var error = "Please answer all questions!";
							} else if ( is_uploaded != 1 ) {
								var error = "Please upload a file!";
							}

							if ( typeof error != null ) {
								if ( current_error_msg != error ) {
									jQuery( '#go_test_error_msg' ).text( error );
								} else {
									flash_error_msg( '#go_test_error_msg' );
								}
							}
						}
					} else {
						if ( jQuery( ".go_test_list input:checked" ).length >= 1 && is_uploaded == 1 ) {
							task_unlock();
						} else {
							if ( jQuery( ".go_test_list input:checked" ).length == 0 && is_uploaded != 1 ) {
								var error = "Please answer the question and upload a file!";
							} else if ( jQuery( ".go_test_list input:checked" ).length == 0 ) {
								var error = "Please answer the question!";
							} else if ( is_uploaded != 1 ) {
								var error = "Please upload a file!";
							}

							if ( typeof error != null ) {
								if ( current_error_msg != error ) {
									jQuery( '#go_test_error_msg' ).text( error );
								} else {
									flash_error_msg( '#go_test_error_msg' );
								}
							}
						}
					}
				});
				jQuery( '#go_upload_submit' ).click( function() {
					var test_list = jQuery( ".go_test_list" );
					var current_error_msg = jQuery( '#go_test_error_msg' ).text();
					if ( test_list.length > 1 ) {
						var checked_ans = 0;
						for (var i = 0; i < test_list.length; i++ ) {
							var obj_str = "#" + test_list[ i ].id + " input:checked";
							var chosen_answers = jQuery( obj_str );
							if ( chosen_answers.length >= 1 ) {
								checked_ans++;
							} else {
								if ( current_error_msg != "Please answer all questions!" ) {
									jQuery( '#go_test_error_msg' ).text( "Please answer all questions!" );
								} else {
									flash_error_msg( '#go_test_error_msg' );
								}
							}
						}
						if ( checked_ans >= test_list.length && is_uploaded == 1 ) {
							task_unlock();
						} else {
							if ( checked_ans < test_list.length && is_uploaded != 1 ) {
								var error = "Please answer all questions and upload a file!";
							} else if ( checked_ans < test_list.length ) {
								var error = "Please answer all questions!";
							} else if ( is_uploaded != 1 ) {
								var error = "Please upload a file!";
							}

							if ( typeof error != null ) {
								if ( current_error_msg != error ) {
									jQuery( '#go_test_error_msg' ).text( error );
								} else {
									flash_error_msg( '#go_test_error_msg' );
								}
							}
						}
					} else {
						if ( jQuery( ".go_test_list input:checked" ).length >= 1 && is_uploaded == 1 ) {
							task_unlock();
						} else {
							if ( jQuery( ".go_test_list input:checked" ).length == 0 && is_uploaded != 1 ) {
								var error = "Please answer the question and upload a file!";
							} else if ( jQuery( ".go_test_list input:checked" ).length == 0 ) {
								var error = "Please answer the question!";
							} else if (is_uploaded != 1) {
								var error = "Please upload a file!";
							}

							if ( typeof error != null ) {
								if ( current_error_msg != error ) {
									jQuery( '#go_test_error_msg' ).text( error );
								} else {
									flash_error_msg( '#go_test_error_msg' );
								}
							}
						}
					}
				});
			} else if ( jQuery( ".go_test_list" ).length != 0 ) {
				if ( jQuery( '#go_pass_lock' ).length == 0 && jQuery( '#go_button' ).attr( 'admin_lock' ) !== 'true' ) {
					jQuery( '#go_button' ).attr( 'disabled', 'true' );
				}
				jQuery( '.go_test_submit' ).click( function() {
					var test_list = jQuery( ".go_test_list" );
					if ( test_list.length > 1 ) {
						var checked_ans = 0;
						for ( var i = 0; i < test_list.length; i++ ) {
							var obj_str = "#" + test_list[ i ].id + " input:checked";
							var chosen_answers = jQuery( obj_str );
							if ( chosen_answers.length >= 1 ) {
								checked_ans++;
							}
						}
						if ( checked_ans >= test_list.length ) {
							task_unlock();
						} else {
							if ( jQuery( '#go_test_error_msg' ).text() != "Please answer all questions!" ) {
								jQuery( '#go_test_error_msg' ).text( "Please answer all questions!" );
							} else {
								flash_error_msg( '#go_test_error_msg' );
							}
						}
					} else {
						if ( jQuery( ".go_test_list input:checked" ).length >= 1 ) {
							task_unlock();
						} else {
							if ( jQuery( '#go_test_error_msg' ).text() != "Please answer the question!" ) {
								jQuery( '#go_test_error_msg' ).text( "Please answer the question!" );
							} else {
								flash_error_msg( '#go_test_error_msg' );
							}
						}
					}
				});
			} else if ( jQuery( '#go_upload_form' ).length != 0 && is_uploaded == 0 ) {
				if ( jQuery( '#go_pass_lock' ).length == 0 && jQuery( '#go_button' ).attr( 'admin_lock' ) !== 'true' ) {
					jQuery( '#go_button' ).attr( 'disabled', 'true' );
				}
				jQuery( '#go_upload_submit' ).click( function() {
					if ( jQuery( '#go_pass_lock' ).length > 0 && jQuery( '#go_pass_lock' ).attr( 'value' ).length == 0 ) {
						var error = "Retrieve the password from <?php echo $admin_name; ?>.";
						if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
							jQuery( '#go_stage_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_stage_error_msg' );
						}
					} else {
						task_unlock();
					}
				});
			}
			if ( ( jQuery( '#go_pass_lock' ).length > 0 && jQuery( '#go_pass_lock' ).attr( 'value' ).length == 0 ) && ( jQuery( '#go_upload_form' ).length != 0 && is_uploaded == 0 ) || jQuery( ".go_test_list" ).length != 0 ) {
				if ( jQuery( '#go_stage_error_msg' ).is( ":visible" ) ) {
					var error = "Retrieve the password from <?php echo $admin_name; ?>.";
					if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
						jQuery( '#go_stage_error_msg' ).text( error );
					} else {
						flash_error_msg( '#go_stage_error_msg' );
					}
				}
			}
		}

		function flash_error_msg( elem ) {
			var bg_color = jQuery( elem ).css( 'background-color' );
			if ( typeof bg_color === undefined ) {
				bg_color = "white";
			}
			jQuery( elem ).animate({
				color: bg_color
			}, 200, function() {
				jQuery( elem ).animate({
					color: "red"
				}, 200 );
			});
		}

		function task_unlock() {
			if ( jQuery( ".go_test_list" ).length != 0) {
				var test_list = jQuery( ".go_test_list" );
				var list_size = test_list.length;
				if ( jQuery( '.go_test_list :checked' ).length >= list_size ) {
					
					var test_list = jQuery( ".go_test_list" );
					var list_size = test_list.length;
					var type_array = [];
					
					if ( jQuery( ".go_test_list" ).length > 1) {
					
						var choice_array = [];

						for ( var x = 0; x < list_size; x++ ) {
							
							// figure out the type of each test
							var test_type = test_list[ x ].children[1].children[0].type;
							type_array.push( test_type );

							// get the checked inputs of each test
							var obj_str = "#" + test_list[ x ].id + " :checked";
							var chosen_answers = jQuery( obj_str );

							if ( test_type == 'radio' ) {
								// push indiviudal answers to the choice_array
								if ( chosen_answers[0] != undefined ) {
									choice_array.push( chosen_answers[0].value );
								}
							} else if ( test_type == 'checkbox' ) {
								var t_array = [];
								for ( var i = 0; i < chosen_answers.length; i++ ) {
									t_array.push( chosen_answers[ i ].value );
								}
								var choice_str = t_array.join( "### " );
								choice_array.push( choice_str );
							}   
						}
						var choice = choice_array.join( "#### " );
						var type = type_array.join( "### " );
					} else {
						var chosen_answer = jQuery( '.go_test_list li input:checked' );
						var type = jQuery( '.go_test_list li input' ).first().attr( "type" );
						if ( type == 'radio' ) {
							var choice = chosen_answer[0].value;
						} else if ( type == 'checkbox' ) {
							var choice = [];
							for (var i = 0; i < chosen_answer.length; i++ ) {
								choice.push( chosen_answer[ i ].value );    
							}
							choice = choice.join( "### " );
						}
					}
				} else {
					jQuery( '#go_test_error_msg' ).text( "Answer all questions!" );
				}
			}

			var is_repeating = jQuery( '#go_button' ).attr( 'repeat' );
			if ( is_repeating !== 'on' ) {
				var status = jQuery( '#go_button' ).attr( 'status' ) - 2;
			} else {
				var status = jQuery( '#go_button' ).attr( 'status' ) - 1;
			}
			jQuery.ajax({
				type: "POST",
				data:{
					_ajax_nonce: '<?php echo $task_shortcode_nonces['go_unlock_stage']; ?>',
					action: 'go_unlock_stage',
					task_id: <?php echo $id; ?>,
					user_id: <?php echo $user_id; ?>,
					list_size: list_size,
					chosen_answer: choice,
					type: type,
					status: status,
					points: "<?php echo $points_str; ?>",
				},
				success: function( response ) {
					if ( response === 1 || response === '1' ) {
						jQuery( '.go_test_container' ).hide( 'slow' );
						jQuery( '#test_failure_msg' ).hide( 'slow' );
						jQuery( '.go_test_submit_div' ).hide( 'slow' );
						jQuery( '.go_wrong_answer_marker' ).hide();
						if ( ! jQuery( '#go_button' ).attr( 'admin_lock' ) ) {
							jQuery( '#go_button' ).removeAttr( 'disabled' );
							jQuery( '#go_test_error_msg' ).attr( 'style', 'color:green' );
							jQuery( '#go_test_error_msg' ).text( "Well done, continue!" );
						} else {
							jQuery( '#go_test_error_msg' ).text( "This stage can only be unlocked by <?php echo $admin_name; ?>." );
						}
						
						var test_e_returns = "<?php echo ( ! empty( $test_e_returns ) ? $test_e_returns : ''); ?>";
						var test_a_returns = "<?php echo ( ! empty( $test_a_returns ) ? $test_a_returns : ''); ?>";
						var test_c_returns = "<?php echo ( ! empty( $test_c_returns ) ? $test_c_returns : ''); ?>";
						var test_m_returns = "<?php echo ( ! empty( $test_m_returns ) ? $test_m_returns : ''); ?>";
						if ( ( status == 0 && test_e_returns == 'on' ) ||
								( status == 1 && test_a_returns == 'on' ) ||
								( status == 2 && test_c_returns == 'on' ) || 
								( status == 3 && test_m_returns == 'on' ) ) {
								
							go_test_point_update();
						}
					} else {
						if ( typeof response === 'string' && list_size > 1 ) {
							var failed_questions = response.split( ', ' );
							for ( var x = 0; x < test_list.length; x++ ) {
								var test_id = "#" + test_list[ x ].id;
								if ( jQuery.inArray( test_id, failed_questions ) === -1) {
									if ( jQuery(test_id + " .go_wrong_answer_marker" ).is( ":visible" ) ) {
										jQuery(test_id + " .go_wrong_answer_marker" ).hide();
									}
									if ( ! jQuery(test_id + " .go_correct_answer_marker" ).is( ":visible" ) ) {
										jQuery(test_id + " .go_correct_answer_marker" ).show();
									}
								} else {
									if ( jQuery(test_id + " .go_correct_answer_marker" ).is( ":visible" ) ) {
										jQuery(test_id + " .go_correct_answer_marker" ).hide();
									}
									if ( ! jQuery(test_id + " .go_wrong_answer_marker" ).is( ":visible" ) ) {
										jQuery(test_id + " .go_wrong_answer_marker" ).show();
									}
								}
							}
						}
						var error_msg_val = jQuery( '#go_test_error_msg' ).text();
						if ( error_msg_val != "Wrong answer, try again!" ) {
							jQuery( '#go_test_error_msg' ).text( "Wrong answer, try again!" );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
					}
				}
			});
		}
		
		function go_test_point_update() {
			var is_repeating = jQuery( '#go_button' ).attr( 'repeat' );
			if (is_repeating !== 'on' ) {
				var status = jQuery( '#go_button' ).attr( 'status' ) - 2;
			} else {
				var status = jQuery( '#go_button' ).attr( 'status' ) - 1;
			}
			jQuery.ajax({
				type: "POST",
				data: {
					_ajax_nonce: '<?php echo $task_shortcode_nonces['go_test_point_update']; ?>',
					action: "go_test_point_update",
					points: "<?php echo $points_str; ?>",
					currency: "<?php echo $currency_str; ?>",
					bonus_currency: "<?php echo $bonus_currency_str; ?>",
					status: status,
					page_id: <?php echo $page_id; ?>,
					user_id: <?php echo $user_id; ?>,
					post_id: <?php echo $id; ?>,
					update_percent: <?php echo $date_update_percent; ?>
				},
				success: function( response ) {
					if ( -1 !== response ) {

						// the three following lines are required for the go_notification to work
						var color = jQuery( '#go_admin_bar_progress_bar' ).css( "background-color" );
						jQuery( '#go_content' ).append( response );
						jQuery( '#go_admin_bar_progress_bar' ).css({ "background-color": color });
					}
				}
			});
		}
		
		function go_repeat_hide( target ) {
			// hides the div#repeat_quest to create the repeat cycle.
			// jQuery( "#repeat_quest" ).hide( 'slow' );
			
			setTimeout( function() {
				// passes the jQuery object received in the parameter of the go_repeat_hide function
				// as an argument for the task_stage_change function, after 500 milliseconds (1.5 seconds).
				task_stage_change( target );
			}, 500 );
		}
		
		function go_repeat_replace() {
			jQuery( '#go_repeat_unclicked' ).remove();
			jQuery( '#go_repeat_clicked' ).show( 'slow' );   
		}
		
		function task_stage_change( target ) {
			var undoing = false;
			if ( 'undefined' !== typeof jQuery( target ).attr( 'undo' ) && 'true' === jQuery( target ).attr( 'undo' ).toLowerCase() ) {
				undoing = true;
			}
			if ( ! undoing && jQuery( '#go_button' ).length > 0 ) {
				var perma_locked = jQuery( '#go_button' ).attr( 'admin_lock' );
				if ( perma_locked === 'true' ) {
					jQuery( '#go_stage_error_msg' ).show();
					jQuery( '#go_button' ).removeAttr( 'disabled' );
					jQuery( '#go_stage_error_msg' ).text( "This stage can only be unlocked by <?php echo $admin_name; ?>." );
					return;
				}
			}
			if ( ! undoing && jQuery( '#go_pass_lock' ).length > 0 ) {
				var pass_entered = jQuery( '#go_pass_lock' ).attr( 'value' ).length > 0 ? true : false;
				if ( ! pass_entered ) {
					jQuery( '#go_stage_error_msg' ).show();
					var error = "Retrieve the password from <?php echo $admin_name; ?>.";
					if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
						jQuery( '#go_stage_error_msg' ).text( error );
					} else {
						flash_error_msg( '#go_stage_error_msg' );
					}
					return;
				}
			} else if ( ! undoing && jQuery( '#go_url_key' ).length > 0 ) {
				var the_url = jQuery( '#go_url_key' ).attr( 'value' ).replace(/\s+/, '' );
				if ( the_url.length > 0 ) {
					if ( the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && ! ( the_url.lastIndexOf( 'http://' ) > 0 ) && ! ( the_url.lastIndexOf( 'https://' ) > 0 ) ) {
						var url_entered = true;
					} else {
						jQuery( '#go_stage_error_msg' ).show();
						var error = "Enter a valid URL.";
						if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
							jQuery( '#go_stage_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_stage_error_msg' );
						}
						return;
					}
				} else {
					jQuery( '#go_stage_error_msg' ).show();
					var error = "Enter a valid URL.";
					if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
						jQuery( '#go_stage_error_msg' ).text( error );
					} else {
						flash_error_msg( '#go_stage_error_msg' );
					}
					return;
				}
			}
			
			var color = jQuery( '#go_admin_bar_progress_bar' ).css( "background-color" );

			// redeclare (also called "overloading" ) the variable $task_count to the value of the 'count' var on the database.
			<?php
			$task_count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT count 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			?>
			
			// if the button#go_button exists, set var 'task_status' to the value of the 'status' attribute on the current button#go_button.
			if ( jQuery( '#go_button' ).length != 0 ) {
				var task_status = jQuery( '#go_button' ).attr( 'status' );
			} else {
				var task_status = 5;
			}
			
			// if 'target' (if an argument is sent to task_stage_change, it is stored as a parameter in the 'target' variable)
			// is assigned the value of jQuery( '#go_back_button' ), AND the div#new_content exists...
			if ( jQuery( target ).is( '#go_back_button' ) && jQuery( '#new_content' ).length != 0 ) {
				jQuery( '#new_content p' ).hide( 'slow' );
				jQuery( target ).remove();
			}
			
			// if the button#go_back_button has the attribute of repeat...
			var repeat_attr = false;
			if ( 'on' === jQuery( '#go_button' ).attr( 'repeat' ) ) {
				// set repeat_attr equal to the value of the attribute of button#go_button.
				repeat_attr = true;
			} else if ( 'on' === jQuery( '#go_back_button' ).attr( 'repeat' ) ) {
				// set repeat_attr equal to the value of the attribute of button#go_back_button.
				repeat_attr = true;
			}
			
			// send the following data to the 'wp_ajax_go_task_change_stage' action and use the POST method to do so...
			// when it succeeds update the content of the page: update the admin bar; set the css display attribute to none for
			// div#new_content; then slowly display div#new_content; if the button#go_button 'status' attribute is equal to 2
			// and remove the first child element of div#new_content.
			jQuery.ajax({
				type: "POST",
				data: {
					_ajax_nonce: '<?php echo $task_shortcode_nonces['go_task_change_stage']; ?>',
					action: 'go_task_change_stage',
					post_id: <?php echo $id; ?>, 
					user_id: <?php echo $user_id; ?>,
					admin_name: '<?php echo $admin_name; ?>',
					task_count: <?php echo ( ! empty( $task_count ) ? $task_count : 0 ); ?>,
					status: task_status,
					repeat: repeat_attr,
					undo: undoing,
					pass: ( pass_entered ? jQuery( '#go_pass_lock' ).attr( 'value' ) : '' ),
					url: ( url_entered ? jQuery( '#go_url_key' ).attr( 'value' ) : '' ),
					page_id: <?php echo $page_id; ?>,
					points: <?php echo json_encode( $points_array ); ?>,
					currency: <?php echo json_encode( $currency_array ); ?>,
					bonus_currency: <?php echo json_encode( $bonus_currency_array ); ?>,
					date_update_percent: <?php echo $date_update_percent; ?>,
					next_post_id_in_chain: <?php echo ( ! empty( $next_post_id_in_chain ) ? $next_post_id_in_chain : 0 ); ?>,
					last_in_chain: <?php echo ( ! empty( $last_in_chain ) ? 'true' : 'false' ); ?>,
					number_of_stages: <?php echo $number_of_stages; ?>
				},
				success: function( res ) {
					if ( '0' === res || -1 === res ) {
						jQuery( '#go_stage_error_msg' ).show();
						var error = "Retrieve the password from <?php echo $admin_name; ?>.";
						if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
							jQuery( '#go_stage_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_stage_error_msg' );
						}
					} else {
						jQuery( '#go_content' ).html( res );
						jQuery( '#go_admin_bar_progress_bar' ).css({ "background-color": color });
						jQuery( "#new_content" ).css( "display', 'none" );
						jQuery( "#new_content" ).show( 'slow' );
						if ( jQuery( '#go_button' ).attr( 'status' ) == 2 ) {
							jQuery( '#new_content' ).children().first().remove();
						}
						jQuery( '#go_button' ).ready( function() {
							check_locks();
						});
					}
				}
			});
		}
	</script>
<?php   
			// this is an edit link.
			edit_post_link( 'Edit '.go_return_options( 'go_tasks_name' ), 
				'<br/><p>', '</p>', $id );
		} // Ends else statement
	} else {
		$custom_fields = get_post_custom( $id );
		$encounter_message = ( ! empty( $custom_fields['go_mta_quick_desc'][0] ) ? $custom_fields['go_mta_quick_desc'][0] : '' );
		$accept_message = ( ! empty( $custom_fields['go_mta_accept_message'][0] ) ? $custom_fields['go_mta_accept_message'][0] : '' );
		$complete_message = ( ! empty( $custom_fields['go_mta_complete_message'][0] ) ? $custom_fields['go_mta_complete_message'][0] : '' );
		$mastery_active = ( ! empty( $custom_fields['go_mta_three_stage_switch'][0] ) ? ! $custom_fields['go_mta_three_stage_switch'][0] : true );
		if ( $mastery_active ) {
			$mastery_privacy = ( ! empty( $custom_fields['go_mta_mastery_privacy'][0] ) ? ! $custom_fields['go_mta_mastery_privacy'][0] : true );
			if ( $mastery_privacy ) {
				$mastery_message = ( ! empty( $custom_fields['go_mta_mastery_message'][0] ) ? $custom_fields['go_mta_mastery_message'][0] : '' );
				$repeat_active = ( ! empty( $custom_fields['go_mta_five_stage_switch'][0] ) ? $custom_fields['go_mta_five_stage_switch'][0] : false );
				if ( $repeat_active && $mastery_privacy ) {
					$repeat_privacy = ( ! empty( $custom_fields['go_mta_repeat_privacy'][0] ) ? ! $custom_fields['go_mta_repeat_privacy'][0] : true );
					if ( $repeat_privacy ) {
						$repeat_message = ( ! empty( $custom_fields['go_mta_repeat_message'][0] ) ? $custom_fields['go_mta_repeat_message'][0] : '' );
					} else {
						$repeat_message = "This stage has been hidden by the administrator.";
					}
				}
			} else {
				$mastery_message = "This stage has been hidden by the administrator.";
			}
		}
		echo "<div id='go_content'>";
		if ( ! empty( $encounter_message ) ) {
			echo "<div id='go_stage_encounter_message' class='go_stage_message'>".do_shortcode(wpautop( $encounter_message ) )."</div>";
		}
		if ( ! empty( $accept_message ) ) {
			echo "<div id='go_stage_accept_message' class='go_stage_message'>".do_shortcode(wpautop( $accept_message ) )."</div>";
		}
		if ( ! empty( $complete_message ) ) {
			echo "<div id='go_stage_complete_message' class='go_stage_message'>".do_shortcode(wpautop( $complete_message ) )."</div>";
		}
		if ( ! empty( $mastery_message ) ) {
			echo "<div id='go_stage_mastery_message' class='go_stage_message'>".do_shortcode(wpautop( $mastery_message ) )."</div>";
			if ( ! empty( $repeat_message ) ) {
				echo "<div id='go_stage_repeat_message' class='go_stage_message'>".do_shortcode(wpautop( $repeat_message ) )."</div>";
			}
		}
	}
}

add_shortcode( 'go_task','go_task_shortcode' );

/**
 * Retrieves and formulates test meta data from a specific task and stage.
 *
 * Note that this function does not check that a stage has the test option enabled. It is expected
 * that such checks will be made prior to calling the function. However, empty test meta data will
 * return null.
 *
 * The test meta data arrays are separately ordered so that index 0 of the question array corresponds
 * to index 0 of all the other arrays, index 1 to all the other index 1 elements, and so on.
 *
 * @since 3.0.0
 *
 * @param string $stage   The stage. e.g. "encounter", "accept", "completion", "mastery" ("repeat"
 *                        would return null, since there is no test option in the fifth stage).
 * @param int    $task_id Optional. The task ID.
 * @return array|null An array of data pertaining to the stage's test(s). Null when the stage's meta
 *                    data is empty.
 *
 * e.g. array(
 *           $test_returns,            // loot meta data
 *           $test_num,                // the number of questions
 *           array(
 *                $test_all_questions, // an array of questions
 *                $test_all_types,     // an array of question types (Multiple Choice or
 *                                     // Multiple Select)
 *                $test_all_answers,   // an array of potential answers
 *                $test_all_keys,      // an array of answer keys
 *           ),
 *      )
 */
function go_task_get_test_meta( $stage, $task_id ) {
	if ( empty( $stage ) ) {
		return null;
	}

	if ( empty( $task_id ) ) {
		$task_id = get_the_id();
	} elseif ( 'int' !== gettype( $task_id ) ) {
		$task_id = (int) $task_id;
	}

	$test_returns = get_post_meta( $task_id, "go_mta_test_{$stage}_lock_loot", true );
	$test_array = get_post_meta( $task_id, "go_mta_test_{$stage}_lock_fields", true );

	if ( ! empty( $test_array ) ) {
		$test_num = $test_array[3];
		$test_all_questions = array();
		foreach ( $test_array[0] as $question ) {
			$esc_question = htmlspecialchars( $question, ENT_QUOTES );
			if ( preg_match( "/[\\\[\]]/", $question ) ) {
				$str = preg_replace( array( "/\[/", "/\]/", "/\\\/" ), array( '&#91;', '&#93;', '\\\\\\\\\\\\' ), $esc_question );
				$test_all_questions[] = $str;
			} else {
				$test_all_questions[] = $esc_question;
			}
		}
		$test_all_types = $test_array[2];
		$test_all_inputs = $test_array[1];
		$test_all_input_num = $test_array[4];
		$test_all_answers = array();
		$test_all_keys = array();
		for ( $i = 0; $i < count( $test_all_inputs ); $i++ ) {
			if ( ! empty( $test_all_inputs[ $i ][0] ) ) {
				$answer_temp = implode( "###", $test_all_inputs[ $i ][0] );
				$esc_answer = htmlspecialchars( $answer_temp, ENT_QUOTES );
				if ( preg_match( "/[\\\[\]]/", $answer_temp ) ) {
					$str = preg_replace( array( "/\[/", "/\]/", "/\\\/" ), array( '&#91;', '&#93;', '\\\\\\\\\\\\' ), $esc_answer );
					$test_all_answers[] = $str;
				} else {
					$test_all_answers[] = $esc_answer;
				}
			}
			if ( ! empty( $test_all_inputs[ $i ][1] ) ) {
				$key_temp = implode( "###", $test_all_inputs[ $i ][1] );
				$esc_key = htmlspecialchars( $key_temp, ENT_QUOTES );
				if (preg_match( "/[\\\[\]]/", $key_temp) ) {
					$str = preg_replace( array( "/\[/", "/\]/", "/\\\/" ), array( '&#91;', '&#93;', '\\\\\\\\\\\\' ), $esc_key );
					$test_all_keys[] = $str;
				} else {
					$test_all_keys[] = $esc_key;
				}
			}
		}

		return array( $test_returns, $test_num, array( $test_all_questions, $test_all_types, $test_all_answers, $test_all_keys ) );
	} else {
		return null;
	}
}

function go_test_point_update() {
	$post_id = ( ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0 );
	$user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 );

	check_ajax_referer( 'go_test_point_update_' . $post_id . '_' . $user_id );

	$status             = ( ! empty( $_POST['status'] )         ? (int) $_POST['status'] : 0 );
	$page_id            = ( ! empty( $_POST['page_id'] )        ? (int) $_POST['page_id'] : 0 );
	$points_str         = ( ! empty( $_POST['points'] )         ? sanitize_text_field( $_POST['points'] ) : '' );
	$currency_str       = ( ! empty( $_POST['currency'] )       ? sanitize_text_field( $_POST['currency'] ) : '' );
	$bonus_currency_str = ( ! empty( $_POST['bonus_currency'] ) ? sanitize_text_field( $_POST['bonus_currency'] ) : '' );
	$update_percent     = ( ! empty( $_POST['update_percent'] ) ? (double) $_POST['update_percent'] : 0.0 );

	$points_array         = explode( ' ', $points_str );
	$point_base           = (int) $points_array[ $status ];
	$currency_array       = explode( ' ', $currency_str );
	$currency_base        = (int) $currency_array[ $status ];
	$bonus_currency_array = explode( ' ', $bonus_currency_str );
	$bonus_currency_base  = (int) $bonus_currency_array[ $status ];
	$e_fail_count         = ( ! empty( $_SESSION['test_encounter_fail_count'] )  ? (int) $_SESSION['test_encounter_fail_count'] : 0 );
	$a_fail_count         = ( ! empty( $_SESSION['test_accept_fail_count'] )     ? (int) $_SESSION['test_accept_fail_count'] : 0 );
	$c_fail_count         = ( ! empty( $_SESSION['test_completion_fail_count'] ) ? (int) $_SESSION['test_completion_fail_count'] : 0 );
	$m_fail_count         = ( ! empty( $_SESSION['test_mastery_fail_count'] )    ? (int) $_SESSION['test_mastery_fail_count'] : 0 );
	$status++;

	$custom_fields = get_post_custom( $post_id );
	switch ( $status ) {
		case ( 1 ):
			$fail_count = $e_fail_count;
			$custom_mod = $custom_fields['go_mta_test_encounter_lock_loot_mod'][0];
			$passed = 1;
			if ( ! empty( $_SESSION['test_encounter_passed'] ) ) {
				$passed = $_SESSION['test_encounter_passed'];
			}
			$_SESSION['test_encounter_passed'] = 1;
			break;
		case ( 2 ):
			$fail_count = $a_fail_count;
			$custom_mod = $custom_fields['go_mta_test_accept_lock_loot_mod'][0];
			$passed = 1;
			if ( ! empty( $_SESSION['test_accept_passed'] ) ) {
				$passed = $_SESSION['test_accept_passed'];
			}
			$_SESSION['test_accept_passed'] = 1;
			break;
		case ( 3 ):
			$fail_count = $c_fail_count;
			$custom_mod = $custom_fields['go_mta_test_completion_lock_loot_mod'][0];
			$passed = 1;
			if ( ! empty( $_SESSION['test_completion_passed'] ) ) {
				$passed = $_SESSION['test_completion_passed'];
			}
			$_SESSION['test_completion_passed'] = 1;
			break;
		case ( 4 ):
			$fail_count = $m_fail_count;
			$custom_mod = $custom_fields['go_mta_test_mastery_lock_loot_mod'][0];
			$passed = 1;
			if ( ! empty( $_SESSION['test_mastery_passed'] ) ) {
				$passed = $_SESSION['test_mastery_passed'];
			}
			$_SESSION['test_mastery_passed'] = 1;
			break;
	}

	if ( empty( $fail_count ) ) {
		$fail_count = 0;
	}

	$e_passed = ( ! empty( $_SESSION['test_encounter_passed'] )  ? (int) $_SESSION['test_encounter_passed'] : 0 );
	$a_passed = ( ! empty( $_SESSION['test_accept_passed'] )     ? (int) $_SESSION['test_accept_passed'] : 0 );
	$c_passed = ( ! empty( $_SESSION['test_completion_passed'] ) ? (int) $_SESSION['test_completion_passed'] : 0 );
	$m_passed = ( ! empty( $_SESSION['test_mastery_passed'] )    ? (int) $_SESSION['test_mastery_passed'] : 0 );
	
	$percent = $custom_mod / 100;
	if ( ! empty( $point_base ) ) {
		$test_fail_max_temp = $point_base / ( $point_base * $percent ); 
	} elseif ( ! empty( $currency_base ) ) {
		$test_fail_max_temp = $currency_base / ( $currency_base * $percent );
	} elseif ( ! empty( $bonus_currency_base ) ) {
		$test_fail_max_temp = $bonus_currency_base / ( $bonus_currency_base * $percent );
	}
	$test_fail_max = ceil( $test_fail_max_temp );
	if ( $fail_count < $test_fail_max ) {
		$p_num = $point_base - ( ( $point_base * $percent) * $fail_count );
		$target_points = floor( $p_num );
		$c_num = $currency_base - ( ( $currency_base * $percent) * $fail_count );
		$target_currency = floor( $c_num );
		$b_num = $bonus_currency_base - ( ( $bonus_currency_base * $percent) * $fail_count );
		$target_bonus_currency = floor( $b_num );
	} else {
		$target_points = 0;
	}
	
	if ( $passed === 0 || $passed === '0' ) {
		go_add_post(
			$user_id, $post_id, $status,
			floor( $update_percent * $target_points ),
			floor( $update_percent * $target_currency ),
			floor( $update_percent * $target_bonus_currency ),
			null, $page_id, false, null,
			$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
			$e_passed, $a_passed, $c_passed, $m_passed
		);
	}
	die();
}

function go_inc_test_fail_count( $s_name, $test_fail_max = null ) {
	if ( ! is_null( $test_fail_max ) ) {
		if ( isset( $_SESSION[ $s_name ] ) ) {
			$s_var = $_SESSION[ $s_name ];
			if ( $s_var < $test_fail_max ) {
				$_SESSION[ $s_name ]++;
			} elseif ( $s_var > $test_fail_max ) {
				unset( $_SESSION[ $s_name ] );
			}
		}
	}
}

function go_unlock_stage() {
	$task_id = ( ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0 );
	$user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 );

	check_ajax_referer( 'go_unlock_stage_' . $task_id . '_' . $user_id );

	$status     = ( ! empty( $_POST['status'] )    ? (int) $_POST['status'] : 0 );
	$test_size  = ( ! empty( $_POST['list_size'] ) ? (int) $_POST['list_size'] : 0 );
	$points_str = ( ! empty( $_POST['points'] )    ? sanitize_text_field( $_POST['points'] ) : '' );
	
	$points_array = explode( ' ', $points_str );
	$point_base   = (int) $points_array[ $status ];

	$choice = ( ! empty( $_POST['chosen_answer'] ) ? stripslashes( $_POST['chosen_answer'] ) : '' );
	$type   = ( ! empty( $_POST['type'] )          ? sanitize_key( $_POST['type'] ) : '' );
	if ( $test_size > 1 ) {
		$all_test_choices = explode( "#### ", $choice );
		$type_array = explode( "### ", $type );
	} else {
		if ( $type == 'checkbox' ) {
			$choice_array = explode( "### ", $choice );
		}
	}
	
	$custom_fields = get_post_custom( $task_id );

	switch ( $status ) {
		case ( 0 ):
			$test_stage = 'go_mta_test_encounter_lock_fields';
			$custom_mod = $custom_fields['go_mta_test_encounter_lock_loot_mod'][0];
			$test_fail_name = 'test_encounter_fail_count';
			break;
		case ( 1 ):
			$test_stage = 'go_mta_test_accept_lock_fields';
			$custom_mod = $custom_fields['go_mta_test_accept_lock_loot_mod'][0];
			$test_fail_name = 'test_accept_fail_count';
			break;
		case ( 2 ):
			$test_stage = 'go_mta_test_completion_lock_fields';
			$custom_mod = $custom_fields['go_mta_test_completion_lock_loot_mod'][0];
			$test_fail_name = 'test_completion_fail_count';
			break;
		case ( 3 ):
			$test_stage = 'go_mta_test_mastery_lock_fields';
			$custom_mod = $custom_fields['go_mta_test_mastery_lock_loot_mod'][0];
			$test_fail_name = 'test_mastery_fail_count';
			break;
		default:
			$custom_mod = 20;
	}

	if ( $point_base !== 0 ) {
		$percent = $custom_mod / 100;
		$test_fail_max_temp = $point_base / ( $point_base * $percent );
		$test_fail_max = ceil( $test_fail_max_temp );
	}
	
	$user_id = get_current_user_id();

	$test_c_array = $custom_fields[ $test_stage ][0];
	$test_c_uns = unserialize( $test_c_array );
	$keys = $test_c_uns[1];
	$all_keys_array = array();
	for ( $i = 0; $i < count( $keys ); $i++ ) {
		$all_keys_array[] = implode( "### ", $keys[ $i ][1] );
	}
	$key = $all_keys_array[0];
	
	if ( $type == 'checkbox' && ! ( $list_size > 1 ) ) {
		$key_str = preg_replace( '/\s*\#\#\#\s*/', '### ', $key );
		$key_array = explode( '### ', $key_str );
	}

	$fail_question_ids = array();
	if ( $test_size > 1 ) {
		$total_matches = 0;
		for ( $i = 0; $i < $test_size; $i++ ) {
			if ( ! empty( $type_array[ $i ] ) && 'radio' == $type_array[ $i ] ) {
				if ( strtolower( $all_keys_array[ $i ] ) == strtolower( $all_test_choices[ $i ] ) ) {
					$total_matches++;
				} else {
					if ( ! in_array( "#go_test_{$i}", $fail_question_ids ) ) {
						array_push( $fail_question_ids, "#go_test_{$i}" );
					}
				}                   
			} else {
				$k_array = explode( "### ", $all_keys_array[ $i ] );
				$c_array = explode( "### ", $all_test_choices[ $i ] );
				$match_count = 0;
				for ( $x = 0; $x < count( $c_array ); $x++ ) {
					if ( strtolower( $c_array[ $x ] ) == strtolower( $k_array[ $x ] ) ) {
						$match_count++;
					}
				}

				if ( $match_count == count( $k_array ) && $match_count == count( $c_array ) ) {
					$total_matches++;
				} else {
					if ( ! in_array( "#go_test_{$i}", $fail_question_ids ) ) {
						array_push( $fail_question_ids, "#go_test_{$i}" );
					}
				}
			}
		}

		if ( $total_matches == $test_size ) {
			echo 1;
			die();
		} else {
			go_inc_test_fail_count( $test_fail_name, $test_fail_max );
			if ( ! empty( $fail_question_ids ) ) {
				$fail_id_str = implode( ', ', $fail_question_ids );
				echo $fail_id_str;
			} else {
				echo 0;
			}
			die();
		}
	} else {

		if ( $type == 'radio' ) {
			if ( strtolower( $choice ) == strtolower( $key ) ) {
				echo 1;
				die();
			} else {
				go_inc_test_fail_count( $test_fail_name, $test_fail_max );
				echo 0;
				die();
			}
		} elseif ( $type == 'checkbox' ) {
			$key_match = 0;
			for ( $i = 0; $i < count( $key_array ); $i++ ) {
				for ( $x = 0; $x < count( $choice_array );  $x++ ) {
					if ( strtolower( $choice_array[ $x ] ) == strtolower( $key_array[ $i ] ) ) {
						$key_match++;
						break;
					}
				}
			}
			if ( $key_match == count( $key_array ) && $key_match == count( $choice_array ) ) {
				echo 1;
				die();
			} else {
				go_inc_test_fail_count( $test_fail_name, $test_fail_max );
				echo 0;
				die();
			}
		}
	}
	
	die();
}

function go_task_change_stage() {
	global $wpdb;

	$post_id = ( ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0 ); // Post id posted from ajax function
	$user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function

	check_ajax_referer( 'go_task_change_stage_' . $post_id . '_' . $user_id );

	$status                = ( ! empty( $_POST['status'] )                ? (int) $_POST['status'] : 0 ); // Task's status posted from ajax function
	$page_id               = ( ! empty( $_POST['page_id'] )               ? (int) $_POST['page_id'] : 0 ); // Page id posted from ajax function
	$admin_name            = ( ! empty( $_POST['admin_name'] )            ? (string) $_POST['admin_name'] : '' );
	$undo                  = ( ! empty( $_POST['undo'] )                  ? go_is_true_str( $_POST['undo'] ) : false ); // Boolean which determines if the button clicked is an undo button or not (True or False)
	$pass                  = ( ! empty( $_POST['pass'] )                  ? (string) $_POST['pass'] : '' ); // Contains the user-entered admin password
	$url                   = ( ! empty( $_POST['url'] )                   ? (string) $_POST['url'] : '' ); // Contains user-entered url
	$repeat_button         = ( ! empty( $_POST['repeat'] )                ? go_is_true_str( $_POST['repeat'] ) : false ); // Boolean which determines if the task is repeatable or not (True or False)
	$points_array          = ( ! empty( $_POST['points'] )                ? (array) $_POST['points'] : array() ); // Serialized array of points rewarded for each stage
	$currency_array        = ( ! empty( $_POST['currency'] )              ? (array) $_POST['currency'] : array() ); // Serialized array of currency rewarded for each stage
	$bonus_currency_array  = ( ! empty( $_POST['bonus_currency'] )        ? (array) $_POST['bonus_currency'] : array() ); // Serialized array of bonus currency awarded for each stage
	$date_update_percent   = ( ! empty( $_POST['date_update_percent'] )   ? (double) $_POST['date_update_percent'] : 0.0 ); // Float which is used to modify values saved to database
	$next_post_id_in_chain = ( ! empty( $_POST['next_post_id_in_chain'] ) ? (int) $_POST['next_post_id_in_chain'] : 0 ); // Integer which is used to display next task in a quest chain
	$last_in_chain         = ( ! empty( $_POST['last_in_chain'] )         ? go_is_true_str( $_POST['last_in_chain'] ) : false ); // Boolean which determines if the current quest is last in chain
	$number_of_stages      = ( ! empty( $_POST['number_of_stages'] )      ? (int) $_POST['number_of_stages'] : 0 ); // Integer with number of stages in the task

	$unix_now = current_time( 'timestamp' ); // Current unix timestamp
	$go_table_name = "{$wpdb->prefix}go";
	$task_count = go_task_get_repeat_count( $post_id, $user_id );

	$custom_fields = get_post_custom( $post_id ); // Just gathering some data about this task with its post id
	$mastery_active = ( ! empty( $custom_fields['go_mta_three_stage_switch'][0] ) ? ! $custom_fields['go_mta_three_stage_switch'][0] : true ); // whether or not the mastery stage is active
	$repeat = ( ! empty( $custom_fields['go_mta_five_stage_switch'][0] ) ? $custom_fields['go_mta_five_stage_switch'][0] : '' ); // Whether or not you can repeat the task

	$e_admin_lock = get_post_meta( $post_id, 'go_mta_encounter_admin_lock', true );
	if ( ! empty( $e_admin_lock ) ) {
		$e_is_locked = ( ! empty( $e_admin_lock[0] ) ? true : false );
		if ( $e_is_locked ) {
			$e_pass_lock = ( ! empty( $e_admin_lock[1] ) ? $e_admin_lock[1] : '' );
		}
	}

	$a_admin_lock = get_post_meta( $post_id, 'go_mta_accept_admin_lock', true );
	if ( ! empty( $a_admin_lock ) ) {
		$a_is_locked = ( ! empty( $a_admin_lock[0] ) ? true : false );
		if ( $a_is_locked ) {
			$a_pass_lock = ( ! empty( $a_admin_lock[1] ) ? $a_admin_lock[1] : '' );
		}
	}

	$c_admin_lock = get_post_meta( $post_id, 'go_mta_completion_admin_lock', true );
	if ( ! empty( $c_admin_lock ) ) {
		$c_is_locked = ( ! empty( $c_admin_lock[0] ) ? true : false );
		if ( $c_is_locked ) {
			$c_pass_lock = ( ! empty( $c_admin_lock[1] ) ? $c_admin_lock[1] : '' );
		}
	}

	$m_admin_lock = get_post_meta( $post_id, 'go_mta_mastery_admin_lock', true );
	if ( ! empty( $m_admin_lock ) ) {
		$m_is_locked = ( ! empty( $m_admin_lock[0] ) ? true : false );
		if ( $m_is_locked ) {
			$m_pass_lock = ( ! empty( $m_admin_lock[1] ) ? $m_admin_lock[1] : '' );
		}
	}

	$r_admin_lock = get_post_meta( $post_id, 'go_mta_repeat_admin_lock', true );
	if ( ! empty( $r_admin_lock ) ) {
		$r_is_locked = ( ! empty( $r_admin_lock[0] ) ? true : false );
		if ( $r_is_locked ) {
			$r_pass_lock = ( ! empty( $r_admin_lock[1] ) ? $r_admin_lock[1] : '' );
		}
	}

	$e_url_is_locked = ( ! empty( $custom_fields['go_mta_encounter_url_key'][0] ) ? true : false );
	$a_url_is_locked = ( ! empty( $custom_fields['go_mta_accept_url_key'][0] ) ? true : false );
	$c_url_is_locked = ( ! empty( $custom_fields['go_mta_completion_url_key'][0] ) ? true : false );
	$m_url_is_locked = ( ! empty( $custom_fields['go_mta_mastery_url_key'][0] ) ? true : false );

	if ( ! empty( $pass ) || '0' === $pass ) {

		$pass_lock = '';
		if ( 4 === $status && $repeat_button && 'on' === $repeat ) {
			if ( $task_count > 0 && ! empty( $r_pass_lock ) ) {
				$pass_lock = $r_pass_lock;
			} elseif ( 0 === $task_count && ! empty( $m_pass_lock ) ) {
				$pass_lock = $m_pass_lock;
			}
		} elseif ( 1 === $status - 1 && ! empty( $e_pass_lock ) ) {
			$pass_lock = $e_pass_lock;
		} elseif ( 2 === $status - 1 && ! empty( $a_pass_lock ) ) {
			$pass_lock = $a_pass_lock;
		} elseif ( 3 === $status - 1 && ! empty( $c_pass_lock ) ) {
			$pass_lock = $c_pass_lock;
		}

		if ( ! empty( $pass_lock ) && $pass !== $pass_lock ) {
			echo 0;
			die();
		}
	}

	$test_e_active = ( ! empty( $custom_fields['go_mta_test_encounter_lock'][0] ) ? $custom_fields['go_mta_test_encounter_lock'][0] : false );
	$test_a_active = ( ! empty( $custom_fields['go_mta_test_accept_lock'][0] ) ? $custom_fields['go_mta_test_accept_lock'][0] : false );
	$test_c_active = ( ! empty( $custom_fields['go_mta_test_completion_lock'][0] ) ? $custom_fields['go_mta_test_completion_lock'][0] : false );

	if ( $test_e_active ) {
		$test_e_array = go_task_get_test_meta( 'encounter', $post_id );
		$test_e_returns = $test_e_array[0];
		$test_e_num = $test_e_array[1];
		$test_e_all_questions = $test_e_array[2][0];
		$test_e_all_types = $test_e_array[2][1];
		$test_e_all_answers = $test_e_array[2][2];
		$test_e_all_keys = $test_e_array[2][3];
	}
	$encounter_upload = ( ! empty( $custom_fields['go_mta_encounter_upload'][0] ) ? $custom_fields['go_mta_encounter_upload'][0] : false );

	if ( $test_a_active ) {
		$test_a_array = go_task_get_test_meta( 'accept', $post_id );
		$test_a_returns = $test_a_array[0];
		$test_a_num = $test_a_array[1];
		$test_a_all_questions = $test_a_array[2][0];
		$test_a_all_types = $test_a_array[2][1];
		$test_a_all_answers = $test_a_array[2][2];
		$test_a_all_keys = $test_a_array[2][3];
	}
	$accept_upload = ( ! empty( $custom_fields['go_mta_accept_upload'][0] ) ? $custom_fields['go_mta_accept_upload'][0] : false );

	if ( $test_c_active ) {
		$test_c_array = go_task_get_test_meta( 'completion', $post_id );
		$test_c_returns = $test_c_array[0];
		$test_c_num = $test_c_array[1];
		$test_c_all_questions = $test_c_array[2][0];
		$test_c_all_types = $test_c_array[2][1];
		$test_c_all_answers = $test_c_array[2][2];
		$test_c_all_keys = $test_c_array[2][3];
	}
	$completion_message = ( ! empty( $custom_fields['go_mta_complete_message'][0] ) ? $custom_fields['go_mta_complete_message'][0] : '' );
	$completion_upload = ( ! empty( $custom_fields['go_mta_completion_upload'][0] ) ? $custom_fields['go_mta_completion_upload'][0] : false );
	if ( $mastery_active ) {
		$test_m_active = ( ! empty( $custom_fields['go_mta_test_mastery_lock'][0] ) ? $custom_fields['go_mta_test_mastery_lock'][0] : false );
		$bonus_loot = ( ! empty( $custom_fields['go_mta_mastery_bonus_loot'][0] ) ? unserialize( $custom_fields['go_mta_mastery_bonus_loot'][0] ) : null );

		if ( $test_m_active ) {
			$test_m_array = go_task_get_test_meta( 'mastery', $post_id );
			$test_m_returns = $test_m_array[0];
			$test_m_num = $test_m_array[1];
			$test_m_all_questions = $test_m_array[2][0];
			$test_m_all_types = $test_m_array[2][1];
			$test_m_all_answers = $test_m_array[2][2];
			$test_m_all_keys = $test_m_array[2][3];
		}
		$mastery_message = ( ! empty( $custom_fields['go_mta_mastery_message'][0] ) ? $custom_fields['go_mta_mastery_message'][0] : '' );
		$mastery_upload = ( ! empty( $custom_fields['go_mta_mastery_upload'][0] ) ? $custom_fields['go_mta_mastery_upload'][0] : false );

		if ( $repeat == 'on' ) {
			$repeat_amount = ( ! empty( $custom_fields['go_mta_repeat_amount'][0] ) ? $custom_fields['go_mta_repeat_amount'][0] : 0 );
			$repeat_message = ( ! empty( $custom_fields['go_mta_repeat_message'][0] ) ? $custom_fields['go_mta_repeat_message'][0] : '' );
			$repeat_upload = ( ! empty( $custom_fields['go_mta_repeat_upload'][0] ) ? $custom_fields['go_mta_repeat_upload'][0] : false );
		}
	}

	$description = ( ! empty( $custom_fields['go_mta_quick_desc'][0] ) ? $custom_fields['go_mta_quick_desc'][0] : '' );

	// Array of badge switch and badges associated with a stage
	// E.g. array( true, array( 263, 276 ) ) means that stage has badges (true) and the badge IDs are 263 and 276
	$stage_badges = array(
		( ! empty( $custom_fields['go_mta_stage_one_badge'][0] )   ? unserialize( $custom_fields['go_mta_stage_one_badge'][0] ) : null ),
		( ! empty( $custom_fields['go_mta_stage_two_badge'][0] )   ? unserialize( $custom_fields['go_mta_stage_two_badge'][0] ) : null ),
		( ! empty( $custom_fields['go_mta_stage_three_badge'][0] ) ? unserialize( $custom_fields['go_mta_stage_three_badge'][0] ) : null ),
		( ! empty( $custom_fields['go_mta_stage_four_badge'][0] )  ? unserialize( $custom_fields['go_mta_stage_four_badge'][0] ) : null ),
		( ! empty( $custom_fields['go_mta_stage_five_badge'][0] )  ? unserialize( $custom_fields['go_mta_stage_five_badge'][0] ) : null ),
	);

	// Stage Stuff
	$content_post = get_post( $post_id );
	$task_content = $content_post->post_content;
	if ( $task_content == '' ) {
		$accept_message = ( ! empty( $custom_fields['go_mta_accept_message'][0] ) ? $custom_fields['go_mta_accept_message'][0] : '' );
	} else {
		$accept_message = $content_post->post_content;
	}

	// Tests failed.
	$e_fail_count = ( ! empty( $_SESSION['test_encounter_fail_count'] )  ? (int) $_SESSION['test_encounter_fail_count'] : 0 );
	$a_fail_count = ( ! empty( $_SESSION['test_accept_fail_count'] )     ? (int) $_SESSION['test_accept_fail_count'] : 0 );
	$c_fail_count = ( ! empty( $_SESSION['test_completion_fail_count'] ) ? (int) $_SESSION['test_completion_fail_count'] : 0 );
	$m_fail_count = ( ! empty( $_SESSION['test_mastery_fail_count'] )    ? (int) $_SESSION['test_mastery_fail_count'] : 0 );

	// Tests passed.
	$e_passed = ( ! empty( $_SESSION['test_encounter_passed'] )  ? (int) $_SESSION['test_encounter_passed'] : 0 );
	$a_passed = ( ! empty( $_SESSION['test_accept_passed'] )     ? (int) $_SESSION['test_accept_passed'] : 0 );
	$c_passed = ( ! empty( $_SESSION['test_completion_passed'] ) ? (int) $_SESSION['test_completion_passed'] : 0 );
	$m_passed = ( ! empty( $_SESSION['test_mastery_passed'] )    ? (int) $_SESSION['test_mastery_passed'] : 0 );

	$db_status = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status 
			FROM {$go_table_name} 
			WHERE uid = %d AND post_id = %d",
			$user_id,
			$post_id
		)
	);

	$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : null ); //determine which future date modifier is on, if any
	$date_picker = ( ! empty( $custom_fields['go_mta_date_picker'][0] ) && unserialize( $custom_fields['go_mta_date_picker'][0] ) ? array_filter( unserialize( $custom_fields['go_mta_date_picker'][0] ) ) : false );

	if ( ! empty( $date_picker) && ( ! empty( $future_switches['calendar'] ) && $future_switches['calendar'] == 'on' ) ) {
		$dates = $date_picker['date'];
		$times = $date_picker['time'];
		$percentages = $date_picker['percent'];

		// Setup empty array to house which dates are closest, in unix timestamp
		$past_dates = array();
		foreach ( $dates as $key => $date ) {

			// gets the UNIX timestamp for the date at the specified time of day
			$timestamp = strtotime( "{$date} {$times[ $key ]}" );
			if ( $unix_now >= $timestamp ) {
				$past_dates[] = abs( $unix_now - $timestamp );
			}
		}

		if ( ! empty( $past_dates ) ) {

			// sorts dates from most recent to oldest
			asort( $past_dates );
			$date_update_percent = (float) ( ( 100 - $percentages[ key( $past_dates ) ] ) / 100);
		} else {
			$date_update_percent = 1;
		}
	} else {
		$date_update_percent = 1;
	}

	$future_modifier = get_post_meta( $post_id, 'go_mta_time_modifier' );
	$future_timer = false;
	if ( ! empty( $future_modifier ) &&
		( ! empty( $future_switches['future'] ) && $future_switches['future'] == 'on' ) &&
		! (
			$future_modifier['days'] == 0 && $future_modifier['hours'] == 0 &&
			$future_modifier['minutes'] == 0 && $future_modifier['seconds'] == 0
		)
	) {
		$user_timers = get_user_meta( $user_id, 'go_timers' );
		$accept_timestamp = 0;
		if ( ! empty( $user_timers[0][ $post_id ] ) ) {
			$accept_timestamp = $user_timers[0][ $post_id ];
		} else {
			$accept_timestamp_raw = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT timestamp 
					FROM {$wpdb->prefix}go 
					WHERE uid = %d AND post_id = %d",
					$user_id,
					$post_id
				)
			);
			$accept_timestamp = strtotime( str_replace( '@', ' ', $accept_timestamp_raw ) );
		}
		$days    = (int) $future_modifier['days'];
		$hours   = (int) $future_modifier['hours'];
		$minutes = (int) $future_modifier['minutes'];
		$seconds = (int) $future_modifier['seconds'];
		$future_time = strtotime( "{$days} days", 0) + strtotime( "{$hours} hours", 0) + strtotime( "{$minutes} minutes", 0) + strtotime( "{$seconds} seconds", 0) + $accept_timestamp;
		if ( $status == 2 || ( ! empty( $accept_timestamp) && $status < 3 ) ) {
			go_task_timer( $post_id, $user_id, $future_modifier );
		}
		
		if ( $future_time != $accept_timestamp && ( ( $unix_now >= $future_time && $status >= 2 ) || ( $unix_now >= $future_time && ! empty( $accept_timestamp ) ) ) ) {
			$future_update_percent = (float) ( (100 - $future_modifier['percentage'] )/100);
			$future_timer = true;
		} else {
			$future_update_percent = 1;
		}
		if ( $status < 3 ) {
			$time_string = ( ( ! empty( $days) ) ? "{$days} day".( ( $days > 1) ? 's' : '' ).( ( ! empty( $hours ) || ! empty( $minutes ) || ! empty( $seconds ) ) ? ', ' : '' ) : '' ) .
						   ( ( ! empty( $hours) ) ? "{$hours} hour".( ( $hours > 1) ? 's' : '' ).( ( ! empty( $minutes ) || ! empty( $seconds ) ) ? ', ' : '' ) : '' ).
						   ( ( ! empty( $minutes) ) ? "{$minutes} minute".( ( $minutes > 1) ? 's' : '' ).( ( ! empty( $seconds ) ) ? ', ' : '' ) : '' ).
						   ( ( ! empty( $seconds) ) ? "{$seconds} second".( ( $seconds > 1) ? 's' : '' ) : '' );
			echo "<span id='go_future_notification'><span id='go_future_notification_task_name'>Time Sensitive ".ucfirst( $task_name ).":</span><br/> After accepting you will have {$time_string} to reach ".go_return_options( 'go_third_stage_name' )." of this {$task_name} before the rewards will be irrevocably reduced by {$future_modifier['percentage']}%.</span>";
		}
	} else {
		$future_update_percent = 1;
	}

	if ( ! empty( $future_switches['calendar'] ) ) {
		$update_percent = $date_update_percent;
	} elseif ( ! empty( $future_switches['future'] ) ) {
		$update_percent = $future_update_percent;
	} else {
		$update_percent = 1;
	}

	$complete_stage = ( $undo ? $status - 1 : $status );

	// if the button pressed IS the repeat button...
	if ( $repeat_button ) {
		if ( $undo ) {
			if ( $task_count > 0 ) {
				go_add_post(
					$user_id, $post_id, $status,
					-floor( ( $update_percent * $points_array[ $status ] ) ),
					-floor( ( $update_percent * $currency_array[ $status ] ) ),
					-floor( ( $update_percent * $bonus_currency_array[ $status ] ) ),
					null, $page_id, $repeat_button, -1,
					$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
					$e_passed, $a_passed, $c_passed, $m_passed
				);
				if ( ! empty( $bonus_loot[0] ) ) {
					if ( ! empty( $bonus_loot[1] ) ) {
						foreach ( $bonus_loot[1] as $store_item => $on ) {
							if ( $on === 'on' && ! empty( $bonus_loot[2][ $store_item ] ) ) {
								$store_custom_fields = get_post_custom( $store_item );
								$store_cost = ( ! empty( $store_custom_fields['go_mta_store_cost'][0] ) ? unserialize( $store_custom_fields['go_mta_store_cost'][0] ) : array() );
								$currency = ( $store_cost[0] < 0 ) ? $store_cost[0] : 0;
								$points = ( $store_cost[1] < 0 ) ? $store_cost[1] : 0;
								$bonus_currency = ( $store_cost[2] < 0 ) ? $store_cost[2] : 0;
								$penalty = ( $store_cost[3] > 0 ) ? $store_cost[3] : 0;
								$minutes = ( $store_cost[4] < 0 ) ? $store_cost[4] : 0;
								$user_id = get_current_user_id();
								$received = $wpdb->query(
									$wpdb->prepare(
										"SELECT * 
										FROM {$go_table_name} 
										WHERE uid = %d AND status = %d AND gifted = %d AND post_id = %d AND reason = 'Bonus' 
										ORDER BY timestamp DESC, reason DESC, id DESC 
										LIMIT 1",
										$user_id,
										-1,
										0,
										$store_item
									)
								);
								if ( $received ) {
									go_update_totals( $user_id, $points, $currency, $bonus_currency, 0, $minutes, null, false, true );
								}
								$wpdb->query(
									$wpdb->prepare(
										"DELETE FROM {$go_table_name} 
										WHERE uid = %d AND status = %d AND gifted = %d AND post_id = %d AND reason = 'Bonus' 
										ORDER BY timestamp DESC, reason DESC, id DESC 
										LIMIT 1",
										$user_id,
										-1,
										0,
										$store_item
									)
								);
							}
						}
					}
				}
			} else {
				go_add_post(
					$user_id, $post_id, ( $status - 1 ),
					-floor( ( $update_percent * $points_array[ $status - 1 ] ) ),
					-floor( ( $update_percent * $currency_array[ $status - 1 ] ) ),
					-floor( ( $update_percent * $bonus_currency_array[ $status - 1 ] ) ),
					null, $page_id, $repeat_button, 0,
					$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
					$e_passed, $a_passed, $c_passed, $m_passed
				);
				if ( ! empty( $bonus_loot[0] ) ) {
					if ( ! empty( $bonus_loot[1] ) ) {
						foreach ( $bonus_loot[1] as $store_item => $on) {
							if ( $on === 'on' && ! empty( $bonus_loot[2][ $store_item ] ) ) {
								$store_custom_fields = get_post_custom( $store_item );
								$store_cost = ( ! empty( $store_custom_fields['go_mta_store_cost'][0] ) ? unserialize( $store_custom_fields['go_mta_store_cost'][0] ) : array() );
								$currency = ( $store_cost[0] < 0 ) ? $store_cost[0] : 0;
								$points = ( $store_cost[1] < 0) ? $store_cost[1] : 0;
								$bonus_currency = ( $store_cost[2] < 0) ? $store_cost[2] : 0;
								$penalty = ( $store_cost[3] > 0) ? $store_cost[3] : 0;
								$minutes = ( $store_cost[4] < 0) ? $store_cost[4] : 0;
								$loot_reason = 'Bonus';
								$user_id = get_current_user_id();
								$received = $wpdb->query(
									$wpdb->prepare(
										"SELECT * 
										FROM {$go_table_name} 
										WHERE uid = %d AND status = %d AND gifted = %d AND post_id = %d AND reason = 'Bonus' 
										ORDER BY timestamp DESC, reason DESC, id DESC 
										LIMIT 1",
										$user_id,
										-1,
										0,
										$store_item
									)
								);
								if ( $received ) {
									go_update_totals( $user_id, $points, $currency, $bonus_currency, 0, $minutes, null, $loot_reason, true, false );
								}
								$wpdb->query(
									$wpdb->prepare(
										"DELETE FROM {$go_table_name} 
										WHERE uid = %d AND status = %d AND gifted = %d AND post_id = %d AND reason = 'Bonus' 
										ORDER BY timestamp DESC, reason DESC, id DESC 
										LIMIT 1",
										$user_id,
										-1,
										0,
										$store_item
									)
								);
							}
						}
					}
				}
				if ( $stage_badges[ $status ][0] ) {
					foreach ( $stage_badges[ $status ][1] as $badge_id ) {
						go_remove_badge( $user_id, $badge_id );
					}
				}
			}
		} else {
			// if repeat is on and undo is not hit...
			go_add_post(
				$user_id, $post_id, $status,
				floor( ( $update_percent * $points_array[ $status ] ) ),
				floor( ( $update_percent * $currency_array[ $status ] ) ),
				floor( ( $update_percent * $bonus_currency_array[ $status ] ) ),
				null, $page_id, $repeat_button, 1,
				$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
				$e_passed, $a_passed, $c_passed, $m_passed, $url
			);
			if ( $stage_badges[ $status ][0] ) {
				foreach ( $stage_badges[ $status ][1] as $badge_id ) {
					go_award_badge(
						array(
							'id'        => $badge_id,
							'repeat'    => false,
							'uid'       => $user_id
						)
					);
				}
			}
		}   
	// if the button pressed is NOT the repeat button...
	} else {
		$db_status = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT status 
				FROM {$go_table_name} 
				WHERE uid = %d AND post_id = %d",
				$user_id,
				$post_id
			)
		);
		if ( $db_status == 0 || ( $db_status <= $status ) ) {
			if ( $undo ) {
				if ( $task_count > 0 ) {
					go_add_post(
						$user_id, $post_id, $status,
						-floor( ( $update_percent * $points_array[ $status - 1 ] ) ),
						-floor( ( $update_percent * $currency_array[ $status - 1 ] ) ),
						-floor( ( $update_percent * $bonus_currency_array[ $status - 1 ] ) ),
						null, $page_id, $repeat_button, -1,
						$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
						$e_passed, $a_passed, $c_passed, $m_passed
					);
				} elseif ( $db_status <= 3 ) {
					go_add_post(
						$user_id, $post_id, ( $status - 2 ),
						-floor( ( $update_percent * $points_array[ $status - 2 ] ) ),
						-floor( ( $update_percent * $currency_array[ $status - 2 ] ) ),
						-floor( ( $update_percent * $bonus_currency_array[ $status - 2 ] ) ),
						null, $page_id, $repeat_button, 0,
						$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
						$e_passed, $a_passed, $c_passed, $m_passed
					);
					if ( $stage_badges[ $db_status - 2 ][0] ) {
						foreach ( $stage_badges[ $db_status - 2 ][1] as $badge_id ) {
							go_remove_badge( $user_id, $badge_id );
						}
					}
				} else {
					go_add_post(
						$user_id, $post_id, ( $status - 1 ),
						-floor( ( $update_percent * $points_array[ $status - 1 ] ) ),
						-floor( ( $update_percent * $currency_array[ $status - 1 ] ) ),
						-floor( ( $update_percent * $bonus_currency_array[ $status - 1 ] ) ),
						null, $page_id, $repeat_button, 0,
						$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
						$e_passed, $a_passed, $c_passed, $m_passed
					);
					if ( $stage_badges[ $status - 1 ][0] ) {
						foreach ( $stage_badges[ $status - 1 ][1] as $badge_id ) {
							go_remove_badge( $user_id, $badge_id );
						}
					}
				}
			} else {
				$update_time = ( $status == 2 ) ? true : false;

				go_add_post(
					$user_id, $post_id, $status,
					floor( ( $update_percent * $points_array[ $status - 1 ] ) ),
					floor( ( $update_percent * $currency_array[ $status - 1 ] ) ),
					floor( ( $update_percent * $bonus_currency_array[ $status - 1 ] ) ),
					null, $page_id, $repeat_button, 0,
					$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
					$e_passed, $a_passed, $c_passed, $m_passed, $url, $update_time
				);
				if ( $stage_badges[ $status - 2 ][0] ) {
					foreach ( $stage_badges[ $status - 2 ][1] as $badge_id ) {
						go_award_badge(
							array(
								'id'        => $badge_id,
								'repeat'    => false,
								'uid'       => $user_id
							)
						);
					}
				}
			}
		}
	}
	
	// redefine the status and task_count because they have been updated as soon as the above go_add_post() calls are made.
	$status = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status 
			FROM {$go_table_name} 
			WHERE uid = %d AND post_id = %d",
			$user_id,
			$post_id
		)
	);
	$task_count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT count 
			FROM {$go_table_name} 
			WHERE post_id = %d AND uid = %d",
			$post_id,
			$user_id
		)
	);

	if ( ! $undo ) {
		if ( $task_count == 1 ) {
			go_record_stage_time( $post_id, 5 );    
		} else {
			go_record_stage_time( $post_id, $status );
		}   
	}
	
	switch ( $status ) {
		case ( 0 ):
			$db_task_stage_upload_var = 'e_uploaded';
			break;
		case ( 1 ):
			$db_task_stage_upload_var = 'a_uploaded';
			break;
		case ( 2 ):
			$db_task_stage_upload_var = 'c_uploaded';
			break;
		case ( 3 ):
			$db_task_stage_upload_var = 'm_uploaded';
			break;
		case ( 4 ):
			$db_task_stage_upload_var = 'r_uploaded';
			break;
	}
	if ( ! empty( $db_task_stage_upload_var ) ) {
		$is_uploaded = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT {$db_task_stage_upload_var} 
				FROM {$go_table_name} 
				WHERE uid = %d AND post_id = %d",
				$user_id,
				$post_id
			)
		);
	} else {
		$is_uploaded = 0;
	}

	// Controls output of the page, after the #go_button has been pressed for the first time while viewing the page.
	switch ( $status ) {
		case 1:
			echo '<div id="new_content">'.'<div class="go_stage_message">'.do_shortcode( wpautop( $accept_message, false ) ).'</div>';
			if ( $test_e_active ) {
				echo "<p id='go_test_error_msg' style='color: red;'></p>";
				if ( $test_e_num > 1 ) {
					for ( $i = 0; $i < $test_e_num; $i++ ) {
						if ( ! empty( $test_e_all_types[ $i ] ) &&
								! empty( $test_e_all_questions[ $i ] ) &&
								! empty( $test_e_all_answers[ $i ] ) &&
								! empty( $test_e_all_keys[ $i ] ) ) {

							echo do_shortcode( "[go_test type='".$test_e_all_types[ $i ]."' question='".$test_e_all_questions[ $i ]."' possible_answers='".$test_e_all_answers[ $i ]."' key='".$test_e_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_e_num."']" );
						}
					}
					echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				} elseif ( ! empty( $test_e_all_types[0] ) &&
						! empty( $test_e_all_questions[0] ) &&
						! empty( $test_e_all_answers[0] ) &&
						! empty( $test_e_all_keys[0] ) ) {

					echo do_shortcode( "[go_test type='".$test_e_all_types[0]."' question='".$test_e_all_questions[0]."' possible_answers='".$test_e_all_answers[0]."' key='".$test_e_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				}
			}
			if ( $encounter_upload ) {
				echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
			}
			echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
			if ( $e_is_locked && ! empty( $e_pass_lock ) ) {
				echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
			} elseif ( $e_url_is_locked === true ) {
				echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
			}
			echo "<button id='go_button' status='2' onclick='task_stage_change( this );'";
			if ( $e_is_locked && empty( $e_pass_lock ) ) {
				echo "admin_lock='true'";
			}
			echo ">".go_return_options( 'go_second_stage_button' )."</button>
			<button id='go_abandon_task' onclick='go_task_abandon();this.disabled = true;'>".get_option( 'go_abandon_stage_button', 'Abandon' )."</button></div>";

			go_task_render_chain_pagination( $post_id, $user_id );

			echo ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
			break;
		case 2:
			echo '<div id="new_content">'.'<div class="go_stage_message">'.do_shortcode( wpautop( $accept_message, false ) ).'</div>';
			if ( $test_a_active ) {
				echo "<p id='go_test_error_msg' style='color: red;'></p>";
				if ( $test_a_num > 1 ) {
					for ( $i = 0; $i < $test_a_num; $i++ ) {
						if ( ! empty( $test_a_all_types[ $i ] ) &&
								! empty( $test_a_all_questions[ $i ] ) &&
								! empty( $test_a_all_answers[ $i ] ) &&
								! empty( $test_a_all_keys[ $i ] ) ) {

							echo do_shortcode( "[go_test type='".$test_a_all_types[ $i ]."' question='".$test_a_all_questions[ $i ]."' possible_answers='".$test_a_all_answers[ $i ]."' key='".$test_a_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_a_num."']" );
						}
					}
					echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				} elseif ( ! empty( $test_a_all_types[0] ) &&
						! empty( $test_a_all_questions[0] ) &&
						! empty( $test_a_all_answers[0] ) &&
						! empty( $test_a_all_keys[0] ) ) {

					echo do_shortcode( "[go_test type='".$test_a_all_types[0]."' question='".$test_a_all_questions[0]."' possible_answers='".$test_a_all_answers[0]."' key='".$test_a_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				}
			}
			if ( $accept_upload ) {
				echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
			}
			echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
			if ( $a_is_locked && ! empty( $a_pass_lock ) ) {
				echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
			} elseif ( $a_url_is_locked === true ) {
				echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
			}
			echo "<button id='go_button' status='3' onclick='task_stage_change( this );'";
			if ( $a_is_locked && empty( $a_pass_lock ) ) {
				echo "admin_lock='true'";
			}
			echo '>'.go_return_options( 'go_third_stage_button' ).'</button> '.
				'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button></div>';

			go_task_render_chain_pagination( $post_id, $user_id );

			echo ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
			break;
		case 3:
			echo '<div class="go_stage_message">'.do_shortcode( wpautop( $accept_message, false ) ).'</div>'.'<div id="new_content"><div class="go_stage_message">'
			.do_shortcode(wpautop( $completion_message ) ).'</div>';
			if ( $mastery_active ) {
				if ( $test_c_active ) {
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					if ( $test_c_num > 1 ) {
						for ( $i = 0; $i < $test_c_num; $i++ ) {
							if ( ! empty( $test_c_all_types[ $i ] ) &&
									! empty( $test_c_all_questions[ $i ] ) &&
									! empty( $test_c_all_answers[ $i ] ) &&
									! empty( $test_c_all_keys[ $i ] ) ) {

								echo do_shortcode( "[go_test type='".$test_c_all_types[ $i ]."' question='".$test_c_all_questions[ $i ]."' possible_answers='".$test_c_all_answers[ $i ]."' key='".$test_c_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_c_num."']" );
							}
						}
						echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					} elseif ( ! empty( $test_c_all_types[0] ) &&
							! empty( $test_c_all_questions[0] ) &&
							! empty( $test_c_all_answers[0] ) &&
							! empty( $test_c_all_keys[0] ) ) {

						echo do_shortcode( "[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					}
				}
				if ( $completion_upload ) {
					echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
				}
				echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
				if ( $c_is_locked && ! empty( $c_pass_lock ) ) {
					echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
				} elseif ( $c_url_is_locked === true ) {
					echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
				}
				echo "<button id='go_button' status='4' onclick='task_stage_change( this );'";
				if ( $c_is_locked && empty( $c_pass_lock ) ) {
					echo "admin_lock='true'";
				}
				echo '>'.go_return_options( 'go_fourth_stage_button' ).'</button> '.
					'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';

				go_task_render_chain_pagination( $post_id, $user_id );
			} else {
				if ( $test_c_active ) {
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					if ( $test_c_num > 1 ) {
						for ( $i = 0; $i < $test_c_num; $i++ ) {
							if ( ! empty( $test_c_all_types[ $i ] ) &&
									! empty( $test_c_all_questions[ $i ] ) &&
									! empty( $test_c_all_answers[ $i ] ) &&
									! empty( $test_c_all_keys[ $i ] ) ) {

								echo do_shortcode( "[go_test type='".$test_c_all_types[ $i ]."' question='".$test_c_all_questions[ $i ]."' possible_answers='".$test_c_all_answers[ $i ]."' key='".$test_c_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_c_num."']" );
							}
						}
						echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					} elseif ( ! empty( $test_c_all_types[0] ) &&
							! empty( $test_c_all_questions[0] ) &&
							! empty( $test_c_all_answers[0] ) &&
							! empty( $test_c_all_keys[0] ) ) {

						echo do_shortcode( "[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					}
				}
				if ( $completion_upload ) {
					echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
				}
				echo '<span id="go_button" status="4" style="display:none;"></span>'.
					'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';
				
				go_task_render_chain_pagination( $post_id, $user_id );
				
				echo "</div>" . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
			}
			break;
		case 4:
			echo '<div class="go_stage_message">'.do_shortcode( wpautop( $accept_message, false ) ).'</div><div class="go_stage_message">'.do_shortcode( wpautop( $completion_message ) ).
			'</div><div id="new_content"><div class="go_stage_message">'.do_shortcode( wpautop( $mastery_message ) ).'</div>';
			// if the task can be repeated...
			if ( $repeat == 'on' ) {
				// if the number of times that the page has been repeated is less than the total amount of repeats allowed OR if the 
				// total repeats allowed is equal to zero (infinte amount allowed)...
				if ( $task_count < $repeat_amount || $repeat_amount == 0 ) {
					if ( $task_count == 0 ) {
						if ( $test_m_active ) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ( $test_m_num > 1 ) {
								for ( $i = 0; $i < $test_m_num; $i++ ) {
									if ( ! empty( $test_m_all_types[ $i ] ) &&
											! empty( $test_m_all_questions[ $i ] ) &&
											! empty( $test_m_all_answers[ $i ] ) &&
											! empty( $test_m_all_keys[ $i ] ) ) {

										echo do_shortcode( "[go_test type='".$test_m_all_types[ $i ]."' question='".$test_m_all_questions[ $i ]."' possible_answers='".$test_m_all_answers[ $i ]."' key='".$test_m_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_m_num."']" );
									}
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} elseif ( ! empty( $test_m_all_types[0] ) &&
									! empty( $test_m_all_questions[0] ) &&
									! empty( $test_m_all_answers[0] ) &&
									! empty( $test_m_all_keys[0] ) ) {

								echo do_shortcode( "[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ( $mastery_upload ) {
							echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
						}
						echo '
							<div id="repeat_quest">
								<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
									.do_shortcode(wpautop( $repeat_message ) ).
									"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
						if ( $m_is_locked && ! empty( $m_pass_lock ) ) {
							echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
						} elseif ( $m_url_is_locked === true ) {
							echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
						}
						echo "<button id='go_button' status='4' onclick='go_repeat_hide( this );' repeat='on'";
						if ( $m_is_locked && empty( $m_pass_lock ) ) {
							echo "admin_lock='true'";
						}
						echo '>'.go_return_options( 'go_fourth_stage_button' )." Again". 
									'</button>
									<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
								'</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
								'<div id="go_repeat_unclicked">'.
									'<button id="go_button" status="4" onclick="go_repeat_replace();">'.
										'See ' . get_option( 'go_fifth_stage_name' ).
									'</button> '.
									'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
								'</div>'.
								( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
							'</div>';
					} else {
						if ( $repeat_upload ) {
							echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
						}
						echo '
							<div id="repeat_quest">
								<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
									.do_shortcode(wpautop( $repeat_message ) ).
									"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
						if ( $r_is_locked && ! empty( $r_pass_lock ) ) {
							echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
						}
						echo "<button id='go_button' status='4' onclick='go_repeat_hide( this );' repeat='on'";
						if ( $r_is_locked && empty( $r_pass_lock ) ) {
							echo "admin_lock='true'";
						}
						echo '>'.go_return_options( 'go_fourth_stage_button' )." Again". 
									'</button>
									<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';

						echo '</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
								'<div id="go_repeat_unclicked">'.
									'<button id="go_button" status="4" onclick="go_repeat_replace();">'.
										'See ' . get_option( 'go_fifth_stage_name' ).
									'</button> '.
									'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
								'</div>'.
								( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : '' ).
							'</div>';
					}
				} else {
					echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span>'.
						'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>'.
						( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
				}
			} else {

				// if repeat is off...
				if ( $test_m_active ) {
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					if ( $test_m_num > 1 ) {
						for ( $i = 0; $i < $test_m_num; $i++ ) {
							if ( ! empty( $test_m_all_types[ $i ] ) &&
									! empty( $test_m_all_questions[ $i ] ) &&
									! empty( $test_m_all_answers[ $i ] ) &&
									! empty( $test_m_all_keys[ $i ] ) ) {

								echo do_shortcode( "[go_test type='".$test_m_all_types[ $i ]."' question='".$test_m_all_questions[ $i ]."' possible_answers='".$test_m_all_answers[ $i ]."' key='".$test_m_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_m_num."']" );
							}
						}
						echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					} elseif ( ! empty( $test_m_all_types[0] ) &&
							! empty( $test_m_all_questions[0] ) &&
							! empty( $test_m_all_answers[0] ) &&
							! empty( $test_m_all_keys[0] ) ) {

						echo do_shortcode( "[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					}
				}
				if ( $mastery_upload ) {
					echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]" )."<br/>";
				}
				$mastered = (array) get_user_meta( $user_id, 'mastered_tasks', true );
				$user_id = get_current_user_id();

				echo ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
				if ( ! empty( $bonus_loot[0] ) ) {
					if ( ! empty( $bonus_loot[1] ) ) {
						foreach ( $bonus_loot[1] as $store_item => $on ) {
							if ( $on === 'on' && ! empty( $bonus_loot[2][ $store_item ] ) ) {
								$random_number = rand( 1, 999 );
								$drop_chance = floatval( $bonus_loot[2][ $store_item ] ) * 10;
								$store_custom_fields = get_post_custom( $store_item );
								$store_cost = ( ! empty( $store_custom_fields['go_mta_store_cost'][0] ) ? unserialize( $store_custom_fields['go_mta_store_cost'][0] ) : array() );
								$currency = ( $store_cost[0] < 0 ) ? -$store_cost[0] : 0;
								$points = ( $store_cost[1] < 0 ) ? -$store_cost[1] : 0;
								$bonus_currency = ( $store_cost[2] < 0 ) ? -$store_cost[2] : 0;
								$penalty = ( $store_cost[3] > 0 ) ? -$store_cost[3] : 0;
								$minutes = ( $store_cost[4] < 0 ) ? -$store_cost[4] : 0;
								$loot_reason = 'Bonus';
								if ( $random_number < $drop_chance ) {
									if ( ! in_array( $post_id, $mastered ) ) {
										go_add_post(
											$user_id, $store_item, -1,
											$points, $currency, $bonus_currency, $minutes,
											null, false, 0,
											$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
											$e_passed, $a_passed, $c_passed, $m_passed, null, false,
											$loot_reason, true, false
										);
										echo "Congrats, " . do_shortcode( '[go_get_displayname]' ).
											"!  You received an item: <a href='#' onclick='go_lb_opener({$store_item})'>".
											get_the_title( $store_item ) . "</a></br>";
									}
								}
							}
						}
					}
					if ( ! in_array( $post_id, $mastered ) ) {
						$mastered[] = $post_id;
						update_user_meta( $user_id, 'mastered_tasks', $mastered );
					}
					echo "</br>";
				}
				add_user_meta( $user_id, 'ever_mastered', $post_id, true );
				echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span>'.
					'<button id="go_back_button" onclick="task_stage_change( this );" undo="true">Undo</button>';
			}

			go_task_render_chain_pagination( $post_id, $user_id );

			echo '</div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
	}

	die();
}

function go_display_rewards( $user_id, $points, $currency, $bonus_currency, $update_percent = 1, $number_of_stages = 4, $future = false ) {
	if ( ! is_null( $number_of_stages ) && ( ! is_null( $points ) || ! is_null( $currency ) || ! is_null( $bonus_currency ) ) ) {
		echo "<div class='go_task_rewards' style='margin: 6px 0px 6px 0px;'><strong>Rewards</strong><br/>";
		$p_name = go_return_options( 'go_points_name' );
		$c_name = go_return_options( 'go_currency_name' );
		$bc_name = go_return_options( 'go_bonus_currency_name' );
		$u_bonuses = go_return_bonus_currency( $user_id );
		$u_penalties = go_return_penalty( $user_id );
		if ( $update_percent !== 1 && ! empty( $update_percent ) ) {
			$rewards_array = array( $points, $currency, $bonus_currency );
			$p_array = $rewards_array[0];
			$c_array = $rewards_array[1];
			$bc_array = $rewards_array[2];
		} else {
			$p_array = $points;
			$c_array = $currency;
			$bc_array = $bonus_currency;
		}

		$custom_fields = get_post_custom();
		for ( $i = 0; $i < $number_of_stages; $i++ ) {
			if ( $future ) {
				if ( 2 == $i ) {
					$mod_array = go_return_multiplier( $user_id, floor( $p_array[ $i ] * $update_percent ), floor( $c_array[ $i ] * $update_percent ), $u_bonuses, $u_penalties );
				} else {
					$mod_array = array( $p_array[ $i ], $c_array[ $i ] );   
				}
			} else {
				$mod_array = go_return_multiplier( $user_id, floor( $p_array[ $i ] * $update_percent ), floor( $c_array[ $i ] * $update_percent ), $u_bonuses, $u_penalties );
			}
			if ( ! empty( $mod_array[0] ) ) {
				$modded_points = (int) $mod_array[0];
			} else {
				$modded_points = 0;
			}
			if ( ! empty( $mod_array[1] ) ) {
				$modded_currency = (int) $mod_array[1];
			} else {
				$modded_currency = 0;
			}
			$bc = (int) floor( $bc_array[ $i ] * $update_percent );
			$stage_name = '';
			switch ( $i ) {
				case 0:
					$stage_name = go_return_options( 'go_first_stage_name' );
					break;
				case 1:
					$stage_name = go_return_options( 'go_second_stage_name' );
					break;
				case 2:
					$stage_name = go_return_options( 'go_third_stage_name' );
					break;
				case 3:
					$stage_name = go_return_options( 'go_fourth_stage_name' );
					break;
				case 4:
					$stage_name = go_return_options( 'go_fifth_stage_name' );
					break;
			}
			$stage = $i + 1;
			if ( 0 === $modded_points && 0 === $modded_currency && 0 === $bc ) {
				$output = "{$stage_name} - <span id='go_task_stage_{$stage}_rewards'>No Rewards</span><br/>";
			} else {
				$point_output = '';
				$currency_output = '';
				$bc_output = '';
				if ( 0 !== $modded_points && ! empty( $p_name ) ) {
					$point_output = "<span id='go_stage_{$stage}_points'>{$modded_points}</span> {$p_name}";
				} else {
					$point_output = "";
				}
				if ( 0 !== $modded_currency && ! empty( $c_name ) ) {
					$currency_output = "<span id='go_stage_{$stage}_currency'>{$modded_currency}</span> {$c_name}";
				} else {
					$currency_output = "";
				}
				if ( 0 !== $bc && ! empty( $bc_name ) ) {
					$bc_output = "<span id='go_stage_{$stage}_bonus_currency'>{$bc}</span> {$bc_name}";
				} else {
					$bc_output = "";
				}
				$output = 
					"{$stage_name} - ".
					"<span id='go_task_stage_{$stage}_rewards'>".
						"{$point_output} {$currency_output} {$bc_output}".
					"</span>".
					"<br/>";
			}
			echo $output;
			if ( ! empty( $custom_fields['go_mta_mastery_bonus_loot'][0] ) ) {
				$bonus_loot = unserialize( $custom_fields['go_mta_mastery_bonus_loot'][0] );
			}
		}
		if ( ! empty( $bonus_loot ) && ! empty( $bonus_loot[0] ) ) {
			$bonus_loot_display = true;
			if ( ! empty( $bonus_loot[1] ) ) {
				$bonus_items_array = array();
				foreach ( $bonus_loot[1] as $store_item => $on ) {
					if ( $on === 'on' && ! empty( $bonus_loot[2][ $store_item ] ) ) {
						$drop_chance_percentile = $bonus_loot[2][ $store_item ];
						$bonus_items_array[] = "<a href='#' onclick='go_lb_opener({$store_item})'>".get_the_title( $store_item )."</a> ".$drop_chance_percentile."% Drop Rate";
					}
				}
			}
		}
		$bonus_loot_name = go_return_options( 'go_bonus_loot_name' );
		$task_loot_name = go_return_options( 'go_task_loot_name' );
		if ( ! empty( $bonus_items_array ) ) {
			$bonus_items_array_keys = array_keys( $bonus_items_array );
		}
		if ( ! empty( $bonus_items_array ) ) {
			echo "<strong>{$bonus_loot_name}</strong> - ";
			foreach ( $bonus_items_array_keys as $index => $key ) {
				echo $bonus_items_array[ $key ];
				if ( $index < max( $bonus_items_array_keys ) ) {
					echo ', ';
				}
			}
		}
		echo '</div>';
	} 
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
			$timestamps[ $post_id ] = array(
				$status => array(
					0 => $time,
					1 => $time,
				),
			);
		} elseif ( 5 == $status ) {
			$timestamps[ $post_id ][ $status ][0] = $time;
		} else {
			$timestamps[ $post_id ][ $status ][1] = $time;
		}
	}
	update_user_meta( $user_id, 'go_task_timestamps', $timestamps );
}

/**
 * Outputs the task chain navigation links for the specified task and user.
 *
 * Outputs a link to the next and previous tasks, if they exist. That is, the first task in the
 * chain will not have a "previous" link, and the last task will not have a "next" link. If the
 * task is the last in the chain, the final chain message (stored in the `go_mta_final_chain_message`
 * meta data) will be displayed.
 *
 * @since 3.0.0
 *
 * @param int $task_id The task ID.
 * @param int $user_id Optional. The user ID.
 */
function go_task_render_chain_pagination( $task_id, $user_id = null ) {
	if ( empty( $task_id ) ) {
		return;
	} else {
		$task_id = (int) $task_id;
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	} else {
		$user_id = (int) $user_id;
	}

	if ( go_user_is_admin( $user_id ) ) {
		return;
	}

	$chain_order = get_post_meta( $task_id, 'go_mta_chain_order', true );

	if ( empty( $chain_order ) || ! is_array( $chain_order ) ) {
		return;
	}

	$final_chain_msg = get_post_meta( $task_id, 'go_mta_final_chain_message', true );
	$can_display_final_msg = false;
	$is_final_msg_displayed = false;

	foreach ( $chain_order as $chain_tt_id => $order ) {
		$pos = array_search( $task_id, $order );
		$the_chain = get_term_by( 'term_taxonomy_id', $chain_tt_id );
		$chain_title = ucwords( $the_chain->name );
		$last_in_chain = go_task_chain_is_final_task( $task_id, $chain_tt_id );

		$prev_finished = false;
		if ( $pos > 0 ) {
			$prev_id = 0;

			// finds the first ID among the tasks before the current one that is published
			for ( $prev_id_counter = $pos; $prev_id_counter > 0; $prev_id_counter-- ) {
				$temp_id = $order[ $prev_id_counter - 1 ];
				$temp_task = get_post( $temp_id );
				if ( ! empty( $temp_task ) && 'publish' === $temp_task->post_status ) {
					$prev_id = $temp_id;
					break;
				}
			}

			if ( 0 !== $prev_id ) {
				$prev_status             = go_task_get_status( $prev_id );
				$prev_five_stage_counter = null;
				$prev_status_required    = 4;
				$prev_three_stage_active = (boolean) get_post_meta( $prev_id, 'go_mta_three_stage_switch', true );
				$prev_five_stage_active  = (boolean) get_post_meta( $prev_id, 'go_mta_five_stage_switch', true );
				if ( $prev_three_stage_active ) {
					$prev_status_required = 3;
				} elseif ( $prev_five_stage_active ) {
					$prev_five_stage_counter = go_task_get_repeat_count( $prev_id );
				}
				if ( $prev_status === $prev_status_required &&
						( ! $prev_five_stage_active || ! empty( $prev_five_stage_counter ) ) ) {

					$prev_finished = true;
				}
			}
		}

		$curr_finished           = false;
		$curr_status             = go_task_get_status( $task_id );
		$curr_five_stage_counter = null;
		$curr_status_required    = 4;
		$curr_three_stage_active = (boolean) get_post_meta( $task_id, 'go_mta_three_stage_switch', true );
		$curr_five_stage_active  = (boolean) get_post_meta( $task_id, 'go_mta_five_stage_switch', true );
		if ( $curr_three_stage_active ) {
			$curr_status_required = 3;
		} elseif ( $curr_five_stage_active ) {
			$curr_five_stage_counter = go_task_get_repeat_count( $task_id );
		}

		if ( $curr_status === $curr_status_required &&
				( ! $curr_five_stage_active || ! empty( $curr_five_stage_counter ) ) ) {

			$curr_finished = true;
		}

		if ( false !== $pos ) {

			/**
			 * There are more tasks in this chain other than the current one and the task is finished.
			 */

			$next_id = 0;
			for ( $next_id_counter = $pos; $next_id_counter < count( $order ) - 1; $next_id_counter++ ) {
				$temp_id = $order[ $next_id_counter + 1 ];
				$temp_task = get_post( $temp_id );
				if ( ! empty( $temp_task ) && 'publish' === $temp_task->post_status ) {
					$next_id = $temp_id;
					break;
				}
			}

			$msg = '';

			foreach ( $order as $_id ) {
				$task_title = get_the_title( $_id );
				$task_perma = get_permalink( $_id );
				$block_classes = 'go_chain_link';
				$block_content = '';

				if ( $_id === $task_id ) {
					$block_content .= "<span>{$task_title}</span>";
					$block_classes .= ' go_chain_link_current';
				} else {
					$block_content .= sprintf(
						'<a href="%s">%s</a>',
						$task_perma,
						$task_title
					);
				}

				$msg .= sprintf(
					'<div class="%s">%s</div>',
					$block_classes,
					$block_content
				);
			}

			if ( ! $is_final_msg_displayed && ! empty( $final_chain_msg ) && $last_in_chain &&
					$curr_finished ) {
				$can_display_final_msg = true;
			}

			if ( ! empty( $msg ) || $can_display_final_msg ) {
				printf(
					'<div class="go_chain_msg_container">'.
					'<div class="go_chain_title go_align_center">%s</div>',
					$chain_title
				);
			
				// displays the final chain message for this chain
				if ( $can_display_final_msg ) {
					printf(
						'<div class="go_chain_final_msg">%s</div>',
						$final_chain_msg
					);
					$is_final_msg_displayed = true;
				}

				if ( ! empty( $msg ) ) {
					printf(
						'<div class="go_chain_links">%s</div>',
						$msg
					);
				}

				echo '</div>';
			}
		}
	}
}
?>
