<?php
session_start();

/**
 * TASK SHORTCODE
 * This is the file that displays content in a post/page with a task.
 * This file interprets and executes the shortcode in a post's body.
 * @param $atts
 * @param null $content
 */
function go_task_shortcode($atts, $content = null ) {
    global $wpdb;

    /**
     * Get Post ID from shortcode
     */
	$atts = shortcode_atts( array(
		'id' => '', // ID defined in Shortcode
	), $atts);
	$post_id = $atts['id'];

	// abort if the post ID is invalid
	if ( ! $post_id ) {
		return;
	}

    /**
     * Enqueue go_tasks.js that is only needed on task pages
	 * https://www.thewpcrowd.com/wordpress/enqueuing-scripts-only-when-widget-or-shortcode/
     */
    wp_enqueue_script( 'go_tasks','','','', true );

    /**
     * Variables
     */
    // the current user's id
    $user_id = get_current_user_id();
    //$is_logged_in = is_user_member_of_blog( $user_id );
    $is_logged_in = ! empty( $user_id ) && is_user_member_of_blog( $user_id ) ? true : false;
    //$is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    $is_unlocked_type = go_master_unlocked($user_id, $post_id);
    if ($is_unlocked_type == 'password' || $is_unlocked_type == 'master password') {
        $is_unlocked = true;
    }
    else { $is_unlocked = false;}
	//Get all the custom fields
	$custom_fields = get_post_custom( $post_id ); // Just gathering some data about this task with its post id

	/**
	 * Get options needed for task display
	 */
	$task_name = strtolower( get_option( 'options_go_tasks_name_singular' ) );
    $uc_task_name = ucwords($task_name);
	$badge_name = get_option( 'options_go_naming_other_badges' );
	$go_lightbox_switch = get_option( 'options_go_video_lightbox' );
	$go_video_unit = get_option ('options_go_video_width_unit');
	if ($go_video_unit == 'px'){
		$go_fitvids_maxwidth = get_option('options_go_video_width_pixels')."px";
	}
	if ($go_video_unit == '%'){
		$go_fitvids_maxwidth = get_option('options_go_video_width_percent')."%";
	}

	// gets admin user object
	$go_admin_email = get_option( 'options_go_email' );
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

    $admin_view = ($is_admin ?  get_user_meta($user_id, 'go_admin_view', true) : null);

    $status = go_get_status($post_id, $user_id);

    //ADD BACK IN, BUT CHECK TO SEE WHAT YOU NEED
    /**
     * Localize Task Script
     * All the variables are set.
     */
    /**
     *prepares nonces for AJAX requests sent from this post
     */
    $task_shortcode_nonces = array(
        //'go_task_abandon' => wp_create_nonce( 'go_task_abandon_' . $post_id . '_' . $user_id ),
        'go_unlock_stage' => wp_create_nonce( 'go_unlock_stage_' . $post_id . '_' . $user_id ),
        //'go_test_point_update' => wp_create_nonce( 'go_test_point_update_' . $post_id . '_' . $user_id ),
        'go_task_change_stage' => wp_create_nonce( 'go_task_change_stage_' . $post_id . '_' . $user_id ),
    );

    $redirect_url = get_option('options_go_landing_page_on_login', '');
    $redirect_url = (site_url() . '/' . $redirect_url);

    wp_localize_script(
        'go_tasks',
        'go_task_data',
        array(
            //'go_taskabandon_nonce'	=>  $task_shortcode_nonces['go_task_abandon'],
            'url'	=> get_site_url(),
            'status'	=>  $status,
            'userID'	=>  $user_id,
            'ID'	=>  $post_id,
            'homeURL'	=>  home_url(),
            'redirectURL'	=> $redirect_url,
            'admin_name'	=>  $admin_name,
            'go_unlock_stage'	=>  $task_shortcode_nonces['go_unlock_stage'],
            //'go_test_point_update'	=>  $task_shortcode_nonces['go_test_point_update'],
            'go_task_change_stage'	=>  $task_shortcode_nonces['go_task_change_stage'],
            'task_count'	=>  ( ! empty( $task_count ) ? $task_count : 0 ),
            'next_post_id_in_chain'	=>  ( ! empty( $next_post_id_in_chain ) ? $next_post_id_in_chain : 0 ),
            'last_in_chain'	=>  ( ! empty( $last_in_chain ) ? 'true' : 'false' ),
        )
    );

    /**
     * Start wrapper
     */
    //The wrapper for the content
    echo "<div id='go_wrapper' data-lightbox='{$go_lightbox_switch}' data-maxwidth='{$go_fitvids_maxwidth}' >";

    /**
     * GUEST ACCESS
     * Determine if guests can access this content
     * then calls function to print guest content
     */
	if ($is_logged_in == false) {
        go_display_visitor_content( $custom_fields, $post_id, $task_name, $badge_name, $uc_task_name);
        return null;
	}

    /**
     * Admin Views & Locks
     */
    $admin_flag = go_admin_content($post_id, $is_admin, $admin_view, $custom_fields, $is_logged_in, $task_name, $status, $user_id, $post_id, $badge_name);

    if ($admin_flag == 'stop') {
        return null;
    }

    /**
     * LOCKS
     */
    if (!$is_unlocked) {
        if (!$is_admin || $admin_flag == 'locks') {
            $task_is_locked = go_display_locks($post_id, $user_id, $is_admin, $task_name, $badge_name, $custom_fields, $is_logged_in, $uc_task_name);
            if ($task_is_locked) {
                //Print the bottom of the page
                go_task_render_chain_pagination( $post_id, $custom_fields );
                return null;
            }
        }
    }
    else if ($is_unlocked){
    	if ($is_unlocked_type === 'master password'){
           echo "<div class='go_checks_and_buttons'><i class='fa fa-unlock fa-2x'></i> Unlocked by the master password.</div>";
		}
		else if ($is_unlocked_type === 'password'){
            echo "<div class='go_checks_and_buttons'><i class='fa fa-unlock fa-2x'></i> Unlocked by the $task_name password.</div>";
		}
	}

    /**
     * Due date mods
     */
    go_due_date_mods ($custom_fields, $is_logged_in, $task_name );


    /**
     * Encounter
     * if this is the first time encountering this task, then create a row in the task database.
     */
    if ($status === null ){
        $status = -1;
        //just a double check that the row doesn't already exist
        $row_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID 
					FROM {$go_task_table_name} 
					WHERE uid = %d and post_id = %d LIMIT 1",
                $user_id,
                $post_id
            )
        );
        //create the row
        $time = current_time( 'mysql');
        if ( $row_exists == null ) {
            $wpdb->insert($go_task_table_name, array('uid' => $user_id, 'post_id' => $post_id, 'status' => 0, 'last_time' => $time, 'xp' => 0, 'gold' => 0, 'health' => 0, 'c4' => 0,));
        }
    }
    /**
     * Display Rewards before task content
     * This is the list of rewards at the top of the task.
     */
    go_display_rewards( $custom_fields, $user_id, $post_id, $task_name );

    /**
     * Timer
     */
    $locks_status = go_display_timer ($custom_fields, $is_logged_in, $user_id, $post_id, $task_name );
    if ($locks_status){

        echo "</div>";
        go_task_render_chain_pagination( $post_id, $custom_fields );
        return null;
    }

    /**
     * Entry reward
	 * Note: If the timer is on, the reward entry is given when the timer is started.
	 *
     */
    if ($status === -1){
    	go_update_stage_table($user_id, $post_id, $custom_fields, -1, null, true, 'entry_reward', null, null, null);
    	$status = 0;
	}




    /**
     * MAIN CONTENT
     */


		//Print stage content
		//Including stages, checks for understanding and buttons
		go_print_messages ( $status, $custom_fields, $user_id, $post_id);

		echo "</div>";

	//echo "</div></div>";
		//Print the bottom of the page
		go_task_render_chain_pagination( $post_id, $custom_fields );

		//Print comments
		if ( get_post_type() == 'tasks' ) {
			comments_template();
			wp_list_comments();
		}
}
add_shortcode( 'go_task','go_task_shortcode' );


