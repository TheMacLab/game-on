<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 10:11 PM
 */

//This is the function that checks the test answers
/**
 *
 */
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

/**
 *
 */
function go_post_exists( $post_id ) {
  return is_string( get_post_status( $post_id ) );
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
    $result_title = (!empty($_POST['result_title']) ? (string)$_POST['result_title'] : '');// Contains the result from the check for understanding
    $blog_post_id = (!empty($_POST['blog_post_id']) ? (string)$_POST['blog_post_id'] : '');
    if (is_integer($blog_post_id) && go_post_exists($blog_post_id) == true){
    }else{
        $blog_post_id = null;
    }

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

        ////////////////
        /// DO ANY FINAL VALIDATION
        ///
        if ($check_type == 'password'){
            $result = go_stage_password_validate($result, $custom_fields, $status, false);
        }
        else if ($check_type == 'blog'){
            $post_name = get_the_title($post_id);
            $my_post = array(
                'ID'        => $blog_post_id,
                'post_type'     => 'go_blogs',
                'post_title'    => $result_title,
                'post_content'  => $result,
                'post_status'   => 'publish',
                'post_author'   => $user_id,
                'tax_input'    => array(
                    'go_blog_tags'     => $post_name
                ),
            );

            $result = go_blog_save($blog_post_id, $my_post);

            // Insert the post into the database

            //create blog post function ($uid, $result);
            //get id of blog post item to set in actions

        }
        else if ($check_type == 'unlock'){
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

        if ($button_type == 'complete') {
            $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);

            $group_ids = (isset($custom_fields['go_groups'][0]) ?  $custom_fields['go_groups'][0] : null);
            $badge_ids_terms = go_badges_task_chains($post_id, $user_id, $custom_fields);
            if (!empty($badge_ids_terms)){
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

        go_print_bonus_stage($user_id, $post_id, $custom_fields);


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
                $post_name = get_the_title($post_id);
                $my_post = array(
                    'ID'        => $blog_post_id,
                    'post_type'     => 'go_blogs',
                    'post_title'    => $result_title,
                    'post_content'  => $result,
                    'post_status'   => 'publish',
                    'post_author'   => $user_id,
                    'tax_input'    => array(
                        'go_blog_tags'     => $post_name
                    ),
                );

                // Insert the post into the database
                $new_post_id = wp_insert_post( $my_post );
                //create blog post function ($uid, $result);
                //get id of blog post item to set in actions
                $result = $new_post_id;
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
        else if ($button_type == 'undo_bonus' || $button_type == 'undo_last_bonus') {

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
    go_check_messages();

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

//Adds the quiz modifier to the actions table
/**
 * @param $user_id
 * @param $task_id
 * @param $fail_count
 * @param $status
 */
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
        go_update_actions($user_id, 'quiz_mod', $task_id, $status + 1, null, $status, $fail_count, null, null, null, null, null, null, null, null, null, null, null, false);
    }
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
 * @param $pass
 * @param $custom_fields
 * @param $status
 * @return string
 */
function go_lock_password_validate($pass, $custom_fields){

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

