<?php
class postmailer_adminmenu {
    
     function __construct(){
        
        // ADD THE ADMIN MENU OPTIONS FOR THIS PLUGIN
        add_action('admin_menu', array($this, 'newpostmenu'));
        
    }
    
    /* 
     * ADMIN MENU
     */ 
    public function newpostmenu() {

        $option_pages = add_options_page('PostMailer', 'PostMailer', 'manage_options', 'page.postmailer', array($this, 'postmailer_page'));
        
        //call register settings function
        add_action('admin_init', array($this, 'postmailer_settings_init'));

        // Admin head action only when this page is called
        add_action('admin_enqueue_scripts', array($this, 'postmailer_admin_scripts'));
        
        
        $this -> options = get_option('postmailer_options');
    }
    
    /**
     *
     * function will ONLY be called for the setting page
     * use for loading scripts etc
     * @return void
     *
     **/
    public function postmailer_admin_scripts($hook) {

        if ($hook == "toplevel_page_page.postmailer") {

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
    public function postmailer_settings_init() {

        register_setting('postmailer_options', 'postmailer_options');
      
        add_settings_section('postmailer_main', 'Post Mailer Settings', array($this, 'postmailer_settings_text'), 'page.postmailer');
        add_settings_field('postmailer_test_modus', 'Test modus?', array($this, 'postmailer_test_modus'), 'page.postmailer', 'postmailer_main', array('label_for' => 'postmailer_options[test_modus]'));
        add_settings_field('postmailer_test_account', 'Test account', array($this, 'postmailer_test_account'), 'page.postmailer', 'postmailer_main', array('label_for' => 'postmailer_options[test_account]'));
        add_settings_field('postmailer_email_title', 'Email Titel', array($this, 'postmailer_email_title'), 'page.postmailer', 'postmailer_main', array('label_for' => 'postmailer_options[email_title]'));
        add_settings_field('postmailer_email_body', 'Email Bericht', array($this, 'postmailer_email_body'), 'page.postmailer', 'postmailer_main', array('label_for' => 'postmailer_options[email_body]'));
        add_settings_field('postmailer_email_from', 'Email Afzender', array($this, 'postmailer_email_from'), 'page.postmailer', 'postmailer_main', array('label_for' => 'postmailer_options[email_from]'));
        add_settings_field('postmailer_email_reply', 'Email Reply-To', array($this, 'postmailer_email_reply'), 'page.postmailer', 'postmailer_main', array('label_for' => 'postmailer_options[email_reply]'));
        
       
    }
     /**
     *
     * Title text for the section
     *
     **/
    public function postmailer_settings_text() {
        //echo '<p>Settings voor de PostMailer publisher</p>';
    }


    // INPUT FIELD: TEST MODUS
    public function postmailer_test_modus() {

        
        echo "<input id='postmailer_test_modus' name='postmailer_options[test_modus]' type='checkbox' value='1' " . ($this -> options['test_modus'] ? 'checked' : '') . "/>
        <span class='description'>Mail wordt alleen naar test account gestuurd</span>";

    }
    // INPUT FIELD: TEST ACCOUNT
    public function postmailer_test_account() {

        
        echo "<input id='postmailer_test_account' name='postmailer_options[test_account]' type='text' value='" . ($this -> options['test_account'] ) . "' style='width:30em'/><br/>
        <span class='description'>Test accounts (Meerdere accounts? koppel dmv ';' ) </span>";

    }
    // INPUT FIELD: EMAIL TITLE
    public function postmailer_email_title() {

        
        echo "<input id='postmailer_email_title' name='postmailer_options[email_title]' type='text' value='" . ($this -> options['email_title'] ) . "' style='width:30em'/><br/>
        <span class='description'>Email titel - Post titel: [posttitel] </span>";

    }
    // INPUT FIELD: EMAIL BODY
    public function postmailer_email_body() {
        
        echo "<textarea id='postmailer_email_body' name='postmailer_options[email_body]'  rows='10' cols='54'>" . ($this -> options['email_body'] ) . "</textarea></br>
        <span class='description'>Post titel: [posttitel] - Naam : [naam] - HTML toegestaan</span>";

    }
     // INPUT FIELD: EMAIL FROM
    public function postmailer_email_from() {

        
        echo "<input id='postmailer_email_from' name='postmailer_options[email_from]' type='text' value='" . ($this -> options['email_from'] ) . "' style='width:30em'/><br/>
        <span class='description'>Email Afzender</span>";

    }
     // INPUT FIELD: EMAIL TITLE
    public function postmailer_email_reply() {

        
        echo "<input id='postmailer_email_reply' name='postmailer_options[email_reply]' type='text' value='" . ($this -> options['email_reply'] ) . "' style='width:30em'/><br/>
        <span class='description'>Email Reply-To</span>";

    }
  
    
    public function postmailer_page() {

        ?>
        <div class="wrap">
        
            <div style="background:#ECECEC;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <div id="icon-users" class="icon32"><br/></div>
            <h2>Verstuur mail bij nieuwe posts</h2>
            </div>
        
        
            <form action="options.php" method="post">
            <?php settings_fields('postmailer_options'); ?>
            <?php do_settings_sections('page.postmailer'); ?>
            
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
