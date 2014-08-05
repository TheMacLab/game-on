<?php
//Task Includes
include('tasks/task.php');

//Store Includes
include('store/super-store.php');

include('test/test_shortcode.php');

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
		'title'      => go_return_options('go_tasks_name').' Settings',
		'pages'      => array( 'tasks' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Presets'.go_task_opt_help('presets', 'SAMPLE TEXT HELLO WORLD HOW ARE YOU DOING TODAY THIS IS GREAT', 'http://maclab.guhsd.net/go/video/quests/presets.mp4'),
				'id'   => 'go_presets',
				'type' => 'go_presets',
			),
			array(
				'name' => go_task_opt_help('advanced_settings', '', 'http://maclab.guhsd.net/go/video/quests/advancedSettings.mp4'),
				'id' => 'advanced_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Advanced Settings',
				'settings_id' => 'go_advanced_task_settings_accordion'
			),
			array(
				'name' => 'Required Rank '.go_task_opt_help('req_rank', '', 'http://maclab.guhsd.net/go/video/quests/requiredRank.mp4'),
				'id'   => $prefix . 'req_rank',
				'type' => 'go_rank_list'
			),
			array(
				'name' => go_return_options('go_bonus_currency_name').' Filter'.go_task_opt_help('bonus_currency_filter', '', 'http://maclab.guhsd.net/go/video/quests/bonusCurrencyFilter.mp4'),
				'id' => $prefix . 'bonus_currency_filter',
				'type' => 'text'
			),
			array(
				'name' => go_return_options('go_penalty_name').' Filter'.go_task_opt_help('penalty_filter', '', 'http://maclab.guhsd.net/go/video/quests/penaltyFilter.mp4'),
				'id' => $prefix . 'penalty_filter',
				'type' => 'text'
			),
			array(
				'name' => 'Date Filter (Calendar)'.go_task_opt_help('nerf_dates', '', 'http://maclab.guhsd.net/go/video/quests/nerfDates.mp4'),
				'id' => $prefix.'date_picker',
				'type' => 'go_decay_table'
			),
			array(
				'name' => go_return_options('go_focus_name').' Filter'.go_task_opt_help('lock_by_cat', '', ' http://maclab.guhsd.net/go/video/quests/lockByProfessionCategory.mp4'),
				'id' => $prefix.'focus_category_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => '3 Stage '.go_return_options('go_tasks_name').go_task_opt_help('three_stage_switch', '', 'http://maclab.guhsd.net/go/video/quests/threeStageQuest.mp4'),
				'id' => $prefix.'three_stage_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => '5 Stage '.go_return_options('go_tasks_name').go_task_opt_help('five_stage_switch', '', 'http://maclab.guhsd.net/go/video/quests/fiveStageQuest.mp4'),
				'id'   => $prefix . 'five_stage_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Chain Order'.go_task_opt_help('task_chain_order', '', 'http://maclab.guhsd.net/go/video/quests/tasksInChain.mp4'),
				'id' => $prefix.'chain_order',
				'type' => 'go_pick_order_of_chain'
			),
			array(
				'name' => 'Final '.go_return_options('go_tasks_name').' Message'.go_task_opt_help('final_chain_message', '', 'http://maclab.guhsd.net/go/video/quests/finalChainMessage.mp4'),
				'id' => $prefix.'final_chain_message',
				'type' => 'text'
			),
			array(
				'name' => 'Stage 1'.go_task_opt_help('encounter', '', 'http://maclab.guhsd.net/go/video/quests/stageOne.mp4'),
				'id' => $prefix . 'quick_desc',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => go_task_opt_help('stage_one_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageOneSettings.mp4'),
				'id' => 'stage_one_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 1 Settings',
				'settings_id' => 'go_stage_one_settings_accordion'
			),
			array(
				'name' => go_return_options('go_points_name').go_task_opt_help('stage_one_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4'),
				'id' => $prefix . 'stage_one_points',
				'type' => 'go_stage_reward',
				'stage' => 1,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options('go_currency_name').go_task_opt_help('stage_one_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4'),
				'id' => $prefix . 'stage_one_currency',
				'type' => 'go_stage_reward',
				'stage' => 1,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options('go_bonus_currency_name').go_task_opt_help('stage_one_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4'),
				'id' => $prefix . 'stage_one_bonus_currency',
				'type' => 'go_stage_reward',
				'stage' => 1,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Upload'.go_task_opt_help('encounter_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4'),
				'id' => $prefix.'encounter_upload',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help('encounter_understand', '', 'http://maclab.guhsd.net/go/video/quests/encounterCheckForUnderstanding.mp4'),
				'id' => $prefix.'test_encounter_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help('encounter_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'id' => $prefix.'test_encounter_lock_loot',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help('encounter_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4'),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_encounter_lock_loot_mod',
				'type' => 'go_test_modifier'
			),
			array(
 				'name' => 'Format'.go_task_opt_help('encounter_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4'),
 				'id' => $prefix.'test_lock_encounter',
 				'type' => 'go_test_field_encounter'
 			),
			array(
				'name' => 'Shortcodes'.go_task_opt_help('shortcode_list', '', 'http://maclab.guhsd.net/go/video/quests/shortcodeList.mp4'),
				'id' => 'stage_one_shortcode_list',
				'type' => 'go_shortcode_list',
			),
			array(
				'name' => 'Stage 2'.go_task_opt_help('accept', '', 'http://maclab.guhsd.net/go/video/quests/acceptMessage.mp4'),
				'id' => $prefix . 'accept_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => go_task_opt_help('stage_two_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageTwoSettings.mp4'),
				'id' => 'stage_two_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 2 Settings',
				'settings_id' => 'go_stage_two_settings_accordion'
			),
			array(
				'name' => go_return_options('go_points_name').go_task_opt_help('stage_two_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4'),
				'id' => $prefix . 'stage_two_points',
				'type' => 'go_stage_reward',
				'stage' => 2,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options('go_currency_name').go_task_opt_help('stage_two_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4'),
				'id' => $prefix . 'stage_two_currency',
				'type' => 'go_stage_reward',
				'stage' => 2,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options('go_bonus_currency_name').go_task_opt_help('stage_two_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4'),
				'id' => $prefix . 'stage_two_bonus_currency',
				'type' => 'go_stage_reward',
				'stage' => 2,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help('accept_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/permaLock.mp4'),
				'id' => $prefix.'accept_admin_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help('accept_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4'),
				'id' => $prefix.'accept_upload',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help('accept_understand', '', 'http://maclab.guhsd.net/go/video/quests/acceptCheckForUnderstanding.mp4'),
				'id' => $prefix.'test_accept_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help('accept_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'id' => $prefix.'test_accept_lock_loot',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help('accept_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4'),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_accept_lock_loot_mod',
				'type' => 'go_test_modifier'
			),
			array(
 				'name' => 'Format'.go_task_opt_help('accept_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4'),
 				'id' => $prefix.'test_lock_accept',
 				'type' => 'go_test_field_accept'
 			),
			array(
				'name' => 'Shortcodes'.go_task_opt_help('shortcode_list', '', 'http://maclab.guhsd.net/go/video/quests/shortcodeList.mp4'),
				'id' => 'stage_two_shortcode_list',
				'type' => 'go_shortcode_list'
			),
			array(
				'name' => 'Stage 3'.go_task_opt_help('complete', '', 'http://maclab.guhsd.net/go/video/quests/completionMessage.mp4'),
				'id' => $prefix . 'complete_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
			),
			array(
				'name' => go_task_opt_help('stage_three_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageThreeSettings.mp4'),
				'id' => 'stage_three_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 3 Settings',
				'settings_id' => 'go_stage_three_settings_accordion'
			),
			array(
				'name' => go_return_options('go_points_name').go_task_opt_help('stage_three_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4'),
				'id' => $prefix . 'stage_three_points',
				'type' => 'go_stage_reward',
				'stage' => 3,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options('go_currency_name').go_task_opt_help('stage_three_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4'),
				'id' => $prefix . 'stage_three_currency',
				'type' => 'go_stage_reward',
				'stage' => 3,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options('go_bonus_currency_name').go_task_opt_help('stage_three_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4'),
				'id' => $prefix . 'stage_three_bonus_currency',
				'type' => 'go_stage_reward',
				'stage' => 3,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help('completion_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/permaLock.mp4'),
				'id' => $prefix.'completion_admin_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help('completion_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4'),
 				'id' => $prefix.'completion_upload',
 				'type' => 'checkbox'
 			),
			array(
				'name' => 'Test'.go_task_opt_help('complete_understand', '', 'http://maclab.guhsd.net/go/video/quests/completionCheckForUnderstanding.mp4'),
				'id' => $prefix.'test_completion_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help('complete_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'id' => $prefix.'test_completion_lock_loot',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help('complete_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4'),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_completion_lock_loot_mod',
				'type' => 'go_test_modifier'
			),
			array(
 				'name' => 'Format'.go_task_opt_help('complete_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4'),
 				'id' => $prefix.'test_lock_completion',
 				'type' => 'go_test_field_completion'
 			),
			array(
				'name' => '3 Stage '.go_return_options('go_tasks_name').go_task_opt_help('toggle_mastery_stage', '', 'http://maclab.guhsd.net/go/video/quests/threeStageQuest.mp4'),
				'id' => $prefix.'task_mastery',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Shortcodes'.go_task_opt_help('shortcode_list', '', 'http://maclab.guhsd.net/go/video/quests/shortcodeList.mp4'),
				'id' => 'stage_three_shortcode_list',
				'type' => 'go_shortcode_list'
			),
			array(
				'name' => 'Stage 4'.go_task_opt_help('mastery', '', 'http://maclab.guhsd.net/go/video/quests/stageFour.mp4'),
				'id' => $prefix . 'mastery_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
			),
			array(
				'name' => go_task_opt_help('stage_four_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageFourSettings.mp4'),
				'id' => 'stage_four_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 4 Settings',
				'settings_id' => 'go_stage_four_settings_accordion'
			),
			array(
				'name' => go_return_options('go_points_name').go_task_opt_help('stage_four_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4'),
				'id' => $prefix . 'stage_four_points',
				'type' => 'go_stage_reward',
				'stage' => 4,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options('go_currency_name').go_task_opt_help('stage_four_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4'),
				'id' => $prefix . 'stage_four_currency',
				'type' => 'go_stage_reward',
				'stage' => 4,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options('go_bonus_currency_name').go_task_opt_help('stage_four_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4'),
				'id' => $prefix . 'stage_four_bonus_currency',
				'type' => 'go_stage_reward',
				'stage' => 4,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Lock'.go_task_opt_help('mastery_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/permaLock.mp4'),
				'id' => $prefix.'mastery_admin_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help('mastery_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4'),
				'id' => $prefix.'mastery_upload',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test'.go_task_opt_help('mastery_understand', '', 'http://maclab.guhsd.net/go/video/quests/masteryCheckForUnderstanding.mp4'),
				'id' => $prefix.'test_mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Loot'.go_task_opt_help('mastery_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'id' => $prefix.'test_mastery_lock_loot',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Loot Modifier'.go_task_opt_help('mastery_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4'),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test.  This will replace the default modifier. 
							<code>Note: Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100".  Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_loot_mod',
				'type' => 'go_test_modifier'
			),
			array(
 				'name' => 'Format'.go_task_opt_help('mastery_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4'),
 				'id' => $prefix.'test_lock_mastery',
 				'type' => 'go_test_field_mastery'
 			),
			array(
				'name' => '5 Stage '.go_return_options('go_tasks_name').go_task_opt_help('five_stage_switch', '', 'http://maclab.guhsd.net/go/video/quests/fiveStageQuest.mp4'),
				'id'   => $prefix . 'task_repeat',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Shortcodes'.go_task_opt_help('shortcode_list', '', 'http://maclab.guhsd.net/go/video/quests/shortcodeList.mp4'),
				'id' => 'stage_four_shortcode_list',
				'type' => 'go_shortcode_list'
			),
			array(
				'name' => 'Stage 5'.go_task_opt_help('repeat_message', '', 'http://maclab.guhsd.net/go/video/quests/stageFive.mp4'),
				'id' => $prefix . 'repeat_message',
				'type' => 'wysiwyg',
        		'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),		
			),
			array(
				'name' => go_task_opt_help('stage_five_settings', '', 'http://maclab.guhsd.net/go/video/quests/stageFiveSettings.mp4'),
				'id' => 'stage_five_settings',
				'type' => 'go_settings_accordion',
				'message' => 'Stage 5 Settings',
				'settings_id' => 'go_stage_five_settings_accordion'
			),
			array(
				'name' => go_return_options('go_points_name').go_task_opt_help('stage_five_points', '', 'http://maclab.guhsd.net/go/video/quests/stagePoint.mp4'),
				'id' => $prefix . 'stage_five_points',
				'type' => 'go_stage_reward',
				'stage' => 5,
				'reward' => 'points'
			),
			array(
				'name' => go_return_options('go_currency_name').go_task_opt_help('stage_five_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageCurrency.mp4'),
				'id' => $prefix . 'stage_five_currency',
				'type' => 'go_stage_reward',
				'stage' => 5,
				'reward' => 'currency'
			),
			array(
				'name' => go_return_options('go_bonus_currency_name').go_task_opt_help('stage_five_bonus_currency', '', 'http://maclab.guhsd.net/go/video/quests/stageBonusCurrency.mp4'),
				'id' => $prefix . 'stage_five_bonus_currency',
				'type' => 'go_stage_reward',
				'stage' => 5,
				'reward' => 'bonus_currency'
			),
			array(
				'name' => 'Limit'.go_task_opt_help('repeat_limit', '', 'http://maclab.guhsd.net/go/video/quests/allowedRepeatableTimes.mp4'),
				'id' => $prefix.'repeat_amount',
				'type' => 'text'
			),
			array(
				'name' => 'Lock'.go_task_opt_help('repeat_admin_lock', '', 'http://maclab.guhsd.net/go/video/quests/permaLock.mp4'),
				'id' => $prefix.'repeat_admin_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Upload'.go_task_opt_help('repeat_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/fileUpload.mp4'),
				'id' => $prefix.'repeat_upload',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Shortcodes'.go_task_opt_help('shortcode_list', '', 'http://maclab.guhsd.net/go/video/quests/shortcodeList.mp4'),
				'id' => 'stage_five_shortcode_list',
				'type' => 'go_shortcode_list'
			)
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
				'name' => 'Cost'.go_task_opt_help('cost', 'The Cost of the store item', 'http://maclab.guhsd.net/go/video/store/cost.mp4'),
				'id' => "{$prefix}store_cost",
				'type' => 'go_store_cost',
			),
			array(
				'name' => 'Limit'.go_task_opt_help('store_limit', '', 'http://maclab.guhsd.net/go/video/store/storeLimit.mp4'),
				'id' => "{$prefix}store_limit",
				'type' => 'go_store_limit'
			),
			array(
				'name' => 'Penalty'.go_task_opt_help('penalty', '', 'http://maclab.guhsd.net/go/video/store/penalty.mp4'),
				'id' => "{$prefix}penalty_switch",
				'type' => 'checkbox'
			),
			array(
				'name' => 'Filter'.go_task_opt_help('filter', '', 'http://maclab.guhsd.net/go/video/store/filter.mp4'),
				'id' => "{$prefix}store_filter",
				'type' => 'go_store_filter'
			),
			array(
				'name' => 'Exchange'.go_task_opt_help('exchange', '', 'http://maclab.guhsd.net/go/video/store/exchange.mp4'),
				'id' => "{$prefix}store_exchange",
				'type' => 'go_store_exchange'
			),
			array(
				'name' => 'URL'.go_task_opt_help('item_url', '', 'http://maclab.guhsd.net/go/video/store/itemURL.mp4'),
				'id' => "{$prefix}store_item_url",
				'type' => 'go_item_url'	
			),
			array(
				'name' => 'Badge'.go_task_opt_help('badge_id', '', 'http://maclab.guhsd.net/go/video/store/badgeID.mp4'),
				'id' => "{$prefix}badge_id",
				'type' => 'go_badge_id'
			),
			array(
				'name' => go_return_options('go_focus_name').go_task_opt_help('focus', '', 'http://maclab.guhsd.net/go/video/store/focus.mp4'),
				'id' => "{$prefix}store_focus",
				'type' => 'go_store_focus'
			),
			array(
				'name' => 'Send Receipt'.go_task_opt_help('store_receipt', '', 'http://maclab.guhsd.net/go/video/store/receipt.mp4'),
				'id' => "{$prefix}store_receipt",
				'type' => 'go_store_receipt'
			),
			array(
				'name' => 'Shortcode'.go_task_opt_help('store_shortcode', '', 'http://maclab.guhsd.net/go/video/store/storeShortcode.mp4'),
				'id' => "{$prefix}store_shortcode_list",
				'type' => 'go_store_shortcode_list'
			),
		),
	);
	return $meta_boxes;
}

