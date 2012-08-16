<?php
/*
 Plugin Name: YEON FRAMEWORK PLUGIN
 Plugin URI: http://www.yeon.nl/
 Description: Deze plugin biedt de basis voor de YEON plugins en is nodig om die goed te laten werken
 Version: 1.0
 Author: Sander Schat
 Author URI: http://www.yeon.nl/
 */

/* Change log
 *
 * 1.0 Initial version
 *
 */

 /*
  * INIT PART
  */ 
  
class ssa_framework_class {

    function __construct(){
        
        // ADD THE ADMIN MENU OPTIONS FOR THIS PLUGIN
        include('ssa_framework_adminmenu.php');
        $admin_menu = new ssa_framework_adminmenu;
        
        /* the basix */
        add_action('init', array($this, 'init') );
        
        // ON PLUGIN ACTIVATION 
        register_activation_hook(__FILE__, array($this,  'activation_hook'));
    
    
    }
    
    
    public function init() {
        
        include_once('includes/php/updater.php');
        
        if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    
            $config = array(
                'slug' => plugin_basename(__FILE__) ,
                'debug' => false
            );
            
            $this -> updater = new SSA_PLUGIN_UPDATE($config);
            
        }
    
    }
    
    function activation_hook(){
        global $wpdb;
        
        
        /**@todo get license in */
        
       
    }
    
    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }
    
}


if (class_exists('ssa_framework_class')) {
    $start = new ssa_framework_class;
}

 

 
 