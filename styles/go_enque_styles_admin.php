<?php

function go_admin_styles () {
    global $go_css_version;
    /*
     * Registering Styles For Admin Pages
     */

    wp_register_style( 'go_admin', plugin_dir_url( __FILE__ ).'min/go_admin.css', null, $go_css_version );

    // Styles for all GO
    wp_register_style( 'go_styles', plugin_dir_url( __FILE__ ).'min/go_styles.css', null, $go_css_version );

    /*
     * Enqueueing Styles For Admin Pages
     */
    /*
     * Combined styles for every admin page. Even if only needed on some pages, include in one file if possible.
     */
    //Combined File
    wp_enqueue_style( 'go_admin' );

    wp_enqueue_style( 'go_styles' );


}


?>