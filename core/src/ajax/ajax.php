<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/1/19
 * Time: 11:28 PM
 */

function go_admin_remove_notification() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }
    check_ajax_referer( 'go_admin_remove_notification_' . get_current_user_id() );

    update_option( 'go_display_admin_explanation', false );

    die( );
}


function go_deactivate_plugin() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }
    check_ajax_referer( 'go_deactivate_plugin_' . get_current_user_id() );

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $plugin = plugin_basename( __FILE__ );
    deactivate_plugins( $plugin );
    die();
}

//Get the time on task from two times as timestamps
//or as one variable passed as a number of seconds
function go_time_on_task($current_time, $TIMESTAMP =false){
    if ($TIMESTAMP != false) {
        $delta_time = strtotime($current_time) - strtotime($TIMESTAMP);
        $d = 'days';
        $h = 'hours';
        $m = 'minutes';
        $s = 'seconds';
        $title = "Time On Task: ";
    }else{
        $delta_time = $current_time;
        $d = 'd';
        $h = 'h';
        $m = 'm';
        $s = 's';
        $title = "";
    }
    $days = floor( $delta_time/86400);
    $delta_time = $delta_time % 86400;
    $hours = floor($delta_time / 3600);
    $delta_time = $delta_time % 3600;
    $minutes = floor($delta_time / 60);
    $delta_time = $delta_time % 60;
    $seconds = $delta_time;




    //$time_on_task = "{$days} days {$hours} hours and {$minutes} minutes and {$seconds} seconds";
    $time_on_task = "";
    if ($days>0){
        $time_on_task .= "{$days}{$d} ";
    }
    if ($hours>0){
        $time_on_task .= "{$hours}{$h} ";
    }
    if ($minutes>0){
        $time_on_task .= "{$minutes}{$m} ";
    }
    if ($seconds>0){
        $time_on_task .= "{$seconds}{$s}";
    }
    $result ="";
    $time = date("m/d/y g:i A", strtotime($TIMESTAMP));
    if ($TIMESTAMP != false) {
        $result .= "<div style='text-align:right;'>Time Submitted: " . $time . "</div>";
    }
    $result .= "<div style='text-align:right;'>". $title .$time_on_task . "</div></div>";
    return $result;
}
