<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 10:11 PM
 */

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
    //$custom_fields = get_post_custom( $post_id ); // Just gathering some data about this task with its post id
    $go_task_data = go_post_data($post_id); //0--name, 1--status, 2--permalink, 3--metadata
    $custom_fields = $go_task_data[3];

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

    $admin_name = 'an administrator';
    $is_admin = go_user_is_admin( $user_id );

    $admin_view = ($is_admin ?  get_user_option('go_admin_view', $user_id) : null);

    //user status
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
            'url'	=> get_site_url(), //ok
            //'status'	=>  $status,
            'userID'	=>  $user_id, //ok
            'ID'	=>  $post_id, //ok
            //'homeURL'	=>  home_url(),
            'redirectURL'	=> $redirect_url, //ok
            'admin_name'	=>  $admin_name, //ok
            'go_unlock_stage'	=>  $task_shortcode_nonces['go_unlock_stage'], //ok
            'go_task_change_stage'	=>  $task_shortcode_nonces['go_task_change_stage'], //ok

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
        echo "</div>";
        return null;
    }

    /**
     * Admin Views & Locks
     */
    $admin_flag = go_admin_content($post_id, $is_admin, $admin_view, $custom_fields, $is_logged_in, $task_name, $status, $user_id, $post_id, $badge_name);

    if ($admin_flag == 'stop') {
        echo "</div>";
        return null;
    }

    /**
     * LOCKS
     */
    if (!$is_unlocked) { //if not previously unlocked with a password
        if (!$is_admin || $admin_flag == 'locks') {
            $task_is_locked = go_display_locks($post_id, $user_id, $is_admin, $task_name, $badge_name, $custom_fields, $is_logged_in, $uc_task_name);
            if ($task_is_locked) {
                //Print the bottom of the page
                go_task_render_chain_pagination( $post_id, $custom_fields );
                go_hidden_footer();
                echo "</div>";
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
            $wpdb->insert($go_task_table_name, array('uid' => $user_id, 'post_id' => $post_id, 'status' => 0, 'last_time' => $time, 'xp' => 0, 'gold' => 0, 'health' => 0, 'start_time' => $time));
        }
    }
    /**
     * Display Rewards before task content
     * This is the list of rewards at the top of the task.
     */
    go_display_rewards( $custom_fields, $task_name, true, $user_id, 'top' );

    /**
     * Timer
     */
    $locks_status = go_display_timer ($custom_fields, $is_logged_in, $user_id, $post_id, $task_name );
    if ($locks_status){

        echo "</div>";
        go_task_render_chain_pagination ( $post_id, $custom_fields );
        go_hidden_footer ();
        echo "</div>";
        return null;
    }

    /**
     * Entry reward
     * Note: If the timer is on, the reward entry is given when the timer is started.
     *
     */
    if ($status === -1 || $status === -2){
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
    go_task_render_chain_pagination( $post_id, $custom_fields );//3 Queries
    go_hidden_footer();

    //Print comments
    if ( get_post_type() == 'tasks' ) {
        comments_template();
        wp_list_comments();
    }
}
add_shortcode( 'go_task','go_task_shortcode' );

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
 * @param $all_content
 */
function go_print_bonus_stage ($user_id, $post_id, $custom_fields, $all_content){
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

    if (!$all_content) {
        while ($i <= $bonus_status && $repeat_max > $i) {


            //Print Checks for Understanding for the last stage message printed and buttons
            go_checks_for_understanding($custom_fields, $i, null, $user_id, $post_id, true, $bonus_status, $repeat_max, $all_content);
            $i++;
        }
    }else{
        go_checks_for_understanding($custom_fields, $i, null, $user_id, $post_id, true, $bonus_status, $repeat_max, $all_content);
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
function go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status, $all_content){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    //$custom_fields = get_post_custom( $post_id );
    $task_name = strtolower( get_option( 'options_go_tasks_name_singular' ) );
    $outro_message = (isset($custom_fields['go_outro_message'][0]) ?  $custom_fields['go_outro_message'][0] : null);
    //$outro_message = do_shortcode($outro_message);
    $outro_message  = apply_filters( 'go_awesome_text', $outro_message );
    echo "<div id='outro' class='go_checks_and_buttons'>";
    echo "    
        <h3>" . ucwords($task_name) . " Complete!</h3>
        <p>" . $outro_message . "</p>
        You earned:";


    if (!$all_content) {
        echo "
        <div class='go_task_rewards'>
        <div class='go_task_reward'>";
        

        $loot = $wpdb->get_results("SELECT * FROM {$go_task_table_name} WHERE uid = {$user_id} AND post_id = {$post_id}");
        $loot = $loot[0];
        if (get_option('options_go_loot_xp_toggle')) {
            $xp_on = true;
            $xp_name = get_option('options_go_loot_xp_name');
            $xp_loot = $loot->xp;
        }
        if (get_option('options_go_loot_gold_toggle')) {
            $gold_on = true;
            $gold_name = get_option('options_go_loot_gold_name');
            $gold_loot = $loot->gold;
        }
        if (get_option('options_go_loot_health_toggle')) {
            $health_on = true;
            $health_name = get_option('options_go_loot_health_name');
            $health_loot = $loot->health;
        }

        if (isset($xp_on)) {
            echo "{$xp_loot} {$xp_name} ";
        }
        if (isset($gold_on)) {
            echo "<br>{$gold_loot} {$gold_name} ";
        }
        if (isset($health_on)) {
            echo "<br>{$health_loot} {$health_name} ";
        }
        echo "</div>";
        if (get_option('options_go_badges_toggle')) {
            //$badges_on = true;
            //$badges_name = get_option('options_go_badges_name_plural');
            $badges = $loot->badges;
            //if($badges) {
            //   $badges = unserialize($badges);
            // }
            go_display_stage_badges($badges);
            echo "</div>";
        }
    }else{
        go_display_rewards( $custom_fields, $task_name, false, $user_id, 'bottom');
    }

    $bonus_loot_radio = (isset($custom_fields['bonus_loot_toggle'][0]) ? $custom_fields['bonus_loot_toggle'][0] : false);//number of loot drops;
    if ($bonus_loot_radio == true || $bonus_loot_radio == 'default' ) {
        global $wpdb;
        $go_actions_table_name = "{$wpdb->prefix}go_actions";
        $previous_bonus_attempt = $wpdb->get_var($wpdb->prepare("SELECT result 
                FROM {$go_actions_table_name} 
                WHERE source_id = %d AND uid = %d AND action_type = %s
                ORDER BY id DESC LIMIT 1", $post_id, $user_id, 'bonus_loot'));
        //ob_start();
        if(empty($previous_bonus_attempt)) {
            go_bonus_loot($custom_fields, $user_id);
        }

    }



    $bonus_status = go_get_bonus_status($post_id, $user_id);
    if ($bonus_status <= 0){
        go_buttons($user_id, $custom_fields, null, $stage_count, $status, 'show_bonus', false, null, null, true);
    }
    echo "</div>";
    if ($bonus_status > 0 || $all_content){
        go_print_bonus_stage ($user_id, $post_id, $custom_fields, $all_content);
    }
}

function go_print_bonus_loot_possibilities($custom_fields, $user_id){

    $rows = go_get_bonus_loot_rows($custom_fields, false, $user_id);

    if (count($rows) >0) {
        echo "<ul>";
        foreach ($rows as $row) {
            $title = (isset($row['title']) ? $row['title'] : null);
            echo "<li>";
            echo $title . "";
            //$message = (isset($row['title']) ? $row['title'] : null);
            if (go_get_loot_toggle( 'xp')){
                $loot = (isset($row['$xp']) ? $row['$xp'] : null);
                if ($loot > 0) {
                    $name = go_get_loot_short_name('xp');

                    echo " " . $loot . " " . $name;
                }
            }
            if (go_get_loot_toggle( 'gold')){
                $loot = (isset($row['gold']) ? $row['gold'] : null);
                if ($loot > 0) {
                    $name = go_get_loot_short_name('gold');
                    echo " " . $loot . " " . $name;
                }
            }
            if (go_get_loot_toggle( 'health')){
                $loot = (isset($row['health']) ? $row['health'] : null);
                if ($loot > 0) {
                    $name = go_get_loot_short_name('health');
                    echo " " . $loot . " " . $name;
                }
            }

            //$drop = get_option($drop);



            echo "</li>";
        }
        echo "</ul>";
    }

}




/**
 * LOCKS
 * prevents all visitors both logged in and out from accessing the task content,
 * if they do not meet the requirements.
 * The task_locks function will set the output for the locks
 * and set the task_is_locked variable to true if it is locked.
 *
 * @param $post_id
 * @param $user_id
 * @param $is_admin
 * @param $task_name
 * @param $badge_name
 * @param $custom_fields
 * @param $is_logged_in
 * @param $uc_task_name
 * @return bool
 */
function go_display_locks ($post_id, $user_id, $is_admin, $task_name, $badge_name, $custom_fields, $is_logged_in, $uc_task_name){

    $task_is_locked = false;
    if ($custom_fields['go-location_map_toggle'][0] == true && !empty($custom_fields['go-location_map_loc'][0])){
        $on_map = true;
    }
    else{
        $on_map = false;
    }
    if ($custom_fields['go_lock_toggle'][0] == true || $custom_fields['go_sched_toggle'][0] == true || $on_map == true) {
        $task_is_locked = go_task_locks($post_id, $user_id, $task_name, $custom_fields, $is_logged_in, false);
    }

    //if it is locked, show master password field and stop printing of the task.
    if ($is_logged_in) {
        $go_password_lock = (isset($custom_fields['go_password_lock'][0]) ? $custom_fields['go_password_lock'][0] : null);
        if ($go_password_lock == true) {
            $task_is_locked = true;
        }

        //Get option (show password field) from custom fields
        if ($go_password_lock) {
            //Show password unlock
            echo "<div class='go_lock'><h3>Unlock {$uc_task_name}</h3><input id='go_result' class='clickable' type='password' placeholder='Enter Password'>";
            go_buttons($user_id, $custom_fields, null, null, null, 'unlock', false, null, null, false);
            echo "</div>";

        } else if ($task_is_locked == true) {
            //if ($is_logged_in) { //add of show password field is on
            ?>
            <div id="go_admin_override" style="overflow: auto; width: 100%;">
                <div style="float: right; font-size: .8em;">Admin Override</div>
            </div>
            <?php
            //Show password unlock
            echo "<div class='go_lock go_password' style='display: none;'><h3>Admin Override</h3><p>This field is not for users. Do not ask for this password. It is not part of the gameplay.</p><input id='go_result' class='clickable' type='password' placeholder='Enter Password'>";
            go_buttons($user_id, $custom_fields, null, null, null, 'unlock', false, null, null, false);
            echo "</div>";

            //}
        }
    }
    return $task_is_locked;

}


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
    if ($guest_access == "blocked" ) {
        echo "<div><h2 class='go_error_red'>You must be logged in to view this content.</h2></div>";
        return null;
    }


    go_display_rewards( $custom_fields, $task_name, true  , null, 'top');
    go_due_date_mods ($custom_fields, false, $task_name );

    $task_is_locked = false;
    if ($guest_access == "regular" ) {
        //echo "Regular";
        $task_is_locked = go_display_locks($post_id, null, false, $task_name, $badge_name, $custom_fields, false, $uc_task_name);

    }


    if (( $guest_access == "regular" && !$task_is_locked) || $guest_access == "open"){
        echo "<script> 
        jQuery( document ).ready( function() { 
           new Noty({
                type: 'error',
                layout: 'topCenter',
                text: 'You are viewing this page as a Guest. <br>You can view the content, but gameplay is disabled.',
                theme: 'relax',
                timeout: '3000',
                visibilityControl: true,  
            }).show();
           jQuery('#go_all_content input, #go_all_content textarea').attr('disabled', 'disabled');
        });</script>";

        echo "<div id='go_all_content'>";
        go_hidden_blog_post();


        go_display_visitor_messages($custom_fields, $post_id);
        go_print_outro (null, $post_id, $custom_fields, 1, 1, true);

        // displays the chain pagination list so that visitors can still navigate chains easily
        go_task_render_chain_pagination( $post_id, $custom_fields );

        go_hidden_footer();
        echo "</div>";

    }
    return null;



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
        go_checks_for_understanding ($custom_fields, $i, $i, null, $post_id, null, null, null, true);

        $i++;
    }
}


/**
 * Used to create a hidden post for the guest views.  It makes it so the other blog forms don't load MCE.
 */
function go_hidden_blog_post(){
    echo "<div style='display:none;'>";
    wp_editor('', 'go_blog_post' );
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
    echo "<script> 
        jQuery( document ).ready( function() { 
           new Noty({
                type: 'error',
                layout: 'topCenter',
                text: 'You are in \"All Stage\" view mode. Gameplay is disabled.',
                theme: 'relax',
                timeout: '8000',
                visibilityControl: true,  
            }).show();
           
           jQuery('#go_all_content input, #go_all_content textarea').attr('disabled', 'disabled');
        });
        
        </script>";

    echo "<div id='go_all_content'>";
    go_hidden_blog_post();
    go_display_rewards( $custom_fields, $task_name, true , $user_id, 'top');
    go_due_date_mods ($custom_fields, $is_logged_in, $task_name );
    //Print messages
    $i = 0;
    $stage_count = $custom_fields['go_stages'][0];
    while (  $stage_count > $i) {
        go_print_1_message ( $custom_fields, $i );
        go_checks_for_understanding ($custom_fields, $i, $i, $user_id, $post_id, null, null, null, true);

        $i++;
    }
    go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status, true);
    /*
    $bonus_count = $custom_fields['go_bonus_limit'][0];
    if ($bonus_count > 0){
        go_print_bonus_stage ($user_id, $post_id, $custom_fields, true);
    }
    */

    // displays the chain pagination list so that visitors can still navigate chains easily
    go_task_render_chain_pagination( $post_id, $custom_fields);
    go_hidden_footer();
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
            //$mod_date_timestamp = $mod_date_timestamp + (3600 * get_option('gmt_offset'));
            $current_timestamp = current_time( 'timestamp' );
            ////$current_time = current_time( 'mysql' );
            $mod_percent = 'go_due_dates_mod_settings_'.$i.'_mod';
            $mod_percent = $custom_fields[$mod_percent][0];
            if ($current_timestamp > $mod_date_timestamp){
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
 * MESSAGES
 * Determines what stages to print
 * @param $status
 * @param $user_id
 * @param $post_id
 *
 */
function go_print_messages ( $status, $custom_fields, $user_id, $post_id){
    //Print messages
    $i = 0;
    $stage_count = $custom_fields['go_stages'][0];
    while ( $i <= $status && $stage_count > $i) {
        go_print_1_message ( $custom_fields, $i );
        //Print Checks for Understanding for the last stage message printed and buttons
        go_checks_for_understanding ($custom_fields, $i, $status, $user_id, $post_id, null, null, null, false);
        //go_checks_for_understanding ($custom_fields, $i, $status, $user_id, $post_id, $bonus, $bonus_status, $repeat_max)
        $i++;
    }
    if ($i <= $status){
        go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status, false);
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
    //if(isset($GLOBALS['wp_embed']))
    //    $message  = $GLOBALS['wp_embed']->autoembed($message );
    //echo "<div id='message_" . $i . "' class='go_stage_message'  style='display: none;'>".do_shortcode(wpautop( $message  ) )."</div>";

    $message  = apply_filters( 'go_awesome_text', $message );
    echo "<div id='message_" . $i . "' class='go_stage_message'  style='display: none;'>". $message ."</div>";
}

/**
 *Bonus Loot
 */
function go_bonus_loot ($custom_fields, $user_id) {
    $bonus_loot = strtolower( get_option( 'options_go_loot_bonus_loot_name' ) );
    $bonus_loot_uc = ucwords($bonus_loot);
    //$mystery_box_url =
    echo "
		<div id='go_bonus_loot'>
    	<h4>{$bonus_loot_uc}</h4>
        <p>Click the box to try and claim " . $bonus_loot . ".</p>
        ";
    $url = plugin_dir_url((dirname(dirname(dirname(__FILE__)))));
    $url = $url . "media/mysterybox_inner_glow_sm.gif";
    echo "<div id='go_bonus_loot_container' style='display:flex;'>
            <div id='go_bonus_loot_mysterybox'>
                <link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
                <img id='go_bonus_button' class='go_bonus_button'src=" . $url . " > 
		    </div>
		    <div id='go_bonus_loot_possibilites'>
                Click the mystery box and you might find: ";
    go_print_bonus_loot_possibilities($custom_fields,$user_id);
    echo "</div>
        </div>
    </div>";
}

/**
 * @param $badges
 */
function go_display_stage_badges($badges) {
    //if (is_array($badges)) {

   // }
    if (is_serialized($badges)) {
        $badge_ids_array = unserialize($badges);//legacy badges saved as serialized array
    }else{
        $badge_ids_array = $badges;
    }
    if (!is_array($badge_ids_array)){
        $badge_ids_array = array();
        $badge_ids_array[] = $badges;

    }


        foreach ($badge_ids_array as $badge) {
            if (is_int($badge) && $badge != 0) {
                $badge_id = $badge;
                $badge_class = 'go_badge_earned';

                $badge_img_id = get_term_meta($badge_id, 'my_image');
                /*
                $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ?  $custom_fields['go_hide_store_cat'][0] : null);
                if( $cat_hidden == true){
                    continue;
                }
                */

                $badge_obj = get_term($badge_id);
                $badge_name = $badge_obj->name;
                //$badge_img_id =(isset($custom_fields['my_image'][0]) ?  $custom_fields['my_image'][0] : null);
                $badge_img = wp_get_attachment_image($badge_img_id[0], array(100, 100));

                //$badge_attachment = wp_get_attachment_image( $badge_img_id, array( 100, 100 ) );
                //$img_post = get_post( $badge_id );
                if (!empty($badge_obj)) {
                    echo "<div class='go_outro_reward'><div>
                        <div>
                            <figure title='{$badge_name}'>{$badge_img}
                                <figcaption>{$badge_name}</figcaption>
                            </figure>
                        </div>
                       </div></div>";

                }
            }
        }

}


/**
 * @param $custom_fields
 * @param $user_id
 * @param $post_id
 * @param $task_name
 * @param $top
 */
function go_display_rewards($custom_fields, $task_name, $top = true, $user_id, $position ) {


    $task_name = ucwords($task_name);
    $stage_count = $custom_fields['go_stages'][0];

    if ($stage_count > 1){
        $stage_name = get_option('options_go_tasks_stage_name_plural');
    }
    else{
        $stage_name = get_option('options_go_tasks_stage_name_singular');
    }

    if($top){
        echo "<p>This is a {$stage_count} {$stage_name} {$task_name}.</p>";

        echo "<div><p style='margin-bottom: 0px;'>You can earn:</>";
    }

    if (get_option( 'options_go_loot_xp_toggle' )){
        $xp_on = true;
        $xp_name = get_option('options_go_loot_xp_name');
        $xp_loot = (isset($custom_fields['go_entry_rewards_xp'][0]) ?  $custom_fields['go_entry_rewards_xp'][0] : null);
    }else{
        $xp_on = false;
    }
    if (get_option( 'options_go_loot_gold_toggle' )){
        $gold_on = true;
        $gold_name = get_option('options_go_loot_gold_name');
        $gold_loot = (isset($custom_fields['go_entry_rewards_gold'][0]) ?  $custom_fields['go_entry_rewards_gold'][0] : null);

    }else{
        $gold_on = false;
    }
    if (get_option( 'options_go_loot_health_toggle' )){
        $health_on = true;
        $health_name = get_option('options_go_loot_health_name');
        $health_loot = (isset($custom_fields['go_entry_rewards_health'][0]) ?  $custom_fields['go_entry_rewards_health'][0] : null);
    }else{
        $health_on = false;
    }

    if (get_option( 'options_go_badges_toggle' )){
        $badges_on = true;
        $badges_name = get_option('options_go_badges_name_plural');
        $badges = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);
        //$badges = unserialize($badges);
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

        $i++;
    }
    if($health_loot > 200){
        $health_loot = 200;
    }

    echo "<div id='go_task_rewards'>
        <div id='go_task_rewards_loot'>";
    if($xp_on){
        echo "{$xp_loot} {$xp_name} ";
    }
    if($gold_on){
        echo "<br>{$gold_loot} {$gold_name} ";
    }
    if($health_on){
        echo "<br>{$health_loot} {$health_name} ";
    }
    echo "</div>";

    go_display_stage_badges($badges);

$bonus_radio =(isset($custom_fields['bonus_loot_toggle'][0]) ? $custom_fields['bonus_loot_toggle'][0] : null);//is bonus set default, custom or off



    if ($bonus_radio == "1" || $bonus_radio == "default") {
        echo "<div id='go_bonus_loot_possibilites'>";
        echo "Complete the {$task_name} for a chance at a bonus of: ";
        go_print_bonus_loot_possibilities($custom_fields, $user_id);
        echo "</div>";
    }

    echo "</div></div>";

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


    $chain_id = (isset($custom_fields['go-location_map_loc'][0]) ?  $custom_fields['go-location_map_loc'][0] : null);

    if (!empty($chain_id)) {
        $chain_order = go_get_chain_posts($chain_id, false);
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
                $go_task_data = go_post_data($prev_task); //0--name, 1--status, 2--permalink, 3--metadata
                $task_title = $go_task_data[0];
                //$status = $go_task_data[1];
                $task_link = $go_task_data[2];
                //$custom_fields = $go_task_data[3];

                $prev_link = $task_link;
                $prev_title = $task_title;
            }
        }
        $count = count($chain_order);
        $next_key = (int)$this_task_order + 1;
        if ($count > $next_key){
            $next_task = $chain_order[$next_key];
            if (is_int($next_task)){
                $go_task_data = go_post_data($next_task); //0--name, 1--status, 2--permalink, 3--metadata
                $task_title = $go_task_data[0];
                //$status = $go_task_data[1];
                $task_link = $go_task_data[2];
                //$custom_fields = $go_task_data[3];


                $next_link = $task_link;
                $next_title = $task_title;
            }
        }

    } else {
        return false;
    }

    echo"<div style='height: 100px;'>";
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


?>
