<?php
/////////////////////////////////////////////
//////////// Front-End Lightbox! ////////////
///////// Module by Vincent Astolfi /////////
/////////////////////////////////////////////
///////////// Special Thanks To /////////////
///////// http://www.ajaxload.info/ /////////
////////////// For Loading Icons ////////////
/////////////////////////////////////////////
//Includes
include ('buy-ajax.php'); // Ajax run when buying something
// Main Lightbox Ajax Function
function go_the_lb_ajax(){
    check_ajax_referer( 'go_lb_ajax_referall', 'nonce' );
	global $wpdb;
	$table_name_go = $wpdb->prefix."go";
	$the_id = $_POST["the_item_id"];
	$the_post = get_post($the_id);
	$the_title = $the_post->post_title;
	$item_content = get_post_field('post_content', $the_id);
	$the_content = wpautop($item_content);
	$custom_fields = get_post_custom($the_id);
	if(isset($custom_fields['go_mta_penalty_switch'])){
		$penalty = true;
	}
	
	if($custom_fields['go_mta_store_currency'][0]){
		$req_currency = $custom_fields['go_mta_store_currency'][0];
	}else {
		$req_currency = 0;
	}
	if($custom_fields['go_mta_store_points'][0]){
		$req_points = $custom_fields['go_mta_store_points'][0];
	}else{
		$req_points = 0;	
	}
	if($custom_fields['go_mta_store_time'][0]){
		$req_time = $custom_fields['go_mta_store_time'][0];
	}else{
		$req_time = 0;	
	}

	
	if($custom_fields['go_mta_store_time_filter'][0]){
		$minutes_required = $custom_fields['go_mta_store_time_filter'][0];	
	} 
	$req_rank_key =  go_get_rank_key($custom_fields['go_mta_store_rank'][0]);
	$req_rank = $custom_fields['go_mta_store_rank'][0];
	$go_store_repeat = $custom_fields['go_mta_store_repeat'][0];
	if($custom_fields['go_mta_store_repeat_amount'][0]){
		$purchase_limit = $custom_fields['go_mta_store_repeat_amount'][0];	
	} else{
		$purchase_limit = 0;
	}
	
	$user_rank = go_get_rank($user_id); // Rank of current user
	$user_ID = get_current_user_id(); // Current User ID
	$user_points = go_return_points($user_ID);
	$user_time = go_return_minutes($user_ID);
	$user_gold = go_return_currency($user_ID);
	$purchase_count = $wpdb->get_var("SELECT `count` FROM `".$table_name_go."` WHERE `post_id`='".$the_id."' AND `uid`='".$user_ID."'"); 
	echo '<h2>'.$the_title.'</h2>';
	echo '<div id="go-lb-the-content">'.do_shortcode($the_content).'</div>';
	if ($user_points >= $req_rank) { $lvl_color = "g"; } else { $lvl_color = "r"; }
	if ($user_gold >= $req_currency || $penalty) { $gold_color = "g"; } else { $gold_color = "r"; }
	if ($user_points >= $req_points) { $points_color = "g"; } else { $points_color = "r"; }
	$time_color = "g"; 
	if ($lvl_color == "g" && $gold_color == "g" && $points_color == "g") { $buy_color = "g"; } else { $buy_color = "r"; }
	
	$user_focuses = array();
	
	// Check if user has a focus
	if(get_user_meta($user_ID, 'go_focus', true) != null){
		$user_focuses = (array) get_user_meta($user_ID, 'go_focus', true);
	}
	
	// Check if the item has a focus and the focus gateway is turned on
	if($custom_fields['go_mta_focuses'][0] && $custom_fields['go_mta_focus_item_switch'][0] == 'on'){
		$item_focus = $custom_fields['go_mta_focuses'][0];
	} 
	
	// If user has the focus and the item is a focus gateway echo this
	if($item_focus && !empty($user_focuses) && in_array($item_focus, $user_focuses)){
		die('You already have this '.go_return_options('go_focus_name').'!');	
	}
	if( $minutes_required && $user_time < $minutes_required){ 
		die('You require more time to view this item.');
	} 
	if($purchase_limit != 0 && $purchase_count >= $purchase_limit){
		die('You\'ve reached the maximum purchase limit.');
	}
	if($user_points < $req_rank){
		die('You need to reach '.$req_rank_key.' to purchase this item.');
	}
	?>
	<div id="golb-fr-price" class="golb-fr-boxes-<?php echo $gold_color; ?>" req="<?php echo $req_currency; ?>" cur="<?php echo $user_gold; ?>"><?php echo go_return_options('go_currency_name').': '.$req_currency; ?></div>
	<div id="golb-fr-points" class="golb-fr-boxes-<?php echo $points_color; ?>" req="<?php echo $req_points; ?>" cur="<?php echo $user_points; ?>"><?php echo go_return_options('go_points_name').': '.$req_points; ?></div>
	<div id="golb-fr-time" class="golb-fr-boxes-<?php echo $time_color; ?>" req="<?php echo $req_time; ?>" cur="<?php echo $user_time; ?>">Time: <?php echo $req_time; ?></div>
	<div id="golb-fr-qty" class="golb-fr-boxes-g">Qty: <input id="go_qty" style="width: 40px;font-size: 11px; margin-right:0px; margin-top: 0px; bottom: 3px; position: relative;" value="1" disabled="disabled" /></div>
	<?php if(!$item_focus){?>
        <div id="go_recipient_wrap" class="golb-fr-boxes-g">Recipient: <input id="go_recipient" type="text"/></div>
        <div id="go_search_results"></div>
	<?php }?>
	<div id="golb-fr-buy" class="golb-fr-boxes-<?php echo $buy_color; ?>" onclick="goBuytheItem('<?php echo $the_id; ?>', '<?php echo $buy_color; ?>');">Buy</div>
	<div id="golb-fr-purchase-limit" val="<?php echo $purchase_limit;?>"><?php if($purchase_limit == 0){echo 'No limit';} else{ echo 'Limit '.$purchase_limit; }?> </div> 
	<div id="golb-purchased">
	<?php 
		if($purchase_count == NULL){ 
			echo 'Times purchased: 0';
		} else{
			echo 'Times purchased: '.$purchase_count;
		} 
	?>
	</div>
	<?php
    die();
}
add_action('wp_ajax_go_lb_ajax', 'go_the_lb_ajax');
add_action('wp_ajax_nopriv_go_lb_ajax', 'go_the_lb_ajax');
////////////////////////////////////////////////////
function go_frontend_lightbox_css() {
	$go_lb_css_dir = plugins_url( '/css/go-lightbox.css' , __FILE__ );
	echo '<link rel="stylesheet" href="'.$go_lb_css_dir.'" />';
}
add_action('wp_head', 'go_frontend_lightbox_css');

