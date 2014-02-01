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

var test_num = 1;
jQuery("#go_mta_test_lock_num").ready(function () {
	test_num = jQuery("#go_mta_test_lock_num").val();
});

function test_show() {
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_num').show('slow');
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').show('slow');

	test_num = jQuery("#go_mta_test_lock_num").val();

	if (test_num == 1) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').show('slow');
	} else if (test_num == 2) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_1').show('slow');
	} else if (test_num == 3) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_1').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_2').show('slow');
	} else if (test_num == 4) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_1').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_2').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_3').show('slow');
	} else if (test_num == 5) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_1').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_2').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_3').show('slow');
	
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_4').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_4').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_4').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_4').show('slow');
	}
}

function test_hide_all() {
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_num').hide('slow');
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_0').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_0').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_0').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_0').hide('slow');
	
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_1').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_1').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_1').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_1').hide('slow');
	
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_2').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_2').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_2').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_2').hide('slow');

	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_3').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_3').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_3').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_3').hide('slow');

	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_type_4').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_question_4').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_answers_4').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_lock_key_4').hide('slow');
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

jQuery('#go_mta_test_lock_num').change(function() {
	test_hide_all();
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_lock_num').show('slow');
	test_show();
});

function test_m_show() {
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_num').show('slow');
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').show('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').show('slow');

	test_num = jQuery("#go_mta_test_mastery_lock_num").val();

	if (test_num == 1) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').show('slow');
	} else if (test_num == 2) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_1').show('slow');
	} else if (test_num == 3) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_1').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_2').show('slow');
	} else if (test_num == 4) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_1').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_2').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_3').show('slow');
	} else if (test_num == 5) {
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_1').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_1').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_2').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_2').show('slow');

			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_3').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_3').show('slow');
	
			jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_4').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_4').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_4').show('slow');
			jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_4').show('slow');
	}
}

function test_m_hide_all() {
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_num').hide('slow');
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_0').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_0').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_0').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_0').hide('slow');
	
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_1').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_1').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_1').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_1').hide('slow');
	
	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_2').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_2').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_2').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_2').hide('slow');

	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_3').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_3').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_3').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_3').hide('slow');

	jQuery('tr.cmb-type-select.cmb_id_go_mta_test_mastery_lock_type_4').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_question_4').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_answers_4').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_test_mastery_lock_key_4').hide('slow');
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

jQuery('#go_mta_task_mastery').click(function(){
	if(jQuery('#go_mta_task_mastery').prop('checked')){
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').show('slow');
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
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_lock').hide('slow');
		jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').hide('slow');
		jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock").hide("slow");
		test_m_hide_all();
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat').hide("slow");
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide("slow");
		jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').hide("slow");
	}
});
if(jQuery('#go_mta_task_mastery').prop('checked')){
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').show('slow');
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
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_lock').hide('slow');
	jQuery('tr.cmb-type-text.cmb_id_go_mta_mastery_unlock').hide('slow');
	jQuery("tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock").hide("slow");
	test_m_hide_all();
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat').hide("slow");
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide("slow");
	jQuery('tr.cmb-type-text.cmb_id_go_mta_repeat_amount').hide("slow");
}
////////
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
