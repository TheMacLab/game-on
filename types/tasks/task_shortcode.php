<?php
session_start();
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
	global $wpdb;
	$user_ID = get_current_user_id(); // User ID
	$page_id = get_the_ID();
	if ($id && !empty($user_ID)) { // If the shortcode has an attribute called id, run this code
		$custom_fields = get_post_custom($id); // Just gathering some data about this task with its post id
		$rewards = unserialize($custom_fields['go_presets'][0]);
		$mastery_active = !$custom_fields['go_mta_task_mastery'][0]; // whether or not the mastery stage is active
		$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task
		$unix_now = current_time('timestamp'); // Current unix timestamp
		$go_table_ind = $wpdb->prefix.'go';
		$task_count = $wpdb->get_var("SELECT `count` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
		$status = (int)$wpdb->get_var("SELECT `status` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
		
		$e_admin_lock = unserialize($custom_fields['go_mta_encounter_admin_lock'][0]);
		$e_is_locked = $e_admin_lock[0];
		if ($e_is_locked === 'true') {
			$e_pass_lock = $e_admin_lock[1];
		}
		$a_admin_lock = unserialize($custom_fields['go_mta_accept_admin_lock'][0]);
		$a_is_locked = $a_admin_lock[0];
		if ($a_is_locked === 'true') {
			$a_pass_lock = $a_admin_lock[1];
		}
		$c_admin_lock = unserialize($custom_fields['go_mta_completion_admin_lock'][0]);
		$c_is_locked = $c_admin_lock[0];
		if ($c_is_locked === 'true') {
			$c_pass_lock = $c_admin_lock[1];
		}
		$m_admin_lock = unserialize($custom_fields['go_mta_mastery_admin_lock'][0]);
		$m_is_locked = $m_admin_lock[0];
		if ($m_is_locked === 'true') {
			$m_pass_lock = $m_admin_lock[1];
		}
		$r_admin_lock = unserialize($custom_fields['go_mta_repeat_admin_lock'][0]);
		$r_is_locked = $r_admin_lock[0];
		if ($r_is_locked === 'true') {
			$r_pass_lock = $r_admin_lock[1];
		}

		$e_url_is_locked = (!empty($custom_fields['go_mta_encounter_url_key'][0]) ? true : false);
		$a_url_is_locked = (!empty($custom_fields['go_mta_accept_url_key'][0]) ? true : false);
		$c_url_is_locked = (!empty($custom_fields['go_mta_completion_url_key'][0]) ? true : false);
		$m_url_is_locked = (!empty($custom_fields['go_mta_mastery_url_key'][0]) ? true : false);

		$test_e_active = $custom_fields['go_mta_test_encounter_lock'][0];
		$test_a_active = $custom_fields['go_mta_test_accept_lock'][0];
		$test_c_active = $custom_fields['go_mta_test_completion_lock'][0];
		
		$number_of_stages = 4;
		
		if ($test_e_active) {
			$test_e_array = go_get_test_meta_content($custom_fields, 'encounter');
			$test_e_returns = $test_e_array[0];
			$test_e_num = $test_e_array[1];
			$test_e_all_questions = $test_e_array[2][0];
			$test_e_all_types = $test_e_array[2][1];
			$test_e_all_answers = $test_e_array[2][2];
			$test_e_all_keys = $test_e_array[2][3];
		}
		$encounter_upload = $custom_fields['go_mta_encounter_upload'][0];

		if ($test_a_active) {
			$test_a_array = go_get_test_meta_content($custom_fields, 'accept');
			$test_a_returns = $test_a_array[0];
			$test_a_num = $test_a_array[1];
			$test_a_all_questions = $test_a_array[2][0];
			$test_a_all_types = $test_a_array[2][1];
			$test_a_all_answers = $test_a_array[2][2];
			$test_a_all_keys = $test_a_array[2][3];
		}
		$accept_upload = $custom_fields['go_mta_accept_upload'][0];

		if ($test_c_active) {
			$test_c_array = go_get_test_meta_content($custom_fields, 'completion');
			$test_c_returns = $test_c_array[0];
			$test_c_num = $test_c_array[1];
			$test_c_all_questions = $test_c_array[2][0];
			$test_c_all_types = $test_c_array[2][1];
			$test_c_all_answers = $test_c_array[2][2];
			$test_c_all_keys = $test_c_array[2][3];
		}
		$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
		$completion_upload = $custom_fields['go_mta_completion_upload'][0];
		
		if ($mastery_active) {
			$test_m_active = $custom_fields['go_mta_test_mastery_lock'][0];

			if ($test_m_active) {
				$test_m_array = go_get_test_meta_content($custom_fields, 'mastery');
				$test_m_returns = $test_m_array[0];
				$test_m_num = $test_m_array[1];
				$test_m_all_questions = $test_m_array[2][0];
				$test_m_all_types = $test_m_array[2][1];
				$test_m_all_answers = $test_m_array[2][2];
				$test_m_all_keys = $test_m_array[2][3];
			}
			$mastery_message = $custom_fields['go_mta_mastery_message'][0]; // Mastery Message
			$mastery_upload = $custom_fields['go_mta_mastery_upload'][0];

			if ($repeat == 'on') {	// Checks if the task is repeatable and if it has a repeat limit
				if ($custom_fields['go_mta_repeat_amount'][0]) {
					$repeat_amount = $custom_fields['go_mta_repeat_amount'][0]; // Sets the limit equal to the meta field value decalred in the task creation page	
				} else {
					$repeat_amount = 0;
				}
				$repeat_message = $custom_fields['go_mta_repeat_message'][0];
				$repeat_upload = $custom_fields['go_mta_repeat_upload'][0];
				$number_of_stages = 5;
			}
		} else {
			$number_of_stages = 3;	
		}
		
		// Checks if the task has a bonus currency filter
		// Sets the filter equal to the meta field value declared in the task creation page, if none exists defaults to 0
		$bonus_currency_required = $custom_fields['go_mta_bonus_currency_filter'][0];
		
		// Checks if the task has a penalty filter
		$penalty_filter = $custom_fields['go_mta_penalty_filter'][0];

		if($custom_fields['go_mta_focus_category_lock'][0]){
			$focus_category_lock = true;
		}

		$description = $custom_fields['go_mta_quick_desc'][0]; // Description
		$req_rank = $custom_fields['go_mta_req_rank'][0]; // Required Rank to accept task
		if (!empty($req_rank)) {
			$ranks = get_option('go_ranks');
			if (!empty($ranks['name']) && !empty($ranks['points'])) {
				$index = array_search($req_rank, $ranks['name']);
				$req_points = $ranks['points'][$index];
			} else {
				$req_points = null;
			}
		} else {
			$req_points = null;
		}
		
		$points_array = $rewards['points'];
		$points_str = implode(" ", $points_array);
		$currency_array = $rewards['currency'];
		$currency_str = implode(" ", $currency_array);
		$bonus_currency_array = $rewards['bonus_currency'];
		$bonus_currency_str = implode(" ", $bonus_currency_array);
		
		$current_bonus_currency = go_return_bonus_currency($user_ID);	
		$current_penalty = go_return_penalty($user_ID);

		$go_admin_email = get_option('go_admin_email');
		if ($go_admin_email) {
			$admin = get_user_by('email', $go_admin_email);
			$admin_name = addslashes($admin->display_name);
		}
		$is_admin = false;
		$user_obj = get_user_by('id', $user_ID);
		$user_roles = $user_obj->roles;
		if (!empty($user_roles)) {
			foreach ($user_roles as $role) {
				if ($role === "administrator") {
					$is_admin = true;
					break;
				}
			}
		}
		

		$content_post = get_post($id); // Grabs content of a task from the post table in your wordpress database where post_id = id in the shortcode. 
		$task_content = $content_post->post_content; // Grabs what the task actually says in the body of it
		
		if ($task_content == '') { // If the task is empty, run this code
			$accept_message = $custom_fields['go_mta_accept_message'][0]; // Accept message meta field exists, set accept message equal to the meta field's content
		} elseif($task_content != '' && !$custom_fields['go_mta_accept_message']) { // If content is returned from the post table, and the post doesn't have an accept message meta field, run this code
			add_post_meta($id, 'go_mta_accept_message', $task_content); // Add accept message meta field with value of the post's content from post table
		} else { // If the task has content in the post table, and has a meta field, run this code
			$accept_message = $custom_fields['go_mta_accept_message'][0]; // Set value of accept message equal to the task's accept message meta field value
		}
		
		$date_picker = ((unserialize($custom_fields['go_mta_date_picker'][0]))?array_filter(unserialize($custom_fields['go_mta_date_picker'][0])):false);
		
		// If there are dates in the date picker
		if (!empty($date_picker)) {
			
			$dates = $date_picker['date'];
			$times = $date_picker['time'];
			$percentages = $date_picker['percent'];
			
			// Setup empty array to house which dates are closest, in unix timestamp
			$past_dates = array();
			
			foreach ($dates as $key => $date) {
				// If current date in loop is in the past, add its key to the array of date modifiers
				if ($unix_now >= (strtotime($date) + strtotime($times[$key], 0))) {
					$past_dates[$key] = abs($unix_now - strtotime($date));
				}
			}
			
			if (!empty($past_dates)) {
				
				// Sorts array from least to greatest
				// Should pust most recent PAST date as first key in array, making grabbing the percentage associated with that day easy
				asort($past_dates);
				$update_percent = (float)(($percentages[key($past_dates)])/100);
			} else {
				$update_percent = 1;	
			}
			
		} else {
			$update_percent = 1;	
		}
		
		$date_update_percent = $update_percent;
		
		$future_modifier = unserialize($custom_fields['go_mta_time_modifier'][0]);
		
		if (!empty($future_modifier)) {
			$accept_timestamp = strtotime(str_replace('@', ' ', $wpdb->get_var("SELECT timestamp FROM {$wpdb->prefix}go WHERE uid='{$user_ID}' AND post_id='{$id}'")));
			$days = $future_modifier['days'] ;
			$hours = $future_modifier['hours'];
			$minutes = $future_modifier['minutes'];
			$future_time = strtotime("{$days} days", 0) + strtotime("{$hours} hours", 0) + strtotime("{$minutes} minutes", 0) + $accept_timestamp;
			
			if (($unix_now >= $future_time && $status >= 2) || ($unix_now >= $future_time && !empty($accept_timestamp))){
				$future_update_percent = max($update_percent * (float) ($future_modifier['percentage']/100), 0);
			} else {
				$future_update_percent = 1;	
			}
			if ($status < 2 && empty($accept_timestamp)) {
				echo "<span id='go_future_notification'><b>After accepting this ".strtolower(go_return_options('go_tasks_name'))." you will have {$days} days, {$hours} hours, and {$minutes} minutes to ".strtolower(go_return_options('go_third_stage_button'))." it or the reward will be reduced by {$future_modifier['percentage']}%. Once accepted you cannot undo the timer.</b></span>";
			}
		} else {
			$future_update_percent = 1;
		}
		
		// Alter rewards for accept/completion stage of task
		for ($i = 1; $i <= 2; $i++){
			$points_array[$i] = floor($points_array[$i] * $future_update_percent);
			$currency_array[$i] = floor($currency_array[$i] * $future_update_percent);
			$bonus_currency_array[$i] = floor($bonus_currency_array[$i] * $future_update_percent);	
		}
		
		
		global $current_points;
		if ($is_admin === false && !empty($req_points) && $current_points < $req_points) {
			$points = $req_points - $current_points;
			$points_name = strtolower(go_return_options('go_points_name'));
			$task_name = strtolower(go_return_options('go_tasks_name'));
			echo "You need {$points} more {$points_name} to begin this {$task_name}.";
		} else {

			switch ($status) {
				case (0):
					$db_task_stage_upload_var = 'e_uploaded';
					break;
				case (1):
					$db_task_stage_upload_var = 'a_uploaded';
					break;
				case (2):
					$db_task_stage_upload_var = 'c_uploaded';
					break;
				case (3):
					$db_task_stage_upload_var = 'm_uploaded';
					break;
				case (4):
					$db_task_stage_upload_var = 'r_uploaded';
					break;
			}
			if (!empty($db_task_stage_upload_var)) {
				$is_uploaded = $wpdb->get_var("SELECT {$db_task_stage_upload_var} FROM {$go_table_ind} WHERE uid = {$user_ID} AND post_id = {$id}");
			} else {
				$is_uploaded = 0;
			}
			
			if(get_the_terms($id, 'task_focus_categories') && $focus_category_lock){
				$categories = get_the_terms($id, 'task_focus_categories');
				$category_names = array();
				foreach($categories as $category){
					array_push($category_names, $category->name);	
				}
			}
			
			if(get_user_meta($user_ID, 'go_focus', true) != ''){
				$user_focus = get_user_meta($user_ID, 'go_focus', true);	
			}
			
			if($category_names && $user_focus){
				$go_ahead = array_intersect($user_focus, $category_names);
			}
			
			go_display_rewards($user_ID, $points_array, $currency_array, $bonus_currency_array, $update_percent, $number_of_stages);
			echo '
			<script type="text/javascript">
				jQuery(".entry-title").after(jQuery(".go_task_rewards"));
			</script>';
?> 

			<div id="go_description"><div class="go_stage_message"><?php echo  do_shortcode(wpautop($description));?></div></div>
            
<?php	
		// If current post in a chain and user logged in
		if ($custom_fields['chain'][0] != null) {
			
			$current_position_in_chain = get_post_meta($id, 'chain_position', true);
			$chain_tax = get_the_terms($id, 'task_chains');
			
			//Grab chain object for this post
			$chain = array_shift(array_values($chain_tax));
			//Grab all posts in the current chain in order
			$posts_in_chain = get_posts(array(
				'post_type' => 'tasks',
				'taxonomy' => 'task_chains',
				'term' => $chain->name,
				'order' => 'ASC',
				'meta_key' => 'chain_position',
				'orderby' => 'meta_value_num',
				'posts_per_page' => '-1'
			));

			// Loop through each one and make array of their ids
			foreach ($posts_in_chain as $post_in_chain) {
				$post_ids_in_chain[] = $post_in_chain->ID;
			}
			
			// Setup next task in chain 
			if ($id != end($post_ids_in_chain)) {
				$next_post_id_in_chain = $post_ids_in_chain[array_search($id, $post_ids_in_chain) + 1];
				$next_post_in_chain = '<a href="'.get_permalink($next_post_id_in_chain).'">'.get_the_title($next_post_id_in_chain).'</a>';
			}
			
			$post_ids_in_chain_string = join(',', $post_ids_in_chain);
			
			// Grab all posts in chain statuses
			$list = $wpdb->get_results("
			select post_id,status
			from ".$go_table_ind."
			where uid = $user_ID
			and post_id in ($post_ids_in_chain_string)
			order by field(post_id, $post_ids_in_chain_string)
			");
			
			// Make array of statuses in chain indexed by post id
			foreach ($list as $post_obj) {
				$post_status_in_chain[$post_obj->post_id] = $post_obj->status;
			}
			
			foreach ($post_ids_in_chain as $post_id_in_chain) {
				if($post_id_in_chain == $id){
					break;	
				}
				
				$post_custom_in_chain = get_post_custom($post_id_in_chain);
				$post_mastery_active_in_chain = !$post_custom_in_chain['go_mta_task_mastery'][0];
				// $post_number_of_stages_in_chain will later be designated by an admin option that will be toggleable per task chain.
				if ($post_mastery_active_in_chain) {
					$post_number_of_stages_in_chain = 3;
				} else {
					$post_number_of_stages_in_chain = 3;
				}
				
				// Check if current post in loop has been completed/mastered, depending on the number of stages in the task that needs to be completed
				if ($post_status_in_chain[$post_id_in_chain] < $post_number_of_stages_in_chain) {
					$previous_task = '<a href="'.get_permalink($post_id_in_chain).'">'.get_the_title($post_id_in_chain).'</a>';
					echo 'You must finish '.$previous_task.' to do this '.strtolower(go_return_options('go_tasks_name'));
					return false;	
				}
			}
			
			if ($current_position_in_chain == $chain->count) {
				$last_in_chain = true;
			} else {
				$last_in_chain = false;
			}
		
		}
		if ($is_admin === true || $go_ahead || !isset($focus_category_lock) || empty($category_names)){
			if (($current_bonus_currency >= $bonus_currency_required && !empty($bonus_currency_required)) || ($current_penalty < $penalty_filter && !empty($penalty_filter)) || (empty($bonus_currency_required) && empty($penalty_filter))) {
				switch ($status) {
					
					// First time a user encounters a task
					case 0:

					// sending go_add_post the $repeat var was the problem, that is why it is now sending a null value.
					go_add_post(
						$user_ID, 
						$id, 
						0, 
						floor(($update_percent * $points_array[0])), 
						floor(($update_percent * $currency_array[0])), 
						floor(($update_percent * $bonus_currency_array[0])),
						null,
						$page_id, 
						null, 
						0, 
						0, 
						0, 
						$c_passed, 
						$m_passed
					);
		?>
					<div id="go_content">
					<?php
						if ($test_e_active) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ($test_e_num > 1) {
								for ($i = 0; $i < $test_e_num; $i++) {
									echo do_shortcode("[go_test type='".$test_e_all_types[$i]."' question='".$test_e_all_questions[$i]."' possible_answers='".$test_e_all_answers[$i]."' key='".$test_e_all_keys[$i]."' test_id='".$i."' total_num='".$test_e_num."']");
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} else {
								echo do_shortcode("[go_test type='".$test_e_all_types[0]."' question='".$test_e_all_questions[0]."' possible_answers='".$test_e_all_answers[0]."' key='".$test_e_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ($encounter_upload) {
							echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
						}
					?>
					<p id='go_stage_error_msg' style='display: none; color: red;'></p>
					<?php 
					if ($e_is_locked === 'true' && !empty($e_pass_lock)) {
						echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
					} else if ($e_url_is_locked === true) {
						echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
					}
					?>
					<button id="go_button" status= "2" onclick="task_stage_change(this);" <?php if ($e_is_locked === 'true' && empty($e_pass_lock)) {echo "admin_lock='true'";} ?>><?php echo go_return_options('go_second_stage_button') ?></button>
					<button id="go_abandon_task" onclick="go_task_abandon();this.disabled = true;"><?php echo get_option('go_abandon_stage_button', 'Abandon'); ?></button>
					</div>
		<?php		
					break;
					
					// Encountered
					case 1: 
		?>
					<div id="go_content">
					<?php
						if ($test_e_active) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ($test_e_num > 1) {
								for ($i = 0; $i < $test_e_num; $i++) {
									echo do_shortcode("[go_test type='".$test_e_all_types[$i]."' question='".$test_e_all_questions[$i]."' possible_answers='".$test_e_all_answers[$i]."' key='".$test_e_all_keys[$i]."' test_id='".$i."' total_num='".$test_e_num."']");
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} else {
								echo do_shortcode("[go_test type='".$test_e_all_types[0]."' question='".$test_e_all_questions[0]."' possible_answers='".$test_e_all_answers[0]."' key='".$test_e_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ($encounter_upload) {
							echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
						}
					?>
					<p id='go_stage_error_msg' style='display: none; color: red;'></p>
					<?php 
					if ($e_is_locked === 'true' && !empty($e_pass_lock)) {
						echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
					} else if ($e_url_is_locked === true) {
						echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
					}
					?>
					<button id="go_button" status= "2" onclick="task_stage_change(this);" <?php if ($e_is_locked === 'true' && empty($e_pass_lock)) {echo "admin_lock='true'";} ?>><?php echo go_return_options('go_second_stage_button') ?></button>
					<button id="go_abandon_task" onclick="go_task_abandon();this.disabled = true;"><?php echo get_option('go_abandon_stage_button', 'Abandon'); ?></button>
					</div>   
		<?php
					break;
					
					// Accepted
					case 2: 
						echo '<div id="go_content"><div class="go_stage_message">'.do_shortcode(wpautop($accept_message)).'</div>';
						if ($test_a_active) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ($test_a_num > 1) {
								for ($i = 0; $i < $test_a_num; $i++) {
									echo do_shortcode("[go_test type='".$test_a_all_types[$i]."' question='".$test_a_all_questions[$i]."' possible_answers='".$test_a_all_answers[$i]."' key='".$test_a_all_keys[$i]."' test_id='".$i."' total_num='".$test_a_num."']");
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} else {
								echo do_shortcode("[go_test type='".$test_a_all_types[0]."' question='".$test_a_all_questions[0]."' possible_answers='".$test_a_all_answers[0]."' key='".$test_a_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ($accept_upload) {
							echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
						}
						echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
						if ($a_is_locked === 'true' && !empty($a_pass_lock)) {
							echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
						} else if ($a_url_is_locked === true) {
							echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
						}
						echo "<button id='go_button' status='3' onclick='task_stage_change(this);'";
						if ($a_is_locked === 'true' && empty($a_pass_lock)) {
							echo "admin_lock='true'";
						}
						echo '>'.go_return_options('go_third_stage_button').'</button>
						<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
						</div>';
					break;
					
					// Completed
					case 3: 
						echo '<div id="go_content"><div class="go_stage_message">'. do_shortcode(wpautop($accept_message)).'</div><div class="go_stage_message">
						'.do_shortcode(wpautop($completion_message)).'</div>';
						if ($mastery_active) {
							if ($test_c_active) {
								echo "<p id='go_test_error_msg' style='color: red;'></p>";
								if ($test_c_num > 1) {
									for ($i = 0; $i < $test_c_num; $i++) {
										echo do_shortcode("[go_test type='".$test_c_all_types[$i]."' question='".$test_c_all_questions[$i]."' possible_answers='".$test_c_all_answers[$i]."' key='".$test_c_all_keys[$i]."' test_id='".$i."' total_num='".$test_c_num."']");
									}
									echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								} else {
									echo do_shortcode("[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								}
							}
							if ($completion_upload) {
								echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
							}
							echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
							if ($c_is_locked === 'true' && !empty($c_pass_lock)) {
								echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
							} else if ($c_url_is_locked === true) {
								echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
							}
							echo "<button id='go_button' status='4' onclick='task_stage_change(this);'";
							if ($c_is_locked === 'true' && empty($c_pass_lock)) {
								echo "admin_lock='true'";
							}
							echo '>'.go_return_options('go_fourth_stage_button').'</button> 
							<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
							
							if($next_post_in_chain && !$last_in_chain){
								echo '<div class="go_chain_message">Next '.strtolower(go_return_options('go_tasks_name')).' in '.$chain->name.': '.$next_post_in_chain.'</div>';
							}else{
								echo '<div class="go_chain_message">'.$custom_fields['go_mta_final_chain_message'][0].'</div>';	
							}
							echo "</div>";
						} else {
							if ($test_c_active) {
								echo "<p id='go_test_error_msg' style='color: red;'></p>";
								if ($test_c_num > 1) {
									for ($i = 0; $i < $test_c_num; $i++) {
										echo do_shortcode("[go_test type='".$test_c_all_types[$i]."' question='".$test_c_all_questions[$i]."' possible_answers='".$test_c_all_answers[$i]."' key='".$test_c_all_keys[$i]."' test_id='".$i."' total_num='".$test_c_num."']");
									}
									echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								} else {
									echo do_shortcode("[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								}
							}
							if ($completion_upload) {
								echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
							}
							echo '<span id="go_button" status="4" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
							if($next_post_in_chain && !$last_in_chain){
								echo '<div class="go_chain_message">Next '.strtolower(go_return_options('go_tasks_name')).' in '.$chain->name.': '.$next_post_in_chain.'</div>';
							}else{
								echo '<div class="go_chain_message">'.$custom_fields['go_mta_final_chain_message'][0].'</div>';	
							}
							echo "</div>";
						}
					break;
					
					// Mastered
					case 4:  
						echo'<div id="go_content"><div class="go_stage_message">'. do_shortcode(wpautop($accept_message)).'</div>'.'<div class="go_stage_message">'.do_shortcode(wpautop($completion_message)).'</div><div class="go_stage_message">'.do_shortcode(wpautop($mastery_message)).'</div>';
						if ($repeat == 'on') {
							if ($task_count < $repeat_amount || $repeat_amount == 0) { // Checks if the amount of times a user has completed a task is less than the amount of times they are allowed to complete a task. If so, outputs the repeat button to allow the user to repeat the task again. 
								if ($task_count == 0) {
									if ($test_m_active) {
										echo "<p id='go_test_error_msg' style='color: red;'></p>";
										if ($test_m_num > 1) {
											for ($i = 0; $i < $test_m_num; $i++) {
												echo do_shortcode("[go_test type='".$test_m_all_types[$i]."' question='".$test_m_all_questions[$i]."' possible_answers='".$test_m_all_answers[$i]."' key='".$test_m_all_keys[$i]."' test_id='".$i."' total_num='".$test_m_num."']");
											}
											echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
										} else {
											echo do_shortcode("[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
										}
									}
									if ($mastery_upload) {
										echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
									}
									echo '
										<div id="repeat_quest">
											<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
												.do_shortcode(wpautop($repeat_message)).
												"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
									if ($m_is_locked === 'true' && !empty($m_pass_lock)) {
										echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
									} else if ($m_url_is_locked === true) {
										echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
									}
									echo "<button id='go_button' status='4' onclick='go_repeat_hide(this);' repeat='on'";
									if ($m_is_locked === 'true' && empty($m_pass_lock)) {
										echo "admin_lock='true'";
									}
									echo '>'.go_return_options('go_fourth_stage_button')." Again". 
												'</button>
												<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
											</div>
											<div id="go_repeat_unclicked">
												<button id="go_button" status="4" onclick="go_repeat_replace();">
													See '.get_option('go_fifth_stage_name').
												'</button>
												<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
											</div>
										</div>
									';
								} else {
									if ($repeat_upload) {
										echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
									}
									echo '
										<div id="repeat_quest">
											<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
												.do_shortcode(wpautop($repeat_message)).
												"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
									if ($r_is_locked === 'true' && !empty($r_pass_lock)) {
										echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
									}
									echo "<button id='go_button' status='4' onclick='go_repeat_hide(this);' repeat='on'";
									if ($r_is_locked === 'true' && empty($r_pass_lock)) {
										echo "admin_lock='true'";
									}
									echo '>'.go_return_options('go_fourth_stage_button')." Again". 
												'</button>
												<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
											</div>
											<div id="go_repeat_unclicked">
												<button id="go_button" status="4" onclick="go_repeat_replace();">'
													.get_option('go_fifth_stage_button').
												'</button>
												<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
											</div>
										</div>
									';
								}
							} else {
								echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
							}
						} else {
							if ($test_m_active) {
								echo "<p id='go_test_error_msg' style='color: red;'></p>";
								if ($test_m_num > 1) {
									for ($i = 0; $i < $test_m_num; $i++) {
										echo do_shortcode("[go_test type='".$test_m_all_types[$i]."' question='".$test_m_all_questions[$i]."' possible_answers='".$test_m_all_answers[$i]."' key='".$test_m_all_keys[$i]."' test_id='".$i."' total_num='".$test_m_num."']");
									}
									echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								} else {
									echo do_shortcode("[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
								}
							}
							if ($mastery_upload) {
								echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_ID} post_id={$id}]")."<br/>";
							}
							echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
						}
						if($next_post_in_chain && !$last_in_chain){
							echo '<div class="go_chain_message">Next '.strtolower(go_return_options('go_tasks_name')).' in '.$chain->name.': '.$next_post_in_chain.'</div>';
						}else{
							echo '<div class="go_chain_message">'.$custom_fields['go_mta_final_chain_message'][0].'</div>';	
						}
						echo '</div>';
				}
				if(get_post_type() == 'tasks'){
					comments_template();
				}
			} else {
				if (($current_bonus_currency < $bonus_currency_required && !empty($bonus_currency_required)) && ($current_penalty > $penalty_filter && !empty($penalty_filter))) {
					echo "You require more than {$bonus_currency_required} ".go_return_options('go_bonus_currency_name')." and less than {$penalty_filter} ".go_return_options('go_penalty_name')." to view this ".go_return_options('go_tasks_name').".";
				} else if (($current_bonus_currency < $bonus_currency_required && !empty($bonus_currency_required))) {
					echo "You require more than {$bonus_currency_required} ".go_return_options('go_bonus_currency_name')." to view this ".go_return_options('go_tasks_name').".";
				} else if (($current_penalty > $penalty_filter && !empty($penalty_filter))) {
					echo "You require less than {$penalty_filter} ".go_return_options('go_penalty_name')." to view this ".go_return_options('go_tasks_name').".";
				}
			}
		} else { // If user can't access quest because they aren't part of the specialty, echo this
			$category_name = implode(',',$category_names);
			$focus_name = get_option('go_focus_name', 'Profession');
			$task_name = strtolower(get_option('go_tasks_name', 'Quest'));
			echo "This {$task_name} is only available to the \"{$category_name}\" {$focus_name}.";
		}

		if ($test_e_active && $test_e_returns) {
			$db_test_encounter_fail_count = $wpdb->get_var("SELECT `e_fail_count` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_encounter_fail_count'] = $db_test_encounter_fail_count;
			
			$db_test_encounter_passed = $wpdb->get_var("SELECT `e_passed` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_encounter_passed'] = $db_test_encounter_passed;
		}

		if ($test_a_active && $test_a_returns) {
			$db_test_accept_fail_count = $wpdb->get_var("SELECT `a_fail_count` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_accept_fail_count'] = $db_test_accept_fail_count;
			
			$db_test_accept_passed = $wpdb->get_var("SELECT `a_passed` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_accept_passed'] = $db_test_accept_passed;
		}

		if ($test_c_active && $test_c_returns) {
			$db_test_completion_fail_count = $wpdb->get_var("SELECT `c_fail_count` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_completion_fail_count'] = $db_test_completion_fail_count;
			
			$db_test_completion_passed = $wpdb->get_var("SELECT `c_passed` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_completion_passed'] = $db_test_completion_passed;
		}

		if ($test_m_active && $test_m_returns) {
			$db_test_mastery_fail_count = $wpdb->get_var("SELECT `m_fail_count` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_mastery_fail_count'] = $db_test_mastery_fail_count;
			
			$test_mastery_passed = $wpdb->get_var("SELECT `m_passed` FROM ".$go_table_ind." WHERE post_id = $id AND uid = $user_ID");
			$_SESSION['test_mastery_passed'] = $test_mastery_passed;
		}
?>
	<script language="javascript">
		jQuery(document).ready(function() {
			jQuery.ajaxSetup({ 
				url: '<?php echo get_site_url(); ?>/wp-admin/admin-ajax.php'
			});
			check_locks();
		});

		function go_task_abandon () {
			jQuery.ajax({
				type: "POST",
				data: {
					action: "go_task_abandon",
					post_id: "<?php echo $id; ?>",
					encounter_points: <?php echo floor($points_array[0] * $update_percent); ?>,
					encounter_currency: <?php echo floor($currency_array[0] * $update_percent); ?>,
					encounter_bonus: <?php echo floor($bonus_currency_array[0] * $update_percent); ?>
				}, success: function () {
					window.location = "<?php echo home_url(); ?>";
				}
			});
		}

		function check_locks() {
			if (jQuery(".go_test_list").length != 0) {
				jQuery('.go_test_submit_div').show();
			}
			var is_uploaded = jQuery('#go_upload_form').attr('uploaded');
			if (jQuery(".go_test_list").length != 0 && jQuery('#go_upload_form').length != 0) {
				if (jQuery('#go_pass_lock').length == 0 && jQuery('#go_button').attr('admin_lock') !== 'true') {
					jQuery('#go_button').attr('disabled', 'true');
				}
				jQuery('.go_test_submit').click(function() {
					var test_list = jQuery(".go_test_list");
					var current_error_msg = jQuery('#go_test_error_msg').text();
					if (test_list.length > 1) {
						var checked_ans = 0;
						for (var i = 0; i < test_list.length; i++) {
							var obj_str = "#"+test_list[i].id+" input:checked";
							var chosen_answers = jQuery(obj_str);
							if (chosen_answers.length >= 1) {
								checked_ans++;
							} else {
								if (current_error_msg != "Please answer all questions!") {
									jQuery('#go_test_error_msg').text("Please answer all questions!");
								} else {
									flash_error_msg('#go_test_error_msg');
								}
							}
						}
						if (checked_ans >= test_list.length && is_uploaded == 1) {
							task_unlock();
						} else {
							if (checked_ans < test_list.length && is_uploaded != 1) {
								var error = "Please answer all questions and upload a file!";
							} else if (checked_ans < test_list.length) {
								var error = "Please answer all questions!";
							} else if (is_uploaded != 1) {
								var error = "Please upload a file!";
							}

							if (typeof(error) != null) {
								if (current_error_msg != error) {
									jQuery('#go_test_error_msg').text(error);
								} else {
									flash_error_msg('#go_test_error_msg');
								}
							}
						}
					} else {
						if (jQuery(".go_test_list input:checked").length >= 1 && is_uploaded == 1) {
							task_unlock();
						} else {
							if (jQuery(".go_test_list input:checked").length == 0 && is_uploaded != 1) {
								var error = "Please answer the question and upload a file!";
							} else if (jQuery(".go_test_list input:checked").length == 0) {
								var error = "Please answer the question!";
							} else if (is_uploaded != 1) {
								var error = "Please upload a file!";
							}

							if (typeof(error) != null) {
								if (current_error_msg != error) {
									jQuery('#go_test_error_msg').text(error);
								} else {
									flash_error_msg('#go_test_error_msg');
								}
							}
						}
					}
				});
				jQuery('#go_upload_submit').click(function() {
					var test_list = jQuery(".go_test_list");
					var current_error_msg = jQuery('#go_test_error_msg').text();
					if (test_list.length > 1) {
						var checked_ans = 0;
						for (var i = 0; i < test_list.length; i++) {
							var obj_str = "#"+test_list[i].id+" input:checked";
							var chosen_answers = jQuery(obj_str);
							if (chosen_answers.length >= 1) {
								checked_ans++;
							} else {
								if (current_error_msg != "Please answer all questions!") {
									jQuery('#go_test_error_msg').text("Please answer all questions!");
								} else {
									flash_error_msg('#go_test_error_msg');
								}
							}
						}
						if (checked_ans >= test_list.length && is_uploaded == 1) {
							task_unlock();
						} else {
							if (checked_ans < test_list.length && is_uploaded != 1) {
								var error = "Please answer all questions and upload a file!";
							} else if (checked_ans < test_list.length) {
								var error = "Please answer all questions!";
							} else if (is_uploaded != 1) {
								var error = "Please upload a file!";
							}

							if (typeof(error) != null) {
								if (current_error_msg != error) {
									jQuery('#go_test_error_msg').text(error);
								} else {
									flash_error_msg('#go_test_error_msg');
								}
							}
						}
					} else {
						if (jQuery(".go_test_list input:checked").length >= 1 && is_uploaded == 1) {
							task_unlock();
						} else {
							if (jQuery(".go_test_list input:checked").length == 0 && is_uploaded != 1) {
								var error = "Please answer the question and upload a file!";
							} else if (jQuery(".go_test_list input:checked").length == 0) {
								var error = "Please answer the question!";
							} else if (is_uploaded != 1) {
								var error = "Please upload a file!";
							}

							if (typeof(error) != null) {
								if (current_error_msg != error) {
									jQuery('#go_test_error_msg').text(error);
								} else {
									flash_error_msg('#go_test_error_msg');
								}
							}
						}
					}
				});
			} else if (jQuery(".go_test_list").length != 0) {
				if (jQuery('#go_pass_lock').length == 0 && jQuery('#go_button').attr('admin_lock') !== 'true') {
					jQuery('#go_button').attr('disabled', 'true');
				}
				jQuery('.go_test_submit').click(function() {
					var test_list = jQuery(".go_test_list");
					if (test_list.length > 1) {
						var checked_ans = 0;
						for (var i = 0; i < test_list.length; i++) {
							var obj_str = "#"+test_list[i].id+" input:checked";
							var chosen_answers = jQuery(obj_str);
							if (chosen_answers.length >= 1) {
								checked_ans++;
							}
						}
						if (checked_ans >= test_list.length) {
							task_unlock();
						} else {
							if (jQuery('#go_test_error_msg').text() != "Please answer all questions!") {
								jQuery('#go_test_error_msg').text("Please answer all questions!");
							} else {
								flash_error_msg('#go_test_error_msg');
							}
						}
					} else {
						if (jQuery(".go_test_list input:checked").length >= 1) {
							task_unlock();
						} else {
							if (jQuery('#go_test_error_msg').text() != "Please answer the question!") {
								jQuery('#go_test_error_msg').text("Please answer the question!");
							} else {
								flash_error_msg('#go_test_error_msg');
							}
						}
					}
				});
			} else if (jQuery('#go_upload_form').length != 0 && is_uploaded == 0) {
				if (jQuery('#go_pass_lock').length == 0 && jQuery('#go_button').attr('admin_lock') !== 'true') {
					jQuery('#go_button').attr('disabled', 'true');
				}
				jQuery('#go_upload_submit').click(function() {
					if (jQuery('#go_pass_lock').length > 0 && jQuery('#go_pass_lock').attr('value').length == 0) {
						var error = "Retrieve the password from <?php echo ($admin_name ? $admin_name : 'an administrator'); ?>.";
						if (jQuery('#go_stage_error_msg').text() != error) {
							jQuery('#go_stage_error_msg').text(error);
						} else {
							flash_error_msg('#go_stage_error_msg');
						}
					} else {
						task_unlock();
					}
				});
			}
			if ((jQuery('#go_pass_lock').length > 0 && jQuery('#go_pass_lock').attr('value').length == 0) && (jQuery('#go_upload_form').length != 0 && is_uploaded == 0) || jQuery(".go_test_list").length != 0) {
				if (jQuery('#go_stage_error_msg').is(":visible")) {
					var error = "Retrieve the password from <?php echo ($admin_name ? $admin_name : 'an administrator'); ?>.";
					if (jQuery('#go_stage_error_msg').text() != error) {
						jQuery('#go_stage_error_msg').text(error);
					} else {
						flash_error_msg('#go_stage_error_msg');
					}
				}
			}
		}

		function flash_error_msg(elem) {
			var bg_color = jQuery(elem).css('background-color');
			if (typeof(bg_color) === undefined) {
				bg_color = "white";
			}
			jQuery(elem).animate({
  				color: bg_color
  			}, 200, function() {
  				jQuery(elem).animate({
  					color: "red"
  				}, 200);
  			});
		}

		function task_unlock() {
			if (jQuery(".go_test_list").length != 0) {
				var test_list = jQuery(".go_test_list");
				var list_size = test_list.length;
				if (jQuery('.go_test_list :checked').length >= list_size) {
					
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
								if (chosen_answers[0] != undefined) {
									choice_array.push(chosen_answers[0].value);
								}
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
				} else {
					jQuery('#go_test_error_msg').text("Answer all questions!");
				}
			}

			var is_repeating = jQuery('#go_button').attr('repeat');
			if (is_repeating !== 'on') {
				var status = jQuery('#go_button').attr('status') - 2;
			} else {
				var status = jQuery('#go_button').attr('status') - 1;
			}
			jQuery.ajax({
				type: "POST",
				data:{
					action: 'unlock_stage',
					task: <?php echo $id; ?>,
					list_size: list_size,
					chosen_answer: choice,
					type: type,
					status: status,
					points: "<?php echo $points_str; ?>",
				},
				success: function(response) {
					if (response === 1 || response === '1') {
						jQuery('.go_test_container').hide('slow');
						jQuery('#test_failure_msg').hide('slow');
						jQuery('.go_test_submit_div').hide('slow');
						jQuery('.go_wrong_answer_marker').hide();
						if (!jQuery('#go_button').attr('admin_lock')) {
							jQuery('#go_button').removeAttr('disabled');
							jQuery('#go_test_error_msg').attr('style', 'color:green');
							jQuery('#go_test_error_msg').text("Well done, continue!");
						} else {
							jQuery('#go_test_error_msg').text("This stage can only be unlocked by <?php echo ($admin_name ? $admin_name : 'an administrator'); ?>.");
						}
						
						var test_e_returns = "<?php echo $test_e_returns; ?>";
						var test_a_returns = "<?php echo $test_a_returns; ?>";
						var test_c_returns = "<?php echo $test_c_returns; ?>";
						var test_m_returns = "<?php echo $test_m_returns; ?>";
						if ((status == 0 && test_e_returns == 'on') ||
							(status == 1 && test_a_returns == 'on') ||
							(status == 2 && test_c_returns == 'on') || 
							(status == 3 && test_m_returns == 'on')) {
							test_point_update();
						}
					} else {
						if (typeof(response) === 'string' && list_size > 1) {
							var failed_questions = response.split(", ");
							for (var x = 0; x < test_list.length; x++) {
								var test_id = "#"+test_list[x].id;
								if (jQuery.inArray(test_id, failed_questions) === -1) {
									if (jQuery(test_id+" .go_wrong_answer_marker").is(":visible")) {
										jQuery(test_id+" .go_wrong_answer_marker").hide();
									}
									if (!jQuery(test_id+" .go_correct_answer_marker").is(":visible")) {
										jQuery(test_id+" .go_correct_answer_marker").show();
									}
								} else {
									if (jQuery(test_id+" .go_correct_answer_marker").is(":visible")) {
										jQuery(test_id+" .go_correct_answer_marker").hide();
									}
									if (!jQuery(test_id+" .go_wrong_answer_marker").is(":visible")) {
										jQuery(test_id+" .go_wrong_answer_marker").show();
									}
								}
							}
						}
						var error_msg_val = jQuery('#go_test_error_msg').text();
						if (error_msg_val != "Wrong answer, try again!") {
							jQuery('#go_test_error_msg').text("Wrong answer, try again!");
						} else {
							flash_error_msg('#go_test_error_msg');
						}
					}
					// console.log(response);
				}
			});
		}
		
		function test_point_update() {
			var is_repeating = jQuery('#go_button').attr('repeat');
			if (is_repeating !== 'on') {
				var status = jQuery('#go_button').attr('status') - 2;
			} else {
				var status = jQuery('#go_button').attr('status') - 1;
			}
			jQuery.ajax({
				type: "POST",
				data: {
					action: "test_point_update",
					points: "<?php echo $points_str; ?>",
					currency: "<?php echo $currency_str; ?>",
					bonus_currency: "<?php echo $bonus_currency_str; ?>",
					status: status,
					page_id: <?php echo $page_id; ?>,
					user_ID: <?php echo $user_ID; ?>,
					post_id: <?php echo $id; ?>,
					update_percent: <?php echo $date_update_percent;?>
				},
				success: function (response) {
					// the three following lines are required for the go_notification to work
					var color = jQuery('#go_admin_bar_progress_bar').css("background-color");
					jQuery('#go_content').append(response);
					jQuery('#go_admin_bar_progress_bar').css({"background-color": color});
					// console.log("\n"+response);
				}
			});
		}
		
		function go_repeat_hide(target) {
			// hides the div#repeat_quest to create the repeat cycle.
			// jQuery("#repeat_quest").hide('slow');
			
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
		
		function task_stage_change(target) {
			var undoing = jQuery(target).attr('undo');
			if (undoing !== 'true' && jQuery('#go_button').length > 0) {
				var perma_locked = jQuery('#go_button').attr('admin_lock');
				if (perma_locked === 'true') {
					jQuery('#go_stage_error_msg').show();
					jQuery('#go_button').removeAttr('disabled');
					jQuery('#go_stage_error_msg').text("This stage can only be unlocked by <?php echo ($admin_name ? $admin_name : 'an administrator'); ?>.");
					return;
				}
			}
			if (undoing !== 'true' && jQuery('#go_pass_lock').length > 0) {
				var pass_entered = jQuery('#go_pass_lock').attr('value').length > 0 ? true : false;
				if (!pass_entered) {
					jQuery('#go_stage_error_msg').show();
					var error = "Retrieve the password from <?php echo ($admin_name ? $admin_name : 'an administrator'); ?>.";
					if (jQuery('#go_stage_error_msg').text() != error) {
						jQuery('#go_stage_error_msg').text(error);
					} else {
						flash_error_msg('#go_stage_error_msg');
					}
					return;
				}
			} else if (undoing !== 'true' && jQuery('#go_url_key').length > 0) {
				var the_url = jQuery('#go_url_key').attr('value').replace(/\s+/, "");
				if (the_url.length > 0) {
					if (the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && !(the_url.lastIndexOf('http://') > 0) && !(the_url.lastIndexOf('https://') > 0)) {
						var url_entered = true;
					} else {
						jQuery('#go_stage_error_msg').show();
						var error = "Enter a valid URL.";
						if (jQuery('#go_stage_error_msg').text() != error) {
							jQuery('#go_stage_error_msg').text(error);
						} else {
							flash_error_msg('#go_stage_error_msg');
						}
						return;
					}
				} else {
					jQuery('#go_stage_error_msg').show();
					var error = "Enter a valid URL.";
					if (jQuery('#go_stage_error_msg').text() != error) {
						jQuery('#go_stage_error_msg').text(error);
					} else {
						flash_error_msg('#go_stage_error_msg');
					}
					return;
				}
			}
			
			var color = jQuery('#go_admin_bar_progress_bar').css("background-color");

			// redeclare (also called "overloading") the variable $task_count to the value of the 'count' var on the database.
			<?php $task_count = $wpdb->get_var("select `count` from ".$go_table_ind." where post_id = $id and uid = $user_ID"); ?>
	  		
			// if the button#go_button exists, set var 'task_status' to the value of the 'status' attribute on the current button#go_button.
			if (jQuery('#go_button').length != 0) {
				var task_status = jQuery('#go_button').attr('status');
			} else {
				var task_status = 5;
			}
			
			// if 'target' (if an argument is sent to task_stage_change, it is stored as a parameter in the 'target' variable)
			// is assigned the value of jQuery('#go_back_button'), AND the div#new_content exists...
			if (jQuery(target).is('#go_back_button') && jQuery('#new_content').length != 0) {
				jQuery('#new_content p').hide('slow');
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
					admin_name: '<?php echo $admin_name; ?>',
					task_count: <?php 
									if ($task_count == null) {
										echo '0';
									} else {
										echo $task_count;
									}
								?>,
					status: task_status,
					repeat: repeat_attr,
					undo: undoing,
					pass: (pass_entered ? jQuery('#go_pass_lock').attr('value') : ''),
					url: (url_entered ? jQuery('#go_url_key').attr('value') : ''),
					page_id: <?php echo $page_id; ?>,
					points: <?php echo json_encode($points_array);?>,
					currency: <?php echo json_encode($currency_array); ?>,
					bonus_currency: <?php echo json_encode($bonus_currency_array);?>,
					update_percent: <?php echo $date_update_percent;?>,
					chain_name: '<?php if($chain->name){echo $chain->name;}else{echo '';}?>',
					next_post_id_in_chain: <?php if($next_post_id_in_chain){echo $next_post_id_in_chain;}else{echo 0;} ?>,
					last_in_chain: <?php if($last_in_chain){echo 'true';}else{echo 'false';}?>,
					number_of_stages: <?php echo $number_of_stages;?>
				},
				success: function(html){
					if (html === '0') {
						jQuery('#go_stage_error_msg').show();
						var error = "Retrieve the password from <?php echo ($admin_name ? $admin_name : 'an administrator'); ?>.";
						if (jQuery('#go_stage_error_msg').text() != error) {
							jQuery('#go_stage_error_msg').text(error);
						} else {
							flash_error_msg('#go_stage_error_msg');
						}
					} else {
						jQuery('#go_future_notification').remove();
						jQuery('#go_content').html(html);
						jQuery('#go_admin_bar_progress_bar').css({"background-color": color});
						jQuery("#new_content").css("display", "none");
						jQuery("#new_content").show('slow');
						if(jQuery('#go_button').attr('status') == 2){
							jQuery('#new_content').children().first().remove();	
						}
						jQuery('#go_button').ready(function() {
							check_locks();
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
	} else {
		$custom_fields = get_post_custom($id);
		$encounter_message = $custom_fields['go_mta_quick_desc'][0];
		$accept_message = $custom_fields['go_mta_accept_message'][0];
		$complete_message = $custom_fields['go_mta_complete_message'][0];
		$mastery_active = !$custom_fields['go_mta_task_mastery'][0];
		if ($mastery_active) {
			$mastery_privacy = !$custom_fields['go_mta_mastery_privacy'][0];
			if ($mastery_privacy) {
				$mastery_message = $custom_fields['go_mta_mastery_message'][0];
				$repeat_active = $custom_fields['go_mta_task_repeat'][0];
				if ($repeat_active && $mastery_privacy) {
					$repeat_privacy = !$custom_fields['go_mta_repeat_privacy'][0];
					if ($repeat_privacy) {
						$repeat_message = $custom_fields['go_mta_repeat_message'][0];
					} else {
						$repeat_message = "This stage has been hidden by the administrator.";
					}
				}
			} else {
				$mastery_message = "This stage has been hidden by the administrator.";
			}
		}
		echo "<div id='go_content'>";
		if (!empty($encounter_message)) {
			echo "<div id='go_stage_encounter_message' class='go_stage_message'>".do_shortcode(wpautop($encounter_message))."</div>";
		}
		if (!empty($accept_message)) {
			echo "<div id='go_stage_accept_message' class='go_stage_message'>".do_shortcode(wpautop($accept_message))."</div>";
		}
		if (!empty($complete_message)) {
			echo "<div id='go_stage_complete_message' class='go_stage_message'>".do_shortcode(wpautop($complete_message))."</div>";
		}
		if (!empty($mastery_message)) {
			echo "<div id='go_stage_mastery_message' class='go_stage_message'>".do_shortcode(wpautop($mastery_message))."</div>";
			if (!empty($repeat_message)) {
				echo "<div id='go_stage_repeat_message' class='go_stage_message'>".do_shortcode(wpautop($repeat_message))."</div>";
			}
		}
	}
}
add_shortcode('go_task','go_task_shortcode');

function go_get_test_meta_content ($custom_fields, $stage) {
	$test_returns = $custom_fields["go_mta_test_{$stage}_lock_loot"][0];
	$test_array = $custom_fields["go_mta_test_lock_{$stage}"][0];
	$test_uns = unserialize($test_array);

	$test_num = $test_uns[3];
	$test_all_questions = array();
	foreach ($test_uns[0] as $question) {
		$esc_question = htmlspecialchars($question, ENT_QUOTES);
		if (preg_match("/[\\\[\]]/", $question)) {
			$str = preg_replace(array("/\[/", "/\]/", "/\\\/"), array('&#91;', '&#93;', '\\\\\\\\\\\\'), $esc_question);
			$test_all_questions[] = $str;
		} else {
			$test_all_questions[] = $esc_question;
		}
	}
	$test_all_types = $test_uns[2];
	$test_all_inputs = $test_uns[1];
	$test_all_input_num = $test_uns[4];
	$test_all_answers = array();
	$test_all_keys = array();
	for ($i = 0; $i < count($test_all_inputs); $i++) {
		if (!empty($test_all_inputs[$i][0])) {
			$answer_temp = implode("###", $test_all_inputs[$i][0]);
			$esc_answer = htmlspecialchars($answer_temp, ENT_QUOTES);
			if (preg_match("/[\\\[\]]/", $answer_temp)) {
				$str = preg_replace(array("/\[/", "/\]/", "/\\\/"), array('&#91;', '&#93;', '\\\\\\\\\\\\'), $esc_answer);
				$test_all_answers[] = $str;
			} else {
				$test_all_answers[] = $esc_answer;
			}
		}
		if (!empty($test_all_inputs[$i][1])) {
			$key_temp = implode("###", $test_all_inputs[$i][1]);
			$esc_key = htmlspecialchars($key_temp, ENT_QUOTES);
			if (preg_match("/[\\\[\]]/", $key_temp)) {
				$str = preg_replace(array("/\[/", "/\]/", "/\\\/"), array('&#91;', '&#93;', '\\\\\\\\\\\\'), $esc_key);
				$test_all_keys[] = $str;
			} else {
				$test_all_keys[] = $esc_key;
			}
		}
	}

	return (array($test_returns, $test_num, array($test_all_questions, $test_all_types, $test_all_answers, $test_all_keys)));
}

function test_point_update () {
	$status = (int)$_POST['status'];
	$page_id = $_POST['page_id'];
	$post_id = $_POST['post_id'];
	$user_id = $_POST['user_ID'];
	$points_str = $_POST['points'];
	$currency_str = $_POST['currency'];
	$bonus_currency_str = $_POST['bonus_currency'];
	$update_percent = $_POST['update_percent'];
	$points_array = explode(" ", $points_str);
	$point_base = (int)$points_array[$status];
	$currency_array = explode(" ", $currency_str);
	$currency_base = (int)$currency_array[$status];
	$bonus_currency_array = explode(" ", $bonus_currency_str);
	$bonus_currency_base = (int)$bonus_currency_array[$status];
	$e_fail_count = $_SESSION['test_encounter_fail_count'];
	$a_fail_count = $_SESSION['test_accept_fail_count'];
	$c_fail_count = $_SESSION['test_completion_fail_count'];
	$m_fail_count = $_SESSION['test_mastery_fail_count'];
	$status++;

	$custom_fields = get_post_custom($post_id);
	switch ($status) {
		case (1):
			$fail_count = $e_fail_count;
			$custom_mod = $custom_fields['go_mta_test_encounter_lock_loot_mod'][0];
			$passed = $_SESSION['test_encounter_passed'];
			$_SESSION['test_encounter_passed'] = 1;
			break;
		case (2):
			$fail_count = $a_fail_count;
			$custom_mod = $custom_fields['go_mta_test_accept_lock_loot_mod'][0];
			$passed = $_SESSION['test_accept_passed'];
			$_SESSION['test_accept_passed'] = 1;
			break;
		case (3):
			$fail_count = $c_fail_count;
			$custom_mod = $custom_fields['go_mta_test_completion_lock_loot_mod'][0];
			$passed = $_SESSION['test_completion_passed'];
			$_SESSION['test_completion_passed'] = 1;
			break;
		case (4):
			$fail_count = $m_fail_count;
			$custom_mod = $custom_fields['go_mta_test_mastery_lock_loot_mod'][0];
			$passed = $_SESSION['test_mastery_passed'];
			$_SESSION['test_mastery_passed'] = 1;
			break;
	}

	if (empty($fail_count)) {
		$fail_count = 0;
	}

	if (is_null($passed)) {
		$passed = 1;
	}

	$e_passed = $_SESSION['test_encounter_passed'];
	$a_passed = $_SESSION['test_accept_passed'];
	$c_passed = $_SESSION['test_completion_passed'];
	$m_passed = $_SESSION['test_mastery_passed'];
	
	$percent = $custom_mod / 100;
	if (!empty($point_base)) {
		$test_fail_max_temp = $point_base / ($point_base * $percent);	
	} else if (!empty($currency_base)) {
		$test_fail_max_temp = $currency_base / ($currency_base * $percent);
	} else if (!empty($bonus_currency_base)) {
		$test_fail_max_temp = $bonus_currency_base / ($bonus_currency_base * $percent);
	}
	$test_fail_max = ceil($test_fail_max_temp);
	if ($fail_count < $test_fail_max) {
		$p_num = $point_base - (($point_base * $percent) * $fail_count);
		$target_points = floor($p_num);
		$c_num = $currency_base - (($currency_base * $percent) * $fail_count);
		$target_currency = floor($c_num);
		$b_num = $bonus_currency_base - (($bonus_currency_base * $percent) * $fail_count);
		$target_bonus_currency = floor($b_num);
	} else {
		$target_points = 0;
	}
	
	if ($passed === 0 || $passed === '0') {
		go_add_post($user_id, $post_id, $status, 
		floor($update_percent * $target_points), 
		floor($update_percent * $target_currency), 
		floor($update_percent * $target_bonus_currency), 
		null, $page_id, null, null, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed, null);
	}
	die();
}

function go_inc_test_fail_count ($s_name, $test_fail_max = null) {
	if (!is_null($test_fail_max)) {
		$s_var = $_SESSION[$s_name];
		if (isset($s_var)) {
			if ($s_var < $test_fail_max) {
				$_SESSION[$s_name]++;
			} else if ($s_var > $test_fail_max) {
				unset($_SESSION[$s_name]);
			}
		}
	}
}

function unlock_stage () {
	$id = $_POST['task'];
	$status = $_POST['status'];
	$test_size = (int)$_POST['list_size'];
	$points_str = $_POST['points'];
	$points_array = explode(" ", $points_str);
	$point_base = (int)$points_array[$status];

	$choice = stripslashes($_POST['chosen_answer']);
	$type = $_POST['type'];
	if ($test_size > 1) {
		$all_test_choices = explode("#### ", $choice);
		$type_array = explode("### ", $type);
	} else {
		if ($type == 'checkbox') {
			$choice_array = explode("### ", $choice);
		}
	}
	
	$custom_fields = get_post_custom($id);

	switch ($status) {
		case (0):
			$test_stage = 'go_mta_test_lock_encounter';
			$custom_mod = $custom_fields['go_mta_test_encounter_lock_loot_mod'][0];
			$test_fail_name = 'test_encounter_fail_count';
			break;
		case (1):
			$test_stage = 'go_mta_test_lock_accept';
			$custom_mod = $custom_fields['go_mta_test_accept_lock_loot_mod'][0];
			$test_fail_name = 'test_accept_fail_count';
			break;
		case (2):
			$test_stage = 'go_mta_test_lock_completion';
			$custom_mod = $custom_fields['go_mta_test_completion_lock_loot_mod'][0];
			$test_fail_name = 'test_completion_fail_count';
			break;
		case (3):
			$test_stage = 'go_mta_test_lock_mastery';
			$custom_mod = $custom_fields['go_mta_test_mastery_lock_loot_mod'][0];
			$test_fail_name = 'test_mastery_fail_count';
			break;
		default:
			$custom_mod = 20;
	}

	if ($point_base !== 0) {
		$percent = $custom_mod / 100;
		$test_fail_max_temp = $point_base / ($point_base * $percent);
		$test_fail_max = ceil($test_fail_max_temp);
	}
	
	$user_ID = get_current_user_id();

	$test_c_array = $custom_fields[$test_stage][0];
	$test_c_uns = unserialize($test_c_array);
	$keys = $test_c_uns[1];
	$all_keys_array = array();
	for ($i = 0; $i < count($keys); $i++) {
		$all_keys_array[] = implode("### ", $keys[$i][1]);
	}
	$key = $all_keys_array[0];
	
	if ($type == 'checkbox' && !($list_size > 1)) {
		$key_str = preg_replace("/\s*\#\#\#\s*/", "### ", $key);
		$key_array = explode("### ", $key_str);
	}

	$fail_question_ids = array();
	if ($test_size > 1) {
		$total_matches = 0;
		for ($i = 0; $i < $test_size; $i++) {
			if ($type_array[$i] == 'radio') {
				if (strtolower($all_keys_array[$i]) == strtolower($all_test_choices[$i])) {
					$total_matches++;
				} else {
					if (!in_array("#go_test_{$i}", $fail_question_ids)) {
						array_push($fail_question_ids, "#go_test_{$i}");
					}
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

				if ($match_count == count($k_array) && $match_count == count($c_array)) {
					$total_matches++;
				} else {
					if (!in_array("#go_test_{$i}", $fail_question_ids)) {
						array_push($fail_question_ids, "#go_test_{$i}");
					}
				}
			}
		}

		if ($total_matches == $test_size) {
			echo 1;
			die();
		} else {
			go_inc_test_fail_count($test_fail_name, $test_fail_max);
			if (!empty($fail_question_ids)) {
				$fail_id_str = implode(", ", $fail_question_ids);
				echo $fail_id_str;
			} else {
				echo 0;
			}
			die();
		}
	} else {

		if ($type == 'radio') {
			if (strtolower($choice) == strtolower($key)) {
				echo 1;
				die();
			} else {
				go_inc_test_fail_count($test_fail_name, $test_fail_max);
				echo 0;
				die();
			}
		} else if ($type == 'checkbox') {
			$key_match = 0;
			for ($i = 0; $i < count($key_array); $i++) {
				for ($x = 0; $x < count($choice_array);  $x++) {
					if (strtolower($choice_array[$x]) == strtolower($key_array[$i])) {
						$key_match++;
						break;
					}
				}
			}
			if ($key_match == count($key_array) && $key_match == count($choice_array)) {
				echo 1;
				die();
			} else {
				go_inc_test_fail_count($test_fail_name, $test_fail_max);
				echo 0;
				die();
			}
		}
	}
	
	die();
}

function task_change_stage() {
	global $wpdb;
	$unix_now = current_time('timestamp'); // Current unix timestamp
	$post_id = $_POST['post_id']; // Post id posted from ajax function
	$user_id = $_POST['user_id']; // User id posted from ajax function
	$status = (int)$_POST['status']; // Task's status posted from ajax function
	$page_id = $_POST['page_id']; // Page id posted from ajax function
	$admin_name = $_POST['admin_name'];
	$undo = $_POST['undo']; // Boolean which determines if the button clicked is an undo button or not (True or False)
	$pass = $_POST['pass']; // Contains the user-entered admin password
	$url = $_POST['url']; // Contains user-entered url
	$repeat_button = $_POST['repeat']; // Boolean which determines if the task is repeatable or not (True or False)
	$points_array = $_POST['points']; // Serialized array of points rewarded for each stage
	$currency_array = $_POST['currency']; // Serialized array of currency rewarded for each stage
	$bonus_currency_array = $_POST['bonus_currency']; // Serialized array of bonus currency awarded for each stage
	$update_percent = $_POST['update_percent']; // Float which is used to modify values saved to database
	$date_update_percent = $update_percent; // Copy update percent to use in special cases
	$chain_name = $_POST['chain_name']; // String which is used to display next task in a quest chain
	$next_post_id_in_chain = $_POST['next_post_id_in_chain']; // Integer which is used to display next task in a quest chain
	$last_in_chain = $_POST['last_in_chain']; // Boolean which determines if the current quest is last in chain
	$number_of_stages = $_POST['number_of_stages']; // Integer with number of stages in the task
	
	$go_table_ind = $wpdb->prefix.'go';
	$task_count = $wpdb->get_var("SELECT `count` FROM ".$go_table_ind." WHERE post_id = $post_id AND uid = $user_id");
	
	$custom_fields = get_post_custom($post_id); // Just gathering some data about this task with its post id
	$mastery_active = !$custom_fields['go_mta_task_mastery'][0]; // whether or not the mastery stage is active
	$repeat = $custom_fields['go_mta_task_repeat'][0]; // Whether or not you can repeat the task

	$e_admin_lock = unserialize($custom_fields['go_mta_encounter_admin_lock'][0]);
	$e_is_locked = $e_admin_lock[0];
	if ($e_is_locked === 'true') {
		$e_pass_lock = $e_admin_lock[1];
	}
	$a_admin_lock = unserialize($custom_fields['go_mta_accept_admin_lock'][0]);
	$a_is_locked = $a_admin_lock[0];
	if ($a_is_locked === 'true') {
		$a_pass_lock = $a_admin_lock[1];
	}
	$c_admin_lock = unserialize($custom_fields['go_mta_completion_admin_lock'][0]);
	$c_is_locked = $c_admin_lock[0];
	if ($c_is_locked === 'true') {
		$c_pass_lock = $c_admin_lock[1];
	}
	$m_admin_lock = unserialize($custom_fields['go_mta_mastery_admin_lock'][0]);
	$m_is_locked = $m_admin_lock[0];
	if ($m_is_locked === 'true') {
		$m_pass_lock = $m_admin_lock[1];
	}
	$r_admin_lock = unserialize($custom_fields['go_mta_repeat_admin_lock'][0]);
	$r_is_locked = $r_admin_lock[0];
	if ($r_is_locked === 'true') {
		$r_pass_lock = $r_admin_lock[1];
	}

	$e_url_is_locked = (!empty($custom_fields['go_mta_encounter_url_key'][0]) ? true : false);
	$a_url_is_locked = (!empty($custom_fields['go_mta_accept_url_key'][0]) ? true : false);
	$c_url_is_locked = (!empty($custom_fields['go_mta_completion_url_key'][0]) ? true : false);
	$m_url_is_locked = (!empty($custom_fields['go_mta_mastery_url_key'][0]) ? true : false);

	if (!empty($pass)) {
		if ($status == 4) {
			$temp_status = $status;
		} else {
			$temp_status = $status - 1;
		}
		switch ($temp_status) {
			case (1):
				$pass_lock = $e_pass_lock;
				break;
			case (2):
				$pass_lock = $a_pass_lock;
				break;
			case (3):
				$pass_lock = $c_pass_lock;
				break;
			case (4):
				if ($repeat === 'on') {
					$pass_lock = $r_pass_lock;
				} else {
					$pass_lock = $m_pass_lock;
				}
				break;
		}
		if (!empty($pass_lock) && $pass !== $pass_lock) {
			echo 0;
			die();
		}
	}

	$test_e_active = $custom_fields['go_mta_test_encounter_lock'][0];
	$test_a_active = $custom_fields['go_mta_test_accept_lock'][0];
	$test_c_active = $custom_fields['go_mta_test_completion_lock'][0];

	if ($test_e_active) {
		$test_e_array = go_get_test_meta_content($custom_fields, 'encounter');
		$test_e_returns = $test_e_array[0];
		$test_e_num = $test_e_array[1];
		$test_e_all_questions = $test_e_array[2][0];
		$test_e_all_types = $test_e_array[2][1];
		$test_e_all_answers = $test_e_array[2][2];
		$test_e_all_keys = $test_e_array[2][3];
	}
	$encounter_upload = $custom_fields['go_mta_encounter_upload'][0];

	if ($test_a_active) {
		$test_a_array = go_get_test_meta_content($custom_fields, 'accept');
		$test_a_returns = $test_a_array[0];
		$test_a_num = $test_a_array[1];
		$test_a_all_questions = $test_a_array[2][0];
		$test_a_all_types = $test_a_array[2][1];
		$test_a_all_answers = $test_a_array[2][2];
		$test_a_all_keys = $test_a_array[2][3];
	}
	$accept_upload = $custom_fields['go_mta_accept_upload'][0];

	if ($test_c_active) {
		$test_c_array = go_get_test_meta_content($custom_fields, 'completion');
		$test_c_returns = $test_c_array[0];
		$test_c_num = $test_c_array[1];
		$test_c_all_questions = $test_c_array[2][0];
		$test_c_all_types = $test_c_array[2][1];
		$test_c_all_answers = $test_c_array[2][2];
		$test_c_all_keys = $test_c_array[2][3];
	}
	$completion_message = $custom_fields['go_mta_complete_message'][0]; // Completion Message
	$completion_upload = $custom_fields['go_mta_completion_upload'][0];
	
	if ($mastery_active) {
		$test_m_active = $custom_fields['go_mta_test_mastery_lock'][0];

		if ($test_m_active) {
			$test_m_array = go_get_test_meta_content($custom_fields, 'mastery');
			$test_m_returns = $test_m_array[0];
			$test_m_num = $test_m_array[1];
			$test_m_all_questions = $test_m_array[2][0];
			$test_m_all_types = $test_m_array[2][1];
			$test_m_all_answers = $test_m_array[2][2];
			$test_m_all_keys = $test_m_array[2][3];
		}
		$mastery_message = $custom_fields['go_mta_mastery_message'][0];
		$mastery_upload = $custom_fields['go_mta_mastery_upload'][0];

		if ($repeat == 'on') {
			if ($custom_fields['go_mta_repeat_amount'][0]) {
				$repeat_amount = $custom_fields['go_mta_repeat_amount'][0];
			} else {
				$repeat_amount = 0;
			}
			$repeat_message = $custom_fields['go_mta_repeat_message'][0];
			$repeat_upload = $custom_fields['go_mta_repeat_upload'][0];
		}
	}

	$description = $custom_fields['go_mta_quick_desc'][0];
	
	// Array of badge switch and badges associated with a stage
	// E.g. array(true, array(263, 276)) means that stage has badges (true) and the badge IDs are 263 and 276
	$stage_badges = array(
		unserialize($custom_fields['go_mta_stage_one_badge'][0]),
		unserialize($custom_fields['go_mta_stage_two_badge'][0]),
		unserialize($custom_fields['go_mta_stage_three_badge'][0]),
		unserialize($custom_fields['go_mta_stage_four_badge'][0]),
		unserialize($custom_fields['go_mta_stage_five_badge'][0]),
	);

	// Stage Stuff
	$content_post = get_post($post_id);
	$task_content = $content_post->post_content;
	if ($task_content == '') {
		$accept_message = $custom_fields['go_mta_accept_message'][0]; // Completion Message
	} else {
		$accept_message = $content_post->post_content;
	}
	$table_name_go = $wpdb->prefix . "go";

	// Tests failed.
	if (isset($_SESSION['test_encounter_fail_count'])) {
		$e_fail_count = $_SESSION['test_encounter_fail_count'];	
	} else {
		$e_fail_count = 0;
	}

	if (isset($_SESSION['test_accept_fail_count'])) {
		$a_fail_count = $_SESSION['test_accept_fail_count'];	
	} else {
		$a_fail_count = 0;
	}

	if (isset($_SESSION['test_completion_fail_count'])) {
		$c_fail_count = $_SESSION['test_completion_fail_count'];	
	} else {
		$c_fail_count = 0;
	}

	if (isset($_SESSION['test_mastery_fail_count'])) {
		$m_fail_count = $_SESSION['test_mastery_fail_count'];
	} else {
		$m_fail_count = 0;
	}

	// Tests passed.
	if (isset($_SESSION['test_encounter_passed'])) {
		$e_passed = $_SESSION['test_encounter_passed'];	
	} else {
		$e_passed = 0;
	}

	if (isset($_SESSION['test_accept_passed'])) {
		$a_passed = $_SESSION['test_accept_passed'];	
	} else {
		$a_passed = 0;
	}

	if (isset($_SESSION['test_completion_passed'])) {
		$c_passed = $_SESSION['test_completion_passed'];	
	} else {
		$c_passed = 0;
	}

	if (isset($_SESSION['test_mastery_passed'])) {
		$m_passed = $_SESSION['test_mastery_passed'];
	} else {
		$m_passed = 0;
	}
	
	$future_modifier = unserialize($custom_fields['go_mta_time_modifier'][0]);
	if (!empty($future_modifier)) {
		$accept_timestamp = strtotime(str_replace('@', ' ', $wpdb->get_var("SELECT timestamp FROM {$wpdb->prefix}go WHERE uid='{$user_ID}' AND post_id='{$id}'")));
		$days = $future_modifier['days'] ;
		$hours = $future_modifier['hours'];
		$minutes = $future_modifier['minutes'];
		$future_time = strtotime("{$days} days", 0) + strtotime("{$hours} hours", 0) + strtotime("{$minutes} minutes", 0) + $accept_timestamp;
		
		if ($unix_now >= $future_time && !empty($accept_timestamp)){
			$future_update_percent = max($update_percent * (float) ($future_modifier['percentage']/100), 0);
		}
	} else {
		$future_update_percent = 1;	
	}
	
		
	// if the button pressed IS the repeat button...
	if ($repeat_button == 'on') {
		if ($undo == 'true' || $undo === true) {
			if ($task_count > 0) {
				go_add_post($user_id, $post_id, $status, 
				-floor(($update_percent * $points_array[$status])), 
				-floor(($update_percent * $currency_array[$status])), 
				-floor(($update_percent * $bonus_currency[$status])), null, $page_id, $repeat_button, -1, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed);
			} else {
				go_add_post($user_id, $post_id, ($status - 1), 
				-floor(($update_percent * $points_array[$status - 1])), 
				-floor(($update_percent * $currency_array[$status - 1])), 
				-floor(($update_percent * $bonus_currency[$status - 1])), null, $page_id, $repeat_button, 0, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed);
				if ($stage_badges[$status][0] == 'true') {
					foreach ($stage_badges[$status][1] as $badge_id) {
						go_remove_badge($user_id, $badge_id);
					}
				}
			}
		} else {
			// if repeat is on and undo is not hit...
			go_add_post($user_id, $post_id, $status, 
			floor(($update_percent * $points_array[$status])), 
			floor(($update_percent * $currency_array[$status])), 
			floor(($update_percent * $bonus_currency[$status])), null, $page_id, $repeat_button, 1, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed, $url);
			if ($stage_badges[$status][0] == 'true') {
				foreach ($stage_badges[$status][1] as $badge_id) {
					do_shortcode("[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']");
				}
			}
		}	
	// if the button pressed is NOT the repeat button...
	} else {
		$db_status = (int) $wpdb->get_var("SELECT `status` FROM ".$table_name_go." WHERE uid = $user_id AND post_id = $post_id");
		if ($db_status == 0 || ($db_status < $status)) {
			if ($undo == 'true' || $undo === true) {
				if ($task_count > 0) {
					go_add_post($user_id, $post_id, $status, 
					-floor(($update_percent * $points_array[$status - 1])), 
					-floor(($update_percent * $currency_array[$status - 1])), 
					-floor(($update_percent * $bonus_currency[$status - 1])), null, $page_id, $repeat_button, -1, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed);
				} else {
					go_add_post($user_id, $post_id, ($status - 2), 
					-floor(($update_percent * $points_array[$status - 2])), 
					-floor(($update_percent * $currency_array[$status - 2])), -floor(($update_percent * $bonus_currency[$status - 2])), null, $page_id, $repeat_button, 0, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed);
					if ($stage_badges[$status - 2][0] == 'true') {
						foreach ($stage_badges[$status - 2][1] as $badge_id) {
							go_remove_badge($user_id, $badge_id);
						}
					}
				}
			} else {
				$update_time = ($status == 2) ? true : false;
				
				go_add_post($user_id, $post_id, $status, 
				floor(($update_percent * $points_array[$status - 1])), 
				floor(($update_percent * $currency_array[$status - 1])), 
				floor(($update_percent * $bonus_currency[$status - 1])), null, $page_id, $repeat_button, 0, $e_fail_count, $a_fail_count, $c_fail_count, $m_fail_count, $e_passed, $a_passed, $c_passed, $m_passed, $url, $update_time); 
				if ($stage_badges[$status - 1][0] == 'true') {
					foreach ($stage_badges[$status - 1][1] as $badge_id) {
						do_shortcode("[go_award_badge id='{$badge_id}' repeat='off' uid='{$user_id}']");
					}
				}
			}
		}
	}
	
	// redefine the status and task_count because they have been updated as soon as the above go_add_post() calls are made.
	$status = $wpdb->get_var("SELECT `status` FROM ".$table_name_go." WHERE uid = $user_id AND post_id = $post_id");
	$task_count = $wpdb->get_var("SELECT `count` FROM ".$go_table_ind." WHERE post_id = $post_id AND uid = $user_id");
	
	switch ($status) {
		case (0):
			$db_task_stage_upload_var = 'e_uploaded';
			break;
		case (1):
			$db_task_stage_upload_var = 'a_uploaded';
			break;
		case (2):
			$db_task_stage_upload_var = 'c_uploaded';
			break;
		case (3):
			$db_task_stage_upload_var = 'm_uploaded';
			break;
		case (4):
			$db_task_stage_upload_var = 'r_uploaded';
			break;
	}
	if (!empty($db_task_stage_upload_var)) {
		$is_uploaded = $wpdb->get_var("SELECT {$db_task_stage_upload_var} FROM {$go_table_ind} WHERE uid = {$user_id} AND post_id = {$post_id}");
	} else {
		$is_uploaded = 0;
	}

	// Controls output of the page, after the #go_button has been pressed for the first time while viewing the page.
	switch ($status) {
		case 1:
			echo '<div id="new_content">'.'<div class="go_stage_message">'.do_shortcode(wpautop($accept_message, false)).'</div>';
			if ($test_e_active) {
				echo "<p id='go_test_error_msg' style='color: red;'></p>";
				if ($test_e_num > 1) {
					for ($i = 0; $i < $test_e_num; $i++) {
						echo do_shortcode("[go_test type='".$test_e_all_types[$i]."' question='".$test_e_all_questions[$i]."' possible_answers='".$test_e_all_answers[$i]."' key='".$test_e_all_keys[$i]."' test_id='".$i."' total_num='".$test_e_num."']");
					}
					echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				} else {
					echo do_shortcode("[go_test type='".$test_e_all_types[0]."' question='".$test_e_all_questions[0]."' possible_answers='".$test_e_all_answers[0]."' key='".$test_e_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				}
			}
			if ($encounter_upload) {
				echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
			}
			echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
			if ($e_is_locked === 'true' && !empty($e_pass_lock)) {
				echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
			} else if ($e_url_is_locked === true) {
				echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
			}
			echo "<button id='go_button' status='2' onclick='task_stage_change(this);'";
			if ($e_is_locked === 'true' && empty($e_pass_lock)) {
				echo "admin_lock='true'";
			}
			echo ">".go_return_options('go_second_stage_button')."</button>
			<button id='go_abandon_task' onclick='go_task_abandon();this.disabled = true;'>".get_option("go_abandon_stage_button", "Abandon")."</button></div>";
			break;
		case 2:
			echo '<div id="new_content">'.'<div class="go_stage_message">'.do_shortcode(wpautop($accept_message, false)).'</div>';
			if ($test_a_active) {
				echo "<p id='go_test_error_msg' style='color: red;'></p>";
				if ($test_a_num > 1) {
					for ($i = 0; $i < $test_a_num; $i++) {
						echo do_shortcode("[go_test type='".$test_a_all_types[$i]."' question='".$test_a_all_questions[$i]."' possible_answers='".$test_a_all_answers[$i]."' key='".$test_a_all_keys[$i]."' test_id='".$i."' total_num='".$test_a_num."']");
					}
					echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				} else {
					echo do_shortcode("[go_test type='".$test_a_all_types[0]."' question='".$test_a_all_questions[0]."' possible_answers='".$test_a_all_answers[0]."' key='".$test_a_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
				}
			}
			if ($accept_upload) {
				echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
			}
			echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
			if ($a_is_locked === 'true' && !empty($a_pass_lock)) {
				echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
			} else if ($a_url_is_locked === true) {
				echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
			}
			echo "<button id='go_button' status='3' onclick='task_stage_change(this);'";
			if ($a_is_locked === 'true' && empty($a_pass_lock)) {
				echo "admin_lock='true'";
			}
			echo '>'.go_return_options('go_third_stage_button').'</button> <button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button></div>';
			break;
		case 3:
			echo '<div class="go_stage_message">'.do_shortcode(wpautop($accept_message, false)).'</div>'.'<div id="new_content"><div class="go_stage_message">'
			.do_shortcode(wpautop($completion_message)).'</div>';
			if ($mastery_active) {
				if ($test_c_active) {
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					if ($test_c_num > 1) {
						for ($i = 0; $i < $test_c_num; $i++) {
							echo do_shortcode("[go_test type='".$test_c_all_types[$i]."' question='".$test_c_all_questions[$i]."' possible_answers='".$test_c_all_answers[$i]."' key='".$test_c_all_keys[$i]."' test_id='".$i."' total_num='".$test_c_num."']");
						}
						echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					} else {
						echo do_shortcode("[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					}
				}
				if ($completion_upload) {
					echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
				}
				echo "<p id='go_stage_error_msg' style='display: none; color: red;'></p>";
				if ($c_is_locked === 'true' && !empty($c_pass_lock)) {
					echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
				} else if ($c_url_is_locked === true) {
					echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
				}
				echo "<button id='go_button' status='4' onclick='task_stage_change(this);'";
				if ($c_is_locked === 'true' && empty($c_pass_lock)) {
					echo "admin_lock='true'";
				}
				echo '>'.go_return_options('go_fourth_stage_button').'</button> 
				<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
				if ($next_post_id_in_chain != 0 && $last_in_chain !== 'true') {
					echo '<div class="go_chain_message">Next '.strtolower(go_return_options('go_tasks_name')).' in '.$chain_name.': <a href="'.get_permalink($next_post_id_in_chain).'">'.get_the_title($next_post_id_in_chain).'</a></div>';
				} else {
					echo '<div class="go_chain_message">'.$custom_fields['go_mta_final_chain_message'][0].'</div>';	
				}
				echo "</div>";
			} else {
				if ($test_c_active) {
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					if ($test_c_num > 1) {
						for ($i = 0; $i < $test_c_num; $i++) {
							echo do_shortcode("[go_test type='".$test_c_all_types[$i]."' question='".$test_c_all_questions[$i]."' possible_answers='".$test_c_all_answers[$i]."' key='".$test_c_all_keys[$i]."' test_id='".$i."' total_num='".$test_c_num."']");
						}
						echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					} else {
						echo do_shortcode("[go_test type='".$test_c_all_types[0]."' question='".$test_c_all_questions[0]."' possible_answers='".$test_c_all_answers[0]."' key='".$test_c_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					}
				}
				if ($completion_upload) {
					echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
				}
				echo '<span id="go_button" status="4" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
				if ($next_post_id_in_chain != 0 && $last_in_chain !== 'true') {
					echo '<div class="go_chain_message">Next '.strtolower(go_return_options('go_tasks_name')).' in '.$chain_name.': <a href="'.get_permalink($next_post_id_in_chain).'">'.get_the_title($next_post_id_in_chain).'</a></div>';
				} else {
					echo '<div class="go_chain_message">'.$custom_fields['go_mta_final_chain_message'][0].'</div>';	
				}
				echo "</div>";
			}
			break;
		case 4:
			echo '<div class="go_stage_message">'.do_shortcode(wpautop($accept_message, false)).'</div><div class="go_stage_message">'.do_shortcode(wpautop($completion_message)).
			'</div><div id="new_content"><div class="go_stage_message">'.do_shortcode(wpautop($mastery_message)).'</div>';
			// if the task can be repeated...
			if ($repeat == 'on') {
				// if the number of times that the page has been repeated is less than the total amount of repeats allowed OR if the 
				// total repeats allowed is equal to zero (infinte amount allowed)...
				if ($task_count < $repeat_amount || $repeat_amount == 0) {
					if ($task_count == 0) {
						if ($test_m_active) {
							echo "<p id='go_test_error_msg' style='color: red;'></p>";
							if ($test_m_num > 1) {
								for ($i = 0; $i < $test_m_num; $i++) {
									echo do_shortcode("[go_test type='".$test_m_all_types[$i]."' question='".$test_m_all_questions[$i]."' possible_answers='".$test_m_all_answers[$i]."' key='".$test_m_all_keys[$i]."' test_id='".$i."' total_num='".$test_m_num."']");
								}
								echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							} else {
								echo do_shortcode("[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
							}
						}
						if ($mastery_upload) {
							echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
						}
						echo '
							<div id="repeat_quest">
								<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
									.do_shortcode(wpautop($repeat_message)).
									"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
						if ($m_is_locked === 'true' && !empty($m_pass_lock)) {
							echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
						} else if ($m_url_is_locked === true) {
							echo "<input id='go_url_key' type='url' placeholder='Enter Url'/></br>";
						}
						echo "<button id='go_button' status='4' onclick='go_repeat_hide(this);' repeat='on'";
						if ($m_is_locked === 'true' && empty($m_pass_lock)) {
							echo "admin_lock='true'";
						}
						echo '>'.go_return_options('go_fourth_stage_button')." Again". 
									'</button>
									<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
								</div>
								<div id="go_repeat_unclicked">
									<button id="go_button" status="4" onclick="go_repeat_replace();">'
										.get_option('go_fifth_stage_button').
									'</button>
									<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
								</div>
							</div>
						';
					} else {
						if ($repeat_upload) {
							echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
						}
						echo '
							<div id="repeat_quest">
								<div id="go_repeat_clicked" style="display:none;"><div class="go_stage_message">'
									.do_shortcode(wpautop($repeat_message)).
									"</div><p id='go_stage_error_msg' style='display: none; color: red;'></p>";
						if ($r_is_locked === 'true' && !empty($r_pass_lock)) {
							echo "<input id='go_pass_lock' type='password' placeholder='Enter Password'/></br>";
						}
						echo "<button id='go_button' status='4' onclick='go_repeat_hide(this);' repeat='on'";
						if ($r_is_locked === 'true' && empty($r_pass_lock)) {
							echo "admin_lock='true'";
						}
						echo '>'.go_return_options('go_fourth_stage_button')." Again". 
									'</button>
									<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
								</div>
								<div id="go_repeat_unclicked">
									<button id="go_button" status="4" onclick="go_repeat_replace();">'
										.get_option('go_fifth_stage_button').
									'</button>
									<button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>
								</div>
							</div>
						';
					}
				} else {
					echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
				}
			} else {
				if ($test_m_active) {
					echo "<p id='go_test_error_msg' style='color: red;'></p>";
					if ($test_m_num > 1) {
						for ($i = 0; $i < $test_m_num; $i++) {
							echo do_shortcode("[go_test type='".$test_m_all_types[$i]."' question='".$test_m_all_questions[$i]."' possible_answers='".$test_m_all_answers[$i]."' key='".$test_m_all_keys[$i]."' test_id='".$i."' total_num='".$test_m_num."']");
						}
						echo "<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					} else {
						echo do_shortcode("[go_test type='".$test_m_all_types[0]."' question='".$test_m_all_questions[0]."' possible_answers='".$test_m_all_answers[0]."' key='".$test_m_all_keys[0]."' test_id='0']")."<div class='go_test_submit_div' style='display: none;'><button class='go_test_submit'>Submit</button></div>";
					}
				}
				if ($mastery_upload) {
					echo do_shortcode("[go_upload is_uploaded={$is_uploaded} status={$status} user_id={$user_id} post_id={$post_id}]")."<br/>";
				}
				echo '<span id="go_button" status="4" repeat="on" style="display:none;"></span><button id="go_back_button" onclick="task_stage_change(this);" undo="true">Undo</button>';
			}
			if($next_post_id_in_chain != 0 && $last_in_chain !== 'true'){
				echo '<div class="go_chain_message"><p>Next '.strtolower(go_return_options('go_tasks_name')).' in '.$chain_name.': <a href="'.get_permalink($next_post_id_in_chain).'">'.get_the_title($next_post_id_in_chain).'</a></div>';
			}else{
				echo '<div class="go_chain_message">'.$custom_fields['go_mta_final_chain_message'][0].'</div>';	
			}
			echo '</div>';
	}
	die();
}

function go_display_rewards ($user_id, $points, $currency, $bonus_currency, $date_percent = 1, $number_of_stages = 4, $update = false) {
	if (!is_null($number_of_stages) && (!is_null($points) || !is_null($currency) || !is_null($bonus_currency)) && !$update) {
		echo "<div class='go_task_rewards' style='margin: 6px 0px 6px 0px;'><strong>Rewards</strong><br/>";
		$p_name = go_return_options('go_points_name');
		$c_name = go_return_options('go_currency_name');
		$bc_name = go_return_options('go_bonus_currency_name');
		$u_bonuses = go_return_bonus_currency($user_id);
		$u_penalties = go_return_penalty($user_id);
		if ($date_percent !== 1 && !empty($date_percent)) {
			$rewards_array = array($points, $currency, $bonus_currency);
			$p_array = $rewards_array[0];
			$c_array = $rewards_array[1];
			$bc_array = $rewards_array[2];
		} else {
			$p_array = $points;
			$c_array = $currency;
			$bc_array = $bonus_currency;
		}
		for ($i = 0; $i < $number_of_stages; $i++) {
			$mod_array = go_return_multiplier($user_id, floor($p_array[$i] * $date_percent), floor($c_array[$i] * $date_percent), floor($bc_array[$i] * $date_percent), $u_bonuses, $u_penalties);
			$stage_name = '';
			switch ($i) {
				case 0:
					$stage_name = go_return_options('go_first_stage_name');
					break;
				case 1:
					$stage_name = go_return_options('go_second_stage_name');
					break;
				case 2:
					$stage_name = go_return_options('go_third_stage_name');
					break;
				case 3:
					$stage_name = go_return_options('go_fourth_stage_name');
					break;
				case 4:
					$stage_name = go_return_options('go_fifth_stage_name');
					break;
			}
			$output = "{$stage_name} - ".(!empty($mod_array[0]) && !empty($p_name) ? "{$mod_array[0]} {$p_name}" : '').
				" ".(!empty($mod_array[1]) && !empty($c_name) ? "{$mod_array[1]} {$c_name}" : '').
				" ".(!empty($mod_array[2]) && !empty($bc_name) ? "{$mod_array[2]} {$bc_name}" : '').
				"<br/>";
			echo $output;
		}
		echo '</div>';
	} 
}
?>