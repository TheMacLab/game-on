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
 * Loads the class periods/sections from the options page in various ACF fields
 */

function acf_load_section_choices( $field ) {

    // reset choices
    $field['choices'] = array();


    // if has rows
    if( have_rows('sections', 'option') ) {

        // while has rows
        while( have_rows('sections', 'option') ) {

            // instantiate row
            the_row();

            // vars
            $value = get_sub_field('section');

            // append to choices
            $field['choices'][ $value ] = $value;

        }
    }
    // return the field
    return $field;

}

add_filter('acf/load_field/name=lock_sections_js_load', 'acf_load_section_choices');
add_filter('acf/load_field/name=user-section', 'acf_load_section_choices');
add_filter('acf/load_field/name=sched_sections_js_load', 'acf_load_section_choices');

//not sure if this is needed
function acf_load_course_sections( $field ) {

    // reset choices
    $field['choices'] = array();

    // get the class periods from options page without any formatting
    $choices = go_return_options( 'go_class_a' );

    // loop through array and add to field 'choices'
    if( is_array($choices) ) {

        foreach( $choices as $choice ) {

            $field['choices'][ $choice ] = $choice;
        }
    }

    // return the field
    return $field;
}

add_filter('acf/load_field/name=course_section', 'acf_load_course_sections');




/**
 * @param $field
 * @return mixed
 * Loads the seating chart from the options page in various ACF fields
 */

function acf_load_seat_choices( $field ) {

    // reset choices
    $field['choices'] = array();


    // if has rows
    if( have_rows('seats', 'option') ) {

        // while has rows
        while( have_rows('seats', 'option') ) {

            // instantiate row
            the_row();


            // vars
            $value = get_sub_field('seat');
            //$label = get_sub_field('label');


            // append to choices
            $field['choices'][ $value ] = $value;

        }

    }


    // return the field
    return $field;

}


add_filter('acf/load_field/name=user-seat', 'acf_load_seat_choices');


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
 * @param $post_id
 * @param $order_field
 * @param $item_order_field
 * @param $toggle
 * @param $term
 */
function go_update_order($post_id, $order_field, $item_order_field, $toggle, $term)  {
    $term_obj = get_term($term);
    $taxonomy = $term_obj->taxonomy;
    //$term_order = get_term_meta( $term, 'go_order', true );
    $order = get_post_meta($post_id, $order_field, true);


    if ($toggle == false) {
        delete_post_meta($post_id, $item_order_field);
        wp_remove_object_terms($post_id, $term, $taxonomy);
    } elseif ($toggle == true) {
        if (empty($term)) {
            delete_post_meta($post_id, $item_order_field);
        } else {
            $i = 0;
            foreach ($order as $item) {
                // for each post in the value, set term
                wp_set_post_terms($item, $term, $taxonomy);
                // for each post in the value, set order
                update_post_meta($item, $item_order_field, $i);
                $i++;
            }
        }

    }
    delete_post_meta($post_id, $order_field);
}


/**
 * @param $post_id
 */
function acf_update_order($post_id ) {

    // bail early if no ACF data
    if( empty($_POST['acf']) ) {

        return;

    }

    // top menu
    $order_field = 'go-location_top_order';
    $item_order_field = 'go-location_top_order_item';
    $toggle = get_post_meta ($post_id, 'go-location_top_toggle', true);
    $term = get_post_meta ($post_id, 'go-location_top_menu', true);
    go_update_order($post_id, $order_field, $item_order_field, $toggle, $term);

    // side widget
    $order_field = 'go-location_side_order';
    $item_order_field = 'go-location_side_order_item';
    $toggle = get_post_meta ($post_id, 'go-location_side_toggle', true);
    $term = get_post_meta ($post_id, 'go-location_side_menu', true);
    go_update_order($post_id, $order_field, $item_order_field, $toggle, $term);

    $order_field = 'go-location_map_order';
    $item_order_field = 'go-location_map_order_item';
    $toggle = get_post_meta ($post_id, 'go-location_map_toggle', true);
    $term = get_post_meta ($post_id, 'go-location_map_loc', true);
    go_update_order($post_id, $order_field, $item_order_field, $toggle, $term);
}
add_action('acf/save_post', 'acf_update_order', 99);







