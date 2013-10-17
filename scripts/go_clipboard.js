function go_clipboard_class_a_choice(){
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_clipboard_intable',
		go_clipboard_class_a_choice: jQuery('#go_clipboard_class_a_choice').val()},
		success: function(html){
			jQuery('#go_clipboard_table_body').html('');
			jQuery('#go_clipboard_table_body').html(html);	
		}
	});
	
	}
function go_clipboard_add(id){
	 var values = [];
	 jQuery("input:checkbox[name=go_selected]:checked").each(function()
{
    values.push(jQuery(this).val())
});
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_clipboard_add',
		ids: values,
		points:jQuery('#go_clipboard_points').val(),
		currency:jQuery('#go_clipboard_currency').val(),
		time:jQuery('#go_clipboard_time').val(),
		reason:jQuery('#go_clipboard_reason').val(),
		infractions:jQuery('#go_clipboard_infractions').val()},
		success: function(html){
				
		}
	});
	}