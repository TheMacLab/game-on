<?php

function task_edit_jquery() {
	$id = get_the_id();
	$task_chains = get_the_terms($id, 'task_chains');
	if (!empty($task_chains)) {
		$chain = array_shift(array_values($task_chains))->name;
	}
	$custom = get_post_custom(get_the_id());
?>
<script type="text/javascript">
console.log("<?php echo plugin_dir_url(__FILE__);?>");

jQuery('.go_reward_points, .go_reward_currency, .go_reward_bonus_currency').on('keyup', function(){
	var reward_stage = jQuery(this).attr('stage');
	var reward_type = jQuery(this).attr('reward');
	jQuery('input[stage=' + reward_stage + '][reward = ' + reward_type + ']').val(jQuery(this).val());
});

jQuery('.go_task_settings_accordion').click(function(){
	jQuery(this).children('.go_triangle_container').children('.go_task_accordion_triangle').toggleClass('down');
});

function go_toggle_settings_rows(stage_settings, condensed, number) {
	condensed = typeof condensed !== 'undefined' ? condensed : false;
	for(setting in stage_settings){
		if(condensed === true){
			stage_settings[setting].addClass('condensed').children().addClass('condensed');
		}
		stage_settings[setting].toggle('slow');
	}
	if (number) {
		for (i = 1; i < 6; i++) {
			if (i == number) {	
				continue;
			}
				stage_accordions[i].removeClass("opened");
				if (stage_settings != task_settings) {
					jQuery("#go_advanced_task_settings_accordion").removeClass("opened");
				}
			if (jQuery('#go_calendar_checkbox').prop('checked') && jQuery("#go_advanced_task_settings_accordion").hasClass('opened')) {
				calendar_row.show('slow');
				future_row.hide();
			} else {
				calendar_row.hide('slow');
				future_row.hide();
			}
			if (jQuery('#go_future_checkbox').prop('checked') && jQuery("#go_advanced_task_settings_accordion").hasClass('opened')) {
				future_row.show('slow');
				calendar_row.hide();
			} else {
				future_row.hide('slow');
				calendar_row.hide();
			}
			if (stage_settings != task_settings){
				for (settings in task_settings) {
					task_settings[settings].hide('slow');
				}
			} else {
				for (settings in stage_settings_rows[i]) {
					if (stage_settings_rows[i][settings] != null) {
					stage_settings_rows[i][settings].hide('slow');
					}
				}
			}
			for (settings in stage_settings_rows[i]) {
				if (stage_settings_rows[i][settings] != null) {
					stage_settings_rows[i][settings].hide('slow');
				}
			}
		}
	}
}

var stage_accordion_rows = {
	1: jQuery('tr.cmb-type-go_settings_accordion.cmb_id_stage_one_settings'),
	2: jQuery('tr.cmb-type-go_settings_accordion.cmb_id_stage_two_settings'),
	3: jQuery('tr.cmb-type-go_settings_accordion.cmb_id_stage_three_settings'),
	4: jQuery('tr.cmb-type-go_settings_accordion.cmb_id_stage_four_settings'),
	5: jQuery('tr.cmb-type-go_settings_accordion.cmb_id_stage_five_settings')
}

var stage_accordions = {
	1: jQuery('#go_stage_one_settings_accordion'),
	2: jQuery('#go_stage_two_settings_accordion'),
	3: jQuery('#go_stage_three_settings_accordion'),
	4: jQuery('#go_stage_four_settings_accordion'),
	5: jQuery('#go_stage_five_settings_accordion')
}

var stage_settings_rows = {
	1: [
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_points'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_currency'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_bonus_currency'),
		jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_encounter_admin_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_encounter_url_key'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_encounter_upload'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_encounter_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_encounter_lock_loot'),
		jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_encounter_lock_loot_mod'),
		jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_encounter'),
		jQuery('tr.cmb_id_go_mta_stage_one_badge')
	],
	2: [
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_points'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_currency'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_bonus_currency'),
		jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_accept_admin_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_accept_url_key'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_accept_upload'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_accept_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_accept_lock_loot'),
		jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_accept_lock_loot_mod'),
		jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_accept'),
		jQuery('tr.cmb_id_go_mta_stage_two_badge')
	],
	3: [
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_points'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_currency'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_bonus_currency'),
		jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_completion_admin_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_upload'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_completion_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_completion_lock_loot'),
		jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_completion_lock_loot_mod'),
		jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_completion'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_mastery'),
		jQuery('tr.cmb_id_go_mta_stage_three_badge')
	],
	4: [
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_points'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_currency'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_bonus_currency'),
		jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_mastery_admin_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock_loot'),
		jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_mastery_lock_loot_mod'),
		jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_mastery'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_privacy'),
		jQuery('tr.cmb_id_go_mta_stage_four_badge'),
		jQuery('tr.cmb-type-go_bonus_loot.cmb_id_go_mta_mastery_bonus_loot')
	],
	5: [
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_points'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_currency'),
		jQuery('tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_bonus_currency'),
		jQuery('tr.cmb-type-go_repeat_amount.cmb_id_go_mta_repeat_amount'),
		jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_repeat_admin_lock'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_repeat_upload'),
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_repeat_privacy'),
		jQuery('tr.cmb_id_go_mta_stage_five_badge')
	]
}

