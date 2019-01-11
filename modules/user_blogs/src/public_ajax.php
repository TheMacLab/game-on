<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/4/19
 * Time: 2:19 AM
 */

function go_blog_form($blog_post_id, $suffix, $go_blog_task_id = null, $i = null, $bonus = false){

    $file_toggle = false;
    $url_toggle = false;
    $video_toggle = false;
    $text_toggle = true;
    $restrict_mime_types = false;
    $content ='';
    $title ='';
    $custom_fields = null;
    $url_content = null;
    $video_content = null;
    $media_content = null;
    $min_words = null;


    if (!empty($blog_post_id)) {
        $post = get_post($blog_post_id, OBJECT, 'edit');
        $content = $post->post_content;
        $title = get_the_title($blog_post_id);
        $blog_meta = get_post_custom($blog_post_id);
        $url_content = (isset($blog_meta['go_blog_url'][0]) ? $blog_meta['go_blog_url'][0] : null);
        $media_content = (isset($blog_meta['go_blog_media'][0]) ? $blog_meta['go_blog_media'][0] : null);
        $video_content = (isset($blog_meta['go_blog_video'][0]) ? $blog_meta['go_blog_video'][0] : null);

        $go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : $go_blog_task_id);
        $i = (isset($blog_meta['go_blog_task_stage'][0]) ? $blog_meta['go_blog_task_stage'][0] : $i); //set this meta for bonus


    }
    if(!empty($go_blog_task_id)) {
        $custom_fields = get_post_custom($go_blog_task_id);
        if (!$bonus === true) {
            $url_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0] : null);
            $file_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0] : null);
            $video_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_video'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_video'][0] : null);
            $text_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0] : true);
            $restrict_mime_types = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0] : null);
            $min_words = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0] : null);
            $required_string = (isset($custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0] : null);

        }
        else{
            $url_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_url'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_url'][0] : null);
            $file_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0] : null);
            $video_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_video'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_video'][0] : null);
            $text_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0] : null);
            $restrict_mime_types = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_restrict_file_types'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_restrict_file_types'][0] : null);
            $min_words = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_minimum_length'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_minimum_length'][0] : null);
            $required_string = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_url_url_validation'][0]) ?  $custom_fields['go_bonus_stage_blog_options_bonus_blog_url_url_validation'][0] : null);

        }
        if(empty($title)){
            $title = get_the_title($go_blog_task_id);
        }

    }


    echo "<div id='go_blog_div'>";
    echo "<div>Title:<div><input style='width: 100%;' id='go_blog_title".$suffix."' type='text' placeholder='' value ='{$title}' data-blog_post_id ='{$blog_post_id}'></div> </div>";

    if ($url_toggle) {
        echo "<div>URL:";
        if ($required_string){
            echo " (url must contain \"".$required_string."\")";
        }
        echo "<div>";
        //go_url_check($custom_fields, $i, $i, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status, null, 'Enter URL', 'go_result_url' , $url_content);
        go_url_check_blog ('Enter URL', 'go_result_url'.$suffix , $url_content);
        echo "</div> </div>";
    }

    if($file_toggle) {

        //go_upload_check($custom_fields, $i, $i, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status, null, $media_content);
        //$restrict_mime_types = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0] : null);
        if ($restrict_mime_types) {
            $mime_types = unserialize(isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_allowed_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_allowed_types'][0] : null);
            $mime_types_array = is_array($mime_types) ? $mime_types : array();
            $mime_types = implode(",", $mime_types_array);
            $mime_types_pretty = implode(", ", $mime_types_array);
            $mime_types_count = count($mime_types_array);
        }else{
            $mime_types = '';
            $mime_types_pretty = '';
            $mime_types_count = 0;
        }

        echo "<div>Attach File:";

        if (!empty($mime_types_pretty))
        {
            if ($mime_types_count > 1) {
                echo " (Allowed file types: " . $mime_types_pretty . ")";
            }else if ($mime_types_count == 1){
                echo " (Allowed file type: " . $mime_types_pretty . ")";
            }
        }
        echo "<div>";

        go_upload_check_blog ($media_content, 'go_result_media'.$suffix, $custom_fields, $i, $mime_types);
        echo "</div> </div>";
    }

    if ($video_toggle) {
        echo "<div>Video Link:<div>";
        //go_url_check($custom_fields, $i, $i, $go_actions_table_name, $user_id, $post_id, $bonus, $bonus_status, null, "URL of Video", 'go_result_video', $video_content);
        go_url_check_blog ('URL of Video', 'go_result_video'.$suffix, $video_content);
        echo "</div> </div>";
    }

    if($text_toggle) {
        $settings = array(//'tinymce'=> array( 'menubar'=> true, 'toolbar1' => 'undo,redo', 'toolbar2' => ''),
            //'tinymce'=>true,
            //'wpautop' =>false,
            'textarea_name' => 'go_result'.$suffix, 'media_buttons' => true, //'teeny' => false,
            'quicktags' => array('buttons' => ''), 'menubar' => false, 'drag_drop_upload' => true);
        wp_editor($content, 'go_blog_post'.$suffix, $settings);
        //echo "<button id='go_save_button' class='progress left'  check_type='blog' button_type='save'  admin_lock='true' >Save Draft</button> ";
    }
    echo "</div>";
    if($text_toggle) { //add stuff below the mce window if it is shown

        //word Count
        if ($min_words > 0) {
            echo "<div id='go_blog_min' style='text-align:right'><span class='char_count'>" . $min_words . "</span> Words Required</div>";
        }

        //Save Draft Button
        echo "<button id='go_save_button{$suffix}' class='go_save_button progress left'  status='{$i}' check_type='skip' button_type='save'  admin_lock='true' blog_post_id='{$blog_post_id}' blog_suffix='{$suffix}' task_id='{$go_blog_task_id}'>Save Draft</button>";
        if($suffix =='_lightbox') {
            ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery('#go_save_button_lightbox').one("click", function (e) {
                        go_blog_submit( this, false );
                    });
                });

            </script>
            <?php
        }
    }
}

