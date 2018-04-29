function go_deactivate_plugin() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_deactivate_plugin;
	jQuery.ajax({
		type: 'post', 
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_deactivate_plugin'
		},
		success: function( res ) {
			if ( -1 !== res ) {
				location.reload();
			}
		}
	});
}

function go_submit_pods () {
	var podInfo = [];
	var links = [];
	jQuery( "input[name='go_pod_link[]']" ).each( function() {
		links.push( jQuery( this ).val() );
		
	});
	podInfo.push( links );
	console.log( links );
	
	var stage = [];
	jQuery( "select[name='go_pod_stage_select[]']" ).each( function() {
		stage.push( jQuery( this ).val() );
	});
	console.log( stage );
	podInfo.push( stage );
	
	var number = [];
	jQuery( "input[name='go_pod_number[]']" ).each( function() {
		number.push( jQuery( this ).val() );
	});
	console.log( number );
	podInfo.push( number );
	
	var next = [];
	jQuery( "select[name='go_next_pod_select[]']" ).each( function() {
		next.push( jQuery( this ).val() );
	});
	console.log( next );
	podInfo.push( next );
	
	console.log( podInfo );
	return podInfo;
	/*jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			action: 'go_submit_pods',
			podLink: jQuery("input[name='go_pod_link[]']").each(),
			podStage: jQuery("input[name='go_pod_stage_select[]']").each(),
			podNumber: jQuery("input[name='go_pod_number[]']").each(),
			podNext: jQuery("input[name='go_next_pod_select[]']").each()
		},
		success: function (html) {
			
		}
	});*/
}

function go_sounds( type ) {
	if ( 'store' == type ) {
		var audio = new Audio( PluginDir.url + 'media/gold.mp3' );
		audio.play();
	} else if ( 'timer' == type ) {
		var audio = new Audio( PluginDir.url + 'media/airhorn.mp3' );
		audio.play();
	}
}

function hideVid() {
	if ( jQuery( '#go_option_help_video' ).length ) {
		myplayer = videojs( 'go_option_help_video' );
	}

	// this will stop the body from scrolling behind the video
	jQuery( 'html' ).removeClass( 'go_no_scroll' );
	jQuery( '.dark' ).hide();
	jQuery( '.light' ).hide();
	if ( jQuery( '#go_option_help_video' ).length ) {
		myplayer.pause();
		myplayer.dispose();
	}
	if ( jQuery( '#go_video_iframe' ).length ) {
		jQuery( '#go_video_iframe' ).remove();
	}
	jQuery( '#go_help_video_container' ).append( '<video id="go_option_help_video" class="video-js vjs-default-skin vjs-big-play-centered" controls height="100%" width="100%" ><source src="" type="video/mp4"/></video>' );
}

function go_display_help_video( url ) {
	jQuery( '.dark' ).show();
	if ( -1 != url.indexOf( 'youtube' ) || -1 != url.indexOf( 'vimeo' ) ) {
		if ( -1 != url.indexOf( 'youtube' ) || url.indexOf( 'youtu.be' ) ) {
			url = url.replace( 'watch?v=', 'v/' );
			if ( -1 == url.indexOf( '&rel=0' ) ) {
				url = url + '&rel=0';	
			}
			jQuery( '#go_help_video_container' ).html( '<iframe id="go_video_iframe" width="100%" height="100%" src="' + url + '" frameborder="0" cc_load_policy="1" allowfullscreen></iframe>' );
		}
		if ( -1 != url.indexOf( 'vimeo' ) ) {
			vimeo_vid_num = url.match( /\d+$/ )[0];
			new_url = 'https://player.vimeo.com/video/' + vimeo_vid_num;
			jQuery( '#go_help_video_container' ).html( '<iframe id="go_video_iframe" src="' + new_url + '" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>' );
		}
	}
	jQuery( '#go_help_video_container' ).show();
	if ( 0 != jQuery( '#go_option_help_video' ).length ) {
		var myplayer = videojs( 'go_option_help_video' );
		myplayer.ready( function() {
			myplayer.src( url );
			myplayer.load();
			myplayer.play();
			videoStatus = 'playing';
		});
	}

	jQuery( '.light' ).show();
	
	// this will stop the body from scrolling behind the video
	jQuery( 'html' ).addClass( 'go_no_scroll' );
	if ( 'none' != jQuery( '.dark' ).css( 'display' ) ) {
		jQuery(document).keydown( function( e ) { 
			if ( jQuery( '#go_help_video_container' ).is(":visible") ) {
				
				// If the key pressed is escape, run this.
				if ( 27 == e.keyCode ) {
					hideVid();
				} 
				if ( 32 == e.keyCode ) {
					e.preventDefault();
					if( ! myplayer.paused() ) {
						myplayer.pause();
					} else {
						myplayer.play();	
					}
				}
			}
		});	
		jQuery( '.dark' ).click( function() {
			hideVid();
		});
	}
}

