<?php

function go_buy_item() {
    global $wpdb;
    //check_ajax_referer( 'go_buy_item' );
    //$user_id = get_current_user_id();

    $user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function
    $is_logged_in = ! empty( $user_id ) && $user_id > 0  || is_user_member_of_blog( $user_id ) ? true : false;
    if (!$is_logged_in){
        //echo 'Error: You must be logged in to use the store.';
        echo "<script> new Noty({
                type: 'info',
                layout: 'topRight',
                text: 'Error: You must be logged in to use the store.',
                visibilityControl: true,
                theme: 'relax'
                }).show();parent.window.$.featherlight.current().close();</script>";
        die();
    }
    if ( ! check_ajax_referer( 'go_buy_item_' . $user_id, false ) ) {
        echo "<script> new Noty({
                type: 'info',
                layout: 'topRight',
                text: 'Error: WordPress hiccuped, try logging in again.' ,
                theme: 'relax',
                visibilityControl: true
                }).show();parent.window.$.featherlight.current().close();</script>";
        die();
    }

    $post_id = ( ! empty( $_POST["the_id"] ) ? (int) $_POST["the_id"] : 0 );
    $custom_fields = get_post_custom( $post_id );

    $purchase_count = go_get_purchase_count($post_id, $user_id, $custom_fields);
    $purchase_limit = go_get_purchase_limit($post_id, $user_id, $custom_fields, $purchase_count);
    $qty = ( ! empty( $_POST['qty'] ) && (int) $_POST['qty'] > 0 ? (int) $_POST['qty'] : 1 );
    if ($qty > $purchase_limit){
        echo "<script> new Noty({
                type: 'error',
                layout: 'topRight',
                text: 'Error: You exceeded your loot available. Try a lower quantity.' ,
                theme: 'relax',
                visibilityControl: true
                }).show();parent.window.$.featherlight.current().close();</script>";
        die();
    }

    $store_limit_toggle = ( ($custom_fields['go-store-options_limit_toggle'][0] == true ) ? $custom_fields['go-store-options_limit_toggle'][0] : null );
    if ($store_limit_toggle) {
        $purchase_remaining_max = go_get_purchase_limit($post_id, $user_id, $custom_fields, null);

        if ($qty > $purchase_remaining_max) {
            //echo 'Error: You attempted to buy more than your current limit. Try again.';
            echo "<script> new Noty({
                type: 'info',
                layout: 'topRight',
                text: 'Error: You attempted to buy more than your current limit.',
                theme: 'relax',
                visibilityControl: true
                }).show();parent.window.$.featherlight.current().close();</script>";
            die();
        }
    }

    $the_title = get_the_title($post_id);
    $health_mod = go_get_health_mod ($user_id);
    //$health_mod = go_get_user_loot( $user_id, 'health' );
    $xp = 0;
    $gold = 0;
    $health = 0;

    $store_abs_cost_xp = (isset($custom_fields['go_loot_loot_xp'][0]) ?  $custom_fields['go_loot_loot_xp'][0] : null);
    if (get_option( 'options_go_loot_xp_toggle' ) && $store_abs_cost_xp > 0){
        $store_toggle_xp = (isset($custom_fields['go_loot_reward_toggle_xp'][0]) ?  $custom_fields['go_loot_reward_toggle_xp'][0] : null);
        if ($store_toggle_xp == false){
            $xp = $qty * ($store_abs_cost_xp) * -1;
        }
        else{
            $xp = $qty * ($store_abs_cost_xp);
        }
    }

    $store_abs_cost_gold = (isset($custom_fields['go_loot_loot_gold'][0]) ?  $custom_fields['go_loot_loot_gold'][0] : null);
    if (get_option( 'options_go_loot_gold_toggle' )  && $store_abs_cost_gold > 0){
        $store_toggle_gold = (isset($custom_fields['go_loot_reward_toggle_gold'][0]) ?  $custom_fields['go_loot_reward_toggle_gold'][0] : null);
        if ($store_toggle_gold == false){
            $gold = $qty * ($store_abs_cost_gold) * -1;
        }
        else{
            $gold = $qty * ($store_abs_cost_gold);
        }
    }

    $store_abs_cost_health = (isset($custom_fields['go_loot_loot_health'][0]) ?  $custom_fields['go_loot_loot_health'][0] : null);
    if (get_option( 'options_go_loot_health_toggle' ) && $store_abs_cost_health > 0){
        $store_toggle_health = (isset($custom_fields['go_loot_reward_toggle_health'][0]) ?  $custom_fields['go_loot_reward_toggle_health'][0] : null);
        if ($store_toggle_health == false){
            $health = $qty * ($store_abs_cost_health) * -1;
        }
        else{
            $health = $qty * ($store_abs_cost_health);
        }

    }

    ob_start();

    //BADGES
    $badge_ids = (isset($custom_fields['go_purch_reward_badges'][0]) ?  $custom_fields['go_purch_reward_badges'][0] : null);
    if (!empty($badge_ids)) {
        $new_badges = go_add_badges ($badge_ids, $user_id, true);
        //$badge_count = count($new_badges);
        $badge_ids = serialize($new_badges);
    }
    //GROUPS
    $group_ids = (isset($custom_fields['go_purch_reward_groups'][0]) ?  $custom_fields['go_purch_reward_groups'][0] : null);
    if (!empty($group_ids)) {
        $new_groups = go_add_groups ($group_ids, $user_id, true);
        $group_ids = serialize($new_groups);

    }


    go_update_actions( $user_id, 'store',  $post_id, $qty, null, null, 'purchase', null, null, null, null,  $xp, $gold, $health, $badge_ids, $group_ids, true, false);

    $go_admin_message = (isset($custom_fields['go-store-options_admin_notifications'][0]) ?  $custom_fields['go-store-options_admin_notifications'][0] : null);

    if ($go_admin_message){

        $username = go_get_users_name($user_id);
        $result = array();
        $result[] = $username . " bought " . $the_title;
        $result[] = "";

        $result = serialize($result);
        $admin_users = get_option('options_go_admin_user_notifications');
        foreach ($admin_users as $admin_user) {
            go_update_actions(intval($admin_user), 'admin_notification', null , 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $badge_ids, $group_ids, 'admin', false);
            update_user_option(intval($admin_user), 'go_new_messages', true);
        }
        //go_update_actions($user_id, 'message', null , 1, null, null, $result, null, null, null, null, $xp, $gold, $health, $badge_ids, $group_ids, false);
        //update_user_option($user_id, 'go_new_messages');
    }
    $time = current_time('m-d-Y g:i A');

    echo "<script> new Noty({
    type: 'info',
    layout: 'topRight',
    text: '<h2>Receipt</h2><br>Item: " . addslashes($the_title) . " <br>Quantity: " . addslashes($qty) . " <br>Time: " . addslashes($time) . "',
    theme: 'relax',
    visibilityControl: true,
    callbacks: {
                    beforeShow: function() { go_noty_close_oldest();},
                }
    //timeout: '3000'
    
}).show();parent.window.$.featherlight.current().close();</script>";

    // stores the contents of the buffer and then clears it
    $buffer = ob_get_contents();

    ob_end_clean();

    echo json_encode(
        array(
            'json_status' => 'success',
            'html' => $buffer
        )
    );
    die();
}

