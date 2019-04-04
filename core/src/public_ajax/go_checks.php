<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/9/18
 * Time: 10:31 PM
 */


/**
 * Prints Checks for understanding for the current stage
 * @param $custom_fields
 * @param $i
 * @param $status
 * @param $user_id
 * @param $post_id
 * @param $bonus
 * @param $bonus_status
 * @param $repeat_max
 * @param $all_content
 */
function go_checks_for_understanding ($custom_fields, $i, $status, $user_id, $post_id, $bonus, $bonus_status, $repeat_max, $all_content){
    global $wpdb;
    $go_actions_table_name = "{$wpdb->prefix}go_actions";
    $stage_count = (isset($custom_fields['go_stages'][0]) ?  $custom_fields['go_stages'][0] : null); //total # of stages


    if ($bonus){
        $check_type = (isset($custom_fields['go_bonus_stage_check'][0]) ?  $custom_fields['go_bonus_stage_check'][0] : null);

    }
    else{
        $check_type = 'go_stages_' . $i . '_check'; //which type of check to print
        //$check_type = $custom_fields[$check_type][0];
        $check_type = (isset($custom_fields[$check_type][0]) ?  $custom_fields[$check_type][0] : null);

    }

    if (isset($custom_fields['go_stages_' . $i . '_instructions'][0]) && (!$bonus)) {
        $instructions = $custom_fields['go_stages_' . $i . '_instructions'][0];
        $instructions = apply_filters( 'go_awesome_text', $instructions );
        $instructions = "<div class='go_call_to_action'>" . $instructions . " </div>";
    } else if (isset($custom_fields['go_bonus_stage_instructions'][0]) && ($bonus)) {
        $instructions = $custom_fields['go_bonus_stage_instructions'][0];
        $instructions = apply_filters( 'go_awesome_text', $instructions );
        $instructions = "<div class='go_call_to_action'>" . $instructions . " </div>";
    }else {
        $instructions = null;
    }

    if ($bonus){
        if ($i == $repeat_max - 1 && $bonus_status == $repeat_max){
            $bonus_is_complete = true;
        }else{
            $bonus_is_complete = false;
        }
    }else{
        $bonus_is_complete = false;
    }

    if ($bonus){
        $status_active_check = $bonus_status;
    }else{
        $status_active_check = $status;
    }

    if($i == $status_active_check && $bonus_is_complete == false ){
        $class = 'active';
    }else{$class = 'null';}
    echo "<div class='go_checks_and_buttons {$class}' style='display:block;'>";
    echo $instructions;

    $blog_post_id = null;
    if ($check_type == 'upload') {
        go_upload_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status);
    } else if ($check_type == 'blog') {
        $blog_post_id = go_blog_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status, $all_content, $repeat_max, $check_type, $stage_count);
    } else if ($check_type == 'URL') {
        go_url_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status);
    } else if ($check_type == 'password') {
        go_password_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status);
    } else if ($check_type == 'quiz') {
        go_test_check($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status);
    } else if ($check_type == 'none' || $check_type == null) {
        go_no_check($i, $status, $custom_fields, $bonus, $bonus_status);
    }

    if ($check_type != 'blog') {
        //Buttons
        go_buttons($user_id, $custom_fields, $i, $stage_count, $status, $check_type, $bonus, $bonus_status, $repeat_max, false, $blog_post_id);
    }


    echo "</div>";
}

/**
 * Prints the buttons on checks for understanding/task pages
 * @param $user_id
 * @param $custom_fields
 * @param $i
 * @param $stage_count
 * @param $status
 * @param $check_type
 * @param $bonus
 * @param $bonus_status
 * @param $repeat_max
 * @param bool $outro
 */
