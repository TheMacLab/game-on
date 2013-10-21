<?php global $wpdb;
	$file_name = $real_file = plugin_dir_path( __FILE__ ) . '/' . 'go_definitions.php';
	$array = explode(',','go_tasks_name,go_tasks_plural_name,go_currency_name,go_points_name,go_first_stage_name,go_second_stage_name,go_second_stage_button,go_third_stage_name,go_third_stage_button,go_fourth_stage_name,go_fourth_stage_button,go_currency_prefix,go_currency_suffix, go_points_prefix, go_points_suffix, go_admin_bar_add_switch, go_repeat_button, go_class_a_name, go_class_b_name,go_max_infractions');
	foreach($array as $key=>$value){
$value = trim($value);
		$string .= 'define("'.$value.'","'.get_option($value).'",TRUE);';
		}
	
 file_put_contents ( $file_name, '<?php '.$string.' ?>' ); ?>