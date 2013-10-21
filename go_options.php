<?php
if (is_admin()) {
function go_opt_help($field, $title) {
	echo '<a id="go_help_'.$field.'" class="go_opt_help" onclick="" title="'.$title.'">?</a>';
}
function go_opt_style() {
    wp_register_style( 'go_opt_css', plugins_url( 'css/options.css' , __FILE__ ), false, '1.0.0' );
    wp_enqueue_style( 'go_opt_css' );
}
add_action('admin_enqueue_scripts', 'go_opt_style');

function go_sub_option($explanation_name, $explanation, $title, $field_name, $option_name, $explanation_question){ ?>
	    <div class="pa">
            	<?php go_opt_help($explanation_name,$explanation); ?> 
            	<strong><?php echo $title; ?>:</strong><br />  
                <input type="text" name="<?php echo $field_name; ?>" size="45" value="<?php echo get_option($option_name); ?>" /><br />
                <i><?php echo $explanation_question; ?></i> 
            </div> <?php
            
	}
	
function go_sub_option_radio($explanation_name, $explanation, $title, $field_name, $option_name, $explanation_question){ ?>
	    <div class="pa">
            	<?php go_opt_help($explanation_name,$explanation); ?> 
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
	}
?>  
    <div class="go-wrap">  
        <h2>Game On Options</h2>  
        <form method="post" action="options.php">  
            <?php wp_nonce_field('update-options') ?> 
            <div id="tsk" class="opt-box">
            <h3>Task Settings</h3>
            <?php
			echo	go_sub_option( 'tasks_name', 'This is the word that will be used in place of Task all over your website. Make sure that it is singular (e.g. Assignment, Quest)', 'Singular Tasks Name', 'go_tasks_name', 'go_tasks_name', 'what would you like tasks to be called? (singular)' );
			echo	go_sub_option('tasks_plural_name','This is the word used in place of Task all over your website. Use only plural words here (e.g. Assignments, Quests)','Plural Tasks Name', 'go_tasks_plural_name', 'go_tasks_plural_name', 'what would you like tasks to be called? (plural)' );
		
			echo	go_sub_option( 'first_stage_name', 'This is the word that will be used for the first stage of the Task stages which is triggered upon visting the page for the first time. Such as Encountered.', 'First Stage Name', 'go_first_stage_name', 'go_first_stage_name', 'What would you like the first stage to be called?'  );
			echo	go_sub_option( 'second_stage_name', 'This is the word that will be used for the second stage of Task stages. Such as Accepted.', 'Second Stage Name', 'go_second_stage_name','go_second_stage_name', 'What would you like the second stage to be called?' );
			echo	go_sub_option('second_stage_button', 'This is the word that will be displayed on the button for the second stage of Task stages. Such as Accept', 'Second Stage Button', 'go_second_stage_button', 'go_second_stage_button', 'What would you like the button for the second stage to say?');
			echo	go_sub_option( 'third_stage_name', 'This is the word that will be used for the third stage of Task stages. Such as Completed.', 'Third Stage Name', 'go_third_stage_name','go_third_stage_name', 'What would you like the third stage to be called?' );
			echo	go_sub_option('third_stage_button', 'This is the word that will be displayed on the button for the third stage of Task stages. Such as Complete', 'Third Stage Button', 'go_third_stage_button', 'go_third_stage_button', 'What would you like the button for the third stage to say?');
			echo	go_sub_option( 'fourth_stage_name', 'This is the word that will be used for the fourth stage of Task stages. Such as Mastered.', 'Fourth Stage Name', 'go_fourth_stage_name','go_fourth_stage_name', 'What would you like the fourth stage to be called?' );
			echo	go_sub_option('fourth_stage_button', 'This is the word that will be displayed on the button for the fourth stage of Task stages. Such as Master', 'Fourth Stage Button', 'go_fourth_stage_button', 'go_fourth_stage_button', 'What would you like the button for the fourth stage to say?');
			echo	go_sub_option('repeat_button', 'This is the word that will be displayed on the button for repeat. Such as Repeat.', 'Repeat Button', 'go_repeat_button', 'go_repeat_button', 'What would you like the button for repeat to say?');

?></div>
            <br />
            <div id="curr" class="opt-box">
            <h3>Currency Settings</h3>
            <?php 
           echo  go_sub_option('currency_name', 'This is what your currency will be called. Use a name like Dollars, or Gold.','Currency Name','go_currency_name', 'go_currency_name', 'what would you like currency to be called?' ); 
			echo go_sub_option( 'tasks_currency_prefix', 'The prefix symbol used to represent your currency, such as a dollar sign.', 'Currency Prefix', 'go_currency_prefix', 'go_currency_prefix', 'what prefix would you like associated with currency? (Optional)' ); 
			echo go_sub_option( 'tasks_currency_suffix', 'The suffix symbol used to represent your currency, such as Dollar.', 'Currency Suffix', 'go_currency_suffix', 'go_currency_suffix', 'what suffix would you like associated with currency? (Optional)' ); 
            ?>
        </div><br />
            <br />
            <div id="poi" class="opt-box">       
            <h3>Points Settings</h3>
           <?php echo go_sub_option('tasks_points_name', 'This is what your points will be called. Use a name like Points, or Experience.', 'Points Name', 'go_points_name', 'go_points_name', 'what would you like points to be called?');
          echo  go_sub_option( 'tasks_points_prefix', 'The prefix symbol used to represent your points, such as a dollar sign.', 'Points Prefix', 'go_points_sym', 'go_points_prefix', 'what prefix would you like associated with points? (Optional)' ); 
			echo go_sub_option( 'tasks_points_suffix', 'The suffix symbol used to represent your points, such as Exp.', 'Points Suffix', 'go_points_suffix', 'go_points_suffix', 'what suffix would you like associated with points? (Optional)' );  ?>
          
          
            </div>
            
            
              <br />
            <div class="opt-box">       
            <h3> Admin Bar Settings</h3>
       
          <?php
		 echo go_sub_option_radio( 'admin_bar_add_trigger', 'Turn on and off the add section of the admin bar.','Add Switch', 'go_admin_bar_add_switch','go_admin_bar_add_switch', 'Would you like to have the Add section of the admin bar?');
		   ?><br />
	<div class="opt-box">
            <h3> Infraction Settings</h3>
            <?php
            echo go_sub_option('max_infractions','When a user has this many infractions they will have 0 life/hitpoints left.','Maximum Infractions','go_max_infractions','go_max_infractions','How many infractions do you want to allow your students?');
                  ?>
            </div>
            </div>
                   <div class="opt-box">       
            <h3> Classifications </h3> 
    <?php
		 echo go_sub_option( 'class_a_name', 'The name of the first classification. Such as Period or Color.','Classification A Name', 'go_class_a_name','go_class_a_name', 'What would you like to call the first classification?');
		  echo go_sub_option( 'class_b_name', 'The name of the second classification. Such as Computer or Skill.','Classification B Name', 'go_class_b_name','go_class_b_name', 'What would you like to call the second classification?');
		   ?>  </div>
           <div class="opt-box"> 
                  <div class="pa">
        <h4> A </h4>
       		<ul id="sortable_go_class_a">
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
       		<ul id="sortable_go_class_b">
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
        </div></div>


            
   <div class="opt-box">       
            <h3> Presets </h3>  </div>
           <div class="opt-box" > 
                  <div class="pa" style="width:46%;">
       
       		<ul id="sortable_go_presets">
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
            <input type="hidden" name="page_options" value="go_tasks_name,go_tasks_plural_name,go_currency_name,go_points_name,go_first_stage_name,go_second_stage_name,go_second_stage_button,go_third_stage_name,go_third_stage_button,go_fourth_stage_name,go_fourth_stage_button,go_currency_prefix,go_currency_suffix, go_points_prefix, go_points_suffix, go_admin_bar_add_switch, go_repeat_button, go_class_a_name, go_class_b_name, go_max_infractions" />  
        </form>
        
        <script type="text/javascript">
        function go_presets_new_input(){
	jQuery('#sortable_go_presets').append(' <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><label for="go_preset_name" style="margin-left:15px;">Name: </label><input type="text" id="go_preset_name" /><label for="go_preset_points"><?php echo go_return_options('go_points_name'); ?>: </label><input type="text" id="go_preset_points" /><label for="go_preset_currency"><?php echo go_return_options('go_currency_name'); ?>: </label><input type="text" id="go_preset_currency" /> </li>');
	}
        </script>
        <?php /*
      */
} 
function add_game_on_options() {  
    add_menu_page('Game On', 'Game On', 'manage_options', 'game-on-options.php','game_on_options', plugins_url( 'images/ico.png' , __FILE__ ), '81');  
}
add_action('admin_menu', 'add_game_on_options');
}

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
	echo ' <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><label for="go_preset_name" style="margin-left:15px;">Name: </label><input type="text" id="go_preset_name" value="'.$value.'" /><label for="go_preset_points">'. go_return_options('go_points_name').': </label><input type="text" id="go_preset_points" value="'.$value[0].'" /><label for="go_preset_currency">'.go_return_options('go_currency_name').': </label><input type="text" id="go_preset_currency" value="'.$value[1].'" /> </li>';
	} }
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
        </tr>

	</table>
    <script type="text/javascript" language="javascript">
    function go_add_class(){
		var ajaxurl = "<?php global $wpdb;
		echo admin_url( 'admin-ajax.php' ) ; ?>";
		jQuery.ajax({
		type: "post",url: ajaxurl,data: { 
		action: 'go_user_option_add',
		go_clipboard_class_a_choice: jQuery('#go_clipboard_class_a_choice').val()},
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
			//echo '<option value="'.$key.'" points="'.$value[0].'" currency="'.$value[1].'">'.$key.' - '.$value[0].' - '.$value[1].'</option>';  
		   }}
		   
	
	return $array;
					
	}
function go_update_globals(){
	global $wpdb;
	$file_name = $real_file = plugin_dir_path( __FILE__ ) . '/' . 'go_definitions.php';
	$array = explode(',','go_tasks_name,go_tasks_plural_name,go_currency_name,go_points_name,go_first_stage_name,go_second_stage_name,go_second_stage_button,go_third_stage_name,go_third_stage_button,go_fourth_stage_name,go_fourth_stage_button,go_currency_prefix,go_currency_suffix, go_points_prefix, go_points_suffix, go_admin_bar_add_switch, go_repeat_button, go_class_a_name, go_class_b_name, go_max_infractions');
	foreach($array as $key=>$value){
$value = trim($value);
		$string .= 'define("'.$value.'","'.get_option($value).'",TRUE);';
		}
	
 file_put_contents ( $file_name, '<?php '.$string.' ?>' );

	}
?>