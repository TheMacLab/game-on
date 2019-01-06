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
    include_once('src/public_ajax.php');
    include_once('src/ajax.php');

    add_action( 'wp_ajax_go_update_last_map', 'go_update_last_map' ); //OK
    add_action( 'wp_ajax_go_to_this_map', 'go_to_this_map' ); //OK
    add_action( 'wp_ajax_nopriv_go_update_last_map', 'go_update_last_map' ); //OK
    add_action( 'wp_ajax_nopriv_go_to_this_map', 'go_to_this_map' ); //OK
    add_action( 'wp_ajax_go_user_map_ajax', 'go_user_map_ajax' );

}else{
    //include_once('admin/admin.php');
}

//always include
include_once('src/functions.php');