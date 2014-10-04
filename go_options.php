<?php
if (is_admin()) {
	function go_opt_help($field, $title, $video_url = null) {
		echo '<a id="go_help_'.$field.'" class="go_opt_help" onclick="go_display_help_video(\''.$video_url.'\');" title="'.$title.'">?</a>';
		
	}
	function go_opt_style () {
		wp_register_style( 'go_opt_css', plugins_url( 'styles/go_options.css' , __FILE__ ), false, '1.0.0' );
		wp_enqueue_style( 'go_opt_css' );
	}
	add_action('admin_enqueue_scripts', 'go_opt_style');
	
	function go_options_accordion_help ($video_url = null, $explanation = null) {
		?>
        <a class='go_options_help_link' href='#' onclick='go_display_help_video("<?php echo $video_url; ?>")' tooltip='<?php echo $explanation;?>'>
			<div class='go_options_accordion_help_wrap'>
                <div class='go_options_accordion_help_text_wrap'>
                	<span class='go_options_accordion_help' href='javascript:;' onclick=''>?</span>
                </div>
            </div>
        </a>
        <?php	
	}
	
	function go_options_help ($video_url = null, $explanation = null, $help = true) {
		?>
    	<a class='go_options_help_link <?php if (!$help) { echo 'go_options_no_help'; }?>' href='javascript:;' onclick='go_display_help_video("<?php echo $video_url; ?>")' tooltip='<?php echo $explanation;?>'>
			<div class='go_options_help_wrap <?php if (!$help) { echo 'go_options_no_help'; }?>'>
                <div class='go_options_help_text_wrap <?php if (!$help) { echo 'go_options_no_help'; }?>'>
                    <span class='go_options_help <?php if (!$help) { echo 'go_options_no_help'; }?>' href='javascript:;' onclick=''>?</span>
                </div>
             </div>
         </a>
        <?php
	}
	
	function go_options_field ($title, $fields, $field_args, $video_url = null, $explanation = null) {
		?> 
        <div class='go_options'>
			<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo $title; go_options_help($video_url, $explanation);?></span></div>
			<?php
            for ($i = 1; $i <= $fields; $i++) {
                ?>
                    <?php if ($field_args[$i]=='go_video_width') { echo 'Width: ';} elseif ($field_args[$i]=='go_video_height') { echo 'Height: '; }?><input type='text' class='go_options_input' name='<?php echo $field_args[$i];?>' value='<?php echo get_option($field_args[$i])?>' /><?php if ($field_args[$i]=='go_video_width' || $field_args[$i]=='go_video_height') { echo 'px'; }?>
                <?php	
            }
            ?>
        </div>
        <?php
	}	
	
	function go_options_input ($title, $type, $name, $video_url, $explanation, $help = true, $reset = null) {
		?>
		<div class='go_options'>
			<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo $title; go_options_help($video_url, $explanation, $help); ?></span></div>
			<input type='<?php echo $type;?>' name='<?php echo $name; ?>' <?php if ($type == 'checkbox') { echo 'value="On"'; if (get_option($name) == 'On') { echo 'checked="checked"'; } } else { echo 'value="'.get_option($name).'"'; }?> class='go_options_additional_settings_input' <?php if ($reset) { echo "reset='{$reset}'"; }?>/>
		</div>
		<?php
	}
	
	function game_on_options () {
		wp_enqueue_script('go_options', plugin_dir_url(__FILE__).'scripts/go_options.js');
		if ($_GET['settings-updated']== true || $_GET['settings-updated']== 'true') {
			go_update_globals();
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
			wp_nonce_field('update-options'); 
			?>
			<div id='go_options_admin_email_wrap' class='go_options_wrap'>
			<?php
			go_options_input('Admin Email','text', 'go_admin_email', 'http://maclab.guhsd.net/go/video/options/adminEmail.mp4', 'IMPORTANT: Enter your email and click the Save Options button');
			?>
			</div>
             <div class='go_options_accordion_wrap' opt='0'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/namingConventions.mp4', 'Customize the names used for tasks, points, currency, etc.'); ?><div class='go_options_accordion'>Naming Conventions<div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
             	<div id='go_options_naming_conventions_wrap' class='go_options_wrap'>
             		<?php 
						go_options_field('Tasks', 2, array(1 => 'go_tasks_name', 2 => 'go_tasks_plural_name'), 'http://maclab.guhsd.net/go/video/options/tasks.mp4', 'Name your assignments'); 
						go_options_field('Stages', 5, array(1 => 'go_first_stage_name', 2 => 'go_second_stage_name', 3 => 'go_third_stage_name', 4 => 'go_fourth_stage_name', 5 => 'go_fifth_stage_name'), 'http://maclab.guhsd.net/go/video/options/stages.mp4', 'Name the steps within your assignments');
						go_options_field('Stage Buttons', 5, array(1 => 'go_abandon_stage_button', 2 => 'go_second_stage_button', 3 => 'go_third_stage_button', 4 => 'go_fourth_stage_button', 5 => 'go_fifth_stage_button'), 'http://maclab.guhsd.net/go/video/options/stageButtons.mp4', 'Name the buttons associated with each step in your assignments');
						go_options_field('Store', 1, array(1 => 'go_store_name'), 'http://maclab.guhsd.net/go/video/options/store.mp4', 'Name the store (independent of store page title)');
						go_options_field('Points', 3, array(1 => 'go_points_name', 2 => 'go_points_prefix', 3 => 'go_points_suffix'), 'http://maclab.guhsd.net/go/video/options/points.mp4', 'Name your points system (used for leveling)');
						go_options_field('Currency', 3, array(1 => 'go_currency_name', 2 => 'go_currency_prefix', 3 => 'go_currency_suffix'), 'http://maclab.guhsd.net/go/video/options/currency.mp4', 'Name your virtual currency (used to purchase goods in the store)');
						go_options_field('Bonus', 3, array(1 => 'go_bonus_currency_name', 2 => 'go_bonus_currency_prefix', 3 => 'go_bonus_currency_suffix'), 'http://maclab.guhsd.net/go/video/options/bonus.mp4', 'Name your bonus mechanism');
						go_options_field('Penalty', 3, array(1 => 'go_penalty_name', 2 => 'go_penalty_prefix', 3 => 'go_penalty_suffix'), 'http://maclab.guhsd.net/go/video/options/penalty.mp4', 'Name your penalty mechanism');
						go_options_field('Minutes', 3, array(1 => 'go_minutes_name', 2 => 'go_minutes_prefix', 3 => 'go_minutes_suffix'), 'http://maclab.guhsd.net/go/video/options/minutes.mp4', 'Name your timing mechanism');
						go_options_field('Ranks', 2, array(1 => 'go_level_names', 2 => 'go_level_plural_names'),'http://maclab.guhsd.net/go/video/options/ranks.mp4', 'Name your leveling system');
						go_options_field('Classifications', 3, array(1 => 'go_organization_name', 2 => 'go_class_a_name', 3 => 'go_class_b_name'),'http://maclab.guhsd.net/go/video/options/classifications.mp4', 'Name your classroom management system');
						go_options_field('Focus', 1, array(1 => 'go_focus_name'),'http://maclab.guhsd.net/go/video/options/focus.mp4', 'Name your sub-groups');
						go_options_field('Stats', 1, array(1 => 'go_stats_name'), 'http://maclab.guhsd.net/go/video/options/stats.mp4', 'Name your data display mechanism');
						go_options_field('Inventory', 1, array(1 => 'go_inventory_name'), 'http://maclab.guhsd.net/go/video/options/inventory.mp4', 'Name your purchased items category');
						go_options_field('Badges', 1, array(1 => 'go_badges_name'), 'http://maclab.guhsd.net/go/video/options/badges.mp4', 'Name your badging system');
						go_options_field('Leaderboard', 1, array(1 => 'go_leaderboard_name'), 'http://maclab.guhsd.net/go/video/options/leaderboard.mp4', 'Name your leaderboard');
						
					?>
                </div>
             <div class='go_options_accordion_wrap' opt='1'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/lootPresets.mp4', 'Customize rewards earned within your game');?><div class='go_options_accordion'>Loot Presets<div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
				<div id='go_options_loot_presets_wrap' class='go_options_wrap'>
					<?php
						$presets = get_option('go_presets',false);
						if ($presets) {
							$first = 1;
							foreach ($presets['name'] as $key => $name) {
								if ($first == 1) {
								?>
								<div id='go_options_preset_name_wrap'>
									<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Preset Name<?php go_options_help('http://maclab.guhsd.net/go/video/options/presetName.mp4', 'Name your assignments (by difficulty, time required, etc.)'); ?></span></div>
									<div id='go_options_preset_name'></div>
								</div>
								<?php }?>
									<input type='text' class='go_options_preset_name_input go_options_preset_input' name='go_presets[name][<?php echo $key;?>]' key='<?php echo $key;?>' value='<?php echo $name; ?>'/>
								<?php 
								$first++;
							}
							$first = 1;
							foreach ($presets['points'] as $key => $points) {
								if ($first == 1) {
								?>
								<div id='go_options_preset_points_wrap'>
									<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo get_option('go_points_name'); go_options_help('http://maclab.guhsd.net/go/video/options/presetPoints.mp4', 'Set your point values'); ?></span></div>
									<div id='go_options_preset_points'></div>
								</div>
								<?php 
								}
								foreach ($points as $point) {
								?>
									<input type='text' class='go_options_preset_points_input go_options_preset_input' name='go_presets[points][<?php echo $key;?>][]' key='<?php echo $key;?>' value='<?php echo $point; ?>'/>
								<?php 
								}
								$first++;
							}
							$first = 1;
							foreach ($presets['currency'] as $key => $currency) {
								if ($first == 1) {
								?>
								<div id='go_options_preset_currency_wrap'>
									<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo get_option('go_currency_name'); go_options_help('http://maclab.guhsd.net/go/video/options/presetCurrency.mp4', 'Set your currency values'); ?></span></div>
									<div id='go_options_preset_currency'></div>
								</div>
								<?php 
								}
								foreach ($currency as $cur) {
								?>
									<input type='text' class='go_options_preset_currency_input go_options_preset_input' name='go_presets[currency][<?php echo $key;?>][]' key='<?php echo $key;?>' value='<?php echo $cur; ?>'/>
								<?php 
								}
								$first++;
							}
						}
					
					?>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/addPreset.mp4','Add or subtract tiers');?></span></div>
						<button type="button"  class='go_add_preset'>+</button>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/resetPresets.mp4','Revert to default presets');?></span></div>
						<button type="button" id='go_reset_presets' class='go_options_button'>Reset Presets</button>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/savePresets.mp4','Save changes to loot presets');?></span></div>
						<button type="button" id='go_save_presets' class='go_options_button'>Save Presets</button>
					</div>
				</div>
			 <div class='go_options_accordion_wrap' opt='2'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/adminBar.mp4', 'Options for the Admin Bar ');?><div class='go_options_accordion'>Admin Bar<div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
				<div id='go_options_admin_bar_wrap' class='go_options_wrap'>
					<?php 
					go_options_input('Display', 'checkbox', 'go_admin_bar_display_switch', 'http://maclab.guhsd.net/go/video/options/adminBarDisplay.mp4', 'Show login option in admin bar (recommended)');
					go_options_input('User Redirect', 'checkbox', 'go_admin_bar_user_redirect', 'http://maclab.guhsd.net/go/video/options/userRedirect.mp4', 'Send users to home page after login (recommended)');
					go_options_input('Add Switch', 'checkbox', 'go_admin_bar_add_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddSwitch.mp4', 'Activate the manual scoring system (not recommended)');
					go_options_input('Minutes Only', 'checkbox', 'go_admin_bar_add_minutes_switch', 'http://maclab.guhsd.net/go/video/options/adminBarAddMinutesOnly.mp4', 'SAMPLE TEXT');
					?>
				</div>
			 <div class='go_options_accordion_wrap' opt='3'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/levels.mp4', 'Customize names, numbers, and award badges');?><div class='go_options_accordion'><?php echo go_return_options('go_level_plural_names');?><div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
				<div id='go_options_levels_wrap' class='go_options_wrap'>
					<?php
					$ranks = get_option('go_ranks',false);
					$rank_name = get_option('go_level_names', 'Level');
					$plural_rank_name = get_option('go_level_plural_names', 'Levels');
					if ($ranks !== false) {
						if (!empty($ranks['name'])) {
							?>
							<div id='go_options_level_names_wrap'>
								<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Preset Name <?php go_options_help('http://maclab.guhsd.net/go/video/options/levelName.mp4','Name your individual levels');?></span></div>
								<div id='go_options_level_names'>
							<?php				
							foreach ($ranks['name'] as $key => $name) {
								?>
									<input type='text' class='go_options_level_names_input' name='go_ranks[name][<?php echo $key;?>]' value='<?php echo $name; ?>'/>
								<?php
							}
							?>
								</div>
							</div>
							<?php
						}
						if (!empty($ranks['points'])) {
							$first = 1;
							foreach ($ranks['points'] as $key => $points) {
								if ($first == 1) {
									?>
									<div id='go_options_level_points_wrap'>
										<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo get_option('go_points_name'); go_options_help('http://maclab.guhsd.net/go/video/options/levelPoints.mp4','Establish thresholds for each level. IMPORTANT: The first level must be set to 0 (zero)');?></span></div>
										<div id='go_options_level_points'></div>
									</div>
									<?php 
								}
								
									?>
										<input type='text' class='go_options_level_points_input' name='go_ranks[points][<?php echo $key;?>]' value='<?php echo $points; ?>'/>
									<?php 
								
								$first++;
							}
						}
						if (!empty($ranks['badges'])) {
							$first = 1;
							foreach ($ranks['badges'] as $key => $badge) {
								if ($first == 1) {
								?>
								<div id='go_options_level_badges_wrap'>
									<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo go_return_options('go_badges_name'); go_options_help('http://maclab.guhsd.net/go/video/options/levelBadges.mp4','Award badges when players reach certain levels');?></span></div>
									<div id='go_options_level_badges'></div>
								</div>
								<?php 
								}
								
								?>
									<input type='text' class='go_options_level_badges_input' name='go_ranks[badges][<?php echo $key;?>]' value='<?php echo $badge; ?>'/>
								<?php 
								$first++;
							}
						}
					}
					?>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/addLevel.mp4','Add or subtract levels');?></span></div>
						<button type="button"  class='go_add_level'>+</button>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/resetLevels.mp4','Revert to default presets');?></span></div>
						<button type="button" id='go_reset_levels' class='go_options_button'>Reset <?php echo $plural_rank_name ?></button>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/saveLevels.mp4','Save changes to level presets');?></span></div>
						<button type="button" id='go_save_levels' class='go_options_button'>Save <?php echo $plural_rank_name ?></button>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/fixLevels.mp4','Repair errors caused by improper level settings');?></span></div>
						<button type="button" id='go_fix_levels' class='go_options_button'>Fix <?php echo $plural_rank_name ?></button>
					</div>
				</div>
			 <div class='go_options_accordion_wrap' opt='4'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/seatingChart.mp4', 'Customize user info to suit your needs');?><div class='go_options_accordion'><?php echo go_return_options('go_organization_name'); ?><div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
				<div id='go_options_seating_chart_wrap' class='go_options_wrap'>
					<?php
					$class_a = get_option('go_class_a');
					$period_name = get_option('go_class_a_name');
					$class_b = get_option('go_class_b');
					$computer_name = get_option('go_class_b_name');
					?>
					<div id='go_options_periods_wrap'>
							<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo $period_name; go_options_help('http://maclab.guhsd.net/go/video/options/periods.mp4','Name the first sorting method');?></span></div>							
							<div id='go_options_periods'>
					<?php
					foreach ($class_a as $key => $period) {
						?>
							<input type='text' class='go_options_period_input' name='go_class_a[]' value='<?php echo $period;?>'/>
						<?php
					}
					?>
						</div>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/addPeriod.mp4','Add or subtract first sorting settings');?></span></div>
						<button type="button"  class='go_add_period'>+</button>
					</div>
					<div id='go_options_computers_wrap'>
							<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php echo $computer_name; go_options_help('http://maclab.guhsd.net/go/video/options/computers.mp4','Name the second sorting method');?></span></div>
							<div id='go_options_computers'>
					<?php
					foreach ($class_b as $key => $computer) {
						?>
							<input type='text' class='go_options_computer_input' name='go_class_b[]' value='<?php echo $computer?>'/>
						<?php
					}
					?>
						</div>
					</div>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'><?php go_options_help('http://maclab.guhsd.net/go/video/options/addComputer.mp4','Add or subtract second sorting settings');?></span></div>
						<button type="button" class='go_add_computer'>+</button>
					</div>
				</div>
			 <div class='go_options_accordion_wrap' opt='5'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/profession.mp4', 'Optional grouping system');?><div class='go_options_accordion'><?php echo go_return_options('go_focus_name'); ?><div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
				<div id='go_options_profession_wrap' class='go_options_wrap'>
					<?php
					go_options_input('Setting', 'checkbox', 'go_focus_switch','http://maclab.guhsd.net/go/video/options/professionSwitch.mp4', 'Enable groups (off by default)');
					$focuses = get_option('go_focus');
					$first = 1;
					if (is_array($focuses) && !empty($focuses)) {
						foreach ($focuses as $focus) {
							if ($first == 1) {
							?>
							<div id='go_options_professions_names_wrap'>
								<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Name<?php go_options_help('http://maclab.guhsd.net/go/video/options/professionName.mp4','Name and add or subtract groups');?></span></div>
								<div id='go_options_professions'></div>
							</div>
							<?php
							}
							?>
								<input type='text' class='go_options_profession_input' name='go_focus[]' value='<?php echo $focus;?>'/>
							<?php
							$first++;
						}
					} else {
					?>
						<div id='go_options_professions_names_wrap'>
							<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Name<?php go_options_help('http://maclab.guhsd.net/go/video/options/professionName.mp4','Name and add or subtract groups');?></span></div>
							<div id='go_options_professions'></div>
						</div>
						<input type='text' class='go_options_profession_input' name='go_focus[]' value=''/>
					<?php
					}
					?>
				</div>
			 <div class='go_options_accordion_wrap' opt='6'><?php go_options_accordion_help('http://maclab.guhsd.net/go/video/options/additionalSettings.mp4', 'Extra custom settings and tools');?><div class='go_options_accordion'>Additional Settings<div class='go_triangle_container'><div class='go_options_triangle'></div></div></div></div>
				<div id='go_options_additional_settings_wrap' class='go_options_wrap'>
					<?php 
					go_options_field('Video Default', 2, array(1 => 'go_video_width', 2 => 'go_video_height'), 'http://maclab.guhsd.net/go/video/options/videoDefault.mp4', 'Set your default video size');
					go_options_input('Store Receipts', 'checkbox', 'go_store_receipt_switch', 'http://maclab.guhsd.net/go/video/options/storeReceipt.mp4', 'Receive email notification for each store purchase (off by default)');
					go_options_input('Full Student Name', 'checkbox', 'go_full_student_name_switch', 'http://maclab.guhsd.net/go/video/options/fullStudentName.mp4', 'Display only first name and last initial (default)');
					go_options_input(get_option('go_bonus_currency_name', 'Bonus'), 'checkbox', 'go_multiplier_switch', 'http://maclab.guhsd.net/go/video/options/multiplier.mp4', 'Enable bonus mechanism to boost rewards');
					go_options_input(get_option('go_bonus_currency_name', 'Bonus').' Threshold', 'text', 'go_multiplier_threshold', 'http://maclab.guhsd.net/go/video/options/multiplierThreshold.mp4', 'Number of bonus points required to boost rewards');
					go_options_input(go_return_options('go_penalty_name'), 'checkbox', 'go_penalty_switch', 'http://maclab.guhsd.net/go/video/options/penalty2.mp4', 'Enable penalty mechanism to reduce rewards');
					go_options_input(go_return_options('go_penalty_name').' Threshold', 'text', 'go_penalty_threshold', 'http://maclab.guhsd.net/go/video/options/penaltyThreshold.mp4', 'Number of penalty points required to reduce rewards');
					go_options_input('Multiplier %', 'text', 'go_multiplier_percentage', 'http://maclab.guhsd.net/go/video/options/multiplierPercentage.mp4', 'Percentage of rewards awarded or deducted at each threshold');
					go_options_input('Data Reset', 'checkbox', 'go_data_reset_switch', 'http://maclab.guhsd.net/go/video/options/dataReset.mp4', 'Clear all user data for specific categories DANGER!');
					go_options_input(go_return_options('go_points_name'), 'checkbox', 'go_data_reset_points', '', null, false, 'points');
					go_options_input(go_return_options('go_currency_name'), 'checkbox', 'go_data_reset_currency', '', null, false, 'currency');
					go_options_input(go_return_options('go_bonus_currency_name'), 'checkbox', 'go_data_reset_bonus_currency', '', null, false, 'bonus_currency');
					go_options_input(go_return_options('go_penalty_name'), 'checkbox', 'go_data_reset_penalty', '', null, false, 'penalty');
					go_options_input(go_return_options('go_minutes_name'), 'checkbox', 'go_data_reset_minutes', '', null, false, 'minutes');
					go_options_input(go_return_options('go_badges_name'), 'checkbox', 'go_data_reset_badges', '', null, false, 'badges');
					go_options_input('All', 'checkbox', 'go_data_reset_all', '', null, false);
					?>
					<div class='go_options'>
						<div class='go_options_field_title_wrap'><span class='go_options_field_title'>Reset <?php go_options_help('http://maclab.guhsd.net/go/video/options/dataReset2.mp4', 'Clear all user data for specific categories DANGER!');?></span></div>
						<button type="button" id='go_data_reset'>Go</button>
					</div>
				</div>
			<input type="submit" name="Submit" value="Save Options" />
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="go_tasks_name, go_tasks_plural_name, go_first_stage_name, go_second_stage_name, go_third_stage_name, go_fourth_stage_name, go_fifth_stage_name, go_abandon_stage_button, go_second_stage_button, go_third_stage_button, go_fourth_stage_button, go_fifth_stage_button, go_store_name, go_points_name, go_points_prefix, go_points_suffix, go_currency_name, go_currency_prefix, go_currency_suffix, go_bonus_currency_name, go_bonus_currency_prefix, go_bonus_currency_suffix, go_penalty_name, go_penalty_prefix, go_penalty_suffix, go_minutes_name, go_minutes_prefix, go_minutes_suffix, go_level_names, go_level_plural_names, go_organization_name, go_class_a_name, go_class_b_name, go_focus_name, go_stats_name, go_inventory_name, go_badges_name, go_leaderboard_name, go_presets, go_admin_bar_display_switch, go_admin_bar_user_redirect, go_admin_bar_add_switch, go_admin_bar_add_minutes_switch, go_ranks, go_class_a, go_class_b, go_focus_switch, go_focus, go_admin_email, go_video_width, go_video_height, go_store_receipt_switch, go_full_student_name_switch, go_multiplier_switch, go_multiplier_threshold, go_penalty_switch, go_penalty_threshold, go_multiplier_percentage, go_data_reset_switch"/>
        </form>
        </div>
        <?php	
	}

}

add_action('admin_menu', 'add_game_on_options');
function add_game_on_options() {  
    add_menu_page('Game On', 'Game On', 'manage_options', 'game-on-options.php','game_on_options', plugins_url( 'images/ico.png' , __FILE__ ), '81');  
	add_submenu_page( 'game-on-options.php', 'Options', 'Options', 'manage_options', 'game-on-options.php', 'game_on_options');

}

function go_reset_levels () {
	$rank_prefix = get_option('go_level_names');
	if (empty($rank_prefix)) {
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
	for($i = 1; $i <= 20; $i++){
		if($i <10){
			$ranks['name'][] = "{$rank_prefix} 0{$i}";
		}else{
			$ranks['name'][] = "{$rank_prefix} {$i}";
		}
		if ($i == 1) {
			$ranks['points'][0] = 0;
		} else {
			$ranks['points'][] = (15/2) * ($i + 18) * ($i - 1);
		}
		$ranks['badges'][] = '';
	}
	update_option('go_ranks',$ranks);
	echo json_encode($ranks);
	die();
}

function go_save_levels() {
	$go_level_names = $_POST['go_level_names'];
	$go_level_points = $_POST['go_level_points'];
	$go_level_badges = $_POST['go_level_badges'];
	$ranks = array(
		'name' => $go_level_names,
		'points' => $go_level_points,
		'badges' => $go_level_badges
	);
	update_option('go_ranks',$ranks);
	die();
}	

function go_fix_levels() {
	global $default_role;
	global $wpdb;
	$role = get_option('go_role',$default_role);
	$ranks = get_option('go_ranks');
	$uids = $wpdb->get_results("
		SELECT user_id
		FROM {$wpdb->usermeta}
		WHERE meta_key =  '{$wpdb->prefix}capabilities'
		AND (meta_value LIKE  '%{$role}%' or meta_value LIKE '%administrator%')
	");
	foreach ($uids as $uid) {
		foreach ($uid as $user_id) {
			$current_points = go_return_points($user_id);
			current($ranks['points']);
			while ($current_points >= current($ranks['points'])) {
				next($ranks['points']);
				ini_set('max_execution_time', 300);
			}
			$next_rank_points = current($ranks['points']);
			$next_rank = $ranks['name'][key($ranks['points'])];
			$rank_points = prev($ranks['points']);
			$new_rank = $ranks['name'][key($ranks['points'])];
			$new_rank_array = array(
				array($new_rank, $rank_points),
				array($next_rank, $next_rank_points)
			);
			update_user_meta($user_id, 'go_rank', $new_rank_array);
		} 
	}
	die();
}

function go_update_user_sc_data () {
	$old_class_a_array = $_POST['old_class_a'];
	$old_class_b_array = $_POST['old_class_b'];
	$new_class_a_array = $_POST['new_class_a'];
	$new_class_b_array = $_POST['new_class_b'];
	
	$class_a_diff = array_diff($old_class_a_array, $new_class_a_array);
	$class_b_diff = array_diff($old_class_b_array, $new_class_b_array);
	
	$users = get_users();
	if (!empty($class_a_diff) || !empty($class_b_diff)){
		foreach ($users as $user) {
			$user_id = $user->ID;
			$user_class = get_user_meta($user_id, 'go_classifications', true);
			if (!empty($user_class)) {
				foreach ($user_class as $class_a => $class_b) {
					$new_class_a = $new_class_a_array[array_search($class_a, $old_class_a_array)];
					$new_class_b = $new_class_b_array[array_search($class_b, $old_class_b_array)];
					$new_class = array($new_class_a => $new_class_b);
					update_user_meta($user_id, 'go_classifications', $new_class);
				}
			}
		}
	}
	die();
}

function go_focus_save() {
	global $wpdb;
	$array = $_POST['focus_array'];
	$terms = $wpdb->get_results("SELECT * FROM $wpdb->terms", ARRAY_A);
	$term_names = array();
	
	foreach ($array as $key=>$value) {
		if ($value == '') {
			unset($array[$key]);
		}
		if (!term_exists($value, 'task_focus_categories')) {
			wp_insert_term($value, 'task_focus_categories');
		}
	} 
	
	foreach ($terms as $term) {
		if ($term['name'] != 'Uncategorized') {
			array_push($term_names, $term['name']);
		}
	}
	$delete_terms = array_diff($term_names, $array);
	foreach ($delete_terms as $term) {
		$term_id = $wpdb->get_var("SELECT `term_id` FROM $wpdb->terms WHERE `name`='".$term."'");
		wp_delete_term($term_id, 'task_focus_categories');
	}
	die();
}

function go_get_all_focuses() {
	if (get_option('go_focus')) {
		$all_focuses = get_option('go_focus');
	}
	$all_focuses_sorted = array();
	if ($all_focuses) {
		foreach ($all_focuses as $focus ) {
			 $all_focuses_sorted[] = array('name' => $focus , 'value' => $focus);
		 }
	}
	return $all_focuses_sorted;
}

add_action('wp_ajax_go_new_user_focus', 'go_new_user_focus');
function go_new_user_focus() {
	$new_user_focus = $_POST['new_user_focus'];
	$user_id = $_POST['user_id'];
	update_user_meta($user_id, 'go_focus', $new_user_focus);
	die();	
}

function go_presets_reset() {
	global $wpdb;
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
				5,5,10,30,30
			),
			array(
				5,5,20,60,60
			),
			array(
				5,5,40,120,120
			),
			array(
				5,5,70,210,210
			),
			array(
				5,5,110,330,330
			)
		),
		'currency' => array(
			array(
				0,0,3,9,9
			),
			array(
				0,0,6,18,18
			),
			array(
				0,0,12,36,36
			),
			array(
				0,0,21,63,63
			),
			array(
				0,0,33,99,99
			)
		)
	);
	update_option('go_presets',$presets);
	echo json_encode($presets);
	die();
}

