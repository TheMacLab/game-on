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
