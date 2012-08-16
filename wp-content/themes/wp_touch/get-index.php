<?php

$posts = array();

$numberposts = 5;
$offset = (intval($_GET['page']) - 1) * $numberposts;

$num_posts = wp_count_posts('post');

$num_pages = intval($num_posts->publish / $numberposts);
if ($num_posts->publish % $numberposts != 0) {
    $num_pages++;
}

if ($page <= $num_pages) {

    $args = array( 'numberposts' => $numberposts, 'offset'=> $offset );
    $myposts = get_posts( $args );

    foreach( $myposts as $post ) : setup_postdata($post);
        $posts[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'permalink' => $post->guid,
            'date' => str_replace("-", "/", substr($post->post_date, 0, 11)),
            'author_id' => $post->post_author,
            'author' => get_userdata($post->post_author)->display_name,
            'content' => wpautop(strstr($post->post_content, '<!--more-->', true))
        );
    endforeach;

    echo(json_encode(array('posts' => $posts)));
}

?>