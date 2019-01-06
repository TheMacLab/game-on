<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */

//conditional includes
if ( !is_admin() ) {
    // include_once('public/public.php');


}else if ( defined( 'DOING_AJAX' )) {

    include_once('src/ajax.php');
    include_once('src/admin_ajax.php');

    //Store
    add_action( 'wp_ajax_go_get_purchase_count', 'go_get_purchase_count' ); //OK
    add_action( 'wp_ajax_nopriv_go_get_purchase_count', 'go_get_purchase_count' ); //OK
    add_action( 'wp_ajax_go_buy_item', 'go_buy_item' ); //OK
    add_action( 'wp_ajax_nopriv_go_buy_item', 'go_buy_item' ); //OK
    add_action( 'wp_ajax_go_the_lb_ajax', 'go_the_lb_ajax' ); //OK
    add_action( 'wp_ajax_nopriv_go_the_lb_ajax', 'go_the_lb_ajax' ); //OK

}else{
    include_once('src/admin.php');
    include_once('src/admin_ajax.php');


    register_activation_hook( __FILE__, 'go_update_store_html' );
}

//always include
include_once('src/functions.php');