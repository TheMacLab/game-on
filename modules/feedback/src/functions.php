<?php

// Included on every load of this module


//https://stackoverflow.com/questions/25310665/wordpress-how-to-create-a-rewrite-rule-for-a-file-in-a-custom-plugin
add_action('init', 'go_reader_page');
function go_reader_page(){
    //add_rewrite_rule( "store", 'index.php?query_type=user_blog&uname=$matches[1]', "top");
    add_rewrite_rule( "reader", 'index.php?reader=true', "top");
}

/* Query Vars */
add_filter( 'query_vars', 'go_reader_register_query_var' );
function go_reader_register_query_var( $vars ) {
    $vars[] = 'reader';
    return $vars;
}

/* Template Include */
add_filter('template_include', 'go_reader_template_include', 1, 1);
function go_reader_template_include($template)
{
    global $wp_query; //Load $wp_query object


    $page_value = ( isset($wp_query->query_vars['reader']) ? $wp_query->query_vars['reader'] : false ); //Check for query var "blah"

    if ($page_value && $page_value == "true") { //Verify "blah" exists and value is "true".
        return plugin_dir_path(__FILE__).'templates/go_reader_template.php'; //Load your template or file
    }

    return $template; //Load normal template when $page_value != "true" as a fallback
}

add_action('go_blog_template_after_post', 'go_user_feedback_container', 10, 2);

function go_user_feedback_container($post_id, $show_form = false){
    $admin_user = go_user_is_admin();

    go_task_status_icon($post_id);
    go_blog_is_private($post_id);
    go_blog_favorite($post_id);
    //go_blog_tags_select($post_id);
    ?>
    <div class="feedback_accordion">
        <h3>Feedback</h3>
        <div><?php go_user_feedback($post_id); ?></div>
        <h3>Revision History</h3>
        <div>Second content panel</div>
        <?php
        if ($admin_user && $show_form) {
            ?>
            <h3>Feedback Form</h3>
            <div>
            <?php
            go_feedback_form($post_id);
            ?>
            </div>
            <?php

        }
        ?>
    </div>
    <?php



}

function go_user_feedback($post_id){
    ?>
    <div class="go_user_feedback">

        <div class="go_feedback_table_container">
            <div class="go_feedback_table">
                <?php
                go_feedback_table($post_id);
                ?>
            </div>
        </div>
        <div class="go_history_table_container">
            <div class="go_history_table">
                <?php
                //go_blog_post_history_table($post_id);
                ?>
            </div>
        </div>
    </div>
    <?php

}

function go_feedback_form($post_id){
    ?>
        <div class="go_feedback_form">
            <div class="go_blog_favorite">
                <?php
                go_is_blog_favorite($post_id);
                ?>
            </div>
            <div class="go_feedback_canned">
                <?php go_feedback_canned(); ?>
            </div>
            <div class="go_feedback_input">
                <?php go_feedback_input(); ?>
            </div>
            <div class="go_feedback_loot">
                <?php //go_feedback_loot(); ?>
            </div>
            <div class="go_feedback_flags">
                <?php //go_feedback_flags(); ?>
            </div>
        </div>
    <?php
}

function go_task_status_icon($post_id){
    $status = get_post_status($post_id);
    $icon ='';
    if ($status == 'read'){
        $icon ='<i class="fa fa-eye" aria-hidden="true"></i>';
    }else if ($status == 'reset'){
        $icon ='<i class="fa fa-times-circle" aria-hidden="true"></i>';
    }else if ($status == 'revise'){
        $icon ='<i class="fa fa-exclamation-circle" aria-hidden="true"></i>';
    }else if ($status == 'draft'){
        $icon ='<span>DRAFT</span>';
    }
    echo '<div class="go_status_icon" >'.$icon.'</div>';
}

function go_blog_is_private($post_id){

    //$blog_meta = get_post_custom($post_id);
    //$status = (isset($blog_meta['go_blog_private_post'][0]) ? $blog_meta['go_blog_private_post'][0] : false);
    $status = get_post_meta($post_id, 'go_blog_private_post', true );
    if ($status) {

        //$status = get_post_status($post_id);
        echo '<div class="go_blog_visibility" ><i class="fa fa-user-secret" aria-hidden="true"></i></div>';
    }
}

