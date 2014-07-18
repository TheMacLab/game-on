<?php
function go_stats_overlay(){ 
	echo '<div id="go_stats_page_black_bg" style="display:none !important;"></div><div id="go_stats_white_overlay" style="display:none;"></div>';
}
function go_admin_bar_stats(){ 
 	global $wpdb;
	$table_name_go = $wpdb->prefix . "go";
	if($_POST['uid']){
		$current_user = get_userdata( $_POST['uid'] );
		}else{
 	$current_user = wp_get_current_user();
	}
	?><input type="hidden" id="go_stats_hidden_input" value="<?php echo $_POST['uid'] ?>"/><?php
	$user_fullname = $current_user->first_name.' '.$current_user->last_name;
	$user_login =  $current_user->user_login;
	$user_display_name = $current_user->display_name;
	$user_id = $current_user->ID ;
	$user_website = $current_user->user_url;
 	$current_user_id = $current_user->ID;
	$user_avatar = get_avatar($current_user_id, 142);
	/* option names */
	$points_name = go_return_options('go_points_name');
	$currency_name = go_return_options('go_currency_name');
	$bonus_currency_name = go_return_options('go_bonus_currency_name');
	$penalty_name = go_return_options('go_penalty_name');
	/* user pnc */
	go_get_rank($current_user_id);
	$current_points = go_return_points($current_user_id);
	$current_currency = go_return_currency($current_user_id);
	$current_bonus_currency = go_return_bonus_currency($current_user_id);
	$current_penalty = go_return_penalty($current_user_id);
	global $current_rank;
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	$display_current_rank_points = $current_points - $current_rank_points;
	$display_next_rank_points = $next_rank_points - $current_rank_points;
	$percentage_of_level = ($display_current_rank_points/$display_next_rank_points) * 100;
	?>
	<div id='go_stats_lay'>
		<div id='go_stats_gravatar'><?php echo $user_avatar;?></div>
		<div id='go_stats_header'>
			<div id='go_stats_user_info'>
				<?php echo "{$user_fullname}<br/>{$user_login}<br/><a href='{$user_website}'>{$user_display_name}</a><br/><div id='go_stats_user_points'><span id='go_stats_user_points_value'>{$current_points}</span> {$points_name}</div><div id='go_stats_user_currency'><span id='go_stats_user_currency_value'>{$current_currency}</span> {$currency_name}</div><div id='go_stats_user_bonus_currency'><span id='go_stats_user_bonus_currency_value'>{$current_bonus_currency}</span> {$bonus_currency_name}</div>{$current_penalty} {$penalty_name}"; ?>
			</div>
			<div id='go_stats_user_rank'><?php echo $current_rank;?></div>
			<div id='go_stats_user_progress'>
				<div id="go_stats_progress_text_wrap">
					<div id='go_stats_progress_text'><?php echo "<span id='go_stats_user_progress_top_value'>{$display_current_rank_points}</span>/<span id='go_stats_user_progress_bottom_value'>{$display_next_rank_points}</span>";?></div>
				</div>
				<div id='go_stats_progress_fill' style='width: <?php echo $percentage_of_level;?>%;<?php $color = barColor($current_bonus_currency); echo "background-color: {$color}";if($percentage_of_level >= 98){echo "border-radius: 15px";}?>'></div></div>
			<div id='go_stats_user_tabs'>
            <!--
				<a href='javascript:;' id="go_stats_body_progress" class='go_stats_body_selectors' tab='progress'>
					WEEKLY PROGRESS
				</a> | 
            -->
            	<?php $is_admin = current_user_can('manage_options'); if($is_admin){ ?>
               		<a href='javascript:;' id='go_stats_admin_help' class='go_stats_body_selectors' tab='help'>
                    	HELP
                    </a> |
                <?php } ?>
				<a href='javascript:;' id="go_stats_body_tasks" class='go_stats_body_selectors' tab='tasks'>
					<?php echo strtoupper(go_return_options('go_tasks_plural_name'));?>
				</a> | 
				<a href='javascript:;' id="go_stats_body_items" class='go_stats_body_selectors' tab='items'>
					INVENTORY
				</a> | 
				<a href='javascript:;' id="go_stats_body_rewards" class='go_stats_body_selectors' tab='rewards'>
					REWARDS
				</a> | 
				<a href='javascript:;' id="go_stats_body_badges" class='go_stats_body_selectors' tab='badges'>
					BADGES
				</a> | 
				<a href='javascript:;' id="go_stats_body_leaderboard" class='go_stats_body_selectors' tab='leaderboard'>
					LEADERBOARD
				</a>
			</div>
		</div>
		<div id='go_stats_body'></div>
	</div>
	<?php 
	die();

}
function go_stats_task_list(){
	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	if(!empty($_POST['user_id'])){
		$user_id = $_POST['user_id'];
	}else{
		$user_id = get_current_user_id();
	}
	$is_admin = current_user_can('manage_options');
	$task_list = $wpdb->get_results($wpdb->prepare("SELECT status,post_id,count FROM {$go_table_name} WHERE uid=%d AND (status = %d OR status = 2 OR status = 3 OR status = 4) ORDER BY id DESC", $user_id, 1));
	$counter = 1;
	?>
	<ul id='go_stats_tasks_list_left' <?php if($is_admin){echo "class='go_stats_tasks_list_admin'";}?>
		<?php
		if ($is_admin){
			go_task_opt_help('admin_stats', 'admin_stats', 'http://google.com');	
		}
		foreach($task_list as $task){
			?>
			<li class='go_stats_task <?php if($counter%2 == 0){echo 'go_stats_right_task';}?>'>
				<a href='<?php echo get_permalink($task->post_id);?>' target='_blank'>
					<?php echo get_the_title($task->post_id);?>
				</a>
				<?php
				if($is_admin){
				?>
					<input type='text' class='go_stats_task_admin_message' id='go_stats_task_<?php echo $task->post_id ?>_message' name='go_stats_task_admin_message' placeholder='See me'/>
                    <button class='go_stats_task_admin_submit' task='<?php echo $task->post_id;?>'></button>
                    <div class='go_stats_task_status_wrap'>
				<?php 
				}
					for($i = 5; $i > 0; $i--){
						if($is_admin){ 
							?>
							<a href='#'>
							<?php 
						}
						?>
						<div task='<?php echo $task->post_id;?>' stage='<?php echo $i;?>' class='go_stats_task_status <?php if($task->status >= $i || $task->count >= 1){echo 'completed';} ?>' <?php if($task->count >=1){echo "count='{$task->count}'"; }?>><?php if($i == 5 && $task->count > 1){echo $task->count;}?></div>
						<?php 
						if ($is_admin){
							?>
							</a>
							<?php
						}
					}
				?>
                </div>
			</li>
			<?php
			$counter++;
		}
		?>
	</ul>
    <ul id='go_stats_tasks_list_right'></ul>
	<?php
	
	if(!$is_admin){
	?>
    	<script type='text/javascript'>
			jQuery('.go_stats_right_task').appendTo('#go_stats_tasks_list_right');
		</script>
    <?php	
	}
	
	die();
}

