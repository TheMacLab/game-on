<?php
//New Menu Item
function go_add_toplevel_menu() {
		
	/* add a new menu item */
	add_menu_page(
		'Maps & Menus', // page title
		'Maps & Menus', // menu title
		'edit_posts', // capability
		'maps_menus', // menu slug
		'go_admin_menu_content', // callback function
		'dashicons-location-alt', // icon
		4 // menu position
	);
	
}

add_action( 'admin_menu', 'go_add_toplevel_menu' );

function go_admin_menu_content() {
	
	?>
	
	<div class="wrap">
				
		<h2>Game On Maps and Menus</h2>
		
		<?php
			
			/* your admin pages content would be added here! */	
			
		?>
	
	</div>
	
	<?php
	
}

////Submenus
function go_add_mapmenu_as_submenu() {
	
	/* add the sub menu under content for posts */
	add_submenu_page(
		'maps_menus', // parent slug
		'Maps & Menus', // page_title,
		'Maps & Menus', // menu_title,
		'edit_posts', // capability,
		'maps_menus' // menu_slug,
	);
	
}
add_action( 'admin_menu', 'go_add_mapmenu_as_submenu', 9 );

function go_add_chains_as_submenu() {
	
	/* add the sub menu under content for posts */
	add_submenu_page(
		'maps_menus', // parent slug
		'Maps', // page_title,
		'Maps', // menu_title,
		'edit_posts', // capability,
		'edit-tags.php?taxonomy=task_chains' // menu_slug,
	);
	
}
add_action( 'admin_menu', 'go_add_chains_as_submenu', 9 );


function go_add_topmenus_as_submenu() {
	
	/* add the sub menu under content for posts */
	add_submenu_page(
		'maps_menus', // parent slug
		'Top Menu', // page_title,
		'Top Menu', // menu_title,
		'edit_posts', // capability,
		'edit-tags.php?taxonomy=task_menus' // menu_slug,
	);
	
}
add_action( 'admin_menu', 'go_add_topmenus_as_submenu', 9 );

function go_add_sidebar_as_submenu() {
	
	/* add the sub menu under content for posts */
	add_submenu_page(
		'maps_menus', // parent slug
		'Side Bar', // page_title,
		'Side Bar', // menu_title,
		'edit_posts', // capability,
		'edit-tags.php?taxonomy=task_categories' // menu_slug,
	);
	
}
add_action( 'admin_menu', 'go_add_sidebar_as_submenu', 9 );

////////fix hierarchy
function go_menu_hierarchy_correction( $parent_file ) {
	
	global $current_screen;
	
	/* get the base of the current screen */
	$screenbase = $current_screen->base;
	/* if this is the edit.php base */
	if( $screenbase == 'edit-tags' ) {
		/* set the parent file slug to the custom content page */
		$parent_file = 'maps_menus';
		
	}
	
	/* return the new parent file */	
	return $parent_file;
	
}
add_action( 'parent_file', 'go_menu_hierarchy_correction', 999 );


/*********************
* re-order left admin menu
**********************/
function go_reorder_admin_menu( $__return_true ) {
    return array(
    	'game-on-options.php', //GO options 
    	'edit.php?post_type=tasks', // Quests
    	'maps_menus', //Maps
    	'edit.php?post_type=go_store', //store   	
		'separator1', // --Space--
		'index.php', // Dashboard
		'edit.php?post_type=page', // Pages 
    	'edit.php', // Posts
    	'upload.php', // Media
    	'themes.php', // Appearance
        'separator2', // --Space--
        'edit-comments.php', // Comments 
        'users.php', // Users
        'separator2', // --Space--
        'plugins.php', // Plugins
        'tools.php', // Tools
        'options-general.php', // Settings
   );
}
add_filter( 'custom_menu_order', 'go_reorder_admin_menu' );
add_filter( 'menu_order', 'go_reorder_admin_menu' );



?>
