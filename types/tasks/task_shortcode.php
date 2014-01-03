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
	$page_id = get_the_ID();
	if ($id) { // If the shortcode has an attribute called id, run this code
	
		$custom_fields = get_post_custom($id); // Just gathering some data about this task with its post id
		$task_currency = $custom_fields['go_mta_task_currency'][0]; // Currency granted after each stage of task
		$task_points = $custom_fields['go_mta_task_points'][0]; // Points granted after each stage of task
		$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task
		
		if ($repeat == 'on' && $custom_fields['go_mta_repeat_amount'][0]){	// Checks if the task is repeatable and if it has a repeat limit
			$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page
		} elseif($repeat == 'on' && !$custom_fields['go_mta_repeat_amount']){ // Checks if the task is repeatable and if it does not have a repeat limit
			$repeat_amount = 0;	// Sets the limit equal to zero. In other words, unlimits the amount of times the task is repeatable
		}
		
		if($custom_fields['go_mta_time_filter'][0]){ // Checks if the task has a time filter
			$minutes_required = $custom_fields['go_mta_time_filter'][0]; // Sets the filter equal to the meta field value declared in the task creation page
		}
		
		if($custom_fields['go_mta_complete_lock'][0] && $custom_fields['go_mta_complete_unlock'][0]){ // Checks if the task completion stage has a lock and password
			$complete_lock = true; 
			$complete_unlock = $custom_fields['go_mta_complete_unlock'][0]; // Sets this variable equal to the password entered on the task creation page
		}
		
		if($custom_fields['go_mta_mastery_lock'][0] && $custom_fields['go_mta_mastery_unlock'][0]){ // Checks if the task mastery stage has a lock and password
			$mastery_lock = true; 
			$mastery_unlock = $custom_fields['go_mta_mastery_unlock'][0]; // Sets this variable equal to the password entered on the task creation page
		}
		
		$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
		$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
		$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Repeat Message
		$description = $custom_fields['go_mta_quick_desc'][0]; // Description
		$req_rank = $custom_fields['go_mta_req_rank'][0]; // Required Rank to accept task
		$currency_array = explode(',', $task_currency); // Makes an array out of currency values for each stage
		$points_array = explode(',', $task_points); //Makes an array out of currency values for each stage
		
		if($user_ID != 0){
			$current_minutes = go_return_minutes($user_ID);	
		}
		
		$admin = get_userdata(1);
		$admin_name = $admin->display_name;
		
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
			echo wpautop($description).wpautop($accpt_mssg).wpautop($completion_message);// Displays task content
		}
		
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
			$task_count = $wpdb->get_var("SELECT `count` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$status = (int)$wpdb->get_var("SELECT `status` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
?> 

			<div id="go_description"> <?php echo  do_shortcode(wpautop($description));?> </div>
            
<?php
			if($current_minutes >= $minutes_required || !$minutes_required){
				switch ($status) {
					
					// First time a user encounters a task
					case 0: 
					// sending go_add_post the $repeat var was the problem, that is why it is not sending a null value.
					go_add_post($user_ID, $id, 0, $points_array[0], $currency_array[0], $page_id, null, 0);
						
	?>
					<div id="go_content">
					<button id="go_button" status="2" onclick="task_stage_change();this.disabled=true;"><?= go_return_options('go_second_stage_button') ?></button>
					</div>
					
	<?php			
					break;
					
					// Encountered
					case 1: 
	?>
					<div id="go_content">
					<button id="go_button" status= "2" onclick="task_stage_change();this.disabled=true;"><?= go_return_options('go_second_stage_button') ?></button>
					</div>   
	<?php
					break;
					
					// Accepted
					case 2: 
						echo '<div id="go_content">'.do_shortcode(wpautop($accpt_mssg)).
						'<button id="go_button" status="3" onclick="task_stage_change();this.disabled=true;">'.
						go_return_options('go_third_stage_button').'</button>
						<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
						</div>';
						
						if($complete_lock){
							echo '<br/><div id="go_complete_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
							<input type="password" id="go_unlock_next_stage"/>';	
						}
					break;
					
					// Completed
					case 3: 
						echo '<div id="go_content">'. do_shortcode(wpautop($accpt_mssg)).'
						'.do_shortcode(wpautop($completion_message)).
						'<button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;">'.
						go_return_options('go_fourth_stage_button').'</button> 
						<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
						</div>';
						
						if($mastery_lock){
							echo '<br/><div id="go_complete_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
							<input type="password" id="go_unlock_next_stage"/>';	
						}
					break;
					
					// Mastered
					case 4:  
						echo'<div id="go_content">'. do_shortcode(wpautop($accpt_mssg)).do_shortcode(wpautop($completion_message)).do_shortcode(wpautop($mastery_message));	
						if ($repeat == 'on') {
							if($task_count < $repeat_amount || $repeat_amount == 0){ // Checks if the amount of times a user has completed a task is less than the amount of times they are allowed to complete a task. If so, outputs the repeat button to allow the user to repeat the task again. 
								echo '<div id="repeat_quest">
										<div id="go_repeat_clicked" style="display:none;">'
											.do_shortcode(wpautop($repeat_message)).
											'<button id="go_button" status="4" onclick="go_repeat_hide(jQuery(this));" repeat="on">'
												.go_return_options('go_fourth_stage_button')." Again". 
											'</button>
											<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
										</div>
										<div id="go_repeat_unclicked">
											<button id="go_button" status="3" onclick="go_repeat_replace();">'
												.go_return_options('go_repeat_button').
											'</button>
											<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
										</div>
									</div>';
							} else {
								echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>';
							}
							echo '</div>';
						} else {
							echo '<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button></div>';
						}
				}
			}

?>
	<script language="javascript">
		if(jQuery('#go_unlock_next_stage').length != 0){
			jQuery('#go_button').attr('disabled', 'true');
			var typing_timer;
			var doneTyping = 500;
			jQuery('#go_unlock_next_stage').keyup(function (){
				typing_timer = setTimeout(task_unlock, doneTyping);
			});
			jQuery('#go_unlock_next_stage').keydown(function (){
				clearTimeout(typing_timer);
			});
		} 
	
		function task_unlock(){
			ajaxurl = '<?php echo get_site_url() ?>/wp-admin/admin-ajax.php';
			var pwd_check = String(jQuery('#go_unlock_next_stage').val());
			var pwd_hash = CryptoJS.SHA1(pwd_check).toString();
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data:{
					action: 'unlock_stage',
					password_check: pwd_hash,
					task: <?php echo $id; ?>
				},
				success: function(response){
					if(response == 1){
						jQuery('#go_button').removeAttr('disabled');
						jQuery('.go_lock_message').html('Password correct, move on.');
						jQuery('#go_unlock_next_stage').remove();	
					} else{
						jQuery('.go_lock_message').html('Incorrect password, try again.');
					}
				}
			});
		}
		function go_repeat_hide(target) {
			// hides the div#repeat_quest to create the repeat cycle.
			jQuery("#repeat_quest").hide('slow');
			
			setTimeout(function() {
				// passes the jQuery object received in the parameter of the go_repeat_hide function
				// as an argument for the task_stage_change function, after 500 milliseconds (1.5 seconds).
				task_stage_change(target);
			}, 500);
		}
		
		function go_repeat_replace() {
 			jQuery('#go_repeat_unclicked').remove();
			jQuery('#go_repeat_clicked').show('slow');	 
		}
		
		function task_stage_change(target){
			var color = jQuery('#go_admin_bar_progress_bar').css("background-color");
			ajaxurl = '<?php echo get_site_url() ?>/wp-admin/admin-ajax.php';
			// redeclare (also called "overloading") the variable $task_count to the value of the 'count' var on the database.
			<?php $task_count = $wpdb->get_var("select `count` from ".$go_table_ind." where post_id = $id and uid = $user_ID"); ?>
			
			if(jQuery('.go_lock_message').length != 0){
				jQuery('.go_lock_message').remove();
				jQuery('#go_unlock_next_stage').remove();
			}
  	
			// if the button#go_button exists...
			if (jQuery('#go_button').length != 0){
				// set var 'task_status' to the value of the 'status' attribute on the current button#go_button.
				var task_status = jQuery('#go_button').attr('status');	
			// otherwise...
			} else {
				var task_status = 5;
			}
			
			// if 'target' (if an argument is sent to task_stage_change, it is stored as a parameter in the 'target' variable)
			// is assigned the value of jQuery('#go_back_button'), AND the div#new_content exists...
			if (jQuery(target).is('#go_back_button') && jQuery('#new_content').length != 0){
				// slowly hide any paragraph tags in the div#new_content.
				jQuery('#new_content p').hide('slow');
				// then remove the object 'target'.
				jQuery(target).remove();
			}
			
			// if the button#go_back_button has the attribute of repeat...
			if (jQuery('#go_back_button').attr('repeat') != 'on') {
				// set repeat_attr equal to the value of the attribute of button#go_button.
				var repeat_attr = jQuery('#go_button').attr('repeat');
			} else {
				// set repeat_attr equal to the value of the attribute of button#go_back_button.
				var repeat_attr = jQuery('#go_back_button').attr('repeat');
			}
			
			// send the following data to the function 'task_change_stage' and use the POST method to do so...
			// when it succeeds update the content of the page: update the admin bar; set the css display attribute to none for
			// div#new_content; then slowly display div#new_content; if the button#go_button 'status' attribute is equal to 2
			// and remove the first child element of div#new_content.
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: { 
					action: 'task_change_stage', 
					post_id: <?php echo $id; ?>, 
					user_id: <?php echo $user_ID; ?>,
					task_count: <?php 
									if ($task_count == null) {
										echo '0';
									} else {
										echo $task_count;
									}
								?>,
					status: task_status,
					repeat: repeat_attr,
					undo: jQuery(target).attr('undo'),
					page_id: <?php echo $page_id; ?>,
					admin_name: '<?php echo $admin_name; ?>',
					complete_lock: <?php if($complete_lock == true){echo 'true';} else{echo 'false';} ?>,
					mastery_lock: <?php if($mastery_lock == true){echo 'true';} else{echo 'false';}?>,
				},
				success: function(html){
					jQuery('#go_content').html(html);
					jQuery('#go_admin_bar_progress_bar').css({"background-color": color});
					jQuery("#new_content").css("display", "none");
					jQuery("#new_content").show('slow');
					if(jQuery('#go_button').attr('status') == 2){
						jQuery('#new_content').children().first().remove();	
					}
					if(jQuery('#go_unlock_next_stage').length != 0){
						jQuery('#go_button').attr('disabled', 'true');
						var typing_timer;
						var doneTyping = 500;
						jQuery('#go_unlock_next_stage').keyup(function (){
							typing_timer = setTimeout(task_unlock, doneTyping);
						});
						jQuery('#go_unlock_next_stage').keydown(function (){
							clearTimeout(typing_timer);
						});
					} 
				}
			});	
		}
	</script>
		
