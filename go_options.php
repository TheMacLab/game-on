<?php

if ( is_admin() ) {
	function go_opt_help( $field, $title, $video_url = null ) {
		echo "<a id='go_help_{$field}' class='go_opt_help' onclick='go_display_help_video(\'{$video_url}\' );' title='{$title}'>?</a>";
		
	}
	
	function go_options_accordion_help( $video_url = null, $explanation = null ) {
		?>
		<a class='go_options_help_link' href='#' onclick='go_display_help_video( "<?php echo $video_url; ?>" )' tooltip='<?php echo $explanation; ?>'>
			<div class='go_options_accordion_help_wrap'>
				<div class='go_options_accordion_help_text_wrap'>
					<span class='go_options_accordion_help' href='javascript:;' onclick=''>?</span>
				</div>
			</div>
		</a>
		<?php	
	}
	
	function go_options_help( $video_url = null, $explanation = null, $help = true ) {
		?>
		<a class='go_options_help_link <?php if ( ! $help ) { echo 'go_options_no_help'; } ?>' href='javascript:;' onclick='go_display_help_video( "<?php echo $video_url; ?>" )' tooltip='<?php echo $explanation; ?>'>
			<div class='go_options_help_wrap <?php if ( ! $help ) { echo 'go_options_no_help'; } ?>'>
				<div class='go_options_help_text_wrap <?php if ( ! $help ) { echo 'go_options_no_help'; } ?>'>
					<span class='go_options_help <?php if ( ! $help ) { echo 'go_options_no_help'; } ?>' href='javascript:;' onclick=''>?</span>
				</div>
			 </div>
		 </a>
		<?php
	}
	
	function go_options_field( $title, $fields, $field_args, $video_url = null, $explanation = null ) {
		?> 
		<div class='go_options'>
			<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo $title; go_options_help( $video_url, $explanation ); ?></span></div>
			<?php
			for ( $i = 1; $i <= $fields; $i++ ) {
				?>
					<?php if ( $field_args[ $i ]=='go_video_width' ) { echo '<span id="go_options_video_dim">Width: </span>';} elseif ( $field_args[ $i ]=='go_video_height' ) { echo '<span id="go_options_video_dim">Height: </span>'; } ?><input type='text' class='go_options_input' name='<?php echo $field_args[ $i ]; ?>' value='<?php echo get_option( $field_args[ $i ] )?>' /><?php if ( $field_args[ $i ] == 'go_video_width' || $field_args[ $i ] =='go_video_height' ) { echo '<span id="go_options_video_dim">px</span>'; } ?>
				<?php	
			}
			?>
		</div>
		<?php
	}	
	
	function go_options_input( $title, $type, $name, $video_url, $explanation, $help = true, $reset = null ) {
		?>
		<div class='go_options'>
			<div class='go_options_field_title_wrap'><span class='go_options_field_title'>
			<?php 
				echo $title; 
				go_options_help( $video_url, $explanation, $help ); 
			?>
			</span></div><input type='<?php echo $type; ?>' name='<?php echo $name; ?>' 
			<?php 
			if ( $type == 'checkbox' ) { 
				echo 'value="On"'; 
				if ( get_option( $name ) == 'On' ) { 
					echo 'checked="checked"';
				 }
			} 
			else { 
				echo 'value="'.get_option( $name ).'"'; 
			} ?> 
			class='go_options_additional_settings_input' <?php if ( $reset ) { echo "reset='{$reset}'"; } ?>/>
		</div>
		<?php
	}
	
	function game_on_options() {
		if ( ! empty( $_GET['settings-updated'] ) && true === $_GET['settings-updated'] || ! empty( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'] ) {
			echo "
			<script type='text/javascript'>
				window.location = '".admin_url()."/?page=game-on-options.php'
			</script>";
		}

		?>
		<div class="wrap go_wrap">
		<h2>Game On Options</h2>
		<a href='http://maclab.guhsd.net/game-on' target='_blank'>Documentation Page</a>
		<form method="post" action="options.php" id="go_options_form">
			<?php 
			wp_nonce_field( 'update-options' ); 
			?>
			<div id='go_options_admin_email_wrap' class='go_options_wrap'>
			<?php
			go_options_input( 'Admin Email','text', 'go_admin_email', 'http://maclab.guhsd.net/go/video/options/adminEmail.mp4', 'IMPORTANT: Enter your email and click the Save Options button' );
			?>
			</div>
			<div class='go_options_accordion_wrap' opt='0'>
				<?php 
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/namingConventions.mp4', 
					'Customize the names used for tasks, points, currency, etc.'
				);
				?>
				<div class='go_options_accordion'>
					Naming Conventions
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_naming_conventions_wrap' class='go_options_wrap'>
				<?php 
					go_options_field( 'Tasks', 2, array( 1 => 'go_tasks_name', 2 => 'go_tasks_plural_name' ), 'http://maclab.guhsd.net/go/video/options/tasks.mp4', 'Name your assignments' ); 
					go_options_field( 'Stages', 5, array( 1 => 'go_first_stage_name', 2 => 'go_second_stage_name', 3 => 'go_third_stage_name', 4 => 'go_fourth_stage_name', 5 => 'go_fifth_stage_name' ), 'http://maclab.guhsd.net/go/video/options/stages.mp4', 'Name the steps within your assignments' );
					go_options_field( 'Stage Buttons', 5, array( 1 => 'go_abandon_stage_button', 2 => 'go_second_stage_button', 3 => 'go_third_stage_button', 4 => 'go_fourth_stage_button', 5 => 'go_fifth_stage_button' ), 'http://maclab.guhsd.net/go/video/options/stageButtons.mp4', 'Name the buttons associated with each step in your assignments' );
					go_options_field( 'Store', 1, array( 1 => 'go_store_name' ), 'http://maclab.guhsd.net/go/video/options/store.mp4', 'Name the store (independent of store page title)' );
					go_options_field( 'Bonus Loot', 1, array( 1 => 'go_bonus_loot_name' ), 'http://maclab.guhsd.net/go/video/options/bonusLoot.mp4', 'Name the rare loot rewarded for task mastery' );
					go_options_field( 'Points', 3, array( 1 => 'go_points_name', 2 => 'go_points_prefix', 3 => 'go_points_suffix' ), 'http://maclab.guhsd.net/go/video/options/points.mp4', 'Name your points system (used for leveling)' );
					go_options_field( 'Currency', 3, array( 1 => 'go_currency_name', 2 => 'go_currency_prefix', 3 => 'go_currency_suffix' ), 'http://maclab.guhsd.net/go/video/options/currency.mp4', 'Name your virtual currency (used to purchase goods in the store)' );
					go_options_field( 'Bonus', 3, array( 1 => 'go_bonus_currency_name', 2 => 'go_bonus_currency_prefix', 3 => 'go_bonus_currency_suffix' ), 'http://maclab.guhsd.net/go/video/options/bonus.mp4', 'Name your bonus mechanism' );
					go_options_field( 'Penalty', 3, array( 1 => 'go_penalty_name', 2 => 'go_penalty_prefix', 3 => 'go_penalty_suffix' ), 'http://maclab.guhsd.net/go/video/options/penalty.mp4', 'Name your penalty mechanism' );
					go_options_field( 'Minutes', 3, array( 1 => 'go_minutes_name', 2 => 'go_minutes_prefix', 3 => 'go_minutes_suffix' ), 'http://maclab.guhsd.net/go/video/options/minutes.mp4', 'Name your timing mechanism' );
					go_options_field( 'Ranks', 2, array( 1 => 'go_level_names', 2 => 'go_level_plural_names' ),'http://maclab.guhsd.net/go/video/options/ranks.mp4', 'Name your leveling system' );
					go_options_field( 'Prestige', 1, array( 1 => 'go_prestige_name' ), '', 'Name the state of being at max level' );
					go_options_field( 'Classifications', 3, array( 1 => 'go_organization_name', 2 => 'go_class_a_name', 3 => 'go_class_b_name' ),'http://maclab.guhsd.net/go/video/options/classifications.mp4', 'Name your classroom management system' );
					go_options_field( 'Focus', 1, array( 1 => 'go_focus_name' ),'http://maclab.guhsd.net/go/video/options/focus.mp4', 'Name your sub-groups' );
					go_options_field( 'Stats', 1, array( 1 => 'go_stats_name' ), 'http://maclab.guhsd.net/go/video/options/stats.mp4', 'Name your data display mechanism' );
					go_options_field( 'Inventory', 1, array( 1 => 'go_inventory_name' ), 'http://maclab.guhsd.net/go/video/options/inventory.mp4', 'Name your purchased items category' );
					go_options_field( 'Badges', 1, array( 1 => 'go_badges_name' ), 'http://maclab.guhsd.net/go/video/options/badges.mp4', 'Name your badging system' );
					go_options_field( 'Leaderboard', 1, array( 1 => 'go_leaderboard_name' ), 'http://maclab.guhsd.net/go/video/options/leaderboard.mp4', 'Name your leaderboard' );
					go_options_field( 'Bonus Task', 1, array( 1 => 'go_bonus_task' ), 'http://maclab.guhsd.net/go/video/options/leaderboard.mp4', 'Bonus tasks label on map' );
					go_options_field( 'Bonus Stage', 1, array( 1 => 'go_bonus_stage' ), 'http://maclab.guhsd.net/go/video/options/leaderboard.mp4', 'Bounus stage label on map' );
				?>
			</div>
			<div class='go_options_accordion_wrap' opt='1'>
				<?php
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/lootPresets.mp4',
					'Customize rewards earned within your game'
				);
				?>
				<div class='go_options_accordion'>
					Loot Presets
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_loot_presets_wrap' class='go_options_wrap'>
				<?php
					$presets = get_option( 'go_presets',false );
					if ( $presets ) {
						$first = 1;
						foreach ( $presets['name'] as $key => $name ) {
							if ( $first == 1 ) {
							?>
							<div id='go_options_preset_name_wrap'>
								<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Preset Name<?php go_options_help( 'http://maclab.guhsd.net/go/video/options/presetName.mp4', 'Name your assignments (by difficulty, time required, etc.)' ); ?></span></div>
								<div id='go_options_preset_name'></div>
							</div>
							<?php } ?>
								<input type='text' class='go_options_preset_name_input go_options_preset_input' name='go_presets[name][<?php echo $key; ?>]' key='<?php echo $key; ?>' value='<?php echo $name; ?>'/>
							<?php 
							$first++;
						}
						$first = 1;
						foreach ( $presets['points'] as $key => $points ) {
							if ( $first == 1 ) {
							?>
							<div id='go_options_preset_points_wrap'>
								<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo get_option( 'go_points_name' ); go_options_help( 'http://maclab.guhsd.net/go/video/options/presetPoints.mp4', 'Set your point values' ); ?></span></div>
								<div id='go_options_preset_points'></div>
							</div>
							<?php 
							}
							foreach ( $points as $point ) {
							?>
								<input type='text' class='go_options_preset_points_input go_options_preset_input' name='go_presets[points][<?php echo $key; ?>][]' key='<?php echo $key; ?>' value='<?php echo $point; ?>'/>
							<?php 
							}
							$first++;
						}
						$first = 1;
						foreach ( $presets['currency'] as $key => $currency ) {
							if ( $first == 1 ) {
							?>
							<div id='go_options_preset_currency_wrap'>
								<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo get_option( 'go_currency_name' ); go_options_help( 'http://maclab.guhsd.net/go/video/options/presetCurrency.mp4', 'Set your currency values' ); ?></span></div>
								<div id='go_options_preset_currency'></div>
							</div>
							<?php 
							}
							foreach ( $currency as $cur ) {
							?>
								<input type='text' class='go_options_preset_currency_input go_options_preset_input' name='go_presets[currency][<?php echo $key; ?>][]' key='<?php echo $key; ?>' value='<?php echo $cur; ?>'/>
							<?php 
							}
							$first++;
						}
					}
				
				?>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/addPreset.mp4',
								'Add or subtract tiers'
							);
							?>
						</span>
					</div>
					<button type="button" class='go_add_preset'>+</button>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/resetPresets.mp4',
								'Revert to default presets'
							);
							?>
						</span>
					</div>
					<button type="button" id='go_reset_presets' class='go_options_button'>Reset Presets</button>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/savePresets.mp4',
								'Save changes to loot presets'
							);
							?>
						</span>
					</div>
					<button type="button" id='go_save_presets' class='go_options_button'>Save Presets</button>
				</div>
			</div>
			<div class='go_options_accordion_wrap' opt='2'>
				<?php
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/adminBar.mp4',
					'Options for the Admin Bar'
				);
				?>
				<div class='go_options_accordion'>
					Admin Bar
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_admin_bar_wrap' class='go_options_wrap'>
				<?php 
				//if (! is_plugin_active( 'wp-term-order/wp-term-order.php' ) ) {echo "<div class=opt_accordian_message_red> The map feature requires <a href=https://wordpress.org/plugins/wp-term-order/ target=_blank>WP Term Order</a> to be installed and activated.</div>";};
				go_options_input( 'Add Map', 'checkbox', 'go_map_switch', 'https://www.youtube.com/embed/rPQiirHBjt4?autoplay=1&rel=0', 'Add the Map Feature. ' );
				go_options_input( 'Add Store', 'checkbox', 'go_store_switch', 'https://www.youtube.com/embed/m2IAYdNZoM4?autoplay=1&rel=0', 'Add the Store Feature. ' );
				go_options_input( 'Show Searchbox', 'checkbox', 'go_search_switch', 'https://www.youtube.com/embed/rPgaDoFn1qs?autoplay=1&rel=0', 'Toggles the search box in the top bar on and off.' );
				go_options_input( 'Show Dashboard', 'checkbox', 'go_dashboard_switch', 'https://www.youtube.com/embed/wgill7wm45Q?autoplay=1&rel=0', 'Toggles the dashboard icon in the top bar on and off for non admin.' );
				go_options_input( 'Always Display Bar', 'checkbox', 'go_admin_bar_display_switch', 'http://maclab.guhsd.net/go/video/options/adminBarDisplay.mp4', 'Show login option in admin bar (recommended)' );
				go_options_input( 'User Redirect', 'checkbox', 'go_admin_bar_user_redirect', 'http://maclab.guhsd.net/go/video/options/userRedirect.mp4', 'Send users to home page after login (recommended)' );
				go_options_input( 'Redirect Location','text', 'go_user_redirect_location', 'https://www.youtube.com/embed/-8fK8PAgkD8?autoplay=1&rel=0', 'Leave blank to send users to homepage.' );
				go_options_input( 'Add Switch', 'checkbox', 'go_admin_bar_add_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddSwitch.mp4', 'Activate the manual scoring system (not recommended)' );
				?>
				<div id ="admin_bar_catagories"><strong>Admin</strong>
				<?php
				go_options_input( go_return_options( 'go_points_name' ), 'checkbox', 'go_admin_bar_add_points_switch', '', 'Enable ' . go_return_options( 'go_points_name' ) . ' in the add bar' );
				go_options_input( go_return_options( 'go_currency_name' ), 'checkbox', 'go_admin_bar_add_currency_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_currency_name' ) . ' in the add bar' );
				go_options_input( go_return_options( 'go_bonus_currency_name' ), 'checkbox', 'go_admin_bar_add_bonus_currency_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_bonus_currency_name' ) . ' in the add bar'  );
				go_options_input( go_return_options( 'go_penalty_name' ), 'checkbox', 'go_admin_bar_add_penalty_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_penalty_name' ) . ' in the add bar'  );
				go_options_input( go_return_options( 'go_minutes_name' ), 'checkbox', 'go_admin_bar_add_minutes_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_minutes_name' ) . ' in the add bar'  );
				?>
				<strong>User</strong></div>
				<?php
				go_options_input( go_return_options( 'go_points_name' ), 'checkbox', 'go_bar_add_points_switch', '', 'Enable ' . go_return_options( 'go_points_name' ) . ' in the add bar' );
				go_options_input( go_return_options( 'go_currency_name' ), 'checkbox', 'go_bar_add_currency_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_currency_name' ) . ' in the add bar' );
				go_options_input( go_return_options( 'go_bonus_currency_name' ), 'checkbox', 'go_bar_add_bonus_currency_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_bonus_currency_name' ) . ' in the add bar'  );
				go_options_input( go_return_options( 'go_penalty_name' ), 'checkbox', 'go_bar_add_penalty_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_penalty_name' ) . ' in the add bar'  );
				go_options_input( go_return_options( 'go_minutes_name' ), 'checkbox', 'go_bar_add_minutes_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'Enable ' . go_return_options( 'go_minutes_name' ) . ' in the add bar'  );
				?>
			</div>
			<div class='go_options_accordion_wrap' opt='3'>
				<?php
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/levels.mp4',
					'Customize names, numbers, and award badges'
				);
				?>
				<div class='go_options_accordion'>
					<?php echo go_return_options( 'go_level_plural_names' ); ?>
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_levels_wrap' class='go_options_wrap'>
				<?php
				$ranks = get_option( 'go_ranks',false );
				$rank_name = get_option( 'go_level_names', 'Level' );
				$plural_rank_name = get_option( 'go_level_plural_names', 'Levels' );
				if ( $ranks !== false ) {
					if ( ! empty( $ranks['name'] ) ) {
						?>
						<div id='go_options_level_names_wrap'>
							<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Preset Name <?php go_options_help( 'http://maclab.guhsd.net/go/video/options/levelName.mp4','Name your individual levels' ); ?></span></div>
							<div id='go_options_level_names'>
						<?php				
						foreach ( $ranks['name'] as $key => $name ) {
							?>
								<input type='text' class='go_options_level_names_input' name='go_ranks[name][<?php echo $key; ?>]' value='<?php echo $name; ?>'/>
							<?php
						}
						?>
							</div>
						</div>
						<?php
					}
					if ( ! empty( $ranks['points'] ) ) {
						$first = 1;
						foreach ( $ranks['points'] as $key => $points ) {
							if ( $first == 1 ) {
								?>
								<div id='go_options_level_points_wrap'>
									<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo get_option( 'go_points_name' ); go_options_help( 'http://maclab.guhsd.net/go/video/options/levelPoints.mp4','Establish thresholds for each level. IMPORTANT: The first level must be set to 0 (zero)' ); ?></span></div>
									<div id='go_options_level_points'></div>
								</div>
								<?php 
							}
							
								?>
									<input type='text' class='go_options_level_points_input' name='go_ranks[points][<?php echo $key; ?>]' value='<?php echo $points; ?>'/>
								<?php 
							
							$first++;
						}
					}
					if ( ! empty( $ranks['badges'] ) ) {
						$first = 1;
						foreach ( $ranks['badges'] as $key => $badge ) {
							if ( $first == 1 ) {
							?>
							<div id='go_options_level_badges_wrap'>
								<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo go_return_options( 'go_badges_name' ); go_options_help( 'http://maclab.guhsd.net/go/video/options/levelBadges.mp4','Award badges when players reach certain levels' ); ?></span></div>
								<div id='go_options_level_badges'></div>
							</div>
							<?php 
							}
							
							?>
								<input type='text' class='go_options_level_badges_input' name='go_ranks[badges][<?php echo $key; ?>]' value='<?php echo $badge; ?>'/>
							<?php 
							$first++;
						}
					}
				}
				?>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/addLevel.mp4',
								'Add or subtract levels' );
							?>
						</span>
					</div>
					<button type="button"  class='go_add_level'>+</button>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/resetLevels.mp4',
								'Revert to default presets' );
							?>
						</span>
					</div>
					<button type="button" id='go_reset_levels' class='go_options_button'>Reset <?php echo $plural_rank_name; ?></button>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/saveLevels.mp4',
								'Save changes to level presets' );
							?>
						</span>
					</div>
					<button type="button" id='go_save_levels' class='go_options_button'>Save <?php echo $plural_rank_name; ?></button>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/fixLevels.mp4',
								'Repair errors caused by improper level settings' );
							?>
						</span>
					</div>
					<button type="button" id='go_fix_levels' class='go_options_button'>Fix <?php echo $plural_rank_name; ?></button>
				</div>
			</div>
			<div class='go_options_accordion_wrap' opt='4'>
				<?php
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/seatingChart.mp4',
					'Customize user info to suit your needs'
				);
				?>
				<div class='go_options_accordion'>
					<?php echo go_return_options( 'go_organization_name' ); ?>
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_seating_chart_wrap' class='go_options_wrap'>
				<?php
				$class_a = get_option( 'go_class_a' );
				$period_name = get_option( 'go_class_a_name' );
				$class_b = get_option( 'go_class_b' );
				$computer_name = get_option( 'go_class_b_name' );
				?>
				<div id='go_options_periods_wrap'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php 
							echo $period_name; 
							go_options_help( 
								'http://maclab.guhsd.net/go/video/options/periods.mp4',
								'Name the first sorting method' 
							); 
							?>
						</span>
					</div>							
					<div id='go_options_periods'>
						<?php
						foreach ( $class_a as $key => $period ) {
							?>
								<input type='text' class='go_options_period_input' name='go_class_a[]' value='<?php echo $period; ?>'/>
							<?php
						}
						?>
					</div>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/addPeriod.mp4',
								'Add or subtract first sorting settings'
							);
							?>
						</span>
					</div>
					<button type="button"  class='go_add_period'>+</button>
				</div>
				<div id='go_options_computers_wrap'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							echo $computer_name;
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/computers.mp4',
								'Name the second sorting method'
							);
							?>
						</span>
					</div>
					<div id='go_options_computers'>
						<?php
						foreach ( $class_b as $key => $computer ) {
							?>
								<input type='text' class='go_options_computer_input' name='go_class_b[]' value='<?php echo $computer?>'/>
							<?php
						}
						?>
					</div>
				</div>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title'>
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/addComputer.mp4',
								'Add or subtract second sorting settings'
							);
							?>
						</span>
					</div>
					<button type="button" class='go_add_computer'>+</button>
				</div>
			</div>
			<div class='go_options_accordion_wrap' opt='5'>
				<?php
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/profession.mp4',
					'Optional grouping system'
				);
				?>
				<div class='go_options_accordion'>
					<?php
					echo go_return_options( 'go_focus_name' );
					?>
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_profession_wrap' class='go_options_wrap'>
				<?php
				go_options_input(
					'Setting',
					'checkbox',
					'go_focus_switch',
					'http://maclab.guhsd.net/go/video/options/professionSwitch.mp4',
					'Enable groups (off by default)'
				);
				$focuses = get_option( 'go_focus' );
				$first = 1;
				if ( is_array( $focuses ) && ! empty( $focuses ) ) {
					foreach ( $focuses as $focus ) {
						if ( $first == 1 ) {
						?>
						<div id='go_options_professions_names_wrap'>
							<div class='go_options_field_title_wrap'>
								<span class='go_options_field_title'>
									Name
									<?php
									go_options_help(
										'http://maclab.guhsd.net/go/video/options/professionName.mp4',
										'Name and add or subtract groups'
									);
									?>
								</span>
							</div>
							<div id='go_options_professions'></div>
						</div>
						<?php
						}
						?>
							<input type='text' class='go_options_profession_input' name='go_focus[]' value='<?php echo esc_attr( $focus ); ?>'/>
						<?php
						$first++;
					}
				} else {
				?>
					<div id='go_options_professions_names_wrap'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Name<?php go_options_help( 'http://maclab.guhsd.net/go/video/options/professionName.mp4','Name and add or subtract groups' ); ?></span></div>
						<div id='go_options_professions'></div>
					</div>
					<input type='text' class='go_options_profession_input' name='go_focus[]' value=''/>
				<?php
				}
				?>
			</div>
			<div class='go_options_accordion_wrap' opt='6'>
				<?php
				go_options_accordion_help(
					'https://www.youtube.com/embed/6iZsyDUt98w?autoplay=1&rel=0',
					'Video Options'
				);
				?>
				<div class='go_options_accordion'>
					Video Settings
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_video_wrap' class='go_options_wrap'>
			<?php 
				echo "<p class=accordian_sub_heading>Video Options</p>";
				echo "<div class=opt_accordian_message><a href=https://codex.wordpress.org/Embeds target=_blank>See a list of sites that wordpress supports embedding content from.</a></div>";
				go_options_input( 'Use Oembed', 'checkbox', 'go_oembed_switch', 'https://www.youtube.com/embed/4i52U_vK17s?autoplay=1&rel=0', 'Use Wordpress default embed (recommended)' );
				go_options_input( 'Use FitVids', 'checkbox', 'go_fitvids_switch', 'https://www.youtube.com/embed/70jBMWxvhZ8?autoplay=1&rel=0', 'Make iFrame Videos Responsive (recommended)' );
				go_options_input( 'Max Width','text', 'go_fitvids_maxwidth', 'https://www.youtube.com/embed/Y6IDoxj_sLo?autoplay=1&rel=0', 'Set a max width for the fitvids' );
				//if ( ! is_plugin_active( 'wp-featherlight/wp-featherlight.php' ) ) {echo "<div class=opt_accordian_message_red> Please install and activate the plugin <a href=https://wordpress.org/plugins/wp-featherlight/ target=_blank>WP FeatherLight</a>.  This will give you the option to open your videos in a lightbox.</div>";}
				go_options_input( 'Use Lightbox', 'checkbox', 'go_lightbox_switch', 'https://www.youtube.com/embed/Y6IDoxj_sLo?autoplay=1&rel=0', 'Add Lightbox to Videos (recommended)' );
				/*
				if ( is_plugin_active( 'fitvids-for-wordpress/fitvids-for-wordpress.php' ) ) {
  					//plugin is activated
  					go_options_input( 'Max Width','text', 'go_fitvids_maxwidth', '#need_video', 'Set a max width for the fitvids' );
  					if ( is_plugin_active( 'wp-featherlight/wp-featherlight.php' ) ) {
  						go_options_input( 'Use Lightbox', 'checkbox', 'go_lightbox_switch', '#need_video', 'Add Lightbox to Videos (recommended)' );
					}
					else {
					echo "<div class=opt_accordian_message_red> Please install and activate the plugin <a href=https://wordpress.org/plugins/wp-featherlight/ target=_blank>WP FeatherLight</a>.  This will give you the option to open your videos in a lightbox.</div>";
					};
				}
				else {
				echo "<div class=opt_accordian_message_red>Please install and activate the plugins <a href=https://wordpress.org/plugins/fitvids-for-wordpress/ target=_blank>FitVids for Wordpress</a> and <a href=https://wordpress.org/plugins/wp-featherlight/ target=_blank>WP FeatherLight</a>.  This will give you the options to set a max width and open your videos in a lightbox.</div>";
				};
				*/
				echo "<p class=accordian_sub_heading>Legacy Lightbox </p>";
				echo "<div class=opt_accordian_message>Only use if you were using this feature in an old version of Game On</div>";
				go_options_field( 'Video Default', 2, array( 1 => 'go_video_width', 2 => 'go_video_height' ), 'http://maclab.guhsd.net/go/video/options/videoDefault.mp4', 'Set your default video size' );
				
			?>
				
			</div>
			<div class='go_options_accordion_wrap' opt='7'>
				<?php
				go_options_accordion_help(
					'http://maclab.guhsd.net/go/video/options/additionalSettings.mp4',
					'Extra custom settings and tools'
				);
				?>
				<div class='go_options_accordion'>
					Additional Settings
					<div class='go_triangle_container'>
						<div class='go_options_triangle'></div>
					</div>
				</div>
			</div>
			<div id='go_options_additional_settings_wrap' class='go_options_wrap'>
				<?php 
				go_options_input( 'From Email Address', 'text', 'go_email_from', 'http://maclab.guhsd.net/go/video/options/emailFrom.mp4', 'Set the "from" address of the store and file-uploader emails (default: "no-reply@go.net" )' );
				go_options_input( 'Store Receipts', 'checkbox', 'go_store_receipt_switch', 'http://maclab.guhsd.net/go/video/options/storeReceipt.mp4', 'Receive email notification for each store purchase (off by default)' );
				go_options_input( 'Full Student Name', 'checkbox', 'go_full_student_name_switch', 'http://maclab.guhsd.net/go/video/options/fullStudentName.mp4', 'Display only first name and last initial (default)' );
				go_options_input(get_option( 'go_bonus_currency_name', 'Bonus' ), 'checkbox', 'go_multiplier_switch', 'http://maclab.guhsd.net/go/video/options/multiplier.mp4', 'Enable bonus mechanism to boost rewards' );
				go_options_input(get_option( 'go_bonus_currency_name', 'Bonus' ).' Threshold', 'text', 'go_multiplier_threshold', 'http://maclab.guhsd.net/go/video/options/multiplierThreshold.mp4', 'Number of bonus points required to boost rewards' );
				go_options_input(go_return_options( 'go_penalty_name' ), 'checkbox', 'go_penalty_switch', 'http://maclab.guhsd.net/go/video/options/penalty2.mp4', 'Enable penalty mechanism to reduce rewards' );
				go_options_input(go_return_options( 'go_penalty_name' ).' Threshold', 'text', 'go_penalty_threshold', 'http://maclab.guhsd.net/go/video/options/penaltyThreshold.mp4', 'Number of penalty points required to reduce rewards' );
				go_options_input( 'Multiplier %', 'text', 'go_multiplier_percentage', 'http://maclab.guhsd.net/go/video/options/multiplierPercentage.mp4', 'Percentage of rewards awarded or deducted at each threshold' );
				go_options_input( '<span class="go_error_red">Data Reset</span>', 'checkbox', 'go_data_reset_switch', 'http://maclab.guhsd.net/go/video/options/dataReset.mp4', 'DANGER! Clears all user data for the specified categories. Includes ' . ucfirst( get_option( 'go_tasks_plural_name', 'Quests' ) ) . '.' );
				go_options_input(go_return_options( 'go_points_name' ), 'checkbox', 'go_data_reset_points', '', null, false, 'points' );
				go_options_input(go_return_options( 'go_currency_name' ), 'checkbox', 'go_data_reset_currency', '', null, false, 'currency' );
				go_options_input(go_return_options( 'go_bonus_currency_name' ), 'checkbox', 'go_data_reset_bonus_currency', '', null, false, 'bonus_currency' );
				go_options_input(go_return_options( 'go_penalty_name' ), 'checkbox', 'go_data_reset_penalty', '', null, false, 'penalty' );
				go_options_input(go_return_options( 'go_minutes_name' ), 'checkbox', 'go_data_reset_minutes', '', null, false, 'minutes' );
				go_options_input(go_return_options( 'go_badges_name' ), 'checkbox', 'go_data_reset_badges', '', null, false, 'badges' );
				go_options_input( 'All', 'checkbox', 'go_data_reset_all', 'http://maclab.guhsd.net/go/video/options/dataReset.mp4', 'Includes ' . ucfirst( get_option( 'go_tasks_plural_name', 'Quests' ) ) . ' AND Store Items.' );
				?>
				<div class='go_options'>
					<div class='go_options_field_title_wrap'>
						<span class='go_options_field_title go_error_red'>
							Reset
							<?php
							go_options_help(
								'http://maclab.guhsd.net/go/video/options/dataReset2.mp4',
								'DANGER! Clears all user data for the specified categories. Includes ' . ucfirst( get_option( 'go_tasks_plural_name', 'Quests' ) ) . '.'
							);
							?>
						</span>
					</div>
					<button type="button" id='go_data_reset'>Go</button>
				</div>
			</div>
			<input type="submit" name="Submit" value="Save Options" />
			<input type="hidden" name="action" value="update" />
			<!--// this input should mimic the values in the array in the go_update_definitions() function  -->
			<input type="hidden" name="page_options" value="go_tasks_name, go_tasks_plural_name, 
				go_first_stage_name, go_second_stage_name, go_third_stage_name, go_fourth_stage_name, 
				go_fifth_stage_name, go_abandon_stage_button, go_second_stage_button, 
				go_third_stage_button, go_fourth_stage_button, go_fifth_stage_button, go_store_name, 
				go_bonus_loot_name, go_points_name, go_points_prefix, go_points_suffix, 
				go_currency_name, go_currency_prefix, go_currency_suffix, go_bonus_currency_name, 
				go_bonus_currency_prefix, go_bonus_currency_suffix, go_penalty_name, go_penalty_prefix, 
				go_penalty_suffix, go_minutes_name, go_minutes_prefix, go_minutes_suffix, go_level_names, 
				go_prestige_name, go_level_plural_names, go_organization_name, go_class_a_name, 
				go_class_b_name, go_focus_name, go_stats_name, go_inventory_name, go_badges_name, 
				go_leaderboard_name, go_bonus_task, go_bonus_stage, go_presets, go_dashboard_switch, go_admin_bar_display_switch, go_admin_bar_user_redirect, go_user_redirect_location, 
				go_admin_bar_add_switch, go_admin_bar_add_minutes_switch, go_admin_bar_add_points_switch, 
				go_admin_bar_add_currency_switch, go_admin_bar_add_bonus_currency_switch, 
				go_admin_bar_add_penalty_switch, go_bar_add_minutes_switch, go_bar_add_points_switch, 
				go_bar_add_currency_switch, go_bar_add_bonus_currency_switch, go_bar_add_penalty_switch, 
				go_ranks, go_class_a, go_class_b, go_focus_switch, go_focus, go_admin_email, 
				go_video_width, go_video_height, go_email_from, go_store_receipt_switch, 
				go_full_student_name_switch, go_multiplier_switch, go_multiplier_threshold, 
				go_penalty_switch, go_penalty_threshold, go_multiplier_percentage, go_data_reset_switch, 
				go_oembed_switch, go_fitvids_switch, go_lightbox_switch, go_fitvids_maxwidth, go_store_switch, go_map_switch, go_search_switch,"/>
		</form>
		</div>
		<?php
	}

}

