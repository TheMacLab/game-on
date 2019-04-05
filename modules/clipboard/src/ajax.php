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
    $unmatched = (isset($_GET['unmatched']) ? $_GET['unmatched'] : false);
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
        if (isset($date) && $date != "") {
            $dates = explode(' - ', $date);
            $firstdate = $dates[0];
            $lastdate = $dates[1];
            $firstdate = date("Y-m-d", strtotime($firstdate));
            $lastdate = date("Y-m-d", strtotime($lastdate));
            $date = " AND ( DATE(t4.TIMESTAMP) BETWEEN '" . $firstdate . "' AND '" . $lastdate . "')";
        }
        $sOn = "AND (action_type = '" . $action_type . "') ";
        $sOn .= $date;


    }else if($action_type == 'tasks'){
        if (isset($date) && $date != "") {
            $dates = explode(' - ', $date);
            $firstdate = $dates[0];
            $lastdate = $dates[1];
            $firstdate = date("Y-m-d", strtotime($firstdate));
            $lastdate = date("Y-m-d", strtotime($lastdate));
            $date = " AND ( DATE(t4.last_time) BETWEEN '" . $firstdate . "' AND '" . $lastdate . "')";
        }
        $sOn .= $date;
    }
    return $sOn;
}

/**
 * The first rows on several tables.
 */
function go_start_row($action){
    $row = array();
    $user_id = $action['uid'];
    $user_display_name = $action['display_name'];
    $user_firstname = $action['first_name'];
    $user_lastname = $action['last_name'];
    $num_sections = $action['num_section'];
    $the_section = intval($action['the_section']);
    $the_seat = $action['the_seat'];
    $website = $action['user_url'];
    $login = $action['user_login'];

    //Get the users section(s)
    if (empty($num_sections) || ($the_section > 0)){
        $num_sections = 1;
    }
    $user_section_names= array();
    $user_seat = array();
    for ($i = 0; $i < $num_sections; $i++) {
        if($the_section > 0){
            $term = get_term($the_section, "user_go_sections");
            //add the section name to the list of sections
            $user_section_names[] = (isset($term->name) ? $term->name : null);
            $user_seat[] = $the_seat;
        }else {
            $user_section_option = "section_" . $i;
            $user_seat_option = "seat_" . $i;
            //get the section that is set, by
            $user_section = $action[$user_section_option];
            //get term
            $term = get_term($user_section, "user_go_sections");
            //add the section name to the list of sections
            $user_section_names[] = (isset($term->name) ? $term->name : null);
            $user_seat[] = $action[$user_seat_option];
        }
    }
    $user_section_names = implode("<br>", $user_section_names);
    $user_seats = implode("<br>", $user_seat);

    ob_start();
    go_user_links($user_id, true, true, true, true, true, false, true, $website, $login);
    $links = ob_get_clean();


    $task_id = (isset($action['post_id']) ?  $action['post_id'] : null);
    //if ($task_id != null) {
    //    $task_id = "data-task='" . $task_id . "'";
    //}

    $check_box = "<input class='go_checkbox' type='checkbox' name='go_selected' data-uid='" . $user_id . "' data-task='". $task_id . "'/>";

    $row[] = "";
    $row[] = "{$check_box}";
    $row[] = "{$user_section_names}";//user period
    $row[] = "{$user_seats}";//user seat
    $row[] = "{$user_firstname}";
    $row[] = "{$user_lastname}";
    $row[] = "{$user_display_name}";
    $row[] = "{$links}";

    return $row;

}


/**
 * Called by the ajax dataloaders.
 * @param $action
 * @return array
 */
function go_loot_columns_clipboard($action){
    $xp = $action['xp'];
    $gold = $action['gold'];
    $health = $action['health'];

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');
    $row = array();
    if ($xp_toggle){
        $row[] = "{$xp}";
    }
    if ($gold_toggle){
        $row[] = "{$gold}";
    }
    if ($health_toggle){
        $row[] = "{$health}";
    }
    return $row;
}

/**
 * Called by the ajax dataloaders.
 * @param $TIMESTAMP
 * @return false|string
 */
function go_clipboard_time($TIMESTAMP){
    if ($TIMESTAMP != null) {
        $time = date("m/d/y g:i A", strtotime($TIMESTAMP));
    }else{
        $time = "N/A";
    }
    return $time;
}

/**
 * @param $badge_ids
 * @param $group_ids
 * @return string
 */
