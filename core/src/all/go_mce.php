<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 8:28 PM
 */

//these are the text filters
//https://themehybrid.com/weblog/how-to-apply-content-filters
add_filter( 'go_awesome_text', 'wptexturize'       );
add_filter( 'go_awesome_text', 'convert_smilies'   );
add_filter( 'go_awesome_text', 'convert_chars'     );
add_filter( 'go_awesome_text', 'wpautop'           );
add_filter( 'go_awesome_text', 'shortcode_unautop' );
add_filter( 'go_awesome_text', 'do_shortcode'      );
add_filter( 'go_awesome_text', 'go_oembed_text' );


//the go_awesome_text filter uses this to embed content
function go_oembed_text($content)
{
    $content = $GLOBALS['wp_embed']->autoembed($content);
    return $content;
}

/*
Plugin Name: Frameitron
Plugin URI: http://ninnypants.com
Description: Allow iframes in tinymce for all user levels
Version: 1.0
Author: ninnypants
Author URI: http://ninnypants.com
License: GPL2
Copyright 2013  Tyrel Kelsey  (email : tyrel@ninnypants.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action( 'init', 'go_frame_it_up' );
add_filter( 'tiny_mce_before_init', 'go_frame_it_up_tinymce' );
function go_frame_it_up( $init_array ){
    global $allowedtags, $allowedposttags;
    $allowedposttags['iframe'] = $allowedtags['iframe'] = array(
        'name' => true,
        'id' => true,
        'class' => true,
        'style' => true,
        'src' => true,
        'width' => true,
        'height' => true,
        'allowtransparency' => true,
        'frameborder' => true,
    );
}


function go_frame_it_up_tinymce( $init_array ){
    if( isset( $init_array['extended_valid_elements'] ) )
        $init_array['extended_valid_elements'] .= ',iframe[id|name|class|style|src|width|height|allowtransparency|frameborder]';
    else
        $init_array['extended_valid_elements'] = 'iframe[id|name|class|style|src|width|height|allowtransparency|frameborder]';
    return $init_array;
}



//This allows all users to use oembed in the WYSIWYG
function override_caps($allcaps){
    $post_action = (isset($_POST['action']) ?  $_POST['action'] : null);

    if ( $post_action == 'parse-embed' ){// override capabilities when embedding content in WYSIWIG
        $role_name = 'administrator';
        $role = get_role($role_name); // Get the role object by role name
        $allcaps = $role->capabilities;  // Get the capabilities for the role
        $allcaps['contributor'] = true;     // Add role name to capabilities
    }
    return $allcaps;
}
add_filter( 'user_has_cap', 'override_caps' );


function go_changeMceDefaults($in) {

    // customize the buttons
    $in['theme_advanced_buttons1'] = 'bold,italic,underline,bullist,numlist,hr,blockquote,link,unlink,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent';
    $in['theme_advanced_buttons2'] = 'formatselect,pastetext,pasteword,charmap,undo,redo';

    // Keep the "kitchen sink" open
    $in[ 'wordpress_adv_hidden' ] = FALSE;

    $in[ 'menubar' ] = FALSE;
    return $in;

}
add_filter( 'tiny_mce_before_init', 'go_changeMceDefaults' );







//*******************************************************************************************
// Load tinymce wordcount
//*******************************************************************************************

add_filter('mce_external_plugins', 'go_tinymce_wordcount');

function go_tinymce_wordcount($plugins_array = array())
{
    $plugins = array('wordcount');
    //Build the response - the key is the plugin name, value is the URL to the plugin JS
    foreach ($plugins as $plugin )
    {
        $plugins_array[ $plugin ] = plugins_url('tinymce/', __FILE__) . $plugin . '/wordcount.js';
    }
    return $plugins_array;
}