add_action( 'cmb_render_go_presets', 'go_presets_js' );
add_filter( 'cmb_meta_boxes', 'go_mta_con_meta' );
add_action( 'init', 'go_init_mtbxs', 9999 );

add_action('cmb_render_go_presets', 'go_presets', 10, 1);
function go_presets($field_args) {
	?>
	<select id="go_presets" onchange="apply_presets();">
        <?php
			$presets = get_option('go_presets',false);
			foreach($presets['name'] as $key => $name){
				$points = implode(',', $presets['points'][$key]);
				$currency = implode(',', $presets['currency'][$key]);
				echo "<option value='{$name}' points='{$points}' currency='{$currency}'>{$name} - {$points} - {$currency}</option>";
			}
		?>
	</select>
	<?php
}

add_action('cmb_validate_go_presets', 'go_validate_stage_reward');
function go_validate_stage_reward(){
	$points = $_POST['stage_1_points'];
	$currency = $_POST['stage_1_currency'];
	$bonus_currency = $_POST['stage_1_bonus_currency'];
	$task_rewards = array('points' => $points, 'currency' => $currency, 'bonus_currency' => $bonus_currency);
	return $task_rewards;
}

add_action('cmb_render_go_rank_list', 'go_rank_list');
function go_rank_list() {
	$custom = get_post_custom(get_the_id());
	$current_rank = $custom['go_mta_req_rank'][0];
	$ranks_array = get_option('go_ranks');
	$ranks = $ranks_array['name'];
	if (!empty($ranks)) {
		echo "<select id='go_req_rank_select' name='go_mta_req_rank'>";
		foreach ($ranks as $rank) {
			echo "<option class='go_req_rank_option' ".(strtolower($rank) == strtolower($current_rank) ? 'selected' : '').">{$rank}</option>";
		}
		echo "</select>";
	} else {
		echo "No <a href='".admin_url()."/?page=game-on-options.php' target='_blank'>".get_option('go_level_plural_names')."</a> were provided.";
	}
}

add_action('cmb_render_go_shortcode_list', 'go_cmb_render_go_shortcode_list');
function go_cmb_render_go_shortcode_list($field_args){
	$meta_id = $field_args["id"];
	$custom = get_post_custom(get_the_id());
	$is_checked = $custom[$meta_id][0];
	echo "
		<input class='go_shortcode_list_checkbox' name='{$meta_id}' type='checkbox'";
		if ($is_checked) {
			echo ' checked';
		}
	echo "/>
		<ul class='go_shortcode_list' style='display: none;'>
			<li class='go_shortcode_list_item'><span>"
				.go_task_opt_help('display_name_shortcode', '', 'http://maclab.guhsd.net/go/video/quests/displayNameShortcode.mp4')."</span>[go_get_displayname]
			</li>
			<li class='go_shortcode_list_item'><span>"
				.go_task_opt_help('badge_shortcode', '', 'http://maclab.guhsd.net/go/video/quests/badgeShortcde.mp4')."</span>[go_award_badge id='']
			</li>
			<li class='go_shortcode_list_item'><span>"
				.go_task_opt_help('user_only_shortcode', '', 'http://maclab.guhsd.net/go/video/quests/userOnlyShortcode.mp4')."</span>[go_user_only_content][/go_user_only_content]
			</li>
			<li class='go_shortcode_list_item'><span>"
				.go_task_opt_help('visitor_only_shortcode', '', 'http://maclab.guhsd.net/go/video/quests/visitorOnlyShortcode.mp4')."</span>[go_visitor_only_content][/go_visitor_only_content]
			</li>
			<!--
			<li class='go_shortcode_list_item'><span>"
				.go_task_opt_help('admin_only_shortcode', '', 'http://maclab.guhsd.net/go/video/quests/adminOnlyShortcode.mp4')."</span>[go_admin_only_content][/go_admin_only_content]
			</li>
			-->
			<li class='go_shortcode_list_item'><span>"
				.go_task_opt_help('video_shortocde', '', 'http://maclab.guhsd.net/go/video/quests/videoShortcode.mp4')."</span>[go_display_video video_url='' video_title='' width='' height='']
			</li>
		</ul>
	";
}

add_action('cmb_render_go_decay_table', 'go_decay_table');
function go_decay_table() {
	?>
		<table id="go_list_of_decay_dates" stye="margin: 0px; padding: 0px;">
			<th></th><th></th>
            <?php
            $custom = get_post_custom(get_the_id());
            if($custom['go_mta_date_picker']){
				$temp_array = array();
				$dates = array();
				$percentages = array();
            	foreach($custom['go_mta_date_picker'] as $key => $value){
					$temp_array[$key] = unserialize($value); 
				}
				$temp_array2 = $temp_array[0];
				
				if(!empty($temp_array2)){
					foreach($temp_array2 as $key => $value){
						if($key == 'date'){
							foreach(array_values($value) as $date_val){
								array_push($dates, $date_val);	
							}
						}elseif($key == 'percent'){
							foreach(array_values($value) as $percent_val){
								array_push($percentages, $percent_val);	
							}
						}
					}
				}
				foreach($dates as $key => $date){
					?>
                    <tr>
                        <td><input name="go_mta_task_decay_calendar[]" id="go_mta_task_decay_calendar" class="datepicker custom_date" value="<?php echo $date;?>" type="date"/></td>
                        <td><input name="go_mta_task_decay_percent[]" id="go_mta_task_decay_percent" value="<?php echo $percentages[$key]?>" type="text"/></td>
                    </tr>
                    <?php
				}
            }else{
			?>
			<tr>
				<td><input name="go_mta_task_decay_calendar[]" id="go_mta_task_decay_calendar" class="datepicker custom_date" type="date" placeholder="Click for Date"/></td>
				<td><input name="go_mta_task_decay_percent[]" id="go_mta_task_decay_percent" type="text" placeholder="Modifier"/></td>
			</tr>
            <?php 
			}
			?>
		</table>
		<input type="button" id="go_mta_add_task_decay" onclick="go_add_decay_table_row()" value="+"/>
		<input type="button" id="go_mta_remove_task_decay" onclick="go_remove_decay_table_row()" value="-"/>
	<?php
}

add_action('cmb_validate_go_decay_table', 'go_validate_decay_table');
function go_validate_decay_table() {
	$dates = $_POST['go_mta_task_decay_calendar'];
	$percentages = $_POST['go_mta_task_decay_percent'];
	if(isset($dates) && isset($percentages)){
		$dates_f = array_filter($dates);
		$percentages_f = array_filter($percentages);
		$new_dates = $dates_f;
		$new_percentages = $percentages_f;
		if(count($dates_f) != count($dates)){
			$new_percentages = array_intersect_key($percentages_f,$dates_f);
		}
		if(count($percentages_f) != count($percentages)){
			$new_dates = array_intersect_key($dates_f,$percentages_f);
		}
		return array('date' => $new_dates, 'percent' => $new_percentages);
	}
}

add_action('cmb_render_go_test_modifier', 'go_test_modifier');
function go_test_modifier($field_args) {
	global $post;
	$post_id = $post->ID;
	$meta_id = $field_args["id"];
	$custom = get_post_custom($post_id);
	$modifier_content = $custom["{$meta_id}"][0];
	if (empty($modifier_content)) {
		$modifier_content = 20;
	}
	echo "<input class='go_test_loot_mod' name='{$meta_id}' type='text' value='{$modifier_content}'/>";
}

add_action('cmb_validate_go_test_modifier', 'go_validate_test_modifier', 10, 3);
function go_validate_test_modifier($override_value, $post_id, $field_args) {
	$meta_id = $field_args["id"];
	$mod_temp = $_POST[$meta_id];
	if (!empty($mod_temp)) {
		if (!preg_match("/[0-9]+/", $mod_temp)) {
			return 20;
		} else {
			if (preg_match("/[^0-9]+/", $mod_temp)) {
				$mod = preg_replace("/[^0-9]+/", '', $mod_temp);
				if ($mod > 0 && $mod <= 100) {
					return $mod;
				} else {
					return 20;
				}
			} else if ((int)$mod_temp > 0 && (int)$mod_temp <= 100) {
				return (int)$mod_temp;
			} else {
				return 20;
			}
		}
	} else {
		return 20;
	}
}

