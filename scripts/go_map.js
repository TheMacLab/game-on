function go_show_map(mapid) {

    
       
    
       var divsToHide = document.getElementsByClassName("map"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
    	
        divsToHide[i].style.display = "none"; // depending on what you're doing
    }
    
    var mapid = "map_" + String(mapid);
    document.getElementById(mapid).style.display='block';

    
}