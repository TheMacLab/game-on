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
    if (empty($blog_post_id)) {
        // Insert the post into the database
        wp_insert_post($my_post);
    }else{
        wp_update_post($my_post);
    }
}

