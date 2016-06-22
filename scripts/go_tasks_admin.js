/*
 * go_tasks_admin.js
 *
 * Where all the functionality for the task edit page goes.
 */

jQuery( '.go_reward_points, .go_reward_currency, .go_reward_bonus_currency' ).on( 'keyup', function () {
	var reward_stage = jQuery( this ).attr( 'stage' );
	var reward_type = jQuery( this ).attr( 'reward' );
	jQuery( 'input[stage=' + reward_stage + '][reward = ' + reward_type + ']' ).val( jQuery( this ).val() );
});

jQuery( '.go_task_settings_accordion' ).click( function () {
	jQuery( this ).children( '.go_triangle_container' ).children( '.go_task_accordion_triangle' ).toggleClass( 'down' );
});

function go_toggle_settings_rows( stage_settings, condensed, number ) {
	condensed = ( 'undefined' !== typeof condensed ? condensed : false );
	for ( var setting in stage_settings ) {
		if ( condensed === true ) {
			stage_settings[ setting ].addClass( 'condensed' ).children().addClass( 'condensed' );
		}
		stage_settings[ setting ].toggle( 'slow' );
	}
	if ( number ) {
		for ( var i = 1; i < 6; i++ ) {
			if ( i === number ) {	
				continue;
			}
			stage_accordions[ i ].removeClass( "opened" );
			if ( stage_settings !== task_settings ) {
				jQuery( "#go_advanced_task_settings_accordion" ).removeClass( "opened" );
				for ( var settings in task_settings ) {
					task_settings[ settings ].hide( 'slow' );
				}
			} else {
				for ( var setting_rows in stage_settings_rows[ i ] ) {
					if ( null !== stage_settings_rows[ i ][ setting_rows ] ) {
						stage_settings_rows[ i ][ setting_rows ].hide( 'slow' );
					}
				}
			}
			if ( jQuery( '#go_calendar_checkbox' ).prop( 'checked' ) &&
					jQuery( "#go_advanced_task_settings_accordion" ).hasClass( 'opened' ) ) {
				calendar_row.show( 'slow' );
				future_row.hide();
			} else {
				calendar_row.hide( 'slow' );
				future_row.hide();
			}
			if ( jQuery( '#go_future_checkbox' ).prop( 'checked' ) &&
					jQuery( "#go_advanced_task_settings_accordion" ).hasClass( 'opened' ) ) {
				future_row.show( 'slow' );
				calendar_row.hide();
			} else {
				future_row.hide( 'slow' );
				calendar_row.hide();
			}
			//! needed ??
			// for ( var setting_rows in stage_settings_rows[ i ] ) {
			// 	if ( null !== stage_settings_rows[ i ][ setting_rows ] ) {
			// 		stage_settings_rows[ i ][ setting_rows ].hide( 'slow' );
			// 	}
			// }
		}
	}
}

var stage_accordion_rows = {
	1: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_one_settings' ),
	2: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_two_settings' ),
	3: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_three_settings' ),
	4: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_four_settings' ),
	5: jQuery( 'tr.cmb-type-go_settings_accordion.cmb_id_stage_five_settings' )
};

var stage_accordions = {
	1: jQuery( '#go_stage_one_settings_accordion' ),
	2: jQuery( '#go_stage_two_settings_accordion' ),
	3: jQuery( '#go_stage_three_settings_accordion' ),
	4: jQuery( '#go_stage_four_settings_accordion' ),
	5: jQuery( '#go_stage_five_settings_accordion' )
};

