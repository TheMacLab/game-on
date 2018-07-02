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

    //wp_register_script( 'go_datatables', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.js', array( 'jquery' ),'v1', false);
    wp_register_script( 'go_datatables', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array( 'jquery' ),'v1', false);
    wp_enqueue_script( 'go_datatables' );

    //wp_register_style( 'go_datatables_css', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.css' );
    wp_register_style( 'go_datatables_css', 'wp_register_style( \'go_datatables_css\', plugin_dir_url( __FILE__ ).\'DataTables/datatables.min.css\' );' );
    wp_enqueue_style( 'go_datatables_css' );

    /**
     * Featherlight
     */

    //wp_register_script( 'go_featherlight', plugin_dir_url( __FILE__ ).'featherlight/release/featherlight.min.js', array( 'jquery' ),'v1', true);
    wp_register_script( 'go_featherlight', '//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js','v1.7.13', true);
    wp_enqueue_script( 'go_featherlight' );

    //wp_register_style( 'go_featherlight_css', plugin_dir_url( __FILE__ ).'featherlight/css/wp-featherlight.min.css' );
    wp_register_style( 'go_featherlight_css', '//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css', null,'v1.7.13' );
    wp_enqueue_style( 'go_featherlight_css' );


}

function go_includes () {

    /**
     * Datatables
     */

    //wp_register_script( 'go_datatables', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.js', array( 'jquery' ),'v1', false);
    wp_register_script( 'go_datatables', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array( 'jquery' ),'v1', false);
    wp_enqueue_script( 'go_datatables' );

    //wp_register_style( 'go_datatables_css', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.css' );
    wp_register_style( 'go_datatables_css', 'wp_register_style( \'go_datatables_css\', plugin_dir_url( __FILE__ ).\'DataTables/datatables.min.css\' );' );
    wp_enqueue_style( 'go_datatables_css' );


    /**
     * Frontend Media
     */
    wp_register_script( 'go_frontend_media', plugin_dir_url( __FILE__ ).'wp-frontend-media-master/js/frontend.js', array( 'jquery' ),
        '2015-05-07', true);
    wp_enqueue_script( 'go_frontend_media' );

    /**
     * Featherlight
     */

    //wp_register_script( 'go_featherlight', plugin_dir_url( __FILE__ ).'featherlight/release/featherlight.min.js', array( 'jquery' ),'v1', true);
    wp_register_script( 'go_featherlight', '//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js','v1.7.13', true);
    wp_enqueue_script( 'go_featherlight' );

    //wp_register_style( 'go_featherlight_css', plugin_dir_url( __FILE__ ).'featherlight/css/wp-featherlight.min.css' );
    wp_register_style( 'go_featherlight_css', '//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css', null,'v1.7.13' );
    wp_enqueue_style( 'go_featherlight_css' );

    wp_register_script( 'go_collapse_lists', plugin_dir_url( __FILE__ ).'CollapsibleLists.js', array( 'jquery' ),'v2', true);
    wp_enqueue_script( 'go_collapse_lists' );

    /**
     * noty
     */

    wp_register_script( 'go_noty', plugin_dir_url( __FILE__ ).'noty/lib/noty.js', '','v1', true);
    wp_enqueue_script( 'go_noty' );

    wp_register_style( 'go_noty_css', plugin_dir_url( __FILE__ ).'noty/lib/noty.css' );
    wp_enqueue_style( 'go_noty_css' );

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
//add_filter('acf/settings/show_admin', '__return_false');


// 4. Include ACF






?>