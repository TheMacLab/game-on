function go_toggle(t){checkboxes=jQuery(".go_checkbox");for(var e=0,o=checkboxes.length;e<o;e++)checkboxes[e].checked=t.checked}function go_toggle_off(){checkboxes=jQuery(".go_checkbox");for(var t=0,e=checkboxes.length;t<e;t++)checkboxes[t].checked=!1}function go_clipboard_class_a_choice(){if(
//var nonce = GO_CLIPBOARD_DATA.nonces.go_clipboard_intable;
go_filter_datatables(),jQuery("#go_clipboard_stats_datatable").length){
//XP////////////////////////////
//go_sort_leaders("go_clipboard", 4);
var s=jQuery("#go_clipboard_stats_datatable").DataTable({
//stateSave: false,
bPaginate:!1,
//colReorder: true,
order:[[5,"asc"]],responsive:!0,autoWidth:!1,stateSave:!0,
//"destroy": true,
dom:"Bfrtip",drawCallback:function(t){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(t){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"1px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[4],visible:!1,className:"noVis"},{targets:[7],className:"noVis"},{targets:[8],className:"noVis"},{targets:[10],className:"noVis",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(t,e,o,a){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
//on change filter listener
//console.log("change5");
jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){
//console.log("change");
s.draw();
//ajax function to save the values
var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_save_filters,e=jQuery("#go_clipboard_user_go_sections_select").val(),o=jQuery("#go_clipboard_user_go_groups_select").val(),a=jQuery("#go_clipboard_go_badges_select").val();
//alert (section);
//console.log(jQuery( '#go_clipboard_user_go_sections_select' ).val());
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_save_filters",section:e,badge:a,group:o},success:function(t){
//console.log("values saved");
}})}),jQuery("#records_tabs").css("margin-left","")}
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc()}function go_clipboard_class_a_choice_activity(t){if(0==jQuery("#go_clipboard_activity_datatable").length||1==t){var e=GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_activity,o=jQuery(".datepicker").val();
//console.log(date);
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_clipboard_intable_activity",go_clipboard_class_a_choice_activity:jQuery("#go_clipboard_class_a_choice_activity").val(),date:jQuery(".datepicker").val()},success:function(t){
//console.log("success");
if(-1!==t){jQuery("#clipboard_activity_datatable_container").html(t);
//go_filter_datatables();
var e=jQuery("#go_clipboard_activity_datatable").DataTable({
//stateSave: false,
bPaginate:!1,
//colReorder: true,
order:[[4,"asc"]],responsive:!0,autoWidth:!1,stateSave:!0,
//"destroy": true,
dom:"Bfrtip",drawCallback:function(t){jQuery(".go_messages_icon").prop("onclick",null).off("click"),jQuery(".go_messages_icon").one("click",function(t){go_messages_opener()}),go_stats_links()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[4],visible:!1,className:"noVis"},{targets:[7],className:"noVis"},{targets:[8],className:"noVis"},{targets:[10],className:"noVis",sortable:!1}],buttons:[{text:'<span class="go_messages_icon">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span></span>',action:function(t,e,o,a){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]});
//show date filter
jQuery("#go_timestamp_filters").show(),
//on change filter listener
//console.log("change5");
jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){
//console.log("change");
e.draw()}),
// Add event listener for opening and closing more actions
jQuery("#go_clipboard_activity_datatable .show_more").click(function(){var t;
//console.log(hidden);
0==jQuery(this).hasClass("shown")?(jQuery(this).addClass("shown"),jQuery(this).siblings(".hidden_action").show(),jQuery(this).find(".hide_more_actions").show(),jQuery(this).find(".show_more_actions").hide()):(jQuery(this).removeClass("shown"),jQuery(this).siblings(".hidden_action").hide(),jQuery(this).find(".hide_more_actions").hide(),jQuery(this).find(".show_more_actions").show())})}}})}}
// written by Dean Edwards, 2005
// with input from Tino Zijdel, Matthias Miller, Diego Perini
// http://dean.edwards.name/weblog/2005/10/add-event/
function dean_addEvent(t,e,o){if(t.addEventListener)t.addEventListener(e,o,!1);else{
// assign each event handler a unique ID
o.$$guid||(o.$$guid=dean_addEvent.guid++),
// create a hash table of event types for the element
t.events||(t.events={});
// create a hash table of event handlers for each element/event pair
var a=t.events[e];a||(a=t.events[e]={},
// store the existing event handler (if there is one)
t["on"+e]&&(a[0]=t["on"+e])),
// store the event handler in the hash table
a[o.$$guid]=o,
// assign a global event handler to do all the work
t["on"+e]=handleEvent}}function removeEvent(t,e,o){t.removeEventListener?t.removeEventListener(e,o,!1):
// delete the event handler from the hash table
t.events&&t.events[e]&&delete t.events[e][o.$$guid]}function handleEvent(t){var e=!0;
// grab the event object (IE uses a global event object)
t=t||fixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);
// get a reference to the hash table of event handlers
var o=this.events[t.type];
// execute each event handler
for(var a in o)this.$$handleEvent=o[a],!1===this.$$handleEvent(t)&&(e=!1);return e}function fixEvent(t){
// add W3C standard event methods
return t.preventDefault=fixEvent.preventDefault,t.stopPropagation=fixEvent.stopPropagation,t}jQuery(document).ready(function(){jQuery("#records_tabs").length&&(jQuery("#records_tabs").tabs(),jQuery(".clipboard_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){
/*
                case 'messages':
                    //console.log("messages");
                    go_clipboard_class_a_choice_messages();
                    break;
                    */
case"activity":
//console.log("activity");
go_clipboard_class_a_choice_activity(),jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc();break;case"clipboard":
//console.log("activity");
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc();break}})),jQuery("#go_clipboard_stats_datatable").length&&(go_clipboard_class_a_choice(),jQuery(".datepicker").datepicker({firstDay:0}),jQuery(".datepicker").datepicker("setDate",new Date),jQuery(".datepicker").change(function(){
//console.log("change");
jQuery("#go_clipboard_activity_datatable").html("<div id='loader' style='font-size: 1.5em; text-align: center; height: 200px'>loading . . .</div>"),go_clipboard_class_a_choice_activity(!0)}),jQuery(".go_datepicker_refresh").click(function(){jQuery("#go_clipboard_activity_datatable").html("<div id='loader' style='font-size: 1.5em; text-align: center; height: 200px'>loading . . .</div>"),go_clipboard_class_a_choice_activity(!0)}))});
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
_timer&&clearInterval(_timer),document.createElement&&document.getElementsByTagName&&(sorttable.DATE_RE=/^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/,forEach(document.getElementsByTagName("table"),function(t){-1!=t.className.search(/\bsortable\b/)&&sorttable.makeSortable(t)})))},makeSortable:function(t){if(0==t.getElementsByTagName("thead").length&&(
// table doesn't have a tHead. Since it should have, create one and
// put the first table row in it.
the=document.createElement("thead"),the.appendChild(t.rows[0]),t.insertBefore(the,t.firstChild)),
// Safari doesn't support table.tHead, sigh
null==t.tHead&&(t.tHead=t.getElementsByTagName("thead")[0]),1==t.tHead.rows.length){// can't cope with two header rows
// Sorttable v1 put rows with a class of "sortbottom" at the bottom (as
// "total" rows, for example). This is B&R, since what you're supposed
// to do is put them in a tfoot. So, if there are sortbottom rows,
// for backwards compatibility, move them to tfoot (creating it if needed).
sortbottomrows=[];for(var e=0;e<t.rows.length;e++)-1!=t.rows[e].className.search(/\bsortbottom\b/)&&(sortbottomrows[sortbottomrows.length]=t.rows[e]);if(sortbottomrows){null==t.tFoot&&(
// table doesn't have a tfoot. Create one.
tfo=document.createElement("tfoot"),t.appendChild(tfo));for(var e=0;e<sortbottomrows.length;e++)tfo.appendChild(sortbottomrows[e]);delete sortbottomrows}
// work through each column and calculate its type
headrow=t.tHead.rows[0].cells;for(var e=0;e<headrow.length;e++)
// manually override the type with a sorttable_type attribute
headrow[e].className.match(/\bsorttable_nosort\b/)||(// skip this col
mtch=headrow[e].className.match(/\bsorttable_([a-z0-9]+)\b/),mtch&&(override=mtch[1]),mtch&&"function"==typeof sorttable["sort_"+override]?headrow[e].sorttable_sortfunction=sorttable["sort_"+override]:headrow[e].sorttable_sortfunction=sorttable.guessType(t,e),
// make it clickable to sort
headrow[e].sorttable_columnindex=e,headrow[e].sorttable_tbody=t.tBodies[0],dean_addEvent(headrow[e],"click",sorttable.innerSortFunction=function(t){if(-1!=this.className.search(/\bsorttable_sorted\b/))
// if we're already sorted by this column, just
// reverse the table, which is quicker
return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted","sorttable_sorted_reverse"),this.removeChild(document.getElementById("sorttable_sortfwdind")),sortrevind=document.createElement("span"),sortrevind.id="sorttable_sortrevind",sortrevind.innerHTML=stIsIE?'&nbsp<font face="webdings">5</font>':"&nbsp;&#x25B4;",void this.appendChild(sortrevind);if(-1!=this.className.search(/\bsorttable_sorted_reverse\b/))
// if we're already sorted by this column in reverse, just
// re-reverse the table, which is quicker
return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted_reverse","sorttable_sorted"),this.removeChild(document.getElementById("sorttable_sortrevind")),sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",void this.appendChild(sortfwdind);
// remove sorttable_sorted classes
theadrow=this.parentNode,forEach(theadrow.childNodes,function(t){1==t.nodeType&&(// an element
t.className=t.className.replace("sorttable_sorted_reverse",""),t.className=t.className.replace("sorttable_sorted",""))}),sortfwdind=document.getElementById("sorttable_sortfwdind"),sortfwdind&&sortfwdind.parentNode.removeChild(sortfwdind),sortrevind=document.getElementById("sorttable_sortrevind"),sortrevind&&sortrevind.parentNode.removeChild(sortrevind),this.className+=" sorttable_sorted",sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",this.appendChild(sortfwdind),
// build an array to sort. This is a Schwartzian transform thing,
// i.e., we "decorate" each row with the actual sort key,
// sort based on the sort keys, and then put the rows back in order
// which is a lot faster because you only do getInnerText once per row
row_array=[],col=this.sorttable_columnindex,rows=this.sorttable_tbody.rows;for(var e=0;e<rows.length;e++)row_array[row_array.length]=[sorttable.getInnerText(rows[e].cells[col]),rows[e]];
/* If you want a stable sort, uncomment the following line */
//sorttable.shaker_sort(row_array, this.sorttable_sortfunction);
/* and comment out this one */row_array.sort(this.sorttable_sortfunction),tb=this.sorttable_tbody;for(var e=0;e<row_array.length;e++)tb.appendChild(row_array[e][1]);delete row_array}))}},guessType:function(t,e){
// guess the type of a column based on its first non-blank row
sortfn=sorttable.sort_alpha;for(var o=0;o<t.tBodies[0].rows.length;o++)if(text=sorttable.getInnerText(t.tBodies[0].rows[o].cells[e]),""!=text){if(text.match(/^-?[�$�]?[\d,.]+%?$/))return sorttable.sort_numeric;
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
sortfn=sorttable.sort_ddmm}}return sortfn},getInnerText:function(t){
// gets the text we want to use for sorting for a cell.
// strips leading and trailing whitespace.
// this is *not* a generic getInnerText function; it's special to sorttable.
// for example, you can override the cell text with a customkey attribute.
// it also gets .value for <input> fields.
if(!t)return"";if(hasInputs="function"==typeof t.getElementsByTagName&&t.getElementsByTagName("input").length,null!=t.getAttribute("sorttable_customkey"))return t.getAttribute("sorttable_customkey");if(void 0!==t.textContent&&!hasInputs)return t.textContent.replace(/^\s+|\s+$/g,"");if(void 0!==t.innerText&&!hasInputs)return t.innerText.replace(/^\s+|\s+$/g,"");if(void 0!==t.text&&!hasInputs)return t.text.replace(/^\s+|\s+$/g,"");switch(t.nodeType){case 3:if("input"==t.nodeName.toLowerCase())return t.value.replace(/^\s+|\s+$/g,"");case 4:return t.nodeValue.replace(/^\s+|\s+$/g,"");break;case 1:case 11:for(var e="",o=0;o<t.childNodes.length;o++)e+=sorttable.getInnerText(t.childNodes[o]);return e.replace(/^\s+|\s+$/g,"");break;default:return""}},reverse:function(t){
// reverse the rows in a tbody
newrows=[];for(var e=0;e<t.rows.length;e++)newrows[newrows.length]=t.rows[e];for(var e=newrows.length-1;0<=e;e--)t.appendChild(newrows[e]);delete newrows},
/* sort functions
     each sort function takes two parameters, a and b
     you are comparing a[0] and b[0] */
