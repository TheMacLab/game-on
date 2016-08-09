<?php

// task includes
include( 'tasks/task.php' );

// store includes
include( 'store/super-store.php' );

// test includes
include( 'test/test_shortcode.php' );

// Meta Boxes
function go_init_mtbxs() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';
}
function go_mta_con_meta( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = 'go_mta_';
	// Tasks Meta Boxes
	$meta_boxes[] = array(
		'id'         => 'go_mta_metabox',
		'title'      => go_return_options( 'go_tasks_name' ).' Settings',
		'pages'      => array( 'tasks' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Presets'.go_task_opt_help( 'presets', 'SAMPLE TEXT HELLO WORLD HOW ARE YOU DOING TODAY THIS IS GREAT', 'http://maclab.guhsd.net/go/video/quests/presets.mp4' ),
				'id'   => 'go_presets',
				'type' => 'go_presets',
			),
			array(
				'name' => go_task_opt_help( 'advanced_settings', '', 'http://maclab.guhsd.net/go/video/quests/advancedSettings.mp4' ),
				'id' => 'advanced_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Advanced Settings',
				'settings_id' => 'go_advanced_task_settings_accordion'
			),
			array(
				'name' => 'Required Rank '.go_task_opt_help( 'req_rank', '', 'http://maclab.guhsd.net/go/video/quests/requiredRank.mp4' ),
				'id'   => "{$prefix}req_rank",
				'type' => 'go_rank_list'
			),
			array(
				'name' => go_return_options( 'go_bonus_currency_name' ).' Filter'.go_task_opt_help( 'bonus_currency_filter', '', 'http://maclab.guhsd.net/go/video/quests/bonusCurrencyFilter.mp4' ),
				'id' => "{$prefix}bonus_currency_filter",
				'type' => 'text'
			),
			array(
				'name' => go_return_options( 'go_penalty_name' ).' Filter'.go_task_opt_help( 'penalty_filter', '', 'http://maclab.guhsd.net/go/video/quests/penaltyFilter.mp4' ),
				'id' => "{$prefix}penalty_filter",
				'type' => 'text'
			),
			array(
				'name' => 'Start Filter'.go_task_opt_help( 'start_filter', '', 'http://maclab.guhsd.net/go/video/quests/startFilter.mp4' ),
				'id' => "{$prefix}start_filter",
				'type' => 'go_start_filter'
			),
			array(
				'name' => 'Time Filters'.go_task_opt_help( 'time_filters', '', 'http://maclab.guhsd.net/go/video/quests/timeFilter.mp4' ),
				'id' => "{$prefix}time_filters",
				'type' => 'go_future_filters'
			),
			array(
				'name' => 'Date'.go_task_opt_help( 'nerf_dates', '', 'http://maclab.guhsd.net/go/video/quests/nerfDates.mp4' ),
				'id' => "{$prefix}date_picker",
				'type' => 'go_decay_table'
			),
			array(
				'name' => 'Time'.go_task_opt_help( 'time_modifier', '', 'http://maclab.guhsd.net/go/video/quests/nerfTime.mp4' ),
				'id' => "{$prefix}time_modifier",
				'type' => 'go_time_modifier_inputs'
			),
			array(
				'name' => go_return_options( 'go_focus_name' ).' Filter'.go_task_opt_help( 'lock_by_cat', '', ' http://maclab.guhsd.net/go/video/quests/lockByProfessionCategory.mp4' ),
				'id' => "{$prefix}focus_category_lock",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Chain Order'.go_task_opt_help( 'task_chain_order', '', 'http://maclab.guhsd.net/go/video/quests/tasksInChain.mp4' ),
				'id' => "{$prefix}chain_order",
				'type' => 'go_task_chain_order'
			),
			array(
				'name' => 'Final '.go_return_options( 'go_tasks_name' ).' Message'.go_task_opt_help( 'final_chain_message', '', 'http://maclab.guhsd.net/go/video/quests/finalChainMessage.mp4' ),
				'id' => "{$prefix}final_chain_message",
				'type' => 'text'
			),
			array(
				'name' => 'Stage 1'.go_task_opt_help( 'encounter', '', 'http://maclab.guhsd.net/go/video/quests/stageOne.mp4' ),
				'id' => "{$prefix}quick_desc",
				'type' => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),
			),
			array(
				'name' => go_task_opt_help( 'stage_one_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageOneSettings.mp4' ),
				'id' => 'stage_one_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 1 Settings',
				'settings_id' => 'go_stage_one_settings_accordion'
			),
			array(
				'name' => go_return_options( 'go_points_name' ).go_task_opt_help( 'stage_one_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4' ),
				'id' => "{$prefix}stage_one_points",
				'type' => 'go_stage_reward',
				'stage' => 1,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options( 'go_currency_name' ).go_task_opt_help( 'stage_one_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4' ),
				'id' => "{$prefix}stage_one_currency",
				'type' => 'go_stage_reward',
				'stage' => 1,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options( 'go_bonus_currency_name' ).go_task_opt_help( 'stage_one_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4' ),
				'id' => "{$prefix}stage_one_bonus_currency",
				'type' => 'go_stage_reward',
				'stage' => 1,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help( 'encounter_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/adminLock.mp4' ),
				'id' => "{$prefix}encounter_admin_lock",
				'type' => 'go_admin_lock'
			),
			array(
				'name' => 'URL'.go_task_opt_help( 'encounter_url_key', '', 'http://maclab.guhsd.net/go/video/quests/urlKey.mp4' ),
				'id' => "{$prefix}encounter_url_key",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help( 'encounter_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4' ),
				'id' => "{$prefix}encounter_upload",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help( 'encounter_understand', '', 'http://maclab.guhsd.net/go/video/quests/encounterCheckForUnderstanding.mp4' ),
				'id' => "{$prefix}test_encounter_lock",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help( 'encounter_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4' ),
				'id' => "{$prefix}test_encounter_lock_loot",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help( 'encounter_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4' ),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', " ) are not permited.</code>',
				'id' => "{$prefix}test_encounter_lock_loot_mod",
				'type' => 'go_test_modifier'
			),
			array(
				'name' => 'Format'.go_task_opt_help( 'encounter_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4' ),
				'id' => "{$prefix}test_encounter_lock_fields",
				'type' => 'go_test_field',
				'test_type' => 'e'
			),
			array(
				'name' => 'Badge'.go_task_opt_help( 'badge', '', 'http://maclab.guhsd.net/go/video/quests/badge.mp4' ),
				'id' => "{$prefix}stage_one_badge",
				'type' => 'go_badge_input',
				'stage' => 1
			),
			array(
				'name' => 'Stage 2'.go_task_opt_help( 'accept', '', 'http://maclab.guhsd.net/go/video/quests/acceptMessage.mp4' ),
				'id' => "{$prefix}accept_message",
				'type' => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),
			),
			array(
				'name' => go_task_opt_help( 'stage_two_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageTwoSettings.mp4' ),
				'id' => 'stage_two_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 2 Settings',
				'settings_id' => 'go_stage_two_settings_accordion'
			),
			array(
				'name' => go_return_options( 'go_points_name' ).go_task_opt_help( 'stage_two_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4' ),
				'id' => "{$prefix}stage_two_points",
				'type' => 'go_stage_reward',
				'stage' => 2,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options( 'go_currency_name' ).go_task_opt_help( 'stage_two_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4' ),
				'id' => "{$prefix}stage_two_currency",
				'type' => 'go_stage_reward',
				'stage' => 2,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options( 'go_bonus_currency_name' ).go_task_opt_help( 'stage_two_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4' ),
				'id' => "{$prefix}stage_two_bonus_currency",
				'type' => 'go_stage_reward',
				'stage' => 2,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help( 'accept_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/adminLock.mp4' ),
				'id' => "{$prefix}accept_admin_lock",
				'type' => 'go_admin_lock'
			),
			array(
				'name' => 'URL'.go_task_opt_help( 'accept_url_key', '', 'http://maclab.guhsd.net/go/video/quests/urlKey.mp4' ),
				'id' => "{$prefix}accept_url_key",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help( 'accept_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4' ),
				'id' => "{$prefix}accept_upload",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help( 'accept_understand', '', 'http://maclab.guhsd.net/go/video/quests/acceptCheckForUnderstanding.mp4' ),
				'id' => "{$prefix}test_accept_lock",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help( 'accept_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4' ),
				'id' => "{$prefix}test_accept_lock_loot",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help( 'accept_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4' ),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', " ) are not permited.</code>',
				'id' => "{$prefix}test_accept_lock_loot_mod",
				'type' => 'go_test_modifier'
			),
			array(
				'name' => 'Format'.go_task_opt_help( 'accept_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4' ),
				'id' => "{$prefix}test_accept_lock_fields",
				'type' => 'go_test_field',
				'test_type' => 'a'
			),
			array(
				'name' => 'Badge'.go_task_opt_help( 'badge', '', 'http://maclab.guhsd.net/go/video/quests/badge.mp4' ),
				'id' => "{$prefix}stage_two_badge",
				'type' => 'go_badge_input',
				'stage' => 2
			),
			array(
				'name' => 'Stage 3'.go_task_opt_help( 'complete', '', 'http://maclab.guhsd.net/go/video/quests/completionMessage.mp4' ),
				'id' => "{$prefix}complete_message",
				'type' => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),
			),
			array(
				'name' => go_task_opt_help( 'stage_three_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageThreeSettings.mp4' ),
				'id' => 'stage_three_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 3 Settings',
				'settings_id' => 'go_stage_three_settings_accordion'
			),
			array(
				'name' => go_return_options( 'go_points_name' ).go_task_opt_help( 'stage_three_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4' ),
				'id' => "{$prefix}stage_three_points",
				'type' => 'go_stage_reward',
				'stage' => 3,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options( 'go_currency_name' ).go_task_opt_help( 'stage_three_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4' ),
				'id' => "{$prefix}stage_three_currency",
				'type' => 'go_stage_reward',
				'stage' => 3,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options( 'go_bonus_currency_name' ).go_task_opt_help( 'stage_three_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4' ),
				'id' => "{$prefix}stage_three_bonus_currency",
				'type' => 'go_stage_reward',
				'stage' => 3,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help( 'completion_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/adminLock.mp4' ),
				'id' => "{$prefix}completion_admin_lock",
				'type' => 'go_admin_lock'
			),
			array(
				'name' => 'URL'.go_task_opt_help( 'completion_url_key', '', 'http://maclab.guhsd.net/go/video/quests/urlKey.mp4' ),
				'id' => "{$prefix}completion_url_key",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help( 'completion_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4' ),
				'id' => "{$prefix}completion_upload",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help( 'complete_understand', '', 'http://maclab.guhsd.net/go/video/quests/completionCheckForUnderstanding.mp4' ),
				'id' => "{$prefix}test_completion_lock",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help( 'complete_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4' ),
				'id' => "{$prefix}test_completion_lock_loot",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help( 'complete_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4' ),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', " ) are not permited.</code>',
				'id' => "{$prefix}test_completion_lock_loot_mod",
				'type' => 'go_test_modifier'
			),
			array(
				'name' => 'Format'.go_task_opt_help( 'complete_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4' ),
				'id' => "{$prefix}test_completion_lock_fields",
				'type' => 'go_test_field',
				'test_type' => 'c'
			),
			array(
				'name' => 'Badge'.go_task_opt_help( 'badge', '', 'http://maclab.guhsd.net/go/video/quests/badge.mp4' ),
				'id' => "{$prefix}stage_three_badge",
				'type' => 'go_badge_input',
				'stage' => 3
			),
			array(
				'name' => '3 Stage '.go_return_options( 'go_tasks_name' ).go_task_opt_help( 'three_stage_switch', '', 'http://maclab.guhsd.net/go/video/quests/threeStageQuest.mp4' ),
				'id' => "{$prefix}three_stage_switch",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Stage 4'.go_task_opt_help( 'mastery', '', 'http://maclab.guhsd.net/go/video/quests/stageFour.mp4' ),
				'id' => "{$prefix}mastery_message",
				'type' => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),
			),
			array(
				'name' => go_task_opt_help( 'stage_four_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageFourSettings.mp4' ),
				'id' => 'stage_four_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 4 Settings',
				'settings_id' => 'go_stage_four_settings_accordion'
			),
			array(
				'name' => go_return_options( 'go_points_name' ).go_task_opt_help( 'stage_four_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4' ),
				'id' => "{$prefix}stage_four_points",
				'type' => 'go_stage_reward',
				'stage' => 4,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options( 'go_currency_name' ).go_task_opt_help( 'stage_four_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4' ),
				'id' => "{$prefix}stage_four_currency",
				'type' => 'go_stage_reward',
				'stage' => 4,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options( 'go_bonus_currency_name' ).go_task_opt_help( 'stage_four_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4' ),
				'id' => "{$prefix}stage_four_bonus_currency",
				'type' => 'go_stage_reward',
				'stage' => 4,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help( 'mastery_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/adminLock.mp4' ),
				'id' => "{$prefix}mastery_admin_lock",
				'type' => 'go_admin_lock'
			),
			array(
				'name' => 'URL'.go_task_opt_help( 'mastery_url_key', '', 'http://maclab.guhsd.net/go/video/quests/urlKey.mp4' ),
				'id' => "{$prefix}mastery_url_key",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help( 'mastery_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4' ),
				'id' => "{$prefix}mastery_upload",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help( 'mastery_understand', '', 'http://maclab.guhsd.net/go/video/quests/masteryCheckForUnderstanding.mp4' ),
				'id' => "{$prefix}test_mastery_lock",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help( 'mastery_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4' ),
				'id' => "{$prefix}test_mastery_lock_loot",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help( 'mastery_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4' ),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', " ) are not permited.</code>',
				'id' => "{$prefix}test_mastery_lock_loot_mod",
				'type' => 'go_test_modifier'
			),
			array(
				'name' => 'Format'.go_task_opt_help( 'mastery_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4' ),
				'id' => "{$prefix}test_mastery_lock_fields",
				'type' => 'go_test_field',
				'test_type' => 'm'
			),
			array(
				'name' => 'Private'.go_task_opt_help( 'mastery_privacy', '', 'http://maclab.guhsd.net/go/video/quests/masteryPrivacy.mp4' ),
				'id' => "{$prefix}mastery_privacy",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Badge'.go_task_opt_help( 'badge', '', 'http://maclab.guhsd.net/go/video/quests/badge.mp4' ),
				'id' => "{$prefix}stage_four_badge",
				'type' => 'go_badge_input',
				'stage' => 4
			),
			array(
				'name' => '5 Stage '.go_return_options( 'go_tasks_name' ).go_task_opt_help( 'five_stage_switch', '', 'http://maclab.guhsd.net/go/video/quests/fiveStageQuest.mp4' ),
				'id'   => "{$prefix}five_stage_switch",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Bonus Loot'.go_task_opt_help( 'mastery_bonus', '', 'http://maclab.guhsd.net/go/video/quests/bonus.mp4' ),
				'id' => "{$prefix}mastery_bonus_loot",
				'type' => 'go_bonus_loot'
			),
			array(
				'name' => 'Stage 5'.go_task_opt_help( 'repeat_message', '', 'http://maclab.guhsd.net/go/video/quests/stageFive.mp4' ),
				'id' => "{$prefix}repeat_message",
				'type' => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),
			),
			array(
				'name' => go_task_opt_help( 'stage_five_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageFiveSettings.mp4' ),
				'id' => 'stage_five_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 5 Settings',
				'settings_id' => 'go_stage_five_settings_accordion'
			),
			array(
				'name' => go_return_options( 'go_points_name' ).go_task_opt_help( 'stage_five_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4' ),
				'id' => "{$prefix}stage_five_points",
				'type' => 'go_stage_reward',
				'stage' => 5,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options( 'go_currency_name' ).go_task_opt_help( 'stage_five_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4' ),
				'id' => "{$prefix}stage_five_currency",
				'type' => 'go_stage_reward',
				'stage' => 5,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options( 'go_bonus_currency_name' ).go_task_opt_help( 'stage_five_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4' ),
				'id' => "{$prefix}stage_five_bonus_currency",
				'type' => 'go_stage_reward',
				'stage' => 5,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Limit'.go_task_opt_help( 'repeat_amount', '', 'http://maclab.guhsd.net/go/video/quests/allowedRepeatableTimes.mp4' ),
				'id' => "{$prefix}repeat_amount",
				'type' => 'go_repeat_amount'
			),
			array(
				'name' => 'Lock'.go_task_opt_help( 'repeat_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/adminLock.mp4' ),
				'id' => "{$prefix}repeat_admin_lock",
				'type' => 'go_admin_lock'
			),
			array(
				'name' => 'Upload'.go_task_opt_help( 'repeat_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4' ),
				'id' => "{$prefix}repeat_upload",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Private'.go_task_opt_help( 'repeat_privacy', '', 'http://maclab.guhsd.net/go/video/quests/repeatPrivacy.mp4' ),
				'id' => "{$prefix}repeat_privacy",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Badge'.go_task_opt_help( 'badge', '', 'http://maclab.guhsd.net/go/video/quests/badge.mp4' ),
				'id' => "{$prefix}stage_five_badge",
				'type' => 'go_badge_input',
				'stage' => 5
			),
		),
	);
	// Store Meta Boxes
	$meta_boxes[] = array(
		'id'         => 'go_mta_metabox',
		'title'      => 'Store Item Options',
		'pages'      => array( 'go_store' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Item ID'.go_task_opt_help( 'post_id', 'The Store Item ID', 'http://maclab.guhsd.net/go/video/store/storeId.mp4' ),
				'id' => "{$prefix}store_post_id",
				'type' => 'go_store_item_post_id'
			),
			array(
				'name' => 'Cost'.go_task_opt_help( 'cost', 'The Cost of the store item', 'http://maclab.guhsd.net/go/video/store/cost.mp4' ),
				'id' => "{$prefix}store_cost",
				'type' => 'go_store_cost',
			),
			array(
				'name' => 'Limit'.go_task_opt_help( 'store_limit', 'Limit the amount of times a player can purchase this item', 'http://maclab.guhsd.net/go/video/store/storeLimit.mp4' ),
				'id' => "{$prefix}store_limit",
				'type' => 'go_store_limit'
			),
			array(
				'name' => 'Bonus Loot'.go_task_opt_help( 'Bonus', 'Make this item available as bonus loot from quests', 'http://maclab.guhsd.net/go/video/store/bonus.mp4' ),
				'id' => "{$prefix}store_bonus",
				'type' => 'checkbox',
			),
			array(
				'name' => 'Unpurchasable'.go_task_opt_help( 'Unpurchasable', 'Make this item unavailable for purchase' ),
				'id' => "{$prefix}store_unpurchasable",
				'type' => 'checkbox',
			),
			array(
				'name' => 'Debt'.go_task_opt_help( 'debt', 'Allows students to accrue debt when purchasing this item', 'http://maclab.guhsd.net/go/video/store/penalty.mp4' ),
				'id' => "{$prefix}debt_switch",
				'type' => 'checkbox',
			),
			array(
				'name' => 'Filter'.go_task_opt_help( 'filter', 'Require students to have certain amounts of levels/honor/damage to buy this item', 'http://maclab.guhsd.net/go/video/store/filter.mp4' ),
				'id' => "{$prefix}store_filter",
				'type' => 'go_store_filter'
			),
			array(
				'name' => 'Gift'.go_task_opt_help( 'item_gift', 'Allow an item to be bought for another student', 'http://maclab.guhsd.net/go/video/store/exchange.mp4' ),
				'id' => "{$prefix}store_gift",
				'type' => 'go_store_gift'
			),
			array(
				'name' => 'URL'.go_task_opt_help( 'item_url', 'Have URL appear upon purchase', 'http://maclab.guhsd.net/go/video/store/itemURL.mp4' ),
				'id' => "{$prefix}store_item_url",
				'type' => 'go_item_url'	
			),
			array(
				'name' => 'Badge'.go_task_opt_help( 'badge_id', 'Award a badge upon purchase', 'http://maclab.guhsd.net/go/video/store/badgeID.mp4' ),
				'id' => "{$prefix}badge_id",
				'type' => 'go_badge_id'
			),
			array(
				'name' => go_return_options( 'go_focus_name' ).go_task_opt_help( 'focus', 'Turn item into a profession', 'http://maclab.guhsd.net/go/video/store/focus.mp4' ),
				'id' => "{$prefix}store_focus",
				'type' => 'go_store_focus'
			),
			array(
				'name' => go_return_options( 'go_focus_name' ).' Lock'.go_task_opt_help( 'focus_lock', '', 'http://maclab.guhsd.net/go/video/store/focusLock.mp4' ),
				'id' => "{$prefix}store_focus_lock",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Send Receipt'.go_task_opt_help( 'store_receipt', 'Send email to admin upon purchase', 'http://maclab.guhsd.net/go/video/store/receipt.mp4' ),
				'id' => "{$prefix}store_receipt",
				'type' => 'go_store_receipt'
			),
		),
	);
	return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'go_mta_con_meta' );
