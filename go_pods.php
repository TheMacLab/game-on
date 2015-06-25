<?php
add_action( 'admin_menu', 'go_pod_submenu' );

function go_pod_submenu() {
	add_submenu_page( 'game-on-options.php', 'Pods', 'Pods', 'manage_options', 'go_pods', 'go_task_pods' );
}

function go_task_pods() {
	if ( $_GET[ 'settings-updated' ] == true || $_GET[ 'settings-updated' ] == 'true' ) {
		 echo "
		 <script type='text/javascript'>
			window.location = '".admin_url()."admin.php?page=go_pods'
		 </script>";
	}

	$post_custom = get_post_custom( get_the_id() );
	$pods_options = get_option( 'go_task_pod_globals' );
	$pods_array = array();
	//list terms in a given taxonomy using wp_list_categories  (also useful as a widget)
	$orderby = 'name';
	$show_count = 0; // 1 for yes, 0 for no
	$pad_counts = 1; // 1 for yes, 0 for no
	$hierarchical = 1; // 1 for yes, 0 for no
	$taxonomy = 'task_pods';
	$title = '';
	$args = array(
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'taxonomy'     => $taxonomy,
	  'title_li'     => $title
	);
	?>
    <div class="wrap go_wrap">
    <h2>Pods</h2><?php  go_options_help( 'http://maclab.guhsd.net/go/video/pods.mp4', 'Group ' . go_return_options( 'go_tasks_plural_name' ) . ' into pods where user must complete a designated amount of ' . go_return_options( 'go_tasks_plural_name' ) . ' to continue.', true ); ?>
    <form method="post" action="options.php" id="go_pod_form">    
            <ul>
            <?php
			wp_nonce_field( 'update-options' ); 
            foreach ( get_categories( $args ) as $category ) {
                $pods_array[] = $category;
            }
            foreach ($pods_array as $pod ) {
                global $post;
                $terms = wp_get_post_terms( $post->ID, 'task_pods' );
                for ( $i = 0; $i <= count( $terms ); $i++ ) {
                    $link = get_category_link( $pod );
					$slug = $pod->slug;
					$total = $pod->count;
                    echo "<b><a href='{$link}' target='_blank'>".$pod->name."</a></b>";
                    ?>
					<br/><input type='text' id='go_pod_link[ <?php echo $pod->slug ?> ]' name='go_task_pod_globals[<?php echo $slug; ?>][go_pod_link]' value='<?php echo ( ! empty( $pods_options[ $slug ][ 'go_pod_link' ] )  ? $pods_options[ $slug ][ 'go_pod_link' ] : '' ); ?>' placeholder='Link'/><br/>
					Must Complete 
					<select id='go_pod_stage_select[<?php echo $pod->slug ?>]' name='go_task_pod_globals[<?php echo $slug; ?>][go_pod_stage_select]'>
						<option <?php echo ( ( $pods_options[ $slug ][ 'go_pod_stage_select' ] == 'third_stage' ) ? 'selected' : '' ); ?> value='third_stage'><?php echo go_return_options( 'go_third_stage_name' ); ?></option>
						<option <?php echo ( ( $pods_options[ $slug ][ 'go_pod_stage_select' ] == 'fourth_stage' ) ? 'selected' : '' ); ?> value='fourth_stage'><?php echo go_return_options( 'go_fourth_stage_name' ); ?></option>
					</select> 
                    of 
                    <input type='number' id='go_pod_number[ <?php echo $pod->slug ?> ]' name='go_task_pod_globals[<?php echo $slug; ?>][go_pod_number]' value='<?php echo ( ! empty( $pods_options[ $slug ][ 'go_pod_number' ] )  ? $pods_options[ $slug ][ 'go_pod_number' ] : 1 ); ?>' style='width : 45px;' max='<?php echo $total ?>'/> <?php echo go_return_options( 'go_tasks_plural_name' ); ?> to continue to 
                    <select id='go_next_pod_select[ <?php echo $pod->slug ?> ]' name='go_task_pod_globals[<?php echo $slug; ?>][go_next_pod_select]'>
                        <option>...</option>
                        <?php
                        foreach ( $pods_array as $pod ) {
                            if ( $pod->slug !== $slug ) {
                                $pod_name = $pod->name;
                                ?>
                                <option <?php echo ( ( $pods_options[ $slug ][ 'go_next_pod_select' ] == $pod_name ) ? 'selected': '...' ); ?> value='<?php echo $pod_name; ?>'><?php echo $pod_name; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    </br>
                    </br>
                    <?php
                }
            }
			?>
	</form>
    <input type="submit" name="Submit" value="Save Pods" />
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="go_task_pod_globals"/>
	</div>
    <?php
}
?>
