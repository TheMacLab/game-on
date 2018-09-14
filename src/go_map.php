<?php

/**
 *
 */
function go_map_activate() {
	$my_post = array(
	  'post_title'    => 'Map',
	  'post_content'  => '[go_make_map]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'   => 'page',
	);
	
	$page = get_page_by_path( "map" , OBJECT );

     if ( ! isset($page) ){
     	wp_insert_post( $my_post );
     }
}

//good
/**
 * @param $last_map_id
 * @param $reload
 */
function go_make_single_map1($last_map_id, $reload){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    wp_nonce_field( 'go_update_last_map');
    $last_map_object = get_term_by( 'id' , $last_map_id, 'task_chains');//Query 1 - get the map
    $user_id = get_current_user_id();
    $is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
    $taxonomy_name = 'task_chains';

    if ($reload == false) {echo "<div id='mapwrapper'>";}
    echo "<div id='loader-wrapper style='width: 100%'><div id='loader' style='display:none;'></div></div><div id='maps' data-mapid='$last_map_id'>";
    if(!empty($last_map_id)){

        echo 	"<div id='map_$last_map_id' class='map'>
				<ul class='primaryNav'>
				<li class='ParentNav'><p>$last_map_object->name</p></li>";

        //Query 1: Get all chains on map--chains with this map as parent
        $args=array(
            'hide_empty' => false,
            'orderby' => 'order',
            'order' => 'ASC',
            'parent' => $last_map_id,
        );

//Query 1: parent chain
        $Children_term_objects = get_terms($taxonomy_name,$args); //query 1 --get the chains

        /*For each chain.  Prints the chain name then find and prints the tasks. */
        foreach ( $Children_term_objects as $term_object ) {

            //get the term id of this chain
            $term_id = $term_object->term_id;

//Query 2
            //Get all posts on this chain
            $args=array(
                'post_type'        => 'tasks',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'term_id',
                        'terms' => $term_id,
                    )
                ),
                'orderby'          => 'meta_value_num',
                'order'            => 'ASC',
                'posts_per_page'   => -1,
                'meta_key'         => 'go-location_map_order_item',
                'post_status'      => 'publish',
                'suppress_filters' => true

            );

            //$go_task_obj = get_posts($args); //Query 2
            $go_task_obj2 = new WP_Query( $args );
            $go_task_objs = $go_task_obj2->posts;

            echo "<li><p>$term_object->name";
            $term_custom = get_term_meta($term_id, '', true);
            $is_pod = get_term_meta($term_id, 'pod_toggle', true); //Q --metadata
            if($is_pod) {
                $pod_min = get_term_meta($term_id, 'pod_done_num', true); //Q metadata
                $pod_all = get_term_meta($term_id, 'pod_all', true);// Q metadata

                $pod_count = count($go_task_objs);
                if ($pod_all || ($pod_min >= $pod_count)){
                    $task_name_pl = get_option('options_go_tasks_name_plural'); //Q option
                    echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete all $task_name_pl. </span>";
                }
                else {
                    if ($pod_min>1){
                        $task_name = get_option('options_go_tasks_name_plural'); //Q option
                    }else{
                        $task_name = get_option('options_go_tasks_name_singular'); //Q option
                    }

                    echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete at least $pod_min $task_name. </span>";
                }
            }

            //START: The list of tasks in the chain

            echo "<ul class='tasks'>";
            //If there are tasks
            if (!empty($go_task_objs)){
                $go_task_ids = array();

                //make an array of the task ids (this could be pulled from a cache if it was created)
                foreach($go_task_objs as $row) {
                    $go_task_ids[] = $row->ID;
                }
                //Query 3
                //Get User info for these tasks
                $id_string = implode(',', $go_task_ids);
                $user_tasks = $wpdb->get_results(
                    "SELECT *
						FROM {$go_task_table_name}
						WHERE uid = $user_id AND post_id IN ($id_string)
						ORDER BY last_time DESC"
                );
                $user_tasks = json_decode(json_encode($user_tasks), True);

                //for each of the tasks (objects retrieved in query 2)
                foreach($go_task_objs as $row) {
                    $status = get_post_status( $row );//is post published
                    if ($status !== 'publish'){continue; }//don't show if not pubished

                    $task_name = $row->post_title; //Q
                    $task_link = get_permalink($row); //Q
                    $id = $row->ID;
                    $custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id Q
                    $stage_count = $custom_fields['go_stages'][0];//total stages
                    $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);

                    //$user_tasks is an array of task object arrays
                    //These next lines pull information from the array by
                    //getting the position of this task in the array,
                    //then get the status this task from the tasks array

                    $ids = array_map(function ($each) {
                        return $each['post_id'];
                    }, $user_tasks);

                    $key = array_search($id, $ids);

                    if ($key !== false) {
                        $this_task = $user_tasks[$key];
                        $status = $this_task['status'];
                    }else{
                        $status = 0;
                        $this_task = array();

                    }
                    //add status to cache
                    $cache_key = 'go_get_status_' . $id;
                    wp_cache_set ($cache_key, $status, 'go_single' );


                    if($custom_fields['bonus_switch'][0]) {
                        $bonus_stage_toggle = true;
                        if ($key) {
                            $bonus_status = $this_task['bonus_status'];
                        }else{
                            $bonus_status = 0;
                        }
                        //$bonus_status = go_get_bonus_status($id, $user_id);
                        $repeat_max = $custom_fields['go_bonus_limit'][0];//max repeats of bonus stage
                        $bonus_stage_name = get_option('options_go_tasks_bonus_stage').':';
                    }
                    else{
                        $bonus_stage_toggle = false;
                    }

                    //if locked
                    $task_is_locked = go_task_locks($id, $user_id, false, $custom_fields, $is_logged_in, true);
                    //$task_is_locked = false;
                    $unlock_message = '';
                    if ($task_is_locked === 'password'){
                        $unlock_message = '<div><i class="fa fa-unlock"></i> Password</div>';
                        $task_is_locked = false;
                    }
                    else if ($task_is_locked === 'master password') {
                        $unlock_message = '<div><i class="fa fa-unlock"></i> Master Password</div>';
                        $task_is_locked = false;
                    }


                    if ($stage_count === $status){
                        $task_color = 'done';
                        $finished = 'checkmark';
                    }else if ($task_is_locked){
                        $task_color = 'locked';
                        $finished = null;
                    }
                    else{
                        $task_color = 'available';
                        $finished = null;
                    }

                    if ($custom_fields['go-location_map_opt'][0] && !$is_pod) {
                        $optional = 'optional_task';
                        $bonus_task = get_option('options_go_tasks_optional_task').':';  //Q option
                    }
                    else {
                        $optional = null;
                        $bonus_task = null;
                    }


                    echo "<li class='$task_color $optional '><a href='$task_link'><div class='$finished'></div><span <span style='font-size: .8em;'>$bonus_task $task_name <br>$unlock_message</span>";

                    if($badge_ids) {
                        $badge_ids = unserialize($badge_ids);
                        foreach($badge_ids as $badge_id) {
                            go_map_quest_badge($badge_id);
                        }
                    }

                    if ($bonus_stage_toggle == true){
                        if ($bonus_status == 0 || $bonus_status == null){
                            echo "<br><div id='repeat_ratio' style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name 
								<div class='star-empty fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> 0 / $repeat_max</div>
							
						";
                        }
                        else if ($bonus_status == $repeat_max) {
                            echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-full fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> $bonus_status / $repeat_max</div>
							";
                        }
                        else {
                            echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-half fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-half-o fa-stack-2x'></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
									
								</div> $bonus_status / $repeat_max</div>
							";
                        }
                    }

                    echo"</a>
							</li>";
                }


                $badge = get_term_meta($term_id, "pod_achievement", true);
                go_map_badge($badge);

            }
            echo "</ul>";
        }
        $badge = get_term_meta($last_map_id, "pod_achievement", true);
        go_map_badge($badge);



        echo "</ul></div>";
    }
    if ($reload == false) {echo "</div>";}
}

