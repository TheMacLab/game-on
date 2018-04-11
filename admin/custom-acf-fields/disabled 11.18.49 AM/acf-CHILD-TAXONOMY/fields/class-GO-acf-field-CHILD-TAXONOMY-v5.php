<?php
//get terms with AJAX
//Change name of file and placeholder text
//Fix new term button--refresh terms on add, add tooltip


// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('GO_acf_field_CHILD_TAXONOMY') ) :


class GO_acf_field_CHILD_TAXONOMY extends acf_field {
	
	
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
		
		$this->name = 'CHILD_TAXONOMY';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('CHILD TAXONOMY', 'TEXTDOMAIN');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'relational';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			//'font_size'	=> 14,
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('FIELD_NAME', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'TEXTDOMAIN'),
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
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Font Size','TEXTDOMAIN'),
			'instructions'	=> __('Customise the input font size','TEXTDOMAIN'),
			'type'			=> 'select',
			'name'			=> 'taxonomy',
			'choices'		=> acf_get_taxonomies(),
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
		
		
		/*
		*  Review the data of $field.
		*  This will show what data is available
		
		
		echo '<pre>';
			print_r( $field );
		echo '</pre>';
		*/
		

		$taxonomy_slug = $field['taxonomy'];
		$taxonomy = get_taxonomy( $field['taxonomy'] );

		$term_args_parent=array(
  					'hide_empty' => false,
  					'orderby' => 'order',
  					'order' => 'ASC',
  					'parent' => false,       
				);			

		$terms = get_terms( $taxonomy_slug, $term_args_parent);
    	if ( is_a( $terms, 'WP_Error' ) ) {
        	$terms = array();
	    }
	    $object_terms = wp_get_object_terms( $post->ID, $taxonomy_slug, array('fields'=>'ids'));
	    if ( is_a( $object_terms, 'WP_Error' ) ) {
	        $object_terms = array();
	    }
	    //ajax to load terms
	    //new item
	   ?>
	    
		
		

	    
	    <div class="acf-taxonomy-field" data-save="0" data-type="select" data-taxonomy="<?php echo $taxonomy_slug; ?>">
	    <div class="acf-actions -hover">
		<a href="#" class="acf-icon -plus acf-js-tooltip small" data-name="add" title="<?php echo esc_attr($taxonomy->labels->add_new_item); ?>"></a>
		</div>
		<input  id="<?php echo esc_attr($field['id']) . '-input' ?>" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" type="hidden">
		<select class='select2me' id="<?php echo esc_attr($field['id']); ?>" name="<?php echo esc_attr($field['name']); ?>" data-ui="1"  data-multiple="0" data-placeholder="Select" data-allow_null="0" onchange="updateinput();" >
		<?php
    	
    	echo "<option></option>";
    	foreach ( $terms as $term ) {
	            
	            echo "<optgroup label='{$term->name}''>";
	           
	       		
				$term_args_child=array(
  					'hide_empty' => false,
  					'orderby' => 'order',
  					'order' => 'ASC',
  					'parent' => $term->term_id,       
				);			

				$terms_child = get_terms( $taxonomy_slug, $term_args_child);
		    	if ( is_a( $terms_child, 'WP_Error' ) ) {
		        	$terms_child = array();
			    }
			    $child_object_terms = wp_get_object_terms( $post->ID, $taxonomy_slug, array('fields'=>'ids'));
			    if ( is_a( $object_terms, 'WP_Error' ) ) {
			        $child_object_terms = array();
			    }

				foreach ( $terms_child as $term_child ) {
		            if ( $term_child->term_id == $field['value']) {
		                echo "<option value='{$term_child->term_id}' selected='selected' >{$term_child->name}</option>";
		            } else {
		                echo "<option value='{$term_child->term_id}' >{$term_child->name}</option>";
		            }
		        }
	        
	    }
   		 echo "</select><br /></div>"; ?>
   		 <script type="text/javascript">
   		jQuery(document).ready(function() {
   			jQuery('.select2me').select2();
   		});
   		
   		
   		
