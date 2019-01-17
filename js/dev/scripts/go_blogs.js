/*
function tinymce_updateCharCounter(el, len) {
    jQuery('.char_count').text(len + '/' + '500');
}

function tinymce_getContentLength() {
    var len = tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
    console.log(len);
    return len;
}
*/
jQuery( document ).ready( function() {

    //add onclick to blog edit buttons
    console.log("opener3");
    jQuery(".go_blog_opener").one("click", function (e) {
        go_blog_opener(this);
    });
    jQuery(".go_blog_trash").one("click", function (e) {
        go_blog_trash(this);
    });

    jQuery('#go_hidden_mce').remove();
    jQuery('#go_hidden_mce_edit').remove();
});

function task_stage_check_input( target, on_task) {

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
        console.log(check_type);
    }
    var fail = false;
    jQuery('#go_stage_error_msg').text("");
    jQuery('#go_blog_error_msg').text("");
    var error_message = '<h3>Your post was not saved.</h3><ul> ';

    var url_toggle = jQuery(target).attr('url_toggle');
    var video_toggle = jQuery(target).attr('video_toggle');
    var file_toggle = jQuery(target).attr('file_toggle');
    var text_toggle = jQuery(target).attr('text_toggle');
    var suffix = jQuery( target ).attr( 'blog_suffix' );

    var go_result_video = '#go_result_video' + suffix;
    var go_result_url = '#go_result_url' + suffix;
    var go_result_media = '#go_result_media' + suffix;
    console.log ("suffix: " + suffix);

    ///v4 START VALIDATE FIELD ENTRIES BEFORE SUBMIT
    //if (button_type == 'continue' || button_type == 'complete' || button_type =='continue_bonus' || button_type =='complete_bonus') {

    if ( check_type == 'blog' || check_type == 'blog_lightbox') { //min words and Video field on blog form validation

        if(video_toggle == '1') {
            var the_url = jQuery(go_result_video).attr('value').replace(/\s+/, '');
            console.log(the_url);

            if (the_url.length > 0) {
                if (the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && !(the_url.lastIndexOf('http://') > 0) && !(the_url.lastIndexOf('https://') > 0)) {
                    if ((the_url.search("youtube") == -1) && (the_url.search("vimeo") == -1)) {
                        error_message += "<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>";
                        fail = true;
                    }
                } else {
                    error_message += "<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>";
                    fail = true;
                }
            } else {
                error_message += "<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>";
                fail = true;
            }
        }

        if(text_toggle  == '1') {
            //Word count validation
            var min_words = jQuery(target).attr('min_words'); //this variable is used in the other functions as well
            //alert("min Words: " + min_words);
            var my_words = tinymce_getContentLength_new();
            if (my_words < min_words) {
                error_message += "<li>Your post is not long enough. There must be " + min_words + " words minimum.</li>";
                fail = true;
            }
        }

    }
    if (check_type === 'password' || check_type == 'unlock') {
        var pass_entered = jQuery('#go_result').attr('value').length > 0 ? true : false;
        if (!pass_entered) {
            error_message += "Retrieve the password from " + go_task_data.admin_name + ".";
            fail = true;
        }
    }
    if (check_type == 'URL' || ((check_type == 'blog' || check_type == 'blog_lightbox') && url_toggle == true)) {

        if (check_type == 'URL') {
            var the_url = jQuery('#go_result').attr('value').replace(/\s+/, '');
        }else{
            var the_url = jQuery(go_result_url).attr('value').replace(/\s+/, '');
            var required_string = jQuery( target ).attr('required_string');
        }
        console.log("URL" + the_url);

        if (the_url.length > 0) {
            if (the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && !(the_url.lastIndexOf('http://') > 0) && !(the_url.lastIndexOf('https://') > 0)) {
                if ( check_type == 'blog' || check_type == 'blog_lightbox') {
                    if ((the_url.indexOf(required_string) == -1) ){
                        error_message += "<li>Enter a valid URL. The URL must contain \"" + required_string + "\".</li>";
                        fail = true;
                    }
                }
            } else {
                error_message += "<li>Enter a valid URL.</li>";
                fail = true;
            }
        } else {
            error_message += "<li>Enter a valid URL.</li>";
            go_disable_loading();
            fail = true;
        }

    }
    if (check_type == 'upload' || ((check_type == 'blog' || check_type == 'blog_lightbox') && file_toggle == true)) {
        if (check_type == 'upload') {
            var result = jQuery("#go_result").attr('value');
        }else{
            var result = jQuery(go_result_media).attr('value');
        }
        if (result == undefined) {
            error_message += "<li>Please attach a file.</li>";
            fail = true;
        }

    }

    if (check_type == 'quiz') {
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
                fail = false;

            }
            //else print error message
            else if (test_list.length > 1) {
                error_message +="<li>Please answer all questions!</li>";
                fail = true;
            }
            //} else {
            //if (jQuery(".go_test_list input:checked").length >= 1) {
            // go_quiz_check_answers();
            //}
            else {
                error_message += "<li>Please answer the question!</li>";
                fail = true;
            }
        }
    }
    //}
    error_message += "</ul>";
    if (fail == true){
        if (on_task == true) {
            console.log("error_stage");
            //flash_error_msg('#go_stage_error_msg');
            jQuery('#go_stage_error_msg').append(error_message);
            jQuery('#go_stage_error_msg').show();

        }else {

            console.log("error_blog");
            jQuery('#go_blog_error_msg').append(error_message);
            jQuery('#go_blog_error_msg').show();
        }
        console.log("error validation");
        go_disable_loading();
        return;
    }else{
        jQuery('#go_stage_error_msg').hide();
        jQuery('#go_blog_error_msg').hide();
    }

    if (on_task == true) {
        task_stage_change(target);
    }else{ //this was a blog submit button in a lightbox, so just save without changing stage.
        go_blog_submit( target, true );
    }
}