add_action('cmb_render_go_test_field_encounter', 'go_test_field_encounter', 10, 1);
function go_test_field_encounter($field_args) {
	$custom = get_post_custom(get_the_id());

	$temp_array = $custom["go_mta_test_lock_encounter"][0];
	$temp_uns = unserialize($temp_array);
	$test_field_input_question = $temp_uns[0];
	$test_field_input_array = $temp_uns[1];
	$test_field_select_array = $temp_uns[2];
	$test_field_block_count = (int)$temp_uns[3];
	$test_field_input_count = $temp_uns[4];

	?>
	<span class='cmb_metabox_description'>
		<?php echo $field_args['desc']; ?>
	</span>
	<table id='go_test_field_table_e' class='go_test_field_table'>
		<?php 
		if (!empty($test_field_block_count)) {
			for ($i = 0; $i < $test_field_block_count; $i++) {
				echo "
				<tr id='go_test_field_input_row_e_".$i."' class='go_test_field_input_row_e go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_e_".$i."' class='go_test_field_input_select_e' name='go_test_field_select_e[]' onchange='update_checkbox_type_e(this);'>
						  <option value='radio' class='go_test_field_input_option_e'>Multiple Choice</option>
						  <option value='checkbox' class='go_test_field_input_option_e'>Multiple Select</option>
						</select>";
						if (!empty($test_field_input_question)) {
							echo "<br/><br/><input class='go_test_field_input_question_e go_test_field_input_question' name='go_test_field_input_question_e[]' placeholder='Shall We Play a Game?' type='text' value='".$test_field_input_question[$i]."' />";
						} else {
							echo "<br/><br/><input class='go_test_field_input_question_e go_test_field_input_question' name='go_test_field_input_question_e[]' placeholder='Shall We Play a Game?' type='text' />";
						}
				if (!empty($test_field_input_count)) {
					echo "<ul>";
					for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
						echo "
							<li><input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_e_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_e(this);' />
							<input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e[".$i."][1][]' type='hidden' />
							<input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][$x]."' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' />";
						if ($x > 1) {
							echo "<input class='go_test_field_rm go_test_field_rm_input_button_e' type='button' value='X' onclick='remove_field_e(this);'>";
						}
						echo "</li>";
						if (($x + 1) == $test_field_input_count[$i]) {
							echo "<input class='go_test_field_add go_test_field_add_input_button_e' type='button' value='+' onclick='add_field_e(this);'/>";
						}
					}
					echo "</ul><ul>";
					if ($i > 0) {
						echo "<li><input class='go_test_field_rm_row_button_e go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_e(this);' /></li>";
					}
					echo "<li><input class='go_test_field_input_count_e' name='go_test_field_input_count_e[]' type='hidden' value='".$test_field_input_count[$i]."' /></li></ul>";
				} else {
					echo "
					<ul>
						<li><input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='go_test_field_input_checkbox_e_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_e(this);' /><input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e[".$i."][1][]' type='hidden' /><input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][0]."' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' /></li>
						<li><input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='go_test_field_input_checkbox_e_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_e(this);' /><input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e[".$i."][1][]' type='hidden' /><input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][1]."' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' /></li>";
					echo "</ul><ul><li>";
					if ($i > 0) {
						echo "<input class='go_test_field_rm_row_button_e go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_e(this);' /></li><li>";
					}
					echo "<input class='go_test_field_input_count_e' name='go_test_field_input_count_e[]' type='hidden' value='2' /></li></ul>";
				}
				echo "
					</td>
				</tr>";
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_e_0' class='go_test_field_input_row_e go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_e_0' class='go_test_field_input_select_e' name='go_test_field_select_e[]' onchange='update_checkbox_type_e(this);'>
							<option value='radio' class='go_test_field_input_option_e'>Multiple Choice</option>
							<option value='checkbox' class='go_test_field_input_option_e'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_e go_test_field_input_question' name='go_test_field_input_question_e[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_e_0' type='radio' onchange='update_checkbox_value_e(this);' />
								<input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e[0][1][]' type='hidden' />
								<input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_e_0' type='radio' onchange='update_checkbox_value_e(this);' />
								<input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e[0][1][]' type='hidden' />
								<input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' />
							</li>
							<input class='go_test_field_add go_test_field_add_input_button_e' type='button' value='+' onclick='add_field_e(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count_e' name='go_test_field_input_count_e[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
		}
		?>
		<tr>
			<td>
				<input id='go_test_field_add_block_button_e' class='go_test_field_add_block_button' value='Add Block' type='button' onclick='add_block_e(this);' />
				<?php 
				if (!empty($test_field_block_count)) {
					echo "<input id='go_test_field_block_count_e' name='go_test_field_block_count_e' type='hidden' value='".$test_field_block_count."' />";
				} else {
					echo "<input id='go_test_field_block_count_e' name='go_test_field_block_count_e' type='hidden' value='1' />";
				}
				?>
			</td>
		</tr>
	</table>
	<script type='text/javascript'>
		var block_num_e = 0;
		var block_type_e = 'radio';
		var input_num_e = 0;
		var block_count_e = <?php echo $test_field_block_count; ?>;
		
		var test_field_select_array_e = new Array(
			<?php 
			if (!empty($test_field_block_count)) {
				for ($i = 0; $i < $test_field_block_count; $i++) {
					echo '"'.ucwords($test_field_select_array[$i]).'"';
					if (($i + 1) != $test_field_block_count) { 
						echo ', ';
					}
				}
			}
			?>
		);
		var test_field_checked_array_e = [
			<?php
			if (!empty($test_field_block_count)) {
				for ($x = 0; $x < $test_field_block_count; $x++) {
					echo "[";
					if (!empty($test_field_input_array[$x][0]) && !empty($test_field_input_array[$x][1])) {
						$intersection = array_intersect($test_field_input_array[$x][0], $test_field_input_array[$x][1]);
						$checked_intersection = array_values($intersection);
						for ($i = 0; $i < count($checked_intersection); $i++) {
							$value = $checked_intersection[$i];
							if (preg_match("/(\&\#39;|\&\#34;)+/", $value)) {
								$str = $value;
								if (preg_match("/(\&\#39;)+/", $str)) {
									$str = preg_replace("/(\&\#39;)+/", "\'", $str);
								}
								
								if (preg_match("/(\&\#34;)+/", $str)) {
									$str = preg_replace("/(\&\#34;)+/", '\"', $str);
								}
								echo '"'.$str.'"';
							} else {
								echo '"'.$value.'"';
							}
							if (($i) < count($checked_intersection)) {
								echo ", ";
							}
						}
					}
					echo "]";
					if (($x + 1) < $test_field_block_count) {
						echo ", ";
					}
				}
			}
			?>
		];
		for (var i = 0; i < test_field_select_array_e.length; i++) {
			var test_field_with_select_value = '#go_test_field_select_e_'+i+' .go_test_field_input_option_e:contains(\''+test_field_select_array_e[i]+'\')';
			jQuery(test_field_with_select_value).attr('selected', true);
		}
		for (var x = 0; x < block_count_e; x++) {
			for (var z = 0; z < test_field_checked_array_e[x].length; z++) {
				var test_fields_with_checked_value = "tr#go_test_field_input_row_e_"+[x]+" .go_test_field_input_e[value='"+test_field_checked_array_e[x][z]+"']";
				var checked_fields = jQuery(test_fields_with_checked_value).siblings('.go_test_field_input_checkbox_e').attr('checked', true);
			}
		}
		var checkbox_obj_array = jQuery('.go_test_field_input_checkbox_e');
		for (var y = 0; y < checkbox_obj_array.length; y++) {
			var next_obj = checkbox_obj_array[y].nextElementSibling;
			if (checkbox_obj_array[y].checked) {
				var input_obj = next_obj.nextElementSibling.value;
				jQuery(next_obj).attr('value', input_obj);
			} else {
				jQuery(next_obj).removeAttr('value');
			}
		}
		function update_checkbox_value_e (target) {
			if (jQuery(target).hasClass('go_test_field_input_e')) {
				var obj = jQuery(target).siblings('.go_test_field_input_checkbox_e');
			} else {
				var obj = target;
			}
			var checkbox_type = jQuery(obj).prop('type');
			var input_field_val = jQuery(obj).siblings('.go_test_field_input_e').val();
			if (checkbox_type === 'radio') {
				var radio_name = jQuery(obj).prop('name');
				var radio_checked_str = ".go_test_field_input_checkbox_e[name='"+radio_name+"']:checked";
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_e').attr('value', input_field_val);
					} else {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_e').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_e').removeAttr('value');
				}
				var radios_not_checked_str = ".go_test_field_input_checkbox_e[name='"+radio_name+"']:not(:checked)";
				jQuery(radios_not_checked_str).siblings('.go_test_field_input_checkbox_hidden_e').removeAttr('value');
			} else {
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_e').attr('value', input_field_val);	
					} else {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_e').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_e').removeAttr('value');
				}
			}
		}
		function update_checkbox_type_e (obj) {
			block_type_e = jQuery(obj).children('option:selected').val();
			jQuery(obj).siblings('ul').children('li').children('input.go_test_field_input_checkbox_e').attr('type', block_type_e);
		}
		function add_block_e (obj) {
			block_num_e = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_e').length;
			jQuery('#go_test_field_block_count_e').attr('value', (block_num_e + 1));
			var field_block = "<tr id='go_test_field_input_row_e_"+block_num_e+"' class='go_test_field_input_row_e go_test_field_input_row'><td><select id='go_test_field_select_e_"+block_num_e+"' class='go_test_field_input_select_e' name='go_test_field_select_e[]' onchange='update_checkbox_type_e(this);'><option value='radio' class='go_test_field_input_option_e'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option_e'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question_e go_test_field_input_question' name='go_test_field_input_question_e[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_e_"+block_num_e+"' type='"+block_type_e+"' onchange='update_checkbox_value_e(this);' /><input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e["+block_num_e+"][1][]' type='hidden' /><input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e["+block_num_e+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' /></li><li><input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_e_"+block_num_e+"' type='"+block_type_e+"' onchange='update_checkbox_value_e(this);' /><input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e["+block_num_e+"][1][]' type='hidden' /><input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e["+block_num_e+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' /></li><input class='go_test_field_add go_test_field_add_input_button_e' type='button' value='+' onclick='add_field_e(this);'/></ul><ul><li><input class='go_test_field_rm_row_button_e go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block_e(this);' /><input class='go_test_field_input_count_e' name='go_test_field_input_count_e[]' type='hidden' value='2' /></li></ul></td></tr>";
			jQuery(obj).parent().parent().before(field_block);
		}
		function remove_block_e (obj) {
			block_num_e = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_e').length;
			jQuery('#go_test_field_block_count_e').attr('value', (block_num_e - 1));
			jQuery(obj).parents('tr.go_test_field_input_row_e').remove();
		}
		function add_field_e (obj) {
			input_num_e = jQuery(obj).siblings('li').length + 1;
			var block_id = jQuery(obj).parents('tr.go_test_field_input_row_e').first().attr('id');
			block_num_e = block_id.split('go_test_field_input_row_e_').pop();
			block_type_e = jQuery(obj).parent('ul').siblings('select').children('option:selected').val();
			jQuery(obj).parent('ul').siblings('ul').children('li').children('.go_test_field_input_count_e').attr('value', input_num_e);
			jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox_e go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_e_"+block_num_e+"' type='"+block_type_e+"' onchange='update_checkbox_value_e(this);' /><input class='go_test_field_input_checkbox_hidden_e' name='go_test_field_values_e["+block_num_e+"][1][]' type='hidden' /><input class='go_test_field_input_e go_test_field_input' name='go_test_field_values_e["+block_num_e+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_e(this);' oncut='update_checkbox_value_e(this);' onpaste='update_checkbox_value_e(this);' /><input class='go_test_field_rm go_test_field_rm_input_button_e' type='button' value='X' onclick='remove_field_e(this);'></li>");
		}
		function remove_field_e (obj) {
			jQuery(obj).parents('tr.go_test_field_input_row_e').find('input.go_test_field_input_count_e')[0].value--;
			jQuery(obj).parent('li').remove();
		}
		
	</script>
	<?php
}

add_action('cmb_validate_go_test_field_encounter', 'go_validate_test_field_encounter');
function go_validate_test_field_encounter() {
	$question_temp = $_POST['go_test_field_input_question_e'];
	$test_temp = $_POST['go_test_field_values_e'];
	$select = $_POST['go_test_field_select_e'];
	$block_count = (int)$_POST['go_test_field_block_count_e'];
	$input_count_temp = $_POST['go_test_field_input_count_e'];

	$input_count = array();
	if (!empty($input_count_temp)) {
		foreach ($input_count_temp as $key => $value) {
			$temp = (int)$input_count_temp[$key];
			array_push($input_count, $temp);
		}
	}

	$question = array();
	if (!empty($question_temp) && is_array($question_temp)) {
		foreach ($question_temp as $value) {
			if (preg_match("/[\'\"\<\>]+/", $value)) {
				$str = $value;
				if (preg_match("/(\')+/", $str)) {
					$str = preg_replace("/(\')+/", '&#39;', $str);
				}
				if (preg_match("/(\")+/", $str)) {
					$str = preg_replace("/(\")+/", '&#34;', $str);
				}
				if (preg_match("/(<)+/", $str)) {
					$str = preg_replace("/(<)+/", "", $str);
				}
				if (preg_match("/(>)+/", $str)) {
					$str = preg_replace("/(>)+/", "", $str);
				}
				$question[] = $str;
			} else {
				$question[] = $value;
			}
		}
	} else {
		$question = $question_temp;
	}

	$test = array();
	if (!empty($test_temp)) {
		for ($f = 0; $f < count($test_temp); $f++) {
			$temp_input = $test_temp[$f][0];
			$temp_checked = $test_temp[$f][1];
			if (!empty($temp_input) && is_array($temp_input)) {
				foreach ($temp_input as $value) {
					if (!empty($value) && preg_match("/\S+/", $value)) {
						if (preg_match("/[\'\"\<\>]+/", $value)) {
							$str = $value;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][0][] = $str;
						} else {
							$test[$f][0][] = $value;
						}
					} else {
						if ($input_count[$f] > 2) {
							$input_count[$f]--;
						}
					}
				}
			}

			if (!empty($temp_checked) && is_array($temp_checked)) {
				foreach ($temp_checked as $val) {
					if (!empty($val) && preg_match("/\S+/", $val)) {
						if (preg_match("/[\'\"\<\>]+/", $val)) {
							$str = $val;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][1][] = $str;
						} else {
							$test[$f][1][] = $val;
						}
					}
				}
			}
		}
	}
	
	return(array($question, $test, $select, $block_count, $input_count));
}

