<?php
/*
 Plugin Name: Bedrijfsscan
 Plugin URI: http://www.yeonactivities.nl/
 Description: Enquete voor uw website bezoekers
 Version: 1.2.3
 Author: Yeon activities
 Author URI: http://yeonactivities.nl/
 */

/* Change log
 *  1.2.3 
 *      css fix
 * 
 *  1.2.2 
 *      minor fixes
 *      added extra check on answers be filled
 * 
 *  1.2.1
 *      export pdf bug
 *      css fix
 * 
 *  1.2.0
 *      css 
 *      new export feature (pfd, excel, mailing list)
 * 
 *  1.1.0
 *      added plugin update feature
 *      added steps to header
 *      added extra settings (coloring options)
 * 
 * 1.0.1
 *      delete question fixed
 * 
 * 1.0 
 *      Initial version
 *
 */
 
// security check
if (!defined('WP_PLUGIN_URL')) {
    die("There is nothing to see here!");
}

define('SCAN_DIR', plugin_dir_path(__FILE__));
define('SCAN_URL', plugin_dir_url(__FILE__));

// define the tables
global $scan_table, $score_table, $question_table;

$scan_table = array(
    "columns" => array( 
    array("column" => "id", "name" => 'id', "type" => "id"), 
    array("column" => "naam", "name" => 'naam', "type" => "text"),
    array("column" => "type", "name" => 'type', "type" => "type"), 
    array("column" => "descr", "name" => 'Omschrijving', "type" => "textarea"), 
    array("column" => "options", "name" => 'options', "type" => "hide"),
    array("column" => "parent_id", "name" => 'pid', "type" => "parent")
    
    ), 
    "table" => "scan_types");

$score_table = array(
    "columns" => array( 
    array("column" => "id", "name" => 'id', "type" => "id"), 
    array("column" => "type", "name" => 'type', "type" => "type"),
    array("column" => "parent_id", "name" => 'pid', "type" => "parent"),
    array("column" => "start", "name" => 'Start', "type" => "text"), 
    array("column" => "end", "name" => 'Eind', "type" => "text"), 
    array("column" => "descr", "name" => 'Omschrijving', "type" => "textarea"),
    array("column" => "options", "name" => 'options', "type" => "hide")
    
    ), 
    "table" => "scan_scores");

$question_table = array(
    "columns" => array( 
    array("column" => "id", "name" => 'id', "type" => "id"), 
    array("column" => "parent_id", "name" => 'pid', "type" => "parent"),
    array("column" => "question", "name" => 'Vraag', "type" => "textarea"), 
    array("column" => "neg", "name" => 'Minst', "type" => "text"), 
    array("column" => "pos", "name" => 'Meest', "type" => "text"),
    array("column" => "options", "name" => 'options', "type" => "hide")
    ), 
    "table" => "scan_questions");
    
    
/*
 * Build the FRONT part
 *
 */

add_action('template_redirect', 'ScanFront');
function ScanFront() {

    include (SCAN_DIR . 'front/class/scanFront.php');
    
    $addSettings = new ssa_scan_scanFront();
    
}

/*
 * Build and work with the ADMIN menu
 *
 */

add_action('admin_menu', 'ScanAdmin');
function ScanAdmin() {

    include (SCAN_DIR . 'admin/class/ControllerScanAdmin.php');
    include (SCAN_DIR . 'admin/class/SettingsScanAdmin.php');

    include (SCAN_DIR . 'admin/class/AjaxScanAdmin.php');
    include (SCAN_DIR . 'admin/class/AjaxGetTablesScan.php');
    include (SCAN_DIR . 'admin/class/AjaxGetFormScan.php');
    
    include (SCAN_DIR . 'admin/class/SqlScanResults.php');
    
    $addSettings = new ssa_scan_SettingsScanAdmin();
    $scan = new ssa_scan_ControllerScanAdmin();

}


/* 
 * The AJAX stuff
 * 
 */

 function sanitizeValues($x){
     
      $attrs = $x; // just to get all in (since we cant be sure about the column names)
      
      //sanitize the incoming values (check as much as we can)
        switch ($x['what']) {
            case 'getQuestion':
                $attrs['what'] = 'getQuestion';
                break;
            case 'getTable':
                $attrs['what'] = 'getTable';
                break;
            case 'getScore':
                $attrs['what'] = 'getScore';
                break;
                
            default:
                $attrs['what'] = null;
                break;
        }
        switch ($x['type']) {
            
            case 'scan':
                $attrs['type'] = 'scan';
                break;
            case 'theme':
                $attrs['type'] = 'theme';
                break;
            case 'qst':
                $attrs['type'] = 'qst';
                break;    
            default:
                $attrs['type'] = null;
                break;
        }
        
        $attrs['id'] = absint($x['id']);
        $attrs['pid'] = absint($x['pid']);
        $attrs['value'] = absint($x['value']);
       
    //print_r($attrs);
    
    return $attrs;
 }
 
add_action('wp_ajax_ssa_scan_save', 'ssa_scan_save');
function ssa_scan_save() {

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];
    if (!wp_verify_nonce($nonce, 'scanDefine-nonce'))
        die('Busted!');
    
    if ( current_user_can('manage_options') ) {
        
        // load the stuff
        include (SCAN_DIR . 'admin/class/AjaxSaveScan.php');
        
        
        
        // set the params
        $attrs = array();
        $attrs = sanitizeValues($_POST['data']);
        
    
        $saveForm = new ssa_scan_AjaxSaveScan();
        $saveForm -> saveForm( $attrs );
    
    } else {
        
        echo "sorry...";
    }
    

    die ;

}
add_action('wp_ajax_ssa_scan_form', 'ssa_scan_form');
function ssa_scan_form() {

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];
    if (!wp_verify_nonce($nonce, 'scanDefine-nonce'))
        die('Busted!');
    
    if ( current_user_can('manage_options') ) {
        
    
      
        // set the params
        $attrs = array();
        $attrs = sanitizeValues($_POST['data']);
        
         // load the stuff
        include (SCAN_DIR . 'admin/class/AjaxGetFormScan.php');
        
        $getForm = new ssa_scan_AjaxGetFormScan();
        echo $getForm -> getEditForm( $attrs );
    
    } else {
        
        echo "sorry...";
    }

    die ;

}

