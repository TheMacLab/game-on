//This file is js on the acf pages
//

jQuery(document).ready(function($){
		// make sure acf is loaded, it should be, but just in case
		if (typeof acf == 'undefined') { return; }


		/*
		// extend the acf.ajax object
		// you should probably rename this var
		var myACFextension = acf.model.extend({
			events: {
				// this data-key must match the field key for the term field on the post page where
				// you want to dynamically load the posts when the term is changed
				//on map change
				'change [data-key="field_5a960f468bf8e"] select': '_map_change',
				//on side menu change
				'change [data-key="field_5ab85b545ba4b"] select': '_side_change',
				//on top menu change
				'change [data-key="field_5ab85b0b5ba46"] select': '_top_change',
                //on store location change
                'change [data-key="field_5abde7f980c65"] select': '_store_change',
				// this entry is to cause the city field to be updated when the page is loaded
				//'ready [data-key="field_579376f522130"] select': '_term_change',
			},

            // this is our function that will perform the
            // ajax request when the term value is changed
            _store_change: function(e){


                // clear the order field options
                // the data-key is the field key of the order field on post
                var $select = $('[data-key="field_5abde7f980cc5"] select');
                $select.empty();

                // get the term selection
                var $value = e.$el.val();

                // a lot of the following code is copied directly
                // from ACF and modified for our purpose

                // I assume this tests to see if there is already a request
                // for this and cancels it if there is
                if( this.term_request) {
                    this.term_request.abort();
                }

                // I don't know exactly what it does
                // acf does it so I copied it
                var self = this,
                    data = this.o;

                //set the name of the input in the li
                data.input_name = 'acf[field_5abde7f92fd6a][field_5abde7f964b9f][field_5abde7f980cc5][]';

                // set the ajax action that's set up in php
                data.action = 'load_order_field_settings';

                // set the term value to be submitted
                data.term = $value;

                //set the post ID
                data.post_id = jQuery("#post_ID").val();

                //console.log( post_id );
                // this is another bit I'm not sure about
                // copied from ACF
                data.exists = [];

                // this the request is copied from ACF
                this.term_request = $.ajax({
                    url:		acf.get('ajaxurl'),
                    data:		acf.prepare_for_ajax(data),
                    type:		'post',
                    dataType:	'text',
                    async: true,
                    success: function(raw){

                        // parse the raw response to get the desired JSON
                        var res = {};
                        try {
                            var res = JSON.parse( raw );
                        } catch (e) {
                            res = {
                                html: '',
                            };
                        }

                        $( "#acf-field_5abde7f92fd6a-field_5abde7f964b9f-field_5abde7f980cc5 .values" ).replaceWith( res.html );
                        $( "#acf-field_5abde7f92fd6a-field_5abde7f964b9f-field_5abde7f980cc5 .values .list").sortable({
                            items:					'li',
                            forceHelperSize:		true,
                            forcePlaceholderSize:	true,
                            scroll:					true,
                            update:	function(){

                                $input.trigger('change');

                            }

                        });

                    }
                });
            },


			// this is our function that will perform the
			// ajax request when the term value is changed
			_map_change: function(e){
				
				
				// clear the order field options
				// the data-key is the field key of the order field on post
				var $select = $('[data-key="field_5a960f468bf91"] select');
				$select.empty();
				
				// get the term selection
				var $value = e.$el.val();
                console.log("value: " + $value);
				// a lot of the following code is copied directly 
				// from ACF and modified for our purpose
				
				// I assume this tests to see if there is already a request
				// for this and cancels it if there is
				if( this.term_request) {
					this.term_request.abort();
				}
                console.log("this: " + JSON.stringify(this, null, 2));
				// I don't know exactly what it does
				// acf does it so I copied it
				var self = this,
						data = this.o;

				var data = [];

				//dynamic js
				// when there is a change in connected taxonomy field
				//clear this field and
				//get the field
				//and make sortble
                console.log("this!!: " + JSON.stringify(this, null, 2));

                console.log("data: " + JSON.stringify(data, null, 2));

                //set the name of the input in the li
				data.input_name = 'acf[field_5a960f458bf8c][field_5ab197179d24a][field_5a960f468bf91]';

				// set the ajax action that's set up in php
				data.action = 'load_order_field_settings';

				// set the term value to be submitted
				data.term = $value;

				//set the post ID
				data.post_id = jQuery("#post_ID").val();

				//console.log( post_id );
				// this is another bit I'm not sure about
				// copied from ACF
				data.exists = [];
				
				// this the request is copied from ACF
				this.term_request = $.ajax({
					url:		acf.get('ajaxurl'),
					data:		acf.prepare_for_ajax(data),
					type:		'post',
					dataType:	'text',
					async: true,
					success: function(raw){
					
					// parse the raw response to get the desired JSON
					var res = {};
					try {
						var res = JSON.parse( raw );
					} catch (e) {

						res = {
							html: '',
						};
					}

					$( "#acf-field_5a960f458bf8c-field_5ab197179d24a-field_5a960f468bf91 .values" ).replaceWith( res.html );
					$( "#acf-field_5a960f458bf8c-field_5ab197179d24a-field_5a960f468bf91 .values .list").sortable({
						items:					'li',
						forceHelperSize:		true,
						forcePlaceholderSize:	true,
						scroll:					true,
						update:	function(){
							
							$input.trigger('change');
							
						}

					});
					
					}
				});
			},

			// this is our function that will perform the
			// ajax request when the term value is changed
			_side_change: function(e){
				
				// clear the order field options
				// the data-key is the field key of the order field on post
				var $select = $('[data-key="field_5ab85b545ba4c"] select');
				$select.empty();
				
				// get the term selection
				var $value = e.$el.val();
				
				// a lot of the following code is copied directly 
				// from ACF and modified for our purpose
				
				// I assume this tests to see if there is already a request
				// for this and cancels it if there is
				if( this.term_request) {
					this.term_request.abort();
				}
				
				// I don't know exactly what it does
				// acf does it so I copied it
				var self = this,
						data = this.o;
						
				//set the name of the input in the li
				data.input_name = 'acf[field_5a960f458bf8c][field_5ab85b545ba49][field_5ab85b545ba4c][]';
				 
				// set the ajax action that's set up in php
				data.action = 'load_order_field_settings';

				// set the term value to be submitted
				data.term = $value;

				//set the post ID
				data.post_id = jQuery("#post_ID").val();

				//console.log( post_id );
				// this is another bit I'm not sure about
				// copied from ACF
				data.exists = [];
				
				// this the request is copied from ACF
				this.term_request = $.ajax({
					url:		acf.get('ajaxurl'),
					data:		acf.prepare_for_ajax(data),
					type:		'post',
					dataType:	'text',
					async: true,
					success: function(raw){
					
					// parse the raw response to get the desired JSON
					var res = {};
					try {
						var res = JSON.parse( raw );
					} catch (e) {
						res = {
							html: '',
						};
					}

					$( "#acf-field_5a960f458bf8c-field_5ab85b545ba49-field_5ab85b545ba4c .values" ).replaceWith( res.html );
					$( "#acf-field_5a960f458bf8c-field_5ab85b545ba49-field_5ab85b545ba4c .values .list").sortable({
						items:					'li',
						forceHelperSize:		true,
						forcePlaceholderSize:	true,
						scroll:					true,
						update:	function(){
							
							$input.trigger('change');
							
						}

					});
					
					}
				});
			},

						// this is our function that will perform the
			// ajax request when the term value is changed
			_top_change: function(e){
				
				// clear the order field options
				// the data-key is the field key of the order field on post
				var $select = $('[data-key="field_5ab85b0b5ba47"] select');
				$select.empty();
				
				// get the term selection
				var $value = e.$el.val();
				
				// a lot of the following code is copied directly 
				// from ACF and modified for our purpose
				
				// I assume this tests to see if there is already a request
				// for this and cancels it if there is
				if( this.term_request) {
					this.term_request.abort();
				}
				
				// I don't know exactly what it does
				// acf does it so I copied it
				var self = this,
						data = this.o;
						
				//set the name of the input in the li
				data.input_name = 'acf[field_5a960f458bf8c][field_5ab85b0b5ba44][field_5ab85b0b5ba47][]';
				 
				// set the ajax action that's set up in php
				data.action = 'load_order_field_settings';

				// set the term value to be submitted
				data.term = $value;

				//set the post ID
				data.post_id = jQuery("#post_ID").val();
				
				//console.log( post_id );
				// this is another bit I'm not sure about
				// copied from ACF
				data.exists = [];
				
				// this the request is copied from ACF
				this.term_request = $.ajax({
					url:		acf.get('ajaxurl'),
					data:		acf.prepare_for_ajax(data),
					type:		'post',
					dataType:	'text',
					async: true,
					success: function(raw){
					
					// parse the raw response to get the desired JSON
					var res = {};
					try {
						var res = JSON.parse( raw );
					} catch (e) {
						res = {
							html: '',
						};
					}

					$( "#acf-field_5a960f458bf8c-field_5ab85b0b5ba44-field_5ab85b0b5ba47 .values" ).replaceWith( res.html );
					$( "#acf-field_5a960f458bf8c-field_5ab85b0b5ba44-field_5ab85b0b5ba47 .values .list").sortable({
						items:					'li',
						forceHelperSize:		true,
						forcePlaceholderSize:	true,
						scroll:					true,
						update:	function(){
							
							$input.trigger('change');
							
						}

					});
					
					}
				});
			},
		});
		*/
		// triger the ready action on page load
		//$('[data-key="field_579376f522130"] select').trigger('ready');
    var go_store_toggle = GO_ACF_DATA.go_store_toggle;
    var go_map_toggle = GO_ACF_DATA.go_map_toggle;
    var go_top_menu_toggle = GO_ACF_DATA.go_top_menu_toggle;
    var go_widget_toggle = GO_ACF_DATA.go_widget_toggle;
    var go_gold_toggle = GO_ACF_DATA.go_gold_toggle;
    var go_xp_toggle = GO_ACF_DATA.go_xp_toggle;
    var go_health_toggle = GO_ACF_DATA.go_health_toggle;
    var go_badges_toggle = GO_ACF_DATA.go_badges_toggle;


    if (go_store_toggle == 0){

	}

    if (go_map_toggle == 0){
        jQuery(".go_map").hide();
        jQuery('.acf-th[data-name="map"]').hide();
    }

    if (go_top_menu_toggle == 0){
        jQuery(".go_top_menu").hide();
        jQuery('.acf-th[data-name="top"]').hide();
    }

    if (go_widget_toggle == 0){
        jQuery(".go_widget").hide();
        jQuery('.acf-th[data-name="side"]').hide();
    }

    if (go_gold_toggle == 0){
        jQuery(".go_gold").hide();
        jQuery('.acf-th[data-name="gold"]').hide();
    }

    if (go_xp_toggle == 0){
        jQuery(".go_xp").hide();
        jQuery('.acf-th[data-name="xp"]').hide();
    }

    if (go_health_toggle == 0){
        jQuery(".go_health").hide();
        jQuery('.acf-th[data-name="health"]').hide();
    }

    if (go_badges_toggle == 0){
        jQuery(".go_badges").hide();
        jQuery('option[value="go_badge_lock"]').hide();
    }



});


