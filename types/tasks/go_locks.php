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
function go_task_locks ( $id, $user_id, $task_name, $custom_fields, $is_logged_in, $check_only ){
    global $wpdb;

    if ($check_only) {
        $is_unlocked = go_master_unlocked($user_id, $id);
        if ($is_unlocked == 'password' || $is_unlocked == 'master password') {
            //$is_unlocked = true;
            return $is_unlocked;
        }
    }

    $task_is_locked = false;
    $task_is_locked_l = false;
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
    $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_until";
    $start_filter = $custom_fields[$option][0];

    $unix_now = current_time('timestamp');
    if (!empty($start_filter)) {

        $start_unix = strtotime($start_filter);

        // stops execution if the start date and time has not passed yet
        if ($unix_now < $start_unix) {
            $time_string = date('g:i A', $start_unix) . ' on ' . date('D, F j, Y', $start_unix);
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
    $option = "go_locks_" . $i . "_keys_" . $k . "_options_0_after";
    $start_filter = $custom_fields[$option][0];

    // holds the output to be displayed when a non-admin has been stopped by the start filter
    $time_string = '';
    $unix_now = current_time('timestamp');
    if (!empty($start_filter)) {
        $start_unix = strtotime($start_filter);

        // stops execution if the user is a non-admin and the start date and time has not
        // passed yet
        if ($unix_now > $start_unix) {
            $time_string = date('g:i A', $start_unix) . ' on ' . date('D, F j, Y', $start_unix);
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
    if ($is_logged_in) {
        $this_lock = false;
        if ($is_logged_in) {
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
function go_task_chain_lock($id, $user_id, $task_name, $custom_fields, $is_logged_in, $check_only)
{
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
        $go_task_ids = go_get_chain_order ($chain_id);

        $is_optional = $custom_fields['go-location_map_opt'][0];
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
                $islast_optional = get_post_meta($prev_task, 'go-location_map_opt', true);
                if (!$islast_optional){
                    break;
                }
                $prev_key--;
            }
            if ($islast_optional){
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
        $sibling_chains = get_terms('task_chains', $args);
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
            $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'DSC', 'parent' => $prev_map, 'fields' => 'ids'
            );
            //children chains
            $children_chains = get_terms('task_chains', $args);
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

        $go_task_ids = go_get_chain_order ($prev_chain);
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


?>