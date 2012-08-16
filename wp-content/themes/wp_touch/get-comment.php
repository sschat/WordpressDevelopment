<?php

$comments_array = array();

$comments = get_comments('post_id='.$_GET['post_id']);

foreach ($comments as $c) :
    $comments_array[] = array(
        'id'         => $c->comment_ID,
        'post_id'    => $c->comment_post_ID,
        'author'     => $c->comment_author,
        'author_url' => $c->comment_author_url,
        'date'       => str_replace("-", "/", substr($c->comment_date, 0, 11)),
        'content'    => $c->comment_content
    );
endforeach;

echo json_encode(array("comments" => $comments_array));

?>
