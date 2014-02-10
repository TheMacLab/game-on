<?php
/*
	This is the file that displays a page on the admin side of wordpress for the list of rankings.
	Allows administrator to edit points required for each ranking and to delete certain rankings/add others. 
*/


function go_ranks() {
	global $wpdb;
	$dir = plugin_dir_url(__FILE__);
	add_submenu_page( 'game-on-options.php', 'Ranks', 'Ranks', 'manage_options', 'go_ranks', 'go_ranks_menu');
}

function go_ranks_menu() {
	global $wpdb;
	if (!current_user_can('manage_options'))  { 
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} 
	else{

		echo media_buttons($editor_d='content');
     
    ?>  
    
        <form method="post" action="" style="margin: 2px 0px 2px 0px;">
        	<input name="go_fix_ranks" type="submit" value="Fix Ranks"/><a  class="go_task_opt_help" onclick="go_display_help_video('http://maclab.guhsd.net/go/video/ranks/fixRanks.mp4');" style="background: #DBDBDB !important; float: inherit !important;" >?</a>
        </form>
        
        <form method="POST" action="" style="margin: 2px 0px 2px 0px; ">
    		<input type="submit" name="go_reset_ranks" value="Reset Ranks"/><a  class="go_task_opt_help" onclick="go_display_help_video('http://maclab.guhsd.net/go/video/ranks/resetRanks.mp4');" style="background: #DBDBDB !important; float: inherit !important;" >?</a>
    	</form>
       
    <?php 
	
		if(isset($_POST['go_reset_ranks'])){
			$ranks = array('Level 01'=>0, 'Level 02'=> 150, 'Level 03'=> 315, 'Level 04'=> 495, 'Level 05'=> 690, 'Level 06'=> 900, 'Level 07'=> 1125, 'Level 08'=> 1365, 'Level 09'=> 1620,'Level 10'=> 1890, 'Level 11'=> 2175,'Level 12'=> 2475,'Level 13'=> 2790,'Level 14'=> 3120,'Level 15'=> 3465,'Level 16'=> 3825,'Level 17'=> 4200,'Level 18'=> 4590,'Level 19'=> 4995,'Level 20'=> 5415,);
			update_option('go_ranks',$ranks); 
		}
	
		if(isset($_POST['go_fix_ranks'])){
			$table_name_user_meta = $wpdb->prefix . "usermeta";
			$table_name_go_totals = $wpdb->prefix . "go_totals";
			global $default_role;
			$role = get_option('go_role',$default_role);
			$uid = $wpdb->get_results("
				SELECT user_id
				FROM ".$table_name_user_meta."
				WHERE meta_key =  'wp_capabilities'
				AND (meta_value LIKE  '%".$role."%' or meta_value like '%administrator%')
			");
				
			foreach($uid as $id){foreach($id as $uids){
				
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
			} // Ends foreach 
		} // Ends if
	} // Ends else

	$ranks = get_option('go_ranks',false);

	?>
     <div>   
         <table style="width:350px;" id="ranks_table" class="widefat">
            <th style="width:250px;">Rank <a  class="go_task_opt_help" onclick="go_display_help_video('http://maclab.guhsd.net/go/video/ranks/rank.mp4');" style="background: #DBDBDB !important; ">?</a></th>
            <th style="width:125px;"><?php echo go_return_options('go_points_name'); ?><a  class="go_task_opt_help" onclick="go_display_help_video('http://maclab.guhsd.net/go/video/ranks/points.mp4');" style="background: #DBDBDB !important;">?</a></th>
            <th></th>
            <th title="Insert Shortcodes (Badges) in the box and they will run when the rank is reached.">Triggers <a  class="go_task_opt_help" onclick="go_display_help_video('http://maclab.guhsd.net/go/video/ranks/triggers.mp4');" style="background: #DBDBDB !important;">?</a></th>
            <tbody id="table_rows">
            <?php  
				foreach($ranks as $level => $points){
					$level_id = preg_replace('/\s+/', '_', $level);
					echo '<tr><td>'.$level.'</td><td>'.$points.'</td><td><button onclick="go_remove_ranks(\''.$level.'\');">Remove</button></td><td id="'.$level_id.'_trigger" class="ui-state-default ui-corner-all"><span onclick="go_add_trigger_field(\''.$level.'\');" class="ui-icon ui-icon-triangle-1-e" ></span></td></tr>';
					
				}
			?>
       		</tbody>
        </table>
	</div>
	<div class="widefat">
        Ranks: <textarea id="ranks"></textarea>
        <?php echo go_return_options('go_points_name');?>: <textarea id="points"></textarea>
        Separator: <textarea id="separator"></textarea><span style="float: left;"><a  class="go_task_opt_help" onclick="go_display_help_video('http://maclab.guhsd.net/go/video/ranks/addRank.mp4');" style="background: #DBDBDB !important;">?</a></span><button onclick="go_add_ranks();" >+</button>
	</div>
    <script language="javascript">
		
		function go_add_trigger_field(level){
			ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';
			jQuery.ajax({
				type: "post",url: ajaxurl,data: { action: 'go_get_rank_trigger', rank: level},
				success: function(html){
					var level_id = level.replace(/ /g,"_");
				jQuery('#'+level_id+'_trigger').append(html);
				}
			});
		}
	
		function go_save_rank_trigger(level){
			var level_id = level.replace(/ /g,"_");
			ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: { 
					action: 'go_save_rank_trigger', 
					rank: level, 
					trigger : jQuery('#go_rank_trigger_'+level_id).val() 
				}
			});
		}
	
		function go_add_ranks(){
			ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: { 
					action: 'go_add_ranks', 
					ranks: jQuery('#ranks').val(), 
					points: jQuery('#points').val(), 
					separator: jQuery('#separator').val()
				},
				success: function(html){
					jQuery('#table_rows').html(html);;
				}
			}); 
		}
	
		function go_remove_ranks(rank_key){
			ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: { 
					action: 'go_remove_ranks', 
					ranks: rank_key
				},
				success: function(html){
					jQuery('#table_rows').html(html);;
				}
			}); 
		}
