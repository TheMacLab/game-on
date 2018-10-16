<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/14/18
 * Time: 1:02 PM
 */

///////////////////////
//Ajax and tasks
/**
 * @param $task_id
 * @param null $user_id
 * @return int|null
 */
function go_get_bonus_status($task_id, $user_id = null ) {
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";

    if ( empty( $task_id ) ) {
        return null;
    }

    if ( empty( $user_id ) ) {
        $user_id = get_current_user_id();
    } else {
        $user_id = (int) $user_id;
    }

    $task_count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT bonus_status
			FROM {$go_task_table_name} 
			WHERE uid = %d AND post_id = %d",
            $user_id,
            $task_id
        )
    );

    if ( null !== $task_count && ! is_int( $task_count ) ) {
        $task_count = (int) $task_count;
    }

    return $task_count;
}

/**
 * go_print_bonus_stage
 * @param $user_id
 * @param $post_id
 * @param $custom_fields
 * @param $task_name
 */
function go_print_bonus_stage ($user_id, $post_id, $custom_fields){
    $bonus_status = go_get_bonus_status($post_id, $user_id);
    $content = (isset($custom_fields['go_bonus_stage_content'][0]) ?  $custom_fields['go_bonus_stage_content'][0] : null);
    $content  = apply_filters( 'go_awesome_text', $content );

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

/**
 * @param $user_id
 * @param $post_id
 * @param $custom_fields
 * @param $stage_count
 * @param $status
 */
function go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    //$custom_fields = get_post_custom( $post_id );
    $task_name = strtolower( get_option( 'options_go_tasks_name_singular' ) );
    $outro_message = (isset($custom_fields['go_outro_message'][0]) ?  $custom_fields['go_outro_message'][0] : null);
    //$outro_message = do_shortcode($outro_message);
    $outro_message  = apply_filters( 'go_awesome_text', $outro_message );
    $loot = $wpdb->get_results ("SELECT * FROM {$go_task_table_name} WHERE uid = {$user_id} AND post_id = {$post_id}" );
    $loot = $loot[0];
    if (get_option( 'options_go_loot_xp_toggle' )){
        $xp_on = true;
        $xp_name = get_option('options_go_loot_xp_name');
        $xp_loot = $loot->xp;
    }
    if (get_option( 'options_go_loot_gold_toggle' )){
        $gold_on = true;
        $gold_name = get_option('options_go_loot_gold_name');
        $gold_loot = $loot->gold;
    }
    if (get_option( 'options_go_loot_health_toggle' )){
        $health_on = true;
        $health_name = get_option('options_go_loot_health_name');
        $health_loot = $loot->health;
    }
    if (get_option( 'options_go_badges_toggle' )){
        //$badges_on = true;
        //$badges_name = get_option('options_go_badges_name_plural');
        $badges = $loot->badges;
        $badges = unserialize($badges);
    }
    //$groups_loot = $loot->groups;
    echo "<div id='outro' class='go_checks_and_buttons'>";
    echo "    
        <h3>" . ucwords($task_name) . " Complete!</h3>
        <p>".$outro_message."</p>
        
        
        <h4>Rewards</h4>
        <div class='go_task_rewards'>
        <div class='go_task_reward'>
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
    echo " </div>";

    go_display_stage_badges($badges);


    echo "</div>";
    if ($custom_fields['bonus_loot_toggle'][0]) {
        global $wpdb;
        $go_actions_table_name = "{$wpdb->prefix}go_actions";
        $previous_bonus_attempt = $wpdb->get_var($wpdb->prepare("SELECT result 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND action_type = %s
                ORDER BY id DESC LIMIT 1", $post_id, $user_id, 'bonus_loot'));
        //ob_start();
        if(empty($previous_bonus_attempt)) {
            go_bonus_loot();
        }

    }

    $bonus_status = go_get_bonus_status($post_id, $user_id);
    if ($bonus_status == 0){
        go_buttons($user_id, $custom_fields, null, $stage_count, $status, 'show_bonus', false, null, null, true);
    }
    echo "</div>";
    if ($bonus_status > 0){
        go_print_bonus_stage ($user_id, $post_id, $custom_fields);
    }
}

/**
 * TIMER
 * @param $custom_fields
 * @param $is_logged_in
 * @param $user_id
 * @param $post_id
 * @param $task_name
 * @return bool
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