function add_game_on_options() {
	add_menu_page(
		'Game On',
		'Game On',
		'manage_options',
		'game-on-options.php',
		'game_on_options',
		plugins_url( 'images/ico.png' , __FILE__ ),
		'81'
	);  
	add_submenu_page(
		'game-on-options.php',
		'Options',
		'Options',
		'manage_options',
		'game-on-options.php',
		'game_on_options'
	);
}

function go_reset_levels() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_reset_levels_' . get_current_user_id() );

	$rank_prefix = get_option( 'go_level_names' );
	if ( empty( $rank_prefix ) ) {
		$rank_prefix = 'Level';
	}
	$ranks = array(
		'name' => array(
			
		),
		'points' => array(
		
		),
		'badges' => array(
			
		)
	);
	for( $i = 1; $i <= 20; $i++ ) {
		if ( $i < 10 ) {
			$ranks['name'][] = "{$rank_prefix} 0{$i}";
		}else{
			$ranks['name'][] = "{$rank_prefix} {$i}";
		}
		if ( $i == 1 ) {
			$ranks['points'][0] = 0;
		} else {
			$ranks['points'][] = ( 15 / 2 ) * ( $i + 18 ) * ( $i - 1 );
		}
		$ranks['badges'][] = '';
	}
	update_option( 'go_ranks', $ranks );
	echo json_encode( $ranks );
	die();
}

