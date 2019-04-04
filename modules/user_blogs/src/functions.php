<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:41 PM
 */

//Uses the hidden footer that is in the core of GO.

function go_blog_form($blog_post_id, $suffix, $go_blog_task_id = null, $i = null, $bonus = null, $check_for_understanding = false){

    //save draft button for drafts
    //print saved info for all


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
    $post_status = null;

    if (!empty($blog_post_id)) {
        $post = get_post($blog_post_id, OBJECT, 'edit');
        $content = $post->post_content;
        $title = get_the_title($blog_post_id);
        $blog_meta = get_post_custom($blog_post_id);
        $url_content = (isset($blog_meta['go_blog_url'][0]) ? $blog_meta['go_blog_url'][0] : null);
        $media_content = (isset($blog_meta['go_blog_media'][0]) ? $blog_meta['go_blog_media'][0] : null);
        $video_content = (isset($blog_meta['go_blog_video'][0]) ? $blog_meta['go_blog_video'][0] : null);
        $post_status = get_post_status($blog_post_id);

        //$go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : $go_blog_task_id);
        //$go_blog_task_id = wp_get_post_parent_id($blog_post_id);
        $go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : null); //for posts created before v4.6
        if (empty($go_blog_task_id)) {
            $go_blog_task_id = wp_get_post_parent_id($blog_post_id);//for posts created after v4.6
        }


    }
    if($go_blog_task_id != 0) {
        $custom_fields = get_post_custom($go_blog_task_id);

        if ($bonus == true ) {
            $blog_title = (isset($custom_fields['go_bonus_stage_blog_options_bonus_title'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_title'][0] : null);
            $url_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_url_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_url_toggle'][0] : null);
            $file_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_toggle'][0] : null);
            $video_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_video'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_video'][0] : null);
            $text_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_toggle'][0] : true);
            $restrict_mime_types = (isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_restrict_file_types'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_restrict_file_types'][0] : null);
            $min_words = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_text_minimum_length'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_blog_text_minimum_length'][0] : null);
            $required_string = (isset($custom_fields['go_bonus_stage_blog_options_bonus_blog_url_url_validation'][0]) ?  $custom_fields['go_bonus_stage_blog_options_bonus_blog_url_url_validation'][0] : null);
            $is_private = (isset($custom_fields['go_bonus_stage_blog_options_bonus_private'][0]) ?  $custom_fields['go_bonus_stage_blog_options_bonus_private'][0] : false);
            if ($blog_title){
                $blog_title = " - ".$blog_title;
            }else{
                $blog_title = " - Bonus";
            }
        }
        else{
            $i = (isset($blog_meta['go_blog_task_stage'][0]) ? $blog_meta['go_blog_task_stage'][0] : $i); //set this meta for bonus
            $blog_title = (isset($custom_fields['go_stages_' . $i . '_blog_options_title'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_title'][0] : null);
            $url_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0] : null);
            $file_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0] : null);
            $video_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_video'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_video'][0] : null);
            $text_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0] : true);
            $restrict_mime_types = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_restrict_file_types'][0] : null);
            $min_words = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_minimum_length'][0] : null);
            $required_string = (isset($custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_url_url_validation'][0] : null);
            $is_private = (isset($custom_fields['go_stages_'.$i.'_blog_options_private'][0]) ?  $custom_fields['go_stages_'.$i.'_blog_options_private'][0] : false);
            if ($blog_title){
                $blog_title = " - ".$blog_title;
            }else{
                $blog_title = " - Stage ". (intval($i) +1) ;
            }
        }
        if(empty($title)){
            $title = get_the_title($go_blog_task_id);

            $title = $title . "" . $blog_title;
        }

    }else{
        $is_private = get_post_meta($blog_post_id, 'go_blog_private_post', true) ? get_post_meta($blog_post_id, 'go_blog_private_post', true) : false;

    }
    echo "<div class='go_blog_div'>";
    if( !empty($go_blog_task_id) && $is_private) {
        echo "<div ><h3>This post is private. Only you and the site administrators/instructors will be able to see it.</h3></div>";
    }
    echo "<div><h3 style='width: 100%;' data-blog_post_id ='{$blog_post_id}' id='go_blog_title{$suffix}'>".$title."</h3> </div>";

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
            if ($bonus == true) {
                $mime_types = isset($custom_fields['go_bonus_stage_blog_options_bonus_attach_file_allowed_types'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_attach_file_allowed_types'][0] : null;

            }else{
                $mime_types = isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_allowed_types'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_allowed_types'][0] : null;
            }
            if(is_serialized($mime_types)){
                $mime_types = unserialize($mime_types);
            }
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

        go_upload_check_blog ($media_content, 'go_result_media'.$suffix, $mime_types);
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
            'tinymce'=>true,
            //'wpautop' =>false,
            'textarea_name' => 'go_result'.$suffix, 'media_buttons' => true, //'teeny' => true,
             'menubar' => false, 'drag_drop_upload' => true);

        //echo "<button id='go_save_button' class='progress left'  check_type='blog' button_type='save'  admin_lock='true' >Save Draft</button> ";

        //$id = $_POST['editorID'];
        //$content = $_POST['content'];

        //wp_editor( $content, $id );
        wp_editor($content, 'go_blog_post'.$suffix, $settings);

    }
    echo "</div>";
    if($text_toggle) { //add stuff below the mce window if it is shown

        //Private Post Toggle
        if(empty($go_blog_task_id)){//only if not attached to quest
            if ($is_private){
                $checked = 'checked';
            }else{
                $checked = '';
            }
            echo "<div style='width: 100%;text-align: right;'><input type='checkbox' id='go_private_post{$suffix}' value='go_private_post{$suffix}' {$checked}> Private Post</div>";
        }
        //word Count
        if ($min_words > 0) {
            echo "<div id='go_blog_min' style='text-align:right'><span class='char_count'>" . $min_words . "</span> Words Required</div>";
        }

        echo "<p id='go_blog_stage_error_msg' style='display: none; color: red;'></p>";

        //show save button if this is a draft, reset, trashed or new post
        $allow_drafts = array("draft", "reset", "trash", null);
        if (in_array($post_status, $allow_drafts)) {
            echo "<button id='go_save_button{$suffix}' class='go_buttons go_save_button progress left'  status='{$i}' data-bonus_status='{$bonus}' check_type='skip' button_type='save{$suffix}'  admin_lock='true' blog_post_id='{$blog_post_id}' blog_suffix='{$suffix}' task_id='{$go_blog_task_id}' data-check_for_understanding ='{$check_for_understanding}'>Save Draft</button>";

        }



        //Save Draft Button
        if($suffix =='_lightbox') {
            ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery('#go_save_button_lightbox').one("click", function (e) {
                        go_blog_submit( this, true );
                    });
                });

            </script>
            <?php
        }
    }
}

