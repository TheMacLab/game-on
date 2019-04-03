<?php


/**
 * Prints menu and container for clipboard
 */
function go_clipboard_menu() {
    //acf_form_head();

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    } else {
        $task_name = get_option( 'options_go_tasks_name_plural'  );
        ?>
        <div id="go_leaderboard_filters" style="display: flex; flex-wrap: wrap ;">
            <div style="padding: 0 20px 20px 20px;">
                <h3>User Filter</h3>

                <span><label for="go_clipboard_user_go_sections_select">Section </label><?php go_make_tax_select('user_go_sections' , "clipboard_"); ?></span>
                <br><span><label for="go_clipboard_user_go_groups_select">Group </label><?php go_make_tax_select('user_go_groups', "clipboard_"); ?></span>
                <br><span><label for="go_clipboard_go_badges_select">Badge </label><?php go_make_tax_select('go_badges', "clipboard_"); ?></span>
                <br>
            </div>
            <div id="go_action_filters" style="padding: 0 20px 20px 20px; display:none;">
                <h3>Action Filters</h3>
                <div id="go_datepicker_container" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <div id="go_datepicker_clipboard">
                        <i class="fa fa-calendar" style="float: left;"></i>&nbsp;
                        <span id="go_datepicker"></span> <i id="go_reset_datepicker" class=""select2-selection__clear><b> × </b></i><i class="fa fa-caret-down"></i>
                    </div>
                </div>

                <span id="go_store_filters"><br><label for="go_clipboard_store_item_select">Store Items </label><select id="go_store_item_select" class="js-store_data" style="width:250px;"></select></span>
                <span id="go_task_filters"><br><label for="go_clipboard_task_select"><?php echo $task_name; ?> </label><select id="go_task_select" class="js-store_data" style="width:250px;"></select></span>
                <span id="go_show_unmatched" ><br><label for="go_unmatched_toggle">Show Unmatched Users </label><input id="go_unmatched_toggle" type="checkbox" class="checkbox" name="unmatched"><span class="tooltip" data-tippy-content="Show a minimum of one row per user. This is useful to see who has not done something, in addition to those who have."><span><i class="fa fa-info-circle"></i></span> </span></span>
            </div>
            <div id="go_leaderboard_update_button" style="padding:20px; align-self: flex-end;">
                <div style="margin-right: 60px;"><button class="go_reset_clipboard dt-button ui-button ui-state-default ui-button-text-only buttons-collection"><span class="ui-button-text">Clear Filters <i class="fa fa-undo" aria-hidden="true"></i></span></button></div>
                <br>
                <div style="margin-right: 60px;"><button class="go_update_clipboard dt-button ui-button ui-state-default ui-button-text-only buttons-collection"><span class="ui-button-text">Refresh Data <i class="fa fa-refresh" aria-hidden="true"></i></span></button></div>
            </div>
        </div>

        <div id="records_tabs" style="clear: both; margin-left: -9999px; margin-right: 20px;">
            <ul>
                <li class="clipboard_tabs" tab="clipboard"><a href="#clipboard_wrap">Stats</a></li>
                <li class="clipboard_tabs" tab="store"><a href="#clipboard_store_wrap">Store</a></li>

                <li class="clipboard_tabs" tab="messages"><a href="#clipboard_messages_wrap">Messages</a></li>

                <li class="clipboard_tabs" tab="activity"><a href="#clipboard_activity_wrap"><?php echo $task_name; ?></a></li>
            </ul>
            <div id="clipboard_wrap">
                <div id="clipboard_stats_datatable_container"></div>
            </div>

            <div id="clipboard_store_wrap">
                <div id="clipboard_store_datatable_container"></div>
            </div>

            <div id="clipboard_messages_wrap">
                <div id="clipboard_messages_datatable_container"></div>
            </div>

            <div id="clipboard_activity_wrap">
                <div id="clipboard_activity_datatable_container"></div>
            </div>
        </div>
        <?php

    }
}



?>
