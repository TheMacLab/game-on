<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/14/19
 * Time: 8:26 PM
 */

/*
Plugin Name: Post Meta Revisions
Description: Revisions for the 'foo' post meta field
Version:     http://wordpress.stackexchange.com/questions/221946
Author:      John Blackbourn
Plugin URI:  http://lud.icro.us/post-meta-revisions-wordpress
*/

function pmr_fields( $fields ) {
    $fields['go_blog_url'] = 'go_blog_url';
    $fields['go_blog_media'] = 'go_blog_media';
    $fields['go_blog_video'] = 'go_blog_video';

    return $fields;
}

// global $revision doesn't work, using third parameter $post instead
function pmr_field( $value, $field, $post ) {
    return get_metadata( 'post', $post->ID, $field, true );
}

function pmr_restore_revision( $post_id, $revision_id ) {
    $post     = get_post( $post_id );
    $revision = get_post( $revision_id );
    $meta     = get_metadata( 'post', $revision->ID, 'go_blog_url', true );

    if ( false === $meta )
        delete_post_meta( $post_id, 'go_blog_url' );
    else
        update_post_meta( $post_id, 'go_blog_url', $meta );
}

function pmr_save_post( $post_id, $post ) {
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $parent = get_post( $parent_id );


        $meta = get_post_meta( $parent->ID, 'go_blog_url', true );
        if ( false !== $meta )
            add_metadata( 'post', $post_id, 'go_blog_url', $meta );

        $meta = get_post_meta( $parent->ID, 'go_blog_media', true );
        if ( false !== $meta )
            add_metadata( 'post', $post_id, 'go_blog_media', $meta );

        $meta = get_post_meta( $parent->ID, 'go_blog_video', true );
        if ( false !== $meta )
            add_metadata( 'post', $post_id, 'go_blog_video', $meta );
    }
}

// we are using three parameters
add_filter( '_wp_post_revision_field_go_blog_url', 'pmr_field', 10, 3 );
add_action( 'save_post',                   'pmr_save_post', 10, 2 );
add_action( 'wp_restore_post_revision',    'pmr_restore_revision', 10, 2 );
add_filter( '_wp_post_revision_fields',    'pmr_fields' );