//add_filter( 'option_page_capability_' . ot_options_id(), create_function( '$caps', "return '$caps';" ), 999 );

//add_filter( 'option_page_capability_' . ot_options_id(), function($caps) {return $caps;},999);


function go_blog_post($blog_post_id, $check_for_understanding = false, $with_feedback = false){
    //$blog_post_id = 10704;
    $current_user = get_current_user_id();
    $is_admin = go_user_is_admin();

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

    $go_blog_task_id = (isset($blog_meta['go_blog_task_id'][0]) ? $blog_meta['go_blog_task_id'][0] : null); //for posts created before v4.6
    if (empty($go_blog_task_id)) {
        $go_blog_task_id = wp_get_post_parent_id($blog_post_id);//for posts created after v4.6
    }

    if($go_blog_task_id != 0) {//if this post was submitted from a task, then add the task required fields

        $i = (isset($blog_meta['go_blog_task_stage'][0]) ? $blog_meta['go_blog_task_stage'][0] : null);
        //if $i (task stage) is not set, then this must be a bonus stage

        $custom_fields = get_post_custom($go_blog_task_id);
        $task_title = get_the_title($go_blog_task_id);
        $task_url = get_permalink($go_blog_task_id);
        //$stage = $i + 1;
        //need to save if bonus in meta
        if ($i != null) {
            $url_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_url_toggle'][0] : false);
            $file_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_attach_file_toggle'][0] : false);
            $video_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_video'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_video'][0] : false);
            $text_toggle = (isset($custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0]) ? $custom_fields['go_stages_' . $i . '_blog_options_blog_text_toggle'][0] : true);
        }
        else{
            $url_toggle = (isset($custom_fields['go_bonus_stage_blog_options_bonus_url_toggle'][0]) ? $custom_fields['go_bonus_stage_blog_options_bonus_url_toggle'][0] : false);
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


    echo "<div class=\"go_blog_post_wrapper go_blog_post_wrapper_$blog_post_id\" style=\"padding: 10px;margin: 10px; background-color: white;\">";
    /*
    if (!empty($task_title) && $stage > 0) {
        echo "<div style='font-size: .8em;'>Submitted on <a href='{$task_url}'>{$task_title} stage {$stage}</a>.</div>";
    }
    */
    if (!empty($task_url)) {
        echo "<h2><a href='{$task_url}'>" . $title . "</a></h2>";
    }else{
        echo "<h2>" . $title . "</a></h2>";
    }

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
    if ($current_user == $author_id) {//if current user then show edit and maybe trash
        echo "<button class='go_blog_opener' blog_post_id ='{$blog_post_id}' data-check_for_understanding ='{$check_for_understanding}'>edit post</button>";
        if (($current_user == $author_id && $check_for_understanding == false  && empty($go_blog_task_id))) {
            echo '<span class="go_blog_trash" blog_post_id ="' . $blog_post_id . '"><i class="fa fa-trash fa-2x"></i></span>';
        }

    }else if ($is_admin) {
        echo '<span class="go_blog_trash" blog_post_id ="' . $blog_post_id . '"><i class="fa fa-times-circle fa-2x"></i></span>';
    }
    if ($with_feedback){
        do_action('go_blog_template_after_post', $blog_post_id);
    }

    echo "</div>";
}