</script>
  
		<?php		
	}
	
}
	
function go_add_ranks(){
	global $wpdb;
	$new_ranks = $_POST['ranks'];
	$points = $_POST['points'];
	$separator = $_POST['separator'];
	$ranks = get_option('go_ranks',false);
	if(!$ranks){
		$ranks = array('Level 1'=> 0);
	} 
	if($separator == ''){
		if(is_numeric($points)){
			$ranks[$new_ranks] =  $points;
		}
	} 
	else {
		$new_ranks = explode($separator,$new_ranks);
		$points = explode($separator,$points);
		foreach($new_ranks as $index => $new_ranks){
			$ranks[$new_ranks] = $points[$index];
		}
	}
	
	
	asort($ranks);
	update_option( 'go_ranks', $ranks );
	foreach($ranks as $ranks => $points){
		echo '<tr><td>'.$ranks.'</td><td>'.$points.'</td><td><button onclick="go_remove_ranks(\''.$ranks.'\');">Remove</button></td></tr>';
	}
	
	die();
}	

function go_remove_ranks(){
	global $wpdb;
	$new_ranks = $_POST['ranks'];
	$ranks = get_option('go_ranks',false);
	unset($ranks[$new_ranks]);
	update_option( 'go_ranks', $ranks );
	
	foreach($ranks as $ranks => $points){
		echo '<tr><td>'.$ranks.'</td><td>'.$points.'</td><td><button onclick="go_remove_ranks(\''.$ranks.'\');">Remove</button></td></tr>';
	}
	
	die();
}
	
