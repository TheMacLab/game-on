<?php
if( ! class_exists( 'FitVidsWP' ) ) {
    // FitVids Plugin is not active
    include( 'fitvids-for-wordpress/fitvids-for-wordpress.php' );
}

if( ! class_exists( 'WP_Term_Order' ) ) {
    // WP Term Order Plugin is not active
    include( 'wp-term-order/wp-term-order.php' );
}

function go_admin_includes () {
	

    /**
     * Datatables
     */

    wp_register_script( 'go_datatables', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.js', array( 'jquery' ),v1, false);
    wp_enqueue_script( 'go_datatables' );

    wp_register_style( 'go_datatables_css', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.css' );
    wp_enqueue_style( 'go_datatables_css' );

    /**
     * Featherlight
     */

    wp_register_script( 'go_featherlight', plugin_dir_url( __FILE__ ).'featherlight/release/featherlight.min.css', array( 'jquery' ),v1, true);
    wp_enqueue_script( 'go_featherlight' );

    wp_register_style( 'go_featherlight_css', plugin_dir_url( __FILE__ ).'featherlight/release/featherlight.min.js' );
    wp_enqueue_style( 'go_featherlight_css' );


}

function go_includes () {

    /**
     * Frontend Media
     */
    wp_register_script( 'go_frontend_media', plugin_dir_url( __FILE__ ).'wp-frontend-media-master/js/frontend.js', array( 'jquery' ),
        '2015-05-07', true);
   wp_enqueue_script( 'go_frontend_media' );



    /**
     * Featherlight
     */

    wp_register_script( 'go_featherlight', plugin_dir_url( __FILE__ ).'featherlight/release/featherlight.min.js', array( 'jquery' ),v1, true);
    wp_enqueue_script( 'go_featherlight' );

    wp_register_style( 'go_featherlight_css', plugin_dir_url( __FILE__ ).'featherlight/css/wp-featherlight.min.css' );
    wp_enqueue_style( 'go_featherlight_css' );

    wp_register_script( 'go_collapse_lists', plugin_dir_url( __FILE__ ).'CollapsibleLists.js', array( 'jquery' ),v2, true);
    wp_enqueue_script( 'go_collapse_lists' );


}

/*

// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');
 
function my_acf_settings_path( $path ) {
 
    // update path
    $path = '/acf/';
    
    // return
    return $path;
    
}
 

// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');
 
function my_acf_settings_dir( $dir ) {
 
    // update path
    $dir =  '/acf/';
    
    // return
    return $dir;
    
}
 */

// 3. Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_false');


// 4. Include ACF






?>