// Advanced settings accordion //

var task_settings = [
	jQuery('tr.cmb-type-go_rank_list.cmb_id_go_mta_req_rank'),
	jQuery('tr.cmb-type-go_future_filters.cmb_id_go_mta_time_filters'),
	jQuery('tr.cmb-type-text.cmb_id_go_mta_bonus_currency_filter'),
	jQuery('tr.cmb-type-text.cmb_id_go_mta_penalty_filter'),
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_focus_category_lock'),
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_three_stage_switch'),
	jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_five_stage_switch'), 
	jQuery('tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order'),
	jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message')
];

go_toggle_settings_rows(task_settings);

var in_chain = 
<?php 
	if (!empty($chain)) {
		echo "true";
	} else {
		echo "false";
	}
?>;

var is_final_task = 
<?php 
	if (!empty($chain)) {
		$posts_in_chain = get_posts(array(
			'post_type' => 'tasks',
			'post_status' => 'publish',
			'taxonomy' => 'task_chains',
			'term' => $chain,
			'order' => 'ASC',
			'meta_key' => 'chain_position',
			'orderby' => 'meta_value_num',
			'posts_per_page' => '-1'
		));
		$last_task = end($posts_in_chain)->ID;
		if ($id == $last_task) {
			echo "true";
		} else {
			echo "false";
		}
	} else {
		echo "false";
	}
?>;

jQuery('#go_advanced_task_settings_accordion').click(function(){
	jQuery(this).toggleClass('opened');
	go_toggle_settings_rows(task_settings, true, 6);
	if (in_chain && jQuery(this).hasClass('opened')) {
		jQuery('tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order').show('slow');
		if (is_final_task) {
			jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').show();
		} else {
			if (jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').is(":visible")) {
				jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').hide();
			}
		}
	} else {
		if (jQuery('tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order').is(":visible")) {
			jQuery('tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order').hide();	
		}
		if (jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').is(":visible")) {
			jQuery('tr.cmb-type-text.cmb_id_go_mta_final_chain_message').hide();
		}
	}
	
});

////////////////////////////////////

// Modifier Date Picker //
var calendar_row = jQuery('tr.cmb-type-go_decay_table.cmb_id_go_mta_date_picker');
var future_row = jQuery('tr.cmb-type-go_time_modifier_inputs.cmb_id_go_mta_time_modifier');

calendar_row.hide();
future_row.hide();

calendar_row.addClass('condensed').children().addClass('condensed');
future_row.addClass('condensed').children().addClass('condensed');

