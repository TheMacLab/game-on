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
    include_once('src/public_ajax.php');
}else if ( defined( 'DOING_AJAX' )) {
    include_once('src/public_ajax.php');
    include_once('src/ajax.php');
    add_action( 'wp_ajax_go_task_change_stage', 'go_task_change_stage' ); //OK
}else{
    include_once('src/admin.php');
}

//always include
include_once('src/functions.php');
include_once('timer/includes.php');