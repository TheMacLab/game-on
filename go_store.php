<?php


function go_store_activate() {

	$my_post = array(
	  'post_title'    => 'Store',
	  'post_content'  => '[go_make_store]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'   => 'page',
	);

	// Insert the post into the database
	
		$page = get_page_by_path( "store" , OBJECT );

     if ( ! isset($page) ){
     	wp_insert_post( $my_post );
     }
}

function go_make_store() {
	//wp_register_script('go_map_js', plugins_url('scripts/go_map.js', __FILE__), array('jquery'),'1.1', false);
	//wp_enqueue_script('go_map_js');
	wp_register_style( 'go_store_style', plugin_dir_url( __FILE__ ).'styles/go_store.css' );
	wp_enqueue_style( 'go_store_style' );
    
	/* Get all task chains with no parents--these are the top level on the map.  They are chains of chains (realms). */
	$taxonomy = 'store_types';
	$term_args0=array(
  		'hide_empty' => false,
  		'orderby' => 'name',
  		'order' => 'ASC',
  		'parent' => '0'
	);
	$tax_terms0 = get_terms($taxonomy,$term_args0);
	echo'
	<div id="storemap" style="display:block;">';
    

	/* For each Store Category with no parent, get all the children.  These are the store categories  
	This will output the various maps for all the top level chains.*/
	$chainParentNum = 0;
	echo '<div id="store">';
			foreach ( $tax_terms0 as $tax_term0 ) {
				$chainParentNum++;

				echo 	"<div id='row_$chainParentNum' class='store_row_container'>
						<div class='parent_cat'><h2>$tax_term0->name</h2></div>
						<div class='store_row'>
						";
	
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
   					echo "<div class ='store_cats'><h3>$tax_term1->name</h3><ul class='store_items'>";
   					/*Gets a list of store items that are assigned to each chain as array. Ordered by post ID */
   					$term_id1 = $tax_term1->term_id;
					$taxonomies = 'store_types';
					$go_store_ids = get_objects_in_term( $term_id1, $taxonomies, $args );
					
					/*Only loop through for first item in array.  This will get the correct order 
					of items from the post metadata */
					$first = true;
					if (!empty($go_store_ids)){	
						foreach ( $go_store_ids as $go_store_id ) {
							if ( $first ){  
    							$task1 = $go_store_ids[0];
    							
    							$go_store_ids3 = get_post_meta( $task1 , 'go_mta_store_order',  true );
    							
        						// do something
        						$first = false;
		    				}
    					}
    					
    				//End set correct order
    				
    					foreach($go_store_ids3 as $rows) {
    							foreach($rows as $row) {
									$status = get_post_status( $row );
									if ($status !== 'publish'){continue; }
									$store_item_name = get_the_title($row);
									$store_item_link = get_permalink($row);
									$page_id = $row;
									$id = $row;
									$blocked = "";
								echo "<li><a class='go_str_item' onclick='go_lb_opener($row);'>$store_item_name</a></li>";
							
    						
    		
    				}}}
    		echo "</ul></div> ";				
		}
		echo "</div> ";
	}			
echo "</div>";
    
}
add_shortcode('go_make_store', 'go_make_store');
         
/* Stop Adding Functions Below this Line */
?>
