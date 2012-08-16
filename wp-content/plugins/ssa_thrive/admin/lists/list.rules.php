<?php

class Rule_Table extends WP_List_Table {
   
    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'rule',     //singular name of the listed records
            'plural'    => 'rules',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );

    }


    function column_default($item, $column_name){
        switch($column_name){
            case 'rule_id':
            case 'descr':
            case 'amount':
            case 'object':
            case 'post_title':
            case 'days':
            case 'message':    
           
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_descr($item){

        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&rule=%s">Edit</a>',$_REQUEST['page'],'edit',$item['rule_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&rule=%s">Delete</a>',$_REQUEST['page'],'delete',$item['rule_id']),
        );

        //Return the title contents
        return sprintf('%1$s %3$s',
            /*$1%s*/ $item['descr'],
            /*$2%s*/ $item['rule_id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    
        
    }

  
    function column_post_title($item){
        if(!$item['post_title']) return;
        
        return sprintf(
            '(%2$s) %1$s',
            /*$1%s*/ $item['post_title'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['object_id']                //The value of the checkbox should be the record's id
        );
    }



    function get_columns(){
        $columns = array(
            //'ID'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'rule_id'        => 'ID', 
            'descr'     => 'Description',
            'amount'    => 'Amount',
            'object' => 'Object',
            'post_title' => 'on Post',
            'days' => 'within Days'
        );
        return $columns;
    }

   
    function get_sortable_columns() {
        $sortable_columns = array(
            'rule_id'  => array('rule_id', true),
            'object'     => array('object', false),     //true means its already sorted
            'price'    => array('price',false)
            
        );
        return $sortable_columns;
    }



    function prepare_items() {
        global $wpdb;

        $per_page = 99;


        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


   
        $this->_column_headers = array($columns, $hidden, $sortable);


     
        $sql = 'SELECT r.rule_id, r.descr , r.amount, r.object, r.object_id, p.post_title, r.days, r.price_id           
                    FROM ' . $wpdb->prefix. 'thrive_rules r
                    left join ' . $wpdb->posts .' p on r.object_id = p.ID;';


        $data = $wpdb -> get_results($sql, ARRAY_A );

    
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'ID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');


        $current_page = $this->get_pagenum();

   
        $total_items = count($data);

       
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);


        $this->items = $data;

      
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }

}


