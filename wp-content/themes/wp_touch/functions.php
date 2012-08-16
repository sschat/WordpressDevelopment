<?php

if ( isset( $_REQUEST['index'] ) ) {
	require_once( TEMPLATEPATH . '/get-index.php' );
	die();
}

if ( isset( $_REQUEST['single'] ) ) {
	require_once( TEMPLATEPATH . '/get-single.php' );
	die();
}

if ( isset( $_REQUEST['maxpage'] ) ) {
	require_once( TEMPLATEPATH . '/get-maxpage.php' );
	die();
}

if ( isset( $_REQUEST['insertcomment'] ) ) {
	require_once( TEMPLATEPATH . '/insert-comment.php' );
	die();
}

if ( isset( $_REQUEST['comment'] ) ) {
	require_once( TEMPLATEPATH . '/get-comment.php' );
	die();
}

?>
