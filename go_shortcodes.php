<?php 

function listUserURL(){
	$class_names = get_option('go_class_a');
	?>
	<select id="go_period_list_user_url">
		<option value="select_option">Select an option</option>
		<?php
			foreach($class_names as $class_name){
				echo '<option value="'.$class_name.'">'.$class_name.'</option>';
			}
		?>
	</select>
	 <script type="text/javascript"> 
		var period = jQuery('#go_period_list_user_url');
		period.change(function(){
			var period_val = period.val();
			var go_ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
			jQuery.ajax({
				url: go_ajaxurl,
				type: "POST",
				data:{
					action: 'listurl',
					class_a_choice: period_val
				},
				success: function(data){
					jQuery('#go_list_user_url').append(data);
					period.change(function(){
						jQuery('#go_list_user_url').html('');
					});
				}
			});
		});
	</script>

	<div id="go_list_user_url" style="margin-top:10px; width:100%;"></div>
	
	<?php
}

function listurl(){
	global $wpdb;
	if(isset($_POST['class_a_choice'])){
		$all_user = get_users();
		$class_a_choice = $_POST['class_a_choice'];
		$table_name_go_totals= $wpdb->prefix.'go_totals';
		$uids = $wpdb->get_results("SELECT uid FROM ".$table_name_go_totals."");
		foreach($uids as $uid){
			foreach($uid as $id){
				$user = get_user_by('id', $id);
				$user_class = get_user_meta($id, 'go_classifications', true);
				if($user_class){
					$class = array_keys($user_class);
					$check = in_array($class_a_choice, $class);
					if($check){
						$user_url = $user->user_url;
						$user_username = $user->display_name;
						$user_complete_url = '<a class="go_user_url" href="'.$user_url.'" target="_blank" >'.$user_username.'</a><br/>';
						echo $user_complete_url;
					}
				}
			}
		}
	}
	die();
}
add_shortcode('go_list_URL', 'listUserURL');

function go_display_video($atts, $video_url){
	extract(shortcode_atts(array(
		'video_url' => '',
		'video_title' => '',
		'height' => '',
		'width' => '',
		), $atts
	));
	if($video_url){
		if($height && $width){
		?>
        	<script type="text/javascript"> 
				jQuery('#go_help_video_container').css({'height': '<?php echo $height?>px', 'width': '<?php echo $width;?>px'});
			</script>
        <?php	
		}
		if($height){
		?>
		<script type="text/javascript"> 
            jQuery('#go_help_video_container').css('height', '<?php echo $height?>px');
        </script>
        <?php
		} 
		if($width){
		?>
		<script type="text/javascript"> 
            jQuery('#go_help_video_container').css('width','<?php echo $width;?>px');
        </script>
        <?php
		}
		if($video_title){
			return '<a href="javascript:;" onclick="go_display_help_video(\''.$video_url.'\');">'.$video_title.'</a>';	
		} else{
			return '<a href="javascript:;" onclick="go_display_help_video(\''.$video_url.'\');">video</a>';	
		}
	}
}
add_shortcode('go_display_video', 'go_display_video');
?>