<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */



foreach ( glob( plugin_dir_path( __FILE__ ) . "*.php" ) as $file ) {
    include_once $file;
}

if ( is_admin() ) {

    foreach ( glob( plugin_dir_path( __FILE__ ) . "admin/*.php" ) as $file ) {
        include_once $file;
    }

    foreach ( glob( plugin_dir_path( __FILE__ ) . "admin/ajax/*.php" ) as $file ) {
        include_once $file;
    }

}else if ( defined( 'DOING_AJAX' )) {

    foreach ( glob( plugin_dir_path( __FILE__ ) . "ajax/*.php" ) as $file ) {
        include_once $file;
    }

    foreach ( glob( plugin_dir_path( __FILE__ ) . "public/ajax/*.php" ) as $file ) {
        include_once $file;
    }

    foreach ( glob( plugin_dir_path( __FILE__ ) . "admin/ajax/*.php" ) as $file ) {
        include_once $file;
    }

}else{

    foreach ( glob( plugin_dir_path( __FILE__ ) . "public/*.php" ) as $file ) {
        include_once $file;
    }

    foreach ( glob( plugin_dir_path( __FILE__ ) . "public/ajax/*.php" ) as $file ) {
        include_once $file;
    }
}
