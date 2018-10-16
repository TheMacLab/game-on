!function(l){acf.fields.order_posts=acf.field.extend({type:"order_posts",$el:null,$input:null,$filters:null,$choices:null,$values:null,actions:{ready:"initialize",append:"initialize"},events:{"keypress [data-filter]":"submit_filter","change [data-filter]":"change_filter","keyup [data-filter]":"change_filter","click .choices .acf-rel-item":"add_item",'click [data-name="remove_item"]':"remove_item"},focus:function(){
// get elements
this.$el=this.$field.find(".acf-relationship"),this.$input=this.$el.children('input[type="hidden"]'),this.$choices=this.$el.find(".choices"),this.$values=this.$el.find(".values"),
// get options
this.o=acf.get_data(this.$el)},initialize:function(){
// reference
var t=this,i=this.$field,s=this.$el,e=this.$input;
// right sortable
this.$values.children(".list").sortable({items:"li",forceHelperSize:!0,forcePlaceholderSize:!0,scroll:!0,update:function(){e.trigger("change")}}),this.$choices.children(".list").scrollTop(0).on("scroll",function(e){
// bail early if no more results
if(!s.hasClass("is-loading")&&!s.hasClass("is-empty")&&Math.ceil(l(this).scrollTop())+l(this).innerHeight()>=l(this).get(0).scrollHeight){
// get paged
var a=s.data("paged")||1;
// update paged
s.data("paged",a+1),
// fetch
t.set("$field",i).fetch()}
// Scrolled to bottom
}),
// fetch
this.fetch()},maybe_fetch:function(){
// reference
var e=this,a=this.$field;
// abort timeout
this.o.timeout&&clearTimeout(this.o.timeout);
// fetch
var t=setTimeout(function(){e.doFocus(a),e.fetch()},300);this.$el.data("timeout",t)},fetch:function(){
// reference
var a=this,t=this.$field;
// add class
this.$el.addClass("is-loading"),
// abort XHR if this field is already loading AJAX data
this.o.xhr&&(this.o.xhr.abort(),this.o.xhr=!1),
// add to this.o
this.o.action="acf/fields/relationship/query",this.o.field_key=t.data("key"),this.o.post_id=acf.get("post_id");
// ready for ajax
var e=acf.prepare_for_ajax(this.o);
// clear html if is new query
1==e.paged&&this.$choices.children(".list").html(""),
// add message
this.$choices.find("ul:last").append('<p><i class="acf-loading"></i> '+acf._e("relationship","loading")+"</p>");
// get results
var i=l.ajax({url:acf.get("ajaxurl"),dataType:"json",type:"post",data:e,success:function(e){a.set("$field",t).render(e)}});
// update el data
this.$el.data("xhr",i)},render:function(e){
// no results?
if(
// remove loading class
this.$el.removeClass("is-loading is-empty"),
// remove p tag
this.$choices.find("p").remove(),!e||!e.results||!e.results.length)
// return
// add class
return this.$el.addClass("is-empty"),void(
// add message
1==this.o.paged&&this.$choices.children(".list").append("<p>"+acf._e("relationship","empty")+"</p>"));
// get new results
var a=l(this.walker(e.results));
// apply .disabled to left li's
this.$values.find(".acf-rel-item").each(function(){a.find('.acf-rel-item[data-id="'+l(this).data("id")+'"]').addClass("disabled")}),
// append
this.$choices.children(".list").append(a);
// merge together groups
var t="",i=null;this.$choices.find(".acf-rel-label").each(function(){if(l(this).text()==t)return i.append(l(this).siblings("ul").html()),void l(this).parent().remove();
// update vars
t=l(this).text(),i=l(this).siblings("ul")})},walker:function(e){
// vars
var a="";
// loop through data
if(l.isArray(e))for(var t in e)a+=this.walker(e[t]);else l.isPlainObject(e)&&(
// optgroup
void 0!==e.children?(a+='<li><span class="acf-rel-label">'+e.text+'</span><ul class="acf-bl">',a+=this.walker(e.children),a+="</ul></li>"):a+='<li><span class="acf-rel-item" data-id="'+e.id+'">'+e.text+"</span></li>");
// return
return a},submit_filter:function(e){
// don't submit form
13==e.which&&e.preventDefault()},change_filter:function(e){
// vars
var a=e.$el.val(),t=e.$el.data("filter");
// Bail early if filter has not changed
this.$el.data(t)!=a&&(
// update attr
this.$el.data(t,a),
// reset paged
this.$el.data("paged",1),
// fetch
e.$el.is("select")?this.fetch():this.maybe_fetch())},add_item:function(e){
// max posts
if(0<this.o.max&&this.$values.find(".acf-rel-item").length>=this.o.max)alert(acf._e("relationship","max").replace("{max}",this.o.max));else{
// can be added?
if(e.$el.hasClass("disabled"))return!1;
// disable
e.$el.addClass("disabled");
// template
var a=["<li>",'<input type="hidden" name="'+this.$input.attr("name")+'[]" value="'+e.$el.data("id")+'" />','<span data-id="'+e.$el.data("id")+'" class="acf-rel-item">'+e.$el.html(),'<a href="#" class="acf-icon -minus small dark" data-name="remove_item"></a>',"</span>","</li>"].join("");
// add new li
this.$values.children(".list").append(a),
// trigger change on new_li
this.$input.trigger("change"),
// validation
acf.validation.remove_error(this.$field)}},remove_item:function(e){
// vars
var a=e.$el.parent(),t=a.data("id");
// remove
a.parent("li").remove(),
// show
this.$choices.find('.acf-rel-item[data-id="'+t+'"]').removeClass("disabled"),
// trigger change on new_li
this.$input.trigger("change")}})}(jQuery),function(d){
// taxonomy
acf.fields.taxonomy2=acf.field.extend({type:"taxonomy2",$el:null,actions:{ready:"render",append:"render",remove:"remove"},events:{'click a[data-name="add"]':"add_term"},focus:function(){
// $el
this.$el=this.$field.find(".acf-taxonomy-field"),
// get options
this.o=acf.get_data(this.$el,{save:"",type:"",taxonomy:""}),
// extra
this.o.key=this.$field.data("key")},render:function(){
// attempt select2
var e=this.$field.find("select");
// bail early if no select field
if(e.exists()){
// select2 options
var a=acf.get_data(e);
// customize args
a=acf.parse_args(a,{pagination:!0,ajax_action:"acf/fields/taxonomy2/query",key:this.o.key}),
// add select2
acf.select2.init(e,a)}},remove:function(){
// attempt select2
var e=this.$field.find("select");
// validate ui
if(!e.exists())return!1;
// remove select2
acf.select2.destroy(e)},add_term:function(e){
// reference
var a=this;
// open popup
acf.open_popup({title:e.$el.attr("title")||e.$el.data("title"),loading:!0,height:220});
// AJAX data
var t=acf.prepare_for_ajax({action:"acf/fields/taxonomy2/add_term",field_key:this.o.key});
// get HTML
d.ajax({url:acf.get("ajaxurl"),data:t,type:"post",dataType:"html",success:function(e){a.add_term_confirm(e)}})},add_term_confirm:function(e){
// reference
var a=this;
// update popup
acf.update_popup({content:e}),
// focus
d('#acf-popup input[name="term_name"]').focus(),
// events
d("#acf-popup form").on("submit",function(e){
// prevent default
e.preventDefault(),
// submit
a.add_term_submit(d(this))})},add_term_submit:function(e){
// reference
var t=this,i=e.find(".acf-submit"),s=e.find('input[name="term_name"]'),a=e.find('select[name="term_parent"]');
// vars
// basic validation
if(""===s.val())return s.focus(),!1;
// show loading
i.find("button").attr("disabled","disabled"),i.find(".acf-spinner").addClass("is-active");
// vars
var l=acf.prepare_for_ajax({action:"acf/fields/taxonomy2/add_term",field_key:this.o.key,term_name:s.val(),term_parent:a.exists()?a.val():0});
// save term
d.ajax({url:acf.get("ajaxurl"),data:l,type:"post",dataType:"json",success:function(e){
// vars
var a=acf.get_ajax_message(e);
// success
acf.is_ajax_success(e)&&(
// clear name
s.val(""),
// update term lists
t.append_new_term(e.data)),
// message
a.text&&i.find("span").html(a.text)},complete:function(){
// reset button
i.find("button").removeAttr("disabled"),
// hide loading
i.find(".acf-spinner").removeClass("is-active"),
// remove message
i.find("span").delay(1500).fadeOut(250,function(){d(this).html(""),d(this).show()}),
// focus
s.focus()}})},append_new_term:function(r){
// vars
var e={id:r.term_id,text:r.term_label};
// append to all taxonomy lists
// set value
switch(d('.acf-taxonomy-field[data-taxonomy="'+this.o.taxonomy+'"]').each(function(){
// vars
var e=d(this).data("type");
// bail early if not checkbox/radio
if("radio"==e||"checkbox"==e){
// vars
var a=d(this).children('input[type="hidden"]'),t=d(this).find("ul:first"),i=a.attr("name");
// allow multiple selection
"checkbox"==e&&(i+="[]");
// create new li
var s=d(['<li data-id="'+r.term_id+'">',"<label>",'<input type="'+e+'" value="'+r.term_id+'" name="'+i+'" /> ',"<span>"+r.term_label+"</span>","</label>","</li>"].join(""));
// find parent
if(r.term_parent){
// vars
var l=t.find('li[data-id="'+r.term_parent+'"]');
// update vars
// create ul
(t=l.children("ul")).exists()||(t=d('<ul class="children acf-bl"></ul>'),l.append(t))}
// append
t.append(s)}}),
// append to select
d("#acf-popup #term_parent").each(function(){
// vars
var e=d('<option value="'+r.term_id+'">'+r.term_label+"</option>");r.term_parent?d(this).children('option[value="'+r.term_parent+'"]').after(e):d(this).append(e)}),this.o.type){
// select
case"select":
//this.$el.children('input').select2('data', item);
// vars
var a=this.$el.children("select");acf.select2.add_value(a,r.term_id,r.term_label);break;case"multi_select":
/*
					// vars
					var $input = this.$el.children('input'),
						value = $input.select2('data') || [];
					
					
					// append
					value.push( item );
					
					
					// update
					$input.select2('data', value);
					
					
*/
// vars
var a=this.$el.children("select");acf.select2.add_value(a,r.term_id,r.term_label);break;case"checkbox":case"radio":
// scroll to view
var t=this.$el.find(".categorychecklist-holder"),i=t.find('li[data-id="'+r.term_id+'"]'),s=t.get(0).scrollTop+(i.offset().top-t.offset().top);
// check input
i.find("input").prop("checked",!0),
// scroll to bottom
t.animate({scrollTop:s},"250");break}}})}(jQuery),function(d){
// taxonomy
acf.fields.taxonomy1=acf.field.extend({type:"taxonomy1",$el:null,actions:{ready:"render",append:"render",remove:"remove"},events:{'click a[data-name="add"]':"add_term"},focus:function(){
// $el
this.$el=this.$field.find(".acf-taxonomy-field"),
// get options
this.o=acf.get_data(this.$el,{save:"",type:"",taxonomy:""}),
// extra
this.o.key=this.$field.data("key")},render:function(){
// attempt select2
var e=this.$field.find("select");
// bail early if no select field
if(e.exists()){
// select2 options
var a=acf.get_data(e);
// customize args
a=acf.parse_args(a,{pagination:!0,ajax_action:"acf/fields/taxonomy1/query",key:this.o.key}),
// add select2
acf.select2.init(e,a)}},remove:function(){
// attempt select2
var e=this.$field.find("select");
// validate ui
if(!e.exists())return!1;
// remove select2
acf.select2.destroy(e)},add_term:function(e){
// reference
var a=this;
// open popup
acf.open_popup({title:e.$el.attr("title")||e.$el.data("title"),loading:!0,height:220});
// AJAX data
var t=acf.prepare_for_ajax({action:"acf/fields/taxonomy1/add_term",field_key:this.o.key});
// get HTML
d.ajax({url:acf.get("ajaxurl"),data:t,type:"post",dataType:"html",success:function(e){a.add_term_confirm(e)}})},add_term_confirm:function(e){
// reference
var a=this;
// update popup
acf.update_popup({content:e}),
// focus
d('#acf-popup input[name="term_name"]').focus(),
// events
d("#acf-popup form").on("submit",function(e){
// prevent default
e.preventDefault(),
// submit
a.add_term_submit(d(this))})},add_term_submit:function(e){
// reference
var t=this,i=e.find(".acf-submit"),s=e.find('input[name="term_name"]'),a=e.find('select[name="term_parent"]');
// vars
// basic validation
if(""===s.val())return s.focus(),!1;
// show loading
i.find("button").attr("disabled","disabled"),i.find(".acf-spinner").addClass("is-active");
// vars
var l=acf.prepare_for_ajax({action:"acf/fields/taxonomy1/add_term",field_key:this.o.key,term_name:s.val(),term_parent:a.exists()?a.val():0});
// save term
d.ajax({url:acf.get("ajaxurl"),data:l,type:"post",dataType:"json",success:function(e){
// vars
var a=acf.get_ajax_message(e);
// success
acf.is_ajax_success(e)&&(
// clear name
s.val(""),
// update term lists
t.append_new_term(e.data)),
// message
a.text&&i.find("span").html(a.text)},complete:function(){
// reset button
i.find("button").removeAttr("disabled"),
// hide loading
i.find(".acf-spinner").removeClass("is-active"),
// remove message
i.find("span").delay(1500).fadeOut(250,function(){d(this).html(""),d(this).show()}),
// focus
s.focus()}})},append_new_term:function(r){
// vars
var e={id:r.term_id,text:r.term_label};
// append to all taxonomy lists
// set value
switch(d('.acf-taxonomy-field[data-taxonomy="'+this.o.taxonomy+'"]').each(function(){
// vars
var e=d(this).data("type");
// bail early if not checkbox/radio
if("radio"==e||"checkbox"==e){
// vars
var a=d(this).children('input[type="hidden"]'),t=d(this).find("ul:first"),i=a.attr("name");
// allow multiple selection
"checkbox"==e&&(i+="[]");
// create new li
var s=d(['<li data-id="'+r.term_id+'">',"<label>",'<input type="'+e+'" value="'+r.term_id+'" name="'+i+'" /> ',"<span>"+r.term_label+"</span>","</label>","</li>"].join(""));
// find parent
if(r.term_parent){
// vars
var l=t.find('li[data-id="'+r.term_parent+'"]');
// update vars
// create ul
(t=l.children("ul")).exists()||(t=d('<ul class="children acf-bl"></ul>'),l.append(t))}
// append
t.append(s)}}),
// append to select
d("#acf-popup #term_parent").each(function(){
// vars
var e=d('<option value="'+r.term_id+'">'+r.term_label+"</option>");r.term_parent?d(this).children('option[value="'+r.term_parent+'"]').after(e):d(this).append(e)}),this.o.type){
// select
case"select":
//this.$el.children('input').select2('data', item);
// vars
var a=this.$el.children("select");acf.select2.add_value(a,r.term_id,r.term_label);break;case"multi_select":
/*
					// vars
					var $input = this.$el.children('input'),
						value = $input.select2('data') || [];
					
					
					// append
					value.push( item );
					
					
					// update
					$input.select2('data', value);
					
					
*/
// vars
var a=this.$el.children("select");acf.select2.add_value(a,r.term_id,r.term_label);break;case"checkbox":case"radio":
// scroll to view
var t=this.$el.find(".categorychecklist-holder"),i=t.find('li[data-id="'+r.term_id+'"]'),s=t.get(0).scrollTop+(i.offset().top-t.offset().top);
// check input
i.find("input").prop("checked",!0),
// scroll to bottom
t.animate({scrollTop:s},"250");break}}})}(jQuery),jQuery(document).ready(function(l){
// make sure acf is loaded, it should be, but just in case
if("undefined"!=typeof acf){
// extend the acf.ajax object
// you should probably rename this var
var e=acf.ajax.extend({events:{
// this data-key must match the field key for the term field on the post page where
// you want to dynamically load the posts when the term is changed
//on map change
'change [data-key="field_5a960f468bf8e"] select':"_map_change",
//on side menu change
'change [data-key="field_5ab85b545ba4b"] select':"_side_change",
//on top menu change
'change [data-key="field_5ab85b0b5ba46"] select':"_top_change",
//on store location change
'change [data-key="field_5abde7f980c65"] select':"_store_change"},
// this is our function that will perform the
// ajax request when the term value is changed
_store_change:function(e){
// clear the order field options
// the data-key is the field key of the order field on post
var a;l('[data-key="field_5abde7f980cc5"] select').empty();
// get the term selection
var t=e.$el.val();
// a lot of the following code is copied directly
// from ACF and modified for our purpose
// I assume this tests to see if there is already a request
// for this and cancels it if there is
this.term_request&&this.term_request.abort();
// I don't know exactly what it does
// acf does it so I copied it
var i=this,s=this.o;
//set the name of the input in the li
s.input_name="acf[field_5abde7f92fd6a][field_5abde7f964b9f][field_5abde7f980cc5][]",
// set the ajax action that's set up in php
s.action="load_order_field_settings",
// set the term value to be submitted
s.term=t,
//set the post ID
s.post_id=jQuery("#post_ID").val(),
//console.log( post_id );
// this is another bit I'm not sure about
// copied from ACF
s.exists=[],
// this the request is copied from ACF
this.term_request=l.ajax({url:acf.get("ajaxurl"),data:acf.prepare_for_ajax(s),type:"post",dataType:"text",async:!0,success:function(e){
// parse the raw response to get the desired JSON
var a={};try{var a=JSON.parse(e)}catch(e){a={html:""}}l("#acf-field_5abde7f92fd6a-field_5abde7f964b9f-field_5abde7f980cc5 .values").replaceWith(a.html),l("#acf-field_5abde7f92fd6a-field_5abde7f964b9f-field_5abde7f980cc5 .values .list").sortable({items:"li",forceHelperSize:!0,forcePlaceholderSize:!0,scroll:!0,update:function(){$input.trigger("change")}})}})},
// this is our function that will perform the
// ajax request when the term value is changed
_map_change:function(e){
// clear the order field options
// the data-key is the field key of the order field on post
var a;l('[data-key="field_5a960f468bf91"] select').empty();
// get the term selection
var t=e.$el.val();
// a lot of the following code is copied directly 
// from ACF and modified for our purpose
// I assume this tests to see if there is already a request
// for this and cancels it if there is
this.term_request&&this.term_request.abort();
// I don't know exactly what it does
// acf does it so I copied it
var i=this,s=this.o;
//set the name of the input in the li
s.input_name="acf[field_5a960f458bf8c][field_5ab197179d24a][field_5a960f468bf91][]",
// set the ajax action that's set up in php
s.action="load_order_field_settings",
// set the term value to be submitted
s.term=t,
//set the post ID
s.post_id=jQuery("#post_ID").val(),
//console.log( post_id );
// this is another bit I'm not sure about
// copied from ACF
s.exists=[],
// this the request is copied from ACF
this.term_request=l.ajax({url:acf.get("ajaxurl"),data:acf.prepare_for_ajax(s),type:"post",dataType:"text",async:!0,success:function(e){
// parse the raw response to get the desired JSON
var a={};try{var a=JSON.parse(e)}catch(e){a={html:""}}l("#acf-field_5a960f458bf8c-field_5ab197179d24a-field_5a960f468bf91 .values").replaceWith(a.html),l("#acf-field_5a960f458bf8c-field_5ab197179d24a-field_5a960f468bf91 .values .list").sortable({items:"li",forceHelperSize:!0,forcePlaceholderSize:!0,scroll:!0,update:function(){$input.trigger("change")}})}})},
// this is our function that will perform the
// ajax request when the term value is changed
_side_change:function(e){
// clear the order field options
// the data-key is the field key of the order field on post
var a;l('[data-key="field_5ab85b545ba4c"] select').empty();
// get the term selection
var t=e.$el.val();
// a lot of the following code is copied directly 
// from ACF and modified for our purpose
// I assume this tests to see if there is already a request
// for this and cancels it if there is
this.term_request&&this.term_request.abort();
// I don't know exactly what it does
// acf does it so I copied it
var i=this,s=this.o;
//set the name of the input in the li
s.input_name="acf[field_5a960f458bf8c][field_5ab85b545ba49][field_5ab85b545ba4c][]",
// set the ajax action that's set up in php
s.action="load_order_field_settings",
// set the term value to be submitted
s.term=t,
//set the post ID
s.post_id=jQuery("#post_ID").val(),
//console.log( post_id );
// this is another bit I'm not sure about
// copied from ACF
s.exists=[],
// this the request is copied from ACF
this.term_request=l.ajax({url:acf.get("ajaxurl"),data:acf.prepare_for_ajax(s),type:"post",dataType:"text",async:!0,success:function(e){
// parse the raw response to get the desired JSON
var a={};try{var a=JSON.parse(e)}catch(e){a={html:""}}l("#acf-field_5a960f458bf8c-field_5ab85b545ba49-field_5ab85b545ba4c .values").replaceWith(a.html),l("#acf-field_5a960f458bf8c-field_5ab85b545ba49-field_5ab85b545ba4c .values .list").sortable({items:"li",forceHelperSize:!0,forcePlaceholderSize:!0,scroll:!0,update:function(){$input.trigger("change")}})}})},
// this is our function that will perform the
// ajax request when the term value is changed
_top_change:function(e){
// clear the order field options
// the data-key is the field key of the order field on post
var a;l('[data-key="field_5ab85b0b5ba47"] select').empty();
// get the term selection
var t=e.$el.val();
// a lot of the following code is copied directly 
// from ACF and modified for our purpose
// I assume this tests to see if there is already a request
// for this and cancels it if there is
this.term_request&&this.term_request.abort();
// I don't know exactly what it does
// acf does it so I copied it
var i=this,s=this.o;
//set the name of the input in the li
s.input_name="acf[field_5a960f458bf8c][field_5ab85b0b5ba44][field_5ab85b0b5ba47][]",
// set the ajax action that's set up in php
s.action="load_order_field_settings",
// set the term value to be submitted
s.term=t,
//set the post ID
s.post_id=jQuery("#post_ID").val(),
//console.log( post_id );
// this is another bit I'm not sure about
// copied from ACF
s.exists=[],
// this the request is copied from ACF
this.term_request=l.ajax({url:acf.get("ajaxurl"),data:acf.prepare_for_ajax(s),type:"post",dataType:"text",async:!0,success:function(e){
// parse the raw response to get the desired JSON
var a={};try{var a=JSON.parse(e)}catch(e){a={html:""}}l("#acf-field_5a960f458bf8c-field_5ab85b0b5ba44-field_5ab85b0b5ba47 .values").replaceWith(a.html),l("#acf-field_5a960f458bf8c-field_5ab85b0b5ba44-field_5ab85b0b5ba47 .values .list").sortable({items:"li",forceHelperSize:!0,forcePlaceholderSize:!0,scroll:!0,update:function(){$input.trigger("change")}})}})}}),a=GO_ACF_DATA.go_store_toggle,t=GO_ACF_DATA.go_map_toggle,i=GO_ACF_DATA.go_top_menu_toggle,s=GO_ACF_DATA.go_widget_toggle,r=GO_ACF_DATA.go_gold_toggle,d=GO_ACF_DATA.go_xp_toggle,n=GO_ACF_DATA.go_health_toggle,c=GO_ACF_DATA.go_badges_toggle;
// triger the ready action on page load
//$('[data-key="field_579376f522130"] select').trigger('ready');
0==t&&(jQuery(".go_map").hide(),jQuery('.acf-th[data-name="map"]').hide()),0==i&&(jQuery(".go_top_menu").hide(),jQuery('.acf-th[data-name="top"]').hide()),0==s&&(jQuery(".go_widget").hide(),jQuery('.acf-th[data-name="side"]').hide()),0==r&&(jQuery(".go_gold").hide(),jQuery('.acf-th[data-name="gold"]').hide()),0==d&&(jQuery(".go_xp").hide(),jQuery('.acf-th[data-name="xp"]').hide()),0==n&&(jQuery(".go_health").hide(),jQuery('.acf-th[data-name="health"]').hide()),0==c&&(jQuery(".go_badges").hide(),jQuery('option[value="go_badge_lock"]').hide())}});