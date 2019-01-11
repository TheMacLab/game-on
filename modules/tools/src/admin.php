<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 1/11/19
 * Time: 1:13 AM
 */

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
