<?php
/**
 * Created by PhpStorm.
 * User: mmcmurray
 * Date: 7/31/18
 * Time: 12:25 PM
 */


/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header();


/////////////////////USER HEADER

    $user = get_query_var('uname');

    $user_obj = get_user_by('login',$user);
    if($user_obj)
    {
       $user_id = $user_obj->ID;
    }else{
        $user_id = 0;
    }

    $current_user_id = get_current_user_id();



    ?>

    <?php
    $user_fullname = $user_obj->first_name.' '.$user_obj->last_name;
    $user_login =  $user_obj->user_login;
    $user_display_name = $user_obj->display_name;
    $user_website = $user_obj->user_url;
    $page_title = $user_display_name . "'s Blog";
    ?><script>
    document.title = "<?php echo $page_title; ?>";
    jQuery( document ).ready(function() {
        go_lightbox_blog_img();
    });
    </script><?php
    $use_local_avatars = get_option('options_go_avatars_local');
    $use_gravatar = get_option('options_go_avatars_gravatars');
    if ($use_local_avatars){
        $user_avatar_id = get_user_meta( $user_id, 'go_avatar', true );
        $user_avatar = wp_get_attachment_image($user_avatar_id);
    }
    if (empty($user_avatar) && $use_gravatar) {
        $user_avatar = get_avatar( $user_id, 150 );
    }





    ?>
    <div id='go_stats_lite_wrapper'>
    <div id='go_stats_lay_lite' class='go_datatables'>
        <div id='go_stats_header_lite'>
            <div class="go_stats_id_card">
                <div class='go_stats_gravatar'><?php echo $user_avatar; ?></div>

                <div class='go_stats_user_info'>
                    <?php echo "<h2>{$user_fullname}</h2>{$user_display_name}<br>"; ?>
                    <?php
                    go_user_links($user_id, true, true, true, false, true, true);
                    if ($current_user_id === $user_id){
                        echo '<button class="go_blog_opener" blog_post_id ="">New Post</button>';
                     }
                    ?>


                </div>

            </div>

        </div>
    </div>
    <?php















    ////////////////////
    /// ////////////
    ////////////////////////////////////
    /// TEST QUERIES
    ///
    global $wpdb;


    $go_action_table_name = "{$wpdb->prefix}go_actions";

    //columns that will be returned
    $aColumns = array('id', 'uid', 'source_id', 'action_type', 'TIMESTAMP', 'result', 'xp', 'gold', 'health', 'badges', 'groups');

    //columns that will be searched
    $sColumns = array('result', 'xp', 'gold', 'health', 'badges', 'groups');

    $sIndexColumn = "id";
    $sTable = $go_action_table_name;

    $sLimit = '';
    //if (isset($_GET['start']) && $_GET['length'] != '-1') {
        $sLimit = "LIMIT " . intval(0) . ", " . intval(100);
    //}

    $sOrder = "ORDER BY TIMESTAMP desc"; //always in reverse order

    $sWhere = "WHERE (action_type = 'store') ";

    $totalWhere = " WHERE (action_type = 'store') ";

    if ( isset($date) && $date != "" )
    {
        $date = date("Y-m-d", strtotime($date));
        $sWhere .= " AND ( DATE(TIMESTAMP) = '" . $date . "')";
    }


