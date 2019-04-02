<?php
function go_filter_reader(){
    global $wpdb;


    $uWhere = go_reader_uWhere_values();

    $pWhere = go_reader_pWhere();

    //$sOrder = go_sOrder('tasks', $section);

    $sOn = go_reader_sOn('tasks');
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

    $lTable = "{$wpdb->prefix}go_loot";
    $aTable = "{$wpdb->prefix}go_actions";
    $uTable = "{$wpdb->prefix}users";
    $umTable = "{$wpdb->prefix}usermeta";
    //$tTable = "{$wpdb->prefix}posts";
    $pTable = "{$wpdb->prefix}posts";

    $order = $_POST['order'];
    $limit = intval($_POST['limit']);


    $sQuery = "
          SELECT
            t4.ID
          FROM (
              SELECT
              t1.uid, t1.badges, t1.groups,    
              t3.display_name, t3.user_url, t3.user_login,
              MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
              MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END)  AS section_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5
              FROM $lTable AS t1 
              LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id
              LEFT JOIN $uTable AS t3 ON t2.user_id = t3.ID
              GROUP BY t1.id
              $uWhere
          ) AS t5
          INNER JOIN $pTable AS t4 ON t5.uid = t4.post_author $sOn 
          $pWhere
          ORDER BY t4.post_modified $order
          LIMIT $limit
          
            
    ";
    //ORDER BY OPTIONS, OLDEST/NEWEST
    //LIMIT to 10 (or option)--Read more to show the next 10.
    // How to know what to load next.  What if new items were submitted?
    //  3 buttons: Mark all as read, Mark as read and load more, Load More.
    //  And buttons on individual items to mark as read.
    //  Mark sure that Mark all as read gets all post_ids with jQuery.


    //Add Badge and Group names from the action item?,
    //can't do because they might have multiple saved in a serialized array so it can't be joined.
    //go_write_log($sQuery);
    ////columns that will be returned
    $posts = $wpdb->get_results($sQuery, ARRAY_A);

    foreach ($posts as $post){
        $blog_post_id = $post['ID'];
        go_blog_post($blog_post_id, $check_for_understanding = false, $with_feedback = true);
    }

    die();
}



function go_reader_uWhere_values(){
    //CREATE THE QUERY
    //CREATE THE USER WHERE STATEMENT
    //check the drop down filters only
    //Query 1:
    //WHERE (uWhere)
    //User_meta by section_id from the drop down filter
    //loot table by badge_id from drop down filter
    //and group_id from the drop down filter.

    $section = $_POST['section'];
    $badge = $_POST['badge'];
    $group = $_POST['group'];

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

function go_reader_section(){
    $section = $_POST['section'];
    if ($section == ""){
        $section = 0;
    }
    //if (is_array($sections) && count($sections) === 1){
    //    $section = $sections[0];
    //}
    return $section;
}

function go_reader_sOn($action_type){
    $sOn = "";
    $date = $_POST['date'];


        if (isset($date) && $date != "") {
            $dates = explode(' - ', $date);
            $firstdate = $dates[0];
            $lastdate = $dates[1];
            $firstdate = date("Y-m-d", strtotime($firstdate));
            $lastdate = date("Y-m-d", strtotime($lastdate));
            $date = " AND ( DATE(t4.post_modified) BETWEEN '" . $firstdate . "' AND '" . $lastdate . "')";
        }
        $sOn .= $date;

    return $sOn;
}


function go_reader_pWhere(){


    $include_read = $_POST['read'];
    $include_unread = $_POST['unread'];
    $include_reset = $_POST['reset'];
    $include_trash = $_POST['trash'];
    $include_draft = $_POST['draft'];
    //$pWhere = "";
    $pWhere = "WHERE ((t4.post_type = 'go_blogs')";

    /*
    $pWhere = array();

    if ($include_read){
        $pWhere[] = 'read';
    }
    if ($include_unread){
        $pWhere[] = 'unread';
    }
    if ($include_reset){
        $pWhere[] = 'reset';
    }
    if ($include_trash){
        $pWhere[] = 'trash';
    }
    */

    if ($include_read === 'true' || $include_unread === 'true' || $include_reset === 'true' || $include_trash === 'true' || $include_trash === 'draft'  )
    {
        $pWhere .= " AND (";
        $first = true;

        if ($include_read === 'true'){
            $pWhere .= "(t4.post_status = 'read')";
            $first = false;
        }
        if ($include_unread === 'true'){
            if (!$first){
                $pWhere .= " OR " ;
            }
            $pWhere .= "(t4.post_status = 'unread')";
            $first = false;
        }
        if ($include_reset === 'true'){
            if (!$first){
                $pWhere .= " OR " ;
            }
            $pWhere .= "(t4.post_status = 'reset')";
            $first = false;
        }
        if ($include_trash === 'true'){
            if (!$first){
                $pWhere .= " OR " ;
            }
            $pWhere .= "(t4.post_status = 'trash')";
        }
        if ($include_draft === 'true'){
            if (!$first){
                $pWhere .= " OR " ;
            }
            $pWhere .= "(t4.post_status = 'draft')";
        }
        $pWhere .= ")";


    }

    $pWhere .= ")";

    return $pWhere;
}


