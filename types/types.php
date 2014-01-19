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
		'title'      => go_return_options('go_tasks_name').' Options <br/> Click ?\'s for videos. <a href="javascript:;" onclick="go_display_help_video(\'maclab.guhsd.net/go/video/quests/questsIntro.mp4\')"> Please watch this video first. </a>',
		'pages'      => array( 'tasks' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Shortcode'.go_task_opt_help('shortocde', '', 'maclab.guhsd.net/go/video/quests/shortocde.mp4'),
				'desc' => 'Insert this shortcode where you want the task to appear.',
				'type' => 'go_shortcode'
			),
			
			array(
				'name' => 'Required Rank '.go_task_opt_help('req_rank', '', 'maclab.guhsd.net/go/video/quests/requiredRank.mp4'),
				'desc' => 'rank required to begin '.go_return_options('go_tasks_name').".",
				'id'   => $prefix . 'req_rank',
				'type' => 'select',
				'options' => go_get_all_ranks()
			),
			array(
				'name' => 'Presets'.go_task_opt_help('presets', '', 'maclab.guhsd.net/go/video/quests/presets.mp4'),
				'id'   => 'go_presets',
				'desc'=> '',
				'type' => 'go_presets',
			),
			array(
				'name' => 'Time Filter (Optional)'.go_task_opt_help('time_filter', '', 'maclab.guhsd.net/go/video/quests/timeFilter.mp4'),
				'desc' => 'Hides this post from all users with less than the entered amount of time (in minutes).',
				'id' => $prefix . 'time_filter',
				'type' => 'text'
			),
			array(
				'name' => 'Lock by '.go_return_options('go_focus_name').' Category? (Optional)'.go_task_opt_help('lock_by_cat', '', ' maclab.guhsd.net/go/video/quests/lockByProfessionCategory.mp4'),
				'desc' => ' Check this box to lock this task by its '.go_return_options('go_focus_name').' category.',
				'id' => $prefix.'focus_category_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Points'.go_task_opt_help('points', '', 'maclab.guhsd.net/go/video/quests/points.mp4'),
				'desc' => 'points awarded for encountering, accepting, completing, and mastering the '.go_return_options('go_tasks_name').'. (comma seperated, e.g. 10,20,50,70).',
				'id'   => $prefix . 'task_points',
				'type' => 'text',
			),
			array(
				'name' => 'Currency'.go_task_opt_help('currency', '', 'maclab.guhsd.net/go/video/quests/currency.mp4'),
				'desc' => 'currency awarded for encountering, accepting, completing, and mastering the '.go_return_options('go_tasks_name').'. (comma seperated, e.g. 10,20,50,70).',
				'id'   => $prefix . 'task_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Encounter Message'.go_task_opt_help('encounter', '', 'maclab.guhsd.net/go/video/quests/encounterMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>encountered</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'quick_desc',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Accept Message'.go_task_opt_help('accept', '', 'maclab.guhsd.net/go/video/quests/acceptMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>accepted</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'accept_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Completion Message (Optional)'.go_task_opt_help('complete', '', 'maclab.guhsd.net/go/video/quests/completeMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>completed</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'complete_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
				),
			array(
				'name' => 'Lock complete stage (Optional)'.go_task_opt_help('lock_complete', '', 'maclab.guhsd.net/go/video/quests/lockCompleteStage.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has entered a specified password.',
				'id' => $prefix.'complete_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Completion unlock password'.go_task_opt_help('unlock_complete', '', 'maclab.guhsd.net/go/video/quests/completionUnlockPassword.mp4'),
				'desc' => 'Enter a password into this field which is used when a user attempts to complete a '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'complete_unlock',
				'type' => 'text'
			),
			array(
				'name' => 'Completion Check for Understanding (Optional)'.go_task_opt_help('complete_understand', '', 'maclab.guhsd.net/go/video/quests/completionCheckForUnderstanding.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has completed the test.',
				'id' => $prefix.'test_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Check Type'.go_task_opt_help('complete_understand_checktype', '', 'maclab.guhsd.net/go/video/quests/completeCheckType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_lock_type',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question'.go_task_opt_help('complete_understand_question', '', 'maclab.guhsd.net/go/video/quests/completeCheckQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_question',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('complete_understand_answer', '', 'maclab.guhsd.net/go/video/quests/completeCheckAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_answers',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('complete_understand_key', '', 'maclab.guhsd.net/go/video/quests/completeCheckKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_lock_key',
				'type' => 'text'
			),
			array(
				'name' => 'Mastery Message (Optional)'.go_task_opt_help('mastery', '', 'maclab.guhsd.net/go/video/quests/masteryMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>mastered</i> the '.go_return_options('go_tasks_name'),
				'id' => $prefix . 'mastery_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
				),
			array(
				'name' => 'Lock mastery stage (Optional)'.go_task_opt_help('lock_mastery', '', 'maclab.guhsd.net/go/video/quests/lockMasteryStage.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has entered a specified password.',
				'id' => $prefix.'mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Mastery Check for Understanding (Optional)'.go_task_opt_help('mastery_understand', '', 'maclab.guhsd.net/go/video/quests/masteryCheckForUnderstanding.mp4'),
				'desc' => ' Check to lock this stage of a '.go_return_options('go_tasks_name').' until a user has completed the test.',
				'id' => $prefix.'test_mastery_lock',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Check Type'.go_task_opt_help('mastery_understand_checktype', '', 'maclab.guhsd.net/go/video/quests/masteryCheckType.mp4'),
				'desc' => 'Select the type of test that is given to the user.',
				'id' => $prefix.'test_mastery_lock_type',
				'type' => 'select',
				'options' => array(
                	array( 'name' => __( 'Radio', 'cmb' ), 'value' => 'radio', ),
					array( 'name' => __( 'Checkbox', 'cmb' ), 'value' => 'checkbox', ),
                )
			),
			array(
				'name' => 'Check Question'.go_task_opt_help('mastery_understand_question', '', 'maclab.guhsd.net/go/video/quests/masteryCheckQuestion.mp4'),
				'desc' => 'Enter a question that the user must answer to continue the '.go_return_options('go_tasks_name').
							'<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_question',
				'type' => 'text'
			),
			array(
				'name' => 'Check Answers'.go_task_opt_help('mastery_understand_answer', '', 'maclab.guhsd.net/go/video/quests/masteryCheckAnswers.mp4'),
				'desc' => 'Enter at least 2 possible answers that a user could chose from to answer the provided question. 
							Separate each answer with three octothorpes ("###"). <code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_answers',
				'type' => 'text'
			),
			array(
				'name' => 'Check Key'.go_task_opt_help('mastery_understand_key', '', 'maclab.guhsd.net/go/video/quests/masteryCheckKey.mp4'),
				'desc' => 'Enter the correct answer/answers for the test.  If the "checkbox" test type is selected, 
							more than one answer (key) is required. Separate each answer with three octothorpes ("###").
							<code>Note: Apostrophes (\', ") are not permited.</code>',
				'id' => $prefix.'test_mastery_lock_key',
				'type' => 'text'
			),
			array(
				'name' => 'Mastery unlock password'.go_task_opt_help('unlock_mastery', '', 'maclab.guhsd.net/go/video/quests/masteryUnlockPassword.mp4'),
				'desc' => 'Enter a password into this field which is used when a user attempts to complete a '.go_return_options('go_tasks_name').".",
				'id' => $prefix.'mastery_unlock',
				'type' => 'text'
			),
			array(
					'name' => 'Repeatable'.go_task_opt_help('repeatable', '', 'maclab.guhsd.net/go/video/quests/repeatable.mp4'),
					'desc' => ' Select to make '.go_return_options('go_tasks_name').' repeatable.',
					'id'   => $prefix . 'task_repeat',
					'type' => 'checkbox'
				),
			array(
				'name' => 'Repeat Message (Optional)'.go_task_opt_help('repeat_message', '', 'maclab.guhsd.net/go/video/quests/repeatMessage.mp4'),
				'desc' => 'Enter a message for the user to recieve when they have <i>repeated</i> the '.go_return_options('go_tasks_name').".",
				'id' => $prefix . 'repeat_message',
				'type' => 'wysiwyg',
        		'options' => array(
								'wpautop' => true,
								'textarea_rows' => '5',
							),
						
				),
			array(
				'name' => 'Allowed Repeatable Times (Optional)'.go_task_opt_help('repeat_limit', '', 'maclab.guhsd.net/go/video/quests/allowedRepeatableTimes.mp4'),
				'desc' => 'Enter a numerical value to set a hard limit to the amount of times a user can repeat a task.<br/> Leave blank if no limit.',
				'id' => $prefix.'repeat_amount',
				'type' => 'text'
				),
			array(
				'name' => 'Shortcode',
				'desc' => 'Insert this shortcode where you want the task to appear.',
				'type' => 'go_shortcode'
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
				'name' => 'Shortcode',
				'desc' => 'Insert this shortcode where you want the task to appear.',
				'type' => 'go_store_shortcode'
			),
			array(
				'name' => 'Penalty Switch',
				'desc' => 'Allow user\'s currency to go negative when purchasing this item.',
				'id' => $prefix . 'penalty_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => go_return_options('go_focus_name').' Gateway? (Optional)',
				'desc' => ' Check this box to convert this item into a focus gateway. When a user purchases this item, this focus pathway will be added to their account.',
				'id' => $prefix . 'focus_item_switch',
				'type' => 'checkbox'
			),
			array(
				'name' => go_return_options('go_focus_name'),
				'desc' => 'Select the '.go_return_options('go_focus_name').' to be associated with this item.',
				'id' => $prefix.'focuses',
				'type' => 'select',
				'options' => go_get_all_focuses()
			),
			array(
				'name' => 'Time Filter (Optional)',
				'desc' => 'Hides this post from all users with less than the entered amount of time (in minutes).',
				'id' => $prefix . 'store_time_filter',
				'type' => 'text'
			),
			array(
				'name' => 'Required Rank',
				'desc' => 'Rank required to purchase the item.',
				'id'   => $prefix . 'store_rank',
				'type' => 'select',
				'options' => go_get_all_ranks()
			),
			array(
				'name' => 'Currency',
				'desc' => 'Currency required to purchase the item.',
				'id'   => $prefix . 'store_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Points',
				'desc' => 'Points required to purchase item.',
				'id'   => $prefix . 'store_points',
				'type' => 'text',
			),
			array(
				'name' => 'Time',
				'desc' => 'Time required to purchase item.',
				'id'   => $prefix . 'store_time',
				'type' => 'text',
			),
			array(
				'name' => 'Item URL (Optional)',
				'desc' => 'URL to be displayed when the item is purchased. Leave blank if you don\'t need a link.',
				'id' => $prefix . 'store_itemURL',
				'type' => 'text'	
			),
			array(
				'name' => 'Allowed Repeatable Times (Optional)',
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

add_action( 'cmb_render_go_shortcode', 'go_cmb_render_go_shortcode', 10, 0 );
function go_cmb_render_go_shortcode() {
 echo '<input type="text" disabled value="[go_task id=\''.get_the_id().'\']"';
 
}

add_action( 'cmb_render_go_store_shortcode', 'go_cmb_render_go_store_shortcode', 10, 0 );
function go_cmb_render_go_store_shortcode() {
 echo '<input type="text" disabled value="[go_store id=\''.get_the_id().'\']"';
 
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
	echo '   <div class="dark"> </div>
    <div class="light">
        <div style="margin: 10px 10px 10px 10px; width: 864px; height: 540px;">
        	<video id="go_option_help_video" class="video-js vjs-default-skin vjs-big-play-centered" controls height="100%" width="100%" ><source src="" type="video/mp4"/></video/options>
        </div>
    </div>';
}

add_action('admin_init', 'go_create_help_video_lb');

function go_task_opt_help($field, $title, $video_url = null) {
	return '<a id="go_help_'.$field.'" class="go_task_opt_help" onclick="go_display_help_video(\''.$video_url.'\');" title="'.$title.'" style="background: #DBDBDB !important;">?</a>';
}