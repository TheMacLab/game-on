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
            jQuery.featherlight(results);
            tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post');
            tinymce.execCommand( 'mceAddEditor', false, 'go_blog_post' );
            jQuery(".featherlight").css('background', 'rgba(0,0,0,.8)');
            jQuery(".featherlight .featherlight-content").css('width', '80%')


            jQuery(".go_blog_opener").one("click", function(e){
                go_blog_opener( this );
            });
            //});
        }
    });
}

function go_blog_submit( el ) {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_submit;
    var result = tinyMCE.activeEditor.getContent();
    var result_title = jQuery( '#go_result_title' ).attr( 'value' );
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


