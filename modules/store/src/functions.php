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

//https://stackoverflow.com/questions/25310665/wordpress-how-to-create-a-rewrite-rule-for-a-file-in-a-custom-plugin
add_action('init', 'go_store_page');
function go_store_page(){
    $store_name = get_option( 'options_go_store_store_link');
    //add_rewrite_rule( "store", 'index.php?query_type=user_blog&uname=$matches[1]', "top");
    add_rewrite_rule( $store_name, 'index.php?' . $store_name . '=true', "top");
}

/* Query Vars */
add_filter( 'query_vars', 'go_store_register_query_var' );
function go_store_register_query_var( $vars ) {
    $store_name = get_option( 'options_go_store_store_link');
    $vars[] = $store_name;
    return $vars;
}

/* Template Include */
add_filter('template_include', 'go_store_template_include', 1, 1);
function go_store_template_include($template)
{
    global $wp_query; //Load $wp_query object
    $store_name = get_option( 'options_go_store_store_link');

    $page_value = ( isset($wp_query->query_vars[$store_name]) ? $wp_query->query_vars[$store_name] : false ); //Check for query var "blah"

    if ($page_value && $page_value == "true") { //Verify "blah" exists and value is "true".
        return plugin_dir_path(__FILE__).'templates/go_store_template.php'; //Load your template or file
    }

    return $template; //Load normal template when $page_value != "true" as a fallback
}


function go_make_store_new() {

    echo "<div id='go_store_container' style='padding:10px 30px; margin: 30px 5%; background-color: white;'>";
    $store_title = get_option( 'options_go_store_title');
    echo "<h1 style='padding:0px 30px 30px 0px;'>{$store_title}</h1>";

    $html = get_option('go_store_html');
    echo $html;
    echo "</div>";
}
add_shortcode('go_make_store', 'go_make_store_new');



function go_register_store_tax_and_cpt() {
	
	/*
	 * Store Types Taxonomy
	 */
	$store_name = get_option( 'options_go_store_name' );
	$cat_labels = array(
		'name' => _x( $store_name.' Categories', 'store_types' ),
		'singular_name' => _x( $store_name.' Item Category', 'store_types' ),
		'search_items' =>  _x( 'Search '.$store_name.' Categories' , 'store_types'),
		'all_items' => _x( 'All '.$store_name.' Categories', 'store_types' ),
		'parent_item' => _x( $store_name.' Section (Set as none to make this a new store section)' , 'store_types'),
		'parent_item_colon' => _x( $store_name.' Section (Set as none to make this a new store section):' , 'store_types'),
		'edit_item' => _x( 'Edit '.$store_name.' Category' , 'store_types'),
		'update_item' => _x( 'Update '.$store_name.' Category' , 'store_types'),
		'add_new_item' => _x( 'Add New '.$store_name.' Category' , 'store_types'),
		'new_item_name' => _x( 'New '.$store_name.' Category' , 'store_types'),
	);
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
    register_taxonomy( 'store_types', array( 'go_store' ), $cat_args );


	/*
	 * Store Custom Post Type
	 */
	 
	$labels_cpt = array(
		'name' => _x( $store_name , 'store-types'),
		'menu_name' => _x( $store_name , 'store-types'),
		'singular_name' => _x( $store_name.' Item' , 'store-types'),
		'add_new' => _x( 'New '.$store_name.' Item' , 'store-types'),
		'add_new_item' => _x( 'New '.$store_name.' Item' , 'store-types'),
		'edit' => _x( 'Edit '.$store_name.' Items' , 'store-types'),
		'edit_item' => _x( 'Edit '.$store_name.' Item' , 'store-types'),
		'new_item' => _x( 'New '.$store_name.' Item' , 'store-types'),
		'view' => _x( 'View Items' , 'store-types'),
		'view_item' => _x( 'View '.$store_name.' Item' , 'store-types'),
		'search_items' => _x( 'Search '.$store_name.' Items' , 'store-types'),
		'not_found' => _x( 'No '.$store_name.' Items found' , 'store-types'),
		'not_found_in_trash' => _x( 'No '.$store_name.' Items found in Trash' , 'store-types'),
		'parent' => 'Parent Store Item',
		'name_admin_bar'        => _x( $store_name , 'store-types'),
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
		'description' => _x( $store_name , 'store-types'),
        'supports'              => array( 'title', 'comments' ),
		'taxonomies' => array(''),
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
add_action( 'init', 'go_register_store_tax_and_cpt', 0 );

/**
 * Update store on post save, delete or trash
 * @param  integer $post_id Current post ID
 * @return integer          Current post ID
 */
function go_update_store_post_save( $post_id ) {
    $post = get_post( $post_id );
    // Check for post type.
    if ( 'go_store' !== $post->post_type ) {
        return;
    }
    $html = go_make_store_html();

    update_option( 'go_store_html', $html );

    //delete task data transient
    $key = 'go_post_data_' . $post_id;
    delete_transient($key);
}

add_action( 'wp_trash_post', 'go_update_store_post_save' );
add_action( 'deleted_post', 'go_update_store_post_save' );
add_action( 'save_post', 'go_update_store_post_save');


/**
 * Update store on store term
 * @param  integer $post_id Current post ID
 * @return integer          Current post ID
 */
function go_update_store_term_save( $term_id ) {

    $html = go_make_store_html();

    update_option( 'go_store_html', $html );
}

add_action( "delete_store_types", 'go_update_store_term_save', 10, 4 );
add_action( "create_store_types", 'go_update_store_term_save', 10, 4 );
add_action( "edit_store_types", 'go_update_store_term_save', 10, 4 );


/* No Idea What This Does!
function go_new_item_permalink( $return, $post_id, $new_title, $new_slug ) {
	if ( strpos( $return, 'edit-slug' ) !== false ) {
		$return .= '<span id="edit-slug button button-small hide-if-no-js"><a href="javascript:void(0)" onclick = "document.getElementById(\'go_lightbox\' ).style.display=\'block\';document.getElementById(\'fade\' ).style.display=\'block\'" class="button button-small" >Insert </a></span>';
		return $return;
	}
}
add_filter( 'get_sample_permalink_html', 'go_new_item_permalink', 5, 4 );
*/


?>