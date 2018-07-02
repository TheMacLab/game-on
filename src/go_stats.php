<?php

function go_stats_overlay() { 
	echo '<div id="go_stats_page_black_bg" style="display:none !important;"></div><div id="go_stats_white_overlay" style="display:none;"></div>';
}

function go_admin_bar_stats() {
	$user_id = 0;
	if ( ! empty( $_POST['uid'] ) ) {
		$user_id = (int) $_POST['uid'];
		$current_user = get_userdata( $user_id );
	} else {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
	}
	check_ajax_referer( 'go_admin_bar_stats_' );
	
	?>
	<input type="hidden" id="go_stats_hidden_input" value="<?php echo $user_id; ?>"/>
	<?php
	$user_fullname = $current_user->first_name.' '.$current_user->last_name;
	$user_login =  $current_user->user_login;
	$user_display_name = $current_user->display_name;
	$user_website = $current_user->user_url;

    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');
    if ($use_local_avatars){
        $user_avatar_id = get_user_meta( $user_id, 'go_avatar', true );
        $user_avatar = wp_get_attachment_image($user_avatar_id);
    }
    if (empty($user_avatar) && $use_gravatar) {
        $user_avatar = get_avatar( $user_id, 150 );
    }

	//$user_focuses = go_display_user_focuses( $user_id );
	
	// option names 
	$points_name = get_option( 'go_points_name' );
	$currency_name = get_option( 'go_currency_name' );
	$bonus_currency_name = get_option( 'go_bonus_currency_name' );
	$penalty_name = get_option( 'go_penalty_name' );
	$minutes_name = get_option( 'go_minutes_name' );

	$current_points = go_return_points( $user_id );
	$current_currency = go_return_currency( $user_id );
	$current_bonus_currency = go_return_health( $user_id );
	//$current_penalty = go_return_penalty( $user_id );
	$current_minutes = go_return_c4( $user_id );

	$go_option_ranks = get_option( 'go_ranks' );
	$points_array = $go_option_ranks['points'];

	$max_rank_index = count( $points_array ) - 1;
	$max_rank_points = (int) $points_array[ $max_rank_index ];

	$percentage_of_level = 1;

	// user pnc 
	$rank = go_get_rank( $user_id );
	$current_rank = $rank['current_rank'];
	$current_rank_points = $rank['current_rank_points'];
	$next_rank = $rank['next_rank'];
	$next_rank_points = $rank['next_rank_points'];
	
	if ( null !== $next_rank_points ) {
		$rank_threshold_diff = ( $next_rank_points - $current_rank_points );
	} else {
		$rank_threshold_diff = 1;
	}
	$pts_to_rank_threshold = ( $current_points - $current_rank_points );

	if ( $max_rank_points === $current_rank_points ) {
		$prestige_name = get_option( 'go_prestige_name' );
		$pts_to_rank_up_str = $prestige_name;
	} else {
		$pts_to_rank_up_str = "{$pts_to_rank_threshold} / {$rank_threshold_diff}";
	}

	$percentage_of_level = ( $pts_to_rank_threshold / $rank_threshold_diff ) * 100;
	if ( $percentage_of_level <= 0 ) { 
		$percentage_of_level = 0;
	} else if ( $percentage_of_level >= 100 ) {
		$percentage_of_level = 100;
	}



	/////////////////////////
    ///
    $xp_toggle = get_option('options_go_loot_xp_toggle');
    $gold_toggle = get_option('options_go_loot_gold_toggle');
    $health_toggle = get_option( 'options_go_loot_health_toggle' );
    $c4_toggle = get_option('options_go_loot_c4_toggle');

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
        $progress_bar = '<div id="go_admin_bar_progress_bar_border"  class="progress-bar-border">'.'<div id="go_admin_bar_progress_bar" class="progress_bar" '.
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
        $gold_total = '<div id="go_admin_bar_gold" class="admin_bar_loot">' . go_display_shorthand_currency('gold', $go_current_gold)  . '</div>';
    }
    else{
        $gold_total = '';
    }

    if ($c4_toggle) {
        // the user's current amount of minutes
        $go_current_c4 = go_get_user_loot( $user_id, 'c4' );
        $c4_total =  '<div id="go_admin_bar_c4" class="admin_bar_loot">' . go_display_shorthand_currency('c4', $go_current_c4) . '</div>';
    }
    else{
        $c4_total = '';
    }
    //////////////////



	?>
	<div id='go_stats_lay'>
        <div id='go_stats_header'>
		    <div id='go_stats_gravatar'><?php echo $user_avatar; ?></div>

			<div id='go_stats_user_info'>
				<?php echo "<h2>{$user_fullname}</h2><a href='{$user_website}' target='_blank'>{$user_display_name}</a><br/>"; ?>

            </div>
            <div id='go_stats_user_loot'>

                <?php
                if ($xp_toggle) {
                    echo '<div id="go_stats_rank"><h3>' . $go_option_ranks . ' ' . $rank_num . ": " . $current_rank . '</h3></div>';
                }
                echo $progress_bar;
                //echo "<div id='go_stats_user_points'><span id='go_stats_user_points_value'>{$current_points}</span> {$points_name}</div><div id='go_stats_user_currency'><span id='go_stats_user_currency_value'>{$current_currency}</span> {$currency_name}</div><div id='go_stats_user_bonus_currency'><span id='go_stats_user_bonus_currency_value'>{$current_bonus_currency}</span> {$bonus_currency_name}</div>{$current_penalty} {$penalty_name}<br/>{$current_minutes} {$minutes_name}";
                echo $health_bar;
                if ($xp_toggle) {
                    echo '<div id="go_stats_xp">' . go_display_longhand_currency('xp', $go_current_xp) . '</div>';
                }
                if ($gold_toggle) {
                    echo '<div id="go_stats_gold">' . go_display_longhand_currency('gold', $go_current_gold) . '</div>';
                }
                if ($health_toggle) {
                    echo '<div id="go_stats_health">' . go_display_longhand_currency('health', $go_current_health) . '</div>';
                }
                if($c4_toggle) {
                    echo '<div id="go_stats_c4">' . go_display_longhand_currency('c4', $go_current_c4) . '</div>';
                }
                ?>
            </div>
        </div>
			<div id='go_stats_user_tabs'>
            <!--
				<a href='javascript:;' id="go_stats_body_progress" class='go_stats_body_selectors' tab='progress'>
					WEEKLY PROGRESS
				</a> | 
            -->
            	<?php $is_admin = current_user_can( 'manage_options' ); if ( $is_admin ) { ?>
               		<a href='javascript:;' id='go_stats_admin_help' class='go_stats_body_selectors' tab='help'>
                    	HELP
                    </a> |
                <?php } ?>

				<a href='javascript:;' id="go_stats_body_tasks" class='go_stats_body_selectors' tab='tasks'>
					<?php echo strtoupper( get_option( 'options_go_tasks_name_plural' ) ); ?>
				</a> |
                <a href='javascript:;' id="go_stats_body_activity" class='go_stats_body_selectors' tab='activity'>
                    ACTIVITY
                </a> |
				<a href='javascript:;' id="go_stats_body_badges" class='go_stats_body_selectors' tab='badges'>
					<?php echo strtoupper( get_option( 'go_badges_name' ) ); ?>
				</a> | 
				<a href='javascript:;' id="go_stats_body_leaderboard" class='go_stats_body_selectors' tab='leaderboard'>
					<?php echo strtoupper( get_option( 'go_leaderboard_name' ) ); ?>
				</a>
			</div>
		<div id='go_stats_body'></div>
	</div>
	<?php 
	die();
}