function go_buttons($user_id, $custom_fields, $i, $stage_count, $status, $check_type, $bonus, $bonus_status, $repeat_max, $outro = false, $blog_post_id = null){

    $is_admin = go_user_is_admin($user_id);
    $admin_view = null;
    if ($is_admin) {
        $admin_view = get_user_option('go_admin_view', $user_id );
    }
    $undo = 'undo';
    $abandon = 'abandon';
    $complete = 'complete';
    $continue = 'continue';
    $stage = $i;

    if ($bonus != true) {
        $url_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0] : null);
        $file_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0] : null);
        $video_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_video'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_video'][0] : null);
        $text_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0] : null);
        $restrict_mime_types = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0] : null);
        $min_words = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0] : null);
        $required_string = (isset($custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0]) ?  $custom_fields['go_stages_'.$stage.'_blog_options_url_url_validation'][0] : null);

    }
    else{
        $url_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_url_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_url_toggle'][0] : null);
        $file_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0] : null);
        $video_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_video'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_video'][0] : null);
        $text_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0] : null);
        $restrict_mime_types = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_restrict_file_types'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_restrict_file_types'][0] : null);
        $min_words = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_minimum_length'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_minimum_length'][0] : null);
        $required_string = (isset($custom_fields['go_bonus_stage_blog_options_url_url_validation'][0]) ?  $custom_fields['go_stages_'.$stage.'_blog_options_url_url_validation'][0] : null);

    }

    $bonus_is_complete = false;
    if ($bonus){
        $stage_count = $repeat_max;
        $undo = 'undo_bonus';
        $abandon = 'abandon_bonus';
        $complete = 'complete_bonus';
        $continue = 'continue_bonus';
        if ($i == $repeat_max - 1 && $bonus_status == $repeat_max){
            $bonus_is_complete = true;
            $undo = 'undo_last_bonus';
        }
        $status = $bonus_status;

    }

    if ($admin_view === 'all') {
        $onclick = '';
    }

    //Buttons
    if ($outro || $check_type == 'show_bonus' || $bonus_is_complete) {
        if (! $bonus_is_complete ){
            $undo = 'undo_last';
        }
        echo "<div id='go_buttons'>";
        echo "<div id='go_back_button' class='go_buttons' undo='true' button_type='{$undo}' status='{$status}' check_type='{$check_type}' blog_post_id='{$blog_post_id}'>⬆ Undo</div>";
        if ($custom_fields['bonus_switch'][0] && ! $bonus_is_complete) {
            //echo "There is a bonus stage.";
            echo "<button id='go_button' class='show_bonus go_buttons' status='{$status}' check_type='{$check_type}' button_type='show_bonus'  admin_lock='true' >Show Bonus Challenge</button> ";
        }
        echo "</div>";
    }

    else if ( $i == $status || $status == 'unlock' || $admin_view == 'all' || $admin_view == 'guest' ) {

        if ($bonus) {
            global $go_print_next;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
            $go_print_next = (isset($go_print_next) ? $go_print_next : $status);
        }
        else{
            $go_print_next = null;
        }

        echo "<div id='go_buttons'>";
        if ($i == 0) {
            echo "<div id='go_back_button'  class='go_buttons' undo='true' button_type='{$abandon}' status='{$status}' check_type='{$check_type}' blog_post_id='{$blog_post_id}'>Abandon</div>";
        } else {
            echo "<div id='go_back_button' class='go_buttons' undo='true' button_type='{$undo}' next_bonus='{$go_print_next}' status='{$status}' next_bonus='{$go_print_next}' check_type='{$check_type}' blog_post_id='{$blog_post_id}'>⬆ Undo</div>";
        }
        if (($i + 1) == $stage_count) {
            echo "<button id='go_button' class='progress go_buttons' status='{$status}' check_type='{$check_type}' button_type='{$complete}' next_bonus='{$go_print_next}' admin_lock='true' required_string='".$required_string."' min_words='{$min_words}' blog_suffix ='' url_toggle='{$url_toggle}' video_toggle='{$video_toggle}' file_toggle='{$file_toggle}' text_toggle='{$text_toggle}' >Complete</button> ";
        } else {

            echo "<button id='go_button' class='progress go_buttons' status='{$status}' check_type='{$check_type}' button_type='{$continue}' next_bonus='{$go_print_next}' admin_lock='true' required_string='{$required_string}' min_words='{$min_words}' blog_suffix ='' url_toggle='{$url_toggle}' video_toggle='{$video_toggle}' file_toggle='{$file_toggle}' text_toggle='{$text_toggle}'>Continue</button>";

        }
        echo "</div>";
        echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
    }
}

/**
 * @param $i
 * @param $status
 * @param $custom_fields
 * @param $bonus
 * @param $bonus_status
 */
function go_no_check ($i, $status, $custom_fields, $bonus, $bonus_status){
    //for bonus stages
    if ($bonus){
        $status = $bonus_status;
    }
    if ($i !=$status) {
        echo "Stage complete!";
    }
}

/**
 * @param $custom_fields
 * @param $i
 * @param $status
 * @param $go_actions_table_name
 * @param $user_id
 * @param $post_id
 * @param $bonus
 * @param $bonus_status
 */
function go_password_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status){
    global $wpdb;

    //for bonus stages
    $stage = 'stage';
    if ($bonus){
        $status = $bonus_status;
        $stage = 'bonus_status';
    }
    //end for bonus stages

    if ($i == $status) {
        echo "<input id='go_result' class='clickable' type='password' placeholder='Enter Password'/>";
    }
    else {
        $i++;
        $password_type = (string) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND {$stage} = %d
				ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $i
            )
        );
        go_print_password_check_result($password_type);

    }
}

