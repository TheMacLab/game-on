<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/31/18
 * Time: 12:25 PM
 */


/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header();


/////////////////////USER HEADER
    $user = get_query_var('uname');
    $user_obj = get_user_by('login',$user);
    if($user_obj)
    {
       $user_id = $user_obj->ID;
    }else{
        $user_id = 0;
    }
    $current_user_id = get_current_user_id();
    ?>

    <?php
    $user_fullname = $user_obj->first_name.' '.$user_obj->last_name;
    $user_login =  $user_obj->user_login;
    $user_display_name = $user_obj->display_name;
    $user_website = $user_obj->user_url;
    $page_title = $user_display_name . "'s Blog";
    ?><script>
    document.title = "<?php echo $page_title; ?>";//set page title
    jQuery( document ).ready(function() {//create lightbox links for images
        go_lightbox_blog_img();
    });
    </script><?php
    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');
    if ($use_local_avatars){
        $user_avatar_id = get_user_option( 'go_avatar', $user_id );
        $user_avatar = wp_get_attachment_image($user_avatar_id);
    }
    if (empty($user_avatar) && $use_gravatar) {
        $user_avatar = get_avatar( $user_id, 150 );
    }


    ?>
    <div id='go_stats_lite_wrapper'>
    <div id='go_stats_lay_lite' class='go_datatables'>
        <div id='go_stats_header_lite'>
            <div class="go_stats_id_card">
                <div class='go_stats_gravatar'><?php echo $user_avatar; ?></div>

                <div class='go_stats_user_info'>
                    <?php echo "<h2>{$user_fullname}</h2>{$user_display_name}<br>"; ?>
                    <?php
                    go_user_links($user_id, true, true, true, false, true, true);
                    if ($current_user_id === $user_id){
                        echo '<button class="go_blog_opener" blog_post_id ="">New Post</button>';
                     }
                    ?>


                </div>

            </div>

        </div>
    </div>
    </div>
    <?php

    /// END USER HEADER

// get the username based from uname value in query var request.

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
//$paged = get_query_var('paged');
// Query param
$arg = array(
    'post_type'         => 'go_blogs',
    'posts_per_page'    => 5,
    'orderby'           => 'publish_date',
    'order'             => 'DESC',
    'author_name'       => $user,
    'paged' => $paged,
    'post_status' => 'any'
);
//build query
$query = new WP_Query( $arg );

// get query request
$posts = $query->get_posts();

//video options
    $go_lightbox_switch = get_option( 'options_go_video_lightbox' );
    $go_video_unit = get_option ('options_go_video_width_unit');
    if ($go_video_unit == 'px'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_pixels')."px";
    }
    if ($go_video_unit == '%'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_percent')."%";
    }

// check if there's any results
if ( empty($posts) ) {
    echo "Author doesn't have any posts";
} else {
    echo "<div id='go_wrapper' data-lightbox='{$go_lightbox_switch}' data-maxwidth='{$go_fitvids_maxwidth}' style='background-color: #f2f2f2' >";
    ?>

    <div class="go_blog_container1" style="display: flex; justify-content: center;">
    <div class="go_blog_container" style="    display: flex;
    justify-content: center;
    flex-direction: column;
    padding: 20px;
    flex-grow: 1;
    max-width: 800px;"><?php
   foreach ($posts as $post){
       $post = json_decode(json_encode($post), True);//convert stdclass to array by encoding and decoding
       $post_id = $post['ID'];
       go_blog_post($post_id, false, true);
       //go_user_feedback_container($post_id);
   }
   ?>

   <div class="pagination">
    <?php
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
    ?>
    </div>
    </div>
    </div>
    </div>


    <?php

}
    go_hidden_footer();
?>
 <script>

        jQuery( document ).ready( function() {
            //console.log("opener1");
            //jQuery(".go_blog_opener").one("click", function(e){
            //    go_blog_opener( this );
            //});
            // remove existing editor instance
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_lightbox');
            //jQuery('#go_hidden_mce').remove();
            //jQuery('#go_hidden_mce_edit').remove();
            jQuery('#wpadminbar').css('z-index', 99999);
            go_stats_links();
        });

    </script>
<?php

get_footer();