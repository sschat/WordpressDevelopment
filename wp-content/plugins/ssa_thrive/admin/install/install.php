<?php

global $wpdb;

add_option("thrive_db_version", "1.0");
$table_name = $wpdb->prefix . "thrive_rules"; 

$charset_collate = '';
if (!empty($wpdb->charset))
        $charset_charset = "DEFAULT CHARACTER SET $wpdb->charset";
if (!empty($wpdb->collate))
        $charset_collate .= " COLLATE $wpdb->collate";


$sql = "CREATE TABLE $table_name (
`rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`descr` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
`amount` int(2) DEFAULT NULL,
`object` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
`days` int(2) DEFAULT NULL,
`price_id` int(11) DEFAULT NULL,
`message` text COLLATE utf8_unicode_ci,
PRIMARY KEY  (`rule_id`)
 ) $charset_collate ;
";

/*
 * CREATE TABLE IF NOT EXISTS `aa_thrive_rules` (
  `rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `descr` varchar(127) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` int(2) DEFAULT NULL,
  `object` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `days` int(2) DEFAULT NULL,
  `price_id` int(11) DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`rule_id`)
)  ;
 * 
 * CREATE TABLE IF NOT EXISTS `ssa_thrive_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `unlocked` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`post_id`)
)
 */


require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

//TODO: create index on the object/object_id
//$sql = "CREATE INDEX IF NOT EXISTS notifcations ON $foll_table_name (user_id(9))";
//$wpdb->query($sql);
$message = "<strong>Please log in so we can show you the hidden content</strong>";
$rows_affected = $wpdb->insert( $table_name, 
       array( 
           'descr' => 'User should log in', 
           'object' => 'login',
           'message' => $message ) 
       );

