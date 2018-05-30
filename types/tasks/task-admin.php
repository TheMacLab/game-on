<?php

/**
 * Returns an array of data to be localized in the task edit page.
 *
 * Returns an array of data that gets passed to `wp_localize_script()` in
 * `go_enqueue_admin_scripts_and_styles()` which is then provided to a script that is enqueued on
 * the task edit page for admins. See the `wp_localize_script()` call that this function is used in
 * to find the slug of the script that will be provided with the data below.
 *
 * @since 3.0.0
 *
 * @see go_task_chain_get_id_by_task(), go_task_chain_is_final_task()
 * @global WP_Post $post The WP_Post object of the current post.
 *
 * @return array Array of task data to be localized. Contains the following:
 *
 *     array(
 *         'stages' => array(
 *             'is_stage_three_active' => boolean, // whether or not the task has three stages (false)
 *             'is_stage_five_active'  => boolean, // whether or not the task has five stages (false)
 *         ),
 *         'task_chains' => array(                 // an array of `task_chains` taxonomy data
 *             'in_chain'         => boolean,      // whether or not the task is in a chain (false)
 *             'is_last_in_chain' => boolean,      // whether or not the task is in the final position
 *         ),                                      // of a chain (false)
 *         'task_id' => int|null,                  // the post id of the task (null)
 *     )
 */
function go_localize_task_data() {
	global $post;

	$task_id = $post->ID;
	$custom_data = get_post_custom( $task_id );
	$tt_id = go_task_chain_get_id_by_task( $task_id );
	$task_chains = get_the_terms( $task_id, 'task_chains' );

	$is_stage_three_active = ( 'on' === strtolower( get_post_meta( $task_id, 'go_mta_three_stage_switch', true ) ) ? true : false );
	$is_stage_five_active  = ( 'on' === strtolower( get_post_meta( $task_id, 'go_mta_five_stage_switch', true ) )  ? true : false );
	$in_chain = false;
	$is_last_in_chain = go_task_chain_is_final_task( $task_id );

	if ( empty( $task_id ) || $task_id < 0 ) {
		$task_id = null;
	}

	if ( ! empty( $task_chains ) ) {
		$in_chain = true;
	}

	return array(
		'stages' => array(
			'is_stage_three_active' => $is_stage_three_active,
			'is_stage_five_active'  => $is_stage_five_active,
		),
		'task_chains' => array(
			'in_chain'         => $in_chain,
			'is_last_in_chain' => $is_last_in_chain,
		),
		'task_id' => $task_id,
	);
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
            //$content = $term->name;
            //$content = get_term_meta( $term_id, 'pod_achievement', true );
            break;
    }

    return $content;
}
add_filter( 'manage_task_chains_custom_column', 'task_chains_add_field_column_contents', 10, 3 );


