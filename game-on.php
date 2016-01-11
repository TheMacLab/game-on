<?php
/*
Plugin Name: Game-On
Plugin URI: http://maclab.guhsd.net/game-on
Description: Gamification tools for teachers.
Author: Valhalla Mac Lab
Author URI: https://github.com/TheMacLab/game-on/blob/master/README.md
Version: 2.5.6
*/

include( 'go_datatable.php' );
include( 'go_pnc.php' );
include( 'go_returns.php' );
include( 'go_ranks.php' );
include( 'go_enque.php' );
include( 'go_globals.php' );
include( 'go_admin_bar.php' );
include( 'go_message.php' );
include( 'go_options.php' );
include( 'go_stats.php' );
include( 'go_clipboard.php' );
include( 'go_open_badge.php' );
include( 'go_shortcodes.php' );
include( 'go_comments.php' );
include( 'go_mail.php' );
include( 'go_messages.php' );
include( 'go_task_search.php' );
include( 'go_pods.php' );
include( 'types/types.php' );

/*
 * Plugin Activation Hooks
 */

register_activation_hook( __FILE__, 'go_table_totals' );
register_activation_hook( __FILE__, 'go_table_individual' );
register_activation_hook( __FILE__, 'go_ranks_registration' );
register_activation_hook( __FILE__, 'go_presets_registration' );
register_activation_hook( __FILE__, 'go_install_data' );
register_activation_hook( __FILE__, 'go_update_definitions' );
register_activation_hook( __FILE__, 'go_open_comments' );
register_activation_hook( __FILE__, 'go_tsk_actv_activate' );

/*
 * Init
 */

add_action( 'init', 'go_register_tax_and_cpt' );
add_action( 'init', 'go_register_admin_scripts_and_styles' );
add_action( 'init', 'go_register_scripts_and_styles' );

/*
 * Admin Menu & Admin Bar
 */

// actions
add_action( 'admin_menu', 'add_game_on_options' );
add_action( 'admin_menu', 'go_clipboard' );
add_action( 'admin_menu', 'go_pod_submenu' );
add_action( 'admin_bar_init', 'go_messages_bar' );
add_action( 'admin_bar_init', 'go_global_defaults' );
add_action( 'admin_bar_init', 'go_global_info' );
add_action( 'admin_bar_init', 'go_admin_bar' );

// filters
add_filter( 'show_admin_bar', 'go_display_admin_bar' );

/*
 * Admin & Login Page
 */

add_action( 'admin_init', 'go_tsk_actv_redirect' );
add_action( 'admin_init', 'go_add_delete_post_hook' );
add_action( 'admin_head', 'go_stats_overlay' );
add_action( 'admin_head', 'go_store_head' );
add_action( 'admin_footer', 'store_edit_jquery' );
add_action( 'admin_footer', 'task_edit_jquery' );
add_action( 'admin_notices', 'go_admin_head_notification' );
add_action( 'admin_enqueue_scripts', 'go_enqueue_admin_scripts_and_styles' );
add_action( 'login_redirect', 'go_user_redirect', 10, 3 );

/*
 * Front-end
 */

// actions
add_action( 'wp_head', 'go_stats_overlay' );
add_action( 'wp_head', 'go_frontend_lightbox_html' );
add_action( 'wp_enqueue_scripts', 'go_enqueue_scripts_and_styles' );

// filters
add_filter( 'get_comment_author', 'go_display_comment_author' );

/*
 * User Data
 */

