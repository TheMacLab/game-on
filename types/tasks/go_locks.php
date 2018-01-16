<?php 
function debug_locks( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

function task_locks ($is_logged_in, $is_filtered, $filtered_content_hidden, $is_admin, $start_filter, $temp_id, $chain_order, $id, $login_url, $badge_filter_meta ){



/* 
LOCKS START
*/

	// prevents users (both logged-in and logged-out) from accessing the task content, if they
	// do not meet the requirements
	if ( $is_logged_in || ( ! $is_logged_in && $is_filtered && $filtered_content_hidden ) ) {

		/**
		 * Start Filter
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
				echo "<span class='go_error_red'>Will be available at {$time_string}.</span>";
				return true;
			}
		}

		/**
		 * Task Chain Filter
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
					'<span class="go_error_red">You must finish</span>' .
					' %s ' .
					'<span class="go_error_red">to continue this %s.%s</span>',
					$link_str,
					ucwords( $task_name ),
					$visitor_str
				);

				return true;
			}
		} // End if().

		/**
		 * Badge Filter
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
				echo sprintf(
					'<span class="go_error_red">' .
						'You need the following %s(s) to begin this %s%s:' .
					'</span><br/>%s',
					strtolower( $badge_name ),
					ucwords( $task_name ),
					$visitor_str,
					go_badge_output_list( $badge_diff, $return_badge_list )
				);
				return true;
					
			}
			
		}
	} // End if().

//Locks End

}