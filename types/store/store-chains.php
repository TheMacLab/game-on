<?php


/**
 * Adds term metadata to "chain" store items together in columns and rows
 */


//Limits the dropdown to store rows.  Removes items that have a parent from the list.
add_filter( 'taxonomy_parent_dropdown_args', 'limit_parents_wpse_106165', 10, 2 );

function limit_parents_wpse_106165( $args, $taxonomy ) {

    if ( 'store_types' != $taxonomy ) return $args; // no change

    $args['depth'] = '1';
    return $args;
}

//remove description metabox
add_action( 'admin_footer-edit-tags.php', 'wpse_56570_remove_cat_tag_description' );

function wpse_56570_remove_cat_tag_description(){
    global $current_screen;
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $('#tag-description').parent().remove();
    });
    </script>
    <?php
}


/**
 * Remove default description column from category
 *
 */
add_filter('manage_edit-store_types_columns', function ( $columns ) {
    if( isset( $columns['description'] ) )
        unset( $columns['description'] );  
    return $columns;
});

add_filter('manage_edit-store_types_columns', function ( $columns ) {
    if( isset( $columns['slug'] ) )
        unset( $columns['slug'] );  
    return $columns;
});


//called in a localize function. Is this needed?
function go_store_types_get_id_by_task( $task_id ) {
	if ( ! isset( $task_id ) || empty( $task_id ) ) {
		$task_id = get_the_id();
	}
	$task_chains = get_the_terms( $task_id, 'store_types' );
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
function go_store_types_get_items( $tt_id = 0, $publish_only = false, $exclude = array() ) {
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
		'post_type' => 'go_store',
		'tax_query' => array(
			array(
				'taxonomy' => 'store_types',
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
 * Handles updating chain order meta data when a chain is added to a task.
 *
 * @since 3.0.0
 * @see go_task_chain_get_tasks()
 *
 * @param int $object_id The ID of the object.
 * @param int $tt_id     The term taxonomy ID.
 */
function go_store_types_add_term_rel( $object_id, $tt_id ) {
	if ( (int) $object_id <= 0 ) {
		return;
	}

	// the task that is having a term assigned to it is referred to as the "target", below
	$the_object = get_post( $object_id );
	$the_term = get_term_by( 'term_taxonomy_id', $tt_id );

	if ( ! empty( $the_object ) &&
			'go_store' === $the_object->post_type &&
			'store_types' === $the_term->taxonomy ) {

		$stored_chain_order   = get_post_meta( $object_id, 'go_mta_store_order', true );
		$new_chain_order      = is_array( $stored_chain_order ) ? $stored_chain_order : array();
		$target_chain_updated = false;
		$tasks_in_chain       = go_store_types_get_items( $tt_id );

		if ( ! empty( $tasks_in_chain ) ) {
			/**
			 * There are tasks in the chain.
			 */

			// loops through all tasks in the chain and append the target's ID to their meta
			// data arrays
			foreach ( $tasks_in_chain as $task_obj ) {
				$order = get_post_meta( $task_obj->ID, 'go_mta_store_order', true );
				$order[ $tt_id ][] = $object_id;

				update_post_meta( $task_obj->ID, 'go_mta_store_order', $order );

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

		update_post_meta( $object_id, 'go_mta_store_order', $new_chain_order );
	}
}
//add_action( 'add_term_relationship', 'go_task_chain_add_term_rel', 10, 2 );
add_action( 'add_term_relationship', 'go_store_types_add_term_rel', 10, 2 );

/**
 * Gets a task chain's term object from its term taxonomy ID.
 *
 * @param  int $tt_id The term taxonomy ID.
 * @return false|WP_Term The taxonomy term. False is returned when the term doesn't exist.
 */
function go_store_types_term_from_id( $tt_id ) {
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
function go_store_types_delete_term_rel( $object_id, $tt_ids ) {

	// the task that is having a term assigned to it is referred to as the "target", below
	$the_object = get_post( $object_id );

	// maps the term objects using the term IDs, using string for pre-5.3 PHP compatibility
	$terms_array = array_map( 'go_store_types_term_from_id', $tt_ids );

	if ( ! empty( $the_object ) && 'go_store' === $the_object->post_type && ! empty( $terms_array ) ) {
		foreach ( $terms_array as $the_term ) {

			if ( 'store_types' === $the_term->taxonomy ) {

				$tt_id              = $the_term->term_taxonomy_id;
				$all_object_terms   = get_the_terms( $object_id, 'store_types' );
				$stored_chain_order = get_post_meta( $object_id, 'go_mta_store_order', true );
				$new_chain_order    = $stored_chain_order;
				$tasks_in_chain     = go_store_types_get_items( $tt_id );

				if ( ! empty( $tasks_in_chain ) ) {

					/**
					 * There are other tasks in the chain.
					 */

					// loops through all tasks in the chain and removes the target's ID from their
					// meta data arrays
					foreach ( $tasks_in_chain as $task_obj ) {
						$order = get_post_meta( $task_obj->ID, 'go_mta_store_order', true );

						if ( ! empty( $order[ $tt_id ] ) ) {

							$id_index = array_search( $object_id, $order[ $tt_id ] );
							if ( false !== $id_index ) {

								unset( $order[ $tt_id ][ $id_index ] );
								$order[ $tt_id ] = array_values( $order[ $tt_id ] );

								update_post_meta( $task_obj->ID, 'go_mta_store_order', $order );
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

						update_post_meta( $object_id, 'go_mta_store_order', $stored_chain_order );
					}
				} else {

					/**
					 * The target is not in any other chains.
					 */

					// deletes the target's meta data array
					delete_post_meta( $object_id, 'go_mta_store_order' );
				}
			}
		}
	}
}
//add_action( 'delete_term_relationships', 'go_store_types_delete_term_rel', 10, 2 );
add_action( 'delete_term_relationships', 'go_store_types_delete_term_rel', 10, 2 );




