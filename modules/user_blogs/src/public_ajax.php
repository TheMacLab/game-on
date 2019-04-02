<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 2019-04-01
 * Time: 23:53
 */

//add_filter( 'wp_default_editor', create_function('', 'return "tinymce";'));
add_filter( 'wp_default_editor', function() {return 'tinymce';});
