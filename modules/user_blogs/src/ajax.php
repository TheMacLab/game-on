<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:45 PM
 */


function go_blog_opener(){
    check_ajax_referer( 'go_blog_opener' );

    $blog_post_id = ( ! empty( $_POST['blog_post_id'] ) ? (int) $_POST['blog_post_id'] : 0 );
    $min_words = null;
    $i = null;
    $required_string = null;
    $go_blog_task_id = null;

    if ($blog_post_id != 0) { //if opening an existing post
        //get the minimum character count to add to the button
        $blog_meta = get_post_custom($blog_post_id);
        $go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : null);
        $stage = (isset($blog_meta['go_blog_task_stage'][0]) ? $blog_meta['go_blog_task_stage'][0] : null);
        $i = $stage - 1;
        if(!empty($go_blog_task_id)) {
            $custom_fields = get_post_custom($go_blog_task_id);
            $min_words = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0] : null);
            $required_string = (isset($custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0] : null);

        }
    }

    go_blog_form($blog_post_id, '_lightbox', $go_blog_task_id, $i, null );
    echo "<button id='go_blog_submit' style='display:block;' check_type='blog_lightbox' blog_post_id ={$blog_post_id} blog_suffix ='_lightbox' min_words ='{$min_words}' required_string='{$required_string}' task_id='{$go_blog_task_id}'>Submit</button>";
    echo "<p id='go_blog_error_msg' style='display: none; color: red;'></p>";
    ?>
    <script>

        jQuery( document ).ready( function() {
            jQuery("#go_blog_submit").one("click", function(e){
                //go_blog_submit( this, '_lightbox' );
                task_stage_check_input(this, false, true);
            });

        });

    </script>

    <?php
}

/*
function go_blog_lightbox_opener(){
    check_ajax_referer( 'go_blog_lightbox_opener' );

    $blog_post_id = ( ! empty( $_POST['blog_post_id'] ) ? (int) $_POST['blog_post_id'] : 0 );

    if(!empty($blog_post_id)) {
        $post = get_post($blog_post_id, OBJECT, 'edit');
        $content = $post->post_content;
        $content  = apply_filters( 'go_awesome_text', $content );
        $title = get_the_title($blog_post_id);
    }else{
        $content = '';
        $title = '';
    }
    echo "<div id='go_url_div'>";

    echo "<div><h3>{$title}</h3></div>";

    echo "<div>{$content}</div>";

    echo "</div>";

}
*/

function go_blog_submit(){
    check_ajax_referer( 'go_blog_submit' );
    $blog_post_id = intval(!empty($_POST['blog_post_id']) ? (string)$_POST['blog_post_id'] : '');
    if($blog_post_id) {
        $blog_meta = get_post_custom($blog_post_id);
        $go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : null);
        $go_blog_task_stage = (isset($blog_meta['go_blog_task_stage'][0]) ? $blog_meta['go_blog_task_stage'][0] : null);
    }else {
        $go_blog_task_id = intval(!empty($_POST['post_id']) ? (string)$_POST['post_id'] : '');
        $go_blog_task_stage = intval(!empty($_POST['go_blog_task_stage']) ? (string)$_POST['go_blog_task_stage'] : '');
    }

    $result = go_save_blog_post($go_blog_task_id, $go_blog_task_stage);

    ob_start();
    go_noty_message_generic('success', 'Draft Saved Successfully', '', 2000);
    $buffer = ob_get_contents();

    ob_end_clean();

    echo json_encode(
        array(
            'json_status' => 'success',
            'blog_post_id' => $result,
            'message' => $buffer
        )
    );

    die();

    /*
    $result = (!empty($_POST['result']) ? (string)$_POST['result'] : ''); // Contains the result from the check for understanding
    $result_title = (!empty($_POST['result_title']) ? (string)$_POST['result_title'] : '');// Contains the result from the check for understanding
    $user_id = get_current_user_id();
    $blog_post_id = (!empty($_POST['blog_post_id']) ? (string)$_POST['blog_post_id'] : '');
    $my_post = array(
        'ID'        => $blog_post_id,
        'post_type'     => 'go_blogs',
        'post_title'    => $result_title,
        'post_content'  => $result,
        'post_status'   => 'publish',
        'post_author'   => $user_id,

    );
    go_blog_save($blog_post_id, $my_post);
    */
}

