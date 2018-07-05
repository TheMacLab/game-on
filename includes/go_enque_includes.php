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
    wp_register_script( 'go_datatables', 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.1/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.js', array( 'jquery' ),'v1.1', false);
    wp_enqueue_script( 'go_datatables' );

    wp_register_script( 'go_pdf_make', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js','v1.7.13', true);
    wp_enqueue_script( 'go_pdf_make' );

    wp_register_script( 'go_pdf_make_fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js','v1.7.13', true);
    wp_enqueue_script( 'go_pdf_make_fonts' );

    //wp_register_style( 'go_datatables_css', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.css' );
    wp_register_style( 'go_datatables_css', 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.1/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.css' );
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
    wp_register_script( 'go_datatables', 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.1/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.js', array( 'jquery' ),'v1.1', false);
    wp_enqueue_script( 'go_datatables' );

    wp_register_script( 'go_pdf_make', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js','v1.7.13', true);
    wp_enqueue_script( 'go_pdf_make' );

    wp_register_script( 'go_pdf_make_fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js','v1.7.13', true);
    wp_enqueue_script( 'go_pdf_make_fonts' );

    //wp_register_style( 'go_datatables_css', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.css' );
    wp_register_style( 'go_datatables_css', 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.1/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.css' );
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