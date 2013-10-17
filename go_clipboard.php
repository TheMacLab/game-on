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
margin-right: 5px;" title="Check the boxes of the students you want to add to." class="ui-state-default ui-corner-all"><span  class="ui-icon ui-icon-help"></span></div><label for="go_clipboard_points"><?php echo go_return_options('go_points_name'); ?>: </label><input name="go_clipboard_points" id="go_clipboard_points" /> <label id="go_clipboard_minutes"><?php echo 'Minutes'; ?>: </label> <input name="go_clipboard_minutes" id="go_clipboard_time" /><label for="go_clipboard_currency"><?php echo go_return_options('go_currency_name'); ?>: </label><input name="go_clipboard_minuts" id="go_clipboard_currency" /><label for="go_clipboard_infractions">Infractions: </label><input name="go_clipboard_infractions" id="go_clipboard_infractions" /><label name="go_clipboard_reason">Reason: </label> <input name="go_clipboard_reason" id="go_clipboard_reason" /><button class="ui-button-text" onclick="go_clipboard_add();">Add</button></div>
    
    <table  id="go_clipboard_table" class="widefat sortable" >
    <thead>
    <tr><th></th>
    <th class="header" style="width:6%;"><a href="#" >ID</a></th>
 <th class="header" style="width:6%;"><a href="#" ><?php echo go_return_options('go_class_b_name'); ?></a></th>
 <th class="header" style="width:10%;"><a href="#" >Name</a></th>
<th class="header" style="width:10%;""><a href="#" >Gamertag</a></th>
<th class="header" style="width:8%;"><a href="#" >Rank</a></th>
<th class="header" style="width:6%;"><a href="#" ><?php echo go_return_options('go_currency_name'); ?></a></th>
<th class="header" style="width:8%;"><a href="#">Minutes</a></th>
<th class="header" style="width:5%;" align="center"><a href="#"><?php echo go_return_options('go_points_name'); ?></a></th>
<th class="header" style="width:13%;"><a href="#" >Infractions (Max: <?php echo go_return_options('go_max_infractions'); ?>)</a></th>
<th class="header" style="width:9%;"><a href="#"><?php echo go_return_options('go_first_stage_name'); ?></a></th> 
<th class="header" style="width:8%;"><a href="#" ><?php echo go_return_options('go_second_stage_name'); ?></a></th> 
<th class="header" style="width:8%;"><a href="#" ><?php echo go_return_options('go_third_stage_name'); ?></a></th> 
<th class="header" style="width:13%; border:none !important;"><a href="#"><?php echo go_return_options('go_fourth_stage_name'); ?></a></th>
<tbody id="go_clipboard_table_body"></tbody>
    
    
    </table>
    
    
     </div><?php
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
		$infractions = go_return_infractions($value);
		$minutes = go_return_minutes($value);
		$currency = go_return_currency($value);
		$points = go_return_points($value);
		go_get_rank($value);
		global $current_rank;
		$first_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 1");
		$second_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 2");
		$third_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 3");
		$fourth_stage = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $value and status = 4");
		
		echo '<tr><td><input class="go_checkbox" type="checkbox" name="go_selected" value="'.$value.'"></td><td><a onclick="go_admin_bar_stats_page_button('.$value.'); "  >'.$user_login.'</a></td><td>'.$class_a[$class_a_choice].'</td><td><a href="'.$user_url.'" target="_blank">'.$user_last_name.', '.$user_first_name.'</a></td><td>'.$user_display.'</td><td>'.$current_rank.'</td><td>'.$currency.'</td><td>'.$minutes.'</td><td>'.$points.'</td><td>'.$infractions.'</td><td>'.$first_stage.'</td><td>'.$second_stage.'</td><td>'.$third_stage.'</td><td>'.$fourth_stage.'</td></tr>';
		
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
	$infractions = $_POST['infractions'];
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
if($infractions!=''&&$reason !=''){
	go_add_infraction($value, $infractions,true);
}
	}
	die();
	
	}
?>
