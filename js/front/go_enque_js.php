<?php




/*
 * Registering Scripts/Styles For The Front-end
 */

function go_scripts () {

	/*
	 * Registering Scripts For The Front-end
	 */

		//task shortcode script is registered here, but enqueued and localized in the shortcode. 
		wp_register_script( 'go_tasks', plugin_dir_url( __FILE__ ).'min/go_tasks-min.js' );	
	
		//COMBINED FILE
		wp_register_script( 'go_frontend-min', plugin_dir_url( __FILE__ ).'min/go_frontend-min.js' );
	
		//PAGE SPECIFIC
		wp_register_script('go_map-min', plugins_url('min/go_map-min.js', __FILE__), array('jquery'),'1.1', true);

		//featherlight
		//wp_register_script( 'go_featherlight_min', plugin_dir_url( __FILE__ ).'bower_components/featherlight/release/featherlight.min.js' );
		//wp_enqueue_script( 'go_featherlight_min' );

	/*
	 * Enqueueing Scripts For The Front-end
	 */
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
                    'go_update_admin_view'         => wp_create_nonce( 'go_update_admin_view' )

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

	
}
?>