function go_print_password_check_result($password_type){
    echo "The " . $password_type . " was entered correctly.";
}

/**
 * @param $custom_fields
 * @param $i
 * @param $status
 * @param $go_actions_table_name
 * @param $user_id
 * @param $post_id
 * @param $bonus
 * @param $bonus_status
 */
function go_blog_check ($custom_fields = null, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status, $all_content, $repeat_max, $check_type, $stage_count){
    global $wpdb;

    //$url_toggle = (isset($custom_fields['go_stages_'.$i.'_blog_options_url_toggle'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_url_toggle'][0] : null);
    //$file_toggle = (isset($custom_fields['go_stages_'.$i.'_blog_options_attach_file_toggle'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_attach_file_toggle'][0] : null);
    //$video_toggle = (isset($custom_fields['go_stages_'.$i.'_blog_options_video'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_video'][0] : null);
    //$text_toggle = (isset($custom_fields['go_stages_'.$i.'_blog_options_blog_text_toggle'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_blog_text_toggle'][0] : null);

    if (!$bonus){//if this is not a bonus
        $stage = 'stage';
        if ($i != $status && !$all_content){//if this is a complete stage
            if(empty($blog_post_id)) {


                $args = array(
                    'post_status' => array( 'draft', 'unread', 'read', 'publish', 'reset', 'revise', 'trash'),
                    'post_type' => 'go_blogs',
                    'post_parent'=> intval($post_id),
                    'author'    => $user_id,
                    'posts_per_page' => 1,
                    'meta_key' => 'go_blog_task_stage',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'go_blog_task_stage',
                            'value' => $i,
                            'compare' => '=',
                        )
                    )
                );
                $my_query = new WP_Query($args);


                if( $my_query->have_posts() ) {
                    while( $my_query->have_posts() ) {
                        $my_query->the_post();
                        $blog_post_id = get_the_ID() ;
                        // Do your work...
                    } // end while
                } // end if
                wp_reset_postdata();


            }
            $blog_post_id = (isset($blog_post_id) ?  $blog_post_id : null);
            go_blog_post($blog_post_id, true, true);
        }
        else{//this is the current stage and print the form
            if (!$all_content) {
                $args = array(
                    'post_status' => array('any', 'trash'),
                    'post_type' => 'go_blogs',
                    'post_parent' => intval($post_id),
                    'author' => $user_id,
                    'posts_per_page' => 1,
                    'meta_key' => 'go_blog_task_stage',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'go_blog_task_stage',
                            'value' => $i,
                            'compare' => '=',
                        )
                    )
                );
                $my_query = new WP_Query($args);


                if ($my_query->have_posts()) {
                    while ($my_query->have_posts()) {
                        $my_query->the_post();
                        $blog_post_id = get_the_ID();
                        // Do your work...
                    } // end while
                } // end if
                wp_reset_postdata();
            }
            $blog_post_id = (isset($blog_post_id) ?  $blog_post_id : null);
            if($blog_post_id) {
                wp_trash_post(intval($blog_post_id));
            }
            go_blog_form($blog_post_id, '', $post_id, $i, $bonus_status, true);

            go_buttons($user_id, $custom_fields, $i, $stage_count, $status, $check_type, $bonus, $bonus_status, $repeat_max, false, $blog_post_id);
            if($blog_post_id){do_action('go_blog_template_after_post', $blog_post_id, false);}


            return $blog_post_id;
        }
    }else{//this is a bonus
        global $go_bonus_count;//the number of stages that have been printed
        global $go_print_next;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
        $go_print_next = (isset($go_print_next) ?  $go_print_next : 0);

        $go_bonus_count++;

        //LOGIC
        //the first time bonus_status == x and bonus_count == 1
        //if x >= bonus count, print the first bonus that isn't trash
        //else then print the form
            //print the first that isn't trash
            //if that doesn't exist, print first that is trash
            //else print blank form

        //the $go_print_next variable is set as the status in the buttons function

        if ($bonus_status >= $go_bonus_count && !$all_content) {//if this is a complete stage

            $args = array(
                'post_status' => array( 'draft', 'unread', 'read', 'publish', 'reset', 'revise' ),
                'post_type' => 'go_blogs',
                'post_parent'=> intval($post_id),
                'author'    => $user_id,
                'posts_per_page' => 1,
                'offset'    => $go_print_next,
                'meta_query' => array(
                    array(
                        'key' => 'go_blog_bonus_stage',
                        'value' => 1,
                        'compare' => '>=',
                    )
                ),
                'orderby' => 'modified',
                'order' => 'ASC'
            );
            $my_query = new WP_Query($args);

            if( $my_query->have_posts() ) {
                while( $my_query->have_posts() ) {
                    $my_query->the_post();
                    $blog_post_id = get_the_ID() ;
                    //$go_print_next = get_post_meta($blog_post_id, 'go_blog_bonus_stage')[0];
                    // Do your work...
               } // end while
            } // end if
            wp_reset_postdata();
            $blog_post_id = (isset($blog_post_id) ?  $blog_post_id : null);
            go_blog_post($blog_post_id, true, true);
            $go_print_next++;
            return $blog_post_id;
        }
        else{//this is the current bonus stage and print the form
            //get the next, trashed or not
            if (!$all_content) {
                $args = array(
                    'post_status' => array('draft', 'unread', 'read', 'publish', 'reset', 'revise'),
                    'post_type' => 'go_blogs',
                    'post_parent' => intval($post_id),
                    'author' => $user_id,
                    'posts_per_page' => 1,
                    'offset' => $go_print_next,
                    'meta_query' => array(
                        array(
                            'key' => 'go_blog_bonus_stage',
                            'value' => 1,
                            'compare' => '>=',
                        )
                    ),
                    'orderby' => 'modified',
                    'order' => 'ASC'
                );
                $my_query = new WP_Query($args);


                if ($my_query->have_posts()) {
                    while ($my_query->have_posts()) {
                        $my_query->the_post();
                        $blog_post_id = get_the_ID();
                        //$go_print_next = get_post_meta($blog_post_id, 'go_blog_bonus_stage')[0];
                        // Do your work...
                    } // end while
                } else {//if there were no posts found, check for trashed posts
                    //global $go_print_next_trash;//the bonus stage to be printed, sometimes they print out of order if posts were trashed
                    //$go_print_next_trash = (isset($go_print_next_trash) ?  $go_print_next_trash : 1);
                    $args = array(
                        'post_status' => 'trash',
                        'post_type' => 'go_blogs',
                        'post_parent' => intval($post_id),
                        'author' => $user_id,
                        'posts_per_page' => 1,
                        'meta_query' => array(
                            array(
                                'key' => 'go_blog_bonus_stage',
                                'value' => 1,
                                'compare' => '>=',
                            )
                        ),
                        'orderby' => 'modified',
                        'order' => 'ASC'
                    );
                    $my_query = new WP_Query($args);


                    if ($my_query->have_posts()) {
                        while ($my_query->have_posts()) {
                            $my_query->the_post();
                            $blog_post_id = get_the_ID();
                            //$go_print_next_trash = get_post_meta($blog_post_id, 'go_blog_bonus_stage')[0];
                            // Do your work...
                        } // end while
                    }

                } // end if
                wp_reset_postdata();
            }
            $blog_post_id = (isset($blog_post_id) ?  $blog_post_id : null);
            if($blog_post_id) {
                wp_trash_post(intval($blog_post_id));
            }
            go_blog_form($blog_post_id, '', $post_id, $i, $bonus, true);

            go_buttons($user_id, $custom_fields, $i, $stage_count, $status, $check_type, $bonus, $bonus_status, $repeat_max, false, $blog_post_id);
            if($blog_post_id){do_action('go_blog_template_after_post', $blog_post_id, false);}

            return $blog_post_id;

        }


    }


}

