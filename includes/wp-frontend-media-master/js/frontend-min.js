//(function($) {
//$(document).ready( function() {
function go_upload_frontend(t,e){var a="#"+t,l;console.log("value: "+a);// variable for the wp.media file_frame
// attach a click event (or whatever you want) to some element on your page
//$( '#frontend-button' ).on( 'click', function( event ) {
//event.preventDefault();
//add ajax function that converts extensions into list of mime types for the media uploader
var n="Select a file",o;0!=e.length&&(n=n+" (Allowed types: "+e.replace(/,/g,", ")+")");
// Add page slug to media uploader settings
_wpPluploadSettings.defaults.multipart_params.admin_page="gif",
//if the file_frame has already been created, just reuse it
l||(l=wp.media.frames.file_frame=wp.media({title:n,button:{text:"Select"},multiple:!1,// set this to true for multiple file selection
library:{type:e}}),jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{action:"go_media_filter_ajax",mime_types:e},success:function(t){console.log("filtered")}}),l.on("select",function(){attachment=l.state().get("selection").first().toJSON(),console.log("here"+this),
// do something with the file here
$("#frontend-button").attr("value","Change File"),$("#go_stage_error_msg").hide(),"image"==attachment.type?$(a).attr("src",attachment.url):$(a).attr("src",attachment.icon),$(a).attr("value",attachment.id),$("#go_result_media_name").html(attachment.title)})),l.open()}
//});
//});
//})(jQuery);