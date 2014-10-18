<?php
/*
Plugin Name: Game-On
Plugin URI: http://maclab.guhsd.net/game-on
Description: Gamification tools for teachers.
Authors: Semar Yousif, Vincent Astolfi, Ezio Ballarin, Forest Hoffman, Austin Vuong, Spencer Nussbaum, Isaac Canada, Charles Leon
Author URI: http://maclab.guhsd.net/
Version: 2.2.6
*/
include('go_datatable.php');
include('types/types.php');
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
include('go_shortcodes.php');
include('go_comments.php');
include('go_definitions.php');
include('go_mail.php');
include('go_messages.php');
include('go_task_search.php');
register_activation_hook( __FILE__, 'go_table_totals' );
register_activation_hook( __FILE__, 'go_table_individual' );
register_activation_hook( __FILE__, 'go_ranks_registration' );
register_activation_hook( __FILE__, 'go_presets_registration' );
register_activation_hook( __FILE__, 'go_install_data' );
register_activation_hook( __FILE__, 'go_define_options' );
register_activation_hook( __FILE__, 'go_open_comments');
add_action('user_register', 'go_user_registration');
add_action('delete_user', 'go_user_delete' );
add_action('wp_ajax_go_deactivate_plugin', 'go_deactivate_plugin');
add_action('go_add_post','go_add_post');
add_action('go_add_currency','go_add_currency');
add_action('go_add_bonus_currency','go_add_bonus_currency');
add_action('go_add_penalty','go_add_penalty');
add_action('go_add_minutes','go_add_minutes');
add_action('go_return_currency','go_return_currency');
add_action('go_return_points','go_return_points');
add_action('go_return_bonus_currency','go_return_bonus_currency');
add_action('go_return_penalty','go_return_penalty');
add_action('go_return_minutes','go_return_minutes');
add_action('go_display_user_focuses', 'go_display_user_focuses');
add_action('go_display_rewards', 'go_display_rewards');
add_action('admin_menu', 'go_clipboard');
add_action('go_jquery_clipboard','go_jquery_clipboard');
add_action('go_style_clipboard','go_style_clipboard');
add_action('wp_ajax_go_clone_task', 'go_clone_task');
add_action('wp_ajax_go_clipboard_intable','go_clipboard_intable');
add_action('wp_ajax_go_user_option_add','go_user_option_add');
add_action('go_update_totals','go_update_totals');
add_action( 'init', 'go_jquery' );
add_shortcode('testbutton','testbutton');
add_action('admin_bar_init','go_global_defaults');
add_action('admin_bar_init','go_global_info');
add_action('admin_bar_init', 'go_admin_bar');
add_action('admin_bar_init', 'go_style_everypage');
add_action('admin_bar_init','go_style_stats');
add_action('show_admin_bar', 'go_display_admin_bar');
add_action('go_get_all_ranks','go_get_all_ranks');
add_action('wp_ajax_test_point_update', 'test_point_update');
add_action('go_get_all_focuses', 'go_get_all_focuses');
add_action('wp_ajax_unlock_stage', 'unlock_stage');
add_action('wp_ajax_task_change_stage','task_change_stage');
add_action('wp_ajax_go_task_abandon','go_task_abandon');
add_action('go_update_admin_bar','go_update_admin_bar');
add_action('go_update_progress_bar','go_update_progress_bar');
add_action('go_style_periods','go_style_periods');
add_action('go_jquery_periods','go_jquery_periods');
add_action('wp_ajax_go_admin_bar_add','go_admin_bar_add');
add_action('wp_ajax_go_admin_bar_stats','go_admin_bar_stats');
add_action('wp_ajax_go_class_a_save','go_class_a_save');
add_action('wp_ajax_go_class_b_save','go_class_b_save');
add_action('wp_ajax_go_update_user_sc_data', 'go_update_user_sc_data');
add_action('wp_ajax_go_focus_save','go_focus_save');
add_action('wp_ajax_go_reset_levels', 'go_reset_levels');
add_action('wp_ajax_go_save_levels', 'go_save_levels');
add_action('wp_ajax_go_reset_data', 'go_reset_data');
add_action('wp_ajax_go_stats_task_list','go_stats_task_list');
add_action('wp_ajax_go_stats_move_stage', 'go_stats_move_stage');
add_action('wp_ajax_go_stats_item_list', 'go_stats_item_list');
add_action('wp_ajax_go_stats_rewards_list', 'go_stats_rewards_list');
add_action('wp_ajax_go_stats_minutes_list', 'go_stats_minutes_list');
add_action('wp_ajax_go_stats_penalties_list', 'go_stats_penalties_list');
add_action('wp_ajax_go_stats_badges_list', 'go_stats_badges_list');
add_action('wp_ajax_go_stats_leaderboard_choices','go_stats_leaderboard_choices');
add_action('wp_ajax_go_stats_leaderboard','go_stats_leaderboard');
add_action('wp_ajax_go_presets_reset','go_presets_reset');
add_action('wp_ajax_go_presets_save','go_presets_save');
add_action('wp_ajax_go_fix_levels', 'go_fix_levels');
add_action('wp_ajax_listurl', 'listurl');
add_action('wp_ajax_nopriv_listurl', 'listurl');
add_action('wp_ajax_go_clipboard_get_data', 'go_clipboard_get_data');
add_action('wp_ajax_go_update_script_day', 'go_update_script_day');
add_action('wp_ajax_go_get_all_terms', 'go_get_all_terms');
add_action('wp_ajax_nopriv_go_get_all_terms', 'go_get_all_terms');
add_action('wp_ajax_go_get_all_posts', 'go_get_all_posts');
add_action('wp_ajax_nopriv_go_get_all_posts', 'go_get_all_posts');
add_action('wp_ajax_go_update_task_order', 'go_update_task_order');
add_action('wp_ajax_go_search_for_user', 'go_search_for_user');
add_action('wp_ajax_go_admin_remove_notification', 'go_admin_remove_notification');
add_shortcode( 'go_stats_page', 'go_stats_page' );
register_activation_hook(__FILE__, 'go_tsk_actv_activate');
add_action('admin_init', 'go_tsk_actv_redirect');
add_action('inRange', 'inRange');
add_action('isEven','isEven');
add_action('wp_head', 'go_stats_overlay');
add_action('admin_head', 'go_stats_overlay');
add_action('admin_notices', 'go_admin_head_notification');
add_action('go_display_points','go_display_points');
add_action('go_display_currency','go_display_currency');
add_action('go_display_penalty','go_display_penalty');
add_action('go_display_minutes','go_display_minutes');
add_action('go_return_options','go_return_options');
add_action('go_update_globals','go_update_globals');
add_action('barColor','barColor');
add_action('go_return_multiplier','go_return_multiplier');
add_filter('get_comment_author', 'go_display_comment_author');
add_action('check_custom', 'check_custom');
add_action('check_values', 'check_values');
add_action('go_message_user', 'go_message_user');
add_filter('jetpack_enable_open_graph', '__return_false');
add_action('login_redirect', 'go_user_redirect', 10, 3);
add_action('go_clipboard_collect_data', 'go_clipboard_collect_data');
add_filter('cron_schedules', 'go_weekly_schedule');