function go_stats_move_stage(){
	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	if(!empty($_POST['user_id'])){
		$user_id = $_POST['user_id'];
	}else{
		$user_id = get_current_user_id();
	}
	$task_id = $_POST['task_id'];
	$status = $_POST['status'];
	$count = $_POST['count'];
	$message = $_POST['message'];
	$custom_fields = get_post_custom($task_id);
	$rewards = unserialize($custom_fields['go_presets'][0]);
	$current_status = $wpdb->get_var($wpdb->prepare("SELECT status FROM {$go_table_name} WHERE uid=%d AND post_id=%d",$user_id,$task_id));
	$page_id = $wpdb->get_var($wpdb->prepare("SELECT page_id FROM {$go_table_name} WHERE uid=%d AND post_id=%d", $user_id, $task_id));
	
	$changed = array('type' => 'json', 'points' => 0, 'currency' => 0, 'bonus_currency' => 0);
	 
	for($count; $count > 0; $count--){
		go_add_post($user_id, $task_id, $current_status, -$rewards['points'][$current_status], -$rewards['currency'][$current_status], $page_id, 'on', -1, null, null, null, null, -$rewards['bonus_currency'][$current_status]);
		
		$changed['points'] += -$rewards['points'][$current_status];
		$changed['currency'] += -$rewards['currency'][$current_status];
		$changed['bonus_currency'] += -$rewards['bonus_currency'][$current_status];
	}
	
	while($current_status != $status){
		if($current_status > $status){
			$current_status--;
			
			go_add_post($user_id, $task_id, $current_status, -$rewards['points'][$current_status], -$rewards['currency'][$current_status], $page_id, null, null, null, null, null, null, -$rewards['bonus_currency'][$current_status]);
			
			$changed['points'] += -$rewards['points'][$current_status];
			$changed['currency'] += -$rewards['currency'][$current_status];
			$changed['bonus_currency'] += -$rewards['bonus_currency'][$current_status];
			
		}elseif($current_status < $status){
			$current_status++;
			$current_count = $wpdb->get_var($wpdb->prepare("SELECT count FROM {$go_table_name} WHERE uid=%d AND post_id=%d", $user_id, $task_id));
			if($current_status == 5 && $current_count == 0){
				go_add_post($user_id, $task_id, $current_status-1, $rewards['points'][$current_status-1], $rewards['currency'][$current_status-1], $page_id, 'on', 1, null, null, null, null, $rewards['bonus_currency'][$current_status-1]);
				
				$changed['points'] += $rewards['points'][$current_status-1];
				$changed['currency'] += $rewards['currency'][$current_status-1];
				$changed['bonus_currency'] += $rewards['bonus_currency'][$current_status-1];
			}elseif($current_status < 5){
				go_add_post($user_id, $task_id, $current_status, $rewards['points'][$current_status-1], $rewards['currency'][$current_status-1], $page_id, null, null, null, null, null, null, $rewards['bonus_currency'][$current_status-1]);
				
				$changed['points'] += $rewards['points'][$current_status-1];
				$changed['currency'] += $rewards['currency'][$current_status-1];
				$changed['bonus_currency'] += $rewards['bonus_currency'][$current_status-1];
			}
		}
	}
	if($message === 'See me'){
		go_message_user($user_id, $message.' about '.strtolower(go_return_options('go_tasks_name')).' <a href="'.get_permalink($task_id).'" style="display: inline-block; text-decoration: underline; padding: 0px; margin: 0px;">'.get_the_title($task_id).'</a> please');
	}else{
		go_message_user($user_id, 'RE: <a href="'.get_permalink($task_id).'">'.get_the_title($task_id).'</a> '.$message);
	}
	echo json_encode($changed);
	die();
}
	
