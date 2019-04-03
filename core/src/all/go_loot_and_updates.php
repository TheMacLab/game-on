<?php

/**
 * Returns an array containing data for the user's current and next rank.
 *
 * Uses the user's "go_rank" meta data value to return the name and point threshold of the current
 * and next rank.
 *
 * @since 2.4.4
 *
 * @param  int $user_id The user's id.
 * @return array Returns an array of defaults on when the user's "go_rank" meta data is empty. 
 * 				 On success, returns array of rank data.
 */
function go_get_rank ( $user_id, $go_current_xp = null ) {
	if ( empty( $user_id ) ) {
		return;
	}
	//get xp if not passed
	if ($go_current_xp == null) {
        $go_current_xp = intval(go_get_user_loot($user_id, 'xp'));
    }
    //get number of ranks in options
    $rank_count = get_option('options_go_loot_xp_levels_level');
    $i = $rank_count - 1; //account for count starting at 0
    while ( $i >= 0 ) {
        //test to see what the rank level is
        if ($i == 0){
            $xp = 0;
        }else {
            $xp = intval(get_option('options_go_loot_xp_levels_level_' . $i . '_xp'));
        }
        if ($go_current_xp >= $xp){
            $current_rank = get_option('options_go_loot_xp_levels_level_' . $i . '_name');//get rank name
            $current_rank_points = $xp;
            $next_rank_points = get_option('options_go_loot_xp_levels_level_' . ($i +1) . '_xp');//get next rank xp
            $next_rank = get_option('options_go_loot_xp_levels_level_' . ($i +1) . '_name');//get next rank name
            break;
        }
        $i--;
    }

		return array(
			'current_rank' 		  => $current_rank,
			'current_rank_points' => $current_rank_points,
			'next_rank' 		  => $next_rank,
			'next_rank_points' 	  => $next_rank_points,
            'rank_num'            => ($i + 1)
		);
}

function go_return_currency( $user_id ) {
    global $wpdb;
    $table_name_go_totals = $wpdb->prefix . "go_loot";
    $currency = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT gold 
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
            $user_id
        )
    );
    return $currency;

}

function go_return_points( $user_id ) {
    global $wpdb;
    $table_name_go_totals = $wpdb->prefix . "go_loot";
    $points = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT xp 
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
            $user_id
        )
    );
    return $points;
}

function go_return_health( $user_id ) {
    global $wpdb;
    $table_name_go_totals = $wpdb->prefix . "go_loot";
    $health = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT health 
			FROM {$table_name_go_totals} 
			WHERE uid = %d",
            $user_id
        )
    );
    return $health;
}

function go_get_loot_toggle ( $loot_type ){

    if (get_option( 'options_go_loot_' . $loot_type . '_toggle' )){
        $is_on = true;
    }else{
        $is_on = false;
    }
    return $is_on;
}

function go_get_loot_name( $loot_type){
        $name = get_option('options_go_loot_' . $loot_type . '_name');
        return $name;
}

function go_get_loot_short_name( $loot_type){
    $name = get_option('options_go_loot_' . $loot_type . '_abbreviation');
    return $name;
}

//not used
function go_display_points( $points ) {

    $prefix = get_option( 'go_points_prefix' );
    $suffix = get_option( 'go_points_suffix' );
    return "{$prefix} {$points} {$suffix}";
}
//not used
function go_display_currency( $currency ) {
    $prefix = get_option( 'go_currency_prefix' );
    $suffix = get_option( 'go_currency_suffix' );
    return "{$prefix} {$currency} {$suffix}";
}
//not used
function go_display_bonus_currency( $bonus_currency ) {
    $prefix = get_option( 'go_bonus_currency_prefix' );
    $suffix = get_option( 'go_bonus_currency_suffix' );
    return "{$prefix} {$bonus_currency} {$suffix}";
}
//not_used
function go_display_penalty( $penalty ) {
    $prefix = get_option( 'go_penalty_prefix' );
    $suffix = get_option( 'go_penalty_suffix' );
    return "{$prefix} {$penalty} {$suffix}";
}
//not_used
function go_display_minutes( $minutes ) {
    $prefix = get_option( 'go_minutes_prefix' );
    $suffix = get_option( 'go_minutes_suffix' );
    return "{$prefix} {$minutes} {$suffix}";
}

/**
 * Output currency formatted for the admin bar dropdown.
 *
 * Outputs any currency in the format (without quotations): "1234 Experience (XP)".
 *
 * @since 2.5.6
 *
 * @param  STRING $currency_type Contains the base name of the currency to be displayed
 * 			(e.g. "points", "currency", "bonus_currency", or "penalty" ).
 * @param  BOOLEAN $output Optional. TRUE will echo the currency, FALSE will return it (default).
 * @return STRING/NULL Either echos or returns the currency. Returns FALSE on failure.
 */
function go_display_longhand_currency ( $currency_type, $amount, $output = false ) {
    if ( "xp" === $currency_type ||
        "gold" === $currency_type ||
        "health" === $currency_type
    ) {

        $currency_name = get_option( "options_go_loot_{$currency_type}_name" );
        $suffix = get_option( "options_go_loot_{$currency_type}_abbreviation" );
        $str = "{$amount} {$currency_name} ({$suffix})";

        if ( $output ) {
            echo $str;
        } else {
            return $str;
        }
    } else {
        return false;
    }
}

function go_display_shorthand_currency ( $currency_type, $amount, $output = false ) {
    if ( "xp" === $currency_type ||
        "gold" === $currency_type ||
        "health" === $currency_type
    ) {

        $suffix = get_option( "options_go_loot_{$currency_type}_abbreviation" );
        $str = "{$suffix}: {$amount}";

        if ( $output ) {
            echo $str;
        } else {
            return $str;
        }
    } else {
        return false;
    }
}

function go_return_badge_count( $user_id ) {
    global $wpdb;
    $badge_count = (int) $wpdb->get_var(
        $wpdb->prepare(
            "SELECT badge_count 
			FROM {$wpdb->prefix}go_loot 
			WHERE uid = %d",
            $user_id
        )
    );
    return $badge_count;
}

/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/29/18
 * Time: 10:40 PM
 */

function go_get_health_mod ($user_id){
    //set the health mod
    $is_logged_in = ! empty( $user_id ) && is_user_member_of_blog( $user_id ) ? true : false;
    $health_toggle = go_get_loot_toggle( 'health' );
    if ($health_toggle && $is_logged_in) {
        //get current health mod from totals table
        $health_mod = go_get_user_loot($user_id, 'health') * .01;
    }
    else {
        $health_mod = 1;
    }
    if ($health_mod > 2){
        $health_mod = 2;
    }
    if ($health_mod < 0){
        $health_mod = 0;
    }
    return $health_mod;
}

/**
 * @param $user_id
 * @param $loot_type
 * @return mixed
 */
