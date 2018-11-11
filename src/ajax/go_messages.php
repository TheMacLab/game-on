<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/21/18
 * Time: 6:04 PM
 */

function go_create_admin_message (){

    check_ajax_referer( 'go_create_admin_message');

    $user_ids = (isset($_POST['user_ids']) ?  $_POST['user_ids'] : null);

    //$user_ids = $_POST['user_ids'];
    $post_id = $_POST['post_id'];
    $message_type = $_POST['message_type'];


    if (empty($user_ids)){
        ?> <div>No User Selected.</div> <?php
        die();
    }

    /*
    if ($message_type == 'reset'){
        $task_name = get_option('options_go_tasks_name_singular');
        $post_id = $post_id[0];
        $task_title = get_the_title($post_id);
        ?>


        <div id="go_messages_container">
            <h3> Reset <?php echo $task_name . ": " . $task_title ; ?></h3>
            <p>You can customize the message below. In addition to removing loot that had been awarded, you may assign an additional penalty.</p>
            <p>Also, if the bonus loot had already been awarded, the user will not have another attempt.  This is to prevent mining of loot.</p>
            <form method="post">
                <div id="go_messages" style="display:flex;">

                    <div id="messages_form">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">To</th>
                                <td style="width: 100%;">
                                    <div>
                                        <?php
                                        $is_first = true;
                                        foreach ($user_ids as $user_id) {
                                            $user = get_userdata($user_id);
                                            if (!$is_first) {
                                                echo ", ";
                                            }
                                            $user_fullname = $user->first_name . ' ' . $user->last_name;
                                            if (empty($user_fullname) || $user_fullname = ' ') {
                                                $user_fullname = $user->display_name;
                                            }
                                            echo $user_fullname;
                                            $is_first = false;
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Title</th>
                                <td style="width: 100%;"><input type="text" name="title" value="<?php echo $task_title ; ?> has been reset" style="width: 100%;"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Message</th>
                                <td><textarea name="message" class="widefat" cols="50" rows="5">This task was reset. All loot and rewards have been removed.</textarea></td>
                            </tr>

                        </table>
                        <div id="go_loot_table" class="acf-field acf-field-group " data-type="group">
                            <div class="acf-input">
                                <p style="font-weight: 600;">Addtional Penalties</p>
                                <div class="acf-fields -top -border">
                                    <div class="acf-field acf-field-group acf-hide-label acf-no-padding acf-table-no-border"
                                         data-name="reward_toggle" data-type="group">
                                        <div class="acf-input">
                                            <table class="acf-table form-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>XP</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Gold</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Health</label></div>
                                                    </th>
                                                    
                                                </tr>


                                                </thead>
                                                <tbody>


                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-number go_reward go_xp  data-name="
                                                        xp
                                                    " data-type="number">
                                                    <div class="acf-input">
                                                        <div class="acf-input-wrap"><input name="xp" type="number"
                                                                                           value="0" min="0" step="1" oninput="validity.valid||(value='');">
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td class="acf-field acf-field-number go_reward go_gold"
                                                        data-name="gold" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="gold" type="number"
                                                                                               value="0" min="0"
                                                                                               step="1" oninput="validity.valid||(value='');"></div>
                                                        </div>
                                                    </td>
                                                    <td class="acf-field acf-field-number go_reward go_health "
                                                        data-name="health" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="health"
                                                                                               type="number" value="0"
                                                                                               min="0" step=".01" oninput="validity.valid||(value='');"></div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <p></p>
                                        <div class="acf-input">
                                            <table class="acf-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Remove Badges</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Remove Groups</label></div>
                                                    </th>

                                                </tr>

                                                </thead>
                                                <tbody>

                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-true-false go_reward go_badges"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('go_badges', "Select One", "messages_", null, true); ?>

                                                    </td>
                                                    <td class="acf-field acf-field-true-false go_reward go_groups"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('user_go_groups', "Select One", "messages_", null, true); ?>
                                                    </td>

                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="go_message_submit"><input type="button" id="go_message_submit"
                                                            class="button button-primary" value="Send"></p>
                    </div>


                </div>
            </form>

            <script type="text/javascript">
                jQuery(document).ready(function () {
                    //jQuery('.go_messages_select2').select2();

                });
            </script>
        </div>
        <?php

    }
    */
    else if ($message_type == 'reset' || $message_type == 'reset_multiple'){

        $is_first = true;
        $task_title = "";
        $quest_count = count($post_id);
        if ($quest_count > 1){
            $task_name = get_option('options_go_tasks_name_plural');
        } else{
            $task_name = get_option('options_go_tasks_name_singular');
        }
        $i = 0;
        foreach ($post_id as $post) {
            $i++;
            if (!$is_first && $quest_count > 2 && $i < $quest_count) {
                $task_title = $task_title . ", ";
            }
            if ($i == $quest_count && $quest_count > 1){
                $task_title = $task_title . ", and ";
            }
            $task_title = $task_title . get_the_title($post);

            $is_first = false;
        }
        //$task_title = get_the_title($post_id);
        ?>


        <div id="go_messages_container">
            <h3> Reset <?php echo $task_name . ": " . $task_title ; ?></h3>
            <p>You can customize the message below. In addition to removing loot that had been awarded, you may assign an additional penalty.</p>
            <p>Also, if the bonus loot had already been awarded, the user will not have another attempt.  This is to prevent mining of loot.</p>
            <form method="post">
                <div id="go_messages" style="display:flex;">

                    <div id="messages_form">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">To</th>
                                <td style="width: 100%;">
                                    <div>
                                        <?php
                                        $is_first = true;
                                        foreach ($user_ids as $user_id) {
                                            $user = get_userdata($user_id);
                                            if (!$is_first) {
                                                echo ", ";
                                            }
                                            $user_fullname = $user->first_name . ' ' . $user->last_name;
                                            if (empty($user_fullname) || $user_fullname = ' ') {
                                                $user_fullname = $user->display_name;
                                            }
                                            echo $user_fullname;
                                            $is_first = false;
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Title</th>
                                <td style="width: 100%;"><input type="text" name="title" value="<?php
                                echo $task_name . " " . $task_title;
                                if ($quest_count > 1){
                                    echo " have ";
                                }
                                else{
                                    echo " has ";
                                }
                                ?>been reset" style="width: 100%;"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Message</th>
                                <td><textarea name="message" class="widefat" cols="50" rows="5"><?php
                                        //echo $task_name . " " . $task_title;
                                        if ($quest_count > 1){
                                            echo "These " . $task_name . " were ";
                                        }
                                        else{
                                            echo "This " . $task_name . " was ";
                                        }
                                        ?>reset. All loot and rewards have been removed.</textarea></td>
                            </tr>

                        </table>
                        <div id="go_loot_table" class="acf-field acf-field-group " data-type="group">
                            <div class="acf-input">
                                <p style="font-weight: 600;">Addtional Penalties</p>
                                <div class="acf-fields -top -border">
                                    <div class="acf-field acf-field-group acf-hide-label acf-no-padding acf-table-no-border"
                                         data-name="reward_toggle" data-type="group">
                                        <div class="acf-input">
                                            <table class="acf-table form-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>XP</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Gold</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Health</label></div>
                                                    </th>
                                                    
                                                </tr>


                                                </thead>
                                                <tbody>


                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-number go_reward go_xp  data-name="
                                                        xp
                                                    " data-type="number">
                                                    <div class="acf-input">
                                                        <div class="acf-input-wrap"><input name="xp" type="number"
                                                                                           value="0" min="0" step="1" oninput="validity.valid||(value='');">
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td class="acf-field acf-field-number go_reward go_gold"
                                                        data-name="gold" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="gold" type="number"
                                                                                               value="0" min="0"
                                                                                               step="1" oninput="validity.valid||(value='');"></div>
                                                        </div>
                                                    </td>
                                                    <td class="acf-field acf-field-number go_reward go_health "
                                                        data-name="health" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="health"
                                                                                               type="number" value="0"
                                                                                               min="0" step=".01" oninput="validity.valid||(value='');"></div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <p></p>
                                        <div class="acf-input">
                                            <table class="acf-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Remove Badges</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Remove Groups</label></div>
                                                    </th>

                                                </tr>

                                                </thead>
                                                <tbody>

                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-true-false go_reward go_badges"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('go_badges', "Select One", "messages_", null, true); ?>

                                                    </td>
                                                    <td class="acf-field acf-field-true-false go_reward go_groups"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('user_go_groups', "Select One", "messages_", null, true); ?>
                                                    </td>

                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="go_message_submit"><input type="button" id="go_message_submit"
                                                            class="button button-primary" value="Send"></p>
                    </div>


                </div>
            </form>

            <script type="text/javascript">
                jQuery(document).ready(function () {
                    //jQuery('.go_messages_select2').select2();

                });
            </script>
        </div>
        <?php

    }
    else {
        ?>


        <div id="go_messages_container">
            <form method="post">
                <div id="go_messages" style="display:flex;">

                    <div id="messages_form">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">To</th>
                                <td style="width: 100%;">
                                    <div>
                                        <?php
                                        $is_first = true;
                                        foreach ($user_ids as $user_id) {
                                            $user = get_userdata($user_id);
                                            if (!$is_first) {
                                                echo ", ";
                                            }
                                            $user_fullname = $user->first_name . ' ' . $user->last_name;
                                            if (empty($user_fullname) || $user_fullname = ' ') {
                                                $user_fullname = $user->display_name;
                                            }
                                            echo $user_fullname;
                                            $is_first = false;
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Title</th>
                                <td style="width: 100%;"><input type="text" name="title" value="" style="width: 100%;"/>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Message</th>
                                <td><textarea name="message" class="widefat" cols="50" rows="5"></textarea></td>
                            </tr>

                        </table>
                        <div id="go_loot_table" class="acf-field acf-field-group" data-type="group">
                            <div class="acf-input">
                                <div class="acf-fields -top -border">
                                    <div class="acf-field acf-field-group acf-hide-label acf-no-padding acf-table-no-border"
                                         data-name="reward_toggle" data-type="group">
                                        <div class="acf-input">
                                            <table class="acf-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>XP</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Gold</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Health</label></div>
                                                    </th>
                                                    
                                                </tr>


                                                </thead>
                                                <tbody>
                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-true-false go_reward go_xp"
                                                        data-name="xp" data-type="true_false">
                                                        <div class="acf-input">
                                                            <div class="acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="xp_toggle" type="checkbox" value="1"
                                                                           class="acf-switch-input">
                                                                    <div class="acf-switch"><span class="acf-switch-on"
                                                                                                  style="min-width: 36px;">+</span><span
                                                                                class="acf-switch-off"
                                                                                style="min-width: 36px;">-</span>
                                                                        <div class="acf-switch-slider"></div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="acf-field acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <div class="acf-input">
                                                            <div class="acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="gold_toggle" type="checkbox"
                                                                           class="acf-switch-input">
                                                                    <div class="acf-switch"><span class="acf-switch-on"
                                                                                                  style="min-width: 36px;">+</span><span
                                                                                class="acf-switch-off"
                                                                                style="min-width: 36px;">-</span>
                                                                        <div class="acf-switch-slider"></div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="acf-field acf-field-true-false go_reward go_health"
                                                        data-name="health" data-type="true_false">
                                                        <div class="acf-input">
                                                            <div class="acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="health_toggle" type="checkbox"
                                                                           value="1" class="acf-switch-input">
                                                                    <div class="acf-switch"><span class="acf-switch-on"
                                                                                                  style="min-width: 36px;">+</span><span
                                                                                class="acf-switch-off"
                                                                                style="min-width: 36px;">-</span>
                                                                        <div class="acf-switch-slider"></div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-number go_reward go_xp  data-name="
                                                        xp
                                                    " data-type="number">
                                                    <div class="acf-input">
                                                        <div class="acf-input-wrap"><input name="xp" type="number"
                                                                                           value="0" min="0" step="1" oninput="validity.valid||(value='');">
                                                        </div>
                                                    </div>
                                                    </td>
                                                    <td class="acf-field acf-field-number go_reward go_gold"
                                                        data-name="gold" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="gold" type="number"
                                                                                               value="0" min="0"
                                                                                               step="1" oninput="validity.valid||(value='');"></div>
                                                        </div>
                                                    </td>
                                                    <td class="acf-field acf-field-number go_reward go_health "
                                                        data-name="health" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="health"
                                                                                               type="number" value="0"
                                                                                               min="0" step=".01" oninput="validity.valid||(value='');"></div>
                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <p></p>
                                        <div class="acf-input">
                                            <table class="acf-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Badges</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>Groups</label></div>
                                                    </th>

                                                </tr>

                                                </thead>
                                                <tbody>
                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-true-false go_reward go_xp"
                                                        data-name="xp" data-type="true_false">
                                                        <div class="acf-input">
                                                            <div class="acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="badges_toggle" type="checkbox"
                                                                           value="1" class="acf-switch-input">
                                                                    <div class="acf-switch"><span class="acf-switch-on"
                                                                                                  style="min-width: 36px;">+</span><span
                                                                                class="acf-switch-off"
                                                                                style="min-width: 36px;">-</span>
                                                                        <div class="acf-switch-slider"></div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="acf-field acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <div class="acf-input">
                                                            <div class="acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="groups_toggle" type="checkbox"
                                                                           value="1" class="acf-switch-input">
                                                                    <div class="acf-switch"><span class="acf-switch-on"
                                                                                                  style="min-width: 36px;">+</span><span
                                                                                class="acf-switch-off"
                                                                                style="min-width: 36px;">-</span>
                                                                        <div class="acf-switch-slider"></div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <tr class="acf-row">
                                                    <td class="acf-field acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('go_badges', "Select One", "messages_", null, true); ?>

                                                    </td>
                                                    <td class="acf-field acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('user_go_groups', "Select One", "messages_", null, true); ?>
                                                    </td>

                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="go_message_submit"><input type="button" id="go_message_submit"
                                                            class="button button-primary" value="Send"></p>
                    </div>


                </div>
            </form>

        </div>
        <?php
    }
}

/**
 * Check for new admin messages
 */
function go_admin_messages(){
    //$user_id = get_current_user_id();
    check_ajax_referer( 'go_admin_messages');
    go_check_messages();
}

function go_send_message(){
    check_ajax_referer( 'go_send_message');

    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";

    $title = ( !empty( $_POST['title'] ) ? $_POST['title'] : "" );

    $message = ( !empty( $_POST['message'] ) ? $_POST['message'] : "" );
    $result = array();
    $result[] = $title;
    $result[] = $message;
    $original_result = $result;

    $post_ids = ( !empty( $_POST['post_id'] ) ? $_POST['post_id'] : 0 );
    $quest_count = count($post_ids);

    $type = ( !empty( $_POST['message_type'] ) ? $_POST['message_type'] : "message" );// can be message, or task
    if ($type == "reset_multiple" ){
        $type = "reset";
    }

    $user_ids = $_POST['user_ids'];

    $xp = intval($_POST['xp']);
    $gold = intval($_POST['gold']);
    $health = intval($_POST['health']);

    $badges_toggle = $_POST['badges_toggle'];
    $badge_ids = $_POST['badges'];


    $groups_toggle = $_POST['groups_toggle'];
    $group_ids = $_POST['groups'];


    //reset each task
    if ($type == 'reset') {

        $user_id = $user_ids[0];//there can be only one user for reset tasks


        //remove badges and groups
        //store the badge and group toggles so later we know if they were awarded or taken.
        if (!empty($badge_ids)) {//if badges toggle is true and badges exist
            $result[] = "badges-";
            $badge_ids = serialize($badge_ids);
            go_remove_badges($badge_ids, $user_id, false);//remove badges
        }else {
            $result[] = "badges0";
            $badge_ids = null;
        }


        if (!empty($group_ids)) {//if groups toggle is true and groups exist
            $result[] = "groups-";
            $group_ids = serialize($group_ids);
            go_remove_groups($group_ids, $user_id, false);//remove groups
        }else{
            $result[] = "groups0";
            $group_ids = null;
        }


        //set the main reset message and additional penalties
        $result = serialize($result);
        go_update_actions($user_id, $type, null, 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $badge_ids, $group_ids, false, false);

        //reset each task
        foreach ($post_ids as $post_id) {
            $tasks = $wpdb->get_results($wpdb->prepare("SELECT *
			FROM {$go_task_table_name}
			WHERE uid = %d and post_id = %d
			ORDER BY last_time DESC", $user_id, $post_id
            ));
            $task = $tasks[0];
            $xp_task = ($task->xp * -1);
            $gold_task = ($task->gold * -1);
            $health_task = ($task->health * -1);

            //update task table
            $wpdb->update($go_task_table_name, array('status' => -1,// integer (number)
                'bonus_status' => 0, 'xp' => 0, 'gold' => 0, 'health' => 0, 'badges' => null, 'groups' => null), array('uid' => $user_id, 'post_id' => $post_id), array('%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s'), array('%d', '%d'));

            $result = array();
            $task_title = get_the_title($post_id);
            $task_name = get_option('options_go_tasks_name_singular');
            $result[] = $task_name . " " . $task_title . " Reset";
            $result[] = " ";
            $result = serialize($result);


            //update actions with loot, title and message
            go_update_actions($user_id, $type, $post_id, 1, null, null, $result, null, null, null, null, $xp_task, $gold_task, $health_task, null, null, false, true);
        }
    }//end of task resets
    else { //this isn't a task reset message and set the message and update the actions and totals
        $result = $original_result;


        //store the badge and group toggles so later we know if they were awarded or taken.
        if ($badges_toggle == "true" && !empty($badge_ids)) {//if badges toggle is true and badges exist
            $result[] = "badges+";
            $badge_ids = serialize($badge_ids);
        }else if ($badges_toggle == "false" && !empty($badge_ids)) {//else if badges toggle is false and badges exist
            $result[] = "badges-";
            $badge_ids = serialize($badge_ids);
        }else {
            $result[] = "badges0";
            $badge_ids = null;
        }


        if ($groups_toggle == "true" && !empty($group_ids)) {//if groups toggle is true and groups exist
            $result[] = "groups+";
            $group_ids = serialize($group_ids);
        }else if ($groups_toggle == "false" && !empty($group_ids)) {//else if groups toggle is false and groups exist
            $result[] = "groups-";
            $group_ids = serialize($group_ids);
        }else{
            $result[] = "groups0";
            $group_ids = null;
        }


        $result = serialize($result);
        //update actions with loot, title and message
        //for each user id
        foreach ($user_ids as $user_id) {
            //if this is a task reset, update task

            if ($badges_toggle == "true" && !empty($badge_ids)) {//if badges toggle is true and badges exist
                go_add_badges($badge_ids, $user_id, false);//add badges
            } else if ($badges_toggle == "false" && !empty($badge_ids)) {//else if badges toggle is false and badges exist
                go_remove_badges($badge_ids, $user_id, false);//remove badges
            }

            if ($groups_toggle && !empty($group_ids)) {//if groups toggle is true and groups exist
                go_add_groups($group_ids, $user_id, false);//add groups
            } else if (!$groups_toggle && !empty($group_ids)) {//else if groups toggle is false and groups exist
                go_remove_groups($group_ids, $user_id, false);//remove groups
            }

            go_update_actions($user_id, $type, null, 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $badge_ids, $group_ids, false, false);

            //update_user_meta($user_id, 'go_new_messages', true);

        }
        //end foreach user
    }
    //set the new messages flag
    foreach ($user_ids as $user_id) {
        update_user_meta($user_id, 'go_new_messages', true);
    } //end foreach user

}

?>