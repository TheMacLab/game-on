<?php
/*
Plugin Name: Game-On
Plugin URI: http://maclab.guhsd.net/game-on
Description: Gamification tools for teachers.
Author: Valhalla Mac Lab
Author URI: https://github.com/TheMacLab/game-on/blob/master/README.md
Version: 4.07.19
*/
$version = 4.0719;

global $version;

include_once( 'includes/acf/acf.php' );


foreach ( glob( plugin_dir_path( __FILE__ ) . "*.php" ) as $file ) {
    include_once $file;
}

include_once "types/types.php";

foreach ( glob( plugin_dir_path( __FILE__ ) . "types/store/*.php" ) as $file ) {
    include_once $file;
}

foreach ( glob( plugin_dir_path( __FILE__ ) . "types/tasks/*.php" ) as $file ) {
    include_once $file;
}

foreach ( glob( plugin_dir_path( __FILE__ ) . "js/admin/*.php" ) as $file ) {
    include_once $file;
}

foreach ( glob( plugin_dir_path( __FILE__ ) . "js/front/*.php" ) as $file ) {
    include_once $file;
}

foreach ( glob( plugin_dir_path( __FILE__ ) . "styles/admin/*.php" ) as $file ) {
    include_once $file;
}

foreach ( glob( plugin_dir_path( __FILE__ ) . "styles/front/*.php" ) as $file ) {
    include_once $file;
}

foreach ( glob( plugin_dir_path( __FILE__ ) . "src/*.php" ) as $file ) {
    include_once $file;
}



//if( ! class_exists( 'wp-featherlight' ) ) {
//	include( 'includes/wp-featherlight/wp-featherlight.php' );
//}

// include custom ACF fields
foreach ( glob( plugin_dir_path( __FILE__ ) . "custom-acf-fields/*.php" ) as $file ) {
    include_once $file;
}


// includes
foreach ( glob( plugin_dir_path( __FILE__ ) . "includes/*.php" ) as $file ) {
    include_once $file;
}


include( 'includes/wp-frontend-media-master/frontend-media.php' );




/*
 * Plugin Activation Hooks
 */

register_activation_hook( __FILE__, 'go_update_db' );
//register_activation_hook( __FILE__, 'go_table_individual' );
//register_activation_hook( __FILE__, 'go_ranks_registration' );
//register_activation_hook( __FILE__, 'go_presets_registration' );
register_activation_hook( __FILE__, 'go_install_data' );
register_activation_hook( __FILE__, 'go_open_comments' );
register_activation_hook( __FILE__, 'go_tsk_actv_activate' );
register_activation_hook( __FILE__, 'go_map_activate' );
register_activation_hook( __FILE__, 'go_store_activate' );
//Multisite--create tables on existing blogs
register_activation_hook( __FILE__, 'go_on_activate_msdb' );

/*
 * Init
 */


/**
 * Registers Game On custom post types and taxonomies, then
 * updates the site's rewrite rules to mitigate cpt and 
 * permalink conflicts. flush_rewrite_rules() must always
 * be called AFTER custom post types and taxonomies are
 * registered.
 */
 //https://developer.wordpress.org/reference/functions/flush_rewrite_rules/
 
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'go_flush_rewrites' );

 
 
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
    flush_rewrite_rules();
    go_custom_rewrite();
}


/*
 * Admin Menu & Admin Bar
 */

// actions
//add_action( 'admin_menu', 'add_game_on_options' );
//add_action( 'admin_menu', 'go_clipboard' );
//add_action( 'admin_bar_init', 'go_messages_bar' );
add_action( 'admin_bar_init', 'go_admin_bar' );

/*
 * Admin & Login Page
 */

add_action( 'admin_init', 'go_tsk_actv_redirect' );
//add_action( 'admin_init', 'go_add_delete_post_hook' );
add_action( 'admin_head', 'go_stats_overlay' );
//add_action( 'admin_head', 'go_store_head' );
add_action( 'admin_notices', 'go_admin_head_notification' );
add_action( 'admin_enqueue_scripts', 'go_admin_scripts' );


