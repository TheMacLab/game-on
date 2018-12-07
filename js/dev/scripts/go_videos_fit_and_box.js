
jQuery(window).ready(function(){
    //jQuery(".mejs-container").hide();
    go_Vids_Fit_and_Box("body");


});

function go_Vids_Fit_and_Box (fit_element){
    runmefirst(fit_element, function() {
        //after making the video fit, set the max width and add the lightbox code
        go_Max_width_and_LightboxNow();
        //go_native_video_resize();
    });
}

function runmefirst(fit_element, callback) {
    jQuery(fit_element).fitVids();
    callback();
}


//For Max width only when videos are already in lightboxes (the store for example)
function go_fit_and_max_only(fit_element){
    go_fit_and_max_only_first(fit_element, function(){
        go_Max_width()
    });
}

function go_fit_and_max_only_first(fit_element, callback){
    jQuery(fit_element).fitVids();
    callback();
}


//resize in the lightbox--featherlight
function go_video_resize(){
    var VratioH = jQuery('.featherlight-content .fluid-width-video-wrapper').css('padding-top');
    var VratioW = jQuery('.featherlight-content .fluid-width-video-wrapper').css('width');

    VratioH = parseFloat(VratioH);
    VratioW = parseFloat(VratioW);
    //console.log ("resize:");
    //console.log ("VratioH:" + VratioH);
    //console.log ("VratioW:" + VratioW);
    var Vratio = VratioH/VratioW;
    console.log ("Vratio:" + Vratio);
    var vW = jQuery( window ).width();
    console.log ("vW:" + vW);
    var contentWidth = vW;
    var vH = jQuery( window ).height();
    console.log ("vH:" + vH);
    var contentHeight = vW * Vratio;
    console.log ("cH1:" + contentHeight);
    if (contentHeight > vH){
        contentHeight = vH - 50 ;
        console.log ("cH2:" + contentHeight);
        contentWidth = (contentHeight / Vratio ) ;
        console.log ("cW:" + contentWidth);
    }

    jQuery(".featherlight-content").css('width', contentWidth);
    jQuery(".featherlight-content").css('height', contentHeight);

}

function go_Max_width(){
    //var _maxwidth = jQuery("#go_wrapper").data('maxwidth');
    var _maxwidth = GO_EVERY_PAGE_DATA.go_fitvids_maxwidth;
    console.log("max" + _maxwidth);
    //var fluid_width_video_wrapper = {};
    jQuery(".fluid-width-video-wrapper:not(.fit)").each(function(){

        jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>');
        jQuery(this).addClass('fit');
        jQuery( ".max-width-video-wrapper").css("max-width", _maxwidth);
    });

    //add max-width wrapper to wp-video (added natively or with shortcode
    jQuery(".wp-video:not(.fit)").each(function(){

        jQuery(this).wrap('<div class="max-width-video-wrapper" style="position:relative;"><div>');
        jQuery(this).addClass('fit');
        jQuery( ".max-width-video-wrapper").css("max-width", _maxwidth);
    });
}

function go_Max_width_and_LightboxNow(){
//console.log("max_width");
    //add a max width video wrapper to the fitVid
    go_Max_width();

    //Toggle lightbox on and off based on option
    //var lightbox_switch = jQuery("#go_wrapper").data('lightbox');
    var lightbox_switch = GO_EVERY_PAGE_DATA.go_lightbox_switch;

    //console.log("lbs" + lightbox_switch);
    if (lightbox_switch === "1"){
        //alert (lightbox_switch);
        //add a featherlight lightroom wrapper to the fitvids iframes
        jQuery(".max-width-video-wrapper:not(.wrapped):has(iframe)").each(function(){
            jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_iframe" href="#" ><div style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;"></div></a>');
            jQuery(".max-width-video-wrapper").children().unbind();
            jQuery(this).addClass('wrapped');

        });

        //adds a html link to the wrapper for featherlight lightbox
        jQuery('[class^="featherlight_wrapper_iframe"]').each(function(){
            var _src = jQuery(this).parent().find('.fluid-width-video-wrapper').parent().html();
            //console.log("src2:" + _src);
            //_src="<div class=\"fluid-width-video-wrapper fit\" style=\"padding-top: 56.1905%;\"><iframe src=\"https://www.youtube.com/embed/zRvOnnoYhKw?feature=oembed?&autoplay=1\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen=\"\" name=\"fitvid0\"></iframe></div>"
            jQuery(this).attr("href", "<div id=\"go_video_container\" style=\" overflow: hidden;\">" + _src + "</div>");
            jQuery('.featherlight_wrapper_iframe').featherlight({
                targetAttr: 'href',
                closeOnEsc: true,
                variant: 'fit_and_box',
                afterOpen: function(event){
                    //console.log ("this" + this);
                    jQuery(".featherlight-content").css({
                    //jQuery(this).css({
                        'width' : '100%',
                        'overflow' : 'hidden'
                    });
                    //jQuery(".featherlight-content iframe").src().append( "&autoplay=1");
                    jQuery(".featherlight-content iframe").attr('src' , jQuery(".featherlight-content iframe").attr('src') + "&autoplay=1");
                    //jQuery(this).find("iframe").attr('src' , jQuery(this).find("iframe").attr('src') + "&autoplay=1");
                    //ev.preventDefault();
                    //console.log("src2:" + _src);

                    go_video_resize();
                    jQuery( window ).resize(function() {
                        go_video_resize();
                    });
                }
            });
        });


        //adds link to native video

        var checkExist = setInterval(function() {
            if (jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").length) {
                console.log("Exists!");
                clearInterval(checkExist);
                jQuery(".max-width-video-wrapper:not(.wrapped):has(video)").each(function(){
                    //jQuery(this).prepend('<a style="display:block;" class="featherlight_wrapper_native_vid" href="#" data-featherlight="iframe" ><span style="position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;"></span></a>');
                    var vidURL = jQuery(this).find('video').attr('src');
                    console.log("src:" + vidURL);
                    //jQuery(this).prepend('<a  class="featherlight_wrapper_vid_native" href="#"><span style=\'position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;\'></span></a>');
                    jQuery(this).prepend("<a href='#' class='featherlight_wrapper_vid_shortcode' data-featherlight='<div id=\"go_video_container\" style=\"height: 90vh; overflow: hidden; text-align: center;\"> <video controls autoplay style=\"height: 100%; max-width: 100%;\"><source src=\"" + vidURL + "\" type=\"video/mp4\">Your browser does not support the video tag.</video></div>'  data-featherlight-close-on-esc='true' data-featherlight-variant='fit_and_box native2' ><span style=\"position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 4;\"></span></a> ")
                    //jQuery(this).children(".featherlight_wrapper_vid_shortcode").prepend("<span style=\"position:absolute; width:100%; height:100%; top:0; left: 0; z-index: 1;\"></span>");
                    //jQuery(".mejs-overlay-play").unbind("click");
                    jQuery(this).addClass('wrapped');
                });
            }
        }, 100); // check every 100ms
    }
 }