function go_save_levels() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_save_levels_' . get_current_user_id() );

	$go_level_names  = ( ! empty( $_POST['go_level_names'] )  ? (array) $_POST['go_level_names'] : array() );
	$go_level_points = ( ! empty( $_POST['go_level_points'] ) ? (array) $_POST['go_level_points'] : array() );
	$go_level_badges = ( ! empty( $_POST['go_level_badges'] ) ? (array) $_POST['go_level_badges'] : array() );
	$ranks = array(
		'name' => $go_level_names,
		'points' => $go_level_points,
		'badges' => $go_level_badges,
	);
	update_option( 'go_ranks', $ranks );
	die();
}

function go_fix_levels() {
	global $default_role;
	global $wpdb;

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_fix_levels_' . get_current_user_id() );

	$role = get_option( 'go_role', $default_role );
	$ranks = get_option( 'go_ranks' );
	$uids = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT user_id
			FROM {$wpdb->usermeta}
			WHERE meta_key = %s AND ( meta_value LIKE %s OR meta_value LIKE %s )",
			"{$wpdb->prefix}capabilities",
			"%{$role}%",
			'%administrator%'
		)
	);
	foreach ( $uids as $uid ) {
		foreach ( $uid as $user_id ) {
			$current_points = go_return_points( $user_id );
			current( $ranks['points'] );
			while ( $current_points >= current( $ranks['points'] ) ) {
				next( $ranks['points'] );
				ini_set( 'max_execution_time', 300);
			}
			$next_rank_points = current( $ranks['points'] );
			$next_rank = $ranks['name'][key( $ranks['points'] ) ];
			$rank_points = prev( $ranks['points'] );
			$new_rank = $ranks['name'][key( $ranks['points'] ) ];
			$new_rank_array = array(
				array( $new_rank, $rank_points ),
				array( $next_rank, $next_rank_points )
			);
			update_user_meta( $user_id, 'go_rank', $new_rank_array );
		} 
	}
	die();
}

