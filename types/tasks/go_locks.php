<?php 
function debug_locks ( $data ) {
	//$task_is_locked = false;
    $output = $data;

    if ( is_array( $output ) )
        $output = implode( ',', $output);


    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

function task_locks ($is_logged_in, $is_filtered, $filtered_content_hidden, $is_admin, $start_filter, $temp_id, $chain_order, $id, $login_url, $badge_filter_meta, $task_name, $badge_name, $custom_fields, $user_id ){



/* 
LOCKS START
*/

	// prevents users (both logged-in and logged-out) from accessing the task content, if they
	// do not meet the requirements
	if ( !$is_admin && ($is_logged_in || ( ! $is_logged_in && $is_filtered && $filtered_content_hidden ) )) {

		/**
		 * Start Lock
		 */

		// holds the output to be displayed when a non-admin has been stopped by the start filter
		$time_string = '';
		$unix_now = current_time( 'timestamp' );
		if ( ! empty( $start_filter ) && ! empty( $start_filter['checked'] ) && ! $is_admin ) {
			$start_date = $start_filter['date'];
			$start_time = $start_filter['time'];
			$start_unix = strtotime( $start_date . $start_time );

			// stops execution if the user is a non-admin and the start date and time has not
			// passed yet
			if ( $unix_now < $start_unix ) {
				$time_string = date( 'g:i A', $start_unix ) . ' on ' . date( 'D, F j, Y', $start_unix );
				echo "<p><span class='go_error_red'>Will be available at {$time_string}.</span></p>";
				$task_is_locked = true;
			}
		}

		/**
		 * schedule Lock
		 */
		
		date_default_timezone_set('America/Los_Angeles');
		for( $i = 0; $i<5; $i++ ) {
			$avail_toggle = "scheduled_availability_" . $i . "_dow_toggle";
			if ( ! empty ($custom_fields[$avail_toggle][0])){
				$toggle_status = $custom_fields[$avail_toggle][0];
				if ($toggle_status == true){				
					$dow_days = "scheduled_availability_" . $i . "_dow_available";
					$dow_days = unserialize( $custom_fields[$dow_days][0]);
					$dow_time = "scheduled_availability_" . $i . "_dow_time";
					$dow_time = $custom_fields[$dow_time][0];
					$dow_minutes = "scheduled_availability_" . $i . "_dow_minutes";
					$dow_minutes = $custom_fields[$dow_minutes][0];
					$dow_time = strtotime($dow_time);			

					//it is unlocked at somepoint today, continue to check time to see if it is unlocked
					if (in_array(date("l"), $dow_days)){						
						//if the current time is between the start time and the start time and the minutes unlocked
						if ((time() >= strtotime($dow_time)) && ( time() < ($dow_time + ($dow_minutes * 60)))) {
							//it is unlocked, so exit loop and continue
							
							$is_locked = false;
						  	break;

						}
					}		
				}
			}
			else{
				break;
			}
		}

		if ($is_locked != false){
			$task_is_locked = true;
			
			echo "<p> <span class='go_error_red'>This is locked except at the following times:";


			for( $i = 0; $i<5; $i++ ) {
				$avail_toggle = "scheduled_availability_" . $i . "_dow_toggle";
				if ( ! empty ($custom_fields[$avail_toggle][0])){
					$toggle_status = $custom_fields[$avail_toggle][0];
					if ($toggle_status == true){				
						$dow_days = "scheduled_availability_" . $i . "_dow_available";
						$dow_days = unserialize( $custom_fields[$dow_days][0]);
						$dow_time = "scheduled_availability_" . $i . "_dow_time";
						$dow_time = $custom_fields[$dow_time][0];
						$dow_minutes = "scheduled_availability_" . $i . "_dow_minutes";
						$dow_minutes = $custom_fields[$dow_minutes][0];
						$dow_time = strtotime($dow_time);			
						echo "<br>";
						print_r( implode (", ", $dow_days));
						echo " @";
						echo date( 'g:iA', $dow_time);
						echo " for " . $dow_minutes . " minutes.";
							
					}
				}
				else{
					break;
				}
			}
			echo "</span></p>";	
		}

		/**
		 * Seating Chart/ Period Lock
		 */
		if ( ! empty ($custom_fields['course_section_go_period_toggle'][0])){
			$toggle_status = $custom_fields['course_section_go_period_toggle'][0];
			//if lock is on
			if ($toggle_status == true){

				//get the period that is the key
				$period_key = $custom_fields['course_section_course_section'][0];


				$user_class = get_user_meta( $user_id, 'go_classifications', true );
				if ( ! empty( $user_class ) ) {
					$user_period = array_keys( $user_class );
				}

				//settype($user_period, "string");
				$user_period = implode(", ",$user_period);
				//$user_period = trim($user_period);

				//debug_locks ($user_period);
				$period_key = trim($period_key);
				//debug_locks ($period_key);

				if ($period_key != $user_period) {
					echo "<p><span class='go_error_red'> You must be in " . $period_key . " to continue. </span></p>";
					$task_is_locked = true;
				}


			}
		}




		 /**
		 * Task Chain Lock
		 */

		// determines whether or not the user can proceed, if the task is in a chain
		$temp_optional_task  = (boolean) get_post_meta(
			$temp_id,
			'go_mta_optional_task',
			true
		);
		if ( ! empty( $chain_order ) ) {
			$chain_links = array();

			foreach ( $chain_order as $chain_tt_id => $order ) {
				$pos = array_search( $id, $order );
				$the_chain = get_term_by( 'term_taxonomy_id', $chain_tt_id );
				$chain_title = ucwords( $the_chain->name );
				$chain_pod = get_term_meta($chain_tt_id, 'pod_toggle', true);
				if ( $pos > 0 && ! $is_admin ) {
					if ( empty ( $temp_optional_task )){
					if (empty( $chain_pod )){
					/**
					 * The current task is not first and the user is not an administrator.
					 */

					$prev_id = 0;

					// finds the first ID among the tasks before the current one that is published
					for ( $prev_id_counter = 0; $prev_id_counter < $pos; $prev_id_counter++ ) {
						$temp_id = $order[ $prev_id_counter ];
						$temp_optional_prev_task  = (boolean) get_post_meta(
							$temp_id,
							'go_mta_optional_task',
							true
						);
						if ( empty ( $temp_optional_prev_task )){
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
					}} // End for().

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
				} // End if().
			}}
			} // End foreach().

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

				$visitor_str = '';
				if ( ! $is_logged_in ) {
					$visitor_str = ' First, you must be ' .
						'<a href="' . esc_url( $login_url ) . '">logged in</a> to do so.';
				}

				printf(
					'<p><span class="go_error_red">You must finish</span>' .
					' %s ' .
					'<span class="go_error_red">to continue.</span></p>	',
					$link_str,
					ucwords( $task_name ),
					$visitor_str
				);

				$task_is_locked = true;
			}
		} // End if().


		/**
		 * Honor and Damage Lock
		 */		
		
		// Checks if the task has a bonus currency filter
		// Sets the filter equal to the meta field value declared in the task creation page, if none exists defaults to 0
		$bonus_currency_required = ( ! empty( $custom_fields['go_mta_bonus_currency_filter'][0] ) ? $custom_fields['go_mta_bonus_currency_filter'][0] : 0 );
	
		// Checks if the task has a penalty filter
		$penalty_filter = ( ! empty( $custom_fields['go_mta_penalty_filter'][0] ) ? $custom_fields['go_mta_penalty_filter'][0] : null );
		//debug_locks( $penalty_filter );
		$locked_by_category = ( ! empty( $custom_fields['go_mta_focus_category_lock'][0] ) ? $custom_fields['go_mta_focus_category_lock'][0] : null );

		$points_array = ( ! empty( $rewards['points'] ) ? $rewards['points'] : array() );
		$points_str = implode( ' ', $points_array );
		$currency_array = ( ! empty( $rewards['currency'] ) ? $rewards['currency'] : array() );
		$currency_str = implode( ' ', $currency_array );
		$bonus_currency_array = ( ! empty( $rewards['bonus_currency'] ) ? $rewards['bonus_currency'] : array() );
		$bonus_currency_str = implode( ' ', $bonus_currency_array );
	
		$current_bonus_currency = go_return_bonus_currency( $user_id ); 
		$current_penalty = go_return_penalty( $user_id );
		//debug_locks( $current_penalty );
	
		if ( ( ! empty( $bonus_currency_required ) && $current_bonus_currency < $bonus_currency_required ) && ( ! empty( $penalty_filter ) && $current_penalty >= $penalty_filter ) ) {
			echo "<p><span class='go_error_red'>You require more than {$bonus_currency_required} ".go_return_options( 'go_bonus_currency_name' )." and less than {$penalty_filter} ".go_return_options( 'go_penalty_name' )." to view this ".go_return_options( 'go_tasks_name' ).".</span></p>";
			$task_is_locked = true;
		} else if ( ( ! empty( $bonus_currency_required ) && $current_bonus_currency < $bonus_currency_required ) ) {
			echo "<p><span class='go_error_red'>You require more than {$bonus_currency_required} ".go_return_options( 'go_bonus_currency_name' )." to view this ".go_return_options( 'go_tasks_name' ).".</span></p>";
			$task_is_locked = true;
		} else if ( ( ! empty( $penalty_filter ) && $current_penalty >= $penalty_filter ) ) {
			echo "<p><span class='go_error_red'>You require less than {$penalty_filter} ".go_return_options( 'go_penalty_name' )." to view this ".go_return_options( 'go_tasks_name' ).".</span></p>";
			$task_is_locked = true;
		}
		
		
		
		
		/**
		 * Specialty/Focus Category Lock
		 */
		$user_focus = get_user_meta( $user_id, 'go_focus', true );  	
		$locked_by_category = ( ! empty( $custom_fields['go_mta_focus_category_lock'][0] ) ? $custom_fields['go_mta_focus_category_lock'][0] : null );
		$focus_category_lock = ( ! empty( $locked_by_category ) ? true : false );	
		//debug_task($user_focus);
		if ( get_the_terms( $id, 'task_focus_categories' ) && $focus_category_lock ) {
			$categories = get_the_terms( $id, 'task_focus_categories' );
			$category_names = array();
			foreach( $categories as $category ) {
				array_push( $category_names, $category->name ); 
			}
	
			if ( ! empty( $category_names ) && $user_focus ) {
				$go_ahead = array_intersect( $user_focus, $category_names );
			}
	
			if ( ! empty( $go_ahead ) || ! isset( $focus_category_lock ) || empty( $category_names ) ){
			}	
			else { // If user can't access quest because they aren't part of the specialty, echo this
				$category_name = implode( ', ',$category_names );
				$focus_name = get_option( 'go_focus_name', 'Profession' );
				$task_name = strtolower( get_option( 'go_tasks_name', 'Quest' ) );
				echo "<p><span class='go_error_red'>This {$task_name} is only available to those with the \"{$category_name}\" {$focus_name}(s).</span></p>";
				$task_is_locked = true;
			}
		}
	
	
	
	
		/**
		 * Badge Lock
		 */

		// gets the user's current badges
		$user_badges = get_user_meta( $user_id, 'go_badges', true );
		if ( ! $user_badges ) {
			$user_badges = array();
		}

		// an array of badge IDs
		$badge_filter_ids = array();

		// determines if the user has the correct badges
		$badge_diff = array();
		if ( ! empty( $badge_filter_meta ) &&
			isset( $badge_filter_meta[0] ) &&
			$badge_filter_meta[0] &&
			! $is_admin
		) {
			$badge_filter_ids = array_filter( (array) $badge_filter_meta[1], 'go_badge_exists' );

			// checks to see if the filter array are in the the user's badge array
			$intersection = array_values( array_intersect( $user_badges, $badge_filter_ids ) );

			// stores an array of the badges that were not found in the user's badge array
			$badge_diff = array_values( array_diff( $badge_filter_ids, $intersection ) );
			if ( ! empty( $badge_filter_ids ) && ! empty( $badge_diff ) ) {
				$return_badge_list = true;

				$visitor_str = '';
				if ( ! $is_logged_in ) {
					$visitor_str = ', and you must be ' .
						'<a href="' . esc_url( $login_url ) . '">logged in</a> to obtain them';
				}

				// outputs all the badges that the user must obtain before beginning this task
				//echo "<p><span class="go_error_red">You need the following {strtolower( $badge_name )}s to begin this .ucwords( $task_name ):";
				echo sprintf(
					'<span class="go_error_red">' .
						'You need the following %s to begin this %s%s:' .
					'</span><br/>%s',
					strtolower( $badge_name ),
					ucwords( $task_name ),
					$visitor_str,
					go_badge_output_list( $badge_diff, $return_badge_list )
				);
				

				$task_is_locked = true;
					
			}
			
		}
		
		/**
		 * Lock Content Message
		 */
		
		if ($task_is_locked == true && ! $is_logged_in ){
			echo "<p class='go_error_red' style='clear: both; padding-top: 30px;'>You must be <a href=" . esc_url( $login_url ) . ">logged in</a> to view this ". ucwords( $task_name ) . ".</p>";
		}
		
	} // End if().


//Locks End
return $task_is_locked;
}