//with caching
/**
 * @param $last_map_id
 * @param $reload
 */
function go_make_single_map_new($last_map_id, $reload){
	global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    //wp_nonce_field( 'go_update_last_map');
//Query 0 - get the map
	$last_map_object = get_term_by( 'id' , $last_map_id, 'task_chains');
    $user_id = get_current_user_id();
    $is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
	$taxonomy_name = 'task_chains';

	if ($reload == false) {echo "<div id='mapwrapper'>";}
	echo "<div id='loader-wrapper style='width: 100%'><div id='loader' style='display:none;'></div></div><div id='maps' data-mapid='$last_map_id'>";
	if(!empty($last_map_id)){

		echo 	"<div id='map_$last_map_id' class='map'>
				<ul class='primaryNav'>
				<li class='ParentNav'><p>$last_map_object->name</p></li>";

//Query 1: Get all chains on map--chains with this map as parent

		//parent chain
		//$Children_term_objects = get_terms($taxonomy_name,$args); //query 1 --get the chains
        //$term_objects = go_get_map_chain_term_objects($last_map_id, $taxonomy_name);
        $term_ids = go_get_map_chain_term_ids($last_map_id);

        //(this is so we can get the names and meta data in the object cache)
        $term_objects = get_terms( $taxonomy_name, array(
            'include' => $term_ids,
        ) );
        //$term_names = go_get_map_chain_term_names($last_map_id, $taxonomy_name);

		foreach ( $term_objects as $term_object ) {

		    $term_name = $term_object->name;
            //get the term id of this chain
            $term_id = $term_object->term_id;

//Query 2: Get all posts on this chain
            $go_post_ids = go_get_chain_posts($term_id, true);

            //this query is run just to load post information into the cache
            //new WP_Query( array(
             //   'post__in' => $go_post_ids,
            //) );
            //Get all posts on this chain
            $args=array(
                'post_type'        => 'tasks',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'term_id',
                        'terms' => $term_id,
                    )
                ),
                'orderby'          => 'meta_value_num',
                'order'            => 'ASC',
                'posts_per_page'   => -1,
                'meta_key'         => 'go-location_map_order_item',
                'post_status'      => 'publish',
                'suppress_filters' => true

            );

            $args = array(
                'post_type'        => 'tasks',
                'post__in' => $go_post_ids,
            );

            //$go_task_obj = get_posts($args); //Query 2
            $go_task_obj2 = new WP_Query( $args );
            $go_task_obj = $go_task_obj2->posts;


            //echo "<li><p>$term_object->name";
            echo "<li><p>$term_name";
            //$term_custom = get_term_meta($term_id, '', true);
            $is_pod = get_term_meta($term_id, 'pod_toggle', true); //Q --metadata
            if($is_pod) {
                $pod_min = get_term_meta($term_id, 'pod_done_num', true); //Q metadata
                $pod_all = get_term_meta($term_id, 'pod_all', true);// Q metadata

				$pod_count = count($go_post_ids);
				if ($pod_all || ($pod_min >= $pod_count)){
					$task_name_pl = get_option('options_go_tasks_name_plural'); //Q option
					echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete all $task_name_pl. </span>";
				}
				else {
					if ($pod_min>1){
						$task_name = get_option('options_go_tasks_name_plural'); //Q option
					}else{
						$task_name = get_option('options_go_tasks_name_singular'); //Q option
					}

					echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete at least $pod_min $task_name. </span>";
				}
			}

			//////The list of tasks in the chain//
			echo "<ul class='tasks'>";
			if (!empty($go_post_ids)){

/////HERE --can't do much becuase it's user data
                //Query 3
                //Get User info for these tasks
				$id_string = implode(',', $go_post_ids);

                $tasks = $wpdb->get_results(
                        "SELECT *
						FROM {$go_task_table_name}
						WHERE uid = $user_id AND post_id IN ($id_string)
						ORDER BY last_time DESC"
				);
                $tasks = json_decode(json_encode($tasks), True);

                foreach($go_post_ids as $post_id) {
					$status = get_post_status( $post_id );//is post published
					if ($status !== 'publish'){continue; }//don't show if not pubished

					$task_name = get_the_title($post_id); //Q
					$task_link = get_permalink($post_id); //Q
					//$id = $row->ID;
///////////////HERE
					$custom_fields = get_post_custom( $post_id ); // Just gathering some data about this task with its post id Q
                    //$custom_fields = get_post_custom( $post_id );
					//$custom_fields = go_get_post_meta($id);
					$stage_count = $custom_fields['go_stages'][0];//total stages
					$badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);

                    //
					//Get the status of this task from the tasks array
					//get the position of this task in the array, then get the status
					$ids = array_map(function ($each) {
						return $each['post_id'];
					}, $tasks);

					$key = array_search($post_id, $ids);
					if ($key) {
						$this_task = $tasks[$key];
						$status = $this_task['status'];
					}else{
                        $this_task = null;
						$status = 0;
					}

					if($custom_fields['bonus_switch'][0]) {
						$bonus_stage_toggle = true;
						if ($key) {
							$bonus_status = $this_task['bonus_status'];
						}else{
							$bonus_status = 0;
						}
						//$bonus_status = go_get_bonus_status($id, $user_id);
						$repeat_max = $custom_fields['go_bonus_limit'][0];//max repeats of bonus stage
						$bonus_stage_name = get_option('options_go_tasks_bonus_stage').':';
					}
					else{
						$bonus_stage_toggle = false;
					}

					//if locked
					$task_is_locked = go_task_locks($post_id, $user_id, false, $custom_fields, $is_logged_in, true);
                    //$task_is_locked = false;

					//$task_is_locked = false;
					$unlock_message = '';
					if ($task_is_locked === 'password'){
						$unlock_message = '<div><i class="fa fa-unlock"></i> Password</div>';
						$task_is_locked = false;
					}
					else if ($task_is_locked === 'master password') {
						$unlock_message = '<div><i class="fa fa-unlock"></i> Master Password</div>';
						$task_is_locked = false;
					}

					if ($stage_count === $status){
						$task_color = 'done';
						$finished = 'checkmark';
					}else if ($task_is_locked){
						$task_color = 'locked';
						$finished = null;
					}
					else{
						$task_color = 'available';
						$finished = null;
					}

					if ($custom_fields['go-location_map_opt'][0] && !$is_pod) {
						$optional = 'optional_task';
						$bonus_task = get_option('options_go_tasks_optional_task').':';  //Q option
					}
					else {
						$optional = null;
						$bonus_task = null;
					}


					echo "<li class='$task_color $optional '><a href='$task_link'><div class='$finished'></div><span <span style='font-size: .8em;'>$bonus_task $task_name <br>$unlock_message</span>";

                    if($badge_ids) {
                    	$badge_ids = unserialize($badge_ids);
                        foreach($badge_ids as $badge_id) {
                            go_map_quest_badge($badge_id);
                        }
                    }

					if ($bonus_stage_toggle == true){
						if ($bonus_status == 0 || $bonus_status == null){
							echo "<br><div id='repeat_ratio' style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name 
								<div class='star-empty fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> 0 / $repeat_max</div>
							
						";
						}
						else if ($bonus_status == $repeat_max) {
							echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-full fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> $bonus_status / $repeat_max</div>
							";
						}
						else {
							echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-half fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-half-o fa-stack-2x'></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
									
								</div> $bonus_status / $repeat_max</div>
							";
						}
					}

					echo"</a>
							</li>";
				}


                $badge = get_term_meta($term_id, "pod_achievement", true);
                go_map_badge($badge);

			}
		echo "</ul>";
		}
        $badge = get_term_meta($last_map_id, "pod_achievement", true);
        go_map_badge($badge);



		echo "</ul></div>";
	}			
	if ($reload == false) {echo "</div>";}	
}