function go_badges_and_groups($badge_ids, $group_ids){
    $bg_links = '';
    $badges_toggle = get_option('options_go_badges_toggle');
    if ($badges_toggle) {
        $badges_names = array();
        $badge_ids = unserialize($badge_ids);
        $badges_name_sing = get_option('options_go_badges_name_singular');

        if (!empty($badge_ids)) {
            $badges_names_heading = "<b>" . $badges_name_sing . ": </b>";
            foreach ($badge_ids as $badge_id) {
                $term = get_term($badge_id, "go_badges");
                $badge_name = $term->name;
                $badges_names[] = $badge_name;
            }
            $badges_names = $badges_names_heading . implode(", " , $badges_names);
            $bg_links = '<i class="fa fa-certificate" aria-hidden="true"></i>';
        }else{
            $badges_names = "";
        }
    }else{
        $badges_names = "";
    }

    $group_names = array();
    $group_ids = unserialize($group_ids);
    if (!empty($group_ids)){
        $group_names_heading = "<b>Group: </b>";
        foreach ($group_ids as $group_id) {
            $term = get_term($group_id, "user_go_groups");
            $group_name = $term->name;
            $group_names[] = $group_name;
        }
        $group_names = $group_names_heading . implode(", " , $group_names);
        $bg_links = ' <i class="fa fa-users" aria-hidden="true"></i>';
    }else{
        $group_names = "";
    }

    if (!empty($badges_names) && !empty($group_names) ) {
        $badges_names = $badges_names . "<br>" ;
    }
    $badges_names =  $badges_names . $group_names;

    $badges_and_groups = '<span class="tooltip" data-tippy-content="'. $badges_names .'">'. $bg_links . '</span>';

    return $badges_and_groups;
}

/**
 * Called by the ajax dataloaders.
 */
function go_clipboard_stats() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }

    global $wpdb;
    check_ajax_referer( 'go_clipboard_stats_' . get_current_user_id() );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');
    $badges_toggle = get_option('options_go_badges_toggle');

    // prepares tab titles
    //$xp_name = get_option( "options_go_loot_xp_name" );
    //$gold_name = get_option( "options_go_loot_gold_name" );
    $badges_name = get_option('options_go_badges_name_plural');

    $xp_abbr = get_option("options_go_loot_xp_abbreviation");
    $gold_abbr = get_option("options_go_loot_gold_abbreviation");
    $health_abbr = get_option("options_go_loot_health_abbreviation");

    $seats_name = get_option('options_go_seats_name');


    echo '<div id="go_clipboard_wrapper" class="go_clipboard">';
    ?>

    <table id='go_clipboard_stats_datatable' class='pretty display'>
        <thead>
        <tr>
            <th></th>
            <th><input type="checkbox" onClick="go_toggle(this);"/></th>
            <th class="header">Section</th>
            <th class="header"><?php echo "$seats_name"; ?></a></th>
            <th class="header">First</th>
            <th class="header">Last</th>
            <th class="header">Display</th>
            <th class="header">Links</th>

            <?php
            if ($xp_toggle) {
                ?>
                <th class="header"><?php echo get_option('options_go_loot_xp_levels_name_singular'); ?></th>
                <th class='header'><?php echo "$xp_abbr"; ?></th>
                <?php
            }
            if ($gold_toggle) {
                ?>
                <th class='header'><?php echo "$gold_abbr"; ?></th>
                <?php
            }
            if ($health_toggle) {
                ?>
                <th class='header'><?php echo "$health_abbr"; ?></th>
                <?php
            }
            if ($badges_toggle) {
                ?>
                <th class='header'><?php echo "$badges_name"; ?></th>
                <?php
            }

            ?>
            <th class='header'>Groups</th>


        </tr>
        </thead>
    </table></div>


    <?php
    die();


}

/**
 *
 */
