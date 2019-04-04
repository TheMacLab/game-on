


//Resize listener--move to map shortcode/lightbox
/*
//Hide and show map on click
jQuery( document ).ready(function() {
    go_map_check_if_done();
});


jQuery( window ).resize(function() {
    go_resizeMap();
});
*/



// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {

        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}


function go_to_this_map(map_id) {
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_to_this_map;
    jQuery.ajax({
        type: "post",
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_to_this_map',
            map_id: map_id

        },
        success: function (res) {
            console.log("success");
            window.location.href=res;
        }
    });
}

function go_show_map(mapid) {
//https://stackoverflow.com/questions/28180584/wordpress-update-user-meta-onclick-with-ajax
//https://wordpress.stackexchange.com/questions/216140/update-user-meta-using-with-ajax
//
	document.getElementById("maps").style.display = "none";
	document.getElementById("loader_container").style.display = "block";
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_update_last_map;
    var uid = jQuery('#go_map_user').data("uid");
	//var map_nonce = jQuery( '#_wpnonce' ).val();

    console.log(mapid);
    //console.log(map_ajax_admin_url);
	

	jQuery.ajax({	
		type: "POST",
		url : MyAjax.ajaxurl,
			data: {
				action:'go_update_last_map',
				goLastMap : mapid,
                _ajax_nonce: nonce,
                uid: uid
			},
			success:function(data) {			
          		jQuery('#mapwrapper').html(data);				
				console.log("success!");
				go_resizeMap();
				document.getElementById("loader_container").style.display = "none";
 				document.getElementById("maps").style.display = "block";
				
			},
			error: function(errorThrown){
				console.log(errorThrown);
				console.log("fail");
			}
			
	});
}

//I think this was supposed to check the dropdown to see if the maps were done.
//It doesn't work
/*
function go_map_check_if_done() {
    go_resizeMap();
    //declare idArray
    var idArray = [];
    //make array of all the maps ids
    jQuery('.map').each(function () {
        idArray.push(this.id);
    });
    console.log("IDS" + idArray);
    console.log(idArray.length);
    //for each map do something
    var mapNum = 0;
    for (var i = 0; i < idArray.length; i++){
        var mapNum = mapNum++;
        var mapNumID = "#mapLink_" + mapNum;
        var mapNumClass = "#mapLink_" + mapNum + ' .mapLink';
        var mapID = "#map_" + mapNum;
        var countAvail = "#" + idArray[i] + " .available_color";
        var countDone = "#" + idArray[i] + " .checkmark";
        var numAvail = jQuery(countAvail).length;
        var numDone = jQuery(countDone).length;
        
     
        if (numAvail == 0){
            if (numDone == 0){
                
                jQuery(mapNumID).addClass("filtered"); 
            }
            else {
                
                jQuery(mapNumID).addClass("done");
                jQuery(mapNumClass).addClass("checkmark");
            }    
        }
    }

    //go_resizeMap();
  }
  */

//Resize map function, also runs on window load
function go_resizeMap() {
 	console.log("resize");
	//get mapid from data
	var mapNum = jQuery("#maps").data('mapid');

    var mapID = "#map_" + mapNum;
        
        var taskCount = ((jQuery(mapID + " .primaryNav > li").length)-1);
        if (taskCount == 0){
            taskCount = 1;
        }
        if (taskCount == Infinity){
            taskCount = 1;
        }
        var taskWidth = (100/taskCount);
        var minWidth = ((jQuery(mapID).width()) / taskCount);
      
        //set the width of the tasks on the map
        //jQuery(mapID + " .primaryNav li").css("width", taskWidth + "%");
        
        if (taskWidth == 100) {
            jQuery(mapID + ' .primaryNav > li').css("width","90%");  
            jQuery(mapID + ' .primaryNav li').css("float","right"); 
            jQuery(mapID + ' .tasks > li').css("width","80%"); 
            jQuery(mapID + " .primaryNav li").addClass("singleCol");
            //jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
 
        }
        else if (minWidth >= 130){
            jQuery(mapID + " .primaryNav li").css("float","left"); 
           
            jQuery(mapID + " .primaryNav li").css("width", taskWidth + "%");
            jQuery(mapID + ' .tasks > li').css("width","100%");
            jQuery(mapID + " .primaryNav li").css("background", "");
 
        }
        else {
            jQuery(mapID + ' .primaryNav > li').css("width","100%");  
            jQuery(mapID + ' .primaryNav li').css("float","right"); 
            jQuery(mapID + ' .tasks > li').css("width","95%"); 
            //jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
 			jQuery(mapID + " .primaryNav li").addClass("singleCol");
        }
        
			jQuery('#sitemap').css("visibility","visible");  
   			jQuery('#maps').css("visibility","visible"); 
        
}

/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function go_map_dropDown() {
    document.getElementById("go_Dropdown").classList.toggle("show");
}

function go_user_map(user_id) {
    console.log("map");
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_user_map_ajax;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data: {
            _ajax_nonce: nonce,
            action: 'go_user_map_ajax',
            uid: user_id
        },
        success: function (res) {
            if (-1 !== res) {
                jQuery.featherlight(res, {
                    variant: 'map',
                    afterOpen: function () {
                        console.log("after");
                        //go_map_check_if_done();
                        go_resizeMap();
                        jQuery( window ).resize(function() {
                           // go_resizeMap();
                        });
                        jQuery(window).on('resize', function(){
                            go_resizeMap();
                        });
                    }
                });
            }
        }
    });

}
