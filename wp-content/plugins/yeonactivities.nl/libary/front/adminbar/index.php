<?php

 // only the admins get to see the admin bar
if (!current_user_can('manage_options')) {
    
  add_filter('show_admin_bar', '__return_false');

} else {

  add_filter('show_admin_bar', '__return_true');

}
     