add_action( 'admin_enqueue_scripts', 'go_admin_includes' );
add_action( 'admin_enqueue_scripts', 'go_admin_styles' );
add_action( 'admin_enqueue_scripts', 'go_acf_scripts' );
add_action( 'login_redirect', 'go_user_redirect', 10, 3 );

/*
 * Front-end
 */

// actions
add_action( 'wp_head', 'go_stats_overlay' );
add_action( 'wp_enqueue_scripts', 'go_scripts' );
add_action( 'wp_enqueue_scripts', 'go_styles' );
add_action( 'wp_enqueue_scripts', 'go_includes' );
add_action( 'wp_enqueue_scripts', 'go_acf_scripts' );
//add_action( 'wp_enqueue_scripts', 'go_scripts' );
//add_action( 'wp_head', 'go_frontend_lightbox_html' );

// filters
add_filter( 'get_comment_author', 'go_display_comment_author', 10, 3 );

/*
 * User Data
 */

add_action( 'delete_user', 'go_user_delete' );
add_action( 'user_register', 'go_user_registration' );
//add_action( 'show_user_profile', 'go_extra_profile_fields' );
//add_action( 'edit_user_profile', 'go_extra_profile_fields' );
//add_action( 'personal_options_update', 'go_save_extra_profile_fields' );
//add_action( 'edit_user_profile_update', 'go_save_extra_profile_fields' );
//add_action( 'wp_footer', 'go_update_totals_out_of_bounds', 21 );

/*
 * AJAX Hooks
 */

