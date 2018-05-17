<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/9/18
 * Time: 10:31 PM
 */

//Prints Checks for understanding for the current stage
function go_checks_for_understanding ($custom_fields, $i, $status, $user_id, $post_id, $bonus, $bonus_count, $repeat_max){
    global $wpdb;
    $go_actions_table_name = "{$wpdb->prefix}go_actions";
    $stage_count = $custom_fields['go_stages'][0]; //total # of stages
    $check_type = 'go_stages_' . $i . '_check'; //which type of check to print
    $check_type = $custom_fields[$check_type][0];
    $button_status = $status;

    echo "<div class='go_checks_and_buttons'>";

    if ($bonus){
        $check_type = $custom_fields['go_bonus_stage_0_check'][0];
    }


    if ($check_type == 'upload') {
        go_upload_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count);
    } else if ($check_type == 'URL') {
        go_url_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count);
    } else if ($check_type == 'password') {
        go_password_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count);
    } else if ($check_type == 'quiz') {
        go_test_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count);
    } else if ($check_type == 'none') {
        go_no_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count);
    }

    //Buttons
    go_buttons($user_id, $custom_fields, $i, $stage_count, $status, $check_type, $bonus, $button_status, $bonus_count, $repeat_max);

    echo "</div>";
}

function go_buttons($user_id, $custom_fields, $i, $stage_count, $status, $check_type, $bonus, $button_status, $bonus_count, $repeat_max){

    $is_admin = go_user_is_admin($user_id);
    $admin_view = null;
    if ($is_admin) {
        $admin_view = get_user_meta($user_id, 'go_admin_view', true);
    }
    if ($admin_view === 'all') {
        $onclick = '';
    } else {
        $onclick_abandon = "onclick='go_task_abandon();this.disabled = true;'";
        $onclick = "onclick='task_stage_check_input( this );'";
    }

    if ($bonus){
        $stage_count = $repeat_max;
        $button_status = $bonus_count;
        $status = $bonus_count;
    }


    //error placeholder

    //Buttons
    if ($check_type == 'show_bonus') {
        echo "<div id='go_buttons'>";
        echo "<div id='go_back_button' " . $onclick . " undo='true' button_type='undo_last' status='{$button_status}' check_type='{$check_type}' ;'>⬆ Undo</div>";
        if ($custom_fields['bonus_switch'][0]) {
            //echo "There is a bonus stage.";
            echo "<button id='go_button' status='{$status}' check_type='{$check_type}' " . $onclick . " button_type='show_bonus'  admin_lock='true' >Show Bonus Challenge</button> ";
        }
        echo "</div>";
    } else if ($bonus &&  $i == $status) {
        echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
        echo "<div id='go_buttons'>";
        echo "<div id='go_back_button' " . $onclick . " undo='true' button_type='undo_bonus' status='{$button_status}' check_type='{$check_type}' >⬆ Undo</div>";
        if (($i + 1) == $stage_count) {
            echo "<button id='go_button' status='{$button_status}' check_type='{$check_type}' " . $onclick . " button_type='complete_bonus'  admin_lock='true' >Complete</button> ";
        } else {
            echo "<button id='go_button' status='{$button_status}' check_type='{$check_type}' " . $onclick . " button_type='complete_bonus'  admin_lock='true' >Continue</button> ";
        }
        echo "</div>";
    } else if ( $i == $status ) {
        echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
        echo "<div id='go_buttons'>";
        if ($i == 0) {
            echo "<div id='go_abandon_task' " . $onclick_abandon . " button_type='abandon' status='{$button_status}' check_type='{$check_type}' >Abandon</div>";
        } else {
            echo "<div id='go_back_button' " . $onclick . " undo='true' button_type='undo' status='{$button_status}' check_type='{$check_type}' >⬆ Undo</div>";
        }
        if (($i + 1) == $stage_count) {
            echo "<button id='go_button' status='{$button_status}' check_type='{$check_type}' " . $onclick . " button_type='complete' admin_lock='true' >Complete</button> ";

        } else {
            echo "<button id='go_button' status='{$button_status}' check_type='{$check_type}' " . $onclick . " button_type='continue'  admin_lock='true' >Continue</button> ";
        }
        echo "</div>";
    }

}


function go_no_check ($custom_fields, $i, $status, $go_actions_table_name){
    if ($i !=$status) {
        echo "Stage complete!";
    }
}

