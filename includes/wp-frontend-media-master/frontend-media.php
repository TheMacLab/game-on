<?php

/**
 * Plugin Name:       Front-end Media Example
 * Plugin URI:        http://derekspringer.wordpress.com
 * Description:       An example of adding the media loader on the front-end.
 * Version:           0.1
 * Author:            Derek Springer
 * Author URI:        http://derekspringer.wordpress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       frontend-media
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class wrapper for Front End Media example
 */
class Front_End_Media {

	/**
	 * A simple call to init when constructed
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init the textdomain and all the the hooks/filters/etc
	 */
	function init() {
		load_plugin_textdomain(
			'frontend-media',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_go_media_filter_ajax', 'go_media_filter_ajax' ); //OK
		add_filter( 'ajax_query_attachments_args', array( $this, 'filter_media' ) );
		add_shortcode( 'frontend-button', array( $this, 'frontend_shortcode' ) );
        add_shortcode( 'frontend_submitted_media', array( $this, 'frontend_shortcode_submitted' ) );
	}

	/**
	 * Call wp_enqueue_media() to load up all the scripts we need for media uploader
     */
	function enqueue_scripts() {
		wp_enqueue_media();


        wp_register_script( 'go_frontend_media', plugin_dir_url( __FILE__ ).'wp-frontend-media-master/js/frontend.js', array( 'jquery' ),
            '2015-05-07', true);
        wp_enqueue_script( 'go_frontend_media' );
	}

	/**
	 * This filter insures users only see their own media
	 */
	function filter_media( $query ) {
		// admins get to see everything
		if ( ! current_user_can( 'manage_options' ) ) {
            $query['author'] = get_current_user_id();
        }
		return $query;
	}

	function frontend_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'div_id' => '',
            'mime_types' => ''
        ), $atts);
        $div_id = $atts['div_id'];
        $mime_types = $atts['mime_types'];
		// check if user can upload files
        $role = get_role( 'subscriber' );
        $role->add_cap( 'upload_files' );
		if ( current_user_can( 'read' ) ) {
			$str = __( 'Select File', 'frontend-media' );
			return '<img id="'.$div_id.'" /><div id="go_upload_button"><input class="go_frontend-button" type="button" onclick="go_upload_frontend(\''.$div_id.'\', \''.$mime_types.'\');" value="' . $str . '" class="button" style="position: relative; z-index: 1;"></div>';
		}

		return __( 'Please Login To Upload', 'frontend-media' );
	}
    function frontend_shortcode_submitted( $atts ) {

        $atts = shortcode_atts( array(
            'id' => '', // ID defined in Shortcode
            'div_id' => '',
            'mime_types' => ''
        ), $atts);
        $div_id = $atts['div_id'];
        $media_id = $atts['id'];
        $mime_types = $atts['mime_types'];
        // check if user can upload files
        $role = get_role( 'subscriber' );
        $role->add_cap( 'upload_files' );


        if ( current_user_can( 'read' ) ) {

            $type = get_post_mime_type($media_id);

            //return $icon;
            switch ($type) {
                case 'image/jpeg':
                case 'image/png':
                case 'image/gif':
                    $type_image = true;
                    break;
                default:
                    $type_image = false;
            }
            echo "<div class='go_required_blog_content'>";
            if ($type_image == true){
                $med = wp_get_attachment_image_src( $media_id, 'medium' );
                $full = wp_get_attachment_image_src( $media_id, 'full' );
                $str = __( 'Change File', 'frontend-media' );
                echo '<a href="#" data-featherlight="' . $full[0] . '"><img id="'.$div_id.'" src="' . $med[0] . '" value="'.$media_id.'"></a>';
                //return '<img id="'.$div_id.'" src="'.$attachment_url.'" value="'.$media_id.'" /><div id="go_upload_button"><input id="frontend-button" type="button" onclick="go_upload_frontend(\''.$div_id.'\', \''.$mime_types.'\');" value="' . $str . '" class="button" style="position: relative; z-index: 1;"></div>';
            }
            else{
                // $img = wp_mime_type_icon($type);
                //echo "<img src='" . $img . "' >";
                $url = wp_get_attachment_url( $media_id );
                $thumb = wp_get_attachment_image_src( $media_id, 'thumbnail',true );
                $str = __( 'Change File', 'frontend-media' );
                echo "<img id='".$div_id."' src='" . $thumb[0] . "' value='".$media_id."' >" ;
                echo "<div>" . get_the_title($media_id) . "</div>" ;

            }

            return '<div id="go_upload_button"><input class="go_frontend-button" type="button" onclick="go_upload_frontend(\''.$div_id.'\', \''.$mime_types.'\');" value="' . $str . '" class="button" style="position: relative; z-index: 1;"></div></div>';

        }

        return __( 'Please Login To See Media', 'frontend-media' );
    }

}

new Front_End_Media();

function go_media_filter_ajax(){
    $user_id = get_current_user_id();
    $mime_types = !empty($_POST['mime_types']) ? (string)$_POST['mime_types'] : '';
    update_user_option( $user_id, 'go_media_filter', $mime_types );

}

add_filter('wp_handle_upload_prefilter', 'go_import_upload_prefilter');
function go_import_upload_prefilter($file)
{
    //$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if(!is_admin()) {
        $type = $file['type'];
        $user_id = get_current_user_id();
        $mime_types = get_user_option('go_media_filter', $user_id);
        $mime_types_array = explode(",", $mime_types);
        $mime_types_pretty = implode(", ", $mime_types_array);

        $match = false;
        foreach ($mime_types_array as $mime_type) {
            if (substr($type, 0, strlen($mime_type)) === $mime_type) {
                $match = true;
                break;

            }
        }

        if (!$match) {
            $file['error'] = "The uploaded file is not supported. Allowed file types: " . $mime_types_pretty;
        }

    }
    return $file;
}