function tinymce_updateCharCounter(el, len) {
    jQuery('.char_count').text(len + '/' + '500');
}

function tinymce_getContentLength() {
    var len = tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
    console.log(len);
    return len;
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
                    console.log("after");

                    tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
                    tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );
                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );
                }
            });
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );

            jQuery(".featherlight").css('background', 'rgba(0,0,0,.8)');
            jQuery(".featherlight .featherlight-content").css('width', '80%');


            jQuery(".go_blog_opener").one("click", function(e){
                go_blog_opener( this );
            });

            /*This code is for the min length validation
            ///counts length of blog posts and activates submit button
            tinyMCE.activeEditor.on('keyup', function(ed) {
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

function go_blog_submit( el ) {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_submit;
    var result = go_get_tinymce_content_blog();
    //var result = tinyMCE.activeEditor.getContent();
    var result_title = jQuery( '#go_result_title_blog' ).val( );

    var blog_post_id= jQuery( el ).attr( 'blog_post_id' );
    var gotoSend = {
        action:"go_blog_submit",
        _ajax_nonce: nonce,
        result: result,
        result_title: result_title,
        blog_post_id: blog_post_id
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        cache: false,
        success: function () {
            console.log("success");
            location.reload();

            //});
        }
    });
}

function go_get_tinymce_content_blog(){
    console.log("html");
    if (jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")){
        return jQuery('#wp-go_blog_post_edit-wrap .wp-editor-area').val();

    }else{
        console.log("visual");
        return tinyMCE.activeEditor.getContent();
    }
}


