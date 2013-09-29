function go_notification (){
	jQuery(".go_notification").fadeIn(200);
	setTimeout(function(){
		jQuery(".go_notification").fadeOut("slow");
	},1500) 
}
