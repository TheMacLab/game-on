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

    if(!empty($blog_post_id)) {
        $post = get_post($blog_post_id, OBJECT, 'edit');
        $content = $post->post_content;
        $title = get_the_title($blog_post_id);
    }else{
        $content = '';
        $title = '';
    }
    echo "<div id='go_url_div'>";

    echo "<div>Title:<div><input style='width: 100%;' id='go_result_title_blog' type='text' value ='{$title}'></div> </div>";
    $settings  = array(
        //'tinymce'=> array( 'menubar'=> true, 'toolbar1' => 'undo,redo', 'toolbar2' => ''),
        //'tinymce'=>true,
        //'wpautop' =>false,
        'textarea_name' => 'go_result',
        'media_buttons' => true,
        //'teeny' => true,
        'quicktags'=> array( 'buttons' => '' ),
        'menubar' => false,
        'drag_drop_upload' => true,
        //'textarea_rows'=>'6'
    );
    wp_editor( $content, 'go_blog_post_edit', $settings );

    $length = strlen(strip_tags($content));
    $minimum = '300';

    echo "</div>";
    //echo "<div id='go_blog_min' style='text-align:left'>Character Count: <span class='char_count'>".$length."/".$minimum ."</span> Minimum</div>";
    echo "<button id='go_blog_submit' style='display:block;' blog_post_id =" .$blog_post_id. ">Submit</button>";
    ?>
    <script>

        jQuery( document ).ready( function() {
            jQuery("#go_blog_submit").one("click", function(e){
                go_blog_submit( this );
            });

        });

    </script>
    <?php
}

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

function go_blog_submit(){
    check_ajax_referer( 'go_blog_submit' );
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
                go_print_blog_check_result($result, false);
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

//Get the time on task from two times as timestamps
//or as one variable passed as a number of seconds
function go_time_on_task($current_time, $TIMESTAMP =false){
    if ($TIMESTAMP != false) {
        $delta_time = strtotime($current_time) - strtotime($TIMESTAMP);
        $d = 'days';
        $h = 'hours';
        $m = 'minutes';
        $s = 'seconds';
        $title = "Time On Task: ";
    }else{
        $delta_time = $current_time;
        $d = 'd';
        $h = 'h';
        $m = 'm';
        $s = 's';
        $title = "";
    }
    $days = floor( $delta_time/86400);
    $delta_time = $delta_time % 86400;
    $hours = floor($delta_time / 3600);
    $delta_time = $delta_time % 3600;
    $minutes = floor($delta_time / 60);
    $delta_time = $delta_time % 60;
    $seconds = $delta_time;




    //$time_on_task = "{$days} days {$hours} hours and {$minutes} minutes and {$seconds} seconds";
    $time_on_task = "";
    if ($days>0){
        $time_on_task .= "{$days}{$d} ";
    }
    if ($hours>0){
        $time_on_task .= "{$hours}{$h} ";
    }
    if ($minutes>0){
        $time_on_task .= "{$minutes}{$m} ";
    }
    if ($seconds>0){
        $time_on_task .= "{$seconds}{$s}";
    }
    $result ="";
    $time = date("m/d/y g:i A", strtotime($TIMESTAMP));
    if ($TIMESTAMP != false) {
        $result .= "<div style='text-align:right;'>Time Submitted: " . $time . "</div>";
    }
    $result .= "<div style='text-align:right;'>". $title .$time_on_task . "</div></div>";
    return $result;
}


