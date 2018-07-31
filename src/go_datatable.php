<?php
//https://codex.wordpress.org/Creating_Tables_with_Plugins

global $wpdb;
global $go_db_version;
$go_db_version = '4.07';


function go_update_db_check() {
    global $go_db_version;
    if ( get_site_option( 'go_db_version' ) != $go_db_version ) {
    update_option('go_db_version', $go_db_version);
    go_update_db();
    //go_on_activate_msdb(true);
    }
}
add_action( 'plugins_loaded', 'go_update_db_check' );

function go_update_db() {

    global $go_db_version;
    go_table_totals();
    go_table_tasks();
    //go_table_store();
    go_table_actions();
    go_install_data();
    add_option( 'go_db_version', $go_db_version );
}

function go_table_tasks() {
    global $wpdb;
    $table_name = "{$wpdb->prefix}go_tasks";
    $sql = "
		CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20),
			post_id bigint(20),
			status TINYINT,
			bonus_status TINYINT DEFAULT 0,
			xp INT,
			gold INT,
			health INT,
			c4 INT,
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			start_time datetime,
			last_time datetime,
			PRIMARY KEY  (id),
            KEY uid (uid),
            KEY post_id (post_id),
            KEY last_time (last_time),
            KEY uid_post (uid, post_id)        
		);
	";
    require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    //add_option( 'go_db_version', $go_db_version );

}

/*
function go_table_store() {
    global $wpdb;
    $table_name = "{$wpdb->prefix}go_store";
    $sql = "
		CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20),
			post_id bigint(20),
			returned BOOLEAN DEFAULT 0,
			xp INT,
			gold INT,
			health DECIMAL (10,2),
			c4 INT,
			last_time datetime,
			PRIMARY KEY  (id),
            KEY uid (uid),
            KEY post_id (post_id),
            KEY last_time (last_time)
		);
	";
    require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    //add_option( 'go_db_version', $go_db_version );

}
*/
function go_table_actions() {
    global $wpdb;
    $table_name = "{$wpdb->prefix}go_actions";
    $sql = "
		CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20),
			action_type VARCHAR (200),
			source_id bigint(20),
			TIMESTAMP datetime,
			stage TINYINT,
			bonus_status TINYINT,
			check_type VARCHAR (200),
			result VARCHAR (200),
			quiz_mod DECIMAL (10,2),
			late_mod DECIMAL (10,2),
			timer_mod DECIMAL (10,2),
			global_mod DECIMAL (10,2),
			xp INT,
			gold INT,
			health INT,
			c4 INT,
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			xp_total INT,
			gold_total INT,
			health_total INT,
			c4_total INT,
			PRIMARY KEY  (id),
            KEY uid (uid),
            KEY source_id (source_id),
            KEY TIMESTAMP (TIMESTAMP),
            KEY uid_source (uid, source_id),
            KEY uid_date (uid, TIMESTAMP)
            
		);
	";
    require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );



}

function go_table_totals() {
    global $wpdb;
    global $go_db_version;
    $table_name = "{$wpdb->prefix}go_loot";
    $sql = "
		CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20),
			xp INT DEFAULT 0,
			gold INT DEFAULT 0,
			health DECIMAL (10,2) DEFAULT 100,
			c4 INT DEFAULT 0,
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			badge_count INT DEFAULT 0,
			PRIMARY KEY  (id),
            KEY uid (uid),
            UNIQUE (uid)          
		);
	";

    //an early beta had no default on the loot table for badge count
    //this fixes that
    $wpdb->query(
        "UPDATE {$table_name} 
                    SET 
                        badge_count = IFNULL(badge_count, 0);");


    require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    //add_option( 'go_db_version', $go_db_version );

}

/**
 * MULTISITE FUNCTIONS
 *
 */


/**
 * Activate for existing sites on plugin activation
 * @param $network_wide
 */
function go_on_activate_msdb( $network_wide ) {
    global $wpdb;
    if ( is_multisite() && $network_wide ) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            go_update_db();
            restore_current_blog();
        }
    } else {
        go_update_db_check();
    }
}


/**
 * Creating table whenever a new blog is created
 * @param $blog_id
 * @param $user_id
 * @param $domain
 * @param $path
 * @param $site_id
 * @param $meta
 */
function go_on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( is_plugin_active_for_network(__FILE__ ) ) {
    switch_to_blog( $blog_id );
    go_update_db();
    restore_current_blog();
    }
}
add_action( 'wpmu_new_blog', 'go_on_create_blog', 10, 6 );

/**
 * // Deleting the table whenever a blog is deleted
 * @param $tables
 * @return array
 */
function go_on_delete_blog( $tables ) {
    global $wpdb;
    $tables[] = $wpdb->prefix . 'go_tasks';
    $tables[] = $wpdb->prefix . 'go_actions';
    $tables[] = $wpdb->prefix . 'go_store';
    $tables[] = $wpdb->prefix . 'go_totals';

    return $tables;
}
add_filter( 'wpmu_drop_tables', 'go_on_delete_blog' );

