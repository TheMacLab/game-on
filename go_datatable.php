<?php

// Creates table for indivual logs.
function go_table_individual() {
	global $wpdb;
	$table_name = "{$wpdb->prefix}go";
	$sql = "
		CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			uid INT,
			status INT,
			post_id INT,
			page_id INT,
			count INT DEFAULT 0,
			e_fail_count INT DEFAULT 0,
			a_fail_count INT DEFAULT 0,
			c_fail_count INT DEFAULT 0,
			m_fail_count INT DEFAULT 0,
			e_passed BOOLEAN DEFAULT 0,
			a_passed BOOLEAN DEFAULT 0,
			c_passed BOOLEAN DEFAULT 0,
			m_passed BOOLEAN DEFAULT 0,
			e_uploaded BOOLEAN DEFAULT 0,
			a_uploaded BOOLEAN DEFAULT 0,
			c_uploaded BOOLEAN DEFAULT 0,
			m_uploaded BOOLEAN DEFAULT 0,
			r_uploaded BOOLEAN DEFAULT 0,
			points INT,
			currency INT,
			bonus_currency INT,
			penalty INT,
			gifted BOOLEAN DEFAULT 0,
			minutes INT,
			reason VARCHAR (200),
			url VARCHAR (200),
			timestamp VARCHAR (200), 
			UNIQUE KEY  id (id)
		);
	";
	require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

// Creates a table for totals.
function go_table_totals() {
	global $wpdb;
	$table_name = "{$wpdb->prefix}go_totals";
	$sql = "
		CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			uid  INT,
			currency  INT,
			points  INT,
			bonus_currency  INT,
			penalty  INT,
			minutes INT,
			badge_count INT,
			UNIQUE KEY  id (id)
		);
	";

	require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

// Updates the rank totals upon activation of plugin.
function go_ranks_registration() {
	$ranks = get_option( 'go_ranks', false );
	if ( ! $ranks || ! in_array( 'name', array_keys( $ranks ) ) ) {
		$rank_prefix = get_option( 'go_level_names' );
		if ( empty( $rank_prefix) ) {
			$rank_prefix = 'Level';
		}
		$ranks = array(
			'name' => array(),
			'points' => array(),
			'badges' => array()
		);
		for ( $i = 1; $i <= 20; $i++ ) {
			if ( $i < 10 ) {
				$ranks['name'][] = "{$rank_prefix} 0{$i}";
			} else {
				$ranks['name'][] = "{$rank_prefix} {$i}";
			}
			if ( $i == 1 ) {
				$ranks['points'][0] = 0;
			} else {
				$ranks['points'][] = (15/2) * ( $i + 18) * ( $i - 1);
			}
			$ranks['badges'][] = '';
		}
		update_option( 'go_ranks', $ranks );
	}
}

// Updates the presets for task creation upon activation of plugin. 
function go_presets_registration() {
	$presets = get_option( 'go_presets' );
	if ( ! $presets || ! in_array( 'name', array_keys( $presets ) ) ) {
		$presets = array(
			'name' => array(
				'Tier 1',
				'Tier 2',
				'Tier 3',
				'Tier 4',
				'Tier 5',
			),
			'points' => array(
				array(
					5, 5, 10, 30, 30
				),
				array(
					5, 5, 20, 60, 60
				),
				array(
					5, 5, 40, 120, 120
				),
				array(
					5, 5, 70, 210, 210
				),
				array(
					5, 5, 110, 330, 330
				)
			),
			'currency' => array(
				array(
					0, 0, 3, 9, 9
				),
				array(
					0, 0, 6, 18, 18
				),
				array(
					0, 0, 12, 36, 36
				),
				array(
					0, 0, 21, 63, 63
				),
				array(
					0, 0, 33, 99, 99
				)
			)
		);
		update_option( 'go_presets', $presets );
	}
}

