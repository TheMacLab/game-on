<?php
function go_stats_overlay(){ 
	echo '<div id="go_stats_page_black_bg" style="display:none !important;"></div><div id="go_stats_white_overlay" style="display:none;"></div>';
}
function go_admin_bar_stats(){ 
 	global $wpdb;
	if($_POST['uid']){
		$current_user = get_userdata( $_POST['uid'] );
		}else{
 	$current_user = wp_get_current_user();
	}
	?><input type="hidden" id="go_stats_hidden_input" value="<?php echo $_POST['uid'] ?>"/><?php
    $user_login =  $current_user->user_login ;
    $user_email = $current_user->user_email;
    $gamer_tag = $current_user->display_name ;
    $user_id = $current_user->ID ;
	$user_website = $current_user->user_url;
 	$current_user_id = $current_user->ID;
	$current_points = go_return_points($current_user_id);
	$current_currency = go_return_currency($current_user_id);
	$current_minutes = go_return_minutes($current_user_id);
	////////////////////////////////////////////////////////////
	go_get_rank($current_user_id);
	global $current_rank;
	global $next_rank_points;
	global $current_rank_points;
	global $next_rank;
	$dom = ($next_rank_points-$current_rank_points);
	if($dom <= 0){ $dom = 1;}
	$percentage = ($current_points-$current_rank_points)/$dom*100;
	if($percentage <= 0){ $percentage = 0;} else if($percentage >= 100){$percentage = 100;}
	$table_name_go = $wpdb->prefix . "go";
	///////////////////////////////////////////////////////////
	$numb_encountered = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $user_id and status = 1 ");
	$numb_accepted = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $user_id and status = 2 ");
	$numb_completed = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $user_id and status = 3 ");
	$numb_mastered = (int)$wpdb->get_var("select count(*) from ".$table_name_go." where uid = $user_id and status = 4 ");
	$total_tasks_done = $numb_encountered+$numb_accepted+$numb_encountered+$numb_mastered;
	if($total_tasks_done == 0){ $total_tasks_done = 1;}
	$percentage_encountered = $numb_encountered/$total_tasks_done*100;
	$percentage_accepted = $numb_accepted/$total_tasks_done*100;
	$percentage_completed = $numb_completed/$total_tasks_done*100;
	$percentage_mastered = $numb_mastered/$total_tasks_done*100;
?>
<div id="go_stats_lay">

<button title="Close" onclick="go_stats_close();" ><span class="ui-icon ui-icon-circle-close"></span></button>
 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script language="javascript">
jQuery('#go_stats_accordion').accordion({
   collapsible: true,
   heightStyle: "content"
} );
jQuery( "#go_stats_progress_bar" ).progressbar({
      value: <?= $percentage ?>
    });
	var Pie = createPie("students","200px","white",4,[<?= $percentage_encountered ?>,<?= $percentage_accepted ?>,<?= $percentage_completed ?>, <?= $percentage_mastered ?>],["rgba(255, 102, 0,.25)","rgba(255, 102, 0,.5)","rgba(255, 102, 0,.75)","rgba(255, 102, 0,1)"]);


	 document.getElementById("go_stats_chart_div").appendChild(Pie);
      
</script>
<div id="go_stats_wrap">
<div id="go_stats_accordion">
  <h3 class="go_stats_header">General Information</h3>
  <div id="go_stats_general_information" class="go_stats_box go_border_radius">
  <div id="go_stats_gravatar" class="go_border_radius">
   <?php echo get_avatar($user_id, 96);?></div>
   <div id="go_stats_info" class="go_border_radius">
  <div class="go_info_boxes" class="go_border_radius"><a href="<?= $user_website ?>" style="color: black !important;
font-size: 25px !important;">Website</a></div>
  <div class="go_info_boxes" class="go_border_radius"><?= go_return_options('go_points_name').'<br />'.$current_points?> </div>
  <div class="go_info_boxes" class="go_border_radius"><?= go_return_options('go_currency_name').'<br />'.$current_currency?></div>
  <div class="go_info_boxes" class="go_border_radius">Minutes <?= '<br />'.$current_minutes?></div>
   </div>
   
   
   <div id="go_stats_badges" class="go_border_radius">
<h3><?php echo get_option('go_badges','Badges'); ?></h3>
<div id="badges">
<?php 
$badges_ids = get_user_meta($user_id, 'go_badges', true);
if($badges_ids){
	if(!empty($badges_ids)){
foreach($badges_ids as $key=>$value){
	
	$img = wp_get_attachment_image( $value ,array(100,100), false, $atts );
	$attachment = get_post( $value );
	$meta = $attachment->go_category;
	$title = $attachment->post_title;
	$description = $attachment->post_content;
	$src = $attachment->guid;
	echo '<a href="'.$src.'" target="_blank" title="'.$title.': '.$description.'">'.$img.'</a>';
	}}}

?>
</div>
   </div>
  
   <div id="go_stats_progress_bar" style="margin-top: 115px; height:2em; position:relative; width:530px;;"><div id="go_stats_progress_bar_label" style="position: absolute;
    left: 50%;
    top: 4px;
    font-weight: bold;
    text-shadow: 1px 1px 0 #fff; color: black;
font-size: 19px;"><?= $current_points.'/'.$next_rank_points ?></div></div>
<div id="go_stats_current_rank"><?= $current_rank ?></div>
<div id="go_stats_next_rank"><?= $next_rank ?></div>
    <div id="go_stats_chart_div" style="margin-left: 55px;
margin-top: 40px; width:200px; display:inline;"></div
><div id="go_stats_chart_key"><div><div id="go_key_box" style="background-color:rgba(255, 102, 0,.25);"></div> <?= go_return_options('go_first_stage_name') ?> (<?= $numb_encountered ?>)</div>
<div><div id="go_key_box" style="background-color:rgba(255, 102, 0,.5);"></div><?= go_return_options('go_second_stage_name') ?>(<?= $numb_accepted ?>)</div>
<div><div id="go_key_box" style="background-color:rgba(255, 102, 0,.75);"></div> <?= go_return_options('go_third_stage_name') ?> (<?= $numb_completed ?>)</div>
<div><div id="go_key_box" style="background-color:rgba(255, 102, 0,1);"></div> <?= go_return_options('go_fourth_stage_name') ?> (<?= $numb_mastered ?>)</div></div>
  </div>
  
  
  
  
 
  
  <h3 class="go_stats_header" onclick="go_stats_task_list();"><?= go_return_options('go_tasks_name'); ?></h3>
  <div class="go_stats_box">
   <div id="go_stats_task_columns"><h6 class="go_stats_box_title"></h6>
<ul id="go_stats_encountered_list" class="go_stats_task_lists" ></ul></div>

  </div>
  <h3 class="go_stats_header" onclick="go_stats_third_tab();"><?= go_return_options('go_points_name').' - '. go_return_options('go_currency_name').' - '. 'Minutes' ?></h3>
  <div class="go_stats_box">
  <div id="go_stats_third_tab_points"><h6 class="go_stats_box_title"><?= go_return_options('go_points_name') ?></h6><ul id="go_stats_points" class="go_stats_task_lists" ></ul></div>
  <div id="go_stats_third_tab_currency"><h6 class="go_stats_box_title"><?= go_return_options('go_currency_name') ?></h6><ul id="go_stats_currency" class="go_stats_task_lists" ></ul></div>
  <div id="go_stats_third_tab_minutes"><h6 class="go_stats_box_title">Minutes</h6><ul id="go_stats_minutes" class="go_stats_task_lists" ></ul></div>
  </div>
  
  
  
  
    <h3 class="go_stats_header" onclick=""><?php echo ' Leaderboard';?></h3>
  <div class="go_stats_box">
  <div id="go_stats_leaderboard_order">Order By:<select id="go_stats_leaderboard_select" onchange="go_stats_leaderboard_choice();">
  <option value="points"><?php echo go_return_options('go_points_name'); ?></option>
  <option value="currency"><?php echo go_return_options('go_currency_name'); ?></option>
  <option value="minutes">Minutes</option>
  </select></div>
  <div id="leaderboard_left_box">
  <table id="go_stats_leaderboard_table" ><thead>
  <tr>
  <th class="header">Gamertag</th>
  <th class="header"><?php echo go_return_options('go_points_name'); ?></th>
  <th class="header"><?php echo go_return_options('go_currency_name'); ?></th>
  <th class="header">Minutes</th>
  </tr></thead>
  <tbody id="go_stats_leaderboard_table_body"></tbody>
  </table>
  </div>
  <div id="leaderboard_right_box">
  	<div id="go_stats_class_a_choice"> 
    <h3>Displaying</h3>
    </div>
	<div id="go_stats_class_a_list">
    <h3> Options to Display</h3>
    <?php
$class_a = get_option('go_class_a');
if($class_a){
	foreach($class_a as $key=> $value){
		echo '<li class="ui-corner-all">'.$value.'</li>';
		}
	}
	?>
    </div>
  </div>
</div>
</div>
<?php die();}
function go_stats_task_list(){
	$stage = $_POST['stage'];
	global $wpdb;
 	if($_POST['uid'] != ''){
		$current_user = get_userdata( $_POST['uid'] );
		}else{
 	$current_user = wp_get_current_user();
	}
    $user_id = $current_user->ID ;
	$table_name_go = $wpdb->prefix . "go";
	$list = $wpdb->get_results("select page_id,status,count,post_id, points from ".$table_name_go." where uid = $user_id and (status = 1 or status = 2 or status = 3 or status = 4) order by id desc");
	$x = 0;
	$sym = get_option('go_points_sym');
	foreach($list as $lists){
		switch($lists->status){
			case '1':
			$status_icon = '<div class="go_status_icon" style="background-color:rgba(255, 102, 0,.25);"></div>';
			break;
			case '2':
			$status_icon = '<div class="go_status_icon" style="background-color:rgba(255, 102, 0,.25);"></div><div class="go_status_icon" style="background-color:rgba(255, 102, 0,.5);"></div>';
			break;
			case '3':
			$status_icon = '<div class="go_status_icon" style="background-color:rgba(255, 102, 0,.25);"></div><div class="go_status_icon" style="background-color:rgba(255, 102, 0,.5);"></div><div class="go_status_icon" style="background-color:rgba(255, 102, 0,.75);" ></div>';
			break;
			case '4':
			$status_icon = '<div class="go_status_icon" style="background-color:rgba(255, 102, 0,.25);"></div><div class="go_status_icon" style="background-color:rgba(255, 102, 0,.5);"></div><div class="go_status_icon" style="background-color:rgba(255, 102, 0,.75);"></div><div class="go_status_icon" style="background-color:rgba(255, 102, 0,1); font-size:8px;"></div>';
			break;
			}
			if($lists->page_id){ $post_id = $lists->page_id; } else {$post_id = $lists->post_id;}
			$x++;
		?> <li class="go_<?php echo isEven($x);?>" ><a href=" <?php echo get_permalink( $post_id); ?>" target="_blank" style="color:rgba(0,0,0,.4); font-size:12px;"> <?= get_the_title($post_id) ?> (<?php echo go_display_points($lists->points); ?>)</a><div class="go_status_icon_wrap"> <?php echo $status_icon; ?></div><div style="float:right;"><?php echo $lists->count ?></div></li> <?php
		}
		die();
	}
	
