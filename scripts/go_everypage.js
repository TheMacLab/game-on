
function go_admin_bar_add(){
		jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_admin_bar_add',
		go_admin_bar_points_points:jQuery('#go_admin_bar_points_points').val(),
		go_admin_bar_points_reason:jQuery('#go_admin_bar_points_reason').val(),
		go_admin_bar_currency_points:jQuery('#go_admin_bar_currency_points').val(),
		go_admin_bar_currency_reason:jQuery('#go_admin_bar_currency_reason').val(),
		go_admin_bar_minutes_points:jQuery('#go_admin_bar_minutes_points').val(),
		go_admin_bar_minutes_reason:jQuery('#go_admin_bar_minutes_reason').val()},
		success: function(html){
	    jQuery('#go_admin_bar_points_points').val('');
		jQuery('#go_admin_bar_points_reason').val('');
		jQuery('#go_admin_bar_currency_points').val('');
		jQuery('#go_admin_bar_currency_reason').val('');
		jQuery('#go_admin_bar_minutes_points').val('');
		jQuery('#go_admin_bar_minutes_reason').val('');
		jQuery('#admin_bar_add_return').html(html);
		}
	});
	
	}
	
function go_admin_bar_stats_page_button(id){
		jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_admin_bar_stats',
		uid: id},
		success: function(html){
jQuery('#go_stats_white_overlay').html(html);
jQuery('#go_stats_page_black_bg').show();
jQuery('#go_stats_white_overlay').show();
jQuery('#go_stats_hidden_input').val(id);


  jQuery( "#go_stats_class_a_list li" ).draggable({
      });
    
jQuery( "#go_stats_class_a_choice" ).droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
      accept: "#go_stats_class_a_list li",
      drop: function( event, ui ) {
        jQuery( this ).find( ".placeholder" ).remove();
        jQuery( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
		 ui.draggable.remove();
		go_stats_leaderboard_choice();
      }
    }).sortable({
      items: "li:not(.placeholder)",
      sort: function() {
        // gets added unintentionally by droppable interacting with sortable
        // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
        jQuery( this ).removeClass( "ui-state-default" );
      }
    });

jQuery( "#go_stats_class_a_choice li" ).draggable({
      });

    
jQuery( "#go_stats_class_a_list" ).droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
      accept: "#go_stats_class_a_choice li",
      drop: function( event, ui ) {
        jQuery( this ).find( ".placeholder" ).remove();
        jQuery( "<li></li>" ).text( ui.draggable.text() ).appendTo( this );
		 ui.draggable.remove();
		 		go_stats_leaderboard_choice();

      }
    }).sortable({
      items: "li:not(.placeholder)",
      sort: function() {
        // gets added unintentionally by droppable interacting with sortable
        // using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
        jQuery( this ).removeClass( "ui-state-default" );
      }
    });


		}
	});
	
	
	
	
		}
function go_stats_close(){
	jQuery('#go_stats_white_overlay').hide();
	jQuery('#go_stats_page_black_bg').hide();
	jQuery('#go_stats_lay').hide();

	
	}
	
	
function go_stats_task_list(){
		jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:1, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_encountered_list').html(html);
		}
	});

	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:2, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_accepted_list').html(html);
		}
	});

	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:3, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_completed_list').html(html);
		}
	});

	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_task_list', stage:4, uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_mastered_list').html(html);
		}
	});
	
	}
	
	
function go_stats_third_tab(){
jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_points', uid: jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_points').html(html);
		}
	});	
jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_currency', uid:jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_currency').html(html);
		}
	});
jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_minutes', uid: jQuery('#go_stats_hidden_input').val()},
		success: function(html){
jQuery('#go_stats_minutes').html(html);
		}
	});
	
	}
function go_stats_leaderboard_choice(){
	var values = []
	jQuery('#go_stats_class_a_list :checked').each(function() {
		values.push(jQuery(this).val());
	});
	jQuery.ajax({
		type: "post",url: MyAjax.ajaxurl,data: { 
		action: 'go_stats_leaderboard',
		class_a_choice: values,
		order: jQuery('#go_stats_leaderboard_select').val()},
		success: function(html){
			jQuery('#go_stats_leaderboard_table_body').html(html);
		}
	});
	}
	 function go_mark_seen(date, type){
			jQuery.ajax({
				url: MyAjax.ajaxurl,
				type: "POST",
				data:{
					action: 'go_mark_read',
					date: date,
					type: type
				},
				success: function(data){
					data = JSON.parse(data);
					if(data[1] == 'remove'){
						jQuery('#wp-admin-bar-'+data[0]+' div').remove();
						jQuery('#go_messages_bar').html(data[2]);
						if(data[2] == 0){
							jQuery('#go_messages_bar').css('background', ' -webkit-radial-gradient( 5px -9px, circle, white 8%, green 26px )');
							}
						}else if( data[1] == 'unseen'){
							jQuery('#wp-admin-bar-'+data[0]+' div').css('color','');
							jQuery('#go_messages_bar').html(data[2]);
							if(data[2] == 0){
							jQuery('#go_messages_bar').css('background', ' -webkit-radial-gradient( 5px -9px, circle, white 8%, green 26px )');
							}
							} 
					
				}
			});
				}
function go_add_uploader(){
	jQuery('#go_uploader').append('<div id="go_uploader"><input type="file" name="go_attachment[]"/><br/>');
	}