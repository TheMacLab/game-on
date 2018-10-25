<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 11:28 PM
 */

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