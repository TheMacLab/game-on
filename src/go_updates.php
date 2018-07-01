<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/29/18
 * Time: 10:40 PM
 */


function go_get_health_mod ($user_id){
    //set the health mod
    $is_logged_in = ! empty( $user_id ) && is_user_member_of_blog( $user_id ) ? true : false;
    $health_toggle = get_option('options_go_loot_health_toggle');
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

function go_get_user_loot ($user_id, $loot_type){
    //get health from totals table
    global $wpdb;
    $go_totals_table_name = "{$wpdb->prefix}go_loot";
    $loot = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT {$loot_type} 
					FROM {$go_totals_table_name} 
					WHERE uid = %d LIMIT 1",
            $user_id
        )
    );
    return $loot;
}

function go_update_stage_table ($user_id, $post_id, $custom_fields, $status, $bonus_status, $progressing, $result = null, $check_type = null ) {
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    $go_actions_table_name = "{$wpdb->prefix}go_actions";
    $is_logged_in = ! empty( $user_id ) && is_user_member_of_blog( $user_id ) ? true : false;
    $bonus_status = (isset($bonus_status) ?  $bonus_status : null);
    $status = (isset($status) ?  $status : null);
    $health_mod = null;
    $stage_mod = null;
    $time = current_time( 'mysql');
    $last_time = $time;

    $start_time = 'null';
    $xp = 0;
    $gold = 0;
    $health = 0;
    $c4 = 0;
    $action_type = 'task';
    if ($progressing === 'timer'){

        $start_time = $time;
        $new_status_task = 'null';
        $new_bonus_status_task = 'null';
        $new_bonus_status_actions = 0;
    }
    else if ($progressing === true) {
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
            }
            //else this is not a bonus stage and get the health mod first time it was attempted
            else {
                $temp_status = $status + 1;
                $original_health_mod = $wpdb->get_var($wpdb->prepare("SELECT global_mod 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND stage = %d AND NOT result = %s
                ORDER BY id DESC LIMIT 1", $post_id, $user_id, $temp_status, 'undo'));
            }
            //get current health mod from totals table
            $current_health_mod = go_get_health_mod ($user_id);


            if ($original_health_mod === null) {
                $health_mod =  $current_health_mod;
            } else if ($original_health_mod > $current_health_mod){
                $health_mod = $current_health_mod;
            }
            else {
                $health_mod = $original_health_mod;
            }
        } else {
            $health_mod = 1;
        }

        $quiz_mod = 0;

        //if not entry loot--it couldn't have a quiz
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
                //$mod_date = date('F j, Y \a\t g:i a\.', $mod_date_timestamp);
                $mod_date_offset = $mod_date_timestamp + (3600 * get_option('gmt_offset'));
                $current_timestamp = current_time('timestamp');
                $mod_percent = 'go_due_dates_mod_settings_' . $i . '_mod';
                $mod_percent = $custom_fields[$mod_percent][0];
                if ($current_timestamp > $mod_date_offset) {
                    //set the latest mod date if this is the first mod date
                    if ($mod_date_latest == null) {
                        $mod_date_latest = $mod_date_offset;
                        $due_date_mod = $mod_percent * .01;
                    } else if ($mod_date_offset > $mod_date_latest) {
                        $mod_date_latest = $mod_date_offset;
                        $due_date_mod = $mod_percent * .01;
                    }
                }
            }
        }

        $timer_mod = 0;
        $timer_on = $custom_fields['go_timer_toggle'][0];
        if ($timer_on && $is_logged_in) {
            $time_left = go_time_left($custom_fields, $user_id, $post_id);
            $current_date = time(); //current date and time
            $timer_time = $time_left - $current_date;
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



        $xp_toggle = get_option('options_go_loot_xp_toggle');
        $gold_toggle = get_option('options_go_loot_gold_toggle');
        //$health_toggle = get_option( 'options_go_loot_health_toggle' );
        $c4_toggle = get_option('options_go_loot_c4_toggle');

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
        if ($c4_toggle) {
            $c4_mod_toggle = get_option('options_go_loot_c4_mods_toggle');
        } else {
            $c4_mod_toggle = false;
        }


        if ($status === -1) {
            /// get entry loot
            $xp = $custom_fields['go_entry_rewards_xp'][0];
            $xp = go_mod_loot($xp, $xp_toggle, $xp_mod_toggle, $stage_mod, $health_mod);

            $gold = $custom_fields['go_entry_rewards_gold'][0];
            $gold = go_mod_loot($gold, $gold_toggle, $gold_mod_toggle, $stage_mod, $health_mod);

            $health = $custom_fields['go_entry_rewards_health'][0];
            $health = go_mod_loot($health, $health_toggle, $health_mod_toggle, $stage_mod, $health_mod);

            $c4 = $custom_fields['go_entry_rewards_c4'][0];
            $c4 = go_mod_loot($c4, $c4_toggle, $c4_mod_toggle, $stage_mod, $health_mod);

        } else if ($status !== null && $progressing === true) {
            /// get modified stage loot
            $xp = $custom_fields['go_stages_' . $status . '_rewards_xp'][0];
            $xp = go_mod_loot($xp, $xp_toggle, $xp_mod_toggle, $stage_mod, $health_mod);
            $gold = $custom_fields['go_stages_' . $status . '_rewards_gold'][0];
            $gold = go_mod_loot($gold, $gold_toggle, $gold_mod_toggle, $stage_mod, $health_mod);
            $health = $custom_fields['go_stages_' . $status . '_rewards_health'][0];
            $health = go_mod_loot($health, $health_toggle, $health_mod_toggle, $stage_mod, $health_mod);
            $c4 = $custom_fields['go_stages_' . $status . '_rewards_c4'][0];
            $c4 = go_mod_loot($c4, $c4_toggle, $c4_mod_toggle, $stage_mod, $health_mod);
        } else if ($bonus_status !== null && $progressing === true) {
            /// get modified bonus stage loot
            $xp = $custom_fields['go_bonus_stage_rewards_xp'][0];
            $xp = go_mod_loot($xp, $xp_toggle, $xp_mod_toggle, $stage_mod, $health_mod);
            $gold = $custom_fields['go_bonus_stage_rewards_gold'][0];
            $gold = go_mod_loot($gold, $gold_toggle, $gold_mod_toggle, $stage_mod, $health_mod);
            $health = $custom_fields['go_bonus_stage_rewards_health'][0];
            $health = go_mod_loot($health, $health_toggle, $health_mod_toggle, $stage_mod, $health_mod);
            $c4 = $custom_fields['go_bonus_stage_rewards_c4'][0];
            $c4 = go_mod_loot($c4, $c4_toggle, $c4_mod_toggle, $stage_mod, $health_mod);
        }
        //make sure we don't go over 200 health
        $health = go_health_to_add($user_id, $health);
    }
    //end progressing = true
    else if ($progressing === false){
        $action_type = 'undo_task';
        if ($status !== null){
            $new_status_task = $status - 1 ;
            $new_status_actions = $status;
        }
        else {
            $new_status_task = 'null';
            $new_status_actions = 'null';
        }

        if ($bonus_status !== null){
            $new_bonus_status_task = $bonus_status - 1;
            $new_bonus_status_actions = $bonus_status;
        }
        else {
            $new_bonus_status_task = 'null';
            $new_bonus_status_actions = 'null';
        }
        /// get last action loot
        $xp = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT xp 
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $status
            )
        ) * -1;
        $gold = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT gold 
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $status
            )
        )  * -1;
        $health = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT health
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $status
            )
        )  * -1;
        $c4 = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT c4
					FROM {$go_actions_table_name} 
					WHERE uid = %d and source_id  = %d and stage = %d 
					ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $status
            )
        )  * -1 ;
        //make sure we don't go over 200 health
        $health = go_health_to_add($user_id, $health);

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
                        c4 = {$c4} + c4,
                        last_time = IFNULL('{$last_time}', last_time) ,
                        start_time = IFNULL('{$start_time}', start_time)                  
                    WHERE uid= %d AND post_id=%d ",
            $user_id,
            $post_id
        )
    );



    go_update_actions( $user_id, $action_type,  $post_id, $new_status_actions, $new_bonus_status_actions, $check_type, $result, $quiz_mod, $due_date_mod, $timer_mod, $health_mod,  $xp, $gold, $health, $c4, null, true);

}