function go_presets_save() {
	global $wpdb;
	$preset_name = $_POST['go_preset_name'];
	$preset_points = $_POST['go_preset_points'];
	$preset_currency = $_POST['go_preset_currency'];
	$preset_array = array(
		'name' => $preset_name,
		'points' => $preset_points,
		'currency' => $preset_currency
	);
	update_option('go_presets',$preset_array);
	die();
}

function go_reset_data() {
	global $wpdb;
	$go_table_name = "{$wpdb->prefix}go";
	$go_table_totals_name = "{$wpdb->prefix}go_totals";
	$reset_data = (array)$_POST['reset_data'];
	$reset_all = $_POST['reset_all'];
	$users = get_users('orderby=ID');
	$ranks = get_option('go_ranks');
	if (in_array('points', $reset_data)) {
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
		foreach($users as $user){
			update_user_meta($user->ID, 'go_rank', $erase_level);
		}
	}
	if (in_array('badges', $reset_data)) {
		unset($reset_data[array_search('badges', $reset_data)]);
		$reset_data[] = 'badge_count';
		foreach ($users as $user) {
			update_user_meta($user->ID, 'go_badges', '');
		}
	}
	if ($reset_all === 'true') {
		$wpdb->query("TRUNCATE TABLE {$go_table_name}");
	} else {
		$erase_list = implode(',', $reset_data);
		$query = "DELETE FROM {$go_table_name} WHERE {$erase_list} IS NOT NULL ".(in_array('points', $reset_data) && !in_array('currency', $reset_data) ? 'AND status != -1' : (in_array('currency', $reset_data) && !in_array('points', $reset_data)? 'AND status = -1': ''));
		$wpdb->query($query);
	}
	$erase_update = "SET ".implode('=0,', $reset_data)."=0 WHERE uid IS NOT NULL";
	$wpdb->query("UPDATE {$go_table_totals_name} ".$erase_update);
	die();
}


