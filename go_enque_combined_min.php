<?php

/*
 * Registering Scripts/Styles For Admin Pages
 */

function go_register_admin_scripts_and_styles () {

//Scripts
	 // Merged Scripts	 
	wp_register_script( 'go-admin-min', plugin_dir_url( __FILE__ ).'scripts/go-admin-min.js' );
		
//Styles

	// Dependencies
	wp_register_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
	wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/video-js/video-js.css' );

	// Custom Styles
	wp_register_style( 'go_admin_css', plugin_dir_url( __FILE__ ).'styles/go_admin.css' );	

}

/*
 * Enqueueing Scripts/Styles For Admin Pages & adding localization & nonces
 */

function go_enqueue_admin_scripts_and_styles ( $hook ) {
	global $post;
	$user_id = get_current_user_id();

	/*
	 *Scripts
	 */
	 
	wp_enqueue_script( 'go-admin-min' );
	
	// Dependencies

	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'jquery-ui-progressbar' );
	wp_enqueue_script( 'jquery-effects-core' );

	// Localization
	wp_localize_script( 'go-admin-min', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go-admin-min', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
	wp_localize_script(
		'go-admin-min',
		'GO_EVERY_PAGE_DATA',
		array(
			'nonces' => array(
				'go_deactivate_plugin'         => wp_create_nonce( 'go_deactivate_plugin_' . $user_id ),
				'go_admin_bar_add'             => wp_create_nonce( 'go_admin_bar_add_' . $user_id ),
				'go_admin_bar_stats'           => wp_create_nonce( 'go_admin_bar_stats_' ),
				'go_stats_task_list'           => wp_create_nonce( 'go_stats_task_list_' ),
				'go_stats_move_stage'          => wp_create_nonce( 'go_stats_move_stage_' ),
				'go_stats_item_list'           => wp_create_nonce( 'go_stats_item_list_' ),
				'go_stats_rewards_list'        => wp_create_nonce( 'go_stats_rewards_list_' ),
				'go_stats_minutes_list'        => wp_create_nonce( 'go_stats_minutes_list_' ),
				'go_stats_penalties_list'      => wp_create_nonce( 'go_stats_penalties_list_' ),
				'go_stats_badges_list'         => wp_create_nonce( 'go_stats_badges_list_' ),
				'go_stats_leaderboard_choices' => wp_create_nonce( 'go_stats_leaderboard_choices_' ),
				'go_stats_leaderboard'         => wp_create_nonce( 'go_stats_leaderboard_' ),
				'go_mark_read'                 => wp_create_nonce( 'go_mark_read_' . $user_id ),
			),
		)
	);
	
	wp_localize_script( 'go-admin-min', 'GO_TASK_DATA', go_localize_task_data() );
	
	wp_localize_script(
		'go-admin-min',
		'GO_OPTION_DATA',
		array(
			'nonces' => array(
				'go_presets_reset'       => wp_create_nonce( 'go_presets_reset_' . $user_id ),
				'go_presets_save'        => wp_create_nonce( 'go_presets_save_' . $user_id ),
				'go_reset_levels'        => wp_create_nonce( 'go_reset_levels_' . $user_id ),
				'go_save_levels'         => wp_create_nonce( 'go_save_levels_' . $user_id ),
				'go_fix_levels'          => wp_create_nonce( 'go_fix_levels_' . $user_id ),
				'go_reset_data'          => wp_create_nonce( 'go_reset_data_' . $user_id ),
				'go_update_user_sc_data' => wp_create_nonce( 'go_update_user_sc_data_' . $user_id ),
				'go_focus_save'          => wp_create_nonce( 'go_focus_save_' . $user_id ),
			),
		)
	);

	wp_localize_script( 'go-admin-min', 'Minutes_limit', array( 'limit' => go_return_options( 'go_minutes_color_limit' ) ) );
	
	wp_localize_script(
		'go-admin-min',
		'GO_CLIPBOARD_DATA',
		array(
			'nonces' => array(
				'go_clipboard_intable'          => wp_create_nonce( 'go_clipboard_intable_' . $user_id ),
				'go_clipboard_intable_messages' => wp_create_nonce( 'go_clipboard_intable_messages_' . $user_id ),
				'go_update_user_focuses'        => wp_create_nonce( 'go_update_user_focuses_' . $user_id ),
				'go_clipboard_add'              => wp_create_nonce( 'go_clipboard_add_' . $user_id ),
				'go_fix_messages'               => wp_create_nonce( 'go_fix_messages_' . $user_id ),
			),
		)
	);

	/*
	 * Common Styles
	 */

	// Dependencies
	wp_enqueue_style( 'jquery-ui-css' );
	wp_enqueue_style( 'video-js-css' );
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
	wp_enqueue_script( 'jquery-ui-tabs' );


	// Custom Styles
	wp_enqueue_style( 'go_admin_css' );


}

