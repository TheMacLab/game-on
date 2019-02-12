<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 12/22/18
 * Time: 5:35 AM
 */

//conditional includes

if ( !is_admin() ) {
    //include_once('public/public.php');
    include_once('src/public_ajax/go_shortcodes.php');
    include_once('src/public_ajax/go_locks.php');
    include_once('src/public_ajax/go_checks.php');

}else if ( defined( 'DOING_AJAX' )) {

    include_once('src/public_ajax/go_shortcodes.php');
    include_once('src/public_ajax/go_locks.php');
    include_once('src/public_ajax/go_checks.php');
    include_once('src/ajax/ajax.php');
    //include_once('admin/ajax/admin_ajax.php');


    add_action( 'wp_ajax_go_user_profile_link', 'go_user_profile_link' );
    add_action( 'wp_ajax_go_deactivate_plugin', 'go_deactivate_plugin' );
    add_action( 'wp_ajax_go_admin_remove_notification', 'go_admin_remove_notification' ); //OK
    add_action( 'wp_ajax_go_update_bonus_loot', 'go_update_bonus_loot' );//OK



}else{
    //include_once('admin/admin.php');
    //include_once('admin/ajax/admin_ajax.php');
    include_once('src/admin/go_datatable.php');
    include_once('src/admin/go_activation.php');
    include_once('src/admin/go_admin.php');

}




//always include
include_once('src/all/go_links.php');
include_once('src/all/go_media.php');
include_once('src/all/go_multisite.php');
include_once('src/all/go_transients.php');
include_once('src/all/go_mce.php');
include_once('src/all/go_loot_and_updates.php');
include_once('src/all/go_users.php');

/**
 * This places the mce in in hidden footer to be used later.
 */
function go_hidden_footer(){

    //ob_start();

    ?>
<div style="display: none;">
    <?php
    wp_editor('', 'initialize');
    ?>
</div>
<?php

    //wp_editor( '', 'initialize');
    //$editor = ob_get_clean(); // We do not need the editor on the page load so no echo.


}

