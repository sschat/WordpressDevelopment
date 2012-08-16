<?php

/*
 * Functions here will alter the admin panel of WP to the YEON activities brand
 */






/*
 * remove the admin wp logo
 * 
 */
 
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node('wp-logo');
}

