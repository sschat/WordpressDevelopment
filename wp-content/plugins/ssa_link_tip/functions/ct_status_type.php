<?php
// ------- ADMIN STUFF -------- //

add_action('init', 'mylink_custom_init');
function mylink_custom_init()
{
  $labels = array(
    'name' => __('My Link', 'post type general name'),
    'singular_name' => __('My Link', 'post type singular name'),
    'add_new' => __('Maak nieuwe', 'mylink'),
    'add_new_item' => __('Maak nieuwe My Link aan'),
    'edit_item' => __('Edit My Link'),
    'new_item' => __('Nieuwe My Link'),
    'view_item' => __('Bekijk My Link'),
    'search_items' => __('Zoek My Link'),
    'not_found' =>  __('Geen My Links gevonden'),
    'not_found_in_trash' => __('Geen My Link gevonden in Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'My Links'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => false,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => 5,
    'supports' => array('title','editor','thumbnail'),
    'taxonomies'=>array('category', 'post_tag')
  ); 
  register_post_type('mylink',$args);
}

//add filter to ensure the text Book, or book, is displayed when user updates a book 
add_filter('post_updated_messages', 'mylink_updated_messages');
function mylink_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['mylink'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('My Link updated. <a href="%s">View mylink</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('My Link updated.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('My Link restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('My Link published. <a href="%s">View My Link</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('My Link saved.'),
    8 => sprintf( __('My Link submitted. <a target="_blank" href="%s">Preview My Link</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('My Link scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview mylink</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('My Link draft updated. <a target="_blank" href="%s">Preview mylink</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//display contextual help for Books
add_action( 'contextual_help', 'mylink_help_text', 10, 3 );

function mylink_help_text($contextual_help, $screen_id, $screen) {
  //$contextual_help .= var_dump($screen); // use this to help determine $screen->id
  if ('mylink' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a bedrijf:') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct genre such as Mystery, or Historic.') . '</li>' .
      '<li>' . __('Specify the correct writer of the book.  Remember that the Author module refers to you, the author of this book review.') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the book review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>' ;
  } elseif ( 'edit-book' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the mylinks blah blah.') . '</p>' ;
  }
  return $contextual_help;
}

require_once  "ct_status_type_mb.php";







//
// -- Update the admin list screen
//
add_action("manage_posts_custom_column",  "mylink_custom_columns");
add_filter("manage_edit-mylink_columns", "mylink_edit_columns");

function mylink_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => "MyLink Title",
    "description" => "Description",
    "type" => "Type",
    "link_title" => "Link Title"
  );

  return $columns;
}

function mylink_custom_columns($column){
  global $post;
  $custom = get_post_custom();


  switch ($column) {
    case "description":
      the_excerpt();
      break;
    case "link_title":
      echo $custom["link_title"][0];
      break;
    case "link_author":
      echo $custom["link_author"][0];
      break;
    case "link_descr":
      echo $custom["link_descr"][0];
      break;
      case "link_details":
      echo $custom["link_details"][0];
      break;
      case "link_code":
      echo $custom["link_code"][0];
      break;

  }
}
