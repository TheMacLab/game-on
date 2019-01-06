<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */

//conditional includes
if ( !is_admin() ) {
    include_once('src/public_ajax.php');
    //include_once('public/public.php');
}else if ( defined( 'DOING_AJAX' )) {
    include_once('src/public_ajax.php');
}else{
    //include_once('admin/admin.php');
}

//always include
//include_once('src/functions.php');