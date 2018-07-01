<?php

function go_admin_styles () {
    global $version;
    /*
         * Registering Styles For Admin Pages
         */

    // Dependencies
    wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css', null, $version );


    wp_register_style( 'go_admin', plugin_dir_url( __FILE__ ).'min/go_admin.css', null, $version );


    /*
     * Enqueueing Styles For Admin Pages
     */
    // Dependencies
    wp_enqueue_style( 'jquery-ui-css' );
    wp_enqueue_style( 'video-js-css' );
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_script( 'jquery-ui-tabs' );


    /*
     * Combined styles for every admin page. Even if only needed on some pages, include in one file if possible.
     */
    //Combined File
    wp_enqueue_style( 'go_admin' );


}


?>