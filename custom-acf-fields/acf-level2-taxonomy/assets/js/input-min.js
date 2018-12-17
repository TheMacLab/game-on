//sets the value that will be returned in the hidden input
function acf_level2_taxonomy_update(a){
//console.log("update");
var e=jQuery(a).children("option:selected").val();
//console.log(val);
jQuery(a).siblings("input").val(e)}!function(t){
/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/
function n(a){var e=a.find(".l2tax").attr("data-taxonomy");
//$field.doStuff();
a.find(".l2tax").select2({ajax:{url:ajaxurl,// AJAX URL is predefined in WordPress admin
dataType:"json",delay:400,// delay in ms while typing when to perform a AJAX search
data:function(a){return{q:a.term,// search query
action:"go_make_taxonomy_dropdown_ajax",// AJAX action for admin-ajax.php
taxonomy:e,is_hier:!0}},processResults:function(a){return{results:a}},cache:!1},minimumInputLength:0,// the minimum of symbols to input before perform a search
multiple:!1,placeholder:"Select",allowClear:!0})}void 0!==acf.add_action?(
/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
acf.add_action("ready_field/type=level2_taxonomy",n),acf.add_action("append_field/type=level2_taxonomy",n)):
/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
t(document).on("acf/setup_fields",function(a,e){
// find all relevant fields
t(e).find('.field[data-field_type="level2_taxonomy"]').each(function(){
// initialize
n(t(this))})})}(jQuery);