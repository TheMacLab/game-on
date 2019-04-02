<?php

function go_admin_scripts ($hook) {
    global $post;
    global $go_js_version;
    global $go_debug;

    $user_id = get_current_user_id();
    //is the current user an admin
    $is_admin = go_user_is_admin($user_id);

    /*
     * Registering Scripts For Admin Pages
     */

    wp_enqueue_style( 'dashicons' );

    /*
     * Combined scripts for every admin page. Combine all scripts unless the page needs localization.
     */

    wp_register_script( 'go_scripts', plugin_dir_url( __FILE__ ).'min/go_scripts-min.js', array( 'jquery' ), $go_js_version, true);

    wp_register_script( 'go_admin_user', plugin_dir_url( __FILE__ ).'min/go_admin_user-min.js', array( 'jquery' ), $go_js_version, true);

    wp_register_script( 'go_clipboard', plugin_dir_url( __FILE__ ).'min/go_clipboard-min.js', array( 'jquery' ), $go_js_version, true);

    wp_register_script( 'go_admin_page', plugin_dir_url( __FILE__ ).'min/go_every_admin_page-min.js', array( 'jquery' ), $go_js_version, true);

    //this one doesn't minify for some reason
    wp_register_script( 'go_admin-tools', plugin_dir_url( __FILE__ ).'scripts/go_tools.js', array( 'jquery' ), $go_js_version, true);

    if(!$go_debug) {
        wp_register_script('go_admin_notifications', plugin_dir_url(__FILE__) . 'scripts/go_admin_notifications.js', array('jquery'), $go_js_version, true);
        wp_enqueue_script('go_admin_notifications');
    }


    /*
     * Enqueue Scripts For Admin Pages (Except for page specific ones below)
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
    //wp_enqueue_script( 'go_featherlight_min' );

    //Combined Scripts
    wp_enqueue_script( 'go_scripts' );

    //single script
    wp_enqueue_script( 'go_admin-tools' );

    //Combined Scripts
    wp_enqueue_script( 'go_admin_page' );

    //END Combined Scripts

    $go_lightbox_switch = get_option( 'options_go_video_lightbox' );
    $go_video_unit = get_option ('options_go_video_width_unit');
    $go_fitvids_maxwidth = "";
    if ($go_video_unit == 'px'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_pixels')."px";
    }
    if ($go_video_unit == '%'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_percent')."%";
    }

		// Localization for all admin page
        wp_localize_script( 'go_scripts', 'SiteURL', get_site_url() );
        wp_localize_script( 'go_scripts', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'go_scripts', 'PluginDir', array( 'url' => plugin_dir_url(dirname(__FILE__) ) ) );
		wp_localize_script(
			'go_scripts',
			'GO_EVERY_PAGE_DATA',
			array(
				'nonces' => array(
					'go_deactivate_plugin'         	=> wp_create_nonce( 'go_deactivate_plugin_' . $user_id ),
					//'go_admin_bar_add'             	=> wp_create_nonce( 'go_admin_bar_add_' . $user_id ),
					'go_admin_bar_stats'           	=> wp_create_nonce( 'go_admin_bar_stats_' ),
                    'go_stats_about'               	=> wp_create_nonce( 'go_stats_about' ),
					'go_stats_task_list'           	=> wp_create_nonce( 'go_stats_task_list_' ),
					//'go_stats_move_stage'         => wp_create_nonce( 'go_stats_move_stage_' ),
					'go_stats_item_list'           	=> wp_create_nonce( 'go_stats_item_list_' ),
					//'go_stats_rewards_list'       => wp_create_nonce( 'go_stats_rewards_list_' ),
					'go_stats_activity_list'       	=> wp_create_nonce( 'go_stats_activity_list_' ),
                    'go_stats_messages'       	    => wp_create_nonce( 'go_stats_messages' ),
                    //'go_activity_dataloader_ajax'   => wp_create_nonce( 'go_activity_dataloader_ajax_' ),
                    'go_stats_single_task_activity_list'       => wp_create_nonce( 'go_stats_single_task_activity_list' ),
					//'go_stats_penalties_list'     => wp_create_nonce( 'go_stats_penalties_list_' ),
					'go_stats_badges_list'         	=> wp_create_nonce( 'go_stats_badges_list_' ),
                    'go_stats_groups_list'         	=> wp_create_nonce( 'go_stats_groups_list_' ),
					//'go_stats_leaderboard_choices' => wp_create_nonce( 'go_stats_leaderboard_choices_' ),
					'go_stats_leaderboard'         	=> wp_create_nonce( 'go_stats_leaderboard_' ),
                    //'go_stats_leaderboard2'        	=> wp_create_nonce( 'go_stats_leaderboard2_' ),
                    'go_stats_lite'                	=> wp_create_nonce( 'go_stats_lite' ),
                    'go_upgade4'                   	=> wp_create_nonce( 'go_upgade4'),
                    'go_reset_all_users'			=> wp_create_nonce( 'go_reset_all_users'),
                    'go_the_lb_ajax' 				=> wp_create_nonce( 'go_the_lb_ajax' ),
                    'go_create_admin_message' 		=> wp_create_nonce('go_create_admin_message'),
                    'go_send_message' 				=> wp_create_nonce('go_send_message'),
                    'go_blog_lightbox_opener'       => wp_create_nonce('go_blog_lightbox_opener'),
                    'go_blog_user_task'             => wp_create_nonce('go_blog_user_task'),
                    'go_user_map_ajax'              => wp_create_nonce('go_user_map_ajax'),
                    'go_update_last_map'            => wp_create_nonce('go_update_last_map'),
                    'go_blog_favorite_toggle'            => wp_create_nonce('go_blog_favorite_toggle')
				),
				'go_is_admin'                   => $is_admin,
                'go_lightbox_switch'            => $go_lightbox_switch,
                'go_fitvids_maxwidth'           => $go_fitvids_maxwidth
			)
		);

    $is_admin_user = go_user_is_admin();
    if ($is_admin_user){
        wp_localize_script(
            'go_scripts',
            'GO_ADMIN_DATA',
            array(
                'nonces' => array(
                    'go_admin_messages'         => wp_create_nonce( 'go_admin_messages'),
                )
            )
        );

        wp_enqueue_script( 'go_admin_user' );

        if ( 'toplevel_page_go_clipboard' === $hook ) {

            /*
             * Clipboard Scripts
             */

            //COMBINED
            wp_enqueue_script( 'go_clipboard' );

            // Localization
            //wp_localize_script( 'go_admin_user', 'Minutes_limit', array( 'limit' => get_option( 'go_minutes_color_limit' ) ) );
            wp_localize_script(
                'go_clipboard',
                'GO_CLIPBOARD_DATA',
                array(
                    'nonces' => array(
                        'go_clipboard_stats'          => wp_create_nonce( 'go_clipboard_stats_' . $user_id ),
                        //'go_clipboard_intable_messages' => wp_create_nonce( 'go_clipboard_intable_messages_' . $user_id ),
                        'go_clipboard_activity' => wp_create_nonce( 'go_clipboard_activity_' . $user_id ),
                        //'go_activity_stateSave' => wp_create_nonce( 'go_activity_stateSave_' . $user_id ),
                        'go_clipboard_messages' => wp_create_nonce( 'go_clipboard_messages'),
                        'go_clipboard_store' => wp_create_nonce( 'go_clipboard_store'),
                        //'go_update_user_focuses'        => wp_create_nonce( 'go_update_user_focuses_' . $user_id ),
                        //'go_clipboard_add'              => wp_create_nonce( 'go_clipboard_add_' . $user_id ),
                        //'go_fix_messages'               => wp_create_nonce( 'go_fix_messages_' . $user_id ),
                        'go_clipboard_save_filters'     => wp_create_nonce( 'go_clipboard_save_filters_' . $user_id )
                    ),
                )
            );
        }

        // Enqueue and Localization for options page
        if ( 'toplevel_page_go_options' === $hook ) {

            //wp_enqueue_script('go_options_admin_js');
            wp_localize_script('go_admin_user', 'levelGrowth', get_option('options_go_loot_xp_levels_growth'));
            wp_localize_script('go_admin_user', 'go_is_options_page', array('is_options_page' => true));
        }

        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
            if ( 'go_store' === $post->post_type ) {
                //wp_enqueue_script('go_edit_store');
                $id = get_the_ID();
                $store_name = get_option( 'options_go_store_name');
                wp_localize_script( 'go_admin_user', 'GO_EDIT_STORE_DATA', array( 'postid' => $id , 'store_name' => $store_name, 'is_store_edit' => true ));
            }
        }
    }

    /**
     * Resize All Images on Client Side
     */
    //wp_enqueue_script( 'client-resize' , plugins_url( 'scripts/client-side-image-resize.js' , __FILE__ ) , array('media-editor' ) , '0.0.1' );
    /*
    wp_localize_script( 'client-resize' , 'client_resize' , array(
        'plupload' => array(
            'resize' => array(
                'enabled' => true,
                'width' => 1920, // enter your width here
                'height' => 1200, // enter your width here
                'quality' => 90,
            ),
        )
    ) );
    */

}




?>