function go_clipboard_stats_dataloader_ajax(){
    global $wpdb;
    $sColumns = array('first_name', 'last_name', 'display_name', 'xp', 'gold', 'health');

    $section = go_section();
    $uWhere = go_uWhere_values();
    $sWhere = go_sWhere( $sColumns);
    $sLimit = '';
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    $sOrder = go_sOrder('stats', $section);

    $lTable = "{$wpdb->prefix}go_loot";
    $uTable = "{$wpdb->prefix}users";
    $umTable = "{$wpdb->prefix}usermeta";

    $sQuery = "
      SELECT SQL_CALC_FOUND_ROWS
        t5.*,
        CASE 
          WHEN t5.section_0 = $section THEN t5.seat_0
          WHEN t5.section_1 = $section THEN t5.seat_1 
          WHEN t5.section_2 = $section THEN t5.seat_2 
          WHEN t5.section_3 = $section THEN t5.seat_3 
          WHEN t5.section_4 = $section THEN t5.seat_4 
          WHEN t5.section_5 = $section THEN t5.seat_5 
          ELSE 0
          END AS the_seat,
      CASE 
          WHEN t5.section_0 = $section THEN t5.section_0
          WHEN t5.section_1 = $section THEN t5.section_1 
          WHEN t5.section_2 = $section THEN t5.section_2 
          WHEN t5.section_3 = $section THEN t5.section_3 
          WHEN t5.section_4 = $section THEN t5.section_4 
          WHEN t5.section_5 = $section THEN t5.section_5 
          ELSE 0
          END AS the_section    
      FROM (
              SELECT
              t1.*,
              MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
              MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END)  AS section_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-seat' THEN meta_value END)  AS seat_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-seat' THEN meta_value END) AS seat_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-seat' THEN meta_value END) AS seat_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-seat' THEN meta_value END) AS seat_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-seat' THEN meta_value END) AS seat_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-seat' THEN meta_value END) AS seat_5,
              t3.display_name, t3.user_url, t3.user_login
              FROM $lTable AS t1 
              LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id
              LEFT JOIN $uTable AS t3 ON t2.user_id = t3.ID
              GROUP BY t1.id
              $uWhere
          ) AS t5
          $sWhere
          $sOrder
          $sLimit
          
          
    ";
    //Add Badge and Group names from the action item?,
    //can't do because they might have multiple saved in a serialized array so it can't be joined.

    ////columns that will be returned
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
    SELECT COUNT(`uid`)
    FROM   $lTable
    
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());


    foreach($rResult as $action){//output a row for each action

        //The message content
        $row = go_start_row($action);
        $user_id = $action['uid'];
        $xp = $action['xp'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];


        $group_ids_array = unserialize($group_ids);
        if (is_array($group_ids_array)){
            $group_list = array();
            $group_count = count($group_ids_array);
            foreach ($group_ids_array as $group_id){
                if (!empty($group_id)) {
                    $term = get_term($group_id);
                    if (!empty($term)) {
                        $name = $term->name;
                        $group_list[] = $name;
                    }
                }
            }
            $group_list = implode(",<br>", $group_list);
            //$group_count = '<span class="tooltip" data-tippy-content="'. $group_list .'">'. $group_count . '</span>';
            $group_count = '<span>'. $group_list . '</span>';


            //$group_count = "<span class='tooltip' target='_blank'><span class='tooltiptext'>$group_list</span>{$group_count}</span>";
        }
        else{
            $group_count = null;

        }

        $badge_ids_array = unserialize($badge_ids);
        $badge_count = $action['badge_count'];
        $badge_list = array();
        if (is_array($badge_ids_array)){
            foreach ($badge_ids_array as $badge_id){
                if (!empty($badge_id)) {
                    $term = get_term($badge_id);
                    if (!empty($term)) {
                        $name = $term->name;
                        $badge_list[] = $name;
                    }
                }
            }
            $badge_list = implode(",<br>", $badge_list);
            //$badge_count = '<span class="tooltip" data-tippy-content="'. $badge_list .'">'. $badge_count . '</span>';
            $badge_count = '<span>'. $badge_list . '</span>';
            //$badge_count = "<span class='tooltip' target='_blank'><span class='tooltiptext'>$badge_list</span>{$badge_count}</span>";
        }
        else{
            $badge_count = null;
        }

        $rank = go_get_rank ( $user_id, $xp );
        $current_rank_name = $rank['current_rank'];
        if (!empty($current_rank_name )){
            $current_rank_name = ": " . $current_rank_name;
        }
        $rank_num = $rank['rank_num'];

        //add to output
        $row[] = "{$rank_num}{$current_rank_name}";
        $go_loot_columns = go_loot_columns_clipboard($action);
        $row = array_merge($row, $go_loot_columns);

        $row[] = "{$badge_count}";
        $row[] = "{$group_count}";
        $output['aaData'][] = $row;
    }

    //$output['iTotalDisplayRecords'] =  count($output['aaData']);

    global $go_debug;
    if($go_debug) {
        go_total_query_time();
    }

    echo json_encode( $output );
    die();
}

/**
 *
 */
