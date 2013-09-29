<?php 
add_filter( 'media_upload_tabs', 'go_media_upload_tab_name' );
add_action( 'media_upload_tab_create_badge', 'badge_designer' );
add_filter( 'attachment_fields_to_edit', 'go_badge_add_attachment', 2, 2 );
add_shortcode('go_award_badge', 'go_award_badge');
function go_media_upload_tab_name( $tabs ) {
    $newtab = array( 'tab_create_badge' => 'Create Badge' );
    return array_merge( $tabs, $newtab );
}

function go_media_badge_list( $tabs ) {
    $newtab = array( 'tab_badge_list' => 'Badges List' );
    return array_merge( $tabs, $newtab );
}



  function go_badge_add_attachment( $form_fields, $post ) {
    $form_fields['location'] = array(
        'value' => '[go_award_badge id="'.$post->ID.'" repeat = "off"]',
        'label' => __( 'Shortcode' ),
		'input'       => 'text'
    );
    return $form_fields;
	
}

function go_award_badge($atts){
	global $wpdb;
	$id = $atts['id'];
	$repeat = $atts['repeat'];
	$display = wp_get_attachment_image( $id, array(200,200), false );
	$user_ID = get_current_user_id();
	$existing_badges = get_user_meta($user_ID, 'go_badges', true);
	if($repeat == 'off'){
		if(!in_array($id, $existing_badges)){
			$existing_badges[] = $id;
			 update_user_meta($user_ID, 'go_badges', $existing_badges);
			 echo '<div id="go_notification_badges" class="go_notification_badges" style="right: 250px">'.$display.'</div><script type="text/javascript" language="javascript">jQuery(".go_notification_badges").fadeIn(200);
	setTimeout(function(){
		jQuery(".go_notification_badges").fadeOut("slow");
	},2000) </script>';

			}
		} else { $existing_badges[] = $id;
					 update_user_meta($user_ID, 'go_badges', $existing_badges);
					 echo '<div id="go_notification_badges" class="go_notification_badges" style="top: '.$space.'px">'.$display.'</div><script type="text/javascript" language="javascript">jQuery(".go_notification_badges").fadeIn(200);
	setTimeout(function(){
		jQuery(".go_notification_badges").fadeOut("slow");
	},2000) </script>';

		}
	
	
	}




?>