<?php
// function to parse a video <entry>
    function parseVideoEntry($entry) {      
      $obj= new stdClass;
      
      // get nodes in media: namespace for media information
      $media = $entry->children('http://search.yahoo.com/mrss/');
      $obj->title = $media->group->title;
      $obj->description = $media->group->description;
      
      // get video player URL
      $attrs = $media->group->player->attributes();
      $obj->watchURL = $attrs['url']; 
      
      // get video thumbnail
      $attrs = $media->group->thumbnail[0]->attributes();
      $obj->thumbnailURL = $attrs['url']; 
            
      // get <yt:duration> node for video length
      $yt = $media->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->duration->attributes();
      $obj->length = $attrs['seconds']; 
      
      // get <yt:stats> node for viewer statistics
      $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->statistics->attributes();
      $obj->viewCount = $attrs['viewCount']; 
      
      // get <gd:rating> node for video ratings
      $gd = $entry->children('http://schemas.google.com/g/2005'); 
      if ($gd->rating) { 
        $attrs = $gd->rating->attributes();
        $obj->rating = $attrs['average']; 
      } else {
        $obj->rating = 0;         
      }
        
      // get <gd:comments> node for video comments
      $gd = $entry->children('http://schemas.google.com/g/2005');
      if ($gd->comments->feedLink) { 
        $attrs = $gd->comments->feedLink->attributes();
        $obj->commentsURL = $attrs['href']; 
        $obj->commentsCount = $attrs['countHint']; 
      }
      
      
    
      // return object to caller  
      return $obj;      
    }   
    
    
    
function extract_link($input){
   // read input and extract the url
    
    preg_match('/(http:\/\/)(.*)/', $input, $link);
    if (empty($link[0])) {
    
            echo $input;

    } else {

           extract_site($link[0]);

    }

}

function extract_site($link){
    // read link and see if we can recognize it

    // slice and dice the http stuff

    $host = parse_url( $link, PHP_URL_HOST );
    $hostArray = explode(".", $host);

    $path = parse_url( $link, PHP_URL_PATH );
    parse_str( parse_url( $link, PHP_URL_QUERY ), $url_vars );

    //echo $host . "<br/>";
    //echo $path . "<br/>";
    //print_r($url_vars);

    $input = $link;
    $type = "ALL";
    // know urls
    //
    // 1: youtube / youtu
    if (in_array("youtube", $hostArray)   ) {

        $type = "YOUTUBE";
        $input = $url_vars['v'];

    }
    if (in_array("youtu", $hostArray)   ) {

        $type = "YOUTUBE";
        $input = trim($path, "/");

    }
    // 2: vimeo
    if (in_array("vimeo", $hostArray)   ) {

        $type = "VIMEO";
        $input = trim($path, "/");
        

    }
    // 3: bol
    if (in_array("bol", $hostArray)   ) {

        $type = "BOL";
        $input = $link;

    }
        // 3: bol
    if (in_array("managementboek", $hostArray) || in_array('mgtbk', $hostArray)  ) {

        $type = "MB";
        $input = $link;

    }

    get_content($type, $input);


}