var stage_settings_rows = {
	1: [
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_points' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_currency' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_one_bonus_currency' ),
		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_encounter_admin_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_encounter_url_key' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_encounter_upload' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_encounter_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_encounter_lock_loot' ),
		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_encounter_lock_loot_mod' ),
		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_encounter' ),
		jQuery( 'tr.cmb_id_go_mta_stage_one_badge' )
	],
	2: [
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_points' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_currency' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_two_bonus_currency' ),
		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_accept_admin_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_accept_url_key' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_accept_upload' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_accept_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_accept_lock_loot' ),
		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_accept_lock_loot_mod' ),
		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_accept' ),
		jQuery( 'tr.cmb_id_go_mta_stage_two_badge' )
	],
	3: [
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_points' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_currency' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_three_bonus_currency' ),
		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_completion_admin_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_upload' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_completion_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_completion_lock_loot' ),
		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_completion_lock_loot_mod' ),
		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_completion' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_task_mastery' ),
		jQuery( 'tr.cmb_id_go_mta_stage_three_badge' )
	],
	4: [
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_points' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_currency' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_four_bonus_currency' ),
		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_mastery_admin_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_upload' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_mastery_lock_loot' ),
		jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_mastery_lock_loot_mod' ),
		jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_mastery' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_task_repeat' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_privacy' ),
		jQuery( 'tr.cmb_id_go_mta_stage_four_badge' ),
		jQuery( 'tr.cmb-type-go_bonus_loot.cmb_id_go_mta_mastery_bonus_loot' )
	],
	5: [
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_points' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_currency' ),
		jQuery( 'tr.cmb-type-go_stage_reward.cmb_id_go_mta_stage_five_bonus_currency' ),
		jQuery( 'tr.cmb-type-go_repeat_amount.cmb_id_go_mta_repeat_amount' ),
		jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_repeat_admin_lock' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_repeat_upload' ),
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_repeat_privacy' ),
		jQuery( 'tr.cmb_id_go_mta_stage_five_badge' )
	]
};

/*
 * Advanced Settings Accordion
 */

var task_settings = [
	jQuery( 'tr.cmb-type-go_rank_list.cmb_id_go_mta_req_rank' ),
	jQuery( 'tr.cmb-type-go_start_filter.cmb_id_go_mta_start_filter' ),
	jQuery( 'tr.cmb-type-go_future_filters.cmb_id_go_mta_time_filters' ),
	jQuery( 'tr.cmb-type-text.cmb_id_go_mta_bonus_currency_filter' ),
	jQuery( 'tr.cmb-type-text.cmb_id_go_mta_penalty_filter' ),
	jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_focus_category_lock' ),
	jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_three_stage_switch' ),
	jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_five_stage_switch' ), 
	jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ),
	jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' )
];

go_toggle_settings_rows( task_settings );

var in_chain = GO_TASK_DATA.task_chains.in_chain;
var is_final_task = GO_TASK_DATA.task_chains.is_last_in_chain;

jQuery( '#go_advanced_task_settings_accordion' ).click( function () {
	jQuery( this ).toggleClass( 'opened' );
	go_toggle_settings_rows( task_settings, true, 6 );
	if ( in_chain && jQuery( this ).hasClass( 'opened' ) ) {
		jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ).show( 'slow' );
		if ( is_final_task ) {
			jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).show();
		} else {
			if ( jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).is( ":visible" ) ) {
				jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).hide();
			}
		}
	} else {
		if ( jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ).is( ":visible" ) ) {
			jQuery( 'tr.cmb-type-go_pick_order_of_chain.cmb_id_go_mta_chain_order' ).hide();	
		}
		if ( jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).is( ":visible" ) ) {
			jQuery( 'tr.cmb-type-text.cmb_id_go_mta_final_chain_message' ).hide();
		}
	}
	if ( jQuery( '#go_start_checkbox' ).prop( 'checked' ) ) {
		jQuery( '#go_start_info' ).show( 'slow' );
	} else {
		jQuery( '#go_start_info' ).hide( 'slow' );
	}
});

/*
 * Modifier Date Picker
 */

jQuery( '#go_start_checkbox' ).click( function () {
	if ( jQuery( '#go_start_checkbox' ).prop( 'checked' ) ) {
		jQuery( '#go_start_info' ).show( 'slow' );
	} else {
		jQuery( '#go_start_info' ).hide( 'slow' );
	}
});

