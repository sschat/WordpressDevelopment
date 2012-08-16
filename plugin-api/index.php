<?php

$action = $_REQUEST['action'];
$slug = $_REQUEST['plugin_name'];

// Create new object
$response = new stdClass;

switch( $action ) {

    // API is asked for the existence of a new version of the plugin
    case 'update-check':
        $response->slug = $slug;
        $response->new_version = '2.0';
        $response->url = 'http://example.com/boj-altapi/';
        $response->package = 'http://mydev.local/plugin-api/boj_altapi.zip';
        break;

    // Request for detailed information
    case 'plugin_information':
        $response->slug = 'boj_altapi.php';
        $response->plugin_name = 'boj_altapi.php';
        $response->new_version = '2.0';
        $response->requires = '2.9.2';
        $response->tested = '3.5';
        $response->downloaded = 12540;
        $response->last_updated = "2010-08-23";
        $response->sections = array(
            'description' => 'This plugin checks against a self-hosted API',
            'changelog' => 'New features added!'
        );        
        $response->download_link = 'http://mydev.local/plugin-api/boj_altapi.zip';
        break;
        
}

echo serialize( $response );
