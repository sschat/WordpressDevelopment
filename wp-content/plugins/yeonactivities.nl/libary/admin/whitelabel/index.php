<?php

/*
 * Functions here will alter the admin panel of WP to the YEON activities brand
 */


/*
 * remove the admin footer
 * 
 */
if (! function_exists('dashboard_footer') ){
    function dashboard_footer () {
    echo 'CMS powered by Wordpress, guided by 
    <a href="http://www.yeonactivities.nl.com">Yeon activities</a>';
    }
}
add_filter('admin_footer_text', 'dashboard_footer');

/*
 * remove the admin wp logo
 * 
 */
 
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node('wp-logo');
}


