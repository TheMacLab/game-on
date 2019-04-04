/*
  SortTable
  version 2
  7th April 2007
  Stuart Langridge, http://www.kryogenix.org/code/browser/sorttable/

  Instructions:
  Download this file
  Add <script src="sorttable.js"></script> to your HTML
  Add class="sortable" to any table you'd like to make sortable
  Click on the headers to sort

  Thanks to many, many people for contributions and suggestions.
  Licenced as X11: http://www.kryogenix.org/code/browser/licence.html
  This basically means: do what you want with it.
*/


var stIsIE = /*@cc_on!@*/false;

sorttable = {
  init: function() {
    // quit if this function has already been called
    if (arguments.callee.done) return;
    // flag this function so we don't do the same thing twice
    arguments.callee.done = true;
    // kill the timer
    if (_timer) clearInterval(_timer);

    if (!document.createElement || !document.getElementsByTagName) return;

    sorttable.DATE_RE = /^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/;

    forEach(document.getElementsByTagName('table'), function(table) {
      if (table.className.search(/\bsortable\b/) != -1) {
        sorttable.makeSortable(table);
      }
    });

  },

  makeSortable: function(table) {
    if (table.getElementsByTagName('thead').length == 0) {
      // table doesn't have a tHead. Since it should have, create one and
      // put the first table row in it.
      the = document.createElement('thead');
      the.appendChild(table.rows[0]);
      table.insertBefore(the,table.firstChild);
    }
    // Safari doesn't support table.tHead, sigh
    if (table.tHead == null) table.tHead = table.getElementsByTagName('thead')[0];

    if (table.tHead.rows.length != 1) return; // can't cope with two header rows

    // Sorttable v1 put rows with a class of "sortbottom" at the bottom (as
    // "total" rows, for example). This is B&R, since what you're supposed
    // to do is put them in a tfoot. So, if there are sortbottom rows,
    // for backwards compatibility, move them to tfoot (creating it if needed).
    sortbottomrows = [];
    for (var i=0; i<table.rows.length; i++) {
      if (table.rows[i].className.search(/\bsortbottom\b/) != -1) {
        sortbottomrows[sortbottomrows.length] = table.rows[i];
      }
    }
    if (sortbottomrows) {
      if (table.tFoot == null) {
        // table doesn't have a tfoot. Create one.
        tfo = document.createElement('tfoot');
        table.appendChild(tfo);
      }
      for (var i=0; i<sortbottomrows.length; i++) {
        tfo.appendChild(sortbottomrows[i]);
      }
      delete sortbottomrows;
    }

    // work through each column and calculate its type
    headrow = table.tHead.rows[0].cells;
    for (var i=0; i<headrow.length; i++) {
      // manually override the type with a sorttable_type attribute
      if (!headrow[i].className.match(/\bsorttable_nosort\b/)) { // skip this col
        mtch = headrow[i].className.match(/\bsorttable_([a-z0-9]+)\b/);
        if (mtch) { override = mtch[1]; }
	      if (mtch && typeof sorttable["sort_"+override] == 'function') {
	        headrow[i].sorttable_sortfunction = sorttable["sort_"+override];
	      } else {
	        headrow[i].sorttable_sortfunction = sorttable.guessType(table,i);
	      }
	      // make it clickable to sort
	      headrow[i].sorttable_columnindex = i;
	      headrow[i].sorttable_tbody = table.tBodies[0];
	      dean_addEvent(headrow[i],"click", sorttable.innerSortFunction = function(e) {

          if (this.className.search(/\bsorttable_sorted\b/) != -1) {
            // if we're already sorted by this column, just
            // reverse the table, which is quicker
            sorttable.reverse(this.sorttable_tbody);
            this.className = this.className.replace('sorttable_sorted',
                                                    'sorttable_sorted_reverse');
            this.removeChild(document.getElementById('sorttable_sortfwdind'));
            sortrevind = document.createElement('span');
            sortrevind.id = "sorttable_sortrevind";
            sortrevind.innerHTML = stIsIE ? '&nbsp<font face="webdings">5</font>' : '&nbsp;&#x25B4;';
            this.appendChild(sortrevind);
            return;
          }
          if (this.className.search(/\bsorttable_sorted_reverse\b/) != -1) {
            // if we're already sorted by this column in reverse, just
            // re-reverse the table, which is quicker
            sorttable.reverse(this.sorttable_tbody);
            this.className = this.className.replace('sorttable_sorted_reverse',
                                                    'sorttable_sorted');
            this.removeChild(document.getElementById('sorttable_sortrevind'));
            sortfwdind = document.createElement('span');
            sortfwdind.id = "sorttable_sortfwdind";
            sortfwdind.innerHTML = stIsIE ? '&nbsp<font face="webdings">6</font>' : '&nbsp;&#x25BE;';
            this.appendChild(sortfwdind);
            return;
          }

          // remove sorttable_sorted classes
          theadrow = this.parentNode;
          forEach(theadrow.childNodes, function(cell) {
            if (cell.nodeType == 1) { // an element
              cell.className = cell.className.replace('sorttable_sorted_reverse','');
              cell.className = cell.className.replace('sorttable_sorted','');
            }
          });
          sortfwdind = document.getElementById('sorttable_sortfwdind');
          if (sortfwdind) { sortfwdind.parentNode.removeChild(sortfwdind); }
          sortrevind = document.getElementById('sorttable_sortrevind');
          if (sortrevind) { sortrevind.parentNode.removeChild(sortrevind); }

          this.className += ' sorttable_sorted';
          sortfwdind = document.createElement('span');
          sortfwdind.id = "sorttable_sortfwdind";
          sortfwdind.innerHTML = stIsIE ? '&nbsp<font face="webdings">6</font>' : '&nbsp;&#x25BE;';
          this.appendChild(sortfwdind);

	        // build an array to sort. This is a Schwartzian transform thing,
	        // i.e., we "decorate" each row with the actual sort key,
	        // sort based on the sort keys, and then put the rows back in order
	        // which is a lot faster because you only do getInnerText once per row
	        row_array = [];
	        col = this.sorttable_columnindex;
	        rows = this.sorttable_tbody.rows;
	        for (var j=0; j<rows.length; j++) {
	          row_array[row_array.length] = [sorttable.getInnerText(rows[j].cells[col]), rows[j]];
	        }
	        /* If you want a stable sort, uncomment the following line */
	        //sorttable.shaker_sort(row_array, this.sorttable_sortfunction);
	        /* and comment out this one */
	        row_array.sort(this.sorttable_sortfunction);

	        tb = this.sorttable_tbody;
	        for (var j=0; j<row_array.length; j++) {
	          tb.appendChild(row_array[j][1]);
	        }

	        delete row_array;
	      });
	    }
    }
  },

  guessType: function(table, column) {
    // guess the type of a column based on its first non-blank row
    sortfn = sorttable.sort_alpha;
    for (var i=0; i<table.tBodies[0].rows.length; i++) {
      text = sorttable.getInnerText(table.tBodies[0].rows[i].cells[column]);
      if (text != '') {
        if (text.match(/^-?[£$¤]?[\d,.]+%?$/)) {
          return sorttable.sort_numeric;
        }
        // check for a date: dd/mm/yyyy or dd/mm/yy
        // can have / or . or - as separator
        // can be mm/dd as well
        possdate = text.match(sorttable.DATE_RE)
        if (possdate) {
          // looks like a date
          first = parseInt(possdate[1]);
          second = parseInt(possdate[2]);
          if (first > 12) {
            // definitely dd/mm
            return sorttable.sort_ddmm;
          } else if (second > 12) {
            return sorttable.sort_mmdd;
          } else {
            // looks like a date, but we can't tell which, so assume
            // that it's dd/mm (English imperialism!) and keep looking
            sortfn = sorttable.sort_ddmm;
          }
        }
      }
    }
    return sortfn;
  },

  getInnerText: function(node) {
    // gets the text we want to use for sorting for a cell.
    // strips leading and trailing whitespace.
    // this is *not* a generic getInnerText function; it's special to sorttable.
    // for example, you can override the cell text with a customkey attribute.
    // it also gets .value for <input> fields.

    if (!node) return "";

    hasInputs = (typeof node.getElementsByTagName == 'function') &&
                 node.getElementsByTagName('input').length;

    if (node.getAttribute("sorttable_customkey") != null) {
      return node.getAttribute("sorttable_customkey");
    }
    else if (typeof node.textContent != 'undefined' && !hasInputs) {
      return node.textContent.replace(/^\s+|\s+$/g, '');
    }
    else if (typeof node.innerText != 'undefined' && !hasInputs) {
      return node.innerText.replace(/^\s+|\s+$/g, '');
    }
    else if (typeof node.text != 'undefined' && !hasInputs) {
      return node.text.replace(/^\s+|\s+$/g, '');
    }
    else {
      switch (node.nodeType) {
        case 3:
          if (node.nodeName.toLowerCase() == 'input') {
            return node.value.replace(/^\s+|\s+$/g, '');
          }
        case 4:
          return node.nodeValue.replace(/^\s+|\s+$/g, '');
          break;
        case 1:
        case 11:
          var innerText = '';
          for (var i = 0; i < node.childNodes.length; i++) {
            innerText += sorttable.getInnerText(node.childNodes[i]);
          }
          return innerText.replace(/^\s+|\s+$/g, '');
          break;
        default:
          return '';
      }
    }
  },

  reverse: function(tbody) {
    // reverse the rows in a tbody
    newrows = [];
    for (var i=0; i<tbody.rows.length; i++) {
      newrows[newrows.length] = tbody.rows[i];
    }
    for (var i=newrows.length-1; i>=0; i--) {
       tbody.appendChild(newrows[i]);
    }
    delete newrows;
  },

  /* sort functions
     each sort function takes two parameters, a and b
     you are comparing a[0] and b[0] */
  sort_numeric: function(a,b) {
    aa = parseFloat(a[0].replace(/[^0-9.-]/g,''));
    if (isNaN(aa)) aa = 0;
    bb = parseFloat(b[0].replace(/[^0-9.-]/g,''));
    if (isNaN(bb)) bb = 0;
    return aa-bb;
  },
  sort_alpha: function(a,b) {
    if (a[0]==b[0]) return 0;
    if (a[0]<b[0]) return -1;
    return 1;
  },
  sort_ddmm: function(a,b) {
    mtch = a[0].match(sorttable.DATE_RE);
    y = mtch[3]; m = mtch[2]; d = mtch[1];
    if (m.length == 1) m = '0'+m;
    if (d.length == 1) d = '0'+d;
    dt1 = y+m+d;
    mtch = b[0].match(sorttable.DATE_RE);
    y = mtch[3]; m = mtch[2]; d = mtch[1];
    if (m.length == 1) m = '0'+m;
    if (d.length == 1) d = '0'+d;
    dt2 = y+m+d;
    if (dt1==dt2) return 0;
    if (dt1<dt2) return -1;
    return 1;
  },
  sort_mmdd: function(a,b) {
    mtch = a[0].match(sorttable.DATE_RE);
    y = mtch[3]; d = mtch[2]; m = mtch[1];
    if (m.length == 1) m = '0'+m;
    if (d.length == 1) d = '0'+d;
    dt1 = y+m+d;
    mtch = b[0].match(sorttable.DATE_RE);
    y = mtch[3]; d = mtch[2]; m = mtch[1];
    if (m.length == 1) m = '0'+m;
    if (d.length == 1) d = '0'+d;
    dt2 = y+m+d;
    if (dt1==dt2) return 0;
    if (dt1<dt2) return -1;
    return 1;
  },

  shaker_sort: function(list, comp_func) {
    // A stable sort function to allow multi-level sorting of data
    // see: http://en.wikipedia.org/wiki/Cocktail_sort
    // thanks to Joseph Nahmias
    var b = 0;
    var t = list.length - 1;
    var swap = true;

    while(swap) {
        swap = false;
        for(var i = b; i < t; ++i) {
            if ( comp_func(list[i], list[i+1]) > 0 ) {
                var q = list[i]; list[i] = list[i+1]; list[i+1] = q;
                swap = true;
            }
        } // for
        t--;

        if (!swap) break;

        for(var i = t; i > b; --i) {
            if ( comp_func(list[i], list[i-1]) < 0 ) {
                var q = list[i]; list[i] = list[i-1]; list[i-1] = q;
                swap = true;
            }
        } // for
        b++;

    } // while(swap)
  }
}

