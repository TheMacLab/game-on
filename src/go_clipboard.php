<?php

/*
function go_clipboard() {
	add_submenu_page( 'game-on-options.php', 'Clipboard', 'Clipboard', 'manage_options', 'go_clipboard', 'go_clipboard_menu' );
}
*/

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

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} else {
        $user_id = get_current_user_id();
	    $section = get_user_meta( $user_id, 'go_clipboard_section' );
        $badge = get_user_meta( $user_id, 'go_clipboard_badge');
        $group = get_user_meta( $user_id, 'go_clipboard_group');

	?><div id="go_leaderboard_filters">

        <span>Section:<?php go_make_tax_select('user_go_sections', "Show All" , "clipboard_", $section); ?></span>
        <span>Group:<?php go_make_tax_select('user_go_groups', "Show All"  , "clipboard_", $group); ?></span>
        <span>Badges:<?php go_make_tax_select('go_badges', "Show All"  , "clipboard_", $badge); ?></span>
        </div>
		<div id="records_tabs">
			<ul>
				<li class="clipboard_tabs" tab="clipboard"><a href="#clipboard_wrap">Stats</a></li>
				<li class="clipboard_tabs" tab="messages"><a href="#clipboard_messages_wrap" on>Messages</a></li>
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


    $current_user_id =  $user_id = get_current_user_id();
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
    $xp_name = get_option( "options_go_loot_xp_name" );
    $gold_name = get_option( "options_go_loot_gold_name" );
    $c4_name = get_option( "options_go_loot_c4_name" );
    $badges_name = get_option( 'options_go_badges_name_plural' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );
    $c4_abbr = get_option( "options_go_loot_c4_abbreviation" );

    $seats_name = get_option( 'options_go_seats_name' );




    echo '<div id="go_clipboard_wrapper" class="go_clipboard">';
    ?>
        <table id='go_clipboard_datatable' class='pretty display'>
            <thead>
            <tr>
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
                $term = get_term( $group_id );
                $name = $term->name;
                $group_list[] = $name ;
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
                $term = get_term( $badge_id );
                $name = $term->name;
                $badge_list[] = $name ;
            }
            $badge_list = implode(",<br>", $badge_list);
            $badge_count = "<span class='tooltip' target='_blank'><span class='tooltiptext'>$badge_list</span>{$badge_count}</span>";
        }
        else{
            $badge_count = null;
        }




        //$sections = get_user_meta($user_id, "go_sections");
        $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
        $sections = array();


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
					<td><input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/></td>
					<td >{$user_period} </a></td>
					<td>{$group_ids}</a></td>
					<td>{$badge_ids}</a></td>
					<td >{$user_period_name} </a></td>
					<td>{$user_seat}</td>
					<td>{$user_firstname}</td>
					
					<td>{$user_lastname}</td>
					<td>{$user_display_name}</td>
					<td>";
            go_user_links($user_id, false, true, true, true, true);
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

function go_clipboard_intable_messages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_clipboard_intable_messages_' . get_current_user_id() );
    global $wpdb;

    //check_ajax_referer( 'go_clipboard_intable_' . get_current_user_id() );


    $current_user_id =  $user_id = get_current_user_id();
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
    $xp_name = get_option( "options_go_loot_xp_name" );
    $gold_name = get_option( "options_go_loot_gold_name" );
    $c4_name = get_option( "options_go_loot_c4_name" );
    $badges_name = get_option( 'options_go_badges_name_plural' );

    $xp_abbr = get_option( "options_go_loot_xp_abbreviation" );
    $gold_abbr = get_option( "options_go_loot_gold_abbreviation" );
    $health_abbr = get_option( "options_go_loot_health_abbreviation" );
    $c4_abbr = get_option( "options_go_loot_c4_abbreviation" );

    $seats_name = get_option( 'options_go_seats_name' );




    echo '<div id="go_clipboard_messages_wrapper" class="go_clipboard">';
    ?>
    <table id='go_clipboard_messages_datatable' class='pretty display'>
        <thead>
        <tr>
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
            <th class='header'>Messages</th>


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


            $group_ids = $row->groups;
            $group_ids = unserialize($group_ids);
            $group_ids = json_encode($group_ids);

            $badge_ids = $row->badges;
            $badge_ids = unserialize($badge_ids);
            $badge_ids = json_encode($badge_ids);

            //$sections = get_user_meta($user_id, "go_sections");
            $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
            $sections = array();


            //$sections = json_encode($sections);
            $user_data = get_userdata($user_id);
            $user_name = $user_data->display_name;
            $xp = $row->xp;
            $gold = $row->gold;
            $health = $row->health;
            $c4 = $row->c4;
            $badge_count = $row->badge_count;

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
					<td><input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/></td>
					<td >{$user_period} </a></td>
					<td>{$group_ids}</a></td>
					<td>{$badge_ids}</a></td>
					<td >{$user_period_name} </a></td>
					<td>{$user_seat}</td>
					<td>{$user_firstname}</td>
					
					<td>{$user_lastname}</td>
					<td>{$user_display_name}</td>
					<td>";
                go_user_links($user_id, false, true, true, true, true);
                echo " </a></td>	
					<td class='user_messages'>Coming Soon!</td>
				  </tr>";


            }
        }
        ?>

        </tbody></table></div>

    <?php
    die();
}

