<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('go_acf_field_quiz') ) :


class go_acf_field_quiz extends acf_field {
	
	
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
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'quiz';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Quiz', 'go-acf-quiz');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'font_size'	=> 14,
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('FIELD_NAME', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error!', 'go-acf-quiz'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/


	}

	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
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
                    <input style='display: none;' class='' name='go_test_field_new_{$ttc}' type='hidden' value='true' />
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
                            echo "<br/><br/><input class=' go_test_field_input_question quiz_input' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='textarea' value=\"" . htmlspecialchars($test_field_input_question[$i], ENT_QUOTES) . "\" />";
                        } else {
                            echo "<br/><br/><input class=' go_test_field_input_question quiz_input' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='textarea' />";
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
								<input class=' go_test_field_input_checkbox_hidden ' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' {$value}/>
								<input class=' go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value='" . htmlspecialchars($test_field_input_array[$i][0][$x], ENT_QUOTES) . "' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />";
                                if ($x > 1) {
                                    echo "<input class='go_button_del_field go_test_field_rm' type='button' value='x' onclick='remove_field(this);'>";
                                }
                                echo "</li>";
                                if (($x + 1) == $test_field_input_count[$i]) {
                                    echo "<input class='go_button_add_field go_test_field_add' type='button' value='+' onclick='add_field(this);'/>";
                                }
                            }
                            echo "</ul><ul>";
                            if ($i > 0) {
                                echo "<li><input class=' go_test_field_input_rm_row_button' type='button' value='Remove Question' onclick='remove_block(this);' /></li>";
                            }
                            echo "<li><input class=' go_test_field_input_count' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='{$test_field_input_count[ $i]}' /></li></ul>";
                        } else {
                            echo "
						<ul>
							<li><input class=' go_test_field_input_checkbox' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class=' go_test_field_input quiz_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"" . htmlspecialchars($test_field_input_array[$i][0][0], ENT_QUOTES) . "\" oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li>
							<li><input class=' go_test_field_input_checkbox' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value(this);' /><input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class=' go_test_field_input quiz_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"" . htmlspecialchars($test_field_input_array[$i][0][1], ENT_QUOTES) . "\" oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' /></li>";
                            echo "</ul><ul><li>";
                            if ($i > 0) {
                                echo "<input class='go_test_field_input_rm_row_button' type='button' value='Remove Question' onclick='remove_block(this);' /></li><li>";
                            }
                            echo "<input class=' go_test_field_input_count' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' /></li></ul>";
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
						<input class='go_test_field_input_question quiz_input' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class=' go_test_field_input_checkbox ' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value(this);' />
								<input class=' go_test_field_input_checkbox_hidden' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class=' go_test_field_input quiz_input' name='go_test_field_values_{$ttc}[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value(this);' />
								<input class='go_test_field_input_checkbox_hidden' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class='go_test_field_input quiz_input' name='go_test_field_values_{$ttc}[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value(this);' oncut='update_checkbox_value(this);' onpaste='update_checkbox_value(this);' />
							</li>
							<input class='go_test_field_add go_button_add_field' type='button' value='+' onclick='add_field(this);'/>
						</ul>
						<ul>
							<li>
								<input  class='go_test_field_input_count' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' />
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
                            class='go_button_add_field go_test_field_add_block_button' value='Add Question'
                            type='button' onclick='add_block(this);'/>
                    <?php
                    if (!empty($test_field_block_count)) {
                        echo "<input class='' name='go_test_field_block_count_{$ttc}' type='hidden' value='{$test_field_block_count}' />";
                    } else {
                        echo "<input class='go_test_field_block_count' name='go_test_field_block_count_{$ttc}' type='hidden' value='1' />";
                    }
                    ?>
                </td>
            </tr>
        </table>

        <?php

    }
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/


	
	function input_admin_enqueue_scripts() {
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		wp_register_script('go-acf-quiz', "{$url}assets/js/input.js", array('acf-input'), $version);
		wp_enqueue_script('go-acf-quiz');
		
		
		// register & include CSS
		wp_register_style('go-acf-quiz', "{$url}assets/css/input.css", array('acf-input'), $version);
		wp_enqueue_style('go-acf-quiz');
		
	}
	

	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
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
	
	/*
	
	function load_value( $value, $post_id, $field ) {
		
		return $value;
		
	}
	
	*/
	
	
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



    /*
    *  format_value()
    *
    *  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$value (mixed) the value which was loaded from the database
    *  @param	$post_id (mixed) the $post_id from which the value was loaded
    *  @param	$field (array) the field array holding all the field options
    *
    *  @return	$value (mixed) the modified value
    */
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font_size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	/*
	
	function validate_value( $valid, $value, $field, $input ){
		
		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		
		
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','TEXTDOMAIN'),
		}
		
		
		// return
		return $valid;
		
	}
	
	*/
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function load_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	/*
	
	function update_field( $field ) {
		
		return $field;
		
	}	
	
	*/
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// initialize
new go_acf_field_quiz( $this->settings );


// class_exists check
endif;

?>