<?php

/*
 * ssa_events_AdminController class
 *
 * Sets up the admin pages
 * and prepare the ajax javascript files
 *
 * called by index.php
 *
 */

class ssa_events_AdminController {

    private $input = array();
    private $api_url = null;
    private $options = array();
    
    public function __construct() {

       
        
        
        
    }

    public function admin_menu(){
         // this key is called only when events want to be exported / emailed // sanitize it
        if (absint($_GET['e']) && substr($_GET['t'], 0, 1)=='p' ) {

            $this -> exportData('p');

        } 
         // this key is called only when events want to be exported / emailed // sanitize it
        if (absint($_GET['e']) && substr($_GET['t'], 0, 1)=='c' ) {

            $this -> exportData('c');

        }
       
        add_action('admin_enqueue_scripts', array($this, 'wpse30583_enqueue'));
        
        add_menu_page('Events', 'Events', 'manage_options', 'events', array($this, 'EventsPage'));
        add_submenu_page('events', 'Settings', 'Settings',  'manage_options', 'settings', array($this, 'SettingPage'));
       // add_options_page('Event instellingen', 'Instellingen', 'manage_options', 'events', array($this, 'SettingPage'));
       
    }
    
    
      
    public function wpse30583_enqueue($hook) {

        // only load on the right page!
        if ($hook == 'toplevel_page_events') {

            wp_deregister_script('jquery');
            wp_register_script('jquery', EVENTS_URL . '/admin/js/jquery-1.7.1.min.js');
            wp_enqueue_script('jquery');

            wp_enqueue_script('anytime', EVENTS_URL . '/admin/js/anytime.js');
            wp_enqueue_style('ui-style', EVENTS_URL . '/makeup/anytime.css');
            wp_enqueue_style('ui-style', EVENTS_URL . '/makeup/smoothness/jquery-ui-1.8.17.custom.css');

            //plugin
            wp_enqueue_style('event-style', EVENTS_URL . '/makeup/event_box.css');
            wp_enqueue_script('ajax-results', EVENTS_URL . '/admin/js/events_script.js', array('jquery'));

            // pass parameters to the script
            wp_localize_script('ajax-results', 'ajaxP', array('ajaxurl' => admin_url('admin-ajax.php'), 'postCommentNonce' => wp_create_nonce('events-nonce-123')));

        }

    }


     public function addSettings() {
        
        $this -> options = get_option('event_options');
        
        
        register_setting('event_options', 'event_options');

        add_settings_section('main', 'Event Settings', array($this, 'event_settings_text'), 'settings');
        add_settings_field('show_attendees', 'Aantal aanwezigen initieel zichtbaar?', array($this, 'show_attendees'), 'settings', 'main', array('label_for' => 'event_options[show_attendees]'));
        add_settings_field('export_delim', 'Export CSV scheidingsteken', array($this, 'export_delim'), 'settings', 'main', array('label_for' => 'event_options[export_delim]'));
        
       }
    
     /**
     *
     * Title text for the setting page
     *
     **/
    public function event_settings_text() {
 
        //echo '<br/><br/><p>Algemene instelling voor de events</p>';
    }
    /**
     *
     *
     *
     **/
    public function show_attendees() {
   
        echo "<input id='show_attendees' name='event_options[show_attendees]' type='text' value='{$this -> options['show_attendees']}' />
        <span class='description'>Laat dit aantal zien als gebruiker event binnen het bericht ziet. </span> ";

    }

    /**
     *
     *
     *
     **/
    public function export_delim() {
   
        echo "<input id='export_delim' name='event_options[export_delim]' type='text' value='{$this -> options['export_delim']}' />
        <span class='description'>Het teken wat gebruikt wordt tijdens de 'CSV' export van de events. (bijv: ; ) </span> ";

    }


    /**
     *
     * Building the actual pages via html
     * @return void
     *
     **/
    public function EventsPage() {

        include (EVENTS_DIR . 'admin/view/eventsPage.php');

    }
    /**
     *
     * Building the SettingPage page via html
     * @return void
     *
     **/
    public function SettingPage() {

        include (EVENTS_DIR . 'admin/view/eventSettings.php');

    }
    /**
     *
     * Building the export page
     * @return void
     *
     **/
    private function exportData($type) {

        include (EVENTS_DIR . 'admin/view/exportPage.php');

    }

    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
