<?php

/**
 * LOCKS START
 * prevents users (both logged-in and logged-out) from accessing the task content, if they
 * do not meet the requirements.
 * @param $id
 * @param $user_id
 * @param $is_admin
 * @param $task_name
 * @param $custom_fields
 * @param $is_logged_in
 * @return bool
 */
function go_task_locks ( $id, $user_id, $task_name, $custom_fields, $is_logged_in ){

    $task_is_locked = false;
    if (!$task_name){
        $check_only = true;
    }

    /**
     * This section is for the password lock
     */

    if ($custom_fields['go_lock_toggle'][0] == true && $custom_fields['go_password_lock'][0] == true){
        $task_is_locked = true;
    }

    /**
     * This section is for the chain locks
     */
    if ($custom_fields['go-location_map_toggle'][0] == true) {
        ob_start();
        $task_is_locked_cl = go_task_chain_lock($id, $user_id, $task_name, $custom_fields, $is_logged_in, $check_only);
        $chain_message = ob_get_clean();

        if ($task_is_locked_cl == true) {
            echo $chain_message;
            $task_is_locked = true;
            //Locks End
            //return $task_is_locked;
        }
    }

    /**
     * This section is for the scheduled access
     */
    if ($custom_fields['go_sched_toggle'][0] == true) {
        ob_start();
        $task_is_locked_sa = go_schedule_access($user_id, $custom_fields, $is_logged_in, $check_only);
        $scheduled_message = ob_get_clean();

        if ($task_is_locked_sa) {
            echo $scheduled_message;
            $task_is_locked = true;
            //Locks End
            //return $task_is_locked;
        }
    }

    //only continues if there is no scheduled access or it is the scheduled time frame.
    /**
     * Loop to check all the locks and keys
     */
    if ($custom_fields['go_lock_toggle'][0] == true ) {
        $task_is_locked_l = false;
        $num_locks = $custom_fields['go_locks'][0];
        for ($i = 0; $i < $num_locks; $i++) {
            ob_start();
            $this_lock = false;
            $lock_num = "go_locks_" . $i . "_keys";
            $num_keys = $custom_fields[$lock_num][0];
            if (!$check_only) {
                if ($task_is_locked_l == true) {
                    echo '-or-<div class="go_lock"><p>Lock ' . ($i + 1) . '<ul>';
                } else {
                    $task_caps = ucwords($task_name);
                    echo '<div class="go_locks"><h3 class="go_error_red">Locked ' . $task_caps . '</h3>You must unlock one lock to continue.<div class="go_lock"><p>Lock ' . ($i + 1) . '<ul>';
                }
            }
            for ($k = 0; $k < $num_keys; $k++) {
                $key_type = "go_locks_" . $i . "_keys_" . $k . "_key";
                $key_type = $custom_fields[$key_type][0];
                if ($this_lock == true && !$check_only){
                    echo '-and-';
                }
                if ($key_type != null) {
                    $this_lock = $key_type($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only);
                }
            }
            if ($this_lock == true){
                $task_is_locked_l = true;
            }
            if (!$check_only) {
                echo '</ul></p></div>';
                echo '</div>';
                $message1 = ob_get_clean();

                echo $message1;
            }
        }
    }
    if ($task_is_locked_l){
        $task_is_locked = true;
    }

    //Locks End
    return $task_is_locked;

}

/**
 * Lock Until Date
 */
function go_until_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    $option = "go_locks_".$i."_keys_".$k."_options_0_until";
    $start_filter = $custom_fields[$option][0];

    $unix_now = current_time( 'timestamp' );
    if ( ! empty( $start_filter )) {

        $start_unix = strtotime($start_filter);

        // stops execution if the start date and time has not passed yet
        if ( $unix_now < $start_unix ) {
            $time_string = date( 'g:i A', $start_unix ) . ' on ' . date( 'D, F j, Y', $start_unix );
            if (!$check_only) {
                echo "<li class='go_error_red'>It is after {$time_string}.</li>";
            }
            $this_lock = true;

        }
    }
    return $this_lock;
}

