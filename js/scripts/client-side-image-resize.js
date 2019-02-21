(function(media){
    var oldReady = media.view.UploaderWindow.prototype.ready;
    media.view.UploaderWindow.prototype.ready = function() {
        if ( ! this.options.uploader.plupload )
            this.options.uploader.plupload = client_resize.plupload;
        // back to default behaviour
        oldReady.apply( this , arguments );
    };
})(wp.media);