// disables the target stage button, and adds a loading gif to it
function go_enable_loading( target ) {
    //prevent further events with this button
    //jQuery('#go_button').prop('disabled',true);
    // prepend the loading gif to the button's content, to show that the request is being processed
    jQuery('.go_loading').remove();
    target.innerHTML = '<span class="go_loading"></span>' + target.innerHTML;
}

// re-enables the stage button, and removes the loading gif
function go_disable_loading( ) {
    //console.log ("oneclick");
    jQuery('.go_loading').remove();

    jQuery('#go_button').off().one("click", function(e){
        task_stage_check_input( this, true );
    });
    jQuery('#go_back_button').off().one("click", function(e){
        task_stage_change(this);
    });

    jQuery('#go_save_button').off().one("click", function(e){
        //task_stage_check_input( this, false );
        go_blog_submit( this, false );//disable loading
    });


    jQuery( "#go_bonus_button" ).off().one("click", function(e) {
        go_update_bonus_loot(this);
    });

    jQuery('.go_str_item').off().one("click", function(e){
        go_lb_opener( this.id );
    });

    //console.log("opener4");
    jQuery(".go_blog_opener").off().one("click", function(e){
        go_blog_opener( this );
    });

    jQuery(".go_blog_trash").off().one("click", function(e){
        go_blog_trash( this );
    });


    jQuery("#go_blog_submit").off().one("click", function(e){
        task_stage_check_input( this, false );//disable loading
    });

    //add active class to checks and buttons
    jQuery(".progress").closest(".go_checks_and_buttons").addClass('active');

}

