<?php

function go_display_admin_bar() {
	if ( go_return_options( 'go_admin_bar_display_switch' ) == 'On' ) {
		return true;
	}
}

function go_admin_bar() {
	global $wp_admin_bar;

	$user_id = get_current_user_id();
	
	// the user's current amount of experience (points)
	$go_current_points = go_return_points( $user_id );
	
	// the user's current amount of currency
	$go_current_currency = go_return_currency( $user_id );

	// the user's current amount of bonus currency,
	// also used for coloring the admin bar
	$go_current_bonus_currency = go_return_bonus_currency( $user_id );

	// the user's current amount of penalties,
	// also used for coloring the admin bar
	$go_current_penalty = go_return_penalty( $user_id );

	// the user's current amount of minutes
	$go_current_minutes = go_return_minutes( $user_id );

	$ranks_output = go_update_ranks( $user_id, $go_current_points, false );

	$rank = go_get_rank( $user_id );
	if ( ! empty( $rank ) ) {
		$current_rank = $rank[0];
		$current_rank_points = $rank[1];
		$next_rank = $rank[2];
		$next_rank_points = $rank[3];
	}

	$go_option_ranks = get_option( 'go_ranks' );
	$points_array = $go_option_ranks['points'];

	/*
	 * Here we are referring to last element manually,
	 * since we don't want to modifiy
	 * the arrays with the array_pop function.
	 */
	$max_rank_index = count( $points_array ) - 1;
	$max_rank_points = $points_array[ $max_rank_index ];

	error_log(
		"##### go_admin_bar #####\n".
		"\$go_current_points: $go_current_points\n".
		"\$current_rank: $current_rank\n".
		"\$current_rank_points: $current_rank_points\n".
		"\$next_rank_points: $next_rank_points\n".
		"\$next_rank: $next_rank\n".
		"\$max_rank_points: $max_rank_points"
	);

	if ( ! empty( $next_rank_points ) ) {
		$rank_threshold_diff = $next_rank_points - $current_rank_points;
	} else {
		$rank_threshold_diff = 1;
	}
	$pts_to_rank_threshold = $go_current_points - $current_rank_points;

	if ( $max_rank_points === $current_rank_points ) {
		$prestige_name = go_return_options( 'go_prestige_name' );
		$pts_to_rank_up_str = "{$pts_to_rank_threshold} - {$prestige_name}";
	} else {
		$pts_to_rank_up_str = "{$pts_to_rank_threshold} / {$rank_threshold_diff}";
	}

	$percentage = $pts_to_rank_threshold / $rank_threshold_diff * 100;
	if ( $percentage <= 0 ) { 
		$percentage = 0;
	} else if ( $percentage >= 100 ) {
		$percentage = 100;
	}
	
	$color = barColor( $go_current_bonus_currency, $go_current_penalty );
	
	$wp_admin_bar->remove_menu( 'wp-logo' );
	
	if ( ! is_user_logged_in() ) {
		$wp_admin_bar->add_node(
			array(
				'id' => 'go_toolbar_login',
				'title' => 'Login',
				'href' => wp_login_url()
			)
		);
	}
	
	if ( is_admin_bar_showing() && is_user_logged_in() ) {
		$is_admin = false;
		$user_obj = get_user_by( 'id', $user_id );
		$user_roles = $user_obj->roles;
		if ( ! empty( $user_roles ) ) {
			foreach ( $user_roles as $role ) {
				if ( $role === "administrator" ) {
					$is_admin = true;
					break;
				}
			}
		}
	
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_info',
				'title' => 
					'<div style="padding-top:5px;">'.
						'<div id="go_admin_bar_progress_bar_border">'.
							'<div id="go_admin_bar_progress_bar" class="progress_bar" '.
								'style="width: '.$percentage.'%; background-color: '.$color.' ;">'.
							'</div>'.
							'<div id="points_needed_to_level_up" class="go_admin_bar_text">'.
								$pts_to_rank_up_str.
							'</div>'.
						'</div>'.
					'</div>',
				'href' => '#',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_rank',
				'title' => '<div id="go_admin_bar_rank">' . $current_rank . '</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_points',
				'title' => '<div id="go_admin_bar_points">' .
					go_display_longhand_currency(
						'points',
						$go_current_points
					) .
					'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_currency',
				'title' => '<div id="go_admin_bar_currency">' .
					go_display_longhand_currency(
						'currency',
						$go_current_currency
					) .
					'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_bonus_currency',
				'title' => '<div id="go_admin_bar_bonus_currency">' .
					go_display_longhand_currency(
						'bonus_currency',
						$go_current_bonus_currency
					) .
					'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_penalty',
				'title' => '<div id="go_admin_bar_penalty">' .
					go_display_longhand_currency(
						'penalty',
						$go_current_penalty
					) .
					'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_minutes',
				'title' => '<div id="go_admin_bar_minutes">' .
					go_display_longhand_currency(
						'minutes',
						$go_current_minutes
					) .
					'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		if ( current_user_can( 'manage_options' ) ) {
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_deactivate',
					'title' => '<input type="button" id="go_admin_bar_deactivation" name="go_admin_bar_deactivation" value="Deactivate" onclick="go_deactivate_plugin()"/>',
					'parent'=>'go_info'
				) 
			);
		}
		
		if ( go_return_options( 'go_admin_bar_add_switch' ) == 'On' ) {		
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_add',
					'title' => 'Add',
					'href' => '#',
				) 
			);
			if ($role !== 'administrator') {
				$title = '';
				if ( go_return_options( 'go_bar_add_points_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_points_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_points_reason"/></div>';
				}
				if ( go_return_options( 'go_bar_add_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_currency_reason"/></div>';
				}
				if ( go_return_options( 'go_bar_add_bonus_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_bonus_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_bonus_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_bonus_currency_reason"/></div>';
				}
				if ( go_return_options( 'go_bar_add_penalty_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_penalty_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_penalty"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_penalty_reason"/></div>';
				}
				if ( go_return_options( 'go_bar_add_minutes_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_minutes_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_minutes"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_minutes_reason"/></div>';
				}
				if ( go_return_options( 'go_bar_add_points_switch' ) == 'On' || go_return_options( 'go_bar_add_currency_switch' ) == 'On' || go_return_options( 'go_bar_add_bonus_currency_switch' ) == 'On' || go_return_options( 'go_bar_add_penalty_switch' ) == 'On' || go_return_options( 'go_bar_add_minutes_switch' ) == 'On') {
					$wp_admin_bar->add_node( 
						array(
							'id' => 'go_add_bar',
							'title' => $title . '
							<div><button style="width: 252px; margin-top: 10px;" id="go_admin_bar_add_button" name="go_admin_bar_submit" onclick="go_admin_bar_add();this.disabled = true;" value="Add">Add</button><div id="admin_bar_add_return"></div></div>
							<script type="text/javascript">
							var height = 80;
							jQuery(".go_admin_bar_reason").each(function() {
								height += 60;
							});
							jQuery( "ul#wp-admin-bar-go_add-default.ab-submenu" ).css( "height", height );
							</script>',
							'href' => false,
							'parent' => 'go_add'
						) 
					);
				}
			} else {
				if ( go_return_options( 'go_admin_bar_add_points_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_points_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_points_reason"/></div>';
				}
				if ( go_return_options( 'go_admin_bar_add_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_currency_reason"/></div>';
				}
				if ( go_return_options( 'go_admin_bar_add_bonus_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_bonus_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_bonus_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_bonus_currency_reason"/></div>';
				}
				if ( go_return_options( 'go_admin_bar_add_penalty_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_penalty_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_penalty"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_penalty_reason"/></div>';
				}
				if ( go_return_options( 'go_admin_bar_add_minutes_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.go_return_options( 'go_minutes_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_minutes"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_minutes_reason"/></div>';
				}
				if ( go_return_options( 'go_admin_bar_add_points_switch' ) == 'On' || go_return_options( 'go_admin_bar_add_currency_switch' ) == 'On' || go_return_options( 'go_admin_bar_add_bonus_currency_switch' ) == 'On' || go_return_options( 'go_admin_bar_add_penalty_switch' ) == 'On' || go_return_options( 'go_admin_bar_add_minutes_switch' ) == 'On') {
					$wp_admin_bar->add_node( 
						array(
							'id' => 'go_add_bar',
							'title' => $title . '
							<div><button style="width: 252px; margin-top: 10px;" id="go_admin_bar_add_button" name="go_admin_bar_submit" onclick="go_admin_bar_add();this.disabled = true;" value="Add">Add</button><div id="admin_bar_add_return"></div></div>
							<script type="text/javascript">
							var height = 80;
							jQuery(".go_admin_bar_reason").each(function() {
								height += 60;
							});
							jQuery( "ul#wp-admin-bar-go_add-default.ab-submenu" ).css( "height", height );
							</script>',
							'href' => false,
							'parent' => 'go_add'
						) 
					);
				}
			}
		}

		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_stats',
				'title' => '<div onclick="go_admin_bar_stats_page_button();">Stats</div><div id="go_stats_page"></div>',
				'href' => '#',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_task_search',
				'title' => '
					<form role="search" method="get" id="go_admin_bar_task_search_form" class="searchform" action="'.home_url( '/' ).'">
						<div><label class="screen-reader-text" for="s">'.__( 'Search for:' ).'</label>
							<input type="text" value="'.get_search_query().'" name="s" id="go_admin_bar_task_search_input" placeholder="Search for '.strtolower( get_option( "go_tasks_plural_name" ) ).'..."/>
							<input type="hidden" name="post_type[]" value="tasks"/>
							<input type="submit" id="go_admin_bar_task_search_submit" value="'.esc_attr__( 'Search' ).'"/>
						</div>
					</form>',
			) 
		);
		
		if ( $is_admin ) {
			$wp_admin_bar->add_group(
				array(
					'id' => 'go_site_name_menu',
					'parent' => 'site-name',
					'meta' => array( 'class' => 'go_site_name_menu' )
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_clipboard',
					'title' => 'Clipboard',
					'href' => get_admin_url().'admin.php?page=go_clipboard',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_tasks',
					'title' => get_option( 'go_tasks_plural_name' ),
					'href' => get_admin_url().'edit.php?post_type=tasks',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_store',
					'title' => get_option( 'go_store_name' ),
					'href' => get_admin_url().'edit.php?post_type=go_store',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_options',
					'title' => 'Game-On',
					'href' => get_admin_url().'admin.php?page=game-on-options.php',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_posts',
					'title' => 'Posts',
					'href' => esc_url( get_admin_url() ).'edit.php',
					'parent' => 'appearance'
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_pages',
					'title' => 'Pages',
					'href' => esc_url( get_admin_url() ).'edit.php?post_type=page',
					'parent' => 'appearance'
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_media',
					'title' => 'Media',
					'href' => esc_url( get_admin_url() ).'upload.php',
					'parent' => 'appearance'
				) 
			);

			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_plugins',
					'title' => 'Plugins',
					'href' => esc_url( get_admin_url() ).'plugins.php',
					'parent' => 'appearance'
				) 
			);
			
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_pods',
					'title' => get_option('go_tasks_name').' Pods',
					'href' => esc_url( get_admin_url() ).'admin.php?page=go_pods',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				)
			);
		}
		echo $ranks_output;
	}
}

?>