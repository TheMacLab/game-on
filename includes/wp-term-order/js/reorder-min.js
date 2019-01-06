//https://stackoverflow.com/questions/918792/use-jquery-to-change-an-html-tag
//extend jquery to replace tags.  Used to turn term table into list.
/**
 * Enable the checkall box.
 * Changing table to list breaks it.
 * This reenables the onclick event.
 */
function toggle(e){for(var r=jQuery("input[name='delete_tags[]']"),t=0,i=r.length;t<i;t++)r[t].checked=e.checked;var a=e.checked;jQuery("#cb-select-all-1").prop("checked",a),jQuery("#cb-select-all-2").prop("checked",a)}jQuery.extend({replaceTag:function(e,r,t){var i=jQuery(e),a,n=jQuery(r).clone();
// return node; (Error spotted by Frank van Luijn)
return t&&(//{{{
newTag=n[0],newTag.className=e.className,newTag.id=jQuery(e).attr("id"),
//console.log(curtentAtts);
jQuery.extend(newTag.classList,e.classList),jQuery.extend(newTag.attributes,e.attributes)),//}}}
//console.log($currentElem);
i.wrapAll(n),i.contents().unwrap(),this;// Suggested by ColeLawrence
}}),jQuery.fn.extend({replaceTag:function(e,r){
// "return" suggested by ColeLawrence
return this.each(function(){jQuery.replaceTag(this,e,r)})}}),jQuery("#cb-select-all-1").click(function(){toggle(this)}),jQuery("#cb-select-all-2").click(function(){toggle(this)}),jQuery(document).ready(function(){var a=[];
//make array of all the term ids
jQuery("#the-list").find("tr").each(function(){a.push(this.id)}),termDivIDs=a,
//alert (termDivIDs);
jQuery.ajax({type:"POST",
//dataType: 'json',
url:ajax_url,data:{action:"check_if_top_term",goTermDivIDs:termDivIDs},success:function(e){
/**
		 * Update the term order based on the ajax response
		 *
		 * @param {type} response
		 * @returns {void}
		 */
function c(e){
//window.location.reload();
/*if ( 'children' === response ) {
				window.location.reload();
				return;
			}
			*/
//alert(response);
var r=jQuery.parseJSON(e),t=r.new_pos;for(var i in t)if("next"!==i){var a=document.getElementById("inline_"+i);if(null!==a&&t.hasOwnProperty(i)){var n=a.querySelector(".order");if(void 0!==t[i].order){null!==n&&(n.innerHTML=t[i].order);var l=a.querySelector(".parent");null!==l&&(l.innerHTML=t[i].parent);var s=null,d=a.querySelector(".row-title");null!==d&&(s=d.innerHTML);for(var o=0;o<t[i].depth;)
//term_title = '&mdash; ' + term_title;
o++;var u=a.parentNode.querySelector(".row-title")}else null!==n&&(n.innerHTML=t[i])}}r.next?jQuery.post(ajaxurl,{action:"reordering_terms",id:r.next.id,previd:r.next.previd,nextid:r.next.nextid,start:r.next.start,excluded:r.next.excluded,tax:y},c):(setTimeout(function(){jQuery(".to-row-updating").removeClass("to-row-updating")},500),h.removeClass("to-updating").sortable("enable"))}for(var r=jQuery.parseJSON(e),t=0;t<a.length;t++){var e=r[t],i="#"+a[t];jQuery(i).addClass(e)}
////////Change table to List
jQuery("#the-list").find(".parent").each(function(e){jQuery(this).wrapInner('<div class="container parent"></div>');var r=jQuery(this).attr("id");jQuery(this).nextUntil(".parent").andSelf().wrapAll("<li id="+r+' class="sortset"></li>');var r=jQuery(this).attr("id");jQuery(this).nextUntil(".parent").wrapAll("<ul id="+r+' class="children ulSortable"></ul>'),jQuery(this).contents().unwrap()}),jQuery("#the-list").find(".child").each(function(e){jQuery(this).wrapInner('<div class="container child"></div>')}),jQuery("tbody#the-list").replaceTag("<ul>",!0),jQuery("#the-list").addClass("ulSortable",!0),jQuery("#the-list").find("tr").replaceTag("<li>",!0),jQuery("#the-list").find("td").replaceTag("<div>",!0),jQuery(".container").prepend('<div class="handleLeft"><i class="fa fa-arrows-v fa-1x" aria-hidden="true"></i></div>'),jQuery(".container.parent").append('<div class="handleRight"><i class="fa fa-chevron-up fa-1x" aria-hidden="true"></i></i></div>'),jQuery(".container.child").append('<div class="handleRight_nograb"></div>'),
//Edit Inline (Quick Edit)
//this doesn't work yet
/*
		jQuery( '.editinline' ).on( 'click', function() {
			var tag_id = jQuery( this ).parents( 'li' ).attr( 'id' ),
			order  = jQuery( 'li.order', '#' + tag_id ).text();
			alert (tag_id);
			alert (order);
			order = 2;
			console.log(order);
			jQuery( ':input[name="order"]', '.inline-edit-row' ).val( order );
		 } );
		 */
/*
		//Event Listener on mouseover show actions
		jQuery( "#the-list .column-name" ).mouseover(function() {
			jQuery(this).find('.row-actions').css( "left", "0px" );
		});


		jQuery("#the-list .column-name").mouseout(function() {
			jQuery(this).find('.row-actions').css( "left", "-99999em" );
			//hideChildren ();
		});
		*/
jQuery(".handleRight").mousedown(function(){jQuery(".child.ui-sortable-handle").css("display","none"),jQuery("body").mouseup(function(){jQuery(".child.ui-sortable-handle").css("display","block")})});
//////////////////////////Begin Sortable List
var h=jQuery(".ulSortable"),y=jQuery('form input[name="taxonomy"]').val();h.sortable({
// Settings
items:"> li",cursor:"move",axis:"y",cancel:"  .inline-edit-row",distance:2,opacity:.9,tolerance:"pointer",scroll:!0,nested:"ul",
//tolerance: 'intersect',
containment:"parent",forceHelperSize:!0,forcePlaceholderSize:!0,cursorAt:{top:25,left:15},
/**
			 * Sort start
			 *
			 * @param {event} e
			 * @param {element} ui
			 * @returns {void}
			 */
start:function(e,r){
//sortable_terms_table.sortable( "refreshPositions" );
//sortable_terms_table.sortable('refresh');
"undefined"!=typeof inlineEditTax&&inlineEditTax.revert(),r.placeholder.height(r.item.height()),r.item.parent().parent().addClass("dragging")},
/**
			 * Sort dragging
			 *
			 * @param {event} e
			 * @param {element} ui
			 * @returns {void}
			 */
helper:function(e,r){
//sortable_terms_table.sortable( "refreshPositions" );
//sortable_terms_table.sortable('refresh');
return r.children().each(function(){jQuery(this).width(jQuery(this).width())}),r},
/**
			 * Sort dragging stopped
			 *
			 * @param {event} e
			 * @param {element} ui
			 * @returns {void}
			 */
/*
			stop: function ( e, ui ) {
				ui.item.children( '.row-actions' ).show();
				ui.item.parent().parent().removeClass( 'dragging' );
				//jQuery(".children").show();
				//window.location.reload();
			},
			*/
/**
			 * Update the data in the database based on UI changes
			 *
			 * @param {event} e
			 * @param {element} ui
			 * @returns {void}
			 */
update:function(e,r){h.sortable("disable").addClass("to-updating"),r.item.addClass("to-row-updating");var t=4,i=r.item[0].id.substr(4),a=!1,n=r.item.prev();0<n.length&&(a=n.attr("id").substr(4));var l=!1,s=r.item.next();0<s.length&&(l=s.attr("id").substr(4)),
// Go do the sorting stuff via ajax
jQuery.post(ajaxurl,{action:"reordering_terms",id:i,previd:a,nextid:l,tax:y},c)}
/////////////end of sortable function
}),jQuery("thead li").each(function(e){jQuery(this).wrapInner('<div class="container term_list_header"></div>')}),jQuery("tfoot li").each(function(e){jQuery(this).wrapInner('<div class="container term_list_footer"></div>')}),jQuery("thead").wrapInner('<div class="the-list-header"></div>'),jQuery("thead").contents().unwrap(),jQuery("th").replaceTag("<div>",!0),jQuery("tfoot").wrapInner('<div class="the-list-footer"></ul>'),jQuery("tfoot").contents().unwrap(),jQuery(".the-list-footer tr").prepend('<div class="handleLeft"></div>'),jQuery(".the-list-header tr").prepend('<div class="handleLeft"></div>'),jQuery(".the-list-footer tr").append('<div class="handleRight_nograb"></div>'),jQuery(".the-list-header tr").append('<div class="handleRight_nograb"></div>'),jQuery(".the-list-header tr").replaceTag("<div class=container>",!0),jQuery(".the-list-header td").replaceTag("<div>",!0),jQuery(".the-list-footer tr").replaceTag("<div class=container>",!0),jQuery(".the-list-footer td").replaceTag("<div>",!0),jQuery("#col-right").css("display","block"),
///if submit button is pressed, reload content and redo list from table
jQuery("#submit").click(function(){jQuery("#col-right").css("display","none"),
//delay here??
jQuery("#col-right").bind("DOMSubtreeModified",function(){console.log("modified and reload"),location.reload(!0)}),1==jQuery(".term-name-wrap").hasClass("form-invalid")&&(jQuery("#col-right").unbind("DOMSubtreeModified"),jQuery("#col-right").css("display","block")),1==jQuery(".formfield").hasClass("form-invalid")&&jQuery(".formfield").css("display","block")})},error:function(e){console.log(e),console.log("fail")}
//end ajax and end on ready
})});