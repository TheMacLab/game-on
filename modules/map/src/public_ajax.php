<?php
/**
 *
 */
function go_make_map() {
    if ( ! is_admin() ) {
        $user_id = get_current_user_id();
        $last_map_id = get_user_option('go_last_map', $user_id);
        echo "<div id='go_map_container' style='padding:10px 30px; margin: 30px 5%; background-color: white;'>";
        $map_title = get_option( 'options_go_locations_map_title');
        echo "<h1 style='padding:0px 30px 30px 0px;'>{$map_title}</h1>";
        go_make_map_dropdown();
        go_make_single_map($last_map_id, false);// do your thing
        echo "</div>";
    }
}
add_shortcode('go_make_map', 'go_make_map');

/**
 * @param $last_map_id
 * @param $reload
 * @param $user_id
 */
function go_make_single_map($last_map_id, $reload, $user_id = null){
    ?>
    <script>
        jQuery( document ).ready(function() {
            console.log("ready1");
            go_resizeMap();
        });


        jQuery( window ).resize(function() {
            console.log("resize1");
            go_resizeMap();
        });
    </script>
        <?php
    global $wpdb;
    $go_loot_table_name = "{$wpdb->prefix}go_loot";
    $go_task_table_name = "{$wpdb->prefix}go_tasks";
    //wp_nonce_field( 'go_update_last_map');
    $last_map_object = get_term_by( 'id' , $last_map_id, 'task_chains');//Query 1 - get the map
    $is_hidden = get_term_meta( $last_map_id, 'go_hide_map', true );
    if ($is_hidden){
        return;
    }
    if ($user_id == null) {
        $user_id = get_current_user_id();
        $task_links = true;
    }else{
        $task_links = false;
    }
    $is_logged_in = ! empty( $user_id ) && $user_id > 0 ? true : false;
    //$taxonomy_name = 'task_chains';

    $user_badges =  $wpdb->get_var ("SELECT badges FROM {$go_loot_table_name} WHERE uid = {$user_id}");
    if(empty($user_badges)){ //if there were no badges then create empty array
        $user_badges = array();
    }
    else {//else unserialize
        if(is_serialized($user_badges)){
            $user_badges = unserialize($user_badges);
        }
    }

    if ($reload == false) {echo "<div id='mapwrapper' style='overflow: auto; '>";}
    echo "<div id='loader_container' style='display:none; height: 250px; width: 100%; padding: 10px 30px; '>
                <div id='loader'>
                </div>
          </div>
            <div id='maps' data-mapid='$last_map_id' style='overflow: auto;'>";
    if(!empty($last_map_id)){

        echo 	"<div id='map_$last_map_id' class='map' style='overflow: auto;'>
				<ul class='primaryNav'>
				<li class='ParentNav'><p>$last_map_object->name</p></li>";

        $term_ids = go_get_map_chain_term_ids($last_map_id);

        foreach ( $term_ids as $term_id ) {

            $term_data = go_term_data($term_id);
            $term_name = $term_data[0];
            $term_custom = $term_data[1];

            $is_hidden = (isset($term_custom['go_hide_map'][0]) ?  $term_custom['go_hide_map'][0] : null);
            if ($is_hidden){
                return;
            }

            //$term_object = get_term($term_id);


            //Get array of postIDs from transient--this also creates transients of each posts data if needed
            $go_post_ids = go_get_chain_posts($term_id, true);

            //echo "<li><p>$term_object->name";
            echo "<li><p>$term_name";

            $is_pod = (isset($term_custom['pod_toggle'][0]) ?  $term_custom['pod_toggle'][0] : null);

            if($is_pod) {
                $pod_min = (isset($term_custom['pod_done_num'][0]) ?  $term_custom['pod_done_num'][0] : null);
                $pod_all = (isset($term_custom['pod_all'][0]) ?  $term_custom['pod_all'][0] : null);
                $pod_count = count($go_post_ids);
                if (($pod_all || ($pod_min >= $pod_count)) && ($pod_count > 1)){
                    $task_name_pl = get_option('options_go_tasks_name_plural'); //Q option
                   	 //echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete all $task_name_pl. </span>";
                }
                else if ($pod_count>1) {
                    if ($pod_min>1){
                        $task_name = get_option('options_go_tasks_name_plural'); //Q option
                    }else{
                        $task_name = get_option('options_go_tasks_name_singular'); //Q option
                    }

                    echo "<br><span style='padding-top: 10px; font-size: .8em;'>Complete at least $pod_min $task_name. </span>";
                }
            }

            //START: The list of tasks in the chain

            echo "<ul class='tasks'>";
            //If there are tasks
            if (!empty($go_post_ids)){
                //$go_task_ids = array();


                //Query 3
                //Get User info for these tasks
                $id_string = implode(',', $go_post_ids);
                $user_tasks = $wpdb->get_results(
                    "SELECT *
						FROM {$go_task_table_name}
						WHERE uid = $user_id AND post_id IN ($id_string)
						ORDER BY last_time DESC"
                );
                $user_tasks = json_decode(json_encode($user_tasks), True);

                //for each of the tasks (objects retrieved in query 2)
                foreach($go_post_ids as $post_id) {
                    $go_task_data = go_post_data($post_id); //0--name, 1--status, 2--permalink, 3--metadata
                    $task_name = $go_task_data[0];
                    $status = $go_task_data[1];
                    $task_link = $go_task_data[2];
                    $custom_fields = $go_task_data[3];

                    //$status = get_post_status( $row );//is post published
                    if ($status !== 'publish'){continue; }//don't show if not pubished

                    //$task_name = $row->post_title; //Q
                    //$task_link = get_permalink($row); //Q
                    //$id = $row->ID;
                    //$custom_fields = get_post_custom( $id ); // Just gathering some data about this task with its post id
                    $stage_count = $custom_fields['go_stages'][0];//total stages

                    //$user_tasks is an array of task object arrays
                    //These next lines pull information from the array by
                    //getting the position of this task in the array,
                    //then get the status this task from the tasks array
                    $ids = array_map(function ($each) {
                        return $each['post_id'];
                    }, $user_tasks);

                    $key = array_search($post_id, $ids);

                    if ($key !== false) {
                        $this_task = $user_tasks[$key];
                        $status = $this_task['status'];
                    }else{
                        $status = 0;
                        $this_task = array();

                    }
                    //add status to cache
                    $cache_key = 'go_get_status_' . $post_id;
                    wp_cache_set ($cache_key, $status, 'go_single' );


                    if($custom_fields['bonus_switch'][0]) {
                        $bonus_stage_toggle = true;
                        if ($key !== false) {
                            $bonus_status = $this_task['bonus_status'];
                        }else{
                            $bonus_status = 0;
                        }
                        //$bonus_status = go_get_bonus_status($id, $user_id);
                        $repeat_max = $custom_fields['go_bonus_limit'][0];//max repeats of bonus stage
                        $bonus_stage_name = get_option('options_go_tasks_bonus_stage').':';
                    }
                    else{
                        $bonus_stage_toggle = false;
                    }

                    //if locked
                    $task_is_locked = go_task_locks($post_id, $user_id, false, $custom_fields, $is_logged_in, true);
                    //$task_is_locked = false;
                    $unlock_message = '';
                    if ($task_is_locked === 'password'){
                        $unlock_message = '<div><i class="fa fa-unlock"></i> Password</div>';
                        $task_is_locked = false;
                    }
                    else if ($task_is_locked === 'master password') {
                        $unlock_message = '<div><i class="fa fa-unlock"></i> Master Password</div>';
                        $task_is_locked = false;
                    }


                    if ($stage_count <= $status){
                        $task_color = 'done';
                        $finished = 'checkmark';
                    }else if ($task_is_locked){
                        $task_color = 'locked';
                        $finished = null;
                    }
                    else{
                        $task_color = 'available';
                        $finished = null;
                    }

                    if ($custom_fields['go-location_map_opt'][0]) {
                        $optional = 'optional_task';
                        $bonus_task = get_option('options_go_tasks_optional_task').':';  //Q option
                    }
                    else {
                        $optional = null;
                        $bonus_task = null;
                    }

                    if ($task_links === true) {
                        echo "<li class='$task_color $optional '><a href='$task_link'><div class='$finished'></div><span style='font-size: .8em;'>$bonus_task $task_name <br>$unlock_message</span>";
                    }else{
                        echo "<li class='$task_color $optional '><a href='javascript:;' class='go_blog_user_task' data-UserId='".$user_id."' onclick='go_blog_user_task(".$user_id.", ".$post_id.");'><div class='$finished'></div><span style='font-size: .8em;'>$bonus_task $task_name <br>$unlock_message</span>";
                        //echo "<li class='$task_color $optional '><a href='$task_link'><div class='$finished'></div><span style='font-size: .8em;'>$bonus_task $task_name <br>$unlock_message</span>";
                        }
                    //<a href="javascript:;" class="go_blog_user_task" data-UserId="'.$user_id.'" onclick="go_blog_user_task('.$user_id.', '.$post_id.');">


                    $badge_ids = (isset($custom_fields['go_badges'][0]) ?  $custom_fields['go_badges'][0] : null);

                    if($badge_ids) {//if there are badges awarded on this task
                        if (!empty($badge_ids)) {
                            go_map_badge($badge_ids, $user_badges, false);
                        }
                    }

                    /*
                    if ($bonus_stage_toggle == true){
                        if ($bonus_status == 0 || $bonus_status == null){
                            echo "<br><div id='repeat_ratio' style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name 
								<div class='star-empty fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> 0 / $repeat_max</div>
							
						";
                        }
                        else if ($bonus_status == $repeat_max) {
                            echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-full fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
								</div> $bonus_status / $repeat_max</div>
							";
                        }
                        else {
                            echo "<br><div style='padding-top: 10px; font-size: .7em;'>$bonus_stage_name
								<div class='star-half fa-stack'>
									<i class='fa fa-star fa-stack-2x''></i>
									<i class='fa fa-star-half-o fa-stack-2x'></i>
									<i class='fa fa-star-o fa-stack-2x'></i>
									
								</div> $bonus_status / $repeat_max</div>
							";
                        }
                    }
                    */

                    if ($bonus_stage_toggle == true){
                        $percentage = $bonus_status / $repeat_max * 100;
                        $color = 'gold';
                        $progress_bar = '<div style="position: relative; width:90%; margin-left: 5%; height:15px; border: 1px solid; background-color: white; box-sizing: unset;">'.'<div class="go_bonus_progress_bar" '.
                            'style="position: absolute; height: 15px; width: '.$percentage.'%; background-color: '.$color.' ;">'.
                            '</div>'.
                            '<div style="position: absolute; width: 100%; height: 100%; font-size: .7em; line-height: initial;" class="bonus_progress">Bonus: '.
                            $bonus_status . ' / ' . $repeat_max .'</div>'.
                            '</div>';
                        echo $progress_bar;
                    }

                    echo"</a>
							</li>";
                }

                //badge for chains
                $badge_ids = get_term_meta($term_id, "pod_achievement", true);


                if (!empty($badge_ids)) {
                    go_map_badge($badge_ids, $user_badges, true);
                }

            }
            echo "</ul>";
        }
        //badge for map
        $badge_ids = get_term_meta($last_map_id, "pod_achievement", true);
        if (!empty($badge_ids)) {
            go_map_badge($badge_ids, $user_badges, true);
        }



        echo "</ul></div>";
    }
    echo "</div>";
    if ($reload == false) {echo "</div>";}
}