function go_admin_bar_add() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_admin_bar_add;
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce,
			action: 'go_admin_bar_add',
			go_admin_bar_add_points: jQuery( '#go_admin_bar_add_points' ).val(),
			go_admin_bar_add_points_reason: jQuery( '#go_admin_bar_add_points_reason' ).val(),
			go_admin_bar_add_currency: jQuery( '#go_admin_bar_add_currency' ).val(),
			go_admin_bar_add_currency_reason: jQuery( '#go_admin_bar_add_currency_reason' ).val(),
			go_admin_bar_add_bonus_currency: jQuery( '#go_admin_bar_add_bonus_currency' ).val(),
			go_admin_bar_add_bonus_currency_reason: jQuery( '#go_admin_bar_add_bonus_currency_reason' ).val(),
			go_admin_bar_add_minutes: jQuery( '#go_admin_bar_add_minutes' ).val(),
			go_admin_bar_add_minutes_reason: jQuery( '#go_admin_bar_add_minutes_reason' ).val(),
			go_admin_bar_add_penalty: jQuery( '#go_admin_bar_add_penalty' ).val(),
			go_admin_bar_add_penalty_reason: jQuery( '#go_admin_bar_add_penalty_reason' ).val()
		},
		success: function( res ) {
			jQuery( '#go_admin_bar_add_points' ).val( '' );
			jQuery( '#go_admin_bar_add_points_reason' ).val( '' );
			jQuery( '#go_admin_bar_add_currency' ).val( '' );
			jQuery( '#go_admin_bar_add_currency_reason' ).val( '' );
			jQuery( '#go_admin_bar_add_bonus_currency' ).val( '' );
			jQuery( '#go_admin_bar_add_bonus_currency_reason' ).val( '' );
			jQuery( '#go_admin_bar_add_minutes' ).val( '' );
			jQuery( '#go_admin_bar_add_minutes_reason' ).val( '' );
			jQuery( '#go_admin_bar_add_penalty' ).val( '' );
			jQuery( '#go_admin_bar_add_penalty_reason' ).val( '' );
			if ( -1 !== res ) {
				jQuery( '#admin_bar_add_return' ).html( res );
				jQuery( '#go_admin_bar_add_button' ).prop( 'disabled', false );
			}
		}
	});	
}
	
function go_admin_bar_stats_page_button( id ) {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_admin_bar_stats;
	jQuery.ajax({
		type: "post",
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce,
			action: 'go_admin_bar_stats',
			uid: id
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_white_overlay' ).html( res );
				jQuery( '#go_stats_page_black_bg' ).show();
				jQuery( '#go_stats_white_overlay' ).show();
				jQuery( '#go_stats_hidden_input' ).val( id );

				// this will stop the body from scrolling behind the stats page
				jQuery( 'html' ).addClass( 'go_no_scroll' );
				
				jQuery( '.go_stats_body_selectors' ).click( function() {
					if ( jQuery( '#go_stats_help_video' ).length ) {
						myplayer = videojs( 'go_stats_help_video' );
						myplayer.pause();
						myplayer.dispose();
					}
					body = jQuery( '#go_stats_body' );
					body.empty();
					body.css( 'background-color', '#FFF' );
					jQuery( '.go_stats_body_selectors' ).css( 'font-weight', 'normal' );
					tab = jQuery( this ).attr( 'tab' );
					jQuery( this ).css( 'font-weight', 'bold' );
					switch ( tab ) {
						/*
						case 'progress':
							body.css( 'background-color', '#CCC' );
							body.html( 'goml' );
							break;
						*/
						case 'help':
							go_stats_help();
							break;
						case 'tasks':
							go_stats_task_list();
							break;
						case 'items':
							go_stats_item_list();
							break;
						case 'rewards':
							go_stats_rewards_list();
							break;
						case 'minutes':
							go_stats_minutes_list();
							break;
						case 'penalties':
							go_stats_penalties_list();
							break;
						case 'badges':
							go_stats_badges_list();
							break;
						case 'leaderboard':
							go_stats_leaderboard();
							break;
					}
				});
				
				jQuery( '#go_stats_body_tasks' ).click();
				
				// Check if store lightbox is visible
				if ( 'none' != jQuery( '#go_stats_white_overlay' ).css( 'display' ) ) {
					
					// Monitors for keyboard input
					jQuery(document).keydown( function( e ) {
						if ( 'none' == jQuery( '.white_content' ).css( 'display' ) && 27 == e.keyCode ) { 
							
							// Close out stats panel
							go_stats_close();
						}
					});
					jQuery( '#go_stats_page_black_bg' ).click( function() {
						go_stats_close();
					});
				}
			}
		}
	});
}

