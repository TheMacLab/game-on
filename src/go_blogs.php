<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/31/18
 * Time: 10:25 AM
 */

if ( ! function_exists( 'go_blog_tags' ) ) {

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

}

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

function go_custom_rewrite() {
    // we are telling wordpress that if somebody access yoursite.com/all-post/user/username
    // wordpress will do a request on this query var yoursite.com/index.php?query_type=all_post&uname=username
    //flush_rewrite_rules();

    add_rewrite_rule( "^user/([^/]*)/page/(.*)/?", 'index.php?query_type=user_blog&uname=$matches[1]&paged=$matches[2]', "top");
    add_rewrite_rule( "^user/(.*)", 'index.php?query_type=user_blog&uname=$matches[1]', "top");
}

function go_custom_query( $vars ) {
    // we will register the two custom query var on wordpress rewrite rule
    $vars[] = 'query_type';
    $vars[] = 'uname';
    $vars[] = 'paged';
    return $vars;
}
// Then add those two functions on thier appropriate hook and filter
add_action( 'init', 'go_custom_rewrite' );
add_filter( 'query_vars', 'go_custom_query' );

function go_template_loader($template){

    // get the custom query var we registered
    $query_var = get_query_var('query_type');

    // load the custom template if ?query_type=all_post is  found on wordpress url/request
    if( $query_var == 'user_blog' ){
        $directory = plugin_dir_path( __FILE__ ) . 'blogs/go_user_blog.php';
        //return get_stylesheet_directory_uri() . 'go_user_blog.php';
        return $directory;

    }
    return $template;
}
add_filter('template_include', 'go_template_loader');

function go_blog_opener(){

    check_ajax_referer( 'go_blog_opener' );

    $blog_post_id = ( ! empty( $_POST['blog_post_id'] ) ? (int) $_POST['blog_post_id'] : 0 );

    if(!empty($blog_post_id)) {
        $post = get_post($blog_post_id, OBJECT, 'edit');
        $content = $post->post_content;
        $title = get_the_title($blog_post_id);
    }else{
        $content = '';
        $title = '';
    }
    echo "<div id='go_url_div'>";
    echo "<div>Title:<div><input style='width: 100%;' id='go_result_title' type='text' value ='{$title}'></div> </div>";
    $settings  = array(
        'textarea_name' => 'go_result',
        'media_buttons' => true,
    );
    wp_editor( $content, 'go_blog_post', $settings );
    echo "</div>";
    echo "<button id='go_blog_submit' blog_post_id =" .$blog_post_id. ">Submit</button>";
    ?>
    <script>

    jQuery( document ).ready( function() {
        jQuery("#go_blog_submit").one("click", function(e){
            go_blog_submit( this );
        });
    });

    </script>
<?php
}

function go_blog_submit(){

    check_ajax_referer( 'go_blog_submit' );
    $result = (!empty($_POST['result']) ? (string)$_POST['result'] : ''); // Contains the result from the check for understanding
    $result_title = (!empty($_POST['result_title']) ? (string)$_POST['result_title'] : '');// Contains the result from the check for understanding
    $user_id = get_current_user_id();
    $blog_post_id = (!empty($_POST['blog_post_id']) ? (string)$_POST['blog_post_id'] : '');
    $my_post = array(
        'ID'        => $blog_post_id,
        'post_type'     => 'go_blogs',
        'post_title'    => $result_title,
        'post_content'  => $result,
        'post_status'   => 'publish',
        'post_author'   => $user_id,


    );
    if (empty($blog_post_id)) {
        // Insert the post into the database
        wp_insert_post($my_post);
    }else{
        wp_update_post($my_post);
    }
}

/*function go_blog_posts_pages( $query ) {
    if ( $query->is_post_type_archive('go_blogs') && $query->is_main_query() ) {
        $query->set( 'posts_per_page', '5' );
    }
}
add_action( 'pre_get_posts', 'go_blog_posts_pages' );
*/
