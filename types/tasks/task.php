<?php
// Includes
include('task_shortcode.php'); // Task Shotcode
include('includes/task_insert.php'); // Task Inserter
include('tsk-admin-footer.php');
// Task custom post type
function register_cpt_task() {
    $labels = array( 
        'name' => _x( get_option('go_tasks_plural_name'), 'task' ),
        'singular_name' => _x( get_option('go_tasks_name'), 'task' ),
        'add_new' => _x( 'Add New '.get_option('go_tasks_name'), 'task' ),
        'add_new_item' => _x( 'Add New '.get_option('go_tasks_name'), 'task' ),
        'edit_item' => _x( 'Edit '.get_option('go_tasks_name'), 'task' ),
        'new_item' => _x( 'New '.get_option('go_tasks_name'), 'task' ),
        'view_item' => _x( 'View '.get_option('go_tasks_name'), 'task' ),
        'search_items' => _x( 'Search '.get_option('go_tasks_plural_name'), 'task' ),
        'not_found' => _x( 'No '.get_option('go_tasks_plural_name').' found', 'task' ),
        'not_found_in_trash' => _x( 'No '.get_option('go_tasks_plural_name').' found in Trash', 'task' ),
        'parent_item_colon' => _x( 'Parent '.get_option('go_tasks_name').':', 'task' ),
        'menu_name' => _x( get_option('go_tasks_plural_name'), 'task' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        'description' => get_option('go_tasks_plural_name'),
        'supports' => array( 'title','thumbnail', 'custom-fields', 'revisions', 'page-attributes' ),
        'taxonomies' => array( 'task_categories' ),
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
        'rewrite' => array( 'slug' => strtolower(get_option('go_tasks_plural_name')) ),
        'capability_type' => 'post'
    );

    register_post_type( 'tasks', $args );
}

add_action( 'init', 'register_cpt_task' );

add_action( 'init', 'register_taxonomy_task_categories' );

function register_taxonomy_task_categories() {

    $labels = array( 
        'name' => _x( get_option('go_tasks_name').' Categories', 'task_categories' ),
        'singular_name' => _x( get_option('go_tasks_name').' Category', 'task_categories' ),
        'search_items' => _x( 'Search '.get_option('go_tasks_name').' Categories', 'task_categories' ),
        'popular_items' => _x( 'Popular '.get_option('go_tasks_name').' Categories', 'task_categories' ),
        'all_items' => _x( 'All '.get_option('go_tasks_name').' Categories', 'task_categories' ),
        'parent_item' => _x( get_option('go_tasks_name').' Category Parent', 'task_categories' ),
        'parent_item_colon' => _x( 'Parent '.get_option('go_tasks_name').' Category:', 'task_categories' ),
        'edit_item' => _x( 'Edit '.get_option('go_tasks_name').' Category', 'task_categories' ),
        'update_item' => _x( 'Update '.get_option('go_tasks_name').' Category', 'task_categories' ),
        'add_new_item' => _x( 'Add New '.get_option('go_tasks_name').' Category', 'task_categories' ),
        'new_item_name' => _x( 'New '.get_option('go_tasks_name').' Category', 'task_categories' ),
        'separate_items_with_commas' => _x( 'Separate '.get_option('go_tasks_name').' categories with commas', 'task_categories' ),
        'add_or_remove_items' => _x( 'Add or remove '.get_option('go_tasks_name').' categories', 'task_categories' ),
        'choose_from_most_used' => _x( 'Choose from the most used '.get_option('go_tasks_name').' categories', 'task_categories' ),
        'menu_name' => _x( get_option('go_tasks_name').' Categories', 'task_categories' ),
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
}
?>