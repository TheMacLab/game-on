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

	$ranks_output = go_update_ranks( $user_id, $go_current_points, false );

	$rank = go_get_rank( $user_id );
	if ( ! empty( $rank ) ) {
		$current_rank = $rank[0];
		$current_rank_points = $rank[1];
		$next_rank = $rank[2];
		$next_rank_points = $rank[3];
	}

	error_log(
		"##### go_admin_bar #####\n".
		"\$go_current_points: $go_current_points\n".
		"\$current_rank: $current_rank\n".
		"\$current_rank_points: $current_rank_points\n".
		"\$next_rank_points: $next_rank_points\n".
		"\$next_rank: $next_rank"
	);

	$dom = ( $next_rank_points - $current_rank_points );
	$rng = ( $go_current_points - $current_rank_points );
	$current_bonus_currency = go_return_bonus_currency( $user_id );
	$current_penalty = go_return_penalty( $user_id );
	$current_minutes = go_return_minutes( $user_id );
	if ( $dom <= 0 ) {
		$dom = 1;
	}
	$percentage = $rng / $dom * 100;
	if ( $percentage <= 0 ) { 
		$percentage = 0;
	} elseif ( $percentage >= 100 ) {
		$percentage = 100;
	}
	
	$color = barColor( $current_bonus_currency, $current_penalty );
	
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
				'title' => '<div style="padding-top:5px;"><div id="go_admin_bar_progress_bar_border"><div id="points_needed_to_level_up" class="go_admin_bar_text">'.( $rng).'/'.( $dom).'</div><div id="go_admin_bar_progress_bar" class="progress_bar" style="width: '.$percentage.'%; background-color: '.$color.' ;"></div></div></div>',
				'href' => '#',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_rank',
				'title' => '<div id="go_admin_bar_rank">'.go_return_clean_rank( $user_id ).'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_points',
				'title' => '<div id="go_admin_bar_points">'.go_return_options( 'go_points_name' ).': '.go_display_points( $go_current_points ).'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_points',
				'title' => '<div id="go_admin_bar_currency">'.go_return_options( 'go_currency_name' ).': '.go_display_currency( $go_current_currency ).'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_currency',
				'title' => '<div id="go_admin_bar_bonus_currency">'.go_return_options( 'go_bonus_currency_name' ).': '.go_display_bonus_currency( $current_bonus_currency ).'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_penalty',
				'title' => '<div id="go_admin_bar_penalty">'.go_return_options( 'go_penalty_name' ).': '.go_display_penalty( $current_penalty ).'</div>',
				'href' => '#',
				'parent' => 'go_info',
			) 
		);
		
		$wp_admin_bar->add_node( 
			array(
				'id' => 'go_minutes',
				'title' => '<div id="go_admin_bar_minutes">'.go_return_options( 'go_minutes_name' ).': '.go_display_minutes( $current_minutes ).'</div>',
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
			
			if ( go_return_options( 'go_admin_bar_add_minutes_switch' ) != 'On' || $role === 'administrator' ) {
			
				$wp_admin_bar->add_node( 
					array(
						'id' => 'go_add_bar',
						'title' => 
						'<div id="go_admin_bar_title">'.go_return_options( 'go_points_name' ).'</div>
						<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_points_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_points_reason"/></div>
						<div id="go_admin_bar_title">'.go_return_options( 'go_currency_name' ).'</div>
						<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_currency_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_currency_reason"/></div>
						<div id="go_admin_bar_title">'.go_return_options( 'go_bonus_currency_name' ).'</div>
						<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_bonus_currency_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_bonus_currency_reason"/></div>
						<div id="go_admin_bar_title">'.go_return_options( 'go_penalty_name' ).'</div>
						<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_penalty_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_penalty_reason"/></div>
						<div id="go_admin_bar_title">'.go_return_options( 'go_minutes_name' ).'</div>
						<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_minutes_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_minutes_reason"/></div>
						<div><input id="go_admin_bar_add_button" type="button" style="width:250px; height: 20px;margin-top: 7px;" name="go_admin_bar_submit" onclick="go_admin_bar_add();this.disabled = true;" value="Add"><div id="admin_bar_add_return"></div></div>',
						'href' => false,
						'parent' => 'go_add'
					) 
				);
				
			} else {
			
				$wp_admin_bar->add_node( 
					array(
						'id' => 'go_add_bar',
						'title' => 
						'<div id="go_admin_bar_title">'.go_return_options( 'go_minutes_name' ).'</div>
						<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_minutes_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_minutes_reason"/></div>
						<div><input id="go_admin_bar_add_button" type="button" style="width:250px; height: 20px;margin-top: 7px;" name="go_admin_bar_submit" onclick="go_admin_bar_add();this.disabled = true;" value="Add"><div id="admin_bar_add_return"></div></div>
						<script type="text/javascript">
							jQuery( "ul#wp-admin-bar-go_add-default.ab-submenu" ).css( "height", "125px" );
						</script>',
						'href' => false,
						'parent' => 'go_add'
					) 
				);
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