function go_install_data () {
	global $wpdb;
	$table_name_user_meta = "{$wpdb->prefix}usermeta";
	$table_name_go_totals = "{$wpdb->prefix}go_totals";
	$table_name_go = "{$wpdb->prefix}go";
	global $default_role;
	$role = get_option( 'go_role', $default_role );
	$rank_prefix = get_option( 'go_level_names' );
	if ( empty( $rank_prefix ) ) {
		$rank_prefix = 'Level';
	}
	$ranks = array(
		'name' => array(),
		'points' => array(),
		'badges' => array()
	);
	for ( $i = 1; $i <= 20; $i++ ) {
		if ( $i < 10 ) {
			$ranks['name'][] = "{$rank_prefix} 0{$i}";
		} else {
			$ranks['name'][] = "{$rank_prefix} {$i}";
		}
		if ( $i == 1 ) {
			$ranks['points'][0] = 0;
		} else {
			$ranks['points'][] = (15/2) * ( $i + 18) * ( $i - 1);
		}
		$ranks['badges'][] = '';
	}
	$tier_presets = array(
		'name' => array(
			'Tier 1',
			'Tier 2',
			'Tier 3',
			'Tier 4',
			'Tier 5'
		), 
		'points' => array(
			array( 5, 5, 10, 30, 30 ), 
			array( 5, 5, 20, 60, 60 ),
			array( 5, 5, 40, 120, 120 ),
			array( 5, 5, 70, 210, 210 ),
			array( 5, 5, 110, 330, 330 )
		),
		'currency' => array(
			array( 0, 0, 3, 9, 9 ),
			array( 0, 0, 6, 18, 18 ),
			array( 0, 0, 12, 36, 36 ),
			array( 0, 0, 21, 63, 63 ),
			array( 0, 0, 33, 99, 99 )
		)
	);
	$period_defaults = array(
		'Period 1', 
		'Period 2', 
		'Period 3', 
		'Period 4', 
		'Period 5', 
		'Period 6', 
		'Period 7'
	);
	$computer_defaults = array(
		'Computer 01', 
		'Computer 02', 
		'Computer 03', 
		'Computer 04', 
		'Computer 05', 
		'Computer 06', 
		'Computer 07', 
		'Computer 08', 
		'Computer 09', 
		'Computer 10', 
		'Computer 11', 
		'Computer 12', 
		'Computer 13', 
		'Computer 14', 
		'Computer 15', 
		'Computer 16', 
		'Computer 17', 
		'Computer 18', 
		'Computer 19', 
		'Computer 20', 
		'Computer 21', 
		'Computer 22', 
		'Computer 23', 
		'Computer 24', 
		'Computer 25', 
		'Computer 26', 
		'Computer 27', 
		'Computer 28', 
		'Computer 29', 
		'Computer 30', 
		'Computer 31', 
		'Computer 32', 
		'Computer 33', 
		'Computer 34', 
		'Computer 35', 
		'Computer 36', 
		'Computer 37', 
		'Computer 38', 
		'Computer 39', 
		'Computer 40', 
		'Computer 41', 
		'Computer 42', 
		'Computer 43', 
		'Computer 44'
	);
	$options_array = array(
		'go_tasks_name' => 'Quest',
		'go_tasks_plural_name' => 'Quests' ,
		'go_first_stage_name' => 'Stage 1',
		'go_second_stage_name' => 'Stage 2',
		'go_third_stage_name' => 'Stage 3',
		'go_fourth_stage_name' => 'Stage 4',
		'go_fifth_stage_name' => 'Stage 5',
		'go_abandon_stage_button' => 'Abandon',
		'go_second_stage_button' => 'Accept',
		'go_third_stage_button' => 'Complete',
		'go_fourth_stage_button' => 'Master',
		'go_fifth_stage_button' => 'Repeat Mastery',
		'go_store_name' => 'Store',
		'go_bonus_loot_name' => 'Bonus Loot',
		'go_points_name' => 'Experience',
		'go_points_prefix' => '',
		'go_points_suffix' => 'XP',
		'go_currency_name' => 'Gold',
		'go_currency_prefix' => '',
		'go_currency_suffix' => 'G',
		'go_bonus_currency_name' => 'Honor',
		'go_bonus_currency_prefix' => '',
		'go_bonus_currency_suffix' => 'HP',
		'go_penalty_name' => 'Damage',
		'go_penalty_prefix' => '',
		'go_penalty_suffix' => 'DP',
		'go_minutes_name' => 'Minutes',
		'go_minutes_prefix' => '',
		'go_minutes_suffix' => 'M',
		'go_level_names' => 'Level',
		'go_level_plural_names' => 'Levels',
		'go_prestige_name' => 'Prestige',
		'go_organization_name' => 'Seating Chart',
		'go_class_a_name' => 'Period',
		'go_class_b_name' => 'Computer',
		'go_focus_name' => 'Profession',
		'go_stats_name' => 'Stats',
		'go_inventory_name' => 'Inventory',
		'go_badges_name' => 'Badges',
		'go_leaderboard_name' => 'Leaderboard',
		'go_presets' => $tier_presets,
		'go_admin_bar_display_switch' => 'On',
		'go_admin_bar_user_redirect' => 'On',
		'go_admin_bar_add_switch' => '',
		'go_ranks' => $ranks,
		'go_class_a' => $period_defaults,
		'go_class_b' => $computer_defaults,
		'go_focus_switch' => '',
		'go_focus' => array( '' ),
		'go_admin_email' => '',
		'go_video_width' => '',
		'go_video_height' => '',
		'go_email_from' => 'no-reply@go.net',
		'go_store_receipt_switch' => '',
		'go_full_student_name_switch' => '',
		'go_multiplier_switch' => '',
		'go_multiplier_threshold' => 10,
		'go_penalty_switch' => '',
		'go_penalty_threshold' => 5,
		'go_multiplier_percentage' => 10,
		'go_data_reset_switch' => '',
	);
	foreach ( $options_array as $key => $value ) {
		add_option( $key, $value );
	}
	$user_id_array = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT user_id
			FROM {$table_name_user_meta}
			WHERE meta_key = %s AND ( meta_value LIKE %s OR meta_value LIKE %s )",
			"{$wpdb->prefix}capabilities",
			"%{$role}%",
			'%administrator%'
		)
	);

	for ( $index = 0; $index < count( $user_id_array ); $index++ ) {
		$user_id = (int) $user_id_array[ $index ]->user_id;
		$stored_points = 0;
		$total_points = 0;
		$total_currency = 0;
		$bonus_currency = 0;
		$penalty = 0;
		$minutes = 0;
		$status = -1;

		$user_has_progress = (bool) $wpdb->get_var(
			$wpdb->prepare( "SELECT uid FROM {$table_name_go} WHERE uid = %d", $user_id )
		);
		if ( $user_has_progress ) {
			$stored_points = (int) $wpdb->get_var(
				$wpdb->prepare( "SELECT sum( points ) FROM {$table_name_go} WHERE uid = %d", $user_id )
			);
			$total_points = ( $stored_points >= 0 ? $stored_points : 0 );
			$total_currency = (int) $wpdb->get_var(
				$wpdb->prepare( "SELECT sum( currency ) FROM {$table_name_go} WHERE uid = %d", $user_id )
			);
		}
		$user_has_totals = (bool) $wpdb->get_var(
			$wpdb->prepare( "SELECT uid FROM {$table_name_go_totals} WHERE uid = %d", $user_id )
		);
		if ( $user_has_totals && $total_points > 0 ) {
			$wpdb->update(
				$table_name_go_totals,
				array(
					'points' => $total_points,
					'currency' => $total_currency
				),
				array(
					'uid' => $user_id
				),
				array( '%d' )
			);
		} else if ( ! $user_has_totals ) {
			$wpdb->insert(
				$table_name_go_totals,
				array(
					'uid' => $user_id,
					'points' => $total_points,
					'currency' => $total_currency
				),
				array( '%d' )
			);
		}

		go_update_ranks( $user_id, $total_points );
	}
}