function go_stats_task_list() {
    global $wpdb;
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    if ( ! empty( $_POST['user_id'] ) ) {
        $user_id = (int) $_POST['user_id'];
    } else {
        $user_id = get_current_user_id();
    }
    check_ajax_referer( 'go_stats_task_list_' );

    $tasks = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d
			ORDER BY id DESC",
            $user_id
        )
    );
    echo "<table id='go_stats_datatable' class='pretty'>
                   <thead>
						<tr>
							<th class='header' id='go_stats_post_name'><a href=\"#\">Post Name</a></th>
							<th class='header' id='go_stats_first_time'><a href=\"#\">First Time</a></th>
							<th class='header' id='go_stats_last_time'><a href=\"#\">Last Time</a></th>
							
							<th class='header' id='go_stats_status'><a href=\"#\">Status</a></th>
							<th class='header' id='go_stats_bonus_status'><a href=\"#\">Bonus Status</a></th>
							<th class='header' id='go_stats_links'><a href=\"#\">Links</a></th>
							
							<th class='header' id='go_stats_mods'><a href=\"#\">XP</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">G</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">H</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">AP</a></th>
							
						</tr>
						</thead>
			    <tbody>
						";
    foreach ( $tasks as $task ) {
        $post_id = $task->post_id;
        $status = $task->status;
        $bonus_status = $task->bonus_status;
        $xp = $task->xp;
        $gold = $task->gold;
        $health = $task->health;
        $c4 = $task->c4;
        $start_time = $task->start_time;
        $last_time = $task->last_time;

        echo " 			
			        <tr id='postID_{$post_id}'>
			           
					    <td>{$post_id}</td>
					    <td>{$start_time}</td>
					    <td>{$last_time}</td>
					    
					    <td>{$status}/ add total stages here</td>
					    <td>{$bonus_status}/ add bonus max</td>
					    <td>Add links to URLs, Uploads, and Blog Posts</td>
					    
					    <td>{$xp}</td>
					    <td>{$gold}</td>
					    <td>{$health}</td>
					    <td>{$c4}</td>
					 
					</tr>
					";


    }
    echo "</tbody>
				</table>";

    die();
}