function tinymce_getContentLength_new() {
    var b = tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText;
    var e = 0;
    if (b) {
        b = b.replace(/\.\.\./g, " "),
            b = b.replace(/<.[^<>]*?>/g, " ").replace(/&nbsp;|&#160;/gi, " "),
            b = b.replace(/(\w+)(&#?[a-z0-9]+;)+(\w+)/i, "$1$3").replace(/&.+?;/g, " "),
            b = b.replace(/[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g, "");
        var f = b.match(/[\w\u2019\x27\-\u00C0-\u1FFF]+/g);
        f && (e = f.length)
    }
    return e
}

function go_blog_opener( el ) {
    go_enable_loading( el );
    jQuery("#go_hidden_mce").remove();
    jQuery(".go_blog_opener").prop("onclick", null).off("click");

    var check_for_understanding= jQuery( el ).attr( 'data-check_for_understanding' );

    //listener for changes to make sure they are saved.  Alert with sweet alert.
    tinymce.init({
        selector: 'go_blog_post_lightbox',  // change this value according to your HTML
        setup: function(editor) {
            editor.on('keyup', function(e) {
                jQuery('body').attr('data-go_blog_updated', '1');
                console.log("updated");
            });
        }
    });
    //var result_title = jQuery( this ).attr( 'value' );
    var blog_post_id= jQuery( el ).attr( 'blog_post_id' );
    //console.log(el);
    //console.log(blog_post_id);
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_opener;
    var gotoSend = {
        action:"go_blog_opener",
        _ajax_nonce: nonce,
        blog_post_id: blog_post_id,
        check_for_understanding: check_for_understanding
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        cache: false,
        success: function (results) {
            //console.log(results);
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
            jQuery.featherlight(results, {afterContent: function(){
                    console.log("aftercontent");

                    jQuery( 'body' ).attr( 'data-go_blog_saved', '0' );
                    jQuery( 'body' ).attr( 'data-go_blog_updated', '0' );

                    jQuery("#go_result_url_lightbox, #go_result_video_lightbox").change(function() {
                        jQuery('body').attr('data-go_blog_updated', '1');
                    });

                    jQuery('.go_frontend-button').on("click", function() {
                        jQuery('body').attr('data-go_blog_updated', '1');
                    });



                    tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_lightbox');
                    tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_lightbox' );

                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );
                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );
                },
                beforeClose: function() {
                    console.log("beforeClose");
                    //var go_blog_saved= jQuery( 'body' ).attr( 'data-go_blog_saved' );
                    var go_blog_updated= jQuery( 'body' ).attr( 'data-go_blog_updated' );
                    if (go_blog_updated == '1') {
                        swal({
                            title: "You have unsaved changes.",
                            text: "Would you like to save? If you don't save you will not be able to recover these changes.",
                            icon: "warning",
                           //buttons: ["Save and Close", "Close without Saving"],
                            //dangerMode: true,

                            buttons: {
                                exit: {
                                    text: "Close without Saving",
                                    value: "exit",
                                },
                                save: {
                                    text: "Save and Close",
                                    value: "save"
                                }
                            }
                        })
                            .then((value) => {
                                switch (value) {

                                    case "exit":
                                        swal("Your changes were not saved.");
                                        jQuery( 'body' ).attr( 'data-go_blog_updated', '0' );
                                        jQuery.featherlight.close();
                                        break;

                                    case "save":
                                        jQuery('#go_blog_submit').trigger('click');
                                        break;

                                    default:

                                }
                            });
                        return false;
                    }else {

                        //location.reload();
                        //change reload to swap wrapper

                    }
                }
            });
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );

            jQuery(".featherlight").css('background', 'rgba(0,0,0,.8)');
            jQuery(".featherlight .featherlight-content").css('width', '80%');

            //console.log("opener2");
            jQuery(".go_blog_opener").one("click", function(e){
                go_blog_opener( this );
            });
            go_disable_loading();

        }
    });
}

