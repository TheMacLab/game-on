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
add_filter( 'show_admin_bar', 'go_display_admin_bar' );

get_header();

/////////////////////USER HEADER
///
///
    //$user_id = 0;
    $user = get_query_var('uname');

    $user_obj = get_user_by('login',$user);
if($user_obj)
{
   $user_id = $user_obj->ID;
}else{
    $user_id = 0;
}



    ?>
    <input type="hidden" id="go_stats_hidden_input" value="<?php echo $user_id; ?>"/>
    <?php
    $user_fullname = $user_obj->first_name.' '.$user_obj->last_name;
    $user_login =  $user_obj->user_login;
    $user_display_name = $user_obj->display_name;
    $user_website = $user_obj->user_url;

    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');
    if ($use_local_avatars){
        $user_avatar_id = get_user_meta( $user_id, 'go_avatar', true );
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
                    go_user_links($user_id,false, true, false, false, true);
                    ?>

                </div>

            </div>

        </div>
    </div>
    <?php
/// END USER HEADER

// get the username based from uname value in query var request.


// Query param
$arg = array(
    'post_type'         => 'go_blogs',
    'posts_per_page'    => 15,
    'orderby'           => 'date',
    'order'             => 'DESC',
    'author_name'       => $user,
);
//build query
$query = new WP_QUery( $arg );

// get query request
$posts = $query->get_posts();

// check if there's any results
if ( empty($posts) ) {
    echo "Author doesn't have any posts";
} else {
    ?>
    <div class="go_blog_container1" style="display: flex; justify-content: center;">
    <div class="go_blog_container" style="display: flex; justify-content: center; flex-direction: column; padding:20px;"><?php
   foreach ($posts as $post){
       $post = json_decode(json_encode($post), True);//convert stdclass to array by encoding and decoding
       $title =  $post['post_title'];
       $content =  $post['post_content'];
       $date = $post['post_date'];
       ?>
       <div class="go_blog_post_wrapper" style="max-width: 800px; ">
           <hr>
           <h2><?php echo $title;?></h2>
           <div><?php echo $date;?></div>
           <p></p>
           <div><?php echo $content;?></div>
       </div>


       <?php
   }
   echo "</div></div>";
}

get_footer();