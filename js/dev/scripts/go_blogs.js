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
    jQuery("#go_hidden_mce").remove();
    jQuery(".go_blog_opener").prop("onclick", null).off("click");
    //var result_title = jQuery( this ).attr( 'value' );
    var blog_post_id= jQuery( el ).attr( 'blog_post_id' );
    //console.log(el);
    //console.log(blog_post_id);
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_opener;
    var gotoSend = {
        action:"go_blog_opener",
        _ajax_nonce: nonce,
        blog_post_id: blog_post_id
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
                    //console.log("after");

                    tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_lightbox');
                    tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_lightbox' );
                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );
                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );
                }
            });
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );

            jQuery(".featherlight").css('background', 'rgba(0,0,0,.8)');
            jQuery(".featherlight .featherlight-content").css('width', '80%');

            console.log("opener2");
            jQuery(".go_blog_opener").one("click", function(e){
                go_blog_opener( this );
            });

            /*

            //This code is for the min length validation
            ///counts length of blog posts and activates submit button
            tinyMCE.activeEditor.on('keyup', function(ed) {
                console.log ("keyclick");
                tinymce_updateCharCounter(this, tinymce_getContentLength());
                if (tinymce_getContentLength() + 1 > 500) {
                    jQuery("#go_blog_submit").show();
                    jQuery("#go_blog_submit").one("click", function(e){
                        go_blog_submit( this );
                    });
                    return ;
                }
                else{
                    jQuery("#go_blog_submit").hide();
                    jQuery("#go_blog_submit").unbind("click");

                    return;
                }
            });
            */


        }
    });
}

function go_blog_submit( el, suffix, reload ) {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_submit;
    var result = go_get_tinymce_content_blog();
    //var result = tinyMCE.activeEditor.getContent();
    var result_title = jQuery( '#go_blog_title' + suffix ).val( );
    console.log("title: " + result_title);

    var blog_post_id= jQuery( el ).attr( 'blog_post_id' );

    var blog_url= jQuery( '#go_result_url' + suffix ).val();
    var blog_media= jQuery( '#go_result_media' + suffix ).attr( 'value' );
    var blog_video= jQuery( '#go_result_video' + suffix).val();

    var gotoSend = {
        action:"go_blog_submit",
        _ajax_nonce: nonce,
        result: result,
        result_title: result_title,
        blog_post_id: blog_post_id,
        blog_url: blog_url,
        blog_media: blog_media,
        blog_video: blog_video,
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        cache: false,
        success: function (res) {
            console.log("success");
            if (reload == true) {
                location.reload();
            }
            else{
                if ( 0 != res ) {
                    jQuery('body').append(res);
                    //console.log(res);
                    jQuery('.go_loading').remove();
                    jQuery('#go_save_button' + suffix).off().one("click", function(e){
                        task_stage_check_input( this, false, false );
                    });

                }
            }
            //});
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