function go_blog_favorite($post_id){

        $status = get_post_meta($post_id, 'go_blog_favorite', true );
        if ($status == 'true'){
            $checked = 'checked';
        }else{
            $checked = '';
        }
        //echo "<div style=''><input type='checkbox' class='go_blog_favorite ' value='go_blog_favorite' data-post_id='{$post_id}' {$checked}> Favorite</div>";
        ?>
    <div class="go_favorite_container">
        <label>
            <?php
        echo "<input type='checkbox' class='go_blog_favorite ' value='go_blog_favorite' data-post_id='{$post_id}' {$checked}>";
        ?>
        <span class="go_favorite_label"></span>
        </label>
    </div>
    <?php
}

function go_blog_favorite_toggle(){
    check_ajax_referer( 'go_blog_favorite_toggle' );
    $post_id = !empty($_POST['blog_post_id']) ? intval($_POST['blog_post_id']) : false;
    $status = !empty($_POST['checked']) ? $_POST['checked'] : false;
    update_post_meta( $post_id, 'go_blog_favorite', $status);


}

function go_blog_tags_select($post_id){
    ?>
    <form name="primaryTagForm" id="primaryTagForm" method="POST" enctype="multipart/form-data" >
        <fieldset>
            <span><label for="go_feedback_go_blog_tags_select">Tags </label><?php go_make_tax_select('_go_blog_tags' , "feedback", 'class'); ?></span>
            <button class="button" type="submit"><?php _e('Tag ', 'framework') ?></button>
        </fieldset>
    </form>
    <?php
}

function go_feedback_table($post_id){

}

function go_blog_post_history_table($post_id){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_actions";

    $custom_fields = get_post_custom( $post_id );
    //$task_id = (isset($custom_fields['go_blog_task_id'][0]) ?  $custom_fields['go_blog_task_id'][0] : null);
    $task_id = wp_get_post_parent_id($post_id);
    $user_id = get_post_field ('post_author', $post_id);

    //check_ajax_referer( 'go_blog_post_history_table' );
    //$post_id = (int) $_POST['postID'];

    $task_name = get_option('options_go_tasks_name_singular');
    $tasks_name = get_option('options_go_tasks_name_plural');

    $actions = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * 
        FROM {$go_task_table_name} 
        WHERE uid = %d and source_id = %d",
            $user_id,
            $task_id
        )
    );
    $post_title = get_the_title($task_id);
    echo "<div id='go_task_list_single' class='go_datatables'>
        <div style='float: right;'><a onclick='go_close_single_history()' href='javascript:void(0);'><i class='fa fa-times ab-icon' aria-hidden='true'></i> Show All $tasks_name</a></div>
        <h3>Single $task_name History: $post_title</h3>

        <table id='go_single_task_datatable' class='pretty display'>
               <thead>
                    <tr>
                    
                        <th class='header' id='go_stats_time'><a href=\"#\">Time</a></th>
                        <th class='header' id='go_stats_action'><a href=\"#\">Action</a></th>
                        <th class='header' id='go_stats_post_name'><a href=\"#\">Stage</a></th>
                        <th class='header' id='go_stats_mods'><a href=\"#\">Modifiers</a></th>";
    go_loot_headers();
    //go_loot_headers(true);
    echo"
                    </tr>
                    </thead>
            <tbody>
                    ";
    foreach ( $actions as $action ) {
        $action_type = $action->action_type;
        $source_id = $action->source_id;
        $TIMESTAMP = $action->TIMESTAMP;
        $time  = date("m/d/y g:i A", strtotime($TIMESTAMP));
        $stage = $action->stage;
        $bonus_status = $action->bonus_status;
        $result = $action->result;
        $quiz_mod = $action->quiz_mod;
        $late_mod = $action->late_mod;
        $timer_mod = $action->timer_mod;
        $health_mod = $action->global_mod;
        $xp = $action->xp;
        $gold = $action->gold;
        $health = $action->health;
        $xp_total = $action->xp_total;
        $gold_total = $action->gold_total;
        $health_total = $action->health_total;

        $post_title = get_the_title($source_id);


        if ($action_type == 'admin'){
            $type = "Admin";
        }
        if ($action_type == 'reset'){
            $type = "Reset";
        }

        if ($action_type == 'store'){
            $store_qnty = $stage;
            $type = strtoupper( get_option( 'options_go_store_name' ) );
            $post_title = "Qnt: " . $store_qnty . " of " . $post_title ;
        }

        if ($action_type == 'task'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                $type = 'Continue';
                $post_title = " Stage: " . $stage;
            }
        }

        if ($action_type == 'undo_task'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                $type = "Undo";
                $post_title = " Stage: " . $stage;
            }
        }
        if ($result == 'undo_bonus'){
            $type = "Undo Bonus";
            $post_title = $post_title . " Bonus: " . $bonus_status ;
        }

        $quiz_mod_int = intval($quiz_mod);
        if (!empty($quiz_mod_int)){
            $quiz_mod = "<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i> ". $late_mod;
        }
        else{
            $quiz_mod = null;
        }

        $late_mod_int = intval($late_mod);
        if (!empty($late_mod_int)){
            $late_mod = "<i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> ". $late_mod;
        }
        else{
            $late_mod = null;
        }

        $timer_mod_int = intval($timer_mod);
        if (!empty($timer_mod_int)){
            $timer_mod = "<i class=\"fa fa-hourglass\" aria-hidden=\"true\"></i> ". $timer_mod;
        }
        else{
            $timer_mod = null;
        }

        $health_mod_int = intval($health_mod);
        if (!empty($health_mod_int)){
            $health_abbr = get_option( "options_go_loot_health_abbreviation" );
            $health_mod_str = $health_abbr . ": ". $health_mod;
        }
        else{
            $health_mod_str = null;
        }

        echo " 			
                <tr id='postID_{$source_id}'>
                    <td data-order='{$TIMESTAMP}'>{$time}</td>
                    <td>{$type} </td>
                    <td>{$post_title} </td>
                    <td>{$health_mod_str}   {$timer_mod}   {$late_mod}   {$quiz_mod}</td>
                    
                    <td>{$xp}</td>
                    <td>{$gold}</td>
                    <td>{$health}</td>
                </tr>
                ";


    }
    echo "</tbody>
            </table></div>";

    die();

}

