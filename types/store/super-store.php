<?php
/*
Module Name: Super Store
Description: Creates a store for CubeGold using a custom content type! Nifty Huh?
Author: Vincent Astolfi (vincentastolfi)
Contributing Author: Semar Yousif
Creation Date: 05/09/13
*/
// Includes
include ("store-shortcode.php");
include ('includes/lightbox/backend-lightbox.php');
include ('store-admin-footer.php');
//////////////////////////////////////////////////////////////////////////////////////
/////////////////////          Store Taxonomy             ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
//Adds Store Texonomy
add_action('init' , 'go_store_taxonomy' );
function go_store_taxonomy()
    {
    $labels = array(
        'name' => _x( get_option('go_store_name').' Categories', 'taxonomy general name' ),
        'singular_name' => _x( get_option('go_store_name').' Item Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search '.get_option('go_store_name').' Categories' ),
        'all_items' => __( 'All '.get_option('go_store_name').' Categories' ),
        'parent_item' => __( 'Parent '.get_option('go_store_name').' Categories' ),
        'parent_item_colon' => __( 'Parent '.get_option('go_store_name').' Category:' ),
        'edit_item' => __( 'Edit '.get_option('go_store_name').' Category' ), 
        'update_item' => __( 'Update '.get_option('go_store_name').' Category' ),
        'add_new_item' => __( 'Add New '.get_option('go_store_name').' Category' ),
        'new_item_name' => __( 'New '.get_option('go_store_name').' Category' ),
    );    

  register_taxonomy('store_types',array('jobs'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'show_in_nav_menus' => true,
    'rewrite' => array('slug' => 'store-types', 'with_front' => false),
  ));
  
  $labels_focus = array( 
        'name' => _x( go_return_options('go_focus_name').' Categories', 'store_focus_categories' ),
        'singular_name' => _x(go_return_options('go_focus_name').' Category', 'store_focus_categories' ),
        'search_items' => _x( 'Search '.go_return_options('go_focus_name').' Categories', 'store_focus_categories' ),
        'popular_items' => _x( 'Popular '.go_return_options('go_focus_name').' Categories', 'store_focus_categories' ),
        'all_items' => _x( 'All '.go_return_options('go_focus_name').' Categories', 'store_focus_categories' ),
        'parent_item' => _x(go_return_options('go_focus_name').' Category Parent', 'store_focus_categories' ),
        'parent_item_colon' => _x( 'Parent '.go_return_options('go_focus_name').' Category:', 'store_focus_categories' ),
        'edit_item' => _x( 'Edit '.go_return_options('go_focus_name').' Category', 'store_focus_categories' ),
        'update_item' => _x( 'Update '.go_return_options('go_focus_name').' Category', 'store_focus_categories' ),
        'add_new_item' => _x( 'Add New '.go_return_options('go_focus_name').' Category', 'store_focus_categories' ),
        'new_item_name' => _x( 'New '.go_return_options('go_focus_name').' Category', 'store_focus_categories' ),
        'separate_items_with_commas' => _x( 'Separate '.go_return_options('go_focus_name').' categories with commas', 'store_focus_categories' ),
        'add_or_remove_items' => _x( 'Add or remove '.go_return_options('go_focus_name').' categories', 'store_focus_categories' ),
        'choose_from_most_used' => _x( 'Choose from the most used '.go_return_options('go_focus_name').' categories', 'store_focus_categories' ),
        'menu_name' => _x( go_return_options('go_focus_name').' Categories', 'store_focus_categories' ),
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
	
	register_taxonomy('store_focus_categories', array('go_store'), $args_focus);

}
//////////////////////////////////////////////////////////////////////////////////////
/////////////////////           Store Post Type           ////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
add_action( 'init', 'go_store_post_type' );
function go_store_post_type() {
	register_post_type( 'go_store',
		array(
			'labels' => array(
                'name' => __(get_option('go_store_name')),
                'menu_name' => __(get_option('go_store_name')),
                'singular_name' => __(get_option('go_store_name').' Item'),
                'add_new' => __('New '.get_option('go_store_name').' Item'),
                'add_new_item' => __('New '.get_option('go_store_name').' Item'),
                'edit' => __('Edit '.get_option('go_store_name').' Items'),
                'edit_item' => __('Edit '.get_option('go_store_name').' Items'),
                'new_item' => __('New '.get_option('go_store_name').' Item'),
                'view' => __('View Items'),
                'view_item' => __('View '.get_option('go_store_name').' Items'),
                'search_items' => __('Search '.get_option('go_store_name').' Items'),
                'not_found' => __('No '.get_option('go_store_name').' Items found'),
                'not_found_in_trash' => __('No '.get_option('go_store_name').' Items found in Trash'),
                'parent' => 'Parent Store Item'
			),
			'taxonomies' => array('store_types'),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'store'),
			'menu_icon' => plugins_url( '/images/little-ico.png' , __FILE__ ),  // Icon Path
			'hierachical' => true,
			'menu_position' => 21,
			'supports' => array( 'title', 'thumbnail', 'excerpt', 'page-attributes', 'editor', 'custom-fields', 'revisions', 'comments' )
			
		)
	);
}
// Default Content
$go_store_new_page = $_GET["go_store"]; // Gets t/f from permalink
if($go_store_new_page == true) { // if was linked to from the go_store content type
function go_store_editor_content( $content ) { // run this function (that accepts $content paramater)
	$go_store_id = $_GET["go_store_id"]; // define the go_store item's id as a variable
	$content = '[go_store id="'.$go_store_id.'"]'; // paste the id in a shortcode
	return $content; // return all of that
}
add_filter( 'default_content', 'go_store_editor_content' ); // filter the content area using wp's default_content filter
}

// Add New Post(of any content type) w/ Store Item Button
$item_id = $_GET["post"];
$go_post_type = get_post_type($item_id);
if ($go_post_type === 'go_store') {
function go_new_item_permalink( $arg, $post_id ){
	global $is_resetable;
	if( ereg('edit-slug', $arg) ){
		$is_resetable = true;
		$go_store_id = $_GET["post"];
		$arg .= '<span id="edit-slug button button-small hide-if-no-js"><a href="javascript:void(0)" onclick = "document.getElementById(\'go_lightbox\').style.display=\'block\';document.getElementById(\'fade\').style.display=\'block\'" class="button button-small" >Insert </a></span>';
	}
	return $arg;
} 
add_filter( 'get_sample_permalink_html', 'go_new_item_permalink',5,2 );
}
?>
