
function go_messages_opener( user_id, post_id, message_type ) {
    //console.log(message_type);
    jQuery('.go_messages_icon').prop('onclick',null).off('click'); //clipboard
    jQuery('#go_stats_messages_icon').prop('onclick',null).off('click'); //stats
    jQuery('.go_reset_task').prop('onclick',null).off('click'); //reset task links
    //jQuery('#go_messages_icon').prop('onclick',null).off('click'); //blog
    //remove the onclick events from any message link and then reattach after ajax call
    //types of links 1. clipboard 2. stats link 3. task reset button and 4. blog page
    //alert(user_id);

    if (!user_id){//no user_id sent, this is from the clipboard and get user ids from checkboxes
        var inputs = jQuery(".go_checkbox:visible");
        var user_ids = [];
        for(var i = 0; i < inputs.length; i++){
            if (inputs[i]['checked'] === true ){
                user_ids.push(jQuery(inputs[i]).val());
            }
        }
    }else{ //this is from the stats panel, so user_id was sent so stuff it in an array
        var user_ids = [user_id];
    }


    var nonce = GO_EVERY_PAGE_DATA.nonces.go_create_admin_message;
    var gotoSend = {
        action:"go_create_admin_message",
        _ajax_nonce: nonce,
        post_id: post_id,
        user_ids: user_ids,
        message_type: message_type
    };
    jQuery.ajax({
        url: MyAjax.ajaxurl,
        type:'POST',
        data: gotoSend,
        success: function( results ) {

            jQuery.featherlight(results, {variant: 'message'});

            jQuery('.go_tax_select').select2();
            jQuery('#go_message_submit').one("click", function(e){
                go_send_message(user_ids, post_id, message_type);
            });

            //clipboard
            jQuery(".go_messages_icon").one("click", function(e){
                go_messages_opener( );
            });

            //stats and blog
            var user_id = jQuery("#go_stats_messages_icon").attr("name");
            console.log("hi1" + user_id);
            jQuery("#go_stats_messages_icon").one("click", function(e){
                go_messages_opener(user_id);
            });

            //reset task links
            jQuery(".go_reset_task").one("click", function(e){
                go_messages_opener( user_id, this.id, 'reset' );
            });

            //blog
            //var user_id = jQuery("#go_stats_messages_icon").attr("name");
            //console.log("hi" + user_id);
            //jQuery("#go_stats_messages_icon").one("click", function(e){
            //    go_messages_opener( user_id );
            //});

        },
        error: function(e, ts, et) {
            //clipboard
            jQuery(".go_messages_icon").one("click", function(e){
                go_messages_opener( );
            });

            //stats and blog
            var user_id = jQuery("#go_stats_messages_icon").attr("name");
            jQuery("#go_stats_messages_icon").one("click", function(e){
                go_messages_opener( user_id );
            });

            //reset task links
            jQuery(".go_reset_task").one("click", function(e){
                go_messages_opener( user_id, this.id, 'reset' );
            });

            //blog
            //var user_id = jQuery("#go_stats_messages_icon").attr("name");
            //jQuery("#go_stats_messages_icon").one("click", function(e){
            //    go_messages_opener( user_id );
            //});

        }

    });


}

function go_send_message(user_ids, post_id, message_type) {
    //replace button with loader
    //check for negative numbers and give error

    //user_ids

    var title = jQuery('[name=title]').val();
    var message = jQuery('[name=message]').val();

    var xp_toggle = (jQuery('[name=xp_toggle]').siblings().hasClass("-on")) ? 1 : -1;
    var xp = jQuery('[name=xp]').val() * xp_toggle;

    var gold_toggle = (jQuery('[name=gold_toggle]').siblings().hasClass("-on")) ? 1 : -1;
    var gold = jQuery('[name=gold]').val() * gold_toggle;

    var health_toggle = (jQuery('[name=health_toggle]').siblings().hasClass("-on")) ? 1 : -1;
    var health = jQuery('[name=health]').val() * health_toggle;

    var c4_toggle =(jQuery('[name=c4_toggle]').siblings().hasClass("-on")) ? 1 : -1;
    var c4 = jQuery('[name=c4]').val() * c4_toggle;

    var badges = jQuery('#go_messages_go_badges_select').val();
    var badges_toggle = jQuery('[name=badges_toggle]').siblings().hasClass("-on");
    var groups = jQuery('#go_messages_user_go_groups_select').val();
    var groups_toggle = jQuery('[name=groups_toggle]').siblings().hasClass("-on");

    /*
    console.log(title);
    console.log(message);
    console.log(xp);
    console.log(xp_toggle);
    console.log(gold);
    console.log(gold_toggle);
    console.log(health);
    console.log(health_toggle);
    console.log(c4);
    console.log(c4_toggle);
    console.log(badges);
    console.log(badges_toggle);
    console.log(groups);
    console.log(groups_toggle);
    */

    // send data
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_send_message;
    var gotoSend = {
        action:"go_send_message",
        _ajax_nonce: nonce,
        post_id: post_id,
        user_ids: user_ids,
        message_type: message_type,
        title: title,
        message: message,
        xp: xp,
        gold: gold,
        health: health,
        c4: c4,
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
