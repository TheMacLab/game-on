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
 *         'id'   => 67 // the post ID of the store item
 *     )
 * @return string Un-escaped HTML to replace the shortcode. If not store items are found, an empty
 *                string will be output.
 */
function go_store_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'   => '',
		),
		$atts,
		'go_store'
	);
	$output = '';

	if ( ! empty( $atts['id'] ) ) {

		/**
		 * Outputs an individual link, for the store item with the specified post ID, which contains
		 * the title of the Store Item.
		 */

		$output .= sprintf(
			'<a id="%s" class="go_str_item">%s</a>',
			$atts['id'],
			get_the_title( $atts['id'] )
		);
	}

	return $output;
}
add_shortcode( 'go_store', 'go_store_shortcode' );

?>