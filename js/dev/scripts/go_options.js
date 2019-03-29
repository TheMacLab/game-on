
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
