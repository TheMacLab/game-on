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
		'title'      => go_return_options('go_tasks_name').' Options <br/> Click ?\'s for videos. <a href="javascript:;" onclick="go_display_help_video(\'http://maclab.guhsd.net/go/video/quests/questsIntro.mp4\')"> Please watch this video first. </a>',
		'pages'      => array( 'tasks' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => go_return_options('go_tasks_name').' Shortcode'.go_task_opt_help('shortocde', '', 'http://maclab.guhsd.net/go/video/quests/taskShortcode.mp4'),
				'type' => 'go_task_shortcode'
			),
			array(
				'name' => 'Video Shortcode'.go_task_opt_help('shortocde', '', 'http://maclab.guhsd.net/go/video/quests/videoShortcode.mp4'),
				'type' => 'go_video_shortcode'
			),
			array(
				'name' => 'Upload Shortcode'.go_task_opt_help('shortocde', '', 'http://maclab.guhsd.net/go/video/quests/uploadShortcode.mp4'),
				'type' => 'go_upload_shortcode'
			),
			
			array(
				'name' => 'Required Rank '.go_task_opt_help('req_rank', '', 'http://maclab.guhsd.net/go/video/quests/requiredRank.mp4'),
				'desc' => 'rank required to begin '.go_return_options('go_tasks_name').".",
				'id'   => $prefix . 'req_rank',
				'type' => 'select',
				'options' => go_get_all_ranks()
			),
			array(
				'name' => 'Presets'.go_task_opt_help('presets', '', 'http://maclab.guhsd.net/go/video/quests/presets.mp4'),
				'id'   => 'go_presets',
				'desc'=> '',
				'type' => 'go_presets',
			),
			array(
				'name' => 'Time Filter (Optional)'.go_task_opt_help('time_filter', '', 'http://maclab.guhsd.net/go/video/quests/timeFilter.mp4'),
				'desc' => 'Hides this post from all users with less than the entered amount of time (in minutes).',
				'id' => $prefix . 'time_filter',
				'type' => 'text'
			),
			array(
				'name' => 'Lock by '.go_return_options('go_focus_name').' Category (Optional)'.go_task_opt_help('lock_by_cat', '', ' http://maclab.guhsd.net/go/video/quests/lockByProfessionCategory.mp4'),
				'desc' => ' Check this box to lock this task by its '.go_return_options('go_focus_name').' category.',
				'id' => $prefix.'focus_category_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Points'.go_task_opt_help('points', '', 'http://maclab.guhsd.net/go/video/quests/points.mp4'),
				'desc' => 'points awarded for encountering, accepting, completing, and mastering the '.go_return_options('go_tasks_name').'. (comma seperated, e.g. 10,20,50,70).',
				'id'   => $prefix . 'task_points',
				'type' => 'text',
			),
			array(
				'name' => 'Currency'.go_task_opt_help('currency', '', 'http://maclab.guhsd.net/go/video/quests/currency.mp4'),
				'desc' => 'currency awarded for encountering, accepting, completing, and mastering the '.go_return_options('go_tasks_name').'. (comma seperated, e.g. 10,20,50,70).',
				'id'   => $prefix . 'task_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Encounter Message'.go_task_opt_help('encounter', '', 'http://maclab.guhsd.net/go/video/quests/encounterMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>encountered</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'quick_desc',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Accept Message'.go_task_opt_help('accept', '', 'http://maclab.guhsd.net/go/video/quests/acceptMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>accepted</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'accept_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Completion Message (Optional)'.go_task_opt_help('complete', '', 'http://maclab.guhsd.net/go/video/quests/completionMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>completed</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'complete_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
				),
			array(
				'name' => 'Completion File Upload',
				'name' => 'Completion File Upload'.go_task_opt_help('completion_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/completionFileUpload.mp4'),
 				'desc' => 'Toggle to require a user to upoad a file before completing the '.go_return_options('go_tasks_name').".",
 				'id' => $prefix.'completion_upload',
 				'type' => 'checkbox'
 			),
			array(
				'name' => 'Lock Complete Stage (Optional)'.go_task_opt_help('lock_complete', '', 'http://maclab.guhsd.net/go/video/quests/lockCompleteStage.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has entered a specified password.',
				'id' => $prefix.'complete_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Completion Unlock Password'.go_task_opt_help('unlock_complete', '', 'http://maclab.guhsd.net/go/video/quests/completionUnlockPassword.mp4'),
				'desc' => 'Enter a password into this field which is used when a user attempts to complete a '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'complete_unlock',
				'type' => 'text'
			),
			array(
				'name' => 'Completion Check for Understanding (Optional)'.go_task_opt_help('complete_understand', '', 'http://maclab.guhsd.net/go/video/quests/completionCheckForUnderstanding.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has completed the test.',
				'id' => $prefix.'test_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Return Points (Optional)'.go_task_opt_help('complete_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'desc' => ' Check to allow tests to return points, based on the tier of the current '.go_return_options('go_tasks_name').', that diminish as the number of test failures increase.',
				'id' => $prefix.'test_lock_loot',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Custom Reward Modifier'.go_task_opt_help('complete_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4'),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test (Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100").  
							This will replace the default modifier. <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_loot_mod',
				'type' => 'text'
			),
			array(
				'name' => 'Question Total'.go_task_opt_help('complete_understand_question_total', '', 'http://maclab.guhsd.net/go/video/quests/questionTotal.mp4'),
				'desc' => 'Select the desired number of questions for the test.',
				'id' => $prefix.'test_lock_num',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( '1', 'cmb' ), 'value' => '1', ),
					array( 'name' => __( '2', 'cmb' ), 'value' => '2', ),
					array( 'name' => __( '3', 'cmb' ), 'value' => '3', ),
					array( 'name' => __( '4', 'cmb' ), 'value' => '4', ),
					array( 'name' => __( '5', 'cmb' ), 'value' => '5', )
                )
			),
			array(
				'name' => 'Check Type 1'.go_task_opt_help('complete_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_lock_type_0',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 1'.go_task_opt_help('complete_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_question_0',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('complete_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_answers_0',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('complete_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_key_0',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 2'.go_task_opt_help('complete_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_lock_type_1',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 2'.go_task_opt_help('complete_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_question_1',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('complete_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_answers_1',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('complete_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_key_1',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 3'.go_task_opt_help('complete_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_lock_type_2',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 3'.go_task_opt_help('complete_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_question_2',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('complete_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_answers_2',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('complete_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_key_2',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 4'.go_task_opt_help('complete_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_lock_type_3',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 4'.go_task_opt_help('complete_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_question_3',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('complete_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_answers_3',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('complete_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_key_3',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 5'.go_task_opt_help('complete_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_lock_type_4',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 5'.go_task_opt_help('complete_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_question_4',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('complete_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_answers_4',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('complete_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_key_4',
				'type' => 'text'
			),
			array(
				'name' => 'Toggle Mastery Stage (Optional)'.go_task_opt_help('toggle_mastery_stage', '', 'http://maclab.guhsd.net/go/video/quests/toggleMasteryStage.mp4'),
				'desc' => 'Choose to deactive the mastery stage, for a three stage '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'task_mastery',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Mastery Message (Optional)'.go_task_opt_help('mastery', '', 'http://maclab.guhsd.net/go/video/quests/masteryMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>mastered</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'mastery_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
				),
			array(
				'name' => 'Mastery File Upload'.go_task_opt_help('mastery_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/masteryFileUpload.mp4'),
				'desc' => 'Toggle to require a user to upoad a file before mastering the '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'mastery_upload',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Lock Mastery Stage (Optional)'.go_task_opt_help('lock_mastery', '', 'http://maclab.guhsd.net/go/video/quests/lockMasteryStage.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has entered a specified password.',
				'id' => $prefix.'mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Mastery unlock password'.go_task_opt_help('unlock_mastery', '', 'http://maclab.guhsd.net/go/video/quests/masteryUnlockPassword.mp4'),
				'desc' => 'Enter a password into this field which is used when a user attempts to complete a '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'mastery_unlock',
				'type' => 'text'
			),
			array(
				'name' => 'Mastery Check for Understanding (Optional)'.go_task_opt_help('mastery_understand', '', 'http://maclab.guhsd.net/go/video/quests/masteryCheckForUnderstanding.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has completed the test.',
				'id' => $prefix.'test_mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Return Points (Optional)'.go_task_opt_help('mastery_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'desc' => ' Check to allow tests to return points, based on the tier of the current '.go_return_options('go_tasks_name').', that diminish as the number of test failures increase.',
				'id' => $prefix.'test_mastery_lock_loot',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Custom Reward Modifier'.go_task_opt_help('mastery_understand_return_modifier', '', 'http://maclab.guhsd.net/go/video/quests/returnModifier.mp4'),
				'desc' => 'Enter a list of modifiers that will be used to determine the points received on the completion of a test (Seperate percentiles with commas, e.g. "20, 0, -20, -50, -80, -100").  
							This will replace the default modifier. <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_loot_mod',
				'type' => 'text'
			),
			array(
				'name' => 'Question Total'.go_task_opt_help('mastery_understand_question_total', '', 'http://maclab.guhsd.net/go/video/quests/questionTotal.mp4'),
				'desc' => 'Select the desired number of questions for the test.',
				'id' => $prefix.'test_mastery_lock_num',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( '1', 'cmb' ), 'value' => '1', ),
					array( 'name' => __( '2', 'cmb' ), 'value' => '2', ),
					array( 'name' => __( '3', 'cmb' ), 'value' => '3', ),
					array( 'name' => __( '4', 'cmb' ), 'value' => '4', ),
					array( 'name' => __( '5', 'cmb' ), 'value' => '5', )
                )
			),
			array(
				'name' => 'Check Type 1'.go_task_opt_help('mastery_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_mastery_lock_type_0',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 1'.go_task_opt_help('mastery_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_question_0',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('mastery_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_answers_0',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('mastery_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_key_0',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 2'.go_task_opt_help('mastery_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_mastery_lock_type_1',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 2'.go_task_opt_help('mastery_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_question_1',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('mastery_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_answers_1',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('mastery_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_key_1',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 3'.go_task_opt_help('mastery_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_mastery_lock_type_2',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 3'.go_task_opt_help('mastery_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_question_2',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('mastery_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_answers_2',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('mastery_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_key_2',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 4'.go_task_opt_help('mastery_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_mastery_lock_type_3',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 4'.go_task_opt_help('mastery_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_question_3',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('mastery_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_answers_3',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('mastery_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_key_3',
				'type' => 'text'
			),
			array(
				'name' => 'Check Type 5'.go_task_opt_help('mastery_understand_checktype', '', 'http://maclab.guhsd.net/go/video/quests/checkType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_mastery_lock_type_4',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question 5'.go_task_opt_help('mastery_understand_question', '', 'http://maclab.guhsd.net/go/video/quests/checkQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_question_4',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('mastery_understand_answer', '', 'http://maclab.guhsd.net/go/video/quests/checkAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_answers_4',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('mastery_understand_key', '', 'http://maclab.guhsd.net/go/video/quests/checkKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_key_4',
				'type' => 'text'
			),
			array(
				'name' => 'Repeatable'.go_task_opt_help('repeatable', '', 'http://maclab.guhsd.net/go/video/quests/repeatable.mp4'),
				'desc' => ' Select to make '.go_return_options('go_tasks_name').' repeatable.',
				'id'   => $prefix . 'task_repeat',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Repeat Message (Optional)'.go_task_opt_help('repeat_message', '', 'http://maclab.guhsd.net/go/video/quests/repeatMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>repeated</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'repeat_message',
				'type' => 'wysiwyg',
        		'options' => array(
								'wpautop' => true,
								'textarea_rows' => '5',
							),
						
				),
			array(
				'name' => 'Allowed Repeatable Times (Optional)'.go_task_opt_help('repeat_limit', '', 'http://maclab.guhsd.net/go/video/quests/allowedRepeatableTimes.mp4'),
				'desc' => 'Enter a numerical value to set a hard limit to the amount of times a user can repeat a task.<br/> Leave blank if no limit.',
				'id' => $prefix.'repeat_amount',
				'type' => 'text'
				),
			array(
				'name' => go_return_options('go_tasks_name').' Shortcode'.go_task_opt_help('shortocde', '', 'http://maclab.guhsd.net/go/video/quests/taskShortcode.mp4'),
				'type' => 'go_task_shortcode'
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
				'name' => 'Shortcode'.go_task_opt_help('shortcode', '', 'http://maclab.guhsd.net/go/video/store/shortcode.mp4'),
				'desc' => 'Insert this shortcode where you want the task to appear.',
				'type' => 'go_store_shortcode'
			),
			array(
				'name' => 'Penalty Switch'.go_task_opt_help('penalty_switch', '', 'http://maclab.guhsd.net/go/video/store/penaltySwitch.mp4'),
				'desc' => 'Allow user\'s currency to go negative when purchasing this item.',
				'id' => $prefix . 'penalty_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => go_return_options('go_focus_name').' Gateway? (Optional)'.go_task_opt_help('focus_gateway', '', 'http://maclab.guhsd.net/go/video/store/focusGateway.mp4'),
				'desc' => ' Check this box to convert this item into a focus gateway. When a user purchases this item, this focus pathway will be added to their account.',
				'id' => $prefix . 'focus_item_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => go_return_options('go_focus_name').go_task_opt_help('focus', '', 'http://maclab.guhsd.net/go/video/store/focus.mp4'),
				'desc' => 'Select the '.go_return_options('go_focus_name').' to be associated with this item.',
				'id' => $prefix.'focuses',
				'type' => 'select',
				'options' => go_get_all_focuses()
			),
			array(
				'name' => 'Time Filter (Optional)'.go_task_opt_help('time_filter', '', 'http://maclab.guhsd.net/go/video/store/timeFilter.mp4'),
				'desc' => 'Hides this post from all users with less than the entered amount of time (in minutes).',
				'id' => $prefix . 'store_time_filter',
				'type' => 'text'
			),
			array(
				'name' => 'Required Rank'.go_task_opt_help('required_rank', '', 'http://maclab.guhsd.net/go/video/store/requiredRank.mp4'),
				'desc' => 'Rank required to purchase the item.',
				'id'   => $prefix . 'store_rank',
				'type' => 'select',
				'options' => go_get_all_ranks()
			),
			array(
				'name' => 'Currency'.go_task_opt_help('currency', '', 'http://maclab.guhsd.net/go/video/store/currency.mp4'),
				'desc' => 'Currency required to purchase the item.',
				'id'   => $prefix . 'store_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Points'.go_task_opt_help('points', '', 'http://maclab.guhsd.net/go/video/store/points.mp4'),
				'desc' => 'Points required to purchase item.',
				'id'   => $prefix . 'store_points',
				'type' => 'text',
			),
			array(
				'name' => 'Time'.go_task_opt_help('time', '', 'http://maclab.guhsd.net/go/video/store/time.mp4'),
				'desc' => 'Time required to purchase item.',
				'id'   => $prefix . 'store_time',
				'type' => 'text',
			),
			array(
				'name' => 'Item URL (Optional)'.go_task_opt_help('item_url', '', 'http://maclab.guhsd.net/go/video/store/itemURL.mp4'),
				'desc' => 'URL to be displayed when the item is purchased. Leave blank if you don\'t need a link.',
				'id' => $prefix . 'store_itemURL',
				'type' => 'text'	
			),
			array(
				'name' => 'Allowed Repeatable Times (Optional)'.go_task_opt_help('repeat_limit', '', 'http://maclab.guhsd.net/go/video/store/allowedRepeatableTimes.mp4'),
				'desc' => 'Enter a numerical value to set a hard limit to the amount of times a user can repeat a task.<br/> Leave blank if no limit.',
				'id' => $prefix.'store_repeat_amount',
				'type' => 'text'
			),
			array(
				'name' => 'Shortcode',
				'desc' => 'Insert this shortcode where you want the task to appear.',
				'type' => 'go_store_shortcode'
			),		
		),
	);
	return $meta_boxes;
}
add_action( 'cmb_render_go_presets', 'go_presets_js' );
add_filter( 'cmb_meta_boxes', 'go_mta_con_meta' );
add_action( 'init', 'go_init_mtbxs', 9999 );



add_action( 'cmb_render_go_presets', 'go_cmb_render_go_presets', 10, 0 );
function go_cmb_render_go_presets() {
    ?><select id="go_presets" onchange="apply_presets();">
                    <option>...</option>
                    <?php
					$presets = get_option('go_presets',false);
					if($presets){
		   foreach($presets as $key=>$value){ 
		  
			 echo '<option value="'.$key.'" points="'.$value[0].'" currency="'.$value[1].'">'.$key.' - '.$value[0].' - '.$value[1].'</option>';  
		   }}
					 ?>
                    </select> <?php
}

add_action( 'cmb_render_go_task_shortcode', 'go_cmb_render_go_task_shortcode', 10, 0 );
function go_cmb_render_go_task_shortcode() {
 echo '<input type="text" disabled value="[go_task id=\''.get_the_id().'\']"/><br/><span>Insert this shortcode where you want the task to appear.</span>';
}

add_action('cmb_render_go_video_shortcode', 'go_cmb_render_go_video_shortcode');
function go_cmb_render_go_video_shortcode(){
	echo '<input type="text" disabled value="[go_display_video video_url=\'\' video_title=\'\' width=\'\' height=\'\']"/><br/><span>Insert this shortcode where you want a video link to appear.</span>';	
}

add_action('cmb_render_go_upload_shortcode', 'go_cmb_render_go_upload_shortcode');
function go_cmb_render_go_upload_shortcode(){
	echo '<input type="text" disabled value="[go_upload]"/><br/><span>Insert this shortcode where you want an upload button to appear</span>';
}

add_action( 'cmb_render_go_store_shortcode', 'go_cmb_render_go_store_shortcode', 10, 0 );
function go_cmb_render_go_store_shortcode() {
 echo '<input type="text" disabled value="[go_store id=\''.get_the_id().'\']"';
 
}

add_action( 'cmb_render_go_button', 'go_cmb_render_go_button', 9, 0 );
function go_cmb_render_go_button() {
 echo '<input id="go_mta_test_lock_add_button" type="button" value="Add Question"/>';
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
	return '<a id="go_help_'.$field.'" class="go_task_opt_help" onclick="go_display_help_video(\''.$video_url.'\');" title="'.$title.'" style="background: #DBDBDB !important;">?</a>';
}