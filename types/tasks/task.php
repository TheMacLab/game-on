<?php
// Includes
include('task_shortcode.php'); // Task Shotcode
include('tsk-admin-footer.php');
// Task custom post type
function register_cpt_task() {
    $labels = array( 
        'name' => _x( go_return_options('go_tasks_plural_name'), 'task' ),
        'singular_name' => _x( go_return_options('go_tasks_name'), 'task' ),
        'add_new' => _x( 'Add New '.go_return_options('go_tasks_name'), 'task' ),
        'add_new_item' => _x( 'Add New '.go_return_options('go_tasks_name'), 'task' ),
        'edit_item' => _x( 'Edit '.go_return_options('go_tasks_name'), 'task' ),
        'new_item' => _x( 'New '.go_return_options('go_tasks_name'), 'task' ),
        'view_item' => _x( 'View '.go_return_options('go_tasks_name'), 'task' ),
        'search_items' => _x( 'Search '.go_return_options('go_tasks_plural_name'), 'task' ),
        'not_found' => _x( 'No '.go_return_options('go_tasks_plural_name').' found', 'task' ),
        'not_found_in_trash' => _x( 'No '.go_return_options('go_tasks_plural_name').' found in Trash', 'task' ),
        'parent_item_colon' => _x( 'Parent '.go_return_options('go_tasks_name').':', 'task' ),
        'menu_name' => _x( go_return_options('go_tasks_plural_name'), 'task' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'description' => go_return_options('go_tasks_plural_name'),
        'supports' => array( 'title', 'publicize', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes', 'comments'),
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
	
    register_post_type( 'tasks', $args );
}

add_action( 'init', 'register_cpt_task' );

add_action( 'init', 'register_taxonomy_task_categories' );

function register_taxonomy_task_categories() {

    $labels = array( 
        'name' => _x( go_return_options('go_tasks_name').' Categories', 'task_categories' ),
        'singular_name' => _x( go_return_options('go_tasks_name').' Category', 'task_categories' ),
        'search_items' => _x( 'Search '.go_return_options('go_tasks_name').' Categories', 'task_categories' ),
        'popular_items' => _x( 'Popular '.go_return_options('go_tasks_name').' Categories', 'task_categories' ),
        'all_items' => _x( 'All '.go_return_options('go_tasks_name').' Categories', 'task_categories' ),
        'parent_item' => _x( go_return_options('go_tasks_name').' Category Parent', 'task_categories' ),
        'parent_item_colon' => _x( 'Parent '.go_return_options('go_tasks_name').' Category:', 'task_categories' ),
        'edit_item' => _x( 'Edit '.go_return_options('go_tasks_name').' Category', 'task_categories' ),
        'update_item' => _x( 'Update '.go_return_options('go_tasks_name').' Category', 'task_categories' ),
        'add_new_item' => _x( 'Add New '.go_return_options('go_tasks_name').' Category', 'task_categories' ),
        'new_item_name' => _x( 'New '.go_return_options('go_tasks_name').' Category', 'task_categories' ),
        'separate_items_with_commas' => _x( 'Separate '.go_return_options('go_tasks_name').' categories with commas', 'task_categories' ),
        'add_or_remove_items' => _x( 'Add or remove '.go_return_options('go_tasks_name').' categories', 'task_categories' ),
        'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options('go_tasks_name').' categories', 'task_categories' ),
        'menu_name' => _x( go_return_options('go_tasks_name').' Categories', 'task_categories' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,

        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'task_categories', array('tasks'), $args );
	
	$labels_focus = array( 
        'name' => _x( go_return_options('go_focus_name').' Categories', 'task_focus_categories' ),
        'singular_name' => _x(go_return_options('go_focus_name').' Category', 'task_focus_categories' ),
        'search_items' => _x( 'Search '.go_return_options('go_focus_name').' Categories', 'task_focus_categories' ),
        'popular_items' => _x( 'Popular '.go_return_options('go_focus_name').' Categories', 'task_focus_categories' ),
        'all_items' => _x( 'All '.go_return_options('go_focus_name').' Categories', 'task_focus_categories' ),
        'parent_item' => _x(go_return_options('go_focus_name').' Category Parent', 'task_focus_categories' ),
        'parent_item_colon' => _x( 'Parent '.go_return_options('go_focus_name').' Category:', 'task_focus_categories' ),
        'edit_item' => _x( 'Edit '.go_return_options('go_focus_name').' Category', 'task_focus_categories' ),
        'update_item' => _x( 'Update '.go_return_options('go_focus_name').' Category', 'task_focus_categories' ),
        'add_new_item' => _x( 'Add New '.go_return_options('go_focus_name').' Category', 'task_focus_categories' ),
        'new_item_name' => _x( 'New '.go_return_options('go_focus_name').' Category', 'task_focus_categories' ),
        'separate_items_with_commas' => _x( 'Separate '.go_return_options('go_focus_name').' categories with commas', 'task_focus_categories' ),
        'add_or_remove_items' => _x( 'Add or remove '.go_return_options('go_focus_name').' categories', 'task_focus_categories' ),
        'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options('go_focus_name').' categories', 'task_focus_categories' ),
        'menu_name' => _x( go_return_options('go_focus_name').' Categories', 'task_focus_categories' ),
    );
	$args_focus = array( 
        'labels' => $labels_focus,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );
	
	register_taxonomy('task_focus_categories', array('tasks'), $args_focus);
	
	$labels_task_chains = array( 
		'name' => _x( go_return_options('go_tasks_name').' Chains', 'task_chains' ),
        'singular_name' => _x( go_return_options('go_tasks_name').' Chain', 'task_chains' ),
        'search_items' => _x( 'Search '.go_return_options('go_tasks_name').' Chains', 'task_chains' ),
        'popular_items' => _x( 'Popular '.go_return_options('go_tasks_name').' Chains', 'task_chains' ),
        'all_items' => _x( 'All '.go_return_options('go_tasks_name').' Chains', 'task_chains' ),
        'parent_item' => _x( go_return_options('go_tasks_name').' Chain Parent', 'task_chains' ),
        'parent_item_colon' => _x( 'Parent '.go_return_options('go_tasks_name').' Chain:', 'task_chains' ),
        'edit_item' => _x( 'Edit '.go_return_options('go_tasks_name').' Chain', 'task_chains' ),
        'update_item' => _x( 'Update '.go_return_options('go_tasks_name').' Chain', 'task_chains' ),
        'add_new_item' => _x( 'Add New '.go_return_options('go_tasks_name').' Chain', 'task_chains' ),
        'new_item_name' => _x( 'New '.go_return_options('go_tasks_name').' Chain', 'task_chains' ),
        'separate_items_with_commas' => _x( 'Separate '.go_return_options('go_tasks_name').' chains with commas', 'task_chains' ),
        'add_or_remove_items' => _x( 'Add or remove '.go_return_options('go_tasks_name').' chains', 'task_chains' ),
        'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options('go_tasks_name').' chains', 'task_chains' ),
        'menu_name' => _x( go_return_options('go_tasks_name').' Chains', 'task_chains' ),
	);
	$args_task_chains = array(
		'labels' => $labels_task_chains,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
	);
	
	register_taxonomy('task_chains', array('tasks'), $args_task_chains);
}

?>