function go_stats_task_listOLD() {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go_tasks";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_task_list_' );

	$is_admin = current_user_can( 'manage_options' );
	$task_list = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT status, post_id, count, url 
			FROM {$go_task_table_name} 
			WHERE uid = %d  
			ORDER BY id DESC",
			$user_id
		)
	);
	$counter = 1;
	?>
	<div id='go_stats_tasks_list' <?php if ( $is_admin ) { echo "class='go_stats_tasks_list_admin'"; } ?>>
		<?php
		foreach ( $task_list as $task ) {
			$task_urls = unserialize( $task->url );
			$custom = get_post_meta( $task->post_id );
			?>
			<div class='go_stats_task <?php if ( $counter % 2 == 0) { echo 'go_stats_right_task'; } ?>'>
				<a href='<?php echo get_permalink( $task->post_id ); ?>' <?php echo ( ( $user_id != get_current_user_id() ) ? "target='_blank'" : '' ); ?>class='go_stats_task_list_name'><?php echo get_the_title( $task->post_id); ?></a>

				<div class='go_stats_task_status_wrap'>
				<?php
								
				if ( ! empty( $custom['go_mta_three_stage_switch'][0] ) && $custom['go_mta_three_stage_switch'][0] == 'on' ) {
					$stage_count = 3;
				} elseif ( ! empty( $custom['go_mta_five_stage_switch'][0] ) && $custom['go_mta_five_stage_switch'][0] == 'on' ) {
					$stage_count = 5;
				} else {
					$stage_count = 4;
				}

				$url_switch = array(
					1 => ! empty( $custom['go_mta_encounter_url_key'][0] ),
					2 => ! empty( $custom['go_mta_accept_url_key'][0] ),
					3 => ! empty( $custom['go_mta_completion_url_key'][0] ),
					4 => ! empty( $custom['go_mta_mastery_url_key'][0] ),
					5 => ! empty( $custom['go_mta_repeat_url_key'][0] )
				);
				
				for ( $i = 5; $i > 0; $i--) {

					/* 
					 * Produces an empty string when the user hasn't viewed any quests yet.
					 * When populated, the timestamps variable will contain an array. The
					 * array will contain arrays, indexed by the post id of the task, which
					 * will contain timestamps indexed by stage.
					 *  
					 * e.g. $timestamps[342][2][0] will grab the first registered attempt
					 *		at the accept stage of task with the post id 342.
					 *		$timestamps[342][2][1] will grab the most recent attempt.
					 */
					$timestamps = get_user_meta( $user_id, 'go_task_timestamps', true );

					// Used for the class attribute in the stage box anchor tag.
					$link_class_list_str = 'go_stats_task_stage_wrap go_user';

					/*
					 * Used for the href attribute in the stage box anchor tag.
					 * The stage URL is not set to "#" by default, because the
					 * stage box div tag's class list relies on the stage URL
					 * being empty. An inline empty check is made for the stage
					 * URL as the stage box is being output.
					 */
					$link_stage_url = '';

					// Used for the target attribute in the stage box anchor tag.
					$link_target_string = '';

					// Used for the class attribute in the stage box div tag.
					$div_class_list_str = 'go_stats_task_status';

					// Used for the title attribute in the stage box div tag.
					$div_title_str = '';

					// Used for the count attribute in the repeat stage box div tag.
					$div_count_str = '';

					/*
					 * Used for the date timestamp in the content of the stage box div tag
					 * (for all except the repeat stage box).
					 */
					$div_timestamp_date_str = '';
			
					unset($url_array);
		
					if ( 5 > $i ) {
						//$link_stage_url = $task_urls[ $i - 1 ];
						
						foreach ($task_urls as $key => $taskurl){
									if ($key === $i + 1){
										$link_stage_url = $taskurl;
									}		
							}
									
					} else if ( 5 == $i  ) {
							
							foreach ($task_urls as $key => $taskurl){
									if ($key > 5){
										$url_array[] = $taskurl;
										$div_class_list_str .= ' popup';
										$div_class_list_str .= ' stage_url';
									}	
							}
					}

					if ( $is_admin ) {
						$link_class_list_str = 'go_stats_task_admin_stage_wrap';
					}

					if ( ! empty( $link_stage_url ) ) {
						$link_class_list_str .= ' go_stats_task_stage_url';
					}

					if ($i == 1){$i = 0; }
					if ( is_array( $timestamps ) && ! empty( $timestamps[ $task->post_id ] ) &&
							! empty( $timestamps[ $task->post_id ][ $i ] ) ) {
						$div_title_str = "First attempt: {$timestamps[ $task->post_id ][ $i ][0]}\n".
							"Most recent: {$timestamps[ $task->post_id ][ $i ][1]}";
					}
					if ($i == 0){$i = 1; }

					if ( $task->status >= $i || $task->count >= 1 ) {
						$div_class_list_str .= ' completed';
					} else if ( $i > $stage_count ) {
						$div_class_list_str .= ' go_stage_does_not_exist';
					}

					if ( ! empty( $link_stage_url )) {
						$div_class_list_str .= ' stage_url';
					}

					if ( ! empty( $url_switch[ $i - 1 ] ) &&
							$task->status < $i &&
							$task->count < 1 &&
							$i <= $stage_count ) {
						$div_class_list_str .= ' future_url';
					}

					if ( $i == 5 && $task->count > 0 ) {
						$div_count_str = $task->count;
					}

					if ($i == 1){$i = 0; }
					if ( 
							is_array( $timestamps ) &&
							! empty( $timestamps[ $task->post_id ] ) &&
							! empty( $timestamps[ $task->post_id ][ $i ][0] ) ) {
						
						$div_timestamp_date_str = substr(
							$timestamps[ $task->post_id ][ $i ][0],
							0,
							5
						);
						
					}
					if ($i == 0){$i = 1; }
					/*
					 * This echo statement displays the stage boxes in the stats panel.
					 * We simply check for the task count string being empty, as the count
					 * attribute should be added to the div when the repeat stage's box is
					 * being displayed. There is no check for the date timestamp string being
					 * empty because it will simply output an empty string on the repeat
					 * stage's box.
					 */
					 if ( $i < 5 ) {
						echo "
							<a class='{$link_class_list_str}' href='".( ! empty( $link_stage_url ) ? $link_stage_url : '#' )."' ".
									( ! empty( $link_stage_url ) ? 'target="_blank"' : '' ).">
								<div class='{$div_class_list_str}' title='{$div_title_str}' task='{$task->post_id}' stage='{$i}' ".
										( ! empty( $div_count_str ) ? "count='{$div_count_str}'>{$div_count_str}" : '>' ).
									"<p>{$div_timestamp_date_str}</p>
								</div>
							</a>
						";
					}
					else if( $i == 5 ) {
						

						echo "
								<div class='{$div_class_list_str}'  title='{$div_title_str}'  task='{$task->post_id}' stage='{$i}' ".
										( ! empty( $div_count_str ) ? "count='{$div_count_str}'>" : '>' ).
									"<p>{$div_count_str}</p>
									  <div class='popuptext' id='myPopup_$div_count_str' >";
						
						
						//repeat status - 4 times
							$repeat_counter = $task->status - 5;
							$repeat_num = 1;
							foreach ($url_array as $url){
								//echo '<script type="text/javascript">alert("'.$url.'");</script>';

								echo "<p><a href='$url'>$repeat_num</a></p>";
								if ($repeat_num == $repeat_counter){break;}
								$repeat_num++;

							}

						echo"</div>" . $repeat_counter . "</div>";
							
					}
				}
				?>
				</div>
			<?php
			$counter++;
			
			if ( $is_admin ) {
				?>
				<input type='text' class='go_stats_task_admin_message' id='go_stats_task_<?php echo $task->post_id ?>_message' name='go_stats_task_admin_message' placeholder='See me'/>
				<div class='go_stats_task_admin_submit_div'><button class='go_stats_task_admin_submit' task='<?php echo $task->post_id; ?>'>SEND</button></div>
				<?php 
				}
				?>
			</div>
			<?php
		}
	?>
	</div>
	<?php
	die();
}

