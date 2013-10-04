<?php
/*
Plugin Name: Game-On
Description: Adds support for a point system and currency for your users.
Author: Semar Yousif, Vincent Astolfi, Ezio Ballarin
Author URI: http://maclab.guhsd.net/
Version: 0.0.0.7
*/
include('types/types.php');
include('go_datatable.php');
include('go_pnc.php');
include('go_returns.php');
include('go_ranks.php');
include('scripts/go_enque.php');
include('go_globals.php');
include('go_admin_bar.php');
include('go_message.php');
include('styles/go_enque_styles.php');
include('go_options.php');
include('go_stats.php');
include('go_clipboard.php');
include('go_open_badge.php');
include('badge_designer.php');
register_activation_hook( __FILE__, 'go_table_totals' );
register_activation_hook( __FILE__, 'go_table_individual' );
register_activation_hook( __FILE__, 'go_ranks_registration' );
register_activation_hook( __FILE__, 'go_install_data' );
add_action('user_register', 'go_user_registration');
add_action( 'delete_user', 'go_user_delete' );
add_action('go_add_post','go_add_post');
add_action('go_add_currency','go_add_currency');
add_action('go_add_minutes','go_add_minutes');
add_action('go_return_currency','go_return_currency');
add_action('go_return_points','go_return_points');
add_action('go_return_minutes','go_return_minutes');
add_action('admin_menu', 'go_ranks');
add_action('admin_menu', 'go_clipboard');
add_action('go_jquery_clipboard','go_jquery_clipboard');
add_action('go_style_clipboard','go_style_clipboard');
add_action('wp_ajax_go_clipboard_intable','go_clipboard_intable');
add_action('wp_ajax_go_user_option_add','go_user_option_add');
add_action('go_update_totals','go_update_totals');
add_action( 'init', 'go_jquery' );
add_action('wp_ajax_go_add_ranks', 'go_add_ranks');
add_action('wp_ajax_go_remove_ranks', 'go_remove_ranks');
add_shortcode('testbutton','testbutton');
add_action('admin_bar_init','go_global_defaults');
add_action('admin_bar_init','go_global_info');
add_action('go_get_all_ranks','go_get_all_ranks');
add_action('wp_ajax_task_change_stage','task_change_stage');
add_action('admin_bar_init', 'go_admin_bar');
add_action('admin_bar_init', 'go_style_everypage' );
add_action('go_update_admin_bar','go_update_admin_bar');
add_action('go_update_progress_bar','go_update_progress_bar');
add_action('go_style_periods','go_style_periods');
add_action('admin_bar_init','go_style_stats');
add_action('go_jquery_periods','go_jquery_periods');
add_action('wp_ajax_go_admin_bar_add','go_admin_bar_add');
add_action('wp_ajax_go_admin_bar_stats','go_admin_bar_stats');
add_action('wp_ajax_go_class_a_save','go_class_a_save');
add_action('wp_ajax_go_class_b_save','go_class_b_save');
add_action('wp_ajax_go_stats_task_list','go_stats_task_list');
add_action('wp_ajax_go_stats_points','go_stats_points');
add_action('wp_ajax_go_stats_currency','go_stats_currency');
add_action('wp_ajax_go_stats_minutes','go_stats_minutes');
add_action('wp_ajax_go_presets_save','go_presets_save');
add_shortcode( 'go_stats_page', 'go_stats_page' );
register_activation_hook(__FILE__, 'go_tsk_actv_activate');
add_action('admin_init', 'go_tsk_actv_redirect');
add_action('isEven','isEven');
add_action('wp_head', 'go_stats_overlay');
add_action('admin_head', 'go_stats_overlay');
add_action('go_display_points','go_display_points');
add_action('go_display_currency','go_display_currency');
function go_tsk_actv_activate() {
    add_option('go_tsk_actv_do_activation_redirect', true);
}
function go_tsk_actv_redirect() {
    if (get_option('go_tsk_actv_do_activation_redirect', false)) {
        delete_option('go_tsk_actv_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=game-on-options.php");
        }
    }
}

function isEven($value) {
	if ($value%2 == 0){
		return 'even';}
	else{
		return 'odd';
}}

?>