add_action( 'init', 'go_init_mtbxs', 9999 );

add_action( 'cmb_render_go_presets', 'go_presets', 10, 1 );
function go_presets( $field_args ) {
	$custom = get_post_custom();
	$content_array = ( ! empty( $custom['go_presets'][0] ) ? unserialize( $custom['go_presets'][0] ) : null );
	if ( ! empty( $content_array ) ) {
		$custom_points = $content_array['points'];
		$custom_points_str = implode( ',', $custom_points );
		$custom_currency = $content_array['currency'];
		$custom_currency_str = implode( ',', $custom_currency );
	}
	?>
	<select id="go_presets">
		<?php
			$presets = get_option( 'go_presets', false );
			foreach( $presets['name'] as $key => $name ) {
				$points = implode( ',', $presets['points'][ $key ] );
				$currency = implode( ',', $presets['currency'][ $key ] );
				echo "<option value='{$name}' points='{$points}' currency='{$currency}'";
				if ( ! empty( $content_array ) && $custom_points_str == $points && $custom_currency_str == $currency ) {
					echo "selected";
				}
				echo ">{$name} - {$points} - {$currency}</option>";
			}
		?>
	</select>
	<?php
}

add_action( 'cmb_validate_go_presets', 'go_validate_stage_reward' );
function go_validate_stage_reward() {
	$points 		= ( ! empty( $_POST['stage_1_points'] ) 		? (array) $_POST['stage_1_points'] : array() );
	$currency 		= ( ! empty( $_POST['stage_1_currency'] ) 		? (array) $_POST['stage_1_currency'] : array() );
	$bonus_currency = ( ! empty( $_POST['stage_1_bonus_currency'] ) ? (array) $_POST['stage_1_bonus_currency'] : array() );

	return array(
		'points' => $points,
		'currency' => $currency,
		'bonus_currency' => $bonus_currency,
	);
}

add_action( 'cmb_render_go_rank_list', 'go_rank_list' );
function go_rank_list() {
	$custom = get_post_custom();
	$current_rank = ( ! empty( $custom['go_mta_req_rank'][0] ) ? $custom['go_mta_req_rank'][0] : null );
	$ranks_array = get_option( 'go_ranks' );
	$ranks = $ranks_array['name'];
	if ( ! empty( $ranks ) ) {
		echo "<select id='go_req_rank_select' name='go_mta_req_rank'>";
		foreach ( $ranks as $rank ) {
			echo "<option class='go_req_rank_option' ".( strtolower( $rank ) == strtolower( $current_rank ) ? 'selected' : '' ).">{$rank}</option>";
		}
		echo "</select>";
	} else {
		echo "No <a href='".admin_url()."/?page=game-on-options.php' target='_blank'>".get_option( 'go_level_plural_names' )."</a> were provided.";
	}
}

