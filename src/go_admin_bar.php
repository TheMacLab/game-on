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
	if ( get_option('go_dashboard_switch')  != 'On'  && ! current_user_can('administrator') && ! is_admin()){
		$wp_admin_bar->remove_node( 'site-name' );
		
	}	
}


//remove dashboard
add_action( 'admin_menu', 'Wps_remove_tools', 99 );
function Wps_remove_tools(){
	if ( get_option('go_dashboard_switch')  != 'On'  && ! current_user_can('administrator') ){
		remove_menu_page( 'index.php' ); //dashboard
		
	}
}

function go_display_admin_bar() {
	if ( get_option( 'go_admin_bar_display_switch' ) == 'On' ) {
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
	$go_search_switch = get_option( 'go_search_switch' );
	$go_map_switch = get_option( 'go_map_switch' );
	$go_store_switch = get_option( 'go_store_switch' );

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

		$rank = go_get_rank( $user_id );
		$current_rank = $rank['current_rank'];
		$current_rank_points = $rank['current_rank_points'];
		$next_rank = $rank['next_rank'];
		$next_rank_points = $rank['next_rank_points'];

		$go_option_ranks = get_option( 'go_ranks' );
		$points_array = $go_option_ranks['points'];

		/*
		 * Here we are referring to last element manually,
		 * since we don't want to modifiy
		 * the arrays with the array_pop function.
		 */
		$max_rank_index = count( $points_array ) - 1;
		$max_rank_points = (int) $points_array[ $max_rank_index ];

		if ( null !== $next_rank_points ) {
			$rank_threshold_diff = $next_rank_points - $current_rank_points;
		} else {
			$rank_threshold_diff = 1;
		}
		$pts_to_rank_threshold = $go_current_points - $current_rank_points;

		if ( $max_rank_points === $current_rank_points ) {
			$prestige_name = get_option( 'go_prestige_name' );
			$pts_to_rank_up_str = $prestige_name;
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
                else {$guest_selected = null;}

            }
            $content = '<form>
                            View: <select onchange="go_update_admin_view(this.value)">
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
        $stats_name = get_option('options_go_stats_name');
		$wp_admin_bar->add_node(

			array(
				'id' => 'go_stats',
				'title' => '<i class="fa fa-area-chart ab-icon" aria-hidden="true"></i><div style="float: right;">' . $stats_name . '</div><div id="go_stats_page"></div><script>jQuery( "#wp-admin-bar-go_stats" ).click(function() {go_admin_bar_stats_page_button();});</script>',
				'href' => '#',
			)
		);
		
        
        if ($go_map_switch === 'On'){
			$go_map_link = get_permalink( get_page_by_path('map') );
			$name = get_option('options_go_locations_map_title');
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_map',
					'title' => '<i class="fa fa-sitemap ab-icon" aria-hidden="true"></i><div id="go_map_page" class="admin_map" style="float: right;" >' . $name . '</div>',
					'href' => $go_map_link,
				) 
			);
		};
		
		if ($go_store_switch === 'On'){
			$go_store_link = get_permalink( get_page_by_path('store') );
            $name = get_option('options_go_store_name');
			$wp_admin_bar->add_node( 
				array(
					'id' => 'go_store',
					'title' => '<i class="fa fa-shopping-cart ab-icon" aria-hidden="true"></i><div id="go_store_page" style="float: right;">'. $name .'</div>',
					'href' => $go_store_link,
				) 
			);
		};        
        
		if ($go_search_switch === 'On'){
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

			// displays chains page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_chains',
					'title' => 'Edit '.get_option('go_tasks_name').' Map',
					'href' => esc_url( get_admin_url() ).'edit-tags.php?taxonomy=task_chains&post_type=tasks',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				)
			);
			
			// displays Task edit page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_tasks',
					'title' => 'Edit '.get_option( 'go_tasks_plural_name' ),
					'href' => get_admin_url().'edit.php?post_type=tasks',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);
			
			// displays Store Categories page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_store_types',
					'title' => get_option('go_store_name').' Categories',
					'href' => esc_url( get_admin_url() ).'edit-tags.php?taxonomy=store_types&post_type=go_store',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				)
			);

			// displays Store Item edit page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_store',
					'title' => 'Edit '.get_option( 'go_store_name' ).' Items',
					'href' => get_admin_url().'edit.php?post_type=go_store',
					'parent' => 'go_site_name_menu',
					'meta' => array( 'class' => 'go_site_name_menu_item' )
				) 
			);

			// displays GO options page link
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_options',
					'title' => 'Game-On',
					'href' => get_admin_url().'admin.php?page=game-on-options.php',
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
			$wp_admin_bar->add_node(
				array(
					'id' => 'go_nav_users',
					'title' => 'Users',
					'href' => esc_url( get_admin_url() ).'users.php',
					'parent' => 'appearance',
				)
			);
			
		}




        //displays Timer in admin bar
        $post_id = get_the_ID();
        $timer_on = get_post_meta($post_id,'go_timer_toggle', true);
        if ($timer_on) {

            $atts = shortcode_atts(array(
                'id' => '', // ID defined in Shortcode
                'cats' => '', // Cats defined in Shortcode
            ), $atts);
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