function go_password_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count){
    global $wpdb;

    if ($bonus){
        $status = $bonus_count;
    }


    if ($i == $status) {
        echo "<input id='go_result' type='password' placeholder='Enter Password'/>";
    }
    else {
        $i++;
        if ($bonus) {
            $i = $i + $status;
        }
        $password_type = (string) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND stage = %d
				ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $i
            )
        );

        echo "The " . $password_type . " was entered correctly.";
    }
}

function go_url_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count){
    global $wpdb;
    if ($i == $status) {
        echo "<div id='go_url_div'>";
        echo "<input id='go_result' type='url' placeholder='Enter Url'>";
        echo "</div>";
    }
    else {
        $i++;
        $url = (string) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND stage = %d
				ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $i
            )
        );
        echo "URL Submitted : <a href='" . $url . "' target='blank'>" . $url . "</a>";
    }
}

function go_upload_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count) {
    global $wpdb;
    if ($i == $status) {

        echo do_shortcode('[frontend-button]');
    }
    else {
        $i++;
        $media_id = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND stage = %d
				ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $i
            )
        );

        $type = get_post_mime_type($media_id);

        //return $icon;
        switch ($type) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                $type_image = true;
                break;
            default:
                $type_image = false;
        }
        if ($type_image == true){
            $thumb = wp_get_attachment_image_src( $media_id, 'thumbnail' );
            echo "<img src='" . $thumb[0] . "' >" ;

        }
        else{
           // $img = wp_mime_type_icon($type);
            //echo "<img src='" . $img . "' >";
            $thumb = wp_get_attachment_image_src( $media_id, 'thumbnail',true );
            echo "<img src='" . $thumb[0] . "' >" ;
        }
        echo "<div>" . get_the_title($media_id) . "</div>" ;

    }

}

function go_test_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_count){
    if ($i == $status) {
        //$quiz_data = 'go_stages_' . $i . '_quiz';
        //$quiz_data = $custom_fields[$check_type][0];

        //Quiz Check for Understanding
        //$test_stage_active = ( ! empty( $custom_fields['go_mta_test_'.$stage_short_name.'_lock'][0] ) ? $custom_fields['go_mta_test_'.$stage_short_name.'_lock'][0] : false );


        $test_stage_array = go_task_get_test_meta($custom_fields, $i);
        //$test_stage_returns = $test_stage_array[0];
        $test_stage_num = $test_stage_array[0];
        $test_stage_all_questions = $test_stage_array[1][0];
        $test_stage_all_types = $test_stage_array[1][1];
        $test_stage_all_answers = $test_stage_array[1][2];
        $test_stage_all_keys = $test_stage_array[1][3];

        if ($test_stage_num > 1) {
            for ($i = 0; $i < $test_stage_num; $i++) {
                if (!empty($test_stage_all_types[$i]) && !empty($test_stage_all_questions[$i]) && !empty($test_stage_all_answers[$i]) && !empty($test_stage_all_keys[$i])) {
                    echo do_shortcode("[go_test type='" . $test_stage_all_types[$i] . "' question='" . $test_stage_all_questions[$i] . "' possible_answers='" . $test_stage_all_answers[$i] . "' key='" . $test_stage_all_keys[$i] . "' test_id='" . $i . "' total_num='" . $test_stage_num . "']");
                }
            }
            echo "<p id='go_test_error_msg' style='color: red;'></p>";
            //echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit' button_type='quiz' >Submit</button></div>";
        } elseif (!empty($test_stage_all_types[0]) && !empty($test_stage_all_questions[0]) && !empty($test_stage_all_answers[0]) && !empty($test_stage_all_keys[0])) {
            echo do_shortcode("[go_test type='" . $test_stage_all_types[0] . "' question='" . $test_stage_all_questions[0] . "' possible_answers='" . $test_stage_all_answers[0] . "' key='" . $test_stage_all_keys[0] . "' test_id='0']");
            //."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit button_type='quiz''>Submit</button></div>";
        }
    }
    else {
        echo "Questions answered correctly.";
    }


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
function go_task_get_test_meta($custom_fields, $stage ) {


    //$test_array = get_post_meta( $task_id, "go_mta_test_{$stage}_lock_fields", true );
    $test_array = $custom_fields['go_stages_' . $stage . '_quiz'][0];
    $test_array = unserialize($test_array);
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

        return array( $test_num, array( $test_all_questions, $test_all_types, $test_all_answers, $test_all_keys ) );
    } else {
        return null;
    }
}
