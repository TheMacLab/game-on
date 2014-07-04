<?php
function go_mail() {
	global $wpdb;
	$dir = plugin_dir_url(__FILE__);
	add_submenu_page( 'game-on-options.php', 'Email', 'Email', 'manage_options', 'go_mail', 'go_mail_menu');
}

function go_mail_menu() {
	global $wpdb;
	if (!current_user_can('manage_options'))  { 
		wp_die( __('You do not have sufficient permissions to access this page.') );
	} 
	else{
		if(isset($_POST['go_mail'])){
			$email = $_POST['go_mail'];
			update_option('go_admin_email', $email);
			} else {
				$email = get_option('go_admin_email','');
				}
		echo '<span">Recipient Email:<span> <a href="javascript:;" class="go_task_opt_help" style="float: inherit !important;"onclick="go_display_help_video(\'http://maclab.guhsd.net/go/video/email/email.mp4\');">?</a> <form action="" method="post">
        <textarea name="go_mail">'.$email.'</textarea>
        <input type="submit" value="Submit"/>
        </form> <br/>
		Paste this shortcode: <input type="text" value="[go_upload]" disabled/>
		This will display an upload box for files and a text-box for additional comments the user has. It will send an Email with the file as an attachment. The message will be from \"no-replay@go.net\" with the first and last name of the student. The subject will be the page title where this was sent from. The message will contain the addition comments and the student\'s login.';
		}
}

//Shortcode for Email input
add_shortcode('go_upload','go_file_input');
function go_file_input () {
	global $wpdb;
	global $post;
	$form = "
		<form id='go_upload_form' action='' method='post' enctype='multipart/form-data'>
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
				return "<div id='go_mailer_error_msg'>Message was not sent.</div>";
			}
		} else {
			return "<div id='go_mailer_confirm_msg'>Message was sent.</div>";
		}
	} else {
		return $form;
	}
}
?>