add_action( 'show_user_profile', 'go_extra_profile_fields' );
add_action( 'edit_user_profile', 'go_extra_profile_fields' );

function go_extra_profile_fields($user) { ?>

	<h3><?php echo go_return_options('go_class_a_name').' and '.go_return_options('go_class_b_name'); ?></h3>

	<table id="go_user_form_table">
<th><?php echo go_return_options('go_class_a_name'); ?></th><th><?php echo go_return_options('go_class_b_name'); ?></th>
<tbody id="go_user_form_table_body">

<?php
 if (get_user_meta($user->ID, 'go_classifications',true)){ 

foreach (get_user_meta($user->ID, 'go_classifications',true) as $keyu => $valueu) {
?>
		<tr>
			<td>
			<?php $class_a = get_option('go_class_a', false);
			if($class_a){
				?><select name="class_a_user[]"><option name="<?php echo $keyu; ?>" value="<?php echo $keyu; ?>"><?php echo $keyu; ?></option>
				<option value="go_remove">Remove</option>
				<?php
				foreach($class_a as $key => $value){
					echo '<option name="'.$value.'" value="'.$value.'">'.$value.'</option>';
					}
				  ?></select><?php
				} ?>	
			</td> 
            
            
            <td>
			<?php $class_b = get_option('go_class_b', false);
			if($class_b){
				?><select name="class_b_user[]"><option name="<?php echo $valueu; ?>" value="<?php echo $valueu; ?>"><?php echo $valueu; ?></option>
				<option value="go_remove">Remove</option>
				<?php
				foreach($class_b as $key => $value){
					echo '<option name="'.$value.'" value="'.$value.'">'.$value.'</option>';
					}
				  ?></select><?php
				} ?>	
			</td> 
            
            
		</tr> <?php }} ?> </tbody>
        <tr> 
        <td><button onclick="go_add_class();" type="button">+</button></td>
	</table>
	<?php 
		if (get_option('go_focus_switch', true) == 'On') {
	?>
		<h3>User <?php echo go_return_options('go_focus_name');?></h3>
		<?php 
			echo go_display_user_focuses($user->ID);
		}
    ?>
    <script type="text/javascript" language="javascript">
		function go_add_class () {
			var ajaxurl = "<?php global $wpdb;
			echo admin_url( 'admin-ajax.php' ) ; ?>";
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: { 
					action: 'go_user_option_add',
					go_clipboard_class_a_choice: jQuery('#go_clipboard_class_a_choice').val()
				},
				success: function(html) {
					jQuery('#go_user_form_table_body').append(html);
				}
			});
		}
    </script>
