<?php

/*
 * Registering Scripts/Styles For Admin Pages
 */

function go_register_admin_scripts_and_styles () {

	/*
	 * Common Scripts
	 */

	// Dependencies
	wp_register_script( 'video-js', plugin_dir_url( __FILE__ ).'scripts/video-js/video.js' );

	// Custom Scripts
	wp_register_script( 'go_notification', plugin_dir_url( __FILE__ ).'scripts/go_notification.js' );
	wp_register_script( 'go_every_page', plugin_dir_url( __FILE__ ).'scripts/go_every_page.js' );

	/*
	 * Common Styles
	 */

	// Dependencies
	wp_register_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
	wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/video-js/video-js.css' );

	// Custom Styles
	wp_register_style( 'go_every_page_css', plugin_dir_url( __FILE__ ).'styles/go_every_page.css' );
	wp_register_style( 'go_style_stats', plugin_dir_url( __FILE__ ).'styles/go_stats.css' );

	/*
	 * Page-Specific Scripts
	 */

	// Tasks
	wp_register_script( 'go_tasks', plugin_dir_url( __FILE__ ).'scripts/go_tasks_admin.js', array( 'jquery' ), false, true );
	wp_register_script( 'go_tasks_chains', plugin_dir_url( __FILE__ ).'scripts/go_tasks_chains.js', array( 'jquery', 'go_tasks' ), false, true );
	wp_register_script( 'go_presets', plugin_dir_url( __FILE__ ).'scripts/go_presets.js', array( 'jquery' ), false, true );
	wp_register_script( 'ptTimeSelectJS', plugin_dir_url( __FILE__ ).'scripts/jquery.ptTimeSelect.js', array( 'jquery' ) );

	// Store

	// Options
	wp_register_script( 'go_options', plugin_dir_url( __FILE__ ).'scripts/go_options.js' );

	// Pods
	wp_register_script( 'go_pod_options_js', plugin_dir_url( __FILE__ ).'scripts/go_pod_options.js', array( 'jquery' ), false, true );

	// Clipboard
	wp_register_script( 'jquery.dataTables.min.js', plugin_dir_url( __FILE__ ).'scripts/jquery.dataTables.min.js' );
	wp_register_script( 'go_jquery_clipboard', plugin_dir_url( __FILE__ ).'scripts/go_clipboard.js' );
	wp_register_script( 'go_jquery_clipboard_tablesorter', plugin_dir_url( __FILE__ ).'scripts/sorttable.js' );

	/*
	 * Page-Specific Styles
	 */

	// Tasks
	wp_register_style( 'ptTimeSelectCSS', plugin_dir_url( __FILE__ ).'styles/jquery.ptTimeSelect.css' );
	wp_register_style( 'go_tasks_admin', plugin_dir_url( __FILE__ ).'styles/tasks-admin.css' );

	// Store

	// Options
	wp_register_style( 'go_opt_css', plugin_dir_url( __FILE__ ).'styles/go_options.css' );

	// Pods

	// Clipboard
	wp_register_style( 'go_style_clipboard', plugin_dir_url( __FILE__ ).'styles/go_clipboard.css' );

}

/*
 * Enqueueing Scripts/Styles For Admin Pages
 */

