<?php

function go_make_store_html() {

    $args = array('hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC', 'parent' => '0');

    /* Get all task chains with no parents--these are the sections of the store.  */
    $taxonomy = 'store_types';

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );


    $rows = get_terms($taxonomy, $args);//the rows
    ob_start();
    echo '
        <div id="storemap" style="display:block;">';


    /* For each Store Category with no parent, get all the children.  These are the store rows.*/
    $chainParentNum = 0;
    echo '<div id="store">';
    //for each row
    foreach ($rows as $row) {
        $chainParentNum++;
        $row_id = $row->term_id;//id of the row
        $custom_fields = get_term_meta($row_id);
        $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ? $custom_fields['go_hide_store_cat'][0] : null);
        if ($cat_hidden == true) {
            continue;
        }


        echo "<div id='row_$chainParentNum' class='store_row_container'>
                            <div class='parent_cat'><h2>$row->name</h2></div>
                            <div class='store_row'>
                            ";//row title and row container


        $column_args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => $row_id,

        );

        $columns = get_terms($taxonomy, $column_args);
        /*Loop for each chain.  Prints the chain name then looks up children (quests). */
        foreach ($columns as $column) {
            $column_id = $column->term_id;
            $custom_fields = get_term_meta($column_id);
            $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ? $custom_fields['go_hide_store_cat'][0] : null);
            if ($cat_hidden == true) {
                continue;
            }


            echo "<div class ='store_cats'><h3>$column->name</h3><ul class='store_items'>";
            /*Gets a list of store items that are assigned to each chain as array. Ordered by post ID */

            ///////////////
            ///
            $args = array('tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $column_id,)), 'orderby' => 'meta_value_num', 'order' => 'ASC', 'posts_per_page' => -1, 'meta_key' => 'go-store-location_store_item', 'meta_value' => '', 'post_type' => 'go_store', 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true);

            $go_store_objs = get_posts($args);

            //////////////////
            /// ////////////////////
            //$go_store_ids = get_objects_in_term( $column_id, $taxonomy );

            /*Only loop through for first item in array.  This will get the correct order
            of items from the post metadata */

            if (!empty($go_store_objs)) {

                foreach ($go_store_objs as $go_store_obj) {

                    $status = get_post_status($go_store_obj);



                    if ($status !== 'publish') {
                        continue;
                    }
                    $store_item_id = $go_store_obj->ID;
                    $custom_fields = get_post_custom($store_item_id);
                    $xp_toggle = (isset($custom_fields['go_loot_reward_toggle_xp'][0]) ?  $custom_fields['go_loot_reward_toggle_xp'][0] : null);
                    $xp_value = (isset($custom_fields['go_loot_loot_xp'][0]) ?  $custom_fields['go_loot_loot_xp'][0] : null);
                    $gold_toggle = (isset($custom_fields['go_loot_reward_toggle_gold'][0]) ?  $custom_fields['go_loot_reward_toggle_gold'][0] : null);
                    $gold_value = (isset($custom_fields['go_loot_loot_gold'][0]) ?  $custom_fields['go_loot_loot_gold'][0] : null);
                    $health_toggle = (isset($custom_fields['go_loot_reward_toggle_health'][0]) ?  $custom_fields['go_loot_reward_toggle_health'][0] : null);
                    $health_value = (isset($custom_fields['go_loot_loot_health'][0]) ?  $custom_fields['go_loot_loot_health'][0] : null);

                    $store_item_name = get_the_title($go_store_obj);
                    //echo "<li><a id='$row' class='go_str_item' onclick='go_lb_opener(this.id);'>$store_item_name</a></li> ";
                    echo "<li><a id='$store_item_id' class='go_str_item' >$store_item_name</a>";
                    echo "<div class='go_store_loot_list'>";
                    if (!empty($xp_value)){
                        if ($xp_toggle == 1 ){
                            $loot_class = 'go_store_loot_list_reward';
                            $loot_direction = "+";

                        }
                        else{
                            $loot_class = 'go_store_loot_list_cost';
                            $loot_direction = "-";
                        }
                        echo "<div id = 'go_store_loot_list_xp' class='go_store_loot_list_item " . $loot_class . "' >" . $loot_direction . $xp_value . $xp_abbr . "</div > ";
                    }
                    if (!empty($gold_value)){
                        if ($gold_toggle == 1 ){
                            $loot_class = 'go_store_loot_list_reward';
                            $loot_direction = "+";
                        }
                        else{
                            $loot_class = 'go_store_loot_list_cost';
                            $loot_direction = "-";
                        }
                        echo "<div id = 'go_store_loot_list_gold' class='go_store_loot_list_item " . $loot_class . "' >" . $loot_direction . $gold_value . $gold_abbr . "</div > ";
                    }
                    if (!empty($health_value)){
                        if ($health_toggle == 1 ){
                            $loot_class = 'go_store_loot_list_reward';
                            $loot_direction = "+";
                        }
                        else{
                            $loot_class = 'go_store_loot_list_cost';
                            $loot_direction = "-";
                        }
                        echo "<div id = 'go_store_loot_list_health' class='go_store_loot_list_item " . $loot_class . "' >" . $loot_direction . $health_value . $health_abbr . "</div > ";
                    }

                    echo "</div>";
                    echo "</li> ";
                    //echo "<button id='$row' class='go_str_item' >$store_item_name</button> ";
                }
            }
            echo "</ul></div> ";
        }
        echo "</div></div> ";
    }
    echo "</div></div>";
    $store_html = ob_get_contents();
    ob_end_clean();

    return $store_html;

}

