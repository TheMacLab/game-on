//this needs to be run on both task and blog pages
//it has the code to verify the blog content
//and assign the click to the opener
jQuery( document ).ready( function() {

    //add onclick to blog edit buttons
    //console.log("opener3");
    //jQuery(".go_blog_opener").one("click", function(e){
    //    go_blog_opener( this );
    //});



    jQuery.ajaxSetup({
		url: go_task_data.url += '/wp-admin/admin-ajax.php'
	});


	go_make_clickable();
	jQuery( ".go_stage_message" ).show(  );

    var go_select_admin_view = jQuery('#go_select_admin_view').val();
    console.log(go_select_admin_view);

    if (go_select_admin_view != 'all') {

        //add onclick to continue buttons
        jQuery('#go_button').one("click", function (e) {
            task_stage_check_input(this, true);
        });
        jQuery('#go_back_button').one("click", function (e) {
            task_stage_change(this);
        });
        jQuery('#go_save_button').one("click", function (e) {
            //task_stage_check_input(this, false, false);
            go_blog_submit( this, false );
        });
    }

    //add onclick to bonus loot buttons
    jQuery( "#go_bonus_button" ).one("click", function(e) {
        go_update_bonus_loot(this);
    });

    //add active class to checks and buttons
    jQuery(".progress").closest(".go_checks_and_buttons").addClass('active');

    jQuery('#go_admin_override').appendTo(".go_locks");

    jQuery('#go_admin_override').click( function () {
        jQuery('.go_password').show();
    });

    // remove existing editor instance
    //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
    //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');


    //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
    //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post' );


});

function go_update_bonus_loot(){
    jQuery('#go_bonus_loot').html('<span class="go_loading"></span>');
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_update_bonus_loot;
    var post_id = go_task_data.ID;
    //alert (post_id);
    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_update_bonus_loot',
            post_id: post_id
        },
        success: function( res ) {
            console.log("Bonus Loot");
            console.log(res);
            jQuery("#go_bonus_loot").remove();
            jQuery("#go_wrapper").append(res);

        }
    });
}

//For the Timer (v4)
function getTimeRemaining(endtime) {
	  var t = endtime - Date.parse(new Date());
	  var seconds = Math.floor((t / 1000) % 60);
	  var minutes = Math.floor((t / 1000 / 60) % 60);
	  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
	  var days = Math.floor(t / (1000 * 60 * 60 * 24));
	  return {
	    'total': t,
	    'days': days,
	    'hours': hours,
	    'minutes': minutes,
	    'seconds': seconds
	  };
}

//Initializes the new timer (v4)
function initializeClock(id, endtime) {
    console.log("initializeClock");
    endtime = endtime + Date.parse(new Date());
	var clock = document.getElementById(id);
	var daysSpan = clock.querySelector('.days');
	var hoursSpan = clock.querySelector('.hours');
	var minutesSpan = clock.querySelector('.minutes');
	var secondsSpan = clock.querySelector('.seconds');

	function updateClock() {

	    var t = getTimeRemaining(endtime);
	    t.days = Math.max(0, t.days);
	    daysSpan.innerHTML = t.days;
	    t.hours = Math.max(0, t.hours);
	    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
	    t.minutes = Math.max(0, t.minutes);
	    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
	    t.seconds = Math.max(0, t.seconds);
	    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

	    if (t.total = 0) {
	      clearInterval(timeinterval);
	      var audio = new Audio( PluginDir.url + 'media/sounds/airhorn.mp3' );
			audio.play();

	    }
  	}

  	updateClock();
  	var t = getTimeRemaining(endtime);
  	var time_ms = t.total;
	console.log ("total" + t.total);
  	if (time_ms > 0 ){
  		var timeinterval = setInterval(updateClock, 1000);
  	}else {
  	}
}

