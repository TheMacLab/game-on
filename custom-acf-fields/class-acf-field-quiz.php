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
		$stage_num = trim($prefix,"]");
        $stage_num = substr($stage_num, strpos($stage_num, ']')+ 2);
		//$custom = get_post_custom();

		//Get field name (complete string from DB)
		$meta_id = 'go_stages_'.$stage_num.'_quiz';
		$stage_num = $stage_num + 1;

		//Get one letter code of "test type" from args. This is now stage number?
		$ttc = $stage_num;

        //$temp_array = ( ! empty( $custom[ $meta_id ][0] ) ? $custom[ $meta_id ][0] : null );
        $temp_uns = $field['value'];
        if ( ! empty( $temp_uns ) ) {
            //$temp_uns = unserialize( $temp_array );
            $test_field_input_question = ( ! empty( $temp_uns[0] ) ? $temp_uns[0] : null );
            $test_field_input_array = ( ! empty( $temp_uns[1] ) ? $temp_uns[1] : null );
            $test_field_select_array = ( ! empty( $temp_uns[2] ) ? $temp_uns[2] : null );
            $test_field_block_count = ( ! empty( $temp_uns[3] ) ? (int) $temp_uns[3] : null );
            $test_field_input_count = ( ! empty( $temp_uns[4] ) ? $temp_uns[4] : null );
        }

        ?>
        <input  class="<?php echo esc_attr($field['id']) . '-input' ?>" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" type="hidden">

        <table id='go_test_field_table_<?php echo $ttc; ?>' class='go_test_field_table'>
            <?php
            if ( ! empty( $test_field_block_count ) && ! empty( $test_field_input_array ) ) {
                for ( $i = 0; $i < $test_field_block_count; $i++ ) {
                    if ( ! empty( $test_field_input_array[ $i ][0] ) ) {
                        echo "
					<tr id='go_test_field_input_row_{$ttc}_{$i}' class='go_test_field_input_row_{$ttc} go_test_field_input_row'>
						<td>
							<select id='go_test_field_select_{$ttc}_{$i}' class='go_test_field_input_select_{$ttc}' name='go_test_field_select_{$ttc}[]' onchange='update_checkbox_type_{$ttc}(this);'>
								<option value='radio' class='go_test_field_input_option_{$ttc}' ".( ( $test_field_select_array[ $i ] == 'radio' ) ? 'selected' : '' ).">Multiple Choice</option>
								<option value='checkbox' class='go_test_field_input_option_{$ttc}' ".( ( $test_field_select_array[ $i ] == 'checkbox' ) ? 'selected' : '' ).">Multiple Select</option>
							</select>";
                        if ( ! empty( $test_field_input_question ) ) {
                            echo "<br/><br/><input class='go_test_field_input_question_{$ttc} go_test_field_input_question' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' value=\"".htmlspecialchars( $test_field_input_question[ $i ], ENT_QUOTES )."\" />";
                        } else {
                            echo "<br/><br/><input class='go_test_field_input_question_{$ttc} go_test_field_input_question' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' />";
                        }
                        if ( ! empty( $test_field_input_count ) ) {
                            echo "<ul>";
                            for ( $x = 0; $x < $test_field_input_count[ $i ]; $x++ ) {
                                echo "
								<li><input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value_{$ttc}(this);' />
								<input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' />
								<input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"".htmlspecialchars( $test_field_input_array[ $i ][0][ $x ], ENT_QUOTES )."\" oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' />";
                                if ( $x > 1 ) {
                                    echo "<input class='go_button_del_field go_test_field_rm go_test_field_rm_input_button_{$ttc}' type='button' value='x' onclick='remove_field_{$ttc}(this);'>";
                                }
                                echo "</li>";
                                if ( ( $x + 1 ) == $test_field_input_count[ $i ] ) {
                                    echo "<input class='go_button_add_field go_test_field_add go_test_field_add_input_button_{$ttc}' type='button' value='+' onclick='add_field_{$ttc}(this);'/>";
                                }
                            }
                            echo "</ul><ul>";
                            if ( $i > 0 ) {
                                echo "<li><input class='go_button_del_field go_test_field_rm_row_button_{$ttc} go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_{$ttc}(this);' /></li>";
                            }
                            echo "<li><input class='go_test_field_input_count_{$ttc}' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='{$test_field_input_count[ $i]}' /></li></ul>";
                        } else {
                            echo "
						<ul>
							<li><input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value_{$ttc}(this);' /><input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"".htmlspecialchars( $test_field_input_array[ $i ][0][0], ENT_QUOTES )."\" oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' /></li>
							<li><input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='go_test_field_input_checkbox_{$ttc}_{$i}' type='{$test_field_select_array[ $i]}' onchange='update_checkbox_value_{$ttc}(this);' /><input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[{$i}][1][]' type='hidden' /><input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[{$i}][0][]' placeholder='Enter an answer!' type='text' value=\"".htmlspecialchars( $test_field_input_array[ $i ][0][1], ENT_QUOTES )."\" oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' /></li>";
                            echo "</ul><ul><li>";
                            if ( $i > 0 ) {
                                echo "<input class='go_button_del_field go_test_field_rm_row_button_{$ttc} go_test_field_input_rm_row_button' type='button' value='Remove' onclick='remove_block_{$ttc}(this);' /></li><li>";
                            }
                            echo "<input class='go_test_field_input_count_{$ttc}' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' /></li></ul>";
                        }
                        echo "
						</td>
					</tr>";
                    }
                }
            } else {
                echo "
				<tr id='go_test_field_input_row_{$ttc}_0' class='go_test_field_input_row_{$ttc} go_test_field_input_row'>
					<td>
						<select id='go_test_field_select_{$ttc}_0' class='go_test_field_input_select_{$ttc}' name='go_test_field_select_{$ttc}[]' onchange='update_checkbox_type_{$ttc}(this);'>
							<option value='radio' class='go_test_field_input_option_{$ttc}'>Multiple Choice</option>
							<option value='checkbox' class='go_test_field_input_option_{$ttc}'>Multiple Select</option>
						</select>
						<br/><br/>
						<input class='go_test_field_input_question_{$ttc} go_test_field_input_question' name='go_test_field_input_question_{$ttc}[]' placeholder='Shall We Play a Game?' type='text' />
						<ul>
							<li>
								<input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value_{$ttc}(this);' />
								<input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[0][0][]' placeholder='Yes' type='text' oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' />
							</li>
							<li>
								<input class='go_test_field_input_checkbox_{$ttc} go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_{$ttc}_0' type='radio' onchange='update_checkbox_value_{$ttc}(this);' />
								<input class='go_test_field_input_checkbox_hidden_{$ttc}' name='go_test_field_values_{$ttc}[0][1][]' type='hidden' />
								<input class='go_test_field_input_{$ttc} go_test_field_input' name='go_test_field_values_{$ttc}[0][0][]' placeholder='No' type='text' oninput='update_checkbox_value_{$ttc}(this);' oncut='update_checkbox_value_{$ttc}(this);' onpaste='update_checkbox_value_{$ttc}(this);' />
							</li>
							<input class='go_button_add_field go_test_field_add go_test_field_add_input_button_{$ttc}' type='button' value='+' onclick='add_field_{$ttc}(this);'/>
						</ul>
						<ul>
							<li>
								<input class='go_test_field_input_count_{$ttc}' name='go_test_field_input_count_{$ttc}[]' type='hidden' value='2' />
							</li>
						</ul>
					</td>
				</tr>
			";
            }
            ?>
            <tr>
                <td>
                    <input id='go_test_field_add_block_button_<?php echo $ttc; ?>' class='go_button_add_field go_test_field_add_block_button' value='Add Question' type='button' onclick='add_block_<?php echo $ttc; ?>(this);' />
                    <?php
                    if ( ! empty( $test_field_block_count ) ) {
                        echo "<input id='go_test_field_block_count_{$ttc}' name='go_test_field_block_count_{$ttc}' type='hidden' value='{$test_field_block_count}' />";
                    } else {
                        echo "<input id='go_test_field_block_count_{$ttc}' name='go_test_field_block_count_{$ttc}' type='hidden' value='1' />";
                    }
                    ?>
                </td>
            </tr>
        </table>
        <script type='text/javascript'>
            var block_num_<?php echo $ttc; ?> = 0;
            var block_type_<?php echo $ttc; ?> = 'radio';
            var input_num_<?php echo $ttc; ?> = 0;
            var block_count_<?php echo $ttc; ?> = <?php echo ( ! empty( $test_field_block_count ) ? $test_field_block_count : 1); ?>;

            var test_field_select_array_<?php echo $ttc; ?> = new Array(
                <?php
                if ( ! empty( $test_field_block_count ) ) {
                    for ( $i = 0; $i < $test_field_block_count; $i++ ) {
                        if ( ! empty( $test_field_select_array[ $i ] ) ) {
                            echo '"'.ucwords( $test_field_select_array[ $i ] ).'"';
                            if ( ( $i + 1 ) !== $test_field_block_count &&
                                ! empty( $test_field_select_array[ $i + 1 ] ) ) {
                                echo ', ';
                            }
                        }
                    }
                }
                ?>
            );
            var test_field_checked_array_<?php echo $ttc; ?> = [
                <?php
                if ( ! empty( $test_field_block_count ) ) {
                    for ( $x = 0; $x < $test_field_block_count; $x++ ) {
                        echo "[";
                        if ( ! empty( $test_field_input_array[ $x ][0] ) && ! empty( $test_field_input_array[ $x ][1] ) ) {
                            $intersection = array_intersect( $test_field_input_array[ $x ][0], $test_field_input_array[ $x ][1] );
                            $checked_intersection = array_values( $intersection );
                            for ( $i = 0; $i < count( $checked_intersection ); $i++ ) {

                                // $test_field_input_array[ $x][0] contains raw strings, the test field data isn't encoded
                                // when it's saved.
                                echo '"'.addslashes( $checked_intersection[ $i ] ).'"';
                                if ( ( $i ) < count( $checked_intersection ) ) {
                                    echo ", ";
                                }
                            }
                        }
                        echo "]";
                        if ( ( $x + 1 ) < $test_field_block_count ) {
                            echo ", ";
                        }
                    }
                }
                ?>
            ];
            for ( var i = 0; i < test_field_select_array_<?php echo $ttc; ?>.length; i++ ) {
                var test_field_with_select_value = '#go_test_field_select_<?php echo $ttc; ?>_'+i+' .go_test_field_input_option_<?php echo $ttc; ?>:contains(\'' + test_field_select_array_<?php echo $ttc; ?>[ i ]+'\' )';
                jQuery(test_field_with_select_value).attr( 'selected', true );
            }
            for ( var x = 0; x < block_count_<?php echo $ttc; ?>; x++ ) {
                if ( test_field_checked_array_<?php echo $ttc; ?>.length !== 0 ) {
                    for ( var z = 0; z < test_field_checked_array_<?php echo $ttc; ?>[ x ].length; z++ ) {

                        // Looping through all the test fields in a row is neccessary, since checking for inputs with a 'value'
                        // attribute containing one or more HTML tags doesn't return the input (it returns the HTML element
                        // inside the 'value' attribute, which doesn't contain a reference to it's parent node).
                        jQuery( "tr#go_test_field_input_row_<?php echo $ttc; ?>_" + [ x ] + " .go_test_field_input_<?php echo $ttc; ?>" ).each( function( ind ) {
                            if ( test_field_checked_array_<?php echo $ttc; ?>[ x ][ z ] === this.value ) {
                                jQuery( this ).siblings( '.go_test_field_input_checkbox_<?php echo $ttc; ?>' ).attr( 'checked', true );
                                return false;
                            }
                        });
                    }
                }
            }
            var checkbox_obj_array = jQuery( '.go_test_field_input_checkbox_<?php echo $ttc; ?>' );
            for ( var y = 0; y < checkbox_obj_array.length; y++ ) {
                var next_obj = checkbox_obj_array[ y ].nextElementSibling;
                if ( checkbox_obj_array[ y ].checked ) {
                    var input_obj = next_obj.nextElementSibling.value;
                    jQuery( next_obj ).attr( 'value', input_obj );
                } else {
                    jQuery( next_obj ).removeAttr( 'value' );
                }
            }
            function update_checkbox_value_<?php echo $ttc; ?> ( target ) {
                if ( jQuery( target ).hasClass( 'go_test_field_input_<?php echo $ttc; ?>' ) ) {
                    var obj = jQuery( target ).siblings( '.go_test_field_input_checkbox_<?php echo $ttc; ?>' );
                } else {
                    var obj = target;
                }
                var checkbox_type = jQuery( obj ).prop( 'type' );
                var input_field_val = jQuery( obj ).siblings( '.go_test_field_input_<?php echo $ttc; ?>' ).val();
                if ( checkbox_type === 'radio' ) {
                    var radio_name = jQuery( obj ).prop( 'name' );
                    var radio_checked_str = ".go_test_field_input_checkbox_<?php echo $ttc; ?>[name='" + radio_name + "']:checked";
                    if ( jQuery( obj ).prop( 'checked' ) ) {
                        if ( input_field_val != '' ) {
                            jQuery( radio_checked_str ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).attr( 'value', input_field_val );
                        } else {
                            jQuery( radio_checked_str ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
                        }
                    } else {
                        jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
                    }
                    var radios_not_checked_str = ".go_test_field_input_checkbox_<?php echo $ttc; ?>[name='" + radio_name + "']:not(:checked)";
                    jQuery( radios_not_checked_str ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
                } else {
                    if ( jQuery( obj ).prop( 'checked' ) ) {
                        if ( input_field_val != '' ) {
                            jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).attr( 'value', input_field_val );
                        } else {
                            jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
                        }
                    } else {
                        jQuery( obj ).siblings( '.go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' ).removeAttr( 'value' );
                    }
                }
            }
            function update_checkbox_type_<?php echo $ttc; ?> ( obj ) {
                block_type_<?php echo $ttc; ?> = jQuery( obj ).children( 'option:selected' ).val();
                jQuery( obj ).siblings( 'ul' ).children( 'li' ).children( 'input.go_test_field_input_checkbox_<?php echo $ttc; ?>' ).attr( 'type', block_type_<?php echo $ttc; ?> );
            }
            function add_block_<?php echo $ttc; ?> ( obj ) {
                block_num_<?php echo $ttc; ?> = jQuery( obj ).parents( 'tr' ).siblings( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).length;
                jQuery( '#go_test_field_block_count_<?php echo $ttc; ?>' ).attr( 'value', ( block_num_<?php echo $ttc; ?> + 1 ) );
                var field_block = "<tr id='go_test_field_input_row_<?php echo $ttc; ?>_"+block_num_<?php echo $ttc; ?> + "' class='go_test_field_input_row_<?php echo $ttc; ?> go_test_field_input_row'><td><select id='go_test_field_select_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?> + "' class='go_test_field_input_select_<?php echo $ttc; ?>' name='go_test_field_select_<?php echo $ttc; ?>[]' onchange='update_checkbox_type_<?php echo $ttc; ?>(this);'><option value='radio' class='go_test_field_input_option_<?php echo $ttc; ?>'>Multiple Choice</option><option value='checkbox' class='go_test_field_input_option_<?php echo $ttc; ?>'>Multiple Select</option></select><br/><br/><input class='go_test_field_input_question_<?php echo $ttc; ?> go_test_field_input_question' name='go_test_field_input_question_<?php echo $ttc; ?>[]' placeholder='Shall We Play a Game?' type='text' /><ul><li><input class='go_test_field_input_checkbox_<?php echo $ttc; ?> go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?>+"' type='" + block_type_<?php echo $ttc; ?> + "' onchange='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][1][]' type='hidden' /><input class='go_test_field_input_<?php echo $ttc; ?> go_test_field_input' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_<?php echo $ttc; ?>(this);' oncut='update_checkbox_value_<?php echo $ttc; ?>(this);' onpaste='update_checkbox_value_<?php echo $ttc; ?>(this);' /></li><li><input class='go_test_field_input_checkbox_<?php echo $ttc; ?> go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?> + "' type='" + block_type_<?php echo $ttc; ?> + "' onchange='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][1][]' type='hidden' /><input class='go_test_field_input_<?php echo $ttc; ?> go_test_field_input' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?> + "][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_<?php echo $ttc; ?>(this);' oncut='update_checkbox_value_<?php echo $ttc; ?>(this);' onpaste='update_checkbox_value_<?php echo $ttc; ?>(this);' /></li><input class='go_button_add_field go_test_field_add go_test_field_add_input_button_<?php echo $ttc; ?>' type='button' value='+' onclick='add_field_<?php echo $ttc; ?>(this);'/></ul><ul><li><input class='go_button_del_field go_test_field_rm_row_button_<?php echo $ttc; ?> go_test_field_input_rm_row_button' type='button' value='Remove' style='margin-left: -2px;' onclick='remove_block_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_count_<?php echo $ttc; ?>' name='go_test_field_input_count_<?php echo $ttc; ?>[]' type='hidden' value='2' /></li></ul></td></tr>";
                jQuery( obj ).parent().parent().before( field_block );
            }
            function remove_block_<?php echo $ttc; ?> ( obj ) {
                block_num_<?php echo $ttc; ?> = jQuery( obj ).parents( 'tr' ).siblings( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).length;
                jQuery( '#go_test_field_block_count_<?php echo $ttc; ?>' ).attr( 'value', ( block_num_<?php echo $ttc; ?> - 1 ) );
                jQuery( obj ).parents( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).remove();
            }
            function add_field_<?php echo $ttc; ?> ( obj ) {
                input_num_<?php echo $ttc; ?> = jQuery( obj ).siblings( 'li' ).length + 1;
                var block_id = jQuery( obj ).parents( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).first().attr( 'id' );
                block_num_<?php echo $ttc; ?> = block_id.split( 'go_test_field_input_row_<?php echo $ttc; ?>_' ).pop();
                block_type_<?php echo $ttc; ?> = jQuery( obj ).parent( 'ul' ).siblings( 'select' ).children( 'option:selected' ).val();
                jQuery( obj ).parent( 'ul' ).siblings( 'ul' ).children( 'li' ).children( '.go_test_field_input_count_<?php echo $ttc; ?>' ).attr( 'value', input_num_<?php echo $ttc; ?> );
                jQuery( obj ).siblings( 'li' ).last().after( "<li><input class='go_test_field_input_checkbox_<?php echo $ttc; ?> go_test_field_input_checkbox' name='unused_go_test_field_input_checkbox_<?php echo $ttc; ?>_" + block_num_<?php echo $ttc; ?> + "' type='" + block_type_<?php echo $ttc; ?> + "' onchange='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_test_field_input_checkbox_hidden_<?php echo $ttc; ?>' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?>+"][1][]' type='hidden' /><input class='go_test_field_input_<?php echo $ttc; ?> go_test_field_input' name='go_test_field_values_<?php echo $ttc; ?>[" + block_num_<?php echo $ttc; ?>+"][0][]' placeholder='Enter an answer!' type='text' style='margin: 0 5px 0 9px !important;' oninput='update_checkbox_value_<?php echo $ttc; ?>(this);' oncut='update_checkbox_value_<?php echo $ttc; ?>(this);' onpaste='update_checkbox_value_<?php echo $ttc; ?>(this);' /><input class='go_button_del_field go_test_field_rm go_test_field_rm_input_button_<?php echo $ttc; ?>' type='button' value='x' onclick='remove_field_<?php echo $ttc; ?>(this);'></li>" );
            }
            function remove_field_<?php echo $ttc; ?> ( obj ) {
                jQuery( obj ).parents( 'tr.go_test_field_input_row_<?php echo $ttc; ?>' ).find( 'input.go_test_field_input_count_<?php echo $ttc; ?>' )[0].value--;
                jQuery( obj ).parent( 'li' ).remove();
            }
        </script>
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
        $val_uns = unserialize($value);

        $stage_quiz_num = $field['name'];
        $ttc = trim($stage_quiz_num,"stages__quiz");
        $ttc = (int)$ttc;
        $ttc =$ttc + 1;

        $question_temp 		= ( ! empty( $_POST["go_test_field_input_question_{$ttc}"] )	? $_POST["go_test_field_input_question_{$ttc}"] : null );
        $block_count        = count($question_temp);
        $test_temp 			= ( ! empty( $_POST["go_test_field_values_{$ttc}"] ) 			? $_POST["go_test_field_values_{$ttc}"] : null );
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