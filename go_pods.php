<?php
add_action( 'admin_menu', 'go_pod_submenu' );
function go_pod_submenu() {
	add_submenu_page( 'game-on-options.php', 'Pods', 'Pods', 'manage_options', 'go_pods', 'go_task_pods' );
}

function go_task_pods() {
	if ( ! empty( $_GET['settings-updated'] ) && ( $_GET['settings-updated'] === true || $_GET['settings-updated'] === 'true' ) ) {
		 echo "
		 <script type='text/javascript'>
			window.location = '".esc_url( admin_url() )."admin.php?page=go_pods'
		 </script>";
	}

	$post_custom = get_post_custom( get_the_id() );
	$pods_options = get_option( 'go_task_pod_globals' );
	$pods_array = array();
	
	$args = array(
		'pad_counts' => 1,
		'taxonomy' => 'task_pods'
	);
	?>
	<div class="wrap go_wrap">
		<h2>Pods</h2><?php  go_options_help( 'http://maclab.guhsd.net/go/video/pods.mp4', 'Group ' . go_return_options( 'go_tasks_plural_name' ) . ' into pods where user must complete a designated amount of ' . go_return_options( 'go_tasks_plural_name' ) . ' to continue.', true ); ?>
		<form method="post" action="options.php" id="go_pod_form">
			<?php
			wp_nonce_field( 'update-options' ); 
			foreach ( get_categories( $args ) as $category ) {
				$pods_array[] = $category;
			}
			foreach ($pods_array as $pod_category ) {
				$link = get_category_link( $pod_category );
				$slug = $pod_category->slug;
				$total = $pod_category->count;
				echo "<span class='go_pod_list_item' id='go_pod_span_{$slug}'><b><a href='{$link}' target='_blank'>".$pod_category->name."</a></b>";
				?>
				<br/>
				<input type='text' id='go_pod_link[<?php echo $slug ?>]' name='go_task_pod_globals[<?php echo $slug; ?>][go_pod_link]' 
					value='<?php echo ( ! empty( $pods_options[ $slug ]['go_pod_link'] ) ? $pods_options[ $slug ]['go_pod_link'] : '' ); ?>' placeholder='Link'/><br/>
				Must Complete 
				<select id='go_pod_stage_select[<?php echo $slug ?>]' name='go_task_pod_globals[<?php echo $slug; ?>][go_pod_stage_select]'>
					<option <?php echo ( ( ! empty( $pods_options[ $slug ]['go_pod_stage_select'] ) && 'third_stage' == $pods_options[ $slug ]['go_pod_stage_select'] ) ? 'selected' : '' ); ?> 
						value='third_stage'><?php echo go_return_options( 'go_third_stage_name' ); ?></option>
					<option <?php echo ( ( ! empty( $pods_options[ $slug ]['go_pod_stage_select'] ) && 'fourth_stage' == $pods_options[ $slug ]['go_pod_stage_select'] ) ? 'selected' : '' ); ?> 
						value='fourth_stage'><?php echo go_return_options( 'go_fourth_stage_name' ); ?></option>
				</select> 
				of 
				<input type='number' id='go_pod_number[ <?php echo $slug ?> ]' name='go_task_pod_globals[<?php echo $slug; ?>][go_pod_number]' 
					value='<?php echo ( isset( $pods_options[ $slug ]['go_pod_number'] )  ? $pods_options[ $slug ]['go_pod_number'] : 1 ); ?>' style='width : 45px;' 
					min='0' max='<?php echo $total ?>'/> <?php echo go_return_options( 'go_tasks_plural_name' ); ?> 
				to continue to 
				<select class='go_next_pod_select' id='go_next_pod_select[ <?php echo $slug ?> ]' name='go_task_pod_globals[<?php echo $slug; ?>][go_next_pod_select]'>
					<option>...</option>
					<?php
					foreach ( $pods_array as $pod ) {
						if ( $pod->slug !== $slug ) {
							$pod_name = $pod->name;
							?>
							<option <?php echo ( ( ! empty( $pods_options[ $slug ]['go_next_pod_select'] ) && $pods_options[ $slug ]['go_next_pod_select'] == $pod_name ) ? 'selected' : '' ); ?> 
								value='<?php echo $pod_name; ?>'
							>
								<?php echo $pod_name; ?>
							</option>
							<?php
						}
					}
					?>
				</select>
				</span>
				</br>
				</br>
				<?php
			}
			?>
			<input type="submit" name="Submit" value="Save Pods" />
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="go_task_pod_globals"/>
		</form>
	</div>
	<?php
}
?>
