<?php

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
    register_taxonomy( 'task_chains', array( 'tasks' ), $task_chains_args );
    register_taxonomy_for_object_type( 'task_chains', 'tasks' );

    // Register Task TOP MENU Taxonomy
    $task_menu_labels = array(
        'name' => _x('Top Menu', 'task_menu' ),
        'singular_name' => _x('Top Menu', 'task_menu' ),
        'search_items' => _x( 'Search Top Menus', 'task_menu' ),
        'popular_items' => _x( 'Popular Top Menus', 'task_menu' ),
        'all_items' => _x( 'All Top Menus', 'task_menu' ),
        'parent_item' => _x('Parent Menu', 'task_menu' ),
        'parent_item_colon' => _x( 'Parent Menu:', 'task_menu' ),
        'edit_item' => _x( 'Edit Top Menu', 'task_menu' ),
        'update_item' => _x( 'Update Top Menu', 'task_menu' ),
        'add_new_item' => _x( 'Add New Top Menu', 'task_menu' ),
        'new_item_name' => _x( 'New Top Menu', 'task_menu' ),
        'separate_items_with_commas' => _x( 'Separate top menus with commas', 'task_menu' ),
        'add_or_remove_items' => _x( 'Add or remove top menus', 'task_menu' ),
        'choose_from_most_used' => _x( 'Choose from the most used top menus', 'task_menu' ),
        'menu_name' => _x(' Top Menu', 'task_menu' ),
    );
    $task_menu_args = array(
        'labels' => $task_menu_labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => false,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => get_option('options_go_locations_top_menu')
        ),
        'query_var' => true
    );
    register_taxonomy( 'task_menus', array( 'maps_menus' ), $task_menu_args );

    // Register Task Side Menu Taxonomy
    $task_cat_labels = array(
        'name' => 'Side Menu',
        'singular_name' => 'Side Menu',
        'search_items' => 'Search Side Menus',
        'popular_items' => 'Popular Side Menus',
        'all_items' => 'All Side Menu Items',
        'parent_item' => ' Side Menu Parent',
        'parent_item_colon' => 'Side Menu:',
        'edit_item' => 'Edit Side Menu Section',
        'update_item' => 'Update Side Menu',
        'add_new_item' => 'Add New Side Menu',
        'new_item_name' => 'New Side Menu',
        'separate_items_with_commas' => 'Separate Side Menus with commas',
        'add_or_remove_items' => 'Add or remove Side Menu',
        'choose_from_most_used' => 'Choose from the most used Side Menus',
        'menu_name' => 'Side Menu',
    );
    $task_cat_args = array(
        'labels' => $task_cat_labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => false,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => get_option('options_go_locations_widget')
        ),
        'query_var' => true
    );
    register_taxonomy( 'task_categories', array( 'maps_menus' ), $task_cat_args );


	// Register Badges
	$labels_badge = array(
		'name'                       => _x( get_option('options_go_badges_name_plural'), 'badges' ),
		'singular_name'              => _x( get_option('options_go_badges_name_singular'), 'badges' ),
		'menu_name'                  => _x( get_option('options_go_badges_name_singular'), 'badges' ),
		'all_items'                  => 'All ' . _x( get_option('options_go_badges_name_plural'), 'badges' ),
		'parent_item'                => 'Parent ' . _x( get_option('options_go_badges_name_singular'), 'badges' ),
		'parent_item_colon'          => 'Parent Item:',
		'new_item_name'              => 'New ' . _x( get_option('options_go_badges_name_singular'), 'badges' ) . ' Name',
		'add_new_item'               => 'Add New ' . _x( get_option('options_go_badges_name_singular'), 'badges' ),
		'edit_item'                  => 'Edit ' . _x( get_option('options_go_badges_name_singular'), 'badges' ),
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
	$labels_cpt = array( 
		'name' => _x( get_option( 'options_go_tasks_name_plural' ), 'task' ),
		'singular_name' => _x( get_option( 'options_go_tasks_name_singular' ), 'task' ),
		'add_new' => _x( 'Add New '.get_option( 'options_go_tasks_name_singular' ), 'task' ),
		'add_new_item' => _x( 'Add New '.get_option( 'options_go_tasks_name_singular' ), 'task' ),
		'edit_item' => _x( 'Edit '.get_option( 'options_go_tasks_name_singular' ), 'task' ),
		'new_item' => _x( 'New '.get_option( 'options_go_tasks_name_singular' ), 'task' ),
		'view_item' => _x( 'View '.get_option( 'options_go_tasks_name_singular' ), 'task' ),
		'search_items' => _x( 'Search '.get_option( 'options_go_tasks_name_plural' ), 'task' ),
		'not_found' => _x( 'No '.get_option( 'options_go_tasks_name_plural' ).' found', 'task' ),
		'not_found_in_trash' => _x( 'No '.get_option( 'options_go_tasks_name_singular' ).' found in Trash', 'task' ),
		'parent_item_colon' => _x( 'Parent '.get_option( 'options_go_tasks_name_singular' ).':', 'task' ),
		'menu_name' => _x( get_option( 'options_go_tasks_name_plural' ), 'task' )
	);
	$args_cpt = array(
		'labels' => $labels_cpt,
		'hierarchical' => false,
		'description' => get_option( 'options_go_tasks_name_plural' ),
        'supports'              => array( 'title', 'comments', 'thumbnail' ),
		'taxonomies' => array('task_chains'),
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
function go_get_status( $task_id, $user_id = null, $task = null ) {
	global $wpdb;

    $key = 'go_get_status_' . $task_id;
    $data = wp_cache_get( $key );
    if ($data !== false){
        $task_status = $data;
    }else {
        if ($task != null){
            $task_status = $task['status'];
        }
        else{
            $go_task_table_name = "{$wpdb->prefix}go_tasks";

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
			FROM {$go_task_table_name} 
			WHERE uid = %d AND post_id = %d",
                    $user_id,
                    $task_id
                )
            );

            if ( null !== $task_status && ! is_int( $task_status ) ) {
                $task_status = (int) $task_status;
            }
        }
        wp_cache_set ($key, $task_status, 'go_single');
   }


	return $task_status;
}

