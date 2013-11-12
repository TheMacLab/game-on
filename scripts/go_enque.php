<?php
function go_jquery() {
    wp_enqueue_script( 'jquery' );
	
		wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-ui-spinner');
	
	wp_enqueue_script( 'go_jquery', plugin_dir_url(__FILE__).'go_test_jquery.js');
	wp_enqueue_script( 'go_notification', plugin_dir_url(__FILE__).'go_notification.js');
	wp_enqueue_script( 'go_everypage', plugin_dir_url(__FILE__).'go_everypage.js');
	wp_localize_script( 'go_everypage', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'go_css_pie', plugin_dir_url(__FILE__).'CSSpie.js');
	
		wp_enqueue_script('jquery-ui-progressbar');
			
	
	
}
function go_jquery_periods() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'go_jquery_periods', plugin_dir_url(__FILE__).'go_periods.js');
	wp_localize_script( 'go_jquery_periods', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
}
function go_jquery_clipboard() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'go_excanvas', plugin_dir_url(__FILE__).'/flot/excanvas.min.js');
	wp_enqueue_script( 'go_flot', plugin_dir_url(__FILE__).'/flot/jquery.flot.min.js');
	wp_enqueue_script( 'go_flot_time', plugin_dir_url(__FILE__).'/flot/jquery.flot.time.min.js');
	wp_enqueue_script( 'go_flot_selection', plugin_dir_url(__FILE__).'/flot/jquery.flot.selection.min.js');
	wp_enqueue_script( 'go_jquery_clipboard', plugin_dir_url(__FILE__).'go_clipboard.js');
	wp_enqueue_script( 'go_jquery_clipboard_tablesorter', plugin_dir_url(__FILE__).'sorttable.js');
	
	wp_localize_script( 'go_jquery_clipboard', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script( 'jquery-ui-accordion' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	
}
function go_presets_js(){
	 wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'go_presets', plugin_dir_url(__FILE__).'go_presets.js');
	}
?>