function go_stats_item_list(){
	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	if(!empty($_POST['user_id'])){
		$user_id = $_POST['user_id'];
	}else{
		$user_id = get_current_user_id();
	}
	$items = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$go_table_name} WHERE uid=%d AND status=%d ORDER BY timestamp DESC, reason DESC", $user_id, -1));
	?>
	<ul id='go_stats_item_list_purchases' class='go_stats_body_list'>
		<li class='go_stats_body_list_head'>PURCHASES</li>
		<?php
		foreach($items as $item){
			$item_id = $item->post_id;
			$item_count = $item->count;
			$purchase_date = $item->timestamp;
			$purchase_reason = $item->reason;
			?>
				<li class='go_stats_item go_stats_purchased_item'>
					<?php
						echo "<a href='".get_permalink($item_id)."'>".get_the_title($item_id)."</a> ({$item_count}) {$purchase_date}";
					?>
				</li>
			<?php
		}
		?>
	</ul>
	<ul class='go_stats_body_list'>
		<li class='go_stats_body_list_head'>RECEIVED (coming soon)</li>
	</ul>
	<ul class='go_stats_body_list'>
		<li class='go_stats_body_list_head'>SOLD (coming soon)</li>
	</ul>
	<?php
	die();
}

function go_stats_rewards_list(){
	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	if(!empty($_POST['user_id'])){
		$user_id = $_POST['user_id'];
	}else{
		$user_id = get_current_user_id();
	}
	$rewards = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$go_table_name} WHERE uid = %d AND (points != %d OR currency != 0 OR bonus_currency != 0) ORDER BY id DESC", $user_id, 0));
	?>
	<ul id='go_stats_rewards_list_points' class='go_stats_body_list'>
		<li class='go_stats_body_list_head'><?php echo strtoupper(go_return_options('go_points_name'));?></li>
		<?php
			foreach($rewards as $reward){
				$reward_id = $reward->post_id;
				$reward_points = $reward->points;
				if($reward_points != 0){
					?>
						<li class='go_stats_reward go_stats_reward_points'><?php echo (($reward->status != 6)?"<a href='".get_permalink($reward_id)."'>".get_the_title($reward_id)."</a>": "{$reward->reason}")." <div class='go_stats_amount'>({$reward_points})</div>";?>
						</li>
					<?php
				}
			}
		?>
	</ul>
	<ul id='go_stats_rewards_list_currency' class='go_stats_body_list'>
		<li class='go_stats_body_list_head'><?php echo strtoupper(go_return_options('go_currency_name'));?></li>
		<?php
			foreach($rewards as $reward){
				$reward_id = $reward->post_id;
				$reward_currency = $reward->currency;
				if($reward_currency != 0){
					?>
						<li class='go_stats_reward go_stats_reward_currency'><?php echo (($reward->status != 6)?"<a href='".get_permalink($reward_id)."'>".get_the_title($reward_id)."</a>": "{$reward->reason}")."<div class='go_stats_amount'>({$reward_currency})</div>";?>
						</li>
					<?php
				}
			}
		?>
	</ul>
	<ul id='go_stats_rewards_list_bonus_currency' class='go_stats_body_list'>
		<li class='go_stats_body_list_head'><?php echo strtoupper(go_return_options('go_bonus_currency_name'));?></li>
		<?php
			foreach($rewards as $reward){
				$reward_id = $reward->post_id;
				$reward_bonus_currency = $reward->bonus_currency;
				if($reward_bonus_currency != 0){
					?>
						<li class='go_stats_reward go_stats_reward_bonus_currency'><?php echo (($reward->status != 6)?"<a href='".get_permalink($reward_id)."'>".get_the_title($reward_id)."</a>": "{$reward->reason}")."<div class='go_stats_amount'>({$reward_bonus_currency})</div>";?>
						</li>
					<?php
				}
			}
		?>
	</ul>
	<?php
	die();
}

