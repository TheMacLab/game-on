<?php
session_start();



/*
	This is the file that displays content in a post/page with a task.
	This file interprets and executes the shortcode in a post's body.
*/

// Debug code
function debug_task( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}

// Task Shortcode
function go_task_shortcode( $atts, $content = null ) {

	//enqueues go_tasks.js that is only needed on task pages
	//https://www.thewpcrowd.com/wordpress/enqueuing-scripts-only-when-widget-or-shortcode/
		wp_enqueue_script( 'go_tasks','','','', true );

	/* Variables
		//sets a whole bunch of variables that are needed to print the task	and task functions
	*/
		$atts = shortcode_atts( array(
			'id' => '', // ID defined in Shortcode
			'cats' => '', // Cats defined in Shortcode     
		), $atts);
		$id = $atts['id'];

		// abort if the post ID is invalid
		if ( ! $id ) {
			return;
		}

		global $wpdb;
		$task_name = strtolower( go_return_options( 'go_tasks_name' ) );
		$badge_name = go_return_options( 'go_badges_name' );

		// the current user's id
		$user_id = get_current_user_id();
		$page_id = get_the_ID();
		
		//get options
		$go_oembed_switch = get_option( 'go_oembed_switch' );
		$go_fitvids_switch = get_option( 'go_fitvids_switch' );
		$go_lightbox_switch = get_option( 'go_lightbox_switch' );
		$go_fitvids_maxwidth = get_option( 'go_fitvids_maxwidth' );

		// gets admin user object
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

		$is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
		$login_url = home_url( '/wp-login.php' );

	/*variables for locks
			// determines if the task is for users (logged-in users) eyes only
	*/
		$is_user_only = get_post_meta( $id, 'go_mta_user_only_content', true ) ? true : false;

		if ( $is_user_only && ! $is_logged_in ) {
			return '<span class="go_error_red">Sorry, you must be ' .
				'<a href="' . esc_url( $login_url ) . '">logged in</a> to view this.</span>';
		}
		// determines whether or not the task is filtered at all
		$is_filtered = false;
		// retrieves the date and time, if specified, after which non-admins can accept this task
		$start_filter = get_post_meta( $id, 'go_mta_start_filter', true );
		// gets an array of badge IDs to prevent users who don't have the badges from viewing the task
		$badge_filter_meta = get_post_meta( $id, 'go_mta_badge_filter', true );
		// obtains the chain order list for this task, if there is one
		$chain_order = get_post_meta( $id, 'go_mta_chain_order', true );

		if ( ! empty( $start_filter['checked'] ) || ! empty( $badge_filter_meta[0] ) || ! empty( $chain_order ) ) {
			$is_filtered = true;
		}
		// determines whether or not filters will affect visitors (users that aren't logged in)
		$filtered_content_hidden = false;
		$hfc_meta = get_post_meta( $id, 'go_mta_hide_filtered_content', true );
		if ( '' === $hfc_meta || 'true' === $hfc_meta ) {
			$filtered_content_hidden = true;
		}
		
		// prepares nonces for AJAX requests sent from this post
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
		$custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id
		$rewards = ( ! empty( $custom_fields['go_presets'][0] ) ? unserialize( $custom_fields['go_presets'][0] ) : null );
		$mastery_active = ( ! empty( $custom_fields['go_mta_three_stage_switch'][0] ) ? ! $custom_fields['go_mta_three_stage_switch'][0] : true ); // whether or not the mastery stage is active
		$repeat = ( ! empty( $custom_fields['go_mta_five_stage_switch'][0] ) ? $custom_fields['go_mta_five_stage_switch'][0] : '' ); // Whether or not you can repeat the task
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

		//set number of stages and repeat limit
		if ( $mastery_active ) {
	        $number_of_stages = 4;	
			if ( $repeat == 'on' ) {    // Checks if the task is repeatable and gets the repeat limit
				$repeat_amount = ( ! empty( $custom_fields['go_mta_repeat_amount'][0] ) ? $custom_fields['go_mta_repeat_amount'][0] : 0 );
				$number_of_stages = 5;
			}
		} else {
			$number_of_stages = 3;  
		}

		$points_array = ( ! empty( $rewards['points'] ) ? $rewards['points'] : array() );
		$points_str = implode( ' ', $points_array );
		$currency_array = ( ! empty( $rewards['currency'] ) ? $rewards['currency'] : array() );
		$currency_str = implode( ' ', $currency_array );
		$bonus_currency_array = ( ! empty( $rewards['bonus_currency'] ) ? $rewards['bonus_currency'] : array() );
		$bonus_currency_str = implode( ' ', $bonus_currency_array );
		$current_bonus_currency = go_return_bonus_currency( $user_id ); 
		$current_penalty = go_return_penalty( $user_id );
		$future_timer = false;
		//$future_timer = go_is_timer_expired ($custom_fields, $userid, $id, $wpdb);
		$update_percent = 1;
		//$update_percent = go_timer_percent($custom_fields, $userid, $id, $wpdb);
		// Array of future modifier switches, determines whether the calendar option or time after stage one option is chosen
		$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : null );


	/*Localize Task Script
		//All the variables are set.
	*/
		wp_localize_script(
			'go_tasks',
			'go_task_data',
			array(
				'go_taskabandon_nonce'	=>  $task_shortcode_nonces['go_task_abandon'],
				'url'	=> get_site_url(),
				'status'	=>  $status,
				'currency'	=>  $currency_array[0],
				'userID'	=>  $user_id,
				'ID'	=>  $id,
				'pointsFloor'	=>  floor( $points_array[0] * $update_percent ),
				'currencyFloor'	=>  floor( $currency_array[0] * $update_percent ),
				'bonusFloor'	=>  floor( $bonus_currency_array[0] * $update_percent ),
				'homeURL'	=>  home_url(),
				'admin_name'	=>  $admin_name,
				'go_unlock_stage'	=>  $task_shortcode_nonces['go_unlock_stage'],
				'points_str'	=>  $points_str,
				'test_e'	=>  ( ! empty( $test_e_returns ) ? $test_e_returns : ''),
				'test_a'	=>  ( ! empty( $test_a_returns ) ? $test_a_returns : ''),
				'test_c'	=>  ( ! empty( $test_c_returns ) ? $test_c_returns : ''),
				'test_m'	=>  ( ! empty( $test_m_returns ) ? $test_m_returns : ''),
				'go_test_point_update'	=>  $task_shortcode_nonces['go_test_point_update'],
				'currency_str'	=>  $currency_str,
				'bonus_currency_str'	=>  $bonus_currency_str,
				'page_id'	=>  $page_id,
				'date_update_percent'	=>  $date_update_percent,
				'go_task_change_stage'	=>  $task_shortcode_nonces['go_task_change_stage'],
				'task_count'	=>  ( ! empty( $task_count ) ? $task_count : 0 ),
				'points_array'	=>  $points_array ,
				'currency_array'	=>  $currency_array,
				'bonus_currency_array'	=>  $bonus_currency_array ,
				'date_update_percent'	=>  $date_update_percent,
				'next_post_id_in_chain'	=>  ( ! empty( $next_post_id_in_chain ) ? $next_post_id_in_chain : 0 ),
				'last_in_chain'	=>  ( ! empty( $last_in_chain ) ? 'true' : 'false' ),
				'number_of_stages'	=>  $number_of_stages,

			)
		);

	/*LOCKS
		// prevents users (both logged-in and logged-out) from accessing the task content, if they
		// do not meet the requirements
		// the task_locks function will set the output for the locks and set the task_is_locked variable to true if it is locked.
	*/	
		$task_is_locked = task_locks ($is_logged_in, $is_filtered, $filtered_content_hidden, $is_admin, $start_filter, $temp_id, $chain_order, $id, $login_url, $badge_filter_meta, $task_name, $badge_name, $custom_fields, $user_id  );
		//if it is locked, stop printing of the task.
		if ($task_is_locked == true){
			return null;
		}

	/*	Visitor Content
		// visitors (logged-out users) are presented with the full task content, if the task doesn't
		// have any active restrictions on it.
	*/
		if ( ! $is_logged_in ) {
			go_display_visitor_content( $id );
			return;
		}

	/*Create task if this is the first time encountered
	//add post/user row in go table if none exists
	*/
		$row_exists = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * 
					FROM {$go_table_name} 
					WHERE uid = %d and post_id = %d LIMIT 1",
					$user_id,
					$id
				)
			);
		
			
		if ( empty( $row_exists ) ) {
				$wpdb->insert(
					$go_table_name, 
					array(
						'uid' => $user_id, 
						'post_id' => $id, 
						'status' => 0
					)
				);
		}
		
	
	/*Timer
	//Timer
	
		$timer_set = go_timer( $custom_fields, $user_id, $id, $wpdb, $go_table_name, $task_name );

		if ($timer_set == true){
			echo "<div id='go_wrapper' data-fitvids='" . $go_fitvids_switch ."'  data-lightbox='" . $go_lightbox_switch . "' data-oembed='" .  $go_oembed_switch . "' data-maxwidth='" . $go_fitvids_maxwidth . "' >";
			return null;
		}

		if ($status == 0){
			go_add_post(
				$user_id,
				$id,
				1,
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


			$status = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT status 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
		}



	
	/*PnC Modifier
		PnC Modifier gets the modified percentage of for rewards.  This is then used to display the correct rewards below.  
		This function is also used to modify the points for rewarding PnC.
		It includes test fails, timer/date, super mod (honor and damage).
	*/
		//function needed

	
	/* Display Rewards before task content 
		//This is the list of rewards at the top of the task.	
	*/	
		//$future_timer = false;
		//$update_percent = 3;
		go_display_rewards( $user_id, $points_array, $currency_array, $bonus_currency_array, $update_percent, $number_of_stages, $future_timer );

	/*Create the Stage if it doesn't Exist
		//I don't think this is needed!!!!!
	*/
	/*
		//Create the stage if it doesn't already exist
		//this can be moved (or removed?) somewhere . . . but where . . . what does this do in the next if statement.  
		//Are there badges awarded on entering a stage?
		// Array of badge switch and badges associated with a stage
		// E.g. array( true, array( 263, 276 ) ) means that stage has badges (true) and the badge IDs are 263 and 276
		$stage_badges = array(
			get_post_meta( $id, 'go_mta_stage_one_badge', true ),
			get_post_meta( $id, 'go_mta_stage_two_badge', true ),
			get_post_meta( $id, 'go_mta_stage_three_badge', true ),
			get_post_meta( $id, 'go_mta_stage_four_badge', true ),
			get_post_meta( $id, 'go_mta_stage_five_badge', true ),
		);
		
		
		//Create the task in GO table if it is the first time encountered)
		if ($status == 0){
			//this is the first time encountered
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
			$status = 1;	
			

		//What are these . . . award badges on encounter?
			if ( $stage_badges[0][0] ) {
				foreach ( $stage_badges[0][1] as $badge_id ) {
					go_award_badge(
						array(
							'id'        => $badge_id,
							'repeat'    => false,
							'uid'       => $user_id
						)
					);
				}
			}
			
			//This is for the timer
			//go_record_stage_time( $page_id, 1 );
		}

	*/

	/*Stage Content
	 *This includes 
	 *messages
	 *checks for understanding
	 *localizing scripts 
	*/	
		
		echo "<div id='go_wrapper' data-fitvids='" . $go_fitvids_switch ."'  data-lightbox='" . $go_lightbox_switch . "' data-oembed='" .  $go_oembed_switch . "' data-maxwidth='" . $go_fitvids_maxwidth . "' >";
		//Print stage content	
		go_print_messages ( $status, $custom_fields, $go_oembed_switch );

		//Print Checks for Understanding for the last stage message printed
		//and buttons
		
		go_checks_for_understanding ( $wpdb, $status, $custom_fields, $go_table_name, $user_id, $id );
		
		//Print the bottom of the page
		
		go_task_render_chain_pagination( $id, $user_id );
		echo '</div></div> <div id="notification"></div>' . ( ( ! empty( $task_pods ) && ! empty( $pods_array ) ) ? "<br/><a href='{$pod_link}'>Return to Pod Page</a>" : "" );
					
		
		if ( get_post_type() == 'tasks' ) {
			comments_template();
			wp_list_comments();
		}
		
		
		
		// redeclare (also called "overloading" ) the variable $task_count to the value of the 'count' var on the database.
		$task_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT count 
				FROM {$go_table_name} 
				WHERE post_id = %d AND uid = %d",
				$id,
				$user_id
			)
		);	
			   
		// this is an edit link at the bottom. NOT NEEDED. Its in the admin bar.
		//edit_post_link( 'Edit ' . go_return_options( 'go_tasks_name' ), '<br/><p>', '</p>', $id );
}
add_shortcode( 'go_task','go_task_shortcode' );