/* ******************************************************************
   Supporting functions: bundled here to avoid depending on a library
   ****************************************************************** */

// Dean Edwards/Matthias Miller/John Resig

/* for Mozilla/Opera9 */
if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", sorttable.init, false);
}

/* for Internet Explorer */
/*@cc_on @*/
/*@if (@_win32)
    document.write("<script id=__ie_onload defer src=javascript:void(0)><\/script>");
    var script = document.getElementById("__ie_onload");
    script.onreadystatechange = function() {
        if (this.readyState == "complete") {
            sorttable.init(); // call the onload handler
        }
    };
/*@end @*/

/* for Safari */
if (/WebKit/i.test(navigator.userAgent)) { // sniff
    var _timer = setInterval(function() {
        if (/loaded|complete/.test(document.readyState)) {
            sorttable.init(); // call the onload handler
        }
    }, 10);
}

/* for other browsers */
window.onload = sorttable.init;

// written by Dean Edwards, 2005
// with input from Tino Zijdel, Matthias Miller, Diego Perini

// http://dean.edwards.name/weblog/2005/10/add-event/

function dean_addEvent(element, type, handler) {
	if (element.addEventListener) {
		element.addEventListener(type, handler, false);
	} else {
		// assign each event handler a unique ID
		if (!handler.$$guid) handler.$$guid = dean_addEvent.guid++;
		// create a hash table of event types for the element
		if (!element.events) element.events = {};
		// create a hash table of event handlers for each element/event pair
		var handlers = element.events[type];
		if (!handlers) {
			handlers = element.events[type] = {};
			// store the existing event handler (if there is one)
			if (element["on" + type]) {
				handlers[0] = element["on" + type];
			}
		}
		// store the event handler in the hash table
		handlers[handler.$$guid] = handler;
		// assign a global event handler to do all the work
		element["on" + type] = handleEvent;
	}
};
// a counter used to create unique IDs
dean_addEvent.guid = 1;

function removeEvent(element, type, handler) {
	if (element.removeEventListener) {
		element.removeEventListener(type, handler, false);
	} else {
		// delete the event handler from the hash table
		if (element.events && element.events[type]) {
			delete element.events[type][handler.$$guid];
		}
	}
};

function handleEvent(event) {
	var returnValue = true;
	// grab the event object (IE uses a global event object)
	event = event || fixEvent(((this.ownerDocument || this.document || this).parentWindow || window).event);
	// get a reference to the hash table of event handlers
	var handlers = this.events[event.type];
	// execute each event handler
	for (var i in handlers) {
		this.$$handleEvent = handlers[i];
		if (this.$$handleEvent(event) === false) {
			returnValue = false;
		}
	}
	return returnValue;
};

