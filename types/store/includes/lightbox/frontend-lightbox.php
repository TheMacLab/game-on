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
	} elseif(!$custom_fields['go_mta_store_repeat_amount']){
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
	
	if(get_user_meta($user_ID, 'go_focus', true) != null){
		$user_focuses = (array) get_user_meta($user_ID, 'go_focus', true);
	}
	
	if($custom_fields['go_mta_focuses'][0]){
		$item_focus = $custom_fields['go_mta_focuses'][0];
	}
	
	if(!in_array($item_focus, $user_focuses) || empty($user_focuses)){
		if($purchase_count < $purchase_limit || $purchase_limit == 0){
			if($user_time>=$minutes_required || !$minutes_required){ 
		?>
			<div id="golb-fr-price" class="golb-fr-boxes-<?php echo $gold_color; ?>" req="<?php echo $req_currency; ?>" cur="<?php echo $user_gold; ?>"><?php echo go_return_options('go_currency_name').': '.$req_currency; ?></div>
			<div id="golb-fr-points" class="golb-fr-boxes-<?php echo $points_color; ?>" req="<?php echo $req_points; ?>" cur="<?php echo $user_points; ?>"><?php echo go_return_options('go_points_name').': '.$req_points; ?></div>
			<div id="golb-fr-time" class="golb-fr-boxes-<?php echo $time_color; ?>" req="<?php echo $req_time; ?>" cur="<?php echo $user_time; ?>">Time: <?php echo $req_time; ?></div>
			<div id="golb-fr-qty" class="golb-fr-boxes-g">Qty: <input id="go_qty" style="width: 40px;height: 30px;font-size: 11px; margin-right:0px;" value="1" disabled="disabled" /></div>
			<!--<div id="golb-fr-recipient" class="golb-fr-boxes-<?php //echo $buy_color; ?>"><input id="go_recipient" type="text"/></div>!-->
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
			} else{
				echo 'You require more time to view this item.';	
			}
		} else{
			echo 'You\'ve reached the maximum purchase limit.';	
		}
	}else{
		echo 'You already have this '.go_return_options('go_focus_name').'!';	
	}
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
	document.getElementById('light').style.display='block';
	document.getElementById('fade').style.display='block';
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
  				}, 
            });
	}
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