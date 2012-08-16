<?php
global $wpdb;
global $ssa_game_db_version;
$ssa_game_db_version = "1.0";


function ssa_game_install() {
   global $wpdb;
   global $ssa_game_db_version;
   $charset_collate = '';


    if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

            $sql = "CREATE TABLE IF NOT EXISTS `netwerk_game_phase` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(127) DEFAULT NULL,
            `description` text,
            `message` text,
            `image` text,
            `points` int(11) DEFAULT NULL,
            `group_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  $charset_collate; ";

            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_group` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(64) DEFAULT NULL,
            `descr` text,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM $charset_collate; ";

            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_rules` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(64) DEFAULT NULL,
            `descr` text,
            `message` text,
            `group_id` int(11) DEFAULT NULL,
            `moment` varchar(64) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM $charset_collate; ";


            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_rule_points` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `rule_id` int(11) DEFAULT NULL,
            `counter` int(11) DEFAULT NULL,
            `points` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM $charset_collate; ";


            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_status` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `group_id` int(11) DEFAULT NULL,
            `phase_id` int(11) DEFAULT NULL,
            `achievement_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM $charset_collate; ";



            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_counter` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `rule_id` int(11) DEFAULT NULL,
            `counter` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM $charset_collate; ";


            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_users` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `wp_user_id` int(11) DEFAULT NULL,
              `ip` varchar(64) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM $charset_collate; ";

            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_achievements` (
              `achi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(64) DEFAULT NULL,
              `descr` text,
              `message` text,
              `image` text,
              PRIMARY KEY (`achi_id`)
            ) ENGINE=MyISAM $charset_collate; ";

            $sql.="CREATE TABLE IF NOT EXISTS `netwerk_game_achievement_rules` (
              `achi_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `rule_id` int(11) DEFAULT NULL,
              `threshold` int(11) DEFAULT NULL,
              PRIMARY KEY (`achi_id`)
            ) ENGINE=MyISAM $charset_collate; ";

           require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
           dbDelta($sql);

           //TODO: create index on the object/object_id
            //$sql = "CREATE INDEX IF NOT EXISTS notifcations ON $foll_table_name (user_id(9))";
            //$wpdb->query($sql);



    add_option("ssa_game_db_version", $ssa_game_db_version);

}

//
//function game_content(){
//    global $game;
//
//
//
//}
//
//add_filter( 'the_content', 'game_content' );