/*
 * Modifier Date Picker
 */

var calendar_row = jQuery( 'tr.cmb-type-go_decay_table.cmb_id_go_mta_date_picker' );
var future_row = jQuery( 'tr.cmb-type-go_time_modifier_inputs.cmb_id_go_mta_time_modifier' );

calendar_row.hide();
future_row.hide();

calendar_row.addClass( 'condensed' ).children().addClass( 'condensed' );
future_row.addClass( 'condensed' ).children().addClass( 'condensed' );

jQuery( '#go_calendar_checkbox' ).click( function () {
	jQuery( '#go_future_checkbox' ).prop( 'checked', false );
	if ( jQuery( '#go_calendar_checkbox' ).prop( 'checked' ) ) {
		calendar_row.show( 'slow' );
		future_row.hide();
	} else {
		calendar_row.hide( 'slow' );
	}
});
jQuery( '#go_future_checkbox' ).click( function () {
	jQuery( '#go_calendar_checkbox' ).prop( 'checked', false );
	if ( jQuery( '#go_future_checkbox' ).prop( 'checked' ) ) {
		future_row.show( 'slow' );
		calendar_row.hide();
	} else {
		future_row.hide( 'slow' );
	}
});

var is_chrome = navigator.userAgent.toLowerCase().indexOf( 'chrome' ) > -1;
jQuery( document ).ready( function () {
	jQuery ( 'input.custom_time' ).each( function () {
		jQuery( this ).keypress( function( e ) {
			var regex = new RegExp( "^[0-9+:+A+P+M]$" );
			var key = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
			if ( ! regex.test( key ) || jQuery( this).val().length > 7 ) {
				e.preventDefault();
			}
			if ( jQuery( this ).val().length > 7 ) {
				jQuery( this ).val( jQuery( this ).val().substr( 0, 8 ) );
			}
		});
	});
	if ( ! is_chrome ) {
		if ( jQuery( 'input.go_datepicker' ).length ) {
			jQuery( 'input.go_datepicker' ).each( function () {
				jQuery( this ).datepicker({ dateFormat: "yy-mm-dd" });
			});
		}
		if ( jQuery( 'input.custom_time' ).length ) {
			jQuery( 'input.custom_time' ).each( function () {
				jQuery( this ).ptTimeSelect(); // Turn input[type='time'] into a custom jquery ui time picker
				var timer = jQuery( this ).val(); // Retrieve time value in 00:00 (hh:mm) format
				var ampm = ( ( parseInt(timer.substring( 0, timer.search( ':' ) ) ) < 12 ) ? 'AM' : 'PM' ); // Check to see if the time is AM/PM for 12 traditional clocks
				var hour = parseInt( timer.substring( 0, timer.search( ':' ) ) ); // Retrieve the first part (hours) of time as a number
				var minutes = timer.substring( timer.search( ':' ) + 1, timer.search( ':' ) + 3); // Retrieve the second part (minutes) of time
				var hour_pretty = ( ( 'PM' === ampm && 12 !== hour ) ? ( ( hour - 12 >= 10 ) ? hour - 12 : '0' + hour - 12 ) : ( ( 'AM' === ampm && hour === 0 ) ? 12 : hour ) ); // Format the hour string to be within 1-12 numerically, rather than 24 hour cycle as is saved to database
				jQuery( this ).val( hour_pretty + ':' + minutes + ' ' + ampm ); // Reconstruct into hh:mm AM/PM human-readable format
			});
		}
	}
});