/**
 * VISITOR CONTENT
 */

/**
 * Logic to decide if locks should be used for visitors
 * based on options and task settings.
 * @param $custom_fields
 * @return null
 */
function go_display_visitor_content ( $custom_fields, $post_id, $task_name, $badge_name, $uc_task_name ){
    if ($custom_fields['go-guest-view'][0] == "global"){
        $guest_access = get_option('options_go_guest_global');
    }
    else {
        $guest_access = $custom_fields['go-guest-view'][0];
    }

	if ($guest_access == "regular" ) {
        $task_is_locked = go_display_locks($post_id, null, false, $task_name, $badge_name, $custom_fields, false, $uc_task_name);
        if (!$task_is_locked){
			go_display_visitor_messages($custom_fields, $post_id);
        }
		return null;
	}
	else if ($guest_access == "open" ) {
        go_display_visitor_messages($custom_fields, $post_id);
		return null;
	}
	else {
		echo "<div><h2 class='go_error_red'>This content is for logged in users only.</h2></div>";
		return null;
	}
}

/**
 * LOCKS
 * prevents all visitors both logged in and out from accessing the task content,
 * if they do not meet the requirements.
 * The task_locks function will set the output for the locks
 * and set the task_is_locked variable to true if it is locked.
 */
function go_display_locks ($post_id, $user_id, $is_admin, $task_name, $badge_name, $custom_fields, $is_logged_in, $uc_task_name){

    //ADD:add code to check for master unlocked already set and then skip this next section if that is so
    //if($is_logged_in && $master_unlock != true){

    $task_is_locked = false;
	if ($custom_fields['go-location_map_toggle'][0] == true && !empty($custom_fields['go-location_map_loc'][0])){
		$on_map = true;
	}
    if ($custom_fields['go_lock_toggle'][0] == true || $custom_fields['go_sched_toggle'][0] == true || $on_map == true) {
        $task_is_locked = go_task_locks($post_id, $user_id, $task_name, $custom_fields, $is_logged_in, false);
    }

    //if it is locked, show master password field and stop printing of the task.
    if ($task_is_locked == true) {
        if ($is_logged_in) {
            //Show password unlock
            echo "<div class='go_lock'><h3>Unlock {$uc_task_name}</h3><input id='go_result' class='clickable' type='password' placeholder='Enter Password'>";
            go_buttons($user_id, $custom_fields, null, null, null, 'unlock',false,null,null, false );
            echo "</div>";
            echo "</div>";
            return $task_is_locked;

        }
    }
    return $task_is_locked;
    //ADD TASK CHAIN LOCKS
}

