<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/3/19
 * Time: 8:14 AM
 */


/**
 *
 */
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
    $next_bonus = ( ! empty( $_POST['next_bonus'] ) ? (int) $_POST['next_bonus'] : 0 );
    $result = (!empty($_POST['result']) ? (string)$_POST['result'] : ''); // Contains the result from the check for understanding

    $blog_post_id = (!empty($_POST['blog_post_id']) ? (string)$_POST['blog_post_id'] : null);
    /*
    $result_title = (!empty($_POST['result_title']) ? (string)$_POST['result_title'] : '');// Contains the result from the check for understanding

    if (is_integer($blog_post_id) && go_post_exists($blog_post_id) == true){
    }else{
        $blog_post_id = null;
    }
    */

    $redirect_url = null;
    $time_left_ms = null;

    $badge_ids = null;
    $group_ids = null;
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

    if ($button_type == 'continue_bonus' || $button_type == 'complete_bonus' || $button_type == 'undo_bonus' || $button_type == 'undo_last_bonus' || $button_type == 'abandon_bonus') {
        $db_status = go_get_bonus_status($post_id, $user_id);

        if ($button_type == 'continue_bonus' || $button_type == 'complete_bonus' ) {
            global $go_print_next;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
            $go_print_next = (isset($go_print_next) ?  $go_print_next : $next_bonus  );
            global $go_bonus_count;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
            $go_bonus_count = (isset($go_bonus_count) ?  $go_bonus_count : $status );

        }
        if ($button_type == 'undo_bonus' || $button_type == 'undo_last_bonus') {
            global $go_print_next;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
            $go_print_next = (isset($go_print_next) ?  $go_print_next : $next_bonus - 1);
            global $go_bonus_count;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
            $go_bonus_count = (isset($go_bonus_count) ?  $go_bonus_count : $status );
        }
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
        $time_left = go_end_time ($custom_fields, $user_id, $post_id );
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
        //check password on stage. Returns "password" or "master_password".
        if ($check_type == 'password'){
            $result = go_stage_password_validate($result, $custom_fields, $status, false);
        }
        //save blog post
        else if ($check_type == 'blog'){

            $result = go_save_blog_post($post_id, $status, null, 'unread');
            // Insert the post into the database

            //create blog post function ($uid, $result);
            //get id of blog post item to set in actions
        }

        else if ($check_type == 'unlock'){ //check password in lock
            //this function checks password and returns
            //invalid or return true
            $result = go_lock_password_validate($result, $custom_fields);
            if ($result == 'password' || $result == 'master password') {
                //set unlock flag
                go_update_actions( $user_id, 'task',  $post_id, null, null, $check_type, $result, null, null,  null, null, null, null, null, null, null, null, null, false );
                //go_update_task_post_save( $post_id );
                echo json_encode(array('json_status' => 'refresh'));
                die;
                //refresh
            }
        }


        //////////////////
        /// UPDATE THE DATABASE for Continue or Complete stage
        ///
        //if task is complete, award badges and groups
        if ($button_type == 'complete') {
            $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);//badges awarded on this task

            $group_ids = (isset($custom_fields['go_groups'][0]) ?  $custom_fields['go_groups'][0] : null);
            $badge_ids_terms = go_badges_task_chains($post_id, $user_id, $custom_fields);//badges awarded on this term
            if (!empty($badge_ids_terms)){//combine the term and task badges before adding them
                $badge_ids = unserialize($badge_ids);
                if (!is_array($badge_ids)){
                    $badge_ids = array();
                }
                $badge_ids = array_unique(array_merge($badge_ids, $badge_ids_terms));
                $badge_ids = serialize($badge_ids);
            }
        }

        go_update_stage_table ($user_id, $post_id, $custom_fields, $status, null, true, $result, $check_type, $badge_ids, $group_ids );
        $status = $status + 1;

        ////////////////////
        /// Write out the new information
        if ($button_type == 'continue') {
            //print new check for understanding based on last stage check type
            go_checks_for_understanding($custom_fields, $status - 1, $status, $user_id, $post_id, null, null, null, false);
            //print new stage message
            go_print_1_message($custom_fields, $status );
            //print new stage check for understanding
            go_checks_for_understanding($custom_fields, $status, $status, $user_id, $post_id, null, null, null, false);
            //$complete = false;
        }else{//Complete

            //print new check for understanding based on last stage check type
            go_checks_for_understanding($custom_fields, $status - 1, $status, $user_id, $post_id, null, null, null, false);
            //complete

            //$complete = true;
            $stage_count = $custom_fields['go_stages'][0];
            go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status, false);
            //print outro and bonus button
        }
    }
    else if ($button_type == 'abandon') {
        //remove entry loot
        $redirect_url = get_option('options_go_landing_page_on_login', '');
        $redirect_url = (site_url() . '/' . $redirect_url);
        go_update_stage_table ($user_id, $post_id, $custom_fields, $status, null, false, 'abandon', null, null, null );
        if($blog_post_id) {
            wp_trash_post(intval($blog_post_id));
        }
    }
    else if ($button_type == 'undo' || $button_type == 'undo_last') {
        if ($button_type == 'undo_last') {
            $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);
            $group_ids = (isset($custom_fields['go_groups'][0]) ?  $custom_fields['go_groups'][0] : null);
            $badge_ids_terms = go_badges_task_chain_undo($post_id, $custom_fields, $user_id);

            if (!empty($badge_ids_terms)){//combine the term and task badges before removing them
                $badge_ids = unserialize($badge_ids);
                if (!is_array($badge_ids)){
                    $badge_ids = array();
                }
                $badge_ids = array_unique(array_merge($badge_ids, $badge_ids_terms));
                $badge_ids = serialize($badge_ids);
            }
        }


        go_update_stage_table ($user_id, $post_id, $custom_fields, $status, null, false, 'undo', null, $badge_ids, $group_ids );
        go_checks_for_understanding ($custom_fields, $status -1 , $status - 1 , $user_id, $post_id, null, null, null, false);
    }
    else if ($button_type == 'show_bonus'){

        go_print_bonus_stage($user_id, $post_id, $custom_fields, false);


    }
    else if ($button_type == 'complete_bonus' || $button_type == 'continue_bonus' || $button_type == 'undo_bonus' || $button_type == 'undo_last_bonus' || $button_type == 'abandon_bonus'){
        $repeat_max = $custom_fields['go_bonus_limit'][0];
        $bonus_status = go_get_bonus_status($post_id, $user_id);

        if ($button_type == 'continue_bonus' || $button_type == 'complete_bonus') {

            $check_type = $custom_fields['go_bonus_stage_check'][0];
            //validate the check for understanding and get modifiers
            if ($check_type == 'password'){
                $result = go_stage_password_validate($result, $custom_fields, $status, true);
            }
            else if ($check_type == 'blog'){
                $result = go_save_blog_post($post_id, null, $bonus_status, 'unread');
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
                go_checks_for_understanding($custom_fields, $bonus_status -1 , null, $user_id, $post_id, true, $bonus_status, $repeat_max, false);
                go_checks_for_understanding($custom_fields, $bonus_status, null, $user_id, $post_id, true, $bonus_status, $repeat_max, false);
            }else
            {
                go_checks_for_understanding($custom_fields, $bonus_status - 1, null, $user_id, $post_id, true, $bonus_status, $repeat_max, false);
            }
        }
        else if ($button_type == 'undo_bonus' || $button_type == 'undo_last_bonus') {

            //////////////////
            /// UPDATE THE DATABASE for BONUS stages undo
            ///
            go_update_stage_table ($user_id, $post_id, $custom_fields, null, $bonus_status, false, 'undo_bonus', $check_type, null, null);
            go_checks_for_understanding($custom_fields, $bonus_status -1, null, $user_id, $post_id, true, $bonus_status - 1 , $repeat_max, false);

        }
        else if ($button_type == 'abandon_bonus') {
            $status = go_get_status($post_id, $user_id);
            $stage_count = $custom_fields['go_stages'][0];
            go_print_outro ($user_id, $post_id, $custom_fields, $stage_count, $status, false);

        }
    }
    //go_check_messages();
    do_action('go_after_stage_change');

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
 *
 */
