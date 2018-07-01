<?php

/**
 * Determines if the user has enough of XP, Gold, Honor, Damage, and/or Minutes to purchase the item.
 *
 * @since <2.0.0
 *
 * @param int $req The base cost of the store item.
 * @param int $qty The number of items being purchased.
 * @param int $cur The currency that the user currently has.
 * @return boolean True if the user can purchase the item, and false if they can't.
 */
function go_user_has_enough_currency( $base = 0, $qty = 1, $cur = 0 ) {
	$cost = $base * $qty;
	if ( $cost > 0 && $cur < $cost ) {
		return false;
	}

	return true;
}

function go_buy_item() {
	global $wpdb;

	//$user_id = get_current_user_id();
    $user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function
	$is_logged_in = ! empty( $user_id ) && $user_id > 0  && is_user_member_of_blog( $user_id ) ? true : false;
	if (!$is_logged_in){
        //echo 'Error: You must be logged in to use the store.';
        echo "<script> new Noty({
                type: 'info',
                layout: 'topRight',
                text: 'Error: You must be logged in to use the store.',
                theme: 'relax'
                }).show();parent.window.$.featherlight.current().close();</script>";
        die();
    }
	if ( ! check_ajax_referer( 'go_buy_item_' . $user_id, false ) ) {
        echo "<script> new Noty({
                type: 'info',
                layout: 'topRight',
                text: 'Error: WordPress hiccuped, try logging in again.' ,
                theme: 'relax'
                }).show();parent.window.$.featherlight.current().close();</script>";
		die();
	}

	$go_task_table_name = $wpdb->prefix."go";
	$post_id = ( ! empty( $_POST["the_id"] ) ? (int) $_POST["the_id"] : 0 );
    $custom_fields = get_post_custom( $post_id );
    $qty = ( ! empty( $_POST['qty'] ) && (int) $_POST['qty'] > 0 ? (int) $_POST['qty'] : 1 );

    $store_limit_toggle = ( ($custom_fields['go-store-options_limit_toggle'][0] == true ) ? $custom_fields['go-store-options_limit_toggle'][0] : null );
    if ($store_limit_toggle) {
        $purchase_remaining_max = go_get_purchase_limit($post_id, $user_id, $custom_fields, null);

        if ($qty > $purchase_remaining_max) {
            //echo 'Error: You attempted to buy more than your current limit. Try again.';
            echo "<script> new Noty({
                type: 'info',
                layout: 'topRight',
                text: 'Error: You attempted to buy more than your current limit.',
                theme: 'relax'
                }).show();parent.window.$.featherlight.current().close();</script>";
            die();
        }
    }

    $the_title = get_the_title($post_id);
    $health_mod = go_get_health_mod ($user_id);
    //$health_mod = go_get_user_loot( $user_id, 'health' );

    $store_abs_cost_xp = (isset($custom_fields['go_loot_loot_xp'][0]) ?  $custom_fields['go_loot_loot_xp'][0] : null);
    if (get_option( 'options_go_loot_xp_toggle' ) && $store_abs_cost_xp > 0){
        $xp_mod_toggle = get_option('options_go_loot_xp_mods_toggle');
        $store_mod_val = $store_abs_cost_xp;
        if ($xp_mod_toggle) {
            $store_mod_val = ceil($store_abs_cost_xp * $health_mod);
        }
        $store_toggle_xp = (isset($custom_fields['go_loot_reward_toggle_xp'][0]) ?  $custom_fields['go_loot_reward_toggle_xp'][0] : null);
        if ($store_toggle_xp == false){
            $xp = $qty * ($store_mod_val) * -1;
        }
        else{
            $xp = $qty * ($store_mod_val);
        }
    }

    $store_abs_cost_gold = (isset($custom_fields['go_loot_loot_gold'][0]) ?  $custom_fields['go_loot_loot_gold'][0] : null);
    if (get_option( 'options_go_loot_gold_toggle' )  && $store_abs_cost_gold > 0){
        $gold_mod_toggle = get_option('options_go_loot_gold_mods_toggle');
        $store_mod_val = $store_abs_cost_gold;
        if ($gold_mod_toggle) {
            $store_mod_val = ceil($store_abs_cost_gold * $health_mod);
        }
        $store_toggle_gold = (isset($custom_fields['go_loot_reward_toggle_gold'][0]) ?  $custom_fields['go_loot_reward_toggle_gold'][0] : null);
        if ($store_toggle_gold == false){
            $gold = $qty * ($store_mod_val) * -1;
        }
        else{
            $gold = $qty * ($store_mod_val);
        }
    }

    $store_abs_cost_health = (isset($custom_fields['go_loot_loot_health'][0]) ?  $custom_fields['go_loot_loot_health'][0] : null);
    if (get_option( 'options_go_loot_health_toggle' ) && $store_abs_cost_health > 0){
        $health_mod_toggle = get_option('options_go_loot_health_mods_toggle');
        $store_mod_val = $store_abs_cost_health;
        if ($health_mod_toggle) {
            $store_mod_val = ceil($store_abs_cost_health * $health_mod);
        }
        $store_toggle_health = (isset($custom_fields['go_loot_reward_toggle_health'][0]) ?  $custom_fields['go_loot_reward_toggle_health'][0] : null);
        if ($store_toggle_health == false){
            $health = $qty * ($store_mod_val) * -1;
        }
        else{
            $health = $qty * ($store_mod_val);
        }
    }

    $store_abs_cost_c4 = (isset($custom_fields['go_loot_loot_c4'][0]) ?  $custom_fields['go_loot_loot_c4'][0] : null);
    if (get_option( 'options_go_loot_c4_toggle' ) && $store_abs_cost_c4 > 0){
        $c4_mod_toggle = get_option('options_go_loot_c4_mods_toggle');
        $store_mod_val = $store_abs_cost_c4;
        if ($c4_mod_toggle) {
            $store_mod_val = ceil($store_abs_cost_c4 * $health_mod);
        }
        $store_toggle_c4 = (isset($custom_fields['go_loot_reward_toggle_c4'][0]) ?  $custom_fields['go_loot_reward_toggle_c4'][0] : null);
        if ($store_toggle_c4 == false){
            $c4 = $qty * ($store_mod_val) * -1;
        }
        else{
            $c4 = $qty * ($store_mod_val);
        }
    }
    ob_start();
    go_update_actions( $user_id, 'store',  $post_id, $qty, null, null, 'purchase', null, null, null, null,  $xp, $gold, $health, $c4, $badges, true);

    //go_update_totals_table($user_id, $xp, $gold, $health, $c4, null, true);

    //echo "<h2>Success</h2><br>Item: ". $the_title . "<br>Quantity: " . $qty  ;

    echo "<script> new Noty({
    type: 'info',
    layout: 'topRight',
    text: '<h2>Receipt</h2><br>Item: $the_title <br>Quantity: $qty',
    theme: 'relax'
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
?>