add_action('cmb_render_go_test_field_accept', 'go_test_field_accept', 10, 1);
function go_test_field_accept($field_args) {
	$custom = get_post_custom(get_the_id());

	$temp_array = $custom["go_mta_test_lock_accept"][0];
	$temp_uns = unserialize($temp_array);
	$test_field_input_question = $temp_uns[0];
	$test_field_input_array = $temp_uns[1];
	$test_field_select_array = $temp_uns[2];
	$test_field_block_count = (int)$temp_uns[3];
	$test_field_input_count = $temp_uns[4];

	?>
	<span class='cmb_metabox_description'>
		<?php echo $field_args['desc']; ?>
	</span>
	<table id='go_test_field_table_a' class='go_test_field_table'>
		<?php 
		if (!empty($test_field_block_count)) {
			for ($i = 0; $i < $test_field_block_count; $i++) {
				echo "
				<tr id='go_test_field_input_row_a_".$i."' class='go_test_field_input_row_a go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_a_".$i."' class='go_test_field_input_select_a' name='go_test_field_select_a[]' onchange='update_checkbox_type_a(this);'>
						  <option value='radio' class='go_test_field_input_option_a'>Multiple Choice</option>
						  <option value='checkbox' class='go_test_field_input_option_a'>Multiple Select</option>
						</select>";
						if (!empty($test_field_input_question)) {
							echo "<br/><br/><input class='go_test_field_input_question_a go_test_field_input_question' name='go_test_field_input_question_a[]' placeholder='Shall We Play a Game?' type='text' value='".$test_field_input_question[$i]."' />";
						} else {
							echo "<br/><br/><input class='go_test_field_input_question_a go_test_field_input_question' name='go_test_field_input_question_a[]' placeholder='Shall We Play a Game?' type='text' />";
						}
				if (!empty($test_field_input_count)) {
					echo "<ul>";
					for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
						echo "
							<li><input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_a_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_a(this);' />
							<input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a[".$i."][1][]' type='hidden' />
							<input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][$x]."' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' />";
						if ($x > 1) {
							echo "<input class='go_test_field_rm go_test_field_rm_input_button_a' type='button' value='X' onclick='remove_field_a(this);'>";
						}
						echo "</li>";
						if (($x + 1) == $test_field_input_count[$i]) {
							echo "<input class='go_test_field_add go_test_field_add_input_button_a' type='button' value='+' onclick='add_field_a(this);'/>";
						}
					}
					echo "</ul><ul>";
					if ($i > 0) {
						echo "<li><input class='go_test_field_rm_row_button_a go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_a(this);' /></li>";
					}
					echo "<li><input class='go_test_field_input_count_a' name='go_test_field_input_count_a[]' type='hidden' value='".$test_field_input_count[$i]."' /></li></ul>";
				} else {
					echo "
					<ul>
						<li><input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='go_test_field_input_checkbox_a_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_a(this);' /><input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a[".$i."][1][]' type='hidden' /><input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][0]."' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' /></li>
						<li><input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='go_test_field_input_checkbox_a_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_a(this);' /><input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a[".$i."][1][]' type='hidden' /><input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][1]."' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' /></li>";
					echo "</ul><ul><li>";
					if ($i > 0) {
						echo "<input class='go_test_field_rm_row_button_a go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_a(this);' /></li><li>";
					}
					echo "<input class='go_test_field_input_count_a' name='go_test_field_input_count_a[]' type='hidden' value='2' /></li></ul>";
				}
				echo "
					</td>
				</tr>";
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_a_0' class='go_test_field_input_row_a go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_a_0' class='go_test_field_input_select_a' name='go_test_field_select_a[]' onchange='update_checkbox_type_a(this);'>
							<option value='radio' class='go_test_field_input_option_a'>Multiple Choice</option>
							<option value='checkbox' class='go_test_field_input_option_a'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_a go_test_field_input_question' name='go_test_field_input_question_a[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_a_0' type='radio' onchange='update_checkbox_value_a(this);' />
								<input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a[0][1][]' type='hidden' />
								<input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_a_0' type='radio' onchange='update_checkbox_value_a(this);' />
								<input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a[0][1][]' type='hidden' />
								<input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' />
							</li>
							<input class='go_test_field_add go_test_field_add_input_button_a' type='button' value='+' onclick='add_field_a(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count_a' name='go_test_field_input_count_a[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
		}
		?>
		<tr>
			<td>
				<input id='go_test_field_add_block_button_a' class='go_test_field_add_block_button' value='Add Block' type='button' onclick='add_block_a(this);' />
				<?php 
				if (!empty($test_field_block_count)) {
					echo "<input id='go_test_field_block_count_a' name='go_test_field_block_count_a' type='hidden' value='".$test_field_block_count."' />";
				} else {
					echo "<input id='go_test_field_block_count_a' name='go_test_field_block_count_a' type='hidden' value='1' />";
				}
				?>
			</td>
		</tr>
	</table>
	<script type='text/javascript'>
		var block_num_a = 0;
		var block_type_a = 'radio';
		var input_num_a = 0;
		var block_count_a = <?php echo $test_field_block_count; ?>;
		
		var test_field_select_array_a = new Array(
			<?php 
			if (!empty($test_field_block_count)) {
				for ($i = 0; $i < $test_field_block_count; $i++) {
					echo '"'.ucwords($test_field_select_array[$i]).'"';
					if (($i + 1) != $test_field_block_count) { 
						echo ', ';
					}
				}
			}
			?>
		);
		var test_field_checked_array_a = [
			<?php
			if (!empty($test_field_block_count)) {
				for ($x = 0; $x < $test_field_block_count; $x++) {
					echo "[";
					if (!empty($test_field_input_array[$x][0]) && !empty($test_field_input_array[$x][1])) {
						$intersection = array_intersect($test_field_input_array[$x][0], $test_field_input_array[$x][1]);
						$checked_intersection = array_values($intersection);
						for ($i = 0; $i < count($checked_intersection); $i++) {
							$value = $checked_intersection[$i];
							if (preg_match("/(\&\#39;|\&\#34;)+/", $value)) {
								$str = $value;
								if (preg_match("/(\&\#39;)+/", $str)) {
									$str = preg_replace("/(\&\#39;)+/", "\'", $str);
								}
								
								if (preg_match("/(\&\#34;)+/", $str)) {
									$str = preg_replace("/(\&\#34;)+/", '\"', $str);
								}
								echo '"'.$str.'"';
							} else {
								echo '"'.$value.'"';
							}
							if (($i) < count($checked_intersection)) {
								echo ", ";
							}
						}
					}
					echo "]";
					if (($x + 1) < $test_field_block_count) {
						echo ", ";
					}
				}
			}
			?>
		];
		for (var i = 0; i < test_field_select_array_a.length; i++) {
			var test_field_with_select_value = '#go_test_field_select_a_'+i+' .go_test_field_input_option_a:contains(\''+test_field_select_array_a[i]+'\')';
			jQuery(test_field_with_select_value).attr('selected', true);
		}
		for (var x = 0; x < block_count_a; x++) {
			for (var z = 0; z < test_field_checked_array_a[x].length; z++) {
				var test_fields_with_checked_value = "tr#go_test_field_input_row_a_"+[x]+" .go_test_field_input_a[value='"+test_field_checked_array_a[x][z]+"']";
				var checked_fields = jQuery(test_fields_with_checked_value).siblings('.go_test_field_input_checkbox_a').attr('checked', true);
			}
		}
		var checkbox_obj_array = jQuery('.go_test_field_input_checkbox_a');
		for (var y = 0; y < checkbox_obj_array.length; y++) {
			var next_obj = checkbox_obj_array[y].nextElementSibling;
			if (checkbox_obj_array[y].checked) {
				var input_obj = next_obj.nextElementSibling.value;
				jQuery(next_obj).attr('value', input_obj);
			} else {
				jQuery(next_obj).removeAttr('value');
			}
		}
		function update_checkbox_value_a (target) {
			if (jQuery(target).hasClass('go_test_field_input_a')) {
				var obj = jQuery(target).siblings('.go_test_field_input_checkbox_a');
			} else {
				var obj = target;
			}
			var checkbox_type = jQuery(obj).prop('type');
			var input_field_val = jQuery(obj).siblings('.go_test_field_input_a').val();
			if (checkbox_type === 'radio') {
				var radio_name = jQuery(obj).prop('name');
				var radio_checked_str = ".go_test_field_input_checkbox_a[name='"+radio_name+"']:checked";
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_a').attr('value', input_field_val);
					} else {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_a').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_a').removeAttr('value');
				}
				var radios_not_checked_str = ".go_test_field_input_checkbox_a[name='"+radio_name+"']:not(:checked)";
				jQuery(radios_not_checked_str).siblings('.go_test_field_input_checkbox_hidden_a').removeAttr('value');
			} else {
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_a').attr('value', input_field_val);	
					} else {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_a').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_a').removeAttr('value');
				}
			}
		}
		function update_checkbox_type_a (obj) {
			block_type_a = jQuery(obj).children('option:selected').val();
			jQuery(obj).siblings('ul').children('li').children('input.go_test_field_input_checkbox_a').attr('type', block_type_a);
		}
		function add_block_a (obj) {
			block_num_a = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_a').length;
			jQuery('#go_test_field_block_count_a').attr('value', (block_num_a + 1));
			var field_block = "<tr id='go_test_field_input_row_a_"+block_num_a+"' class='go_test_field_input_row_a go_test_field_input_row'><td><select id='go_test_field_select_a_"+block_num_a+"' class='go_test_field_input_select_a' name='go_test_field_select_a[]' onchange='update_checkbox_type_a(this);'><option value='radio' class='go_test_field_input_option_a'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option_a'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question_a go_test_field_input_question' name='go_test_field_input_question_a[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_a_"+block_num_a+"' type='"+block_type_a+"' onchange='update_checkbox_value_a(this);' /><input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a["+block_num_a+"][1][]' type='hidden' /><input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a["+block_num_a+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' /></li><li><input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_a_"+block_num_a+"' type='"+block_type_a+"' onchange='update_checkbox_value_a(this);' /><input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a["+block_num_a+"][1][]' type='hidden' /><input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a["+block_num_a+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' /></li><input class='go_test_field_add go_test_field_add_input_button_a' type='button' value='+' onclick='add_field_a(this);'/></ul><ul><li><input class='go_test_field_rm_row_button_a go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block_a(this);' /><input class='go_test_field_input_count_a' name='go_test_field_input_count_a[]' type='hidden' value='2' /></li></ul></td></tr>";
			jQuery(obj).parent().parent().before(field_block);
		}
		function remove_block_a (obj) {
			block_num_a = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_a').length;
			jQuery('#go_test_field_block_count_a').attr('value', (block_num_a - 1));
			jQuery(obj).parents('tr.go_test_field_input_row_a').remove();
		}
		function add_field_a (obj) {
			input_num_a = jQuery(obj).siblings('li').length + 1;
			var block_id = jQuery(obj).parents('tr.go_test_field_input_row_a').first().attr('id');
			block_num_a = block_id.split('go_test_field_input_row_a_').pop();
			block_type_a = jQuery(obj).parent('ul').siblings('select').children('option:selected').val();
			jQuery(obj).parent('ul').siblings('ul').children('li').children('.go_test_field_input_count_a').attr('value', input_num_a);
			jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox_a go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_a_"+block_num_a+"' type='"+block_type_a+"' onchange='update_checkbox_value_a(this);' /><input class='go_test_field_input_checkbox_hidden_a' name='go_test_field_values_a["+block_num_a+"][1][]' type='hidden' /><input class='go_test_field_input_a go_test_field_input' name='go_test_field_values_a["+block_num_a+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_a(this);' oncut='update_checkbox_value_a(this);' onpaste='update_checkbox_value_a(this);' /><input class='go_test_field_rm go_test_field_rm_input_button_a' type='button' value='X' onclick='remove_field_a(this);'></li>");
		}
		function remove_field_a (obj) {
			jQuery(obj).parents('tr.go_test_field_input_row_a').find('input.go_test_field_input_count_a')[0].value--;
			jQuery(obj).parent('li').remove();
		}
		
	</script>
	<?php
}

add_action('cmb_validate_go_test_field_accept', 'go_validate_test_field_accept');
function go_validate_test_field_accept() {
	$question_temp = $_POST['go_test_field_input_question_a'];
	$test_temp = $_POST['go_test_field_values_a'];
	$select = $_POST['go_test_field_select_a'];
	$block_count = (int)$_POST['go_test_field_block_count_a'];
	$input_count_temp = $_POST['go_test_field_input_count_a'];

	$input_count = array();
	if (!empty($input_count_temp)) {
		foreach ($input_count_temp as $key => $value) {
			$temp = (int)$input_count_temp[$key];
			array_push($input_count, $temp);
		}
	}

	$question = array();
	if (!empty($question_temp) && is_array($question_temp)) {
		foreach ($question_temp as $value) {
			if (preg_match("/[\'\"\<\>]+/", $value)) {
				$str = $value;
				if (preg_match("/(\')+/", $str)) {
					$str = preg_replace("/(\')+/", '&#39;', $str);
				}
				if (preg_match("/(\")+/", $str)) {
					$str = preg_replace("/(\")+/", '&#34;', $str);
				}
				if (preg_match("/(<)+/", $str)) {
					$str = preg_replace("/(<)+/", "", $str);
				}
				if (preg_match("/(>)+/", $str)) {
					$str = preg_replace("/(>)+/", "", $str);
				}
				$question[] = $str;
			} else {
				$question[] = $value;
			}
		}
	} else {
		$question = $question_temp;
	}

	$test = array();
	if (!empty($test_temp)) {
		for ($f = 0; $f < count($test_temp); $f++) {
			$temp_input = $test_temp[$f][0];
			$temp_checked = $test_temp[$f][1];
			if (!empty($temp_input) && is_array($temp_input)) {
				foreach ($temp_input as $value) {
					if (!empty($value) && preg_match("/\S+/", $value)) {
						if (preg_match("/[\'\"\<\>]+/", $value)) {
							$str = $value;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][0][] = $str;
						} else {
							$test[$f][0][] = $value;
						}
					} else {
						if ($input_count[$f] > 2) {
							$input_count[$f]--;
						}
					}
				}
			}

			if (!empty($temp_checked) && is_array($temp_checked)) {
				foreach ($temp_checked as $val) {
					if (!empty($val) && preg_match("/\S+/", $val)) {
						if (preg_match("/[\'\"\<\>]+/", $val)) {
							$str = $val;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][1][] = $str;
						} else {
							$test[$f][1][] = $val;
						}
					}
				}
			}
		}
	}
	
	return(array($question, $test, $select, $block_count, $input_count));
}

