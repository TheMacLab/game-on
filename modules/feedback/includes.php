<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */

//conditional includes
if ( !is_admin() ) {
    // include_once('public/public.php');


}else if ( defined( 'DOING_AJAX' )) {
    include_once('src/ajax.php');
    include_once('src/admin_ajax.php');


}else{
    include_once('src/admin.php');
    include_once('src/admin_ajax.php');
}

//always include
include_once('src/functions.php');