function go_stats_badges_list(){
	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	if(!empty($_POST['user_id'])){
		$user_id = $_POST['user_id'];
	}else{
		$user_id = get_current_user_id();
	}
	$badges = get_user_meta($user_id, 'go_badges', true);
	if($badges){
		foreach($badges as $id => $badge){
			$img = wp_get_attachment_image($badge, array(100,100), false, $atts);
			echo "<div class='go_badge_wrap'><div class='go_badge_container'><div class='go_badge'>{$img}</div></div></div>";
		}
	}
	die();
}

function go_stats_leaderboard_choices(){
	?>
	<div id='go_stats_leaderboard_filters'>
		<div id='go_stats_leaderboard_filters_head'>FILTER</div>
		<div id='go_stats_leaderboard_classes'>
			<?php
			$classes = get_option('go_class_a');
			$first = 1;
			if($classes){
				foreach($classes as $class_a){
					?>
						<div class='go_stats_leaderboard_class_wrap'><input type='checkbox' class='go_stats_leaderboard_class_choice' value='<?php echo $class_a;?>'><?php echo $class_a;?></div>
					<?php
					$first++;
				}
			}
			?>
		</div>
		<div id='go_stats_leaderboard_focuses'>
			<?php
			$focuses = get_option('go_focus');
			if($focuses){
				foreach($focuses as $focus){
					?>
						<div class='go_stats_leaderboard_focus_wrap'><input type='checkbox' class='go_stats_leaderboard_focus_choice' value='<?php echo $focus;?>'><?php echo $focus;?></div>
					<?php
				}
			}
			?>
		</div>
		<div id='go_stats_leaderboard_dates'>
       		(coming soon)
			<div class='go_stats_leaderboard_date_wrap'><input type='radio' class='go_stats_leaderboard_date_choice' value='all' checked>All Time</div>
			<div class='go_stats_leaderboard_date_wrap'><input type='radio' class='go_stats_leaderboard_date_choice' value='30'>Last 30 Days</div>
			<div class='go_stats_leaderboard_date_wrap'><input type='radio' class='go_stats_leaderboard_date_choice' value='10'>Last 10 Days</div>
		</div>
	</div>
	<div id='go_stats_leaderboard'></div>
	<?php
	die();
}

function go_return_user_data($id, $counter, $sort){
	$points = go_return_points($id);
	$currency = go_return_currency($id);
	$bonus_currency = go_return_bonus_currency($id);
	$badge_count = go_return_badge_count($id);
	$user_data_key = get_userdata($id);
	$user_display = "<a href='{$user_data_key->user_url}' target='_blank'>{$user_data_key->display_name}</a>";
	switch($sort){
		case 'points':
			echo "<li>{$counter} {$user_display} <div class='go_stats_amount'>{$points}</div></li>";
			break;
		case 'currency':
			echo "<li>{$counter} {$user_display} <div class='go_stats_amount'>{$currency}</div></li>";
			break;
		case 'bonus_currency':
			echo "<li>{$counter} {$user_display} <div class='go_stats_amount'>{$bonus_currency}</div></li>";
			break;
		case 'badges':
			echo "<li>{$counter} {$user_display} <div class='go_stats_amount'>{$badge_count}</div></li>";
			break;
	}
}

