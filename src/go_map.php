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

function go_make_single_map($last_map_id, $reload = false){
    wp_nonce_field( 'go_update_last_map');
	$last_map_object = get_term_by( 'id' , $last_map_id, 'task_chains');
    $user_id = get_current_user_id();
    $is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
	$taxonomy = 'task_chains';

	if ($reload == false) {echo "<div id='mapwrapper'>";}
	echo "<div id='loader-wrapper style='width: 100%'><div id='loader' style='display:none;'></div></div><div id='maps' data-mapid='$last_map_id'>";
	if(!empty($last_map_id)){

				echo 	"<div id='map_$last_map_id' class='map'>
						<ul class='primaryNav'>
						<li class='ParentNav'><p>$last_map_object->name</p></li>";
				
				$Parent_ID = $last_map_object->term_id;
	
				$args=array(
  					'hide_empty' => false,
  					'orderby' => 'order',
  					'order' => 'ASC',
  					'parent' => $Parent_ID,
				);
				
				//parent chain
				$Children_term_objects = get_terms($taxonomy,$args);

				/*For each chain.  Prints the chain name then find and prints the tasks. */
   				foreach ( $Children_term_objects as $term_object ) {

					echo "<li><p>$term_object->name";


                    //get the term id of this chain
                    $term_id = $term_object->term_id;
                    $taxonomy = 'task_chains';
                    $args=array(
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'term_id',
                                'terms' => $term_id,
                            )
                        ),
                        'orderby'          => 'meta_value',
                        'order'            => 'ASC',

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

					$go_task_ids = get_posts($args);
                    //$go_task_ids = get_objects_in_term( $term_id1, $taxonomies );
                    //get post_ids in order
                    //$go_task_ids = get_term_meta($term_id, 'go_order', true);

					/*
					///////POD STUFF
					$chain_pod = get_term_meta($term_id, 'pod_toggle', true);

					//THIS IS A REALLY BAD WAY TO FIND OUT IF A TASK IS DONE
					//FIX ME
					//if it is a pod, 
   					if ($chain_pod){
   						//get number of tasks needed to complete pod
   						$go_pod_count = get_term_meta($term_id, 'pod_done_num', true);
   						if ($go_pod_count == null){$go_pod_count = 0;}
   						//count number of total tasks in the pod
						$count_pod = count($go_task_ids);
						//get the number of tasks done
   						if ( !empty ( $go_task_ids ) ) {
   							$tasks_done = 0;
							foreach ( $go_task_ids as $go_task_id ) {
								//how far the user has progressed
								$temp_status = go_task_get_status( $go_task_id );
								if (empty($temp_status)) {
									 $temp_status = 0;
								}
								// determines to what stage the user has to progress to finish the task
								$temp_three_stage_active = (boolean) get_post_meta($temp_id, 'go_mta_three_stage_switch', true);
								$temp_status_required = 4;
								if ( $temp_three_stage_active ) {
									$temp_status_required = 3;
								}
								// determines whether or not the task is finished
								if ( $temp_status == $temp_status_required) {
									$finished = true;
									$tasks_done++;
								}	
							}
							
						}					
   						echo "<br><br><span style='padding-top: 10px; font-size: .8em;'>Choose at least $go_pod_count. <br> $tasks_done done.</span>";
   					}
					echo "</p>";
   					//end pod stuff

*/

                    echo "<ul class='tasks'>";
					if (!empty($go_task_ids)){
						foreach($go_task_ids as $row) {
							$status = get_post_status( $row );
							if ($status !== 'publish'){continue; }
							$task_name = get_the_title($row);
							$task_link = get_permalink($row);

							$task_color = "";
							$id = $row->ID;
                            $custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id
                            $stage_count = $custom_fields['go_stages'][0];//total stages

                            $status = go_get_status($id, $user_id);
                            if($custom_fields['bonus_switch'][0]) {
                            	$bonus_stages = go_get_bonus_status($id, $user_id);
                                $repeat_max = $custom_fields['go_bonus_limit'][0];//max repeats of bonus stage
                            }


                            //if locked
							$task_is_locked = go_task_locks($id, $user_id, false, $custom_fields, $is_logged_in);
							if ($task_is_locked){
                                $task_color = 'locked';
							}

							else if ($stage_count == $status){
                                $task_color = 'done';
							}
							else{
                            	$task_color = 'available';
							}
								//

							//$optional = "optional_task";
                            //$bonus_task = get_option( 'go_bonus_task' ).":";
							//$bonus_stage = get_option( 'go_bonus_task' ).":";


							echo "<li class='$task_color $optional'><a href='$task_link'><div class='$finished'></div><span <span style='font-size: .8em;'>$bonus_task $task_name</span>";
							if ($temp_five_stage_active == true){
								if ($temp_five_stage_counter == null){
									echo "<br><div id='repeat_ratio' style='padding-top: 10px; font-size: .7em;'>$bonus_stage: 
										<div class='star-empty fa-stack'>
											<i class='fa fa-star fa-stack-2x''></i>
  											<i class='fa fa-star-o fa-stack-2x'></i>
										</div> 0 / $temp_repeat_ammount</div>
									
								";
								}
								else if ($temp_five_stage_counter == $temp_repeat_ammount) {
									echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage: 
										<div class='star-full fa-stack'>
											<i class='fa fa-star fa-stack-2x''></i>
  											<i class='fa fa-star-o fa-stack-2x'></i>
										</div> $temp_five_stage_counter / $temp_repeat_ammount</div>
									";
								}
								else {
									echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage: 	
										<div class='star-half fa-stack'>
											<i class='fa fa-star fa-stack-2x''></i>
											<i class='fa fa-star-half-o fa-stack-2x''></i>
  											<i class='fa fa-star-o fa-stack-2x'></i>
										</div> $temp_five_stage_counter / $temp_repeat_ammount</div>
									";
								}
							}

							echo"</a>
									</li>";
							//}
					}

    				}
    		echo "</ul>";
    						
		}
		echo "</ul></div>";
	}			
	if ($reload == false) {echo "</div>";}	
}

function go_make_map_dropdown(){
	$user_id = get_current_user_id();
	$last_map_id = get_user_meta($user_id, 'go_last_map', true);
	///here
	$last_map = get_term_by( 'id' , $last_map_id, 'task_chains');
	$map_name = $last_map->name;
	////
	
	
    
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

            $false = " false";
            foreach ( $tax_terms_maps as $tax_term_map ) {
            	
				$term_id = $tax_term_map->term_id;  
                echo "
                <div id='mapLink_$term_id' >
                <a onclick=go_show_map($term_id)>$tax_term_map->name</a></div>";
            }
        echo"
            </div>
    </div></div>
    ";  
}

function go_make_map() {

	$user_id = get_current_user_id();

	$last_map_id = get_user_meta($user_id, 'go_last_map', true);
	go_make_map_dropdown();

	go_make_single_map($last_map_id, false);
   
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
