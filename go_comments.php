<?php

function go_display_comment_author() {
	global $comment;
	$user_id = $comment->user_id;
	$author = $comment->comment_author;
	if ($user_id == 0) {
		return $author;
	} else {
		$points = get_user_meta($user_id, 'go_rank', true);
		$focus = get_user_meta($user_id, 'go_focus', true);

		if (!empty($focus)) {
			if (is_array($focus)) {
				foreach ($focus as $val) {
					$careers .= $val.'/';
				}
				$careers = substr($careers, 0, (strlen($careers) - 1));	
			} else {
				$careers = $focus;	
			}
		}

		if (!empty($careers)) {
			if (!empty($points)) {
				return $author.'<br/>'.'('.$careers.', '.$points[0][0].')';
			} else {
				return $author;
			}
		} else {
			if (!empty($points)) {
				return $author.'<br/>'.'('.$points[0][0].')';
			} else {
				return $author;
			}
		}
	}
}

?>