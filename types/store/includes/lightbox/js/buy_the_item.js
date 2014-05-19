function goBuytheItem(id, buyColor, count) {
jQuery(document).ready(function(jQuery){
	var gotoBuy = {
                action:'buy_item',
                nonce: "",
				the_id: id,
				qty: jQuery('#go_qty').val(),
				recipient: jQuery('#go_recipient').val(),
				purchase_count: count
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
			var buy = jQuery('#golb-fr-buy');
			buy.attr('onclick','');
			if(response.indexOf("Need more") > -1){
				alert(response);
				buy.html('Error');	
			} else{
				buy.innerHTML = "";
				buy.html('');
				buy.append('<span>' + response + '</span>');
				// Whenever you figure out a better way to do this, implement it. 
				jQuery('#go_admin_bar_progress_bar').css({"background-color":color});
			}
			go_count_item(id);
		}
	});
});
}

function go_count_item(id){
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		type: "POST",
		data:{
			action: 'purchase_count',
			the_item_id: id
		},
		success: function(data){
			var count = data.toString();
			jQuery('#golb-purchased').html("Times purchased: " + count);
		}
	});	
}