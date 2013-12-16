<?php
function task_edit_jquery() {
?>
<script type="text/javascript">

jQuery('#go_mta_complete_lock').click(function(){
	if(jQuery('#go_mta_complete_lock').prop('checked')){
		jQuery('tr.cmb-type-text.cmb_id_go_mta_complete_unlock').show('slow');
	} else{
		jQuery('tr.cmb-type-text.cmb_id_go_mta_complete_unlock').hide('slow');
	}
});
if(jQuery('#go_mta_complete_lock').prop('checked')){
	jQuery('tr.cmb-type-text.cmb_id_go_mta_complete_unlock').show('slow');
} else{
	jQuery('tr.cmb-type-text.cmb_id_go_mta_complete_unlock').hide('slow');
}

jQuery('#go_mta_mastery_lock').click(function(){
	if(jQuery('#go_mta_mastery_lock').prop('checked')){
		jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').show('slow');
	} else{
		jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').hide('slow');
	}
});
if(jQuery('#go_mta_mastery_lock').prop('checked')){
	jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').show('slow');
} else{
	jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').hide('slow');
}
	
jQuery('#go_mta_task_repeat').click(function() {
	if (jQuery('#go_mta_task_repeat').prop('checked')) {
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').show('slow');
		jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').show('slow');
	} else {
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide('slow');
		jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').hide('slow');
	}
});
if (jQuery('#go_mta_task_repeat').prop('checked')) {
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').show('slow');
} else {
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').hide('slow');
}
</script>
<?php
}
add_action( 'admin_footer', 'task_edit_jquery' );
?>