//new
/**
 * @param $last_map_id
 * @param $reload
 */
function go_make_single_map($last_map_id, $reload){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    wp_nonce_field( 'go_update_last_map');
    $last_map_object = get_term_by( 'id' , $last_map_id, 'task_chains');//Query 1 - get the map
    $user_id = get_current_user_id();
    $is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
    $taxonomy_name = 'task_chains';

    if ($reload == false) {echo "<div id='mapwrapper'>";}
    echo "<div id='loader-wrapper style='width: 100%'><div id='loader' style='display:none;'></div></div><div id='maps' data-mapid='$last_map_id'>";
    if(!empty($last_map_id)){

        //get user loot to pass to locks so they don't have to look it up for each task
        //$go_loot_table_name = "{$wpdb->prefix}go_loot";
        //$loot = $wpdb->get_results ("SELECT * FROM {$go_loot_table_name} WHERE uid = {$user_id}");
        //$loot =$loot[0];
        //$loot = json_decode(json_encode($loot), True);


        echo 	"<div id='map_$last_map_id' class='map'>
				<ul class='primaryNav'>
				<li class='ParentNav'><p>$last_map_object->name</p></li>";

        //Query 1: Get all chains on map--chains with this map as parent
        /*
        $args=array(
            'hide_empty' => false,
            'orderby' => 'order',
            'order' => 'ASC',
            'parent' => $last_map_id,
        );
        */

//Query 1: parent chain
        //$Children_term_objects = get_terms($taxonomy_name,$args); //query 1 --get the chains

        $term_ids = go_get_map_chain_term_ids($last_map_id);

        //(this is so we can get the names and meta data in the object cache)
        //$Children_term_objects = get_terms( $taxonomy_name, array('include' => $term_ids) );

        /*For each chain.  Prints the chain name then find and prints the tasks. */
        //foreach ( $Children_term_objects as $term_object ) {
        foreach ( $term_ids as $term_id ) {

            $term_data = go_term_data($term_id);
            $term_name = $term_data[0];
            $term_custom = $term_data[1];

            //$term_object = get_term($term_id);

            //get the term id of this chain
            //$term_id = $term_object->term_id;

//Query 2
            //Get all posts on this chain
            /*
            $args=array(
                'post_type'        => 'tasks',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'term_id',
                        'terms' => $term_id,
                    )
                ),
                'orderby'          => 'meta_value_num',
                'order'            => 'ASC',
                'posts_per_page'   => -1,
                'meta_key'         => 'go-location_map_order_item',
                'post_status'      => 'publish',
                'suppress_filters' => true

            );

            //$go_task_obj = get_posts($args); //Query 2

            $go_task_obj2 = new WP_Query( $args );
            $go_task_objs = $go_task_obj2->posts;
            */

            //Get array of postIDs from transient--this also creates transients of each posts data if needed
            $go_post_ids = go_get_chain_posts($term_id, true);

            //echo "<li><p>$term_object->name";
            echo "<li><p>$term_name";
            /*
            $term_custom = get_term_meta($term_id, '', true);
            $is_pod = get_term_meta($term_id, 'pod_toggle', true); //Q --metadata
            if($is_pod) {
                $pod_min = get_term_meta($term_id, 'pod_done_num', true); //Q metadata
                $pod_all = get_term_meta($term_id, 'pod_all', true);// Q metadata
            */
            $is_pod = (isset($term_custom['pod_toggle'][0]) ?  $term_custom['pod_toggle'][0] : null);

            if($is_pod) {
                $pod_min = (isset($term_custom['pod_done_num'][0]) ?  $term_custom['pod_done_num'][0] : null);
                $pod_all = (isset($term_custom['pod_all'][0]) ?  $term_custom['pod_all'][0] : null);
                $pod_count = count($go_post_ids);
                if ($pod_all || ($pod_min >= $pod_count)){
                    $task_name_pl = get_option('options_go_tasks_name_plural'); //Q option
                    echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete all $task_name_pl. </span>";
                }
                else {
                    if ($pod_min>1){
                        $task_name = get_option('options_go_tasks_name_plural'); //Q option
                    }else{
                        $task_name = get_option('options_go_tasks_name_singular'); //Q option
                    }

                    echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete at least $pod_min $task_name. </span>";
                }
            }

            //START: The list of tasks in the chain

            echo "<ul class='tasks'>";
            //If there are tasks
            if (!empty($go_post_ids)){
                //$go_task_ids = array();

                //make an array of the task ids (this could be pulled from a cache if it was created)
                //foreach($go_task_objs as $row) {$go_task_ids[] = $row->ID;}


                //Query 3
                //Get User info for these tasks
                $id_string = implode(',', $go_post_ids);
                $user_tasks = $wpdb->get_results(
                    "SELECT *
						FROM {$go_task_table_name}
						WHERE uid = $user_id AND post_id IN ($id_string)
						ORDER BY last_time DESC"
                );
                $user_tasks = json_decode(json_encode($user_tasks), True);

                //for each of the tasks (objects retrieved in query 2)
                foreach($go_post_ids as $post_id) {
                    $go_task_data = go_map_task_data($post_id); //0--name, 1--status, 2--permalink, 3--metadata
                    $task_name = $go_task_data[0];
                    $status = $go_task_data[1];
                    $task_link = $go_task_data[2];
                    $custom_fields = $go_task_data[3];


                    //$status = get_post_status( $row );//is post published
                    if ($status !== 'publish'){continue; }//don't show if not pubished

                    //$task_name = $row->post_title; //Q
                    //$task_link = get_permalink($row); //Q
                    //$id = $row->ID;
                    //$custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id Q
                    $stage_count = $custom_fields['go_stages'][0];//total stages
                    $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);

                    //$user_tasks is an array of task object arrays
                    //These next lines pull information from the array by
                    //getting the position of this task in the array,
                    //then get the status this task from the tasks array
                    $ids = array_map(function ($each) {
                        return $each['post_id'];
                    }, $user_tasks);

                    $key = array_search($post_id, $ids);

                    if ($key !== false) {
                        $this_task = $user_tasks[$key];
                        $status = $this_task['status'];
                    }else{
                        $status = 0;
                        $this_task = array();

                    }
                    //add status to cache
                    $cache_key = 'go_get_status_' . $post_id;
                    wp_cache_set ($cache_key, $status, 'go_single' );


                    if($custom_fields['bonus_switch'][0]) {
                        $bonus_stage_toggle = true;
                        if ($key) {
                            $bonus_status = $this_task['bonus_status'];
                        }else{
                            $bonus_status = 0;
                        }
                        //$bonus_status = go_get_bonus_status($id, $user_id);
                        $repeat_max = $custom_fields['go_bonus_limit'][0];//max repeats of bonus stage
                        $bonus_stage_name = get_option('options_go_tasks_bonus_stage').':';
                    }
                    else{
                        $bonus_stage_toggle = false;
                    }

                    //if locked
                    $task_is_locked = go_task_locks($post_id, $user_id, false, $custom_fields, $is_logged_in, true);
                    //$task_is_locked = false;
                    $unlock_message = '';
                    if ($task_is_locked === 'password'){
                        $unlock_message = '<div><i class="fa fa-unlock"></i> Password</div>';
                        $task_is_locked = false;
                    }
                    else if ($task_is_locked === 'master password') {
                        $unlock_message = '<div><i class="fa fa-unlock"></i> Master Password</div>';
                        $task_is_locked = false;
                    }


                    if ($stage_count <= $status){
                        $task_color = 'done';
                        $finished = 'checkmark';
                    }else if ($task_is_locked){
                        $task_color = 'locked';
                        $finished = null;
                    }
                    else{
                        $task_color = 'available';
                        $finished = null;
                    }

                    if ($custom_fields['go-location_map_opt'][0] && !$is_pod) {
                        $optional = 'optional_task';
                        $bonus_task = get_option('options_go_tasks_optional_task').':';  //Q option
                    }
                    else {
                        $optional = null;
                        $bonus_task = null;
                    }


                    echo "<li class='$task_color $optional '><a href='$task_link'><div class='$finished'></div><span <span style='font-size: .8em;'>$bonus_task $task_name <br>$unlock_message</span>";

                    if($badge_ids) {
                        $badge_ids = unserialize($badge_ids);
                        foreach($badge_ids as $badge_id) {
                            go_map_quest_badge($badge_id);
                        }
                    }

                    if ($bonus_stage_toggle == true){
                        if ($bonus_status == 0 || $bonus_status == null){
                            echo "<br><div id='repeat_ratio' style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name 
								<div class='star-empty fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> 0 / $repeat_max</div>
							
						";
                        }
                        else if ($bonus_status == $repeat_max) {
                            echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-full fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> $bonus_status / $repeat_max</div>
							";
                        }
                        else {
                            echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-half fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-half-o fa-stack-2x'></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
									
								</div> $bonus_status / $repeat_max</div>
							";
                        }
                    }

                    echo"</a>
							</li>";
                }


                $badge = get_term_meta($term_id, "pod_achievement", true);
                go_map_badge($badge);

            }
            echo "</ul>";
        }
        $badge = get_term_meta($last_map_id, "pod_achievement", true);
        go_map_badge($badge);



        echo "</ul></div>";
    }
    if ($reload == false) {echo "</div>";}
}

