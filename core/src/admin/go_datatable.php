<?php
//https://codex.wordpress.org/Creating_Tables_with_Plugins
global $wpdb;

function go_update_db_check() {
    $go_db_version = 4.6;
    if ( get_site_option( 'go_db_version' ) != $go_db_version ) {
        update_option('go_db_version', $go_db_version);
        go_update_db();
    }
}
add_action( 'plugins_loaded', 'go_update_db_check' );

function go_update_db() {
    go_table_totals();
    go_table_tasks();
    go_table_actions();
    go_install_data();
    go_set_options_autoload();
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
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			start_time datetime,
			last_time datetime,
			timer_time datetime,
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
			result TEXT,
			quiz_mod DECIMAL (10,2),
			late_mod DECIMAL (10,2),
			timer_mod DECIMAL (10,2),
			global_mod DECIMAL (10,4),
			xp INT,
			gold DECIMAL (10,2),
			health DECIMAL (10,2),
			badges VARCHAR (4096),
			groups VARCHAR (4096),
			xp_total INT unsigned,
			gold_total DECIMAL (10,2) unsigned,
			health_total INT unsigned,
			PRIMARY KEY  (id),
            KEY uid (uid),
            KEY source_id (source_id),
            KEY action_type (action_type ),
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

function go_set_options_autoload(){
    $options_array = array(
        'options_go_tasks_name_singular',
        'options_go_tasks_name_plural',
        'options_go_tasks_stage_name_singular',
        'options_go_tasks_stage_name_plural',
        'options_go_tasks_optional_task',
        'options_go_tasks_bonus_stage',
        'options_go_store_toggle',
        'options_go_store_name',
        'options_go_store_store_link',
        'options_go_store_store_receipts',
        'options_go_badges_toggle',
        'options_go_badges_name_singular',
        'options_go_badges_name_plural',
        'options_go_stats_toggle',
        'options_go_blogs_toggle',
        'options_go_stats_name',
        'options_go_stats_leaderboard_toggle',
        'options_go_stats_leaderboard_name',
        'options_go_locations_map_toggle',
        'options_go_locations_map_title',
        'options_go_locations_map_map_link',
        'options_go_loot_name',
        'options_go_loot_xp_toggle',
        'options_go_loot_gold_toggle',
        'options_go_loot_health_toggle',

        'options_go_loot_xp_name',
        'options_go_loot_gold_name',
        'options_go_loot_health_name',

        'options_go_loot_xp_abbreviation',
        'options_go_loot_gold_abbreviation',
        'options_go_loot_health_abbreviation',

        'options_go_loot_xp_levels_name_singular',
        'options_go_loot_xp_levels_name_plural',
        'options_go_loot_xp_levels_growth',

        'options_go_loot_bonus_loot_toggle',
        'options_go_loot_bonus_loot_name',

        'options_go_seats_name',
        'options_go_seats_number',

        'options_go_video_width_unit',
        'options_go_video_width_pixels',
        'options_go_video_lightbox',

        //'options_go_images_resize_toggle',
        //'options_go_images_resize_longest_side',

        'options_go_guest_global',
        'options_go_full-names_toggle',
        'options_go_search_toggle',

        'options_go_dashboard_toggle',
        'options_go_admin_bar_toggle',

        'options_go_slugs_toggle',

        'options_go_avatars_local',
        'options_go_avatars_gravatars',
    );

    foreach ( $options_array as $option ) {//autoload must be set on creation of option
        $value = get_option($option); //get the value if it exists
        if ($value) {//if value already exists, set the value
            delete_option($option);
        }
        update_option( $option, $value, true );//update the value
    }
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
        'options_go_blogs_toggle' => 1,
        'options_go_stats_name' => 'Stats',
        'options_go_stats_leaderboard_toggle' => 1,
        'options_go_stats_leaderboard_name' => 'Leaderboard',
        'options_go_locations_map_toggle' => 1,
        'options_go_locations_map_title' => 'Map',
        'options_go_locations_map_map_link' => 'map',
        'options_go_loot_name' => 'Loot',
        'options_go_loot_xp_toggle' => 1,
        'options_go_loot_gold_toggle' => 1,
        'options_go_loot_health_toggle' => 1,

        'options_go_loot_xp_name' => 'Experience Points',
        'options_go_loot_gold_name' => 'Gold',
        'options_go_loot_health_name' => 'Health',

        'options_go_loot_xp_abbreviation' => 'XP',
        'options_go_loot_gold_abbreviation' => 'G',
        'options_go_loot_health_abbreviation' => 'HP',

        'options_go_loot_xp_levels_name_singular' => 'Level',
        'options_go_loot_xp_levels_name_plural' => 'Levels',
        'options_go_loot_xp_levels_growth' => '5',
        'options_go_loot_xp_levels_go_first_level_up' => 50,

        'options_go_loot_bonus_loot_toggle' => 1,
        'options_go_loot_bonus_loot_name' => 'Bonus Loot',

        'options_go_seats_name' => 'Seat',
        'options_go_seats_number' => '40',

        'options_go_video_width_unit' => 'px',
        'options_go_video_width_pixels' => '500',
        'options_go_video_lightbox' => '1',

        //'options_go_images_resize_toggle' => 1,
        //'options_go_images_resize_longest_side' => '1920',

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
        add_option( $key, $value, '', 'yes' );
    }
    if($reset){
        update_option( $key, $value, true );
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

    //For Levels
    $isset = get_option('options_go_feedback_canned'); //if there are no level at all
    if ($isset == false){
        add_option('options_go_feedback_canned', 3);
        add_option('options_go_feedback_canned_0_title', 'Post has been reset');
        add_option('options_go_feedback_canned_0_message', 'This post does not meet the minimum requirements.  Loot has been removed.');
        add_option('options_go_feedback_canned_0_defaults_xp', 0);
        add_option('options_go_feedback_canned_0_defaults_gold', 0);
        add_option('options_go_feedback_canned_0_defaults_health', 0);
        add_option('options_go_feedback_canned_1_title', 'Needs revision');
        add_option('options_go_feedback_canned_1_message', 'Please revise this post.');
        add_option('options_go_feedback_canned_1_defaults_xp', 0);
        add_option('options_go_feedback_canned_1_defaults_gold', 0);
        add_option('options_go_feedback_canned_1_defaults_health', 0);
        add_option('options_go_feedback_canned_2_title', 'Great work');
        add_option('options_go_feedback_canned_2_message', 'Great job!  Here is some extra loot.');
        add_option('options_go_feedback_canned_2_defaults_xp', 10);
        add_option('options_go_feedback_canned_2_defaults_gold', 10);
        add_option('options_go_feedback_canned_2_defaults_health', 0);
    }

}

//not sure what this does, but it is in an activation hook
function go_open_comments() {
    global $wpdb;
    $wpdb->update( $wpdb->posts, array( 'comment_status' => 'open', 'ping_status' => 'open' ), array( 'post_type' => 'tasks' ) );
}


?>