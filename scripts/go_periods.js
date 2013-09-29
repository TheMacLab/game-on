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
	
	
jQuery('#sortable_go_presets').sortable({axis:"y"});

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