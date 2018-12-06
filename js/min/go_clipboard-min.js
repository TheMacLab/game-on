//not used
function go_cache_menu(e,a){
// the type of the cacheData should be changed to object
sessionStorage.setItem("go_menu_"+a,JSON.stringify(e))}
//not used?
function go_get_menu_data(e){jQuery.ajax({type:"get",url:MyAjax.ajaxurl,data:{
//_ajax_nonce: nonce,
action:"go_clipboard_save_filters",section:section,badge:badge,group:group,unmatched:unmatched},success:function(e){return e}})}function go_toggle(e){checkboxes=jQuery(".go_checkbox");for(var a=0,t=checkboxes.length;a<t;a++)checkboxes[a].checked=e.checked}function go_clipboard_callback(){
//*******************//
// ALL TABS
//*******************//
//Apply on click to the stats and messages buttons in the table
go_stats_links(),
//apply on click to the messages button at the top
jQuery(".go_messages_icon_multiple_clipboard").parent().prop("onclick",null).off("click"),jQuery(".go_messages_icon_multiple_clipboard").parent().one("click",function(e){go_messages_opener(null,null,"multiple_messages")}),tippy(".tooltip",{delay:0,arrow:!0,arrowType:"round",size:"large",duration:300,animation:"scale",zIndex:999999});
//*******************//
//GET CURRENT TAB
//*******************//
var e=jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls");console.log(e),
//IF CURRENT TAB IS . . .
"clipboard_wrap"==e?(
//recalculate for responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc(),
//hide action filters
jQuery("#go_action_filters").hide(),
//update button--set this table to update
jQuery(".go_update_clipboard").prop("onclick",null).off("click"),//unbind click
jQuery(".go_update_clipboard").one("click",function(){Clipboard.draw(),
//go_clipboard_stats_datatable(true);
go_clipboard_update()}),
//search
//unbind search
jQuery("div.dataTables_filter input").unbind(),
//search on clear with 'x'
document.querySelector("#go_clipboard_stats_datatable_filter input").onsearch=function(e){Clipboard.search(this.value).draw()}):"clipboard_store_wrap"==e?(
//recalculate for responsive behavior
jQuery("#go_clipboard_store_datatable").DataTable().columns.adjust().responsive.recalc(),
//show action filters
jQuery("#go_action_filters").show(),jQuery("#go_store_filters").show(),jQuery("#go_task_filters").hide(),
//update button--set this table to update
jQuery(".go_update_clipboard").prop("onclick",null).off("click"),//unbind click
jQuery(".go_update_clipboard").one("click",function(){
//go_clipboard_store_datatable(true);
Store.draw(),go_clipboard_update()}),
//search
jQuery("div.dataTables_filter input").unbind(),
//search on clear with 'x'
document.querySelector("#go_clipboard_store_datatable_filter input").onsearch=function(e){Store.search(this.value).draw()}):"clipboard_messages_wrap"==e?(
//recalculate for responsive behavior
jQuery("#go_clipboard_messages_datatable").DataTable().columns.adjust().responsive.recalc(),
//show/hide filters
jQuery("#go_action_filters").show(),jQuery("#go_store_filters").hide(),jQuery("#go_task_filters").hide(),
//update button--set this table to update
jQuery(".go_update_clipboard").prop("onclick",null).off("click"),//unbind click
jQuery(".go_update_clipboard").one("click",function(){Messages.draw(),go_clipboard_update()}),
/*
            //if filters are changed, redraw the table
            jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #datepicker_clipboard').unbind("change");
            jQuery('#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #datepicker_clipboard').change(function () {
                Messages.draw();
                go_save_clipboard_filters();
                //Clear other tabs
                jQuery('#go_clipboard_stats_datatable').remove();
                jQuery('#go_clipboard_store_datatable').remove();
                //jQuery('#go_clipboard_messages_datatable').remove();
            });
            */
//search
jQuery("div.dataTables_filter input").unbind(),
//search on clear with 'x'
document.querySelector("#go_clipboard_messages_datatable_filter input").onsearch=function(e){Messages.search(this.value).draw()}):"clipboard_activity_wrap"==e&&(
//recalculate for responsive behavior
jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc(),
//show date filter
jQuery("#go_action_filters").show(),jQuery("#go_store_filters").hide(),jQuery("#go_task_filters").show(),
//update button--set this table to update
jQuery(".go_update_clipboard").prop("onclick",null).off("click"),//unbind click
jQuery(".go_update_clipboard").one("click",function(){Activity.draw(),go_clipboard_update()}),
//search
jQuery("div.dataTables_filter input").unbind(),
//search on clear with 'x'
document.querySelector("#go_clipboard_activity_datatable_filter input").onsearch=function(e){Activity.search(this.value).draw()},
/*
            //apply on click to the reset button at the top
            jQuery('.go_reset_icon').prop('onclick',null).off('click');
            jQuery(".go_reset_icon").one("click", function(e){
                go_messages_opener();
            });
            */
go_enable_reset_buttons())}function go_clipboard_update(){go_save_clipboard_filters(),jQuery(".go_update_clipboard").removeClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Refresh Data <span class="dashicons dashicons-update" style="vertical-align: center;"></span></span>');
//*******************//
//GET CURRENT TAB
//*******************//
var e=jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls");
//IF CURRENT TAB IS . . .
"clipboard_wrap"==e?(
//Clear other tables
//jQuery('#go_clipboard_stats_datatable').remove();
jQuery("#go_clipboard_store_datatable").remove(),jQuery("#go_clipboard_messages_datatable").remove()):"clipboard_store_wrap"==e?(
//Clear other tabs
jQuery("#go_clipboard_stats_datatable").remove(),
//jQuery('#go_clipboard_store_datatable').remove();
jQuery("#go_clipboard_messages_datatable").remove()):"clipboard_messages_wrap"==e&&(
//Clear other tabs
jQuery("#go_clipboard_stats_datatable").remove(),jQuery("#go_clipboard_store_datatable").remove())}
//this now saves to session data
function go_save_clipboard_filters(){
//SESSION STORAGE
var e=jQuery("#go_clipboard_user_go_sections_select").val(),a=jQuery("#go_clipboard_user_go_sections_select option:selected").text(),t=jQuery("#go_clipboard_user_go_groups_select").val(),o=jQuery("#go_clipboard_user_go_groups_select option:selected").text(),r=jQuery("#go_clipboard_go_badges_select").val(),s=jQuery("#go_clipboard_go_badges_select option:selected").text(),l=document.getElementById("go_unmatched_toggle").checked;console.log("b "+r),localStorage.setItem("go_clipboard_section",e),localStorage.setItem("go_clipboard_badge",r),localStorage.setItem("go_clipboard_group",t),localStorage.setItem("go_clipboard_section_name",a),localStorage.setItem("go_clipboard_badge_name",s),localStorage.setItem("go_clipboard_group_name",o),localStorage.setItem("go_clipboard_unmatched",l)}
/*
function go_filter_clipboard_datatables(filter_badges) { //function that filters all tables on draw
    jQuery.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var mytable = settings.sTableId;
            //if (mytable == "go_clipboard_stats_datatable" || mytable == "go_clipboard_messages_datatable" || mytable == "go_clipboard_activity_datatable") {
                var section = jQuery('#go_clipboard_user_go_sections_select').val();
                var group = jQuery('#go_clipboard_user_go_groups_select').val();
                var badge = jQuery('#go_clipboard_go_badges_select').val();
                var badges =  data[4] ;
                var groups =  data[3] ; // use data for the filter by column
                var sections = data[2]; // use data for the filter by column



                groups = JSON.parse(groups);
                //sections = JSON.parse(sections);
                badges = JSON.parse(badges);
                //console.log("badges" + badges);
                //console.log("sections" + sections);

                var inlist = true;
                if( group == "none" || jQuery.inArray(group, groups) != -1) {
                    inlist = true;
                }else {
                    inlist = false;
                }

                if (inlist){
                    if( section == "none" || sections == section) {
                        inlist = true;
                    }else {
                        inlist = false;
                    }
                }
                if (filter_badges == true) {
                    if (inlist) {
                        if (badge == "none" || jQuery.inArray(badge, badges) != -1) {
                            inlist = true;
                            //console.log(inlist);
                        } else {
                            inlist = false;
                            //console.log(inlist);
                        }
                    }
                }
                return inlist;
            //}
            //else{
             //   return true;
           // }
        });
}
*/function go_toggle_off(){checkboxes=jQuery(".go_checkbox");for(var e=0,a=checkboxes.length;e<a;e++)checkboxes[e].checked=!1}function go_clipboard_stats_datatable(e){if(0==jQuery("#go_clipboard_stats_datatable").length||1==e){jQuery("#clipboard_stats_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_stats;
//console.log("refresh" + refresh);
//console.log("stats");
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_stats",refresh:e},success:function(e){
//console.log("success");
-1!==e&&(jQuery("#clipboard_stats_datatable_container").html(e),Clipboard=jQuery("#go_clipboard_stats_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_stats_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
//d.user_id = jQuery('#go_stats_hidden_input').val();
e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val()}},bPaginate:!0,
//colReorder: true,
order:[[6,"desc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateLoadParams:function(e,a){
//if (data.order) delete data.order;
a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,searchDelay:1e3,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"1px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7],
//className: 'noVis',
sortable:!1},{targets:[8]},{targets:[13]}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}))}})}else go_clipboard_callback()}function go_clipboard_store_datatable(e){if(0==jQuery("#go_clipboard_store_datatable").length||1==e){jQuery("#clipboard_store_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_store;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_store"},success:function(e){
//console.log("success");
-1!==e&&(jQuery("#clipboard_store_datatable_container").html(e),
//go_filter_datatables();
Store=jQuery("#go_clipboard_store_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_store_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
e.date=jQuery("#datepicker_clipboard").val(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val(),e.unmatched=document.getElementById("go_unmatched_toggle").checked,e.store_item=jQuery("#go_store_item_select").val()}},bPaginate:!0,
//colReorder: true,
order:[[8,"desc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateLoadParams:function(e,a){
//if (data.order) delete data.order;
a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,searchDelay:1e3,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7],sortable:!1},{targets:[9],sortable:!0},{targets:[13],sortable:!1}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}))}})}else go_clipboard_callback()}function go_clipboard_messages_datatable(e){if(0==jQuery("#go_clipboard_messages_datatable").length||1==e){jQuery("#clipboard_messages_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_messages"},success:function(e){
//console.log("success");
-1!==e&&(jQuery("#clipboard_messages_datatable_container").html(e),
//go_filter_datatables();
Messages=jQuery("#go_clipboard_messages_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_messages_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
e.date=jQuery("#datepicker_clipboard").val(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val(),e.unmatched=document.getElementById("go_unmatched_toggle").checked}},bPaginate:!0,
//colReorder: true,
order:[[8,"desc"]],responsive:!0,autoWidth:!1,searchDelay:1e3,stateSave:!0,stateLoadParams:function(e,a){
//if (data.order) delete data.order;
a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7],sortable:!1},{targets:[9],sortable:!1},{targets:[13],sortable:!1}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}),
//search only on enter key
jQuery("div.dataTables_filter input").unbind(),jQuery("div.dataTables_filter input").keyup(function(e){13==e.keyCode&&Messages.search(this.value).draw()}))}})}else go_clipboard_callback()}function go_clipboard_activity_datatable(e){if(0==jQuery("#go_clipboard_activity_datatable").length||1==e){jQuery("#clipboard_activity_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_activity;console.log("date: "+jQuery("#datepicker_clipboard").val()),console.log("section: "+jQuery("#go_clipboard_user_go_sections_select").val()),console.log("group: "+jQuery("#go_clipboard_user_go_groups_select").val()),console.log("badges: "+jQuery("#go_clipboard_go_badges_select").val()),console.log("unmatched: "+document.getElementById("go_unmatched_toggle").checked),console.log("tasks: "+jQuery("#go_task_select").val()),
//console.log(date);
jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_activity",date:jQuery("#datepicker_clipboard").val()},success:function(e){
//console.log("success");
-1!==e&&(jQuery("#clipboard_activity_datatable_container").html(e),
//go_filter_datatables();
Activity=jQuery("#go_clipboard_activity_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_activity_dataloader_ajax",data:function(e){
//d.user_id = jQuery('#go_stats_hidden_input').val();
e.date=jQuery("#datepicker_clipboard").val(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val(),e.unmatched=document.getElementById("go_unmatched_toggle").checked,e.tasks=jQuery("#go_task_select").val();for(var a=0,t=e.columns.length;a<t;a++)e.columns[a].search.value||delete e.columns[a].search,!0===e.columns[a].searchable&&delete e.columns[a].searchable,!0===e.columns[a].orderable&&delete e.columns[a].orderable,e.columns[a].data===e.columns[a].name&&delete e.columns[a].name;delete e.search.regex}},deferRender:!0,bPaginate:!0,
//colReorder: true,
order:[11,"asc"],responsive:!0,autoWidth:!1,
//stateSave: true,
stateLoadParams:function(e,a){
//if (data.order) delete data.order;
a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7,9,13],sortable:!1}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{text:'<span class="go_tasks_reset_multiple_clipboard">Reset <i class="fa fa-times-circle" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}),
// Add event listener for opening and closing more actions
jQuery("#go_clipboard_activity_datatable .show_more").click(function(){var e;
//console.log(hidden);
0==jQuery(this).hasClass("shown")?(jQuery(this).addClass("shown"),jQuery(this).siblings(".hidden_action").show(),jQuery(this).find(".hide_more_actions").show(),jQuery(this).find(".show_more_actions").hide()):(jQuery(this).removeClass("shown"),jQuery(this).siblings(".hidden_action").hide(),jQuery(this).find(".hide_more_actions").hide(),jQuery(this).find(".show_more_actions").show())}))}})}else go_clipboard_callback()}jQuery(document).ready(function(){jQuery("#datepicker_clipboard").datepicker({firstDay:0}),jQuery("#datepicker_clipboard").datepicker("setDate",new Date),//today's date
//Tabs
jQuery("#records_tabs").length&&(jQuery("#records_tabs").tabs(),jQuery(".clipboard_tabs").click(function(){switch(
//console.log("tabs");
tab=jQuery(this).attr("tab"),tab){case"clipboard":
//console.log("stats1");
go_clipboard_stats_datatable(!1),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc();break;case"store":go_clipboard_store_datatable(),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_store_datatable").DataTable().columns.adjust().responsive.recalc(),
//add the store item filter select2
jQuery("#go_store_item_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_cpt_select2_ajax",// AJAX action for admin-ajax.php
cpt:"go_store"}},processResults:function(e){
//console.log("search results: " + data);
var t=[];return e&&
// data is the array of arrays, and each of them contains ID and the Label of the option
jQuery.each(e,function(e,a){// do not forget that "index" is just auto incremented value
t.push({id:a[0],text:a[1]})}),{results:t}},cache:!0},minimumInputLength:1,// the minimum of symbols to input before perform a search
multiple:!0,placeholder:"Show All"});break;case"messages":
//console.log("messages");
go_clipboard_messages_datatable(),
//force window resize on load to initialize responsive behavior
jQuery("#go_clipboard_messages_datatable").DataTable().columns.adjust().responsive.recalc();break;case"activity":
//console.log("activity");
go_clipboard_activity_datatable(),jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc(),
//add task select2
jQuery("#go_task_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_cpt_select2_ajax",// AJAX action for admin-ajax.php
cpt:"tasks"}},processResults:function(e){
//console.log("search results: " + data);
var t=[];return e&&
// data is the array of arrays, and each of them contains ID and the Label of the option
jQuery.each(e,function(e,a){// do not forget that "index" is just auto incremented value
t.push({id:a[0],text:a[1]})}),{results:t}},cache:!1},minimumInputLength:1,// the minimum of symbols to input before perform a search
multiple:!0,placeholder:"Show All"});break}})),jQuery("#records_tabs").length&&
//go_clipboard_stats_datatable(false);
jQuery("#records_tabs").css("margin-left","");
// Get saved data from sessionStorage
var a=localStorage.getItem("go_clipboard_section"),e=localStorage.getItem("go_clipboard_section_name"),t=localStorage.getItem("go_clipboard_badge"),o=localStorage.getItem("go_clipboard_badge_name"),r=localStorage.getItem("go_clipboard_group"),s=localStorage.getItem("go_clipboard_group_name"),l=localStorage.getItem("go_clipboard_unmatched");
//jQuery('#go_clipboard_user_go_sections_select').select2({data: go_get_menu_data('go_user_sections')});
// go_cache_menu(1, 'stats');
//jQuery("#go_clipboard_user_go_sections_select").select2("destroy").select2({data:data});
if(1!=l&&"true"!=l||jQuery("#go_unmatched_toggle").prop("checked",!0),jQuery("#go_clipboard_user_go_sections_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"user_go_sections",is_hier:!1}},processResults:function(e){return jQuery("#go_clipboard_user_go_sections_select").select2("destroy"),jQuery("#go_clipboard_user_go_sections_select").children().remove(),jQuery("#go_clipboard_user_go_sections_select").select2({data:e,placeholder:"Show All",allowClear:!0}).val(a).trigger("change"),jQuery("#go_clipboard_user_go_sections_select").select2("open"),{results:e}},cache:!0},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Show All",allowClear:!0}),null!=a&&"null"!=a){
// Fetch the preselected item, and add to the control
var c=jQuery("#go_clipboard_user_go_sections_select"),i=new Option(e,a,!0,!0);
// create the option and append to Select2
c.append(i).trigger("change")}if(jQuery("#go_clipboard_user_go_groups_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"user_go_groups",is_hier:!0}},processResults:function(e){return jQuery("#go_clipboard_user_go_groups_select").select2("destroy"),jQuery("#go_clipboard_user_go_groups_select").children().remove(),jQuery("#go_clipboard_user_go_groups_select").select2({data:e,placeholder:"Show All",allowClear:!0}).val(r).trigger("change"),jQuery("#go_clipboard_user_go_groups_select").select2("open"),{results:e}},cache:!0},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Show All",allowClear:!0}),null!=r&&"null"!=r){
// Fetch the preselected item, and add to the control
var _=jQuery("#go_clipboard_user_go_groups_select"),i=new Option(s,r,!0,!0);
// create the option and append to Select2
_.append(i).trigger("change")}if(jQuery("#go_clipboard_go_badges_select").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(e){return{q:e.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:"go_badges",is_hier:!0}},processResults:function(e){return jQuery("#go_clipboard_go_badges_select").select2("destroy"),jQuery("#go_clipboard_go_badges_select").children().remove(),jQuery("#go_clipboard_go_badges_select").select2({data:e,placeholder:"Show All",allowClear:!0}).val(t).trigger("change"),jQuery("#go_clipboard_go_badges_select").select2("open"),{results:e}},cache:!1},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Show All",allowClear:!0}),null!=t&&"null"!=t){console.log("setB");
// Fetch the preselected item, and add to the control
var n=jQuery("#go_clipboard_go_badges_select"),i=new Option(o,t,!0,!0);
// create the option and append to Select2
n.append(i).trigger("change")}
//jQuery('#go_clipboard_user_go_sections_select').val(section).trigger('change');;
//jQuery('#go_clipboard_user_go_groups_select').val(group);
//jQuery('#go_clipboard_go_badges_select').val(badge);
go_clipboard_stats_datatable(),//draw the stats tab on load
//ADD Blue background and glow to filter button
jQuery("#datepicker_clipboard, #go_unmatched_toggle").change(function(){jQuery(".go_update_clipboard").addClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>')}),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #go_task_select, #go_store_item_select").on("select2:select",function(e){
// Do something
jQuery(".go_update_clipboard").addClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>')}),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #go_task_select, #go_store_item_select").on("select2:unselect",function(e){
// Do something
jQuery(".go_update_clipboard").addClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>')}),jQuery(".go_reset_clipboard").on("click",function(){jQuery("#datepicker_clipboard").val(""),jQuery("#go_clipboard_user_go_sections_select, #go_clipboard_user_go_groups_select, #go_clipboard_go_badges_select, #go_task_select, #go_store_item_select").val(null).trigger("change"),jQuery(".go_update_clipboard").addClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>'),jQuery("#go_unmatched_toggle").prop("checked",!1)})});