function go_save_blog_post($post_id = null, $stage = null, $message = false){
    $user_id = get_current_user_id();
    $result = (!empty($_POST['result']) ? (string)$_POST['result'] : ''); // Contains the result from the check for understanding
    $result_title = (!empty($_POST['result_title']) ? (string)$_POST['result_title'] : '');// Contains the result from the check for understanding
    $blog_post_id = intval(!empty($_POST['blog_post_id']) ? (string)$_POST['blog_post_id'] : '');
    if (go_post_exists($blog_post_id) == true){
    }else{
        $blog_post_id = null;
    }
    $blog_url = (!empty($_POST['blog_url']) ? (string)$_POST['blog_url'] : '');
    $blog_media = (!empty($_POST['blog_media']) ? (string)$_POST['blog_media'] : '');
    $blog_video = (!empty($_POST['blog_video']) ? (string)$_POST['blog_video'] : '');

    //if this post is associated with a task stage
    if ($post_id === null) {
        $post_name = get_the_title($post_id);
    }else{
        $post_name = null;
    }


    $my_post = array(
        'ID'        => $blog_post_id,
        'post_type'     => 'go_blogs',
        'post_title'    => $result_title,
        'post_content'  => $result,
        'post_status'   => 'publish',
        'post_author'   => $user_id,
        'tax_input'    => array(
            'go_blog_tags'     => $post_name,
            'go_blog_tags'     => $post_id,

        ),
        'meta_input'    => array(
            'go_blog_url'     => $blog_url,
            'go_blog_media'     => $blog_media,
            'go_blog_video'     => $blog_video,
            'go_blog_task_id'     => $post_id,
            'go_blog_task_stage'     => $stage

        )
    );

    $result = go_blog_save($blog_post_id, $my_post);

    return $result;
}

function go_blog_save($blog_post_id, $my_post){
    if (empty($blog_post_id)) {
        // Insert the post into the database
        $new_post_id = wp_insert_post( $my_post );
        $result = $new_post_id;
    }else{
        wp_update_post($my_post);
        $result = $blog_post_id;
    }
    return $result;
}

/**
 * Prints content for the clipboard tasks table
 */
