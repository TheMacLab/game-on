jQuery.extend({
    replaceTag: function (currentElem, newTagObj, keepProps) {
        var $currentElem = jQuery(currentElem);
        var i, $newTag = jQuery(newTagObj).clone();
        if (keepProps) {//{{{
            newTag = $newTag[0];
            newTag.className = currentElem.className;
            newTag.id = jQuery(currentElem).attr('id');
            //console.log(curtentAtts);
            jQuery.extend(newTag.classList, currentElem.classList);
            jQuery.extend(newTag.attributes, currentElem.attributes);
        }//}}}
        //console.log($currentElem);
        $currentElem.wrapAll($newTag);
        $currentElem.contents().unwrap();
        // return node; (Error spotted by Frank van Luijn)
        return this; // Suggested by ColeLawrence
    }
});

jQuery.fn.extend({
    replaceTag: function (newTagObj, keepProps) {
        // "return" suggested by ColeLawrence
        return this.each(function() {
            jQuery.replaceTag(this, newTagObj, keepProps);
        });
    }
});
    
    
    /* global inlineEditTax, ajaxurl */




/**
 * Update the term order based on the ajax response
 *
 * @param {type} response
 * @returns {void}
 */
function term_order_update_callback( response ) {
	window.location.reload();
	/*if ( 'children' === response ) {
		window.location.reload();
		return;
	}
	*/

	var changes = jQuery.parseJSON( response ),
		new_pos = changes.new_pos;

	for ( var key in new_pos ) {

		if ( 'next' === key ) {
			continue;
		}

		var inline_key = document.getElementById( 'inline_' + key );

		if ( null !== inline_key && new_pos.hasOwnProperty( key ) ) {
			var dom_order = inline_key.querySelector( '.order' );

			if ( undefined !== new_pos[ key ]['order'] ) {
				if ( null !== dom_order ) {
					dom_order.innerHTML = new_pos[ key ]['order'];
				}

				var dom_term_parent = inline_key.querySelector( '.parent' );
				if ( null !== dom_term_parent ) {
					dom_term_parent.innerHTML = new_pos[ key ]['parent'];
				}

				var term_title     = null,
					dom_term_title = inline_key.querySelector( '.row-title' );
				if ( null !== dom_term_title ) {
					term_title = dom_term_title.innerHTML;
				}

				var dashes = 0;
				while ( dashes < new_pos[ key ]['depth'] ) {
					//term_title = '&mdash; ' + term_title;
					dashes++;
				}

				var dom_row_title = inline_key.parentNode.querySelector( '.row-title' );
				if ( null !== dom_row_title && null !== term_title ) {
					//dom_row_title.innerHTML = term_title;
				}

			} else if ( null !== dom_order ) {
				dom_order.innerHTML = new_pos[ key ];
			}
		}
	}

	if ( changes.next ) {
		jQuery.post( ajaxurl, {
			action:  'reordering_terms',
			id:       changes.next['id'],
			previd:   changes.next['previd'],
			nextid:   changes.next['nextid'],
			start:    changes.next['start'],
			excluded: changes.next['excluded'],
			tax:      taxonomy
		}, term_order_update_callback );
	} else {

		setTimeout( function() {
			jQuery( '.to-row-updating' ).removeClass( 'to-row-updating' );
		}, 500 );

		sortable_terms_table.removeClass( 'to-updating' ).sortable( 'enable' );
	}
}

//////ADDED BY GAME ON

