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
				'name' => 'Order of Tasks in Chain'.go_task_opt_help('task_chain_order', '', 'http://maclab.guhsd.net/go/video/quests/tasksInChain'),
				'type' => 'go_pick_order_of_chain'
			),
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
				'name' => 'Nerf Dates <br/>(Optional)'.go_task_opt_help('nerf_dates', '', 'http://maclab.guhsd.net/go/video/quests/nerfDates.mp4'),
				'id' => $prefix.'date_picker',
				'type' => 'go_decay_table'
			),
			array(
				'name' => 'Time Filter <br/>(Optional)'.go_task_opt_help('time_filter', '', 'http://maclab.guhsd.net/go/video/quests/timeFilter.mp4'),
				'desc' => 'Hides this post from all users with less than the entered amount of time (in minutes).',
				'id' => $prefix . 'time_filter',
				'type' => 'text'
			),
			array(
				'name' => 'Lock by '.go_return_options('go_focus_name').' Category <br/>(Optional)'.go_task_opt_help('lock_by_cat', '', ' http://maclab.guhsd.net/go/video/quests/lockByProfessionCategory.mp4'),
				'desc' => 'Check this box to lock this task by its '.go_return_options('go_focus_name').' category.',
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
				'name' => 'Encounter Message <br/>(Optional)'.go_task_opt_help('encounter', '', 'http://maclab.guhsd.net/go/video/quests/encounterMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>encountered</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'quick_desc',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Accept Message <br/>(Optional)'.go_task_opt_help('accept', '', 'http://maclab.guhsd.net/go/video/quests/acceptMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>accepted</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'accept_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Completion Message <br/>(Optional)'.go_task_opt_help('complete', '', 'http://maclab.guhsd.net/go/video/quests/completionMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>completed</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'complete_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
			),
			array(
				'name' => 'Completion File Upload <br/>(Optional)'.go_task_opt_help('completion_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/completionFileUpload.mp4'),
 				'desc' => 'Toggle to require a user to upload a file before completing the '.go_return_options('go_tasks_name').".",
 				'id' => $prefix.'completion_upload',
 				'type' => 'checkbox'
 			),
			array(
				'name' => 'Lock Complete Stage <br/>(Optional)'.go_task_opt_help('lock_complete', '', 'http://maclab.guhsd.net/go/video/quests/lockCompleteStage.mp4'),
				'desc' => 'Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has entered a specified password.',
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
				'name' => 'Completion Check for Understanding <br/>(Optional)'.go_task_opt_help('complete_understand', '', 'http://maclab.guhsd.net/go/video/quests/completionCheckForUnderstanding.mp4'),
				'desc' => 'Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has completed the test.',
				'id' => $prefix.'test_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Return Points <br/>(Optional)'.go_task_opt_help('complete_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'desc' => 'Check to allow tests to return points, based on the tier of the current '.go_return_options('go_tasks_name').', that diminish as the number of test failures increase.',
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
 				'name' => 'Test Questions and Answers'.go_task_opt_help('complete_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4'),
 				'desc' => 'Enter questions and answers in the fields below, select which answers are correct. <code>Note: Questions with the "Checkbox" type require atleast two designated
 							correct answers.</code>',
 				'id' => $prefix.'test_lock_completion',
 				'type' => 'go_test_field'
 			),
			array(
				'name' => 'Toggle Mastery Stage <br/>(Optional)'.go_task_opt_help('toggle_mastery_stage', '', 'http://maclab.guhsd.net/go/video/quests/toggleMasteryStage.mp4'),
				'desc' => 'Choose to deactive the mastery stage, for a three stage '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'task_mastery',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Mastery Message <br/>(Optional)'.go_task_opt_help('mastery', '', 'http://maclab.guhsd.net/go/video/quests/masteryMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>mastered</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'mastery_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
			),
			array(
				'name' => 'Mastery File Upload <br/>(Optional)'.go_task_opt_help('mastery_file_upload', '', 'http://maclab.guhsd.net/go/video/quests/masteryFileUpload.mp4'),
				'desc' => 'Toggle to require a user to upload a file before mastering the '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'mastery_upload',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Lock Mastery Stage <br/>(Optional)'.go_task_opt_help('lock_mastery', '', 'http://maclab.guhsd.net/go/video/quests/lockMasteryStage.mp4'),
				'desc' => 'Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has entered a specified password.',
				'id' => $prefix.'mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Mastery unlock password <br/>(Optional)'.go_task_opt_help('unlock_mastery', '', 'http://maclab.guhsd.net/go/video/quests/masteryUnlockPassword.mp4'),
				'desc' => 'Enter a password into this field which is used when a user attempts to complete a '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'mastery_unlock',
				'type' => 'text'
			),
			array(
				'name' => 'Mastery Check for Understanding <br/>(Optional)'.go_task_opt_help('mastery_understand', '', 'http://maclab.guhsd.net/go/video/quests/masteryCheckForUnderstanding.mp4'),
				'desc' => 'Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has completed the test.',
				'id' => $prefix.'test_mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Test Return Points <br/>(Optional)'.go_task_opt_help('mastery_understand_return_points', '', 'http://maclab.guhsd.net/go/video/quests/returnPoints.mp4'),
				'desc' => 'Check to allow tests to return points, based on the tier of the current '.go_return_options('go_tasks_name').', that diminish as the number of test failures increase.',
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
 				'name' => 'Test Questions and Answers'.go_task_opt_help('mastery_understand_test_fields', '', 'http://maclab.guhsd.net/go/video/quests/testFields.mp4'),
 				'desc' => 'Enter questions and answers in the fields below, select which answers are correct. <code>Note: Questions with the "Checkbox" type require atleast two designated
 							correct answers.</code>',
 				'id' => $prefix.'test_lock_mastery',
 				'type' => 'go_test_field_mastery'
 			),
			array(
				'name' => 'Repeatable <br/>(Optional)'.go_task_opt_help('repeatable', '', 'http://maclab.guhsd.net/go/video/quests/repeatable.mp4'),
				'desc' => 'Select to make '.go_return_options('go_tasks_name').' repeatable.',
				'id'   => $prefix . 'task_repeat',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Repeat Message <br/>(Optional)'.go_task_opt_help('repeat_message', '', 'http://maclab.guhsd.net/go/video/quests/repeatMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>repeated</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'repeat_message',
				'type' => 'wysiwyg',
        		'options' => array(
					'wpautop' => true,
					'textarea_rows' => '5',
				),		
			),
			array(
				'name' => 'Allowed Repeatable Times <br/>(Optional)'.go_task_opt_help('repeat_limit', '', 'http://maclab.guhsd.net/go/video/quests/allowedRepeatableTimes.mp4'),
				'desc' => 'Enter a numerical value to set a hard limit to the amount of times a user can repeat a task.<br/> Leave blank if no limit.',
				'id' => $prefix.'repeat_amount',
				'type' => 'text'
			),
			array(
				'name' => 'Final Chain Message (Optional)'.go_task_opt_help('final_chain_message', '', 'http://maclab.guhsd.net/go/video/quests/finalChainMessage.mp4'),
				'desc' => 'Enter a message to be displayed after the <strong>final</strong> '.strtolower(go_return_options('go_tasks_name')).' in this '.strtolower(go_return_options('go_tasks_name')).' chain has been fully finished',
				'id' => $prefix.'final_chain_message',
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
				'name' => go_return_options('go_focus_name').' Gateway? <br/>(Optional)'.go_task_opt_help('focus_gateway', '', 'http://maclab.guhsd.net/go/video/store/focusGateway.mp4'),
				'desc' => 'Check this box to convert this item into a focus gateway. When a user purchases this item, this focus pathway will be added to their account.',
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
				'name' => 'Time Filter <br/>(Optional)'.go_task_opt_help('time_filter', '', 'http://maclab.guhsd.net/go/video/store/timeFilter.mp4'),
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
				'name' => 'Currency Cost'.go_task_opt_help('currency', '', 'http://maclab.guhsd.net/go/video/store/currency.mp4'),
				'desc' => 'Currency required to purchase the item.',
				'id'   => $prefix . 'store_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Points Cost'.go_task_opt_help('points', '', 'http://maclab.guhsd.net/go/video/store/points.mp4'),
				'desc' => 'Points required to purchase item.',
				'id'   => $prefix . 'store_points',
				'type' => 'text',
			),
			array(
				'name' => 'Time Cost'.go_task_opt_help('time', '', 'http://maclab.guhsd.net/go/video/store/time.mp4'),
				'desc' => 'Time required to purchase item.',
				'id'   => $prefix . 'store_time',
				'type' => 'text',
			),
			array(
				'name' => 'Exchange Switch <br/>(Optional)'.go_task_opt_help('exchange', '', 'http://maclab.guhsd.net/go/video/store/exchange.mp4'),
				'desc' => 'Check this box to allow users to purchase this item for one another',
				'id' => $prefix.'store_exchange_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Exchange Currency'.go_task_opt_help('exchange_currency', '', 'http://maclab.guhsd.net/go/video/store/exchangeCurrency.mp4'),
				'desc' => 'Currency to be given to recipient of item. Leave blank to reward none.',
				'id' => $prefix.'store_exchange_currency',
				'type' => 'text'
			),
			array(
				'name' => 'Exchange Points'.go_task_opt_help('exchange_points', '', 'http://maclab.guhsd.net/go/video/store/exchangePoints.mp4'),
				'desc' => 'Points to be given to recipient of item. Leave blank to reward none.',
				'id' => $prefix.'store_exchange_points',
				'type' => 'text'
			),
			array(
				'name' => 'Exchange Time'.go_task_opt_help('exchange_time', '', 'http://maclab.guhsd.net/go/video/store/exchangeTime.mp4'),
				'desc' => 'Time to be given to recipient of item. Leave blank to reward none.',
				'id' => $prefix.'store_exchange_time',
				'type' => 'text'
			),
			array(
				'name' => 'Item URL <br/>(Optional)'.go_task_opt_help('item_url', '', 'http://maclab.guhsd.net/go/video/store/itemURL.mp4'),
				'desc' => 'URL to be displayed when the item is purchased. Leave blank if you don\'t need a link.',
				'id' => $prefix . 'store_itemURL',
				'type' => 'text'	
			),
			array(
				'name' => 'Allowed Repeatable Times <br/>(Optional)'.go_task_opt_help('repeat_limit', '', 'http://maclab.guhsd.net/go/video/store/allowedRepeatableTimes.mp4'),
				'desc' => 'Enter a numerical value to set a hard limit to the amount of times a user can repeat a task.<br/> Leave blank if no limit.',
				'id' => $prefix.'store_repeat_amount',
				'type' => 'text'
			),
			array(
				'name' => 'Badge <br/>(Optional)'.go_task_opt_help('badge', '', 'http://maclab.guhsd.net/go/video/store/badge.mp4'),
				'desc' => 'Check this box to have a badge associated with this item.',
				'id' => $prefix.'badge_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Badge ID'.go_task_opt_help('badge_id', '', 'http://maclab.guhsd.net/go/video/store/badgeID.mp4'),
				'desc' => 'ID of badge to be rewarded',
				'id' => $prefix.'badge_id',
				'type' => 'text'
			),
			array(
				'name' => 'Badge After Purchases'.go_task_opt_help('badge', '', 'http://maclab.guhsd.net/go/video/store/badgeAfterPurchases.mp4'),
				'desc' => 'Reward badge after this many purchases. Leave blank to default to 1 purchase.',
				'id' => $prefix.'badge_purchase_count',
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

add_action('cmb_render_go_decay_table', 'go_decay_table');
function go_decay_table(){
	?>
		<table id="go_list_of_decay_dates">
			<th>Date of Nerf</th><th>Percentage Nerf</th>
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
                        <td><input name="go_mta_task_decay_calendar[]" id="go_mta_task_decay_calendar" class="datepicker" value="<?php echo $date;?>" type="date"/></td>
                        <td><input name="go_mta_task_decay_percent[]" id="go_mta_task_decay_percent" value="<?php echo $percentages[$key]?>" type="text"/></td>
                    </tr>
                    <?php
				}
            }else{
			?>
			<tr>
				<td><input name="go_mta_task_decay_calendar[]" id="go_mta_task_decay_calendar" class="datepicker" type="date"/></td>
				<td><input name="go_mta_task_decay_percent[]" id="go_mta_task_decay_percent" type="text"/></td>
			</tr>
            <?php 
			}
			?>
		</table>
		<input type="button" id="go_mta_add_task_decay" onclick="go_add_decay_table_row()" value="+"/>
	<?php
}

add_action('cmb_validate_go_decay_table', 'go_validate_decay_table');
function go_validate_decay_table(){
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

add_action('cmb_render_go_test_field', 'go_test_field', 10, 1);
function go_test_field($field_args) {
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
	<table id='go_test_field_table'>
		<?php 
		if (!empty($test_field_block_count)) {
			for ($i = 0; $i < $test_field_block_count; $i++) {
				echo "
				<tr id='go_test_field_input_row_".$i."' class='go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_".$i."' class='go_test_field_input_select' name='go_test_field_select[]' onchange='update_checkbox_type(this);'>
						  <option value='radio' class='go_test_field_input_option'>Radio</option>
						  <option value='checkbox' class='go_test_field_input_option'>Checkbox</option>
						</select>";
						if (!empty($test_field_input_question)) {
							echo "<br/><br/><input class='go_test_field_input_question' name='go_test_field_input_question[]' placeholder='Shall We Play a Game?' type='text' value='".$test_field_input_question[$i]."' />";
						} else {
							echo "<br/><br/><input class='go_test_field_input_question' name='go_test_field_input_question[]' placeholder='Shall We Play a Game?' type='text' />";
						}
				if (!empty($test_field_input_count)) {
					echo "<ul>";
					for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
						echo "
							<li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value(this);' />
							<input class='go_test_field_input_checkbox_hidden' name='go_test_field_values[".$i."][1][]' type='hidden' />
							<input class='go_test_field_input' name='go_test_field_values[".$i."][0][]' placeholder='Enter An Answer!' type='text' value='".$test_field_input_array[$i][0][$x]."' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />";
						if ($x > 1) {
							echo "<input class='go_test_field_rm go_test_field_rm_input_button' type='button' value='X' onclick='remove_field(this);'>";
						}
						echo "</li>";
						if (($x + 1) == $test_field_input_count[$i]) {
							echo "<input class='go_test_field_add go_test_field_add_input_button' type='button' value='+' onclick='add_field(this);'/>";
						}
					}
					echo "</ul><ul>";
					if ($i > 0) {
						echo "<li><input class='go_test_field_rm_row_button' type='button' value='Remove' onclick='remove_block(this);' /></li>";
					}
					echo "<li><input class='go_test_field_input_count' name='go_test_field_input_count[]' type='hidden' value='".$test_field_input_count[$i]."' /></li></ul>";
				} else {
					echo "
					<ul>
						<li><input class='go_test_field_input_checkbox' name='go_test_field_input_checkbox_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values[".$i."][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values[".$i."][0][]' placeholder='Enter An Answer!' type='text' value='".$test_field_input_array[$i][0][0]."' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li>
						<li><input class='go_test_field_input_checkbox' name='go_test_field_input_checkbox_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values[".$i."][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values[".$i."][0][]' placeholder='Enter An Answer!' type='text' value='".$test_field_input_array[$i][0][1]."' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li>";
					echo "</ul><ul><li>";
					if ($i > 0) {
						echo "<input class='go_test_field_rm_row_button' type='button' value='Remove' onclick='remove_block(this);' /></li><li>";
					}
					echo "<input class='go_test_field_input_count' name='go_test_field_input_count[]' type='hidden' value='2' /></li></ul>";
				}
				echo "
					</td>
				</tr>";
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_0' class='go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_0' class='go_test_field_input_select' name='go_test_field_select[]' onchange='update_checkbox_type(this);'>
							<option value='radio' class='go_test_field_input_option'>Radio</option>
							<option value='checkbox' class='go_test_field_input_option'>Checkbox</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question' name='go_test_field_input_question[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_0' type='radio' onchange='update_checkbox_value(this);' />
								<input class='go_test_field_input_checkbox_hidden' name='go_test_field_values[0][1][]' type='hidden' />
								<input class='go_test_field_input' name='go_test_field_values[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_0' type='radio' onchange='update_checkbox_value(this);' />
								<input class='go_test_field_input_checkbox_hidden' name='go_test_field_values[0][1][]' type='hidden' />
								<input class='go_test_field_input' name='go_test_field_values[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />
							</li>
							<input class='go_test_field_add go_test_field_add_input_button' type='button' value='+' onclick='add_field(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count' name='go_test_field_input_count[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
		}
		?>
		<tr>
			<td>
				<input id='go_test_field_add_block_button' value='Add Block' type='button' onclick='add_block(this);' />
				<?php 
				if (!empty($test_field_block_count)) {
					echo "<input id='go_test_field_block_count' name='go_test_field_block_count' type='hidden' value='".$test_field_block_count."' />";
				} else {
					echo "<input id='go_test_field_block_count' name='go_test_field_block_count' type='hidden' value='1' />";
				}
				?>
			</td>
		</tr>
	</table>
	<script type='text/javascript'>
		var block_num = 0;
		var block_type = 'radio';
		var input_num = 0;
		var block_count = <?php echo $test_field_block_count; ?>;
		
		var test_field_select_array = new Array(
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
		var test_field_checked_array = [
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
		for (var i = 0; i < test_field_select_array.length; i++) {
			var test_field_with_select_value = '#go_test_field_select_'+i+' .go_test_field_input_option:contains(\''+test_field_select_array[i]+'\')';
			jQuery(test_field_with_select_value).attr('selected', true);
		}
		for (var x = 0; x < block_count; x++) {
			for (var z = 0; z < test_field_checked_array[x].length; z++) {
				var test_fields_with_checked_value = ".go_test_field_input[value='"+test_field_checked_array[x][z]+"']";
				var checked_fields = jQuery(test_fields_with_checked_value).siblings('.go_test_field_input_checkbox').attr('checked', true);
			}
		}
		var checkbox_obj_array = jQuery('.go_test_field_input_checkbox');
		for (var y = 0; y < checkbox_obj_array.length; y++) {
			var next_obj = checkbox_obj_array[y].nextElementSibling;
			if (checkbox_obj_array[y].checked) {
				var input_obj = next_obj.nextElementSibling.value;
				jQuery(next_obj).attr('value', input_obj);
			} else {
				jQuery(next_obj).removeAttr('value');
			}
		}
		function update_checkbox_value (target) {
			if (jQuery(target).hasClass('go_test_field_input')) {
				var obj = jQuery(target).siblings('.go_test_field_input_checkbox');
			} else {
				var obj = target;
			}
			var checkbox_type = jQuery(obj).prop('type');
			var input_field_val = jQuery(obj).siblings('.go_test_field_input').val();
			if (checkbox_type === 'radio') {
				var radio_name = jQuery(obj).prop('name');
				var radio_checked_str = ".go_test_field_input_checkbox[name='"+radio_name+"']:checked";
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden').attr('value', input_field_val);
					} else {
						jQuery(radio_checked_str).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
				}
				var radios_not_checked_str = ".go_test_field_input_checkbox[name='"+radio_name+"']:not(:checked)";
				jQuery(radios_not_checked_str).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
			} else {
				if (jQuery(obj).prop('checked')) {
					if (input_field_val != '') {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').attr('value', input_field_val);	
					} else {
						jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
					}
				} else {
					jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
				}
			}
		}
		function update_checkbox_type (obj) {
			block_type = jQuery(obj).children('option:selected').val();
			jQuery(obj).siblings('ul').children('li').children('input.go_test_field_input_checkbox').attr('type', block_type);
		}
		function add_block (obj) {
			block_num = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row').length;
			jQuery('#go_test_field_block_count').attr('value', (block_num + 1));
			var field_block = "<tr id='go_test_field_input_row_"+block_num+"' class='go_test_field_input_row'><td><select id='go_test_field_select_"+block_num+"' class='go_test_field_input_select' name='go_test_field_select[]' onchange='update_checkbox_type(this);'><option value='radio' class='go_test_field_input_option'>Radio</option><option value='checkbox' class='go_test_field_input_option'>Checkbox</option></select><br/><br/><input class='go_test_field_input_question' name='go_test_field_input_question[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_"+block_num+"' type='"+block_type+"' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values["+block_num+"][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values["+block_num+"][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li><li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_"+block_num+"' type='"+block_type+"' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values["+block_num+"][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values["+block_num+"][0][]' placeholder='No' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li><input class='go_test_field_add go_test_field_add_input_button' type='button' value='+' onclick='add_field(this);'/></ul><ul><li><input class='go_test_field_rm_row_button' type='button' value='Remove' onclick='remove_block(this);' /><input class='go_test_field_input_count' name='go_test_field_input_count[]' type='hidden' value='2' /></li></ul></td></tr>";
			jQuery(obj).parent().parent().before(field_block);
		}
		function remove_block (obj) {
			block_num = jQuery(obj).parents('tr').siblings('tr.go_test_field_input_row').length;
			jQuery('#go_test_field_block_count').attr('value', (block_num - 1));
			jQuery(obj).parents('tr.go_test_field_input_row').remove();
		}
		function add_field (obj) {
			input_num = jQuery(obj).siblings('li').length + 1;
			var block_id = jQuery(obj).parents('tr.go_test_field_input_row').first().attr('id');
			block_num = block_id.split('go_test_field_input_row_').pop();
			block_type = jQuery(obj).parent('ul').siblings('select').children('option:selected').val();
			jQuery(obj).parent('ul').siblings('ul').children('li').children('.go_test_field_input_count').attr('value', input_num);
			jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_"+block_num+"' type='"+block_type+"' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values["+block_num+"][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values["+block_num+"][0][]' placeholder='Enter An Answer!' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /><input class='go_test_field_rm go_test_field_rm_input_button' type='button' value='X' onclick='remove_field(this);'></li>");
		}
		function remove_field (obj) {
			jQuery(obj).parents('tr.go_test_field_input_row').find('input.go_test_field_input_count')[0].value--;
			jQuery(obj).parent('li').remove();
		}
		
	</script>
	<?php
}

add_action('cmb_validate_go_test_field', 'go_validate_test_field');
function go_validate_test_field() {
	$question_temp = $_POST['go_test_field_input_question'];
	$test_temp = $_POST['go_test_field_values'];
	$select = $_POST['go_test_field_select'];
	$block_count = (int)$_POST['go_test_field_block_count'];
	$input_count_temp = $_POST['go_test_field_input_count'];

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
	<table id='go_test_field_table_m'>
		<?php 
		if (!empty($test_field_block_count)) {
			for ($i = 0; $i < $test_field_block_count; $i++) {
				echo "
				<tr id='go_test_field_input_row_m_".$i."' class='go_test_field_input_row_m'>
					<td>
						<select id='go_test_field_select_m_".$i."' class='go_test_field_input_select_m' name='go_test_field_select_m[]' onchange='update_checkbox_type_m(this);'>
						  <option value='radio' class='go_test_field_input_option_m'>Radio</option>
						  <option value='checkbox' class='go_test_field_input_option_m'>Checkbox</option>
						</select>";
						if (!empty($test_field_input_question)) {
							echo "<br/><br/><input class='go_test_field_input_question_m' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' value='".$test_field_input_question[$i]."' />";
						} else {
							echo "<br/><br/><input class='go_test_field_input_question_m' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' />";
						}
				if (!empty($test_field_input_count)) {
					echo "<ul>";
					for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
						echo "
							<li><input class='go_test_field_input_checkbox_m' name='unused_go_test_field_input_checkbox_m_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_m(this);' />
							<input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[".$i."][1][]' type='hidden' />
							<input class='go_test_field_input_m' name='go_test_field_values_m[".$i."][0][]' placeholder='Enter An Answer!' type='text' value='".$test_field_input_array[$i][0][$x]."' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' />";
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
						echo "<li><input class='go_test_field_rm_row_button_m' type='button' value='Remove' onclick='remove_block_m(this);' /></li>";
					}
					echo "<li><input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='".$test_field_input_count[$i]."' /></li></ul>";
				} else {
					echo "
					<ul>
						<li><input class='go_test_field_input_checkbox_m' name='go_test_field_input_checkbox_m_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[".$i."][1][]' type='hidden' /><input class='go_test_field_input_m' name='go_test_field_values_m[".$i."][0][]' placeholder='Enter An Answer!' type='text' value='".$test_field_input_array[$i][0][0]."' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li>
						<li><input class='go_test_field_input_checkbox_m' name='go_test_field_input_checkbox_m_".$i."' type='".$test_field_select_array[$i]."' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[".$i."][1][]' type='hidden' /><input class='go_test_field_input_m' name='go_test_field_values_m[".$i."][0][]' placeholder='Enter An Answer!' type='text' value='".$test_field_input_array[$i][0][1]."' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li>";
					echo "</ul><ul><li>";
					if ($i > 0) {
						echo "<input class='go_test_field_rm_row_button_m' type='button' value='Remove' onclick='remove_block_m(this);' /></li><li>";
					}
					echo "<input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='2' /></li></ul>";
				}
				echo "
					</td>
				</tr>";
			}
		} else {
			echo "
				<tr id='go_test_field_input_row_m_0' class='go_test_field_input_row_m'>
					<td>
						<select id='go_test_field_select_m_0' class='go_test_field_input_select_m' name='go_test_field_select_m[]' onchange='update_checkbox_type_m(this);'>
							<option value='radio' class='go_test_field_input_option_m'>Radio</option>
							<option value='checkbox' class='go_test_field_input_option_m'>Checkbox</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_m' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_m' name='unused_go_test_field_input_checkbox_m_0' type='radio' onchange='update_checkbox_value_m(this);' />
								<input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[0][1][]' type='hidden' />
								<input class='go_test_field_input_m' name='go_test_field_values_m[0][0][]' placeholder='Enter An Answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_m' name='unused_go_test_field_input_checkbox_m_0' type='radio' onchange='update_checkbox_value_m(this);' />
								<input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m[0][1][]' type='hidden' />
								<input class='go_test_field_input_m' name='go_test_field_values_m[0][0][]' placeholder='Enter An Answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' />
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
				<input id='go_test_field_add_block_button_m' value='Add Block' type='button' onclick='add_block_m(this);' />
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
				var test_fields_with_checked_value = ".go_test_field_input_m[value='"+test_field_checked_array_m[x][z]+"']";
				var checked_fields = jQuery(test_fields_with_checked_value).siblings('.go_test_field_input_checkbox_m').attr('checked', true);
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
			var field_block = "<tr id='go_test_field_input_row_m_"+block_num_m+"' class='go_test_field_input_row_m'><td><select id='go_test_field_select_m_"+block_num_m+"' class='go_test_field_input_select_m' name='go_test_field_select_m[]' onchange='update_checkbox_type_m(this);'><option value='radio' class='go_test_field_input_option_m'>Radio</option><option value='checkbox' class='go_test_field_input_option_m'>Checkbox</option></select><br/><br/><input class='go_test_field_input_question_m' name='go_test_field_input_question_m[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox_m' name='unused_go_test_field_input_checkbox_m_"+block_num_m+"' type='"+block_type_m+"' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m["+block_num_m+"][1][]' type='hidden' /><input class='go_test_field_input_m' name='go_test_field_values_m["+block_num_m+"][0][]' placeholder='Enter An Answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li><li><input class='go_test_field_input_checkbox_m' name='unused_go_test_field_input_checkbox_m_"+block_num_m+"' type='"+block_type_m+"' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m["+block_num_m+"][1][]' type='hidden' /><input class='go_test_field_input_m' name='go_test_field_values_m["+block_num_m+"][0][]' placeholder='Enter An Answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /></li><input class='go_test_field_add go_test_field_add_input_button_m' type='button' value='+' onclick='add_field_m(this);'/></ul><ul><li><input class='go_test_field_rm_row_button_m' type='button' value='Remove' onclick='remove_block_m(this);' /><input class='go_test_field_input_count_m' name='go_test_field_input_count_m[]' type='hidden' value='2' /></li></ul></td></tr>";
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
			jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox_m' name='unused_go_test_field_input_checkbox_m_"+block_num_m+"' type='"+block_type+"' onchange='update_checkbox_value_m(this);' /><input class='go_test_field_input_checkbox_hidden_m' name='go_test_field_values_m["+block_num_m+"][1][]' type='hidden' /><input class='go_test_field_input_m' name='go_test_field_values_m["+block_num_m+"][0][]' placeholder='Enter An Answer!' type='text' oninput='update_checkbox_value_m(this);' oncut='update_checkbox_value_m(this);' onpaste='update_checkbox_value_m(this);' /><input class='go_test_field_rm go_test_field_rm_input_button_m' type='button' value='X' onclick='remove_field_m(this);'></li>");
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
	return '<a id="go_help_'.$field.'" class="go_task_opt_help" onclick="go_display_help_video(\''.$video_url.'\');" title="'.$title.'" style="background: #DBDBDB !important;">?</a>';
}

add_action('cmb_render_go_pick_order_of_chain', 'go_pick_order_of_chain');
function go_pick_order_of_chain(){
	global $wpdb;
	$task_id = get_the_id();
	if(get_the_terms($task_id, 'task_chains')){
		$chain = array_shift(array_values(get_the_terms($task_id, 'task_chains')));
		$posts_in_chain = get_posts(array(
			'post_type' => 'tasks',
			'taxonomy' => 'task_chains',
			'term' => $chain->name,
			'order' => 'ASC',
			'meta_key' => 'chain_position',
			'orderby' => 'meta_value_num'
		));
		
		?>
        <ul id="go_task_order_in_chain">
			<?php
            foreach($posts_in_chain as $post){
                echo '<li class="go_task_in_chain" post_id="'.$post->ID.'">'.$post->post_title.'</li>';
            }
            ?>
		</ul>
        <script type="text/javascript">
		jQuery('document').ready(function(e) {
           jQuery('#go_task_order_in_chain').sortable({
			   axis: "y", 
			   stop: function(event, ui){
				   var go_ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
				   var order = {};
				   var chain = '<?php echo $chain->name;?>';
				   var position = 1;
				   jQuery('.go_task_in_chain').each(function(i, el){
					   order[position] = jQuery(this).attr('post_id');
					   position++;
				   });
				   jQuery.ajax({
					   url: go_ajaxurl,
					   type: 'POST',
					   data: {
						   action: 'go_update_task_order',
						   order: order,
						   chain: chain
					   }
				   });   
			   }
			}); 
        });
		</script>
        <?php
	}
}

add_action('save_post', 'go_add_new_task_in_chain');
function go_add_new_task_in_chain(){
	$task_id = get_the_id();
	if(get_post_type($task_id) == 'tasks'){
		if(get_the_terms($task_id, 'task_chains')){
			$chain = array_shift(array_values(get_the_terms($task_id, 'task_chains')))->name;
		}
		if($chain && go_return_task_amount_in_chain($chain)){
			$position = go_return_task_amount_in_chain($chain) + 1;
		}
		add_post_meta($task_id, 'chain', $chain, true);
		add_post_meta($task_id, 'chain_position', $position, true);
	}
}

function go_update_task_order(){
	$order = $_POST['order'];
	$chain = $_POST['chain'];
	foreach($order as $key => $value){
		add_post_meta($value, 'chain', $chain, true);
		update_post_meta($value, 'chain_position', $key);
	}
	die();	
}

add_action('save_post', 'go_final_chain_message');
function go_final_chain_message(){
	$task_id = get_the_id();
	$custom = get_post_custom($task_id);
	if(get_post_type($task_id) == 'tasks'){
		if(get_the_terms($task_id, 'task_chains')){
			$chain = array_shift(array_values(get_the_terms($task_id, 'task_chains')));
			$posts_in_chain = get_posts(array(
				'post_type' => 'tasks',
				'taxonomy' => 'task_chains',
				'term' => $chain->name,
				'order' => 'ASC',
				'meta_key' => 'chain_position',
				'orderby' => 'meta_value_num'
			));
			$message = $custom['go_mta_final_chain_message'][0];
			foreach($posts_in_chain as $post){
               update_post_meta($post->ID, 'go_mta_final_chain_message', $message);
            }
		}
	}
}
?>