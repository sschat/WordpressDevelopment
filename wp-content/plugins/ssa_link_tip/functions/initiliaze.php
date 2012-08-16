<?php




function link_lookup_func( $atts ) {
	extract( shortcode_atts( array(
		'sort' => 'first_name'
             ), $atts ) );
        global $wpdb;

        ?>
        <div class="link_tip" style="float:right">
                <input type="text" id="status" size="60"  name="status" value=""/>*<span id="link_err"></span>
                <input type="text" id="my_title" size="60"  name="my_title" value=""/>*<span id="my_title_err"></span>
                <textarea id="my_reason" cols="20" rows="5" name="my_reason" ></textarea><br/>
                <span id="my_reason_err"></span>
                <br/>
                <button id="status_submit" >submit</button>
                <div id="status_result"></div>
                <div id="status_list"></div>
        </div>
        <?
       
}
add_shortcode( 'link_lookup', 'link_lookup_func' );



add_action( 'wp_enqueue_scripts', 'wpse30583_enqueue' );
function wpse30583_enqueue()
{
    // embed the javascript file that makes the AJAX request
    wp_enqueue_script( 'my-ajax-request',   LL_FRONT_URL . '/lookup.js' , array( 'jquery' ) );


   // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
    wp_localize_script( 'my-ajax-request', 'MyAjax', array(
    // URL to wp-admin/admin-ajax.php to process the request
    'ajaxurl'          => admin_url( 'admin-ajax.php' ),

    // generate a nonce with a unique ID "myajax-post-comment-nonce"
    // so that you can check it later when an AJAX request is sent
    'postCommentNonce' => wp_create_nonce( 'myajax-post-comment-nonce' )

    ));


}



// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
add_action( 'wp_ajax_nopriv_ajax_lookup_link', 'ajax_lookup_link' );
add_action( 'wp_ajax_ajax_lookup_link', 'ajax_lookup_link' );

function ajax_lookup_link() {

    global $wpdb;

    $nonce = $_POST['postCommentNonce'];
    $status = $_POST['status'];


    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    if ( ! wp_verify_nonce( $nonce, 'myajax-post-comment-nonce' ) )
        die ( 'Busted!');

    // ignore the request if the current user doesn't have
    // sufficient permissions

    //if ( current_user_can( 'edit_posts' ) ) {

    // Check the given status for urls

    extract_link($status);


    //}

    // IMPORTANT: don't forget to "exit"
    exit;
}


// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
add_action( 'wp_ajax_nopriv_add_status', 'add_status' );
add_action( 'wp_ajax_add_status', 'add_status' );

function add_status() {

    global $wpdb;

    $nonce = $_POST['postCommentNonce'];
    $status = $_POST['status'];
    $link = array();

    $link['mylink_type'] = $_POST['link_type'];
    $link['mylink_title'] = $_POST['link_title'];
    $link['mylink_author'] = $_POST['link_author'];
    $link['mylink_descr'] = $_POST['link_descr'];
    $link['mylink_details'] = $_POST['link_details'];
    $link['mylink_code'] = $_POST['link_code'];
                            
    $link['my_link'] = $_POST['my_link'];
    $link['my_title'] = $_POST['my_title'];
    $link['my_reason'] = $_POST['my_reason'];

    // check to see if the submitted nonce matches with the
    // generated nonce we created earlier
    if ( ! wp_verify_nonce( $nonce, 'myajax-post-comment-nonce' ) )
        die ( 'Busted!');

    // ignore the request if the current user doesn't have
    // sufficient permissions

    //if ( current_user_can( 'edit_posts' ) ) {

    // Check the given status for urls

    save_status($status, $link);


    //}

    // IMPORTANT: don't forget to "exit"
    exit;
}
