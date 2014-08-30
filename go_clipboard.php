<?php
function go_clipboard() {
global $wpdb;
$dir = plugin_dir_url(__FILE__);
 add_submenu_page( 'game-on-options.php', 'Clipboard', 'Clipboard', 'manage_options', 'go_clipboard', 'go_clipboard_menu');
}

function go_clipboard_menu() {
		global $wpdb;
	if (!current_user_can('manage_options'))  { 
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	else{
		go_style_clipboard();
		go_jquery_clipboard();
		
		?>
 <div id="records_tabs">
<ul>
    <li><a href="#clipboard_wrap">Clipboard</a></li>
    <li><a href="#go_analysis">Analysis</a></li>
  </ul>
        <div id="clipboard_wrap">
        <select class="menuitem" id="go_clipboard_class_a_choice" onchange="go_clipboard_class_a_choice();">
      <option>...</option>
      
         <?php
$class_a = get_option('go_class_a');
if($class_a){
	foreach($class_a as $key=> $value){
		echo '<option class="ui-corner-all">'.$value.'</option>';
		}
	}
	?></select>
    
    <div id="go_clipboard_add">
    <?php go_options_help('http://maclab.guhsd.net/go/video/clipboard/clipboard.mp4', 'SAMPLE TEXT'); ?>
	<label for="go_clipboard_points"><?php echo go_return_options('go_points_name'); ?>: </label><input name="go_clipboard_points" id="go_clipboard_points" class='go_clipboard_add'/> 
	<label for="go_clipboard_currency"><?php echo go_return_options('go_currency_name'); ?>: </label><input name="go_clipboard_currency" id="go_clipboard_currency" class='go_clipboard_add'/>
	<label for="go_clipboard_bonus_currency"><?php echo go_return_options('go_bonus_currency_name'); ?>: </label> <input name="go_clipboard_bonus_currency" id="go_clipboard_bonus_currency" class='go_clipboard_add'/>
	<label for="go_clipboard_penalty"><?php echo go_return_options('go_penalty_name'); ?>: </label><input name="go_clipboard_penalty" id="go_clipboard_penalty" class='go_clipboard_add'/>
	<label for="go_clipboard_badge">Badge ID:</label><input name="go_clipboard_badge" id="go_clipboard_badge" class='go_clipboard_add'/><br />
	<label name="go_clipboard_reason">Message: </label>
    <div>
    	<textarea name="go_clipboard_reason" id="go_clipboard_reason" placeholder='See me'></textarea><br/>
        <button class="ui-button-text" id="go_send_message" onclick="go_clipboard_add();">Add</button>
        <button id="go_fix_messages" onclick="fixmessages()">Fix Messages</button>
	</div>

	<table  id="go_clipboard_table" class="pretty" >
		<thead>
			<tr>
				<th><input type="checkbox" onClick="go_toggle(this);" /></th>
				<th class="header"><a href="#" >ID</a></th>
				<th class="header"><a href="#" ><?php echo go_return_options('go_class_b_name'); ?></a></th>
				<th class="header"><a href="#" >Student Name</a></th>
				<th class="header"><a href="#" >Display Name</a></th>
				<th class="header"><a href="#" ><?php echo go_return_options('go_level_names'); ?></a></th>
				<?php if(go_return_options('go_focus_switch') == 'On'){?><th class="header"><a href="#" ><?php echo go_return_options('go_focus_name'); ?></a></th><?php }?>
				<th class="header"><a href="#"><?php echo go_return_options('go_points_name'); ?></a></th>
				<th class="header"><a href="#" ><?php echo go_return_options('go_currency_name'); ?></a></th>
				<th class="header"><a href="#"><?php echo go_return_options('go_bonus_currency_name'); ?></a></th>
				<th class="header"><a href="#"><?php echo go_return_options('go_penalty_name'); ?></a></th>
				<th class="header"><a href="#"><?php echo go_return_options('go_badges_name');?></a></th>
			</tr>
		</thead>
	<tbody id="go_clipboard_table_body"></tbody>
	</table>
    </div>
    
     </div>
	 <div id="go_analysis">
		Choose the day at which data will be collected at midnight (0:00 AM)
		<select id='go_day_select' onchange='go_update_script_day()'>
			<?php 
			$script_day = go_return_options('go_analysis_script_day');
			if($script_day){
				echo "<option value='{$script_day}'>{$script_day}</option>";
			}
			?>
			<option value='Monday'>Monday</option>
			<option value='Tuesday'>Tuesday</option>
			<option value='Wednesday'>Wednesday</option>
			<option value='Thursday'>Thursday</option>
			<option value='Friday'>Friday</option>
			<option value='Saturday'>Saturday</option>
			<option value='Sunday'>Sunday</option>
		</select>
         <select id="go_selection" onchange="go_update_graph();">
            <option value="1"><?php echo go_return_options('go_points_name'); ?></option>
            <option value="4"><?php echo go_return_options('go_currency_name');?></option>
            <option value="0"><?php echo go_return_options('go_bonus_currency_name'); ?></option>
            <option value="0"><?php echo go_return_options('go_penalty_name'); ?></option>
            <option value="2"><?php echo go_return_options('go_third_stage_name'); ?></option>
            <option value="3"><?php echo go_return_options('go_fourth_stage_name'); ?></option>
         </select>
         <div id="choices">
         <?php
		 	if($class_a){
				foreach($class_a as $class){
				?>
                	<input type="checkbox" class="go_class_a" name="<?php echo strtolower(preg_replace('/\s+/', '', $class)); ?>" value="<?php echo $class;?>" onclick="go_update_graph(this)"/><?php echo $class;?><br />
                    <div id="<?php echo strtolower(preg_replace('/\s+/', '', $class)); ?>" class="go_class_a_results"></div>
                <?php	
					$i++;
				}
			}
		 ?>
         </div>
         <div class="container">
             <div id="placeholder" style="width:98%;height:98%;">
             </div>  
         </div>
     </div>
     </div>
	 <?php
	}
}

function go_clipboard_intable(){
	global $wpdb;
	$class_a_choice = $_POST['go_clipboard_class_a_choice'];
	$table_name_user_meta = $wpdb->prefix.'usermeta';
	$table_name_go = $wpdb->prefix.'go';
	$uid = $wpdb->get_results("SELECT user_id
	FROM {$table_name_user_meta}
	WHERE meta_key =  '{$wpdb->prefix}capabilities'
	AND meta_value LIKE  '%subscriber%'");
	print_r($uid);
	foreach ($uid as $id) {
		foreach ($id as $value) {
			$class_a = get_user_meta($value, 'go_classifications',true);
			if ($class_a) { 
				if ($class_a[$class_a_choice]) {
					$user_data_key = get_userdata( $value ); 
					$user_login = $user_data_key->user_login;
					$user_display = $user_data_key->display_name;
					$user_first_name = $user_data_key->user_firstname;
					$user_last_name =  $user_data_key->user_lastname;
					$user_url =  $user_data_key->user_url;
					$user_focuses = go_display_user_focuses($value);
					$focus_name = get_option('go_focus_name');
					$focuses = get_option('go_focus');
					$focuses_list = '';
					$focuses_list = "<option value='No {$focus_name}' ".((empty($user_focuses) || $user_focuses == "No {$focus_name}")?"selected":"").">No {$focus_name}</option>";
					foreach ($focuses as $focus) {
						$focuses_list .= "<option value='{$focus}' ".($focus == $user_focuses ? "selected" : "").">{$focus}</option>";
					}
					$bonus_currency = go_return_bonus_currency($value);
					$penalty = go_return_penalty($value);
					$currency = go_return_currency($value);
					$points = go_return_points($value);
					$badge_count = go_return_badge_count($value);
					go_get_rank($value);
					global $current_rank;
					
					echo "<tr id='user_{$value}'>
							<td><input class='go_checkbox' type='checkbox' name='go_selected' value='{$value}'/></td>
							<td><span><a href='#' onclick='go_admin_bar_stats_page_button(&quot;{$value}&quot;);'>{$user_login}</a></td>
							<td>{$class_a[$class_a_choice]}</td>
							<td><a href='{$user_url}' target='_blank'>{$user_last_name}, {$user_first_name}</a></td>
							<td>{$user_display}</td>
							<td>{$current_rank}</td>
							".((go_return_options('go_focus_switch') == 'On')?"<td><select class='go_focus' onchange='go_user_focus_change(&quot;{$value}&quot;, this);'>{$focuses_list}</select</td>":"")."
							<td class='user_points'>{$points}</td>
							<td class='user_currency'>{$currency}</td>
							<td class='user_bonus_currency'>{$bonus_currency}</td>
							<td class='user_penalty'>{$penalty}</td>
							<td class='user_badge_count'>{$badge_count}</td>
						  </tr>";
				}
			}
		}
	}
	die();
}

add_action('wp_ajax_go_clipboard_add','go_clipboard_add');
function go_clipboard_add(){
	$ids = $_POST['ids'];
	$points = $_POST['points'];
	$currency = $_POST['currency'];
	$bonus_currency = $_POST['bonus_currency'];
	$penalty = $_POST['penalty'];
	$reason = $_POST['reason'];
	$badge_ID = $_POST['badge_ID'];
	foreach($ids as $key=>$value){
		if($reason != ''){
			if($points != ''){
				go_add_currency($value,$reason, 6, $points, 0, false);
			}
			if($currency!= ''){
				go_add_currency($value, $reason, 6, 0, $currency, false);
			}
			if($bonus_currency!= ''){
				go_add_bonus_currency($value, $bonus_currency, $reason);
			}
			if($penalty!= ''){
				go_add_penalty($value, $penalty, $reason);
			}
			if($badge_ID != ''){
				do_shortcode('[go_award_badge id="'.$badge_ID.'" repeat = "off" uid="'.$value.'"]');
			}
			go_message_user($value, $reason);
		}
	}
	die();
}

function go_clipboard_collect_data(){
	global $wpdb;
	$table_name_user_meta = $wpdb->prefix.'usermeta';
	$table_name_go = $wpdb->prefix.'go';
	$uid = $wpdb->get_results("SELECT user_id
	FROM ".$table_name_user_meta."
	WHERE meta_key =  '".$wpdb->prefix."capabilities'
	AND meta_value LIKE  '%subscriber%'");
	$time = round(microtime(true));
	$array = get_option('go_graphing_data');
	foreach($uid as $id){
		foreach($id as $value){
			$bonus_currency = go_return_bonus_currency($value);
			$penalty = go_return_penalty($value);
			$currency = go_return_currency($value);
			$points = go_return_points($value);
			$third_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 3");
			$fourth_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 4");
			$array[$value][$time] = $bonus_currency.','. $penalty.','. $points.','. $third_stage.','. $fourth_stage.','.$currency;
		}
	}
	update_option( 'go_graphing_data', $array );
}
	
//function which is run when the analysis tab is clicked, when a user collects data, or when a different selection (points, currency, time) is picked in the analysis tab
function go_clipboard_get_data(){
	global $wpdb;
	
	//grabs the selection
	// 0 = bonus currency
	// 1 = points
	// 2 = completed
	// 3 = mastered
	// 4 = currency
	// 5 = penalty
	$selection = $_POST['go_graph_selection'];
	if(isset($_POST['go_class_a'])){
		$class_a_choice = $_POST['go_class_a'];
	}else{
		$class_a_choice = array();	
	}
	if(isset($_POST['go_choices_checked_names'])){
		$go_choices_checked_names = $_POST['go_choices_checked_names'];
	}else{
		$go_choices_checked_names = array();
	}
	
	$array = get_option('go_graphing_data',false);
	
	$users_in_class = array();
	foreach($class_a_choice as $value){
		if(!array_key_exists($value, $users_in_class)){
			$users_in_class[$value] = array();	
		}
	}
	
	$table_name_go_totals= $wpdb->prefix.'go_totals';
	$uids = $wpdb->get_results("SELECT uid FROM ".$table_name_go_totals."");
	// loops through game on users and places each user in their respective class_a 
	foreach($uids as $uid){
		foreach($uid as $id){
			$user_class = get_user_meta($id, 'go_classifications', true);
			if($user_class){
				$class = array_keys($user_class);
				$check = array_intersect($class, array_keys($users_in_class));
				if($check){
					if(count($check) > 1 || count($class) > 1){
						foreach($check as $value){
							$users_in_class[$value][] = $id;
						}
					}else{
						$key = (string)$check[0];
						$users_in_class[$key][] = $id;	
					}
				}
			}
		}
	}
	// loops through users in each class and creates array of all their data
	foreach($users_in_class as $class => $students){
		// date is the unix timestamp of the last time data was collected using the go_clipboard_collect_data function
		foreach($array as $id => $date){
			if(in_array($id, $students)){
				$getinfo = get_userdata( $id );
				$id = $getinfo -> user_login;
				$first = $getinfo-> first_name;
				$last = $getinfo-> last_name;
				$info[$id]['label'] = $last.', '.$first.' ('.$id.')';
				$info[$id]['class_a'][] = $class;
				foreach($date as $date => $content){
					// Bonus Currency, penalty, points, completed, and mastered array associated with the unix timestamp when go_clipboard_collect_data function ran
					$content_array = explode(',',$content);
					// generates array of user data associated with a unix timestamp, then appends the unix timestamp$content_array's element which corresponds to the graph selection key above
					$info[$id]['data'][] = array($date*1000,$content_array[$selection]);	
				}
			}
		}
	}
	if($go_choices_checked_names){
		$info['checked'] = $go_choices_checked_names;
	}	
	// stringifies the php array into a json object
	echo JSON_encode($info);
	die();
}

add_action('wp_ajax_fixmessages', 'fixmessages');					
function fixmessages(){
	global $wpdb;
	$users = get_users(array('role' => 'Subscriber'));
	foreach($users as $user){
		$messages = get_user_meta($user->ID, 'go_admin_messages',true);
		$messages_array = $messages[1];
		$messages_unread = array_values($messages_array);
		$messages_unread_count = 0;
		foreach($messages_unread as $message_unread){
			if($message_unread[1] == 1){
				$messages_unread_count++;	
			}
		}
		if($messages[0] != $message_unread_count){
			$messages[0] = $messages_unread_count;
			update_user_meta($user->ID, 'go_admin_messages', $messages);
		}
	}
	
	die();
}

function go_update_script_day(){
	$new_day = $_POST['new_day'];
	update_option('go_analysis_script_day', $new_day);
	wp_clear_scheduled_hook('go_clipboard_collect_data');
	$script_day = go_return_options('go_analysis_script_day');
	$script_timestamp = strtotime("this {$script_day}");
	wp_schedule_event($script_timestamp, 'go_weekly', 'go_clipboard_collect_data');
	die();
}
?>