function fixEvent(event) {
	// add W3C standard event methods
	event.preventDefault = fixEvent.preventDefault;
	event.stopPropagation = fixEvent.stopPropagation;
	return event;
};
fixEvent.preventDefault = function() {
	this.returnValue = false;
};
fixEvent.stopPropagation = function() {
  this.cancelBubble = true;
}

// Dean's forEach: http://dean.edwards.name/base/forEach.js
/*
	forEach, version 1.0
	Copyright 2006, Dean Edwards
	License: http://www.opensource.org/licenses/mit-license.php
*/

// array-like enumeration
if (!Array.forEach) { // mozilla already supports this
	Array.forEach = function(array, block, context) {
		for (var i = 0; i < array.length; i++) {
			block.call(context, array[i], i, array);
		}
	};
}

// generic enumeration
Function.prototype.forEach = function(object, block, context) {
	for (var key in object) {
		if (typeof this.prototype[key] == "undefined") {
			block.call(context, object[key], key, object);
		}
	}
};

// character enumeration
String.forEach = function(string, block, context) {
	Array.forEach(string.split(""), function(chr, index) {
		block.call(context, chr, index, string);
	});
};

// globally resolve forEach enumeration
var forEach = function(object, block, context) {
	if (object) {
		var resolve = Object; // default
		if (object instanceof Function) {
			// functions have a "length" property
			resolve = Function;
		} else if (object.forEach instanceof Function) {
			// the object implements a custom forEach method so use that
			object.forEach(block, context);
			return;
		} else if (typeof object == "string") {
			// the object is a string
			resolve = String;
		} else if (typeof object.length == "number") {
			// the object is array-like
			resolve = Array;
		}
		resolve.forEach(object, block, context);
	}
};



/*
 * go_tasks_admin.js
 *
 * Where all the functionality for the task edit page goes.
 *
 * @see go_generate_accordion_array() below, it maps all the functions to their appropriate
 *      settings/accordions.
 */
/*
 * Disable sorting of metaboxes

jQuery(document).ready( function($) {
    $('.meta-box-sortables').sortable({
        disabled: true
    });

    $('.postbox .hndle').css('cursor', 'pointer');
});


 */

//fix https://stackoverflow.com/questions/9588025/change-tinymce-editors-height-dynamically
function set_height_mce() {
    jQuery('.go_call_to_action .mce-edit-area iframe').height( 100 );

}

jQuery(document).ready(function(){
    go_hide_child_tax_acfs();
    jQuery('.taxonomy-task_chains #parent, .taxonomy-go_badges #parent').change(function(){
        go_hide_child_tax_acfs();
    });

    setTimeout(set_height_mce, 1000);

});


/*
on the create new taxonomy term page,
this hides the acf stuff until a parent map is selected
 */

function go_hide_child_tax_acfs() {
    if(jQuery('.taxonomy-task_chains #parent, .taxonomy-go_badges #parent').val() == -1){
        //jQuery('#acf-term-fields').hide();
        //jQuery('.acf-field').hide();
        jQuery('.go_child_term').hide();
        jQuery('#go_map_shortcode_id').show();
    }
    else{
        jQuery('.go_child_term').show();
        //jQuery('#acf-term-fields').show();
        //jQuery('.acf-field').show();
        //jQuery('h2').show();
        jQuery('#go_map_shortcode_id').hide();
    }

    var map_id = jQuery('[name="tag_ID"]').val();
    if (map_id == null) {
        jQuery('#go_map_shortcode_id').hide();
    }

    //store item shortcode--add item id to bottom
    var item_id = jQuery('#post_ID').val();
    jQuery('#go_store_item_id .acf-input').html('[go_store id="' + item_id + '"]');

    //map shortcode message
    //var map_id = jQuery('[name="tag_ID"]').val();
    //console.log(map_id);
    var map_name = jQuery('#name').val();
    jQuery('#go_map_shortcode_id .acf-input').html('Place this code in a content area to link directly to this map.<br><br>[go_single_map_link map_id="' + map_id + '"]' + map_name + '[/go_single_map_link]');
    if (map_id == null) {
        jQuery('#go_map_shortcode_id').hide();
    }

}








/**
 * This next section makes sure the levels on the options page proceed in ascending order.
 */

//get it set up on page load
jQuery(document).ready(function(){
    //get the growth level from options
    //var growth = levelGrowth*1;
    if(typeof go_is_options_page !== 'undefined') {
        var is_options_page = go_is_options_page.is_options_page;
    }
    if (is_options_page) {
        //console.log(is_options_page);
        Go_orgGrowth = jQuery('#go_levels_growth').find('input').val();

        //run the limit function once on load
        go_levels_limit_each();

        //attach function each input field
        jQuery('.go_levels_repeater_numbers').find('input').change(go_levels_limit_each);
        jQuery('.go_levels_repeater_names').find('input').change(go_level_names);
        //jQuery('.go_levels_repeater_names').find('input').change(go_level_names);
        jQuery('#go_levels_growth').find('input').change(go_validate_growth);


        acf.add_action('append', function ($el) { //run limit function when new row is added and attach it to the input in the new field
            // $el will be equivalent to the new element being appended $('tr.row')
            //limit to the levels table
            if (jQuery($el).closest("#go_levels_repeater").length) {//if there is a previous field
                var $input_num = $el.find('input').first(); // find the first input field
                jQuery($input_num).change(go_levels_limit_each); //bind to input on change

                var $input_name = $el.find('input').last(); // find the first input field
                jQuery($input_name).change(go_level_names);
                //console.log('-----------------row added------------------------');
                go_levels_limit_each(); //run one time
                go_level_names();
            }
        });

        jQuery(".more_info_accordian").accordion({
            collapsible: true,
            header: "h3",
            active: false
        });
    }
});

function go_validate_growth() {
    var NewGrowth = jQuery('#go_levels_growth').find('input').val();
    if(isNaN(NewGrowth)){
        jQuery('#go_levels_growth').find('input').val(Go_orgGrowth);

    }else{
        Go_orgGrowth = NewGrowth;
    }
}

function go_level_names(){
    var rows = document.getElementById('go_levels_repeater').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
    var row;
    row = 0;
    var thisName;
    var prevName;
    thisName = '';
    jQuery('.go_levels_repeater_names').find('input').each(function() {
        row++;
        prevName = thisName;

        thisName = jQuery(this).val();
        //console.log (thisName);
        //console.log (prevName);
        if (row > 1 && row != rows){
            console.log ('Row:' + row)
            if (thisName == null || thisName ==''){
                console.log ('empty:' + row)
                console.log (thisName);
                jQuery(this).val(prevName);
                thisName = prevName;
            }
        }

    });
}