/**
 * @param $atts
 * @param null $content
 * @return string
 */
function go_single_map_link($atts, $content = null ) {
    $atts = shortcode_atts(
        array(
            "map_id" => ''
        ),
        $atts
    );
    $map_id = $atts['map_id'];
    return "<a href='#' onclick='go_to_this_map(" . $map_id . ")'>" . $content . "</a>";
}
add_shortcode( 'go_single_map_link', 'go_single_map_link' );



/**
 * @param $badge_ids
 * @param $user_badges
 * @param $container
 *
 * Show badge on map for tasks
 */
function go_map_badge($badge_ids, $user_badges, $container){

    if($badge_ids) {//if there are badges awarded on this task
        if (is_serialized($badge_ids)){
            $badge_ids = unserialize($badge_ids);//legacy badges saved as serialized array
        }
        if (!is_array($badge_ids)){
            $badge_ids = array($badge_ids);
        }
        if (is_array($badge_ids)){
            foreach($badge_ids as $badge_id) {
                if ($container){
                    if (in_array($badge_id, $user_badges)){
                        $task_color = 'done';//set to green if badge is posessed
                        //$badge_needed = '';
                    }else{
                        $task_color = 'locked';//set to grey if needed
                        //$badge_needed = 'go_badge_needed';
                    }
                    echo "<li class='". $task_color . "'><a class='go_map_badge'>";
                }
                go_map_quest_badge($badge_id, $user_badges);
                if ($container){
                    echo "</a></li>";
                }
            }
        }
    }

}