function go_clipboard_intable_activity() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	check_ajax_referer( 'go_clipboard_intable_activity_' . get_current_user_id() );
    global $wpdb;

    $go_totals_table_name = "{$wpdb->prefix}go_loot";
    $rows = $wpdb->get_results(
        "SELECT * 
			        FROM {$go_totals_table_name}"

    );

    $seats_name = get_option( 'options_go_seats_name' );

    echo '<div id="go_clipboard_activity_wrapper" class="go_clipboard">';
    ?>
    <table id='go_clipboard_activity_datatable' class='pretty display'>
        <thead>
        <tr>
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
            <th class='header'>Activity</th>


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


            $group_ids = $row->groups;
            $group_ids = unserialize($group_ids);
            $group_ids = json_encode($group_ids);

            $badge_ids = $row->badges;
            $badge_ids = unserialize($badge_ids);
            $badge_ids = json_encode($badge_ids);


            $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);


            for ($i = 0; $i < $num_sections; $i++) {
                $user_period_option = "go_section_and_seat_" . $i . "_user-section";
                $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";


                $user_period = get_user_meta($user_id, $user_period_option, true);
                $term = get_term( $user_period, "user_go_sections" );
                $user_period_name = (isset($term->name) ?  $term->name : null);

                $user_seat = get_user_meta($user_id, $user_seat_option, true);

                echo "<tr>
					<td><input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/></td>
					<td >{$user_period} </a></td>
					<td>{$group_ids}</a></td>
					<td>{$badge_ids}</a></td>
					<td >{$user_period_name} </a></td>
					<td>{$user_seat}</td>
					<td>{$user_firstname}</td>
					
					<td>{$user_lastname}</td>
					<td>{$user_display_name}</td>
					<td>";
                go_user_links($user_id, false, true, true, true, true);
                echo " </a></td>	
					<td class='user_activity'>Coming Soon!</td>
				  </tr>";


            }
        }
        ?>

        </tbody></table></div>

    <?php
    die();
}

 
function go_clipboard_add() {

	// the third param in the `check_ajax_referer()` call prevents the function from dying with 
	// a "-1" response
	$referer_passed = false;
	if ( current_user_can( 'manage_options' ) && check_ajax_referer( 'go_clipboard_add_' . get_current_user_id(), false, false ) ) {
		$referer_passed = true;
	}

	$status = 6;
	$bonus_loot_default = null;
	$undo_default = false;
	$show_notification = false;
	$output_data = array(
		"update_status" => false
	);

	if ( $referer_passed ) {
		$user_id_array = (array) $_POST['ids'];
		$points = (int) $_POST['points'];
		$currency = (int) $_POST['currency'];
		$bonus_currency = (int) $_POST['bonus_currency'];
		$penalty = (int) $_POST['penalty'];
		$minutes = (int) $_POST['minutes'];
		$reason = sanitize_text_field( $_POST['reason'] );
		$badge_id = (int) $_POST['badge_ID'];
	
		foreach ( $user_id_array as $key => $user_id ) {
			$user_id = (int) $user_id;
	
			if ( ! empty( $badge_id ) ) {
				if ( $badge_id > 0 ) {
	
					// the badge id is positive, so award it to the user if they don't have it
					go_award_badge(
						array(
							'id' 		=> $badge_id,
							'repeat' 	=> false,
							'uid' 		=> $user_id
						)
					);
				} else if ( $badge_id < 0 ) {
	
					// the badge id is negative, so remove that badge from the user
					$badge_id *= -1;
					go_remove_badge( $user_id, $badge_id );
				}
			}
			go_update_totals(
				$user_id,
				$points,
				$currency,
				$bonus_currency,
				$penalty,
				$minutes,
				$status,
				$bonus_loot_default,
				$undo_default,
				$show_notification
			);
			go_message_user( $user_id, $reason );
	
			// returning information to the AJAX call to update the clipboard
			$new_point_total = go_return_points( $user_id );
			$new_currency_total = go_return_currency( $user_id );
			$new_bonus_currency_total = go_return_health( $user_id );
			//$new_penalty_total = go_return_penalty( $user_id );
			$new_minute_total = go_return_c4( $user_id );
			$new_badge_count = go_return_badge_count( $user_id );
	
			if ( ! $output_data[ 'update_status' ] ) {
				$output_data[ 'update_status' ] = true;
			}
			$output_data[ $user_id ] = array(
				"points" => $new_point_total,
				"currency" => $new_currency_total,
				"bonus_currency" => $new_bonus_currency_total,
				"penalty" => $new_penalty_total,
				"minutes" => $new_minute_total,
				"badge_count" => $new_badge_count
			);
		}
	}
	wp_die( json_encode( $output_data ) );
}

function go_update_user_focuses() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_update_user_focuses_' . get_current_user_id() );

	$new_user_focus = sanitize_text_field( stripslashes( $_POST['new_user_focus'] ) );
	$user_id = (int) $_POST['user_id'];
	if ( $new_user_focus != 'No '.get_option( 'go_focus_name' ) ) {
		update_user_meta( $user_id, 'go_focus', array( $new_user_focus ) );
	} else {
		update_user_meta( $user_id, 'go_focus', array() );
	}
	echo $new_user_focus;
	die();	
}

function go_fix_messages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_fix_messages_' . get_current_user_id() );

	$users = get_users(array( 'role' => 'Subscriber' ) );
	foreach ( $users as $user ) {
		$messages = get_user_meta( $user->ID, 'go_admin_messages',true );
		$messages_array = $messages[1];
		$messages_unread = array_values( $messages_array );
		$messages_unread_count = 0;
		foreach ( $messages_unread as $message_unread ) {
			if ( $message_unread[1] == 1) {
				$messages_unread_count++;	
			}
		}
		if ( $messages[0] != $message_unread_count ) {
			$messages[0] = $messages_unread_count;
			update_user_meta( $user->ID, 'go_admin_messages', $messages );
		}
	}
	
	die();
}
?>
