<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/1/19
 * Time: 11:25 PM
 */

function go_update_admin_view (){
    check_ajax_referer( 'go_update_admin_view'  );

    if(empty($_POST) || !isset($_POST)) {
        ajaxStatus('error', 'Nothing to update.');
    } else {
        try {
            //$user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function

            $go_admin_view = $_POST['go_admin_view'];
            //check_ajax_referer('go_update_admin_view', 'security' );
            $user_id = wp_get_current_user();
            $user_id = get_current_user_id();
            update_user_option( $user_id, 'go_admin_view', $go_admin_view );
            die();
        } catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

}