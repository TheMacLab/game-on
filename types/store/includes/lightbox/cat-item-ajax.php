<?php
if ( is_admin () ) {	
	function go_cat_item() {
		$the_id = $_POST["the_item_id"]; 
		echo 'hello';
		die(); 
	}
}
?>