/*
	This is the file that handles the displaying of points, level ups, and experience gained when task portions are completed.
*/

function go_notification (timer, el) {	
	if (typeof timer === 'undefined') {
		timer = 3000;	
	}
	if (typeof el === 'undefined') {
		el = false;
	}

	// Fades the notification(s) in after 200 miliseconds
	jQuery(".go_notification").fadeIn(200);
	
	// Fades the notification(s) out after being visible for 1.5 seconds
	if (el) {
		setTimeout(function () {
			el.fadeOut("slow", function(){
				el.remove();
			});
		}, timer);
	} else {
		setTimeout(function () {
			jQuery(".go_notification").not('#go_notification_level, #go_notification_badges').fadeOut("slow", function () {
				jQuery('.go_notification').remove();
			});
		}, timer);
	}
}