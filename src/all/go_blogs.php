<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/31/18
 * Time: 10:25 AM
 */


function go_custom_rewrite() {
    // we are telling wordpress that if somebody access yoursite.com/all-post/user/username
    // wordpress will do a request on this query var yoursite.com/index.php?query_type=user_blog&uname=username
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
// Then add those two functions on their appropriate hook and filter
add_action( 'init', 'go_custom_rewrite' );
add_filter( 'query_vars', 'go_custom_query' );

function go_template_loader($template){

    // get the custom query var we registered
    $query_var = get_query_var('query_type');

    // load the custom template if ?query_type=all_post is  found on wordpress url/request
    if( $query_var == 'user_blog' ){
        $directory = plugin_dir_path( __FILE__ ) . '/templates/go_user_blog_template.php';
        //return get_stylesheet_directory_uri() . 'go_user_blog.php';
        return $directory;

    }
    return $template;
}
add_filter('template_include', 'go_template_loader');

/**
 *
 */
function go_hidden_footer(){

    /**
     * Hidden mce so it can be initialized later
     */
    echo "<div id='go_hidden_mce' style='display: block;'>";
    $settings  = array(
        //'wpautop' =>false,
        //'tinymce'=> array( 'menubar'=> true, 'toolbar1' => 'undo,redo', 'toolbar2' => ''),
        //'tinymce'=>true,
        'textarea_name' => 'go_result',
        'media_buttons' => true,
        //'teeny' => true,
        'quicktags'=>false,
        'menubar' => true,
        'drag_drop_upload' => true
    );
    wp_editor( '', 'go_blog_post', $settings );

    echo "</div>";
    echo "<div id='go_hidden_mce_edit' style='display: block;'>";

    $settings2  = array(
        //'tinymce'=> array( 'menubar'=> true, 'toolbar1' => 'undo,redo', 'toolbar2' => ''),
        //'tinymce'=>true,
        //'wpautop' =>false,
        'textarea_name' => 'go_result',
        'media_buttons' => true,
        //'teeny' => true,
        'quicktags'=>false,
        'menubar' => true,
        'drag_drop_upload' => true
    );
    wp_editor( '', 'go_blog_post_edit', $settings2 );
    echo "</div>";
}