function go_update_ranks($user_id, $total_points){
	global $wpdb;
	global $current_rank;
	go_get_rank($user_id);
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
	global $current_points;
	if($next_rank != ''){
		if($total_points >= $next_rank_points){
		
		$ranks = get_option('go_ranks');
		$ranks_keys = array_keys($ranks);
		$new_rank_key = array_search($next_rank, $ranks_keys);
		$new_next_rank = $ranks_keys[($new_rank_key+1)];
		$new_rank = array(array($next_rank, $next_rank_points),	array($new_next_rank, $ranks[$new_next_rank]));
		update_user_meta($user_id, 'go_rank', $new_rank);
		$update = true;
		}
		
		if($total_points < $current_rank_points){
		
		$ranks = get_option('go_ranks');
		$ranks_keys = array_keys($ranks);
		$current_rank_key = array_search($current_rank, $ranks_keys);
		$prev_rank = $ranks_keys[($current_rank_key-1)];
		$prev_rank_points = $ranks[$prev_rank];
		$new_rank = array(array($prev_rank, $prev_rank_points),	array($current_rank, $current_rank_points));
		update_user_meta($user_id, 'go_rank', $new_rank);
		$update = true;
		}
	}
	else {
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
		$update = true;
	}
	if($update){
		go_get_rank($user_id);
		global $current_rank;
		global $current_rank_points;
		global $next_rank;
		global $next_rank_points;
		global $counter;
		$counter++;
		$space = $counter*85;
		echo '<div id="go_notification" class="go_notification" style="top: '.$space.'px; color: #FFD700;"> '.$current_rank.'!</div><script type="text/javascript" language="javascript">go_notification();
		jQuery("#go_admin_bar_rank").html("'.$current_rank.'");
		</script>';
		$ranks_triggers = get_option('go_ranks_trigger', false);
		if($ranks_triggers){
			if($ranks_triggers[$current_rank]){
				echo do_shortcode($ranks_triggers[$current_rank]);
			}
		}	
	}
}

function go_get_rank($user_id) {
	global $wpdb;
	$rank = get_user_meta($user_id, 'go_rank');
	global $current_rank;
	global $current_rank_points;
	global $next_rank;
	global $next_rank_points;
		$current_rank = $rank[0][0][0];
	$current_rank_points = $rank[0][0][1];
	$next_rank = $rank[0][1][0];
	$next_rank_points = $rank[0][1][1];
}
function go_get_all_ranks() {
	$all_ranks = get_option('go_ranks');
	$all_ranks_sorted = array();
	 foreach($all_ranks as $level => $points) {
		 $all_ranks_sorted[] = array('name' => $level , 'value' => $points);
		 }
	return $all_ranks_sorted;
}
function go_clean_ranks() {
	$all_ranks = get_option('go_ranks');
	$all_ranks_sorted = array();
	foreach($all_ranks as $level => $points) {
    	echo '<option value="'.$points.'">'.$level.'</option>';
	}
}
function go_get_rank_key($points) {
	global $wpdb;
	$ranks = get_option('go_ranks',false);
	foreach ($ranks as $key => $rank ) {
		switch($rank) {
			case $points:
				return $key;
				break;
		}
	}
}
add_action('wp_ajax_go_get_rank_trigger','go_get_rank_trigger');
function go_get_rank_trigger(){
	global $wpdb;
	$rank = $_POST['rank'];
	$option = get_option('go_ranks_trigger', false);
	if($option){
		if($option[$rank]){
			$display = wp_unslash($option[$rank]);
			} else{ $display= '';}
			 
		}else {$display='';}
					$rank_id = preg_replace('/\s+/', '_', $rank);
		echo '<input type="text" id="go_rank_trigger_'.$rank_id.'" value=\''.$display.'\'/><button onclick="go_save_rank_trigger(\''.$rank.'\');">Save</button>';
		die();
	}
	
	
add_action('wp_ajax_go_save_rank_trigger','go_save_rank_trigger');
function go_save_rank_trigger(){
	global $wpdb;
	$rank = $_POST['rank'];
	$trigger = $_POST['trigger'];
	$option = get_option('go_ranks_trigger', false);
	if($option){
		$option[$rank] = $trigger;
			 
		}else {$option = array($rank => $trigger);}
			update_option('go_ranks_trigger', wp_unslash($option));		
		die();
	}

?>