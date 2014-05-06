<?php
function store_edit_jquery(){
?>
<script type="text/javascript"> 
var focus_switch = jQuery('#go_mta_focus_item_switch');
var focus_type = jQuery('tr.cmb-type-select.cmb_id_go_mta_focuses');
focus_switch.click(function(){
	if(focus_switch.prop('checked')){
		focus_type.show('slow');
	} else{
		focus_type.hide('slow');
	}
});
if(focus_switch.prop('checked')){
	focus_type.show('slow');
} else{
	focus_type.hide('slow');
}

var exchange_switch = jQuery('#go_mta_store_exchange_switch');
var exchange_map = new Array(jQuery('tr.cmb-type-text.cmb_id_go_mta_store_exchange_currency'), jQuery('tr.cmb-type-text.cmb_id_go_mta_store_exchange_points'), jQuery('tr.cmb-type-text.cmb_id_go_mta_store_exchange_time'));

exchange_switch.click(function(){
	if(jQuery(this).prop('checked')){
		exchange_map.forEach(function(el){
			el.show('slow');
		});
	} else{
		exchange_map.forEach(function(el){
			el.hide('slow');
		});
	}
});
if(exchange_switch.prop('checked')){
	exchange_map.forEach(function(el){
		el.show('slow');
	});
} else{
	exchange_map.forEach(function(el){
		el.hide('slow');
	});
}
</script>
<?php 
}
add_action('admin_footer', 'store_edit_jquery');
?>