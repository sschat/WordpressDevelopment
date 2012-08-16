<?php

$numberposts = 5;

$num_posts = wp_count_posts('post');

$max_pages = intval($num_posts->publish / $numberposts);
if ($num_posts->publish % $numberposts != 0) {
    $max_pages++;
}

echo $max_pages;

?>
