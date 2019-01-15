<?php

/**
 *
 */
function go_register_task_tax_and_cpt() {

    // Register Task chains Taxonomy
    $task_chains_labels = array(
        'name' => _x(get_option( 'options_go_tasks_name_singular' ). ' Maps', 'task_chains' ),
        'singular_name' => _x(get_option( 'options_go_tasks_name_singular' ). ' Map', 'task_chains' ),
        'search_items' => _x('Search '. get_option( 'options_go_tasks_name_singular' ) . ' Maps', 'task_chains' ),
        'popular_items' => _x('Popular '. get_option( 'options_go_tasks_name_singular' ) . ' Maps', 'task_chains' ),
        'all_items' => _x('All '. get_option( 'options_go_tasks_name_singular' ) . ' Maps', 'task_chains' ),
        'parent_item' => 'Map (Set as none to create a new map)',
        'parent_item_colon' => 'Map (Set as none to create a new map):',
        'edit_item' => _x('Edit '. get_option( 'options_go_tasks_name_singular' ) . ' Maps', 'task_chains' ),
        'update_item' => _x('Update '. get_option( 'options_go_tasks_name_singular' ) . ' Maps', 'task_chains' ),
        'add_new_item' => _x('Add New '. get_option( 'options_go_tasks_name_singular' ) . ' Map/Map Section', 'task_chains' ),
        'new_item_name' => _x('Add New '. get_option( 'options_go_tasks_name_singular' ) . ' Map/Map Section', 'task_chains' ),
        'separate_items_with_commas' => _x('Separate '. get_option( 'options_go_tasks_name_singular' ) . ' Maps with commas', 'task_chains' ),
        'add_or_remove_items' => _x('Add of Remove '. get_option( 'options_go_tasks_name_singular' ) . ' Maps', 'task_chains' ),
        'choose_from_most_used' => _x('Choose from the most used '. get_option( 'options_go_tasks_name_singular' ) . ' Map', 'task_chains' ),
        'menu_name' => _x(get_option( 'options_go_tasks_name_singular' ). 'Maps', 'task_chains' ),
    );
    $task_chains_args = array(
        'labels' => $task_chains_labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => false,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'task_chains'
        ),
        'query_var' => true
    );
    register_taxonomy( 'task_chains', array( '' ), $task_chains_args );
    //register_taxonom  y_for_object_type( 'task_chains', 'tasks' );



	$badges_name_singular = get_option('options_go_badges_name_singular');
	$badges_name_plural = get_option('options_go_badges_name_plural');

	// Register Badges
	$labels_badge = array(
		'name'                       => _x( $badges_name_plural, 'badges' ),
		'singular_name'              => _x( $badges_name_singular, 'badges' ),
		'menu_name'                  => _x( $badges_name_singular, 'badges' ),
		'all_items'                  => 'All ' . _x( $badges_name_plural, 'badges' ),
		'parent_item'                => 'Parent ' . _x( $badges_name_singular, 'badges' ),
		'parent_item_colon'          => 'Parent Item:',
		'new_item_name'              => 'New ' . _x( $badges_name_singular, 'badges' ) . ' Name',
		'add_new_item'               => 'Add New ' . _x( $badges_name_singular, 'badges' ),
		'edit_item'                  => 'Edit ' . _x( $badges_name_singular, 'badges' ),
		'update_item'                => 'Update Item',
		'view_item'                  => 'View Item',
		'separate_items_with_commas' => 'Separate items with commas',
		'add_or_remove_items'        => 'Add or remove items',
		'choose_from_most_used'      => 'Choose from the most used',
		'popular_items'              => 'Popular Items',
		'search_items'               => 'Search Items',
		'not_found'                  => 'Not Found',
		'no_terms'                   => 'No items',
		'items_list'                 => 'Items list',
		'items_list_navigation'      => 'Items list navigation',
	);
	$args_badge = array(
		'labels'                     => $labels_badge,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_in_menu' 				 => false,
		'show_tagcloud'              => true,
        'rewrite' => array(
            'slug' => 'go_badges'
        ),
        'query_var' => true
	);
	register_taxonomy( 'go_badges', array( '' ), $args_badge );