/**
 * Lock After Date
 */
function go_after_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    $option = "go_locks_".$i."_keys_".$k."_options_0_after";
    $start_filter = $custom_fields[$option][0];

    // holds the output to be displayed when a non-admin has been stopped by the start filter
    $time_string = '';
    $unix_now = current_time( 'timestamp' );
    if ( ! empty( $start_filter )) {
        $start_unix = strtotime($start_filter);

        // stops execution if the user is a non-admin and the start date and time has not
        // passed yet
        if ( $unix_now > $start_unix ) {
            $time_string = date( 'g:i A', $start_unix ) . ' on ' . date( 'D, F j, Y', $start_unix );
            if (!$check_only) {
                echo "<li class='go_error_red'>This " . $task_name . " was only available until {$time_string}.</li>";
            }
            $this_lock = true;
        }
    }
    return $this_lock;
}

/**
 * Badge Lock
 */
function go_badge_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $badge_name = get_option( 'options_go_naming_other_badges' );
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_badge";
        $terms_needed = $custom_fields[$option][0];
        $terms_needed = unserialize($terms_needed);
        // gets the current user's period(s)
        $num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        $user_terms = array();
        for ($i = 0; $i < $num_terms; $i++) {

            $user_period = "go_section_and_seat_" . $i . "_user-section";
            $user_period = get_user_meta($user_id, $user_period, true);
            $user_terms[] = $user_period;
        }

        //if the current user is in a class period then check if it is the right one
        if (!$user_terms) {
            $user_terms = array();
        }

        // determines if the user has the correct badges
        if (!empty($terms_needed)) {
            // checks to see if the filter array are in the the user's badge array
            $intersection = array_values(array_intersect($user_terms, $terms_needed));
            // stores an array of the badges that were not found in the user's badge array
            $term_diff = array_diff($terms_needed, $intersection);
            if (!empty($term_diff)) {
                if (!$check_only) {
                    echo "<li class='go_error_red'>You  possess one of these " . $badge_name . ":</li>";
                    echo "<ul class='go_term_list go_error_red'>";
                    foreach ($term_diff as $term_id) {
                        $term_object = get_term($term_id);
                        $term_name = $term_object->name;
                        if (!empty($term_name)) {
                            echo "<li>$term_name</li>";
                        }
                    }
                    echo "</ul>";
                }
                $this_lock = true;
            }
        }
    }
    return $this_lock;

}

/**
 * Seating Chart/ Period Lock
 */
function go_period_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_lock_sections_js_load";
        $terms_needed = $custom_fields[$option][0];
        $terms_needed = unserialize($terms_needed);
        // gets the current user's period(s)
        $num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        $user_terms = array();
        for ($i = 0; $i < $num_terms; $i++) {

            $user_period = "go_section_and_seat_" . $i . "_user-section";
            $user_period = get_user_meta($user_id, $user_period, true);
            $user_terms[] = $user_period;
        }

        //if the current user is in a class period then check if it is the right one
        if (!$user_terms) {
            $user_terms = array();
        }

        // determines if the user has the correct term
        if (!empty($terms_needed)) {
            // checks to see if the filter array are in the the user's badge array
            $intersection = array_values(array_intersect($user_terms, $terms_needed));
            // stores an array of the badges that were not found in the user's badge array
            $term_diff = array_diff($terms_needed, $intersection);
            if (!empty($term_diff)) {
                if (!$check_only) {
                    echo "<li class='go_error_red'>You are in one of the following classes:</li>";
                    echo "<ul class='go_term_list go_error_red'>";
                    foreach ($term_diff as $term_id) {
                        //$term_object = get_term($term_id);
                        //$term_name = $term_object->name;
                        if (!empty($term_id)) {
                            echo "<li>$term_id</li>";
                        }
                    }
                    echo "</ul>";
                }
                $this_lock = true;
            }
        }
    }

    return $this_lock;
}

/**
 * xp Lock --not finished
 */