function go_stats_move_stage() {
	global $wpdb;

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}

	$go_task_table_name = "{$wpdb->prefix}go";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_move_stage_' );

	$current_rank = get_user_meta( $user_id, 'go_rank', true );
	$task_id = ( ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0 );
	$status  = ( ! empty( $_POST['status'] ) ? (int) $_POST['status'] : 1 );
	$count   = ( ! empty( $_POST['count'] ) ? (int) $_POST['count'] : 0 );
	$message = ( ! empty( $_POST['message'] ) ? sanitize_text_field( $_POST['message'] ) : 'See me' );
	$custom_fields = get_post_custom( $task_id );
	$date_picker = (
		! empty( $custom_fields['go_mta_date_picker'][0] ) && unserialize( $custom_fields['go_mta_date_picker'][0] ) ?
		array_filter( unserialize( $custom_fields['go_mta_date_picker'][0] ) ) :
		null
	);
	$rewards = unserialize( $custom_fields['go_presets'][0] );
	$current_status = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status 
			FROM {$go_task_table_name} 
			WHERE uid = %d AND post_id = %d",
			$user_id,
			$task_id
		)
	);
	$page_id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT page_id 
			FROM {$go_task_table_name} 
			WHERE uid = %d AND post_id = %d",
			$user_id,
			$task_id
		)
	);

	$go_option_ranks = get_option( 'go_ranks' );
	$points_array = $go_option_ranks['points'];

	$max_rank_index = count( $points_array ) - 1;
	$max_rank_points = (int) $points_array[ $max_rank_index ];
	$prestige_name = get_option( 'go_prestige_name' );

	$changed = array(
		'type'            => 'json',
		'points'          => 0,
		'currency'        => 0,
		'bonus_currency'  => 0,
		'max_rank_points' => $max_rank_points,
		'prestige_name'   => $prestige_name,
	);

	if ( ! empty( $date_picker ) ) {
		$dates = $date_picker['date'];
		$percentages = $date_picker['percent'];
		$unix_today = strtotime( date( 'Y-m-d' ) );

		$past_dates = array();

		foreach ( $dates as $key => $date ) {
			if ( $unix_today >= strtotime( $date ) ) {
				$past_dates[ $key ] = abs( $unix_today - strtotime( $date ) );
			}
		}

		if ( ! empty( $past_dates ) ) {
			asort( $past_dates );
			$update_percent = (float) ( ( $percentages[ key( $past_dates ) ] ) / 100);
		} else {
			$update_percent = 1;
		}
	} else {
		$update_percent = 1;
	}

	if ( 1 === $status ) {
		$current_rewards = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT points, currency, bonus_currency 
				FROM {$go_task_table_name} 
				WHERE uid = %d AND post_id = %d",
				$user_id,
				$task_id
			)
		);

		go_task_abandon(
			$user_id,
			$task_id,
			$current_rewards[0]->points,
			$current_rewards[0]->currency,
			$current_rewards[0]->bonus_currency * $update_percent
		);

		$changed['points'] = -$current_rewards[0]->points;
		$changed['currency'] = -$current_rewards[0]->currency;
		$changed['bonus_currency'] = -$current_rewards[0]->bonus_currency;

		$current_points = go_return_points( $user_id );
		$updated_rank = get_user_meta( $user_id, 'go_rank', true );
		if ( $current_rank[0][1] != $updated_rank[0][1] ) {
			$changed['rank'] = $updated_rank[0][0];
		}
		$changed['current_points'] = $current_points;
		$changed['current_rank_points'] = $updated_rank[0][1];
		$changed['next_rank_points'] = $updated_rank[1][1];
		$changed['abandon'] = 'true';

		if ( 'See me' === $message ) {
			go_message_user( $user_id, $message.' about, <a href="'.get_permalink( $task_id ).'" style="display: inline-block; text-decoration: underline; padding: 0px; margin: 0px;">'.get_the_title( $task_id ).'</a>, please.' );
		} else {
			go_message_user( $user_id, 'RE: <a href="' . get_permalink( $task_id ) . '">' . get_the_title( $task_id ) . '</a> ' . $message );
		}
	} else {

		for ( $count; $count > 0; $count-- ) {
			go_add_post(
				$user_id, $task_id, $current_status,
				floor( -$rewards['points'][ $current_status ] * $update_percent ),
				floor( -$rewards['currency'][ $current_status ] * $update_percent ),
				floor( -$rewards['bonus_currency'][ $current_status ] * $update_percent ),
				null, $page_id, true, -1
			);

			$changed['points'] += floor(
				-$rewards['points'][ $current_status ] * $update_percent
			);
			$changed['currency'] += floor(
				-$rewards['currency'][ $current_status ] * $update_percent
			);
			$changed['bonus_currency'] += floor(
				-$rewards['bonus_currency'][ $current_status ] * $update_percent
			);
		}

		while ( $current_status != $status ) {
			if ( $current_status > $status ) {
				$current_status--;

				go_add_post(
					$user_id, $task_id, $current_status,
					floor( -$rewards['points'][ $current_status ] * $update_percent ),
					floor( -$rewards['currency'][ $current_status ] * $update_percent ),
					floor( -$rewards['bonus_currency'][ $current_status ] * $update_percent ),
					null, $page_id, false, null
				);

				$changed['points'] += floor(
					-$rewards['points'][ $current_status ] * $update_percent
				);
				$changed['currency'] += floor(
					-$rewards['currency'][ $current_status ] * $update_percent
				);
				$changed['bonus_currency'] += floor(
					-$rewards['bonus_currency'][ $current_status ] * $update_percent
				);

			} elseif ( $current_status < $status ) {
				$current_status++;
				$current_count = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT count 
						FROM {$go_task_table_name} 
						WHERE uid = %d AND post_id = %d",
						$user_id,
						$task_id
					)
				);
				if ( 5 === $current_status && 0 === $current_count ) {
					go_add_post(
						$user_id, $task_id, $current_status - 1,
						floor( $rewards['points'][ $current_status - 1 ] * $update_percent ),
						floor( $rewards['currency'][ $current_status - 1 ] * $update_percent ),
						floor( $rewards['bonus_currency'][ $current_status - 1 ] * $update_percent ),
						null, $page_id, true, 1
					);

					$changed['points'] += floor( $rewards['points'][ $current_status - 1 ] * $update_percent );
					$changed['currency'] += floor( $rewards['currency'][ $current_status - 1 ] * $update_percent );
					$changed['bonus_currency'] += floor( $rewards['bonus_currency'][ $current_status - 1 ] * $update_percent );

				} elseif ( $current_status < 5 ) {
					go_add_post(
						$user_id, $task_id, $current_status,
						floor( $rewards['points'][ $current_status - 1 ] * $update_percent ),
						floor( $rewards['currency'][ $current_status - 1 ] * $update_percent ),
						floor( $rewards['bonus_currency'][ $current_status - 1 ] * $update_percent ),
						null, $page_id, false, null
					);

					$changed['points'] += floor( $rewards['points'][ $current_status - 1 ] * $update_percent );
					$changed['currency'] += floor( $rewards['currency'][ $current_status - 1 ] * $update_percent );
					$changed['bonus_currency'] += floor( $rewards['bonus_currency'][ $current_status - 1 ] * $update_percent );
				}
			}
		}

		if ( 'See me' === $message ) {
			go_message_user( $user_id, $message.' about, <a href="'.get_permalink( $task_id ).'" style="display: inline-block; text-decoration: underline; padding: 0px; margin: 0px;">'.get_the_title( $task_id ).'</a>, please.' );
		} else {
			go_message_user( $user_id, 'RE: <a href="'.get_permalink( $task_id ).'">'.get_the_title( $task_id ).'</a> '.$message );
		}
		$current_points = go_return_points( $user_id );
		$updated_rank = get_user_meta( $user_id, 'go_rank', true );
		if ( $current_rank[0][1] != $updated_rank[0][1] ) {
			$changed['rank'] = $updated_rank[0][0];
		}
		$changed['current_points'] = $current_points;
		$changed['current_rank_points'] = $updated_rank[0][1];
		$changed['next_rank_points'] = $updated_rank[1][1];
	}

	echo json_encode( $changed );
	die();
}

