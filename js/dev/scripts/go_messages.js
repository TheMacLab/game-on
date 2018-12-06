
function go_reset_opener(message_type){

    if (message_type == "multiple_messages") {
        //apply on click to the messages button at the top
        jQuery('.go_messages_icon_multiple_clipboard').parent().prop('onclick', null).off('click');
        jQuery(".go_messages_icon_multiple_clipboard").parent().one("click", function (e) {
            go_messages_opener(null, null, "multiple_messages");
        });
    }

    if (message_type == "single_reset") {
        //apply on click to the individual task reset icons
        jQuery('.go_reset_task_clipboard').prop('onclick', null).off('click');
        jQuery(".go_reset_task_clipboard").one("click", function () {
            go_messages_opener(this.getAttribute('data-uid'), this.getAttribute('data-task'), 'single_reset');
        });
    }

    if (message_type == "multiple_reset") {
        //apply on click to the reset button at the top
        jQuery('.go_tasks_reset_multiple_clipboard').parent().prop('onclick', null).off('click');
        jQuery(".go_tasks_reset_multiple_clipboard").parent().one("click", function () {
            go_messages_opener(null, null, 'multiple_reset');
        });
    }

    if (message_type == "single_message") {
        jQuery(".go_stats_messages_icon").prop('onclick', null).off('click');
        jQuery(".go_stats_messages_icon").one("click", function (e) {
            var user_id = this.getAttribute('data-uid');
            go_messages_opener(user_id, null, "single_message");
        });
    }

}

