<?php

function go_shortcode_button_add_button( $buttons ) {

        array_push($buttons, "separator", "go_shortcode_button");
        return $buttons;
}
add_filter( 'mce_buttons', 'go_shortcode_button_add_button', 0);

function go_shortcode_button_register( $plugin_array ) {
    $is_admin = go_user_is_admin();
    if($is_admin) {
    	$url = plugin_dir_url(dirname(dirname(__FILE__)));
        $url = $url . "js/scripts/go_shortcode_mce.js";
        $plugin_array['go_shortcode_button'] = $url;
        return $plugin_array;
    }
}
add_filter( 'mce_external_plugins', 'go_shortcode_button_register' );


?>