/**
 * @param $badge
 */
function go_map_badge($badge){
    //does this term have a badge assigned and if so show it

    if($badge){
        $badge_img_id = get_term_meta( $badge, 'my_image' );
        $badge_description = term_description( $badge );

        echo "<li>";
        $badge_obj = get_term( $badge);
        $badge_name = $badge_obj->name;
        //$badge_img_id =(isset($custom_fields['my_image'][0]) ?  $custom_fields['my_image'][0] : null);
        $badge_img = wp_get_attachment_image($badge_img_id[0], array( 100, 100 ));
        if (!$badge_img){
            $badge_img = "<div style='width:100px; height: 100px;'></div>";
        }

        //$badge_attachment = wp_get_attachment_image( $badge_img_id, array( 100, 100 ) );
        //$img_post = get_post( $badge_id );
        if ( ! empty( $badge_obj ) ) {
            echo"<div class='go_badge_wrap'>
                        <div class='go_badge_container '><figure class=go_badge title='{$badge_name}'>";

            if (!empty($badge_description)){
                echo "<span class='tooltip' ><span class='tooltiptext'>{$badge_description}</span>{$badge_img}</span>";
            }else{
                echo "$badge_img";
            }
            echo "        
              				 <figcaption>{$badge_name}</figcaption>
                            </figure>
                        </div>
                       </div>";

        }
        echo "</li>";

    }
}