function go_messages_opener( user_id, post_id, message_type ) {
    post_id = (typeof post_id !== 'undefined') ?  post_id : null;
    message_type = (typeof message_type !== 'undefined') ?  message_type : null;
    console.log("type" + message_type);
    jQuery('.go_tasks_reset_multiple_clipboard').prop('onclick',null).off('click');

    var reset_vars = [];
    var uids = [];
    var post_ids = [];
    if (message_type == 'multiple_messages' || message_type == 'multiple_reset' ){//the reset button or messages button on clipboard was pressed
        var inputs = jQuery(".go_checkbox:visible");
        for(var i = 0; i < inputs.length; i++){
            if (inputs[i]['checked'] === true ){
                var uid = (inputs[i]).getAttribute('data-uid');
                var task = (inputs[i]).getAttribute('data-task');
                if (message_type == 'multiple_messages'){
                   task = "";
                }
                reset_vars.push({uid:uid, task:task});
            }
        }
    }
    else if (message_type == 'single_reset' || message_type == 'single_message'){ //single task reset or message was pressed
        reset_vars.push({uid:user_id, task:post_id});
    }
    //if only a uid was passed, this is just a send message to single user box (no reset)

    var nonce = GO_EVERY_PAGE_DATA.nonces.go_create_admin_message;
    var gotoSend = {
        action:"go_create_admin_message",
        _ajax_nonce: nonce,
        //post_id: post_ids,
        //user_id: user_id,
        message_type: message_type,
        reset_vars: reset_vars
    };
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type:'POST',
        data: gotoSend,
        success: function( results ) {
            //console.log(results);
            jQuery.featherlight(results, {variant: 'message'});

            jQuery('#go_message_submit').one("click", function(e){
                go_send_message(reset_vars, message_type);
            });

            go_reset_opener(message_type);

            jQuery('#go_messages_go_badges_select').select2({
                ajax: {
                    url: ajaxurl, // AJAX URL is predefined in WordPress admin
                    dataType: 'json',
                    delay: 400, // delay in ms while typing when to perform a AJAX search
                    data: function (params) {
                        return {
                            q: params.term, // search query
                            action: 'go_make_taxonomy_dropdown_ajax', // AJAX action for admin-ajax.php
                            taxonomy: 'go_badges',
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
                multiple: true,
                placeholder: "Show All",
                allowClear: true
            });

            jQuery('#go_messages_user_go_groups_select').select2({
                ajax: {
                    url: ajaxurl, // AJAX URL is predefined in WordPress admin
                    dataType: 'json',
                    delay: 400, // delay in ms while typing when to perform a AJAX search
                    data: function (params) {
                        return {
                            q: params.term, // search query
                            action: 'go_make_taxonomy_dropdown_ajax', // AJAX action for admin-ajax.php
                            taxonomy: 'user_go_groups',
                            is_hier: true
                        };
                    },
                    processResults: function( data ) {
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

            tippy('.tooltip', {
                delay: 0,
                arrow: true,
                arrowType: 'round',
                size: 'large',
                duration: 300,
                animation: 'scale',
                zIndex: 999999
            });

            jQuery('#go_additional_penalty_toggle').change(function () {
                var penalty = document.getElementById("go_additional_penalty_toggle").checked;
                //console.log(penalty);
                if (penalty == true){
                    jQuery("#go_penalty_table").css('display', 'block');
                }else{
                    jQuery("#go_penalty_table").css('display', 'none');
                }
            });

            jQuery('#go_custom_message_toggle').change(function () {
                var penalty = document.getElementById("go_custom_message_toggle").checked;
                //console.log(penalty);
                if (penalty == true){
                    jQuery("#go_custom_message_table").css('display', 'block');
                }else{
                    jQuery("#go_custom_message_table").css('display', 'none');
                }
            });



        },
        error: function(e, ts, et) {
            go_reset_opener(message_type);
        }
    });
}

function go_send_message(reset_vars, message_type) {
    var title = jQuery('[name=title]').val();
    if (message_type == "multiple_reset" || message_type == "single_reset"){
        message_type = "reset";
    }else {
        message_type = "message";
    }

    if (message_type == "reset"){
        var message_toggle =  document.getElementById("go_custom_message_toggle").checked;
        var additional_penalty_toggle =  document.getElementById("go_additional_penalty_toggle").checked;
    }
    else{
        var message_toggle =  null;
        var additional_penalty_toggle =  null;
    }

    if (message_type == "message" || (message_type == "reset" && message_toggle == true ) ){
        var message = jQuery('[name=message]').val();
    }
    else{
        var message = "";
    }


    if (message_type == "message" || (message_type == "reset" && additional_penalty_toggle == true ) ){
        if (message_type == "message" ){
            var xp_toggle = (jQuery('[name=xp_toggle]').siblings().hasClass("-on")) ? 1 : -1;
            var gold_toggle = (jQuery('[name=gold_toggle]').siblings().hasClass("-on")) ? 1 : -1;
            var health_toggle = (jQuery('[name=health_toggle]').siblings().hasClass("-on")) ? 1 : -1;
            var badges_toggle = jQuery('[name=badges_toggle]').siblings().hasClass("-on");
            var groups_toggle = jQuery('[name=groups_toggle]').siblings().hasClass("-on");
        }else{
            var xp_toggle = -1;
            var gold_toggle = -1;
            var health_toggle = -1;
            var badges_toggle = false;
            var groups_toggle = false;
        }
        var xp = jQuery('[name=xp]').val() * xp_toggle;
        var gold = jQuery('[name=gold]').val() * gold_toggle;
        var health = jQuery('[name=health]').val() * health_toggle;

        var badges = jQuery('#go_messages_go_badges_select').val();
        var groups = jQuery('#go_messages_user_go_groups_select').val();
    }
    else if (message_type == "reset" && additional_penalty_toggle == false ){
        var badges_toggle = false;
        var groups_toggle = false;
        var xp = 0;
        var gold = 0;
        var health = 0;
        var badges = null;
        var groups = null;
    }
    // send data
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_send_message;
    var gotoSend = {
        action:"go_send_message",
        _ajax_nonce: nonce,
        //post_id: post_id,
        reset_vars: reset_vars,
        message_type: message_type,
        title: title,
        message: message,
        xp: xp,
        gold: gold,
        health: health,
        badges_toggle: badges_toggle,
        badges: badges,
        groups_toggle: groups_toggle,
        groups: groups

    };
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type:'POST',
        data: gotoSend,
        success: function( results ) {
            // show success or error message
            jQuery("#go_messages_container").html("Message sent successfully.");
            jQuery( "#go_tasks_datatable" ).remove();
            go_stats_task_list();
            go_toggle_off();
        },
        error: function(e, ts, et) {
            jQuery("#go_messages_container").html("Error.");
        }
    });
}
