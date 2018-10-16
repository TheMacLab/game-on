<?php

add_filter( 'template_include', 'go_tasks_template_function', 1 );
function go_tasks_template_function( $template_path ) {
    if ( get_post_type() == 'tasks' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array (  'index.php' ) )
                //$theme_file =	get_page_template()
            ) {
                $template_path = $theme_file;
                add_filter( 'the_content', 'go_tasks_filter_content' );
            }
        }
    }
    return $template_path;
}

function go_tasks_filter_content() {
    echo do_shortcode( '[go_task id="'.get_the_id().'"]' );
}

?>