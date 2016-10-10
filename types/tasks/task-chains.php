<?php

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

	if ( null !== $tt_id && ! empty( $chain_order[ $tt_id ] ) ) {

		// determines the position of the final published task in the order array
		$valid_pos = -1;
		$end_pos = count( $chain_order[ $tt_id ] ) - 1;
		for ( $i = $end_pos; $i > 0; $i-- ) {
			$temp_id = $chain_order[ $tt_id ][ $i ];
			$temp_task = get_post( $temp_id );
			if ( ! empty( $temp_task ) && 'publish' === $temp_task->post_status ) {
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
	} elseif ( ! empty( $chain_order ) ) {

		foreach ( $chain_order as $order ) {

			// determines the position of the final published task in the order array
			$valid_pos = -1;
			$end_pos = count( $order ) - 1;
			for ( $i = $end_pos; $i > 0; $i-- ) {
				$temp_id = $order[ $i ];
				$temp_task = get_post( $temp_id );
				if ( ! empty( $temp_task ) && 'publish' === $temp_task->post_status ) {
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

	// the task that is having a term assigned to it is referred to as the "target", below
	$the_object = get_post( $object_id );
	$the_term = get_term_by( 'term_taxonomy_id', $tt_id );

	if ( ! empty( $the_object ) &&
			'tasks' === $the_object->post_type &&
			'task_chains' === $the_term->taxonomy ) {

		$stored_chain_order   = get_post_meta( $object_id, 'go_mta_chain_order', true );
		$new_chain_order      = $stored_chain_order;
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
	$terms_array = array_map( function ( $tt_id ) {
		$term = get_term_by( 'term_taxonomy_id', $tt_id );
		return $term;
	}, $tt_ids );

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