function go_post_exists( $post_id ) {
    return is_string( get_post_status( $post_id ) );
}


/**
 * @param $pass
 * @param $custom_fields
 * @param $status
 * @param $bonus
 * @return string
 */
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

/**
 * Get achievements associated with the map of a particular post
 * @param $post_id
 * @param $user_id
 * @param $custom_fields
 * @return array
 */
function go_badges_task_chains ($post_id, $user_id, $custom_fields ) {
//Get the chain that this task is in
    $chain_id = (isset($custom_fields['go-location_map_loc'][0]) ?  $custom_fields['go-location_map_loc'][0] : null);

    //if it is in a chain, check if it is done and add badge to array
    if (!empty($chain_id) && $chain_id != null) {
        $badges = array();
        //Get the badge assigned
        $badge= get_term_meta($chain_id, "pod_achievement", true);

        $is_chain_done = null;
        //if chain/pod has badge is on it
        if(!empty($badge) && $badge != null){
            $is_chain_done = is_chain_done($chain_id, $user_id, $post_id, true);
            //is chain done
            if ($is_chain_done){
                //if chain is done, add badge to array
                $badges[] = $badge;
            }
        }

        //CHECK IS ENTIRE MAP IS DONE, and add the badge to the array
        //if the chain isn't done, don't bother to check the entire map
        if($is_chain_done == true || $is_chain_done == null) {
            //chain is done, so get the parent chain
            $term = get_term($chain_id, 'task_chains');
            $termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'task_chains');
            $termParentID = $termParent->term_id;                                   //get the id of the map



            //if ($termParentID == $chain_id) {

            $badge = get_term_meta($termParentID, "pod_achievement", true); //badge assigned to map
            //if map has a badge on it
            if (!empty($badge)) {
                //get all chains and pods
                $children = get_term_children($termParentID, 'task_chains');

                $is_chain_done = false;
                //for each chain/pod //check if all chains are done
                foreach ($children as $child) {
                    //check if each chain is done
                    $is_chain_done = is_chain_done($child, $user_id, $post_id, true);
                    //if it isn't done, stop checking the other chains.
                    if (!$is_chain_done) {
                        break;
                    }
                }
                //all the chains were done, so add badge to array
                if ($is_chain_done) {
                    $badges[] = $badge;
                }
            }
            //}
        }
        return $badges;
    }
}

