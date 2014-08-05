/*
	This is the file that handles the displaying of points, level ups, and experience gained when task portions are completed.
*/

function go_notification (timer, el) {	
	if (typeof timer === 'undefined') {
		timer = 1500;	
	}
	if (typeof el === 'undefined') {
		el = false;
	}

	// Fades the notification(s) in after 200 miliseconds
	jQuery(".go_notification").fadeIn(200);
	
	// This block makes sure the elements are all placed first in the stack order so that they appear in front of every other element
	var highest_index = 0;
	jQuery("*").each(function() {
		var current_index = parseInt(jQuery(this).css("z-index"), 10);
		if(current_index > highest_index){
			highest_index = current_index;
			jQuery(".go_notification").css("z-index", highest_index);
		}
	});
	// Fades the notifaction(s) out after being visible for 1.5 seconds
	
	if (el) {
		setTimeout(function() {
			el.fadeOut("slow");
		},timer)
	} else {
		setTimeout(function() {
			jQuery(".go_notification").not('#go_notification_level, #go_notification_badges').fadeOut("slow");
		},timer)
	}
}