function go_stats_close() {
	if ( jQuery( '#go_stats_help_video' ).length ) {
		myplayer = videojs( 'go_stats_help_video' );
		myplayer.pause();
		myplayer.dispose();
	}
	jQuery( 'html' ).removeClass( 'go_no_scroll' );
	jQuery( '#go_stats_white_overlay' ).hide();
	jQuery( '#go_stats_page_black_bg' ).hide();
	jQuery( '#go_stats_lay' ).hide();
}

function go_stats_help() {
	jQuery( '#go_stats_body' ).append( '<div id="go_stats_help_video_container"></div>' );
	jQuery( '#go_stats_help_video_container' ).css({ 'margin': '0px 10% 0px 15%', 'height': '100%', 'width': '100%' });
	jQuery( '#go_option_help_video' ).clone().prop( 'id', 'go_stats_help_video' ).attr( 'width', '70%' ).attr( 'height', '100%' ).appendTo( '#go_stats_help_video_container' );
	if ( jQuery( '#go_stats_help_video' ).length ) {
		myplayer = videojs( 'go_stats_help_video' );
		myplayer.ready( function() {
			myplayer.src( 'http://maclab.guhsd.net/go/video/stats/help.mp4' );
			myplayer.load();
			myplayer.play();
			videoStatus = 'playing';
		});
	}
}
	
function go_stats_task_list() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_task_list;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce,
			action: 'go_stats_task_list',
			user_id: jQuery( '#go_stats_hidden_input' ).val()
		},
		success:function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
				jQuery( '.go_stats_task_status_wrap a.go_stats_task_admin_stage_wrap' ).click( function() {
					if ( '#' == jQuery( this ).attr( 'href' ) ) {
						jQuery( '.chosen' ).not( jQuery( this ).children( 'div' ) ).removeClass( 'chosen' );
						jQuery( this ).children( 'div' ).not( jQuery( '.go_stage_does_not_exist' ) ).toggleClass( 'chosen' );
					}
				});
				a_color = jQuery( 'a' ).css( 'color' );
				jQuery( '.stage_url' ).css( 'background-color', '' + a_color + '' );
				jQuery( '.future_url' ).css( 'border-color', '' + a_color + '' );
				jQuery( '.go_stage_does_not_exist' ).parent().css( 'cursor', 'default' );
				jQuery( '.go_stage_does_not_exist' ).parent().on( 'click', function( e ) {
					e.preventDefault();
				});
				jQuery( '.go_user' ).not( '.go_stats_task_stage_url' ).click( function( e ) {
					e.preventDefault();
				});
				jQuery( '.go_stats_task_admin_submit' ).click( function() {
					task_id = jQuery( this ).attr( 'task' );
					stage = '';
					if ( jQuery( 'div[task="' + task_id + '"].chosen' ).length ) {
						stage = jQuery( 'div[task="' + task_id + '"].chosen' ).attr( 'stage' );
					}
					if ( '' != task_id && '' != stage ) {
						go_stats_move_stage( task_id, stage );
					}
					jQuery( '.chosen' ).toggleClass( 'chosen' );
				});
			}
		}
	});
}

