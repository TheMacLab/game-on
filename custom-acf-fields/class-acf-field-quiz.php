<?php


if( ! class_exists('acf_field_quiz') ) :

class acf_field_quiz extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/
	
	function initialize() {
		
		// vars
		$this->name = 'quiz';
		$this->label = __("quiz",'acf');
		$this->category = 'relational';
		$this->defaults = array(
			
		);
    	
	}
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - an array holding all the field's data
	*/
	
	function render_field( $field ) {
		$prefix = $field['prefix'];

            $stage_num = trim($prefix, "]");
            $stage_num = substr($stage_num, strpos($stage_num, ']') + 2);
            if ($stage_num != 'acfcloneindex') {
                $stage_num = (int)$stage_num;
                //$custom = get_post_custom();

                //Get field name (complete string from DB)
                $meta_id = 'go_stages_' . $stage_num . '_quiz';
                $stage_num = $stage_num + 1;


            //Get one letter code of "test type" from args. This is now stage number?
            $ttc = $stage_num;
            }else{
                $ttc = $stage_num;
            }


            //$temp_array = ( ! empty( $custom[ $meta_id ][0] ) ? $custom[ $meta_id ][0] : null );
            $temp_uns = $field['value'];
            if (!empty($temp_uns)) {
                //$temp_uns = unserialize( $temp_array );
                $test_field_input_question = (!empty($temp_uns[0]) ? $temp_uns[0] : null);
                $test_field_input_array = (!empty($temp_uns[1]) ? $temp_uns[1] : null);
                $test_field_select_array = (!empty($temp_uns[2]) ? $temp_uns[2] : null);
                $test_field_block_count = (!empty($temp_uns[3]) ? (int)$temp_uns[3] : null);
                $test_field_input_count = (!empty($temp_uns[4]) ? $temp_uns[4] : null);
            }

            ?>
            <input class="<?php echo esc_attr($field['id']) . '-input' ?>" name="<?php echo esc_attr($field['name']) ?>" value="" type="hidden">

            <table class='go_test_field_table' name="<?php echo $ttc; ?>">
                <?php
                if ($stage_num == 'acfcloneindex'){
                    echo "
                    <input style='display: none;' class='noEnterSubmit' name='go_test_field_new_{$ttc}' type='hidden' value='true' />
                    ";
                }

                //if this quiz exists, then print it out
                if (!empty($test_field_block_count) && !empty($test_field_input_array)) { //for each question (block)
                    for ($i = 0; $i < $test_field_block_count; $i++) {
                        if (!empty($test_field_input_array[$i][0])) {
                            $correct = $test_field_input_array[$i][1];
                            echo "
					<tr class='go_test_field_input_row' data-block_num='{$i}'> 
						<td>
							<select name='go_test_field_select_{$ttc}[]' onchange='update_checkbox_type(this);'>
								<option value='radio' " . (($test_field_select_array[$i] == 'radio') ? 'selected' : '') . ">Multiple Choice</option>
								<option value='checkbox' " . (($test_field_select_array[$i] == 'checkbox') ? 'selected' : '') . ">Multiple Select</option>
							</select>"; //the question type
                            if (!empty($test_field_input_question)) {
                                echo "<br/><br/><input class=' go_test_field_input_question  noEnterSubmit' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='textarea' value=\"" . htmlspecialchars($test_field_input_question[$i], ENT_QUOTES) . "\" />";
                            } else {
                                echo "<br/><br/><input class=' go_test_field_input_question noEnterSubmit' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='textarea' />";
                            }
                            if (!empty($test_field_input_count)) {
                                echo "<ul>";
                                for ($x = 0; $x < $test_field_input_count[$i]; $x++) {
                                    if (in_array($test_field_input_array[$i][0][$x], $correct)){
                                        $checked = "checked";
                                        $value = "value='" . htmlspecialchars($test_field_input_array[$i][0][$x], ENT_QUOTES) . "'";
                                    }
                                    else{
                                        $checked = "";
                                        $value = "";
                                    }
                                    echo "
								<li><input class=' go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value(this);' {$checked} />
								<input class=' go_test_field_input_checkbox_hidden noEnterSubmit' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' {$value}/>
								<input class=' go_test_field_input noEnterSubmit' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value='" . htmlspecialchars($test_field_input_array[$i][0][$x], ENT_QUOTES) . "' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />";
                                    if ($x > 1) {
                                        echo "<input class='go_button_del_field go_test_field_rm noEnterSubmit' type='button' value='x' onclick='remove_field(this);'>";
                                    }
                                    echo "</li>";
                                    if (($x + 1) == $test_field_input_count[$i]) {
                                        echo "<input class='go_button_add_field go_test_field_add noEnterSubmit' type='button' value='+' onclick='add_field(this);'/>";
                                    }
                                }
                                echo "</ul><ul>";
                                if ($i > 0) {
                                    echo "<li><input class=' go_test_field_input_rm_row_button  noEnterSubmit' type='button' value='Remove Question' onclick='remove_block(this);' /></li>";
                                }
                                echo "<li><input class=' go_test_field_input_count noEnterSubmit' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='{$test_field_input_count[ $i]}' /></li></ul>";
                            } else {
                                echo "
						<ul>
							<li><input class=' go_test_field_input_checkbox noEnterSubmit' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class=' go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"" . htmlspecialchars($test_field_input_array[$i][0][0], ENT_QUOTES) . "\" oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li>
							<li><input class=' go_test_field_input_checkbox noEnterSubmit' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class=' go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"" . htmlspecialchars($test_field_input_array[$i][0][1], ENT_QUOTES) . "\" oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li>";
                                echo "</ul><ul><li>";
                                if ($i > 0) {
                                    echo "<input class='go_test_field_input_rm_row_button noEnterSubmit' type='button' value='Remove Question' onclick='remove_block(this);' /></li><li>";
                                }
                                echo "<input class=' go_test_field_input_count noEnterSubmit' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' /></li></ul>";
                            }
                            echo "
						</td>
					</tr>";
                        }
                    }
                } else { //else this is a new quiz and print the template
                    echo "
				<tr class='go_test_field_input_row' data-block_num='0'>
					<td>
						<select class='go_test_field_input_select' name='go_test_field_select_{$ttc}[]' onchange='update_checkbox_type(this);'>
							<option value='radio' >Multiple Choice</option>
							<option value='checkbox'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question noEnterSubmit' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class=' go_test_field_input_checkbox noEnterSubmit' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value(this);' />
								<input class=' go_test_field_input_checkbox_hidden noEnterSubmit' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class=' go_test_field_input noEnterSubmit' name='go_test_field_values_{$ttc}[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox noEnterSubmit' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value(this);' />
								<input class='go_test_field_input_checkbox_hidden noEnterSubmit' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class='go_test_field_input noEnterSubmit' name='go_test_field_values_{$ttc}[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />
							</li>
							<input class='go_test_field_add go_button_add_field noEnterSubmit' type='button' value='+' onclick='add_field(this);'/>
						</ul>
						<ul>
							<li>
								<input  class='go_test_field_input_count noEnterSubmit' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
                }
                ?>
                <tr>
                    <td>
                        <input
                               class='go_button_add_field go_test_field_add_block_button noEnterSubmit' value='Add Question'
                               type='button' onclick='add_block(this);'/>
                        <?php
                        if (!empty($test_field_block_count)) {
                            echo "<input class=' noEnterSubmit' name='go_test_field_block_count_{$ttc}' type='hidden' value='{$test_field_block_count}' />";
                        } else {
                            echo "<input class='go_test_field_block_count noEnterSubmit' name='go_test_field_block_count_{$ttc}' type='hidden' value='1' />";
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <?php

	}
	
	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	function render_field_settings( $field ) {

	}

	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
    function update_value( $value, $post_id, $field ) {
        //$val_uns = unserialize($value);
        //Get the order of the quizes on the page, even if stages are reordered or new stages are added.
        $array = $_POST['acf']['field_5ac134fe12f51'];
        $array = array_keys($array);
        //$i = array_search('blah', array_keys($array));
        $stage_quiz_num = $field['name'];
        $ttc = trim($stage_quiz_num,"go_stages__quiz");
        $ttc = $array[$ttc];
        if (is_integer($ttc)){
            $ttc =$ttc + 1;
        }




        $question_temp 		= ( ! empty( $_POST["go_test_field_input_question_{$ttc}"] )	? $_POST["go_test_field_input_question_{$ttc}"] : null );

        if (!empty($question_temp) ){
            $block_count = count($question_temp);
        }

        $test_temp 			= ( ! empty( $_POST["go_test_field_values_{$ttc}"] ) 			? $_POST["go_test_field_values_{$ttc}"] : null );
        if (!empty($test_temp)) {
            $test_temp = array_values($test_temp);
        }
        $select 			= ( ! empty( $_POST["go_test_field_select_{$ttc}"] ) 			? $_POST["go_test_field_select_{$ttc}"] : null );
        //$block_count 		= ( ! empty( $_POST["go_test_field_block_count_{$ttc}"] ) 		? (int) $_POST["go_test_field_block_count_{$ttc}"] : null );

        $input_count_temp 	= ( ! empty( $_POST["go_test_field_input_count_{$ttc}"] ) 		? $_POST["go_test_field_input_count_{$ttc}"] : null );

        $input_count = array();
        if ( ! empty( $input_count_temp ) ) {
            foreach ( $input_count_temp as $key => $value ) {
                $temp = (int) $input_count_temp[ $key ];
                array_push( $input_count, $temp );
            }
        }

        $question = array();
        if ( ! empty( $question_temp ) && is_array( $question_temp ) ) {
            foreach ( $question_temp as $value ) {
                if ( ! is_null( $value ) && preg_match( "/\S+/", $value ) ) {
                    $question[] = $value;
                }
            }
        } else {
            $question = $question_temp;
        }

        $test = array();
        if ( ! empty( $test_temp ) ) {
            for ( $f = 0; $f < count( $test_temp ); $f++ ) {
                if ( ! empty( $test_temp[ $f ][0] ) && is_array( $test_temp[ $f ][0] ) ) {
                    $temp_input = $test_temp[ $f ][0];
                    foreach ( $temp_input as $value ) {
                        if ( ! is_null( $value ) && preg_match( "/\S+/", $value ) ) {
                            $test[ $f ][0][] = $value;
                        } else {
                            if ( $input_count[ $f ] > 2) {
                                $input_count[ $f ]--;
                            }
                        }
                    }
                }

                if ( ! empty( $test_temp[ $f ][1] ) && is_array( $test_temp[ $f ][1] ) ) {
                    $temp_checked = $test_temp[ $f ][1];
                    foreach ( $temp_checked as $value ) {
                        if ( ! is_null( $value ) && preg_match( "/\S+/", $value ) ) {
                            $test[ $f ][1][] = $value;
                        }
                    }
                }
            }
        }

        $validated_data = array();
        if ( ! empty( $question ) && ! empty( $test ) && ! empty( $select ) &&
            ! empty( $block_count ) && ! empty( $input_count ) ) {

            $validated_data = array( $question, $test, $select, $block_count, $input_count );
        }

        return $validated_data;

      //return $value;

    }



}


// initialize
acf_register_field_type( 'acf_field_quiz' );



endif; // class_exists check



?>