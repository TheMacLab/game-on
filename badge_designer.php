<?php
/*
Description: A hook into the OpenBadges.me badge designer tool to create badge graphics directly into your media library.
Version: 1.0.1
Author: Dave Waller (MyKnowledgeMap)
Author URI: http://www.myknowledgemap.com/
License: GPL2
*/

/*  Copyright 2013  Dave Waller  (email : dave.waller@myknowledgemap.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Add hooks for the plugin to use... */
add_action('admin_menu', 'bd_create_menu');

/* Create the badge designer menu item as a child of "media"... */
function bd_create_menu(){
	global $wpdb;
$dir = plugin_dir_url(__FILE__);
 add_submenu_page( 'game-on-options.php', 'Badge Designer', 'Badge Designer', 'manage_options', 'bd_create_menu', 'badge_designer');
}

/* Create the badge designer page... */
function badge_designer(){

	/* Get data about the current user... */
	global $current_user;
	get_currentuserinfo();
	
	/* Has a badge been created? */
	if($_POST['targetImage'] != ''){
		
		/* Create a directory to hold temporary image files... */
		if (!is_dir(dirname(__FILE__) . '/temp_images')){
			mkdir(dirname(__FILE__) . '/temp_images/');
			chmod(dirname(__FILE__) . '/temp_images/', 0777);
		}
		
		/* Save a temp version of the badge based on the base64 supplied... */
		define('UPLOAD_DIR', dirname(__FILE__) . '/temp_images/');
		$img = $_POST['targetImage'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . uniqid() . '.png';
		$success = file_put_contents($file, $data);
		//echo '<hr/>Temp image created: ';print_r($success);
		
		/* Create a $_FILES based array... */
		$file_array['tmp_name'] = $file;
		$file_array['name'] = 'badge.png'; 
		//echo '<hr/>Files array created: ';print_r($file_array);

		/* Include required files... */
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		
		/* Move the file to the uploads directory... */
		$uploadedfile = $file_array;
		$upload_overrides = array('test_form' => false);
		$movefile = wp_handle_sideload($uploadedfile, $upload_overrides);
		
		/* If the move was successful... */
		if ($movefile){
			/* Remove the temp image file... */
			@unlink($file_array['tmp_name']);
			/* Generate image metadata... */
			$wp_filetype = wp_check_filetype(basename($movefile['file']), null);
			$wp_upload_dir = wp_upload_dir();
			$attachment = array(
				'guid' => $wp_upload_dir['url'] . '/' . basename( $movefile['file']), 
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
				'post_content' => 'fasd',
				'post_status' => 'inherit',
				
			);
			/* Add the image into the media library... */
			
			$attach_id = wp_insert_attachment($attachment, $movefile['file']);
			$attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
			$attach_data['go_category'] = 'badge';
			wp_update_attachment_metadata($attach_id, $attach_data);
			//echo "<hr/>File is valid, and was successfully uploaded.\n";print_r( $movefile);
			
			/* Redirect to media library */
		echo '<script>window.location.replace("'.get_site_url().'/wp-admin/post.php?post='.$attach_id.'&action=edit");</script>';
		} else {
		
			/* something went wrong... */
			echo "An error occured - possible file upload attack!\n";
		}

	}else{ ?>
		<div class="wrap">
			<iframe name="if" id="if" style="margin-top:5px;" src="https://www.openbadges.me/designer.html?origin=<?php echo get_site_url(); ?>&email=<?php echo $current_user->user_email; ?>" height="670" width="100%">
			</iframe>
			<script>
				window.onmessage = function(e){
					if(e.origin=='https://www.openbadges.me'){
						if(e.data!='cancelled'){
							document.getElementById('targetImage').value = e.data;
							document.getElementById('imageForm').submit();
						}
					}
				};
			</script>
			<form id="imageForm" method="POST" action="" enctype="multipart/form-data">
				<input type="hidden" name="targetImage" id="targetImage"/>
			</form>
		</div>
	<?php } ?>
<?php }

?>