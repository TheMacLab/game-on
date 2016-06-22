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
	wp_register_script( 'go_tasks', plugin_dir_url( __FILE__ ).'scripts/go_tasks_admin.js', array( 'jquery' ) );
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

			wp_enqueue_script( 'go_tasks' );
			wp_enqueue_script( 'go_presets' );
			wp_enqueue_script( 'ptTimeSelectJS' );

			// Localization

			wp_localize_script( 'go_tasks', 'GO_TASK_DATA', go_localize_task_data() );

			/*
			 * Task Styles
			 */

			wp_enqueue_style( 'ptTimeSelectCSS' );

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
		wp_localize_script( 'go_jquery_clipboard', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( 'go_jquery_clipboard', 'Minutes_limit', array( 'limit' => go_return_options( 'go_minutes_color_limit' ) ) );

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
	wp_register_script( 'cat_the_item', plugin_dir_url( __FILE__ ).'types/store/includes/lightbox/js/cat_the_item.js', array( 'jquery' ), 1.0, true );

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
	wp_enqueue_script( 'cat_the_item' );

	// Localization
	wp_localize_script( 'go_every_page', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go_every_page', 'PluginDir', array( 'url' => plugin_dir_url( __FILE__ ) ) );
	wp_localize_script( 'buy_the_item', 'buy_item', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'cat_the_item', 'cat_item', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); //create ajaxurl global for front-end AJAX call; 

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