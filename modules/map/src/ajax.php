<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 10:00 PM
 */

/**
 * @param bool $map_id
 */
function go_update_last_map($map_id = false) {

    check_ajax_referer( 'go_update_last_map' );
    if(empty($_POST) || !isset($_POST)) {
        ajaxStatus('error', 'Nothing to update.');
    } else {
        try {
            if (!$map_id){
                $mapid = $_POST['goLastMap'];
            }
            $user_id = get_current_user_id();
            update_user_option( $user_id, 'go_last_map', $mapid );
            $user_id = $_POST['uid'];
            go_make_single_map($mapid, true, $user_id);

            die();
        } catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}


/**
 *
 */
function go_to_this_map(){
    check_ajax_referer( 'go_to_this_map');
    $user_id = get_current_user_id();
    $map_id = $_POST['map_id'];
    update_user_option( $user_id, 'go_last_map', $map_id );

    $map_url = get_option('options_go_locations_map_map_link');
    $map_url = (string) $map_url;
    $go_map_link = get_permalink( get_page_by_path($map_url) );
    echo $go_map_link;
    die;
}

function go_user_map_ajax(){
    check_ajax_referer( 'go_user_map_ajax');
    $user_id = intval($_POST['uid']);
    $current_user_id = get_current_user_id();
    $last_map_id = get_user_option('go_last_map', $current_user_id);
    go_make_map_dropdown($user_id);
    go_make_single_map($last_map_id, false, $user_id);// do your thing
    die();
}