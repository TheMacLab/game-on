<?php
function debug_to_console( $data ) {
    $output = $data;
    if ( is_array( $output ) )
        $output = implode( ',', $output);

    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}



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
	$user_id = get_current_user_id();
	///here
	$last_map = get_term_by( 'id' , $last_map_id, 'task_chains');
	$map_name = $last_map->name;
	wp_nonce_field( 'go_update_last_map');
	$taxonomy = 'task_chains';
	////
	if ($reload == false) {echo "<div id='mapwrapper'>";}
	echo "<div id='loader-wrapper style='width: 100%'><div id='loader' style='display:none;'></div></div><div id='maps' data-mapid='$last_map_id'>";
	if(!empty($last_map_id)){
	
				$tax_term0 = $last_map;
				echo 	"<div id='map_$last_map_id' class='map'>
						<ul class='primaryNav'>
						<li class='ParentNav'><p>$tax_term0->name</p></li>";
	
				$term_id0 = $tax_term0->term_id;
	
				$term_args1=array(
  					'hide_empty' => false,
  					'orderby' => 'order',
  					'order' => 'ASC',
  					'parent' => $term_id0,       
				);
		
				$tax_terms1 = get_terms($taxonomy,$term_args1);
				/*Loop for each chain.  Prints the chain name then looks up children (tasks). */
   				foreach ( $tax_terms1 as $tax_term1 ) {
   					
   					/*Gets a list of quests that are assigned to each chain as array. Ordered by post ID */
   					$term_id1 = $tax_term1->term_id;
					$taxonomies = 'task_chains';
					$go_task_ids = get_objects_in_term( $term_id1, $taxonomies, $args );
					
					echo "<li><p>$tax_term1->name";
					
					$chain_pod = get_term_meta($term_id1, 'pod_toggle', true);   
					//if it is a pod, 
   					if ($chain_pod){
   						//get number of tasks needed to complete pod
   						$go_pod_count = get_term_meta($term_id1, 'pod_done_num', true);
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
   					echo "</p><ul class='tasks'>";
					/*Only loop through for first item in array.  This will get the correct order 
					of quests from the post metadata */
					$first = true;
					if (!empty($go_task_ids)){	
						
						foreach ( $go_task_ids as $go_task_id ) {
							if ( $first ){  
    							$task1 = $go_task_ids[0];
    							$go_task_chains_array = get_post_meta( $task1 , 'go_mta_chain_order',  true );
    							$first = false;
        						$go_task_chain_post_id_array = $go_task_chains_array[$term_id1];
							}
    					}
    				//End set correct order
						foreach($go_task_chain_post_id_array as $row) {
							$status = get_post_status( $row );
							if ($status !== 'publish'){continue; }
							$task_name = get_the_title($row);
							$task_link = get_permalink($row);
							$page_id = $row;
							$id = $row;
							$blocked = "";
						
							//START OF CODE FOR CHECKBOXES FOR COMPLETED ITEMS
												
							$temp_id = $row;
							$temp_finished = true;
						
							$temp_status = go_task_get_status( $temp_id );
							if (empty($temp_status)) {
								 $temp_status = 0;
							}
						
							$temp_five_stage_counter = null;
							$temp_status_required    = 4;
							$temp_three_stage_active = (boolean) get_post_meta(
								$temp_id,
								'go_mta_three_stage_switch',
								true
							);
							$temp_five_stage_active = null;
							$temp_five_stage_active  = (boolean) get_post_meta(
								$temp_id,
								'go_mta_five_stage_switch',
								true
							);
						
							$temp_optional_task  = (boolean) get_post_meta(
								$temp_id,
								'go_mta_optional_task',
								true
							);					
						
							if ( ! empty( $temp_optional_task ) && ! ($chain_pod) ) {
								$optional = "optional_task";
								$bonus_task = go_return_options( 'go_bonus_task' ).":";
							}
							else {
								$optional = "";
								$bonus_task ="";
							}
							$bonus_stage = go_return_options( 'go_bonus_stage' );
							
							// determines to what stage the user has to progress to finish the task
							if ( $temp_three_stage_active ) {
								$temp_status_required = 3;
							} 
							
							if ( $temp_five_stage_active ) {
								$temp_five_stage_counter = go_task_get_repeat_count( $temp_id );
							}

							// determines whether or not the task is finished
							if ($temp_status === 0) {
								$blocked = "available_color";
								 $finished = "circle";
							}
							elseif ( $temp_status !== $temp_status_required ) {

								$temp_finished = false;
								$finished = "circle";
								$blocked = "available_color";	
							}
							else {
								$finished = "checkmark";
							}
						
						
	   
							//END OF CODE FOR CHECKBOXES	
											
							 //START OF CODE TO GREY OUT ITEMS THAT ARE INACCESSIBLE		
						
							$badge_name = go_return_options( 'go_badges_name' );

							// the current user's id
							$user_id = get_current_user_id();
						
							// gets admin user object
							$go_admin_email = get_option( 'go_admin_email' );
							if ( $go_admin_email ) {
								$admin = get_user_by( 'email', $go_admin_email );
							}

							// use display name of admin with store email, or use default name
							if ( ! empty( $admin ) ) {
								$admin_name = addslashes( $admin->display_name );
							} else {
								$admin_name = 'an administrator';
							}			
							$is_admin = go_user_is_admin( $user_id );

							// determines if user is logged in
							$is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
						

							// determines if the task is for users (logged-in users) eyes only
							$is_user_only = get_post_meta( $id, 'go_mta_user_only_content', true ) ? true : false;

							//blocks access if not logged in if task is for logged in users only
							if ( $is_user_only && ! $is_logged_in ) {
							}	

							
							// determines whether or not the task is filtered at all
							$is_filtered = false;

							// retrieves the date and time, if specified, after which non-admins can accept this task
							$start_filter = get_post_meta( $id, 'go_mta_start_filter', true );
						

							// gets an array of badge IDs to prevent users who don't have the badges from viewing the task
							$badge_filter_meta = get_post_meta( $id, 'go_mta_badge_filter', true );

							// obtains the chain order list for this task, if there is one
							$chain_order = get_post_meta( $id, 'go_mta_chain_order', true );
							
							$temp_repeat_ammount  = get_post_meta(
															$id,
															'go_mta_repeat_amount',
															true
														);
													
						
						


							// if any filters are on, sets variable to check if should be blocked
							if ( ! empty( $start_filter['checked'] ) || ! empty( $badge_filter_meta[0] ) || ! empty( $chain_order ) ) {
								$is_filtered = true;
					 
							}

							// determines whether or not filters will affect visitors (users that aren't logged in)
							$filtered_content_hidden = false;
							$hfc_meta = get_post_meta( $id, 'go_mta_hide_filtered_content', true );
							if ( '' === $hfc_meta || 'true' === $hfc_meta ) {
								$filtered_content_hidden = true;
							}

							if ( $is_logged_in || ( ! $is_logged_in && $is_filtered && $filtered_content_hidden ) ) {

									/**
									 * Start Filter
									 */

									// holds the output to be displayed when a non-admin has been stopped by the start filter
									$time_string = '';
									$unix_now = current_time( 'timestamp' );
									if ( ! empty( $start_filter ) && ! empty( $start_filter['checked'] ) && ! $is_admin ) {
										$start_date = $start_filter['date'];
										$start_time = $start_filter['time'];
										$start_unix = strtotime( $start_date . $start_time );

										// stops execution if the user is a non-admin and the start date and time has not
										// passed yet
										if ( $unix_now < $start_unix ) {
											$time_string = date( 'g:i A', $start_unix ) . ' on ' . date( 'D, F j, Y', $start_unix );
											$blocked = "filtered";
											//return "<span class='go_error_red'>Will be available at {$time_string}.</span>";
										}
									}

									/**
									 * Task Chain Filter
									 */

									// determines whether or not the user can proceed, if the task is in a chain
								
									if ( ! empty( $chain_order ) ) {
										$chain_links = array();

										foreach ( $chain_order as $chain_tt_id => $order ) {
											$pos = array_search( $id, $order );
											$the_chain = get_term_by( 'term_taxonomy_id', $chain_tt_id );
											$chain_title = ucwords( $the_chain->name );
											$chain_pod = get_term_meta($chain_tt_id, 'pod_toggle', true);
										
											if ( $pos > 0 && ! $is_admin ) {
												//if ( empty ( $temp_optional_task )){
												if (empty( $chain_pod )){

												/**
												 * The current task is not first and the user is not an administrator.
												 */

												$prev_id = 0;

												// finds the first ID among the tasks before the current one that is published AND not optional
												for ( $prev_id_counter = 0; $prev_id_counter < $pos; $prev_id_counter++ ) {
												
													$temp_id = $order[ $prev_id_counter ];
													$temp_optional_prev_task  = (boolean) get_post_meta(
														$temp_id,
														'go_mta_optional_task',
														true
													);
													if ( empty ( $temp_optional_prev_task )){
														$temp_task = get_post( $temp_id );
														$temp_finished           = true;
														$temp_status             = go_task_get_status( $temp_id );
														//$temp_five_stage_counter = null;
														$temp_status_required    = 4;
														$temp_three_stage_active = (boolean) get_post_meta(
															$temp_id,
															'go_mta_three_stage_switch',
															true
														);
														
														

														// determines to what stage the user has to progress to finish the task
														if ( $temp_three_stage_active ) {
															$temp_status_required = 3;
														} 

														// determines whether or not the task is finished
														if ( $temp_status !== $temp_status_required ) {

															$temp_finished = false;
															$blocked = "filtered";
																									  
														} 
													}                                           
												} // End for().
											} // End if().
											//}
											}
										} // End foreach().                       
									} // End if().

									/**
									 * Badge Filter
									 */

									// gets the user's current badges
									$user_badges = get_user_meta( $user_id, 'go_badges', true );
									if ( ! $user_badges ) {
										$user_badges = array();
									}

									// an array of badge IDs
									$badge_filter_ids = array();

									// determines if the user has the correct badges
									$badge_diff = array();
									if ( ! empty( $badge_filter_meta ) &&
										isset( $badge_filter_meta[0] ) &&
										$badge_filter_meta[0] &&
										! $is_admin
									) {
										$badge_filter_ids = array_filter( (array) $badge_filter_meta[1], 'go_badge_exists' );

										// checks to see if the filter array are in the the user's badge array
										$intersection = array_values( array_intersect( $user_badges, $badge_filter_ids ) );

										// stores an array of the badges that were not found in the user's badge array
										$badge_diff = array_values( array_diff( $badge_filter_ids, $intersection ) );
										if ( ! empty( $badge_filter_ids ) && ! empty( $badge_diff ) ) {
											$return_badge_list = true;
											$blocked = "filtered";
											$visitor_str = '';
											/*if ( ! $is_logged_in ) {
												$blocked = "filtered";
												//$visitor_str = ', and you must be ' .
												//	'<a href="' . esc_url( $login_url ) . '">logged in</a> to obtain them';
											}
											*/

											// outputs all the badges that the user must obtain before beginning this task
											/*
											return sprintf(
										   
												'<span class="go_error_red">' .
													'You need the following %s(s) to begin this %s%s:' .
												'</span><br/>%s',
												strtolower( $badge_name ),
												ucwords( $task_name ),
												$visitor_str,
												go_badge_output_list( $badge_diff, $return_badge_list )
											);
											*/
										}
									} //End badge filter
							} // End if().
							 //END OF CODE TO GREY OUT ITEMS THAT ARE INACCESSABLE	
	
	


			//<div class='fa fa-star-o fa-1x' ></div> 0 / $temp_repeat_ammount</div>
							echo "<li class='$blocked $optional'><a href='$task_link'><div class='$finished'></div><span <span style='font-size: .8em;'>$bonus_task $task_name</span>";
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
	//debug_to_console( "go_make_map" );
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
	//debug_to_console( "go_make_map" );
	$ajax_url   = admin_url( 'admin-ajax.php' );        // Localized AJAX URL

	wp_register_script('go_map_js', plugins_url('scripts/go_map.js', __FILE__), array('jquery'),'1.1', false);
	wp_localize_script('go_map_js','ajax_url',$ajax_url);
	wp_enqueue_script('go_map_js');

	wp_register_style( 'go_map_style', plugin_dir_url( __FILE__ ).'styles/go_map.css' );
	wp_enqueue_style( 'go_map_style' );
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
