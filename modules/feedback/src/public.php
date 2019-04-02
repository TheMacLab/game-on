<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 2019-03-30
 * Time: 18:39
 */

function go_make_reader() {
    ?>
    <script>
        jQuery( document ).ready( function() {
            go_load_daterangepicker();


            go_make_select2_filter('user_go_sections', 'section');
            go_make_select2_filter('user_go_groups', 'group');
            go_make_select2_filter('go_badges', 'badge');


            tippy('.tooltip', {
                delay: 0,
                arrow: true,
                arrowType: 'round',
                size: 'large',
                duration: 300,
                animation: 'scale',
                zIndex: 999999
            });



            jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #go_task_select, #go_store_item_select').on('select2:select', function (e) {
                // Do something
                jQuery('.go_update_clipboard').addClass("bluepulse");
                jQuery('.go_update_clipboard').html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>');
            });

            jQuery('.go_reset_clipboard').on("click", function () {
                jQuery('#datepicker_clipboard span').html("");
                jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #go_task_select, #go_store_item_select').val(null).trigger('change');
                jQuery('.go_update_clipboard').addClass("bluepulse");
                jQuery('.go_update_clipboard').html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>');
            });

            go_setup_reset_filter_button();

            //add task select2
            go_make_select2_cpt('#go_task_select', 'tasks');

            //update button--set this table to update
            jQuery('.go_update_clipboard').prop('onclick',null).off('click');//unbind click
            jQuery('.go_update_clipboard').one("click", function () {
                go_reader_update();
            });

        });

        function go_reader_update() {
            console.log("update reader");
            go_save_clipboard_filters(true);
            jQuery('.go_update_clipboard').removeClass("bluepulse");
            jQuery('.go_update_clipboard').html('<span class="ui-button-text">Refresh Data <span class="dashicons dashicons-update" style="vertical-align: center;"></span></span>');
            jQuery('.go_update_clipboard').prop('onclick',null).off('click');//unbind click
            jQuery('.go_update_clipboard').one("click", function () {
                go_reader_update();
            });

            var date = jQuery('#datepicker_clipboard span').html();
            var section = jQuery('#go_clipboard_user_go_sections_select').val();
            var group = jQuery('#go_clipboard_user_go_groups_select').val();
            var badge = jQuery('#go_clipboard_go_badges_select').val();
            var tasks = jQuery("#go_task_select").val();
            var unread = jQuery('#go_reader_unread').prop('checked');
            var read = jQuery('#go_reader_read').prop('checked');
            var reset = jQuery('#go_reader_reset').prop('checked');
            var trash = jQuery('#go_reader_trash').prop('checked');
            var draft = jQuery('#go_reader_draft').prop('checked');
            var order = jQuery("input[name='go_reader_order']:checked").val();
            var limit = jQuery('#go_posts_num').val();
            //console.log("unread:" + unread);

            var nonce = GO_EVERY_PAGE_DATA.nonces.go_filter_reader;
            //console.log("refresh" + nonce);
            //console.log("stats");
            jQuery.ajax({
                url: MyAjax.ajaxurl,
                type: 'post',
                data: {
                    _ajax_nonce: nonce,
                    action: 'go_filter_reader',
                    date: date,
                    section: section,
                    group: group,
                    badge: badge,
                    tasks: tasks,
                    unread: unread,
                    read: read,
                    reset: reset,
                    trash: trash,
                    draft: draft,
                    order: order,
                    limit: limit
                },
                success: function( res ) {
                    console.log("success: " + res);
                    if (-1 !== res) {
                        jQuery('#go_wrapper').html(res);

                    }
                }
            });


        }


    </script>
    <?php
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    //echo "success";
    go_reader_header();
    //get_sidebar();
}
add_shortcode( 'go_make_reader','go_make_reader' );