function go_update_user_sc_data() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_update_user_sc_data_' . get_current_user_id() );

	$old_class_a_array = (array) $_POST['old_class_a'];
	$old_class_b_array = (array) $_POST['old_class_b'];
	
	$new_class_a_array = (array) $_POST['new_class_a'];
	$new_class_b_array = (array) $_POST['new_class_b'];
	
	// get the new class settings, by comparing the new and old arrays
	$class_a_diff = array_diff( $old_class_a_array, $new_class_a_array );

	// get the new computer/seating settings, by comparing the new and old arrays
	$class_b_diff = array_diff( $old_class_b_array, $new_class_b_array );
	
	$users = get_users();
	if ( ! empty( $class_a_diff ) || ! empty( $class_b_diff ) ) {
		foreach ( $users as $user ) {
			$user_id = $user->ID;
			$user_class = get_user_meta( $user_id, 'go_classifications', true );
			if ( ! empty( $user_class ) ) {
				foreach ( $user_class as $class_a => $class_b ) {
					$new_class_a = $new_class_a_array[ array_search( $class_a, $old_class_a_array ) ];
					$new_class_b = $new_class_b_array[ array_search( $class_b, $old_class_b_array ) ];
					$new_class = array( $new_class_a => $new_class_b );
					update_user_meta( $user_id, 'go_classifications', $new_class );
				}
			}
		}
	}
	die();
}

