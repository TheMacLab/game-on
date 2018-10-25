jQuery(document).ready(function(){
    //add on click
    jQuery('#go_tool_update').one("click", function() {go_update_go_ajax();});
    jQuery('#go_tool_update_no_loot').one("click", function() {go_update_go_ajax_no_task_loot();});
    jQuery('#go_reset_all_users').one("click", function() {go_reset_all_users_dialog();});
});

function go_update_go_ajax (){
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_upgade4;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_upgade4',
            loot: true
        },
        success: function( res ) {
alert ("Done.  Hope that helps :)")
        }
    });
}


function go_update_go_ajax_no_task_loot (){
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_upgade4;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_upgade4',
            loot: false
        },
        success: function( res ) {
            alert ("Done.  Hope that helps :)")
        }
    });
}

function go_reset_all_users_dialog (){

    swal({
      title: "Reset User Game Data",
      text: "Are you sure? This can't be undone!",
      icon: "warning",
      buttons: ["Cancel", "Reset All User Game Data"],
        dangerMode: true,
    }).
    then((willDelete) => {
        if (willDelete) {
            go_reset_all_users ();
        } else {
            swal("No action taken.");
        }
    });
    
}

function go_reset_all_users (){
    var nonce = GO_EVERY_PAGE_DATA.nonces.go_reset_all_users;
    jQuery.ajax({
        type: 'post',
        url: MyAjax.ajaxurl,
        data:{
            _ajax_nonce: nonce,
            action: 'go_reset_all_users'
        },
        success: function( res ) {
            swal("Success", "All user game data was reset.", "success");
        }
    });
}