function go_blog_submit( el, reload ) {
    go_enable_loading( el );

    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_submit;
    var suffix = jQuery( el ).attr( 'blog_suffix' );
    var result = go_get_tinymce_content_blog();
    //var result = tinyMCE.activeEditor.getContent();
    var result_title = jQuery( '#go_blog_title' + suffix ).val( );
    //console.log("title: " + result_title);

    var blog_post_id= jQuery( el ).attr( 'blog_post_id' );
    var post_wrapper_class = ".go_blog_post_wrapper_" + blog_post_id;
    var go_blog_bonus_stage= jQuery( el ).attr( 'data-bonus_status' );
    var go_blog_task_stage= jQuery( el ).attr( 'status' );
    var task_id= jQuery( el ).attr( 'task_id' );
    var check_for_understanding= jQuery( el ).attr( 'data-check_for_understanding' );

    var blog_url= jQuery( '#go_result_url' + suffix ).val();
    var blog_media= jQuery( '#go_result_media' + suffix ).attr( 'value' );
    var blog_video= jQuery( '#go_result_video' + suffix).val();
    //console.log("go_blog_bonus_stage: " + go_blog_bonus_stage);
    //console.log("stage: " + blog_media);

    var gotoSend = {
        action:"go_blog_submit",
        _ajax_nonce: nonce,
        result: result,
        result_title: result_title,
        blog_post_id: blog_post_id,
        blog_url: blog_url,
        blog_media: blog_media,
        blog_video: blog_video,
        go_blog_task_stage: go_blog_task_stage,
        go_blog_bonus_stage: go_blog_bonus_stage,
        post_id: task_id,
        check_for_understanding: check_for_understanding
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        cache: false,
        success: function (raw) {
            console.log('success');
            //console.log(raw);
            // parse the raw response to get the desired JSON
            var res = {};
            try {
                var res = JSON.parse( raw );
            } catch (e) {
                res = {
                    json_status: '101',
                    message: '',
                    blog_post_id: '',
                    wrapper: ''
                };
            }
            //console.log("message" + res.message);
            //console.log("blog_post_id: " + res.blog_post_id);
            //console.log("suffix: " + suffix);
            jQuery( 'body' ).attr( 'data-go_blog_updated', '0' );


            jQuery(post_wrapper_class).html(res.wrapper);

            jQuery('body').append(res.message);
            //jQuery('.go_loading').remove();
            go_disable_loading();
            jQuery('#go_save_button' + suffix).off().one("click", function(e){
                //task_stage_check_input( this, false );//on submit, no reload
                go_blog_submit( this, false );
            });


            jQuery( '#go_save_button' + suffix ).attr( 'blog_post_id', res.blog_post_id );

            jQuery( '#go_blog_title' + suffix ).attr( 'data-blog_post_id', res.blog_post_id );

            if (reload == true) {
                //go_disable_loading();
                var current = jQuery.featherlight.current();
                current.close();
            }


            //});
        }
    });
}

function go_blog_trash( el ) {
    go_enable_loading( el );

    swal({
        title: "Are you sure?",
        text: "Do you really want to delete this post?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_trash;

                var blog_post_id= jQuery( el ).attr( 'blog_post_id' );

                var gotoSend = {
                    action:"go_blog_trash",
                    _ajax_nonce: nonce,
                    blog_post_id: blog_post_id,
                };
                //jQuery.ajaxSetup({ cache: true });
                jQuery.ajax({
                    url: MyAjax.ajaxurl,
                    type: 'POST',
                    data: gotoSend,
                    cache: false,
                    success: function (raw) {
                        jQuery("body").append(raw);
                        var post_wrapper_class = ".go_blog_post_wrapper_" + blog_post_id;
                        jQuery(post_wrapper_class).hide();
                        //location.reload();
                        jQuery(".go_blog_trash").off().one("click", function(e){
                            go_blog_trash( this );
                        });
                        swal("Poof! Your post has been deleted!", {
                            icon: "success",
                        });
                    }
                });
                go_disable_loading( el );


            } else {
                swal("Your post is safe!");
                go_disable_loading( el );
            }
        });





}

function go_get_tinymce_content_blog(){
    //console.log("html");
    if (jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")){
        return jQuery('#wp-go_blog_post_edit-wrap .wp-editor-area').val();

    }else{
        //console.log("visual");
        return tinyMCE.activeEditor.getContent();
    }
}

function go_blog_user_task (user_id, task_id) {
    //jQuery(".go_datatables").hide();
    console.log("blogs!");
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_user_task;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_blog_user_task',
            uid: user_id,
            task_id: task_id
        },
        success: function( res ) {

            jQuery.featherlight(res, {
                variant: 'blogs',
                afterOpen: function(event){
                    //console.log("fitvids"); // this contains all related elements
                    //alert(this.$content.hasClass('true')); // alert class of content
                    //jQuery("#go_blog_container").fitVids();
                    go_fit_and_max_only("#go_blog_container");
                }

            });


            if ( -1 !== res ) {

            }
        }
    });
}
