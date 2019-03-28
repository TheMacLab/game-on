<?php

//has ajax call
function go_make_taxonomy_dropdown_ajax(){
    // we will pass post IDs and titles to this array
    $return = array();

    $results = array();

    $is_acf = (isset($_GET['taxonomy2acf']) ?  $_GET['taxonomy2acf'] : false);

    if($is_acf){

        $field = acf_get_field( $_GET['field_key']);
        if( !$field ) return false;
        // bail early if taxonomy does not exist
        if( !taxonomy_exists($field['taxonomy']) ) return false;
        $taxonomy = $field['taxonomy'];

    }
    else {


        $taxonomy = $_GET['taxonomy']; // taxonomy
    }
    $is_hier = $_GET['is_hier']; // is it hierarchical
    ////////////////
    if ($is_hier === true || $is_hier === "true") {
        $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => '0');

        //parent terms
        $parents = get_terms($taxonomy, $args);

        foreach ( $parents as $parent ) {
            $title = ( mb_strlen( $parent->name ) > 50 ) ? mb_substr( $parent->name, 0, 49 ) . '...' : $parent->name;
            $return[] = array( $parent->term_id, $title, true ); // array( Post ID, Post Title )

            $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC', 'parent' => $parent->term_id);
            //children terms
            $children = get_terms($taxonomy, $args);
            foreach ( $children as $child ) {
                $title = ( mb_strlen( $child->name ) > 50 ) ? mb_substr( $child->name, 0, 49 ) . '...' : $child->name;
                $return[] = array( $child->term_id, $title, false ); // array( Post ID, Post Title )
            }
        }
        $terms = $return;
        $i = -1;
        $c = 0;
        foreach ($terms as $term){
            if ($term[2] == true){
                $i++;
                $results[$i]['text'] = $term[1];
                $c = 0;
            }
            else {
                $results[$i]['children'][$c]['id'] = $term[0];
                $results[$i]['children'][$c]['text'] = $term[1];
                $c++;
            }
        }
    }else{
        $args = array('hide_empty' => false, 'orderby' => 'order', 'order' => 'ASC');
        //children terms
        $children = get_terms($taxonomy, $args);
        foreach ( $children as $child ) {
            $title = ( mb_strlen( $child->name ) > 50 ) ? mb_substr( $child->name, 0, 49 ) . '...' : $child->name;
            $results[] = array(
                'id' => $child->term_id,
                'text' => $title ); // array( Post ID, Post Title )
        }

    }


    /*
    ////////////////////////
    $args = array(
        'hide_empty' => false,
        'orderby' => 'order',
        'order' => 'ASC',
        'search'=> $_GET['q'], // the search query)
        'posts_per_page' => 50, // how much to show at once\
    );

    $search_results = get_terms($taxonomy, $args);

    if( count($search_results) > 0 ){
        foreach ($search_results as $search_result){
            $title = ( mb_strlen( $search_result->name ) > 50 ) ? mb_substr( $search_result->name, 0, 49 ) . '...' : $search_result->name;
            //$return[] = array( $search_result->term_id, $title ); // array( Post ID, Post Title )
        }
    }
    */


    echo json_encode( $results );
    die;
}

function go_loot_headers($totals = null){
    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');
    if ($totals == true){
        $total = "Total ";

    }else{
        $total ="";
    }

    if ($xp_toggle){
        ?>
        <th class='header'><a href="#"><?php echo "$total" . "$xp_abbr"; ?></a></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><a href="#"><?php echo "$total" . "$gold_abbr"; ?></a></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><a href="#"><?php echo "$total" . "$health_abbr"; ?></a></th>
        <?php
    }
}
/**
 *
 */
function go_admin_bar_stats() {
    check_ajax_referer( 'go_admin_bar_stats_' );
    //$user_id = 0;
    //Get the user_id for the stats
    if ( ! empty( $_POST['uid'] ) ) {
        $user_id = (int) $_POST['uid'];
        $current_user = get_userdata( $user_id );
    } else {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
    }

    //is the current user an admin
    $current_user_id = get_current_user_id();
    $is_admin = go_user_is_admin($current_user_id);


    ?>
    <input type="hidden" id="go_stats_hidden_input" value="<?php echo $user_id; ?>"/>
    <?php
    $full_name_toggle = get_option('options_go_full-names_toggle');
    $user_fullname = $current_user->first_name.' '.$current_user->last_name;
    //$user_login =  $current_user->user_login;
    $user_display_name = $current_user->display_name;
    //$user_website = $current_user->user_url;



    $leaderboard_toggle = get_option('options_go_stats_leaderboard_toggle');


    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');
    if ($use_local_avatars){
        $user_avatar_id = get_user_option( 'go_avatar', $user_id );
        $user_avatar = wp_get_attachment_image($user_avatar_id);
    }
    if (empty($user_avatar) && $use_gravatar) {
        $user_avatar = get_avatar( $user_id, 150 );
    }

    //$user_focuses = go_display_user_focuses( $user_id );



    $current_points = go_return_points( $user_id );


    $go_option_ranks = get_option( 'go_ranks' );
    $points_array = $go_option_ranks['points'];

    $max_rank_index = count( $points_array ) - 1;
    $max_rank_points = (int) $points_array[ $max_rank_index ];

    $percentage_of_level = 1;

    // user pnc
    $rank = go_get_rank( $user_id );
    $current_rank = $rank['current_rank'];
    /*
    $current_rank_points = $rank['current_rank_points'];
    $next_rank = $rank['next_rank'];
    $next_rank_points = $rank['next_rank_points'];

    if ( null !== $next_rank_points ) {
        $rank_threshold_diff = ( $next_rank_points - $current_rank_points );
    } else {
        $rank_threshold_diff = 1;
    }
    $pts_to_rank_threshold = ( $current_points - $current_rank_points );

    if ( $max_rank_points === $current_rank_points ) {
        $prestige_name = get_option( 'go_prestige_name' );
        $pts_to_rank_up_str = $prestige_name;
    } else {
        $pts_to_rank_up_str = "{$pts_to_rank_threshold} / {$rank_threshold_diff}";
    }

    $percentage_of_level = ( $pts_to_rank_threshold / $rank_threshold_diff ) * 100;
    if ( $percentage_of_level <= 0 ) {
        $percentage_of_level = 0;
    } else if ( $percentage_of_level >= 100 ) {
        $percentage_of_level = 100;
    }
    */


    /////////////////////////
    ///
    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option( 'options_go_loot_health_toggle' );

    if ($xp_toggle) {
        // the user's current amount of experience (points)
        $go_current_xp = go_get_user_loot($user_id, 'xp');

        $rank = go_get_rank($user_id);
        $rank_num = $rank['rank_num'];
        $current_rank = $rank['current_rank'];
        $current_rank_points = $rank['current_rank_points'];
        $next_rank = $rank['next_rank'];
        $next_rank_points = $rank['next_rank_points'];

        $go_option_ranks = get_option('options_go_loot_xp_levels_name_singular');
        //$points_array = $go_option_ranks['points'];

        /*
         * Here we are referring to last element manually,
         * since we don't want to modifiy
         * the arrays with the array_pop function.
         */
        //$max_rank_index = count( $points_array ) - 1;
        //$max_rank_points = (int) $points_array[ $max_rank_index ];

        if ($next_rank_points != false) {
            $rank_threshold_diff = $next_rank_points - $current_rank_points;
            $pts_to_rank_threshold = $go_current_xp - $current_rank_points;
            $pts_to_rank_up_str = "L{$rank_num}: {$pts_to_rank_threshold} / {$rank_threshold_diff}";
            $percentage = $pts_to_rank_threshold / $rank_threshold_diff * 100;
            //$color = barColor( $go_current_health, 0 );
            $color = "#39b54a";
        } else {
            $pts_to_rank_up_str = $current_rank;
            $percentage = 100;
            $color = "gold";
        }
        if ( $percentage <= 0 ) {
            $percentage = 0;
        } else if ( $percentage >= 100 ) {
            $percentage = 100;
        }
        $progress_bar = '<div class="go_admin_bar_progress_bar_border progress-bar-border">'.'<div class="go_admin_bar_progress_bar stats_progress_bar" '.
            'style="width: '.$percentage.'%; background-color: '.$color.' ;">'.
            '</div>'.
            '<div class="points_needed_to_level_up ">'.
            $pts_to_rank_up_str.
            '</div>'.
            '</div>';
    }
    else {
        $progress_bar = '';
    }


    if($health_toggle) {
        // the user's current amount of bonus currency,
        // also used for coloring the admin bar
        $go_current_health = go_get_user_loot($user_id, 'health');
        $health_percentage = intval($go_current_health / 2);
        if ($health_percentage <= 0) {
            $health_percentage = 0;
        } else if ($health_percentage >= 100) {
            $health_percentage = 100;
        }
        $health_bar = '<div class="go_admin_health_bar_border progress-bar-border">' . '<div class="go_admin_bar_health_bar stats_progress_bar" ' . 'style="width: ' . $health_percentage . '%; background-color: red ;">' . '</div>' . '<div class="health_bar_percentage_str">' . "Health Mod: " . $go_current_health . "%" . '</div>' . '</div>';

    }
    else{
        $health_bar = '';
    }

    if ($gold_toggle) {
        // the user's current amount of currency
        $go_current_gold = go_get_user_loot($user_id, 'gold');
        $gold_total = '<div  class="go_admin_bar_gold admin_bar_loot">' . go_display_shorthand_currency('gold', $go_current_gold)  . '</div>';
    }
    else{
        $gold_total = '';
    }

    //////////////////



    ?>
    <script>
        go_stats_links();
        jQuery('#wp-admin-bar-go_stats').prop('onclick',null).off('click');
        jQuery("#wp-admin-bar-go_stats").one("click", function(){ go_admin_bar_stats_page_button()});
    </script>
    <div id='go_stats_lay'>
        <div id='go_stats_header'>
            <div class="go_stats_id_card">
                <div class='go_stats_gravatar'><?php echo $user_avatar; ?></div>

                <div class='go_stats_user_info'>
                    <?php
                    if ($full_name_toggle || $is_admin){
                        echo "<h2>{$user_fullname}</h2>{$user_display_name}<br>";
                    }else{
                        echo "<h2>{$user_display_name}</h2>";
                    }
                    go_user_links($user_id, true, false, true, true, true, false);
                    ?>

                </div>
            </div>
            <div class="go_stats_bars">
                <?php
                if ($xp_toggle) {
                    echo '<div class="go_stats_rank"><h3>' . $go_option_ranks . ' ' . $rank_num . ": " . $current_rank . '</h3></div>';
                }
                echo $progress_bar;
                //echo "<div id='go_stats_user_points'><span id='go_stats_user_points_value'>{$current_points}</span> {$points_name}</div><div id='go_stats_user_currency'><span id='go_stats_user_currency_value'>{$current_currency}</span> {$currency_name}</div><div id='go_stats_user_bonus_currency'><span id='go_stats_user_bonus_currency_value'>{$current_bonus_currency}</span> {$bonus_currency_name}</div>{$current_penalty} {$penalty_name}<br>{$current_minutes} {$minutes_name}";
                echo $health_bar;
                ?>
            </div>
            <div class='go_stats_user_loot'>

                <?php

                if ($xp_toggle) {
                    echo '<div class="go_stats_xp">' . go_display_longhand_currency('xp', $go_current_xp) . '</div>';
                }
                if ($gold_toggle) {
                    echo '<div class="go_stats_gold">' . go_display_longhand_currency('gold', $go_current_gold) . '</div>';
                }
                if ($health_toggle) {
                    echo '<div class="go_stats_health">' . go_display_longhand_currency('health', $go_current_health) . '</div>';
                }
                ?>
            </div>
        </div>

        <div id="stats_tabs">
            <ul>
                <?php
                $current_user_id = get_current_user_id();
                $is_admin = go_user_is_admin($current_user_id);
                if ($is_admin){
                    echo '<li class="stats_tabs" tab="about"><a href="#stats_about">ABOUT</a></li>';
                }
                ?>


                <li class="stats_tabs" tab="tasks"><a href="#stats_tasks"><?php echo strtoupper( get_option( 'options_go_tasks_name_plural' ) ); ?></a></li>
                <li class="stats_tabs" tab="store"><a href="#stats_store"><?php echo strtoupper( get_option( 'options_go_store_name' ) ); ?></a></li>
                <li class="stats_tabs" tab="messages"><a href="#stats_messages">MESSAGES</a></li>
                <li class="stats_tabs" tab="history"><a href="#stats_history">HISTORY</a></li>
                <li class="stats_tabs" tab="badges"><a href="#stats_badges"><?php echo strtoupper( get_option( 'options_go_badges_name_plural' ) ); ?></a></li>
                <li class="stats_tabs" tab="groups"><a href="#stats_groups">GROUPS</a></li>

                <?php
                if ($leaderboard_toggle){
                    ?>
                    <li class="stats_tabs" tab="leaderboard"><a href="#stats_leaderboard"><?php echo strtoupper(get_option('options_go_stats_leaderboard_name')); ?></a></li>

                    <?php
                }
                if (!$is_admin){
                    echo '<li class="stats_tabs" tab="about"><a href="#stats_about">ABOUT</a></li>';
                }
                ?>

            </ul>
            <?php
            if($is_admin){
                echo '<div id="stats_about">';

                go_stats_about($user_id,  true);
                echo "</div>";
            }
            ?>

            <div id="stats_tasks"></div>
            <div id="stats_store"></div>
            <div id="stats_messages"></div>
            <div id="stats_history"></div>
            <div id="stats_badges"></div>
            <div id="stats_groups"></div>
            <?php
            if ($leaderboard_toggle){
                ?>
                <div id="stats_leaderboard"></div>
                <?php
            }
            if(!$is_admin){
                echo '<div id="stats_about"></div>';
            }
            ?>

        </div>


    </div>
    <?php
    die();
}