function go_focus_save() {
	global $wpdb;

	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_focus_save_' . get_current_user_id() );

	$array = array_values( array_filter( (array) $_POST['focus_array'] ) );
	$terms = $wpdb->get_results(
		"SELECT * FROM {$wpdb->terms}",
		ARRAY_A
	);
	$term_names = array();
	
	foreach ( $array as $key => $value ) {
		if ( $value == '' ) {
			unset( $array[ $key ] );
		}
		if ( ! term_exists( $value, 'task_focus_categories' ) ) {
			wp_insert_term( $value, 'task_focus_categories' );
		}
		if ( ! term_exists( $value, 'store_focus_categories' ) ) {
			wp_insert_term( $value, 'store_focus_categories' );	
		}
	} 
	
	foreach ( $terms as $term ) {
		if ( $term['name'] != 'Uncategorized' ) {
			array_push( $term_names, $term['name'] );
		}
	}
	$delete_terms = array_diff( $term_names, $array );
	foreach ( $delete_terms as $term ) {
		$term_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT term_id FROM {$wpdb->terms} WHERE name = %s", sanitize_text_field( $term )
			)
		);
		wp_delete_term( $term_id, 'task_focus_categories' );
		wp_delete_term( $term_id, 'store_focus_categories' );
	}
	die();
}

