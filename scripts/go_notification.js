/*
	This is the file that handles the displaying of points, level ups, and experience gained when task portions are completed.
*/

function go_notification (){	

	// Fades the notification(s) in after 200 miliseconds
	jQuery(".go_notification").fadeIn(200);
	
	// This block makes sure the elements are all placed first in the stack order so that they appear in front of every other element
	var highest_index = 0;
	jQuery("*").each(function(){
		var current_index = parseInt(jQuery(this).css("z-index"), 10);
		if(current_index > highest_index){
			highest_index = current_index;
			jQuery(".go_notification").css("z-index", highest_index);
		}
	});
	
	// Fades the notifaction(s) out after being visible for 1.5 seconds
	setTimeout(function(){
		jQuery(".go_notification").fadeOut("slow");
	},1500)
}