//Prints the Content of the Tasks
function go_print_messages ( $status, $custom_fields, $go_oembed_switch ){
	//Print messages 	
	$i = 1;
	while ( $i <= $status) {
		go_print_1_message ( $custom_fields, $go_oembed_switch, $i );	
		$i++;
	}
}

//Gets the name of each stage
function go_short_name ( $stage_num ){
		if ($stage_num == 1){
			$stage_short_name = 'encounter';
		}else if ($stage_num == 2){
			$stage_short_name = 'accept';
		} else if ($stage_num == 3){
			$stage_short_name = 'completion';
		} else if ($stage_num == 4){
			$stage_short_name = 'mastery';
		} else if ($stage_num == 5){
			$stage_short_name = 'repeat';
		}
	return $stage_short_name;
}

//Gets the abbreviation of each stage
function go_stage_letter ( $stage_num ){
		if ($stage_num == 1){
			$stage_letter = 'encounter';
		}else if ($stage_num == 2){
			$stage_letter = 'accept';
		} else if ($stage_num == 3){
			$stage_letter = 'completion';
		} else if ($stage_num == 4){
			$stage_letter = 'mastery';
		} else if ($stage_num == 5){
			$stage_letter = 'repeat';
		}
	return $stage_letter;
}

//Prints a single stage content
function go_print_1_message ( $custom_fields, $go_oembed_switch, $i ){	
	if ($i > 0){
	//for ($counter = 1; $counter <= 5; $counter++){
		if ($i == 1){
			$stage_short_name = 'quick_desc';
		}else if ($i == 2){
			$stage_short_name = 'accept_message';
		} else if ($i == 3){
			$stage_short_name = 'complete_message';
		} else if ($i == 4){
			$stage_short_name = 'mastery_message';
		} else if ($i == 5){
			$stage_short_name = 'repeat_message';
		}
	}
	$message = ( ! empty( $custom_fields['go_mta_'.$stage_short_name][0] ) ? $custom_fields['go_mta_'.$stage_short_name][0] : '' ); // Completion Message
	//adds oembed content
	if ($go_oembed_switch === 'On'){
		if(isset($GLOBALS['wp_embed']))
		$message  = $GLOBALS['wp_embed']->autoembed($message );
	}
	echo "<div id='message_" . $i . "' class='go_stage_message'  style='display: none;'>".do_shortcode(wpautop( $message  ) )."</div>"; 	
}

