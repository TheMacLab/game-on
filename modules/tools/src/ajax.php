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

    foreach ($tasks as $task) {
        $id = $task->ID;
        //echo $id;
        $custom_fields = get_post_custom($id);
        update_post_meta($id, 'go_stages', 3);
        $message1 = (isset($custom_fields['go_mta_quick_desc'][0]) ? $custom_fields['go_mta_quick_desc'][0] : null);
        update_post_meta($id, 'go_stages_0_content', $message1);
        $message2 = (isset($custom_fields['go_mta_accept_message'][0]) ? $custom_fields['go_mta_accept_message'][0] : null);
        update_post_meta($id, 'go_stages_1_content', $message2);
        $message3 = (isset($custom_fields['go_mta_complete_message'][0]) ? $custom_fields['go_mta_complete_message'][0] : null);
        update_post_meta($id, 'go_stages_2_content', $message3);

        //get post meta
        //copy message 1
        //copy message 2
        //copy message 3


        //STAGE 1
        $quiz_toggle = (isset($custom_fields['go_mta_test_encounter_lock'][0]) ? $custom_fields['go_mta_test_encounter_lock'][0] : null);
        $url_toggle = (isset($custom_fields['go_mta_encounter_url_key'][0]) ? $custom_fields['go_mta_encounter_url_key'][0] : null);
        $upload_toggle = (isset($custom_fields['go_mta_encounter_upload'][0]) ? $custom_fields['go_mta_encounter_upload'][0] : null);
        $password_serial = (isset($custom_fields['go_mta_encounter_admin_lock'][0]) ? $custom_fields['go_mta_encounter_admin_lock'][0] : null);
        $password_array = unserialize($password_serial);
        $password_toggle = $password_array[0];
        $password = $password_array[1];
        if ($quiz_toggle) {
            $quiz = (isset($custom_fields['go_mta_test_encounter_lock_fields'][0]) ? $custom_fields['go_mta_test_encounter_lock_fields'][0] : null);
            update_post_meta($id, 'go_stages_0_quiz', $quiz);
            update_post_meta($id, 'go_stages_0_check', 'quiz');
        } else if ($url_toggle) {
            update_post_meta($id, 'go_stages_0_check', 'URL');
        } else if ($upload_toggle) {
            update_post_meta($id, 'go_stages_0_check', 'upload');
        } else if ($password_toggle) {
            update_post_meta($id, 'go_stages_0_check', 'password');
            update_post_meta($id, 'go_stages_0_password', $password);
        } else {
            update_post_meta($id, 'go_stages_0_check', 'none');
        }

        //STAGE 2
        $quiz_toggle = (isset($custom_fields['go_mta_test_accept_lock'][0]) ? $custom_fields['go_mta_test_accept_lock'][0] : null);
        $url_toggle = (isset($custom_fields['go_mta_accept_url_key'][0]) ? $custom_fields['go_mta_accept_url_key'][0] : null);
        $upload_toggle = (isset($custom_fields['go_mta_accept_upload'][0]) ? $custom_fields['go_mta_accept_upload'][0] : null);
        $password_serial = (isset($custom_fields['go_mta_accept_admin_lock'][0]) ? $custom_fields['go_mta_accept_admin_lock'][0] : null);
        $password_array = unserialize($password_serial);
        $password_toggle = $password_array[0];
        $password = $password_array[1];
        if ($quiz_toggle) {
            $quiz = (isset($custom_fields['go_mta_test_accept_lock_fields'][0]) ? $custom_fields['go_mta_test_accept_lock_fields'][0] : null);
            update_post_meta($id, 'go_stages_1_quiz', $quiz);
            update_post_meta($id, 'go_stages_1_check', 'quiz');
        } else if ($url_toggle) {
            update_post_meta($id, 'go_stages_1_check', 'URL');
        } else if ($upload_toggle) {
            update_post_meta($id, 'go_stages_1_check', 'upload');
        } else if ($password_toggle) {
            update_post_meta($id, 'go_stages_1_check', 'password');
            update_post_meta($id, 'go_stages_1_password', $password);
        } else {
            update_post_meta($id, 'go_stages_1_check', 'none');
        }

        //STAGE 3
        $quiz_toggle = (isset($custom_fields['go_mta_test_completion_lock'][0]) ? $custom_fields['go_mta_test_completion_lock'][0] : null);
        $url_toggle = (isset($custom_fields['go_mta_completion_url_key'][0]) ? $custom_fields['go_mta_completion_url_key'][0] : null);
        $upload_toggle = (isset($custom_fields['go_mta_completion_upload'][0]) ? $custom_fields['go_mta_completion_upload'][0] : null);
        $password_serial = (isset($custom_fields['go_mta_completion_admin_lock'][0]) ? $custom_fields['go_mta_completion_admin_lock'][0] : null);
        $password_array = unserialize($password_serial);
        $password_toggle = $password_array[0];
        $password = $password_array[1];
        if ($quiz_toggle) {
            $quiz = (isset($custom_fields['go_mta_test_completion_lock_fields'][0]) ? $custom_fields['go_mta_test_completion_lock_fields'][0] : null);
            update_post_meta($id, 'go_stages_2_quiz', $quiz);
            update_post_meta($id, 'go_stages_2_check', 'quiz');
        } else if ($url_toggle) {
            update_post_meta($id, 'go_stages_2_check', 'URL');
        } else if ($upload_toggle) {
            update_post_meta($id, 'go_stages_2_check', 'upload');
        } else if ($password_toggle) {
            update_post_meta($id, 'go_stages_2_check', 'password');
            update_post_meta($id, 'go_stages_2_password', $password);
        } else {
            update_post_meta($id, 'go_stages_2_check', 'none');
        }

        $three_switch = (isset($custom_fields['go_mta_three_stage_switch'][0]) ? $custom_fields['go_mta_three_stage_switch'][0] : false);

        if ($three_switch != 'on') {
            update_post_meta($id, 'go_stages', 4);
            $message4 = (isset($custom_fields['go_mta_mastery_message'][0]) ? $custom_fields['go_mta_mastery_message'][0] : null);
            update_post_meta($id, 'go_stages_3_content', $message4);
        }


        $five_switch = (isset($custom_fields['go_mta_five_stage_switch'][0]) ? $custom_fields['go_mta_five_stage_switch'][0] : null);

        if ($three_switch != 'on' && $five_switch == 'on') {
            update_post_meta($id, 'bonus_switch', 1);
            $message5 = (isset($custom_fields['go_mta_repeat_message'][0]) ? $custom_fields['go_mta_repeat_message'][0] : null);
            update_post_meta($id, 'go_bonus_stage_content', $message5);
            //STAGE 5 --the check for understandings from stage 4 in v3 go to stage 5 in v4
            $quiz_toggle = (isset($custom_fields['go_mta_test_mastery_lock'][0]) ? $custom_fields['go_mta_test_mastery_lock'][0] : null);
            $url_toggle = (isset($custom_fields['go_mta_mastery_url_key'][0]) ? $custom_fields['go_mta_mastery_url_key'][0] : null);
            $upload_toggle = (isset($custom_fields['go_mta_mastery_upload'][0]) ? $custom_fields['go_mta_mastery_upload'][0] : null);
            $password_serial = (isset($custom_fields['go_mta_mastery_admin_lock'][0]) ? $custom_fields['go_mta_mastery_admin_lock'][0] : null);
            $password_array = unserialize($password_serial);
            $password_toggle = $password_array[0];
            $password = $password_array[1];
            if ($quiz_toggle) {
                $quiz = (isset($custom_fields['go_mta_test_mastery_lock_fields'][0]) ? $custom_fields['go_mta_test_mastery_lock_fields'][0] : null);
                update_post_meta($id, 'go_bonus_stage_quiz', $quiz);
                update_post_meta($id, 'go_bonus_stage_check', 'quiz');
            } else if ($url_toggle) {
                update_post_meta($id, 'go_bonus_stage_check', 'URL');
            } else if ($upload_toggle) {
                update_post_meta($id, 'go_bonus_stage_check', 'upload');
            } else if ($password_toggle) {
                update_post_meta($id, 'go_bonus_stage_check', 'password');
                update_post_meta($id, 'go_bonus_stage_password', $password);
            } else {
                update_post_meta($id, 'go_bonus_stage_check', 'none');
            }
        }

        $update_loot = ( ! empty( $_POST['loot'] ) ? $_POST['loot'] : 0 );
        if ($update_loot == 'true'){

            //update task loot
            $presets  = (isset($custom_fields['go_presets'][0]) ?  $custom_fields['go_presets'][0] : null);
            $presets = unserialize($presets);
            $points = $presets['points'];
            $gold = $presets['currency'];

            $xpe = $points[0];
            $xp1 = $points[1];
            $xp2 = $points[2];
            $xp3 = $points[3];
            $xpb = $points[4];

            $golde = $gold[0];
            $gold1 = $gold[1];
            $gold2 = $gold[2];
            $gold3 = $gold[3];
            $goldb = $gold[4];

            //encounter
            update_post_meta($id, 'go_entry_rewards_gold', $golde);
            update_post_meta($id, 'go_entry_rewards_xp', $xpe);

            //stage 1
            update_post_meta($id, 'go_stages_0_rewards_gold', $gold1);
            update_post_meta($id, 'go_stages_0_rewards_xp', $xp1);

            //stage 2
            update_post_meta($id, 'go_stages_1_rewards_gold', $gold2);
            update_post_meta($id, 'go_stages_1_rewards_xp', $xp2);

            //stage 3
            update_post_meta($id, 'go_stages_2_rewards_gold', $gold3);
            update_post_meta($id, 'go_stages_2_rewards_xp', $xp3);


            //bonus stage/ stage 5
            update_post_meta($id, 'go_bonus_stage_rewards_gold', $goldb);
            update_post_meta($id, 'go_bonus_stage_rewards_xp', $xpb);
        }

    }


