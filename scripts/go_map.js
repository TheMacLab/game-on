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
        
        var mapNumID = "mapLink_" + mapNum;
        
        var countThis = "#" + idArray[i] + " .available_color";
        
        var numItems = jQuery(countThis).length;
        
        if (numItems == 0){
            document.getElementById(mapNumID).className += " filtered";
             
        }

        console.log(numItems);
    }
    

   
    var maplink = "mapLink_" + String(mapid);
    document.getElementById(mapid).style.display='none';
    
    
 
  }, false);