/**
 *  * Show badge on map for chains
 *
 * @param $badge
 * @param $user_badges
 */
function go_map_quest_badge($badge, $user_badges){
    //does this term have a badge assigned and if so show it
    if ($user_badges == null){
        $user_badges = array();
    }
    if($badge){
        if (in_array($badge, $user_badges)){
            //$task_color = 'done';
            $badge_needed = '';
        }else{
            //$task_color = 'locked';
            $badge_needed = 'go_badge_needed';
        }
        $badge_img_id = get_term_meta( $badge, 'my_image' );
        $badge_description = term_description( $badge );

        //echo "<li>";
        $badge_obj = get_term( $badge);
        $badge_name = $badge_obj->name;
        //$badge_img_id =(isset($custom_fields['my_image'][0]) ?  $custom_fields['my_image'][0] : null);
        $badge_img = wp_get_attachment_image($badge_img_id[0], array( 50, 50 ));
        if (!$badge_img){
            $badge_img = "<div style='width:50px; height: 50px;'></div>";
        }

        //$badge_attachment = wp_get_attachment_image( $badge_img_id, array( 100, 100 ) );
        //$img_post = get_post( $badge_id );
        if ( ! empty( $badge_obj ) ) {
            echo"<div class='go_badge_quest_wrap'>
                        <div class='go_badge_quest_container ". $badge_needed . "'><figure class=go_quest_badge title='{$badge_name}'>";

            if (!empty($badge_description)){
                $badge_description = strip_tags($badge_description );
                echo "<span class='tooltip' ><span class='tooltiptext'>{$badge_description}</span>{$badge_img}</span>";
            }else{
                echo "$badge_img";
            }
            echo "        
              				 <figcaption>{$badge_name}</figcaption>
                            </figure>
                        </div>
                       </div>";

        }
        //echo "</li>";

    }
}


