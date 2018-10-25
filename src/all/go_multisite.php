<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:03 PM
 */

/**
 * MULTISITE FUNCTIONS
 *
 */

/**
 * Activate for existing sites on plugin activation
 * @param $network_wide
 */
//I DOn't think this is active--fix this. (it's called in the main file.)
function go_on_activate_msdb( $network_wide ) {
    global $wpdb;
    if ( is_multisite() && $network_wide ) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            go_update_db();
            restore_current_blog();
        }
    } else {
        go_update_db_check();
    }
}

/**
 * Creating table whenever a new blog is created
 * @param $blog_id
 * @param $user_id
 * @param $domain
 * @param $path
 * @param $site_id
 * @param $meta
 */
function go_on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( is_plugin_active_for_network(__FILE__ ) ) {
        switch_to_blog( $blog_id );
        go_update_db();
        restore_current_blog();
    }
}
add_action( 'wpmu_new_blog', 'go_on_create_blog', 10, 6 );

/**
 * // Deleting the table whenever a blog is deleted
 * @param $tables
 * @return array
 */
function go_on_delete_blog( $tables ) {
    global $wpdb;
    $tables[] = $wpdb->prefix . 'go_tasks';
    $tables[] = $wpdb->prefix . 'go_actions';
    $tables[] = $wpdb->prefix . 'go_loot';
    //$tables[] = $wpdb->prefix . 'go_totals';

    return $tables;
}
add_filter( 'wpmu_drop_tables', 'go_on_delete_blog' );


