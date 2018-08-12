<?php

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


		///////////////
        $tasks = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
			FROM {$go_task_table_name}
			WHERE uid = %d
			ORDER BY last_time DESC",
                $user_id

            )
        );
///////////////////
				echo 	"<div id='map_$last_map_id' class='map'>
						<ul class='primaryNav'>
						<li class='ParentNav'><p>$last_map_object->name</p></li>";

				
				//$Parent_ID = $last_map_object->term_id;
	
				$args=array(
  					'hide_empty' => false,
  					'orderby' => 'order',
  					'order' => 'ASC',
  					'parent' => $last_map_id,
				);
				
				//parent chain
				$Children_term_objects = get_terms($taxonomy_name,$args); //query 2 --get the chains

				/*For each chain.  Prints the chain name then find and prints the tasks. */
   				foreach ( $Children_term_objects as $term_object ) {

					echo "<li><p>$term_object->name";

                    //get the term id of this chain
					//must also be changed in the go_change_sort_order function
                    $term_id = $term_object->term_id;
                    $args=array(
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
                        'meta_value'       => '',
                        'post_type'        => 'tasks',
                        'post_mime_type'   => '',
                        'post_parent'      => '',
                        'author'	   => '',
                        'author_name'	   => '',
                        'post_status'      => 'publish',
                        'suppress_filters' => true

                    );

					$go_task_ids = get_posts($args); //Query 3-- One for each chain
                    $is_pod = get_term_meta($term_id, 'pod_toggle', true); //Q --metadata
                    if($is_pod) {
                        $pod_min = get_term_meta($term_id, 'pod_done_num', true); //Q metadata
                        $pod_all = get_term_meta($term_id, 'pod_all', true);// Q metadata

                        $pod_count = count($go_task_ids);
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
					if (!empty($go_task_ids)){
						foreach($go_task_ids as $row) {
							$status = get_post_status( $row );//is post published
							if ($status !== 'publish'){continue; }//don't show if not pubished

							$task_name = $row->post_title; //Q
							$task_link = get_permalink($row); //Q
							$id = $row->ID;
                            $custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id Q
                            $stage_count = $custom_fields['go_stages'][0];//total stages



                            $tasks = json_decode(json_encode($tasks), True);
                            $ids = array_map(function ($each) {

                                return $each['post_id'];

                            }, $tasks);

                            $key = array_search($id, $ids);
                            if ($key) {
                                $this_task = $tasks[$key];
                                $status = $this_task['status'];
                            }else{
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
    				}
    		echo "</ul>";
		}
		echo "</ul></div>";
	}			
	if ($reload == false) {echo "</div>";}	
}



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

function go_make_map() {
    if ( ! is_admin() ) {
        $user_id = get_current_user_id();
        $last_map_id = get_user_meta($user_id, 'go_last_map', true);
        go_make_map_dropdown();
        go_make_single_map($last_map_id, false);// do your thing
    }
}
add_shortcode('go_make_map', 'go_make_map');

function go_update_last_map() {
 	if(empty($_POST) || !isset($_POST)) {
        ajaxStatus('error', 'Nothing to update.');
    } else {
        try {
        	$mapid = $_POST['goLastMap'];
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
         
?>
