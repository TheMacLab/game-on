<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 6:11 AM
 */


/**
 * checks the test answers
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