function go_deactivate_plugin () {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = plugin_basename( __FILE__ );
	deactivate_plugins($plugin);
	die();
}

function go_tsk_actv_activate () {
    add_option('go_tsk_actv_do_activation_redirect', true);
	update_option('go_display_admin_explanation', true);
}

function go_tsk_actv_redirect() {
    if (get_option('go_tsk_actv_do_activation_redirect', false)) {
        delete_option('go_tsk_actv_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=game-on-options.php&settings-updated=true");
        }
    }
}

function inRange ($int, $min, $max) {
	return ($int>$min && $int<=$max);
} 

function isEven($value) {
	if ($value%2 == 0) {
		return 'even';
	} else {
		return 'odd';
	}
}

function check_values ($req = null, $cur = null) {
	if ($cur >= $req || $req <= 0) {
		return true;
	} else {
		return false;
	}
}

function go_user_redirect ($redirect_to, $request, $user) {
    if (get_option('go_admin_bar_user_redirect', true)) {
    	$roles = $user->roles;
    	if (is_array($roles)) {
	    	if (in_array('administrator', $roles)) {
	    		return admin_url();
	    	} else {
	    		return site_url();
	    	}
	    } else {
	    	if ($roles == 'administrator') {
	    		return admin_url();
	    	} else {
	    		return site_url();
	    	}
	    }
    } else {
    	return $redirect_to;
    }
}

function go_admin_head_notification(){
	if (get_option('go_display_admin_explanation') && current_user_can('manage_options')) {
		echo "<div id='message' class='update-nag' style='font-size: 16px;'>This is a fresh installation of Game On.<br/>Watch <a href='javascript:;'  onclick='go_display_help_video(&quot;http://maclab.guhsd.net/go/video/gameOn.mp4&quot;);' style='display:inline-block;'>this short video</a> for important information.<br/>Or visit the <a href='http://maclab.guhsd.net/game-on' target='_blank'>documentation page</a>.<br/><a href='javascript:;' onclick='go_remove_admin_notification()'>Dismiss messsage</a></div>";
		echo "<script>
			function go_remove_admin_notification(){
				jQuery.ajax({
					type: 'post',
					url: MyAjax.ajaxurl,
					data:{
						action: 'go_admin_remove_notification'
					},
					success:function(html){
						location.reload();
					}
				});
			}
		</script>";
	}
}

function go_admin_remove_notification () {
	update_option('go_display_admin_explanation', false);
	die();
}

function go_weekly_schedule ($schedules) {
	$schedules['go_weekly'] = array(
		'interval' => 604800,
		'display' => __('Once Weekly')
	);
	return $schedules;
}
?>