add_action('cmb_render_go_test_field_completion', 'go_test_field_completion', 10, 1);
function go_test_field_completion($field_args) {
	$custom = get_post_custom(get_the_id());

	$temp_array = $custom["go_mta_test_lock_completion"][0];
	$temp_uns = unserialize($temp_array);
	$test_field_input_question = $temp_uns[0];
	$test_field_input_array = $temp_uns[1];
	$test_field_select_array = $temp_uns[2];
	$test_field_block_count = (int)$temp_uns[3];
	$test_field_input_count = $temp_uns[4];

	?>
	<span class='cmb_metabox_description'>
		<?php echo $field_args['desc']; ?>
	</span>
	<table id='go_test_field_table_c' class='go_test_field_table'>
		<?php 
		if (!empty($test_field_block_count)) {
			for ($i = 0; $i < $test_field_block_count; $i++) {
				echo "
				<tr id='go_test_field_input_row_c_".$i."' class='go_test_field_input_row_c go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_c_".$i."' class='go_test_field_input_select_c' name='go_test_field_select_c[]' onchange='update_checkbox_type_c(this);'>
						  <option value='radio' class='go_test_field_input_option_c'>Multiple Choice</option>
						  <option value='checkbox' class='go_test_field_input_option_c'>Multiple Select</option>
						</select>";
						if (!empty($test_field_input_question)) {
							echo "<br/><br/><input class='go_test_field_input_question_c go_test_field_input_question' name='go_test_field_input_question_c[]' placeholder='Shall We Play a Game?' type='text' value='".$test_field_input_question[$i]."' />";
						} else {
							echo "<br/><br/><input class='go_test_field_input_question_c go_test_field_input_question' name='go_test_field_input_question_c[]' placeholder='Shall We Play a Game?' type='text' />";
						}
				if (!empty($test_field_input_count)) {
					echo "<ul>";
					for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
						echo "
							<li><input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_c_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_c(this);' />
							<input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c[".$i."][1][]' type='hidden' />
							<input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][$x]."' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' />";
						if ($x > 1) {
							echo "<input class='go_test_field_rm go_test_field_rm_input_button_c' type='button' value='X' onclick='remove_field_c(this);'>";
						}
						echo "</li>";
						if (($x + 1) == $test_field_input_count[$i]) {
							echo "<input class='go_test_field_add go_test_field_add_input_button_c' type='button' value='+' onclick='add_field_c(this);'/>";
						}
					}
					echo "</ul><ul>";
					if ($i > 0) {
						echo "<li><input class='go_test_field_rm_row_button_c go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_c(this);' /></li>";
					}
					echo "<li><input class='go_test_field_input_count_c' name='go_test_field_input_count_c[]' type='hidden' value='".$test_field_input_count[$i]."' /></li></ul>";
				} else {
					echo "
					<ul>
						<li><input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='go_test_field_input_checkbox_c_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_c(this);' /><input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c[".$i."][1][]' type='hidden' /><input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][0]."' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' /></li>
						<li><input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='go_test_field_input_checkbox_c_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_c(this);' /><input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c[".$i."][1][]' type='hidden' /><input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][1]."' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' /></li>";
					echo "</ul><ul><li>";
					if ($i > 0) {
						echo "<input class='go_test_field_rm_row_button_c go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_c(this);' /></li><li>";
					}
					echo "<input class='go_test_field_input_count_c' name='go_test_field_input_count_c[]' type='hidden' value='2' /></li></ul>";
				}
				echo "
					</td>
				</tr>";
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_c_0' class='go_test_field_input_row_c go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_c_0' class='go_test_field_input_select_c' name='go_test_field_select_c[]' onchange='update_checkbox_type_c(this);'>
							<option value='radio' class='go_test_field_input_option_c'>Multiple Choice</option>
							<option value='checkbox' class='go_test_field_input_option_c'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_c go_test_field_input_question' name='go_test_field_input_question_c[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_c_0' type='radio' onchange='update_checkbox_value_c(this);' />
								<input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c[0][1][]' type='hidden' />
								<input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_c_0' type='radio' onchange='update_checkbox_value_c(this);' />
								<input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c[0][1][]' type='hidden' />
								<input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' />
							</li>
							<input class='go_test_field_add go_test_field_add_input_button_c' type='button' value='+' onclick='add_field_c(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count_c' name='go_test_field_input_count_c[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
		}
		?>
		<tr>
			<td>
				<input id='go_test_field_add_block_button_c' class='go_test_field_add_block_button' value='Add Block' type='button' onclick='add_block_c(this);' />
				<?php 
				if (!empty($test_field_block_count)) {
					echo "<input id='go_test_field_block_count_c' name='go_test_field_block_count_c' type='hidden' value='".$test_field_block_count."' />";
				} else {
					echo "<input id='go_test_field_block_count_c' name='go_test_field_block_count_c' type='hidden' value='1' />";
				}
				?>
			</td>
		</tr>
	</table>
	<script type='text/javascript'>
		var block_num_c = 0;
		var block_type_c = 'radio';
		var input_num_c = 0;
		var block_count_c = <?php echo $test_field_block_count; ?>;
		
		var test_field_select_array_c = new Array(
			<?php 
			if (!empty($test_field_block_count)) {
				for ($i = 0; $i < $test_field_block_count; $i++) {
					echo '"'.ucwords($test_field_select_array[$i]).'"';
					if (($i + 1) != $test_field_block_count) { 
						echo ', ';
					}
				}
			}
			?>
		);
		var test_field_checked_array_c = [
			<?php
			if (!empty($test_field_block_count)) {
				for ($x = 0; $x < $test_field_block_count; $x++) {
					echo "[";
					if (!empty($test_field_input_array[$x][0]) && !empty($test_field_input_array[$x][1])) {
						$intersection = array_intersect($test_field_input_array[$x][0], $test_field_input_array[$x][1]);
						$checked_intersection = array_values($intersection);
						for ($i = 0; $i < count($checked_intersection); $i++) {
							$value = $checked_intersection[$i];
							if (preg_match("/(\&\#39;|\&\#34;)+/", $value)) {
								$str = $value;
								if (preg_match("/(\&\#39;)+/", $str)) {
									$str = preg_replace("/(\&\#39;)+/", "\'", $str);
								}
								
								if (preg_match("/(\&\#34;)+/", $str)) {
									$str = preg_replace("/(\&\#34;)+/", '\"', $str);
								}
								echo '"'.$str.'"';
							} else {
								echo '"'.$value.'"';
							}
							if (($i) < count($checked_intersection)) {
								echo ", ";
							}
						}
					}
					echo "]";
					if (($x + 1) < $test_field_block_count) {
						echo ", ";
					}
				}
			}
			?>
		];
		for (var i = 0; i < test_field_select_array_c.length; i++) {
			var test_field_with_select_value = '#go_test_field_select_c_'+i+' .go_test_field_input_option_c:contains(\''+test_field_select_array_c[i]+'\')';
			jQuery(test_field_with_select_value).attr('selected', true);
		}
		for (var x = 0; x < block_count_c; x++) {
			for (var z = 0; z < test_field_checked_array_c[x].length; z++) {
				var test_fields_with_checked_value = "tr#go_test_field_input_row_c_"+[x]+" .go_test_field_input_c[value='"+test_field_checked_array_c[x][z]+"']";
				jQuery(test_fields_with_checked_value).siblings('.go_test_field_input_checkbox_c').attr('checked', true);
			}
		}
		var checkbox_obj_array = jQuery('.go_test_field_input_checkbox_c');
		for (var y = 0; y < checkbox_obj_array.length; y++) {
			var next_obj = checkbox_obj_array[y].nextElementSibling;
			if (checkbox_obj_array[y].checked) {
				var input_obj = next_obj.nextElementSibling.value;
				jQuery(next_obj).attr('value', input_obj);
			} else {
				jQuery(next_obj).removeAttr('value');
			}
		}
		function update_checkbox_value_c (target) {
			if (jQuery(target).hasClass('go_test_field_input_c')) {
				var obj = jQuery(target).siblings('.go_test_field_input_checkbox_c');
			} else {
				var obj = target;
			}
			var checkbox_type = jQuery(obj).prop('type');
			var input_field_val = jQuery(obj).siblings('.go_test_field_input_c').val();
			if (checkbox_type === 'radio') {
				var radio_name = jQuery(obj).prop('name');
				var radio_checked_str = ".go_test_field_input_checkbox_c[name='"+radio_name+"']:checked";
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_c').attr('value', input_field_val);
					} else {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_c').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_c').removeAttr('value');
				}
				var radios_not_checked_str = ".go_test_field_input_checkbox_c[name='"+radio_name+"']:not(:checked)";
				jQuery(radios_not_checked_str).siblings('.go_test_field_input_checkbox_hidden_c').removeAttr('value');
			} else {
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_c').attr('value', input_field_val);	
					} else {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_c').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_c').removeAttr('value');
				}
			}
		}
		function update_checkbox_type_c (obj) {
			block_type_c = jQuery(obj).children('option:selected').val();
			jQuery(obj).siblings('ul').children('li').children('input.go_test_field_input_checkbox_c').attr('type', block_type_c);
		}
		function add_block_c (obj) {
			block_num_c = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_c').length;
			jQuery('#go_test_field_block_count_c').attr('value', (block_num_c + 1));
			var field_block = "<tr id='go_test_field_input_row_c_"+block_num_c+"' class='go_test_field_input_row_c go_test_field_input_row'><td><select id='go_test_field_select_c_"+block_num_c+"' class='go_test_field_input_select_c' name='go_test_field_select_c[]' onchange='update_checkbox_type_c(this);'><option value='radio' class='go_test_field_input_option_c'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option_c'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question_c go_test_field_input_question' name='go_test_field_input_question_c[]' placeholder='' type='text' /><ul><li><input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_c_"+block_num_c+"' type='"+block_type_c+"' onchange='update_checkbox_value_c(this);' /><input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c["+block_num_c+"][1][]' type='hidden' /><input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c["+block_num_c+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' /></li><li><input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_c_"+block_num_c+"' type='"+block_type_c+"' onchange='update_checkbox_value_c(this);' /><input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c["+block_num_c+"][1][]' type='hidden' /><input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c["+block_num_c+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' /></li><input class='go_test_field_add go_test_field_add_input_button_c' type='button' value='+' onclick='add_field_c(this);'/></ul><ul><li><input class='go_test_field_rm_row_button_c go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block_c(this);' /><input class='go_test_field_input_count_c' name='go_test_field_input_count_c[]' type='hidden' value='2' /></li></ul></td></tr>";
			jQuery(obj).parent().parent().before(field_block);
		}
		function remove_block_c (obj) {
			block_num_c = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_c').length;
			jQuery('#go_test_field_block_count_c').attr('value', (block_num_c - 1));
			jQuery(obj).parents('tr.go_test_field_input_row_c').remove();
		}
		function add_field_c (obj) {
			input_num_c = jQuery(obj).siblings('li').length + 1;
			var block_id = jQuery(obj).parents('tr.go_test_field_input_row_c').first().attr('id');
			block_num_c = block_id.split('go_test_field_input_row_c_').pop();
			block_type_c = jQuery(obj).parent('ul').siblings('select').children('option:selected').val();
			jQuery(obj).parent('ul').siblings('ul').children('li').children('.go_test_field_input_count_c').attr('value', input_num_c);
			jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox_c go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_c_"+block_num_c+"' type='"+block_type_c+"' onchange='update_checkbox_value_c(this);' /><input class='go_test_field_input_checkbox_hidden_c' name='go_test_field_values_c["+block_num_c+"][1][]' type='hidden' /><input class='go_test_field_input_c go_test_field_input' name='go_test_field_values_c["+block_num_c+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_c(this);' oncut='update_checkbox_value_c(this);' onpaste='update_checkbox_value_c(this);' /><input class='go_test_field_rm go_test_field_rm_input_button_c' type='button' value='X' onclick='remove_field_c(this);'></li>");
		}
		function remove_field_c (obj) {
			jQuery(obj).parents('tr.go_test_field_input_row_c').find('input.go_test_field_input_count_c')[0].value--;
			jQuery(obj).parent('li').remove();
		}
		
	</script>
	<?php
}

add_action('cmb_validate_go_test_field_completion', 'go_validate_test_field_completion');
function go_validate_test_field_completion() {
	$question_temp = $_POST['go_test_field_input_question_c'];
	$test_temp = $_POST['go_test_field_values_c'];
	$select = $_POST['go_test_field_select_c'];
	$block_count = (int)$_POST['go_test_field_block_count_c'];
	$input_count_temp = $_POST['go_test_field_input_count_c'];

	$input_count = array();
	if (!empty($input_count_temp)) {
		foreach ($input_count_temp as $key => $value) {
			$temp = (int)$input_count_temp[$key];
			array_push($input_count, $temp);
		}
	}

	$question = array();
	if (!empty($question_temp) && is_array($question_temp)) {
		foreach ($question_temp as $value) {
			if (preg_match("/[\'\"\<\>]+/", $value)) {
				$str = $value;
				if (preg_match("/(\')+/", $str)) {
					$str = preg_replace("/(\')+/", '&#39;', $str);
				}
				if (preg_match("/(\")+/", $str)) {
					$str = preg_replace("/(\")+/", '&#34;', $str);
				}
				if (preg_match("/(<)+/", $str)) {
					$str = preg_replace("/(<)+/", "", $str);
				}
				if (preg_match("/(>)+/", $str)) {
					$str = preg_replace("/(>)+/", "", $str);
				}
				$question[] = $str;
			} else {
				$question[] = $value;
			}
		}
	} else {
		$question = $question_temp;
	}

	$test = array();
	if (!empty($test_temp)) {
		for ($f = 0; $f < count($test_temp); $f++) {
			$temp_input = $test_temp[$f][0];
			$temp_checked = $test_temp[$f][1];
			if (!empty($temp_input) && is_array($temp_input)) {
				foreach ($temp_input as $value) {
					if (!empty($value) && preg_match("/\S+/", $value)) {
						if (preg_match("/[\'\"\<\>]+/", $value)) {
							$str = $value;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][0][] = $str;
						} else {
							$test[$f][0][] = $value;
						}
					} else {
						if ($input_count[$f] > 2) {
							$input_count[$f]--;
						}
					}
				}
			}

			if (!empty($temp_checked) && is_array($temp_checked)) {
				foreach ($temp_checked as $val) {
					if (!empty($val) && preg_match("/\S+/", $val)) {
						if (preg_match("/[\'\"\<\>]+/", $val)) {
							$str = $val;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][1][] = $str;
						} else {
							$test[$f][1][] = $val;
						}
					}
				}
			}
		}
	}
	
	return(array($question, $test, $select, $block_count, $input_count));
}