/**
 * @param null $user_id
 * @param bool $not_ajax
 */
function go_stats_about($user_id = null, $not_ajax = false) {

    if ( ! empty( $_POST['user_id'] ) && empty($user_id) ) {
        $user_id = (int) $_POST['user_id'];
    }

    if (!$not_ajax){
        check_ajax_referer( 'go_stats_about' );
    }

    echo "<div id='go_stats_about' class='go_datatables'>";
    $headshot_id = get_user_option('go_headshot', $user_id ) ;
    $headshot = wp_get_attachment_image($headshot_id);
    ?>
    <div class='go_stats_gravatar'><?php echo $headshot; ?></div>
    <?php

    $num_of_qs = get_option('options_go_user_profile_questions');

    for ($i = 0; $i < $num_of_qs; $i++) {
        $q_title = get_option('options_go_user_profile_questions_' . $i . '_title');
        $q_answer = get_user_option('question_' . $i, $user_id);

        echo "<h4>{$q_title}</h4>";
        echo "<p>{$q_answer}</p>";
    }

    echo "</div>";

    //die();
}

/**Tasks with Sever Side Processing--in case the tables get too large*/

function go_stats_task_list() {

    check_ajax_referer( 'go_stats_task_list_' );
    $current_user = get_current_user_id();
    $is_admin = go_user_is_admin($current_user);

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');


    echo "<div id='go_task_list' class='go_datatables'><table id='go_tasks_datatable' class='pretty display'>
                   <thead>
						<tr>";
    if ($is_admin){
        echo "<th></th><th class='header go_tasks_reset_multiple'  style='color: red;'><a href='#' class='go_tasks_reset_multiple_clipboard'><i class='fa fa-times-circle' aria-hidden='true'></i></a></th>
    <th class='header go_tasks_reset' ><a href='#'></a></th>";
    }
    echo "    
        <th class='header' id='go_stats_last_time'><a href=\"#\">Time</a></th>
        <th class='header' id='go_stats_post_name'><a href=\"#\">Title</a></th>
        
    
        <th class='header' id='go_stats_status'><a href=\"#\">Status</a></th>
        <th class='header' id='go_stats_bonus_status'><a href=\"#\">Bonus</a></th>
        <th class='header' id='go_stats_actions'><a href=\"#\">Actions</a></th>
        <th class='header' id='go_stats_links'><a href=\"#\">History</a></th>";
    go_loot_headers();
    echo"
            </tr>
            </thead>
            <tfoot>
            <tr>";
    if ($is_admin){
        echo "<th></th><th class='header go_tasks_reset_multiple'  style='color: red;'><a href='#' class='go_tasks_reset_multiple_clipboard'><i class='fa fa-times-circle' aria-hidden='true'></i></a></th>
    <th class='header go_tasks_reset' ><a href='#'></a></th>";
    }
    echo "
							<th class='header' id='go_stats_last_time'><a href=\"#\">Time</a></th>
							<th class='header' id='go_stats_post_name'><a href=\"#\">Title</a></th>
							
						
							<th class='header' id='go_stats_status'><a href=\"#\">Status</a></th>
							<th class='header' id='go_stats_bonus_status'><a href=\"#\">Bonus</a></th>
							<th class='header' id='go_stats_actions'><a href=\"#\">Actions</a></th>
                            <th class='header' id='go_stats_links'><a href=\"#\">History</a></th>";

    go_loot_headers();
    echo"
            </tr>
            </tfoot>
			   
				</table></div>";
    die();
}