function go_add_decay_table_row () {
	jQuery( '#go_list_of_decay_dates tbody' ).last().append( '<tr><td><input name="go_mta_task_decay_calendar[]" class="go_datepicker custom_date" type="date" placeholder="Click for Date"/> @ (hh:mm AM/PM)<input type="time" name="go_mta_task_decay_calendar_time[]" class="custom_time" placeholder="Click for Time" value="00:00" /></td><td><input name="go_mta_task_decay_percent[]" type="text" placeholder="Modifier"/></td></tr>' );	
	if ( ! is_chrome ) {
		if ( jQuery( 'input.go_datepicker' ).length) {
			jQuery( 'input.go_datepicker' ).each( function () {
				jQuery( this ).datepicker({dateFormat: "yy-mm-dd"});
			});
			jQuery( 'input.custom_time' ).each( function () {
				jQuery( this ).ptTimeSelect();
			});
		}
	}
}
function go_remove_decay_table_row () {
	jQuery( '#go_list_of_decay_dates tbody tr' ).last( '.go_datepicker' ).remove();
}

/*
 * Admin Lock
 */

function toggle_admin_lock( accordion, stage ) {
	if ( typeof accordion === 'string' ) {
		if ( jQuery(accordion).hasClass( "opened" ) ) {
			if ( jQuery( '#go_mta_' + stage + '_admin_lock_checkbox' ).prop( 'checked' ) ) {
				if ( ! jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
					jQuery( '#go_mta_' + stage + '_admin_lock_input' ).show( 'slow' );
				}
			} else {
				if ( jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
					jQuery( '#go_mta_' + stage + '_admin_lock_input' ).hide( 'slow' );
				}
			}
		} else {
			if ( jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_' + stage + '_admin_lock' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_admin_lock.cmb_id_go_mta_' + stage + '_admin_lock' ).hide();
			}
		}
	} else {
		if ( jQuery( '#go_mta_' + stage + '_admin_lock_checkbox' ).prop( 'checked' ) ) {
			if ( ! jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
				jQuery( '#go_mta_' + stage + '_admin_lock_input' ).show( 'slow' );
			}
		} else {
			if ( jQuery( '#go_mta_' + stage + '_admin_lock_input' ).is( ':visible' ) ) {
				jQuery( '#go_mta_' + stage + '_admin_lock_input' ).hide( 'slow' );
			}
		}
	}
}

jQuery( '.go_admin_lock_checkbox' ).click( function () {
	var stage = this.id.getMid( "go_mta_", "_admin_lock_checkbox" );
	toggle_admin_lock( null, stage );
});

/*
 * Task Tests
 */

function toggle_tests ( accordion, stage ) {
	if (typeof stage === 'string' ) {
		if ( jQuery( accordion ).hasClass( "opened" ) ) {
			if ( jQuery( '#go_mta_test_' + stage + '_lock' ).prop( 'checked' ) ) {
				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).show( 'slow' );
				if ( jQuery( '#go_mta_test_' + stage + '_lock_loot' ).prop( 'checked' ) ) {
					if ( ! jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
						jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).show( 'slow' );
					}
				} else {
					if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
						jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
					}
				}
				jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).show( 'slow' );
			} else {
				if ( jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).is( ':visible' ) ) {
					jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).hide();
				}
				if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
					jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
				}
				if ( jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is( ':visible' ) ) {
					jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide();
				}
			}
		} else {
			if ( jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).hide();
			}
			if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
			}
			if ( jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide();
			}
		}
	}
}

function toggle_test_all ( stage ) {
	if (typeof stage === 'string' ) {
		if ( jQuery( '#go_mta_test_' + stage + '_lock' ).prop( 'checked' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).show( 'slow' );
			if ( jQuery( '#go_mta_test_' + stage + '_lock_loot' ).prop( 'checked' ) ) {
				if ( ! jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
					jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).show( 'slow' );
				}
			} else {
				if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
					jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide();
				}
			}
			jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).show( 'slow' );
		} else {
			if ( jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_test_' + stage + '_lock_loot' ).hide( 'hide' );
			}
			if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide( 'hide' );
			}
			if ( jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_test_field.cmb_id_go_mta_test_lock_'+stage).hide( 'hide' );
			}
		}
	}
}

