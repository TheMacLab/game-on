<?php

/**
 * Add Term Metadata
 *
 *
 * @since 3.5
 */

//https://section214.com/2016/01/adding-custom-meta-fields-to-taxonomies/
//https://developer.wordpress.org/reference/hooks/taxonomy_add_form_fields/
//https://stackoverflow.com/questions/7526374/how-to-save-a-checkbox-meta-box-in-wordpress
/*
function task_chains_add_meta_fields( $taxonomy ) {
    echo '<div class="form-field term-group>
			<label for="pod_toggle">';
	 _e( 'Pod', 'my-plugin' ); 
	echo '</label>
			<label><input type="checkbox" id="pod_toggle" name="pod_toggle" onclick="toggleBoxVisibility()" >This chain can be completed in any order.</label>
		</div>
		<!--This section is for the # to complete pod.  This will require locking task chains by order. Add the onclick script back to the checkbox. onclick="toggleBoxVisibility()"--!>
		<div id="pod_number_complete" class="form-field term-group">
			<label for="pod_done_num">';
	_e( 'Number Done to Complete Pod', 'my-plugin' );
	echo '</label>
			<input type="number" id="pod_done_num" class="number" min="1" max="" step="any" name="pod_done_num" value="1" placeholder="number">
			<script type="text/javascript">
				function toggleBoxVisibility() {
					if (document.getElementById("pod_toggle").checked == true) {
						document.getElementById("pod_number_complete").style.display = "block";
					} 
   					else {
        				document.getElementById("pod_number_complete").style.display = "none";
        			}
				};	
				jQuery(document).ready(toggleBoxVisibility());	
			</script>
			<p class="description">Number that must be complete to finish pod</p>
		</div>
	
		<div id="pod_achievement" class="form-field term-group">
			<label for="pod_achievement">';
	_e( get_option( 'go_badges_name' ).' #', 'my-plugin' );
	echo '</label>
			<input type="number" id="pod_achievement" class="number" min="1" max="" step="any" name="pod_achievement"  placeholder="optional: ID # of '.get_option( 'go_badges_name' ).'">
		<p class="description">Number of media file for achievement.</p></div>';
}
add_action( 'task_chains_add_form_fields', 'task_chains_add_meta_fields', 10, 2 );
*/


/*

function task_chains_edit_meta_fields( $term, $taxonomy ) {
    $my_field = get_term_meta( $term->term_id, 'my_field', true );
    $pod_toggle = get_term_meta( $term->term_id, 'pod_toggle', true );
    $pod_done_num = get_term_meta( $term->term_id, 'pod_done_num', true );
    $pod_achievement = get_term_meta( $term->term_id, 'pod_achievement', true );

	echo '<tr class="form-field term-group-wrap">
			<th scope="row">
			<label for="pod_toggle">';
	_e( 'Pod', 'my-plugin' );
	echo' </label>
			</th>
			<td>
				<input type="checkbox" id="pod_toggle"  onclick="toggleBoxVisibility()" name="pod_toggle" ';
	if ( $pod_toggle == true ) {
		echo 'checked="checked"'; 
	}
	echo '/>	 
		</td>
		</tr>
		<tr id="pod_number_complete" class="form-field term-group-wrap">
			<th scope="row">
			<label for="pod_done_num">';
	_e( 'Number Done to Complete Pod', 'my-plugin' ); 
	echo '</label>
			</th>
			<td>
				<input type="number" id="pod_done_num" class="number" min="1" max="" step="any" name="pod_done_num" value="';
	echo $pod_done_num; 
	echo '" placeholder="number">
 		</td>
					<script type="text/javascript">
				function toggleBoxVisibility() {
					if (document.getElementById("pod_toggle").checked == true) {
						document.getElementById("pod_number_complete").style.display = "table-row";
					} 
   					else {
        				document.getElementById("pod_number_complete").style.display = "none";
        			}
				};	
				jQuery(document).ready(toggleBoxVisibility());	
			</script>	
		</tr>
		<tr class="form-field term-group-wrap">
		<th scope="row">
			<label for="pod_achievement">';
	_e( 'Achievement #', 'my-plugin' ); 
	echo '</label>
		</th>
		<td>
			<input type="number" id="pod_achievement" class="number" min="1" max="" step="any" name="pod_achievement" value="';
	echo $pod_achievement;
	echo '" placeholder="number">
			</td>	
		</tr>';
}
add_action( 'task_chains_edit_form_fields', 'task_chains_edit_meta_fields', 10, 2 );
*/