function go_get_user_loot ($user_id, $loot_type){
    //get health from totals table
    global $wpdb;
    $user_loot = go_get_loot($user_id);

    $loot = $user_loot[$loot_type];
    return $loot;
}




/**
 * @param $user_id
 * @param $post_id
 * @param $custom_fields
 * @param $status
 * @param $bonus_status
 * @param $progressing
 * @param null $result
 * @param null $check_type
 * @param null $badge_ids
 * @param null $group_ids
 */
function go_update_stage_table ($user_id, $post_id, $custom_fields, $status, $bonus_status, $progressing, $result = null, $check_type = null, $badge_ids = null, $group_ids = null )
{
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    $go_actions_table_name = "{$wpdb->prefix}go_actions";
    $is_logged_in = !empty($user_id) && is_user_member_of_blog($user_id) ? true : false;
    $bonus_status = (isset($bonus_status) ? $bonus_status : null);
    $status = (isset($status) ? $status : null);
    $health_mod = null;
    $stage_mod = null;
    $time = current_time('mysql');
    $last_time = $time;
    $xp = 0;
    $gold = 0;
    $health = 0;
    $action_type = 'task';
    $quiz_mod = 0;
    $due_date_mod = 0;
    $timer_mod = 0;
    if ($progressing === 'timer') {

        $start_time = $time;
        $new_status_task = 'null';
        $new_bonus_status_task = 'null';
        $new_bonus_status_actions = 0;

        $wpdb->query($wpdb->prepare("UPDATE {$go_task_table_name} 
                    SET 
                        last_time = IFNULL('{$last_time}', last_time) ,
                        timer_time = IFNULL('{$last_time}', last_time)                  
                    WHERE uid= %d AND post_id=%d ", $user_id, $post_id));
    } else {
        if ($progressing === true) {
            if ($status !== null) {
                $new_status_task = $status + 1;
                $new_status_actions = $status + 1;
            } else {
                $new_status_task = 'null';
                $new_status_actions = 'null';
            }
            if ($bonus_status !== null) {
                $new_bonus_status_task = $bonus_status + 1;
                $new_bonus_status_actions = $bonus_status + 1;
            } else {
                $new_bonus_status_task = 'null';
                $new_bonus_status_actions = 'null';
            }

            //if health toggle is on
            $health_toggle = get_option('options_go_loot_health_toggle');
            if ($health_toggle && $is_logged_in) {
                //get health_mod from the first time this task was tried.
                //if this is a bonus stage, then get the health mod the first time this bonus stage/repeat was attempted.
                if ($bonus_status !== null) {
                    $temp_status = $bonus_status + 1;
                    $original_health_mod = $wpdb->get_var($wpdb->prepare("SELECT global_mod 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND bonus_status = %d AND NOT result = %s
                ORDER BY id DESC LIMIT 1", $post_id, $user_id, $temp_status, 'undo_bonus'));
                } //else this is not a bonus stage and get the health mod first time it was attempted
                else {
                    $temp_status = $status + 1;
                    $original_health_mod = $wpdb->get_var($wpdb->prepare("SELECT global_mod 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND stage = %d AND NOT result = %s
                ORDER BY id DESC LIMIT 1", $post_id, $user_id, $temp_status, 'undo'));
                }
                //get current health mod from totals table
                $current_health_mod = go_get_health_mod($user_id);


                if ($original_health_mod === null) {
                    $health_mod = $current_health_mod;
                } else if ($original_health_mod > $current_health_mod) {
                    $health_mod = $current_health_mod;
                } else {
                    $health_mod = $original_health_mod;
                }
            } else {
                $health_mod = 1;
            }



            //if not entry loot--it could have a quiz
            if ($status != -1) {
                //if stage check is a quiz
                if ($check_type == 'quiz') {
                    $temp_status = $status + 1;
                    $questions_missed = $wpdb->get_var($wpdb->prepare("SELECT result 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND stage = %d AND action_type = %s", $post_id, $user_id, $temp_status, 'quiz_mod'));
                    if ($questions_missed > 0) {
                        $quiz_stage_mod = 'go_stages_' . $status . '_quiz_modifier'; //% to take off for each question missed
                        $quiz_stage_mod = (isset($custom_fields[$quiz_stage_mod][0]) ? $custom_fields[$quiz_stage_mod][0] : 0);
                        $quiz_mod = $quiz_stage_mod * .01 * $questions_missed;

                    }
                    $quiz_array = "go_stages_" . strval($status) . "_quiz";
                    $quiz_array= $custom_fields[$quiz_array];
                    $quiz_array = unserialize($quiz_array[0]);
                    $question_count = $quiz_array[3];
                    if (empty($questions_missed)){$questions_missed = 0;}
                    $result = ($question_count - $questions_missed) . "/" . $question_count;

                }

            }

            $due_date_mod = 0;
            if ($custom_fields['go_due_dates_toggle'][0] == true && $is_logged_in) {
                $num_loops = $custom_fields['go_due_dates_mod_settings'][0];
                $mod_date_latest = null;
                for ($i = 0; $i < $num_loops; $i++) {
                    $mod_date = 'go_due_dates_mod_settings_' . $i . '_date';
                    $mod_date = $custom_fields[$mod_date][0];
                    $mod_date_timestamp = strtotime($mod_date);
                    ////$mod_date = date('F j, Y \a\t g:i a\.', $mod_date_timestamp);
                    //$mod_date_timestamp = $mod_date_timestamp + (3600 * get_option('gmt_offset'));
                    $current_timestamp = current_time('timestamp');
                    $mod_percent = 'go_due_dates_mod_settings_' . $i . '_mod';
                    $mod_percent = $custom_fields[$mod_percent][0];
                    if ($current_timestamp > $mod_date_timestamp) {
                        //set the latest mod date if this is the first mod date
                        if ($mod_date_latest == null) {
                            $mod_date_latest = $mod_date_timestamp;
                            $due_date_mod = $mod_percent * .01;
                        } else if ($mod_date_timestamp > $mod_date_latest) {
                            $mod_date_latest = $mod_date_timestamp;
                            $due_date_mod = $mod_percent * .01;
                        }
                    }
                }
            }

            $timer_mod = 0;
            $timer_on = $custom_fields['go_timer_toggle'][0];
            if ($timer_on && $is_logged_in) {
                $end_time = go_end_time($custom_fields, $user_id, $post_id);
                $current_date = strtotime(current_time('mysql')); //current date and time
                $timer_time = $end_time - $current_date;
                //if the time is up, display message
                if ($timer_time <= 0) {
                    $timer_mod = (isset($custom_fields['go_timer_settings_timer_mod'][0]) ? $custom_fields['go_timer_settings_timer_mod'][0] : 0);
                    $timer_mod = $timer_mod * .01;
                }
            }

            $stage_mod = ($due_date_mod + $timer_mod + $quiz_mod);
            if ($stage_mod > 1) {
                $stage_mod = 1;
            }

            //$xp_mod_toggle = false;
            //$gold_mod_toggle = true;
            //$health_mod_toggle = false;

            /*
            if ($xp_toggle) {
                $xp_mod_toggle = get_option('options_go_loot_xp_mods_toggle');
            } else {
                $xp_mod_toggle = false;
            }
            if ($gold_toggle) {
                $gold_mod_toggle = get_option('options_go_loot_gold_mods_toggle');
            } else {
                $gold_mod_toggle = false;
            }
            if ($health_toggle) {
                $health_mod_toggle = get_option('options_go_loot_health_mods_toggle');
            } else {
                $health_mod_toggle = false;
            }
            */
            if ($status === -1) {
                /// get entry loot
                $xp = $custom_fields['go_entry_rewards_xp'][0];
                //$xp = go_mod_loot($xp, $xp_toggle, $xp_mod_toggle, $stage_mod, $health_mod);

                $gold = $custom_fields['go_entry_rewards_gold'][0];
                $gold = go_mod_loot($gold, $stage_mod, $health_mod);

                $health = $custom_fields['go_entry_rewards_health'][0];
                //$health = go_mod_loot($health, $health_toggle, $health_mod_toggle, $stage_mod, $health_mod);

            } else if ($status !== null && $progressing === true) {
                /// get modified stage loot
                $xp = $custom_fields['go_stages_' . $status . '_rewards_xp'][0];
                //$xp = go_mod_loot($xp, $xp_toggle, $xp_mod_toggle, $stage_mod, $health_mod);
                $gold = $custom_fields['go_stages_' . $status . '_rewards_gold'][0];
                $gold = go_mod_loot($gold, $stage_mod, $health_mod);
                $health = $custom_fields['go_stages_' . $status . '_rewards_health'][0];
                //$health = go_mod_loot($health, $health_toggle, $health_mod_toggle, $stage_mod, $health_mod);
            } else if ($bonus_status !== null && $progressing === true) {
                /// get modified bonus stage loot
                $xp = $custom_fields['go_bonus_stage_rewards_xp'][0];
                //$xp = go_mod_loot($xp, $xp_toggle, $xp_mod_toggle, $stage_mod, $health_mod);
                $gold = $custom_fields['go_bonus_stage_rewards_gold'][0];
                $gold = go_mod_loot($gold, $stage_mod, $health_mod);
                $health = $custom_fields['go_bonus_stage_rewards_health'][0];
                //$health = go_mod_loot($health, $health_toggle, $health_mod_toggle, $stage_mod, $health_mod);
            }

            $xp_toggle = get_option('options_go_loot_xp_toggle');
            $gold_toggle = get_option('options_go_loot_gold_toggle');
            $health_toggle = get_option( 'options_go_loot_health_toggle' );
            if (!$xp_toggle) {
                $xp = 0;
            }
            if (!$gold_toggle) {
                $gold = 0;
            }
            if (!$health_toggle) {
                $health = 0;
            }

            //make sure we don't go over 200 health
            $health = go_health_to_add($user_id, $health);


            $badges_toggle = get_option('options_go_badges_toggle');
            //ADD BADGES
            if ($badges_toggle && !empty($badge_ids)) {
                $new_badges = go_add_badges ($badge_ids, $user_id, true);
                //$badge_count = count($new_badges);
                $badge_ids = serialize($new_badges);
            }

            //ADD GROUPS
            if (!empty($group_ids)) {
                $new_groups = go_add_groups ($group_ids, $user_id, true);
                $group_ids = serialize($new_groups);
            }

        } //end progressing = true
        else if ($progressing === false) {
            $action_type = 'undo_task';
            if ($status !== null) {
                $new_status_task = $status - 1;
                $new_status_actions = $status;
            } else {
                $new_status_task = 'null';
                $new_status_actions = 'null';
            }

            if ($bonus_status !== null) {
                $new_bonus_status_task = $bonus_status - 1;
                $new_bonus_status_actions = $bonus_status;
            } else {
                $new_bonus_status_task = 'null';
                $new_bonus_status_actions = 'null';
            }
            /// get last action loot
            $xp = $wpdb->get_var($wpdb->prepare("SELECT xp 
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1", $user_id, $post_id, $status)) * -1;
            $gold = $wpdb->get_var($wpdb->prepare("SELECT gold 
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1", $user_id, $post_id, $status)) * -1;
            $health = $wpdb->get_var($wpdb->prepare("SELECT health
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1", $user_id, $post_id, $status)) * -1;
            //make sure we don't go over 200 health
            $health = go_health_to_add($user_id, $health);

            if ($status != 0) {

                $badges = $wpdb->get_var($wpdb->prepare("SELECT badges
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1", $user_id, $post_id, $status));

                if (is_serialized($badges)) {
                        $badges = unserialize($badges);
                }
                if (!is_array($badges)) {
                    $badges = array();
                }


                if (!empty($badge_ids)){//combine the term and task badges before removing them
                    $badge_ids = unserialize($badge_ids);
                    if (!is_array($badge_ids)){
                        $badge_ids = array();
                    }
                    $badges = array_unique(array_merge($badge_ids, $badges));

                }
                $badges = serialize($badges);


                $badge_ids = go_remove_badges($badges, $user_id, true);

                $badge_ids = serialize($badge_ids);

                $groups = $wpdb->get_var($wpdb->prepare("SELECT groups
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1", $user_id, $post_id, $status));
                $group_ids = go_remove_groups($groups, $user_id, true);
                $group_ids = serialize($group_ids);
            }
        }


    }

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$go_task_table_name} 
                    SET 
                        status = IFNULL({$new_status_task}, status),
                        bonus_status = IFNULL({$new_bonus_status_task}, bonus_status),
                        xp = {$xp} + xp,
                        gold = {$gold} + gold,
                        health = {$health} + health,
                        last_time = IFNULL('{$last_time}', last_time),
                        badges = '{$badge_ids}',
                        groups = '{$group_ids}'             
                    WHERE uid= %d AND post_id=%d ",
            $user_id,
            $post_id
        )
    );

    go_update_actions( $user_id, $action_type,  $post_id, $new_status_actions, $new_bonus_status_actions, $check_type, $result, $quiz_mod, $due_date_mod, $timer_mod, $health_mod,  $xp, $gold, $health, $badge_ids, $group_ids, true, true);
}

/**
 * @param $badge_ids
 * @param $user_id
 * @param bool $notify
 * @return array|mixed|null
 */
function go_add_badges ($badge_ids, $user_id, $notify = false) {

    global $wpdb;

    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    if (get_option( 'options_go_badges_toggle' )){

        //$badge_ids = (isset($custom_fields['go_purch_reward_badges'][0]) ?  $custom_fields['go_purch_reward_badges'][0] : null);
        //store badge ids
        if (!empty($badge_ids)) {
            $badge_ids_array = unserialize($badge_ids);//legacy badges saved as serialized array
            if (!is_array($badge_ids_array)){
                $badge_ids_array = array();
                $badge_ids_array[] = $badge_ids;
            }
            $badge_ids_array = ((is_array($badge_ids_array)) ? $badge_ids_array : array());
            //$user_badges = get_user_option('go_user_badges', $user_id);
            $user_badges = $wpdb->get_var ("SELECT badges FROM {$go_loot_table_name} WHERE uid = {$user_id}");
            if (!empty($user_badges) && $user_badges != null) {
                $user_badges_array = unserialize($user_badges);
                $user_badges_array = ((is_array($user_badges_array)) ? $user_badges_array : array());
                $new_badges = array_diff($badge_ids_array, $user_badges_array);
                $all_user_badges_array = array_unique(array_merge($user_badges_array, $badge_ids_array));
            } else {//there were no existing user badges
                $all_user_badges_array = $badge_ids_array;
                $new_badges = $badge_ids_array;
            }
            $all_user_badges_array = array_values($all_user_badges_array);
            $all_user_badges_ser = serialize($all_user_badges_array);

            $badge_count = count($all_user_badges_array);

            //update_user_option($user_id, 'go_user_badges', $all_user_badges_ser);
            //go_update_totals_table($user_id, null, null, null, null, null, null, null, null, $all_user_badges_ser, null, $badge_count, false);
            go_update_totals_table_Badges($user_id, $all_user_badges_ser, $badge_count);
            if ($notify === true) {
                foreach ($new_badges as $badge_id) {
                    $badge_obj = get_term($badge_id);
                    $badge_name = $badge_obj->name;
                    $image_id = get_term_meta($badge_id, 'my_image');
                    $badge_img = wp_get_attachment_image($image_id[0], array( 100, 100 ));
                    $badge_title = get_option('options_go_badges_name_singular');
                    $title = "New " . ucfirst($badge_title);
                    //go_noty_loot_success($badge_name, $message);
                    $content = "<div>" . $badge_img . "<br>" . $badge_name . "</div>";
                    //$content = "hi";
                    go_noty_message_generic ('success', $title, $content);
                }
            }
            return $new_badges;//array--not serialized
        }
    }
}

/**
 * @param $badge_ids
 * @param $user_id
 * @param bool $notify
 * @return array
 */
function go_remove_badges ($badge_ids, $user_id, $notify = false) {
    global $wpdb;
    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    if (get_option( 'options_go_badges_toggle' )){
        //$badge_ids = (isset($custom_fields['go_purch_reward_badges'][0]) ?  $custom_fields['go_purch_reward_badges'][0] : null);
        //store badge ids

        if (!empty($badge_ids)) {
            $badge_ids_array = unserialize($badge_ids);
            $badge_ids_array = ((is_array($badge_ids_array)) ? $badge_ids_array : array());
            //$user_badges = get_user_option('go_user_badges', $user_id);
            $user_badges = $wpdb->get_var ("SELECT badges FROM {$go_loot_table_name} WHERE uid = {$user_id}");

            if (!empty($user_badges)) {//there are existing user badges
                $user_badges_array = unserialize($user_badges);
                $user_badges_array = ((is_array($user_badges_array)) ? $user_badges_array : array());
                $remove_badges_array = array_intersect($user_badges_array, $badge_ids_array);
                $all_user_badges_array = array_diff($user_badges_array, $badge_ids_array);
                $all_user_badges_array = array_values($all_user_badges_array);
                $all_user_badges_ser = serialize($all_user_badges_array);

                $badge_count = count($all_user_badges_array);
                //update_user_option($user_id, 'go_user_badges', $all_user_badges_ser);
                //go_update_totals_table($user_id, null, null, null, null, null, null, null, null, $all_user_badges_ser, null, $badge_count, false);
                go_update_totals_table_Badges($user_id, $all_user_badges_ser, $badge_count);

                if ($notify === true) {
                    foreach ($remove_badges_array as $badge_id) {
                        $badge_obj = get_term($badge_id);
                        $badge_name = $badge_obj->name;
                        $badge_title = get_option('options_go_badges_name_singular');
                        $message = ucfirst($badge_title) . " Removed";
                        go_noty_loot_error($badge_name, $message);
                    }

                }
                return $remove_badges_array;//array--not serialized

            }
        }
    }
}


/**
 * @param $group_ids
 * @param $user_id
 * @param bool $notify
 * @return array|mixed|null
 */
function go_add_groups($group_ids, $user_id, $notify = false) {
    global $wpdb;
    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    if (!empty($group_ids)) {
        $group_ids_array = unserialize($group_ids);
        $group_ids_array = ((is_array($group_ids_array)) ? $group_ids_array : array());
        //$user_groups_ser = get_user_option('go_user_groups', $user_id);
        $user_groups_ser = $wpdb->get_var ("SELECT groups FROM {$go_loot_table_name} WHERE uid = {$user_id}");
        if (!empty($user_groups_ser)) {//there are existing groups for this user
            $user_groups_array = unserialize($user_groups_ser);
            $user_groups_array = ((is_array($user_groups_array)) ? $user_groups_array : array());
            $new_groups = array_diff($group_ids_array, $user_groups_array);//for the notifications
            $all_user_groups_array = array_unique (array_merge($user_groups_array, $group_ids_array));
            $all_user_groups_array = array_values($all_user_groups_array);
            $all_user_groups_ser = serialize($all_user_groups_array);
        }else{//there are no existing groups
            $user_groups = $group_ids;
            $all_user_groups_ser = $group_ids;
            $new_groups = $group_ids_array;
        }
        //update_user_option($user_id, 'go_user_groups', $all_user_groups_ser);
        //go_update_totals_table($user_id, null, null, null, null, null, null, null, null, null, $all_user_groups_ser, 0, false);
        go_update_totals_table_Groups($user_id, $all_user_groups_ser);

        if ($notify === true) {
            foreach ($new_groups as $id) {
                $obj = get_term($id);
                $name = $obj->name;
                $message = "New Group";
                go_noty_loot_success($name, $message);
            }
        }
        return $new_groups;//array--not serialized
    }

}

/**
 * @param $group_ids
 * @param $user_id
 * @param bool $notify
 * @return array
 */
function go_remove_groups($group_ids, $user_id, $notify = false) {
    global $wpdb;
    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    if (!empty($group_ids)) {
        $group_ids_array = unserialize($group_ids);
        $group_ids_array = ((is_array($group_ids_array)) ? $group_ids_array : null);
        //$user_groups_ser = get_user_option('go_user_groups', $user_id );
        $user_groups_ser = $wpdb->get_var ("SELECT groups FROM {$go_loot_table_name} WHERE uid = {$user_id}");
        if (!empty($user_groups_ser)) {//there are existing groups for this user
            $user_groups_array = unserialize($user_groups_ser);
            $user_groups_array = ((is_array($user_groups_array)) ? $user_groups_array : array());
            $remove_groups = array_intersect($user_groups_array, $group_ids_array);//what's going to be removed
            $all_user_groups_array = array_diff($user_groups_array, $group_ids_array);
            $all_user_groups_array = array_values($all_user_groups_array);
            $all_user_groups_ser = serialize($all_user_groups_array);

            // update_user_option($user_id, 'go_user_groups', $all_user_groups_ser);//update user badges
            //go_update_totals_table($user_id, null, null, null, null, null, null, null, null, null, $all_user_groups_ser, 0, false);
            go_update_totals_table_Groups($user_id, $all_user_groups_ser);


            if ($notify === true) {//notify what was removed
                foreach ($remove_groups as $id) {
                    $obj = get_term($id);
                    $name = $obj->name;
                    $message = "Group Removed";
                    go_noty_loot_error($name, $message);
                }
            }
            return $remove_groups;//array--not serialized of what was removed
        }
    }

}

/**
 * @param $loot
 * @param $toggle
 * @param $mod_toggle
 * @param $stage_mod
 * @param $health_mod
 * @return float|int
 */
function go_mod_loot($loot, $stage_mod, $health_mod)
{
    $loot = ($loot - ($loot * $stage_mod));
    $loot = $loot * $health_mod;
    return $loot;
}

//makes sure health doesn't go over 200
/**
 * @param $user_id
 * @param $added_health
 * @return int|mixed
 */
function go_health_to_add($user_id, $added_health){
    global $wpdb;
    $current_health = go_get_user_loot( $user_id, 'health' );
    $max_new_health = 200 - $current_health;

    if ($max_new_health < $added_health){
        $added_health = $max_new_health;
    }
    return $added_health;
}

/**
 * @param $user_id
 * @param $type
 * @param $source_id
 * @param $status
 * @param $bonus_status
 * @param $check_type
 * @param $result
 * @param $quiz_mod
 * @param $late_mod
 * @param $timer_mod
 * @param $global_mod
 * @param $xp
 * @param $gold
 * @param $health
 * @param $badge_ids
 * @param $group_ids
 * @param $notify
 * @param $debt
 */
function go_update_actions($user_id, $type, $source_id, $status, $bonus_status, $check_type, $result, $quiz_mod, $late_mod, $timer_mod, $global_mod, $xp, $gold, $health, $badge_ids, $group_ids, $notify, $debt)
{
    global $wpdb;

    if (get_option('options_go_loot_xp_toggle') == false) {
        $xp = 0;
    }
    if (get_option('options_go_loot_gold_toggle') == false) {
        $gold = 0;
    }
    if (get_option('options_go_loot_health_toggle') == false) {
        $health = 0;
    }

    $xp_name = get_option('options_go_loot_xp_name');
    $gold_name = get_option('options_go_loot_gold_name');
    $health_name = get_option('options_go_loot_health_name');

    $user_loot = go_get_loot($user_id);

    // the user's current amount of experience (points)
    //$go_current_xp = go_get_user_loot($user_id, 'xp');
    $go_current_xp = $user_loot['xp'];
    $new_xp_total = $go_current_xp + $xp;

    // the user's current amount of currency
    //$go_current_gold = go_get_user_loot($user_id, 'gold');
    $go_current_gold = $user_loot['gold'];
    $new_gold_total = $go_current_gold + $gold;

    // the user's current amount of bonus currency,
    // also used for coloring the admin bar
    //$go_current_health = go_get_user_loot($user_id, 'health');
    $go_current_health = $user_loot['health'];
    $new_health_total = $go_current_health + $health;
    if ($new_health_total < 0) {
        $new_health_total = 0;
        $health = $go_current_health * -1;
    } else if ($new_health_total > 200) {
        $new_health_total = 200;
        $health = 200 - $go_current_health;
    }

    $go_actions_table_name = "{$wpdb->prefix}go_actions";
    //$time = date( 'Y-m-d G:i:s', current_time( 'timestamp', 0 ) );
    $time = current_time('mysql');
    $wpdb->insert($go_actions_table_name, array('uid' => $user_id, 'action_type' => $type, 'source_id' => $source_id, 'TIMESTAMP' => $time, 'stage' => $status, 'bonus_status' => $bonus_status, 'check_type' => $check_type, 'result' => $result, 'quiz_mod' => $quiz_mod, 'late_mod' => $late_mod, 'timer_mod' => $timer_mod, 'global_mod' => $global_mod, 'xp' => $xp, 'gold' => $gold, 'health' => $health, 'badges' => $badge_ids, 'groups' => $group_ids, 'xp_total' => $new_xp_total, 'gold_total' => $new_gold_total, 'health_total' => $new_health_total));

    //if notify = admin than this action is just creating
    //an admin notification and should not update the totals.
    //The totals will be updated in another call to this function.
    //So, if this is not a store item with admin notifications, then update the totals table
    if ($notify !== 'admin') {
        go_update_totals_table($user_id, $xp, $xp_name, $gold, $gold_name, $health, $health_name, $notify, $debt);
    }

    if ($notify === true) {
        go_update_admin_bar_v4($user_id, $new_xp_total, $xp_name, $new_gold_total, $gold_name, $new_health_total, $health_name);
    }
    //badges and groups are only updated from the add/remove badges and groups functions
}

/**
 * @param $loot
 * @param $loot_type
 */
function go_noty_loot_success ($loot, $loot_type) {
    //go_noty_close_oldest();
    echo "<script> 
        jQuery( document ).ready( function() {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: '<div style=\"font-size: 1.5em;\">" . addslashes($loot_type) . ": " . addslashes($loot) . "</div>',
            theme: 'relax',
            timeout: '3000',
            visibilityControl: true,
            callbacks: {
                beforeShow: function() { go_noty_close_oldest();},
            }
        }).show();
        });
     </script>";


}

/**
 * @param $rank
 * @param $rank_name
 */
function go_noty_level_up ($rank, $rank_name) {
    //go_noty_close_oldest();
    echo "<script> 
        jQuery( document ).ready( function() {
        new Noty({
            type: 'success',
            layout: 'topRight',
            text: '<h1>Level Up! You are now Level " . addslashes($rank) . " (" . addslashes($rank_name) . ").</h3>',
            theme: 'relax', 
            timeout: false,
            visibilityControl: true,
            callbacks: {
                beforeShow: function() { go_noty_close_oldest();},
            }
        
        }).show(); 
        });
        </script>";
}

/**
 * @param $rank
 * @param $rank_name
 */
function go_noty_level_down ($rank, $rank_name) {
    //go_noty_close_oldest();
    echo "<script> 
        jQuery( document ).ready( function() {
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: '<h1>Level Down! You are now Level " . addslashes($rank) . " (" . addslashes($rank_name) . ").</h3>',
            theme: 'relax', 
            timeout: false,
            visibilityControl: true,
            callbacks: {
                beforeShow: function() { go_noty_close_oldest();},
            }
            
        }).show();  
        });
    </script>";
}

/**
 * @param $loot
 * @param $loot_type
 */
function go_noty_loot_error ($loot, $loot_type) {
    //go_noty_close_oldest();
    echo "<script> 
        jQuery( document ).ready( function() {
        new Noty({
            type: 'error',
            layout: 'topRight',
            text: '<div style=\"font-size: 1.5em;\">" . addslashes($loot_type) . ": " . addslashes($loot) . "</div>',
            theme: 'relax',
            timeout: '3000',
            visibilityControl: true,
            callbacks: {
                beforeShow: function() { go_noty_close_oldest();},
            }
            
        }).show();
        }); 
    </script>";
}

/**
 * @param string $type
 * @param $title
 * @param $content
 */
function go_noty_message_generic ($type = 'alert', $title, $content, $timeout = false) {
    if (!empty($title)){
        $text = "<h3>" . $title . "</h3><div>" . $content . "</div>";
    }
    else{
        $text = $content;
    }
    //go_noty_close_oldest();
    echo "<script> 
        jQuery( document ).ready( function() { 
           new Noty({
                type: '" . $type . "',
                layout: 'topRight',
                text: '" . addslashes($text) . "',
                theme: 'relax',
                timeout: '" . $timeout . "',
                visibilityControl: true,
                callbacks: {
                    beforeShow: function() { go_noty_close_oldest();},
                }   
            }).show();
        });</script>";
}

/**
 * @param $user_id
 * @param $badges
 * @param $badge_count
 */
function go_update_totals_table_Badges($user_id, $badges, $badge_count)
{
    global $wpdb;
    $key = 'go_get_loot_' . $user_id;
    delete_transient($key);
    $go_totals_table_name = "{$wpdb->prefix}go_loot";

    //create row for user if none exists
    go_add_user_to_totals_table($user_id);

    $wpdb->query($wpdb->prepare("UPDATE {$go_totals_table_name} 
                    SET 
                        badges = '{$badges}',
                        badge_count = {$badge_count}                      
                    WHERE uid= %d", $user_id));
}

/**
 * @param $user_id
 * @param $groups
 */
function go_update_totals_table_Groups($user_id, $groups)
{
    global $wpdb;

    $key = 'go_get_loot_' . $user_id;
    delete_transient($key);

    $go_totals_table_name = "{$wpdb->prefix}go_loot";

    //create row for user if none exists
    go_add_user_to_totals_table($user_id);

    $wpdb->query($wpdb->prepare("UPDATE {$go_totals_table_name} 
                    SET 
                        groups = '{$groups}'                     
                    WHERE uid= %d", $user_id));
}

/**
 * @param $user_id
 * @param $xp
 * @param $xp_name
 * @param $gold
 * @param $gold_name
 * @param $health
 * @param $health_name
 * @param $notify
 * @param $debt
 */
function go_update_totals_table($user_id, $xp, $xp_name, $gold, $gold_name, $health, $health_name, $notify, $debt){
    global $wpdb;

    $key = 'go_get_loot_' . $user_id;
    delete_transient($key);
    wp_cache_delete( $key, 'go_single' );

    $go_totals_table_name = "{$wpdb->prefix}go_loot";

    //create row for user if none exists
    go_add_user_to_totals_table($user_id);
    if ($debt == true) {
        $wpdb->query($wpdb->prepare("UPDATE {$go_totals_table_name} 
                    SET 
                        xp = {$xp} + xp,
                        gold = {$gold} + gold,
                        health = {$health} + health                   
                    WHERE uid= %d", $user_id));
    }else{
        $wpdb->query($wpdb->prepare("UPDATE {$go_totals_table_name} 
                    SET 
                        xp = {$xp} + xp,
                        gold = GREATEST(({$gold} + gold), 0),
                        health = {$health} + health             
                    WHERE uid= %d", $user_id));
    }

    if ($xp != 0) {
        $new_rank = go_get_rank($user_id);
        $rank_num = $new_rank['rank_num'];
        $rank_name = $new_rank['current_rank'];
        $old_rank = get_user_option("go_rank", $user_id);
        if ($rank_num > $old_rank){
            update_user_option($user_id, "go_rank", $rank_num);
            go_noty_level_up($rank_num, $rank_name );
            echo "<script>var audio = new Audio( PluginDir.url + 'media/sounds/milestone2.mp3' ); audio.play();</script>";
        }

        if ($rank_num < $old_rank){
            update_user_option($user_id, "go_rank", $rank_num);
            go_noty_level_down($rank_num, $rank_name );
        }



    }



    if ($notify === true) {
        $up = false;
        $down = false;
        if ($xp > 0) {
            go_noty_loot_success($xp, $xp_name);
            $up = true;
        }
        else if ($xp < 0) {
            go_noty_loot_error($xp, $xp_name);
            $down = true;
        }

        if ($gold > 0) {
            go_noty_loot_success($gold, $gold_name);
            $up = true;
        }
        else if ($gold < 0) {
            go_noty_loot_error($gold, $gold_name);
            $down = true;
        }

        if ($health > 0) {
            go_noty_loot_success($health, $health_name);
            $up = true;
        }
        else if ($health < 0) {
            go_noty_loot_error($health, $health_name);
            $down = true;
        }

        if ($up == true){
            echo "<script>var audio = new Audio( PluginDir.url + 'media/sounds/coins.mp3' ); audio.play();</script>";
        }
        if ($down == true){
            echo "<script>var audio = new Audio( PluginDir.url + 'media/sounds/down.mp3' ); audio.play();</script>";
        }

    }
}

/**
 * @param $user_id
 * @param $xp
 * @param $xp_name
 * @param $gold
 * @param $gold_name
 * @param $health
 * @param $health_name
 */
function go_update_admin_bar_v4($user_id, $xp, $xp_name, $gold, $gold_name, $health, $health_name) {

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option( 'options_go_loot_health_toggle' );

    $user_loot = go_get_loot($user_id);

    if ($xp_toggle) {
        // the user's current amount of experience (points)
        //$go_current_xp = go_get_user_loot($user_id, 'xp');
        $go_current_xp = $user_loot['xp'];

        $rank = go_get_rank($user_id);
        $rank_num = $rank['rank_num'];
        $current_rank = $rank['current_rank'];
        $current_rank_points = $rank['current_rank_points'];
        $next_rank = $rank['next_rank'];
        $next_rank_points = $rank['next_rank_points'];

        if ($next_rank_points != false) {
            $rank_threshold_diff = $next_rank_points - $current_rank_points;
            $pts_to_rank_threshold = $go_current_xp - $current_rank_points;
            $pts_to_rank_up_str = "L{$rank_num}: {$pts_to_rank_threshold} / {$rank_threshold_diff}";
            $percentage = $pts_to_rank_threshold / $rank_threshold_diff * 100;
            $color = "#39b54a";
        } else {
            $pts_to_rank_up_str = $current_rank;
            $percentage = 100;
            $color = "gold";
        }
        if ( $percentage <= 0 ) {
            $percentage = 0;
        } else if ( $percentage >= 100 ) {
            $percentage = 100;
        }
        $progress_bar = '<div id="go_admin_bar_progress_bar_border" class="progress-bar-border">'.'<div id="go_admin_bar_progress_bar" class="progress_bar" '.
            'style="width: '.$percentage.'%; background-color: '.$color.' ;">'.
            '</div>'.
            '<div id="points_needed_to_level_up" class="go_admin_bar_text">'.
            $pts_to_rank_up_str.
            '</div>'.
            '</div>';
    }
    else {
        $progress_bar = '';
    }


    if($health_toggle) {
        // the user's current amount of bonus currency,
        // also used for coloring the admin bar
        //$go_current_health = go_get_user_loot($user_id, 'health');
        $go_current_health = $user_loot['health'];
        $health_percentage = intval($go_current_health / 2);
        if ($health_percentage <= 0) {
            $health_percentage = 0;
        } else if ($health_percentage >= 100) {
            $health_percentage = 100;
        }
        $health_bar = '<div id="go_admin_health_bar_border" class="progress-bar-border">' . '<div id="go_admin_bar_health_bar" class="progress_bar" ' . 'style="width: ' . $health_percentage . '%; background-color: red ;">' . '</div>' . '<div id="health_bar_percentage_str" class="go_admin_bar_text">' . "Health Mod: " . $go_current_health . "%" . '</div>' . '</div>';

    }
    else{
        $health_bar = '';
    }

    if ($gold_toggle) {
        // the user's current amount of currency
        //$go_current_gold = go_get_user_loot($user_id, 'gold');
        $go_current_gold = $user_loot['gold'];
        $gold_total = '<div id="go_admin_bar_gold_2" class="admin_bar_loot">' . go_display_shorthand_currency('gold', $go_current_gold)  . '</div>';
    }
    else{
        $gold_total = '';
    }

    ?><script language='javascript'>
        jQuery(document).ready(function() {
    <?php

    if (get_option('options_go_loot_xp_toggle')){
        //$suffix = get_option( "options_go_loot_xp_abbreviation" );
        /*

         $display = go_display_longhand_currency('xp', $go_current_xp) ;
        echo "jQuery( '#go_admin_bar_xp' ).html( '{$display}' );";
        echo "jQuery( '#go_admin_bar_progress_bar' ).css( {'width': '{$percentage}%', 'background-color' : '{$color}'} );";
        echo "jQuery( '#points_needed_to_level_up' ).html( '{$pts_to_rank_up_str}' );";
        $rank_str = $go_option_ranks . ' ' . $rank_num . ": " . $current_rank ;
        echo "jQuery( '#go_admin_bar_progress_bar' ).html( '{$rank_str}' );";
        */
        echo "jQuery( '#go_admin_bar_progress_bar_border' ).replaceWith( '{$progress_bar}' );";
    }
    if (get_option('options_go_loot_gold_toggle')){
        //$suffix = get_option( "options_go_loot_gold_abbreviation" );
        $display = go_display_longhand_currency('gold', $go_current_gold) ;
        $display_short = go_display_shorthand_currency('gold', $go_current_gold) ;
        echo "jQuery( '#go_admin_bar_gold' ).html( '{$display}' );";
        //echo "jQuery( '#go_admin_bar_rank' ).html( '{$rank_str}' );";
        echo "jQuery( '#go_admin_bar_gold' ).html( '{$display_short}' );";
        echo "jQuery( '#go_admin_bar_gold_2' ).html( '{$display_short}' );";
    }
    if (get_option('options_go_loot_health_toggle')){
        //$suffix = get_option( "options_go_loot_health_abbreviation" );
        $display = go_display_longhand_currency('health', $go_current_health) ;
        echo "jQuery( '#go_admin_bar_health' ).html( '{$display}' );";
        echo "jQuery( '#go_admin_bar_health_bar' ).css( {'width': '{$health_percentage}%'} );";
        $health_str = "Health Mod: " . $go_current_health. "%" ;
        echo "jQuery( '#health_bar_percentage_str' ).html( '{$health_str}' );";
    }

    echo "
			} );
		</script>";
}

/**
 * @param $user_id
 */
function go_add_user_to_totals_table($user_id){
    global $wpdb;
    $go_totals_table_name = "{$wpdb->prefix}go_loot";

    //create row for user if none exists
    $row_exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID 
					FROM {$go_totals_table_name} 
					WHERE uid = %d LIMIT 1",
            $user_id
        )
    );

    //create the row
    if ( $row_exists == null ) {
        $wpdb->insert(
            $go_totals_table_name,
            array(
                'uid' => $user_id
            )
        );
    }
}

function go_get_bonus_loot_rows($custom_fields, $health_mod = false, $user_id = 'guest'){


    if (!$health_mod) {//if the health mod was not passed, get it
        $health_mod = 1;//default mod
        $health_toggle = get_option('options_go_loot_health_toggle');
        if ($health_toggle) {
            if ($user_id != 'guest') {//if not a guest user
                $health_mod = go_get_health_mod($user_id);//get this users health level
            }
        }
    }

    //LOGIC
    //get bonus radio
    //if default, get default values
    //if custom, get custom
    //return rows found as values array

    $bonus_radio =(isset($custom_fields['bonus_loot_toggle'][0]) ? $custom_fields['bonus_loot_toggle'][0] : null);//is bonus set default, custom or off



    if ($bonus_radio == "1" || $bonus_radio == "default") {
        if ($bonus_radio == "1") {
            $key_prefix = 'bonus_loot_go_bonus_loot_';
            $row_count = (isset($custom_fields['bonus_loot_go_bonus_loot'][0]) ? $custom_fields['bonus_loot_go_bonus_loot'][0] : null);//number of loot drops
        }else if ($bonus_radio == "default"){
            $key_prefix = 'options_go_loot_bonus_loot_';
            $row_count = get_option('options_go_loot_bonus_loot');
        }


        $values = array();
        if (!empty($row_count)) {//if there are drop rows
            for ($i = 0; $i < $row_count; $i++) {//get the values for each row
                $message = $key_prefix . $i . "_message";
                $title = $key_prefix . $i . "_title";
                $xp = $key_prefix . $i . "_defaults_xp";
                $gold = $key_prefix . $i . "_defaults_gold";
                $health = $key_prefix . $i . "_defaults_health";
                $drop = $key_prefix . $i . "_defaults_drop_rate";

                if ($bonus_radio == "1") {
                    $title = (isset($custom_fields[$title][0]) ? $custom_fields[$title][0] : null);
                    $message = (isset($custom_fields[$message][0]) ? $custom_fields[$message][0] : null);
                    $xp = (isset($custom_fields[$xp][0]) ? $custom_fields[$xp][0] : null) * $health_mod;
                    $gold = (isset($custom_fields[$gold][0]) ? $custom_fields[$gold][0] : null) * $health_mod;
                    $health = (isset($custom_fields[$health][0]) ? $custom_fields[$health][0] : null);
                    $drop = (isset($custom_fields[$drop][0]) ? $custom_fields[$drop][0] : null);
                }else if($bonus_radio == "default"){
                    $title = get_option($title);
                    $message = get_option($message);
                    $xp = get_option($xp);;
                    $gold = get_option($gold);
                    $health = get_option($health);
                    $drop = get_option($drop);
                }

                $row_val = array('title' => $title, 'message' => $message, 'xp' => $xp, 'gold' => $gold, 'health' => $health, 'drop' => $drop);

                $values[] = $row_val;//stuff each row in to an array

            }
        }
        //sort by drop rate
        $bonus_option = array();
        foreach ($values as $key => $row) {
            $bonus_option[$key] = $row['drop'];
        }
        array_multisort($bonus_option, SORT_ASC, $values);

        return $values;
    }

    return array();
}



/**
 * @param $post_id
 */
function go_update_bonus_loot ($post_id){
    check_ajax_referer( 'go_update_bonus_loot' );
    $post_id = $_POST['post_id'];

    $user_id = get_current_user_id();
    global $wpdb;

    $go_actions_table_name = "{$wpdb->prefix}go_actions";

    //check to see if the bonus has been attempted by this user on this task before.  You can only get 1 bonus per task.
    $previous_bonus_attempt = $wpdb->get_var($wpdb->prepare("SELECT result 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND action_type = %s
                ORDER BY id DESC LIMIT 1", $post_id, $user_id, 'bonus_loot'));

    //ob_start();
    if(!empty($previous_bonus_attempt)) {
        //if(0==1){
        go_noty_message_generic('error', '', "You have previously attempted this bonus.  No award given.");


    }else {
        //if health toggle is on, get health.  Health affects the bonus.
        $health_toggle = get_option('options_go_loot_health_toggle');
        if ($health_toggle) {

            $health_mod = go_get_health_mod($user_id);

        } else {
            $health_mod = 1;
        }


        $custom_fields = get_post_custom($post_id);

        /*
        $row_count = (isset($custom_fields['bonus_loot_go_bonus_loot'][0]) ? $custom_fields['bonus_loot_go_bonus_loot'][0] : null);//number of loot drops
        $values = array();
        if (!empty($row_count)) {//if there are drop rows
            for ($i = 0; $i < $row_count; $i++) {//get the values for each row
                $message = "bonus_loot_go_bonus_loot_" . $i . "_message";
                $message = (isset($custom_fields[$message][0]) ? $custom_fields[$message][0] : null);
                $title = "bonus_loot_go_bonus_loot_" . $i . "_title";
                $title = (isset($custom_fields[$title][0]) ? $custom_fields[$title][0] : null);
                $xp = "bonus_loot_go_bonus_loot_" . $i . "_defaults_xp";
                $xp = (isset($custom_fields[$xp][0]) ? $custom_fields[$xp][0] : null) * $health_mod;
                $gold = "bonus_loot_go_bonus_loot_" . $i . "_defaults_gold";
                $gold = (isset($custom_fields[$gold][0]) ? $custom_fields[$gold][0] : null) * $health_mod;;
                $health = "bonus_loot_go_bonus_loot_" . $i . "_defaults_health";
                $health = (isset($custom_fields[$health][0]) ? $custom_fields[$health][0] : null);
                $drop = "bonus_loot_go_bonus_loot_" . $i . "_defaults_drop_rate";
                $drop = (isset($custom_fields[$drop][0]) ? $custom_fields[$drop][0] : null);

                $row_val = array('title' => $title, 'message' => $message, 'xp' => $xp, 'gold' => $gold, 'health' => $health, 'drop' => $drop);

                $values[] = $row_val;//stuff each row in to an array

            }

        }
        //sort by drop rate
        $bonus_option = array();
        foreach ($values as $key => $row) {
            $bonus_option[$key] = $row['drop'];
        }
        array_multisort($bonus_option, SORT_ASC, $values);
        */

        $values = go_get_bonus_loot_rows($custom_fields, $health_mod, $user_id);


        //add all the drop rates together
        //if greater than 100, treat them as ratios
        $drop_total = 0;
        foreach ($values as $value) {
            $drop_total = $value['drop'] + $drop_total;
        }
        if ($drop_total < 100){
            $drop_total = 100;
        }

        $drop_total = $drop_total * 1000;

        $winner = false;
        foreach ($values as $value) { //for each drop, test to award randomly
            $drop = $value['drop'] * 1000;

            $rand = mt_rand(0, $drop_total);
            if ( $rand <= $drop) {
                $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
                $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
                $health_abbr = get_option( "options_go_loot_health_abbreviation" );
                $xp = $value['xp'];
                if ($xp > 0){
                    $xp_message = $xp_abbr . ": " .  $xp . "<br>";
                }else {$xp_message = '';}
                $gold = $value['gold'];
                if ($gold > 0){
                    $gold_message = $gold_abbr . ": " .  $gold . "<br>";
                }else {$gold_message = '';}
                $health = $value['health'];
                if ($health > 0){
                    $health_message = $health_abbr . ": " .  $health . "<br>";
                }else {$health_message = '';}

                $title = $value['title'];
                $message = $value['message'];

                //$title = get_option('options_go_loot_bonus_loot_name');;
                $message = $message . "<br><br>" . $xp_message .  $gold_message . $health_message;
                go_noty_message_generic('success', $title, $message);
                //go_noty_loot_success($title,$message );
                go_update_actions($user_id, 'bonus_loot', $post_id, null, null, null, 'Bonus Loot Winner', null, null, null, $health_mod, $xp, $gold, $health, null, null, true, false);
                $winner = true;
                break;
            }
            $drop_total = $drop_total - $drop;
        }
        if (!$winner) {//NOT winner
            //add update here for no winner
            go_update_actions($user_id, 'bonus_loot', $post_id, null, null, null, 'Bonus Loot Not Winner', null, null, null, null, null, null, null, null, null, null, true, false);
            go_noty_message_generic('warning', "", "Better luck next time!");
        }
    }
    /*
    $buffer = ob_get_contents();
    ob_end_clean();

    // constructs the JSON response
    echo json_encode(
        array(
            'json_status' => 'success',
            'html' => $buffer
        )
    );
    */
    die();

}

?>