//Prints Checks for understanding for the current stage
function go_checks_for_understanding ( $wpdb, $status, $custom_fields, $go_table_name, $user_id, $id ){
	echo "<div id='go_checks_and_buttons'><div id='checks'>";
		$stage_short_name = go_short_name ( $status );
		$stage_letter = go_stage_letter ( $status );
	
	//Upload Check for Understanding
			$stage_upload = ( ! empty( $custom_fields['go_mta_'.$stage_short_name.'_upload'][0] ) ? $custom_fields['go_mta_'.$stage_short_name.'_upload'][0] : false );
			//get if item is uploaded variable (null or 1)
			$db_task_stage_upload_var = $stage_letter . '_uploaded';
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
			if ( $stage_upload ) {
				echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
			}

	//Quiz Check for Understanding
			$test_stage_active = ( ! empty( $custom_fields['go_mta_test_'.$stage_short_name.'_lock'][0] ) ? $custom_fields['go_mta_test_'.$stage_short_name.'_lock'][0] : false );

			if ( $test_stage_active ) {
				$test_stage_array = go_task_get_test_meta( $stage_short_name, $id );
				$test_stage_returns = $test_stage_array[0];
				$test_stage_num = $test_stage_array[1];
				$test_stage_all_questions = $test_stage_array[2][0];
				$test_stage_all_types = $test_stage_array[2][1];
				$test_stage_all_answers = $test_stage_array[2][2];
				$test_stage_all_keys = $test_stage_array[2][3];
			}

			if ( $test_stage_active ) {
				
				if ( $test_stage_num > 1 ) {
					for ( $i = 0; $i < $test_stage_num; $i++ ) {
						if ( ! empty( $test_stage_all_types[ $i ] ) &&
							! empty( $test_stage_all_questions[ $i ] ) &&
							! empty( $test_stage_all_answers[ $i ] ) &&
							! empty( $test_stage_all_keys[ $i ] ) ) {
							echo do_shortcode( "[go_test type='".$test_stage_all_types[ $i ]."' question='".$test_stage_all_questions[ $i ]."' possible_answers='".$test_stage_all_answers[ $i ]."' key='".$test_stage_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_stage_num."']" );
						}
					}
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit' button_type='quiz' >Submit</button></div>";
				} elseif ( ! empty( $test_stage_all_types[0] ) &&
					! empty( $test_stage_all_questions[0] ) &&
					! empty( $test_stage_all_answers[0] ) &&
					! empty( $test_stage_all_keys[0] ) ) {
						echo do_shortcode( "[go_test type='".$test_stage_all_types[0]."' question='".$test_stage_all_questions[0]."' possible_answers='".$test_stage_all_answers[0]."' key='".$test_stage_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit button_type='quiz''>Submit</button></div>";
				}
				
			}
			
	//Password Check for Understanding		
			$stage_admin_lock = get_post_meta( $id, 'go_mta_'.$stage_short_name.'_admin_lock', true );
			if ( ! empty( $stage_admin_lock ) ) {
				$stage_is_locked = ( ! empty( $stage_admin_lock[0] ) ? true : false );
				if ( $stage_is_locked ) {
					$stage_pass_lock = ( ! empty( $stage_admin_lock[1] ) ? $stage_admin_lock[1] : '' );
				}
			}
			if ( $stage_is_locked && ! empty( $stage_pass_lock ) ) {
				echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
			} 
			
	//URL Check for Understanding
			$stage_url_is_locked = ( ! empty( $custom_fields['go_mta_'.$stage_short_name.'_url_key'][0] ) ? true : false );
			if ( $stage_url_is_locked === true ) {
				echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
			}
			
	//error placeholder
			echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
		
		
	//Buttons
		echo "<div id='go_buttons'>";
		//$status = $status + 1;
		echo "<button id='go_button' status='{$status}' onclick='task_stage_change( this );' button_type='continue'";	
		if ( $stage_is_locked && empty( $stage_pass_lock ) ) {
			echo "admin_lock='true'";
		}
		echo ">Continue</button> ";
		if ($status == 1){
			echo "<button id='go_abandon_task' onclick='go_task_abandon();this.disabled = true;' button_type='continue'>Abandon</button>";
		}
		else{
			echo "<button id='go_back_button' onclick='task_stage_change( this );' undo='true' button_type='undo'>Undo</button>";
		}
		echo "</div></div>";
}

//FIX THIS CODE--IT"S WET
function go_display_visitor_content( $id ) {
	$custom_fields = get_post_custom( $id );
	
    $encounter_message = ( ! empty( $custom_fields['go_mta_quick_desc'][0] ) ? $custom_fields['go_mta_quick_desc'][0] : '' );
    //adds oembed content
    	if ($go_oembed_switch === 'On'){
    		if(isset($GLOBALS['wp_embed']))
    		$encounter_message = $GLOBALS['wp_embed']->autoembed($encounter_message);
    };
    
    $accept_message = ( ! empty( $custom_fields['go_mta_accept_message'][0] ) ? $custom_fields['go_mta_accept_message'][0] : '' );
    //adds oembed content
    if ($go_oembed_switch === 'On'){
		if(isset($GLOBALS['wp_embed']))
		$accept_message = $GLOBALS['wp_embed']->autoembed($accept_message);
	};
	
    $complete_message = ( ! empty( $custom_fields['go_mta_complete_message'][0] ) ? $custom_fields['go_mta_complete_message'][0] : '' );
    //adds oembed content
    	if ($go_oembed_switch === 'On'){
    		if(isset($GLOBALS['wp_embed']))
    		$complete_message = $GLOBALS['wp_embed']->autoembed($complete_message);
    	};
    	
	$mastery_active = ( ! empty( $custom_fields['go_mta_three_stage_switch'][0] ) ? ! $custom_fields['go_mta_three_stage_switch'][0] : true );
	if ( $mastery_active ) {
		$mastery_privacy = ( ! empty( $custom_fields['go_mta_mastery_privacy'][0] ) ? ! $custom_fields['go_mta_mastery_privacy'][0] : true );
		if ( $mastery_privacy ) {
			$mastery_message = ( ! empty( $custom_fields['go_mta_mastery_message'][0] ) ? $custom_fields['go_mta_mastery_message'][0] : '' );
            //adds oembed content
    		if ($go_oembed_switch === 'On'){
				if(isset($GLOBALS['wp_embed']))
            	$mastery_message = $GLOBALS['wp_embed']->autoembed($mastery_message);
            };
            
            $repeat_active = ( ! empty( $custom_fields['go_mta_five_stage_switch'][0] ) ? $custom_fields['go_mta_five_stage_switch'][0] : false );
			if ( $repeat_active && $mastery_privacy ) {
				$repeat_privacy = ( ! empty( $custom_fields['go_mta_repeat_privacy'][0] ) ? ! $custom_fields['go_mta_repeat_privacy'][0] : true );
				if ( $repeat_privacy ) {
					
                    $repeat_message = ( ! empty( $custom_fields['go_mta_repeat_message'][0] ) ? $custom_fields['go_mta_repeat_message'][0] : '' );
                    //adds oembed content
    				if ($go_oembed_switch === 'On'){
                    	if(isset($GLOBALS['wp_embed']))
                    	$repeat_message = $GLOBALS['wp_embed']->autoembed($repeat_message);
                    };
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

	// displays the chain pagination list so that visitors can still navigate chains easily
	go_task_render_chain_pagination( $id );
}

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

//WET CODE
//DON"T KNOW WHAT IT DOES
//MOVE TO POST UPDATE
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

//DON"T KNOW WHAT IT DOES
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

//What does this do? Something to do with the test.
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

//checks if the task is done
//Used when awarding badges on the last stage of the last quest in a chain
function go_task_done_check( $post_id , $is_current_task = false, $undo = false) {
	global $wpdb;
	
	$temp_status_required    = 4;
	$temp_three_stage_active = (boolean) get_post_meta(
		$post_id,
		'go_mta_three_stage_switch',
		true
	);
	if ($temp_three_stage_active == true) {
		$temp_status_required    = 3;
	}
	$temp_five_stage_active  = (boolean) get_post_meta(
		$post_id,
		'go_mta_five_stage_switch',
		true
	);
	if ($temp_five_stage_active == true) {
		$temp_status_required    = 5;
	}		
	//echo '<script type="text/javascript">alert("# needed '.$temp_status_required.'");</script>';
	//gets the current stage
	$temp_status = go_task_get_status( $post_id );	
	//echo '<script type="text/javascript">alert("# done '.$temp_status.'");</script>';
	//echo '<script type="text/javascript">alert("'.$undo.'");</script>';
	if (($is_current_task == true) && ($undo != true) ){
		++$temp_status;
		//echo '<script type="text/javascript">alert("'.$temp_status.' adjusted");</script>';
		//echo '<script type="text/javascript">alert("current task");</script>';
	}
	if ( $temp_status === $temp_status_required) {
		$result = true;
		//echo '<script type="text/javascript">alert("done");</script>';
	}
	else {
		//echo '<script type="text/javascript">alert("not done");</script>';
		$result = false;
	}
	return $result;
}

/**Award an achievement if this is the last stage in a pod or chain.********
*/
function go_badges_task_chains ($post_id, $user_id, $is_admin = false, $undo = false ) {
	$chain_order = get_post_meta( $post_id, 'go_mta_chain_order', true );
	///set variables for each chain the task is in
	foreach ( $chain_order as $chain_tt_id => $order ) {
		$pos = array_search( $post_id, $order );
		$the_chain = get_term_by( 'term_taxonomy_id', $chain_tt_id );
		$chain_title = ucwords( $the_chain->name );
		$chain_id = ( $the_chain->term_id );
		$chain_pod = get_term_meta($chain_tt_id, 'pod_toggle', true);
		$chain_badge = get_term_meta($chain_tt_id, 'pod_achievement', true);
		///////////////////////////////////////////////
		//Award badge if button is continue and is last in chain
		if ( ($undo != true ) && (! empty( $chain_order ))) {
			//////////////////////////////////
			///check if this is the last stage in the current task -->
			//////////////////////////////////
			if ( go_task_done_check($post_id, true, false ) == true ){
				$tasks_done_count = 1;
				//if this is part of a pod, then count number of tasks complete in pod
				if ($chain_pod == true){
					//get number of tasks to complete pod
					$pod_done_num = get_term_meta($chain_tt_id, 'pod_done_num', true);
					$chain_items = array_shift($chain_order);
					//echo '<script type="text/javascript">alert("'.$chain_items.'");</script>';
					foreach ( $chain_items as $chain_item ) {
						if ( go_task_done_check($chain_item, false, false) == true ) {
							++$tasks_done_count;
						}
					}
					//if the number complete is equal to the number to recieve then give achievement
					if ( $tasks_done_count == $pod_done_num ){
						if ( ! empty ($chain_badge)) {
							go_award_badge(
								array(
									'id'        => $chain_badge,
									'repeat'    => false,
									'uid'       => $user_id
								)
							);
						}
					}
				}
				//else the task is not a pod 
				else {	
					//if the task is the last item then give achievement assigned to chain
					$last_in_chain = go_task_chain_is_final_task( $post_id, $chain_tt_id );					
					if ($last_in_chain == true){
						if ( ! empty ($chain_badge)) {
							go_award_badge(
								array(
									'id'        => $chain_badge,
									'repeat'    => false,
									'uid'       => $user_id
								)
							);
						}
					}
				}
			}
		}	
	///////////////////////////////////////////////
	//REMOVE CHAIN BADGE IF BUTTON CLICKED IS UNDO
	if ( ($undo == true ) && (! empty( $chain_order ))) {
		//////////////////////////////////
		///check if this is the last stage in the current task -->
		//////////////////////////////////
		if ( go_task_done_check($post_id, true, $undo ) == true ){
			//echo '<script type="text/javascript">alert("last stage undo");</script>';
			$tasks_done_count = 0;
				//if this is part of a pod, then count number of tasks complete in pod
				if ($chain_pod == true){
					//get number of tasks to complete pod
					$pod_done_num = get_term_meta($chain_tt_id, 'pod_done_num', true);
					$chain_items = array_shift($chain_order);
					foreach ( $chain_items as $chain_item ) {
						if ( go_task_done_check($chain_item, false, false) == true ) {
							++$tasks_done_count;
						}
					}
					//echo '<script type="text/javascript">alert("'.$tasks_done_count.'");</script>';
					//echo '<script type="text/javascript">alert("'.$pod_done_num.'");</script>';
					//if the number complete is equal to the number to recieve then give achievement
					if ( $tasks_done_count == $pod_done_num ){
						if ( ! empty ($chain_badge)) {
							go_remove_badge( $user_id, $chain_badge );
						}
					}
				}
				//else the task is not a pod 
				else {	
					//if the task is the last item then give achievement assigned to chain
					$last_in_chain = go_task_chain_is_final_task( $post_id, $chain_tt_id );					
					if ($last_in_chain == true){
						if ( ! empty ($chain_badge)) {
							go_remove_badge( $user_id, $chain_badge );
						}
					}
				}
			}
		}
	}
}

/*When button is pressed, this Function gets the variables from the javascript, then returns content to the javascript and makes db changes as needed
//
 */
function go_task_change_stage() {
	global $wpdb;

	//Types of buttons
	//Abandon
	//Start Timer
	//Continue
	//Undo
	//Repeat
	//Undo Repeat --is this defferent than just und


	/* Variables
	*/

	//CHECK THIS
	 //These globals are needed for the oembed to function--I think . . .
	 //Delete? Check what these do!
    global $post;
    $post = get_post($post_id);
    setup_postdata( $post );
	$go_oembed_switch = get_option( 'go_oembed_switch' );

	/* variables 
	*/

		$user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function
		$is_admin = go_user_is_admin( $user_id );
		// post id posted from ajax function (untrusted)
		$post_id = ( ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0 );
		$button_type 			= ( ! empty( $_POST['button_type'] ) ? $_POST['button_type'] : 0 );
		$page_id              	= ( ! empty( $_POST['page_id'] )               ? (int) $_POST['page_id'] : 0 ); // Page id posted from ajax function
		$admin_name       		= ( ! empty( $_POST['admin_name'] )            ? (string) $_POST['admin_name'] : '' );
		$pass                 	= ( ! empty( $_POST['pass'] )                  ? (string) $_POST['pass'] : '' ); // Contains the user-entered admin password
		$url                   	= ( ! empty( $_POST['url'] )                   ? (string) $_POST['url'] : '' ); // Contains user-entered url
		$points_array          	= ( ! empty( $_POST['points'] )                ? (array) $_POST['points'] : array() ); // Serialized array of points rewarded for each stage
		$currency_array        	= ( ! empty( $_POST['currency'] )              ? (array) $_POST['currency'] : array() ); // Serialized array of currency rewarded for each stage
		$bonus_currency_array  	= ( ! empty( $_POST['bonus_currency'] )        ? (array) $_POST['bonus_currency'] : array() ); // Serialized array of bonus currency awarded for each stage
		$date_update_percent   	= ( ! empty( $_POST['date_update_percent'] )   ? (double) $_POST['date_update_percent'] : 0.0 ); // Float which is used to modify values saved to database
		$next_post_id_in_chain 	= ( ! empty( $_POST['next_post_id_in_chain'] ) ? (int) $_POST['next_post_id_in_chain'] : 0 ); // Integer which is used to display next task in a quest chain
		$last_in_chain         	= ( ! empty( $_POST['last_in_chain'] )         ? go_is_true_str( $_POST['last_in_chain'] ) : false ); // Boolean which determines if the current quest is last in chain
		$number_of_stages      	= ( ! empty( $_POST['number_of_stages'] )      ? (int) $_POST['number_of_stages'] : 0 ); // Integer with number of stages in the task
		$task_count 			= go_task_get_repeat_count( $post_id, $user_id );
		$custom_fields 			= get_post_custom( $post_id ); // Just gathering some data about this task with its post id
		$mastery_active			= ( ! empty( $custom_fields['go_mta_three_stage_switch'][0] ) ? ! $custom_fields['go_mta_three_stage_switch'][0] : true ); // whether or not the mastery stage is active
		//Get stage short letter and name
		$stage_short_name = go_short_name ( $status );
		$stage_letter = go_stage_letter ( $status );
		$go_table_name = "{$wpdb->prefix}go";

	/* Security 
	*/
		// gets the task's post object to validate that it exists, user requests for non-existent tasks
		// should be stopped and the user redirected to the home page
		$post_obj = get_post( $post_id );
		if ( null === $post_obj || ( 'publish' !== $post_obj->post_status && ! $is_admin ) || ( 'trash' === $post_obj->post_status && $is_admin )) {
			echo json_encode(
				array(
					'json_status' => 302,
					'html' => '',
					'rewards' => array(),
					'location' => home_url(),
				)
			);
			die();
		}
		check_ajax_referer( 'go_task_change_stage_' . $post_id . '_' . $user_id );

		// set the user's current progress on this task as db_status
		$db_status = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT status 
				FROM {$go_table_name} 
				WHERE uid = %d AND post_id = %d",
				$user_id,
				$post_id
			)
		);

		$status        = ( ! empty( $_POST['status'] ) ? (int) $_POST['status'] : 0 ); // Task's status posted from ajax function
		//are both of these needed?

		$repeat_button = ( ! empty( $_POST['repeat'] ) ? go_is_true_str( $_POST['repeat'] ) : false ); // Boolean which determines if the task is repeatable or not (True or False)
		$repeat        = get_post_meta( $post_id, 'go_mta_five_stage_switch', true ); // Whether or not you can repeat the task
		$undo          = ( ! empty( $_POST['undo'] )   ? go_is_true_str( $_POST['undo'] ) : false ); // Boolean which determines if the button clicked is an undo button or not (True or False)
		$timer_start          = ( ! empty( $_POST['timer_start'] )   ? go_is_true_str( $_POST['timer_start'] ) : false ); // Boolean which determines if the button clicked is timer start

		$is_progressing = false;
		$is_degressing = false;
		if ( ! $undo && (($db_status === $status && ! $repeat_button) || ( $db_status === $status && $repeat_button && 'on' === $repeat ))) {
			$is_progressing = true;
		} else if ( $undo && ( $db_status  === $status && ! $repeat_button ) || ( $db_status === $status && $repeat_button )) {
			$is_degressing = true;
		}

		$encountered = true;
		if ( 0 === $db_status ) {
			$encountered = false;
		}

		// checks if the current post has a permalink
		$task_permalink = get_permalink( $post_id );

		//CHECK THIS
		/*TURN THIS BACK ON__DEBUG ONLY
			// users should be redirected to the current task when:
			// a. The task exists (has a permalink)
			//
			// AND one of the following is true:
			// b. The user is neither progressing or degressing (pressing the undo button)
			// OR
			// c. The user has not encountered this task yet
			if ( $task_permalink && (( ! $is_progressing && ! $is_degressing ) || ! $encountered || ! empty( $chain_links ))) {
				echo json_encode(
					array(
						'json_status' => 302,
						'html' => '',
						'rewards' => array(),
						'location' => $task_permalink,
					)
				);
				die();
			}

			// users should be redirected to the home page when:
			// a. The task doesn't exist (has no permalink)
			if ( ! $task_permalink ) {
				echo json_encode(
					array(
						'json_status' => 302,
						'html' => '',
						'rewards' => array(),
						'location' => home_url(),
					)
				);
				die();
			}
		*/

	/* Checks for Understanding
	//Verify password and something to do with uploads 
	*/

		//CHECK THIS, Can it be moved higher?	
		//If password field is on, Check if password is correct 
		//Get password
		$stage_admin_lock = get_post_meta( $post_id, 'go_mta_'.$stage_short_name.'_admin_lock', true );
		if ( ! empty( $stage_admin_lock ) ) {
			$stage_is_locked = ( ! empty( $stage_admin_lock[0] ) ? true : false );
			if ( $stage_is_locked ) {
				$stage_pass_lock = ( ! empty( $stage_admin_lock[1] ) ? $stage_admin_lock[1] : '' );
			}
		}

		//If password is set and the wrong password was entered then die, else continue
			if ( ! empty( $stage_pass_lock ) && $pass !== $stage_pass_lock ) {
				echo json_encode(
					array(
						'json_status' => 'fail',
						'html' => '',
						'rewards' => array(
							'gold' => 0,
						),
						'location' => '',
					)
				);
				die();
			}

		//Upload check stuff
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
		//CHECK THIS
		//CHECK TO SEE IF SOMETHING WAS UPLOADED BY LOOKING THE THE VARIABLE, ELSE SET TO "0" WHICH IS NOTHING UPLOADED
		//WHEN IS THIS SET TO UPLOADED?  //WHAT FUNCTION DOES THE BUTTON CALL?
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
		//Upload

		/* this is for BONUS	
		//if a password was entered, make sure this isn't a bonus task, and if it is set the repeat password lock
		if ( ! empty( $pass ) || '0' === $pass ) {
			
			$db_pass_lock = '';
			if ( 4 === $status && $repeat_button && 'on' === $repeat ) {
				if ( $task_count > 0 && ! empty( $r_pass_lock ) ) {
					$db_pass_lock = $r_pass_lock;
				} 
			} 
			else  {
				$db_pass_lock = $stage_pass_lock;
			} 
		}
		*/

		//CHECK THIS, Can it be moved higher?	
		//If password is set and the wrong password was entered then die, else continue
			if ( ! empty( $stage_pass_lock ) && $pass !== $stage_pass_lock ) {
				echo json_encode(
					array(
						'json_status' => 'fail',
						'html' => '',
						'rewards' => array(
							'gold' => 0,
						),
						'location' => '',
					)
				);
				die();
			}
		
	/*
	//Awards
	*/
		//Pop up currency awards
		// catch everything output here as is, and stuff it in a buffer to be dumped into a JSON response
		// at the end of the function
		ob_start();
		
		go_badges_task_chains ($post_id, $user_id, $is_admin, $undo);

		// Array of badge switch and badges associated with a stage
		//these are for the pop up
		// E.g. array( true, array( 263, 276 ) ) means that stage has badges (true) and the badge IDs are 263 and 276
		$stage_badges = array(
			( ! empty( $custom_fields['go_mta_stage_one_badge'][0] )   ? unserialize( $custom_fields['go_mta_stage_one_badge'][0] ) : null ),
			( ! empty( $custom_fields['go_mta_stage_two_badge'][0] )   ? unserialize( $custom_fields['go_mta_stage_two_badge'][0] ) : null ),
			( ! empty( $custom_fields['go_mta_stage_three_badge'][0] ) ? unserialize( $custom_fields['go_mta_stage_three_badge'][0] ) : null ),
			( ! empty( $custom_fields['go_mta_stage_four_badge'][0] )  ? unserialize( $custom_fields['go_mta_stage_four_badge'][0] ) : null ),
			( ! empty( $custom_fields['go_mta_stage_five_badge'][0] )  ? unserialize( $custom_fields['go_mta_stage_five_badge'][0] ) : null ),
		);

		//TIMER: stuff added until timer and test mods gets fixed	
		//these might be needed (or something to replace them).
		//
		$update_percent = 1;
		$future_timer = false;
		$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : null ); 
		//determine which future date modifier is on, if any
		//END stuff added until timer and test mods gets fixed	

		//$complete_stage = ( $undo ? $status - 1 : $status );

		//Why gold and not others?
		$gold_reward = 0;

		// if the button pressed IS the repeat button...
		if ( $repeat_button ) {
			if ( $undo ) {
				if ( $task_count > 0 ) {
					$gold_reward = -floor( ( $update_percent * $currency_array[ $status ] ) );
					go_add_post(
						$user_id, $post_id, $status,
						-floor( ( $update_percent * $points_array[ $status ] ) ),
						$gold_reward,
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
					$gold_reward = -floor( ( $update_percent * $currency_array[ $status - 1 ] ) );
					go_add_post(
						$user_id, $post_id, ( $status - 1 ),
						-floor( ( $update_percent * $points_array[ $status - 1 ] ) ),
						$gold_reward,
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

					if ( $stage_badges[ $status - 1 ][0] ) {
						foreach ( $stage_badges[ $status - 1 ][1] as $badge_id ) {
							go_remove_badge( $user_id, $badge_id );
						}
					}
				}
			} else {
				$gold_reward = floor( ( $update_percent * $currency_array[ $status ] ) );
				// if repeat is on and undo is not hit...
				go_add_post(
					$user_id, $post_id, $status,
					floor( ( $update_percent * $points_array[ $status ] ) ),
					$gold_reward,
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
		} 

		// if the button pressed is NOT the repeat button...
		else {
			$db_status = (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT status 
					FROM {$go_table_name} 
					WHERE uid = %d AND post_id = %d",
					$user_id,
					$post_id
				)
			);

			//if this is the undo button on stage 5
			if ( $undo ) {	
				
				if ( $task_count > 0 ) {
					$task_count_decrease = -1;
					}
				else {
					$task_count_decrease = 0;
				}			
					$gold_reward = -floor( ( $update_percent * $currency_array[ $status  ] ) );
					go_add_post(
						$user_id, $post_id, ($status - 1 ),
						-floor( ( $update_percent * $points_array[ $status ] ) ),
						$gold_reward,
						-floor( ( $update_percent * $bonus_currency_array[ $status ] ) ),
						null, $page_id, $repeat_button, $task_count_decrease,
						$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
						$e_passed, $a_passed, $c_passed, $m_passed
					);

					if ( $stage_badges[ $db_status ][0] ) {
						foreach ( $stage_badges[ $db_status - 1 ][1] as $badge_id ) {
							go_remove_badge( $user_id, $badge_id );
						}
					}
				}
			//else this is the continue button
			else {	
				$update_time = ( $status == 1 ) ? true : false;		
				$gold_reward = floor( ( $update_percent * $currency_array[ $status - 1 ] ) );
				go_add_post(
					$user_id, $post_id, ($status + 1 ),
					floor( ( $update_percent * $points_array[ $status ] ) ),
					$gold_reward,
					floor( ( $update_percent * $bonus_currency_array[ $status ] ) ),
					null, $page_id, $repeat_button, 0,
					$e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count,
					$e_passed, $a_passed, $c_passed, $m_passed, $url, $update_time
				);

				if ( $stage_badges[ $status - 1 ][0] ) {
					foreach ( $stage_badges[ $status - 1 ][1] as $badge_id ) {
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

		
		
		// redefine the status and task_count (repeat count) because they have been updated as soon as the above go_add_post() calls are made.
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

		/*
		THIS STUFF HAS SOMETHING TO DO WITH THE TIMER	
		if ( ! $undo ) {
			if ( $task_count == 1 ) {
				go_record_stage_time( $post_id, 5 );    
			} else {
				go_record_stage_time( $post_id, $status );
			}   
		}
		*/

		$go_notification = ob_get_contents();
		ob_end_clean();

	/*Button Types
	*/
		//If button press was start timer
		if ($button_type == 'timer'){
			//check if there is already a start time
			$start_time = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT starttime 
					FROM {$go_table_name} 
					WHERE post_id = %d AND uid = %d",
					$id,
					$user_id
				)
			);
			//if this task is being started for the first time
			if ( empty($start_time) ){
				// add a start time to the database
				$time = date( 'Y-m-d G:i:s', current_time( 'timestamp', 0 ) );
				$wpdb->update(
					$go_table_name,
					array(
						'starttime' => $time
					), 
					array(
						'uid' => $user_id, 
						'post_id' => $post_id
					)
				);
			}

			
			// add a start time to the database
			$wpdb->update(
					$table_name_go,
					array(
						'points' => $points + ( $old_points->points ),
						'currency' => $currency + ( $old_points->currency ),
						'bonus_currency' => $bonus_currency + ( $old_points->bonus_currency ),
						'minutes' => $minutes + ( $old_points->minutes ),
						'page_id' => $page_id,
						'count' => ( ( $old_points->count ) + $qty ),
						'reason' => esc_html( $reason )
					), 
					array(
						'uid' => $user_id, 
						'post_id' => $post_id
					)
				);
		
		}

	
	/*
	//Print Content for next stage
	*/
		ob_start();

		//if button_type is timer get the time left for the timer
		if ($button_type == 'timer'){
			$time_left = go_time_left ($custom_fields, $user_id, $id, $wpdb, $go_table_name );
		}

		if ( ! $undo ) {
		go_print_1_message ( $custom_fields, $go_oembed_switch, $status );	
		go_checks_for_understanding ( $wpdb, $status , $custom_fields, $go_table_name, $user_id, $post_id );
		} else{
		go_checks_for_understanding ( $wpdb, $status , $custom_fields, $go_table_name, $user_id, $post_id );
		}
		echo "stugg";
		// stores the contents of the buffer and then clears it
		$buffer = ob_get_contents();
		ob_end_clean();

		// constructs the JSON response
		echo json_encode(
			array(
				'json_status' => 'success',
				'html' => $buffer,
				'notification' => $go_notification,
				'undo' => $undo,
				'button_type' => $button_type,
				'time_left' => 1000,
				'rewards' => array(
					'gold' => $gold_reward,
				)
			)
		);
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
function go_task_render_chain_pagination ( $task_id, $user_id = null ) {
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

	//if ( go_user_is_admin( $user_id ) ) {
	//	return;
	//}

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
		$chain_pod = get_term_meta($chain_tt_id, 'pod_toggle', true);
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
				if ( get_post_status ( $_id ) == 'publish' ) { 
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

				echo '</div></div>';
			}
		}
	}
}
?>
