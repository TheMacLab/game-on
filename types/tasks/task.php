<?php

include( 'task_shortcode.php' ); 
include( 'task-admin.php' );

function go_register_task_tax_and_cpt() {
	
	/*
	 * Task Category Taxonomy
	 */
	$task_cat_labels = array( 
		'name' => _x( go_return_options( 'go_tasks_name' ).' Categories', 'task_categories' ),
		'singular_name' => _x( go_return_options( 'go_tasks_name' ).' Category', 'task_categories' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_tasks_name' ).' Categories', 'task_categories' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_tasks_name' ).' Categories', 'task_categories' ),
		'all_items' => _x( 'All '.go_return_options( 'go_tasks_name' ).' Categories', 'task_categories' ),
		'parent_item' => _x( go_return_options( 'go_tasks_name' ).' Category Parent', 'task_categories' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_tasks_name' ).' Category:', 'task_categories' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_tasks_name' ).' Category', 'task_categories' ),
		'update_item' => _x( 'Update '.go_return_options( 'go_tasks_name' ).' Category', 'task_categories' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_tasks_name' ).' Category', 'task_categories' ),
		'new_item_name' => _x( 'New '.go_return_options( 'go_tasks_name' ).' Category', 'task_categories' ),
		'separate_items_with_commas' => _x( 'Separate '.go_return_options( 'go_tasks_name' ).' categories with commas', 'task_categories' ),
		'add_or_remove_items' => _x( 'Add or remove '.go_return_options( 'go_tasks_name' ).' categories', 'task_categories' ),
		'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options( 'go_tasks_name' ).' categories', 'task_categories' ),
		'menu_name' => _x( go_return_options( 'go_tasks_name' ).' Categories', 'task_categories' ),
	);
	$task_cat_args = array( 
		'labels' => $task_cat_labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_categories', array( 'tasks' ), $task_cat_args );
	
	/*
	 * Task Focus Categories Taxonomy
	 */
	$focus_labels = array( 
		'name' => _x( go_return_options( 'go_focus_name' ).' Categories', 'task_focus_categories' ),
		'singular_name' => _x( go_return_options( 'go_focus_name' ).' Category', 'task_focus_categories' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_focus_name' ).' Categories', 'task_focus_categories' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_focus_name' ).' Categories', 'task_focus_categories' ),
		'all_items' => _x( 'All '.go_return_options( 'go_focus_name' ).' Categories', 'task_focus_categories' ),
		'parent_item' => _x( go_return_options( 'go_focus_name' ).' Category Parent', 'task_focus_categories' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_focus_name' ).' Category:', 'task_focus_categories' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_focus_name' ).' Category', 'task_focus_categories' ),
		'update_item' => _x( 'Update '.go_return_options( 'go_focus_name' ).' Category', 'task_focus_categories' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_focus_name' ).' Category', 'task_focus_categories' ),
		'new_item_name' => _x( 'New '.go_return_options( 'go_focus_name' ).' Category', 'task_focus_categories' ),
		'separate_items_with_commas' => _x( 'Separate '.go_return_options( 'go_focus_name' ).' categories with commas', 'task_focus_categories' ),
		'add_or_remove_items' => _x( 'Add or remove '.go_return_options( 'go_focus_name' ).' categories', 'task_focus_categories' ),
		'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options( 'go_focus_name' ).' categories', 'task_focus_categories' ),
		'menu_name' => _x( go_return_options( 'go_focus_name' ).' Categories', 'task_focus_categories' ),
	);
	$focus_args = array(
		'labels' => $focus_labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_focus_categories', array( 'tasks' ), $focus_args );
	
	/*
	 * Task Chains Taxonomy
	 */
	$task_chains_labels = array(
		'name' => _x( go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'singular_name' => _x( go_return_options( 'go_tasks_name' ).' Chain', 'task_chains' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'all_items' => _x( 'All '.go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'parent_item' => _x( go_return_options( 'go_tasks_name' ).' Chain Parent', 'task_chains' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_tasks_name' ).' Chain:', 'task_chains' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_tasks_name' ).' Chain', 'task_chains' ),
		'update_item' => _x( 'Update '.go_return_options( 'go_tasks_name' ).' Chain', 'task_chains' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_tasks_name' ).' Chain', 'task_chains' ),
		'new_item_name' => _x( 'New '.go_return_options( 'go_tasks_name' ).' Chain', 'task_chains' ),
		'separate_items_with_commas' => _x( 'Separate '.go_return_options( 'go_tasks_name' ).' chains with commas', 'task_chains' ),
		'add_or_remove_items' => _x( 'Add or remove '.go_return_options( 'go_tasks_name' ).' chains', 'task_chains' ),
		'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options( 'go_tasks_name' ).' chains', 'task_chains' ),
		'menu_name' => _x( go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
	);
	$task_chains_args = array(
		'labels' => $task_chains_labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_chains', array( 'tasks' ), $task_chains_args );
	
	/*
	 * Task Pods Taxonomy
	 */
	$task_pods_labels = array(
		'name' => _x( go_return_options( 'go_tasks_name' ).' Pods', 'task_pods' ),
		'singular_name' => _x( go_return_options( 'go_tasks_name' ).' Pod', 'task_pods' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_tasks_name' ).' Pods', 'task_pods' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_tasks_name' ).' Pods', 'task_pods' ),
		'all_items' => _x( 'All '.go_return_options( 'go_tasks_name' ).' Pods', 'task_pods' ),
		'parent_item' => _x( go_return_options( 'go_tasks_name' ).' Pod Parent', 'task_pods' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_tasks_name' ).' Pod:', 'task_pods' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_tasks_name' ).' Pod', 'task_pods' ),
		'update_item' => _x( 'Update '.go_return_options( 'go_tasks_name' ).' Pod', 'task_pods' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_tasks_name' ).' Pod', 'task_pods' ),
		'new_item_name' => _x( 'New '.go_return_options( 'go_tasks_name' ).' Pod', 'task_pods' ),
		'separate_items_with_commas' => _x( 'Separate '.go_return_options( 'go_tasks_name' ).' pods with commas', 'task_pods' ),
		'add_or_remove_items' => _x( 'Add or remove '.go_return_options( 'go_tasks_name' ).' pods', 'task_pods' ),
		'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options( 'go_tasks_name' ).' pods', 'task_pods' ),
		'menu_name' => _x( go_return_options( 'go_tasks_name' ).' Pods', 'task_pods' ),
	);
	$task_pods_args = array(
		'labels' => $task_pods_labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_pods', array( 'tasks' ), $task_pods_args );

	/*
	 * Task Custom Post Type
	 */
	$labels_cpt = array( 
		'name' => _x( go_return_options( 'go_tasks_plural_name' ), 'task' ),
		'singular_name' => _x( go_return_options( 'go_tasks_name' ), 'task' ),
		'add_new' => _x( 'Add New '.go_return_options( 'go_tasks_name' ), 'task' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_tasks_name' ), 'task' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_tasks_name' ), 'task' ),
		'new_item' => _x( 'New '.go_return_options( 'go_tasks_name' ), 'task' ),
		'view_item' => _x( 'View '.go_return_options( 'go_tasks_name' ), 'task' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_tasks_plural_name' ), 'task' ),
		'not_found' => _x( 'No '.go_return_options( 'go_tasks_plural_name' ).' found', 'task' ),
		'not_found_in_trash' => _x( 'No '.go_return_options( 'go_tasks_plural_name' ).' found in Trash', 'task' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_tasks_name' ).':', 'task' ),
		'menu_name' => _x( go_return_options( 'go_tasks_plural_name' ), 'task' )
	);
	$args_cpt = array(
		'labels' => $labels_cpt,
		'hierarchical' => false,
		'description' => go_return_options( 'go_tasks_plural_name' ),
		'supports' => array( 'title', 'publicize', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes', 'comments' ),
		'taxonomies' => array( 'task_categories', 'post_tag', 'task_focus_categories' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => plugins_url( 'images/ico.png' , __FILE__ ),
		'show_in_nav_menus' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true ,
		'capability_type' => 'post'
	);
	register_post_type( 'tasks', $args_cpt );
}

/**
 * Retrieves the status of a task for a specific user.
 *
 * Task status values are stored in the `go` DB table.
 *
 * @since 2.6.1
 *
 * @global wpdb $wpdb The WordPress database class.
 *
 * @param int $task_id The task ID.
 * @param int $user_id Optional. The user ID.
 * @return int|null The status (0,1,2,3,4,5) of a task. Null if the query finds nothing.
 */
function go_task_get_status( $task_id, $user_id = null ) {
	global $wpdb;
	$go_table_name = $wpdb->prefix . 'go';

	if ( empty( $task_id ) ) {
		return null;
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	} else {
		$user_id = (int) $user_id;
	}

	$task_status = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status 
			FROM {$go_table_name} 
			WHERE uid = %d AND post_id = %d",
			$user_id,
			$task_id
		)
	);

	if ( null !== $task_status && ! is_int( $task_status ) ) {
		$task_status = (int) $task_status;
	}

	return $task_status;
}
?>