add_action('cmb_render_go_test_field_mastery', 'go_test_field_mastery', 10, 1);
function go_test_field_mastery($field_args) {
	$custom = get_post_custom(get_the_id());

	$temp_array = $custom["go_mta_test_lock_mastery"][0];
	$temp_uns = unserialize($temp_array);
	$test_field_input_question = $temp_uns[0];
	$test_field_input_array = $temp_uns[1];
	$test_field_select_array = $temp_uns[2];
	$test_field_block_count = (int)$temp_uns[3];
	$test_field_input_count = $temp_uns[4];

	?>
	<p>
		<?php echo $field_args['desc']; ?>
	</p>
	<table id='go_test_field_table_m' class='go_test_field_table'>
		<?php 
		if (!empty($test_field_block_count)) {
			for ($i = 0; $i < $test_field_block_count; $i++) {
				echo "
				<tr id='go_test_field_input_row_m_".$i."' class='go_test_field_input_row_m go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_m_".$i."' class='go_test_field_input_select_m' name='go_test_field_select_m[]' onchange='update_checkbox_type_m(this);'>
						  <option value='radio' class='go_test_field_input_option_m'>Multiple Choice</option>
						  <option value='checkbox' class='go_test_field_input_option_m'>Multiple Select</option>
						</select>";
						if (!empty($test_field_input_question)) {
							echo "<br/><br/><input class='go_test_field_input_question_m go_test_field_input_question' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' value='".$test_field_input_question[$i]."' />";
						} else {
							echo "<br/><br/><input class='go_test_field_input_question_m go_test_field_input_question' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' />";
						}
				if (!empty($test_field_input_count)) {
					echo "<ul>";
					for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
						echo "
							<li><input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_m_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_m(this);' />
							<input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[".$i."][1][]' type='hidden' />
							<input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][$x]."' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' />";
						if ($x > 1) {
							echo "<input class='go_test_field_rm go_test_field_rm_input_button_m' type='button' value='X' onclick='remove_field_m(this);'>";
						}
						echo "</li>";
						if (($x + 1) == $test_field_input_count[$i]) {
							echo "<input class='go_test_field_add go_test_field_add_input_button_m' type='button' value='+' onclick='add_field_m(this);'/>";
						}
					}
					echo "</ul><ul>";
					if ($i > 0) {
						echo "<li><input class='go_test_field_rm_row_button_m go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_m(this);' /></li>";
					}
					echo "<li><input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='".$test_field_input_count[$i]."' /></li></ul>";
				} else {
					echo "
					<ul>
						<li><input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='go_test_field_input_checkbox_m_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[".$i."][1][]' type='hidden' /><input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][0]."' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li>
						<li><input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='go_test_field_input_checkbox_m_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[".$i."][1][]' type='hidden' /><input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m[".$i."][0][]' placeholder='Enter an answer!' type='text' value='".$test_field_input_array[$i][0][1]."' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li>";
					echo "</ul><ul><li>";
					if ($i > 0) {
						echo "<input class='go_test_field_rm_row_button_m go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_m(this);' /></li><li>";
					}
					echo "<input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='2' /></li></ul>";
				}
				echo "
					</td>
				</tr>";
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_m_0' class='go_test_field_input_row_m go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_m_0' class='go_test_field_input_select_m' name='go_test_field_select_m[]' onchange='update_checkbox_type_m(this);'>
							<option value='radio' class='go_test_field_input_option_m'>Multiple Choice</option>
							<option value='checkbox' class='go_test_field_input_option_m'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_m go_test_field_input_question' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_m_0' type='radio' onchange='update_checkbox_value_m(this);' />
								<input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[0][1][]' type='hidden' />
								<input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m[0][0][]' placeholder='Enter an answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_m_0' type='radio' onchange='update_checkbox_value_m(this);' />
								<input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[0][1][]' type='hidden' />
								<input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m[0][0][]' placeholder='Enter an answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' />
							</li>
							<input class='go_test_field_add go_test_field_add_input_button_m' type='button' value='+' onclick='add_field_m(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
		}
		?>
		<tr>
			<td>
				<input id='go_test_field_add_block_button_m' class='go_test_field_add_block_button' value='Add Block' type='button' onclick='add_block_m(this);' />
				<?php 
				if (!empty($test_field_block_count)) {
					echo "<input id='go_test_field_block_count_m' name='go_test_field_block_count_m' type='hidden' value='".$test_field_block_count."' />";
				} else {
					echo "<input id='go_test_field_block_count_m' name='go_test_field_block_count_m' type='hidden' value='1' />";
				}
				?>
			</td>
		</tr>
	</table>
	<script type='text/javascript'>
		var block_num_m = 0;
		var block_type_m = 'radio';
		var input_num_m = 0;
		var block_count_m = <?php echo $test_field_block_count; ?>;
		
		var test_field_select_array_m = new Array(
			<?php 
			if (!empty($test_field_block_count)) {
				for ($i = 0; $i < $test_field_block_count; $i++) {
					echo '"'.ucwords($test_field_select_array[$i]).'"';
					if (($i + 1) != $test_field_block_count) { 
						echo ', ';
					}
				}
			}
			?>
		);
		var test_field_checked_array_m = [
			<?php
			if (!empty($test_field_block_count)) {
				for ($x = 0; $x < $test_field_block_count; $x++) {
					echo "[";
					if (!empty($test_field_input_array[$x][0]) && !empty($test_field_input_array[$x][1])) {
						$intersection = array_intersect($test_field_input_array[$x][0], $test_field_input_array[$x][1]);
						$checked_intersection = array_values($intersection);
						for ($i = 0; $i < count($checked_intersection); $i++) {
							$value = $checked_intersection[$i];
							if (preg_match("/(\&\#39;|\&\#34;)+/", $value)) {
								$str = $value;
								if (preg_match("/(\&\#39;)+/", $str)) {
									$str = preg_replace("/(\&\#39;)+/", "\'", $str);
								}
								
								if (preg_match("/(\&\#34;)+/", $str)) {
									$str = preg_replace("/(\&\#34;)+/", '\"', $str);
								}
								echo '"'.$str.'"';
							} else {
								echo '"'.$value.'"';
							}
							if (($i) < count($checked_intersection)) {
								echo ", ";
							}
						}
					}
					echo "]";
					if (($x + 1) < $test_field_block_count) {
						echo ", ";
					}
				}
			}
			?>
		];
		for (var i = 0; i < test_field_select_array_m.length; i++) {
			var test_field_with_select_value_m = '#go_test_field_select_m_'+i+' .go_test_field_input_option_m:contains(\''+test_field_select_array_m[i]+'\')';
			jQuery(test_field_with_select_value_m).attr('selected', true);
		}
		for (var x = 0; x < block_count_m; x++) {
			for (var z = 0; z < test_field_checked_array_m[x].length; z++) {
				var test_fields_with_checked_value = "tr#go_test_field_input_row_m_"+[x]+" .go_test_field_input_m[value='"+test_field_checked_array_m[x][z]+"']";
				jQuery(test_fields_with_checked_value).siblings('.go_test_field_input_checkbox_m').attr('checked', true);
			}
		}
		var checkbox_obj_array_m = jQuery('.go_test_field_input_checkbox_m');
		for (var y = 0; y < checkbox_obj_array_m.length; y++) {
			var next_obj = checkbox_obj_array_m[y].nextElementSibling;
			if (checkbox_obj_array_m[y].checked) {
				var input_obj = next_obj.nextElementSibling.value;
				jQuery(next_obj).attr('value', input_obj);
			} else {
				jQuery(next_obj).removeAttr('value');
			}
		}
		function update_checkbox_value_m (target) {
			if (jQuery(target).hasClass('go_test_field_input_m')) {
				var obj = jQuery(target).siblings('.go_test_field_input_checkbox_m');
			} else {
				var obj = target;
			}
			var checkbox_type = jQuery(obj).prop('type');
			var input_field_val = jQuery(obj).siblings('.go_test_field_input_m').val();
			if (checkbox_type === 'radio') {
				var radio_name = jQuery(obj).prop('name');
				var radio_checked_str = ".go_test_field_input_checkbox_m[name='"+radio_name+"']:checked";
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_m').attr('value', input_field_val);
					} else {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden_m').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_m').removeAttr('value');
				}
				var radios_not_checked_str = ".go_test_field_input_checkbox_m[name='"+radio_name+"']:not(:checked)";
				jQuery(radios_not_checked_str).siblings('.go_test_field_input_checkbox_hidden_m').removeAttr('value');
			} else {
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_m').attr('value', input_field_val);	
					} else {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_m').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden_m').removeAttr('value');
				}
			}
		}
		function update_checkbox_type_m (obj) {
			block_type_m = jQuery(obj).children('option:selected').val();
			jQuery(obj).siblings('ul').children('li').children('input.go_test_field_input_checkbox_m').attr('type', block_type_m);
		}
		function add_block_m (obj) {
			block_num_m = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_m').length;
			jQuery('#go_test_field_block_count_m').attr('value', (block_num_m + 1));
			var field_block = "<tr id='go_test_field_input_row_m_"+block_num_m+"' class='go_test_field_input_row_m go_test_field_input_row'><td><select id='go_test_field_select_m_"+block_num_m+"' class='go_test_field_input_select_m' name='go_test_field_select_m[]' onchange='update_checkbox_type_m(this);'><option value='radio' class='go_test_field_input_option_m'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option_m'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question_m go_test_field_input_question' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_m_"+block_num_m+"' type='"+block_type_m+"' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m["+block_num_m+"][1][]' type='hidden' /><input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m["+block_num_m+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li><li><input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_m_"+block_num_m+"' type='"+block_type_m+"' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m["+block_num_m+"][1][]' type='hidden' /><input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m["+block_num_m+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li><input class='go_test_field_add go_test_field_add_input_button_m' type='button' value='+' onclick='add_field_m(this);'/></ul><ul><li><input class='go_test_field_rm_row_button_m go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block_m(this);' /><input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='2' /></li></ul></td></tr>";
			jQuery(obj).parent().parent().before(field_block);
		}
		function remove_block_m (obj) {
			block_num_m = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row_m').length;
			jQuery('#go_test_field_block_count_m').attr('value', (block_num_m -1));
			jQuery(obj).parents('tr.go_test_field_input_row_m').remove();
		}
		function add_field_m (obj) {
			input_num_m = jQuery(obj).siblings('li').length + 1;
			var block_id = jQuery(obj).parents('tr.go_test_field_input_row_m').first().attr('id');
			block_num_m = block_id.split('go_test_field_input_row_m_').pop();
			block_type = jQuery(obj).parent('ul').siblings('select').children('option:selected').val();
			jQuery(obj).parent('ul').siblings('ul').children('li').children('.go_test_field_input_count_m').attr('value', input_num_m);
			jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox_m go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_m_"+block_num_m+"' type='"+block_type+"' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m["+block_num_m+"][1][]' type='hidden' /><input class='go_test_field_input_m go_test_field_input' name='go_test_field_values_m["+block_num_m+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /><input class='go_test_field_rm go_test_field_rm_input_button_m' type='button' value='X' onclick='remove_field_m(this);'></li>");
		}
		function remove_field_m (obj) {
			jQuery(obj).parents('tr.go_test_field_input_row_m').find('input.go_test_field_input_count_m')[0].value--;
			jQuery(obj).parent('li').remove();
		}
		
	</script>
	<?php
}

add_action('cmb_validate_go_test_field_mastery', 'go_validate_test_field_mastery');
function go_validate_test_field_mastery() {
	$question_temp = $_POST['go_test_field_input_question_m'];
	$test_temp = $_POST['go_test_field_values_m'];
	$select = $_POST['go_test_field_select_m'];
	$block_count = (int)$_POST['go_test_field_block_count_m'];
	$input_count_temp = $_POST['go_test_field_input_count_m'];

	$input_count = array();
	if (!empty($input_count_temp)) {
		foreach ($input_count_temp as $key => $value) {
			$temp = (int)$input_count_temp[$key];
			array_push($input_count, $temp);
		}
	}

	$question = array();
	if (!empty($question_temp) && is_array($question_temp)) {
		foreach ($question_temp as $value) {
			if (preg_match("/[\'\"\<\>]+/", $value)) {
				$str = $value;
				if (preg_match("/(\')+/", $str)) {
					$str = preg_replace("/(\')+/", '&#39;', $str);
				}
				if (preg_match("/(\")+/", $str)) {
					$str = preg_replace("/(\")+/", '&#34;', $str);
				}
				if (preg_match("/(<)+/", $str)) {
					$str = preg_replace("/(<)+/", "", $str);
				}
				if (preg_match("/(>)+/", $str)) {
					$str = preg_replace("/(>)+/", "", $str);
				}
				$question[] = $str;
			} else {
				$question[] = $value;
			}
		}
	} else {
		$question = $question_temp;
	}

	$test = array();
	if (!empty($test_temp)) {
		for ($f = 0; $f < count($test_temp); $f++) {
			$temp_input = $test_temp[$f][0];
			$temp_checked = $test_temp[$f][1];
			if (!empty($temp_input) && is_array($temp_input)) {
				foreach ($temp_input as $value) {
					if (!empty($value) && preg_match("/\S+/", $value)) {
						if (preg_match("/[\'\"\<\>]+/", $value)) {
							$str = $value;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][0][] = $str;
						} else {
							$test[$f][0][] = $value;
						}
					} else {
						if ($input_count[$f] > 2) {
							$input_count[$f]--;
						}
					}
				}
			}

			if (!empty($temp_checked) && is_array($temp_checked)) {
				foreach ($temp_checked as $val) {
					if (!empty($val) && preg_match("/\S+/", $val)) {
						if (preg_match("/[\'\"\<\>]+/", $val)) {
							$str = $val;
							if (preg_match("/(\')+/", $str)) {
								$str = preg_replace("/(\')+/", '&#39;', $str);
							}

							if (preg_match("/(\")+/", $str)) {
								$str = preg_replace("/(\")+/", '&#34;', $str);
							}

							if (preg_match("/(<)+/", $str)) {
								$str = preg_replace("/(<)+/", "", $str);
							}

							if (preg_match("/(>)+/", $str)) {
								$str = preg_replace("/(>)+/", "", $str);
							}
							$test[$f][1][] = $str;
						} else {
							$test[$f][1][] = $val;
						}
					}
				}
			}
		}
	}
	
	return(array($question, $test, $select, $block_count, $input_count));
}