/**
 * @param $badge
 */
function go_map_quest_badge($badge){
    //does this term have a badge assigned and if so show it

    if($badge){
        $badge_img_id = get_term_meta( $badge, 'my_image' );
        $badge_description = term_description( $badge );

        //echo "<li>";
        $badge_obj = get_term( $badge);
        $badge_name = $badge_obj->name;
        //$badge_img_id =(isset($custom_fields['my_image'][0]) ?  $custom_fields['my_image'][0] : null);
        $badge_img = wp_get_attachment_image($badge_img_id[0], array( 50, 50 ));
        if (!$badge_img){
            $badge_img = "<div style='width:50px; height: 50px;'></div>";
        }

        //$badge_attachment = wp_get_attachment_image( $badge_img_id, array( 100, 100 ) );
        //$img_post = get_post( $badge_id );
        if ( ! empty( $badge_obj ) ) {
            echo"<div class='go_badge_quest_wrap'>
                        <div class='go_badge_quest_container '><figure class=go_quest_badge title='{$badge_name}'>";

            if (!empty($badge_description)){
                echo "<span class='tooltip' ><span class='tooltiptext'>{$badge_description}</span>{$badge_img}</span>";
            }else{
                echo "$badge_img";
            }
            echo "        
              				 <figcaption>{$badge_name}</figcaption>
                            </figure>
                        </div>
                       </div>";

        }
        //echo "</li>";

    }
}