jQuery('#go_calendar_checkbox').click(function () {
	jQuery('#go_future_checkbox').prop('checked', false);
	if (jQuery('#go_calendar_checkbox').prop('checked')) {
		calendar_row.show('slow');
		future_row.hide();
	} else {
		calendar_row.hide('slow');
	}
});
jQuery('#go_future_checkbox').click(function () {
	jQuery('#go_calendar_checkbox').prop('checked', false);
	if (jQuery('#go_future_checkbox').prop('checked')) {
		future_row.show('slow');
		calendar_row.hide();
	} else {
		future_row.hide('slow');
	}
});

var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
jQuery( document ).ready( function(){
	if( !is_chrome ){
		if ( jQuery( 'input.go_datepicker' ).length ){
			jQuery( 'input.go_datepicker' ).each( function () {
				jQuery( this ).datepicker({ dateFormat: "yy-mm-dd" });
			});
		}
		<?php 
		wp_enqueue_style('ptTimeSelectCSS', plugin_dir_url(__FILE__).'includes/jQuery.ptTimeSelect-0.8/src/jquery.ptTimeSelect.css');
		wp_enqueue_script('ptTimeSelectJS', plugin_dir_url(__FILE__).'includes/jQuery.ptTimeSelect-0.8/src/jquery.ptTimeSelect.js');
		?>
		if ( jQuery('input.custom_time').length ) {
			jQuery('input.custom_time').each( function () {
				jQuery( this ).ptTimeSelect();
				var time = jQuery( this ).attr('value');
				console.log(parseInt(time.substring( 0, time.search( ':' ))));
				var hour = (((parseInt(time.substring( 0, time.search( ':' ))) - 12) >= 10) ? time.substring( 0, time.search( ':' ) ): '0' + (parseInt(time.substring( 0, time.search( ':' ))) - 12));
				console.log(hour);
				jQuery( this ).text( 'what' );
			});
		}
	}
});

function go_add_decay_table_row(){
	jQuery('#go_list_of_decay_dates tbody').last().append('<tr><td><input name="go_mta_task_decay_calendar[]" class="go_datepicker custom_date" type="date" placeholder="Click for Date"/> @ <input type="time" name="go_mta_task_decay_calendar_time[]" class="custom_time" placeholder="Click for Time" value="00:00"/></td><td><input name="go_mta_task_decay_percent[]" type="text" placeholder="Modifier"/></td></tr>');	
	if(!is_chrome){
		if(jQuery('input.go_datepicker').length){
			jQuery('input.go_datepicker').each( function () {
				jQuery(this).datepicker({dateFormat: "yy-mm-dd"});
			});
		}
	}
}
function go_remove_decay_table_row(){
	jQuery('#go_list_of_decay_dates tbody tr').last('.go_datepicker').remove();
}

////////////////////////////////////

// Admin Lock //

function toggle_admin_lock(accordion, stage) {
	if (typeof(accordion) === 'string') {
		if (jQuery(accordion).hasClass("opened")) {
			if (jQuery('#go_mta_'+stage+'_admin_lock_checkbox').prop('checked')) {
				if (!jQuery('#go_mta_'+stage+'_admin_lock_input').is(':visible')) {
					jQuery('#go_mta_'+stage+'_admin_lock_input').show('slow');
				}
			} else {
				if (jQuery('#go_mta_'+stage+'_admin_lock_input').is(':visible')) {
					jQuery('#go_mta_'+stage+'_admin_lock_input').hide('slow');
				}
			}
		} else {
			if (jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_'+stage+'_admin_lock').is(':visible')) {
				jQuery('tr.cmb-type-go_admin_lock.cmb_id_go_mta_'+stage+'_admin_lock').hide();
			}
		}
	} else {
		if (jQuery('#go_mta_'+stage+'_admin_lock_checkbox').prop('checked')) {
			if (!jQuery('#go_mta_'+stage+'_admin_lock_input').is(':visible')) {
				jQuery('#go_mta_'+stage+'_admin_lock_input').show('slow');
			}
		} else {
			if (jQuery('#go_mta_'+stage+'_admin_lock_input').is(':visible')) {
				jQuery('#go_mta_'+stage+'_admin_lock_input').hide('slow');
			}
		}
	}
}