function go_stats_points(){
		global $wpdb;
 	if($_POST['uid'] != ''){
		$current_user = get_userdata( $_POST['uid'] );
		}else{
 	$current_user = wp_get_current_user();
	}
    $user_id = $current_user->ID ;
	$table_name_go = $wpdb->prefix . "go";
	$list = $wpdb->get_results("select post_id, points, reason from ".$table_name_go." where uid = $user_id and points != 0 order by id desc");
	$x = 0;
	$sym = get_option('go_points_sym');
	foreach($list as $lists){
		if ($lists->post_id != 0){
			$x++;
		?> <li class="go_<?= isEven($x)?>" ><a href=" <?= get_permalink( $lists->post_id) ?>" style="color:rgba(0,0,0,.4); font-size:12px;"> <?= get_the_title($lists->post_id) ?> (<?=go_display_points($lists->points) ?>)</a></li> <?php
		} else{
			$x++;
		?> <li class="go_<?= isEven($x)?>" ><?= $lists->reason ?> (<?= go_display_points($lists->points) ?>)</li> <?php
			}}
		die(); 
	}
function go_stats_currency(){
		global $wpdb;
 	if($_POST['uid'] != ''){
		$current_user = get_userdata( $_POST['uid'] );
		}else{
 	$current_user = wp_get_current_user();
	}
    $user_id = $current_user->ID ;
	$table_name_go = $wpdb->prefix . "go";
	$list = $wpdb->get_results("select post_id, currency, reason from ".$table_name_go." where uid = $user_id and currency != 0 order by id desc");
	$x = 0;
	$sym = get_option('go_currency_sym');
	foreach($list as $lists){
		if ($lists->post_id != 0){
			$x++;
		?> <li class="go_<?= isEven($x)?>" ><a href=" <?= get_permalink( $lists->post_id) ?>" style="color:rgba(0,0,0,.4); font-size:12px;"> <?= get_the_title($lists->post_id) ?> (<?= $lists->currency ?> <?=  $sym ?>)</a></li> <?php
		} else{
			$x++;
		?> <li class="go_<?= isEven($x)?>" ><?= $lists->reason ?> (<?= go_display_currency($lists->currency) ?>)</li> <?php
			}}
		die(); 
	}