function go_clipboard_store() {
    check_ajax_referer( 'go_clipboard_store' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');

    $seats_name = get_option( 'options_go_seats_name' );

    echo "<div id='go_clipboard_store' class='go_datatables'><table id='go_clipboard_store_datatable' class='pretty display'>
    <thead>
    <tr>
        <th></th>
        <th><input type=\"checkbox\" onClick=\"go_toggle(this);\" /></th>
        <th class=\"header\">Section</th>
        <th class=\"header\">" . $seats_name . "</a></th>
        <th class=\"header\">First</th>
        <th class=\"header\">Last</th>
        <th class=\"header\">Display</th>
        <th class=\"header\">Links</th>
        <th class='header'>Time</th>
        <th class='header'>Item</th>";


    if ($xp_toggle){
        ?>
        <th class='header'><?php echo "$xp_abbr"; ?></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><?php echo "$gold_abbr"; ?></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><?php echo "$health_abbr"; ?></th>
        <?php
    }



    echo "<th class='header'>Other</th>
    </tr>
    </thead>

    </table></div>";

    die();
}

/**
 * go_clipboard_store_dataloader_ajax
 * Called for Server Side Processing from the JS
 *
 */
function go_clipboard_store_dataloader_ajax(){
    global $wpdb;

    //Get the search value
    $section = go_section();
    $uWhere = go_uWhere_values();

    $sColumns = array('first_name', 'last_name', 'display_name', 'result', 'xp', 'gold', 'health', 'post_title');
    $sWhere = go_sWhere($sColumns);

    $sLimit = '';
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    $sOrder = go_sOrder('store', $section);
    $sType = go_sType();

    $pTable = "{$wpdb->prefix}posts";
    $lTable = "{$wpdb->prefix}go_loot";
    $aTable = "{$wpdb->prefix}go_actions";
    $uTable = "{$wpdb->prefix}users";
    $umTable = "{$wpdb->prefix}usermeta";

    $sOn = go_sOn('store');
    //add store items to On statement
    $store_items = $_GET['store_item'];
    if ( isset($store_items) && !empty($store_items) )
    {
        $sOn .= " AND (";
        for ( $i=0 ; $i<count($store_items) ; $i++ )
        {
            $store_item = intval($store_items[$i]);
            $sOn .= "t4.source_id = ".$store_item." OR ";
        }
        $sOn = substr_replace( $sOn, "", -3 );
        $sOn .= ")";
    }
    //Index column
    $sIndexColumn = "id";

    //$sWhere = "WHERE (action_type = 'store') ";
    $sQuery = "
      
      SELECT SQL_CALC_FOUND_ROWS
        t9.*,
        CASE 
          WHEN t9.section_0 = $section THEN t9.seat_0
          WHEN t9.section_1 = $section THEN t9.seat_1 
          WHEN t9.section_2 = $section THEN t9.seat_2 
          WHEN t9.section_3 = $section THEN t9.seat_3 
          WHEN t9.section_4 = $section THEN t9.seat_4 
          WHEN t9.section_5 = $section THEN t9.seat_5 
          ELSE 0
          END AS the_seat,
      CASE 
          WHEN t9.section_0 = $section THEN t9.section_0
          WHEN t9.section_1 = $section THEN t9.section_1 
          WHEN t9.section_2 = $section THEN t9.section_2 
          WHEN t9.section_3 = $section THEN t9.section_3 
          WHEN t9.section_4 = $section THEN t9.section_4 
          WHEN t9.section_5 = $section THEN t9.section_5 
          ELSE 0
          END AS the_section
          
      FROM (
          SELECT
            t6.post_title, t5.*, t4.id, t4.source_id, t4.action_type, t4.TIMESTAMP, t4.result, t4.xp, t4.gold, t4.health, t4.badges, t4.groups
          FROM (
              SELECT
              t1.uid, t1.badges AS user_badges, t1.groups AS user_groups,
              MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
              MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END)  AS section_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-seat' THEN meta_value END)  AS seat_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-seat' THEN meta_value END) AS seat_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-seat' THEN meta_value END) AS seat_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-seat' THEN meta_value END) AS seat_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-seat' THEN meta_value END) AS seat_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-seat' THEN meta_value END) AS seat_5,      
              t3.display_name, t3.user_url, t3.user_login
              FROM $lTable AS t1 
              LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id 
              LEFT JOIN $uTable AS t3 ON t2.user_id = t3.ID
              GROUP BY t1.id
              $uWhere
          ) AS t5
          $sType JOIN $aTable AS t4 ON t5.uid = t4.uid $sOn
          $sType JOIN $pTable AS t6 ON t4.source_id = t6.ID
          $sWhere
          ) AS t9
          $sOrder
          $sLimit
    ";
    //Add Badge and Group names from the action item?,
    //can't do because they might have multiple saved in a serialized array so it can't be joined.

    ////columns that will be returned
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $totalWhere = " WHERE (action_type = 'store') ";
    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $aTable
    $totalWhere
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());


    foreach($rResult as $action){//output a row for each action
        $row = go_start_row($action);
        //The message content
        $TIMESTAMP = $action['TIMESTAMP'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];
        $title = $action['post_title'];

        $badges_and_groups = go_badges_and_groups($badge_ids, $group_ids);

        //$unix_time = strtotime($TIMESTAMP);
        $time = go_clipboard_time($TIMESTAMP);

        $row[] = "{$time}";
        $row[] = "{$title}";

        $go_loot_columns = go_loot_columns_clipboard($action);
        $row = array_merge($row, $go_loot_columns);

        $row[] = "{$badges_and_groups}";
        $output['aaData'][] = $row;
    }

    //$output['iTotalDisplayRecords'] =  count($output['aaData']);
    global $go_debug;
    if($go_debug) {
        go_total_query_time();
    }

    echo json_encode( $output );
    die();
}


