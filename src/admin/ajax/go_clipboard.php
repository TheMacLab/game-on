<?php

/**
 * Get/set transient of user_id totals
 *
 * Reset on:
 * new user
 * user update
 *
 * @param $term_id
 * @return mixed
 */
function go_get_all_user_names() {//should I put website, section, and seats here as well?--no the user objects are cached
    //and the user objects are used for the links.
    global $wpdb;

    $totals_table_name = "{$wpdb->prefix}go_loot";
   /* $key = 'go_user_names';
    delete_transient($key);
    $data = get_transient($key);

    if ($data === false) {
   */
        $user_ids = $wpdb->get_results("SELECT uid
			        FROM {$totals_table_name}");
        $data = array();
        foreach ($user_ids as $user_id){
            $user_id = $user_id->uid;
            $user_data =  array();
            $user_data_key = get_userdata($user_id);
            $user_display_name = $user_data_key->display_name;
            $user_firstname = $user_data_key->user_firstname;
            $user_lastname = $user_data_key->user_lastname;


            $user_data[] = $user_id;
            $user_data[] = $user_display_name;
            $user_data[] = $user_firstname;
            $user_data[] = $user_lastname;

            $data[] = $user_data;


        }

     //   set_transient($key, $data, 3600 * 24);
    //}

    return $data;

}

function go_get_user_ids_search($search_val){
    //USER IDS
    //if there is a search value, get the user ids that match by name
    $user_ids = array();
    $users_data = go_get_all_user_names();
    foreach ($users_data as $user_data){
        //$is_match = strpos($user_data, $search_val);
        $needle = $search_val;
        $matches = array_filter($user_data, function ($user_data) use ($needle) {
            $match = strpos($user_data, $needle);
            if($match !== false){
                $match = true;
            }
            return($match);
        });

        if (!empty($matches)){
            $user_id = $user_data[0];
            $user_ids[] = $user_id;
        }
    }
    return $user_ids;
}

function go_get_term_ids_search($search_val, $taxonomy){

    $term_ids = array();
    $terms = go_get_all_terms($taxonomy);
    foreach ($terms as $term){
        $name = $term->name;
        $match = strpos($name, $search_val);
        if($match !== false){
            $term_ids[] =  $term->term_id;
        }
    }
    return $term_ids;
}

function go_clipboard_save_filters (){
    $user_id = get_current_user_id();
    check_ajax_referer( 'go_clipboard_save_filters_' . $user_id );
    $section = $_POST['section'];
    $badge = $_POST['badge'];
    $group = $_POST['group'];
    update_user_meta( $user_id, 'go_clipboard_section', $section );
    update_user_meta( $user_id, 'go_clipboard_badge', $badge );
    update_user_meta( $user_id, 'go_clipboard_group', $group );
}

function go_clipboard_menu() {
    acf_form_head();


	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} else {
        $user_id = get_current_user_id();
	    $section = get_user_meta( $user_id, 'go_clipboard_section' );
        $badge = get_user_meta( $user_id, 'go_clipboard_badge');
        $group = get_user_meta( $user_id, 'go_clipboard_group');

	?>

        <div>
            <div id="go_leaderboard_filters" style="width:100%; margin-right: 20px; float: left;">

                <span> Section: <?php go_make_tax_select('user_go_sections', "Show All" , "clipboard_", $section, false); ?></span>
                <span> Group: <?php go_make_tax_select('user_go_groups', "Show All"  , "clipboard_", $group, false); ?></span>
                <span> Badges: <?php go_make_tax_select('go_badges', "Show All"  , "clipboard_", $badge, false); ?></span>

                <span id="go_timestamp_filters" style="display:none;" > Date: <input id="datepicker-store" type="search" class="datepicker" name="datepicker" value=""/><input id="datepicker-messages" type="search" class="datepicker" name="datepicker" value=""/><input id="datepicker-activity" type="text" class="datepicker" name="datepicker" value=""/></span>
                <span style="float:right; margin-right: 60px;"><button class="go_update_clipboard dt-button ui-button ui-state-default ui-button-text-only buttons-collection"><span class="ui-button-text">Update <span class="dashicons dashicons-update" style="vertical-align: center;"></span></span></button></span>


            </div>



        </div>
		<div id="records_tabs" style="clear: both; margin-left: -9999px; margin-right: 20px;">
			<ul>
				<li class="clipboard_tabs" tab="clipboard"><a href="#clipboard_wrap">Stats</a></li>
                <li class="clipboard_tabs" tab="store"><a href="#clipboard_store_wrap">Store</a></li>

				<li class="clipboard_tabs" tab="messages"><a href="#clipboard_messages_wrap">Messages</a></li>

				<li class="clipboard_tabs" tab="activity"><a href="#clipboard_activity_wrap">Activity</a></li>
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

function go_clipboard_stats() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }

    global $wpdb;
    check_ajax_referer( 'go_clipboard_stats_' . get_current_user_id() );
    $refresh = $_POST['refresh'];
    $key = 'go_clipboard_stats';
    delete_transient($key);
    if ($refresh == "true"){
        delete_transient($key);
    }

    $data = get_transient($key);
    if ($data === false) {
        //$current_user_id =  $user_id = get_current_user_id();
        $go_totals_table_name = "{$wpdb->prefix}go_loot";
        $go_users_table_name = "{$wpdb->prefix}users";
        $rows = $wpdb->get_results(
            "SELECT * 
                        FROM {$go_totals_table_name}"
        );

        $xp_toggle = get_option('options_go_loot_xp_toggle');
        $gold_toggle = get_option('options_go_loot_gold_toggle');
        $health_toggle = get_option('options_go_loot_health_toggle');
        $badges_toggle = get_option('options_go_badges_toggle');

        // prepares tab titles
        //$xp_name = get_option( "options_go_loot_xp_name" );
        //$gold_name = get_option( "options_go_loot_gold_name" );
        $badges_name = get_option( 'options_go_badges_name_plural' );

        $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
        $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
        $health_abbr = get_option( "options_go_loot_health_abbreviation" );

        $seats_name = get_option( 'options_go_seats_name' );




        echo '<div id="go_clipboard_wrapper" class="go_clipboard">';
        ?>

            <table id='go_clipboard_stats_datatable' class='pretty display'>
                <thead>
                <tr>
                    <th></th>
                    <th><input type="checkbox" onClick="go_toggle(this);" /></th>
                    <th class="header">sections</th>
                    <th class="header">groups</th>
                    <th class="header">badges</th>
                    <th class="header">Section</th>
                    <th class="header"><?php echo "$seats_name"; ?></a></th>
                    <th class="header">First</th>
                    <th class="header">Last</th>
                    <th class="header">Display</th>
                    <th class="header">Links</th>

                    <?php
                    if ($xp_toggle){
                        ?>
                        <th class="header"><?php echo get_option( 'options_go_loot_xp_levels_name_singular' ); ?></th>
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
                    if ($badges_toggle){
                        ?>
                        <th class='header'><?php echo "$badges_name"; ?></th>
                        <?php
                    }

                        ?>
                    <th class='header'>Groups</th>


                </tr>
                </thead>
                <tbody>
        <?php


        foreach ( $rows as $row ) {
            $user_id = $row->uid;
            //$is_admin = go_user_is_admin($user_id);
            //if ($is_admin) {
            //    continue;
            //}
            $user_data_key = get_userdata($user_id);
            $user_display_name = $user_data_key->display_name;
            $user_firstname = $user_data_key->user_firstname;
            $user_lastname = $user_data_key->user_lastname;

            //these are used in the filter
            $group_ids = $row->groups;
            $group_ids_array = unserialize($group_ids);
            if (!is_array($group_ids_array)) {
                $group_ids_array = array();
            }
            $group_ids = json_encode(array_values($group_ids_array));

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
                $group_count = "<span class='tooltip' target='_blank'><span class='tooltiptext'>$group_list</span>{$group_count}</span>";
            }
            else{
                $group_count = null;

            }

            //these are used in the filter
            $badge_ids = $row->badges;
            $badge_ids_array = unserialize($badge_ids);
            if (!is_array($badge_ids_array)) {
                $badge_ids_array = array();
            }
            $badge_ids = json_encode(array_values($badge_ids_array));

            $badge_count = $row->badge_count;
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
                $badge_count = "<span class='tooltip' target='_blank'><span class='tooltiptext'>$badge_list</span>{$badge_count}</span>";
            }
            else{
                $badge_count = null;
            }


            $xp = $row->xp;
            $gold = $row->gold;
            $health = $row->health;

            $rank = go_get_rank ( $user_id, $xp );
            $current_rank_name = $rank['current_rank'];
            if (!empty($current_rank_name )){
                $current_rank_name = ": " . $current_rank_name;
            }
            $rank_num = $rank['rank_num'];

            //Sections and Seats
            $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
            if (empty($num_sections)){
                $num_sections =1;
            }

            for ($i = 0; $i < $num_sections; $i++) {
                $user_period_option = "go_section_and_seat_" . $i . "_user-section";
                $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";


                $user_period = get_user_meta($user_id, $user_period_option, true);
                $term = get_term( $user_period, "user_go_sections" );
                //$user_period_name = $term->name;
                $user_period_name = (isset($term->name) ?  $term->name : null);

                $user_seat = get_user_meta($user_id, $user_seat_option, true);

                echo "<tr>
                    <td></td>
                    <td style='text-align: center;'><input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/></td>
                    <td >{$user_period} </a></td>
                    <td>{$group_ids}</a></td>
                    <td>{$badge_ids}</a></td>
                    <td >{$user_period_name} </a></td>
                    <td>{$user_seat}</td>
                    <td>{$user_firstname}</td>	
                    <td>{$user_lastname}</td>
                    <td>{$user_display_name}</td>
                    <td>";
                go_user_links($user_id, true, true, true, true, true, false);
                echo " </a></td>
                    ";

                if ($xp_toggle) {
                    echo "<td data-order='{$rank_num}'>{$rank_num}{$current_rank_name}</td>
                  <td class='user_points'>{$xp}</td>";
                }
                if ($gold_toggle) {
                    echo "<td class='user_currency'>{$gold}</td>";
                }
                if ($health_toggle) {
                    echo "<td class='user_health'>{$health}</td>";
                }

                echo "		
                    <td class='user_badge_count'>{$badge_count}</td>
                    <td class='user_group_count'>{$group_count}</td>
                  </tr>";
            }
        }
        ?>
                </tbody></table></div>
        <?php

        $data = ob_get_contents();
        set_transient($key, $data, 600 );
    }
    else{
        echo $data;
    }
    die();
}

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
 */
