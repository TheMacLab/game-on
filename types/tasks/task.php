<?php

function go_register_task_tax_and_cpt() {
	// Register Badges
	$labels_badge = array(
		'name'                       => _x( go_return_options( 'go_badges_name' ), 'badges' ),
		'singular_name'              => _x( go_return_options( 'go_badges_name' ), 'badges' ),
		'menu_name'                  => _x( go_return_options( 'go_badges_name' ), 'badges' ),
		'all_items'                  => 'All Items',
		'parent_item'                => 'Parent Item',
		'parent_item_colon'          => 'Parent Item:',
		'new_item_name'              => 'New Item Name',
		'add_new_item'               => 'Add New Item',
		'edit_item'                  => 'Edit Item',
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
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_in_menu' => true,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'go_badges', array( '' ), $args_badge );
	
	
//DELETE PODS TAXONOMY ONCE REFACTOR IS DONE ON TASK SHORT CODE	
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
		'show_ui' => false,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_pods', array( 'tasks' ), $task_pods_args );
/////////END DELETE HERE	

	// Register Task Category Taxonomy
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
		'show_in_nav_menus' => false,
		'show_in_menu' => false,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_categories', array( 'tasks' ), $task_cat_args );

	// Register Task Focus-specailist
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
		'show_in_nav_menus' => false,
		'show_in_menu' => true,
		'show_ui' => true,
		'show_tagcloud' => false,
		'show_admin_column' => false,
		'hierarchical' => false,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_focus_categories', array( 'tasks' ), $focus_args );

	// Register Task sidemenu Taxonomy
	$task_menu_labels = array( 
		'name' => _x( go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'singular_name' => _x( go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'all_items' => _x( 'All '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'parent_item' => _x( go_return_options( 'go_tasks_name' ).' Menu Parent', 'task_menu' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_tasks_name' ).' Menu:', 'task_menu' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'update_item' => _x( 'Update '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'new_item_name' => _x( 'New '.go_return_options( 'go_tasks_name' ).' Menu', 'task_menu' ),
		'separate_items_with_commas' => _x( 'Separate '.go_return_options( 'go_tasks_name' ).' menus with commas', 'task_menu' ),
		'add_or_remove_items' => _x( 'Add or remove '.go_return_options( 'go_tasks_name' ).' menus', 'task_menu' ),
		'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options( 'go_tasks_name' ).' menus', 'task_menu' ),
		'menu_name' => _x( go_return_options( 'go_tasks_name' ).' Menu Items', 'task_menu' ),
	);
	$task_menu_args = array( 
		'labels' => $task_menu_labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'show_in_menu' => false,
		'show_ui' => true,
		'show_tagcloud' => false,
		'show_admin_column' => false,
		'hierarchical' => true,
		'rewrite' => true,
		'query_var' => true
	);
	register_taxonomy( 'task_menus', array( 'tasks' ), $task_menu_args );

	// Register Task chains Taxonomy
	$task_chains_labels = array(
		'name' => _x( go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'singular_name' => _x( go_return_options( 'go_tasks_name' ).' Chain', 'task_chains' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'all_items' => _x( 'All '.go_return_options( 'go_tasks_name' ).' Chains', 'task_chains' ),
		'parent_item' => _x('Map (Set as none to make this chain a map)', 'task_chains' ),
		'parent_item_colon' => _x( 'Map (Set as none to make this chain a map):', 'task_chains' ),
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
		'show_in_nav_menus' => false,
		'show_in_menu' => false,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => true,
		'hierarchical' => true,
		'rewrite' => false,
		'query_var' => true
	);
	register_taxonomy( 'task_chains', array( 'tasks' ), $task_chains_args );



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
		'taxonomies' => array( 'task_categories', 'task_focus_categories', 'task_chains', 'task_menus', 'go_badges' ),
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
	
}
add_action( 'init', 'go_register_task_tax_and_cpt', 0 );



/**
 * Retrieves the status of a task for a specific user.
 *
 * Task "status" values are stored in the `go`.`status` column. Statuses outside the range of [0,5]
 * are not used for tasks, so this function is for tasks ONLY.
 *
 * @since 3.0.0
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

/**
 * Retrieves repeat loop count of a task for a specific user.
 *
 * Task "count" values are stored in the `go`.`count` column. The `count` column is used by other GO
 * custom post types (which it should not be), so this function is for tasks ONLY.
 *
 * @since 3.0.0
 *
 * @global wpdb $wpdb The WordPress database class.
 *
 * @param int $task_id The task ID.
 * @param int $user_id Optional. The user ID.
 * @return int|null The number of fifth stage (repeat) iterations the user has finished. Null if
 *                  the query finds nothing.
 */
function go_task_get_repeat_count( $task_id, $user_id = null ) {
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

	$task_count = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT count 
			FROM {$go_table_name} 
			WHERE uid = %d AND post_id = %d",
			$user_id,
			$task_id
		)
	);

	if ( null !== $task_count && ! is_int( $task_count ) ) {
		$task_count = (int) $task_count;
	}

	return $task_count;
}

/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_action('restrict_manage_posts', 'go_filter_tasks_by_taxonomy');
function go_filter_tasks_by_taxonomy() {
	global $typenow;
	$post_type = 'tasks'; // change to your post type
	$taxonomy  = 'task_chains'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}
/**
 * Filter posts by taxonomy in admin
 * @author  Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_filter('parse_query', 'go_convert_task_id_to_term_in_query');
function go_convert_task_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'tasks'; // change to your post type
	$taxonomy  = 'task_chains'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

/**
 * Auto update slugs
 * @author  Mick McMurray
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */

/**
**turn this on in future release.  Perhaps only for terms and then fix so only some terms.
function go_update_slug( $data, $postarr ) {

        $data['post_name'] = wp_unique_post_slug( sanitize_title( $data['post_title'] ), $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent'] );


    return $data;
}
add_filter( 'wp_insert_post_data', 'go_update_slug', 99, 2 );



// define the wp_update_term_data callback 
function go_update_term_slug( $data, $term_id, $taxonomy, $args ) { 
	//echo '<script type="text/javascript">alert("'.print_r($args).'");</script>';
	$no_space_slug = sanitize_title($data['name']);
     $data['slug'] = wp_unique_term_slug( $no_space_slug , (object) $args );
    return $data; 
 
}; 
         
add_filter( 'wp_update_term_data', 'go_update_term_slug', 10, 4 );
*/
?>