function go_stats_move_stage( task_id, status ) {
	task_message = jQuery( '#go_stats_task_' + task_id + '_message' );
	if ( '' != task_message.val() ) {
		message = task_message.val();
	} else {
		message = task_message.prop( 'placeholder' );
	}
	var count = jQuery( 'div[task="' + task_id + '"][stage="' + status + '"]' ).attr( 'count' );
	if ( 'undefined' == typeof( count ) || '' == count ) {
		count = 0;
	}
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_move_stage;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_stats_move_stage',
			user_id: jQuery( '#go_stats_hidden_input' ).val(),
			task_id: task_id,
			status: status,
			count: count,
			message: message
		},
		success: function( res ) {
			if ( -1 !== res ) {
				task_message.val( '' );
				for ( i = 5; i > 0; i-- ) {
					if ( i <= status ) {
						jQuery( 'div[task="' + task_id + '"][stage="' + i + '"]' ).addClass( 'completed' );
					} else {
						if ( jQuery( 'div[task="' + task_id + '"][stage="' + i + '"]' ).hasClass( 'stage_url' ) ) {
							jQuery( 'div[task="' + task_id + '"][stage="' + i + '"]' ).removeAttr( 'style' );
							jQuery( 'div[task="' + task_id + '"][stage="' + i + '"]' ).parent( 'a' ).attr( 'href', '#' ).removeAttr( 'target' );
						}
						jQuery( 'div[task="' + task_id + '"][stage="' + i + '"]' ).removeClass( 'completed' ).removeClass( 'stage_url' );
					}
				}
				var json = JSON.parse( res.substr( res.search( '{"type"' ), res.length ) );
				jQuery( '#go_stats_user_points_value' ).html( parseFloat( jQuery( '#go_stats_user_points_value' ).html() ) + json['points']);
				
				var current_rank_points = parseInt( json.current_rank_points );
				var next_rank_points = parseInt( json.next_rank_points );
				var max_rank_points = parseInt( json.max_rank_points );
				var prestige_name = json.prestige_name;
				var pts_to_rank_threshold = 0;
				var rank_threshold_diff = 1;
				var pts_to_rank_up_str = '';
				var percentage = 0;

				pts_to_rank_threshold = json.current_points - current_rank_points;
				if ( 0 !== next_rank_points ) {
					rank_threshold_diff = next_rank_points - current_rank_points;
				}

				if ( max_rank_points === current_rank_points ) {
					pts_to_rank_up_str = prestige_name;
				} else {
					pts_to_rank_up_str = pts_to_rank_threshold + ' / ' + rank_threshold_diff;
				}

				percentage = ( pts_to_rank_threshold / rank_threshold_diff ) * 100;
				if ( percentage <= 0 ) { 
					percentage = 0;
				} else if ( percentage >= 100 ) {
					percentage = 100;
				}

				if ( json['rank'] ) {
					jQuery( '#go_stats_user_rank' ).html( json['rank'] );
				}
				jQuery( '#go_stats_progress_text' ).html( pts_to_rank_up_str );
				jQuery( '#go_stats_progress_fill' ).css( 'width', percentage + '%' );

				if ( json['abandon'] ) {
					task_message.parent( 'li' ).remove();
				}
				jQuery( '#go_stats_user_currency_value' ).html( parseFloat( jQuery( '#go_stats_user_currency_value' ).html() ) + json['currency']);
				jQuery( '#go_stats_user_bonus_currency_value' ).html( parseFloat( jQuery( '#go_stats_user_bonus_currency_value' ).html() ) + json['bonus_currency']);

				// refreshes the stats page task list
				go_stats_task_list();
			}
		}
	});
}

function go_stats_item_list() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_item_list;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_stats_item_list',
			user_id: jQuery( '#go_stats_hidden_input' ).val()	
		}, 
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
			}
		}
	});	
}

function go_stats_rewards_list() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_rewards_list;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_stats_rewards_list',
			user_id: jQuery( '#go_stats_hidden_input' ).val()
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
			}
		}
	});
}	

function go_stats_minutes_list() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_minutes_list;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_stats_minutes_list',
			user_id: jQuery( '#go_stats_hidden_input' ).val()
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
			}
		}
	});
}

function go_stats_penalties_list() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_penalties_list;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_stats_penalties_list',
			user_id: jQuery( '#go_stats_hidden_input' ).val()	
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
			}
		}
	});
}

function go_stats_badges_list() {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_stats_badges_list;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data:{
			_ajax_nonce: nonce,
			action: 'go_stats_badges_list',
			user_id: jQuery( '#go_stats_hidden_input' ).val()
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
			}
		}
	});
}

