<?php
// Includes
include('includes/lightbox/frontend-lightbox.php');
// Store Shortcode
function go_gold_store_sc ($atts, $content = null) {
	$user_ID = get_current_user_id(); // Current User ID
	$user_points = go_return_points( $user_ID ); // Current CubePoints points
	$args = array( 'post_type' => 'go_store', 'posts_per_page' => 10 ); // Defines args used to get custom post type content
	$loop = new WP_Query( $args ); // Loops in custom post type content
	if ( $loop->have_posts() ) : $loop->the_post(); //
	extract( shortcode_atts( array(
		'cats' => '',
		'id' => ''
	), $atts ) );
	if ($cats) { // Checks if there are categories defined
		$the_cats = explode(',', $cats); // gets the string from cats="HERE"
		foreach ($the_cats as $cat) { // for every one of the categories...
			$the_term_id = get_term_by('name', $cat, 'store_types')->term_id; // get the term's ID
			$the_args = array('orderby' => 'name'); // an array, telling the_items how to display
			$the_items = get_objects_in_term( $the_term_id, 'store_types', $the_args ); // gets all items under category
			$upper_cat = ucwords($cat);
			echo '<h3>'.$upper_cat.'</h3>';
			foreach ($the_items as $item) {
				// Definitions
				$the_title = get_the_title($item); // get item title
				echo '<a class="go_str_item" onclick="go_lb_opener('.$item.');">'.$the_title.'</a><br />';
				
			}
		} 
	}	elseif ($id) {
			$the_title = get_the_title($id); // get item title
			$custom_fields = get_post_custom($id);
			$req_currency = $custom_fields['go_mta_store_currency'][0];
			return '<a class="go_str_item" onclick="go_lb_opener('.$id.');">'.$the_title.'</a>';
		}
endif;
}
add_shortcode ('go_store', 'go_gold_store_sc');
?>