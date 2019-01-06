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

    add_action( 'wp_ajax_go_update_admin_view', 'go_update_admin_view' ); //OK
}else{
    //include_once('admin/admin.php');
}

//always include
include_once('src/functions.php');

/*
 * Admin Menu & Admin Bar
 */
add_action( 'admin_bar_init', 'go_admin_bar' );