add_action( 'wp_ajax_go_deactivate_plugin', 'go_deactivate_plugin' );
add_action( 'wp_ajax_go_clone_post', 'go_clone_post' );
add_action( 'wp_ajax_go_clipboard_intable', 'go_clipboard_intable' );
add_action( 'wp_ajax_go_clipboard_intable_messages', 'go_clipboard_intable_messages' );
add_action( 'wp_ajax_go_clipboard_intable_activity', 'go_clipboard_intable_activity' );
add_action( 'wp_ajax_go_clipboard_save_filters', 'go_clipboard_save_filters' );
add_action( 'wp_ajax_go_user_option_add', 'go_user_option_add' );
//add_action( 'wp_ajax_go_test_point_update', 'go_test_point_update' );
add_action( 'wp_ajax_go_unlock_stage', 'go_unlock_stage' );
add_action( 'wp_ajax_go_task_change_stage', 'go_task_change_stage' );
//add_action( 'wp_ajax_go_task_abandon', 'go_task_abandon' );
add_action( 'wp_ajax_go_admin_bar_add', 'go_admin_bar_add' );
add_action( 'wp_ajax_go_admin_bar_stats', 'go_admin_bar_stats' );
add_action( 'wp_ajax_go_class_a_save', 'go_class_a_save' );
add_action( 'wp_ajax_go_class_b_save', 'go_class_b_save' );
add_action( 'wp_ajax_go_update_user_sc_data', 'go_update_user_sc_data' );
add_action( 'wp_ajax_go_focus_save', 'go_focus_save' );
add_action( 'wp_ajax_go_reset_levels', 'go_reset_levels' );
add_action( 'wp_ajax_go_save_levels', 'go_save_levels' );
add_action( 'wp_ajax_go_reset_data', 'go_reset_data' );
add_action( 'wp_ajax_go_stats_about', 'go_stats_about' );
add_action( 'wp_ajax_go_stats_task_list', 'go_stats_task_list' );
//add_action( 'wp_ajax_go_stats_move_stage', 'go_stats_move_stage' );
add_action( 'wp_ajax_go_stats_item_list', 'go_stats_item_list' );
//add_action( 'wp_ajax_go_stats_rewards_list', 'go_stats_rewards_list' );
add_action( 'wp_ajax_go_stats_activity_list', 'go_stats_activity_list' );
add_action( 'wp_ajax_go_stats_single_task_activity_list', 'go_stats_single_task_activity_list' );
//add_action( 'wp_ajax_go_stats_penalties_list', 'go_stats_penalties_list' );
add_action( 'wp_ajax_go_stats_badges_list', 'go_stats_badges_list' );
add_action( 'wp_ajax_go_stats_groups_list', 'go_stats_groups_list' );
//add_action( 'wp_ajax_go_stats_leaderboard_choices', 'go_stats_leaderboard_choices' );
add_action( 'wp_ajax_go_stats_leaderboard', 'go_stats_leaderboard' );
add_action( 'wp_ajax_go_stats_lite', 'go_stats_lite' );
add_action( 'wp_ajax_go_presets_reset', 'go_presets_reset' );
add_action( 'wp_ajax_go_presets_save', 'go_presets_save' );
add_action( 'wp_ajax_go_fix_levels', 'go_fix_levels' );
add_action( 'wp_ajax_listurl', 'listurl' );
add_action( 'wp_ajax_go_update_user_focuses', 'go_update_user_focuses' );
add_action( 'wp_ajax_go_get_all_terms', 'go_get_all_terms' );
add_action( 'wp_ajax_go_get_all_posts', 'go_get_all_posts' );
add_action( 'wp_ajax_go_update_task_order', 'go_update_task_order' );
add_action( 'wp_ajax_go_search_for_user', 'go_search_for_user' );
add_action( 'wp_ajax_go_admin_remove_notification', 'go_admin_remove_notification' );
add_action( 'wp_ajax_go_get_purchase_count', 'go_get_purchase_count' );
add_action( 'wp_ajax_nopriv_go_get_purchase_count', 'go_get_purchase_count' );
add_action( 'wp_ajax_go_buy_item', 'go_buy_item' );
add_action( 'wp_ajax_nopriv_go_buy_item', 'go_buy_item' );
add_action( 'wp_ajax_go_clipboard_add', 'go_clipboard_add' );
add_action( 'wp_ajax_go_fix_messages', 'go_fix_messages' );
add_action( 'wp_ajax_go_mark_read', 'go_mark_read' );
add_action( 'wp_ajax_go_the_lb_ajax', 'go_the_lb_ajax' );
add_action( 'wp_ajax_nopriv_go_the_lb_ajax', 'go_the_lb_ajax' );
add_action( 'wp_ajax_go_update_last_map', 'go_update_last_map' );
add_action( 'wp_ajax_nopriv_go_update_last_map', 'go_update_last_map' );
add_action( 'wp_ajax_check_if_top_term', 'go_check_if_top_term' );
add_action( 'wp_ajax_go_update_admin_view', 'go_update_admin_view' );
add_action( 'wp_ajax_go_upgade4', 'go_upgade4' );
add_action( 'wp_ajax_go_update_bonus_loot', 'go_update_bonus_loot' );
add_action( 'wp_ajax_go_create_admin_message', 'go_create_admin_message' );
add_action( 'wp_ajax_go_send_message', 'go_send_message' );
add_action( 'wp_ajax_go_blog_opener', 'go_blog_opener' );
add_action( 'wp_ajax_go_blog_submit', 'go_blog_submit' );
add_action( 'wp_ajax_go_admin_messages', 'go_admin_messages' );


add_action('wp_ajax_go_tasks_dataloader_ajax', 'go_tasks_dataloader_ajax');
add_action('wp_ajax_go_activity_dataloader_ajax', 'go_activity_dataloader_ajax');
//add_action('wp_ajax_nopriv_go_tasks_dataloader_ajax', 'fn_my_ajaxified_dataloader_ajax');

/**
 * Miscellaneous Filters
 */

add_filter( 'cron_schedules', 'go_weekly_schedule' );
//add_filter( 'attachment_fields_to_edit', 'go_badge_add_attachment', 2, 2 );

// mitigating compatibility issues with Jetpack plugin by Automatic
// (https://wordpress.org/plugins/jetpack/).
add_filter( 'jetpack_enable_open_graph', '__return_false' );




/**
 * Important Functions
 */

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
 * Check for new admin messages
 */
function go_admin_messages(){
    $user_id = get_current_user_id();
    check_ajax_referer( 'go_admin_messages');
    go_check_messages();
}

/**
 * Changes roles so subscribers can upload media
 */