function go_badges_task_chain_undo($post_id, $custom_fields, $user_id){

    $chain_id = (isset($custom_fields['go-location_map_loc'][0]) ?  $custom_fields['go-location_map_loc'][0] : null);

    //if it is in a chain, check if it is done and remove badge to array if it is not done
    if (!empty($chain_id) && $chain_id != null) {
        $badges = array();
        //Get the badge assigned
        $badge = get_term_meta($chain_id, "pod_achievement", true);

        $is_chain_done = null;
        //if chain/pod has badge is on it
        if (!empty($badge) && $badge != null) {
            $is_chain_done = is_chain_done($chain_id, $user_id, $post_id, false);
            //is chain done
            if (!$is_chain_done) {
                //if chain is not done, add badge to array
                $badges[] = $badge;
            }
        }


    //if the chain isn't done, also remove map badge
    if($is_chain_done == true || $is_chain_done == null) {
        //chain is done, so get the parent chain
        $term = get_term($chain_id, 'task_chains');
        $termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'task_chains');
        $termParentID = $termParent->term_id;                                   //get the id of the map

        $badge = get_term_meta($termParentID, "pod_achievement", true); //badge assigned to map
        //if map has a badge on it
        if (!empty($badge)) {

                $badges[] = $badge;

        }
        //}
    }
    return $badges;
    }
};




