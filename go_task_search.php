<?php

function filter_search( $query ) {
	$post_type = $_GET['post_type'];

	// if the post type is null or false, then set it to the default post-type.
	if ( empty( $post_type ) ) {
		$post_type = array( 'post', 'page' );
	}

	// checks if this query is in the main loop (for a post or page) and retrieves relative tasks.
	  if ( $query->is_main_query() ) {
	    if ( $query->is_search ) {
			$query->set( 'post_type', $post_type );
	    }
	    return $query;
	}
}
add_filter( 'pre_get_posts', 'filter_search' );

?>