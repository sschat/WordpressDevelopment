<?php
/*
Plugin Name: New post mail publisher
Plugin URI:
Version: 1.0.1
Desciption: On new mail send all your users a notification via mail
Author: Sander Schat.
Author URI: http://www.yeon.nl
Text Domain:
Domain Path: /lang
License:
 *
*/


/*
 * CHANGE LOG
 * 
 * 1.0.1 minor update changes
 * 1.0: Initial version
 * 
 */

/* 
 * Users will get extra profile setting: Dont receive updates
 * Back end post will get checkbox to send mail, on publish / save
 * 
 * When send, save the result, so no post email will be send twice, unless manually overwritten
 * 
 */
 
class ssa_newpostmail {

    function __construct(){
        
        
        
        // ADD THE ADMIN MENU OPTIONS FOR THIS PLUGIN
        include('admin_menu.php');
        $admin_menu = new postmailer_adminmenu;
        $this -> options = get_option('postmailer_options');
        
        // ADD THE EXTRA PROFILE FIELD TO USER
        add_action('personal_options', array(&$this, 'show_extra_profile_field') );
        add_action('personal_options_update', array(&$this, 'show_extra_profile_field') );
        add_action('personal_options_update', array(&$this, 'save_extra_profile_fields') );
        add_action('edit_user_profile_update', array(&$this, 'save_extra_profile_fields') );
        
        // ADD THE OPTION TO THE POST
        add_action( 'admin_init', array(&$this, 'add_meta_box') , 1 );
        
        /* Do something with the data entered */
        add_action( 'publish_post', array(&$this, 'save_postdata'), 10, 2 ); 
        
       
        
    }
    

   

    function install(){
        global $wpdb;
        
        $sql = "delete from " . $wpdb -> prefix . "usermeta where meta_key = 'user_post_subscription' ";
        $wpdb -> query($sql);
        
        
        $sql = "insert " . $wpdb -> prefix . "usermeta
                    (user_id, meta_key, meta_value)
                    
                    SELECT u.ID, 'user_post_subscription', '1' 
                    FROM " . $wpdb -> prefix . "users u;";

        $wpdb -> query($sql);
       
    }
    
    /*
     * Show the extra field in the profile page
     * 
     * */
     function show_extra_profile_field($user){
         
         $current_value = (get_the_author_meta('user_post_subscription', $user->ID)) ;
         
        $x = '<tr>   
            <th><label for="user_post_subscription">Mail ontvangen bij nieuwe posts?</label></th>
            <td>
                <input type="checkbox" name="user_post_subscription" id="user_post_subscription" value="1" ' . checked( "1", $current_value ) . ' style="width:34em"/>
            </td>
        </tr>
        ';
        
        echo $x;
    }

    /* 
     * SAVE THE OPTION TO THE USER 
     * 
     * */
    function save_extra_profile_fields($user_id) {
    
        if (!current_user_can('edit_user', $user_id))
            return false;
        
       
        update_usermeta($user_id, 'user_post_subscription', $_POST['user_post_subscription']);
    }
    
  
    /* 
     * SHOW THE "SEND MAIL" OPTION IN NEW METABOX ON POST PAGE
     * 
     */   
    function add_meta_box(){
        
        add_meta_box( 
              'verstuur_mail'
            , 'Verstuur mailing'
            , array( &$this, 'render_meta_box_content' )
            ,'post'
            ,'side' 
            ,'high'
        );
        
       
    }
    /**
     * Render Meta Box content
     */
    public function render_meta_box_content($post) 
    {
        // Use nonce for verification
        wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
     
     
       $x = '<p><label for="user_post_subscription">Bij opslaan: mail versturen naar leden bestand?</label></p>
             <p><input type="checkbox" name="send_mail_op_publish" id="send_mail_op_publish" value="1" />Ja</p> ';
        
        $send_mail_date = get_post_meta($post->ID, 'send_mail_date', true);
        if($send_mail_date){
                $x .= '
                <script>
                jQuery("#send_mail_op_publish").click(function(){
                    alert("Let op!\nEr is voor dit bericht al eerder een mail verstuurd. \nWeet u het zeker?");
                })
                </script>';
                
                $x .= "<p>Mail verstuurd op: " . date('H:i:s d-m-Y', $send_mail_date) . "</p>";
            
        }
        
        echo $x;
    }
    
