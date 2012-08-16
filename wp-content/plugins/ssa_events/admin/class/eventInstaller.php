<?php

/*
 * on activation or deactivation
 * call this script
 *
 */
class eventInstaller {

    private $tablename = null;
    private $sql = null;

    public function install() {

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

        $this -> addTableEvents();
        $this -> addTableAttendees();

    }

    private function addTableEvents() {
        global $wpdb;
        
        $this -> tablename = $wpdb -> prefix . 'events';

        $this -> sql = "CREATE TABLE `{$this -> tablename}` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `ename` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
              `efrom` timestamp NULL DEFAULT NULL,
              `till` timestamp NULL DEFAULT NULL,
              `amount` int(11) DEFAULT NULL,
              `info` text COLLATE utf8_unicode_ci COMMENT 'just a field for making notes',
              PRIMARY KEY (`id`)
            );";

        $this -> addTable();

    }

    private function addTableAttendees() {
        global $wpdb;
        
        $this -> tablename = $wpdb -> prefix . 'events_attendees';

        $this -> sql = "CREATE TABLE `{$this -> tablename}` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `user_id` int(11) DEFAULT NULL,
                      `event_id` int(11) DEFAULT NULL,
                      `status` int(1) DEFAULT NULL COMMENT '0= no, 1 = maybe, 2 = attend',
                      `register_page` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
                      `time_insert` timestamp NULL DEFAULT NULL,
                      `time_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `user_event` (`user_id`,`event_id`)
                    );";

        $this -> addTable();

    }


   


    private function addTable() {

       // if tablename is found, this will not pass
        if ($this -> checkTable($this -> tablename)) {

            dbDelta($this -> sql);
            
        }

    }

    private function checkTable($tablename) {
        global $wpdb;
        
        
        $x = ( $wpdb -> get_var("SHOW TABLES LIKE '$tablename'") != $tablename );
        return $x;
        
        
    }

    public function deinstall() {
        
        // none at this moment

    }

}