/**
 *
 */
function go_clipboard_messages() {
    check_ajax_referer( 'go_clipboard_messages' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');

    $seats_name = get_option( 'options_go_seats_name' );

    echo "<div id='go_clipboard_messages' class='go_datatables'><table id='go_clipboard_messages_datatable' class='pretty display'>
    <thead>
    <tr>
        <th></th>
        <th><input type=\"checkbox\" onClick=\"go_toggle(this);\" /></th>
        <th class=\"header\">Section</th>
        <th class=\"header\">" . $seats_name . "</a></th>
        <th class=\"header\">First</th>
        <th class=\"header\">Last</th>
        <th class=\"header\">Display</th>
        <th class=\"header\">Links</th>
        <th class='header'>Time</th>
        <th class='header'>Message</th>";


    if ($xp_toggle){
        ?>
        <th class='header'><?php echo "$xp_abbr"; ?></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><?php echo "$gold_abbr"; ?></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><?php echo "$health_abbr"; ?></th>
        <?php
    }

    echo "<th class='header'>Other</th>
    </tr>
    </thead>

    </table></div>";

    die();
}

/**
 * go_clipboard_messages_dataloader_ajax
 * Called for Server Side Processing from the JS
 */
function go_clipboard_messages_dataloader_ajax(){
    global $wpdb;

    //Get the search value
    //$search_val = $_GET['search']['value'];
    $section = go_section();
    $uWhere = go_uWhere_values();

    $sColumns = array('first_name', 'last_name', 'display_name', 'result', 'xp', 'gold', 'health');
    $sWhere = go_sWhere($sColumns);

    $sLimit = '';
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    $sOrder = go_sOrder('store', $section);

    $sType = go_sType();
    $sOn = go_sOn('message');
    //Index column
    $sIndexColumn = "id";

    $lTable = "{$wpdb->prefix}go_loot";
    $aTable = "{$wpdb->prefix}go_actions";
    $uTable = "{$wpdb->prefix}users";
    $umTable = "{$wpdb->prefix}usermeta";

    $sQuery = "
      
      SELECT SQL_CALC_FOUND_ROWS
        t9.*,
        CASE 
          WHEN t9.section_0 = $section THEN t9.seat_0
          WHEN t9.section_1 = $section THEN t9.seat_1 
          WHEN t9.section_2 = $section THEN t9.seat_2 
          WHEN t9.section_3 = $section THEN t9.seat_3 
          WHEN t9.section_4 = $section THEN t9.seat_4 
          WHEN t9.section_5 = $section THEN t9.seat_5 
          ELSE 0
          END AS the_seat,
      CASE 
          WHEN t9.section_0 = $section THEN t9.section_0
          WHEN t9.section_1 = $section THEN t9.section_1 
          WHEN t9.section_2 = $section THEN t9.section_2 
          WHEN t9.section_3 = $section THEN t9.section_3 
          WHEN t9.section_4 = $section THEN t9.section_4 
          WHEN t9.section_5 = $section THEN t9.section_5 
          ELSE 0
          END AS the_section
          
      FROM (
          SELECT
            t5.*, t4.id, t4.source_id, t4.action_type, t4.TIMESTAMP, t4.result, t4.xp, t4.gold, t4.health, t4.badges, t4.groups
          FROM (
              SELECT
              t1.uid, t1.badges AS user_badges, t1.groups AS user_groups,
              MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
              MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END)  AS section_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-seat' THEN meta_value END)  AS seat_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-seat' THEN meta_value END) AS seat_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-seat' THEN meta_value END) AS seat_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-seat' THEN meta_value END) AS seat_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-seat' THEN meta_value END) AS seat_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-seat' THEN meta_value END) AS seat_5,      
              t3.display_name, t3.user_url, t3.user_login
              FROM $lTable AS t1 
              LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id
              LEFT JOIN $uTable AS t3 ON t2.user_id = t3.ID
              GROUP BY t1.id
              $uWhere
          ) AS t5
          $sType JOIN $aTable AS t4 ON t5.uid = t4.uid $sOn
          $sWhere
          ) AS t9
          $sOrder
          $sLimit
    ";
    //Add Badge and Group names from the action item?,
    //can't do because they might have multiple saved in a serialized array so it can't be joined.

    ////columns that will be returned
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);


    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $totalWhere = " WHERE (action_type = 'message') ";
    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $aTable
    $totalWhere
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());


    foreach($rResult as $action){//output a row for each message

        //The message content
        $row = go_start_row($action);
        $TIMESTAMP = $action['TIMESTAMP'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];
        $result = $action['result'];


        $badges_toggle = get_option('options_go_badges_toggle');
        if ($badges_toggle) {
            $badges_names = array();
            $badge_ids = unserialize($badge_ids);
            $badges_name_sing = get_option('options_go_badges_name_singular');

            if (!empty($badge_ids)) {
                if(!is_array($badge_ids)){
                    $badge_ids_array = array();
                    $badge_ids_array[]= $badge_ids;
                    $badge_ids = $badge_ids_array;
                }
                $badges_names_heading = "<b>" . $badges_name_sing . ": </b>";
                foreach ($badge_ids as $badge_id) {
                    $term = get_term($badge_id, "go_badges");
                    $badge_name = $term->name;
                    $badges_names[] = $badge_name;
                }
                $badges_names = $badges_names_heading . implode(", " , $badges_names);
            }else{
                $badges_names = "";
            }
        }else{
            $badges_names = "";
        }

        $group_names = array();
        $group_ids = unserialize($group_ids);
        if (!empty($group_ids)){
            $group_names_heading = "<b>Group: </b>";
            foreach ($group_ids as $group_id) {
                $term = get_term($group_id, "user_go_groups");
                $group_name = $term->name;
                $group_names[] = $group_name;
            }
            $group_names = $group_names_heading . implode(", " , $group_names);
        }else{
            $group_names = "";
        }

        //unserialize the message and set the results
        $result_array = unserialize($result);
        $title = $result_array[0];
        $message = $result_array[1];

        if (!empty($message)) {
            //$title = "<span class='tooltip' ><span class='tooltiptext'>{$message}</span>$title</span>";
            $title = '<span class="tooltip" data-tippy-content="'. $message .'">'. $title . '</span>';
        }
        $bg_links = '';
        if (!empty($badges_names)) {
            $badge_dir = $result_array[2];
            if ($badge_dir == "badges+"){
                $badges_names = "<b>Add </b> ". $badges_names;
                $bg_links .= '+';
            }else if ($badge_dir == "badges-"){
                $badges_names = "<b>Remove </b> ". $badges_names;
                $bg_links .= '-';
            }
            $bg_links .= '<i class="fa fa-certificate" aria-hidden="true"></i>';
        }

        if (!empty($group_names)){
            $groups_dir = $result_array[3];
            if ($groups_dir == "groups+"){
                $group_names = "<b>Add </b> " . $group_names;
                $bg_links .= ' +';
            }else if ($groups_dir == "groups-") {
                $group_names = "<b>Remove </b> ". $group_names;
                $bg_links .= ' -';
            }
            $bg_links .= '<i class="fa fa-users" aria-hidden="true"></i>';
        }
        if (!empty($badges_names) && !empty($group_names) ) {
            $badges_names = $badges_names . "<br>" ;
        }
        $badges_names =  $badges_names . $group_names;
        $badges_and_groups = '<span class="tooltip" data-tippy-content="'. $badges_names .'">'. $bg_links . '</span>';
        //$badges_and_groups = '<span class=\'tooltip\' target=\'_blank\'><span class=\'tooltiptext\' style=\'z-index: 99999;\'>'. $badges_names .'</span>' . $bg_links . '</span>';

        $time  = go_clipboard_time($TIMESTAMP);

        $row[] = "{$time}";
        $row[] = "{$title}";

        $go_loot_columns = go_loot_columns_clipboard($action);
        $row = array_merge($row, $go_loot_columns);

        $row[] = "{$badges_and_groups}";
        $output['aaData'][] = $row;
    }

    global $go_debug;
    if($go_debug) {
        go_total_query_time();
    }

    echo json_encode( $output );
    die();
}

