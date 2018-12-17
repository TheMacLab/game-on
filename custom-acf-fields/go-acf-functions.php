<?php
/**
 * Created by PhpStorm.
 * User: mcmurray
 * Date: 3/29/18
 * Time: 10:03 PM
 */


/**
 * @param $field
 * @return mixed
 * Loads the seating chart from the options page in various ACF fields
 */

function acf_load_seat_choices( $field ) {

    // reset choices
    //$field['choices'] = array();
    $field['choices'] = null;
    $field['choices'][ null ] = "Select";
    $name = get_option('options_go_seats_name');
    $number = get_option('options_go_seats_number');
    $field['placeholder'] = 'Select';

    if ($number > 0){
        $i = 0;
        while ($i < $number){
            $i++;

            // vars
            $value = $name . " " . $i;


            // append to choices
            $field['choices'][ $value ] = $value;

        }
    }
    // return the field
    return $field;

}
add_filter('acf/load_field/name=user-seat', 'acf_load_seat_choices');

function acf_load_xp_levels( $field ) {

    // reset choices
    //$field['choices'] = array();
    $field['choices'] = null;
    $field['choices'][ null ] = "Select";
    $num_levels = get_option('options_go_loot_xp_levels_level');
    $number = get_option('options_go_seat_number');
    $field['placeholder'] = 'Select';

    for ($i = 0; $i < $num_levels; $i++) {
        $num = $i+1;
        $xp = get_option('options_go_loot_xp_levels_level_' . $i . '_xp');
        $level_name = get_option('options_go_loot_xp_levels_level_' . $i . '_name');
        $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );

        $name = "Level" . $num . " - " . "$level_name" . " : " . $xp . " " . $xp_abbr;

        $field['choices'][ $xp ] = $name;
    }



    // return the field
    return $field;

}
add_filter('acf/load_field/key=field_5b23676184648', 'acf_load_xp_levels');
add_filter('acf/load_field/key=field_5b52731ddd4f7', 'acf_load_xp_levels');


/**
 * Flushes the rewrite rules when options page is saved.
 * It does this by setting a option to true (1) and then another action
 * actually does the update later and sets the flag back to false.
 * @param $post_id
 * @return string
 * Modified From : https://wordpress.stackexchange.com/questions/182798/flush-rewrite-rules-on-save-post-does-not-work-on-first-post-save
 * Modified From: https://support.advancedcustomfields.com/forums/topic/when-using-save_post-action-how-do-you-identify-which-options-page/
 */
function acf_flush_rewrite_rules( $post_id ) {

    if ($post_id == 'options') {
        update_option( 'go-flush-rewrite-rules', 1 );
        //flush_rewrite_rules(true);
    }

}

add_action('acf/save_post', 'acf_flush_rewrite_rules', 2);

function go_late_init_flush() {

    if ( ! $option = get_option( 'go-flush-rewrite-rules' ) ) {
        return false;
    }

    if ( $option == 1 ) {

        flush_rewrite_rules();
        update_option( 'go-flush-rewrite-rules', 0 );

    }

    return true;

}

add_action( 'init', 'go_late_init_flush', 999999 );




/**
 *Loads the default options in bonus loot
 * Default is set in options and loaded on tasks
 * both the next two functions are needed
 */
function default_value_field_5b526d2e7957e($value, $post_id, $field) {
    if ($value === false) {
        $row_count = get_option('options_go_loot_bonus_loot');
        $value = array();
        if(!empty($row_count)){
            for ($i = 0; $i < $row_count; $i++) {
                $title = "options_go_loot_bonus_loot_" . $i . "_title";
                $title = get_option($title);
                $message = "options_go_loot_bonus_loot_" . $i . "_message";
                $message = get_option($message);
                $xp = "options_go_loot_bonus_loot_" . $i . "_defaults_xp";
                $xp = get_option($xp);
                $gold = "options_go_loot_bonus_loot_" . $i . "_defaults_gold";
                $gold = get_option($gold);
                $health = "options_go_loot_bonus_loot_" . $i . "_defaults_health";
                $health = get_option($health);
                $drop = "options_go_loot_bonus_loot_" . $i . "_defaults_drop_rate";
                $drop = get_option($drop);

                $loot_val = array(
                    'field_5b526d2e79583' => $xp,
                    'field_5b526d2e79584' => $gold ,
                    'field_5b526d2e79585' => $health,
                    'field_5b526d2e79588' => $drop
                );
                $row_val = array(
                    'field_5b526d2e7957f' => $title,
                    'field_5b526d2e79580' => $message,
                    'field_5b526d2e79582' => $loot_val
                );

                $value[] = $row_val;

            }

        }
    }
    return $value;
}
add_filter('acf/load_value/key=field_5b526d2e7957e', 'default_value_field_5b526d2e7957e', 10, 3);






