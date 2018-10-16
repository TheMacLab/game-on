<?php
//https://codex.wordpress.org/Creating_Tables_with_Plugins
global $wpdb;

function go_update_db_check() {
    $go_db_version = 4.21;
    if ( get_site_option( 'go_db_version' ) != $go_db_version ) {
        update_option('go_db_version', $go_db_version);
        go_update_db();
        //go_on_activate_msdb(true);
    }
}
//add_action( 'plugins_loaded', 'go_update_db_check' );

function go_update_db() {
    go_table_totals();
    go_table_tasks();
    go_table_actions();
    go_install_data();
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
			gold DECIMAL (10,2),
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
}

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
			global_mod DECIMAL (10,4),
			xp INT,
			gold DECIMAL (10,2),
			health DECIMAL (10,2),
			c4 INT,
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			xp_total INT unsigned,
			gold_total DECIMAL (10,2) unsigned,
			health_total INT unsigned,
			c4_total INT unsigned,
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
    $table_name = "{$wpdb->prefix}go_loot";
    $sql = "
		CREATE TABLE $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20) NOT NULL,
			xp INT unsigned DEFAULT 0,
			gold DECIMAL (10,2) DEFAULT 0,
			health DECIMAL (10,2) unsigned DEFAULT 100,
			c4 INT unsigned DEFAULT 0,
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			badge_count INT DEFAULT 0,
			PRIMARY KEY  (id),
            CONSTRAINT user_id UNIQUE (uid)                
		);
	";

    require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
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

}

//not sure what this does, but it is in an activation hook
function go_open_comments() {
    global $wpdb;
    $wpdb->update( $wpdb->posts, array( 'comment_status' => 'open', 'ping_status' => 'open' ), array( 'post_type' => 'tasks' ) );
}


?>