function go_stats_item_list() {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_item_list_' );

	$items = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d AND status = %d AND gifted = %d 
			ORDER BY timestamp DESC, id DESC, reason DESC",
			$user_id,
			-1,
			0
		)
	);
	$gifted_items = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d AND status = %d AND gifted = %d 
			ORDER BY timestamp DESC, reason DESC, id DESC",
			$user_id,
			-1,
			1
		)
	);
	?>
	<div style="width: 99%;">
	 <div style="float: left; width: 33%;"><strong>PURCHASES</strong></div>
	 <div style="float: left; width: 33%;"><strong>RECEIVED</strong></div>
	 <div style="float: left; width: 33%;"><strong>SOLD</strong></div>
 	 <br style="clear: left;" />
	</div>

	<ul id='go_stats_item_list_purchases' class='go_stats_body_list'>
		<?php
		
		foreach ( $items as $item ) {
			$item_id = $item->post_id;
			$item_count_total = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT SUM( count ) 
					FROM {$go_task_table_name} 
					WHERE uid = %d AND status = %d AND post_id = %d",
					$user_id,
					-1,
					$item_id
				)
			);
			$count_before = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT SUM( count ) 
					FROM {$go_task_table_name} 
					WHERE uid = %d AND status = %d AND post_id = %d AND id <= %d",
					$user_id,
					-1,
					$item_id,
					$item->id
				)
			);
			$purchase_date = $item->timestamp;
			$purchase_reason = $item->reason;
			?>
				<li class='go_stats_item go_stats_purchased_item'>
					<?php
						echo "<a href='#' onclick='go_lb_opener({$item_id})'>".get_the_title( $item_id )."</a> ({$count_before} of {$item_count_total}) {$purchase_date} {$purchase_reason}";
					?>
				</li>
			<?php
		}
		?>
	</ul>
	<ul id='go_stats_item_list_recieved' class='go_stats_body_list'>
        <?php
		
		if ( ! empty( $gifted_items ) ) {		
			foreach ( $gifted_items as $item ) {
				$item_id = $item->post_id;
				$item_count_total = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT SUM( count ) 
						FROM {$go_task_table_name} 
						WHERE uid = %d AND status = %d AND post_id = %d",
						$user_id,
						-1,
						$item_id
					)
				);
				$count_before = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT SUM( count ) 
						FROM {$go_task_table_name} 
						WHERE uid = %d AND status = %d AND post_id = %d AND id <= %d",
						$user_id,
						-1,
						$item_id,
						$item->id
					)
				);
				$purchase_date = $item->timestamp;
				$purchase_reason = $item->reason;
				?>
					<li class='go_stats_item go_stats_purchased_item'>
						<?php
							echo "<a href='#' onclick='go_lb_opener({$item_id})'>".get_the_title( $item_id )."</a> ({$count_before} of {$item_count_total}) {$purchase_date}";
						?>
					</li>
				<?php
			}
		}
		?>
	</ul>
	<ul class='go_stats_body_list'>
	</ul>
	<?php
	die();
}

