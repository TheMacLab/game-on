<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('go_acf_field_order_posts') ) :


class go_acf_field_order_posts extends acf_field {
	
	
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
		
		$this->name = 'order_posts';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Order Posts', 'acf-order-posts');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'relational';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

        $this->defaults = array(
            'post_type'			=> array(),
            'taxonomy'			=> array(),
            'min' 				=> 0,
            'max' 				=> 0,
            'filters'			=> array('search', 'post_type', 'taxonomy'),
            'elements' 			=> array(),
            'return_format'		=> 'object'
        );

		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('FIELD_NAME', 'error');
		*/

        $this->l10n = array(
            'min'		=> __("Minimum values reached ( {min} values )",'acf'),
            'max'		=> __("Maximum values reached ( {max} values )",'acf'),
            'loading'	=> __('Loading','acf'),
            'empty'		=> __('No matches found','acf'),
        );
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;
		
		
		// do not delete!
    	parent::__construct();
    	
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

        // term to sort
        acf_render_field_setting( $field, array(
            'label'			=> __('Taxonomy field to sort--field ID','acf'),
            'instructions'	=> 'Enter the field ID ',
            'type'			=> 'text',
            'name'			=> 'taxonomy_field',
        ));

        // order field name
        acf_render_field_setting( $field, array(
            'label'			=> __('Order field key name in post meta data','acf'),
            'instructions'	=> 'Name the field meta key for the order field.  E.g. field_name_item ',
            'type'			=> 'text',
            'name'			=> 'order_key_name',
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


        $nonce = wp_create_nonce( 'acf_load_order_field_list' );
        ?>
        <div <?php acf_esc_attr_e($atts); ?>>

            <?php
            acf_hidden_input( array('name' => $field['name'], 'value' => '') );
            ?>



            <div class="selection">

                <div class="values" style="width: 100%;">
                    <?php
                    $term_id = get_field($field['sort_term']) ;
                    $key = $field['key'] ;
                    $name = $field['name'] ;
                    $order_key_name = $field['order_key_name'] ;

                    go_acf_order_posts_list($key, $name, $term_id, $post_id, $order_key_name)

                    ?>
                </div>
            </div>
        </div>


        <?php
        $nonce = wp_create_nonce( 'acf_load_order_field_list' );
        $order_key_name = $field['order_key_name'] ;
        $taxonomy = $field['taxonomy_field'] ;
        //dynamic js
        // when there is a change in connected taxonomy field
        //clear this field and
        //get the field
        //and make sortble
        //$key, $name, $term_id, $post_id
        echo '<script>
console.log("reorder");
                jQuery(".' . $taxonomy . '").change(
                    function() {
                        var term_id =  jQuery(".' . $taxonomy . '").val();
                        var key = "' . $key . '";
                        var post_id = "' . $post_id . '";
                        var name = "' . $name . '";
                        var url = "' . admin_url( 'admin-ajax.php' ) . '";
                        var list_id = "#list_' . $key . '";
                        var order_key_name = "' . $order_key_name . '";
                        var nonce = "' . $nonce . '";
                        console.log("term_id: " + term_id);
                        jQuery.ajax({
                            type: "get",
                            url: url,
                            data: {
                                _ajax_nonce: nonce,
                                action: "acf_load_order_field_list",
                                key: key,
                                post_id: post_id,
                                name: name,
                                term_id: term_id,
                                order_key_name: order_key_name
                            },
                            success: function( res ) {
                                console.log("res: " + res);
                                jQuery(list_id).html(res);
                               return res;
                               
                            }
                        });
                    }
                );
        </script>';


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
		wp_register_script('acf-order-posts', "{$url}assets/js/input.js", array('acf-input'), $version);
		wp_enqueue_script('acf-order-posts');
		
		
		// register & include CSS
		wp_register_style('acf-order-posts', "{$url}assets/css/input.css", array('acf-input'), $version);
		wp_enqueue_style('acf-order-posts');
		
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

    function load_value( $value, $post_id, $field ) {

        //new taxonomy
        $term_id = get_field($field['sort_term']) ;
        $post_slug = get_post_type( null );
        $term_obj = get_term( $term_id);
        if (!empty($term_obj) && !is_wp_error($term_obj)) {
            $taxonomy = $term_obj->taxonomy;
            $meta_key = $field["order_key_name"];
            /*
            //Game on Specific Code
            //CHANGE LINES BELOW TO ADD/CHANGE GAME ON FIELDS
            if ($taxonomy == 'task_chains') {
                $meta_key = 'go-location_map_order_item';
            } else if ($taxonomy == 'store_types') {
                $meta_key = 'go-store-location_store_item';
            }else{
                $meta_key = 'acf-'. $taxonomy . '_order';
            }
            */
            //substitute for universal code
            //$meta_key = 'go-order-posts-item';

            // get all posts that are assigned to this taxonomy term
            $args = array('tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $term_id,)), 'posts_per_page' => -1, 'orderby' => 'meta_value_num', 'order' => 'ASC',

                'meta_key' => $meta_key, 'meta_value' => '', 'post_type' => $post_slug, 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true

            );

            $go_tasks_objs = get_posts($args);

            //create an array of the posts
            $value = array();
            foreach ($go_tasks_objs as $go_tasks_obj) {
                $value[] = $go_tasks_obj->ID;
            }

        }
        return $value;

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
        //this needs Game On Info or the database needs to be changed.

       // $item_order_field = $field['name'] . '_item';

        $meta_key = $field["order_key_name"];
        $term_id = get_post_meta ($post_id, $field['sort_term'], true);


        $term_obj = get_term($term_id);
        if (!empty($term_obj) && !is_wp_error($term_obj)) {
            $taxonomy = $term_obj->taxonomy;
            //$term_order = get_term_meta( $term, 'go_order', true );
            //$order = get_post_meta($post_id, $order_field, true);
            $order = $value;

            if (empty($term_id)) {
                delete_post_meta($post_id, $meta_key);
            } else {
                $i = 0;
                foreach ($order as $item) {
                    // for each post in the value, set term
                    wp_set_post_terms($item, $term_id, $taxonomy);
                    // for each post in the value, set order
                    update_post_meta($item, $meta_key, $i);
                    $i++;
                }
            }
        }
        $value = '';

        return $value;
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



    function format_value( $value, $post_id, $field ) {


        // return
        return $value;

    }




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



    function validate_value( $valid, $value, $field, $input ){
        /*
                // default
                if( empty($value) || !is_array($value) ) {

                    $value = array();

                }


                // min
                if( count($value) < $field['min'] ) {

                    $valid = _n( '%s requires at least %s selection', '%s requires at least %s selections', $field['min'], 'acf' );
                    $valid = sprintf( $valid, $field['label'], $field['min'] );

                }
        */

        // return
        return $valid;

    }





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
new go_acf_field_order_posts( $this->settings );


// class_exists check
endif;


/**
 *Extend the relationship field so that when a taxonomy is changed in another field the
 *terms and order are loaded in this field
 */
function go_acf_order_posts_value($term_id, $order_key_name){
    //term_id
    //new taxonomy
    $value = array();
    $post_slug = get_post_type( null );
    $term_obj = get_term( $term_id);
    if (!empty($term_obj) && !is_wp_error($term_obj)) {
        $taxonomy = $term_obj->taxonomy;

        /*
        //Game on Specific Code
        //CHANGE LINES BELOW TO ADD/CHANGE GAME ON FIELDS
        if ($taxonomy == 'task_chains') {
            $meta_key = 'go-location_map_order_item';
        } else if ($taxonomy == 'store_types') {
            $meta_key = 'go-store-location_store_item';
        }else{
           $meta_key = 'acf-'. $taxonomy . '_order';
        }
        */

        //substitute for universal code
        //$meta_key = 'go-order-posts-item';

        // get all posts that are assigned to this taxonomy term
        $args = array('tax_query' => array(array('taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $term_id,)), 'posts_per_page' => -1, 'orderby' => 'meta_value_num', 'order' => 'ASC',

            'meta_key' => $order_key_name, 'meta_value' => '', 'post_type' => $post_slug, 'post_mime_type' => '', 'post_parent' => '', 'author' => '', 'author_name' => '', 'post_status' => 'publish', 'suppress_filters' => true

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

        foreach ($go_tasks_objs as $go_tasks_obj) {
            $value[] = $go_tasks_obj->ID;
        }

    }
    return $value;
}

function go_acf_order_posts_list($key, $name, $term_id, $post_id, $order_key_name){
    //term_id can be the one from the db on load
    //or from ajax if the term was changed on the page

    $values = go_acf_order_posts_value($term_id, $order_key_name);
    $list_id = "list_" . $key;
    ?>
    <ul id="<?php
    echo $list_id;
    ?>" class="acf-bl list">
    <?php
        //if( !empty($field['value']) ):



        $value = $values;

        // if post is not in the taxonomy already, add to end of order
        if( !in_array($post_id, $value) ) {
            $value[] = $post_id;
        };

        // get posts
        $posts = acf_get_posts(array(
            'post__in' => $value
        ));



        // loop
        foreach( $posts as $post ): ?>
            <li>
                <?php acf_hidden_input( array('name' => $name.'[]', 'value' => $post->ID) ); ?>
                <span data-id="<?php echo esc_attr($post->ID); ?>" class="acf-rel-item">
							<?php echo get_the_title( $post ); ?>

						</span>
            </li>
        <?php endforeach; ?>

    </ul>
<?php
}


function acf_load_order_field_list() {
    // this function is called by AJAX to load posts
    // based on term selection

    // we can use the acf nonce to verify
   // if (!wp_verify_nonce($_POST['nonce'], 'acf_nonce')) {
        // die();
    //}
    check_ajax_referer( 'acf_load_order_field_list' );

   // ob_start();

    //field
    //post_id
    //this
    $key = $_GET['key'];
    $name = $_GET['name'];
    $term_id = $_GET['term_id'];
    $post_id = $_GET['post_id'];
    $order_key_name = $_GET['order_key_name'];
    go_acf_order_posts_list($key, $name, $term_id, $post_id, $order_key_name);

    // stores the contents of the buffer and then clears it
    //$buffer = ob_get_contents();

    //ob_end_clean();

    //echo json_encode(array(
    //    'html' => $buffer
   // ));

    die();
} // end public function ajax_load_city_field_choices



?>