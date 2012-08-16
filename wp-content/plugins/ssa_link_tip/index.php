<?php

/*
Plugin Name: Link Tip
Plugin URI:
Version: 0.1
Desciption:
Author: Sander Schat.
Author URI:
Text Domain:
Domain Path: 
License:
 *
*/
//
//// add shortcode
// add input field
// while typing, ajax call
// figure out what url it is
// retrieve its data
// show it in extra field

// security check
if( !defined( 'WP_PLUGIN_URL' ) )
{
	die( "There is nothing to see here!" );
}


define('LL_DIR', plugin_dir_path(__FILE__) );
define('LL_URL', plugin_dir_url(__FILE__) );

define('LL_FUNCTION_DIR', LL_DIR . '/functions' );
define('LL_FRONT_URL', LL_URL . '/front' );
    
 
// ADD THE STATUS TYPE
include( LL_FUNCTION_DIR . '/ct_status_type.php');

// ADD THE functions who do the work
include( LL_FUNCTION_DIR .'/functions.php');

// initialize the stuff
include( LL_FUNCTION_DIR .'/initiliaze.php');
    

