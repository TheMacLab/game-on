
jQuery( document ).ready( function() {
    console.log("admin");

	jQuery.ajaxSetup({
		url: go_task_data.url += '/wp-admin/admin-ajax.php'
	});
	//check_locks();

	// checks to see that the task has just been encountered, and fires off the Gold
	// ("store") sound, if the task has Gold rewards for the first stage
	var status = go_task_data.status;
	var stage_1_gold = go_task_data.currency;
	if ( 0 === status && stage_1_gold > 0 ) {
		go_sounds( 'store' );
	}
	go_make_clickable()
	jQuery( ".go_stage_message" ).show( 'slow' );
    jQuery( ".go_checks_and_buttons" ).show( 'slow' );

    //add onclick to continue buttons
    jQuery('#go_button').one("click", function(e){
        task_stage_check_input( this );
    });
    jQuery('#go_back_button').one("click", function(e){
        task_stage_check_input( this );
    });


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
            jQuery("#page-container").append(res);

        }
    });
}

//For the Timer (v4)
function getTimeRemaining(endtime) {
	  var t = Date.parse(endtime) - Date.parse(new Date());
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
	      var audio = new Audio( PluginDir.url + 'media/airhorn.mp3' );
			audio.play();

	    }
  	}

  	updateClock();
  	var t = getTimeRemaining(endtime);
  	var time_ms = t.total;
	console.log (t.total);
  	if (time_ms > 0 ){
  		var timeinterval = setInterval(updateClock, 1000);
  	}else {

  	}

}

function go_timer_abandon() {
	 var redirectURL = go_task_data.redirectURL
 	//window.location = $homeURL;
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

// disables the target stage button, and adds a loading gif to it
function go_enable_loading( target ) {
	//prevent further events with this button
	//jQuery('#go_button').prop('disabled',true);
	// prepend the loading gif to the button's content, to show that the request is being processed
	target.innerHTML = '<span class="go_loading"></span>' + target.innerHTML;
}

// re-enables the stage button, and removes the loading gif
function go_disable_loading( ) {
    console.log ("oneclick");
    jQuery('.go_loading').remove();

    jQuery('#go_button').off().one("click", function(e){
        task_stage_check_input( this );
    });
    jQuery('#go_back_button').off().one("click", function(e){
        task_stage_check_input( this );
    });

    jQuery( "#go_bonus_button" ).off().one("click", function(e) {
        go_update_bonus_loot(this);
    });


    //add active class to checks and buttons
    jQuery(".progress").closest(".go_checks_and_buttons").addClass('active');

}

function task_stage_check_input( target ) {
	console.log('button clicked');
    //disable button to prevent double clicks
    go_enable_loading( target );

    //BUTTON TYPES
    //Abandon
    //Start Timer
    //Continue
    //Undo
    //Repeat
    //Undo Repeat --is this different than just undo

    //Continue or Complete button needs to validate input for:
    ////quizes
    ///URLs
    ///passwords
    ///uploads

    //if it passes validation:
    ////send information to php with ajax and wait for a response

    //if response is success
    ////update totals
    ///flash rewards and sounds
    ////update last check
    ////update current stage and check


    //v4 Set variables
    var button_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'button_type' ) ) {
        button_type = jQuery( target ).attr( 'button_type' )
    }

    var task_status = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'status' ) ) {
        task_status = jQuery( target ).attr( 'status' )
    }

    var check_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'check_type' ) ) {
        check_type = jQuery( target ).attr( 'check_type' )
    }

    ///v4 START VALIDATE FIELD ENTRIES BEFORE SUBMIT
    if (button_type == 'continue' || button_type == 'complete' || button_type =='continue_bonus' || button_type =='complete_bonus') {
    	if (check_type === 'password' || check_type == 'unlock') {
            var pass_entered = jQuery('#go_result').attr('value').length > 0 ? true : false;
            if (!pass_entered) {
                jQuery('#go_stage_error_msg').show();
                var error = "Retrieve the password from " + go_task_data.admin_name + ".";
                if (jQuery('#go_stage_error_msg').text() != error) {
                    jQuery('#go_stage_error_msg').text(error);
                } else {
                    flash_error_msg('#go_stage_error_msg');
                }
                go_disable_loading();
                return;
            }
        } else if (check_type == 'URL') {
            var the_url = jQuery('#go_result').attr('value').replace(/\s+/, '');
            if (the_url.length > 0) {
                if (the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && !(the_url.lastIndexOf('http://') > 0) && !(the_url.lastIndexOf('https://') > 0)) {
                    var url_entered = true;
                } else {
                    jQuery('#go_stage_error_msg').show();
                    var error = "Enter a valid URL.";
                    if (jQuery('#go_stage_error_msg').text() != error) {
                        jQuery('#go_stage_error_msg').text(error);
                    } else {
                        flash_error_msg('#go_stage_error_msg');
                    }
                    go_disable_loading();
                    return;
                }
            } else {
                jQuery('#go_stage_error_msg').show();
                var error = "Enter a valid URL.";
                if (jQuery('#go_stage_error_msg').text() != error) {
                    jQuery('#go_stage_error_msg').text(error);
                } else {
                    flash_error_msg('#go_stage_error_msg');
                }
                go_disable_loading();
                return;
            }
        }else if (check_type == 'upload') {
            var result = jQuery("#go_result").attr( 'value');
            if (result == undefined) {
                jQuery('#go_stage_error_msg').show();
                var error = "Please attach a file.";
                if (jQuery('#go_stage_error_msg').text() != error) {
                    jQuery('#go_stage_error_msg').text(error);
                } else {
                    flash_error_msg('#go_stage_error_msg');
                }
                go_disable_loading();
                return;
            }

        }else if (check_type == 'quiz') {
            var test_list = jQuery(".go_test_list");
            if (test_list.length >= 1) {
                var checked_ans = 0;
                for (var i = 0; i < test_list.length; i++) {
                    var obj_str = "#" + test_list[i].id + " input:checked";
                    var chosen_answers = jQuery(obj_str);
                    if (chosen_answers.length >= 1) {
                        checked_ans++;
                    }
                }
                //if all questions were answered
                if (checked_ans >= test_list.length) {
                    go_quiz_check_answers(task_status, target);
                    go_disable_loading();
                    return;

                }
                //else print error message
                else if (test_list.length > 1) {
                    jQuery('#go_stage_error_msg').show();
                    if (jQuery('#go_stage_error_msg').text() != "Please answer all questions!") {
                        jQuery('#go_stage_error_msg').text("Please answer all questions!");
                    } else {
                        flash_error_msg('#go_stage_error_msg');
                    }
                    go_disable_loading();
                    return;
                }
                //} else {
                //if (jQuery(".go_test_list input:checked").length >= 1) {
                // go_quiz_check_answers();
                //}
                else {
                    jQuery('#go_stage_error_msg').show();
                    if (jQuery('#go_stage_error_msg').text() != "Please answer the question!") {
                        jQuery('#go_stage_error_msg').text("Please answer the question!");
                    } else {
                        flash_error_msg('#go_stage_error_msg');
                    }
                    go_disable_loading();
                    return;
                }
            }
        }
    }
    task_stage_change( target );
}

