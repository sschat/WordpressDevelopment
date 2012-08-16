<?php
/*
 Plugin Name: Bedrijf events
 Plugin URI: http://www.yeonactivities.nl/
 Description: Laat uw bezoekers zich aanmelden voor uw events
 Version: 1.3.9
 Author: Yeon activities
 Author URI: http://yeonactivities.nl/
 */

/* Change log
 * 
 * 1.4.0 new plugin version control
 * 1.3.9 small fix on activation 
 *       fix on login in js
 *    
 * 1.3.7 small fix on widget
 * 1.3.6 fix : no foto on current user
 * 1.3.5 fix : userid clashed with eventid
 * 1.3.4 fix
 * 1.3.3 js fix
 * 1.3.2 small fixes (inlog, "showall")
 * 1.3.1 Small fixes (css, user photo)
 * 1.3   Added setting page / added CSV export
 * 1.2.0 Added inlog / show all button / links / afmeld button
 * 1.1.0 Added the widgets
 * 
 * 1.0.0 Initial version 
 *
 */

// security check
if (!defined('WP_PLUGIN_URL')) {
    die("There is nothing to see here!");
}

define('EVENTS_DIR', plugin_dir_path(__FILE__));
define('EVENTS_URL', plugin_dir_url(__FILE__));
define('PLUGIN_SLUG', plugin_basename(__FILE__));


/*
 * 
 * Build the FRONT part
 * Build the FRONT part
 * Build the FRONT part
 *
 */

add_action('template_redirect', 'EventFront');
function EventFront() {

    include (EVENTS_DIR . 'front/class/eventFront.php');

    $addSettings = new ssa_events_Front();

}


/*
 * 
 * Build the ADMIN menu
 * Build the ADMIN menu
 * Build the ADMIN menu
 * 
 */

add_action('admin_menu', 'ssa_event_admin_menu');
function ssa_event_admin_menu() {
    
    
    include_once (EVENTS_DIR . 'admin/class/Controller.php');

    $admin_menu = new ssa_events_AdminController();
    $admin_menu -> admin_menu();

}
add_action('admin_init', 'ssa_event_admin_init' );
function ssa_event_admin_init(){
    
    
    include_once (EVENTS_DIR . 'admin/class/Controller.php');

    $addSettings = new ssa_events_AdminController();
    $addSettings -> addSettings();
    
}

/*
 * WIDGETS
 * WIDGETS
 * WIDGETS
 * 
 */ 
// add the widgets
include (EVENTS_DIR . 'admin/class/eventWidgets.php');
add_action('widgets_init', 'event_widgets_init');
function event_widgets_init() {
        register_widget('ssa_event_attendees');
}
 
/*
 * 
 * AJAX CALLS AND FUNCTIONS
 * AJAX CALLS AND FUNCTIONS
 * AJAX CALLS AND FUNCTIONS
 * 
 */
function sanitizeEventValues($values) {

    $attrs = $values;
    // just to get all in (since we cant be sure about the column names)

    //sanitize the incoming values (check as much as we can)
    switch ($values['what']) {
        case 'new' :
            $attrs['what'] = 'new';
            break;

        case 'save' :
            $attrs['what'] = 'save';
            break;

        case 'edit' :
            $attrs['what'] = 'edit';
            break;

        case 'delete' :
            $attrs['what'] = 'delete';
            break;

        case 'get' :
            $attrs['what'] = 'get';
            break;

        case 'set' :
            $attrs['what'] = 'set';
            break;

        default :
            $attrs['what'] = null;
            break;
    }

    $attrs['id'] = absint($values['id']);
    $attrs['eventID'] = absint($values['eventID']);
    $attrs['userID'] = absint($values['userID']);
    $attrs['register_post'] = $values['register_post'];
    
    
    //print_r($attrs);

    return $attrs;
}

add_action('wp_ajax_ssa_event_ajax', 'ssa_event_ajax');
function ssa_event_ajax() {

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];

    if (!wp_verify_nonce($nonce, 'events-nonce-123'))
        die('Busted!');

    if (current_user_can('manage_options')) {

        // load the stuff
        include (EVENTS_DIR . 'admin/class/ajax.php');

        $values = sanitizeEventValues($_POST['values']);
        $ajax = new ssa_events_admin_ajax($values);

    } else {

        echo "sorry...";
    }

    die ;

}

// used for the front part (button)
add_action('wp_ajax_ssa_event_front_ajax', 'ssa_event_front_ajax');
function ssa_event_front_ajax() {

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];

    if (!wp_verify_nonce($nonce, 'events-nonce-123'))
        die('Busted!');

    // load the stuff
    include (EVENTS_DIR . 'admin/class/ajax.php');

    $values = sanitizeEventValues($_POST['values']);
    $ajax = new ssa_events_admin_ajax($values);

    die ;

}

/* 
 * register the plugin stuff
 * register the plugin stuff
 * register the plugin stuff
 * 
 */
register_activation_hook(__FILE__, 'install_event');
function install_event() {

    // load the stuff
    include (EVENTS_DIR . 'admin/class/eventInstaller.php');
    $install = new eventInstaller;
    $install -> install();

}

register_deactivation_hook(__FILE__, 'deinstall_event');
function deinstall_event() {

    // load the stuff
    include (EVENTS_DIR . 'admin/class/eventInstaller.php');
    $install = new eventInstaller;
    $install -> deinstall();

}



/* 
 * 
 * Update plugins
 * Update plugins
 * Update plugins
 * 
 */
include_once('admin/class/updater.php');
if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin

    $config = array(
        'slug' => plugin_basename(__FILE__) ,
        'debug' => false,
    );
    $updater = new SSA_PLUGIN_UPDATE($config);

}
        