    /* When the post is saved, saves our custom data */
    function save_postdata( $post_id, $post  ) {
        
      $this -> post = $post;
      $this -> post_id = $post_id;  
      
      // verify if this is an auto save routine. 
      // If it is our form has not been submitted, so we dont want to do anything
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
    
      // verify this came from the our screen and with proper authorization,
      // because save_post can be triggered at other times
    
      if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
          return;
    
      
      // Check permissions
      if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    
    
      // OK, we're authenticated: we need to find and save the data
      // ALS checkbox is selected, dan mail versturen en nieuwe tijdstip onthouden
      $publish_mail = $_POST['send_mail_op_publish'];
      
      if($publish_mail==1){
              
          $send = $this -> send_mail();
          
          if($send) update_post_meta($post_id, 'send_mail_date', time() ); 
          
      }  
      
    }
    
    
    /* 
     * GET THE LIST OF USERS IN
     * 
     */
    function get_users(){
        
        if( $this -> options['test_modus'] ){
                
            $templist = explode(';', $this -> options['test_account'] );
            
            $i = 0;            
            foreach($templist as $testmail){
                $i++;
                $list[$i]['email'] = $testmail;
                $list[$i]['display_name'] = $testmail;
            
            }
            
            
        } else {
            global $wpdb; 

            $sql = "select um.user_id, um2.meta_value as firstname, u.user_email as email, u.`display_name` as display_name
                    from
                    " . $wpdb -> prefix . "usermeta um
                    left join " . $wpdb -> prefix . "usermeta um2
                    on um.user_id = um2.user_id
                    left join " . $wpdb -> prefix . "users u
                    on um.user_id = u.ID
                    
                    where um.meta_key = 'user_post_subscription'
                    AND um.meta_value = 1
                    AND um2.meta_key='first_name'";

            $list = $wpdb -> get_results($sql, ARRAY_A);
            
        }
        
        /*TODO make distinct list of users, no doubles */
            
        return $list;
        
    }
    
    function create_excerpt($length) {
       
        $text = $this -> post -> post_content;
        
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]gt;', $text);
        $text = strip_shortcodes($text);
        $text = strip_tags($text);  
            
        $words = explode(' ', strip_tags($text), $length + 1);  
        if (count($words) > $length) :
            array_pop($words);
            array_push($words, '...');
            $text = implode(' ', $words);
        endif;
        return $text;
    }
    
    
    function get_post_part(){
        
        $postid = $this -> post_id;
        
        $permalink = get_permalink($postid);
        $thumb = get_the_post_thumbnail($postid, 'thumbnail');
        $thumbtarget = $permalink;
        
        $title   = '<h3><a href="' . $permalink . '">' . get_the_title($postid) . '</a></h3>';
        $output  = '<div class="imgholder" style="float:left; margin: 10px">' . $thumb . '</div>';
        $excerpt = '<p>' . $this -> create_excerpt(30) . '</p>';
        
        return $title . $excerpt;
        return $title . ($thumb ? $output : '') . $excerpt;
    }
    
    function compose_mail(){
        
        $body = $this -> options['email_body'];
        
        
        $body = str_replace("[posttitel]", $this -> post -> post_title, $body);
        $body = str_replace("[postlink]", get_permalink( $this -> post_id ) , $body);
        $body = str_replace("[postpart]", $this -> get_post_part() , $body);
        
        return $body;
    }
    /*
     *  COMPOSE AND SEND MAIL
     */
    function send_mail(){
        
        // get the message in 
        $title = $this -> options['email_title'];
        $title = str_replace("[posttitel]", $this -> post -> post_title, $title);
        
        $body = $this -> compose_mail();
         
        $headers = "From: " . ($this -> options['email_from']) . "\r\n";
        $headers .= "Reply-To: ". ($this -> options['email_reply']) . "\r\n";
       
        // get the users in        
        $send_list = $this -> get_users();
        
        foreach($send_list as $receiver):
            
            $name = $receiver['firstname'] ? $receiver['firstname'] : $receiver['display_name'];
           
            $send_body = str_replace("[naam]", $name, $body);
           
            $message = '<p>' . $send_body . '</p>';
            
           
           if($receiver['email']):
             
             
              // GET THE "BETTER MAILS" PLUGIN template in
              if (class_exists('WP_Better_Emails')) {
                //$wp_better_emails = new WP_Better_Emails();
                wp_mail( $receiver['email'], $title,  $message, $headers); 
              
              } else {
                    
                //$headers .= "CC: susan@example.com\r\n";
                $headers2 = $headers;
                $headers2 .= "MIME-Version: 1.0\r\n";
                $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            
                  
                wp_mail( $receiver['email'], $title,  $message, $headers2 );
              
              }
              
               
           endif;
            
            
        endforeach;
            

        return true;
    }
    
     public function ssa_plugin_updater() {
    
        include_once('updater.php');
        if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
    
            $config = array(
                'slug' => plugin_basename(__FILE__) ,
                'debug' => false,
            );
            $updater = new SSA_PLUGIN_UPDATE($config);
    
        }
    
    }
    
    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this -> $key);
        }
    }
    
    
}

if (class_exists('ssa_newpostmail')) {
    $start = new ssa_newpostmail;
    // ON PLUGIN ACTIVATION SET DEFAULT VALUE TO "ON"
    register_activation_hook(__FILE__, array($start,  'install'));
    
    /* update plugin version */
    add_action('init', array($start, 'ssa_plugin_updater') );
}

 

 