/**
 * @param $custom_fields
 * @param $i
 * @param $status
 * @param $go_actions_table_name
 * @param $user_id
 * @param $post_id
 * @param $bonus
 * @param $bonus_status
 */
function go_url_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status){
    global $wpdb;

    //for bonus stages
    $stage = 'stage';
    if ($bonus){
        $status = $bonus_status;
        $stage = 'bonus_status';
    }
    //end for bonus stages

    if ($i == $status) {
        $i++;
        $url = (string)$wpdb->get_var($wpdb->prepare("SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND {$stage}  = %d AND action_type = %s
				ORDER BY id DESC LIMIT 1", $user_id, $post_id, $i, 'task'));

        echo "<div class='go_url_div'>";
        echo "<input id='go_result' class='clickable' type='url' placeholder='Enter URL' value='{$url}'>";
        echo "</div>";
    }
    else {
        $i++;
        $url = (string) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND {$stage}  = %d 
				ORDER BY id DESC LIMIT 1",
                $user_id,
                $post_id,
                $i
            )
        );
        //add lightbox to this--NOPE too many protected URLs
        go_print_URL_check_result($url);
        //Too many security errors with Lightbox
        //echo "<br><a href='" . $url . "' data-featherlight='iframe'>Open in a lightbox.</a>";
    }
}

