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
		$mastery_active = !$custom_fields['go_mta_task_mastery'][0]; // whether or not the mastery stage is active
		$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task
		
		$test_active = $custom_fields['go_mta_test_lock'][0];
		
		if ($test_active) {
			$test_num = $custom_fields['go_mta_test_lock_num'][0];
			
			$test_type_0 = $custom_fields['go_mta_test_lock_type_0'][0];
			$test_question_0 = $custom_fields['go_mta_test_lock_question_0'][0];
			$test_answers_0 = $custom_fields['go_mta_test_lock_answers_0'][0];
			$test_key_0 = $custom_fields['go_mta_test_lock_key_0'][0];
			
			if ($test_num > 1) {
					$test_all_types = array();
					$test_all_questions = array();
					$test_all_answers = array();
					$test_all_keys = array();
					array_push($test_all_types, $test_type_0);
					array_push($test_all_questions, $test_question_0);
					array_push($test_all_answers, $test_answers_0);
					array_push($test_all_keys, $test_key_0);
			}
			if ($test_num >= 2) {
				$test_type_1 = $custom_fields['go_mta_test_lock_type_1'][0];
				$test_question_1 = $custom_fields['go_mta_test_lock_question_1'][0];
				$test_answers_1 = $custom_fields['go_mta_test_lock_answers_1'][0];
				$test_key_1 = $custom_fields['go_mta_test_lock_key_1'][0];
				array_push($test_all_types, $test_type_1);
				array_push($test_all_questions, $test_question_1);
				array_push($test_all_answers, $test_answers_1);
				array_push($test_all_keys, $test_key_1);
			}
			if ($test_num >= 3) {
				$test_type_2 = $custom_fields['go_mta_test_lock_type_2'][0];
				$test_question_2 = $custom_fields['go_mta_test_lock_question_2'][0];
				$test_answers_2 = $custom_fields['go_mta_test_lock_answers_2'][0];
				$test_key_2 = $custom_fields['go_mta_test_lock_key_2'][0];
				array_push($test_all_types, $test_type_2);
				array_push($test_all_questions, $test_question_2);
				array_push($test_all_answers, $test_answers_2);
				array_push($test_all_keys, $test_key_2);
			}
			if ($test_num >= 4) {
				$test_type_3 = $custom_fields['go_mta_test_lock_type_3'][0];
				$test_question_3 = $custom_fields['go_mta_test_lock_question_3'][0];
				$test_answers_3 = $custom_fields['go_mta_test_lock_answers_3'][0];
				$test_key_3 = $custom_fields['go_mta_test_lock_key_3'][0];			
				array_push($test_all_types, $test_type_3);
				array_push($test_all_questions, $test_question_3);
				array_push($test_all_answers, $test_answers_3);
				array_push($test_all_keys, $test_key_3);
			}
			if ($test_num == 5) {
				$test_type_4 = $custom_fields['go_mta_test_lock_type_4'][0];
				$test_question_4 = $custom_fields['go_mta_test_lock_question_4'][0];
				$test_answers_4 = $custom_fields['go_mta_test_lock_answers_4'][0];
				$test_key_4 = $custom_fields['go_mta_test_lock_key_4'][0];			
				array_push($test_all_types, $test_type_4);
				array_push($test_all_questions, $test_question_4);
				array_push($test_all_answers, $test_answers_4);
				array_push($test_all_keys, $test_key_4);			
			}
		}
		
		if ($mastery_active) {
			$test_m_active = $custom_fields['go_mta_test_mastery_lock'][0];

			if ($test_m_active) {
				$test_m_num = $custom_fields['go_mta_test_mastery_lock_num'][0];
				
				$test_m_type_0 = $custom_fields['go_mta_test_mastery_lock_type_0'][0];
				$test_m_question_0 = $custom_fields['go_mta_test_mastery_lock_question_0'][0];
				$test_m_answers_0 = $custom_fields['go_mta_test_mastery_lock_answers_0'][0];
				$test_m_key_0 = $custom_fields['go_mta_test_mastery_lock_key_0'][0];
				
				if ($test_m_num > 1) {
						$test_m_all_types = array();
						$test_m_all_questions = array();
						$test_m_all_answers = array();
						$test_m_all_keys = array();
						array_push($test_m_all_types, $test_m_type_0);
						array_push($test_m_all_questions, $test_m_question_0);
						array_push($test_m_all_answers, $test_m_answers_0);
						array_push($test_m_all_keys, $test_m_key_0);
				}
				if ($test_m_num >= 2) {
					$test_m_type_1 = $custom_fields['go_mta_test_mastery_lock_type_1'][0];
					$test_m_question_1 = $custom_fields['go_mta_test_mastery_lock_question_1'][0];
					$test_m_answers_1 = $custom_fields['go_mta_test_mastery_lock_answers_1'][0];
					$test_m_key_1 = $custom_fields['go_mta_test_mastery_lock_key_1'][0];
					array_push($test_m_all_types, $test_m_type_1);
					array_push($test_m_all_questions, $test_m_question_1);
					array_push($test_m_all_answers, $test_m_answers_1);
					array_push($test_m_all_keys, $test_m_key_1);
				}
				if ($test_m_num >= 3) {
					$test_m_type_2 = $custom_fields['go_mta_test_mastery_lock_type_2'][0];
					$test_m_question_2 = $custom_fields['go_mta_test_mastery_lock_question_2'][0];
					$test_m_answers_2 = $custom_fields['go_mta_test_mastery_lock_answers_2'][0];
					$test_m_key_2 = $custom_fields['go_mta_test_mastery_lock_key_2'][0];
					array_push($test_m_all_types, $test_m_type_2);
					array_push($test_m_all_questions, $test_m_question_2);
					array_push($test_m_all_answers, $test_m_answers_2);
					array_push($test_m_all_keys, $test_m_key_2);
				}
				if ($test_m_num >= 4) {
					$test_m_type_3 = $custom_fields['go_mta_test_mastery_lock_type_3'][0];
					$test_m_question_3 = $custom_fields['go_mta_test_mastery_lock_question_3'][0];
					$test_m_answers_3 = $custom_fields['go_mta_test_mastery_lock_answers_3'][0];
					$test_m_key_3 = $custom_fields['go_mta_test_mastery_lock_key_3'][0];			
					array_push($test_m_all_types, $test_m_type_3);
					array_push($test_m_all_questions, $test_m_question_3);
					array_push($test_m_all_answers, $test_m_answers_3);
					array_push($test_m_all_keys, $test_m_key_3);
				}
				if ($test_m_num == 5) {
					$test_m_type_4 = $custom_fields['go_mta_test_mastery_lock_type_4'][0];
					$test_m_question_4 = $custom_fields['go_mta_test_mastery_lock_question_4'][0];
					$test_m_answers_4 = $custom_fields['go_mta_test_mastery_lock_answers_4'][0];
					$test_m_key_4 = $custom_fields['go_mta_test_mastery_lock_key_4'][0];			
					array_push($test_m_all_types, $test_m_type_4);
					array_push($test_m_all_questions, $test_m_question_4);
					array_push($test_m_all_answers, $test_m_answers_4);
					array_push($test_m_all_keys, $test_m_key_4);			
				}
			}
			$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
			$mastery_upload = $custom_fields['go_mta_mastery_upload'][0];
			$mastery_locked = $custom_fields['go_mta_mastery_lock'][0];
			$mastery_unlock = $custom_fields['go_mta_mastery_unlock'][0];
			if ($mastery_locked == 'on' && $mastery_unlock != ' ') {
				$mastery_lock = true; 
			} else {
				$mastery_lock = false;
			}

			if ($repeat == 'on' && $custom_fields['go_mta_repeat_amount'][0]){	// Checks if the task is repeatable and if it has a repeat limit
				$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page
			} elseif($repeat == 'on' && !$custom_fields['go_mta_repeat_amount']){ // Checks if the task is repeatable and if it does not have a repeat limit
				$repeat_amount = 0;	// Sets the limit equal to zero. In other words, unlimits the amount of times the task is repeatable
			}

			$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Repeat Message
		}