function go_tasks_dataloader_ajax(){
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    $aColumns = array( 'id', 'uid', 'post_id', 'status', 'bonus_status' ,'xp', 'gold', 'health', 'start_time', 'last_time', 'badges', 'groups' );
    $sIndexColumn = "id";
    $sTable = $go_task_table_name;

    /*
    $sLimit = "";
    if ( isset( $_REQUEST['iDisplayStart'] ) && $_REQUEST['iDisplayLength'] != '-1' )
    {
        $sLimit = "LIMIT ".intval( $_REQUEST['iDisplayStart'] ).", ".
            intval( $_REQUEST['iDisplayLength'] );
    }
    */

    $sLimit = '';
    if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' )
    {
        $sLimit = "LIMIT ".intval( $_GET['start'] ).", ".
            intval( $_GET['length'] );
    }

    $sOrder = "ORDER BY last_time desc"; //always in reverse order
    ///////////
    /// ////
    ///
    ///OLD
    ///
    ///
    $sWhere = "";
    if ( isset($_REQUEST['sSearch']) && $_REQUEST['sSearch'] != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".esc_sql( $_REQUEST['sSearch'] )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }

    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( isset($_REQUEST['bSearchable_'.$i]) && $_REQUEST['bSearchable_'.$i] == "true" && $_REQUEST['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".esc_sql($_REQUEST['sSearch_'.$i])."%' ";
        }
    }

    $sQuery = "
  SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
  FROM   $sTable
  $sWhere
  $sOrder
  $sLimit
  ";
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "
  SELECT FOUND_ROWS()
 ";
    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);
    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
  SELECT COUNT(`".$sIndexColumn."`)
  FROM   $sTable
 ";
    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);
    $iTotal = $rResultTotal [0];

    $output = array(
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    /////////////
    /// START
    ///
    $search_val = $_GET['search']['value'];

    $user_id = $_GET['user_id'];

    $sWhere = "WHERE uid = ".$user_id;

    if ( isset($search_val) && $search_val != "" )
    {

        $sWhere .= " AND (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );//removes the last OR

        $sWhere .= ')';
    }

    $totalWhere = " WHERE uid = ".$user_id;

    $pTable = "{$wpdb->prefix}posts";
    /*
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS
      t1.*, t2.post_title
    FROM
        (
          SELECT `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
          FROM   $sTable
          $sWhere
          $sOrder
          $sLimit
        ) AS t1
      INNER JOIN $pTable AS t2 ON t1.post_id = t2.ID
      ";
    */
    $sQuery = "
          SELECT `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
          FROM   $sTable
          $sWhere
          $sOrder
          $sLimit
  
      ";

    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
      SELECT COUNT(`".$sIndexColumn."`)
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

    //////////////////
    ///
    ///END
    ///
    ///
    ///
    foreach($rResult as $task){//output a row for each task
        $row = array();
        ///////////
        ///
        $post_id = $task['post_id'];
        //$post_name = $task[post_title];
        $custom_fields = get_post_custom( $post_id );
        $post_name = get_the_title($post_id);
        $post_link = get_post_permalink($post_id);
        $status = $task['status'];
        $total_stages = (isset($custom_fields['go_stages'][0]) ?  $custom_fields['go_stages'][0] : null);


        $bonus_switch = (isset($custom_fields['bonus_switch'][0]) ?  $custom_fields['bonus_switch'][0] : null);
        $bonus_status = null;
        $total_bonus_stages = null;
        if ($bonus_switch) {
            $bonus_status = $task['bonus_status'];
            $total_bonus_stages = (isset($custom_fields['go_bonus_limit'][0]) ? $custom_fields['go_bonus_limit'][0] : null);
            $bonus_status = $bonus_status ."/". $total_bonus_stages;
        }
        //$xp = $task['xp'];
        //$gold = $task['gold'];
        //$health = $task['health'];
        //$start_time = $task->start_time;
        $last_time = $task['last_time'];
        $time  = date("m/d/y g:i A", strtotime($last_time));
        //$unix_time = strtotime($last_time);


        $next_bonus_stage = null;

        $links = array();

        $links = array_reverse($links);
        $links = $comma_separated = implode(" ", $links);
        $check_box = "<input class='go_checkbox' type='checkbox' name='go_selected' data-uid='" . $user_id . "' data-task='". $post_id . "'/>";
        $row[] = "";
        $row[] = "{$check_box}";
        $row[] = '<a><i data-uid="' . $user_id . '" data-task="'. $post_id . '" style="padding: 0px 10px;" class="go_reset_task_clipboard fa fa-times-circle" aria-hidden="true"></a>';
        $row[] = "{$time}";
        $row[] = "<a href='{$post_link}' >{$post_name}</a>";
        $row[] = "{$status} / {$total_stages}";
        $row[] = "{$bonus_status}";
        $row[] = '<a href="javascript:;" class="go_blog_user_task" data-UserId="'.$user_id.'" onclick="go_blog_user_task('.$user_id.', '.$post_id.');"><i style="padding: 0px 10px;" class="fa fa-eye" aria-hidden="true"></i></a>';//actions

        $row[] = " <a href='javascript:;' class='go_stats_body_activity_single_task' data-postID='{$post_id}' onclick='go_stats_single_task_activity_list({$post_id});'><i style=\"padding: 0px 10px;\" class=\"fa fa-table\" aria-hidden=\"true\"></i></a>";

        $go_loot_columns = go_loot_columns_stats($task);
        $row = array_merge($row, $go_loot_columns);

        $output['aaData'][] = $row;
    }


    echo json_encode( $output );
    die();
}

/**
 * Called by the ajax dataloaders.
 * @param $action
 * @return array
 */
function go_loot_columns_stats($action){
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
 * @param $post_id
 */
function go_stats_single_task_activity_list($post_id) {
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_actions";
    if ( ! empty( $_POST['user_id'] ) ) {
        $user_id = (int) $_POST['user_id'];
    }
    check_ajax_referer( 'go_stats_single_task_activity_list' );
    $post_id = (int) $_POST['postID'];

    $task_name = get_option('options_go_tasks_name_singular');
    $tasks_name = get_option('options_go_tasks_name_plural');

    $actions = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d and source_id = %d",
            $user_id,
            $post_id
        )
    );
    $post_title = get_the_title($post_id);
    echo "<div id='go_task_list_single' class='go_datatables'>
            <div style='float: right;'><a onclick='go_close_single_history()' href='javascript:void(0);'><i class='fa fa-times ab-icon' aria-hidden='true'></i> Show All $tasks_name</a></div>
            <h3>Single $task_name History: $post_title</h3>

            <table id='go_single_task_datatable' class='pretty display'>
                   <thead>
						<tr>
						
							<th class='header' id='go_stats_time'><a href=\"#\">Time</a></th>
							<th class='header' id='go_stats_action'><a href=\"#\">Action</a></th>
							<th class='header' id='go_stats_post_name'><a href=\"#\">Stage</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">Modifiers</a></th>";
    go_loot_headers();
    //go_loot_headers(true);
    echo"
						</tr>
						</thead>
			    <tbody>
						";
    foreach ( $actions as $action ) {
        $action_type = $action->action_type;
        $source_id = $action->source_id;
        $TIMESTAMP = $action->TIMESTAMP;
        $time  = date("m/d/y g:i A", strtotime($TIMESTAMP));
        $stage = $action->stage;
        $bonus_status = $action->bonus_status;
        $result = $action->result;
        $quiz_mod = $action->quiz_mod;
        $late_mod = $action->late_mod;
        $timer_mod = $action->timer_mod;
        $health_mod = $action->global_mod;
        $xp = $action->xp;
        $gold = $action->gold;
        $health = $action->health;
        $xp_total = $action->xp_total;
        $gold_total = $action->gold_total;
        $health_total = $action->health_total;

        $post_title = get_the_title($source_id);


        if ($action_type == 'admin'){
            $type = "Admin";
        }
        if ($action_type == 'reset'){
            $type = "Reset";
        }

        if ($action_type == 'store'){
            $store_qnty = $stage;
            $type = strtoupper( get_option( 'options_go_store_name' ) );
            $post_title = "Qnt: " . $store_qnty . " of " . $post_title ;
        }

        if ($action_type == 'task'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                $type = 'Continue';
                $post_title = " Stage: " . $stage;
            }
        }

        if ($action_type == 'undo_task'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                $type = "Undo";
                $post_title = " Stage: " . $stage;
            }
        }
        if ($result == 'undo_bonus'){
            $type = "Undo Bonus";
            $post_title = $post_title . " Bonus: " . $bonus_status ;
        }

        $quiz_mod_int = intval($quiz_mod);
        if (!empty($quiz_mod_int)){
            $quiz_mod = "<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i> ". $late_mod;
        }
        else{
            $quiz_mod = null;
        }

        $late_mod_int = intval($late_mod);
        if (!empty($late_mod_int)){
            $late_mod = "<i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> ". $late_mod;
        }
        else{
            $late_mod = null;
        }

        $timer_mod_int = intval($timer_mod);
        if (!empty($timer_mod_int)){
            $timer_mod = "<i class=\"fa fa-hourglass\" aria-hidden=\"true\"></i> ". $timer_mod;
        }
        else{
            $timer_mod = null;
        }

        $health_mod_int = intval($health_mod);
        if (!empty($health_mod_int)){
            $health_abbr = get_option( "options_go_loot_health_abbreviation" );
            $health_mod_str = $health_abbr . ": ". $health_mod;
        }
        else{
            $health_mod_str = null;
        }

        echo " 			
			        <tr id='postID_{$source_id}'>
			            <td data-order='{$TIMESTAMP}'>{$time}</td>
					    <td>{$type} </td>
					    <td>{$post_title} </td>
					    <td>{$health_mod_str}   {$timer_mod}   {$late_mod}   {$quiz_mod}</td>
					    
					    <td>{$xp}</td>
					    <td>{$gold}</td>
					    <td>{$health}</td>
					</tr>
					";


    }
    echo "</tbody>
				</table></div>";

    die();
}

/**
 *
 */
function go_stats_item_list() {
    check_ajax_referer( 'go_stats_item_list_' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');

    echo "<div id='go_item_list' class='go_datatables'><table id='go_store_datatable' class='pretty display'>
                   <thead>
						<tr>
						
							<th class='header'><a href=\"#\">Time</a></th>
							<th class='header'><a href=\"#\">Item</a></th>					
							<th class='header'><a href=\"#\">QTY</a></th>";
    if ($xp_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$xp_abbr"; ?></a></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$gold_abbr"; ?></a></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$health_abbr"; ?></a></th>
        <?php
    }
    echo "<th class='header'><a href='#'>Other</a></th>
					</tr>
						</thead>

				</table></div>";

    die();
}

/**
 * go_messages_dataloader_ajax
 * Called for Server Side Processing from the JS
 */
function go_stats_store_item_dataloader(){
    global $wpdb;

    $go_action_table_name = "{$wpdb->prefix}go_actions";

    $aColumns = array( 'id', 'uid', 'source_id', 'action_type', 'stage', 'TIMESTAMP' , 'result', 'global_mod', 'xp', 'gold', 'health', 'badges', 'groups' );

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

    $user_id = $_GET['user_id'];

    $sWhere = "WHERE uid = ".$user_id . " AND (action_type = 'store') ";

    if ( isset($search_val) && $search_val != "" )
    {

        $sWhere .= " AND (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );//removes the last OR


        $sWhere .= ')';

    }

    $totalWhere = " WHERE uid = ".$user_id . " AND (action_type = 'store') ";

    $pTable = "{$wpdb->prefix}posts";
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS 
      t1.*, t2.post_title 
    FROM
        (
          SELECT `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
          FROM   $sTable
          $sWhere
          $sOrder
          $sLimit
        ) AS t1
      INNER JOIN $pTable AS t2 ON t1.source_id = t2.ID
      ";

    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
      SELECT COUNT(`".$sIndexColumn."`)
      FROM   $sTable
      leftjoin
      $totalWhere
     ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];

    $output = array(
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    foreach($rResult as $action){//output a row for each task
        $row = array();
        ///////////
        ///
        $action_type = $action['action_type'];
        $TIMESTAMP = $action['TIMESTAMP'];
        $result = $action['result'];
        $health_mod = $action['global_mod'];
        $xp = $action['xp'];
        $gold = $action['gold'];
        $health = $action['health'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];
        $title = $action['post_title'];
        $qnt = $action['stage'];

        $badges_names = array();
        $badges_toggle = get_option('options_go_badges_toggle');

        if ($badges_toggle) {
            $badge_ids = unserialize($badge_ids);
            $badges_name_sing = get_option('options_go_badges_name_singular');

            if (!empty($badge_ids)) {
                $badges_names[] = "<b>" . $badges_name_sing . ":</b>";
                foreach ($badge_ids as $badge_id) {
                    $term = get_term($badge_id, "go_badges");
                    $badge_name = $term->name;
                    $badges_names[] = $badge_name;
                }
            }
        }


        $group_names = array();
        $group_ids = unserialize($group_ids);
        if (!empty($group_ids)){
            if (!empty($badge_ids)) {
                $badges_names[] = "<br>";
            }
            $group_names[] = "<b>Group:</b>";
            foreach ($group_ids as $group_id) {
                $term = get_term($group_id, "user_go_groups");
                $group_name = $term->name;
                $group_names[] = $group_name;
            }
        }
        $badges_names = implode("<br>" , $badges_names);
        $group_names = implode("<br>" , $group_names);


        //$message = $title . ": <br>" . $message;
        //$action = "<span class='tooltip' ><span class='tooltiptext'>{$message}</span>See Message</span>";



        $badges_names =  $badges_names . $group_names;

        //$unix_time = strtotime($TIMESTAMP);
        $row[] = "{$TIMESTAMP}";
        $row[] = "{$title}";
        $row[] = "{$qnt}";

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


    echo json_encode( $output );
    die();
}

/**
 *
 */
function go_stats_messages() {
    check_ajax_referer( 'go_stats_messages' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');

    echo "<div id='go_messages' class='go_datatables'><table id='go_messages_datatable' class='pretty display'>
                   <thead>
						<tr>
						
							<th class='header'><a href=\"#\">Time</a></th>
							<th class='header'><a href=\"#\">Title</a></th>					
							<th class='header'><a href=\"#\">Message</a></th>";


    if ($xp_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$xp_abbr"; ?></a></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$gold_abbr"; ?></a></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$health_abbr"; ?></a></th>
        <?php
    }



    echo "<th class='header'><a href='#'>Other</a></th>
					</tr>
						</thead>

				</table></div>";

    die();
}

/**
 * go_messages_dataloader_ajax
 * Called for Server Side Processing from the JS
 */
function go_messages_dataloader_ajax(){

    global $wpdb;
    $go_action_table_name = "{$wpdb->prefix}go_actions";

    $aColumns = array( 'id', 'uid', 'action_type', 'TIMESTAMP' , 'result', 'global_mod', 'xp', 'gold', 'health', 'badges', 'groups' );

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

    $user_id = $_GET['user_id'];

    $sWhere = "WHERE uid = ".$user_id . " AND (action_type = 'message' OR action_type = 'reset') ";

    if ( isset($search_val) && $search_val != "" )
    {

        $sWhere .= " AND (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );//removes the last OR


        $sWhere .= ')';

    }

    $totalWhere = " WHERE uid = ".$user_id . " AND (action_type = 'message' OR action_type = 'reset') ";

    $sQuery = "
      SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
      FROM   $sTable
      $sWhere
      $sOrder
      $sLimit
      ";

    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
      SELECT COUNT(`".$sIndexColumn."`)
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

    foreach($rResult as $action){//output a row for each task
        $row = array();
        ///////////
        ///
        $action_type = $action['action_type'];
        $TIMESTAMP = $action['TIMESTAMP'];
        $result = $action['result'];
        $xp = $action['xp'];
        $gold = $action['gold'];
        $health = $action['health'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];

        $badges_names = array();
        $badges_toggle = get_option('options_go_badges_toggle');

        if ($badges_toggle) {
            $badge_ids = unserialize($badge_ids);
            $badges_name_sing = get_option('options_go_badges_name_singular');

            if (!empty($badge_ids)) {
                $badges_names[] = "<b>" . $badges_name_sing . ":</b>";
                foreach ($badge_ids as $badge_id) {
                    $term = get_term($badge_id, "go_badges");
                    $badge_name = $term->name;
                    $badges_names[] = $badge_name;
                }
            }
        }


        $group_names = array();
        $group_ids = unserialize($group_ids);
        if (!empty($group_ids)){
            if (!empty($badge_ids)) {
                $badges_names[] = "<br>";
            }
            $group_names[] = "<b>Group:</b>";
            foreach ($group_ids as $group_id) {
                $term = get_term($group_id, "user_go_groups");
                $group_name = $term->name;
                $group_names[] = $group_name;
            }
        }
        $badges_names = implode("<br>" , $badges_names);
        $group_names = implode("<br>" , $group_names);


        $type = ucfirst($action_type);
        $result_array = unserialize($result);
        $title = $result_array[0];
        $message = $result_array[1];
        //$message = $title . ": <br>" . $message;
        //$action = "<span class='tooltip' ><span class='tooltiptext'>{$message}</span>See Message</span>";

        if (!empty($badge_ids)) {
            $badge_dir = $result_array[2];

            //$badges_name = get_option('options_go_badges_name_plural');

            if ($badge_dir == "badges+"){
                $badge_dir = "<b>Add </b> ";
            }else if ($badge_dir == "badges-"){
                $badge_dir = "<b>Remove </b> ";
            }else{
                $badge_dir = "";
            }
        }
        else{
            $badge_dir = "";
        }

        if (!empty($group_ids)){
            $groups_dir = $result_array[3];
            if ($groups_dir == "groups+"){
                $group_dir = "<b>Add </b> ";
            }else if ($groups_dir == "groups-") {
                $group_dir = "<b>Remove </b> ";
            }else{
                $group_dir = "";
            }
        }else{
            $group_dir = "";
        }

        $badges_names = $badge_dir . $badges_names . $group_dir . $group_names;


        //$unix_time = strtotime($TIMESTAMP);
        $row[] = "{$TIMESTAMP}";
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


    echo json_encode( $output );
    die();
}
//History table
/**
 *
 */
function go_stats_activity_list() {
    check_ajax_referer( 'go_stats_activity_list_' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');

    echo "<div id='go_activity_list' class='go_datatables'><table id='go_activity_datatable' class='pretty display'>
                   <thead>
						<tr>
						
							<th class='header'><a href=\"#\">Time</a></th>
							<th class='header'><a href=\"#\">Type</a></th>
							
							<th class='header'><a href=\"#\">Item</a></th>
							<th class='header'><a href=\"#\">Action</a></th>
							<th class='header'><a href=\"#\">Modifiers</a></th>";


    if ($xp_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$xp_abbr"; ?></a></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$gold_abbr"; ?></a></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><a href=\"#\"><?php echo "$health_abbr"; ?></a></th>
        <?php
    }
    if ($xp_toggle){
        ?>
        <th class='header'><a href=\"#\">Total<br><?php echo "$xp_abbr"; ?></a></th>
        <?php
    }
    if ($gold_toggle){
        ?>
        <th class='header'><a href=\"#\">Total<br><?php echo "$gold_abbr"; ?></a></th>
        <?php
    }
    if ($health_toggle){
        ?>
        <th class='header'><a href=\"#\">Total<br><?php echo "$health_abbr"; ?></a></th>
        <?php
    }


    echo "<th class='header'><a href='#'>Other</a></th>
					</tr>
						</thead>

				</table></div>";

    die();
}


/**
 * go_activity_dataloader_ajax
 * Called for Server Side Processing from the JS
 */
function go_activity_dataloader_ajax(){

    global $wpdb;
    $go_action_table_name = "{$wpdb->prefix}go_actions";

    $aColumns = array( 'id', 'uid', 'action_type', 'source_id', 'TIMESTAMP' ,'stage', 'bonus_status', 'check_type', 'result', 'quiz_mod', 'late_mod', 'timer_mod', 'global_mod', 'xp', 'gold', 'health', 'xp_total', 'gold_total', 'health_total', 'badges', 'groups' );

    $sIndexColumn = "id";
    $sTable = $go_action_table_name;

    $sLimit = '';

    if ( isset( $_GET['start'] ) && $_GET['length'] != '-1' )
    {
        $sLimit = "LIMIT ".intval( $_GET['start'] ).", ".
            intval( $_GET['length'] );
    }

    $sOrder = "ORDER BY TIMESTAMP desc"; //always in reverse order

    $sWhere = "";
    $search_val = $_GET['search']['value'];

    if ( isset($search_val) && $search_val != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            $sWhere .= "`".$aColumns[$i]."` LIKE '%".esc_sql( $search_val )."%' OR ";
        }
        $sWhere = substr_replace( $sWhere, "", -3 );


        $posts_table_name = "{$wpdb->prefix}posts";

        $tWhere = " WHERE post_title LIKE '%".$search_val."%'";
        $task_id_query = "
                SELECT ID
          FROM $posts_table_name
          $tWhere
        
        ";
        $task_ids = $wpdb->get_results($task_id_query, ARRAY_A);
        if(is_array($task_ids)){
            $sWhere .= "OR ";

            foreach ($task_ids as $task_id){
                $sWhere .= "`source_id` LIKE '%".esc_sql( $task_id['ID'] )."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
        }
        $sWhere .= ')';

    }


    //add the filter by UID
    $user_id = $_GET['user_id'];
    if($user_id != ''){
        if ( $sWhere == "" )
        {
            $sWhere = "WHERE ";
        }
        else
        {
            $sWhere .= " AND ";
        }
        $sWhere .= "uid = ".$user_id . " AND NOT action_type = 'admin_notification'";
    }

    $totalWhere = " WHERE uid = ".$user_id . " AND NOT action_type = 'admin_notification'";


    $sQuery = "
    
      SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
      FROM   $sTable
      $sWhere
      $sOrder
      $sLimit
      
      ";

    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "
  SELECT FOUND_ROWS()
 ";
    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);
    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
  SELECT COUNT(`".$sIndexColumn."`)
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

    foreach($rResult as $action){//output a row for each task
        $row = array();
        ///////////
        ///
        $action_type = $action['action_type'];
        $source_id = $action['source_id'];
        $TIMESTAMP = $action['TIMESTAMP'];
        //$time  = date("m/d/y g:i A", strtotime($TIMESTAMP));
        $time = $TIMESTAMP;
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
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];

        $badges_names = array();

        $badges_toggle = get_option('options_go_badges_toggle');
        if ($badges_toggle) {
            $badge_ids = unserialize($badge_ids);
            $badges_name_sing = get_option('options_go_badges_name_singular');

            if (!empty($badge_ids)) {
                $badges_names[] = "<b>" . $badges_name_sing . ":</b>";
                foreach ($badge_ids as $badge_id) {
                    $term = get_term($badge_id, "go_badges");
                    $badge_name = $term->name;
                    $badges_names[] = $badge_name;
                }
            }
        }


        $group_names = array();
        $group_ids = unserialize($group_ids);
        if (!empty($group_ids)){
            if (!empty($badge_ids)) {
                $badges_names[] = "<br>";
            }
            $group_names[] = "<b>Group:</b>";
            foreach ($group_ids as $group_id) {
                $term = get_term($group_id, "user_go_groups");
                $group_name = $term->name;
                $group_names[] = $group_name;
            }
        }
        $badges_names = implode("<br>" , $badges_names);
        $group_names = implode("<br>" , $group_names);

        $post_title = get_the_title($source_id);


        if ($action_type == 'message' || $action_type == 'reset'){
            $type = ucfirst($action_type);
            $result_array = unserialize($result);
            $title = $result_array[0];
            $message = $result_array[1];
            $message = $title . ": <br>" . $message;
            $action = "<span class='tooltip' ><span class='tooltiptext'>{$message}</span>See Message</span>";

            if (!empty($badge_ids)) {
                $badge_dir = $result_array[2];

                //$badges_name = get_option('options_go_badges_name_plural');

                if ($badge_dir == "badges+"){
                    $badge_dir = "<b>Add </b> ";
                }else if ($badge_dir == "badges-"){
                    $badge_dir = "<b>Remove </b> ";
                }else{
                    $badge_dir = "";
                }
            }
            else{
                $badge_dir = "";
            }

            if (!empty($group_ids)){
                $groups_dir = $result_array[3];
                if ($groups_dir == "groups+"){
                    $group_dir = "<b>Add </b> ";
                }else if ($groups_dir == "groups-") {
                    $group_dir = "<b>Remove </b> ";
                }else{
                    $group_dir = "";
                }
            }else{
                $group_dir = "";
            }

        }else{
            $badge_dir = "";
            $group_dir = "";
        }

        $badges_names = $badge_dir . $badges_names . $group_dir . $group_names;

        if ($action_type == 'store'){
            $store_qnty = $stage;
            $type = ucfirst( get_option( 'options_go_store_name' ) );
            $action = "Qnt: " . $store_qnty ;
        }

        if ($action_type == 'task'){
            $type = ucfirst( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                //$type = "Continue";
                $action = "Stage: " . $stage;
            }
            else{
                //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                // $type = "Continue";
                $action = "Bonus: " . $bonus_status;
            }
        }

        if ($action_type == 'undo_task'){
            $type = ucfirst( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) ) . " Undo";
                //$type = "Undo";
                $action = "Undo Stage: " . $stage;
            }
        }
        if ($result == 'undo_bonus'){
            //$type = strtoupper( get_option( 'options_go_tasks_name_singular' ) ) . " Undo Bonus";
            //$type = "Undo Bonus";
            $action = "Undo Bonus: " . $bonus_status ;
        }

        $quiz_mod_int = intval($quiz_mod);
        if (!empty($quiz_mod_int)){
            $quiz_mod = "<i class=\"fa fa-check-circle-o\" aria-hidden=\"true\"></i> ". $quiz_mod;
        }
        else{
            $quiz_mod = null;
        }

        $late_mod_int = intval($late_mod);
        if (!empty($late_mod_int)){
            $late_mod = "<i class=\"fa fa-calendar\" aria-hidden=\"true\"></i> ". $late_mod;
        }
        else{
            $late_mod = null;
        }

        $timer_mod_int = intval($timer_mod);
        if (!empty($timer_mod_int)){
            $timer_mod = "<i class=\"fa fa-hourglass\" aria-hidden=\"true\"></i> ". $timer_mod;
        }
        else{
            $timer_mod = null;
        }

        $health_mod_int = $health_mod;
        if (!empty($health_mod_int)){
            $health_abbr = get_option( "options_go_loot_health_abbreviation" );
            $health_mod_str = $health_abbr . ": ". $health_mod;
        }
        else{
            $health_mod_str = null;
        }
        //$unix_time = strtotime($TIMESTAMP);
        $row[] = "{$TIMESTAMP}";
        $row[] = "{$type}";
        $row[] = "{$post_title}";
        $row[] = "{$action}";
        $row[] = "{$health_mod_str}   {$timer_mod}   {$late_mod}   {$quiz_mod}";

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
        if ($xp_toggle){
            $row[] = "{$xp_total}";
        }
        if ($gold_toggle){
            $row[] = "{$gold_total}";
        }
        if ($health_toggle){
            $row[] = "{$health_total}";
        }
        $row[] = "{$badges_names}";
        $output['aaData'][] = $row;
    }


    echo json_encode( $output );
    die();
}

