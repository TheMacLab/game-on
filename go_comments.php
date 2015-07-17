<?php

function go_display_comment_author() {
	global $comment;
	$author_id = $comment->user_id;
	$author = $comment->comment_author;
	$author_obj = get_userdata( $author_id );
	$author_roles = $author_obj->roles;
	$is_admin = false;
	if ( is_array( $author_roles ) ) {
		foreach ( $author_roles as $role ) {
			if ( $role == "administrator" ) {
				$is_admin = true;
				break;
			}
		}
	} else {
		if ( $author_roles == 'administrator' ) {
			$is_admin = true;
		}
	}
	if ( $is_admin ) {
		return $author;
	} else {
		$points = get_user_meta( $author_id, 'go_rank', true );
		$focus = get_user_meta( $author_id, 'go_focus', true );
		if ( ! empty( $focus ) ) {
			if ( is_array( $focus ) ) {
				for ( $i = 0; $i < count( $focus ); $i++ ) {
					if ( ! empty( $focus[ $i ] ) ) {
						$careers .= $focus[ $i ];
						if ( ( $i + 1 ) < count( $focus ) ) {
							$careers .= '/';
						}
					}
				}
			} else {
				$no_focus_str = 'No '.get_option( 'go_focus_name', 'Profession' );
				if ( $focus != $no_focus_str ) {
					$careers = $focus;
				}
			}
		}
		if ( ! empty( $careers ) ) {
			if ( ! empty( $points ) ) {
				return $author.'<br/>'.'('.$careers.', '.$points[0][0].')';
			} else {
				return $author;
			}
		} else {
			if ( ! empty( $points ) ) {
				return $author.'<br/>'.'('.$points[0][0].')';
			} else {
				return $author;
			}
		}
	}
}

?>