/*

function task_chains_save_taxonomy_meta( $term_id, $tag_id ) {
    if( isset( $_POST['pod_done_num'] ) ) {
        update_term_meta( $term_id, 'pod_done_num', esc_attr( $_POST['pod_done_num'] ) );
    }
     if( isset( $_POST['pod_achievement'] ) ) {
        update_term_meta( $term_id, 'pod_achievement', esc_attr( $_POST['pod_achievement'] ) );
    }
    update_term_meta( $term_id, 'pod_toggle', esc_attr( $_POST['pod_toggle'] ) );
    
}
add_action( 'created_task_chains', 'task_chains_save_taxonomy_meta', 10, 2 );
add_action( 'edited_task_chains', 'task_chains_save_taxonomy_meta', 10, 2 );
*/



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
        	$content = $term->name;
            //$content = get_term_meta( $term_id, 'pod_achievement', true );
            break;
    }

    return $content;
}
add_filter( 'manage_task_chains_custom_column', 'task_chains_add_field_column_contents', 10, 3 );


//////////////END TERM META

/**
 * Retrieves the taxonomy ID (see returns) of the first chain associated with a task.
 *
 * Takes in a task ID (the post ID) and tries to find a chain associated with that task.
 *
 * @since 3.0.0
 *
 * @param int $task_id The post ID of the task in question.
 * @return int|null Returns the `term_taxonomy_id` property of the chain term object, if it exists;
 *                  otherwise, null is returned.
 */
 
/*
echo '<script language="javascript">';
echo 'alert("chains")';
echo '</script>';
*/

function go_task_chain_get_id_by_task( $task_id ) {
	if ( ! isset( $task_id ) || empty( $task_id ) ) {
		$task_id = get_the_id();
	}
	$task_chains = get_the_terms( $task_id, 'task_chains' );
	if ( ! empty( $task_chains ) ) {
		$task_chains_array = array_values( $task_chains );

		// this uses only the first chain in the list, if the task is included in more than one
		$task_chains_first = array_shift( $task_chains_array );
		$tt_id = $task_chains_first->term_taxonomy_id;

		return $tt_id;
	}

	return null;
}



/**
 * Returns the term name of a chain based on the passed chain term taxonomy ID.
 *
 * If no chain ID is passed, or the chain ID is invalid (null, negative, etc.), the function will
 * use the current task as a reference for determining the chain's name. Omitting the chain ID
 * should be avoided, if possible. For example, if `go_task_chain_get_id_by_task()` is being used to
 * retrieve the chain ID, and the chain name is also needed, pass in that value. This will prevent
 * unnecessary queries to the database.
 *
 * @since 3.0.0
 *
 * @param int $tt_id Optional. The term taxonomy ID of the chain in question.
 * @return string|null Returns the `name` property of the chain term object, if it exists;
 *                     otherwise, null is returned.
 */
function go_task_chain_get_name_by_id( $tt_id = null ) {
	global $post;
	if ( ! isset( $tt_id ) || empty( $tt_id ) ) {
		$task_id = $post->id;

		$task_chains = get_the_terms( $task_id, 'task_chains' );

		if ( ! empty( $task_chains ) ) {
			$task_chains_array = array_values( $task_chains );

			// this uses only the first chain in the list, if the task is included in more than one
			$task_chains_first = array_shift( $task_chains_array );
			$chain_name = $task_chains_first->name;

			return $chain_name;
		}
	} else {
		$the_chain = get_term_by( 'term_taxonomy_id', $tt_id, 'task_chains' );
		$chain_name = $the_chain->name;

		return $chain_name;
	}

	return null;
}

/**
 * Retrieves all the published tasks in a chain, using a chain ID.
 *
 * @since 3.0.0
 *
 * @param int     $tt_id        Contains the term taxonomy ID of the task chain to search.
 * @param boolean $publish_only Optional. Whether or not to retrieve only published tasks.
 * @param array   $exclude      Optional. An array of task IDs to exclude from the returned results.
 * @return array Returns the objects of all the tasks in the specified chain. Returns an empty
 *               array if the chain ID is invalid, or if no matching tasks are found.
 */
