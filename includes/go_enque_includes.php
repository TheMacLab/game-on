<?php

/**
 * Place external JS in the footer
 * Used as the last param to wp_register_script() and wp_enqueue_script()
 */
$js_in_footer = true;
/**
 * URL strings for external scripts
 */
// JS



$go_select2_js_url       = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js';

//Datatables
//$go_datatables_js_url    = 'https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/r-2.2.2/sl-1.3.0/datatables.min.js';
$go_datatables_js_url    = 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.1/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.js';

$go_datatables_ns_js_url = 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/natural.js';
$go_pdfmake_js_url       = 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js';
$go_pdfmake_fonts_js_url  = 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js';
//$go_datatables_css_url   = 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css';
$go_datatables_css_url   = 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-html5-1.5.6/b-print-1.5.6/cr-1.5.0/r-2.2.2/sl-1.3.0/datatables.min.css';



$go_featherlight_js_url  = 'https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.js';
$go_noty_js_url          = 'https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js'; 
$go_sweetalert_js_url    = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js';
$go_tippy_js_url		= 'https://unpkg.com/tippy.js@3/dist/tippy.all.min.js';

//DateRangePicker
$go_moment_js_url           = 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js';
$go_daterangepicker_js_url  = 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js';
$go_daterangepicker_css_url = 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css';

// CSS
$go_select2_css_url      = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css';
$go_featherlight_css_url = 'https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.css';
$go_noty_css_url         = 'https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css';
$font_awesome_url = 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';

/*
 * Define what scripts/styles need to be enqueued on the admin side.
 *
 * TODO: define what scripts/styles need to be loaded on per-page basis.
 * 
 * https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
 * 
 * The above URL notes that pages can be specified via the $hook parameter 
 * to the includes() function. 
 *
 */
/**
 * @param $hook
 */
