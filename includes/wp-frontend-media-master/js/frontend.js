//(function($) {

//$(document).ready( function() {
function go_upload_frontend() {

    var file_frame; // variable for the wp.media file_frame

    // attach a click event (or whatever you want) to some element on your page
    //$( '#frontend-button' ).on( 'click', function( event ) {
    event.preventDefault();

    //if the file_frame has already been created, just reuse it
    if (file_frame) {
        file_frame.open();
        return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
        title: $(this).data('uploader_title'),
        button: {
            text: $(this).data('uploader_button_text'),
        },
        multiple: false // set this to true for multiple file selection
    });

    file_frame.on('select', function () {
        attachment = file_frame.state().get('selection').first().toJSON();
        //console.log ("here");
        //console.log (attachment);
        // do something with the file here
        $('#frontend-button').attr('value', 'Change File');
        $('#go_stage_error_msg').hide();
        if (attachment.type == 'image') {
            $('#go_result').attr('src', attachment.url);
        }
        else{
            $('#go_result').attr('src', attachment.icon);
        }
        $('#go_result').attr('value', attachment.id);
        $('#go_result_name').html(attachment.title);

    });

    file_frame.open();
}
	//});
//});

//})(jQuery);