// Adds user id to the totals table upon user creation.
function go_user_registration ( $user_id ) {
    global $wpdb;
    $table_name_go_totals = "{$wpdb->prefix}go_loot";
    $table_name_capabilities = "{$wpdb->prefix}capabilities";
    $role = get_option( 'go_role', 'subscriber' );
    $user_role = get_user_meta( $user_id, "{$table_name_capabilities}", true );
    if ( array_search( 1, $user_role ) == $role || array_search( 1, $user_role ) == 'administrator' ) {

        // this should update the user's rank metadata
        go_update_ranks( $user_id, 0 );

        // this should set the user's points to 0
        $wpdb->insert( $table_name_go_totals, array( 'uid' => $user_id), array( '%s' ) );
    }
}

// Deletes all rows related to a user in the individual and total tables upon deleting said user.
function go_user_delete( $user_id ) {
    global $wpdb;
    $table_name_go_totals = "{$wpdb->prefix}go_loot";
    $table_name_go_tasks = "{$wpdb->prefix}go_tasks";
    $table_name_go_actions = "{$wpdb->prefix}go_actions";
    //$table_name_go_store = "{$wpdb->prefix}go_store";

    $wpdb->delete( $table_name_go_totals, array( 'uid' => $user_id ) );
    $wpdb->delete( $table_name_go_tasks, array( 'uid' => $user_id ) );
    $wpdb->delete( $table_name_go_actions, array( 'uid' => $user_id ) );
    //$wpdb->delete( $table_name_store, array( 'uid' => $user_id ) );
}

function go_open_comments() {
    global $wpdb;
    $wpdb->update( $wpdb->posts, array( 'comment_status' => 'open', 'ping_status' => 'open' ), array( 'post_type' => 'tasks' ) );
}

