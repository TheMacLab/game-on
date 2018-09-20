<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/21/18
 * Time: 6:04 PM
 */

function go_create_admin_message (){

    check_ajax_referer( 'go_create_admin_message');

    $user_ids = $_POST['user_ids'];
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
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>C4</label></div>
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
                                                    <td class="acf-field acf-field-number go_reward go_c4 "
                                                        data-name="c4" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="c4" type="number"
                                                                                               value="0" min="0"
                                                                                               step="1" placeholder="0" oninput="validity.valid||(value='');">
                                                            </div>
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
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>C4</label></div>
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
                                                    <td class="acf-field acf-field-number go_reward go_c4 "
                                                        data-name="c4" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="c4" type="number"
                                                                                               value="0" min="0"
                                                                                               step="1" placeholder="0" oninput="validity.valid||(value='');">
                                                            </div>
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
                                                    <th>
                                                        <div class="acf-th">
                                                            <label>C4</label></div>
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
                                                    <td class="acf-field acf-field-true-false go_reward go_c4"
                                                        data-name="c4" data-type="true_false">
                                                        <div class="acf-input">
                                                            <div class="acf-true-false">
                                                                <input value="0" type="hidden">
                                                                <label>
                                                                    <input name="c4_toggle" type="checkbox" value="1"
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
                                                    <td class="acf-field acf-field-number go_reward go_c4 "
                                                        data-name="c4" data-type="number">
                                                        <div class="acf-input">
                                                            <div class="acf-input-wrap"><input name="c4" type="number"
                                                                                               value="0" min="0"
                                                                                               step="1" placeholder="0" oninput="validity.valid||(value='');">
                                                            </div>
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
    $c4 = intval($_POST['c4']);

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
        go_update_actions($user_id, $type, null, 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $c4, $badge_ids, $group_ids, false);

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
            $c4_task = ($task->c4 * -1);

            //update task table
            $wpdb->update($go_task_table_name, array('status' => -1,// integer (number)
                'bonus_status' => 0, 'xp' => 0, 'gold' => 0, 'health' => 0, 'c4' => 0, 'badges' => null, 'groups' => null), array('uid' => $user_id, 'post_id' => $post_id), array('%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s'), array('%d', '%d'));

            $result = array();
            $task_title = get_the_title($post_id);
            $task_name = get_option('options_go_tasks_name_singular');
            $result[] = $task_name . " " . $task_title . " Reset";
            $result[] = " ";
            $result = serialize($result);


            //update actions with loot, title and message
            go_update_actions($user_id, $type, $post_id, 1, null, null, $result, null, null, null, null, $xp_task, $gold_task, $health_task, $c4_task, null, null, false);
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

            go_update_actions($user_id, $type, null, 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $c4, $badge_ids, $group_ids, false);

            //update_user_meta($user_id, 'go_new_messages', true);

        }
        //end foreach user
    }
    //set the new messages flag
    foreach ($user_ids as $user_id) {
        update_user_meta($user_id, 'go_new_messages', true);
    } //end foreach user

}

