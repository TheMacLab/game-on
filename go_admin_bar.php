<?php

function go_display_admin_bar(){
	if(go_return_options('go_admin_bar_display_switch') == 'On'){
		return true;
	}
}

function go_admin_bar(){
	global $wpdb;
	global $current_user_id;
	global $wp_admin_bar;
	global $current_points; //users current experience
	global $current_currency; //users current money
	global $current_rank;
	global $next_rank_points;
	global $current_rank_points;
	$dom = ($next_rank_points-$current_rank_points);
	$rng = ($current_points - $current_rank_points);
	$current_bonus_currency = go_return_bonus_currency(get_current_user_id());
	$current_penalty = go_return_penalty(get_current_user_id());
	$table_name_options = $wpdb->prefix."options";
	if($dom <= 0){ $dom = 1;}
	$percentage = $rng/$dom*100;
	if($percentage <= 0){ $percentage = 0;} else if($percentage >= 100){$percentage = 100;}
	
	$color = barColor($current_bonus_currency);
	
	$wp_admin_bar->remove_menu('wp-logo');
	
	if(!is_user_logged_in()){
		return
		$wp_admin_bar->add_menu(
			array(
				'id' => 'go_toolbar_login',
				'title' => 'Login',
				'href' => wp_login_url()
			)
		);
	}
	
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
			'title' => '<div style="padding-top:5px;"><div id="go_admin_bar_progress_bar_border"><div id="points_needed_to_level_up" class="go_admin_bar_text">'.($rng).'/'.($dom).'</div><div id="go_admin_bar_progress_bar" class="progress_bar" style="width: '.$percentage.'%; background-color: '.$color.' ;"></div></div></div>',
			'href' => '#',
			'id' => 'go_info',
		));
	
	
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<div id="go_admin_bar_points">'.go_return_options('go_points_name').': '.go_return_options('go_points_prefix').$current_points.go_return_options('go_points_suffix').'</div>',
		'href' => '#',
		'parent' => 'go_info',
	));
	
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<div id="go_admin_bar_currency">'.go_return_options('go_currency_name').': '.go_display_currency($current_currency).'</div>',
		'href' => '#',
		'parent' => 'go_info',
	));
	
	if (!is_admin_bar_showing()|| !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<div id="go_admin_bar_rank">'.$current_rank.'</div>',
		'href' => '#',
		'parent' => 'go_info',
	));
	
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<div id="go_admin_bar_bonus_currency">'.go_return_options('go_bonus_currency_name').': '.$current_bonus_currency.'</div>',
		'href' => '#',
		'parent' => 'go_info',
	));
	
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<div id="go_admin_bar_penalty">'.go_return_options('go_penalty_name').': '.$current_penalty.'</div>',
		'href' => '#',
		'parent' => 'go_info',
	));

	
	
////////////////////////////////////////////////////////////////////////	
if(go_return_options('go_admin_bar_add_switch') == 'On'){	
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => 'Add',
		'href' => '#',
		'id' => 'go_add',
	));
	
		if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => 
		'<div id="go_admin_bar_title">Points</div>
		<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_points_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_points_reason"/></div>
		<div id="go_admin_bar_title">'.go_return_options('go_currency_name').'</div>
		<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_currency_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_currency_reason"/></div>
		<div id="go_admin_bar_title">'.go_return_options('go_bonus_currency_name').'</div>
		<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_bonus_currency_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_bonus_currency_reason"/></div>
		<div id="go_admin_bar_title">'.go_return_options('go_penalty_name').'</div>
		<div id="go_admin_bar_input"><input type="text" class="go_admin_bar_points" id="go_admin_bar_penalty_points"/> For <input type="text" class="go_admin_bar_reason" id="go_admin_bar_penalty_reason"/></div>
		<div><input type="button" style="width:250px; height: 20px;margin-top: 7px;" name="go_admin_bar_submit" onclick="go_admin_bar_add();" value="Add"><div id="admin_bar_add_return"></div></div>',
		'href' => false,
		'parent' => 'go_add',
	));}

///////////////////////////////////////////////////////////////////////////

if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<div onclick="go_admin_bar_stats_page_button();">Stats</div><div id="go_stats_page"></div>',
		'href' => '#',
		'id' => 'go_stats',
	));

if (!is_admin_bar_showing() || !is_user_logged_in())
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '
			<form role="search" method="get" id="go_admin_bar_task_search_form" class="searchform" action="' . home_url( '/' ) . '" >
		    	<div><label class="screen-reader-text" for="s">' . __( 'Search for:' ) . '</label>
		    		<input type="text" value="' . get_search_query() . '" name="s" id="go_admin_bar_task_search_input" placeholder="Search for tasks..."/>
		    		<input type="hidden" name="post_type[]" value="tasks" />
		    		<input type="submit" id="go_admin_bar_task_search_submit" value="'. esc_attr__( 'Search' ) .'" />
		    	</div>
		    </form>',
		'id' => 'go_task_search'
	));

if (!is_admin_bar_showing() || !is_user_logged_in() || !is_super_admin() )
		return;
		$wp_admin_bar->add_menu( array(
		'title' => 'Clipboard',
		'href' => get_site_url().'/wp-admin/admin.php?page=go_clipboard',
		'parent' => 'site-name'
	));	
	
if (!is_admin_bar_showing() || !is_user_logged_in() || !is_super_admin())
		return;
		$wp_admin_bar->add_menu( array(
		'title' => '<form method="post" action=""><input type="submit" name="go_admin_bar_deactivation" value="Deactivate"/></form>',
		'parent'=>'go_info'
	));
	
	
	if(isset($_POST['go_admin_bar_deactivation'])){
		$plugins = $wpdb->get_var("select option_value from ".$table_name_options." where option_name = 'active_plugins'");
		$plug = unserialize($plugins);
if(in_array('game-on-master/game-on.php', $plug)){
	$key = array_search('game-on-master/game-on.php', $plug);
 unset($plug[$key]);
	}
		update_option('active_plugins', $plug);
		}
	
}
?>