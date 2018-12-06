<?php
/*
Plugin Name: Game-On
Plugin URI: http://maclab.guhsd.net/game-on
Description: Gamification tools for teachers.
Author: Valhalla Mac Lab
Author URI: https://github.com/TheMacLab/game-on/blob/master/README.md
Version: 4.26
*/

$go_js_version = 4.26;
global $go_js_version;

$go_css_version = 4.26;
global $go_css_version;

///////////////////////////////
//INCLUDE RESOURCES BEFORE GO
///////////////////////////////
//include_once('includes/acf/acf.php');
include( 'includes/wp-frontend-media-master/frontend-media.php' );
include_once('includes/wp-term-order/wp-term-order.php'); //try to block this from non admin users

// include external js and css resources
include_once('includes/go_enque_includes.php');//split this into public and admin
add_action( 'wp_enqueue_scripts', 'go_includes' );
add_action( 'admin_enqueue_scripts', 'go_admin_includes' );


if ( is_admin() ) {

    include_once('includes/acf/acf.php');

    include_once('custom-acf-fields/class-acf-field-order-posts.php');
    include_once('custom-acf-fields/class-acf-field-quiz.php');
    include_once('custom-acf-fields/class-acf-field-taxonomy2.php');
    include_once('custom-acf-fields/go-acf-functions.php');

    include_once('custom-acf-fields/go_enque_js_acf.php');
    add_action( 'admin_enqueue_scripts', 'go_acf_scripts' );

}else if ( defined( 'DOING_AJAX' )) {

}else{
    //INCLUDES on Public Pages
    //include_once('includes/acf/acf.php');
}


////////////////////////
//INCLUDE ON ALL PAGES
/////////////////////////
//main directory
include_once('go_acf_groups.php');

//all directory
include_once('src/all/go_admin_bar.php');
include_once('src/all/go_blogs.php');
include_once('src/all/go_cpt_blogs.php');
include_once('src/all/go_cpt_store.php');
include_once('src/all/go_cpt_task_taxonomies.php');
include_once('src/all/go_links.php');
include_once('src/all/go_mce.php');
include_once('src/all/go_mce_defaults.php');
include_once('src/all/go_media.php');
include_once('src/all/go_messages_check.php');
include_once('src/all/go_multisite.php');
include_once('src/all/go_ranks.php');
include_once('src/all/go_returns.php');
include_once('src/all/go_timer.php');
include_once('src/all/go_transients.php');
include_once('src/all/go_updates.php');
include_once('src/all/go_user_management.php');


////////////////////////////
/// CONDITIONAL INCLUDES
/////////////////////////////