function go_blog_post($blog_post_id){

    $current_user = get_current_user_id();

    $file_toggle = false;
    $url_toggle = false;
    $video_toggle = false;
    $text_toggle = true;
    $url_content = null;
    $video_content = null;
    $media_content = null;
    $min_words = null;

    if (empty($blog_post_id)) {
        return;
    }
        $post = get_post($blog_post_id, OBJECT, 'edit');
        $author_id = $post->post_author;
        $content = $post->post_content;
        $content = apply_filters( 'go_awesome_text', $content );
        $title = get_the_title($blog_post_id);
        $blog_meta = get_post_custom($blog_post_id);
        $go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : null);
        $i = (isset($blog_meta['go_blog_task_stage'][0]) ? $blog_meta['go_blog_task_stage'][0] : null);
        //if $i (task stage) is not set, then this must be a bonus stage

    if(!empty($go_blog_task_id)) {//if this post was submitted from a task, then add the task required fields
        $custom_fields = get_post_custom($go_blog_task_id);
        $task_title = get_the_title($go_blog_task_id);
        $task_url = get_permalink($go_blog_task_id);
        $stage = $i + 1;
        if ($i != null) {
            $url_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0] : false);
            $file_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0] : false);
            $video_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_video'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_video'][0] : false);
            $text_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0] : true);
        }
        else{
            $url_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_url'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_url'][0] : false);
            $file_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0] : false);
            $video_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_video'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_video'][0] : false);
            $text_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0] : true);
        }
        if(empty($title)){
            $title = $task_title;
        }

    }

    ?><script>

        jQuery( document ).ready(function() {
            go_lightbox_blog_img();
        });
    </script><?php


    echo "<div class=\"go_blog_post_wrapper\" style=\"padding: 10px;margin: 10px; background-color: white;\">";
    /*
    if (!empty($task_title) && $stage > 0) {
        echo "<div style='font-size: .8em;'>Submitted on <a href='{$task_url}'>{$task_title} stage {$stage}</a>.</div>";
    }
    */
    echo "<h2><a href='{$task_url}'>". $title . "</a></h2>";

    if($url_toggle){
        $url_content = (isset($blog_meta['go_blog_url'][0]) ?  $blog_meta['go_blog_url'][0] : null);
        if (!empty($url_content)){
            go_print_URL_check_result($url_content);
        }
    }

    if($file_toggle){
        $media_content = (isset($blog_meta['go_blog_media'][0]) ?  $blog_meta['go_blog_media'][0] : null);
        if (!empty($media_content)){
            go_print_upload_check_result($media_content);
        }
    }

    if($video_toggle){
        $video_content = (isset($blog_meta['go_blog_video'][0]) ?  $blog_meta['go_blog_video'][0] : null);
        if (!empty($video_content)){
            //$video_content =   "Video Submitted : <p>$video_content </p>";
            $video_content = apply_filters( 'go_awesome_text', $video_content );
            echo "$video_content";
        }
    }


    if($text_toggle) {
        echo $content;
    }
    if ($current_user == $author_id) {
        echo '<button class="go_blog_opener" blog_post_id ="' . $blog_post_id . '">edit post</button>';
    }
    echo "</div>";




}
