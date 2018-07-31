<?php

function go_acf_scripts ($hook) {

	global $post;
	/*
	 * Registering Scripts For Admin Pages
	 */

		/*
		 * Combined scripts for every admin page. Combine all scripts unless the page needs localization.
		 *
		 */
			
		wp_register_script( 'go_acf-min', plugin_dir_url( __FILE__ ).'js/min/go_acf-min.js', array( 'jquery' ),'v1', true);

		//Combined Scripts
		wp_enqueue_script( 'go_acf-min' ); 
		//END Combined Scripts

        wp_register_style( 'go_admin_task_afc', plugin_dir_url( __FILE__ ).'css/go_tasks-admin-acf.css' );

        wp_enqueue_style( 'go_admin_task_afc' );

        wp_localize_script(
            'go_acf-min',
            'GO_ACF_DATA',
            array(
                    'go_store_toggle'       => get_option('options_go_store_toggle') ,
                    'go_map_toggle'         => get_option('options_go_locations_map_toggle') ,
                    'go_top_menu_toggle'    => get_option('options_go_locations_top_menu_toggle') ,
                    'go_widget_toggle'      => get_option('options_go_locations_widget_toggle') ,
                    'go_gold_toggle'        => get_option('options_go_loot_gold_toggle') ,
                    'go_xp_toggle'          => get_option('options_go_loot_xp_toggle') ,
                    'go_health_toggle'      => get_option('options_go_loot_health_toggle') ,
                    'go_c4_toggle'          => get_option('options_go_loot_c4_toggle') ,
                    'go_badges_toggle'      => get_option('options_go_badges_toggle'),
                    //'go_leaderboard_toggle'      => get_option('options_go_stats_leaderboard_toggle')

            )
        );
	
}

add_action('acf/field_group/admin_enqueue_scripts', 'go_acf_scripts');



?>