/**
 * Print the stage content for visitors
 * @param $custom_fields
 */
function go_display_visitor_messages( $custom_fields, $post_id ) {
    //Print messages
    $i = 0;
    $stage_count = $custom_fields['go_stages'][0];
    while (  $stage_count > $i) {
        go_print_1_message ( $custom_fields, $i );
        $i++;
    }

    // displays the chain pagination list so that visitors can still navigate chains easily
    go_task_render_chain_pagination( $post_id, $custom_fields );
    echo "</div>";
}

/**
 * ADMIN CONTENT
 */

/**
 * Logic for which type of admin content to show based
 * on the drop down selection at the top of the tasks pages on frontend.
 * @param $is_admin
 * @param $admin_view
 * @param $custom_fields
 * @param $is_logged_in
 * @param $task_name
 * @return string
 */
function go_admin_content ($post_id, $is_admin, $admin_view, $custom_fields, $is_logged_in, $task_name, $status, $uid, $task_id, $badge_name){
    if ($is_admin && $admin_view == 'all') {
        go_display_all_admin_content($custom_fields, $is_logged_in, $task_name, $status, $uid, $task_id);
        $admin_flag = 'stop';
        return $admin_flag;
    }

    else if ($is_admin && $admin_view == 'guest') {
        go_display_visitor_content( $custom_fields, $post_id, $task_name, $badge_name, $task_name);
        $admin_flag = 'stop';
        return $admin_flag;
    }
    else if (!$is_admin || $admin_view == 'user') {
        $admin_flag = 'locks';
        return $admin_flag;
    }
    else if (!$is_admin || $admin_view == 'player') {
        $admin_flag = 'no_locks';
        return $admin_flag;
    }
}

/**
 * If the dropdown is "all" do this.
 * @param $custom_fields
 * @param $is_logged_in
 * @param $task_name
 */
function go_display_all_admin_content( $custom_fields, $is_logged_in, $task_name, $status, $user_id, $post_id ) {
    go_display_rewards( $custom_fields, $user_id, $post_id, $task_name  );
    go_due_date_mods ($custom_fields, $is_logged_in, $task_name );
    //Print messages
    $i = 0;
    $stage_count = $custom_fields['go_stages'][0];
    while (  $stage_count > $i) {
        go_print_1_message ( $custom_fields, $i );
        go_checks_for_understanding ($custom_fields, $i, $i, $user_id, $post_id, null, null, null);
        go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status);
        $bonus_status = go_get_bonus_status($post_id, $user_id);
        if ($bonus_status == 0){
            go_print_bonus_stage ($user_id, $post_id, $custom_fields, $task_name);
        }
        $i++;
    }

    // displays the chain pagination list so that visitors can still navigate chains easily
    go_task_render_chain_pagination( $post_id, $custom_fields);
    echo "</div>";
}

/**
 * DUE DATE MODIFIER MESSAGE
 * @param $custom_fields
 * @param $is_logged_in
 * @param $task_name
 */
function go_due_date_mods ($custom_fields, $is_logged_in, $task_name ){
    $uc_task_name = ucwords($task_name);
    if ($custom_fields['go_due_dates_toggle'][0] == true && $is_logged_in) {
        echo '<div class="go_late_mods"><h3 class="go_error_red">Due Date</h3>';
        echo "<ul>";
        $num_loops = $custom_fields['go_due_dates_mod_settings'][0];
        for ($i = 0; $i < $num_loops; $i++) {
            $mod_date = 'go_due_dates_mod_settings_'.$i.'_date';
            $mod_date = $custom_fields[$mod_date][0];
            $mod_date_timestamp = strtotime($mod_date);
            $mod_date = date('F j, Y \a\t g:i a\.' ,$mod_date_timestamp);
            $mod_date_offset = $mod_date_timestamp + (3600 * get_option('gmt_offset'));
            $current_timestamp = current_time( 'timestamp' );
            //$current_time = current_time( 'mysql' );
            $mod_percent = 'go_due_dates_mod_settings_'.$i.'_mod';
            $mod_percent = $custom_fields[$mod_percent][0];
            if ($current_timestamp > $mod_date_offset){
                echo '<li>The rewards on this '. $task_name . '  were reduced by<br>';
			}
			else {
                echo '<li>The rewards on this ' . $uc_task_name . ' will be reduced <br>';
            }
            echo "" . $mod_percent . "% on " . $mod_date . "</li>";
        }
        echo "</ul></div>";
    }
}

