<?php
add_action( 'admin_menu', 'go_pod_submenu' );
function go_pod_submenu () {
	add_submenu_page( 'game-on-options.php', 'Pods', 'Pods', 'manage_options', 'go_pods', 'go_task_pods' );
}
function go_task_pods () {
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
    <h2>Pods</h2>
    <form method="post" action="" id="go_pod_form">    
            <ul>
            <?php
            foreach ( get_categories( $args ) as $category ) {
                $pods_array[] = $category;
            }
            foreach ($pods_array as $pod ) {
                global $post;
                $terms = wp_get_post_terms( $post->ID, 'task_pods' );
                for ( $i = 0; $i <= count($terms); $i++ ) {
					$pod_id = 307;//need to fix
                    $link = get_category_link( $pod );
					$slug = $pod->slug;
                    echo "<b><a href='{$link}' target='_blank'>".$pod->name."</a></b>";
                    ?>
					<br/><input type='text' id='go_pod_link[ <?php echo $pod->slug ?> ]' name='go_pod_link[]' value='<?php echo ( !empty( $pods_options[ $pod_id ][ 'pod_link' ][ $slug ] ) ) ? $pods_options[ $pod_id ][ 'pod_link' ][ $slug ]: '' ; ?>' placeholder='Link'/><br/>
					Must Complete 
					<select id='go_pod_stage_select[<?php echo $pod->slug ?>]' name='go_pod_stage_select[]'>
						<option <?php echo ( $pods_options[ $pod_id ][ 'stage_required' ][ $slug ] == 'third_stage' ) ? 'selected': '' ; ?> value='third_stage'><?php echo go_return_options( 'go_third_stage_name' ); ?></option>
						<option <?php echo ( $pods_options[ $pod_id ][ 'stage_required' ][ $slug ] == 'fourth_stage' ) ? 'selected': '' ; ?> value='fourth_stage'><?php echo go_return_options( 'go_fourth_stage_name' ); ?></option>
					</select> 
                    of 
                    <input type='number' id='go_pod_number[ <?php echo $pod->slug ?> ]' name='go_pod_number[]' value='<?php echo ( !empty( $pods_options[ $pod_id ][ 'tasks_required' ][ $slug ] ) ) ? $pods_options[ $pod_id ][ 'tasks_required' ][ $slug ]: 1 ; ?>' style='width: 45px;'/> <?php echo go_return_options( 'go_tasks_plural_name' ); ?> to continue to 
                    <select id='go_next_pod_select[ <?php echo $pod->slug ?> ]' name='go_next_pod_select[]'>
                        <option>...</option>
                        <?php
                        foreach ( $pods_array as $pod ) {
                            if ( $pod->slug !== $slug ) {
                                $pod_name = $pod->name;
                                ?>
                                <option <?php echo ($pods_options[ $pod_id ][ 'next_pod' ][ $slug ] == $pod_name) ? 'selected': '...' ; ?> value='<?php echo $pod_name; ?>'><?php echo $pod_name; ?></option>
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
    <!-- <input type="submit" name="Submit" value="Save Pods" onClick="go_submit_pods();"/> -->
	<input type="button" name="Submit" id="pod_submit" value="Save" onClick="go_submit_pods();" />
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="go_pod_link, go_pod_stage_select, go_pod_number, go_next_pod_select" />
	</div>
    <?php
}

function go_update_pods () {
	$go_pod_link = $_POST[ 'go_pod_link' ];
	$go_pod_stage_select = $_POST[ 'go_pod_stage_select' ];
	$go_pod_number = $_POST[ 'go_pod_number' ];
	$go_next_pod_select = $_POST[ 'go_next_pod_select' ];
	$pods_array = array(
		'pod_link'   => $go_pod_link,
		'pod_stage'  => $go_pod_stage_select,
		'pod-number' => $go_pod_number,
		'next_pod'   => $go_next_pod_select
	);
	update_option( 'go_task_pod_globals', $pods_array );
	die();
}

/*add_action('cmb_validate_go_task_pod', 'go_validate_task_pod');
function go_validate_task_pod () {
	$task_id = get_the_id();
	$stage_required = $_POST['go_pod_stage_select'];
	$tasks_required = $_POST['go_pod_number'];
	$next_pod = $_POST['go_next_pod_select'];
	$pod_link = $_POST['go_pod_link'];
	$task_pod_info = array(
		'stage_required' => $stage_required, 
		'tasks_required' => $tasks_required, 
		'next_pod' => $next_pod, 
		'pod_link' => $pod_link
	);
	$task_pod_globals = array();
	$task_pod_globals = get_option('go_task_pod_globals');
	$task_pod_globals[ $task_id ] = $task_pod_info;
	update_option('go_task_pod_globals', $task_pod_globals);
	return $task_pod_info;
}*/
?>
