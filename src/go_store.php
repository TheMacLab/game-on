<?php

function go_store_activate() {

	$my_post = array(
	  'post_title'    => 'Store',
	  'post_content'  => '[go_make_store]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'   => 'page',
	);

	// Insert the post into the database
	
		$page = get_page_by_path( "store" , OBJECT );

     if ( ! isset($page) ){
     	wp_insert_post( $my_post );
     }
}

/**
 *
 */
function go_make_store() {
    if ( ! is_admin() ) {
        $args = array('hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC', 'parent' => '0');

        /* Get all task chains with no parents--these are the sections of the store.  */
        $taxonomy = 'store_types';


        $rows = get_terms($taxonomy, $args);//the rows
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
                $args = array('tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $column_id,)), 'orderby' => 'meta_value_num', 'order' => 'ASC', 'posts_per_page' => -1, 'meta_key' => 'go-store-location_store_item', 'meta_value' => '', 'post_type' => 'go_store', 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true

                );

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
                        $store_item_name = get_the_title($go_store_obj);
                        //echo "<li><a id='$row' class='go_str_item' onclick='go_lb_opener(this.id);'>$store_item_name</a></li> ";
                        echo "<li><a id='$store_item_id' class='go_str_item' >$store_item_name</a></li> ";
                        //echo "<button id='$row' class='go_str_item' >$store_item_name</button> ";
                    }
                }
                echo "</ul></div> ";
            }
            echo "</div> ";
        }
        echo "</div>";
    }
}
add_shortcode('go_make_store', 'go_make_store');

?>
