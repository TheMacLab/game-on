<?php

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
            <div id="go_leaderboard_filters" style="float: left;">

                <span> Section: <?php go_make_tax_select('user_go_sections', "Show All" , "clipboard_", $section, false); ?></span>
                <span> Group: <?php go_make_tax_select('user_go_groups', "Show All"  , "clipboard_", $group, false); ?></span>
                <span> Badges: <?php go_make_tax_select('go_badges', "Show All"  , "clipboard_", $badge, false); ?></span>

            </div>

        </div>
		<div id="records_tabs" style="clear: both; margin-left: -9999px;">
			<ul>
				<li class="clipboard_tabs" tab="clipboard"><a href="#clipboard_wrap">Stats</a></li>
                <?php
				//<li class="clipboard_tabs" tab="messages"><a href="#clipboard_messages_wrap" on>Messages</a></li>
				?>
				<li class="clipboard_tabs" tab="activity"><a href="#clipboard_activity_wrap">Activity</a></li>
			</ul>
			<div id="clipboard_wrap">
                <?php
                go_clipboard_intable();
                ?>
			</div>

			<div id="clipboard_messages_wrap">

			</div>

			<div id="clipboard_activity_wrap">
                <div id="go_timestamp_filters" style="float: right; display:none;">
                    <span><button class="go_datepicker_refresh dt-button ui-button ui-state-default ui-button-text-only buttons-collection"><span class="ui-button-text">Update <span class="dashicons dashicons-update" style="vertical-align: center;"></span></span></button></span>
                    <span> Date: <input type="text" class="datepicker" name="datepicker" value=""/></span>
                </div>
                <div id="clipboard_activity_datatable_container"></div>
			</div>
		</div>
	<?php

	}
}

function go_clipboard_intable() {
	global $wpdb;
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	//check_ajax_referer( 'go_clipboard_intable_' . get_current_user_id() );
    //$current_user_id =  $user_id = get_current_user_id();
    $go_totals_table_name = "{$wpdb->prefix}go_loot";
    $rows = $wpdb->get_results(
        "SELECT * 
			        FROM {$go_totals_table_name}"

    );

    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option('options_go_loot_health_toggle');
    $c4_toggle = get_option('options_go_loot_c4_toggle');
    $badges_toggle = get_option('options_go_badges_toggle');

    // prepares tab titles
    //$xp_name = get_option( "options_go_loot_xp_name" );
    //$gold_name = get_option( "options_go_loot_gold_name" );
    //$c4_name = get_option( "options_go_loot_c4_name" );
    $badges_name = get_option( 'options_go_badges_name_plural' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );
    $c4_abbr = get_option( "options_go_loot_c4_abbreviation" );

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
                if ($c4_toggle){
                    ?>
                    <th class='header'><?php echo "$c4_abbr"; ?></th>
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
        $is_admin = go_user_is_admin($user_id);
        if ($is_admin) {
            continue;
        }
        $user_data_key = get_userdata($user_id);
        $user_display_name = $user_data_key->display_name;
        $user_firstname = $user_data_key->user_firstname;
        $user_lastname = $user_data_key->user_lastname;

        //these are used in the filter
        $group_ids = $row->groups;
        $group_ids_array = unserialize($group_ids);
        $group_ids = json_encode($group_ids_array);

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
        $badge_ids = json_encode($badge_ids_array);


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

        //$sections = get_user_meta($user_id, "go_sections");
        $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
        if (empty($num_sections)){
            $num_sections =1;
        }
        //$sections = array();


        //$sections = json_encode($sections);
        $user_data = get_userdata($user_id);
        $user_name = $user_data->display_name;
        $xp = $row->xp;
        $gold = $row->gold;
        $health = $row->health;
        $c4 = $row->c4;


        $rank = go_get_rank ( $user_id );
        $current_rank_name = $rank['current_rank'];
        if (!empty($current_rank_name )){
            $current_rank_name = ": " . $current_rank_name;
        }
        $rank_num = $rank['rank_num'];



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
            go_user_links($user_id, false, true, true, true, true, false);
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
            if ($c4_toggle) {
                echo "<td class='user_c4'>{$c4}</td>";
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
}

function go_clipboard_intable_activity() {
    if ( ! current_user_can( 'manage_options' ) ) {
        die( -1 );
    }

    check_ajax_referer( 'go_clipboard_intable_activity_' . get_current_user_id() );
    global $wpdb;

    $date_ajax = $_POST['date'];
    $date_ajax = date("Y-m-d", strtotime($date_ajax));

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
            $is_admin = go_user_is_admin($user_id);
            if ($is_admin) {
                continue;
            }
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
                $uid = intval($action[uid]);
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
                    ///
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
                    $c4 = $action['c4'];
                    $xp_total = $action['xp_total'];
                    $gold_total = $action['gold_total'];
                    $health_total = $action['health_total'];
                    $c4_total = $action['c4_total'];
                    $action_badge_ids = $action['badges'];
                    $action_group_ids = $action['groups'];

                    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
                    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
                    $health_abbr = get_option( "options_go_loot_health_abbreviation" );
                    $c4_abbr = get_option( "options_go_loot_c4_abbreviation" );


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

                            //$badges_name = get_option('options_go_badges_name_plural');

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
                    $c4_toggle = get_option('options_go_loot_c4_toggle');

                    if ($xp_toggle) {
                        $xp = $xp_abbr . ": " . $xp;
                    }
                    if ($gold_toggle) {
                        $gold = $gold_abbr . ": " . $gold;
                    }
                    if ($health_toggle) {
                        $health = $health_abbr . ": " . $health;
                    }
                    if ($c4_toggle) {
                        $c4 = $c4_abbr . ": " . $c4;
                    }

                    //$row[] = "{$badges_names}";


                    $loot = $xp . "<br>" . $gold . "<br>" . $health  . "<br>" . $c4;
                    $loot = "<span class='tooltip' ><span class='tooltiptext'>{$loot}</span>Loot</span>";

                    $action_list[] = "<tr class='" . $class . "'><td>" . $time . "</td><td>" . $type . "</td><td>" . $post_title . "</td><td>" . $action . "</td><td>" . $loot . "</td><td>" . $badges_names . "</td></tr>";

                }
                ////////////////////////////////////////////////////////////////////////
            }
            $action_list = "<table>" . implode('', $action_list) . "</table>";

            for ($i = 0; $i < $num_sections; $i++) {
                $user_period_option = "go_section_and_seat_" . $i . "_user-section";
                $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";

                $user_period = get_user_meta($user_id, $user_period_option, true);
                $term = get_term( $user_period, "user_go_sections" );
                $user_period_name = (isset($term->name) ?  $term->name : null);

                $user_seat = get_user_meta($user_id, $user_seat_option, true);

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
                go_user_links($user_id, false, true, true, true, true, false);
                echo " </a></td>
					<td class='user_activity' style='padding: 4px;'>{$action_list} </td>
				  </tr>";


            }
        }
        ?>

        </tbody></table></div>

    <?php
    die();
}

?>