function go_clipboard_store_dataloader_ajax(){
    global $wpdb;

    $search_val = $_GET['search']['value'];
    //FIRST GET THE SEARCH PARAMETERS FROM OTHER TABLES
    //STORE IDS
    //if there is a search value, get the store ids that match by title and description
    $store_item_ids = array();
    if (isset($search_val) && $search_val != "") {
        $sTable = "{$wpdb->prefix}posts";

        //columns that will be returned
        $aColumns = array('ID');

        //columns that will be searched
        $sColumns = array('post_content', "post_title");

        $search_val = $_GET['search']['value'];

        $sWhere = "WHERE (post_type = 'go_store') ";

        $sWhere .= " AND (";
        for ($i = 0; $i < count($sColumns); $i++) {
            $sWhere .= "`" . $sColumns[$i] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR

        $sWhere .= ')';

        $sQuery = "
            SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
            FROM   $sTable
            $sWhere
        ";

        $rResult = $wpdb->get_results($sQuery, ARRAY_A);
        foreach ($rResult as $store_item_id) {
            $store_item_ids[]= $store_item_id['ID'];
        }
    }
    if (empty($store_item_ids)){
        $store_item_ids[] = 'none';
    }

    //USER IDS
    //if there is a search value, get the user ids that match by name
    if (isset($search_val) && $search_val != "") {
        $user_ids = go_get_user_ids_search($search_val);
    }
    if (empty($user_ids)){
        $user_ids[] = 'none';
    }

    //Badge IDS
    //if there is a search value, get the badges that match by name
    if (isset($search_val) && $search_val != "") {
        $badge_ids = go_get_term_ids_search($search_val, 'go_badges');
    }
    if (empty($term_ids)){
        $badge_ids[] = 'none';
    }

    //Group IDS
    //if there is a search value, get the group that match by name
    if (isset($search_val) && $search_val != "") {
        $group_ids = go_get_term_ids_search($search_val, 'user_go_groups');
    }
    if (empty($term_ids)){
        $group_ids[] = 'none';
    }

    //END GET PARAMETERS

    //CREATE THE QUERY
    $section = $_GET['section'];
    $badge = $_GET['badge'];
    $group = $_GET['group'];
    $date = $_GET['date'];

    $go_action_table_name = "{$wpdb->prefix}go_actions";

    //columns that will be returned
    $aColumns = array('id', 'uid', 'source_id', 'action_type', 'TIMESTAMP', 'result', 'xp', 'gold', 'health', 'badges', 'groups');

    //columns that will be searched
    $sColumns = array('result', 'xp', 'gold', 'health', 'badges', 'groups');

    $sIndexColumn = "id";
    $sTable = $go_action_table_name;

    $sLimit = '';
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    $sOrder = "ORDER BY TIMESTAMP desc"; //always in reverse order

    $sWhere = "WHERE (action_type = 'store') ";

    if ( isset($search_val) && $search_val != "" )
    {
        $sWhere .= " AND ((";

        //search these columns
        for ( $i=0 ; $i<count($sColumns) ; $i++ )
        {
            $sWhere .= "`".$sColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';

        //search for source_ids
        //columns source_id column
        $sColumns = array('source_id');
        //Store IDs added to search
        $sWhere .= " OR (";
        for ($i = 0; $i < count($store_item_ids); $i++) {
            $search_val = $store_item_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';


        //search for User IDs
        $sColumns = array('uid');
        $sWhere .= " OR (";
        for ($i = 0; $i < count($user_ids); $i++) {
            $search_val = $user_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';

        //search for badge IDs
        $sColumns = array('badges');
        $sWhere .= " OR (";
        for ($i = 0; $i < count($badge_ids); $i++) {
            $search_val = $badge_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';

        //search for group IDs
        $sColumns = array('groups');
        $sWhere .= " OR (";
        for ($i = 0; $i < count($group_ids); $i++) {
            $search_val = $group_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';

        //end create sWhere statement
        $sWhere .= ")";


    }

    $totalWhere = " WHERE (action_type = 'store') ";

    if ( isset($date) && $date != "" )
    {
        $date = date("Y-m-d", strtotime($date));
        $sWhere .= " AND ( DATE(TIMESTAMP) = '" . $date . "')";
    }

    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
    FROM $sTable 
    $sWhere
    $sOrder
    $sLimit
    ";
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $sTable
    $totalWhere
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());


    foreach($rResult as $action){//output a row for each message

        //The message content
        $row = array();
        $user_id = $action['uid'];
        $source_id = $action['source_id'];
        $TIMESTAMP = $action['TIMESTAMP'];
        $xp = $action['xp'];
        $gold = $action['gold'];
        $health = $action['health'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];

        $title = get_the_title($source_id);


        ///Get the user info for this row from the transient--should this be stored temporarily, in the object cache
        $user_row = go_get_loot($user_id);


        //FILTERS
        //groups filter
        $user_group_ids = $user_row['groups'];
        $group_ids_array = unserialize($user_group_ids);

        //if the group filter is on
        if($group != 'none') {
            $is_array = is_array($group_ids_array); //does this user have groups set as an array
            //if not array then filter
            if (!$is_array){
                continue;
            }

            //if there is a match then continue, if not
            $in_array = in_array($group, $group_ids_array);
            if (!$in_array) {
                continue;
            }
        }

        //badge filter
        $user_badge_ids = $user_row['badges'];
        $badge_ids_array = unserialize($user_badge_ids);
        //if badge filter is on
        if ($badge != 'none') {
            $is_array = is_array($badge_ids_array); //does this user have groups set as an array
            //if not array then filter
            if (!$is_array){
                continue;
            }
            $in_array = in_array($badge, $badge_ids_array);
            if (!$in_array) {
                continue;
            }
        }

        $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
        if (empty($num_sections)){
            $num_sections =1;
        }
        $user_periods= array();
        $user_period_name= array();
        $user_seat = array();
        for ($i = 0; $i < $num_sections; $i++) {
            $user_period_option = "go_section_and_seat_" . $i . "_user-section";
            $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";


            $user_period = get_user_meta($user_id, $user_period_option, true);
            $user_periods[] = $user_period;
            $term = get_term($user_period, "user_go_sections");
            //$user_period_name = $term->name;
            $user_period_name[] = (isset($term->name) ? $term->name : null);

            $user_seat[] = get_user_meta($user_id, $user_seat_option, true);
        }
        if ($section != 'none') {
            $in_array = in_array($section, $user_periods);
            if (!$in_array) {
                continue;
            }
        }
        //if this is not filtered, set the values
        $user_period_name = implode("<br>", $user_period_name);
        $user_seat = implode("<br>", $user_seat);

        $user_data_key = get_userdata($user_id);
        $user_display_name = $user_data_key->display_name;
        $user_firstname = $user_data_key->user_firstname;
        $user_lastname = $user_data_key->user_lastname;

        $badges_toggle = get_option('options_go_badges_toggle');
        if ($badges_toggle) {
            $badges_names = array();
            $badge_ids = unserialize($badge_ids);
            $badges_name_sing = get_option('options_go_badges_name_singular');

            if (!empty($badge_ids)) {
                $badges_names_heading = "<b>Add " . $badges_name_sing . ":</b> ";
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
            if (!empty($badge_ids)) {
                $group_names_heading = "<br><b>Add Group: </b>";
            }else {
                $group_names_heading = "<b>Add Group: </b>";
            }
            foreach ($group_ids as $group_id) {
                $term = get_term($group_id, "user_go_groups");
                $group_name = $term->name;
                $group_names[] = $group_name;
            }
            $group_names = $group_names_heading . implode(", " , $group_names);
        }else{
            $group_names = "";
        }

        $badges_names = $badges_names . $group_names;


        //$unix_time = strtotime($TIMESTAMP);
        $time  = date("m/d/y g:i A", strtotime($TIMESTAMP));

        ob_start();
        go_user_links($user_id, true, true, true, true, true, false);
        $links = ob_get_clean();

        $check_box = "<input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/>";

        $row[] = "";
        $row[] = "{$check_box}";
        $row[] = "{$user_period_name}";//user period
        $row[] = "{$user_seat}";//user seat
        $row[] = "{$user_firstname}";
        $row[] = "{$user_lastname}";
        $row[] = "{$user_display_name}";
        $row[] = "{$links}";
        $row[] = "{$time}";
        $row[] = "{$title}";

        $xp_toggle = get_option('options_go_loot_xp_toggle');
        $gold_toggle = get_option('options_go_loot_gold_toggle');
        $health_toggle = get_option('options_go_loot_health_toggle');

        if ($xp_toggle){
            $row[] = "{$xp}";
        }
        if ($gold_toggle){
            $row[] = "{$gold}";
        }
        if ($health_toggle){
            $row[] = "{$health}";
        }
        $row[] = "{$badges_names}";
        $output['aaData'][] = $row;
    }

    $output['iTotalDisplayRecords'] =  count($output['aaData']);


    echo json_encode( $output );
    die();
}

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
        <th class='header'>Title</th>					
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
    //FIRST GET THE SEARCH PARAMETERS FROM OTHER TABLES
    $search_val = $_GET['search']['value'];

    //USER IDS
    //if there is a search value, get the user ids that match by name
    if (isset($search_val) && $search_val != "") {
        $user_ids = go_get_user_ids_search($search_val);
    }
    if (empty($user_ids)){
        $user_ids[] = 'none';
    }

    //Badge IDS
    //if there is a search value, get the badges that match by name
    if (isset($search_val) && $search_val != "") {
        $badge_ids = go_get_term_ids_search($search_val, 'go_badges');
    }
    if (empty($term_ids)){
        $badge_ids[] = 'none';
    }

    //Group IDS
    //if there is a search value, get the group that match by name
    if (isset($search_val) && $search_val != "") {
        $group_ids = go_get_term_ids_search($search_val, 'user_go_groups');
    }
    if (empty($term_ids)){
        $group_ids[] = 'none';
    }
    //END GET PARAMETERS

    //CREATE THE QUERY
    //FILTER VALUES
    $section = $_GET['section'];
    $badge = $_GET['badge'];
    $group = $_GET['group'];
    $date = $_GET['date'];

    $go_action_table_name = "{$wpdb->prefix}go_actions";

    //columns that will be returned
    $aColumns = array( 'id', 'uid', 'TIMESTAMP' , 'result', 'xp', 'gold', 'health', 'badges', 'groups' );

    //columns that will be searched
    $sColumns = array( 'result', 'global_mod', 'xp', 'gold', 'health' );

    $sIndexColumn = "id";
    $sTable = $go_action_table_name;

    $sLimit = '';
    if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' )
    {
        $sLimit = "LIMIT ".intval( $_GET['start'] ).", ".
            intval( $_GET['length'] );
    }

    $sOrder = "ORDER BY TIMESTAMP desc"; //always in reverse order

    $search_val = $_GET['search']['value'];

    $sWhere = "WHERE (action_type = 'message' OR action_type = 'reset') ";

    if ( isset($date) && $date != "" )
    {
        $date = date("Y-m-d", strtotime($date));
        $sWhere .= " AND ( DATE(TIMESTAMP) = '" . $date . "')";
    }

    if ( isset($search_val) && $search_val != "" )
    {
        $sWhere .= " AND ((";

        //search these columns
        for ( $i=0 ; $i<count($sColumns) ; $i++ )
        {
            $sWhere .= "`".$sColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';




        //search for User IDs
        $sColumns = array('uid');
        $sWhere .= " OR (";
        for ($i = 0; $i < count($user_ids); $i++) {
            $search_val = $user_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';

        //search for badge IDs
        $sColumns = array('badges');
        $sWhere .= " OR (";
        for ($i = 0; $i < count($badge_ids); $i++) {
            $search_val = $badge_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';

        //search for group IDs
        $sColumns = array('groups');
        $sWhere .= " OR (";
        for ($i = 0; $i < count($group_ids); $i++) {
            $search_val = $group_ids[$i];
            $sWhere .= "`" . $sColumns[0] . "` LIKE '%" . esc_sql($search_val) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);//removes the last OR
        $sWhere .= ')';

        //end create sWhere statement
        $sWhere .= ")";


    }

    $totalWhere = " WHERE (action_type = 'message' OR action_type = 'reset') ";

    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
    FROM   $sTable
    $sWhere
    $sOrder
    $sLimit
    ";

    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $sTable
    $sWhere
    ";

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $sTable
    $totalWhere
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];

    $output = array(
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    foreach($rResult as $action){//output a row for each message

        //The message content
        $row = array();
        $user_id = $action['uid'];
        $TIMESTAMP = $action['TIMESTAMP'];
        $result = $action['result'];
        $xp = $action['xp'];
        $gold = $action['gold'];
        $health = $action['health'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];

        ///Get the user info for this row from the transient
        $user_row = go_get_loot($user_id);

        //FILTERS
        //groups filter
        $user_group_ids = $user_row['groups'];
        $group_ids_array = unserialize($user_group_ids);

        //if the group filter is on
        if($group != 'none') {
            $is_array = is_array($group_ids_array); //does this user have groups set as an array
            //if not array then filter
            if (!$is_array){
                continue;
            }

            //if there is a match then continue, if not
            $in_array = in_array($group, $group_ids_array);
            if (!$in_array) {
                continue;
            }
        }

        //badge filter
        $user_badge_ids = $user_row['badges'];
        $badge_ids_array = unserialize($user_badge_ids);
        //if badge filter is on
        if ($badge != 'none') {
            $is_array = is_array($badge_ids_array); //does this user have groups set as an array
            //if not array then filter
            if (!$is_array){
                continue;
            }
            $in_array = in_array($badge, $badge_ids_array);
            if (!$in_array) {
                continue;
            }
        }

        $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
        if (empty($num_sections)){
            $num_sections =1;
        }
        $user_periods= array();
        $user_period_name= array();
        $user_seat = array();
        for ($i = 0; $i < $num_sections; $i++) {
            $user_period_option = "go_section_and_seat_" . $i . "_user-section";
            $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";

            $user_period = get_user_meta($user_id, $user_period_option, true);
            $user_periods[] = $user_period;
            $term = get_term($user_period, "user_go_sections");
            //$user_period_name = $term->name;
            $user_period_name[] = (isset($term->name) ? $term->name : null);

            $user_seat[] = get_user_meta($user_id, $user_seat_option, true);
        }
        if ($section != 'none') {
            $in_array = in_array($section, $user_periods);
            if (!$in_array) {
                continue;
            }
        }
        //if this is not filtered, set the values
        $user_period_name = implode("<br>", $user_period_name);
        $user_seat = implode("<br>", $user_seat);


        $user_data_key = get_userdata($user_id);
        $user_display_name = $user_data_key->display_name;
        $user_firstname = $user_data_key->user_firstname;
        $user_lastname = $user_data_key->user_lastname;

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

        $result_array = unserialize($result);

        if (!empty($badges_names)) {
            $badge_dir = $result_array[2];
            if ($badge_dir == "badges+"){
                $badges_names = "<b>Add </b> ". $badges_names;
            }else if ($badge_dir == "badges-"){
                $badges_names = "<b>Remove </b> ". $badges_names;
            }
        }

        if (!empty($group_names)){
            $groups_dir = $result_array[3];
            if ($groups_dir == "groups+"){
                $group_names = "<b>Add </b> " . $group_names;
            }else if ($groups_dir == "groups-") {
                $group_names = "<b>Remove </b> ". $group_names;
            }
        }
        if (!empty($badges_names) && !empty($badges_names) ) {
            $badges_names = $badges_names . "<br>";
        }
        $badges_names =  $badges_names . $group_names;

        $title = $result_array[0];
        $message = $result_array[1];

        //$unix_time = strtotime($TIMESTAMP);
        $time  = date("m/d/y g:i A", strtotime($TIMESTAMP));

        ob_start();
        go_user_links($user_id, true, true, true, true, true, false);
        $links = ob_get_clean();

        $check_box = "<input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/>";

        $row[] = "";
        $row[] = "{$check_box}";
        $row[] = "{$user_period_name}";//user period
        $row[] = "{$user_seat}";//user seat
        $row[] = "{$user_firstname}";
        $row[] = "{$user_lastname}";
        $row[] = "{$user_display_name}";
        $row[] = "{$links}";
        $row[] = "{$time}";
        $row[] = "{$title}";
        $row[] = "{$message}";

        $xp_toggle = get_option('options_go_loot_xp_toggle');
        $gold_toggle = get_option('options_go_loot_gold_toggle');
        $health_toggle = get_option('options_go_loot_health_toggle');

        if ($xp_toggle){
            $row[] = "{$xp}";
        }
        if ($gold_toggle){
            $row[] = "{$gold}";
        }
        if ($health_toggle){
            $row[] = "{$health}";
        }
        $row[] = "{$badges_names}";
        $output['aaData'][] = $row;
    }
    $output['iTotalDisplayRecords'] =  count($output['aaData']);


    echo json_encode( $output );
    die();
}

function go_clipboard_activity() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }

    //check_ajax_referer( 'go_clipboard_activity_' . get_current_user_id() );
    global $wpdb;

    $date_ajax = $_POST['date'];
    if (!empty($date_ajax)) {
        $date_ajax = date("Y-m-d", strtotime($date_ajax));
    }else{
        $date_ajax = null;
    }

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
            <th class="header">sections</th>
            <th class="header">groups</th>
            <th class="header">badges</th>
            <th class="header">Section</th>
            <th class="header"><?php echo "$seats_name"; ?></a></th>
            <th class="header">First</th>
            <th class="header">Last</th>
            <th class="header">Display</th>
            <th class="header">Links</th>
            <th class='header'>Activity</th>
        </tr>
        </thead>
        <tbody>
        <?php


        $go_task_table_name = "{$wpdb->prefix}go_actions";
        $actions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
			FROM {$go_task_table_name}
			WHERE DATE(TIMESTAMP) = %s
			ORDER BY TIMESTAMP DESC",
                $date_ajax
            )
        );


        foreach ( $rows as $row ) {
            $user_id = $row->uid;
            //$is_admin = go_user_is_admin($user_id);
            //if ($is_admin) {
             //   continue;
            //}
            $user_data_key = get_userdata($user_id);
            $user_display_name = $user_data_key->display_name;
            $user_firstname = $user_data_key->user_firstname;
            $user_lastname = $user_data_key->user_lastname;

            $group_ids = $row->groups;
            $group_ids = unserialize($group_ids);
            $group_ids = json_encode($group_ids);

            $badge_ids = $row->badges;
            $badge_ids = unserialize($badge_ids);
            $badge_ids = json_encode($badge_ids);

            $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);

            $action_list = array();

            $action_count = 0;
            $user_actions = array();
            foreach ($actions as $action) {
                $action = json_decode(json_encode($action), True);//convert stdclass to array by encoding and decoding
                $uid = intval($action['uid']);
                if ($uid == $user_id) {
                    $action_count++;
                    $user_actions[] = $action;
                }
            }

            $i=0;
            foreach ($user_actions as $action){
                $action = json_decode(json_encode($action), True);//convert stdclass to array by encoding and decoding
                $uid = intval($action['uid']);
                if ($uid == $user_id) {
                    $i++;
                    if ( $i < 4){
                        $class = 'recent_action';
                    }else if ($i == 4){
                        $more = $action_count - 3;
                        $action_list[] = "<tr class='show_more'><td colspan='6'><span class='show_more_actions'>Show</span><span class='hide_more_actions' style='display: none;'>Hide</span> " . $more . " more actions.</td></tr>";
                        $class = 'hidden_action';
                    }else{
                        $class = 'hidden_action';
                    }

                    ///////////
                    $action_type = $action['action_type'];
                    $source_id = $action['source_id'];
                    $TIMESTAMP = $action['TIMESTAMP'];
                    $time  = date("g:i A", strtotime($TIMESTAMP));
                    $stage = $action['stage'];
                    $bonus_status = $action['bonus_status'];
                    $result = $action['result'];
                    $quiz_mod = $action['quiz_mod'];
                    $late_mod = $action['late_mod'];
                    $timer_mod = $action['timer_mod'];
                    $health_mod = $action['global_mod'];
                    $xp = $action['xp'];
                    $gold = $action['gold'];
                    $health = $action['health'];
                    $xp_total = $action['xp_total'];
                    $gold_total = $action['gold_total'];
                    $health_total = $action['health_total'];
                    $action_badge_ids = $action['badges'];
                    $action_group_ids = $action['groups'];

                    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
                    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
                    $health_abbr = get_option( "options_go_loot_health_abbreviation" );


                    $badges_names = array();

                    $badges_toggle = get_option('options_go_badges_toggle');
                    if ($badges_toggle) {
                        $action_badge_ids = unserialize($action_badge_ids);
                        $badges_name_sing = get_option('options_go_badges_name_singular');

                        if (!empty($action_badge_ids)) {
                            $badges_names[] = "<b>" . $badges_name_sing . ":</b>";
                            foreach ($action_badge_ids as $action_badge_id) {
                                $term = get_term($action_badge_id, "go_badges");
                                $badge_name = $term->name;
                                $badges_names[] = $badge_name;
                            }
                        }
                    }


                    $group_names = array();
                    $action_group_ids = unserialize($action_group_ids);
                    if (!empty($action_group_ids)) {
                        if (!empty($action_badge_ids)) {
                            $badges_names[] = "<br>";
                        }
                        $group_names[] = "<b>Group:</b>";
                        foreach ($action_group_ids as $action_group_id) {
                            $term = get_term($action_group_id, "user_go_groups");
                            $group_name = $term->name;
                            $group_names[] = $group_name;
                        }
                    }
                    $badges_names = implode("<br>", $badges_names);
                    $group_names = implode("<br>", $group_names);

                    $post_title = get_the_title($source_id);


                    if ($action_type == 'message' || $action_type == 'reset') {
                        $type = ucfirst($action_type);
                        $result_array = unserialize($result);
                        $title = $result_array[0];
                        $message = $result_array[1];
                        $message = $title . ": <br>" . $message;
                        $action = "<span class='tooltip' ><span class='tooltiptext'>{$message}</span>See Message</span>";

                        if (!empty($badge_ids)) {
                            $badge_dir = $result_array[2];

                            if ($badge_dir == "badges+") {
                                $badge_dir = "<b>Add </b> ";
                            } else if ($badge_dir == "badges-") {
                                $badge_dir = "<b>Remove </b> ";
                            } else {
                                $badge_dir = "";
                            }
                        } else {
                            $badge_dir = "";
                        }

                        if (!empty($action_group_ids)) {
                            $groups_dir = $result_array[3];
                            if ($groups_dir == "groups+") {
                                $group_dir = "<b>Add </b> ";
                            } else if ($groups_dir == "groups-") {
                                $group_dir = "<b>Remove </b> ";
                            } else {
                                $group_dir = "";
                            }
                        } else {
                            $group_dir = "";
                        }

                    } else {
                        $badge_dir = "";
                        $group_dir = "";
                    }

                    $badges_names = $badge_dir . $badges_names . $group_dir . $group_names;

                    if ($action_type == 'store') {
                        $store_qnty = $stage;
                        $type = ucfirst(get_option('options_go_store_name'));
                        $action = "Qnt: " . $store_qnty;
                    }

                    if ($action_type == 'task') {
                        $type = ucfirst(get_option('options_go_tasks_name_singular'));
                        if ($bonus_status == 0) {
                            //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                            //$type = "Continue";
                            $action = "Stage: " . $stage;
                        } else {
                            //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                            // $type = "Continue";
                            $action = "Bonus: " . $bonus_status;
                        }
                    }

                    if ($action_type == 'undo_task') {
                        $type = ucfirst(get_option('options_go_tasks_name_singular'));
                        if ($bonus_status == 0) {
                            //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) ) . " Undo";
                            //$type = "Undo";
                            $action = "Undo Stage: " . $stage;
                        }
                    }
                    if ($result == 'undo_bonus') {
                        //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) ) . " Undo Bonus";
                        //$type = "Undo Bonus";
                        $action = "Undo Bonus: " . $bonus_status;
                    }

                    $quiz_mod_int = intval($quiz_mod);
                    if (!empty($quiz_mod_int)) {
                        $quiz_mod = "<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i> " . $quiz_mod;
                    } else {
                        $quiz_mod = null;
                    }

                    $late_mod_int = intval($late_mod);
                    if (!empty($late_mod_int)) {
                        $late_mod = "<i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> " . $late_mod;
                    } else {
                        $late_mod = null;
                    }

                    $timer_mod_int = intval($timer_mod);
                    if (!empty($timer_mod_int)) {
                        $timer_mod = "<i class=\"fa fa-hourglass\" aria-hidden=\"true\"></i> " . $timer_mod;
                    } else {
                        $timer_mod = null;
                    }

                    $health_mod_int = intval($health_mod);
                    if (!empty($health_mod_int)) {
                        $health_abbr = get_option("options_go_loot_health_abbreviation");
                        $health_mod_str = $health_abbr . ": " . $health_mod;
                    } else {
                        $health_mod_str = null;
                    }



                    $xp_toggle = get_option('options_go_loot_xp_toggle');
                    $gold_toggle = get_option('options_go_loot_gold_toggle');
                    $health_toggle = get_option('options_go_loot_health_toggle');

                    if ($xp_toggle) {
                        $xp = $xp_abbr . ": " . $xp;
                    }
                    if ($gold_toggle) {
                        $gold = $gold_abbr . ": " . $gold;
                    }
                    if ($health_toggle) {
                        $health = $health_abbr . ": " . $health;
                    }
                    //$row[] = "{$badges_names}";


                    $loot = $xp . "<br>" . $gold . "<br>" . $health;
                    $loot = "<span class='tooltip' ><span class='tooltiptext'>{$loot}</span>Loot</span>";

                    $action_list[] = "<tr class='" . $class . "'><td>" . $time . "</td><td>" . $type . "</td><td>" . $post_title . "</td><td>" . $action . "</td><td>" . $loot . "</td><td>" . $badges_names . "</td></tr>";

                }
                ////////////////////////////////////////////////////////////////////////
            }

            if(empty($date_ajax)){
                $action_list = "Select a Date";
            }
            else if (empty($action_list) || $action_list == ""){
                $action_list = "";
            }
            else {
                $action_list = "<table>" . implode('', $action_list) . "</table>";
            }


            //Sections and Seats
            $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
            if (empty($num_sections)){
                $num_sections =1;
            }
            $user_periods= array();
            $user_period_name= array();
            $user_seat = array();
            for ($i = 0; $i < $num_sections; $i++) {
                $user_period_option = "go_section_and_seat_" . $i . "_user-section";
                $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";

                $user_period = get_user_meta($user_id, $user_period_option, true);
                $user_periods[] = $user_period;
                $term = get_term($user_period, "user_go_sections");
                //$user_period_name = $term->name;
                $user_period_name[] = (isset($term->name) ? $term->name : null);

                $user_seat[] = get_user_meta($user_id, $user_seat_option, true);
            }

            //if this is not filtered, set the values
            $user_period_name = implode("<br>", $user_period_name);
            $user_seat = implode("<br>", $user_seat);

                echo "<tr style='vertical-align: top;'>
					<td></td>
					<td style='text-align: center;'><input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/></td>
					<td >{$user_period} </a></td>
					<td>{$group_ids}</a></td>
					<td>{$badge_ids}</a></td>
					<td >{$user_period_name} </a></td>
					<td>{$user_seat}</td>
					<td>{$user_firstname}</td>
					<td>{$user_lastname}</td>
					<td>{$user_display_name}</td>
					<td>";
                go_user_links($user_id, true, true, true, true, true, false);
                echo " </a></td>
					<td class='user_activity' style='padding: 4px;'>{$action_list} </td>
				  </tr>";



        }
        ?>

        </tbody></table></div>

    <?php
    die();
}

?>