function go_check_messages(){
    global $wpdb;
    //on each page load, check if user has new messages
    $user_id =  get_current_user_id();
    $is_logged_in = is_user_logged_in();
    $is_new_messages = get_user_meta($user_id, 'go_new_messages', true);

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );
    $c4_abbr = get_option( "options_go_loot_c4_abbreviation" );

    if ($is_logged_in && $is_new_messages ){
        //get unread messages
        $go_actions_table_name = "{$wpdb->prefix}go_actions";
        $actions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
			FROM {$go_actions_table_name}
			WHERE uid = %d and (action_type = %s or action_type = %s or action_type = %s)  and stage = %d
			ORDER BY id DESC",
                $user_id,
                'message',
                'reset',
                'admin_notification',
                1
            )
        );
        //turn them into noty
        //set them as read
        foreach ($actions as $action) {
            $type = $action->action_type;
            $post_id = $action->source_id;
            $result = $action->result;
            $result = unserialize($result);
            $title = $result[0];
            $message = $result[1];
            $xp = $action->xp;
            $gold = $action->gold;
            $health = $action->health;
            $c4 = $action->c4;
            $badges = $action->badges;
            $badges = unserialize($badges);
            $groups = $action->groups;
            $groups = unserialize($groups);

            if ($type != 'admin_notification') {


                if (empty($xp)) {
                    $xp_penalty = null;
                    $xp_reward = null;
                } else if ($xp > 0) {
                    $xp_reward = $xp . " " . $xp_abbr . "<br>";
                    $xp_penalty = null;
                } else if ($xp < 0) {
                    $xp_penalty = $xp . " " . $xp_abbr . "<br>";
                    $xp_reward = null;
                } else {
                    $xp_penalty = null;
                    $xp_reward = null;
                }

                if (empty($gold)) {
                    $gold_penalty = null;
                    $gold_reward = null;
                } else if ($gold > 0) {
                    $gold_reward = $gold . " " . $gold_abbr . "<br>";
                    $gold_penalty = null;
                } else if ($gold < 0) {
                    $gold_penalty = $gold . " " . $gold_abbr . "<br>";
                    $gold_reward = null;
                } else {
                    $gold_penalty = null;
                    $gold_reward = null;
                }

                if (empty($health)) {
                    $health_penalty = null;
                    $health_reward = null;
                } else if ($health > 0) {
                    $health_reward = $health . " " . $health_abbr . "<br>";
                    $health_penalty = null;
                } else if ($health < 0) {
                    $health_reward = null;
                    $health_penalty = $health . " " . $health_abbr . "<br>";
                } else {
                    $health_penalty = null;
                    $health_reward = null;
                }

                if (empty($c4)) {
                    $c4_penalty = null;
                    $c4_reward = null;
                } else if ($c4 > 0) {
                    $c4_reward = $c4 . " " . $c4_abbr . "<br>";
                    $c4_penalty = null;
                } else if ($c4 < 0) {
                    $c4_reward = null;
                    $c4_penalty = $c4 . " " . $c4_abbr . "<br>";
                } else {
                    $c4_penalty = null;
                    $c4_reward = null;
                }


                $badges_toggle = get_option('options_go_badges_toggle');
                if ($badges_toggle && !empty($badges)) {
                    $badge_dir = $result[2];

                    $badges_name = get_option('options_go_badges_name_plural');

                    $badges_names = array();
                    $badges_names[] = "<b>" . $badges_name . ":</b>";
                    foreach ($badges as $badge) {
                        $term = get_term($badge, "go_badges");
                        if (!empty($term)) {
                            $badge_name = $term->name;
                            $badges_names[] = $badge_name;
                        }
                    }

                    if ($badge_dir == "badges+") {
                        //message for awarding badges
                        $badge_award = implode("<br>", $badges_names);
                        $badge_penalty = null;
                    } else if ($badge_dir == "badges-") {
                        //message for taking badges
                        //get all badge names
                        $badge_penalty = implode("<br>", $badges_names);
                        $badge_award = null;
                    } else {
                        $badge_penalty = null;
                        $badge_award = null;
                    }


                } else {
                    $badge_penalty = null;
                    $badge_award = null;
                }

                if (!empty($groups)) {
                    $groups_dir = $result[3];
                    $groups_names = array();
                    $groups_names[] = "<br><b>Groups:</b>";
                    foreach ($groups as $group) {
                        $term = get_term($group, "user_go_groups");
                        if (!empty($term)) {
                            $group_name = $term->name;
                            $groups_names[] = $group_name;
                        }
                    }

                    if ($groups_dir == "groups+") {
                        //message for awarding badges
                        $group_award = implode("<br>", $groups_names);
                        $group_penalty = null;
                    } else if ($groups_dir == "groups-") {
                        //message for taking badges
                        //get all badge names
                        $group_penalty = implode("<br>", $groups_names);
                        $group_award = null;
                    } else {
                        $group_penalty = null;
                        $group_award = null;
                    }
                } else {
                    $group_penalty = null;
                    $group_award = null;
                }


                if (!empty($xp_reward) || !empty($gold_reward) || !empty($health_reward) || !empty($c4_reward) || !empty($badge_award) || !empty($group_award)) {
                    $reward = "<h4>Reward</h4>{$xp_reward}{$gold_reward}{$health_reward}{$c4_reward}{$badge_award}{$group_award}";
                } else {
                    $reward = '';
                }

                if (!empty($xp_penalty) || !empty($gold_penalty) || !empty($health_penalty) || !empty($c4_penalty) || !empty($badge_penalty) || !empty($group_penalty)) {
                    if (empty($post_id)){
                        $penalty = "<h4>Additional Penalty:</h4>{$xp_penalty}{$gold_penalty}{$health_penalty}{$c4_penalty}{$badge_penalty}{$group_penalty}";
                    }
                    else{
                        $penalty = "<h4>Loot Lost:</h4>{$xp_penalty}{$gold_penalty}{$health_penalty}{$c4_penalty}{$badge_penalty}{$group_penalty}";
                    }
                } else {
                    $penalty = '';
                }
                $message = "<div> {$message}</div><div>{$reward}{$penalty}</div>";

                go_noty_message_generic('warning', $title, $message);
            }
            else{
                go_noty_message_generic('warning', '', $title);
            }
        }
        //set messages flag to read
        $wpdb->update(
            $go_actions_table_name,
            array(
                'stage' => 0 // integer (number)
            ),
            array(
                'uid' => $user_id,
                'action_type' => 'message',
                'stage' => 1

            ),
            array(
                '%d'	// value2
            ),
            array(
                '%d',
                '%s',
                '%d'
            )
        );

        $wpdb->update(
            $go_actions_table_name,
            array(
                'stage' => 0 // integer (number)
            ),
            array(
                'uid' => $user_id,
                'action_type' => 'reset',
                'stage' => 1

            ),
            array(
                '%d'	// value2
            ),
            array(
                '%d',
                '%s',
                '%d'
            )
        );
        $wpdb->update(
            $go_actions_table_name,
            array(
                'stage' => 0 // integer (number)
            ),
            array(
                'uid' => $user_id,
                'action_type' => 'admin_notification',
                'stage' => 1

            ),
            array(
                '%d'	// value2
            ),
            array(
                '%d',
                '%s',
                '%d'
            )
        );
        update_user_meta($user_id, 'go_new_messages', false);
    }


}
add_action( 'wp_footer', 'go_check_messages' );