function go_levels_limit_each(){
    var rows = document.getElementById('go_levels_repeater').getElementsByTagName("tbody")[0].getElementsByTagName("tr").length;
    //var growth = jQuery('#go_levels_growth').find('input').val();
    var rate = Go_orgGrowth;
    //rate = 5;
    var firstUp = Number(jQuery('#go_first_up').find('input').val());

    //alert(growth);
    //console.log('-----------------limit check------------------------');
    var row;
    row = 0;

    jQuery('.go_levels_repeater_numbers').find('input').each(function(){
        row++;
        //console.log('-----------row'+ row);
        var thisVal;
        thisVal = jQuery(this).val() || 0;
        thisVal = parseInt (thisVal);
        var rowNum = jQuery(this).closest('.acf-row').find('span').html();
        console.log("row num:" + rowNum);
        var prevVal = jQuery(this).closest('.acf-row').prev().find('.go_levels_repeater_numbers').find('input').val() || 0;
        prevVal = parseInt (prevVal);
        var nextVal = jQuery(this).closest('.acf-row').next().find('.go_levels_repeater_numbers').find('input').val() || 0;
        nextVal = parseInt (nextVal);

        //console.log('prev' + prevVal);
        //console.log('this' + thisVal);
        //console.log('next' + nextVal);
        if (row === 1){   //the first row
            jQuery(this).attr({
                "max" : 0,          // substitute your own
                "min" : 0           // values (or variables) here
            });
            jQuery(this).val(0);
            //console.log ('first:' + 0);
        }
        else if (row === rows -1 ){  //the last row
            jQuery(this).attr({
                "min" : prevVal     // min is prev row value
            });
            jQuery(this).removeAttr("max");

            if (thisVal <= prevVal){
                //jQuery(this).val(Math.floor(prevVal * growth));
                jQuery(this).val(((rowNum-1)*(rowNum-2)*rate)/2+firstUp+prevVal);
                console.log("firstUp #:" + firstUp + " .  " + "rate Value: " + rate);
                //console.log('Last row Value too low: set to ' + prevVal + '    ---- compared: ' + thisVal + ' < ' +prevVal );
            }
            else{
                //console.log ('lastOK value: ' + thisVal);
            }
        }
        else if (row === rows){    //the template row for ACF
            //console.log('Template Row');
        }
        else {  //all the rows in teh middle
            if (thisVal < nextVal) {
                jQuery(this).attr({
                    "min": prevVal,
                    "max": nextVal
                });
            }
            if (thisVal > nextVal) {
                jQuery(this).attr({
                    "min": prevVal
                });
            }

            if (thisVal <= prevVal) {


                //jQuery(this).val(prevVal * growth);
                jQuery(this).val(((rowNum-1)*(rowNum-2)*rate)/2+firstUp+prevVal);


                console.log("firstUp #:" + firstUp + " .  " + "rate Value: " + rate);

                //console.log('value to low.  Set to ' + prevVal);
            }
            /*
            else if (thisVal > nextVal && nextVal != 0) {

                jQuery(this).val(nextVal);
                //console.log('Middle Row: Value to high.  Set to ' + nextVal);
            }
            else {
                //console.log('middle Value:' + thisVal);
            }
            */
        }
    });
}


jQuery( document ).ready( function() {

    if(typeof GO_EDIT_STORE_DATA !== 'undefined') {
        var is_store = GO_EDIT_STORE_DATA.is_store_edit;
    }
    if (is_store) {
        var id = GO_EDIT_STORE_DATA.postid;
        var store_name = GO_EDIT_STORE_DATA.store_name;
        var link = "<a id=" + id + " class='go_str_item ab-item' >View " + store_name + " Item</a>"
        //console.log(link);
        jQuery('#wp-admin-bar-view').html(link);
    }
});


//Add an on click to all store items
jQuery(document).ready(function(){
    jQuery('.go_str_item').one("click", function(e){
        go_lb_opener( this.id );
    });
});

// Makes it so you can press return and enter content in a field
function go_make_store_clickable() {
    //Make URL button clickable by clicking enter when field is in focus
    jQuery('.clickable').keyup(function(ev) {
        // 13 is ENTER
        if (ev.which === 13) {
            jQuery("#go_store_pass_button").click();
        }
    });
}

//open the lightbox for the store items
function go_lb_opener( id ) {
    jQuery( '#light' ).css( 'display', 'block' );
    jQuery('.go_str_item').prop('onclick',null).off('click');

    if ( ! jQuery.trim( jQuery( '#lb-content' ).html() ).length ) {
        var get_id = id;
        var nonce = GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax;
        var gotoSend = {
            action:"go_the_lb_ajax",
            _ajax_nonce: nonce,
            the_item_id: get_id,
        };
        jQuery.ajax({
            url: MyAjax.ajaxurl,
            type:'POST',
            data: gotoSend,
            beforeSend: function() {
                jQuery( "#lb-content" ).append( '<div class="go-lb-loading"></div>' );
            },
            cache: false,
            success: function( raw) {
                console.log('success');
                console.log(raw);
                var res = JSON.parse( raw );

                try {
                    var res = JSON.parse( raw );
                } catch (e) {
                    res = {
                        json_status: '101',
                        html: ''
                    };
                }
                jQuery( "#lb-content" ).innerHTML = "";
                jQuery( "#lb-content" ).html( '' );

                jQuery.featherlight(res.html, {
                    variant: 'store',
                    afterOpen: function(event){
                        console.log("store-fitvids3");
                        //jQuery("#go_store_description").fitVids();
                        //go_fit_and_max_only("#go_store_description");
                        go_fit_and_max_only("#go_store_description");
                    }
                });
                if ( '101' === Number.parseInt( res.json_status ) ) {
                    console.log (101);
                    jQuery( '#go_store_error_msg' ).show();
                    var error = "Server Error.";
                    if ( jQuery( '#go_store_error_msg' ).text() != error ) {
                        jQuery( '#go_store_error_msg' ).text( error );
                    } else {
                        flash_error_msg_store( '#go_store_error_msg' );
                    }
                } else if ( 302 === Number.parseInt( res.json_status ) ) {
                    console.log (302);
                    window.location = res.location;

                }
                jQuery('.go_str_item').one("click", function(e){
                    go_lb_opener( this.id );
                });

                jQuery('#go_store_pass_button').one("click", function (e) {
                    go_store_password(id);
                });

                go_max_purchase_limit();

            }
        });
    }
}

//called when the "buy" button is clicked.
function goBuytheItem( id, count ) {

	var nonce = GO_BUY_ITEM_DATA.nonces.go_buy_item;
	var user_id = GO_BUY_ITEM_DATA.userID;

	jQuery( document ).ready( function( jQuery ) {
		var gotoBuy = {
			_ajax_nonce: nonce,
			action: 'go_buy_item',
			the_id: id,
			qty: jQuery( '#go_qty' ).val(),
            user_id: user_id,
		};


		jQuery.ajax({
			url: MyAjax.ajaxurl,
			type: 'POST',
			data: gotoBuy,
			beforeSend: function() {
				jQuery( '#golb-fr-buy' ).innerHTML = '';
				jQuery( '#golb-fr-buy' ).html( '' );
				jQuery( '#golb-fr-buy' ).append( '<div id="go-buy-loading" class="buy_gold"></div>' );
			},
			success: function( raw ) {
                var res = {};
                try {
                    var res = JSON.parse( raw );
                } catch (e) {
                    res = {
                        json_status : '101',
                        html : '101 Error: Please try again.'
                    };
                }
				if ( -1 !== raw.indexOf( 'Error' ) ) {
					jQuery( '#light').html(raw);
				} else {
                    jQuery( '#light').html(res.html);
				}
			}
		});
	});
}

function flash_error_msg_store( elem ) {
    var bg_color = jQuery( elem ).css( 'background-color' );
    if ( typeof bg_color === undefined ) {
        bg_color = "white";
    }
    jQuery( elem ).animate({
        color: bg_color
    }, 200, function() {
        jQuery( elem ).animate({
            color: "red"
        }, 200 );
    });
}