function go_media_access() {
    $role = get_role( 'subscriber' );
    $role->add_cap( 'upload_files' );
}
register_activation_hook( __FILE__, 'go_media_access' );



function go_changeMceDefaults($in) {

    // customize the buttons
    $in['theme_advanced_buttons1'] = 'bold,italic,underline,bullist,numlist,hr,blockquote,link,unlink,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent';
    $in['theme_advanced_buttons2'] = 'formatselect,pastetext,pasteword,charmap,undo,redo';

    // Keep the "kitchen sink" open
    $in[ 'wordpress_adv_hidden' ] = FALSE;
    return $in;
}
add_filter( 'tiny_mce_before_init', 'go_changeMceDefaults' );


/*
 * Appends errors to the configured PHP error log.
 *
 * Use this function to easily output Game On errors.
 *
 * @since 3.0.0
 *
 * @param  string  $error The error message.
 * @param  string  $func  The name of the function which is calling go_error_log().
 * @param  string  $file  The name of the file in which go_error_log() is being called.
 * @param  boolean $trace Whether or not to output a stack trace.
 */
function go_error_log( $error = '', $func = __FUNCTION__, $file = __FILE__, $trace = false ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	if ( '' !== $error ) {
		$log = "Game On Error: {$error}. " .
			( ! empty( $func ) ? "from {$func}() " : '' ) .
			( ! empty( $file ) ? "in {$file}" : 'erring file not provided' );
		if ( true === $trace ) {
			$exception = new Exception;
			$log .= print_r( "\nTrace:\n" . $exception->getTraceAsString(), true );
		}
		error_log( $log );
	}
}

function go_deactivate_plugin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_deactivate_plugin_' . get_current_user_id() );

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = plugin_basename( __FILE__ );
	deactivate_plugins( $plugin );
	die();
}

function go_tsk_actv_activate() {
	add_option( 'go_tsk_actv_do_activation_redirect', true );
	update_option( 'go_display_admin_explanation', true );
}

function go_tsk_actv_redirect() {
	if ( get_option( 'go_tsk_actv_do_activation_redirect', false ) ) {
		delete_option( 'go_tsk_actv_do_activation_redirect' );
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_redirect( 'admin.php?page=go_options' );
		}
	}
}

function go_user_redirect( $redirect_to, $request, $user )
{
    //if (is_user_logged_in()) {
        //$redirect_on = get_option( 'options_go_landing_page_on_login', true );
     if (isset($user) && ($user instanceof WP_User)) {
            $redirect_url = get_option('options_go_landing_page_on_login', '');
            $default_map = get_option('options_go_locations_map_default', '');
            $user_id = $user->ID;
            if ($default_map !== '') {
                update_user_meta($user_id, 'go_last_map', $default_map);
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

//this is the activation notification
function go_admin_head_notification() {
	if ( get_option( 'go_display_admin_explanation' ) && current_user_can( 'manage_options' ) ) {
		$plugin_data = get_plugin_data( __FILE__, false, false );
		$plugin_version = $plugin_data['Version'];
		$nonce = wp_create_nonce( 'go_admin_remove_notification_' . get_current_user_id() );
        $url = get_site_url(null, 'wp-admin/admin.php?page=game-tools');
		echo "<div id='message' class='update-nag' style='font-size: 16px; padding-right: 50px;'>This is a fresh installation of Game On (version <a href='https://github.com/TheMacLab/game-on/releases/tag/v{$plugin_version}' target='_blank'>{$plugin_version}</a>).

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
					success: function( res ) {
						if ( 'success' === res ) {
							location.reload();
						}
					}
				} );
			}
		</script>";
	}
}

function go_admin_remove_notification() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_admin_remove_notification_' . get_current_user_id() );

	update_option( 'go_display_admin_explanation', false );

	die( 'success' );
}

function go_weekly_schedule( $schedules ) {
	$schedules['go_weekly'] = array(
		'interval' => 604800,
		'display' => __( 'Once Weekly' )
	);
	return $schedules;
}

