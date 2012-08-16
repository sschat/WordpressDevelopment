<?php

/*
 Plugin Name: Yeon activities Plugin set for Wordpres
 Plugin URI: http://www.Yeonactivities.nl/
 Description: Yeon activities Plugin set for Wordpress. Enable this plugin and select the specific libaries to be included in the theme
 Version: 1.0
 Author: Yeon activities
 Author URI: http://Yeonactivities.nl/
 */

/* Change log
 *
 * 1.0 Initial version
 *
 */
/*

 foreach directory in the libary, check if it has an index.php
 if so, include it in the admin screen

 in the admin screen, turn it on or off (default is "on", for easy insertation)

 foreach "not off",
 * check if still exist,
 * if so include the index.php of that directory

 */

 /*
  * TO DO: Make this work better , fool proof
  * 
  * check for index.php, instead of assuming it
  * 
  * /
 
 
/* INIT */
$dirList = array();


define('YA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YA_PLUGIN_URL', plugin_dir_url(__FILE__));
DEFINE('YA_LIBARY_DIR' , YA_PLUGIN_DIR . "libary/" );
DEFINE('YA_LIBARY_URL' , YA_PLUGIN_URL . "libary/" );



/* 
 * ALL actions in this directory fire in general (like login)
 * 
 */
includeFiles( YA_LIBARY_DIR . 'general/'); 

/* 
 * ALL actions in this directory only fire on the FRONT SITE
 * 
 */

add_action('template_redirect', 'FrontAlters');
function FrontAlters() {


    includeFiles( YA_LIBARY_DIR . 'front/');
 
}

/* 
 * ALL actions in this directory only fire on the ADMIN SITE
 * 
 */

add_action('admin_menu', 'AdminAlters');
function AdminAlters() {


     includeFiles( YA_LIBARY_DIR . 'admin/'); 
   

}

function includeFiles( $path ){

    /* Generate the directory list */
    foreach (new DirectoryIterator( $path ) as $dir) {
            
            $dirList[] = $dir->getFilename();
    
    }
    
    if($dirList){
        $bad = array(".", "..", ".DS_Store", "_notes", "Thumbs.db");
        $dirList = array_diff($dirList, $bad);
    }   
    
    
     $features =& $dirList;    
     foreach( $features as $feature )
     {
         
                
        if(file_exists($path . $feature . '/index.php') ){
            include( $path . $feature . '/index.php' );
        }
     }
 
}

