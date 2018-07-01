<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/1/18
 * Time: 6:31 PM
 */

$use_local_avatars = get_option('options_go_avatars_local');
$use_gravatar = get_option('options_go_avatars_gravatars');

if (!$use_gravatar) {
    update_option( 'show_avatars', 0 );
}
else{
    update_option( 'show_avatars', 1 );
}

if (!$use_local_avatars){
    ///put code to hide local avatars
    /// js to remove id
    ///
}





// Callback function to remove default bio field from user profile page & re-title the section
// ------------------------------------------------------------------
// Thanks to original code found here: https://wordpress.org/support/topic/remove-the-bio-section-from-user-profile
// More reference: http://wordpress.stackexchange.com/questions/49643/remove-personal-options-section-from-profile
// Alternate examples: http://wordpress.stackexchange.com/questions/38819/how-to-remove-biography-from-user-profile-admin-page

if(!function_exists('remove_plain_bio')){
    function remove_bio_box($buffer){
        $buffer = str_replace('<h2>About Yourself</h2>','',$buffer);
        $buffer = preg_replace('/<tr class=\"user-description-wrap\"[\s\S]*?<\/tr>/','',$buffer,1);
        //$buffer = preg_replace('/<tr class=\"user-admin-color-wrap\"[\s\S]*?<\/tr>/','',$buffer,1);
        // $buffer = preg_replace('/<tr class=\"user-admin-bar-front-wrap\"[\s\S]*?<\/tr>/','',$buffer,1);
        return $buffer;
    }
    function user_profile_subject_start(){ ob_start('remove_bio_box'); }
    function user_profile_subject_end(){ ob_end_flush(); }
}
add_action('admin_head-profile.php','user_profile_subject_start');
add_action('admin_footer-profile.php','user_profile_subject_end');


//https://wordpress.stackexchange.com/questions/49643/remove-personal-options-section-from-profile
// removes the `profile.php` admin color scheme options
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

if ( ! function_exists( 'cor_remove_personal_options' ) ) {
    /**
     * Removes the leftover 'Visual Editor', 'Keyboard Shortcuts' and 'Toolbar' options.
     */
    function cor_remove_personal_options( $subject ) {
        $subject = preg_replace( '#<h2>Personal Options</h2>.+?/table>#s', '', $subject, 1 );
        return $subject;
    }

    function cor_profile_subject_start() {
        ob_start( 'cor_remove_personal_options' );
    }

    function cor_profile_subject_end() {
        ob_end_flush();
    }
}
add_action( 'admin_head-profile.php', 'cor_profile_subject_start' );
add_action( 'admin_footer-profile.php', 'cor_profile_subject_end' );