jQuery('.go_admin_lock_checkbox').click(function () {
	var stage = this.id.getMid("go_mta_", "_admin_lock_checkbox");
	toggle_admin_lock(null, stage);
});

////////////////////////////////////

// Task Tests //

function toggle_tests(accordion, stage) {
	if (typeof(stage) === 'string') {
		if (jQuery(accordion).hasClass("opened")) {
			if (jQuery('#go_mta_test_'+stage+'_lock').prop('checked')) {
				jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').show('slow');
				if (jQuery('#go_mta_test_'+stage+'_lock_loot').prop('checked')) {
					if (!jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
						jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').show('slow');
					}
				} else {
					if (jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
						jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').hide();
					}
				}
				jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).show('slow');
			} else {
				if (jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').is(':visible')) {
					jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').hide();
				}
				if (jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
					jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').hide();
				}
				if (jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is(':visible')) {
					jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide();
				}
			}
		} else {
			if (jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').is(':visible')) {
				jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').hide();
			}
			if (jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
				jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').hide();
			}
			if (jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is(':visible')) {
				jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide();
			}
		}
	}
}

function toggle_test_all(stage) {
	if (typeof(stage) === 'string') {
		if (jQuery('#go_mta_test_'+stage+'_lock').prop('checked')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').show('slow');
			if (jQuery('#go_mta_test_'+stage+'_lock_loot').prop('checked')) {
				if (!jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
					jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').show('slow');
				}
			} else {
				if (jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
					jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').hide();
				}
			}
			jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).show('slow');
		} else {
			if (jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').is(':visible')) {
				jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_test_'+stage+'_lock_loot').hide('hide');
			}
			if (jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
				jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').hide('hide');
			}
			if (jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is(':visible')) {
				jQuery('tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide('hide');
			}
		}
	}
}

function toggle_test_loot(stage) {
	if (typeof(stage) === 'string') {
		if (jQuery('#go_mta_test_'+stage+'_lock_loot').prop('checked')) {
			if (!jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
				jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').show('slow');
			}
		} else {
			if (jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').is(':visible')) {
				jQuery('tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_'+stage+'_lock_loot_mod').hide('slow');
			}
		}
	}
}

jQuery('#go_mta_test_encounter_lock, #go_mta_test_accept_lock, #go_mta_test_completion_lock, #go_mta_test_mastery_lock').click(function() {
	var stage = this.id.getMid("go_mta_test_", "_lock");
	toggle_test_all(stage);
});

jQuery('#go_mta_test_encounter_lock_loot, #go_mta_test_accept_lock_loot, #go_mta_test_completion_lock_loot, #go_mta_test_mastery_lock_loot').click(function() {
	var stage = this.id.getMid("go_mta_test_", "_lock_loot");
	toggle_test_loot(stage);
});

////////////////////////////////////

// Badge Rewarding //

jQuery('.go_badge_input_toggle').each( function () {
	stage = jQuery(this).attr('stage');
	if (jQuery(this).prop('checked')) {
		jQuery('.go_badge_input[stage=' + stage + ']').show('slow');
		jQuery('button[name="go_badge_input_add"][stage=' + stage + ']').show('slow');
		jQuery('button[name="go_badge_input_remove"][stage=' + stage + ']').show('slow');
	} else {
		jQuery('.go_badge_input[stage=' + stage + ']').hide('slow');
		jQuery('button[name="go_badge_input_add"][stage=' + stage + ']').hide('slow');
		jQuery('button[name="go_badge_input_remove"][stage=' + stage + ']').hide('slow');
	}
});