function go_return_user_leaderboard($users, $class_a_choice, $focuses, $type, $counter){
	foreach($users as $user_ids){
		foreach($user_ids as $user_id){
			if(!user_can($user_id, 'manage_options')){
				$class_a = get_user_meta($user_id, 'go_classifications', true);
				$focus = get_user_meta($user_id, 'go_focus', true);
				if($class_a){
					$class_keys = array_keys($class_a);
				}
				if(!empty($class_a_choice) && !empty($focuses)){
					if(!empty($class_keys) && !empty($focus)){
						$class_intersect = array_intersect($class_keys, $class_a_choice);
						if(is_array($focus)){
							$focus_intersect = array_intersect($focus, $focuses);
						}else{
							$focus_intersect = in_array($focus, $focuses);
						}
						if(!empty($class_intersect) && !empty($focus_intersect)){
							go_return_user_data($user_id, $counter, $type);
							$counter++;
						}
					}
				}elseif(!empty($class_a_choice)){
					if(!empty($class_keys)){
						$class_intersect = array_intersect($class_keys, $class_a_choice);
						if(!empty($class_intersect)){
							go_return_user_data($user_id, $counter, $type);
							$counter++;
						}
					}
				}elseif(!empty($focuses)){
					if(!empty($focus)){
						if(is_array($focus)){
							$focus_intersect = array_intersect($focus, $focuses);
						}else{
							$focus_intersect = in_array($focus, $focuses);
						}
						if(!empty($focus_intersect)){
							go_return_user_data($user_id, $counter, $type);
							$counter++;
						}
					}
				}
			}
		}
	}	
}

function go_stats_leaderboard(){
	global $wpdb;
	$go_totals_table_name = "{$wpdb->prefix}go_totals";
	$class_a_choice = $_POST['class_a_choice'];
	$focuses = $_POST['focuses'];
	$date = $_POST['date'];
	?>
	<ul id='go_stats_leaderboard_list_points' class='go_stats_body_list go_stats_leaderboard_list'>
		<li class='go_stats_body_list_head'><?php echo strtoupper(go_return_options('go_points_name'));?></li>
		<?php 
		$counter = 1;
		$users_points = $wpdb->get_results("SELECT uid FROM {$go_totals_table_name} ORDER BY CAST(points as signed) DESC");
		go_return_user_leaderboard($users_points, $class_a_choice, $focuses, 'points', $counter)
		?>
	</ul>
	<ul id='go_stats_leaderboard_list_currency' class='go_stats_body_list go_stats_leaderboard_list'>
		<li class='go_stats_body_list_head'><?php echo strtoupper(go_return_options('go_currency_name'));?></li>
		<?php 
		$counter = 1;
		$users_currency = $wpdb->get_results("SELECT uid FROM {$go_totals_table_name} ORDER BY CAST(currency as signed) DESC");
		go_return_user_leaderboard($users_currency, $class_a_choice, $focuses, 'currency', $counter)
		?>
	</ul>
	<ul id='go_stats_leaderboard_list_bonus_currency' class='go_stats_body_list go_stats_leaderboard_list'>
		<li class='go_stats_body_list_head'><?php echo strtoupper(go_return_options('go_bonus_currency_name'));?></li>
		<?php 
		$counter = 1;
		$users_bonus_currency = $wpdb->get_results("SELECT uid FROM {$go_totals_table_name} ORDER BY CAST(bonus_currency as signed) DESC");
		go_return_user_leaderboard($users_bonus_currency, $class_a_choice, $focuses, 'bonus_currency', $counter)
		?>
	</ul>
	<ul id='go_stats_leaderboard_list_badge_count' class='go_stats_body_list go_stats_leaderboard_list'>
		<li class='go_stats_body_list_head'>BADGES</li>
		<?php 
		$counter = 1;
		$users_badge_count = $wpdb->get_results("SELECT uid FROM {$go_totals_table_name} ORDER BY CAST(badge_count as signed) DESC");
		go_return_user_leaderboard($users_badge_count, $class_a_choice, $focuses, 'badges', $counter)
		?>
	</ul>
	<?php 
	die();
}
	
 ?>