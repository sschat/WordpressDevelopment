<?php

$time = current_time('mysql');

$comment_data = array(
    'comment_post_ID'       => $_POST['post_id'],
    'comment_author'        => $_POST['author'],
    'comment_author_email'  => $_POST['email'],
    'comment_author_url'    => $_POST['url'],
    'comment_content'       => $_POST['content'],
    'comment_parent'        => 0,
    'comment_author_IP'     => $_POST['author_ip'],
    'comment_agent'         => $_POST['author_agent'],
    'comment_date'          => $time,
    'comment_approved'      => 1
);

wp_insert_comment($comment_data);

echo '{"success": true}';

?>
