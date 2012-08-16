<?php

$posts = array();
$my_id = intval($_GET['id']);
$the_post = get_post($my_id);

$posts[] = array(
    'id' => $the_post->ID,
    'title' => $the_post->post_title,
    'permalink' => $the_post->guid,
    'date' => str_replace("-", "/", substr($the_post->post_date, 0, 11)),
    'author_id' => $the_post->post_author,
    'author' => get_userdata($the_post->post_author)->display_name,
    'content' => wpautop($the_post->post_content)
);

echo(json_encode(array('posts' => $posts)));

?>