function go_presets_reset() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_presets_reset_' . get_current_user_id() );

	$presets = array(
		'name' => array(
			'Tier 1',
			'Tier 2',
			'Tier 3',
			'Tier 4',
			'Tier 5',
		),
		'points' => array(
			array(
				5, 5, 10, 30, 30
			),
			array(
				5, 5, 20, 60, 60
			),
			array(
				5, 5, 40, 120, 120
			),
			array(
				5, 5, 70, 210, 210
			),
			array(
				5, 5, 110, 330, 330
			)
		),
		'currency' => array(
			array(
				0, 0, 3, 9, 9
			),
			array(
				0, 0, 6, 18, 18
			),
			array(
				0, 0, 12, 36, 36
			),
			array(
				0, 0, 21, 63, 63
			),
			array(
				0, 0, 33, 99, 99
			)
		)
	);
	update_option( 'go_presets', $presets );
	echo json_encode( $presets );
	die();
}

function go_presets_save() {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( -1 );
	}
	check_ajax_referer( 'go_presets_save_' . get_current_user_id() );

	$preset_name = (array) $_POST['go_preset_name'];
	$preset_points = (array) $_POST['go_preset_points'];
	$preset_currency = (array) $_POST['go_preset_currency'];
	
	$preset_array = array(
		'name' => $preset_name,
		'points' => $preset_points,
		'currency' => $preset_currency
	);
	update_option( 'go_presets', $preset_array );
	die();
}

