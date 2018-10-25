function tinymce_updateCharCounter(e,o){jQuery(".char_count").text(o+"/500")}function tinymce_getContentLength(){var e=tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;return console.log(e),e}function go_blog_opener(e){jQuery("#go_hidden_mce").remove(),jQuery(".go_blog_opener").prop("onclick",null).off("click");
//var result_title = jQuery( this ).attr( 'value' );
var o=jQuery(e).attr("blog_post_id"),t,a={action:"go_blog_opener",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_opener,blog_post_id:o};
//console.log(el);
//console.log(blog_post_id);
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:a,cache:!1,success:function(e){
//console.log(results);
//tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
//tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
jQuery.featherlight(e,{afterContent:function(){console.log("after"),tinymce.execCommand("mceRemoveEditor",!0,"go_blog_post_edit"),tinymce.execCommand("mceAddEditor",!0,"go_blog_post_edit")}}),
//tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
//tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );
jQuery(".featherlight").css("background","rgba(0,0,0,.8)"),jQuery(".featherlight .featherlight-content").css("width","80%"),jQuery(".go_blog_opener").one("click",function(e){go_blog_opener(this)})}})}function go_blog_submit(e){var o,t,a,n,i={action:"go_blog_submit",_ajax_nonce:GO_EVERY_PAGE_DATA.nonces.go_blog_submit,result:go_get_tinymce_content_blog(),result_title:jQuery("#go_result_title_blog").val(),blog_post_id:jQuery(e).attr("blog_post_id")};
//jQuery.ajaxSetup({ cache: true });
jQuery.ajax({url:MyAjax.ajaxurl,type:"POST",data:i,cache:!1,success:function(){console.log("success"),location.reload()}})}function go_get_tinymce_content_blog(){return console.log("html"),jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")?jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").val():(console.log("visual"),tinyMCE.activeEditor.getContent())}function go_to_this_map(e){var o=GO_EVERY_PAGE_DATA.nonces.go_to_this_map;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:o,action:"go_to_this_map",map_id:e},success:function(e){console.log("success"),window.location.href=e}})}function go_show_map(e){
//https://stackoverflow.com/questions/28180584/wordpress-update-user-meta-onclick-with-ajax
//https://wordpress.stackexchange.com/questions/216140/update-user-meta-using-with-ajax
//
document.getElementById("maps").style.display="none",document.getElementById("loader").style.display="block";var o=jQuery("#_wpnonce").val();console.log(e),console.log(map_ajax_admin_url),jQuery.ajax({type:"POST",url:map_ajax_admin_url,data:{action:"go_update_last_map",goLastMap:e,security:o},success:function(e){jQuery("#mapwrapper").html(e),
//console.log("success!");
go_resizeMap(),document.getElementById("loader").style.display="none",document.getElementById("maps").style.display="block"},error:function(e){console.log(e),console.log("fail")}})}function go_map_check_if_done(){
//declare idArray
var e=[];
//make array of all the maps ids
jQuery(".map").each(function(){e.push(this.id)});for(
//for each map do something
var o=0,t=0;t<e.length;t++){var o,a="#mapLink_"+(o=o++),n="#mapLink_"+o+" .mapLink",i="#map_"+o,r="#"+e[t]+" .available_color",s="#"+e[t]+" .checkmark",l=jQuery(r).length,c=jQuery(s).length;0==l&&(0==c?jQuery(a).addClass("filtered"):(jQuery(a).addClass("done"),jQuery(n).addClass("checkmark")))}go_resizeMap()}
//Resize map function, also runs on window load
function go_resizeMap(){
//get mapid from data
var e,o="#map_"+jQuery("#maps").data("mapid"),t=jQuery(o+" .primaryNav > li").length-1;0==t&&(t=1),t==1/0&&(t=1);var a=100/t,n=jQuery(o).width()/t;
//set the width of the tasks on the map
//jQuery(mapID + " .primaryNav li").css("width", taskWidth + "%");
100==a?(jQuery(o+" .primaryNav > li").css("width","90%"),jQuery(o+" .primaryNav li").css("float","right"),jQuery(o+" .tasks > li").css("width","80%"),jQuery(o+" .primaryNav li").addClass("singleCol")):130<=n?(jQuery(o+" .primaryNav li").css("float","left"),jQuery(o+" .primaryNav li").css("width",a+"%"),jQuery(o+" .tasks > li").css("width","100%"),jQuery(o+" .primaryNav li").css("background","")):(jQuery(o+" .primaryNav > li").css("width","100%"),jQuery(o+" .primaryNav li").css("float","right"),jQuery(o+" .tasks > li").css("width","95%"),
//jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
jQuery(o+" .primaryNav li").addClass("singleCol")),jQuery("#sitemap").css("visibility","visible"),jQuery("#maps").css("visibility","visible")}
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */function go_map_dropDown(){document.getElementById("myDropdown").classList.toggle("show")}//Hide and show map on click
jQuery(document).ready(function(){go_map_check_if_done()}),
//Resize listener
jQuery(window).resize(function(){go_resizeMap()}),
// Close the dropdown menu if the user clicks outside of it
window.onclick=function(e){if(!e.target.matches(".dropbtn")){var o=document.getElementsByClassName("dropdown-content"),t;for(t=0;t<o.length;t++){var a=o[t];a.classList.contains("show")&&a.classList.remove("show")}}};