(function($){
	
	
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
	
	function initialize_field( $field ) {
        var taxonomy = $field.find(".l2tax").attr("data-taxonomy");
        //$field.doStuff();
        $field.find(".l2tax").select2({
            ajax: {
                url: ajaxurl, // AJAX URL is predefined in WordPress admin
                dataType: 'json',
                delay: 400, // delay in ms while typing when to perform a AJAX search
                data: function (params) {
                    return {
                        q: params.term, // search query
                        action: 'go_make_taxonomy_dropdown_ajax', // AJAX action for admin-ajax.php
                        taxonomy: taxonomy,
                    is_hier: true
                };
                },
                processResults: function( data ) {
                    return {
                        results: data
                    };
                },
                cache: false
            },
            minimumInputLength: 0, // the minimum of symbols to input before perform a search
            multiple: false,
            placeholder: "Select",
            allowClear: true
        });

	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
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
		
		acf.add_action('ready_field/type=level2_taxonomy', initialize_field);
		acf.add_action('append_field/type=level2_taxonomy', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="level2_taxonomy"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}
})(jQuery);

//sets the value that will be returned in the hidden input
function acf_level2_taxonomy_update(obj) {
    //console.log("update");
    var val = jQuery(obj).children('option:selected').val();
    //console.log(val);
    jQuery(obj).siblings('input').val(val);
}

