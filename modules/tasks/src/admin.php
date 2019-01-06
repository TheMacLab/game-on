<?php



//ADMIN STUFF
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
 *
 */
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


/**
 * @param $args
 * @param $taxonomy
 * @return mixed
 */
function go_limit_parents($args, $taxonomy ) {
    //if ( 'task_chains' != $taxonomy ) return $args; // no change
    $args['depth'] = '1';
    return $args;
}


function task_chains_add_field_columns( $columns ) {;
    $columns['pod_toggle'] = __( 'Pod', 'my-plugin' );
    $columns['pod_done_num'] = __( '# Needed', 'my-plugin' );
    $columns['pod_achievement'] = __( 'Achievements', 'my-plugin' );
    return $columns;
}

add_filter( 'manage_edit-task_chains_columns', 'task_chains_add_field_columns' );

function task_chains_add_field_column_contents( $content, $column_name, $term_id ) {
    switch( $column_name ) {
        case 'pod_toggle' :
            $content = get_term_meta( $term_id, 'pod_toggle', true );
            if ($content == true){
                $content = '&#10004;';
            }
            else {
                $content = '';}
            break;
        case 'pod_done_num' :
            $content = get_term_meta( $term_id, 'pod_toggle', true );
            $all = get_term_meta( $term_id, 'pod_all', true );
            if ($content == true){
                if ($all == true){
                    $content = 'all';
                }else {
                    $content = get_term_meta($term_id, 'pod_done_num', true);
                }
            }
            else{
                $content = '';
            }
            break;
        case 'pod_achievement' :
            $term_id = get_term_meta( $term_id, 'pod_achievement', true );
            $term = get_term( $term_id, 'go_badges' );
            //$term = (isset(get_term( $term_id, 'go_badges' ) ?  get_term( $term_id, 'go_badges' ) : null));

            if (!is_wp_error($term)) {
                $name = $term->name;
            }
            if(!empty($name)) {
                $content = $name;
            }
            else{
                $content = '';
            }

            break;
    }

    return $content;
}
add_filter( 'manage_task_chains_custom_column', 'task_chains_add_field_column_contents', 10, 3 );