function go_admin_includes ($hook) {

    // Bring variables from beginning of file into function scope
    global $js_in_footer;
    global $go_select2_js_url, $go_datatables_js_url, $go_datatables_ns_js_url,
       $go_pdfmake_js_url, $go_pdfmake_fonts_js_url, $go_featherlight_js_url,
       $go_noty_js_url, $go_sweetalert_js_url, $go_select2_css_url,
       $go_datatables_css_url, $go_featherlight_css_url, $go_noty_css_url, $go_tippy_js_url, $font_awesome_url, $go_moment_js_url, $go_daterangepicker_js_url, $go_daterangepicker_css_url;

    /**
     * Date Range Picker
     * http://www.daterangepicker.com/
     */
    if ($hook == 'toplevel_page_go_clipboard'){
        wp_register_script( 'go_moment_js_url', $go_moment_js_url, array( 'jquery' ),'v1.1', false);
        wp_enqueue_script( 'go_moment_js_url' );

        wp_register_script( 'go_daterangepicker_js_url', $go_daterangepicker_js_url, array( 'jquery' ),'v1.1', false);
        wp_enqueue_script( 'go_daterangepicker_js_url' );

        wp_register_style( 'go_daterangepicker_css_url', $go_daterangepicker_css_url, null, 1.112);
        wp_enqueue_style( 'go_daterangepicker_css_url' );
    }


    /**
     * jQuery theme
     * https://select2.org/
     */
    wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', null, 1.112 );
    wp_enqueue_style( 'jquery-ui-css' );

    /**
     * Font Awesome
     * https://fontawesome.com/v4.7.0/icons/
     */
    wp_register_style ('font-awesome', $font_awesome_url , null, 4.7 );
    wp_enqueue_style('font-awesome');


    /**
     * TIPPY (TOOLTIP LIBRARY)
     */
    wp_register_script( 'go_tippy', $go_tippy_js_url, array( 'jquery' ),'v1.1', $js_in_footer);
    wp_enqueue_script( 'go_tippy' );
   
    /**
     * Select 2 by Kevin Brown
     * https://select2.org/
     */
    wp_register_script( 'go_select2', $go_select2_js_url, array( 'jquery' ),'v1.1', $js_in_footer );
    wp_enqueue_script( 'go_select2' );

    wp_register_style( 'go_select2_css', $go_select2_css_url ); 
    wp_enqueue_style( 'go_select2_css' );

    /**
     * Datatables by SpryMedia Ltd.
     * https://datatables.net/
     */

    //wp_register_script( 'go_datatables', plugin_dir_url( __FILE__ ).'DataTables/datatables.min.js', array( 'jquery' ),'v1', false);
    //wp_register_script( 'go_datatables', 'https://cdn.datatables.net/v/ju/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.1/b-html5-1.5.2/b-print-1.5.2/cr-1.5.0/fc-3.2.5/fh-3.1.4/kt-2.4.0/r-2.2.2/sc-1.5.0/sl-1.2.6/datatables.min.js', array( 'jquery' ),'v1.1', false);
    wp_register_script( 'go_datatables', $go_datatables_js_url, array( 'jquery' ),'v1.1', false);

    wp_enqueue_script( 'go_datatables' );
    // Natural sort plugin
    // https://datatables.net/plug-ins/sorting/natural
    wp_register_script( 'go_natural_sort', $go_datatables_ns_js_url, array( 'jquery' ), 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_natural_sort' );

    wp_register_style( 'go_datatables_css_url', $go_datatables_css_url );
    wp_enqueue_style( 'go_datatables_css_url' );


    /**
     * PDF Make by Bartek Pampuch
     * http://pdfmake.org
     */
    wp_register_script( 'go_pdf_make', $go_pdfmake_js_url, null, 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_pdf_make' );

    wp_register_script( 'go_pdf_make_fonts', $go_pdfmake_fonts_js_url, null, 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_pdf_make_fonts' );

    /**
     * Featherlight by Noel Bossart
     * https://noelboss.github.io/featherlight/
     */
    wp_register_script( 'go_featherlight', $go_featherlight_js_url , null, 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_featherlight' );

    wp_register_style( 'go_featherlight_css', $go_featherlight_css_url, null,'v1.7.13' );
    wp_enqueue_style( 'go_featherlight_css' );

    /**
     * noty by Nedim Arabaci
     * ned.im/noty/
     */
    wp_register_script( 'go_noty', $go_noty_js_url, null,'v1', $js_in_footer );
    wp_enqueue_script( 'go_noty' );

    wp_register_style( 'go_noty_css', $go_noty_css_url );
    wp_enqueue_style( 'go_noty_css' );

    /**
     * Sweet Alert
     */
    wp_register_script( 'go_sweet_alert', $go_sweetalert_js_url, null,'v1', $js_in_footer );
    wp_enqueue_script( 'go_sweet_alert' );

    /**
     * Tabs
     */
    wp_enqueue_script( 'jquery-ui-tabs', null, null, $js_in_footer);

}

/**
 * Define what scripts/styles need to be enqueued on the public side.
 * @param $hook
 */
function go_includes () {

    // Bring variables from beginning of file into function scope
    global $js_in_footer;
    global $go_select2_js_url, $go_datatables_js_url, $go_datatables_ns_js_url,
       $go_pdfmake_js_url, $go_pdfmake_fonts_js_url, $go_featherlight_js_url,
       $go_noty_js_url, $go_sweetalert_js_url, $go_select2_css_url,
       $go_datatables_css_url, $go_featherlight_css_url, $go_noty_css_url, $font_awesome_url, $go_tippy_js_url,
           $go_moment_js_url, $go_daterangepicker_js_url, $go_daterangepicker_css_url;


        wp_register_script( 'go_moment_js_url', $go_moment_js_url, array( 'jquery' ),'v1.1', false);
        wp_enqueue_script( 'go_moment_js_url' );

        wp_register_script( 'go_daterangepicker_js_url', $go_daterangepicker_js_url, array( 'jquery' ),'v1.1', false);
        wp_enqueue_script( 'go_daterangepicker_js_url' );

        wp_register_style( 'go_daterangepicker_css_url', $go_daterangepicker_css_url, null, 1.112);
        wp_enqueue_style( 'go_daterangepicker_css_url' );



    wp_enqueue_media();

    /**
     * jQuery theme
     * https://select2.org/
     */
    wp_register_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css', null, 1.112 );
    wp_enqueue_style( 'jquery-ui-css' );

    /**
     * TIPPY (TOOLTIP LIBRARY)
     */
    wp_register_script( 'go_tippy', $go_tippy_js_url, array( 'jquery' ),'v1.1', $js_in_footer);
    wp_enqueue_script( 'go_tippy' );


    /**
     * Font Awesome
     * https://fontawesome.com/v4.7.0/icons/
     */
    wp_register_style ('font-awesome', $font_awesome_url , null, 4.7 );
    wp_enqueue_style('font-awesome');

    /**
     * Select 2 by Kevin Brown
     * https://select2.org/
     */
    wp_register_script( 'go_select2', $go_select2_js_url, array( 'jquery' ),'v1.1', $js_in_footer );
    wp_enqueue_script( 'go_select2' );

    wp_register_style( 'go_select2_css', $go_select2_css_url );
    wp_enqueue_style( 'go_select2_css' );

    /**
     * Datatables by SpryMedia Ltd.
     * https://datatables.net/
     */
    wp_register_script( 'go_datatables', $go_datatables_js_url, array( 'jquery' ),'v1.1', $js_in_footer );
    wp_enqueue_script( 'go_datatables' );
    // Natural sort plugin
    // https://datatables.net/plug-ins/sorting/natural
    wp_register_script( 'go_natural_sort', $go_datatables_ns_js_url, array( 'jquery' ), 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_natural_sort' );

    wp_register_style( 'go_datatables_css', $go_datatables_css_url );
    wp_enqueue_style( 'go_datatables_css' );

    /**
     * PDF Make by Bartek Pampuch
     * http://pdfmake.org
     */
    wp_register_script( 'go_pdf_make', $go_pdfmake_js_url, array( 'jquery' ), 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_pdf_make' );

    wp_register_script( 'go_pdf_make_fonts', $go_pdfmake_fonts_js_url, array( 'jquery' ),'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_pdf_make_fonts' );

    /**
     * Frontend Media
     */
    wp_register_script( 'go_frontend_media', plugin_dir_url( __FILE__ ).'wp-frontend-media-master/js/frontend.js', array( 'jquery' ), '2015-05-07', true);
    //wp_enqueue_script( 'go_frontend_media' );

    /**
     * Featherlight by Noel Bossart
     * https://noelboss.github.io/featherlight/
     */
    wp_register_script( 'go_featherlight', $go_featherlight_js_url, null, 'v1.7.13', $js_in_footer );
    wp_enqueue_script( 'go_featherlight' );

    wp_register_style( 'go_featherlight_css', $go_featherlight_css_url, null, 'v1.7.13' );
    wp_enqueue_style( 'go_featherlight_css' );


    /**
     * Sweet Alert
     */
    wp_register_script( 'go_sweet_alert', $go_sweetalert_js_url, null,'v1', $js_in_footer );
    wp_enqueue_script( 'go_sweet_alert' );


    /**
     * Collapsible Lists by Kate Morley
     * http://code.iamkate.com/javascript/collapsible-lists/ 
     */
    wp_register_script( 'go_collapse_lists', plugin_dir_url( __FILE__ ).'CollapsibleLists.js', array( 'jquery' ),'v2', $js_in_footer );
    wp_enqueue_script( 'go_collapse_lists' );

    /**
     * noty by Nedim Arabaci
     * ned.im/noty/
     */
    wp_register_script( 'go_noty', $go_noty_js_url, '','v1', $js_in_footer );
    wp_enqueue_script( 'go_noty' );

    wp_register_style( 'go_noty_css', $go_noty_css_url );
    wp_enqueue_style( 'go_noty_css' );

    /**
     * Tabs
     */
    wp_enqueue_script( 'jquery-ui-tabs', null, null, null, $js_in_footer );
}

?>
