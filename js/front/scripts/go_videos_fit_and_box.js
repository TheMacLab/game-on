
jQuery(window).ready(function(){
    //jQuery(".mejs-container").hide();
	Vids_Fit_and_Box();
});

function Vids_Fit_and_Box(){
    runmefirst(function() {
        Max_width_and_LightboxNow();
       go_native_video_resize();
    });
};

function runmefirst(callback) {
    fitVidsNow();
    callback();
};


function fitVidsNow(){
        jQuery("body").fitVids();
       // var local_customSelector = "mejs-container";
    	jQuery("body").fitVids({customSelector: "video"});
}

function go_native_video_resize() {

    jQuery(window).resize(function() {
        jQuery("video.wp-video-shortcode").css("height", "");
        setTimeout(function(){ jQuery("mediaelementwrapper .wp-video-shortcode, .mejs-container").css("height", "");
            var vidHeight = jQuery("video.wp-video-shortcode").height();
            //console.log("h:" + vidHeight);
            jQuery(".mejs-container").css("height", vidHeight);
            jQuery(".fluid-width-video-wrapper:has(.mejs-container)").css("padding-top", "");
            //jQuery(".mejs-container").show();

        }, 1000);

    }).resize();

}

function Max_width_and_LightboxNow(){  
        //do stuff
		//add a max width video wrapper to the fitVid
		var _maxwidth = jQuery("#go_wrapper").data('maxwidth');
        //var fluid_width_video_wrapper = {};
        jQuery(".fluid-width-video-wrapper:not(.fit)").each(function(){

	        jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>');
	        jQuery(this).addClass('fit');
	        jQuery( ".max-width-video-wrapper").css("max-width", _maxwidth);
        });

    	//Toggle lightbox on and off based on option
    	var lightbox_switch = jQuery("#go_wrapper").data('lightbox');

    	if (lightbox_switch === 1){
            //alert (lightbox_switch);
			//add a featherlight lightroom wrapper to the fitvids iframes
			jQuery(".max-width-video-wrapper:not(.wrapped):has(iframe)").each(function(){
				jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_iframe" href="#" data-featherlight="iframe" ><span style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;"></span></a>');
				jQuery(this).addClass('wrapped');

			});

            //adds a html link to the wrapper for featherlight lightbox

            jQuery('[class^="featherlight_wrapper_iframe"]').each(function(){
                var _src = jQuery(this).parent().find('iframe').attr('src');
                jQuery(this).attr("href", _src);
                var _href = jQuery(this).attr("href");
                jQuery(this).attr("href", _href + '?&autoplay=1');
                //activates the lightbox
                //jQuery.featherlight.defaults.closeOnClick = true;
                jQuery.featherlight.defaults.iframeWidth = '100%';
                jQuery.featherlight.defaults.iframeHeight = '100%';
                jQuery(this).featherlight();
            });


            //add a featherlight lightroom wrapper to the fitvids native video
            jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").each(function(){
                jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_native_vid" href="#" data-featherlight="iframe" ><span style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;"></span></a>');
                jQuery(this).addClass('wrapped');
            });

            //adds a html link to the wrapper for featherlight lightbox
            setTimeout(function(){
            jQuery('[class^="featherlight_wrapper_native_vid"]').each(function(){
                    var _src = jQuery(this).parent().find('video').attr('src');
                    //var _src = jQuery(this).attr('src');
                    console.log(_src);
                    //jQuery(this).attr("href", _src);
                    //var _href = jQuery(this).attr("href");
                    //console.log("href:" + _href);
                    jQuery(this).attr("href", _src + '?&autoplay=1');
                    //activates the lightbox
                    //jQuery.featherlight.defaults.closeOnClick = true;
                    jQuery.featherlight.defaults.iframeWidth = '100%';
                    jQuery.featherlight.defaults.iframeHeight = '100%';
                    jQuery(this).featherlight();
                });
            }, 200);

        } 
 }
 