function go_is_blog_favorite($post_id){

}

function go_feedback_canned(){
    echo "<select>";
    echo "<option>Canned Feedback</option>";
    $num_preset = get_option('options_go_feedback_canned');
    $i = 0;
    while ($i < 3){
        $title = get_option('options_go_feedback_canned_'.$i.'_title');
        $message = get_option('options_go_feedback_canned_'.$i.'_message');
        $xp = get_option('options_go_feedback_canned_'.$i.'_defaults_xp');
        $gold = get_option('options_go_feedback_canned_'.$i.'_defaults_gold');
        $health = get_option('options_go_feedback_canned_'.$i.'_defaults_health');
        echo "<option value='{$i}'>{$title} </option>";
        $i++;
    }
    echo "</select>";

}

function go_feedback_input(){

    ?>
    <div id="go_messages_container">
        <form method="post">
            <div id="go_messages" style="display:flex;">

                <div id="messages_form">
                    <table class="form-table">

                        <tr valign="top">
                            <th scope="row">Title</th>
                            <td style="width: 100%;"><input type="text" name="title" value="" style="width: 100%;"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Message</th>
                            <td><textarea name="message" class="widefat" cols="50" rows="5"></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row">Loot</th>
                            <td>
                                <div id="go_loot_table" class="go-acf-field go-acf-field-group" data-type="group">
                                    <div class="go-acf-input">
                                        <div class="go-acf-fields -top -border">
                                            <div class="go-acf-field go-acf-field-group go-acf-hide-label go-acf-no-padding go-acf-table-no-border"
                                                 data-name="reward_toggle" data-type="group">
                                                <div class="go-acf-input">
                                                    <table class="go-acf-table">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="go-acf-th">
                                                                    <label>XP</label></div>
                                                            </th>
                                                            <th>
                                                                <div class="go-acf-th">
                                                                    <label>Gold</label></div>
                                                            </th>
                                                            <th>
                                                                <div class="go-acf-th">
                                                                    <label>Health</label></div>
                                                            </th>

                                                        </tr>


                                                        </thead>
                                                        <tbody>
                                                        <tr class="go-acf-row">
                                                            <td class="go-acf-field go-acf-field-true-false go_reward go_xp"
                                                                data-name="xp" data-type="true_false">
                                                                <div class="go-acf-input">
                                                                    <div class="go-acf-true-false">
                                                                        <input value="0" type="hidden">
                                                                        <label>
                                                                            <input name="xp_toggle" type="checkbox" value="1"
                                                                                   class="go-acf-switch-input">
                                                                            <div class="go-acf-switch"><span class="go-acf-switch-on"
                                                                                                             style="min-width: 36px;">+</span><span
                                                                                        class="go-acf-switch-off"
                                                                                        style="min-width: 36px;">-</span>
                                                                                <div class="go-acf-switch-slider"></div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="go-acf-field go-acf-field-true-false go_reward go_gold"
                                                                data-name="gold" data-type="true_false">
                                                                <div class="go-acf-input">
                                                                    <div class="go-acf-true-false">
                                                                        <input value="0" type="hidden">
                                                                        <label>
                                                                            <input name="gold_toggle" type="checkbox"
                                                                                   class="go-acf-switch-input">
                                                                            <div class="go-acf-switch"><span class="go-acf-switch-on"
                                                                                                             style="min-width: 36px;">+</span><span
                                                                                        class="go-acf-switch-off"
                                                                                        style="min-width: 36px;">-</span>
                                                                                <div class="go-acf-switch-slider"></div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="go-acf-field go-acf-field-true-false go_reward go_health"
                                                                data-name="health" data-type="true_false">
                                                                <div class="go-acf-input">
                                                                    <div class="go-acf-true-false">
                                                                        <input value="0" type="hidden">
                                                                        <label>
                                                                            <input name="health_toggle" type="checkbox"
                                                                                   value="1" class="go-acf-switch-input">
                                                                            <div class="go-acf-switch"><span class="go-acf-switch-on"
                                                                                                             style="min-width: 36px;">+</span><span
                                                                                        class="go-acf-switch-off"
                                                                                        style="min-width: 36px;">-</span>
                                                                                <div class="go-acf-switch-slider"></div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr class="go-acf-row">
                                                            <td class="go-acf-field go-acf-field-number go_reward go_xp  data-name="
                                                                xp
                                                            " data-type="number">
                                                            <div class="go-acf-input">
                                                                <div class="go-acf-input-wrap"><input name="xp" type="number"
                                                                                                      value="0" min="0" step="1" oninput="validity.valid||(value='');">
                                                                </div>
                                                            </div>
                                                            </td>
                                                            <td class="go-acf-field go-acf-field-number go_reward go_gold"
                                                                data-name="gold" data-type="number">
                                                                <div class="go-acf-input">
                                                                    <div class="go-acf-input-wrap"><input name="gold" type="number"
                                                                                                          value="0" min="0"
                                                                                                          step="1" oninput="validity.valid||(value='');"></div>
                                                                </div>
                                                            </td>
                                                            <td class="go-acf-field go-acf-field-number go_reward go_health "
                                                                data-name="health" data-type="number">
                                                                <div class="go-acf-input">
                                                                    <div class="go-acf-input-wrap"><input name="health"
                                                                                                          type="number" value="0"
                                                                                                          min="0" step=".01" oninput="validity.valid||(value='');"></div>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </table>

                    <div>

                        <input type="checkbox" class="favorite" name="favorite"
                               >
                        <label for="scales">Favorite</label>
                        <input type="checkbox" class="reset" name="reset"
                               >
                        <label for="scales">Reset</label>

                    </div>
                    <p class="go_message_submit"><input type="button" id="go_message_submit"
                                                        class="button button-primary" value="Send"></p>
                </div>


            </div>
        </form>

    </div>
    <script>
        jQuery( document ).ready( function() {
            jQuery('.go-acf-switch').click(function () {
                console.log("click");
                if (jQuery(this).hasClass('-on') == false) {
                    jQuery(this).prev('input').prop('checked', true);
                    jQuery(this).addClass('-on');
                    jQuery(this).removeClass('-off');
                } else {
                    jQuery(this).prev('input').prop('checked', false);
                    jQuery(this).removeClass('-on');
                    jQuery(this).addClass('-off');
                }
            });

        });
    </script>
    <?php
}
?>