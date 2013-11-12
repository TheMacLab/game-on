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
    
    <div id="go_clipboard_add"> <div style="width:17px; display:inline-table;margin-top: 4px;
margin-right: 5px;" title="Check the boxes of the students you want to add to." class="ui-state-default ui-corner-all"><span  class="ui-icon ui-icon-help"></span></div><label for="go_clipboard_points"><?php echo go_return_options('go_points_name'); ?>: </label><input name="go_clipboard_points" id="go_clipboard_points" /><label for="go_clipboard_currency"><?php echo go_return_options('go_currency_name'); ?>: </label><input name="go_clipboard_minuts" id="go_clipboard_currency" /> <label id="go_clipboard_minutes"><?php echo 'Minutes'; ?>: </label> <input name="go_clipboard_minutes" id="go_clipboard_time" /><label name="go_clipboard_reason">Reason: </label> <input name="go_clipboard_reason" id="go_clipboard_reason" /><button class="ui-button-text" onclick="go_clipboard_add();">Add</button></div>
    
    <table  id="go_clipboard_table" class="pretty" >
    <thead>
    <tr><th></th>
    <th class="header" style="width:7%;"><a href="#" >ID</a></th>
 <th class="header" style="width:7%;"><a href="#" ><?php echo go_return_options('go_class_b_name'); ?></a></th>
 <th class="header" style="width:10%;"><a href="#" >Name</a></th>
<th class="header" style="width:10%;""><a href="#" >Gamertag</a></th>
<th class="header" style="width:9%;"><a href="#" >Rank</a></th>
<th class="header" style="width:7%;"><a href="#" ><?php echo go_return_options('go_currency_name'); ?></a></th>
<th class="header" style="width:9%;"><a href="#">Minutes</a></th>
<th class="header" style="width:5%;" align="center"><a href="#"><?php echo go_return_options('go_points_name'); ?></a></th>
<th class="header" style="width:10%;"><a href="#"><?php echo go_return_options('go_first_stage_name'); ?></a></th> 
<th class="header" style="width:9%;"><a href="#" ><?php echo go_return_options('go_second_stage_name'); ?></a></th> 
<th class="header" style="width:9%;"><a href="#" ><?php echo go_return_options('go_third_stage_name'); ?></a></th> 
<th class="header" style="width:14%;"><a href="#"><?php echo go_return_options('go_fourth_stage_name'); ?></a></th> </tr></thead>
<tbody id="go_clipboard_table_body"></tbody>
    
    
    </table>
    
    
     </div>
	 <div id="go_analysis">
     <button onClick="collectData();">Collect Data</button>
     <select id="go_selection" onClick="go_update_graph();">
     <option value="0"><?php echo 'Minutes'; ?></option>
     <option value="1"><?php echo go_return_options('go_points_name'); ?></option>
     <option value="2"><?php echo go_return_options('go_third_stage_name'); ?></option>
     <option value="3"><?php echo go_return_options('go_fourth_stage_name'); ?></option>
     </select>
     <div class="container" style="overflow:auto;">
     <div id="placeholder" style="width:600px;height:300px; float:left"></div>
     <div id="overview" class="demo-placeholder" style="float:right;width:400px; height:235px;"></div>
     <p id="choices" style="float:right; width:200px; height:250px; overflow:auto; margin-right:30px; border:solid black
     thin;"></p>
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
FROM ".$table_name_user_meta."
WHERE meta_key =  'wp_capabilities'
AND meta_value LIKE  '%subscriber%'");
	foreach($uid as $id){
		foreach($id as $value){
			$class_a = get_user_meta($value, 'go_classifications',true);
			if($class_a){ 
			if($class_a[$class_a_choice]){
		$user_data_key = get_userdata( $value ); 
		$user_login = $user_data_key->user_login;
		$user_display = $user_data_key->display_name;
		$user_first_name = $user_data_key->user_firstname;
		$user_last_name =  $user_data_key->user_lastname;
		$user_url =  $user_data_key->user_url;
		$minutes = go_return_minutes($value);
		$currency = go_return_currency($value);
		$points = go_return_points($value);
		go_get_rank($value);
		global $current_rank;
		$first_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 1");
		$second_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 2");
		$third_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 3");
		$fourth_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 4");
		
		echo '<tr><td><input class="go_checkbox" type="checkbox" name="go_selected" value="'.$value.'"></td><td><a onclick="go_admin_bar_stats_page_button('.$value.'); "  >'.$user_login.'</a></td><td>'.$class_a[$class_a_choice].'</td><td><a href="'.$user_url.'" target="_blank">'.$user_last_name.', '.$user_first_name.'</a></td><td>'.$user_display.'</td><td>'.$current_rank.'</td><td>'.$currency.'</td><td>'.$minutes.'</td><td>'.$points.'</td><td>'.$first_stage.'</td><td>'.$second_stage.'</td><td>'.$third_stage.'</td><td>'.$fourth_stage.'</td></tr>';
		
		}}}}
		die();
	}

add_action('wp_ajax_go_clipboard_add','go_clipboard_add');
function go_clipboard_add(){
	$ids = $_POST['ids'];
	$points = $_POST['points'];
	$currency = $_POST['currency'];
	$minutes = $_POST['time'];
	$reason = $_POST['reason'];
	foreach($ids as $key=>$value){
	if($points != ''&& $reason != ''){
	go_add_currency($value,$reason, 6, $points, 0, false);
	}
if($currency!= ''&&$reason!= ''){
	go_add_currency($value, $reason, 6, 0, $currency, false);

	}
if($minutes!= ''&&$reason != ''){
	go_add_minutes($value, $minutes, $reason);
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
WHERE meta_key =  'wp_capabilities'
AND meta_value LIKE  '%subscriber%'");
	$time = round(microtime(true));
	$array = get_option('go_graphing_data');
	foreach($uid as $id){
		foreach($id as $value){
		$minutes = go_return_minutes($value);
		$points = go_return_points($value);
		$third_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 3");
		$fourth_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 4");
		$array[$value][$time] = $minutes.','. $points.','. $third_stage.','. $fourth_stage;
		}}
	update_option( 'go_graphing_data', $array );
	}
	
function go_clipboard_get_data(){
	global $wpdb;
	$selection = $_POST['go_graph_selection'];
	$array = get_option('go_graphing_data',false);
	foreach($array as $id => $date){
		$getinfo = get_userdata( $id );
		$id= $getinfo -> user_login;
		$first= $getinfo-> first_name;
		$last= $getinfo-> last_name;
		$info[$id]['label'] = $last.','.$first.'('.$id.')';
		foreach($date as $date => $content){
			$content_array = explode(',',$content);
			$info[$id]['data'][]=array($date*1000,$content_array[$selection]);
			//$data[$id] .= '['.$date.','.$content_array[$selection].'],';
			}
		//$info .= '"'.$id.'": {label: "'.$id.'",data: ['.$data[$id].']},';
		}
		

		echo JSON_encode($info);
		//	echo '{'.$info.'}';
			die();
			     	}
?>