function go_timer_abandon() {
	 var redirectURL = go_task_data.redirectURL;
    window.location = redirectURL;
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

function task_stage_change( target ) {

    console.log( "change");

    //v4 Set variables
    var button_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'button_type' ) ) {
        button_type = jQuery( target ).attr( 'button_type' )
    }
    console.log("Button:" + button_type);

    var task_status = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'status' ) ) {
        task_status = jQuery( target ).attr( 'status' )
    }

    var next_bonus = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'next_bonus' ) ) {
        next_bonus = jQuery( target ).attr( 'next_bonus' )
    }

    var check_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'check_type' ) ) {
        check_type = jQuery( target ).attr( 'check_type' )
    }

    var blog_post_id = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'blog_post_id' ) ) {
        check_type = jQuery( target ).attr( 'blog_post_id' )
    }

    var result = jQuery( '#go_result' ).attr( 'value' );

    if( check_type == 'blog' && button_type != 'undo_last_bonus'){
        //result = tinyMCE.activeEditor.getContent();
        result = go_get_tinymce_content_blog('task_stage_change');
        var result_title = jQuery( '#go_blog_title' ).html();
        var blog_post_id= jQuery( '#go_blog_title' ).data( 'blog_post_id' );
        var blog_url= jQuery( '#go_result_url' ).val();
        var blog_media= jQuery( '#go_result_media' ).attr( 'value' );
        var blog_video= jQuery( '#go_result_video' ).val();
        //==console.log("ID" + blog_post_id);
        //console.log("BV" + blog_video);

    }else{
        var result_title = null;
    }
    jQuery.ajax({
        type: "POST",
        data: {
            _ajax_nonce: go_task_data.go_task_change_stage,
            action: 'go_task_change_stage',
            post_id: go_task_data.ID,
            user_id: go_task_data.userID,
            status: task_status,
            next_bonus: next_bonus,
            button_type: button_type,
            check_type: check_type,
            result: result,
            result_title: result_title,
            blog_post_id: blog_post_id,
            blog_url: blog_url,
            blog_media: blog_media,
            blog_video: blog_video,

        },
        success: function( raw ) {
            // parse the raw response to get the desired JSON
            var res = {};
            try {
                var res = JSON.parse( raw );
            } catch (e) {
                res = {
                    json_status: '101',
                    timer_start: '',//doesn;t do anything
                    button_type: '',
                    time_left: '',
                    html: '',
                    redirect: '',
                    rewards: { //this doesn't do anything? check this function
                        gold: 0,
                    },
                };
            }
            //console.log("HTML: " + res.html);
            if ( '101' === Number.parseInt( res.json_status ) ) {
                console.log (101);
                jQuery( '#go_stage_error_msg' ).show();
                var error = "Server Error.";
                if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
                    jQuery( '#go_stage_error_msg' ).text( error );
                } else {
                    flash_error_msg( '#go_stage_error_msg' );
                }
            } else if ( 302 === Number.parseInt( res.json_status ) ) {
                console.log (302);
                window.location = res.location;
            }
            else if ( 'refresh' ==  res.json_status  ) {
                location.reload();
            }else if ( 'bad_password' ==  res.json_status ) {
                jQuery( '#go_stage_error_msg' ).show();
                var error = "Invalid password.";
                if ( jQuery( '#go_stage_error_msg' ).text() != error ) {
                    jQuery( '#go_stage_error_msg' ).text( error );
                } else {
                    flash_error_msg( '#go_stage_error_msg' );
                }
                go_disable_loading();
            }else {

                if ( res.button_type == 'undo' ){
                    jQuery( '#go_wrapper div' ).last().hide();
                    jQuery( '#go_wrapper > div' ).slice(-3).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'undo_last' ){
                    jQuery( '#go_wrapper div' ).last().hide();
                    jQuery( '#go_wrapper > div' ).slice(-2).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'continue' || res.button_type == 'complete' ){
                    jQuery( '#go_wrapper > div' ).slice(-1).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'show_bonus' ){
					jQuery('#go_buttons').remove();
                    //remove active class to checks and buttons
                    jQuery(".go_checks_and_buttons").removeClass('active');
                }
                else if ( res.button_type == 'continue_bonus' || res.button_type == 'complete_bonus' ){
					jQuery( '#go_wrapper > div' ).slice(-1).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'undo_bonus' ){
                    jQuery( '#go_wrapper > div' ).slice(-2).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'undo_last_bonus' ){
                    jQuery( '#go_wrapper > div' ).slice(-1).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'abandon_bonus' ){
                    jQuery( '#go_wrapper > div' ).slice(-3).remove();
                }
                else if ( res.button_type == 'abandon' ){
                    window.location = res.redirect;
                }
                else if ( res.button_type == 'timer' ){
                    jQuery( '#go_wrapper > div' ).slice(-2).hide( 'slow', function() { jQuery(this).remove();} );
                    var audio = new Audio( PluginDir.url + 'media/sounds/airhorn.mp3' );
                    audio.play();
                }
                go_append(res);
                jQuery( document ).ready( function() {
                    jQuery( ".feedback_accordion" ).accordion({
                        collapsible: true
                    });
                });
            }
        }
    });
}

//This isn't currently used, but saving just in case . . .
function go_mce_reset() {
    // remove existing editor instance
    tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
    tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post' );
}