/**
 * @param $user_id
 */
function go_stats_badges_list($user_id) {
    global $wpdb;
    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    if ( ! empty( $_POST['user_id'] ) ) {
        $user_id = (int) $_POST['user_id'];
    }
    check_ajax_referer( 'go_stats_badges_list_' );
    $badges_array = $wpdb->get_var ("SELECT badges FROM {$go_loot_table_name} WHERE uid = {$user_id}");
    $badges_array = unserialize($badges_array);
    if (empty($badges_array)){
        $badges_array = array();
    }
    $args = array(
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
        'parent' => '0'
    );

    /* Get all task chains with no parents--these are the badge categories.  */
    $taxonomy = 'go_badges';
    //$badges_name = get_option('options_go_badges_name_plural');

    $rows = get_terms( $taxonomy, $args);//the rows
    echo"<div id='go_badges_list' class='go_datatables'> ";


    /* For each Store Category with no parent, get all the children. */
    $chainParentNum = 0;
    echo '<div id="go_stats_badges">';
    //for each row
    foreach ( $rows as $row ) {
        $chainParentNum++;
        $row_id = $row->term_id;//id of the row
        //$custom_fields = get_term_meta( $row_id );
        /*
        $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ?  $custom_fields['go_hide_store_cat'][0] : null);
        if( $cat_hidden == true){
            continue;
        }
        */
        $column_args=array(
            'hide_empty' => false,
            'orderby' => 'order',
            'order' => 'ASC',
            'parent' => $row_id,

        );

        $badges = get_terms($taxonomy,$column_args);

        if(empty($badges)){
            continue;
        }

        echo 	"<div class='parent_cat'>
                        <div id='row_$chainParentNum' class='badges_row_container'>
						    <h3>$row->name</h3>
						</div>
					    <div class='badges_row'>
						";//row title and row container



        /*Loop for each chain.  Prints the chain name then looks up children (quests). */
        $badge_blocks = '';
        foreach ( $badges as $badge) {
            $badge_id = $badge->term_id;
            $badge_assigned = in_array($badge_id, $badges_array);
            if ($badge_assigned){
                $badge_class = 'go_badge_earned';
            }else{
                $badge_class = 'go_badge_needed';
            }
            $badge_img_id = get_term_meta( $badge_id, 'my_image' );
            $badge_description = term_description( $badge_id );
            /*
            $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ?  $custom_fields['go_hide_store_cat'][0] : null);
            if( $cat_hidden == true){
                continue;
            }
            */

            $badge_obj = get_term( $badge_id);
            $badge_name = $badge_obj->name;
            //$badge_img_id =(isset($custom_fields['my_image'][0]) ?  $custom_fields['my_image'][0] : null);
            if (isset($badge_img_id[0])){
                $badge_img = wp_get_attachment_image($badge_img_id[0], array( 100, 100 ));
            }else{
                $badge_img = null;
            }

            //$badge_attachment = wp_get_attachment_image( $badge_img_id, array( 100, 100 ) );
            //$img_post = get_post( $badge_id );
            if ( ! empty( $badge_obj ) ) {
                echo"<div class='go_badge_wrap'>
                        <div class='go_badge_container {$badge_class}'><figure class=go_badge title='{$badge_name}'>";

                if (!empty($badge_description)){
                    echo "<span class='tooltip' ><span class='tooltiptext'>{$badge_description}</span>{$badge_img}</span>";
                }else{
                    echo "$badge_img";
                }
                echo "        
               <figcaption>{$badge_name}</figcaption>
                            </figure>
                        </div>
                       </div>";

            }
        }
        echo "</div></div>";
    }
    echo "</div></div>";
    die();

}

