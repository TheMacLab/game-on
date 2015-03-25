<?php

function go_display_comment_author() {
	global $comment;
	$author_id = $comment->user_id;
	$author = $comment->comment_author;
	$author_obj = get_userdata($author_id);
	$author_roles = $author_obj->roles;
	$is_admin = false;
	if (is_array($author_roles)) {
		foreach ($author_roles as $role) {
			if ($role == "administrator") {
				$is_admin = true;
				break;
			}
		}
	} else {
		if ($author_roles == "administrator") {
			$is_admin = true;
		}
	}
	
	$points = get_user_meta($author_id, 'go_rank', true);
	$focus = get_user_meta($author_id, 'go_focus', true);
	$careers = go_display_user_focuses($author_id);
	if (!empty($careers)) {
		if (!empty($points)) {
			return "<a href='#' onclick='go_admin_bar_stats_page_button(\"{$author_id}\");'>{$author}</a><br/>({$careers}, {$points[0][0]})";
		} else {
			return $author;
		}
	} else {
		if (!empty($points)) {
			return "<a href='#' onclick='go_admin_bar_stats_page_button(\"{$author_id}\");'>{$author}</a><br/>({$points[0][0]})";
		} else {
			return $author;
		}
	}
}
?>