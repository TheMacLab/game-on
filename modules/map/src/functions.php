<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/2/19
 * Time: 12:51 AM
 */

//set the default map on login
function go_default_map($user_login, $user){
    $default_map = get_option('options_go_locations_map_default', '');
    //$user = $user;

    $user_id = $user->ID;
    if ($default_map !== '') {
        update_user_option($user_id, 'go_last_map', $default_map);
    }
}
add_action('wp_login', 'go_default_map', 10, 2);