// Register Custom Taxonomy
function go_blog_tags() {

    $labels = array(
        'name'                       => _x( 'Task Tags', 'Taxonomy General Name', 'go' ),
        'singular_name'              => _x( 'Task Tag', 'Taxonomy Singular Name', 'go' ),
        'menu_name'                  => __( 'Task Tags', 'go' ),
        'all_items'                  => __( 'All Items', 'go' ),
        'parent_item'                => __( 'Parent Item', 'go' ),
        'parent_item_colon'          => __( 'Parent Item:', 'go' ),
        'new_item_name'              => __( 'New Item Name', 'go' ),
        'add_new_item'               => __( 'Add New Item', 'go' ),
        'edit_item'                  => __( 'Edit Item', 'go' ),
        'update_item'                => __( 'Update Item', 'go' ),
        'view_item'                  => __( 'View Item', 'go' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'go' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'go' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'go' ),
        'popular_items'              => __( 'Popular Items', 'go' ),
        'search_items'               => __( 'Search Items', 'go' ),
        'not_found'                  => __( 'Not Found', 'go' ),
        'no_terms'                   => __( 'No items', 'go' ),
        'items_list'                 => __( 'Items list', 'go' ),
        'items_list_navigation'      => __( 'Items list navigation', 'go' ),
    );
    $rewrite = array(
        'slug'                       => 'user_posts',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => false,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewrite,
    );
    register_taxonomy( 'go_blog_tags', array( 'go_blogs' ), $args );

}
add_action( 'init', 'go_blog_tags', 0 );


// Register Custom Post Type
function go_blogs() {

    $labels = array(
        'name'                  => _x( 'User Blog Posts', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'User Blog Post', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'User Blog Posts', 'text_domain' ),
        'name_admin_bar'        => __( 'User Blog Post', 'text_domain' ),
        'archives'              => __( 'Item Archives', 'text_domain' ),
        'attributes'            => __( 'Item Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
        'all_items'             => __( 'All Items', 'text_domain' ),
        'add_new_item'          => __( 'Add New Item', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Item', 'text_domain' ),
        'edit_item'             => __( 'Edit Item', 'text_domain' ),
        'update_item'           => __( 'Update Item', 'text_domain' ),
        'view_item'             => __( 'View Item', 'text_domain' ),
        'view_items'            => __( 'View Items', 'text_domain' ),
        'search_items'          => __( 'Search Item', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
        'items_list'            => __( 'Items list', 'text_domain' ),
        'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter items list', 'text_domain' ),

    );
    $rewrite = array(
        'slug'                  => 'blogs',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'User Blog Post', 'text_domain' ),
        'description'           => __( 'User Blog Posts', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'author', 'revisions' ),
        'taxonomies'            => array( 'go_blog_tags' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        //'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'go_blogs', $args );

}
add_action( 'init', 'go_blogs', 0 );

// Register custom post status
function go_custom_post_status(){
    register_post_status( 'unread', array(
        'label'                     => _x( 'Unread', 'post' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Unread <span class="count">(%s)</span>', 'Unread <span class="count">(%s)</span>' ),
    ) );

    register_post_status( 'read', array(
        'label'                     => _x( 'Read', 'post' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Read <span class="count">(%s)</span>', 'Read <span class="count">(%s)</span>' ),
    ) );

    register_post_status( 'reset', array(
        'label'                     => _x( 'Reset', 'post' ),
        'public'                    => false,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Reset <span class="count">(%s)</span>', 'Reset <span class="count">(%s)</span>' ),
    ) );

    register_post_status( 'revise', array(
        'label'                     => _x( 'Revise', 'post' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Revise <span class="count">(%s)</span>', 'Revise <span class="count">(%s)</span>' ),
    ) );
}
add_action( 'init', 'go_custom_post_status' );


function go_custom_rewrite() {
    // we are telling wordpress that if somebody access yoursite.com/all-post/user/username
    // wordpress will do a request on this query var yoursite.com/index.php?query_type=user_blog&uname=username
    //flush_rewrite_rules();

    add_rewrite_rule( "^user/([^/]*)/page/(.*)/?", 'index.php?query_type=user_blog&uname=$matches[1]&paged=$matches[2]', "top");
    add_rewrite_rule( "^user/(.*)", 'index.php?query_type=user_blog&uname=$matches[1]', "top");

}

function go_custom_query( $vars ) {
    // we will register the two custom query var on wordpress rewrite rule
    $vars[] = 'query_type';
    $vars[] = 'uname';
    $vars[] = 'paged';
    return $vars;
}
// Then add those two functions on their appropriate hook and filter
add_action( 'init', 'go_custom_rewrite' );
add_filter( 'query_vars', 'go_custom_query' );

function go_template_loader($template){

    // get the custom query var we registered
    $query_var = get_query_var('query_type');

    // load the custom template if ?query_type=all_post is  found on wordpress url/request
    if( $query_var == 'user_blog' ){
        $directory = plugin_dir_path( __FILE__ ) . '/templates/go_user_blog_template.php';
        //return get_stylesheet_directory_uri() . 'go_user_blog.php';
        return $directory;

    }
    return $template;
}
add_filter('template_include', 'go_template_loader');




