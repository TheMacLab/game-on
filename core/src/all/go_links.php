<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 10/13/18
 * Time: 10:25 PM
 */

/**
 * @param $user_id
 * @param $on_stats
 * @param bool $website
 * @param bool $stats
 * @param bool $profile
 * @param bool $blog
 * @param bool $show_messages
 * @param bool $stats_lite
 * @param bool $clipboard
 * @param $website_link
 * @param $login
 */
function go_user_links($user_id, $website = true, $stats = false, $profile = false, $blog = false, $show_messages = false, $stats_lite = false , $clipboard = false, $website_link = null, $login = null ) {
    //if current user is admin, set all to true
    global $wpdb;

    if ($clipboard) { //if this is the clipboard
        $show_all = true;

    }else{
        $current_id = get_current_user_id();
        $is_admin = go_user_is_admin($current_id);
        //if this is an admin
        if ($is_admin){
            $show_all = true;
        }
        else if ($current_id == $user_id) {//if this is the current user
            $show_all = true;
        } else {
            $show_all = false;
        }
    }

    echo" <div class='go_user_links'>";



    if ($stats){
        if ($stats_lite) {
            echo "<div class='go_user_link'><a href='javascript:;' class='go_stats_lite' data-UserId='{$user_id}' onclick='go_stats_lite({$user_id});'><i class='fa fa-area-chart ab-icon' aria-hidden='true'></i></a></div>";
        }else{//regular stats link
            echo "<div class='go_user_link_stats go_user_link' name='{$user_id}'><a href='#';'><i class='fa fa-area-chart ab-icon' aria-hidden='true'></i></a></div>";
        }
    }
    if ($clipboard){
        echo "<div class='go_user_link go_user_map' name='{$user_id}'><a onclick='go_user_map({$user_id})' href='javascript:void(0);'><i class='fa fa-sitemap ab-icon' aria-hidden='true'></i></a></div>";

    }
    if ($profile && $show_all) {
        if($clipboard){
            echo "<div class='go_user_link'><a onclick='go_user_profile_link({$user_id})' href='javascript:void(0);' target='_blank'><i class='fa fa-user' aria-hidden='true'></i></a></div>";
        }else {
            $user_edit_link = get_edit_user_link($user_id);
            echo "<div class='go_user_link'><a href='$user_edit_link' target='_blank'><i class='fa fa-user' aria-hidden='true'></i></a></div>";
        }
    }
    if ($blog) {
        $blog_toggle = get_option('options_go_blogs_toggle');
        if ($blog_toggle) {
            if ($clipboard) {
                $info_login = $login;
            } else {
                $user_info = get_userdata($user_id);
                $info_login = $user_info->user_login;
            }
            $user_blog_link = get_site_url(null, '/user/' . $info_login);
            echo " <div class='go_user_link'><a href='$user_blog_link' target='_blank'><span class='dashicons dashicons-admin-post'></span></a></div>";

        }
    }
    if ($website){
        if (!$clipboard) {
            $user_obj = get_userdata($user_id);
            $website_link = $user_obj->user_url;//user website
        }
        if (!empty($website_link)) {
            echo " <div class='go_user_link'><a href='$website_link' target='_blank'><span class='dashicons dashicons-admin-site'></span></a></div>";
        }
    }

    if($show_messages && ($clipboard || $is_admin)){
        echo "<div class='go_stats_messages_icon go_user_link ' data-uid='" . $user_id . "' ><a href='#' ><i class='fa fa-bullhorn' aria-hidden='true'></i></a></div>";
        //make the messages icon a link to this user
    }
    echo "</div>";

}
//has ajax call
function go_user_profile_link(){
    $user_id = $_POST['uid'];
    $user_id = intval($user_id);

    $user_edit_link = get_edit_user_link($user_id);
    echo $user_edit_link;
    die();
}
/**
 * @param $check_type
 * @param $result
 * @param $stage
 * @param $time
 * @param $bonus
 * @return string
 */
function go_result_link($check_type, $result, $stage, $time, $bonus = false){
    if ($bonus){
        $stage = 'Bonus ' . $stage ;
    }
    else{
        $stage = 'Stage: ' . $stage ;
    }
    $link = '';
    if ($check_type == 'URL'){
        $link = "<a href='{$result}' class='tooltip' target='_blank'><span class=\"dashicons dashicons-admin-site\"></span><span class=\"tooltiptext\">{$stage} at <br> {$time}</span></a>";
    }
    else if ($check_type == 'upload'){
        $image_url = wp_get_attachment_url($result);
        $is_image = wp_attachment_is_image($result);
        if ($is_image) {
            $link = "<a href='#' class='tooltip' data-featherlight='{$image_url}'><span class=\"dashicons dashicons-format-image\"></span> <span class=\"tooltiptext\">{$stage} at <br> {$time}</span></a>";
        }else{
            $link = "<a href='{$image_url}' class='tooltip' target='_blank'><span class=\"dashicons dashicons-media-default\"></span><span class=\"tooltiptext\">{$stage} at <br> {$time}</span></a>";
        }
    }
    else if ($check_type == 'blog'){
        $link = "<a href='#' onclick='return false' id='$result' class='go_blog_lightbox tooltip' target='_blank'><span class=\"dashicons dashicons-admin-post\"></span><span class=\"tooltiptext\">{$stage} at <br> {$time}</span></a>";
        //$link = "<a href='javascript:;' id='$result' class='go_blog_lightbox' target='_blank'><span class=\"dashicons dashicons-admin-post\"></span>{$stage} at <br> {$time}</a>";

    }
    return $link;

}

/**
 * @param $check_type
 * @param $result
 * @param $stage
 * @param $time
 * @param $bonus
 * @return string
 */
function go_bonus_result_link($check_type, $result, $stage, $time, $bonus = true){
    if ($bonus){
        $stage = 'Bonus ' . $stage ;
    }
    else{
        $stage = 'Stage: ' . $stage ;
    }
    $link = '';
    if ($check_type == 'URL'){
        $link = "<a href='{$result}' class='tooltip' target='_blank'><span class=\"dashicons dashicons-admin-site\"></span>{$stage} at <br> {$time}</a>";
    }
    else if ($check_type == 'upload'){
        $image_url = wp_get_attachment_url($result);
        $is_image = wp_attachment_is_image($result);
        if ($is_image) {
            $link = "<a href='{#}' class='tooltip' data-featherlight='{$image_url}'><span class=\"dashicons dashicons-format-image\"></span>{$stage} at <br> {$time}</a>";
        }else{
            $link = "<a href='{$image_url}' class='tooltip' target='_blank'><span class=\"dashicons dashicons-media-default\"></span>{$stage} at <br> {$time}</a>";
        }
    }
    else if ($check_type == 'blog'){
        //echo "<a href='javascript:;' id='$result' class='go_blog_lightbox tooltip' target='_blank'><span class=\"dashicons dashicons-admin-post\"></span><span class=\"tooltiptext\">{$stage} at <br> {$time}</span></a>";
        $link = "<a href='javascript:;' id='$result' class='go_blog_lightbox' target='_blank'><span class=\"dashicons dashicons-admin-post \"></span>{$stage} at {$time}</a>";

    }
    return $link;

}

//this then uses select2 and ajax to make dropdown
function go_make_tax_select ($taxonomy, $location = null, $selector = 'id'){

    echo "<select ".$selector."='go_". $location . $taxonomy . "_select'></select>";

}

