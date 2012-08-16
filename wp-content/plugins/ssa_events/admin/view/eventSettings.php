<?php
/*
 * page used for events settings
 *
 */
?>


<div class="wrap">

    <div style="background:#ECECEC;border:1px solid #CCC;padding:10px;margin-top:15px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Instellingen Events</h2>
    </div>


    <form action="options.php" method="post">
    <?php settings_fields('event_options');  ?>
    <?php do_settings_sections('settings'); ?>
    
    <input name="Submit" type="submit" value="Sla op"  class="button-primary" />
    </form>

</div>