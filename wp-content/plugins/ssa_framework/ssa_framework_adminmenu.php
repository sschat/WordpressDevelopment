<?php

class ssa_framework_adminmenu {
    
     function __construct(){
        
        // ADD THE ADMIN MENU OPTIONS FOR THIS PLUGIN
        add_action('admin_menu', array($this, 'ssa_framework_menu'));
        
    }
    
    /* 
     * ADMIN MENU
     */ 
    public function ssa_framework_menu() {

        $option_pages = add_options_page('Framework', 'Framework', 'manage_options', 'page.ssa_framework', array($this, 'ssa_framework_page'));
        
        //call register settings function
        add_action('admin_init', array($this, 'ssa_framework_settings_init'));

        // Admin head action only when this page is called
        add_action('admin_enqueue_scripts', array($this, 'ssa_framework_admin_scripts'));
        
        
        $this -> options = get_option('ssa_framework_options');
    }
    
    /**
     *
     * function will ONLY be called for the setting page
     * use for loading scripts etc
     * @return void
     *
     **/
    public function ssa_framework_admin_scripts($hook) {

        if ($hook == "toplevel_page_page.ssa_framework") {

            //wp_enqueue_script("jquery");
            //wp_enqueue_script("jquery_tools_js", THRIVE_URL . '/admin/js/jquery.tools.min.js');
            //wp_enqueue_style("tabs_css", THRIVE_URL . '/admin/css/admin.css');
        
        }

    }


     /**
     *
     * Register the indiviuel settings for the admin pages. Add yours here and define it in a new function
     * @return void
     *
     **/
    public function ssa_framework_settings_init() {

        register_setting('ssa_framework_options', 'ssa_framework_options');
      
        add_settings_section('ssa_framework_main', 'Post Mailer Settings', array($this, 'ssa_framework_settings_text'), 'page.ssa_framework');
        add_settings_field('ssa_framework_test_modus', 'Test modus?', array($this, 'ssa_framework_test_modus'), 'page.ssa_framework', 'ssa_framework_main', array('label_for' => 'ssa_framework_options[test_modus]'));
        add_settings_field('ssa_framework_test_account', 'Test account', array($this, 'ssa_framework_test_account'), 'page.ssa_framework', 'ssa_framework_main', array('label_for' => 'ssa_framework_options[test_account]'));
        add_settings_field('ssa_framework_email_title', 'Email Titel', array($this, 'ssa_framework_email_title'), 'page.ssa_framework', 'ssa_framework_main', array('label_for' => 'ssa_framework_options[email_title]'));
        add_settings_field('ssa_framework_email_body', 'Email Bericht', array($this, 'ssa_framework_email_body'), 'page.ssa_framework', 'ssa_framework_main', array('label_for' => 'ssa_framework_options[email_body]'));
        add_settings_field('ssa_framework_email_from', 'Email Afzender', array($this, 'ssa_framework_email_from'), 'page.ssa_framework', 'ssa_framework_main', array('label_for' => 'ssa_framework_options[email_from]'));
        add_settings_field('ssa_framework_email_reply', 'Email Reply-To', array($this, 'ssa_framework_email_reply'), 'page.ssa_framework', 'ssa_framework_main', array('label_for' => 'ssa_framework_options[email_reply]'));
        
       
    }
     /**
     *
     * Title text for the section
     *
     **/
    public function ssa_framework_settings_text() {
        //echo '<p>Settings voor de ssa_framework publisher</p>';
    }


    // INPUT FIELD: TEST MODUS
    public function ssa_framework_test_modus() {

        
        echo "<input id='ssa_framework_test_modus' name='ssa_framework_options[test_modus]' type='checkbox' value='1' " . ($this -> options['test_modus'] ? 'checked' : '') . "/>
        <span class='description'>Mail wordt alleen naar test account gestuurd</span>";

    }
    // INPUT FIELD: TEST ACCOUNT
    public function ssa_framework_test_account() {

        
        echo "<input id='ssa_framework_test_account' name='ssa_framework_options[test_account]' type='text' value='" . ($this -> options['test_account'] ) . "' style='width:30em'/><br/>
        <span class='description'>Test accounts (Meerdere accounts? koppel dmv ';' ) </span>";

    }
    // INPUT FIELD: EMAIL TITLE
    public function ssa_framework_email_title() {

        
        echo "<input id='ssa_framework_email_title' name='ssa_framework_options[email_title]' type='text' value='" . ($this -> options['email_title'] ) . "' style='width:30em'/><br/>
        <span class='description'>Email titel - Post titel: [posttitel] </span>";

    }
    // INPUT FIELD: EMAIL BODY
    public function ssa_framework_email_body() {
        
        echo "<textarea id='ssa_framework_email_body' name='ssa_framework_options[email_body]'  rows='10' cols='54'>" . ($this -> options['email_body'] ) . "</textarea></br>
        <span class='description'>Post titel: [posttitel] - Naam : [naam] - HTML toegestaan</span>";

    }
     // INPUT FIELD: EMAIL FROM
    public function ssa_framework_email_from() {

        
        echo "<input id='ssa_framework_email_from' name='ssa_framework_options[email_from]' type='text' value='" . ($this -> options['email_from'] ) . "' style='width:30em'/><br/>
        <span class='description'>Email Afzender</span>";

    }
     // INPUT FIELD: EMAIL TITLE
    public function ssa_framework_email_reply() {

        
        echo "<input id='ssa_framework_email_reply' name='ssa_framework_options[email_reply]' type='text' value='" . ($this -> options['email_reply'] ) . "' style='width:30em'/><br/>
        <span class='description'>Email Reply-To</span>";

    }
  
    
    public function ssa_framework_page() {

        ?>
        <div class="wrap">
        
            <div style="background:#ECECEC;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <div id="icon-users" class="icon32"><br/></div>
            <h2>Verstuur mail bij nieuwe posts</h2>
            </div>
        
        
            <form action="options.php" method="post">
            <?php settings_fields('ssa_framework_options'); ?>
            <?php do_settings_sections('page.ssa_framework'); ?>
            
            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>"  class="button-primary" />
            </form>
        
        </div>
        <?
    }



     public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }
    
}