add_action( 'delete_user', 'go_user_delete' );
add_action( 'user_register', 'go_user_registration' );
add_action( 'show_user_profile', 'go_extra_profile_fields' );
add_action( 'edit_user_profile', 'go_extra_profile_fields' );
add_action( 'personal_options_update', 'go_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'go_save_extra_profile_fields' );

/*
 * AJAX Hooks
 */

add_action( 'wp_ajax_go_deactivate_plugin', 'go_deactivate_plugin' );
add_action( 'wp_ajax_go_clone_post', 'go_clone_post' );
add_action( 'wp_ajax_go_clipboard_intable', 'go_clipboard_intable' );
add_action( 'wp_ajax_go_clipboard_intable_messages', 'go_clipboard_intable_messages' );
add_action( 'wp_ajax_go_user_option_add', 'go_user_option_add' );
add_action( 'wp_ajax_test_point_update', 'test_point_update' );
add_action( 'wp_ajax_unlock_stage', 'unlock_stage' );
add_action( 'wp_ajax_task_change_stage', 'task_change_stage' );
add_action( 'wp_ajax_go_task_abandon', 'go_task_abandon' );
add_action( 'wp_ajax_go_admin_bar_add', 'go_admin_bar_add' );
add_action( 'wp_ajax_go_admin_bar_stats', 'go_admin_bar_stats' );
add_action( 'wp_ajax_go_class_a_save', 'go_class_a_save' );
add_action( 'wp_ajax_go_class_b_save', 'go_class_b_save' );
add_action( 'wp_ajax_go_update_user_sc_data', 'go_update_user_sc_data' );
add_action( 'wp_ajax_go_focus_save', 'go_focus_save' );
add_action( 'wp_ajax_go_reset_levels', 'go_reset_levels' );
add_action( 'wp_ajax_go_save_levels', 'go_save_levels' );
add_action( 'wp_ajax_go_reset_data', 'go_reset_data' );
add_action( 'wp_ajax_go_stats_task_list', 'go_stats_task_list' );
add_action( 'wp_ajax_go_stats_move_stage', 'go_stats_move_stage' );
add_action( 'wp_ajax_go_stats_item_list', 'go_stats_item_list' );
add_action( 'wp_ajax_go_stats_rewards_list', 'go_stats_rewards_list' );
add_action( 'wp_ajax_go_stats_minutes_list', 'go_stats_minutes_list' );
add_action( 'wp_ajax_go_stats_penalties_list', 'go_stats_penalties_list' );
add_action( 'wp_ajax_go_stats_badges_list', 'go_stats_badges_list' );
add_action( 'wp_ajax_go_stats_leaderboard_choices', 'go_stats_leaderboard_choices' );
add_action( 'wp_ajax_go_stats_leaderboard', 'go_stats_leaderboard' );
add_action( 'wp_ajax_go_presets_reset', 'go_presets_reset' );
add_action( 'wp_ajax_go_presets_save', 'go_presets_save' );
add_action( 'wp_ajax_go_fix_levels', 'go_fix_levels' );
add_action( 'wp_ajax_listurl', 'listurl' );
add_action( 'wp_ajax_nopriv_listurl', 'listurl' );
add_action( 'wp_ajax_go_update_user_focuses', 'go_update_user_focuses' );
add_action( 'wp_ajax_go_get_all_terms', 'go_get_all_terms' );
add_action( 'wp_ajax_nopriv_go_get_all_terms', 'go_get_all_terms' );
add_action( 'wp_ajax_go_get_all_posts', 'go_get_all_posts' );
add_action( 'wp_ajax_nopriv_go_get_all_posts', 'go_get_all_posts' );
add_action( 'wp_ajax_go_update_task_order', 'go_update_task_order' );
add_action( 'wp_ajax_go_search_for_user', 'go_search_for_user' );
add_action( 'wp_ajax_go_admin_remove_notification', 'go_admin_remove_notification' );
add_action( 'wp_ajax_go_get_purchase_count', 'go_get_purchase_count' );
add_action( 'wp_ajax_buy_item', 'go_buy_item' );
add_action( 'wp_ajax_nopriv_buy_item', 'go_buy_item' );
add_action( 'wp_ajax_cat_item', 'go_cat_item' );
add_action( 'wp_ajax_nopriv_cat_item', 'go_cat_item' );
add_action( 'wp_ajax_go_clipboard_add', 'go_clipboard_add' );
add_action( 'wp_ajax_fixmessages', 'fixmessages' );
add_action( 'wp_ajax_go_mark_read', 'go_mark_read' );
add_action( 'wp_ajax_go_lb_ajax', 'go_the_lb_ajax' );
add_action( 'wp_ajax_nopriv_go_lb_ajax', 'go_the_lb_ajax' );

/*
 * Miscellaneous Filters
 */

add_filter( 'cron_schedules', 'go_weekly_schedule' );
add_filter( 'media_upload_tabs', 'go_media_upload_tab_name' );
add_filter( 'attachment_fields_to_edit', 'go_badge_add_attachment', 2, 2 );

// mitigating compatibility issues with Jetpack plugin by Automatic
// (https://wordpress.org/plugins/jetpack/).
add_filter( 'jetpack_enable_open_graph', '__return_false' );

/*
 * Important Functions
 */

function go_deactivate_plugin() {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = plugin_basename( __FILE__ );
	deactivate_plugins( $plugin );
	die();
}

function go_tsk_actv_activate() {
	add_option( 'go_tsk_actv_do_activation_redirect', true );
	update_option( 'go_display_admin_explanation', true );
}

function go_tsk_actv_redirect() {
	if ( get_option( 'go_tsk_actv_do_activation_redirect', false ) ) {
		delete_option( 'go_tsk_actv_do_activation_redirect' );
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_redirect( 'admin.php?page=game-on-options.php&settings-updated=true' );
		}
	}
}