function go_stats_leaderboard() {
	var nonce_leaderboard_choices = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard_choices;
	var nonce_leaderboard = GO_EVERY_PAGE_DATA.nonces.go_stats_leaderboard;
	jQuery.ajax({
		type: 'post',
		url: MyAjax.ajaxurl,
		data: {
			_ajax_nonce: nonce_leaderboard_choices,
			action: 'go_stats_leaderboard_choices',
		},
		success: function( res ) {
			if ( -1 !== res ) {
				jQuery( '#go_stats_body' ).html( res );
				jQuery( '.go_stats_leaderboard_focus_choice, .go_stats_leaderboard_class_choice' ).click( function() {
					var class_values = [];
					var focus_values = [];
					jQuery( '.go_stats_leaderboard_class_choice' ).each( function() {
						if ( jQuery( this ).prop( 'checked' ) ) {
							class_values.push( jQuery( this ).val() );
						}
					});
					jQuery( '.go_stats_leaderboard_focus_choice' ).each( function() {
						if ( jQuery( this ).prop( 'checked' ) ) {
							focus_values.push( jQuery( this ).val() );
						}
					});
					jQuery.ajax({
						type: 'post',
						url: MyAjax.ajaxurl,
						data: {
							_ajax_nonce: nonce_leaderboard,
							action: 'go_stats_leaderboard',
							class_a_choice: class_values,
							focuses: focus_values,
							date: jQuery( '.go_stats_leaderboard_date_choice:checked' ).val()
						},
						success: function( res_leaderboard ) {
							if ( -1 !== res_leaderboard ) {
								jQuery( '#go_stats_leaderboard' ).html( res_leaderboard );
							}
						}
					});
				});
				jQuery( '.go_stats_leaderboard_class_choice' ).first().click();
			}
		}
	});
}
function go_mark_seen( date, type ) {
	var nonce = GO_EVERY_PAGE_DATA.nonces.go_mark_read;
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		type: "POST",
		data:{
			_ajax_nonce: nonce,
			action: 'go_mark_read',
			date: date,
			type: type
		},
		success: function( res ) {
			if ( -1 !== res ) {
				var parsed_data = JSON.parse( res );
				if ( 'remove' == parsed_data[1] ) {
					jQuery( '#wp-admin-bar-' + parsed_data[0] ).remove();
					jQuery( '#go_messages_bar' ).html( parsed_data[2] );
					if ( 0 == parsed_data[2] ) {
						jQuery( '#go_messages_bar' ).css( 'background', '#222222' );
					}
				} else if ( 'unseen' == parsed_data[1] ) {
					jQuery( '#wp-admin-bar-' + parsed_data[0] + ' div' ).css( 'color','white' );
					jQuery( '#go_messages_bar' ).html( parsed_data[2] );
					if ( 0 == parsed_data[2] ) {
						jQuery( '#go_messages_bar' ).css( 'background', '#222222' );
					}
				} else if ( 'seen' == parsed_data[1] ) {
					jQuery( '#wp-admin-bar-' + parsed_data[0] + ' a:first-of-type div' ).css( 'color','red' );
					jQuery( '#go_messages_bar' ).html( parsed_data[2] );
					if ( 1 == parsed_data[2] ) {
						jQuery( '#go_messages_bar' ).css( 'background', 'red' );
					}
				}
				if ( parsed_data[2] > 1 ) {
					jQuery( '#wp-admin-bar-no-new-messages-from-admin .ab-item' ).text("New messages from admin");
				} else if ( 1 == parsed_data[2] ) {
					jQuery( '#wp-admin-bar-no-new-messages-from-admin .ab-item' ).text("New message from admin");
				} else {
					jQuery( '#wp-admin-bar-no-new-messages-from-admin .ab-item' ).text("No new messages from admin");
				}
			}
		}
	});
}
function go_change_seen( date, type, obj ) {
	if ( 'unseen' == type ) {
		jQuery( obj ).text( 'Mark Unread' );
		jQuery( obj ).attr( 'onClick', 'go_mark_seen("' + date + '", "seen"); go_change_seen("' + date + '", "seen", this);' );
	} else if ( 'seen' == type ) {
		jQuery( obj ).text( 'Mark Read' );
		jQuery( obj ).attr( 'onClick', 'go_mark_seen("' + date + '", "unseen"); go_change_seen("' + date + '", "unseen", this);' );
	}
}
function go_add_uploader() {
	jQuery( '#go_upload_form div#go_uploader' ).append( '<input type="file" name="go_attachment[]"/><br/>' );
}
	