function go_store_password( id ){
    var pass_entered = jQuery('#go_store_password_result').attr('value').length > 0 ? true : false;
    if (!pass_entered) {
        jQuery('#go_store_error_msg').show();
        var error = "Please enter a password.";
        if (jQuery('#go_store_error_msg').text() != error) {
            jQuery('#go_store_error_msg').text(error);
        } else {
            flash_error_msg_store('#go_store_error_msg');
        }
        jQuery('#go_store_pass_button').one("click", function (e) {
            go_store_password(id);
        });
        return;
    }
    var result = jQuery( '#go_store_password_result' ).attr( 'value' );

    jQuery( '#light' ).css( 'display', 'block' );

    if ( ! jQuery.trim( jQuery( '#lb-content' ).html() ).length ) {
        var get_id = id;
        var nonce = GO_EVERY_PAGE_DATA.nonces.go_the_lb_ajax;
        var gotoSend = {
            action:"go_the_lb_ajax",
            _ajax_nonce: nonce,
            the_item_id: get_id,
            skip_locks: true,
            result: result
        };

        jQuery.ajax({

            url: MyAjax.ajaxurl,
            type:'POST',
            data: gotoSend,
            cache: false,
            success: function( raw) {
                    var res = JSON.parse( raw );

                    try {
                        var res = JSON.parse( raw );
                    } catch (e) {
                        res = {
                            json_status: '101',
                            html: ''
                        };
                    }

                    if ( '101' === Number.parseInt( res.json_status ) ) {
                        console.log (101);
                        jQuery( '#go_store_error_msg' ).show();
                        var error = "Server Error.";
                        if ( jQuery( '#go_store_error_msg' ).text() != error ) {
                            jQuery( '#go_store_error_msg' ).text( error );
                        } else {
                            flash_error_msg_store( '#go_store_error_msg' );
                        }
                    } else if ( 302 === Number.parseInt( res.json_status ) ) {
                        console.log (302);
                        window.location = res.location;

                    }else if ( 'bad_password' ==  res.json_status ) {
                        jQuery( '#go_store_error_msg' ).show();
                        var error = "Invalid password.";
                        if ( jQuery( '#go_store_error_msg' ).text() != error ) {
                            jQuery( '#go_store_error_msg' ).text( error );
                        } else {
                            flash_error_msg_store( '#go_store_error_msg' );
                        }
                        jQuery('#go_store_pass_button').one("click", function (e) {
                            go_store_password(id);
                        });
                    }else {
                        jQuery('#go_store_pass_button').one("click", function (e) {
                            go_store_password(id);
                        });
                        jQuery('#go_store_lightbox_container').hide();
                        jQuery('.featherlight-content').html(res.html);
                        go_max_purchase_limit();


                    }
            }
        });
    }
}

function go_max_purchase_limit(){
    window.go_purchase_limit = jQuery( '#golb-fr-purchase-limit' ).attr( 'val' );

    var spinner_max_size = go_purchase_limit;

    jQuery( '#go_qty' ).spinner({
        max: spinner_max_size,
        min: 1,
        stop: function() {
            jQuery( this ).change();
        }
    });
    go_make_store_clickable();

    jQuery('#go_store_admin_override').one("click", function (e) {
        jQuery('.go_store_lock').show();
        jQuery('#go_store_admin_override').hide();
        go_make_store_clickable();

    });
}

function go_count_item( item_id ) {
	var nonce = GO_BUY_ITEM_DATA.nonces.go_get_purchase_count;
	jQuery.ajax({
		url: MyAjax.ajaxurl,
		type: 'POST',
		data: {
			_ajax_nonce: nonce,
			action: 'go_get_purchase_count',
			item_id: item_id
		},
		success: function( res ) {
			if ( -1 !== res ) {
				var count = res.toString();
				jQuery( '#golb-purchased' ).html( 'Quantity purchased: ' + count );
			}
		}
	});
}



/*
function tinymce_updateCharCounter(el, len) {
    jQuery('.char_count').text(len + '/' + '500');
}

function tinymce_getContentLength() {
    var len = tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
    console.log(len);
    return len;
}
*/
jQuery( document ).ready( function() {

    //add onclick to blog edit buttons
    //console.log("opener3");
    jQuery(".go_blog_opener").one("click", function (e) {
        go_blog_opener(this);
    });
    jQuery(".go_blog_trash").one("click", function (e) {
        go_blog_trash(this);
    });

    jQuery('#go_hidden_mce').remove();
    jQuery('#go_hidden_mce_edit').remove();

    jQuery( ".feedback_accordion" ).accordion({
        collapsible: true
    });

    jQuery(".go_blog_favorite").click(function() {
        go_blog_favorite(this);
    });

    //go_blog_tags_select2();

});

function  go_blog_favorite(target){
    blog_post_id = jQuery( target ).attr( 'data-post_id' );


    if (jQuery(target).is(":checked"))
    {
        var checked = true;
    }else{
        checked = false;
    }

    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_favorite_toggle;

    //console.log("favorite_id: " + blog_post_id);
    var gotoSend = {
        action:"go_blog_favorite_toggle",
        _ajax_nonce: nonce,
        blog_post_id: blog_post_id,
        checked: checked
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        success: function (raw) {

        }
    });
}

function go_blog_tags_select2(){
    jQuery('.go_feedback_go_blog_tags_select').select2({
        ajax: {
            url: MyAjax.ajaxurl, // AJAX URL is predefined in WordPress admin
            dataType: 'json',
            delay: 400, // delay in ms while typing when to perform a AJAX search
            data: function (params) {

                return {
                    q: params.term, // search query
                    action: 'go_make_taxonomy_dropdown_ajax', // AJAX action for admin-ajax.php
                    taxonomy: 'go_blog_tags',
                    is_hier: false
                };


            },
            processResults: function( data ) {

                jQuery(".go_feedback_go_blog_tags_select").select2("destroy");
                jQuery('.go_feedback_go_blog_tags_select').children().remove();
                jQuery(".go_feedback_go_blog_tags_select").select2({
                    data: data,
                    placeholder: "Show All",
                    allowClear: true}).trigger("change");
                jQuery(".go_feedback_go_blog_tags_select").select2("open");
                return {
                    results: data
                };

            },
            cache: true
        },
        minimumInputLength: 0, // the minimum of symbols to input before perform a search
        multiple: true,
        placeholder: "Show All",
        allowClear: true
    });
}

