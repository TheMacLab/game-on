<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:41 PM
 */


// Register Custom Taxonomy
function go_blog_tags() {

    $labels = array(
        'name'                       => _x( 'Task Tags', 'Taxonomy General Name', 'go' ),
        'singular_name'              => _x( 'Task Tag', 'Taxonomy Singular Name', 'go' ),
        'menu_name'                  => __( 'Task Tags', 'go' ),
        'all_items'                  => __( 'All Items', 'go' ),
        'parent_item'                => __( 'Parent Item', 'go' ),
        'parent_item_colon'          => __( 'Parent Item:', 'go' ),
        'new_item_name'              => __( 'New Item Name', 'go' ),
        'add_new_item'               => __( 'Add New Item', 'go' ),
        'edit_item'                  => __( 'Edit Item', 'go' ),
        'update_item'                => __( 'Update Item', 'go' ),
        'view_item'                  => __( 'View Item', 'go' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'go' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'go' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'go' ),
        'popular_items'              => __( 'Popular Items', 'go' ),
        'search_items'               => __( 'Search Items', 'go' ),
        'not_found'                  => __( 'Not Found', 'go' ),
        'no_terms'                   => __( 'No items', 'go' ),
        'items_list'                 => __( 'Items list', 'go' ),
        'items_list_navigation'      => __( 'Items list navigation', 'go' ),
    );
    $rewrite = array(
        'slug'                       => 'user_posts',
        'with_front'                 => true,
        'hierarchical'               => false,
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => false,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewrite,
    );
    register_taxonomy( 'go_blog_tags', array( 'go_blogs' ), $args );

}
add_action( 'init', 'go_blog_tags', 0 );


// Register Custom Post Type
function go_blogs() {

    $labels = array(
        'name'                  => _x( 'User Blog Posts', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'User Blog Post', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'User Blog Posts', 'text_domain' ),
        'name_admin_bar'        => __( 'User Blog Post', 'text_domain' ),
        'archives'              => __( 'Item Archives', 'text_domain' ),
        'attributes'            => __( 'Item Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
        'all_items'             => __( 'All Items', 'text_domain' ),
        'add_new_item'          => __( 'Add New Item', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Item', 'text_domain' ),
        'edit_item'             => __( 'Edit Item', 'text_domain' ),
        'update_item'           => __( 'Update Item', 'text_domain' ),
        'view_item'             => __( 'View Item', 'text_domain' ),
        'view_items'            => __( 'View Items', 'text_domain' ),
        'search_items'          => __( 'Search Item', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
        'items_list'            => __( 'Items list', 'text_domain' ),
        'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
    );
    $rewrite = array(
        'slug'                  => 'blogs',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'User Blog Post', 'text_domain' ),
        'description'           => __( 'User Blog Posts', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'author' ),
        'taxonomies'            => array( 'go_blog_tags' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        //'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'go_blogs', $args );

}
add_action( 'init', 'go_blogs', 0 );