add_filter( 'template_include', 'go_tasks_template_function', 1 );
function go_tasks_template_function( $template_path ) {
    if ( get_post_type() == 'tasks' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
			
			
			
            if ( $theme_file = locate_template( array (  'index.php') )
		//$theme_file =	get_page_template()
		 ) {
                $template_path = $theme_file;
				add_filter( 'the_content', 'go_tasks_filter_content' );
            } 
        }
    }
    return $template_path;
}

function go_tasks_filter_content(){
	 global $wpdb;
	 echo do_shortcode('[go_task id="'.get_the_id().'"]');
	 }
	 
function go_create_help_video_lb(){
	?>
	<div class="dark" style="display: none;"> </div>
    <div class="light" style="display: none;">
        <div id="go_help_video_container" style="margin: 10px 10px 10px 10px; <?php if(is_admin()){?>height:540px;width:864px;<?php } else {?>height: <?php echo go_return_options('go_video_height');?>px; width: <?php echo go_return_options('go_video_width');}?>px">
        	<video id="go_option_help_video" class="video-js vjs-default-skin vjs-big-play-centered" controls height="100%" width="100%" ><source src="" type="video/mp4"/></video/options>
        </div>
    </div>
    <?php 
}

add_action('admin_head', 'go_create_help_video_lb');
add_action('wp_head', 'go_create_help_video_lb');

function go_task_opt_help($field, $title, $video_url = null) {
	return '<a id="go_help_'.$field.'" class="go_task_opt_help" onclick="go_display_help_video(\''.$video_url.'\');" tooltip="'.$title.'">?</a>';
}

add_action('cmb_render_go_pick_order_of_chain', 'go_pick_order_of_chain');
function go_pick_order_of_chain(){
	global $wpdb;
	$task_id = get_the_id();
	if(get_the_terms($task_id, 'task_chains')){
		$chain = array_shift(array_values(get_the_terms($task_id, 'task_chains')));
		$posts_in_chain = get_posts(array(
			'post_type' => 'tasks',
			'post_status' => 'publish',
			'taxonomy' => 'task_chains',
			'term' => $chain->name,
			'order' => 'ASC',
			'meta_key' => 'chain_position',
			'orderby' => 'meta_value_num',
			'posts_per_page' => '-1'
		));
		
		?>
        <ul id="go_task_order_in_chain" class="go_sortable">
			<?php
            foreach($posts_in_chain as $post => $obj){
            	echo '<li class="go_task_in_chain" post_id="'.$obj->ID.'">'.$obj->post_title.'</li>';
            }
            ?>
		</ul>
        <script type="text/javascript">
			jQuery('document').ready(function(e) {
				var go_ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
				var post_id = "<?php echo $task_id; ?>";
	           	jQuery('#go_task_order_in_chain').sortable({
				   	axis: "y", 
				   	start: function(event, ui) {
				   		jQuery(ui.item).addClass('go_sortable_item');
				   	},
				   	stop: function (event, ui) {
				   		jQuery(ui.item).removeClass('go_sortable_item');
					  	var order = [];
					  	var chain = '<?php echo $chain->name;?>';
					  	jQuery('.go_task_in_chain').each(function(i, el){
							order[i+1] = jQuery(this).attr('post_id');
					  	});
					  	jQuery.ajax({
						   	url: go_ajaxurl,
						   	type: 'POST',
						   	data: {
								action: 'go_update_task_order',
								order: order,
								chain: chain,
								post_id: "<?php echo $task_id; ?>",
						   	}
					   	}); 
				   }
				});

				jQuery('input#publish').click(function(event, skip) {
					// The default value for skip is false, this way the post's custom metadata "chain_position" will always be updated.
					// The first time this fucntion is called, skip will be undefined.
					if (typeof(skip) === undefined) {
						skip = false;
					}
					// If skip is false, prevent the page from saving and update the metadata value "chain_position".
					if (!skip) {
						// Prevent the default functionality (saving).
						event.preventDefault();
						// Get the order of the task chain from the "Chain Order" meta box.
						var order = [];
						jQuery('.go_task_in_chain').each(function(i, el){
							order[i+1] = jQuery(this).attr('post_id');
					  	});
						// Get the position of this task in the chain and get the current value of the meta value "chain_position".
						// Compare them and update the meta value if they are not equal and the task id does exist in the chain.
						var n_position = order.indexOf(post_id);
						var c_position = jQuery("#the-list .left").children("input[value='chain_position']").first().parent('td.left').siblings("td").children("textarea").text();
						if (n_position != c_position && n_position != -1) {
							jQuery("#the-list .left").children("input[value='chain_position']").first().parent('td.left').siblings("td").children("textarea").text(n_position);
						}
						// Trigger the click event again, and pass it a true value for skip so that the task will resume normal function.
						jQuery('input#publish').trigger('click', [true]);
					}
				});
	        });
		</script>
        <?php
	}
}

add_action('cmb_render_go_settings_accordion', 'go_settings_accordion', 10, 1);
function go_settings_accordion($field_args){
	echo "
		<div id='{$field_args['settings_id']}' class='go_task_settings_accordion'>
			<strong>{$field_args['message']}</strong>
			<div class='go_triangle_container'>
				<div class='go_task_accordion_triangle'></div>
			</div>
		</div>";
}

add_action('cmb_render_go_stage_reward', 'go_stage_reward');
function go_stage_reward($field_args){
	$custom = get_post_custom(get_the_id());
	if (empty($custom['go_presets'][0])) {
		$presets = get_option('go_presets');
		$points = $presets['points'];
		$currency = $presets['currency'];
		$rewards = array(
			'points' => $points[$field_args['stage'] - 1],
			'currency' => $currency[$field_args['stage'] - 1]
		);
		
	} else {
		$rewards = unserialize($custom['go_presets'][0]);
	}
	echo "<div id='stage_{$field_args['stage']}'>";
	if($rewards){
		for($i = 1; $i <= 5; $i++){
			echo "<input stage='{$i}' reward='{$field_args['reward']}' type='text' name='stage_{$field_args['stage']}_{$field_args['reward']}[".($i - 1)."]' class='go_reward_input go_reward_{$field_args['reward']} go_reward_{$field_args['reward']}_{$i} ".(($field_args['stage'] == $i)?"go_current":"")."' value='".
			(($field_args['reward'] == 'points') && (!empty($rewards['points']))?$rewards['points'][$i-1]: 
			(($field_args['reward'] == 'currency') && (!empty($rewards['currency']))?$rewards['currency'][$i-1]:
			(($field_args['reward'] == 'bonus_currency') && (!empty($rewards['bonus_currency']))?$rewards['bonus_currency'][$i-1]:0)))."'/>";
		}
	}
	echo "</div>";
}

function go_update_task_order () {
	global $wpdb;
	$order = $_POST['order'];
	$chain = $_POST['chain'];
	$id = $_POST['post_id'];
	foreach($order as $key => $value){
		add_post_meta($value, 'chain', $chain, true);
		update_post_meta($value, 'chain_position', $key);
	}
}

add_action('transition_post_status', 'go_add_new_task_in_chain', 10, 3);
function go_add_new_task_in_chain ($new_status, $old_status, $post) {
	$task_id = $post->ID;
	if (get_post_type($task_id) == 'tasks') {
		$task_chains = get_the_terms($task_id, 'task_chains');
		if (!empty($task_chains)) {
			$chain = array_shift(array_values($task_chains))->name;
		}

		// Check if task is new, is being updated, or being deleted and update the
		// task chain list appropriately.
		if ($new_status == 'publish' && $old_status != 'publish') {	
			
			// Get the current number of tasks in the given chain.
			$count = go_return_task_amount_in_chain($chain);
			
			// If the chain is not empty and go_return_task_amount_in_chain() returns a non-null value,
			// set $pos to the number of tasks plus one and then update the 'chain' and 'chain_position' meta values
			// of the current post.
			if (!empty($chain)) {				
				if (!update_post_meta($task_id, 'chain', $chain)) {
					add_post_meta($task_id, 'chain', $chain, true);
				}
				if (!empty($count)) {
					if (!update_post_meta($task_id, 'chain_position', $count)) {
						add_post_meta($task_id, 'chain_position', $count, true);
					}
				}				
			}
		} else if ($new_status == 'publish' && $old_status == 'publish') {

			// Get the current meta position in the database for this task as a string.
			$c_position = get_post_meta($task_id, 'chain_position', true);
			$chain_meta = get_post_meta($task_id, 'chain', true);
			
			// Get a list of all the tasks in this chain and order them by chain position.
			$other_posts = get_posts(array(
				'post_type' => 'tasks',
				'post_status' => 'publish',
				'taxonomy' => 'task_chains',
				'term' => $chain,
				'order' => 'ASC',
				'meta_key' => 'chain_position',
				'orderby' => 'meta_value_num',
				'posts_per_page' => '-1'
			));
			
			// Pull out the ids for the tasks, for each task, update the order so that the first task always has an index of 1.
			foreach ($other_posts as $pos => $post) {
				$id = $post->ID;
				if ($id != $task_id) {
					update_post_meta($id, 'chain_position', ($pos + 1));
				} else {
					if (!empty($c_position)) {
						update_post_meta($task_id, 'chain_position', ($pos + 1));
					} else {
						$end_pos = go_return_task_amount_in_chain($chain) + 1;
						add_post_meta($task_id, 'chain_position', $end_pos);
					}
				}
			}
			if (empty($chain_meta)) {
				add_post_meta($task_id, 'chain', $chain);
			}
			if (empty($c_position)) {
				$end_pos = go_return_task_amount_in_chain($chain);
				add_post_meta($task_id, 'chain_position', $end_pos);
			}
		} else if ($new_status == 'trash' && $old_status != 'trash') {

			// Get a list of all the tasks in this chain and order them by chain position.
			$other_posts = get_posts(array(
				'post_type' => 'tasks',
				'post_status' => 'publish',
				'taxonomy' => 'task_chains',
				'term' => $chain,
				'order' => 'ASC',
				'meta_key' => 'chain_position',
				'orderby' => 'meta_value_num',
				'posts_per_page' => '-1'
			));
			
			// Pull out the ids for the tasks, for each task, update the order so that the first task always has an index of 1.
			foreach ($other_posts as $pos => $post) {
				$id = $post->ID;
				update_post_meta($id, 'chain_position', ($pos + 1));
			}
		}
	}
}

add_action('save_post', 'go_final_chain_message');
function go_final_chain_message () {
	$task_id = get_the_id();
	$custom = get_post_custom($task_id);
	if(get_post_type($task_id) == 'tasks'){
		if(get_the_terms($task_id, 'task_chains')){
			$chain = array_shift(array_values(get_the_terms($task_id, 'task_chains')));
			$posts_in_chain = get_posts(array(
				'post_type' => 'tasks',
				'taxonomy' => 'task_chains',
				'term' => $chain->name,
				'meta_key' => 'chain_position',
				'orderby' => 'meta_value_num',
				'posts_per_page' => '-1'
			));
			$message = $custom['go_mta_final_chain_message'][0];
			foreach($posts_in_chain as $post){
               update_post_meta($post->ID, 'go_mta_final_chain_message', $message);
            }
		}
	}
}

add_action('delete_term_taxonomy', 'go_remove_task_chain_from_posts', 10, 1);
function go_remove_task_chain_from_posts ($term_id) {
	$term = get_term_by('id', $term_id, 'task_chains');
	$posts_in_chain = get_posts(array(
		'post_type' => 'tasks',
		'taxonomy' => 'task_chains',
		'meta_key' => 'chain',
		'posts_per_page' => '-1'
	));
	if (!empty($posts_in_chain)) {
		foreach ($posts_in_chain as $key => $post) {
			$post_chain_name = get_post_meta($post->ID, 'chain', true);
			if ($post_chain_name == $term->name) {
				delete_post_meta($post->ID, 'chain');
				delete_post_meta($post->ID, 'chain_position');
				
				$post_tax = get_the_terms($post->ID, 'task_chains');
				if (!empty($post_tax)) {
					$first_chain_name = array_shift($post_tax)->name;
					$chain_length = go_return_task_amount_in_chain($first_chain_name);
					add_post_meta($post->ID, 'chain', $first_chain_name);
					add_post_meta($post->ID, 'chain_position', $chain_length);
				}
			}
		}
	}
}

add_action('post_submitbox_misc_actions', 'go_clone_task_ajax');
function go_clone_task_ajax () {
	global $post;

	// When the "clone" button is pressed send an ajax call to the go_clone_task() function to
	// clone the task using the sent task id.
	if (get_post_type($post) == 'tasks') {
		echo '
		<div class="misc-pub-section misc-pub-section-last">
			<input id="go-button-clone" class="button button-large alignright" type="button" value="Clone" />
		</div>
		<script type="text/javascript">        	
			function clone_post_ajax() {
				jQuery("input#go-button-clone").click(function() {
					jQuery.ajax({
						url: "'.get_site_url().'/wp-admin/admin-ajax.php",
						type: "POST",
						data: {
							action: "go_clone_task",
							post_id: '.$post->ID.',
						}, success: function(url) {
							var reg = new RegExp("^(http)");
							var match = reg.test(url);
							if (url != \'\' && match) {
								window.location = url;
							}
						}
					});
				});
			}
			jQuery(document).ready(function() {
				clone_post_ajax();
			});
		</script>
		';
	}
}