function go_mod_loot($loot, $toggle, $mod_toggle, $stage_mod, $health_mod)
{
    $loot = ($loot - ($loot * $stage_mod));
    if ($mod_toggle) {
        $loot = $loot * $health_mod;
    } else if (!$toggle) {
        $loot = 0;
    }
    return $loot;
}


//makes sure health doesn't go over 200
function go_health_to_add( $user_id, $added_health){
    global $wpdb;
    $current_health = go_get_user_loot( $user_id, 'health' );
    $max_new_health = 200 - $current_health;

    if ($max_new_health < $added_health){
        $added_health = $max_new_health;
    }
    return $added_health;
}

function go_update_actions( $user_id, $type,  $source_id, $status, $bonus_status, $check_type, $result, $quiz_mod, $late_mod, $timer_mod, $global_mod, $xp, $gold, $health, $c4, $badges, $notify){
    global $wpdb;

    if (get_option('options_go_loot_xp_toggle') == false){
        $xp = 0;
    }
    if (get_option('options_go_loot_gold_toggle') == false){
        $gold = 0;
    }
    if (get_option('options_go_loot_health_toggle') == false){
        $health = 0;
    }
    if (get_option('options_go_loot_c4_toggle') == false){
        $c4 = 0;
    }

    $xp_name = null;
    $gold_name = null;
    $health_name = null;
    $c4_name = null;

    // the user's current amount of experience (points)
    $go_current_xp = go_get_user_loot( $user_id, 'xp' );
    $new_xp_total = $go_current_xp + $xp;

    // the user's current amount of currency
    $go_current_gold = go_get_user_loot( $user_id, 'gold' );
    $new_gold_total = $go_current_gold + $gold;

    // the user's current amount of bonus currency,
    // also used for coloring the admin bar
    $go_current_health = go_get_user_loot( $user_id, 'health' );
    $new_health_total = $go_current_health + $health;
    if ($new_health_total < 0) {
        $new_health_total = 0;
        $health = $go_current_health * -1;
    }
    else if ($new_health_total > 200) {
        $new_health_total = 200;
        $health = 200 -$go_current_health;
    }

    // the user's current amount of minutes
    $go_current_c4 = go_get_user_loot( $user_id, 'c4' );
    $new_c4_total = $go_current_c4 + $c4;

    $go_actions_table_name = "{$wpdb->prefix}go_actions";
    $time = date( 'Y-m-d G:i:s', current_time( 'timestamp', 0 ) );
    $wpdb->insert(
        $go_actions_table_name,
        array(
            'uid' => $user_id,
            'action_type' => $type,
            'source_id' => $source_id,
            'TIMESTAMP' => $time,
            'stage' => $status,
            'bonus_status' => $bonus_status,
            'check_type' => $check_type,
            'result' => $result,
            'quiz_mod' => $quiz_mod,
            'late_mod' => $late_mod,
            'timer_mod' => $timer_mod,
            'global_mod' => $global_mod,
            'xp' => $xp,
            'gold' => $gold,
            'health' => $health,
            'c4' => $c4,
            'xp_total' => $new_xp_total,
            'gold_total' => $new_gold_total,
            'health_total' => $new_health_total,
            'c4_total' => $new_c4_total
        )
    );
    if ($notify === true) {
        $xp_name = get_option('options_go_loot_xp_name');
        $gold_name = get_option('options_go_loot_gold_name');
        $health_name = get_option('options_go_loot_health_name');
        $c4_name = get_option('options_go_loot_c4_name');
        go_update_admin_bar_v4($user_id, $new_xp_total, $xp_name, $new_gold_total, $gold_name, $new_health_total, $health_name, $new_c4_total, $c4_name);
    }
    go_update_totals_table($user_id, $xp, $xp_name, $gold, $gold_name, $health, $health_name, $c4, $c4_name, null, $notify);

}

