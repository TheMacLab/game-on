<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:06 PM
 */


function go_user_redirect( $redirect_to, $request, $user )
{
    //if (is_user_logged_in()) {
    //$redirect_on = get_option( 'options_go_landing_page_on_login', true );
    if (isset($user) && ($user instanceof WP_User)) {
        $redirect_url = get_option('options_go_landing_page_on_login', '');
        $default_map = get_option('options_go_locations_map_default', '');
        $user_id = $user->ID;
        if ($default_map !== '') {
            update_user_option($user_id, 'go_last_map', $default_map);
        }
        if (isset($user->roles) && is_array($user->roles)) {
            $roles = $user->roles;
            if (is_array($roles)) {
                if (in_array('administrator', $roles)) {
                    return admin_url();
                } else {
                    if (!empty ($redirect_url)) {
                        return (site_url() . '/' . $redirect_url);
                    } else {
                        return site_url();
                    }
                }
            } else {
                if ($roles == 'administrator') {
                    return admin_url();
                } else {
                    if (!empty ($redirect_url)) {
                        return (site_url() . '/' . $redirect_url);
                    } else {
                        return site_url();
                    }
                }
            }
        }
    } else {
        return $redirect_to;
    }
    //}
}
add_action( 'login_redirect', 'go_user_redirect', 10, 3 );



/**
 * Get user's first and last name, else just their first name, else their
 * display name. Defalts to the current user if $user_id is not provided.
 *
 * @param  mixed  $user_id The user ID or object. Default is current user.
 * @return string          The user's name.
 */
function go_get_users_name( $user_id = null ) {
    $user_info = $user_id ? new WP_User( $user_id ) : wp_get_current_user();
    if ( $user_info->first_name ) {
        if ( $user_info->last_name ) {
            return $user_info->first_name . ' ' . $user_info->last_name;
        }
        return $user_info->first_name;
    }
    return $user_info->display_name;
}

/**
 * Determines whether or not a user is an administrator with management capabilities.
 *
 * @since 3.0.0
 *
 * @param int $user_id Optional. The user ID.
 * @return boolean True if the user has the 'administrator' role and has the 'manage_options'
 *                 capability. False otherwise.
 */
function go_user_is_admin( $user_id = null ) {
    if ( empty( $user_id ) ) {
        $user_id = get_current_user_id();
    } else {
        $user_id = (int) $user_id;
    }

    if(user_can( $user_id, 'manage_options' )) {
        return true;
    }
    return false;
}



// Adds user id to the totals table upon user creation.
function go_user_registration ( $user_id ) {
    global $wpdb;
    $table_name_go_totals = "{$wpdb->prefix}go_loot";
    $table_name_capabilities = "{$wpdb->prefix}capabilities";
    $role = get_option( 'go_role', 'subscriber' );
    $user_role = get_user_option("{$table_name_capabilities}", $user_id);
    if ( array_search( 1, $user_role ) == $role || array_search( 1, $user_role ) == 'administrator' ) {

        // this should update the user's rank metadata
        //go_update_ranks( $user_id, 0 );

        // this should set the user's points to 0
        $wpdb->insert( $table_name_go_totals, array( 'uid' => $user_id), array( '%s' ) );
    }
}

// Deletes all rows related to a user in the individual and total tables upon deleting said user.
function go_user_delete( $user_id ) {
    global $wpdb;
    $table_name_go_totals = "{$wpdb->prefix}go_loot";
    $table_name_go_tasks = "{$wpdb->prefix}go_tasks";
    $table_name_go_actions = "{$wpdb->prefix}go_actions";

    $wpdb->delete( $table_name_go_totals, array( 'uid' => $user_id ) );
    $wpdb->delete( $table_name_go_tasks, array( 'uid' => $user_id ) );
    $wpdb->delete( $table_name_go_actions, array( 'uid' => $user_id ) );
}


function go_add_user_to_totals_table_at_login($user_login, $user){
    $user_id = $user->ID;

    go_add_user_to_totals_table($user_id);
}
add_action('wp_login', 'go_add_user_to_totals_table_at_login', 10, 2);