function task_stage_check_input( target, on_task) {

    console.log('button clicked');
    //disable button to prevent double clicks
    go_enable_loading( target );

    //BUTTON TYPES
    //Abandon
    //Start Timer
    //Continue
    //Undo
    //Repeat
    //Undo Repeat --is this different than just undo

    //Continue or Complete button needs to validate input for:
    ////quizes
    ///URLs
    ///passwords
    ///uploads

    //if it passes validation:
    ////send information to php with ajax and wait for a response

    //if response is success
    ////update totals
    ///flash rewards and sounds
    ////update last check
    ////update current stage and check


    //v4 Set variables
    var button_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'button_type' ) ) {
        button_type = jQuery( target ).attr( 'button_type' )
        console.log("button_type: " + button_type);
    }

    var task_status = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'status' ) ) {
        task_status = jQuery( target ).attr( 'status' )
    }

    var check_type = "";
    if ( 'undefined' !== typeof jQuery( target ).attr( 'check_type' ) ) {
        check_type = jQuery( target ).attr( 'check_type' )
        console.log("Check Type: " + check_type);
    }
    var fail = false;
    jQuery('#go_blog_stage_error_msg').text("");
    jQuery('#go_blog_error_msg').text("");
    var error_message = '<h3>Your post was not saved.</h3><ul> ';

    var url_toggle = jQuery(target).attr('url_toggle');
    var video_toggle = jQuery(target).attr('video_toggle');
    var file_toggle = jQuery(target).attr('file_toggle');
    var text_toggle = jQuery(target).attr('text_toggle');
    var suffix = jQuery( target ).attr( 'blog_suffix' );

    var go_result_video = '#go_result_video' + suffix;
    var go_result_url = '#go_result_url' + suffix;
    var go_result_media = '#go_result_media' + suffix;
    console.log ("suffix: " + suffix);

    ///v4 START VALIDATE FIELD ENTRIES BEFORE SUBMIT
    //if (button_type == 'continue' || button_type == 'complete' || button_type =='continue_bonus' || button_type =='complete_bonus') {

    if ( check_type == 'blog' || check_type == 'blog_lightbox') { //min words and Video field on blog form validation

        if(video_toggle == '1') {
            var the_url = jQuery(go_result_video).attr('value').replace(/\s+/, '');
            console.log(the_url);

            if (the_url.length > 0) {
                if (the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && !(the_url.lastIndexOf('http://') > 0) && !(the_url.lastIndexOf('https://') > 0)) {
                    if ((the_url.search("youtube") == -1) && (the_url.search("vimeo") == -1)) {
                        error_message += "<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>";
                        fail = true;
                    }
                } else {
                    error_message += "<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>";
                    fail = true;
                }
            } else {
                error_message += "<li>Enter a valid video URL. YouTube and Vimeo are supported.</li>";
                fail = true;
            }
        }

        if(text_toggle  == '1') {
            //Word count validation
            var min_words = jQuery(target).attr('min_words'); //this variable is used in the other functions as well
            //alert("min Words: " + min_words);
            var my_words = tinymce_getContentLength_new(check_type);
            //var bb = tinymce.get(tinymce.activeEditor.id);
            //console.log(bb);
            if (my_words < min_words) {
                error_message += "<li>Your post is not long enough. There must be " + min_words + " words minimum. You have " + my_words + " words.</li>";
                fail = true;
            }
        }

    }
    if (check_type === 'password' || check_type == 'unlock') {
        var pass_entered = jQuery('#go_result').attr('value').length > 0 ? true : false;
        if (!pass_entered) {
            error_message += "Retrieve the password from " + go_task_data.admin_name + ".";
            fail = true;
        }
    }
    if (check_type == 'URL' || ((check_type == 'blog' || check_type == 'blog_lightbox') && url_toggle == true)) {

        if (check_type == 'URL') {
            var the_url = jQuery('#go_result').attr('value').replace(/\s+/, '');
        }else{
            var the_url = jQuery(go_result_url).attr('value').replace(/\s+/, '');
            var required_string = jQuery( target ).attr('required_string');
        }
        console.log("URL" + the_url);

        if (the_url.length > 0) {
            if (the_url.match(/^(http:\/\/|https:\/\/).*\..*$/) && !(the_url.lastIndexOf('http://') > 0) && !(the_url.lastIndexOf('https://') > 0)) {
                if ( check_type == 'blog' || check_type == 'blog_lightbox') {
                    if ((the_url.indexOf(required_string) == -1) ){
                        error_message += "<li>Enter a valid URL. The URL must contain \"" + required_string + "\".</li>";
                        fail = true;
                    }
                }
            } else {
                error_message += "<li>Enter a valid URL.</li>";
                fail = true;
            }
        } else {
            error_message += "<li>Enter a valid URL.</li>";
            go_disable_loading();
            fail = true;
        }

    }
    if (check_type == 'upload' || ((check_type == 'blog' || check_type == 'blog_lightbox') && file_toggle == true)) {
        if (check_type == 'upload') {
            var result = jQuery("#go_result").attr('value');
        }else{
            var result = jQuery(go_result_media).attr('value');
        }
        if (result == undefined) {
            error_message += "<li>Please attach a file.</li>";
            fail = true;
        }

    }

    if (check_type == 'quiz') {
        var test_list = jQuery(".go_test_list");
        if (test_list.length >= 1) {
            var checked_ans = 0;
            for (var i = 0; i < test_list.length; i++) {
                var obj_str = "#" + test_list[i].id + " input:checked";
                var chosen_answers = jQuery(obj_str);
                if (chosen_answers.length >= 1) {
                    checked_ans++;
                }
            }
            //if all questions were answered
            if (checked_ans >= test_list.length) {
                go_quiz_check_answers(task_status, target);
                fail = false;

            }
            //else print error message
            else if (test_list.length > 1) {
                error_message +="<li>Please answer all questions!</li>";
                fail = true;
            }
            //} else {
            //if (jQuery(".go_test_list input:checked").length >= 1) {
            // go_quiz_check_answers();
            //}
            else {
                error_message += "<li>Please answer the question!</li>";
                fail = true;
            }
        }
    }
    //}
    error_message += "</ul>";
    if (fail == true){
        if (on_task == true) {
            console.log("error_stage");
            //flash_error_msg('#go_stage_error_msg');
            jQuery('#go_blog_stage_error_msg').append(error_message);
            jQuery('#go_blog_stage_error_msg').show();

        }else {

            console.log("error_blog");
            jQuery('#go_blog_error_msg').append(error_message);
            jQuery('#go_blog_error_msg').show();
        }

        console.log("error validation");
        /*
        new Noty({
                type: 'error',
                layout: 'center',
                text: error_message,
                theme: 'relax',
                timeout: '5000',
                visibilityControl: true,
            }).show();
            */

        go_disable_loading();
        return;
    }else{
        jQuery('#go_blog_stage_error_msg').hide();
        jQuery('#go_blog_error_msg').hide();
    }


    if (on_task == true) {
        task_stage_change(target);
    }else{ //this was a blog submit button in a lightbox, so just save without changing stage.
        go_blog_submit( target, true );
    }
}

// disables the target stage button, and adds a loading gif to it
function go_enable_loading( target ) {
    //prevent further events with this button
    //jQuery('#go_button').prop('disabled',true);
    // prepend the loading gif to the button's content, to show that the request is being processed
    jQuery('.go_loading').remove();
    target.innerHTML = '<span class="go_loading"></span>' + target.innerHTML;
}

// re-enables the stage button, and removes the loading gif
function go_disable_loading( ) {
    //console.log ("oneclick");
    jQuery('.go_loading').remove();

    jQuery('#go_button').off().one("click", function(e){
        task_stage_check_input( this, true );
    });
    jQuery('#go_back_button').off().one("click", function(e){
        task_stage_change(this);
    });

    jQuery('#go_save_button').off().one("click", function(e){
        //task_stage_check_input( this, false );
        go_blog_submit( this, false );//disable loading
    });


    jQuery( "#go_bonus_button" ).off().one("click", function(e) {
        go_update_bonus_loot(this);
    });

    jQuery('.go_str_item').off().one("click", function(e){
        go_lb_opener( this.id );
    });

    //console.log("opener4");
    jQuery(".go_blog_opener").off().one("click", function(e){
        go_blog_opener( this );
    });

    jQuery(".go_blog_trash").off().one("click", function(e){
        go_blog_trash( this );
    });


    jQuery("#go_blog_submit").off().one("click", function(e){
        task_stage_check_input( this, false );//disable loading
    });

    //add active class to checks and buttons
    jQuery(".progress").closest(".go_checks_and_buttons").addClass('active');

}

