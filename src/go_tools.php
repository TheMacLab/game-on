<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/1/18
 * Time: 9:18 PM
 */

function go_upgade4 (){
    global $wpdb;
    check_ajax_referer( 'go_upgade4' );
    $go_posts_table = "{$wpdb->prefix}posts";
    $tasks = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID 
			FROM {$go_posts_table} 
			WHERE post_type = %s
			ORDER BY id DESC",
            'tasks'
        )
    );

    foreach ($tasks as $task){
        $id = $task->ID;
        echo $id;
        $custom_fields = get_post_custom( $id );
        update_post_meta($id, 'go_stages', 3);
        $message1 = (isset($custom_fields['go_mta_quick_desc'][0]) ?  $custom_fields['go_mta_quick_desc'][0] : null);
        update_post_meta($id, 'go_stages_0_content', $message1);
        $message2 = (isset($custom_fields['go_mta_accept_message'][0]) ?  $custom_fields['go_mta_accept_message'][0] : null);
        update_post_meta($id, 'go_stages_1_content', $message2);
        $message3 = (isset($custom_fields['go_mta_complete_message'][0]) ?  $custom_fields['go_mta_complete_message'][0] : null);
        update_post_meta($id, 'go_stages_2_content', $message3);

        //get post meta
        //copy message 1
        //copy message 2
        //copy message 3


        //STAGE 1
        $quiz_toggle = (isset($custom_fields['go_mta_test_encounter_lock'][0]) ?  $custom_fields['go_mta_test_encounter_lock'][0] : null);
        $url_toggle = (isset($custom_fields['go_mta_encounter_url_key'][0]) ?  $custom_fields['go_mta_encounter_url_key'][0] : null);
        $upload_toggle = (isset($custom_fields['go_mta_encounter_upload'][0]) ?  $custom_fields['go_mta_encounter_upload'][0] : null);
        $password_serial = (isset($custom_fields['go_mta_encounter_admin_lock'][0]) ?  $custom_fields['go_mta_encounter_admin_lock'][0] : null);
        $password_array = unserialize($password_serial);
        $password_toggle = $password_array[0];
        $password = $password_array[1];
        if($quiz_toggle){
            $quiz = (isset($custom_fields['go_mta_test_encounter_lock_fields'][0]) ?  $custom_fields['go_mta_test_encounter_lock_fields'][0] : null);
            update_post_meta($id, 'go_stages_0_quiz', $quiz);
            update_post_meta($id, 'go_stages_0_check', 'quiz');
        }
        else if($url_toggle){
            update_post_meta($id, 'go_stages_0_check', 'url');
        }
        else if($upload_toggle){
            update_post_meta($id, 'go_stages_0_check', 'upload');
        }
        else if($password_toggle) {
            update_post_meta($id, 'go_stages_0_check', 'password');
            update_post_meta($id, 'go_stages_0_password', $password);
        }else{
            update_post_meta($id, 'go_stages_0_check', 'none');
        }

        //STAGE 2
        $quiz_toggle = (isset($custom_fields['go_mta_test_accept_lock'][0]) ?  $custom_fields['go_mta_test_accept_lock'][0] : null);
        $url_toggle = (isset($custom_fields['go_mta_accept_url_key'][0]) ?  $custom_fields['go_mta_accept_url_key'][0] : null);
        $upload_toggle = (isset($custom_fields['go_mta_accept_upload'][0]) ?  $custom_fields['go_mta_accept_upload'][0] : null);
        $password_serial = (isset($custom_fields['go_mta_accept_admin_lock'][0]) ?  $custom_fields['go_mta_accept_admin_lock'][0] : null);
        $password_array = unserialize($password_serial);
        $password_toggle = $password_array[0];
        $password = $password_array[1];
        if($quiz_toggle){
            $quiz = (isset($custom_fields['go_mta_test_accept_lock_fields'][0]) ?  $custom_fields['go_mta_test_accept_lock_fields'][0] : null);
            update_post_meta($id, 'go_stages_1_quiz', $quiz);
            update_post_meta($id, 'go_stages_1_check', 'quiz');
        }
        else if($url_toggle){
            update_post_meta($id, 'go_stages_1_check', 'url');
        }
        else if($upload_toggle){
            update_post_meta($id, 'go_stages_1_check', 'upload');
        }
        else if($password_toggle) {
            update_post_meta($id, 'go_stages_1_check', 'password');
            update_post_meta($id, 'go_stages_1_password', $password);
        }else{
            update_post_meta($id, 'go_stages_1_check', 'none');
        }

        //STAGE 3
        $quiz_toggle = (isset($custom_fields['go_mta_test_completion_lock'][0]) ?  $custom_fields['go_mta_test_completion_lock'][0] : null);
        $url_toggle = (isset($custom_fields['go_mta_completion_url_key'][0]) ?  $custom_fields['go_mta_completion_url_key'][0] : null);
        $upload_toggle = (isset($custom_fields['go_mta_completion_upload'][0]) ?  $custom_fields['go_mta_completion_upload'][0] : null);
        $password_serial = (isset($custom_fields['go_mta_completion_admin_lock'][0]) ?  $custom_fields['go_mta_completion_admin_lock'][0] : null);
        $password_array = unserialize($password_serial);
        $password_toggle = $password_array[0];
        $password = $password_array[1];
        if($quiz_toggle){
            $quiz = (isset($custom_fields['go_mta_test_completion_lock_fields'][0]) ?  $custom_fields['go_mta_test_completion_lock_fields'][0] : null);
            update_post_meta($id, 'go_stages_2_quiz', $quiz);
            update_post_meta($id, 'go_stages_2_check', 'quiz');
        }
        else if($url_toggle){
            update_post_meta($id, 'go_stages_2_check', 'url');
        }
        else if($upload_toggle){
            update_post_meta($id, 'go_stages_2_check', 'upload');
        }
        else if($password_toggle) {
            update_post_meta($id, 'go_stages_2_check', 'password');
            update_post_meta($id, 'go_stages_2_password', $password);
        }else{
            update_post_meta($id, 'go_stages_2_check', 'none');
        }

        //for stage 1-3
        //if quiz on
            //set radio to 'quiz
            //copy quiz
        //else if url on
            //set radio to 'URL'
            //go_mta_mastery_url_key
        //else if upload on
            //set radio to 'upload'
            //go_mta_mastery_upload
        //else if password on
            //set radio to password
            //copy password
            //go_mta_mastery_admin_lock

        $three_switch = (isset($custom_fields['go_mta_three_stage_switch'][0]) ?  $custom_fields['go_mta_three_stage_switch'][0] : false);

        if($three_switch != 'on'){
            update_post_meta($id, 'go_stages', 4);
            $message4 = (isset($custom_fields['go_mta_mastery_message'][0]) ?  $custom_fields['go_mta_mastery_message'][0] : null);
            update_post_meta($id, 'go_stages_3_content', $message4);
        }


        $five_switch = (isset($custom_fields['go_mta_five_stage_switch'][0]) ?  $custom_fields['go_mta_five_stage_switch'][0] : null);

        if($three_switch != 'on' && $five_switch == 'on'){
            update_post_meta($id, 'go_stages', 5);
            $message5 = (isset($custom_fields['go_mta_repeat_message'][0]) ?  $custom_fields['go_mta_repeat_message'][0] : null);
            update_post_meta($id, 'go_stages_4_content', $message5);
            //STAGE 5 --the check for understandings from stage 4 in v3 go to stage 5 in v4
            $quiz_toggle = (isset($custom_fields['go_mta_test_mastery_lock'][0]) ?  $custom_fields['go_mta_test_mastery_lock'][0] : null);
            $url_toggle = (isset($custom_fields['go_mta_mastery_url_key'][0]) ?  $custom_fields['go_mta_mastery_url_key'][0] : null);
            $upload_toggle = (isset($custom_fields['go_mta_mastery_upload'][0]) ?  $custom_fields['go_mta_mastery_upload'][0] : null);
            $password_serial = (isset($custom_fields['go_mta_mastery_admin_lock'][0]) ?  $custom_fields['go_mta_mastery_admin_lock'][0] : null);
            $password_array = unserialize($password_serial);
            $password_toggle = $password_array[0];
            $password = $password_array[1];
            if($quiz_toggle){
                $quiz = (isset($custom_fields['go_mta_test_mastery_lock_fields'][0]) ?  $custom_fields['go_mta_test_mastery_lock_fields'][0] : null);
                update_post_meta($id, 'go_stages_4_quiz', $quiz);
                update_post_meta($id, 'go_stages_4_check', 'quiz');
            }
            else if($url_toggle){
                update_post_meta($id, 'go_stages_4_check', 'url');
            }
            else if($upload_toggle){
                update_post_meta($id, 'go_stages_4_check', 'upload');
            }
            else if($password_toggle) {
                update_post_meta($id, 'go_stages_4_check', 'password');
                update_post_meta($id, 'go_stages_4_password', $password);
            }else{
                update_post_meta($id, 'go_stages_4_check', 'none');
            }
        }



//store content

    }
}