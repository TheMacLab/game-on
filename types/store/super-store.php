<?php
/*
Module Name: Super Store
Description: Creates a store for CubeGold using a custom content type! Nifty Huh?
Author: Vincent Astolfi (vincentastolfi)
Contributing Author: Semar Yousif
Creation Date: 05/09/13
*/
// Includes

//include('includes/lightbox/backend-lightbox.php');

function go_register_store_tax_and_cpt() {
	
	/*
	 * Store Types Taxonomy
	 */
	$cat_labels = array(
		'name' => _x( get_option( 'options_go_store_name' ).' Categories', 'store_types' ),
		'singular_name' => _x( get_option( 'options_go_store_name' ).' Item Category', 'store_types' ),
		'search_items' =>  _x( 'Search '.get_option( 'options_go_store_name' ).' Categories' , 'store_types'),
		'all_items' => _x( 'All '.get_option( 'options_go_store_name' ).' Categories', 'store_types' ),
		'parent_item' => _x( get_option( 'options_go_store_name' ).' Section (Set as none to make this a new store section)' , 'store_types'),
		'parent_item_colon' => _x( get_option( 'options_go_store_name' ).' Section (Set as none to make this a new store section):' , 'store_types'),
		'edit_item' => _x( 'Edit '.get_option( 'options_go_store_name' ).' Category' , 'store_types'),
		'update_item' => _x( 'Update '.get_option( 'options_go_store_name' ).' Category' , 'store_types'),
		'add_new_item' => _x( 'Add New '.get_option( 'options_go_store_name' ).' Category' , 'store_types'),
		'new_item_name' => _x( 'New '.get_option( 'options_go_store_name' ).' Category' , 'store_types'),
	);
<<<<<<< HEAD
=======
    $cat_args = array(
        'labels' => $cat_labels,
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
    register_taxonomy( 'store_types', array( '' ), $cat_args );

>>>>>>> 13ea3212a91c646af9bdbddad271e0008c7a7dbe

	/*
	 * Store Custom Post Type
	 */
	 
	$labels_cpt = array(
		'name' => _x( get_option( 'options_go_store_name' ) , 'store-types'),
		'menu_name' => _x( get_option( 'options_go_store_name' ) , 'store-types'),
		'singular_name' => _x( get_option( 'options_go_store_name' ).' Item' , 'store-types'),
		'add_new' => _x( 'New '.get_option( 'options_go_store_name' ).' Item' , 'store-types'),
		'add_new_item' => _x( 'New '.get_option( 'options_go_store_name' ).' Item' , 'store-types'),
		'edit' => _x( 'Edit '.get_option( 'options_go_store_name' ).' Items' , 'store-types'),
		'edit_item' => _x( 'Edit '.get_option( 'options_go_store_name' ).' Items' , 'store-types'),
		'new_item' => _x( 'New '.get_option( 'options_go_store_name' ).' Item' , 'store-types'),
		'view' => _x( 'View Items' , 'store-types'),
		'view_item' => _x( 'View '.get_option( 'options_go_store_name' ).' Items' , 'store-types'),
		'search_items' => _x( 'Search '.get_option( 'options_go_store_name' ).' Items' , 'store-types'),
		'not_found' => _x( 'No '.get_option( 'options_go_store_name' ).' Items found' , 'store-types'),
		'not_found_in_trash' => _x( 'No '.get_option( 'options_go_store_name' ).' Items found in Trash' , 'store-types'),
		'parent' => 'Parent Store Item',
		'name_admin_bar'        => _x( get_option( 'options_go_store_name' ) , 'store-types'),
		'archives'              => 'Item Archives',
		'attributes'            => 'Item Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'update_item'           => 'Update Item',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
        'labels' => $labels_cpt,
		'hierarchical' => false,
		'description' => _x( get_option( 'options_go_store_name' ) , 'store-types'),
        'supports'              => array( 'title', 'comments' ),
		'taxonomies' => array('store_types'),
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
		'rewrite' => true ,
		'capability_type' => 'post'

	);
	register_post_type( 'go_store', $args );
}
<<<<<<< HEAD
add_action( 'init', 'go_register_store_tax_and_cpt', 0 );	
=======
add_action( 'init', 'go_register_store_tax_and_cpt', 0 );	 
	 
>>>>>>> 13ea3212a91c646af9bdbddad271e0008c7a7dbe

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