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



    //Clipboard
    add_action( 'wp_ajax_go_clipboard_stats', 'go_clipboard_stats' ); //OK
    add_action( 'wp_ajax_go_clipboard_activity', 'go_clipboard_activity' ); //OK
    add_action( 'wp_ajax_go_clipboard_messages', 'go_clipboard_messages' ); //OK
    add_action( 'wp_ajax_go_clipboard_store', 'go_clipboard_store' ); //OK
    add_action( 'wp_ajax_go_clipboard_stats_dataloader_ajax', 'go_clipboard_stats_dataloader_ajax' ); //OK
    add_action( 'wp_ajax_go_clipboard_store_dataloader_ajax', 'go_clipboard_store_dataloader_ajax' ); //OK
    add_action( 'wp_ajax_go_clipboard_messages_dataloader_ajax', 'go_clipboard_messages_dataloader_ajax' ); //OK
    add_action( 'wp_ajax_go_clipboard_activity_dataloader_ajax', 'go_clipboard_activity_dataloader_ajax' ); //OK
    add_action( 'wp_ajax_go_make_store_dropdown_ajax', 'go_make_store_dropdown_ajax' );
    add_action( 'wp_ajax_go_make_cpt_select2_ajax', 'go_make_cpt_select2_ajax' );
    add_action( 'wp_ajax_go_make_taxonomy_dropdown_ajax', 'go_make_taxonomy_dropdown_ajax' );

    include_once('src/ajax.php');

}else{
    //include_once('admin/admin.php');
    include_once('src/admin.php');
}

//always include

//include_once('functions.php');