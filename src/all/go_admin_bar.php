<?php

//Redirect to homepage after logout
add_action('wp_logout','auto_redirect_after_logout');
function auto_redirect_after_logout(){
  wp_redirect( home_url() );
  exit();
}

//remove_menu_page( 'index.php' );
add_action( 'admin_bar_menu', 'remove_wp_admin_items', 999 );

function remove_wp_admin_items( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'wp-logo' );	
	if ( ! get_option('go_dashboard_toggle') && ! current_user_can('administrator') && ! is_admin()){
		$wp_admin_bar->remove_node( 'site-name' );
	}	
}

//remove dashboard
add_action( 'admin_menu', 'Wps_remove_tools', 99 );
function Wps_remove_tools(){
	if ( ! get_option('go_dashboard_toggle') && ! current_user_can('administrator') ){
		remove_menu_page( 'index.php' ); //dashboard
		
	}
}

function go_display_admin_bar() {
	if ( get_option( 'options_go_admin_bar_toggle' ) ) {
		return true;	
	}
	else if ( is_user_logged_in() ) {
		return true;
	}
	else{
	return false;
	}
}
add_filter( 'show_admin_bar', 'go_display_admin_bar' );

function go_admin_bar() {
	global $wp_admin_bar;
	
	//get options for what to show
	$go_search_switch = get_option( 'options_go_search_toggle' );
	$go_map_switch = get_option( 'options_go_locations_map_toggle' );
	$go_store_switch = get_option( 'options_go_store_toggle' );
    $go_stats_switch = get_option( 'options_go_stats_toggle' );
    $go_blog_switch = get_option('options_go_blogs_toggle');

	if ( ! is_user_logged_in() ) {
		$wp_admin_bar->add_node(
			array(
				'id' => 'go_toolbar_login',
				'title' => 'Login',
				'href' => wp_login_url()
			)
		);
	} else if ( is_admin_bar_showing() && is_user_logged_in() ) {
		$user_id = get_current_user_id();
        $wp_admin_bar->remove_menu( 'wp-logo' );

        /**
         * If is admin, show the dropdown for view type
         */
        $is_admin = go_user_is_admin( $user_id );

        $post_type = get_post_type();

        if ($is_admin) {
            $admin_view = get_user_meta($user_id, 'go_admin_view',true);
            if (!empty ($admin_view) ){
                if ($admin_view == 'all'){
                    $all_selected = 'selected = "selected"';
                }
                else {
                    $all_selected = null;
                }
                if ($admin_view == 'player'){
                    $player_selected = 'selected = "selected"';
                }
                else {
                    $player_selected = null;
                }
                if ($admin_view == 'user') {
                    $user_selected = 'selected = "selected"';
                }
                else {
                    $user_selected = null;
                }
                if ($admin_view == 'guest'){
                    $guest_selected = 'selected = "selected"';
                }
                else {$guest_selected = null;
                }

            }
            else{
                $all_selected = null;
                $player_selected = null;
                $user_selected = null;
                $guest_selected = null;
            }
            $content = '<form>
                            View: <select id="go_select_admin_view" onchange="go_update_admin_view(this.value)">
                                <option value="all" ' . $all_selected . ' >All Stages</option>
                                <option value="player" ' . $player_selected . '>No Locks</option>
                                <option value="user" ' . $user_selected . '>With Locks</option>
                                <option value="guest" ' . $guest_selected . '>Guest</option>
                            </select>
                        </form>';
            if ($post_type == 'tasks') {
                $wp_admin_bar->add_menu(array('id' => 'go_admin_view_form', 'parent' => 'top-secondary', 'title' => $content));
            }
        }

        /**
         * Get the percentage for the XP Bar/health Bar and the Loot for the totals
         * Show bars and create dropdown
         */
        $xp_toggle = get_option('options_go_loot_xp_toggle');
        $gold_toggle = get_option('options_go_loot_gold_toggle');
        $health_toggle = get_option( 'options_go_loot_health_toggle' );

		if ($xp_toggle) {
            // the user's current amount of experience (points)
            $go_current_xp = go_get_user_loot($user_id, 'xp');

            $rank = go_get_rank($user_id);
            $rank_num = $rank['rank_num'];
            $current_rank = $rank['current_rank'];
            $current_rank_points = $rank['current_rank_points'];
            $next_rank = $rank['next_rank'];
            $next_rank_points = $rank['next_rank_points'];

            $go_option_ranks = get_option('options_go_loot_xp_levels_name_singular');
            //$points_array = $go_option_ranks['points'];

            /*
             * Here we are referring to last element manually,
             * since we don't want to modifiy
             * the arrays with the array_pop function.
             */
            //$max_rank_index = count( $points_array ) - 1;
            //$max_rank_points = (int) $points_array[ $max_rank_index ];

            if ($next_rank_points != false) {
                $rank_threshold_diff = $next_rank_points - $current_rank_points;
                $pts_to_rank_threshold = $go_current_xp - $current_rank_points;
                $pts_to_rank_up_str = "L{$rank_num}: {$pts_to_rank_threshold} / {$rank_threshold_diff}";
                $percentage = $pts_to_rank_threshold / $rank_threshold_diff * 100;
                //$color = barColor( $go_current_health, 0 );
                $color = "#39b54a";
            } else {
                $pts_to_rank_up_str = $current_rank;
                $percentage = 100;
                $color = "gold";
            }
            if ( $percentage <= 0 ) {
                $percentage = 0;
            } else if ( $percentage >= 100 ) {
                $percentage = 100;
            }
            $progress_bar = '<div id="go_admin_bar_progress_bar_border" class="progress-bar-border">'.'<div id="go_admin_bar_progress_bar" class="progress_bar" '.
                'style="width: '.$percentage.'%; background-color: '.$color.' ;">'.
                '</div>'.
                '<div id="points_needed_to_level_up" class="go_admin_bar_text">'.
                $pts_to_rank_up_str.
                '</div>'.
                '</div>';
        }
        else {
            $progress_bar = '';
        }


        if($health_toggle) {
            // the user's current amount of bonus currency,
            // also used for coloring the admin bar
            $go_current_health = go_get_user_loot($user_id, 'health');
            $health_percentage = intval($go_current_health / 2);
            if ($health_percentage <= 0) {
                $health_percentage = 0;
            } else if ($health_percentage >= 100) {
                $health_percentage = 100;
            }
            $health_bar = '<div id="go_admin_health_bar_border" class="progress-bar-border">' . '<div id="go_admin_bar_health_bar" class="progress_bar" ' . 'style="width: ' . $health_percentage . '%; background-color: red ;">' . '</div>' . '<div id="health_bar_percentage_str" class="go_admin_bar_text">' . "Health Mod: " . $go_current_health . "%" . '</div>' . '</div>';

        }
        else{
            $health_bar = '';
        }

        if ($gold_toggle) {
            // the user's current amount of currency
            $go_current_gold = go_get_user_loot($user_id, 'gold');
            $gold_total = '<div id="go_admin_bar_gold_2" class="admin_bar_loot">' . go_display_shorthand_currency('gold', $go_current_gold)  . '</div>';
        }
        else{
            $gold_total = '';
        }

        

		$wp_admin_bar->add_node(
			array(
				'id' => 'go_info',
				'title' => 
					'<div style="padding-top:5px;">'.
							$progress_bar.
                            $health_bar.
                            $gold_total.
					'</div>',
				'href' => '#',
			) 
		);


		if($xp_toggle) {
            $wp_admin_bar->add_node(array('id' => 'go_rank', 'title' => '<div id="go_admin_bar_rank">' . $go_option_ranks . ' ' . $rank_num . ": " . $current_rank . '</div>', 'href' => '#', 'parent' => 'go_info',));

            $wp_admin_bar->add_node(array('id' => 'go_xp', 'title' => '<div id="go_admin_bar_xp">' . go_display_longhand_currency('xp', $go_current_xp) . '</div>', 'href' => '#', 'parent' => 'go_info',));
        }

        if($gold_toggle) {
            $wp_admin_bar->add_node(array('id' => 'go_gold', 'title' => '<div id="go_admin_bar_gold">' . go_display_longhand_currency('gold', $go_current_gold) . '</div>', 'href' => '#', 'parent' => 'go_info',));
        }

        if($health_toggle) {
            $wp_admin_bar->add_node(array('id' => 'go_health', 'title' => '<div id="go_admin_bar_health">' . go_display_longhand_currency('health', $go_current_health) . '</div>', 'href' => '#', 'parent' => 'go_info',));
        }

		/*
		if ( current_user_can( 'manage_options' ) ) {
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_deactivate',
					'title' => '<input type="button" id="go_admin_bar_deactivation" name="go_admin_bar_deactivation" value="Deactivate" onclick="go_deactivate_plugin()"/>',
					'parent'=>'go_info'
				) 
			);
		}
		*/
		/*
		if ( get_option( 'go_admin_bar_add_switch' ) == 'On' ) {		
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_add',
					'title' => 'Add',
					'href' => '#',
				) 
			);
			$title = '';
			if ( ! $is_admin ) {
				if ( get_option( 'go_bar_add_points_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_points_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_points_reason"/></div>';
				}
				if ( get_option( 'go_bar_add_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_currency_reason"/></div>';
				}
				if ( get_option( 'go_bar_add_bonus_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_bonus_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_bonus_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_bonus_currency_reason"/></div>';
				}
				if ( get_option( 'go_bar_add_penalty_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_penalty_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_penalty"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_penalty_reason"/></div>';
				}
				if ( get_option( 'go_bar_add_minutes_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_minutes_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_minutes"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_minutes_reason"/></div>';
				}
				if ( get_option( 'go_bar_add_points_switch' ) == 'On' || get_option( 'go_bar_add_currency_switch' ) == 'On' || get_option( 'go_bar_add_bonus_currency_switch' ) == 'On' || get_option( 'go_bar_add_penalty_switch' ) == 'On' || get_option( 'go_bar_add_minutes_switch' ) == 'On') {
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
				if ( get_option( 'go_admin_bar_add_points_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_points_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_points_reason"/></div>';
				}
				if ( get_option( 'go_admin_bar_add_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_currency_reason"/></div>';
				}
				if ( get_option( 'go_admin_bar_add_bonus_currency_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_bonus_currency_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_bonus_currency"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_bonus_currency_reason"/></div>';
				}
				if ( get_option( 'go_admin_bar_add_penalty_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_penalty_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_penalty"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_penalty_reason"/></div>';
				}
				if ( get_option( 'go_admin_bar_add_minutes_switch' ) == 'On' ) {
					$title .=  '<div id="go_admin_bar_title">'.get_option( 'go_minutes_name' ).'</div>
								<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_add_input" id="go_admin_bar_add_minutes"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_add_minutes_reason"/></div>';
				}
				if ( get_option( 'go_admin_bar_add_points_switch' ) == 'On' || get_option( 'go_admin_bar_add_currency_switch' ) == 'On' || get_option( 'go_admin_bar_add_bonus_currency_switch' ) == 'On' || get_option( 'go_admin_bar_add_penalty_switch' ) == 'On' || get_option( 'go_admin_bar_add_minutes_switch' ) == 'On') {
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
		*/
        if ($go_stats_switch) {
            acf_form_head();
            $stats_name = get_option('options_go_stats_name');
            $wp_admin_bar->add_node(

                array('id' => 'go_stats', 'title' => '<i class="fa fa-area-chart ab-icon" aria-hidden="true"></i><div style="float: right;">' . $stats_name . '</div><div id="go_stats_page"></div><script>  jQuery("#wp-admin-bar-go_stats").one("click", function(){ go_admin_bar_stats_page_button()}); </script>', 'href' => '#',));
        };

        if ($go_blog_switch) {
            acf_form_head();
            //$stats_name = get_option('options_go_stats_name');

            $user_info = get_userdata($user_id);
            $userloginname = $user_info->user_login;
            $user_blog_link = get_site_url(null, '/user/' . $userloginname);

            $wp_admin_bar->add_node(
                array('id' => 'go_blog_menu_link', 'title' => '<span class="ab-icon dashicons dashicons-admin-post "></span><div style="float: right;">My Blog</div>', 'href' => $user_blog_link,));
        };
        
        if ($go_map_switch){
            $map_url = get_option('options_go_locations_map_map_link');
            $map_url = (string) $map_url;
			$go_map_link = get_permalink( get_page_by_path($map_url) );
			$name = get_option('options_go_locations_map_title');
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_map',
					'title' => '<i class="fa fa-sitemap ab-icon" aria-hidden="true"></i><div id="go_map_page" class="admin_map" style="float: right;" >' . $name . '</div>',
					'href' => $go_map_link,
				) 
			);
		};
		
		if ($go_store_switch){
            $page_path = get_option('options_go_store_store_link');
			$go_store_link = get_permalink( get_page_by_path($page_path) );
            $name = get_option('options_go_store_name');
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_store',
					'title' => '<i class="fa fa-shopping-cart ab-icon" aria-hidden="true"></i><div id="go_store_page" style="float: right;">'. $name .'</div>',
					'href' => $go_store_link,
				) 
			);
		};        
        
		if ($go_search_switch){
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
        };

        if ( $is_admin ) {
            $wp_admin_bar->add_node(array('id' => 'go_clipboard', 'title' => '<span class="ab-icon dashicons dashicons-clipboard"></span><div id="go_clipboard_adminbar" style="float: right;">Clipboard</div>', 'href' => get_admin_url() . 'admin.php?page=go_clipboard',));
        }
		if ( $is_admin ) {
			$wp_admin_bar->add_group(
				array(
					'id' => 'go_site_name_menu',
					'parent' => 'site-name',
					'meta' => array( 'class' => 'go_site_name_menu' )
				) 
			);

			/*
			 * Game On Links
			 */

            // displays GO options page link
            $wp_admin_bar->add_node(
                array(
                    'id' => 'go_nav_options',
                    'title' => 'Game-On Options',
                    'href' => get_admin_url().'admin.php?page=go_options',
                    'parent' => 'go_site_name_menu',
                    'meta' => array( 'class' => 'go_site_name_menu_item' )
                )
            );

			// displays Clipboard link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_clipboard',
					'title' => 'Clipboard',
					'href' => get_admin_url().'admin.php?page=go_clipboard',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);


			
			// displays Task edit page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_tasks',
					'title' => get_option( 'options_go_tasks_name_plural' ),
					'href' => get_admin_url().'edit.php?post_type=tasks',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);

            // displays chains page link
            $wp_admin_bar->add_node(
                array(
                    'id' => 'go_nav_chains',
                    'title' => get_option('options_go_tasks_name_plural').' Maps',
                    'href' => esc_url( get_admin_url() ).'edit-tags.php?taxonomy=task_chains&post_type=tasks',
                    'parent' => 'go_site_name_menu',
                    'meta' => array( 'class' => 'go_site_name_menu_item' )
                )
            );

            // displays Store Item edit page link
            $wp_admin_bar->add_node(
                array(
                    'id' => 'go_nav_store',
                    'title' => get_option( 'options_go_store_name' ).' Items',
                    'href' => get_admin_url().'edit.php?post_type=go_store',
                    'parent' => 'go_site_name_menu',
                    'meta' => array( 'class' => 'go_site_name_menu_item' )
                )
            );

            // displays Store Categories page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_store_types',
					'title' => get_option('options_go_store_name').' Categories',
					'href' => esc_url( get_admin_url() ).'edit-tags.php?taxonomy=store_types&post_type=go_store',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				)
			);

			// displays Badges
            $badges_toggle = get_option('options_go_badges_toggle');
            if($badges_toggle) {
                $wp_admin_bar->add_node(array('id' => 'go_nav_badges', 'title' => get_option('options_go_badges_name_plural'), 'href' => esc_url(get_admin_url()) . 'edit-tags.php?taxonomy=go_badges', 'parent' => 'go_site_name_menu', 'meta' => array('class' => 'go_site_name_menu_item')));
            }

            // displays Store Categories page link
            $wp_admin_bar->add_node(
                array(
                    'id' => 'go_nav_users',
                    'title' => 'Users',
                    'href' => esc_url( get_admin_url() ).'users.php',
                    'parent' => 'go_site_name_menu',
                    'meta' => array( 'class' => 'go_site_name_menu_item' )
                )
            );

            // displays Store Categories page link
            $wp_admin_bar->add_node(
                array(
                    'id' => 'go_nav_user_types',
                    'title' => 'User Groups',
                    'href' => esc_url( get_admin_url() ).'edit-tags.php?taxonomy=user_go_groups',
                    'parent' => 'go_site_name_menu',
                    'meta' => array( 'class' => 'go_site_name_menu_item' )
                )
            );








			/*
			 * Default WP Links
			 */

			// displays Post edit page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_posts',
					'title' => 'Posts',
					'href' => esc_url( get_admin_url() ).'edit.php',
					'parent' => 'appearance'
				) 
			);

			// displays Page edit page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_pages',
					'title' => 'Pages',
					'href' => esc_url( get_admin_url() ).'edit.php?post_type=page',
					'parent' => 'appearance'
				) 
			);

			// displays Media Library page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_media',
					'title' => 'Media',
					'href' => esc_url( get_admin_url() ).'upload.php',
					'parent' => 'appearance'
				) 
			);

			// displays Plugins page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_plugins',
					'title' => 'Plugins',
					'href' => esc_url( get_admin_url() ).'plugins.php',
					'parent' => 'appearance'
				) 
			);

			// displays Users page link
            /*
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_users',
					'title' => 'Users',
					'href' => esc_url( get_admin_url() ).'users.php',
					'parent' => 'appearance',
				)
			);
			*/
		}




        //displays Timer in admin bar
        $post_id = get_the_ID();
        $timer_on = get_post_meta($post_id,'go_timer_toggle', true);
        if ($timer_on) {

            $atts = shortcode_atts(array(
                'id' => '', // ID defined in Shortcode
                'cats' => '', // Cats defined in Shortcode
            ), '');
            $id = $atts['id'];
            $custom_fields = get_post_custom($id); // Just gathering some data about this task with its post id

            $wp_admin_bar->add_node(
                array(
                    'id' => 'go_timer',
                    'title' => '<div id="go_timer"><i class="fa fa-clock-o ab-icon" aria-hidden="true"></i><div><span class="days"></span>d : </div><div><span class="hours"></span>h : </div><div><span class="minutes"></span>m : </div><div><span class="seconds"></span>s</div></div>',
                )
            );
        }

	// end-if the admin bar is turned on and the user is logged in
	}
}

function go_update_admin_view (){
    global $wpdb;
    check_ajax_referer( 'go_update_admin_view'  );

    if(empty($_POST) || !isset($_POST)) {
        ajaxStatus('error', 'Nothing to update.');
    } else {
        try {
            //$user_id = ( ! empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0 ); // User id posted from ajax function

            $go_admin_view = $_POST['go_admin_view'];
            //check_ajax_referer('go_update_admin_view', 'security' );
            $user_id = wp_get_current_user();
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'go_admin_view', $go_admin_view );
            die();
        } catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

?>