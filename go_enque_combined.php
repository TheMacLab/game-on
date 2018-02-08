<?php

function go_admin_scripts_and_styles ($hook) {
	
	global $post;
	$user_id = get_current_user_id();
	
	/*
	 * Registering Scripts For Admin Pages
	 */

		/*
		 * Combined scripts for every admin page. Combine all scripts unless the page needs localization.
		 *
		 */
			
			wp_register_script( 'go_admin-min', plugin_dir_url( __FILE__ ).'scripts-min/go_admin-min.js', v1 );
		/*
		 * END Combined scripts for every admin page. 
		 */

		/*
		 * Page-Specific Scripts
		 */
			// Options 
			wp_register_script( 'go_options-min', plugin_dir_url( __FILE__ ).'scripts-min/go_options-min.js' );
		
			// Tasks
			wp_register_script( 'go_tasks_admin_combined-min', plugin_dir_url( __FILE__ ).'scripts-min/go_tasks_admin_combined-min.js', array( 'jquery' ), false, true );

			// Clipboard
			wp_register_script( 'go_clipboard_combined-min', plugin_dir_url( __FILE__ ).'scripts-min/go_clipboard_combined-min.js' );
			
			// store
			wp_register_script( 'go_store_admin-min', plugin_dir_url( __FILE__ ).'scripts-min/go_store_admin-min.js' );

			//featherlight
			wp_register_script( 'go_featherlight_min', plugin_dir_url( __FILE__ ).'bower_components/featherlight/release/featherlight.min.js' );
				
	/*
	 * Enqueue Scripts For Admin Pages (Exept for page specific ones below)
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
		wp_enqueue_script( 'go_featherlight_min' );
		
	
	
		//Combined Scripts
		wp_enqueue_script( 'go_admin-min' ); 
		//END Combined Scripts

		// Localization for every admin page
		wp_localize_script( 'go_admin-min', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'go_admin-min', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
		wp_localize_script(
			'go_admin-min',
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
	 * Page-Specific Scripts.  These have localization on these pages for at least one script.
	 */

	if ( 'post-new.php' === $hook || 'post.php' === $hook ) {
		if ( 'tasks' === $post->post_type ) {

			/*
			 * Task Scripts
			 */
			//Combined
			wp_enqueue_script( 'go_tasks_admin_combined-min' );
			//END Combine d
		
		
			// Localization
			wp_localize_script( 'go_tasks_admin_combined-min', 'GO_TASK_DATA', go_localize_task_data() );

		} 
	
	
		else if ( 'go_store' === $post->post_type ) {

			/*
			 * Task Scripts
			 */
			//Combine 
			wp_enqueue_script( 'go_store_admin-min' );

		} 
	}else if ( 'toplevel_page_game-on-options' === $hook ) {

			/*
			 * Options Page Scripts
			 */

			wp_enqueue_script( 'go_options-min' );


			// Localization

			wp_localize_script(
				'go_options-min',
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

		} else if ( 'game-on_page_go_clipboard' === $hook ) {

			/*
			 * Clipboard Scripts
			 */

			//COMBINED
			wp_enqueue_script( 'go_clipboard_combined-min' );
			//END COMBINED

			// Localization
			wp_localize_script( 'go_clipboard_combined-min', 'Minutes_limit', array( 'limit' => go_return_options( 'go_minutes_color_limit' ) ) );
			wp_localize_script(
				'go_clipboard_combined-min',
				'GO_CLIPBOARD_DATA',
				array(
					'nonces' => array(
						'go_clipboard_intable'          => wp_create_nonce( 'go_clipboard_intable_' . $user_id ),
						'go_clipboard_intable_messages' => wp_create_nonce( 'go_clipboard_intable_messages_' . $user_id ),
						'go_clipboard_intable_activity' => wp_create_nonce( 'go_clipboard_intable_activity_' . $user_id ),
						'go_update_user_focuses'        => wp_create_nonce( 'go_update_user_focuses_' . $user_id ),
						'go_clipboard_add'              => wp_create_nonce( 'go_clipboard_add_' . $user_id ),
						'go_fix_messages'               => wp_create_nonce( 'go_fix_messages_' . $user_id ),
					),
				)
			);
		} 	
	
	

	/*
	 * Registering Styles For Admin Pages
	 */

		// Dependencies
		wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
		wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/video-js/video-js.css' );

		/*
		 * Combined styles for every admin page. Even if only needed on some pages, include in one file if possible.
		 */	
			wp_register_style( 'go_admin', plugin_dir_url( __FILE__ ).'styles-min/go_admin.css' );
		//END COMBINED STYLES

	/*
	 * Enqueueing Styles For Admin Pages
	 */
		// Dependencies
		wp_enqueue_style( 'jquery-ui-css' );
		wp_enqueue_style( 'video-js-css' );
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
		wp_enqueue_script( 'jquery-ui-tabs' );

	
		/*
		 * Combined styles for every admin page. Even if only needed on some pages, include in one file if possible.
		 */	
			//Combined File
			wp_enqueue_style( 'go_admin' );
}


/*
 * Registering Scripts/Styles For The Front-end
 */

function go_scripts_and_styles () {

	/*
	 * Registering Scripts For The Front-end
	 */

		//task shortcode script is registered here, but enqueued and localized in the shortcode. 
		wp_register_script( 'go_tasks', plugin_dir_url( __FILE__ ).'scripts-min/go_tasks-min.js' );	
	
		//COMBINED FILE
		wp_register_script( 'go_frontend-min', plugin_dir_url( __FILE__ ).'scripts-min/go_frontend-min.js' );
	
		//PAGE SPECIFIC
		wp_register_script('go_map-min', plugins_url('scripts-min/go_map-min.js', __FILE__), array('jquery'),'1.1', true);

		//featherlight
		//wp_register_script( 'go_featherlight_min', plugin_dir_url( __FILE__ ).'bower_components/featherlight/release/featherlight.min.js' );
		//wp_enqueue_script( 'go_featherlight_min' );

	/*
	 * Enqueueing Scripts For The Front-end
	 */
		// COMBINED Scripts
		//wp_enqueue_script( 'go_notification' );
		//wp_enqueue_script( 'go_every_page' );
		//wp_enqueue_script( 'buy_the_item' );
		//wp_enqueue_script('go_videos_fit_and_box');
		//wp_enqueue_script( 'video-js' );
		//Combined File
		wp_enqueue_script( 'go_frontend-min' );
		
		//END COMBINED

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


		// Localization
		$user_id = get_current_user_id();

		wp_localize_script( 'go_frontend-min', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'go_frontend-min', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
		wp_localize_script(
			'go_frontend-min',
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
			'go_frontend-min',
			'GO_BUY_ITEM_DATA',
			array(
				'nonces' => array(
					'go_buy_item'           => wp_create_nonce( 'go_buy_item_' . $user_id ),
					'go_get_purchase_count' => wp_create_nonce( 'go_get_purchase_count_' . $user_id ),
				)
			)
		);
		
	/*
	 * Page-Specific Scripts.  These have localization on these pages for at least one script.
	 */	
		
		if ( is_page(map) ) {
			wp_enqueue_script('go_map-min');
			$ajax_url   = admin_url( 'admin-ajax.php' );        // Localized AJAX URL
			wp_localize_script('go_map-min','map_ajax_admin_url',$ajax_url);
		}	

	/*
	 * Registering Styles For The Front-end
	 */

		// Dependencies
		wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
		wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/video-js/video-js.css' );
		wp_register_style( 'go_lightbox', plugin_dir_url( __FILE__ ).'types/store/includes/lightbox/css/go-lightbox.css' );

		//featherlight
		//wp_register_style( 'go_featherlight_css', plugin_dir_url( __FILE__ ).'bower_components/featherlight/release/featherlight.min.css' );
		//wp_enqueue_style( 'go_featherlight_css' );


	


		// COMBINED STYLES
		wp_register_style( 'go_frontend', plugin_dir_url( __FILE__ ).'styles-min/go_frontend.css' );
		
	/*
	 * Enqueue Styles For The Front-end
	 */

		// Dependencies
		wp_enqueue_style( 'jquery-ui-css' );
		wp_enqueue_style( 'video-js-css' );
		wp_enqueue_style( 'go_lightbox' );
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 
		
		//COMBINED FILE:
		wp_enqueue_style( 'go_frontend' );
}
?>