function get_content($type, $input){
    $data = array();
    
    if($type=="YOUTUBE"){

            $video_stats = 'http://gdata.youtube.com/feeds/api/videos/' . $input;
            $xml             = simplexml_load_file($video_stats);

            // parse video entry
            $data['img'] = '<iframe width="210" height="157" src="http://www.youtube.com/embed/' . $input . '" frameborder="0" allowfullscreen></iframe>';
            
            $data['title'] =  (string)$xml->title;
            $data['by']   = (string)$xml->author->name;
            $data['desc'] = (string)$xml->content;
           
            $data['type'] = "video";
    }

    if($type=="VIMEO"){

            $video_stats = 'http://vimeo.com/api/oembed.xml?url=http%3A//vimeo.com/' . $input;
            $xml             = simplexml_load_file($video_stats);

            $data['img'] = '<iframe src="http://player.vimeo.com/video/' . $input . '" width="210" height="157" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
            $data['title'] =  (string)$xml->title;
            $data['by']   = (string)$xml->author_name;
            $data['desc'] = (string)$xml->description;
            $data['type'] = "video";
    }

    if($type=="BOL"){

        include( 'simple_html_dom.php');
        $data = array();

        $html = new simple_html_dom();
        $html->load_file($input);

        $image = $html->find('img[class=product_image_regular]' , 0);
        $data['img'] = "<img src='$image->src' />";

        $title= $html->find('h1[class=bol_header]' , 0);
        $data['title']=$title->first_child()->outertext;
        
        $data['type'] = "book";
        // BOOK DETAILS
        $details = $html->find('div[class=product_creator]' , 0);
        $data['by'] = $details->children(0)->outertext;
        $details = $html->find('p[class=small_details]' , 0);
        $data['pages'] = $details->outertext;
        

        // BOOK DESCR
        $book_details = $html->find('div[class=product_description]' , 0);
        $data['desc'] = $book_details->children(0)->outertext;

    }

        if($type=="MB"){

        include('simple_html_dom.php');
        $data = array();

        $html = new simple_html_dom();
        $html->load_file($input);

        $image = $html->find('div[class=img-holder]' , 0);
        $data['img'] = $image;

        $data['title'] = $html->find('h1' , 0);
        $data['type'] = "book";
        // BOOK DETAILS
        $details = $html->find('p[class=author]' , 0);
        $data['by'] = $details->children(0)->outertext;

        $details = $html->find('p[class=small_details]' , 0);
        $data['pages'] = $details->outertext;


        // BOOK DESCR
        $book_details = $html->find('div[class=expandable]' , 0);
        $data['desc'] = $book_details->children(0)->outertext;

    }



    show_content($data);

}

function show_content($data){

    
    
     echo "<div id='sr'>";
        echo "<div id='sr_vid' style='float:left;width:210px;margin: 10px;'>";
                  echo $data['img'];
        echo "</div>";
        echo "<div id='sr_det' style='float:left;width:300px;margin: 10px;'>";
            echo "<h3 id='sr_title'>" . $data['title'] . "</h3>";
            echo "by <span  id='sr_by' style='text-transform:lowercase;font-weight:bold'>" . $data['by'] . "</span>";
            echo "<p style='font-size:7px'  id='sr_details'>" . $data['pages'] . "</p>";
            echo "<p  id='sr_desc'>" .  substr( $data['desc'], 0, 175) . "</p>";
            echo "<input type='hidden' id='sr_type' value='{$data['type']}' />";
            
            
        echo "</div>";
        echo "</div>";
        
}



function save_status($status, $link){
    // save the status after submit
    
   $current_user = wp_get_current_user();
   
    $post = array(
         
          'comment_status' =>  'open', // 'closed' means no comments.
          'ping_status' => 'open',  // 'closed' means pingbacks or trackbacks turned off
          
          'post_author' => $current_user->ID , //The user ID number of the author.
          'post_category' => array(1), //Add some categories.
          'post_content' => $link['my_reason'], //The full text of the post.
          
          'post_status' => 'publish', //Set the status of the new post. 
          'post_title' =>$link['my_title'],  //The title of your post.
          'post_type' => 'mylink'  //You may want to insert a regular post, page, link, a menu item or some custom post type
         
        );  
    
    //print_r($link);
    
    $insert_id = wp_insert_post( $post, $wp_error ); 
    
    add_post_meta($insert_id, 'mylink_title', $link['mylink_title']);
    add_post_meta($insert_id, 'mylink_type', $link['mylink_type']);
    add_post_meta($insert_id, 'mylink_author', $link['mylink_author']);
    add_post_meta($insert_id, 'mylink_descr', $link['mylink_descr']);
    add_post_meta($insert_id, 'mylink_details', $link['mylink_details']);
    //add_post_meta($insert_id, 'link_code', $link['link_code']);
    
    
    
    if($insert_id){ 
        echo "saved succesfully";
   }
    else {
        echo "save failed!";
    }
    
}