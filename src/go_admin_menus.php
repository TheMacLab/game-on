<?php

/**
 * Add GO Options Page using ACF
 */
// add sub page
acf_add_options_page(array(
    'page_title' => 'Options',
    'menu_slug' => 'go_options',
    'capability' => 'edit_posts',
    'icon_url'  => 'dashicons-admin-settings'
    //'parent_slug' 	=> 'game-on',
));


/**
 * re-order left admin menu
 */

function go_reorder_admin_menu( ) {
    return array(
        'game-on', //GO heading
        'go_options', //GO options
        'go_clipboard', //GO clipboard
        'edit.php?post_type=tasks', // Quests
        'edit-tags.php?taxonomy=task_chains', //Maps
        'edit.php?post_type=go_store', //store
        'badges', //badges
        'users.php', // Users
        'groups',
        'separator1', // --Space--
        'index.php', // Dashboard
        'edit.php?post_type=page', // Pages
        'edit.php', // Posts
        'upload.php', // Media
        'themes.php', // Appearance
        'separator2', // --Space--
        'edit-comments.php', // Comments
        //'users.php', // Users
        'separator3', // --Space--
        'plugins.php', // Plugins
        'go_tools.php', // Tools
        'options-general.php', // Settings
    );
}
add_filter( 'custom_menu_order', 'go_reorder_admin_menu' );
add_filter( 'menu_order', 'go_reorder_admin_menu' );


/**
 * Add new top level menus
 */
function go_add_toplevel_menu() {

    /* add a new menu item */
    add_menu_page(
        'Game On',
        'GAME ON',
        'manage_options',
        'game-on',
        'go_admin_game_on_menu_content',
        'dashicons-admin-tools',
        ''
    );


    /* add a new menu item */
    add_menu_page(
        'Clipboard',
        'Clipboard',
        'manage_options',
        'go_clipboard',
        'go_clipboard_menu',
        'dashicons-clipboard', // icon
        4
    );

    /* add a new menu item */
    add_menu_page(
        'Maps & Menus', // page title
        'Maps & Menus', // menu title
        'edit_posts', // capability
        'edit-tags.php?taxonomy=task_chains', // menu slug
        '', // callback function
        'dashicons-location-alt', // icon
        4 // menu position
    );

    /* add a new menu item */
    $badges_name = get_option('options_go_badges_name');
    add_menu_page(
        $badges_name, // page title
        $badges_name, // menu title
        'edit_posts', // capability
        'badges' // menu slug
    //'', // callback function
    //'', // icon
    // 4 // menu position
    );

    /* add a new menu item */
    add_menu_page(
        'User Groups', // page title
        'User Groups', // menu title
        'edit_posts', // capability
        'groups', // menu slug
        '', // callback function
        '', // icon
        4 // menu position
    );
}
add_action( 'admin_menu', 'go_add_toplevel_menu' );


/**
 * Add sub menus
 */

function go_about_go_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'game-on', // parent slug
        'About Game On', // page_title,
        'About Game On', // menu_title,
        'edit_posts', // capability,
        'admin.php?page=game-on', // menu_slug,
        'go_admin_tools_menu_content' // callback function
    );

}
add_action( 'admin_menu', 'go_about_go_as_submenu', 9 );

function go_tools_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'game-on', // parent slug
        'Tools', // page_title,
        'Tools', // menu_title,
        'edit_posts', // capability,
        'admin.php?page=tools', // menu_slug,
        'go_admin_tools_menu_content' // callback function
    );

}
add_action( 'admin_menu', 'go_tools_as_submenu', 10);

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

function go_add_badges_sub_menus() {
    $badges_name = get_option('options_go_badges_name');

    // add the sub menu under content for posts */
    add_submenu_page(
        'badges', // parent slug
        'Manage ' . $badges_name, // page_title,
        'Manage ' . $badges_name, // menu_title,
        'edit_posts', // capability,
        'edit-tags.php?taxonomy=go_badges' // menu_slug,
    );
}
add_action( 'admin_menu', 'go_add_badges_sub_menus', 9 );

