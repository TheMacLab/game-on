<?php 

function go_test_shortcode ( $atts, $content ) {
	extract(shortcode_atts(array(
		'type' => 'radio',
		'question' => 'What is the ultimate answer to life, the universe, and everything?',
		'possible_answers' => 'Pie### Burritos### 42### There is no answer',
		'key' => '42',
		'test_id' => '0',
		'total_num' => '1'
	), $atts) );
	$possible_answers_str = preg_replace("/\#\#\#\s*/", "### ", $possible_answers);
	$answer_array = explode("### ", $possible_answers_str);
	$answer_array_keys = array_keys($answer_array);
	if ($type == 'checkbox') {
		$key_str = preg_replace("/\s*\#\#\#\s*/", "### ", $key);
		$key_array = explode("### ", $key_str);
		$key_array_keys = array_keys($key_array);
	}
	
	$key_check = false;
	$key_match = 0;
	
	if ($type == 'radio') {
		for($i = 0; $i < count($answer_array_keys); $i++) {
			if (strtolower($answer_array[$i]) == strtolower($key)) {
				$key_check = true;
				break;
			}
		}	
	} else if ($type == 'checkbox') {
		for($i = 0; $i < count($answer_array_keys); $i++) {
			for ($x = 0; $x < count($key_array_keys); $x++) {
				if (strtolower($answer_array[$i]) == strtolower($key_array[$x])) {
					$key_match++;
					break;
				}
			}
		}
		if ($key_match == count($key_array_keys) && $key_match >= 2) {
			$key_check = true;	
		}
	}

	if (count($answer_array_keys) >= 2 && $question != '' && $key_check == true) {
		$output_array = array();
		if ($type == 'radio') {
			for ($i = 0; $i < count($answer_array_keys); $i++) {
				$upper_name = ucfirst($answer_array[$i]);
				array_push($output_array, "<li class='go_test go_test_element'><input type='radio' name='go_test_answer_".$test_id."' value='".$upper_name."'> ".$upper_name."</input></li>");
			}
		} else if ($type == 'checkbox') {
			for ($i = 0; $i < count($answer_array_keys); $i++) {
				$upper_name = ucfirst($answer_array[$i]);
				array_push($output_array, "<li class='go_test go_test_element'><input type='checkbox' name='go_test_answer_".$test_id."_".$answer_array[$i]."' value='".$upper_name."'> ".$upper_name."</input></li>");
			}
		}
		$output_array_str = implode(" ", $output_array);
		if ($total_num > 1) {
			$rtn_output = "<div class='go_test_container'><p id='go_test_error_msg' class='go_test' style='color: red;'></p><ul id='go_test_".$test_id."' class='go_test go_test_list go_test_".$type."'><span style='font-weight:700;'>".ucfirst($question)."</span>".$output_array_str."</div>";
		} else {
			$rtn_output = "<div class='go_test_container'><p id='go_test_error_msg' class='go_test' style='color: red;'></p><ul id='go_test' class='go_test go_test_list go_test_".$type."'><span style='font-weight:700;'>".ucfirst($question)."</span>".$output_array_str."<button class='go_test_submit' style='margin-top: 10px;'>GO!</button></ul></div>";
			
		}
		
		return $rtn_output;
	} else {
		if (current_user_can('manage_options')) {
			$error_array = array();
			
			if (count($answer_array_keys) < 2) {
				print_r($answer_array);
				array_push($error_array, "<b>ERROR: A minimum of two possible answers are required.</b>");
			}
			
			if ($key_check == false) {
				array_push($error_array, "<b>ERROR: The correct answer provided does not match any of the possible answers.</b>");
			}
		
			if (mb_strlen($question) === 0) {
				array_push($error_array, "<b>ERROR: The question attribute has been left blank.</b>");
			}
			$error_array_str = implode("<br/>", $error_array);
			return ($error_array_str."<br/>");
		} else {
			return "";
		}
	}
}
add_shortcode('go_test', 'go_test_shortcode'); 
?>