function go_install_data ($reset = false) {
    global $wpdb;
    $table_name_user_meta = "{$wpdb->prefix}usermeta";
    $table_name_go_totals = "{$wpdb->prefix}go_loot";
    $table_name_go = "{$wpdb->prefix}go_tasks";
    $table_name_users = "{$wpdb->prefix}users";

    $options_array = array(
        'options_go_tasks_name_singular' => 'Quest',
        'options_go_tasks_name_plural' => 'Quests',
        'options_go_tasks_stage_name_singular' => 'Stage',
        'options_go_tasks_stage_name_plural' => 'Stages',
        'options_go_tasks_optional_task' => 'Bonus',
        'options_go_tasks_bonus_stage' => 'Bonus Stage',
        'options_go_store_toggle' => 1,
        'options_go_store_name' => 'Store',
        'options_go_store_store_link' => 'store',
        'options_go_store_store_receipts' => 0,
        'options_go_badges_toggle' => 1,
        'options_go_badges_name_singular' => 'Badge',
        'options_go_badges_name_plural' => 'Badges',
        'options_go_stats_toggle' => 1,
        'options_go_stats_name' => 'Stats',
        'options_go_stats_leaderboard_toggle' => 1,
        'options_go_stats_leaderboard_name' => 'Leaderboard',
        'options_go_locations_map_toggle' => 1,
        'options_go_locations_map_title' => 'Map',
        'options_go_locations_map_map_link' => 'map',
        'options_go_locations_widget_toggle' => 1,
        'options_go_locations_widget_name' => 'Game Categories',
        'options_go_locations_top_menu_toggle' => 1,
        'options_go_loot_name' => 'Loot',
        'options_go_loot_xp_toggle' => 1,
        'options_go_loot_gold_toggle' => 1,
        'options_go_loot_health_toggle' => 1,
        'options_go_loot_c4_toggle' => 0,

        'options_go_loot_xp_name' => 'Experience Points',
        'options_go_loot_gold_name' => 'Gold',
        'options_go_loot_health_name' => 'Health',
        'options_go_loot_c4_name' => 'Awesome Points',

        'options_go_loot_xp_abbreviation' => 'XP',
        'options_go_loot_gold_abbreviation' => 'G',
        'options_go_loot_health_abbreviation' => 'HP',
        'options_go_loot_c4_abbreviation' => 'AP',

        'options_go_loot_xp_mods_toggle' => 0,
        'options_go_loot_gold_mods_toggle' => 1,
        'options_go_loot_health_mods_toggle' => 0,
        'options_go_loot_c4_mods_toggle' => 0,

        'options_go_loot_xp_levels_name_singular' => 'Level',
        'options_go_loot_xp_levels_name_plural' => 'Levels',
        'options_go_loot_xp_levels_growth' => '1.5',

        'options_go_loot_bonus_loot_toggle' => 1,
        'options_go_loot_bonus_loot_name' => 'Bonus Loot',
        'options_go_loot_bonus_loot_mods_toggle' => 1,

        'options_go_seat_name' => 'Seat',
        'options_go_seat_number' => '40',

        'options_go_video_width_unit' => 'px',
        'options_go_video_width_pixels' => '500',
        'options_go_video_lightbox' => '1',

        'options_go_images_resize_toggle' => 1,
        'options_go_images_resize_longest_side' => '1920',

        'options_go_guest_global' => 'regular',
        'options_go_full-names_toggle' => 0,
        'options_go_search_toggle' => 0,

        'options_go_dashboard_toggle' => 0,
        'options_go_admin_bar_toggle' => 1,

        'options_go_slugs_toggle' => 1,

        'options_go_avatars_local' => 1,
        'options_go_avatars_gravatars' => 1,
    );
    foreach ( $options_array as $key => $value ) {
        add_option( $key, $value );
    }
    if($reset){
        update_option( $key, $value );
    }


    //For Repeater Fields Sections
    $isset = get_option('options_go_sections'); //if there are no sections at all
    if ($isset == false){
        add_option('options_go_sections', 7);
        add_option('options_go_sections_0_section', 'Period 1');
        add_option('options_go_sections_1_section', 'Period 2');
        add_option('options_go_sections_2_section', 'Period 3');
        add_option('options_go_sections_3_section', 'Period 4');
        add_option('options_go_sections_4_section', 'Period 5');
        add_option('options_go_sections_5_section', 'Period 6');
        add_option('options_go_sections_6_section', 'Period 7');

    }

    //For Levels
    $isset = get_option('options_go_loot_xp_levels_level'); //if there are no level at all
    if ($isset == false){
        add_option('options_go_loot_xp_levels_level', 15);
        add_option('options_go_loot_xp_levels_level_0_xp', 0);
        add_option('options_go_loot_xp_levels_level_1_xp', 100);
        add_option('options_go_loot_xp_levels_level_2_xp', 150);
        add_option('options_go_loot_xp_levels_level_3_xp', 225);
        add_option('options_go_loot_xp_levels_level_4_xp', 337);
        add_option('options_go_loot_xp_levels_level_5_xp', 505);
        add_option('options_go_loot_xp_levels_level_6_xp', 757);
        add_option('options_go_loot_xp_levels_level_7_xp', 1135);
        add_option('options_go_loot_xp_levels_level_8_xp', 1702);
        add_option('options_go_loot_xp_levels_level_9_xp', 2553);
        add_option('options_go_loot_xp_levels_level_10_xp', 3829);
        add_option('options_go_loot_xp_levels_level_11_xp', 5743);
        add_option('options_go_loot_xp_levels_level_12_xp', 8614);
        add_option('options_go_loot_xp_levels_level_13_xp', 12921);
        add_option('options_go_loot_xp_levels_level_14_xp', 19381);
        add_option('options_go_loot_xp_levels_level_14_name', 'Guru');

    }

/*
    $blogusers = get_users( array( 'fields' => array( 'ID' ) ) );
    $blogusers = json_decode(json_encode($blogusers), True); //converts std_class to array

    foreach ($blogusers as $key => $value) {
        foreach ($value as $key2 => $value2) {
            $result_blogusers[] = $value2;
        };
    }
    //$blogusers = array_values($blogusers);

    $user_ids = $wpdb->get_results( "SELECT uid FROM {$table_name_go_totals}" );
    $user_ids = json_decode(json_encode($user_ids), True); //converts std_class to array
    foreach ($user_ids as $key => $value) {
        foreach ($value as $key2 => $value2) {
            $result_userids[] = $value2;
        };
    }

    $diff_ids = array_diff($result_blogusers, $result_userids);

    foreach ($diff_ids as $uid) {
        go_add_user_to_totals_table($uid);
    }
*/
        /*
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
            */
}



//OLD Tables
// Creates table for indivual logs.
/*
 *
 function go_table_individual() {
	global $wpdb;
	$table_name = "{$wpdb->prefix}go";
	$sql = "
		CREATE TABLE $table_name (
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
			url VARCHAR (1000),
			starttime VARCHAR (200),
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
		CREATE TABLE $table_name (
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

*/

/*
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
*/
/*
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
*/
/*
function go_install_data () {
    global $wpdb;
    $table_name_user_meta = "{$wpdb->prefix}usermeta";
    $table_name_go_totals = "{$wpdb->prefix}go_loot";
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
        'go_bonus_task' => 'Bonus',
        'go_bonus_stage' => 'Bonus',
        'go_presets' => $tier_presets,
        'go_admin_bar_display_switch' => 'On',
        'go_admin_bar_user_redirect' => 'On',
        'go_user_redirect_location' => '',
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
        'go_search_switch' => 'On',
        'go_map_switch' => 'On',
        'go_dashboard_switch' => 'On',
        'go_store_switch' => 'On',
        'go_fitvids_switch' => 'On',
        'go_oembed_switch' => 'On',
        'go_lightbox_switch' => 'On',
        'go_fitvids_maxwidth' => '500px',
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
*/



?>