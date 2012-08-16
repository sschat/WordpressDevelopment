<?php

class followication {

    public $follower;
    public $object_id;
    public $object;


    public function __construct($id) {

       global $wpdb;


       $this->sqlTable = $wpdb->prefix . "ssa_followications";
       $this->sqlTableNoti = $wpdb->prefix . "ssa_notifications";
       $this->follower = $id;

    }

    public function set_object($id, $object){
        $this->object =  $object;
        $this->object_id = $id;
        return "done";
    }

    public function status(){

         return $this->_read_object();
    
    }

    public function count(){
        // returns the number of followers of an object
         return $this->_count_object();
    }

    public function list_following() {
        // returns a list of objects User is following
         return $this->_list_following();
    }

    public function list_followers() {
        // returns a list of followers of this object
         return $this->_list_followers();
    }


    public function follow() {
       global $notify;
       // to be sure: first check status: should be unfollowed
       // update it
       // check again: should be following
       // oke? Notify the world and return.
       $begin = $this->_read_object();

              
       if($begin=='follow'){
        $result = $this->_add_object();
       } else {
           return "E-F1";
       }

       if($result){
               $end = $this->_read_object();
       } else {
           return "E-F2";
       }

      if($end=='following'){
         
          $notify->insert("followication", "started following", $this->object, $this->object_id);
          return "following";
      
      }

      return "E-F99";

   }

   public function unfollow() {
        global $notify;

        // to be sure: first check status: should be following
       // update it
       // check again: should be unfollowed
       // oke? Notify the world and return.
       $begin = $this->_read_object();

       if($begin=='following'){
           $result = $this->_del_object();
       } else {
           return "E-U1";
       }

       if($result){
          $end = $this->_read_object();
       } else {
           return "E-U2";
       }

       if($end=="follow"){
           $notify->insert("followication", "stopped following", $this->object, $this->object_id);
           return "follow";
       }

       return "E-U99";
       
   }


    protected function _read_object(){

        global $wpdb;
        $id = $this->object_id;
        $object = $this->object;


        $sql = 'SELECT id FROM ' . $this->sqlTable . ' where object="' . $object . '" and object_id="' . $id . '" and follower_id="' . $this->follower . '"';
        $following = $wpdb->get_var( $wpdb->prepare( $sql ));
        
        if($following){

            return "following";

        } else {

            return "follow";
        }

   }

   protected function _count_object(){

        global $wpdb;
        $id = $this->object_id;
        $object = $this->object;

        $sql = 'SELECT count(id) FROM ' . $this->sqlTable . ' where object="' . $object . '" and object_id="' . $id . '" ';
        $count = $wpdb->get_var( $wpdb->prepare( $sql ));

        if($count){

            return $count;

        } else {

            return "0";
        }

   }

   protected function _list_following() {
        // returns a list of objects User is following
        global $wpdb;

        $sql = "SELECT object, object_id FROM $this->sqlTable  where follower_id=$this->follower;";

        $list = $wpdb->get_results($sql );

         if($list){

            return $list;

        } else {

            return "0";
        }
    }

    protected function _list_followers() {
        // returns a list of followers of this object
        global $wpdb;
        $id = $this->object_id;
        $object = $this->object;

        $sql = 'SELECT follower_id FROM ' . $this->sqlTable . ' where object="' . $object . '" and object_id="' . $id . '" ';
        $list = $wpdb->get_col( $wpdb->prepare( $sql ));

        if($list){

            return $list;

        } else {

            return "0";
            
        }
    }


   protected function _add_object(){
        global $wpdb;
        $id = $this->object_id;
        $object = $this->object;
        

        $wpdb->query(  $wpdb->prepare(
        "INSERT INTO $this->sqlTable
        (  object, object_id, follower_id, insert_time )
        VALUES ( %s, %s, %s, %s )",
        $object, $id, $this->follower, current_time('mysql')   )) ;

        // TODO
        // if false returned, log it to the plugin error log
        return $wpdb->insert_id;

   }

   protected function _del_object(){
        global $wpdb;
        $id = $this->object_id;
        $object = $this->object;

        $x = $wpdb->query( $wpdb->prepare(
	"DELETE FROM $this->sqlTable
	WHERE object = '%s'
	AND object_id = '%s'
        AND follower_id = %s",
        $object,
	$id,
        $this->follower
        ) );

        // TODO
        // if false returned, log it to the plugin error log
        return $x;

   }
   
  
    public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }


    

}