/*
 * Registering Scripts/Styles For The Front-end
 */

function go_register_scripts_and_styles () {

	//Scripts 

	// Merged Scripts
	wp_register_script( 'go-frontend-min', plugin_dir_url( __FILE__ ).'scripts/go-frontend-min.js' );
	
	//Styles
	 
	// Dependencies
	wp_register_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
	wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/video-js/video-js.css' );
	wp_register_style( 'go_lightbox', plugin_dir_url( __FILE__ ).'types/store/includes/lightbox/css/go-lightbox.css' );

	// Custom Styles
	wp_register_style( 'go_frontend_css', plugin_dir_url( __FILE__ ).'styles/go_frontend.css' );
}

/*
 * Enqueueing Scripts/Styles For The Front-end
 */

function go_enqueue_scripts_and_styles () {

	//Scripts	 
	wp_enqueue_script( 'go-frontend-min' );

	// Dependencies
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'jquery-ui-progressbar' );
	wp_enqueue_script( 'jquery-effects-core' );
	
	// Localization
	$user_id = get_current_user_id();

	wp_localize_script( 'go-frontend-min', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go-frontend-min', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
	wp_localize_script(
		'go-frontend-min',
		'GO_EVERY_PAGE_DATA',
		array(
			'nonces' => array(
				'go_deactivate_plugin'         => wp_create_nonce( 'go_deactivate_plugin_' . $user_id ),
				'go_admin_bar_add'             => wp_create_nonce( 'go_admin_bar_add_' . $user_id ),
				'go_admin_bar_stats'           => wp_create_nonce( 'go_admin_bar_stats_' ),
				'go_stats_task_list'           => wp_create_nonce( 'go_stats_task_list_' ),
				'go_stats_move_stage'          => wp_create_nonce( 'go_stats_move_stage_' ),
				'go_stats_item_list'           => wp_create_nonce( 'go_stats_item_list_' ),
				'go_stats_rewards_list'        => wp_create_nonce( 'go_stats_rewards_list_' ),
				'go_stats_minutes_list'        => wp_create_nonce( 'go_stats_minutes_list_' ),
				'go_stats_penalties_list'      => wp_create_nonce( 'go_stats_penalties_list_' ),
				'go_stats_badges_list'         => wp_create_nonce( 'go_stats_badges_list_' ),
				'go_stats_leaderboard_choices' => wp_create_nonce( 'go_stats_leaderboard_choices_' ),
				'go_stats_leaderboard'         => wp_create_nonce( 'go_stats_leaderboard_' ),
				'go_mark_read'                 => wp_create_nonce( 'go_mark_read_' . $user_id ),
			)
		)
	);
	wp_localize_script(
		'go-frontend-min',
		'GO_BUY_ITEM_DATA',
		array(
			'nonces' => array(
				'go_buy_item'           => wp_create_nonce( 'go_buy_item_' . $user_id ),
				'go_get_purchase_count' => wp_create_nonce( 'go_get_purchase_count_' . $user_id ),
			)
		)
	);
	$ajax_url   = admin_url( 'admin-ajax.php' );        // Localized AJAX URL
	wp_localize_script('go-frontend-min','map_ajax_admin_url',$ajax_url);
		

	/*
	 * Common Styles
	 */

	// Dependencies
	wp_enqueue_style( 'jquery-ui-css' );
	wp_enqueue_style( 'video-js-css' );
	wp_enqueue_style( 'go_lightbox' );
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 

	// Custom Styles
	wp_enqueue_style( 'go_frontend_css' );

}
?>