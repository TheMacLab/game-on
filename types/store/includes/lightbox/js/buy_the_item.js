function goBuytheItem(id, buyColor) {
jQuery(document).ready(function(jQuery){
	var gotoBuy = {
                action:'buy_item',
                nonce: "",
				the_id: id,
				qty: jQuery('#go_qty').val()
    };
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
		}
	});
});
}