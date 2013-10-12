<?php
//Task Includes
include('tasks/task.php');

//Store Includes
include('store/super-store.php');

// Meta Boxes
function go_init_mtbxs() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';
}
function go_mta_con_meta( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'go_mta_';
	// Tasks Meta Boxes
	$meta_boxes[] = array(
		'id'         => 'go_mta_metabox',
		'title'      => go_return_options('go_tasks_name').' Options',
		'pages'      => array( 'tasks' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Shortcode',
				'desc' => 'Insert this shortcode where you want the task to appear',
				'type' => 'go_shortcode'
			),
		
			array(
				'name' => 'Required Rank',
				'desc' => 'rank required to begin '.go_return_options('go_tasks_name'),
				'id'   => $prefix . 'req_rank',
				'type' => 'select',
				'options' => go_get_all_ranks()
			),
			array(
				'name' => 'Presets',
				'id'   => 'go_presets',
				'desc'=> '',
				'type' => 'go_presets',
				
			),
			array(
				'name' => 'Points',
				'desc' => 'points awarded for encountering, accepting, completing, and mastering the '.go_return_options('go_tasks_name').'. (comma seperated, e.g. 10,20,50,70)',
				'id'   => $prefix . 'task_points',
				'type' => 'text',
			),
			array(
				'name' => 'Currency',
				'desc' => 'currency awarded for encountering, accepting, completing, and mastering the '.go_return_options('go_tasks_name').'. (comma seperated, e.g. 10,20,50,70)',
				'id'   => $prefix . 'task_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Encounter Message',
				'desc' => 'Enter a message for the user to recieve when they have <i>encountered</i> the '.go_return_options('go_tasks_name'),
				'id' => $prefix . 'quick_desc',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Accept Message',
				'desc' => 'Enter a message for the user to recieve when they have <i>accepted</i> the '.go_return_options('go_tasks_name'),
				'id' => $prefix . 'accept_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
          			'textarea_rows' => '5',
           		),
			),
			array(
				'name' => 'Completion Message (Optional)',
				'desc' => 'Enter a message for the user to recieve when they have <i>completed</i> the '.go_return_options('go_tasks_name'),
				'id' => $prefix . 'complete_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
				),
			array(
				'name' => 'Mastery Message (Optional)',
				'desc' => 'Enter a message for the user to recieve when they have <i>mastered</i> the '.go_return_options('go_tasks_name'),
				'id' => $prefix . 'mastery_message',
				'type' => 'wysiwyg',
        		'options' => array(
           			'wpautop' => true,
           			'textarea_rows' => '5',
         		),
				),
			array(
					'name' => 'Repeatable',
					'desc' => ' Select to make '.go_return_options('go_tasks_name').' repeatable',
					'id'   => $prefix . 'task_repeat',
					'type' => 'checkbox'
				),
			array(
				'name' => 'Repeat Message (Optional)',
				'desc' => 'Enter a message for the user to recieve when they have <i>repeated</i> the '.go_return_options('go_tasks_name'),
				'id' => $prefix . 'repeat_message',
				'type' => 'wysiwyg',
        		'options' => array(
								'wpautop' => true,
								'textarea_rows' => '5',
							),
						
				),
				array(
				'name' => 'Shortcode',
				'desc' => 'Insert this shortcode where you want the task to appear',
				'type' => 'go_shortcode'
			),
			),
		);
	// Store Meta Boxes
	$meta_boxes[] = array(
		'id'         => 'go_mta_metabox',
		'title'      => 'Store Item Options',
		'pages'      => array( 'go_store' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Required Rank',
				'desc' => 'rank required to purchase the item',
				'id'   => $prefix . 'store_rank',
				'type' => 'select',
				'options' => go_get_all_ranks()
			),
			array(
				'name' => 'Currency',
				'desc' => 'currency required to purchase the item',
				'id'   => $prefix . 'store_currency',
				'type' => 'text',
			),
			array(
				'name' => 'Points',
				'desc' => 'points required to purchase item',
				'id'   => $prefix . 'store_points',
				'type' => 'text',
			),
			array(
				'name' => 'Time',
				'desc' => 'time required to purchase item',
				'id'   => $prefix . 'store_time',
				'type' => 'text',
			),
			
				
				
		),
	);
	return $meta_boxes;
}
add_action( 'cmb_render_go_presets', 'go_presets_js' );
add_filter( 'cmb_meta_boxes', 'go_mta_con_meta' );
add_action( 'init', 'go_init_mtbxs', 9999 );



add_action( 'cmb_render_go_presets', 'go_cmb_render_go_presets', 10, 0 );
function go_cmb_render_go_presets() {
    ?><select id="go_presets" onchange="apply_presets();">
                    <option>...</option>
                    <?php
					$presets = get_option('go_presets',false);
					if($presets){
		   foreach($presets as $key=>$value){ 
		  
			 echo '<option value="'.$key.'" points="'.$value[0].'" currency="'.$value[1].'">'.$key.' - '.$value[0].' - '.$value[1].'</option>';  
		   }}
					 ?>
                    </select> <?php
}

add_action( 'cmb_render_go_shortcode', 'go_cmb_render_go_shortcode', 10, 0 );
function go_cmb_render_go_shortcode() {
 echo '<input type="text" value="[go_task id=\''.get_the_id().'\']"';
 
}



add_filter( 'template_include', 'go_tasks_template_function', 1 );

function go_tasks_template_function( $template_path ) {
    if ( get_post_type() == 'tasks' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
			
			
			
            if ( $theme_file = locate_template( array (  'index.php') )
		//$theme_file =	get_page_template()
		 ) {
                $template_path = $theme_file;
				add_filter( 'the_content', 'go_tasks_filter_content' );
            } 
        }
    }
    return $template_path;
}

function go_tasks_filter_content(){
	 global $wpdb;
	 echo do_shortcode('[go_task id="'.get_the_id().'"]');
	 }


