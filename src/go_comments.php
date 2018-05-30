<?php
/**
 * Filters the comment author content that gets output by get_comment_author().
 *
 * Filters the comment author content. If the author is an admin or not an existing user, the
 * author's name is returned. If the author is an existing user and not an admin, the author's
 * profession and rank will be returned. The author's profession will be omitted if it doesn't exist.
 *
 * @since 1.0.0
 *
 * @param  string	  $author_name The name of the author or "Anonymous".
 * @param  int	      $comment_id  The id of the comment.
 * @param  WP_Comment $comment	   An object of all the important comment data.
 * @return string The filtered comment author content.
 */
function go_display_comment_author ( $author_name, $comment_id, $comment ) {
	$author_id = $comment->user_id;
	$author_obj = get_userdata( $author_id );
    if (!empty($author_obj) && !is_wp_error($author_obj)) {
        $author_roles = $author_obj->roles;

        $is_admin = false;
        if (is_array($author_roles)) {
            foreach ($author_roles as $role) {
                if ('administrator' == $role && current_user_can('manage_options')) {
                    $is_admin = true;
                    break;
                }
            }
        } else {
            if ('administrator' == $author_roles && current_user_can('manage_options')) {
                $is_admin = true;
            }
        }
        if ($is_admin) {
            return $author_name;
        } else {
            $ranks = get_user_meta($author_id, 'go_rank', true);
            $points_array = $ranks[0];
            $rank_name = $points_array[0];
            $focus = get_user_meta($author_id, 'go_focus', true);
            if (!empty($focus)) {
                if (is_array($focus)) {
                    for ($i = 0; $i < count($focus); $i++) {
                        if (!empty($focus[$i])) {
                            $careers .= $focus[$i];
                            if (($i + 1) < count($focus)) {
                                $careers .= '/';
                            }
                        }
                    }
                } else {
                    $no_focus_str = 'No ' . get_option('go_focus_name', 'Profession');
                    if ($focus != $no_focus_str) {
                        $careers = $focus;
                    }
                }
            }
            if (!empty($careers)) {
                if (!empty($ranks)) {
                    return $author_name . '<br/>' . '(' . $careers . ', ' . $rank_name . ')';
                } else {
                    return $author_name;
                }
            } else {
                if (!empty($ranks)) {
                    return $author_name . '<br/>' . '(' . $rank_name . ')';
                } else {
                    return $author_name;
                }
            }
        }
    }
}

?>