// Adds user id to the totals table upon user creation.
function go_user_registration ( $user_id ) {
	global $wpdb;
	$table_name_go_totals = "{$wpdb->prefix}go_totals";
	$table_name_capabilities = "{$wpdb->prefix}capabilities";
	$role = get_option( 'go_role', 'subscriber' );
	$user_role = get_user_meta( $user_id, "{$table_name_capabilities}", true );
	if ( array_search( 1, $user_role ) == $role || array_search( 1, $user_role ) == 'administrator' ) {

		// this should update the user's rank metadata
		go_update_ranks( $user_id, 0 );

		// this should set the user's points to 0
		$wpdb->insert( $table_name_go_totals, array( 'uid' => $user_id, 'points' => 0 ), array( '%s' ) );
	}
}	

// Deletes all rows related to a user in the individual and total tables upon deleting said user.
function go_user_delete( $user_id ) {
 	global $wpdb;
	$table_name_go_totals = "{$wpdb->prefix}go_totals";
	$table_name_go = "{$wpdb->prefix}go";

	$wpdb->delete( $table_name_go_totals, array( 'uid' => $user_id ) );
	$wpdb->delete( $table_name_go, array( 'uid' => $user_id ) );
}

function go_open_comments() {
	global $wpdb;
	$wpdb->update( $wpdb->posts, array( 'comment_status' => 'open', 'ping_status' => 'open' ), array( 'post_type' => 'tasks' ) );	
}

?>