function go_stats_rewards_list() {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_rewards_list_' );

	$new_tab = ( $user_id != get_current_user_id() ) ? "target='_blank'" : '';
	$rewards = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d AND ( points != %d OR currency != 0 OR bonus_currency != 0 ) 
			ORDER BY id DESC",
			$user_id,
			0
		)
	);
	?>
	<div style="width: 99%;">
	 <div style="float: left; width: 33%;"><strong><?php echo strtoupper( get_option( 'go_points_name' ) ); ?></strong></div>
	 <div style="float: left; width: 33%;"><strong><?php echo strtoupper( get_option( 'go_currency_name' ) ); ?></strong></div>
	 <div style="float: left; width: 33%;"><strong><?php echo strtoupper( get_option( 'go_bonus_currency_name' ) ); ?></strong></div>
 	 <br style="clear: left;" />
	</div>
	<ul id='go_stats_rewards_list_points' class='go_stats_body_list'>
	<?php
	foreach ( $rewards as $reward ) {
		$reward_id = $reward->post_id;
		$reward_points = $reward->points;
		if ( $reward_points != 0 ) {
			$reward_html = '';
			if ( ! empty( $reward->status ) ) {
				if ( $reward->status == -1 ) {
					$reward_html = sprintf(
						'<div class="go_stats_item_wrapper"><a href="#" onclick="go_lb_opener(%s)">%s</a></div>',
						$reward_id,
						get_the_title( $reward_id )
					);
				} elseif ( $reward->status < 6 ) {
					$reward_html = sprintf(
						'<div class="go_stats_item_wrapper"><a href="%s" %s>%s</a></div>',
						get_permalink( $reward_idF ),
						$new_tab,
						get_the_title( $reward_id )
					);
				} else {
					$reward_html = sprintf( '<div class="go_stats_item_wrapper">%s</div>', $reward->reason );
				}
				$reward_html .= "<div class='go_stats_amount'>({$reward_points})</div>";
			}
			printf( '<li class="go_stats_reward go_stats_reward_points">%s</li>', $reward_html );
		}
	}
	?>
	</ul>
	<ul id='go_stats_rewards_list_currency' class='go_stats_body_list'>
	<?php
	foreach ( $rewards as $reward ) {
		$reward_id = $reward->post_id;
		$reward_currency = $reward->currency;
		if ( $reward_currency != 0 ) {
			$reward_html = '';
			if ( ! empty( $reward->status ) ) {
				if ( $reward->status == -1 ) {
					$reward_html = sprintf(
						'<div class="go_stats_item_wrapper"><a href="#" onclick="go_lb_opener(%s)">%s</a></div>',
						$reward_id,
						get_the_title( $reward_id )
					);
				} elseif ( $reward->status < 6 ) {
					$reward_html = sprintf(
						'<div class="go_stats_item_wrapper"><a href="%s" %s>%s</a></div>',
						get_permalink( $reward_idF ),
						$new_tab,
						get_the_title( $reward_id )
					);
				} else {
					$reward_html = sprintf( '<div class="go_stats_item_wrapper">%s</div>', $reward->reason );
				}
				$reward_html .= "<div class='go_stats_amount'>({$reward_currency})</div>";
			}
			printf( '<li class="go_stats_reward go_stats_reward_currency">%s</li>', $reward_html );
		}
	}
	?>
	</ul>
	<ul id='go_stats_rewards_list_bonus_currency' class='go_stats_body_list'>
	<?php
		foreach ( $rewards as $reward ) {
			$reward_id = $reward->post_id;
			$reward_bonus_currency = $reward->bonus_currency;
			if ( $reward_bonus_currency != 0 && ! empty( $reward->status ) && $reward->status !== 6 ) {
				$reward_html = '';
				if ( $reward->status == -1 ) {
					$reward_html = sprintf(
						'<div class="go_stats_item_wrapper"><a href="#" onclick="go_lb_opener(%s)">%s</a></div>',
						$reward_id,
						get_the_title( $reward_id )
					);
				} elseif ( $reward->status < 6 ) {
					$reward_html = sprintf(
						'<div class="go_stats_item_wrapper"><a href="%s" %s>%s</a></div>',
						get_permalink( $reward_idF ),
						$new_tab,
						get_the_title( $reward_id )
					);
				} else {
					$reward_html = sprintf( '<div class="go_stats_item_wrapper">%s</div>', $reward->reason );
				}
				$reward_html .= "<div class='go_stats_amount'>({$reward_bonus_currency})</div>";
				printf( '<li class="go_stats_reward go_stats_reward_bonus_currency">%s</li>', $reward_html );
			}
		}
	?>
	</ul>
	<?php
	die();
}