// Main Lightbox Ajax Function
function go_the_lb_ajax() {
    check_ajax_referer( 'go_the_lb_ajax');


    $post_id = (int) $_POST['the_item_id'];
    $skip_locks = (isset($_POST['skip_locks']) ?  $_POST['skip_locks'] : false);

    $go_post_data = go_post_data($post_id); //0--name, 1--status, 2--permalink, 3--metadata
    $the_title = $go_post_data[0];
    //$status = $go_post_data[1];
    //$task_link = $go_post_data[2];
    $custom_fields = $go_post_data[3];

    $item_content = (isset($custom_fields['go_store_item_desc'][0]) ?  $custom_fields['go_store_item_desc'][0] : null);
    $the_content  = apply_filters( 'the_content', $item_content );

    $user_id = get_current_user_id();
    $is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
    $is_admin = go_user_is_admin( $user_id );

    ob_start();

    $unlock_flag = true;
    if($skip_locks == false) {
        echo "<div class='go_store_lightbox_container'>";
        if ($is_admin) {
            echo edit_post_link('edit', null, null, $post_id);
        }
        $task_is_locked = false;
        if ($custom_fields['go_lock_toggle'][0] == true || $custom_fields['go_sched_toggle'][0] == true) {
            $task_is_locked = go_task_locks($post_id, $user_id, "Item", $custom_fields, $is_logged_in, false);
        }

        $go_password_lock = (isset($custom_fields['go_password_lock'][0]) ? $custom_fields['go_password_lock'][0] : null);
        if ($go_password_lock == true) {
            $task_is_locked = true;
        }
        //Get option (show password field) from custom fields
        if ($go_password_lock && $is_logged_in) {
            //Show password unlock
            ?>
            <div class='go_lock go_store_lock'><h3>Unlock</h3><input id='go_store_password_result' class='clickable' type='password' placeholder='Enter Password'>
                <div id="go_store_buttons" style="overflow: auto; position: relative; padding: 10px; min-height: 40px;">
                    <p id='go_store_error_msg' style='display: none; color: red;'></p>
                    <button style="float: right; cursor: pointer;" id="go_store_pass_button" class="progress"
                            check_type="unlock_store" button_type="continue" admin_lock="true">Submit
                    </button>
                </div>
            </div>
            <?php
        } else if ($task_is_locked == true && $is_logged_in) { //change this code to show admin override box
            //if ($is_logged_in) { //add of show password field is on
            ?>
            <div id="go_store_admin_override" style="overflow: auto; width: 100%;">
                <div style="float: right; font-size: .8em;">Admin Override</div>
            </div>

            <div class='go_lock go_store_lock go_lock go_password' style="display:none;"><h3>Admin Override</h3><input id='go_store_password_result' class='clickable' type='password' placeholder='Enter Password'>
                <div id="go_store_buttons" style="overflow: auto; position: relative; padding: 10px; min-height: 40px;">
                    <p id='go_store_error_msg' style='display: none; color: red;'></p>
                    <button style="float: right; cursor: pointer;" id="go_store_pass_button" class="progress"
                            check_type="unlock_store" button_type="continue" admin_lock="true">Submit
                    </button>
                </div>
            </div>
            <?php
        }
        ?>
        <script>
            jQuery(document).ready(function () {
                jQuery('#go_store_pass_button').one("click", function (e) {
                    go_store_password(<?php echo $post_id; ?>);
                });
            });
        </script>
        <?php
        echo "</div>";
        //$task_is_locked = go_display_locks($post_id, $user_id, $is_admin, 'item', $badge_name, $custom_fields, $is_logged_in, 'Item');

    }
    else{//skip locks is true--this is a request from the password field
        //check the password and return an error
        //or return the store item
        $result = (!empty($_POST['result']) ? (string)$_POST['result'] : ''); // Contains the result from the password field
        $result = go_lock_password_validate($result, $custom_fields);
        if ($result == 'password' || $result ==  'master password') {
            //set unlock flag
            $unlock_flag = 'password_valid';
            //the password is correct so just continue
        }
        else {//the password is invalid
            $unlock_flag = 'bad_password';
        }
    }
    if (!$task_is_locked) {


        $store_abs_cost_xp = (isset($custom_fields['go_loot_loot_xp'][0]) ? $custom_fields['go_loot_loot_xp'][0] : null);
        if (get_option('options_go_loot_xp_toggle') && $store_abs_cost_xp > 0) {
            $xp_on = true;
            $xp_name = get_option('options_go_loot_xp_name');
            //$xp_abbr         = get_option( 'options_go_loot_xp_abbreviation' );
            //$user_xp = go_return_points( $user_id );
            $store_toggle_xp = (isset($custom_fields['go_loot_reward_toggle_xp'][0]) ? $custom_fields['go_loot_reward_toggle_xp'][0] : null);

        } else {
            $xp_on = false;
        }

        $store_abs_cost_gold = (isset($custom_fields['go_loot_loot_gold'][0]) ? $custom_fields['go_loot_loot_gold'][0] : null);
        if (get_option('options_go_loot_gold_toggle') && $store_abs_cost_gold > 0) {
            $gold_on = true;
            $gold_name = get_option('options_go_loot_gold_name');
            $store_toggle_gold = (isset($custom_fields['go_loot_reward_toggle_gold'][0]) ? $custom_fields['go_loot_reward_toggle_gold'][0] : null);

        } else {
            $gold_on = false;
        }

        $store_abs_cost_health = (isset($custom_fields['go_loot_loot_health'][0]) ? $custom_fields['go_loot_loot_health'][0] : null);
        if (get_option('options_go_loot_health_toggle') && $store_abs_cost_health > 0) {
            $health_on = true;
            $health_name = get_option('options_go_loot_health_name');
            $store_toggle_health = (isset($custom_fields['go_loot_reward_toggle_health'][0]) ? $custom_fields['go_loot_reward_toggle_health'][0] : null);

        } else {
            $health_on = false;
        }

        $purchase_count = go_get_purchase_count($post_id, $user_id, $custom_fields);

        $store_limit_toggle = (($custom_fields['go-store-options_limit_toggle'][0] == true) ? $custom_fields['go-store-options_limit_toggle'][0] : null);
        if ($store_limit_toggle) {
            $store_limit = (($custom_fields['go-store-options_limit_num'][0] == true) ? $custom_fields['go-store-options_limit_num'][0] : null);
        }
        $purchase_remaining_max = go_get_purchase_limit($post_id, $user_id, $custom_fields, $purchase_count);

        $badges_toggle = get_option('options_go_badges_toggle');
        if ($badges_toggle) {
            $badges = (($custom_fields['go_purch_reward_badges'][0] == true) ? $custom_fields['go_purch_reward_badges'][0] : null);
            $badges = unserialize($badges);
        }
        $groups = (($custom_fields['go_purch_reward_groups'][0] == true) ? $custom_fields['go_purch_reward_groups'][0] : null);
        $groups = unserialize($groups);


        echo '<div id="light" class="white_content">';
        echo "<h1>{$the_title}</h1>";

        echo '<div id="go-lb-the-content"><div id="go_store_description" >' . $the_content . '</div>';

        if (($xp_on && $store_toggle_xp == false) || ($gold_on && $store_toggle_gold == false) || ($health_on && $store_toggle_health == false)) {
            echo "<div id='go_store_loot'><div id='go_cost'> <div id='go_store_cost_container' class='go_store_container'> <div class='go_store_loot_left'><div class='go_round_button_container'><div id='gp_store_minus' class='go_store_round_button'>-</div></div></div><div class='go_store_loot_right'><h3>Cost</h3>";
            if ($xp_on && $store_toggle_xp == false) {
                echo '<div class="golb-fr-boxes-r">' . $store_abs_cost_xp . ' : ' . $xp_name . '</div>';
            }
            if ($gold_on && $store_toggle_gold == false) {
                echo '<div class="golb-fr-boxes-r">' . $store_abs_cost_gold . ' : ' . $gold_name . '</div>';
            }
            if ($health_on && $store_toggle_health == false) {
                echo '<div class="golb-fr-boxes-r">' . $store_abs_cost_health . ' : ' . $health_name . '</div>';
            }

            echo "</div></div></div>";
        }

        if (($xp_on && $store_toggle_xp == true) || ($gold_on && $store_toggle_gold == true) || ($health_on && $store_toggle_health == true) || (!empty($badges)) || (!empty($groups))) {
            echo "<div id='go_reward'><div class='go_store_container'> <div class='go_store_loot_left'><div class='go_round_button_container'><div id='gp_store_plus' class='go_store_round_button'>+</div></div></div><div class='go_store_loot_right'><h3>Reward</h3>";
            if ($xp_on && $store_toggle_xp == true) {
                echo '<div class="golb-fr-boxes-g">' . $store_abs_cost_xp . ' : ' . $xp_name . '</div>';
            }
            if ($gold_on && $store_toggle_gold == true) {
                echo '<div class="golb-fr-boxes-g">' . $store_abs_cost_gold . ' : ' . $gold_name . '</div>';
            }
            if ($health_on && $store_toggle_health == true) {
                echo '<div class="golb-fr-boxes-g">' . $store_abs_cost_health . ' : ' . $health_name . '</div>';
            }

            echo '<div id="go_badges_groups" style="display: flex; flex-wrap: wrap">';
            if (!empty($badges)) {
                $badges_name_plural = get_option('options_go_badges_name_plural');
                echo '<div id="go_store_badges" style="padding:10px;"><b>'.$badges_name_plural.'</b>';
                foreach ($badges as $badge) {
                    $term = get_term($badge);
                    $name = $term->name;
                    echo '<br>' . $name ;
                }
                echo '</div>';
            }

            if (!empty($groups)) {
                echo '<div id="go_store_groups" style="padding:10px;"><b>Groups</b>';
                foreach ($groups as $group) {
                    $term = get_term($group);
                    $name = $term->name;
                    echo '<br>' . $name . '</br>';
                }
                echo '</div>';
            }
            echo "</div></div></div>";
        }


        echo "</div></div></div>";

        ?>
        <div id="go_store_actions" style="display:flex; flex-wrap: wrap;">
            <?php
            $store_multiple_toggle = (isset($custom_fields['go-store-options_multiple'][0]) ? $custom_fields['go-store-options_multiple'][0] : null);

            if ($purchase_remaining_max > 0 && $store_multiple_toggle) {
                ?>

                <div id="golb-fr-qty" class="golb-fr-boxes-n">Qty: <input id="go_qty" type="number" value="1" disabled="disabled"/>
                </div>
                <?php
            }
            ?>

            <div id="go_purch_limits">
                <?php
                $store_limit_duration = false;
                if ($store_limit_duration == 'Total') {
                    $var1 = ' ';
                } else {
                    $var1 = ' / ';
                }

                if ($store_limit_toggle) {
                    ?>
                    <div id="golb-fr-purchase-limit"
                         val="<?php echo(!empty($purchase_remaining_max) ? $purchase_remaining_max : 0); ?>"><?php echo(($store_limit_toggle) ? "Limit {$store_limit}{$var1}{$store_limit_duration}" : 'No limit'); ?></div>
                    <?php
                }
                ?>
                <div id="golb-purchased">
                    <?php
                    if (is_null($purchase_count)) {
                        echo 'Quantity purchased: 0';
                    } else {
                        echo "Quantity purchased: {$purchase_count}";
                    }
                    ?>
                </div>
            </div>

            <?php
            if ($purchase_remaining_max > 0) {
                ?>
                <div id="golb-fr-buy" class="golb-fr-boxes-gold"
                     onclick="goBuytheItem( '<?php echo $post_id; ?>', '<?php echo $purchase_count ?>' ); this.removeAttribute( 'onclick' );">
                    Buy
                </div>
                <?php
            }
            if ($purchase_remaining_max == 0) {
                ?>
                <div class="error">You have reached your purchase limit or do not have enough loot to purchase this
                    item.
                </div>
                <?php
            }
            ?>

        </div></div>

        <?php
    }
    $store_html = ob_get_contents();
    ob_end_clean();

    echo json_encode(array('json_status' => $unlock_flag, 'html' => $store_html));
    die;

}

