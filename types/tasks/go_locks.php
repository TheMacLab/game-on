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
 * @param $check_only
 * @param $loot
 * @return bool
 */
function go_task_locks ( $id, $user_id, $task_name, $custom_fields, $is_logged_in, $check_only){



    if ($check_only) {
        $is_unlocked = go_master_unlocked($user_id, $id); //one query per quest on map
        if ($is_unlocked == 'password' || $is_unlocked == 'master password') {
            //$is_unlocked = true;
            return $is_unlocked;
        }
    }

    $task_is_locked = false;
    //$task_is_locked_l = false;
    if (!$task_name){
        $check_only = true;
    }

    /**
     * This section is for the password lock
     */
    $go_lock_toggle = (isset($custom_fields['go_lock_toggle'][0]) ?  $custom_fields['go_lock_toggle'][0] : null);
    $go_password_lock = (isset($custom_fields['go_password_lock'][0]) ?  $custom_fields['go_password_lock'][0] : null);
    if ($go_lock_toggle == true && $go_password_lock == true){
        $task_is_locked = true;
    }

    /**
     * This section is for the chain locks
     */
    $location_map_toggle = (isset($custom_fields['go-location_map_toggle'][0]) ?  $custom_fields['go-location_map_toggle'][0] : null);
    if ($location_map_toggle == true) {
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
    $go_sched_toggle = (isset($custom_fields['go_sched_toggle'][0]) ?  $custom_fields['go_sched_toggle'][0] : null);
    if ($go_sched_toggle == true) {
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


        if (!$check_only) {
            $task_caps = ucwords($task_name);
            $lock_message = (isset($custom_fields['go_lock_message'][0]) ?  $custom_fields['go_lock_message'][0] : null);
            ob_start();
            echo '<div class="go_locks"><h3 class="go_error_red">Locked ' . $task_caps . '</h3>' . $lock_message . '<br>You must unlock one lock to continue.';
        }
        //$task_is_locked = false;
        $num_locks = $custom_fields['go_locks'][0];
        $lock_num_display = 1;
        $print_locks = false;
        for ($i = 0; $i < $num_locks; $i++) {
            if (!$check_only) {
                ob_start();
                if ($lock_num_display > 1){
                    echo '-or-';
                }
                echo '<div class="go_lock"><p>Lock ' . $lock_num_display . '<ul>';
            }
            $this_lock = false;
            $lock_num = "go_locks_" . $i . "_keys";
            $num_keys = $custom_fields[$lock_num][0];

            for ($k = 0; $k < $num_keys; $k++) {
                $key_type = "go_locks_" . $i . "_keys_" . $k . "_key";
                $key_type = $custom_fields[$key_type][0];
                if ($this_lock == true && !$check_only){
                    echo '-and-';
                }
                if ($key_type != null) {
                    $this_lock = $key_type($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only);
                    $print_locks = true;
                }

                if ($this_lock == true){
                    $task_is_locked = true;
                    $this_lock_on = true;
                }
            }
            if (!$check_only) {
                echo '</ul></div>';
                if ($this_lock_on) {
                    $lock_num_display++;
                    echo ob_get_clean();
                } else {
                    ob_end_clean();
                }
            }

        }
        if (!$check_only) {
            echo '</div>';
            $message1 = ob_get_clean();
            if($task_is_locked && $print_locks == true) {
                echo $message1;
            }
        }

    }
    //if ($task_is_locked_l){
    //    $task_is_locked = true;
    //}

    //Locks End
    return $task_is_locked;

}

/**
 * Lock Until Date
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_until_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    $is_admin = go_user_is_admin( $user_id );
    $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_until";
    $start_filter = $custom_fields[$option][0];

    $unix_now = current_time('timestamp');
    //$offset = 3600 * get_option('gmt_offset');
    //$unix_now = $unix_now - $offset;
    if (!empty($start_filter)) {

        $start_unix = strtotime($start_filter);

        // stops execution if the start date and time has not passed yet
        if ($unix_now < $start_unix) {
            $time_string = date('g:i A', $start_unix) . ' on ' . date('D, F j, Y', $start_unix);
            if (!$check_only) {
                echo "<li class='go_error_red'>It is after {$time_string}.</li>";

                    echo go_timezone_message($user_id);

            }
            $this_lock = true;

        }
    }

    return $this_lock;
}

/**
 * Lock After Date
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_after_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    //$is_admin = go_user_is_admin( $user_id );
    $this_lock = false;
    $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_after";
    $start_filter = $custom_fields[$option][0];

    // holds the output to be displayed when a non-admin has been stopped by the start filter
    $unix_now = current_time('timestamp');
    //$offset = 3600 * get_option('gmt_offset');
    //$unix_now = $unix_now - $offset;
    if (!empty($start_filter)) {
        $start_unix = strtotime($start_filter);

        // stops execution if the user is a non-admin and the start date and time has not
        // passed yet
        if ($unix_now > $start_unix) {
            $time_string = date('g:i A', $start_unix) . ' on ' . date('D, F j, Y', $start_unix);
            if (!$check_only) {
                echo "<li class='go_error_red'>This " . $task_name . " was only available until {$time_string}.</li>";
                    echo go_timezone_message($user_id);

            }
            $this_lock = true;
        }
    }

    return $this_lock;
}

/**
 * Badge Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_badge_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){

    $badge_name = get_option( 'options_go_naming_other_badges' );
    $this_lock = false;
    if ($is_logged_in) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_badge";
        $terms_needed = $custom_fields[$option][0];
        $terms_needed = unserialize($terms_needed);
        // gets the current user's period(s)
        //$num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        //$user_terms = array();
        //for ($i = 0; $i < $num_terms; $i++) {

            //$user_period = "go_section_and_seat_" . $i . "_user-section";
            //$user_period = get_user_meta($user_id, $user_period, true);
            //$user_terms[] = $user_period;
/*
            global $wpdb;
            $go_loot_table_name = "{$wpdb->prefix}go_loot";
            $badges_array = $wpdb->get_var ("SELECT badges FROM {$go_loot_table_name} WHERE uid = {$user_id}");
            $user_terms = unserialize($badges_array);
*/
            $loot = go_get_loot($user_id);
            $badges_array = $loot['badges'];
            $user_terms = unserialize($badges_array);
        //}



        //if the current user is in a class period then check if it is the right one
        if (!$user_terms || !is_array($user_terms)) {
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
                    echo "<li class='go_error_red'>You are in the possession of one of these " . $badge_name . ":</li>";
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
 * Groups Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_user_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_group";
        $terms_needed = $custom_fields[$option][0];
        $terms_needed = unserialize($terms_needed);
        // gets the current user's period(s)
        //$num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        // $user_terms = array();
        //for ($i = 0; $i < $num_terms; $i++) {
            /*
            global $wpdb;
            $go_loot_table_name = "{$wpdb->prefix}go_loot";
            $groups_array = $wpdb->get_var ("SELECT groups FROM {$go_loot_table_name} WHERE uid = {$user_id}");
            $user_terms = unserialize($groups_array);
            */
            $loot = go_get_loot($user_id);
            $groups_array = $loot['groups'];
            $user_terms = unserialize($groups_array);
        //}

        //set $user_terms to empty array if not set
        if (!$user_terms || !is_array($user_terms)) {
            $user_terms = array();
        }

        // determines if the user has the correct group
        if (!empty($terms_needed)) {
            // checks to see if the filter array are in the the user's groups array
            $intersection = array_values(array_intersect($user_terms, $terms_needed));
            // stores an array of the groups that were not found in the user's groups array
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
 * Seating Chart/ Period Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_period_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_lock_sections";
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

        //set $user_terms to empty array if not set
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
                    echo "<li class='go_error_red'>You must be in one of the following classes:</li>";
                    echo "<ul class='go_term_list go_error_red'>";
                    foreach ($term_diff as $term_id) {
                        //$term_object = get_term($term_id);
                        //$term_name = $term_object->name;
                        if (!empty($term_id)) {
                            $term = get_term($term_id);
                            $term_name = $term->name;
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
 * Minimum XP Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_xp_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_xp";
        $xp_needed = $custom_fields[$option][0];
        //get user health from totals table
        //$user_xp = go_get_user_loot ($user_id, 'xp');
        $loot = go_get_loot($user_id);
        $user_xp = $loot['xp'];
        $xp_name = get_option('options_go_loot_xp_name');
        if ($user_xp < $xp_needed){
            if (!$check_only) {
                echo "<br><span class='go_error_red'>You must have {$xp_needed} {$xp_name} to access this {$task_name}.</span></br>";
            }
            $this_lock = true;
        }
    }
    return $this_lock;
}

/**
 * Minimum XP LEVELS Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_xp_levels_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_xp_level";
        $xp_needed = $custom_fields[$option][0];

        //$user_xp = go_get_user_loot ($user_id, 'xp');
        $loot = go_get_loot($user_id);
        $user_xp = $loot['xp'];
        $xp_name = get_option('options_go_loot_xp_name');
        if ($user_xp < $xp_needed){
            if (!$check_only) {
                echo "<br><span class='go_error_red'>You must have {$xp_needed} {$xp_name} to access this {$task_name}.</span></br>";
            }
            $this_lock = true;
        }
    }
    return $this_lock;
}

/**
 * Minimum C4 Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_c4_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_c4";
        $c4_needed = $custom_fields[$option][0];
        //get user health from totals table
        $user_c4 = go_get_user_loot($user_id, 'c4');
        $loot = go_get_loot($user_id);
        $user_c4 = $loot['c4'];
        $c4_name = get_option('options_go_loot_c4_name');
        if ($user_c4 < $c4_needed){
            if (!$check_only) {
                echo "<br><span class='go_error_red'>You must have {$c4_needed} {$c4_name} to access this {$task_name}.</span></br>";
            }
            $this_lock = true;
        }
    }
    return $this_lock;
}

/**
 * Minimum Gold Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_gold_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_gold";
        $gold_needed = $custom_fields[$option][0];
        //get user health from totals table
        $user_gold = go_get_user_loot ($user_id, 'gold');
        $loot = go_get_loot($user_id);
        $user_gold = $loot['gold'];
        $gold_name = get_option('options_go_loot_gold_name');
        if ($user_gold < $gold_needed){
            if (!$check_only) {
                echo "<br><span class='go_error_red'>You must have {$gold_needed} {$gold_name} to access this {$task_name}.</span></br>";
            }
            $this_lock = true;
        }
    }
    return $this_lock;
}

/**
 * Minimum Health Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $i
 * @param $k
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_health_lock($id, $user_id, $task_name, $custom_fields, $i, $k, $is_logged_in, $check_only ){
    $this_lock = false;
    if( $is_logged_in ) {
        $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_health";
        $health_needed = $custom_fields[$option][0];
        //get user health from totals table
        //$user_health = go_get_user_loot ($user_id, 'health');
        $loot = go_get_loot($user_id);
        $user_health = $loot['health'];
        $health_name = get_option('options_go_loot_health_name');
        if ($user_health < $health_needed){
            if (!$check_only) {
                echo "<br><span class='go_error_red'>You must have {$health_needed} {$health_name} to access this {$task_name}.</span></br>";
            }
            $this_lock = true;
        }
    }
    return $this_lock;
}

/**
 * Scheduled Access
 * @param $user_id
 * @param $custom_fields
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_schedule_access($user_id, $custom_fields, $is_logged_in, $check_only){
    if( $is_logged_in || !$is_logged_in) {
        $is_locked = true;
        $user_terms = array();
        $num_terms = get_user_meta($user_id, 'go_section_and_seat', true);
        $user_terms = array();
        for ($i = 0; $i < $num_terms; $i++) {

            $user_period = "go_section_and_seat_" . $i . "_user-section";
            $user_period = get_user_meta($user_id, $user_period, true);
            $user_terms[] = $user_period;
        }

        //set $user_terms to empty array if not set
        if (!$user_terms) {
            $user_terms = array();
        }

        $sched_num = (isset($custom_fields['go_sched_opt'][0]) ?  $custom_fields['go_sched_opt'][0] : null); //the number of schedule locks

        //loop through the locks to see if any one of them allows the user to proceed
        for ($i = 0; $i < $sched_num; $i++) {
            $dow_section = "go_sched_opt_" . $i . "_sched_sections";

            $dow_section = (isset($custom_fields[$dow_section][0]) ?  unserialize($custom_fields[$dow_section][0]) : null);

            if (!$dow_section){
                $dow_section = array();
            }
            $dow_days = "go_sched_opt_" . $i . "_dow";
            $dow_days = (isset($custom_fields[$dow_days][0]) ?  unserialize($custom_fields[$dow_days][0]) : null);
            if (!$dow_days){$dow_days = array();}

            $dow_time = "go_sched_opt_" . $i . "_time";
            $dow_time = (isset($custom_fields[$dow_time][0]) ?  $custom_fields[$dow_time][0] : null);
            $dow_time = strtotime($dow_time);
            //$offset = 3600 * get_option('gmt_offset');
            //$dow_time = $dow_time + $offset;

            $dow_minutes = "go_sched_opt_" . $i . "_min";
            $dow_minutes = (isset($custom_fields[$dow_minutes][0]) ?  $custom_fields[$dow_minutes][0] : null);
            $seconds_available = 60 * $dow_minutes;


            $current_time = current_time('timestamp');
            //$offset = 3600 * get_option('gmt_offset');
            //$current_time = $current_time - $offset;

            //If the user is in at least one section, continue . . .
            if ((array_intersect($user_terms, $dow_section) != null) || (empty ($dow_section))) {
                //If today is one of the days it ulocks
                if (in_array(date("l"), $dow_days)) {

                    //if the current time is between the start time and the start time and the minutes unlocked
                    if (($current_time >= $dow_time) && ($current_time < ( $dow_time + $seconds_available ))) {
                        //it is unlocked, so exit loop and continue
                        $is_locked = false;

                        break;
                    }
                }
            }
        }

        if ($is_locked == true) {
            if (!$check_only) {
                $lock_message = (isset($custom_fields['go_sched_access_message'][0]) ?  $custom_fields['go_sched_access_message'][0] : null);
                if (!empty ($lock_message)){
                    $lock_message = $lock_message . '<br>';
                }

                echo "<div class='go_sched_access_message'><h3 class='go_error_red'>Access Schedule</h3>$lock_message";


                //echo current_time( 'timestamp' );

                $first = true;
                for ($i = 0; $i < $sched_num; $i++) {
                    $dow_sections = "go_sched_opt_" . $i . "_sched_sections";
                    //$dow_section = unserialize($custom_fields[$dow_section][0]);
                    $dow_sections = (isset($custom_fields[$dow_sections][0]) ?  unserialize($custom_fields[$dow_sections][0]) : null);

                        $dow_section = array();

                    if (!empty($dow_sections)){
                        foreach ($dow_sections as $dow_term) {
                            if (!empty($dow_term)) {
                                $term = get_term($dow_term);
                                $term_name = $term->name;
                                $dow_section[] = $term_name;

                            }
                        }
                    }


                    $dow_days = "go_sched_opt_" . $i . "_dow";
                    //$dow_days = unserialize($custom_fields[$dow_days][0]);
                    $dow_days = (isset($custom_fields[$dow_days][0]) ?  unserialize($custom_fields[$dow_days][0]) : null);
                    if (!$dow_days) {
                        $dow_days = array();
                    }
                    $dow_time = "go_sched_opt_" . $i . "_time";
                    //$dow_time = $custom_fields[$dow_time][0];
                    $dow_time = (isset($custom_fields[$dow_time][0]) ?  $custom_fields[$dow_time][0] : null);
                    if (!$dow_time) {
                        $dow_time = array();
                    }
                    $dow_minutes = "go_sched_opt_" . $i . "_min";
                    //$dow_minutes = $custom_fields[$dow_minutes][0];
                    $dow_minutes = (isset($custom_fields[$dow_minutes][0]) ?  $custom_fields[$dow_minutes][0] : null);
                    if (!$dow_minutes) {
                        $dow_minutes = array();
                    }
                    $dow_time = strtotime($dow_time);
                    if (!$first) {
                        echo "-or-<br>";
                    }

                    if (!empty ($dow_section)) {
                        print_r(implode(" & ", $dow_section));
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

                echo go_timezone_message($user_id);

                echo '</div>';
            }
            return $is_locked;
        }

    }
}


/**
 * Task Chain Lock
 * @param $id
 * @param $user_id
 * @param $task_name
 * @param $custom_fields
 * @param $is_logged_in
 * @param $check_only
 * @return bool
 */
function go_task_chain_lock($id, $user_id, $task_name, $custom_fields, $is_logged_in, $check_only){
    global $wpdb;
    $chain_id = $custom_fields['go-location_map_loc'][0];
    //if not empty chain id
    //get variables
        //is_optional
        //previous_task
        //is pod
        //is first in chain
        //locked by prev
    if (!empty($chain_id)) {
        $go_task_ids = go_get_chain_posts ($chain_id, false);

        //$is_optional = $custom_fields['go-location_map_opt'][0];
        //$post_ids = wp_list_pluck( $go_task_ids, 'ID' );
        $this_task_order = array_search($id, $go_task_ids);
        if ($this_task_order == 0) {
            $first_in_chain = true;
            $prev_task = null;
        } else {
            $first_in_chain = false;
            $prev_key = (int)$this_task_order - 1;
            while ($prev_key >= 0){
                $prev_task = $go_task_ids[$prev_key];
                $prev_task_data = go_map_task_data($id);
                $prev_task_custom_meta = $prev_task_data[3];
                $is_last_optional = (isset($prev_task_custom_meta['location_map_opt'][0]) ?  $prev_task_custom_meta['location_map_opt'][0] : null);

                //$islast_optional = get_post_meta($prev_task, 'go-location_map_opt', true);
                if (!$is_last_optional){
                    break;
                }
                $prev_key--;
            }
            if ($is_last_optional){
                $first_in_chain = true;
                $prev_task = null;
            }
        }
        $is_pod = get_term_meta($chain_id, 'pod_toggle', true);
        $locked_by_prev = get_term_meta($chain_id, 'locked_by_previous', true);
    } else {
        return false;
    }

    //if this is pod and any task in pod has masterpassword unlock
    //then unlocked
    if ($is_pod) {
        $master_unlock = false;
        //are any in the pod unlocked by masterpassword
        foreach ($go_task_ids as $task_id) {
            $is_unlocked = go_master_unlocked($user_id, $task_id);
            if ($is_unlocked == 'password' || $is_unlocked == 'master password') {
                $master_unlock = true;
            }

        }
        if ($master_unlock == true) {
            return false;
        }
    }
    //if this is a pod or the first in the chain
    //and this chain is not locked by the previous
    //then set to unlocked
    if (($is_pod || $first_in_chain) && !$locked_by_prev) {
        return false;
    }
    //else if this is a pod or the first in the chain
    //and this chain is locked by previous chain
    //then get the previous chain
    else if (($is_pod || $first_in_chain) && $locked_by_prev) {
        $term = get_term($chain_id, 'task_chains');
        $termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'task_chains');
        $parent_map_term_id = $termParent->term_id;
        $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => $parent_map_term_id, 'fields' => 'ids'
        );
        //get all chains on this map
        //$sibling_chains = get_terms('task_chains', $args);
        $sibling_chains = go_get_map_chain_term_ids($parent_map_term_id);
        $this_chain_order = array_search($chain_id, $sibling_chains);
        //if this is first chain on map
        //then get the last chain on previous map
        if ($this_chain_order == 0) {
            $prev_chain = null;
            $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => 0, 'fields' => 'ids');
            //get all parent maps (chains with no parents)
            $top_chains = get_terms('task_chains', $args);
            $this_map_order = array_search($parent_map_term_id, $top_chains);
            //if this is the first map, then return unlocked
            if ($this_map_order == 0){
                return false;
            }
            //get previous map
            $prev_map_key = (int)$this_map_order - 1;
            $prev_map = $top_chains[$prev_map_key];
            //get last chain on previous map
            //$args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'DSC', 'parent' => $prev_map, 'fields' => 'ids');
            //children chains
            //$children_chains = get_terms('task_chains', $args);
            $children_chains = go_get_map_chain_term_ids($prev_map);
            //if no children chains on previous map
            if ($children_chains == false) {
                return false;
            }
            //get last chain of previous map
            $prev_chain = $children_chains[0];

        }
        //else this is not the first chain
        //then get the previous chain on this map
        else {
            $prev_key = (int)$this_chain_order - 1;
            $prev_chain = $sibling_chains[$prev_key];
        }


        /**
         * what is last task in previous chain that was not optional
         * and is it done
         * */

        $go_task_ids = go_get_chain_posts ($prev_chain, false);
        $reversed_ids = array_reverse($go_task_ids);
        //if the previous chain is pod
        $is_prev_pod = get_term_meta($prev_chain, 'pod_toggle', true);
        if($is_prev_pod){
            //count # done and compare to #requried
            $pod_all = get_term_meta($prev_chain, 'pod_all', true);
            if ($pod_all) {
                $pod_min = count($reversed_ids);
            }
            else{
                $pod_min = get_term_meta($prev_chain, 'pod_done_num', true);
            }
            $pod_count = 0;
            foreach ($reversed_ids as $reversed_id) {
                $is_task_done = go_is_done($reversed_id, $user_id);
                if ($is_task_done){
                    $pod_count++;
                }

            }
            if ($pod_count >= $pod_min){
                $is_done = true;
            }
            else{
                $is_done = false;
            }
        }
        //else previous chain is not a pod
        else {
            foreach ($reversed_ids as $reversed_id) {
                //get is optional
                //if not optional
                //exit
                $islast_optional = get_post_meta($reversed_id, 'go-location_map_opt', true);
                if (!$islast_optional) {
                    break;
                }
            }
            if (isset($reversed_id)){
                $is_done = go_is_done($reversed_id, $user_id);
            }
            else {
                //there is no previous task
                $is_done = true;
            }
        }
        if ($is_done) {
            return false;

        } else {
            //the previous task is not done
            if (!$check_only) {
                $task_link = get_permalink($reversed_id);
                $task_title = get_the_title($reversed_id);
                echo "<div class='go_sched_access_message'><h3 class='go_error_red'>Locked</h3>The $task_name, <a href='$task_link'>$task_title</a> must be done first</div>";
            }

            return true;
        }
    }
    //else if this is not the first task in a chain (and it is not a pod)
    //get previous task and check if it is complete
    else if (!$first_in_chain) {

        $is_done = go_is_done($prev_task, $user_id);
        if ($is_done) {
            return false;
        } else {
            if (!$check_only) {
                $task_link = get_permalink($prev_task, false) ;
                $task_title = get_the_title($prev_task);
                echo "<div class='go_sched_access_message'><h3 class='go_error_red'>Locked</h3>The $task_name, <a href='$task_link'>$task_title</a> must be done first</div>";
            }
            return true;
        }
    }
    //I think all the options are covered above,
    //but just in case
    else {
        return false;
    }
}

