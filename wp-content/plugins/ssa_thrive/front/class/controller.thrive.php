<?php
/**
 * This class is the controller
 * it generates the shortcode and its input
 *
 * input : rules (number)
 *
 * check this details for this rule and this user in the engine
 * return is "oke", then show content
 * if not, get the message box in
 *
 *
 */
class ThriveClass {

   
    
    private $options = array();
    private $engine = null;


    public function registerHooksAndActions() {

        add_action('init', array($this, "ssa_thrive_init"));

        add_action('wp_enqueue_scripts', array($this, 'head_scripts'));
        
        add_action('wp_ajax_getUnlockedPosts', array($this,'getUnlockedPosts'));
       
        add_action('wp_ajax_nopriv_getUnlockedPosts', array($this,'getUnlockedPosts'));//for users that are not logged in.

        
        add_shortcode('thrive', array($this, 'thrive_shortcode'));
         
         
        register_activation_hook(__FILE__, array($this, 'ssa_thrive_install'));

        register_deactivation_hook(__FILE__, array($this, 'ssa_thrive_deinstall'));
        
        
        // triggers for checking the users stats (did this action unlock something?) //
        add_action('comment_post', array($this, 'update_user_stats'));
        add_action('save_post', array($this, 'update_user_stats'));
        add_action('wp_login', array($this, 'update_user_stats'));
        add_action('deleted_post', array($this, 'update_user_stats'));
        add_action('deleted_comment', array($this, 'update_user_stats'));
        
         
    }
    
    public function ssa_thrive_init() {
        
        $this -> engine = new ThriveEngine();
        $this -> view = new ThriveView();
        $this -> options = get_option('thrive_options');
        
        
    }
    
    function getUnlockedPosts(){
            
            if ($this -> options['show_alert_box']) {// set by the admin options
                        
                // check and compare this users activity to the used rules. With cache 
                $this -> engine -> checkUser();
                
                $listUnlockedPosts = $this -> engine -> getUnlockedPosts();
                
                $this -> view -> setlistUnlockedPosts( $listUnlockedPosts ) ;
                
                $this -> view -> alertUser($this -> options['alert_level']);
            
            }
             
            die;
        
    }

    function update_user_stats(){
            
            // check and compare this users activity to the used rules. Without cache           
            $this -> engine -> checkUser(false);
                    
    }




    public function head_scripts() {

       
        wp_enqueue_style('thrive_style', THRIVE_URL . 'front/makeup/thrive.css');
        wp_enqueue_script('thrive_script', THRIVE_URL . 'front/js/thrive.js');
        

    }

    public function thrive_shortcode($atts, $content = null) {
        extract(shortcode_atts(array('rule' => "", 'descr' => "", ), $atts));

        $results = array();
        
        // only show on single page
        if ($this -> options['show_archive'] || is_single() || is_page()) {

       

            // this starts the calculation engine and takes all "rules" as input key
            
            $this -> engine -> checkRule($rule);
            $results = $this -> engine -> getResults();
            
            if($this -> engine -> hasFailed()) {          
            
                
                $this -> view -> setResults($results);
                $this -> view -> setDescription($descr);
                $this -> view -> setContent($content);
                return $this -> view -> makeMessageBox();
            
            } else {
                
                $content = apply_filters('the_content', $content);
                $content = str_replace(']]>', ']]&gt;', $content);
                return $content;
                
            }
            
            
        }

    }
    
    

    // INSTALL / DEINSTALL
    public function ssa_thrive_install() {
      //  include (THRIVE_DIR . 'admin/install/install.php');
    }

    public function ssa_thrive_deinstall() {
        //include (THRIVE_DIR . 'admin/install/deinstall.php');
    }


    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }



}