/**
 *
 */
function go_make_map_dropdown($user_id = null){
/* Get all task chains with no parents--these are the top level on the map.  They are chains of chains (realms). */
	$taxonomy = 'task_chains';
	$term_args0=array(
  		'hide_empty' => false,
  		'orderby' => 'name',
  		'order' => 'ASC',
  		'parent' => '0'
	);
	$tax_terms_maps = get_terms($taxonomy,$term_args0);

	if($user_id != null){
	    $user_data = get_userdata($user_id );
	    $user_name = $user_data->display_name;
	    echo "<h1 id='go_map_user' data-uid='".intval($user_id)."'>Viewing map as ". $user_name . "</h1>";

    }
	
	echo"
	<div id='sitemap' style='visibility:hidden;'>   
    <div class='dropdown'>
      <button onclick='go_map_dropDown()' class='dropbtn'>Choose a Map</button>
      <div id='go_Dropdown' class='dropdown-content'>";
    /* For each task chain with no parent, add to Dropdown  */
            foreach ( $tax_terms_maps as $tax_term_map ) {
				$term_id = $tax_term_map->term_id;
                $is_hidden = get_term_meta( $term_id, 'go_hide_map', true );
                if (!$is_hidden) {
                    echo "
                <div id='mapLink_$term_id' >
                <a onclick=go_show_map($term_id)>$tax_term_map->name</a></div>";
                }
            }
        echo"</div></div></div> ";
}




         
?>
