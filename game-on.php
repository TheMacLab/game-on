<?php
/*
Plugin Name: Game-On
Plugin URI: http://maclab.guhsd.net/game-on
Description: Gamification tools for teachers.
Author: Valhalla Mac Lab
Author URI: https://github.com/TheMacLab/game-on/blob/master/README.md
Version: 4.22
*/

$version = 4.22;
global $version;

$go_js_version = 4.22;
global $go_js_version;

$go_css_version = 4.22;
global $go_css_version;

include( 'includes/wp-frontend-media-master/frontend-media.php' );

if ( is_admin() ) {
//add conditionals to these
    include_once('includes/acf/acf.php');//try to block this from non admin users
    include_once('includes/wp-term-order/wp-term-order.php'); //try to block this from non admin users
    foreach (glob(plugin_dir_path(__FILE__) . "custom-acf-fields/*.php") as $file) {
        include_once $file;
    }
}else if ( defined( 'DOING_AJAX' )) {
    //include( 'includes/wp-frontend-media-master/frontend-media.php' );
   // include_once('includes/acf/acf.php');//try to block this from non admin users
   // include_once('includes/wp-term-order/wp-term-order.php'); //try to block this from non admin users
    foreach (glob(plugin_dir_path(__FILE__) . "custom-acf-fields/*.php") as $file) {
        //include_once $file;
    }

}else{
    //INCLUDES on Public
    //include( 'includes/wp-frontend-media-master/frontend-media.php' );


    include_once('includes/acf/acf.php');//try to block this from non admin users
    //include_once('includes/wp-term-order/wp-term-order.php'); //try to block this from non admin users
    foreach (glob(plugin_dir_path(__FILE__) . "custom-acf-fields/*.php") as $file) {
       // include_once $file;
    }
}

//in the main directory
foreach ( glob( plugin_dir_path( __FILE__ ) . "*.php" ) as $file ) {
    include_once $file;
}

//in the all directory
foreach ( glob( plugin_dir_path( __FILE__ ) . "src/all/*.php" ) as $file ) {
    include_once $file;
}

// include external resources
foreach ( glob( plugin_dir_path( __FILE__ ) . "includes/*.php" ) as $file ) {
    include_once $file;
}


////////////////////////////
/// IS THIS NOT A BACKEND PAGE (PUBLIC FACING PAGE)
if ( !is_admin() ) {

	//in the admin_and_public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/admin_and_public/*.php" ) as $file ) {
        include_once $file;
    }

    //in the public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/public/*.php" ) as $file ) {
        include_once $file;
    }

    //in the public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/public/ajax/*.php" ) as $file ) {
        include_once $file;
    }

    add_action("wp", "go_include_tasks");


    foreach ( glob( plugin_dir_path( __FILE__ ) . "js/front/*.php" ) as $file ) {
        include_once $file;
    }

    foreach ( glob( plugin_dir_path( __FILE__ ) . "styles/front/*.php" ) as $file ) {
        include_once $file;
    }

} else if ( defined( 'DOING_AJAX' )) { //ELSE THIS IS AN AJAX CALL


    //in the public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/public/ajax/*.php" ) as $file ) {
        include_once $file;
    }

    //in the public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/admin/ajax/*.php" ) as $file ) {
        include_once $file;
    }

    //in the all directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/ajax/*.php" ) as $file ) {
        include_once $file;
    }

    foreach (glob(plugin_dir_path(__FILE__) . "src/public/tasks/ajax_tasks/*.php") as $file) {
        include_once $file;
    }

    //maybe remove?
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/admin/*.php" ) as $file ) {
        //include_once $file;
    }

    /*
    * AJAX Hooks
    */
//Tasks
    add_action( 'wp_ajax_go_unlock_stage', 'go_unlock_stage' ); //OK
    add_action( 'wp_ajax_go_task_change_stage', 'go_task_change_stage' ); //OK
    add_action( 'wp_ajax_go_update_last_map', 'go_update_last_map' ); //OK
    add_action( 'wp_ajax_go_to_this_map', 'go_to_this_map' ); //OK
//Stats
    add_action( 'wp_ajax_go_admin_bar_stats', 'go_admin_bar_stats' ); //OK
    add_action( 'wp_ajax_go_stats_task_list', 'go_stats_task_list' ); //OK
    add_action( 'wp_ajax_go_stats_item_list', 'go_stats_item_list' ); //OK
    add_action( 'wp_ajax_go_stats_activity_list', 'go_stats_activity_list' ); //OK
    add_action( 'wp_ajax_go_stats_single_task_activity_list', 'go_stats_single_task_activity_list' ); //OK
    add_action( 'wp_ajax_go_stats_badges_list', 'go_stats_badges_list' ); //OK
    add_action( 'wp_ajax_go_stats_groups_list', 'go_stats_groups_list' ); //OK
    add_action( 'wp_ajax_go_stats_leaderboard', 'go_stats_leaderboard' ); //OK
    add_action( 'wp_ajax_go_stats_lite', 'go_stats_lite' ); //OK
    add_action( 'wp_ajax_go_stats_about', 'go_stats_about' ); //OK
    add_action( 'wp_ajax_go_activity_dataloader_ajax', 'go_activity_dataloader_ajax');
//add_action( 'wp_ajax_go_tasks_dataloader_ajax', 'go_tasks_dataloader_ajax');
//Activation
    add_action( 'wp_ajax_go_admin_remove_notification', 'go_admin_remove_notification' ); //OK
