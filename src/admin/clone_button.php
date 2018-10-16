<?php


add_action( 'post_submitbox_misc_actions', 'go_clone_post_ajax' );
function go_clone_post_ajax() {
    global $post;
    $post_type = get_post_type( $post );
    $nonce = wp_create_nonce( 'go_clone_post_' . $post->ID );

    // When the "Clone" button is pressed, send an ajax call to the go_clone_post() function to
    // clone the post using the sent post id and post type.
    echo "
	<div class='misc-pub-section misc-pub-section-last'>
		<input id='go-button-clone' class='button button-large alignright' type='button' value='Clone' />
	</div>
	<script type='text/javascript'>        	
		function clone_post_ajax() {
			jQuery( 'input#go-button-clone' ).click(function() {
				jQuery( 'input#go-button-clone' ).prop( 'disabled', true );
				jQuery.ajax({
					url: '".admin_url( 'admin-ajax.php' )."',
					type: 'POST',
					data: {
						_ajax_nonce: '{$nonce}',
						action: 'go_clone_post',
						post_id: {$post->ID},
						post_type: '{$post_type}'
					}, success: function( res ) {
						if ( -1 !== res && '' !== res ) {
							var reg = new RegExp( \"^(http)\" );
							var url_match = reg.test( res );
							if ( url_match ) {
								window.location = res;
							}
						}
					}
				});
			});
		}
		jQuery( document ).ready(function() {
			clone_post_ajax();
		});
	</script>
	";
}

?>