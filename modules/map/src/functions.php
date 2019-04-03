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


add_action('init', 'go_map_page');
function go_map_page(){
    $map_name = get_option( 'options_go_locations_map_map_link');
    //add_rewrite_rule( "store", 'index.php?query_type=user_blog&uname=$matches[1]', "top");
    add_rewrite_rule( $map_name, 'index.php?' . $map_name . '=true', "top");
}

/* Query Vars */
add_filter( 'query_vars', 'go_map_register_query_var' );
function go_map_register_query_var( $vars ) {
    $map_name = get_option( 'options_go_locations_map_map_link');
    $vars[] = $map_name;
    return $vars;
}

/* Template Include */
add_filter('template_include', 'go_map_template_include', 1, 1);
function go_map_template_include($template){
    $map_name = get_option( 'options_go_locations_map_map_link');
    global $wp_query; //Load $wp_query object
    $page_value = ( isset($wp_query->query_vars[$map_name]) ? $wp_query->query_vars[$map_name] : false ); //Check for query var "blah"

    if ($page_value && $page_value == "true") { //Verify "blah" exists and value is "true".
        return plugin_dir_path(__FILE__).'templates/go_map_template.php'; //Load your template or file
    }

    return $template; //Load normal template when $page_value != "true" as a fallback
}