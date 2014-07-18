<?php
function go_mail() {
	global $wpdb;
	$dir = plugin_dir_url(__FILE__);
	add_submenu_page('game-on-options.php', 'Email', 'Email', 'manage_options', 'go_mail', 'go_mail_menu');
}

function go_mail_menu() {
	global $wpdb;
	if (!current_user_can('manage_options')) { 
		wp_die(__('You do not have sufficient permissions to access this page.'));
	} else {
		if(isset($_POST['go_mail'])){
			$email = $_POST['go_mail'];
			update_option('go_admin_email', $email);
			} else {
				$email = get_option('go_admin_email','');
			}
		echo "
			<div id='go_option_admin_email_desc'>
				<span>
					Recipient Email:
				</span>
				<a href='javascript:;' class='go_task_opt_help' style='float: inherit !important;' onclick='go_display_help_video(\"http://maclab.guhsd.net/go/video/email/email.mp4\");'>
					?
				</a>
				<form action='' method='post'>
					<input name='go_mail' type='text' style='width: 35%;' value='{$email}'/>
					<input type='submit' value='Submit'/>
				</form>
				<p>
					Paste this shortcode,
					<input type='text' value='[go_upload]' style='width: 95px; text-align: center;' disabled/>
					anywhere you would like to present an upload form for users to upload files.  <b>The files will be sent from a non-replyable address (\"no-reply@go.net\")
					to the address given above.</b>
				</p>
				<p>
					The subject line will contain the uploader's first and last name, username, and the task/post it was uploaded from. The body will contain
					the user's email and any comments that they may leave.
				</p>
			</div>
		";
	}
}

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
	
	if (isset($_FILES['go_attachment'])) {
		$user_id = get_current_user_id();
		$user_info = get_userdata($user_id);
		$username = $user_info->user_login;
		$user_full_name = "{$user_info->first_name} {$user_info->last_name}";
		$user_email = $user_info->user_email;
		$user_role = $user_info->roles;
		$task_title = $post->post_title;
		$task_name = go_return_options('go_tasks_name');
		$to = get_option('go_admin_email','');
		require("mail/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->From = "no-reply@go.net";
		$mail->FromName = $user_full_name;
		$mail->AddAddress($to);
		$mail->Subject = "Upload: {$task_title} | {$user_full_name} {$username}";
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