function go_xp_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_sections";
        $terms_needed = $custom_fields[$option][0];
        //$terms_needed = unserialize($terms_needed);
        // gets the current user's period(s)
        $num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        $user_terms = array();
        for ($i = 0; $i < $num_terms; $i++) {

            $user_period = "go_section_and_seat_" . $i . "_user-section";
            $user_period = get_user_meta($user_id, $user_period, true);
            $user_terms[] = $user_period;
        }

        //if the current user is in a class period then check if it is the right one
        if (!$user_terms) {
            $user_terms = array();
        }

        // determines if the user has the correct badges
        if (!empty($terms_needed)) {
            // checks to see if the filter array are in the the user's badge array
            $intersection = array_values(array_intersect($user_terms, $terms_needed));
            // stores an array of the badges that were not found in the user's badge array
            $term_diff = array_diff($terms_needed, $intersection);
            if (!empty($term_diff)) {
                if (!$check_only) {
                    echo "<li class='go_error_red'>You need to have XP to continue.</li>";
                    echo "<ul class='go_term_list go_error_red'>";
                    foreach ($term_diff as $term_id) {
                        //$term_object = get_term($term_id);
                        //$term_name = $term_object->name;
                        if (!empty($term_id)) {
                            echo "<li>$term_id</li>";
                        }
                    }
                    echo "</ul>";
                }
                $this_lock = true;
            }
        }
    }

    return $this_lock;
}

/**
 * User Group Lock
 */
function go_user_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_group";
        $terms_needed = $custom_fields[$option][0];
        $terms_needed = unserialize($terms_needed);
        // gets the current user's period(s)
        $num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        $user_terms = array();
        for ($i = 0; $i < $num_terms; $i++) {

            $user_period = "go_section_and_seat_" . $i . "_user-section";
            $user_period = get_user_meta($user_id, $user_period, true);
            $user_terms[] = $user_period;
        }

        //if the current user is in a class period then check if it is the right one
        if (!$user_terms) {
            $user_terms = array();
        }

        // determines if the user has the correct badges
        if (!empty($terms_needed)) {
            // checks to see if the filter array are in the the user's badge array
            $intersection = array_values(array_intersect($user_terms, $terms_needed));
            // stores an array of the badges that were not found in the user's badge array
            $term_diff = array_diff($terms_needed, $intersection);
            if (!empty($term_diff)) {
                if (!$check_only) {
                    echo "<li class='go_error_red'>You are a member of one of these groups:</li>";
                    echo "<ul class='go_term_list go_error_red'>";
                    foreach ($term_diff as $term_id) {
                        $term_object = get_term($term_id);
                        $term_name = $term_object->name;
                        if (!empty($term_name)) {
                            echo "<li>$term_name</li>";
                        }
                    }
                    echo "</ul>";
                }
                $this_lock = true;
            }
        }
    }

    return $this_lock;
}

/**
 * Minimum Health Lock
 */
function go_health_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_health";
        $health_needed = $custom_fields[$option][0];
        //get user health from totals table
        //$user_health = get from totals table
        $health_name = get_option('options_go_loot_health_name');
        //if ($user_health < $health_needed){
        // echo "<br><span class='go_error_red'>You must have {$option} {$health_name} to access this {$task_name}.</span></br>";
        //$this_lock = true;
        //}
    }
    return $this_lock;
}

/**
 * schedule Lock
 */