function go_reset_data() {
	if ( ! current_user_can( 'manage_options' ) ) {
		go_error_log( 'Only admins with correct permissions can resest data' );
		die( -1 );
	}
	check_ajax_referer( 'go_reset_data_' . get_current_user_id() );

	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	$go_table_totals_name = "{$wpdb->prefix}go_totals";
	$reset_data = ( ! empty( $_POST['reset_data'] ) ? (array) $_POST['reset_data'] : array() );
	if ( empty( $reset_data ) ) {
		die();
	}

	$reset_all = ( ! empty( $_POST['reset_all'] ) ? (boolean) $_POST['reset_all'] : false );
	$default_reset_keys = array(
		'points',
		'currency',
		'bonus_currency',
		'penalty',
		'minutes',
		'badge_count',
	);

	$users = get_users( 'orderby=ID' );
	$ranks = get_option( 'go_ranks' );
	if ( in_array( 'points', $reset_data ) ) {
		$erase_level = array( 
			array(
				$ranks['name'][0],
				$ranks['points'][0]
			),
			array(
				$ranks['name'][1],
				$ranks['points'][1]
			)
		);
		foreach( $users as $user) {
			update_user_meta( $user->ID, 'go_rank', $erase_level );
		}
	}
	if ( in_array( 'badges', $reset_data ) ) {
		unset( $reset_data[ array_search( 'badges', $reset_data ) ] );
		$reset_data[] = 'badge_count';
		foreach ( $users as $user ) {
			update_user_meta( $user->ID, 'go_badges', '' );
		}
	}

	// removes all task timestamps
	$get_users_args = array(
		'meta_key' => 'go_task_timestamps',
		'fields'   => 'ID',
	);
	$users_with_timestamps = get_users( $get_users_args );

	// for every user that has timestamps stored in their meta data, delete the timestamps
	foreach ( $users_with_timestamps as $user_id ) {
		delete_user_meta( $user_id, $get_users_args['meta_key'] );
	}

	// the check for $reset_data equaling 6 will have to be changed if more data reset options are added
	if ( $reset_all === 'true' && count( $reset_data ) === 6 ) {
		$wpdb->query( "TRUNCATE TABLE {$go_table_name}" );
	} else {
		$reset_data_go = $reset_data;
		$erase_list = '';
		if ( in_array( 'badge_count', $reset_data_go ) ) {
			$badge_count_loc = array_search( 'badge_count', $reset_data_go );
			unset( $reset_data_go[ $badge_count_loc ] );
		}

		if ( ! empty( $reset_data_go ) && count( $reset_data_go ) > 1 ) {
			$erase_list = sanitize_text_field( implode( ' IS NOT NULL AND ', $reset_data_go ) );
		} else {
			$erase_list = sanitize_text_field( $reset_data_go[0] );
		}
		if ( ! empty( $erase_list ) ) {
			$query = "DELETE FROM {$go_table_name} WHERE %s IS NOT NULL ";
			if ( in_array( 'points', $reset_data ) && ! in_array( 'currency', $reset_data ) ) {
				$query .= 'AND status != -1';
			} else if ( ! in_array( 'points', $reset_data ) && in_array( 'currency', $reset_data ) ) {
				$query .= 'AND status = -1';
			}
			$wpdb->query( $wpdb->prepare( $query, $erase_list ) );
		}
	}

	$reset_data_str = '';
	foreach ( $default_reset_keys as $index => $key_name ) {
		if ( in_array( $key_name, $reset_data ) ) {
			$key_value_pair = "{$key_name} = 0";
			if ( '' !== $reset_data_str ) {
				$reset_data_str .= ", {$key_value_pair}";
			} else {
				$reset_data_str .= "{$key_value_pair}";
			}
		}
	}

	/**
	 * This query doesn't make use of the `$wpdb->prepare()` function, because the list of columns to
	 * set was being wrapped in single quotes. This resulted in an invalid query and a DB error.
	 * Instead, the AJAX (considered user-input) is compared with a list of expected values. If
	 * the arrays match up, then the expected value is used in the query, not the user input. With
	 * no data to sanitize, the query doesn't need to be passed through the prepare function.
	 */
	$wpdb->query( "UPDATE {$go_table_totals_name} SET {$reset_data_str} WHERE uid IS NOT NULL" );

	die();
}

