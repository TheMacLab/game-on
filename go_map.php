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
    

   
    var maplink = "mapLink_" + String(mapid);
    document.getElementById(mapid).style.display='none';
    
    
 
  }, false);
