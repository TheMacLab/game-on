jQuery('#sortable_go_class_a').sortable({axis:"y"});
function go_class_a_new_input(){
	jQuery('#sortable_go_class_a').append(' <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_class_a_input" type="text" value=""/></li>');
	}
function go_class_a_save(){
	var values = jQuery("input[id='go_class_a_input']")
              .map(function(){return jQuery(this).val();}).get();
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_class_a_save',
		class_a_array: values},
		success: function(html){
			jQuery('#sortable_go_class_a').html(html);
		}
	});
	}
	
	
	
	
	
jQuery('#sortable_go_class_b').sortable({axis:"y"});
function go_class_b_new_input(){
	jQuery('#sortable_go_class_b').append(' <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_class_b_input" type="text" value=""/></li>');
	}
function go_class_b_save(){
	var values = jQuery("input[id='go_class_b_input']")
              .map(function(){return jQuery(this).val();}).get();
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_class_b_save',
		class_b_array: values},
		success: function(html){
			jQuery('#sortable_go_class_b').html(html);
		}
	});
	}
	
jQuery('#sortable_focus').sortable({axis:"y"});
function go_focus_new_input(){
	jQuery('#sortable_focus').append(' <li class="ui-state-default" class="go_list"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><input id="go_focus" type="text" value=""/></li>');
}
function go_focus_save(){
	var values = jQuery("input[id='go_focus']").map(function(){return jQuery(this).val();}).get();
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: { 
			action: 'go_focus_save',
			focus_array: values
		},
		success: function(html){
			jQuery('#sortable_focus').html(html);
		}
	});
}
	
jQuery('#sortable_go_presets').sortable({axis:"y"});

function go_preset_reset(){
	var presetsObj = {
		'Tier 1': {0:'5,5,10,30', 1:'0,0,3,9'}, 
		'Tier 2': {0:'5,5,20,60', 1:'0,0,6,18'},
		'Tier 3': {0:'5,5,40,120', 1:'0,0,12,36'},
		'Tier 4': {0:'5,5,70,210', 1:'0,0,21,63'},
		'Tier 5': {0:'5,5,110,330', 1:'0,0,33,99'}	
	};
	jQuery.ajax({
		type: "POST", 
		url: MyAjax.ajaxurl,
		data: {
			action: 'go_presets_reset',
			presets: JSON.stringify(presetsObj)
		}, 
		success: function(){
			location.reload();	
		}
	});	
}

function go_preset_save(){
	var name = jQuery("input[id='go_preset_name']")
              .map(function(){return jQuery(this).val();}).get();
var points = jQuery("input[id='go_preset_points']")
              .map(function(){return jQuery(this).val();}).get();var currency = jQuery("input[id='go_preset_currency']")
              .map(function(){return jQuery(this).val();}).get();
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_presets_save',
		go_preset_name: name,
		go_preset_points: points,go_preset_currency: currency,
		},
		success: function(html){
			jQuery('#sortable_go_presets').html(html);
		}
	});
	}
	
function go_display_help_video(url){
	jQuery('.dark').show();
	jQuery('#go_option_help_video').prop('src', url);
	jQuery('#go_option_help_video_html5_api').prop('src', url);
	videojs('#go_option_help_video', {}, function(){
		
	});
	var myplayer = videojs('#go_option_help_video');
	myplayer.ready(function(){
		jQuery('#go_option_help_video').removeClass("vjs-playing").addClass("vjs-paused");
		myplayer.load();
		myplayer.play();
	});
	jQuery('.light').show();
	if(jQuery('.dark').css('display') != 'none'){
		jQuery(document).keyup(function(e) { 
			if (e.keyCode == 27) { // If keypressed is escape, run this
				jQuery('.dark').hide();
				myplayer.pause();
				jQuery('#go_option_help_video_html5_api').prop('src', '');
				jQuery('#go_option_help_video').addClass("vjs-paused");
				jQuery('.light').hide();
			}  
		});	
		jQuery('.dark').click(function(){
			jQuery('.dark').hide();
			myplayer.pause();
			jQuery('#go_option_help_video_html5_api').prop('src', '');
			jQuery('#go_option_help_video').addClass("vjs-paused");
			jQuery('.light').hide();
		});
	}
}