<script type="text/javascript">        
jQuery(document).ready(function($) {

    $('.color_picker').jPicker({
          
          images:{clientPath: '/wp-content/plugins/ssa_thrive/admin/jpicker-1.1.6/images/'}
             
      });
      
      
    $("#thrive_border_radius").rangeinput();
    $("#thrive_width").rangeinput();
     $("#alert_level").rangeinput();       
});
</script>

                                            
                                            
                          
</script>
 


<div class="wrap">

    <div style="background:#ECECEC;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Thrive your community</h2>
    </div>


    <form action="options.php" method="post">
    <?php settings_fields('thrive_options'); ?>
    <?php do_settings_sections('page.main'); ?>
    
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>"  class="button-primary" />
    </form>

</div>