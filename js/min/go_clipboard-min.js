function go_cache_menu(e,a){sessionStorage.setItem("go_menu_"+a,JSON.stringify(e))}function go_get_menu_data(e){jQuery.ajax({type:"get",url:MyAjax.ajaxurl,data:{action:"go_clipboard_save_filters",section:section,badge:badge,group:group,unmatched:unmatched},success:function(e){return e}})}function go_toggle(e){checkboxes=jQuery(".go_checkbox");for(var a=0,t=checkboxes.length;a<t;a++)checkboxes[a].checked=e.checked}function go_clipboard_callback(){go_stats_links(),jQuery(".go_messages_icon_multiple_clipboard").parent().prop("onclick",null).off("click"),jQuery(".go_messages_icon_multiple_clipboard").parent().one("click",function(e){go_messages_opener(null,null,"multiple_messages")}),tippy(".tooltip",{delay:0,arrow:!0,arrowType:"round",size:"large",duration:300,animation:"scale",zIndex:999999});var e=jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls");console.log(e),"clipboard_wrap"==e?(jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc(),jQuery("#go_action_filters").hide(),jQuery(".go_update_clipboard").prop("onclick",null).off("click"),jQuery(".go_update_clipboard").one("click",function(){Clipboard.draw(),go_clipboard_update()}),jQuery("div.dataTables_filter input").unbind(),document.querySelector("#go_clipboard_stats_datatable_filter input").onsearch=function(e){Clipboard.search(this.value).draw()}):"clipboard_store_wrap"==e?(jQuery("#go_clipboard_store_datatable").DataTable().columns.adjust().responsive.recalc(),jQuery("#go_action_filters").show(),jQuery("#go_store_filters").show(),jQuery("#go_task_filters").hide(),jQuery(".go_update_clipboard").prop("onclick",null).off("click"),jQuery(".go_update_clipboard").one("click",function(){Store.draw(),go_clipboard_update()}),jQuery("div.dataTables_filter input").unbind(),document.querySelector("#go_clipboard_store_datatable_filter input").onsearch=function(e){Store.search(this.value).draw()}):"clipboard_messages_wrap"==e?(jQuery("#go_clipboard_messages_datatable").DataTable().columns.adjust().responsive.recalc(),jQuery("#go_action_filters").show(),jQuery("#go_store_filters").hide(),jQuery("#go_task_filters").hide(),jQuery(".go_update_clipboard").prop("onclick",null).off("click"),jQuery(".go_update_clipboard").one("click",function(){Messages.draw(),go_clipboard_update()}),jQuery("div.dataTables_filter input").unbind(),document.querySelector("#go_clipboard_messages_datatable_filter input").onsearch=function(e){Messages.search(this.value).draw()}):"clipboard_activity_wrap"==e&&(jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc(),jQuery("#go_action_filters").show(),jQuery("#go_store_filters").hide(),jQuery("#go_task_filters").show(),jQuery(".go_update_clipboard").prop("onclick",null).off("click"),jQuery(".go_update_clipboard").one("click",function(){Activity.draw(),go_clipboard_update()}),jQuery("div.dataTables_filter input").unbind(),document.querySelector("#go_clipboard_activity_datatable_filter input").onsearch=function(e){Activity.search(this.value).draw()},go_enable_reset_buttons())}function go_clipboard_update(){go_save_clipboard_filters(!1),jQuery(".go_update_clipboard").removeClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Refresh Data <span class="dashicons dashicons-update" style="vertical-align: center;"></span></span>');var e=jQuery("#records_tabs").find("[aria-selected='true']").attr("aria-controls");"clipboard_wrap"==e?(jQuery("#go_clipboard_store_datatable").remove(),jQuery("#go_clipboard_messages_datatable").remove()):"clipboard_store_wrap"==e?(jQuery("#go_clipboard_stats_datatable").remove(),jQuery("#go_clipboard_messages_datatable").remove()):"clipboard_messages_wrap"==e&&(jQuery("#go_clipboard_stats_datatable").remove(),jQuery("#go_clipboard_store_datatable").remove())}function go_toggle_off(){checkboxes=jQuery(".go_checkbox");for(var e=0,a=checkboxes.length;e<a;e++)checkboxes[e].checked=!1}function go_clipboard_stats_datatable(e){if(0==jQuery("#go_clipboard_stats_datatable").length||1==e){jQuery("#clipboard_stats_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_stats;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_stats",refresh:e},success:function(e){-1!==e&&(jQuery("#clipboard_stats_datatable_container").html(e),Clipboard=jQuery("#go_clipboard_stats_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_stats_dataloader_ajax",data:function(e){e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val()}},bPaginate:!0,order:[[6,"desc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateLoadParams:function(e,a){a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,searchDelay:1e3,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"1px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7],sortable:!1},{targets:[8]},{targets:[13]}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}))}})}else go_clipboard_callback()}function go_clipboard_store_datatable(e){if(0==jQuery("#go_clipboard_store_datatable").length||1==e){jQuery("#clipboard_store_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_store;jQuery.ajax({url:MyAjax.ajaxurl,type:"post",data:{_ajax_nonce:a,action:"go_clipboard_store"},success:function(e){-1!==e&&(jQuery("#clipboard_store_datatable_container").html(e),Store=jQuery("#go_clipboard_store_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_store_dataloader_ajax",data:function(e){e.date=jQuery("#datepicker_clipboard span").html(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val(),e.unmatched=document.getElementById("go_unmatched_toggle").checked,e.store_item=jQuery("#go_store_item_select").val()}},bPaginate:!0,order:[[8,"desc"]],responsive:!0,autoWidth:!1,stateSave:!0,stateLoadParams:function(e,a){a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,searchDelay:1e3,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7],sortable:!1},{targets:[9],sortable:!0},{targets:[13],sortable:!1}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}))}})}else go_clipboard_callback()}function go_clipboard_messages_datatable(e){if(0==jQuery("#go_clipboard_messages_datatable").length||1==e){jQuery("#clipboard_messages_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_messages;jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_messages"},success:function(e){-1!==e&&(jQuery("#clipboard_messages_datatable_container").html(e),Messages=jQuery("#go_clipboard_messages_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_messages_dataloader_ajax",data:function(e){e.date=jQuery("#datepicker_clipboard span").html(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val(),e.unmatched=document.getElementById("go_unmatched_toggle").checked}},bPaginate:!0,order:[[8,"desc"]],responsive:!0,autoWidth:!1,searchDelay:1e3,stateSave:!0,stateLoadParams:function(e,a){a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7],sortable:!1},{targets:[9],sortable:!1},{targets:[13],sortable:!1}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}),jQuery("div.dataTables_filter input").unbind(),jQuery("div.dataTables_filter input").keyup(function(e){13==e.keyCode&&Messages.search(this.value).draw()}))}})}else go_clipboard_callback()}function go_clipboard_activity_datatable(e){if(0==jQuery("#go_clipboard_activity_datatable").length||1==e){jQuery("#clipboard_activity_datatable_container").html("<h2>Loading . . .</h2>");var a=GO_CLIPBOARD_DATA.nonces.go_clipboard_activity;console.log("date: "+jQuery("#datepicker_clipboard span").html()),console.log("section: "+jQuery("#go_clipboard_user_go_sections_select").val()),console.log("group: "+jQuery("#go_clipboard_user_go_groups_select").val()),console.log("badges: "+jQuery("#go_clipboard_go_badges_select").val()),console.log("unmatched: "+document.getElementById("go_unmatched_toggle").checked),console.log("tasks: "+jQuery("#go_task_select").val()),jQuery.ajax({type:"post",url:MyAjax.ajaxurl,data:{_ajax_nonce:a,action:"go_clipboard_activity",date:jQuery("#datepicker_clipboard span").html()},success:function(e){-1!==e&&(jQuery("#clipboard_activity_datatable_container").html(e),Activity=jQuery("#go_clipboard_activity_datatable").DataTable({processing:!0,serverSide:!0,ajax:{url:MyAjax.ajaxurl+"?action=go_clipboard_activity_dataloader_ajax",data:function(e){e.date=jQuery("#datepicker_clipboard span").html(),e.section=jQuery("#go_clipboard_user_go_sections_select").val(),e.group=jQuery("#go_clipboard_user_go_groups_select").val(),e.badge=jQuery("#go_clipboard_go_badges_select").val(),e.unmatched=document.getElementById("go_unmatched_toggle").checked,e.tasks=jQuery("#go_task_select").val();for(var a=0,t=e.columns.length;a<t;a++)e.columns[a].search.value||delete e.columns[a].search,!0===e.columns[a].searchable&&delete e.columns[a].searchable,!0===e.columns[a].orderable&&delete e.columns[a].orderable,e.columns[a].data===e.columns[a].name&&delete e.columns[a].name;delete e.search.regex}},deferRender:!0,bPaginate:!0,order:[11,"asc"],responsive:!0,autoWidth:!1,stateLoadParams:function(e,a){a.search&&delete a.search,a.start&&delete a.start},stateDuration:31557600,dom:"lBfrtip",drawCallback:function(e){go_clipboard_callback()},columnDefs:[{type:"natural",targets:"_all"},{targets:[0],className:"noVis",width:"5px",sortable:!1},{targets:[1],className:"noVis",width:"20px",sortable:!1},{targets:[7,9,14],sortable:!1}],buttons:[{text:'<span class="go_messages_icon_multiple_clipboard">Message <i class="fa fa-bullhorn" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{text:'<span class="go_tasks_reset_multiple_clipboard">Reset <i class="fa fa-times-circle" aria-hidden="true"></i><span>',action:function(e,a,t,o){}},{extend:"collection",text:"Export ...",buttons:[{extend:"pdf",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"},orientation:"landscape"},{extend:"excel",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}},{extend:"csv",title:"Game On Data Export",exportOptions:{columns:"thead th:not(.noExport)"}}]},{extend:"colvis",columns:":not(.noVis)",postfixButtons:["colvisRestore"],text:"Column Visibility"}]}),jQuery("#go_clipboard_activity_datatable .show_more").click(function(){var e;0==jQuery(this).hasClass("shown")?(jQuery(this).addClass("shown"),jQuery(this).siblings(".hidden_action").show(),jQuery(this).find(".hide_more_actions").show(),jQuery(this).find(".show_more_actions").hide()):(jQuery(this).removeClass("shown"),jQuery(this).siblings(".hidden_action").hide(),jQuery(this).find(".hide_more_actions").hide(),jQuery(this).find(".show_more_actions").show())}))}})}else go_clipboard_callback()}jQuery(document).ready(function(){go_load_daterangepicker(),jQuery("#records_tabs").length&&(jQuery("#records_tabs").tabs(),jQuery(".clipboard_tabs").click(function(){switch(tab=jQuery(this).attr("tab"),tab){case"clipboard":go_clipboard_stats_datatable(!1),jQuery("#go_clipboard_stats_datatable").DataTable().columns.adjust().responsive.recalc();break;case"store":go_clipboard_store_datatable(),jQuery("#go_clipboard_store_datatable").DataTable().columns.adjust().responsive.recalc(),go_make_select2_cpt("#go_store_item_select","go_store");break;case"messages":go_clipboard_messages_datatable(),jQuery("#go_clipboard_messages_datatable").DataTable().columns.adjust().responsive.recalc();break;case"activity":go_clipboard_activity_datatable(),jQuery("#go_clipboard_activity_datatable").DataTable().columns.adjust().responsive.recalc(),go_make_select2_cpt("#go_task_select","tasks");break}})),jQuery("#records_tabs").length&&jQuery("#records_tabs").css("margin-left","");var e=localStorage.getItem("go_clipboard_unmatched");1!=e&&"true"!=e||jQuery("#go_unmatched_toggle").prop("checked",!0),go_make_select2_filter("user_go_sections","section"),go_make_select2_filter("user_go_groups","group"),go_make_select2_filter("go_badges","badge"),go_clipboard_stats_datatable(),jQuery("#go_unmatched_toggle").change(function(){jQuery(".go_update_clipboard").addClass("bluepulse"),jQuery(".go_update_clipboard").html('<span class="ui-button-text">Apply Filters<i class="fa fa-filter" aria-hidden="true"></i></span>')}),go_setup_reset_filter_button()});