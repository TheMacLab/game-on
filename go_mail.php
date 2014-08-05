<?php

//Shortcode for Email input
add_shortcode('go_upload','go_file_input');
function go_file_input ($atts, $content = null) {
	extract(shortcode_atts(array(
		'is_uploaded' => '0',
		'status' => '1',
		'user_id' => null,
		'post_id' => null
	), $atts));
	global $wpdb;
	global $post;
	$table_go = "{$wpdb->prefix}go";
	switch ($status) {
		case (0):
			$db_task_stage_upload_var = 'e_uploaded';
			break;
		case (1):
			$db_task_stage_upload_var = 'a_uploaded';
			break;
		case (2):
			$db_task_stage_upload_var = 'c_uploaded';
			break;
		case (3):
			$db_task_stage_upload_var = 'm_uploaded';
			break;
		case (4):
			$db_task_stage_upload_var = 'r_uploaded';
			break;
	}
	if (empty($user_id) || is_null($user_id)) {
		$user_id = get_current_user_id();
	}
	if (empty($post_id) || is_null($post_id)) {
		$post_id = $post->ID;
	}
	$allow_full_name = get_option('go_full_student_name_switch');
	
	if (isset($_FILES['go_attachment'])) {
		$user_id = get_current_user_id();
		$user_info = get_userdata($user_id);
		$user_login = $user_info->user_login;
		$first_name = trim($user_info->first_name);
		$last_name = trim($user_info->last_name);
		if ($allow_full_name == 'On') {
			$user_name = "{$first_name} {$last_name}";
		} else {
			$last_initial = substr($last_name, 0, 1);
			$user_name = "{$first_name} {$last_initial}.";
		}
		$user_email = $user_info->user_email;
		$user_role = $user_info->roles;
		$task_title = $post->post_title;
		$task_name = go_return_options('go_tasks_name');
		$to = get_option('go_admin_email','');
		require("mail/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->From = "no-reply@go.net";
		$mail->FromName = $user_name;
		$mail->AddAddress($to);
		$mail->Subject = "Upload: {$task_title} | {$user_name} {$user_login}";
		$mail->Body = "{$user_email}\n\nUser comments: \n\t{$_POST['go_attachment_com']}";
		$mail->WordWrap = 50;

		// This loop will upload all the files you have attached to your email. 
		for ($i=0; $i < count($_FILES['go_attachment']); $i++) { 
			$name=$_FILES['go_attachment']['name'][$i];
			$path=$_FILES['go_attachment']['tmp_name'][$i];
			//And attach it using attachment method of PHPmailer.
			$mail->AddAttachment($path, $name); 
		}
		if(!$mail->Send()) {
			if ((is_array($user_role) && in_array('administrator', $user_role)) || $user_role === 'administrator') {
				return "<div id='go_mailer_error_msg'>{$mail->ErrorInfo}</div>";
			} else {
				return "
					<div id='go_mailer_error_msg'>Message was not sent.</div>
					<form id='go_upload_form' action='' method='post' enctype='multipart/form-data' uploaded='0'>
						<div id='go_uploader'>
							<input type='file' name='go_attachment[]'/>
							<br/>
						</div>
						<button type='button' onClick='go_add_uploader();'>Attach More</button><br/>
						Comments:
						<br/>
						<textarea name='go_attachment_com' style='width: 50%; height: 100px; resize: vertical;' placeholder='Enter any comments you have...'></textarea>
						<br/>
						<input type='submit' value='Submit'/>
					</form>
				";
			}
		} else {
			$wpdb->update($table_go, array($db_task_stage_upload_var => 1), array('uid' => $user_id, 'post_id' => $post_id));
			return "
				<div id='go_mailer_confirm_msg'>Message was sent.</div>
				<form id='go_upload_form' action='' method='post' enctype='multipart/form-data' uploaded='1'>
					<div id='go_uploader'>
						<input type='file' name='go_attachment[]'/>
						<br/>
					</div>
					<button type='button' onClick='go_add_uploader();'>Attach More</button><br/>
					Comments:
					<br/>
					<textarea name='go_attachment_com' style='width: 50%; height: 100px; resize: vertical;' placeholder='Enter any comments you have...'></textarea>
					<br/>
					<input type='submit' value='Submit'/>
				</form>
			";
		}
	} else {
		return "
			<form id='go_upload_form' action='' method='post' enctype='multipart/form-data' uploaded='{$is_uploaded}'>
				<div id='go_uploader'>
					<input type='file' name='go_attachment[]'/>
					<br/>
				</div>
				<button type='button' onClick='go_add_uploader();'>Attach More</button><br/>
				Comments:
				<br/>
				<textarea name='go_attachment_com' style='width: 50%; height: 100px; resize: vertical;' placeholder='Enter any comments you have...'></textarea>
				<br/>
				<input type='submit' value='Submit'/>
			</form>
		";
	}
}
?>