/**
 *
 */
function go_make_map_dropdown(){
/* Get all task chains with no parents--these are the top level on the map.  They are chains of chains (realms). */
	$taxonomy = 'task_chains';
	$term_args0=array(
  		'hide_empty' => false,
  		'orderby' => 'name',
  		'order' => 'ASC',
  		'parent' => '0'
	);
	$tax_terms_maps = get_terms($taxonomy,$term_args0);
	
	echo"
	<div id='sitemap' style='visibility:hidden;'>   
    <div class='dropdown'>
      <button onclick='go_map_dropDown()' class='dropbtn'>Choose a Map</button>
      <div id='myDropdown' class='dropdown-content'>";
    /* For each task chain with no parent, add to Dropdown  */
            foreach ( $tax_terms_maps as $tax_term_map ) {
				$term_id = $tax_term_map->term_id;  
                echo "
                <div id='mapLink_$term_id' >
                <a onclick=go_show_map($term_id)>$tax_term_map->name</a></div>";
            }
        echo"</div></div></div> ";
}

/**
 *
 */
function go_make_map() {
    if ( ! is_admin() ) {
        $user_id = get_current_user_id();
        $last_map_id = get_user_meta($user_id, 'go_last_map', true);
        go_make_map_dropdown();
        go_make_single_map($last_map_id, false);// do your thing
    }
}
add_shortcode('go_make_map', 'go_make_map');

