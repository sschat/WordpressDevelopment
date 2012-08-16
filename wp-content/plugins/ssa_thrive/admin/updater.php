<?php
// Prevent loading this file directly - Busted!
if (!defined('ABSPATH'))
    die('-1');

/**
 * @version 1.0
 * @author Sander Schat
 * @link http://www.yeon.nl
 * @package WP plugin updater
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright Copyright (c) 2012, yeon.nl
 *
 * GNU General Public License, Free Software Foundation
 * <http://creativecommons.org/licenses/GPL/2.0/>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
if (!class_exists('SSA_PLUGIN_UPDATE')) :

    class SSA_PLUGIN_UPDATE {

        /**
         * @since 1.0
         * @param array $config configuration
         * @return void
         */
        public function __construct($config = array()) {

            global $wp_version;


            if( ! is_admin() ) return false;
    
                
            $defaults = array(
                'slug' => plugin_basename(__FILE__) ,
                'apiurl' => 'http://code.sanderschat.nl/index.php/get',
                'debug' => false
               
            );


            $this -> config = wp_parse_args($config, $defaults);
            
            // POST data to send to your API
            $this -> args = array(
                'action' => 'update-check', 
                'plugin_name' => $this -> config['slug'],
                'k' => 7303, 
                'debug' => $this -> config['debug']
           );
            
            
            
            if ((defined('WP_DEBUG') && WP_DEBUG) || ($this -> config['debug']))
                add_action('admin_init', array($this, 'delete_transients'));

            add_filter('pre_set_site_transient_update_plugins', array($this, 'ssa_altapi_check'));
            add_filter('plugins_api', array($this, 'ssa_altapi_information'), 10, 3);

            // set timeout
            add_filter('http_request_timeout', array($this, 'http_request_timeout'));
            
            
            
       
                
    
        }
    
        
        /**
         * Callback fn for the http_request_timeout filter
         *
         * @since 1.0
         * @return int timeout value
         */
        public function http_request_timeout() {
            return 2;
        }

        /**
         * Delete transients (runs when WP_DEBUG is on)
         * For testing purposes the site transient will be reset on each page load
         *
         * @since 1.0
         * @return void
         */
        public function delete_transients() {

            delete_site_transient('update_plugins');
            delete_site_transient($this -> config['slug']);

        }

        // Hook into the plugin update check
        function ssa_altapi_check($transient) {

            // Check if the transient contains the 'checked' information
            // If no, just return its value without hacking it
            if (empty($transient -> checked))
                return $transient;

            // The transient contains the 'checked' information
            // Now append to it information form your own API

            // POST data to send to your API
            $args = $this -> args;
            $args = array( 'version' => $transient -> checked[$this -> config['slug']]);
            
            // Send request checking for an update
            $response = $this -> ssa_altapi_request($args);

            // If response is false, don't alter the transient
            if (false !== $response) {
                $transient -> response[$this -> config['slug']] = $response;

                set_site_transient($this -> config['slug'], $response, 60 * 60 * 6);
            }

            return $transient;
        }

        function ssa_altapi_information($false, $action, $args) {

            // Check if this plugins API is about this plugin
            if ($args -> slug != $this -> config['slug']) {
                return false;
            }

            // POST data to send to your API
            $args = $this -> args;
            $args = array( 'action' => 'plugin_information');
            
            // Send request for detailed information
            $response = ssa_altapi_request($args);

            // Send request checking for information
           // $request = wp_remote_post($this -> config['apiurl'], array('body' => $args));

            return $response;
        }

       
        // Send a request to the alternative API, return an object
        function ssa_altapi_request() {
            
            $args = $this -> args;
            
            // Send request
            $request = wp_remote_post($this -> config['apiurl'], array('body' => $args));

            // Make sure the request was successful
            if (is_wp_error($request) or wp_remote_retrieve_response_code($request) != 200) {
                // Request failed
                return false;
            }

            // Read server response, which should be an object
            $response = unserialize(wp_remote_retrieve_body($request));

            if ((defined('WP_DEBUG') && WP_DEBUG) || ($this -> config['debug'])) {

                echo $this -> config['apiurl'];
                echo "<br/>";
                print_r($args);
                echo "<br/>";
                print_r($response);
                die ;
            }

            if (is_object($response)) {

                return $response;

            } else {

                // Unexpected response
               
                return false;
            }

        }

            

    }

endif; // endif class exists
