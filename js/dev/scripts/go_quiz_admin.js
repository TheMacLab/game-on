
    /////
    //This sets the correct value(s) when answer value or checkbox is changed
    function update_checkbox_value (target) {
        console.log("update answer");
        if (jQuery(target).hasClass('go_test_field_input')) {  //if the change was to the answer
            var obj = jQuery(target).siblings('.go_test_field_input_checkbox');//set the obj to the checkbox
        } else {//else the change was to the checkbox
            var obj = target;//set obj to object sent
        }
        var checkbox_type = jQuery(obj).prop('type'); //the type of checkbox on this answer
        console.log(checkbox_type);
        var input_field_val = jQuery(obj).siblings('.go_test_field_input').val(); //the answer value

        if (jQuery(obj).prop('checked')) {//if this one is checked
            if (input_field_val != '') {//if the answer is not blank, let it be a correct answer
                jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').attr('value', input_field_val);
            } else {//if the answer is blank, do not add it to the list, even if checked
                jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
            }
        } else {//remove this as a correct answer if not checked
            jQuery(obj).siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
        }

        if (checkbox_type === 'radio') {
            //when a radio is checked, others change,
            jQuery(obj).closest("ul").find('.go_test_field_input_checkbox:not(:checked)').siblings('.go_test_field_input_checkbox_hidden').removeAttr('value');
        }
    }

function update_checkbox_type (obj) {
    //console.log(obj);
    var check_or_radio = jQuery(obj).children('option:selected').val();
    jQuery(obj).siblings('ul').children('li').children('input.go_test_field_input_checkbox').attr('type', check_or_radio );
    if (check_or_radio === 'radio') {//remove the checkboxes values if switching to radio
        jQuery(obj).closest("ul").find('.go_test_field_input_checkbox_hidden').removeAttr('value');
        jQuery(obj).closest("ul").find('.go_test_field_input_checkbox').prop('checked', false);
    }
}

function add_block (obj) {
    var stage = jQuery(obj).closest('table').attr("name");
    //console.log(stage);
    //number of current questions
    var block_num = parseInt(jQuery(obj).next().val());
    //add ++ to value the hidden field under the add block button
    jQuery(obj).next().value++;
    /////
    var field_block = "<tr class='go_test_field_input_row' data-block_num='" + block_num + "'><td><select class='go_test_field_input_select' name='go_test_field_select_" + stage + "[]' onchange='update_checkbox_type(this);'><option value='radio' class='go_test_field_input_option'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question' name='go_test_field_input_question_" + stage + "[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_" + stage + "_" + block_num + "' type='radio' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_" + stage + "[" + block_num + "][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values_" + stage + "[" + block_num + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li><li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_" + stage + "_" + block_num + "' type='radio' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_" + stage + "[" + block_num + "][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values_" + stage + "[" + block_num + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li><input class='go_button_add_field go_test_field_add go_test_field_add_input_button' type='button' value='+' onclick='add_field(this);'/></ul><ul><li><input class='go_button_del_field go_test_field_rm_row_button go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block(this);' /><input class='go_test_field_input_count' name='go_test_field_input_count_" + stage + "[]' type='hidden' value='2' /></li></ul></td></tr>";
    jQuery(obj).parent().parent().before(field_block);
}

function remove_block (obj) {
    jQuery(obj).next().value--;
    jQuery(obj).closest('tr').remove();
}

function add_field(obj) {
    var stage = jQuery(obj).closest('table').attr("name");
    var block_num = jQuery(obj).closest('.go_test_field_input_row').data("block_num");//the question #
    var block_type = jQuery(obj).parent('ul').siblings('select').children('option:selected').val(); //radio or checkbox

    //Set the value of the count of the answers
    var count_ans = parseInt( jQuery(obj).closest('tr').find('.go_test_field_input_count').val() );
    jQuery(obj).closest('tr').find('.go_test_field_input_count').val(count_ans + 1);
    //add the answer
    jQuery(obj).siblings('li').last().after("<li><input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_" + stage + "_" + block_num + "' type='" + block_type + "' onchange='update_checkbox_value(this);' /><input class=' go_test_field_input_checkbox_hidden' name='go_test_field_values_" + stage + "[" + block_num + "][1][]' type='hidden' /><input class='go_test_field_input' name='go_test_field_values_" + stage + "[" + block_num + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /><input class='go_button_del_field go_test_field_rm go_test_field_rm_input_button' type='button' value='x' onclick='remove_field(this);'></li>");
}

function remove_field (obj) {
    var count_ans = parseInt( jQuery(obj).closest('tr').find('.go_test_field_input_count').val() );
    jQuery(obj).closest('tr').find('.go_test_field_input_count').val(count_ans - 1);
    jQuery(obj).parent('li').remove();
}


