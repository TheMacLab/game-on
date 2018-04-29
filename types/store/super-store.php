<?php
/*
Module Name: Super Store
Description: Creates a store for CubeGold using a custom content type! Nifty Huh?
Author: Vincent Astolfi (vincentastolfi)
Contributing Author: Semar Yousif
Creation Date: 05/09/13
*/
// Includes

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
	 * Store Custom Post Type
	 */
	 
	$labels = array(
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
		'parent' => 'Parent Store Item',
		'name_admin_bar'        => __( get_option( 'go_store_name' ) ),
		'archives'              => 'Item Archives',
		'attributes'            => 'Item Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'update_item'           => 'Update Item',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
		'label'                 => __( get_option( 'go_store_name' ) ),
		'description'           => __( get_option( 'go_store_name' ) ),
		'labels'                => $labels,
		'supports' => array( 'title'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-cart',
		'show_in_nav_menus' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => array( 'slug' => 'storeitems' ),
		'capability_type' => 'post'

	);
	register_post_type( 'go_store', $args );
}
add_action( 'init', 'go_register_store_tax_and_cpt', 0 );	 
	 

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