function toggle_test_loot ( stage ) {
	if (typeof stage === 'string' ) {
		if ( jQuery( '#go_mta_test_' + stage + '_lock_loot' ).prop( 'checked' ) ) {
			if ( ! jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).show( 'slow' );
			}
		} else {
			if ( jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).is( ':visible' ) ) {
				jQuery( 'tr.cmb-type-go_test_modifier.cmb_id_go_mta_test_' + stage + '_lock_loot_mod' ).hide( 'slow' );
			}
		}
	}
}

jQuery( '#go_mta_test_encounter_lock, #go_mta_test_accept_lock, #go_mta_test_completion_lock, #go_mta_test_mastery_lock' ).click( function () {
	var stage = this.id.getMid( "go_mta_test_", "_lock" );
	toggle_test_all( stage );
});

jQuery( '#go_mta_test_encounter_lock_loot, #go_mta_test_accept_lock_loot, #go_mta_test_completion_lock_loot, #go_mta_test_mastery_lock_loot' ).click( function () {
	var stage = this.id.getMid( "go_mta_test_", "_lock_loot" );
	toggle_test_loot( stage );
});

/*
 * Badge Rewarding
 */

jQuery( '.go_badge_input_toggle' ).each( function () {
	var stage = jQuery( this ).attr( 'stage' );
	if ( jQuery( this ).prop( 'checked' ) ) {
		jQuery( '.go_badge_input[stage=' + stage + ']' ).show( 'slow' );
		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).show( 'slow' );
		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).show( 'slow' );
	} else {
		jQuery( '.go_badge_input[stage=' + stage + ']' ).hide( 'slow' );
		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).hide( 'slow' );
		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).hide( 'slow' );
	}
});

jQuery( '.go_badge_input_toggle' ).click( function () {
	var stage = jQuery( this ).attr( 'stage' );
	if ( jQuery( this ).prop( 'checked' ) ) {
		jQuery( '.go_badge_input[stage=' + stage + ']' ).show( 'slow', function () {
			jQuery( this ).focus();
		});
		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).show( 'slow' );
		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).show( 'slow' );
	} else {
		jQuery( '.go_badge_input[stage=' + stage + ']' ).hide( 'slow' );
		jQuery( 'button[name="go_badge_input_add"][stage=' + stage + ']' ).hide( 'slow' );
		jQuery( 'button[name="go_badge_input_remove"][stage=' + stage + ']' ).hide( 'slow' );
	}
});

jQuery( 'button[name="go_badge_input_add"]' ).click( function( e ) {
	e.preventDefault();
	var stage = jQuery( this ).attr( 'stage' );
	jQuery( this ).before( "<input type='text' name='go_badge_input_stage_" + stage + "[]' class='go_badge_input' stage='" + stage + "'/>" );
	jQuery( 'input[name="go_badge_input_stage_' + stage + '[]"]' ).last().focus();
});

jQuery( 'button[name="go_badge_input_remove"]' ).click( function( e ) {
	e.preventDefault();
	var stage = jQuery( this ).attr( 'stage' );
	jQuery( '.go_badge_input[stage=' + stage + ']' ).last().remove();
});

/*
 * Stage One Settings Accordion
 */

go_toggle_settings_rows( stage_settings_rows[1] );

stage_accordions[1].click( function () {
	jQuery( this ).toggleClass( 'opened' );
	go_toggle_settings_rows( stage_settings_rows[1], true, 1 );
	toggle_admin_lock( stage_accordions[1], 'encounter' );
	toggle_tests( stage_accordions[1], 'encounter' );
});

/*
 * Stage Two Settings Accordion
 */

go_toggle_settings_rows(stage_settings_rows[2] );