function go_schedule_access($user_id, $custom_fields, $is_logged_in, $check_only){
    if( $is_logged_in ) {

        $is_locked = true;
        $user_terms = array();
        $num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        for ($i = 0; $i < $num_terms; $i++) {

            $user_period = "go_section_and_seat_" . $i . "_user-section";
            $user_period = get_user_meta($user_id, $user_period, true);
            $user_terms[] = $user_period;
        }


        date_default_timezone_set('America/Los_Angeles');
        $sched_num = $custom_fields['go_sched_opt'][0];
        for ($i = 0; $i < $sched_num; $i++) {
            $dow_section = "go_sched_opt_" . $i . "_sched_sections_js_load";
            $dow_section = unserialize($custom_fields[$dow_section][0]);
            if (!$dow_section){$dow_section = array();}
            $dow_days = "go_sched_opt_" . $i . "_dow";
            $dow_days = unserialize($custom_fields[$dow_days][0]);
            if (!$dow_days){$dow_days = array();}
            $dow_time = "go_sched_opt_" . $i . "_time";
            $dow_time = $custom_fields[$dow_time][0];
            if (!$dow_time){$dow_time = array();}
            $dow_minutes = "go_sched_opt_" . $i . "_min";
            $dow_minutes = $custom_fields[$dow_minutes][0];
            if (!$dow_minutes){$dow_minutes = array();}
            $dow_time = strtotime($dow_time);
            //If the user is in at least one section, continue . . .
            if ((array_intersect($user_terms, $dow_section) != null) || (empty ($dow_section))) {
                //If today is one of the days it ulocks
                if (in_array(date("l"), $dow_days)) {
                    //if the current time is between the start time and the start time and the minutes unlocked
                    if ((time() >= strtotime($dow_time)) && (time() < ($dow_time + ($dow_minutes * 60)))) {
                        //it is unlocked, so exit loop and continue
                        $is_locked = false;

                        break;
                    }
                }
            }
        }

        if ($is_locked == true) {
            $task_is_locked = true;
            if (!$check_only) {

                echo "<div class='go_sched_access_message'><h3 class='go_error_red'>Access Schedule</h3>";

                $first = true;
                for ($i = 0; $i < $sched_num; $i++) {
                    $dow_section = "go_sched_opt_" . $i . "_sched_sections_js_load";
                    $dow_section = unserialize($custom_fields[$dow_section][0]);
                    if (!$dow_section) {
                        $dow_section = array();
                    }
                    $dow_days = "go_sched_opt_" . $i . "_dow";
                    $dow_days = unserialize($custom_fields[$dow_days][0]);
                    if (!$dow_days) {
                        $dow_days = array();
                    }
                    $dow_time = "go_sched_opt_" . $i . "_time";
                    $dow_time = $custom_fields[$dow_time][0];
                    if (!$dow_time) {
                        $dow_time = array();
                    }
                    $dow_minutes = "go_sched_opt_" . $i . "_min";
                    $dow_minutes = $custom_fields[$dow_minutes][0];
                    if (!$dow_minutes) {
                        $dow_minutes = array();
                    }
                    $dow_time = strtotime($dow_time);
                    if (!$first) {
                        echo "-or-<br>";
                    }
                    print_r(implode(" & ", $dow_section));
                    if (!empty ($dow_section)) {
                        echo ' on ';
                    } else {
                        echo 'All Classes on ';
                    }
                    print_r(implode(" & ", $dow_days));
                    echo " @ ";
                    echo date('g:iA', $dow_time);
                    echo " for " . $dow_minutes . " minutes.";
                    echo "<br>";
                    $first = false;

                }
                echo "</div>";
            }
        }
        return $task_is_locked;
    }
}

/**
 * Task Chain Lock
 */