jQuery(document).ready(function(){
	
	//jQuery( '.ui-sortable-handle' ).addClass( 'termRow' );
    var idArray = [];
    //make array of all the term ids
    jQuery("#the-list tr").each(function () {
    	idArray.push(this.id);
    });

    /////pass the entire array and use JSON to parse and set class    	
    	termDivIDs = (idArray);
    	//alert (termID);
        jQuery.ajax({	
		type: "POST",
		//dataType: 'json',
		url : ajax_url,
		
			data: {
				'action':'check_if_top_term',
				'goTermDivIDs' : termDivIDs,
			},
			success:function(status) {	
				//alert(rowID);				
				console.log("success!");
				console.log(status);
				//var array2 = (data);
				//array2.push(data);
				var StatusArray = jQuery.parseJSON(status);
			
				//alert (idArray.length);
				//alert (idArray);
				
				//alert (StatusArray.length);
				//alert (StatusArray);
 				
				//var larray = JsonData.length;

				for (var i = 0; i < idArray.length; i++){
					
					var status = StatusArray[i];
					
					var rowid = ("#" + idArray[i]);
					//alert (divid);
					//alert (status);
					jQuery(rowid).addClass(status);
					//jQuery(rowid).removeAttr('id');
					
					//jQuery( '.child td' ).css( 'display', 'none' );
					//jQuery( '.child th' ).css( 'display', 'none' );
					//jQuery( '.child th' ).css( 'height', '0' );
					
					//alert(rowID);
					//alert(data);
				}
				jQuery(".parent").each(function (index) {
					var currentID = jQuery(this).attr('id');
   					jQuery(this).nextUntil(".parent").andSelf().wrapAll('<li id=' + currentID + ' class="sortset"></li>');
   					jQuery(this).replaceTag('<div>', true);
   					
				});
				jQuery(".parent").each(function (index) {
					var currentID = jQuery(this).attr('id');
   					jQuery(this).nextUntil(".parent").wrapAll('<ul id=' + currentID + ' class="children ulSortable"></ul>');
   					
   					
				});
				//jQuery(".child").each(function (index) {
   					//jQuery(this).wrap("<div class='child_div'></div>");
				//});
				//jQuery("tr").each(function (index) {
   				//	jQuery(this).replaceTag('<li>', true);
				//});
				jQuery('tbody#the-list').replaceTag('<ul>', true);
				jQuery('#the-list').addClass('ulSortable', true);
				jQuery('tr').replaceTag('<li>', true);
				
				
				//jQuery('.sortset').each(function(index) {
   					//var newid = jQuery(this).children(":first").attr("id");
   					//jQuery(this).attr('id', newid);
				//});	
				
				
				
				
//////////////////////////

					var sortable_terms_table = jQuery( '.ulSortable' ),
					taxonomy = jQuery( 'form input[name="taxonomy"]' ).val();

					sortable_terms_table.sortable( {

						// Settings
						items:     '> li',
						cursor: 'move',
						axis:  'y',
						//cancel: '  .inline-edit-row',
						//distance:  2,
						opacity:   0.9,
						//tolerance: 'pointer',
						scroll:    true,
						nested: 'ul',
						tolerance: 'intersect',
						containment: 'parent',
						
	

						/**
						 * Sort start
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						start: function ( e, ui ) {
						sortable_terms_table.sortable('refresh'); 
						sortable_terms_table.sortable( "refreshPositions" );
							if (ui.item.hasClass('sortset')){
								//jQuery(".child").remove(".child");
								//jQuery(".child").css("display","none");
		jQuery.extend({
    replaceTag: function (currentElem, newTagObj, keepProps) {
        var $currentElem = jQuery(currentElem);
        var i, $newTag = jQuery(newTagObj).clone();
        if (keepProps) {//{{{
            newTag = $newTag[0];
            newTag.className = currentElem.className;
            newTag.id = jQuery(currentElem).attr('id');
            //console.log(curtentAtts);
            jQuery.extend(newTag.classList, currentElem.classList);
            jQuery.extend(newTag.attributes, currentElem.attributes);
        }//}}}
        //console.log($currentElem);
        $currentElem.wrapAll($newTag);
        $currentElem.contents().unwrap();
        // return node; (Error spotted by Frank van Luijn)
        return this; // Suggested by ColeLawrence
    }
});

jQuery.fn.extend({
    replaceTag: function (newTagObj, keepProps) {
        // "return" suggested by ColeLawrence
        return this.each(function() {
            jQuery.replaceTag(this, newTagObj, keepProps);
        });
    }
});
    
    
    /* global inlineEditTax, ajaxurl */




/**
 * Update the term order based on the ajax response
 *
 * @param {type} response
 * @returns {void}
 */
function term_order_update_callback( response ) {
	window.location.reload();
	/*if ( 'children' === response ) {
		window.location.reload();
		return;
	}
	*/

	var changes = jQuery.parseJSON( response ),
		new_pos = changes.new_pos;

	for ( var key in new_pos ) {

		if ( 'next' === key ) {
			continue;
		}

		var inline_key = document.getElementById( 'inline_' + key );

		if ( null !== inline_key && new_pos.hasOwnProperty( key ) ) {
			var dom_order = inline_key.querySelector( '.order' );

			if ( undefined !== new_pos[ key ]['order'] ) {
				if ( null !== dom_order ) {
					dom_order.innerHTML = new_pos[ key ]['order'];
				}

				var dom_term_parent = inline_key.querySelector( '.parent' );
				if ( null !== dom_term_parent ) {
					dom_term_parent.innerHTML = new_pos[ key ]['parent'];
				}

				var term_title     = null,
					dom_term_title = inline_key.querySelector( '.row-title' );
				if ( null !== dom_term_title ) {
					term_title = dom_term_title.innerHTML;
				}

				var dashes = 0;
				while ( dashes < new_pos[ key ]['depth'] ) {
					//term_title = '&mdash; ' + term_title;
					dashes++;
				}

				var dom_row_title = inline_key.parentNode.querySelector( '.row-title' );
				if ( null !== dom_row_title && null !== term_title ) {
					//dom_row_title.innerHTML = term_title;
				}

			} else if ( null !== dom_order ) {
				dom_order.innerHTML = new_pos[ key ];
			}
		}
	}

	if ( changes.next ) {
		jQuery.post( ajaxurl, {
			action:  'reordering_terms',
			id:       changes.next['id'],
			previd:   changes.next['previd'],
			nextid:   changes.next['nextid'],
			start:    changes.next['start'],
			excluded: changes.next['excluded'],
			tax:      taxonomy
		}, term_order_update_callback );
	} else {

		setTimeout( function() {
			jQuery( '.to-row-updating' ).removeClass( 'to-row-updating' );
		}, 500 );

		sortable_terms_table.removeClass( 'to-updating' ).sortable( 'enable' );
	}
}

//////ADDED BY GAME ON

jQuery(document).ready(function(){
	
	//jQuery( '.ui-sortable-handle' ).addClass( 'termRow' );
    var idArray = [];
    //make array of all the term ids
    jQuery("#the-list tr").each(function () {
    	idArray.push(this.id);
    });

    /////pass the entire array and use JSON to parse and set class    	
    	termDivIDs = (idArray);
    	//alert (termID);
        jQuery.ajax({	
		type: "POST",
		//dataType: 'json',
		url : ajax_url,
		
			data: {
				'action':'check_if_top_term',
				'goTermDivIDs' : termDivIDs,
			},
			success:function(status) {	
				//alert(rowID);				
				console.log("success!");
				console.log(status);
				//var array2 = (data);
				//array2.push(data);
				var StatusArray = jQuery.parseJSON(status);
			
				//alert (idArray.length);
				//alert (idArray);
				
				//alert (StatusArray.length);
				//alert (StatusArray);
 				
				//var larray = JsonData.length;

				for (var i = 0; i < idArray.length; i++){
					
					var status = StatusArray[i];
					
					var rowid = ("#" + idArray[i]);
					//alert (divid);
					//alert (status);
					jQuery(rowid).addClass(status);
					//jQuery(rowid).removeAttr('id');
					
					//jQuery( '.child td' ).css( 'display', 'none' );
					//jQuery( '.child th' ).css( 'display', 'none' );
					//jQuery( '.child th' ).css( 'height', '0' );
					
					//alert(rowID);
					//alert(data);
				}
				jQuery(".parent").each(function (index) {
					var currentID = jQuery(this).attr('id');
   					jQuery(this).nextUntil(".parent").andSelf().wrapAll('<div id=' + currentID + ' class="sortset"></div>');
   					//jQuery(this).replaceTag('<div>', true);
   					
				});
				jQuery(".parent").each(function (index) {
					var currentID = jQuery(this).attr('id');
   					jQuery(this).nextUntil(".parent").wrapAll('<div id=' + currentID + ' class="children ulSortable"></div	>');
   					
   					
				});
				//jQuery(".child").each(function (index) {
   					//jQuery(this).wrap("<div class='child_div'></div>");
				//});
				//jQuery("tr").each(function (index) {
   				//	jQuery(this).replaceTag('<li>', true);
				//});
				//jQuery('tbody#the-list').replaceTag('<ul>', true);
				//jQuery('#the-list').addClass('ulSortable', true);
				//jQuery('tr').replaceTag('<li>', true);
				
				
				//jQuery('.sortset').each(function(index) {
   					//var newid = jQuery(this).children(":first").attr("id");
   					//jQuery(this).attr('id', newid);
				//});	
				
				
				
				
//////////////////////////

					var sortable_terms_table = jQuery( '.wp-list-table tbody' ),
	taxonomy             = jQuery( 'form input[name="taxonomy"]' ).val();

sortable_terms_table.sortable( {

	// Settings
	items:     '> tr:not(.no-items)',
	cursor:    'move',
	axis:      'y',
	cancel: '  .inline-edit-row',
	distance:  2,
	opacity:   0.9,
	tolerance: 'pointer',
	scroll:    true,
	//nested: 'span',
	//containment: 'parent',
						
	

						/**
						 * Sort start
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						start: function ( e, ui ) {
						sortable_terms_table.sortable('refresh'); 
						sortable_terms_table.sortable( "refreshPositions" );
							if (ui.item.hasClass('sortset')){
								//jQuery(".child").remove(".child");
								//jQuery(".child").css("display","none");
			
							};
							//setTimeout(myFunction, 3000);
		
							//function myFunction(){
			
								if ( typeof ( inlineEditTax ) !== 'undefined' ) {
								inlineEditTax.revert();
							//}
							ui.placeholder.height( ui.item.height() );
							ui.item.parent().parent().addClass( 'dragging' );
							}		
						},

						/**
						 * Sort dragging
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						helper: function ( e, ui ) {
		
							ui.children().each( function() {
								jQuery( this ).width( jQuery( this ).width() );
			
							} );

							return ui;
						},

						/**
						 * Sort dragging stopped
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						stop: function ( e, ui ) {
							//ui.item.children( '.row-actions' ).show();
							ui.item.parent().parent().removeClass( 'dragging' );
							window.location.reload();	
						},

						/**
						 * Update the data in the database based on UI changes
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						update: function ( e, ui ) {
							sortable_terms_table.sortable( 'disable' ).addClass( 'to-updating' );

							ui.item.addClass( 'to-row-updating' );

							var strlen     = 4,
								termid     = ui.item[0].id.substr( strlen ),
								prevtermid = false,
								prevterm   = ui.item.prev();

							if ( prevterm.length > 0 ) {
								prevtermid = prevterm.attr( 'id' ).substr( strlen );
							}

							var nexttermid = false,
								nextterm   = ui.item.next();
							if ( nextterm.length > 0 ) {
								nexttermid = nextterm.attr( 'id' ).substr( strlen );
							}

							// Go do the sorting stuff via ajax
							jQuery.post( ajaxurl, {
								action: 'reordering_terms',
								id:     termid,
								previd: prevtermid,
								nextid: nexttermid,
								tax:    taxonomy
							}, term_order_update_callback );
		
							//jQuery(".child").css({"display":"block", "height" : ""} );
						}
					} );



/////////////end of sortable function






////////////////////////////				
				
				
				
				
				
				
							
				
			},
			error: function(errorThrown){
				console.log(errorThrown);
				console.log("fail");
			}	
			
		});
});


	
	
							};
							//setTimeout(myFunction, 3000);
		
							//function myFunction(){
			
								if ( typeof ( inlineEditTax ) !== 'undefined' ) {
								inlineEditTax.revert();
							//}
							ui.placeholder.height( ui.item.height() );
							ui.item.parent().parent().addClass( 'dragging' );
							}		
						},

						/**
						 * Sort dragging
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						helper: function ( e, ui ) {
		
							ui.children().each( function() {
								jQuery( this ).width( jQuery( this ).width() );
			
							} );

							return ui;
						},

						/**
						 * Sort dragging stopped
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						stop: function ( e, ui ) {
							//ui.item.children( '.row-actions' ).show();
							ui.item.parent().parent().removeClass( 'dragging' );
							window.location.reload();	
						},

						/**
						 * Update the data in the database based on UI changes
						 *
						 * @param {event} e
						 * @param {element} ui
						 * @returns {void}
						 */
						update: function ( e, ui ) {
							sortable_terms_table.sortable( 'disable' ).addClass( 'to-updating' );

							ui.item.addClass( 'to-row-updating' );

							var strlen     = 4,
								termid     = ui.item[0].id.substr( strlen ),
								prevtermid = false,
								prevterm   = ui.item.prev();

							if ( prevterm.length > 0 ) {
								prevtermid = prevterm.attr( 'id' ).substr( strlen );
							}

							var nexttermid = false,
								nextterm   = ui.item.next();
							if ( nextterm.length > 0 ) {
								nexttermid = nextterm.attr( 'id' ).substr( strlen );
							}

							// Go do the sorting stuff via ajax
							jQuery.post( ajaxurl, {
								action: 'reordering_terms',
								id:     termid,
								previd: prevtermid,
								nextid: nexttermid,
								tax:    taxonomy
							}, term_order_update_callback );
		
							//jQuery(".child").css({"display":"block", "height" : ""} );
						}
					} );



/////////////end of sortable function






////////////////////////////				
				
				
				
				
				
				
							
				
			},
			error: function(errorThrown){
				console.log(errorThrown);
				console.log("fail");
			}	
			
		});
});


	
