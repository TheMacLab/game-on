/////
//This sets the correct value(s) when answer value or checkbox is changed
function update_checkbox_value(e){if(console.log("update answer"),jQuery(e).hasClass("go_test_field_input"))//if the change was to the answer
var t=jQuery(e).siblings(".go_test_field_input_checkbox");//set the obj to the checkbox
else//else the change was to the checkbox
var t=e;//set obj to object sent
var _=jQuery(t).prop("type");//the type of checkbox on this answer
console.log(_);var i=jQuery(t).siblings(".go_test_field_input").val();//the answer value
jQuery(t).prop("checked")&&""!=i?//if the answer is not blank, let it be a correct answer
jQuery(t).siblings(".go_test_field_input_checkbox_hidden").attr("value",i)://remove this as a correct answer if not checked
jQuery(t).siblings(".go_test_field_input_checkbox_hidden").removeAttr("value"),"radio"===_&&
//when a radio is checked, others change,
jQuery(t).closest("ul").find(".go_test_field_input_checkbox:not(:checked)").siblings(".go_test_field_input_checkbox_hidden").removeAttr("value")}function update_checkbox_type(e){
//console.log(obj);
var t=jQuery(e).children("option:selected").val();jQuery(e).siblings("ul").children("li").children("input.go_test_field_input_checkbox").attr("type",t),"radio"===t&&(//remove the checkboxes values if switching to radio
jQuery(e).closest("ul").find(".go_test_field_input_checkbox_hidden").removeAttr("value"),jQuery(e).closest("ul").find(".go_test_field_input_checkbox").prop("checked",!1))}function add_block(e){var t=jQuery(e).closest("table").attr("name"),_=parseInt(jQuery(e).next().val());
//console.log(stage);
//number of current questions
//add ++ to value the hidden field under the add block button
jQuery(e).next().value++;
/////
var i="<tr class='go_test_field_input_row' data-block_num='"+_+"'><td><select class='go_test_field_input_select quiz_input' name='go_test_field_select_"+t+"[]' onchange='update_checkbox_type(this);'><option value='radio' class='go_test_field_input_option'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question quiz_input' name='go_test_field_input_question_"+t+"[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_"+t+"_"+_+"' type='radio' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_"+t+"["+_+"][1][]' type='hidden' /><input class='go_test_field_input quiz_input' name='go_test_field_values_"+t+"["+_+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li><li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_"+t+"_"+_+"' type='radio' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_"+t+"["+_+"][1][]' type='hidden' /><input class='go_test_field_input quiz_input' name='go_test_field_values_"+t+"["+_+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li><input class='go_button_add_field go_test_field_add go_test_field_add_input_button' type='button' value='+' onclick='add_field(this);'/></ul><ul><li><input class='go_button_del_field go_test_field_rm_row_button go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block(this);' /><input class='go_test_field_input_count' name='go_test_field_input_count_"+t+"[]' type='hidden' value='2' /></li></ul></td></tr>";jQuery(e).parent().parent().before(i),acf_quiz_no_submit_on_enter()}function remove_block(e){jQuery(e).next().value--,jQuery(e).closest("tr").remove()}function add_field(e){var t=jQuery(e).closest("table").attr("name"),_=jQuery(e).closest(".go_test_field_input_row").data("block_num"),i=jQuery(e).parent("ul").siblings("select").children("option:selected").val(),n=parseInt(jQuery(e).closest("tr").find(".go_test_field_input_count").val());jQuery(e).closest("tr").find(".go_test_field_input_count").val(n+1),
//add the answer
jQuery(e).siblings("li").last().after("<li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_"+t+"_"+_+"' type='"+i+"' onchange='update_checkbox_value(this);' /><input class=' go_test_field_input_checkbox_hidden' name='go_test_field_values_"+t+"["+_+"][1][]' type='hidden' /><input class='go_test_field_input quiz_input' name='go_test_field_values_"+t+"["+_+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /><input class='go_button_del_field go_test_field_rm go_test_field_rm_input_button' type='button' value='x' onclick='remove_field(this);'></li>"),acf_quiz_no_submit_on_enter()}function remove_field(e){var t=parseInt(jQuery(e).closest("tr").find(".go_test_field_input_count").val());jQuery(e).closest("tr").find(".go_test_field_input_count").val(t-1),jQuery(e).parent("li").remove()}function acf_quiz_no_submit_on_enter(){jQuery(".quiz_input").bind("keydown",function(e){var t;13===(e.keyCode||e.which)&&(e.preventDefault(),jQuery(".quiz_input")[jQuery(".quiz_input").index(this)+1].focus())})}!function(_){
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
function i(e){
//$field.doStuff();
acf_quiz_no_submit_on_enter()}void 0!==acf.add_action?(
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
acf.add_action("ready_field/type=quiz",i),acf.add_action("append_field/type=quiz",i)):
/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
_(document).on("acf/setup_fields",function(e,t){
// find all relevant fields
_(t).find('.field[data-field_type="quiz"]').each(function(){
// initialize
i(_(this))})})}(jQuery);