function go_toggle(e){checkboxes=jQuery(".go_checkbox");for(var t=0,a=checkboxes.length;t<a;t++)checkboxes[t].checked=e.checked}function go_clipboard_class_a_choiceOLD_AJAX(){jQuery.fn.dataTable.ext.search.push(function(e,t,a){var r=jQuery("#go_user_go_sections_select").val(),o=jQuery("#go_user_go_groups_select").val(),s=t[2],n=t[1];s=JSON.parse(s),n=JSON.parse(n);var c=jQuery.inArray(r,n);return("none"==o||-1!=jQuery.inArray(o,s))&&("none"==r||-1!=jQuery.inArray(r,n))}),jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:nonce,action:"go_clipboard_intable",go_clipboard_class_a_choice:jQuery("#go_clipboard_class_a_choice").val()},success:function(e){-1!==e&&(jQuery("#go_clipboard_table_body").append(e),jQuery(document).ready(function(){if(jQuery("#go_clipboard").length){var e=jQuery("#go_clipboard").DataTable({orderFixed:[[4,"desc"]],responsive:!0,autoWidth:!1,paging:!1,columnDefs:[{targets:[1],visible:!1},{targets:[2],visible:!1}]});jQuery("#go_user_go_sections_select, #go_user_go_groups_select").change(function(){e.draw()})}}))}})}function go_clipboard_class_a_choice(){if(go_filter_datatables(),jQuery("#go_clipboard_datatable").length){var e=jQuery("#go_clipboard_datatable").DataTable({stateSave:!0,bPaginate:!1,order:[[4,"asc"]],dom:"Bfrtip",columnDefs:[{targets:[0],className:"noVis"},{targets:[1],visible:!1,className:"noVis"},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[6],className:"noVis"},{targets:[7],className:"noVis"},{targets:[9],className:"noVis"}],buttons:["copy","excel","pdf",{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"]}]});jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){e.draw();var t=GO_CLIPBOARD_DATA.nonces.go_clipboard_save_filters,a=jQuery("#go_clipboard_user_go_sections_select").val(),r=jQuery("#go_clipboard_user_go_groups_select").val(),o=jQuery("#go_clipboard_go_badges_select").val();jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:t,action:"go_clipboard_save_filters",section:a,badge:o,group:r},success:function(e){console.log("values saved")}})})}}function go_clipboard_class_a_choice_activity(){if(0==jQuery("#go_clipboard_activity_datatable").length){var e=GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_activity;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_clipboard_intable_activity",go_clipboard_class_a_choice_activity:jQuery("#go_clipboard_class_a_choice_activity").val()},success:function(e){if(-1!==e){jQuery("#clipboard_activity_wrap").html(e),go_filter_datatables();var t=jQuery("#go_clipboard_activity_datatable").DataTable({stateSave:!0,bPaginate:!1,order:[[4,"asc"]],dom:"Bfrtip",columnDefs:[{targets:[0],className:"noVis"},{targets:[1],visible:!1,className:"noVis"},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[6],className:"noVis"},{targets:[7],className:"noVis"},{targets:[9],className:"noVis"}],buttons:["copy","excel","pdf",{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"]}]});jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){t.draw()})}}})}}function go_clipboard_class_a_choice_messages(){if(0==jQuery("#go_clipboard_messages_datatable").length){var e=GO_CLIPBOARD_DATA.nonces.go_clipboard_intable_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_clipboard_intable_messages",go_clipboard_class_a_choice_messages:jQuery("#go_clipboard_class_a_choice_messages").val()},success:function(e){if(-1!==e){jQuery("#clipboard_messages_wrap").html(e),go_filter_datatables();var t=jQuery("#go_clipboard_messages_datatable").DataTable({stateSave:!0,bPaginate:!1,order:[[4,"asc"]],dom:"Bfrtip",columnDefs:[{targets:[0],className:"noVis"},{targets:[1],visible:!1,className:"noVis"},{targets:[2],visible:!1,className:"noVis"},{targets:[3],visible:!1,className:"noVis"},{targets:[6],className:"noVis"},{targets:[7],className:"noVis"},{targets:[9],className:"noVis"}],buttons:["copy","excel","pdf",{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"]}]});jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select").change(function(){t.draw()})}}})}}function go_user_focus_change(e,t){var a=GO_CLIPBOARD_DATA.nonces.go_update_user_focuses;jQuery.ajax({type:"POST",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_update_user_focuses",new_user_focus:jQuery(t).val(),user_id:e}})}function check_null(e){return""!=e?e:0}function go_clipboard_add(){var e=[];if(jQuery("#go_send_message").prop("disabled","disabled"),jQuery("input:checkbox[name=go_selected]:checked").each(function(){e.push(jQuery(this).val())}),e.length>0){var t=parseFloat(check_null(jQuery("#go_clipboard_points").val())),a=parseFloat(check_null(jQuery("#go_clipboard_currency").val())),r=parseFloat(check_null(jQuery("#go_clipboard_bonus_currency").val())),o=parseFloat(check_null(jQuery("#go_clipboard_penalty").val())),s=parseFloat(check_null(jQuery("#go_clipboard_minutes").val())),n=parseFloat(check_null(jQuery("#go_clipboard_badge").val())),c=jQuery("#go_clipboard_reason").val();""===c&&(c=jQuery("#go_clipboard_reason").attr("placeholder"));var i=GO_CLIPBOARD_DATA.nonces.go_clipboard_add;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:i,action:"go_clipboard_add",ids:e,points:t,currency:a,bonus_currency:r,penalty:o,reason:c,minutes:s,badge_ID:n},success:function(t){var a=t.indexOf('{"update_status":'),r=t.substr(a),o=JSON.parse(r);if(o.update_status)for(index in e){var s=e[index],n=o[s].points,c=o[s].currency,i=o[s].bonus_currency,l=o[s].penalty,d=o[s].minutes,_=o[s].badge_count;jQuery("#user_"+s+" .user_points").html(n),jQuery("#user_"+s+" .user_currency").html(c),jQuery("#user_"+s+" .user_bonus_currency").html(i),jQuery("#user_"+s+" .user_penalty").html(l),jQuery("#user_"+s+" .user_minutes").html(d),jQuery("#user_"+s+" .user_badge_count").html(_)}go_clipboard_clear_fields(),jQuery("#go_send_message").prop("disabled",!1),jQuery('#go_clipboard_table input[type="checkbox"]').removeAttr("checked")}})}else go_clipboard_clear_fields(),jQuery("#go_send_message").prop("disabled",!1)}function go_clipboard_clear_fields(){jQuery("#go_clipboard_points").val(""),jQuery("#go_clipboard_currency").val(""),jQuery("#go_clipboard_bonus_currency").val(""),jQuery("#go_clipboard_minutes").val(""),jQuery("#go_clipboard_penalty").val(""),jQuery("#go_clipboard_reason").val(""),jQuery("#go_clipboard_badge").val("")}function go_fix_messages(){var e=GO_CLIPBOARD_DATA.nonces.go_fix_messages;jQuery.ajax({type:"POST",url:MyAjax.ajaxurl,data:{_ajax_nonce:e,action:"go_fix_messages"},success:function(e){-1!==e&&alert("Messages fixed")}})}function dean_addEvent(e,t,a){if(e.addEventListener)e.addEventListener(t,a,!1);else{a.$$guid||(a.$$guid=dean_addEvent.guid++),e.events||(e.events={});var r=e.events[t];r||(r=e.events[t]={},e["on"+t]&&(r[0]=e["on"+t])),r[a.$$guid]=a,e["on"+t]=handleEvent}}function removeEvent(e,t,a){e.removeEventListener?e.removeEventListener(t,a,!1):e.events&&e.events[t]&&delete e.events[t][a.$$guid]}function handleEvent(e){var t=!0;e=e||fixEvent(((this.ownerDocument||this.document||this).parentWindow||window).event);var a=this.events[e.type];for(var r in a)this.$$handleEvent=a[r],!1===this.$$handleEvent(e)&&(t=!1);return t}function fixEvent(e){return e.preventDefault=fixEvent.preventDefault,e.stopPropagation=fixEvent.stopPropagation,e}jQuery(document).ready(function(){jQuery("#records_tabs").length&&(jQuery("#records_tabs").tabs(),jQuery(".clipboard_tabs").click(function(){switch(tab=jQuery(this).attr("tab"),tab){case"messages":go_clipboard_class_a_choice_messages();break;case"activity":go_clipboard_class_a_choice_activity();break}})),jQuery("#go_clipboard_datatable").length&&go_clipboard_class_a_choice()});var stIsIE=/*@cc_on!@*/!1;/*@cc_on @*/
if(sorttable={init:function(){arguments.callee.done||(arguments.callee.done=!0,_timer&&clearInterval(_timer),document.createElement&&document.getElementsByTagName&&(sorttable.DATE_RE=/^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/,forEach(document.getElementsByTagName("table"),function(e){-1!=e.className.search(/\bsortable\b/)&&sorttable.makeSortable(e)})))},makeSortable:function(e){if(0==e.getElementsByTagName("thead").length&&(the=document.createElement("thead"),the.appendChild(e.rows[0]),e.insertBefore(the,e.firstChild)),null==e.tHead&&(e.tHead=e.getElementsByTagName("thead")[0]),1==e.tHead.rows.length){sortbottomrows=[];for(var t=0;t<e.rows.length;t++)-1!=e.rows[t].className.search(/\bsortbottom\b/)&&(sortbottomrows[sortbottomrows.length]=e.rows[t]);if(sortbottomrows){null==e.tFoot&&(tfo=document.createElement("tfoot"),e.appendChild(tfo));for(var t=0;t<sortbottomrows.length;t++)tfo.appendChild(sortbottomrows[t]);delete sortbottomrows}headrow=e.tHead.rows[0].cells;for(var t=0;t<headrow.length;t++)headrow[t].className.match(/\bsorttable_nosort\b/)||(mtch=headrow[t].className.match(/\bsorttable_([a-z0-9]+)\b/),mtch&&(override=mtch[1]),mtch&&"function"==typeof sorttable["sort_"+override]?headrow[t].sorttable_sortfunction=sorttable["sort_"+override]:headrow[t].sorttable_sortfunction=sorttable.guessType(e,t),headrow[t].sorttable_columnindex=t,headrow[t].sorttable_tbody=e.tBodies[0],dean_addEvent(headrow[t],"click",sorttable.innerSortFunction=function(e){if(-1!=this.className.search(/\bsorttable_sorted\b/))return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted","sorttable_sorted_reverse"),this.removeChild(document.getElementById("sorttable_sortfwdind")),sortrevind=document.createElement("span"),sortrevind.id="sorttable_sortrevind",sortrevind.innerHTML=stIsIE?'&nbsp<font face="webdings">5</font>':"&nbsp;&#x25B4;",void this.appendChild(sortrevind);if(-1!=this.className.search(/\bsorttable_sorted_reverse\b/))return sorttable.reverse(this.sorttable_tbody),this.className=this.className.replace("sorttable_sorted_reverse","sorttable_sorted"),this.removeChild(document.getElementById("sorttable_sortrevind")),sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",void this.appendChild(sortfwdind);theadrow=this.parentNode,forEach(theadrow.childNodes,function(e){1==e.nodeType&&(e.className=e.className.replace("sorttable_sorted_reverse",""),e.className=e.className.replace("sorttable_sorted",""))}),sortfwdind=document.getElementById("sorttable_sortfwdind"),sortfwdind&&sortfwdind.parentNode.removeChild(sortfwdind),sortrevind=document.getElementById("sorttable_sortrevind"),sortrevind&&sortrevind.parentNode.removeChild(sortrevind),this.className+=" sorttable_sorted",sortfwdind=document.createElement("span"),sortfwdind.id="sorttable_sortfwdind",sortfwdind.innerHTML=stIsIE?'&nbsp<font face="webdings">6</font>':"&nbsp;&#x25BE;",this.appendChild(sortfwdind),row_array=[],col=this.sorttable_columnindex,rows=this.sorttable_tbody.rows;for(var t=0;t<rows.length;t++)row_array[row_array.length]=[sorttable.getInnerText(rows[t].cells[col]),rows[t]];row_array.sort(this.sorttable_sortfunction),tb=this.sorttable_tbody;for(var t=0;t<row_array.length;t++)tb.appendChild(row_array[t][1]);delete row_array}))}},guessType:function(e,t){sortfn=sorttable.sort_alpha;for(var a=0;a<e.tBodies[0].rows.length;a++)if(text=sorttable.getInnerText(e.tBodies[0].rows[a].cells[t]),""!=text){if(text.match(/^-?[�$�]?[\d,.]+%?$/))return sorttable.sort_numeric;if(possdate=text.match(sorttable.DATE_RE),possdate){if(first=parseInt(possdate[1]),second=parseInt(possdate[2]),first>12)return sorttable.sort_ddmm;if(second>12)return sorttable.sort_mmdd;sortfn=sorttable.sort_ddmm}}return sortfn},getInnerText:function(e){if(!e)return"";if(hasInputs="function"==typeof e.getElementsByTagName&&e.getElementsByTagName("input").length,null!=e.getAttribute("sorttable_customkey"))return e.getAttribute("sorttable_customkey");if(void 0!==e.textContent&&!hasInputs)return e.textContent.replace(/^\s+|\s+$/g,"");if(void 0!==e.innerText&&!hasInputs)return e.innerText.replace(/^\s+|\s+$/g,"");if(void 0!==e.text&&!hasInputs)return e.text.replace(/^\s+|\s+$/g,"");switch(e.nodeType){case 3:if("input"==e.nodeName.toLowerCase())return e.value.replace(/^\s+|\s+$/g,"");case 4:return e.nodeValue.replace(/^\s+|\s+$/g,"");break;case 1:case 11:for(var t="",a=0;a<e.childNodes.length;a++)t+=sorttable.getInnerText(e.childNodes[a]);return t.replace(/^\s+|\s+$/g,"");break;default:return""}},reverse:function(e){newrows=[];for(var t=0;t<e.rows.length;t++)newrows[newrows.length]=e.rows[t];for(var t=newrows.length-1;t>=0;t--)e.appendChild(newrows[t]);delete newrows},sort_numeric:function(e,t){return aa=parseFloat(e[0].replace(/[^0-9.-]/g,"")),isNaN(aa)&&(aa=0),bb=parseFloat(t[0].replace(/[^0-9.-]/g,"")),isNaN(bb)&&(bb=0),aa-bb},sort_alpha:function(e,t){return e[0]==t[0]?0:e[0]<t[0]?-1:1},sort_ddmm:function(e,t){return mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],m=mtch[2],d=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},sort_mmdd:function(e,t){return mtch=e[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt1=y+m+d,mtch=t[0].match(sorttable.DATE_RE),y=mtch[3],d=mtch[2],m=mtch[1],1==m.length&&(m="0"+m),1==d.length&&(d="0"+d),dt2=y+m+d,dt1==dt2?0:dt1<dt2?-1:1},shaker_sort:function(e,t){for(var a=0,r=e.length-1,o=!0;o;){o=!1;for(var s=a;s<r;++s)if(t(e[s],e[s+1])>0){var n=e[s];e[s]=e[s+1],e[s+1]=n,o=!0}if(r--,!o)break;for(var s=r;s>a;--s)if(t(e[s],e[s-1])<0){var n=e[s];e[s]=e[s-1],e[s-1]=n,o=!0}a++}}},document.addEventListener&&document.addEventListener("DOMContentLoaded",sorttable.init,!1),/WebKit/i.test(navigator.userAgent))var _timer=setInterval(function(){/loaded|complete/.test(document.readyState)&&sorttable.init()},10);window.onload=sorttable.init,dean_addEvent.guid=1,fixEvent.preventDefault=function(){this.returnValue=!1},fixEvent.stopPropagation=function(){this.cancelBubble=!0},Array.forEach||(Array.forEach=function(e,t,a){for(var r=0;r<e.length;r++)t.call(a,e[r],r,e)}),Function.prototype.forEach=function(e,t,a){for(var r in e)void 0===this.prototype[r]&&t.call(a,e[r],r,e)},String.forEach=function(e,t,a){Array.forEach(e.split(""),function(r,o){t.call(a,r,o,e)})};var forEach=function(e,t,a){if(e){var r=Object;if(e instanceof Function)r=Function;else{if(e.forEach instanceof Function)return void e.forEach(t,a);"string"==typeof e?r=String:"number"==typeof e.length&&(r=Array)}r.forEach(e,t,a)}};