function go_noty_loot_success ($loot, $loot_type) {
    echo "<script> new Noty({
    type: 'success',
    layout: 'topRight',
    text: '<div style=\"font-size: 1.5em;\">" . $loot_type . ": " . $loot . "</div>',
    theme: 'relax',
    timeout: '3000'
    
}).show();</script>";
}

function go_noty_loot_error ($loot, $loot_type) {
    echo "<script> new Noty({
    type: 'error',
    layout: 'topRight',
    text: '<div style=\"font-size: 1.5em;\">" . $loot_type . ": " . $loot . "</div>',
    theme: 'relax',
    timeout: '3000'
    
}).show();</script>";
}

function go_update_totals_table($user_id, $xp, $xp_name, $gold, $gold_name, $health, $health_name, $c4, $c4_name, $badge_count, $notify){
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
        go_add_user_to_totals_table ($user_id);
    }

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$go_totals_table_name} 
                    SET 
                        xp = {$xp} + xp,
                        gold = {$gold} + gold,
                        health = {$health} + health,
                        c4 = {$c4} + c4,
                        badge_count = {$badge_count} + badge_count                       
                    WHERE uid= %d",
            $user_id
        )
    );

    if ($notify === true) {
        if ($xp > 0) {
            go_noty_loot_success($xp, $xp_name);
        }
        else if ($xp < 0) {
            go_noty_loot_error($xp, $xp_name);
        }

        if ($gold > 0) {
            go_noty_loot_success($gold, $gold_name);
        }
        else if ($gold < 0) {
            go_noty_loot_error($gold, $gold_name);
        }

        if ($health > 0) {
            go_noty_loot_success($health, $health_name);
        }
        else if ($health < 0) {
            go_noty_loot_error($health, $health_name);
        }

        if ($c4 > 0) {
            go_noty_loot_success($c4, $c4_name);
        }
        else if ($c4 < 0) {
            go_noty_loot_error($c4, $c4_name);
        }
        echo "<script>var audio = new Audio( PluginDir.url + 'media/gold.mp3' ); audio.play();</script>";


    }


}