jQuery('.go_badge_input_toggle').click( function () {
	stage = jQuery(this).attr('stage');
	if (jQuery(this).prop('checked')) {
		jQuery('.go_badge_input[stage=' + stage + ']').show('slow', function(){
			jQuery(this).focus();
		});
		jQuery('button[name="go_badge_input_add"][stage=' + stage + ']').show('slow');
		jQuery('button[name="go_badge_input_remove"][stage=' + stage + ']').show('slow');
	} else {
		jQuery('.go_badge_input[stage=' + stage + ']').hide('slow');
		jQuery('button[name="go_badge_input_add"][stage=' + stage + ']').hide('slow');
		jQuery('button[name="go_badge_input_remove"][stage=' + stage + ']').hide('slow');
	}
});

jQuery('button[name="go_badge_input_add"]').click(function (e) {
	e.preventDefault();
	stage = jQuery(this).attr('stage');
	jQuery(this).before("<input type='text' name='go_badge_input_stage_" + stage + "[]' class='go_badge_input' stage='" + stage + "'/>");
	jQuery('input[name="go_badge_input_stage_'+stage+'[]"]').last().focus();
});

jQuery('button[name="go_badge_input_remove"]').click(function (e) {
	e.preventDefault();
	stage = jQuery(this).attr('stage');
	jQuery('.go_badge_input[stage=' + stage + ']').last().remove();
});

////////////////////////////////////

// Stage one settings accordion //

go_toggle_settings_rows(stage_settings_rows[1]);

stage_accordions[1].click(function(){
	jQuery(this).toggleClass('opened');
	go_toggle_settings_rows(stage_settings_rows[1], true, 1);
	toggle_admin_lock(stage_accordions[1], 'encounter');
	toggle_tests(stage_accordions[1], 'encounter');
});

////////////////////////////////////

// Stage two settings accordion //

go_toggle_settings_rows(stage_settings_rows[2]);

stage_accordions[2].click(function(){
	jQuery(this).toggleClass('opened');
	go_toggle_settings_rows(stage_settings_rows[2], true, 2);
	toggle_admin_lock(stage_accordions[2], 'accept');
	toggle_tests(stage_accordions[2], 'accept');
});

////////////////////////////////////

// Stage three settings accordion //

go_toggle_settings_rows(stage_settings_rows[3]);

stage_accordions[3].click(function(){
	jQuery(this).toggleClass('opened');
	go_toggle_settings_rows(stage_settings_rows[3], true, 3);
	toggle_admin_lock(stage_accordions[3], 'completion');
	if (jQuery(this).hasClass('opened')) {
		if (!jQuery('#go_mta_task_mastery').prop('checked')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').show();
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key').hide();
		} else {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').hide();
		}
	} else {
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').hide();
	}
	toggle_tests(stage_accordions[3], 'completion');
});

////////////////////////////////////

// Stage four settings accordion //
go_toggle_settings_rows(stage_settings_rows[4]);

stage_accordions[4].click(function(){
	jQuery(this).toggleClass('opened');
	go_toggle_settings_rows(stage_settings_rows[4], true, 4);
	toggle_admin_lock(stage_accordions[4], 'mastery');
	if (jQuery(this).hasClass('opened')) {
		if (!jQuery('#go_mta_task_repeat').prop('checked')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key').hide();
			if (jQuery(stage_accordions[3]).hasClass('opened')) {
				jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').show();
			} else {
				jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').hide();
			}
		} else {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key').show();
		}
	} else {
		jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key').hide();
	}
	toggle_tests(stage_accordions[4], 'mastery');
});

////////////////////////////////////

