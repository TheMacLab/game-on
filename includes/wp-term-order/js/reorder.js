//https://stackoverflow.com/questions/918792/use-jquery-to-change-an-html-tag
//extend jquery to replace tags.  Used to turn term table into list.

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

/**
 * Enable the checkall box.
 * Changing table to list breaks it.
 * This reenables the onclick event.
 */
function toggle(source) {
  var checkboxes = jQuery("input[name='delete_tags[]']");
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
  var ischecked = source.checked;
jQuery("#cb-select-all-1").prop("checked", ischecked);
jQuery("#cb-select-all-2").prop("checked", ischecked);
}

jQuery("#cb-select-all-1").click(function() {
	toggle(this);
});
jQuery("#cb-select-all-2").click(function() {
	toggle(this);
});

jQuery(document).ready(function(){

    var idArray = [];
    //make array of all the term ids
    jQuery("#the-list").find("tr").each(function () {
    	idArray.push(this.id);
    });

	termDivIDs = (idArray);
	//alert (termDivIDs);
	jQuery.ajax({
	type: "POST",
	//dataType: 'json',
	url : ajax_url,
	data: {
		'action':'check_if_top_term',
		'goTermDivIDs' : termDivIDs,
	},
	success:function(status) {
		var StatusArray = jQuery.parseJSON(status);
		for (var i = 0; i < idArray.length; i++){
			var status = StatusArray[i];
			var rowid = ("#" + idArray[i]);
			jQuery(rowid).addClass(status);
		}
		////////Change table to List
		jQuery("#the-list").find(".parent").each(function (index) {
			jQuery(this).wrapInner('<div class="container parent"></div>');
			var currentID = jQuery(this).attr('id');
			jQuery(this).nextUntil(".parent").andSelf().wrapAll('<li id=' + currentID + ' class="sortset"></li>');
			var currentID = jQuery(this).attr('id');
			jQuery(this).nextUntil(".parent").wrapAll('<ul id=' + currentID + ' class="children ulSortable"></ul>');

			jQuery(this).contents().unwrap();

		});

		jQuery("#the-list").find(".child").each(function (index) {
			jQuery(this).wrapInner('<div class="container child"></div>');
		});
		jQuery('tbody#the-list').replaceTag('<ul>', true);
		jQuery('#the-list').addClass('ulSortable', true);
		jQuery('#the-list').find('tr').replaceTag('<li>', true);
		jQuery('#the-list').find('td').replaceTag('<div>', true);

		jQuery('.container').prepend('<div class="handleLeft"><i class="fa fa-arrows-v fa-1x" aria-hidden="true"></i></div>' );

		jQuery('.container.parent').append('<div class="handleRight"><i class="fa fa-chevron-up fa-1x" aria-hidden="true"></i></i></div>' );
		jQuery('.container.child').append('<div class="handleRight_nograb"></div>' );

		//Edit Inline (Quick Edit)
		//this doesn't work yet
		/*
		jQuery( '.editinline' ).on( 'click', function() {
			var tag_id = jQuery( this ).parents( 'li' ).attr( 'id' ),
			order  = jQuery( 'li.order', '#' + tag_id ).text();
			alert (tag_id);
			alert (order);
			order = 2;
			console.log(order);
			jQuery( ':input[name="order"]', '.inline-edit-row' ).val( order );
		 } );
		 */

		/*
		//Event Listener on mouseover show actions
		jQuery( "#the-list .column-name" ).mouseover(function() {
			jQuery(this).find('.row-actions').css( "left", "0px" );
		});


		jQuery("#the-list .column-name").mouseout(function() {
			jQuery(this).find('.row-actions').css( "left", "-99999em" );
			//hideChildren ();
		});
		*/

		jQuery(".handleRight").mousedown(function() {
				 jQuery(".child.ui-sortable-handle").css('display','none');
			jQuery("body").mouseup(function() {
				jQuery(".child.ui-sortable-handle").css('display','block');
			});
		});

		//////////////////////////Begin Sortable List


		var sortable_terms_table = jQuery( '.ulSortable' ),
		taxonomy = jQuery( 'form input[name="taxonomy"]' ).val();

		sortable_terms_table.sortable( {

			// Settings
			items:     '> li',
			cursor: 'move',
			axis:  'y',
			cancel: '  .inline-edit-row',
			distance:  2,
			opacity:   0.9,
			tolerance: 'pointer',
			scroll:    true,
			nested: 'ul',
			//tolerance: 'intersect',
			containment: 'parent',
			forceHelperSize: true,
			forcePlaceholderSize: true,
			cursorAt: {top:25, left:15},

			/**
			 * Sort start
			 *
			 * @param {event} e
			 * @param {element} ui
			 * @returns {void}
			 */
			start: function ( e, ui ) {
				//sortable_terms_table.sortable( "refreshPositions" );
				//sortable_terms_table.sortable('refresh');

				if ( typeof ( inlineEditTax ) !== 'undefined' ) {
					inlineEditTax.revert();
				}

				ui.placeholder.height( ui.item.height() );
				ui.item.parent().parent().addClass( 'dragging' );
			},

			/**
			 * Sort dragging
			 *
			 * @param {event} e
			 * @param {element} ui
			 * @returns {void}
			 */
			helper: function ( e, ui ) {
				//sortable_terms_table.sortable( "refreshPositions" );
				//sortable_terms_table.sortable('refresh');
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
			 /*
			stop: function ( e, ui ) {
				ui.item.children( '.row-actions' ).show();
				ui.item.parent().parent().removeClass( 'dragging' );
				//jQuery(".children").show();
				//window.location.reload();
			},
			*/

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
			/////////////end of sortable function
		} );

		/**
		 * Update the term order based on the ajax response
		 *
		 * @param {type} response
		 * @returns {void}
		 */
		function term_order_update_callback( response ) {
			//window.location.reload();
			/*if ( 'children' === response ) {
				window.location.reload();
				return;
			}
			*/
			//alert(response);

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

		jQuery("thead li").each(function (index) {
			jQuery(this).wrapInner('<div class="container term_list_header"></div>');
		});

		jQuery("tfoot li").each(function (index) {
			jQuery(this).wrapInner('<div class="container term_list_footer"></div>');
		});

		jQuery('thead').wrapInner('<div class="the-list-header"></div>');
		jQuery('thead').contents().unwrap();
		jQuery('th').replaceTag('<div>', true);

		jQuery('tfoot').wrapInner('<div class="the-list-footer"></ul>');
		jQuery('tfoot').contents().unwrap();



		jQuery('.the-list-footer tr').prepend('<div class="handleLeft"></div>' );
		jQuery('.the-list-header tr').prepend('<div class="handleLeft"></div>' );
		jQuery('.the-list-footer tr').append('<div class="handleRight_nograb"></div>' );
		jQuery('.the-list-header tr').append('<div class="handleRight_nograb"></div>' );

		jQuery('.the-list-header tr').replaceTag('<div class=container>', true);
		jQuery('.the-list-header td').replaceTag('<div>', true);

		jQuery('.the-list-footer tr').replaceTag('<div class=container>', true);
		jQuery('.the-list-footer td').replaceTag('<div>', true);

		jQuery("#col-right").css("display", "block");

		///if submit button is pressed, reload content and redo list from table
		jQuery("#submit").click(function() {
			jQuery("#col-right").css("display", "none");
			//delay here??
			jQuery("#col-right").bind("DOMSubtreeModified", function(){
				console.log("modified and reload");
				location.reload(true);
			});

			if ((jQuery(".term-name-wrap").hasClass( "form-invalid" )) == true){
				jQuery("#col-right").unbind("DOMSubtreeModified");
				jQuery("#col-right").css("display", "block");
			}
			if ((jQuery( ".formfield" ).hasClass( "form-invalid" )) == true)
			{
				jQuery( ".formfield" ).css("display", "block");
			}

			//jQuery("#col-right").css("display", "block");
		});

//end success of ajax
		},
		error: function(errorThrown){
			console.log(errorThrown);
			console.log("fail");
		}
//end ajax and end on ready
	});



});




