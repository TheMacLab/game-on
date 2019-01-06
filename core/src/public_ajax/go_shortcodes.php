<?php



function go_video_link( $atts, $video_url ) {
	$atts = shortcode_atts( 
		array(
			'video_url' => '',
			'video_title' => ''
		), 
		$atts
	);
	$video_url = ( ! empty ( $video_url ) ? $video_url : $atts['video_url'] );
	$video_title = $atts['video_title'];
	if ( $video_url ) {

		if ( $video_title ) {
			//return "<a href='#'  data-featherlight='<video controls><source src=\"".$video_url."\"></video>'>{$video_title}</a>";
            //return "<a class='featherlight_wrapper_vid_link' href='{$video_url}' data-featherlight='iframe'>{$video_title}</a>";
            return "<a href='#' class='featherlight_wrapper_vid_shortcode' data-featherlight='<div id=\"go_video_container\" style=\"height: 90vh; overflow: hidden; text-align: center;\"> <video controls autoplay style=\"height: 100%; max-width: 100%;\"><source src=\"{$video_url}\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>'  data-featherlight-close-on-esc='true' data-featherlight-variant='fit_and_box native2' >{$video_title}</a> ";
		} else {
            return "<a href='#' class='featherlight_wrapper_vid_shortcode' data-featherlight='<div id=\"go_video_container\" style=\"height: 90vh; overflow: hidden; text-align: center;\"> <video controls autoplay style=\"height: 100%; max-width: 100%;\"><source src=\"{$video_url}\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>'  data-featherlight-close-on-esc='true' data-featherlight-variant='fit_and_box native2' >Video</a> ";

        }

	}
}
add_shortcode( 'go_display_video', 'go_video_link' );
add_shortcode( 'go_video_link', 'go_video_link' );

function go_video($atts){
    extract(shortcode_atts(array(
        'video_url' => ''
    ), $atts));


    $video_url = $atts['video_url'];;

    $lightbox = "[video mp4=" . $video_url . "][/video]";
    //<video class='wp-video-shortcode' preload='metadata' src='{$video_url }?_=1' style='width: 200px;'><source src='{$video_url }?_=1'><a href='{$video_url }'>{$video_url }</a></video>

    return do_shortcode($lightbox);

}
add_shortcode( 'go_video','go_video' );

function go_lightbox_url($atts){
    extract(shortcode_atts(array(
        'link_url' => '',
        'link_text' => ''
    ), $atts));

    $link_text = $atts['link_text'];
    $link_url = $atts['link_url'];;

    $lightbox = "<a href='{$link_url}' data-featherlight='iframe' data-featherlight-iframe-height='100%' data-featherlight-iframe-width='100%'>{$link_text}</a>";
    return $lightbox;

}
add_shortcode( 'go_lightbox_url','go_lightbox_url' );

//Gets the user's display name
function go_get_displayname_function( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		return "<span id='go-displayname'>{$current_user->display_name}</span>";
	} else { 
		return '<span id="go-visitor">Visitor</span>'; 
	}
}
add_shortcode( 'get_displayname', 'go_get_displayname_function' );
add_shortcode( 'go_get_displayname', 'go_get_displayname_function' );

//Gets the users first name
function go_get_firstname_function( $atts, $content = null ) {
	if (is_user_logged_in() ) {
		$current_user = wp_get_current_user();
	    return "<span id='go-firstname'>{$current_user->user_firstname}</span>";
	} else { 
		return '<span id="go-visitor">Visitor</span>'; 
	}
}
add_shortcode( 'go_firstname', 'go_get_firstname_function' );

//Gets the users last name
function go_get_lastname_function( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
	    return "<span id='go-lastname'>{$current_user->user_lastname}</span>";
	} else { 
		return '<span id="go-visitor">Visitor</span>'; 
	}
}
add_shortcode( 'go_lastname', 'go_get_lastname_function' );

//Gets the users login
function go_get_login_function( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
	    $current_user = wp_get_current_user();
	    return "<span id='go-username'>{$current_user->user_login}</span>";
	} else { 
		return '<span id="go-visitor">Visitor</span>'; 
	}
}
add_shortcode( 'go_loginname', 'go_get_login_function' );

// creates shortcode for page grab function
add_shortcode( 'page_grab', 'go_page_grabber_shortcode' );
add_shortcode( 'go_page_grab', 'go_page_grabber_shortcode' );

//Creates an excerpt for grabbed post
function go_post_grabber_content_exerpt_shortcode() {
    echo '';
    query_posts( 'showposts=2' );
    while ( have_posts() ) : the_post();
        ?>
        <h5>
            <a href="<?php the_permalink() ?>">
                <?php the_title(); ?>
            </a>
        </h5>
        <?php
        the_excerpt( __( '(more?)' ) );
    endwhile;
}
// creates shortcode for post grab function
add_shortcode( 'post_grab', 'go_post_grabber_shortcode' );
add_shortcode( 'go_post_grab', 'go_post_grabber_shortcode' );