/**
 * TIMER
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
 * MESSAGES
 * Determines what stages to print
 * @param $status
 * @param $custom_fields
 */
function go_print_messages ( $status, $custom_fields, $user_id, $post_id){
	//Print messages
	$i = 0;
	$stage_count = $custom_fields['go_stages'][0];
	while ( $i <= $status && $stage_count > $i) {
		go_print_1_message ( $custom_fields, $i );
        //Print Checks for Understanding for the last stage message printed and buttons
        go_checks_for_understanding ($custom_fields, $i, $status, $user_id, $post_id, null, null, null);
        //go_checks_for_understanding ($custom_fields, $i, $status, $user_id, $post_id, $bonus, $bonus_status, $repeat_max)
		$i++;
	}
    if ($i == $status){
        go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status);
    }
}

/**
 * Prints a single stage content
 * @param $custom_fields
 * @param $i
 */
function go_print_1_message ( $custom_fields, $i ){
	$key = 'go_stages_' . $i . '_content';
	$content = $custom_fields[$key][0];
	$message = ( ! empty( $content ) ? $content : '' ); // Completion Message
	//adds oembed to content
		if(isset($GLOBALS['wp_embed']))
		$message  = $GLOBALS['wp_embed']->autoembed($message );
	echo "<div id='message_" . $i . "' class='go_stage_message'  style='display: none;'>".do_shortcode(wpautop( $message  ) )."</div>";
}

/**
 * checks if the task is done
 * Used when awarding badges on the last stage of the last quest in a chain
 * @param $post_id
 * @param bool $is_current_task
 * @param bool $undo
 * @return bool
 */
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

