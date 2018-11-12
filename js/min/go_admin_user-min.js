function go_toggle(e){checkboxes=jQuery(".go_checkbox");for(var t=0,a=checkboxes.length;t<a;t++)checkboxes[t].checked=e.checked}function go_clipboard_change_filter(){var e=jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls");console.log(e),"clipboard_wrap"==e?(console.log("1"),
//Clipboard.draw();
//jQuery("#clipboard_stats_datatable_container").html("");
jQuery("#clipboard_store_datatable_container").html(""),jQuery("#clipboard_messages_datatable_container").html("")):"clipboard_store_wrap"==e?(console.log("2"),
//Store.draw();
//jQuery("#clipboard_stats_datatable_container").html("");
//jQuery("#clipboard_store_datatable_container").html("");
jQuery("#clipboard_messages_datatable_container").html("")):"clipboard_messages_wrap"==e?(console.log("3"),
//Messages.draw();
//jQuery("#clipboard_stats_datatable_container").html("");
jQuery("#clipboard_store_datatable_container").html("")):"clipboard_activity_wrap"==e&&(console.log("4"),
//Activity.draw();
//jQuery("#clipboard_stats_datatable_container").html("");
jQuery("#clipboard_store_datatable_container").html(""),jQuery("#clipboard_messages_datatable_container").html(""));
//ajax to save the values
var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_save_filters,a=jQuery("#go_clipboard_user_go_sections_select").val(),o=jQuery("#go_clipboard_user_go_groups_select").val(),r=jQuery("#go_clipboard_go_badges_select").val();
//alert (section);
//console.log(jQuery( '#go_clipboard_user_go_sections_select' ).val());
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_save_filters",section:a,badge:r,group:o},success:function(e){
//console.log("values saved");
}})}function go_filter_clipboard_datatables(e){//function that filters all tables on draw
jQuery.fn.dataTable.ext.search.push(function(e,t,a){var o=e.sTableId,r=jQuery("#go_clipboard_user_go_sections_select").val(),s=jQuery("#go_clipboard_user_go_groups_select").val(),n=jQuery("#go_clipboard_go_badges_select").val(),i=t[4],l=t[3],c=t[2];
//console.log("mytable" + mytable);
//if (mytable == "go_clipboard_stats_datatable" || mytable == "go_clipboard_messages_datatable" || mytable == "go_clipboard_activity_datatable") {
// use data for the filter by column
//console.log("data" + data);
//console.log("badges" + badges);
//console.log("groups" + groups);
//console.log("sections" + sections);
//console.log(sections);
l=JSON.parse(l),
//console.log("groups" + groups);
//sections = JSON.parse(sections);
i=JSON.parse(i);
//console.log("badges" + badges);
//console.log("sections" + sections);
var d=!0;return(d="none"==s||-1!=jQuery.inArray(s,l))&&(d="none"==r||c==r),"go_clipboard_datatable"==o&&d&&(d="none"==n||-1!=jQuery.inArray(n,i)),d;
//}
//else{
//   return true;
// }
})}function go_toggle_off(){checkboxes=jQuery(".go_checkbox");for(var e=0,t=checkboxes.length;e<t;e++)checkboxes[e].checked=!1}function go_clipboard_stats_datatable(e){
//var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
if(
//hide date filter
jQuery("#go_timestamp_filters").hide(),0==jQuery("#go_clipboard_stats_datatable").length||1==e){jQuery("#clipboard_stats_datatable_container").html("<h2>Loading . . .</h2>");var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_stats;
//console.log("refresh" + refresh);
//console.log("stats");
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_stats",date:jQuery(".datepicker").val(),refresh:e},success:function(e){
//console.log("success");
if(-1!==e){jQuery("#clipboard_stats_datatable_container").html(e);var t=jQuery("#go_clipboard_stats_datatable").DataTable({deferRender:!0,bPaginate:!0,
//colReorder: true,
order:[[5,"asc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateDuration:31557600,
//"destroy": true,
dom:"lBfrtip",drawCallback:function(e){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"1px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[4],visible:!1,className:"noVis"},{targets:[7],className:"noVis"},{targets:[8],className:"noVis"},{targets:[10],className:"noVis",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(e,t,a,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
//search on enter only
jQuery("div.dataTables_filter input").unbind(),jQuery("div.dataTables_filter input").keyup(function(e){13==e.keyCode&&t.search(this.value).draw()}),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){var e;"clipboard_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&t.draw(),go_clipboard_change_filter()}),jQuery(".go_update_clipboard").one("click",function(){var e;"clipboard_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&go_clipboard_stats_datatable(!0),go_clipboard_change_filter()}),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc(),
//the filter for client side
go_filter_clipboard_datatables("go_clipboard_stats_datatable"),t.draw()}}})}}function go_clipboard_store_datatable(e){if(
//show date filter
jQuery("#go_timestamp_filters").show(),0==jQuery("#go_clipboard_store_datatable").length||1==e){jQuery("#clipboard_store_datatable_container").html("<h2>Loading . . .</h2>");var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_store;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_store"},success:function(e){
//console.log("success");
if(-1!==e){jQuery("#clipboard_store_datatable_container").html(e);
//go_filter_datatables();
var t=jQuery("#go_clipboard_store_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_store_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
e.date=jQuery(".datepicker").val(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val()}},bPaginate:!0,
//colReorder: true,
order:[[8,"desc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateDuration:31557600,searchDelay:1e3,dom:"lBfrtip",drawCallback:function(e){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all",sortable:!1},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(e,t,a,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});jQuery("div.dataTables_filter input").unbind(),jQuery("div.dataTables_filter input").keyup(function(e){13==e.keyCode&&t.search(this.value).draw()}),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, .datepicker").change(function(){var e;"clipboard_store_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&t.draw(),go_clipboard_change_filter()}),jQuery(".go_update_clipboard").one("click",function(){var e;"clipboard_store_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&go_clipboard_store_datatable(!0),go_clipboard_change_filter()})}}})}}function go_clipboard_messages_datatable(e){if(
//show date filter
jQuery("#go_timestamp_filters").show(),0==jQuery("#go_clipboard_messages_datatable").length||1==e){jQuery("#clipboard_messages_datatable_container").html("<h2>Loading . . .</h2>");var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_messages"},success:function(e){
//console.log("success");
if(-1!==e){jQuery("#clipboard_messages_datatable_container").html(e);
//go_filter_datatables();
var t=jQuery("#go_clipboard_messages_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_messages_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
e.date=jQuery(".datepicker").val(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val()}},bPaginate:!0,
//colReorder: true,
order:[[8,"desc"]],responsive:!0,autoWidth:!1,searchDelay:1e3,stateSave:!0,stateDuration:31557600,dom:"lBfrtip",drawCallback:function(e){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all",sortable:!1},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(e,t,a,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
//search only on enter key
jQuery("div.dataTables_filter input").unbind(),jQuery("div.dataTables_filter input").keyup(function(e){13==e.keyCode&&t.search(this.value).draw()}),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, .datepicker").change(function(){var e;"clipboard_messages_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&t.draw(),go_clipboard_change_filter()}),jQuery(".go_update_clipboard").one("click",function(){var e;"clipboard_messages_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&(console.log("draw"),go_clipboard_messages_datatable(!0)),go_clipboard_change_filter()})}}})}}function go_clipboard_activity_datatable(e){if(
//show date filter
jQuery("#go_timestamp_filters").show(),0==jQuery("#go_clipboard_activity_datatable").length||1==e){jQuery("#clipboard_activity_datatable_container").html("<h2>Loading . . .</h2>");var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_activity;
//console.log(date);
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_activity",date:jQuery(".datepicker").val()},success:function(e){
//console.log("success");
if(-1!==e){jQuery("#clipboard_activity_datatable_container").html(e);
//go_filter_datatables();
var t=jQuery("#go_clipboard_activity_datatable").DataTable({deferRender:!0,bPaginate:!0,
//colReorder: true,
order:[[4,"asc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateDuration:31557600,dom:"lBfrtip",drawCallback:function(e){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(e){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[4],visible:!1,className:"noVis"},{targets:[7],className:"noVis"},{targets:[8],className:"noVis"},{targets:[10],className:"noVis",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(e,t,a,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
// Add event listener for opening and closing more actions
jQuery("#go_clipboard_activity_datatable .show_more").click(function(){var e;
//console.log(hidden);
0==jQuery(this).hasClass("shown")?(jQuery(this).addClass("shown"),jQuery(this).siblings(".hidden_action").show(),jQuery(this).find(".hide_more_actions").show(),jQuery(this).find(".show_more_actions").hide()):(jQuery(this).removeClass("shown"),jQuery(this).siblings(".hidden_action").hide(),jQuery(this).find(".hide_more_actions").hide(),jQuery(this).find(".show_more_actions").show())}),
//search on enter only
jQuery("div.dataTables_filter input").unbind(),jQuery("div.dataTables_filter input").keyup(function(e){13==e.keyCode&&t.search(this.value).draw()}),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){var e;"clipboard_activity_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&t.draw(),go_clipboard_change_filter()}),jQuery(".go_update_clipboard").one("click",function(){var e;"clipboard_activity_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&(console.log("11"),go_clipboard_activity_datatable(!0)),go_clipboard_change_filter()}),jQuery(".datepicker").change(function(){var e;"clipboard_activity_wrap"==jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls")&&(console.log("22"),go_clipboard_activity_datatable(!0)),go_clipboard_change_filter()})}}})}}
// written by Dean Edwards, 2005
// with input from Tino Zijdel, Matthias Miller, Diego Perini
// http://dean.edwards.name/weblog/2005/10/add-event/
function dean_addEvent(e,t,a){if(e.addEventListener)e.addEventListener(t,a,!1);else{
// assign each event handler a unique ID
a.$$guid||(a.$$guid=dean_addEvent.guid++),
// create a hash table of event types for the element
e.events||(e.events={});
// create a hash table of event handlers for each element/event pair
var o=e.events[t];o||(o=e.events[t]={},
// store the existing event handler (if there is one)
e["on"+t]&&(o[0]=e["on"+t])),
// store the event handler in the hash table
o[a.$$guid]=a,
// assign a global event handler to do all the work
e["on"+t]=handleEvent}}function removeEvent(e,t,a){e.removeEventListener?e.removeEventListener(t,a,!1):
// delete the event handler from the hash table
e.events&&e.events[t]&&delete e.events[t][a.$$guid]}function handleEvent(e){var t=!0;
// grab the event object (IE uses a global event object)
e=e||fixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);
// get a reference to the hash table of event handlers
var a=this.events[e.type];
// execute each event handler
for(var o in a)this.$$handleEvent=a[o],!1===this.$$handleEvent(e)&&(t=!1);return t}function fixEvent(e){
// add W3C standard event methods
return e.preventDefault=fixEvent.preventDefault,e.stopPropagation=fixEvent.stopPropagation,e}
/*
on the create new taxonomy term page,
this hides the acf stuff until a parent map is selected
 */
function go_hide_child_tax_acfs(){-1==jQuery(".taxonomy-task_chains #parent, .taxonomy-go_badges #parent").val()?(
//jQuery('#acf-term-fields').hide();
//jQuery('.acf-field').hide();
jQuery(".go_child_term").hide(),jQuery("#go_map_shortcode_id").show()):(jQuery(".go_child_term").show(),
//jQuery('#acf-term-fields').show();
//jQuery('.acf-field').show();
//jQuery('h2').show();
jQuery("#go_map_shortcode_id").hide());var e=jQuery('[name="tag_ID"]').val();null==e&&jQuery("#go_map_shortcode_id").hide();
//store item shortcode--add item id to bottom
var t=jQuery("#post_ID").val();jQuery("#go_store_item_id .acf-input").html('[go_store id="'+t+'"]');
//map shortcode message
//var map_id = jQuery('[name="tag_ID"]').val();
//console.log(map_id);
var a=jQuery("#name").val();jQuery("#go_map_shortcode_id .acf-input").html('Place this code in a content area to link directly to this map.<br><br>[go_single_map_link map_id="'+e+'"]'+a+"[/go_single_map_link]"),null==e&&jQuery("#go_map_shortcode_id").hide()}function set_height_mce(){jQuery(".go_call_to_action .mce-edit-area iframe").height(100)}function go_validate_growth(){var e=jQuery("#go_levels_growth").find("input").val();isNaN(e)?jQuery("#go_levels_growth").find("input").val(Go_orgGrowth):Go_orgGrowth=e}function go_level_names(){var e=document.getElementById("go_levels_repeater").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length,t,a,o;t=0,a="",jQuery(".go_levels_repeater_names").find("input").each(function(){t++,o=a,a=jQuery(this).val(),
//console.log (thisName);
//console.log (prevName);
1<t&&t!=e&&(console.log("Row:"+t),null!=a&&""!=a||(console.log("empty:"+t),console.log(a),jQuery(this).val(o),a=o))})}function go_levels_limit_each(){var o=document.getElementById("go_levels_repeater").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length,r=Go_orgGrowth,s;
//var growth = jQuery('#go_levels_growth').find('input').val();
s=0,jQuery(".go_levels_repeater_numbers").find("input").each(function(){
//console.log('-----------row'+ row);
var e;s++,e=jQuery(this).val()||0,e=parseInt(e);var t=jQuery(this).closest(".acf-row").prev().find(".go_levels_repeater_numbers").find("input").val()||0;t=parseInt(t);var a=jQuery(this).closest(".acf-row").next().find(".go_levels_repeater_numbers").find("input").val()||0;a=parseInt(a),
//console.log('prev' + prevVal);
//console.log('this' + thisVal);
//console.log('next' + nextVal);
1===s?(//the first row
jQuery(this).attr({max:0,// substitute your own
min:0}),jQuery(this).val(0)):s===o-1?(//the last row
jQuery(this).attr({min:t}),jQuery(this).removeAttr("max"),e<t&&jQuery(this).val(Math.floor(t*r))):s===o||(//all the rows in teh middle
e<a&&jQuery(this).attr({min:t,max:a}),a<e&&jQuery(this).attr({min:t}),e<t&&jQuery(this).val(t*r)
/*
            else if (thisVal > nextVal && nextVal != 0) {

                jQuery(this).val(nextVal);
                //console.log('Middle Row: Value to high.  Set to ' + nextVal);
            }
            else {
                //console.log('middle Value:' + thisVal);
            }
            */)})}jQuery(document).ready(function(){jQuery("#records_tabs").length&&(jQuery("#records_tabs").tabs(),jQuery(".clipboard_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){case"clipboard":console.log("stats1"),go_clipboard_stats_datatable(!1),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc();break;case"store":
//console.log("messages");
go_clipboard_store_datatable(),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_store_datatable").DataTable().columns.adjust().responsive.recalc();break;case"messages":
//console.log("messages");
go_clipboard_messages_datatable(),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_messages_datatable").DataTable().columns.adjust().responsive.recalc();break;case"activity":
//console.log("activity");
go_clipboard_activity_datatable(),jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc();break}})),jQuery("#records_tabs").length&&(go_clipboard_stats_datatable(!1),jQuery("#records_tabs").css("margin-left",""),jQuery(".datepicker").datepicker({firstDay:0}),jQuery(".datepicker").datepicker("setDate",new Date))});
/*
  SortTable
  version 2
  7th April 2007
  Stuart Langridge, http://www.kryogenix.org/code/browser/sorttable/

  Instructions:
  Download this file
  Add <script src="sorttable.js"></script> to your HTML
  Add class="sortable" to any table you'd like to make sortable
  Click on the headers to sort

  Thanks to many, many people for contributions and suggestions.
  Licenced as X11: http://www.kryogenix.org/code/browser/licence.html
  This basically means: do what you want with it.
*/
var stIsIE=/*@cc_on!@*/!1;
/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
    document.write("<script id=__ie_onload defer src=javascript:void(0)><\/script>");
    var script = document.getElementById("__ie_onload");
    script.onreadystatechange = function() {
        if (this.readyState == "complete") {
            sorttable.init(); // call the onload handler
        }
    };
/*@end @*/
/* for Safari */
if(sorttable={init:function(){
// quit if this function has already been called
arguments.callee.done||(
// flag this function so we don't do the same thing twice
arguments.callee.done=!0,
// kill the timer
_timer&&clearInterval(_timer),document.createElement&&document.getElementsByTagName&&(sorttable.DATE_RE=/^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/,forEach(document.getElementsByTagName("table"),function(e){-1!=e.className.search(/\bsortable\b/)&&sorttable.makeSortable(e)})))},makeSortable:function(e){if(0==e.getElementsByTagName("thead").length&&(
// table doesn't have a tHead. Since it should have, create one and
// put the first table row in it.
the=document.createElement("thead"),the.appendChild(e.rows[0]),e.insertBefore(the,e.firstChild)),
// Safari doesn't support table.tHead, sigh
null==e.tHead&&(e.tHead=e.getElementsByTagName("thead")[0]),1==e.tHead.rows.length){// can't cope with two header rows
// Sorttable v1 put rows with a class of "sortbottom" at the bottom (as
// "total" rows, for example). This is B&R, since what you're supposed
// to do is put them in a tfoot. So, if there are sortbottom rows,
// for backwards compatibility, move them to tfoot (creating it if needed).
sortbottomrows=[];for(var t=0;t<e.rows.length;t++)-1!=e.rows[t].className.search(/\bsortbottom\b/)&&(sortbottomrows[sortbottomrows.length]=e.rows[t]);if(sortbottomrows){null==e.tFoot&&(
// table doesn't have a tfoot. Create one.
tfo=document.createElement("tfoot"),e.appendChild(tfo));for(var t=0;t<sortbottomrows.length;t++)tfo.appendChild(sortbottomrows[t]);delete sortbottomrows}
// work through each column and calculate its type
headrow=e.tHead.rows[0].cells;for(var t=0;t<headrow.length;t++)
// manually override the type with a sorttable_type attribute
headrow[t].className.match(/\bsorttable_nosort\b/)||(// skip this col
mtch=headrow[t].className.match(/\bsorttable_([a-z0-9]+)\b/),mtch&&(override=mtch[1]),mtch&&"function"==typeof sorttable["sort_"+override]?headrow[t].sorttable_sortfunction=sorttable["sort_"+override]:headrow[t].sorttable_sortfunction=sorttable.guessType(e,t),
// make it clickable to sort
headrow[t].sorttable_columnindex=t,headrow[t].sorttable_tbody=e.tBodies[0],dean_addEvent(headrow[t],"click",sorttable.innerSortFunction=function(e){if(-1!=this.className.search(/\bsorttable_sorted\b/))
// if we're already sorted by this column, just
// reverse the table, which is quicker
return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted","sorttable_sorted_reverse"),this.removeChild(document.getElementById("sorttable_sortfwdind")),sortrevind=document.createElement("span"),sortrevind.id="sorttable_sortrevind",sortrevind.innerHTML=stIsIE?'&nbsp<font face="webdings">5</font>':"&nbsp;&#x25B4;",void this.appendChild(sortrevind);if(-1!=this.className.search(/\bsorttable_sorted_reverse\b/))
// if we're already sorted by this column in reverse, just
// re-reverse the table, which is quicker
return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted_reverse","sorttable_sorted"),this.removeChild(document.getElementById("sorttable_sortrevind")),sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",void this.appendChild(sortfwdind);
// remove sorttable_sorted classes
theadrow=this.parentNode,forEach(theadrow.childNodes,function(e){1==e.nodeType&&(// an element
e.className=e.className.replace("sorttable_sorted_reverse",""),e.className=e.className.replace("sorttable_sorted",""))}),sortfwdind=document.getElementById("sorttable_sortfwdind"),sortfwdind&&sortfwdind.parentNode.removeChild(sortfwdind),sortrevind=document.getElementById("sorttable_sortrevind"),sortrevind&&sortrevind.parentNode.removeChild(sortrevind),this.className+=" sorttable_sorted",sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",this.appendChild(sortfwdind),
// build an array to sort. This is a Schwartzian transform thing,
// i.e., we "decorate" each row with the actual sort key,
// sort based on the sort keys, and then put the rows back in order
// which is a lot faster because you only do getInnerText once per row
row_array=[],col=this.sorttable_columnindex,rows=this.sorttable_tbody.rows;for(var t=0;t<rows.length;t++)row_array[row_array.length]=[sorttable.getInnerText(rows[t].cells[col]),rows[t]];
/* If you want a stable sort, uncomment the following line */
//sorttable.shaker_sort(row_array, this.sorttable_sortfunction);
/* and comment out this one */row_array.sort(this.sorttable_sortfunction),tb=this.sorttable_tbody;for(var t=0;t<row_array.length;t++)tb.appendChild(row_array[t][1]);delete row_array}))}},guessType:function(e,t){
// guess the type of a column based on its first non-blank row
sortfn=sorttable.sort_alpha;for(var a=0;a<e.tBodies[0].rows.length;a++)if(text=sorttable.getInnerText(e.tBodies[0].rows[a].cells[t]),""!=text){if(text.match(/^-?[�$�]?[\d,.]+%?$/))return sorttable.sort_numeric;
// check for a date: dd/mm/yyyy or dd/mm/yy
// can have / or . or - as separator
// can be mm/dd as well
if(possdate=text.match(sorttable.DATE_RE),possdate){if(
// looks like a date
first=parseInt(possdate[1]),second=parseInt(possdate[2]),12<first)
// definitely dd/mm
return sorttable.sort_ddmm;if(12<second)return sorttable.sort_mmdd;
// looks like a date, but we can't tell which, so assume
// that it's dd/mm (English imperialism!) and keep looking
sortfn=sorttable.sort_ddmm}}return sortfn},getInnerText:function(e){
// gets the text we want to use for sorting for a cell.
// strips leading and trailing whitespace.
// this is *not* a generic getInnerText function; it's special to sorttable.
// for example, you can override the cell text with a customkey attribute.
// it also gets .value for <input> fields.
if(!e)return"";if(hasInputs="function"==typeof e.getElementsByTagName&&e.getElementsByTagName("input").length,null!=e.getAttribute("sorttable_customkey"))return e.getAttribute("sorttable_customkey");if(void 0!==e.textContent&&!hasInputs)return e.textContent.replace(/^\s+|\s+$/g,"");if(void 0!==e.innerText&&!hasInputs)return e.innerText.replace(/^\s+|\s+$/g,"");if(void 0!==e.text&&!hasInputs)return e.text.replace(/^\s+|\s+$/g,"");switch(e.nodeType){case 3:if("input"==e.nodeName.toLowerCase())return e.value.replace(/^\s+|\s+$/g,"");case 4:return e.nodeValue.replace(/^\s+|\s+$/g,"");break;case 1:case 11:for(var t="",a=0;a<e.childNodes.length;a++)t+=sorttable.getInnerText(e.childNodes[a]);return t.replace(/^\s+|\s+$/g,"");break;default:return""}},reverse:function(e){
// reverse the rows in a tbody
newrows=[];for(var t=0;t<e.rows.length;t++)newrows[newrows.length]=e.rows[t];for(var t=newrows.length-1;0<=t;t--)e.appendChild(newrows[t]);delete newrows},
/* sort functions
     each sort function takes two parameters, a and b
     you are comparing a[0] and b[0] */
sort_numeric:function(e,t){return aa=parseFloat(e[0].replace(/[^0-9.-]/g,"")),isNaN(aa)&&(aa=0),bb=parseFloat(t[0].replace(/[^0-9.-]/g,"")),isNaN(bb)&&(bb=0),aa-bb},sort_alpha:function(e,t){return e[0]==t[0]?0:e[0]<t[0]?-1:1},sort_ddmm:function(e,t){return mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},sort_mmdd:function(e,t){return mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},shaker_sort:function(e,t){for(
// A stable sort function to allow multi-level sorting of data
// see: http://en.wikipedia.org/wiki/Cocktail_sort
// thanks to Joseph Nahmias
var a=0,o=e.length-1,r=!0;r;){r=!1;for(var s=a;s<o;++s)if(0<t(e[s],e[s+1])){var n=e[s];e[s]=e[s+1],e[s+1]=n,r=!0}// for
if(o--,!r)break;for(var s=o;a<s;--s)if(t(e[s],e[s-1])<0){var n=e[s];e[s]=e[s-1],e[s-1]=n,r=!0}// for
a++}// while(swap)
}},
/* ******************************************************************
   Supporting functions: bundled here to avoid depending on a library
   ****************************************************************** */
// Dean Edwards/Matthias Miller/John Resig
/* for Mozilla/Opera9 */
document.addEventListener&&document.addEventListener("DOMContentLoaded",sorttable.init,!1),/WebKit/i.test(navigator.userAgent))// sniff
var _timer=setInterval(function(){/loaded|complete/.test(document.readyState)&&sorttable.init()},10);
/* for other browsers */window.onload=sorttable.init,
// a counter used to create unique IDs
dean_addEvent.guid=1,fixEvent.preventDefault=function(){this.returnValue=!1},fixEvent.stopPropagation=function(){this.cancelBubble=!0}
// Dean's forEach: http://dean.edwards.name/base/forEach.js
/*
	forEach, version 1.0
	Copyright 2006, Dean Edwards
	License: http://www.opensource.org/licenses/mit-license.php
*/
// array-like enumeration
,Array.forEach||(// mozilla already supports this
Array.forEach=function(e,t,a){for(var o=0;o<e.length;o++)t.call(a,e[o],o,e)}),
// generic enumeration
Function.prototype.forEach=function(e,t,a){for(var o in e)void 0===this.prototype[o]&&t.call(a,e[o],o,e)},
// character enumeration
String.forEach=function(a,o,r){Array.forEach(a.split(""),function(e,t){o.call(r,e,t,a)})};
// globally resolve forEach enumeration
var forEach=function(e,t,a){if(e){var o=Object;// default
if(e instanceof Function)
// functions have a "length" property
o=Function;else{if(e.forEach instanceof Function)
// the object implements a custom forEach method so use that
return void e.forEach(t,a);"string"==typeof e?
// the object is a string
o=String:"number"==typeof e.length&&(
// the object is array-like
o=Array)}o.forEach(e,t,a)}};
/*
 * go_tasks_admin.js
 *
 * Where all the functionality for the task edit page goes.
 *
 * @see go_generate_accordion_array() below, it maps all the functions to their appropriate
 *      settings/accordions.
 */
/*
 * Disable sorting of metaboxes

jQuery(document).ready( function($) {
    $('.meta-box-sortables').sortable({
        disabled: true
    });

    $('.postbox .hndle').css('cursor', 'pointer');
});


 */
jQuery(document).ready(function(){go_hide_child_tax_acfs(),jQuery(".taxonomy-task_chains #parent, .taxonomy-go_badges #parent").change(function(){go_hide_child_tax_acfs()}),setTimeout(set_height_mce,1e3)}),
/**
 * This next section makes sure the levels on the options page proceed in ascending order.
 */
//get it set up on page load
jQuery(document).ready(function(){
//get the growth level from options
//var growth = levelGrowth*1;
if("undefined"!=typeof go_is_options_page)var e=go_is_options_page.is_options_page;e&&(
//console.log(is_options_page);
Go_orgGrowth=jQuery("#go_levels_growth").find("input").val(),
//run the limit function once on load
go_levels_limit_each(),
//attach function each input field
jQuery(".go_levels_repeater_numbers").find("input").change(go_levels_limit_each),jQuery(".go_levels_repeater_names").find("input").change(go_level_names),jQuery(".go_levels_repeater_names").find("input").change(go_level_names),jQuery("#go_levels_growth").find("input").change(go_validate_growth),acf.add_action("append",function(e){//run limit function when new row is added and attach it to the input in the new field
// $el will be equivalent to the new element being appended $('tr.row')
//limit to the levels table
if(jQuery(e).closest("#go_levels_repeater").length){var t=e.find("input").first();// find the first input field
jQuery(t).change(go_levels_limit_each);//bind to input on change
var a=e.find("input").last();// find the first input field
jQuery(a).change(go_level_names),
//console.log('-----------------row added------------------------');
go_levels_limit_each(),//run one time
go_level_names()}}),jQuery(".more_info_accordian").accordion({collapsible:!0,header:"h3",active:!1}))}),jQuery(document).ready(function(){if("undefined"!=typeof GO_EDIT_STORE_DATA)var e=GO_EDIT_STORE_DATA.is_store_edit;if(e){var t,a,o="<a id="+GO_EDIT_STORE_DATA.postid+" class='go_str_item ab-item' >View "+GO_EDIT_STORE_DATA.store_name+" Item</a>";
//console.log(link);
jQuery("#wp-admin-bar-view").html(o)}});