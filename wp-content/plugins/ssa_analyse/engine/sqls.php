<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function prep_sql($nr, $date_start, $date_end, $aantal, $only_posts){

if($nr==5)$post_id==$date_start;

// add this for ONLY the posts
$x = ($only_posts?" and p.postID > 1 and p.postID < 9999999":"");

switch ($nr) {
case '1':
//
// get count of pages over periode of time
$sql="SELECT count(c.pageID) as count, po.post_title as title, p.postID as post_id
FROM `ssa_Counterize` c
left join ssa_Counterize_Pages p on c.pageID = p.pageID
left join ssa_posts po on p.pageID = po.ID
where c.timestamp > '$date_start'
and c.timestamp < '$date_end' " . $x . "
group by c.pageID
order by count desc
limit $aantal ";
break;

case '2':
//
 // get the count of comments over periode of time
$sql="SELECT count(c.comment_post_ID) as count
, p.post_title as title
, p.post_author
, u.display_name as schrijver
FROM `ssa_comments` c
left join ssa_posts p on c.comment_post_ID = p.ID
left join ssa_users u on p.post_author = u.ID
where c.comment_date > '$date_start'
and c.comment_date < '$date_end'
group by c.comment_post_ID
order by count desc
limit $aantal ";
break;

case '3':
//
 // get posts of trainers and their comments
$sql="SELECT u.display_name as trainer, p.post_title as title, p.post_content as vraag,  p.post_date as date, c.comment_content as content, c.comment_date as cdate, c.comment_author as schrijver, cr.ck_rating_up as love
FROM ssa_users u
LEFT JOIN ssa_usermeta m ON u.ID = m.user_id
LEFT JOIN ssa_posts p on u.ID = p.post_author";

$sql .= " left join ssa_comments c on p.ID = c.comment_post_ID ";
$sql .= " left join ssa_comment_rating cr on cr.ck_comment_id = c.comment_ID ";
$sql .= " WHERE m.meta_key =  'user_trainer'
and p.post_date > '$date_start'
and p.post_date < '$date_end'";
break;

case '4':
//
 // get all comments trainers gave on a subject
$sql="SELECT u.display_name as schrijver, p.post_title as title, c.comment_content as bericht, c.comment_date as date
FROM ssa_users u
LEFT JOIN ssa_usermeta m ON u.ID = m.user_id
left join ssa_comments c on u.ID = c.user_id
left join ssa_posts p on c.comment_post_ID = p.ID
WHERE m.meta_key =  'user_trainer'
and c.comment_date > '$date_start'
and c.comment_date < '$date_end'";
break;

case '5':
//
// the lifecycle of a single post and the views it get per day
$sql="SELECT count(p.postID), DATE_FORMAT(c.timestamp, '%y-%m-%d') as day
FROM ssa_Counterize_Pages p
left join ssa_Counterize c on p.pageID = c.pageID
where
postID = $post_id
group by day
ORDER BY  `c`.`timestamp` asc";
break;


case '6':
//
// comment activity of user during period of time
$sql="SELECT count(c.user_id) as c, comment_author as schrijver
FROM `ssa_comments` c
where c.comment_date > '$date_start'
and c.comment_date < '$date_end'
group by c.user_id
order by c desc";
break;

case '7':
//
// post activity of user during period of time
$sql="SELECT count(p.post_author) as c, p.post_author, u.display_name
FROM `ssa_posts` p
left join ssa_users u on p.post_author = u.ID
where p.post_date > '$date_start'
and p.post_date < '$date_end'
and p.post_type='post'
group by p.post_author
order by c desc";
break;
}

return $sql;
}


function get_results($nr, $date_start, $date_end, $aantal = '10', $only_post = false){
    global $wpdb;

    $sql=prep_sql($nr, $date_start, $date_end, $aantal, $only_post);

    //echo $sql;
    
    $results = $wpdb->get_results( $sql  );

    return $results;
    
    
}