add_action( 'cmb_render_go_start_filter', 'go_render_start_filter' );
function go_render_start_filter() {
	$custom = get_post_custom();
	$custom_array = ( ! empty( $custom['go_mta_start_filter'][0] ) ? unserialize( $custom['go_mta_start_filter'][0] ) : null );
	$checked     = ( ! empty( $custom_array['checked'] ) ? 'checked' : '' );
	$date_output = ( ! empty( $custom_array['date'] )    ? "value='{$custom_array['date']}'" : 'placeholder="Click for Date"' );
	$time_output = ( ! empty( $custom_array['time'] )    ? "value='{$custom_array['time']}'" : 'placeholder="Click for Time" value="00:00"' );

	?>
	<input name='go_mta_task_start_date_switch' type='checkbox' id='go_start_checkbox' <?php echo $checked; ?>/>
	<div id='go_start_info'>
		<input name="go_mta_task_start_date" class='go_datepicker' type="date" <?php echo $date_output; ?>/> @ (hh:mm AM/PM)
		<input type='time' name='go_mta_task_start_time' class='custom_time' <?php echo $time_output;?> />
	</div>
	<?php
}

add_action( 'cmb_validate_go_start_filter', 'go_validate_start_filter' );
function go_validate_start_filter() { 
	$checked = ( ! empty( $_POST['go_mta_task_start_date_switch'] )	? true : false );
	$date    = ( ! empty( $_POST['go_mta_task_start_date'] )        ? sanitize_text_field( $_POST['go_mta_task_start_date'] ) : '' );
	$time    = ( ! empty( $_POST['go_mta_task_start_time'] )        ? sanitize_text_field( $_POST['go_mta_task_start_time'] ) : '' );
	
	$time    = substr( $time, 0, 8 ); // Make sure no more than 8 characters are in the string
	$hour    = intval( substr( $time, 0, strpos( $time, ':' ) ) ); // Grab numerical value of hour
	$minutes = substr( $time, strpos( $time, ':' ) + 1, strlen( $time ) ); // Grab minutes string
	
	if ( strpos( $time, 'PM' ) !== false ) { // If PM found
	
		if ( $hour < 12 ) { 
			$time = ( $hour + 12 ).substr( $time, strpos ( $time, ':' ), strpos( $time, ':' ) + 2 ); // Set the time saved to be the correct 24-hour representation
		}
		
		$time = str_replace( 'PM', '', $time ); // Remove PM from the string
		
	} elseif ( strpos( $time, 'AM' ) !== false ) { // If AM found
		
		if ( $hour < 10 ) {
			$time = "0{$time}"; // Add leading 0 to hour to maintain 00:00 format
		} elseif ( $hour == 12) {
			$time = "00:{$minutes}";
		}
		
		$time = str_replace( 'AM', '', $time ); // Remove AM from the string
	}
	$time = trim( $time ); // Remove spaces around time

	return array(
		'checked' => $checked,
		'date' => $date,
		'time' => $time,
	);
}

add_action( 'cmb_render_go_future_filters', 'go_future_filters' );
function go_future_filters( $field_args ) {
	$custom = get_post_custom();
	$checked_array    = ( ! empty( $custom['go_mta_time_filters'][0] ) ? unserialize( $custom['go_mta_time_filters'][0] ) : null );
	$calendar_checked = ( ! empty( $checked_array['calendar'] )        ? 'checked' : '' );
	$future_checked   = ( ! empty( $checked_array['future'] )          ? 'checked' : '' );

	echo "Date: <input  type='checkbox' id='go_calendar_checkbox' name='go_time_modifier[calendar]' {$calendar_checked}/>".
		"Time: <input type='checkbox' id='go_future_checkbox' name='go_time_modifier[future]' {$future_checked}/>";
}

add_action( 'cmb_validate_go_future_filters', 'go_validate_future_filters' );
function go_validate_future_filters() {
	$calendar_checked = ( ! empty( $_POST['go_time_modifier']['calendar'] ) ? true : false );
	$future_checked   = ( ! empty( $_POST['go_time_modifier']['future'] ) ? true : false );

	return array(
		'calendar' => $calendar_checked,
		'future' => $future_checked,
	);
}


