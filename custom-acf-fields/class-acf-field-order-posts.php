<?php

if( ! class_exists('acf_field_order_posts') ) :

class acf_field_order_posts extends acf_field {
	
	
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
		$this->name = 'order_posts';
		$this->label = __("Order Posts",'acf');
		$this->category = 'relational';
		$this->defaults = array(
			'post_type'			=> array(),
			'taxonomy'			=> array(),
			'min' 				=> 0,
			'max' 				=> 0,
			'filters'			=> array('search', 'post_type', 'taxonomy'),
			'elements' 			=> array(),
			'return_format'		=> 'object'
		);
		$this->l10n = array(
			'min'		=> __("Minimum values reached ( {min} values )",'acf'),
			'max'		=> __("Maximum values reached ( {max} values )",'acf'),
			'loading'	=> __('Loading','acf'),
			'empty'		=> __('No matches found','acf'),
		);
		
		
		// extra
		//add_action('wp_ajax_acf/fields/order_posts/query',			array($this, 'ajax_query'));
		//add_action('wp_ajax_nopriv_acf/fields/order_posts/query',	array($this, 'ajax_query'));
    	
	}
	
	
	function get_post_title( $post, $field, $post_id = 0, $is_search = 0 ) {
		
		// get post_id
		if( !$post_id ) $post_id = acf_get_form_data('post_id');
		
		
		// vars
		$title = acf_get_post_title( $post, $is_search );
		
		
		// featured_image
		if( acf_in_array('featured_image', $field['elements']) ) {
			
			// vars
			$class = 'thumbnail';
			$thumbnail = acf_get_post_thumbnail($post->ID, array(17, 17));
			
			
			// icon
			if( $thumbnail['type'] == 'icon' ) {
				
				$class .= ' -' . $thumbnail['type'];
				
			}
			
			
			// append
			$title = '<div class="' . $class . '">' . $thumbnail['html'] . '</div>' . $title;
			
		}
		

		
		// return
		return $title;
		
	}
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function render_field( $field ) {

		$post_id = get_the_ID();

		// vars
		$values = array();
		$atts = array(
			'id'				=> $field['id'],
			'class'				=> "acf-relationship {$field['class']}",
			'data-min'			=> $field['min'],
			'data-max'			=> $field['max'],
			'data-s'			=> '',
			'data-post_type'	=> '',
			'data-taxonomy'		=> '',
			'data-paged'		=> 1,
		);
		

		
		// Lang
		if( defined('ICL_LANGUAGE_CODE') ) {
		
			$atts['data-lang'] = ICL_LANGUAGE_CODE;
			
		}
		
		
		
		?>
        <div <?php acf_esc_attr_e($atts); ?>>
	
	    <?php acf_hidden_input( array('name' => $field['name'], 'value' => '') ); ?>
	
	
	
	    <div class="selection">
		
		<div class="values" style="width: 100%;">
			<ul class="acf-bl list">
			<?php if( !empty($field['value']) ): 


				$value = $field['value'];

				// if post is not in the taxonomy already, add to end of order 
				if( !in_array($post_id, $value) ) {
					$value[] = $post_id;
				};

				// get posts
				$posts = acf_get_posts(array(
					'post__in' => $value,
					'post_type'	=> $field['post_type']
				));
				
				
			
				// loop
				foreach( $posts as $post ): ?>
					<li>
						<?php acf_hidden_input( array('name' => $field['name'].'[]', 'value' => $post->ID) ); ?>
						<span data-id="<?php echo esc_attr($post->ID); ?>" class="acf-rel-item">
							<?php echo $this->get_post_title( $post, $field ); ?>
							
						</span>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			</ul>
		</div>
	    </div>
        </div>
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
		
		// vars
		$field['min'] = empty($field['min']) ? '' : $field['min'];
		$field['max'] = empty($field['max']) ? '' : $field['max'];

		// term to sort
		acf_render_field_setting( $field, array(
			'label'			=> __('Taxonomy field to sort','acf'),
			'instructions'	=> 'Enter the field name ',
			'type'			=> 'text',
			'name'			=> 'sort_term',
		));
			
		// return_format
		acf_render_field_setting( $field, array(
			'label'			=> __('Return Format','acf'),
			'instructions'	=> '',
			'type'			=> 'radio',
			'name'			=> 'return_format',
			'choices'		=> array(
				'object'		=> __("Post Object",'acf'),
				'id'			=> __("Post ID",'acf'),
			),
			'layout'	=>	'horizontal',
		));	
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is applied to the $value after it is loaded from the db and before it is returned to the template
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
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// force value to array
		$value = acf_get_array( $value );
		
		
		// convert to int
		$value = array_map('intval', $value);
		
		
		// load posts if needed
		if( $field['return_format'] == 'object' ) {
			
			// get posts
			$value = acf_get_posts(array(
				'post__in' => $value,
				'post_type'	=> $field['post_type']
			));
			
		}
		
		
		// return
		return $value;
		
	}
	
	
	/*
	*  validate_value
	*
	*  description
	*
	*  @type	function
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/
	
	function validate_value( $valid, $value, $field, $input ){
		
		// default
		if( empty($value) || !is_array($value) ) {
		
			$value = array();
			
		}
		
		
		// min
		if( count($value) < $field['min'] ) {
		
			$valid = _n( '%s requires at least %s selection', '%s requires at least %s selections', $field['min'], 'acf' );
			$valid = sprintf( $valid, $field['label'], $field['min'] );
			
		}
		
		
		// return		
		return $valid;
		
	}
	

	/**
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
	function load_value( $value, $post_id, $field ) {


    	//$value = get_term_meta( $taxonomy, 'go_order', true);

        //new taxonomy
		$term_id = get_field($field['sort_term']) ;
        $post_slug = get_post_type( $value[0] );
        $term_obj = get_term( $term_id);
        if (!empty($term_obj) && !is_wp_error($term_obj)) {
            $taxonomy = $term_obj->taxonomy;
            if ($taxonomy == 'task_chains') {
                $meta_key = 'go-location_map_order_item';
            } else if ($taxonomy == 'task_menus') {
                $meta_key = 'go-location_top_order_item';
            } else if ($taxonomy == 'task_categories') {
                $meta_key = 'go-location_side_order_item';
            } else if ($taxonomy == 'store_types') {
                $meta_key = 'go-store-location_store_item';
            }
// get all posts that are assigned to this taxonomy term
            $args = array('tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $term_id,)), 'posts_per_page' => -1, 'orderby' => 'meta_value_num', 'order' => 'ASC',

                'meta_key' => $meta_key, 'meta_value' => '', 'post_type' => $post_slug, 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true

            );

            $go_tasks_objs = get_posts($args);

            //$posts = get_posts(array(
            //'post_type' => $post_slug,
            //'tax_query' => array(
            // array(
            //'taxonomy' => $taxonomy,
            //'field' => 'term_id',
            // 'terms' => $term)
            // ))
            // );

            //create an array of the posts
            $value = array();
            foreach ($go_tasks_objs as $go_tasks_obj) {
                $value[] = $go_tasks_obj->ID;
            }

        }
    return $value;
		
	}

	/**
	*  update_value()
	*
	*  This filter is applied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/
   function update_value( $value, $post_id, $field ) {

       return $value;
   }

}


// initialize
acf_register_field_type( 'acf_field_order_posts' );

/**
*Extend the relationship field so that when a taxonomy is changed in another field the
*terms and order are loaded in this field
*/
add_action('wp_ajax_load_order_field_settings', 'ajax_load_order_field_settings');
	
