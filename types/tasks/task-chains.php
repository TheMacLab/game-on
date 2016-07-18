<?php

/**
 * Retrieves the taxonomy ID (see returns) of the first chain associated with a task.
 *
 * Takes in a task ID (the post ID) and tries to find a chain associated with that task.
 *
 * @since 2.6.1
 *
 * @param int $task_id The post ID of the task in question.
 * @return int|null Returns the `taxonomy_term_id` property of the chain term object, if it exists;
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
		$chain_id = $task_chains_first->term_taxonomy_id;

		return $chain_id;
	}

	return null;
}

/**
 * Returns the term name of a chain based on the passed chain term ID.
 *
 * If no chain ID is passed, or the chain ID is invalid (null, negative, etc.), the function will
 * use the current task as a reference for determining the chain's name. Omitting the chain ID
 * should be avoided, if possible. For example, if `go_task_chain_get_id_by_task()` is being used to
 * retrieve the chain ID, and the chain name is also needed, pass in that value. This will prevent
 * unnecessary queries to the database.
 *
 * @since 2.6.1
 *
 * @param int $chain_id Optional. The term ID of the chain in question.
 * @return string|null Returns the `name` property of the chain term object, if it exists;
 *                     otherwise, null is returned.
 */
function go_task_chain_get_name_by_id( $chain_id = null ) {
	global $post;
	if ( ! isset( $chain_id ) || empty( $chain_id ) ) {
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
		$the_chain = get_term_by( 'term_taxonomy_id', $chain_id, 'task_chains' );
		$chain_name = $the_chain->name;

		return $chain_name;
	}

	return null;
}

/**
 * Retrieves all the published tasks in a chain, using a chain ID.
 *
 * @since 2.6.1
 *
 * @param int   $chain_id Contains the term ID of the task chain to search.
 * @param array $exclude  Optional. An array of task IDs to exclude from the returned results.
 * @return array Returns the objects of all the tasks in the specified chain. Returns an empty
 *               array if the chain ID is invalid, or if no matching tasks are found.
 */
function go_task_chain_get_tasks( $chain_id = 0, $exclude = array() ) {
	if ( ! is_int( $chain_id ) ) {
		$chain_id = (int) $chain_id;
	}

	if ( empty( $chain_id ) ) {
		return array();
	}

	$tasks = get_posts(
		array(
			'post_type' => 'tasks',
			'post_status' => 'publish',
			'tax_query' => array(
				array(
					'taxonomy' => 'task_chains',
					'field' => 'id',
					'terms' => $chain_id,
					'include_children' => false,
				),
			),
			'order' => 'ASC',
			'posts_per_page' => '-1',
			'post__not_in' => $exclude,
		)
	);

	return $tasks;
}

/**
 * Determines if the task in question is in the final position of the task chain.
 *
 * Takes the task ID (the post ID) and determines if that task is the last one in the chain
 * that it is associated with. If a chain ID parameter is supplied and the task ID doesn't belong
 * to that chain, the function will return false. Likewise, if the chain ID parameter is NOT
 * supplied and the task ID is not associated with any existing chains, the function will return
 * false.
 *
 * @since 2.6.1
 *
 * @see go_task_chain_get_id_by_task()
 *
 * @param int $task_id  The post ID of the task in question.
 * @param int $chain_id Optional. The `taxonomy_term_id` property of the chain in question.
 * @return boolean 	true when the task is at the end of a chain (see description). false when the
 *                  task is not at the end of a chain, or if there is a mismatch, or if the chain
 *                  doesn't exist (see description).
 */
function go_task_chain_is_final_task( $task_id, $chain_id = null ) {
	if ( ! isset( $task_id ) ) {
		$task_id = get_the_id();
	} else {
		$task_id = (int) $task_id;
	}

	if ( ! isset( $chain_id ) || null === $chain_id ) {
		$chain_id = go_task_chain_get_id_by_task( $task_id );
	}

	if ( null === $chain_id ) {
		return false;
	} else {

		// gets all published tasks associated with the specified chain (using the `taxonomy_term_id`)
		$tasks_in_chain = go_task_chain_get_tasks( $chain_id );

		if ( is_array( $tasks_in_chain ) && ! empty( $tasks_in_chain ) ) {
			$last_task = $tasks_in_chain[ count( $tasks_in_chain ) - 1 ];
			$last_task_id = $last_task->ID;
			if ( $task_id === $last_task_id ) {
				return true;
			}
		}

		return false;
	}
}

/**
 * Takes care of deleting a task chain term from its tasks' meta data.
 *
 * Hooks into the `pre_delete_term` action, which gets fired before any object-term relationships
 * are deleted.
 *
 * The term ID of the task chain then gets removed from the `go_mta_chain_order` meta data array of
 * each task in the chain.
 *
 * @since 2.6.1
 *
 * @param int    $term_id  The term ID.
 * @param string $tax_name The taxonomy name.
 */
function go_task_chain_delete_term( $term_id, $tax_name ) {
	if ( 'task_chains' === $tax_name ) {
		$tasks_in_chain = go_task_chain_get_tasks( $term_id );

		if ( ! empty( $tasks_in_chain ) ) {
			foreach ( $tasks_in_chain as $task_obj ) {
				$chain_order = get_post_meta( $task_obj->ID, 'go_mta_chain_order', true );
				$updated = false;

				if ( ! empty( $chain_order ) && is_array( $chain_order ) ) {
					foreach ( $chain_order as $chain_term_id => $chain_order_array ) {
						if ( ! empty( $chain_order_array ) && is_array( $chain_order_array ) ) {
							unset( $chain_order[ $chain_term_id ] );
							$updated = true;
						}
					}

					if ( $updated ) {
						update_post_meta( $task_obj->ID, 'go_mta_chain_order', $chain_order );
					}
				}
			}
		}
	}
}
add_action( 'pre_delete_term', 'go_task_chain_delete_term', 10, 2 );