function go_frontend_lightbox_html() {
?>
<script type="text/javascript">

function go_lb_closer() {
	document.getElementById('light').style.display='none';
	document.getElementById('fade').style.display='none';
	document.getElementById('lb-content').innerHTML = '';
}
function go_lb_opener(id) {
	jQuery('#light').css('display', 'block');
	if(jQuery('#go_stats_page_black_bg').css('display') == 'none'){
		jQuery('#fade').css('display', 'block');
	}
	jQuery('#light').css('z-index', 9000);
	if( !jQuery.trim( jQuery('#lb-content').html() ).length ) {
	var get_id = id;
	var gotoSend = {
                action:"go_lb_ajax",
                nonce: "<?php echo esc_js( wp_create_nonce('go_lb_ajax_referall') ); ?>",
				the_item_id: get_id,
    };
	var url_action = "<?php echo admin_url('/admin-ajax.php'); ?>";
            jQuery.ajaxSetup({cache:true});
            jQuery.ajax({
                url: url_action,
                type:'POST',
                data: gotoSend,
				beforeSend: function() {
				jQuery("#lb-content").append('<div class="go-lb-loading"></div>');
					},
                cache: false,
                success:function(results, textStatus, XMLHttpRequest){  
					jQuery("#lb-content").innerHTML = "";
					jQuery("#lb-content").html('');  
					jQuery("#lb-content").append(results);
					window.go_req_currency = jQuery('#golb-fr-price').attr('req');
					window.go_req_points = jQuery('#golb-fr-points').attr('req');
					window.go_req_time = jQuery('#golb-fr-time').attr('req');
					window.go_cur_currency = jQuery('#golb-fr-price').attr('cur');
					window.go_cur_points = jQuery('#golb-fr-points').attr('cur');
					window.go_cur_time = jQuery('#golb-fr-time').attr('cur');
					window.go_purchase_limit = jQuery('#golb-fr-purchase-limit').attr('val');
					if(go_purchase_limit == 0){
						go_purchase_limit = Number.MAX_VALUE;
					} 
					jQuery('#go_qty').spinner({
			
						max: Math.min(Math.floor(go_cur_currency/go_req_currency),Math.floor(go_cur_points/go_req_points),go_purchase_limit),
						min: 1,
						stop: function(event, ui){
							jQuery(this).change();
						}
					});
					jQuery('#go_qty').change(function(){
						var price_raw = jQuery('#golb-fr-price').html();
						var price_sub = price_raw.substr(price_raw.indexOf(":")+2);
						var price = price_raw.replace(price_sub, go_req_currency * jQuery(this).val())
						jQuery('#golb-fr-price').html(price);
						
						var points_raw = jQuery('#golb-fr-points').html();
						var points_sub = points_raw.substr(points_raw.indexOf(":")+2);
						var points = points_raw.replace(points_sub, go_req_points * jQuery(this).val())
						jQuery('#golb-fr-points').html(points);
						
						var time_raw = jQuery('#golb-fr-time').html();
						var time_sub = time_raw.substr(time_raw.indexOf(":")+2);
						var time = time_raw.replace(time_sub, go_req_time * jQuery(this).val())
						jQuery('#golb-fr-time').html(time);
					});
					if(jQuery('.white_content').css('display') != 'none'){
						jQuery(document).keyup(function(e) { 
							if (e.keyCode == 27) { // If keypressed is escape, run this
								go_lb_closer();
							} 
						});
						jQuery('.black_overlay').click(function(){
							go_lb_closer();
						});
					}
					var done_typing = 500;
					var typing_timer;
					var recipient = jQuery('#go_recipient');
					var search_res = jQuery('#go_search_results');
					recipient.keyup(function(){
						clearTimeout(typing_timer);
						if(recipient.val().length != 0){
							typing_timer = setTimeout(function(){
								go_search_for_user(recipient.val());
							}, done_typing);
						}
					});
					recipient.focus(function(){
						if(search_res.is(':hidden')){
							search_res.empty();
							search_res.show();	
						}
					});
  				}, 
            });
	}
}
function go_fill_recipient(el){
	var el = jQuery(el);
	var val = el.text();
	var recipient = jQuery('#go_recipient');
	recipient.val(val);
	el.parent().hide();
}

function go_close_this(el){
	jQuery(el).parent().hide();	
}

function go_search_for_user(user){
	var url_action = "<?php echo admin_url('/admin-ajax.php'); ?>";
	jQuery.ajax({
		url: url_action,
		type: "POST",
		data: {
			action: 'go_search_for_user',
			user: user
		},
		success: function(data){
			var recipient = jQuery('#go_recipient');
			var search_res = jQuery('#go_search_results');
			var position = recipient.position();
			search_res.css({top: position.top + recipient.height() + 1 + "px" , left: position.left + "px", width: recipient[0].getBoundingClientRect().width - 2 + "px"});
			search_res.html(data);
		}
	});
}
</script>
	<div id="light" class="white_content">
    	<a href="javascript:void(0)" onclick="go_lb_closer();" class="go_lb_closer">Close</a>
        <div id="lb-content"></div>
    </div>
	<div id="fade" class="black_overlay"></div>
<?php
}
add_action('wp_head', 'go_frontend_lightbox_html');

function go_search_for_user(){
	global $wpdb;
	$user = $_POST['user'];
	$users = $wpdb->get_results("SELECT display_name FROM ".$wpdb->users." WHERE display_name LIKE '%".$user."%' LIMIT 0, 4");
	if($users){
		foreach($users as $user_name){
			echo '<a href="javascript:;" class="go_search_res_user" onclick="go_fill_recipient(this)">'.$user_name->display_name."</a><br/>";
		}
	}else{
		echo '<a href="javascript:;" class="go_search_res_user" onclick="go_close_this(this)">No users found</a>';	
	}
	die();	
}

function go_get_purchase_count(){
	global $wpdb;
	$table_name_go = $wpdb->prefix."go";
	$the_id = $_POST["the_item_id"];
	$user_ID = get_current_user_id();
	$purchase_count = $wpdb->get_var("SELECT `count` FROM `".$table_name_go."` WHERE `post_id`='".$the_id."' AND `uid`='".$user_ID."'"); 
	if($purchase_count == NULL){ 
		echo '0';
	} else{
		echo $purchase_count;
	}
	die();
}
add_action('wp_ajax_purchase_count', 'go_get_purchase_count');
?>