/**
 * Determines if the string has a boolean value of true (case is ignored).
 *
 * This exists because `boolval( 'true' )` equals the boolean value of true, as does
 * `boolval( 'false' )`. Typecasting a string as a boolean (using `(boolean) $var`) doesn't work
 * either. That achieves the same undesired effect. This function isn't insanely helpful, but it
 * does save a few lines.
 *
 * @since 3.0.0
 *
 * @param  string $str The string to check for a boolean value of true.
 * @return boolean Returns true if the string is equal to 'true', otherwise it returns false.
 */
function go_is_true_str( $str ) {
	if ( ! empty( $str ) && 'string' === gettype( $str ) && 'true' === strtolower( $str ) ) {
		return true;
	} else {
		return false;
	}
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

/**
 * TOP MENU ITEMS
 * @param $items
 * @return string
 */
function go_new_nav_menu_items($items) {

    $homelink = '<li class="home go_top_menu_1"><a href="' . home_url( '/' ) . '">' . __('Home') . '</a></li>';

    $menu_link = $homelink . $items;


    $terms = get_terms( array(
        'taxonomy' 		=> 'task_menus',
        'hide_empty'	=> false,
        'parent'		=> 0,
    ) );
    //$menu_link = '<ul class="collapsibleList">';
    foreach ($terms as $term) {
        $term_id = $term->term_id;
        $child_terms = get_terms(array(
            'taxonomy' => 'task_menus',
            'hide_empty' => false,
            'parent' => $term_id,
        ));
        $args=array(
            'tax_query' => array(
                array(
                    'taxonomy' => 'task_menus',
                    'field' => 'term_id',
                    'terms' => $term_id,
                )
            ),
            'posts_per_page'   => -1,
            'orderby'          => 'meta_value_num',
            'order'            => 'ASC',

            'meta_key'         => 'go-location_top_order_item',
            'meta_value'       => '',
            'post_type'        => 'tasks',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'	   => '',
            'author_name'	   => '',
            'post_status'      => 'publish',
            'suppress_filters' => true

        );

        $go_tasks_objs = get_posts($args);

        if (!empty($go_tasks_objs)) {

            $term_name = $term->name;
            $term_link = get_term_link($term->term_id);
            //$menu_link = $menu_link . '<li><a href="' . $term_link . '">' . __($term_name) . '</a><ul>';
            $menu_link = $menu_link . '<li class="go_top_menu_1"><a href="#">' . __($term_name) . '</a><ul>';

            foreach ($child_terms as $child_term) {
                $term_id = $child_term->term_id;
                $args=array(
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'task_menus',
                            'field' => 'term_id',
                            'terms' => $term_id,
                        )
                    ),
                    'posts_per_page'   => -1,
                    'orderby'          => 'meta_value_num',
                    'order'            => 'ASC',

                    'meta_key'         => 'go-location_top_order_item',
                    'meta_value'       => '',
                    'post_type'        => 'tasks',
                    'post_mime_type'   => '',
                    'post_parent'      => '',
                    'author'	   => '',
                    'author_name'	   => '',
                    'post_status'      => 'publish',
                    'suppress_filters' => true

                );

                $go_tasks_objs = get_posts($args);

                 if (!empty($go_tasks_objs)) {

                     $term_name = $child_term->name;
                     $term_link = get_term_link($child_term->term_id);
                     //$menu_link = $menu_link . '<li class="go_top_menu_2"><a href="' . $term_link . '">' . __($term_name) . '</a>' ;
                     $menu_link = $menu_link . '<li class="go_top_menu_2"><a href="#">' . __($term_name) . '</a>' ;

                     //get the term id of this chain
                     $term_id = $child_term->term_id;
                     $args = array('tax_query' => array(array('taxonomy' => 'task_menus', 'field' => 'term_id', 'terms' => $term_id,)),'posts_per_page'   => -1, 'orderby' => 'meta_value_num', 'order' => 'ASC',

                         'meta_key' => 'go-location_menu_order_item', 'meta_value' => '', 'post_type' => 'tasks', 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true

                     );

                     $go_tasks_objs = get_posts($args);
                     if (!empty($go_tasks_objs)) {
                         $menu_link = $menu_link . '<ul>';
                         foreach ($go_tasks_objs as $go_tasks_obj) {
                             $task_link = $go_tasks_obj->guid;
                             $task_title = $go_tasks_obj->post_title;

                             $menu_link = $menu_link . '<li class="go_menu_task"><a href="' . $task_link . '">' . __($task_title) . '</a></li>';

                         }
                         $menu_link = $menu_link . '</ul>';
                     }
                     $menu_link = $menu_link . '</li>';
                 }
            }
            $menu_link = $menu_link . '</ul></li>';
        }
    }
    //$menu_link = $menu_link . '</ul>';
    return $menu_link;

}
add_filter( 'wp_nav_menu_go_top_menu_items', 'go_new_nav_menu_items' );