/**
 * Timezone message
 */
function go_timezone_message($user_id) {
    $is_admin = go_user_is_admin( $user_id );
    $timezone = get_option('timezone_string');
    $current_time = current_time('timestamp');
    //$offset = 3600 * get_option('gmt_offset');
    //$current_time = $current_time - $offset;
    $current_time = date( 'g:ia l', $current_time );
    echo '<div><br> This lock is set based on the timezone ' . $timezone . ' where it is currently ' . $current_time . '.';
    if ($is_admin){
        echo '<br>Admin Message: This setting can be changed in the <a href="' . admin_url('options-general.php') . '">wordpress settings.</a> ';
    }
    echo '</div>';
}

/**
 * @param $user_id
 * @return mixed
 */
function go_get_loot($user_id){
    global $wpdb;
    $key = 'go_get_loot';
    $data = wp_cache_get( $key );
    if ($data !== false){
        $loot = $data;
    }else {

        $go_loot_table_name = "{$wpdb->prefix}go_loot";
        $loot = $wpdb->get_results("SELECT * FROM {$go_loot_table_name} WHERE uid = {$user_id}");
        $loot = $loot[0];
        $loot = json_decode(json_encode($loot), True);
        wp_cache_set($key, $loot, 'go_single');
    }
    return $loot;
}

?>