function ajax_load_order_field_settings() {
			// this funtion is called by AJAX to load posts
			// based on term selecteion

			// we can use the acf nonce to verify
			if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
				die();
			}
			$term = 0;
			if (isset($_POST['term'])) {
				$term = intval($_POST['term']);
			}
			//$choices = get_term_meta( $term, 'go_order', true);
			$post_id = $_POST['post_id'];
			$input_name = $_POST['input_name'];

            $post_slug = get_post_type( $post_id );
            $term_obj = get_term( $term);
            $taxonomy =  $term_obj->taxonomy;
            if ($taxonomy == 'task_chains') {
                $meta_key = 'go-location_map_order_item';
            }
            else if ($taxonomy == 'task_menus') {
                $meta_key = 'go-location_top_order_item';
            }
            else if ($taxonomy == 'task_categories') {
                $meta_key = 'go-location_side_order_item';
            }
            else if ($taxonomy == 'store_types') {
                $meta_key = 'go-store-location_store_item';
            }
            // get all posts that are assigned to this taxonomy term
            $args=array(
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $term,
                    )
                ),
                'posts_per_page'   => -1,
                'orderby'          => 'meta_value_num',
                'order'            => 'ASC',

                'meta_key'         => $meta_key,
                'meta_value'       => '',
                'post_type'        => $post_slug,
                'post_mime_type'   => '',
                'post_parent'      => '',
                'author'	   => '',
                'author_name'	   => '',
                'post_status'      => 'publish',
                'suppress_filters' => true

            );

            $posts = get_posts($args);

            //create an array of the posts
            $choices = array();
            foreach ($posts as $post) {
                $choices[] = $post->ID;
            }
            // if post is not in the taxonomy already, add to end of order
            if( !in_array($post_id, $choices) ) {
                $choices[] = $post_id;
            };

			ob_start();
			
			echo "<div class='values' style='width: 100%;'><ul class='acf-bl list'>";
			// loop
			foreach( $choices as $post ): 
				$title = get_the_title( $post );
				echo "<li class><input name='".$input_name."' value = '".$post."' type = 'hidden'><span data-id='".$post."' class='acf-rel-item'> ".$title." " . "</span></li>";
			endforeach; 
			
			echo '</ul></div>';
			
			// stores the contents of the buffer and then clears it
			$buffer = ob_get_contents();

			ob_end_clean();

			echo json_encode(array(
				'html' => $buffer
			));
			exit;
		} // end public function ajax_load_city_field_choices
		

endif; // class_exists check


?>