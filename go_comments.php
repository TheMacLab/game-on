<?php

function go_display_comment_author_points(){
	global $comment;
	$user_id = $comment->user_id;
	$author = $comment->comment_author;
	if($user_id == 0){
		return $author;
	} else{
		$points = get_user_meta($user_id, 'go_rank', true);
		return $author.'<br/>'.'('.$points[0][0].')';
	}
}

?>