function task_stage_change( target ) {
    console.log( "change");
    //v4 Set variables
    var button_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'button_type' ) ) {
        button_type = jQuery( target ).attr( 'button_type' )
    }

    var task_status = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'status' ) ) {
        task_status = jQuery( target ).attr( 'status' )
    }

    var check_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'check_type' ) ) {
        check_type = jQuery( target ).attr( 'check_type' )
    }

    var color = jQuery( '#go_admin_bar_progress_bar' ).css( "background-color" );
    var result = jQuery( '#go_result' ).attr( 'value' );

    if( check_type == 'blog'){
        result = tinyMCE.activeEditor.getContent();
        var result_title = jQuery( '#go_result_title' ).attr( 'value' );
        var blog_post_id= jQuery( '#go_result_title' ).attr( 'blog_post_id' );
        console.log(blog_post_id);

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
            button_type: button_type,
            check_type: check_type,
            result: result,
            result_title: result_title,
            blog_post_id: blog_post_id
        },
        success: function( raw ) {
            console.log('success');
            //console.log(raw);
            // parse the raw response to get the desired JSON
            var res = {};
            try {
                var res = JSON.parse( raw );
            } catch (e) {
                res = {
                    json_status: '101',
                    timer_start: '',
                    button_type: '',
                    time_left: '',
                    html: '',
                    redirect: '',
                    rewards: {
                        gold: 0,
                    },
                };
            }
            //console.log(res.html);
            //alert(json_status);
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
            }else {

                if ( res.button_type == 'undo' ){
                    jQuery( '#go_wrapper div' ).last().hide();
                    jQuery( '#go_wrapper > div' ).slice(-3).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'undo_last' ){
                    jQuery( '#go_wrapper div' ).last().hide();
                    jQuery( '#go_wrapper > div' ).slice(-2).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'continue' ){
                    jQuery( '#go_wrapper > div' ).slice(-1).hide( 'slow', function() { jQuery(this).remove();} );
                }else if ( res.button_type == 'complete' ){
                    jQuery( '#go_wrapper > div' ).slice(-1).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'show_bonus' ){
					jQuery('#go_buttons').remove();
                    //remove active class to checks and buttons
                    jQuery(".go_checks_and_buttons").removeClass('active');
                }
                else if ( res.button_type == 'continue_bonus' ){
					jQuery( '#go_wrapper > div' ).slice(-1).hide( 'slow', function() { jQuery(this).remove();} );
                }
                else if ( res.button_type == 'complete_bonus' ){
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

                    //initializeClock('clockdiv', deadline);
                    //initializeClock('go_timer', deadline);

                    var audio = new Audio( PluginDir.url + 'media/airhorn.mp3' );
                    audio.play();
                }
                    //console.log(res.html);
                    go_append(res);


                //, function() {
                //                         setTimeout(function(){ go_mce(); }, 1000);
                //                 }






                //Pop up currency awards
                jQuery( '#notification' ).html( res.notification );
                jQuery( '#go_admin_bar_progress_bar' ).css({ "background-color": color });
                jQuery( '#go_button' ).ready( function() {
                    //check_locks();
                });

            }

        }
    });

}

function go_mce() {
    // remove existing editor instance
    console.log('mce2');
    tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');

    // init editor for newly appended div
    var init = tinymce.extend( {}, tinyMCEPreInit.mceInit[ 'go_blog_post' ] );
    try { tinymce.init( init ); } catch(e){}
}

function go_append (res){
    //jQuery( res.html ).addClass('active');
    jQuery( res.html ).appendTo( '#go_wrapper' ).stop().hide().show( 'slow' ).promise().then(function() {
        // Animation complete
        Vids_Fit_and_Box();
        go_make_clickable();
        go_disable_loading();
        go_mce();
    });
    //callback();
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
            //points: go_task_data.points_str,
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

