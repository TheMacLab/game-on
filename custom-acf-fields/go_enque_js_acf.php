<?php

function go_acf_scripts ($hook) {
	
	global $post;
	$user_id = get_current_user_id();
	
	/*
	 * Registering Scripts For Admin Pages
	 */

		/*
		 * Combined scripts for every admin page. Combine all scripts unless the page needs localization.
		 *
		 */
			
		wp_register_script( 'go_acf-min', plugin_dir_url( __FILE__ ).'js/min/go_acf-min.js', array( 'jquery' ),v1, true);

		//Combined Scripts
		wp_enqueue_script( 'go_acf-min' ); 
		//END Combined Scripts

        wp_register_style( 'go_admin_task_afc', plugin_dir_url( __FILE__ ).'css/go_tasks-admin-acf.css' );

        wp_enqueue_style( 'go_admin_task_afc' );
	
}




?>