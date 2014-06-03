<?php

//Creates table for indivual logs.

function go_table_individual() {
   global $wpdb;

   $table_name = $wpdb->prefix . "go";
   
   $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  uid INT,
  status INT,
  post_id INT,
  page_id INT,
  count INT DEFAULT 0,
  c_fail_count INT DEFAULT 0,
  m_fail_count INT DEFAULT 0,
  c_passed BOOLEAN DEFAULT 0,
  m_passed BOOLEAN DEFAULT 0,
  points INT,
  currency INT,
  infractions INT,	 
  minutes VARCHAR (200),
  reason VARCHAR (200),
  timestamp VARCHAR (200), 
  UNIQUE KEY  id (id)
    );";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
 
}



//Creates a table for totals.

function go_table_totals() {
   global $wpdb;

   $table_name = $wpdb->prefix . "go_totals";
      
   $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  uid  INT,
  currency  INT,
  points  INT,
  minutes  VARCHAR (200),
  infractions INT,
  UNIQUE KEY  id (id)
    );";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
 
}



//Updates the rank totals upon activation of plugin.
function go_ranks_registration(){
	global $wpdb;
	$ranks = get_option('go_ranks',false);
		if(!$ranks){
			$ranks = array('Level 01'=>0, 'Level 02'=> 150, 'Level 03'=> 315, 'Level 04'=> 495, 'Level 05'=> 690, 'Level 06'=> 900, 'Level 07'=> 1125, 'Level 08'=> 1365, 'Level 09'=> 1620,'Level 10'=> 1890, 'Level 11'=> 2175,'Level 12'=> 2475,'Level 13'=> 2790,'Level 14'=> 3120,'Level 15'=> 3465,'Level 16'=> 3825,'Level 17'=> 4200,'Level 18'=> 4590,'Level 19'=> 4995,'Level 20'=> 5415,);
			update_option('go_ranks',$ranks);
			}
	}
// Updates the presets for task creation upon activation of plugin. 
function go_presets_registration(){
	global $wpdb;
	$presets = get_option('go_presets');
	if (!$presets){
		$presets = array(
		'Tier 1' => array(0 => '5,5,10,30', 1=> '0,0,3,9'), 
		'Tier 2' => array(0 => '5,5,20,60', 1=> '0,0,6,18'),
		'Tier 3' => array(0 => '5,5,40,120', 1=> '0,0,12,36'),
		'Tier 4' => array(0 => '5,5,70,210', 1=> '0,0,21,63'),
		'Tier 5' => array(0 => '5,5,110,330', 1=> '0,0,33,99'));   
		update_option('go_presets',$presets);
	}
}

