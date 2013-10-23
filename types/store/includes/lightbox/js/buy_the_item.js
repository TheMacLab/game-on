function goBuytheItem(id, buyColor) {
jQuery(document).ready(function(jQuery){
	var gotoBuy = {
                action:'buy_item',
                nonce: "",
				the_id: id,
				qty: jQuery('#go_qty').val()
    };
	// Whenever you figure out a better way to do this, implement it. 
	var color = jQuery('#go_admin_bar_progress_bar').css("background-color");
	jQuery.ajax({
		url: buy_item.ajaxurl,
		type: "POST",
		data: gotoBuy,
		beforeSend: function() {
			jQuery("#golb-fr-buy").innerHTML = "";
			jQuery("#golb-fr-buy").html(''); 
			jQuery("#golb-fr-buy").append('<div id="go-buy-loading" class="buy_'+buyColor+'"></div>');
					},
		dataType: "html",
		success: function(response){
			if (response == 'Insuffcient Funds') {
				alert('Purchase Denied. Reason: '+response);
			} else if (response == 'Rank Too Low') {
				alert('Purchase Denied. Reason: '+response);
			}
			jQuery("#golb-fr-buy").innerHTML = "";
			jQuery("#golb-fr-buy").html('');  
			jQuery("#golb-fr-buy").append('<span>'+response+'</span>');
			// Whenever you figure out a better way to do this, implement it. 
			jQuery('#go_admin_bar_progress_bar').css({"background-color":color});
		}
	});
});
}

function goCountItem(id){
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		type: "POST",
		data:{
			action: 'purchase_count',
			the_item_id: id
		},
		success: function(data){
			var count = data.toString();
			jQuery('#golb-purchased').html("Times purchased: " + count.substring(0, count.length - 1));
		}
	});	
}