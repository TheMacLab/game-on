<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/9/18
 * Time: 10:31 PM
 */

//Prints Checks for understanding for the current stage
function go_checks_for_understanding ( $wpdb, $status, $custom_fields, $go_table_name, $user_id, $id, $number_of_stages, $repeat_amount  ){
    echo "<div id='go_checks_and_buttons'><div id='checks'>";
    //move the stage 4 checks for understanding to the bonus
    if ($status >= 5){
        $stage_lookup = 5;
    }
    else {
        $stage_lookup = $status;
    }
    $stage_short_name = go_short_name ( $stage_lookup );
    $stage_letter = go_stage_letter ( $stage_lookup );

    //Upload Check for Understanding
    $stage_upload = ( ! empty( $custom_fields['go_mta_'.$stage_short_name.'_upload'][0] ) ? $custom_fields['go_mta_'.$stage_short_name.'_upload'][0] : false );
    //get if item is uploaded variable (null or 1)
    $db_task_stage_upload_var = $stage_letter . '_uploaded';
    if ( ! empty( $db_task_stage_upload_var ) ) {
        $is_uploaded = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT {$db_task_stage_upload_var} 
					FROM {$go_table_name} 
					WHERE uid = %d AND post_id = %d",
                $user_id,
                $id
            )
        );
    } else {
        $is_uploaded = 0;
    }
    if ( $stage_upload ) {
        echo do_shortcode( "[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$id}]" )."<br/>";
    }

    //Quiz Check for Understanding
    $test_stage_active = ( ! empty( $custom_fields['go_mta_test_'.$stage_short_name.'_lock'][0] ) ? $custom_fields['go_mta_test_'.$stage_short_name.'_lock'][0] : false );

    if ( $test_stage_active ) {
        $test_stage_array = go_task_get_test_meta( $stage_short_name, $id );
        $test_stage_returns = $test_stage_array[0];
        $test_stage_num = $test_stage_array[1];
        $test_stage_all_questions = $test_stage_array[2][0];
        $test_stage_all_types = $test_stage_array[2][1];
        $test_stage_all_answers = $test_stage_array[2][2];
        $test_stage_all_keys = $test_stage_array[2][3];
    }

    if ( $test_stage_active ) {

        if ( $test_stage_num > 1 ) {
            for ( $i = 0; $i < $test_stage_num; $i++ ) {
                if ( ! empty( $test_stage_all_types[ $i ] ) &&
                    ! empty( $test_stage_all_questions[ $i ] ) &&
                    ! empty( $test_stage_all_answers[ $i ] ) &&
                    ! empty( $test_stage_all_keys[ $i ] ) ) {
                    echo do_shortcode( "[go_test type='".$test_stage_all_types[ $i ]."' question='".$test_stage_all_questions[ $i ]."' possible_answers='".$test_stage_all_answers[ $i ]."' key='".$test_stage_all_keys[ $i ]."' test_id='".$i."' total_num='".$test_stage_num."']" );
                }
            }
            echo "<p id='go_test_error_msg' style='color: red;'></p>";
            echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit' button_type='quiz' >Submit</button></div>";
        } elseif ( ! empty( $test_stage_all_types[0] ) &&
            ! empty( $test_stage_all_questions[0] ) &&
            ! empty( $test_stage_all_answers[0] ) &&
            ! empty( $test_stage_all_keys[0] ) ) {
            echo do_shortcode( "[go_test type='".$test_stage_all_types[0]."' question='".$test_stage_all_questions[0]."' possible_answers='".$test_stage_all_answers[0]."' key='".$test_stage_all_keys[0]."' test_id='0']" )."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit button_type='quiz''>Submit</button></div>";
        }

    }

    //Password Check for Understanding
    $stage_admin_lock = get_post_meta( $id, 'go_mta_'.$stage_short_name.'_admin_lock', true );
    if ( ! empty( $stage_admin_lock ) ) {
        $stage_is_locked = ( ! empty( $stage_admin_lock[0] ) ? true : false );
        if ( $stage_is_locked ) {
            $stage_pass_lock = ( ! empty( $stage_admin_lock[1] ) ? $stage_admin_lock[1] : '' );
        }
    }
    if ( $stage_is_locked && ! empty( $stage_pass_lock ) ) {
        echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
    }

    //URL Check for Understanding
    $stage_url_is_locked = ( ! empty( $custom_fields['go_mta_'.$stage_short_name.'_url_key'][0] ) ? true : false );
    if ( $stage_url_is_locked === true ) {
        echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
    }

    //error placeholder
    echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";


    //Buttons
    echo "<div id='go_buttons'>";
    //$status = $status + 1;
    if (($number_of_stages > $status) && ($status < 4) ){
        echo "<button id='go_button' status='{$status}' onclick='task_stage_change( this );' button_type='continue'";
        if ( $stage_is_locked && empty( $stage_pass_lock ) ) {
            echo "admin_lock='true'";
        }
        echo ">Continue</button> ";
    }

    if (($number_of_stages == 5) && ($status == 4) ){
        echo "<button id='go_button' status='{$status}' onclick='task_stage_change( this );' button_type='continue'";
        if ( $stage_is_locked && empty( $stage_pass_lock ) ) {
            echo "admin_lock='true'";
        }
        echo ">See Bonus</button> ";
    }

    $number = $status - 4;
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number %100) >= 11 && ($number%100) <= 13)
        $abbreviation = $number. 'th';
    else
        $abbreviation = $number. $ends[$number % 10];
    if (($number_of_stages == 5) && ($status >= 5) ){
        if ($status >= 5 && $repeat_amount >= $number){
            if ($repeat_amount > 1) {
                echo "This bonus task can be repeated " . $repeat_amount . " times.<br>This is your " . $abbreviation . ".<br>";
            }
            echo "<button id='go_button' status='{$status}' onclick='task_stage_change( this );' button_type='continue'";
            if ( $stage_is_locked && empty( $stage_pass_lock ) ) {
                echo "admin_lock='true'";
            }
            echo ">Submit Bonus</button> ";
        }

    }


    if ($status == 1){
        echo "<button id='go_abandon_task' onclick='go_task_abandon();this.disabled = true;' button_type='continue'>Abandon</button>";
    }
    else{
        echo "<button id='go_back_button' onclick='task_stage_change( this );' undo='true' button_type='undo'>Undo</button>";
    }
    echo "</div></div>";
}