function go_clone_task () {

	// Grab the post id from the ajax call and use it to grab data from the original post.
	$post_id = $_POST['post_id'];

	// Get the post's title, permalink, publishing and modification dates, etc.
	$post_data = get_post($post_id, ARRAY_A);

	// Grab the original post's meta data.
	$post_custom = get_post_custom($post_id);

	// Grab the original post's taxonomies.
	$terms = get_the_terms($post_id, 'task_chains');
	$foci = get_the_terms($post_id, 'task_focus_categories');
	$cat = get_the_terms($post_id, 'task_categories');

	$term_ids = array();
	$focus_ids = array();	
	$cat_ids = array();

	// Put the ids of the taxonomies that the original post was assigned to into arrays.
	for ($i = 0; $i < count($terms); $i++) {
		array_push($term_ids, $terms[$i]->term_id);
	}

	for ($i = 0; $i < count($foci); $i++) {
		array_push($focus_ids, $foci[$i]->term_id);
	}

	for ($i = 0; $i < count($cat); $i++) {
		array_push($cat_ids, $cat[$i]->term_id);
	}
	
	// Change the post status to "draft" and leave the guid up to Wordpress.
	$post_data['post_status'] = 'draft';
	$post_data['guid'] = '';

	// Remove some other data to allow the post to be easily cloned.
	unset($post_data['ID']);
	unset($post_data['post_title']);
	unset($post_data['post_name']);
	unset($post_data['post_modified']);
	unset($post_data['post_modified_gmt']);
	unset($post_data['post_date']);
	unset($post_data['post_date_gmt']);

	// Clone the original post with the modified data from above, and retreive the new post's id.
	$clone_id = wp_insert_post($post_data);

	// Set the cloned post's taxonomies using the ids from above.
	wp_set_object_terms($clone_id, $term_ids, "task_chains");
	wp_set_object_terms($clone_id, $focus_ids, "task_focus_categories");
	wp_set_object_terms($clone_id, $cat_ids, "task_categories");

	// If the clone was successfully created continue, otherwise return 0.
	if (!empty($clone_id)) {

		// Setup the url to the cloned post.
		$url = get_site_url()."/wp-admin/post.php?post={$clone_id}&action=edit";
		
		// Add the original post's meta data to the clone.
		foreach ($post_custom as $key => $value) {
			for ($i = 0; $i < count($value); $i++) {
				$uns = unserialize($value[$i]);
				if ($uns !== false) {
					add_post_meta($clone_id, $key, $uns, true);
				} else {
					if ($key === 'chain_position') {
						$chain = $post_custom["chain"][0];
						$end_pos = go_return_task_amount_in_chain($chain) + 1;
						add_post_meta($clone_id, $key, $end_pos, true);
					} else {
						add_post_meta($clone_id, $key, $value[$i], true);
					}
				}
			}
		}
		echo $url;
	} else {
		echo 0;
	}
	die();
}

add_action('cmb_render_go_store_shortcode_list', 'go_cmb_render_go_store_shortcode_list');
function go_cmb_render_go_store_shortcode_list() {
	$post_id = get_the_id();
	$custom = get_post_custom($post_id);
	$is_checked = $custom['go_mta_store_shortcode_list'][0];
	echo "
		<input id='go_store_shortcode_list_checkbox' name='go_mta_store_shortcode_list' type='checkbox'".($is_checked ? "checked" : "")."/>";
	echo "
		<ul id='go_store_shortcode_list' style='display: none;'>
			<li class='go_store_shortcode_list_item'><span>"
				.go_task_opt_help('display_name_shortcode', '', 'http://maclab.guhsd.net/go/video/store/displayNameShortcode.mp4')."</span>[go_get_displayname]
			</li>
			<li class='go_store_shortcode_list_item'><span>"
				.go_task_opt_help('store_by_cat', '', 'http://maclab.guhsd.net/go/video/store/storeByCat.mp4')."</span>[go_store cats='']
			</li>
			<li class='go_store_shortcode_list_item'><span>"
				.go_task_opt_help('store_by_id', '', 'http://maclab.guhsd.net/go/video/store/storeById.mp4')."</span>[go_store id='{$post_id}']
			</li>
		</ul>
	";
}

add_action('cmb_render_go_store_cost', 'go_store_cost');
function go_store_cost() {
	$custom = get_post_custom(get_the_id());
	$cost_array = unserialize($custom['go_mta_store_cost'][0]);
	if (!empty($cost_array)) {
		$go_currency_cost = $cost_array[0];
		$go_point_cost = $cost_array[1];
		$go_bonus_currency_cost = $cost_array[2];
	}
	echo "
		<input class='go_store_cost_input' name='go_currency_cost' type='text' placeholder='".go_return_options('go_currency_name')."'".(!empty($go_currency_cost) ? "value='{$go_currency_cost}'" : "")."/>
		<input class='go_store_cost_input' name='go_point_cost' type='text' placeholder='".go_return_options('go_points_name')."'".(!empty($go_point_cost) ? "value='{$go_point_cost}'" : "")."/>
		<input class='go_store_cost_input' name='go_bonus_currency_cost' type='text' placeholder='".go_return_options('go_bonus_currency_name')."'".(!empty($go_bonus_currency_cost) ? "value='{$go_bonus_currency_cost}'" : "")."/>
	";
}

add_action('cmb_validate_go_store_cost', 'go_validate_store_cost');
function go_validate_store_cost() {
	$go_currency_cost = $_POST['go_currency_cost'];
	$go_point_cost = $_POST['go_point_cost'];
	$go_bonus_currency_cost = $_POST['go_bonus_currency_cost'];
	if (empty($go_currency_cost)) {
		$go_currency_cost = 0;
	}
	if (empty($go_point_cost)) {
		$go_point_cost = 0;
	}
	if (empty($go_bonus_currency_cost)) {
		$go_bonus_currency_cost = 0;
	}
	return (array($go_currency_cost, $go_point_cost, $go_bonus_currency_cost));
}

add_action('cmb_render_go_store_limit', 'go_store_limit');
function go_store_limit() {
	$custom = get_post_custom(get_the_id());
	$content_array = unserialize($custom['go_mta_store_limit'][0]);
	$is_checked = $content_array[0];
	if (empty($is_checked)) {
		$is_checked = "true";
	}
	$limit = $content_array[1];
	echo "
		<input id='go_store_limit_checkbox' name='go_store_limit' type='checkbox' ".($is_checked == 'true' ? "checked" : "")."/>
		<input id='go_store_limit_input' name='go_store_limit_input' type='text' style='display: none;' placeholder='Limit'".(!empty($limit) ? "value='{$limit}'" : "")."/>
	";
}

add_action('cmb_validate_go_store_limit', 'go_validate_store_limit');
function go_validate_store_limit() {
	$is_checked = $_POST['go_store_limit'];
	if (empty($is_checked)) {
		$is_checked = "false";
	} else {
		$is_checked = "true";
	}
	$limit = $_POST['go_store_limit_input'];
	return (array($is_checked, $limit));
}

add_action('cmb_render_go_store_focus', 'go_store_focus');
function go_store_focus() {
	$custom = get_post_custom(get_the_id());
	$content_array = unserialize($custom['go_mta_store_focus'][0]);
	$is_checked = $content_array[0];
	if (empty($is_checked)) {
		$is_checked = "false";
	}
	$profession = $content_array[1];
	$user_id = get_current_user_id();
	$focus_switch = go_return_options('go_focus_switch');
	
	if ($focus_switch == 'On') {
		$go_foci = get_option('go_focus');
		if (!empty($go_foci)) {
			if (count($go_foci) > 1 || (count($go_foci) == 1 && !empty($go_foci[0]))) {
				echo "
					<input id='go_store_focus_checkbox' name='go_mta_store_focus' type='checkbox' ".($is_checked == 'true' ? "checked" : "")."/>
					<select id='go_store_focus_select' name='go_store_focus_select' style='display: none;'>
				";
				foreach ($go_foci as $key => $focus) {
					echo "<option class='go_store_focus_option'";
					if (strtolower($focus) == strtolower($profession) && !empty($profession)) {
						echo 'selected';
					}
					echo ">{$focus}</option>";
				}
				echo "</select>";
			} else {
				echo "<p>No names were found in the ".go_return_options('go_focus_name')." section in the <a href='".admin_url()."/?page=game-on-options.php'>Game-On options</a>.</p>";
			}
		}
	} else {
		echo "<p>The ".go_return_options('go_focus_name')." option is disabled in the <a href='".admin_url()."/?page=game-on-options.php'>Game-On options</a>.</p>";
	}
}

add_action('cmb_validate_go_store_focus', 'go_validate_store_focus');
function go_validate_store_focus() {
	$is_checked = $_POST['go_mta_store_focus'];
	if (empty($is_checked)) {
		$is_checked = "false";
	} else {
		$is_checked = "true";
	}
	$focus_select = $_POST['go_store_focus_select'];
	return (array($is_checked, $focus_select));
}

add_action('cmb_render_go_store_receipt', 'go_store_receipt');
function go_store_receipt() {
	$custom = get_post_custom(get_the_id());
	$store_receipt_option = get_option('go_store_receipt_switch');
	$is_checked = $custom['go_mta_store_receipt'][0];
	if ($store_receipt_option == 'On') {
		if (empty($is_checked)) {
			$is_checked = "true";
		}
	} else {
		if (empty($is_checked)) {
			$is_checked = "false";
		}
	}
	echo "<input id='go_store_receipt_checkbox' name='go_store_receipt' type='checkbox'".($is_checked == 'true' ? "checked" : "")."/>";
}

add_action('cmb_validate_go_store_receipt', 'go_validate_store_receipt');
function go_validate_store_receipt() {
	$is_checked = $_POST['go_store_receipt'];
	if (empty($is_checked)) {
		$is_checked = "false";
	} else {
		$is_checked = "true";
	}
	return ($is_checked);
}

add_action('cmb_render_go_store_filter', 'go_store_filter');
function go_store_filter() {
	$custom = get_post_custom(get_the_id());
	$content_array = unserialize($custom['go_mta_store_filter'][0]);
	$is_checked = $content_array[0];
	if (empty($is_checked)) {
		$is_checked = "false";
	}
	$chosen_rank = $content_array[1];
	$bonus_currency_filter = $content_array[2];
	$penalty_filter = $content_array[3];
	$ranks_array = get_option('go_ranks');
	$ranks = $ranks_array['name'];
	echo "
		<input id='go_store_filter_checkbox' name='go_mta_store_filter' type='checkbox' ".($is_checked == 'true' ? "checked" : "")."/>";

	if (!empty($ranks) && is_array($ranks)) {
		echo "<select id='go_store_filter_select' class='go_store_filter_input' name='go_store_filter_select' style='display: none;'>";
		foreach ($ranks as $rank) {
			echo "<option class='go_store_filter_option'";
			if (strtolower($rank) == strtolower($chosen_rank)) {
				echo 'selected';
			}
			echo ">{$rank}</option>";
		}
		echo "</select>";
	}
	echo "
		<input id='go_store_filter_bonus_currency' class='go_store_filter_input' name='go_store_filter_bonus_currency' type='text' style='display: none;' placeholder='".go_return_options('go_bonus_currency_name')."' ".(!empty($bonus_currency_filter) ? "value='{$bonus_currency_filter}'" : '')."/>
		<input id='go_store_filter_penalty' class='go_store_filter_input' name='go_store_filter_penalty' type='text' style='display: none;' placeholder='".go_return_options('go_penalty_name')."' ".(!empty($penalty_filter) ? "value='{$penalty_filter}'" : '')."/>
	";
}

add_action('cmb_validate_go_store_filter', 'go_validate_store_filter');
function go_validate_store_filter() {
	$is_checked = $_POST['go_mta_store_filter'];
	if (empty($is_checked)) {
		$is_checked = "false";
	} else {
		$is_checked = "true";
	}
	$chosen_rank = $_POST['go_store_filter_select'];
	$b_filter = $_POST['go_store_filter_bonus_currency'];
	$d_filter = $_POST['go_store_filter_penalty'];
	return (array($is_checked, $chosen_rank, $b_filter, $d_filter));
}

add_action('cmb_render_go_item_url', 'go_item_url');
function go_item_url() {
	$custom = get_post_custom(get_the_id());
	$url = $custom['go_mta_store_item_url'][0];
	echo "<input id='go_store_item_url_input' name='go_mta_store_item_url' type='text' placeholder='http://yourlink.com' ".(!empty($url) ? "value='{$url}'" : '')."/>";
}

add_action('cmb_render_go_badge_id', 'go_badge_id');
function go_badge_id() {
	$custom = get_post_custom(get_the_id());
	$id = $custom['go_mta_badge_id'][0];
	echo "<input id='go_store_badge_id_input' name='go_mta_badge_id' type='text' placeholder='Badge ID' ".(!empty($id) ? "value='{$id}'" : '')."/>";
}

add_action('cmb_render_go_store_exchange', 'go_store_exchange');
function go_store_exchange() {
	$custom = get_post_custom(get_the_id());
	$content_array = unserialize($custom['go_mta_store_exchange'][0]);
	$is_checked = $content_array[0];
	if (empty($is_checked)) {
		$is_checked = "false";
	}
	$c_exchange = $content_array[1];
	$p_exchange = $content_array[2];
	$b_exchange = $content_array[3];
	echo "
		<input id='go_store_exchange_checkbox' name='go_mta_store_exchange' type='checkbox' ".($is_checked == 'true' ? "checked" : "")."/>
		<input class='go_store_exchange_input' name='go_store_exchange_currency' type='text' style='display: none;' placeholder='".go_return_options('go_currency_name')."' ".(!empty($c_exchange) ? "value='{$c_exchange}'" : '')."/>
		<input class='go_store_exchange_input' name='go_store_exchange_points' type='text' style='display: none;' placeholder='".go_return_options('go_points_name')."' ".(!empty($p_exchange) ? "value='{$p_exchange}'" : '')."/>
		<input class='go_store_exchange_input' name='go_store_exchange_bonus_currency' type='text' style='display: none;' placeholder='".go_return_options('go_bonus_currency_name')."' ".(!empty($b_exchange) ? "value='{$b_exchange}'" : '')."/>
	";
}

add_action('cmb_validate_go_store_exchange', 'go_validate_store_exchange');
function go_validate_store_exchange() {
	$is_checked = $_POST['go_mta_store_exchange'];
	if (empty($is_checked)) {
		$is_checked = "false";
	} else {
		$is_checked = "true";
	}
	$c_exchange = $_POST['go_store_exchange_currency'];
	$p_exchange = $_POST['go_store_exchange_points'];
	$b_exchange = $_POST['go_store_exchange_bonus_currency'];
	return (array($is_checked, $c_exchange, $p_exchange, $b_exchange));
}
?>