function go_install_data(){
	
	global $wpdb;
	 $table_name_user_meta = $wpdb->prefix . "usermeta";
	 $table_name_go_totals = $wpdb->prefix . "go_totals";
	 $table_name_go = $wpdb->prefix . "go";

global $default_role;
	$role = get_option('go_role',$default_role);
	
	$options_array = array(
	'go_first_stage_name' => 'Encountered',
	'go_second_stage_name' => 'Accepted',
	'go_third_stage_name' => 'Completed',
	'go_fourth_stage_name' => 'Mastered',
	'go_second_stage_button' => 'Accept Quest',
	'go_third_stage_button' => 'Complete Quest',
	'go_fourth_stage_button' => 'Master Quest',
	'go_currency_prefix' => '',
	'go_currency_suffix' => 'g',
	'go_points_prefix' => '',
	'go_points_suffix' => 'XP',
	'go_currency_name' => 'Gold',
	'go_points_name' => 'Experience',
	'go_admin_bar_add_switch' => 'Off',
	'go_repeat_button' => 'Repeat Quest',
	'go_class_a_name' => 'Period',
	'go_class_b_name' => 'Computer',
	'go_class_a' => array('Period 1', 'Period 2', 'Period 3', 'Period 4', 'Period 5', 'Period 6', 'Period 7'),
	'go_class_b' => array('Computer 01', 'Computer 02', 'Computer 03', 'Computer 04', 'Computer 05', 'Computer 06', 'Computer 07', 'Computer 08', 'Computer 09', 'Computer 10', 'Computer 11', 'Computer 12', 'Computer 13', 'Computer 14', 'Computer 15', 'Computer 16', 'Computer 17', 'Computer 18', 'Computer 19', 'Computer 20', 'Computer 21', 'Computer 22', 'Computer 23', 'Computer 24', 'Computer 25', 'Computer 26', 'Computer 27', 'Computer 28', 'Computer 29', 'Computer 30', 'Computer 31', 'Computer 32', 'Computer 33', 'Computer 34', 'Computer 35', 'Computer 36', 'Computer 37', 'Computer 38', 'Computer 39', 'Computer 40', 'Computer 41', 'Computer 42', 'Computer 43', 'Computer 44'),
	'go_tasks_name'=>'Quest',
	'go_tasks_plural_name'=>'Quests',
	'go_multiplier'=>'a:9:{i:0;s:14:"-40,-9000,-901";i:1;s:13:"-30,-900,-601";i:2;s:13:"-20,-600,-301";i:3;s:11:"-10,-300,-1";i:4;s:10:"10,301,600";i:5;s:10:"20,601,900";i:6;s:11:"30,901,1200";i:7;s:12:"40,1201,1500";i:8;s:13:"50,1501,90000";}',
	'go_multiplier_switch'=>'Off',
	'go_infractions_name'=>'Infractions',
	'go_max_infractions'=> 3,
	'go_multiplier_rounding'=>'a:9:{i:0;s:1:"3";i:1;s:1:"3";i:2;s:1:"3";i:3;s:1:"3";i:4;s:1:"2";i:5;s:1:"2";i:6;s:1:"2";i:7;s:1:"2";i:8;s:1:"2";}',
	'go_minutes_color_limit'=>'-900,-600,-300,0',
	'go_focus_name' => 'Focus',
	'go_focus_switch'=>'Off',
	'go_focus'=>'',
	'go_time_reset_switch' =>'Off',
	'go_video_height' => '540',
	'go_video_width' => '864'
	);
	foreach($options_array as $key => $value){
		 add_option( $key, $value );
		}
	
	
	
	$uid = $wpdb->get_results("SELECT user_id
FROM ".$table_name_user_meta."
WHERE meta_key =  '".$wpdb->prefix."capabilities'
AND (meta_value LIKE  '%".$role."%' or meta_value like '%administrator%')");
 foreach($uid as $id){
 foreach($id as $uids){
			$check = (int)$wpdb->get_var("select uid from ".$table_name_go_totals." where uid = $uids ");
			$total_points = (int)$wpdb->get_var("select sum(points) from ".$table_name_go." where uid = $uids ");
			$total_currency = (int)$wpdb->get_var("select sum(currency) from ".$table_name_go." where uid = $uids ");

				if($check == 0){
					$wpdb->insert( $table_name_go_totals,array( 'uid' => $uids, 'points'=>$total_points, 'currency'=>$total_currency ), array(  '%d' ) );
						} else {
		 					$wpdb->update( $table_name_go_totals, array( 'uid' => $uids,  'points'=>$total_points, 'currency'=>$total_currency), array( 'uid' => $uids ), array('%d'), array( '%d') ) ;
								}
								$badges_ids = get_user_meta($uids, 'go_badges', true);	
								if(!$badges_ids){
		update_user_meta($uids, 'go_badges', array());
}					
$rank_check =	get_user_meta($uids, 'go_rank');
 if(empty($rank_check) || $rank_check == ''){ 
 $ranks = get_option('go_ranks', false);
 $current_points = go_return_points($uids);
 while($current_points >= current($ranks)){
	 next($ranks);
	 }
 $next_rank_points = current($ranks);
 $next_rank = array_search($next_rank_points, $ranks);
 $rank_points = prev($ranks);
 $new_rank = array_search($rank_points, $ranks);
 $new_rank_array= array(array($new_rank, $rank_points),array($next_rank, $next_rank_points));
  update_user_meta($uids,'go_rank', $new_rank_array );
}								

								
				}
 }
}
	
//Adds user id to the totals table upon user creation.
function go_user_registration($user_id) {
 global $wpdb;
 global $role_default;
 $table_name_go_totals = $wpdb->prefix . "go_totals";
 $table_name_user_meta = $wpdb->prefix . "usermeta";
 $role = get_option('go_role','subscriber');
 $user_role = get_user_meta($user_id,$wpdb->prefix.'capabilities', true);
 if(array_search(1, $user_role) == $role || array_search(1, $user_role) == 'administrator'){
 	$ranks = get_option('go_ranks');
		$current_rank_points = current($ranks);
		$current_rank = array_search($current_rank, $ranks);
		$next_rank_points = next($ranks);
		$next_rank = array_search($next_rank_points, $ranks);
		$new_rank = array(array($current_rank, $current_rank_points),array($next_rank, $next_rank_points));
 $wpdb->insert( $table_name_go_totals,array( 'uid' => $user_id, 'points' => 0 ),  array(  '%s' ) );
 update_user_meta($user_id,'go_rank', $new_rank);
 }
}	


//Deltes all rows related to a user in the individual and total tables upon deleting said user.
function go_user_delete($user_id){
 	global $wpdb;
	$table_name_go_totals = $wpdb->prefix . "go_totals";
	$table_name_go = $wpdb->prefix . "go";

	$wpdb->delete( $table_name_go_totals, array('uid'=> $user_id));
	$wpdb->delete( $table_name_go, array('uid'=> $user_id) );
}

function go_open_comments(){
	global $wpdb;
	$wpdb->update($wpdb->posts, array('comment_status'=>'open', 'ping_status'=>'open'), array('post_type'=>'tasks'));	
}

?>