<?php
include( 'includes/lightbox/frontend-lightbox.php' );

/**
 * Handles the parsing and output of the "go_store" shortcode.
 *
 * This function should be maintained for backwards compatibility. The functionality that the
 * "go_store_wrap" shortcode provides can't be accomplished by the "go_store" shortcode. This is
 * due to the fact that the shortcode attempts to provide two types of functionality. The first one
 * being the output of Store Items under a list of one or more Store Categories. The output of such
 * a list shouldn't involve user-provided content. Thus using multiple "go_store" shortcodes in one
 * block will in broken shortcodes.
 *
 * @since <1.0.0
 *
 * @param array  $atts    User-provided attributes.
 *     e.g.
 *     array(
 *         'cats' => 'store-items, prizes, bounties' // a comma-separated list of one or more Store
 *                                                   // Item Category names
 *         'id'   => 67 // the post ID of the store item
 *     )
 * @return string Un-escaped HTML to replace the shortcode. If not store items are found, an empty
 *                string will be output.
 */
function go_store_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'cats' => '',
			'id'   => '',
		),
		$atts,
		'go_store'
	);
	$output = '';

	if ( ! empty( $atts['cats'] ) ) {

		/**
		 * Outputs a list of links to Store Items within the provided categories.
		 */

		// replaces any characters that would not be accepted as a taxonomy term name
		$cat_str = preg_replace( '/[^a-z\-\_,\S]/i', '', $atts['cats'] );
		$cat_array = explode( ',', $cat_str );

		// appends item links to output string
		foreach ( $cat_array as $cat_name ) {
			$term = get_term_by( 'name', $cat_name, 'store_types' );
			if ( ! $term ) {
				continue;
			}

			$item_ids = get_objects_in_term( $term->term_id, 'store_types' );
			$output .= sprintf( '<h3>%s</h3>', ucwords( $cat_name ) );
			foreach ( $item_ids as $id ) {
				$item_title = get_the_title( $id );
				$output .= sprintf(
					'<a class="go_str_item" onclick="go_lb_opener(%s);">%s</a><br/>',
					$id,
					$item_title
				);
			}
		}
	} elseif ( ! empty( $atts['id'] ) ) {

		/**
		 * Outputs an individual link, for the store item with the specified post ID, which contains
		 * the title of the Store Item.
		 */

		$output .= sprintf(
			'<a class="go_str_item" onclick="go_lb_opener(%s);">%s</a>',
			$atts['id'],
			get_the_title( $atts['id'] )
		);
	}

	return $output;
}
add_shortcode( 'go_store', 'go_store_shortcode' );

/**
 * Handles the parsing and output of the "go_store" shortcode. Outputs a link to a Store Item, with
 * the provided post ID, which encloses the provided content. The provided content will be run
 * through the 'do_shortcode()' function, which will execute any other shortcodes enclosed.
 *
 * @since v3.2.0
 * @see do_shortcode
 *
 * @param array  $atts    User-provided attributes.
 *     e.g.
 *     array(
 *         'id' => 67 // the post ID of the store item
 *     )
 * @param string $content The content between the shortcode start and end tags, if the later were
 *                        used.
 * @return string Un-escaped HTML to replace the shortcode. If not store items are found, an empty
 *                string will be output.
 */
function go_store_wrap_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'go_store_wrap'
	);
	$output = '';

	if ( ! empty( $atts['id'] ) && ! empty( $content ) ) {

		/**
		 * Filters the content of the "go_store_wrap" shortcode, just before it is output.
		 *
		 * @param string $content The raw content of the shortcode.
		 * @param int $id The post ID of the Store Item.
		 */
		$content = apply_filters( 'go_store_wrap_content', $content, $atts['id'] );

		// outputs an individual link and the provided content to be included in the link
		$output .= sprintf(
			'<a class="go_str_item" onclick="go_lb_opener(%s);">%s</a>',
			$atts['id'],
			do_shortcode( $content )
		);
	}

	return $output;
}
add_shortcode( 'go_store_wrap', 'go_store_wrap_shortcode' );
?>