function go_url_check_blog ($placeholder = 'Enter URL', $id = 'go_result', $url = null){
    global $wpdb;

        echo "<div class='go_url_div'>";
        echo "<input id='{$id}' class='clickable' type='url' placeholder='{$placeholder}' value='{$url}'>";
        echo "</div>";
}

function go_print_URL_check_result($url){
    echo "<div class='go_required_blog_content'>";
    echo "<div>URL Submitted : <a href='" . $url . "' target='blank'>" . $url . "</a></div>";
    echo "</div>";
}

/**
 * @param $custom_fields
 * @param $i
 * @param $status
 * @param $go_actions_table_name
 * @param $user_id
 * @param $post_id
 * @param $bonus
 * @param $bonus_status
 */
function go_upload_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status) {
    global $wpdb;

    //for bonus stages
    $stage = 'stage';
    if ($bonus){
        $status = $bonus_status;
        $stage = 'bonus_status';
    }
    //end for bonus stages

    if ($i == $status) {
        $i++;
        $media_id = (int)$wpdb->get_var($wpdb->prepare("SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND {$stage}  = %d AND action_type = %s
				ORDER BY id DESC LIMIT 1", $user_id, $post_id, $i, 'task'));


        if (empty($media_id) || $media_id === 0) {
            echo do_shortcode('[frontend-button div_id="go_result"]');
        }else{
            echo do_shortcode( '[frontend_submitted_media div_id="go_result" id="'.$media_id.'"]' );
        }

    }
    else {
        $i++;
        $media_id = (int)$wpdb->get_var($wpdb->prepare("SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d AND {$stage}  = %d
				ORDER BY id DESC LIMIT 1", $user_id, $post_id, $i));

        go_print_upload_check_result($media_id);
    }

}

function go_upload_check_blog ($media_id = null, $div_id, $mime_types) {
    //$restrict_mime_types = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0] : null);

    /*
    $doc_mime_types = unserialize(isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_file_types_documents'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_file_types_documents'][0] : null);
    $doc_mime_types = is_array($doc_mime_types) ? $doc_mime_types :  array();
    $image_mime_types = unserialize(isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_file_types_images'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_file_types_images'][0] : null);
    $image_mime_types = is_array($image_mime_types) ? $image_mime_types :  array();
    $adobe_mime_types = unserialize(isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_file_types_adobe'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_file_types_adobe'][0] : null);
    $adobe_mime_types = is_array($adobe_mime_types) ? $adobe_mime_types :  array();

    $mime_types = implode(",", array_merge($doc_mime_types, $image_mime_types, $adobe_mime_types));
    */

    /*
    if ($restrict_mime_types) {
        $mime_types = unserialize(isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_allowed_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_allowed_types'][0] : null);
        $mime_types = is_array($mime_types) ? $mime_types : array();
        $mime_types = implode(",", $mime_types);
    }else{
        $mime_types = '';
    }
    */

    if (empty($media_id)) {
        echo do_shortcode('[frontend-button div_id="'.$div_id.'" mime_types="'.$mime_types.'"]');
    }else{
        echo do_shortcode( '[frontend_submitted_media div_id="'.$div_id.'" id="'.$media_id.'" mime_types="'.$mime_types.'"]' );
    }
}

function go_print_upload_check_result($media_id){
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
    echo "<div class='go_required_blog_content'>";
    if ($type_image == true){
        $med = wp_get_attachment_image_src( $media_id, 'medium' );
        $full = wp_get_attachment_image_src( $media_id, 'full' );
        //echo "<img src='" . $thumb[0] . "' >" ;
        echo '<a href="#" data-featherlight="' . $full[0] . '"><img src="' . $med[0] . '"></a>';

    }
    else{
        // $img = wp_mime_type_icon($type);
        //echo "<img src='" . $img . "' >";
        $url = wp_get_attachment_url( $media_id );
        $thumb = wp_get_attachment_image_src( $media_id, 'thumbnail',true );
        echo "<a href='{$url}'>";
        echo "<img src='" . $thumb[0] . "' >" ;
        echo "<div>" . get_the_title($media_id) . "</div>" ;
        echo "</a>";
    }
    echo "</div>";
}

/**
 * @param $custom_fields
 * @param $i
 * @param $status
 * @param $go_actions_table_name
 * @param $user_id
 * @param $post_id
 * @param $bonus
 * @param $bonus_status
 */
function go_test_check ($custom_fields, $i, $status, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status){
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
 */
function go_task_get_test_meta($custom_fields, $stage ) {

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
