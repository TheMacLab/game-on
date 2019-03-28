<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/21/18
 * Time: 6:04 PM
 */

function go_create_admin_message (){

    check_ajax_referer( 'go_create_admin_message');

    $user_id = (isset($_POST['user_ids']) ?  $_POST['user_id'] : null);

    //$user_ids = $_POST['user_ids'];
    //$post_id = $_POST['post_id'];
    $message_type = $_POST['message_type'];
    //$user_ids = array_unique($user_ids);

    $reset_vars = (isset($_POST['reset_vars']) ?  $_POST['reset_vars'] : null);


    if (empty($reset_vars) && empty($user_id)){
        echo" <div>Error.  No variables passed to message builder.</div>";
        die();
    }
    else if ($message_type == 'single_reset' || $message_type == 'multiple_reset'){
        $uniqueTasks = array_unique(array_map(function ($i) { return $i['task']; }, $reset_vars));
        $quest_count = count($uniqueTasks);

        $uniqueUsers = array_unique(array_map(function ($i) { return $i['uid']; }, $reset_vars));
        $user_count = count($uniqueUsers);


        if ($quest_count > 1){
            $task_name = get_option('options_go_tasks_name_plural');
        } else{
            $task_name = get_option('options_go_tasks_name_singular');
        }


        if ($quest_count > 1 && $user_count > 1){ //if there is both more than one task and more than one user
            $task_title = "Multiple Values Selected";
            $user_name = "Multiple Values Selected";
        }
        else {
            $is_first = true;
            $task_title = "";
            $i = 0;
            foreach ($uniqueTasks as $task_id) {
                $i++;

                if (!$is_first && $quest_count > 2 && $i < $quest_count) {
                    $task_title = $task_title . ", ";
                }
                if ($i == $quest_count && $quest_count > 1) {
                    $task_title = $task_title . ", and ";
                }
                $task_title = $task_title . get_the_title($task_id);
                $is_first = false;
            }

            $is_first = true;
            $user_name = "";
            $i = 0;
            foreach ($uniqueUsers as $user_id) {
                $i++;
                //$task_id = intval($task['task_id']);
                if (!$is_first && $user_count > 2 && $i < $user_count) {
                    $user_name = $user_name . ", ";
                }
                if ($i == $user_count && $user_count > 1) {
                    $user_name = $user_name . ", and ";
                }
                $user = get_userdata($user_id);
                $this_user_name = $user->first_name . ' ' . $user->last_name;
                if (empty($this_user_name) || $this_user_name == ' ') {
                    $user_name = $user->display_name;
                }
                $user_name = $user_name . $this_user_name;
                $is_first = false;
            }
        }


        /*
                if ($user_count > 1){

                } else{
                    $user = get_userdata($uniqueUsers[0]);
                    $user_name = $user->first_name . ' ' . $user->last_name;
                    if (empty($user_fullname) || $user_fullname = ' ') {
                        $user_name = $user->display_name;
                    }
                }
        */
        ?>

        <div id="go_messages_container">
            <h3 style="font-size: 1.5em;"> Reset <?php echo $task_name;?> <span class="tooltip" data-tippy-content="Resetting removes all loot and rewards. <br> <br>If the bonus loot had already been awarded, it is also removed and the user will not have another attempt."><span><i class="fa fa-info-circle"></i></span></span></h3>
            <form method="post">
                <?php
                if ($quest_count > 1 && $user_count > 1){ //if there is both more than one task and more than one user
                    ?>
                    <table>
                        <tr valign="top">

                            <td>
                                <div>
                                    <p>Warning: Multiple Values for Users and <?php echo $task_name ?> were selected. Please double check that you want to reset all these <?php echo $task_name ?>.</p>
                                </div>
                            </td>

                        </tr>
                    </table>
                    <?php
                }
                else {
                    ?>
                    <table>
                        <tr valign="top">
                            <?php

                            echo "<th scope=\"row\">" . $task_name . ":</th>";
                            ?>
                            <td>
                                <div>
                                    <?php
                                    echo $task_title;
                                    ?>
                                </div>
                            </td>

                        </tr>
                        <tr valign="top">
                            <?php
                            if ($user_count > 1) {
                                echo "<th scope=\"row\">Users:</th>";
                            } else {
                                echo "<th scope=\"row\">User:</th>";
                            }
                            ?>

                            <td>
                                <div>
                                    <?php
                                    echo $user_name;
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <?php
                }
                ?>
                <div id="go_messages" style="display:flex;">

                    <div id="messages_form">
                        <h3>Notification Message <span class="tooltip" data-tippy-content="Users will be notified of reset. You can add a custom message."><span><i class="fa fa-info-circle"></i></span> </span></h3>
                        <label for="go_custom_message">Custom Message </label><input id="go_custom_message_toggle" type="checkbox">
                        <table id="go_custom_message_table" class="form-table" style="display: none;">

                            <tr valign="top">
                                <th scope="row">Custom Message </th>
                                <td><textarea name="message" class="widefat" cols="50" rows="5"></textarea></td>
                            </tr>

                        </table>
                        <div id="go_loot_table" class="go-acf-field go-acf-field-group " data-type="group">

                            <div class="go-acf-input">
                                <h3>Addtional Penalties <span class="tooltip" data-tippy-content="In addition to removing loot that had been awarded, you may assign an additional penalty."><span><i class="fa fa-info-circle"></i></span></span></h3>

                                <label for="go_additional_penalty_toggle">Assign Penalty </label><input id="go_additional_penalty_toggle" type="checkbox">
                                <br>
                                <br>
                                <div id="go_penalty_table" class="go-acf-fields -top -border" style="display:none;">
                                    <div class="go-acf-field go-acf-field-group go-acf-hide-label go-acf-no-padding go-acf-table-no-border"
                                         data-name="reward_toggle" data-type="group">
                                        <div class="go-acf-input">
                                            <table class="go-acf-table form-table">
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
                                        <p></p>
                                        <div class="go-acf-input">
                                            <table class="go-acf-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="go-acf-th">
                                                            <label>Remove Badges</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="go-acf-th">
                                                            <label>Remove Groups</label></div>
                                                    </th>

                                                </tr>

                                                </thead>
                                                <tbody>

                                                <tr class="go-acf-row">
                                                    <td class="go-acf-field go-acf-field-true-false go_reward go_badges"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('go_badges', "messages_"); ?>

                                                    </td>
                                                    <td class="go-acf-field go-acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('user_go_groups', "messages_"); ?>
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
                                                            class="button button-primary" value="Reset"></p>
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
        $uniqueUsers = array_unique(array_map(function ($i) { return $i['uid']; }, $reset_vars));
        $user_count = count($uniqueUsers);
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
                                        foreach ($uniqueUsers as $user_id) {
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
                                        <p></p>
                                        <div class="go-acf-input">
                                            <table class="go-acf-table">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div class="go-acf-th">
                                                            <label>Badges</label></div>
                                                    </th>
                                                    <th>
                                                        <div class="go-acf-th">
                                                            <label>Groups</label></div>
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
                                                                    <input name="badges_toggle" type="checkbox"
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
                                                    <td class="go-acf-field go-acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <div class="go-acf-input">
                                                            <div class="go-acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="groups_toggle" type="checkbox"
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
                                                    <td class="go-acf-field go-acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('go_badges', "messages_"); ?>

                                                    </td>
                                                    <td class="go-acf-field go-acf-field-true-false go_reward go_gold"
                                                        data-name="gold" data-type="true_false">
                                                        <?php go_make_tax_select('user_go_groups', "messages_"); ?>
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
}


/**
 * Check for new admin messages
 */
function go_admin_messages(){
    //$user_id = get_current_user_id();
    check_ajax_referer( 'go_admin_messages');
    go_check_messages();
    die();
}

function go_send_message(){
    check_ajax_referer( 'go_send_message');

    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";

    $title = ( !empty( $_POST['title'] ) ? $_POST['title'] : "" );

    $message = ( !empty( $_POST['message'] ) ? $_POST['message'] : "" );

    $type = ( !empty( $_POST['message_type'] ) ? $_POST['message_type'] : "message" );// can be message, or reset

    $xp = intval($_POST['xp']);
    $gold = intval($_POST['gold']);
    $health = intval($_POST['health']);

    $badges_toggle = $_POST['badges_toggle'];
    $badge_ids = $_POST['badges'];
    if (!is_array($badge_ids)){
        $badge_ids = array();
    }

    $groups_toggle = $_POST['groups_toggle'];
    $group_ids = $_POST['groups'];
    if (!is_array($group_ids)){
        $group_ids = array();
    }

    $reset_vars = $_POST['reset_vars'];

    $uniqueUsers = array_unique(array_map(function ($i) { return $i['uid']; }, $reset_vars));

    $task_name = get_option('options_go_tasks_name_singular');


    foreach ($reset_vars as $vars){
        $task_id = $vars['task'];
        $user_id = $vars['uid'];
        if ($type == "reset"){
            $task_title = get_the_title($task_id);
            $title = "The following " .$task_name . " has been reset: ". $task_title .".";
            $this_message = "All loot and rewards earned have been removed.";
            if (!empty($message)) {
                $message = $this_message . "<br><br>" . $message;
            }

            $tasks = $wpdb->get_results($wpdb->prepare("SELECT *
			FROM {$go_task_table_name}
			WHERE uid = %d and post_id = %d
			ORDER BY last_time DESC", $user_id, $task_id
            ));
            $task = $tasks[0];
            $xp_task = ($task->xp * -1);
            $gold_task = ($task->gold * -1);
            $health_task = ($task->health * -1);
            $badge_task = unserialize($task->badges);
            $group_task = unserialize($task->groups);
            if (!is_array($badge_task)){
                $badge_task = array();
            }
            if (!is_array($group_task)){
                $group_task = array();
            }

            //update task table
            $wpdb->update($go_task_table_name, array('status' => -2,// integer (number)
                'bonus_status' => 0, 'xp' => 0, 'gold' => 0, 'health' => 0, 'badges' => null, 'groups' => null), array('uid' => $user_id, 'post_id' => $task_id), array('%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s'), array('%d', '%d'));

            //add the task loot removed to the additional penalties
            $xp = $xp + $xp_task;
            $gold = $gold + $gold_task;
            $health = $health + $health_task;

            $badge_ids = array_merge($badge_task, $badge_ids);
            $group_ids = array_merge($group_task, $group_ids);
        }
        $result = array();
        $result[] = $title;
        $result[] = $message;

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

        //Update the DB as needed
        //add/subtrct groups and badges

        if ($badges_toggle == "true" && !empty($badge_ids)) {//if badges toggle is true and badges exist
            go_add_badges($badge_ids, $user_id, false);//add badges
        } else if ($badges_toggle == "false" && !empty($badge_ids)) {//else if badges toggle is false and badges exist
            go_remove_badges($badge_ids, $user_id, false);//remove badges
        }

        if ($groups_toggle == "true" && !empty($group_ids)) {//if groups toggle is true and groups exist
            go_add_groups($group_ids, $user_id, false);//add groups
        } else if ($groups_toggle == "false" && !empty($group_ids)) {//else if groups toggle is false and groups exist
            go_remove_groups($group_ids, $user_id, false);//remove groups
        }

        //update actions
        go_update_actions($user_id, $type, null, 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $badge_ids, $group_ids, false, false);
    }
    foreach ($uniqueUsers as $user_id) {
        $user_id = intval($user_id);
        update_user_option($user_id, 'go_new_messages', true);
    } //end foreach user
}

?>