function go_task_chain_lock($id, $user_id, $task_name, $custom_fields, $is_logged_in, $check_only){

    //if in a chain
        //get variables
        //chain id
        //is_optional
        //previous_task
        //is pod
        //is first in chain

    //if is pod or first in chain
        //is locked by previous set
            //if true, then
                //what was previous chain
                //what is last task in previous chain that was not optional
                //is it done
                //if false
                    //$task_is_locked = true;

    //else
        //what is previous task that wasn't optional
        //is it done
        //if false
            //$task_is_locked = true;

    //if $task_is_locked == true
        //message "you must complete previous task before continuing
        //return true



   // if( $is_logged_in ) {
        // determines whether or not the user can proceed, if the task is in a chain
        $temp_optional_task = $custom_fields['go_location_map_opt'][0];
        $chain_id = $custom_fields['go_location_map_loc'][0];



        if (!empty($in_chain)) {
            $chain_links = array();

            foreach ($chain_order as $chain_tt_id => $order) {
                $pos = array_search($id, $order);
                $the_chain = get_term_by('term_taxonomy_id', $chain_tt_id);
                $chain_title = ucwords($the_chain->name);
                $chain_pod = get_term_meta($chain_tt_id, 'pod_toggle', true);
                if ($pos > 0 && !$is_admin) {
                    if (empty ($temp_optional_task)) {
                        if (empty($chain_pod)) {

                             //The current task is not first and the user is not an administrator.


                            $prev_id = 0;

                            // finds the first ID among the tasks before the current one that is published
                            for ($prev_id_counter = 0; $prev_id_counter < $pos; $prev_id_counter++) {
                                $temp_id = $order[$prev_id_counter];
                                $temp_optional_prev_task = (boolean)get_post_meta(
                                    $temp_id,
                                    'go_mta_optional_task',
                                    true
                                );
                                if (empty ($temp_optional_prev_task)) {
                                    $temp_task = get_post($temp_id);

                                    $temp_finished = true;
                                    $temp_status = go_task_get_status($temp_id);
                                    $temp_five_stage_counter = null;
                                    $temp_status_required = 4;
                                    $temp_three_stage_active = (boolean)get_post_meta(
                                        $temp_id,
                                        'go_mta_three_stage_switch',
                                        true
                                    );
                                    $temp_five_stage_active = (boolean)get_post_meta(
                                        $temp_id,
                                        'go_mta_five_stage_switch',
                                        true
                                    );


                                    // determines to what stage the user has to progress to finish the task
                                    if ($temp_three_stage_active) {
                                        $temp_status_required = 3;
                                    } elseif ($temp_five_stage_active) {
                                        $temp_five_stage_counter = go_task_get_repeat_count($temp_id);
                                    }

                                    // determines whether or not the task is finished
                                    if ($temp_status !== $temp_status_required &&
                                        (!$temp_five_stage_active ||
                                            ($temp_five_stage_active && empty($temp_five_stage_counter)))) {

                                        $temp_finished = false;
                                    }

                                    if (!empty($temp_task) &&
                                        'publish' === $temp_task->post_status &&
                                        !$temp_finished) {


                                         // The task is published, but is not finished. This task must be finished
                                         //before the current task can be accepted.


                                        $prev_id = $temp_id;
                                        break;
                                    }
                                }
                            } // End for().

                            if (0 !== $prev_id) {
                                $prev_permalink = get_permalink($prev_id);
                                $prev_title = get_the_title($prev_id);

                                $link_tag = sprintf(
                                    '<a href="%s">%s (%s)</a>',
                                    $prev_permalink,
                                    $prev_title,
                                    $chain_title
                                );
                                if (false === array_search($link_tag, $chain_links)) {

                                    // appends the anchor tag for previous task
                                    $chain_links[] = $link_tag;
                                }
                            }
                        } // End if().
                    }
                }
            } // End foreach().

            if (!empty($chain_links)) {
                $link_str = '';
                for ($link_counter = 0; $link_counter < count($chain_links); $link_counter++) {
                    if ($link_counter > 0) {
                        $link_str .= ', ';
                        if (count($chain_links) > 2 && count($chain_links) === $link_counter + 1) {
                            $link_str .= 'and ';
                        }
                    }
                    $link_str .= $chain_links[$link_counter];
                }

                $visitor_str = '';
                if (!$is_logged_in) {
                    $visitor_str = ' First, you must be ' .
                        '<a href="' . esc_url($login_url) . '">logged in</a> to do so.';
                }

                printf(
                    '<p><span class="go_error_red">You must finish</span>' .
                    ' %s ' .
                    '<span class="go_error_red">to continue.</span></p>	',
                    $link_str,
                    ucwords($task_name),
                    $visitor_str
                );

                $task_is_locked = true;
            }
        }
  //  }// End if().

}