/*
		if ($repeat == 'on' && $custom_fields['go_mta_repeat_amount'][0]){	// Checks if the task is repeatable and if it has a repeat limit
			$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page
		} elseif($repeat == 'on' && !$custom_fields['go_mta_repeat_amount']){ // Checks if the task is repeatable and if it does not have a repeat limit
			$repeat_amount = 0;	// Sets the limit equal to zero. In other words, unlimits the amount of times the task is repeatable
		}
*/		
		if($custom_fields['go_mta_time_filter'][0]){ // Checks if the task has a time filter
			$minutes_required = $custom_fields['go_mta_time_filter'][0]; // Sets the filter equal to the meta field value declared in the task creation page
		}
		
		$completion_upload = $custom_fields['go_mta_completion_upload'][0];
		$complete_locked = $custom_fields['go_mta_complete_lock'][0]; // Sets this variable equal to the password entered on the task creation page
		$complete_unlock = $custom_fields['go_mta_complete_unlock'][0];
		if ($complete_locked == 'on' && $complete_unlock != ' ') {
			$complete_lock = true;
		} else {
			$complete_lock = false;
		}

		if($custom_fields['go_mta_focus_category_lock'][0]){
			$focus_category_lock = true;
		}
		
		$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
		
		//$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
		//$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Repeat Message
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
			
			if(get_the_terms($id, 'task_focus_categories') && $focus_category_lock){
				$categories = get_the_terms($id, 'task_focus_categories');
				$category_names = array();
				foreach($categories as $category){
					array_push($category_names, $category->name);	
				}
			}
			
			if(get_user_meta($user_ID, 'go_focus', true) != ''){
				$user_focus = (array) get_user_meta($user_ID, 'go_focus', true);	
			}
			
			if($category_names && $user_focus){
				$go_ahead = array_intersect($user_focus, $category_names);	
			}
			
?> 

			<div id="go_description"> <?php echo  do_shortcode(wpautop($description));?> </div>
            