stage_accordions[2].click( function () {
	jQuery( this ).toggleClass( 'opened' );
	go_toggle_settings_rows( stage_settings_rows[2], true, 2 );
	toggle_admin_lock( stage_accordions[2], 'accept' );
	toggle_tests( stage_accordions[2], 'accept' );
});

/*
 * Stage Three Settings Accordion
 */

go_toggle_settings_rows( stage_settings_rows[3] );

stage_accordions[3].click( function () {
	jQuery( this ).toggleClass( 'opened' );
	go_toggle_settings_rows( stage_settings_rows[3], true, 3 );
	toggle_admin_lock( stage_accordions[3], 'completion' );
	if ( jQuery( this ).hasClass( 'opened' ) ) {
		if ( ! jQuery( '#go_mta_task_mastery' ).prop( 'checked' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
		} else {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
		}
	} else {
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
	}
	toggle_tests( stage_accordions[3], 'completion' );
});

/*
 * Stage Four Settings Accordion
 */

go_toggle_settings_rows( stage_settings_rows[4] );

stage_accordions[4].click( function () {
	jQuery( this ).toggleClass( 'opened' );
	go_toggle_settings_rows( stage_settings_rows[4], true, 4 );
	toggle_admin_lock( stage_accordions[4], 'mastery' );
	if ( jQuery( this ).hasClass( 'opened' ) ) {
		if ( ! jQuery( '#go_mta_task_repeat' ).prop( 'checked' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
			if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
			} else {
				jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
			}
		} else {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).show();
		}
	} else {
		jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
	}
	toggle_tests( stage_accordions[4], 'mastery' );
});

/*
 * Three Stage Toggle
 */

jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).click( function () {
	if ( jQuery( this ).prop( 'checked' ) ) {
		jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).prop( 'checked', true );
		jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).prop( 'checked', false );
		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).hide( 'slow' );
		stage_accordion_rows[4].hide( 'slow' );
		if ( stage_accordions[4].hasClass( 'opened' ) ) {
			jQuery(stage_settings_rows[4] ).hide();
		}
		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).hide( 'slow' );
		stage_accordion_rows[5].hide( 'slow' );
		if ( stage_accordions[5].hasClass( 'opened' ) ) {
			jQuery( stage_settings_rows[5] ).hide();
		}
		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).hide();
		}
	}else{
		jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).prop( 'checked', false );
		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).toggle( 'slow' );
		stage_accordion_rows[4].toggle( 'slow' );
		if ( stage_accordions[4].hasClass( 'opened' ) ) {
			jQuery( stage_settings_rows[4] ).hide();
		}
		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
		}
	}
});

var stage_three = GO_TASK_DATA.stages.is_stage_three_active;

if ( stage_three ) {
	jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).toggle( 'slow' );
	stage_accordion_rows[4].toggle( 'slow' );
	if ( stage_accordions[4].hasClass( 'opened' ) ) {
		go_toggle_settings_rows(stage_settings_rows[4] );
	}
}

/*
 * Five Stage Toggle
 */

go_toggle_settings_rows( stage_settings_rows[5], true );
stage_accordions[5].click( function () {
	jQuery( this ).toggleClass( 'opened' );
	go_toggle_settings_rows( stage_settings_rows[5], true, 5 );
	toggle_admin_lock( stage_accordions[5], 'repeat' );
});

jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).click( function () {
	if ( jQuery( this ).prop( 'checked' ) ) {
		jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).prop( 'checked', true );
		jQuery( '#go_mta_three_stage_switch, #go_mta_task_mastery' ).prop( 'checked', false );
		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).toggle( 'slow' );
		stage_accordion_rows[5].toggle( 'slow' );
		if ( stage_accordions[5].hasClass( 'opened' ) ) {
			go_toggle_settings_rows( stage_settings_rows[5] );
		}
		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_mastery_message' ).show( 'slow' );
		stage_accordion_rows[4].show( 'slow' );
		if ( stage_accordions[4].hasClass( 'opened' ) ) {
			jQuery( stage_settings_rows[4] ).show( 'slow' );
		}
		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
		}
		if ( jQuery( stage_accordions[4] ).hasClass( 'opened' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).show();
		}
	} else {
		jQuery( '#go_mta_five_stage_switch, #go_mta_task_repeat' ).prop( 'checked', false );
		jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).toggle( 'slow' );
		stage_accordion_rows[5].toggle( 'slow' );
		if (stage_accordions[5].hasClass( 'opened' ) ) {
			go_toggle_settings_rows( stage_settings_rows[5] );
		}
		if ( jQuery( stage_accordions[3] ).hasClass( 'opened' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_completion_url_key' ).show();
		}
		if ( jQuery( stage_accordions[4] ).hasClass( 'opened' ) ) {
			jQuery( 'tr.cmb-type-checkbox.cmb_id_go_mta_mastery_url_key' ).hide();
		}
	}
});

var stage_five = GO_TASK_DATA.stages.is_stage_four_active;

if ( stage_five ) {
	jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).show( 'slow' );
	stage_accordion_rows[5].show( 'slow' );
} else {
	jQuery( 'tr.cmb-type-wysiwyg.cmb_id_go_mta_repeat_message' ).hide( 'slow' );
	stage_accordion_rows[5].hide( 'slow' );
}

/*
 * Toggling Bonus Loot
 */

var go_bonus_loot_check_box = jQuery( "#go_bonus_loot_checkbox" );
var go_bonus_loot_items = jQuery( "#go_bonus_loot_wrap" );
go_bonus_loot_items.prop( "hidden", true );
go_bonus_loot_check_box.click( function () {
	if ( jQuery( this ).is( ":checked" ) ) {
		go_bonus_loot_items.prop( "hidden", false );
	} else {
		go_bonus_loot_items.prop( "hidden", true );
	}
});
if ( go_bonus_loot_check_box.is( ":checked" ) ) {
	go_bonus_loot_items.prop( "hidden", false );
} else {
	go_bonus_loot_items.prop( "hidden", true );
}

function go_bonus_loot_rarity_validate ( item_rarities ) {
	var accordion_id = 'go_stage_four_settings_accordion';

	// will contain the ids of any failing elements
	var invalid_el_ids = [];
	var range_min_str = '';
	var range_max_str = '';
	
	jQuery.each( item_rarities, function ( index, input_field ) {
		var id = input_field.id;
		var range_min = input_field.min;
		range_min_str = range_min;
		var range_max = input_field.max;
		range_max_str = range_max;
		var rarity_val = input_field.value;

		// test the rarity value against the desired float pattern
		// (i.e. "XX" or "XX.X+", where "X" is a number 0 through 9 and
		// + indicates "X" once or more times)
		var float_regex = /^([0-9]{1,2}|[0-9]{0,2}\.[0-9]{1,})$/;
		var float_match = float_regex.test( rarity_val );
		if ( false === float_match ) {
			invalid_el_ids.push( id );
			return true;
		}

		// round the number to the hundredths place
		// check for the input being within the field's min and max values
		var rarity_float = parseFloat( rarity_val );
		var rounded_rarity = Math.round10( rarity_float, -2 );
		if ( rounded_rarity < range_min && rounded_rarity > range_max ) {
			invalid_el_ids.push( id );
			return true;
		} else {
			jQuery( '#' + id ).val( rounded_rarity );
		}
	});

	// validation failed, throw an error
	if ( invalid_el_ids.length > 0 ) {
		var error = new Error(
			'In the rarity field, only decimals (to the hundredths place) from ' +
			range_min_str +
			' to ' +
			range_max_str +
			' are allowed.'
		);

		// will jump to the first failing element
		error.name = 'Game On Error';
		error.el_ids = invalid_el_ids;
		error.accord_id = '#' + accordion_id;
		throw error;
	}
}

