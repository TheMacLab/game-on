<?php

function go_jquery() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery-effects-core' );
	wp_enqueue_script( 'jquery-ui-spinner' );
	wp_enqueue_script( 'go_jquery', plugin_dir_url( __FILE__ ).'go_test_jquery.js' );
	wp_enqueue_script( 'go_notification', plugin_dir_url( __FILE__ ).'go_notification.js' );
	wp_enqueue_script( 'go_everypage', plugin_dir_url( __FILE__ ).'go_everypage.js' );
	wp_localize_script( 'go_everypage', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go_everypage', 'PluginDir', array( 'url' => plugins_url( '/', dirname( __FILE__ ) ) ) );
	wp_enqueue_script( 'video', plugin_dir_url( __FILE__ ).'/video-js/video.js' );
	wp_enqueue_style( 'video-js', plugin_dir_url( __FILE__ ).'/video-js/video-js.css' );
	wp_enqueue_script( 'jquery-ui-progressbar' );
}

function go_jquery_periods() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'go_jquery_periods', plugin_dir_url( __FILE__ ).'go_periods.js' );
	wp_localize_script( 'go_jquery_periods', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
}

function go_jquery_clipboard() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery.dataTables.min.js', plugin_dir_url( __FILE__ ).'jquery.dataTables.min.js' );
	wp_enqueue_script( 'go_jquery_clipboard', plugin_dir_url( __FILE__ ).'go_clipboard.js' );
	wp_enqueue_script( 'go_jquery_clipboard_tablesorter', plugin_dir_url( __FILE__ ).'sorttable.js' );
	wp_localize_script( 'go_jquery_clipboard', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_localize_script( 'go_jquery_clipboard', 'Minutes_limit', array( 'limit' => go_return_options( 'go_minutes_color_limit' ) ) );
}

function go_presets_js() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'go_presets', plugin_dir_url( __FILE__ ).'go_presets.js' );
}
?>