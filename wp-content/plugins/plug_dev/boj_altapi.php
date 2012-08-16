<?php
/*
Plugin Name: BOJ Alternate Plugin API
Plugin URI: http://example.com/
Description: This plugin checks for a new version of itself against a self hosted API
Version: 1.0
Author: Ozh
Author URI: http://wrox.com/
*/

define( 'BOJ_ALT_API', 'http://code.sanderschat/' );

// For testing purpose, the site transient will be reset on each page load
add_action( 'init', 'boj_altapi_delete_transient' );
function boj_altapi_delete_transient() {
    delete_site_transient( 'update_plugins' );
}

// Hook into the plugin update check
add_filter('pre_set_site_transient_update_plugins', 'boj_altapi_check');

function boj_altapi_check( $transient ) {


    // Check if the transient contains the 'checked' information
    // If no, just return its value without hacking it
    if( empty( $transient->checked ) )
        return $transient;
    

    // The transient contains the 'checked' information
    // Now append to it information form your own API
    
    $plugin_slug = plugin_basename( __FILE__ );
    
    
    // POST data to send to your API
    $args = array(
        'action' => 'update-check',
        'plugin_name' => $plugin_slug,
        'version' => $transient->checked[$plugin_slug],
        'key' => 2134
    );
    
    // Send request checking for an update
    $response = boj_altapi_request( $args );

   
    // If response is false, don't alter the transient
    if( false !== $response ) {
        $transient->response[$plugin_slug] = $response;
    }
   
    return $transient;
}

// Send a request to the alternative API, return an object
function boj_altapi_request( $args ) {

    // Send request
    $request = wp_remote_post( BOJ_ALT_API, array( 'body' => $args ) );
    
    
    
    // Make sure the request was successful
    if( is_wp_error( $request )
    or
    wp_remote_retrieve_response_code( $request ) != 200
    ) {
        // Request failed
        return false;
    }


    
    // Read server response, which should be an object
    $response = unserialize( wp_remote_retrieve_body( $request ) );


    if( is_object( $response ) ) {
    
        return $response;

    } else {
        
        // Unexpected response
        return false;
    }



}


// Hook into the plugin details screen
add_filter('plugins_api', 'boj_altapi_information', 10, 3);

function boj_altapi_information( $false, $action, $args ) {

    $plugin_slug = plugin_basename( __FILE__ );

    // Check if this plugins API is about this plugin
    if( $args->slug != $plugin_slug ) {
        return false;
    }
        
    // POST data to send to your API
    $args = array(
        'action' => 'plugin_information',
        'plugin_name' => $plugin_slug,
        'version' => $transient->checked[$plugin_slug],
    );
    
    // Send request for detailed information
    $response = boj_altapi_request( $args );
    
    // Send request checking for information
    $request = wp_remote_post( BOJ_ALT_API, array( 'body' => $args ) );

    return $response;
}


