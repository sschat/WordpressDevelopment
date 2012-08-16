<?php

/*
 * ControllerScanAdmin class
 * 
 * Sets up the admin pages
 * and prepare the ajax javascript files
 * 
 * called by index.php
 *
 */

class ssa_scan_ControllerScanAdmin {

    private $input = array();
   

    public function __construct() {
        
        //is this an export?
        $export = $_POST['export'] ? substr($_POST['export'], 0, 1 ) : FALSE;
        $SCANID = $_POST['selectScanId'] ? absint($_POST['selectScanId']) : FALSE;
        
        if ( $SCANID ) {

              $this -> exportData($export, $SCANID);

        } 
        
        // the input fields
        $this->input['scan'] = absint($_GET['scan']);
        $this->input['theme'] = absint($_GET['theme']);
        $this->input['qst'] = absint($_GET['qst']);
        
       

        add_menu_page('Scan', 'Scan', 'manage_options', 'scan', array($this, 'ScanSettingPage'));
        add_submenu_page('scan', 'Scans', 'Scans', 'manage_options', 'scans', array($this, 'ScanDefinePage'));
        add_submenu_page('scan', 'Resultaat', 'Resultaat', 'manage_options', 'scanresults', array($this, 'ScanResultPage'));

        add_action('admin_enqueue_scripts', array($this, 'wpse30583_enqueue'));

    }




    public function wpse30583_enqueue($hook) {
        
 
        // header scripts for the result page
        if($hook == 'scan_page_scanresults' ){
            
            
            wp_enqueue_style('scan_style', SCAN_URL . 'makeup/scan.css');
            
            
            wp_deregister_script( 'jquery' );
            wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js');
            wp_enqueue_script( 'jquery' );    
        
        // embed the javascript file that makes the AJAX request
            wp_enqueue_script('ajax-results', SCAN_URL . '/admin/js/results.js', array('jquery'));
       
        // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
            wp_localize_script('ajax-results', 'ajaxResults', array(
            // URL to wp-admin/admin-ajax.php to process the request
                'ajaxurl' => admin_url('admin-ajax.php'),
                
            
                
            // generate a nonce with a unique ID "myajax-post-comment-nonce"
            // so that you can check it later when an AJAX request is sent
                'postCommentNonce' => wp_create_nonce('scanResults-nonce')));
    
        }

        
        // only load on the right page!
        if($hook == 'scan_page_scans'){
        
           
            wp_deregister_script( 'jquery' );
            wp_register_script( 'jquery', 'http://code.jquery.com/jquery-latest.min.js');
            wp_enqueue_script( 'jquery' );    
            
            // embed the javascript file that makes the AJAX request
            wp_enqueue_script('my-ajax-request', SCAN_URL . '/admin/js/scanScript.js', array('jquery'));
    
            // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
            wp_localize_script('my-ajax-request', 'MyAjax', array(
            // URL to wp-admin/admin-ajax.php to process the request
                'ajaxurl' => admin_url('admin-ajax.php'),
                
            // Get the page variables to the front    
                'i_scanID' => $this->input['scan'],
                'i_themeID' => $this->input['theme'],
                'i_qsID' => $this->input['qst'],
                
            // generate a nonce with a unique ID "myajax-post-comment-nonce"
            // so that you can check it later when an AJAX request is sent
                'postCommentNonce' => wp_create_nonce('scanDefine-nonce')));
    
        }
        
        // scripts for the scan settings page
        if ($hook == 'toplevel_page_scan'){
            
            wp_enqueue_script("color_picker_js", SCAN_URL . '/admin/jpicker-1.1.6/jpicker-1.1.6.min.js');
            wp_enqueue_style("color_picker_css", SCAN_URL . '/admin/jpicker-1.1.6/css/jPicker-1.1.6.min.css');

            
        }
                

    }

    /**
     *
     * Building the actual pages via html
     * @return void
     *
     **/
    public function ScanSettingPage() {

        include (SCAN_DIR . 'admin/view/ScanSettingPage.php');

    }

    public function ScanDefinePage() {

        include (SCAN_DIR . 'admin/view/ScanDefinePage.php');
        
    }

    public function ScanResultPage() {

        include (SCAN_DIR . 'admin/view/ScanResultPage.php');
        
    }

    /**
     *
     * Building the export page
     * @return void
     *
     **/
    private function exportData($export, $SCANID) {

        include (SCAN_DIR . 'admin/view/exportPage.php');

    }
    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }

}
