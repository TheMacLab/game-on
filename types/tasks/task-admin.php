<?php


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
            if ($content == true){
                $content = get_term_meta( $term_id, 'pod_done_num', true );
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


