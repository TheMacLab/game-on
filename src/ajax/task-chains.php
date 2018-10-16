<?php

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