function go_user_classif_opt_elems( $arr, $target_val = '' ) {
	$str = '<option value="go_remove">Remove</option>';
	for ( $i = 0; $i < count( $arr ); $i++ ) {
		$value = $arr[ $i ];
		$selected = '';
		if ( ! empty( $target_val ) && $value === $target_val ) {
			$selected = 'selected';
		}

		$str .= "<option value='{$value}' {$selected}>{$value}</option>";
	}

	return $str;
}

function go_user_render_classif_options( $user_id ) {
	$nonce            = wp_create_nonce( 'go_user_option_add_class_' . $user_id );
	$avail_periods    = get_option( 'go_class_a'     , false );
	$avail_computers  = get_option( 'go_class_b'     , false );
	$go_period_name   = get_option( 'go_class_a_name', 'Period' );
	$go_computer_name = get_option( 'go_class_b_name', 'Computer' );
	$user_classif     = get_user_meta( $user_id, 'go_classifications', true );

	$rows         = '';
	$script_block = '';
	$option_title = "{$go_period_name} and {$go_computer_name}";

	// a script needs to be enqueued with the following, this should only be temporary
	$script_block = sprintf(
		'<script type="text/javascript" language="javascript">
			jQuery( document ).ready( function() {
				jQuery( "#go_button_add_classif_row" ).click( go_add_class );
			});
			function go_add_class() {
				jQuery.ajax({
					type: "post",
					url: MyAjax.ajaxurl,
					data: {
						_ajax_nonce: "%s",
						action: "go_user_option_add",
						user_id: %d,
						go_clipboard_class_a_choice: jQuery( "#go_clipboard_class_a_choice" ).val()
					},
					success: function( res ) {
						if ( -1 !== res ) {
							jQuery( "#go_user_classif_form_body" ).append( res );
						}
					}
				});
			}
		</script>',
		$nonce,
		$user_id
	);

	if ( ! empty( $user_classif ) ) {
		foreach ( $user_classif as $period => $computer ) {
			$period_opt_elems = go_user_classif_opt_elems( $avail_periods, $period );
			$computer_opt_elems = go_user_classif_opt_elems( $avail_computers, $computer );

			$rows .= sprintf(
				'<tr>'.
					'<td>'.
						'<select name="class_a_user[]">%s</select>'.
					'</td>'.
					'<td>'.
						'<select name="class_b_user[]">%s</select>'.
					'</td>'.
				'</tr>',
				$period_opt_elems,
				$computer_opt_elems
			);
		}
	}

	printf(
		'<h3>%1$s and %2$s</h3>'.
		'<table class="go_user_form_table">'.
			'<th>%1$s</th><th>%2$s</th>'.
			'<tbody id="go_user_classif_form_body">%3$s</tbody>'.
			'<tr>'.
				'<td>'.
					'<input id="go_button_add_classif_row" class="go_button_add_field" '.
						'type="button" value="+"/>'.
				'</td>'.
			'</tr>'.
		'</table>'.
		'%4$s',
		$go_period_name,
		$go_computer_name,
		$rows,
		$script_block
	);
}
add_action( 'go_user_render_extra_fields', 'go_user_render_classif_options', 10, 1 );

function go_user_render_focus_name( $user_id ) {
	if ( get_option( 'go_focus_switch', true ) == 'On' ) {
		$focus_name = get_option( 'go_focus_name', 'Profession' );

		echo "<h3>User {$focus_name}</h3>".go_display_user_focuses( $user_id );
	}
}
add_action( 'go_user_render_extra_fields', 'go_user_render_focus_name', 10, 1 );

function go_extra_profile_fields( $user ) {
	do_action( 'go_user_render_extra_fields', $user->ID );
}

function go_user_option_add() {

	if ( empty( $_POST['user_id'] ) ) {
		die( -1 );
	}

	$user_id = (int) $_POST['user_id'];
	check_ajax_referer( 'go_user_option_add_class_' . $user_id );

	?>
	<tr>
		<td>
			<?php
			$class_a = get_option( 'go_class_a', false );
			if ( $class_a ) {
				?>
				<select name="class_a_user[]">
					<option value="go_remove">Remove</option>
					<?php
					foreach ( $class_a as $key => $value ) {
						echo "<option name='{$value}' value='{$value}'>{$value}</option>";
					}
					?>
				</select>
			<?php
			}
			?>
		</td>
		<td>
			<?php
			$class_b = get_option( 'go_class_b', false );
			if ( $class_b ) {
				?>
				<select name="class_b_user[]">
					<option value="go_remove">Remove</option>
					<?php
					foreach ( $class_b as $key => $value ) {
						echo "<option name='{$value}' value='{$value}'>{$value}</option>";
					}
					?>
				</select>
			<?php
			}
			?>
		</td>
	</tr>
	<?php
	die();
}

function go_save_extra_profile_fields( $user_id ) {
	$class = array();
	if ( isset( $_POST['class_a_user'] ) ) {
		foreach ( $_POST['class_a_user'] as $key => $value ) {
			if ( $value != 'go_remove' ) {
				if ( ! isset( $_POST['class_b_user'] ) || ! isset( $_POST['class_b_user'][ $key ] ) ) {
					return;
				}
				$class_a = $value;
				$class_b = sanitize_text_field( $_POST['class_b_user'][ $key ] );
				$class[ $class_a ] = $class_b;
			}
		}
		update_user_meta( $user_id, 'go_classifications', $class );
	}
}

?>