<?php
		if($go_ahead || !isset($focus_category_lock) || empty($category_names)){
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
						echo '<div id="go_content">'.do_shortcode(wpautop($accpt_mssg));
						if ($test_active) {
							if (preg_match("/('|\")+/", $test_question_0) || preg_match("/('|\")+/", $test_answers_0) || preg_match("/('|\")+/", $test_key_0)) {
								if (current_user_can('manage_options')) {
									echo "<span style='color:red'><b>ERROR: Please make sure that there are no appostrophes (' or  \")in any of the provided fields.</b></span><br/>";
								}
							} else {
								if ($test_num > 1) {
									for ($i = 0; $i < $test_num; $i++) {
										echo do_shortcode("[go_test type='".$test_all_types[$i]."' question='".$test_all_questions[$i]."' possible_answers='".$test_all_answers[$i]."' key='".$test_all_keys[$i]."' test_id='".$i."' total_num='".$test_num."']");
									}
									echo "<button class='go_test_submit' style='margin-top: -10px; margin-left: 40px;'>GO!</button><br/><br/>";
								} else {
									echo do_shortcode("[go_test type='".$test_type_0."' question='".$test_question_0."' possible_answers='".$test_answers_0."' key='".$test_key_0."' test_id='0']");
								}
							}
						}

						if ($completion_upload) {
							echo do_shortcode("[go_upload]")."<br/>";
						}

						echo '<button id="go_button" status="3" onclick="task_stage_change();this.disabled=true;">'.
						go_return_options('go_third_stage_button').'</button>
						<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
						</div>';
						
						if($complete_lock == true || $complete_lock == 'true'){
							echo '<br/><div id="go_complete_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
							<input type="password" id="go_unlock_next_stage"/>';
						}
					break;
					
					// Completed
					case 3: 
						echo '<div id="go_content">'. do_shortcode(wpautop($accpt_mssg)).'
						'.do_shortcode(wpautop($completion_message));
						if ($mastery_active) {
							if ($test_m_active) {
								if (preg_match("/('|\")+/", $test_m_question_0) || preg_match("/('|\")+/", $test_m_answers_0) || preg_match("/('|\")+/", $test_m_key_0)) {
									if (current_user_can('manage_options')) {
										echo "<span style='color:red'><b>ERROR: Please make sure that there are no appostrophes (' or  \")in any of the provided fields.</b></span><br/>";
									}
								} else {
									if ($test_m_num > 1) {
										for ($i = 0; $i < $test_m_num; $i++) {
											echo do_shortcode("[go_test type='".$test_m_all_types[$i]."' question='".$test_m_all_questions[$i]."' possible_answers='".$test_m_all_answers[$i]."' key='".$test_m_all_keys[$i]."' test_id='".$i."' total_num='".$test_m_num."']");
										}
										echo "<button class='go_test_submit' style='margin-top: -10px; margin-left: 40px;'>GO!</button><br/><br/>";
									} else {
										echo do_shortcode("[go_test type='".$test_m_type_0."' question='".$test_m_question_0."' possible_answers='".$test_m_answers_0."' key='".$test_m_key_0."' test_id='0']");
									}
								}
							}
						
							if ($mastery_upload) {
								echo do_shortcode("[go_upload]")."<br/>";
							}

							echo '<button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;">'.
							go_return_options('go_fourth_stage_button').'</button> 
							<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
							</div>';
							
							if($mastery_lock){
								echo '<br/><div id="go_mastery_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
								<input type="password" id="go_unlock_next_stage"/>';	
							}
						} else {
							echo '<span id="go_button" status="4" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
								</div>';
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
			if(get_post_type() == 'tasks'){
				comments_template();
			}
		} else{ // If user can't access quest because they aren't part of the specialty, echo this
			$category_name = implode(',',$category_names);
			echo 'This task is only available to '.$category_name;
		}
		
?>
	<script language="javascript">
		jQuery(document).ready(function() {
			jQuery.ajaxSetup({ 
				url: '<?php echo get_site_url() ?>/wp-admin/admin-ajax.php'
			});
			check_locks();
		});
		
		function check_locks() {
			if (jQuery('#go_unlock_next_stage').length != 0 && jQuery(".go_test_list").length != 0) {
				jQuery('#go_button').attr('disabled', 'true');
				var typing_timer;
				var doneTyping = 500;
				jQuery('.go_test_submit').click(function() {
						task_unlock();
				});
				jQuery('#go_unlock_next_stage').keyup(function (){
					typing_timer = setTimeout(function() {
							task_unlock();
					}, doneTyping);
				});
				jQuery('#go_unlock_next_stage').keydown(function (){
					clearTimeout(typing_timer);
				});
			} else if (jQuery('#go_unlock_next_stage').length != 0){
				jQuery('#go_button').attr('disabled', 'true');
				var typing_timer;
				var doneTyping = 500;
				jQuery('#go_unlock_next_stage').keyup(function (){
					typing_timer = setTimeout(task_unlock, doneTyping);
				});
				jQuery('#go_unlock_next_stage').keydown(function (){
					clearTimeout(typing_timer);
				});
			} else if (jQuery(".go_test_list").length != 0) {
				jQuery('#go_button').attr('disabled', 'true');
				jQuery('.go_test_submit').click(function() {
					var test_list = jQuery(".go_test_list");
					if (test_list.length > 1) {
						var checked_ans = 0;
						for (var i = 0; i < test_list.length; i++) {
							var type = test_list[i].children[1].children[0].type;

							var obj_str = "#"+test_list[i].id+" :checked";
							var chosen_answers = jQuery(obj_str);

							if (type == 'radio') {
								if (chosen_answers.length == 1) {
									checked_ans++;
								} else { 
									jQuery('#go_test_error_msg').text("Please answer all questions!");
								}
							} else {
								if (chosen_answers.length >= 2) {
									checked_ans++;
								} else { 
									jQuery('#go_test_error_msg').text("Please choose at least two answers!");
								}
							}
						}

						if (checked_ans >= test_list.length) {
							task_unlock();
						}
					} else {
						var type = jQuery('.go_test_list li input').attr("type");
						if (type == 'radio') {
							if (jQuery(".go_test_list input:checked").length == 1) {
								task_unlock();
							} else {
								jQuery('#go_test_error_msg').text("Please choose an answer!");
							}
						} else {
							if (jQuery(".go_test_list input:checked").length > 1) {
								task_unlock();
							} else { 
								jQuery('#go_test_error_msg').text("Please choose at least two answers!");
							}
						}
					}
				});
			} 

			if (jQuery('#go_upload_form').length != 0) {
				jQuery('#go_button').attr('disabled', 'true');
				jQuery('#go_upload_submit').click(function() {
					task_unlock();
				});
			}
		}

		function task_unlock() {
			if (jQuery('#go_unlock_next_stage').length != 0 && jQuery(".go_test_list").length != 0) {
				if (jQuery('.go_test_list :checked').length != 0) {
						var test_list = jQuery(".go_test_list");
						var list_size = test_list.length;
						var type_array = [];
						
						if (jQuery(".go_test_list").length > 1) {
						
							var choice_array = [];

							for (var x = 0; x < list_size; x++) {
								
								// figure out the type of each test
								var test_type = test_list[x].children[1].children[0].type;
								type_array.push(test_type);

								// get the checked inputs of each test
								var obj_str = "#"+test_list[x].id+" :checked";
								var chosen_answers = jQuery(obj_str);

								if (test_type == 'radio') {
									// push indiviudal answers to the choice_array
									choice_array.push(chosen_answers[0].value);
								} else if (test_type == 'checkbox') {
									var t_array = [];
									for (var i = 0; i < chosen_answers.length; i++) {
										t_array.push(chosen_answers[i].value);
									}
									var choice_str = t_array.join("### ");
									choice_array.push(choice_str);
								}	
							}
							var choice = choice_array.join("#### ");
							var type = type_array.join("### ");
						} else {
							var chosen_answer = jQuery('.go_test_list li input:checked');
							var type = jQuery('.go_test_list li input').first().attr("type");
							if (type == 'radio') {
								var choice = chosen_answer[0].value;
							} else if (type == 'checkbox') {
								var choice = [];
								for (var i = 0; i < chosen_answer.length; i++) {
									choice.push(chosen_answer[i].value);	
								}
								choice = choice.join("### ");
							}
						}
					var pwd_check = String(jQuery('#go_unlock_next_stage').val());
					var pwd_hash = CryptoJS.SHA1(pwd_check).toString();
					var which = 'both';

				} else {
					jQuery('#go_test_error_msg').text("Choose an answer!");
				}
			} else {
				if (jQuery('#go_unlock_next_stage').length != 0) {
					var pwd_check = String(jQuery('#go_unlock_next_stage').val());
					var pwd_hash = CryptoJS.SHA1(pwd_check).toString();
					var which = 'pass';
				} else if (jQuery(".go_test_list").length != 0) {
					if (jQuery('.go_test_list :checked').length != 0) {
						
						var test_list = jQuery(".go_test_list");
						var list_size = test_list.length;
						var type_array = [];
						
						if (jQuery(".go_test_list").length > 1) {
						
							var choice_array = [];

							for (var x = 0; x < list_size; x++) {
								
								// figure out the type of each test
								var test_type = test_list[x].children[1].children[0].type;
								type_array.push(test_type);

								// get the checked inputs of each test
								var obj_str = "#"+test_list[x].id+" :checked";
								var chosen_answers = jQuery(obj_str);

								if (test_type == 'radio') {
									// push indiviudal answers to the choice_array
									choice_array.push(chosen_answers[0].value);
								} else if (test_type == 'checkbox') {
									var t_array = [];
									for (var i = 0; i < chosen_answers.length; i++) {
										t_array.push(chosen_answers[i].value);
									}
									var choice_str = t_array.join("### ");
									choice_array.push(choice_str);
								}	
							}
							var choice = choice_array.join("#### ");
							var type = type_array.join("### ");
						} else {
							var chosen_answer = jQuery('.go_test_list li input:checked');
							var type = jQuery('.go_test_list li input').first().attr("type");
							if (type == 'radio') {
								var choice = chosen_answer[0].value;
							} else if (type == 'checkbox') {
								var choice = [];
								for (var i = 0; i < chosen_answer.length; i++) {
									choice.push(chosen_answer[i].value);	
								}
								choice = choice.join("### ");
							}
						}
						var which = 'test';
					} else {
						jQuery('#go_test_error_msg').text("Choose an answer!");
					}
				}
			} 
			var status = jQuery('#go_button').attr("status");
			jQuery.ajax({
				type: "POST",
				data:{
					action: 'unlock_stage',
					password_check: pwd_hash,
					task: <?php echo $id; ?>,
					list_size: list_size,
					chosen_answer: choice,
					type: type,
					status: status,
					which: which,
				},
				success: function(response){
					if(response == 1 || response == '1'){
						jQuery('#go_button').removeAttr('disabled');
						if (which == 'both') {
							jQuery('.go_lock_message').html('Password correct, move on.');
							jQuery('#go_unlock_next_stage').remove();
							jQuery('.go_test_container').hide('slow');
							if (list_size > 1) {
								jQuery('.go_test_submit').hide('slow');	
							}
							jQuery('#go_button').removeAttr('disabled');
							jQuery('#go_test_error_msg').attr('style', 'color:green');
							jQuery('#go_test_error_msg').text("Well done, continue!");
							//test_point_update();
						} else if (which == 'pass') {	
							jQuery('.go_lock_message').html('Password correct, move on.');
							jQuery('#go_unlock_next_stage').remove();	
						} else if (which == 'test') {
							jQuery('.go_test_container').hide('slow');
							if (list_size > 1) {
								jQuery('.go_test_submit').hide('slow');	
							}
							jQuery('#go_button').removeAttr('disabled');
							jQuery('#go_test_error_msg').attr('style', 'color:green');
							jQuery('#go_test_error_msg').text("Well done, continue!");
						}
					} else {
						if (which == 'both') {
							jQuery('.go_lock_message').html('Incorrect password, try again.');
							jQuery('#go_test_error_msg').text(response);
						} else if (which == 'pass'){
							jQuery('.go_lock_message').html('Incorrect password, try again.');
						} else if (which == 'test') {
							jQuery('#go_test_error_msg').text("Wrong answer, try again!");
						}
					}
				}
			});
		}
		
		/*  For when the checks for understanding need to provide bonuses with diminishing returns.
		function test_point_update() {
			jQuery.ajax({
				type: "POST",
				data: {
					action: "test_point_update",
					points: <?php echo $points_array[0]; ?>,
					status: <?php echo $status; ?>,
					user_ID: <?php echo $user_ID; ?>,
				},
				success: function () {
					console.log("test_point_update success");
				}
			});
		}
		*/
		
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
				type: "POST",
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
					complete_lock: <?php if($complete_lock){echo 'true';} else{echo 'false';} ?>,
					mastery_lock: <?php if($mastery_lock){echo 'true';} else{echo 'false';}?>,
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
					
					jQuery('#go_button').ready(function() {
						check_locks();
					});
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

/*  For checks for understanding... See the declaration of the test_point_update function in the script tag above.
function test_point_update() {
	$status = $_POST['status'];
	$user_id = $_POST['user_ID'];
	$points = $_POST['points'];
	go_update_totals($user_id, $points, 0, 0, $status);
	//go_add_post($user_ID, $id, 0, $points_array[0], $currency_array[0], $page_id, null, 0);
}
*/

function unlock_stage(){
	global $wpdb;

	$id = $_POST['task'];
	$status = $_POST['status'] - 1;
	$which = $_POST['which'];
	$test_size = $_POST['list_size'];
	
	if ($which == 'both') {
		$password_check = $_POST['password_check'];
		$choice = $_POST['chosen_answer'];
		$type = $_POST['type'];
		if ($test_size > 1) {
			$all_test_choices = explode("#### ", $choice);
			$type_array = explode("### ", $type);
		} else {
			if ($type == 'checkbox') {
				$choice_array = explode("### ", $choice);
			}
		}
	} else if ($which == 'pass') {
		$password_check = $_POST['password_check'];
	} else if ($which == 'test') {
		$choice = $_POST['chosen_answer'];
		$type = $_POST['type'];
		if ($test_size > 1) {
			$all_test_choices = explode("#### ", $choice);
			$type_array = explode("### ", $type);
		} else {
			if ($type == 'checkbox') {
				$choice_array = explode("### ", $choice);
			}
		}
	}
	
	if ($type == 'checkbox') {
		$choice_array_keys = array_keys($choice_array);
		if (count($choice_array_keys) < 2) {
			echo 0;
			die();
		}
	}
	
	$custom_fields = get_post_custom($id);
	$task_points = $custom_fields['go_mta_task_points'][0];
	$points_array = explode(',', $task_points);
	$task_currency = $custom_fields['go_mta_task_currency'][0];
	$currency_array = explode(',', $task_currency);
	$page_id = get_the_ID();
	
	$user_ID = get_current_user_id();
	
	if ($status == 2) {
		$password = sha1($custom_fields['go_mta_complete_unlock'][0]);
		$key = $custom_fields['go_mta_test_lock_key_0'][0];
		if ($test_size > 1) {
			$key_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $key);

			$all_keys_array = array($key_parsed);
			$test_key_1 = $custom_fields['go_mta_test_lock_key_1'][0];
			$test_key_1_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_1);
			array_push($all_keys_array, $test_key_1_parsed);
		}
		if ($test_size > 2) {
			$test_key_2 = $custom_fields['go_mta_test_lock_key_2'][0];
			$test_key_2_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_2);
			array_push($all_keys_array, $test_key_2_parsed);
		}
		if ($test_size > 3) {
			$test_key_3 = $custom_fields['go_mta_test_lock_key_3'][0];
			$test_key_3_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_3);
			array_push($all_keys_array, $test_key_3_parsed);
		}
		if ($test_size > 4) {
			$test_key_4 = $custom_fields['go_mta_test_lock_key_4'][0];
			$test_key_4_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_4);
			array_push($all_keys_array, $test_key_4_parsed);
		}
	} else if ($status == 3) {
		$password = sha1($custom_fields['go_mta_mastery_unlock'][0]);
		$key = $custom_fields['go_mta_test_mastery_lock_key_0'][0];
		if ($test_size > 1) {
			$key_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $key);

			$all_keys_array = array($key_parsed);
			$test_key_1 = $custom_fields['go_mta_test_mastery_lock_key_1'][0];
			$test_key_1_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_1);
			array_push($all_keys_array, $test_key_1_parsed);
		}
		if ($test_size > 2) {
			$test_key_2 = $custom_fields['go_mta_test_mastery_lock_key_2'][0];
			$test_key_2_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_2);
			array_push($all_keys_array, $test_key_2_parsed);
		}
		if ($test_size > 3) {
			$test_key_3 = $custom_fields['go_mta_test_mastery_lock_key_3'][0];
			$test_key_3_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_3);
			array_push($all_keys_array, $test_key_3_parsed);
		}
		if ($test_size > 4) {
			$test_key_4 = $custom_fields['go_mta_test_mastery_lock_key_4'][0];
			$test_key_4_parsed = preg_replace("/\s*\#\#\#\s*/", "### ", $test_key_4);
			array_push($all_keys_array, $test_key_4_parsed);
		}
	}
	
	if ($type == 'checkbox' && !($list_size > 1)) {
		$key_str = preg_replace("/\s*\#\#\#\s*/", "### ", $key);
		$key_array = explode("### ", $key_str);
	}


	if ($which == 'both') {
		if ($test_size > 1) {
			$total_matches = 0;
			for ($i = 0; $i < $test_size; $i++) {
				if ($type_array[$i] == 'radio') {
					if (strtolower($all_keys_array[$i]) == strtolower($all_test_choices[$i])) {
						$total_matches++;
					}					
				} else {
					$k_array = explode("### ", $all_keys_array[$i]);
					$c_array = explode("### ", $all_test_choices[$i]);
					$match_count = 0;
					for ($x = 0; $x < count($c_array); $x++) {
						if (strtolower($c_array[$x]) == strtolower($k_array[$x])) {
							$match_count++;
						}
					}

					if ($match_count == count($k_array)) {
						$total_matches++;
					}
				}
			}
			if ($total_matches == $test_size && $password_check == $password) {
				echo 1;
			} else {
				if ($total_matches != $test_size && $password_check != $password) {
					echo "Wrong answer AND incorrect password!";
					die();
				} else { 
			
					if ($password_check != $password) {
						echo "Incorrect password!";
					}
				
					if ($total_matches != $test_size) {
						echo "Wrong answer!";
					}
					die();
				}
			}
		} else {

			if ($type == 'radio') {
				if (strtolower($choice) == strtolower($key) && $password_check == $password) {
					echo 1;
				} else {
					if (strtolower($choice) != strtolower($key) && $password_check != $password) {
						echo "Wrong answer AND incorrect password!";
						die();
					} else { 
				
						if ($password_check != $password) {
							echo "Incorrect password!";
						}
					
						if (strtolower($choice) != strtolower($key)) {
							echo "Wrong answer!";
						}
						die();
					}
				}

			} else if ($type == 'checkbox') {
				$key_match = 0;
				$key_array_keys = array_keys($key_array);
				$choice_array_keys = array_keys($choice_array);
				for ($i = 0; $i < count($key_array_keys); $i++) {
					for ($x = 0; $x < count($choice_array_keys);  $x++) {
						if (strtolower($choice_array[$x]) == strtolower($key_array[$i])) {
							$key_match++;
							break;
						}
					}
				}

				if ($key_match == count($choice_array_keys) && $key_match >= 2 && $password_check == $password) {
					echo 1;
				} else {
					if ($key_match != count($choice_array_keys) && $key_match < 2 && $password_check != $password) {
						echo "Wrong answer AND incorrect password!";
						die();
					} else { 
				
						if ($password_check != $password) {
							echo "Incorrect password!";
						}
					
						if ($key_match != count($choice_array_keys) || $key_match < 2) {
							echo "Wrong answer!";
						}
						die();
					}
				}
			}
		}


	} else if ($which == 'pass') {
		if($password_check == $password){
			echo 1;
			die();
		} else {
			echo 0;
			die();
		}
	} else if ($which == 'test') {
		if ($test_size > 1) {
			$total_matches = 0;
			for ($i = 0; $i < $test_size; $i++) {
				if ($type_array[$i] == 'radio') {
					if (strtolower($all_keys_array[$i]) == strtolower($all_test_choices[$i])) {
						$total_matches++;
					} else {
						echo 0;
						die();
					}					
				} else {
					$k_array = explode("### ", $all_keys_array[$i]);
					$c_array = explode("### ", $all_test_choices[$i]);
					$match_count = 0;
					for ($x = 0; $x < count($c_array); $x++) {
						if (strtolower($c_array[$x]) == strtolower($k_array[$x])) {
							$match_count++;
						} else {
							echo 0;
							die();
						}
					}

					if ($match_count == count($k_array)) {
						$total_matches++;
					} else {
						echo 0;
						die();
					}
				}
			}

			if ($total_matches == $test_size) {
				echo 1;
				die();
			} else {
				echo 0;
				die();
			}
		} else {

			if ($type == 'radio') {
				if (strtolower($choice) == strtolower($key)) {
					echo 1;
					die();
				} else {
					echo 0;
					die();
				}
			} else if ($type == 'checkbox') {
				$key_match = 0;
				$key_array_keys = array_keys($key_array);
				$choice_array_keys = array_keys($choice_array);
				for ($i = 0; $i < count($key_array_keys); $i++) {
					for ($x = 0; $x < count($choice_array_keys);  $x++) {
						if (strtolower($choice_array[$x]) == strtolower($key_array[$i])) {
							$key_match++;
							break;
						}
					}
				}
				if ($key_match == count($choice_array_keys) && $key_match >= 2) {
					echo 1;
					die();
				} else {
					echo 0;
					die();
				}
			}
		}
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
	$mastery_active = !$custom_fields['go_mta_task_mastery'][0]; // whether or not the mastery stage is active
	$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task

	$test_active = $custom_fields['go_mta_test_lock'][0];
	
	if ($test_active) {
		$test_num = $custom_fields['go_mta_test_lock_num'][0];
		
		$test_type_0 = $custom_fields['go_mta_test_lock_type_0'][0];
		$test_question_0 = $custom_fields['go_mta_test_lock_question_0'][0];
		$test_answers_0 = $custom_fields['go_mta_test_lock_answers_0'][0];
		$test_key_0 = $custom_fields['go_mta_test_lock_key_0'][0];
		
		if ($test_num > 1) {
				$test_all_types = array();
				$test_all_questions = array();
				$test_all_answers = array();
				$test_all_keys = array();
				array_push($test_all_types, $test_type_0);
				array_push($test_all_questions, $test_question_0);
				array_push($test_all_answers, $test_answers_0);
				array_push($test_all_keys, $test_key_0);
		}
		if ($test_num >= 2) {
			$test_type_1 = $custom_fields['go_mta_test_lock_type_1'][0];
			$test_question_1 = $custom_fields['go_mta_test_lock_question_1'][0];
			$test_answers_1 = $custom_fields['go_mta_test_lock_answers_1'][0];
			$test_key_1 = $custom_fields['go_mta_test_lock_key_1'][0];
			array_push($test_all_types, $test_type_1);
			array_push($test_all_questions, $test_question_1);
			array_push($test_all_answers, $test_answers_1);
			array_push($test_all_keys, $test_key_1);
		}
		if ($test_num >= 3) {
			$test_type_2 = $custom_fields['go_mta_test_lock_type_2'][0];
			$test_question_2 = $custom_fields['go_mta_test_lock_question_2'][0];
			$test_answers_2 = $custom_fields['go_mta_test_lock_answers_2'][0];
			$test_key_2 = $custom_fields['go_mta_test_lock_key_2'][0];
			array_push($test_all_types, $test_type_2);
			array_push($test_all_questions, $test_question_2);
			array_push($test_all_answers, $test_answers_2);
			array_push($test_all_keys, $test_key_2);
		}
		if ($test_num >= 4) {
			$test_type_3 = $custom_fields['go_mta_test_lock_type_3'][0];
			$test_question_3 = $custom_fields['go_mta_test_lock_question_3'][0];
			$test_answers_3 = $custom_fields['go_mta_test_lock_answers_3'][0];
			$test_key_3 = $custom_fields['go_mta_test_lock_key_3'][0];			
			array_push($test_all_types, $test_type_3);
			array_push($test_all_questions, $test_question_3);
			array_push($test_all_answers, $test_answers_3);
			array_push($test_all_keys, $test_key_3);
		}
		if ($test_num == 5) {
			$test_type_4 = $custom_fields['go_mta_test_lock_type_4'][0];
			$test_question_4 = $custom_fields['go_mta_test_lock_question_4'][0];
			$test_answers_4 = $custom_fields['go_mta_test_lock_answers_4'][0];
			$test_key_4 = $custom_fields['go_mta_test_lock_key_4'][0];			
			array_push($test_all_types, $test_type_4);
			array_push($test_all_questions, $test_question_4);
			array_push($test_all_answers, $test_answers_4);
			array_push($test_all_keys, $test_key_4);			
		}
	}

	if ($mastery_active) {
			$test_m_active = $custom_fields['go_mta_test_mastery_lock'][0];

			if ($test_m_active) {
				$test_m_num = $custom_fields['go_mta_test_mastery_lock_num'][0];
				
				$test_m_type_0 = $custom_fields['go_mta_test_mastery_lock_type_0'][0];
				$test_m_question_0 = $custom_fields['go_mta_test_mastery_lock_question_0'][0];
				$test_m_answers_0 = $custom_fields['go_mta_test_mastery_lock_answers_0'][0];
				$test_m_key_0 = $custom_fields['go_mta_test_mastery_lock_key_0'][0];
				
				if ($test_m_num > 1) {
						$test_m_all_types = array();
						$test_m_all_questions = array();
						$test_m_all_answers = array();
						$test_m_all_keys = array();
						array_push($test_m_all_types, $test_m_type_0);
						array_push($test_m_all_questions, $test_m_question_0);
						array_push($test_m_all_answers, $test_m_answers_0);
						array_push($test_m_all_keys, $test_m_key_0);
				}
				if ($test_m_num >= 2) {
					$test_m_type_1 = $custom_fields['go_mta_test_mastery_lock_type_1'][0];
					$test_m_question_1 = $custom_fields['go_mta_test_mastery_lock_question_1'][0];
					$test_m_answers_1 = $custom_fields['go_mta_test_mastery_lock_answers_1'][0];
					$test_m_key_1 = $custom_fields['go_mta_test_mastery_lock_key_1'][0];
					array_push($test_m_all_types, $test_m_type_1);
					array_push($test_m_all_questions, $test_m_question_1);
					array_push($test_m_all_answers, $test_m_answers_1);
					array_push($test_m_all_keys, $test_m_key_1);
				}
				if ($test_m_num >= 3) {
					$test_m_type_2 = $custom_fields['go_mta_test_mastery_lock_type_2'][0];
					$test_m_question_2 = $custom_fields['go_mta_test_mastery_lock_question_2'][0];
					$test_m_answers_2 = $custom_fields['go_mta_test_mastery_lock_answers_2'][0];
					$test_m_key_2 = $custom_fields['go_mta_test_mastery_lock_key_2'][0];
					array_push($test_m_all_types, $test_m_type_2);
					array_push($test_m_all_questions, $test_m_question_2);
					array_push($test_m_all_answers, $test_m_answers_2);
					array_push($test_m_all_keys, $test_m_key_2);
				}
				if ($test_m_num >= 4) {
					$test_m_type_3 = $custom_fields['go_mta_test_mastery_lock_type_3'][0];
					$test_m_question_3 = $custom_fields['go_mta_test_mastery_lock_question_3'][0];
					$test_m_answers_3 = $custom_fields['go_mta_test_mastery_lock_answers_3'][0];
					$test_m_key_3 = $custom_fields['go_mta_test_mastery_lock_key_3'][0];			
					array_push($test_m_all_types, $test_m_type_3);
					array_push($test_m_all_questions, $test_m_question_3);
					array_push($test_m_all_answers, $test_m_answers_3);
					array_push($test_m_all_keys, $test_m_key_3);
				}
				if ($test_m_num == 5) {
					$test_m_type_4 = $custom_fields['go_mta_test_mastery_lock_type_4'][0];
					$test_m_question_4 = $custom_fields['go_mta_test_mastery_lock_question_4'][0];
					$test_m_answers_4 = $custom_fields['go_mta_test_mastery_lock_answers_4'][0];
					$test_m_key_4 = $custom_fields['go_mta_test_mastery_lock_key_4'][0];			
					array_push($test_m_all_types, $test_m_type_4);
					array_push($test_m_all_questions, $test_m_question_4);
					array_push($test_m_all_answers, $test_m_answers_4);
					array_push($test_m_all_keys, $test_m_key_4);			
				}
			}
			$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
			$mastery_upload = $custom_fields['go_mta_mastery_upload'][0];
			if ($repeat == 'on' && $custom_fields['go_mta_repeat_amount'][0]){	// Checks if the task is repeatable and if it has a repeat limit
				$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page
			} elseif($repeat == 'on' && !$custom_fields['go_mta_repeat_amount']){ // Checks if the task is repeatable and if it does not have a repeat limit
				$repeat_amount = 0;	// Sets the limit equal to zero. In other words, unlimits the amount of times the task is repeatable
			}

			$repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Repeat Message
		}

/*
	if ($repeat == 'on' && $custom_fields['go_mta_repeat_amount'][0]){	// Checks if the task is repeatable and if it has a repeat limit
		$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page
	} elseif($repeat == 'on' && !$custom_fields['go_mta_repeat_amount']){ // Checks if the task is repeatable and if it does not have a repeat limit
		$repeat_amount = 0;	// Sets the limit equal to zero. In other words, unlimits the amount of times the task is repeatable
	}
*/	
	$completion_upload = $custom_fields['go_mta_completion_upload'][0];
	$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
	$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
	// $repeat_message = $custom_fields['go_mta_repeat_message'][0]; // Mastery Message
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
			echo '<div id="new_content">'.do_shortcode(wpautop($accpt_mssg, false));
			if ($test_active) {
				if (preg_match("/('|\")+/", $test_question_0) || preg_match("/('|\")+/", $test_answers_0) || preg_match("/('|\")+/", $test_key_0)) {
					if (current_user_can('manage_options')) {
						echo "<span style='color:red'><b>ERROR: Please make sure that there are no appostrophes (' or  \")in any of the provided fields.</b></span><br/>";
					}
				} else {
					if ($test_num > 1) {
						for ($i = 0; $i < $test_num; $i++) {
							echo do_shortcode("[go_test type='".$test_all_types[$i]."' question='".$test_all_questions[$i]."' possible_answers='".$test_all_answers[$i]."' key='".$test_all_keys[$i]."' test_id='".$i."' total_num='".$test_num."']");
						}
						echo "<button class='go_test_submit' style='margin-top: -10px; margin-left: 40px;'>GO!</button><br/><br/>";
					} else {
						echo do_shortcode("[go_test type='".$test_type_0."' question='".$test_question_0."' possible_answers='".$test_answers_0."' key='".$test_key_0."' test_id='0']");
					}
				}
			}

			if ($completion_upload) {
				echo do_shortcode("[go_upload]")."<br/>";
			}

			echo ' <button id="go_button" status="3" onclick="task_stage_change();this.disabled=true;">'
			.go_return_options('go_third_stage_button').'</button> <button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button></div>';
			
			if($complete_lock == 'true'){
				echo '<br/><div id="go_complete_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
				<input type="password" id="go_unlock_next_stage"/>';	
			}
			break;
		case 3:
			echo do_shortcode(wpautop($accpt_mssg, false)).'<div id="new_content">'
			.do_shortcode(wpautop($completion_message));
			if ($mastery_active) {
							if ($test_m_active) {
								if (preg_match("/('|\")+/", $test_m_question_0) || preg_match("/('|\")+/", $test_m_answers_0) || preg_match("/('|\")+/", $test_m_key_0)) {
									if (current_user_can('manage_options')) {
										echo "<span style='color:red'><b>ERROR: Please make sure that there are no appostrophes (' or  \")in any of the provided fields.</b></span><br/>";
									}
								} else {
									if ($test_m_num > 1) {
										for ($i = 0; $i < $test_m_num; $i++) {
											echo do_shortcode("[go_test type='".$test_m_all_types[$i]."' question='".$test_m_all_questions[$i]."' possible_answers='".$test_m_all_answers[$i]."' key='".$test_m_all_keys[$i]."' test_id='".$i."' total_num='".$test_m_num."']");
										}
										echo "<button class='go_test_submit' style='margin-top: -10px; margin-left: 40px;'>GO!</button><br/><br/>";
									} else {
										echo do_shortcode("[go_test type='".$test_m_type_0."' question='".$test_m_question_0."' possible_answers='".$test_m_answers_0."' key='".$test_m_key_0."' test_id='0']");
									}
								}
							}

							if ($mastery_upload) {
								echo do_shortcode("[go_upload]")."<br/>";
							}

							echo '<button id="go_button" status="4" onclick="task_stage_change();this.disabled=true;">'.
							go_return_options('go_fourth_stage_button').'</button> 
							<button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
							</div>';
							
							if($mastery_lock == 'true'){
								echo '<br/><div id="go_mastery_lock_message" class="go_lock_message">Need '.$admin_name.'\'s approval to continue.</div>
								<input type="password" id="go_unlock_next_stage"/>';	
							}
						} else {
							echo '<span id="go_button" status="4" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);this.disabled=true;" undo="true">Undo</button>
								</div>';
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
