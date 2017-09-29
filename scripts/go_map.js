//Hide and show map on click
function go_show_map(mapid) {
    //hide all when clicked
    var divsToHide = document.getElementsByClassName("map"); //divsToHide is an array
    for (var i = 0; i < divsToHide.length; i++){
    	
        divsToHide[i].style.display = "none"; // depending on what you're doing
    }
    //show the one clicked
    var mapid = "map_" + String(mapid);
    document.getElementById(mapid).style.display='block';

    
}

//Set the filtered and done content in the dropdown
window.addEventListener('load', 
  function() { 
    //declare idArray
    var idArray = [];
    //make array of all the maps ids
    jQuery('.map').each(function () {
        idArray.push(this.id);
    });
    //for each map do something

    var mapNum = 0;
    for (var i = 0; i < idArray.length; i++){
        var mapNum = mapNum + 1;
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
    

    jQuery('#sitemap').css("display","block");
    jQuery('#map_1').css("display","block");    
 

    resizeMap();
  }, false);

//Resize listener
jQuery( window ).resize(function() {
    resizeMap();
    })

//Resize map function, also runs on window load 
function resizeMap() {
    //declare idArray
    var idArray = [];
    //make array of all the maps ids
    jQuery('.map').each(function () {
        idArray.push(this.id);
    });
    //for each map do something

    var mapNum = 0;
    for (var i = 0; i < idArray.length; i++){
        var mapNum = mapNum + 1;
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
            jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
 
        }
        else if (minWidth >= 130){
            jQuery(mapID + " .primaryNav li").css("float","left"); 
           
            jQuery(mapID + " .primaryNav li").css("width", taskWidth + "%");
            jQuery(mapID + ' .tasks > li').css("width","100%");
             jQuery(mapID + " .primaryNav li").css("background", "");
 
        }
        else {

            jQuery(mapID + ' .primaryNav > li').css("width","90%");  
            jQuery(mapID + ' .primaryNav li').css("float","right"); 
            jQuery(mapID + ' .tasks > li').css("width","80%"); 
            jQuery(mapID + " .primaryNav li").css("background", "url('../wp-content/plugins/game-on-master/styles/images/map/vertical-line.png') center top no-repeat");
 
        }
   
  }
}


/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function dropDown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

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
