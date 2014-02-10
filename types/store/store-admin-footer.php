<?php
function store_edit_jquery(){
?>
<script type="text/javascript"> 
jQuery('#go_mta_focus_item_switch').click(function(){
	if(jQuery('#go_mta_focus_item_switch').prop('checked')){
		jQuery('tr.cmb-type-select.cmb_id_go_mta_focuses').show('slow');
	} else{
		jQuery('tr.cmb-type-select.cmb_id_go_mta_focuses').hide('slow');
	}
});
if(jQuery('#go_mta_focus_item_switch').prop('checked')){
	jQuery('tr.cmb-type-select.cmb_id_go_mta_focuses').show('slow');
} else{
	jQuery('tr.cmb-type-select.cmb_id_go_mta_focuses').hide('slow');
}
</script>
<?php 
}
add_action('admin_footer', 'store_edit_jquery');
?>