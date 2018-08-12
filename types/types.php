<?php


add_filter( 'template_include', 'go_tasks_template_function', 1 );
function go_tasks_template_function( $template_path ) {
    if ( get_post_type() == 'tasks' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin



            if ( $theme_file = locate_template( array (  'index.php' ) )
                //$theme_file =	get_page_template()
            ) {
                $template_path = $theme_file;
                add_filter( 'the_content', 'go_tasks_filter_content' );
            }
        }
    }
    return $template_path;
}

function go_tasks_filter_content() {
    echo do_shortcode( '[go_task id="'.get_the_id().'"]' );
}


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

function go_clone_post() {
    if ( ! current_user_can( 'edit_posts' ) ) {
        die( -1 );
    }

    // Grab the post id from the ajax call and use it to grab data from the original post.
    $post_id = ( ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : get_the_ID() );

    // verify the nonce passed in the AJAX request
    check_ajax_referer( 'go_clone_post_' . $post_id );

    $post_type = ( ! empty( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : '' );
    $post_data = get_post( $post_id, ARRAY_A );
    $post_custom = get_post_custom( $post_id );

    // Grab the original post's taxonomies.
    if ( 'tasks' == $post_type ) {
        $terms = get_the_terms( $post_id, 'task_chains' );
        $foci = get_the_terms( $post_id, 'task_focus_categories' );
        $cat = get_the_terms( $post_id, 'task_categories' );
        $pods = get_the_terms( $post_id, 'task_pods' );
        $term_ids = array();
        $focus_ids = array();
        $pod_ids = array();
        if ( ! empty( $terms ) ) {
            foreach ( $terms as $key => $term ) {
                if ( ! empty( $term->term_id ) ) {
                    $term_ids[] = $term->term_id;
                }
            }
        }
        if ( ! empty( $foci ) ) {
            foreach ( $foci as $key => $focus_term ) {
                if ( ! empty( $focus_term->term_id ) ) {
                    $focus_ids[] = $focus_term->term_id;
                }
            }
        }
        if ( ! empty( $pods ) ) {
            foreach ( $pods as $key => $pod_term ) {
                if ( ! empty( $pod_term->term_id ) ) {
                    $pod_ids[] = $pod_term->term_id;
                }
            }
        }
    } elseif ( 'go_store' == $post_type ) {
        $cat = get_the_terms( $post_id, 'store_types' );
    } else {
        $cat = get_the_terms( $post_id, 'category' );
    }

    $cat_ids = array();
    if ( ! empty( $cat ) && is_array( $cat ) ) {
        foreach ( $cat as $key => $cat_term ) {
            if ( ! empty( $cat_term->term_id ) ) {
                $cat_ids[] = $cat_term->term_id;
            }
        }
    }

    // Change the post status to "draft", leave the guid up to Wordpress,
    // and remove all other post data.
    $post_data['post_status'] = 'draft';
    $post_data['guid'] = '';
    unset( $post_data['ID'] );
    unset( $post_data['post_title'] );
    unset( $post_data['post_name'] );
    unset( $post_data['post_modified'] );
    unset( $post_data['post_modified_gmt'] );
    unset( $post_data['post_date'] );
    unset( $post_data['post_date_gmt'] );

    // Clone the original post with the modified data from above, and retrieve the new post's id.
    $clone_id = wp_insert_post( $post_data );

    // Set the cloned post's taxonomies using the ids from above.
    if ( 'tasks' == $post_type ) {
        wp_set_object_terms( $clone_id, $term_ids, 'task_chains' );
        wp_set_object_terms( $clone_id, $focus_ids, 'task_focus_categories' );
        wp_set_object_terms( $clone_id, $cat_ids, 'task_categories' );
        wp_set_object_terms( $clone_id, $pod_ids, 'task_pods' );
    } elseif ( 'go_store' == $post_type ) {
        wp_set_object_terms( $clone_id, $cat_ids, 'store_types' );
    } else {
        wp_set_object_terms( $clone_id, $cat_ids, 'category' );
    }

    if ( ! empty( $clone_id ) ) {
        $url = admin_url( "post.php?post={$clone_id}&action=edit" );

        // Add the original post's meta data to the clone.
        foreach ( $post_custom as $key => $value ) {
            $uns = maybe_unserialize( $value[0] );

            add_post_meta( $clone_id, $key, $uns, true );
        }
        echo $url;
    } else {
        echo -1;
    }
    die();
}


function go_create_help_video_lb() {
    ?>
    <div class="dark" style="display: none;"> </div>
    <div class="light" style="display: none;">
        <div id="go_help_video_container" style="height: 100%; width: 100%;">
            <video id="go_option_help_video" class="video-js vjs-default-skin vjs-big-play-centered" controls height="100%" width="100%" ><source src="" type="video/mp4"/></video/options>
        </div>
    </div>
    <?php
}
//add_action( 'admin_head', 'go_create_help_video_lb' );
add_action( 'wp_head', 'go_create_help_video_lb' );



?>