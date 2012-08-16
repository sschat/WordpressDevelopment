<?php

class notification {

    // notify class will recieve input
    //    and update the big messager table
    //    also it will check if this notification must be forwarded/visible to others
    //        (followication)


    public $follower;



    public function __construct($id) {

       global $wpdb;


       $this->sqlTable = $wpdb->prefix . "ssa_notifications";
       $this->user = $id;

    }


    public function insert($action_type, $action, $object, $object_id){

        $id = $this->_insert($action_type, $action, $object, $object_id);

        return true;

    }


    public function read(){
        // will read the big table and return what is applicable
        //
        // filters: find the messages of the users he follows and are checked as "show" by user



    }



    protected function _insert($action_type, $action, $object, $object_id){
        // this adds a notification to the big table
        //
        // some type of notifications are "mail-worthy", some not
        // check the admin-page for settings
        global $wpdb;

        $wpdb->query(  $wpdb->prepare(
        "INSERT INTO $this->sqlTable
        (  object, object_id, user_id, action_type, action, insert_time )
        VALUES ( %s, %s, %s, %s, %s, %s )",
        $object, $object_id, $this->user, $action_type, $action, current_time('mysql')   )) ;


        // TODO
        // if false returned, log it to the plugin error log
        return $wpdb->insert_id;


   }



    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }




}