function go_task_chain_get_tasks( $tt_id = 0, $publish_only = false, $exclude = array() ) {
	if ( ! is_int( $tt_id ) ) {
		$tt_id = (int) $tt_id;
	}

	if ( empty( $tt_id ) ) {
		return array();
	}

	if ( 'boolean' !== gettype( $publish_only ) ) {
		$publish_only = false;
	}

	$args = array(
		'post_type' => 'tasks',
		'tax_query' => array(
			array(
				'taxonomy' => 'task_chains',
				'field' => 'term_taxonomy_id',
				'terms' => $tt_id,
				'include_children' => false,
			),
		),
		'order' => 'ASC',
		'posts_per_page' => '-1',
		'post__not_in' => $exclude,
	);

	if ( $publish_only ) {
		$args['post_status'] = 'publish';
	}

	$tasks = get_posts( $args );

	return $tasks;
}

/**
 * Determines whether or not a task is at the end of a chain.
 *
 * If the term taxonomy ID is provided, the provided task ID will be compared with that chain in the
 * task's meta data. Otherwise, the provided task ID will be compared with all the chains in the
 * task's meta data.
 *
 * @since 3.0.0
 *
 * @param int $task_id The task ID.
 * @param int $tt_id   Optional. The term taxonomy ID of the chain.
 * @return boolean True when the task is at the end of a chain (see description). False otherwise.
 */