function go_reader_header() {
    //acf_form_head();

        $task_name = get_option( 'options_go_tasks_name_plural'  );
        ?>
        <div id="go_leaderboard_filters" style="display: flex; flex-wrap: wrap ;">
            <div id="go_user_filters" style="padding: 0 20px 20px 20px;">
                <h3>User Filter</h3>
                <div id="go_user_filters_1" style="padding: 0px 20px 20px 20px;">
                    <span><label for="go_clipboard_user_go_sections_select">Section </label><?php go_make_tax_select('user_go_sections' , "clipboard_"); ?></span>
                    <br><span><label for="go_clipboard_user_go_groups_select">Group </label><?php go_make_tax_select('user_go_groups', "clipboard_"); ?></span>
                    <br><span><label for="go_clipboard_go_badges_select">Badge </label><?php go_make_tax_select('go_badges', "clipboard_"); ?></span>
                </div>
            </div>
            <div id="go_action_filters" style="padding: 0 20px 20px 20px;">
                <h3>Blog Post Filters</h3>
                <div id="go_action_filters_container" style="display: flex; flex-wrap: wrap;">
                    <div id="go_action_filters_1" style="padding: 0px 20px 20px 20px;">
                        <div>
                            <div id="datepicker_clipboard" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                            <div>
                                <span id="go_task_filters"><br><label for="go_clipboard_task_select"><?php echo $task_name; ?> </label><select id="go_task_select" class="js-store_data" style="width:250px;"></select></span>
                            </div>
                        </div>
                    </div>

                    <div id="go_action_filters_2" style="padding: 0px 20px 20px 20px;">
                        <div>
                            Status<br>
                            <input type="checkbox" id="go_reader_unread" value="unread" checked><label for="go_reader_unread">Unread </label>
                            <input type="checkbox" id="go_reader_read" value="read"><label for="go_reader_unread">Read </label>
                            <input type="checkbox" id="go_reader_reset" value="reset"><label for="go_reader_unread">Reset </label>
                            <input type="checkbox" id="go_reader_trash" value="trash"><label for="go_reader_unread">Trash </label>
                            <input type="checkbox" id="go_reader_draft" value="draft"><label for="go_reader_draft">Draft </label>
                        </div>
                        <br>
                        <div>
                            Order <span class="tooltip" data-tippy-content="Posts are sorted by the last modified time."><span><i class="fa fa-info-circle"></i></span> </span><br>
                            <input type="radio" id="go_reader_order_oldest" name="go_reader_order" value="ASC" checked><label for="go_reader_order_oldest">Oldest First</label>
                            <input type="radio" id="go_reader_order_newest" name="go_reader_order" value="DESC"><label for="go_reader_order_newest">Newest First</label>
                        </div>
                        <br>
                        <div>
                            Number of Posts<br>
                            <select id="go_posts_num" class="go_posts_num" name="postNum">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    <div id="go_leaderboard_update_button" style="padding:20px; text-align: right">
        <div style="margin-right: 30px; float:left;"><button class="go_reset_clipboard dt-button ui-button ui-state-default ui-button-text-only buttons-collection"><span class="ui-button-text">Clear Filters <i class="fa fa-undo" aria-hidden="true"></i></span></button></div>
        <div style="margin-right: 60px;"><button class="go_update_clipboard dt-button ui-button ui-state-default ui-button-text-only buttons-collection"><span class="ui-button-text">Refresh Data <i class="fa fa-refresh" aria-hidden="true"></i></span></button></div>
    </div>
        <?php

//video options
    $go_lightbox_switch = get_option( 'options_go_video_lightbox' );
    $go_video_unit = get_option ('options_go_video_width_unit');
    if ($go_video_unit == 'px'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_pixels')."px";
    }
    if ($go_video_unit == '%'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_percent')."%";
    }
    echo "<div id='go_wrapper' data-lightbox='{$go_lightbox_switch}' data-maxwidth='{$go_fitvids_maxwidth}' ></div>";


}





?>
