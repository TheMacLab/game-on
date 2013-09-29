function apply_presets(){
		var points =jQuery('#go_presets option:selected').attr('points');
		var currency =jQuery('#go_presets option:selected').attr('currency');
		jQuery('#go_mta_task_points').val(points);
		jQuery('#go_mta_task_currency').val(currency);
							
							}