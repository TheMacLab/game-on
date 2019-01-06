<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */

//conditional includes

if ( !is_admin() ) {
    //include_once('public/public.php');
}else if ( defined( 'DOING_AJAX' )) {
    include_once('src/ajax.php');

    //Messages
    add_action( 'wp_ajax_go_create_admin_message', 'go_create_admin_message' );//OK
    add_action( 'wp_ajax_go_send_message', 'go_send_message' ); //OK
    add_action( 'wp_ajax_go_admin_messages', 'go_admin_messages' );//OK

}else{
    //include_once('admin/admin.php');
}

//always include
include_once('src/functions.php');