function go_make_store_dropdown() {

    $args = array('hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC', 'parent' => '0');

    /* Get all task chains with no parents--these are the sections of the store.  */
    $taxonomy = 'store_types';

    $rows = get_terms($taxonomy, $args);//the rows
    echo "<select id='go_store_item_select' class='go_tax_select' data-placeholder='Show All' multiple>";

    //echo "<option value='none' ><b>Show All</b></option>";
    echo "<option></option>";


    /* For each Store Category with no parent, get all the children.  These are the store rows.*/
    $chainParentNum = 0;
    //for each row
    foreach ($rows as $row) {
        $chainParentNum++;
        $row_id = $row->term_id;//id of the row
        $custom_fields = get_term_meta($row_id);
        $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ? $custom_fields['go_hide_store_cat'][0] : null);
        if ($cat_hidden == true) {
            continue;
        }

        echo  "<optgroup label='" . $row->name . "' ></optgroup>" ;

        $column_args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => $row_id,

        );

        $columns = get_terms($taxonomy, $column_args);
        /*Loop for each chain.  Prints the chain name then looks up children (quests). */
        foreach ($columns as $column) {

            echo  "<optgroup label='" . $column->name . "' >" ;

            $column_id = $column->term_id;
            /*Gets a list of store items that are assigned to each chain as array. Ordered by post ID */
            $args = array('tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $column_id,)), 'orderby' => 'meta_value_num', 'order' => 'ASC', 'posts_per_page' => -1, 'meta_key' => 'go-store-location_store_item', 'meta_value' => '', 'post_type' => 'go_store', 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true);
            $go_store_objs = get_posts($args);

            //////////////////
            /// ////////////////////
            //$go_store_ids = get_objects_in_term( $column_id, $taxonomy );

            /*Only loop through for first item in array.  This will get the correct order
            of items from the post metadata */

            if (!empty($go_store_objs)) {

                foreach ($go_store_objs as $go_store_obj) {

                    $status = get_post_status($go_store_obj);



                    if ($status !== 'publish') {
                        continue;
                    }
                    $store_item_id = $go_store_obj->ID;

                    echo "<option value='" . $go_store_obj->ID . "'>" . get_the_title($go_store_obj) . "</option>";
                }
            }

            echo "</optgroup>";
        }

    }

    echo "</select>";

}

function go_make_store_dropdown_ajax() {
    // we will pass post IDs and titles to this array
    $return = array();

    // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
    $search_results = new WP_Query( array(
        's'=> $_GET['q'], // the search query
        'post_status' => 'publish', // if you don't want drafts to be returned
        'ignore_sticky_posts' => 1,
        'posts_per_page' => 50, // how much to show at once\
        'post_type' => 'go_store'
    ) );
    if( $search_results->have_posts() ) :
        while( $search_results->have_posts() ) : $search_results->the_post();
            // shorten the title a little
            $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
            $return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
        endwhile;
    endif;
    echo json_encode( $return );
    die;

}



?>
