

jQuery( document ).ready( function() {
	jQuery.ajaxSetup({ 
		url: go_task_data.url += '/wp-admin/admin-ajax.php'
	});
	check_locks();

	// checks to see that the task has just been encountered, and fires off the Gold
	// ("store") sound, if the task has Gold rewards for the first stage
	var status = go_task_data.status;
	var stage_1_gold = go_task_data.currency;
	if ( 0 === status && stage_1_gold > 0 ) {
		go_sounds( 'store' );
	}
	make_clickable();
	jQuery( ".go_stage_message" ).show( 'slow' ); 
}); 

function go_task_abandon() {
	jQuery.ajax({
		type: "POST",
		data: {
			_ajax_nonce: go_task_data.go_taskabandon_nonce,
			action: "go_task_abandon",
			user_id: go_task_data.userID,
			post_id: go_task_data.ID,
			encounter_points: go_task_data.pointsFloor,
			encounter_currency: go_task_data.currencyFloor,
			encounter_bonus: go_task_data.bonusFloor
		}, success: function( res ) {
			if ( -1 !== res ) {
				window.location = go_task_data.homeURL;
			}
		}
	});
}

function check_locks() {
	if ( jQuery( ".go_test_list" ).length != 0 ) {
		jQuery( '.go_test_submit_div' ).show();
	}
	var is_uploaded = jQuery( '#go_upload_form' ).attr( 'uploaded' );
	if ( jQuery( ".go_test_list" ).length != 0 && jQuery( '#go_upload_form' ).length != 0 ) {
		if ( jQuery( '#go_pass_lock' ).length == 0 && jQuery( '#go_button' ).attr( 'admin_lock' ) !== 'true' ) {
			jQuery( '#go_button' ).attr( 'disabled', 'true' );
		}
		jQuery( '.go_test_submit' ).click( function() {
			var test_list = jQuery( '.go_test_list' );
			var current_error_msg = jQuery( '#go_test_error_msg' ).text();
			if ( test_list.length > 1 ) {
				var checked_ans = 0;
				for (var i = 0; i < test_list.length; i++ ) {
					var obj_str = "#" + test_list[ i ].id + " input:checked";
					var chosen_answers = jQuery( obj_str );
					if ( chosen_answers.length >= 1 ) {
						checked_ans++;
					} else {
						if ( current_error_msg != "Please answer all questions!" ) {
							jQuery( '#go_test_error_msg' ).text( "Please answer all questions!" );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
						go_disable_loading();
					}
				}
				if ( checked_ans >= test_list.length && is_uploaded == 1 ) {
					task_unlock();
				} else {
					if ( checked_ans < test_list.length && is_uploaded != 1 ) {
						var error = "Please answer all questions and upload a file!";
					} else if ( checked_ans < test_list.length ) {
						var error = "Please answer all questions!";
					} else if ( is_uploaded != 1 ) {
						var error = "Please upload a file!";
					}

					if ( typeof error != null ) {
						if ( current_error_msg != error ) {
							jQuery( '#go_test_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
						go_disable_loading();
					}
				}
			} else {
				if ( jQuery( ".go_test_list input:checked" ).length >= 1 && is_uploaded == 1 ) {
					task_unlock();
				} else {
					if ( jQuery( ".go_test_list input:checked" ).length == 0 && is_uploaded != 1 ) {
						var error = "Please answer the question and upload a file!";
					} else if ( jQuery( ".go_test_list input:checked" ).length == 0 ) {
						var error = "Please answer the question!";
					} else if ( is_uploaded != 1 ) {
						var error = "Please upload a file!";
					}

					if ( typeof error != null ) {
						if ( current_error_msg != error ) {
							jQuery( '#go_test_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
						go_disable_loading();
					}
				}
			}
		});
		jQuery( '#go_upload_submit' ).click( function() {
			var test_list = jQuery( ".go_test_list" );
			var current_error_msg = jQuery( '#go_test_error_msg' ).text();
			if ( test_list.length > 1 ) {
				var checked_ans = 0;
				for (var i = 0; i < test_list.length; i++ ) {
					var obj_str = "#" + test_list[ i ].id + " input:checked";
					var chosen_answers = jQuery( obj_str );
					if ( chosen_answers.length >= 1 ) {
						checked_ans++;
					} else {
						if ( current_error_msg != "Please answer all questions!" ) {
							jQuery( '#go_test_error_msg' ).text( "Please answer all questions!" );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
						go_disable_loading();
					}
				}
				if ( checked_ans >= test_list.length && is_uploaded == 1 ) {
					task_unlock();
				} else {
					if ( checked_ans < test_list.length && is_uploaded != 1 ) {
						var error = "Please answer all questions and upload a file!";
					} else if ( checked_ans < test_list.length ) {
						var error = "Please answer all questions!";
					} else if ( is_uploaded != 1 ) {
						var error = "Please upload a file!";
					}

					if ( typeof error != null ) {
						if ( current_error_msg != error ) {
							jQuery( '#go_test_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
						go_disable_loading();
					}
				}
			} else {
				if ( jQuery( ".go_test_list input:checked" ).length >= 1 && is_uploaded == 1 ) {
					task_unlock();
				} else {
					if ( jQuery( ".go_test_list input:checked" ).length == 0 && is_uploaded != 1 ) {
						var error = "Please answer the question and upload a file!";
					} else if ( jQuery( ".go_test_list input:checked" ).length == 0 ) {
						var error = "Please answer the question!";
					} else if (is_uploaded != 1) {
						var error = "Please upload a file!";
					}

					if ( typeof error != null ) {
						if ( current_error_msg != error ) {
							jQuery( '#go_test_error_msg' ).text( error );
						} else {
							flash_error_msg( '#go_test_error_msg' );
						}
						go_disable_loading();
					}
				}
			}
		});
	} else if ( jQuery( ".go_test_list" ).length != 0 ) {
		if ( jQuery( '#go_pass_lock' ).length == 0 && jQuery( '#go_button' ).attr( 'admin_lock' ) !== 'true' ) {
			jQuery( '#go_button' ).attr( 'disabled', 'true' );
		}
		jQuery( '.go_test_submit' ).click( function() {
			var test_list = jQuery( ".go_test_list" );
			if ( test_list.length > 1 ) {
				var checked_ans = 0;
				for ( var i = 0; i < test_list.length; i++ ) {
					var obj_str = "#" + test_list[ i ].id + " input:checked";
					var chosen_answers = jQuery( obj_str );
					if ( chosen_answers.length >= 1 ) {
						checked_ans++;
					}
				}
				if ( checked_ans >= test_list.length ) {
					task_unlock();
				} else {
					if ( jQuery( '#go_test_error_msg' ).text() != "Please answer all questions!" ) {
						jQuery( '#go_test_error_msg' ).text( "Please answer all questions!" );
					} else {
						flash_error_msg( '#go_test_error_msg' );
					}
					go_disable_loading();
				}
			} else {
				if ( jQuery( ".go_test_list input:checked" ).length >= 1 ) {
					task_unlock();
				} else {
					if ( jQuery( '#go_test_error_msg' ).text() != "Please answer the question!" ) {
						jQuery( '#go_test_error_msg' ).text( "Please answer the question!" );
					} else {
						flash_error_msg( '#go_test_error_msg' );
					}
					go_disable_loading();
				}
			}
		});
	} else if ( jQuery( '#go_upload_form' ).length != 0 && is_uploaded == 0 ) {
		if ( jQuery( '#go_pass_lock' ).length == 0 && jQuery( '#go_button' ).attr( 'admin_lock' ) !== 'true' ) {
			jQuery( '#go_button' ).attr( 'disabled', 'true' );
		}
		jQuery( '#go_upload_submit' ).click( function() {
			if ( jQuery( '#go_pass_lock' ).length > 0 && jQuery( '#go_pass_lock' ).attr( 'value' ).length == 0 ) {
				var error = "Retrieve the password from " + go_task_data.admin_name + ".";
				if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
					jQuery( '#go_stage_error_msg' ).text( error );
				} else {
					flash_error_msg( '#go_stage_error_msg' );
				}
				go_disable_loading();
			} else {
				task_unlock();
			}
		});
	}
	if ( ( jQuery( '#go_pass_lock' ).length > 0 && jQuery( '#go_pass_lock' ).attr( 'value' ).length == 0 ) && ( jQuery( '#go_upload_form' ).length != 0 && is_uploaded == 0 ) || jQuery( ".go_test_list" ).length != 0 ) {
		if ( jQuery( '#go_stage_error_msg' ).is( ":visible" ) ) {
			var error = "Retrieve the password from " + go_task_data.admin_name + ".";
			if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
				jQuery( '#go_stage_error_msg' ).text( error );
			} else {
				flash_error_msg( '#go_stage_error_msg' );
			}
			go_disable_loading();
		}
	}
}

function flash_error_msg( elem ) {
	var bg_color = jQuery( elem ).css( 'background-color' );
	if ( typeof bg_color === undefined ) {
		bg_color = "white";
	}
	jQuery( elem ).animate({
		color: bg_color
	}, 200, function() {
		jQuery( elem ).animate({
			color: "red"
		}, 200 );
	});
}

function task_unlock() {
	if ( jQuery( ".go_test_list" ).length != 0) {
		var test_list = jQuery( ".go_test_list" );
		var list_size = test_list.length;
		if ( jQuery( '.go_test_list :checked' ).length >= list_size ) {
			
			var test_list = jQuery( ".go_test_list" );
			var list_size = test_list.length;
			var type_array = [];
			
			if ( jQuery( ".go_test_list" ).length > 1) {
			
				var choice_array = [];

				for ( var x = 0; x < list_size; x++ ) {
					
					// figure out the type of each test
					var test_type = test_list[ x ].children[1].children[0].type;
					type_array.push( test_type );

					// get the checked inputs of each test
					var obj_str = "#" + test_list[ x ].id + " :checked";
					var chosen_answers = jQuery( obj_str );

					if ( test_type == 'radio' ) {
						// push indiviudal answers to the choice_array
						if ( chosen_answers[0] != undefined ) {
							choice_array.push( chosen_answers[0].value );
						}
					} else if ( test_type == 'checkbox' ) {
						var t_array = [];
						for ( var i = 0; i < chosen_answers.length; i++ ) {
							t_array.push( chosen_answers[ i ].value );
						}
						var choice_str = t_array.join( "### " );
						choice_array.push( choice_str );
					}   
				}
				var choice = choice_array.join( "#### " );
				var type = type_array.join( "### " );
			} else {
				var chosen_answer = jQuery( '.go_test_list li input:checked' );
				var type = jQuery( '.go_test_list li input' ).first().attr( "type" );
				if ( type == 'radio' ) {
					var choice = chosen_answer[0].value;
				} else if ( type == 'checkbox' ) {
					var choice = [];
					for (var i = 0; i < chosen_answer.length; i++ ) {
						choice.push( chosen_answer[ i ].value );    
					}
					choice = choice.join( "### " );
				}
			}
		} else {
			jQuery( '#go_test_error_msg' ).text( "Answer all questions!" );
		}
	}

	var is_repeating = jQuery( '#go_button' ).attr( 'repeat' );
	if ( is_repeating !== 'on' ) {
		var status = jQuery( '#go_button' ).attr( 'status' ) - 1;
	} else {
		var status = jQuery( '#go_button' ).attr( 'status' ) ;
	}
	jQuery.ajax({
		type: "POST",
		data:{
			_ajax_nonce: go_task_data.go_unlock_stage,
			action: 'go_unlock_stage',
			task_id: go_task_data.ID,
			user_id: go_task_data.userID,
			list_size: list_size,
			chosen_answer: choice,
			type: type,
			status: status,
			points: go_task_data.points_str,
		},
		success: function( response ) {
			if ( response === 1 || response === '1' ) {
				jQuery( '.go_test_container' ).hide( 'slow' );
				jQuery( '#test_failure_msg' ).hide( 'slow' );
				jQuery( '.go_test_submit_div' ).hide( 'slow' );
				jQuery( '.go_wrong_answer_marker' ).hide();
				if ( ! jQuery( '#go_button' ).attr( 'admin_lock' ) ) {
					jQuery( '#go_button' ).removeAttr( 'disabled' );
					jQuery( '#go_test_error_msg' ).attr( 'style', 'color:green' );
					jQuery( '#go_test_error_msg' ).text( "Well done, continue!" );
				} else {
					jQuery( '#go_test_error_msg' ).text( "This stage can only be unlocked by " + go_task_data.admin_name + "." );
				}
				
				var test_e_returns = go_task_data.test_e;
				var test_a_returns = go_task_data.test_a;
				var test_c_returns = go_task_data.test_c;
				var test_m_returns = go_task_data.test_m;
				if ( ( status == 0 && test_e_returns == 'on' ) ||
						( status == 1 && test_a_returns == 'on' ) ||
						( status == 2 && test_c_returns == 'on' ) || 
						( status == 3 && test_m_returns == 'on' ) ) {
						
					go_test_point_update();
				}
			} else {
				if ( typeof response === 'string' && list_size > 1 ) {
					var failed_questions = response.split( ', ' );
					for ( var x = 0; x < test_list.length; x++ ) {
						var test_id = "#" + test_list[ x ].id;
						if ( jQuery.inArray( test_id, failed_questions ) === -1) {
							if ( jQuery(test_id + " .go_wrong_answer_marker" ).is( ":visible" ) ) {
								jQuery(test_id + " .go_wrong_answer_marker" ).hide();
							}
							if ( ! jQuery(test_id + " .go_correct_answer_marker" ).is( ":visible" ) ) {
								jQuery(test_id + " .go_correct_answer_marker" ).show();
							}
						} else {
							if ( jQuery(test_id + " .go_correct_answer_marker" ).is( ":visible" ) ) {
								jQuery(test_id + " .go_correct_answer_marker" ).hide();
							}
							if ( ! jQuery(test_id + " .go_wrong_answer_marker" ).is( ":visible" ) ) {
								jQuery(test_id + " .go_wrong_answer_marker" ).show();
							}
						}
					}
				}
				var error_msg_val = jQuery( '#go_test_error_msg' ).text();
				if ( error_msg_val != "Wrong answer, try again!" ) {
					jQuery( '#go_test_error_msg' ).text( "Wrong answer, try again!" );
				} else {
					flash_error_msg( '#go_test_error_msg' );
				}
				go_disable_loading();
			}
		}
	});
}

function go_test_point_update() {
	var is_repeating = jQuery( '#go_button' ).attr( 'repeat' );
	if (is_repeating !== 'on' ) {
		var status = jQuery( '#go_button' ).attr( 'status' ) - 2;
	} else {
		var status = jQuery( '#go_button' ).attr( 'status' ) - 1;
	}
	jQuery.ajax({
		type: "POST",
		data: {
			_ajax_nonce: go_task_data.go_test_point_update,
			action: "go_test_point_update",
			points: go_task_data.points_str,
			currency: go_task_data.currency_str,
			bonus_currency: go_task_data.bonus_currency_str,
			status: status,
			page_id: go_task_data.page_id,
			user_id: go_task_data.userID,
			post_id: go_task_data.ID,
			update_percent: go_task_data.date_update_percent
		},
		success: function( response ) {
			if ( -1 !== response ) {

				// the three following lines are required for the go_notification to work
				var color = jQuery( '#go_admin_bar_progress_bar' ).css( "background-color" );
				jQuery( '#go_content' ).append( response );
				jQuery( '#go_admin_bar_progress_bar' ).css({ "background-color": color });
			}
		}
	});
}

function go_repeat_replace() {
	jQuery( '#go_repeat_unclicked' ).remove();
	jQuery( '#go_repeat_clicked' ).show( 'slow' );   
}

// disables the target stage button, and adds a loading gif to it
function go_enable_loading( target ) {

	// prevent further events with this button
	target.disabled = true;

	// prepend the loading gif to the button's content, to show that the request is being
	// processed
	target.innerHTML = '<span class="go_loading"></span>' + target.innerHTML;
}

// re-enables the stage button, and removes the loading gif
function go_disable_loading() {
	jQuery('#go_button .go_loading').remove();
	jQuery('#go_button').prop( 'disabled', '' );
}

function task_stage_change( target ) {

	go_enable_loading( target );

	var undoing = false;
	if ( 'undefined' !== typeof jQuery( target ).attr( 'undo' ) && 'true' === jQuery( target ).attr( 'undo' ).toLowerCase() ) {
		undoing = true;
	}
	
	if ( ! undoing && jQuery( '#go_button' ).length > 0 ) {
		var perma_locked = jQuery( '#go_button' ).attr( 'admin_lock' );
		if ( perma_locked === 'true' ) {
			jQuery( '#go_stage_error_msg' ).show();
			jQuery( '#go_button' ).removeAttr( 'disabled' );
			jQuery( '#go_stage_error_msg' ).text( "This stage can only be unlocked by " + go_task_data.admin_name + "." );
			return;
		}
	}
	
	if ( ! undoing && jQuery( '#go_pass_lock' ).length > 0 ) {
		var pass_entered = jQuery( '#go_pass_lock' ).attr( 'value' ).length > 0 ? true : false;
		if ( ! pass_entered ) {
			jQuery( '#go_stage_error_msg' ).show();
			var error = "Retrieve the password from " + go_task_data.admin_name + ".";
			if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
				jQuery( '#go_stage_error_msg' ).text( error );
			} else {
				flash_error_msg( '#go_stage_error_msg' );
			}
			go_disable_loading();
			return;
		}
	} else if ( ! undoing && jQuery( '#go_url_key' ).length > 0 ) {
		var the_url = jQuery( '#go_url_key' ).attr( 'value' ).replace(/\s+/, '' );
		if ( the_url.length > 0 ) {
			if ( the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && ! ( the_url.lastIndexOf( 'http://' ) > 0 ) && ! ( the_url.lastIndexOf( 'https://' ) > 0 ) ) {
				var url_entered = true;
			} else {
				jQuery( '#go_stage_error_msg' ).show();
				var error = "Enter a valid URL.";
				if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
					jQuery( '#go_stage_error_msg' ).text( error );
				} else {
					flash_error_msg( '#go_stage_error_msg' );
				}
				go_disable_loading();
				return;
			}
		} else {
			jQuery( '#go_stage_error_msg' ).show();
			var error = "Enter a valid URL.";
			if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
				jQuery( '#go_stage_error_msg' ).text( error );
			} else {
				flash_error_msg( '#go_stage_error_msg' );
			}
			go_disable_loading();
			return;
		}
	}
	
	var color = jQuery( '#go_admin_bar_progress_bar' ).css( "background-color" );

	// if the button#go_button exists, set var 'task_status' to the value of the 'status' attribute on the current button#go_button.
	if ( jQuery( '#go_button' ).length != 0 ) {
		var task_status = jQuery( '#go_button' ).attr( 'status' );
	} else {
		var task_status = 5;
	}
	
	// if 'target' (if an argument is sent to task_stage_change, it is stored as a parameter in the 'target' variable)
	// is assigned the value of jQuery( '#go_back_button' ), AND the div#new_content exists...
	if ( jQuery( target ).is( '#go_back_button' ) && jQuery( '#new_content' ).length != 0 ) {
		jQuery( '#new_content p' ).hide( 'slow' );
		jQuery( target ).remove();
	}
	
	// if the button#go_back_button has the attribute of repeat...
	var repeat_attr = false;
	if ( 'on' === jQuery( '#go_button' ).attr( 'repeat' ) ) {
		// set repeat_attr equal to the value of the attribute of button#go_button.
		repeat_attr = true;
	} else if ( 'on' === jQuery( '#go_back_button' ).attr( 'repeat' ) ) {
		// set repeat_attr equal to the value of the attribute of button#go_back_button.
		repeat_attr = true;
	}

	// send the following data to the 'wp_ajax_go_task_change_stage' action and use the POST method to do so...
	// when it succeeds update the content of the page: update the admin bar; set the css display attribute to none for
	// div#new_content; then slowly display div#new_content; if the button#go_button 'status' attribute is equal to 2
	// and remove the first child element of div#new_content.
	jQuery.ajax({
		type: "POST",
		data: {
			_ajax_nonce: go_task_data.go_task_change_stage,
			action: 'go_task_change_stage',
			post_id: go_task_data.ID, 
			user_id: go_task_data.userID,
			admin_name: go_task_data.admin_name,
			task_count: go_task_data.task_count,
			status: task_status,
			repeat: repeat_attr,
			undo: undoing,
			pass: ( pass_entered ? jQuery( '#go_pass_lock' ).attr( 'value' ) : '' ),
			url: ( url_entered ? jQuery( '#go_url_key' ).attr( 'value' ) : '' ),
			page_id: go_task_data.page_id,
			points: go_task_data.points_array,
			currency: go_task_data.currency_array,
			bonus_currency: go_task_data.bonus_currency_array,
			date_update_percent: go_task_data.date_update_percent,
			next_post_id_in_chain: go_task_data.next_post_id_in_chain,
			last_in_chain: go_task_data.last_in_chain,
			number_of_stages: go_task_data.number_of_stages,
		},
		success: function( raw ) {
			

			// parse the raw response to get the desired JSON
			var res = {};
			try {
				var res = JSON.parse( raw );
			} catch (e) {
				res = {
					json_status: 'fail',
					notification: '',
					undo: '',
					html: '',
					rewards: {
						gold: 0,
					},
				};
			}
			console.log (res.html);
			if ( 'fail' === Number.parseInt( res.status ) ) {
			console.log (1);
				jQuery( '#go_stage_error_msg' ).show();
				var error = "Retrieve the password from " + go_task_data.admin_name + ".";
				if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
					jQuery( '#go_stage_error_msg' ).text( error );
				} else {
					flash_error_msg( '#go_stage_error_msg' );
				}
				go_disable_loading();
			} else if ( 302 === Number.parseInt( res.status ) ) {
			console.log (2);
				window.location = res.location;
			} else {
			console.log (3);
				if ( res.undo == true ){
				jQuery( '#go_wrapper div' ).last().hide();
				
				jQuery( '#go_wrapper > div' ).slice(-2).hide( 'slow', function() { jQuery(this).remove();} );
				jQuery( '#go_wrapper' ).append( res.html );
					
					//jQuery( '#go_wrapper > div' ).slice(-2).remove();
					
				} else{
				jQuery( '#go_wrapper > div' ).slice(-1).remove();
					jQuery( '#go_wrapper' ).append( res.html );
					jQuery( ".go_stage_message" ).show( 'slow' ); 
				}
				
				jQuery( '#notification' ).html( res.notification );
				jQuery( '#go_admin_bar_progress_bar' ).css({ "background-color": color });
				//jQuery( "#new_content" ).css( 'display', 'none' );
				//jQuery( "#new_content" ).show( 'slow' ); 
										
				
				//if ( jQuery( '#go_button' ).attr( 'status' ) == 2 ) {
				//	jQuery( '#new_content' ).children().first().remove();
				//}
				
				jQuery( '#go_button' ).ready( function() {
					check_locks();
				});
				
				//Make URL button clickable by clicking enter when field is in focus
				make_clickable('#go_url_key');
				make_clickable('#go_pass_lock');
				
				// fires off the Gold ("store") sound if the stage awarded or revoked Gold
				if ( 0 !== res.rewards.gold ) {
					go_sounds( 'store' );
				}
			}

		}
	});
}

function make_clickable($go_text_box) {
	//Make URL button clickable by clicking enter when field is in focus
				jQuery($go_text_box).keyup(function(ev) {
				// 13 is ENTER
				if (ev.which === 13) {
					// do something
					jQuery("#go_button").click();
				}
				});  
};



