<?php
function task_edit_jquery() {
?>
<script type="text/javascript">
var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
if(!is_chrome){
	if(jQuery('.datepicker').length){
		jQuery('.datepicker').datepicker({dateFormat: "yy-mm-dd"});
	}
}
var count = 1;
function go_add_decay_table_row(){
	jQuery('#go_list_of_decay_dates tbody').last().append('<tr><td><input name="go_mta_task_decay_calendar[]" id="go_mta_task_decay_calendar' + count + '" class="datepicker" type="date"/></td><td><input name="go_mta_task_decay_percent[]" id="go_mta_task_decay_percent" type="text"/></td></tr>');	
	if(!is_chrome){
		if(jQuery('.datepicker').length){
			jQuery('.datepicker').datepicker({dateFormat: "yy-mm-dd"});
		}
	}
	count++;
}

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

var test_num = 1;
jQuery("#go_mta_test_lock_num").ready(function () {
	test_num = jQuery("#go_mta_test_lock_num").val();
});

function test_show() {
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_lock_loot').show('slow');
	if (jQuery('#go_mta_test_lock_loot').prop('checked')) {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_loot_mod').show('slow');
	}
	jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_completion').show('slow');
}

function test_hide_all() {
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_lock_loot').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_loot_mod').hide('slow');
	jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_completion').hide('slow');
}

jQuery('#go_mta_test_lock').click(function(){
	if(jQuery('#go_mta_test_lock').prop('checked')){
		test_show();
	} else{
		test_hide_all();
	}
});

if(jQuery('#go_mta_test_lock').prop('checked')){
	test_hide_all();
	test_show();
} else{
	test_hide_all();
}

if (jQuery('#go_mta_test_lock_loot').prop("checked")) {
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_loot_mod').show('slow');
} else {
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_loot_mod').hide('slow');
}
jQuery('#go_mta_test_lock_loot').click(function() {
	if (jQuery('#go_mta_test_lock_loot').prop("checked")) {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_loot_mod').show('slow');
	} else {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_loot_mod').hide('slow');
	}
});

if (jQuery('#go_mta_test_mastery_lock_loot').prop("checked")) {
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_loot_mod').show('slow');
} else {
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_loot_mod').hide('slow');
}
jQuery('#go_mta_test_mastery_lock_loot').click(function() {
	if (jQuery('#go_mta_test_mastery_lock_loot').prop("checked")) {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_loot_mod').show('slow');
	} else {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_loot_mod').hide('slow');
	}
});

jQuery('#go_mta_test_lock_num').change(function() {
	test_hide_all();
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_num').show('slow');
	test_show();
});

function test_m_show() {
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock_loot').show('slow');
	if (jQuery('#go_mta_test_mastery_lock_loot').prop('checked')) {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_loot_mod').show('slow');
	}
	jQuery('tr.cmb-type-go_test_field_mastery.cmb_id_go_mta_test_lock_mastery').show('slow');
}

function test_m_hide_all() {
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock_loot').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_loot_mod').hide('slow');
	jQuery('tr.cmb-type-go_test_field_mastery.cmb_id_go_mta_test_lock_mastery').hide('slow');
}

jQuery('#go_mta_test_mastery_lock').click(function(){
	if(jQuery('#go_mta_test_mastery_lock').prop('checked')){
		test_m_show();
	} else{
		test_m_hide_all();
	}
});

if(jQuery('#go_mta_test_mastery_lock').prop('checked')){
	test_m_hide_all();
	test_m_show();
} else {
	test_m_hide_all();
}

jQuery('#go_mta_test_mastery_lock_num').change(function() {
	test_m_hide_all();
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_num').show('slow');
	test_m_show();
});

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

jQuery('#go_mta_task_mastery').click(function(){
	if(!jQuery('#go_mta_task_mastery').prop('checked')){
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').show('slow');
		jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload").show("slow");
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_lock').show('slow');
		if (jQuery('#go_mta_mastery_lock').prop('checked')) {
			jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').show('slow');
		}
		jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock").show("slow");
		if (jQuery("#go_mta_test_mastery_lock").prop('checked')) {
			test_m_show();
		}
		
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat').show("slow");
		if (jQuery('#go_mta_task_repeat').prop('checked')) {
			jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').show("slow");
			jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').show("slow");
		}
	} else{
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').hide('slow');
		jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload").hide("slow");
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_lock').hide('slow');
		jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').hide('slow');
		jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock").hide("slow");
		test_m_hide_all();
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat').hide("slow");
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide("slow");
		jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').hide("slow");
	}
});
if(!jQuery('#go_mta_task_mastery').prop('checked')){
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').show('slow');
	jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload").show("slow");
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_lock').show('slow');
	if (jQuery('#go_mta_mastery_lock').prop('checked')) {
		jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').show('slow');
	}
	jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock").show("slow");
	if (jQuery("#go_mta_test_mastery_lock").prop('checked')) {
		test_m_show();
	}
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat').show("slow");
	if (jQuery('#go_mta_task_repeat').prop('checked')) {
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').show("slow");
		jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').show("slow");
	}
} else{
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').hide('slow');
	jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload").hide("slow");
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_lock').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').hide('slow');
	jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock").hide("slow");
	test_m_hide_all();
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat').hide("slow");
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide("slow");
	jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').hide("slow");
}
////////
	
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
var is_final_task = 
<?php 
	$id = get_the_id();
	$chain = get_post_meta($id, "chain", true);
	if (!empty($chain)) {
		$posts_in_chain = get_posts(array(
			'post_type' => 'tasks',
			'taxonomy' => 'task_chains',
			'term' => $chain,
			'order' => 'ASC',
			'meta_key' => 'chain_position',
			'orderby' => 'meta_value_num',
			'posts_per_page' => '-1'
		));
		$last_task = end($posts_in_chain)->ID;
		if ($id == $last_task) {
			echo '"true"';
		} else {
			echo '"false"';
		}
	} else {
		echo '"false"';
	}
?>;
if (is_final_task == "true") {
	jQuery(document).on('click', 'input[name="tax_input[task_chains][]"]', function(){
		jQuery('input[name="tax_input[task_chains][]"]').each(function(){
			if(jQuery(this).prop('checked')){
				jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').show('slow');
				return false;
			}else{
				jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').hide('slow');
			}
		});
	});
	jQuery('input[name="tax_input[task_chains][]"]').each(function(){
		if(jQuery(this).prop('checked')){
			jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').show('slow');
			return false;
		}else{
			jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').hide('slow');
		}
	});
} else {
	jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').hide();
}
</script>
<?php
}
add_action( 'admin_footer', 'task_edit_jquery' );

?>