/**
 * @param $user_id
 */
function go_stats_groups_list($user_id) {
    global $wpdb;
    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    if ( ! empty( $_POST['user_id'] ) ) {
        $user_id = (int) $_POST['user_id'];
    } else {
        $user_id = get_current_user_id();
    }
    check_ajax_referer( 'go_stats_groups_list_' );
    $badges_array = $wpdb->get_var ("SELECT groups FROM {$go_loot_table_name} WHERE uid = {$user_id}");
    $badges_array = unserialize($badges_array);
    $args = array(
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
        'parent' => '0'
    );

    /* Get all task chains with no parents--these are the sections of the store.  */
    $taxonomy = 'user_go_groups';


    $rows = get_terms( $taxonomy, $args);//the rows
    echo"<div id='go_groups_list' class='go_datatables'>";


    /* For each Store Category with no parent, get all the children.  These are the store rows.*/
    $chainParentNum = 0 ;
    echo '<div id="go_groups">';
    //for each row
    foreach ( $rows as $row ) {
        $chainParentNum++;
        $row_id = $row->term_id;//id of the row
        //$custom_fields = get_term_meta( $row_id );
        /*
        $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ?  $custom_fields['go_hide_store_cat'][0] : null);
        if( $cat_hidden == true){
            continue;
        }
        */

        $column_args=array(
            'hide_empty' => false,
            'orderby' => 'order',
            'order' => 'ASC',
            'parent' => $row_id,

        );

        $badges = get_terms($taxonomy,$column_args);

        if(empty($badges)){
            continue;
        }

        echo 	"<div class='parent_cat'>
                            <div id='row_$chainParentNum' class='groups_row_container'>
                                <h3>$row->name</h3>
                            </div>
                            <div class='groups_row'>
                            ";//row title and row container



        /*Loop for each chain.  Prints the chain name then looks up children (quests). */
        $badge_blocks = '';
        foreach ( $badges as $badge) {
            $badge_id = $badge->term_id;
            $badge_assigned = in_array($badge_id, $badges_array);
            if ($badge_assigned){
                $badge_class = 'go_group_earned';
            }else{
                $badge_class = 'go_group_needed';
            }
            $custom_fields = get_term_meta( $badge_id );
            /*
            $cat_hidden = (isset($custom_fields['go_hide_store_cat'][0]) ?  $custom_fields['go_hide_store_cat'][0] : null);
            if( $cat_hidden == true){
                continue;
            }
            */

            $badge_obj = get_term( $badge_id);
            $badge_name = $badge_obj->name;
            $badge_img_id =(isset($custom_fields['my_image'][0]) ?  $custom_fields['my_image'][0] : null);
            $badge_img = wp_get_attachment_image($badge_img_id, array( 100, 100 ));

            //$badge_attachment = wp_get_attachment_image( $badge_img_id, array( 100, 100 ) );
            //$img_post = get_post( $badge_id );
            if ( ! empty( $badge_obj ) ) {
                echo"<div class='go_group_wrap'>
                            <div class='go_group_container {$badge_class}'>
                                    <p>{$badge_name}</p>
                            </div>
                        </div>";

            }
        }
        echo "</div></div>";
    }
    echo "</div></div>";
    die();
}


