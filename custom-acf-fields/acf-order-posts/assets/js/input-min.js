!function(o){
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
function d(e){console.log("$field: "+JSON.stringify(e,null,2)),
//$field.doStuff();
e.find(".list").sortable({items:"li",forceHelperSize:!0,forcePlaceholderSize:!0,scroll:!0})}void 0!==acf.add_action?(
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
acf.add_action("ready_field/type=order_posts",d),acf.add_action("append_field/type=order_posts",d)):
/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
o(document).on("acf/setup_fields",function(e,i){
// find all relevant fields
o(i).find('.field[data-field_type="order_posts"]').each(function(){
// initialize
d(o(this))})})}(jQuery);