function go_stats_activity_list() {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go_actions";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_activity_list_' );

	$actions = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d
			ORDER BY id DESC",
			$user_id
		)
	);
    echo "<table id='go_stats_datatable' class='pretty'>
                   <thead>
						<tr>
						
							<th class='header' id='go_stats_time'><a href=\"#\">Time</a></th>
							<th class='header' id='go_stats_action'><a href=\"#\">Type</a></th>
							<th class='header' id='go_stats_post_name'><a href=\"#\">Action</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">Modifiers</a></th>
							
							<th class='header' id='go_stats_mods'><a href=\"#\">XP</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">G</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">H</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">AP</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">Total<br> XP</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">Total<br> G</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">Total<br> H</a></th>
							<th class='header' id='go_stats_mods'><a href=\"#\">Total<br> AP</a></th>
						</tr>
						</thead>
			    <tbody>
						";
    foreach ( $actions as $action ) {
        $action_type = $action->action_type;
        $source_id = $action->source_id;
        $TIMESTAMP = $action->TIMESTAMP;
        $stage = $action->stage;
        $bonus_status = $action->bonus_status;
        $result = $action->result;
        $quiz_mod = $action->quiz_mod;
        $late_mod = $action->late_mod;
        $timer_mod = $action->timer_mod;
        $health_mod = $action->global_mod;
        $xp = $action->xp;
        $gold = $action->gold;
        $health = $action->health;
        $c4 = $action->c4;
        $xp_total = $action->xp_total;
        $gold_total = $action->gold_total;
        $health_total = $action->health_total;
        $c4_total = $action->c4_total;

        $post_title = get_the_title($source_id);


        if ($action_type == 'admin'){
            $type = "Admin";
        }

        if ($action_type == 'store'){
                $store_qnty = $stage;
                $type = strtoupper( get_option( 'options_go_store_name' ) );
                $post_title = "Qnt: " . $store_qnty . " of " . $post_title ;
        }

        if ($action_type == 'task'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
                $post_title = $post_title . " Stage: " . $stage;
            }
        }

        if ($action_type == 'undo_task'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) );
            if ($bonus_status == 0) {
                $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) ) . " Undo";
                $post_title = $post_title . " Stage: " . $stage;
            }
        }
        if ($result == 'undo_bonus'){
            $type = strtoupper( get_option( 'options_go_tasks_name_singular' ) ) . " Undo Bonus";
            $post_title = $post_title . " Bonus: " . $bonus_status ;
        }

        $quiz_mod = intval($quiz_mod);
        if (!empty($quiz_mod)){
            $quiz_mod = "Quiz: ". $quiz_mod;
        }
        else{
            $quiz_mod = null;
        }

        $late_mod = intval($late_mod);
        if (!empty($late_mod)){
            $late_mod = "Late: ". $late_mod;
        }
        else{
            $late_mod = null;
        }

        $timer_mod = intval($timer_mod);
        if (!empty($timer_mod)){
            $timer_mod = "Timer: ". $timer_mod;
        }
        else{
            $timer_mod = null;
        }

        $health_mod = intval($health_mod);
        if (!empty($health_mod)){
            $health_mod_str = "Health: ". $health_mod;
        }
        else{
            $health_mod_str = null;
        }






		echo " 			
			        <tr id='postID_{$source_id}'>
			            <td>{$TIMESTAMP}</td>
					    <td>{$type} </td>
					    <td>{$post_title} </td>
					    <td>{$health_mod_str} {$timer_mod} {$late_mod} {$quiz_mod}</td>
					    
					    <td>{$xp}</td>
					    <td>{$gold}</td>
					    <td>{$health}</td>
					    <td>{$c4}</td>
					    <td>{$xp_total}</td>
					    <td>{$gold_total}</td>
					    <td>{$health_total}</td>
					    <td>{$c4_total}</td>
					</tr>
					";


    }
    echo "</tbody>
				</table>";

	die();
}

function go_stats_penalties_list() {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_penalties_list_' );

	$penalties = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * 
			FROM {$go_task_table_name} 
			WHERE uid = %d AND ( penalty != %d ) 
			ORDER BY id DESC",
			$user_id,
			0
		)
	);
	?>
	<ul id='go_stats_penalties_list' class='go_stats_body_list'>
		<?php 
			foreach ( $penalties as $penalty ) {
				?>
					<li class='go_stats_penalties'>
						<div class='go_stats_item_wrapper'><span><?php echo $penalty->reason.' '.$penalty->timestamp; ?></span></div>
						<div class='go_stats_amount'>(<?php echo $penalty->penalty; ?>)</div>
					</li>
				<?php
			}
		?>
	</ul>
	<?php
	die();
}

function go_stats_badges_list() {
	global $wpdb;
	$go_task_table_name = "{$wpdb->prefix}go";
	if ( ! empty( $_POST['user_id'] ) ) {
		$user_id = (int) $_POST['user_id'];
	} else {
		$user_id = get_current_user_id();
	}
	check_ajax_referer( 'go_stats_badges_list_' );

	$badges_array = get_user_meta( $user_id, 'go_badges', true );
	if ( is_array( $badges_array ) && ! empty( $badges_array ) ) {
		go_badge_output_list( $badges_array );
	}
	die();
}

function go_stats_leaderboard_choices() {
	check_ajax_referer( 'go_stats_leaderboard_choices_' );

	?>
	<div id='go_stats_leaderboard_filters'>
		<div id='go_stats_leaderboard_filters_head'>FILTERS</div>
		<label for='go_stats_leaderboard_focuses'><b><?php echo get_option( 'go_class_a_name' ); ?></b></label>
		<div id='go_stats_leaderboard_classes'>
			<?php
			$classes = get_option( 'go_class_a' );
			$first = 1;
			if ( $classes) {
				foreach ( $classes as $class_a ) {
					?>
						<div class='go_stats_leaderboard_class_wrap'><input type='checkbox' class='go_stats_leaderboard_class_choice' value='<?php echo $class_a; ?>'><span><?php echo $class_a; ?></span></div>
					<?php
					$first++;
				}
			}
			?>
		</div>
		<label for='go_stats_leaderboard_focuses'><b><?php echo get_option( 'go_focus_name' ); ?></b></label>
		<div id='go_stats_leaderboard_focuses'>
			<?php
			$focuses = get_option( 'go_focus' );
			if ( $focuses ) {
				foreach ( $focuses as $focus ) {
					?>
						<div class='go_stats_leaderboard_focus_wrap'><input type='checkbox' class='go_stats_leaderboard_focus_choice' value='<?php echo $focus; ?>'><span><?php echo $focus; ?></span></div>
					<?php
				}
			}
			?>
		</div>
	</div>
	<div id='go_stats_leaderboard'></div>
	<?php
	die();
}

