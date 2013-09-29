<?php


include('the_lightbox.php');
$item_id = $_GET["post"];
$go_post_type = get_post_type($item_id);
if ($go_post_type === 'tasks') {
function go_task_insert_bttn( $arg, $post_id ){
	global $is_resetable;
	if( ereg('edit-slug', $arg) ){
		$is_resetable = true;
		$arg .= '<span id="edit-slug button button-small hide-if-no-js"><a href="javascript:void(0);" onclick="tsk_admn_opnr();" class="button button-small" >Add to other</a></span> ';
	}
	return $arg;
} 
add_filter( 'get_sample_permalink_html', 'go_task_insert_bttn',5,2 );
////////////////////////////////////////////////////////////////////////////////

function go_task_insert_link( $arg, $post_id ){
	global $is_resetable;
	global $wpdb;
if( ereg('edit-slug', $arg) ){
		$is_resetable = true;
		$go_table_posts = $wpdb->prefix.'posts';
		$go_page_ids = (int)$wpdb->get_var("SELECT `ID` FROM ".$go_table_posts." WHERE (`post_content` like '%go_task id=\"".$post_id."\"%' or `post_content` like '%go_task id=\'".$post_id."\'%') order by id asc limit 1");
		$the_create_new_url = $the_admin_url.'post-new.php?&action=edit&post_type=page&go_tsk_id='.$post_id;
		$option = '<a class="button button-small"  href="'.post_permalink( $go_page_ids ) .'">View Page</a>';
		$arg .= '<span id="edit-slug button button-small"><a class="button button-small" href="'.$the_create_new_url.'">Add To New Page</a></span><span id="edit-slug button button-small">'.$option.'</span>';
		}
		
return $arg;
} 
add_filter( 'get_sample_permalink_html', 'go_task_insert_link',5,2 );

/////////////////////////////////////////////////////////////////////////////////
} else {
// Includes
include('pop-task.php');
function go_task_insert_bttn( $arg, $post_id ){
	global $is_resetable;
	if( ereg('edit-slug', $arg) ){
		$is_resetable = true;
		$arg .= '<span id="edit-slug button button-small hide-if-no-js"><a href="javascript:void(0)" onclick="tsk_admn_opnr();" class="button button-small" >Insert Task</a></span> ';
	}
	return $arg;
} 
add_filter( 'get_sample_permalink_html', 'go_task_insert_bttn',5,2 );
}
?>