add_action( 'cmb_render_go_decay_table', 'go_decay_table' );
function go_decay_table() {
	?>
		<table id="go_list_of_decay_dates" stye="margin: 0px; padding: 0px;">
			<tbody>
			<?php
			$custom = get_post_custom();
			$date_picker = ( ! empty( $custom['go_mta_date_picker'] ) ? $custom['go_mta_date_picker'] : null );
			if ( ! empty( $date_picker ) ) {
				$temp_array = array();
				$dates = array();
				$times = array();
				$percentages = array();
				
				foreach ( $date_picker as $key => $value ) {
					$temp_array[ $key ] = unserialize( $value );
				}
				
				$temp_array2 = $temp_array[0];
				
				if ( ! empty( $temp_array2 ) ) {
					foreach ( $temp_array2 as $key => $value ) {
						if ( $key == 'date' ) {
							foreach ( array_values( $value ) as $date_val ) {
								array_push( $dates, $date_val );
							}
						} elseif ( $key == 'time' ) {
							foreach ( array_values( $value ) as $time_val ) {
								array_push( $times, $time_val );
							}
						} elseif ( $key == 'percent' ) {
							foreach (array_values( $value ) as $percent_val ) {
								array_push( $percentages, $percent_val );
							}
						}
					}
				}
				foreach ( $dates as $key => $date ) {
					?>
					<tr>
						<td><input class='go_date_picker_input go_date_picker_calendar_input go_datepicker custom_date' name="go_mta_task_decay_calendar[<?php echo $key; ?>]" value="<?php echo $date; ?>" type="date"/> @ (hh:mm AM/PM)<input class='go_date_picker_input go_date_picker_time_input custom_time' type='time' name='go_mta_task_decay_calendar_time[<?php echo $key; ?>]' value='<?php echo $times[ $key ]; ?>' /></td>
						<td><input class='go_date_picker_input go_date_picker_modifier_input' name="go_mta_task_decay_percent[<?php echo $key; ?>]" value="<?php echo $percentages[ $key ]; ?>" type="text"/>%</td>
					</tr>
					<?php
				}
			} else {
			?>
			<tr>
				<td><input class='go_date_picker_input go_date_picker_calendar_input go_datepicker custom_date' name="go_mta_task_decay_calendar[]" type="date" placeholder="Click for Date"/> @ (hh:mm AM/PM)<input class='go_date_picker_input go_date_picker_time_input custom_time' type='time' name='go_mta_task_decay_calendar_time[]' placeholder='Click for Time' value='00:00' /></td>
				<td><input class='go_date_picker_input go_date_picker_modifier_input' name="go_mta_task_decay_percent[]" type="text" placeholder="Modifier"/>%</td>
			</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<input type="button" id="go_mta_add_task_decay" class='go_button_add_field' value="+"/>
		<input type="button" id="go_mta_remove_task_decay" class='go_button_del_field' value="x"/>
	<?php
}

add_action( 'cmb_validate_go_decay_table', 'go_validate_decay_table' );
function go_validate_decay_table() {
	
	// Filter empty values
	$dates 			= ( ! empty( $_POST['go_mta_task_decay_calendar'] ) 		? (array) $_POST['go_mta_task_decay_calendar'] : array() );
	$times 			= ( ! empty( $_POST['go_mta_task_decay_calendar_time'] ) 	? (array) $_POST['go_mta_task_decay_calendar_time'] : array() );
	$percentages 	= ( ! empty( $_POST['go_mta_task_decay_percent'] ) 			? (array) $_POST['go_mta_task_decay_percent'] : array() );

	if ( ! empty( $dates ) && ! empty( $times ) && ! empty( $percentages ) ) {
		$dates_f = array_filter( $dates );
		$times_f = array_filter( $times );
		$percentages_f = array_filter( $percentages );
		
		foreach ( $times_f as $key => $time ) {
			$time = substr( $time, 0, 8 ); // Make sure no more than 8 characters are in the string
			$hour = intval( substr( $time, 0, strpos( $time, ':' ) ) ); // Grab numerical value of hour
			$minutes = substr( $time, strpos( $time, ':' ) + 1, strlen( $time ) ); // Grab minutes string
			
			if ( strpos( $time, 'PM' ) !== false ) { // If PM found
			
				if ( $hour < 12 ) { 
					$times_f[ $key ] = ( $hour + 12 ).substr( $time, strpos ( $time, ':' ), strpos( $time, ':' ) + 2 ); // Set the time saved to be the correct 24-hour representation
				}
				
				$times_f[ $key ] = str_replace( 'PM', '', $times_f[ $key ] ); // Remove PM from the string
				
			} elseif ( strpos( $time, 'AM' ) !== false ) { // If AM found
				
				if ( $hour < 10 ) {
					$times_f[ $key ] = "0{$times_f[ $key ]}"; // Add leading 0 to hour to maintain 00:00 format
				} elseif ( $hour == 12) {
					$times_f[ $key ] = "00:{$minutes}";
				}
				
				$times_f[ $key ] = str_replace( 'AM', '', $times_f[ $key ] ); // Remove AM from the string
			}
			$times_f[ $key ] = trim( $times_f[ $key ] ); // Remove spaces around time
		}
		$new_dates = array_intersect_key( $dates_f, $times_f, $percentages_f );
		$new_times = array_intersect_key( $times_f, $percentages_f, $times_f );
		$new_percentages = array_intersect_key( $percentages_f, $dates_f, $times_f );
		
		$modifier_array = array( 'date' => $new_dates, 'time' => $new_times, 'percent' => $new_percentages );	
		return $modifier_array;
	}
}


add_action( 'cmb_render_go_bonus_loot', 'go_render_bonus_loot' );
function go_render_bonus_loot( $field_args ) {
	$custom = get_post_custom();
	$check_array = ( ! empty( $custom['go_mta_mastery_bonus_loot'][0] ) ? unserialize( $custom['go_mta_mastery_bonus_loot'][0] ) : null );
	$meta_id = $field_args['id'];
	$bonus_loot_opt_checked = ( ! empty( $check_array[0] ) ? 'checked' : '' );
	$min = 0.01;
	$max = 99.99;
	$range_str = "{$min}, {$max}";
	$store_list = get_posts(
		array(
			'post_type' => 'go_store',
			'orderby' => 'post_date',
			'order' => 'DESC',
			'meta_query' => array(
				array(
					'key' => 'go_mta_store_bonus',
					'value' => 'on',
				),
			),
		)
	);

	$list_elems = '';

	foreach ( $store_list as $store_item ) {
		$rarity = ( ! empty( $check_array[2][ $store_item->ID ] ) ? $check_array[2][ $store_item->ID ] : 50 );
		$item_checked = ( ! empty( $check_array[1][ $store_item->ID ] ) ? 'checked' : '' );
		$list_elems .= sprintf(
			'<li>'.
				'<input type="checkbox" class="go_bonus_loot_checkbox go_bonus_loot_item_checkbox" '.
					'name="go_task_bonus_loot_select[%1$d]" %2$s /> %3$s'.
				'<input type="text" id="go_bonus_loot_rarity_%1$d" class="go_bonus_loot_rarity" '.
					'name="go_bonus_loot_rarity[%1$d]" value="%4$0.2f" min="%5$0.2f" max="%6$0.2f" /> %%'.
			'</li>',
			$store_item->ID,
			$item_checked,
			$store_item->post_title,
			$rarity,
			$min,
			$max
		);
	}

	printf(
		'<input id="go_bonus_loot_checkbox" class="go_bonus_loot_checkbox" name="%s" type="checkbox" %s/>'.
		'<input type="hidden" class="go_bonus_loot_rarity_range" name="go_bonus_loot_rarity_range" value="%s"/>'.
		'<ul id="go_bonus_loot_wrap">%s</ul>',
		$meta_id,
		$bonus_loot_opt_checked,
		$range_str,
		$list_elems
	);
}

add_action( 'cmb_validate_go_bonus_loot', 'go_validate_bonus_loot' );
function go_validate_bonus_loot() {
	$is_checked         = ( ! empty( $_POST['go_mta_mastery_bonus_loot'] )  ? true : false );
	$selected_loot      = ( ! empty( $_POST['go_task_bonus_loot_select'] )  ? (array) $_POST['go_task_bonus_loot_select'] : array() );
	$loot_rarity        = ( ! empty( $_POST['go_bonus_loot_rarity'] )       ? (array) $_POST['go_bonus_loot_rarity'] : array() );
	$range_values_str   = ( ! empty( $_POST['go_bonus_loot_rarity_range'] ) ? sanitize_text_field( $_POST['go_bonus_loot_rarity_range'] ) : '' );

	$range_values_array = ( ! empty( $range_values_str ) ? explode( ',', $range_values_str ) : array() );
	if ( ! empty( $range_values_array ) ) {
		$range_min = $range_values_array[0];
		$range_max = $range_values_array[1];
	}

	$rarity_array = array();
	if ( ! empty( $loot_rarity ) && isset( $range_min ) && isset( $range_max ) ) {
		foreach ( $loot_rarity as $item_id => $perc ) {

			// test the rarity value against the desired float pattern
			// (i.e. "XX" or "XX.X+", where "X" is a number 0 through 9 and
			// + indicates "X" once or more times)
			$float_regex = '/^([0-9]{1,2}|[0-9]{0,2}\.[0-9]{1,})$/';
			$float_match = preg_match( $float_regex, $perc );
			if ( 0 === $float_match ) {
				$rarity_array[ $item_id ] = null;
			} else {

				// check for the input being within the field's min and max values
				$perc_float = floatval( $perc );
				$rounded_perc = round( $perc_float, 2 );
				if ( $rounded_perc < $range_min && $rounded_perc > $range_max ) {
					$rarity_array[ $item_id ] = null;
				} else {
					$rarity_array[ $item_id ] = $rounded_perc;
				}
			}
		}
	}

	return array(
		$is_checked,
		$selected_loot,
		$rarity_array,
	);
}

add_action( 'cmb_render_go_store_unpurchasable', 'go_unpurchasable' );
function go_unpurchasable() {
	$custom = get_post_custom();
	$unpurchasable = get_option( 'go_store_unpurchasable_switch' );
	$is_checked = ( ! empty( $custom['go_mta_unpurchasable'][0] ) ? 'checked' : '' );

	echo "<input id='go_unpurchasable_checkbox' name='go_unpurchasable' type='checkbox' {$is_checked}/>";
}

add_action( 'cmb_validate_go_unpurchasable', 'go_validate_unpurchasable' );
function go_validate_unpurchasable() {
	$is_checked = ( ! empty( $_POST['go_unpurchasable'] ) ? true : false );

	return $is_checked;
}

add_action( 'cmb_render_go_time_modifier_inputs', 'go_time_modifier_inputs' );
function go_time_modifier_inputs() {
	$custom = get_post_custom();
	$time_modifier = ( ! empty( $custom['go_mta_time_modifier'][0] ) ? unserialize( $custom['go_mta_time_modifier'][0] ) : null );
	if ( $time_modifier ) {
		?>
		Days: <input type='text' name='go_modifier_input_days' value='<?php echo $time_modifier['days']; ?>'/>
		Hours: <input type='text' name='go_modifier_input_hours' value='<?php echo $time_modifier['hours']; ?>'/>
		Minutes: <input type='text' name='go_modifier_input_minutes' value='<?php echo $time_modifier['minutes']; ?>'/>
		Seconds: <input type='text' name='go_modifier_input_seconds' value='<?php echo $time_modifier['seconds']; ?>'/>
		Modifier: <input type='text' name='go_modifier_input_percent' value='<?php echo $time_modifier['percentage']; ?>'/>%
		<?php
	} else {
		?>
		Days: <input type='text' name='go_modifier_input_days'/>
		Hours: <input type='text' name='go_modifier_input_hours'/>
		Minutes: <input type='text' name='go_modifier_input_minutes'/>
		Seconds: <input type='text' name='go_modifier_input_seconds'/>
		Modifier: <input type='text' name='go_modifier_input_percent'/>
		<?php
	}
}

add_action( 'cmb_validate_go_time_modifier_inputs', 'go_validate_time_nerf_inputs' );
function go_validate_time_nerf_inputs() {
	$days       = ( ! empty( $_POST['go_modifier_input_days'] )     ? round( (int) $_POST['go_modifier_input_days'] ) : 0 );
	$hours      = ( ! empty( $_POST['go_modifier_input_hours'] )    ? round( (int) $_POST['go_modifier_input_hours'] ) : 0 );
	$minutes    = ( ! empty( $_POST['go_modifier_input_minutes'] )  ? round( (int) $_POST['go_modifier_input_minutes'] ) : 0 );
	$seconds    = ( ! empty( $_POST['go_modifier_input_seconds'] )  ? round( (int) $_POST['go_modifier_input_seconds'] ) : 0 );
	$percentage = ( ! empty( $_POST['go_modifier_input_percent'] )  ? (double) $_POST['go_modifier_input_percent'] : 0.0 );

	return array(
		'days' => $days,
		'hours' => $hours,
		'minutes' => $minutes,
		'seconds' => $seconds,
		'percentage' => $percentage,
	);
}

add_action( 'cmb_render_go_admin_lock', 'go_admin_lock', 10, 1 );
function go_admin_lock( $field_args ) {
	$custom = get_post_custom();
	$meta_id = $field_args['id'];
	$content_array = ( ! empty( $custom[ $meta_id ][0] ) ? unserialize( $custom[ $meta_id ][0] ) : null );
	$is_checked = ( ! empty( $content_array[0] ) ? 'checked' : '' );
	$pass = $content_array[1];
	echo "
		<input id='{$meta_id}_checkbox' class='go_admin_lock_checkbox' name='{$meta_id}' type='checkbox' {$is_checked}/>
		<input id='{$meta_id}_input' class='go_admin_lock_text' name='{$meta_id}_input' type='text' placeholder='Enter A Password' ".( ( ! empty( $pass ) ) ? "value='{$pass}'": '' )."/>
	";
}

add_action( 'cmb_validate_go_admin_lock', 'go_validate_admin_lock', 10, 3 );
function go_validate_admin_lock( $override_value, $post_id, $field_args ) {
	$meta_id = $field_args['id'];
	$is_checked = ( ! empty( $_POST[ $meta_id ] )           ? true : false );
	$temp_pass 	= ( ! empty( $_POST[ "{$meta_id}_input" ] ) ? sanitize_text_field( $_POST[ "{$meta_id}_input" ] ) : '' );

	if ( preg_match( "/['\"\<>]+/", $temp_pass ) ) {
		$pass = preg_replace( "/['\"<>]+/", '', $temp_pass );
	} else {
		$pass = $temp_pass;
	}
	return array( $is_checked, $pass );
}

add_action( 'cmb_render_go_test_modifier', 'go_test_modifier' );
function go_test_modifier( $field_args ) {
	global $post;
	$post_id = $post->ID;
	$meta_id = $field_args['id'];
	$custom = get_post_custom( $post_id );
	$modifier_content = ( ! empty( $custom["{$meta_id}"][0] ) ? $custom["{$meta_id}"][0] : null );
	if ( empty( $modifier_content ) ) {
		$modifier_content = 20;
	}
	echo "<input class='go_test_loot_mod' name='{$meta_id}' type='text' value='{$modifier_content}'/>";
}

add_action( 'cmb_validate_go_test_modifier', 'go_validate_test_modifier', 10, 3 );
function go_validate_test_modifier( $override_value, $post_id, $field_args ) {
	$meta_id = $field_args['id'];
	$mod_temp = ( ! empty( $_POST[ $meta_id ] ) ? sanitize_text_field( $_POST[ $meta_id ] ) : '' );
	if ( ! empty( $mod_temp ) ) {
		if ( ! preg_match( '/[0-9]+/', $mod_temp ) ) {
			return 20;
		} else {
			if ( preg_match( "/[^0-9]+/", $mod_temp ) ) {
				$mod = preg_replace( "/[^0-9]+/", '', $mod_temp );
				$mod = (int) $mod;
				if ( $mod > 0 && $mod <= 100 ) {
					return $mod;
				} else {
					return 20;
				}
			} elseif ( (int) $mod_temp > 0 && (int) $mod_temp <= 100) {
				return (int) $mod_temp;
			} else {
				return 20;
			}
		}
	} else {
		return 20;
	}
}

add_action( 'cmb_render_go_test_field', 'go_render_test_field', 10, 1 );
function go_render_test_field( $field_args ) {
	$custom = get_post_custom();

	$meta_id = $field_args['id'];
	$ttc = $field_args['test_type'];

	$temp_array = ( ! empty( $custom[ $meta_id ][0] ) ? $custom[ $meta_id ][0] : null );
	if ( ! empty( $temp_array ) ) {
		$temp_uns = unserialize( $temp_array );
		$test_field_input_question = ( ! empty( $temp_uns[0] ) ? $temp_uns[0] : null );
		$test_field_input_array = ( ! empty( $temp_uns[1] ) ? $temp_uns[1] : null );
		$test_field_select_array = ( ! empty( $temp_uns[2] ) ? $temp_uns[2] : null );
		$test_field_block_count = ( ! empty( $temp_uns[3] ) ? (int) $temp_uns[3] : null );
		$test_field_input_count = ( ! empty( $temp_uns[4] ) ? $temp_uns[4] : null );
	}

	?>
	<span class='cmb_metabox_description'>
		<?php echo $field_args['desc']; ?>
	</span>
	<table id='go_test_field_table_<?php echo $ttc; ?>' class='go_test_field_table'>
		<?php
		if ( ! empty( $test_field_block_count ) && ! empty( $test_field_input_array ) ) {
			for ( $i = 0; $i < $test_field_block_count; $i++ ) {
				if ( ! empty( $test_field_input_array[ $i ][0] ) ) {
					echo "
					<tr id='go_test_field_input_row_{$ttc}_{$i}' class='go_test_field_input_row_{$ttc} go_test_field_input_row'>
						<td>
							<select id='go_test_field_select_{$ttc}_{$i}' class='go_test_field_input_select_{$ttc}' name='go_test_field_select_{$ttc}[]' onchange='update_checkbox_type_{$ttc}(this);'>
								<option value='radio' class='go_test_field_input_option_{$ttc}' ".( ( $test_field_select_array[ $i ] == 'radio' ) ? 'selected' : '' ).">Multiple Choice</option>
								<option value='checkbox' class='go_test_field_input_option_{$ttc}' ".( ( $test_field_select_array[ $i ] == 'checkbox' ) ? 'selected' : '' ).">Multiple Select</option>
							</select>";
							if ( ! empty( $test_field_input_question ) ) {
								echo "<br/><br/><input class='go_test_field_input_question_{$ttc} go_test_field_input_question' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' value=\"".htmlspecialchars( $test_field_input_question[ $i ], ENT_QUOTES )."\" />";
							} else {
								echo "<br/><br/><input class='go_test_field_input_question_{$ttc} go_test_field_input_question' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' />";
							}
					if ( ! empty( $test_field_input_count ) ) {
						echo "<ul>";
						for ( $x = 0; $x < $test_field_input_count[ $i ]; $x++ ) {
							echo "
								<li><input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value_{$ttc}(this);' />
								<input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' />
								<input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"".htmlspecialchars( $test_field_input_array[ $i ][0][ $x ], ENT_QUOTES )."\" oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' />";
							if ( $x > 1 ) {
								echo "<input class='go_button_del_field go_test_field_rm go_test_field_rm_input_button_{$ttc}' type='button' value='x' onclick='remove_field_{$ttc}(this);'>";
							}
							echo "</li>";
							if ( ( $x + 1 ) == $test_field_input_count[ $i ] ) {
								echo "<input class='go_button_add_field go_test_field_add go_test_field_add_input_button_{$ttc}' type='button' value='+' onclick='add_field_{$ttc}(this);'/>";
							}
						}
						echo "</ul><ul>";
						if ( $i > 0 ) {
							echo "<li><input class='go_button_del_field go_test_field_rm_row_button_{$ttc} go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_{$ttc}(this);' /></li>";
						}
						echo "<li><input class='go_test_field_input_count_{$ttc}' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='{$test_field_input_count[ $i]}' /></li></ul>";
					} else {
						echo "
						<ul>
							<li><input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value_{$ttc}(this);' /><input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"".htmlspecialchars( $test_field_input_array[ $i ][0][0], ENT_QUOTES )."\" oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' /></li>
							<li><input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value_{$ttc}(this);' /><input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"".htmlspecialchars( $test_field_input_array[ $i ][0][1], ENT_QUOTES )."\" oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' /></li>";
						echo "</ul><ul><li>";
						if ( $i > 0 ) {
							echo "<input class='go_button_del_field go_test_field_rm_row_button_{$ttc} go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_{$ttc}(this);' /></li><li>";
						}
						echo "<input class='go_test_field_input_count_{$ttc}' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' /></li></ul>";
					}
					echo "
						</td>
					</tr>";
				}
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_{$ttc}_0' class='go_test_field_input_row_{$ttc} go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_{$ttc}_0' class='go_test_field_input_select_{$ttc}' name='go_test_field_select_{$ttc}[]' onchange='update_checkbox_type_{$ttc}(this);'>
							<option value='radio' class='go_test_field_input_option_{$ttc}'>Multiple Choice</option>
							<option value='checkbox' class='go_test_field_input_option_{$ttc}'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_{$ttc} go_test_field_input_question' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value_{$ttc}(this);' />
								<input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value_{$ttc}(this);' />
								<input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' />
							</li>
							<input class='go_button_add_field go_test_field_add go_test_field_add_input_button_{$ttc}' type='button' value='+' onclick='add_field_{$ttc}(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count_{$ttc}' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
		}
		?>
		<tr>
			<td>
				<input id='go_test_field_add_block_button_<?php echo $ttc; ?>' class='go_button_add_field go_test_field_add_block_button' value='Add Question' type='button' onclick='add_block_<?php echo $ttc; ?>(this);' />
				<?php 
				if ( ! empty( $test_field_block_count ) ) {
					echo "<input id='go_test_field_block_count_{$ttc}' name='go_test_field_block_count_{$ttc}' type='hidden' value='{$test_field_block_count}' />";
				} else {
					echo "<input id='go_test_field_block_count_{$ttc}' name='go_test_field_block_count_{$ttc}' type='hidden' value='1' />";
				}
				?>
			</td>
		</tr>
	</table>
	<script type='text/javascript'>
		var block_num_<?php echo $ttc; ?> = 0;
		var block_type_<?php echo $ttc; ?> = 'radio';
		var input_num_<?php echo $ttc; ?> = 0;
		var block_count_<?php echo $ttc; ?> = <?php echo ( ! empty( $test_field_block_count ) ? $test_field_block_count : 1); ?>;
		
		var test_field_select_array_<?php echo $ttc; ?> = new Array(
			<?php 
			if ( ! empty( $test_field_block_count ) ) {
				for ( $i = 0; $i < $test_field_block_count; $i++ ) {
					if ( ! empty( $test_field_select_array[ $i ] ) ) {
						echo '"'.ucwords( $test_field_select_array[ $i ] ).'"';
						if ( ( $i + 1 ) !== $test_field_block_count &&
								! empty( $test_field_select_array[ $i + 1 ] ) ) {
							echo ', ';
						}
					}
				}
			}
			?>
		);
		var test_field_checked_array_<?php echo $ttc; ?> = [
			<?php
			if ( ! empty( $test_field_block_count ) ) {
				for ( $x = 0; $x < $test_field_block_count; $x++ ) {
					echo "[";
					if ( ! empty( $test_field_input_array[ $x ][0] ) && ! empty( $test_field_input_array[ $x ][1] ) ) {
						$intersection = array_intersect( $test_field_input_array[ $x ][0], $test_field_input_array[ $x ][1] );
						$checked_intersection = array_values( $intersection );
						for ( $i = 0; $i < count( $checked_intersection ); $i++ ) {

							// $test_field_input_array[ $x][0] contains raw strings, the test field data isn't encoded
							// when it's saved.
							echo '"'.addslashes( $checked_intersection[ $i ] ).'"';
							if ( ( $i ) < count( $checked_intersection ) ) {
								echo ", ";
							}
						}
					}
					echo "]";
					if ( ( $x + 1 ) < $test_field_block_count ) {
						echo ", ";
					}
				}
			}
			?>
		];
		for ( var i = 0; i < test_field_select_array_<?php echo $ttc; ?>.length; i++ ) {
			var test_field_with_select_value = '#go_test_field_select_<?php echo $ttc; ?>_'+i+' .go_test_field_input_option_<?php echo $ttc; ?>:contains(\'' + test_field_select_array_<?php echo $ttc; ?>[ i ]+'\' )';
			jQuery(test_field_with_select_value).attr( 'selected', true );
		}
		for ( var x = 0; x < block_count_<?php echo $ttc; ?>; x++ ) {
			if ( test_field_checked_array_<?php echo $ttc; ?>.length !== 0 ) {
				for ( var z = 0; z < test_field_checked_array_<?php echo $ttc; ?>[ x ].length; z++ ) {

					// Looping through all the test fields in a row is neccessary, since checking for inputs with a 'value'
					// attribute containing one or more HTML tags doesn't return the input (it returns the HTML element
					// inside the 'value' attribute, which doesn't contain a reference to it's parent node).
					jQuery( "tr#go_test_field_input_row_<?php echo $ttc; ?>_" + [ x ] + " .go_test_field_input_<?php echo $ttc; ?>" ).each( function( ind ) {
						if ( test_field_checked_array_<?php echo $ttc; ?>[ x ][ z ] === this.value ) {
							jQuery( this ).siblings( '.go_test_field_input_checkbox_<?php echo $ttc; ?>' ).attr( 'checked', true );
							return false;
						}
					});
				}
			}
		}
		var checkbox_obj_array = jQuery( '.go_test_field_input_checkbox_<?php echo $ttc; ?>' );
		for ( var y = 0; y < checkbox_obj_array.length; y++ ) {
			var next_obj = checkbox_obj_array[ y ].nextElementSibling;
			if ( checkbox_obj_array[ y ].checked ) {
				var input_obj = next_obj.nextElementSibling.value;
				jQuery( next_obj ).attr( 'value', input_obj );
			} else {
				jQuery( next_obj ).removeAttr( 'value' );
			}
		}
		function update_checkbox_value_<?php echo $ttc; ?> ( target ) {
			if ( jQuery( target ).hasClass( 'go_test_field_input_<?php echo $ttc; ?>' ) ) {
				var obj = jQuery( target ).siblings( '.go_test_field_input_checkbox_<?php echo $ttc; ?>' );
			} else {
				var obj = target;
			}
			var checkbox_type = jQuery( obj ).prop( 'type' );
			var input_field_val = jQuery( obj ).siblings( '.go_test_field_input_<?php echo $ttc; ?>' ).val();
			if ( checkbox_type === 'radio' ) {
				var radio_name = jQuery( obj ).prop( 'name' );
				var radio_checked_str = ".go_test_field_input_checkbox_<?php echo $ttc; ?>[name='" + radio_name + "']:checked";
				if ( jQuery( obj ).prop( 'checked' ) ) {
					if ( input_field_val != '' ) {
						jQuery( radio_checked_str ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).attr( 'value', input_field_val );
					} else {
						jQuery( radio_checked_str ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
					}
				} else {
					jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
				}
				var radios_not_checked_str = ".go_test_field_input_checkbox_<?php echo $ttc; ?>[name='" + radio_name + "']:not(:checked)";
				jQuery( radios_not_checked_str ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
			} else {
				if ( jQuery( obj ).prop( 'checked' ) ) {
					if ( input_field_val != '' ) {
						jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).attr( 'value', input_field_val );	
					} else {
						jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
					}
				} else {
					jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
				}
			}
		}
		function update_checkbox_type_<?php echo $ttc; ?> ( obj ) {
			block_type_<?php echo $ttc; ?> = jQuery( obj ).children( 'option:selected' ).val();
			jQuery( obj ).siblings( 'ul' ).children( 'li' ).children( 'input.go_test_field_input_checkbox_<?php echo $ttc; ?>' ).attr( 'type', block_type_<?php echo $ttc; ?> );
		}
		function add_block_<?php echo $ttc; ?> ( obj ) {
			block_num_<?php echo $ttc; ?> = jQuery( obj ).parents( 'tr' ).siblings( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).length;
			jQuery( '#go_test_field_block_count_<?php echo $ttc; ?>' ).attr( 'value', ( block_num_<?php echo $ttc; ?> + 1 ) );
			var field_block = "<tr id='go_test_field_input_row_<?php echo $ttc; ?>_"+block_num_<?php echo $ttc; ?> + "' class='go_test_field_input_row_<?php echo $ttc; ?> go_test_field_input_row'><td><select id='go_test_field_select_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?> + "' class='go_test_field_input_select_<?php echo $ttc; ?>' name='go_test_field_select_<?php echo $ttc; ?>[]' onchange='update_checkbox_type_<?php echo $ttc; ?>(this);'><option value='radio' class='go_test_field_input_option_<?php echo $ttc; ?>'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option_<?php echo $ttc; ?>'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question_<?php echo $ttc; ?> go_test_field_input_question' name='go_test_field_input_question_<?php echo $ttc; ?>[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox_<?php echo $ttc; ?> go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?>+"' type='" + block_type_<?php echo $ttc; ?> + "' onchange='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][1][]' type='hidden' /><input class='go_test_field_input_<?php echo $ttc; ?> go_test_field_input' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_<?php echo $ttc; ?>(this);' oncut='update_checkbox_value_<?php echo $ttc; ?>(this);' onpaste='update_checkbox_value_<?php echo $ttc; ?>(this);' /></li><li><input class='go_test_field_input_checkbox_<?php echo $ttc; ?> go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?> + "' type='" + block_type_<?php echo $ttc; ?> + "' onchange='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][1][]' type='hidden' /><input class='go_test_field_input_<?php echo $ttc; ?> go_test_field_input' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_<?php echo $ttc; ?>(this);' oncut='update_checkbox_value_<?php echo $ttc; ?>(this);' onpaste='update_checkbox_value_<?php echo $ttc; ?>(this);' /></li><input class='go_button_add_field go_test_field_add go_test_field_add_input_button_<?php echo $ttc; ?>' type='button' value='+' onclick='add_field_<?php echo $ttc; ?>(this);'/></ul><ul><li><input class='go_button_del_field go_test_field_rm_row_button_<?php echo $ttc; ?> go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_count_<?php echo $ttc; ?>' name='go_test_field_input_count_<?php echo $ttc; ?>[]' type='hidden' value='2' /></li></ul></td></tr>";
			jQuery( obj ).parent().parent().before( field_block );
		}
		function remove_block_<?php echo $ttc; ?> ( obj ) {
			block_num_<?php echo $ttc; ?> = jQuery( obj ).parents( 'tr' ).siblings( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).length;
			jQuery( '#go_test_field_block_count_<?php echo $ttc; ?>' ).attr( 'value', ( block_num_<?php echo $ttc; ?> - 1 ) );
			jQuery( obj ).parents( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).remove();
		}
		function add_field_<?php echo $ttc; ?> ( obj ) {
			input_num_<?php echo $ttc; ?> = jQuery( obj ).siblings( 'li' ).length + 1;
			var block_id = jQuery( obj ).parents( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).first().attr( 'id' );
			block_num_<?php echo $ttc; ?> = block_id.split( 'go_test_field_input_row_<?php echo $ttc; ?>_' ).pop();
			block_type_<?php echo $ttc; ?> = jQuery( obj ).parent( 'ul' ).siblings( 'select' ).children( 'option:selected' ).val();
			jQuery( obj ).parent( 'ul' ).siblings( 'ul' ).children( 'li' ).children( '.go_test_field_input_count_<?php echo $ttc; ?>' ).attr( 'value', input_num_<?php echo $ttc; ?> );
			jQuery( obj ).siblings( 'li' ).last().after( "<li><input class='go_test_field_input_checkbox_<?php echo $ttc; ?> go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?> + "' type='" + block_type_<?php echo $ttc; ?> + "' onchange='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?>+"][1][]' type='hidden' /><input class='go_test_field_input_<?php echo $ttc; ?> go_test_field_input' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?>+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_<?php echo $ttc; ?>(this);' oncut='update_checkbox_value_<?php echo $ttc; ?>(this);' onpaste='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_button_del_field go_test_field_rm go_test_field_rm_input_button_<?php echo $ttc; ?>' type='button' value='x' onclick='remove_field_<?php echo $ttc; ?>(this);'></li>" );
		}
		function remove_field_<?php echo $ttc; ?> ( obj ) {
			jQuery( obj ).parents( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).find( 'input.go_test_field_input_count_<?php echo $ttc; ?>' )[0].value--;
			jQuery( obj ).parent( 'li' ).remove();
		}
	</script>
	<?php
}

add_action( 'cmb_validate_go_test_field', 'go_validate_test_field', 10, 3);
function go_validate_test_field( $unused_override_value, $unused_value, $field_args ) {
	$ttc = $field_args['test_type'];

	$question_temp 		= ( ! empty( $_POST["go_test_field_input_question_{$ttc}"] )	? $_POST["go_test_field_input_question_{$ttc}"] : null );
	$test_temp 			= ( ! empty( $_POST["go_test_field_values_{$ttc}"] ) 			? $_POST["go_test_field_values_{$ttc}"] : null );
	$select 			= ( ! empty( $_POST["go_test_field_select_{$ttc}"] ) 			? $_POST["go_test_field_select_{$ttc}"] : null );
	$block_count 		= ( ! empty( $_POST["go_test_field_block_count_{$ttc}"] ) 		? (int) $_POST["go_test_field_block_count_{$ttc}"] : null );
	$input_count_temp 	= ( ! empty( $_POST["go_test_field_input_count_{$ttc}"] ) 		? $_POST["go_test_field_input_count_{$ttc}"] : null );

	$input_count = array();
	if ( ! empty( $input_count_temp ) ) {
		foreach ( $input_count_temp as $key => $value ) {
			$temp = (int) $input_count_temp[ $key ];
			array_push( $input_count, $temp );
		}
	}

	$question = array();
	if ( ! empty( $question_temp ) && is_array( $question_temp ) ) {
		foreach ( $question_temp as $value ) {
			if ( ! is_null( $value ) && preg_match( "/\S+/", $value ) ) {
				$question[] = $value;
			}
		}
	} else {
		$question = $question_temp;
	}

	$test = array();
	if ( ! empty( $test_temp ) ) {
		for ( $f = 0; $f < count( $test_temp ); $f++ ) {
			if ( ! empty( $test_temp[ $f ][0] ) && is_array( $test_temp[ $f ][0] ) ) {
				$temp_input = $test_temp[ $f ][0];
				foreach ( $temp_input as $value ) {
					if ( ! is_null( $value ) && preg_match( "/\S+/", $value ) ) {
						$test[ $f ][0][] = $value;
					} else {
						if ( $input_count[ $f ] > 2) {
							$input_count[ $f ]--;
						}
					}
				}
			}

			if ( ! empty( $test_temp[ $f ][1] ) && is_array( $test_temp[ $f ][1] ) ) {
				$temp_checked = $test_temp[ $f ][1];
				foreach ( $temp_checked as $value ) {
					if ( ! is_null( $value ) && preg_match( "/\S+/", $value ) ) {
						$test[ $f ][1][] = $value;
					}
				}
			}
		}
	}
	
	return( array( $question, $test, $select, $block_count, $input_count ) );
}

add_action( 'cmb_render_go_repeat_amount', 'go_repeat_amount' );
function go_repeat_amount() {
	$custom = get_post_custom();
	$content = ( ! empty( $custom['go_mta_repeat_amount'][0] ) ? $custom['go_mta_repeat_amount'][0] : null );
	if ( is_null( $content ) ) {
		$value = 1;
	} else {
		$value = $content[0];
	}
	echo "<input id='go_repeat_amount_input' name='go_mta_repeat_amount' type='text' ".( ! empty( $value ) ? "value='{$value}'" : '' )."/>";
}

add_filter( 'template_include', 'go_tasks_template_function', 1 );
function go_tasks_template_function( $template_path ) {
	if ( get_post_type() == 'tasks' ) {
		if ( is_single() ) {
			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			
			
			
			if ( $theme_file = locate_template( array (  'index.php' ) )
		//$theme_file =	get_page_template()
		 ) {
				$template_path = $theme_file;
				add_filter( 'the_content', 'go_tasks_filter_content' );
			} 
		}
	}
	return $template_path;
}

function go_tasks_filter_content() {
	echo do_shortcode( '[go_task id="'.get_the_id().'"]' );
}
	 
function go_create_help_video_lb() {
	?>
	<div class="dark" style="display: none;"> </div>
	<div class="light" style="display: none; <?php if ( is_admin() ) {?> height: 540px; width: 864px; margin: -270px 0 0 -432px;<?php } else { ?>height: <?php echo ( go_return_options( 'go_video_height' ) ) ?go_return_options( 'go_video_height' ) : '540';?>px; width: <?php echo ( go_return_options( 'go_video_width' ) ) ? go_return_options( 'go_video_width' ) : '864' ;?>px; margin: <?php echo ( ( go_return_options( 'go_video_width' ) ) ? "-".( go_return_options( 'go_video_height' ) / 2) : "-270" )."px 0 0 ".( ( go_return_options( 'go_video_width' ) ) ? "-".( go_return_options( 'go_video_width' ) / 2):"-432" )."px;"; } ?>">
		<div id="go_help_video_container" style="height: 100%; width: 100%;">
			<video id="go_option_help_video" class="video-js vjs-default-skin vjs-big-play-centered" controls height="100%" width="100%" ><source src="" type="video/mp4"/></video/options>
		</div>
	</div>
	<?php 
}
add_action( 'admin_head', 'go_create_help_video_lb' );
add_action( 'wp_head', 'go_create_help_video_lb' );

function go_task_opt_help( $field, $title, $video_url = null ) {
	return "<a id='go_help_{$field}' class='go_task_opt_help' onclick='go_display_help_video( ".esc_attr( '\''.$video_url.'\'' )." );' tooltip='{$title}'>?</a>";
}

add_action( 'cmb_render_go_task_chain_order', 'go_render_task_chain_order' );
function go_render_task_chain_order() {
	global $post;
	$task_chains = get_the_terms( $post->ID, 'task_chains' );

	if ( ! empty( $task_chains ) ) {
		$chain_terms_array = array_values( $task_chains );
		$stored_chain_order = get_post_meta( $post->ID, 'go_mta_chain_order', true );

		foreach ( $chain_terms_array as $chain_term ) {
			$tasks_in_chain = array();
			$tt_id = $chain_term->term_taxonomy_id;

			if ( ! empty( $stored_chain_order[ $tt_id ] ) ) {
				if ( ! is_array( $stored_chain_order[ $tt_id ] ) ) {
					$chain_order = explode( ',', $stored_chain_order[ $tt_id ] );
				} else {
					$chain_order = $stored_chain_order[ $tt_id ];
				}
				foreach ( $chain_order as $post->ID ) {
					if ( ! empty( $post->ID ) ) {
						$tasks_in_chain[] = get_post( $post->ID );
					}
				}
			} else {
				$tasks_in_chain = go_task_chain_get_tasks( $tt_id );
			}

			$task_id_array = array();
			$task_id_str = null;
			$chain_list_elems = '';
			foreach ( $tasks_in_chain as $index => $post_obj ) {
				$task_id_array[] = $post_obj->ID;
				$post_status = $post_obj->post_status;

				$elem_val = $post_obj->post_title . (
					'publish' !== $post_status ?
					' (' . ucwords( $post_status ) . ')' :
					''
				);

				$chain_list_elems .= sprintf(
					'<li class="go_task_in_chain" post_id=%d>%s</li>',
					$post_obj->ID,
					$elem_val
				);
			}

			$task_id_str = join( ',', $task_id_array );

			printf(
				'<div class="go_task_chain_order_container">'.
					'<div class="go_task_chain_order_title"><strong>%s</strong></div>'.
					'<ul class="go_sortable go_task_chain_order_list">%s</ul>'.
					'<input class="go_task_order_hidden" name="go_mta_chain_order[%d]" '.
						'type="hidden" value="%s"/>'.
				'</div>',
				ucwords( $chain_term->name ),
				$chain_list_elems,
				$tt_id,
				$task_id_str
			);
		}
	}
}

/**
 * Updates the task chain order meta data of each task in a chain, as needed.
 *
 * Only updates the task's chain order if the stored chain order and the new chain order values are
 * different. Terms that the task doesn't have an object-term relationship with will be removed from
 * the chain order. If the task's chain order does need updating, all other tasks within the chain
 * will be updated with the new chain order meta data. This ensures that all tasks display the
 * correct order.
 *
 * This function is not meant to be called in any way other than through its `cmb_validate_*` hook.
 *
 * @since 2.6.1
 *
 * @global $post
 *
 * @param array $new_values The new chain order array.
 * @return array If changes occurred, the updated chain order array is returned (saved); otherwise,
 *               the current meta value is used.
 */
function go_validate_task_chain_order( $new_values ) {
	global $post;

	/**
	 * The chain order should be of the form below. The task IDs are lumped into a string due to the
	 * way that form inputs pass data via POST.
	 *
	 *     array(
	 *         [tt_id #1] => '[post_id #1],[post_id #2],[post_id #3]',
	 *         ...
	 *     )
	 *
	 *     e.g.
	 *     array(
	 *         892 => '123,204,347',
	 *         ...
	 *     )
	 */
	$new_order_input    = ( ! empty( $new_values ) ? $new_values : array() );
	$task_chains        = get_the_terms( $post->ID, 'task_chains' );
	$stored_chain_order = get_post_meta( $post->ID, 'go_mta_chain_order', true );
	$new_chain_order    = $stored_chain_order;

	if ( empty( $new_order_input ) || empty( $task_chains ) ) {
		return $stored_chain_order;
	}

	foreach ( $new_order_input as $tt_id => $chain_order_str ) {

		$in_chain = false;

		foreach ( $task_chains as $chain_obj ) {
			if ( $chain_obj->term_taxonomy_id === $tt_id ) {
				$in_chain = true;
				break;
			}
		}

		// splits the list of task IDs into an array
		$chain_order = explode( ',', $chain_order_str );

		// duplicates the chain order array; this makes looping through the chain order array and
		// removing task IDs that do not belong possible without skipping elements
		$chain_order_dupe = $chain_order;

		if ( ! empty( $chain_order ) && $in_chain ) {
			if ( empty( $stored_chain_order ) ||
					( ! empty( $stored_chain_order[ $tt_id ] ) &&
					$stored_chain_order[ $tt_id ] !== $chain_order ) ) {

				foreach ( $chain_order_dupe as $index => $task_id ) {

					// typecasts the task IDs to ensure that they are actually integers; the benefit
					// being that an entire loop isn't being created just for typecasting the IDs
					$task_id = (int) $task_id;
					$chain_order[ $index ] = $task_id;

					if ( $task_id !== $post->ID ) {
						$is_in_target_chain = false;
						$other_task_chains = get_the_terms( $task_id, 'task_chains' );

						if ( ! empty( $other_task_chains ) ) {
							foreach ( $other_task_chains as $chain ) {
								if ( $chain->term_taxonomy_id === $tt_id ) {
									$is_in_target_chain = true;
									break;
								}
							}
						}

						$other_chain_order = get_post_meta( $task_id, 'go_mta_chain_order', true );

						if ( $is_in_target_chain &&
								( empty( $other_chain_order[ $tt_id ] ) ||
								$other_chain_order[ $tt_id ] !== $chain_order ) ) {

							// updates/adds the chain ID and order pair to the chain order array,
							// if the task is actually in the chain and the order hasn't been updated
							$other_chain_order[ $tt_id ] = $chain_order;
						} elseif ( ! $is_in_target_chain && ! empty( $other_chain_order[ $tt_id ] ) ) {

							// removes the chain ID and order pair from the task's chain order array,
							// if the task isn't in the chain and still has the chain's order in
							// its meta data
							unset( $other_chain_order[ $tt_id ] );
						}

						// ensures that tasks get removed from the chain order array when necessary
						if ( empty( $other_chain_order[ $tt_id ] ) ) {
							$task_pos = array_search( $task_id, $chain_order );

							// removes the ID of the task (that is no longer in the chain) from the
							// chain order array of the target
							unset( $chain_order[ $task_pos ] );
						}

						// converts all values in the task's chain order (for a specific chain) to ints
						if ( ! empty( $other_chain_order[ $tt_id ] ) && is_array( $other_chain_order[ $tt_id ] ) ) {
							foreach ( $other_chain_order[ $tt_id ] as $other_order_index => $other_task_id ) {
								$other_chain_order[ $tt_id ][ $other_order_index ] = (int) $other_task_id;
							}
						}

						// updates the task's chain order
						update_post_meta( $task_id, 'go_mta_chain_order', $other_chain_order );
					}
				}
			}
			$new_chain_order[ $tt_id ] = $chain_order;
		}
	}

	return $new_chain_order;
}
add_action( 'cmb_validate_go_task_chain_order', 'go_validate_task_chain_order', 10, 1 );

add_action( 'cmb_render_go_settings_accordion', 'go_settings_accordion', 10, 1);
function go_settings_accordion( $field_args ) {
	echo "
		<div id='{$field_args['settings_id']}' class='go_task_settings_accordion'>
			<strong>{$field_args['message']}</strong>
			<div class='go_triangle_container'>
				<div class='go_task_accordion_triangle'></div>
			</div>
		</div>";
}

add_action( 'cmb_render_go_stage_reward', 'go_stage_reward' );
function go_stage_reward( $field_args ) {
	$custom = get_post_custom();
	if ( empty( $custom['go_presets'][0] ) ) {
		$presets = get_option( 'go_presets' );
		$points = $presets['points'];
		$currency = $presets['currency'];

		$rewards = array(
			'points' => $points[0],
			'currency' => $currency[0],
			'bonus_currency' => array( 0, 0, 0, 0, 0 ),
		);
	} else {
		$rewards = unserialize( $custom['go_presets'][0] );
	}

	$stage_num   = $field_args['stage'];
	$reward_type = $field_args['reward'];

	echo "<div id='stage_{$stage_num}'>";
	if ( $rewards ) {
		for ( $i = 1; $i <= 5; $i++ ) {

			$input_name = "stage_{$stage_num}_{$reward_type}[" . ( $i - 1 ) . ']';
			$input_classes = "go_reward_input go_reward_{$reward_type} go_reward_{$reward_type}_{$i}";
			if ( $stage_num === $i ) {
				$input_classes .= ' go_current';
			}

			$input_val = 0;
			if ( 'points' === $reward_type && ! empty( $rewards['points'][ $i - 1 ] ) ) {
				$input_val = (int) $rewards['points'][ $i - 1 ];
			} elseif ( 'currency' === $reward_type && ! empty( $rewards['currency'][ $i - 1 ] ) ) {
				$input_val = (int) $rewards['currency'][ $i - 1 ];
			} elseif ( 'bonus_currency' === $reward_type && ! empty( $rewards['bonus_currency'][ $i - 1 ] ) ) {
				$input_val = (int) $rewards['bonus_currency'][ $i - 1 ];
			}

			printf(
				'<input type="text" name="%s" class="%s" value=%d stage=%d reward="%s"/>',
				$input_name,
				$input_classes,
				$input_val,
				$i,
				$reward_type
			);
		}
	}
	echo '</div>';
}

add_action( 'post_submitbox_misc_actions', 'go_clone_post_ajax' );
function go_clone_post_ajax() {
	global $post;
	$post_type = get_post_type( $post );
	$nonce = wp_create_nonce( 'go_clone_post_' . $post->ID );

	// When the "Clone" button is pressed, send an ajax call to the go_clone_post() function to
	// clone the post using the sent post id and post type.
	echo "
	<div class='misc-pub-section misc-pub-section-last'>
		<input id='go-button-clone' class='button button-large alignright' type='button' value='Clone' />
	</div>
	<script type='text/javascript'>        	
		function clone_post_ajax() {
			jQuery( 'input#go-button-clone' ).click(function() {
				jQuery( 'input#go-button-clone' ).prop( 'disabled', true );
				jQuery.ajax({
					url: '".admin_url( 'admin-ajax.php' )."',
					type: 'POST',
					data: {
						_ajax_nonce: '{$nonce}',
						action: 'go_clone_post',
						post_id: {$post->ID},
						post_type: '{$post_type}'
					}, success: function( res ) {
						if ( -1 !== res && '' !== res ) {
							var reg = new RegExp( \"^(http)\" );
							var url_match = reg.test( res );
							if ( url_match ) {
								window.location = res;
							}
						}
					}
				});
			});
		}
		jQuery( document ).ready(function() {
			clone_post_ajax();
		});
	</script>
	";
}

function go_clone_post() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		die( -1 );
	}

	// Grab the post id from the ajax call and use it to grab data from the original post.
	$post_id = ( ! empty( $_POST['post_id'] ) ? (int) $_POST['post_id'] : get_the_ID() );

	// verify the nonce passed in the AJAX request
	check_ajax_referer( 'go_clone_post_' . $post_id );

	$post_type = ( ! empty( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : '' );
	$post_data = get_post( $post_id, ARRAY_A );
	$post_custom = get_post_custom( $post_id );

	// Grab the original post's taxonomies.
	if ( 'tasks' == $post_type ) {
		$terms = get_the_terms( $post_id, 'task_chains' );
		$foci = get_the_terms( $post_id, 'task_focus_categories' );
		$cat = get_the_terms( $post_id, 'task_categories' );
		$pods = get_the_terms( $post_id, 'task_pods' );
		$term_ids = array();
		$focus_ids = array();
		$pod_ids = array();
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $key => $term ) {
				if ( ! empty( $term->term_id ) ) {
					$term_ids[] = $term->term_id;
				}
			}
		}
		if ( ! empty( $foci ) ) {
			foreach ( $foci as $key => $focus_term ) {
				if ( ! empty( $focus_term->term_id ) ) {
					$focus_ids[] = $focus_term->term_id;
				}
			}
		}
		if ( ! empty( $pods ) ) {
			foreach ( $pods as $key => $pod_term ) {
				if ( ! empty( $pod_term->term_id ) ) {
					$pod_ids[] = $pod_term->term_id;
				}
			}
		}
	} elseif ( 'go_store' == $post_type ) {
		$cat = get_the_terms( $post_id, 'store_types' );
	} else {
		$cat = get_the_terms( $post_id, 'category' );
	}

	$cat_ids = array();
	if ( ! empty( $cat ) && is_array( $cat ) ) {
		foreach ( $cat as $key => $cat_term ) {
			if ( ! empty( $cat_term->term_id ) ) {
				$cat_ids[] = $cat_term->term_id;
			}
		}
	}

	// Change the post status to "draft", leave the guid up to Wordpress,
	// and remove all other post data.
	$post_data['post_status'] = 'draft';
	$post_data['guid'] = '';
	unset( $post_data['ID'] );
	unset( $post_data['post_title'] );
	unset( $post_data['post_name'] );
	unset( $post_data['post_modified'] );
	unset( $post_data['post_modified_gmt'] );
	unset( $post_data['post_date'] );
	unset( $post_data['post_date_gmt'] );

	// Clone the original post with the modified data from above, and retrieve the new post's id.
	$clone_id = wp_insert_post( $post_data );

	// Set the cloned post's taxonomies using the ids from above.
	if ( 'tasks' == $post_type ) {
		wp_set_object_terms( $clone_id, $term_ids, 'task_chains' );
		wp_set_object_terms( $clone_id, $focus_ids, 'task_focus_categories' );
		wp_set_object_terms( $clone_id, $cat_ids, 'task_categories' );
		wp_set_object_terms( $clone_id, $pod_ids, 'task_pods' );
	} elseif ( 'go_store' == $post_type ) {
		wp_set_object_terms( $clone_id, $cat_ids, 'store_types' );
	} else {
		wp_set_object_terms( $clone_id, $cat_ids, 'category' );
	}

	if ( ! empty( $clone_id ) ) {
		$url = admin_url( "post.php?post={$clone_id}&action=edit" );

		// Add the original post's meta data to the clone.
		foreach ( $post_custom as $key => $value ) {
			$uns = maybe_unserialize( $value[0] );

			add_post_meta( $clone_id, $key, $uns, true );
		}
		echo $url;
	} else {
		echo -1;
	}
	die();
}

add_action( 'cmb_render_go_store_item_post_id', 'go_store_item_post_id' );
function go_store_item_post_id() {
	echo "<div id='go_store_id'>".get_the_id()."</div>";
}

add_action( 'cmb_render_go_store_cost', 'go_store_cost' );
function go_store_cost() {
	$custom = get_post_custom();
	$cost_array = ( ! empty( $custom['go_mta_store_cost'][0] ) ? unserialize( $custom['go_mta_store_cost'][0] ) : null );
	if ( ! empty( $cost_array ) ) {
		$go_currency_cost = $cost_array[0];
		$go_point_cost = $cost_array[1];
		$go_bonus_currency_cost = $cost_array[2];
		$go_penalty_cost = $cost_array[3];
		$go_minutes_cost = $cost_array[4];
	}
	echo "
		<input class='go_store_cost_input' name='go_currency_cost' type='text' placeholder='".go_return_options( 'go_currency_name' )."'".( ! empty( $go_currency_cost ) ? "value='{$go_currency_cost}'" : '' )."/>
		<input class='go_store_cost_input' name='go_point_cost' type='text' placeholder='".go_return_options( 'go_points_name' )."'".( ! empty( $go_point_cost ) ? "value='{$go_point_cost}'" : '' )."/>
		<input class='go_store_cost_input' name='go_bonus_currency_cost' type='text' placeholder='".go_return_options( 'go_bonus_currency_name' )."'".( ! empty( $go_bonus_currency_cost ) ? "value='{$go_bonus_currency_cost}'" : '' )."/>
		<input class='go_store_cost_input' name='go_penalty_cost' type='text' placeholder='".go_return_options( 'go_penalty_name' )."'".( ! empty( $go_penalty_cost ) ? "value='{$go_penalty_cost}'" : '' )." />
		<input class='go_store_cost_input' name='go_minutes_cost' type='text' placeholder='".go_return_options( 'go_minutes_name' )."'".( ! empty( $go_minutes_cost ) ? "value='{$go_minutes_cost}'" : '' )."/>
	";
}

add_action( 'cmb_validate_go_store_cost', 'go_validate_store_cost' );
function go_validate_store_cost() {
	$go_currency_cost       = ( ! empty( $_POST['go_currency_cost'] )       ? (int) $_POST['go_currency_cost'] : 0 );
	$go_point_cost          = ( ! empty( $_POST['go_point_cost'] )          ? (int) $_POST['go_point_cost'] : 0 );
	$go_bonus_currency_cost = ( ! empty( $_POST['go_bonus_currency_cost'] ) ? (int) $_POST['go_bonus_currency_cost'] : 0 );
	$go_penalty_cost        = ( ! empty( $_POST['go_penalty_cost'] )        ? (int) $_POST['go_penalty_cost'] : 0 );
	$go_minutes_cost        = ( ! empty( $_POST['go_minutes_cost'] )        ? (int) $_POST['go_minutes_cost'] : 0 );

	return array(
		$go_currency_cost,
		$go_point_cost,
		$go_bonus_currency_cost,
		$go_penalty_cost,
		$go_minutes_cost,
	);
}

add_action( 'cmb_render_go_store_limit', 'go_store_limit' );
function go_store_limit() {
	$custom = get_post_custom();
	$content_array = ( ! empty( $custom['go_mta_store_limit'][0] ) ? unserialize( $custom['go_mta_store_limit'][0] ) : null );

	// turn the option on by default
	if ( ! isset( $content_array[0] ) ) {
		$content_array[0] = true;
	}
	$is_checked = ( ! empty( $content_array[0] ) ? 'checked' : '' );
	$limit = ( ! empty( $content_array[1] ) ? "value='{$content_array[1]}'" : '' );
	echo "
		<input id='go_store_limit_checkbox' name='go_store_limit' type='checkbox' {$is_checked}/>
		<input id='go_store_limit_input' name='go_store_limit_input' type='text' style='display: none;' placeholder='Limit' {$limit}/>
	";
}

add_action( 'cmb_validate_go_store_limit', 'go_validate_store_limit' );
function go_validate_store_limit() {
	$is_checked = ( ! empty( $_POST['go_store_limit'] )       ? true : false );
	$limit 		= ( ! empty( $_POST['go_store_limit_input'] ) ? sanitize_text_field( $_POST['go_store_limit_input'] ) : '' );

	return array( $is_checked, $limit );
}

add_action( 'cmb_render_go_store_focus', 'go_store_focus' );
function go_store_focus() {
	$custom = get_post_custom();
	$content_array = ( ! empty( $custom['go_mta_store_focus'][0] ) ? unserialize( $custom['go_mta_store_focus'][0] ) : null );
	$is_checked = ( ! empty( $content_array[0] ) ? 'checked' : '' );
	$profession = $content_array[1];
	$user_id = get_current_user_id();
	$focus_switch = go_return_options( 'go_focus_switch' );
	
	if ( $focus_switch == 'On' ) {
		$go_foci = get_option( 'go_focus' );
		if ( ! empty( $go_foci ) ) {
			if ( count( $go_foci ) > 1 || ( count( $go_foci ) == 1 && ! empty( $go_foci[0] ) ) ) {
				echo "
					<input id='go_store_focus_checkbox' name='go_mta_store_focus' type='checkbox' {$is_checked}/>
					<select id='go_store_focus_select' name='go_store_focus_select' style='display: none;'>
				";
				foreach ( $go_foci as $key => $focus ) {
					echo "<option class='go_store_focus_option'";
					if ( ! empty( $profession ) && strtolower( $focus ) == strtolower( $profession ) ) {
						echo 'selected';
					}
					echo ">{$focus}</option>";
				}
				echo "</select>";
			} else {
				echo "<p>No names were found in the ".go_return_options( 'go_focus_name' )." section in the <a href='".admin_url()."/?page=game-on-options.php'>Game-On options</a>.</p>";
			}
		}
	} else {
		echo "<p>The ".go_return_options( 'go_focus_name' )." option is disabled in the <a href='".admin_url()."/?page=game-on-options.php'>Game-On options</a>.</p>";
	}
}

add_action( 'cmb_validate_go_store_focus', 'go_validate_store_focus' );
function go_validate_store_focus() {
	$is_checked   = ( ! empty( $_POST['go_mta_store_focus'] )    ? true : false );
	$focus_select = ( ! empty( $_POST['go_store_focus_select'] ) ? sanitize_text_field( $_POST['go_store_focus_select'] ) : '' );

	return array( $is_checked, $focus_select );
}

add_action( 'cmb_render_go_store_receipt', 'go_store_receipt' );
function go_store_receipt() {
	$custom = get_post_custom();
	$store_receipt_option = get_option( 'go_store_receipt_switch' );

	// turn the option on by default
	if ( ! isset( $custom['go_mta_store_receipt'][0] ) ) {
		$custom['go_mta_store_receipt'][0] = true;
	}
	$is_checked = ( ! empty( $custom['go_mta_store_receipt'][0] ) ? 'checked' : '' );

	echo "<input id='go_store_receipt_checkbox' name='go_store_receipt' type='checkbox' {$is_checked}/>";
}

add_action( 'cmb_validate_go_store_receipt', 'go_validate_store_receipt' );
function go_validate_store_receipt() {
	$is_checked = ( ! empty( $_POST['go_store_receipt'] ) ? true : false );

	return $is_checked;
}

add_action( 'cmb_render_go_store_filter', 'go_store_filter' );
function go_store_filter() {
	$custom = get_post_custom();
	$content_array = ( ! empty( $custom['go_mta_store_filter'][0] ) ? unserialize( $custom['go_mta_store_filter'][0] ) : null );
	$is_checked = ( ! empty( $content_array[0] ) ? 'checked' : '' );
	$chosen_rank = $content_array[1];
	$bonus_currency_filter = $content_array[2];
	$penalty_filter = $content_array[3];
	$ranks_array = get_option( 'go_ranks' );
	$ranks = $ranks_array['name'];
	echo "<input id='go_store_filter_checkbox' name='go_mta_store_filter' type='checkbox' {$is_checked}/>";

	if ( ! empty( $ranks ) && is_array( $ranks ) ) {
		echo "<select id='go_store_filter_select' class='go_store_filter_input' name='go_store_filter_select' style='display: none;'>";
		foreach ( $ranks as $rank ) {
			echo "<option class='go_store_filter_option'";
			if ( strtolower( $rank ) == strtolower( $chosen_rank ) ) {
				echo 'selected';
			}
			echo ">{$rank}</option>";
		}
		echo "</select>";
	}
	echo "
		<input id='go_store_filter_bonus_currency' class='go_store_filter_input' name='go_store_filter_bonus_currency' type='text' style='display: none;' placeholder='".go_return_options( 'go_bonus_currency_name' )."' ".( ( ! empty( $bonus_currency_filter ) ) ? "value='{$bonus_currency_filter}'" : '' )."/>
		<input id='go_store_filter_penalty' class='go_store_filter_input' name='go_store_filter_penalty' type='text' style='display: none;' placeholder='".go_return_options( 'go_penalty_name' )."' ".( ( ! empty( $penalty_filter ) ) ? "value='{$penalty_filter}'" : '' )."/>
	";
}

add_action( 'cmb_validate_go_store_filter', 'go_validate_store_filter' );
function go_validate_store_filter() {
	$is_checked     = ( ! empty( $_POST['go_mta_store_filter'] )            ? true : false );
	$chosen_rank    = ( ! empty( $_POST['go_store_filter_select'] )         ? sanitize_text_field( $_POST['go_store_filter_select'] ) : '' );
	$b_filter       = ( ! empty( $_POST['go_store_filter_bonus_currency'] ) ? (int) $_POST['go_store_filter_bonus_currency'] : 0 );
	$d_filter       = ( ! empty( $_POST['go_store_filter_penalty'] )        ? (int) $_POST['go_store_filter_penalty'] : 0 );

	return array(
		$is_checked,
		$chosen_rank,
		$b_filter,
		$d_filter,
	);
}

add_action( 'cmb_render_go_item_url', 'go_item_url' );
function go_item_url() {
	$custom = get_post_custom();
	$url = ( ! empty( $custom['go_mta_store_item_url'][0] ) ? $custom['go_mta_store_item_url'][0] : null );
	echo "<input id='go_store_item_url_input' name='go_mta_store_item_url' type='text' placeholder='http://yourlink.com' ".( ( ! empty( $url) ) ? "value='{$url}'" : '' )."/>";
}

add_action( 'cmb_render_go_badge_id', 'go_badge_id' );
function go_badge_id() {
	$custom = get_post_custom();
	$id = ( ! empty( $custom['go_mta_badge_id'][0] ) ? $custom['go_mta_badge_id'][0] : null );
	echo "<input id='go_store_badge_id_input' name='go_mta_badge_id' type='text' placeholder='Badge ID' ".( ( ! empty( $id ) ) ? "value='{$id}'" : '' )."/>";
}

add_action( 'cmb_render_go_store_gift', 'go_store_gift' );
function go_store_gift() {
	$custom = get_post_custom();
	$content_array = ( ! empty( $custom['go_mta_store_gift'][0] ) ? unserialize( $custom['go_mta_store_gift'][0] ) : null );
	$is_checked = ( ! empty( $content_array[0] ) ? 'checked' : '' );
	
	$c_gift = ( ! empty( $content_array[1] ) ? (int) $content_array[1] : '' );
	$p_gift = ( ! empty( $content_array[2] ) ? (int) $content_array[2] : '' );
	$b_gift = ( ! empty( $content_array[3] ) ? (int) $content_array[3] : '' );
	$t_gift = ( ! empty( $content_array[4] ) ? (int) $content_array[4] : '' );

	echo "
		<input id='go_store_gift_checkbox' name='go_mta_store_gift' type='checkbox' {$is_checked}/>
		<input class='go_store_gift_input' name='go_store_gift_currency' type='text' placeholder='".go_return_options( 'go_currency_name' )."' value='{$c_gift}'/>
		<input class='go_store_gift_input' name='go_store_gift_points' type='text' placeholder='".go_return_options( 'go_points_name' )."' value='{$p_gift}'/>
		<input class='go_store_gift_input' name='go_store_gift_bonus_currency' type='text' placeholder='".go_return_options( 'go_bonus_currency_name' )."' value='{$b_gift}'/>
		<input class='go_store_gift_input' name='go_store_gift_time' type='text' placeholder='".go_return_options( 'go_minutes_name' )."' value='{$t_gift}'/>
	";
}

add_action( 'cmb_validate_go_store_gift', 'go_validate_store_gift' );
function go_validate_store_gift() {
	$is_checked = ( ! empty( $_POST['go_mta_store_gift'] )            ? true : false );
	$c_gift     = ( ! empty( $_POST['go_store_gift_currency'] )       ? $_POST['go_store_gift_currency'] : null );
	$p_gift     = ( ! empty( $_POST['go_store_gift_points'] )         ? $_POST['go_store_gift_points'] : null );
	$b_gift     = ( ! empty( $_POST['go_store_gift_bonus_currency'] ) ? $_POST['go_store_gift_bonus_currency'] : null );
	$t_gift     = ( ! empty( $_POST['go_store_gift_time'] )           ? $_POST['go_store_gift_time'] : null );

	return array(
		$is_checked,
		$c_gift,
		$p_gift,
		$b_gift,
		$t_gift
	);
}

add_action( 'cmb_render_go_badge_input', 'go_render_badge_input', 10, 1 );
function go_render_badge_input( $field_args ) {
	global $post;

	// doesn't display anything if no stage or ID is provided
	if ( empty( $field_args['id'] ) || empty( $field_args['stage'] ) ) {
		return;
	}

	$row_id = $field_args['id'];
	$row_stage = $field_args['stage'];

	$badge_meta = get_post_meta( $post->ID, $row_id, true );

	$checked = ( ! empty( $badge_meta[0] ) ? 'checked' : '' );
	$badges  = ( ! empty( $badge_meta[1] ) ? (array) $badge_meta[1] : array() );

	$badge_elems = '';

	if ( ! empty( $badges ) ) {
		foreach ( $badges as $badge ) {
			$badge_elems .= sprintf(
				'<li>'.
					'<input type="text" name="go_badge_input_stage_%1$s[]" class="go_badge_input" '.
						'stage="%1$s" placeholder="Badge ID" value="%2$s"/>'.
					'<input type="button" class="go_button_add_field go_badge_input_add go_badge_input_button" value="+" />'.
					'<input type="button" class="go_button_del_field go_badge_input_del go_badge_input_button" value="x" />'.
				'</li>',
				$row_stage,
				$badge
			);
		}
	} else {
		$badge_elems = sprintf(
			'<li>'.
				'<input type="text" name="go_badge_input_stage_%1$s[]" class="go_badge_input" '.
					'stage="%1$s" placeholder="Badge ID"/>'.
				'<input type="button" class="go_button_add_field go_badge_input_add go_badge_input_button" value="+" />'.
				'<input type="button" class="go_button_del_field go_badge_input_del go_badge_input_button" value="x" '.
					'style="display: none;"/>'.
			'</li>',
			$row_stage
		);
	}

	printf(
		'<input type="checkbox" name="%1$s" class="go_badge_input_toggle" stage="%2$s" %3$s/>'.
		'<ul id="go_stage_%2$s_badges" class="go_stage_badge_container">%4$s</ul>',
		$row_id,
		$row_stage,
		$checked,
		$badge_elems
	);
}

add_action( 'cmb_validate_go_badge_input', 'go_validate_badge_input', 10, 3 );
function go_validate_badge_input( $override_value, $value, $field_args ) {
	$row_id = $field_args['id'];

	$is_checked = ( ! empty( $_POST[ $row_id ] ) ? true : false );
	$badge_ids = array();
	if ( ! empty( $_POST[ "go_badge_input_stage_{$field_args['stage']}" ] ) ) {
		$badge_ids = (array) $_POST[ "go_badge_input_stage_{$field_args['stage']}" ];

		// casts each ID to an int
		foreach ( $badge_ids as $key => $id ) {
			$badge_ids[ $key ] = (int) $id;
		}
	}

	return array( $is_checked, $badge_ids );
}

add_action( 'cmb_render_go_store_bonus', 'go_store_bonus' );
function go_store_bonus() {
	$custom = get_post_custom();
	$is_checked = ( ! empty( $custom['go_mta_store_bonus'][0] ) ? 'checked' : '' );

	echo "<input id='go_store_bonus_checkbox' name='go_store_bonus' type='checkbox' {$is_checked}/>";
}

add_action( 'cmb_validate_go_store_bonus', 'go_validate_store_bonus' );
function go_validate_store_bonus() {
	$is_checked = ( ! empty( $_POST['go_store_bonus'] ) ? true : false );

	return $is_checked;
}

?>