function go_return_user_data( $id, $counter, $sort ) {
	$points = go_return_points( $id );
	$currency = go_return_currency( $id );
	$bonus_currency = go_return_health( $id );
	$badge_count = go_return_badge_count( $id );
	$user_data_key = get_userdata( $id );
	$user_display = "<a href='#' onclick='go_admin_bar_stats_page_button(&quot;{$id}&quot;);'>{$user_data_key->display_name}</a>";

	$amount = 0;
	switch ( $sort) {
		case 'points' :
			$amount = $points;
			break;
		case 'currency' :
			$amount = $currency;
			break;
		case 'bonus_currency' :
			$amount = $bonus_currency;
			break;
		case 'badges' :
			$amount = $badge_count;
			break;
		default:

			// returns without output, since there's nothing to display
			return;
	}
	if ( 0 !== $amount ) {
		printf(
			'<li><div class="go_stats_item_wrapper">%d %s</div><div class="go_stats_amount">%s</li>',
			$counter,
			$user_display,
			$amount
		);
	}
}

function go_return_user_leaderboard( $user_id_objs, $class_a_choice, $all_foci, $type, $counter ) {

	// stops if there are no users
	if ( empty( $user_id_objs ) || ! is_array( $user_id_objs ) ) {
		return;
	}

	foreach ( $user_id_objs as $obj ) {
		$user_id = (int) $obj->uid;
		if ( ! go_user_is_admin( $user_id ) ) {
			$class_a = get_user_meta( $user_id, 'go_classifications', true );
			$user_focus = get_user_meta( $user_id, 'go_focus', true );
			if ( ! empty( $class_a ) ) {
				$class_keys = array_keys( $class_a );
			}
			if ( ! empty( $class_a_choice ) && is_array( $class_a_choice ) &&
					! empty( $all_foci ) && is_array( $all_foci ) ) {

				if ( ! empty( $class_keys ) && ! empty( $user_focus ) ) {
					$class_intersect = array_intersect( $class_keys, $class_a_choice );
					if ( is_array( $user_focus ) ) {
						$user_focus_intersect = array_intersect( $user_focus, $all_foci );
					} else {
						$user_focus_intersect = in_array( $user_focus, $all_foci );
					}
					if ( ! empty( $class_intersect ) && ! empty( $user_focus_intersect ) ) {
						go_return_user_data( $user_id, $counter, $type );
						$counter++;
					}
				}
			} elseif ( ! empty( $class_a_choice ) && is_array( $class_a_choice ) ) {
				if ( ! empty( $class_keys ) ) {
					$class_intersect = array_intersect( $class_keys, $class_a_choice );
					if ( ! empty( $class_intersect ) ) {
						go_return_user_data( $user_id, $counter, $type );
						$counter++;
					}
				}
			} elseif ( ! empty( $all_foci ) && is_array( $all_foci ) ) {
				if ( ! empty( $user_focus ) ) {
					if ( is_array( $user_focus ) ) {
						$user_focus_intersect = array_intersect( $user_focus, $all_foci );
					} else {
						$user_focus_intersect = in_array( $user_focus, $all_foci );
					}
					if ( ! empty( $user_focus_intersect ) ) {
						go_return_user_data( $user_id, $counter, $type );
						$counter++;
					}
				}
			}
		}
	}
}

function go_stats_leaderboard() {
	global $wpdb;
	check_ajax_referer( 'go_stats_leaderboard_' );

	// prepares tab titles
	$xp_name = strtoupper( get_option( 'go_points_name' ) );
	$gold_name = strtoupper( get_option( 'go_currency_name' ) );
	$honor_name = strtoupper( get_option( 'go_bonus_currency_name' ) );
	$badge_name = strtoupper( get_option( 'go_badges_name' ) );

	$go_totals_table_name = "{$wpdb->prefix}go_loot";
	$class_a_choice = ( ! empty( $_POST['class_a_choice'] ) ? (array) $_POST['class_a_choice'] : array() );
	$focuses = ( ! empty( $_POST['focuses'] ) ? (array) $_POST['focuses'] : array() );
	$date = 'all';
	if ( ! empty( $_POST['date'] ) ) {
		if ( 'all' !== $_POST['date'] ) {
			$date = (int) $_POST['date'];
		}
	}
?>
<ul id='go_stats_leaderboard_list_points' class='go_stats_body_list go_stats_leaderboard_list'>
	<li class='go_stats_body_list_head'><?php echo $xp_name; ?></li>
	<?php 
	$counter = 1;
	$users_points = $wpdb->get_results( "SELECT uid FROM {$go_totals_table_name} ORDER BY CAST( points as signed ) DESC" );
	go_return_user_leaderboard( $users_points, $class_a_choice, $focuses, 'points', $counter )
	?>
</ul><ul id='go_stats_leaderboard_list_currency' class='go_stats_body_list go_stats_leaderboard_list'>
	<li class='go_stats_body_list_head'><?php echo $gold_name; ?></li>
	<?php
	$counter = 1;
	$users_currency = $wpdb->get_results( "SELECT uid FROM {$go_totals_table_name} ORDER BY CAST( currency as signed ) DESC" );
	go_return_user_leaderboard( $users_currency, $class_a_choice, $focuses, 'currency', $counter )
	?>
</ul><ul id='go_stats_leaderboard_list_bonus_currency' class='go_stats_body_list go_stats_leaderboard_list'>
	<li class='go_stats_body_list_head'><?php echo $honor_name; ?></li>
	<?php 
	$counter = 1;
	$users_bonus_currency = $wpdb->get_results( "SELECT uid FROM {$go_totals_table_name} ORDER BY CAST( bonus_currency as signed ) DESC" );
	go_return_user_leaderboard( $users_bonus_currency, $class_a_choice, $focuses, 'bonus_currency', $counter )
	?>
</ul><ul id='go_stats_leaderboard_list_badge_count' class='go_stats_body_list go_stats_leaderboard_list'>
	<li class='go_stats_body_list_head'><?php echo $badge_name; ?></li>
	<?php
	$counter = 1;
	$users_badge_count = $wpdb->get_results( "SELECT uid FROM {$go_totals_table_name} ORDER BY CAST( badge_count as signed ) DESC" );
	go_return_user_leaderboard( $users_badge_count, $class_a_choice, $focuses, 'badges', $counter )
	?>
</ul>
<?php
	die();
}

?>