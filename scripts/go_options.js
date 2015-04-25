jQuery( document ).ready( function () {
	jQuery( '.go_options_no_help' ).click( false );
	
	var option_wraps = [
		jQuery( '#go_options_naming_conventions_wrap' ),
		jQuery( '#go_options_loot_presets_wrap' ),
		jQuery( '#go_options_admin_bar_wrap' ),
		jQuery( '#go_options_levels_wrap' ),
		jQuery( '#go_options_seating_chart_wrap' ),
		jQuery( '#go_options_profession_wrap' ),
		jQuery( '#go_options_additional_settings_wrap' )
	];
	
	for( wrap in option_wraps ) {
		option_wraps[ wrap ].toggle( 'slow' );
	}
	
	var data_reset_display = [
		jQuery( 'input[name="go_data_reset_points"]' ).parent( '.go_options' ),
		jQuery( 'input[name="go_data_reset_currency"]' ).parent( '.go_options' ),
		jQuery( 'input[name="go_data_reset_bonus_currency"]' ).parent( '.go_options' ),
		jQuery( 'input[name="go_data_reset_penalty"]' ).parent( '.go_options' ),
		jQuery( 'input[name="go_data_reset_minutes"]' ).parent( '.go_options' ),
		jQuery( 'input[name="go_data_reset_badges"]' ).parent( '.go_options' ),
		jQuery( 'input[name="go_data_reset_all"]' ).parent( '.go_options' ),
		jQuery( '#go_data_reset' ).parent( '.go_options' )
	];
	
	var data_reset_inputs = [
		jQuery( 'input[name="go_data_reset_points"]' ),
		jQuery( 'input[name="go_data_reset_currency"]' ),
		jQuery( 'input[name="go_data_reset_bonus_currency"]' ),
		jQuery( 'input[name="go_data_reset_penalty"]' ),
		jQuery( 'input[name="go_data_reset_minutes"]' ),
		jQuery( 'input[name="go_data_reset_badges"]' ),
		jQuery( 'input[name="go_data_reset_all"]' )
	];
	
	jQuery( '.go_options_accordion' ).click( function () {
		
		if ( jQuery( '.go_data_reset_disclaimer' ).length === 0 ) {
			jQuery( 'input[name="go_data_reset_switch"]' ).parent( 'div' ).append( "<span class='go_data_reset_disclaimer' style='display: none;'>Select data to reset.</span>" );
		}
		
		jQuery( '.go_options_triangle', this ).toggleClass( 'go_triangle_up' );
		var wrap = jQuery( this ).parent( '.go_options_accordion_wrap' ).attr( 'opt' );
		jQuery.each( option_wraps, function ( index, value ) {
			if ( option_wraps[ index ] != wrap && jQuery( option_wraps[ index ] ).is( ":visible" ) ) {
				option_wraps[ index ].toggle( 'slow' );
			}
		});
		
		if ( jQuery( option_wraps[ wrap ] ).is( ':visible' ) ) {
			jQuery( option_wraps[ wrap ] ).hide( 'slow' );
		} else {
			option_wraps[ wrap ].toggle( 'slow' );
		}


		if ( jQuery( 'input[name="go_focus_switch"]' ).is( ':checked' ) ) {
			jQuery( '#go_options_professions_names_wrap' ).show( 'slow' );
		} else {
			jQuery( '#go_options_professions_names_wrap' ).hide( 'slow' );
		}
		if ( jQuery( 'input[name="go_data_reset_switch"]' ).is( ':checked' ) ) {
			jQuery( '.go_data_reset_disclaimer' ).show( 'slow' );
			for ( display in data_reset_display ) {
				data_reset_display[ display ].show( 'slow' );
			}
		} else {
			jQuery( '.go_data_reset_disclaimer' ).hide( 'slow' );
			for ( display in data_reset_display ) {
				data_reset_display[ display ].hide( 'slow' );
			}
		}
	});
	
	jQuery( 'input[name="go_focus_switch"]' ).click( function () {
		if ( jQuery( 'input[name="go_focus_switch"]' ).is( ':checked' ) ) {
			jQuery( '#go_options_professions_names_wrap' ).show( 'slow' );
		} else {
			jQuery( '#go_options_professions_names_wrap' ).hide( 'slow' );
		}
	});
	
	jQuery( 'input[name="go_data_reset_switch"]' ).click( function () {
		if ( jQuery( 'input[name="go_data_reset_switch"]' ).is( ':checked' ) ) {
			jQuery( '.go_data_reset_disclaimer' ).show( 'slow' );
			for ( display in data_reset_display ) {
				data_reset_display[ display ].show( 'slow' );
			}
		} else {
			jQuery( '.go_data_reset_disclaimer' ).hide( 'slow' );
			for ( display in data_reset_display ) {
				data_reset_display[ display ].hide( 'slow' );
			}
		}
	});
	
	jQuery( '.go_add_period' ).last().after( '<button type="button" class="go_remove_period">-</button>' );
	jQuery( '.go_add_computer' ).last().after( '<button type="button" class="go_remove_computer">-</button>' );
	jQuery( '.go_add_preset' ).last().after( '<button type="button" class="go_remove_preset">-</button>' );
	
	jQuery( 'button' ).click( function ( e ) {
		e.preventDefault();
	});
	
	var go_level_names_value = jQuery( "input[name='go_level_names']" ).val();
	jQuery( "input[name='go_level_names']" ).attr( 'old_value', go_level_names_value );
	var go_period_names_value = jQuery( "input[name='go_class_a_name']" ).val();
	jQuery( "input[name='go_class_a_name']" ).attr( 'old_value', go_period_names_value );
	var go_computer_names_value = jQuery( "input[name='go_class_b_name']" ).val();
	jQuery( "input[name='go_class_b_name']" ).attr( 'old_value', go_computer_names_value );

	jQuery( '.go_options_preset_name_input' ).appendTo( '#go_options_preset_name' );
	jQuery( '.go_options_preset_points_input' ).appendTo( '#go_options_preset_points' );
	jQuery( '.go_options_preset_currency_input' ).appendTo( '#go_options_preset_currency' );
		
	jQuery( '.go_options_level_points_input' ).appendTo( '#go_options_level_points' );
	jQuery( '.go_options_level_badges_input' ).appendTo( '#go_options_level_badges' );
	
	jQuery( '.go_options_profession_input' ).appendTo( '#go_options_professions' );
	if ( jQuery( '.go_options_profession_input' ).length > 1 ) {
		jQuery( '.go_options_profession_input' ).last().after( '<button type="button" class="go_remove_profession">-</button><button type="button" class="go_add_profession">+</button>' );
	} else {
		jQuery( '.go_options_profession_input' ).last().after( '<button type="button" class="go_add_profession">+</button>' );
	}
	
	
	jQuery( document ).on( 'click', '.go_remove_preset', function () {
		if ( jQuery( '.go_options_preset_name_input' ).length > 1 ) {	
			key = jQuery( '.go_options_preset_name_input' ).last().attr( 'key' );
			jQuery( 'input[key="' + key + '"' ).remove();
		} 
		if ( jQuery( '.go_options_preset_name_input' ).length == 1 ) {
			jQuery( '.go_remove_preset' ).remove();
		}
	});
	
	jQuery( '.go_add_preset' ).click( function () {
		var preset_name = jQuery( '.go_options_preset_name_input' ).last().val();
		var regex = /((\S)+(\s)+)+((\d)+)/;
		if ( preset_name.match( regex ) ) {
			var name_array = preset_name.split( ' ' );
			var output_str = '';
			for ( var i = 0; i < name_array.length; i++ ) {
				if ( ( i + 1 ) < name_array.length ) {
					output_str += name_array[ i ]+ " ";
				}
			}
			var name_index = Number( name_array.pop() ) + 1;
			var name = output_str + name_index;
		} else {
			var name = preset_name;
		}

		var preset_key = jQuery( '.go_options_preset_name_input' ).last().attr( 'key' );
		var points_array = [];
		jQuery( '.go_options_preset_points_input[key="' + preset_key + '"' ).each( function ( index ) {
			points_array[ index ] = this.value;
		});
		var currency_array = [];
		jQuery( '.go_options_preset_currency_input[key="' + preset_key + '"' ).each( function ( index ) {
			currency_array[ index ] = this.value;
		});

		var presets = jQuery( '.go_options_preset_name_input' ).length;
		jQuery( '.go_remove_preset' ).remove();
		jQuery( '#go_options_preset_name' ).append( "<input type='text' class='go_options_preset_name_input go_options_preset_input' name='go_presets[name][" + presets + "]' key='" + presets + "'value='" +name+ "'/>" );
		for ( i = 0; i < 5; i++ ) {
			jQuery( '#go_options_preset_points' ).append( "<input type='text' class='go_options_preset_points_input go_options_preset_input' name='go_presets[points][" + presets + "][]' key='" + presets + "' value='" +points_array[ i ]+ "'/>" );
			jQuery( '#go_options_preset_currency' ).append( "<input type='text' class='go_options_preset_currency_input go_options_preset_input' name='go_presets[currency][" + presets + "][]' key='" + presets + "' value='" +currency_array[ i ]+ "'/>" );
		}
		jQuery( '.go_add_preset' ).last().after( '<button type="button" class="go_remove_preset">-</button>' );
	});
	
	jQuery( '#go_reset_presets' ).click( function () {
		jQuery.ajax({
			type: 'post', 
			url: MyAjax.ajaxurl,
			data: {
				action: 'go_presets_reset'
			},
			success: function ( html ) {
				presets = JSON.parse( html );
				jQuery( '#go_options_preset_name' ).empty();
				jQuery( '#go_options_preset_points' ).empty();
				jQuery( '#go_options_preset_currency' ).empty();
				for ( name in presets['name'] ) {
					jQuery( '#go_options_preset_name' ).append( "<input type='text' class='go_options_preset_name_input go_options_preset_input' name='go_presets[name][" + name + "]' key='" + name + "' value='" + presets['name'][ name ] + "'/>" );
				}
				for ( points in presets['points'] ) {
					for(point in presets['points'] ) {
						jQuery( '#go_options_preset_points' ).append( "<input type='text' class='go_options_preset_points_input go_options_preset_input' name='go_presets[points][" + points + "][]' key='" + points + "' value='" + presets['points'][ points ][ point ] + "'/>" )
					}
				}
				for ( currency in presets['currency'] ) {
					for(cur in presets['currency'] ) {
						jQuery( '#go_options_preset_currency' ).append( "<input type='text' class='go_options_preset_currency_input go_options_preset_input' name='go_presets[currency][" + currency + "][]' key='" + currency + "' value='" + presets['currency'][ currency ][ cur ] + "'/>" )
					}
				}

			}
		});
	});
	
	jQuery( '#go_save_presets' ).click( function () {
		var go_preset_name = [];
		var go_preset_points = [];
		var go_preset_currency = [];
		var presets = jQuery( '.go_options_preset_name_input' ).length;
		for( i = 0; i < presets; i++ ) {
			go_preset_points[ i ] = [];
			go_preset_currency[ i ] = [];
		}
		jQuery( '.go_options_preset_name_input' ).each( function () {
			go_preset_name.push( jQuery( this ).val() );
		});
		jQuery( '.go_options_preset_points_input' ).each( function () {
			go_preset_points[ jQuery( this ).attr( 'key' ) ].push( jQuery( this ).val() );
		});
		jQuery( '.go_options_preset_currency_input' ).each( function () {
			go_preset_currency[ jQuery( this ).attr( 'key' ) ].push( jQuery( this ).val() );
		});
		jQuery.ajax({
			type: 'post',
			url: MyAjax.ajaxurl,
			data: {
				action: 'go_presets_save',
				go_preset_name: go_preset_name,
				go_preset_points: go_preset_points,
				go_preset_currency: go_preset_currency
			}
		});
	});
	
	jQuery( document ).on( 'click', '.go_remove_level', function () {
		if ( jQuery( '.go_options_level_names_input' ).length > 1 ) {
			jQuery( '.go_options_level_names_input' ).last().remove();
			jQuery( '.go_options_level_points_input' ).last().remove();
			jQuery( '.go_options_level_badges_input' ).last().remove();
		}
		if ( jQuery( '.go_options_level_names_input' ).length == 1 ) {
			jQuery( '.go_remove_level' ).remove();
		}
	});
	
	if ( jQuery( 'input[name="go_admin_bar_add_switch"]' ).is( ':checked' ) ) {
		jQuery( 'input[name="go_admin_bar_add_minutes_switch"]' ).parent().show( 'slow' )
		jQuery( 'input[name="go_admin_bar_add_points_switch"]' ).parent().show( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_currency_switch"]' ).parent().show( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_bonus_currency_switch"]' ).parent().show( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_penalty_switch"]' ).parent().show( 'slow' );
	} else {
		jQuery( 'input[name="go_admin_bar_add_minutes_switch"]' ).parent().hide( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_points_switch"]' ).parent().hide( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_currency_switch"]' ).parent().hide( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_bonus_currency_switch"]' ).parent().hide( 'slow' );
		jQuery( 'input[name="go_admin_bar_add_penalty_switch"]' ).parent().hide( 'slow' );
	}
	
	jQuery( 'input[name="go_admin_bar_add_switch"]' ).click( function () {
		if ( jQuery( 'input[name="go_admin_bar_add_switch"]' ).is( ':checked' ) ) {
			jQuery( 'input[name="go_admin_bar_add_minutes_switch"]' ).parent().show( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_points_switch"]' ).parent().show( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_currency_switch"]' ).parent().show( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_bonus_currency_switch"]' ).parent().show( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_penalty_switch"]' ).parent().show( 'slow' );
		} else {
			jQuery( 'input[name="go_admin_bar_add_minutes_switch"]' ).parent().hide( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_points_switch"]' ).parent().hide( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_currency_switch"]' ).parent().hide( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_bonus_currency_switch"]' ).parent().hide( 'slow' );
			jQuery( 'input[name="go_admin_bar_add_penalty_switch"]' ).parent().hide( 'slow' );	
		}
	});
	
	jQuery( '.go_add_level' ).last().after( '<button type="button" class="go_remove_level">-</button>' );
	
	jQuery( '.go_add_level' ).click( function () {
		jQuery( '.go_remove_level' ).remove();
		var r_name, points = '';
		var r_end_name = jQuery( '.go_options_level_names_input' ).last().val();
		var name_array = r_end_name.split( ' ' );
		var name_length = name_array.length;
		if ( name_length > 2 ) {
			var output_str = '';
			for ( var i = 0; i < name_length; i++ ) {
				var str = name_array[ i ];
				if ( ( i + 1 ) < name_length && str.length > 0 ) {
					output_str += str + " ";
				}
			}
			var name_index = Number( name_array.pop() ) + 1;
			var name = output_str + ( name_index < 10 ? "0" + name_index : name_index );
		} else {
			var name_index = Number( name_array[1] ) + 1;
			var name = name_array[ 0 ] + " " + ( name_index < 10 ? "0" + name_index : name_index );
		}
		
		var r_num = jQuery( '.go_options_level_points_input' ).length + 1;
		var new_points = ( 15 / 2 ) * ( r_num + 18 ) * ( r_num - 1 );
		points = new_points;

		levels = jQuery( '.go_options_level_names_input' ).length;
		jQuery( '#go_options_level_names' ).append( "<input type='text' class='go_options_level_names_input' name='go_ranks[name][" + levels + "]' value='" + name + "'/>" );
		jQuery( '#go_options_level_points' ).append( "<input type='text' class='go_options_level_points_input' name='go_ranks[points][" + levels + "]' value='" + points + "'/>" );
		jQuery( '#go_options_level_badges' ).append( "<input type='text' class='go_options_level_badges_input' name='go_ranks[badges][" + levels + "]' value=''/>" );
		jQuery( '.go_add_level' ).last().after( '<button type="button" class="go_remove_level">-</button>' );
	});
	
	jQuery( '#go_reset_levels' ).click( function () {
		jQuery.ajax({
			type: 'post',
			url: MyAjax.ajaxurl,
			data: {
				action: 'go_reset_levels'
			}, 
			success: function ( html ) {
				var levels = JSON.parse( html );
				jQuery( '#go_options_level_names' ).empty();
				jQuery( '#go_options_level_points' ).empty();
				jQuery( '#go_options_level_badges' ).empty();
				
				for( name in levels['name'] ) {
					jQuery( '#go_options_level_names' ).append( "<input type='text' class='go_options_level_names_input' name='go_ranks[name][" + name + "]' value='" + levels['name'][ name ] + "'/>" );
				}
				for( point in levels['points'] ) {
					jQuery( '#go_options_level_points' ).append( "<input type='text' class='go_options_level_points_input' name='go_ranks[points][" + point + "]' value='" + levels['points'][ point ] + "'/>" );
				}
				for( badge in levels['badges'] ) {
					jQuery( '#go_options_level_badges' ).append( "<input type='text' class='go_options_level_badges_input' name='go_ranks[badges][" + badge + "]' value='" + levels['badges'][ badge ] + "'/>" );
				}

			}
		});
	});
	
	jQuery( '#go_save_levels' ).click( function () {
		var go_level_names = [];
		var go_level_points = [];
		var go_level_badges = [];
		var levels = jQuery( '.go_options_level_names_input' ).length;
		jQuery( '.go_options_level_names_input' ).each( function () {
			go_level_names.push( jQuery( this ).val() );
		});
		jQuery( '.go_options_level_points_input' ).each( function () {
			go_level_points.push( jQuery( this ).val() );
		});
		jQuery( '.go_options_level_badges_input' ).each( function () {
			go_level_badges.push( jQuery( this ).val() );
		});
		jQuery.ajax({
			type: 'post',
			url: MyAjax.ajaxurl,
			data: {
				action: 'go_save_levels',
				go_level_names: go_level_names,
				go_level_points: go_level_points,
				go_level_badges: go_level_badges
			}
		});
	});
	
	jQuery( '#go_fix_levels' ).click( function () {
		jQuery.ajax({
			type: 'post',
			url: MyAjax.ajaxurl,
			data: {
				action: 'go_fix_levels',
			},
			success: function () {
				location.reload();
			}
		});
	});
	
	jQuery( document ).on( 'click', '.go_remove_period', function () {
		if ( jQuery( '.go_options_period_input' ).length > 1 ) {
			jQuery( '.go_options_period_input' ).last().remove();
		}
		if ( jQuery( '.go_options_period_input' ).length == 1 ) {
			jQuery( '.go_remove_period' ).remove();
		}
	});
	
	jQuery( '.go_add_period' ).click( function () {
		jQuery( '.go_remove_period' ).remove();
		var last_period_name = jQuery( '.go_options_period_input' ).last().val();
		var name_array = last_period_name.split( ' ' );
		var name_length = name_array.length;
		if ( name_length > 2 ) {
			var output_str = '';
			for ( var i = 0; i < name_length; i++ ) {
				var str = name_array[ i ];
				if ( ( i + 1 ) < name_length && str.length > 0) {
					output_str += str+ " ";
				}
			}
			var name_index = Number( name_array[ name_length - 1 ] ) + 1;
			var name = output_str + name_index;
		} else {
			var name_index = Number( name_array[1] ) + 1;
			var name = name_array[0] + " " + name_index;
		}
		jQuery( '#go_options_periods' ).append( "<input type='text' class='go_options_period_input' name='go_class_a[]' value='" + name + "'/>" );
		jQuery( '.go_add_period' ).last().after( '<button type="button" class="go_remove_period">-</button>' );
		
	});
	
	jQuery( document ).on( 'click', '.go_remove_computer', function () {
		if ( jQuery( '.go_options_computer_input' ).length > 1 ) {
			jQuery( '.go_options_computer_input' ).last().remove();
		}
		if ( jQuery( '.go_options_computer_input' ).length == 1 ) {
			jQuery( '.go_remove_computer' ).remove();
		}
	});
	
	jQuery( '.go_add_computer' ).click( function () {
		jQuery( '.go_remove_computer' ).remove();
		var last_period_name = jQuery( '.go_options_computer_input' ).last().val();
		var name_array = last_period_name.split( ' ' );
		var name_length = name_array.length;
		if ( name_length > 2 ) {
			var output_str = '';
			for ( var i = 0; i < name_length; i++ ) {
				var str = name_array[ i ];
				if ( ( i + 1 ) < name_length && str.length > 0 ) {
					output_str += str + " ";
				}
			}
			var name_index = Number( name_array.pop() ) + 1;
			var name = output_str + ( name_index < 10 ? "0" + name_index : name_index );
		} else {
			var name_index = Number( name_array[1] ) + 1;
			var name = name_array[0] + " " + ( name_index < 10 ? "0" + name_index : name_index );
		}
		jQuery( '#go_options_computers' ).append( "<input type='text' class='go_options_computer_input' name='go_class_b[]' value='" + name + "'/>" );
		jQuery( '.go_add_computer' ).last().after( '<button type="button" class="go_remove_computer">-</button>' );
	});
	
	jQuery( document ).on( 'click', '.go_remove_profession', function () {
		var professions_count = jQuery( '.go_options_profession_input' ).length;
		if ( professions_count > 1 ) {
			jQuery( '.go_options_profession_input' ).last().remove();
		}
		if ( professions_count == 2 ) {
			jQuery( this ).remove();
		}
	});
	
	jQuery( document ).on( 'click', '.go_add_profession', function () {
		jQuery( '.go_remove_profession' ).remove();
		jQuery( this ).remove();
		jQuery( '#go_options_professions' ).append( "<input type='text' class='go_options_profession_input' name='go_focus[]' value=''/>" );
		jQuery( '.go_options_profession_input' ).last().after( '<button type="button" class="go_add_profession">+</button>' );
		jQuery( '.go_add_profession' ).last().after( '<button type="button" class="go_remove_profession">-</button>' );
	});
	
	jQuery( 'input[name="go_data_reset_all"]' ).click( function () {
		if ( jQuery( 'input[name="go_data_reset_all"]' ).is( ':checked' ) ) {
			for ( input in data_reset_inputs ) {
				data_reset_inputs[ input ].prop( 'checked', true );
			}
		} else {
			for ( input in data_reset_inputs ) {
				data_reset_inputs[ input ].prop( 'checked', false );
			}
		}
	});
	
	jQuery( '#go_data_reset' ).click( function () {
		if ( jQuery( 'input[name="go_data_reset_switch"]' ).is( ':checked' ) ) {
			var reset_data = [];
			for ( input in data_reset_inputs ) {
				if ( data_reset_inputs[ input ].is( ':checked' ) ) {
					reset_data.push( data_reset_inputs[ input ].attr( 'reset' ) );
				}
			}
			if ( reset_data.length >= 1 ) {
				var reset_all = jQuery( "input[name='go_data_reset_all']" ).is( ':checked' );
				if ( confirm( "WARNING: What you are about to do will reset the chosen types of data from EVERY user on your database. Do you wish to continue?" ) ) {
					jQuery.ajax({
						type: 'post',
						url: MyAjax.ajaxurl,
						data: {
							action: 'go_reset_data',
							reset_data: reset_data,
							reset_all: reset_all
						},
						success: function ( html ) {
							location.reload();
						}
					});
				}
			} else {
				alert( "ATTENTION: Please select data to reset, and try again!" );
			}
		}
	});
	
	jQuery( '#go_options_form' ).submit( function ( event ) {
		if ( event.levels_saved !== true ) {
			event.preventDefault();
			var old_class_a = [];
			var	old_class_b = [];
			var	new_class_a = [];
			var	new_class_b = [];
			jQuery( '.go_options_period_input' ).each( function ( i, el ) {
				old_class_a.push( jQuery( el ).val() );
			});
			jQuery( '.go_options_computer_input' ).each( function ( i, el ) { 
				old_class_b.push( jQuery( el ).val() );
			});
			var new_value_level = jQuery( "input[name='go_level_names']" ).val().trim();
			var old_value_level = jQuery( "input[name='go_level_names']" ).attr( 'old_value' ).trim();
			var new_value_period = jQuery( "input[name='go_class_a_name']" ).val().trim();
			var old_value_period = jQuery( "input[name='go_class_a_name']" ).attr( 'old_value' ).trim();
			var new_value_computer = jQuery( "input[name='go_class_b_name']" ).val().trim();
			var old_value_computer = jQuery( "input[name='go_class_b_name']" ).attr( 'old_value' ).trim();
			if ( new_value_level.length > 0 && old_value_level.length > 0 && new_value_level != old_value_level ) {
				jQuery( ".go_options_level_names_input" ).each( function ( x ) {
					x++;
					var name = new_value_level + " " + ( x < 10 ? "0" + x : x );
					this.value = name;
				});
			}
			if ( new_value_period.length > 0 && old_value_period.length > 0 && new_value_period != old_value_period ) {
				jQuery( ".go_options_period_input" ).each( function ( x ) {
					x++;
					var name = new_value_period + " " + x;
					this.value = name;
				});
			}
			if ( new_value_computer.length > 0 && old_value_computer.length > 0 && new_value_computer != old_value_computer ) {
				jQuery( ".go_options_computer_input" ).each( function ( x ) {
					x++;
					var name = new_value_computer + " " + ( x < 10 ? "0" + x : x );
					this.value = name;
				});
			}
			
			jQuery( '.go_options_period_input' ).each( function ( i, el ) {
				new_class_a.push( jQuery( el ).val() );
			});
			jQuery( '.go_options_computer_input' ).each( function ( i, el ) { 
				new_class_b.push( jQuery( el ).val() );
			});
			
			jQuery.ajax({
				type: 'post',
				url: MyAjax.ajaxurl,
				data: {
					action: 'go_update_user_sc_data',
					old_class_a: old_class_a,
					old_class_b: old_class_b,
					new_class_a: new_class_a,
					new_class_b: new_class_b
				}
			});
			
			if ( jQuery( 'input[name="go_focus_switch"]' ).is( ':checked' ) ) {
				jQuery( '.go_options_profession_input' ).filter( function () {
					var re = new RegExp( "(\\\S)+" );
					if ( this.value.length > 0 && re.test( this.value ) ) {
						return true;
					} else {
						jQuery( this ).remove();
						return false;
					}
				});
				var values = jQuery( '.go_options_profession_input' ).map( function () {
					return jQuery( this ).val();
				}).get();
				jQuery.ajax({
					type: "post",
					url: MyAjax.ajaxurl,
					data: { 
						action: 'go_focus_save',
						focus_array: values
					}, 
					success: function () {
						jQuery( '#go_options_form' ).trigger({ type: 'submit', levels_saved: true });
					}
				});
			} else {
				jQuery( '#go_options_form' ).trigger({ type: 'submit', levels_saved: true });
			}
		}
	});
});