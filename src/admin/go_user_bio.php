<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 4/1/18
 * Time: 6:31 PM
 */

remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');

// Function to remove default bio field from user profile page & re-title the section
// ------------------------------------------------------------------
// Thanks to original code found here: https://wordpress.org/support/topic/remove-the-bio-section-from-user-profile
// More reference: http://wordpress.stackexchange.com/questions/49643/remove-personal-options-section-from-profile
// Alternate examples: http://wordpress.stackexchange.com/questions/38819/how-to-remove-biography-from-user-profile-admin-page


global $pagenow;
if ($pagenow == 'profile.php' || $pagenow == 'user-edit.php') {
    function go_remove_bio_box($buffer)
    {
        //global $pagenow;
        //if ($pagenow == 'profile.php' || $pagenow == 'user-edit.php') {

        $buffer = str_replace('<h2>About Yourself</h2>', '', $buffer);
        $buffer = preg_replace('/<tr class=\"user-description-wrap\"[\s\S]*?<\/tr>/', '', $buffer, 1);
        //$buffer = preg_replace('/<tr class=\"user-admin-color-wrap\"[\s\S]*?<\/tr>/','',$buffer,1);
        // $buffer = preg_replace('/<tr class=\"user-admin-bar-front-wrap\"[\s\S]*?<\/tr>/','',$buffer,1);
        return $buffer;
        // }
    }

    function go_user_profile_subject_start()
    {
        //global $pagenow;
        // if ($pagenow == 'profile.php' || $pagenow == 'user-edit.php') {
        ob_start('go_remove_bio_box');
        //}
    }

    function go_user_profile_subject_end()
    {

        ob_end_flush();

    }

    add_action('admin_head-profile.php', 'go_user_profile_subject_start');
    add_action('admin_footer-profile.php', 'go_user_profile_subject_end');


//https://wordpress.stackexchange.com/questions/49643/remove-personal-options-section-from-profile
// removes the `profile.php` admin color scheme options

    /**
     * Removes the leftover 'Visual Editor', 'Keyboard Shortcuts' and 'Toolbar' options.
     */
    function go_cor_remove_personal_options($subject)
    {
        $subject = preg_replace('#<h2>Personal Options</h2>.+?/table>#s', '', $subject, 1);
        return $subject;
    }

    function go_cor_profile_subject_start()
    {
        ob_start('go_cor_remove_personal_options');
    }

    function go_cor_profile_subject_end()
    {
        ob_end_flush();
    }

    add_action('admin_head-profile.php', 'go_cor_profile_subject_start');
    add_action('admin_footer-profile.php', 'go_cor_profile_subject_end');


//function go_user_profile_page (){
    /**
     * ACF User Profile Questions
     */


    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');

//this should be moved to a save action on the acf field
    if (!$use_gravatar) {
        update_option('show_avatars', 0);
    } else {
        update_option('show_avatars', 1);
    }

    if (!$use_local_avatars) {
        echo "
    
        <script>
        jQuery(document).ready(function(){
        jQuery('#go_local_avatar').remove();
        });
        </script>
    ";

        /// or put code to add the field!! see below
    }

    if (function_exists('acf_add_local_field_group')):

        $num_of_qs = get_option('options_go_user_profile_questions');
        $fields = array();
        $message = array('key' => 'field_user_message', 'label' => '', 'name' => '', 'type' => 'message', 'instructions' => '', 'required' => 0, 'conditional_logic' => 0, 'wrapper' => array('width' => '', 'class' => '', 'id' => '',), 'message' => 'Please provide the following information so we can get to know you better. These items and answers are only visible to site administrators.', 'new_lines' => 'wpautop', 'esc_html' => 0,);
        $fields[] = $message;
        $headshot = array('key' => 'field_5b4addf715427', 'label' => 'Headshot', 'name' => 'go_headshot', 'type' => 'image', 'instructions' => 'Please upload an actual photo of you.', 'required' => 0, 'conditional_logic' => 0, 'wrapper' => array('width' => '', 'class' => '', 'id' => '',), 'return_format' => 'id', 'preview_size' => 'thumbnail', 'library' => 'uploadedTo', 'min_width' => '', 'min_height' => '', 'min_size' => '', 'max_width' => '', 'max_height' => '', 'max_size' => '', 'mime_types' => '',);
        $fields[] = $headshot;
        for ($i = 0; $i < $num_of_qs; $i++) {
            $q_title = get_option('options_go_user_profile_questions_' . $i . '_title');
            $q_question = get_option('options_go_user_profile_questions_' . $i . '_question');
            $num_rows = get_option('options_go_user_profile_questions_' . $i . '_rows');
            $field_num = "field_" . $i;
            $field_name = "question_" . $i;
            $field = array('key' => $field_num, 'label' => $q_title, 'name' => $field_name, 'type' => 'textarea', 'instructions' => $q_question, 'required' => 0, 'conditional_logic' => 0, 'wrapper' => array('width' => '', 'class' => '', 'id' => '',), 'default_value' => '', 'placeholder' => '', 'maxlength' => '', 'rows' => $num_rows, 'new_lines' => '',);

            $fields[] = $field;
        }

        //echo "here";
        acf_add_local_field_group(array('key' => 'group_1', 'title' => 'About Me', 'fields' => $fields, 'location' => array(array(array('param' => 'user_form', 'operator' => '==', 'value' => 'all',),),), 'menu_order' => 1, 'position' => 'normal', 'style' => 'default', 'label_placement' => 'left', 'instruction_placement' => 'label', 'hide_on_screen' => '', 'active' => 1, 'description' => 'These items will show only for administrators.',));
        //echo $this;
        //acf_get_local_field( 'group_1' );
    endif;
}

//}
//add_action('show_user_profile', 'go_user_profile_page', 10, 1);
//add_action('edit_user_profile', 'go_user_profile_page', 10, 1);
//add_action('profile_personal_options', 'go_user_profile_page');
//"There is no equivalent hook at this point for injecting content onto the profile pages of non-current users."
//https://codex.wordpress.org/Plugin_API/Action_Reference/profile_personal_options