function go_stats_minutes(){
		global $wpdb;
 	if($_POST['uid'] != ''){
		$current_user = get_userdata( $_POST['uid'] );
		}else{
 	$current_user = wp_get_current_user();
	}
    $user_id = $current_user->ID ;
	$table_name_go = $wpdb->prefix . "go";
	$list = $wpdb->get_results("select minutes, reason from ".$table_name_go." where uid = $user_id and minutes != 0 order by id desc");
	$x = 0;
	foreach($list as $lists){
		$reason_array = unserialize($lists->reason);
			$x++;
		?> <li class="go_<?= isEven($x)?>"><?= $lists->minutes ?> Minutes @ <?= $reason_array['time'] ?> For <?= $reason_array['reason'] ?></li> <?php
		}
		die(); 
	}
	add_action('wp_ajax_go_stats_leaderboard','go_stats_leaderboard');
function go_stats_leaderboard(){
	global $wpdb;
	$class_a_choice= $_POST['class_a_choice'];
	$table_name_go_totals= $wpdb->prefix.'go_totals';
	$ids = $wpdb->get_results("SELECT uid
FROM ".$table_name_go_totals."
order by ".$_POST['order']." Desc");
	foreach($ids as $uid){
		foreach($uid as $id){
		$class_a = get_user_meta($id, 'go_classifications',true);
		if($class_a){
		$class_key = array_keys($class_a);
		$intersect = array_intersect($class_key, $class_a_choice);
		if(!empty($intersect)){
			$points =go_return_points($id);
			$currency = go_return_currency($id);
			$minutes = go_return_minutes($id);
			$user_data_key = get_userdata( $id ); 
		$user_display = $user_data_key->display_name;
			echo '<tr><td>'.$user_display.'</td><td>'.$points.'</td><td>'.$currency.'</td><td>'.$minutes.'</td></tr>';
			}
		}
		}
		}
		die();
	}
	

 ?>