sort_numeric:function(t,e){return aa=parseFloat(t[0].replace(/[^0-9.-]/g,"")),isNaN(aa)&&(aa=0),bb=parseFloat(e[0].replace(/[^0-9.-]/g,"")),isNaN(bb)&&(bb=0),aa-bb},sort_alpha:function(t,e){return t[0]==e[0]?0:t[0]<e[0]?-1:1},sort_ddmm:function(t,e){return mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},sort_mmdd:function(t,e){return mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},shaker_sort:function(t,e){for(
// A stable sort function to allow multi-level sorting of data
// see: http://en.wikipedia.org/wiki/Cocktail_sort
// thanks to Joseph Nahmias
var o=0,a=t.length-1,s=!0;s;){s=!1;for(var r=o;r<a;++r)if(0<e(t[r],t[r+1])){var n=t[r];t[r]=t[r+1],t[r+1]=n,s=!0}// for
if(a--,!s)break;for(var r=a;o<r;--r)if(e(t[r],t[r-1])<0){var n=t[r];t[r]=t[r-1],t[r-1]=n,s=!0}// for
o++}// while(swap)
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
Array.forEach=function(t,e,o){for(var a=0;a<t.length;a++)e.call(o,t[a],a,t)}),
// generic enumeration
Function.prototype.forEach=function(t,e,o){for(var a in t)void 0===this.prototype[a]&&e.call(o,t[a],a,t)},
// character enumeration
String.forEach=function(o,a,s){Array.forEach(o.split(""),function(t,e){a.call(s,t,e,o)})};
// globally resolve forEach enumeration
var forEach=function(t,e,o){if(t){var a=Object;// default
if(t instanceof Function)
// functions have a "length" property
a=Function;else{if(t.forEach instanceof Function)
// the object implements a custom forEach method so use that
return void t.forEach(e,o);"string"==typeof t?
// the object is a string
a=String:"number"==typeof t.length&&(
// the object is array-like
a=Array)}a.forEach(t,e,o)}};