//Adds a link to the most recent post
function go_latest_post_url_shortcode( $atts, $content = null ) { 
	$atts = shortcode_atts(
		array(  
			"cat" => '',
			"usetitle" => 'yes'   
		), 
		$atts
	);
	$catquery = new WP_Query( "cat={$atts['cat']}&posts_per_page=1" );
	$usetitle = $atts['usetitle'];
	while( $catquery->have_posts () ) : $catquery->the_post();
	?>
		<a href="<?php the_permalink(); ?>">
			<?php 
			if ( $usetitle = "yes" ) {
				the_title();
			} else { 
				return '';
			}
			?>
		</a>
	<?php 
	endwhile;
}
add_shortcode ( 'latest_post', 'go_latest_post_url_shortcode' );
add_shortcode ( 'go_latest_post', 'go_latest_post_url_shortcode' );

//Makes content within tags only visible to people who aren't logged in
function go_visitor_only_content_function( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
    	echo '';
	} else {
    	return '<div id="visitor-only-content">'.do_shortcode( $content ).'</div>';
	}
}
add_shortcode ( 'visitor_only_content', 'go_visitor_only_content_function' );
add_shortcode ( 'go_visitor_only_content', 'go_visitor_only_content_function' );

//Makes content within tags visible to only people who are logged in  
function go_user_only_content_function( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
    	return '<div id="user-only-content">'.do_shortcode( $content).'</div>';
	} else {
    	return '';
	}
}
add_shortcode ( 'user_only_content', 'go_user_only_content_function' );
add_shortcode ( 'go_user_only_content','go_user_only_content_function' );

//Makes content within tags visible to admins only
function go_admin_only_content_function( $atts, $content = null ) {
	if ( current_user_can( 'manage_options' ) ) {
		return '<div id="admin-only-content" style="color:red"> <i>' .do_shortcode( $content). '</i> </div>';
	} else {
		return '';
	}
}
add_shortcode ( 'admin_only_content', 'go_admin_only_content_function' );
add_shortcode ( 'go_admin_only_content', 'go_admin_only_content_function' );

//Adds the ability to put a login box anywhere it is needed
function go_login( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			"size" => 'medium',
			"redirect" => 'current',
			"lostpass" => 'yes'
		), 
		$atts
	);
	
	// Define Redirects for Login/Logout
	switch ( $atts['redirect'] ) {
		case "current":
			$current_page_logout = wp_logout_url( get_permalink() );
			$current_page_login  = $_SERVER["REQUEST_URI"];
			break;
		case 'dashboard':
			$current_page_logout = wp_logout_url();
			$current_page_login  = wp_login_url();
			break;
		case 'homepage':
			$current_page_logout = wp_logout_url( home_url() );
			$current_page_login  = wp_login_url( home_url() );
			break;
	}
	
	// End Define Redirects
	// Define Size 
	if ( $atts['size'] == 'medium' ) {
		$input_size = '20';
	} elseif ( $atts['size'] == 'small' ) {
		$input_size = '10';
	} elseif ( $atts['size'] == 'large' ) {
		$input_size = '30';
	} else {
		$input_size = '20';
	}
	//End Define Size
	
	// Begin Form
	if ( is_user_logged_in() ) { 
	?>
		<a class="submit" href="<?php echo ( $current_page_logout ); ?>" title="Logout">Logout</a>
    <?php
	} else {
		?>
		<form name="loginform" id="loginform" action="<?php echo get_option( 'home' ); ?>/wp-login.php" method="post">
		<p>
			<label>Username</br>
				<input type="text" name="log" id="user_login" class="input" value="" size="<?php echo $input_size; ?>" tabindex="10"/>
			</label>
		</p>
		<p>
			<label>Password</br>
				<input type="password" name="pwd" id="user_pass" class="input" value="" size="<?php echo $input_size; ?>" tabindex="20"/>
			</label>
		</p>
		<p class="forgetmenot">
			<label>
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> Remember Me
			</label>
		</p>
		<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In" tabindex="100"/>
		<input type="hidden" name="redirect_to" value="<?php echo ( $current_page_login); ?>"/>
		<input type="hidden" name="testcookie" value="1"/>
		</form>
		<?php 	
		if ( $atts['lostpass'] == true ) { 
		?>
				<a href="<?php echo get_option( 'home' ); ?>/wp-login.php?action=lostpassword" title="Password Lost and Found">Lost your password?</a>
		<?php 
		} elseif ( $atts['lostpass'] == false ) { 
			echo ''; 
		} else {
		?> 
			<a href="<?php echo get_option( 'home' ); ?>/wp-login.php?action=lostpassword" title="Password Lost and Found">Lost your password?</a>
		<?php 
		} 
	}
}
add_shortcode ( 'sb_login', 'go_login' );
add_shortcode ( 'go_login', 'go_login' );

function go_store_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'id'   => '',
		),
		$atts,
		'go_store'
	);
	$output = '';

	if ( ! empty( $atts['id'] ) ) {

		/**
		 * Outputs an individual link, for the store item with the specified post ID, which contains
		 * the title of the Store Item.
		 */

		$output .= sprintf(
			'<a id="%s" class="go_str_item">%s</a>',
			$atts['id'],
			get_the_title( $atts['id'] )
		);
	}

	return $output;
}
add_shortcode( 'go_store', 'go_store_shortcode' );



?>