function go_is_done( $task_id, $user_id = null ) {

    if ( empty( $task_id ) ) {
        return null;
    }

    $key = 'go_is_done_' . $task_id;
    $data = wp_cache_get( $key );
    if ($data !== false){
        $is_done = $data;
    }else {

        if (empty($user_id)) {
            $user_id = get_current_user_id();
        } else {
            $user_id = (int)$user_id;
        }

        //get status from cache
        $task_status = go_get_status($task_id, $user_id);

        //get data from transient
        $task_data = go_map_task_data($task_id);
        $task_custom_meta = $task_data[3];
        $task_stage_count = (isset($task_custom_meta['go_stages'][0]) ?  $task_custom_meta['go_stages'][0] : null);
        //$task_stage_count = get_post_meta($task_id, 'go_stages', true);

        if ($task_status == $task_stage_count) {
            $is_done = true;
        } else {
            $is_done = false;
        }
        wp_cache_set($key, $is_done, 'go_single');

    }
    return $is_done;
}

function go_master_unlocked($user_id, $post_id){
	global $wpdb;
	$key = 'go_master_unlocked_' . $post_id;
    $data = wp_cache_get( $key );
	if ($data !== false){
        $is_unlocked = $data;
    }else {

        $go_actions_table_name = "{$wpdb->prefix}go_actions";
        $is_unlocked = (string)$wpdb->get_var($wpdb->prepare("SELECT result 
				FROM {$go_actions_table_name} 
				WHERE uid = %d AND source_id = %d  AND check_type = %s
				ORDER BY id DESC LIMIT 1", $user_id, $post_id, 'unlock'));

        wp_cache_set( $key, $is_unlocked, 'go_single');
    }

    //$is_unlocked = ( $is_unlocked == 'password' ) ? true : false;
    return $is_unlocked;
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
function go_get_bonus_status( $task_id, $user_id = null ) {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go_tasks";

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
			"SELECT bonus_status
			FROM {$go_task_table_name} 
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
        if ( ! is_admin() ){
            return;
        }

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
 * Based on info from:
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */

function go_update_slug( $data, $postarr ) {
	$slug_toggle = get_option( 'options_go_slugs_toggle');
	if ($slug_toggle) {
        $post_type = $data['post_type'];
        if ($post_type == 'tasks' || $post_type == 'go_store') {
            $data['post_name'] = wp_unique_post_slug(sanitize_title($data['post_title']), $postarr['ID'], $data['post_status'], $data['post_type'], $data['post_parent']);
        }
        return $data;
    }
}
add_filter( 'wp_insert_post_data', 'go_update_slug', 99, 2 );

// define the wp_update_term_data callback 
function go_update_term_slug( $data, $term_id, $taxonomy, $args ) {
	$slug_toggle = get_option( 'options_go_slugs_toggle');
	if ($slug_toggle) {
        $no_space_slug = sanitize_title($data['name']);
        $data['slug'] = wp_unique_term_slug($no_space_slug, (object)$args);
        return $data;
    }
};
add_filter( 'wp_update_term_data', 'go_update_term_slug', 10, 4 );

function hide_all_slugs() {
	$slug_toggle = get_option( 'options_go_slugs_toggle');
	if ($slug_toggle) {
        global $post;
        $post_type = get_post_type( get_the_ID() );
        if ($post_type != 'post' && $post_type != 'page') {
            $hide_slugs = "<style type=\"text/css\"> #slugdiv, #edit-slug-box, .term-slug-wrap { display: none; }</style>";
            print($hide_slugs);
        }

    }
}
add_action( 'admin_head', 'hide_all_slugs'  );

function manage_task_chains_columns(){
    /**
     * TASK CHAINS EDIT COLUMNS AND FIELDS
     *
     */
//remove description column
    add_filter('manage_edit-task_chains_columns', function ( $columns ) {
        if( isset( $columns['description'] ) )
            unset( $columns['description'] );
        return $columns;
    });
//remove slug column
    add_filter('manage_edit-task_chains_columns', function ( $columns ) {
        if( isset( $columns['slug'] ) )
            unset( $columns['slug'] );
        return $columns;
    });

    /**
     * BADGES EDIT COLUMNS AND FIELDS
     *
     */
//remove description column
    add_filter('manage_edit-go_badges_columns', function ( $columns ) {
        if( isset( $columns['description'] ) )
            unset( $columns['description'] );
        return $columns;
    });
//remove slug column
    add_filter('manage_edit-go_badges_columns', function ( $columns ) {
        if( isset( $columns['slug'] ) )
            unset( $columns['slug'] );
        return $columns;
    });
//remove count column
    /*
    add_filter('manage_edit-go_badges_columns', function ( $columns ) {
        if( isset( $columns['posts'] ) )
            unset( $columns['posts'] );
        return $columns;
    });
    */


    /**
     * JOBS EDIT COLUMNS AND FIELDS
     *
     */

//remove description column
    add_filter('manage_edit-task_focus_categories_columns', function ( $columns ) {
        if( isset( $columns['description'] ) )
            unset( $columns['description'] );
        return $columns;
    });
//remove slug column
    add_filter('manage_edit-task_focus_categories_columns', function ( $columns ) {
        if( isset( $columns['slug'] ) )
            unset( $columns['slug'] );
        return $columns;
    });
//remove count column
    /*
    add_filter('manage_edit-task_focus_categories_columns', function ( $columns ) {
        if( isset( $columns['posts'] ) )
            unset( $columns['posts'] );
        return $columns;
    });
    */

    /**
     * SIDE MENU EDIT COLUMNS AND FIELDS
     *
     */
//remove description column
    add_filter('manage_edit-task_categories_columns', function ( $columns ) {
        if( isset( $columns['description'] ) )
            unset( $columns['description'] );
        return $columns;
    });
//remove slug column
    add_filter('manage_edit-task_categories_columns', function ( $columns ) {
        if( isset( $columns['slug'] ) )
            unset( $columns['slug'] );
        return $columns;
    });


    /**
     * TOP MENU EDIT COLUMNS AND FIELDS
     *
     */
//remove description column
    add_filter('manage_edit-task_menus_columns', function ( $columns ) {
        if( isset( $columns['description'] ) )
            unset( $columns['description'] );
        return $columns;
    });
//remove slug column
    add_filter('manage_edit-task_menus_columns', function ( $columns ) {
        if( isset( $columns['slug'] ) )
            unset( $columns['slug'] );
        return $columns;
    });


    /**
     * USER GROUPS EDIT COLUMNS AND FIELDS
     *
     */
//remove slug column
    add_filter('manage_edit-user_go_groups_columns', function ( $columns ) {
        if( isset( $columns['slug'] ) )
            unset( $columns['slug'] );
        return $columns;
    });
    //////Limits the dropdown to top level hierarchy.  Removes items that have a parent from the list.
    add_filter( 'taxonomy_parent_dropdown_args', 'go_limit_parents', 10, 2 );
}
add_action( 'admin_init', 'manage_task_chains_columns' );



function go_limit_parents( $args, $taxonomy ) {
    //if ( 'task_chains' != $taxonomy ) return $args; // no change
    $args['depth'] = '1';
    return $args;
}

?>