function go_add_errors ( id_array, error_msg ) {
	jQuery.each( id_array, function ( index, input_id ) {
		jQuery( '#' + input_id ).addClass( 'go_error_red' );
	});

	jQuery( '.go_error' ).show();
	jQuery( '.go_error' ).html( error_msg );
}

function go_remove_errors ( id_array ) {
	jQuery.each( id_array, function ( index, input_id ) {
		jQuery( '#' + input_id ).removeClass( 'go_error_red' );
	});

	jQuery( '.go_error' ).hide();
	jQuery( '.go_error' ).html();
}

function go_before_task_publish ( e, skip_default ) {

	e.preventDefault();

	// the skip_default argument allows input validation to occur
	// before the task is published
	if ( "undefined" === typeof( skip_default ) ) {
		skip_default = true;
	}

	console.log( e, skip_default );

	return;

	// var error_marked_elems = [];

	// if ( true === skip_default ) {
	// 	e.preventDefault();

	// 	window.location.hash = '';
	// 	jQuery( '.go_error_red' ).each( function ( index, element ) {
	// 		error_marked_elems.push( element.id );
	// 	});
	// 	if ( error_marked_elems.length > 0 ) {
	// 		go_remove_errors( error_marked_elems, 'go_bonus_loot_error_msg' );
	// 	}
		
	// 	var task_error = false;
	// 	try {

	// 		// bonus loot rarity field validation
	// 		var go_bonus_loot_on = go_bonus_loot_check_box[0].checked;
	// 		var go_bonus_loot_items_checked = jQuery( go_bonus_loot_items ).children( '.go_bonus_loot_checkbox:checked' );
	// 		var go_bonus_loot_active_item_rarities = jQuery( go_bonus_loot_items_checked ).next( '.go_bonus_loot_rarity' );

	// 		if ( go_bonus_loot_on && go_bonus_loot_items_checked.length > 0 ) {
	// 			go_bonus_loot_rarity_validate( go_bonus_loot_active_item_rarities );
	// 		}

	// 		// update the order of the task's chain, if the task is in a chain
	// 		go_update_task_order_before_publish();
	// 	} catch ( err ) {

	// 		// if an input is causing an issue (e.g. failed validation),
	// 		// stop the task from being updated
	// 		if ( 'Game On Error' === err.name && 'undefined' !== typeof( err.el_ids ) ) {
				
	// 			// open the accordion that the problematic element is under
	// 			if ( ! jQuery( err.accord_id ).hasClass( 'opened' ) ) {
	// 				jQuery( err.accord_id ).trigger( 'click' );
	// 			}

	// 			// direct the user to the problematic element
	// 			window.location.hash = err.el_ids[0];

	// 			go_add_errors( err.el_ids, 'go_bonus_loot_error_msg', err.message );

	// 			task_error = true;
	// 		}
	// 	}

	// 	if ( false === task_error ) {
	// 		jQuery( 'input#publish' ).trigger( 'click', [false] );
	// 	}
	// } else {
	// 	jQuery( '.go_error_red' ).each( function ( index, element ) {
	// 		error_marked_elems.push( element.id );
	// 	});
	// 	if ( error_marked_elems.length > 0 ) {
	// 		go_remove_errors( error_marked_elems, 'go_bonus_loot_error_msg' );
	// 	}
	// }
}
jQuery( 'input#publish' ).on( 'click submit', go_before_task_publish );

/* 
 * This is meant to prevent the page from being submitted via 
 * the enter button, which would bypass the validation functions 
 * that are run when the publish button is triggered.
 */
jQuery( 'form#post' ).keydown( function( e ) {

	// if the enter key is hit, trigger the "submit" event on the publish button
	if ( 13 === e.keyCode ) {
		e.preventDefault();
		jQuery( 'input#publish' ).trigger( 'submit' );
	}
});