<?php
			echo $the_stage; // Just for Testing Purposes
			// this is an edit link.
			edit_post_link('Edit '.go_return_options('go_tasks_name'), '<br />
	<p>', '</p>', $id);
		} // Ends else statement
	} // Ends if statement
} // Ends function
add_shortcode('go_task','go_task_shortcode');

function unlock_stage(){
	global $wpdb;
	$password_check = $_POST['password_check'];
	$id = $_POST['task'];
	
	$custom_fields = get_post_custom($id);
	
	$user_ID = get_current_user_id();
	$go_table_ind = $wpdb->prefix.'go';
	$status = (int)$wpdb->get_var("SELECT `status` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
	
	if($status == 2){
		$password = sha1($custom_fields['go_mta_complete_unlock'][0]);
	} elseif($status == 3){
		$password = sha1($custom_fields['go_mta_mastery_unlock'][0]);
	}
	
	if($password_check == $password){
		echo 1;
	} else{
		echo 0;	
	}
	die();
}

function task_change_stage() {
	global $wpdb;
	$post_id = $_POST['post_id']; // Post id posted from ajax function
	$user_id = $_POST['user_id']; // User id posted from ajax function
	$status = $_POST['status']; // Task's status posted from ajax function
	$page_id = $_POST['page_id']; // Page id posted from ajax function
	$admin_name = $_POST['admin_name']; // Admin's display name posted from ajax function
	$undo = $_POST['undo']; // Boolean which determines if the button clicked is an undo button or not (True or False)
	$repeat_button = $_POST['repeat']; // Boolean which determines if the task is repeatable or not (True or False)
	$complete_lock = $_POST['complete_lock']; // Boolean which determines if the completion stage is locked or not (True or False)
	$mastery_lock = $_POST['mastery_lock']; // Boolean which determines if the mastery stage is locked or not (True or False)
	
	$go_table_ind = $wpdb->prefix.'go';
	$task_count = $wpdb->get_var("SELECT `count` FROM ".$go_table_ind." WHERE post_id = $post_id AND uid = $user_id");
	
	$custom_fields = get_post_custom($post_id); // Just gathering some data about this task with its post id
	$req_rank = $custom_fields['go_mta_req_rank'][0]; // Required Rank to accept Task
	$task_currency = $custom_fields['go_mta_task_currency'][0]; // Currency granted after each stage of task
	$task_points = $custom_fields['go_mta_task_points'][0]; // Points granted after each stage of task
	$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task
	if ($repeat == 'on' && $custom_fields['go_mta_repeat_amount'][0]){	// Checks if the task is repeatable and if it has a repeat limit
		$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page
	} elseif($repeat == 'on' && !$custom_fields['go_mta_repeat_amount']){ // Checks if the task is repeatable and if it does not have a repeat limit
		$repeat_amount = 0;	// Sets the limit equal to zero. In other words, unlimits the amount of times the task is repeatable
	}
	
	$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
	$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
	$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Mastery Message
	$description = $custom_fields['go_mta_quick_desc'][0]; // Description
	$currency_array = explode(',', $task_currency); // Makes an array out of currency values for each stage
	$points_array = explode(',', $task_points); //Makes an array out of currency values for each stage
	// Stage Stuff
	$content_post = get_post($post_id);
	$task_content = $content_post->post_content;
	if ($task_content == '') {
		$accpt_mssg = $custom_fields['go_mta_accept_message'][0]; // Completion Message
	} else {
		$accpt_mssg = $content_post->post_content;
	}
	$table_name_go = $wpdb->prefix . "go";
	
	// if the button pressed IS the repeat button...
	if ($repeat_button == 'on') {
		if ($undo == 'true' || $undo === true) {
			if ($task_count > 0) {
				go_add_post($user_id, $post_id, $status, -$points_array[$status-1], -$currency_array[$status-1], $page_id, $repeat_button, -1);
			} else {
				go_add_post($user_id, $post_id, ($status-1), -$points_array[$status-1], -$currency_array[$status-1], $page_id, $repeat_button, 0);
			}
		} else {
			// if repeat is on and undo is not hit...
			go_add_post($user_id, $post_id, $status, $points_array[$status-1], $currency_array[$status-1], $page_id, $repeat_button, 1);
		}	
	// if the button pressed is NOT the repeat button...
	} else {
		$db_status = (int) $wpdb->get_var("SELECT `status` FROM ".$table_name_go." WHERE uid = $user_id AND post_id = $post_id");
		if ($db_status == 0 || ($db_status < $status)) {
			if ($undo == 'true' || $undo === true) {
				if ($task_count > 0) {
					go_add_post($user_id, $post_id, $status, -$points_array[$status-1], -$currency_array[$status-1], $page_id, $repeat_button, -1);
				} else {
					go_add_post($user_id, $post_id, ($status-2), -$points_array[$status-2], -$currency_array[$status-2], $page_id, $repeat_button, 0);
				}
			} else {
				go_add_post($user_id, $post_id, $status, $points_array[$status-1], $currency_array[$status-1], $page_id, $repeat_button, 0); 
			}
		}
	}
	
	// redefine the status and task_count because they have been updated as soon as the above go_add_post() calls are made.
	$status = $wpdb->get_var("SELECT `status` FROM ".$table_name_go." WHERE uid = $user_id AND post_id = $post_id");
	$task_count = $wpdb->get_var("SELECT `count` FROM ".$go_table_ind." WHERE post_id = $post_id AND uid = $user_id");
	
	// The switch iterates through every value of status until it finds a value that matches a case.  So, if $status = 2, case 1 will
	// be skipped and case 2 will be output.  NOTE:  Without the 'break' statement after each case, the switch would recursively output
	// each case beyond the current value of $status.  Ex: if there are no 'break' statments in any of the cases and $status = 1, 
	// every case 1 will be output and so will ever case after it, until it hits the end of the switch.
	switch ($status) {
		case 1:
			echo '<div id="new_content">'.do_shortcode(wpautop($accpt_mssg, false)).
			' <button id="go_button" status="2" onclick="task_stage_change();this.disabled=true;">'
			.go_return_options('go_second_stage_button').'</button></div>';
			break;
		case 2:
			echo '<div id="new_content">'.do_shortcode(wpautop($accpt_mssg, false)).
			' <button id="go_button" status="3" onclick="task_stage_change();this.disabled=true;">'
			.go_return_options('go_third_stage_button').'</button> <button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button></div>';
			if($complete_lock == "true"){
				echo '<br/><div id="go_complete_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
				<input type="password" id="go_unlock_next_stage"/>';	
			}
			break;
		case 3:
			echo do_shortcode(wpautop($accpt_mssg, false)).'<div id="new_content">'
			.do_shortcode(wpautop($completion_message)).
			'<button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;">'
			.go_return_options('go_fourth_stage_button').'</button> <button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button></div>';
			if($mastery_lock == "true"){
				echo '<br/><div id="go_complete_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
				<input type="password" id="go_unlock_next_stage"/>';	
			}
			break;
		case 4:
			echo do_shortcode(wpautop($accpt_mssg, false)).do_shortcode(wpautop($completion_message)).
			'<div id="new_content">'.do_shortcode(wpautop($mastery_message));
			// if the task can be repeated...
			if ($repeat == 'on') {
				// if the number of times that the page has been repeated is less than the total amount of repeats allowed OR if the 
				// total repeats allowed is equal to zero (infinte amount allowed)...
				if ($task_count < $repeat_amount || $repeat_amount == 0) {
					echo '<div id="repeat_quest">
							<div id="go_repeat_clicked" style="display:none;">'
								.do_shortcode(wpautop($repeat_message)).
								'<button id="go_button" status="4" onclick="go_repeat_hide(jQuery(this));" repeat="on">'
									.go_return_options('go_fourth_stage_button')." Again".
								'</button>
								<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
							</div>
							<div id="go_repeat_unclicked">
								<button id="go_button" status="3" onclick="go_repeat_replace();">'
									.go_return_options('go_repeat_button').
								'</button>
								<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
							</div>
						</div>';
				// otherwise...
				} else {
					// display the undo (button#go_back_button) button and a pseudo-#go_button.
					echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>';
				}
				echo '</div>';
			// otherwise...
			} else {
				// display the back button.
				echo '<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button></div>';
			}
	}
die();
}
?>
