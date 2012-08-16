<?php

/*
 * on activation or deactivation
 * call this script
 *
 */
class scanInstaller {

    private $tablename = null;
    private $sql = null;

    public function install() {

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

        $this -> addTableQuestions();
        $this -> addTableResults();
        $this -> addTableScores();
        $this -> addTableTypes();

    }

    private function addTableQuestions() {
        global $wpdb;
        
        $this -> tablename = $wpdb -> prefix . 'scan_questions';

        $this -> sql = "CREATE TABLE `{$this -> tablename}` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `parent_id` int(11) DEFAULT NULL,
                      `question` text ,
                      `neg` varchar(256) DEFAULT NULL,
                      `pos` varchar(256)  DEFAULT NULL,
                      `options` text ,
                      PRIMARY KEY (`id`),
                      KEY `parent_id` (`parent_id`)
                    );";

        $this -> addTable();

    }

    private function addTableResults() {
        global $wpdb;
        
        $this -> tablename = $wpdb -> prefix . 'scan_results';

        $this -> sql = "CREATE TABLE `{$this -> tablename}` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `user_id` int(11) DEFAULT NULL,
                      `question_id` int(11) DEFAULT NULL,
                      `answer` int(11) DEFAULT NULL,
                      `qst_explain` text ,
                      `insert_timestamp` timestamp NULL DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      UNIQUE KEY `u_user_question` (`user_id`,`question_id`),
                      KEY `user_id` (`user_id`),
                      KEY `question_id` (`question_id`)
                    );";

        $this -> addTable();

    }


    private function addTableScores() {
        global $wpdb;
        
        $this -> tablename = $wpdb -> prefix . 'scan_scores';

        $this -> sql = "CREATE TABLE `{$this -> tablename}` (
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `type` varchar(12)  DEFAULT NULL,
                      `parent_id` int(11) DEFAULT NULL,
                      `start` int(11) DEFAULT NULL,
                      `end` int(11) DEFAULT NULL,
                      `descr` text,
                      `options` text,
                      PRIMARY KEY (`id`),
                      KEY `parent_id` (`parent_id`)
                    );";

        $this -> addTable();

    }
    private function addTableTypes() {
        global $wpdb;
        
        $this -> tablename = $wpdb -> prefix . 'scan_types';

        $this -> sql = "CREATE TABLE `{$this -> tablename}` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `type` varchar(12)  DEFAULT NULL COMMENT 'scan or theme',
                  `naam` varchar(128)  DEFAULT NULL,
                  `descr` text ,
                  `options` text COMMENT 'might not be used',
                  `parent_id` int(11) DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `parent_id` (`parent_id`)
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
