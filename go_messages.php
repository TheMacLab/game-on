<?php
add_action('admin_bar_init','go_messages_bar');

function go_messages_bar(){
	global $wpdb;
	global $wp_admin_bar;
	$messages = get_user_meta(get_current_user_id(), 'go_admin_messages',true);
	if((int)$messages[0] > 0){
		$style = 'background: -webkit-radial-gradient( 5px -9px, circle, white 8%, red 26px );';
		} else {
			$style = 'background: -webkit-radial-gradient( 5px -9px, circle, white 8%, green 26px );';
			$wp_admin_bar->add_menu( array(
			'title' => 'You have no messages from admin',
			'href' => '#',
			'parent' => 'go_messages'
		));
			}
	if (!is_admin_bar_showing() || !is_user_logged_in() )
		return;
		$wp_admin_bar->add_menu( array(
			'title' => '<div style="padding-top:5px;"><div id="go_messages_bar" style="'.$style.'">'.(int)$messages[0].'</div></div>',
			'href' => '#',
			'id' => 'go_messages',
		));
		if(!empty($messages[1])){
			foreach($messages[1] as $date=> $values){
				$style = '';
				if((int)$values[1] == 1){
					$style = 'color: rgba(255, 215, 0, .4);';
					}
				$wp_admin_bar->add_menu( array(
			'title' => '<div style="'.$style.'">'.substr($values[0],0,20).'...</div>',
			'href' => '#',
			'id' => $date,
			'parent' => 'go_messages'
		));
		$wp_admin_bar->add_menu( array(
			'title' => date('m-d-Y',$date).' - <a onClick="go_mark_seen('.$date.',\'unseen\');" style="display:inline;" href="#">Mark seen</a>'.' - <a onClick="go_mark_seen('.$date.',\'remove\');" style="display:inline;" href="#">Remove</a>',
			'parent' => $date,
			'meta' => array('html' =>  '<div style="width:350px;">'.$values[0].'</div>'),
			'id' => rand()
		));
				}
			}
	}
add_action('wp_ajax_go_mark_read','go_mark_read');
function go_mark_read(){
	global $wpdb;
	$messages = get_user_meta(get_current_user_id(), 'go_admin_messages',true);
	if($_POST['type'] == 'unseen'){
		if($messages[1][$_POST['date']][1] == 1){
	$messages[1][$_POST['date']][1] = 0;
	(int)$messages[0] = (int)$messages[0] - 1;
		}
	} elseif($_POST['type'] == 'remove') {
		if($messages[1][$_POST['date']][1] == 1){
	(int)$messages[0] = (int)$messages[0] - 1;
		}	
		unset($messages[1][$_POST['date']]);
	}
	update_user_meta( get_current_user_id(), 'go_admin_messages', $messages);
	echo JSON_encode(array(0 => $_POST['date'], 1 => $_POST['type'], 2 => $messages[0]));
	die();
	}
?>