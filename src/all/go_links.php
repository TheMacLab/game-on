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
 */
function go_user_links($user_id, $on_stats, $website = true, $stats = false, $profile = false, $blog = false, $show_messages = false) {
    //if current user is admin, set all to true
    $current_id = get_current_user_id();
    $is_admin = go_user_is_admin($current_id);
    if($is_admin){
        $stats = true;
    }
    $user_obj = get_userdata($user_id);
    echo" <div class='go_user_links'>";

    if ($stats && !$on_stats){
        echo "<div class='go_user_link_stats go_user_link' name='{$user_id}'><a href='#';'><i class='fa fa-area-chart ab-icon' aria-hidden='true'></i></a></div>";
    }
    if ($profile){
        $user_edit_link = get_edit_user_link( $user_id  );
        echo "<div class='go_user_link'><a href='$user_edit_link' target='_blank'><i class='fa fa-user' aria-hidden='true'></i></a></div>";
    }
    if ($blog){
        $blog_toggle = get_option('options_go_blogs_toggle');
        if($blog_toggle){
            $user_info = get_userdata($user_id);
            $userloginname = $user_info->user_login;
            $user_blog_link = get_site_url(null, '/user/' . $userloginname);
            echo" <div class='go_user_link'><a href='$user_blog_link' target='_blank'><span class='dashicons dashicons-admin-post'></span></a></div>";
        }
    }
    if ($website){
        $user_website = $user_obj->user_url;//user website
        if (!empty($user_website)) {
            echo " <div class='go_user_link'><a href='$user_website' target='_blank'><span class='dashicons dashicons-admin-site'></span></a></div>";
        }
    }
    if($is_admin && $show_messages && $on_stats){
        echo "<div id='go_stats_messages_icon_stats' class='go_stats_messages_icon go_user_link ' name='{$user_id}'><a href='#' ><i class='fa fa-bullhorn' aria-hidden='true'></i></a></div>";
        //make the messages icon a link to this user
        echo '<script>console.log("on_stats");  jQuery(".go_stats_messages_icon").one("click", function(e){ var user_id = jQuery(this).attr("name"); go_messages_opener(user_id);}); </script>';
    }
    else if($is_admin && $show_messages ){
        echo "<div id='go_stats_messages_icon_blog' class='go_stats_messages_icon go_user_link ' name='{$user_id}'><a href='#' ><i class='fa fa-bullhorn' aria-hidden='true'></i></a></div>";
        //make the messages icon a link to this user
        echo '<script>console.log("on_blog"); jQuery(".go_stats_messages_icon").one("click", function(e){ var user_id = jQuery(this).attr("name"); go_messages_opener(user_id); }); </script>';
        echo "<script>  jQuery('.go_user_link_stats').one('click', function(){  var user_id = jQuery(this).attr('name'); go_admin_bar_stats_page_button(user_id)}); </script>";
    }
    echo "</div>";

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


function go_make_tax_select ($taxonomy, $title = "Show All", $location = null, $saved_val = null, $multiple = false){
    $is_hier = is_taxonomy_hierarchical( $taxonomy );
    if ($multiple == true){
        $multiple = "multiple='multiple'";
    }
    echo "<select id='go_". $location . $taxonomy . "_select' class='go_tax_select' name='" . $taxonomy . "[]' " . $multiple . ">";

    echo "<option value='none' ><b>" . $title . "</b></option>";
    if ($is_hier) {
        $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => '0');

        //parent terms
        $parents = get_terms($taxonomy, $args);

        foreach ( $parents as $parent ) {
            //$row_id = $parent->term_id;

            $option =  "<optgroup label='" . $parent->name . "' >" ;
            echo $option;
            $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => $parent->term_id);
            //children terms
            $children = get_terms($taxonomy, $args);
            foreach ( $children as $child ) {
                if ($saved_val[0] == $child->term_id ){
                    $selected = "selected";
                }else{$selected = null;}
                echo "<option value='" . $child->term_id . "' " . $selected . ">" . $child->name . "</option>";
                echo "";
            }
            echo "</optgroup>";

        }

    }else{
        $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC');
        //children terms
        $children = get_terms($taxonomy, $args);
        foreach ( $children as $child ) {
            if ($saved_val[0] == $child->term_id ){
                $selected = "selected";
            }else{$selected = null;}
            echo "<option value='" . $child->term_id . "' " . $selected . ">" . $child->name . "</option>";
        }
    }
    echo "</select>";
}