//This is the fast one if it is sorted by user info or a search term is provided
    $sQuery = "
      SELECT 
        user_info.*, t4.source_id, t4.action_type, t4.TIMESTAMP, t4.result, t4.xp, t4.gold, t4.health, t4.badges, t4.groups
      FROM (
          SELECT
          t1.uid,
          MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
          MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END) AS section_0,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-seat' THEN meta_value END) AS seat_0,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-seat' THEN meta_value END) AS seat_1,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-seat' THEN meta_value END) AS seat_2,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-seat' THEN meta_value END) AS seat_3,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-seat' THEN meta_value END) AS seat_4,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-seat' THEN meta_value END) AS seat_5,      
          t3.display_name, t3.user_url
          FROM wp_go_loot AS t1 
          LEFT JOIN wp_usermeta AS t2 ON t1.uid = t2.user_id
          LEFT JOIN wp_users AS t3 ON t2.user_id = t3.ID
          GROUP BY t1.id
      ) AS user_info
      INNER JOIN wp_go_actions AS t4 ON user_info.uid = t4.uid
      WHERE (t4.action_type = 'store')
      $sOrder
      $sLimit
    ";
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);
/*
    //SLOWER.
    $sQuery = "
      SELECT 
         actions.*,
          MAX(CASE WHEN t2.meta_key = 'first_name' THEN meta_value END) AS first_name,
          MAX(CASE WHEN t2.meta_key = 'last_name' THEN meta_value END) AS last_name,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat' THEN meta_value END) AS num_section,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-section' THEN meta_value END) AS section_0,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_0_user-seat' THEN meta_value END) AS seat_0,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-section' THEN meta_value END) AS section_1,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_1_user-seat' THEN meta_value END) AS seat_1,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-section' THEN meta_value END) AS section_2,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_2_user-seat' THEN meta_value END) AS seat_2,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-section' THEN meta_value END) AS section_3,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_3_user-seat' THEN meta_value END) AS seat_3,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-section' THEN meta_value END) AS section_4,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_4_user-seat' THEN meta_value END) AS seat_4,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-section' THEN meta_value END) AS section_5,
          MAX(CASE WHEN t2.meta_key = 'go_section_and_seat_5_user-seat' THEN meta_value END) AS seat_5,      
          t3.display_name, t3.user_url
      FROM (
          SELECT " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
          FROM $sTable 
          $sWhere
          $sOrder
          $sLimit
      ) AS actions
      LEFT JOIN wp_usermeta AS t2 ON actions.uid = t2.user_id
      LEFT JOIN wp_users AS t3 ON t2.user_id = t3.ID
          GROUP BY actions.id
    ";
    $rResult = $wpdb->get_results($sQuery, ARRAY_A);
*/
    $sQuery = "
    SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
    FROM $sTable 
    $sWhere
    $sOrder
    $sLimit
    ";

    $rResult = $wpdb->get_results($sQuery, ARRAY_A);

    $sQuery = "SELECT FOUND_ROWS()";

    $rResultFilterTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iFilteredTotal = $rResultFilterTotal [0];

    $sQuery = "
    SELECT COUNT(`" . $sIndexColumn . "`)
    FROM   $sTable
    $totalWhere
    ";

    $rResultTotal = $wpdb->get_results($sQuery, ARRAY_N);

    $iTotal = $rResultTotal [0];
    //$iFilteredTotal = number that match without limit;
    //$iTotalRecords = number in this table total (total store items/messages)
    $output = array("iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => array());


    foreach($rResult as $action){//output a row for each message

        //The message content
        $row = array();
        $user_id = $action['uid'];
        $source_id = $action['source_id'];
        $TIMESTAMP = $action['TIMESTAMP'];
        $xp = $action['xp'];
        $gold = $action['gold'];
        $health = $action['health'];
        $badge_ids = $action['badges'];
        $group_ids = $action['groups'];

        $title = get_the_title($source_id);


        ///Get the user info for this row from the transient--should this be stored temporarily, in the object cache
        $user_row = go_get_loot($user_id);


        //FILTERS
        //groups filter
        $user_group_ids = $user_row['groups'];
        $group_ids_array = unserialize($user_group_ids);



        $num_sections = get_user_meta($user_id, 'go_section_and_seat', true);
        if (empty($num_sections)){
            $num_sections =1;
        }
        $user_periods= array();
        $user_period_name= array();
        $user_seat = array();
        for ($i = 0; $i < $num_sections; $i++) {
            $user_period_option = "go_section_and_seat_" . $i . "_user-section";
            $user_seat_option = "go_section_and_seat_" . $i . "_user-seat";


            $user_period = get_user_meta($user_id, $user_period_option, true);
            $user_periods[] = $user_period;
            $term = get_term($user_period, "user_go_sections");
            //$user_period_name = $term->name;
            $user_period_name[] = (isset($term->name) ? $term->name : null);

            $user_seat[] = get_user_meta($user_id, $user_seat_option, true);
        }

        //if this is not filtered, set the values
        $user_period_name = implode("<br>", $user_period_name);
        $user_seat = implode("<br>", $user_seat);

        $user_data_key = get_userdata($user_id);
        $user_display_name = $user_data_key->display_name;
        $user_firstname = $user_data_key->user_firstname;
        $user_lastname = $user_data_key->user_lastname;

        $badges_toggle = get_option('options_go_badges_toggle');
        if ($badges_toggle) {
            $badges_names = array();
            $badge_ids = unserialize($badge_ids);
            $badges_name_sing = get_option('options_go_badges_name_singular');

            if (!empty($badge_ids)) {
                $badges_names_heading = "<b>Add " . $badges_name_sing . ":</b> ";
                foreach ($badge_ids as $badge_id) {
                    $term = get_term($badge_id, "go_badges");
                    $badge_name = $term->name;
                    $badges_names[] = $badge_name;
                }
                $badges_names = $badges_names_heading . implode(", " , $badges_names);
            }else{
                $badges_names = "";
            }

        }else{
            $badges_names = "";
        }

        $group_names = array();
        $group_ids = unserialize($group_ids);
        if (!empty($group_ids)){
            if (!empty($badge_ids)) {
                $group_names_heading = "<br><b>Add Group: </b>";
            }else {
                $group_names_heading = "<b>Add Group: </b>";
            }
            foreach ($group_ids as $group_id) {
                $term = get_term($group_id, "user_go_groups");
                $group_name = $term->name;
                $group_names[] = $group_name;
            }
            $group_names = $group_names_heading . implode(", " , $group_names);
        }else{
            $group_names = "";
        }

        $badges_names = $badges_names . $group_names;


        //$unix_time = strtotime($TIMESTAMP);
        $time  = date("m/d/y g:i A", strtotime($TIMESTAMP));

        ob_start();
        go_user_links($user_id, true, true, true, true, true, false);
        $links = ob_get_clean();

        $check_box = "<input class='go_checkbox' type='checkbox' name='go_selected' value='{$user_id}'/>";

        $row[] = "";
        $row[] = "{$check_box}";
        $row[] = "{$user_period_name}";//user period
        $row[] = "{$user_seat}";//user seat
        $row[] = "{$user_firstname}";
        $row[] = "{$user_lastname}";
        $row[] = "{$user_display_name}";
        $row[] = "{$links}";
        $row[] = "{$time}";
        $row[] = "{$title}";

        $xp_toggle = get_option('options_go_loot_xp_toggle');
        $gold_toggle = get_option('options_go_loot_gold_toggle');
        $health_toggle = get_option('options_go_loot_health_toggle');

        if ($xp_toggle){
            $row[] = "{$xp}";
        }
        if ($gold_toggle){
            $row[] = "{$gold}";
        }
        if ($health_toggle){
            $row[] = "{$health}";
        }
        $row[] = "{$badges_names}";
        $output['aaData'][] = $row;
    }
















    /// END USER HEADER

// get the username based from uname value in query var request.

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
//$paged = get_query_var('paged');
// Query param
$arg = array(
    'post_type'         => 'go_blogs',
    'posts_per_page'    => 5,
    'orderby'           => 'publish_date',
    'order'             => 'DESC',
    'author_name'       => $user,
    'paged' => $paged,
);
//build query
$query = new WP_Query( $arg );

// get query request
$posts = $query->get_posts();

//video options
    $go_lightbox_switch = get_option( 'options_go_video_lightbox' );
    $go_video_unit = get_option ('options_go_video_width_unit');
    if ($go_video_unit == 'px'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_pixels')."px";
    }
    if ($go_video_unit == '%'){
        $go_fitvids_maxwidth = get_option('options_go_video_width_percent')."%";
    }

// check if there's any results
if ( empty($posts) ) {
    echo "Author doesn't have any posts";
} else {
    echo "<div id='go_wrapper' data-lightbox='{$go_lightbox_switch}' data-maxwidth='{$go_fitvids_maxwidth}' >";
    ?>

    <div class="go_blog_container1" style="display: flex; justify-content: center;">
    <div class="go_blog_container" style="    display: flex;
    justify-content: center;
    flex-direction: column;
    padding: 20px;
    flex-grow: 1;
    max-width: 800px;"><?php
   foreach ($posts as $post){
       $post = json_decode(json_encode($post), True);//convert stdclass to array by encoding and decoding
       $title =  $post['post_title'];
       $content =  $post['post_content'];
       $content  = $GLOBALS['wp_embed']->autoembed($content );
       $content = do_shortcode(wpautop( $content  ) );
       //$content = apply_filters('the_content', $content);
       //$content = str_replace(']]>', ']]&gt;', $content);

       //$content = do_shortcode($content);
       $date = $post['post_date'];
       $post_id = $post['ID'];
       ?>
       <div class="go_blog_post_wrapper" style="padding: 20px;">
           <hr>
           <h2><?php echo $title;?></h2>
           <div><?php echo $date;?></div>
           <?php
if ($current_user_id === $user_id){
    echo '<button class="go_blog_opener" blog_post_id ="'.$post_id.'">edit post</button>';
}
 ?>

           <p></p>
           <div><?php echo $content;?></div>
       </div>


       <?php
   }


   ?>

   <div class="pagination">
    <?php
        echo paginate_links( array(
            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'total'        => $query->max_num_pages,
            'current'      => max( 1, get_query_var( 'paged' ) ),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
            'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
            'add_args'     => false,
            'add_fragment' => '',
        ) );
    ?>
    </div>
    </div>
    </div>
    </div>


    <?php

}
    go_hidden_footer();
?>
 <script>

        jQuery( document ).ready( function() {
            jQuery(".go_blog_opener").one("click", function(e){
                go_blog_opener( this );
            });
            // remove existing editor instance
            tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
            tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            jQuery('#go_hidden_mce').remove();
            jQuery('#go_hidden_mce_edit').remove();
            jQuery('#wpadminbar').css('z-index', 99999);
            go_stats_links();
        });

    </script>
<?php

get_footer();