<?php



 }


add_action( 'personal_options_update', 'go_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'go_save_extra_profile_fields' );

function go_user_option_add() {
	?> 
	
    <tr>

			<td>
			<?php $class_a = get_option('go_class_a', false);
			if($class_a){
				?><select name="class_a_user[]">
				<option value="go_remove">Remove</option>
				<?php
				foreach($class_a as $key => $value){
					echo '<option name="'.$value.'" value="'.$value.'">'.$value.'</option>';
					}
				  ?></select><?php
				} ?>	
			</td> 
            
            <td>
			<?php $class_b = get_option('go_class_b', false);
			if($class_b){
				?><select name="class_b_user[]">
				<option value="go_remove">Remove</option>
				<?php
				foreach($class_b as $key => $value){
					echo '<option name="'.$value.'" value="'.$value.'">'.$value.'</option>';
					}
				  ?></select><?php
				} ?>	
			</td> 
		</tr>
       
	<?php

	}
	
function go_save_extra_profile_fields($user_id) {
	if (isset($_POST['class_a_user'])) {
		foreach ($_POST['class_a_user'] as $key=>$value) {
			if ($value != 'go_remove') {
				$class_a = $value;
				$class_b = $_POST['class_b_user'][$key];
				$class[$class_a] = $class_b;
			}
		}
		update_user_meta( $user_id, 'go_classifications', $class );
	}
}	

function go_update_globals() {
	global $wpdb;
	$file_name = $real_file = plugin_dir_path( __FILE__ ) . '/' . 'go_definitions.php';
	$array = explode(',','go_tasks_name, go_tasks_plural_name, go_first_stage_name, go_second_stage_name, go_third_stage_name, go_fourth_stage_name, go_fifth_stage_name, go_abandon_stage_button, go_second_stage_button, go_third_stage_button, go_fourth_stage_button, go_fifth_stage_button, go_store_name, go_points_name, go_points_prefix, go_points_suffix, go_currency_name, go_currency_prefix, go_currency_suffix, go_bonus_currency_name, go_bonus_currency_prefix, go_bonus_currency_suffix, go_penalty_name, go_penalty_prefix, go_penalty_suffix, go_minutes_name, go_minutes_prefix, go_minutes_suffix, go_level_names, go_level_plural_names, go_organization_name, go_class_a_name, go_class_b_name, go_focus_name, go_stats_name, go_inventory_name, go_badges_name, go_leaderboard_name, go_presets, go_admin_bar_display_switch, go_admin_bar_user_redirect, go_admin_bar_add_switch, go_admin_bar_add_minutes_switch, go_ranks, go_class_a, go_class_b, go_focus_switch, go_focus, go_admin_email, go_video_width, go_video_height, go_store_receipt_switch, go_full_student_name_switch, go_multiplier_switch, go_multiplier_threshold, go_penalty_switch, go_penalty_threshold, go_multiplier_percentage, go_data_reset_switch');
	foreach ($array as $key=>$value) {
		$value = trim($value);
		$content = get_option($value);
		if (is_array($content)) {
			$content = serialize($content);
		}
		$string .= 'define("'.$value.'",\''.$content.'\',TRUE);';
	}

	file_put_contents ( $file_name, '<?php '.$string.' ?>' );
}
?>