/**
 * Award an achievement if this is the last stage in a pod or chain.
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

function go_bonus_loot () {
    $bonus_loot = strtolower( get_option( 'options_go_loot_bonus_loot_name' ) );
    $bonus_loot_uc = ucwords($bonus_loot);
    echo "
    	<h4>{$bonus_loot_uc}</h4>
        <p>Click the button below to try and claim " . $bonus_loot . ".
        ";
    echo "<br><br>

		<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
		<button class='go_bonus_button'>?</button>
	";
    echo "</p>";
}

function go_display_rewards( $custom_fields, $user_id, $post_id, $task_name ) {
    $stage_count = $custom_fields['go_stages'][0];

    if ($stage_count > 1){
       $stage_name = get_option('options_go_tasks_stage_name_plural');
	}
	else{
        $stage_name = get_option('options_go_tasks_stage_name_singular');
	}

    if (get_option( 'options_go_loot_xp_toggle' )){
    	$xp_on = true;
        $xp_name = get_option('options_go_loot_xp_name');
        $xp_loot = 0;
    }else{
        $xp_on = false;
	}
    if (get_option( 'options_go_loot_gold_toggle' )){
    	$gold_on = true;
        $gold_name = get_option('options_go_loot_gold_name');
        $gold_loot= 0;
    }else{
        $gold_on = false;
    }
    if (get_option( 'options_go_loot_health_toggle' )){
    	$health_on = true;
        $health_name = get_option('options_go_loot_health_name');
        $health_loot = 0;
    }else{
        $health_on = false;
    }
    if (get_option( 'options_go_loot_c4_toggle' )){
    	$c4_on = true;
        $c4_name = get_option('options_go_loot_c4_name');
        $c4_loot = 0;
    }else{
        $c4_on = false;
    }

    $i = 0;
    while ( $stage_count > $i ) {
    	if ($xp_on) {
            $key = 'go_stages_' . $i . '_rewards_xp';
            //$xp = $custom_fields[$key][0];
            $xp = (isset($custom_fields[$key][0]) ?  $custom_fields[$key][0] : null);
            $xp_loot = $xp + $xp_loot;
        }

        if($gold_on) {
            $key = 'go_stages_' . $i . '_rewards_gold';
            $gold = (isset($custom_fields[$key][0]) ?  $custom_fields[$key][0] : null);
            $gold_loot = $gold + $gold_loot;
        }

        if($health_on) {
            $key = 'go_stages_' . $i . '_rewards_health';
            $health = (isset($custom_fields[$key][0]) ?  $custom_fields[$key][0] : null);
            $health_loot = $health + $health_loot;
        }

        if($c4_on) {
            $key = 'go_stages_' . $i . '_rewards_c4';
            $c4 = (isset($custom_fields[$key][0]) ?  $custom_fields[$key][0] : null);
            $c4_loot = $c4 + $c4_loot;
        }

        $i++;
    }
    echo "This {$task_name} has:<br>{$stage_count} {$stage_name}<br>Where you can earn:";
    if($xp_on){
    	echo "<br>{$xp_loot} {$xp_name} ";
    }
    if($gold_on){
    	echo "<br>{$gold_loot} {$gold_name} ";
    }
    if($health_on){
    	echo "<br>{$health_loot} {$health_name} ";
    }
    if($c4_on){
    	echo "<br>{$c4_loot} {$c4_name} ";
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
function go_task_render_chain_pagination ( $task_id, $custom_fields ) {

	if ( empty( $task_id ) ) {
		return;
	} else {
		$task_id = (int) $task_id;
	}


    $chain_id = $custom_fields['go-location_map_loc'][0];
	if (!empty($chain_id)) {
        $chain_order = go_get_chain_order($chain_id);
        if ( empty( $chain_order ) || ! is_array( $chain_order ) ) {
            return;
        }
        $this_task_order = array_search($task_id, $chain_order);
        if ($this_task_order == 0) {
            $prev_task = null;
        } else {
            $prev_key = (int)$this_task_order - 1;
            $prev_task = $chain_order[$prev_key];
            if (is_int($prev_task)){
                $prev_link = get_permalink($prev_task);
                $prev_title = get_the_title($prev_task);
            }
        }
        $count = count($chain_order);
        $next_key = (int)$this_task_order + 1;
        if ($count > $next_key){
        	$next_task = $chain_order[$next_key];
            if (is_int($next_task)){
                $next_link = get_permalink($next_task);
                $next_title = get_the_title($next_task);
            }
        }

    } else {
        return false;
    }

    echo"<div>";
	if (isset($prev_link)){
		echo "<div style='float: left;'><p>Previous:<br><a href='$prev_link'>$prev_title</a></p></div> ";
	}
	if (isset($next_link)){
        echo "<div style='float: right;'><p>Next Up:<br><a href='$next_link'>$next_title</a></p></div>";
	}
	echo "</div>";

	foreach ( $chain_order as $task_id ) {
	}
}

function go_stage_password_validate($pass, $custom_fields, $status, $bonus){
    $master_pass = get_option('options_go_masterpass');

    if ($bonus){
    	$stage_pass = $custom_fields['go_bonus_stage_password'][0];
    }
    else{
        $stage_pass = $custom_fields['go_stages_' . $status . '_password'][0];
	}

    if ($pass == $stage_pass) {
    	return 'password';
        //password is correct
    }
    else if ( $pass == $master_pass){
        return 'master password';
    } else{
        echo json_encode(array('json_status' => 'bad_password', 'html' => '', 'rewards' => array('gold' => 0,), 'location' => '',));
        die();
    }
}

function go_lock_password_validate($pass, $custom_fields, $status){

    $lock_pass = $custom_fields['go_unlock_password'][0];
    $master_pass = get_option('options_go_masterpass');
    if ($pass == $lock_pass ) {
        //password is correct
		return 'password';
    } else if($pass == $master_pass){
        return 'master password';
    } else
    	{
        echo json_encode(array('json_status' => 'bad_password', 'html' => '', 'rewards' => array(), 'location' => '',));
        die();
    }
}

function go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    $custom_fields = get_post_custom( $post_id );
    $task_name = strtolower( get_option( 'options_go_tasks_name_singular' ) );
    $outro_message = (isset($custom_fields['go_outro_message'][0]) ?  $custom_fields['go_outro_message'][0] : null);
    if (get_option( 'options_go_loot_xp_toggle' )){
        $xp_on = true;
        $xp_name = get_option('options_go_loot_xp_name');
        $xp_loot = $wpdb->get_var ("SELECT xp FROM {$go_task_table_name} WHERE uid = {$user_id} AND post_id = {$post_id}");
    }
    if (get_option( 'options_go_loot_gold_toggle' )){
        $gold_on = true;
        $gold_name = get_option('options_go_loot_gold_name');
        $gold_loot = $wpdb->get_var ("SELECT gold FROM {$go_task_table_name} WHERE uid = {$user_id} AND post_id = {$post_id}");
    }
    if (get_option( 'options_go_loot_health_toggle' )){
        $health_on = true;
        $health_name = get_option('options_go_loot_health_name');
        $health_loot = $wpdb->get_var ("SELECT health FROM {$go_task_table_name} WHERE uid = {$user_id} AND post_id = {$post_id}");
    }
    if (get_option( 'options_go_loot_c4_toggle' )){
        $c4_on = true;
        $c4_name = get_option('options_go_loot_c4_name');
        $c4_loot = $wpdb->get_var ("SELECT c4 FROM {$go_task_table_name} WHERE uid = {$user_id} AND post_id = {$post_id}");
    }
    echo "<div id='outro' class='go_checks_and_buttons'>";
    echo "    
        <h3>" . ucwords($task_name) . " Complete!</h3>
        <p>".$outro_message."</p>
        <h4>Rewards</h4>
        <p>You earned  : ";
    if(isset($xp_on)){
        echo "<br>{$xp_loot} {$xp_name} ";
    }
    if(isset($gold_on)){
        echo "<br>{$gold_loot} {$gold_name} ";
    }
    if(isset($health_on)){
        echo "<br>{$health_loot} {$health_name} ";
    }
    if(isset($c4_on)){
        echo "<br>{$c4_loot} {$c4_name} ";
    }

    if ($custom_fields['bonus_loot_toggle'][0]) {
        go_bonus_loot();
    }

    $bonus_status = go_get_bonus_status($post_id, $user_id);
	if ($bonus_status == 0){
		go_buttons($user_id, $custom_fields, null, $stage_count, $status, 'show_bonus', false, null, null, true);
	}
    echo "</div>";
    if ($bonus_status > 0){
            go_print_bonus_stage ($user_id, $post_id, $custom_fields, $task_name);
	}
}

function go_print_bonus_stage ($user_id, $post_id, $custom_fields, $task_name){
    global $wpdb;
    $bonus_status = go_get_bonus_status($post_id, $user_id);
    $content = (isset($custom_fields['go_bonus_stage_content'][0]) ?  $custom_fields['go_bonus_stage_content'][0] : null);

    $bonus_stage_name =  get_option( 'options_go_tasks_bonus_stage' );
    $repeat_max = (isset($custom_fields['go_bonus_limit'][0]) ?  $custom_fields['go_bonus_limit'][0] : null);

    echo "
        <div id='bonus_stage' >
            <h3>" . ucwords($bonus_stage_name)   . "</h3>
            ". $content . "
            <h3>This ".$bonus_stage_name." can be submitted ".$repeat_max." times.</h3>
        </div>
    ";

    $i = 0;
    while ( $i <= $bonus_status && $repeat_max > $i) {
        //Print Checks for Understanding for the last stage message printed and buttons
        go_checks_for_understanding($custom_fields, $i, null, $user_id, $post_id, true, $bonus_status, $repeat_max);
        $i++;
    }

    //if ($bonus_status == $i ) {
    //}

}

function go_task_change_stage() {
    global $wpdb;

    /* variables
    */
    $user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function
    $is_admin = go_user_is_admin( $user_id );
    // post id posted from ajax function (untrusted)
    $post_id = ( ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0 );
    $custom_fields = get_post_custom( $post_id ); // Just gathering some data about this task with its post id
	$task_name = strtolower( get_option( 'options_go_tasks_name_singular' ) );
    $button_type 			= ( ! empty( $_POST['button_type'] ) ? $_POST['button_type'] : null );
    $check_type 			= ( ! empty( $_POST['check_type'] ) ? $_POST['check_type'] : null );
    $status        = ( ! empty( $_POST['status'] ) ? (int) $_POST['status'] : 0 ); // Task's status posted from ajax function
	$result = (!empty($_POST['result']) ? (string)$_POST['result'] : ''); // Contains the result from the check for understanding

    /**
     * Security
     */
    // gets the task's post id to validate that it exists, user requests for non-existent tasks
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

    //Sets the $status variable
	// and checks if the status on the button is the same as the database
	//they should be the same unless a user had two windows open and continued in one and then switch to the other.
	//If they are different then respond and have the page refresh.
    // get the user's current progress on this task as db_status

    if ($button_type == 'continue_bonus' || $button_type == 'complete_bonus' || $button_type == 'undo_bonus' || $button_type == 'abandon_bonus') {
        $db_status = go_get_bonus_status($post_id, $user_id);
    }
    else{
        $db_status = go_get_status($post_id, $user_id);
    }


    if ($status != $db_status && $check_type != 'unlock'){
        echo json_encode(
            array(
                'json_status' => 'refresh'
            )
        );
        die();
	}

    ob_start();
	//print new stage and check for understanding
    /**
     * BUTTON TYPE
     */



    /**
     * Button types and loot actions
	 * timer--start timer and create entry in task table and give entry reward (task, actions, totals)
	 * continue/complete--get mod, get stage loot (task, actions, totals)
	 * undo/abandon--get loot from actions table last entry for this task (task, actions, totals)
	 * bonus continue/complete
     */

    if ($button_type == 'timer'){
        //RECORD TIMER START TIME
        //check if there is already a start time
        $start_time = go_timer_start_time ($post_id, $user_id );

        //if this task is being started for the first time
        if ( $start_time == null ){
            go_update_stage_table($user_id, $post_id, $custom_fields, null, null, 'timer', 'start_timer', 'timer', null, null);
        }
		$time_left = go_time_left ($custom_fields, $user_id, $post_id );
		$time_left_ms = $time_left * 1000;


		go_display_timer($custom_fields, true, $user_id, $post_id, $task_name);
        //print new stage message
        go_print_messages ( $status, $custom_fields, $user_id, $post_id  );
        //Print the bottom of the page
        //go_task_render_chain_pagination( $post_id, $custom_fields );

        //Print comments
        if ( get_post_type() == 'tasks' ) {
            comments_template();
            wp_list_comments();
        }

    }
    else if ($button_type == 'continue' || $button_type == 'complete'){
        //$result = null;
        //if password

		////////////////
		/// DO ANY FINAL VALIDATION
		///
        if ($check_type == 'password'){
            $result = go_stage_password_validate($result, $custom_fields, $status, false);
        }
        //else if ($check_type == 'URL'){
            //$result = ( ! empty( $_POST['url'] ) ? (string) $_POST['url'] : '' ); // Contains user-entered url
		//}
		//else if ($check_type == 'quiz'){
        	//get ratio of number correct
		//}
        //else if ($check_type == 'upload'){
            //get id of media item
            //$result = ( ! empty( $_POST['url'] ) ? (string) $_POST['url'] : '' );
        //}
        //else if ($check_type == 'blog'){
        	//create blog post function ($uid, $result);
            //get id of blog post item to set in actions
        //}
        else if ($check_type == 'unlock'){
            //this function checks password and returns
			//invalid or return true
        	$result = go_lock_password_validate($result, $custom_fields, $status);
            if ($result == 'password' || $result == 'master password') {
                //set unlock flag
                go_update_actions( $user_id, 'task',  $post_id, null, null, $check_type, $result, null, null,  null, null, null, null, null, null, null, null, null );

                echo json_encode(array('json_status' => 'refresh'));
                die;
                //refresh
			}
		}

        //////////////////
		/// UPDATE THE DATABASE for Continue or Complete stage
		///
        $badge_ids = null;
        $group_ids = null;
        if ($button_type == 'complete') {
            $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);
            $group_ids = (isset($custom_fields['go_groups'][0]) ?  $custom_fields['go_groups'][0] : null);
        }

        go_update_stage_table ($user_id, $post_id, $custom_fields, $status, null, true, $result, $check_type, $badge_ids, $group_ids );
        $status = $status + 1;

        ////////////////////
        /// Write out the new information
        if ($button_type == 'continue') {
            //print new check for understanding based on last stage check type
            go_checks_for_understanding($custom_fields, $status - 1, $status, $user_id, $post_id, null, null, null);
            //print new stage message
            go_print_1_message($custom_fields, $status );
            //print new stage check for understanding
            go_checks_for_understanding($custom_fields, $status, $status, $user_id, $post_id, null, null, null);
            //$complete = false;
        }else{//Complete

            //print new check for understanding based on last stage check type
            go_checks_for_understanding($custom_fields, $status - 1, $status, $user_id, $post_id, null, null, null);
            //complete

            //$complete = true;
            $stage_count = $custom_fields['go_stages'][0];
            go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status);
            //print outro and bonus button
        }
    }
	else if ($button_type == 'abandon') {
    	//remove entry loot
        $redirect_url = get_option('options_go_landing_page_on_login', '');
        $redirect_url = (site_url() . '/' . $redirect_url);
        go_update_stage_table ($user_id, $post_id, $custom_fields, $status, null, false, 'abandon', null, null, null );
    }
	else if ($button_type == 'undo' || $button_type == 'undo_last') {
        if ($button_type == 'undo_last') {
            $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);
            $group_ids = (isset($custom_fields['go_groups'][0]) ?  $custom_fields['go_groups'][0] : null);
        }
        go_update_stage_table ($user_id, $post_id, $custom_fields, $status, null, false, 'undo', null, $badge_ids, $group_ids );
        go_checks_for_understanding ($custom_fields, $status -1 , $status - 1 , $user_id, $post_id, null, null, null);
    }
    else if ($button_type == 'show_bonus'){

        go_print_bonus_stage($user_id, $post_id, $custom_fields, $task_name);


    }
    else if ($button_type == 'complete_bonus' || $button_type == 'continue_bonus' || $button_type == 'undo_bonus' || $button_type == 'abandon_bonus'){
        $repeat_max = $custom_fields['go_bonus_limit'][0];
        $bonus_status = go_get_bonus_status($post_id, $user_id);

		if ($button_type == 'continue_bonus' || $button_type == 'complete_bonus') {

			$check_type = $custom_fields['go_bonus_stage_check'][0];
			//validate the check for understanding and get modifiers
			if ($check_type == 'password'){
				$result = go_stage_password_validate($result, $custom_fields, $status, true);
			}

			//get the rewards and apply modifiers
			//record the check for understanding in the activity table
			//update the task table and the totals table
			//update repeat count
			//update bonus history

            //////////////////
            /// UPDATE THE DATABASE for BONUS stages complete
            ///
            go_update_stage_table ($user_id, $post_id, $custom_fields, null, $bonus_status, true, $result, $check_type, null, null );
            $bonus_status = $bonus_status + 1;
			$repeat_max = $custom_fields['go_bonus_limit'][0];
            if ($bonus_status  < $repeat_max) {
                go_checks_for_understanding($custom_fields, $bonus_status -1 , null, $user_id, $post_id, true, $bonus_status, $repeat_max);
                go_checks_for_understanding($custom_fields, $bonus_status, null, $user_id, $post_id, true, $bonus_status, $repeat_max);
            }else
            {
                go_checks_for_understanding($custom_fields, $bonus_status - 1, null, $user_id, $post_id, true, $bonus_status, $repeat_max);
            }
		}
	 	else if ($button_type == 'undo_bonus') {

            //////////////////
            /// UPDATE THE DATABASE for BONUS stages undo
            ///
            go_update_stage_table ($user_id, $post_id, $custom_fields, null, $bonus_status, false, 'undo_bonus', $check_type, null, null);
			go_checks_for_understanding($custom_fields, $bonus_status -1, null, $user_id, $post_id, true, $bonus_status - 1 , $repeat_max);
		}
        else if ($button_type == 'abandon_bonus') {
            $status = go_get_status($post_id, $user_id);
            $stage_count = $custom_fields['go_stages'][0];
            go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status);
        }
	}

    // stores the contents of the buffer and then clears it
    $buffer = ob_get_contents();

    ob_end_clean();

    // constructs the JSON response
    echo json_encode(
        array(
            'json_status' => 'success',
            'html' => $buffer,
            'redirect' => $redirect_url,
            'button_type' => $button_type,
            'time_left' => $time_left_ms
        )
    );
    die();
}