//	Grabs substring in the middle of the string object that getMid() is being called from.
//	Takes two strings, one from the left and one from the right.
String.prototype.getMid = function( str_1, str_2 ) {
	if ( 'string' === typeof( str_1 ) && 'string' === typeof( str_2 ) ) {
		var start = str_1.length;
		var substr_length = this.length - ( str_1.length + str_2.length );
		var substr = this.substr( start, substr_length );
		return substr;
	} else {
		if ( 'string' !== typeof( str_1 ) && 'string' !== typeof( str_2 ) ) {
			console.error("String.prototype.getMid expects two strings as args.");
		} else if ( 'string' !== typeof( str_1 ) ) {
			console.error("String.prototype.getMid expects 1st arg to be string.");
		} else if ( 'string' !== typeof( str_2 ) ) {
			console.error("String.prototype.getMid expects 2nd arg to be string.");
		}
	}
}

/**
 * Decimal adjustment of a number.
 *
 * @param string type  The type of adjustment.
 * @param number value The number to adjust.
 * @param int    exp   The exponent (the 10 logarithm of the adjustment base).
 * @returns number The adjusted value.
 */
function decimalAdjust ( type, value, exp ) {
	
	// If the exp is undefined or zero...
	if ( typeof exp === 'undefined' || +exp === 0 ) {
		return Math[ type ]( value );
	}
	value = +value;
	exp = +exp;
	
	// If the value is not a number or the exp is not an integer...
	if ( isNaN( value ) || ! ( typeof exp === 'number' && exp % 1 === 0 ) ) {
		return NaN;
	}

	// Shift
	value = value.toString().split( 'e' );
	value = Math[ type ]( +( value[0] + 'e' + ( value[1] ? ( +value[1] - exp ) : -exp ) ) );
	
	// Shift back
	value = value.toString().split( 'e' );
	return +( value[0] + 'e' + ( value[1] ? ( +value[1] + exp ) : exp ) );
}

// Decimal round
if ( ! Math.round10 ) {
	Math.round10 = function ( value, exp ) {
		return decimalAdjust( 'round', value, exp );
	};
}

// Decimal floor
if ( ! Math.floor10 ) {
	Math.floor10 = function ( value, exp ) {
		return decimalAdjust( 'floor', value, exp );
	};
}

// Decimal ceil
if ( ! Math.ceil10 ) {
	Math.ceil10 = function ( value, exp ) {
		return decimalAdjust( 'ceil', value, exp );
	};
}

/**
 * Retrieves the jQuery object of the nth previous element.
 *
 * @since 3.0.0
 *
 * @see jQuery.prototype.prev()
 *
 * @param int    n        The number of times to call `jQuery.prev()`.
 * @param string selector Optional. The selector to be passed to each query.
 * @return jQuery|null The nth previous sibling, or null if none are found in the nth previous
 *                     position.
 */
jQuery.prototype.go_prev_n = function ( n, selector ) {
	if ( 'undefined' === typeof n ) {
		console.error( 'Game On Error: go_prev_n() requires at least one argument.' );
		return null;
	} else if ( 'int' !== typeof n ) {
		n = Number.parseInt( n );
	}

	var obj = null;
	for ( var x = 0; x < n; x++ ) {
		if ( 0 === x ) {
			if ( 'undefined' !== typeof selector ) {
				obj = jQuery( this ).prev( selector );
			} else {
				obj = jQuery( this ).prev();
			}
		} else if ( null !== obj ) {
			if ( 'undefined' !== typeof selector ) {
				obj = jQuery( obj ).prev( selector );
			} else {
				obj = jQuery( obj ).prev();
			}
		} else {
			break;
		}
	}

	return obj;
};



/////For NEw ACF map stuff

////////////NEW STUFF
jQuery(document).ready(function(){

	
	if(jQuery('#parent').val() == -1){ 
		jQuery('.acf-field').hide();
	}
	else{ 
		jQuery('.acf-field').show();
		jQuery('h2').show();
	}

	jQuery('#parent').change(function(){
	 	if(jQuery(this).val() == -1){ 
			jQuery('.acf-field').hide();
	 	}
	 	else{ 
			jQuery('.acf-field').show();
			jQuery('h2').show();
			
	  	}
	});
});