function go_get_purchase_count($post_id, $user_id, $custom_fields) {
    global $wpdb;
    $store_limit_frequency = ( ($custom_fields['go-store-options_limit_toggle'][0] == true ) ? $custom_fields['go-store-options_limit_frequency'][0] : null );
    $current_time = current_time('Ymd');

    //set the search for the correct time period
    if ($store_limit_frequency == 'day') {
        $timeSQL = ' Date(timestamp)= '.$current_time.' AND';
    }
    else if ($store_limit_frequency == 'week') {
        $timeSQL = ' YEARWEEK(timestamp)= YEARWEEK('.$current_time.') AND';
    }
    else if ($store_limit_frequency == 'month') {
        $timeSQL = ' Year(timestamp)=Year('.$current_time.') AND Month(timestamp)= Month('.$current_time.') AND';
    }
    else {
        $timeSQL = '';
    }

    $table_name = $wpdb->prefix."go_actions";

    if ( empty( $post_id ) ) {
        die( '0' );
    }

    $purchase_count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT SUM( stage ) 
			FROM {$table_name} 
			WHERE {$timeSQL}  source_id = %d AND uid = %d",
            $post_id,
            $user_id
        )
    );

    if ( empty( $purchase_count ) ) {
        return '0';
    } else {
        return $purchase_count;
    }
}