/**
 * Task Categories Widget
 * Modified from: http://www.wpbeginner.com/wp-tutorials/how-to-create-a-custom-wordpress-widget/
 */
// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );

}

/**
 * Added v4.0 Sort items that show on menu pages
 *Modified from: https://wordpress.stackexchange.com/questions/39817/sort-results-by-name-asc-order-on-archive-php
 * https://www.advancedcustomfields.com/resources/orde-posts-by-custom-fields/
 */
add_action( 'pre_get_posts', 'go_change_sort_order');
function go_change_sort_order($query){

    // do not modify queries in the admin
    if( is_admin() ) {

        return $query;

    }

	if ($query->is_tax('task_menus')){
        $query->set('orderby', 'meta_value_num');
        $query->set('posts_per_page', -1);
        $query->set('meta_key', 'go-location_top_order_item');
        $query->set('order', 'ASC');
	}

	if ($query->is_tax('task_categories')){
        $query->set('orderby', 'meta_value_num');
        $query->set('posts_per_page', -1);
        $query->set('meta_key', 'go-location_side_order_item');
        $query->set('order', 'ASC');
	}

	/*
    if ($query->is_tax('task_chains')){
        $query->set('orderby', 'meta_value_num');
        $query->set('posts_per_page', -1);
        $query->set('meta_key', 'go-location_map_order_item');
        $query->set('order', 'ASC');
    }
*/



    // return
    //return $query;


};

//bbPress visual editor
function bbp_enable_visual_editor( $args = array() ) {
    $args['tinymce'] = true;
    return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'bbp_enable_visual_editor' );


/**
 * @param $items
 * @return string
 * Modified from:
 * https://wordpress.stackexchange.com/questions/121309/how-do-i-programatically-insert-a-new-menu-item
 *
 */

// Check if the menu exists
$menu_name = 'go_top_menu';
$menu_exists = wp_get_nav_menu_object( $menu_name );

// If it doesn't exist, let's create it.
if( !$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);
}


$widget_toggle = get_option('options_go_locations_widget_toggle');
if($widget_toggle) {
    add_action('widgets_init', 'wpb_load_widget');
}

// Creating the widget
class wpb_widget extends WP_Widget {

    function __construct() {
        parent::__construct(

        // Base ID of your widget
            'wpb_widget',

            // Widget name will appear in UI
            __(get_option('options_go_locations_widget_name'), 'go_widget_domain'),

            // Widget description
            array( 'description' => __( 'Widget of Categories of Game On', 'go_widget_domain' ), )
        );
    }