/**
 *
 */
function go_clipboard_activity() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }

    check_ajax_referer( 'go_clipboard_activity_' . get_current_user_id() );
    global $wpdb;

    /*
    $date_ajax = $_POST['date'];
    if (!empty($date_ajax)) {
        $date_ajax = date("Y-m-d", strtotime($date_ajax));
    }else{
        $date_ajax = null;
    }
    */

    $go_totals_table_name = "{$wpdb->prefix}go_loot";
    $rows = $wpdb->get_results(
        "SELECT *
			        FROM {$go_totals_table_name}"
    );

    $seats_name = get_option( 'options_go_seats_name' );
    ?>

    <?php
    echo '<div id="go_clipboard_activity_wrapper" class="go_clipboard">';
    ?>
    <table id='go_clipboard_activity_datatable' class='pretty display'>
        <thead>
        <tr>
            <th>
            </th><th><input type="checkbox" onClick="go_toggle(this);" /></th>
            <th class="header">Section</th>
            <th class="header"><?php echo "$seats_name"; ?></a></th>
            <th class="header">First</th>
            <th class="header">Last</th>
            <th class="header">Display</th>
            <th class="header">Links</th>
            <th class='header'>Task</th>
            <th class='header'>Actions</th>
            <th class='header'>Start</th>
            <th class='header'>Last</th>
            <th class='header'>Time On</th>
            <th class='header'>Status</th>
            <th class='header'>Done</th>
            <th class='header'>Bonus</th>
            <th class='header'>XP</th>
            <th class='header'>G</th>
            <th class='header'>H</th>
            <th class='header'>Other</th>


        </tr>
        </thead>
    </table></div>
    <?php
    die();
}

