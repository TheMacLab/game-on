//(function($) {

//$(document).ready( function() {
function go_upload_frontend(div_id, mime_types) {

    var val = "#" + div_id ;
    console.log("value: " + val);

    var file_frame; // variable for the wp.media file_frame

    // attach a click event (or whatever you want) to some element on your page
    //$( '#frontend-button' ).on( 'click', function( event ) {
    //event.preventDefault();

    //add ajax function that converts extensions into list of mime types for the media uploader
    var title = "Select a file"
    if (mime_types.length != 0) {
        var restricted = mime_types.replace(/,/g, ', ')
        title = title + " (Allowed types: " + restricted + ")";
    }

    // Add page slug to media uploader settings
    _wpPluploadSettings['defaults']['multipart_params']['admin_page']= 'gif';
    //if the file_frame has already been created, just reuse it
    if (file_frame) {
        file_frame.open();
        return;
    }
    else {
        file_frame = wp.media.frames.file_frame = wp.media({
            title: title,
            button: {
                text: 'Select',
            },
            multiple: false, // set this to true for multiple file selection
            library: {
                type: mime_types
            },
        });

    }

    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            action: 'go_media_filter_ajax',
            mime_types : mime_types
        },
        success: function( res ) {
            console.log("filtered");
        }
    });

    file_frame.on('select', function () {
        attachment = file_frame.state().get('selection').first().toJSON();
        console.log ("here" + this);

        // do something with the file here
        $('#frontend-button').attr('value', 'Change File');
        $('#go_stage_error_msg').hide();
        if (attachment.type == 'image') {
            $(val).attr('src', attachment.url);
        }
        else{
            $(val).attr('src', attachment.icon);
        }
        $(val).attr('value', attachment.id);
        $('#go_result_media_name').html(attachment.title);

    });

    file_frame.open();
}
	//});
//});

//})(jQuery);