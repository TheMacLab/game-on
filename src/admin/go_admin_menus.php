<?php

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
        'edit.php?post_type=go_blogs',
        'go_random_events',
        'game-tools',//gameon tools
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

    /* add a new menu item */
    add_menu_page(
        'Game On',
        'About Game On',
        'manage_options',
        'game-on',
        'go_admin_game_on_menu_content',
        'dashicons-admin-home',
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

    $badges_toggle = get_option('options_go_badges_toggle');
    if($badges_toggle) {
        /* add a new menu item */
        $badges_name = get_option('options_go_badges_name_plural');
        add_menu_page($badges_name, // page title
            $badges_name, // menu title
            'edit_posts', // capability
            'badges' // menu slug
        //'', // callback function
        //'', // icon
        // 4 // menu position
        );
    }
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

    /* add a new menu item */
    add_menu_page(
        'Tools',
        'Tools',
        'manage_options',
        'game-tools',
        'go_admin_tools_menu_content',
        'dashicons-admin-tools',
        4
    );
}
add_action( 'admin_menu', 'go_add_toplevel_menu' );

function go_add_templates_as_submenu() {

    /* add the sub menu under content for posts */
    add_submenu_page(
        'edit.php?post_type=tasks', // parent slug
        'Templates', // page_title,
        'Templates', // menu_title,
        'edit_posts', // capability,
        'edit.php?post_type=tasks_templates' // menu_slug,
    );

}
add_action( 'admin_menu', 'go_add_templates_as_submenu', 9 );


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
    $badges_name = get_option('options_go_badges_name_singular');

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

function go_add_sections_sub_menus() {

    // add the sub menu under content for posts */
    add_submenu_page(
        'groups', // parent slug
        'Manage Sections', // page_title,
        'Manage Sections', // menu_title,
        'edit_posts', // capability,
        'edit-tags.php?taxonomy=user_go_sections' // menu_slug,
    );
}
add_action( 'admin_menu', 'go_add_sections_sub_menus', 9 );

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

      <h1></a>Game-On</h1>


    
<p>Game-On (GO) is an educational framework that provides teachers with a vast amount of tools to create their own <a href="http://en.wikipedia.org/wiki/Gamification" rel="nofollow">gamified</a> learning system.</p>

<h3>Information and Help</h3>
<ul style="list-style-position: outside; list-style-type: circle; margin-left: 30px;">
    <li><a href="http://maclab.guhsd.net/game-on" rel="nofollow">Game-On Documentation</a>: This is still v3 documentation.  v4 documentation is in the works.</li>
    <li><a href='https://www.youtube.com/channel/UC1G3josozpubdzaINcFjk0g' >YouTube</a> Visit our YouTube Channel for the most recent updates.</li>
    <li><a href="http://edex.adobe.com/group/game-on/discussions/" rel="nofollow">Adobe Education Exchange (AEE)</a> Game-On Group Forum</li>
        <ul>
            <li>The Game  <a href="https://edex.adobe.com/group/game-on/discussion/-9038000/" rel="nofollow">Questions and Observations</a> thread.</li>
            <li>If you found a bug or are having any difficulties in v3.X versions, refer to the <a href="https://edex.adobe.com/group/game-on/discussion/v9f80aa7d/" rel="nofollow">Game On v3.x Discussion</a> thread.</li>
            <li>Currently, AEE does not support thread subscription without commenting. If you'd like to recieve updates in any of the AEE threads, be sure to leave a comment. Something as simple as "Hi, following along." will do!</li>
        </ul>
    <li><a href="http://edex.adobe.com/group/game-on/discussions/" rel="nofollow">Gameful.me Forum</a></li>
    <ul>
        <li>For v4 information, bug reporting, and feature requests, please refer to the <a href="https://gameful.me/forums" rel="nofollow">forum on Gameful.me</a>.</li>
    </ul>
</ul>
        <h3>Installation Requirements</h3>
<p>Make sure to talk to your web hosting service provider about these technical requirements, if you have any doubts.</p>
<h4> PHP</h4>
<p>Make sure that your hosting service supports and maintains a PHP version of <strong>at least</strong> <code>5.3</code>. Ideally, every service would have updated their PHP versions to <code>7.1</code>, but that isn't a realistic assumption. If the most recent version is not an option, version <code>5.6</code> should do the trick.</p>
<p>In order of best scenario: <code>7.1</code> is better than <code>5.6</code>, which is better than <code>5.3</code>.</p>
<p>If your service does not provide a version of PHP greater than <code>5.3</code>, please be aware that there are potential compatibility issues due to the nature of the outdated software.</p>
<h4>WordPress</h4>
<p>We highly recommend keeping your WordPress installation up to date. This not only ensures that you receive all official <a href="https://wordpress.org/" rel="nofollow">WordPress.org</a> security updates and hotfixes, but you'll also receive the best experience when using GO.</p>
<hr>
<h3>Lovingly Created By</h3>
<p>Current Authors:</p>
<ul>
<li>Mick McMurray</li>
</ul>
<p>Previous Authors/Contributors:</p>
<ul>
<li><a href="http://foresthoffman.com" rel="nofollow">Forest Hoffman</a></li>
    <li>Zach Hofmeister</li>
<li>Ezio Ballarin</li>
<li>Charles Leon</li>
<li>Austin Vuong</li>
<li>Vincent Astolfi</li>
<li>Semar Yousif</li>
</ul>
<hr>
<h3>For Contributors</h3>
<p>Everything you need should be in the <a href="https://github.com/TheMacLab/game-on/wiki/">wiki</a>.</p>
<h3>License</h3>
<p>License:           GPLv2 or later
License URI:       <a href="http://www.gnu.org/licenses/gpl-2.0.html" rel="nofollow">http://www.gnu.org/licenses/gpl-2.0.html</a></p>


    </div>

    <?php

}
function go_admin_tools_menu_content() {

    ?>

    <div class="wrap">

        <h2>Update v3 to v4</h2>
        <p>This will update your v3 posts and store items to v4.  It's not perfect, but it's better then starting from scratch. The v3 content will be left unchanged and new post metadata will be created.</p>
        <p>An upcoming update will have an additional tool to purge the v3 content from the database.</p>
        <button id="go_tool_update">Update</button>

        <h2>Update v3 to v4--but don't update the quest loot.</h2>
        <p>This is just like the above tool, but doesn't copy all the task loot. This is if you want all your old quests for reference, but don't want them playable for rewards. </p>
        <button id="go_tool_update_no_loot">Update-No Loot</button>
        
        <h2>Reset All User Data</h2>
        <p>Reset tasks, history, and loot for all users. Blog posts and media will remain.</p>
        <button id="go_reset_all_users">Reset All Users</button>

        <h2>More Tools Coming Soon!</h2>
        <p>Export/Import Tasks Tool</p>
        <p>Archive</p>
        <p>Reset User Data</p>


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
    else if ($taxonomy == 'user_go_sections'){
        if( $screenbase == 'term' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'sections';

        }
        else if( $screenbase == 'edit-tags' ) {
            /* set the parent file slug to the custom content page */
            $parent_file = 'sections';
        }
    }

    /* return the new parent file */
    return $parent_file;

}
add_action( 'parent_file', 'go_menu_hierarchy_correction', 999 );

?>