/**Leaderboard Stuff Below
 *
 */

/**
 *
 */
function go_stats_leaderboard() {

    check_ajax_referer('go_stats_leaderboard_');
    if (!empty($_POST['user_id'])) {
        $current_user_id = (int)$_POST['user_id'];
    }
    // prepares tab titles
    $xp_name = get_option("options_go_loot_xp_name");
    $gold_name = get_option("options_go_loot_gold_name");
    $health_name = get_option("options_go_loot_health_name");
    $badges_name = get_option('options_go_badges_name_singular') . " Count";



    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');

    $badges_toggle = get_option('options_go_badges_toggle');

    //is the current user an admin
    $current_user_id = get_current_user_id();
    $is_admin = go_user_is_admin($current_user_id);

    $full_name_toggle = get_option('options_go_full-names_toggle');

    ?>

    <div id="go_leaderboard_wrapper" class="go_datatables">
        <div id="go_leaderboard_filters">
            <span>Section:<?php go_make_tax_select('user_go_sections'); ?></span>
            <span>Group:<?php go_make_tax_select('user_go_groups'); ?></span>
        </div>

        <div id="go_leaderboard_flex">

            <div id="go_leaderboard" class="go_leaderboard_layer">

                <table id='go_leaders_datatable' class='pretty display'>
                    <thead>
                    <tr>
                        <th></th>
                        <?php
                        if ($full_name_toggle || $is_admin){
                            echo "<th class='header'><a href='#'>Full Name</a></th>";
                        }
                        ?>
                        <th class='header'><a href="#">Name</a></th>
                        <th class='header'><a href="#">Links</a></th>
                        <?php
                        if ($xp_toggle) {
                            echo "<th class='header'><a href='#'>" . $xp_name . "</a></th>";
                        }
                        if ($gold_toggle) {
                            echo "<th class='header'><a href='#'>" . $gold_name . "</a></th>";
                        }
                        if ($health_toggle) {
                            echo "<th class='header'><a href='#'>" . $health_name . "</a></th>";
                        }
                        if ($badges_toggle) {
                            echo "<th class='header'><a href='#'>" . $badges_name . "</a></th>";
                        }
                        ?>

                    </tr>
                    </thead>
                    <tbody></table>
            </div>

        </div>
    </div>


    <?php
    die();

}

