<?php
$the_id = $_GET['post'];
$the_stupid_type = get_post_type( $the_id );
if ($the_stupid_type == 'tasks') {
function good_bye() {
	$the_post_id = $_GET['post'];
	echo '<p id="bye">Display this task: [go_task id="' .$the_post_id.'"]</p>';
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'good_bye' );

// We need some CSS to position the paragraph
function bye_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#bye {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'bye_css' );
}
?>