// Register User Groups
    $focus_labels = array(
        'name' => _x( 'User Groups', 'user_go_groups' ),
        'singular_name' => _x( 'User Group', 'user_go_groups' ),
        'search_items' => _x( 'Search User Groups', 'user_go_groups' ),
        'popular_items' => _x( 'Popular User Groups', 'user_go_groups' ),
        'all_items' => _x( 'All User Groups', 'user_go_groups' ),
        'parent_item' => _x('User Group Parent', 'user_go_groups' ),
        'parent_item_colon' => _x( 'User Group Parent: ', 'user_go_groups' ),
        'edit_item' => _x( 'Edit User Groups', 'user_go_groups' ),
        'update_item' => _x( 'Update User Groups', 'user_go_groups' ),
        'add_new_item' => _x( 'Add New User Group', 'user_go_groups' ),
        'new_item_name' => _x( 'New User Group', 'user_go_groups' ),
        'separate_items_with_commas' => _x( 'Separate User Groups with commas', 'user_go_groups' ),
        'add_or_remove_items' => _x( 'Add or remove User Group ', 'user_go_groups' ),
        'choose_from_most_used' => _x( 'Choose from the most used User Groups', 'user_go_groups' ),
        'menu_name' => _x( 'User Groups', 'user_go_groups' ),
    );
    $focus_args = array(
        'labels' => $focus_labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => true,
        'rewrite' => true,
        'query_var' => true
    );
    register_taxonomy( 'user_go_groups', array( '' ), $focus_args );

    // Register User Groups
    $focus_labels = array(
        'name' => _x( 'Sections', 'user_go_sections' ),
        'singular_name' => _x( 'Section', 'user_go_sections' ),
        'search_items' => _x( 'Search Sections', 'user_go_sections' ),
        'popular_items' => _x( 'Popular User Section', 'user_go_sections' ),
        'all_items' => _x( 'All Sections', 'user_go_sections' ),
        'parent_item' => _x('Section Parent', 'user_go_sections' ),
        'parent_item_colon' => _x( 'Section Parent: ', 'user_go_sections' ),
        'edit_item' => _x( 'Edit Sections', 'user_go_sections' ),
        'update_item' => _x( 'Update Sections', 'user_go_sections' ),
        'add_new_item' => _x( 'Add New Section', 'user_go_sections' ),
        'new_item_name' => _x( 'New Section', 'user_go_sections' ),
        'separate_items_with_commas' => _x( 'Separate Section with commas', 'user_go_sections' ),
        'add_or_remove_items' => _x( 'Add or remove Section ', 'user_go_sections' ),
        'choose_from_most_used' => _x( 'Choose from the most used Sections', 'user_go_sections' ),
        'menu_name' => _x( 'Sections', 'user_go_sections' ),
    );
    $focus_args = array(
        'labels' => $focus_labels,
        'public' => true,
        'show_in_nav_menus' => false,
        'show_in_menu' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => false,
        'rewrite' => true,
        'query_var' => true
    );
    register_taxonomy( 'user_go_sections', array( '' ), $focus_args );

	/*
	 * Task Custom Post Type
	 */
	$tasks_name_singular = get_option( 'options_go_tasks_name_singular' );
    $tasks_name_plural = get_option( 'options_go_tasks_name_plural' );
	$labels_cpt = array( 
		'name' => _x( $tasks_name_plural, 'task' ),
		'singular_name' => _x( $tasks_name_singular, 'task' ),
		'add_new' => _x( 'Add New '.$tasks_name_singular, 'task' ),
		'add_new_item' => _x( 'Add New '.$tasks_name_singular, 'task' ),
		'edit_item' => _x( 'Edit '.$tasks_name_singular, 'task' ),
		'new_item' => _x( 'New '.$tasks_name_singular, 'task' ),
		'view_item' => _x( 'View '.$tasks_name_singular, 'task' ),
		'search_items' => _x( 'Search '.$tasks_name_plural, 'task' ),
		'not_found' => _x( 'No '.$tasks_name_plural.' found', 'task' ),
		'not_found_in_trash' => _x( 'No '.$tasks_name_singular.' found in Trash', 'task' ),
		'parent_item_colon' => _x( 'Parent '.$tasks_name_singular.':', 'task' ),
		'menu_name' => _x( $tasks_name_plural, 'task' )
	);
	$args_cpt = array(
		'labels' => $labels_cpt,
		'hierarchical' => false,
		'description' => $tasks_name_plural,
        'supports'              => array( 'title', 'comments', 'thumbnail', 'revisions' ),
		'taxonomies' => array(''),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-welcome-widgets-menus',
		'show_in_nav_menus' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true ,
		'capability_type' => 'post'
	);
	register_post_type( 'tasks', $args_cpt );

    /*
 * Task Custom Post Type
 */
    $labels_cpt = array(
        'name' => _x( $tasks_name_singular . ' Templates', 'tasks_templates' ),
        'singular_name' => _x( $tasks_name_singular . ' Template', 'tasks_templates' ),
        'add_new' => _x( 'Add New '.$tasks_name_singular . ' Template', 'tasks_templates' ),
        'add_new_item' => _x( 'Add New '.$tasks_name_singular . ' Template', 'tasks_templates' ),
        'edit_item' => _x( 'Edit '.$tasks_name_singular . ' Template', 'tasks_templates' ),
        'new_item' => _x( 'New '.$tasks_name_singular . ' Template', 'tasks_templates' ),
        'view_item' => _x( 'View '.$tasks_name_singular . ' Template', 'tasks_templates' ),
        'search_items' => _x( 'Search '.$tasks_name_singular . ' Templates', 'tasks_templates' ),
        'not_found' => _x( 'No '.$tasks_name_singular . ' Templates'.' found', 'tasks_templates' ),
        'not_found_in_trash' => _x( 'No '.$tasks_name_singular . ' Template' .' found in Trash', 'tasks_templates' ),
        'parent_item_colon' => _x( 'Parent '.$tasks_name_singular . ' Template' .':', 'tasks_templates' ),
        'menu_name' => _x( $tasks_name_singular  . ' Templates' , 'tasks_templates' )
    );
    $args_cpt = array(
        'labels' => $labels_cpt,
        'hierarchical' => false,
        'description' => $tasks_name_plural,
        'supports'              => array( 'title', 'comments', 'thumbnail' ),
        'taxonomies' => array(''),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => false,
		'show_in_admin_bar'     => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-welcome-widgets-menus',
        'show_in_nav_menus' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true ,
        'capability_type' => 'post'
    );
    register_post_type( 'tasks_templates', $args_cpt );
	
}
add_action( 'init', 'go_register_task_tax_and_cpt', 0 );


add_filter( 'template_include', 'go_tasks_template_function', 1 );
function go_tasks_template_function( $template_path ) {
    if ( get_post_type() == 'tasks' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array (  'index.php' ) )
                //$theme_file =	get_page_template()
            ) {
                $template_path = $theme_file;
                add_filter( 'the_content', 'go_tasks_filter_content' );
            }
        }
    }
    return $template_path;
}

function go_tasks_filter_content() {
    echo do_shortcode( '[go_task id="'.get_the_id().'"]' );
}



?>
