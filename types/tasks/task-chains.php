<?php

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
 * Get achievements associated with the map of a particular post
 * @param $post_id
 * @param $user_id
 * @param $custom_fields
 * @return array
 */
function go_badges_task_chains ($post_id, $user_id, $custom_fields ) {
//Get the chain that this task is in
    $chain_id = (isset($custom_fields['go-location_map_loc'][0]) ?  $custom_fields['go-location_map_loc'][0] : null);

    //if it is in a chain, check if it is done and add badge to array
    if (!empty($chain_id) && $chain_id != null) {
        $badges = array();
        //Get the badge assigned
        $badge= get_term_meta($chain_id, "pod_achievement", true);

        $is_chain_done = null;
        //if chain/pod has badge is on it
        if(!empty($badge) && $badge != null){
            $is_chain_done = is_chain_done($chain_id, $user_id, $post_id);
            //is chain done
            if ($is_chain_done){
                //if chain is done, add badge to array
                $badges[] = $badge;
            }
        }

        //CHECK IS ENTIRE MAP IS DONE, and add the badge to the array
        //if the chain isn't done, don't bother to check the entire map
        if($is_chain_done == true || $is_chain_done == null) {
            //chain is done, so get the parent chain
            $term = get_term($chain_id, 'task_chains');
            $termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'task_chains');
            $termParentID = $termParent->term_id;                                   //get the id of the map



            //if ($termParentID == $chain_id) {

            $badge = get_term_meta($termParentID, "pod_achievement", true); //badge assigned to map
            //if map has a badge on it
            if (!empty($badge)) {
                //get all chains and pods
                $children = get_term_children($termParentID, 'task_chains');

                $is_chain_done = false;
                //for each chain/pod //check if all chains are done
                foreach ($children as $child) {
                    //check if each chain is done
                    $is_chain_done = is_chain_done($child, $user_id, $post_id);
                    //if it isn't done, stop checking the other chains.
                    if (!$is_chain_done) {
                        break;
                    }
                }
                //all the chains were done, so add badge to array
                if ($is_chain_done) {
                    $badges[] = $badge;
                }
            }
            //}
        }
        return $badges;
    }
}

function is_chain_done($chain_id, $user_id, $post_id){
    $is_pod = get_term_meta($chain_id, "pod_toggle", true);

    $args = array('tax_query' => array(array('taxonomy' => 'task_chains', 'field' => 'term_id', 'terms' => $chain_id,)), 'orderby' => 'meta_value_num', 'order' => 'ASC', 'posts_per_page' => -1, 'meta_key' => 'go-location_map_order_item', 'meta_value' => '', 'post_type' => 'tasks', 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true
    );

    $go_task_objs = get_posts($args);


    if ($is_pod) {// it is a pod
        $all_needed = get_term_meta($chain_id, "pod_all", true);

        if ($all_needed) {

            $num_needed = count($go_task_objs);
        }else{
            $num_needed = get_term_meta($chain_id, "pod_done_num", true);
        }

        $num_done = 0;
        foreach ($go_task_objs as $go_task_obj) {//check if each task is done
            $go_task_id = $go_task_obj->ID;
            $status = get_post_status( $go_task_id );
            if ($status !== 'publish'){
                $num_done++;
                continue; }//if task is not published then continue--go to next loop

            $stage_count = intval(get_post_meta($go_task_id, 'go_stages', true));//total stages

            $status = intval(go_get_status($go_task_id, $user_id));

            if($post_id == $go_task_id){
                $status++;
            }

            if ($stage_count == $status){
                $is_done = true;
            }else{
                $is_done = false;
            }

            if ($is_done) {
                $num_done++;
            }
            if ($num_done >= $num_needed) {
                return true;//if enough are done, add badge to array
            }
        }
    } else {//not pod
    //is this the last item on chain that isn't optional


        foreach ($go_task_objs as $go_task_obj){
            $go_task_id = $go_task_obj->ID;

            $is_optional = get_post_meta($go_task_id, 'go-location_map_opt', true);
            if (!$is_optional){
                $stage_count = get_post_meta($go_task_id, 'go_stages', true);//total stages

                $status = go_get_status($go_task_id, $user_id);

                if ($stage_count == $status){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }
    return false;
}

