<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 11/28/18
 * Time: 6:07 AM
 */

function go_uWhere_values(){
    //CREATE THE QUERY
    //CREATE THE USER WHERE STATEMENT
    //check the drop down filters only
    //Query 1:
    //WHERE (uWhere)
    //User_meta by section_id from the drop down filter
    //loot table by badge_id from drop down filter
    //and group_id from the drop down filter.

    $section = $_GET['section'];
    $badge = $_GET['badge'];
    $group = $_GET['group'];

    $uWhere = "";
    if ((isset($section) && $section != "" ) || (isset($badge) && $badge != "") || (isset($group) && $group != "") )
    {
        $uWhere = "HAVING ";
        $uWhere .= " (";
        $first = true;

        //add search for section number
        if  (isset($section) && $section != "") {
            //search for badge IDs
            $sColumns = array('section_0', 'section_1', 'section_2', 'section_3', 'section_4', 'section_5', );
            $uWhere .= " (";
            $first = false;

            /*
            $search_array = $section;

            if ( isset($search_array) && !empty($search_array) )
            {
                for ( $i=0 ; $i<count($search_array) ; $i++ )
                {
                    for ($i2 = 0; $i2 < count($sColumns); $i2++) {
                        $uWhere .= "`" . $sColumns[$i2] . "` = " . intval($search_array[$i]) . " OR ";
                    }
                }
            }
            */
            for ($i = 0; $i < count($sColumns); $i++) {
                $uWhere .= "`" . $sColumns[$i] . "` = " . intval($section) . " OR ";
            }
            $uWhere = substr_replace( $uWhere, "", -3 );
            $uWhere .= ")";
        }

        if  (isset($badge) && $badge != "") {
            //search for badge IDs
            $sColumn = 'badges';
            if ($first == false) {
                $uWhere .= " AND (";
            }else {
                $uWhere .= " (";
                $first = false;
            }
            $search_var = $badge;
            $uWhere .= "`" . $sColumn . "` LIKE '%\"" . esc_sql($search_var). "\"%'";
            $uWhere .= ')';
        }

        if  (isset($group)  && $group != "") {
            //search for group IDs
            $sColumn = 'groups';
            if ($first == false) {
                $uWhere .= " AND (";
            }else {
                $uWhere .= " (";
                $first = false;
            }
            $search_var = $group;
            $uWhere .= "`" . $sColumn . "` LIKE '%\"" . esc_sql($search_var). "\"%'";
            $uWhere .= ')';
        }
        $uWhere .= ")";
    }
    return $uWhere;
}

function go_section(){
    $section = $_GET['section'];
    if ($section == ""){
        $section = 0;
    }
    //if (is_array($sections) && count($sections) === 1){
    //    $section = $sections[0];
    //}
    return $section;
}

function go_sWhere($sColumns){

    $search_val = $_GET['search']['value'];
    $sWhere = "";
    if ( isset($search_val) && $search_val != "" )
    {
        $sWhere = "WHERE  ";
        $sWhere .= "";

        //search these columns
        for ( $i=0 ; $i<count($sColumns) ; $i++ )
        {
            $sWhere .= "`".$sColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= '';
    }
    return $sWhere;
}

function go_sOrder($tab = null, $section = 0){
    if ($tab === null){
        return "";
    }
    $section = $_GET['section'];
    if ($section == "none"){
        $section = 0;
    }

    $order_dir = $_GET['order'][0]['dir'];
    $order_col = $_GET['order'][0]['column'];
    if ($order_col == 2){
        if ($section > 0 && !empty($section)){
            $order_col = 'the_section';
        }else {
            $order_col = 'section_0';//section
        }
    }
    if ($order_col == 3){
        if ($section > 0 && !empty($section)){
            $order_col = 'length(the_seat)';
            $order_col2 = 'the_seat';
        }else {
            $order_col = 'length(seat_0)';
            $order_col2 = 'seat_0';//section
        }
    }
    else if ($order_col == 4){
        $order_col = 'first_name';//first
    }
    else if ($order_col == 5){
        $order_col = 'last_name';//last
    }
    else if ($order_col == 6){
        $order_col = 'display_name';//display
    }

    if ($tab == 'stats') {
        if ($order_col == 8) {
            $order_col = 'xp';//xp
        }else if ($order_col == 9) {
            $order_col = 'xp';//xp
        } else if ($order_col == 10) {
            $order_col = 'gold';//gold
        } else if ($order_col == 11) {
            $order_col = 'health';//health
        } else if ($order_col == 12) {
            $order_col = 'badge_count';//badges
        } else if ($order_col == 13) {
            $order_col = 'length(groups)';//groups
        }

    }
    else if ($tab == 'store'){
        if ($order_col == 8){
            $order_col = 'id';//Time (ids are sequential)
        }
        else if ($order_col == 9){
            $order_col = 'post_title';
        }
        else if ($order_col == 10){
            $order_col = 'xp';//xp
        }
        else if ($order_col == 11){
            $order_col = 'gold';//gold
        }
        else if ($order_col == 12){
            $order_col = 'health';//health
        }

    }
    else if ($tab == 'tasks'){
        if ($order_col == 8){
            $order_col = 'post_title';//Time (ids are sequential)
        }
        else if ($order_col == 10){
            $order_col = 'start_time';
        }
        else if ($order_col == 11){
            $order_col = 'last_time';
        }
        else if ($order_col == 12){
            $order_col = 'timediff';
        }
        else if ($order_col == 13){
            $order_col = 'status';
        }
        else if ($order_col == 14){
            $order_col = 'status';
        }
        else if ($order_col == 14){
            $order_col = 'bonus_status';
        }
        else if ($order_col == 15){
            $order_col = 'xp';
        }
        else if ($order_col == 16){
            $order_col = 'gold';//gold
        }
        else if ($order_col == 17){
            $order_col = 'health';//health
        }

    }




    $sOrder = "ORDER BY " . $order_col . " " . $order_dir;
    if (isset($order_col2)){
        $sOrder .= " , " . $order_col2 . " " . $order_dir;
    }
    return $sOrder;
}

function go_sType(){
    $unmatched = $_GET['unmatched'];
    if ($unmatched === true || $unmatched == 'true' ) {
        $sType = "LEFT";//add switch for "show unmatched users" toggle
    }else{
        $sType = "INNER";
    }
    return $sType;
}

function go_sOn($action_type){
    $sOn = "";
    $date = $_GET['date'];
    if ($action_type == 'store' || $action_type == 'message') {
        $sOn = "AND (action_type = '" . $action_type . "') ";

        if (isset($date) && $date != "") {
            $date = date("Y-m-d", strtotime($date));
            $sOn .= " AND ( DATE(t4.TIMESTAMP) = '" . $date . "')";
        }
    }else if($action_type == 'tasks'){
        if (isset($date) && $date != "") {
            $date = date("Y-m-d", strtotime($date));
            $sOn .= " AND ( DATE(t4.last_time) = '" . $date . "')";
        }
    }
    return $sOn;
}