function go_add_delete_post_hook() {
	if ( current_user_can( 'delete_posts' ) ) {
		add_action( 'delete_post', 'go_delete_cpt_data' );
	}
}

function go_delete_cpt_data( $cpt_id ) {
	global $wpdb;
	if ( "tasks" == get_post_type( $cpt_id ) || "go_store" == get_post_type( $cpt_id ) ) {
		$cpt_to_delete = $wpdb->get_var( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}go WHERE post_id = %d", $cpt_id ) );
		if ( $cpt_to_delete ) {
			return $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}go WHERE post_id = %d", $cpt_id ) );
		}
	}
	return true;
}

/* 
 * Registers Game On custom post types and taxonomies, then
 * updates the site's rewrite rules to mitigate cpt and 
 * permalink conflicts. flush_rewrite_rules() must always
 * be called AFTER custom post types and taxonomies are
 * registered.
 */
function go_register_tax_and_cpt() {
	go_register_task_tax_and_cpt();
	go_register_store_tax_and_cpt();
	flush_rewrite_rules();
}

function check_values( $req = null, $cur = null ) {
	if ( $cur >= $req || $req <= 0 ) {
		return true;
	} else {
		return false;
	}
}

function go_user_redirect( $redirect_to, $request, $user ) {
	if ( get_option( 'go_admin_bar_user_redirect', true ) ) {
		$roles = $user->roles;
		if ( is_array( $roles) ) {
			if ( in_array( 'administrator', $roles ) ) {
				return admin_url();
			} else {
				return site_url();
			}
		} else {
			if ( $roles == 'administrator' ) {
				return admin_url();
			} else {
				return site_url();
			}
		}
	} else {
		return $redirect_to;
	}
}

function go_admin_head_notification() {
	if ( get_option( 'go_display_admin_explanation' ) && current_user_can( 'manage_options' ) ) {
		$plugin_data = get_plugin_data( __FILE__, false, false );
		$plugin_version = $plugin_data['Version'];
		echo "<div id='message' class='update-nag' style='font-size: 16px;'>This is a fresh installation of Game On (version <a href='https://github.com/TheMacLab/game-on/releases/tag/v{$plugin_version}' target='_blank'>{$plugin_version}</a>).<br/>Watch <a href='javascript:;'  onclick='go_display_help_video(&quot;http://maclab.guhsd.net/go/video/gameOn.mp4&quot;);' style='display:inline-block;'>this short video</a> for important information.<br/>Or visit the <a href='http://maclab.guhsd.net/game-on' target='_blank'>documentation page</a>.<br/><a href='javascript:;' onclick='go_remove_admin_notification()'>Dismiss messsage</a></div>";
		echo "<script>
			function go_remove_admin_notification() {
				jQuery.ajax( {
					type: 'post',
					url: MyAjax.ajaxurl,
					data: {
						action: 'go_admin_remove_notification'
					},
					success: function( html ) {
						location.reload();
					}
				} );
			}
		</script>";
	}
}

function go_admin_remove_notification() {
	update_option( 'go_display_admin_explanation', false );
	die();
}

function go_weekly_schedule( $schedules ) {
	$schedules['go_weekly'] = array(
		'interval' => 604800,
		'display' => __( 'Once Weekly' )
	);
	return $schedules;
}

function go_task_timer_headers() {
	$custom_fields = get_post_custom();
	$future_switches = ( ! empty( $custom_fields['go_mta_time_filters'][0] ) ? unserialize( $custom_fields['go_mta_time_filters'][0] ) : '' );
	if ( 'tasks' == get_post_type() && ! empty( $future_switches['future'] ) && 'on' == $future_switches['future'] ) {
		header( 'Expires: Thu, 1 Jan 1970 00:00:00 GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
		header( 'Pragma: no-cache' );	
	}
}
?>