// Three stage toglge //
jQuery('#go_mta_three_stage_switch, #go_mta_task_mastery').click(function(){
	if(jQuery(this).prop('checked')){
		jQuery('#go_mta_three_stage_switch, #go_mta_task_mastery').prop('checked', true);
		jQuery('#go_mta_five_stage_switch, #go_mta_task_repeat').prop('checked', false);
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').hide('slow');
		stage_accordion_rows[4].hide('slow');
		if (stage_accordions[4].hasClass('opened')) {
			jQuery(stage_settings_rows[4]).hide();
		}
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide('slow');
		stage_accordion_rows[5].hide('slow');
		if (stage_accordions[5].hasClass('opened')) {
			jQuery(stage_settings_rows[5]).hide();
		}
		if (jQuery(stage_accordions[3]).hasClass('opened')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').hide();
		}
	}else{
		jQuery('#go_mta_three_stage_switch, #go_mta_task_mastery').prop('checked', false);
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').toggle('slow');
		stage_accordion_rows[4].toggle('slow');
		if (stage_accordions[4].hasClass('opened')) {
			jQuery(stage_settings_rows[4]).hide();
		}
		if (jQuery(stage_accordions[3]).hasClass('opened')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').show();
		}
	}
});

var stage_three = <?php echo ($custom['go_mta_three_stage_switch'][0] == 'on')? 'true' : 'false';?>;

if(stage_three){
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').toggle('slow');
	stage_accordion_rows[4].toggle('slow');
	if(stage_accordions[4].hasClass('opened')){
		go_toggle_settings_rows(stage_settings_rows[4]);
	}
}

////////////////////////////////////

// five stage toggle //

go_toggle_settings_rows(stage_settings_rows[5], true);
stage_accordions[5].click(function(){
	jQuery(this).toggleClass('opened');
	go_toggle_settings_rows(stage_settings_rows[5], true, 5);
	toggle_admin_lock(stage_accordions[5], 'repeat');
});

jQuery('#go_mta_five_stage_switch, #go_mta_task_repeat').click(function(){
	if (jQuery(this).prop('checked')) {
		jQuery('#go_mta_five_stage_switch, #go_mta_task_repeat').prop('checked', true);
		jQuery('#go_mta_three_stage_switch, #go_mta_task_mastery').prop('checked', false);
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').toggle('slow');
		stage_accordion_rows[5].toggle('slow');
		if (stage_accordions[5].hasClass('opened')) {
			go_toggle_settings_rows(stage_settings_rows[5]);
		}
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message').show('slow');
		stage_accordion_rows[4].show('slow');
		if (stage_accordions[4].hasClass('opened')) {
			jQuery(stage_settings_rows[4]).show('slow');
		}
		if (jQuery(stage_accordions[3]).hasClass('opened')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').show();
		}
		if (jQuery(stage_accordions[4]).hasClass('opened')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key').show();
		}
	} else {
		jQuery('#go_mta_five_stage_switch, #go_mta_task_repeat').prop('checked', false);
		jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').toggle('slow');
		stage_accordion_rows[5].toggle('slow');
		if (stage_accordions[5].hasClass('opened')) {
			go_toggle_settings_rows(stage_settings_rows[5]);
		}
		if (jQuery(stage_accordions[3]).hasClass('opened')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key').show();
		}
		if (jQuery(stage_accordions[4]).hasClass('opened')) {
			jQuery('tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key').hide();
		}
	}
});

var stage_five = <?php echo ($custom['go_mta_five_stage_switch'][0] == 'on')? 'true' : 'false';?>;

if (stage_five) {
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').show('slow');
	stage_accordion_rows[5].show('slow');
} else {
	jQuery('tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message').hide('slow');
	stage_accordion_rows[5].hide('slow');
}

////////////////////////////////////

//bonus loot toggle//

var go_bonus_loot_check_box = jQuery("#go_bonus_loot_checkbox");
var go_bonus_loot_items = jQuery("#go_bonus_loot_wrap");
go_bonus_loot_items.prop("hidden", true);
go_bonus_loot_check_box.click(function () {
	if (jQuery(this).is(":checked")) {
		go_bonus_loot_items.prop("hidden", false);
	} else {
		go_bonus_loot_items.prop("hidden", true);
	}
});
if (go_bonus_loot_check_box.is(":checked")) {
	go_bonus_loot_items.prop("hidden", false);
} else {
	go_bonus_loot_items.prop("hidden", true);
}

////////////////////////////////////

</script>
<?php
}
add_action( 'admin_footer', 'task_edit_jquery' );

?>