/**
 * @param bool $map_id
 */
function go_update_last_map($map_id = false) {
 	if(empty($_POST) || !isset($_POST)) {
        ajaxStatus('error', 'Nothing to update.');
    } else {
        try {
        	if (!$map_id){$mapid = $_POST['goLastMap'];}
        	check_ajax_referer('go_update_last_map', 'security' );
			$user_id = get_current_user_id();
			update_user_meta( $user_id, 'go_last_map', $mapid );
			go_make_single_map($mapid, true);
			
            die();
        } catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

/**
 * @param $atts
 * @param null $content
 * @return string
 */
function go_single_map_link($atts, $content = null ) {
    $atts = shortcode_atts(
        array(
            "map_id" => ''
        ),
        $atts
    );
    $map_id = $atts['map_id'];
    return "<a href='#' onclick='go_to_this_map(" . $map_id . ")'>" . $content . "</a>";
}
add_shortcode( 'go_single_map_link', 'go_single_map_link' );

/**
 *
 */
function go_to_this_map(){
    check_ajax_referer( 'go_to_this_map');
    $user_id = get_current_user_id();
    $map_id = $_POST['map_id'];
    update_user_meta( $user_id, 'go_last_map', $map_id );

    $map_url = get_option('options_go_locations_map_map_link');
    $map_url = (string) $map_url;
    $go_map_link = get_permalink( get_page_by_path($map_url) );
    echo $go_map_link;
    die;
}
         
?>