function go_enqueue_admin_scripts_and_styles ( $hook ) {
	global $post;
	$user_id = get_current_user_id();

	/*
	 * Common Scripts
	 */

	// Dependencies
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'jquery-ui-progressbar' );
	wp_enqueue_script( 'jquery-effects-core' );
	wp_enqueue_script( 'video-js' );

	// Custom Scripts
	wp_enqueue_script( 'go_notification' );
	wp_enqueue_script( 'go_every_page' );

	// Localization
	wp_localize_script( 'go_every_page', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go_every_page', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
	wp_localize_script(
		'go_every_page',
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

	/*
	 * Common Styles
	 */

	// Dependencies
	wp_enqueue_style( 'jquery-ui-css' );
	wp_enqueue_style( 'video-js-css' );

	// Custom Styles
	wp_enqueue_style( 'go_every_page_css' );
	wp_enqueue_style( 'go_style_stats' );

	/*
	 * Page-Specific Scripts and Styles
	 */

	if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
		if ( 'tasks' === $post->post_type ) {

			/*
			 * Task Scripts
			 */

			wp_enqueue_script( 'go_tasks_chains' );
			wp_enqueue_script( 'go_presets' );
			wp_enqueue_script( 'ptTimeSelectJS' );

			// Localization

			wp_localize_script( 'go_tasks', 'GO_TASK_DATA', go_localize_task_data() );

			/*
			 * Task Styles
			 */

			wp_enqueue_style( 'ptTimeSelectCSS' );
			wp_enqueue_style( 'go_tasks_admin' );

		} else if ( 'go_store' === $post->post_type ) {

			/*
			 * Store Scripts
			 */
			
			// Localization

			/*
			 * Store Styles
			 */

		}
	} else if ( 'toplevel_page_game-on-options' === $hook ) {

		/*
		 * Options Page Scripts
		 */

		wp_enqueue_script( 'go_options' );

		// Localization

		wp_localize_script(
			'go_options',
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

		/*
		 * Options Page Styles
		 */

		wp_enqueue_style( 'go_opt_css' );

	} else if ( 'game-on_page_go_pods' === $hook ) {

		/*
		 * Pods Scripts
		 */

		wp_enqueue_script( 'go_pod_options_js' );

		// Localization

		/*
		 * Pods Styles
		 */

	} else if ( 'game-on_page_go_clipboard' === $hook ) {

		/*
		 * Clipboard Scripts
		 */

		// Dependencies
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery.dataTables.min.js' );
		
		// Custom Scripts
		wp_enqueue_script( 'go_jquery_clipboard' );
		wp_enqueue_script( 'go_jquery_clipboard_tablesorter' );
		
		// Localization

		wp_localize_script( 'go_jquery_clipboard', 'Minutes_limit', array( 'limit' => go_return_options( 'go_minutes_color_limit' ) ) );
		wp_localize_script(
			'go_jquery_clipboard',
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
		 * Clipboard Styles
		 */

		wp_enqueue_style( 'go_style_clipboard' );

	}
}

/*
 * Registering Scripts/Styles For The Front-end
 */

function go_register_scripts_and_styles () {

	/*
	 * Common Scripts
	 */

	// Dependencies
	wp_register_script( 'video-js', plugin_dir_url( __FILE__ ).'scripts/video-js/video.js' );

	// Custom Scripts
	wp_register_script( 'go_notification', plugin_dir_url( __FILE__ ).'scripts/go_notification.js' );
	wp_register_script( 'go_every_page', plugin_dir_url( __FILE__ ).'scripts/go_every_page.js' );

	wp_register_script( 'buy_the_item', plugin_dir_url( __FILE__ ).'types/store/includes/lightbox/js/buy_the_item.js', array( 'jquery' ), 1.0, true );

	/*
	 * Common Styles
	 */

	// Dependencies
	wp_register_style( 'jquery-ui-css', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
	wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/video-js/video-js.css' );
	wp_register_style( 'go_lightbox', plugin_dir_url( __FILE__ ).'types/store/includes/lightbox/css/go-lightbox.css' );

	// Custom Styles
	wp_register_style( 'go_every_page_css', plugin_dir_url( __FILE__ ).'styles/go_every_page.css' );
	wp_register_style( 'go_style_stats', plugin_dir_url( __FILE__ ).'styles/go_stats.css' );
}

/*
 * Enqueueing Scripts/Styles For The Front-end
 */

function go_enqueue_scripts_and_styles () {

	/*
	 * Common Scripts
	 */

	// Dependencies
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'jquery-ui-progressbar' );
	wp_enqueue_script( 'jquery-effects-core' );
	wp_enqueue_script( 'video-js' );

	// Custom Scripts
	wp_enqueue_script( 'go_notification' );
	wp_enqueue_script( 'go_every_page' );
	wp_enqueue_script( 'buy_the_item' );

	// Localization
	$user_id = get_current_user_id();

	wp_localize_script( 'go_every_page', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go_every_page', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
	wp_localize_script(
		'go_every_page',
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
		'buy_the_item',
		'GO_BUY_ITEM_DATA',
		array(
			'nonces' => array(
				'go_buy_item'           => wp_create_nonce( 'go_buy_item_' . $user_id ),
				'go_get_purchase_count' => wp_create_nonce( 'go_get_purchase_count_' . $user_id ),
			)
		)
	);

	/*
	 * Common Styles
	 */

	// Dependencies
	wp_enqueue_style( 'jquery-ui-css' );
	wp_enqueue_style( 'video-js-css' );
	wp_enqueue_style( 'go_lightbox' );

	// Custom Styles
	wp_enqueue_style( 'go_every_page_css' );
	wp_enqueue_style( 'go_style_stats' );

}
?>