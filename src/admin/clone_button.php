<?php

/*
 * Function for post duplication. Dups appear as drafts. User is redirected to the edit screen
 * https://www.hostinger.com/tutorials/how-to-duplicate-wordpress-page-post#gref
 */
function go_duplicate_post_as_draft(){

    if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'go_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
        wp_die('No post to duplicate has been supplied!');
    }

    /*
     * Nonce verification
     */
    if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
        return;

    go_clone_post_new(false);
}
add_action( 'admin_action_go_duplicate_post_as_draft', 'go_duplicate_post_as_draft' );

function go_new_task_from_template_as_draft()
{

    if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'go_new_task_from_template_as_draft' == $_REQUEST['action']))) {
        wp_die('No post to duplicate has been supplied!');
    }

    /*
     * Nonce verification
     */
    if (!isset($_GET['template_nonce']) || !wp_verify_nonce($_GET['template_nonce'], basename(__FILE__))) return;

    go_clone_post_new(true);
}

function go_clone_post_new($is_template = false){
    global $wpdb;
    /*
     * get the original post id
     */
    $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
    /*
     * and all the original post data then
     */
    $post = get_post( $post_id );

    /*
     * if you don't want current user to be the new post author,
     * then change next couple of lines to this: $new_post_author = $post->post_author;
     */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
     * if post data exists, create the post duplicate
     */
    if (isset( $post ) && $post != null) {

        if ($is_template) {
            $post_type = 'tasks';
        }else{
            $post_type = $post->post_type;
        }

        /*
         * new post data array
         */
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title . " copy",
            'post_type'      => $post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        /*
         * insert the post by wp_insert_post() function
         */
        $new_post_id = wp_insert_post( $args );

        /*
         * get all current post terms ad set them to the new post draft
         */
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        /*
         * duplicate all post meta just in two SQL queries
         */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if( $meta_key == '_wp_old_slug' ) continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }


        /*
         * finally, redirect to the edit post screen for the new draft
         */
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}
add_action( 'admin_action_go_new_task_from_template_as_draft', 'go_new_task_from_template_as_draft' );

/*
 * Add the duplicate link to action list for post_row_actions
 */
function go_duplicate_post_link( $actions, $post ) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=go_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Clone</a>';
        if ($post->post_type == 'tasks_templates') {
            $actions['new_from_template'] = '<a href="' . wp_nonce_url('admin.php?action=go_new_task_from_template_as_draft&post=' . $post->ID, basename(__FILE__), 'template_nonce' ) . '" title="Duplicate this item" rel="permalink">New From Template</a>';

        }
    }
    return $actions;
}

add_filter( 'post_row_actions', 'go_duplicate_post_link', 10, 2 );

function go_duplicate_post_button($post ) {
    if (current_user_can('edit_posts')) {
        echo '<div style="padding: 10px;"><a class="button" href="' . wp_nonce_url('admin.php?action=go_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Clone</a></div>';
        if ($post->post_type == 'tasks_templates'){
            echo '<div style="padding: 10px;"><a class="button" href="' . wp_nonce_url('admin.php?action=go_new_task_from_template_as_draft&post=' . $post->ID, basename(__FILE__), 'template_nonce' ) . '" title="Duplicate this item" rel="permalink">New From Template</a></div>';
        }
    }
}

add_action( 'post_submitbox_misc_actions', 'go_duplicate_post_button' );


/*
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
*/

?>