<?php
/*
Plugin Name: Content Analys
Plugin URI: http://yeonactivities.nl
Description: Generate some basic content statistics
Version: 01
Author: Sander Schat
Author URI: http://yeonactivities.nl
*/



/*
 * Calculation Class
 *
 *
 * This will do the maths from the current state of content
 * It will show the current state for a time-frame,
 * When saved it will go to the table for future reference
 *
 *
 * Works together with the Counterize plugin
 * for reading the counts on any given day or periode
 *
 *
 */
function getIPs() {

if (getenv("HTTP_CLIENT_IP"))
$ip = getenv("HTTP_CLIENT_IP");
else if(getenv("HTTP_X_FORWARDED_FOR"))
$ip = getenv("HTTP_X_FORWARDED_FOR");
else if(getenv("REMOTE_ADDR"))
$ip = getenv("REMOTE_ADDR");
else
$ip = false;
return $ip;

}

function dev_switchs($ip){

    // ADD THE IP ADRESSES FOR SHOWING THE CUSTOMISED VERSION OF A THEME
    $dev_list = array (

        '95.97.136.127' //my home
        //, '145.107.3.67'  // innovam
        //, '77.160.218.95' // nancy home
    );

    return in_array($ip, $dev_list);

}


//only for me
//if(dev_switchs(getIPs())){

require 'engine/sqls.php';
require 'engine/ajaxed.php';
require 'admin/admin-page.php';
//require 'admin/admin-report.php';
//require 'tcpdf/config/lang/eng.php';
//require 'tcpdf/tcpdf.php';
//}




/* 
 * 
 * Update plugins
 * Update plugins
 * Update plugins
 * 
 */
include_once('admin/updater.php');
if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin

    $config = array(
        'slug' => plugin_basename(__FILE__) ,
        'debug' => false,
    );
    $updater = new SSA_PLUGIN_UPDATE($config);

}
        