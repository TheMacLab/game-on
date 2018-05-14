<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/29/18
 * Time: 10:40 PM
 */

function go_update_stage_progress($go_table_name, $user_id, $post_id, $status, $xp, $gold, $health, $c4, $complete ){
    global $wpdb;

    $time = date( 'Y-m-d G:i:s', current_time( 'timestamp', 0 ) );
    $status = $status + 1;

    $wpdb->update(
        $go_table_name,
        array(
            'last_time' => $time,
            'status' => $status,
            'complete' => $complete
        ),
        array(
            'uid' => $user_id,
            'post_id' => $post_id
        ));
}

function go_update_stage_bonus($go_table_name, $user_id, $post_id, $bonus_count, $xp, $gold, $health, $c4, $complete ){
    global $wpdb;

    $time = date( 'Y-m-d G:i:s', current_time( 'timestamp', 0 ) );
    $bonus_count = $bonus_count + 1;

    $wpdb->update(
        $go_table_name,
        array(
            'last_time' => $time,
            'bonus_count' => $bonus_count,
            'complete' => $complete
        ),
        array(
            'uid' => $user_id,
            'post_id' => $post_id
        ));
}

function go_update_stage_undo($go_table_name, $user_id, $post_id, $status, $xp, $gold, $health, $c4, $complete ){
    global $wpdb;

    $time = date( 'Y-m-d G:i:s', current_time( 'timestamp', 0 ) );
    $status = $status -1;

    $wpdb->update(
        $go_table_name,
        array(
            'last_time' => $time,
            'status' => $status,
            'complete' => $complete
        ),
        array(
            'uid' => $user_id,
            'post_id' => $post_id
        ));
}

function go_update_actions($go_actions_table_name, $user_id, $type,  $source_id, $time, $status, $check_type, $result, $stage_mod, $global_mod,  $xp, $gold, $health, $c4, $xp_total, $gold_total, $health_total, $c4_total ){
    global $wpdb;
    $wpdb->insert(
        $go_actions_table_name,
        array(
            'uid' => $user_id,
            'type' => $type,
            'source_id' => $source_id,
            'TIMESTAMP' => $time,
            'stage' => $status,
            'check_type' => $check_type,
            'result' => $result,
            'stage_mod' => $stage_mod,
            'global_mod' => $global_mod,
            'xp' => $xp,
            'gold' => $gold,
            'health' => $health,
            'c4' => $c4,
            'xp_total' => $xp_total,
            'gold_total' => $gold_total,
            'health_total' => $health_total,
            'c4_total' => $c4_total
        )
    );
}

function go_update_totals_table($go_totals_table_name, $user_id, $xp_total, $gold_total, $health_total, $c4_total, $badge_count){
    global $wpdb;

    $wpdb->update(
        $go_totals_table_name,

        array(
            'xp' => $xp_total,
            'gold' => $gold_total,
            'health' => $health_total,
            'c4' => $c4_total,
            'badge_count' => $badge_count
        ),
        array(
            'uid' => $user_id
        )
    );
}

function go_add_user_to_totals_table (){
    global $wpdb;
    $go_totals_table_name = "{$wpdb->prefix}go_totals";
    $user_id = get_current_user_id();
    $wpdb->insert(
        $go_totals_table_name,
        array(
            'uid' => $user_id
        )
    );
}
add_action('wp_login', 'go_add_user_to_totals_table');