//Store
    add_action( 'wp_ajax_go_get_purchase_count', 'go_get_purchase_count' ); //OK
    add_action( 'wp_ajax_nopriv_go_get_purchase_count', 'go_get_purchase_count' ); //OK
    add_action( 'wp_ajax_go_buy_item', 'go_buy_item' ); //OK
    add_action( 'wp_ajax_nopriv_go_buy_item', 'go_buy_item' ); //OK
    add_action( 'wp_ajax_go_the_lb_ajax', 'go_the_lb_ajax' ); //OK
    add_action( 'wp_ajax_nopriv_go_the_lb_ajax', 'go_the_lb_ajax' ); //OK
//Clipboard
    add_action( 'wp_ajax_go_clipboard_intable', 'go_clipboard_intable' ); //OK
//add_action( 'wp_ajax_go_clipboard_intable_messages', 'go_clipboard_intable_messages' );
    add_action( 'wp_ajax_go_clipboard_intable_activity', 'go_clipboard_intable_activity' ); //OK
    add_action( 'wp_ajax_go_clipboard_save_filters', 'go_clipboard_save_filters' ); //OK
//Messages
    add_action( 'wp_ajax_go_create_admin_message', 'go_create_admin_message' );//OK
    add_action( 'wp_ajax_go_send_message', 'go_send_message' ); //OK
    add_action( 'wp_ajax_go_admin_messages', 'go_admin_messages' );//OK
//Updates
    add_action( 'wp_ajax_go_update_bonus_loot', 'go_update_bonus_loot' );//OK
//Blogs
    add_action( 'wp_ajax_go_blog_lightbox_opener', 'go_blog_lightbox_opener' ); //OK
    add_action( 'wp_ajax_go_blog_opener', 'go_blog_opener' ); //OK
    add_action( 'wp_ajax_go_blog_submit', 'go_blog_submit' ); //OK
//Admin
    add_action( 'wp_ajax_go_update_admin_view', 'go_update_admin_view' ); //OK
    add_action( 'wp_ajax_check_if_top_term', 'go_check_if_top_term' ); //for term order //OK
    add_action( 'wp_ajax_go_deactivate_plugin', 'go_deactivate_plugin' );
    add_action( 'wp_ajax_go_reset_all_users', 'go_reset_all_users' ); //OK
    add_action( 'wp_ajax_go_clone_post', 'go_clone_post' );  //OK
    add_action( 'wp_ajax_go_upgade4', 'go_upgade4' ); //OK



} else {//ELSE THIS IS AN ADMIN PAGE

    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/admin/*.php" ) as $file ) {
        include_once $file;
    }

    //in the public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/admin/ajax/*.php" ) as $file ) {
        include_once $file;
    }
    //in the admin_and_public directory
    foreach ( glob( plugin_dir_path( __FILE__ ) . "src/admin_and_public/*.php" ) as $file ) {
        include_once $file;
    }
    foreach ( glob( plugin_dir_path( __FILE__ ) . "js/admin/*.php" ) as $file ) {
        include_once $file;
    }
    foreach ( glob( plugin_dir_path( __FILE__ ) . "styles/admin/*.php" ) as $file ) {
        include_once $file;
    }

}

function go_include_tasks()
{
    if (is_singular( 'tasks' )) {
        foreach (glob(plugin_dir_path(__FILE__) . "src/public/tasks/*.php") as $file) {
            include_once $file;
        }

        foreach (glob(plugin_dir_path(__FILE__) . "src/public/tasks/ajax_tasks/*.php") as $file) {
            include_once $file;
        }
    }
}










//create non-persistent cache group
//This is used by the transients
wp_cache_add_non_persistent_groups( 'go_single' );


/*
 * Plugin Activation Hooks
 */
register_activation_hook( __FILE__, 'go_update_db' );
register_activation_hook( __FILE__, 'go_open_comments' );
register_activation_hook( __FILE__, 'go_tsk_actv_activate' );
register_activation_hook( __FILE__, 'go_map_activate' );
register_activation_hook( __FILE__, 'go_store_activate' );
register_activation_hook( __FILE__, 'go_media_access' );

register_activation_hook( __FILE__, 'go_flush_rewrites' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

register_activation_hook( __FILE__, 'go_update_store_html' );



/*
 * Admin Menu & Admin Bar
 */

// actions
add_action( 'admin_bar_init', 'go_admin_bar' );

/*
 * Admin & Login Page
 */
add_action( 'admin_enqueue_scripts', 'go_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'go_admin_styles' );


add_action( 'admin_enqueue_scripts', 'go_admin_includes' );
add_action( 'admin_enqueue_scripts', 'go_acf_scripts' );


/*
 * Front-end
 */

// actions
//add_action( 'wp_head', 'go_stats_overlay' );
add_action( 'wp_enqueue_scripts', 'go_scripts' );
add_action( 'wp_enqueue_scripts', 'go_styles' );
add_action( 'wp_enqueue_scripts', 'go_includes' );



/*
 * User Data
 */
add_action( 'delete_user', 'go_user_delete' );
add_action( 'user_register', 'go_user_registration' );




/**
 * Miscellaneous Filters
 */

// mitigating compatibility issues with Jetpack plugin by Automatic
// (https://wordpress.org/plugins/jetpack/).
add_filter( 'jetpack_enable_open_graph', '__return_false', 99 );





?>
