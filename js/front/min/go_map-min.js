//Hide and show map on click
function go_to_this_map(a){var e=GO_EVERY_PAGE_DATA.nonces.go_to_this_map;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_to_this_map",map_id:a},success:function(a){console.log("success"),window.location.href=a}})}function go_show_map(a){
//https://stackoverflow.com/questions/28180584/wordpress-update-user-meta-onclick-with-ajax
//https://wordpress.stackexchange.com/questions/216140/update-user-meta-using-with-ajax
//
document.getElementById("maps").style.display="none",document.getElementById("loader").style.display="block";var e=jQuery("#_wpnonce").val();console.log(a),console.log(map_ajax_admin_url),jQuery.ajax({type:"POST",url:map_ajax_admin_url,data:{action:"go_update_last_map",goLastMap:a,security:e},success:function(a){jQuery("#mapwrapper").html(a),
//console.log("success!");
go_resizeMap(),document.getElementById("loader").style.display="none",document.getElementById("maps").style.display="block"},error:function(a){console.log(a),console.log("fail")}})}function go_map_check_if_done(){
//declare idArray
var a=[];
//make array of all the maps ids
jQuery(".map").each(function(){a.push(this.id)});for(
//for each map do something
var e=0,s=0;s<a.length;s++){var e,i="#mapLink_"+(e=e++),o="#mapLink_"+e+" .mapLink",r="#map_"+e,t="#"+a[s]+" .available_color",l="#"+a[s]+" .checkmark",n=jQuery(t).length,c=jQuery(l).length;0==n&&(0==c?jQuery(i).addClass("filtered"):(jQuery(i).addClass("done"),jQuery(o).addClass("checkmark")))}go_resizeMap()}
//Resize map function, also runs on window load
function go_resizeMap(){
//get mapid from data
var a,e="#map_"+jQuery("#maps").data("mapid"),s=jQuery(e+" .primaryNav > li").length-1;0==s&&(s=1),s==1/0&&(s=1);var i=100/s,o=jQuery(e).width()/s;
//set the width of the tasks on the map
//jQuery(mapID + " .primaryNav li").css("width", taskWidth + "%");
100==i?(jQuery(e+" .primaryNav > li").css("width","90%"),jQuery(e+" .primaryNav li").css("float","right"),jQuery(e+" .tasks > li").css("width","80%"),jQuery(e+" .primaryNav li").addClass("singleCol")):130<=o?(jQuery(e+" .primaryNav li").css("float","left"),jQuery(e+" .primaryNav li").css("width",i+"%"),jQuery(e+" .tasks > li").css("width","100%"),jQuery(e+" .primaryNav li").css("background","")):(jQuery(e+" .primaryNav > li").css("width","100%"),jQuery(e+" .primaryNav li").css("float","right"),jQuery(e+" .tasks > li").css("width","95%"),
//jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
jQuery(e+" .primaryNav li").addClass("singleCol")),jQuery("#sitemap").css("visibility","visible"),jQuery("#maps").css("visibility","visible")}
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */function go_map_dropDown(){document.getElementById("myDropdown").classList.toggle("show")}jQuery(document).ready(function(){go_map_check_if_done()}),
//Resize listener
jQuery(window).resize(function(){go_resizeMap()}),
// Close the dropdown menu if the user clicks outside of it
window.onclick=function(a){if(!a.target.matches(".dropbtn")){var e=document.getElementsByClassName("dropdown-content"),s;for(s=0;s<e.length;s++){var i=e[s];i.classList.contains("show")&&i.classList.remove("show")}}};