<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 8/4/18
 * Time: 1:29 PM
 */

function go_wp_enqueue_media( $args = array() ) {
    // Enqueue me just once per page, please.
    if ( did_action( 'wp_enqueue_media' ) )
        return;

    global $content_width, $wpdb, $wp_locale;

    $defaults = array(
        'post' => null,
    );
    $args = wp_parse_args( $args, $defaults );

    // We're going to pass the old thickbox media tabs to `media_upload_tabs`
    // to ensure plugins will work. We will then unset those tabs.
    $tabs = array(
        // handler action suffix => tab label
        'type'     => '',
        'type_url' => '',
        'gallery'  => '',
        'library'  => '',
    );

    /** This filter is documented in wp-admin/includes/media.php */
    $tabs = apply_filters( 'media_upload_tabs', $tabs );
    unset( $tabs['type'], $tabs['type_url'], $tabs['gallery'], $tabs['library'] );

    $props = array(
        'link'  => get_option( 'image_default_link_type' ), // db default is 'file'
        'align' => get_option( 'image_default_align' ), // empty default
        'size'  => get_option( 'image_default_size' ),  // empty default
    );

    $exts = array_merge( wp_get_audio_extensions(), wp_get_video_extensions() );
    $mimes = get_allowed_mime_types();
    $ext_mimes = array();
    foreach ( $exts as $ext ) {
        foreach ( $mimes as $ext_preg => $mime_match ) {
            if ( preg_match( '#' . $ext . '#i', $ext_preg ) ) {
                $ext_mimes[ $ext ] = $mime_match;
                break;
            }
        }
    }

    /**
     * Allows showing or hiding the "Create Audio Playlist" button in the media library.
     *
     * By default, the "Create Audio Playlist" button will always be shown in
     * the media library.  If this filter returns `null`, a query will be run
     * to determine whether the media library contains any audio items.  This
     * was the default behavior prior to version 4.8.0, but this query is
     * expensive for large media libraries.
     *
     * @since 4.7.4
     * @since 4.8.0 The filter's default value is `true` rather than `null`.
     *
     * @link https://core.trac.wordpress.org/ticket/31071
     *
     * @param bool|null Whether to show the button, or `null` to decide based
     *                  on whether any audio files exist in the media library.
     */
    $show_audio_playlist = apply_filters( 'media_library_show_audio_playlist', true );
    if ( null === $show_audio_playlist ) {
        $show_audio_playlist = $wpdb->get_var( "
            SELECT ID
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
            AND post_mime_type LIKE 'audio%'
            LIMIT 1
        " );
    }

    /**
     * Allows showing or hiding the "Create Video Playlist" button in the media library.
     *
     * By default, the "Create Video Playlist" button will always be shown in
     * the media library.  If this filter returns `null`, a query will be run
     * to determine whether the media library contains any video items.  This
     * was the default behavior prior to version 4.8.0, but this query is
     * expensive for large media libraries.
     *
     * @since 4.7.4
     * @since 4.8.0 The filter's default value is `true` rather than `null`.
     *
     * @link https://core.trac.wordpress.org/ticket/31071
     *
     * @param bool|null Whether to show the button, or `null` to decide based
     *                  on whether any video files exist in the media library.
     */
    $show_video_playlist = apply_filters( 'media_library_show_video_playlist', true );
    if ( null === $show_video_playlist ) {
        $show_video_playlist = $wpdb->get_var( "
            SELECT ID
            FROM $wpdb->posts
            WHERE post_type = 'attachment'
            AND post_mime_type LIKE 'video%'
            LIMIT 1
        " );
    }

    /**
     * Allows overriding the list of months displayed in the media library.
     *
     * By default (if this filter does not return an array), a query will be
     * run to determine the months that have media items.  This query can be
     * expensive for large media libraries, so it may be desirable for sites to
     * override this behavior.
     *
     * @since 4.7.4
     *
     * @link https://core.trac.wordpress.org/ticket/31071
     *
     * @param array|null An array of objects with `month` and `year`
     *                   properties, or `null` (or any other non-array value)
     *                   for default behavior.
     */
    $months = apply_filters( 'media_library_months_with_files', null );
    if ( ! is_array( $months ) ) {
        $months = $wpdb->get_results( $wpdb->prepare( "
            SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
            FROM $wpdb->posts
            WHERE post_type = %s
            ORDER BY post_date DESC
        ", 'attachment' ) );
    }
    foreach ( $months as $month_year ) {
        $month_year->text = sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month_year->month ), $month_year->year );
    }

    $settings = array(
        'tabs'      => $tabs,
        'tabUrl'    => add_query_arg( array( 'chromeless' => true ), admin_url('media-upload.php') ),
        'mimeTypes' => wp_list_pluck( get_post_mime_types(), 0 ),
        /** This filter is documented in wp-admin/includes/media.php */
        'captions'  => ! apply_filters( 'disable_captions', '' ),
        'nonce'     => array(
            'sendToEditor' => wp_create_nonce( 'media-send-to-editor' ),
        ),
        'post'    => array(
            'id' => 0,
        ),
        'defaultProps' => $props,
        'attachmentCounts' => array(
            'audio' => ( $show_audio_playlist ) ? 1 : 0,
            'video' => ( $show_video_playlist ) ? 1 : 0,
        ),
        'oEmbedProxyUrl' => rest_url( 'oembed/1.0/proxy' ),
        'embedExts'    => $exts,
        'embedMimes'   => $ext_mimes,
        'contentWidth' => $content_width,
        'months'       => $months,
        'mediaTrash'   => MEDIA_TRASH ? 1 : 0,
    );

    $post = null;
    if ( isset( $args['post'] ) ) {
        $post = get_post( $args['post'] );
        $settings['post'] = array(
            'id' => $post->ID,
            'nonce' => wp_create_nonce( 'update-post_' . $post->ID ),
        );

        $thumbnail_support = current_theme_supports( 'post-thumbnails', $post->post_type ) && post_type_supports( $post->post_type, 'thumbnail' );
        if ( ! $thumbnail_support && 'attachment' === $post->post_type && $post->post_mime_type ) {
            if ( wp_attachment_is( 'audio', $post ) ) {
                $thumbnail_support = post_type_supports( 'attachment:audio', 'thumbnail' ) || current_theme_supports( 'post-thumbnails', 'attachment:audio' );
            } elseif ( wp_attachment_is( 'video', $post ) ) {
                $thumbnail_support = post_type_supports( 'attachment:video', 'thumbnail' ) || current_theme_supports( 'post-thumbnails', 'attachment:video' );
            }
        }

        if ( $thumbnail_support ) {
            $featured_image_id = get_post_meta( $post->ID, '_thumbnail_id', true );
            $settings['post']['featuredImageId'] = $featured_image_id ? $featured_image_id : -1;
        }
    }

    if ( $post ) {
        $post_type_object = get_post_type_object( $post->post_type );
    } else {
        $post_type_object = get_post_type_object( 'post' );
    }

    $strings = array(
        // Generic
        'url'         => __( 'URL' ),
        'addMedia'    => __( 'Add Media' ),
        'search'      => __( 'Search' ),
        'select'      => __( 'Select' ),
        'cancel'      => __( 'Cancel' ),
        'update'      => __( 'Update' ),
        'replace'     => __( 'Replace' ),
        'remove'      => __( 'Remove' ),
        'back'        => __( 'Back' ),
        /* translators: This is a would-be plural string used in the media manager.
           If there is not a word you can use in your language to avoid issues with the
           lack of plural support here, turn it into "selected: %d" then translate it.
         */
        'selected'    => __( '%d selected' ),
        'dragInfo'    => __( 'Drag and drop to reorder media files.' ),

        // Upload
        'uploadFilesTitle'  => __( 'Upload Files' ),
        'uploadImagesTitle' => __( 'Upload Images' ),

        // Library
        'mediaLibraryTitle'      => __( 'Media Library' ),
        'insertMediaTitle'       => __( 'Add Media' ),
        'createNewGallery'       => __( 'Create a new gallery' ),
        'createNewPlaylist'      => __( 'Create a new playlist' ),
        'createNewVideoPlaylist' => __( 'Create a new video playlist' ),
        'returnToLibrary'        => __( '&#8592; Return to library' ),
        'allMediaItems'          => __( 'All media items' ),
        'allDates'               => __( 'All dates' ),
        'noItemsFound'           => __( 'No items found.' ),
        'insertIntoPost'         => $post_type_object->labels->insert_into_item,
        'unattached'             => __( 'Unattached' ),
        'trash'                  => _x( 'Trash', 'noun' ),
        'uploadedToThisPost'     => $post_type_object->labels->uploaded_to_this_item,
        'warnDelete'             => __( "You are about to permanently delete this item from your site.\nThis action cannot be undone.\n 'Cancel' to stop, 'OK' to delete." ),
        'warnBulkDelete'         => __( "You are about to permanently delete these items from your site.\nThis action cannot be undone.\n 'Cancel' to stop, 'OK' to delete." ),
        'warnBulkTrash'          => __( "You are about to trash these items.\n  'Cancel' to stop, 'OK' to delete." ),
        'bulkSelect'             => __( 'Bulk Select' ),
        'cancelSelection'        => __( 'Cancel Selection' ),
        'trashSelected'          => __( 'Trash Selected' ),
        'untrashSelected'        => __( 'Untrash Selected' ),
        'deleteSelected'         => __( 'Delete Selected' ),
        'deletePermanently'      => __( 'Delete Permanently' ),
        'apply'                  => __( 'Apply' ),
        'filterByDate'           => __( 'Filter by date' ),
        'filterByType'           => __( 'Filter by type' ),
        'searchMediaLabel'       => __( 'Search Media' ),
        'searchMediaPlaceholder' => __( 'Search media items...' ), // placeholder (no ellipsis)
        'noMedia'                => __( 'No media files found.' ),

        // Library Details
        'attachmentDetails'  => __( 'Attachment Details' ),

        // From URL
        'insertFromUrlTitle' => __( 'Insert from URL' ),

        // Featured Images
        'setFeaturedImageTitle' => $post_type_object->labels->featured_image,
        'setFeaturedImage'      => $post_type_object->labels->set_featured_image,

        // Gallery
        'createGalleryTitle' => __( 'Create Gallery' ),
        'editGalleryTitle'   => __( 'Edit Gallery' ),
        'cancelGalleryTitle' => __( '&#8592; Cancel Gallery' ),
        'insertGallery'      => __( 'Insert gallery' ),
        'updateGallery'      => __( 'Update gallery' ),
        'addToGallery'       => __( 'Add to gallery' ),
        'addToGalleryTitle'  => __( 'Add to Gallery' ),
        'reverseOrder'       => __( 'Reverse order' ),

        // Edit Image
        'imageDetailsTitle'     => __( 'Image Details' ),
        'imageReplaceTitle'     => __( 'Replace Image' ),
        'imageDetailsCancel'    => __( 'Cancel Edit' ),
        'editImage'             => __( 'Edit Image' ),

        // Crop Image
        'chooseImage' => __( 'Choose Image' ),
        'selectAndCrop' => __( 'Select and Crop' ),
        'skipCropping' => __( 'Skip Cropping' ),
        'cropImage' => __( 'Crop Image' ),
        'cropYourImage' => __( 'Crop your image' ),
        'cropping' => __( 'Cropping&hellip;' ),
        /* translators: 1: suggested width number, 2: suggested height number. */
        'suggestedDimensions' => __( 'Suggested image dimensions: %1$s by %2$s pixels.' ),
        'cropError' => __( 'There has been an error cropping your image.' ),

        // Edit Audio
        'audioDetailsTitle'     => __( 'Audio Details' ),
        'audioReplaceTitle'     => __( 'Replace Audio' ),
        'audioAddSourceTitle'   => __( 'Add Audio Source' ),
        'audioDetailsCancel'    => __( 'Cancel Edit' ),

        // Edit Video
        'videoDetailsTitle'     => __( 'Video Details' ),
        'videoReplaceTitle'     => __( 'Replace Video' ),
        'videoAddSourceTitle'   => __( 'Add Video Source' ),
        'videoDetailsCancel'    => __( 'Cancel Edit' ),
        'videoSelectPosterImageTitle' => __( 'Select Poster Image' ),
        'videoAddTrackTitle'    => __( 'Add Subtitles' ),

        // Playlist
        'playlistDragInfo'    => __( 'Drag and drop to reorder tracks.' ),
        'createPlaylistTitle' => __( 'Create Audio Playlist' ),
        'editPlaylistTitle'   => __( 'Edit Audio Playlist' ),
        'cancelPlaylistTitle' => __( '&#8592; Cancel Audio Playlist' ),
        'insertPlaylist'      => __( 'Insert audio playlist' ),
        'updatePlaylist'      => __( 'Update audio playlist' ),
        'addToPlaylist'       => __( 'Add to audio playlist' ),
        'addToPlaylistTitle'  => __( 'Add to Audio Playlist' ),

        // Video Playlist
        'videoPlaylistDragInfo'    => __( 'Drag and drop to reorder videos.' ),
        'createVideoPlaylistTitle' => __( 'Create Video Playlist' ),
        'editVideoPlaylistTitle'   => __( 'Edit Video Playlist' ),
        'cancelVideoPlaylistTitle' => __( '&#8592; Cancel Video Playlist' ),
        'insertVideoPlaylist'      => __( 'Insert video playlist' ),
        'updateVideoPlaylist'      => __( 'Update video playlist' ),
        'addToVideoPlaylist'       => __( 'Add to video playlist' ),
        'addToVideoPlaylistTitle'  => __( 'Add to Video Playlist' ),
    );

    /**
     * Filters the media view settings.
     *
     * @since 3.5.0
     *
     * @param array   $settings List of media view settings.
     * @param WP_Post $post     Post object.
     */
    $settings = apply_filters( 'media_view_settings', $settings, $post );

    /**
     * Filters the media view strings.
     *
     * @since 3.5.0
     *
     * @param array   $strings List of media view strings.
     * @param WP_Post $post    Post object.
     */
    $strings = apply_filters( 'media_view_strings', $strings,  $post );

    $strings['settings'] = $settings;

    // Ensure we enqueue media-editor first, that way media-views is
    // registered internally before we try to localize it. see #24724.
    wp_enqueue_script( 'media-editor' );
    wp_localize_script( 'media-views', '_wpMediaViewsL10n', $strings );

    wp_enqueue_script( 'media-audiovideo' );
    wp_enqueue_style( 'media-views' );
    if ( is_admin() ) {
        wp_enqueue_script( 'mce-view' );
        wp_enqueue_script( 'image-edit' );
    }
    wp_enqueue_style( 'imgareaselect' );
    wp_plupload_default_settings();

    require_once ABSPATH . WPINC . '/media-template.php';
    add_action( 'admin_footer', 'wp_print_media_templates' );
    //add_action( 'wp_header', 'wp_print_media_templates' );
    add_action( 'customize_controls_print_footer_scripts', 'wp_print_media_templates' );

    /**
     * Fires at the conclusion of wp_enqueue_media().
     *
     * @since 3.5.0
     */
    do_action( 'wp_enqueue_media' );
}

//add_action( 'wp_loaded', 'go_wp_enqueue_media' );

/**
 * Resize All Images on Client Side
 */
function client_side_resize_load() {
    wp_enqueue_script( 'client-resize' , plugins_url( '../js/js/client-side-image-resize.js' , __FILE__ ) , array('media-editor' ) , '0.0.1' );
    wp_localize_script( 'client-resize' , 'client_resize' , array(
        'plupload' => array(
            'resize' => array(
                'enabled' => true,
                'width' => 1920, // enter your width here
                'height' => 1200, // enter your width here
                'quality' => 90,
            ),
        )
    ) );
}
add_action( 'wp_enqueue_media' , 'client_side_resize_load' );

/**
 * https://wordpress.stackexchange.com/questions/204779/how-can-i-add-an-author-filter-to-the-media-library
 *
 */
function go_media_add_author_dropdown(){
    $scr = get_current_screen();
    if ( $scr->base !== 'upload' ) return;
    if (current_user_can( 'manage_options' )) {
        $author = filter_input(INPUT_GET, 'author', FILTER_SANITIZE_STRING);
        $selected = (int)$author > 0 ? $author : '0';
        $args = array('show_option_all' => 'All Authors', 'name' => 'author', 'selected' => $selected);
        wp_dropdown_users($args);
    }
}
add_action('restrict_manage_posts', 'go_media_add_author_dropdown');



/**
 * Show Only Current Users Media
 * @param $wp_query
 * https://stackoverflow.com/questions/28787575/wordpress-restrict-users-to-see-only-their-uploads
 * This works on the grid view only.  There is a filter in the upload plugin that filters the list view.
 */
function go_my_files_only( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
        if ( !current_user_can('activate_plugins') && !current_user_can('edit_others_posts') ) {
            $user_id = get_current_user_id();
            $wp_query->set( 'author', $user_id );
        }
    }
}
add_filter('parse_query', 'go_my_files_only' );