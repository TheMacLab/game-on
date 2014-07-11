<?php
if (is_admin()) {
function go_opt_help($field, $title, $video_url = null) {
	echo '<a id="go_help_'.$field.'" class="go_opt_help" onclick="go_display_help_video(\''.$video_url.'\');" title="'.$title.'">?</a>';
	
}
function go_opt_style() {
    wp_register_style( 'go_opt_css', plugins_url( 'css/options.css' , __FILE__ ), false, '1.0.0' );
    wp_enqueue_style( 'go_opt_css' );
}
add_action('admin_enqueue_scripts', 'go_opt_style');

function go_sub_option($explanation_name, $explanation, $title, $field_name, $option_name, $explanation_question, $video_url = null){ ?>
	    <div class="pa">
            	<?php go_opt_help($explanation_name,$explanation, $video_url); ?> 
            	<strong><?php echo $title; ?>:</strong><br />  
                <input type="text" name="<?php echo $field_name; ?>" size="45" value="<?php echo get_option($option_name); ?>" /><br />
                <i><?php echo $explanation_question; ?></i> 
            </div> <?php
            
	}
	
function go_sub_option_radio($explanation_name, $explanation, $title, $field_name, $option_name, $explanation_question, $video_url = null){ ?>
	    <div class="pa">
            	<?php go_opt_help($explanation_name,$explanation, $video_url); ?> 
            	<strong><?php echo $title; ?>:</strong><br />  
                   On:<input type="radio" <?php if(get_option($option_name) == 'On'){echo 'checked="checked"';} ?> name="<?php echo $option_name; ?>" size="45" value="On" /><br />
                Off:<input type="radio" <?php if(get_option($option_name) == 'Off'){echo 'checked="checked"';} ?> name="<?php echo $option_name; ?>" size="45" value="Off" /><br />
                <i><?php echo $explanation_question; ?></i> 
            </div> <?php
            
	}	
add_action('go_sub_option','go_sub_option');
add_action('go_sub_option_radio','go_sub_option_radio');
function game_on_options() { 
if($_GET['settings-updated']== true || $_GET['settings-updated']== 'true'){
	go_update_globals();
	// wp_redirect("admin.php?page=game-on-options.php");
	 echo '<script type="text/javascript">
<!--
window.location = "'.admin_url().'/?page=game-on-options.php"
//-->
</script>';
	}
?>  
    <div class="go-wrap">  
        <h2>Game On Options </h2><?php echo 'Click ?\'s for videos.<a href="javascript:;" onclick="go_display_help_video(\'http://maclab.guhsd.net/go/video/options/optionsIntro.mp4\');">Please watch this video first</a>'; ?>
        <form method="post" action="options.php">  
            <?php wp_nonce_field('update-options') ?> 
            <div id="tsk" class="opt-box">
            <h3>Task Settings</h3>
            <?php
			echo	go_sub_option( 'tasks_name', 'This is the word that will be used in place of Task all over your website. Make sure that it is singular (e.g. Assignment, Quest)', 'Singular Tasks Name', 'go_tasks_name', 'go_tasks_name', 'what would you like tasks to be called? (singular)', 'http://maclab.guhsd.net/go/video/options/questNames.mp4');
			echo	go_sub_option('tasks_plural_name','This is the word used in place of Task all over your website. Use only plural words here (e.g. Assignments, Quests)','Plural Tasks Name', 'go_tasks_plural_name', 'go_tasks_plural_name', 'what would you like tasks to be called? (plural)', 'http://maclab.guhsd.net/go/video/options/questNames.mp4');
		
			echo	go_sub_option( 'first_stage_name', 'This is the word that will be used for the first stage of the Task stages which is triggered upon visting the page for the first time. Such as Encountered.', 'First Stage Name', 'go_first_stage_name', 'go_first_stage_name', 'What would you like the first stage to be called?' , 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option( 'second_stage_name', 'This is the word that will be used for the second stage of Task stages. Such as Accepted.', 'Second Stage Name', 'go_second_stage_name','go_second_stage_name', 'What would you like the second stage to be called?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option('second_stage_button', 'This is the word that will be displayed on the button for the second stage of Task stages. Such as Accept', 'Second Stage Button', 'go_second_stage_button', 'go_second_stage_button', 'What would you like the button for the second stage to say?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option( 'third_stage_name', 'This is the word that will be used for the third stage of Task stages. Such as Completed.', 'Third Stage Name', 'go_third_stage_name','go_third_stage_name', 'What would you like the third stage to be called?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option('third_stage_button', 'This is the word that will be displayed on the button for the third stage of Task stages. Such as Complete', 'Third Stage Button', 'go_third_stage_button', 'go_third_stage_button', 'What would you like the button for the third stage to say?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option( 'fourth_stage_name', 'This is the word that will be used for the fourth stage of Task stages. Such as Mastered.', 'Fourth Stage Name', 'go_fourth_stage_name','go_fourth_stage_name', 'What would you like the fourth stage to be called?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option('fourth_stage_button', 'This is the word that will be displayed on the button for the fourth stage of Task stages. Such as Master', 'Fourth Stage Button', 'go_fourth_stage_button', 'go_fourth_stage_button', 'What would you like the button for the fourth stage to say?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');
			echo	go_sub_option('repeat_button', 'This is the word that will be displayed on the button for repeat. Such as Repeat.', 'Repeat Button', 'go_repeat_button', 'go_repeat_button', 'What would you like the button for repeat to say?', 'http://maclab.guhsd.net/go/video/options/stageNames.mp4');

?></div>
            <br />
            <div id="curr" class="opt-box">
            <h3>Currency Settings</h3>
            <?php 
           echo  go_sub_option('currency_name', 'This is what your currency will be called. Use a name like Dollars, or Gold.','Currency Name','go_currency_name', 'go_currency_name', 'what would you like currency to be called?', 'http://maclab.guhsd.net/go/video/options/currency.mp4'); 
			echo go_sub_option( 'tasks_currency_prefix', 'The prefix symbol used to represent your currency, such as a dollar sign.', 'Currency Prefix', 'go_currency_prefix', 'go_currency_prefix', 'what prefix would you like associated with currency? (Optional)', 'http://maclab.guhsd.net/go/video/options/currency.mp4'); 
			echo go_sub_option( 'tasks_currency_suffix', 'The suffix symbol used to represent your currency, such as Dollar.', 'Currency Suffix', 'go_currency_suffix', 'go_currency_suffix', 'what suffix would you like associated with currency? (Optional)', 'http://maclab.guhsd.net/go/video/options/currency.mp4' ); 
            ?>
        </div><br />
            <br />
            <div id="poi" class="opt-box">       
				<h3>Points Settings</h3>
				<?php 
					echo go_sub_option('tasks_points_name', 'This is what your points will be called. Use a name like Points, or Experience.', 'Points Name', 'go_points_name', 'go_points_name', 'what would you like points to be called?', 'http://maclab.guhsd.net/go/video/options/points.mp4');
					echo go_sub_option( 'tasks_points_prefix', 'The prefix symbol used to represent your points, such as a dollar sign.', 'Points Prefix', 'go_points_sym', 'go_points_prefix', 'what prefix would you like associated with points? (Optional)', 'http://maclab.guhsd.net/go/video/options/points.mp4' ); 
					echo go_sub_option( 'tasks_points_suffix', 'The suffix symbol used to represent your points, such as Exp.', 'Points Suffix', 'go_points_suffix', 'go_points_suffix', 'what suffix would you like associated with points? (Optional)', 'http://maclab.guhsd.net/go/video/options/points.mp4' );  
				?>
            </div><br />
            <div class="opt-box">       
				<h3> Admin Bar Settings</h3>
				<?php
					echo go_sub_option_radio( 'admin_bar_switch', 'Display the admin bar for all users, logged in or not.','Admin Bar Display', 'go_admin_bar_display_switch','go_admin_bar_display_switch', 'Would you like to display the admin bar for all users, logged in or not?Note: for non-logged-in users admin bar will <strong>only</strong> display a "log in" button', 'http://maclab.guhsd.net/go/video/options/adminBarSwitch.mp4');
					echo go_sub_option_radio( 'admin_bar_add_trigger', 'Turn the add section of the admin bar on or off.','Add Switch', 'go_admin_bar_add_switch','go_admin_bar_add_switch', 'Would you like to have the add section of the admin bar?', 'http://maclab.guhsd.net/go/video/options/addBar.mp4');
          echo go_sub_option_radio( 'admin_bar_redirect', 'Redirect all non-admin users to the homepage instead of the dashboard on login.','User Redirect', 'go_admin_bar_user_redirect','go_admin_bar_user_redirect', 'Would you like to redirect users to the homepage on login?', 'http://maclab.guhsd.net/go/video/options/userRedirect.mp4');
				?>
			</div><br />
			<div class="opt-box">
				<h3> Infraction Settings</h3>
				<?php
					echo go_sub_option('max_infractions','When a user has this many infractions they will have 0 life/hitpoints left.','Maximum Infractions','go_max_infractions','go_max_infractions','How many infractions do you want to allow your users?', 'http://maclab.guhsd.net/go/video/options/infractions.mp4');
					echo go_sub_option('infractions_name','The name for infractions','Infractions Name','go_infractions_name','go_infractions_name','The name for infractions.', 'http://maclab.guhsd.net/go/video/options/infractions.mp4');
				?>
            </div>
            <div class="opt-box">       
            <h3>Bonus Currency Settings</h3>
       
          <?php
		echo  go_sub_option('bonus_currency_name', 'This is what your bonus currency will be called. Use a name like Honor or Tokens.',' Bonus Currency Name','go_bonus_currency_name', 'go_bonus_currency_name', 'What would you like the bonus currency to be called?', 'http://maclab.guhsd.net/go/video/options/bonusCurrency.mp4'); 
		echo go_sub_option( 'bonus_currency_bar_color', 'The intervals for the Bonus Currency colors.', ''.go_return_options('go_bonus_currency_name').'', 'go_bonus_currency_color_limit', 'go_bonus_currency_color_limit', '', 'http://maclab.guhsd.net/go/video/options/bonusCurrencyColors.mp4' );
		
		?>
	    <div class="pa">
            	<?php go_opt_help('minutes_multi','It adds an extra percentage of points and currency to points and currency gained from tasks.', 'http://maclab.guhsd.net/go/video/options/bonusCurrencyMultiplier.mp4'); ?> 
            	<strong><?php echo 'Percentage Multiplier. Format: percentage, lower limit, upper limit.'; ?>:</strong><br />   
                On:<input type="radio" <?php if(go_return_options('go_multiplier_switch') == 'On'){echo 'checked="checked"';} ?> name="go_multiplier_switch" size="45" value="On" style="margin-left: 5px;
width: 20px;" /><br />
                Off:<input type="radio" <?php if(go_return_options('go_multiplier_switch') == 'Off'){echo 'checked="checked"';} ?> name="go_multiplier_switch" size="45" value="Off" style="margin-left: 5px;
width: 20px;" /><br />
                <div id="go_multiplier">
<?php 
$limit = go_return_options('go_multiplier');
$rounding = go_return_options('go_multiplier_rounding');
$rounding_text = array('','Normal rounding','Always round up','Always round down') ;
if($limit != ''){
if(!is_array($limit)){ 
$limit = unserialize($limit);
}
if(!is_array($rounding)){
$rounding = unserialize($rounding);	
}
foreach($limit as $key=>$value){
	echo '<input type="text" name="go_multiplier[]" size="45" value="'.$value.'" /> <select name="go_multiplier_rounding[]">
	<option selected="selected" value="'.$rounding[$key].'">'.$rounding_text[$rounding[$key]].'</option>
<option value="1">Normal rounding</option>
<option value="2">Always round up</option>
<option value="3">Always round down</option>
</select>
';
	}}
?></div>
<button type="button" style="width:100%;" onclick="go_new_multiplier();" value="New" >New</button>

                <br />
                <i><?php echo $explanation_question; ?></i> 
            </div> 
 
            </div>
                   <div class="opt-box">  <br />

			</div>
            <div class="opt-box">       
            <h3>Penalty Settings</h3>
       
          <?php
		echo  go_sub_option('penalty_name', 'This is what your penalty will be called. Use a name like Demerit or Penalty.',' Penalty Name','go_penalty_name', 'go_penalty_name', 'What would you like the penalty to be called?', 'http://maclab.guhsd.net/go/video/options/penalty.mp4'); 

		?>
        
        </div>
            <div class="opt-box"> 
     
            <h3> Classifications </h3> 
    <?php
		 echo go_sub_option( 'class_a_name', 'The name of the first classification. Such as Period or Color.','Classification A Name', 'go_class_a_name','go_class_a_name', 'What would you like to call the first classification?', 'http://maclab.guhsd.net/go/video/options/classifications.mp4');
		  echo go_sub_option( 'class_b_name', 'The name of the second classification. Such as Computer or Skill.','Classification B Name', 'go_class_b_name','go_class_b_name', 'What would you like to call the second classification?', 'http://maclab.guhsd.net/go/video/options/classifications.mp4');
		   ?>  
            
           </div>
           <div class="opt-box"> 
                  <div class="pa">
        <h4> A </h4>
       		<ul id="sortable_go_class_a" class="go_sortable">
       <?php
	   $class_a = get_option('go_class_a',false);
	   if($class_a){
		   foreach($class_a as $key=>$value){ 
	    ?>
       <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_class_a_input" type="text" value="<?php echo $value; ?>"/></li> 
<?php     }
	   } 
?>
       </ul>
       <button type="button" style="width:100%;" onclick="go_class_a_new_input();" id="go_class_a_add_input" value="New" >New</button>
       <button type="button" style="width:100%;" onclick="go_class_a_save();" id="go_class_a_add_input" value="Save Classifications" >Save</button>
        </div>
   <?php
go_style_periods();
go_jquery_periods();  
		  ?>
          
          
               <div class="pa">
        <h4> B </h4>
       		<ul id="sortable_go_class_b" class="go_sortable">
       <?php
	   $class_b = get_option('go_class_b',false);
	   if($class_b){
		   foreach($class_b as $key=>$value){ 
	    ?>
       <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_class_b_input" type="text" value="<?php echo $value; ?>"/></li> 
<?php     }
	   } 
?>
       </ul>
       <button type="button" style="width:100%;" onclick="go_class_b_new_input();" id="go_class_b_add_input" value="New" >New</button>
       <button type="button" style="width:100%;" onclick="go_class_b_save();" id="go_class_b_add_input" value="Save Classifications" >Save</button>
        </div>
        </div>

	<div class="opt-box">
    	<h3> Focus </h3>
        <?php echo go_sub_option( 'focus_name', 'The name of focuses such as \'Career\' or \'Pathway\' ', 'Focus Name', 'go_focus_name','go_focus_name', 'What would you like to call focuses?', 'http://maclab.guhsd.net/go/video/options/focuses.mp4');?>
    </div>
    
    <div class="opt-box">
      <div class="pa">
      	<?php go_opt_help('focus_explanation', 'The switch to turn focuses on and off. Focuses are the areas of interest or study that users choose. Focuses can be purchased via store items.', 'http://maclab.guhsd.net/go/video/options/focuses.mp4'); ?> 
        <strong><?php echo 'Turn focuses on or off: ';?> </strong><br />
        On:<input type="radio" <?php if(go_return_options('go_focus_switch') == 'On'){echo 'checked="checked"';} ?> name="go_focus_switch" size="45" value="On" style="margin-left: 5px;width: 20px;" /><br />
        Off:<input type="radio" <?php if(go_return_options('go_focus_switch') == 'Off'){echo 'checked="checked"';} ?>name="go_focus_switch" size="45" value="Off" style="margin-left: 5px;width: 20px;" /><br />
        <strong><?php echo 'List of focuses: '; ?></strong><br />
        <i>Note: Clicking save will add these values as task categories</i>
        <ul id="sortable_focus" class="go_sortable">
			<?php 
            $focus = get_option('go_focus');
            if($focus){
            	foreach($focus as $key=>$value){
            ?>
            	<li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_focus" type="text" value="<?php echo $value; ?>"/></li> 
            <?php
           		}
            }
            ?>
        </ul>
        <button type="button" style="width:100%;" onclick="go_focus_new_input();" id="go_focus_add_input" value="New" >New</button>
      	<button type="button" style="width:100%;" onclick="go_focus_save();" id="go_focus_add_input" value="Save Classifications" >Save</button>
      </div>
    </div>
	
	<div class="opt-box">
		<h3>Reset Data Switch</h3>
	</div>
	<div class="opt-box">
		<div class="pa">
			<?php 
				echo go_opt_help('reset_data_switch', 'Turn the data reset button on or off. The button can reset points, currency, bonus currency, penalties, or all four. After resetting points/currency/bonus currency/penalties, user\'s records are erased, meaning they will have 0 of whatever was erased and there will be no log of it in their stats page.',  'http://maclab.guhsd.net/go/video/options/resetDataSwitch.mp4');
			?>
			<strong>Turn data reset switches on or off: </strong><br/><i>Note, button appears at the bottom of this page.</i><br/>
			On:<input type="radio" <?php if(go_return_options('go_data_reset_switch') == 'On'){echo 'checked="checked"';} ?> name="go_data_reset_switch" size="45" value="On" style="margin-left: 5px;width: 20px;" /><br />
			Off:<input type="radio" <?php if(go_return_options('go_data_reset_switch') == 'Off'){echo 'checked="checked"';} ?>name="go_data_reset_switch" size="45" value="Off" style="margin-left: 5px;width: 20px;" /><br />
		</div>
	</div>
   	<div class="opt-box">
    	<h3>Videos</h3>
        <div class="pa">
        Set the default height and width of videos displayed by the go_display_video shortcode.
        <?php go_opt_help('video_settings', 'Set the default dimensions of videos on your site.', 'http://maclab.guhsd.net/go/video/options/videos.mp4');?>
        <ul>
            <li class="ui-state-default" >Width: <input name="go_video_width" type="text" value="<?php echo go_return_options('go_video_width');?>"/>px</li>
        	<li class="ui-state-default" >Height: <input name="go_video_height" type="text" value="<?php echo go_return_options('go_video_height');?>"/>px</li>
        </ul>
   		</div>
    </div>

            
   <div class="opt-box">       
            <h3> Presets </h3>  </div>
           <div class="opt-box" > 
                  <div class="pa" style="width:46%;">
                  <?php go_opt_help('presets_explanation', 'Preset values for tasks. Values are comma separated and correspond with their task stages. E.g., 1,2,3,4 assigns 1 exp to stage 1, 2 exp to stage 2, etc.', 'http://maclab.guhsd.net/go/video/options/presets.mp4');?><br/>
       
       		<ul id="sortable_go_presets" class="go_sortable">
       <?php
	   $presets = get_option('go_presets',false);
	   if($presets){
		   foreach($presets as $key=>$value){ 
		  
	    ?>
       <li class="ui-state-default" class="go_list">
       <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
       <label for="go_preset_name" style="margin-left:15px;">Name: </label>
       <input type="text" id="go_preset_name" value="<?php echo $key; ?>" />
       <label for="go_preset_points"><?php echo go_return_options('go_points_name'); ?>: </label>
       <input type="text" id="go_preset_points" value="<?php echo $value[0]; ?>"/>
	   <label for="go_preset_currency"><?php echo go_return_options('go_currency_name'); ?>: </label>
       <input type="text" id="go_preset_currency" value="<?php echo $value[1]; ?>"/>
       
       </li> 
<?php     
	   } 
	   }
?>
       </ul>
       <button type="button" style="width:100%;" onclick="go_preset_reset();" id="go_reset_presets">Reset Presets</button>
       <button type="button" style="width:100%;" onclick="go_presets_new_input();" id="go_preset_new_input" value="New" >New</button>
       <button type="button" style="width:100%;" onclick="go_preset_save();" id="go_preset_add_input" >Save</button>
        </div>
       
          
            </div>
                        
          
            
            
            <span class="opt-inp"><input type="submit" name="Submit" value="Save Options" /> </span> 
            <input type="hidden" name="action" value="update" />  
            <input type="hidden" name="page_options" value="go_tasks_name,go_tasks_plural_name,go_currency_name,go_points_name,go_first_stage_name,go_second_stage_name,go_second_stage_button,go_third_stage_name,go_third_stage_button,go_fourth_stage_name,go_fourth_stage_button,go_currency_prefix,go_currency_suffix, go_points_prefix, go_points_suffix, go_admin_bar_display_switch, go_admin_bar_add_switch, go_admin_bar_user_redirect, go_repeat_button, go_class_a_name, go_class_b_name,go_max_infractions,go_infractions_name,go_bonus_currency_color_limit,go_multiplier,go_multiplier_switch,go_multiplier_rounding,go_focus_switch,go_focus_name,go_data_reset_switch, go_video_height, go_video_width, go_bonus_currency_name, go_penalty_name" />  
        </form>
		<?php
			if(get_option('go_data_reset_switch') == 'On'){
				global $wpdb;
		?> 
				<h3> Choose which records to erase </h3>
				<form action="" method="post">
					<span class="opt-inp"><input type="checkbox" value="erase_points" name="erase_records[]" /><?php echo go_return_options('go_points_name'); ?></span>
					<span class="opt-inp"><input type="checkbox" value="erase_currency" name="erase_records[]" /><?php echo go_return_options('go_currency_name');?></span>
					<span class="opt-inp"><input type="checkbox" value="erase_bonus_currency" name="erase_records[]" /><?php echo go_return_options('go_bonus_currency_name');?></span>
                    <span class="opt-inp"><input type="checkbox" value="erase_penalty" name="erase_records[]" /><?php echo go_return_options('go_penalty_name');?></span>
					<span class="opt-inp"><input type="checkbox" value="erase_all" name="erase_records[]" />All</span>
					<span class="opt-inp"><input type="submit" value="Erase" name="erase" /></span>
				</form>
				<script type="text/javascript">
					jQuery('input[value="erase_all"]').click(function(){
						if(jQuery(this).prop('checked')){
							jQuery('input[name="erase_records[]"]').each(function(){
								jQuery(this).prop('checked', true);
							});
						}else{
							jQuery('input[name="erase_records[]"]').each(function(){
								jQuery(this).prop('checked', false);
							});
						}
					});
				</script>
		<?php
				if(isset($_POST['erase_records'])){
					foreach($_POST['erase_records'] as $erase_type){
						switch($erase_type){
							case 'erase_points':
								$erase_array[] = 'points';
								$users = get_users('orderby=ID');
								$ranks = go_return_options('go_ranks');
								$erase_level = array(array(key($ranks), $ranks[key($ranks)]));
								next($ranks);
								$erase_level[] = array(key($ranks), $ranks[key($ranks)]);
				
								foreach($users as $user){
									update_user_meta($user->ID, 'go_rank', $erase_level);
								}
								break;
							case 'erase_currency':
								$erase_array[] = 'currency';
								break;
							case 'erase_bonus_currency':
								$erase_array[] = 'bonus_currency';
								break;
							case 'erase_penalty':
								$erase_array[] = 'penalty';
								break;
						}
					}
					$erase_list = implode(',', $erase_array);
					$erase_update = "SET ".implode('=0,', $erase_array)."=0";
					$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."go WHERE %s IS NOT NULL", $erase_list));
					$wpdb->query("UPDATE ".$wpdb->prefix."go_totals ".$erase_update);
				}
			}
		?>
        
        <script type="text/javascript">
        function go_presets_new_input(){
	jQuery('#sortable_go_presets').append(' <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><label for="go_preset_name" style="margin-left:15px;">Name: </label><input type="text" id="go_preset_name" /><label for="go_preset_points"><?php echo go_return_options('go_points_name'); ?>: </label><input type="text" id="go_preset_points" /><label for="go_preset_currency"><?php echo go_return_options('go_currency_name'); ?>: </label><input type="text" id="go_preset_currency" /> </li>');
	}
	function go_new_multiplier(){
		jQuery('#go_multiplier').append('<input type="text" name="go_multiplier[]" size="45" value="" /><select name="go_multiplier_rounding[]"><option value="1">Normal rounding</option><option value="2">Always round up</option><option value="3">Always round down</option></select>');
		}
        </script>
        <?php 
} 

}
function add_game_on_options() {  
    add_menu_page('Game On', 'Game On', 'manage_options', 'game-on-options.php','game_on_options', plugins_url( 'images/ico.png' , __FILE__ ), '81');  
	add_submenu_page( 'game-on-options.php', 'Options', 'Options', 'manage_options', 'game-on-options.php', 'game_on_options');

}
add_action('admin_menu', 'add_game_on_options');
function go_class_a_save(){
	$array = $_POST['class_a_array'];
	foreach($array as $key=>$value){
		if ($value == ''){unset($array[$key]);}
	} 
update_option('go_class_a',$array);
 $class_a = get_option('go_class_a',false);
	   if($class_a){foreach($class_a as $key=>$value){
		  
	    ?>
       <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_class_a_input" type="text" value="<?php echo $value; ?>"/></li> <?php }} 
die();
}

function go_class_b_save(){
	$array = $_POST['class_b_array'];
	foreach($array as $key=>$value){
		if ($value == ''){unset($array[$key]);}
	} 
update_option('go_class_b',$array);
 $class_b = get_option('go_class_b',false);
	   if($class_b){foreach($class_b as $key=>$value){
		  
	    ?>
       <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_class_b_input" type="text" value="<?php echo $value; ?>"/></li> <?php }} 
die();
}

function go_focus_save(){
	global $wpdb;
	$array = $_POST['focus_array'];
	$terms = $wpdb->get_results("SELECT * FROM $wpdb->terms", ARRAY_A);
	$term_names = array();
	
	foreach($array as $key=>$value){
		if ($value == ''){
			unset($array[$key]);
		}
		if(!term_exists($value, 'task_focus_categories')){
			wp_insert_term($value, 'task_focus_categories');
		}
	} 
	
	foreach($terms as $term){
		if($term['name'] != 'Uncategorized'){
			array_push($term_names, $term['name']);
		}
	}
	$delete_terms = array_diff($term_names, $array);
	foreach($delete_terms as $term){
		$term_id = $wpdb->get_var("SELECT `term_id` FROM $wpdb->terms WHERE `name`='".$term."'");
		wp_delete_term($term_id, 'task_focus_categories');
	}
	update_option('go_focus',$array);
	$focus = get_option('go_focus',false);
	if($focus){
		foreach($focus as $key=>$value){
	?>
       		<li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_focus" type="text" value="<?php echo $value; ?>"/></li> 
	<?php 
		}
	} 
die();
}

function go_get_all_focuses() {
	if(get_option('go_focus')){
		$all_focuses = get_option('go_focus');
	}
	$all_focuses_sorted = array();
	if($all_focuses){
		foreach($all_focuses as $focus ) {
			 $all_focuses_sorted[] = array('name' => $focus , 'value' => $focus);
		 }
	}
	return $all_focuses_sorted;
}

add_action('wp_ajax_go_new_user_focus', 'go_new_user_focus');
function go_new_user_focus(){
	$new_user_focus = $_POST['new_user_focus'];
	$user_id = $_POST['user_id'];
	update_user_meta($user_id, 'go_focus', $new_user_focus);
	die();	
}

function go_presets_reset(){
	global $wpdb;
	if(!empty($_POST['presets'])){
		$json = $_POST['presets'];
		$json_string = stripslashes($json);
		$presets = json_decode($json_string, true);
		
		update_option('go_presets', $presets);
	}
}

function go_presets_save(){
	global $wpdb;
	$preset_name = $_POST['go_preset_name'];
	$preset_points = $_POST['go_preset_points'];
	$preset_currency = $_POST['go_preset_currency'];
	foreach($preset_name as $key=>$value){
		if($value!=''){
			$preset_array[$value] = array($preset_points[$key],$preset_currency[$key]);
			echo '<li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><label for="go_preset_name" style="margin-left:15px;">Name: </label><input type="text" id="go_preset_name" value="'.$value.'" /><label for="go_preset_points">'. go_return_options('go_points_name').': </label><input type="text" id="go_preset_points" value="'.$preset_array[$value][0].'" /><label for="go_preset_currency">'.go_return_options('go_currency_name').': </label><input type="text" id="go_preset_currency" value="'.$preset_array[$value][1].'" /> </li>';
		} 
	}
	update_option('go_presets',$preset_array);
	die();
}




add_action( 'show_user_profile', 'go_extra_profile_fields' );
add_action( 'edit_user_profile', 'go_extra_profile_fields' );

function go_extra_profile_fields( $user ) { ?>

	<h3><?php echo go_return_options('go_class_a_name').' and '.go_return_options('go_class_b_name'); ?></h3>

	<table id="go_user_form_table">
<th><?php echo go_return_options('go_class_a_name'); ?></th><th><?php echo go_return_options('go_class_b_name'); ?></th>
<tbody id="go_user_form_table_body">

<?php
 if(get_user_meta($user->ID, 'go_classifications',true)){ 

foreach(get_user_meta($user->ID, 'go_classifications',true) as $keyu => $valueu){
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
		if(get_option('go_focus_switch', true) == 'On'){
	?>
		<h3>User <?php echo go_return_options('go_focus_name');?></h3>
		<?php 
			echo go_display_user_focuses($user->ID);
		}
    ?>
    <script type="text/javascript" language="javascript">
		function go_add_class(){
			var ajaxurl = "<?php global $wpdb;
			echo admin_url( 'admin-ajax.php' ) ; ?>";
			jQuery.ajax({
				type: "post",
				url: ajaxurl,
				data: { 
					action: 'go_user_option_add',
					go_clipboard_class_a_choice: jQuery('#go_clipboard_class_a_choice').val()
				},
				success: function(html){
					jQuery('#go_user_form_table_body').append(html);
				}
			});
		}
    </script>
<?php



 }




add_action( 'personal_options_update', 'go_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'go_save_extra_profile_fields' );
add_action('go_return_presets_options','go_return_presets_options');

function go_user_option_add(){
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
	
function go_save_extra_profile_fields( $user_id ) {

	if(isset($_POST['class_a_user'])){
		foreach($_POST['class_a_user'] as $key=>$value){
			if($value != 'go_remove'){
				$class_a = $value;
				$class_b = $_POST['class_b_user'][$key];
				$class[$class_a] = $class_b;
			}
		}
		update_user_meta( $user_id, 'go_classifications', $class );
	}
}	

function go_return_presets_options(){
	 global $wpdb;
	$presets = get_option('go_presets',false);
	$array = array();
	if($presets){
		foreach($presets as $key=>$value){ 
			$array[] = array('name'=> $key, 'points'=>$value[0], 'currency'=>$value[1], 'value'=>  $key.' - '.$value[0].' - '.$value[1]  );
		}
	}
	return $array;
}

function go_update_globals(){
	global $wpdb;
	$file_name = $real_file = plugin_dir_path( __FILE__ ) . '/' . 'go_definitions.php';
	$array = explode(',','go_tasks_name,go_tasks_plural_name,go_currency_name,go_points_name,go_first_stage_name,go_second_stage_name,go_second_stage_button,go_third_stage_name,go_third_stage_button,go_fourth_stage_name,go_fourth_stage_button,go_currency_prefix,go_currency_suffix, go_points_prefix, go_points_suffix, go_admin_bar_display_switch, go_admin_bar_add_switch, go_admin_bar_user_redirect, go_repeat_button, go_class_a_name, go_class_b_name, go_max_infractions,go_infractions_name, go_multiplier,go_multiplier_switch,go_multiplier_rounding,go_bonus_currency_color_limit,go_focus_switch,go_focus_name,go_data_reset_switch, go_video_height, go_video_width, go_bonus_currency_name, go_penalty_name');
	foreach($array as $key=>$value){
		$value = trim($value);
		$content = get_option($value);
		if(is_array($content)){
			$content = serialize($content);
		}
		$string .= 'define("'.$value.'",\''.$content.'\',TRUE);';
	}

	file_put_contents ( $file_name, '<?php '.$string.' ?>' );

}
?>