function go_update_admin_bar_v4( $user_id, $xp, $xp_name, $gold, $gold_name, $health, $health_name, $c4, $c4_name) {
    //$user_id = get_current_user_id();

    $rank = go_get_rank( $user_id );
    $rank_num = $rank['rank_num'];
    $current_rank = $rank['current_rank'];
    $current_rank_points = $rank['current_rank_points'];
    //$next_rank = $rank['next_rank'];
    $next_rank_points = $rank['next_rank_points'];

    $go_option_ranks = get_option( 'options_go_loot_xp_levels_name_singular' );

    if ( $next_rank_points != false ) {
        $rank_threshold_diff = $next_rank_points - $current_rank_points;
        $pts_to_rank_threshold = $xp - $current_rank_points;
        $pts_to_rank_up_str = "L{$rank_num}: {$pts_to_rank_threshold} / {$rank_threshold_diff}";
        $percentage = $pts_to_rank_threshold / $rank_threshold_diff * 100;
        //$color = barColor( $go_current_health, 0 );
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

    $health_percentage = intval($health / 2);
    if ( $health_percentage <= 0 ) {
        $health_percentage = 0;
    } else if ( $health_percentage >= 100 ) {
        $health_percentage = 100;
    }

    ?><script language='javascript'>
			jQuery(document).ready(function() {
	<?php

    if (get_option('options_go_loot_xp_toggle')){
        //$suffix = get_option( "options_go_loot_xp_abbreviation" );
        $display = go_display_longhand_currency('xp', $xp) ;
        echo "jQuery( '#go_admin_bar_xp' ).html( '{$display}' );";
        echo "jQuery( '#go_admin_bar_progress_bar' ).css( {'width': '{$percentage}%', 'background-color' : '{$color}'} );";
        echo "jQuery( '#points_needed_to_level_up' ).html( '{$pts_to_rank_up_str}' );";
        $rank_str = $go_option_ranks . ' ' . $rank_num . ": " . $current_rank ;
        echo "jQuery( '#go_admin_bar_rank' ).html( '{$rank_str}' );";
    }
    if (get_option('options_go_loot_gold_toggle')){
        //$suffix = get_option( "options_go_loot_gold_abbreviation" );
        $display = go_display_longhand_currency('gold', $gold) ;
        $display_short = go_display_shorthand_currency('gold', $gold) ;
        echo "jQuery( '#go_admin_bar_gold' ).html( '{$display}' );";
        echo "jQuery( '#go_admin_bar_rank' ).html( '{$rank_str}' );";
        echo "jQuery( '#go_admin_bar_gold' ).html( '{$display_short}' );";
    }
    if (get_option('options_go_loot_health_toggle')){
        //$suffix = get_option( "options_go_loot_health_abbreviation" );
        $display = go_display_longhand_currency('health', $health) ;
        echo "jQuery( '#go_admin_bar_health' ).html( '{$display}' );";
        echo "jQuery( '#go_admin_bar_health_bar' ).css( {'width': '{$health_percentage}%'} );";
        $health_str = "Health Mod: " . $health. "%" ;
        echo "jQuery( '#health_bar_percentage_str' ).html( '{$health_str}' );";
    }
    if (get_option('options_go_loot_c4_toggle')){
        //$suffix = get_option( "options_go_loot_c4_abbreviation" );
        $display = go_display_longhand_currency('c4', $c4) ;
        $display_short = go_display_shorthand_currency('c4', $c4) ;
        echo "jQuery( '#go_admin_bar_c4' ).html( '{$display}' );";
        echo "jQuery( '#go_admin_bar_c4' ).html( '{$display_short}' );";
    }

    echo "
			} );
		</script>";
}




function go_add_user_to_totals_table($user_id){
    global $wpdb;
    $go_totals_table_name = "{$wpdb->prefix}go_loot";
    $wpdb->insert(
        $go_totals_table_name,
        array(
            'uid' => $user_id
        )
    );
}
function go_add_user_to_totals_table_login($user_login, $user){
    global $wpdb;
    $go_totals_table_name = "{$wpdb->prefix}go_loot";
    $user_id = $user -> ID ;
    $wpdb->insert(
        $go_totals_table_name,
        array(
            'uid' => $user_id
        )
    );
}
add_action('wp_login', 'go_add_user_to_totals_table_login', 10, 2);