/**
 *
 */
function go_clipboard_activity_dataloader_ajax(){
    global $wpdb;
    //go_write_log("GET: ");
    //go_write_log($_GET);
    //$wpdb->show_errors();
    //Get the search value
    //$search_val = $_GET['search']['value'];
    $section = go_section();
    $uWhere = go_uWhere_values();

    $sColumns = array('first_name', 'last_name', 'display_name', 'xp', 'gold', 'health', 'post_title');
    $sWhere = go_sWhere( $sColumns);

    $sLimit = '';
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    $sOrder = go_sOrder('tasks', $section);

    $sType = go_sType();
    $sOn = go_sOn('tasks');
    //add store items to On statement
    $tasks = $_GET['tasks'];
    if ( isset($tasks) && !empty($tasks) )
    {
        $sOn .= " AND (";
        for ( $i=0 ; $i<count($tasks) ; $i++ )
        {
            $task = intval($tasks[$i]);
            $sOn .= "t4.post_id = ".$task." OR ";
        }
        $sOn = substr_replace( $sOn, "", -3 );
        $sOn .= ")";
    }

    //Index column
    $sIndexColumn = "id";


    $lTable = "{$wpdb->prefix}go_loot";
    $aTable = "{$wpdb->prefix}go_actions";
    $uTable = "{$wpdb->prefix}users";
    $umTable = "{$wpdb->prefix}usermeta";
    $tTable = "{$wpdb->prefix}go_tasks";
    $pTable = "{$wpdb->prefix}posts";

    $sQuery = "
      SELECT SQL_CALC_FOUND_ROWS
        t9.*,
        CASE 
          WHEN t9.section_0 = $section THEN t9.seat_0
          WHEN t9.section_1 = $section THEN t9.seat_1 
          WHEN t9.section_2 = $section THEN t9.seat_2 
          WHEN t9.section_3 = $section THEN t9.seat_3 
          WHEN t9.section_4 = $section THEN t9.seat_4 
          WHEN t9.section_5 = $section THEN t9.seat_5 
          ELSE 0
          END AS the_seat,
      CASE 
          WHEN t9.section_0 = $section THEN t9.section_0
          WHEN t9.section_1 = $section THEN t9.section_1 
          WHEN t9.section_2 = $section THEN t9.section_2 
          WHEN t9.section_3 = $section THEN t9.section_3 
          WHEN t9.section_4 = $section THEN t9.section_4 
          WHEN t9.section_5 = $section THEN t9.section_5 
          ELSE 0
          END AS the_section
          
      FROM (
          SELECT
            t5.*, t6.post_title, t4.id, t4.post_id, t4.status, t4.bonus_status, t4.xp, t4.gold, t4.health, t4.start_time, t4.last_time, t4.badges, t4.groups,
            TIMESTAMPDIFF(SECOND, t4.start_time, t4.last_time ) AS timediff
          FROM (
              SELECT
              t1.uid, t1.badges AS user_badges, t1.groups AS user_groups,
              MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
              MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END)  AS section_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-seat' THEN meta_value END)  AS seat_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-seat' THEN meta_value END) AS seat_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-seat' THEN meta_value END) AS seat_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-seat' THEN meta_value END) AS seat_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-seat' THEN meta_value END) AS seat_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-seat' THEN meta_value END) AS seat_5,      
              t3.display_name, t3.user_url, t3.user_login
              FROM $lTable AS t1 
              LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id
              LEFT JOIN $uTable AS t3 ON t2.user_id = t3.ID
              GROUP BY t1.id
              $uWhere
          ) AS t5
          $sType JOIN $tTable AS t4 ON t5.uid = t4.uid $sOn
          $sType JOIN $pTable AS t6 ON t4.post_id = t6.ID
          $sWhere
          ) AS t9
          $sOrder
          $sLimit
    ";
    //Add Badge and Group names from the action item?,
    //can't do because they might have multiple saved in a serialized array so it can't be joined.
    //go_write_log($sQuery);
    ////columns that will be returned
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    //go_write_log("ERROR: ");
    //go_write_log($wpdb->print_error());
    // go_write_log($rResult);
    $sQuery2 = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery2, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    //$totalWhere = " WHERE (action_type = 'message') ";
    $sQuery3 = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $tTable
    ";

    $rResultTotal = $wpdb->get_results($sQuery3, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());


    foreach($rResult as $action){//output a row for each message

        //The message content
        $row = go_start_row($action);
        $row[] = $action['post_title'];

        $task_id = (isset($action['post_id']) ?  $action['post_id'] : null);
        //if ($task_id != null) {
        //  $task_id = "data-task='" . $task_id . "'";
        //}

        $user_id = $action['uid'];
        $row[] = '<a href="javascript:;" class="go_blog_user_task" data-UserId="'.$user_id.'" onclick="go_blog_user_task('.$user_id.', '.$task_id.');"><i style="padding: 0px 10px;" class="fa fa-eye" aria-hidden="true"></i></a><a><i data-uid="' . $user_id . '" data-task="'. $task_id . '" style="padding: 0px 10px;" class="go_reset_task_clipboard fa fa-times-circle" aria-hidden="true"></a>';//actions

        $start = $action['start_time'];
        $row[] = go_clipboard_time($start);
        $last = $action['last_time'];
        $row[] = go_clipboard_time($last);
        $diff = $action['timediff'];
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        if (!empty($diff)) {
            //$diff = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $diff);
            //sscanf($diff, "%d:%d:%d", $hours, $minutes, $seconds);
            //$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;
            //$diff = go_time_on_task($time_seconds, false);
            $diff = go_time_on_task($diff, false);
        }
        $row[] = $diff;


        $go_post_data = go_post_data($action['post_id']);
        //$the_title = $go_post_data[0];
        //$status = $go_post_data[1];
        //$task_link = $go_post_data[2];
        $custom_fields = $go_post_data[3];

        if ($action['status'] >= 0) {
            $stage_count = (isset($custom_fields['go_stages'][0]) ? $custom_fields['go_stages'][0] : null);
            $bonus_count = (isset($custom_fields['go_bonus_limit'][0]) ? $custom_fields['go_bonus_limit'][0] : null);
            $row[] = strval($action['status']) . " / " . strval($stage_count);

            if (($action['status'] >= $stage_count) && !empty($action['status'])){
                $complete = "<i class=\"fa fa-check\" aria-hidden=\"true\"></i>";
            }else{
                $complete = "";
            }
            $row[] = $complete;

        }
        else if($action['status'] == -2){
            $row[] = "reset";
            $row[] = "";
        }
        else if ($action['status'] == -1){
            $row[] = "abandoned";
            $row[] = "";
        }

        if ($action['bonus_status'] > 0) {
            $row[] = strval($action['bonus_status']) . " / " . strval($bonus_count);
        }else{
            $row[] = "";
        }

        $go_loot_columns = go_loot_columns_clipboard($action);
        $row = array_merge($row, $go_loot_columns);

        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];
        $badges_and_groups = go_badges_and_groups($badge_ids, $group_ids);

        $row[] = "{$badges_and_groups}";

        $output['aaData'][] = $row;
    }
    //go_write_log("output: ");
    //go_write_log($output);
    //go_total_query_time();

    echo json_encode( $output );
    die();
}

//has ajax call
/**
 *
 */
function go_make_cpt_select2_ajax() {
    // we will pass post IDs and titles to this array
    $return = array();

    // you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
    $search_results = new WP_Query( array(
        's'=> $_GET['q'], // the search query
        'post_type'=> $_GET['cpt'], // the search query
        //'post_status' => 'publish', // if you don't want drafts to be returned
        //'ignore_sticky_posts' => 1,
        //'posts_per_page' => 50 // how much to show at once\

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