    // Creating widget front-end
    public function widget( $args, $instance ) {
        //$title = apply_filters( 'widget_title', $instance['title'] );
        $title = get_option('options_go_locations_widget_title');

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        //echo __( 'Hello, World!', 'wpb_widget_domain' );
        $terms = get_terms( array(
            'taxonomy' 		=> 'task_categories',
            'hide_empty'	=> false,
            'parent'		=> 0,
        ) );
        $menu_link = '<ul class="collapsibleList">';
        foreach ($terms as $term) {
            $term_id = $term->term_id;
            $child_terms = get_terms(array(
                'taxonomy' => 'task_categories',
                'hide_empty' => false,
                'parent' => $term_id,
            ));
            $args=array(
                'tax_query' => array(
                    array(
                        'taxonomy' => 'task_categories',
                        'field' => 'term_id',
                        'terms' => $term_id,
                    )
                ),
                'posts_per_page'   => -1,
                'orderby'          => 'meta_value_num',
                'order'            => 'ASC',

                'meta_key'         => 'go-location_widget_order_item',
                'meta_value'       => '',
                'post_type'        => 'tasks',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'author'	   => '',
                'author_name'	   => '',
                'post_status'      => 'publish',
                'suppress_filters' => true

            );

            $go_tasks_objs = get_posts($args);

            if (!empty($go_tasks_objs)) {

                $term_name = $term->name;
                $term_link = get_term_link($term->term_id);
                //$menu_link = $menu_link . '<li><a href="' . $term_link . '">' . __($term_name) . '</a><ul>';
                $menu_link = $menu_link . '<li class="go_side_menu_1">' . __($term_name) . '<ul>';

                foreach ($child_terms as $child_term) {
                    $term_id = $child_term->term_id;
                    $args=array(
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'task_categories',
                                'field' => 'term_id',
                                'terms' => $term_id,
                            )
                        ),
                        'posts_per_page'   => -1,
                        'orderby'          => 'meta_value_num',
                        'order'            => 'ASC',
                        'posts_per_page'   => 25,
                        'meta_key'         => 'go-location_widget_order_item',
                        'meta_value'       => '',
                        'post_type'        => 'tasks',
                        'post_mime_type'   => '',
                        'post_parent'      => '',
                        'author'	   => '',
                        'author_name'	   => '',
                        'post_status'      => 'publish',
                        'suppress_filters' => true

                    );

                    $go_tasks_objs = get_posts($args);

                    if (!empty($go_tasks_objs)) {

                        $term_name = $child_term->name;

                        //toggle these next 3 lines on/off if you want links on the terms
                        //$term_link = get_term_link($child_term->term_id);
                        //$menu_link = $menu_link . '<li class="go_side_menu_2"><a href="' . $term_link . '">' . __($term_name) . '</a>';
                        $menu_link = $menu_link . '<li class="go_side_menu_2">' . __($term_name) ;

                        //get the term id of this chain
                        $term_id = $child_term->term_id;
                        $args = array('tax_query' => array(array('taxonomy' => 'task_categories', 'field' => 'term_id', 'terms' => $term_id,)), 'posts_per_page'   => -1, 'orderby' => 'meta_value_num', 'order' => 'ASC',

                            'meta_key' => 'go-location_widget_order_item', 'meta_value' => '', 'post_type' => 'tasks', 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true

                        );

                        $go_tasks_objs = get_posts($args);
                        if (!empty($go_tasks_objs)) {
                            $menu_link = $menu_link . '<ul>';
                            foreach ($go_tasks_objs as $go_tasks_obj) {
                                $task_link = $go_tasks_obj->guid;
                                $task_title = $go_tasks_obj->post_title;

                                $menu_link = $menu_link . '<li class="go_side_menu_task"><a href="' . $task_link . '">' . __($task_title) . '</a></li>';

                            }
                            $menu_link = $menu_link . '</ul>';
                        }
                        $menu_link = $menu_link . '</li>';
                    }
                }
                $menu_link = $menu_link . '</ul></li>';
            }
        }
        $menu_link = $menu_link . '</ul>';
        echo $menu_link;
        //echo $args['after_widget'];
    }

    // Widget Backend
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( get_option('options_go_locations_widget_title'), 'go_widget_domain' );
        }
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
} // Class go_widget ends here

/**
 * Plugin Name: Disable ACF on Frontend
 * Description: Provides a performance boost if ACF frontend functions aren't being used
 * Version:     1.0
 * Author:      Bill Erickson
 * Author URI:  http://www.billerickson.net
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */
/**
 * Disable ACF on Frontend
 *

function ea_disable_acf_on_frontend( $plugins ) {
	if( is_admin() )
		return $plugins;
	foreach( $plugins as $i => $plugin )
		if( 'advanced-custom-fields-pro/acf.php' == $plugin )
			unset( $plugins[$i] );
	return $plugins;
}
add_filter( 'option_active_plugins', 'ea_disable_acf_on_frontend' );
*/




?>