			function updateinput(){
				var e = document.getElementById("<?php echo esc_attr($field['id']); ?>");
				var catSelected = e.options[e.selectedIndex].value;

				document.getElementById("<?php echo esc_attr($field['id']) . '-input' ?>").value=catSelected;
			}





			(function($){
	
	// taxonomy
	acf.fields.taxonomy = acf.field.extend({
		
		type: 'CHILD_TAXONOMY',
		$el: null,
		
		actions: {
			'ready':	'render',
			'append':	'render',
			'remove':	'remove'
		},
		events: {
			'click a[data-name="add"]': 	'add_term'
		},
		
		focus: function(){
			
			// $el
			this.$el = this.$field.find('.acf-taxonomy-field');
			
			
			// get options
			this.o = acf.get_data(this.$el, {
				save: '',
				type: '',
				taxonomy: ''
			});
			
			
			// extra
			this.o.key = this.$field.data('key');
			
		},
		
		render: function(){
			
			// attempt select2
			var $select = this.$field.find('select');
			
			
			// bail early if no select field
			if( !$select.exists() ) return;
			
			
			// select2 options
			var args = acf.get_data( $select );
			
			
			// customize args
			args = acf.parse_args(args, {
				'pagination':	true,
				'ajax_action':	'acf/fields/taxonomy/query',
				'key':			this.o.key
			});
						
			
			// add select2
			acf.select2.init( $select, args );
			
		},
		
		remove: function(){
			
			// attempt select2
			var $select = this.$field.find('select');
			
			
			// validate ui
			if( !$select.exists() ) return false;
			
			
			// remove select2
			acf.select2.destroy( $select );
			
		},
		
		add_term: function( e ){
			
			// reference
			var self = this;
			
			
			// open popup
			acf.open_popup({
				title:		e.$el.attr('title') || e.$el.data('title'),
				loading:	true,
				height:		220
			});
			
			
			
			// AJAX data
			var ajax_data = acf.prepare_for_ajax({
				action:		'acf/fields/taxonomy/add_term',
				field_key:	this.o.key
			});
			
			
			
			// get HTML
			$.ajax({
				url:		acf.get('ajaxurl'),
				data:		ajax_data,
				type:		'post',
				dataType:	'html',
				success:	function(html){
				
					self.add_term_confirm( html );
					
				}
			});
			
			
		},
		
		add_term_confirm: function( html ){
			
			// reference
			var self = this;
			
			
			// update popup
			acf.update_popup({
				content : html
			});
			
			
			// focus
			$('#acf-popup input[name="term_name"]').focus();
			
			
			// events
			$('#acf-popup form').on('submit', function( e ){
				
				// prevent default
				e.preventDefault();
				
				
				// submit
				self.add_term_submit( $(this ));
				
			});
			
		},
		
		add_term_submit: function( $form ){
			
			// reference
			var self = this;
			
			
			// vars
			var $submit = $form.find('.acf-submit'),
				$name = $form.find('input[name="term_name"]'),
				$parent = $form.find('select[name="term_parent"]');
			
			
			// basic validation
			if( $name.val() === '' ) {
				
				$name.focus();
				return false;
				
			}
			
			
			// show loading
			$submit.find('button').attr('disabled', 'disabled');
			$submit.find('.acf-spinner').addClass('is-active');
			
			
			// vars
			var ajax_data = acf.prepare_for_ajax({
				action:			'acf/fields/taxonomy/add_term',
				field_key:		this.o.key,
				term_name:		$name.val(),
				term_parent:	$parent.exists() ? $parent.val() : 0
			});
			
			
			// save term
			$.ajax({
				url:		acf.get('ajaxurl'),
				data:		ajax_data,
				type:		'post',
				dataType:	'json',
				success:	function( json ){
					
					// vars
					var message = acf.get_ajax_message(json);
					
					
					// success
					if( acf.is_ajax_success(json) ) {
						
						// clear name
						$name.val('');
						
						
						// update term lists
						self.append_new_term( json.data );

					}
					
					
					// message
					if( message.text ) {
						
						$submit.find('span').html( message.text );
						
					}
					
				},
				complete: function(){
					
					// reset button
					$submit.find('button').removeAttr('disabled');
					
					
					// hide loading
					$submit.find('.acf-spinner').removeClass('is-active');
					
					
					// remove message
					$submit.find('span').delay(1500).fadeOut(250, function(){
						
						$(this).html('');
						$(this).show();
						
					});
					
					
					// focus
					$name.focus();
					
				}
			});
			
		},
		
		append_new_term: function( term ){
			
			// vars
			var item = {
				id:		term.term_id,
				text:	term.term_label
			}; 
			
			
			// append to all taxonomy lists
			$('.acf-taxonomy-field[data-taxonomy="' + this.o.taxonomy + '"]').each(function(){
				
				// vars
				var type = $(this).data('type');
				
				
				// bail early if not checkbox/radio
				if( type == 'radio' || type == 'checkbox' ) {
					
					// allow
					
				} else {
					
					return;
					
				}
				
				
				// vars
				var $hidden = $(this).children('input[type="hidden"]'),
					$ul = $(this).find('ul:first'),
					name = $hidden.attr('name');
				
				
				// allow multiple selection
				if( type == 'checkbox' ) {
					
					name += '[]';
						
				}
				
				
				// create new li
				var $li = $([
					'<li data-id="' + term.term_id + '">',
						'<label>',
							'<input type="' + type + '" value="' + term.term_id + '" name="' + name + '" /> ',
							'<span>' + term.term_label + '</span>',
						'</label>',
					'</li>'
				].join(''));
				
				
				// find parent
				if( term.term_parent ) {
					
					// vars
					var $parent = $ul.find('li[data-id="' + term.term_parent + '"]');
				
					
					// update vars
					$ul = $parent.children('ul');
					
					
					// create ul
					if( !$ul.exists() ) {
						
						$ul = $('<ul class="children acf-bl"></ul>');
						
						$parent.append( $ul );
						
					}
					
				}
				
				
				// append
				$ul.append( $li );

			});
			
			
			// append to select
			$('#acf-popup #term_parent').each(function(){
				
				// vars
				var $option = $('<option value="' + term.term_id + '">' + term.term_label + '</option>');
				
				if( term.term_parent ) {
					
					$(this).children('option[value="' + term.term_parent + '"]').after( $option );
					
				} else {
					
					$(this).append( $option );
					
				}
				
			});
			
			
			// set value
			switch( this.o.type ) {
				
				// select
				case 'select':
					
					//this.$el.children('input').select2('data', item);
					
					
					// vars
					var $select = this.$el.children('select');
					acf.select2.add_value($select, term.term_id, term.term_label);
					
					
					break;
				
				case 'multi_select':
					
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
					var $select = this.$el.children('select');
					acf.select2.add_value($select, term.term_id, term.term_label);
					
					
					break;
				
				case 'checkbox':
				case 'radio':
					
					// scroll to view
					var $holder = this.$el.find('.categorychecklist-holder'),
						$li = $holder.find('li[data-id="' + term.term_id + '"]'),
						offet = $holder.get(0).scrollTop + ( $li.offset().top - $holder.offset().top );
					
					
					// check input
					$li.find('input').prop('checked', true);
					
					
					// scroll to bottom
					$holder.animate({scrollTop: offet}, '250');
					break;
				
			}
			
			
		}
	
	});
	
})(jQuery);

		</script>
		<?php

	}
	

	
	
}


// initialize
new GO_acf_field_CHILD_TAXONOMY( $this->settings );


// class_exists check
endif;

?>