function go_task_chain_is_final_task( $task_id, $tt_id = null ) {
	if ( empty( $task_id ) ) {
		$task_id = get_the_id();
	} else {
		$task_id = (int) $task_id;
	}

	// retrieves the chain order of the task
	$chain_order = get_post_meta( $task_id, 'go_mta_chain_order', true );
	
	//if post is only in one chain
	if ( null !== $tt_id && ! empty( $chain_order[ $tt_id ] ) ) {
	
		// determines the position of the final published task in the order array
		$valid_pos = -1;
		$end_pos = count( $chain_order[ $tt_id ] ) - 1;
		for ( $i = $end_pos; $i > 0; $i-- ) {
			$temp_id = $chain_order[ $tt_id ][ $i ];
			$temp_task = get_post( $temp_id );
			$optional_post = get_post_meta ($temp_id, 'go_mta_optional_task', true);
			
			if ( ! empty( $temp_task ) && 'publish' === $temp_task->post_status && $optional_post != 'on' ) {
				$valid_pos = $i;
				break;
			}
		}

		// the term taxonomy ID and task ID must be integers
		$tt_id = (int) $tt_id;
		$id_pos = array_search( $task_id, $chain_order[ $tt_id ] );
		if ( -1 === $valid_pos || $valid_pos === $id_pos ) {
			return true;
		}
	//if post is more than 1 chain
	} elseif ( ! empty( $chain_order ) && is_array( $chain_order ) ) {

		foreach ( $chain_order as $order ) {

			// determines the position of the final published task in the order array
			$valid_pos = -1;
			$end_pos = count( $order ) - 1;
			for ( $i = $end_pos; $i > 0; $i-- ) {
				$temp_id = $order[ $i ];
				$temp_task = get_post( $temp_id );
				$optional_post = get_post_meta ($temp_id, 'go_mta_optional_task', true);
				if ( ! empty( $temp_task ) && 'publish' === $temp_task->post_status && $optional_post != 'on') {
					$valid_pos = $i;
					break;
				}
			}

			$id_pos = array_search( $task_id, $order );
			if ( -1 === $valid_pos || $valid_pos === $id_pos ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Handles updating chain order meta data when a chain is added to a task.
 *
 * @since 3.0.0
 * @see go_task_chain_get_tasks()
 *
 * @param int $object_id The ID of the object.
 * @param int $tt_id     The term taxonomy ID.
 */
function go_task_chain_add_term_rel( $object_id, $tt_id ) {
	if ( (int) $object_id <= 0 ) {
		return;
	}

	// the task that is having a term assigned to it is referred to as the "target", below
	$the_object = get_post( $object_id );
	$the_term = get_term_by( 'term_taxonomy_id', $tt_id );

	if ( ! empty( $the_object ) &&
			'tasks' === $the_object->post_type &&
			'task_chains' === $the_term->taxonomy ) {

		$stored_chain_order   = get_post_meta( $object_id, 'go_mta_chain_order', true );
		$new_chain_order      = is_array( $stored_chain_order ) ? $stored_chain_order : array();
		$target_chain_updated = false;
		$tasks_in_chain       = go_task_chain_get_tasks( $tt_id );

		if ( ! empty( $tasks_in_chain ) ) {

			/**
			 * There are tasks in the chain.
			 */

			// loops through all tasks in the chain and append the target's ID to their meta
			// data arrays
			foreach ( $tasks_in_chain as $task_obj ) {
				$order = get_post_meta( $task_obj->ID, 'go_mta_chain_order', true );
				$order[ $tt_id ][] = $object_id;

				update_post_meta( $task_obj->ID, 'go_mta_chain_order', $order );

				if ( ! $target_chain_updated ) {
					$new_chain_order = $order;
					$target_chain_updated = true;
				}
			}
		} else {

			/**
			 * There are not any tasks associated with the chain.
			 */

			$new_chain_order[ $tt_id ] = array( $object_id );
		}

		update_post_meta( $object_id, 'go_mta_chain_order', $new_chain_order );
	}
}
add_action( 'add_term_relationship', 'go_task_chain_add_term_rel', 10, 2 );

/**
 * Gets a task chain's term object from its term taxonomy ID.
 *
 * @param  int $tt_id The term taxonomy ID.
 * @return false|WP_Term The taxonomy term. False is returned when the term doesn't exist.
 */
function go_task_chain_term_from_id( $tt_id ) {
	$term = get_term_by( 'term_taxonomy_id', $tt_id );
	return $term;
}

/**
 * Handles updating chain order meta data when one or more chains are removed from a task.
 *
 * @since 3.0.0
 * @see go_task_chain_get_tasks()
 *
 * @param int   $object_id The ID of the object.
 * @param array $tt_ids    The term taxonomy IDs.
 */
function go_task_chain_delete_term_rel( $object_id, $tt_ids ) {

	// the task that is having a term assigned to it is referred to as the "target", below
	$the_object = get_post( $object_id );

	// maps the term objects using the term IDs, using string for pre-5.3 PHP compatibility
	$terms_array = array_map( 'go_task_chain_term_from_id', $tt_ids );

	if ( ! empty( $the_object ) && 'tasks' === $the_object->post_type && ! empty( $terms_array ) ) {
		foreach ( $terms_array as $the_term ) {

			if ( 'task_chains' === $the_term->taxonomy ) {

				$tt_id              = $the_term->term_taxonomy_id;
				$all_object_terms   = get_the_terms( $object_id, 'task_chains' );
				$stored_chain_order = get_post_meta( $object_id, 'go_mta_chain_order', true );
				$new_chain_order    = $stored_chain_order;
				$tasks_in_chain     = go_task_chain_get_tasks( $tt_id );

				if ( count( $tasks_in_chain ) > 1 ) {

					/**
					 * There are other tasks in the chain.
					 */

					// loops through all tasks in the chain and removes the target's ID from their
					// meta data arrays
					foreach ( $tasks_in_chain as $task_obj ) {
						$order = get_post_meta( $task_obj->ID, 'go_mta_chain_order', true );

						if ( ! empty( $order[ $tt_id ] ) ) {

							$id_index = array_search( $object_id, $order[ $tt_id ] );
							if ( false !== $id_index ) {

								unset( $order[ $tt_id ][ $id_index ] );
								$order[ $tt_id ] = array_values( $order[ $tt_id ] );

								update_post_meta( $task_obj->ID, 'go_mta_chain_order', $order );
							}
						}
					}
				}

				if ( count( $all_object_terms ) > 1 ) {

					/**
					 * The target is in other chains.
					 */

					if ( isset( $stored_chain_order[ $tt_id ] ) ) {
						unset( $stored_chain_order[ $tt_id ] );

						update_post_meta( $object_id, 'go_mta_chain_order', $stored_chain_order );
					}
				} else {

					/**
					 * The target is not in any other chains.
					 */

					// deletes the target's meta data array
					delete_post_meta( $object_id, 'go_mta_chain_order' );
				}
			}
		}
	}
}
add_action( 'delete_term_relationships', 'go_task_chain_delete_term_rel', 10, 2 );