add_action('wp_ajax_ssa_scan_delete', 'ssa_scan_delete');
function ssa_scan_delete() {

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];
    if (!wp_verify_nonce($nonce, 'scanDefine-nonce'))
        die('Busted!');
    
    if ( current_user_can('manage_options') ) {
        
    
    // load the stuff
    include (SCAN_DIR . 'admin/class/AjaxDeleteRow.php');
    
    // set the params
    $attrs = array();
    $attrs = sanitizeValues($_POST['data']);
    
   
    $deleteRow = new ssa_scan_DeleteRow($attrs);
    
    
    
    } else {
        
        echo 'sorry...';
    }

    die ;

}


add_action('wp_ajax_ssa_scan_showAdminScanResults', 'ssa_scan_showAdminScanResults');
function ssa_scan_showAdminScanResults() {


    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];
    if (!wp_verify_nonce($nonce, 'scanResults-nonce'))
        die('Busted!');
    
    
    // load the stuff
    include (SCAN_DIR . 'admin/class/SqlScanResults.php');
    
    // set the params
    $data = $_POST['data'];
    
    
    
    $showAdminScanResults = new ssa_scan_SqlScanResults($data);

    die ;

}

/* front 
 * we define only the "logged in" version. 
 * none logged in users, return -1
 */

add_action('wp_ajax_ssa_scan_savequestion', 'ssa_scan_savequestion');
function ssa_scan_savequestion() {

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];
    if (!wp_verify_nonce($nonce, 'scanQuestion-nonce'))
        die('Busted!');
    
    
    // load the stuff
    include (SCAN_DIR . 'front/class/saveQuestion.php');
    
    // set the params
    $attrs = array(); 
    $attrs = $_POST['data'];
    
  
    $saveQuestion = new ssa_scan_saveQuestion($attrs);


    die ;

}

add_action('wp_ajax_ssa_scan_showScanResults', 'ssa_scan_showScanResults');

function ssa_scan_showScanResults() {


    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    $nonce = $_POST['postCommentNonce'];
    if (!wp_verify_nonce($nonce, 'scanQuestion-nonce'))
        die('Busted!');
    
    // load the stuff
    include( SCAN_DIR . 'front/class/csscolor.php');
    include( SCAN_DIR . 'front/class/getScanResults.php');
    

    // set the params
    $scanID = $_POST['scanID'];

    $getScanResults = new ssa_scan_getScanResults($scanID);

    die ;

}



// register the plugin stuff
register_activation_hook(__FILE__, 'install_scan');
function install_scan(){
    
    // load the stuff
    include (SCAN_DIR . 'admin/class/scanInstaller.php');
    $install = new scanInstaller;
    $install -> install();

}
register_deactivation_hook(__FILE__, 'deinstall_scan');
function deinstall_scan(){
    
    // load the stuff
    include (SCAN_DIR . 'admin/class/scanInstaller.php');
    $install = new scanInstaller;
    $install -> deinstall();

}


/* 
 * 
 * Update plugins
 * Update plugins
 * Update plugins
 * 
 */
add_filter('pre_set_site_transient_update_plugins', 'ssa_scanapi_check');
add_filter('plugins_api', 'ssa_scanapi_information', 10, 3);
define('PLUGIN_SLUG', plugin_basename(__FILE__));
define('APIURL', "http://code.sanderschat.nl");

// Hook into the plugin update check
function ssa_scanapi_check($transient) {

    // Check if the transient contains the 'checked' information
    // If no, just return its value without hacking it
    if (empty($transient -> checked))
        return $transient;

    // The transient contains the 'checked' information
    // Now append to it information form your own API

    // POST data to send to your API
    $args = array('action' => 'update-check', 'plugin_name' => PLUGIN_SLUG, 'version' => $transient -> checked[PLUGIN_SLUG], 'k' => 2134);

    // Send request checking for an update
    $response = ssa_scanapi_request($args);

    // If response is false, don't alter the transient
    if (false !== $response) {
        $transient -> response[PLUGIN_SLUG] = $response;
    }

    return $transient;
}

// Send a request to the alternative API, return an object
function ssa_scanapi_request($args) {

    // Send request
    $request = wp_remote_post(APIURL, array('body' => $args));

    // Make sure the request was successful
    if (is_wp_error($request) or wp_remote_retrieve_response_code($request) != 200) {
        // Request failed
        return false;
    }

    // Read server response, which should be an object
    $response = unserialize(wp_remote_retrieve_body($request));

    if (is_object($response)) {

        return $response;

    } else {

        // Unexpected response

        return false;
    }

}

function ssa_scanapi_information($false, $action, $args) {

    // Check if this plugins API is about this plugin
    if ($args -> slug != PLUGIN_SLUG) {
        return false;
    }

    // POST data to send to your API
    $args = array('action' => 'plugin_information', 'plugin_name' => PLUGIN_SLUG, 'version' => $transient -> checked[PLUGIN_SLUG],'k' => 2134);

    // Send request for detailed information
    $response = ssa_scanapi_request($args);

    // Send request checking for information
    $request = wp_remote_post(APIURL, array('body' => $args));

    return $response;
}
