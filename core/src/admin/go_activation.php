<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:20 PM
 */


/**
 * Activate for existing sites on plugin activation
 * @param $network_wide
 */
function go_update_db_ms( ) {
    global $wpdb;
    if ( is_multisite() ) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            go_update_db_check();
            restore_current_blog();
        }
    }else{
        go_update_db_check();
    }
}

/**
 * Registers Game On custom post types and taxonomies, then
 * updates the site's rewrite rules to mitigate cpt and
 * permalink conflicts. flush_rewrite_rules() must always
 * be called AFTER custom post types and taxonomies are
 * registered.
 */
/**
 * Flush rewrite rules on activation
 */
function go_flush_rewrites() {
    // call your CPT registration function here (it should also be hooked into 'init')
    go_register_task_tax_and_cpt();
    go_register_store_tax_and_cpt();
    go_blog_tags();
    go_blogs();
    go_custom_rewrite();
    //go_reader_page();
    go_map_page();
    go_store_page();
    flush_rewrite_rules();
    go_custom_rewrite();
    //go_reader_page();
    go_map_page();
    go_store_page();

}



//creates a page for the store on activation of plugin
function go_store_activate() {
    $my_post = array(
        'post_title'    => 'Store',
        'post_content'  => '[go_make_store]',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'   => 'page',
    );
    // Insert the post into the database

    $page = get_page_by_path( "store" , OBJECT );

    if ( ! isset($page) ){
        wp_insert_post( $my_post );
    }
}


/**
 *
 */
function go_map_activate() {
    $my_post = array(
        'post_title'    => 'Map',
        'post_content'  => '[go_make_map]',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'   => 'page',
    );

    $page = get_page_by_path( "map" , OBJECT );

    if ( ! isset($page) ){
        wp_insert_post( $my_post );
    }
}

/**
 *
 */
function go_reader_activate() {
    $my_post = array(
        'post_title'    => 'Reader',
        'post_content'  => '[go_make_reader]',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'   => 'page',
    );

    $page = get_page_by_path( "Reader" , OBJECT );

    if ( ! isset($page) ){
        wp_insert_post( $my_post );
    }
}




/**
 * Changes roles so subscribers can upload media
 */
function go_media_access() {
    $role = get_role( 'subscriber' );
    $role->add_cap( 'upload_files' );
    //$role->add_cap( 'edit_posts' );

    //$role = get_role( 'contributor' );
    //$role->add_cap( 'upload_files' );

}

function go_tsk_actv_activate() {
    add_option( 'go_tsk_actv_do_activation_redirect', true );
    update_option( 'go_display_admin_explanation', true );
}



//this is the activation notification
function go_admin_head_notification() {
    if ( get_option( 'go_display_admin_explanation' ) && current_user_can( 'manage_options' ) ) {
        $nonce = wp_create_nonce( 'go_admin_remove_notification_' . get_current_user_id() );
        $url = get_site_url(null, 'wp-admin/admin.php?page=game-tools');
        echo "<div id='go_activation_message' class='update-nag' style='font-size: 16px; padding-right: 50px;'>This is a fresh installation of <a href='https://github.com/mcmick/game-on-v4/releases' target='_blank'>Game On</a>.

			<div style='position: relative; left: 20px;'>
				<br>
				Visit the <a href='http://maclab.guhsd.net/game-on' target='_blank'>documentation page</a>.
				<br>
				<br>
				Visit our <a href='https://www.youtube.com/channel/UC1G3josozpubdzaINcFjk0g' >YouTube Channel</a> for the most recent updates.
				<br>
				<br>
				Did you just update from version 3? Check out the <a href='{$url}'>upgrade tool</a>.
				<br>
				<br>
			</div>
			<a href='javascript:;' onclick='go_remove_admin_notification()'>Dismiss messsage</a>
		</div>
		<script>
			function go_remove_admin_notification() {
				jQuery.ajax( {
					type: 'post',
					url: MyAjax.ajaxurl,
					data: {
						_ajax_nonce: '{$nonce}',
						action: 'go_admin_remove_notification'
					},
					success: function( ) {
							jQuery('#go_activation_message').remove();
					}
				} );
			}
		</script>";
    }
}
add_action( 'admin_notices', 'go_admin_head_notification' );




function go_tsk_actv_redirect() {
    if ( get_option( 'go_tsk_actv_do_activation_redirect', false ) ) {
        delete_option( 'go_tsk_actv_do_activation_redirect' );
        if ( ! isset( $_GET['activate-multi'] ) ) {
            wp_redirect( 'admin.php?page=go_options' );
        }
    }
}
add_action( 'admin_init', 'go_tsk_actv_redirect' );

