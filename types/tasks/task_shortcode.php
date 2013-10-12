<?php
/*
	This is the file that displays content in a post/page with a task. 
	This file interprets and executes the shortcode in a post's body. 
*/

// Task Shortcode
function go_task_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'id' => '', // ID defined in Shortcode
		'cats' => '', // Cats defined in Shortcode     
	), $atts) );
	$user_ID = get_current_user_id(); // User ID
	if ($id) { // If the shortcode has an attribute called id, run this code
		$custom_fields = get_post_custom($id); // Just gathering some data about this task with its post id
		$task_currency = $custom_fields['go_mta_task_currency'][0]; // Currency granted after each stage of task
		$task_points = $custom_fields['go_mta_task_points'][0]; // Points granted after each stage of task
		$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task
		$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
		$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
		$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Repeat Message
		$description = $custom_fields['go_mta_quick_desc'][0]; // Description
		$currency_array = explode(',', $task_currency); // Makes an array out of currency values for each stage
		$points_array = explode(',', $task_points); //Makes an array out of currency values for each stage
		
		$content_post = get_post($id); // Grabs content of a task from the post table in your wordpress database where post_id = id in the shortcode. 
		$task_content = $content_post->post_content; // Grabs what the task actually says in the body of it
		
		if ($task_content == '') { // If the task is empty, run this code
			$accpt_mssg = $custom_fields['go_mta_accept_message'][0]; // Accept message meta field exists, set accept message equal to the meta field's content
		} elseif($task_content != '' && !$custom_fields['go_mta_accept_message']) { // If content is returned from the post table, and the post doesn't have an accept message meta field, run this code
			add_post_meta($id, 'go_mta_accept_message', $task_content); // Add accept message meta field with value of the post's content from post table
		} else { // If the task has content in the post table, and has a meta field, run this code
			$accpt_mssg = $custom_fields['go_mta_accept_message'][0]; // Set value of accept message equal to the task's accept message meta field value
		}
		
		if($user_ID == 0){ // If user isn't logged in, run this code
			echo wpautop($description).wpautop($accpt_mssg).wpautop($completion_message).wpautop($mastery_message); // Displays task content
		}
		
		$req_rank = $custom_fields['go_mta_req_rank'][0]; // Required Rank to accept task
		global $current_points;
		if ($current_points < $req_rank) {
			if($user_ID == 0) { // If the user isn't logged in, run this code
				echo '';	// Echo nothing as they don't need to know how many points necessary to start it 
			} else { // If the user is logged in, and does not have enough points, run this code
				$points = $req_rank - $current_points; // Grabs points difference between user's current points, and points required to start the task
				$points_name = go_return_options('go_points_name'); // Grabs what the points are called, whether it's XP or something else
				echo 'You need '.$points.' more '.$points_name.' to begin this task.'; // displays the message stating the need for more points to start the task
			}
		} else {
			
			//Stage Stuff
			global $wpdb;
			$user_ID = get_current_user_id(); // User ID
			$go_table_ind = $wpdb->prefix.'go';
			$status = (int)$wpdb->get_var("select status from ".$go_table_ind." where post_id = $id and uid = $user_ID");
?> <div id="go_description"> <?php echo  do_shortcode(wpautop($description)) ;?> </div>
<?php

			switch ($status) {
				
				// This one's for you First Timers out there...
				case '': 
					go_add_post($user_ID, $id, 0, $points_array[0], $currency_array[0], get_the_ID());
					
?>
				<div id="go_content"> <br />
				<button id="go_button" status="2" onclick="task_stage_change();this.disabled=true;"><?= go_return_options('go_second_stage_button') ?></button>
            	</div>
                
<?php			
				break;
				
				// Encountered
				case '1': 
?>
				<div id="go_content"> <br />
				<button id="go_button" status= "2" onclick="task_stage_change();this.disabled=true;"><?= go_return_options('go_second_stage_button') ?></button>
         		</div>   
<?php
				break;
				
				// Accepted
				case '2': 
					echo '<div id="go_content">'. do_shortcode(wpautop($accpt_mssg)).'<br /> <button id="go_button" status="3" onclick="task_stage_change();this.disabled=true;">'.go_return_options('go_third_stage_button').'</button></div>';
				break;
				
				// Completed
				case '3': 
					echo '<div id="go_content">'. do_shortcode(wpautop($accpt_mssg)).''.do_shortcode(wpautop($completion_message)).'<br /> <button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;">'.go_return_options('go_fourth_stage_button').'</button></div>';
				break;
				
				// Mastered
				case '4':  
					echo'<div id="go_content">'. do_shortcode(wpautop($accpt_mssg)).do_shortcode(wpautop($completion_message)).do_shortcode(wpautop($mastery_message));
					if ($repeat == 'on') {
		/*		echo '<button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;" repeat="on">'.go_return_options('go_repeat_button').'</button></div>'; */
		
						echo '<div id="go_repeat_clicked" style="display:none;">'
						.do_shortcode(wpautop($repeat_message)).
						'<br/><button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;" repeat="on">'
						.go_return_options('go_repeat_button').
						'</button>
						</div>
						<div id="go_repeat_unclicked">
						<button onclick="go_repeat_replace();">'
						.go_return_options('go_repeat_button').
						'</button>
						</div>';
					} else {
						echo '</div>';
					}
				break;		
			}
?>
		
	<script language="javascript">
	function go_repeat_replace(){
		jQuery('#go_repeat_unclicked').remove();
		jQuery('#go_repeat_clicked').show();		
	}
	function task_stage_change(){
		var color = jQuery('#go_admin_bar_progress_bar').css("background-color");
		ajaxurl = '<?php echo get_site_url() ?>/wp-admin/admin-ajax.php';
		jQuery.ajax({
			type: "post",
			url: ajaxurl,
			data: { 
				action: 'task_change_stage', 
				post_id: <?php echo $id ?>, 
				user_id: <?php echo $user_ID ?>, 
				status: jQuery('#go_button').attr('status'),
				repeat: jQuery('#go_button').attr('repeat'),
				page_id: <?php echo get_the_ID(); ?>,},
				success: function(html){
					jQuery('#go_content').html(html);
					jQuery('#go_admin_bar_progress_bar').css({"background-color": color})
				}
		});	
	}
	</script>
		
<?php
			echo $the_stage; // Just for Testing Purposes
			edit_post_link('Edit '.go_return_options('go_tasks_name'), '<br />
	<p>', '</p>', $id);
		} // Ends else statement
	} // Ends if statement
} // Ends function
add_shortcode('go_task','go_task_shortcode');
function task_change_stage(){
	global $wpdb;
	$task_id = $_POST['post_id'];
	$user_id = $_POST['user_id'];
	$status = $_POST['status'];
	$page_id = $_POST['page_id'];
	$repeat_button = $_POST['repeat'];
		$custom_fields = get_post_custom($task_id); // Just gathering some data about this task with its post id
		$req_rank = $custom_fields['go_mta_req_rank'][0]; // Required Rank to accept Task
		$task_currency = $custom_fields['go_mta_task_currency'][0]; // Currency granted after each stage of task
		$task_points = $custom_fields['go_mta_task_points'][0]; // Points granted after each stage of task
		$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task
		$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
		$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
		$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Mastery Message
		$description = $custom_fields['go_mta_quick_desc'][0]; // Description
		$currency_array = explode(',', $task_currency); // Makes an array out of currency values for each stage
		$points_array = explode(',', $task_points); //Makes an array out of currency values for each stage
		// Stage Stuff
		$content_post = get_post($task_id);
		$task_content = $content_post->post_content;
		if ($task_content == '') {
			$accpt_mssg = $custom_fields['go_mta_accept_message'][0]; // Completion Message
		} else {
			$accpt_mssg = $content_post->post_content;
		}
		$table_name_go = $wpdb->prefix . "go";
		if($repeat_button != 'on'){
			$check = (int) $wpdb->get_var("select status from ".$table_name_go." where uid = $user_id and post_id = $task_id");
			if($check == 0 || $check < ($status)){
				go_add_post($user_id, $task_id, $status, $points_array[$status-1], $currency_array[$status-1], $page_id, $repeat_button  ); }
			} else {
				go_add_post($user_id, $task_id, $status, $points_array[$status-1], $currency_array[$status-1], $page_id, $repeat_button  );
			}
	switch($status) {
		case 1:
			echo '<div id="go_content">'.do_shortcode(wpautop($accpt_mssg, false)).' <button id="go_button" status="2" onclick="task_stage_change();this.disabled=true;">'.go_return_options('go_second_stage_button').'</button></div>';
			break;
		case 2:
			echo '<div id="go_content">'.do_shortcode(wpautop($accpt_mssg, false)).' <button id="go_button" status="3" onclick="task_stage_change();this.disabled=true;">'.go_return_options('go_third_stage_button').'</button></div>';
			break;
		case 3:
			echo '<div id="go_content">'.do_shortcode(wpautop($accpt_mssg, false)).do_shortcode(wpautop($completion_message)).' <button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;">'.go_return_options('go_fourth_stage_button').'</button</div>';
			break;
		case 4:
			echo '<div id="go_content">'.do_shortcode(wpautop($accpt_mssg, false)).do_shortcode(wpautop($completion_message)).
			do_shortcode(wpautop($mastery_message));
			if ($repeat == 'on') {
				echo '<div id="go_repeat_clicked" style="display:none;">'
		.do_shortcode(wpautop($repeat_message)).
		'<br/><button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;" repeat="on">'
		.go_return_options('go_repeat_button').
		'</button>
		</div>
		<div id="go_repeat_unclicked">
		<button onclick="go_repeat_replace();">'
		.go_return_options('go_repeat_button').
		'</button>
		</div>';
			} else {
				echo '</div>';
			}
			break;
	}
	
die();
}
?>