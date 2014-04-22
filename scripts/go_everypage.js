function hideVid(){
	if(jQuery('#go_option_help_video').length){
		myplayer = videojs('go_option_help_video');
	}
	jQuery('.dark').hide();
	jQuery('.light').hide();
	if(jQuery('#go_option_help_video').length){
		myplayer.pause();
		myplayer.dispose();
	}
	if(jQuery('#go_video_iframe').length){
		jQuery('#go_video_iframe').remove();
	}
	jQuery('#go_help_video_container').append('<video id="go_option_help_video" class="video-js vjs-default-skin vjs-big-play-centered" controls height="100%" width="100%" ><source src="" type="video/mp4"/></video>');
}

function go_display_help_video(url){
	jQuery('.dark').show();
	if(url.indexOf('youtube') != -1 || url.indexOf('vimeo') != -1){
		if(url.indexOf('youtube') != -1 ){
			jQuery('#go_help_video_container').html('<iframe id="go_video_iframe" width="100%" height="100%" src="'+ url +'" frameborder="0" allowfullscreen></iframe>');
		}
		if(url.indexOf('vimeo') != -1){
			jQuery('#go_help_video_container').html('<iframe id="go_video_iframe" src="' + url + '" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
		}
	}
	jQuery('#go_help_video_container').show();
	if(jQuery('#go_option_help_video').length){
		var myplayer = videojs('go_option_help_video');
		myplayer.ready(function(){
			myplayer.src(url);
			myplayer.load();
			myplayer.play();
			videoStatus = 'playing';
		});
	}
	jQuery('.light').show();
	if(jQuery('.dark').css('display') != 'none'){
		jQuery(document).keydown(function(e) { 
			if (e.keyCode == 27) { // If keypressed is escape, run this
				hideVid();
			} 
			if(e.keyCode == 32){
				e.preventDefault();
				if(!myplayer.paused()){
					myplayer.pause();
				}else{
					myplayer.play();	
				}
			}
		});	
		jQuery('.dark').click(function(){
			hideVid();
		});
	}
}

function go_admin_bar_add(){
		jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_admin_bar_add',
		go_admin_bar_points_points:jQuery('#go_admin_bar_points_points').val(),
		go_admin_bar_points_reason:jQuery('#go_admin_bar_points_reason').val(),
		go_admin_bar_currency_points:jQuery('#go_admin_bar_currency_points').val(),
		go_admin_bar_currency_reason:jQuery('#go_admin_bar_currency_reason').val(),
		go_admin_bar_minutes_points:jQuery('#go_admin_bar_minutes_points').val(),
		go_admin_bar_minutes_reason:jQuery('#go_admin_bar_minutes_reason').val()},
		success: function(html){
	    jQuery('#go_admin_bar_points_points').val('');
		jQuery('#go_admin_bar_points_reason').val('');
		jQuery('#go_admin_bar_currency_points').val('');
		jQuery('#go_admin_bar_currency_reason').val('');
		jQuery('#go_admin_bar_minutes_points').val('');
		jQuery('#go_admin_bar_minutes_reason').val('');
		jQuery('#admin_bar_add_return').html(html);
		}
	});
	
	}
	
function go_admin_bar_stats_page_button(id){
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: { 
			action: 'go_admin_bar_stats',
			uid: id
		},
		success: function(html){
			jQuery('#go_stats_white_overlay').html(html);
			jQuery('#go_stats_page_black_bg').show();
			jQuery('#go_stats_white_overlay').show();
			jQuery('#go_stats_hidden_input').val(id);
			
			//Check if store lightbox is visible
			if(jQuery('#go_stats_white_overlay').css('display') != 'none'){
				//Monitors for keyboard input
				jQuery(document).keydown(function(e) {
					if(jQuery('.white_content').css('display') == 'none' && e.keyCode == 27){ 
						go_stats_close(); //Close out stats panel
					}
				});
				jQuery('#go_stats_page_black_bg').click(function(){
					go_stats_close();
				});
			}
		}
	});
}

function go_stats_close(){
	jQuery('#go_stats_white_overlay').hide();
	jQuery('#go_stats_page_black_bg').hide();
	jQuery('#go_stats_lay').hide();
}
	
function go_stats_task_list(){
		jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:1, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_encountered_list').html(html);
		}
	});

	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:2, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_accepted_list').html(html);
		}
	});

	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:3, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_completed_list').html(html);
		}
	});

	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:4, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_mastered_list').html(html);
		}
	});
	
}

function go_stats_item_list(){
	jQuery.ajax({
		type: "POST",
		url: MyAjax.ajaxurl,
		data:{
			action: 'go_stats_item_list',
			uid: jQuery('#go_stats_hidden_input').val()	
		}, 
		success: function(html){
			jQuery('#go_stats_item_list').html(html);
		}
	});	
}
	
function go_stats_third_tab(){
jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_points', uid: jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_points').html(html);
		}
	});	
jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_currency', uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_currency').html(html);
		}
	});
jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_minutes', uid: jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_minutes').html(html);
		}
	});
	
	}
function go_stats_leaderboard_choice(){
	var class_values = [];
	var focus_values = [];
	jQuery('#go_stats_class_a_list :checked').each(function() {
		class_values.push(jQuery(this).val());
	});
	jQuery('#go_focuses :checked').each(function(){
		focus_values.push(jQuery(this).val());
	});
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_leaderboard',
		class_a_choice: class_values,
		focuses: focus_values,
		order: jQuery('#go_stats_leaderboard_select').val()},
		success: function(html){
			jQuery('#go_stats_leaderboard_table_body').html(html);
		}
	});
}
	 function go_mark_seen(date, type){
			jQuery.ajax({
				url: MyAjax.ajaxurl,
				type: "POST",
				data:{
					action: 'go_mark_read',
					date: date,
					type: type
				},
				success: function(data){
					data = JSON.parse(data);
					if(data[1] == 'remove'){
						jQuery('#wp-admin-bar-'+data[0]).remove();
						jQuery('#go_messages_bar').html(data[2]);
						if(data[2] == 0){
							jQuery('#go_messages_bar').css('background', ' -webkit-radial-gradient( 5px -9px, circle, white 8%, green 26px )');
							}
						}else if( data[1] == 'unseen'){
							jQuery('#wp-admin-bar-'+data[0]+' div').css('color','');
							jQuery('#go_messages_bar').html(data[2]);
							if(data[2] == 0){
							jQuery('#go_messages_bar').css('background', ' -webkit-radial-gradient( 5px -9px, circle, white 8%, green 26px )');
							}
							} 
					
				}
			});
				}
function go_add_uploader(){
	jQuery('#go_uploader').append('<div id="go_uploader"><input type="file" name="go_attachment[]"/><br/>');
	}