function go_blog_user_task(){
    global $wpdb;

    check_ajax_referer( 'go_blog_user_task' );

    $user_id = intval($_POST['uid']);
    $post_id = intval($_POST['task_id']);

    $go_activity_table_name = "{$wpdb->prefix}go_actions";
    //get all blog posts from a particular task
    //get task history
    //get post #s
    //print posts
    /*
    $actions = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
			FROM {$go_activity_table_name}
			WHERE action_type = %s and uid = %d and source_id = %d and check_type = %s
			ORDER BY id DESC",
            "task",
            $user_id,
            $post_id,
            "blog"
        )
    );
*/
    $actions = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * 
			FROM {$go_activity_table_name} 
			WHERE action_type = %s and uid = %d and source_id = %d  
			ORDER BY id DESC",
            "task",
            $user_id,
            $post_id
        )
    );

    $entry_time = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT TIMESTAMP 
			FROM {$go_activity_table_name} 
			WHERE result = %s and uid = %d and source_id = %d  
			ORDER BY id DESC",
            "entry_reward",
            $user_id,
            $post_id
        )
    );


    $post_title = get_the_title($post_id);
    $task_name = get_option('options_go_tasks_name_singular');
    echo "<div id='go_blog_container' class='go_blogs'><h2>{$task_name}: {$post_title}</h2>";
    $current_stage = null;
    $bonus = false;

    $stage_results = array();
    $this_result = array();
    $first_loop = true;
    foreach ( $actions as $action ) {
        $stage_name ="";
        $action_type = $action->action_type;
        $TIMESTAMP = $action->TIMESTAMP;
        $stage = $action->stage;
        $bonus_status = $action->bonus_status;
        $result = $action->result;
        $quiz_mod = $action->quiz_mod;
        $late_mod = $action->late_mod;
        $timer_mod = $action->timer_mod;
        $health_mod = $action->global_mod;
        $xp = $action->xp;
        $gold = $action->gold;
        $health = $action->health;
        $xp_total = $action->xp_total;
        $gold_total = $action->gold_total;
        $health_total = $action->health_total;
        $check_type = $action->check_type;


        $print = false;
        $bonus_name = "";

        //this is the trigger that this was the last bonus
        if ($bonus == true && $bonus_status == 0){
            $current_stage = null;
            $bonus = false;
        }
        //Only print the content last submitted for each stage.

        //this is the first entry for this task or the first bonus stage (in reverse order)
        if ($current_stage == null){
            if ($bonus_status > 0){ //if this is a bonus stage, set some stuff
                $current_stage = $bonus_status;
                $bonus = true;
                $bonus_name = "Bonus ";
                $stage_name =  get_option('options_go_tasks_bonus_stage');

                $print = true;
                $current_time = $TIMESTAMP;
            }else {//this is not a bonus stage so set these things
                $current_stage = $stage;
                $print = true;
                $current_time = $TIMESTAMP;
                $stage_name = get_option('options_go_tasks_stage_name_singular');
            }
        }else {//this is not the first bonus or regular stage (in reverse order)

            //after the first action
            if ($bonus == true && intval($bonus_status) > 0 && intval($bonus_status) < intval($current_stage)) {
                $current_stage = $bonus_status;
                $stage_name =  get_option('options_go_tasks_bonus_stage');
                $print = true;
            } else if ($bonus == false && intval($stage) < intval($current_stage) && intval($stage) > 0) {
                $current_stage = $stage;
                $stage_name = get_option('options_go_tasks_stage_name_singular');
                $print = true;
            }
        }

        if ($print){
            //this is the time for the previous task
            //$time_on_task = $current_time - $TIMESTAMP;
            if($first_loop === false){
                $time_on_task = go_time_on_task($current_time, $TIMESTAMP);


                $this_result[] = $time_on_task;
                $stage_results[] = $this_result;
            }
            $first_loop = false;
            $this_result = array();//clear it for the next go

            //set the time for the next loop
            $current_time = $TIMESTAMP;

            //$current_stage = $stage;
            //if (blog, url, quiz,
            /*
            $content_post = get_post($result);
            $post_title = $content_post->post_title;
            $content = $content_post->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            */
            ob_start();
            $stage_name = ucfirst($stage_name);
            echo  "<div class='go_blog_stage'><h3>". $stage_name . " " . $current_stage .": ";
            if ($check_type == "blog"){
                echo "Blog Post</h3>";
                //go_print_blog_check_result($result, false);
                go_blog_post($result);
            }else if($check_type == "URL"){
                echo "URL</h3>";
                go_print_URL_check_result($result);
            }else if($check_type == "upload"){
                echo "Upload</h3>";
                go_print_upload_check_result($result);
            }else if($check_type == "password"){
                echo "Password</h3>";
                go_print_password_check_result($result);
            }else if($check_type == "none"){
                echo "No Check for Understanding</h3>";
            }
            else if($check_type == "quiz"){
                echo "Quiz</h3>";
                echo "Quiz Score: " .$result;
            }

            $this_result[]= ob_get_contents();
            ob_end_clean();
        }

    }

    $time_on_task = go_time_on_task($current_time, $entry_time);
    $this_result[] = $time_on_task;
    $stage_results[] = $this_result;

    $stage_results = array_reverse($stage_results);
    foreach ($stage_results as $result){
        echo $result[0];
        echo $result[1];
    }
    echo "</div>";

}



