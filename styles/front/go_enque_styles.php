<?php


/*
 * Registering Scripts/Styles For The Front-end
 */

function go_styles () {


	/*
	 * Registering Styles For The Front-end
	 */

		// Dependencies
		wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', null, 1.112 );
		//wp_register_style( 'video-js-css', plugin_dir_url( __FILE__ ).'scripts/front/scripts/video-js/video-js.css' );
		//wp_register_style( 'go_lightbox', plugin_dir_url( __FILE__ ).'min/go-lightbox.css' );

		//featherlight
		//wp_register_style( 'go_featherlight_css', plugin_dir_url( __FILE__ ).'bower_components/featherlight/release/featherlight.min.css' );
		//wp_enqueue_style( 'go_featherlight_css' );

		// COMBINED STYLES
		wp_register_style( 'go_frontend', plugin_dir_url( __FILE__ ).'min/go_frontend.css', null, 4.09 );
		
	/*
	 * Enqueue Styles For The Front-end
	 */

		// Dependencies
		wp_enqueue_style( 'jquery-ui-css' );
		//wp_enqueue_style( 'video-js-css' );
		//wp_enqueue_style( 'go_lightbox' );
		wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		
		//COMBINED FILE:
		wp_enqueue_style( 'go_frontend' );
}
?>