function tinymce_getContentLength_new(source) {
    //var b = jQuery(target).closest(".go_checks_and_buttons").find('.mce-content-body').hide();
    //console.log(b);
    //var b = tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText;

    if (source == 'blog_lightbox'){
        //var b = tinymce.get('go_blog_post_lightbox').contentDocument.body.innerText;
        var b = go_tmce_getContent('go_blog_post_lightbox');

    }else {
        //var b = tinymce.get('go_blog_post').contentDocument.body.innerText;
        var b = go_tmce_getContent('go_blog_post');
    }
    var e = 0;
    if (b) {
        b = b.replace(/\.\.\./g, " "),
            b = b.replace(/<.[^<>]*?>/g, " ").replace(/&nbsp;|&#160;/gi, " "),
            b = b.replace(/(\w+)(&#?[a-z0-9]+;)+(\w+)/i, "$1$3").replace(/&.+?;/g, " "),
            b = b.replace(/[0-9.(),;:!?%#$?\x27\x22_+=\\\/\-]*/g, "");
        var f = b.match(/[\w\u2019\x27\-\u00C0-\u1FFF]+/g);
        f && (e = f.length)
    }
    return e
}

function go_blog_opener( el ) {
    go_enable_loading( el );
    jQuery("#go_hidden_mce").remove();
    jQuery(".go_blog_opener").prop("onclick", null).off("click");

    var check_for_understanding= jQuery( el ).attr( 'data-check_for_understanding' );

    //var result_title = jQuery( this ).attr( 'value' );
    var blog_post_id= jQuery( el ).attr( 'blog_post_id' );
    //console.log(el);
    //console.log(blog_post_id);
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_opener;
    var gotoSend = {
        action:"go_blog_opener",
        _ajax_nonce: nonce,
        blog_post_id: blog_post_id,
        check_for_understanding: check_for_understanding
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        cache: false,
        success: function (results) {
            //console.log(results);
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );

            jQuery.featherlight(results, {afterContent: function(){
                    console.log("aftercontent");

                    jQuery( 'body' ).attr( 'data-go_blog_saved', '0' );
                    jQuery( 'body' ).attr( 'data-go_blog_updated', '0' );

                    jQuery("#go_result_url_lightbox, #go_result_video_lightbox").change(function() {
                        jQuery('body').attr('data-go_blog_updated', '1');
                    });

                    jQuery('.go_frontend-button').on("click", function() {
                        jQuery('body').attr('data-go_blog_updated', '1');
                    });

                    //tinymce.init(tinyMCEPreInit.mceInit['go_result_lightbox']);
                    //https://stackoverflow.com/questions/25732679/load-wordpress-editor-via-ajax-plugin
                    var fullId = 'go_blog_post_lightbox';
                    //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_lightbox');
                    tinymce.execCommand('mceRemoveEditor', true, fullId);


                    //quicktags({id :'go_blog_post_lightbox'});

                    quicktags({id : fullId});
                    // use wordpress settings
                    tinymce.init({
                        selector: fullId,
                         // change this value according to your HTML
                        setup: function(editor) {
                            editor.on('keyup', function(e) {
                                jQuery('body').attr('data-go_blog_updated', '1');
                                console.log("updated");
                            });
                        },
                        branding: false,
                        theme:"modern",
                        skin:"lightgray",
                        language:"en",
                        formats:{
                            alignleft: [
                                {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'left'}},
                                {selector: 'img,table,dl.wp-caption', classes: 'alignleft'}
                            ],
                            aligncenter: [
                                {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'center'}},
                                {selector: 'img,table,dl.wp-caption', classes: 'aligncenter'}
                            ],
                            alignright: [
                                {selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: {textAlign:'right'}},
                                {selector: 'img,table,dl.wp-caption', classes: 'alignright'}
                            ],
                            strikethrough: {inline: 'del'}
                        },
                        relative_urls:false,
                        remove_script_host:false,
                        convert_urls:false,
                        browser_spellcheck:true,
                        fix_list_elements:true,
                        entities:"38,amp,60,lt,62,gt",
                        entity_encoding:"raw",
                        keep_styles:false,
                        paste_webkit_styles:"font-weight font-style color",
                        preview_styles:"font-family font-size font-weight font-style text-decoration text-transform",
                        wpeditimage_disable_captions:false,
                        wpeditimage_html5_captions:true,
                        plugins:"charmap,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview,wordcount",
                        selector:"#" + fullId,
                        resize:"vertical",
                        menubar:false,
                        wpautop:true,
                        wordpress_adv_hidden:false,
                        indent:false,
                        toolbar1:"formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,fullscreen,wp_adv",
                        toolbar2:"strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
                        toolbar3:"",
                        toolbar4:"",
                        tabfocus_elements:":prev,:next",
                        body_class:"id post-type-post post-status-publish post-format-standard",});
                    // this is needed for the editor to initiate
                    tinyMCE.execCommand('mceAddEditor', false, fullId);


                    //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_lightbox' );
                    //tinyMCE.execCommand("mceAddControl", false, 'go_blog_post_lightbox');

                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_lightbox' );
                    //tinymce.execCommand( 'mceToggleEditor', true, 'go_blog_post_edit' );

                },
                beforeClose: function() {
                    console.log("beforeClose");
                    //var go_blog_saved= jQuery( 'body' ).attr( 'data-go_blog_saved' );
                    var go_blog_updated= jQuery( 'body' ).attr( 'data-go_blog_updated' );
                    if (go_blog_updated == '1') {
                        swal({
                            title: "You have unsaved changes.",
                            text: "Would you like to save? If you don't save you will not be able to recover these changes.",
                            icon: "warning",
                           //buttons: ["Save and Close", "Close without Saving"],
                            //dangerMode: true,

                            buttons: {
                                exit: {
                                    text: "Close without Saving",
                                    value: "exit",
                                },
                                save: {
                                    text: "Save and Close",
                                    value: "save"
                                }
                            }
                        })
                            .then((value) => {
                                switch (value) {

                                    case "exit":
                                        swal("Your changes were not saved.");
                                        jQuery( 'body' ).attr( 'data-go_blog_updated', '0' );
                                        jQuery.featherlight.close();
                                        break;

                                    case "save":
                                        jQuery('#go_blog_submit').trigger('click');
                                        break;

                                    default:

                                }
                            });
                        return false;
                    }else {
                        var post_wrapper_class = ".go_blog_post_wrapper_" + blog_post_id;
                        if (jQuery(post_wrapper_class).length = 0) {
                            location.reload();
                        }


                        //location.reload();
                        //change reload to swap wrapper

                    }
                }
            });
            //tinymce.execCommand('mceRemoveEditor', true, 'go_blog_post_edit');
            //tinymce.execCommand( 'mceAddEditor', true, 'go_blog_post_edit' );

            jQuery(".featherlight").css('background', 'rgba(0,0,0,.8)');
            jQuery(".featherlight .featherlight-content").css('width', '80%');

            //console.log("opener2");
            jQuery(".go_blog_opener").one("click", function(e){
                go_blog_opener( this );
            });
            go_disable_loading();

        }
    });
}

function go_blog_submit( el, reload ) {

    go_enable_loading( el );

    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_submit;
    var suffix = jQuery( el ).attr( 'blog_suffix' );

    //var result = tinyMCE.activeEditor.getContent();
    var result_title = jQuery( '#go_blog_title' + suffix ).html();
    var button= jQuery( el ).attr( 'button_type' );
    var result = go_get_tinymce_content_blog(suffix);
    //console.log("title: " + result_title);
    console.log("go_blog_submit");
    //var blog_post_id= jQuery( el ).attr( 'blog_post_id' );
    var blog_post_id = jQuery('#go_blog_title' + suffix).attr( 'data-blog_post_id' );
    console.log("blog_post_id: " + blog_post_id);
    var post_wrapper_class = ".go_blog_post_wrapper_" + blog_post_id;//checks if the post exists on the page--used to reload that section later
    //alert(post_wrapper_class);
    var go_blog_bonus_stage= jQuery( el ).attr( 'data-bonus_status' );
    var go_blog_task_stage= jQuery( el ).attr( 'status' );
    var task_id= jQuery( el ).attr( 'task_id' );
    var check_for_understanding= jQuery( el ).attr( 'data-check_for_understanding' );

    var blog_url= jQuery( '#go_result_url' + suffix ).val();
    //var blog_private= jQuery( '#go_private_post' + suffix ).val();
    if (jQuery('#go_private_post' + suffix).is(":checked"))
    {
        var blog_private = true;
    }
    var blog_media= jQuery( '#go_result_media' + suffix ).attr( 'value' );
    var blog_video= jQuery( '#go_result_video' + suffix).val();
    //console.log("go_blog_bonus_stage: " + go_blog_bonus_stage);
    console.log("blog_private: " + blog_private);

    var gotoSend = {
        action:"go_blog_submit",
        _ajax_nonce: nonce,
        result: result,
        result_title: result_title,
        blog_post_id: blog_post_id,
        blog_url: blog_url,
        blog_media: blog_media,
        blog_video: blog_video,
        blog_private: blog_private,
        go_blog_task_stage: go_blog_task_stage,
        go_blog_bonus_stage: go_blog_bonus_stage,
        post_id: task_id,
        button: button,
        check_for_understanding: check_for_understanding
    };
    //jQuery.ajaxSetup({ cache: true });
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type: 'POST',
        data: gotoSend,
        cache: false,
        success: function (raw) {
            console.log('success1');
            //console.log(raw);
            // parse the raw response to get the desired JSON
            var res = {};
            try {
                var res = JSON.parse( raw );
            } catch (e) {
                res = {
                    json_status: '101',
                    message: '',
                    blog_post_id: '',
                    wrapper: ''
                };
            }
            //console.log("message" + res.message);
            //console.log("blog_post_id: " + res.blog_post_id);
            //console.log("suffix: " + suffix);
            jQuery( 'body' ).attr( 'data-go_blog_updated', '0' );


            jQuery('body').append(res.message);
            //jQuery('.go_loading').remove();
            go_disable_loading();
            jQuery('#go_save_button' + suffix).off().one("click", function(e){
                //task_stage_check_input( this, false );//on submit, no reload
                go_blog_submit( this, false );
            });


            jQuery( '#go_save_button' + suffix ).attr( 'blog_post_id', res.blog_post_id );

            jQuery( '#go_blog_title' + suffix ).attr( 'data-blog_post_id', res.blog_post_id );


            if (reload == true) {
                //alert("here");
                var is_new = jQuery(post_wrapper_class).length;
                console.log("reload is true:" + is_new);
                //go_disable_loading();
                var current = jQuery.featherlight.current();
                current.close();
                if (jQuery(post_wrapper_class).length > 0) {
                    jQuery(post_wrapper_class).replaceWith(res.wrapper);
                    jQuery( ".feedback_accordion" ).accordion({
                        collapsible: true
                    });
                    //go_blog_tags_select2();
                    go_disable_loading();
                }else{
                    location.reload();
                }

            }


            //});
        }
    });
}