/**
 * ALL THE STUFF BELOW HAS TO DO WITH QUIZZES
 */
//This is the function that checks the test answers
function go_unlock_stage() {
    global $wpdb;
    $task_id = ( ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0 );
    $user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 );
    //$go_task_table_name = "{$wpdb->prefix}go_tasks";
    $db_status = go_get_status($task_id, $user_id);
    $status        = ( ! empty( $_POST['status'] ) ? (int) $_POST['status'] : 0 ); // Task's status posted from ajax function
    if ($status != $db_status){
        echo "refresh";
        die();
    }

    check_ajax_referer( 'go_unlock_stage_' . $task_id . '_' . $user_id );
    $test_size  = ( ! empty( $_POST['list_size'] ) ? (int) $_POST['list_size'] : 0 );
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
    $test_stage = 'go_stages_' . $status . '_quiz';
    //$test_fail_name = 'test_fail_count';


    $test_c_array = $custom_fields[ $test_stage ][0];
    $test_c_uns = unserialize( $test_c_array );
    $keys = $test_c_uns[1];
    $all_keys_array = array();
    for ( $i = 0; $i < count( $keys ); $i++ ) {
        $all_keys_array[] = implode( "### ", $keys[ $i ][1] );
    }
    $key = $all_keys_array[0];

    if ( $type == 'checkbox'  ) {
        $key_str = preg_replace( '/\s*\#\#\#\s*/', '### ', $key );
        $key_array = explode( '### ', $key_str );
    }

    $fail_question_ids = array();
    //if there is at least 2 questions, make array of wrong answers
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
            echo true;
            die();
        } else {
            //go_inc_test_fail_count( $test_fail_name, $test_fail_max );
            if ( ! empty( $fail_question_ids ) ) {
                $fail_id_str = implode( ', ', $fail_question_ids );
                $fail_count = count($fail_question_ids);
                go_update_fail_count($user_id, $task_id, $fail_count, $status);
                echo $fail_id_str;
            } else {
                echo 0;
            }
            die();
        }
    }
    //else there is only one answer, so just return true or false
    else {

        if ( $type == 'radio' ) {
            if ( strtolower( $choice ) == strtolower( $key ) ) {
                echo true;
                die();
            } else {
                //go_inc_test_fail_count( $test_fail_name, $test_fail_max );
                echo 0;
                go_update_fail_count($user_id, $task_id,1, $status);
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
                echo true;
                die();
            } else {
                //go_inc_test_fail_count( $test_fail_name, $test_fail_max );
                echo 0;
                go_update_fail_count($user_id, $task_id, 1, $status);
                die();
            }
        }
    }

    die();
}

//Adds the quiz modifier to the actions table
function go_update_fail_count($user_id, $task_id, $fail_count, $status){
    global $wpdb;
    $go_actions_table_name = "{$wpdb->prefix}go_actions";
	//check to see if a quiz-mod exists for this stage
	$quiz_mod_exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id 
				FROM {$go_actions_table_name} 
				WHERE source_id = %d AND uid = %d AND action_type = %s",
            $task_id,
            $user_id,
			'quiz_mod'
        )
    );
	if ($quiz_mod_exists == null) {
        //then update if needed
        go_update_actions($user_id, 'quiz_mod', $task_id, $status + 1, null, $status, $fail_count, null, null, null, null, null, null, null, null, null, null, null);
    }
}
/*
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

//DELETE Updates points after a quiz--need to move to updates and delete

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

*/



?>