function go_stats_uWhere_values(){
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

function go_stats_leaderboard_dataloader_ajax(){
    global $wpdb;
    $current_id = get_current_user_id();
    $is_admin = go_user_is_admin($current_id);

    //$section = go_section();
    $uWhere = go_stats_uWhere_values();
    $sLimit = '';
    if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval($_GET['start']) . ", " . intval($_GET['length']);
    }

    //$sOrder = go_sOrder('leaderboard', $section);

    $order_dir = $_GET['order'][0]['dir'];
    $order_col = $_GET['order'][0]['column'];
    if($is_admin){
        $order_col--;
    }
    if ($order_col == 3){
        $order_col = 'xp';//xp
    }
    else if ($order_col == 4){
        $order_col = 'gold';//gold
    }
    else if ($order_col == 5){
        $order_col = 'health';//health
    }
    else if ($order_col == 6){
        $order_col = 'badge_count';//badges
    }


    $sOrder = "ORDER BY " . $order_col . " " . $order_dir;

    $lTable = "{$wpdb->prefix}go_loot";
    $umTable = "{$wpdb->prefix}usermeta";
    $uTable = "{$wpdb->prefix}users";
    $sColumn = "{$wpdb->prefix}capabilities";
    $sQuery = "
          
      SELECT SQL_CALC_FOUND_ROWS 
        t5.*
      FROM (
          SELECT
              t1.*,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END)  AS section_0,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
              MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
              MAX(CASE WHEN t2.meta_key = '$sColumn' THEN meta_value END) AS capabilities
          FROM (
              SELECT
              *
              FROM $lTable
              $sOrder
              $sLimit 
          ) AS t1
          LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id  
          GROUP BY t1.id
          $uWhere
          $sOrder
      ) AS t5
    ";

    $sQuery = "
          
      SELECT SQL_CALC_FOUND_ROWS
        t5.*
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
     
     SELECT COUNT(*)
     FROM( 
      SELECT 
          MAX(CASE WHEN t2.meta_key = '$sColumn' THEN meta_value END) AS capabilities
      FROM $lTable AS t1 
          LEFT JOIN $umTable AS t2 ON t1.uid = t2.user_id
          GROUP BY t1.id
          HAVING ( capabilities NOT LIKE '%administrator%')
      ) AS t3   
      
    
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());

    $num = $_GET['start'];
    foreach($rResult as $action){//output a row for each action

        //The message content
        $row = array();
        $user_id = $action['uid'];
        $xp = $action['xp'];
        $gold = $action['gold'];
        $health = $action['health'];
        $badge_count = $action['badge_count'];
        $user_display_name = $action['display_name'];
        $user_firstname = $action['first_name'];
        $user_lastname = $action['last_name'];

        /*
        $userdata = get_userdata($user_id);
        $user_display_name = $userdata->display_name;
        $user_firstname = $userdata->user_firstname;
        $user_lastname = $userdata->user_lastname;
        */

        //set full name
        $full_name_toggle = get_option('options_go_full-names_toggle');
        if ($full_name_toggle || $is_admin){
            $user_fullname = $user_firstname.' '.$user_lastname;
        }

        $num++;

        ob_start();
        go_user_links($user_id, true, true, true, true, true, true);
        $links = ob_get_clean();

        $row[] = "{$num}";
        if ($full_name_toggle || $is_admin){
            $row[] = $user_fullname;
        }
        $row[] = "{$user_display_name}";
        $row[] = "{$links}";//user period

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
        $badges_toggle = get_option('options_go_badges_toggle');
        if ($badges_toggle) {
            $row[] = "{$badge_count}";
        }
        $output['aaData'][] = $row;
    }

    //$output['iTotalDisplayRecords'] =  count($output['aaData']);
    global $go_debug;
    if($go_debug) {
        //go_total_query_time();
    }

    echo json_encode( $output );
    die();
}

/**
 *
 */
