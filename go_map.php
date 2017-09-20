<?php

function go_map_activate() {

    // Activation code here...
    // Create post object
$my_post = array(
  'post_title'    => 'Map',
  'post_content'  => '[makeGOMap]',
  'post_status'   => 'publish',
  'post_author'   => 1,
  'post_type'   => 'page',
);


// Insert the post into the database
wp_insert_post( $my_post );

}


function makeGOMap() {

$my_post = array(
  'post_title'    => 'Map',
  'post_content'  => '[makeGOMap]',
  'post_status'   => 'publish',
  'post_author'   => 1,
  'post_type'   => 'page',
);

 
// Insert the post into the database
wp_insert_post( $my_post );


wp_register_script('go_map_js', plugins_url('scripts/go_map.js', __FILE__), array('jquery'),'1.1', false);
wp_enqueue_script('go_map_js');


wp_register_style( 'go_map_style', plugin_dir_url( __FILE__ ).'styles/go_map.css' );
wp_enqueue_style( 'go_map_style' );
    





	/* Get all task chains with no parents--these are the top level on the map.  They are chains of chains (realms). */
	$taxonomy = 'task_chains';

	$term_args0=array(
  		'hide_empty' => false,
  		'orderby' => 'name',
  		'order' => 'ASC',
  		'parent' => '0'
	);
	
	$tax_terms0 = get_terms($taxonomy,$term_args0);

	echo"

	<div id='sitemap' style='display:none;'>
    
    <div class='dropdown'>
      <button onclick='dropDown()' class='dropbtn'>Choose a Map</button>
      <div id='myDropdown' class='dropdown-content'>";
            /* For each task chain with no parent, add to top level nav  */
            $chainParentNum = 0;
            $false = " false";
            foreach ( $tax_terms0 as $tax_term0 ) {
                $chainParentNum = ($chainParentNum + 1);
                echo "
                <div id='mapLink_$chainParentNum' >
                <a onclick=go_show_map($chainParentNum)><div class='mapLink'></div>$tax_term0->name</a></div>";
            }
        echo"
            </div>
    </div>
    ";    
   
    /* 
    echo"<ul id='utilityNav'>"
	;
		
		// For each task chain with no parent, add to top level nav  
		$chainParentNum = 0;
		$false = " false";
		foreach ( $tax_terms0 as $tax_term0 ) {
			$chainParentNum = ($chainParentNum + 1);
   			echo "<li id='mapLink_$chainParentNum' ><a  onclick=go_show_map($chainParentNum); ><div class='mapLink'></div>$tax_term0->name</a></li>";

		}

	echo"</ul>";
   
   */


	/* For each task chain with no parent, get all the children.  These are the actual task chains.  */
	$chainParentNum = 0;
	echo "<div id='maps'>";
			foreach ( $tax_terms0 as $tax_term0 ) {
				$chainParentNum = ($chainParentNum + 1);

				echo 	"<div id='map_$chainParentNum' class='map'>
						<ul class='primaryNav col6'>
						<li class='ParentNav'><p>$tax_term0->name</p></li>";
	
				$term_id0 = $tax_term0->term_id;
	
				$term_args1=array(
  					'hide_empty' => false,
  					'orderby' => 'order',
  					'order' => 'ASC',
  					'parent' => $term_id0,
                    
				);
		
				$tax_terms1 = get_terms($taxonomy,$term_args1);
				/*Loop for each chain.  Prints the chain name then looks up children (quests). */
   				foreach ( $tax_terms1 as $tax_term1 ) {

   					echo "<li><p>$tax_term1->name</p><ul class='tasks'>";
   					/*Gets a list of quests that are assigned to each chain as array. Ordered by post ID */
   					$term_id1 = $tax_term1->term_id;
					$taxonomies = 'task_chains';
					$go_task_ids = get_objects_in_term( $term_id1, $taxonomies, $args );
			
					/*Only loop through for first item in array.  This will get the correct order 
					of quests from the post metadata */
					$first = true;
					if (!empty($go_task_ids)){	
                        
				
						foreach ( $go_task_ids as $go_task_id ) {
							if ( $first ){
                                
    							$task1 = $go_task_ids[0];
    							$go_task_ids2 = get_post_meta( $task1 , 'go_mta_chain_order',  true );
    							
	
        						// do something
        						$first = false;
		    				}
				
    					}
    		
    					foreach($go_task_ids2 as $rows) {
    						foreach($rows as $row) {
                                $status = get_post_status( $row );
                                if ($status !== 'publish'){break; }
    						$task_name = get_the_title($row);
    						$task_link = get_permalink($row);
    						$page_id = $row;
							$id = $row;
							$blocked = "";
							
							//START OF CODE FOR CHECKBOXES FOR COMPLETED ITEMS
													
													$temp_id = $row;
                                                    $temp_finished           = true;
                                                    
                                                    $temp_status             = go_task_get_status( $temp_id );
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
                                                    
                                                    $temp_five_stage_active  = (boolean) get_post_meta(
                                                        $temp_id,
                                                        'go_mta_five_stage_switch',
                                                        true
                                                    );
                                                    // determines to what stage the user has to progress to finish the task
                                                    if ( $temp_three_stage_active ) {
                                                        $temp_status_required = 3;
                                                    } elseif ( $temp_five_stage_active ) {
                                                        $temp_five_stage_counter = go_task_get_repeat_count( $temp_id );
                                                    }

                                                    // determines whether or not the task is finished
                                                    if ($temp_status === 0) {
                                                    	$blocked = "available_color";
   														 $finished = "circle";
													}
													elseif ( $temp_status !== $temp_status_required &&
                                                            ( ! $temp_five_stage_active ||
                                                            ( $temp_five_stage_active && empty( $temp_five_stage_counter ) ) ) ) {

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

                                            if ( $pos > 0 && ! $is_admin ) {

                                                /**
                                                 * The current task is not first and the user is not an administrator.
                                                 */

                                                $prev_id = 0;

                                                // finds the first ID among the tasks before the current one that is published
                                                for ( $prev_id_counter = 0; $prev_id_counter < $pos; $prev_id_counter++ ) {
                                                    $temp_id = $order[ $prev_id_counter ];
                                                    $temp_task = get_post( $temp_id );

                                                    $temp_finished           = true;
                                                    $temp_status             = go_task_get_status( $temp_id );
                                                    $temp_five_stage_counter = null;
                                                    $temp_status_required    = 4;
                                                    $temp_three_stage_active = (boolean) get_post_meta(
                                                        $temp_id,
                                                        'go_mta_three_stage_switch',
                                                        true
                                                    );
                                                    $temp_five_stage_active  = (boolean) get_post_meta(
                                                        $temp_id,
                                                        'go_mta_five_stage_switch',
                                                        true
                                                    );

                                                    // determines to what stage the user has to progress to finish the task
                                                    if ( $temp_three_stage_active ) {
                                                        $temp_status_required = 3;
                                                    } elseif ( $temp_five_stage_active ) {
                                                        $temp_five_stage_counter = go_task_get_repeat_count( $temp_id );
                                                    }

                                                    // determines whether or not the task is finished
                                                    if ( $temp_status !== $temp_status_required &&
                                                            ( ! $temp_five_stage_active ||
                                                            ( $temp_five_stage_active && empty( $temp_five_stage_counter ) ) ) ) {

                                                        $temp_finished = false;
                                                        $blocked = "filtered";
                                                                                                          
                                                    }
                                                   

                                            
                                                } // End for().

                                            
                                            } // End if().
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
		
		
	
	
    			
							echo "<li class='$blocked'><a href='$task_link'><div class='$finished'></div>$task_name</a></li>";
							}
    					}
    		
    				}
    		echo "</ul>";
    						
		}
		echo "</ul></div>";
	}			
echo "</div>";
    
}
add_shortcode('makeGOMap', 'makeGOMap');
         
/* Stop Adding Functions Below this Line */
?>