function go_append (res){
    //jQuery( res.html ).addClass('active');
    jQuery( res.html ).appendTo( '#go_wrapper' ).stop().hide().show( 'slow' ).promise().then(function() {
        // Animation complete
        go_Vids_Fit_and_Box("body");
        go_make_clickable();
        go_disable_loading();
        //go_mce();
        // remove existing editor instance, and add new one
        //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
        //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post' );

        //https://stackoverflow.com/questions/25732679/load-wordpress-editor-via-ajax-plugin
        var fullId = 'go_blog_post';
        //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_lightbox');
        tinymce.execCommand('mceRemoveEditor', true, fullId);
        //quicktags({id :'go_blog_post_lightbox'});

        quicktags({id : fullId});
        // use wordpress settings
        tinymce.init({
            selector: fullId,
            branding: false,
            theme:"modern",
            skin:"lightgray",
            language:"en",
            formats:{
                alignleft: [
                    {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'left'}},
                    {selector: 'img,table,dl.wp-caption', classes: 'alignleft'}
                ],
                aligncenter: [
                    {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'center'}},
                    {selector: 'img,table,dl.wp-caption', classes: 'aligncenter'}
                ],
                alignright: [
                    {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'right'}},
                    {selector: 'img,table,dl.wp-caption', classes: 'alignright'}
                ],
                strikethrough: {inline: 'del'}
            },
            relative_urls:false,
            remove_script_host:false,
            convert_urls:false,
            browser_spellcheck:true,
            fix_list_elements:true,
            entities:"38,amp,60,lt,62,gt",
            entity_encoding:"raw",
            keep_styles:false,
            paste_webkit_styles:"font-weight font-style color",
            preview_styles:"font-family font-size font-weight font-style text-decoration text-transform",
            wpeditimage_disable_captions:false,
            wpeditimage_html5_captions:true,
            plugins:"charmap,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview,wordcount",
            selector:"#" + fullId,
            resize:"vertical",
            menubar:false,
            wpautop:true,
            wordpress_adv_hidden:false,
            indent:false,
            toolbar1:"formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv",
            toolbar2:"strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
            toolbar3:"",
            toolbar4:"",
            tabfocus_elements:":prev,:next",
            body_class:"id post-type-post post-status-publish post-format-standard",});
        // this is needed for the editor to initiate
        tinyMCE.execCommand('mceAddEditor', false, fullId);
    });
}

// Makes it so you can press return and enter content in a field
function go_make_clickable() {
	//Make URL button clickable by clicking enter when field is in focus
				jQuery('.clickable').keyup(function(ev) {
				// 13 is ENTER
				if (ev.which === 13) {
					// do something
					jQuery("#go_button").click();
				}
				});
}

//This updates the admin view option and refreshes the page.
function go_update_admin_view(go_admin_view){
    jQuery.ajax({
        type: "POST",
        url : MyAjax.ajaxurl,
        data: {
            _ajax_nonce: GO_EVERY_PAGE_DATA.nonces.go_update_admin_view,
            'action':'go_update_admin_view',
            //user_id: go_task_data.userID,
            'go_admin_view' : go_admin_view,
        },
        success:function(data) {
            location.reload();

        },
        error: function(errorThrown){
            console.log(errorThrown);
            console.log("fail");
        }
    })
}

function go_quiz_check_answers(status, target) {
    //if ( jQuery( ".go_test_list" ).length != 0) {
    var test_list = jQuery( ".go_test_list" );
    var list_size = test_list.length;
    if ( jQuery( '.go_test_list :checked' ).length >= list_size ) {

        //var test_list = jQuery( ".go_test_list" );
        //var list_size = test_list.length;
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
        },
        success: function( response ) {
            if ( response == 'refresh'){
                location.reload();
            }
            //if is all are correct
            else if ( response == true ) {
                jQuery( '.go_test_container' ).hide( 'slow' );
                jQuery( '#test_failure_msg' ).hide( 'slow' );
                jQuery( '.go_test_submit_div' ).hide( 'slow' );
                jQuery( '.go_wrong_answer_marker' ).hide();
                jQuery('#go_stage_error_msg').hide();
                task_stage_change( target );
                return 0;//return a mod of 0
            }
            //if a single question and wrong
            else if ( response == false ){
                jQuery('#go_stage_error_msg').show();
                jQuery( '#go_stage_error_msg' ).text( "Wrong answer, try again!" );
                //go_disable_loading();
                return 1;//return a mod of 1
            }
            else if ( typeof response === 'string' && list_size > 1 ) {
                console.log("response" + response);
                var failed_questions = response.split( ', ' );
                //var failed_count = failed_questions.length;
                //console.log (failed_count);
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

                var error_msg_val = jQuery( '#go_stage_error_msg' ).text();
                if ( error_msg_val != "Wrong answer, try again!" ) {
                    jQuery('#go_stage_error_msg').show();
                    jQuery( '#go_stage_error_msg' ).text( "Wrong answer, try again!" );
                } else {
                    flash_error_msg( '#go_stage_error_msg' );
                }
                //go_disable_loading();
                return;
            }
        }
    });
}



