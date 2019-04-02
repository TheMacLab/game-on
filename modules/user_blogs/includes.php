<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */

//conditional includes
if ( !is_admin() ) {
    include_once('src/public_ajax.php');

}else if ( defined( 'DOING_AJAX' )) {
    include_once('src/ajax.php');
    //include_once('src/public_ajax.php');

    //Blogs
    //add_action( 'wp_ajax_go_blog_lightbox_opener', 'go_blog_lightbox_opener' ); //OK
    add_action( 'wp_ajax_go_blog_opener', 'go_blog_opener' ); //OK
    add_action( 'wp_ajax_go_blog_trash', 'go_blog_trash' ); //OK
    add_action( 'wp_ajax_go_blog_submit', 'go_blog_submit' ); //OK
    add_action( 'wp_ajax_go_blog_user_task', 'go_blog_user_task');
    add_action( 'wp_ajax_go_blog_favorite_toggle', 'go_blog_favorite_toggle');
}else{
    //include_once('admin/admin.php');
}

//always include
include_once('src/functions.php');
include_once('src/revisions.php');