//store content
    $store_items = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID 
			FROM {$go_posts_table} 
			WHERE post_type = %s
			ORDER BY id DESC",
            'go_store'
        )
    );
    foreach ($store_items as $store_item){
        $id = $store_item->ID;
        $custom_fields = get_post_custom($id);
        $store_description = $my_post_content = apply_filters('the_content', get_post_field('post_content', $id));
        update_post_meta($id, 'go_store_item_desc', $store_description);

        $store_cost = (isset($custom_fields['go_mta_store_cost'][0]) ?  $custom_fields['go_mta_store_cost'][0] : null);
        $store_cost = unserialize($store_cost);
        $store_gold = $store_cost[0];
        $store_xp = $store_cost[1];

        if (!empty($store_gold)){
            if ($store_gold > 0) {
                update_post_meta($id, 'go_loot_loot_gold', $store_gold);
            }
            if ($store_gold < 0){
                $store_gold = abs($store_gold);
                update_post_meta($id, 'go_loot_loot_gold', $store_gold);
                update_post_meta($id, 'go_loot_reward_toggle_gold', 1);
            }
        }

        if (!empty($store_xp)){
            if ($store_xp > 0) {
                update_post_meta($id, 'go_loot_loot_xp', $store_xp);
            }
            if ($store_xp < 0){
                $store_xp = abs($store_xp);
                update_post_meta($id, 'go_loot_loot_xp', $store_xp);
                update_post_meta($id, 'go_loot_reward_toggle_xp', 1);
            }
        }
    }
}

function go_reset_all_users(){
    global $wpdb;
    check_ajax_referer( 'go_reset_all_users' );
    global $wpdb;
    $loot_table  = $wpdb->prefix . 'go_loot';
    $wpdb->query("TRUNCATE TABLE $loot_table");

    $tasks_table  = $wpdb->prefix . 'go_tasks';
    $wpdb->query("TRUNCATE TABLE $tasks_table");

    $actions_table  = $wpdb->prefix . 'go_actions';
    $wpdb->query("TRUNCATE TABLE $actions_table");

}