<?php
/*
Module Name: Super Store
Description: Creates a store for CubeGold using a custom content type! Nifty Huh?
Author: Vincent Astolfi (vincentastolfi)
Contributing Author: Semar Yousif
Creation Date: 05/09/13
*/
// Includes
include( 'store-shortcode.php' );
include( 'includes/lightbox/backend-lightbox.php' );

function go_register_store_tax_and_cpt() {
	
	/*
	 * Store Types Taxonomy
	 */
	$labels = array(
		'name' => _x( get_option( 'go_store_name' ).' Categories', 'taxonomy general name' ),
		'singular_name' => _x( get_option( 'go_store_name' ).' Item Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search '.get_option( 'go_store_name' ).' Categories' ),
		'all_items' => __( 'All '.get_option( 'go_store_name' ).' Categories' ),
		'parent_item' => __( get_option( 'go_store_name' ).' Section (Set as none to make this a new store section)' ),
		'parent_item_colon' => __( get_option( 'go_store_name' ).' Section (Set as none to make this a new store section):' ),
		'edit_item' => __( 'Edit '.get_option( 'go_store_name' ).' Category' ), 
		'update_item' => __( 'Update '.get_option( 'go_store_name' ).' Category' ),
		'add_new_item' => __( 'Add New '.get_option( 'go_store_name' ).' Category' ),
		'new_item_name' => __( 'New '.get_option( 'go_store_name' ).' Category' ),
	);   
	register_taxonomy( 
		'store_types', 
		array( 'jobs' ), 
		array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'store-types', 'with_front' => false ),
		)
	);
	
	/*
	 * Store Focus Category Taxonomy
	 */
	$labels_focus = array( 
		'name' => _x( go_return_options( 'go_focus_name' ).' Categories', 'store_focus_categories' ),
		'singular_name' => _x( go_return_options( 'go_focus_name' ).' Category', 'store_focus_categories' ),
		'search_items' => _x( 'Search '.go_return_options( 'go_focus_name' ).' Categories', 'store_focus_categories' ),
		'popular_items' => _x( 'Popular '.go_return_options( 'go_focus_name' ).' Categories', 'store_focus_categories' ),
		'all_items' => _x( 'All '.go_return_options( 'go_focus_name' ).' Categories', 'store_focus_categories' ),
		'parent_item' => _x( go_return_options( 'go_focus_name' ).' Category Parent', 'store_focus_categories' ),
		'parent_item_colon' => _x( 'Parent '.go_return_options( 'go_focus_name' ).' Category:', 'store_focus_categories' ),
		'edit_item' => _x( 'Edit '.go_return_options( 'go_focus_name' ).' Category', 'store_focus_categories' ),
		'update_item' => _x( 'Update '.go_return_options( 'go_focus_name' ).' Category', 'store_focus_categories' ),
		'add_new_item' => _x( 'Add New '.go_return_options( 'go_focus_name' ).' Category', 'store_focus_categories' ),
		'new_item_name' => _x( 'New '.go_return_options( 'go_focus_name' ).' Category', 'store_focus_categories' ),
		'separate_items_with_commas' => _x( 'Separate '.go_return_options( 'go_focus_name' ).' categories with commas', 'store_focus_categories' ),
		'add_or_remove_items' => _x( 'Add or remove '.go_return_options( 'go_focus_name' ).' categories', 'store_focus_categories' ),
		'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options( 'go_focus_name' ).' categories', 'store_focus_categories' ),
		'menu_name' => _x( go_return_options( 'go_focus_name' ).' Categories', 'store_focus_categories' ),
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
	register_taxonomy( 'store_focus_categories', array( 'go_store' ), $args_focus );

	/*
	 * Store Custom Post Type
	 */
	register_post_type( 'go_store',
		array(
			'labels' => array(
				'name' => __( get_option( 'go_store_name' ) ),
				'menu_name' => __( get_option( 'go_store_name' ) ),
				'singular_name' => __( get_option( 'go_store_name' ).' Item' ),
				'add_new' => __( 'New '.get_option( 'go_store_name' ).' Item' ),
				'add_new_item' => __( 'New '.get_option( 'go_store_name' ).' Item' ),
				'edit' => __( 'Edit '.get_option( 'go_store_name' ).' Items' ),
				'edit_item' => __( 'Edit '.get_option( 'go_store_name' ).' Items' ),
				'new_item' => __( 'New '.get_option( 'go_store_name' ).' Item' ),
				'view' => __( 'View Items' ),
				'view_item' => __( 'View '.get_option( 'go_store_name' ).' Items' ),
				'search_items' => __( 'Search '.get_option( 'go_store_name' ).' Items' ),
				'not_found' => __( 'No '.get_option( 'go_store_name' ).' Items found' ),
				'not_found_in_trash' => __( 'No '.get_option( 'go_store_name' ).' Items found in Trash' ),
				'parent' => 'Parent Store Item'
			),
			'taxonomies' => array( 'store_types' ),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array( 'slug' => 'storeitems' ),
			'menu_icon' => 'dashicons-cart',
			'hierachical' => true,
			'menu_position' => 21,
			'supports' => array( 
				'title',
				'thumbnail',
				'excerpt',
				'page-attributes',
				'editor',
				'custom-fields',
				'revisions',
				'comments'
			)
		)
	);
}

function go_new_item_permalink( $return, $post_id, $new_title, $new_slug ) {
	if ( strpos( $return, 'edit-slug' ) !== false ) {
		$return .= '<span id="edit-slug button button-small hide-if-no-js"><a href="javascript:void(0)" onclick = "document.getElementById(\'go_lightbox\' ).style.display=\'block\';document.getElementById(\'fade\' ).style.display=\'block\'" class="button button-small" >Insert </a></span>';
		return $return;
	}
}
add_filter( 'get_sample_permalink_html', 'go_new_item_permalink', 5, 4 );

/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_action('restrict_manage_posts', 'go_filter_store_by_taxonomy');
function go_filter_store_by_taxonomy() {
	global $typenow;
	$post_type = 'go_store'; // change to your post type
	$taxonomy  = 'store_types'; // change to your taxonomy
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
add_filter('parse_query', 'go_convert_store_id_to_term_in_query');
function go_convert_store_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'go_store'; // change to your post type
	$taxonomy  = 'store_types'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}



?>