//Quiz function
/**
 * Retrieves and formulates test meta data from a specific task and stage.
 *
 * Note that this function does not check that a stage has the test option enabled. It is expected
 * that such checks will be made prior to calling the function. However, empty test meta data will
 * return null.
 *
 * The test meta data arrays are separately ordered so that index 0 of the question array corresponds
 * to index 0 of all the other arrays, index 1 to all the other index 1 elements, and so on.
 *
 * @since 3.0.0
 *
 * @param string $stage   The stage. e.g. "encounter", "accept", "completion", "mastery" ("repeat"
 *                        would return null, since there is no test option in the fifth stage).
 * @param int    $task_id Optional. The task ID.
 * @return array|null An array of data pertaining to the stage's test(s). Null when the stage's meta
 *                    data is empty.
 *
 * e.g. array(
 *           $test_returns,            // loot meta data
 *           $test_num,                // the number of questions
 *           array(
 *                $test_all_questions, // an array of questions
 *                $test_all_types,     // an array of question types (Multiple Choice or
 *                                     // Multiple Select)
 *                $test_all_answers,   // an array of potential answers
 *                $test_all_keys,      // an array of answer keys
 *           ),
 *      )
 */
function go_task_get_test_meta( $stage, $task_id ) {
    if ( empty( $stage ) ) {
        return null;
    }

    if ( empty( $task_id ) ) {
        $task_id = get_the_id();
    } elseif ( 'int' !== gettype( $task_id ) ) {
        $task_id = (int) $task_id;
    }

    $test_returns = get_post_meta( $task_id, "go_mta_test_{$stage}_lock_loot", true );
    $test_array = get_post_meta( $task_id, "go_mta_test_{$stage}_lock_fields", true );

    if ( ! empty( $test_array ) ) {
        $test_num = $test_array[3];
        $test_all_questions = array();
        foreach ( $test_array[0] as $question ) {
            $esc_question = htmlspecialchars( $question, ENT_QUOTES );
            if ( preg_match( "/[\\\[\]]/", $question ) ) {
                $str = preg_replace( array( "/\[/", "/\]/", "/\\\/" ), array( '&#91;', '&#93;', '\\\\\\\\\\\\' ), $esc_question );
                $test_all_questions[] = $str;
            } else {
                $test_all_questions[] = $esc_question;
            }
        }
        $test_all_types = $test_array[2];
        $test_all_inputs = $test_array[1];
        $test_all_input_num = $test_array[4];
        $test_all_answers = array();
        $test_all_keys = array();
        for ( $i = 0; $i < count( $test_all_inputs ); $i++ ) {
            if ( ! empty( $test_all_inputs[ $i ][0] ) ) {
                $answer_temp = implode( "###", $test_all_inputs[ $i ][0] );
                $esc_answer = htmlspecialchars( $answer_temp, ENT_QUOTES );
                if ( preg_match( "/[\\\[\]]/", $answer_temp ) ) {
                    $str = preg_replace( array( "/\[/", "/\]/", "/\\\/" ), array( '&#91;', '&#93;', '\\\\\\\\\\\\' ), $esc_answer );
                    $test_all_answers[] = $str;
                } else {
                    $test_all_answers[] = $esc_answer;
                }
            }
            if ( ! empty( $test_all_inputs[ $i ][1] ) ) {
                $key_temp = implode( "###", $test_all_inputs[ $i ][1] );
                $esc_key = htmlspecialchars( $key_temp, ENT_QUOTES );
                if (preg_match( "/[\\\[\]]/", $key_temp) ) {
                    $str = preg_replace( array( "/\[/", "/\]/", "/\\\/" ), array( '&#91;', '&#93;', '\\\\\\\\\\\\' ), $esc_key );
                    $test_all_keys[] = $str;
                } else {
                    $test_all_keys[] = $esc_key;
                }
            }
        }

        return array( $test_returns, $test_num, array( $test_all_questions, $test_all_types, $test_all_answers, $test_all_keys ) );
    } else {
        return null;
    }
}