if ( !is_admin() ) { //IF PUBLIC FACING PAGE

    //in the public directory
    include_once('src/public/ajax/go_checks.php');
    include_once('src/public/ajax/go_locks.php');
    include_once('src/public/ajax/go_map.php');
    include_once('src/public/ajax/go_shortcodes.php');

    //runs immediately after the global WP class object
    //that way it can check if a task
    add_action("wp", "go_include_tasks");

    include_once('js/go_enque_js.php');
    add_action( 'wp_enqueue_scripts', 'go_scripts' );

    include_once('styles/go_enque_styles.php');
    add_action( 'wp_enqueue_scripts', 'go_styles' );



} else if ( defined( 'DOING_AJAX' )) { //ELSE THIS IS AN AJAX CALL

    //in the public/ajax directory
    include_once('src/public/ajax/go_checks.php');
    include_once('src/public/ajax/go_locks.php');
    include_once('src/public/ajax/go_map.php');
    include_once('src/public/ajax/go_shortcodes.php');

    //in the admin/ajax directory
    include_once('src/admin/ajax/go_clipboard.php');
    include_once('src/admin/ajax/go_store_make_html.php');

    //in the ajax directory
    include_once('src/ajax/go_admin_ajax.php');
    include_once('src/ajax/go_blog_ajax.php');
    include_once('src/ajax/go_map_ajax.php');
    include_once('src/ajax/go_messages.php');
    include_once('src/ajax/go_public_ajax.php');
    include_once('src/ajax/go_stats.php');
    include_once('src/ajax/go_tools.php');
    include_once('src/ajax/store_lightbox.php');
    include_once('src/ajax/task-chains.php');
    include_once('src/ajax/go_construct_queries.php');

    //in the public/tasks/ajax directory
    include_once('src/public/tasks/ajax_tasks/go_tasks_and_ajax.php');
    include_once('src/public/tasks/ajax_tasks/task_shortcode.php');
    include_once('src/public/tasks/ajax_tasks/task_test_shortcode.php');

    /*
    * AJAX Hooks
    */
    //Tasks
    add_action( 'wp_ajax_go_unlock_stage', 'go_unlock_stage' ); //OK
    add_action( 'wp_ajax_go_task_change_stage', 'go_task_change_stage' ); //OK
    add_action( 'wp_ajax_go_update_last_map', 'go_update_last_map' ); //OK
    add_action( 'wp_ajax_go_to_this_map', 'go_to_this_map' ); //OK
    add_action( 'wp_ajax_nopriv_go_update_last_map', 'go_update_last_map' ); //OK
    add_action( 'wp_ajax_nopriv_go_to_this_map', 'go_to_this_map' ); //OK
    //Stats
    add_action( 'wp_ajax_go_admin_bar_stats', 'go_admin_bar_stats' ); //OK
    add_action( 'wp_ajax_go_stats_task_list', 'go_stats_task_list' ); //OK
    add_action( 'wp_ajax_go_stats_item_list', 'go_stats_item_list' ); //OK
    add_action( 'wp_ajax_go_stats_activity_list', 'go_stats_activity_list' ); //OK
    add_action( 'wp_ajax_go_stats_messages', 'go_stats_messages' ); //OK
    add_action( 'wp_ajax_go_stats_single_task_activity_list', 'go_stats_single_task_activity_list' ); //OK
    add_action( 'wp_ajax_go_stats_badges_list', 'go_stats_badges_list' ); //OK
    add_action( 'wp_ajax_go_stats_groups_list', 'go_stats_groups_list' ); //OK
    add_action( 'wp_ajax_go_stats_leaderboard', 'go_stats_leaderboard' ); //OK
    add_action( 'wp_ajax_go_stats_leaderboard_dataloader_ajax', 'go_stats_leaderboard_dataloader_ajax');
    add_action( 'wp_ajax_go_stats_lite', 'go_stats_lite' ); //OK
    add_action( 'wp_ajax_go_stats_about', 'go_stats_about' ); //OK
    add_action( 'wp_ajax_go_activity_dataloader_ajax', 'go_activity_dataloader_ajax');
    add_action( 'wp_ajax_go_messages_dataloader_ajax', 'go_messages_dataloader_ajax');
    add_action( 'wp_ajax_go_stats_store_item_dataloader', 'go_stats_store_item_dataloader');
    add_action( 'wp_ajax_go_tasks_dataloader_ajax', 'go_tasks_dataloader_ajax');
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
    add_action( 'wp_ajax_go_initial_value', 'go_initial_value' );
    add_action( 'wp_ajax_go_user_profile_link', 'go_user_profile_link' );
    //add_action( 'wp_ajax_go_clipboard_save_filters', 'go_clipboard_save_filters' ); //OK
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
    add_action( 'wp_ajax_go_blog_user_task', 'go_blog_user_task');
    //Admin
    add_action( 'wp_ajax_go_update_admin_view', 'go_update_admin_view' ); //OK
    add_action( 'wp_ajax_check_if_top_term', 'go_check_if_top_term' ); //for term order //OK
    add_action( 'wp_ajax_go_deactivate_plugin', 'go_deactivate_plugin' );
    add_action( 'wp_ajax_go_reset_all_users', 'go_reset_all_users' ); //OK
    //add_action( 'wp_ajax_go_clone_post', 'go_clone_post' );  //OK
    add_action( 'wp_ajax_go_upgade4', 'go_upgade4' ); //OK
    add_action( 'wp_ajax_go_user_map_ajax', 'go_user_map_ajax' );

} else {//ELSE THIS IS AN ADMIN PAGE

    //ajax directory
    include_once('src/admin/clone_button.php');
    include_once('src/admin/go_activation.php');
    include_once('src/admin/go_admin_menus.php');
    include_once('src/admin/go_datatable.php');
    include_once('src/admin/go_shortcodes_button.php');
    include_once('src/admin/go_store_admin.php');
    include_once('src/admin/go_task_admin.php');
    include_once('src/admin/go_user_bio.php');

    //admin/ajax directory
    include_once('src/admin/ajax/go_clipboard.php');
    include_once('src/admin/ajax/go_store_make_html.php');

    //admin js
    include_once('js/go_enque_js_admin.php');

    //admin css
    include_once('styles/go_enque_styles_admin.php');

    add_action( 'admin_enqueue_scripts', 'go_admin_scripts' );
    add_action( 'admin_enqueue_scripts', 'go_admin_styles' );

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



////////////////////////////
/// ALL PAGES & AJAX
////////////////////////////

//create non-persistent cache group
//This is used by the transients
wp_cache_add_non_persistent_groups( 'go_single' );

/*
 * Admin Menu & Admin Bar
 */
add_action( 'admin_bar_init', 'go_admin_bar' );

/*
 * User Data
 */
add_action( 'delete_user', 'go_user_delete' ); //this should change for Multisite
add_action( 'user_register', 'go_user_registration' ); //this should change for Multisite

/**
 * Miscellaneous Filters
 */
// mitigating compatibility issues with Jetpack plugin by Automatic
// (https://wordpress.org/plugins/jetpack/).
add_filter( 'jetpack_enable_open_graph', '__return_false', 99 );

?>