function go_stats_lite(){

    check_ajax_referer( 'go_stats_lite' );
    if ( ! empty( $_POST['uid'] ) ) {
        $user_id = (int) $_POST['uid'];
        $current_user = get_userdata( $user_id );
    } else {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
    }


    ?>
    <input type="hidden" id="go_stats_hidden_input" value="<?php echo $user_id; ?>"/>
    <?php
    $user_fullname = $current_user->first_name.' '.$current_user->last_name;
    $user_login =  $current_user->user_login;
    $user_display_name = $current_user->display_name;
    $user_website = $current_user->user_url;

    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');
    if ($use_local_avatars){
        $user_avatar_id = get_user_option( 'go_avatar', $user_id );
        $user_avatar = wp_get_attachment_image($user_avatar_id);
    }
    if (empty($user_avatar) && $use_gravatar) {
        $user_avatar = get_avatar( $user_id, 150 );
    }

//$user_focuses = go_display_user_focuses( $user_id );



    $current_points = go_return_points( $user_id );


    $go_option_ranks = get_option( 'go_ranks' );
    $points_array = $go_option_ranks['points'];

    $max_rank_index = count( $points_array ) - 1;
    $max_rank_points = (int) $points_array[ $max_rank_index ];

    $percentage_of_level = 1;

// user pnc
    $rank = go_get_rank( $user_id );
    $current_rank = $rank['current_rank'];
    $current_rank_points = $rank['current_rank_points'];
    $next_rank = $rank['next_rank'];
    $next_rank_points = $rank['next_rank_points'];

    if ( null !== $next_rank_points ) {
        $rank_threshold_diff = ( $next_rank_points - $current_rank_points );
    } else {
        $rank_threshold_diff = 1;
    }
    $pts_to_rank_threshold = ( $current_points - $current_rank_points );

    if ( $max_rank_points === $current_rank_points ) {
        $prestige_name = get_option( 'go_prestige_name' );
        $pts_to_rank_up_str = $prestige_name;
    } else {
        $pts_to_rank_up_str = "{$pts_to_rank_threshold} / {$rank_threshold_diff}";
    }

    $percentage_of_level = ( $pts_to_rank_threshold / $rank_threshold_diff ) * 100;
    if ( $percentage_of_level <= 0 ) {
        $percentage_of_level = 0;
    } else if ( $percentage_of_level >= 100 ) {
        $percentage_of_level = 100;
    }



/////////////////////////
///
    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option( 'options_go_loot_health_toggle' );

    if ($xp_toggle) {
        // the user's current amount of experience (points)
        $go_current_xp = go_get_user_loot($user_id, 'xp');

        $rank = go_get_rank($user_id);
        $rank_num = $rank['rank_num'];
        $current_rank = $rank['current_rank'];
        $current_rank_points = $rank['current_rank_points'];
        $next_rank = $rank['next_rank'];
        $next_rank_points = $rank['next_rank_points'];

        $go_option_ranks = get_option('options_go_loot_xp_levels_name_singular');
        //$points_array = $go_option_ranks['points'];

        /*
         * Here we are referring to last element manually,
         * since we don't want to modifiy
         * the arrays with the array_pop function.
         */
        //$max_rank_index = count( $points_array ) - 1;
        //$max_rank_points = (int) $points_array[ $max_rank_index ];

        if ($next_rank_points != false) {
            $rank_threshold_diff = $next_rank_points - $current_rank_points;
            $pts_to_rank_threshold = $go_current_xp - $current_rank_points;
            $pts_to_rank_up_str = "L{$rank_num}: {$pts_to_rank_threshold} / {$rank_threshold_diff}";
            $percentage = $pts_to_rank_threshold / $rank_threshold_diff * 100;
            //$color = barColor( $go_current_health, 0 );
            $color = "#39b54a";
        } else {
            $pts_to_rank_up_str = $current_rank;
            $percentage = 100;
            $color = "gold";
        }
        if ( $percentage <= 0 ) {
            $percentage = 0;
        } else if ( $percentage >= 100 ) {
            $percentage = 100;
        }
        $progress_bar = '<div class="go_admin_bar_progress_bar_border progress-bar-border">'.'<div class="go_admin_bar_progress_bar stats_progress_bar" '.
            'style="width: '.$percentage.'%; background-color: '.$color.' ;">'.
            '</div>'.
            '<div class="points_needed_to_level_up">'.
            $pts_to_rank_up_str.
            '</div>'.
            '</div>';
    }
    else {
        $progress_bar = '';
    }


    if($health_toggle) {
        // the user's current amount of bonus currency,
        // also used for coloring the admin bar
        $go_current_health = go_get_user_loot($user_id, 'health');
        $health_percentage = intval($go_current_health / 2);
        if ($health_percentage <= 0) {
            $health_percentage = 0;
        } else if ($health_percentage >= 100) {
            $health_percentage = 100;
        }
        $health_bar = '<div class="go_admin_health_bar_border progress-bar-border">' . '<div class="go_admin_bar_health_bar stats_progress_bar" ' . 'style="width: ' . $health_percentage . '%; background-color: red ;">' . '</div>' . '<div class="health_bar_percentage_str ">' . "Health Mod: " . $go_current_health . "%" . '</div>' . '</div>';

    }
    else{
        $health_bar = '';
    }

    if ($gold_toggle) {
        // the user's current amount of currency
        $go_current_gold = go_get_user_loot($user_id, 'gold');
        $gold_total = '<div class="go_admin_bar_gold admin_bar_loot">' . go_display_shorthand_currency('gold', $go_current_gold)  . '</div>';
    }
    else{
        $gold_total = '';
    }


//////////////////



    ?>
    <div id='go_stats_lite_wrapper'>
    <div id='go_stats_lay_lite' class='go_datatables'>
        <div id='go_stats_header_lite'>
            <div class="go_stats_id_card">
                <div class='go_stats_gravatar'><?php echo $user_avatar; ?></div>

                <div class='go_stats_user_info'>
                    <?php
                    $current_user_id = get_current_user_id();
                    $is_admin = go_user_is_admin($current_user_id);
                    $full_name_toggle = get_option('options_go_full-names_toggle');
                    if ($full_name_toggle || $is_admin){
                        echo "<h2>{$user_fullname}</h2>{$user_display_name}<br>";
                    }else{
                        echo "<h2>{$user_display_name}</h2>";
                    }?>
                    <?php
                    go_user_links($user_id, true, false, true, true, true, false);
                    ?>

                </div>

            </div>
            <div class="go_stats_bars">
                <?php
                if ($xp_toggle) {
                    echo '<div class="go_stats_rank"><h3>' . $go_option_ranks . ' ' . $rank_num . ": " . $current_rank . '</h3></div>';
                }
                echo $progress_bar;
                //echo "<div id='go_stats_user_points'><span id='go_stats_user_points_value'>{$current_points}</span> {$points_name}</div><div id='go_stats_user_currency'><span id='go_stats_user_currency_value'>{$current_currency}</span> {$currency_name}</div><div id='go_stats_user_bonus_currency'><span id='go_stats_user_bonus_currency_value'>{$current_bonus_currency}</span> {$bonus_currency_name}</div>{$current_penalty} {$penalty_name}<br/>{$current_minutes} {$minutes_name}";
                echo $health_bar;
                ?>
            </div>
            <div class='go_stats_user_loot'>

                <?php

                if ($xp_toggle) {
                    echo '<div class="go_stats_xp">' . go_display_longhand_currency('xp', $go_current_xp) . '</div>';
                }
                if ($gold_toggle) {
                    echo '<div class="go_stats_gold">' . go_display_longhand_currency('gold', $go_current_gold) . '</div>';
                }
                if ($health_toggle) {
                    echo '<div class="go_stats_health">' . go_display_longhand_currency('health', $go_current_health) . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php
//////////////////////////////
/// /////////////////////////
/// Table
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";

    $tasks = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT *
        FROM {$go_task_table_name}
        WHERE uid = %d
        ORDER BY id ASC",
            $user_id
        )
    );
    echo "<div id='go_task_list_lite' class='go_datatables'><table id='go_tasks_datatable_lite' class='pretty display'>
               <thead>
                    <tr>
                        <th class='header' id='go_stats_post_name'><a href=\"#\">Title</a></th>
                        <th class='header' id='go_stats_status'><a href=\"#\">Status</a></th>
                        <th class='header' id='go_stats_bonus_status'><a href=\"#\">Bonus</a></th>
                        <th class='header' id='go_stats_links'><a href=\"#\">Activity</a></th>
                    </tr>
                    </thead>
            <tbody>
                    ";
    foreach ( $tasks as $task ) {

        $post_id = $task->post_id;
        $custom_fields = get_post_custom( $post_id );
        $post_name = get_the_title($post_id);
        $post_link = get_post_permalink($post_id);
        $status = $task->status;
        $total_stages = (isset($custom_fields['go_stages'][0]) ?  $custom_fields['go_stages'][0] : null);


        $bonus_switch = (isset($custom_fields['bonus_switch'][0]) ?  $custom_fields['bonus_switch'][0] : null);
        $bonus_status = null;
        $total_bonus_stages = null;
        if ($bonus_switch) {
            $bonus_status = $task->bonus_status;
            $total_bonus_stages = (isset($custom_fields['go_bonus_limit'][0]) ? $custom_fields['go_bonus_limit'][0] : null);
        }
        $xp = $task->xp;
        $gold = $task->gold;
        $health = $task->health;
        //$start_time = $task->start_time;
        $last_time = $task->last_time;
        $time  = date("m/d/y g:i A", strtotime($last_time));


        $go_actions_table_name = "{$wpdb->prefix}go_actions";
        $actions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT *
        FROM {$go_actions_table_name}
        WHERE source_id = %d
        ORDER BY id DESC",
                $post_id
            )
        );

        $next_bonus_stage = null;
        $i = 0;
        $links = array();
        foreach ($actions as $action){
            $i++;
            $check_type = $action->check_type;
            $result = $action->result;
            $action_time = $action->TIMESTAMP;
            $action_time = date("m/d/y g:i A", strtotime($action_time));
            $action_stage = $action->stage;
            if ($action->action_type == 'task'){
                $loop_bonus_status = $action->bonus_status; //get the bonus status if it exists
                $stage = $action->stage ; //get the stage

                if ($loop_bonus_status > 0){//it is a bonus stage
                    $last_bonus = (isset($last_bonus) ?  false : true);//if this is the first encounter set to true, after that false
                    if ($last_bonus || ($next_bonus_stage > 0 && $loop_bonus_status == $next_bonus_stage )){
                        //$links[] = go_result_link($check_type, $result, $action_stage, $action_time);
                        $next_bonus_stage = $loop_bonus_status -1;
                        $links[] = go_result_link($check_type, $result, $loop_bonus_status, $action_time, true);
                    }
                }
                else if ($next_bonus_stage <= 0 || $next_bonus_stage == null) {//if it's not a bonus and it's not the last one completed
                    $next_stage = (isset($next_stage) ?  $next_stage : $stage );
                    if ($next_stage > 0 && $stage == $next_stage){
                        $action_stage = $action->stage;
                        $links[] = go_result_link($check_type, $result, $action_stage, $action_time, false);
                        $next_stage = $stage - 1;
                    }
                }

            }


        }
        $links = array_reverse($links);
        $link_count = count($links);
        $links = $comma_separated = implode(" ", $links);


        $status_order = $status/$total_stages;
        $bonus_status_order = $bonus_status/$total_bonus_stages;
        if (!empty($total_bonus_stages)){
            $bonus_str = $bonus_status . " / " . $total_bonus_stages;
        }else{
            $bonus_str = null;
        }
        echo "
                <tr id='postID_{$post_id}'>

                    <td><a href='{$post_link}' >{$post_name}<a></td>

                    <td data-order='{$status_order}'>{$status} / {$total_stages}</td>
                    <td data-order='{$bonus_status_order}'>{$bonus_str}</td>
                    <td data-order='{$link_count}'>{$links} </td>

                </tr>
                ";


    }
    echo "</tbody>
<tfoot>
                    <tr>
                        
                        <th><a href=\"#\">Title</a></th>


                        <th><a href=\"#\">Status</a></th>
                        <th><a href=\"#\">Bonus</a></th>
                        <th><a href=\"#\">Activity</a></th>

                    </tr>
                    </tfoot>
            </table></div></div>";

    die();
}




?>