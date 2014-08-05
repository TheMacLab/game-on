<?php
// Includes
include('includes/lightbox/frontend-lightbox.php');
// Store Shortcode
function go_gold_store_sc ($atts, $content = null) {
	$args = array( 'post_type' => 'go_store', 'posts_per_page' => 10 ); // Defines args used to get custom post type content
	$loop = new WP_Query( $args ); // Loops in custom post type content
	
	if (count(array_keys($loop->posts)) > 0) {
		$output_array = array();
		extract( shortcode_atts( array(
			'cats' => '',
			'id' => ''
		), $atts ) );
		if ($cats) {
			// the idea is that teachers/educators can leave an optional space between the items in their store.
			// e.g. [go_store cats='time, music, bathroom-passes'] or [go_store cats='time,music,bathroom-passes']
			$cat_array_raw = explode(", ", $cats); // remove comma-space blocks and create an array
			$cat_string = implode(",", $cat_array_raw); // join the array into a string with commas
			$cat_array = explode(",", $cat_string);	// remove comma blocks and break the string into an array
			for ($i = 0; $i < count($cat_array); $i++) {
				$the_term_id = get_term_by('name', $cat_array[$i], 'store_types')->term_id;
				$the_args = array('orderby' => 'name');
				$the_items = get_objects_in_term( $the_term_id, 'store_types', $the_args );
				$upp_cat = ucwords($cat_array[$i]);

				array_push($output_array, "<h3>".$upp_cat."</h3>");
		
				for ($x = 0; $x < count($the_items); $x++) {
					$the_title = get_the_title($the_items[$x]);
					array_push($output_array, "<a class='go_str_item' onclick='go_lb_opener(".$the_items[$x].");'>".$the_title."</a><br/>");
				}
		
			}
			$output_array = implode(" ", $output_array);
			return $output_array;
		} else if ($id) {
			$the_title = get_the_title($id); // get item title
			$custom_fields = get_post_custom($id);
			$req_currency = $custom_fields['go_mta_store_currency'][0];
			return '<a class="go_str_item" onclick="go_lb_opener('.$id.');">'.$the_title.'</a>';
		}
	}
}
add_shortcode ('go_store', 'go_gold_store_sc');
?>