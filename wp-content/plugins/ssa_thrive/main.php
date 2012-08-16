<?php
/*
 Plugin Name: THRIVE 
 Plugin URI: http://www.yeon.nl/
 Description: Thrive your community. Let them perform tasks and give them content in return
 Version: 1.1
 Author: Sander Schat
 Author URI: http://www.yeon.nl/
 */

 /* Change log
  * 
  * 1.1 new updater added
  * 1.0 Initial version  / Trail at APKEXPERTS 23-01-12
  *  
  */
 
 
// security check
if (!defined('WP_PLUGIN_URL')) {
    die("There is nothing to see here!");
}

define('THRIVE_DIR', plugin_dir_path(__FILE__));
define('THRIVE_URL', plugin_dir_url(__FILE__));


include ('front/class/controller.thrive.php');
include ('front/class/model.thriveEngine.php');
include ('front/class/model.dbGateway.php');
include ('front/class/view.thriveView.php');


$thrive = new ThriveClass;
$thrive -> registerHooksAndActions();

// ADMIN PAGE
include ('admin/widgets/widget.alertbox.php');
include ('admin/class.thriveAdminClass.inc.php');
$thriveAdmin = new thriveAdminClass;



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
        



/**
 * TODO
 *
// install procedure
 * 
 *
 * front: check if user has matched some rules. If so, search if this rule is used. If so, let user know he "accedently" unlock some content
 *  think about: showing message just one time, or user can dismiss message (option in admin)
 *  add_action( save_post / save comment / login )
 *
 *
 *
 * dynamic progress bar ( adjust to width)
 *
 *
 * inspiration: visitor.js
 * add website timer for logged in user. Unlock when user spent "x" minutes on site
 *
 * link it to "question" custom type
 * object "question"
 *
 * link it to followication
 *
 *
 **/