function go_blog_trash( el ) {
    go_enable_loading( el );

    swal({
        title: "Are you sure?",
        text: "Do you really want to delete this post?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_trash;

                var blog_post_id= jQuery( el ).attr( 'blog_post_id' );

                var gotoSend = {
                    action:"go_blog_trash",
                    _ajax_nonce: nonce,
                    blog_post_id: blog_post_id,
                };
                //jQuery.ajaxSetup({ cache: true });
                jQuery.ajax({
                    url: MyAjax.ajaxurl,
                    type: 'POST',
                    data: gotoSend,
                    cache: false,
                    success: function (raw) {
                        jQuery("body").append(raw);
                        var post_wrapper_class = ".go_blog_post_wrapper_" + blog_post_id;
                        jQuery(post_wrapper_class).hide();
                        //location.reload();
                        jQuery(".go_blog_trash").off().one("click", function(e){
                            go_blog_trash( this );
                        });
                        swal("Poof! Your post has been deleted!", {
                            icon: "success",
                        });
                    }
                });
                go_disable_loading( el );


            } else {
                swal("Your post is safe!");
                go_disable_loading( el );
            }
        });

}

function go_get_tinymce_content_blog( source ){
    //console.log("html");
    if (jQuery("#wp-go_blog_post_edit-wrap .wp-editor-area").is(":visible")){
        //alert("content1");
        return jQuery('#wp-go_blog_post_edit-wrap .wp-editor-area').val();

    }else{
        //console.log("visual");
        //alert("content2");

        if (source == '_lightbox'){//this was a save in a lightbox
            //return tinymce.get('go_blog_post_lightbox').getContent();
            return go_tmce_getContent('go_blog_post_lightbox');
        }else{
            //return tinymce.get('go_blog_post').getContent();
            return go_tmce_getContent('go_blog_post');
        }



    }
}

function go_blog_user_task (user_id, task_id) {
    //jQuery(".go_datatables").hide();
    console.log("blogs!");
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_blog_user_task;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_blog_user_task',
            uid: user_id,
            task_id: task_id
        },
        success: function( res ) {

            jQuery.featherlight(res, {
                variant: 'blogs',
                afterOpen: function(event){
                    //console.log("fitvids"); // this contains all related elements
                    //alert(this.$content.hasClass('true')); // alert class of content
                    //jQuery("#go_blog_container").fitVids();
                    go_fit_and_max_only("#go_blog_container");
                }

            });


            if ( -1 !== res ) {

            }
        }
    });
}

/*
Based on: http://wordpress.stackexchange.com/questions/42652/#answer-42729
These functions provide a simple way to interact with TinyMCE (wp_editor) visual editor.
This is the same thing that WordPress does, but a tad more intuitive.
Additionally, this works for any editor - not just the "content" editor.
Usage:
0) If you are not using the default visual editor, make your own in PHP with a defined editor ID:
  wp_editor( $content, 'tab-editor' );

1) Get contents of your editor in JavaScript:
  tmce_getContent( 'tab-editor' )

2) Set content of the editor:
  tmce_setContent( content, 'tab-editor' )
Note: If you just want to use the default editor, you can leave the ID blank:
  tmce_getContent()
  tmce_setContent( content )

Note: If using a custom textarea ID, different than the editor id, add an extra argument:
  tmce_getContent( 'visual-id', 'textarea-id' )
  tmce_getContent( content, 'visual-id', 'textarea-id')

Note: An additional function to provide "focus" to the displayed editor:
  tmce_focus( 'tab-editor' )
=========================================================
*/
function go_tmce_getContent(editor_id, textarea_id) {
    if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
    if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;

    if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
        return tinyMCE.get(editor_id).getContent();
    }else{
        return jQuery('#'+textarea_id).val();
    }
}

function go_tmce_setContent(content, editor_id, textarea_id) {
    if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
    if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;

    if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
        return tinyMCE.get(editor_id).setContent(content);
    }else{
        return jQuery('#'+textarea_id).val(content);
    }
}

function go_tmce_focus(editor_id, textarea_id) {
    if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
    if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;

    if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
        return tinyMCE.get(editor_id).focus();
    }else{
        return jQuery('#'+textarea_id).focus();
    }
}


// @codekit-prepend 'scripts/sorttable.js'
// @codekit-prepend 'scripts/go_admin_page_admin_user.js'
// @codekit-prepend 'scripts/go_options.js'
// @codekit-prepend 'scripts/go_edit_store.js'
// @codekit-prepend 'scripts/buy_the_item.js'
// @codekit-prepend 'scripts/go_blogs.js'