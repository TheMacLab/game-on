<?php




/*
 * Registering Scripts/Styles For The Front-end
 */

function go_scripts () {
    global $go_js_version;
    //$site_url = get_site_url(null, 'wp-admin/css/media.css');
    wp_register_script( 'go_wp_media', get_site_url(null, 'wp-admin/css/media.css'), null, $go_js_version );
    //wp_enqueue_script( 'go_wp_media' );
	/*
	 * Registering Scripts For The Front-end
	 */
    wp_enqueue_script( 'mce-view' );

	wp_enqueue_style( 'dashicons' );

		//task shortcode script is registered here, but enqueued and localized in the shortcode.
		wp_register_script( 'go_tasks', plugin_dir_url( __FILE__ ).'min/go_tasks-min.js', null, $go_js_version );

		//COMBINED FILE
		wp_register_script( 'go_frontend-min', plugin_dir_url( __FILE__ ).'min/go_frontend-min.js', array('jquery'), $go_js_version, false);

		//PAGE SPECIFIC
		wp_register_script('go_map-min', plugins_url('min/go_map-min.js', __FILE__), array('jquery'), $go_js_version, true);

		//featherlight
		//wp_register_script( 'go_featherlight_min', plugin_dir_url( __FILE__ ).'bower_components/featherlight/release/featherlight.min.js' );
		//wp_enqueue_script( 'go_featherlight_min' );

	/*
	 * Enqueueing Scripts For The Front-end
	 */
		//Combined File
		wp_enqueue_script( 'go_frontend-min' );

        $is_admin = go_user_is_admin();
        if ($is_admin){
            wp_register_script('go_admin_notification_listener', plugins_url('min/go_admin_notifications-min.js', __FILE__), array('jquery'), $go_js_version, true);
            wp_enqueue_script( 'go_admin_notification_listener' );
            wp_localize_script(
                'go_admin_notification_listener',
                'GO_ADMIN_DATA',
                array(
                    'nonces' => array(
                        'go_admin_messages'         => wp_create_nonce( 'go_admin_messages'),
                    )
                )
            );
        }


   

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

        wp_localize_script( 'go_frontend-min', 'SiteURL', get_site_url() );
        wp_localize_script( 'go_frontend-min', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'go_frontend-min', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
		wp_localize_script(
			'go_frontend-min',
			'GO_EVERY_PAGE_DATA',
			array(
				'nonces' => array(
					'go_deactivate_plugin'         => wp_create_nonce( 'go_deactivate_plugin_' . $user_id ),
					//'go_admin_bar_add'             => wp_create_nonce( 'go_admin_bar_add_' . $user_id ),
					'go_admin_bar_stats'           => wp_create_nonce( 'go_admin_bar_stats_' ),
                    'go_stats_about'               => wp_create_nonce( 'go_stats_about' ),
                    'go_stats_task_list'           => wp_create_nonce( 'go_stats_task_list_' ),
					//'go_stats_move_stage'          => wp_create_nonce( 'go_stats_move_stage_' ),
					'go_stats_item_list'           => wp_create_nonce( 'go_stats_item_list_' ),
					//'go_stats_rewards_list'        => wp_create_nonce( 'go_stats_rewards_list_' ),
					'go_stats_activity_list'       => wp_create_nonce( 'go_stats_activity_list_' ),
                    //'go_activity_dataloader_ajax'   => wp_create_nonce( 'go_activity_dataloader_ajax_' ),
                    'go_stats_single_task_activity_list'       => wp_create_nonce( 'go_stats_single_task_activity_list' ),
					//'go_stats_penalties_list'      => wp_create_nonce( 'go_stats_penalties_list_' ),
					'go_stats_badges_list'         => wp_create_nonce( 'go_stats_badges_list_' ),
                    'go_stats_groups_list'         => wp_create_nonce( 'go_stats_groups_list_' ),
					//'go_stats_leaderboard_choices' => wp_create_nonce( 'go_stats_leaderboard_choices_' ),
					'go_stats_leaderboard'         => wp_create_nonce( 'go_stats_leaderboard_' ),
                    //'go_stats_leaderboard2'        => wp_create_nonce( 'go_stats_leaderboard2_' ),
                    'go_stats_lite'                => wp_create_nonce( 'go_stats_lite' ),
                    'go_update_admin_view'         => wp_create_nonce( 'go_update_admin_view' ),
                    'go_the_lb_ajax'                => wp_create_nonce( 'go_the_lb_ajax' ),
                    'go_update_bonus_loot'          => wp_create_nonce('go_update_bonus_loot'),
                    'go_create_admin_message' => wp_create_nonce('go_create_admin_message'),
                    'go_send_message' => wp_create_nonce('go_send_message'),
                    'go_blog_opener'                => wp_create_nonce('go_blog_opener'),
                    'go_blog_submit'                => wp_create_nonce('go_blog_submit'),
                    'go_to_this_map'                => wp_create_nonce('go_to_this_map'),
                    'go_blog_lightbox_opener'                => wp_create_nonce('go_blog_lightbox_opener')

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
				),
                'userID'	=>  $user_id
			)
		);

	/*
	 * Page-Specific Scripts.  These have localization on these pages for at least one script.
	 */
    //$map_url = get_option('options_go_locations_map_map_link');
	//	if ( is_page($map_url) ) {
			wp_enqueue_script('go_map-min');
			$ajax_url   = admin_url( 'admin-ajax.php' );        // Localized AJAX URL
			wp_localize_script('go_map-min','map_ajax_admin_url',$ajax_url);
	//	}


}
?>