function go_get_purchase_limit($post_id, $user_id, $custom_fields, $purchase_count) {

    if ($purchase_count == null) {
        $purchase_count = go_get_purchase_count($post_id, $user_id, $custom_fields);
    }
    $store_limit = ( ($custom_fields['go-store-options_limit_toggle'][0] == true ) ? $custom_fields['go-store-options_limit_num'][0] : false );

    if ($store_limit){
        $purchases_left = $store_limit - $purchase_count;
    }else{
        $purchases_left = 9999;
    }

    $max_xp = 9999;
    $store_abs_cost_xp = (isset($custom_fields['go_loot_loot_xp'][0]) ?  $custom_fields['go_loot_loot_xp'][0] : null);
    if (get_option( 'options_go_loot_xp_toggle' ) && $store_abs_cost_xp > 0){
        $user_xp = go_return_points( $user_id );
        $store_toggle_xp = (isset($custom_fields['go_loot_reward_toggle_xp'][0]) ?  $custom_fields['go_loot_reward_toggle_xp'][0] : null);
        if ($store_toggle_xp == false){
            //$store_cost_xp = $store_abs_cost_xp * -1;
            $max_xp = $user_xp / $store_abs_cost_xp;
        }
    }

    $max_gold = 9999;
    $store_abs_cost_gold = (isset($custom_fields['go_loot_loot_gold'][0]) ?  $custom_fields['go_loot_loot_gold'][0] : null);
    if (get_option( 'options_go_loot_gold_toggle' )  && $store_abs_cost_gold > 0){
        $user_gold = go_return_currency( $user_id );
        $store_toggle_gold = (isset($custom_fields['go_loot_reward_toggle_gold'][0]) ?  $custom_fields['go_loot_reward_toggle_gold'][0] : null);
        if ($store_toggle_gold == false){
            $max_gold = $user_gold / $store_abs_cost_gold;
        }
    }

    $max_health = 9999;
    $store_abs_cost_health = (isset($custom_fields['go_loot_loot_health'][0]) ?  $custom_fields['go_loot_loot_health'][0] : null);
    if (get_option( 'options_go_loot_health_toggle' ) && $store_abs_cost_health > 0){
        $user_health = go_return_health ($user_id );
        $store_toggle_health = (isset($custom_fields['go_loot_reward_toggle_health'][0]) ?  $custom_fields['go_loot_reward_toggle_health'][0] : null);
        if ($store_toggle_health == false){
            $max_health = $user_health / $store_abs_cost_health;
        }
    }

    $purchase_remaining_min = floor(min($purchases_left, $max_xp, $max_gold, $max_health));

    if ($purchase_remaining_min < 0){
        $purchase_remaining_min = 0;
    }

    return $purchase_remaining_min;

}
?>