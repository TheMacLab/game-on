<?php 
	global $wpdb;
	$file_name = $real_file = plugin_dir_path( __FILE__ ) . '/' . 'go_definitions.php';
	$array = explode(',','go_tasks_name,go_tasks_plural_name,go_currency_name,go_points_name,go_first_stage_name,go_second_stage_name,go_second_stage_button,go_third_stage_name,go_third_stage_button,go_fourth_stage_name,go_fourth_stage_button,go_currency_prefix,go_currency_suffix, go_points_prefix, go_points_suffix, go_admin_bar_add_switch, go_repeat_button, go_class_a_name, go_class_b_name,go_multiplier,go_multiplier_switch,go_multiplier_rounding,go_minutes_color_limit');
	foreach($array as $key=>$value){
$value = trim($value);
$content = get_option($value);
if(is_array($content)){
	$content = serialize($content);
	}
		$string .= 'define("'.$value.'",\''.$content.'\',TRUE);';
		}
	
 file_put_contents ( $file_name, '<?php '.$string.' ?>' );

	?>