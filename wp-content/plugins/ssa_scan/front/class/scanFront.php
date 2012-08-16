<?php
/*
 * Called by index.php on front side of WP
 * 
 * Shortcode ["scan id='x'] is created
 * Javascript and styles are added to header
 * 
 * Shortcode takes the scan_id, and generates a div section for each found theme, 
 * plus adds a front and result page
 *  
 */

class ssa_scan_scanFront {

    private $scanID = null;
    private $questions = array();
    private $themes = array();
    private $sections = null;
    private $questionCount = array();
    private $userID = 0;
    private $scan_descr = '';
    private $options = array();
    
   
    public function __construct() {

        global $current_user;

        get_currentuserinfo();
        $this -> userID = $current_user -> ID;

        // define the shortcode
        add_shortcode('scan', array($this, 'scan_shortcode'));

        // get your files in
        add_action('wp_enqueue_scripts', array($this, 'head_scripts'));

    }

    public function head_scripts() {              
        
        //wp_deregister_script('jquery');
       // wp_register_script('jquery',  SCAN_URL . 'front/js/jquery.min.171.js');
        wp_enqueue_script('jquery');
        
        wp_enqueue_style('scan_style', SCAN_URL . 'makeup/scan.css');
        wp_enqueue_script('scan_script', SCAN_URL . 'front/js/scan.js' );
        wp_enqueue_style('button_tyle', SCAN_URL . 'makeup/jqtransformplugin/jqtransform.css');
        wp_enqueue_script('button_script', SCAN_URL . 'makeup/jqtransformplugin/jquery.jqtransform.js');
       
        wp_enqueue_style('custom_style', SCAN_URL . 'custom/custom.css');
       // wp_enqueue_script('modernizr', SCAN_URL . 'front/js/modernizr-2.0.6.min.js');
       
       
        
        // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
        wp_localize_script('scan_script', 'AjaxP', array(
        // URL to wp-admin/admin-ajax.php to process the request
            'ajaxurl' => admin_url('admin-ajax.php'),
                        
            
        // generate a nonce with a unique ID "myajax-post-comment-nonce"
        // so that you can check it later when an AJAX request is sent
            'postCommentNonce' => wp_create_nonce('scanQuestion-nonce')));

    }

    public function scan_shortcode($atts) {
        extract(shortcode_atts(array('id' => "1"), $atts));

        // this shortcode will lookup the scan id and present it

        // read themes and questions of this scan
        // present questions (by themes) on 1 page

        $this -> scanID = $id;
        $this -> showScan();

    }

    private function showScan() {
            
        include_once('csscolor.php');
        
        $this -> options = get_option('scan_options');
        
        //first read all the themes
        $this -> getThemes();
        
        // get the questions in page
        $this -> prepareQuestions();
        
        // generate the start page (after prepareQuestions, so we know how many themes we have)
        $this -> showStartPage();
       
        // generate the last page / summary
        $this -> showEndPage();
        

    }
    
    private function showStartPage(){
        
        echo "<div id='scan_startpage' name='{$this -> scanID}'>{$this -> scan_descr}";
        
        echo "<div id='scan_start' style='display:none' ><a href='javascript:;' id='$this->sections' class='btn_start'>Start</a></div>";
        
        echo '</div>';
    }
    
    private function showEndPage(){
        
        echo "<div id='scan_loading'></div>";
        echo "<div class='scan_endpage'></div>";
 
    }
    

    private function getThemes() {
        global $wpdb;

        $sql = "SELECT 
                 t.id, t.naam as theme_name, t.descr as theme_descr, t2.naam as scan_name, t2.descr as scan_descr
                from 
                {$wpdb->prefix}scan_types t
                left join
                {$wpdb->prefix}scan_types t2 on t.parent_id = t2.id
                where
                t.parent_id = %d
                order by t.id ASC";
                
                

        $safe_sql = $wpdb -> prepare($sql, $this -> scanID);
        $this -> themes = $wpdb -> get_results($safe_sql);
        $this -> scan_descr = $this -> themes[0]->scan_descr;
        
        
    }

    private function getQuestions($id) {
        global $wpdb;

        $sql = "SELECT 
                q.id as question_id, q.question, q.neg, q.pos
                from 
                {$wpdb->prefix}scan_questions q
                
                where
                q.parent_id = %d
                ";

        $safe_sql = $wpdb -> prepare($sql, $id);
        $this -> questions = $wpdb -> get_results($safe_sql);

    }

    private function prepareQuestions() {

        $cnt = 1;
        // per theme we create a table
        foreach ($this -> themes as $theme) {
              
            // i know, i know, this we can optimize for sure lator on!
            $this -> getQuestions($theme->id);

            $x .= "<div class='theme_section' id='$cnt' style='display:none'>";
            
            $x .= "<div class='theme_header' style='background-color:#{$this -> options['header_color']}'>";
            
            $x .= $this -> addThemePhase($cnt);
                        
            $x .= "<div class='theme_descr' style='color:#{$this -> options['text_color']}'>$theme->theme_descr</div></div>";
            
            
            $x .= "<form class='theme_form jqtransform'> ";

            foreach ($this -> questions as $question) {
                
                $var = $this -> getQuestionUser($question -> question_id);
                
                
                // first row had the question
                $x .= "<span class='question' id='qst_{$question -> question_id}' >{$question -> question}</span>";

                // second row had neg , options and pos
                $x .= "<table><tr><td id='neg'><span>{$question -> neg}</span></td>";

                $x .= "<td>";
                $x .= $this -> getRadioBtns($question -> question_id, $var, 5);
                $x .= "</td>";
                
                $x .= "<td id='pos'><span>{$question -> pos}</span></td></tr></table>";

            }

            $x .= " ";
            $x .= "<input type='submit'  name='prev'  class='prev' value='Vorige'/>";
            $x .= "<input type='submit'  name='next'  class='next' value='Volgende'/>";
            $x .= "</form>";
            $x .= "</div><!-- end theme section -->";
            $x .= "<div style='clear:both'></div>";
            
            $cnt++;
        }// foreach theme
        
        // the total sections is the cnt MINUS the last count
        $this -> sections = $cnt - 1;
        echo $x;

    }
    
    private function addThemePhase($current){
                
        
        $base = new CSS_Color($this -> options['header_color']);
          
        $x .= "<div class='theme_phases'>";
        
        foreach ($this -> themes as $key => $theme) {
            $count = $key+1; // key starts at 0
                
            $y = ($current == $count ? "current_header" : "");    
            
            $x .= "<div class='theme_phase $y' id='theme_phase_" . $y . "_$count' style='background-color: #" . $base->bg[ ($current == $count ? '+4' : '+2' ) ] . "'><h3>$theme->theme_name</h3></div>";
            
        }
        $x .= "</div>";
        
        return $x;
        
    }
    // this gets the current value of the question for this user (if any)
   private function getQuestionUser($id) {
        global $wpdb;

        $sql = "SELECT 
                r.answer
                from 
                {$wpdb->prefix}scan_results r 
                where
                r.question_id = %d
                and 
                r.user_id = %s
                ";

        //echo $sql;
        $safe_sql = $wpdb -> prepare($sql, $id, $this -> userID);
        return $wpdb -> get_var($safe_sql);

    }

   private function getRadioBtns($id, $var, $number){
               
       $i = 1;
        while ($i <= $number):
             $x .= "<input type='radio' name='$id'  class='qst_option' value='$i' " . ( $i == $var?'checked="checked"':'') . "/> <label>$i</label> ";
            $i++;
        endwhile;
       return $x;
   
   }
}
