<?php 
function go_define_options(){
	$file_name = plugin_dir_path( __FILE__ ) . '/' . 'go_definitions.php';
	$array = explode(',','go_tasks_name, go_tasks_plural_name, go_first_stage_name, go_second_stage_name, go_third_stage_name, go_fourth_stage_name, go_fifth_stage_name, go_abandon_stage_button, go_second_stage_button, go_third_stage_button, go_fourth_stage_button, go_fifth_stage_button, go_points_name, go_points_prefix, go_points_suffix, go_currency_name, go_currency_prefix, go_currency_suffix, go_bonus_currency_name, go_bonus_currency_prefix, go_bonus_currency_suffix, go_penalty_name, go_penalty_prefix, go_penalty_suffix, go_level_names, go_level_plural_names, go_organization_name, go_class_a_name, go_class_b_name, go_focus_name, go_stats_name, go_inventory_name, go_badges_name, go_leaderboard_name, go_presets, go_admin_bar_display_switch, go_admin_bar_user_redirect, go_admin_bar_add_switch, go_ranks, go_class_a, go_class_b, go_focus_switch, go_focus, go_admin_email, go_video_width, go_video_height, go_full_student_name_switch, go_multiplier_switch, go_multiplier_threshold, go_penalty_switch, go_penalty_threshold, go_multiplier_percentage, go_data_reset_switch, go_analysis_script_day');
	foreach($array as $key=>$value){ 	
		$value = trim($value);
		$content = get_option($value);
		if(is_array($content)){
			$content = serialize($content);
		}
		$string .= 'define("'.$value.'",\''.$content.'\',TRUE);';
	}
	
	file_put_contents ( $file_name, '<?php '.$string.' ?>' );
}
?>