/* function go_add_badges_sub_menus2() {


      add the sub menu under content for posts
     add_submenu_page(
         'badges', // parent slug
         'About Badges', // page_title,
         'About Badges', // menu_title,
         'edit_posts', // capability,
         'admin.php?page=about-badges', // menu_slug
         'go_admin_badges_menu_content'// callback function
     );

 }
 add_action( 'admin_menu', 'go_add_badges_sub_menus2', 10 );
 */
function go_add_groups_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'groups', // parent slug
        'Manage Groups', // page_title,
        'Manage Groups', // menu_title,
        'edit_posts', // capability,
        'edit-tags.php?taxonomy=user_go_groups' // menu_slug,
    );

}
add_action( 'admin_menu', 'go_add_groups_as_submenu', 9 );

function go_add_chains_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'edit-tags.php?taxonomy=task_chains', // parent slug
        'Quest Maps', // page_title,
        'Quest Maps', // menu_title,
        'edit_posts', // capability,
        'edit-tags.php?taxonomy=task_chains' // menu_slug,
    );

}
add_action( 'admin_menu', 'go_add_chains_as_submenu', 9 );

function go_add_topmenus_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'edit-tags.php?taxonomy=task_chains', // parent slug
        'Top Menu', // page_title,
        'Top Menu', // menu_title,
        'edit_posts', // capability,
        'edit-tags.php?taxonomy=task_menus'// menu_slug,
    );

}
add_action( 'admin_menu', 'go_add_topmenus_as_submenu', 9 );

function go_add_sidebar_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'edit-tags.php?taxonomy=task_chains', // parent slug
        'Side Bar', // page_title,
        'Side Bar', // menu_title,
        'edit_posts', // capability,
        'edit-tags.php?taxonomy=task_categories' // menu_slug,
    );

}
add_action( 'admin_menu', 'go_add_sidebar_as_submenu', 9 );

/**
 * Add content to submenus
 * Callbacks
 */

function go_admin_game_on_menu_content() {

    ?>

    <div class="wrap">

        <h2>About Game On</h2>

        <?php

        /* your admin pages content would be added here! */

        ?>

    </div>

    <?php

}
function go_admin_tools_menu_content() {

    ?>

    <div class="wrap">

        <h2>Tools Coming Soon!</h2>
        <p>Export/Import Tasks Tool</p>
        <p>Archive</p>
        <p>Reset User Data</p>

        <h2>Update v3 to v4</h2>
        <p>This will update your v3 posts and store items to v4.  It's not perfect, but it's better then starting from scratch. The v3 content will be left unchanged and new post metadata will be created.</p>
        <p>An upcoming update will have an additional tool to purge the v3 content from the database.</p>
        <button id="go_tool_update">Update</button>


        <?php



        /* your admin pages content would be added here! */

        ?>

    </div>

    <?php

}
function go_admin_maps_menu_content() {

    ?>

    <div class="wrap">

        <h2>Game On Maps and Menus</h2>

        <?php

        /* your admin pages content would be added here! */

        ?>

    </div>

    <?php

}



/**
 * @param $parent_file
 * @return string
 * Fix Hierarchy on menus when items are clicked
 * show the correct sub menu
 */
function go_menu_hierarchy_correction( $parent_file ) {

    global $current_screen;


    /* get the base of the current screen */
    $screenbase = $current_screen->base;
    $taxonomy = $current_screen->taxonomy;

    if ($taxonomy == 'task_chains' || $taxonomy == 'task_menus'   || $taxonomy == 'task_categories'  ){
        /* if this is the edit.php base */
        if( $screenbase == 'term' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'maps_menus';

        }
        else if( $screenbase == 'edit-tags' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'maps_menus';
        }
    }

    else if ($taxonomy == 'go_badges'){
        if( $screenbase == 'term' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'badges';

        }
        else if( $screenbase == 'edit-tags' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'badges';
        }
    }
    else if ($taxonomy == 'user_go_groups'){
        if( $screenbase == 'term' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'groups';

        }
        else if( $screenbase == 'edit-tags' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'groups';
        }
    }

    /* return the new parent file */
    return $parent_file;

}
add_action( 'parent_file', 'go_menu_hierarchy_correction', 999 );




?>
